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

    <?if($arResult["COLS_ISSET"]):?>
        <div class="row">
    <?endif;?>

        <?foreach($arResult["ITEMS"] as $arItem):?>

            <?if($arResult["COLS_ISSET"]):?>
                <div class="<?=$arParams["COLS"]?>">
            <?endif;?>

                <div class="banner-flat-item lazyload parent-tool-settings" <?if(strlen($arItem["PREVIEW_PICTURE_SRC"])>0):?>data-src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>"<?endif;?> style="background-color: <?=$arItem['PROPERTIES']['BANNER_USER_BG_COLOR']['VALUE']?>;">
                    <div class="bg-tone"></div>
                    <div class="bottom-tone"></div>
                    <div class="text bold"><?=$arItem["~NAME"]?></div>

                    <a class="wr-link <?=CPhoenix::buttonEditClass ($arItem["CLASS"])?>" <?=CPhoenix::buttonEditAttr ($arItem["ATTRS"])?>></a>

                    <?CPhoenix::admin_setting($arItem, false, 'top')?>
                </div>
            <?if($arResult["COLS_ISSET"]):?>
                </div>
            <?endif;?>

        <?endforeach;?>


    <?if($arResult["COLS_ISSET"]):?>
        </div>
    <?endif;?>
<?endif;?>
<?$frame->end();?>
