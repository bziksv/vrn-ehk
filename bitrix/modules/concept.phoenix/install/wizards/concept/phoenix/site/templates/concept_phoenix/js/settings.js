/*setting file-download*/

$(document).on("click", ".clearfile",
    function ()  
    {
        $(this).parents(".clearfile-parent").find('input.phoenix_file_del').val('Y');
        $(this).parents(".clearfile-parent").find('.focus-anim').removeClass('focus-anim');
        $(this).parents(".clearfile-parent").find('.ex-file').html('');
        $(this).removeClass('on');
        $(this).parents(".clearfile-parent").find('input[type="file"]').val('');

    }
);

function showTooltip(elem, msg) 
{
    $(elem).parents(".parent_copy").find(".copy-success").addClass("active");
    setTimeout(
        function()
        {
            $(elem).parents(".parent_copy").find(".copy-success").removeClass("active");
        },3000
    );
}
function initPanelSettings()
{

    if($("#panel_phoenix_head_bg_color").length>0)
        $.farbtastic('#panel_phoenix_head_bg_color').linkTo('#picker_phoenix_head_bg_color');

    if($("#panel_phoenix_menu_bg_color").length>0)
        $.farbtastic('#panel_phoenix_menu_bg_color').linkTo('#picker_phoenix_menu_bg_color');

    if($("#panel_phoenix_main_color").length>0)
        $.farbtastic('#panel_phoenix_main_color').linkTo('#picker_phoenix_main_color');

    if($("#panel_phoenix_footer_color_bg").length>0)
        $.farbtastic('#panel_phoenix_footer_color_bg').linkTo('#picker_phoenix_footer_color_bg');


    var clipboardDemos = new ClipboardJS(".list-copy");
        
    clipboardDemos.on('success', function(e) {
        e.clearSelection();
        showTooltip(e.trigger);

   
    });
    clipboardDemos.on('error', function(e) {
    });

}

$(document).on("click", "button.btn-submit-main-set", 
    function()
    {

        var form = $(this).parents("form.form-setting");
    
        /*var formData = new FormData(form.get(0));
        formData.append("send", "Y");*/
        
        var button = $("button[name='form-submit']", form);

        var load = $("div.load", form);
        var thank = $("div.thank", form);

      
        var error = 0;
        var count = 0;

        tmpl = $("input.tmpl").val();
        tmpl_path = $("input.tmpl_path").val();

        var formSendAll = new FormData();
        
        $("input.email", form).each(
            function()
            {
                
                if(count == 1)
                {
                    var destination = $(this).position().top; 
                    
                    if(destination <= 0)
                        destination = 0;                    
                    
                    jQuery("div.phoenix-setting.list-set.on").animate(
                    {
                      scrollTop: destination
                    }, 700);
                }
 
            }
        );
        $("input[type='text'], input[type='password'], textarea", form).each(
            function(key, value)
            {
                if($(this).hasClass("require"))
                {
                    if($(this).val().length <= 0)
                    {
                        $(this).parent("div.input").addClass("has-error");
                        error = 1;
                    }
                }
            }
        );

        
        if(error == 0)
        {

            formSendAll.append("send", "Y");
            formSendAll.append("tmpl_path", tmpl_path);

            form_arr = $(form).find(':input,select,textarea').serializeArray();

            for (var i = 0; i < form_arr.length; i++)
            {
                
                formSendAll.append(form_arr[i].name, form_arr[i].value);
                
            };

      
            form.find('input[type=file]').each(function(key)
            {
                if($(this).parent().hasClass("for-download"))
                {
                    if($(this).parent().find(".phoenix_file_del").val() != "Y")
                        formSendAll.append($(this).attr('name'), $(this)[0].files[0], $(this)[0].files[0].name);
                }

            });

            
            button.parents("div.part-cell").width(button.parents("div.part-cell").width());
            
            button.removeClass("active");
            load.addClass("active");
            
            
            $.ajax({
      
                url: "/bitrix/tools/concept.phoenix/ajax/settings/settings.php",
                type: "post",
                contentType: false, 
                processData: false, 
                data: formSendAll,
                dataType: 'json',
                success: function(json){

                    if(json.OK == "N")
                    {
                        button.addClass("active");
                        load.removeClass("active");

                    }
                    
                    if(json.OK == "Y")
                    {
                        document.location.reload(true);
                    }
                    
                    
                }
            });
        }
    
    }
);


