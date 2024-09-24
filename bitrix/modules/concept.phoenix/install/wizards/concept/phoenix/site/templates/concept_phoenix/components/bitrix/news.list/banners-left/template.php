<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?
    global $PHOENIX_TEMPLATE_ARRAY;
    $count = 0;
?>

<?if(!empty($arResult["ITEMS"])):?>



<?if($arParams["VIEW"] === "flat"):?>

    <div class="menu-banners menu-banners-flat">

        <?foreach($arResult["ITEMS"] as $banner):?>
            
            <?

            $onlyIMG = "";

            if(strlen($banner['PROPERTIES']['BANNER_UPTITLE']['~VALUE'])<=0
                && strlen($banner['PROPERTIES']['BANNER_TITLE']['~VALUE'])<=0
                && empty($banner['PROPERTIES']['BANNER_TEXT']['VALUE'])
                && strlen($banner['PROPERTIES']['BANNER_BTN_NAME']['~VALUE'])<=0

                && strlen($banner['PROPERTIES']['BANNER_DESC']['~VALUE'])<=0)
                $onlyIMG = "only-img";

                $imgBG['src'] = '';
    

                if(strlen($banner['DETAIL_PICTURE']["ID"]) > 0)
                    $imgBG = CFile::ResizeImageGet($banner['DETAIL_PICTURE']["ID"], array('width'=>600, 'height'=>800), BX_RESIZE_IMAGE_EXACT, true);

                $arClass = array();
                $arClass=array(
                    "XML_ID"=> $banner["PROPERTIES"]["BANNER_BTN_TYPE"]["VALUE_XML_ID"],
                    "FORM_ID"=> $banner["PROPERTIES"]["BANNER_BTN_FORM"]["VALUE"],
                    "MODAL_ID"=> $banner["PROPERTIES"]["BANNER_BTN_MODAL"]["VALUE"],
                    "QUIZ_ID"=> $banner["PROPERTIES"]["BANNER_BTN_QUIZ"]["VALUE"],
                );

                $arAttr=array();
                $arAttr=array(
                    "XML_ID"=> $banner["PROPERTIES"]["BANNER_BTN_TYPE"]["VALUE_XML_ID"],
                    "FORM_ID"=> $banner["PROPERTIES"]["BANNER_BTN_FORM"]["VALUE"],
                    "MODAL_ID"=> $banner["PROPERTIES"]["BANNER_BTN_MODAL"]["VALUE"],
                    "LINK"=> $banner["PROPERTIES"]["BANNER_LINK"]["VALUE"],
                    "BLANK"=> $banner["PROPERTIES"]["BANNER_BTN_BLANK"]["VALUE_XML_ID"],
                    "HEADER"=> $banner['NAME'],
                    "QUIZ_ID"=> $banner["PROPERTIES"]["BANNER_BTN_QUIZ"]["VALUE"],
                    "LAND_ID"=> $banner["PROPERTIES"]["BANNER_BTN_LAND"]["VALUE"]
                );
            ?>
            
            <div class="item <?=$banner['PROPERTIES']['BANNER_COLOR_TEXT']['VALUE_XML_ID']?> <?=$banner['PROPERTIES']['BANNER_BORDER']['VALUE_XML_ID']?> lazyload <?=$onlyIMG?>" style="background-color: <?=$banner['PROPERTIES']['BANNER_USER_BG_COLOR']['VALUE']?>;" <?if(strlen($imgBG['src'])>0):?>data-src="<?=$imgBG['src']?>"<?endif;?>>
                <?CPhoenix::admin_setting($banner, false, 'top')?>


                <?if(strlen($banner['PREVIEW_PICTURE']["SRC"])>0):?>

                    <img data-src="<?=$banner['PREVIEW_PICTURE']["SRC"]?>" alt="<?=$banner['PREVIEW_PICTURE']["ALT"]?>" class="img mx-auto lazyload">

                <?endif;?>

                <?if(strlen($banner['PROPERTIES']['BANNER_UPTITLE']['~VALUE']) > 0):?>
                    <div class="uptitle"><?=$banner['PROPERTIES']['BANNER_UPTITLE']['~VALUE']?></div>
                <?endif;?>

                <?if(strlen($banner['PROPERTIES']['BANNER_TITLE']['~VALUE']) > 0):?>

                    <div class="name bold"><?=$banner['PROPERTIES']['BANNER_TITLE']['~VALUE']?></div>

                <?endif;?>


                <?if(!empty($banner['PROPERTIES']['BANNER_TEXT']['VALUE'])):?>

                    <div class="desc"><?=$banner['PROPERTIES']['BANNER_TEXT']['~VALUE']['TEXT']?></div>

                <?endif;?>

                <?if(strlen($banner['PROPERTIES']['BANNER_BTN_NAME']['~VALUE']) > 0):?>

                    <a title = "<?=$banner['PROPERTIES']['BANNER_BTN_NAME']['VALUE']?>" class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> <?=CPhoenix::buttonEditClass ($arClass)?>" <?=CPhoenix::buttonEditAttr ($arAttr)?>><?=$banner['PROPERTIES']['BANNER_BTN_NAME']['~VALUE']?></a>

                <?endif;?>
                

                <?if($banner['PROPERTIES']['BANNER_ACTION_ALL_WRAP']['VALUE'] == 'Y' || strlen($onlyIMG)>0):?>
                    <a class='menu-banner-wrap <?=CPhoenix::buttonEditClass ($arClass)?>' <?=CPhoenix::buttonEditAttr ($arAttr)?>></a>
                <?endif;?>
               
            </div>

            <?if(strlen($banner['PROPERTIES']['BANNER_DESC']['~VALUE']) > 0):?>

                <div class="more-desc italic"><?=$banner['PROPERTIES']['BANNER_DESC']['~VALUE']?></div>

            <?endif;?>

        <?endforeach;?>
    </div>

