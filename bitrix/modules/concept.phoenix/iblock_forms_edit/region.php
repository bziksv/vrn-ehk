<?

use Bitrix\Main\Loader,
	Bitrix\Main,
	Bitrix\Iblock,
	Bitrix\Catalog;

if(!CModule::IncludeModule("concept.phoenix"))
	die();


CUserOptions::DeleteOptionsByName("form", "form_element_".$IBLOCK_ID);


$arShowTabs['sections'] = $arShowTabs['edit_rights'] = false;

/* tabs*/
$arOurTabs = array(
	array(
		"DIV" => "edit1",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_BASE_SETTINGS"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_BASE_SETTINGS"),
	),
	/*array(
		"DIV" => "edit2",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_NAME_DECLINE"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_NAME_DECLINE"),
	),*/
	array(
		"DIV" => "edit3",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_INFO"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_INFO"),
	),

	array(
		"DIV" => "edit4",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_LID"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_LID"),
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
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NAME_DESCRIPTION",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FAVORITES",
	),
	/*array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_DEFAULT",
	),*/
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAIN_DOMAIN",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_DOMAINS",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PRICES_LINK",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_STORES_LINK",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_LOCATION_LINK_ORDER",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_LOCATION_LINK",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PICTURE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PICTURE_SMALL",
	)
	


);

/*$arr["edit2"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_REGION_NAME_DECLINE_RP",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_REGION_NAME_DECLINE_PP",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_REGION_NAME_DECLINE_TP",
	),

);*/

$arr["edit3"] = array(

	array(
		"TYPE" => "STRING",
		"ID" => "SECTION_TITLE_CONTACT_INFO",
		"NAME" => GetMessage("IB_FORM_TITLE_CONTACT_INFO")
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PHONES",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMAILS",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADDRESS",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_REGION_TAG_MAP",
	),
	array(
		"TYPE" => "STRING",
		"ID" => "SECTION_TITLE_CONTACT_GROUPS",
		"NAME" => GetMessage("IB_FORM_TITLE_CONTACT_GROUPS")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CONTACT_VK",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CONTACT_FACEBOOK",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CONTACT_TWITTER",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CONTACT_YOUTUBE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CONTACT_INSTAGRAM",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CONTACT_TELEGRAM",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CONTACT_OK",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CONTACT_TIKTOK",
	),
);

$arr["edit4"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMAIL_TO",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMAIL_FROM",
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


CJSCore::Init(array("jquery"));
\Bitrix\Main\Page\Asset::getInstance()->addJs("/bitrix/js/concept.phoenix/jquery-ui.min.js");

require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/concept.phoenix/iblock_forms_edit/common/common_footer.php');?>
<style>
	.admin-inputdatalist-preload{
	    width: 14px;
	    height: 14px;
	    position: absolute;
	    right: 7px;
	    top: 6px;
	    background-repeat: no-repeat;
	    background-image: url(/bitrix/panel/main/images/waiter-white.gif)!important;
	    background-size: contain;
	    display: none;
	}
	input.admin-inputdatalist-css.ui-autocomplete-loading + .admin-inputdatalist-preload{
	    display: block;
	}

	input.admin-inputdatalist-css{
        background: url(/bitrix/images/concept.phoenix/search_gr.svg) 6px center no-repeat !important;
	    background-size: 14px !important;
	    padding-left: 25px !important;
        background-color: #fff !important;
	}
	.admin-inputdatalist{
	    position: absolute;
	    left: 0;
	    right: 0;
	    top: 45px;
	}
	.admin-inputdatalist .ui-autocomplete {
	    background: #fff;
	    max-height: 240px;
	    overflow: auto;
	    list-style: none;
	    padding: 0;
	    margin: 0;
	    z-index: 9;
        box-shadow: 0px 15px 16px 0px rgba(50, 50, 50, 0.30);
	}
	.admin-inputdatalist .ui-menu-item {
	    cursor: pointer;
        font-size: 13px;
    	padding: 10px 10px 9px;
	    margin: 0;
	}
	.admin-inputdatalist .ui-autocomplete .ui-menu-item:hover {
	    background-color: #eee;
	}
</style>
<?

	//////////////////////////
	//END of the custom form
	//////////////////////////