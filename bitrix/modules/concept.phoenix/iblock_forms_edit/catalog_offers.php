<?

use Bitrix\Main\Loader,
	Bitrix\Main,
	Bitrix\Iblock,
	Bitrix\Catalog;

if(!CModule::IncludeModule("concept.phoenix"))
	die();


CUserOptions::DeleteOptionsByName("form", "form_element_".$IBLOCK_ID);

$arShowTabs['sections'] = false;



/* tabs*/
$arOurTabs = array(
	array(
		"DIV" => "edit1",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_CATALOG_OFFERS_BASE_SETTINGS"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit2",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_CATALOG_OFFERS_TAB_OTHER"),
		"ICON" => "iblock_element",
	),
	
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
	"TAB" => GetMessage("IB_FORM_SETTING_CPHX_CATALOG_OFFERS_USER"),
	"ICON" => "iblock_element",
);

$tabControl->tabs = "";

$tabControl->tabs = $aTabs;
/**/

/*struct*/

$arr = array();

$arr["edit1"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "ACTIVE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PREVIEW_PICTURE"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "ACTIVE_FROM",
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "ACTIVE_TO",
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
		"CODE" => "ARTICLE",
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "MORE_PHOTO"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PREVIEW_TEXT"
	),
);

$arr["edit2"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "XML_ID",
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "CML2_LINK",
	),
);


$arPropFields = array();

foreach($PROP as $prop_fields)
{
	$arPropFields[$prop_fields["CODE"]] = $prop_fields["ID"];
}



	/*^struct*/

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

	if($arTranslit["TRANSLITERATION"] == "Y")
	{
		CJSCore::Init(array('translit'));
		?>
		<script src = "/bitrix/modules/concept.phoenix/iblock_forms_edit/common/translate.js" type="text/javascript"></script>
		<?
	}
	?>
	<script src = "/bitrix/modules/concept.phoenix/iblock_forms_edit/common/tab_control.js" type="text/javascript"></script>
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
	$arEditLinkParams['lookup'] = $strLookup;


$tabControl->Begin(array(
	"FORM_ACTION" => "/bitrix/admin/".CIBlock::GetAdminElementEditLink($IBLOCK_ID, null, $arEditLinkParams)
));


$arUser = $PROP;

foreach ($arOurTabs as $arTab)
{
	$tabControl->BeginNextFormTab();

	foreach ($arr[$arTab["DIV"]] as $elements) 
	{
		if($elements["TYPE"] == "PROPERTY")
		{
			CPhoenix::showHtmlField($elements["CODE"], $elements["NAME"]);
			unset($arUser[ $arPropFields[$elements["CODE"]]  ]); 
		}

		if($elements["TYPE"] == "STRING")
		{
			$tabControl->AddSection($elements["ID"], $elements["NAME"]);
		}
	}
}

require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/concept.phoenix/iblock_forms_edit/common/common_tabs.php');



//user
$arrSkipFields = array(
	"USER_FIELD123",
);

if(!empty($arUser))
{
	

	foreach ($arUser as $key => $items){
		if(in_array($items["CODE"], $arrSkipFields))
			unset($arUser[$key]);
	}

	$arUser = CPhoenix::repairArUserFields($arUser);
	
	if(!empty($arUser))
	{
		$tabControl->BeginNextFormTab();
	
		foreach ($arUser as $items){
			CPhoenix::showHtmlField($items["CODE"], "");
		}
	}
}



require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/concept.phoenix/iblock_forms_edit/common/common_footer.php');

	//////////////////////////
	//END of the custom form
	//////////////////////////