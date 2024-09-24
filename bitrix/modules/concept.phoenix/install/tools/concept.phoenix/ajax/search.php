<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
CModule::IncludeModule("concept.phoenix");

global $PHOENIX_TEMPLATE_ARRAY;
CPhoenix::phoenixOptionsValues($site_id, array(
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