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

<?global $PHOENIX_TEMPLATE_ARRAY;?>

<?
$page = array();
$page["BLOG"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["BLOG"]."/";
$page["NEWS"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["NEWS"]."/";
$page["ACTIONS"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["ACTIONS"]."/";
?>

<div class="col-12">
    <div class="section-block show-hidden-parent" id="to-<?=$arParams["TYPE_CODE"]?>">
        <div class="section-head">
            <div class="title-wrap">
                <div class="title"><?=$arParams["TITLE"]?> <span class="title-count hidden-sm hidden-xs">(<?=$arParams["SEARCH_RESULT"]["COUNT_TOTAL"]?>)</span></div>
            </div>

            <?if( $arParams["SEARCH_RESULT"]["COUNT_TOTAL"] > 4 && $arParams["VIEW"] == "short" ):?>
            	<a href="<?=$page[$arParams["TYPE_CODE"]]?>?q=<?=$arParams["QUERY"]?>" class="btn-trasparent hidden-sm hidden-xs"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL"]?></a>
            <?endif;?>

        </div>

        <div class="section-block-content">

            <div class="<?=($arParams["VIEW"] == "short")?"hidden-sm hidden-xs":"";?>">

                <?if( $arParams["TYPE_CODE"] == "BLOG" ):?>

                    <?
                        $APPLICATION->IncludeComponent(
                            "concept:phoenix.news-blogs-actions-list",
                            "",
                            Array(
                                "COMPOSITE_FRAME_MODE" => "N",
                                "COL_LG"=> "3",
                                "DISPLAY_DATE" => "N",
                                "DISPLAY_NAME" => "N",
                                "DISPLAY_PICTURE" => "N",
                                "DISPLAY_PREVIEW_TEXT" => "N",
                                "ELEMENTS_ID" => $arResult["ELENETNS_ID"],
                                "SEARCH_SORT" => "Y"
                            )
                        );
                    ?>

                <?else:?>

                    <div class="news flat">

                        <?
                            $APPLICATION->IncludeComponent(
                                "concept:phoenix.news-blogs-actions-list",
                                "flat",
                                Array(
                                    "COMPOSITE_FRAME_MODE" => "N",
                                    "COL_LG"=> "3",
                                    "DISPLAY_DATE" => "N",
                                    "DISPLAY_NAME" => "N",
                                    "DISPLAY_PICTURE" => "N",
                                    "DISPLAY_PREVIEW_TEXT" => "N",
                                    "ELEMENTS_ID" => $arResult["ELENETNS_ID"],
                                    "SEARCH_SORT" => "Y"
                                )
                            );
                        ?>
                    </div>

                <?endif;?>

            </div>

            <?if(($arParams["VIEW"] == "short")):?>

                <div class="visible-sm visible-xs">
                    <div class="ex-row">
                    
                        <?
                            $APPLICATION->IncludeComponent(
                                "concept:phoenix.news-blogs-actions-list",
                                "slider",
                                Array(
                                    "COMPOSITE_FRAME_MODE" => "N",
                                    "DISPLAY_DATE" => "N",
                                    "DISPLAY_NAME" => "N",
                                    "DISPLAY_PICTURE" => "N",
                                    "DISPLAY_PREVIEW_TEXT" => "N",
                                    "ELEMENTS_ID" => $arResult["ELENETNS_ID"],
                                    "SEARCH_SORT" => "Y"
                                )
                            );
                        ?>

                    </div>

                </div>

            <?endif;?>

            <?if( $arParams["SEARCH_RESULT"]["COUNT_TOTAL"] > 4 && $arParams["VIEW"] == "short" ):?>
                <a href="<?=$page[$arParams["TYPE_CODE"]]?>?q=<?=$arParams["QUERY"]?>" class="btn-trasparent mob visible-sm visible-xs"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL"]?></a>
            <?endif;?>

            <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
                <?=$arResult["NAV_STRING"]?>
            <?endif;?>
        </div>
    </div>
</div>


<?endif;?>