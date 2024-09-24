<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);

?>


<?
global $PHOENIX_TEMPLATE_ARRAY;


function page404()
{
    global $APPLICATION;

    if (!defined("ERROR_404"))
        define("ERROR_404", "Y");

    \CHTTP::setStatus("404 Not Found");
       
    if ($APPLICATION->RestartWorkarea()) {
       require(\Bitrix\Main\Application::getDocumentRoot()."/404.php");
       die();
    }
}


$arSelect = Array("ID", "CODE", "IBLOCK_ID", "IBLOCK_SECTION_ID", "ACTIVE", "PROPERTY_*");


$arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "CODE"=>$arResult["VARIABLES"]["ELEMENT_CODE"], "ACTIVE_DATE" => "Y");

$arStoresFilter = CPhoenix::getFilterByStores();

if(!empty($arStoresFilter))
    $arFilter[] = $arStoresFilter;



$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

if($ob = $res->GetNextElement())
{
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();
}
else
{
    page404();
}
/*if($arProps["MODE_HIDE"]["VALUE"] === 'Y')
{
    page404();
}*/


$arResult["NEW_GROUPS"] = array();
$db_old_groups = CIBlockElement::GetElementGroups($arFields["ID"], true, array("ID", "GLOBAL_ACTIVE"));
//$arResult["NEW_GROUPS"] = Array($NEW_GROUP_ID);

while($ar_group = $db_old_groups->Fetch())
{
    if($ar_group["GLOBAL_ACTIVE"] == "Y")
        $arResult["NEW_GROUPS"][] = $ar_group["ID"];
}


if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SHOW_DEACTIVATED_GOODS"]["VALUE"]["ACTIVE"] != "Y")
{
    if(empty($arFields) || $arFields["ACTIVE"] == "N" || $arProps["MODE_HIDE"]["VALUE"] == "Y")
        page404();


    if(!empty($arResult["NEW_GROUPS"]))
        $arResult["NEW_GROUPS"] = array_diff($arResult["NEW_GROUPS"], array(''));

    if($arFields["IBLOCK_SECTION_ID"]>0 && empty($arResult["NEW_GROUPS"]))
        page404();
}


