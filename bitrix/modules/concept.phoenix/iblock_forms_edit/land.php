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
		"DIV" => "edit_start",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_START"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_settings",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_SETTINGS"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_design",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_DESIGN"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_fb",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_text",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_adv",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_numbers",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_video",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_catalog",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_item",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_tariff",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_photo",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_blog",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_faq",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_quote",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_map",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_form",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_tabs",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_clients",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_banners",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_stuff",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_component",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_slider",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_special",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_search",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_retranslator",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_changer_blocks",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_CONTENT"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_buttons",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_BUTTONS"),
		"ICON" => "iblock_element",
	),
	array(
		"DIV" => "edit_else",
		"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_OTHER"),
		"ICON" => "iblock_element",
		"TITLE" => GetMessage("IB_FORM_SETTING_CPHX_TAB_OTHER"),
	)
);

$aTabs = $arOurTabs;


/*if($arShowTabs['sections'])
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
}*/
	

$aTabs[] = array(
	"DIV" => "userprops",
	"TAB" => GetMessage("IB_FORM_SETTING_CPHX_TAB_USER"),
	"ICON" => "iblock_element",
);

$tabControl->tabs = "";

$tabControl->tabs = $aTabs;

/*struct*/

$arr = array();

$arr["edit_start"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "SECTIONS",
		"HIDDEN" => "Y"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "NAME",
		"NAME" => GetMessage("IB_FIELD_NAME_NAME")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TYPE",
	),
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
		"CODE" => "PROPERTY_ADMIN_ONLY_VIEW",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "SORT",
		"NAME" => GetMessage("IB_FIELD_NAME_SORT")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_REGION",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLOCK_INSTEAD",
	)		
	
);

$arr["edit_settings"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HEADER"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_THIS_H1"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SUBHEADER"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SUPHEADER"
	),

	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_1",
		"NAME" => GetMessage("IB_FORM_LABEL_1")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDE_BLOCK_LG"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HIDE_BLOCK"
	),
	
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_2",
		"NAME" => GetMessage("IB_FORM_LABEL_2")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHOW_IN_MENU"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENU_SORT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENU_TITLE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENU_ICON"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MENU_PIC"
	),
);

$arr["edit_design"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CLASS_BLOCK",
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_3",
		"NAME" => GetMessage("IB_FORM_LABEL_3")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BACKGROUND_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PREVIEW_PICTURE",
		"NAME" => GetMessage("IB_FIELD_NAME_PREVIEW_PICTURE")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_COVER"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_BACKGROUND_HIDE_PICTURE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_BACKGROUND"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_BACKGROUND_FILE_MP4"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_BACKGROUND_FILE_WEBM"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_BACKGROUND_FILE_OGG"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SHADOW"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_4",
		"NAME" => GetMessage("IB_FORM_LABEL_4")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAIN_TITLE_POS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAIN_TITLE_POS_MOB"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_HEADER_COLOR"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_5",
		"NAME" => GetMessage("IB_FORM_LABEL_5")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_SHADOW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_SIZE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TITLE_SIZE_MOB"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_6",
		"NAME" => GetMessage("IB_FORM_LABEL_6")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SUBTITLE_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SUBTITLE_SHADOW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SUBTITLE_SIZE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SUBTITLE_SIZE_MOB"
	),
	
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_7",
		"NAME" => GetMessage("IB_FORM_LABEL_7")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ANIMATE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PARALLAX"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SLIDES"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_LINES"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_8",
		"NAME" => GetMessage("IB_FORM_LABEL_8")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PADDING_TOP"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PADDING_BOTTOM"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MARGIN_TOP"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MARGIN_BOTTOM"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_9",
		"NAME" => GetMessage("IB_FORM_LABEL_9")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PADDING_TOP_MOB"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PADDING_BOTTOM_MOB"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MARGIN_TOP_MOB"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MARGIN_BOTTOM_MOB"
	)
);

