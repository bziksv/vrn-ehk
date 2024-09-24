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
global $PHOENIX_TEMPLATE_ARRAY, $USER;

$basket_url = CPhoenix::getBasketUrl(SITE_DIR, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]);


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

		<?
			$bg_pic = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_PAGE_HEADBG"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false);
		?>

		<?/*data-src = "<?=$bg_pic["src"]?>"*/?>

		style="background-image: url(<?=$bg_pic["src"]?>);"

	<?endif;?>
>

	<div class="shadow-tone <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>"></div>

	<div class="top-shadow"></div>

	<div class="container z-i-9">

		<div class="row">
    	
			<div class="col part part-left align-self-center">

				<div class="head">

	    			<div class="title main1"><h1><?$APPLICATION->ShowTitle(false);?></h1></div>

	                <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["DESC"]["VALUE"]) > 0):?>
	                    <div class="subtitle"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["DESC"]["~VALUE"]?></div>
	                <?endif;?>

	            </div>

			</div>

			

	        <div class="col-auto part part-right d-none d-sm-block">

	        	<?
			        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']["VALUE"])>0 && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']["VALUE"] != "N")
			            $par_condition = "class='basket-page-header-btn call-modal callagreement' data-call-modal='agreement".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']["VALUE"]."'";

			        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_LINK_CONDITIONS']["VALUE"])>0)
			            $par_condition = "class='basket-page-header-btn' target='_blank' href='".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_LINK_CONDITIONS']["VALUE"]."' ";
			    ?>

			    <?if(isset($par_condition)):?>
					<a <?=$par_condition?>>
						<span><?=(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_NAME_CONDITIONS']["VALUE"])>0)? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_NAME_CONDITIONS']["VALUE"] : $PHOENIX_TEMPLATE_ARRAY["MESS"]["CART_DELIVERY"];?></span>
					</a>
				<?endif;?>
	           
	        </div>

	        <?if(!isset($_REQUEST["ORDER_ID"])):?>

	        	<?
		        	$showBuyBtn = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_BASKET_ON"]["VALUE"]["ACTIVE"] == "Y") ? true : false;
		        	if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"])&& !in_array('Y', $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"]))
						$showBuyBtn = false;
	        	?>

            	<?if($showBuyBtn):?>

	            	<div class="col-12 wr-order-btn d-md-none">


		                <a class="sec-b callFastOrder callDialog">

		                    <span class="bord-bot">
		                
			                    <?= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_BASKET"]["~VALUE"];?>

		                    </span>
		                </a>

					</div>

		        <?endif;?>

	        <?endif;?>

        </div>
    	
    </div>


</div>


<div class="container">

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


	<div class="basket-style page basket-order-page-container">

		<div class="body static">
			

			<div class="basketOrder-body">



				<?$APPLICATION->IncludeComponent("bitrix:sale.order.ajax", 
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
						"DISABLE_BASKET_REDIRECT" => "N",
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
				);?>



			</div>

		</div>

	</div>
</div>


<?
	if(isset($_REQUEST["ORDER_ID"]))
	{
		if(Bitrix\Main\Loader::includeModule("concept.phoenix"))
        {

			global $PHOENIX_TEMPLATE_ARRAY;
			
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

        }
	}
?>
