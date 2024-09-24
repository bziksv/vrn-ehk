<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

$this->setFrameMode(false);
global $PHOENIX_TEMPLATE_ARRAY;




$arSelect = array("ID", "NAME", "DESCRIPTION", "PICTURE", "DETAIL_PICTURE", "GLOBAL_ACTIVE", "ACTIVE", "IBLOCK_SECTION_ID", "UF_*");
$arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_ID"], "CODE" => $arResult["VARIABLES"]["SECTION_CODE"]);
$db_list = CIBlockSection::GetList(array(), $arFilter, false, $arSelect);
$ar_result = $db_list->GetNext();

$domenUrlForCookie = CPhoenixHost::getHostTranslit();

if(empty($ar_result) || $ar_result["ACTIVE"] == "N" || $ar_result["GLOBAL_ACTIVE"] == "N") 
{

    if (!defined("ERROR_404"))
        define("ERROR_404", "Y");

    \CHTTP::setStatus("404 Not Found");

    if ($APPLICATION->RestartWorkarea()) {
        require(\Bitrix\Main\Application::getDocumentRoot() . "/404.php");
        die();
    }
}

if (strlen($ar_result["UF_PHX_CTLG_TMPL"]) > 0) {
    $ar_result["UF_PHX_CTLG_TMPL_ENUM"] = CUserFieldEnum::GetList(array(), array(
        "ID" => $ar_result["UF_PHX_CTLG_TMPL"],
    ))->GetNext();
}

if (strlen($ar_result["UF_PHX_CTLG_TMPL_ENUM"]["XML_ID"]) <= 0)
    $ar_result["UF_PHX_CTLG_TMPL_ENUM"]["XML_ID"] = "default";


    CPhoenixFunc::setInitialFilterParams("arrCatalogFilter");



if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_FILTER']['VALUE']['ACTIVE'] == "Y" && $ar_result["UF_PHX_CTLG_TMPL_ENUM"]["XML_ID"] == "default" && !$ar_result["UF_USE_FILTER"]) 
{


    if (\Bitrix\Main\Loader::includeModule("sotbit.seometa")) {

        $html = "";

        ob_start();

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.smart.filter",
            "empty",
            array(
                "FILTER_HIDE_PRICE" => $filterHidePrice,
                "DATA_SHOW" => "catalog-filter",
                //"PREFILTER_NAME"=>"arrCatalogPreFilter",
                "FILTER_NAME" => "arrCatalogFilter",
                "TAB_FILTER" => $tabFilter,
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "CACHE_NOTES" => "",
                "COMPOSITE_FRAME_MODE" => "N",
                'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
                "CONVERT_CURRENCY" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
                "DISPLAY_ELEMENT_COUNT" => "Y",

                "FILTER_VIEW_MODE" => "vertical",
                "HIDE_NOT_AVAILABLE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"],
                'HIDE_NOT_AVAILABLE_OFFERS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"],
                "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_ID"],
                "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_TYPE"],
                "PAGER_PARAMS_NAME" => "arrPager",
                "PRICE_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
                "SAVE_IN_SESSION" => "N",
                "SECTION_CODE" => "",
                "SECTION_DESCRIPTION" => "-",
                "SECTION_ID" => $ar_result["ID"],
                "SECTION_TITLE" => "-",
                "SEF_MODE" => "Y",
                "TEMPLATE_THEME" => "blue",
                "XML_EXPORT" => "Y",
                "INSTANT_RELOAD" => "Y",
                "SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"] . "#actionbox",
                "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
            ),
            $component
        );



        $html = ob_get_clean();


        global $sotbitSeoMetaBottomDesc, $sotbitSeoMetaTopDesc, $sotbitSeoMetaAddDesc;
        global $sotbitSeoMetaFile;



        $APPLICATION->IncludeComponent(
            "sotbit:seo.meta",
            "",
            array(
                "FILTER_NAME" => "arrCatalogFilter",
                "SECTION_ID" => $ar_result["ID"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
            ),
            $component
        );



        if (strlen($sotbitSeoMetaTopDesc)>0)
            $sotbitSeoMetaFile = str_replace("<img", "<img class='img-fluid pictureInHead'", $sotbitSeoMetaFile);

        if (strlen($sotbitSeoMetaTopDesc)>0)
            $GLOBALS["~UF_PHX_CTLG_TOP_T"] = $sotbitSeoMetaTopDesc;

        if (strlen($sotbitSeoMetaBottomDesc)>0)
            $GLOBALS["~DESCRIPTION"] = $sotbitSeoMetaBottomDesc;

        if (strlen($sotbitSeoMetaAddDesc)>0)
            $ar_result["~UF_PHX_CTLG_PRTXT"] = $sotbitSeoMetaAddDesc;

        unset($GLOBALS["arrCatalogFilter"]);


        CPhoenixFunc::setInitialFilterParams('arrCatalogFilter');
    }

    $html = "";


    $GLOBALS["arrCatalogPreFilter"] = $GLOBALS["arrCatalogFilter"];


    ob_start();

    $tabFilter = "";

    if (!isset($_COOKIE[$domenUrlForCookie . "_phoenix_catalog_tab_catalog-filter" . SITE_ID])) {
        if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_SHOW']['VALUE']['ACTIVE'] == 'Y')
            $tabFilter = "active";
        else
            $tabFilter = "noactive";
    } else
        $tabFilter = $_COOKIE[$domenUrlForCookie . "_phoenix_catalog_tab_catalog-filter" . SITE_ID];



    $filterHidePrice = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["FILTER_HIDE_PRICE"]["VALUE"]["ACTIVE"];

    if ($filterHidePrice == "Y")
        $filterHidePrice = ($ar_result["UF_FILTER_HIDE_PRICE"]) ? "" : "Y";
    else
        $filterHidePrice = ($ar_result["UF_FILTER_HIDE_PRICE"]) ? "Y" : "";


    $APPLICATION->IncludeComponent(
        "bitrix:catalog.smart.filter",
        "main",
        array(
            "FILTER_HIDE_PRICE" => $filterHidePrice,
            "DATA_SHOW" => "catalog-filter",
            "PREFILTER_NAME" => "arrCatalogPreFilter",
            "FILTER_NAME" => "arrCatalogFilter",
            "TAB_FILTER" => $tabFilter,
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_FILTER" => $arParams["CACHE_FILTER"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "CACHE_NOTES" => "",
            "COMPOSITE_FRAME_MODE" => "N",
            'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
            "CONVERT_CURRENCY" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
            "DISPLAY_ELEMENT_COUNT" => "Y",

            "FILTER_VIEW_MODE" => "vertical",
            "HIDE_NOT_AVAILABLE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"],
            'HIDE_NOT_AVAILABLE_OFFERS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"],
            "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_ID"],
            "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_TYPE"],
            "PAGER_PARAMS_NAME" => "arrPager",
            "PRICE_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
            "SAVE_IN_SESSION" => "N",
            "SECTION_CODE" => "",
            "SECTION_DESCRIPTION" => "-",
            "SECTION_ID" => $ar_result["ID"],
            "SECTION_TITLE" => "-",
            "SEF_MODE" => "Y",
            "TEMPLATE_THEME" => "blue",
            "XML_EXPORT" => "Y",
            "INSTANT_RELOAD" => "Y",
            "SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"] . "#actionbox",
            "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
        ),
        $component
    );

    $html = ob_get_clean();

    $APPLICATION->AddViewContent('filter_content', $html);
}


