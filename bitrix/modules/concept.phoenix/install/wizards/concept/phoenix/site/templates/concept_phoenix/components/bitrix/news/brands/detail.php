<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
global $PHOENIX_TEMPLATE_ARRAY;

$domenUrlForCookie = CPhoenixHost::getHostTranslit();
CPhoenixFunc::setInitialFilterParams("arrBrandFilter");
?>
<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest" && isset($_GET["ajax_get_filter"]) && $_GET["ajax_get_filter"] == "Y" ){
    $isAjaxFilter="Y";
}?>

<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES_MODE"]["VALUE"] == "custom" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"]):?>
    <?
        $img = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"], array('width'=>800, 'height'=>900), BX_RESIZE_IMAGE_PROPORTIONAL, false);  
        $header_back_xs = $img["src"];
    ?>
    <style>
        @media (max-width: 767.98px){
            div.page-header{
                background-image: url('<?=$header_back_xs?>') !important;
            }
        }
    </style>
<?endif;?>



<div class=
        "
            page-header
            section
            section-catalog
            padding-bottom-section
            cover
            <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>
            phoenix-firsttype-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
            <?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES_MODE"]["VALUE"] == "custom" && !$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"]) ? "def-bg-xs" : "";?>
        "

    <?$APPLICATION->ShowViewContent('brand-headbg');?>

    
>

    <div class="board-shadow-tone <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>"></div>

    <div class="top-shadow"></div>

    <div class="container z-i-9">

        <div class="row">
            
            <div class="col-md-7 col-12 part part-left">
                
                <div class="head margin-bottom">

                    <div class="d-md-none">

                        <?
                            $APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumbs", Array(
                                    "COMPONENT_TEMPLATE" => ".default",
                                    "START_FROM" => "0",
                                    "PATH" => "",
                                    "SITE_ID" => SITE_ID,
                                    "COMPOSITE_FRAME_MODE" => "N",
                                ),
                                $component
                            );
                        ?>
                    </div>

                    <div class="title main1"><h1><?$APPLICATION->ShowTitle(false);?></h1></div>

                    <div class="d-md-none">
                        <?$APPLICATION->ShowViewContent('brand-img');?>
                    </div>

                    <?$APPLICATION->ShowViewContent('detail-preview-text');?>
                    

                </div>
            </div>

            <div class="col-md-5 col-12 part part-right d-none d-sm-block"><?$APPLICATION->ShowViewContent('brand-img');?></div>

        </div>

    </div>


</div>