$(document).on("click", "button.btn-submit-page-list", 
    function()
    {

        var form = $(this).parents("form.form-page-list");
        /*var formData = new FormData(form.get(0));
        formData.append("send", "Y");*/
        var button = $("button[name='form']-submit", form);
        var load = $("div.load", form);
        var thank = $("div.thank", form);

        var error = 0;
        var count = 0;


        var formSendAll = new FormData();

        if(error == 0)
        {

            formSendAll.append("send", "Y");
            formSendAll.append("tmpl_path", $("input.tmpl_path").val());

            form_arr = $(form).find(':input,select,textarea').serializeArray();

            for (var i = 0; i < form_arr.length; i++)
            {
               
                formSendAll.append(form_arr[i].name, form_arr[i].value);
               
            };


            button.parents("div.part-cell").width(button.parents("div.part-cell").width());
            
            button.removeClass("active");
            load.addClass("active");
            
            
            $.ajax({
      
                url: "/bitrix/tools/concept.phoenix/ajax/settings/page_list.php",
                type: "post",
                contentType: false, 
                processData: false, 
                data: formSendAll,
                dataType: 'json',
                success: function(json){

                    if(json.OK == "N")
                    {
                        button.addClass("active");
                        load.removeClass("active");

                    }
                    
                    if(json.OK == "Y")
                        document.location.reload(true);
                    
                    
                }
            });
        }
    
    }
);

$(document).on("click", "button.btn-submit-add-page", 
    function()
    {

        var form = $(this).parents("form.form-add-page");
    
        /*var formData = new FormData(form.get(0));
        formData.append("send", "Y");*/
        
        var button = $("button[name='form-submit']", form);

        var load = $("div.load", form);
        var thank = $("div.thank", form);

      
        var error = 0;
        var count = 0;


       

        var formSendAll = new FormData();
        
        $("input.email", form).each(
            function()
            {
                
                if(count == 1)
                {
                    var destination = $(this).position().top; 
                    
                    if(destination <= 0)
                        destination = 0;                    
                    
                    jQuery("div.hameleon-setting.list-set.on").animate(
                    {
                      scrollTop: destination
                    }, 700);
                }
 
            }
        );
        $("input[type='text'], input[type='password'], textarea", form).each(
            function(key, value)
            {

                if($(this).hasClass("require"))
                {
                    if($(this).val().length <= 0)
                    {
                        $(this).parent("div.input").addClass("has-error");
                        error = 1;
                    }
                }
            }
        );

        if(error == 0)
        {

            formSendAll.append("send", "Y");
            formSendAll.append("tmpl_path", $("input.tmpl_path").val());

            form_arr = $(form).find(':input,select,textarea').serializeArray();

            for (var i = 0; i < form_arr.length; i++)
            {
                
                formSendAll.append(form_arr[i].name, form_arr[i].value);
            
            };


            button.parents("div.part-cell").width(button.parents("div.part-cell").width());
            
            button.removeClass("active");
            load.addClass("active");
            
            
            $.ajax({
      
                url: "/bitrix/tools/concept.phoenix/ajax/settings/add_page.php",
                type: "post",
                contentType: false, 
                processData: false, 
                data: formSendAll,
                dataType: 'json',
                success: function(json){

                    if(json.OK == "N")
                    {
                        button.addClass("active");
                        load.removeClass("active");

                    }
                    
                    if(json.OK == "Y")
                        location.href = json.HREF;
                }
            });

            
        }
    
    }
);