if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["USE_404"]["VALUE"]["ACTIVE"] === "Y")
{
    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"]) > 0)
    {
        if(!empty($arProps["REGION"]["VALUE"]) && !in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"], $arProps["REGION"]["VALUE"]))
            page404();
    }
    else
    {
        if(!empty($arProps["REGION"]["VALUE"]))
            page404();
    }
}




$GLOBALS["PHOENIX_CURRENT_DIR"] = "element";
$GLOBALS["PHOENIX_CURRENT_SECTION_ID"] = $arFields["IBLOCK_SECTION_ID"];
$GLOBALS["PHOENIX_ELEMENT_ID"] = $arFields["ID"];
$GLOBALS["PHOENIX_CURRENT_TMPL"] = $arProps["ITEM_TEMPLATE"]["VALUE_XML_ID"];


if(strlen($arProps["ITEM_TEMPLATE"]["VALUE_XML_ID"]) <= 0)
    $arProps["ITEM_TEMPLATE"]["VALUE_XML_ID"] = "default";


$header_back = "";


if($arProps["HEADER_PICTURE"]["VALUE"] > 0)
{
    $img = CFile::ResizeImageGet($arProps["HEADER_PICTURE"]["VALUE"], array('width'=>3000, 'height'=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);  
    $header_back = $img["src"];   
}    



$arResult["BANNERS_LEFT"] = Array();

if(!empty($arProps["BANNERS_LEFT"]["VALUE"]) && $arProps["BANNERS_LEFT_TYPE"]["VALUE_XML_ID"] == "own")
    $arResult["BANNERS_LEFT"] = $arProps["BANNERS_LEFT"]["VALUE"];


$arResult["EMPL_BANNER"] = Array();

if(!empty($arProps["EMPL_BANNER"]["VALUE"]) && $arProps["EMPL_BANNER_TYPE"]["VALUE_XML_ID"] == "own")
    $arResult["EMPL_BANNER"] = $arProps["EMPL_BANNER"]["VALUE"];


$parent_section_id = intval($arFields["IBLOCK_SECTION_ID"]);


$showStore = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SHOW_STORE_BLOCK"]["VALUE"]["ACTIVE"]=="Y")?true:false;

$firstParent = true;
$i = 0;
while($parent_section_id != 0)
{

    if($parent_section_id<0 || $i >=10)
        break;

    $arSelect = Array("ID", "DETAIL_PICTURE", "ACTIVE", "GLOBAL_ACTIVE", "IBLOCK_SECTION_ID", "UF_*");
    $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ID"=>$parent_section_id);
    $db_list = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
    
    while($ar_res = $db_list->GetNext())
    {
        
        if($firstParent)
        {
            $showStore = ($ar_res["UF_HIDE_STORE_BLOCK"])?false:$showStore;
            $firstParent = false;
        }


        if($arProps["BANNERS_LEFT_TYPE"]["VALUE_XML_ID"] == "parent")
        {
            if(empty($arResult["BANNERS_LEFT"]))
                $arResult["BANNERS_LEFT"] = $ar_res["UF_PHX_CTLG_BNNRS"];
        }

        if($arProps["EMPL_BANNER_TYPE"]["VALUE_XML_ID"] == "parent")
        {
            if(empty($arResult["EMPL_BANNER"]))
                $arResult["EMPL_BANNER"] = $ar_res["UF_EMPL_BANNER"];
        }


        if(strlen($header_back) <= 0)
        {
            if($ar_res["DETAIL_PICTURE"] > 0)
            {
                $img = CFile::ResizeImageGet($ar_res["DETAIL_PICTURE"], array('width'=>3000, 'height'=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);  
                $header_back = $img["src"];   
            }
        }
        $parent_section_id = intval($ar_res["IBLOCK_SECTION_ID"]);
    }

    $i++;

}
unset($i);


if(strlen($header_back) <= 0 && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["CTLG_BG_PIC"]["VALUE"] > 0)
{
    $img = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["CTLG_BG_PIC"]["VALUE"], array('width'=>3000, 'height'=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);  
    $header_back = $img["src"];
}
?> 


<?if($arProps["ITEM_TEMPLATE"]["VALUE_XML_ID"] == "default"):?>

    <?$GLOBALS["IS_CONSTRUCTOR"] = false;?>

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

<div itemscope itemtype="http://schema.org/Product">

    <div class=
            "
                page-header
                padding-bottom-detail
                detail-catalog
                cover
                parent-scroll-down
                <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>
                phoenix-firsttype-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
                <?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES_MODE"]["VALUE"] == "custom" && !$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"]) ? "def-bg-xs" : "";?>
                ctlg
            " 
        <?if(strlen($header_back) > 0):?>data-src="<?=$header_back?>" style="background-image: url(<?=$header_back?>);"<?endif;?>
       
    >
        
        <div class="shadow-tone <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>"></div>
        <div class="top-shadow"></div>
        
        <div class="container z-i-9">

            <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumbs", Array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "START_FROM" => "0",
                    "PATH" => "",
                    "SITE_ID" => SITE_ID,
                    "COMPOSITE_FRAME_MODE" => "N",
                ),
                $component
            );?>
    
            <div class="row justify-content-between">   

                <div class="<?$APPLICATION->ShowViewContent('catalog-detail-head-left-part-cols');?> part part-left">
                    
                    <div class="head">
                        <div class="title main1">
                            <h1 itemprop="name"><?$APPLICATION->ShowTitle(false);?><?$APPLICATION->ShowViewContent('catalog-detail-set-product');?></h1>
                        </div>
                                                                        
                    </div>
                    
                </div>

                <?$APPLICATION->ShowViewContent('catalog-detail-head-right-part');?>
    
            </div>
        </div>
                                            
    </div>
    
    <div class="catalog-card-wrap page_pad_bot page-body detail-catalog">

        <div class="container">

            <div class="catalog-card-wrap-inner">

                <div class="row">
                
                    <div class="col-lg-five col-12 d-none d-lg-block parent-fixedSrollBlock">
        
                        <div class="wrapperWidthFixedSrollBlock">
                        
                            <div class="selector-fixedSrollBlock menu-navigation" id='navigation'>

                                <div class="selector-fixedSrollBlock-real-height menu-navigation-inner">

                                    <div class="menu-navigation-inner-padding-right">
        
                                        <?$APPLICATION->ShowViewContent('catalog-left-menu');?>

                                        <?if(is_array($arProps["SIDE_MENU_HTML"]["VALUE"])):?>
                                            <div class="sidemenuHTML"><?=$arProps["SIDE_MENU_HTML"]["~VALUE"]["TEXT"]?></div>
                                        <?endif;?>

                                        <?$APPLICATION->ShowViewContent('empl-banner');?>
                                                                        
                                        <?if(!empty($arResult["BANNERS_LEFT"]) > 0):?>
                                            
                                            <?$GLOBALS["arrBannersFilter"]["ID"] = $arResult["BANNERS_LEFT"];?>
                                            
                                            <?$APPLICATION->IncludeComponent(
                                                "bitrix:news.list", 
                                                "banners-left", 
                                                array(
                                                    "COMPONENT_TEMPLATE" => "banners-left",
                                                    "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_TYPE"],
                                                    "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_ID"],
                                                    "NEWS_COUNT" => "20",
                                                    "SORT_BY1" => "SORT",
                                                    "SORT_ORDER1" => "ASC",
                                                    "SORT_BY2" => "SORT",
                                                    "SORT_ORDER2" => "ASC",
                                                    "FILTER_NAME" => "arrBannersFilter",
                                                    "FIELD_CODE" => array(
                                                        0 => "DETAIL_PICTURE",
                                                        1 => "PREVIEW_PICTURE",
                                                    ),
                                                    "PROPERTY_CODE" => array(
                                                        0 => "",
                                                        1 => "BANNER_BTN_TYPE",
                                                        2 => "BANNER_ACTION_ALL_WRAP",
                                                        3 => "BANNER_USER_BG_COLOR",
                                                        4 => "BANNER_UPTITLE",
                                                        5 => "BANNER_BTN_NAME",
                                                        6 => "BANNER_TITLE",
                                                        7 => "BANNER_BTN_BLANK",
                                                        8 => "BANNER_BORDER",
                                                        9 => "BANNER_DESC",
                                                        10 => "BANNER_TEXT",
                                                        11 => "BANNER_LINK",
                                                        12 => "BANNER_COLOR_TEXT",
                                                        13 => "",
                                                    ),
                                                    "CHECK_DATES" => "Y",
                                                    "DETAIL_URL" => "",
                                                    "AJAX_MODE" => "N",
                                                    "AJAX_OPTION_JUMP" => "N",
                                                    "AJAX_OPTION_STYLE" => "Y",
                                                    "AJAX_OPTION_HISTORY" => "N",
                                                    "AJAX_OPTION_ADDITIONAL" => "",
                                                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                                    "PREVIEW_TRUNCATE_LEN" => "",
                                                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                                    "SET_TITLE" => "N",
                                                    "SET_BROWSER_TITLE" => "N",
                                                    "SET_META_KEYWORDS" => "N",
                                                    "SET_META_DESCRIPTION" => "N",
                                                    "SET_LAST_MODIFIED" => "N",
                                                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                                    "ADD_SECTIONS_CHAIN" => "N",
                                                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                                    "PARENT_SECTION" => "",
                                                    "PARENT_SECTION_CODE" => "",
                                                    "INCLUDE_SUBSECTIONS" => "N",
                                                    "STRICT_SECTION_CHECK" => "N",
                                                    "DISPLAY_DATE" => "N",
                                                    "DISPLAY_NAME" => "N",
                                                    "DISPLAY_PICTURE" => "N",
                                                    "DISPLAY_PREVIEW_TEXT" => "N",
                                                    "COMPOSITE_FRAME_MODE" => "N",
                                                    "PAGER_TEMPLATE" => ".default",
                                                    "DISPLAY_TOP_PAGER" => "N",
                                                    "DISPLAY_BOTTOM_PAGER" => "N",
                                                    "PAGER_TITLE" => "",
                                                    "PAGER_SHOW_ALWAYS" => "N",
                                                    "PAGER_DESC_NUMBERING" => "N",
                                                    "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                                                    "PAGER_SHOW_ALL" => "N",
                                                    "PAGER_BASE_LINK_ENABLE" => "N",
                                                    "SET_STATUS_404" => "N",
                                                    "SHOW_404" => "N",
                                                    "MESSAGE_404" => "",

                                                ),
                                                $component
                                            );?>
                                        
                                        <?endif;?>

                                        <div class="close-mob close-side-menu d-lg-none"></div>

                                    </div>

                                </div>
                            
                            </div>
    
                        </div>
                       
                    </div>

                
        
                    <div class="col-lg-five-80 col-12 content-inner page">

                        <div class="block small-block first-block-detail">

                            <?
                                
                                $componentElementParams = array(
                                    'EMPL_BANNER'=>$arResult["EMPL_BANNER"],
                                    'VIEW' => $view,
                                    'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
                                    'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
                                    "USE_PRICE_COUNT" => "Y",
                                    'PRICE_CODE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
                                    'OFFERS_CART_PROPERTIES' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                    'OFFERS_PROPERTY_CODE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                    'OFFER_TREE_PROPS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                    'MAIN_BLOCK_OFFERS_PROPERTY_CODE' => (isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_']) ? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'] : ''),
                                    'IBLOCK_TYPE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']['IBLOCK_TYPE'],
                                    'IBLOCK_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],

                                    'SHOW_STORE_BLOCK' => $showStore,

                                    'NEW_GROUPS' => $arResult["NEW_GROUPS"],
                                    'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
                                    'ELEMENT_CODE' => $arResult['VARIABLES']['ELEMENT_CODE'],
                                    'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
                                    'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
                                    'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
                                    'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],
                                    'COMPARE_PATH' => '',


                                    "USE_PRODUCT_QUANTITY" => "Y",
                                    "SHOW_PRICE_COUNT" => "1",
                                    'SHOW_OLD_PRICE' => "Y",
                                    'SHOW_MAX_QUANTITY' => "Y",
                                    "PRICE_VAT_INCLUDE" => "Y",
                                    'PRICE_VAT_SHOW_VALUE' => 'N',
                                    'SHOW_DISCOUNT_PERCENT' => "Y",
                                    "ADD_PROPERTIES_TO_BASKET" => "Y",
                                    'HIDE_NOT_AVAILABLE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"],
                                    'HIDE_NOT_AVAILABLE_OFFERS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"],
                                    "COMPOSITE_FRAME_MODE" => "N",
                                    'ADD_ELEMENT_CHAIN' => "Y",
                                    'OFFERS_FIELD_CODE' => array("NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE",""),
                                    "OFFERS_SORT_FIELD" => "SORT",
                                    "OFFERS_SORT_ORDER" => "ID",
                                    "OFFERS_SORT_FIELD2" => "ASC",
                                    "OFFERS_SORT_ORDER2" => "ASC",

                                    'ADD_SECTIONS_CHAIN' => 'Y',
                                    'PRODUCT_SUBSCRIPTION' => 'Y',
                                    'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],
                                    'SHOW_DEACTIVATED' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SHOW_DEACTIVATED_GOODS"]["VALUE"]["ACTIVE"],
                                    'USE_MAIN_ELEMENT_SECTION' => 'Y',
                                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                    'SET_TITLE' => 'Y',
                                    'SET_STATUS_404' => 'Y',


                                    'MESS_SHOW_MAX_QUANTITY' => '',
                                    'MESS_RELATIVE_QUANTITY_MANY' => '',
                                    'MESS_RELATIVE_QUANTITY_FEW' => '',
                                    'MESS_BTN_SUBSCRIBE' => '',
                                    'MESS_NOT_AVAILABLE' => '',
                                    'MESS_BTN_COMPARE' => '',
                                    'MESS_PRICE_RANGES_TITLE' => '',
                                    'MESS_DESCRIPTION_TAB' => '',
                                    'MESS_PROPERTIES_TAB' => '',
                                    'MESS_COMMENTS_TAB' => '',
                                    'MESS_BTN_DETAIL' => "",
                                    'MESS_BTN_BUY' => "",
                                    'MESS_BTN_ADD_TO_BASKET' => "",
                                    'META_KEYWORDS' => '',
                                    'META_DESCRIPTION' => '',
                                    'BROWSER_TITLE' => '',
                                    'SET_CANONICAL_URL' => 'Y',
                                    'ACTION_VARIABLE' => '',
                                    'PRODUCT_ID_VARIABLE' => '',
                                    'SECTION_ID_VARIABLE' => '',
                                    'CHECK_SECTION_ID_VARIABLE' => '',
                                    'PRODUCT_QUANTITY_VARIABLE' => '',
                                    'PRODUCT_PROPS_VARIABLE' => '',
                                    'SET_LAST_MODIFIED' => '',
                                    'MESSAGE_404' => '',
                                    'SHOW_404' => '',
                                    'FILE_404' => '',
                                    'LINK_IBLOCK_TYPE' => '',
                                    'LINK_IBLOCK_ID' => '',
                                    'LINK_PROPERTY_SID' => '',
                                    'LINK_ELEMENTS_URL' => '',
                                    'STRICT_SECTION_CHECK' => '',
                                    'ADD_PICT_PROP' => '',
                                    'LABEL_PROP' => '',
                                    'LABEL_PROP_MOBILE' => '',
                                    'LABEL_PROP_POSITION' => '',
                                    'OFFER_ADD_PICT_PROP' => '',
                                    'DISCOUNT_PERCENT_POSITION' => '',
                                    'RELATIVE_QUANTITY_FACTOR' => '',
                                    'MAIN_BLOCK_PROPERTY_CODE' => '',
                                    'VOTE_DISPLAY_AS_RATING' => '',
                                    'USE_COMMENTS' => '',
                                    'BLOG_USE' => '',
                                    'BLOG_URL' => '',
                                    'BLOG_EMAIL_NOTIFY' => '',
                                    'VK_USE' => '',
                                    'VK_API_ID' => '',
                                    'FB_USE' => '',
                                    'FB_APP_ID' => '',
                                    'BRAND_USE' => '',
                                    'BRAND_PROP_CODE' => '',
                                    'DISPLAY_NAME' => 'Y',
                                    'IMAGE_RESOLUTION' => '',
                                    'PRODUCT_INFO_BLOCK_ORDER' => '',
                                    'PRODUCT_PAY_BLOCK_ORDER' => '',
                                    'ADD_DETAIL_TO_SLIDER' => '',
                                    'TEMPLATE_THEME' => '',
                                    'DISPLAY_PREVIEW_TEXT_MODE' => '',
                                    'DETAIL_PICTURE_MODE' => '',
                                    'ADD_TO_BASKET_ACTION' => '',
                                    'ADD_TO_BASKET_ACTION_PRIMARY' => '',
                                    'SHOW_CLOSE_POPUP' => '',
                                    'DISPLAY_COMPARE' => '',
                                    'BACKGROUND_IMAGE' => '',
                                    'COMPATIBLE_MODE' => '',
                                    'DISABLE_INIT_JS_IN_COMPONENT' => '',
                                    'SET_VIEWED_IN_COMPONENT' => '',
                                    'SHOW_SLIDER' => '',
                                    'SLIDER_INTERVAL' => '',
                                    'SLIDER_PROGRESS' => '',
                                    'USE_ENHANCED_ECOMMERCE' => '',
                                    'DATA_LAYER_NAME' => '',
                                    'BRAND_PROPERTY' => '',
                                    'USE_VOTE_RATING' => "",
                                    'BASKET_URL' => "",
                                    'PROPERTY_CODE' => array(),
                                    'USE_GIFTS_DETAIL' => '',
                                    'USE_GIFTS_MAIN_PR_SECTION_LIST' => '',
                                    'GIFTS_SHOW_DISCOUNT_PERCENT' => '',
                                    'GIFTS_SHOW_OLD_PRICE' => '',
                                    'GIFTS_DETAIL_PAGE_ELEMENT_COUNT' => '',
                                    'GIFTS_DETAIL_HIDE_BLOCK_TITLE' => '',
                                    'GIFTS_DETAIL_TEXT_LABEL_GIFT' => '',
                                    'GIFTS_DETAIL_BLOCK_TITLE' => '',
                                    'GIFTS_SHOW_NAME' => '',
                                    'GIFTS_SHOW_IMAGE' => '',
                                    'GIFTS_MESS_BTN_BUY' => '',
                                    'GIFTS_PRODUCT_BLOCKS_ORDER' => '',
                                    'GIFTS_SHOW_SLIDER' => '',
                                    'GIFTS_SLIDER_INTERVAL' => '',
                                    'GIFTS_SLIDER_PROGRESS' => '',
                                    'GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT' => '',
                                    'GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE' => '',
                                    'GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE' => ''
                                );
                        
                                $elementId = $APPLICATION->IncludeComponent(
                                    'bitrix:catalog.element',
                                    'main',
                                    $componentElementParams,
                                    $component
                                );
                                
                                $GLOBALS['CATALOG_CURRENT_ELEMENT_ID'] = $elementId;
                        
                            ?>

                            

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["CATALOG"] === 'Y'):?>
            <div class="catalog-stories-ajax" data-count="4"></div>
        <?endif;?>
    </div>

