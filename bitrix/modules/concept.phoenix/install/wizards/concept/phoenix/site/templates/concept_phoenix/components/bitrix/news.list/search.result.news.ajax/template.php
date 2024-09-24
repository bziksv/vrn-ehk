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

<?if(!empty($arResult["ITEMS"]) ):?>
    <div class="ajax-search-results-row <?=$arParams["LINE_VIEW_SIZE"]?>">
        <div class="section-head">
            <div class="gr-line"></div>
            <div class="title"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_RESULT_".$arParams["TYPE_CODE"]]?>
            </div>
        </div>

        <div class="section-block-content news">
            <div class="row">

                <?foreach ($arResult['ITEMS'] as $keyItem => $arItem):?>

                    <?if(($keyItem+1) > $arResult["PAGE_ELEMENT_COUNT"])
                        break;?>

                    <div class="<?=$arResult["COLS_ITEMS"]?>">
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="d-block search-item">
                            <table class="search-item">
                                <tr>
                                    <td class="search-item-img">
                                        <div class="search-item-img row no-gutters align-items-center">
                                            <div class="col-12">
                                                <img src="<?=$arItem['PREVIEW_PICTURE_SRC']?>" alt=""/>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="search-item-name">
                                        <div class="search-item-name wspace-normal">
                                            <?=str_replace(array("<br/>", "<br>")," ", $arItem["~NAME"])?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </a>
                    </div>

                <?endforeach;?>

                <?if($arParams["SEARCH_RESULT"]["COUNT_ELEMENTS"] > $arResult["PAGE_ELEMENT_COUNT"]):?>
                    <div class="<?=$arResult["COLS_ITEMS"]?> align-self-center">
                        <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"][$arParams["TYPE_CODE"]]."/"?>?q=<?=$arParams["QUERY"]?>" target="_blank" class="button-def secondary <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> btn-show-all"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL_".$arParams["TYPE_CODE"]]?>  <span>(<?=$arParams["SEARCH_RESULT"]["COUNT_ELEMENTS"]?>)</span></a>

                    </div>
                <?endif;?>

            </div>
        </div>
    </div>

<?endif;?>