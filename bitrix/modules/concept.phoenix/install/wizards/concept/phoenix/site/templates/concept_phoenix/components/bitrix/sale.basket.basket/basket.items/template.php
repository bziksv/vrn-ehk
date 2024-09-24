<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
/** @var CBitrixBasketComponent $component */
$this->setFrameMode(true);

global $PHOENIX_TEMPLATE_ARRAY;
$showBuyBtn = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_BASKET_ON"]["VALUE"]["ACTIVE"] == "Y") ? true : false;
$showBuyBtnOnly = false;

if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"])&& !in_array('Y', $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"]))
	$showBuyBtn = false;

if($showBuyBtn)
	$showBuyBtnOnly = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_BASKET_ONLY"]["VALUE"]["ACTIVE"] == "Y") ? true : false;


$showbasketProducts = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"]=="Y") ? true: false;

/*$colsLeft = "col-lg-8 col-12";
$colsRight = "col-lg-4 col-12";

if(!$showbasketProducts)
{
	$colsLeft = "col-12";
	$colsRight = "d-none";
}*/


$ymWizard = "ym-record-keys";

$normalCount = 0;
?>


<?/*if( isset($_REQUEST["ORDER_ID"]) && strlen($_REQUEST["ORDER_ID"])):?>

	<div class="col-12 thank-container">

		<?
			$textThank = str_replace("#ORDER_ID#", $_REQUEST["ORDER_ID"], $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_COMPLITED_MESS"]["~VALUE"]);


			echo $textThank;
		?>

	</div>
<?endif;*/?>



