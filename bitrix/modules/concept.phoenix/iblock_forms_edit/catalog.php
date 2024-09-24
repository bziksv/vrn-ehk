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
	),
	array(
		"DIV" => "edit2",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_FB"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit3",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_ADVS"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit4",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CHAR"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit5",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_PICT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit6",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_VIDEO"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit7",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_REVIEW"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit8",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_FAQ"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit9",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_MORE"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit10",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_MORE_2"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit11",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_MORE_3"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit12",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONSULT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit13",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_ANALOG"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit14",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_ANALOG_CATEGORY"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit15",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_ACCESSORY"),
		"ICON" => "iblock_element",
	),
	
	array(
		"DIV" => "edit17",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_SEO"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit18",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_SECTIONS"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit19",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_COMPLECT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit20",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_SET_PRODUCT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit16",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_SORT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_other",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_OTHER"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_OTHER"),
	),
);

$aTabs = $arOurTabs;


// if($arShowTabs['sections'])
// {
// 	$aTabs[] = array(
// 		"DIV" => "sections",
// 		"TAB" => $arIBlock["SECTIONS_NAME"],
// 		"ICON" => "iblock_element_section",
// 		"TITLE" => htmlspecialcharsEx($arIBlock["SECTIONS_NAME"]),
// 	);
// }
	
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
		"CODE" => "ACTIVE",
		"NAME" => GetMessage("IB_FIELD_NAME_ACTIVE")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "ACTIVE_FROM",
		"NAME" => GetMessage("IB_FIELD_NAME_ACTIVE_FROM")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "ACTIVE_TO",
		"NAME" => GetMessage("IB_FIELD_NAME_ACTIVE_TO")
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
		"CODE" => "CODE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ITEM_TEMPLATE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_REGION",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CHOOSE_LANDING",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MODE_DISALLOW_ORDER",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PREORDER_ONLY",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MODE_ARCHIVE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MODE_HIDE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HEADER_PICTURE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMPL_BANNER_TYPE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMPL_BANNER",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BANNERS_LEFT_TYPE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BANNERS_LEFT",
	),
	
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BRAND",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PREVIEW_TEXT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHORT_DESCRIPTION",
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_1",
		"NAME" => GetMessage("IB_FORM_LABEL_1")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_vote_count",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_vote_sum",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_rating",
	),

	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_SEND_AFTER_PAY",
		"NAME" => GetMessage("IB_FORM_LABEL_SEND_AFTER_PAY")
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SEND_AFTER_PAY_THEME",
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SEND_AFTER_PAY_TEXT",
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SEND_AFTER_PAY_FILES",
	),

	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_AFTER_PAY_TEXT",
		"NAME" => GetMessage("IB_FORM_LABEL_AFTER_PAY_TEXT")
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_AFTER_PAY_TEXT",
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
		"CODE" => "PROPERTY_SHOWMENU_BLOCK1"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK1"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_RESIZE_IMAGES"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PREVIEW_PICTURE",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "DETAIL_PICTURE"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MORE_PHOTO"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_POPUP"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_LABELS"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ARTICLE"
	),

	/*array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PREVIEW_TEXT"
	),*/

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PREVIEW_TEXT_POSITION"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MODAL_WINDOWS"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORMS"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_QUIZS"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_FOR_ACTUAL_PRICE"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TIT_FOR_QUANTITY"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_COMMENT_FOR_QUANTITY"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_COMMENT_HIDE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SCROLL_TO_CHARS_OFF"
	),
	/*array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SKROLL_TO_CHARS_ON"
	),*/
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SKROLL_TO_CHARS_TITLE"
	),

	array(
		"TYPE" => "STRING",
		"ID" => "BTN_SECTION_TITLE",
		"NAME" => GetMessage("IB_FORM_BTN_SECTION_TITLE")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_NAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_ACTION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_BG_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_FORM"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_POPUP"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_LAND"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_URL"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_TARGET_BLANK"
	),
	
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_QUIZ_ID"
	),
	
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_PRODUCT_ID"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_OFFER_ID"
	),


	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BTN_ONCLICK"
	),
	
);

$arr["edit3"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PARENT_ADV"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADVANTAGES"
	),
	
);

$arr["edit4"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK3"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK3"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK3"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK3"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CHARS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FILES"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FILES_DESC"
	),
);

$arr["edit5"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK10"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK10"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK10"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK10"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_COLS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_ANIMATE"
	),

);

$arr["edit6"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK4"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK4"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK4"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK4"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_DESC"
	),
);

$arr["edit7"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK7"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK7"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK7"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK7"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLOGS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NEWS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SPECIALS"
	),
);

