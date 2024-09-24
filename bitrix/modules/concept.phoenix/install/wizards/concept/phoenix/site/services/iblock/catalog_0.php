<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

set_time_limit(0);

use \Bitrix\Main\Config\Option as Option;

Option::set("catalog", "enable_processing_deprecated_events", "Y");


if(!CModule::IncludeModule("iblock"))
	return;

if(!CModule::IncludeModule("catalog"))
    return;

$lang = "ru";
WizardServices::IncludeServiceLang("lang_catalog.php", $lang);

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/ru/catalog_0.xml";

$iblockCode = "concept_phoenix_catalog";
$iblockCode1 = $iblockCode."_".WIZARD_SITE_ID;
$iblockType = "concept_phoenix"."_".WIZARD_SITE_ID;

$iblockID = false; 

$rsIBlock = CIBlock::GetList(array(), array("CODE" => $iblockCode1, "TYPE" => $iblockType));

if($arIBlock = $rsIBlock->Fetch())
{

    $rsIBlock1 = CIBlock::GetList(array(), array("CODE" => "concept_phoenix_catalog_offers_".WIZARD_SITE_ID, "TYPE" => "concept_phoenix_".WIZARD_SITE_ID));
    if($arIBlock1 = $rsIBlock1->Fetch())
        CCatalog::Delete($arIBlock1["ID"]);

    $rsIBlock1 = CIBlock::GetList(array(), array("CODE" => "concept_phoenix_catalog_".WIZARD_SITE_ID, "TYPE" => "concept_phoenix_".WIZARD_SITE_ID));
    if($arIBlock1 = $rsIBlock1->Fetch())
        CCatalog::Delete($arIBlock1["ID"]);
                
	CIBlock::Delete($arIBlock["ID"]); 
}


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
$tabs = 'edit1--#--'.GetMessage("PHOENIX_SECTION_1").'--,--'.'ACTIVE--#--'.GetMessage("PHOENIX_SECTION_2").'--,--'.'UF_PHX_MAIN_MENU--#--'.GetMessage("PHOENIX_SECTION_3").'--,--'.'UF_USE_FILTER--#--'.GetMessage("PHOENIX_SECTION_4").'--,--'.'UF_FILTER_HIDE_PRICE--#--'.GetMessage("PHOENIX_SECTION_5").'--,--'.'UF_HIDE_SUBSECTIONS--#--'.GetMessage("PHOENIX_SECTION_6").'--,--'.'NAME--#--'.GetMessage("PHOENIX_SECTION_7").'--,--'.'CODE--#--'.GetMessage("PHOENIX_SECTION_8").'--,--'.'XML_ID--#--'.GetMessage("PHOENIX_SECTION_9").'--,--'.'IBLOCK_SECTION_ID--#--'.GetMessage("PHOENIX_SECTION_10").'--,--'.'UF_PHX_CTLG_TMPL--#--'.GetMessage("PHOENIX_SECTION_11").'--,--'.'UF_PHX_CTLG_T_ID--#--'.GetMessage("PHOENIX_SECTION_12").'--,--'.'SORT--#--'.GetMessage("PHOENIX_SECTION_13").'--,--'.'UF_PHX_CTLG_ADV--#--'.GetMessage("PHOENIX_SECTION_14").'--,--'.'UF_VIEW_SUBSECTIONS--#--'.GetMessage("PHOENIX_SECTION_15").'--,--'.'UF_ANIMATE_HEAD--#--'.GetMessage("PHOENIX_SECTION_16").'--,--'.'UF_CHARS_VIEW--#--'.GetMessage("PHOENIX_SECTION_17").'--,--'.'UF_HIDE_STORE_BLOCK--#--'.GetMessage("PHOENIX_SECTION_18").'--;--'.'cedit2--#--'.GetMessage("PHOENIX_SECTION_19").'--,--'.'UF_ZOOM_ON--#--'.GetMessage("PHOENIX_SECTION_20").'--,--'.'DETAIL_PICTURE--#--'.GetMessage("PHOENIX_SECTION_21").'--,--'.'UF_PHX_CTLG_PRTXT--#--'.GetMessage("PHOENIX_SECTION_22").'--,--'.'cedit2_csection1--#----'.GetMessage("PHOENIX_SECTION_23").'--,--'.'UF_PHX_MAIN_CTLG--#--'.GetMessage("PHOENIX_SECTION_24").'--,--'.'PICTURE--#--'.GetMessage("PHOENIX_SECTION_25").'--,--'.'UF_PHX_CTLG_SIZE--#--'.GetMessage("PHOENIX_SECTION_26").'--,--'.'UF_PHX_CTLG_BTN--#--'.GetMessage("PHOENIX_SECTION_27").'--,--'.'cedit2_csection2--#----'.GetMessage("PHOENIX_SECTION_28").'--,--'.'UF_PHX_PICT_IN_HEAD--#--'.GetMessage("PHOENIX_SECTION_29").'--,--'.'UF_PHX_MENU_PICT--#--'.GetMessage("PHOENIX_SECTION_30").'--,--'.'cedit2_csection3--#----'.GetMessage("PHOENIX_SECTION_31").'--,--'.'UF_DESC_FOR_PRICE--#--'.GetMessage("PHOENIX_SECTION_32").'--,--'.'UF_TIT_FOR_QUANTITY--#--'.GetMessage("PHOENIX_SECTION_33").'--,--'.'UF_COMM_FOR_QUANTITY--#--'.GetMessage("PHOENIX_SECTION_34").'--,--'.'UF_PREVIEW_TEXT_POS--#--'.GetMessage("PHOENIX_SECTION_35").'--,--'.'cedit2_csection4--#----'.GetMessage("PHOENIX_SECTION_36").'--,--'.'UF_SIDEMENU_HTML--#--'.GetMessage("PHOENIX_SECTION_37").'--;--'.'edit5--#--'.GetMessage("PHOENIX_SECTION_38").'--,--'.'IPROPERTY_TEMPLATES_SECTION--#----'.GetMessage("PHOENIX_SECTION_39").'--,--'.'IPROPERTY_TEMPLATES_SECTION_META_TITLE--#--'.GetMessage("PHOENIX_SECTION_40").'--,--'.'IPROPERTY_TEMPLATES_SECTION_META_KEYWORDS--#--'.GetMessage("PHOENIX_SECTION_41").'--,--'.'IPROPERTY_TEMPLATES_SECTION_META_DESCRIPTION--#--'.GetMessage("PHOENIX_SECTION_42").'--,--'.'IPROPERTY_TEMPLATES_SECTION_PAGE_TITLE--#--'.GetMessage("PHOENIX_SECTION_43").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT--#----'.GetMessage("PHOENIX_SECTION_44").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_META_TITLE--#--'.GetMessage("PHOENIX_SECTION_40").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS--#--'.GetMessage("PHOENIX_SECTION_41").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION--#--'.GetMessage("PHOENIX_SECTION_42").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE--#--'.GetMessage("PHOENIX_SECTION_48").'--,--'.'IPROPERTY_TEMPLATES_SECTIONS_PICTURE--#----'.GetMessage("PHOENIX_SECTION_49").'--,--'.'IPROPERTY_TEMPLATES_SECTION_PICTURE_FILE_ALT--#--'.GetMessage("PHOENIX_SECTION_50").'--,--'.'IPROPERTY_TEMPLATES_SECTION_PICTURE_FILE_TITLE--#--'.GetMessage("PHOENIX_SECTION_51").'--,--'.'IPROPERTY_TEMPLATES_SECTION_PICTURE_FILE_NAME--#--'.GetMessage("PHOENIX_SECTION_52").'--,--'.'IPROPERTY_TEMPLATES_SECTIONS_DETAIL_PICTURE--#----'.GetMessage("PHOENIX_SECTION_53").'--,--'.'IPROPERTY_TEMPLATES_SECTION_DETAIL_PICTURE_FILE_ALT--#--'.GetMessage("PHOENIX_SECTION_50").'--,--'.'IPROPERTY_TEMPLATES_SECTION_DETAIL_PICTURE_FILE_TITLE--#--'.GetMessage("PHOENIX_SECTION_51").'--,--'.'IPROPERTY_TEMPLATES_SECTION_DETAIL_PICTURE_FILE_NAME--#--'.GetMessage("PHOENIX_SECTION_52").'--,--'.'IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE--#----'.GetMessage("PHOENIX_SECTION_57").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT--#--'.GetMessage("PHOENIX_SECTION_50").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE--#--'.GetMessage("PHOENIX_SECTION_51").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME--#--'.GetMessage("PHOENIX_SECTION_52").'--,--'.'IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE--#----'.GetMessage("PHOENIX_SECTION_61").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT--#--'.GetMessage("PHOENIX_SECTION_50").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE--#--'.GetMessage("PHOENIX_SECTION_51").'--,--'.'IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME--#--'.GetMessage("PHOENIX_SECTION_52").'--,--'.'IPROPERTY_TEMPLATES_MANAGEMENT--#----'.GetMessage("PHOENIX_SECTION_65").'--,--'.'IPROPERTY_CLEAR_VALUES--#--'.GetMessage("PHOENIX_SECTION_66").'--;--'.'cedit1--#--SEO--,--'.'UF_PHX_CTLG_H1--#--H1--,--'.'UF_PHX_CTLG_TITLE--#--Title--,--'.'UF_PHX_CTLG_KWORD--#--Keywords--,--'.'UF_PHX_CTLG_DSCR--#--Description--,--'.'DESCRIPTION--#--'.GetMessage("PHOENIX_SECTION_67").'--,--'.'UF_PHX_CTLG_TXT_P--#--'.GetMessage("PHOENIX_SECTION_68").'--,--'.'UF_PHX_CTLG_TOP_T--#--'.GetMessage("PHOENIX_SECTION_69").'--;--'.'cedit3--#--'.GetMessage("PHOENIX_SECTION_70").'--,--'.'UF_PHX_BNRS_VIEW--#--'.GetMessage("PHOENIX_SECTION_71").'--,--'.'UF_PHX_CTLG_BNNRS--#--'.GetMessage("PHOENIX_SECTION_72").'--,--'.'cedit3_csection1--#----'.GetMessage("PHOENIX_SECTION_73").'--,--'.'UF_EMPL_BANNER_TYPE--#--'.GetMessage("PHOENIX_SECTION_74").'--,--'.'UF_EMPL_BANNER--#--'.GetMessage("PHOENIX_SECTION_75").'--;--'.'cedit4--#--'.GetMessage("PHOENIX_SECTION_76").'--,--'.'UF_SIMILAR_CATEGORY--#--'.GetMessage("PHOENIX_SECTION_77").'--;--'.'edit4--#--'.GetMessage("PHOENIX_SECTION_78").'--,--'.'SECTION_PROPERTY--#--'.GetMessage("PHOENIX_SECTION_79").'--;--'.'';
     
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

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.'/catalog/index.php', array("IBLOCK_CATALOG" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.'/catalog/index.php', array("IBLOCK_TYPE" => $iblockType));

?>
