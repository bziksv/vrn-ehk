<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

set_time_limit(0);

if(!CModule::IncludeModule("iblock"))
	return;

$lang = "ru";
WizardServices::IncludeServiceLang("lang_menu.php", $lang);

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/ru/menu_0.xml";

$iblockCode = "concept_phoenix_menu";
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
{
	$permissions[$arGroup["ID"]] = 'W';
};

$iblockID = WizardServices::ImportIBlockFromXML(
	$iblockXMLFile,
	$iblockCode,
	$iblockType,
	WIZARD_SITE_ID,
	$permissions
);

if ($iblockID < 1)
	return;



// iblock user fields


$arProperty = array();
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblockID));
while($arProp = $dbProperty->Fetch())
	$arProperty[$arProp["CODE"]] = $arProp["ID"];

$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$iblockID."_SECTION"));
while($arRes = $rsData->Fetch())
    $arUF[$arRes["FIELD_NAME"]] = $arRes["ID"];


// edit form user options
$tabs = 'edit1--#--'.GetMessage("PHOENIX_SECTION_1").'--,--'.'NAME--#--'.GetMessage("PHOENIX_SECTION_2").'--,--'.'UF_MENU_VIEW--#--'.GetMessage("PHOENIX_SECTION_3").'--,--'.'SORT--#--'.GetMessage("PHOENIX_SECTION_4").'--,--'.'UF_PHX_MENU_REGION--#--'.GetMessage("PHOENIX_SECTION_5").'--,--'.'UF_PHX_MENU_TYPE--#--'.GetMessage("PHOENIX_SECTION_6").'--,--'.'UF_PHX_LAND--#--'.GetMessage("PHOENIX_SECTION_7").'--,--'.'UF_PHX_M_FORM--#--'.GetMessage("PHOENIX_SECTION_8").'--,--'.'UF_PHX_M_MODAL--#--'.GetMessage("PHOENIX_SECTION_9").'--,--'.'UF_PHX_M_QUIZ--#--'.GetMessage("PHOENIX_SECTION_10").'--,--'.'UF_PHX_MENU_LINK--#--'.GetMessage("PHOENIX_SECTION_11").'--,--'.'UF_PHX_M_BLANK--#--'.GetMessage("PHOENIX_SECTION_12").'--,--'.'edit1_csection3--#----'.GetMessage("PHOENIX_SECTION_13").'--,--'.'UF_ACTION_OTHER_BLOCK--#--'.GetMessage("PHOENIX_SECTION_14").'--,--'.'UF_OTHER_IBLOCK_ID--#--'.GetMessage("PHOENIX_SECTION_15").'--,--'.'edit1_csection1--#----'.GetMessage("PHOENIX_SECTION_16").'--,--'.'UF_MENU_LVLS--#--'.GetMessage("PHOENIX_SECTION_17").'--,--'.'UF_PHX_MENU_COLOR--#--'.GetMessage("PHOENIX_SECTION_18").'--,--'.'UF_PHX_MENU_ICON--#--'.GetMessage("PHOENIX_SECTION_19").'--,--'.'UF_PHX_MENU_IC_US--#--'.GetMessage("PHOENIX_SECTION_20").'--,--'.'UF_ACCENTED--#--'.GetMessage("PHOENIX_SECTION_21").'--,--'.'UF_PHX_MENU_COL--#--'.GetMessage("PHOENIX_SECTION_22").'--,--'.'edit1_csection2--#----'.GetMessage("PHOENIX_SECTION_23").'--,--'.'UF_MENU_PICT--#--'.GetMessage("PHOENIX_SECTION_24").'--,--'.'UF_TOP_TEXT--#--'.GetMessage("PHOENIX_SECTION_25").'--,--'.'UF_EXTRA_MARGIN--#--'.GetMessage("PHOENIX_SECTION_26").'--,--'.'UF_BOTTOM_TEXT--#--'.GetMessage("PHOENIX_SECTION_27").'--,--'.'UF_BANNERS--#--'.GetMessage("PHOENIX_SECTION_28").'--;--'.'';
     
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