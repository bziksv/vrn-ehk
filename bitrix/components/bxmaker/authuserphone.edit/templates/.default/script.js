;




/**
 * Обработка действий в компоненте изменения нмоера телефона
 *
 * @param b
 * @param $
 *
 * @returns {boolean}
 *
 * @emits bxmaker.authuserphone.ajax {request, result, params} - событие вызывается после получения ответа на ajax запрос (смена номера отправка кода)
 *
 * @constructor
 */
function BxmakerAuthUserphoneEdit(b, $) {
    if (b == undefined || b.hasClass('js_init_complete')) {
        return false;
    }

    var self = this, box = b, msgBox = box.find('.msg');
    var rand = box.attr('data-rand');
    var timeout = false;
    var paramsData = (!!window.BxmakerAuthUserPhoneEditData && !!window.BxmakerAuthUserPhoneEditData[rand] ? window.BxmakerAuthUserPhoneEditData[rand] : false);


    if (!paramsData) {
        return false;
    }

    box.addClass('js_init_complete');


    self.getMessage = function (name) {
        return ((!!paramsData.messages && !!paramsData.messages[name]) ? paramsData.messages[name] : '');
    };

    // show errors and messages
    self.showMsg = function (msg, error) {
        var msg = msg || null,
            error = error || false;

        if (!!msgBox === false) return;
        msgBox.removeClass('error success').empty();

        if (msg) {
            if (error) msgBox.addClass('error').html(msg);
            else msgBox.addClass('success').html(msg);
        }
    };

    // показываем капчу
    self.showCaptcha = function (param) {
        var cb = box.find('.cbaup_row.captcha');

        param.captcha_sid = param.captcha_sid || '';
        param.captcha_src = param.captcha_src || '';

        if (!cb.find('input[name="captcha_sid"]').length) {
            var html = '<input type="hidden" name="captcha_sid" value="' + param.captcha_sid + '"/>' +
                '<img src="' + param.captcha_src + '" title="' + self.getMessage('UPDATE_CAPTCHA_IMAGE') + '" alt=""/>' +
                '<span class="btn_captcha_reload" title="' + self.getMessage('UPDATE_CAPTCHA_IMAGE') + '"></span>' +
                '<input type="text" name="captcha_word" class="captcha_word" placeholder="' + self.getMessage('INPUT_CAPTHCA') + '"/>';

            cb.append(html).fadeIn(300);
        } else {
            cb.find('input[name="captcha_sid"]').val(param.captcha_sid);
            cb.find('img').attr('src', param.captcha_src);
        }
    };


    // btn enter
    box.find(".cbaup_btn").on("click", function () {
        var btn = $(this);

        if (btn.hasClass("preloader")) return false;
        btn.addClass("preloader");

        var data = {
            method: 'setPhone',
            phone: box.find('input[name="phone"]').val(),
            code: box.find('input[name="code"]').val()
        };

        data['parameters'] = paramsData['parameters'];
        data['template'] = paramsData['template'];
        data['siteId'] = paramsData['siteId'];
        data['sessid'] = BX.bitrix_sessid();

        if (box.find('input[name="captcha_sid"]').length) {
            data['captcha_sid'] = box.find('input[name="captcha_sid"]').val();
            data['captcha_word'] = box.find('input[name="captcha_word"]').val();
        }

        $.ajax({
            url: paramsData['ajaxUrl'],
            type: 'POST',
            dataType: 'json',
            data: data,
            error: function (r) {
                self.showMsg('Error connect to server!', true);
                btn.removeClass("preloader");
            },
            success: function (r) {

                // событие получения ответа на ajax запрос
                $(document).trigger('bxmaker.authuserphone.ajax', {
                    'params': paramsData,
                    'request': data,
                    'result': r,
                });

                btn.removeClass("preloader");

                if (!!r.response) {
                    self.showMsg(r.response.msg);

                    if (!!r.response.phone) {
                        box.find('.cur_phone_info').html(self.getMessage('PHONE_INFO_TEMPLATE').replace(/PHONE/, r.response.phone));

                        box.find('input[name="phone"]').val('');
                        box.find('input[name="code"]').val('');
                        timeout = 0;
                    }
                } else if (!!r.error) {

                    if (!!r.error && r.error.code == 'INVALID_SESSID' && r.error.more && r.error.more.sessid) {
                        BX.message({"bitrix_sessid": r.error.more.sessid});

                        var coutn = +box.find(".cbaup_btn").attr('data-count');
                        if (isNaN(coutn)) {
                            coutn = 0;
                        }
                        if (coutn <= 5) {
                            box.find(".cbaup_btn").attr('data-count', coutn + 1);

                            setTimeout(function () {
                                box.find(".cbaup_btn").click();
                            }, 100);
                        }
                        return false;
                    }

                    self.showMsg(r.error.msg, true);

                    //captcha
                    if (!!r.error.more.captcha_sid) {
                        self.showCaptcha(r.error.more);
                    }
                }


            }
        });
    });

    // btn send code
    box.find('.cbaup_btn_link').on("click", function () {
        var btn = $(this);

        if (btn.hasClass('preloader') || btn.hasClass('timeout')) return false;
        btn.addClass('preloader');

        var data = {
            method: 'sendCode',
            phone: box.find('input[name="phone"]').val()
        };

        data['parameters'] = paramsData['parameters'];
        data['template'] = paramsData['template'];
        data['siteId'] = paramsData['siteId'];
        data['sessid'] = BX.bitrix_sessid();

        if (box.find('input[name="captcha_sid"]').length) {
            data['captcha_sid'] = box.find('input[name="captcha_sid"]').val();
            data['captcha_word'] = box.find('input[name="captcha_word"]').val();
        }

        $.ajax({
            url: paramsData['ajaxUrl'],
            type: 'POST',
            dataType: 'json',
            data: data,
            error: function (r) {
                self.showMsg('Error connect to server!', true);
                btn.removeClass("preloader");
            },
            success: function (r) {

                // событие получения ответа на ajax запрос
                $(document).trigger('bxmaker.authuserphone.ajax', {
                    'params': paramsData,
                    'request': data,
                    'result': r,
                });


                if (!!r.response) {
                    self.showMsg(r.response.msg);

                    timeout = (!!r.response.time ? r.response.time : 59);

                    // индикатор
                    var smsInterval = setInterval(function () {
                        if (--timeout > 0) {
                            btn.text(self.getMessage('BTN_SEND_CODE_TIMEOUT').replace(/#TIMEOUT#/, timeout));
                        } else {
                            clearInterval(smsInterval);
                            btn.text(self.getMessage('BTN_SEND_CODE'));
                            btn.removeClass("timeout");
                        }
                    }, 1000);

                    //сразу отображаем
                    btn.text(self.getMessage('BTN_SEND_CODE_TIMEOUT').replace(/#TIMEOUT#/, timeout)).removeClass("preloader").addClass('timeout');
                } else if (!!r.error) {

                    btn.removeClass("preloader");

                    if (!!r.error && r.error.code == 'INVALID_SESSID' && r.error.more && r.error.more.sessid) {
                        BX.message({"bitrix_sessid": r.error.more.sessid});
                        return false;
                    }

                    self.showMsg(r.error.msg, true);


                    if (!!r.error.more && !!r.error.more.time) {
                        var smsInterval = setInterval(function () {
                            if (--timeout > 0) {
                                btn.text(self.getMessage('BTN_SEND_CODE_TIMEOUT').replace(/#TIMEOUT#/, timeout));
                            } else {
                                clearInterval(smsInterval);
                                btn.text(self.getMessage('BTN_SEND_CODE'));
                                btn.removeClass("timeout");
                            }
                        }, 1000);
                        btn.text(self.getMessage('BTN_SEND_CODE_TIMEOUT').replace(/#TIMEOUT#/, timeout)).removeClass("preloader").addClass('timeout');
                    }

                    //captcha
                    if (!!r.error.more.captcha_sid) {
                        self.showCaptcha(r.error.more);
                    }


                } else {
                    btn.removeClass("preloader");
                }


            }
        })
    });


    // обновление капчи
    box.on("click", '.cbaup_row.captcha img, .cbaup_row.captcha span', function () {
        var b = box.find('.cbaup_row.captcha');

        if (b.hasClass("preloader")) return false;
        b.addClass("preloader");

        var data = {};
        data['parameters'] = paramsData['parameters'];
        data['template'] = paramsData['template'];
        data['siteId'] = paramsData['siteId'];
        data['sessid'] = BX.bitrix_sessid();

        data['method'] = 'getCaptcha';

        $.ajax({
            url: paramsData['ajaxUrl'],
            type: 'POST',
            dataType: 'json',
            data: data,
            error: function (r) {
                self.showMsg('Error connect to server!', true);
                b.removeClass("preloader");
            },
            success: function (r) {

                // событие получения ответа на ajax запрос
                $(document).trigger('bxmaker.authuserphone.ajax', {
                    'params': paramsData,
                    'request': data,
                    'result': r,
                });

                b.removeClass("preloader");

                if (!!r.response) {
                    self.showCaptcha(r.response);
                } else if (!!r.error) {

                    if (!!r.error && r.error.code == 'INVALID_SESSID' && r.error.more && r.error.more.sessid) {
                        BX.message({"bitrix_sessid": r.error.more.sessid});
                        return false;
                    }
                }
            }
        });
    });


    // отправка при клике по кнопке enter
    box.find('input').on("keyup", function (e) {
        if (e.keyCode == 13) {
            box.find(".cbaup_btn").click();
        }
    });

}

// воркер --
function BxmakerAuthUserphoneEditWorker() {

    if (!!window.jQuery == false) {
        console.log('bxmaker.authserphone.edit - need jQuery');
        return true;
    }

    window.$ = window.jQuery;

    $('.c-bxmaker-authuserphone_edit-default-box').each(function () {
        new BxmakerAuthUserphoneEdit(jQuery(this), jQuery);
    });
}

//запуск ----
if (window.frameCacheVars !== undefined && !!window.frameCacheVars.AUTO_UPDATE) {
    BX.addCustomEvent("onFrameDataReceived", function (json) {
        setTimeout(function () {
            BxmakerAuthUserphoneEditWorker();
        }, 200);
    });
    BX.addCustomEvent("onFrameDataRequestFail", function (json) {
        setTimeout(function () {
            BxmakerAuthUserphoneEditWorker();
        }, 200);
    });
} else {
    BX.ready(function () {
        setTimeout(function () {
            BxmakerAuthUserphoneEditWorker();
        }, 200);
    });
}

