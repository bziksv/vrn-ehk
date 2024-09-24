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



global $PHOENIX_TEMPLATE_ARRAY, $DB;
$arFields = array();
$arProps = array();
$arSelect = Array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE_DATE" => "", "ACTIVE"=>"Y", "CODE"=>$arResult["VARIABLES"]["ELEMENT_CODE"]);

if($arParams["TYPE"] == "ACT")
    $arFilter["<=DATE_ACTIVE_FROM"] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")), mktime());


$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

if($ob = $res->GetNextElement())
{
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();
}
  

if(empty($arFields))
    page404();


$ar_new_groups = array();
$db_old_groups = CIBlockElement::GetElementGroups($arFields["ID"], true, array("ID", "GLOBAL_ACTIVE"));
$ar_new_groups = Array($NEW_GROUP_ID);

while($ar_group = $db_old_groups->Fetch())
{
    if($ar_group["GLOBAL_ACTIVE"] == "Y")
        $ar_new_groups[] = $ar_group["ID"];
}

if(!empty($ar_new_groups))
    $ar_new_groups = array_diff($ar_new_groups, array(''));


if(empty($ar_new_groups) && $arFields["IBLOCK_SECTION_ID"] > 0)
    page404();



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
    

$arResult["BANNERS_RIGHT"] = Array();

if(!empty($arProps["BANNERS_RIGHT"]["VALUE"]) && $arProps["BANNERS_RIGHT_TYPE"]["VALUE_XML_ID"] == "own")
    $arResult["BANNERS_RIGHT"] = $arProps["BANNERS_RIGHT"]["VALUE"];



if($arParams["TYPE"] == "ACT")
{
    if($arProps["HEADER_PICTURE"]["VALUE"] <= 0)
    {
        $header_back = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["BG_PIC"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false);
        $header_back = $header_back["src"];
    }
    
    
}
else
{
    $parent_section_id = intval($arFields["IBLOCK_SECTION_ID"]);

    while($parent_section_id != 0 && in_array($parent_section_id, $ar_new_groups))
    {
        if($parent_section_id<0)
            break;

        $arSelect = Array("ID", "PICTURE", "IBLOCK_SECTION_ID", "UF_*");
        $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "GLOBAL_ACTIVE"=>"Y", "ID"=>$parent_section_id);

        $db_list = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);


        while($ar_res = $db_list->GetNext())
        {
            if($arProps["BANNERS_RIGHT_TYPE"]["VALUE_XML_ID"] == "parent")
            {
                if(empty($arResult["BANNERS_RIGHT"]))
                    $arResult["BANNERS_RIGHT"] = $ar_res["UF_PHX_".$arParams["TYPE"]."_BNNRS"];

                if(empty($arResult["BANNERS_RIGHT"]))
                    $arResult["BANNERS_RIGHT"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["BANNERS"]['VALUE'];
            }
            
            
            if(strlen($header_back) <= 0)
            {
           
                if($ar_res["PICTURE"] > 0)
                {
                    $img = CFile::ResizeImageGet($ar_res["PICTURE"], array('width'=>3000, 'height'=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);  
                    $header_back = $img["src"];   
                }
            }
            $parent_section_id = intval($ar_res["IBLOCK_SECTION_ID"]);
        }
    }
}

if(strlen($header_back) <= 0 && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["BG_PIC"]["VALUE"])>0)
{
    $img = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"][$arParams["ARRAY_NAME"]]["ITEMS"]["BG_PIC"]["VALUE"], array('width'=>3000, 'height'=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
    $header_back = $img['src'];
}