if (strlen($ar_result["UF_PHX_BNRS_VIEW"]) > 0) 
    $ar_result["UF_PHX_BNRS_VIEW_ENUM"] = CUserFieldEnum::GetList(array(), array("ID" => $ar_result["UF_PHX_BNRS_VIEW"]))->GetNext();


if (strlen($ar_result["UF_PHX_BNRS_VIEW_ENUM"]["XML_ID"]) <= 0)
    $ar_result["UF_PHX_BNRS_VIEW_ENUM"]["XML_ID"] = "none";

if (strlen($ar_result["UF_EMPL_BANNER_TYPE"]) > 0) {
    $ar_result["UF_EMPL_BANNER_TYPE_ENUM"] = CUserFieldEnum::GetList(array(), array(
        "ID" => $ar_result["UF_EMPL_BANNER_TYPE"],
    ))->GetNext();
} else
    $ar_result["UF_EMPL_BANNER_TYPE_ENUM"]["XML_ID"] = "none";

if (strlen($ar_result["UF_PHX_CTLG_TXT_P"]) > 0) {
    $ar_result["UF_PHX_CTLG_TXT_P_ENUM"] = CUserFieldEnum::GetList(array(), array(
        "ID" => $ar_result["UF_PHX_CTLG_TXT_P"],
    ))->GetNext();
}

if (strlen($ar_result["UF_PHX_CTLG_TXT_P_ENUM"]["XML_ID"]) <= 0)
    $ar_result["UF_PHX_CTLG_TXT_P_ENUM"]["XML_ID"] = "short";


if (strlen($ar_result["UF_VIEW_SUBSECTIONS"]) > 0) {
    $ar_result["UF_VIEW_SUBSECTIONS_ENUM"] = CUserFieldEnum::GetList(array(), array(
        "ID" => $ar_result["UF_VIEW_SUBSECTIONS"],
    ))->GetNext();
}

if (strlen($ar_result["UF_VIEW_SUBSECTIONS_ENUM"]["XML_ID"]) <= 0)
    $ar_result["UF_VIEW_SUBSECTIONS_ENUM"]["XML_ID"] = "in_head";


