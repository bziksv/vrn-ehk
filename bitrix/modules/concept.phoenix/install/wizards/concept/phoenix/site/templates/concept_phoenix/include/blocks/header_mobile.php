<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="mobile-menu d-md-none">
	<div class="container">
		<div class="in-mobile-menu row align-items-center justify-content-between">

			<div class="col-auto <?if($PHOENIX_MENU>0):?>open-main-menu<?endif;?> item">
				<div class="wr-btns">
					
					<?if($PHOENIX_MENU>0):?>
						<div class="icon-hamburger">
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					    </div>
				    <?endif;?>
				</div>
				
			</div>

			<div class="col-auto item">
				<div class="wr-btns">
					
					<?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"]) || !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"]) || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["CONTACT"] == 'Y' && $PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"]):?>
						<a class="ic-callback-mob common-svg-style open_modal_contacts">
						</a>
					<?endif?>

				</div>
			</div>

			<div class="col item">

				<?if(!$bIsMainPage):?>
                    <a href="<?=SITE_DIR?>">
                <?endif;?>

                	<?=$PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"]?>
				
				<?if(!$bIsMainPage):?>
                	</a>
                <?endif;;?>
	    		
			</div>

			<div class="col-auto item">
				<div class="wr-btns">
					<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y"):?>

					<div class="mini-search-style mob open-search-top common-svg-style"></div>

					<?endif;?>

				</div>

			</div>

			<div class="col-auto item">
				<div class="wr-btns">
					<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"] ["ACTIVE"] == "Y"):?>
						<div class="ic-cart-mob count-basket-items-parent common-svg-style">
							<span class="count-basket"></span>
							<a class="url-basket" href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"></a>
						</div>
					<?endif;?>


				</div>
			</div>

		</div>
	</div>

</div>