<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="fix-board hidden-sm hidden-xs">

	<?//if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_FIXED_VIEW"]["VALUE"] == "view-open"):?>

    	<div class="container">

			<div class="wrapper-head-top d-none d-sm-block">
				<div class="row align-items-center wrapper-item">
            		<div class="col-xl-3 col-lg-4 col-md-5">

						<div class=
						"
						row no-gutters align-items-center wrapper-item 

						<?if($PHOENIX_MENU>0):?>

							menu-width

						<?endif;?>
						">

							<?if($PHOENIX_MENU>0):?>
								<div class="col-auto wrapper-menu">
									<div class="wrapper-icon-hamburger open-main-menu">
							            <div class="icon-hamburger">
							                <span class="icon-bar"></span>
							                <span class="icon-bar"></span>
							                <span class="icon-bar"></span>
							            </div>
							        </div>
								</div>
							<?endif;?>

							<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("contacts-3");?>

								<?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"]) || !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"])|| $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['SHOW_APPLY']["VALUE"]["ACTIVE"] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['SHOW_IN_HEAD']["VALUE"]["ACTIVE"] == "Y" && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"])):?>

									<div class="col-11 wrapper-contacts">
										<?contactHTML()?>
									</div>

								<?endif;?>

							<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("contacts-3");?>



						</div>

					</div>

					<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["VALUE"])>0):?>
						<div class="col hidden-md text-html">

							<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["~VALUE"]?>

						</div>
					<?endif;?>


					<div class="col hidden-md">

						<?
	            			if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['SHOW_IN']['VALUE']['IN_MENU'] == "Y" )
	            				$APPLICATION->IncludeComponent("concept:phoenix.search.line", "fix-header",
	            					Array(
				            		"CONTAINER_ID" => "search-page-input-container-fix-header",
				            		"INPUT_ID" => "search-page-input-fix-header",
				            		"COMPOSITE_FRAME_MODE" => "N",
				            		"SHOW_RESULTS" => "",
				            		"SEARCH_CONTAINER_WIDTH" => "half-width"
				            	)

	            			);
	            		?>

					</div>

					<div class="col-2 d-lg-none wrapper-logotype">
						<?if(!$bIsMainPage):?>
                            <a href="<?=SITE_DIR?>" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["LOGO_TOMAIN_TITLE"]?>">
                        <?endif;?>

            				<?=$PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"]?>
        				
        				<?if(!$bIsMainPage):?>
                        	</a>
                        <?endif;;?>
					</div>


					<div class="col-xl-5 col-lg-4 col-5">
						<div class="row no-gutters align-items-center wrapper-item">
							
							<div class="col-xl-6 col-2 wrapper-cabinet">

								<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("cabinet-2");?>

									<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CABINET"]["VALUE"]["ACTIVE"] == "Y"):?>

									<?=CPhoenix::ShowCabinetLink();?>

									<?endif;?>

									<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("cabinet-2");?>
								
							</div>
							

							<div class="col-xl-6 col-10 row no-gutters cart-delay-compare justify-content-end">

		            			<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"] ["ACTIVE"] == "Y"):?>

			            			<div class="col-4">
				            			<div class="basket-quantity-info-icon cart count-basket-items-parent"><span class="count count-basket">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"></a></div>
				            		</div>

			            		<?endif;?>

			            		<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y"):?>

				            		<div class="col-4">
				        				<div class="basket-quantity-info-icon delay count-delay-parent"><span class="count count-delay">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL_DELAYED"]?>"></a></div>
			        				</div>

		        				<?endif;?>

		        				<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y"):?>

			        				<div class="col-4">
				        				<div class="basket-quantity-info-icon compare count-compare-parent"><span class="count count-compare">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL_COMPARE"]?>"></a></div>
				            		</div>

				            	<?endif;?>
		            		</div>

						</div>
					</div>


            	</div>
    		</div>
    	</div>

	<?//endif;?>

</div>