<?
$site_id = trim($_REQUEST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
CModule::IncludeModule("concept.phoenix");

global $PHOENIX_TEMPLATE_ARRAY;
CPhoenix::phoenixOptionsValues($arParams["CURRENT_SITE"], 
        array(
        	"start",
            "search",
		    "catalog",
		    "catalog_fields",
		    "shop",
		    "region"
        ));



$APPLICATION->IncludeComponent(
	"concept:phoenix.search", 
	"ajax", 
	array(),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>