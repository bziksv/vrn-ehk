<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
global $PHOENIX_TEMPLATE_ARRAY;


	$arrProps = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']['CODE_VALUE'];
	$arrSku = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']['CODE_VALUE'];

	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["BG_PIC"]["VALUE"])>0)
		$bg_pic = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["BG_PIC"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false);

	$colsLeft = "col-12";

?>

<div class=
	"
		page-header
		compare-header
		sections
		padding-bottom-section
		cover
		parent-scroll-down
		<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>
		phoenix-firsttype-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
		padding-bottom-section

	" 
		<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["BG_PIC"]["VALUE"])>0):?>

			<?/*data-src = "<?=$bg_pic["src"]?>"*/?>

			style="background-image: url(<?=$bg_pic["src"]?>);"

		<?endif;?>
	>

	<div class="shadow-tone <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>"></div>

	<div class="top-shadow"></div>

	<div class="container">

    	<div class="row">
    		<div class="<?=$colsLeft?> part part-left">

    			<div class="head">

	    			<div class="title main1"><h1><?$APPLICATION->ShowTitle(false);?></h1></div>

                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["DESC"]["VALUE"]) > 0):?>
                        <div class="subtitle hidden-sm hidden-xs"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["DESC"]["~VALUE"]?></div>
                    <?endif;?>

                </div>

    		</div>
                
    	</div>
    </div>


</div>


<div class="container">

	<div class="compare-page block-move-to-up">

	<?

		$APPLICATION->IncludeComponent(
			"bitrix:catalog.compare.result", 
			"compare_result", 
			array(
				"COMPONENT_TEMPLATE" => "compare_result",
				"NAME" => "CATALOG_COMPARE_LIST",
				"IBLOCK_TYPE" => "concept_phoenix_".SITE_ID,
				"IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
				"FIELD_CODE" => array(
					0 => "PREVIEW_PICTURE",
				),
				"PROPERTY_CODE" => $arrProps,
				"ELEMENT_SORT_FIELD" => "sort",
				"ELEMENT_SORT_ORDER" => "asc",
				"TEMPLATE_THEME" => "blue",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"DETAIL_URL" => "",
				"SECTION_ID_VARIABLE" => "SECTION_ID",
				"DISPLAY_ELEMENT_SELECT_BOX" => "N", 
				"ELEMENT_SORT_FIELD_BOX" => "name",
				"ELEMENT_SORT_ORDER_BOX" => "asc",
				"ELEMENT_SORT_FIELD_BOX2" => "id",
				"ELEMENT_SORT_ORDER_BOX2" => "desc",
				"HIDE_NOT_AVAILABLE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"],
				"COMPOSITE_FRAME_MODE" => "N",
				"ACTION_VARIABLE" => "action",
				"PRODUCT_ID_VARIABLE" => "id",
				"PRICE_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
				//"USE_PRICE_COUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == "Y" ? "Y" : "N",
				"SHOW_PRICE_COUNT" => "1",
				"PRICE_VAT_INCLUDE" => "Y",
				"CONVERT_CURRENCY" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
				"BASKET_URL" => "",
				"CURRENCY_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
				"OFFERS_FIELD_CODE" => array(
					0 => "",
					1 => "",
				),
				"OFFERS_PROPERTY_CODE" => $arrSku
			),
			$component
		);

	?>
	</div>

</div>

<?
	$GLOBALS["PHOENIX_CURRENT_PAGE"] = "compare";
	$GLOBALS["PHOENIX_CURRENT_DIR"] = "main";
?>