$arr["edit_fb"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_CLICK_DOWN"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_SLIDER_TIME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_HEIGHT_WINDOW"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_10",
		"NAME" => GetMessage("IB_FORM_LABEL_10")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_ICONS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_ICONS_DESC"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_11",
		"NAME" => GetMessage("IB_FORM_LABEL_11")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_NAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_TYPE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_BG_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_FORM"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_MODAL"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_LAND"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_LINK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_BUTTON_BLANK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_BUTTON_QUIZ"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_CATALOG_ID"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_OFFER_ID"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_LB_ONCLICK"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_12",
		"NAME" => GetMessage("IB_FORM_LABEL_12")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_NAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_TYPE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_BG_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_FORM"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_MODAL"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_LAND"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_LINK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_BUTTON_BLANK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_BUTTON_QUIZ"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_CATALOG_ID"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_OFFER_ID"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_RB_ONCLICK"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_13",
		"NAME" => GetMessage("IB_FORM_LABEL_13")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_VIDEO_LINK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_VIDEO_NAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_VIDEO_COMMENT"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_14",
		"NAME" => GetMessage("IB_FORM_LABEL_14")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_TEXT_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_ADD_PICTURE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_IMAGE_BLOCK_POSITION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_IMAGE_POSITION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FB_IMAGE_POSITION_MOBILE"
	),
);

$arr["edit_text"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TEXT_BLOCK_TEXT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TEXT_BLOCK_TEXT_ALIGN"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TEXT_BLOCK_TEXT_ALIGN_MOB"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TEXT_BLOCK_GALLERY"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TEXT_BLOCK_BORDER"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_15",
		"NAME" => GetMessage("IB_FORM_LABEL_15")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TEXT_BLOCK_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TEXT_BLOCK_PICTURE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TEXT_BLOCK_PICTURE_BLOCK_POSITION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TEXT_BLOCK_PICTURE_POSITION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TEXT_BLOCK_IMAGE_POSITION_MOBILE"
	),

);

$arr["edit_adv"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADVANTAGES_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADVANTAGES_PICTURES_DESC"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADVANTAGES_TEXT_COLOR"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_16",
		"NAME" => GetMessage("IB_FORM_LABEL_16")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADVANTAGES_TYPE_PICTURE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADVANTAGES_VIEW_SIZE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADVANTAGES_PICTURES"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADVANTAGES_ICONS"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_17",
		"NAME" => GetMessage("IB_FORM_LABEL_17")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADVANTAGES_IMAGE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADVANTAGES_IMAGE_BLOCK_POSITION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADVANTAGES_IMAGE_POSITION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_ADVANTAGES_IMAGE_POSITION_MOBILE"
	),
	
);

$arr["edit_numbers"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NUMBERS_TEXTS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NUMBERS_FONT_SIZE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NUMBERS_TEXTS_COLOR"
	),
);

$arr["edit_video"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_BLOCK_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_BLOCK_CODE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_BLOCK_PICTURES"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_VIDEO_BLOCK_TEXT"
	),
);

$arr["edit_catalog"] = array(
	
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CATALOG"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CATALOG_COUNT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CATALOG_COUNT_NAME"
	)
);

$arr["edit_item"] = array(
	
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CATALOG_BIG_ITEMS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CATALOG_BIG_TEXT_CLR"
	)
);

$arr["edit_tariff"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_ROUND_HEIGHT"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_20",
		"NAME" => GetMessage("IB_FORM_LABEL_20")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_NAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_PREVIEW_TEXT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_PICTURE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_HIT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_INCLUDE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_NOT_INCLUDE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_PRICE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_OLD_PRICE"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_21",
		"NAME" => GetMessage("IB_FORM_LABEL_21")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_BUTTON_NAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_BUTTON_BG_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_BUTTON_TYPE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_BUTTON_FORM"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_MODAL"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_BUTTON_LAND"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_BUTTON_LINK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_BUTTON_BLANK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_BUTTON_QUIZ"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_BUTTON_ONCLICK"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_22",
		"NAME" => GetMessage("IB_FORM_LABEL_22")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_DETAIL_TEXT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_WATERMARK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_GALLERY_TITLE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_GALLERY"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_GALLERY_BORDER"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_23",
		"NAME" => GetMessage("IB_FORM_LABEL_23")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_PRICES"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_PRICES_TITLE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_TEXT_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_PICTURE_POSITION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_TARIFF_IMAGE_POSITION_MOBILE"
	),
);

$arr["edit_photo"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_WATERMARK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_24",
		"NAME" => GetMessage("IB_FORM_LABEL_24")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_COUNT_PHOTO_1"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_COUNT_PHOTO_2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_COUNT_PHOTO_3"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_COUNT_PHOTO_4"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_BORDER"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_DESK_COLOR"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_25",
		"NAME" => GetMessage("IB_FORM_LABEL_25")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GALLERY_PICS_IN_SLIDE"
	),
	
);