</div>



<?$APPLICATION->ShowViewContent('catalog-detail-popup-gallery');?>

<?endif;?>

<?if($arProps["ITEM_TEMPLATE"]["VALUE_XML_ID"] == "landing"):?>

    <?$GLOBALS["IS_CONSTRUCTOR"] = true;?>
    
    <?if($arProps["CHOOSE_LANDING"]["VALUE"] > 0):?>
   
        <?
        $arFilter = Array("ID" => $arProps["CHOOSE_LANDING"]["VALUE"]);
        $db_list = CIBlockSection::GetList(Array(), $arFilter, false);
        $ar_res = $db_list->GetNext();
        ?> 
    
        <?if($ar_res["ACTIVE"] == "Y"):?>
            <?$section = $APPLICATION->IncludeComponent(
                "concept:phoenix.one.page", 
                "", 
                array(
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CHECK_DATES" => $arParams["CHECK_DATES"],
                    "IBLOCK_ID" => $ar_res["IBLOCK_ID"],
                    "IBLOCK_TYPE" => $ar_res["IBLOCK_TYPE_ID"],
                    "PARENT_SECTION" => $ar_res["ID"],
                    "SET_TITLE" => $arParams["SET_TITLE"],
                    "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                    "MESSAGE_404" => $arParams["MESSAGE_404"],
                    "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                    "SHOW_404" => $arParams["SHOW_404"],
                    "FILE_404" => $arParams["FILE_404"],
                    "COMPONENT_TEMPLATE" => "",
                    "COMPOSITE_FRAME_MODE" => "N",
                ),
                $component
            );?>
        
            <?$GLOBALS["PHOENIX_CURRENT_SECTION_ID"] = $section;?>
            
        <?else:?>

            <?
            if (!defined("ERROR_404"))
               define("ERROR_404", "Y");

                \CHTTP::setStatus("404 Not Found");
                   
                if ($APPLICATION->RestartWorkarea()) {
                   require(\Bitrix\Main\Application::getDocumentRoot()."/404.php");
                   die();
                }
    

            ?>

        <?endif;?>
    
    <?endif;?>

<?endif;?>

<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["CATALOG"] === 'Y'
    || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["LAND"] === 'Y'
    || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["BRANDS"] === 'Y'
    || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["NEWS"] === 'Y'
    || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["BLOG"] === 'Y'
    || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["OFFERS"] === 'Y'):?>

    <div id="productJSData" data-json='{ "id": "<?=$arFields["ID"]?>", "siteId": "<?=SITE_ID?>" }'></div>

    <script>
        $(document).ready(function() {
        var jsDataJSON = $("#productJSData").data('json');

        $.ajax({
            url: '/bitrix/components/bitrix/catalog.element/ajax.php',
            method: 'POST',
            data: {
                AJAX: 'Y',
                SITE_ID: jsDataJSON.siteId,
                PRODUCT_ID: jsDataJSON.id,
                PARENT_ID: jsDataJSON.id
            },
            success: function(obj) {
            },
            error: function(p1,p2,p3) {
                console.log('ERROR',p1,p2,p3);
            }
        });
    });
    </script>
<?endif;?>