<div class="catalog-list-wrap page_pad_bot brand-page parent-hide-column d-none" id="catalog_brand">

    <div class="container">

        <div class="block-move-to-up">

            <div class="section-control-view hidden-md hidden-sm hidden-xs">

                <div class="row align-items-center padding-for-actionbox brand-goods-isset-js" id="actionbox">

                    <div class="col-xl-3 col-lg-4 col-12">
                        <div class="control-column column-1">
                            <div class="brand-name bold"><?$APPLICATION->ShowViewContent('detail-brand-name');?></div>
                        </div>

                    </div>

                    <div class="col-xl-9 col-lg-8 col-12">
                        <div class="row">
                            <div class="col-xl-10 col-md-9 col-8 column-2">
                                <?include("sort.php");?>

                                <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_IN_STOCK']['VALUE']['ACTIVE'] == "Y"):?>
                                    <div class="available-wrapper <?$APPLICATION->ShowViewContent('available-show');?>">

                                        <?
                                            $available = "Y";
                                            
                                            if($_REQUEST["available"] == "Y")
                                            {
                                                $available = $_REQUEST["available"];

                                                if($available == "Y")
                                                    $available = "N";

                                                else
                                                    $available = "Y";
                                            }
                                        ?>

                                        <a 
                                            class="checkbox-available sort-available-js <?= ($available == "N") ? 'active' : '' ;?>" data-available = "<?=$available?>">
                                            <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FILTER_AVAILABLE"]?>
                                                
                                        </a>
                                    </div>
                                <?endif;?>
                            </div>

                            <div class="col-xl-2 col-md-3 col-4 column-3 hidden-sm hidden-xs">
                                <?include("view.php");?>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="position-relative">

                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-10 col-9 left-side position-static wr-filter-side brand-goods-isset-xs-js">
                        <div class="side-inner">

                            <?$APPLICATION->ShowViewContent('filter_content');?>


                            <div class="hidden-md hidden-sm hidden-xs menu-navigation no-padding-top static">

                                <div class="menu-navigation-wrap">
                                    <div class="menu-navigation-inner" id="navigation">

                                        <div class="row">
                                            <ul class='nav'>
                                
                                                <li class="col-12 back">
                                                    <a href="<?=$arResult["FOLDER"]?>"><span class="text back-ic"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BRANDS_LEVEL"]?></span></a>
                                                </li>
                                                
                                            </ul>
                                        </div>
                                    </div>
                                
                                </div>
                                <?$APPLICATION->ShowViewContent('brand-sidemenu');?>
                            </div>



                        </div>
                    </div>

                    <div class="col-md-2 col-3 d-lg-none wr-sort-btn-side brand-goods-isset-xs-js">

                        <div class="btn-show-sort-board">
                            <div class="sort-dialog-content">
                                <?include("sort.php");?>

                                <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_IN_STOCK']['VALUE']['ACTIVE'] == "Y"):?>

                                    <div class="available-wrapper <?$APPLICATION->ShowViewContent('available-show');?>">

                                        <?
                                            $available = "Y";
                                            
                                            if(strlen($_REQUEST["available"]) > 0)
                                            {
                                                $available = $_REQUEST["available"];

                                                if($available == "Y")
                                                    $available = "N";

                                                else
                                                    $available = "Y";
                                            }
                                            
                                            if($available == "N")
                                                $GLOBALS["arrBrandFilter"][">CATALOG_QUANTITY"] = 0;

                                        ?>

                                        <a 
                                            class="checkbox-available sort-available-js <?= ($available == "N") ? 'active' : '' ;?>" data-available = "<?=$available?>">
                                            <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FILTER_AVAILABLE"]?>
                                                
                                        </a>
                                    </div>
                                <?endif;?>



                            </div>
                        </div>
                        
                    </div>

                    <div class="col-xl-9 col-lg-8 col-12 main-container">

                        <div class="row">

                            <?
                                $ElementID = $APPLICATION->IncludeComponent(
                                    "bitrix:news.detail",
                                    "",
                                    Array(
                                        "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
                                        "DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
                                        "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
                                        "DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
                                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                        "FIELD_CODE" => array("NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_TEXT", ""),
                                        "PROPERTY_CODE" => array("GALLERY_TITLE", "GALLERY_BORDER", "CERTIFICATE_TITLE", "CERTIFICATE_BORDER", "VIDEO_TITLE", "VIDEO_LINK", ""),
                                        "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
                                        "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                                        "META_KEYWORDS" => $arParams["META_KEYWORDS"],
                                        "META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
                                        "BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
                                        "SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
                                        "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
                                        "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                                        "SET_TITLE" => $arParams["SET_TITLE"],
                                        "MESSAGE_404" => $arParams["MESSAGE_404"],
                                        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                                        "SHOW_404" => $arParams["SHOW_404"],
                                        "FILE_404" => $arParams["FILE_404"],
                                        "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
                                        "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
                                        "ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
                                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                        "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
                                        "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
                                        "DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
                                        "DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
                                        "PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
                                        "PAGER_SHOW_ALWAYS" => "N",
                                        "PAGER_TEMPLATE" => "phoenix_round",
                                        "PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
                                        "CHECK_DATES" => $arParams["CHECK_DATES"],
                                        "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
                                        "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
                                        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                                        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                                        "IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
                                        "USE_SHARE" => $arParams["USE_SHARE"],
                                        "SHARE_HIDE" => $arParams["SHARE_HIDE"],
                                        "SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
                                        "SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
                                        "SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
                                        "SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
                                        "ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
                                        'STRICT_SECTION_CHECK' => (isset($arParams['STRICT_SECTION_CHECK']) ? $arParams['STRICT_SECTION_CHECK'] : ''),
                                        "COMPOSITE_FRAME_MODE" => "N",
                                    ),
                                    $component
                                );


                                $arSelect = Array("ID", "PROPERTY_FILTER_SHOW", "PROPERTY_FILTER_HIDE_PRICE");
                                $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BRAND']["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID" => $ElementID);
                                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                                $arElement = array();

                                $arElement = $res->GetNext();

                            ?>
                            
                            <div class="col-12 order-1">
                                <div class="element-list-wrap parent-tool-settings active">
                                    <?
                                        if($available == "N")
                                            $GLOBALS["arrBrandFilter"][">CATALOG_QUANTITY"] = 0;


                                        $tabFilter = "";

                                        if(!isset($_COOKIE[$domenUrlForCookie."_phoenix_catalog_tab_brand-filter".SITE_ID]))
                                        {
                                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_SHOW']['VALUE']['ACTIVE'] == 'Y')
                                                $tabFilter = "active";
                                            else
                                                $tabFilter = "noactive";
                                        }
                                            
                                        else
                                            $tabFilter = $_COOKIE[$domenUrlForCookie."_phoenix_catalog_tab_brand-filter".SITE_ID];

                                        $GLOBALS["PHOENIX_CURRENT_DIR"] = "element";
                                        $GLOBALS["PHOENIX_ELEMENT_ID"] = $ElementID;


                                        $GLOBALS["arrBrandPreFilter"]["PROPERTY_BRAND"] = $ElementID;
                                        $GLOBALS["arrBrandPreFilter"]["SECTION_ACTIVE"] = "Y";
                                        $GLOBALS["arrBrandPreFilter"]["SECTION_SCOPE"] = "iblock";

                                    ?>

                                    <?if($arElement["PROPERTY_FILTER_SHOW_VALUE"] == "Y"):?>

                                        <?ob_start()?>
                                            <?

                                                

                                                $filterHidePrice = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["FILTER_HIDE_PRICE"]["VALUE"]["ACTIVE"];

                                                if($filterHidePrice == "Y")
                                                    $filterHidePrice = ($arElement["PROPERTY_FILTER_HIDE_PRICE_VALUE"] == "Y")?"":"Y";
                                                else
                                                    $filterHidePrice = ($arElement["PROPERTY_FILTER_HIDE_PRICE_VALUE"] == "Y")?"Y":"";

                                                

                                                $GLOBALS['arrBrandPreFilter'] = array_merge($GLOBALS['arrBrandPreFilter'], CPhoenix::getFilterByRegion());

                                                $arStoresFilter = CPhoenix::getFilterByStores();

                                                if(!empty($arStoresFilter))
                                                    $GLOBALS['arrBrandPreFilter'][] = $arStoresFilter;


                                                $APPLICATION->IncludeComponent(
                                                    "bitrix:catalog.smart.filter",
                                                    "main",
                                                        Array(
                                                            "FILTER_HIDE_PRICE" => $filterHidePrice,
                                                            "DATA_SHOW" => "brand-filter",
                                                            "SECTION_ID" => 0,
                                                            "SHOW_ALL_WO_SECTION" => "Y",
                                                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                            "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                                            "CACHE_NOTES" => "",
                                                            "COMPOSITE_FRAME_MODE" => "N",
                                                            "TAB_FILTER" => $tabFilter,
                                                            'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
                                                            "CONVERT_CURRENCY" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
                                                            "DISPLAY_ELEMENT_COUNT" => "Y",
                                                            "FILTER_NAME" => "arrBrandFilter",
                                                            "PREFILTER_NAME"=>"arrBrandPreFilter",
                                                            "FILTER_VIEW_MODE" => "vertical",
                                                            "HIDE_NOT_AVAILABLE" => "L",
                                                            "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_ID"],
                                                            "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_TYPE"],
                                                            "PAGER_PARAMS_NAME" => "arrPager",
                                                            "PRICE_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
                                                            "SAVE_IN_SESSION" => "N",
                                                            "SECTION_CODE" => "",
                                                            "SECTION_DESCRIPTION" => "-",
                                                            "SECTION_TITLE" => "-",
                                                            "SEF_MODE" => "N",
                                                            "TEMPLATE_THEME" => "blue",
                                                            "XML_EXPORT" => "Y",
                                                            "INSTANT_RELOAD" => "Y",
                                                            "SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"]."#actionbox",
                                                            "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                                                        ),
                                                        $component
                                                    );
                                            ?>

                                            

                                        <?$html = ob_get_clean();?>

                                        <?$APPLICATION->AddViewContent('filter_content', $html);?>

                                    <?endif;?>


                                    <?
                                        
                                        $isAjax = ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["ajax_action"]) && $_POST["ajax_action"] == "Y");

                                        if($isAjax)
                                            $APPLICATION->RestartBuffer();


                                        $GLOBALS["arrBrandFilter"]["PROPERTY_BRAND"] = $ElementID;
                                        
                                        
                                        unset($GLOBALS["arrBrandFilter"]["FACET_OPTIONS"]);


                                            $APPLICATION->IncludeComponent(
                                                "bitrix:catalog.section",
                                                "main",
                                                array(
                                                    "FILTER_NAME" => "arrBrandFilter",
                                                    "ELEMENT_SORT_FIELD" => $sort1,
                                                    "ELEMENT_SORT_ORDER" => $sort_order1,
                                                    "ELEMENT_SORT_FIELD2" => $sort2,
                                                    "ELEMENT_SORT_ORDER2" => $sort_order2,
                                                    "PAGE_ELEMENT_COUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["NEWS_COUNT"]["VALUE"],


                                                    "OBJ_NAME" => "brand_".$ElementID,
                                                    "FROM" => "brand",
                                                    'VIEW' => $view,
                                                    "COLS" => '3',
                                                    "COLS_LG" => '3',

                                                    'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
                                                    'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
                                                    "PRICE_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
                                                    "OFFERS_CART_PROPERTIES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                                    "OFFERS_PROPERTY_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                                    'OFFER_TREE_PROPS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                                    "USE_PRICE_COUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == "Y" ? "Y" : "N",
                                                    "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_TYPE"],
                                                    "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],


                                                    
                                                    "OFFERS_FIELD_CODE" => array("NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE",""),
                                                     "OFFERS_SORT_FIELD" => "sort",
                                                    "OFFERS_SORT_ORDER" => "id",
                                                    "OFFERS_SORT_FIELD2" => "asc",
                                                    "OFFERS_SORT_ORDER2" => "asc",

                                                    'COMPARE_PATH' => '',

                                                   
                                                    "OFFERS_LIMIT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["OFFERS_QUANTITY"]["VALUE"],
                                                    "SECTION_ID" => "",
                                                    "SECTION_CODE" => "",
                                                    "USE_PRODUCT_QUANTITY" => "Y",
                                                    "SHOW_PRICE_COUNT" => "1",
                                                    'SHOW_OLD_PRICE' => "Y",
                                                    'SHOW_MAX_QUANTITY' => "Y",
                                                    "PRICE_VAT_INCLUDE" => "Y",
                                                    'SHOW_DISCOUNT_PERCENT' => "Y",
                                                    "PAGER_SHOW_ALL" => "N",
                                                    'HIDE_NOT_AVAILABLE' => "L",
                                                    'HIDE_NOT_AVAILABLE_OFFERS' => "Y",



                                                    "BASKET_URL" => "",
                                                    "PROPERTY_CODE" => array(""),
                                                    "PROPERTY_CODE_MOBILE" => "",

                                                    "META_KEYWORDS" => '',
                                                    "META_DESCRIPTION" => '',
                                                    "BROWSER_TITLE" => '',
                                                    "SET_LAST_MODIFIED" => '',
                                                    "INCLUDE_SUBSECTIONS" => "Y",
                                                    "SHOW_ALL_WO_SECTION" => "Y",
                                                    "ACTION_VARIABLE" => '',
                                                    "PRODUCT_ID_VARIABLE" => '',
                                                    "SECTION_ID_VARIABLE" => '',
                                                    "PRODUCT_QUANTITY_VARIABLE" => '',
                                                    "PRODUCT_PROPS_VARIABLE" => '',

                                                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                                    "SET_TITLE" => '',
                                                    "MESSAGE_404" => '',
                                                    "SET_STATUS_404" => 'Y',
                                                    "SHOW_404" => '',
                                                    "FILE_404" => '',
                                                    "DISPLAY_COMPARE" => '',

                                                    "LINE_ELEMENT_COUNT" => '',

                                                    "ADD_PROPERTIES_TO_BASKET" => "Y",
                                                    "PARTIAL_PRODUCT_PROPERTIES" => '',
                                                    "PRODUCT_PROPERTIES" => '',
                                                    "DISPLAY_TOP_PAGER" =>'',
                                                    "DISPLAY_BOTTOM_PAGER" => 'Y',
                                                    "PAGER_TEMPLATE" => "phoenix_round",
                                                    "PAGER_TITLE" => '',
                                                    "PAGER_SHOW_ALWAYS" =>'',
                                                    "PAGER_DESC_NUMBERING" => '',
                                                    "PAGER_DESC_NUMBERING_CACHE_TIME" => '36000',

                                                    "PAGER_BASE_LINK_ENABLE" => '',
                                                    "PAGER_BASE_LINK" => '',
                                                    "PAGER_PARAMS_NAME" => '',
                                                    "LAZY_LOAD" => '',
                                                    "MESS_BTN_LAZY_LOAD" => '',
                                                    "LOAD_ON_SCROLL" => '',
                                                    "SECTION_URL" => "",
                                                    "DETAIL_URL" => "",
                                                    "USE_MAIN_ELEMENT_SECTION" => '',

                                                    'LABEL_PROP' => '',
                                                    'LABEL_PROP_MOBILE' => '',
                                                    'LABEL_PROP_POSITION' => '',
                                                    'ADD_PICT_PROP' => '',
                                                    'PRODUCT_DISPLAY_MODE' => '',
                                                    'PRODUCT_BLOCKS_ORDER' => '',
                                                    'PRODUCT_ROW_VARIANTS' => '',
                                                    'ENLARGE_PRODUCT' => '',
                                                    'ENLARGE_PROP' => '',
                                                    'SHOW_SLIDER' => '',
                                                    'SLIDER_INTERVAL' => '',
                                                    'SLIDER_PROGRESS' => '',
                                                    'OFFER_ADD_PICT_PROP' => '',
                                                    'PRODUCT_SUBSCRIPTION' => 'Y',
                                                    'DISCOUNT_PERCENT_POSITION' =>'',
                                                    'MESS_SHOW_MAX_QUANTITY' => '',
                                                    'RELATIVE_QUANTITY_FACTOR' => '',
                                                    'MESS_RELATIVE_QUANTITY_MANY' => '',
                                                    'MESS_RELATIVE_QUANTITY_FEW' => '',
                                                    'MESS_BTN_BUY' => '',
                                                    'MESS_BTN_ADD_TO_BASKET' => '',
                                                    'MESS_BTN_SUBSCRIBE' => '',
                                                    'MESS_BTN_DETAIL' => '',
                                                    'MESS_NOT_AVAILABLE' => '',
                                                    'MESS_BTN_COMPARE' => '',
                                                    'USE_ENHANCED_ECOMMERCE' => '',
                                                    'DATA_LAYER_NAME' => '',
                                                    'BRAND_PROPERTY' =>'',
                                                    'TEMPLATE_THEME' => '',
                                                    "ADD_SECTIONS_CHAIN" => "Y",
                                                    'ADD_TO_BASKET_ACTION' => '',
                                                    'SHOW_CLOSE_POPUP' => '',
                                                    'COMPARE_NAME' => '',
                                                    'BACKGROUND_IMAGE' => '',
                                                    'COMPATIBLE_MODE' => '',
                                                    'DISABLE_INIT_JS_IN_COMPONENT' => '',
                                                    "COMPOSITE_FRAME_MODE" => "N",
                                                       
                                                   ),
                                                $component
                                            );

                                        if($isAjax)
                                        {
                                           die();
                                        }
                                        

                                        $GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $ElementID;

                                    ?>



                                    
                                    <?if($ar_result["UF_PHX_CTLG_TXT_P_ENUM"]["XML_ID"] == "short"):?>
                                        <?$APPLICATION->ShowViewContent('catalog-bottom-desc');?>
                                    <?endif;?>
                                    
                                </div>
                            </div>

                        </div>
                    
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["BRANDS"] === 'Y'):?>
    <div class="catalog-stories-ajax" data-count="4"></div>