$(document).on("click", "button.btn-submit-form-seo", 
    function()
    {

        var form = $(this).parents("form.form-seo");
    
        /*var formData = new FormData(form.get(0));
        formData.append("send", "Y");*/
        
        var button = $("button[name='form-submit']", form);

        var load = $("div.load", form);
        var thank = $("div.thank", form);

      
        var error = 0;
        var count = 0;
        

        var formSendAll = new FormData();
        
        if(error == 0)
        {

            formSendAll.append("send", "Y");
            formSendAll.append("tmpl_path", $("input.tmpl_path").val());

            form_arr = $(form).find(':input,select,textarea').serializeArray();

            for (var i = 0; i < form_arr.length; i++)
            {
                
                formSendAll.append(form_arr[i].name, form_arr[i].value);
                
            };

            form.find('input[type=file]').each(function(key)
            {
                if($(this).parent().hasClass("for-download"))
                {
                    if($(this).parent().find(".phoenix_file_del").val() != "Y")
                        formSendAll.append($(this).attr('name'), $(this)[0].files[0], $(this)[0].files[0].name);
                }

            });

            button.parents("div.part-cell").width(button.parents("div.part-cell").width());
            
            button.removeClass("active");
            load.addClass("active");
            
            
            $.ajax({
      
                url: "/bitrix/tools/concept.phoenix/ajax/seo.php",
                type: "post",
                contentType: false, 
                processData: false, 
                data: formSendAll,
                dataType: 'json',
                success: function(json){

                    if(json.OK == "N")
                    {
                        button.addClass("active");
                        load.removeClass("active");

                    }
                    
                    if(json.OK == "Y")
                    {
                        document.location.reload(true);
                    }
                }
            });
            
        }
        
    
    }
);




$(document).on("click", "div.phoenix-btn",
    function ()  
    { 
        showSetsPanel();
    }
);

$(document).on("click", "div.phoenix-sets-list-close",
    function ()  
    { 
        hideSetsPanel();
    }
);

function hideSetsPanel(){
    if($('div.tool-settings').length>0)
        $('div.tool-settings').removeClass('on');

    if($('.change-colls').length>0)
        $('.change-colls').removeClass('on');

    $('div.phoenix-sets-list-wrap').removeClass('view');
    setTimeout(
        function()
        {
            $('div.phoenix-sets-list-wrap').removeClass('on');
        }, 200
    );

    $('div.phoenix-main-setting').removeClass('hide');

    setTimeout(
        function()
        {
            $('div.phoenix-main-setting').removeClass('off');
        }, 200
    );

    BX.setCookie($(".domen-url-for-cookie").val()+'_phoenix_menu_open', 'N', {expires: 60*60*60*60, path: "/"});
}

function showSetsPanel(){

    if($('div.tool-settings').length)
        $('div.tool-settings').addClass('on');

    if($('.change-colls').length)
        $('.change-colls').addClass('on');

    $('div.phoenix-main-setting').addClass('off');
    setTimeout(
        function()
        {
            $('.phoenix-main-setting').addClass('hide');
        }, 500

    );

    $('div.phoenix-sets-list-wrap').addClass('on');
    setTimeout(
        function()
        {
            $('div.phoenix-sets-list-wrap.on').addClass('view');
        }, 50

    );

    BX.setCookie($(".domen-url-for-cookie").val()+'_phoenix_menu_open', 'Y', {expires: 60*60*60*60, path: "/"});

}

function initSetsPanel(){
    var menu_open = BX.getCookie($(".domen-url-for-cookie").val()+'_phoenix_menu_open');

    if(menu_open == 'Y')
        showSetsPanel();
    
    else
    {
        hideSetsPanel();
    }
}

$(document).ready(
    function()
    {
        initSetsPanel();
    }
);

function minHeightEditSets(thisblock, height, height1, height2)
{
    if(height2 === 'NULL')
        height2 = 0;

    var total = height - height1 - height2;


    thisblock.find('table.sides').css('min-height', total+'px');   

}


function showContentSettings (panel)
{
    panel = panel || "";

    if(!panel)
        return;


    if(panel != "newpage")
    {
        startBlurWrapperContainer();
        $('div.phoenix-sets-list-wrap').addClass('blur');
        $('div.sets-shadow').addClass('on');
    }
    

    $('div.phoenix-setting.'+panel).addClass('open');
    setTimeout(
        function()
        {
            $('div.phoenix-setting.'+panel).addClass('on');
            minHeightEditSets($('div.phoenix-setting.'+panel), $('div.phoenix-setting.'+panel).height(), $('div.phoenix-setting.'+panel).find('.phoenix-set-head').outerHeight(), $('div.phoenix-setting.'+panel).find('.phoenix-set-top').outerHeight());

        }, 200
    );
}


