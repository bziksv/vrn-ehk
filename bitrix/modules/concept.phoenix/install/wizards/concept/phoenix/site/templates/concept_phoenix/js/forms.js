$(document).ready(function(){
        
    $("input[type='email'], input[type='text'], input[type='password'], textarea", "form").focus(function()
    {
        $(this).parent().removeClass("has-error");
    });
});

$(window).on("load", function() {
    cur_pos = $(document).scrollTop();
    $("input.urlpage").val(location.href);
});


$(document).on('change', "form input[type='file'].flat",
    function()
    {
        var inp = $(this);
        var btn = inp.parent();
        var lbl = btn.find("span.ex-file");
        var file_api = (window.File && window.FileReader && window.FileList && window.Blob) ? true : false;
    
        if (file_api && inp[0].files[0])
            file_name = inp[0].files[0].name;
        else{
            file_name = inp.val().replace("C:\\fakepath\\", '');
        }

        if (!file_name.length)
            return;

        if (lbl.is(":visible")) {
            lbl.text(file_name);

        }
        else{
            btn.text(file_name);
        }

        inp.parents(".clearfile-parent").find('input.phoenix_file_del').val('');

        
        btn.addClass('focus-anim for-download');
        inp.parents(".clearfile-parent").find(".clearfile").addClass('on');
    }
);

$(document).on('change', ".form.send input[type='file']",
    function(){

        var inp = $(this);
        var file_list_name = "";

        $(inp[0].files).each(
            function(key){
                file_list_name += "<span>"+inp[0].files[key].name+"</span><br/>";
            }
        );


        if(!file_list_name.length)
            return;

        inp.parents('form.send').addClass('file-download');
        inp.parents('label').find('.area-files-name').html(file_list_name);
        inp.parents('label').removeClass("area-file");
        inp.closest('.load-file').removeClass("has-error");

    }
);

$(document).on("click", "div.wrap-agree input[type='checkbox']", function() {
    $(this).parents("div.wrap-agree").removeClass("has-error");
});

function validGroupCheckbox(arParams){

    var error = 0;

    for (key in arParams){

        var cheked = false;

        for (var i = 0; i < arParams[key].length; i++){

            if(arParams[key][i].checked)
                cheked = true;
        }

        if(!cheked)
        {
            if(error == 0)
                error = 1;

            for (var i = 0; i < arParams[key].length; i++){

                $(arParams[key][i]).addClass('has-error');
            }
        }
    }

    return error;
}

function validGroupSelect(arParams){
    var error = 0;

    for (key in arParams){

        var cheked = false;

        for (var i = 0; i < arParams[key].length; i++){

            if(arParams[key][i].checked)
                cheked = true;
        }

        if(!cheked)
        {
            if(error == 0)
                error = 1;

            $(arParams[key][0]).parents(".form-select").addClass('has-error');
        }
    }
    return error;
}

