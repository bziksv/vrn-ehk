<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
set_time_limit(0);

if(!CModule::IncludeModule("iblock"))
    return;
    
//Landing List
$arLands = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_site_".WIZARD_SITE_ID);
$res = CIBlockSection::GetList(Array(), $arFilter, true);

while($ob = $res->GetNext())
    $arLands[$ob["XML_ID"]] = $ob["ID"];
    
    
//All Blocks List
$arBlocks = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_site_".WIZARD_SITE_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNext())
    $arBlocks[$ob["XML_ID"]] = $ob["ID"];
    

//All Forms List
$arForms = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_forms_".WIZARD_SITE_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNext())
    $arForms[$ob["XML_ID"]] = $ob["ID"]; 


//All Modals List
$arModals = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_modals_".WIZARD_SITE_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNext())
    $arModals[$ob["XML_ID"]] = $ob["ID"]; 


//All Advantages List
$arAdvs = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_advantages_".WIZARD_SITE_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNext())
    $arAdvs[$ob["XML_ID"]] = $ob["ID"]; 


//All Faq List
$arFAQ = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_faq_".WIZARD_SITE_ID);
$res = CIBlockSection::GetList(Array(), $arFilter, true);

while($ob = $res->GetNext())
    $arFAQ[$ob["XML_ID"]] = $ob["ID"];


//All Menu List
$arMenu = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_menu_".WIZARD_SITE_ID);
$res = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);

while($ob = $res->GetNext())
    $arMenu[$ob["XML_ID"]] = $ob["ID"];


//Al Footer Menu List
$arFooterMenu = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_footer_menu_".WIZARD_SITE_ID);
$res = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);

while($ob = $res->GetNext())
    $arFooterMenu[$ob["XML_ID"]] = $ob["ID"];


//All News List
$arNews = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_news_".WIZARD_SITE_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNext())
    $arNews[$ob["XML_ID"]] = $ob["ID"];
    

//All Blogs List
$arBlogs = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_blog_".WIZARD_SITE_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNext())
    $arBlogs[$ob["XML_ID"]] = $ob["ID"];


//All Actions List
$arActions = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_action_".WIZARD_SITE_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNext())
    $arActions[$ob["XML_ID"]] = $ob["ID"];


//All Banners List
$arBanners = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_banners_".WIZARD_SITE_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNext())
    $arBanners[$ob["XML_ID"]] = $ob["ID"];


//All Brands List
$arBrands = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_brand_".WIZARD_SITE_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNext())
    $arBrands[$ob["XML_ID"]] = $ob["ID"];


//All Catalog Pages List
$arCatalogPages = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_catalog_".WIZARD_SITE_ID);
$res = CIBlockSection::GetList(Array(), $arFilter, true);

while($ob = $res->GetNext())
    $arCatalogPages[$ob["XML_ID"]] = $ob["ID"];
    
//All Catalog Items List
$arCatalogItems = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_catalog_".WIZARD_SITE_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNext())
    $arCatalogItems[$ob["XML_ID"]] = $ob["ID"];


//All Catalog Offers Items List
$arCatalogOffersItems = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_catalog_offers_".WIZARD_SITE_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNext())
    $arCatalogOffersItems[$ob["XML_ID"]] = $ob["ID"];

//All Stuff List
$arStuff = Array();

$arSelect = Array("ID", "XML_ID");
$arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_employee_".WIZARD_SITE_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNext())
    $arStuff[$ob["XML_ID"]] = $ob["ID"];