$(document).on("click", "a.phoenix-sets-open",
    function ()  
    {

        var _this = $(this),
            panel = $(this).attr('data-open-set'),
            path = "/bitrix/tools/concept.phoenix/ajax/settings/show_settings.php",
            params = {};
            currentSectionId = "";

            if($("input[name='currentSectionId']").length>0)
                currentSectionId = $("input[name='currentSectionId']").val();


        params = {
            site_id: $("input.site_id").val(),
            panel: panel,
            currentSectionId: currentSectionId,
            ajax_seo:''
        };

        if(panel == 'seo')
        {
            params.ajax_seo = 'Y';
            path = location.href
        }

        if(!$(this).hasClass('init'))
        {
            showProcessLoad();

            $.post(path, params, 
            function(html)
            {
                _this.addClass('init');
                $('div.phoenix-setting.'+panel).html(html);
                showContentSettings(panel);
                closeProcessLoad();
                $('[data-toggle="tooltip"]').tooltip({
                    html:true
                });
                initPanelSettings();
                
            });
        }
        else
        {
            showContentSettings(panel);
        }
    }
);

/*$(document).on("click", "a.new_page",
    function ()  
    {
        var attr = $(this).attr('data-open-set');

        $('div.phoenix-setting.'+attr).addClass('open');
        setTimeout(
            function()
            {
                $('div.phoenix-setting.'+attr).addClass('on');

            }, 200
        );
    }
);*/

$(document).on("click", ".phoenix-set-close",
    function ()  
    { 
        var btn = $(this);

        if(!(btn.parents('div.phoenix-setting')).hasClass('newpage'))
        {
            stopBlurWrapperContainer();
            $('div.phoenix-sets-list-wrap').removeClass('blur');
            $('div.sets-shadow').removeClass('on');
        }


        btn.parents('div.phoenix-setting').removeClass('on');
        setTimeout(
            function()
            {
                btn.parents('div.phoenix-setting').removeClass('open');
            }, 700
        );
    }
);


$(document).on('click', 'ul.set-tabs li:not(.active)', 
    function() 
    {
        $(this).parents('ul.set-tabs').find('li').removeClass('active');
        $(this).addClass('active');
        $(this).parents('.sides').find('.set-content').removeClass('active');
        $(this).parents('.sides').find('.set-content[data-set="'+$(this).attr('data-set')+'"]').addClass('active');
    }
);

$(document).on("click", ".open_edit",
    function ()  
    {
        $(this).parents('div.button-wrap').addClass('off');
        $(this).parents('div.phoenix-set-content').find('div.more_edit').addClass('on');
        $(this).parents('div.phoenix-set-content').find('div.more_set').addClass('on');
    }
);

$(document).on("click", ".close_edit",
    function ()  
    {
        $(this).parents('div.more_edit').removeClass('on');
        $(this).parents('div.phoenix-set-content').find('div.button-wrap').removeClass('off');
        $(this).parents('div.phoenix-set-content').find('div.more_set').removeClass('on');
    }
);

$(document).on("click", "span.toggle-indicator",
    function ()  
    {

        if($(this).parents('div.ignite').hasClass('on'))
        {
            $(this).parents('form.form-page-list').find('input[name="page_active'+$(this).attr('data-page-id')+'"]').val('N');
            $(this).parents('div.ignite').removeClass('on');
        }
        
        else
        {
            $(this).parents('form.form-page-list').find('input[name="page_active'+$(this).attr('data-page-id')+'"]').val('Y');
            $(this).parents('div.ignite').addClass('on');
        }
    }
);



function showHideOptions(check, block, type)
{

    if(type =='checkbox')
    {
        if(check.prop("checked"))
            block.slideDown(200);
        
        else{
            block.slideUp(200);
        }
    }

    else if(type =='radio')
    {
        if(!block.hasClass('active'))
        {
            check.parents('.parent-more-option').next().find('.more-option').removeClass('active');
            block.addClass('active');
            check.parents('.parent-more-option').next().find('.more-option').slideUp(200);
        }
        
        block.slideDown(200);
        
    }

    else if(type =='open'){
        block.slideDown(200);
    }
    
    else if(type =='close'){
        block.slideUp(200);
    }
    
    
}