function sendForm(arParams)
{
    var error = 0,
        formSendAll = new FormData();


    formSendAll.append("send", "Y");
    formSendAll.append("element", arParams.form_block.attr("id").replace("form-", ""));
    formSendAll.append("tmpl_path", $("input.tmpl_path").val());
    formSendAll.append("typeForm", arParams.typeForm);
    formSendAll.append("fromBasket", arParams.fromBasket);
    formSendAll.append("site_id", $("input.site_id").val());
    formSendAll.append("url", location.href);

    

    for (var i = 1; i <= 10; i++) {
        if($("input#custom-input-"+i).val().length>0)
            formSendAll.append("custom-input-"+i, $("input#custom-input-"+i).val());
    }

    for (var i = 1; i <= 3; i++) {
        if($("input#custom-dynamic-input-"+i).val().length>0)
            formSendAll.append("custom-dynamic-input-"+i, $("input#custom-dynamic-input-"+i).val());
    }


    if (typeof(arParams.agreecheck.val()) != "undefined") {
        if (!arParams.agreecheck.prop("checked")) {
            arParams.agreecheck.parents("div.wrap-agree").addClass("has-error");
            error = 1;
        }
    }

    $("input[type='email'], input[type='text'], input[type='password'], textarea", arParams.form_block).each(
        function(key, value)
        {
            if ($(this).hasClass("email") && $(this).val().length > 0) {
                if (!(
                        /^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,15}$/i
                    ).test($(this).val())) {
                    $(this).parent("div.input").addClass("has-error");
                    error = 1;
                }
            }
            if ($(this).hasClass("require")) {
                if ($(this).val().trim().length <= 0){
                    $(this).val("");
                    $(this).parent("div.input").addClass("has-error");
                    error = 1;
                }
            }
        }
    );

    $("input[type='file']", arParams.form_block).each(function(key, value)
    {

        if ($(this).hasClass("require"))
        {

            if ($(this).closest('.load-file').find('.area-file').length>0) {
                $(this).closest('.load-file').addClass("has-error");
                error = 1;
            }
        }
    });

    if (!$('.phoenix-modal').hasClass('form-modal'))
    {
        var otherHeight = 0;
        if ($('header').hasClass('fixed')) 
            otherHeight = $('.fix-board').outerHeight(
            true);
    }


    var arChecbox = {}, newKey = null;

    $("input.check-require[type='checkbox']", arParams.form_block).each(
        function(key, value)
        {
            newKey = $(this).attr('name').replace("[]", "");

            if(typeof arChecbox[newKey] == "undefined")
                arChecbox[newKey] = [];
            
            arChecbox[newKey].push(this);
        }
    );

    if(newKey && !error)
        error = validGroupCheckbox(arChecbox);

    delete arChecbox;
    newKey = null;

    var arRadio = {};
    $(".select-require input.opinion[type='radio']", arParams.form_block).each(
        function(key, value)
        {
            newKey = $(this).attr('name');

            if(typeof arRadio[newKey] == "undefined")
                arRadio[newKey] = [];
            
            arRadio[newKey].push(this);
        }
    );

    if(newKey && !error)
        error = validGroupSelect(arRadio);

    delete arRadio;
    newKey = null;

    
    if (error == 1)
    {

        if ($('.phoenix-modal').hasClass('form-modal'))
        {
            formAttentionScroll(
                arParams.form_block.find('.has-error:first').offset().top - arParams.form_block.parents('.phoenix-modal-dialog').offset().top, 
                ".phoenix-modal");
        } 
        else if (arParams.form_block.hasClass('form-cart'))
        {
            formAttentionScroll(
                arParams.form_block.find('.has-error:first').offset().top - arParams.form_block.find(".body").offset().top, 
                ".cart-parent");
        }
        else
        {
            formAttentionScroll(arParams.form_block.find('.has-error:first').offset().top - otherHeight, 
            "html:not(:animated), body:not(:animated)");

        }
    }

    if (error == 0)
    {
        

        arParams.form_block.css({
            "height": arParams.form_block.outerHeight() + "px"
        });


        var form_arr = arParams.form_block.find(':input,select,textarea').serializeArray();

        for (var i = 0; i < form_arr.length; i++)
        {
            if (form_arr[i].value.length > 0)
            { 
                formSendAll.append(form_arr[i].name, form_arr[i].value);
            }
        };

        if (arParams.form_block.hasClass('file-download'))
        {
            arParams.form_block.find('input[type=file]').each(function(key)
            {

                var inp = $(this);
                var file_list_name = "";

                $(inp[0].files).each(
                    function(k){
                        formSendAll.append(inp.attr('name'), inp[0].files[k], inp[0].files[k].name);
                    }
                );

            });
        }

        if(arParams.captchaToken.length>0)
            formSendAll.append("captchaToken", arParams.captchaToken);


        arParams.button.removeClass("active");
        arParams.load.addClass("active");

        setTimeout(function()
        {
            $.ajax({
                url: arParams.path,
                method: 'POST',
                contentType: false,
                processData: false,
                data: formSendAll,
                dataType: 'json',
                success: function(json)
                {
                    if(typeof (json.OK) != "undefined" )
                    {
                        console.log(json.CAPTCHA_SCORE);
                         
                        if(json.OK == "N")
                        {
                            arParams.button.addClass("active");
                            arParams.load.removeClass("active");
                        }

                        if (json.OK == "Y")
                        {

                            if(arParams.typeForm != "fast_order")
                            {
                                arParams.questions.removeClass("active");
                                arParams.thank.addClass("active");

                                if(typeof(arParams.linkHREF) != "undefined")
                                {
                                    if(typeof(arParams.linkHREFNew) != "undefined")
                                    {
                                        if(arParams.linkHREFNew == "Y")
                                        {
                                            setTimeout(function() {
                                                window.open(arParams.linkHREF, '_blank');
                                            }, 1000);      
                                        }
                                    }
                                    else
                                    {
                                        setTimeout(function() {
                                            window.location.href = arParams.linkHREF;
                                        }, 3300);
                                    }
                                    
                                }
                            }
                            $.ajax({
                                url: "/bitrix/tools/concept.phoenix/ajax/ajax_hit_page.php",
                                method: 'POST',
                                success: function(json) {}
                            });
                            

                            setTimeout(function()
                            {
                                if ($('.phoenix-modal').hasClass('form-modal'))
                                    formAttentionScroll(arParams.form_block.find('.thank').offset().top - arParams.form_block.parents(
                                        '.phoenix-modal-dialog').offset().top, ".phoenix-modal");
                                else if (arParams.form_block.hasClass('form-cart')) {
                                    formAttentionScroll(arParams.form_block.find('.thank').offset().top - arParams.form_block.find(
                                        ".body").offset().top, ".cart-parent");
                                } else {
                                    formAttentionScroll(arParams.form_block.find('.thank').offset().top -
                                        otherHeight, "html:not(:animated),body:not(:animated)");
                                }
                            }, 300);
                            

                            if(typeof(json.SCRIPTS) != "undefined")
                            {
                                if (json.SCRIPTS.length > 0)
                                {
                                    $('body').append("<script>"+json.SCRIPTS+"</script>");
                                }
                            }

                            resetCustomDynamicInputs();
                            
                        }

                        if(arParams.typeForm == "fast_order")
                        {
                            if (arParams.fromBasket == "Y")
                            {

                                if (typeof(json.CUR_PAGE) != "undefined")
                                {
                                    if(json.CUR_PAGE.length>0)
                                    {
                                        window.location.href = json.CUR_PAGE;
                                    }
                                }
                            }
                            else
                            {

                                if (typeof(json.TEXT_THANK) != "undefined")
                                {
                                    if(json.TEXT_THANK.length>0)
                                    {
                                        arParams.questions.removeClass("active");
                                        arParams.thank.html(json.TEXT_THANK);
                                        arParams.thank.addClass("active");
                                    }
                                }
                            }
                        }


                    }
                }
            });

        }, 1000);
    }

}

