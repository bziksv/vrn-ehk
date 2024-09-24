<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
    
if(!CModule::IncludeModule('iblock'))
    return false;



if(isset($_POST["arParams"]) && !empty($_POST["arParams"]))
{
	$moduleID = "concept.phoenix";
	CModule::IncludeModule($moduleID);
	
	CPhoenix::phoenixOptionsValues($site_id, array("design"));

    $APPLICATION->IncludeComponent(
        "concept:phoenix.news-list",
        "empl",
        Array(
            "COMPOSITE_FRAME_MODE" => "N",
            "ELEMENTS_ID" => $_POST["arParams"]["element_id"],
            "VIEW"=>"popup",
            "ALL_ID"=>$_POST["arParams"]["all_id"]
        )
    );
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");