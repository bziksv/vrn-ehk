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
$curJsId = $this->randString();
?>

<div id="bx-set-const-<?=$curJsId?>" class="bx-set-constructor" data-obj="<?=$curJsId?>">

	<div class="row">

		<div class="col-md-4 col-12 wr-product-item">
			<div class="product-item flat phx-border">
				<div class="wr-img row no-gutters align-items-center">
                    <div class="col"><img class="d-block mx-auto lazyload" data-src="<?=$arResult["ELEMENT"]["PREVIEW_PICTURE_SRC"]?>" alt=""></div>
                    <div class="plus-label">+</div>
                </div>
                
                <div class="name" target="_blank"><span><?=$arResult["ELEMENT"]["NAME"]?></span><div class="measure-label"><?=$arResult["ELEMENT"]["BASKET_QUANTITY"];?>&nbsp;<?=$arResult["ELEMENT"]["MEASURE"]["SYMBOL_RUS"];?></div></div>
                <?if(strlen($arResult["ELEMENT"]["SKU_LIST"])>0):?>
					<div class="sku"><?=$arResult["ELEMENT"]["SKU_LIST"]?></div>
				<?endif;?>

				<div class="wr-price row no-gutters">
					<div class="price"><span class="price-value"><?=$arResult["ELEMENT"]["PRICE_PRINT_DISCOUNT_VALUE"]?></span><?if(strlen($arResult["ELEMENT"]["MEASURE"]["SYMBOL_RUS"])>0):?><span class="measure">&nbsp;/&nbsp;<?=$arResult["ELEMENT"]["MEASURE"]["SYMBOL_RUS"];?></span><?endif;?></div>
					<?if (!($arResult["ELEMENT"]["PRICE_VALUE"] == $arResult["ELEMENT"]["PRICE_DISCOUNT_VALUE"])):?><div class="old-price align-self-end"><?=$arResult["ELEMENT"]["PRICE_PRINT_VALUE"]?></div><?endif?>
				</div>

			</div>
			
		</div>
		

		<div class="col-md-4 col-12">
			<div class="row" data-role="set-items">

				<?foreach($arResult["SET_ITEMS"]["DEFAULT"] as $key => $arItem):?>
					<div class="col-12 wr-product-item">
						<div class="product-item list phx-border"

							data-id="<?=htmlspecialcharsbx($arItem["ID"])?>"
							data-section-id="<?=htmlspecialcharsbx($arItem["IBLOCK_SECTION_ID"])?>"
							data-img="<?=htmlspecialcharsbx($arItem["PREVIEW_PICTURE_SRC"])?>"
							data-url="<?=htmlspecialcharsbx($arItem["DETAIL_PAGE_URL"])?>"
							data-name="<?=htmlspecialcharsbx($arItem["NAME"])?>"
							data-sku-list="<?=htmlspecialcharsbx($arItem["SKU_LIST"])?>"
							data-price="<?=htmlspecialcharsbx($arItem["PRICE_DISCOUNT_VALUE"])?>"
							data-print-price="<?=htmlspecialcharsbx($arItem["PRICE_PRINT_DISCOUNT_VALUE"])?>"
							data-old-price="<?=htmlspecialcharsbx($arItem["PRICE_VALUE"])?>"
							data-print-old-price="<?=htmlspecialcharsbx($arItem["PRICE_PRINT_VALUE"])?>"
							data-diff-price="<?=htmlspecialcharsbx($arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"])?>"
							data-measure="<?=htmlspecialcharsbx($arItem["MEASURE"]["SYMBOL_RUS"])?>"
							data-quantity="<?=htmlspecialcharsbx($arItem["BASKET_QUANTITY"])?>"

						>
							<div class="row no-gutters">
								<div class="col-3 wr-img">
									<img class="d-block mx-auto lazyload" data-src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" alt="">
								</div>
								<div class="col-9 align-self-center">
									<a class="name" href="<?=$arItem["DETAIL_PAGE_URL"]?>" target="_blank">

										<span><?=$arItem["NAME"]?></span>
										<div class="measure-label"><?=$arItem["BASKET_QUANTITY"];?>&nbsp;<?=$arItem["MEASURE"]["SYMBOL_RUS"];?></div>
										
									</a>
					                <?if(strlen($arItem["SKU_LIST"])>0):?>
										<div class="sku"><?=$arItem["SKU_LIST"]?></div>
									<?endif;?>

									<div class="wr-price row no-gutters">
										<div class="price"><span class="price-value"><?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?></span><?if(strlen($arItem["MEASURE"]["SYMBOL_RUS"])>0):?><span class="measure">&nbsp;/&nbsp;<?=$arItem["MEASURE"]["SYMBOL_RUS"];?></span><?endif;?></div>
										<?if (!($arItem["PRICE_VALUE"] == $arItem["PRICE_DISCOUNT_VALUE"])):?><div class="old-price align-self-end"><?=$arItem["PRICE_PRINT_VALUE"]?></div><?endif?>
									</div>
								</div>
							</div>

							<div class="item-delete" data-role="set-delete-btn"></div>

						</div>
						
					</div>
				<?endforeach?>
			</div>

			<div class ="text-content d-none empty-set" data-set-message="empty-set"><p><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_ALERT"]?></p></div>

			<a href="#set-product-other-list-<?=$curJsId?>" class="scroll-after button-gray show-set-products-list"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_CUSTOM_COMPLECT"]?></a>

			
			
		</div>

		<div class="col-md-4 col-12">
			<div class="wr-result">

				<div class="wr-head">
					<div class="title">
						<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_COMPLITED"]?>
					</div>
					<div class="description"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_COUNT_PRODUCTS"]?>&nbsp;<span class="count-set-products" data-role="info-count-items"><?=$arResult["SET_ITEMS_COUNT"]?>&nbsp;<?=CPhoenix::getTermination($arResult["SET_ITEMS_COUNT"],
						array(
							$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_CNT_1"],
							$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_CNT_2"],
							$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_CNT_3"],
							$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_CNT_4"]
						))?></span></div>
				</div>

				<div class="wr-sum">
					<div class="wr-price row no-gutters">
						<div class="price">
							<span class="price-value" data-role="set-price"><?=$arResult["SET_ITEMS"]["PRICE"]?></span>
						</div>
						<div class="old-price align-self-end <?=($arResult['SHOW_DEFAULT_SET_DISCOUNT'] ? '' : 'd-none'); ?>" data-role="set-old-price"><?=$arResult["SET_ITEMS"]["OLD_PRICE"]?></div>
					</div>
					<div class="wr-discount clearfix <?=($arResult['SHOW_DEFAULT_SET_DISCOUNT'] ? '' : 'd-none'); ?>"><span class="desc-discount"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ECONOMY"]?></span><span class="actual-econom bold" data-role="set-diff-price"><?=$arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"]?></span></div>
				</div>

				<a class="btn-add-set-products main-color bold <?=($arResult["ELEMENT"]["CAN_BUY"] ? '' : 'd-none;"')?>" data-role="set-buy-btn">
					<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_ADD2BASKET_BTN"]?>
				</a>
			</div>
		</div>

	</div>


	<div class="set_product_other d-none" id="set-product-other-list-<?=$curJsId?>">

        <div class="cart-title <?if(strlen($arParams["TITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR_OTHER"]) <= 0):?>empty-title<?endif;?> ">
        	<div class="line"></div>

        	<div class="row align-items-center justify-content-between">
        		<div class="col">
        			<?if(strlen($arParams["TITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR_OTHER"]) > 0):?>
		                <div class="title"><?=$arParams["TITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR_OTHER"]?></div>
		            <?endif;?>
        		</div>
        		<div class="col-auto"><div class="wr-btn"><a class="button-second elips hide-set-products-list scroll-after" href="#set_product_constructor"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_HIDE"]?></a></div></div>
        	</div>
            
        </div>
    
    

	    <div class="set_product_other_container <?=(empty($arResult["SET_ITEMS"]["OTHER"]) ? 'd-none;"' : '')?>" data-role="slider-parent-container">

	    	<?if(!empty($arResult["SET_SECTIONS"])):?>
				<div class="set-products-other-tabs row no-gutters">
					<?
						$first=true;
						$curSection=0;
					?>
		    		<?foreach($arResult["SET_SECTIONS"] as $key=>$arItem):?>
				    	
			    		<div class="col-auto set-products-other-tab main-color-border-active <?=($first)?"active":""?> <?=$curJsId?>" data-count ="<?=$arItem["COUNT"]?>" data-value="<?=$arItem["ID"]?>">
			    			<?=$arItem["NAME"]?><span><?=$arItem["COUNT"]?></span>
			    		</div>

			    		<?
				    		if($first)
				    		{
				    			$first = false;
				    			$curSection=$arItem["ID"];
				    		}
			    		?>
				    	
			    	<?endforeach;?>


			    	<input class="curSetPropductsTabSection" type="hidden" value="<?=$curSection?>">
		    	</div>
		    <?endif;?>

		    
	    	
	    	<div class="row" data-role="set-other-items">

	    		<?if(!empty($arResult["SET_ITEMS"]["OTHER"])):?>

		    		<?foreach($arResult["SET_ITEMS"]["OTHER"] as $key => $arItem):?>

			    		<div class="col-lg-4 col-6 wr-product-item tab-item <?=$curJsId?> <?=($curSection == $arItem["IBLOCK_SECTION_ID"])?"":"d-none"?>" data-value="<?=$arItem["IBLOCK_SECTION_ID"]?>">
							<div class="product-item flat"

								data-id="<?=htmlspecialcharsbx($arItem["ID"])?>"
								data-section-id="<?=htmlspecialcharsbx($arItem["IBLOCK_SECTION_ID"])?>"
								data-img="<?=htmlspecialcharsbx($arItem["PREVIEW_PICTURE_SRC"])?>"
								data-url="<?=htmlspecialcharsbx($arItem["DETAIL_PAGE_URL"])?>"
								data-name="<?=htmlspecialcharsbx($arItem["NAME"])?>"
								data-sku-list="<?=htmlspecialcharsbx($arItem["SKU_LIST"])?>"
								data-price="<?=htmlspecialcharsbx($arItem["PRICE_DISCOUNT_VALUE"])?>"
								data-print-price="<?=htmlspecialcharsbx($arItem["PRICE_PRINT_DISCOUNT_VALUE"])?>"
								data-old-price="<?=htmlspecialcharsbx($arItem["PRICE_VALUE"])?>"
								data-print-old-price="<?=htmlspecialcharsbx($arItem["PRICE_PRINT_VALUE"])?>"
								data-diff-price="<?=htmlspecialcharsbx($arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"])?>"
								data-measure="<?=htmlspecialcharsbx($arItem["MEASURE"]["SYMBOL_RUS"])?>"
								data-quantity="<?=htmlspecialcharsbx($arItem["BASKET_QUANTITY"])?>"
								<?
								if (!$arItem['CAN_BUY'] && $first)
								{
									echo 'data-not-avail="yes"';
									$first = false;
								}

								?>

							>
								<div class="row no-gutters">
									<div class="wr-img row no-gutters align-items-center">
					                    <div class="col"><img class="d-block mx-auto" src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" alt=""></div>
					                </div>
									

									<a class="name" href="<?=$arItem["DETAIL_PAGE_URL"]?>" target="_blank"><span><?=$arItem["NAME"]?></span><div class="measure-label"><?=$arItem["BASKET_QUANTITY"];?>&nbsp;<?=$arItem["MEASURE"]["SYMBOL_RUS"];?></div></a>
					                <?if(isset($arItem["SKU_LIST"])):?>
										<div class="sku"><?=$arItem["SKU_LIST"]?></div>
									<?endif;?>

									<div class="wr-price row no-gutters">
										<div class="price"><span class="price-value"><?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?></span><?if(strlen($arItem["MEASURE"]["SYMBOL_RUS"])>0):?><span class="measure">&nbsp;/&nbsp;<?=$arItem["MEASURE"]["SYMBOL_RUS"];?></span><?endif;?></div>
										<?if (!($arItem["PRICE_VALUE"] == $arItem["PRICE_DISCOUNT_VALUE"])):?><div class="old-price align-self-end"><?=$arItem["PRICE_PRINT_VALUE"]?></div><?endif?>
									</div>
								</div>

								<?if ($arItem['CAN_BUY']):?>

									<a class="add-set-product-constructor main-color" data-role="set-add-btn" data-toggle="tooltip" data-placement="right" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_ADD2COMPLECT"]?>">+</a>

								<?else:?>

									<span class="bx-catalog-set-item-notavailable"><?=GetMessage('CATALOG_SET_MESS_NOT_AVAILABLE');?></span>

								<?endif;?>

							</div>
							
						</div>



		    		<?endforeach;?>
	    		<?endif;?>
	    		
	    	</div>

	    	
			

	    </div>

    </div>


