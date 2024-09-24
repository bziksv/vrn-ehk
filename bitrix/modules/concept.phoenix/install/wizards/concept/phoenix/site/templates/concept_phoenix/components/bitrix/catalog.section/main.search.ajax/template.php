<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main;

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

<?global $PHOENIX_TEMPLATE_ARRAY;?>

<?if(!empty($arResult['ITEMS'])):?>

<?$countItems = count($arResult["ITEMS"]);?>

<?foreach ($arResult['ITEMS'] as $keyItem => $arItem):?>
    
    <div class="<?=$arParams["COLS_GOODS"]?>">
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="d-block search-item">
            <table class="search-item m-bottom-sm">
                <tr>
                    <td class="search-item-img">
                        <div class="search-item-img row no-gutters align-items-center">
                            <div class="col-12">
                                <img src="<?=$arItem["FIRST_ITEM"]['PHOTO']?>" alt=""/>
                            </div>
                        </div>
                    </td>
                    <td class="search-item-name">

                        <div class="search-item-name" title="<?=CPhoenix::prepareText($arItem["NAME"])?>">
                            <?=CPhoenix::prepareText($arItem["NAME"])?>
                        </div>

                        <div class="search-item-prices">

                            <?if($arItem['HAVEOFFERS']):?>
                                <div class="search-item-actual-price">
                                    <?=$arItem['OFFER_DIFF']['VALUE']['HTML']?>
                                </div>

                            <?else:?>

                                <?if($arItem["FIRST_ITEM"]["SHOW_DISCOUNT"]):?>
                                    <div class="search-item-old-price">
                                        <?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE']?>
                                    </div>
                                <?endif;?>
                                
                                <div class="search-item-actual-price">
                                    <?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?>
                                </div>

                            <?endif;?>
                            
                        </div>
                    </td>
                </tr>
            </table>
        </a>
    </div>

<?endforeach;?>

<?endif;?>