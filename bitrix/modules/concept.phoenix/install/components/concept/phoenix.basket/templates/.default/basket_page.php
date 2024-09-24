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
?>

<?
	global $PHOENIX_TEMPLATE_ARRAY;

	$showbasketProducts = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"]=="Y") ? true: false;

	$orderConfirm = false;
    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_PAGES"]["VALUE"]=="one")
    	$orderConfirm = isset($_REQUEST["ORDER_ID"]) && strlen($_REQUEST["ORDER_ID"])>0;


    $showBuyBtn = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_BASKET_ON"]["VALUE"]["ACTIVE"] == "Y") ? true : false;

    if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"])&& !in_array('Y', $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"]))
	$showBuyBtn = false;

	$showBuyBtnOnly = false;

	if($showBuyBtn)
		$showBuyBtnOnly = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_BASKET_ONLY"]["VALUE"]["ACTIVE"] == "Y") ? true : false;


	$user_registration = \Bitrix\Main\Config\Option::get("main", "new_user_registration", "");
	$email_confirmation = \Bitrix\Main\Config\Option::get("main", "new_user_registration_email_confirmation", "");
	$email_required = \Bitrix\Main\Config\Option::get("main", "new_user_email_required", "");
	$bConfirmReq = ($email_confirmation == "Y" && $email_required == "Y");

	$showAuthForm = (($user_registration == "N" || $bConfirmReq) && !$USER->IsAuthorized());
?>

<div class=
"
	page-header
	sections
	cover
	parent-scroll-down
	<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>
	phoenix-firsttype-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
	padding-bottom-section
	basket-order

" 
	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_PAGE_HEADBG"]["VALUE"])>0):?>

		<?$bg_pic = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_PAGE_HEADBG"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>

		style="background-image: url(<?=$bg_pic["src"]?>);"

	<?endif;?>
>

	<div class="shadow-tone <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>"></div>

	<div class="top-shadow"></div>

	<div class="container z-i-9">

		<?if(!$orderConfirm):?>

			<div class="ajax-back-page"></div>

		<?endif;?>

    	<div class="row">
    		<div class="col part part-left align-self-center">

    			<div class="head">

	    			<div class="title main1"><h1>
	    				<?$APPLICATION->ShowTitle(false);?>
	    				</h1>
	    			</div>

                </div>

    		</div>

    		<?if($showbasketProducts):?>
                
	            <div class="col-auto part part-right d-none d-sm-block">

	            	<?
				        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']["VALUE"])>0 && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']["VALUE"] != "N")
				            $par_condition = "class='basket-page-header-btn call-modal callagreement d-none' data-call-modal='agreement".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']["VALUE"]."'";

				        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_LINK_CONDITIONS']["VALUE"])>0)
				            $par_condition = "class='basket-page-header-btn d-none' target='_blank' href='".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_LINK_CONDITIONS']["VALUE"]."' ";
				    ?>

				    <?if(isset($par_condition)):?>
						<a <?=$par_condition?>>
							<span><?=(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_NAME_CONDITIONS']["VALUE"])>0)? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_NAME_CONDITIONS']["VALUE"] : $PHOENIX_TEMPLATE_ARRAY["MESS"]["CART_DELIVERY"];?></span>
						</a>
					<?endif;?>
				

					<div class="basket-page-header-btn-unset click_cart clear-cart d-none clear-basket-node-control" data-toggle="tooltip" data-placement="top" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CART_CLEAR"]?>"></div>
	               
	            </div>

            <?endif;?>

            <?if(!$orderConfirm):?>

	            <div class="col-12 wr-order-btn d-md-none d-none clear-basket-node-control">

	            	<?if(!$showBuyBtnOnly):?>

		            	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_PAGES"]["VALUE"]=="one"):?>
		            		<a class="main-color button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> big scroll" href="#bx-soa-order">
		            			<?echo $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_STEP_TO_ORDER"];?>
		            		</a>
		            	<?else:?>
		            		<a class="main-color button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> big" href="<?=CPhoenix::getBasketUrl(SITE_DIR, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"])?>order/">
		            			<?echo $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_STEP_TO_ORDER"];?>
		            		</a>
		            	<?endif;?>

		            <?endif;?>

	            

	            	<?if($showBuyBtn):?>


		                <a class="sec-b callFastOrder callDialog">

		                    <span class="bord-bot">
		                
			                    <?= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_BASKET"]["~VALUE"];?>

		                    </span>
		                </a>

			        <?endif;?>

	            </div>
            <?endif?>
    	</div>
    </div>


