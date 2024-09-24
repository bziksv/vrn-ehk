<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>

<?
    if($USER->isAuthorized())
        LocalRedirect(SITE_DIR.'personal/');
?>


    
<?if($arResult["FORM_TYPE"] == "login"):?>
    <?global $PHOENIX_TEMPLATE_ARRAY;?>

    <div class=
            "
                page-header
                cover
                parent-scroll-down
                <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>
                phoenix-firsttype-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
                padding-bottom-section
            " 

    >

        <div class="shadow-tone <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>"></div>

        <div class="top-shadow"></div>

        <div class="container">

            <div class="row">
                <div class="col-12 part part-left">
                        
                    <div class="head margin-bottom">
                        <div class="title main1"><h1><?$APPLICATION->ShowTitle(false);?></h1></div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="cabinet-wrap auth-page">
        <div class="container">
            <div class="block-move-to-up">
            	<div class="auth-block pad_top_container">
                    <div class="row">

                        <?if(strlen($_GET["confirm_user_id"])>0):?>

                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:system.auth.confirmation",
                                    "main",
                                    Array(
                                        "COMPOSITE_FRAME_MODE" => "N",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                        "CONFIRM_CODE" => "confirm_code",
                                        "LOGIN" => "login",
                                        "USER_ID" => "confirm_user_id"
                                    )
                                );?>
                            </div>

                        <?else:?>
                        
                            <div class="col-lg-4 col-md-6 col-12">
                                <form class="form auth" action="#">
                                    <div class="row inputs-block">
                                        <div class="col-12 title-form main1">
                                            <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_LOGIN_TITLE"]?>
                                        </div>
                                        <div class="col-12">
                                            <div class="input">
                                                <div class="bg"></div>
                                                <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_LOGIN_INPUT"]?></span>
                                                <input class='focus-anim require' name="auth-login" type="text" value="" />
                                            </div>
                                            <div class="input">
                                                <div class="bg"></div>
                                                <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_PASSWORD_INPUT"]?></span>
                                                <input class='focus-anim require' name="auth-password" type="password" />
                                            </div>
                                            <div class="errors"></div>
                                        </div>

                                        <div class="col-12">
                                            <div class="input-btn">
                                                <div class="load">
                                                    <div class="xLoader form-preload"><div class="audio-wave"><span></span><span></span><span></span><span></span><span></span></div></div>
                                                </div>
                                                <button class="button-def main-color big active <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?> auth-submit" name="form-submit" type="button"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_BTN_ENTER"]?></button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row links-block">
                                        <div class="col-sm-6 col-12">
                                            <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["FORGOT_PASSWORD_URL"]["VALUE"]?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["FORGOT_PASSWORD_URL"]["DESCRIPTION"]?></a>
                                        </div>
                                        
                                        <div class="col-sm-6 col-12">
                                            <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["REGISTER_URL"]["VALUE"]?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["REGISTER_URL"]["DESCRIPTION"]?></a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-8 col-md-6 hidden-xs">
                                    
                                <div class="reg">
                                    <div class="reg-comment">
                                        <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_REGISTER_INDEX_PAGE_COMMENT"]?>
                                    </div>
                                    
                                </div>
                            </div>

                        <?endif;?>

                    </div>    
                </div>
            </div>
        </div>
    </div>

<?endif;?>