$(document).on("click", ".btn-submit", function()
{
    var form = $(this).parents("form.send"),
        typeForm = "",
        fromBasket = "",
        paramsForm = {};

    if(form.length>0)
    {
        
        if($(this).hasClass('fast-order'))
            typeForm = "fast_order";

        if($(this).hasClass('fast-order-basket'))
        {
            fromBasket = "Y";
            typeForm = "fast_order";
        }


        paramsForm = 
            {
                form_block: form,
                typeForm: typeForm,
                fromBasket: fromBasket,
                path: "/bitrix/tools/concept.phoenix/ajax/form_send.php",
                button: $(this),
                linkHREF: $(this).attr("data-link"),
                linkHREFNew: $(this).attr("data-link-new"),
                header: $("input[name='header']", form),
                agreecheck: $("input.agreecheck", form),
                questions: $("div.questions", form),
                load: $("div.load", form),
                thank: $("div.thank", form),
                captchaToken: ""
            };


        if($("body").hasClass('captcha'))
        {
            grecaptcha.execute($(".captcha-site-key").val(), {action: 'homepage'}).then(function(token) {

                paramsForm.captchaToken = token;
                sendForm(paramsForm);
            });
        }
        else{
            sendForm(paramsForm);
        }
    
    }


    
});



$(document).on("focus", "input[type='email'], input[type='text'], input[type='password'], textarea", function() {
    $(this).parent("div.input").removeClass("has-error");
    if ($(this).val().length <= 0 && !$(this).hasClass("phone")) {
        $(this).attr("data-placeholder", $(this).attr("placeholder"));
        $(this).attr("placeholder", "");
    }
});
$(document).on("blur", "input[type='email'], input[type='text'], input[type='password'], textarea", function() {
    if ($(this).val().length <= 0 && !$(this).hasClass("phone")) $(this).attr(
        "placeholder", $(this).attr("data-placeholder"));
});
$(document).on("keypress", "form.form div.count input", function(e) {
    e = e || event;
    if (e.ctrlKey || e.altKey || e.metaKey) return;
    var chr = getChar(e);
    if (chr == null) return;
    if (chr < '0' || chr > '9') {
        return false;
    }
});
$(document).on("keyup", "form.form div.count input", function(e) {
    var value = $(this).val().toString();
    var newVal = "";
    for (var i = 0; i < value.length; i++) {
        if (value[i] == "0" || value[i] == "1" || value[i] == "2" || value[i] ==
            "3" || value[i] == "4" || value[i] == "5" || value[i] == "6" || value[i] ==
            "7" || value[i] == "8" || value[i] == "9") newVal += value[i];
    }
    if (newVal == 0) newVal = 1;
    $(this).val(newVal);
    if ($(this).val() == "") $(this).parent().addClass('in-focus');
});
$(document).on("click", "form.form div.count span.plus", function(e) {
    var input = $(this).parent("div.count").find("input");
    var value = parseFloat(input.val());
    if (isNaN(value)) value = 0;
    value += 1;
    input.val(value);
    if ($(this).val() == "") $(this).parent().addClass('in-focus');
});
$(document).on("click", "form.form div.count span.minus", function(e) {
    var input = $(this).parent("div.count").find("input");
    var value = parseFloat(input.val());
    if (isNaN(value)) value = 0;
    value -= 1;
    if (value < 0) value = '';
    if (value == 0) value = 1;
    input.val(value);
});
$(document).on("keypress", ".only-num", function(e) {
    e = e || event;
    if (e.ctrlKey || e.altKey || e.metaKey) return;
    var chr = getChar(e);
    if (chr == null) return;
    if (chr < '0' || chr > '9') {
        return false;
    }
});
$(document).on("focus", "input.focus-anim, textarea.focus-anim", function() {
    if ($(this).val() == "") $(this).parent().addClass('in-focus');
});
$(document).on("blur", "input.focus-anim, textarea.focus-anim", function() {
    element = $(this);
    setTimeout(function() {
        if (element.val() == "") element.parent().removeClass('in-focus');
    }, 200);
});

