<?

use Bitrix\Main\Loader,
	Bitrix\Main,
	Bitrix\Iblock,
	Bitrix\Catalog;

if(!CModule::IncludeModule("concept.phoenix"))
	die();


//CUserOptions::DeleteOptionsByName("form", "form_element_".$IBLOCK_ID);

//$arShowTabs['sections'] = false;

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
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_DETAIL_PAGE"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_DETAIL_PAGE"),
	),
	array(
		"DIV" => "edit3",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_DETAIL_PAGE_VIDEO"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_DETAIL_PAGE_VIDEO"),
	),
	array(
		"DIV" => "edit4",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_DETAIL_PAGE_GALLERY"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_DETAIL_PAGE_GALLERY"),
	),
	array(
		"DIV" => "edit5",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_DETAIL_PAGE_SERTIFICATE"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_DETAIL_PAGE_SERTIFICATE"),
	),
	
	
	array(
		"DIV" => "edit_other",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_OTHER"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_OTHER"),
	)
);

$aTabs = $arOurTabs;

$arShowTabs['sections'] = false;



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
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "SORT",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "CODE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FILTER_SHOW",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FILTER_HIDE_PRICE",
	),
	
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HEADER_PICTURE",
	),
	
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PREVIEW_PICTURE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "DETAIL_PICTURE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PREVIEW_TEXT",
	),
	array(
		"TYPE" => "STRING",
		"ID" => "sidemenu",
		"NAME" => GetMessage("IB_FORM_SETTING_CPHX_SIDE_MENU_TITLE"),
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SIDE_MENU_HTML",
	),

);

$arr["edit2"] = array(
	
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "DETAIL_TEXT",
	),
	
);

$arr["edit3"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_TITLE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_LINK",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_DESC",
	),
);

$arr["edit4"] = array(
	
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_TITLE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_PHOTOS",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_WATERMARK",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_BORDER",
	),

);

$arr["edit5"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CERTIFICATE_TITLE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CERTIFICATE_PHOTOS",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CERTIFICATE_WATERMARK",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CERTIFICATE_BORDER",
	),
);

if(\Bitrix\Main\Config\Option::Get("iblock", "show_xml_id") == "Y")
{
	$arr["edit_other"] = array(

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
);


$arUser = CPhoenix::deleteTabContentUser($arOurTabs, $arr, $arUser, $arPropFields, $arrSkipFields);

CPhoenix::showTabContentUser($arUser);





require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/concept.phoenix/iblock_forms_edit/common/common_footer.php');

	//////////////////////////
	//END of the custom form
	//////////////////////////