/*main menu update*/
$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_2"]);
$bs->Update($arMenu["menu_1"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_2"]);
$bs->Update($arMenu["menu_1_1"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_6"]);
$bs->Update($arMenu["menu_1_3"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_3"]);
$bs->Update($arMenu["menu_1_4"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_4"]);
$bs->Update($arMenu["menu_1_5"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_5"]);
$bs->Update($arMenu["menu_1_6"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_7"]);
$bs->Update($arMenu["menu_2"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_10"]);
$bs->Update($arMenu["menu_2_1"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_11"]);
$bs->Update($arMenu["menu_2_2_2"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_10"]);
$bs->Update($arMenu["menu_2_2_3"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_12"]);
$bs->Update($arMenu["menu_2_3"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_8"]);
$bs->Update($arMenu["menu_2_4"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_9"]);
$bs->Update($arMenu["menu_2_5"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_13"]);
$bs->Update($arMenu["menu_2_6"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_23"]);
$bs->Update($arMenu["menu_4"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_14"]);
$bs->Update($arMenu["menu_7"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_15"]);
$bs->Update($arMenu["menu_8"], $arFields);


/*footer menu update*/
$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_1"]);
$bs->Update($arFooterMenu["footer_menu_1"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_2"]);
$bs->Update($arFooterMenu["footer_menu_2"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_23"]);
$bs->Update($arFooterMenu["footer_menu_3"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_7"]);
$bs->Update($arFooterMenu["footer_menu_9"], $arFields);

$bs = new CIBlockSection;
$arFields = Array("UF_PHX_LAND" => $arLands["page_14"]);
$bs->Update($arFooterMenu["footer_menu_10"], $arFields);


/*catalog offers*/
$arCatalog = Array(
    "catalog_2_2_2" => Array(
        "sku_1",
        "sku_2",
        "sku_3",
        "sku_4",
        "sku_5",
        "sku_6",
        "sku_7",
        "sku_8",
        "sku_9",
    ),
    "catalog_2_5_1" => Array(
        "sku_12",
        "sku_13",
        "sku_14",
    ),
    "catalog_9_1_3" => Array(
        "sku_49",
        "sku_50",
        "sku_51",
        "sku_52",
        "sku_53",
        "sku_54",
        "sku_55",
        "sku_56",
    ),
    "catalog_9_1_1" => Array(
        "sku_15",
        "sku_16",
        "sku_17",
        "sku_18",
        "sku_19",
        "sku_20",
        "sku_21",
        "sku_22",
        "sku_23",
        "sku_24",
        "sku_25",
        "sku_26",
        "sku_27",
    ),
    "catalog_9_1_7" => Array(
        "sku_28",
        "sku_29",
        "sku_30",
        "sku_31",
        "sku_32",
        "sku_33",
        "sku_34",
        "sku_35",
        "sku_36",
        "sku_37",
    ),
    "catalog_9_1_8" => Array(
        "sku_38",
        "sku_39",
        "sku_40",
        "sku_41",
    ),
    "catalog_9_1_4" => Array(
        "sku_44",
        "sku_45",
        "sku_46",
    ),
    "catalog_9_1_6" => Array(
        "sku_47",
        "sku_48",
    ),
    "catalog_9_1_5" => Array(
        "sku_57",
    ),
    "catalog_9_4_2" => Array(
        "sku_42",
        "sku_43",
    ),
    "catalog_1_1_8" => Array(
        "sku_10",
        "sku_11",
    ),
);

foreach($arCatalog as $key => $arSKU) 
{
    foreach($arSKU as $sku)
        CIBlockElement::SetPropertyValuesEx($arCatalogOffersItems[$sku], false, array("CML2_LINK" => $arCatalogItems[$key]));
}

/*banners*/
CIBlockElement::SetPropertyValuesEx($arBanners["banner_1"], false, array("BANNER_BTN_FORM" => $arForms["forms_2_2"]));
CIBlockElement::SetPropertyValuesEx($arBanners["banner_2"], false, array("BANNER_BTN_FORM" => $arForms["forms_2_5"]));

/*news*/
CIBlockElement::SetPropertyValuesEx($arNews["news_2_1"], false, array("CHOOSE_LANDING" => $arLands["page_24"]));

/*specials*/
CIBlockElement::SetPropertyValuesEx($arActions["action_3"], false, array("CHOOSE_LANDING" => $arLands["page_18"]));
CIBlockElement::SetPropertyValuesEx($arActions["action_4"], false, array("CHOOSE_LANDING" => $arLands["page_24"]));

/*blog*/
CIBlockElement::SetPropertyValuesEx($arBlogs["blog_2_3"], false, array("CHOOSE_LANDING" => $arLands["page_19"]));
CIBlockElement::SetPropertyValuesEx($arBlogs["blog_1_2"], false, array("CHOOSE_LANDING" => $arLands["page_21"]));
CIBlockElement::SetPropertyValuesEx($arBlogs["blog_1_1"], false, array("CHOOSE_LANDING" => $arLands["page_16"]));
CIBlockElement::SetPropertyValuesEx($arBlogs["blog_1_3"], false, array("CHOOSE_LANDING" => $arLands["page_22"]));
CIBlockElement::SetPropertyValuesEx($arBlogs["blog_2_4"], false, array("CHOOSE_LANDING" => $arLands["page_20"]));


/*stuff*/
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_1_1"], false, array("EMPL_FORMS" => $arForms["forms_1_6"]));
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_1_2"], false, array("EMPL_FORMS" => $arForms["forms_1_6"]));
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_1_4"], false, array("EMPL_FORMS" => $arForms["forms_1_6"]));
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_1_7"], false, array("EMPL_FORMS" => $arForms["forms_1_6"]));
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_2_1"], false, array("EMPL_FORMS" => $arForms["forms_1_6"]));
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_2_2"], false, array("EMPL_FORMS" => $arForms["forms_1_6"]));
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_2_4"], false, array("EMPL_FORMS" => $arForms["forms_1_6"]));
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_3_4"], false, array("EMPL_FORMS" => $arForms["forms_1_6"]));

CIBlockElement::SetPropertyValuesEx($arStuff["stuff_3_1"], false, array("EMPL_FORMS" => $arForms["forms_1_5"]));
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_3_2"], false, array("EMPL_FORMS" => $arForms["forms_1_5"]));
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_3_3"], false, array("EMPL_FORMS" => $arForms["forms_1_5"]));


CIBlockElement::SetPropertyValuesEx($arStuff["stuff_3_5"], false, array("EMPL_FORMS" => $arForms["forms_2_2"]));
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_5_1"], false, array("EMPL_FORMS" => $arForms["forms_2_10"]));
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_5_2"], false, array("EMPL_FORMS" => $arForms["forms_2_12"]));
CIBlockElement::SetPropertyValuesEx($arStuff["stuff_4_1"], false, array("EMPL_FORMS" => $arForms["forms_1_2"]));



/*catalog banners*/
$bs = new CIBlockSection;
$arFields = Array("UF_PHX_CTLG_BNNRS" => Array($arBanners["banner_2"], $arBanners["banner_3"]));
$bs->Update($arCatalogPages["catalog_2"], $arFields);

/*catalog brands*/
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_3"], false, array("BRAND" => $arBrands["brands_1_2"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_8"], false, array("BRAND" => $arBrands["brands_1_2"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_5"], false, array("BRAND" => $arBrands["brands_1_2"]));

CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_1_1"], false, array("BRAND" => $arBrands["brands_0_1"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_5_1"], false, array("BRAND" => $arBrands["brands_0_1"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_2_2"], false, array("BRAND" => $arBrands["brands_0_1"]));

CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_1"], false, array("BRAND" => $arBrands["brands_1_1"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_7"], false, array("BRAND" => $arBrands["brands_1_1"]));

CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_4"], false, array("BRAND" => $arBrands["brands_1_3"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_6"], false, array("BRAND" => $arBrands["brands_1_3"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_2"], false, array("BRAND" => $arBrands["brands_1_3"]));


/*items*/
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_2_2"], false, array("FAQ_FORM" => $arForms["forms_2_10"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_2_2"], false, array("FAQ_ELEMENTS" => $arFAQ["faq_2"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_2_2"], false, array("BLOGS" => $arBlogs["blog_2_3"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_2_2"], false, array("NEWS" => $arNews["news_2_1"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_2_2"], false, array("SPECIALS" => $arActions["action_3"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_2_2"], false, array("ADVANTAGES" => Array($arAdvs["adv_sect_1_1"], $arAdvs["adv_sect_1_2"], $arAdvs["adv_sect_1_3"], $arAdvs["adv_sect_1_6"], $arAdvs["adv_sect_1_4"], $arAdvs["adv_sect_2_1"])));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_2_2"], false, array("ACCESSORY" => Array($arCatalogItems["catalog_2_3_1"], $arCatalogItems["catalog_2_3_4"])));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_2_2"], false, array("STUFF" => $arStuff["stuff_5_1"]));


CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_3"], false, array("STUFF" => $arStuff["stuff_4_1"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_4_2"], false, array("STUFF" => $arStuff["stuff_4_1"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_4_3"], false, array("STUFF" => $arStuff["stuff_4_1"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_4_5"], false, array("STUFF" => $arStuff["stuff_4_1"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_4_7"], false, array("STUFF" => $arStuff["stuff_4_1"]));

CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_1"], false, array("FAQ_FORM" => $arForms["forms_1_2"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_1"], false, array("FAQ_ELEMENTS" => $arFAQ["faq_3"]));

CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_7"], false, array("STUFF" => $arStuff["stuff_4_1"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_8"], false, array("STUFF" => $arStuff["stuff_4_1"]));

CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_4"], false, array("FAQ_FORM" => $arForms["forms_1_2"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_4"], false, array("FAQ_ELEMENTS" => $arFAQ["faq_3"]));

CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_6"], false, array("STUFF" => $arStuff["stuff_4_1"]));

CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_2"], false, array("FAQ_FORM" => $arForms["forms_1_2"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_2"], false, array("FAQ_ELEMENTS" => $arFAQ["faq_3"]));

CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_5"], false, array("FAQ_FORM" => $arForms["forms_1_2"]));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_5"], false, array("FAQ_ELEMENTS" => $arFAQ["faq_3"]));


CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_1_1"], false, array("MODAL_WINDOWS" => Array($arModals["modal_2"])));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_2_1_1"], false, array("FORMS" => Array($arForms["forms_2_2"])));

CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_3"], false, array("MODAL_WINDOWS" => Array($arModals["modal_4"])));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_1"], false, array("MODAL_WINDOWS" => Array($arModals["modal_4"])));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_7"], false, array("MODAL_WINDOWS" => Array($arModals["modal_4"])));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_8"], false, array("MODAL_WINDOWS" => Array($arModals["modal_4"])));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_4"], false, array("MODAL_WINDOWS" => Array($arModals["modal_4"])));
CIBlockElement::SetPropertyValuesEx($arCatalogItems["catalog_9_1_2"], false, array("MODAL_WINDOWS" => Array($arModals["modal_4"])));


CIBlockElement::SetPropertyValuesEx($arBlogs["blog_3_1"], false, array("BUTTON_FORM" => Array($arForms["forms_1_4"])));




/*main_page*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_3"], false, array("BLINK_BUTTON_LINK" => WIZARD_SITE_DIR."catalog/tekhnika-apple/"));    
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_4"], false, array("BLINK_BUTTON_LINK" => WIZARD_SITE_DIR."catalog/bikes/"));    
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_5"], false, array("BLINK_BUTTON_LINK" => WIZARD_SITE_DIR."catalog/mebel/"));    
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_6"], false, array("BLINK_BUTTON_LINK" => WIZARD_SITE_DIR."catalog/videonablyudenie/"));    
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_7"], false, array("BLINK_BUTTON_LINK" => WIZARD_SITE_DIR."catalog/elektroinstrument/"));    
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_8"], false, array("BLINK_BUTTON_LINK" => WIZARD_SITE_DIR."catalog/"));    

CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_10"], false, array("BUTTON_LAND" => $arLands["page_7"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_11"], false, array("BUTTON_LAND" => $arLands["page_23"])); 


CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_12"], false, array("BUTTON_LINK" => WIZARD_SITE_DIR."offers/")); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_12"], false, array("CATALOG_LABELS_SECTIONS" => $arCatalogPages)); 

CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_13"], false, array("BUTTON_LINK" => WIZARD_SITE_DIR."brands/")); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_13"], false, array("PARTNERS_LINKS" => Array(WIZARD_SITE_DIR."brands/apple/"))); 

CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_14"], false, array("BUTTON_LINK" => WIZARD_SITE_DIR."blog/"));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_14"], false, array("NEWS_ELEMENTS_BLOG" => $arBlogs)); 

CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_15"], false, array("BLINK_BUTTON_LINK" => WIZARD_SITE_DIR."personal/register/")); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_16"], false, array("BUTTON_LINK" => WIZARD_SITE_DIR."personal/register/")); 

CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_17"], false, array("BUTTON_LAND" => $arLands["page_2"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_17"], false, array("BUTTON_LAND_2" => $arLands["page_23"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_17"], false, array("MAP_ADVS" => Array($arAdvs["adv_sect_1_1"], $arAdvs["adv_sect_1_3"], $arAdvs["adv_sect_1_6"], $arAdvs["adv_sect_1_4"], $arAdvs["adv_sect_2_1"])));

CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_9"], false, array("BUTTON_LINK" => WIZARD_SITE_DIR."news/"));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_1_9"], false, array("NEWS_ELEMENTS_NEWS" => $arNews)); 


/*about*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_2_3"], false, array("NEWS_ELEMENTS_NEWS" => Array($arNews["news_4_1"], $arNews["news_4_2"], $arNews["news_4_3"], $arNews["news_4_4"], $arNews["news_4_5"], $arNews["news_4_6"],))); 

CIBlockElement::SetPropertyValuesEx($arBlocks["page_2_8"], false, array("BLINK_BTN_LAND" => $arLands["page_6"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_2_9"], false, array("BLINK_BTN_LAND" => $arLands["page_4"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_2_10"], false, array("BLINK_BTN_LAND" => $arLands["page_5"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_2_11"], false, array("BLINK_BTN_LAND" => $arLands["page_3"])); 

/*documents*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_3_4"], false, array("BLINK_BTN_LAND" => $arLands["page_2"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_3_5"], false, array("BLINK_BTN_LAND" => $arLands["page_4"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_3_6"], false, array("BLINK_BTN_LAND" => $arLands["page_5"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_3_7"], false, array("BLINK_BTN_LAND" => $arLands["page_6"]));

/*feedback*/
$bs = new CIBlockSection;
$arFields = Array("UF_PHX_BANNERS" => Array($arBanners["banner_2"]));
$bs->Update($arLands["page_4"], $arFields);

CIBlockElement::SetPropertyValuesEx($arBlocks["page_4_3"], false, array("BLINK_BUTTON_FORM" => $arForms["forms_2_5"]));

CIBlockElement::SetPropertyValuesEx($arBlocks["page_4_9"], false, array("BLINK_BTN_LAND" => $arLands["page_2"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_4_10"], false, array("BLINK_BTN_LAND" => $arLands["page_6"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_4_11"], false, array("BLINK_BTN_LAND" => $arLands["page_5"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_4_12"], false, array("BLINK_BTN_LAND" => $arLands["page_3"]));


/*partners*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_5_4"], false, array("BLINK_BTN_LAND" => $arLands["page_2"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_5_5"], false, array("BLINK_BTN_LAND" => $arLands["page_4"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_5_6"], false, array("BLINK_BTN_LAND" => $arLands["page_5"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_5_7"], false, array("BLINK_BTN_LAND" => $arLands["page_3"]));


/*team*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_6_2"], false, array("EMPL" => Array($arStuff["stuff_3_1"], $arStuff["stuff_3_2"], $arStuff["stuff_3_3"], $arStuff["stuff_3_5"])));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_6_3"], false, array("EMPL" => Array($arStuff["stuff_3_4"])));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_6_4"], false, array("EMPL" => Array($arStuff["stuff_1_1"], $arStuff["stuff_1_2"], $arStuff["stuff_1_3"], $arStuff["stuff_1_4"], $arStuff["stuff_1_6"], $arStuff["stuff_3_3"], $arStuff["stuff_1_5"], $arStuff["stuff_3_2"])));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_6_5"], false, array("EMPL" => Array($arStuff["stuff_1_7"])));

CIBlockElement::SetPropertyValuesEx($arBlocks["page_6_8"], false, array("BLINK_BTN_LAND" => $arLands["page_2"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_6_9"], false, array("BLINK_BTN_LAND" => $arLands["page_4"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_6_10"], false, array("BLINK_BTN_LAND" => $arLands["page_5"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_6_11"], false, array("BLINK_BTN_LAND" => $arLands["page_3"]));


/*services*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_7_2"], false, array("BLINK_BTN_LAND" => $arLands["page_8"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_7_3"], false, array("BLINK_BTN_LAND" => $arLands["page_12"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_7_4"], false, array("BLINK_BTN_LAND" => $arLands["page_10"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_7_5"], false, array("BLINK_BTN_LAND" => $arLands["page_11"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_7_6"], false, array("BLINK_BTN_LAND" => $arLands["page_9"])); 

/*cctv*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_8_1"], false, array("FB_LB_FORM" => $arForms["forms_1_4"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_8_1_1"], false, array("FB_LB_FORM" => $arForms["forms_1_4"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_8_3"], false, array("BUTTON_FORM" => $arForms["forms_2_12"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_8_4"], false, array("BUTTON_FORM" => $arForms["forms_1_4"]));

/*photo*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_9_4"], false, array("BUTTON_FORM" => $arForms["forms_2_3"]));

CIBlockElement::SetPropertyValuesEx($arBlocks["page_9_6"], false, array("TARIFF_BUTTON_FORM" => $arForms["forms_2_4"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_9_7"], false, array("TARIFF_BUTTON_FORM" => $arForms["forms_2_4"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_9_8"], false, array("TARIFF_BUTTON_FORM" => $arForms["forms_2_4"]));


/*landing*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_1"], false, array("FB_LB_LINK" => WIZARD_SITE_DIR."portfolio/"));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_6"], false, array("BUTTON_FORM" => $arForms["forms_2_8"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_15"], false, array("BUTTON_LINK" => WIZARD_SITE_DIR."portfolio/"));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_17"], false, array("BLINK_BUTTON_FORM" => $arForms["forms_2_6"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_18"], false, array("BLINK_BUTTON_MODAL" => $arModals["modal_3"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_19"], false, array("BLINK_BUTTON_LINK" => WIZARD_SITE_DIR."feedback/")); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_29"], false, array("CATALOG" => Array($arCatalogItems["catalog_2_1_1"], $arCatalogItems["catalog_2_1_3"], $arCatalogItems["catalog_2_2_1"], $arCatalogItems["catalog_2_2_2"], $arCatalogItems["catalog_2_5_1"], $arCatalogItems["catalog_2_3_1"], $arCatalogItems["catalog_2_3_4"], $arCatalogItems["catalog_9_1_8"])));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_32"], false, array("NEWS_ELEMENTS_BLOG" => $arBlogs, "NEWS_ELEMENTS_NEWS" => $arNews, "NEWS_ELEMENTS_ACTION" => $arActions)); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_33"], false, array("TARIFF_BUTTON_FORM" => $arForms["forms_2_7"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_34"], false, array("TARIFF_BUTTON_FORM" => $arForms["forms_2_7"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_35"], false, array("TARIFF_BUTTON_FORM" => $arForms["forms_2_7"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_36"], false, array("TARIFF_BUTTON_FORM" => $arForms["forms_2_1"]));

CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_37"], false, array("FAQ_ELEMENTS" => $arFAQ["faq_1"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_10_37"], false, array("BUTTON_FORM" => $arForms["forms_1_2"]));


/*quiz*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_11_8"], false, array("TARIFF_BUTTON_FORM" => $arForms["forms_1_4"]));


/*remont*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_12_5"], false, array("BLINK_BTN_LAND" => $arLands["page_4"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_12_8"], false, array("EMPL" => Array($arStuff["stuff_2_1"], $arStuff["stuff_2_2"], $arStuff["stuff_2_3"], $arStuff["stuff_2_4"])));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_12_10"], false, array("TARIFF_BUTTON_FORM" => $arForms["forms_2_1"]));


/*portfolio*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_13_2"], false, array("NEWS_ELEMENTS_BLOG" => Array($arBlogs["blog_1_1"], $arBlogs["blog_1_2"], $arBlogs["blog_1_3"])));


/*delivery*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_23_3"], false, array("BUTTON_FORM" => $arForms["forms_2_2"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_23_6"], false, array("BUTTON_LAND" => $arLands["page_7"])); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_23_6_slavuy"], false, array("BUTTON_LAND" => $arLands["page_7"])); 

/*video*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_16_1"], false, array("FB_LB_FORM" => $arForms["forms_1_4"])); 

/*iPhone X*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_19_1"], false, array("BUTTON_LINK" => WIZARD_SITE_DIR."catalog/iphone/")); 
CIBlockElement::SetPropertyValuesEx($arBlocks["page_19_9"], false, array("CATALOG_BIG_ITEMS" => Array($arCatalogItems["catalog_2_2_1"], $arCatalogItems["catalog_2_2_2"])));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_19_9"], false, array("BUTTON_LINK" => WIZARD_SITE_DIR."catalog/iphone/")); 

/*update*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_20_3"], false, array("BLINK_BUTTON_FORM" => $arForms["forms_2_6"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_20_4"], false, array("BLINK_BUTTON_MODAL" => $arModals["modal_3"]));

/*house*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_18_1"], false, array("FB_LB_FORM" => $arForms["forms_1_4"])); 

/*contacts*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_14_4"], false, array("EMPL" => $arStuff["stuff_3_5"]));

/*bycicles*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_24_2"], false, array("CATALOG_BIG_ITEMS" => Array($arCatalogItems["catalog_9_1_7"], $arCatalogItems["catalog_9_1_3"], $arCatalogItems["catalog_9_1_2"], $arCatalogItems["catalog_9_1_8"])));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_24_3"], false, array("CATALOG" => Array($arCatalogItems["catalog_9_4_1"], $arCatalogItems["catalog_9_4_2"], $arCatalogItems["catalog_9_4_3"], $arCatalogItems["catalog_9_4_4"], $arCatalogItems["catalog_9_4_5"], $arCatalogItems["catalog_9_4_6"], $arCatalogItems["catalog_9_4_7"], $arCatalogItems["catalog_9_4_8"])));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_24_4"], false, array("BUTTON_FORM" => $arForms["forms_1_6"]));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_24_4"], false, array("FAQ_ELEMENTS" => $arFAQ["faq_3"]));

/*iphone*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_17_1"], false, array("FB_LB_LINK" => WIZARD_SITE_DIR."catalog/iphone/"));
CIBlockElement::SetPropertyValuesEx($arBlocks["page_17_9"], false, array("BUTTON_LINK" => WIZARD_SITE_DIR."catalog/aksessuary/")); 

/*fire offers*/
CIBlockElement::SetPropertyValuesEx($arBlocks["page_21_2"], false, array("CATALOG_BIG_ITEMS" => Array($arCatalogItems["catalog_2_1_1"], $arCatalogItems["catalog_2_1_3"], $arCatalogItems["catalog_2_2_1"], $arCatalogItems["catalog_2_2_2"], $arCatalogItems["catalog_2_5_1"], $arCatalogItems["catalog_2_3_1"], $arCatalogItems["catalog_2_3_4"])));

?>