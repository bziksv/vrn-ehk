<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


if(CModule::IncludeModule("concept.phoenix"))
{

	global $PHOENIX_TEMPLATE_ARRAY;
	CPhoenix::phoenixOptionsValues($site_id, array("shop","catalog", "design"));
	CPhoenix::setMainMess();


	$APPLICATION->IncludeComponent(
    	"bitrix:catalog.products.viewed",
	    "main",
	    Array(
	    	'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
	    	"VIEW" => "FLAT SLIDER",
	    	"COUNT" => ($_POST["COUNT"])?$_POST["COUNT"]:"4",

	        "ACTION_VARIABLE" => "action_cpv",
	        "ADD_PROPERTIES_TO_BASKET" => "Y",
	        "ADD_TO_BASKET_ACTION" => "ADD",
	        "BASKET_URL" => "",
	        "CACHE_GROUPS" => "Y",
	        "CACHE_NOTES" => "",
	        "CACHE_TIME" => "3600",
	        "CACHE_TYPE" => "A",
	        "COMPOSITE_FRAME_MODE" => "N",
	        "COMPOSITE_FRAME_TYPE" => "AUTO",
	        'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
	        "DEPTH" => "2",
	        "DISPLAY_COMPARE" => "N",
	        "ENLARGE_PRODUCT" => "STRICT",
	        'HIDE_NOT_AVAILABLE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"],
	            'HIDE_NOT_AVAILABLE_OFFERS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"],
	        "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
	        "IBLOCK_MODE" => "single",
	        "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_TYPE"],
	        "LABEL_PROP_POSITION" => "top-left",
	        "MESS_BTN_ADD_TO_BASKET" => "",
	        "MESS_BTN_BUY" => "",
	        "MESS_BTN_DETAIL" => "",
	        "MESS_BTN_SUBSCRIBE" => "",
	        "MESS_NOT_AVAILABLE" => "",
	        "PAGE_ELEMENT_COUNT" => "9",
	        "PARTIAL_PRODUCT_PROPERTIES" => "N",
	        "PRICE_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
	        "PRICE_VAT_INCLUDE" => "Y",
	        "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
	        "PRODUCT_ID_VARIABLE" => "id",
	        "PRODUCT_PROPS_VARIABLE" => "prop",
	        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
	        "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
	        "PRODUCT_SUBSCRIPTION" => "N",
	        "SECTION_CODE" => "",
	        "SECTION_ELEMENT_CODE" => "",
	        "SECTION_ELEMENT_ID" => $GLOBALS["CATALOG_CURRENT_ELEMENT_ID"],
	        "SECTION_ID" => $GLOBALS["CATALOG_CURRENT_SECTION_ID"],
	        "SHOW_CLOSE_POPUP" => "N",
	        "SHOW_DISCOUNT_PERCENT" => "Y",
	        "SHOW_FROM_SECTION" => "N",
	        "SHOW_MAX_QUANTITY" => "Y",
	        "SHOW_OLD_PRICE" => "N",
	        "SHOW_PRICE_COUNT" => "1",
	        "SHOW_SLIDER" => "Y",
	        "SLIDER_INTERVAL" => "3000",
	        "SLIDER_PROGRESS" => "N",
	        "TEMPLATE_THEME" => "blue",
	        "USE_ENHANCED_ECOMMERCE" => "N",
	        "USE_PRICE_COUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == "Y" ? "Y" : "N",
	        "USE_PRODUCT_QUANTITY" => "Y"
	    )
	);
}