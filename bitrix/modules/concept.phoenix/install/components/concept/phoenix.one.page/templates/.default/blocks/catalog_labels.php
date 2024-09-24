<?if(isset($arItem["LABELS"])):?>
	<?if(!empty($arItem["LABELS"]) ):?>
	<?$showBtnBasket = $showBtnBasketOption = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"] === "Y" ) ? 1 : 0;?>


	<?global $html_constructor_labels;
		$html_constructor_labels = array();
	?>

	<?foreach($arItem["LABELS"] as $arLabel):?>
			            		
		<?$GLOBALS['arFilterConstructorLabels'] = array(
			"SECTION_ID"=>$arItem["PROPERTIES"]["CATALOG_LABELS_SECTIONS"]["VALUE"], 
			"INCLUDE_SUBSECTIONS" => "Y", 
			"PROPERTY_LABELS" => $arLabel["ID"],
			"SECTION_GLOBAL_ACTIVE" => "Y"
		);

		
        CPhoenixFunc::setInitialFilterParams('arFilterConstructorLabels');


		$intSectionID = $APPLICATION->IncludeComponent(
			"bitrix:catalog.section",
			"constructor_labels",
			array(
				'LABEL' => $arLabel,
				'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
				'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],

	            "PAGE_ELEMENT_COUNT" => $arItem["PROPERTIES"]["CATALOG_LABELS_ITEMS_MAX_COUNT"]["VALUE"],

				"OBJ_NAME" => $arItem["ID"],
				"TAB_ID" => $arLabel["TAB_ID"],
				"ACTIVE_TAB" => $activeTAB,
				"COMPONENT_TEMPLATE" => "constructor_labels",

	            "PAGER_TEMPLATE" => "phoenix_round",
	            "FILTER_NAME" => "arFilterConstructorLabels",
	            "ELEMENT_SORT_FIELD" => "SORT",
	            "ELEMENT_SORT_ORDER" => "ASC",
	            "ELEMENT_SORT_FIELD2" => "ID",
	            "ELEMENT_SORT_ORDER2" => "ASC",
	            'HIDE_NOT_AVAILABLE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"],
	            'HIDE_NOT_AVAILABLE_OFFERS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"],
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
	            "CACHE_TYPE" => "A",
	            "SET_LAST_MODIFIED" => "N",
	            "DISPLAY_TOP_PAGER" => "N",
	            "DISPLAY_BOTTOM_PAGER" =>  "N",
	            "PAGER_SHOW_ALL" => "N",
	            "CACHE_FILTER" => "Y",
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
	            "SET_TITLE" => "N",
	            "SET_STATUS_404" => "N",
	            "SHOW_404" => "N",
	            
	            
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
	            "COMPOSITE_FRAME_MODE" => "N",
				
			),
			$component
		);?>

	<?endforeach;?>

	<div class="catalog-labels hidden-sm hidden-xs">

		<div class="catalog-labels-inner parent-tab">
		    <div class="catalog-list FLAT <?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_AVAILABLE_PRICE_COUNT']['VALUE']['ACTIVE'] === 'Y')?'size-lg':''?>">

		        <div class="row">

		        	<div class="col-xl-3 col-lg-4 col-6 wrapper-side-tabs">

		        		<ul class="side-tabs">

		    				<?
		    					$i = 0;
		    					$activeTAB = "";
		    				?>

		            		<?foreach($arItem["LABELS"] as $arLabel):?>

		            			<li class="ic-isset <?if($i==0) echo "active";?> tab-section" data-tab="<?=$arLabel["TAB_ID"]?>"><?=$arLabel["NAME"]?> 
		                            <span class="ic ic-<?=$arLabel["ICON"]?>"></span>
		                            <!-- <span class="refresh"></span> -->

		                            <span class="ar-right">&#8594;</span>
		                        </li>

		                        <?
		                        	if($i==0) 
		                        		$activeTAB = $arLabel["TAB_ID"];
		                        ?>

		                        <?$i++;?>

		            		<?endforeach;?>
		            	</ul>

		            	<?if($arItem["BUTTON_CHANGE"]):?>
		                    <?=CreateButton($arItem, $show_menu, false)?>
		                <?endif;?>

		        	</div>


					<?foreach($html_constructor_labels as $keyLabel => $arLabel):?>

						<?if(!empty($arLabel["ITEMS"])):?>

							<?
								$countItems = count($arLabel["ITEMS"]);
								
								$iXl = 0;
							    $iLg = 0;
							    $iMd = 0;
							?>

							<?foreach ($arLabel["ITEMS"] as $keyItemLabel => $arItemLabel):?>

							    <?
							        $iXl++;
							        $iLg++;
							        $iMd++;


			        				$arItemLabel["VISUAL"] = CPhoenix::prepareObjJs($arItemLabel['VISUAL'], $keyLabel.'_label');
				        			

							        $itemIds = $arItemLabel["VISUAL"];

							        $boardRight = "";

							        
							        if(  ( ($keyItemLabel+1) == 3 ) || ( ($iXl) % 4 == 0 )  )
							            $boardRight .= "item-noboard-right-xl ";

							        if(  ( ($keyItemLabel+1) == 2 ) || ( ($iLg) % 3 == 0 )  )
							            $boardRight .= "item-noboard-right-lg ";

							        if(  ( ($keyItemLabel+1) == 1 ) || ( ($iMd) % 2 == 0 )  )
							            $boardRight .= "item-noboard-right-md item-noboard-right-sm ";
							            
							    ?>



							    <div class="
							                col-xl-3 col-lg-4 col-6 
							                <?=$boardRight?>
							                item 
							                item-board-right
							                catalog-item 
							                tab-element
							                <?=$arLabel["TAB_ID"]?> 
							                <?if($arLabel["TAB_ID"] == $activeTAB):?>active<?endif;?>
							                " id="<?=$itemIds['ID']?>">


							        <div class="item-inner">

									    <div class="wrapper-top">

									        <div class="wrapper-image row no-gutters align-items-center">

									            <a href="<?=$arItemLabel['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="d-block col" id="<?=$itemIds["DETAIL_URL_IMG"]?>">

									                <img class="img-fluid d-block mx-auto lazyload" id="<?=$itemIds["PICT"]?>" data-src="<?=$arItemLabel["FIRST_ITEM"]["PHOTO"]?>" alt="<?=$arItemLabel["ALT"]?>"/>
									            </a>


									            <?if(!empty($arItemLabel["LABELS"]["XML_ID"])):?>
									                <div class="wrapper-board-label">
									                    <?foreach($arItemLabel["LABELS"]["XML_ID"] as $k=>$xml_id):?>
									                        <div class="mini-board <?=$xml_id?>" title="<?=$arItemLabel["LABELS"]["VALUE"][$k]?>"><?=$arItemLabel["LABELS"]["VALUE"][$k]?></div>
									                    <?endforeach;?>
									                </div>
									            <?endif;?>
									            
									            
									            <span id="<?=$itemIds['DISCOUNT_PERCENT']?>" class="sale <?=( ($arItemLabel['CONFIG']["SHOW_DISCOUNT_PERCENT"] === 'Y' && $arItemLabel["FIRST_ITEM"]['PRICE']['PERCENT']>0) ? '' : 'd-none')?>"><?=-$arItemLabel["FIRST_ITEM"]['PRICE']["PERCENT"]?>%</span>

									            
									            <?if( $arItemLabel['CONFIG']['SHOW_DELAY'] == "Y" 
									              || $arItemLabel['CONFIG']['SHOW_COMPARE'] == "Y" ):?>

									                <div class="wrapper-delay-compare-icons <?=($arItemLabel['HAVEOFFERS'])?"hidden-md hidden-sm hidden-xs":"";?>">

									                    <?if($arItemLabel['CONFIG']['SHOW_DELAY'] == "Y"):?>
									                        <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DELAY_TITLE"]?>" class="icon delay add2delay" id = "<?=$itemIds["DELAY"]?>" data-item="<?=$arItemLabel["ID"]?>"></div>
									                    <?endif;?>

									                    <?if($arItemLabel['CONFIG']['SHOW_COMPARE'] == "Y"):?>
									                        <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_COMPARE_TITLE"]?>" class="icon compare add2compare" id = "<?=$itemIds["COMPARE"]?>" data-item="<?=$arItemLabel["ID"]?>"></div>
									                    <?endif;?>
									                </div>
									           

									            <?endif;?>

									            
									        </div>

									        
									           
									        <div class="wrapper-article-available row no-gutters d-none d-lg-flex" id="<?=$itemIds['ARTICLE_AVAILABLE']?>">

									            <div class="product-available-js hidden-js"><?=$arItemLabel["FIRST_ITEM"]["QUANTITY"]["HTML"]?></div>

									            <div class="detail-article italic col" title="<?=(strlen($arItemLabel["FIRST_ITEM"]["ARTICLE"])>0)?$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arItemLabel["FIRST_ITEM"]["ARTICLE"]:""?>"><?=(strlen($arItemLabel["FIRST_ITEM"]["ARTICLE"])>0)?$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arItemLabel["FIRST_ITEM"]["ARTICLE"]:""?></div>
									        </div>

									        <a href="<?=$arItemLabel['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="name-element" id="<?=$itemIds['NAME']?>">
									            <?=$arItemLabel["NAME_HTML"]?>
									        </a>


									        <div 
									            class="block-price
									            <?=($arItemLabel["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItemLabel["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'd-none' : '';?>
									            <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y' ):?>
									                wrapper-board-price
									            <?endif;?>"

									            >

									            <div class="<?=($arItemLabel['HAVEOFFERS'])?"d-none d-lg-block":""?>">

									            	<div class="name-type-price d-none">
									                    <?=$arItemLabel['FIRST_ITEM']['PRICE']['NAME']?>
									                </div>

									                <div class="board-price row no-gutters product-price-js">

									                    <div class="actual-price">

									                        <span class="price-value" id="<?=$itemIds['PRICE']?>"><?=$arItemLabel["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?></span><span class="unit <?=(strlen($arItemLabel["FIRST_ITEM"]['MEASURE'])>0 ? '' : 'd-none')?>" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$arItemLabel["FIRST_ITEM"]['MEASURE_HTML']?></span>

									                    </div>

									                    
									                    <div class="old-price align-self-end <?=($arItemLabel["FIRST_ITEM"]["SHOW_DISCOUNT"] ? '' : 'd-none')?>" id="<?=$itemIds['OLD_PRICE']?>">
									                        <?=$arItemLabel["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE']?>
									                    </div>
									                    

									                </div>

									                <?//if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y'):?>
									                    <div class="wrapper-matrix-block hidden-xs d-none" id= "<?=$itemIds["PRICE_MATRIX"]?>"><?=$arItemLabel["FIRST_ITEM"]["PRICE_MATRIX_RESULT"]["HTML"]?></div>
									                <?//endif;?>
									            </div>

									            <?if($arItemLabel['HAVEOFFERS']):?>
									                <div class="actual-price d-lg-none">
									                    <?=$arItemLabel['OFFER_DIFF']['VALUE']['HTML']?>
									                </div>
									            <?endif;?>

									        </div>

									        

									    </div>


									    <div class="wrapper-bot part-hidden">

									        <?if(   $arItemLabel['HAVEOFFERS']
									                || strlen($arItemLabel["FIRST_ITEM"]["SHORT_DESCRIPTION"])
									                || strlen($arItemLabel["FIRST_ITEM"]["PREVIEW_TEXT"])
									                || !empty($arItemLabel["CHARS_SORT"])
									                || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"

									            ):?>

									            <div class="wrapper-list-info">

									                <div class="d-none d-lg-block">


									                    <?if($arItemLabel['CONFIG']["SHOW_OFFERS"] && $arItemLabel['HAVEOFFERS']):?>

									                        <div id="<?=$itemIds['TREE']?>" class="wrapper-skudiv <?=($arItemLabel['SKU_HIDE_IN_LIST']==='Y')?'d-none':''?>">

									                            <?if(!empty($arItemLabel["SKU_PROPS"])):?>

									                                <?foreach ($arItemLabel["SKU_PROPS"] as $skuProperty):?>

									                                    <?
									                                        $propertyId = $skuProperty['ID'];

									                                        if(!isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'][$propertyId]))
									                                            continue;

									                                        $skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
									                                    ?>

									                                    <div class="wrapper-sku-props clearfix">
									                                        <div class="product-item-scu-container clearfix">

									                                            <div class="wrapper-title row no-gutters">
									                                                <div class="desc-title"><?=htmlspecialcharsEx($skuProperty['NAME'])?><span class="prop-name"></span> </div>
									                                                
									                                            </div>

									                                            <?if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic' || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic_with_info'):?>

									                                                <ul class="sku-props clearfix">

									                                                    <?if(!empty($skuProperty['VALUES'])):?>

									                                                    <?foreach ($skuProperty['VALUES'] as $value):?>

									                                                        <?

									                                                            $styleTab = "";
									                                                            $styleHoverBoard = "";


									                                                            if(isset($value["PICT"]) || isset($value["PICT_SEC"]) )
									                                                            {
									                                                                if(isset($value["PICT_SEC"]))
									                                                                {
									                                                                    $styleHoverBoard .= "background-image: url('".$value['PICT_SEC']['BIG']."'); ";

									                                                                    if(isset($value["PICT"]))
									                                                                        $styleTab .= "background-image: url('".$value['PICT']['SMALL']."'); ";
									                                                                    else
									                                                                        $styleTab .= "background-image: url('".$value['PICT_SEC']['SMALL']."'); ";

									                                                                }

									                                                                else if(isset($value["PICT"]))
									                                                                {
									                                                                    $styleTab .= "background-image: url('".$value['PICT']['SMALL']."'); ";
									                                                                    $styleHoverBoard .= "background-image: url('".$value['PICT']['BIG']."'); ";
									                                                                }
									                                                            }

									                                                            if($value["COLOR"])
									                                                            {
									                                                                $styleTab .= "background-color:".$value["COLOR"]."; ";
									                                                                $styleHoverBoard .= "background-color:".$value["COLOR"]."; ";
									                                                            }
									                                                        ?>


									                                                        <li title='<?=CPhoenix::prepareText($value['NAME'])?>' class="detail-color"

									                                                                data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
									                                                                data-onevalue="<?=$value['ID']?>"
									                                                                

									                                                            >

									                                                            <div class="color" style="<?=$styleTab?>"></div>


									                                                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic_with_info'):?>

									                                                                <div class="wrapper-hover-board">
									                                                                    <div class="img" style="<?=$styleHoverBoard?>"></div>
									                                                                    <div class="desc"><?=$value['NAME']?></div>
									                                                                    <div class="arrow"></div>
									                                                                </div>

									                                                            <?endif;?>

									                                                            <span class="active-flag"></span>

									                                                        </li>

									                                                    <?endforeach;?>

									                                                    <?endif;?>
									                                                </ul>

									                                            <?elseif($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'select'):?>

									                                                <div class="wrapper-select-input">

									                                                    <ul class="sku-props select-input">

									                                                        <li class="area-for-current-value"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SKU_SELECT_TITLE"]?></li>

									                                                        <?if(!empty($skuProperty['VALUES'])):?>

									                                                            <?foreach ($skuProperty['VALUES'] as $value):?>
									                                                                <li title='<?=CPhoenix::prepareText($value['NAME'])?>'

									                                                                        data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
									                                                                        data-onevalue="<?=$value['ID']?>"

									                                                                    ><?=$value['NAME']?></li>
									                                                            <?endforeach;?>

									                                                        <?endif;?>
									                                                       
									                                                    </ul>

									                                                    <div class="ar-down"></div>

									                                                </div>


									                                            <?else:?>

									                                                <ul class="sku-props">

									                                                    <?if(!empty($skuProperty['VALUES'])):?>

									                                                        <?foreach ($skuProperty['VALUES'] as &$value):?>
									                                                            <li title='<?=CPhoenix::prepareText($value['NAME'])?>' class="detail-text"

									                                                                data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
									                                                                data-onevalue="<?=$value['ID']?>"

									                                                            ><?=$value['NAME']?></li>
									                                                        <?endforeach;?>

									                                                    <?endif;?>
									                                                </ul>


									                                            <?endif;?>


									                                        </div>

									                                    </div>

									                                <?endforeach;?>

									                            <?endif;?>

									                        </div>                            

									                    <?endif;?>

									                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]["PROPS_IN_LIST_FOR_FLAT"]["VALUE"]["DESCRIPTION"] == "Y"):?>

									                        <div class="short-description" id="<?=$itemIds["SHORT_DESCRIPTION"]?>"><?=$arItemLabel["FIRST_ITEM"]["SHORT_DESCRIPTION_HTML"]?></div>

									                    <?endif;?>



									                    <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]["PROPS_IN_LIST_FOR_FLAT"]["VALUE"]["PREVIEW_TEXT"] == "Y" ):?>

									                        <div class="preview-text" id="<?=$itemIds["PREVIEW_TEXT"]?>"><?=$arItemLabel["FIRST_ITEM"]["PREVIEW_TEXT_HTML"]?></div>

									                    <?endif;?>

									                </div>

									                <div class="<?=($arItemLabel['CONFIG']["SHOW_OFFERS"] && $arItemLabel['HAVEOFFERS'])? "d-none d-lg-block":""?>">

									                    <?
									                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y")
									                        {?>
									                            <?if($arLabel['RATING_VIEW'] == "simple"):?>
									                                                
									                                <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItemLabel['ID'], "CLASS"=>"simple-rating hover"));?>

									                            <?elseif($arLabel["RATING_VIEW"] == "full"):?>

									                                <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItemLabel['ID'], "VIEW"=>"rating-reviewsCount", "HREF"=>$arItemLabel['FIRST_ITEM']['DETAIL_PAGE_URL']."#rating-block"));?>

									                            <?endif;?>

									                        <?}
									                    ?>

									                </div>


									                <?if($arItemLabel['HAVEOFFERS']):?>

									                    <div class="d-lg-none count-offers">
									                        <?=count($arItemLabel["OFFERS"])." ".CPhoenix::getTermination(count($arItemLabel["OFFERS"]), $PHOENIX_TEMPLATE_ARRAY["TERMINATIONS_OFFERS"])?>
									                    </div>

									                <?endif;?>

									                <div class="d-none d-lg-block">


									                    <?if(!empty($arItemLabel["CHARS_SORT"])):?>

									                        <?$countChars = 0;?>

									                        <div class="wrapper-characteristics">

									                            <div class="characteristics show-hidden-parent">

									                                <?foreach ($arItemLabel["CHARS_SORT"] as $keyChar => $valueChar):?>

									                                    <?if($keyChar == "sku_chars"):?>

									                                        <div class="sku-chars" id = "<?=$itemIds["SKU_CHARS"]?>"></div>

									                                    <?elseif($keyChar == "props_chars"):?>

									                                        <?if(!empty($arItemLabel["PROPS_CHARS"])):?>
									                    
									                                            <?foreach($arItemLabel["PROPS_CHARS"] as $key=>$value):?>

									                                                <div class="characteristics-item show-hidden-child <?if($countChars >= 5):?>hidden<?endif;?>">
									                            
									                                                    <span class="characteristics-item-name bold"><?=$value["NAME"]?>:</span>&nbsp;<span class="characteristics-item-value"><?=$value["VALUE"]?></span>

									                                                </div>

									                                                <?$countChars++?>

									                                            <?endforeach;?>

									                                        <?endif;?>



									                                    <?elseif($keyChar == "prop_chars"):?>

									                                        <?if(!empty($arItemLabel["PROP_CHARS"])):?>
									                    
									                                            <?foreach($arItemLabel["PROP_CHARS"] as $key=>$value):?>

									                                                <div class="characteristics-item show-hidden-child <?if($countChars >= 5):?>hidden<?endif;?>">
									                            
									                                                    <span class="characteristics-item-name bold"><?=$value["NAME"]?>:</span>&nbsp;<span class="characteristics-item-value"><?=$value["VALUE"]?></span>

									                                                </div>

									                                                <?$countChars++?>

									                                            <?endforeach;?>

									                                        <?endif;?>

									                                    <?endif;?>

									                                <?endforeach;?>



									                                <?if($countChars > 5):?>

									                                    <div class="show-hidden-wrap">
									                                        <a class="show-hidden btn-style-light"><span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL"]?></span></a>
									                                    </div>

									                                <?endif;?>

									                            </div>

									                        </div>

									                    <?endif;?>
									                    

									                </div>


									            </div>
									            
									        <?endif;?>

									        <div class="wrapper-inner-bot row no-gutters hidden-js" id="<?=$itemIds['WR_ADD2BASKET']?>">
									        

									            <div class="quantity-container row no-gutters align-items-center col-xl-6 quantity-block d-none d-xl-flex" data-item="<?=$arItemLabel['ID']?>">

									                <table>
									                    <tr>
									                        <td class="btn-quantity"><span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>">&minus;</span></td>
									                        <td><input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number" name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>" value="<?=$arItemLabel["FIRST_ITEM"]["PRICE"]["MIN_QUANTITY"]?>"></td>
									                        <td class="btn-quantity"><span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>">&plus;</span></td>
									                    </tr>
									                </table>


									            </div>

									            <div class="btn-container align-items-center col-xl-6 col-12" id="<?=$itemIds['BASKET_ACTIONS']?>">

									                <div class="<?=($arItemLabel['HAVEOFFERS'])?"d-none d-lg-block":""?>">
									                    <a
									                        id = "<?=$itemIds['ADD2BASKET']?>"
									                        href="javascript:void(0);"
									                        data-item = "<?=$arItemLabel["ID"]?>"

									                    class="main-color add2basket bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]?></a>

									                    <a
									                        id = "<?=$itemIds['MOVE2BASKET']?>"
									                        href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"
									                        data-item = "<?=$arItemLabel["ID"]?>"

									                    class="move2basket"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?></a>
									                </div>

									                <?if($arItemLabel['HAVEOFFERS']):?>
									                    <a href="<?=$arItemLabel['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="main-color bold d-lg-none">
									                        <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER_MOB"]["VALUE"]?>
									                    </a>
									                <?endif;?>

									            </div>


									        </div>

									        <div class="wrapper-inner-bot row no-gutters d-none" id="<?=$itemIds['BTN2DETAIL_PAGE_PRODUCT']?>">

									            <div class="btn-container align-items-center col-12">
									                <a href="<?=$arItemLabel['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="main-color bold">

									                    <?if($arItemLabel['HAVEOFFERS']):?>

									                        <span class="d-none d-lg-block"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER"]["VALUE"]?></span>

									                        <span class="d-lg-none"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER_MOB"]["VALUE"]?></span>

									                    <?else:?>

									                        <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME"]["VALUE"]?>

									                    <?endif;?>
									                        
									                </a>
									            </div>

									        </div>

									    </div>


									</div>
							        

							        <?	

							        	CPhoenix::admin_setting($arItemLabel, false);

							        	unset($arItemLabel['FIRST_ITEM'], $arItemLabel['SKU_PROPS']);
							        ?>

							        <script>
							        	var <?=$arItemLabel["OBJ_ID"]?> = new JCCatalogItem(<?=CUtil::PhpToJSObject($arItemLabel, false, true)?>);
							        </script>

							    </div>


								<?if($countItems != ($keyItemLabel+1)):?>

							        <?if(  ( ($keyItemLabel+1) == 3 ) || ( ($iXl) % 4 == 0 )  ):?>
							            <span class="col-12 break-line tab-element visible-xxl visible-xl
							                <?=$arLabel["TAB_ID"]?> <?if($arLabel["TAB_ID"] == $activeTAB):?>active<?endif;?>">
							                <div></div>
							            </span>
							        <?endif;?>

							        <?if(  ( ($keyItemLabel+1) == 2 ) || ( ($iLg) % 3 == 0 )  ):?>
							            <span class="col-12 break-line tab-element visible-lg
							                <?=$arLabel["TAB_ID"]?> <?if($arLabel["TAB_ID"] == $activeTAB):?>active<?endif;?>">
							                <div></div>
							            </span>
							        <?endif;?>
								 

								    <?if(  ( ($keyItemLabel+1) == 1 ) || ( ($iMd) % 2 == 0 )  ):?>
								        <span class="col-12 break-line visible-sm visible-xs visible-md tab-element
								            <?=$arLabel["TAB_ID"]?> <?if($arLabel["TAB_ID"] == $activeTAB):?>active<?endif;?>">
								            <div></div>
								        </span>
								    <?endif;?>

							    <?endif;?>

							    <?

							        if( $keyItemLabel == 2 && $iXl == 3)
							            $iXl = 0;

							        if( $keyItemLabel == 1 && $iLg == 2)
							            $iLg = 0;

							        if( $keyItemLabel == 0 && $iMd == 1)
							            $iMd = 0;
							    ?>
							    
							<?endforeach;?>

						<?endif;?>

					<?endforeach;?>


		        </div>

		    </div>
		</div>
	</div>



	<div class="visible-sm visible-xs universal-mobile-arrows">

		

		<?foreach($html_constructor_labels as $keyLabel => $arLabel):?>

			<div class="label-item">

				<div class="title-tab">
				    <?=$arLabel["NAME"]?> 
				    <div class='main-color'></div>
				</div>

				<div class="img-for-lazyload-parent">
					<img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arItem["ID"]?>">

					<div class="catalog-block">

						<div class="catalog-list catalog-list-slider FLAT SLIDER universal-slider parent-slider-item-js">

							<?if(!empty($arLabel["ITEMS"])):?>
								<?$countItems = count($arLabel["ITEMS"]);?>

								<?foreach ($arLabel["ITEMS"] as $keyItemLabel => $arItemLabel):?>

				        			<?
				        				$arItemLabel["VISUAL"] = CPhoenix::prepareObjJs($arItemLabel['VISUAL'], $keyLabel.'_slider');

				        				$itemIds = $arItemLabel["VISUAL"];
				        			?>

				        			<div class="item catalog-item <?if($keyItemLabel!=0) echo 'noactive-slide-lazyload';?>" id="<?=$itemIds['ID']?>">

				        				<div class="item-inner">

										    <div class="wrapper-top">

										       	
										       	<div class="wrapper-image row no-gutters align-items-center">

										            <a href="<?=$arItemLabel['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="d-block col" id="<?=$itemIds["DETAIL_URL_IMG"]?>">

										                <img class="img-fluid d-block mx-auto lazyload" id="<?=$itemIds["PICT"]?>" data-src="<?=$arItemLabel["FIRST_ITEM"]["PHOTO"]?>" alt="<?=$arItemLabel["ALT"]?>"/>
										            </a>


										            <?if(!empty($arItemLabel["LABELS"]["XML_ID"])):?>
										                <div class="wrapper-board-label">
										                    <?foreach($arItemLabel["LABELS"]["XML_ID"] as $k=>$xml_id):?>
										                        <div class="mini-board <?=$xml_id?>" title="<?=$arItemLabel["LABELS"]["VALUE"][$k]?>"><?=$arItemLabel["LABELS"]["VALUE"][$k]?></div>
										                    <?endforeach;?>
										                </div>
										            <?endif;?>
										            
										            
										            <span id="<?=$itemIds['DISCOUNT_PERCENT']?>" class="sale <?=( ($arItemLabel['CONFIG']["SHOW_DISCOUNT_PERCENT"] === 'Y' && $arItemLabel["FIRST_ITEM"]['PRICE']['PERCENT']>0) ? '' : 'd-none')?>"><?=-$arItemLabel["FIRST_ITEM"]['PRICE']["PERCENT"]?>%</span>

										            
										            <?if( $arItemLabel['CONFIG']['SHOW_DELAY'] == "Y" 
										              || $arItemLabel['CONFIG']['SHOW_COMPARE'] == "Y" ):?>

										                <div class="wrapper-delay-compare-icons <?=($arItemLabel['HAVEOFFERS'])?"hidden-md hidden-sm hidden-xs":"";?>">

										                    <?if($arItemLabel['CONFIG']['SHOW_DELAY'] == "Y"):?>
										                        <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DELAY_TITLE"]?>" class="icon delay add2delay" id = "<?=$itemIds["DELAY"]?>" data-item="<?=$arItemLabel["ID"]?>"></div>
										                    <?endif;?>

										                    <?if($arItemLabel['CONFIG']['SHOW_COMPARE'] == "Y"):?>
										                        <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_COMPARE_TITLE"]?>" class="icon compare add2compare" id = "<?=$itemIds["COMPARE"]?>" data-item="<?=$arItemLabel["ID"]?>"></div>
										                    <?endif;?>
										                </div>
										           

										            <?endif;?>

										            <?if($arItemLabel['HAVEOFFERS']):?>

									                    <div class="d-lg-none count-offers-img">
									                        <?=count($arItemLabel["OFFERS"])." ".CPhoenix::getTermination(count($arItemLabel["OFFERS"]), $PHOENIX_TEMPLATE_ARRAY["TERMINATIONS_OFFERS"])?>
									                    </div>

									                <?endif;?>

										            
										        </div>


										        <a href="<?=$arItemLabel['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="name-element" id="<?=$itemIds['NAME']?>">
										            <?=$arItemLabel["NAME_HTML"]?>
										        </a>

										        <div class="wr-block-price">

											        <div 
											            class="block-price
											            <?=($arItemLabel["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItemLabel["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'd-none' : '';?>
											            <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y' ):?>
											                wrapper-board-price
											            <?endif;?>"

											            >

											            <div class="<?=($arItemLabel['HAVEOFFERS'])?"d-none d-lg-block":""?>">

											            	<div class="name-type-price d-none">
											                    <?=$arItemLabel['FIRST_ITEM']['PRICE']['NAME']?>
											                </div>

											                <div class="board-price row no-gutters product-price-js">

											                    <div class="actual-price">

											                        <span class="price-value" id="<?=$itemIds['PRICE']?>"><?=$arItemLabel["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?></span><span class="unit <?=(strlen($arItemLabel["FIRST_ITEM"]['MEASURE'])>0 ? '' : 'd-none')?>" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$arItemLabel["FIRST_ITEM"]['MEASURE_HTML']?></span>

											                    </div>

											                    
											                    <div class="old-price align-self-end <?=($arItemLabel["FIRST_ITEM"]["SHOW_DISCOUNT"] ? '' : 'd-none')?>" id="<?=$itemIds['OLD_PRICE']?>">
											                        <?=$arItemLabel["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE']?>
											                    </div>
											                    

											                </div>

											                <?//if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y'):?>
											                    <div class="wrapper-matrix-block hidden-xs d-none" id= "<?=$itemIds["PRICE_MATRIX"]?>"><?=$arItemLabel["FIRST_ITEM"]["PRICE_MATRIX_RESULT"]["HTML"]?></div>
											                <?//endif;?>
											            </div>

											            <?if($arItemLabel['HAVEOFFERS']):?>
											                <div class="actual-price d-lg-none">
											                    <?=$arItemLabel['OFFER_DIFF']['VALUE']['HTML']?>
											                </div>
											            <?endif;?>

											        </div>

											    </div>

										    </div>


										    <div class="wrapper-bot part-hidden">



										        <?if($arItemLabel['HAVEOFFERS'] || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>

									                <div class="wrapper-list-info">

									                

									                    <?
									                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y")
									                        {?>
									                            <?if($arLabel["RATING_VIEW"] == "simple"):?>
									                                                
									                                <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItemLabel['ID'], "CLASS"=>"simple-rating hover"));?>

									                            <?elseif($arLabel["RATING_VIEW"] == "full"):?>

									                                <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItemLabel['ID'], "VIEW"=>"rating-reviewsCount", "HREF"=>$arItemLabel['FIRST_ITEM']['DETAIL_PAGE_URL']."#rating-block"));?>

									                            <?endif;?>

									                        <?}
									                    ?>

									          

										                <?if($arItemLabel['CONFIG']["SHOW_OFFERS"] && $arItemLabel['HAVEOFFERS']):?>

									                        <div id="<?=$itemIds['TREE']?>" class="wrapper-skudiv d-none">

									                            <?if(!empty($arItemLabel["SKU_PROPS"])):?>

									                                <?foreach ($arItemLabel["SKU_PROPS"] as $skuProperty):?>

									                                    <?
									                                        $propertyId = $skuProperty['ID'];

									                                        if(!isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'][$propertyId]))
									                                            continue;

									                                        $skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
									                                    ?>

									                                    <div class="wrapper-sku-props clearfix">
									                                        <div class="product-item-scu-container clearfix">

									                                            <div class="wrapper-title row no-gutters">
									                                                <div class="desc-title"><?=htmlspecialcharsEx($skuProperty['NAME'])?><span class="prop-name"></span> </div>
									                                                
									                                            </div>

									                                            <?if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic' || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic_with_info'):?>

									                                                <ul class="sku-props clearfix">

									                                                    <?if(!empty($skuProperty['VALUES'])):?>

									                                                    <?foreach ($skuProperty['VALUES'] as $value):?>

									                                                        <?

									                                                            $styleTab = "";
									                                                            $styleHoverBoard = "";


									                                                            if(isset($value["PICT"]) || isset($value["PICT_SEC"]) )
									                                                            {
									                                                                if(isset($value["PICT_SEC"]))
									                                                                {
									                                                                    $styleHoverBoard .= "background-image: url('".$value['PICT_SEC']['BIG']."'); ";

									                                                                    if(isset($value["PICT"]))
									                                                                        $styleTab .= "background-image: url('".$value['PICT']['SMALL']."'); ";
									                                                                    else
									                                                                        $styleTab .= "background-image: url('".$value['PICT_SEC']['SMALL']."'); ";

									                                                                }

									                                                                else if(isset($value["PICT"]))
									                                                                {
									                                                                    $styleTab .= "background-image: url('".$value['PICT']['SMALL']."'); ";
									                                                                    $styleHoverBoard .= "background-image: url('".$value['PICT']['BIG']."'); ";
									                                                                }
									                                                            }

									                                                            if($value["COLOR"])
									                                                            {
									                                                                $styleTab .= "background-color:".$value["COLOR"]."; ";
									                                                                $styleHoverBoard .= "background-color:".$value["COLOR"]."; ";
									                                                            }
									                                                        ?>


									                                                        <li title='<?=CPhoenix::prepareText($value['NAME'])?>' class="detail-color"

									                                                                data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
									                                                                data-onevalue="<?=$value['ID']?>"
									                                                                

									                                                            >

									                                                            <div class="color" style="<?=$styleTab?>"></div>


									                                                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic_with_info'):?>

									                                                                <div class="wrapper-hover-board">
									                                                                    <div class="img" style="<?=$styleHoverBoard?>"></div>
									                                                                    <div class="desc"><?=$value['NAME']?></div>
									                                                                    <div class="arrow"></div>
									                                                                </div>

									                                                            <?endif;?>

									                                                            <span class="active-flag"></span>

									                                                        </li>

									                                                    <?endforeach;?>

									                                                    <?endif;?>
									                                                </ul>

									                                            <?elseif($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'select'):?>

									                                                <div class="wrapper-select-input">

									                                                    <ul class="sku-props select-input">

									                                                        <li class="area-for-current-value"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SKU_SELECT_TITLE"]?></li>

									                                                        <?if(!empty($skuProperty['VALUES'])):?>

									                                                            <?foreach ($skuProperty['VALUES'] as $value):?>
									                                                                <li title='<?=CPhoenix::prepareText($value['NAME'])?>'

									                                                                        data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
									                                                                        data-onevalue="<?=$value['ID']?>"

									                                                                    ><?=$value['NAME']?></li>
									                                                            <?endforeach;?>

									                                                        <?endif;?>
									                                                       
									                                                    </ul>

									                                                    <div class="ar-down"></div>

									                                                </div>


									                                            <?else:?>

									                                                <ul class="sku-props">

									                                                    <?if(!empty($skuProperty['VALUES'])):?>

									                                                        <?foreach ($skuProperty['VALUES'] as &$value):?>
									                                                            <li title='<?=CPhoenix::prepareText($value['NAME'])?>' class="detail-text"

									                                                                data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
									                                                                data-onevalue="<?=$value['ID']?>"

									                                                            ><?=$value['NAME']?></li>
									                                                        <?endforeach;?>

									                                                    <?endif;?>
									                                                </ul>


									                                            <?endif;?>


									                                        </div>

									                                    </div>

									                                <?endforeach;?>

									                            <?endif;?>

									                        </div>                            

									                    <?endif;?>

									                </div>
										            

										        <?endif;?>


										        <div class="wrapper-inner-bot row no-gutters hidden-js" id="<?=$itemIds['WR_ADD2BASKET']?>">
				        

										            <div class="quantity-container row no-gutters align-items-center col-xl-6 quantity-block d-none d-xl-flex" data-item="<?=$arItemLabel['ID']?>">

										                <table>
										                    <tr>
										                        <td class="btn-quantity"><span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>">&minus;</span></td>
										                        <td><input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number" name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>" value="<?=$arItemLabel["FIRST_ITEM"]["PRICE"]["MIN_QUANTITY"]?>"></td>
										                        <td class="btn-quantity"><span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>">&plus;</span></td>
										                    </tr>
										                </table>


										            </div>

										            <div class="btn-container align-items-center col-xl-6 col-12" id="<?=$itemIds['BASKET_ACTIONS']?>">

										                <div class="<?=($arItemLabel['HAVEOFFERS'])?"d-none d-lg-block":""?>">
										                    <a
										                        id = "<?=$itemIds['ADD2BASKET']?>"
										                        href="javascript:void(0);"
										                        data-item = "<?=$arItemLabel["ID"]?>"

										                    class="main-color add2basket bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]?></a>

										                    <a
										                        id = "<?=$itemIds['MOVE2BASKET']?>"
										                        href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"
										                        data-item = "<?=$arItemLabel["ID"]?>"

										                    class="move2basket"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?></a>
										                </div>

										                <?if($arItemLabel['HAVEOFFERS']):?>
										                    <a href="<?=$arItemLabel['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="main-color bold d-lg-none">
										                        <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER_MOB"]["VALUE"]?>
										                    </a>
										                <?endif;?>

										            </div>


										        </div>

										        <div class="wrapper-inner-bot row no-gutters d-none" id="<?=$itemIds['BTN2DETAIL_PAGE_PRODUCT']?>">

										            <div class="btn-container align-items-center col-12">
										                <a href="<?=$arItemLabel['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="main-color bold">

										                    <?if($arItemLabel['HAVEOFFERS']):?>

										                        <span class="d-none d-lg-block"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER"]["VALUE"]?></span>

										                        <span class="d-lg-none"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER_MOB"]["VALUE"]?></span>

										                    <?else:?>

										                        <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME"]["VALUE"]?>

										                    <?endif;?>
										                        
										                </a>
										            </div>

										        </div>

										    </div>


										</div>
									    

									    <?
									    	CPhoenix::admin_setting($arItemLabel, false);
									    	unset($arItemLabel['FIRST_ITEM'], $arItemLabel['SKU_PROPS']);
									    ?>

									    

									    <script>
									        
									      var <?=$arItemLabel["OBJ_ID"]."_slider"?> = new JCCatalogItem(<?=CUtil::PhpToJSObject($arItemLabel, false, true)?>);
									    </script>

									</div>


				        		<?endforeach;?>


							<?endif;?>
						</div>
					</div>

					<img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arItem["ID"]?>">

				</div>
			</div>

		<?endforeach;?>


	</div>



	<script>
	    BX.message({
	        PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
	        RELATIVE_QUANTITY_MANY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_2"]?>',
	        RELATIVE_QUANTITY_FEW: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["DESCRIPTION_2"]?>',
	        RELATIVE_QUANTITY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_NOEMPTY"]?>',
	        RELATIVE_QUANTITY_EMPTY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_EMPTY"]?>',
	        ARTICLE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"]?>',
	    });
	      
	</script>


	<?endif;?>
<?endif;?>