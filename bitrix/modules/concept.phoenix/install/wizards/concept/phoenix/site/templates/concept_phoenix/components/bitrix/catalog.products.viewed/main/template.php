<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main;

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
<?
global $PHOENIX_TEMPLATE_ARRAY;
?>

<?if( !empty($arResult["ITEMS"]) ):?>

	<div class="block item-block padding-on" id="blockProductsViewed">
		

		<div class="catalog-card-wrap universal-parent-slider universal-mobile-arrows viewed-products-js">
			<div class="cart-title">
				<div class="line"></div>
				<div class="container">
			   
				    <div class="title"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["VIEWED_PRODUCTS_TITLE_BLOCK"]?></div>

				    
				    <div class="universal-arrows-mini wr-arrows-slick d-none">
				        <div class="arrow-next"></div>
				        <div class="arrow-prev"></div>
				    </div>
				</div>
			    
			</div>

			<div class="container">

				<div class="cart-catalog-list-wrap">
					<?
						$firstItem = array_shift($arResult["ITEMS"]);
						array_unshift($arResult["ITEMS"], $firstItem);
					?>
					<div class="img-for-lazyload-parent">
						<img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">

					    <?
					        $showBtnBasketOption = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"] === "Y" ) ? 1 : 0;
					        $countItems = count($arResult["ITEMS"]);
					    ?>

					    <div class="catalog-block">

					        <div class="catalog-list catalog-list-slider-viewed FLAT SLIDER universal-slider parent-slider-item-js" data-count='<?=$arParams['COUNT']?>'>

					            <?foreach ($arResult['ITEMS'] as $keyItem => $arItem):?>

					                <?$itemIds = $arItem["VISUAL"];?>

					                <div class="item catalog-item <?if($keyItem!=0) echo 'noactive-slide-lazyload';?>" id="<?=$itemIds['ID']?>">


					                    <div class="item-inner item-board-right">


										    <div class="wrapper-top">
																		       	
										       	<div class="wrapper-image row no-gutters align-items-center">

										            <a href="<?=$arItem['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="d-block col" id="<?=$itemIds["DETAIL_URL_IMG"]?>">

										                <img class="img-fluid d-block mx-auto lazyload" id="<?=$itemIds["PICT"]?>" data-src="<?=$arItem["FIRST_ITEM"]["PHOTO"]?>" alt="<?=$arItem["ALT"]?>"/>
										            </a>


										            <?if(!empty($arItem["LABELS"]["XML_ID"])):?>
										                <div class="wrapper-board-label">
										                    <?foreach($arItem["LABELS"]["XML_ID"] as $k=>$xml_id):?>
										                        <div class="mini-board <?=$xml_id?>" title="<?=$arItem["LABELS"]["VALUE"][$k]?>"><?=$arItem["LABELS"]["VALUE"][$k]?></div>
										                    <?endforeach;?>
										                </div>
										            <?endif;?>
										            
										            
										            <span id="<?=$itemIds['DISCOUNT_PERCENT']?>" class="sale d-none"><?=-$arItem["FIRST_ITEM"]['PRICE']["PERCENT"]?>%</span>


										            <?if($arItem['HAVEOFFERS']):?>

									                    <div class="count-offers-img">
									                        <?=count($arItem["OFFERS"])." ".CPhoenix::getTermination(count($arItem["OFFERS"]), $PHOENIX_TEMPLATE_ARRAY["TERMINATIONS_OFFERS"])?>
									                    </div>

									                <?endif;?>

										            
										        </div>


										        <a href="<?=$arItem['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="name-element" id="<?=$itemIds['NAME']?>">
										            <?=$arItem["NAME_HTML"]?>
										        </a>

										        <div class="wr-block-price">

											        <div 
											            class="block-price
											            <?=($arItem["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItem["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'd-none' : '';?>
											            <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y' ):?>
											                wrapper-board-price
											            <?endif;?>"

											            >

											            <div class="<?=($arItem['HAVEOFFERS'])?"d-none d-lg-block":""?>">

											            	<div class="name-type-price d-none">
											                    <?=$arItem['FIRST_ITEM']['PRICE']['NAME']?>
											                </div>

											                <div class="board-price row no-gutters product-price-js">

											                	
											                	<div class="<?=($arItem['HAVEOFFERS'])?"d-none":""?>">

												                    <div class="actual-price">

												                        <span class="price-value" id="<?=$itemIds['PRICE']?>"><?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?></span><span class="unit <?=(strlen($arItem["FIRST_ITEM"]['MEASURE'])>0 ? '' : 'd-none')?>" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$arItem["FIRST_ITEM"]['MEASURE_HTML']?></span>

												                    </div>

												                    
												                    <div class="old-price align-self-end <?=($arItem["FIRST_ITEM"]["SHOW_DISCOUNT"] ? '' : 'd-none')?>" id="<?=$itemIds['OLD_PRICE']?>">
												                        <?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE']?>
												                    </div>

											                    </div>

											                    <?if($arItem['HAVEOFFERS']):?>
											                    	<div class="actual-price">

												                        <span class="price-value"><?=$arItem['OFFER_DIFF']['VALUE']['HTML']?></span>

												                    </div>
											                    <?endif;?>
											                    

											                </div>

											                <?//if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y'):?>
											                    <div class="wrapper-matrix-block d-none" id= "<?=$itemIds["PRICE_MATRIX"]?>"><?=$arItem["FIRST_ITEM"]["PRICE_MATRIX_RESULT"]["HTML"]?></div>
											                <?//endif;?>
											            </div>

											            <?if($arItem['HAVEOFFERS']):?>
											                <div class="actual-price d-lg-none">
											                	<span class="price-value"><?=$arItem['OFFER_DIFF']['VALUE']['HTML']?></span>
											                </div>
											            <?endif;?>

											        </div>
										        </div>

										    </div>

										    <div class="wrapper-bot part-hidden d-none">


										        <?if($arItem['HAVEOFFERS'] || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>

									                <div class="wrapper-list-info">

									                    <div class="<?=($arItem['CONFIG']["SHOW_OFFERS"] && $arItem['HAVEOFFERS'])? "d-none d-lg-block":""?>">

										                    <?
										                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y")
										                        {?>
										                            <?if($arResult["RATING_VIEW"] == "simple"):?>
										                                                
										                                <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItem['ID'], "CLASS"=>"simple-rating hover"));?>

										                            <?elseif($arResult["RATING_VIEW"] == "full"):?>

										                                <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItem['ID'], "VIEW"=>"rating-reviewsCount", "HREF"=>$arItem['FIRST_ITEM']['DETAIL_PAGE_URL']."#rating-block"));?>

										                            <?endif;?>

										                        <?}
										                    ?>

										                </div>

									                    

										                <?if($arItem['CONFIG']["SHOW_OFFERS"] && $arItem['HAVEOFFERS']):?>

									                        <div id="<?=$itemIds['TREE']?>" class="wrapper-skudiv d-none">

									                            <?if(!empty($arItem["SKU_PROPS"])):?>

									                                <?foreach ($arItem["SKU_PROPS"] as $skuProperty):?>

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


										        <div class="wrapper-inner-bot row no-gutters hidden-js d-none <?=($arItem['HAVEOFFERS'])?'hidden':'';?>" id="<?=$itemIds['WR_ADD2BASKET']?>">

										        	<div class="btn-container align-items-center col-md-6 col-12" id="<?=$itemIds['BASKET_ACTIONS']?>">
												        <a
												            id = "<?=$itemIds['ADD2BASKET']?>"
												            href="javascript:void(0);"
												            data-item = "<?=$arItem["ID"]?>"

												        class="main-color add2basket bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]?></a>

												        <a
												            id = "<?=$itemIds['MOVE2BASKET']?>"
												            href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"
												            data-item = "<?=$arItem["ID"]?>"

												        class="move2basket"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?></a>
												    </div>

												    <div class="col-md-6 col-12">

												        <div class="quantity-container quantity-block row no-gutters align-items-center justify-content-between hidden-sm hidden-xs"
												             data-item="<?=$arItem['ID']?>">

																<span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>">&minus;</span>

																<input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number" value="<?=$arItem["FIRST_ITEM"]["PRICE"]["MIN_QUANTITY"]?>">
																<span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>">&plus;</span>
														</div>
													</div>

										        </div>

										        <?if($arItem['HAVEOFFERS']):?>
											        <div class="wrapper-inner-bot row no-gutters">

											            <div class="btn-container align-items-center col-12">
											                <a href="<?=$arItem['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="main-color bold">

										                        <span class="d-none d-lg-block"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER"]["VALUE"]?></span>

										                        <span class="d-lg-none"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER_MOB"]["VALUE"]?></span>
											                        
											                </a>
											            </div>

											        </div>
											    <?endif;?>
											  
											    	<div class="wrapper-inner-bot row no-gutters d-none <?if($arItem['HAVEOFFERS']):?>hidden<?endif;?>" id="<?=$itemIds['BTN2DETAIL_PAGE_PRODUCT']?>">

											            <div class="btn-container align-items-center col-12">
											                <a href="<?=$arItem['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="main-color bold">
										                        <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME"]["VALUE"]?>
											                </a>
											            </div>

											        </div>
										        

										        

										    </div>

										</div>

					                    <?CPhoenix::admin_setting($arItem, false)?>

					                </div>

					            <?endforeach;?>
					          
					        </div>

					    </div>

						<img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">
					</div>
				</div>

			</div>
		</div>
			
	
	</div>


	<?
		$countItems = count( $arResult["ITEMS"] );

		$class_arrows = "";


		if($countItems>4)
		    $class_arrows .= " visible-xxl visible-xl visible-lg hidden-xs";

		if($countItems>3)
		    $class_arrows .= " visible-md visible-sm hidden-xs";
	?>

	<?if(strlen($class_arrows)>0):?>
	    <script>

	        $(document).ready(function(){
	            $(".viewed-products-js").find(".wr-arrows-slick").addClass('<?=$class_arrows?>').removeClass('d-none');
	        });
	        
	    </script>
	<?endif;?>

	<script>
		initCatalogDetailSlider($(".catalog-list-slider-viewed"));
	</script>


<?endif;?>