$(document).on("click", ".open_more_options",
    function ()  
    {

        showHideOptions($(this), $('.more-option[data-show-options="'+$(this).attr('data-show-options')+'"]'), $(this).attr('type'));
    }
);

$(document).on("click", "div.edit_land span.change",
    function ()  
    {
        $(this).parent().addClass('off');
        $(this).parents('.edit_land').find('input').removeAttr('disabled').focus();
    }
);

$(document).on("keydown", "div.edit_land input",
    function (e)  
    { 
        if(e.which == 191)
            return false;
    }
);


$(document).on("keyup", "div.edit_land input",
    function (e)  
    { 

        if($(this).val().length > 0)
            $(this).parents('div.edit_land').find('span.backslash').removeClass('off');
        else{
            $(this).parents('div.edit_land').find('span.backslash').addClass('off');
        }

        $(this).parents('div.edit_land').find('span.new_url').html($(this).val()); 
    }
);

$('div.edit_land input').focusout(function(){
    $(this).attr('disabled', 'disabled');
    $(this).parents('.edit_land').find('div.wrap-change').removeClass('off');
});


$(document).ready(function() {

    initPanelSettings();
    
});

$(document).on('click', '.call_picker', 
    function() 
    {
        $(this).parents('div.input').find('div.picker-wrap').addClass('on');
    }
);
$(document).on('click', '.picker_panel', 
    function() 
    {
        $(this).parents('.parent-clearcolor').find('.clearcolor').addClass('on');
    }
);
$(document).on('click', '.picker-close', 
    function() 
    {
        $(this).parent('div.picker-wrap').removeClass('on');
    }
);
$(document).mouseup(
    function (e)
    { 
        var div = $("div.picker-wrap.on"); 

        if (!div.is(e.target) && div.has(e.target).length === 0) 
        {
            div.removeClass('on');
        }
    }
);
$(document).on("click", ".clearcolor",
    function ()  
    {
        $(this).parents('.parent-clearcolor').find('.picker_color').val(' ').removeAttr('style');
        $(this).removeClass('on');
    }
);



$(document).on("click", ".addrow",
    function ()  
    {
        var template_clone = $(this).parents('.parent-row').find(".empty-template").find('.row-for-copy').clone();
        var count = $(this).parents('.parent-row').find('.rows-copy').find('.row-for-copy').length;
        var count2 = count + 1;

        var name1 = $(this).parents('.parent-row').find(".empty-template").find('.for-copy-1').attr('name');
        
        count = String(count);
        count2 = String(count2);
        
        name1 = name1.replace('[n'+count, '[n'+count2);
        
        $(this).parents('.parent-row').find('.rows-copy').append(template_clone);
        $(this).parents('.parent-row').find(".empty-template").find('.for-copy-1').attr('name', name1);
        

        if($(this).hasClass('multy'))
        {
            var name2 = $(this).parents('.parent-row').find(".empty-template").find('.for-copy-2').attr('name');
            name2 = name2.replace('[n'+count, '[n'+count2);
            $(this).parents('.parent-row').find(".empty-template").find('.for-copy-2').attr('name', name2);
        }

        
    }
);
 
$(document).on("click", ".on-save",
    function ()  
    {
       $(this).parents('.phoenix-setting').find('div.phoenix-set-foot').removeClass('off');
    }
);

$(document).on("change", ".on-save",
    function ()  
    {
       $(this).parents('.phoenix-setting').find('div.phoenix-set-foot').removeClass('off');
    }
);

$(document).on("focus", ".on-save",
    function ()  
    {
       $(this).parents('.phoenix-setting').find('div.phoenix-set-foot').removeClass('off');
    }
);

$(document).on("click", "div.seo-more_info",
    function ()  
    {
        if($(this).hasClass('on'))
        {
            $(this).removeClass('on');
            $(this).parents('.phoenix-set-top').find('.progress-info').slideUp(200);

        }
        else
        {
            $(this).addClass('on');
            $(this).parents('.phoenix-set-top').find('.progress-info').slideDown(200);
        }
       

    }
);



