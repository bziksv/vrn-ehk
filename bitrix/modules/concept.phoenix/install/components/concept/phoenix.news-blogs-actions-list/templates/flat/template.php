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
<?if(!empty($arResult["ITEMS"])):?>
    <div class="wrap-elements row <?if($arParams["ANIMATE"] == "Y"):?>parent-animate<?endif;?>">
            
        <?foreach($arResult["ITEMS"] as $k => $arItem):?>
            <div class="col-lg-<?=$arParams["COL_LG"]?> col-md-6 col-12">
                <div class="wrap-element <?if($arParams["ANIMATE"] == "Y"):?>child-animate opacity-zero<?endif;?>">

                    <div class="element">

                        <?CPhoenix::admin_setting($arItem, false)?>

                        <table>
                            <tr>
                                <td>
                                    <a href='<?=$arItem["DETAIL_PAGE_URL"]?>' class='hover_shine img-wrap'>
                                        <div class='bg-img lazyload' <?if(strlen($arItem['PREVIEW_PICTURE_SRC'])):?> data-src="<?=$arItem['PREVIEW_PICTURE_SRC']?>"<?endif;?>>

                                            <div class="new-dark-shadow"></div>
                                      
                                        </div>
                                        <div class="shine"></div>
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <div class="wrap-text">

                            <?
                                if($arResult["SHOW_DATA"])
                                {
                                    switch($arItem["TYPE_ELEMENT"]){

                                        case ("action"):?>

                                            <div class="date-action">
                                                <span class="<?=$arItem["CURRENT_TIME_ACTION"]["CLASS"]?>"><?=$arItem["CURRENT_TIME_ACTION"]["TITLE"]?></span>
                                            </div>

                                        <?break;

                                        case ("blog"):
                                        case ("news"):
                                        default:?>

                                            <div class="section" title='<?=$arItem["CATEGORY_NAME"]?>'>
                                                <a href='<?=$arItem["CATEGORY_URL"]?>' class="wrap-link-sect"><?=$arItem["CATEGORY_NAME"]?></a>
                                            </div>

                                        <?break;
                                    }
                                }
                            ?>

                            <a href='<?=$arItem["DETAIL_PAGE_URL"]?>'>
                                <div class="new-name bold"><?=$arItem['~NAME']?></div>
                            </a>


                            <?if(isset($arItem["ACTIVE_FROM_FORMATED"])):?>

                                <div class="date">
                                    <?=$arItem["ACTIVE_FROM_FORMATED"]?>
                                </div>

                            <?endif;?>


                            <?if(strlen($arItem["~PREVIEW_TEXT"])):?>
                                <a href='<?=$arItem["DETAIL_PAGE_URL"]?>'>
                                    <div class="new-text"><?=$arItem["~PREVIEW_TEXT"]?></div>
                                </a>
                            <?endif;?>

                        </div>

                    </div>

                    <div class="new-shadow"></div>
                </div>
            </div>
        <?endforeach;?>
    </div>
<?endif;?>