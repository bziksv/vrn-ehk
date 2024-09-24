<?

use Bitrix\Main\Loader,
	Bitrix\Main,
	Bitrix\Iblock,
	Bitrix\Catalog;

if(!CModule::IncludeModule("concept.phoenix"))
	die();


CUserOptions::DeleteOptionsByName("form", "form_element_".$IBLOCK_ID);

//$arShowTabs['sections'] = false;

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
		"CODE" => "SORT",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "NAME",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_STR_NAME"),
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMPL_DESC",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PREVIEW_PICTURE",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_STR_PREVIEW_PICTURE"),
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMPL_PHONE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMPL_EMAIL",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMPL_FORMS",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMPL_BTN_NAME",
	),
	
	array(
		"TYPE" => "STRING",
		"ID" => "2",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_LABEL2"),
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_LABELS",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_QUOTE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TEXT",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_NAME",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FILE_NAME",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FILES",
	),
	array(
		"TYPE" => "STRING",
		"ID" => "1",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_LABEL1"),
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PREVIEW_TEXT",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_STR_PREVIEW_TEXT"),
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "DETAIL_PICTURE",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_STR_DETAIL_PICTURE"),
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "DETAIL_TEXT",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_STR_DETAIL_TEXT"),
	),
);

if(\Bitrix\Main\Config\Option::Get("iblock", "show_xml_id") == "Y")
{
	$arr["edit2"] = array(

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