<?else:?>
    <?
        $firstItem = array_shift($arResult["ITEMS"]);
        array_unshift($arResult["ITEMS"], $firstItem);
    ?>
    <div class="img-for-lazyload-parent">
        <img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>" alt="">
        

        <div class="menu-banners menu-banner-slider parent-slider-item-js">

            <?foreach($arResult["ITEMS"] as $banner):?>
                <div class="<?=($count!=0)?'noactive-slide-lazyload':'';?>">
                    <?

                    $onlyIMG = "";

                    if(strlen($banner['PROPERTIES']['BANNER_UPTITLE']['~VALUE'])<=0
                        && strlen($banner['PROPERTIES']['BANNER_TITLE']['~VALUE'])<=0
                        && empty($banner['PROPERTIES']['BANNER_TEXT']['VALUE'])
                        && strlen($banner['PROPERTIES']['BANNER_BTN_NAME']['~VALUE'])<=0

                        && strlen($banner['PROPERTIES']['BANNER_DESC']['~VALUE'])<=0)
                        $onlyIMG = "only-img";

                        $imgBG['src'] = '';
            

                        if(strlen($banner['DETAIL_PICTURE']["ID"]) > 0)
                            $imgBG = CFile::ResizeImageGet($banner['DETAIL_PICTURE']["ID"], array('width'=>600, 'height'=>800), BX_RESIZE_IMAGE_EXACT, true);

                        $arClass = array();
                        $arClass=array(
                            "XML_ID"=> $banner["PROPERTIES"]["BANNER_BTN_TYPE"]["VALUE_XML_ID"],
                            "FORM_ID"=> $banner["PROPERTIES"]["BANNER_BTN_FORM"]["VALUE"],
                            "MODAL_ID"=> $banner["PROPERTIES"]["BANNER_BTN_MODAL"]["VALUE"],
                            "QUIZ_ID"=> $banner["PROPERTIES"]["BANNER_BTN_QUIZ"]["VALUE"],
                        );

                        $arAttr=array();
                        $arAttr=array(
                            "XML_ID"=> $banner["PROPERTIES"]["BANNER_BTN_TYPE"]["VALUE_XML_ID"],
                            "FORM_ID"=> $banner["PROPERTIES"]["BANNER_BTN_FORM"]["VALUE"],
                            "MODAL_ID"=> $banner["PROPERTIES"]["BANNER_BTN_MODAL"]["VALUE"],
                            "LINK"=> $banner["PROPERTIES"]["BANNER_LINK"]["VALUE"],
                            "BLANK"=> $banner["PROPERTIES"]["BANNER_BTN_BLANK"]["VALUE_XML_ID"],
                            "HEADER"=> $banner['NAME'],
                            "QUIZ_ID"=> $banner["PROPERTIES"]["BANNER_BTN_QUIZ"]["VALUE"],
                            "LAND_ID"=> $banner["PROPERTIES"]["BANNER_BTN_LAND"]["VALUE"]
                        );
                   ?>
                    
                    <div class="item <?=$banner['PROPERTIES']['BANNER_COLOR_TEXT']['VALUE_XML_ID']?> <?=$banner['PROPERTIES']['BANNER_BORDER']['VALUE_XML_ID']?> lazyload <?=$onlyIMG?>" style="background-color: <?=$banner['PROPERTIES']['BANNER_USER_BG_COLOR']['VALUE']?>;" <?if(strlen($imgBG['src'])>0):?>data-src="<?=$imgBG['src']?>"<?endif;?>>
                        <?CPhoenix::admin_setting($banner, false)?>


                        <?if(strlen($banner['PREVIEW_PICTURE']["SRC"])>0):?>

                            <img data-src="<?=$banner['PREVIEW_PICTURE']["SRC"]?>" alt="<?=$banner['PREVIEW_PICTURE']["ALT"]?>" class="img mx-auto lazyload">

                        <?endif;?>

                        <?if(strlen($banner['PROPERTIES']['BANNER_UPTITLE']['~VALUE']) > 0):?>
                            <div class="uptitle"><?=$banner['PROPERTIES']['BANNER_UPTITLE']['~VALUE']?></div>
                        <?endif;?>

                        <?if(strlen($banner['PROPERTIES']['BANNER_TITLE']['~VALUE']) > 0):?>

                            <div class="name bold"><?=$banner['PROPERTIES']['BANNER_TITLE']['~VALUE']?></div>

                        <?endif;?>


                        <?if(!empty($banner['PROPERTIES']['BANNER_TEXT']['VALUE'])):?>

                            <div class="desc"><?=$banner['PROPERTIES']['BANNER_TEXT']['~VALUE']['TEXT']?></div>

                        <?endif;?>

                        <?if(strlen($banner['PROPERTIES']['BANNER_BTN_NAME']['~VALUE']) > 0):?>

                            <a title = "<?=$banner['PROPERTIES']['BANNER_BTN_NAME']['VALUE']?>" class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> <?=CPhoenix::buttonEditClass ($arClass)?>" <?=CPhoenix::buttonEditAttr ($arAttr)?>><?=$banner['PROPERTIES']['BANNER_BTN_NAME']['~VALUE']?></a>

                        <?endif;?>
                        

                        <?if($banner['PROPERTIES']['BANNER_ACTION_ALL_WRAP']['VALUE'] == 'Y' || strlen($onlyIMG)>0):?>
                            <a class='menu-banner-wrap <?=CPhoenix::buttonEditClass ($arClass)?>' <?=CPhoenix::buttonEditAttr ($arAttr)?>></a>
                        <?endif;?>
                       
                    </div>

                    <?if(strlen($banner['PROPERTIES']['BANNER_DESC']['~VALUE']) > 0):?>

                        <div class="more-desc italic"><?=$banner['PROPERTIES']['BANNER_DESC']['~VALUE']?></div>

                    <?endif;?>
                </div>

                <?
                    if(!$count)
                        $count++;
                ?>

            <?endforeach;?>
        </div>
        
        <img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">
    </div>

<?endif;?>
<?endif;?>