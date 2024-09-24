<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?global $PHOENIX_TEMPLATE_ARRAY;?>


<div class="container">

    <div class="changepassword col-md-4 col-sm-8 col-12">

        <div class="success" style="display: none;"></div>

        <div class="bx-auth">
            
            <div class="errors" style="display: none;"></div>
        
            <form class="form changepassword" method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform">
            
                <?if (strlen($arResult["BACKURL"]) > 0): ?>
                   <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
                <? endif ?>
                
                <input type="hidden" name="AUTH_FORM" value="Y">
                <input type="hidden" name="TYPE" value="CHANGE_PWD">
                
        
                <div style="display: none;">
                    <td><input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" class="bx-auth-input" />
                    <td><input type="text" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="bx-auth-input" />
                </div>
                
                <div class="input <?if(strlen($arResult["USER_PASSWORD"]) > 0):?>in-focus<?endif;?>">                  
                    <div class="bg"></div>
                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_CHANGEPASSWORD_PAGE_NEW_PASSWORD_REQ"]?></span>
                    <input class="focus-anim require" type="password" name="USER_PASSWORD" id="USER_PASSWORD" value="<?=$arResult["USER_PASSWORD"]?>" autocomplete="off" />
                </div>
                
                
                <?/*if($arResult["SECURE_AUTH"]):?>
                
                    <span class="bx-auth-secure" id="bx_auth_secure" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_CHANGEPASSWORD_PAGE_SECURE_NOTE"]?>" style="display:none">
                        <div class="bx-auth-secure-icon"></div>
                    </span>
                    
                    <noscript>
                        <span class="bx-auth-secure" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_CHANGEPASSWORD_PAGE_NONSECURE_NOTE"]?>">
                            <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                        </span>
                    </noscript>
                
                    <script type="text/javascript">
                        document.getElementById('bx_auth_secure').style.display = 'inline-block';
                    </script>
                <?endif*/?>
                
                <div class="input <?if(strlen($arResult["USER_CONFIRM_PASSWORD"]) > 0):?>in-focus<?endif;?>">                  
                    <div class="bg"></div>
                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_CHANGEPASSWORD_PAGE_NEW_PASSWORD_CONFIRM"]?></span>
                    <input class="focus-anim require" type="password" name="USER_CONFIRM_PASSWORD" id="USER_CONFIRM_PASSWORD" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" autocomplete="off" />
                </div>

                 <div class="input-btn">

                    <div class="load">
                        <div class="xLoader form-preload"><div class="audio-wave"><span></span><span></span><span></span><span></span><span></span></div></div>
                    </div>

                    <button type="button" class="button-def main-color big active <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?> changepassword-submit" name="change_pwd" value=""><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_CHANGEPASSWORD_PAGE_CHANGE"]?></button>
                </div>

                <?if(!empty($arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"])):?>

                    <div class="alert-group-policy">
                        <?=$arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]?>
                    </div>

                <?endif;?>
        
            
            </form>
            
        </div>
    
    </div>

</div>