function showModalDialog(target){
    startBlurWrapperContainer();
    $(".phx-modal-dialog[data-target='"+target+"']").addClass('active');
    var timer = setTimeout(function() {
        $(".phx-modal-dialog[data-target='"+target+"']").addClass('show');
        clearTimeout(timer);
    }, 300);
}
function closeModalDialog(target){

    $(".phx-modal-dialog[data-target='"+target+"']").removeClass('show');
    var timer = setTimeout(function() {
        $(".phx-modal-dialog[data-target='"+target+"']").removeClass('active');
        stopBlurWrapperContainer();
        clearTimeout(timer);
    }, 300);
}

$(document).on("click", ".show-phx-modal-dialog", function()
{
    showModalDialog($(this).attr("data-target"));
});
$(document).on("click", ".close-phx-modal-dialog", function()
{
    closeModalDialog($(this).attr("data-target"));
});



$(document).on("click", ".auth-submit", function()
{
    var form = $(this).parents("form.auth"),
        er_block = $("div.errors", form),
        login = $("input[name='auth-login']", form),
        password = $("input[name='auth-password']", form),
        error = 0,
        count = 0,
        button = $("button", form),
        load = $("div.load", form);
    
    $("input[type='text'], input[type='password']", form).each(
        function()
        {
            
            if($(this).hasClass("email") && $(this).val().length > 0)
            {
                if(!(/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,15}$/i).test($(this).val()))
                {
                    $(this).parent("div.input").addClass("has-error");
                    error = 1;
                    
                    count++;
                    
                    if(count == 1)
                    {
                        $('html, body').animate({ scrollTop: $(this).offset().top - 100 }, 500);
                    }
                }
            }
           
            
            if($(this).hasClass("require"))
            {
                if($(this).val().length <= 0)
                {
                    $(this).parent("div.input").addClass("has-error");
                    error = 1;
                    
                    count++;
                    
                    if(count == 1)
                    {
                        $('html, body').animate({ scrollTop: $(this).offset().top - 100 }, 500);
                    }
                }
            }
        }
    );
    
    
    if(error == 0)
    {
        button.removeClass('active');
        load.addClass('active');

        $.post(
            "/bitrix/tools/concept.phoenix/ajax/personal/auth.php",
            {
                "send": "Y",
                "login": login.val(),
                "password": password.val(),
                "site_id": $("input.site_id").val()
            },
            function(data)
            {
                if(data.OK == "N")
                {
                    er_block.html(data.ERROR);
                    button.addClass('active');
                    load.removeClass('active');
                }
                
                if(data.OK == "Y")
                {
                    location.href = location.href;
                }
            },
            "json"
        ); 
    }
    
    return false;
});