$header_back = "";

if ($ar_result["DETAIL_PICTURE"] > 0) {
    $img = CFile::ResizeImageGet($ar_result["DETAIL_PICTURE"], array('width' => 3000, 'height' => 1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
    $header_back = $img["src"];
}
$img = null;

$pictureInHead = "";
if ($ar_result["UF_PHX_PICT_IN_HEAD"] > 0) {
    $img = CFile::ResizeImageGet($ar_result["UF_PHX_PICT_IN_HEAD"], array('width' => 558, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
    $pictureInHead = $img["src"];
}
$img = null;

$arResult["BANNERS_LEFT"] = array();

if (!empty($ar_result["UF_PHX_CTLG_BNNRS"]) && $ar_result["UF_PHX_BNRS_VIEW_ENUM"]["XML_ID"] == "own")
    $arResult["BANNERS_LEFT"] = $ar_result["UF_PHX_CTLG_BNNRS"];


$arResult["EMPL_BANNER"] = array();

if (!empty($ar_result["UF_EMPL_BANNER"]) && $ar_result["UF_EMPL_BANNER_TYPE_ENUM"]["XML_ID"] == "own")
    $arResult["EMPL_BANNER"] = $ar_result["UF_EMPL_BANNER"];


$parent_section_id = intval($ar_result["IBLOCK_SECTION_ID"]);

while ($parent_section_id != 0) {
    if ($parent_section_id < 0)
        break;

    $arSelect = array("ID", "DETAIL_PICTURE", "IBLOCK_SECTION_ID", "UF_*");
    $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "ID" => $parent_section_id);
    $db_list = CIBlockSection::GetList(array(), $arFilter, false, $arSelect);

    while ($ar_res = $db_list->GetNext()) {


        if ($ar_result["UF_PHX_BNRS_VIEW_ENUM"]["XML_ID"] == "parent") {
            if (empty($arResult["BANNERS_LEFT"]))
                $arResult["BANNERS_LEFT"] = $ar_res["UF_PHX_CTLG_BNNRS"];
        }

        if ($ar_result["UF_EMPL_BANNER_TYPE_ENUM"]["XML_ID"] == "parent") {
            if (empty($arResult["UF_EMPL_BANNER"]))
                $arResult["EMPL_BANNER"] = $ar_res["UF_EMPL_BANNER"];
        }


        if (strlen($header_back) <= 0) {
            if ($ar_res["DETAIL_PICTURE"] > 0) {
                $img = CFile::ResizeImageGet($ar_res["DETAIL_PICTURE"], array('width' => 3000, 'height' => 1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
                $header_back = $img["src"];
            }
        }


        $parent_section_id = intval($ar_res["IBLOCK_SECTION_ID"]);
    }
}





if (strlen($header_back) <= 0 && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HEAD_BG_PIC"]["VALUE"] > 0) {
    $img = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HEAD_BG_PIC"]["VALUE"], array('width' => 3000, 'height' => 1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
    $header_back = $img["src"];
}




$colsLeft = "col-12 empty-part-right";
$colsRight = "d-none";

$alignSubsections = "justify-content-center";

$pictureInHeadIsset = strlen($pictureInHead) ? true : false;

if (strlen($pictureInHeadIsset)) {
    $colsLeft = "col-lg-8 col-12";
    $colsRight = "col-lg-4 col-12";
    $alignSubsections = "";
}


?>

<? $GLOBALS["PHOENIX_CURRENT_DIR"] = "section"; ?>
<? $GLOBALS["PHOENIX_CURRENT_SECTION_ID"] = $ar_result["ID"]; ?>
<? $GLOBALS["PHOENIX_CURRENT_TMPL"] = $ar_result["UF_PHX_CTLG_TMPL_ENUM"]["XML_ID"]; ?>




<? if ($ar_result["UF_PHX_CTLG_TMPL_ENUM"]["XML_ID"] == "default") : ?>
    <? $GLOBALS["IS_CONSTRUCTOR"] = false; ?>
    <?


    $sideColumn = "Y";
    if (isset($_COOKIE[$domenUrlForCookie . "_phoenix_catalog_side_column_" . SITE_ID]))
        $sideColumn = $_COOKIE[$domenUrlForCookie . "_phoenix_catalog_side_column_" . SITE_ID];


    ?>

    <? if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES_MODE"]["VALUE"] == "custom" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"]) : ?>
        <?
        $img = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"], array('width' => 800, 'height' => 900), BX_RESIZE_IMAGE_PROPORTIONAL, false);
        $header_back_xs = $img["src"];
        ?>
        <style>
            @media (max-width: 767.98px) {
                div.page-header {
                    background-image: url('<?= $header_back_xs ?>') !important;
                }
            }
        </style>
    <? endif; ?>

    <div class="
                page-header
                section
                section-catalog
                padding-bottom-section
                cover
                <?= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"] ?>
                phoenix-firsttype-<?= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"] ?>
                <?= ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES_MODE"]["VALUE"] == "custom" && !$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"]) ? "def-bg-xs" : ""; ?>
                ctlg
            " 
            <? if (strlen($header_back) > 0) : ?> data-src="<?= $header_back ?>" style="background-image: url(<?= $header_back ?>);" <? endif; ?>>

        <div class="shadow-tone <?= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"] ?>"></div>
        <div class="top-shadow"></div>

        <div class="container z-i-9">
            <div class="row">

                <div class="<?= $colsLeft ?> part part-left">

                    <div class="head margin-bottom">

                        <? $APPLICATION->IncludeComponent(
                            "bitrix:breadcrumb",
                            "breadcrumbs",
                            array(
                                "COMPONENT_TEMPLATE" => ".default",
                                "START_FROM" => "0",
                                "PATH" => "",
                                "SITE_ID" => SITE_ID,
                                "COMPOSITE_FRAME_MODE" => "N",
                            ),
                            $component
                        ); ?>


                        <div class="title main1">
                            <h1>
                                <? $APPLICATION->ShowTitle(false); ?>
                            </h1>
                        </div>

                        <? if (strlen($ar_result["~UF_PHX_CTLG_PRTXT"]) > 0 && $ar_result["UF_VIEW_SUBSECTIONS_ENUM"]["XML_ID"] == "in_head") : ?>
                            <div class="subtitle"><?= $ar_result["~UF_PHX_CTLG_PRTXT"] ?></div>
                        <? endif; ?>

                    </div>

                    <? if ($ar_result["UF_VIEW_SUBSECTIONS_ENUM"]["XML_ID"] == "in_head") : ?>

                        <div class="hidden-sm hidden-xs">

                            <? $APPLICATION->IncludeComponent(
                                "bitrix:catalog.section.list",
                                "subsections",
                                array(
                                    "REGION_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"],
                                    "SECTION_USER_FIELDS" => array("UF_PHX_MENU_PICT"),
                                    "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_ID"],
                                    "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_TYPE"],

                                    "SECTION_ID" => $ar_result["ID"],

                                    "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                                    "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                    "COUNT_ELEMENTS" => "Y",
                                    "TOP_DEPTH" => 1,
                                    "VIEW_MODE" => "",
                                    "SHOW_PARENT_NAME" => "Y",
                                    "HIDE_SECTION_NAME" => "N",
                                    "ADD_SECTIONS_CHAIN" => "N",
                                    "COMPOSITE_FRAME_MODE" => "N",

                                    "ALIGN" => $alignSubsections,
                                    "PICT_IN_HEAD_ISSET" => $pictureInHeadIsset,
                                    "VIEW" => $ar_result["UF_VIEW_SUBSECTIONS_ENUM"]["XML_ID"],
                                    "ANIMATE_MODE" => $ar_result["UF_ANIMATE_HEAD"],
                                ),
                                $component,
                                array("HIDE_ICONS" => "Y")
                            ); ?>

                        </div>
                    <? endif; ?>

                    <div class="visible-sm visible-xs">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:catalog.section.list",
                            "subsections-slider",
                            array(
                                "REGION_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"],
                                "SECTION_USER_FIELDS" => array("UF_PHX_MENU_PICT"),
                                "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_ID"],
                                "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_TYPE"],

                                "SECTION_ID" => $ar_result["ID"],

                                "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                                "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                                "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                "COUNT_ELEMENTS" => "Y",
                                "TOP_DEPTH" => 1,
                                "VIEW_MODE" => "",
                                "SHOW_PARENT_NAME" => "Y",
                                "HIDE_SECTION_NAME" => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "COMPOSITE_FRAME_MODE" => "N",

                            ),
                            $component,
                            array("HIDE_ICONS" => "Y")
                        ); ?>
                    </div>

                </div>

                <? if ($pictureInHeadIsset || strlen($sotbitSeoMetaFile) > 0) : ?>

                    <div class="hidden-md hidden-sm hidden-xs <?= $colsRight ?> part part-right 
                <? if ($ar_result[" UF_ANIMATE_HEAD"]) : ?>
                wow concept-slideInUp
                <? endif; ?>" <? if ($ar_result["UF_ANIMATE_HEAD"]) : ?> data-wow-offset="100" data-wow-duration="1s" data-wow-delay="0.1s" <? endif; ?>>

                        <? if (strlen($sotbitSeoMetaFile) > 0) : ?>
                            <?= $sotbitSeoMetaFile ?>
                        <? else : ?>
                            <img class="img-fluid pictureInHead " src="<?= $pictureInHead ?>" alt="<?=CPhoenix::prepareText($ar_result["NAME"])?>">
                        <? endif; ?>
                    </div>

                <? endif; ?>


            </div>
        </div>

    </div>

    <? //\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("section-list");
    ?>

    <div class="catalog-list-wrap z-index-99 page_pad_bot parent-hide-column <?= $sideColumn == 'Y' ? '' : 'hide'; ?>">

        <div class="container">


            <div class="block-move-to-up">

                <div class="section-control-view hidden-md hidden-sm hidden-xs">

                    <? if ($ar_result["UF_VIEW_SUBSECTIONS_ENUM"]["XML_ID"] == "in_content") : ?>
                        <?
                        $APPLICATION->IncludeComponent(
                            "bitrix:catalog.section.list",
                            "subsections",
                            array(
                                "REGION_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"],
                                "SECTION_ID" => $ar_result["ID"],
                                "SECTION_USER_FIELDS" => array("UF_PHX_MENU_PICT"),

                                "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_ID"],
                                "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_TYPE"],

                                "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                                "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],

                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                                "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                "COUNT_ELEMENTS" => "Y",
                                "TOP_DEPTH" => 1,
                                "VIEW_MODE" => "",
                                "SHOW_PARENT_NAME" => "Y",
                                "HIDE_SECTION_NAME" => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "COMPOSITE_FRAME_MODE" => "N",


                                "VIEW" => $ar_result["UF_VIEW_SUBSECTIONS_ENUM"]["XML_ID"],
                                "SUBTITLE" => $ar_result["~UF_PHX_CTLG_PRTXT"],
                            ),
                            $component,
                            array("HIDE_ICONS" => "Y")
                        );
                        ?>
                    <? endif; ?>

                    <div class="row align-items-center padding-for-actionbox" id="actionbox">


                        <div class="col-xl-3 col-4 hidden-md hidden-sm hidden-xs">
                            <div class="control-column column-1">
                                <div class="switch-toogle <?= $sideColumn == 'Y' ? 'active' : ''; ?> btn-hide-column clearfix">
                                    <div class="btn-toogle"><span class="circle"></span></div>
                                    <div class="wrapper-desc">
                                        <div class="desc desc-hide"><?= $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_HIDE_COLUMN"] ?></div>
                                        <div class="desc desc-show"><?= $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_SHOW_COLUMN"] ?></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-xl-9 col-lg-8 col-12">
                            <div class="row">
                                <div class="col-xl-10 col-md-9 col-8 column-2">
                                    <? include("sort.php"); ?>


                                    <? if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_IN_STOCK']['VALUE']['ACTIVE'] == "Y") : ?>

                                        <div class="available-wrapper <? $APPLICATION->ShowViewContent('available-show'); ?>">

                                            <?
                                                $available = "Y";

                                                if (strlen($_REQUEST["available"]) > 0) {
                                                    $available = $_REQUEST["available"];
                                                    $_SESSION["available"] = $_REQUEST["available"];
                                                }
                                                else if($_SESSION["available"])
                                                {
                                                    $available = $_SESSION["available"];
                                                }

                                                if ($available == "Y")
                                                    $available = "N";

                                                else
                                                    $available = "Y";

                                                if ($available == "N")
                                                {
                                                    $GLOBALS["arrCatalogFilter"]["!PROPERTY_MODE_ARCHIVE_VALUE"] = 'Y';
                                                    $GLOBALS["arrCatalogFilter"][">CATALOG_QUANTITY"] = 0;
                                                }

                                            ?>

                                            <a class="checkbox-available sort-available-js <?= ($available == "N") ? 'active' : ''; ?>" data-available="<?= $available ?>">
                                                <?= $PHOENIX_TEMPLATE_ARRAY["MESS"]["FILTER_AVAILABLE"] ?>
                                            </a>
                                        </div>

                                    <? endif; ?>
                                </div>

                                <div class="col-xl-2 col-md-3 col-4 column-3 hidden-sm hidden-xs">
                                    <? include("view.php"); ?>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="position-relative">

                    <div class="row">

                        <div class="col-xl-3 col-lg-4 col-10 col-hide-column position-static wr-filter-side">

                            <div class="side-inner">

                                <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SORT_LEFT_SIDE']['VALUE'] === 'filter'):?>

                                    <? $APPLICATION->ShowViewContent('filter_content'); ?>


                                    <div class="hidden-md hidden-sm hidden-xs">
                                        <?

                                        if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TAB_SECTIONS_SHOW']["VALUE"]['ACTIVE'] == 'Y')
                                            $tab = "active";
                                        else
                                            $tab = "noactive";

                                        $APPLICATION->IncludeComponent(
                                            "bitrix:catalog.section.list",
                                            "mainsections",
                                            array(
                                                "TAB" => $tab,
                                                "SECTION_ID" => $ar_result["IBLOCK_SECTION_ID"],

                                                "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_ID"],
                                                "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_TYPE"],

                                                "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                                                "REGION_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"],

                                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                                "COUNT_ELEMENTS" => "N",
                                                "TOP_DEPTH" => 1,
                                                "VIEW_MODE" => "",
                                                "SHOW_PARENT_NAME" => "Y",
                                                "HIDE_SECTION_NAME" => "N",
                                                "ADD_SECTIONS_CHAIN" => "N",
                                                "COMPOSITE_FRAME_MODE" => "N",
                                            ),
                                            $component,
                                            array("HIDE_ICONS" => "Y")
                                        );

                                        ?>

                                    </div>

                                <?else:?>

                                    <div class="hidden-md hidden-sm hidden-xs">

                                        <?

                                            if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TAB_SECTIONS_SHOW']["VALUE"]['ACTIVE'] == 'Y')
                                                $tab = "active";
                                            else
                                                $tab = "noactive";

                                            $APPLICATION->IncludeComponent(
                                                "bitrix:catalog.section.list",
                                                "mainsections",
                                                array(
                                                    "TAB" => $tab,
                                                    "SECTION_ID" => $ar_result["IBLOCK_SECTION_ID"],

                                                    "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_ID"],
                                                    "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_TYPE"],

                                                    "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                                                    "REGION_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"],

                                                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                                    "COUNT_ELEMENTS" => "N",
                                                    "TOP_DEPTH" => 1,
                                                    "VIEW_MODE" => "",
                                                    "SHOW_PARENT_NAME" => "Y",
                                                    "HIDE_SECTION_NAME" => "N",
                                                    "ADD_SECTIONS_CHAIN" => "N",
                                                    "COMPOSITE_FRAME_MODE" => "N",
                                                ),
                                                $component,
                                                array("HIDE_ICONS" => "Y")
                                            );

                                        ?>
                                    </div>

                                    <? $APPLICATION->ShowViewContent('filter_content'); ?>


                                <?endif;?>

                                <?if($ar_result["UF_SIDEMENU_HTML"]):?>
                                    <div class="sidemenuHTML"><?=$ar_result["~UF_SIDEMENU_HTML"]?></div>
                                <?endif;?>

                                <div class="hidden-md hidden-sm hidden-xs">
                                    <? if (!empty($arResult["EMPL_BANNER"]) > 0) : ?>

                                        <? $APPLICATION->IncludeComponent(
                                            "concept:phoenix.news-list",
                                            "empl",
                                            array(
                                                "COMPOSITE_FRAME_MODE" => "N",
                                                "ELEMENTS_ID" => $arResult["EMPL_BANNER"],
                                                "VIEW" => "flat-banner",
                                                "COLS" => "col-12",
                                                "SORT_BY1" => "SORT",
                                                "SORT_ORDER1" => "ASC",
                                            ),
                                            $component
                                        ); ?>

                                    <? endif; ?>



                                    <?if(!empty($arResult["BANNERS_LEFT"]) > 0):?>

                                        <? $GLOBALS["arrBannersFilter"]["ID"] = $arResult["BANNERS_LEFT"]; ?>

                                        <? $APPLICATION->IncludeComponent(
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
                                                "MESSAGE_404" => "",
                                            ),
                                            $component
                                        ); ?>

                                    <? endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-2 d-lg-none wr-sort-btn-side">

                            <div class="btn-show-sort-board">
                                <div class="sort-dialog-content">


                                    <? include("sort.php"); ?>

                                    <? if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_IN_STOCK']['VALUE']['ACTIVE'] == "Y") : ?>

                                        <div class="available-wrapper <? $APPLICATION->ShowViewContent('available-show'); ?>">

                                            <?
                                                $available = "Y";

                                                if (strlen($_REQUEST["available"]) > 0) {
                                                    $available = $_REQUEST["available"];
                                                    $_SESSION["available"] = $_REQUEST["available"];
                                                }
                                                else if($_SESSION["available"])
                                                {
                                                    $available = $_SESSION["available"];
                                                }

                                                if ($available == "Y")
                                                    $available = "N";

                                                else
                                                    $available = "Y";

                                                if ($available == "N")
                                                {
                                                    $GLOBALS["arrCatalogFilter"]["!PROPERTY_MODE_ARCHIVE_VALUE"] = 'Y';
                                                    $GLOBALS["arrCatalogFilter"][">CATALOG_QUANTITY"] = 0;
                                                }

                                            ?>

                                            <a class="checkbox-available sort-available-js <?= ($available == "N") ? 'active' : ''; ?>" data-available="<?= $available ?>">
                                                <?= $PHOENIX_TEMPLATE_ARRAY["MESS"]["FILTER_AVAILABLE"] ?>

                                            </a>
                                        </div>
                                    <?endif;?>
                                </div>
                            </div>

                        </div>


                        <div class="col-xl-9 col-lg-8 col-12 content-inner page content-column">

                            <div class="block small-block">

                                <? $APPLICATION->ShowViewContent('catalog-top-desc'); ?>


                                <div class="element-list-wrap active">

                                    <?

                                    $isAjax = ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["ajax_action"]) && $_POST["ajax_action"] == "Y");


                                    if ($isAjax) {
                                        $APPLICATION->RestartBuffer();
                                    }



                                    $intSectionID = $APPLICATION->IncludeComponent(
                                        "bitrix:catalog.section",
                                        "main",
                                        array(

                                            "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                                            "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
                                            "SECTION_ID" => $ar_result["ID"],
                                            "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                                            "INCLUDE_SUBSECTIONS" => ($ar_result["UF_HIDE_SUBSECTIONS"]) ? "N" : "A",

                                            "FILTER_NAME" => "arrCatalogFilter",
                                            "ELEMENT_SORT_FIELD" => $sort1,
                                            "ELEMENT_SORT_ORDER" => $sort_order1,
                                            "ELEMENT_SORT_FIELD2" => $sort2,
                                            "ELEMENT_SORT_ORDER2" => $sort_order2,
                                            "PAGE_ELEMENT_COUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["NEWS_COUNT"]["VALUE"],

                                            "OBJ_NAME" => "section_" . $arResult["ID"],
                                            'VIEW' => $view,
                                            "COLS" => $sideColumn == 'Y' ? '3' : '4',
                                            "COLS_LG" => $sideColumn == 'Y' ? '3' : '4',


                                            'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
                                            'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
                                            "PRICE_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
                                            "OFFERS_CART_PROPERTIES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                            "OFFERS_PROPERTY_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                            'OFFER_TREE_PROPS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                            "USE_PRICE_COUNT" => "Y",
                                            "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_TYPE"],
                                            "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],


                                            'COMPARE_PATH' => '',


                                            "WITH_HIDE_COLUMN" => "Y",
                                            "FROM" => "section",
                                            "OFFERS_FIELD_CODE" => array("NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_TEXT", "DETAIL_PICTURE", ""),
                                            "OFFERS_SORT_FIELD" => "sort",
                                            "OFFERS_SORT_ORDER" => "id",
                                            "OFFERS_SORT_FIELD2" => "asc",
                                            "OFFERS_SORT_ORDER2" => "asc",
                                            "OFFERS_LIMIT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["OFFERS_QUANTITY"]["VALUE_RESULT"],
                                            "USE_PRODUCT_QUANTITY" => "Y",
                                            "SHOW_PRICE_COUNT" => "1",
                                            'SHOW_OLD_PRICE' => "Y",
                                            'SHOW_MAX_QUANTITY' => "Y",
                                            "PRICE_VAT_INCLUDE" => "Y",
                                            'SHOW_DISCOUNT_PERCENT' => "Y",
                                            "PAGER_SHOW_ALL" => "N",
                                            "HIDE_NOT_AVAILABLE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"],
                                            'HIDE_NOT_AVAILABLE_OFFERS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"],
                                            "ADD_SECTIONS_CHAIN" => "Y",

                                            "BASKET_URL" => "",
                                            "PROPERTY_CODE" => array(""),
                                            "PROPERTY_CODE_MOBILE" => "",

                                            "META_KEYWORDS" => '',
                                            "META_DESCRIPTION" => '',
                                            "BROWSER_TITLE" => '',
                                            "SET_LAST_MODIFIED" => '',
                                            "ACTION_VARIABLE" => '',
                                            "PRODUCT_ID_VARIABLE" => '',
                                            "SECTION_ID_VARIABLE" => '',
                                            "PRODUCT_QUANTITY_VARIABLE" => '',
                                            "PRODUCT_PROPS_VARIABLE" => '',
                                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                                            "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                            'SET_TITLE' => '',
                                            'SET_STATUS_404' => 'Y',
                                            'MESSAGE_404' => '',
                                            'SHOW_404' => '',
                                            'FILE_404' => '',
                                            "DISPLAY_COMPARE" => '',


                                            "LINE_ELEMENT_COUNT" => '',

                                            "ADD_PROPERTIES_TO_BASKET" => "Y",
                                            "PARTIAL_PRODUCT_PROPERTIES" => '',
                                            "PRODUCT_PROPERTIES" => '',
                                            "DISPLAY_TOP_PAGER" => '',
                                            "DISPLAY_BOTTOM_PAGER" => 'Y',
                                            "PAGER_TITLE" => '',
                                            "PAGER_SHOW_ALWAYS" => '',
                                            "PAGER_TEMPLATE" => "phoenix_round",
                                            "PAGER_DESC_NUMBERING" => '',
                                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",

                                            "PAGER_BASE_LINK_ENABLE" => '',
                                            "PAGER_BASE_LINK" => '',
                                            "PAGER_PARAMS_NAME" => '',
                                            "LAZY_LOAD" => '',
                                            "MESS_BTN_LAZY_LOAD" => '',
                                            "LOAD_ON_SCROLL" => '',
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
                                            'DISCOUNT_PERCENT_POSITION' => '',
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
                                            'BRAND_PROPERTY' => '',
                                            'TEMPLATE_THEME' => '',
                                            'ADD_TO_BASKET_ACTION' => '',
                                            'SHOW_CLOSE_POPUP' => '',
                                            'COMPARE_NAME' => '',
                                            'BACKGROUND_IMAGE' => '',
                                            'COMPATIBLE_MODE' => 'Y',
                                            'DISABLE_INIT_JS_IN_COMPONENT' => '',
                                            "COMPOSITE_FRAME_MODE" => "N",

                                        ),
                                        $component
                                    );

                                    if ($isAjax) {
                                        die();
                                    }
                                    ?>
                        
                                    <? $GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID; ?>

                                </div>


                                <? if ($ar_result["UF_PHX_CTLG_TXT_P_ENUM"]["XML_ID"] == "short") : ?>
                                    <? $APPLICATION->ShowViewContent('catalog-bottom-desc'); ?>
                                <? endif; ?>

                            </div>

                        </div>


                    </div>

                </div>

            </div>
        </div>

    </div>


    <? //\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("section-list");
    ?>


    <? if ($ar_result["UF_PHX_CTLG_TXT_P_ENUM"]["XML_ID"] == "long") : ?>
        <? $APPLICATION->ShowViewContent('catalog-bottom-desc'); ?>
    <? endif; ?>

    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["CATALOG"] === 'Y'):?>
        <div class="catalog-stories-ajax catalog-list-page" data-count="4"></div>
    <?endif;?>



