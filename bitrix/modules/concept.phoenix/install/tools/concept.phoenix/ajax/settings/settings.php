<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');?>
<?  

$arResult = array();
$arResult["OK"] = "N";

if($_POST["send"] == "Y")
{
    $moduleID = "concept.phoenix";
	CModule::IncludeModule($moduleID);

    CPhoenix::saveOptions($site_id, $_POST);
    $arResult["OK"] = "Y";
}
    
$arResult = json_encode($arResult);
echo $arResult;