$(document).on("click", ".register-submit", function()
{
    var form = $(this).parents("form.reg-form"),
        wr_form = form.parents("div.reg-page"),
        er_block = $("div.errors", wr_form),
        name = $("input[name='bx-name']", form),
        password = $("input[name='bx-password']", form),
        email = $("input[name='bx-email']", form),
        error = 0,
        count = 0,
        button = $("button", form),
        load = $("div.load", form);



    if($("input.agreecheck", form).length>0)
    {
        agreecheck = $("input.agreecheck", form);

        if (!agreecheck.prop("checked")) {
            agreecheck.parents("div.wrap-agree").addClass("has-error");
            error = 1;
        }
    }


    $("input[type='email'], input[type='text'], input[type='password']", form).each(
        function()
        {
            
            if($(this).hasClass("email") && $(this).val().length > 0)
            {
                if(!(/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,15}$/i).test($(this).val()))
                {
                    $(this).parent("div.input").addClass("has-error");
                    error = 1;
                    
                    count++;
                    
                    if(count == 1)
                    {
                        $('html, body').animate({ scrollTop: $(this).offset().top - 100 }, 500);
                    }
                }
            }
           
            
            if($(this).hasClass("require"))
            {
                if($(this).val().length <= 0)
                {
                    $(this).parent("div.input").addClass("has-error");
                    error = 1;
                    
                    count++;
                    
                    if(count == 1)
                    {
                        $('html, body').animate({ scrollTop: $(this).offset().top - 100 }, 500);
                    }
                }
            }
        }
    );

    var captchaToken = "";

    if(error == 0)
    {
        
        button.removeClass("active");
        load.addClass('active');

        if($("body").hasClass('captcha'))
        {
            grecaptcha.execute($(".captcha-site-key").val(), {action: 'homepage'}).then(function(token) {
                captchaToken = token;

                $.post(
                    "/bitrix/tools/concept.phoenix/ajax/personal/reg.php",
                    {
                        "send": "Y",
                        "bx-name": name.val(),
                        "bx-email": email.val(),
                        "bx-password": password.val(),
                        "site_id": $("input.site_id").val(),
                        "captchaToken": captchaToken
                    },
                    function(data)
                    {
                        if(data.OK == "N")
                        {
                            er_block.html(data.ERROR);
                            $('html, body').animate({ scrollTop: er_block.offset().top - 100 }, 500);
                            button.addClass("active");
                            load.removeClass('active');
                        }
                        
                        if(data.OK == "Y")
                        {
                            location.href = data.HREF;
                        }
                           
                    },
                    "json"
                );
            });
        }

        else
        {
            $.post(
                "/bitrix/tools/concept.phoenix/ajax/personal/reg.php",
                {
                    "send": "Y",
                    "bx-name": name.val(),
                    "bx-email": email.val(),
                    "bx-password": password.val(),
                    "site_id": $("input.site_id").val()
                },
                function(data)
                {
                    if(data.OK == "N")
                    {
                        er_block.html(data.ERROR);
                        $('html, body').animate({ scrollTop: er_block.offset().top - 100 }, 500);
                        button.addClass("active");
                        load.removeClass('active');
                    }
                    
                    if(data.OK == "Y")
                    {
                        location.href = data.HREF;
                    }
                       
                },
                "json"
            );
        }

        
        
    }
    
    return false;

});



