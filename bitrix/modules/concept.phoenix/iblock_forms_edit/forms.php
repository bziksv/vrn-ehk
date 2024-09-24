<?

use Bitrix\Main\Loader,
	Bitrix\Main,
	Bitrix\Iblock,
	Bitrix\Catalog;

if(!CModule::IncludeModule("concept.phoenix"))
	die();


CUserOptions::DeleteOptionsByName("form", "form_element_".$IBLOCK_ID);


/* tabs*/
$arOurTabs = array(
	array(
		"DIV" => "edit1",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_BASE_SETTINGS"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_BASE_SETTINGS"),
	),
	array(
		"DIV" => "edit2",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_DESIGN"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_DESIGN"),
	),
	array(
		"DIV" => "edit3",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_AUTO"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_AUTO"),
	),
	array(
		"DIV" => "edit4",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_WEB"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_WEB"),
	),
	array(
		"DIV" => "edit5",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_PROCESS"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_PROCESS"),
	),
	array(
		"DIV" => "edit_else",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_OTHER"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_OTHER"),
	)
);

$aTabs = $arOurTabs;


if($arShowTabs['sections'])
{
	$aTabs[] = array(
		"DIV" => "sections",
		"TAB" => $arIBlock["SECTIONS_NAME"],
		"ICON" => "iblock_element_section",
		"TITLE" => htmlspecialcharsEx($arIBlock["SECTIONS_NAME"]),
		"required" => true,
	);
}
	
if ($arShowTabs['catalog'])
{
	$aTabs[] = array(
		"DIV" => "catalog",
		"TAB" => GetMessage("IBLOCK_TCATALOG"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IBLOCK_TCATALOG"),
		"required" => true,
	);
}
	
if($arShowTabs['sku'])
{
	$aTabs[] = array(
		"DIV" => "sku",
		"TAB" => GetMessage("IBLOCK_EL_TAB_OFFERS"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IBLOCK_EL_TAB_OFFERS_TITLE"),
		"required" => true,
	);
}
	
if ($arShowTabs['product_set'])
{
	$aTabs[] = array(
		"DIV" => "product_set",
		"TAB" => GetMessage("IBLOCK_EL_TAB_PRODUCT_SET"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IBLOCK_EL_TAB_PRODUCT_SET_TITLE"),
		"required" => true,
	);
}
	
if ($arShowTabs['product_group'])
{
	$aTabs[] = array(
		"DIV" => "product_group",
		"TAB" => GetMessage("IBLOCK_EL_TAB_PRODUCT_GROUP"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IBLOCK_EL_TAB_PRODUCT_GROUP_TITLE"),
		"required" => true,
	);
}
	
if($arShowTabs['workflow'])
{
	$aTabs[] = array(
		"DIV" => "workflow",
		"TAB" => GetMessage("IBLOCK_EL_TAB_WF"),
		"ICON" => "iblock_element_wf",
		"TITLE" => GetMessage("IBLOCK_EL_TAB_WF_TITLE")
	);
}
	
if($arShowTabs['bizproc'])
{
	$aTabs[] = array(
		"DIV" => "bizproc",
		"TAB" => GetMessage("IBEL_E_TAB_BIZPROC"),
		"ICON" => "iblock_element_bizproc",
		"TITLE" => GetMessage("IBEL_E_TAB_BIZPROC")
	);
}
	
if($arShowTabs['edit_rights'])
{
	$aTabs[] = array(
		"DIV" => "edit_rights",
		"TAB" => GetMessage("IBEL_E_TAB_RIGHTS"),
		"ICON" => "iblock_element_rights",
		"TITLE" => GetMessage("IBEL_E_TAB_RIGHTS_TITLE")
	);
}
	

$aTabs[] = array(
	"DIV" => "userprops",
	"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_USER"),
	"ICON" => "iblock_element",
);



$tabControl->tabs = "";

$tabControl->tabs = $aTabs;

/*struct*/

$arr = array();

$arr["edit1"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "SECTIONS",
		"HIDDEN" => "Y"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "ACTIVE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "NAME",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_STR_NAME"),
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_ADMIN",
	),
	array(
		"TYPE" => "STRING",
		"ID" => "label1",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_LABEL1"),
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_LIST_TITLE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_LIST",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_RADIOCHECK",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_INPUTS",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_INPUTS_REQ",
	),
	array(
		"TYPE" => "STRING",
		"ID" => "label2",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_LABEL2"),
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_PROP_INPUTS",
	),
);

$arr["edit2"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TITLE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_SUBTITLE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_BUTTON",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_BUTTON_BG_COLOR",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_THANKS",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_COMMENT",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PREVIEW_TEXT",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_STR_PREVIEW_TEXT"),
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TEXT_TITLE_COLOR",
	),
	

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "DETAIL_PICTURE",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_STR_DETAIL_PICTURE"),
	),
	
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_COVER",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_POSITION_IMAGE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PREVIEW_PICTURE",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_STR_PREVIEW_PICTURE"),
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_BGC",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TEXT_COLOR",
	),
);

