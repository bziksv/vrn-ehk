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
?>
<?global $PHOENIX_TEMPLATE_ARRAY;?>

<?
if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["BG_PIC"]["VALUE"])>0)
	$header_back = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["BG_PIC"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false);
	
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
			cover
			parent-scroll-down
			<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>
			phoenix-firsttype-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
			padding-bottom-section
			brands-list-header
			<?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES_MODE"]["VALUE"] == "custom" && !$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"]) ? "def-bg-xs" : "";?>
		" 
	<?if(strlen($header_back["src"])>0):?>
		data-src="<?=$header_back["src"]?>"
		style="background-image: url(<?=$header_back["src"]?>);"
	<?endif;?>
>

	<div class="shadow-tone <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>"></div>

    <div class="top-shadow"></div>

    <div class="container z-i-9">

    	<div class="row">
    		<div class="<?=$colsLeft?> part part-left">
	    			
    			<div class="head margin-bottom">

                    <div class="title main1"><h1><?$APPLICATION->ShowTitle(false);?></h1></div>

                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["DESC"]["VALUE"]) > 0):?>
                        <div class="subtitle"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["DESC"]["~VALUE"]?></div>
                    <?endif;?>

    			</div>

    		</div>

    		<div class="<?=$colsRight?> part part-right">
    			<div class="wrap-scroll-down hidden-xs hidden-xxs">
                    <div class="down-scrollBig scroll-down">
                        <i class="fa fa-chevron-down"></i>
                    </div>
                </div>
    		</div>


    	</div>

    	<?//include("search.php");?>
    </div>

</div>

<div class="news-list-wrap page_pad_bot brands-page">

    <div class="container">

    	<div class="block-move-to-up">

	        <div class="row">

	        	<div class="col-12">

	        		<div class="block small padding-on">

						<?$APPLICATION->IncludeComponent(
							"bitrix:news.list",
							"main",
							Array(

								"PAGER_TEMPLATE" => "phoenix_round",
								"DISPLAY_BOTTOM_PAGER" => "Y",
								"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
								"IBLOCK_ID" => $arParams["IBLOCK_ID"],
								"NEWS_COUNT" => $arParams["NEWS_COUNT"],
								"SORT_BY1" => $arParams["SORT_BY1"],
								"SORT_ORDER1" => $arParams["SORT_ORDER1"],
								"SORT_BY2" => $arParams["SORT_BY2"],
								"SORT_ORDER2" => $arParams["SORT_ORDER2"],
								"FIELD_CODE" => array("NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_TEXT", ""),
								"PROPERTY_CODE" => array("GALLERY_TITLE", "GALLERY_BORDER", "CERTIFICATE_TITLE", "CERTIFICATE_BORDER", "VIDEO_TITLE", "VIDEO_LINK", ""),
								"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
								"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
								"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
								"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
								"SET_TITLE" => $arParams["SET_TITLE"],
								"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
								"MESSAGE_404" => $arParams["MESSAGE_404"],
								"SET_STATUS_404" => $arParams["SET_STATUS_404"],
								"SHOW_404" => $arParams["SHOW_404"],
								"FILE_404" => $arParams["FILE_404"],
								"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"CACHE_FILTER" => $arParams["CACHE_FILTER"],
								"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
								"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
								"PAGER_TITLE" => $arParams["PAGER_TITLE"],
								"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
								"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
								"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
								"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
								"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
								"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
								"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
								"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
								"DISPLAY_NAME" => "Y",
								"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
								"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
								"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
								"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
								"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
								"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
								"FILTER_NAME" => $arParams["FILTER_NAME"],
								"HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
								"CHECK_DATES" => $arParams["CHECK_DATES"],
								"COMPOSITE_FRAME_MODE" => "N",
							),
							$component
						);?>

						<?$GLOBALS["PHOENIX_CURRENT_SECTION_ID"] = 0;?>
						<?$GLOBALS["PHOENIX_CURRENT_DIR"] = "main";?>

					</div>

					<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["SEO_TEXT"]["VALUE"])):?>

						<div class="block small text-content">
							<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["SEO_TEXT"]["~VALUE"]?>
						</div>

					<?endif;?>

				</div>

			</div>
		</div>
	</div>
</div>

<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["BRANDS"] === 'Y'):?>
    <div class="catalog-stories-ajax" data-count="4"></div>
<?endif;?>