$arr["edit_blog"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NEWS_VIEW"
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NEWS_MODE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NEWS_CHRONO"
	),


	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_42",
		"NAME" => GetMessage("IB_FORM_LABEL_42")
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NEWS_ELEMENTS_BLOG"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NEWS_ELEMENTS_NEWS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NEWS_ELEMENTS_ACTION"
	),

	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_43",
		"NAME" => GetMessage("IB_FORM_LABEL_43")
	),
	
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NEWS_RESOURCE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NEWS_QUANTITY"
	),

	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_44",
		"NAME" => GetMessage("IB_FORM_LABEL_44")
	),

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NEWS_PICTURE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_NEWS_HIDE_DATE"
	),

	
);

$arr["edit_faq"] = array(
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
		"CODE" => "PROPERTY_FAQ_PICTURE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FAQ_ELEMENTS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FAQ_HIDE_FIRST_ITEM"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FAQ_QUESTS_TXT_CLR"
	),
	
);

$arr["edit_quote"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_VIEW"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_26",
		"NAME" => GetMessage("IB_FORM_LABEL_26")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_TEXT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_NAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_PROF"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_IMAGE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_FILE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_FILE_TEXT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_VIDEO_TEXT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_VIDEO"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_27",
		"NAME" => GetMessage("IB_FORM_LABEL_27")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_IMAGE_BLOCK_POSITION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_IMAGE_POSITION_MOBILE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_TEXT_COLOR"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_28",
		"NAME" => GetMessage("IB_FORM_LABEL_28")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_OPINION_ROUND_OFF"
	),
);

$arr["edit_map"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAP_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAP_NAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAP_ADDRESS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAP_PHONE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAP_MAIL"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAP_DESC"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAP_SUBSTRATE_POS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAP"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAP_SIZE"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_29",
		"NAME" => GetMessage("IB_FORM_LABEL_29")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAP_PICTURE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAP_GALLERY"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_MAP_ADVS"
	),

);

$arr["edit_form"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_ADMIN"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_30",
		"NAME" => GetMessage("IB_FORM_LABEL_30")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_INPUTS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_INPUTS_REQ"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_LIST_TITLE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_RADIOCHECK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_LIST"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_31",
		"NAME" => GetMessage("IB_FORM_LABEL_31")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_PROP_INPUTS"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_32",
		"NAME" => GetMessage("IB_FORM_LABEL_32")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TITLE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_SUBTITLE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_BUTTON"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_BUTTON_BG_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_THANKS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TEXT_TITLE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TEXT_UNDER_TITLE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TEXT_TITLE_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_IMAGE"
	),
	array(
		"TYPE" => "PROPERTY",
		"HIDDEN" => "Y",
		"CODE" => "PROPERTY_FORM_IMAGE_HIDDEN_XS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_IMAGE_POSITION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_IMAGE_BLOCK_POSITION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_IMAGE_POSITION_MOBILE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_UPLINE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_BACKGROUND"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_BGC"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TEXT_COLOR"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_33",
		"NAME" => GetMessage("IB_FORM_LABEL_33")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TIMER_ON"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TIMER_SHOW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TIMER_TEXT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TIMER_HIDE"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_34",
		"NAME" => GetMessage("IB_FORM_LABEL_34")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TO_LINK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TO_LINK_NEWTAB"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_THEME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_TEXT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_FILES"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_34_1",
		"NAME" => GetMessage("IB_FORM_LABEL_34_1")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMAIL_TO",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_DUBLICATE",
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_35",
		"NAME" => GetMessage("IB_FORM_LABEL_35")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_YANDEX_GOAL"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GOOGLE_GOAL_CATEGORY"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GOOGLE_GOAL_ACTION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GTM_EVENT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GTM_GOAL_CATEGORY"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_GTM_GOAL_ACTION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_FORM_JS_AFTER_SEND"
	),
);

$arr["edit_tabs"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SWITCHER_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SWITCHER_TABNAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SWITCHER_HTML"
	),
);

$arr["edit_clients"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PARTNERS_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PARTNERS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PARTNERS_LINKS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PARTNERS_GRAYSCALE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_PARTNERS_SUBSTRATE"
	),
);