<? endif; ?>


<? if ($ar_result["UF_PHX_CTLG_TMPL_ENUM"]["XML_ID"] == "landing") : ?>



    <? if ($ar_result["UF_PHX_CTLG_T_ID"] > 0) : ?>

        <?
        $arFilter = array("ID" => $ar_result["UF_PHX_CTLG_T_ID"]);
        $db_list = CIBlockSection::GetList(array(), $arFilter, false);
        $ar_res = $db_list->GetNext();
        ?>

        <? if ($ar_res["ACTIVE"] == "Y") : ?>
            <? $GLOBALS["IS_CONSTRUCTOR"] = true; ?>
            <? $section = $APPLICATION->IncludeComponent(
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
            ); ?>

            <? $GLOBALS["PHOENIX_CURRENT_SECTION_ID"] = $section; ?>



        <? else : ?>

            <?
            if (!defined("ERROR_404"))
                define("ERROR_404", "Y");

            \CHTTP::setStatus("404 Not Found");

            if ($APPLICATION->RestartWorkarea()) {
                require(\Bitrix\Main\Application::getDocumentRoot() . "/404.php");
                die();
            }


            ?>

        <? endif; ?>

    <? endif; ?>

<? endif; ?>

<? unset($colsLeft, $colsRight, $alignSubsections, $pictureInHeadIsset, $header_back, $img, $parent_section_id); ?>



