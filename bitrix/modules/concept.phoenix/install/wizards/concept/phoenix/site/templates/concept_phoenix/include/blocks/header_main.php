<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="static-board hidden-sm hidden-xs">

    <div class="container">

    	<?
    		
    		$order_logotype = "order-1";
    		$order_contacts = "order-2";

    		if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_VIEW"]["VALUE"] == "two")
    		{
    			$order_logotype = "order-2";
    			$order_contacts = "order-1";
    		}
    	?>

    	<div class="wrapper-head-top">
            <div class="inner-head-top row align-items-center">

            	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_VIEW"]["VALUE"] == "two"):?>


	            	<div class="<?=(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["VALUE"])>0)?"col-xl-3 col-lg-4":""?> col board-contacts <?=$order_contacts?>">

			    		<div class=
			    		"
			    			row no-gutters align-items-center wrapper-item 

			    		">
			    			<?if($PHOENIX_MENU>0):?>
			        			<div class="col-auto board-menu order-first">
			        				<div class="wrapper-icon-hamburger open-main-menu">
					                    <div class="icon-hamburger">
					                        <span class="icon-bar"></span>
					                        <span class="icon-bar"></span>
					                        <span class="icon-bar"></span>
					                    </div>
				                    </div>
			        			</div>
			    			<?endif;?>

			    			<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("contacts-1");?>

			    			<?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"]) || !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"]) || ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['SHOW_APPLY']["VALUE"]["ACTIVE"] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['SHOW_IN_HEAD']["VALUE"]["ACTIVE"] == "Y" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["VALUE"])>0)):?>

				    			<div class="col-11 wrapper-contacts">
			    					<?contactHTML($regionsIsset)?>
				    			</div>

			    			<?endif;?>

			    			<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("contacts-1");?>

			    		</div>
			    		
			    	</div>

			    	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["VALUE"])>0):?>
						<div class="col hidden-md hidden-lg text-html <?=$order_contacts?>">

							<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["~VALUE"]?>

						</div>
					<?endif;?>

            	<?else:?>

            		<?if($PHOENIX_MENU>0):?>
            			<div class="col-auto board-menu order-first">
            				<div class="wrapper-icon-hamburger open-main-menu">
			                    <div class="icon-hamburger">
			                        <span class="icon-bar"></span>
			                        <span class="icon-bar"></span>
			                        <span class="icon-bar"></span>
			                    </div>
		                    </div>
            			</div>
        			<?endif;?>

	            	<div class="col board-contacts <?=$order_contacts?>">

	            		<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("contacts-2");?>

		            		<?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"]) || !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"]) || ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['SHOW_APPLY']["VALUE"]["ACTIVE"] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['SHOW_IN_HEAD']["VALUE"]["ACTIVE"] == "Y" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["VALUE"])>0)):?>

			            		<div class=
			            		"
			            			row no-gutters align-items-center wrapper-item 

			            		">

			            			<div class="<?=($order_contacts == "order-2")?"col-12":"col-7"?> wrapper-contacts">
			            				<?contactHTML($regionsIsset)?>
			            			</div>
			            		</div>

		            		<?endif;?>

	            		<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("contacts-2");?>
	            		
	            	</div>

	            	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["VALUE"])>0):?>
						<div class="col hidden-md hidden-lg text-html <?=$order_contacts?>">

							<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["~VALUE"]?>

						</div>
					<?endif;?>

            	<?endif;?>


            	<div class="col-md-2 col-4 wrapper-logotype <?=$order_logotype?>">

	    			<div class="row no-gutters align-items-center wrapper-item">
	    				<div class="col">

	    					<?if(!$bIsMainPage):?>
                                <a href="<?=SITE_DIR?>" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["LOGO_DOMAIN_TITLE"]?>">
                            <?endif;?>

	            				<?=$PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"]?>
            				
            				<?if(!$bIsMainPage):?>
                            	</a>
                            <?endif;;?>
	    					
	    				</div>
	    			</div>

            	</div>


            	<div class="col-5 board-info order-last">
            		<div class="row no-gutters align-items-center wrapper-item">


            			<div class="col-xl-6 col-4 wrapper-cabinet">

            				<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("cabinet-1");?>

	            				<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CABINET"]["VALUE"]["ACTIVE"] == "Y"):?>

	            					<?=CPhoenix::ShowCabinetLink();?>

	        					<?endif;?>

        					<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("cabinet-1");?>


            			</div>

	            		<div class="col-xl-6 col-8 row no-gutters justify-content-end counts-board">

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

    <?

        if($PHOENIX_MENU>0 && ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"] == "on_board" || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"] == "on_line"))
        {
        	
        	$APPLICATION->IncludeComponent(
				"concept:phoenix.menu",
				".default",
				Array(
					"COMPONENT_TEMPLATE" => ".default",
					"COMPOSITE_FRAME_MODE" => "N",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "A",
					"CACHE_USE_GROUPS" => "Y"
				)
			);
        }
	?>

        
</div>