$colsLeft = "col-md-9 col-12";
$colsRight = "col-md-3 col-12";

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

    
    <div class=
            "
                page-header
                cover
                parent-scroll-down
                <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>
                phoenix-firsttype-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
                padding-bottom-section
                <?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES_MODE"]["VALUE"] == "custom" && !$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"]) ? "def-bg-xs" : "";?>
                <?=ToLower($arParams["TYPE"])?>
            " 
        <?if(strlen($header_back) > 0):?>
            data-src="<?=$header_back?>"
            style="background-image: url(<?=$header_back?>);"
        <?endif;?>
    >
        <div class="shadow-tone <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>"></div>
        <div class="top-shadow"></div>

        <div class="container z-i-9">

            <div class="row">
                                    
                <div class="<?=$colsLeft?> part part-left">
                
                    <div class="head">

                        <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumbs", Array(
                                "COMPONENT_TEMPLATE" => ".default",
                                "START_FROM" => "0",
                                "PATH" => "",
                                "SITE_ID" => SITE_ID,
                                "COMPOSITE_FRAME_MODE" => "N",
                            ),
                            $component
                        );?>
                        
                        <div class="title main1"><h1><?$APPLICATION->ShowTitle(false);?></h1></div>
                                                                        
                    </div>
                    
                </div>

                <div class="<?=$colsRight?> part part-right">

                    <?$APPLICATION->ShowViewContent('news-detail-head-right-part');?>
                    
                </div>

            </div>

        </div>
                                            
    </div>

    <div class="news-list-wrap page_pad_bot detail">

        <div class="container">
            
            <div class="block-move-to-up">

                <div class="row">

                    <div class="col-lg-9 col-md-12 col-12 content-inner page">
         

                        <?$ElementID = $APPLICATION->IncludeComponent(
                            "bitrix:news.detail",
                            "news.detail-page",
                            Array(
                                "ARRAY_NAME"=>$arParams["ARRAY_NAME"],
                                "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
                                "DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
                                "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
                                "DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
                                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                "FIELD_CODE" => array(
                                    0 => "ID",
                                    1 => "CODE",
                                    2 => "NAME",
                                    3 => "SORT",
                                    4 => "PREVIEW_TEXT",
                                    5 => "PREVIEW_PICTURE",
                                    6 => "DETAIL_TEXT",
                                    7 => "DETAIL_PICTURE",
                                    8 => "DATE_ACTIVE_FROM",
                                    9 => "ACTIVE_FROM",
                                    10 => "SHOW_COUNTER",
                                    11 => "IBLOCK_TYPE_ID",
                                    12 => "IBLOCK_ID",
                                    13 => "IBLOCK_CODE",
                                    14 => "DATE_ACTIVE_TO",
                                    15 => "ACTIVE_TO",
                                    16 => "",
                                ),
                                "PROPERTY_CODE" => array(
                                    0 => "BANNERS_RIGHT",
                                    1 => "NEWS_ELEMENTS_ACTION",
                                    2 => "NEWS_ELEMENTS_BLOG",
                                    3 => "BUTTON_MODAL",
                                    4 => "NEWS_ELEMENTS_NEWS",
                                    5 => "BUTTON_FORM",
                                    6 => "BUTTON_TYPE",
                                    7 => "NEWS_TITLE_NBA",
                                    8 => "NEWS_TITLE_CATALOG",
                                    9 => "NEWS_GALLERY_TITLE",
                                    10 => "BUTTON_ONCLICK",
                                    11 => "BUTTON_NAME",
                                    12 => "NEWS_GALLERY_BORDER",
                                    13 => "BUTTON_BLANK",
                                    14 => "BANNERS_RIGHT_TYPE",
                                    15 => "CATALOG",
                                    16 => "BUTTON_QUIZ",
                                    17 => "BUTTON_LINK",
                                    18 => "ITEM_TEMPLATE",
                                    19 => "NEWS_DETAIL_TEXT",
                                    20 => "DATE_ACTIVE_TO",
                                    21 => "ACTIVE_TO",
                                    22 => "",
                                ),
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
                                "PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
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
                        );?>

                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['PAGES']['VALUE'][$arParams['ARRAY_NAME']] === 'Y'):?>
                            <?CPhoenix::setMess(array('comments'));?>

                            <div class="wr-block-comments">

                                <div class="wr-content-title"><div class="content-title"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["TITLE_BLOCK"]?></div><div class="line"></div></div>

                                <div class="block-comments block-comments-js" data-element-id="<?=$ElementID?>">

                                    <div class="comments-set comments-set-js"></div>

                                    <div class="loading-block"></div>
                                </div>
                            </div>


                            <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['USERS_ACCESS']["VALUE"] === 'auth' && !$PHOENIX_TEMPLATE_ARRAY["USER_ID"]):?>
                                <div class="error-message">
                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["MESSAGE_AUTH"]?>
                                </div>
                            <?else:?>

                                <form class="form-comments">
                                    <input type="hidden" name="ELEMENT_ID" value="<?=$ElementID?>">
                                    <input type="hidden" name="IBLOCK_ID" value="<?=$arFields["IBLOCK_ID"]?>">

                                    <?CPhoenixTemplate::hiddenInputsFormHTML();?>

                                    <div class="question-js">

                                        <?
                                            $arUser = CPhoenix::getUserFields($PHOENIX_TEMPLATE_ARRAY["USER_ID"]);
                                        ?>

                                        <div class="row row-section">

                                            <div class="col-md-6 col-12">

                                                <div class="input-simple left-col <?=(strlen($arUser['NAME'])>0)?'in-focus':'';?>"> 

                                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["FORM_NAME"]?></span> 

                                                    <input type="text" class="focus-anim bord-1" name="USER_NAME" value="<?=(strlen($arUser['NAME'])>0)? $arUser['NAME']:'';?>">

                                                </div>
                                            </div>

                                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['FORM_EMAIL_ACTIVE']['VALUE']['ACTIVE'] === 'Y'):?>

                                                <div class="col-md-6 col-12">

                                                    <div class="input-simple right-col <?=(strlen($arUser['NAME'])>0)?'in-focus':'';?>"> 

                                                        <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["FORM_EMAIL"]?></span> 

                                                        <input type="text" class="focus-anim bord-1" name="USER_EMAIL" value="<?=(strlen($arUser['EMAIL'])>0)?$arUser['EMAIL']:'';?>">

                                                    </div>
                                                </div>
                                            <?endif;?>
                                        </div>
                                        
                                        <div class="textarea-simple">

                                            <div class="bg"></div>

                                            <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["TEXTAREA_DESCRIPTION"]?></span>

                                            <textarea class="focus-anim text-require bord-1" name="TEXT"></textarea>
                                        </div>
                                       
                                        <div class="wt-button">

                                            <div class="loader-simple d-none">
                                                <div class="xLoader form-preload"><div class="audio-wave"><span></span><span></span><span></span><span></span><span></span></div></div>
                                            </div>

                                            <button class="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> button-def main-color active btn-submit-comments-js" type="button"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["SEND_BTN_NAME"]?></button>
                                        </div>

                                    </div>
                                    <div class="thank-js d-none"></div>
                                </form>

                            <?endif;?>

                        <?endif;?>


                    </div>
                
                    <div class="col-lg-3 hidden-md hidden-sm hidden-xs">
                        

                        <div class="menu-navigation" id="navigation">

                            <div class="menu-navigation-inner">

                                <input type="hidden" id="detail-page" name="detail-page" value="<?=$arParams["IBLOCK_ID"]?>">

                                <?
                                    $tmpl = "";

                                    if($arParams["TYPE"] == "ACT")
                                        $tmpl = "-action";
                                    
                                ?>
                                    
                                <?$APPLICATION->IncludeComponent(
                                        "bitrix:catalog.section.list",
                                        "news.sections".$tmpl,
                                        array(
                                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                            "SECTION_ID" => $arFields["IBLOCK_SECTION_ID"],
                                            "SECTION_CODE" => "",
                                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                            "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                                            "TOP_DEPTH" => 1,
                                            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                                            "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                                            "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                                            "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                                            "ADD_SECTIONS_CHAIN" => "N",
                                            "TYPE"=> $arParams["TYPE"],
                                            "ELEMENT" => "Y",
                                            "COMPOSITE_FRAME_MODE" => "N",
                                        ),
                                        $component,
                                        array("HIDE_ICONS" => "Y")
                                    );                  
                                ?>


                                <?if($arParams["TYPE"] == "ACT"):?>
                                    <?$GLOBALS['arNewsfilter'] = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "!ID" => $arFields["ID"], "ACTIVE_DATE" => "", "ACTIVE" => "Y", "<=DATE_ACTIVE_FROM" => date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")), mktime()));?>

                                <?else:?>
                                    <?$GLOBALS['arNewsfilter'] = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "!ID" => $arFields["ID"], "ACTIVE" => "Y", 'SECTION_ID' => $arFields["IBLOCK_SECTION_ID"]);?>

                                <?endif;?>

                                <?if(is_array($arProps["SIDE_MENU_HTML"]["VALUE"])):?>
                                    <div class="sidemenuHTML"><?=$arProps["SIDE_MENU_HTML"]["~VALUE"]["TEXT"]?></div>
                                <?endif;?>

                                
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:news.list",
                                    "news.other-news",
                                    Array(
                                        "ACTIVE_DATE_FORMAT" => "FULL",
                                        "ADD_SECTIONS_CHAIN" => "N",
                                        "AJAX_MODE" => "N",
                                        "AJAX_OPTION_ADDITIONAL" => "",
                                        "AJAX_OPTION_HISTORY" => "N",
                                        "AJAX_OPTION_JUMP" => "N",
                                        "AJAX_OPTION_STYLE" => "Y",
                                        "CACHE_FILTER" => "Y",
                                        "CACHE_GROUPS" => "Y",
                                        "CACHE_TIME" => "36000000",
                                        "CACHE_TYPE" => "N",
                                        "CHECK_DATES" => "Y",
                                        "COMPOSITE_FRAME_MODE" => "N",
                                        "DETAIL_URL" => "",
                                        "DISPLAY_BOTTOM_PAGER" => "N",
                                        "DISPLAY_DATE" => "N",
                                        "DISPLAY_NAME" => "Y",
                                        "DISPLAY_PICTURE" => "Y",
                                        "DISPLAY_PREVIEW_TEXT" => "N",
                                        "DISPLAY_TOP_PAGER" => "N",
                                        "FIELD_CODE" => array("PREVIEW_PICTURE",""),
                                        "FILTER_NAME" => "arNewsfilter",
                                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                        "INCLUDE_SUBSECTIONS" => "N",
                                        "MESSAGE_404" => "",
                                        "NEWS_COUNT" => "2",
                                        "PAGER_BASE_LINK_ENABLE" => "N",
                                        "PAGER_DESC_NUMBERING" => "N",
                                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                        "PAGER_SHOW_ALL" => "N",
                                        "PAGER_SHOW_ALWAYS" => "N",
                                        "PAGER_TEMPLATE" => ".default",
                                        "PAGER_TITLE" => "",
                                        "PARENT_SECTION" => "",
                                        "PARENT_SECTION_CODE" => "",
                                        "PREVIEW_TRUNCATE_LEN" => "",
                                        "PROPERTY_CODE" => array("",""),
                                        "SET_BROWSER_TITLE" => "N",
                                        "SET_LAST_MODIFIED" => "N",
                                        "SET_META_DESCRIPTION" => "N",
                                        "SET_META_KEYWORDS" => "N",
                                        "SET_STATUS_404" => "N",
                                        "SET_TITLE" => "N",
                                        "SHOW_404" => "N",
                                        "SORT_BY1" => "rand",
                                        "SORT_BY2" => "",
                                        "SORT_ORDER1" => "ASC",
                                        "SORT_ORDER2" => "ASC",
                                        "STRICT_SECTION_CHECK" => "N"
                                    ),
                                    
                                    $component
                                    );?>

                                <?if(!empty($arResult["BANNERS_RIGHT"]) > 0):?>
                                    
                                    <?$GLOBALS["arrBannersFilter"]["ID"] = $arResult["BANNERS_RIGHT"];?>
                                    
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
                                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                            "PAGER_SHOW_ALL" => "N",
                                            "PAGER_BASE_LINK_ENABLE" => "N",
                                            "SET_STATUS_404" => "N",
                                            "SHOW_404" => "N",
                                            "MESSAGE_404" => ""
                                        ),
                                        $component
                                    );?>
                                
                                <?endif;?>

                            </div>
                            
                        </div>



                           
                    </div>

                </div>

            </div>


        </div>

    </div>

    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"][$arParams["ARRAY_NAME"]] === 'Y'):?>
        <div class="catalog-stories-ajax" data-count="4"></div>
    <?endif;?>

<?endif;?>

<?if($arProps["ITEM_TEMPLATE"]["VALUE_XML_ID"] == "landing"):?>
    
    <?if($arProps["CHOOSE_LANDING"]["VALUE"] > 0):?>
   
        <?
        $arFilter = Array("ID" => $arProps["CHOOSE_LANDING"]["VALUE"]);
        $db_list = CIBlockSection::GetList(Array(), $arFilter, false);
        $ar_res = $db_list->GetNext();
        ?> 

        <?if($ar_res["ACTIVE"] == "Y"):?>

            <?$GLOBALS["IS_CONSTRUCTOR"] = true;?>
    
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