$(document).on("click", "div.for-copy .change",
    function ()  
    {
        $(this).parents('.wrap-change').addClass('off');
        $(this).parents('.parent-seo-copy').find('.seo-clone').html($(this).parents('.for-copy').find('.disabled_texarea span').html()).val($(this).parents('.for-copy').find('.disabled_texarea span').html());
    }
);
$(document).on("click", "div.seo-cancel",
    function ()  
    {

        $(this).parents('.parent-seo-copy').find('.wrap-change').removeClass('off');

        if($(this).parents('.parent-seo-copy').find("textarea.seo-clone").attr("data-fill") == "Y")
            $(this).parents('.parent-seo-copy').find("textarea.seo-clone").val($(this).parents('.parent-seo-copy').find("div.disabled_texarea span").html());
        else{
            $(this).parent().find('.seo-clone').html('').val('');
        }
    }
);

$(document).on("click", ".addrow-seo",
    function ()  
    {

        var template_clone = $(this).parents('.parent-row').find(".empty-template").children().clone();
        var count = $(this).prev().children().length;
        var count2 = count + 1;
        var name1 = $(this).parents('.parent-row').find(".empty-template").find('.seo-name').attr('name');

        count = String(count);
        
        name1 = name1.replace('[n'+count, '[n'+count2);


        $(this).parents('.parent-row').find('.area-for-clone').append(template_clone);

        $(this).parents('.parent-row').find(".empty-template").find('.seo-name').attr('name', name1);


        
    }
);


$(document).on('click', '.change-colls', 
    function() 
    {
        var view = 0;
        var type = $(this).attr('data-type');

        if(!($(this).parents('.big-parent-colls').find('.change-colls-info').hasClass('active')))
            $(this).parents('.big-parent-colls').find('.change-colls-info').addClass('active');
        
        if($(this).parents('.parent-change-cools').hasClass('middle') && $(this).parents('div.block').hasClass('small-block'))
        {
            $(this).parents('.parent-change-cools').removeClass('col-lg-8 col-sm-6 middle').addClass('col-lg-4 col-sm-6 small');
            view = $(this).parents('.parent-change-cools').find('input.colls_small').val();
        }
        else if($(this).parents('.parent-change-cools').hasClass('small') && $(this).parents('div.block').hasClass('small-block'))
        {
            $(this).parents('.parent-change-cools').removeClass('col-lg-4 col-sm-6 small').addClass('col-lg-8 col-sm-6 middle');
            view = $(this).parents('.parent-change-cools').find('input.colls_middle').val();
        }


        else if($(this).parents('.parent-change-cools').hasClass('middle') && !($(this).parents('div.block').hasClass('small-block')))
        {
            $(this).parents('.parent-change-cools').removeClass('col-sm-6 middle').addClass('col-lg-3 col-sm-6 small');
            view = $(this).parents('.parent-change-cools').find('input.colls_small').val();
        }

        else if($(this).parents('.parent-change-cools').hasClass('small') && !($(this).parents('div.block').hasClass('small-block')))
        {
            $(this).parents('.parent-change-cools').removeClass('col-lg-3 col-sm-6 small').addClass('col-sm-6 middle');
            view = $(this).parents('.parent-change-cools').find('input.colls_middle').val();
        }


        $.post("/bitrix/tools/concept.phoenix/ajax/settings/new_colls.php", 
        { 
            element_id: $(this).attr('data-element-id'),
            view: view,
            type: type,
            code: $(this).parents('.parent-change-cools').find('input.colls_code').val(),
            site_id: $("input.site_id").val(),
            send: "Y"
        });
        
    }
);


$(document).on('click', '.open-main-menu', 
    function() 
    {
        $('div.phoenix-sets-list-wrap').removeClass('view');
    }
);
$(document).on('click', '.close-menu', 
    function() 
    {
        $('div.phoenix-sets-list-wrap').addClass('view');
    }
);

$(document).on("change", ".btn-block-show", function()
{
    $(this).parents(".input-wrap").find(".block-show").addClass('d-none');
    $(".block-show."+$(this).val()).removeClass('d-none');
}
);

function initOptionsByJs(){
    $(".btn-block-show").each(
        function()
        {
            $(".block-show."+$(this).val()).removeClass('d-none');
        }
    );
}