<? if (\Bitrix\Main\Loader::includeModule("sotbit.seometa")) : ?>



    <?
    //sotbit seometa meta start
    global $sotbitSeoMetaTitle;
    global $sotbitSeoMetaKeywords;
    global $sotbitSeoMetaDescription;
    global $sotbitSeoMetaBreadcrumbTitle;
    global $sotbitSeoMetaH1;

    if (!empty($sotbitSeoMetaH1)) {
        $APPLICATION->SetTitle($sotbitSeoMetaH1);
        $GLOBALS["H1"] = $sotbitSeoMetaH1;
    }

    if (!empty($sotbitSeoMetaTitle)) {
        $APPLICATION->SetPageProperty("title", $sotbitSeoMetaTitle);
        $GLOBALS["TITLE"] = $sotbitSeoMetaTitle;
    }

    if (!empty($sotbitSeoMetaKeywords)) {
        $APPLICATION->SetPageProperty("keywords", $sotbitSeoMetaKeywords);
        $GLOBALS["KEYWORDS"] = $sotbitSeoMetaKeywords;
    }

    if (!empty($sotbitSeoMetaDescription)) {
        $APPLICATION->SetPageProperty("description", $sotbitSeoMetaDescription);
        $GLOBALS["DESCRIPTION"] = $sotbitSeoMetaDescription;
    }

    if (!empty($sotbitSeoMetaBreadcrumbTitle)) {
        $APPLICATION->AddChainItem($sotbitSeoMetaBreadcrumbTitle);
    }
    //sotbit seometa meta end
    ?>

<? endif; ?>