<?endif;?>
<?
/* 
//sotbit seometa meta start
global $sotbitSeoMetaTitle;
global $sotbitSeoMetaKeywords;
global $sotbitSeoMetaDescription;
global $sotbitSeoMetaBreadcrumbTitle;
global $sotbitSeoMetaH1;

if(!empty($sotbitSeoMetaH1))
{
    $APPLICATION->SetTitle($sotbitSeoMetaH1);
    $GLOBALS["H1"] = $sotbitSeoMetaH1;
}

if(!empty($sotbitSeoMetaTitle))
{
    $APPLICATION->SetPageProperty("title", $sotbitSeoMetaTitle);
    $GLOBALS["TITLE"] = $sotbitSeoMetaTitle;
}

if(!empty($sotbitSeoMetaKeywords))
{
    $APPLICATION->SetPageProperty("keywords", $sotbitSeoMetaKeywords);
    $GLOBALS["KEYWORDS"] = $sotbitSeoMetaKeywords;
}

if(!empty($sotbitSeoMetaDescription))
{
    $APPLICATION->SetPageProperty("description", $sotbitSeoMetaDescription);
    $GLOBALS["DESCRIPTION"] = $sotbitSeoMetaDescription;
}

if(!empty($sotbitSeoMetaBreadcrumbTitle)) 
{
    $APPLICATION->AddChainItem($sotbitSeoMetaBreadcrumbTitle);
}
//sotbit seometa meta end 
*/
?>