</div>


<div class="container">

	<?
		$showbasketProducts = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"]=="Y") ? true: false;

        $colsLeft = "col-lg-8 col-12";
        $colsRight = "col-lg-4 col-12";

        if(!$showbasketProducts)
        {
            $colsLeft = "col-12";
            $colsRight = "d-none";
        }

	?>

	<?if($orderConfirm):?>

		<?
            $basket_url = CPhoenix::getBasketUrl(SITE_DIR, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]);
        ?>

        <div class="block-move-to-up order-page"></div>
    	<div class="basket-page-container">

            <?
                $APPLICATION->IncludeComponent("bitrix:sale.order.ajax", 
                    "basket_order", 
                    Array(
                        "PAY_FROM_ACCOUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PAY_FROM_ACCOUNT"]["VALUE"]["ACTIVE"],
                        "ONLY_FULL_PAY_FROM_ACCOUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ONLY_FULL_PAY_FROM_ACCOUNT"]["VALUE"]["ACTIVE"],
                        "TEMPLATE_LOCATION" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["TEMPLATE_LOCATION"]["VALUE"],
                        "DELIVERY_TO_PAYSYSTEM" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["DELIVERY_TO_PAYSYSTEM"]["VALUE"],
                        "BASKET_POSITION" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_POSITION"]["VALUE"],
                        "SHOW_COUPONS" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["COUPON"]["VALUE"]["ACTIVE"],
                        "SHOW_COUPONS_BASKET" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["COUPON"]["VALUE"]["ACTIVE"],
                        "SHOW_COUPONS_DELIVERY" => "Y",
                        "SHOW_COUPONS_PAY_SYSTEM" => "Y",
                        "ACTION_VARIABLE" => "soa-action",
                        "ADDITIONAL_PICT_PROP_15" => "-",
                        "ADDITIONAL_PICT_PROP_8" => "-",
                        "ALLOW_APPEND_ORDER" => "Y",
                        "ALLOW_AUTO_REGISTER" => "Y",
                        "ALLOW_NEW_PROFILE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ALLOW_NEW_PROFILE"]["VALUE"]["ACTIVE"],
						"ALLOW_USER_PROFILES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ALLOW_USER_PROFILES"]["VALUE"]["ACTIVE"],
                        "BASKET_IMAGES_SCALING" => "adaptive",
                        "COMPATIBLE_MODE" => "Y",
                        "COMPOSITE_FRAME_MODE" => "N",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "DELIVERIES_PER_PAGE" => "9",
                        "DELIVERY_FADE_EXTRA_SERVICES" => "N",
                        "DELIVERY_NO_AJAX" => "N",
                        "DELIVERY_NO_SESSION" => "Y",
                        "DISABLE_BASKET_REDIRECT" => "Y",
                        "EMPTY_BASKET_HINT_PATH" => SITE_DIR."catalog/",
                        "HIDE_ORDER_DESCRIPTION" => "N",
                        "PATH_TO_AUTH" => SITE_DIR."auth/",
                        "PATH_TO_BASKET" => $basket_url,
                        "PATH_TO_PAYMENT" => $basket_url."payment/",
                        "PATH_TO_PERSONAL" => SITE_DIR."personal/orders/",
                        "PAY_SYSTEMS_PER_PAGE" => "9",
                        "PICKUPS_PER_PAGE" => "5",
                        "PICKUP_MAP_TYPE" => "yandex",
                        "PRODUCT_COLUMNS_HIDDEN" => "",
                        "PRODUCT_COLUMNS_VISIBLE" => array(
                            0 => "PREVIEW_PICTURE",
                            1 => "PROPS",
                        ),
                        "SEND_NEW_USER_NOTIFY" => "N",
                        "SERVICES_IMAGES_SCALING" => "adaptive",
                        "SET_TITLE" => "Y",
                        "SHOW_BASKET_HEADERS" => "N",
                        "SHOW_DELIVERY_INFO_NAME" => "Y",
                        "SHOW_DELIVERY_LIST_NAMES" => "Y",
                        "SHOW_DELIVERY_PARENT_NAMES" => ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["SHOW_DELIVERY_PARENT_NAMES"]["VALUE"]["ACTIVE"] === "Y")?"Y":"N",
                        "SHOW_MAP_IN_PROPS" => "N",
                        "SHOW_NEAREST_PICKUP" => "N",
                        "SHOW_NOT_CALCULATED_DELIVERIES" => "L",
                        "SHOW_ORDER_BUTTON" => "always",
                        "SHOW_PAY_SYSTEM_INFO_NAME" => "Y",
                        "SHOW_PAY_SYSTEM_LIST_NAMES" => "Y",
                        "SHOW_PICKUP_MAP" => "Y",
                        "SHOW_STORES_IMAGES" => "Y",
                        "SHOW_TOTAL_ORDER_BUTTON" => "Y",
                        "SHOW_VAT_PRICE" => "Y",
                        "SKIP_USELESS_BLOCK" => "Y",
                        "SPOT_LOCATION_BY_GEOIP" => "Y",
                        "TEMPLATE_THEME" => "blue",
                        "USER_CONSENT" => "N",
                        "USER_CONSENT_ID" => "0",
                        "USER_CONSENT_IS_CHECKED" => "Y",
                        "USER_CONSENT_IS_LOADED" => "N",
                        "USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
                        "USE_CUSTOM_ERROR_MESSAGES" => "N",
                        "USE_CUSTOM_MAIN_MESSAGES" => "N",
                        "USE_ENHANCED_ECOMMERCE" => "N",
                        "USE_PHONE_NORMALIZATION" => "Y",
                        "USE_PRELOAD" => "Y",
                        "USE_PREPAYMENT" => "N",
                        "USE_YM_GOALS" => "N",
                        "COMPONENT_TEMPLATE" => "bootstrap_v4",
                        "PROPS_FADE_LIST_1" => "",
                        "PROPS_FADE_LIST_2" => "",
                    ),
                    $component
                );
            ?>

        </div>


        <?
           $goalsScript = CPhoenix::getGoalsScriptsHTML(SITE_ID,

           	array(
           		"YAGOAL"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA_GOAL_ORDER"]['VALUE'],
           		"GA_CAT"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_CATEGORY_ORDER"]['VALUE'],
           		"GA_ACT"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_ACTION_ORDER"]['VALUE'],
           		"GTM_EVT"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_EVENT_ORDER"]['VALUE'],
           		"GTM_CAT"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_CATEGORY_ORDER"]['VALUE'],
           		"GTM_ACT"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_ACTION_ORDER"]['VALUE'],
           	)
           );

           //CPhoenix::phoenixOptionsValues(SITE_ID, array("services"));

           if(strlen($PHOENIX_TEMPLATE_ARRAY["LAZY_SCRIPTS"])>0):?>

           	<script>

           		$(window).on("load", function()
           		{
           			var timerService = setTimeout(function()
           			{
           				
           				$("body").append('<?=str_replace(array("/","'", "\r\n"), array("\/", '"', ""), $goalsScript)?>');
           				clearTimeout(timerService);

           			},  (<?=intval($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SERVICE_TIME"]["VALUE"])?>+1) * 1000);

           		});
           	</script>
           <?
           else:

           	echo $goalsScript;

           endif;
		?>


	<?else:?>

		<input type="hidden" id = "basketPage" value = "basket_page">

		<?if($showAuthForm):?>
			<div class="cabinet-wrap">
				<div class="block-move-to-up">

					<div class="auth-block">
						<div class="row">

							<div class="col-lg-4 col-md-6 col-12">
		                        <form class="form auth" action="#">
		                            <div class="row inputs-block">
		                                <div class="col-12 title-form main1">
		                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_LOGIN_TITLE"]?>
		                                </div>
		                                <div class="col-12">
		                                    <div class="input">
		                                        <div class="bg"></div>
		                                        <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_LOGIN_INPUT"]?></span>
		                                        <input class='focus-anim require' name="auth-login" type="text" value="" />
		                                    </div>
		                                    <div class="input">
		                                        <div class="bg"></div>
		                                        <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_PASSWORD_INPUT"]?></span>
		                                        <input class='focus-anim require' name="auth-password" type="password" />
		                                    </div>
		                                    <div class="errors"></div>
		                                </div>

		                                <div class="col-12">
		                                    <div class="input-btn">
		                                        <div class="load">
		                                            <div class="xLoader form-preload"><div class="audio-wave"><span></span><span></span><span></span><span></span><span></span></div></div>
		                                        </div>
		                                        <button class="button-def main-color big active <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?> auth-submit" name="form-submit" type="button"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_BTN_ENTER"]?></button>
		                                    </div>
		                                </div>
		                            </div>

		                            <div class="row links-block">
		                                <div class="col-sm-6 col-12">
		                                    <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["FORGOT_PASSWORD_URL"]["VALUE"]?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["FORGOT_PASSWORD_URL"]["DESCRIPTION"]?></a>
		                                </div>
		                                
		                                <div class="col-sm-6 col-12">
		                                    <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["REGISTER_URL"]["VALUE"]?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["REGISTER_URL"]["DESCRIPTION"]?></a>
		                                </div>
		                            </div>
		                        </form>
		                    </div>

		                    <div class="col-lg-8 col-md-6 hidden-xs">
		                                    
		                        <div class="reg">
		                            <div class="reg-comment">
		                                <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SALE_ORDER_ALERT"]?>
		                            </div>
		                            
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
	        </div>

		<?endif;?>

		<div class="basket-style page">

			<div class="row body static">



				<div class="left-p <?=$colsLeft?>">


					<div class="body-basket-ajax-left">

						<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket",
		                    "basket.items",
		                    Array(
		                    "ACTION_VARIABLE" => "basketAction",    
		                        "ADDITIONAL_PICT_PROP_15" => "-",   
		                        "ADDITIONAL_PICT_PROP_8" => "-",    
		                        "AUTO_CALCULATION" => "Y",  
		                        "BASKET_IMAGES_SCALING" => "adaptive",  
		                        "COLUMNS_LIST_EXT" => array(    
		                            0 => "PREVIEW_PICTURE",
		                            1 => "DISCOUNT",
		                            2 => "DELETE",
		                            3 => "DELAY",
		                            4 => "TYPE",
		                            5 => "SUM",
		                        ),
		                        "COLUMNS_LIST_MOBILE" => array(
		                            0 => "PREVIEW_PICTURE",
		                            1 => "DISCOUNT",
		                            2 => "DELETE",
		                            3 => "DELAY",
		                            4 => "TYPE",
		                            5 => "SUM",
		                        ),
		                        "COMPATIBLE_MODE" => "Y",
		                        "CORRECT_RATIO" => "Y",
		                        "DEFERRED_REFRESH" => "N",
		                        "DISCOUNT_PERCENT_POSITION" => "bottom-right",
		                        "DISPLAY_MODE" => "compact",
		                        "EMPTY_BASKET_HINT_PATH" => "/",
		                        "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
		                        "GIFTS_CONVERT_CURRENCY" => "N",
		                        "GIFTS_HIDE_BLOCK_TITLE" => "N",
		                        "GIFTS_HIDE_NOT_AVAILABLE" => "N",
		                        "GIFTS_MESS_BTN_BUY" => "Выбрать",
		                        "GIFTS_MESS_BTN_DETAIL" => "Подробнее",
		                        "GIFTS_PAGE_ELEMENT_COUNT" => "4",
		                        "GIFTS_PLACE" => "BOTTOM",
		                        "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
		                        "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
		                        "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		                        "GIFTS_SHOW_OLD_PRICE" => "N",
		                        "GIFTS_TEXT_LABEL_GIFT" => "Подарок",
		                        "HIDE_COUPON" => "N",
		                        "LABEL_PROP" => "",
		                        "OFFERS_PROPS" => "",
		                        "PATH_TO_ORDER" => SITE_DIR."order/",
		                        "PRICE_DISPLAY_MODE" => "Y",
		                        "PRICE_VAT_SHOW_VALUE" => "Y",
		                        "PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
		                        "QUANTITY_FLOAT" => "N",
		                        "SET_TITLE" => "N",
		                        "SHOW_DISCOUNT_PERCENT" => "Y",
		                        "SHOW_FILTER" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_FILTER"]["VALUE"]["ACTIVE"],
		                        "SHOW_RESTORE" => "Y",
		                        "TEMPLATE_THEME" => "blue",
		                        "TOTAL_BLOCK_DISPLAY" => array(
		                            0 => "top",
		                        ),
		                        "USE_DYNAMIC_SCROLL" => "Y",
		                        "USE_ENHANCED_ECOMMERCE" => "N",
		                        "USE_GIFTS" => "Y",
		                        "USE_PREPAYMENT" => "N",
		                        "USE_PRICE_ANIMATION" => "Y",
		                    ),
		                    $component
		                );?>

					</div>


					<?
						if( !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ADVS']['VALUE']) )
						{
							CPhoenix::getIblockIDs(array("concept_phoenix_advantages_".SITE_ID));
							$arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ADVS']["IBLOCK_ID"], "ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ADVS']['VALUE'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
							$res = CIBlockElement::GetList(Array(), $arFilter, false);

							$arSizes = Array(
						        "small" => array(
						                "width" => 80,
						                "height" => 80
						            ),
						        "big" => array(
						                "width" => 200,
						                "height" => 200
						            ),
						    );


							while($ob = $res->GetNextElement())
							{ 
								$arFields = array();
							    $arFields = $ob->GetFields();
							    $arFields["PROPERTIES"] = $ob->GetProperties();

							    if(!strlen($arFields["PROPERTIES"]["SIZE"]["VALUE_XML_ID"]))
							    	$arFields["PROPERTIES"]["SIZE"]["VALUE_XML_ID"] = "small";

							   	$arFields["PREVIEW_PICTURE_SRC"] = "";
						        $file = array();

						        if($arFields["PREVIEW_PICTURE"])
						        {
						            $file = CFile::ResizeImageGet($arFields["PREVIEW_PICTURE"], 
						                $arSizes[$arFields["PROPERTIES"]["SIZE"]["VALUE_XML_ID"]],
						                BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

						            $arFields["PREVIEW_PICTURE_SRC"] = $file["src"];
						        }
							    $arAdvantages["ITEMS"][] = $arFields;
							}

							$arAdvantages["COUNT"] = (!empty($arAdvantages["ITEMS"]))?count($arAdvantages["ITEMS"]):0;

							$arAdvantages["CLASS_COLS"] = "col-md-4 col-12";


							if($arAdvantages["COUNT"]%3 == 0)
						        $arAdvantages["CLASS_COLS"] = "col-md-4 col-12";
						    
						    
						    if($arAdvantages["COUNT"] == 2)
						        $arAdvantages["CLASS_COLS"] = "col-md-6 col-12";
						    
						    
						    if($arAdvantages["COUNT"] == 1)
						        $arAdvantages["CLASS_COLS"] = "col-12";
						}
					?>

					<?if( !empty($arAdvantages["ITEMS"]) && $showbasketProducts):?>

						<div class="cart-advantage hidden d-none <?=(intval($PHOENIX_TEMPLATE_ARRAY["ORDER"]["BASKET_PRODUCTS_COUNT"]) > 0 || intval($PHOENIX_TEMPLATE_ARRAY["ORDER"]["BASKET_DELAY_COUNT"]) > 0)?"d-sm-block":""?>">

						    <div class="row">

						        <?foreach($arAdvantages["ITEMS"] as $key=>$arItems):?>
					
									<div class="<?=$arAdvantages["CLASS_COLS"]?>">
				                        <table class='size-<?=($arItems["PROPERTIES"]["SIZE"]["VALUE_XML_ID"])?>'>
				                            <tr>
				                            
				                                <td class="img">

				                                
				                                    <?if(strlen($arItems["PREVIEW_PICTURE_SRC"])):?>
				                                 
				                            
				                                    	<img src="<?=$arItems["PREVIEW_PICTURE_SRC"]?>" alt="<?=$arItems["NAME"]?>" class="d-block mx-auto img-fluid" />

				                                    <?elseif(strlen($arItems["PROPERTIES"]["ICON"]["VALUE"]) && $arItems["PREVIEW_PICTURE"] <= 0):?>
				             
				                                        <div class="icon">
				                                            <i class="<?=$arItems["PROPERTIES"]["ICON"]["VALUE"]?>" <?if(strlen($arItems["PROPERTIES"]["ICON"]["DESCRIPTION"]) > 0):?>style="color: <?=$arItems["PROPERTIES"]["ICON"]["DESCRIPTION"]?>;"<?endif;?>></i>
				                                        </div>
				                                        
				                                    <?else:?>
				                                        
				                                        
				                                        <div class="icon default"></div>
				                                        
				                                    <?endif;?>
				                                    
				                                </td>
				                                
				                                <td class='text'><?=$arItems["PROPERTIES"]["SIGN"]["~VALUE"]?></td>
				                                
				                            </tr>
				                        </table>
				                    </div>

						        <?endforeach;?>

						    </div> 

						</div>

					<?endif;?>



					<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_PAGES"]["VALUE"]=="one" && !$showBuyBtnOnly):?>

		                <?$basket_url = CPhoenix::getBasketUrl(SITE_DIR, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]);?>

		                <div class="basketOrder-body <?=(intval($PHOENIX_TEMPLATE_ARRAY["ORDER"]["BASKET_PRODUCTS_COUNT"]) > 0)?"":"d-none"?>">
		                    <?
		                        $APPLICATION->IncludeComponent("bitrix:sale.order.ajax", 
		                            "basket_order",
		                            Array(
		                                "PAY_FROM_ACCOUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PAY_FROM_ACCOUNT"]["VALUE"]["ACTIVE"],
		                                "ONLY_FULL_PAY_FROM_ACCOUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ONLY_FULL_PAY_FROM_ACCOUNT"]["VALUE"]["ACTIVE"],
		                                "TEMPLATE_LOCATION" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["TEMPLATE_LOCATION"]["VALUE"],
		                                "DELIVERY_TO_PAYSYSTEM" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["DELIVERY_TO_PAYSYSTEM"]["VALUE"],
		                                "BASKET_POSITION" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_POSITION"]["VALUE"],
		                                "SHOW_COUPONS" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["COUPON"]["VALUE"]["ACTIVE"],
		                                "SHOW_COUPONS_BASKET" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["COUPON"]["VALUE"]["ACTIVE"],
		                                "SHOW_COUPONS_DELIVERY" => "Y",
		                                "SHOW_COUPONS_PAY_SYSTEM" => "Y",
		                                "ACTION_VARIABLE" => "soa-action",
		                                "ADDITIONAL_PICT_PROP_15" => "-",
		                                "ADDITIONAL_PICT_PROP_8" => "-",
		                                "ALLOW_APPEND_ORDER" => "Y",
		                                "ALLOW_AUTO_REGISTER" => "Y",
		                                "ALLOW_NEW_PROFILE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ALLOW_NEW_PROFILE"]["VALUE"]["ACTIVE"],
										"ALLOW_USER_PROFILES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ALLOW_USER_PROFILES"]["VALUE"]["ACTIVE"],
		                                "BASKET_IMAGES_SCALING" => "adaptive",
		                                "COMPATIBLE_MODE" => "Y",
		                                "COMPOSITE_FRAME_MODE" => "N",
		                                "COMPOSITE_FRAME_TYPE" => "AUTO",
		                                "DELIVERIES_PER_PAGE" => "9",
		                                "DELIVERY_FADE_EXTRA_SERVICES" => "N",
		                                "DELIVERY_NO_AJAX" => "N",
		                                "DELIVERY_NO_SESSION" => "Y",
		                                "DISABLE_BASKET_REDIRECT" => "Y",
		                                "EMPTY_BASKET_HINT_PATH" => SITE_DIR."catalog/",
		                                "HIDE_ORDER_DESCRIPTION" => "N",
		                                "PATH_TO_AUTH" => SITE_DIR."auth/",
		                                "PATH_TO_BASKET" => $basket_url,
		                                "PATH_TO_PAYMENT" => $basket_url."payment/",
		                                "PATH_TO_PERSONAL" => SITE_DIR."personal/orders/",
		                                "PAY_SYSTEMS_PER_PAGE" => "9",
		                                "PICKUPS_PER_PAGE" => "5",
		                                "PICKUP_MAP_TYPE" => "yandex",
		                                "PRODUCT_COLUMNS_HIDDEN" => "",
		                                "PRODUCT_COLUMNS_VISIBLE" => array(
		                                    0 => "PREVIEW_PICTURE",
		                                    1 => "PROPS",
		                                ),
		                                "SEND_NEW_USER_NOTIFY" => "N",
		                                "SERVICES_IMAGES_SCALING" => "adaptive",
		                                "SET_TITLE" => "Y",
		                                "SHOW_BASKET_HEADERS" => "N",
		                                "SHOW_DELIVERY_INFO_NAME" => "Y",
		                                "SHOW_DELIVERY_LIST_NAMES" => "Y",
		                                "SHOW_DELIVERY_PARENT_NAMES" => ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["SHOW_DELIVERY_PARENT_NAMES"]["VALUE"]["ACTIVE"] === "Y")?"Y":"N",
		                                "SHOW_MAP_IN_PROPS" => "N",
		                                "SHOW_NEAREST_PICKUP" => "N",
		                                "SHOW_NOT_CALCULATED_DELIVERIES" => "L",
		                                "SHOW_ORDER_BUTTON" => "always",
		                                "SHOW_PAY_SYSTEM_INFO_NAME" => "Y",
		                                "SHOW_PAY_SYSTEM_LIST_NAMES" => "Y",
		                                "SHOW_PICKUP_MAP" => "Y",
		                                "SHOW_STORES_IMAGES" => "Y",
		                                "SHOW_TOTAL_ORDER_BUTTON" => "Y",
		                                "SHOW_VAT_PRICE" => "Y",
		                                "SKIP_USELESS_BLOCK" => "Y",
		                                "SPOT_LOCATION_BY_GEOIP" => "Y",
		                                "TEMPLATE_THEME" => "blue",
		                                "USER_CONSENT" => "N",
		                                "USER_CONSENT_ID" => "0",
		                                "USER_CONSENT_IS_CHECKED" => "Y",
		                                "USER_CONSENT_IS_LOADED" => "N",
		                                "USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
		                                "USE_CUSTOM_ERROR_MESSAGES" => "N",
		                                "USE_CUSTOM_MAIN_MESSAGES" => "N",
		                                "USE_ENHANCED_ECOMMERCE" => "N",
		                                "USE_PHONE_NORMALIZATION" => "Y",
		                                "USE_PRELOAD" => "Y",
		                                "USE_PREPAYMENT" => "N",
		                                "USE_YM_GOALS" => "N",
		                                "COMPONENT_TEMPLATE" => "bootstrap_v4",
		                                "PROPS_FADE_LIST_1" => "",
		                                "PROPS_FADE_LIST_2" => "",
		                            ),
		                            $component
		                        );
		                    ?>

		                </div>

		                <input type="hidden" class="basketOrder">

		            <?endif;?>

		            


		            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_PAGE_TITLE"]["VALUE"]) > 0):?>
    					<?$APPLICATION->setTitle($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_PAGE_TITLE"]["~VALUE"]);?>
    				<?endif;?>

				</div>

				<div class="right-p <?=$colsRight?> parent-fixedSrollBlock">

					<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_PAGES"]["VALUE"]=="one" && !$showBuyBtnOnly):?>

		                <div class="basketOrder-side <?=(intval($PHOENIX_TEMPLATE_ARRAY["ORDER"]["BASKET_PRODUCTS_COUNT"]) <= 0 && intval($PHOENIX_TEMPLATE_ARRAY["ORDER"]["BASKET_DELAY_COUNT"]) <= 0)?"d-none":""?>">

		                    <?$APPLICATION->ShowViewContent('order-side');?>

		                </div>

		           

	                    <div class="body-basket-ajax-right d-none">
	                        <?$APPLICATION->ShowViewContent('basket-side');?>
	                    </div>

		              

		            <?else:?>

		                <div class="body-basket-ajax-right">
		                    <?$APPLICATION->ShowViewContent('basket-side');?>
		                </div>

		            <?endif;?>


		            <?if( !empty($arAdvantages["ITEMS"]) ):?>

						<div class="cart-advantage <?=(intval($PHOENIX_TEMPLATE_ARRAY["ORDER"]["BASKET_PRODUCTS_COUNT"]) > 0 || intval($PHOENIX_TEMPLATE_ARRAY["ORDER"]["BASKET_DELAY_COUNT"]) > 0)?"d-md-none":"hidden"?>">

						    <div class="row">

						        <?foreach($arAdvantages["ITEMS"] as $key=>$arItems):?>
					
									<div class="<?=$arAdvantages["CLASS_COLS"]?>">
				                        <table class='size-<?=($arItems["PROPERTIES"]["SIZE"]["VALUE_XML_ID"])?>'>
				                            <tr>
				                            
				                                <td class="img">

				                                
				                                    <?if(strlen($arItems["PREVIEW_PICTURE_SRC"])):?>
			                                     
			                                
			                                        	<img src="<?=$arItems["PREVIEW_PICTURE_SRC"]?>" alt="<?=$arItems["NAME"]?>" class="d-block mx-auto img-fluid" />

				                                    <?elseif(strlen($arItems["PROPERTIES"]["ICON"]["VALUE"]) && $arItems["PREVIEW_PICTURE"] <= 0):?>
				             
				                                        <div class="icon">
				                                            <i class="<?=$arItems["PROPERTIES"]["ICON"]["VALUE"]?>" <?if(strlen($arItems["PROPERTIES"]["ICON"]["DESCRIPTION"]) > 0):?>style="color: <?=$arItems["PROPERTIES"]["ICON"]["DESCRIPTION"]?>;"<?endif;?>></i>
				                                        </div>
				                                        
				                                    <?else:?>
				                                        
				                                        
				                                        <div class="icon default"></div>
				                                        
				                                    <?endif;?>
				                                    
				                                </td>
				                                
				                                <td class='text'><?=$arItems["PROPERTIES"]["SIGN"]["~VALUE"]?></td>
				                                
				                            </tr>
				                        </table>
				                    </div>

						        <?endforeach;?>

						    </div> 

						</div>

					<?endif;?>


				    <noindex>

				        <div class="buttons buttons-2 cart-buttons-height visible-sm visible-xs">
				            <table class="mobile-break">
				                <tbody>
				                    <tr>
				                        

				                        <?
				                            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']["VALUE"])>0 && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']["VALUE"] != "N")
				                                $par_condition = "class='open-info call-modal callagreement' data-call-modal='agreement".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']["VALUE"]."'";

				                            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_LINK_CONDITIONS']["VALUE"])>0)
				                                $par_condition = "class='open-info' target='_blank' href='".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_LINK_CONDITIONS']["VALUE"]."' ";                                                            
				                        ?>

				                        <?if(isset($par_condition)):?>
				                            <td class="right">
				                                <a <?=$par_condition?>><span class="bord-bot"><?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_NAME_CONDITIONS']["VALUE"])>0) echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_NAME_CONDITIONS']["VALUE"]; else echo $PHOENIX_TEMPLATE_ARRAY["MESS"]["CART_DELIVERY"];?></span></a>
				                            </td>
				                        <?endif;?>

					                    <td class="right d-none clear-basket-node-control">
					                        <div class="clear">
									            <a class="click_cart clear-cart"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CART_CLEAR"]?></a>
									        </div>
					                    </td>
				                    </tr>
				                </tbody>
				            </table>
				        </div>
				    </noindex>


				</div>

			</div>

		</div>


	<?endif;?>

	
</div>
<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["BASKET"] === 'Y'):?>
    <div class="container">
        <div class="ex-row catalog-stories-ajax" data-count="4">
        </div>
    </div>
<?endif;?>