$(document).on("click", ".changepassword-submit", function()
{
    var form = $(this).parents("form.changepassword"),
        wr_form = form.parents("div.changepassword"),
        er_block = $("div.errors", wr_form),
        suc_block = $("div.success", wr_form),
        login = $("input[name='USER_LOGIN']", form),
        checkword = $("input[name='USER_CHECKWORD']", form),
        password = $("input[name='USER_PASSWORD']", form),
        password_confirm = $("input[name='USER_CONFIRM_PASSWORD']", form),
        error = 0,
        count = 0,
        button = $("button", form),
        load = $("div.load", form);


    $("input[type='password']", form).each(
        function()
        {     
            if($(this).hasClass("require"))
            {
                if($(this).val().length <= 0)
                {
                    $(this).parent("div.input").addClass("has-error");
                    error = 1;
                    
                    count++;
                    
                    if(count == 1)
                    {
                        $('html, body').animate({ scrollTop: $(this).offset().top - 100 }, 500);
                    }
                }
            }
        }
    );



    if(error == 0)
    {
        
        button.removeClass("active");
        load.addClass('active');
        
        $.post(
            "/bitrix/tools/concept.phoenix/ajax/personal/changepassword.php",
            {
                "send": "Y",
                "login": login.val(),
                "password": password.val(),
                "password_confirm": password_confirm.val(),
                "checkword": checkword.val(),
                "site_id": $("input.site_id").val()
            },
            function(data)
            {
                if(data.OK == "N")
                {
                    if(data.ERRORS)
                    {
                        var errors_html = "";
                        
                        for( var i in data.ERRORS )
                        {
                            errors_html += "<div class='er-policy-item'>"+data.ERRORS[i]+"</div>";
                        }

                        er_block.show().html('<div class="er-policy">'+errors_html+"</div>");

                        $('html, body').animate({ scrollTop: er_block.offset().top - 100 }, 500);
                    }


                    suc_block.hide();
                    form.show();
                    button.addClass("active");
                    load.removeClass('active');
                }
                
                if(data.OK == "Y")
                {
                    er_block.hide();
                    form.hide();
                    suc_block.show().html(data.SUCCESS);

                    $.ajax({
                        url: "/bitrix/tools/concept.phoenix/ajax/ajax_hit_page.php",
                        method: 'POST',
                        success: function(json) {}
                    });
                }
                   
            },
            "json"
        ); 
    }
    
    return false;

});


$(document).on("click", ".forgotpassword-submit", function()
{
    var form = $(this).parents("form.forgotpassword"),
        wr_form = form.parents("div.forgetpass"),
        er_block = $("div.errors", wr_form),
        suc_block = $("div.success", wr_form),
        desc_block = $(".description-change-form", wr_form),
        login = $("input[name='USER_LOGIN']", form),
        error = 0,
        count = 0,
        button = $("button", form),
        load = $("div.load", form);


    $("input[type='text']", form).each(
        function()
        {     
            if($(this).hasClass("require"))
            {
                if($(this).val().length <= 0)
                {
                    $(this).parent("div.input").addClass("has-error");
                    error = 1;
                    
                    count++;
                    
                    if(count == 1)
                    {
                        $('html, body').animate({ scrollTop: $(this).offset().top - 100 }, 500);
                    }
                }
            }
        }
    );

    if(error == 0)
    {
        
        button.removeClass("active");
        load.addClass('active');
        
        $.post(
            "/bitrix/tools/concept.phoenix/ajax/personal/forgotpassword.php",
            {
                "send": "Y",
                "login": login.val(),
                "site_id": $("input.site_id").val()
            },
            function(data)
            {
                
                if(data.OK == "N")
                {
                    er_block.show().html(data.ERRORS);
                    suc_block.hide();
                    desc_block.show();
                    form.show();
                    button.addClass("active");
                    load.removeClass('active');
                }
                
                if(data.OK == "Y")
                {
                    desc_block.hide();
                    er_block.hide();
                    form.hide();
                    suc_block.show().html(data.SUCCESS);
                }
                   
            },
            "json"
        ); 
    }
    
    return false;

});