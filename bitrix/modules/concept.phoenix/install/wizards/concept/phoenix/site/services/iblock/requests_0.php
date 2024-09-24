<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

set_time_limit(0);

if(!CModule::IncludeModule("iblock"))
	return;

$lang = "ru";
WizardServices::IncludeServiceLang("lang_requests.php", $lang);

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/ru/requests_0.xml";

$iblockCode = "concept_phoenix_requests";
$iblockCode1 = $iblockCode."_".WIZARD_SITE_ID;
$iblockType = "concept_phoenix"."_".WIZARD_SITE_ID;


$iblockID = false; 



$rsIBlock = CIBlock::GetList(array(), array("CODE" => $iblockCode1, "TYPE" => $iblockType));

if($arIBlock = $rsIBlock->Fetch())
	CIBlock::Delete($arIBlock["ID"]); 



$permissions = Array(
	"1" => "X",
	"2" => "R"
);

$dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "content_editor"));

if($arGroup = $dbGroup -> Fetch())
	$permissions[$arGroup["ID"]] = 'W';


$iblockID = WizardServices::ImportIBlockFromXML(
	$iblockXMLFile,
	$iblockCode,
	$iblockType,
	WIZARD_SITE_ID,
	$permissions
);

if($iblockID < 1)
	return;


//IBlock fields
$iblock = new CIBlock;

$arFields = Array(
	"ACTIVE" => "Y",
	"FIELDS" => array ( 'IBLOCK_SECTION' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'ACTIVE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'Y', ), 'ACTIVE_FROM' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '=today', ), 'ACTIVE_TO' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'SORT' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'NAME' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => '', ), 'PREVIEW_PICTURE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => array ( 'FROM_DETAIL' => 'N', 'SCALE' => 'N', 'WIDTH' => '', 'HEIGHT' => '', 'IGNORE_ERRORS' => 'N', ), ), 'PREVIEW_TEXT_TYPE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'html', ), 'PREVIEW_TEXT' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'DETAIL_PICTURE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => array ( 'SCALE' => 'N', 'WIDTH' => '', 'HEIGHT' => '', 'IGNORE_ERRORS' => 'N', ), ), 'DETAIL_TEXT_TYPE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'text', ), 'DETAIL_TEXT' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'XML_ID' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'CODE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'TAGS' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), ), 
	"CODE" => $iblockCode1, 
	"XML_ID" => $iblockCode1,
	"NAME" => $iblock->GetArrayByID($iblockID, "NAME"),
	"EDIT_FILE_BEFORE" => "/bitrix/modules/concept.phoenix/iblock_forms_edit/requests_before_save.php",
	"EDIT_FILE_AFTER" => "/bitrix/modules/concept.phoenix/iblock_forms_edit/requests.php"
);

$iblock->Update($iblockID, $arFields);




// iblock user fields

$arProperty = array();
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblockID));
while($arProp = $dbProperty->Fetch())
	$arProperty[$arProp["CODE"]] = $arProp["ID"];


$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$iblockID."_SECTION"));
while($arRes = $rsData->Fetch())
    $arUF[$arRes["FIELD_NAME"]] = $arRes["ID"];


// edit form user options
$tabs = 'edit1--#--'.GetMessage("PHOENIX_SECTION_1").'--,--'.'ACTIVE--#--'.GetMessage("PHOENIX_SECTION_2").'--,--'.'NAME--#--'.GetMessage("PHOENIX_SECTION_3").'--;--'.'';
     
CUserOptions::SetOption("form", "form_section_".$iblockID, 
    array(
        "tabs" => $tabs
    ),
    true
);



CUserOptions::SetOption("list", "tbl_iblock_list_".md5($iblockType.".".$iblockID), array ( 'columns' => 'NAME,SORT,ACTIVE,ID', 'by' => 'sort', 'order' => 'asc', 'page_size' => '20', ),
    true
);

foreach($arProperty as $key=>$propID)
{        
    if(strlen(GetMessage("PHOENIX_ELEMENT_HINT_$key")) > 0)
    {
        $arFields = Array("HINT"=>GetMessage("PHOENIX_ELEMENT_HINT_$key"));
        $ibp = new CIBlockProperty;
        $ibp->Update($propID, $arFields);
    }
}

foreach($arUF as $key=>$UFid)
{
    $oUserTypeEntity = new CUserTypeEntity();;
    $oUserTypeEntity->Update($UFid, 
        array(
            'EDIT_FORM_LABEL' => array('ru' => GetMessage("PHOENIX_SECTION_NAME_$key")),
            'HELP_MESSAGE'=> array('ru' => GetMessage("PHOENIX_SECTION_HELP_$key"))
        ) 
    );
}


?>
