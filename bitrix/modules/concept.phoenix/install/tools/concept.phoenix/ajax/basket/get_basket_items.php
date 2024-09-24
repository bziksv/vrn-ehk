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
	CPhoenix::phoenixOptionsValues($site_id, array("shop", "design"));
	CPhoenix::setMainMess();


	$APPLICATION->IncludeComponent("bitrix:sale.basket.basket",
	    "basket.items",
	    Array(
	    	"AJAX_CONTENT" => $_POST["ajax_content"],
	    	"ACTION_VARIABLE" => "basketAction",    
	        "ADDITIONAL_PICT_PROP_15" => "-",   
	        "ADDITIONAL_PICT_PROP_8" => "-",    
	        "AUTO_CALCULATION" => "Y",  
	        "BASKET_IMAGES_SCALING" => "adaptive",  
	        "COLUMNS_LIST_EXT" => array(    
	            0 => "PREVIEW_PICTURE",
	            1 => "DISCOUNT",
	            2 => "DELETE",
	            3 => "DELAY",
	            4 => "TYPE",
	            5 => "SUM",
	        ),
	        "COLUMNS_LIST_MOBILE" => array(
	            0 => "PREVIEW_PICTURE",
	            1 => "DISCOUNT",
	            2 => "DELETE",
	            3 => "DELAY",
	            4 => "TYPE",
	            5 => "SUM",
	        ),
	        "COMPATIBLE_MODE" => "Y",
	        "CORRECT_RATIO" => "Y",
	        "DEFERRED_REFRESH" => "N",
	        "DISCOUNT_PERCENT_POSITION" => "bottom-right",
	        "DISPLAY_MODE" => "compact",
	        "EMPTY_BASKET_HINT_PATH" => "/",
	        "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
	        "GIFTS_CONVERT_CURRENCY" => "N",
	        "GIFTS_HIDE_BLOCK_TITLE" => "N",
	        "GIFTS_HIDE_NOT_AVAILABLE" => "N",
	        "GIFTS_MESS_BTN_BUY" => "Выбрать",
	        "GIFTS_MESS_BTN_DETAIL" => "Подробнее",
	        "GIFTS_PAGE_ELEMENT_COUNT" => "4",
	        "GIFTS_PLACE" => "BOTTOM",
	        "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
	        "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
	        "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
	        "GIFTS_SHOW_OLD_PRICE" => "N",
	        "GIFTS_TEXT_LABEL_GIFT" => "Подарок",
	        "HIDE_COUPON" => "N",
	        "LABEL_PROP" => "",
	        "OFFERS_PROPS" => "",
	        "PATH_TO_ORDER" => SITE_DIR."order/",
	        "PRICE_DISPLAY_MODE" => "Y",
	        "PRICE_VAT_SHOW_VALUE" => "Y",
	        "PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
	        "QUANTITY_FLOAT" => "N",
	        "SET_TITLE" => "N",
	        "SHOW_DISCOUNT_PERCENT" => "Y",
	        "SHOW_FILTER" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_FILTER"]["VALUE"]["ACTIVE"],
	        "SHOW_RESTORE" => "Y",
	        "TEMPLATE_THEME" => "blue",
	        "TOTAL_BLOCK_DISPLAY" => array(
	            0 => "top",
	        ),
	        "USE_DYNAMIC_SCROLL" => "Y",
	        "USE_ENHANCED_ECOMMERCE" => "N",
	        "USE_GIFTS" => "N",
	        "USE_PREPAYMENT" => "N",
	        "USE_PRICE_ANIMATION" => "Y",
	    ),
	    false
	);


	$arJsonBasket["OK"] = "Y";
}




//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");