<?if (strlen($arResult["ERROR_MESSAGE"]) <= 0):?>

	<?
		$normalCount = (!empty($arResult["ITEMS"]["AnDelCanBuy"]))?count($arResult["ITEMS"]["AnDelCanBuy"]):0;
		$delayCount = (!empty($arResult["ITEMS"]["DelDelCanBuy"]))?count($arResult["ITEMS"]["DelDelCanBuy"]):0;
		$nanCount = (!empty($arResult["ITEMS"]["nAnCanBuy"]))?count($arResult["ITEMS"]["nAnCanBuy"]):0;
		$delayCount += $nanCount;

	?>

	<?if( !isset($arParams["AJAX_CONTENT"]) || $arParams["AJAX_CONTENT"] == "products"):?>

		
		
	    <div class="product-area url-tab-parent" >
	    	
	    	<div class="sort_tabs">
	    		<div class="row no-gutters">

	    			<?if($showbasketProducts):?>

			    		<div class="col-md-auto col-6">

							<div class="tab_item show_items url-tab active" data-url-tab = "items" data-items = "items">
								
								<span class="desc bord-bot">
									<span class="d-none d-md-inline"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_SALE_ITEMS"]?></span>
									<span class="d-md-none"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TAB_TITLE_PRODUCTS"]?></span>
								</span>
						
								<div class="round main-text-color"><?=$normalCount?></div>
							</div>

						</div>

					<?endif;?>

					<div class="col-md-auto col-6">

						<div class="tab_item show_items url-tab" data-url-tab = "delayed" data-items = "delayed" <?if($delayCount <= 0):?>style="display: none;"<?endif;?>>
							
							<span class="desc bord-bot">
								<span class="d-none d-md-inline"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_SALE_ITEMS_DELAY"]?></span>
								<span class="d-md-none"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TAB_TITLE_DELAYED"]?></span>
							</span>
							
							<div class="round main-text-color"><?=$delayCount?></div>

						</div>
					</div>

					<div class="col-md-auto col-6 d-none d-md-block">
					</div>

					<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_FILTER"]["VALUE"]["ACTIVE"]=="Y"):?>

						<div class="col-md-auto col-6 d-none d-md-block ml-auto">

							<div class="wr-basket-filter">
								
								<input type="text" class="basket-filter" placeholder="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SBB_BASKET_FILTER"]?>">
								
								<div class="basket-items-search-clear-btn d-none"></div>
							</div>


						</div>
					<?endif;?>

				</div>
			
			</div>

			

			<?if($showbasketProducts):?>
				<div class="basket_items_list active url-tab-content <?=( !empty($arResult["ADVANTAGES"]["ITEMS"]) )? "last-border-bottom-unset" : ""?>" data-url-tab = "items" data-items = "items">

			        <div id = "basket_items">

			        	<?if($normalCount > 0):?>

				        	<?if(!empty($arResult["GRID"]["ROWS"])):?>
					
								<?foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):?>

									<?if($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"):?>

										<div class="product row" id="<?=$arItem["ID"]?>">
											
											<div class="col-sm-7 col-12">
												<div class="img-name row no-gutters align-items-center">

													<div class="img col-3">

														<a class="link_style d-block" target="_blank" href="<?=$arItem["DETAIL_PAGE"]?>">

															<img class="img-fluid" src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" alt="">

														</a>
														
													</div>

													<div class="wr-name col-9">

														<a href="<?=$arItem["DETAIL_PAGE"]?>" class="d-block bold product-name">
															<?=$arItem["NAME"]?>
														</a>

														<?if(strlen($arItem["ARTICLE"])):?>

															<div class="article italic">
																<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arItem["ARTICLE"]?>
															</div>

														<?endif;?>

														<?if(strlen($arItem["NAME_OFFERS"])):?>
															<div class="name_offers">
																<?=$arItem["NAME_OFFERS"]?>
															</div>

														<?endif;?>
														
													</div>
													
												</div>
											</div>

											<div class="col-sm-2 col-5 wrapper-quantity">
												<div class="row">

													<?
														$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
														$useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
														$useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");
													?>
													<table
														class="quantity-container" 
														data-entity="quantity-block" 
														data-item="<?=$arItem['ID']?>">
														<tr>
															<td 
																class="amount-btn btn-minus"
																onclick="setQuantity(<?=$arItem["ID"]?>, <?=($arItem["MEASURE_RATIO"])?$arItem["MEASURE_RATIO"]:"1";?>, 'down', <?=$useFloatQuantityJS?>);"
															>
																<div>&minus;</div>
															</td>
															<td>
																
																<input
																	id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
																	name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
										                        	class="amount-field"
										                        	type="number"
										                        	value="<?=$arItem["QUANTITY"]?>"
										                        	onchange="updateQuantity(
										                        		'QUANTITY_INPUT_<?=$arItem["ID"]?>',
										                        		'<?=$arItem["ID"]?>',
										                        		<?=$ratio?>,
										                        		<?=$useFloatQuantityJS?>)"
										                        >
										                        <input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
															</td>
															<td 
																class="amount-btn btn-plus"
																onclick="setQuantity(<?=$arItem["ID"]?>, <?=($arItem["MEASURE_RATIO"])?$arItem["MEASURE_RATIO"]:"1";?>, 'up', <?=$useFloatQuantityJS?>);"
															>
																<div>&plus;</div>
															</td>

														</tr>

								                    </table>
								                    <div class="price-product" id="current_price_<?=$arItem['ID']?>">
														<?=$arItem["PRICE_FORMATED"]?>
													</div>
							                    </div>

												
											</div>

											<div class="col-sm-2 col-5 wrapper-sum">
												<div class="sum-price-product bold parent-preload-circleG">


													<div class="circleG-opacity" id="sum_<?=$arItem['ID']?>"><?=$arItem["SUM"]?></div>


													<div class="circleG-wrap small">
									                    <div class="circleG circleG_1"></div>
									                    <div class="circleG circleG_2"></div>
									                    <div class="circleG circleG_3"></div>
									                </div>
												</div>

												<div class="sum-oldprice-product parent-preload-circleG">

													<div class="circleG-opacity" id="old_price_<?=$arItem['ID']?>"

														style = "display: <?= ($arItem["DISCOUNT_PRICE_PERCENT"]) ? '' : 'none';?>"

														><?=$arItem["SUM_FULL_PRICE_FORMATED"]?></div>

													<div class="circleG-wrap small">
									                    <div class="circleG circleG_1"></div>
									                    <div class="circleG circleG_2"></div>
									                    <div class="circleG circleG_3"></div>
									                </div>


												</div>
											</div>

											<div class="col-sm-1 col-2 wrapper-remove">
												<a href = "javascript:void(0);" class="remove-product" onclick="deleteProduct(<?=$arItem['ID']?>, {'action': 'product'})"></a>
											</div>
										</div>

									<?endif;?>
								<?endforeach;?>

							<?endif;?>

						<?else:?>

							<div class="basket-items-empty-message">

								<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_PAGE_EMPTY_MESS"]["~VALUE"];
								/*$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_ITEM_EMPTY"]*/?>

							</div>

						<?endif;?>

					</div>

				</div>
			<?endif;?>

			
			<div class="basket_items_list items_delayed url-tab-content <?=( !empty($arResult["ADVANTAGES"]["ITEMS"]) )? "last-border-bottom-unset" : ""?>" data-url-tab = "delayed" data-items = "delayed">

		        <div id = "delayed_items">

		        	<?if($delayCount > 0):?>

		        		<?if(!empty($arResult["GRID"]["ROWS"])):?>
			
							<?foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):?>

								<?if($arItem["DELAY"] == "Y" && $arItem["CAN_BUY"] == "Y"):?>

									<div class="product row" id="<?=$arItem["ID"]?>">
										
										<div class="col-xl-6 col-lg-5 col-sm-6 col-12">
											<div class="img-name row no-gutters align-items-center">

												<div class="img col-3">

													<a class="link_style d-block" target="_blank" href="<?=$arItem["DETAIL_PAGE"]?>">

														<img class="img-fluid" src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" alt="">

													</a>
													
												</div>

												<div class="wr-name col-9">

													<a href="<?=$arItem["DETAIL_PAGE"]?>" class="d-block bold product-name">
														<?=$arItem["NAME"]?>
													</a>

													<?if(strlen($arItem["ARTICLE"])):?>

														<div class="article italic">
															<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arItem["ARTICLE"]?>
														</div>

													<?endif;?>

													<?if(strlen($arItem["NAME_OFFERS"])):?>
														<div class="name_offers">
															<?=$arItem["NAME_OFFERS"]?>
														</div>

													<?endif;?>
													
												</div>
												
											</div>
										</div>

										<div class="col-sm-2 col-4 wr-price">
											<div class="row">
							                    <div class="price-product bold" id="current_price_<?=$arItem['ID']?>">
													<?=($arItem['PRICE']>0)?$arItem["PRICE_FORMATED"]:''?>
												</div>
						                    </div>

											
										</div>

										<div class="col-xl-3 col-lg-4 col-sm-3 col-7 wr-btn">

											<?if($showbasketProducts):?>

											<a 

											data-param-product="{'PRODUCT_ID':'<?=$arItem['PRODUCT_ID']?>','PRODUCT_ID':'<?=$arItem['PRODUCT_ID']?>','QUANTITY':'<?=$arItem["QUANTITY"]?>', 'PRICE_ID':'<?=$arItem['PRODUCT_PRICE_ID']?>'}"

											title = "<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_ADD2ORDER"]?>" class="button-def main-color add2order <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> btn-add2basket">
												<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_ADD2ORDER"]?>
											</a>
											<?endif;?>
										</div>

										<div class="remove-wrap col-1">
											<a href = "javascript:void(0);" class="remove-product" onclick="deleteProduct(<?=$arItem['ID']?>, {'action': 'delayed'})"></a>
										</div>

										<input type="hidden" name="DELAY_<?=$arItem["ID"]?>" value="Y"/>
									</div>

								<?endif;?>

								
							<?endforeach;?>

							<?foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):?>
								<?if($arItem["DELAY"] == "Y" && $arItem["CAN_BUY"] == "N"):?>

									<div class="product row" id="<?=$arItem["ID"]?>">
										
										<div class="col-xl-6 col-lg-5 col-sm-6 col-12">
											<div class="img-name row no-gutters align-items-center">

												<div class="img col-3">

													<a class="link_style d-block" target="_blank" href="<?=$arItem["DETAIL_PAGE"]?>">

														<img class="img-fluid" src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" alt="">

													</a>
													
												</div>

												<div class="wr-name col-9">

													<a href="<?=$arItem["DETAIL_PAGE"]?>" class="d-block bold product-name">
														<?=$arItem["NAME"]?>
													</a>

													<?if(strlen($arItem["ARTICLE"])):?>

														<div class="article italic">
															<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arItem["ARTICLE"]?>
														</div>

													<?endif;?>

													<?if(strlen($arItem["NAME_OFFERS"])):?>
														<div class="name_offers">
															<?=$arItem["NAME_OFFERS"]?>
														</div>

													<?endif;?>
													
												</div>
												
											</div>
										</div>

										<div class="col-sm-2 col-4 wr-price">
											<div class="row">
							                    <div class="price-product bold" id="current_price_<?=$arItem['ID']?>">
							                    	
													<?=($arItem['PRICE']>0)?$arItem["PRICE_FORMATED"]:''?>

												</div>
						                    </div>

											
										</div>

										<div class="col-xl-3 col-lg-4 col-sm-3 col-7 wr-btn">

										
										</div>

										<div class="remove-wrap col-1">
											<a href = "javascript:void(0);" class="remove-product" onclick="deleteProduct(<?=$arItem['ID']?>, {'action': 'delayed'})"></a>
										</div>

										<input type="hidden" name="DELAY_<?=$arItem["ID"]?>" value="Y"/>
									</div>

								<?endif;?>
							<?endforeach;?>

						<?endif;?>

					<?endif;?>

				</div>

			</div>
		</div>



		<? 
			if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["GIFTS_ON"]["VALUE"]["ACTIVE"] === "Y")
			{
				$APPLICATION->IncludeComponent(
					"bitrix:sale.gift.basket",
					"main",
					array(
						"VIEW"=>"GIFTS",
						"SHOW_PRICE_COUNT" => 1,
						"PRODUCT_SUBSCRIPTION" => 'N',
						'PRODUCT_ID_VARIABLE' => 'id',
						"PARTIAL_PRODUCT_PROPERTIES" => 'N',
						"USE_PRODUCT_QUANTITY" => 'N',
						"ACTION_VARIABLE" => "actionGift",
						"ADD_PROPERTIES_TO_BASKET" => "Y",

						"BASKET_URL" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"],
						"APPLIED_DISCOUNT_LIST" => $arResult["APPLIED_DISCOUNT_LIST"],
						"FULL_DISCOUNT_LIST" => $arResult["FULL_DISCOUNT_LIST"],

						"TEMPLATE_THEME" => "",
						"PRICE_VAT_INCLUDE" => "Y",
						"CACHE_GROUPS" => "Y",

						'BLOCK_TITLE' => "",
						'HIDE_BLOCK_TITLE' => "",
						'TEXT_LABEL_GIFT' => "",
						'PRODUCT_QUANTITY_VARIABLE' => "quantity",
						'PRODUCT_PROPS_VARIABLE' => "prop",
						'SHOW_OLD_PRICE' => "Y",
						'SHOW_DISCOUNT_PERCENT' =>"Y",
						'SHOW_NAME' => "",
						'SHOW_IMAGE' => "",
						'MESS_BTN_BUY' => "",
						'MESS_BTN_DETAIL' => "",
						'PAGE_ELEMENT_COUNT' => "8",
						'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
						'HIDE_NOT_AVAILABLE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"],
						'HIDE_NOT_AVAILABLE_OFFERS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"],
						"LINE_ELEMENT_COUNT" => "",
					),
					false
				); 
			}
		?>
	





		<input type="hidden" id="action_var" value="<?=htmlspecialcharsbx($arParams["ACTION_VARIABLE"])?>" />
	    <input type="hidden" id="quantity_float" value="<?=($arParams["QUANTITY_FLOAT"] == "Y") ? "Y" : "N"?>" />
	    <input type="hidden" id="auto_calculation" value="<?=($arParams["AUTO_CALCULATION"] == "N") ? "N" : "Y"?>" />

	    <input type="hidden" id="products_count" value=" <?=$normalCount?>" />
	    <input type="hidden" id="delayed_count" value=" <?=$delayCount?>" />

	    <?$PHOENIX_TEMPLATE_ARRAY["ORDER"]["BASKET_PRODUCTS_COUNT"] = $normalCount;?>
	    <?$PHOENIX_TEMPLATE_ARRAY["ORDER"]["BASKET_DELAY_COUNT"] = $delayCount;?>

	    <script>
	    	$(document).ready(function()
			{
				var params = {

					"products": <?=$normalCount?>,
					"delay": <?=$delayCount?>
				};
				showTabContent(params);
				scrollSideInit();

				<?if(!empty($arResult["BASKET_FILTER"])):?>
					arrBasketFilter = <?=CUtil::PhpToJSObject($arResult["BASKET_FILTER"], false, true);?>;
				<?endif;?>

				<?if(!empty($arResult["DELAY_FILTER"])):?>
					arrDelayFilter = <?=CUtil::PhpToJSObject($arResult["DELAY_FILTER"], false, true);?>;
				<?endif;?>
			}
			);
	    </script>

	<?endif;?>

	
	<?if(!isset($arParams["AJAX_CONTENT"])):?>

		<?$this->SetViewTarget('basket-side');?>

	<?endif;?>


	<?if(!isset($arParams["AJAX_CONTENT"]) || $arParams["AJAX_CONTENT"] == "side"):?>

		<div class="wrapperWidthFixedSrollBlock">

			<div class="selector-fixedSrollBlock">
				<div class="selector-fixedSrollBlock-real-height">

				    <div class="info-table active" data-target="form-fast-order">

				    	<?if($normalCount):?>
				            <div class="total sale_on">

				                
			                	<div class="desc-top">

			                		<div class="dynamic-show-hide-discount" style="display: <?=($arResult["DISCOUNT_PRICE_ALL"] > 0) ? "" : "none";?>"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TOTAL_WITH_DISCOUNT"]?></div>

			                		<div class="dynamic-show-hide-total-title" style="display: <?=($arResult["DISCOUNT_PRICE_ALL"] <= 0) ? "" : "none";?>"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TOTAL_TITLE"]?></div>


			                			
			                	</div>
				                

				                <div class="total-price bold parent-preload-circleG total-parent-preload-circleG">
				                    <div class="circleG-opacity" id="allSum_FORMATED">
				                        <?=str_replace(" ", "&nbsp;", $arResult["allSum_FORMATED"])?>
				                    </div>
				                    <div class="circleG-wrap">
				                        <div class="circleG circleG_1"></div>
				                        <div class="circleG circleG_2"></div>
				                        <div class="circleG circleG_3"></div>
				                    </div>
				                </div>


				                <?

				            		$showCoupon = false;

				            		if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["COUPON"]["VALUE"]["ACTIVE"] == "Y")
				            			$showCoupon = true;
				            		

				            		if(isset($_REQUEST["ORDER_ID"]) && strlen($_REQUEST["ORDER_ID"]) || strlen($arResult["ERROR_MESSAGE"]))
				            			$showCoupon = false;
				            	?>

				            	<?if($showCoupon):?>

				            		<?$emptyCouponList = empty($arResult['COUPON_LIST']);?>

				            		<div class="wr-hidden-container wr-coupon-container">

				            			<?if($emptyCouponList):?>

				            			<div class="btn-show-container coupon-show-desc"><span class="bord-bot white"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOP_COUPON_BTN_SHOW"]?></span></div>

				            			<?endif;?>

						            	<div class="<?=($emptyCouponList)?"d-none":""?> hidden-container form-uni-style coupon-container">

						                	<div class="input square">
						                        <div class="bg"></div>
						                        <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_COUPON_APPLY"]?></span>
						                        <input 
						                        		class="focus-anim input-coupon" 
						                        		onchange="enterCoupon();"
														type="text"
														class="focus-anim on-save"
														value=""

						                        >

						                        <a class="main-color in-input" type="input" onclick="enterCoupon();"></a>
						                        
						                    </div>


						                    <div class="wrapper-coupons clearfix coupons_block">

									            <?if (!$emptyCouponList):?>

									            	<?foreach ($arResult['COUPON_LIST'] as $oneCoupon):?>

										            	<?
										            		$couponClass = 'disabled';
															switch ($oneCoupon['STATUS'])
															{
																case Bitrix\Sale\DiscountCouponsManager::STATUS_NOT_FOUND:
																case Bitrix\Sale\DiscountCouponsManager::STATUS_FREEZE:
																	$couponClass = 'bad';
																	break;
																case Bitrix\Sale\DiscountCouponsManager::STATUS_APPLYED:
																	$couponClass = 'good';
																	break;
															}
										            	?>

										            	<div class="coupon-one <? echo $couponClass; ?>">

											            	<span class="coupon-name"><?=$oneCoupon['COUPON']?></span>

															<span class="coupon-close remove-coupon" data-coupon="<? echo htmlspecialcharsbx($oneCoupon['COUPON']); ?>"></span>

														</div>

									            	<?endforeach;?>
									            	<?
									            		unset($couponClass, $oneCoupon);
									            	?>

									            <?endif;?>

								            </div>

						                </div>

					                </div>

					            <?endif;?>


				            </div>
			            <?endif;?>



			        	<div class="updesc dynamic-show-hide-discount" style="display: <?=($arResult["DISCOUNT_PRICE_ALL"] > 0) ? "" : "none";?>">

			        		<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DISCOUNT_COMMENT"]?>

					        <span class="total bold parent-preload-circleG total-parent-preload-circleG">

					            <span class="circleG-opacity" id="DISCOUNT_PRICE_ALL"><?=$arResult["DISCOUNT_PRICE_FORMATED"]?></span>

					            <div class="circleG-wrap small">
					                <div class="circleG circleG_1"></div>
					                <div class="circleG circleG_2"></div>
					                <div class="circleG circleG_3"></div>
					            </div>
					            
					        </span>

					    </div>

				       

				        <div class="buttons basket-buttons"

				        	style = "display: <?=
			            	
			            	($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MIN_SUM']['VALUE'] <= $arResult["allSum"]) ? "" : "none";

			            	?>"
			            >
				        	<?$basket_url = CPhoenix::getBasketUrl(SITE_DIR, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]);?>

				        	<?if($showBuyBtnOnly):?>

				        		<div class="wrapper-a-btn">

						        	<a class="first-b main-color button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> shine big show-fast-order-form" data-target="form-fast-order">
					                    <?= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_BASKET"]["~VALUE"];?>
					                </a>

				                </div>

				        	<?else:?>

					        	<div class="wrapper-a-btn">

						        	<a href = "<?=$basket_url?>order/" class="first-b main-color button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> shine big">
					                    <?
					                    	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ORDER_NAME"]["~VALUE"])>0)
					                    		echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ORDER_NAME"]["~VALUE"];
					                    	else
					                    		echo $PHOENIX_TEMPLATE_ARRAY["MESS"]["CART_ORDER"];
					                    ?>
					                </a>

				                </div>

				                <?if($showBuyBtn):?>

				                	<div class="wrapper-a-btn">

						                <a class="sec-b show-fast-order-form" data-target="form-fast-order">

						                    <span class="bord-bot">
						                
							                    <?= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_BASKET"]["~VALUE"];?>

						                    </span>
						                </a>
						            </div>

				                <?endif;?>

			                <?endif;?>


				        </div>

				        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_COMMENT']['~VALUE']) > 0):?>

				            <div class="comment">
				               <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_COMMENT']['~VALUE']?>
				            </div> 

				        <?endif;?>



			            <div 

			            	class="alert-message-min-sum"
			            	style = "display: <?=
			            	
			            	($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MIN_SUM']['VALUE'] > $arResult["allSum"]) ? "" : "none";

			            	?>">
			            	<div class="text-top bold"><?= $PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_ALERT_MIN_SUM_TEXT_TOP"].CurrencyFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MIN_SUM']['VALUE'], $arResult["CURRENCY"])?></div>

			            	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_FORM_MINPICE_ALERT"]["VALUE"])):?>
			            		<div class="text-bottom"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_FORM_MINPICE_ALERT"]["~VALUE"]?></div>
			            	<?endif;?>
			            </div>

			            <div class="bottom-dots"></div>

				    </div>

				    <div class="form-order" data-target="form-fast-order">
			            <form id = "form-fast-order-basket" action="/" class="form-fast-order-basket form send dark" method="post" role="form">

			                <input name="header" type="hidden" value="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FAST_ORDER_FORM_TITLE"]?>">

			                <table class="wrap-act">
			                    <tr>
			                        <td>
			                            <div class="questions active">
			                                <div class="row">

			                                    <div class="col-12 title-form main1">
			                                        <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FAST_ORDER_FORM_TITLE"]?>
			                                    </div>

			                                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['FAST_ORDER_FORM_SUBTITLE']["VALUE"])):?>

			                                        <div class="col-12 subtitle-form">
			                                            <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['FAST_ORDER_FORM_SUBTITLE']["VALUE"]?>
			                                        </div>

			                                    <?endif;?>

			                                    <?$showBtn = false;?>

			                                    <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"])):?>


				                                    <?foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"] as $key => $value)
				                                        {
				                                            $curField = array();
				                                            $require = "";

				                                            if($value == "Y")
				                                            {

				                                            	$showBtn = true;

				                                                $curField = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUES"][$key];


				                                                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"][$key] == "Y")
				                                                    $require = "require";

				                                                ?>

				                                                <div class="col-12">

				                                                    <?if($curField["PROPS"]["TYPE"] == "DATE"):?>


				                                                    	<div class="input date-wrap <?=$require?>">

			                                                                <div class="bg"></div>
			                                                                <span class="desc"><?=$curField["DESCRIPTION"]?></span>
			                                                                <input class="
			                                                                	date-group 
			                                                                	focus-anim 
			                                                                	input-date
			                                                                	<?=$require?>
			                                                                    input_<?=$curField["PROPS"]["CODE"]?>
			                                                                    <?=$ymWizard?>
			                                                                "  name="<?=$curField["PROPS"]["CODE"]?>" type="text">
			                                                            </div>
				                                                    

				                                                    <?elseif($curField["PROPS"]["TYPE"] == "TEXTAREA"):?>

				                                                        <div class="input input-textarea input_textarea_<?=$curField["PROPS"]["CODE"]?>">
				                                                            <div class="bg"></div>
				                                                            <span class="desc"><?=$curField["DESCRIPTION"]?></span>

				                                                            <textarea class='focus-anim <?=$require?> <?=$ymWizard?>' name="<?=$curField["PROPS"]["CODE"]?>"></textarea>
				                                                        </div>

				                                                   

				                                                    <?else:?>

				                                                    	<div class="input">
				                                                            <div class="bg"></div>
				                                                            <span class="desc"><?=$curField["DESCRIPTION"]?></span>
				                                                            <input 

				                                                                <?$class = "";?>

				                                                                name="<?=$curField["PROPS"]["CODE"]?>"


				                                                                <?if($curField["PROPS"]["IS_EMAIL"] == "Y"):?>

				                                                                    <?$class = "email";?>
				                                                                    type="email"

				                                                                <?elseif($curField["PROPS"]["IS_PHONE"] == "Y"):?>

				                                                                    <?$class = "phone";?>
				                                                                    type="text"

				                                                                <?else:?>

				                                                                    type="text"

				                                                                <?endif;?>
				                                                                
				                                                                class='
				                                                                    focus-anim 
				                                                                    <?=$class?>
				                                                                    <?=$require?>
				                                                                    input_<?=$curField["PROPS"]["CODE"]?>
				                                                                    <?=$ymWizard?>
				                                                                '
				                                                            >
				                                                            
				                                                        </div>


				                                                    <?endif;?>
				                                                </div>

			                                                	<?
				                                            }
				                                            
				                                        }

				                                        unset($curField);
				                                    ?>

			                                    <?endif;?>


			                                    <?if($showBtn):?>

				                                    <div class="col-12">
				                                        <div class="input-btn">
				                                            <div class="load">
				                                                <div class="xLoader form-preload">
				                                                    <div class="audio-wave">
				                                                        <span></span>
				                                                        <span></span>
				                                                        <span></span>
				                                                        <span></span>
				                                                        <span></span>
				                                                    </div>
				                                                </div>
				                                            </div>

				                                            <button 
				                                                class="

				                                                button-def main-color big active <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?> btn-submit fast-order-basket"

				                                                name="form-submit"
				                                                type="button"

				                                                >
				                                                <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FAST_ORDER_FORM_BUTTON"]?>
				                                            </button>
				                                        </div>
				                                    </div>

			                                    <?endif;?>
			                                </div>

			                                <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM'])):?>

			                                    <div class="wrap-agree">

			                                        <label class="input-checkbox-css">
			                                            <input type="checkbox" class="agreecheck" name="checkboxAgree" value="agree" <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["POLITIC_CHECKED"]['VALUE']["ACTIVE"] == 'Y'):?> checked<?endif;?>>
			                                            <span></span>   
			                                        </label>    

			                                        <div class="wrap-desc">                                                                    
			                                            <span class="text"><?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["POLITIC_DESC"]['VALUE'])>0):?><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["POLITIC_DESC"]['~VALUE']?><?else:?><?=GetMessage('PHOENIX_MODAL_FORM_AGREEMENT')?><?endif;?></span>


			                                            <?$agrCount = count($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM']);?>
			                                            <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM'] as $k => $arAgr):?>

			                                                <a class="call-modal callagreement" data-call-modal="agreement<?=$arAgr['ID']?>"><?if(strlen($arAgr['PROPERTIES']['CASE_TEXT']['VALUE'])>0):?><?=$arAgr['PROPERTIES']['CASE_TEXT']['VALUE']?><?else:?><?=$arAgr['NAME']?><?endif;?></a><?if($k+1 != $agrCount):?><span>, </span><?endif;?>

			                                                
			                                            <?endforeach;?>
			                                         
			                                        </div>

			                                    </div>
			                                <?endif;?>
			                            </div>
			                            
			                            <div class="thank"></div>
			                        </td>
			                    </tr>
			                </table>

			            </form>
			        </div>

				    <div class="style-cart-back hide-fast-order-form" data-target="form-fast-order"></div>
			    </div>

		    </div>

	    </div>

	<?endif;?>

	<script>

		var minSumCart = parseFloat(<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MIN_SUM']['VALUE']?>);
		var sumCart = parseFloat(<?=$arResult["allSum"]?>);

		if(minSumCart > sumCart)
			$(".clear-basket-node-control").addClass('hidden');
		else
			$(".clear-basket-node-control").removeClass('hidden');
		 
	</script>


	<?if(!isset($arParams["AJAX_CONTENT"])):?>
	    <?$this->EndViewTarget();?>
    <?endif;?>


	
<?else:?>

	<?if( !isset($arParams["AJAX_CONTENT"]) || $arParams["AJAX_CONTENT"] == "products"):?>

		<div class="basket-empty-message">
		
			<?echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_PAGE_EMPTY_MESS"]["~VALUE"];

			/*ShowError($arResult["ERROR_MESSAGE"]);*/?>

		</div>
	<?endif;?>
<?endif;?>