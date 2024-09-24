<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();
    
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-type: application/json');
use Bitrix\Main\Loader;

$arRes["OK"] = "N";

if(Loader::includeModule("sale"))
{
    if(CSaleBasket::Delete($_POST['id']))
        $arRes["OK"] = "Y";
}

echo json_encode($arRes);
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");