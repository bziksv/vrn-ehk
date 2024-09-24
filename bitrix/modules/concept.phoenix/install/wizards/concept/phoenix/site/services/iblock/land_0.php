<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

set_time_limit(0);

if(!CModule::IncludeModule("iblock"))
	return;

$lang = "ru";
WizardServices::IncludeServiceLang("lang_lands.php", $lang);

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/ru/land_0.xml";

$iblockCode = "concept_phoenix_site";
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

$arUF = array();
$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$iblockID."_SECTION"));
while($arRes = $rsData->Fetch())
    $arUF[$arRes["FIELD_NAME"]] = $arRes["ID"];


// edit form user options
$tabs = 'edit1--#--'.GetMessage("PHOENIX_SECTION_1").'--,--'.'ACTIVE--#--'.GetMessage("PHOENIX_SECTION_2").'--,--'.'UF_PHX_LINK_OBJ--#--'.GetMessage("PHOENIX_SECTION_3").'--,--'.'UF_PHX_MAIN_PAGE--#--'.GetMessage("PHOENIX_SECTION_4").'--,--'.'SORT--#--'.GetMessage("PHOENIX_SECTION_5").'--,--'.'NAME--#--'.GetMessage("PHOENIX_SECTION_6").'--,--'.'CODE--#--'.GetMessage("PHOENIX_SECTION_7").'--,--'.'UF_PHX_INMENU_POS--#--'.GetMessage("PHOENIX_SECTION_8").'--,--'.'edit1_csection1--#----'.GetMessage("PHOENIX_SECTION_9").'--,--'.'UF_PHX_BANNERS--#--'.GetMessage("PHOENIX_SECTION_10").'--,--'.'edit1_csection2--#----'.GetMessage("PHOENIX_SECTION_11").'--,--'.'UF_HIDE_PRODUCTS_VIEWED--#--'.GetMessage("PHOENIX_SECTION_12").'--,--'.'UF_SIDEMENU_HTML--#--'.GetMessage("PHOENIX_SECTION_13").'--;--'.'cedit1--#--'.GetMessage("PHOENIX_SECTION_14").'--,--'.'UF_PHX_CSS_USER--#--'.GetMessage("PHOENIX_SECTION_15").'--,--'.'UF_PHX_JS_USER--#--'.GetMessage("PHOENIX_SECTION_16").'--;--'.'edit5--#--SEO--,--'.'IPROPERTY_TEMPLATES_SECTION--#----'.GetMessage("PHOENIX_SECTION_17").'--,--'.'IPROPERTY_TEMPLATES_SECTION_META_TITLE--#--'.GetMessage("PHOENIX_SECTION_18").'--,--'.'IPROPERTY_TEMPLATES_SECTION_META_KEYWORDS--#--'.GetMessage("PHOENIX_SECTION_19").'--,--'.'IPROPERTY_TEMPLATES_SECTION_META_DESCRIPTION--#--'.GetMessage("PHOENIX_SECTION_20").'--,--'.'IPROPERTY_TEMPLATES_SECTION_PAGE_TITLE--#--'.GetMessage("PHOENIX_SECTION_21").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT--#----'.GetMessage("PHOENIX_SECTION_22").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_META_TITLE--#--'.GetMessage("PHOENIX_SECTION_18").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS--#--'.GetMessage("PHOENIX_SECTION_19").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION--#--'.GetMessage("PHOENIX_SECTION_20").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE--#--'.GetMessage("PHOENIX_SECTION_26").'--,--'.'IPROPERTY_TEMPLATES_SECTIONS_PICTURE--#----'.GetMessage("PHOENIX_SECTION_27").'--,--'.'IPROPERTY_TEMPLATES_SECTION_PICTURE_FILE_ALT--#--'.GetMessage("PHOENIX_SECTION_28").'--,--'.'IPROPERTY_TEMPLATES_SECTION_PICTURE_FILE_TITLE--#--'.GetMessage("PHOENIX_SECTION_29").'--,--'.'IPROPERTY_TEMPLATES_SECTION_PICTURE_FILE_NAME--#--'.GetMessage("PHOENIX_SECTION_30").'--,--'.'IPROPERTY_TEMPLATES_SECTIONS_DETAIL_PICTURE--#----'.GetMessage("PHOENIX_SECTION_31").'--,--'.'IPROPERTY_TEMPLATES_SECTION_DETAIL_PICTURE_FILE_ALT--#--'.GetMessage("PHOENIX_SECTION_28").'--,--'.'IPROPERTY_TEMPLATES_SECTION_DETAIL_PICTURE_FILE_TITLE--#--'.GetMessage("PHOENIX_SECTION_29").'--,--'.'IPROPERTY_TEMPLATES_SECTION_DETAIL_PICTURE_FILE_NAME--#--'.GetMessage("PHOENIX_SECTION_30").'--,--'.'IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE--#----'.GetMessage("PHOENIX_SECTION_35").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT--#--'.GetMessage("PHOENIX_SECTION_28").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE--#--'.GetMessage("PHOENIX_SECTION_29").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME--#--'.GetMessage("PHOENIX_SECTION_30").'--,--'.'IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE--#----'.GetMessage("PHOENIX_SECTION_39").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT--#--'.GetMessage("PHOENIX_SECTION_28").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE--#--'.GetMessage("PHOENIX_SECTION_29").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME--#--'.GetMessage("PHOENIX_SECTION_30").'--,--'.'IPROPERTY_TEMPLATES_MANAGEMENT--#----'.GetMessage("PHOENIX_SECTION_43").'--,--'.'IPROPERTY_CLEAR_VALUES--#--'.GetMessage("PHOENIX_SECTION_44").'--;--'.'';
     
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

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.'/_index.php', array("IBLOCK_LANDS" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.'/_index.php', array("IBLOCK_TYPE" => $iblockType));

?>
