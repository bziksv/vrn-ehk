<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Декоративная художественная ковка элементов и изделий");
$APPLICATION->SetPageProperty("keywords", "Декоративная художественная ковка элементов и изделий");
$APPLICATION->SetPageProperty("title", "Декоративная художественная ковка элементов и изделий");
$APPLICATION->SetTitle("Декоративная ");
?>
<?if(isset($_GET["LIST"]) && !empty($_GET["LIST"]))
{
	$_SESSION["LIST_VIEW"]=$_GET["LIST"];
}
if(isset($_GET["NUMBER"]) && !empty($_GET["NUMBER"]))
{
	$number=$_GET["NUMBER"];
	if($number=="ALL")
	{
		$number=16;
	}
	$_SESSION["NUMBER"]=$number;
}
if(isset($_SESSION["NUMBER"]) && !empty($_SESSION["NUMBER"]))
{
	$number=$_SESSION["NUMBER"];
}
else
{
	$number=16;
}
$sort="sort";
$sort_to="ASC";
if(isset($_GET["sort"]) && !empty($_GET["sort"]))
{
	$sort_arr=explode("-", $_GET["sort"]);
	$sort=$sort_arr[0];
	$sort_to=$sort_arr[1];
}?>
<?



if(isset($_GET["SHOW"]) && !empty($_GET["SHOW"]))
{
    $GLOBALS["BY_LINK"]="Y";
    switch($_GET["SHOW"])
    {
        case "NEW":
            $GLOBALS["arrFilter"]["PROPERTY_NEW_VALUE"]="да";
            $APPLICATION->SetTitle("Новинки");
            break;
		case "NEW_RAZDEL":
			if(empty($_GET["sort"])){
				$sort = "PROPERTY_SORT_CAT_RASP";
				$sort_to = "asc,nulls";
			}
            $GLOBALS["arrFilter"]["PROPERTY_NEW_RAZDEL_VALUE"]="да";
            $APPLICATION->SetTitle(tplvar('name_2'));
            break;
        case "LIDER":
            $GLOBALS["arrFilter"]["PROPERTY_LIDER_VALUE"]="да";
            $APPLICATION->SetTitle("Популярные товары");
            break;
        case "SPEC":
            $GLOBALS["arrFilter"]["PROPERTY_SPEC_VALUE"]="да";
            $APPLICATION->SetTitle("Товары со специальной ценой");
            break;
		 case "SPEC_RAZDEL":
		 if(empty($_GET["sort"])){
			$sort = "PROPERTY_SORT_CAT";
			$sort_to = "asc,nulls";
		 }
            $GLOBALS["arrFilter"]["PROPERTY_SPEC_RAZDEL_VALUE"]="да";
            $APPLICATION->SetTitle(tplvar('name_1'));
            break;
        default:$APPLICATION->SetTitle("Фильтр по товарам");
    }

}
else
{
    $APPLICATION->SetTitle("Каталог товаров");
    $GLOBALS["BY_LINK"]="N";
}




?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	"my_catalog", 
	array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "21",
		"HIDE_NOT_AVAILABLE" => "N",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/catalog/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "N",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "arrFilter",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "NAIMENOVANIE_SAYT02",
			1 => "NAIMENOVANIE_SAYT03",
			2 => "MATERIAL",
			3 => "SPEC_RAZDEL",
			4 => "NEW_RAZDEL",
			5 => "SPEC",
			6 => "CML2_ARTICLE",
			7 => "CML2_BASE_UNIT",
			8 => "LIDER",
			9 => "NEW",
			10 => "CML2_MANUFACTURER",
			11 => "CML2_TRAITS",
			12 => "CML2_TAXES",
			13 => "CML2_ATTRIBUTES",
			14 => "CML2_BAR_CODE",
			15 => "NAIMENOVANIE_SAYT",
			16 => "SPOSOB_IZGOTOVLENIYA",
			17 => "MATERIAL_IZGOTOVLENIYA",
			18 => "TITLE_SECTION",
			19 => "WINDOWS_TITLE",
			20 => "COUNT_RATE",
			21 => "SPEC_PRICE",
			22 => "WIDTH",
			23 => "HEIGHT",
			24 => "LENGTH",
			25 => "SAME",
			26 => "",
		),
		"FILTER_PRICE_CODE" => array(
			0 => "Реализация Ковка",
		),
		"FILTER_VIEW_MODE" => "VERTICAL",
		"USE_REVIEW" => "N",
		"USE_COMPARE" => "N",
		"PRICE_CODE" => array(
			0 => "Реализация Ковка",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"CONVERT_CURRENCY" => "N",
		"BASKET_URL" => "/basket/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"USE_PRODUCT_QUANTITY" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"SHOW_TOP_ELEMENTS" => "N",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_TOP_DEPTH" => "1",
		"SECTIONS_VIEW_MODE" => "LINE",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"PAGE_ELEMENT_COUNT" => $number,
		"LINE_ELEMENT_COUNT" => "3",
		"ELEMENT_SORT_FIELD" => $sort,
		"ELEMENT_SORT_ORDER" => $sort_to,
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"LIST_PROPERTY_CODE" => array(
			0 => "CML2_ARTICLE",
			1 => "CML2_BASE_UNIT",
			2 => "",
		),
		"INCLUDE_SUBSECTIONS" => "N",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_DISPLAY_NAME" => "Y",
		"DETAIL_DETAIL_PICTURE_MODE" => "IMG",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "N",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"LINK_IBLOCK_TYPE" => "",
		"LINK_IBLOCK_ID" => "5",
		"LINK_PROPERTY_SID" => "",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_ALSO_BUY" => "N",
		"USE_STORE" => "N",
		"PAGER_TEMPLATE" => "ehk_all",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"TEMPLATE_THEME" => "blue",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "N",
		"DETAIL_SHOW_MAX_QUANTITY" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"DETAIL_USE_VOTE_RATING" => "N",
		"DETAIL_USE_COMMENTS" => "N",
		"DETAIL_BRAND_USE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"COMPONENT_TEMPLATE" => "my_catalog",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"DETAIL_STRICT_SECTION_CHECK" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"SHOW_DEACTIVATED" => "N",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"COMPATIBLE_MODE" => "Y",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE#/",
			"element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
			"compare" => "compare.php?action=#ACTION_CODE#",
			"smart_filter" => "#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/",
		),
		"VARIABLE_ALIASES" => array(
			"compare" => array(
				"ACTION_CODE" => "action",
			),
		)
	),
	false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
