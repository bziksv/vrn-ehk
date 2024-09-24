<?
if(!empty($arItem["PROPERTIES"]["CATALOG"]["VALUE"]) )
{

      $GLOBALS['arFilterConstructorCatalog'] = array("ID" => $arItem["PROPERTIES"]["CATALOG"]["VALUE"]);
      
      CPhoenixFunc::setInitialFilterParams('arFilterConstructorCatalog');

     

      if($arItem["PROPERTIES"]["CATALOG_CURR_VAL"]["VALUE"] && $arItem["PROPERTIES"]["CATALOG_CURR_ON"]["VALUE_XML_ID"] == "Y")
            $currId = $arItem["PROPERTIES"]["CATALOG_CURR_VAL"]["VALUE"];

      else
            $currId = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'];
      


      if(!strlen($arItem["PROPERTIES"]["CATALOG_COUNT"]["VALUE"]))
            $arItem["PROPERTIES"]["CATALOG_COUNT"]["VALUE"] = 8;


      $intSectionID = $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "main",
        array(
            "MAX_VISIBLE_ITEMS" => $arItem["PROPERTIES"]["CATALOG_COUNT"]["VALUE"],
            "MAX_VISIBLE_ITEMS_BTN_NAME" => $arItem["PROPERTIES"]["CATALOG_COUNT_NAME"]["VALUE"],
            "PAGE_ELEMENT_COUNT" => "100",
            'CURRENCY_ID' => $currId,
            'CONVERT_CURRENCY' => 

                  ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"] == "Y" || $arItem["PROPERTIES"]["CATALOG_CURR_ON"]["VALUE_XML_ID"] == "Y")? "Y":"",


            "PAGER_TEMPLATE" => "phoenix_round",
            "FILTER_NAME" => "arFilterConstructorCatalog",
            "VIEW" => "FLAT",
            "COLS" => $show_menu ? '3' : '4',
            "COLS_LG" => $show_menu ? '2' : '3',
            "ELEMENT_SORT_FIELD" => "SORT",
            "ELEMENT_SORT_ORDER" => "ASC",
            "ELEMENT_SORT_FIELD2" => "ID",
            "ELEMENT_SORT_ORDER2" => "ASC",
            "OBJ_NAME" => $arItem["ID"],
            "ACTIVE_TAB" => $activeTAB,
            'HIDE_NOT_AVAILABLE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"],
            'HIDE_NOT_AVAILABLE_OFFERS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"],
            "COMPONENT_TEMPLATE" => "main",
            "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_TYPE"],
            "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
            "PRICE_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
            "OFFERS_CART_PROPERTIES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
            "OFFERS_PROPERTY_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
            'OFFER_TREE_PROPS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
            "OFFERS_FIELD_CODE" => array("NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE",""),

            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
            "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
            
            "OFFERS_SORT_FIELD" => "sort",
            "OFFERS_SORT_ORDER" => "id",
            "OFFERS_SORT_FIELD2" => "asc",
            "OFFERS_SORT_ORDER2" => "asc",
            "OFFERS_LIMIT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["OFFERS_QUANTITY"]["VALUE_RESULT"],

            "USE_PRICE_COUNT" => "Y",
            "USE_PRODUCT_QUANTITY" => "Y",
            "SHOW_PRICE_COUNT" => "1",
            'SHOW_OLD_PRICE' => "Y",
            'SHOW_MAX_QUANTITY' => "Y",
            "PRICE_VAT_INCLUDE" => "Y",
            'SHOW_DISCOUNT_PERCENT' => "Y",

            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "CACHE_TYPE" => "A",
            "SET_LAST_MODIFIED" => "N",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" =>  "N",
            "PAGER_SHOW_ALL" => "N",
            "CACHE_FILTER" => "Y",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PAGER_SHOW_ALWAYS" => "N",
            
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "LAZY_LOAD" => "N",
            "LOAD_ON_SCROLL" => "N",
            "USE_MAIN_ELEMENT_SECTION" => "N",
            'PRODUCT_DISPLAY_MODE' => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            'PRODUCT_SUBSCRIPTION' => "N",
            'ENLARGE_PRODUCT' => "STRICT",
            'COMPARE_NAME' => "CATALOG_COMPARE_LIST",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "CACHE_TIME" => "36000000",
            "INCLUDE_SUBSECTIONS" => "Y",
            "SHOW_ALL_WO_SECTION" => "Y",
            "CACHE_GROUPS" => "Y",
            "SET_TITLE" => "N",
            "SET_STATUS_404" => "N",
            "SHOW_404" => "N",
            'ADD_PICT_PROP' => "-",
            'OFFER_ADD_PICT_PROP' => "-",
            "META_KEYWORDS" => "-",
            "META_DESCRIPTION" => "-",
            "BROWSER_TITLE" => "-",
            'USE_ENHANCED_ECOMMERCE' => '',
            'ADD_TO_BASKET_ACTION' => "",
            'SHOW_CLOSE_POPUP' => "",
            'COMPARE_PATH' => "",
            'DISCOUNT_PERCENT_POSITION' => '',
            'ENLARGE_PROP' => '',
            'SHOW_SLIDER' => "",
            'SLIDER_INTERVAL' => '',
            'SLIDER_PROGRESS' => '',
            'PRODUCT_BLOCKS_ORDER' => "",
            'PRODUCT_ROW_VARIANTS' => "",
            "SECTION_ID" => "",
            "SECTION_CODE" => "",
            "PRODUCT_PROPERTIES" => "",
            "PAGER_TITLE" => "",
            "CUSTOM_FILTER" => "",
            "FILE_404" => "",
            "LINE_ELEMENT_COUNT" => "",
            "BASKET_URL" => "",
            "PROPERTY_CODE" => array(),
            "PROPERTY_CODE_MOBILE" => array(),
            'LABEL_PROP' => array(),
            "COMPOSITE_FRAME_MODE" => "N",
            
        ),
        $component
    );
}
