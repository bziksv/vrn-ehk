<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>


<?if(!empty($arResult["ITEMS"])):?>
    <div class="empl 
                empl-flat
                <?if(isset($arParams["ANIMATE"]) && $arParams["ANIMATE"] == "Y"):?>parent-animate<?endif;?>
                row 
                no-margin 
                <?if(isset($arParams["SIDEMENU"]) && !$arParams["SIDEMENU"]):?>justify-content-center<?endif;?>"
    >

        <?foreach($arResult["ITEMS"] as $key=>$arItem):?>

            <div class="wrap-element elem-hover <?=$arResult["COLS_CLASS"]?> <?if(isset($arParams["ANIMATE"]) && $arParams["ANIMATE"] == "Y"):?>child-animate opacity-zero<?endif;?>">

                <div class="element elem-hover-height-more">
                    

                    <div class="elem-hover-height">

                        <div class="wr-empl-face row align-items-center">
                            <div class="col-12">
                                <div class="empl-face lazyload <?=(isset($arParams["PICTURE_ROUND"]))?$arParams["PICTURE_ROUND"]:""?>" data-src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>"></div>
                            </div>
                        </div>

                        <div class="wrap-text">

                            <?if(strlen($arResult["SECTIONS"][$arItem['IBLOCK_SECTION_ID']]['NAME'])>0):?>
                                <div class="section" title='<?=$arResult["SECTIONS"][$arItem['IBLOCK_SECTION_ID']]['NAME']?>'><?=$arResult["SECTIONS"][$arItem['IBLOCK_SECTION_ID']]['~NAME']?></div>
                            <?endif;?>

                            <div class="empl-name bold"><?=$arItem['~NAME']?></div>

                            <?if(strlen($arItem['PROPERTIES']['EMPL_DESC']['VALUE'])>0):?>
                                <div class="empl-desc italic"><?=$arItem['PROPERTIES']['EMPL_DESC']['~VALUE']?></div>
                            <?endif;?>

                        </div>
                    </div>

                    <?if(strlen($arItem['PROPERTIES']['EMPL_PHONE']['VALUE'])>0 || strlen($arItem['PROPERTIES']['EMPL_EMAIL']['VALUE'])>0 || (strlen($arItem['PROPERTIES']['EMPL_BTN_NAME']['VALUE'])>0 && strlen($arItem['PROPERTIES']['EMPL_FORMS']['VALUE'])>0) || is_array($arItem["PROPERTIES"]["TEXT"]["VALUE"])):?>
                            <div class="hide-part elem-hover-show">
                                

                                <?if(is_array($arItem["PROPERTIES"]["TEXT"]["VALUE"])):?>
                                    <div class="wrap-button">
                                       <a class="button-def main-color <?=$btn_size?> <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> call-modal callemployee" data-all-id="<?=$arResult["ALL_ID"]?>" data-call-modal="<?=$arItem["ID"]?>"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MORE_EMPLOYEE"]?></a>
                                    </div>
                                    
                                <?else:?>

                                    <?if(strlen($arItem['PROPERTIES']['EMPL_PHONE']['VALUE'])>0):?>
                                        <div class="empl-phone bold">

                                            <span title='<?=$arItem['PROPERTIES']['EMPL_PHONE']['VALUE']?>'>

                                                <a href="tel:<?=$arItem["PHONE_TRIM"]?>"><?=$arItem['PROPERTIES']['EMPL_PHONE']['~VALUE']?></a>

                                            </span>

                                        </div>
                                    <?endif;?>

                                    <?if(strlen($arItem['PROPERTIES']['EMPL_EMAIL']['VALUE'])>0):?>
                                        <div class="empl-email"><a href="mailto:<?=$arItem['PROPERTIES']['EMPL_EMAIL']['VALUE']?>" title='<?=$arItem['PROPERTIES']['EMPL_EMAIL']['VALUE']?>'><span class="bord-bot"><?=$arItem['PROPERTIES']['EMPL_EMAIL']['~VALUE']?></span></a></div>
                                    <?endif;?>

                                    <?if(strlen($arItem['PROPERTIES']['EMPL_BTN_NAME']['VALUE'])>0 && strlen($arItem['PROPERTIES']['EMPL_FORMS']['VALUE'])>0):?>

                                        <div class="wrap-button">
                                            <a class="button-def main-color call-modal callform <?=$btn_size?> <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?>"  data-call-modal="form<?=$arItem['PROPERTIES']['EMPL_FORMS']['VALUE']?>" data-header='<?=$arParams["BLOCK_TITLE_FORMATED"]?>' title='<?=$arItem['PROPERTIES']['EMPL_BTN_NAME']['VALUE']?>'><?=$arItem['PROPERTIES']['EMPL_BTN_NAME']['~VALUE']?></a>
                                        </div>
                                    <?endif;?>
                                <?endif;?>
                                
                            </div>
                    <?endif;?>

                    <?if(is_array($arItem["PROPERTIES"]["TEXT"]["VALUE"])):?>
                        <a class="call-modal callemployee callemployee-css" data-all-id="<?=$arResult["ALL_ID"]?>" data-call-modal="<?=$arItem["ID"]?>"></a>
                    <?endif;?>

                </div>

                <?CPhoenix::admin_setting($arItem, false)?>

            </div>

        <?endforeach;?>

    </div>
<?endif;?>