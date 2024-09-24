<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-type: application/json');

$arItems = array();
if(CModule::IncludeModule("concept.phoenix"))
{
	$iblockID = $_POST['iblockID'];
	$arItems = CPhoenix::getElementsBasketHTML($iblockID, $site_id);

}
echo json_encode($arItems);