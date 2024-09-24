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


<div class="col-12">
	<div class="section-block show-hidden-parent" id="to-CATALOG">
	    <div class="section-head">
	        <div class="title-wrap">
	            <div class="title"><?=$arParams["TITLE"]?></div>
	        </div>
	        <?if( $arParams["SEARCH_RESULT"]["COUNT_ELEMENTS"] > 4
	        	&& $arParams["VIEW"] == "short" ):?>
	        	<a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["CATALOG"]."/"?>?q=<?=$arParams["QUERY"]?>" class="btn-trasparent hidden-sm hidden-xs"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL"]?></a>
	        <?endif;?>
	    </div>


	    <?if( !empty($arResult["ITEMS"]) ):?>

	        <div class="desc-mini-wrap hidden-sm hidden-xs">
	            <table class="desc-mini">
	                <tr>
	                    <td><div class="desc-count"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_PRODUCTS"]?>(<?=$arParams["SEARCH_RESULT"]["COUNT_ELEMENTS"]?>)</div></td>
	                </tr>
	            </table>
	        </div>

	    
	        <div class="section-block-content">

	        	<?
	        		if(!empty($arResult["SEARCH_ITEMS_ID"]))
	        			$GLOBALS['arFilterSearchCatalog'] = array("ID" => $arResult["SEARCH_ITEMS_ID"]);

	        		CPhoenixFunc::setInitialFilterParams('arFilterSearchCatalog');
	        		
				    $intSectionID = $APPLICATION->IncludeComponent(
				        "bitrix:catalog.section",
				        "main",
				        array(
				        	'SEARCH_ELEMENTS_ID' => $arParams["SEARCH_RESULT"]["ITEMS_ID"],
				            'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
				            'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
				            "PAGE_ELEMENT_COUNT" => $arParams["NEWS_COUNT"],
				            "PAGER_TEMPLATE" => "phoenix_round",
				            "FILTER_NAME" => "arFilterSearchCatalog",
				            "VIEW" => "FLAT",
				            "COLS" => "4",
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
				            "OFFERS_LIMIT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["OFFERS_QUANTITY"]["VALUE"],

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
				            "DISPLAY_BOTTOM_PAGER" =>  "Y",
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

	        	<?if( ($arParams["SEARCH_RESULT"]["COUNT_ELEMENTS"] > 4)
		        	&& $arParams["VIEW"] == "short" ):?>
		        	<a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["CATALOG"]."/"?>?q=<?=$arParams["QUERY"]?>" class="btn-trasparent mob visible-sm visible-xs"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL_GOODS_SEARCH"]?></a>
		        <?endif;?>
	        	
	        </div>

	        

	        <?/*if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
			    <?=$arResult["NAV_STRING"]?>
			<?endif;*/?>



	    <?endif;?>

	    <?if( !empty($arResult["SECTIONS"]) ):?>

	        <div class="section-block-mini">

	            <div class="desc-mini-wrap">

		            <table class="desc-mini">
		                <tr>
		                    <td><div class="desc-count"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_SECTIONS"]?>&nbsp;(<?=$arParams["SEARCH_RESULT"]["COUNT_SECTIONS"]?>)</div></td>

		                </tr>
		            </table>

		        </div>

            	<div class="wr-category-items-flat hidden-sm hidden-xs">
            		<div class="row">

		            	<?foreach($arResult["SECTIONS"] as $key=>$arItem):?>
		            	
		            		<?if( ($key+1) > 12 )
		                        break;?>

		            		<div class="col-lg-2 col-md-3 col-sm-4 col-6">

			                    <div class="category-item-flat">

			                        <a href="<?=$arItem['SECTION_PAGE_URL']?>" target="_blank">

			                        	<div class="wr-img mx-auto row align-items-center">
	                                        <div class="col-12">
	                                            <img class="d-block mx-auto img-fluid" src="<?=$arItem['PREVIEW_PICTURE_SRC']?>" alt="">
	                                        </div>
	                                    </div>

	                                    <div class="name"><?= $arItem["~NAME"]?></div>
			                        </a>

			                    </div>

			                </div>


		            	<?endforeach;?>

	            	</div>

            	</div>

            	<div class="ex-row visible-sm visible-xs">

                    <div class="wr-category-items-slider">

                        <div class="img-for-lazyload-parent finish-bottom">

                            <img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="search_<?=$arResult["ID"]?>">


                                <div class="section-items-slider parent-slider-item-js">

                                    <?foreach($arResult["SECTIONS"] as $key=>$arItem):?>

                                    	<?if( ($key+1) > 12 )
                                    		break;?>
                                    
                                        <div class="<?=($keySimilarCategory != 0) ? 'noactive-slide-lazyload' : ''?>">
                                            <div class="item">
                                                <a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="d-block">
                                                        
                                                    <img src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" class="img-fluid mx-auto d-block" alt="">

                                                    <div class="desc row align-items-center">
                                                        <div class="col-12">
                                                            <?=$arItem["~NAME"]?>
                                                        </div> 
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                    <?endforeach;?>
                                  
                                </div>

                                <?if(intval($arParams["SEARCH_RESULT"]["COUNT_SECTIONS"])>3):?>
                                    <div class="slider-swipe-icon dark">
                                    </div>
                                <?endif;?>

                            <img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="search_<?=$arResult["ID"]?>">
                        </div>

                    </div>

                </div>

	        </div>

	    <?endif;?>

		<?if( !empty($arResult["BRANDS"]) ):?>

	        <div class="section-block-mini">
	            <div class="desc-mini-wrap">
	                <div class="desc-count"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_BRANDS"]?> (<?=$arParams["SEARCH_RESULT"]["COUNT_BRANDS"]?>)</div>
	            </div>

	            <div class="brands-list">

	                <div class="row">

	                	<?foreach ($arResult["BRANDS"] as $key => $arItem):?>

	                		<?if( ($key+1) > 12 )
	                        	break;?>

		                    <div class="col-lg-3 col-md-4 col-6">
		                        <div class="brand-wrap">

		                            <table class="brand">
		                                <tr>
		                                    <td>
		                                    	<?if(isset($arItem["PREVIEW_PICTURE_SRC"])):?>
		                                        	<img src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" alt="" class="d-block mx-auto img-fluid">
		                                        <?endif;?>
		                                    </td>
		                                </tr>
		                            </table>

		                            <a class = "general-link-wrap" href="<?=$arItem['DETAIL_PAGE_URL']?>"></a>

		                        </div>
		                        <?CPhoenix::admin_setting($arItem, false)?>
		                    </div>

	                    <?endforeach;?>
	                </div>

	            </div>

	        </div>

	   	<?endif;?>

	</div>
</div>