$arr["edit3"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TO_LINK",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TO_LINK_NEWTAB",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_THEME",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TEXT",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_FILES",
	),
);

$arr["edit4"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_YANDEX_GOAL",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GOOGLE_GOAL_CATEGORY",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GOOGLE_GOAL_ACTION",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GTM_EVENT",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GTM_GOAL_CATEGORY",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GTM_GOAL_ACTION",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_JS_AFTER_SEND",
	),
	
);

$arr["edit5"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMAIL_TO",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_DUBLICATE",
	),
	
);

if(\Bitrix\Main\Config\Option::Get("iblock", "show_xml_id") == "Y")
{
	$arr["edit_else"] = array(

		array(
			"TYPE" => "PROPERTY",
			"CODE" => "XML_ID"
		),

	);
}



	


	$nameFormat = CSite::GetNameFormat();

	$tabControl->BeginPrologContent();
	CJSCore::Init(array('date'));

	//TODO: this code only for old html editor. Need remove after final cut old editor
	if (
		$bFileman
		&& (string)Main\Config\Option::get('iblock', 'use_htmledit') == 'Y'
		&& (string)Main\Config\Option::get('fileman', 'use_editor_3', 'Y') != 'Y'
	)
	{
		echo '<div style="display:none">';
		CFileMan::AddHTMLEditorFrame("SOME_TEXT", "", "SOME_TEXT_TYPE", "text",
			array('height' => 450, 'width' => '100%'),
			"N", 0, "", "", $arIBlock["LID"]
		);
		echo '</div>';
	}

	require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/concept.phoenix/iblock_forms_edit/common/tab_control.php');
?>

<?
	$tabControl->EndPrologContent();

	$tabControl->BeginEpilogContent();

echo bitrix_sessid_post();
echo GetFilterHiddens("find_");?>

<?require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/concept.phoenix/iblock_forms_edit/common/inputs.php');?>


<?
$tabControl->EndEpilogContent();

$customTabber->SetErrorState($bVarsFromForm);

$arEditLinkParams = array(
	"find_section_section" => $find_section_section
);
if ($bAutocomplete)
{
	$arEditLinkParams['lookup'] = $strLookup;
}

$tabControl->Begin(array(
	"FORM_ACTION" => "/bitrix/admin/".CIBlock::GetAdminElementEditLink($IBLOCK_ID, null, $arEditLinkParams)
));





$arPropFields = array();

foreach($PROP as $key => $prop_fields)
{
	if(empty($prop_fields["CODE"]))
		$PROP[$key]["CODE"] = $prop_fields["ID"];
}

foreach($PROP as $prop_fields)
{
	$arPropFields[$prop_fields["CODE"]] = $prop_fields["ID"];
}

CPhoenix::showTabContent($arOurTabs, $arr);



require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/concept.phoenix/iblock_forms_edit/common/common_tabs.php');





$arUser = $PROP;


$arrSkipFields = array(
	"PROPERTY_USER_FIELD123",
);


$arUser = CPhoenix::deleteTabContentUser($arOurTabs, $arr, $arUser, $arPropFields, $arrSkipFields);

CPhoenix::showTabContentUser($arUser);





require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/concept.phoenix/iblock_forms_edit/common/common_footer.php');

	//////////////////////////
	//END of the custom form
	//////////////////////////