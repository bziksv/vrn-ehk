<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$moduleID = "concept.phoenix";
CModule::IncludeModule($moduleID);
    
if(!CPhoenix::isAdmin())
    die();

CModule::IncludeModule("iblock");
$arResult["OK"] = "N";

if($_POST["send"] == "Y")
{
	$iCode = 'concept_phoenix_site_'.$site_id;

	$arFilter = Array('IBLOCK_CODE'=>$iCode, 'GLOBAL_ACTIVE'=>'', "ACTIVE"=>"");
	$arSelect = Array("ID");
	$db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, $arSelect);

	while($ar_result = $db_list->GetNext())
	{
	    $bs = new CIBlockSection;
	    
	    $arFields = Array();
	    
	    $arFields["ACTIVE"] = trim($_POST["page_active".$ar_result["ID"]]);
	    $arFields["SORT"] = trim($_POST["sort_".$ar_result["ID"]]);
	    
	    
	    
	    $bs->Update($ar_result["ID"], $arFields);
	}


	$arResult["OK"] = "Y";
}

$arResult = json_encode($arResult);
echo $arResult;?>