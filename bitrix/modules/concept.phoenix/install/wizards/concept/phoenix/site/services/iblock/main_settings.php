<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
set_time_limit(0);

use \Bitrix\Main\Config\Option as Option;


Option::set("concept.phoenix", "phoenix_basket_url", WIZARD_SITE_DIR."basket/", WIZARD_SITE_ID);
Option::set("concept.phoenix", "phoenix_cart_minicart_link_page", WIZARD_SITE_DIR."catalog/", WIZARD_SITE_ID);

Option::set("concept.phoenix", "phoenix_search_page", WIZARD_SITE_DIR."search/", WIZARD_SITE_ID);



if(WIZARD_INSTALL_DEMO_DATA)
{
	$lang = "ru";
	WizardServices::IncludeServiceLang("lang_main_settings.php", $lang);

	if(!CModule::IncludeModule("iblock"))
		return;

	if(!CModule::IncludeModule("catalog"))
		return;
	    
	//All Forms List
	$arForms = Array();

	$arSelect = Array("ID", "XML_ID");
	$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_forms_".WIZARD_SITE_ID);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

	while($ob = $res->GetNext())
	    $arForms[$ob["XML_ID"]] = $ob["ID"]; 
	    
	    
	//All Banners List
	$arBanners = Array();

	$arSelect = Array("ID", "XML_ID");
	$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_banners_".WIZARD_SITE_ID);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

	while($ob = $res->GetNext())
	    $arBanners[$ob["XML_ID"]] = $ob["ID"];


	//All Advantages List
	$arAdvs = Array();

	$arSelect = Array("ID", "XML_ID");
	$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_advantages_".WIZARD_SITE_ID);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

	while($ob = $res->GetNext())
	    $arAdvs[$ob["XML_ID"]] = $ob["ID"];


	//All Agreements List
	$arAgrs = Array();

	$arSelect = Array("ID", "XML_ID");
	$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_agreements_".WIZARD_SITE_ID);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

	while($ob = $res->GetNext())
	    $arAgrs[$ob["XML_ID"]] = $ob["ID"];


	/*DESIGN*/

	//favicon
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/fav.png");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_favicon", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_favicon", $fid, WIZARD_SITE_ID);

	//logo
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/logo.png");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_logotype", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_logotype", $fid, WIZARD_SITE_ID);

	//logo mobile
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/logo_mob.png");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_logo_mob", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_logo_mob", $fid, WIZARD_SITE_ID);


	Option::set("concept.phoenix", "phoenix_pic_quality", 85, WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_font_title", "lato", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_font_text", "segoeUI", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_color_std", "#e59a05", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_font_color", "light", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_head_tone", "dark", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_color_header", "def", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_btn_view", "elips", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_head_view", "two", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_head_bg_opacity", 70, WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_head_fixed", "fixed", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_headBgXs4pagesMode", "default", WIZARD_SITE_ID);


	/*CONTACTS*/
	Option::set("concept.phoenix", "phoenix_callback_show", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_callback_name", htmlspecialcharsEx(GetMessage("PHOENIX_CALLBACK")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_id_callback", $arForms["forms_1_1"], WIZARD_SITE_ID);


	$arPhone = Array();

	$arPhone[0]["name"] = htmlspecialcharsEx(GetMessage("PHOENIX_DEF_PHONE_0"));
	$arPhone[0]["desc"] = htmlspecialcharsEx(GetMessage("PHOENIX_DEF_PHONE_DESC_0"));

	$arPhone[1]["name"] = htmlspecialcharsEx(GetMessage("PHOENIX_DEF_PHONE_1"));
	$arPhone[1]["desc"] = htmlspecialcharsEx(GetMessage("PHOENIX_DEF_PHONE_DESC_1"));

	$arPhone = base64_encode(serialize($arPhone));
	Option::set("concept.phoenix", "phoenix_phones", $arPhone, WIZARD_SITE_ID);

	$arEmail = Array();

	$arEmail[0]["name"] = htmlspecialcharsEx(GetMessage("PHOENIX_DEF_EMAIL_0"));
	$arEmail[0]["desc"] = htmlspecialcharsEx(GetMessage("PHOENIX_DEF_EMAIL_DESC_0"));

	$arEmail = base64_encode(serialize($arEmail));
	Option::set("concept.phoenix", "phoenix_emails_addr", $arEmail, WIZARD_SITE_ID);


	Option::set("concept.phoenix", "phoenix_contact_address", htmlspecialcharsEx(GetMessage("PHOENIX_ADDRESS")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_contact_dialog_map", htmlspecialcharsEx(GetMessage("PHOENIX_DIALOG_MAP")), WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_soc_vk", htmlspecialcharsEx(GetMessage("PHOENIX_SOC_VK")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_soc_fb", htmlspecialcharsEx(GetMessage("PHOENIX_SOC_FB")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_soc_tw", htmlspecialcharsEx(GetMessage("PHOENIX_SOC_TW")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_soc_youtube", htmlspecialcharsEx(GetMessage("PHOENIX_SOC_YT")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_soc_inst", htmlspecialcharsEx(GetMessage("PHOENIX_SOC_IN")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_soc_telegram", htmlspecialcharsEx(GetMessage("PHOENIX_SOC_TG")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_soc_ok", htmlspecialcharsEx(GetMessage("PHOENIX_SOC_OK")), WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_soc_pos_contacts", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_soc_pos_menu", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_soc_pos_main_menu", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_soc_pos_footer", "Y", WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_widget_fast_call_on", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_widget_fast_call_num", htmlspecialcharsEx(GetMessage("PHOENIX_WIDGET_CALL_NUM")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_widget_fast_call_desc", htmlspecialcharsEx(GetMessage("PHOENIX_WIDGET_CALL_DESC")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_detail_catalog_shares_on", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_share_for_detail_catalog", htmlspecialcharsEx(GetMessage("PHOENIX_SHARE_CATALOG")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_mask", "rus", WIZARD_SITE_ID);


	/*MAIN MENU*/

	Option::set("concept.phoenix", "phoenix_menu_type", "on_board", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_menu_view", "full", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_menu_view_second_lvl_for_catalog", "flat", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_menu_bg_color", "#ffffff", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_menu_bg_opacity", 80, WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_menu_text_color", "def", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_mobile_menu_tone", "dark", WIZARD_SITE_ID);


	/*FOOTER*/

	Option::set("concept.phoenix", "phoenix_footer_on", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_footer_desc", htmlspecialcharsEx(GetMessage("PHOENIX_FOOTER_DESC")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_footer_info", htmlspecialcharsEx(GetMessage("PHOENIX_FOOTER_INFO")), WIZARD_SITE_ID);

	//footer
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/footer.jpg");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_footer_bg", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_footer_bg", $fid, WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_footer_copyright_type", "user", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_footer_copyright_user_desc", htmlspecialcharsEx(GetMessage("PHOENIX_FOOTER_COPY_DESC")), WIZARD_SITE_ID);
	
	Option::set("concept.phoenix", "phoenix_footer_copyright_on", "Y", WIZARD_SITE_ID);
	
	//footer logo min
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/logo_min.png");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_footer_copyright_user_pic", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_footer_copyright_user_pic", $fid, WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_footer_copyright_user_url", htmlspecialcharsEx(GetMessage("PHOENIX_FOOTER_COPY_URL")), WIZARD_SITE_ID);

	//footer banner1
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/youtube.png");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_footer_banner_1", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_footer_banner_1", $fid, WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_footer_banner_2_url", htmlspecialcharsEx(GetMessage("PHOENIX_FOOTER_BANNER1_URL")), WIZARD_SITE_ID);

	//footer banner2
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/market.png");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_footer_banner_2", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_footer_banner_2", $fid, WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_footer_banner_2_url", htmlspecialcharsEx(GetMessage("PHOENIX_FOOTER_BANNER2_URL")), WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_footer_subscripe", "", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_footer_subscripe_description", htmlspecialcharsEx(GetMessage("PHOENIX_FOOTER_SUBSCRIBE")), WIZARD_SITE_ID);
	

	//footer small pic
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/pay.png");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_footer_payment_pic", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_footer_payment_pic", $fid, WIZARD_SITE_ID);


	/*CATALOG*/
	$mainPrice = 0;

	$dbPriceType = CCatalogGroup::GetList();
	while($arPriceType = $dbPriceType->Fetch())
	{
		if($arPriceType["NAME"] === "BASE" || $mainPrice == 0)
			$mainPrice = $arPriceType["ID"];

	}

	if($mainPrice > 0)
		Option::set("concept.phoenix", "phoenix_type_price_".$mainPrice, "Y", WIZARD_SITE_ID);


	$arProps = Array(
		"COLOR" => "pic",
		"MEMORY" => "text",
		"COMPLECT" => "text",
		"VELO_COLOR" => "pic",
		"RAMA_SIZE" => "select"
	);

	$rsIBlock = CIBlock::GetList(array(), array("CODE" => "concept_phoenix_catalog_offers_".WIZARD_SITE_ID, "TYPE" => "concept_phoenix_".WIZARD_SITE_ID));

	if($arIBlock = $rsIBlock->Fetch())
	{
		$arProperty = array();
		$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["ID"]));
		while($arProp = $dbProperty->Fetch())
			$arProperty[$arProp["CODE"]] = $arProp["ID"];

		foreach($arProperty as $key => $prop) 
		{
			if(array_key_exists($key, $arProps))
			{
				Option::set("concept.phoenix", "phoenix_offer_field_".$prop, $prop, WIZARD_SITE_ID);
				Option::set("concept.phoenix", "phoenix_offer_field_".$prop."_value_2", $arProps[$key], WIZARD_SITE_ID);
			}
		}
	} 

	$res_measure = CCatalogMeasure::getList();
    while($measure = $res_measure->GetNext()) 
        Option::set("concept.phoenix", "phoenix_measure_".$measure["ID"], "Y", WIZARD_SITE_ID);
         

    Option::set("concept.phoenix", "phoenix_catalog_use_filter", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_delay_on", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_zoom_on", "Y", WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_catalog_link_2_detail_page_name", htmlspecialcharsEx(GetMessage("PHOENIX_CATALOG_LINK")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_catalog_link_2_detail_page_name_offer", htmlspecialcharsEx(GetMessage("PHOENIX_CATALOG_LINK_OFFER")), WIZARD_SITE_ID);

	//catalog background
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/catalog.jpg");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_catalog_head_bg_pic", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_catalog_head_bg_pic", $fid, WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_catalog_subtitle", htmlspecialcharsEx(GetMessage("PHOENIX_CATALOG_SUBTITLE")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_catalog_labels_max_count", 8, WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_store_quantity_on", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_store_quantity_view", "visible", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_store_quantity_many", 30, WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_store_quantity_few", 5, WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_catalog_preview_text_position", "under_pic", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_tit_for_quantity", htmlspecialcharsEx(GetMessage("PHOENIX_CATALOG_QUANTITY")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_for_detail_catalog", htmlspecialcharsEx(GetMessage("PHOENIX_CATALOG_DETAIL")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_form_better_price", $arForms["forms_1_2"], WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_catalog_use_rub", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_use_btn_scroll2chars", "Y", WIZARD_SITE_ID);


	/*ITEMS*/

	$rsIBlock = CIBlock::GetList(array(), array("CODE" => "concept_phoenix_catalog_offers_".WIZARD_SITE_ID, "TYPE" => "concept_phoenix_".WIZARD_SITE_ID));

	if($arIBlock = $rsIBlock->Fetch())
	{
		$arProperty = array();
		$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["ID"]));
		while($arProp = $dbProperty->Fetch())
			$arProperty[$arProp["CODE"]] = $arProp["ID"];


		$arProps = Array(
			"COLOR",
			"MEMORY",
			"COMPLECT",
			"VELO_COLOR",
			"RAMA_SIZE"
		);

		foreach($arProperty as $key => $prop) 
		{
			if(in_array($key, $arProps))
			{
				Option::set("concept.phoenix", "phoenix_catalog_list_sku_fields_".$prop, $prop, WIZARD_SITE_ID);
				Option::set("concept.phoenix", "phoenix_catalog_detail_sku_fields_".$prop, $prop, WIZARD_SITE_ID);
			}
			
		}
	} 


	$rsIBlock = CIBlock::GetList(array(), array("CODE" => "concept_phoenix_catalog_".WIZARD_SITE_ID, "TYPE" => "concept_phoenix_".WIZARD_SITE_ID));

	if($arIBlock = $rsIBlock->Fetch())
	{
		$arProperty = array();
		$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["ID"]));
		while($arProp = $dbProperty->Fetch())
			$arProperty[$arProp["CODE"]] = $arProp["ID"];

		$arProps = Array(
			"DIAGONAL_apple",
			"WEIGHT_gr",
			"WEIGHT_kg",
			"COUNTRY",
			"MATERIAL",
			"VELO_TYPE",
			"VELO_DIAMETR",
			"VELO_BRAKES",
			"BLOCK_VILKA",
		);

		foreach($arProperty as $key => $prop) 
		{
			if(in_array($key, $arProps))
			{
				Option::set("concept.phoenix", "phoenix_catalog_item_list_chars_fields_".$prop, $prop, WIZARD_SITE_ID);
				Option::set("concept.phoenix", "phoenix_catalog_item_detail_chars_fields_".$prop, $prop, WIZARD_SITE_ID);
			}
			
		}

	}

	Option::set("concept.phoenix", "phoenix_show_prediction", "Y", WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_catalogIF_l_flat_preview_text", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_catalogIF_l_flat_description", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_catalogIF_l_flat_rate_vote", "Y", WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_catalogIF_l_list_preview_text", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_catalogIF_l_list_description", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_catalogIF_l_list_sku_props", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_catalogIF_l_list_chars", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_catalogIF_l_list_item_props", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_catalogIF_l_list_rate_vote", "Y", WIZARD_SITE_ID);
	

	/*RATING*/
	Option::set("concept.phoenix", "phoenix_ctl_use_vote", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_use_review", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_review_moderator", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_review_send_new_review", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_rating_sidemenu_show", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_rating_sidemenu_name", htmlspecialcharsEx(GetMessage("PHOENIX_RATING_SIDEMENU_NAME")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_rating_block_title", htmlspecialcharsEx(GetMessage("PHOENIX_RATING_BLOCK_TITLE")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_review_block_title", htmlspecialcharsEx(GetMessage("PHOENIX_REVIEW_BLOCK_TITLE")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_rating_recommend_hint", htmlspecialcharsEx(GetMessage("PHOENIX_RATING_RECOMMEND_HINT")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_review_fly_pnl_desc", htmlspecialcharsEx(GetMessage("PHOENIX_REVIEW_FLY_PNL_DESC")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_review_fly_pnl_success", htmlspecialcharsEx(GetMessage("PHOENIX_REVIEW_FLY_PNL_SUCCESS")), WIZARD_SITE_ID);

	/*BRANDS*/
	Option::set("concept.phoenix", "phoenix_brand_desc", htmlspecialcharsEx(GetMessage("PHOENIX_BRAND_DESC")), WIZARD_SITE_ID);


	/*BASKET*/
	Option::set("concept.phoenix", "phoenix_cart_on", "Y", WIZARD_SITE_ID);

	
	Option::set("concept.phoenix", "phoenix_cart_minicart_mode", "semi_show", WIZARD_SITE_ID);
	
	Option::set("concept.phoenix", "phoenix_cart_minicart_desc_empty", htmlspecialcharsEx(GetMessage("PHOENIX_MINICART_DESC_EMPTY")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_cart_minicart_desc_noempty", htmlspecialcharsEx(GetMessage("PHOENIX_MINICART_DESC_NOEMPTY")), WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_cart_conditions", $arAgrs["agreement_1"], WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_cart_link_conditions", WIZARD_SITE_DIR."delivery/", WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_cart_head_tit", htmlspecialcharsEx(GetMessage("PHOENIX_CART_HEAD_TITLE")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_cart_btn_add_name", htmlspecialcharsEx(GetMessage("PHOENIX_CART_ADD_NAME")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_cart_btn_added_name", htmlspecialcharsEx(GetMessage("PHOENIX_CART_ADDED_NAME")), WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_cart_advs".$arAdvs["adv_sect_1_1"], $arAdvs["adv_sect_1_1"], WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_cart_advs".$arAdvs["adv_sect_1_4"], $arAdvs["adv_sect_1_4"], WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_cart_advs".$arAdvs["adv_sect_1_3"], $arAdvs["adv_sect_1_3"], WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_cart_advs".$arAdvs["adv_sect_1_2"], $arAdvs["adv_sect_1_2"], WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_cart_advs".$arAdvs["adv_sect_2_1"], $arAdvs["adv_sect_2_1"], WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_cart_advs".$arAdvs["adv_sect_1_6"], $arAdvs["adv_sect_1_6"], WIZARD_SITE_ID);
	
	Option::set("concept.phoenix", "phoenix_cart_add_on", "Y", WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_cart_page_title", htmlspecialcharsEx(GetMessage("PHOENIX_CART_PAGE_TITLE")), WIZARD_SITE_ID);

	//basket background
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/basket.jpg");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_cart_page_bg", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_cart_page_bg", $fid, WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_cart_page_empty_mess", htmlspecialcharsEx(GetMessage("PHOENIX_CART_PAGE_EMPTY_MESS", Array ("#SITE_DIR#" => WIZARD_SITE_DIR))), WIZARD_SITE_ID);


	/*ORDER*/
	Option::set("concept.phoenix", "phoenix_delivery_to_paysystem", "p2d", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_template_location", "popup", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_show_zip_input", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_basket_position", "before", WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_cart_btn_order_name", htmlspecialcharsEx(GetMessage("PHOENIX_CART_ORDER_NAME")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_cart_btn_pre_order_name", htmlspecialcharsEx(GetMessage("PHOENIX_CART_PREOREDER_NAME")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_cart_comment", htmlspecialcharsEx(GetMessage("PHOENIX_CART_COMMENT")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_order_complited_mess", htmlspecialcharsEx(GetMessage("PHOENIX_CART_COMP_MESS", Array ("#SITE_DIR#" => WIZARD_SITE_DIR))), WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_shop_allow_new_profile", "Y", WIZARD_SITE_ID);
    Option::set("concept.phoenix", "phoenix_shop_allow_user_profiles", "Y", WIZARD_SITE_ID);

	/*COMPARE*/
	Option::set("concept.phoenix", "phoenix_compare_active", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_compare_desc", htmlspecialcharsEx(GetMessage("PHOENIX_COMPARE_DESC")), WIZARD_SITE_ID);

	//compare background
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/compare.jpg");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_compare_bg_pic", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_compare_bg_pic", $fid, WIZARD_SITE_ID);


	$rsIBlock = CIBlock::GetList(array(), array("CODE" => "concept_phoenix_catalog_offers_".WIZARD_SITE_ID, "TYPE" => "concept_phoenix_".WIZARD_SITE_ID));

	if($arIBlock = $rsIBlock->Fetch())
	{
		$arProperty = array();
		$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["ID"]));
		while($arProp = $dbProperty->Fetch())
			$arProperty[$arProp["CODE"]] = $arProp["ID"];


		$arProps = Array(
			"COLOR",
			"MEMORY",
			"COMPLECT",
			"VELO_COLOR",
			"VELO_SIZE"
		);

		foreach($arProperty as $key => $prop) 
		{
			if(in_array($key, $arProps))
				Option::set("concept.phoenix", "phoenix_compare_sku_".$prop, $prop, WIZARD_SITE_ID);
		}
	} 


	$rsIBlock = CIBlock::GetList(array(), array("CODE" => "concept_phoenix_catalog_".WIZARD_SITE_ID, "TYPE" => "concept_phoenix_".WIZARD_SITE_ID));

	if($arIBlock = $rsIBlock->Fetch())
	{
		$arProperty = array();
		$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIBlock["ID"]));
		while($arProp = $dbProperty->Fetch())
			$arProperty[$arProp["CODE"]] = $arProp["ID"];

		$arProps = Array(
			"DIAGONAL_apple",
			"WEIGHT_gr",
			"WEIGHT_kg",
			"COUNTRY",
			"MATERIAL",
			"VELO_TYPE",
			"VELO_DIAMETR",
			"VELO_BRAKES",
			"BLOCK_VILKA",
		);

		foreach($arProperty as $key => $prop) 
		{
			if(in_array($key, $arProps))
				Option::set("concept.phoenix", "phoenix_compare_props_".$prop, $prop, WIZARD_SITE_ID);	
		}

	}


	/*BLOG*/
	Option::set("concept.phoenix", "phoenix_blg_more", htmlspecialcharsEx(GetMessage("PHOENIX_BLOG_MORE")), WIZARD_SITE_ID);

	//blog background
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/blog.jpg");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_blog_bg_pic", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_blog_bg_pic", $fid, WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_blog_desc", htmlspecialcharsEx(GetMessage("PHOENIX_BLOG_DESC")), WIZARD_SITE_ID);


	/*NEWS*/
	Option::set("concept.phoenix", "phoenix_news_more", htmlspecialcharsEx(GetMessage("PHOENIX_NEWS_MORE")), WIZARD_SITE_ID);

	//news background
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/news.jpg");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_news_bg_pic", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_news_bg_pic", $fid, WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_news_desc", htmlspecialcharsEx(GetMessage("PHOENIX_NEWS_DESC")), WIZARD_SITE_ID);


	/*ACTIONS*/
	Option::set("concept.phoenix", "phoenix_act_more", htmlspecialcharsEx(GetMessage("PHOENIX_ACTION_MORE")), WIZARD_SITE_ID);

	//news background
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/offers.jpg");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_action_bg_pic", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_action_bg_pic", $fid, WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_action_desc", htmlspecialcharsEx(GetMessage("PHOENIX_ACTION_DESC")), WIZARD_SITE_ID);


	/*SEARCH*/
	Option::set("concept.phoenix", "phoenix_search_active", "Y", WIZARD_SITE_ID);
	

	Option::set("concept.phoenix", "phoenix_search_in_catalog", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_search_in_blog", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_search_in_news", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_search_in_actions", "Y", WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_search_show_in_catalog", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_search_show_in_actions", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_search_show_in_menu", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_search_show_in_menu_open", "Y", WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_search_quest_in_categories", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_search_quest_in_products", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_search_quest_in_brands", "Y", WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_search_hint_default", htmlspecialcharsEx(GetMessage("PHOENIX_SEARCH_HINT_DEFAULT")), WIZARD_SITE_ID);

	//search img
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/catalog.jpg");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_search_head_bg", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_search_head_bg", $fid, WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_fastsearch_active", "Y", WIZARD_SITE_ID);


	/*PERSONAL*/
	Option::set("concept.phoenix", "phoenix_personal_cabinet", "Y", WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_personal_section_orders", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_personal_section_private", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_personal_section_profile", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_personal_section_basket", "Y", WIZARD_SITE_ID);

    Option::set("concept.phoenix", "phoenix_personal_show_fix_price", "Y", WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_personal_form_subtitle", htmlspecialcharsEx(GetMessage("PHOENIX_PERSONAL_FORM_SUBTITLE")), WIZARD_SITE_ID);

	//personal img
	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/personal.jpg");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_personal_form_pic", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_personal_form_pic", $fid, WIZARD_SITE_ID);

	Option::set("concept.phoenix", "phoenix_personal_comment_orders", htmlspecialcharsEx(GetMessage("PHOENIX_PERSONAL_ORDERS")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_personal_comment_orders_history", htmlspecialcharsEx(GetMessage("PHOENIX_PERSONAL_HISTORY")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_personal_comment_private", htmlspecialcharsEx(GetMessage("PHOENIX_PERSONAL_PRIVATE")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_personal_fire_title", htmlspecialcharsEx(GetMessage("PHOENIX_PERSONAL_FIRE")), WIZARD_SITE_ID);


	/*REQUESTS*/
	Option::set("concept.phoenix", "phoenix_mail_from", "info@sendmail.com", WIZARD_SITE_ID);


	/*152-FZ*/
	Option::set("concept.phoenix", "phoenix_politic_desc", htmlspecialcharsEx(GetMessage("PHOENIX_POLITIC_DESC")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_politic_checked", "Y", WIZARD_SITE_ID);


	/*REGIONALITY*/
	Option::set("concept.phoenix", "phoenix_region_show_in_head", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_region_comment_for_form", htmlspecialcharsEx(GetMessage("PHOENIX_REGION_COMMENT_FOR_FORM")), WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_region_show_apply", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_region_comment_for_apply", htmlspecialcharsEx(GetMessage("PHOENIX_REGION_APPLY")), WIZARD_SITE_ID);

	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/city_big.jpg");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_region_bg", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_region_bg", $fid, WIZARD_SITE_ID);


	$arIMAGE = Array();

	$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/settings_images/city_small.jpg");
	$arIMAGE["old_file"] = Option::get("concept.phoenix", "img_phoenix_region_bg_small", false, WIZARD_SITE_ID);
	$arIMAGE["MODULE_ID"] = "concept.phoenix";

	$fid = CFile::SaveFile($arIMAGE, "phoenix");
	CFile::Delete($arIMAGE["old_file"]);
	$fid = intval($fid);
	Option::set("concept.phoenix", "img_phoenix_region_bg_small", $fid, WIZARD_SITE_ID);


	/*OTHERS*/
	Option::set("concept.phoenix", "phoenix_date_format", "j F Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_name_site", htmlspecialcharsEx(GetMessage("PHOENIX_SITE_NAME")), WIZARD_SITE_ID);
	/*Option::set("concept.phoenix", "phoenix_add_site_title", "Y", WIZARD_SITE_ID);*/
	Option::set("concept.phoenix", "phoenix_admin_edit_on", "Y", WIZARD_SITE_ID);

	/*Option::set("concept.phoenix", "phoenix_compress_html", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_delete_bx_tech_files", "Y", WIZARD_SITE_ID);
	Option::set("concept.phoenix", "phoenix_transfer_tech_files", "Y", WIZARD_SITE_ID);*/

}
?>