$arr["edit_banners"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_VIEW"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_36",
		"NAME" => GetMessage("IB_FORM_LABEL_36")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_TITLE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_TEXT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_TEXT_VIEW"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_37",
		"NAME" => GetMessage("IB_FORM_LABEL_37")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_ANIM_OFF"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_TEXT_POS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_BACKGROUND"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_BG_CLR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_TEXT_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_COLS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_PICTURE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_POSITION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_WIDTH_FULL"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_38",
		"NAME" => GetMessage("IB_FORM_LABEL_38")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_NAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_BUTTON_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_BUTTON_BG_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_BTN_TYPE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_LINK_BLOCK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_BUTTON_FORM"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_BUTTON_MODAL"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_BTN_LAND"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_BUTTON_LINK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_BUTTON_BLANK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_BUTTON_QUIZ"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_CATALOG_ID"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_OFFER_ID"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BLINK_ONCLICK"
	),
	
);

$arr["edit_stuff"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMPL_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMPL"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMPL_PICTURE_POSITION"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_EMPL_PICTURES_ROUND"
	),
);

$arr["edit_component"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_COMPONENT_PATH"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_COMPONENT_DESIGN"
	),
);

$arr["edit_slider"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SLIDER_TEXT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SLIDER_TEXT_CLR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SLIDER_PICTURE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SLIDER_PICTURE_POS_HOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SLIDER_PICTURE_POS_VERT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SLIDER_SCROLL_SPEED"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SLIDER_IMAGE_POSITION_MOBILE"
	),
);

$arr["edit_special"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CATALOG_LABELS_SECTIONS"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CATALOG_LABELS_ITEMS_MAX_COUNT"
	),
);

$arr["edit_search"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SEARCH_IN"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SEARCH_HINT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SEARCH_PLACEHOLDER"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_39",
		"NAME" => GetMessage("IB_FORM_LABEL_39")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SEARCH_MARGIN_FROM_TEXT"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SEARCH_MARGIN_FROM_TEXT_MOB"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SEARCH_CONTAINER_WIDTH"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SEARCH_BORDER_BOX_SHADOW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_SEARCH_FASTSEARCH"
	),
);

$arr["edit_retranslator"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_RETRANSLATOR"
	),
);

$arr["edit_changer_blocks"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CHANGER_BLOCKS_LABEL"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CHANGER_BLOCKS_ID"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CHANGER_BLOCKS_STYLE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_CHANGER_BLOCKS_ALIGN"
	),
);

$arr["edit_buttons"] = array(
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTONS_ALIGN"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_40",
		"NAME" => GetMessage("IB_FORM_LABEL_40")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_NAME"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_TYPE"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_VIEW"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_BG_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_FORM"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_MODAL"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_LAND"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_LINK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_BLANK"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_QUIZ"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_CATALOG_ID"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_OFFER_ID"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_ONCLICK"
	),
	array(
		"TYPE" => "STRING",
		"ID" => "LABEL_41",
		"NAME" => GetMessage("IB_FORM_LABEL_41")
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_NAME_2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_TYPE_2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_VIEW_2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_2_BG_COLOR"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_FORM_2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_MODAL_2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_LAND_2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_LINK_2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_BLANK_2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_QUIZ_2"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_2_CATALOG_ID"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_2_OFFER_ID"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_2_OFFER_ID"
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "PROPERTY_BUTTON_2_ONCLICK"
	),
);

$arr["edit_else"] = array(

	array(
		"TYPE" => "PROPERTY",
		"CODE" => "XML_ID",
	),
	array(
		"TYPE" => "PROPERTY",
		"CODE" => "SECTIONS",
		"HIDDEN" => "Y"
	),
);


	


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
);


$arUser = CPhoenix::deleteTabContentUser($arOurTabs, $arr, $arUser, $arPropFields, $arrSkipFields);



if(!empty($arUser) && !empty($arPropLinks))
{
	foreach ($arUser as $key => $value)
	{
		if(!array_key_exists($value["ID"], $arPropLinks))
			unset($arUser[$key]);
	}
}


CPhoenix::showTabContentUser($arUser);





require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/concept.phoenix/iblock_forms_edit/common/common_footer.php');



	//////////////////////////
	//END of the custom form
	//////////////////////////