$arr["edit8"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK8"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK8"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK8"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK8"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FAQ_PICTURE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FAQ_NAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FAQ_PROF"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FAQ_BUTTON_NAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FAQ_FORM"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FAQ_ELEMENTS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FAQ_HIDE_FIRST_ITEM"
	)
);

$arr["edit9"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK9"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK9"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK9"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK9"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "DETAIL_TEXT"
	),
);

$arr["edit10"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK12"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK12"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK12"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK12"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_DETAIL_TEXT_BLOCK12"
	),
);

$arr["edit11"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK15"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK15"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK15"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK15"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_DETAIL_TEXT_BLOCK15"
	),
);


$arr["edit12"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK6"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK6"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK6"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK6"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_STUFF"
	),
);


$arr["edit13"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWBLOCK5"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK5"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK5"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK5"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK5"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SIMILAR_SOURCE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SIMILAR_CATEGORY_LIST"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SIMILAR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SIMILAR_MAX_QUANTITY"
	),
);

$arr["edit14"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK11"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK11"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK11"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK11"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SIMILAR_CATEGORY_INHERIT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SIMILAR_CATEGORY"
	),
	
);

$arr["edit15"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWBLOCK13"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK13"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK13"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK13"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK13"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ACCESSORY_SOURCE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ACCESSORY_CATEGORY_LIST"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ACCESSORY"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ACCESSORY_MAX_QUANTITY"
	),
);

$arr["edit17"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "IPROPERTY_TEMPLATES_ELEMENT_META_TITLE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION"
	),
);

$arr["edit18"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "SECTIONS",
	),
);

$arr["edit19"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOW_BLOCK_SET_PRODUCT"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK_SET_PRODUCT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK_SET_PRODUCT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK_SET_PRODUCT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK_SET_PRODUCT"
	)
);

$arr["edit20"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOW_BLOCK_SET_PRODUCT_CONSTRUCTOR"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOWMENU_BLOCK_SET_PRODUCT_CONSTRUCTOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENUTITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDELINE_BLOCK_SET_PRODUCT_CONSTRUCTOR"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_SET_PRODUCT_CONSTRUCTOR_OTHER",
		"NAME" => GetMessage("IB_FORM_LABEL_SET_PRODUCT_CONSTRUCTOR_OTHER")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR_OTHER"
	)
);

$arr["edit16"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SORT_SELF"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK3"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK10"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK4"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK7"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK8"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK9"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK12"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK15"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK6"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK5"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK11"
	),
	
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK13"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK14"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK_SET_PRODUCT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_POSITION_BLOCK_SET_PRODUCT_CONSTRUCTOR"
	),

);

if(\Bitrix\Main\Config\Option::Get("iblock", "show_xml_id") == "Y")
{
	$arr["edit_other"] = array(
		array(
			"TYPE" => "PROPERTY",
			"CODE" => "XML_ID",
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


<?if(CPhoenix::isLowLicense()):?>
	<style>
		#tab_cont_edit19,
		#tab_cont_edit20{
			display: none;
		}
	</style> 
<?endif;?>


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




if ($arIBlock["SECTION_PROPERTY"] === "Y" || defined("CATALOG_PRODUCT"))
{
	if(is_array($str_IBLOCK_ELEMENT_SECTION) && !empty($str_IBLOCK_ELEMENT_SECTION))
	{
	    foreach($str_IBLOCK_ELEMENT_SECTION as $section_id)
	    {
	          foreach(CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, $section_id) as $PID => $arLink)
	                $arPropLinks[$PID] = "PROPERTY_".$PID;
	       
	    }

	}
	else
	{
	    foreach(CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, 0) as $PID => $arLink)
	        $arPropLinks[$PID] = "PROPERTY_".$PID;
	}
}

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
	"PROPERTY_MINIMUM_PRICE",
	"PROPERTY_MAXIMUM_PRICE",
	"PROPERTY_SKROLL_TO_CHARS_ON"
);

$arUser = CPhoenix::deleteTabContentUser($arOurTabs, $arr, $arUser, $arPropFields, $arrSkipFields);


if(!empty($arUser) && !empty($arPropLinks))
{
	
	foreach ($arUser as $key => $value)
	{
		if(!array_key_exists($value["ID"], $arPropLinks))
			unset($arUser[$key]);
	}

	$arUser = CPhoenix::repairArUserFields($arUser);
}

CPhoenix::showTabContentUser($arUser);





require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/concept.phoenix/iblock_forms_edit/common/common_footer.php');



	//////////////////////////
	//END of the custom form
	//////////////////////////