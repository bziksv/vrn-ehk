<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("area");?>

<?global $PHOENIX_TEMPLATE_ARRAY;?>
<?
    if(!empty($arResult["CATALOG"]["ITEMS"]))
    {?>

        <?if(strlen($arResult["CATALOG"]["TITLE"])>0):?>
            <div class="text-content">
                <h2><?=$arResult["CATALOG"]["TITLE"]?></h2>
            </div>
        <?endif;?>
        
        <?
            $GLOBALS['arFilterDetailNewsCatalog'] = array("ID" => $arResult["CATALOG"]["ITEMS"]);

            CPhoenixFunc::setInitialFilterParams('arFilterDetailNewsCatalog');



          $intSectionID = $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "main",
            array(
                'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
                'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
                "PAGER_TEMPLATE" => "phoenix_round",
                "FILTER_NAME" => "arFilterDetailNewsCatalog",
                "VIEW" => "FLAT",
                "COLS" =>  '3',
                "COLS_LG" => '2',
                "ELEMENT_SORT_FIELD" => "SORT",
                "ELEMENT_SORT_ORDER" => "ASC",
                "ELEMENT_SORT_FIELD2" => "ID",
                "ELEMENT_SORT_ORDER2" => "ASC",
                "OBJ_NAME" => $arItem["ID"],
                "ACTIVE_TAB" => $activeTAB,
                "HIDE_NOT_AVAILABLE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"],
                'HIDE_NOT_AVAILABLE_OFFERS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"],
                "COMPONENT_TEMPLATE" => "main",
                "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_TYPE"],
                "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
                "PRICE_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
                "OFFERS_CART_PROPERTIES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                "OFFERS_PROPERTY_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                'OFFER_TREE_PROPS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                "OFFERS_FIELD_CODE" => array("NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE",""),

                "SECTION_URL" => "",
                "DETAIL_URL" => "",
                
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
                
                "SET_LAST_MODIFIED" => "N",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" =>  "N",
                "PAGER_SHOW_ALL" => "N",
                "CACHE_FILTER" => "N",
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
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
                
                "INCLUDE_SUBSECTIONS" => "Y",
                "SHOW_ALL_WO_SECTION" => "Y",
                "SET_TITLE" => "Y",
                "SET_STATUS_404" => "Y",
                "SHOW_404" => "Y",
                'ADD_PICT_PROP' => "-",
                'OFFER_ADD_PICT_PROP' => "-",
                "META_KEYWORDS" => "-",
                "META_DESCRIPTION" => "-",
                "BROWSER_TITLE" => "-",
                "PAGE_ELEMENT_COUNT" => "48",
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
    ?>



<?

if(strlen($GLOBALS["TITLE"]) <= 0)
    $GLOBALS["TITLE"] = $arResult["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"];

if(strlen($GLOBALS["KEYWORDS"]) <= 0)
    $GLOBALS["KEYWORDS"] = $arResult["IPROPERTY_VALUES"]["ELEMENT_META_KEYWORDS"];

if(strlen($GLOBALS["DESCRIPTION"]) <= 0)
    $GLOBALS["DESCRIPTION"] = $arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"];

if(strlen($GLOBALS["H1"]) <= 0)
    $GLOBALS["H1"] = $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"];
     
    
?>
<script>
	
	$(document).ready(function($) {
		
		<?foreach ($arResult["SECTIONS_ID"] as $value):?>

			$(".section-menu-id-<?=$value?>").addClass('selected');
			
		<?endforeach;?>

	});

</script>
<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("area");?>
</div><?//end block from template.php?>