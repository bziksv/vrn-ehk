<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?global $PHOENIX_TEMPLATE_ARRAY;?>

<div class="container">
    <div class="row">
    
        <div class="forgetpass col-md-4 col-sm-8 col-12">

            <div class="success" style="display:none;"></div>

            <p class="description-change-form"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_FORGOT_PAGE_COMMENT"]?></p>


            <div class="errors" style="display:none;"></div>

            <form class="form forgotpassword" name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
                
                <?if (strlen($arResult["BACKURL"]) > 0):?>
                    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
                <?endif;?>
                
                <input type="hidden" name="AUTH_FORM" value="Y">
                <input type="hidden" name="TYPE" value="SEND_PWD">
                
                <div class="input <?if(strlen($arResult["LAST_LOGIN"]) > 0):?>in-focus<?endif;?>">                  
                    <div class="bg"></div>
                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_FORGOT_PAGE_INPUT_LOGIN"]?></span>
                    <input class="focus-anim require" name="USER_LOGIN" type="text" value="<?=$arResult["LAST_LOGIN"]?>" />
                    <input type="hidden" name="USER_EMAIL" />
                </div>

                <?/*if($arResult["USE_CAPTCHA"]):?>
                    <div style="margin-top: 5px">
                        <div>
                            <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                        </div>
                        <div><?echo GetMessage("system_auth_captcha")?></div>
                        <div><input type="text" name="captcha_word" maxlength="50" value="" /></div>
                    </div>
                <?endif*/?>

                <div class="input-btn">
                    <div class="load">
                        <div class="xLoader form-preload"><div class="audio-wave"><span></span><span></span><span></span><span></span><span></span></div></div>
                    </div>

                    <button type="button" class="button-def main-color big active <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?> forgotpassword-submit" name="send_account_info" value=""><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_FORGOT_PAGE_BTN_NAME"]?></button>

                </div>
                
            
            </form>

            <?/*$result = $APPLICATION->arAuthResult;?>


            
            <?if($result["TYPE"] == "OK"):?>
                
                <div class="success">
                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_FORGOT_PAGE_SUCCESS"]?>
                </div>
            
            <?else:?>
                
                <?if($result["TYPE"] == "ERROR"):?>
                    <div class="error"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_FORGOT_PAGE_ERROR"]?></div>
                <?endif;?>
            
                
            
            <?endif;?>
            
            <script type="text/javascript">
                document.bform.onsubmit = function(){document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;};
                <?document.bform.USER_LOGIN.focus();?>
            </script>*/?>
            
        </div>

    </div>

</div>


