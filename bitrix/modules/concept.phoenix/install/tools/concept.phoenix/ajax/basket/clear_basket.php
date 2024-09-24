<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
header('Content-type: application/json');

$arRes["OK"] = "N";

if(Loader::includeModule("sale"))
{
	$inBasket = "N";
	$fUserID = CSaleBasket::GetBasketUserID(True);
    $fUserID = IntVal($fUserID);
    $error = '';

    $res = CSaleBasket::GetList(array(), array(
        'FUSER_ID' => $fUserID,
        'LID' => $_POST['site_id'],
        'ORDER_ID' => 'null',
        'DELAY' => 'N',
        'CAN_BUY' => 'Y'));
    
    while ($row = $res->fetch())
    {
        CSaleBasket::Delete($row['ID']);
    }
    
    $arRes["OK"] = "Y";
}


echo json_encode($arRes);
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");