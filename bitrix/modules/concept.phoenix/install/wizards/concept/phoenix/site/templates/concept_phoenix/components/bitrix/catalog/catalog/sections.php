<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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
?>

<?global $PHOENIX_TEMPLATE_ARRAY;?>

<?
use Bitrix\Catalog\CatalogViewedProductTable;
use Bitrix\Sale\Fuser;


$currentMainPageForSearch = CPhoenix::GetCurrentMainPageCodeForSearch($arParams["IBLOCK_ID"]);
$header_back = "";


if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HEAD_BG_PIC"]["VALUE"] > 0)
{
    
    $img = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HEAD_BG_PIC"]["VALUE"], array('width'=>2560, 'height'=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);  
    $header_back = $img["src"];
}

$colsLeft = "col-md-7 col-12";
$colsRight = "col-md-5 col-12";


?>

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
			sections
            <?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['SHOW_IN']['VALUE'][$currentMainPageForSearch] == "Y")? "padding-bottom-section":""?>
			cover
			parent-scroll-down
			<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>
			phoenix-firsttype-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
            <?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES_MODE"]["VALUE"] == "custom" && !$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"]) ? "def-bg-xs" : "";?>
            ctlg
		" 
	<?if(strlen($header_back) > 0):?>
        style="background-image: url(<?=$header_back?>);"
    <?endif;?>
>


    
    <div class="shadow-tone <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>"></div>
    <div class="top-shadow"></div>
    
    <div class="container z-i-9">
        <div class="row">
                                    
            <div class="<?=$colsLeft?> part part-left">
                
                <div class="head margin-bottom">
                    
                    <?/*$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumbs", Array(
                            "COMPONENT_TEMPLATE" => ".default",
                    		"START_FROM" => "0",
                    		"PATH" => "",
                    		"SITE_ID" => SITE_ID,
                    		"COMPOSITE_FRAME_MODE" => "A",
                    		"COMPOSITE_FRAME_TYPE" => "AUTO",
                    	),
                    	$component
                    );*/?>
                
                    
                    <div class="title main1"><h1><?$APPLICATION->ShowTitle(false);?></h1></div>

                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBTITLE"]["VALUE"]) > 0):?>
                        <div class="subtitle"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBTITLE"]["~VALUE"]?></div>
                    <?endif;?>
                                                                    
                </div>
                
            </div>
            

            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["PIC_IN_HEAD"]["VALUE"])):?>
                <div class="<?=$colsRight?> part part-right">

                    <?$pic = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["PIC_IN_HEAD"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>

                    <img class = "lazyload d-block mx-auto" data-src="<?=$pic["src"]?>" alt="">
                </div>

            <?else:?>
            
                <div class="<?=$colsRight?> part part-right">

        			<div class="wrap-scroll-down hidden-sm hidden-xs">
                        <div class="down-scrollBig scroll-down">
                            <i class="fa fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

            <?endif;?>
                
    		

        </div>

        <?include("search.php");?>
    </div>
                                        
</div>

<div class="container">
    <div class="catalog-main-page block-move-to-up">
    </div>
</div>

<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TOP_TEXT"]["~VALUE"])>0):?>

    <div class="catalog-top-description text-content">

        <div class="container">
            
            <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TOP_TEXT"]["~VALUE"]?>
        
        </div>
            

    </div>

<?endif;?>



<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("component-sections-list");?>
<?
$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"sections-list",
	array(
        "REGION_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"],
		"IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_ID"],
        "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_TYPE"],

		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_FILTER" => $arParams["CACHE_FILTER"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"COUNT_ELEMENTS" => "Y",
		"TOP_DEPTH" => 2,
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"VIEW_MODE" =>'',
		"SHOW_PARENT_NAME" => "Y",
		"HIDE_SECTION_NAME" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "COMPOSITE_FRAME_MODE" => "N",
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?>

<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("component-sections-list");?>

<?$GLOBALS["PHOENIX_CURRENT_SECTION_ID"] = 0;?>
<?$GLOBALS["PHOENIX_CURRENT_DIR"] = "main";?>


<?include("labels.php");?>



<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["BOT_TEXT"]["~VALUE"])>0):?>
    
    <div class="bottom-description-full text-content">
        <div class="container">
            <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["BOT_TEXT"]["~VALUE"]?>
        </div>

    </div>

<?endif;?>



<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["CATALOG"] === 'Y'):?>
	<?//\Bitrix\Catalog\CatalogViewedProductTable::clear(1);?>
    <div class="catalog-stories-ajax" data-count="4"></div>
<?endif;?>