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

if(!empty($arResult["SEARCH_ITEMS_ID"]))
	$GLOBALS['arFilterSearchCatalog'] = array("ID" => $arResult["SEARCH_ITEMS_ID"]);
?>
<?if( !empty($arResult["ITEMS"]) || !empty($arResult["SECTIONS"]) || !empty($arResult["BRANDS"]) ):?>
	<div class="ajax-search-results-row <?=$arParams["LINE_VIEW_SIZE"]?>">
		<?if(!empty($arResult["ITEMS"]) ):?>

			<?/*<div class="section-head"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_RESULT_GOODS"]?> (<?=$arParams["SEARCH_RESULT"]["COUNT_ELEMENTS"]?>)
		    </div>
		    */?>

			<div class="section-block-content goods">
				<div class="row">

					

					<?$intSectionID = $APPLICATION->IncludeComponent(
				        "bitrix:catalog.section",
				        "main.search.ajax",
				        array(
				        	'COLS_GOODS' => $arResult["COLS_GOODS"],
				        	'SEARCH_ELEMENTS_ID' => $arParams["SEARCH_RESULT"]["ITEMS_ID"],
				            'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
				            'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
				            "PAGE_ELEMENT_COUNT" => $arResult["PAGE_ELEMENT_COUNT"],
				            "PAGER_TEMPLATE" => "phoenix_round",
				            "FILTER_NAME" => "arFilterSearchCatalog",
				            "COMPOSITE_FRAME_MODE" => "N",
				            "ELEMENT_SORT_FIELD" => "ID",
				            "ELEMENT_SORT_ORDER" => $arParams["SEARCH_RESULT"]["ITEMS_ID"],
				            "ELEMENT_SORT_FIELD2" => "SORT",
				            "ELEMENT_SORT_ORDER2" => "ASC",
				            "OBJ_NAME" => $arResult["ID"],
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

				            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				            "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
				            
				            "OFFERS_SORT_FIELD" => "sort",
				            "OFFERS_SORT_ORDER" => "id",
				            "OFFERS_SORT_FIELD2" => "asc",
				            "OFFERS_SORT_ORDER2" => "asc",
				            "OFFERS_LIMIT" => "0",

				            "USE_PRICE_COUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == "Y" ? "Y" : "N",
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
				            "CACHE_FILTER" => "N",
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
				            "SET_TITLE" => "Y",
				            "SET_STATUS_404" => "Y",
				            "SHOW_404" => "Y",
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
				            
				        ),
				        $component
				    );
					?>

					<?if($arParams["SEARCH_RESULT"]["COUNT_ELEMENTS"] > $arResult["PAGE_ELEMENT_COUNT"]):?>
						<div class="<?=$arResult["COLS_GOODS"]?> align-self-center">
					        <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["CATALOG"]."/"?>?q=<?=$arParams["QUERY"]?>" target="_blank" class="button-def secondary <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> btn-show-all"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL_GOODS"]?>  <span>(<?=$arParams["SEARCH_RESULT"]["COUNT_ELEMENTS"]?>)</span></a>

				        </div>
				    <?endif;?>

				</div>
			</div>

		<?endif;?>


		<?if(!empty($arResult["SECTIONS"]) ):?>

			<div class="section-head">
				<div class="gr-line"></div>
				<div class="title"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_RESULT_SECTIONS"]?></div>
				
		    </div>

			<div class="section-block-content categories">
				<div class="row">

					<?foreach($arResult["SECTIONS"] as $keyItem=>$arItem):?>

		        		<?if(($keyItem+1) > $arResult["PAGE_ELEMENT_COUNT"])
		                    break;?>

		                <div class="<?=$arResult["COLS_ITEMS"]?>">
		                	<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="d-block search-item">
		                    	<table class="search-item">
		                    		<tr>
		                    			<td class="search-item-img">
		                    				<div class="search-item-img row no-gutters align-items-center">
		                    					<div class="col-12">
						                        	<img src="<?=$arItem['PREVIEW_PICTURE_SRC']?>" alt=""/>
						                        </div>
							                </div>
		                    			</td>
		                    			<td class="search-item-name">
		                    				<div class="search-item-name wspace-normal">
		                    					<?=strip_tags($arItem["~NAME"])?>
		                    				</div>
		                    			</td>
		                    		</tr>
		                    	</table>
		                	</a>
					    </div>
		            <?endforeach;?>

		            <?if($arParams["SEARCH_RESULT"]["COUNT_SECTIONS"] > $arResult["PAGE_ELEMENT_COUNT"]):?>
						<div class="<?=$arResult["COLS_ITEMS"]?> align-self-center">
					        <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["CATALOG"]."/"?>?q=<?=$arParams["QUERY"]?>" target="_blank" class="button-def secondary <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> btn-show-all"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL_CATEGORIES"]?>  <span>(<?=$arParams["SEARCH_RESULT"]["COUNT_SECTIONS"]?>)</span></a>

				        </div>
				    <?endif;?>

				</div>
			</div>
			
		<?endif;?>
		

		<?if(!empty($arResult["BRANDS"]) ):?>

			<div class="section-head">
				<div class="gr-line"></div>
				<div class="title"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_BRANDS"]?></div>
				
		    </div>

			<div class="section-block-content brands">
				<div class="row">

					<?foreach($arResult["BRANDS"] as $keyItem=>$arItem):?>

		        		<?if(($keyItem+1) > $arResult["PAGE_ELEMENT_COUNT"])
		                    break;?>

		                <div class="<?=$arResult["COLS_ITEMS"]?>">
		                	<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="d-block search-item">
		                    	<table class="search-item">
		                    		<tr>
		                    			<td class="search-item-img">
		                    				<div class="search-item-img row no-gutters align-items-center big-size" title = "<?=strip_tags($arItem["~NAME"])?>">
		                    					<div class="col-12">
					                        		<img src="<?=$arItem['PREVIEW_PICTURE_SRC']?>" alt=""/>
					                        	</div>
							                </div>
		                    			</td>
		                    			<?/*<td class="search-item-name">
		                    				<div class="search-item-name">
		                    					<?=strip_tags($arItem["~NAME"])?>
		                    				</div>
		                    			</td>*/?>
		                    		</tr>
		                    	</table>
		                	</a>
					    </div>
		            <?endforeach;?>

		            <?if($arParams["SEARCH_RESULT"]["COUNT_BRANDS"] > $arResult["PAGE_ELEMENT_COUNT"]):?>
						<div class="<?=$arResult["COLS_ITEMS"]?> align-self-center">
					        <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["CATALOG"]."/"?>?q=<?=$arParams["QUERY"]?>" target="_blank" class="button-def secondary <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> btn-show-all"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL_BRANDS"]?>  <span>(<?=$arParams["SEARCH_RESULT"]["COUNT_BRANDS"]?>)</span></a>

				        </div>
				    <?endif;?>

				</div>
			</div>

		<?endif;?>
	</div>
<?endif;?>