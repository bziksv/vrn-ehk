<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
    if($arParams["H_POSITION_IMAGE_BLOCK"] == "")
        $arParams["H_POSITION_IMAGE_BLOCK"] = "left";


    if($arParams["H_POSITION_IMAGE_BLOCK"] == "left")
    {
        $title_pos = "offset-sm-4";
        $position_hor = "order-sm-1";
    }

    if($arParams["H_POSITION_IMAGE_BLOCK"] == "right")
    {
        $title_pos = "";
        $position_hor = "order-sm-3";
    }
?>

<?if(!empty($arResult["ITEMS"])):?>
<?foreach($arResult["ITEMS"] as $arItem):?>
    <div class="empl-full">

    	<?CPhoenix::admin_setting($arItem, false)?>

    	<?if(strlen($arItem["PROPERTIES"]["EMPL_DESC"]["~VALUE"])>0):?>

            <div class="empl-desc col-lg-8 col-12 <?=$title_pos?>" title='<?=$arItem["PROPERTIES"]["EMPL_DESC"]["~VALUE"]?>'><?=$arItem["PROPERTIES"]["EMPL_DESC"]["~VALUE"]?></div>

        <?endif;?>

        <div class="empl-table row <?=($arParams["SIDEMENU"])? "no-margin":""?>">


            <div class="empl-cell col-sm-8 col-12 center order-2">

                <div class="empl-name bold"><?=$arItem["~NAME"]?></div>

                <?if(strlen($arItem["~PREVIEW_TEXT"])>0):?>
                    <div class="line main-color"></div>
                    <div class="empl-text"><?=$arItem["~PREVIEW_TEXT"]?></div>
                <?endif;?>

                <?if((strlen($arItem["PROPERTIES"]["EMPL_BTN_NAME"]["~VALUE"])>0 && strlen($arItem["PROPERTIES"]["EMPL_FORMS"]["~VALUE"])>0) || strlen($arItem["PROPERTIES"]["EMPL_PHONE"]["~VALUE"])>0 || strlen($arItem["PROPERTIES"]["EMPL_EMAIL"]["~VALUE"])>0):?>

                    
                    <?if(!$arParams["SIDEMENU"]):?>
                        <div class="wrap-info">

                            <div class="row align-items-center">
                                    
                                <?if(strlen($arItem["PROPERTIES"]["EMPL_BTN_NAME"]["~VALUE"])>0 && strlen($arItem["PROPERTIES"]["EMPL_FORMS"]["VALUE"])>0):?>

                                    <div class="col-xl-4 col-sm-6 col-12 order-sm-1 order-2">
                                        <a class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> call-modal callform" data-call-modal="form<?=$arItem["PROPERTIES"]["EMPL_FORMS"]["VALUE"]?>" data-header='<?=$arParams["BLOCK_TITLE_FORMATED"]?>' title='<?=$arItem["PROPERTIES"]["EMPL_BTN_NAME"]["VALUE"]?>'><?=$arItem["PROPERTIES"]["EMPL_BTN_NAME"]["~VALUE"]?></a>
                                    </div>

                                <?endif;?>

                                <?if(strlen($arItem["PROPERTIES"]["EMPL_PHONE"]["~VALUE"])>0 || (strlen($arItem["PROPERTIES"]["EMPL_EMAIL"]["~VALUE"])>0)):?>
                                    <div class="col-xl-8 col-sm-6 col-12 order-sm-2 order-1 contacts-board">
                                        <div class="row align-items-center">
                                       
                                            <?if(strlen($arItem["PROPERTIES"]["EMPL_PHONE"]["~VALUE"])>0):?>

                                                <div class="col-sm-6 col-12">

                                                    <div class="empl-phone bold"><span title='<?=$arItem["PROPERTIES"]["EMPL_PHONE"]["VALUE"]?>'>

                                                        <a href="tel:<?=$arItem["PHONE_TRIM"]?>"><?=$arItem['PROPERTIES']['EMPL_PHONE']['~VALUE']?></a>
                                                    </span></div>

                                                </div>

                                            <?endif;?>

                                            <?if(strlen($arItem["PROPERTIES"]["EMPL_EMAIL"]["~VALUE"]) > 0):?>
                                                <div class="col-sm-6 col-12">
                                                    <div class="empl-email"><a href="mailto:<?=$arItem["PROPERTIES"]["EMPL_EMAIL"]["~VALUE"]?>"><span class="bord-bot" title='<?=$arItem["PROPERTIES"]["EMPL_EMAIL"]["VALUE"]?>'><?=$arItem["PROPERTIES"]["EMPL_EMAIL"]["~VALUE"]?></span></a></div>
                                                </div>
                                            <?endif;?>

                                        </div>
                                    </div>
                                <?endif;?>

                            </div>
                          
                        
                        </div>

                    <?else:?>

                        <div class="wrap-info">

                                <?if(strlen($arItem["PROPERTIES"]["EMPL_PHONE"]["~VALUE"])>0 || (strlen($arItem["PROPERTIES"]["EMPL_EMAIL"]["~VALUE"])>0)):?>
                                    <div class="contacts-board">
                                        <div class="row align-items-center">
                                       
                                            <?if(strlen($arItem["PROPERTIES"]["EMPL_PHONE"]["~VALUE"])>0):?>

                                                <div class="col-sm-6 col-12">

                                                    <div class="empl-phone bold"><span title='<?=$arItem["PROPERTIES"]["EMPL_PHONE"]["VALUE"]?>'>

                                                        <a href="tel:<?=$arItem["PHONE_TRIM"]?>"><?=$arItem['PROPERTIES']['EMPL_PHONE']['~VALUE']?></a>
                                                    </span></div>

                                                </div>

                                            <?endif;?>

                                            <?if(strlen($arItem["PROPERTIES"]["EMPL_EMAIL"]["~VALUE"]) > 0):?>
                                                <div class="col-sm-6 col-12">
                                                    <div class="empl-email"><a href="mailto:<?=$arItem["PROPERTIES"]["EMPL_EMAIL"]["~VALUE"]?>"><span class="bord-bot" title='<?=$arItem["PROPERTIES"]["EMPL_EMAIL"]["VALUE"]?>'><?=$arItem["PROPERTIES"]["EMPL_EMAIL"]["~VALUE"]?></span></a></div>
                                                </div>
                                            <?endif;?>

                                        </div>
                                    </div>
                                <?endif;?>

                            <?if(is_array($arItem["PROPERTIES"]["TEXT"]["VALUE"])):?>
                                <div class="wr-btn">
                                    <a class="button-def main-color <?=$btn_size?> <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> call-modal callemployee " data-call-modal="<?=$arItem["ID"]?>"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MORE_EMPLOYEE"]?></a>
                                </div>
                            <?else:?>

                                <?if(strlen($arItem["PROPERTIES"]["EMPL_BTN_NAME"]["~VALUE"])>0 && strlen($arItem["PROPERTIES"]["EMPL_FORMS"]["VALUE"])>0):?>

                                    <div class="wr-btn">
                                        <a class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> call-modal callform" data-call-modal="form<?=$arItem["PROPERTIES"]["EMPL_FORMS"]["VALUE"]?>" data-header='<?=$arParams["BLOCK_TITLE_FORMATED"]?>' title='<?=$arItem["PROPERTIES"]["EMPL_BTN_NAME"]["VALUE"]?>'><?=$arItem["PROPERTIES"]["EMPL_BTN_NAME"]["~VALUE"]?></a>
                                    </div>

                                <?endif;?>
                             <?endif;?>

                          
                        
                        </div>

                    <?endif;?>

                <?endif;?>
            </div>


            <div class="empl-cell col-sm-4 col-12 <?=$position_hor?> order-1">
                <div class="bg-fone"></div>

                <div class="container-photo">

                    <div class="row">

                        <div class="col-12">

                            <div class="wrap-photo">

                                <img class="mx-auto d-block<?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?> wow zoomIn<?endif;?> lazyload" data-src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" alt='<?=$arItem["PREVIEW_PICTURE_DESCRIPTION"]?>'>
                                <?if(strlen($arItem["~DETAIL_TEXT"])>0):?>
                                    <div class="icon-center main-color"><span></span></div>
                                <?endif;?>
                            </div>

                        </div>

                        <div class="col-12">

                            <?if(strlen($arItem["~DETAIL_TEXT"])>0):?>
                                <div class="empl-under-desc italic"><?=$arItem["~DETAIL_TEXT"]?></div>
                            <?endif;?>

                            <?if(strlen($arItem["DETAIL_PICTURE_SRC"])):?>

                                <img data-src="<?=$arItem["DETAIL_PICTURE_SRC"]?>" class="mx-auto d-block under img-fluid lazyload" alt='<?=$arItem["DETAIL_PICTURE_DESCRIPTION"]?>'>

                            <?endif;?>

                        </div>

                    </div>

                </div>
                
            </div>

        </div>

    </div>
<?endforeach;?>
<?endif;?>