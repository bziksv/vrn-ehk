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
global $PHOENIX_TEMPLATE_ARRAY;
?>
<?$frame = $this->createFrame()->begin("");?>

<?if(!empty($arResult["ITEMS"])):?>
<div class="other-news">

    <?foreach($arResult["ITEMS"] as $arItem):?>

        <?unset($img);?>

        <?if(strlen($arItem["PREVIEW_PICTURE"]["ID"])>0):?>
            <?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array('width'=>600, 'height'=>600), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
        <?endif;?>

        <div class="item lazyload" 

            <?/*style="background-image: url(<?=$img["src"]?>);"*/?>

            data-src="<?=$img["src"]?>"

        >
            <div class="frameshadow"></div>

    
            <div class="new-dark-shadow"></div>

            <div class="cont">
                <div class="name bold"><?=$arItem["~NAME"]?></div>
            </div>
            <a class="wrap-link" href="<?=$arItem["DETAIL_PAGE_URL"]?>"></a>

            <?CPhoenix::admin_setting($arItem, false, 'top')?>
      
        </div>

    <?endforeach;?>
</div>
<?endif;?>
<?$frame->end();?>
