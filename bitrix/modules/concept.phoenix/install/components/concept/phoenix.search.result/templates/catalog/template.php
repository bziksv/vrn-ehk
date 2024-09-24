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

$display_bottom_pager = "N";
$news_count = "4";

$template = "search.result.catalog";

if($arParams["MODE"] == "ajax")
{
    $arParams["VIEW"] == "";
    $news_count = "6";
    $template = "search.result.catalog.ajax";
}

if($arParams["VIEW"] == "full")
{
    $display_bottom_pager = "Y";
    $news_count = "24";
}


$GLOBALS["arrItemsFilter"]["ID"] = $arResult["ITEMS_ID"];

if( empty($arResult["ITEMS_ID"]) )
    $GLOBALS["arrItemsFilter"]["ID"] = 0;



if ( $arResult["COUNT_TOTAL"] > 0 )
{
    $arFields = array(
        "FILTER_NAME" => "arrItemsFilter",
        "IBLOCK_ID" => $arParams["PARAM2"],
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "ID",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "ASC",
        "CHECK_DATES" => "Y",
        "NEWS_COUNT" => $news_count,
        "DISPLAY_BOTTOM_PAGER" => $display_bottom_pager,
        "FIELD_CODE" => array("ID", "IBLOCK_ID", "IBLOCK_CODE"),
        "PROPERTY_CODE" => array("RESIZE_IMAGES","BUTTON_NAME", "CART_BTN_NAME", "DONT_SHOW_FORM", "ITEM_TEMPLATE", "LABELS"),
        "SET_BROWSER_TITLE" => "N",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_STATUS_404" => "N",
            "SET_TITLE" => "N",
            "SHOW_404" => "N",
        "PAGER_TEMPLATE" => "phoenix_round",
        "SEARCH_RESULT" => $arResult,
        "VIEW" => $arParams["VIEW"],
        "QUERY" => $arParams["QUERY"],
        "TYPE_CODE" => $arParams["CODE"],
        "TITLE" => $arParams["~TITLE"],
        "MODE" => $arParams["MODE"],
        "LINE_VIEW_SIZE" => $arParams["LINE_VIEW_SIZE"]
    );
    
    $APPLICATION->IncludeComponent(
        "bitrix:news.list", 
        $template, 
        $arFields,
        false
    );
}

if($arResult["COUNT_TOTAL"] <= 0 && $arParams["VIEW"] == "full" )
{
    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["NOT_FOUND"]["~VALUE"]))
        echo "<div class='col-12'>".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["NOT_FOUND"]["~VALUE"]."</div>";
    else
        echo "<div class='col-12'>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_RESULT_NOT_FOUND"]."</div>";
}

?>