</div>


<?
$arJsParams = array(
	"numSliderItems" => count($arResult["SET_ITEMS"]["OTHER"]),
	"numSetItems" => count($arResult["SET_ITEMS"]["DEFAULT"]),
	"jsId" => $curJsId,
	"parentContId" => "bx-set-const-".$curJsId,
	"ajaxPath" => $this->GetFolder().'/ajax.php',
	"canBuy" => $arResult["ELEMENT"]["CAN_BUY"],
	"currency" => $arResult["ELEMENT"]["PRICE_CURRENCY"],
	"mainElementPrice" => $arResult["ELEMENT"]["PRICE_DISCOUNT_VALUE"],
	"mainElementOldPrice" => $arResult["ELEMENT"]["PRICE_VALUE"],
	"mainElementDiffPrice" => $arResult["ELEMENT"]["PRICE_DISCOUNT_DIFFERENCE_VALUE"],
	"mainElementBasketQuantity" => $arResult["ELEMENT"]["BASKET_QUANTITY"],
	"lid" => SITE_ID,
	"iblockId" => $arResult["ELEMENT"]["IBLOCK_ID"],
	"basketUrl" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"],
	"setIds" => $arResult["DEFAULT_SET_IDS"],
	/*"offersCartProps" => $arParams["OFFERS_CART_PROPERTIES"],*/
	"itemsRatio" => $arResult["BASKET_QUANTITY"],
	"noFotoSrc" => SITE_TEMPLATE_PATH."/images/ufo.png",
	"messages" => array(
		"EMPTY_SET" => GetMessage('CT_BCE_CATALOG_MESS_EMPTY_SET'),
		"ADD_BUTTON" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_ADD2COMPLECT"],
		"SET_CONSTRUCTOR_CNT_1"=>$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_CNT_1"],
		"SET_CONSTRUCTOR_CNT_2"=>$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_CNT_2"],
		"SET_CONSTRUCTOR_CNT_3"=>$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_CNT_3"],
		"SET_CONSTRUCTOR_CNT_4"=>$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_CONSTRUCTOR_CNT_4"]
	)
);
?>
<script type="text/javascript">
	BX.ready(function(){
		new BX.Catalog.SetConstructor(<?=CUtil::PhpToJSObject($arJsParams, false, true, true)?>);
	});
</script>