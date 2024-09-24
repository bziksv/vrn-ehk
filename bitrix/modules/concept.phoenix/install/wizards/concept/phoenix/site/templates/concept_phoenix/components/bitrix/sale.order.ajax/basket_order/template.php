<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CUser $USER
 * @var SaleOrderAjax $component
 * @var string $templateFolder
 */

global $PHOENIX_TEMPLATE_ARRAY;
$context = Main\Application::getInstance()->getContext();
$request = $context->getRequest();


$showBuyBtn = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_BASKET_ON"]["VALUE"]["ACTIVE"] == "Y") ? true : false;

if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"])&& !in_array('Y', $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"]))
	$showBuyBtn = false;
	
$showBuyBtnOnly = false;

if($showBuyBtn)
	$showBuyBtnOnly = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_BASKET_ONLY"]["VALUE"]["ACTIVE"] == "Y") ? true : false;


if (empty($arParams['TEMPLATE_THEME']))
{
	$arParams['TEMPLATE_THEME'] = Main\ModuleManager::isModuleInstalled('bitrix.eshop') ? 'site' : 'blue';
}

if ($arParams['TEMPLATE_THEME'] === 'site')
{
	$templateId = Main\Config\Option::get('main', 'wizard_template_id', 'eshop_bootstrap', $component->getSiteId());
	$templateId = preg_match('/^eshop_adapt/', $templateId) ? 'eshop_adapt' : $templateId;
	$arParams['TEMPLATE_THEME'] = Main\Config\Option::get('main', 'wizard_'.$templateId.'_theme_id', 'blue', $component->getSiteId());
}

if (!empty($arParams['TEMPLATE_THEME']))
{
	if (!is_file(Main\Application::getDocumentRoot().'/bitrix/css/main/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
	{
		$arParams['TEMPLATE_THEME'] = 'blue';
	}
}

$arParams['ALLOW_USER_PROFILES'] = $arParams['ALLOW_USER_PROFILES'] === 'Y' ? 'Y' : 'N';
$arParams['SKIP_USELESS_BLOCK'] = $arParams['SKIP_USELESS_BLOCK'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['SHOW_ORDER_BUTTON']))
{
	$arParams['SHOW_ORDER_BUTTON'] = 'final_step';
}

$arParams['HIDE_ORDER_DESCRIPTION'] = isset($arParams['HIDE_ORDER_DESCRIPTION']) && $arParams['HIDE_ORDER_DESCRIPTION'] === 'Y' ? 'Y' : 'N';
$arParams['SHOW_TOTAL_ORDER_BUTTON'] = $arParams['SHOW_TOTAL_ORDER_BUTTON'] === 'Y' ? 'Y' : 'N';
$arParams['SHOW_PAY_SYSTEM_LIST_NAMES'] = $arParams['SHOW_PAY_SYSTEM_LIST_NAMES'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_PAY_SYSTEM_INFO_NAME'] = $arParams['SHOW_PAY_SYSTEM_INFO_NAME'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_DELIVERY_LIST_NAMES'] = $arParams['SHOW_DELIVERY_LIST_NAMES'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_DELIVERY_INFO_NAME'] = $arParams['SHOW_DELIVERY_INFO_NAME'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_DELIVERY_PARENT_NAMES'] = $arParams['SHOW_DELIVERY_PARENT_NAMES'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_STORES_IMAGES'] = $arParams['SHOW_STORES_IMAGES'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['BASKET_POSITION']) || !in_array($arParams['BASKET_POSITION'], array('before', 'after')))
{
	$arParams['BASKET_POSITION'] = 'after';
}

$arParams['EMPTY_BASKET_HINT_PATH'] = isset($arParams['EMPTY_BASKET_HINT_PATH']) ? (string)$arParams['EMPTY_BASKET_HINT_PATH'] : '/';
$arParams['SHOW_BASKET_HEADERS'] = $arParams['SHOW_BASKET_HEADERS'] === 'Y' ? 'Y' : 'N';
$arParams['HIDE_DETAIL_PAGE_URL'] = isset($arParams['HIDE_DETAIL_PAGE_URL']) && $arParams['HIDE_DETAIL_PAGE_URL'] === 'Y' ? 'Y' : 'N';
$arParams['DELIVERY_FADE_EXTRA_SERVICES'] = $arParams['DELIVERY_FADE_EXTRA_SERVICES'] === 'Y' ? 'Y' : 'N';

$arParams['SHOW_COUPONS'] = isset($arParams['SHOW_COUPONS']) && $arParams['SHOW_COUPONS'] === 'N' ? 'N' : 'Y';

if ($arParams['SHOW_COUPONS'] === 'N')
{
	$arParams['SHOW_COUPONS_BASKET'] = 'N';
	$arParams['SHOW_COUPONS_DELIVERY'] = 'N';
	$arParams['SHOW_COUPONS_PAY_SYSTEM'] = 'N';
}
else
{
	$arParams['SHOW_COUPONS_BASKET'] = isset($arParams['SHOW_COUPONS_BASKET']) && $arParams['SHOW_COUPONS_BASKET'] === 'N' ? 'N' : 'Y';
	$arParams['SHOW_COUPONS_DELIVERY'] = isset($arParams['SHOW_COUPONS_DELIVERY']) && $arParams['SHOW_COUPONS_DELIVERY'] === 'N' ? 'N' : 'Y';
	$arParams['SHOW_COUPONS_PAY_SYSTEM'] = isset($arParams['SHOW_COUPONS_PAY_SYSTEM']) && $arParams['SHOW_COUPONS_PAY_SYSTEM'] === 'N' ? 'N' : 'Y';
}

$arParams['SHOW_NEAREST_PICKUP'] = $arParams['SHOW_NEAREST_PICKUP'] === 'Y' ? 'Y' : 'N';
$arParams['DELIVERIES_PER_PAGE'] = isset($arParams['DELIVERIES_PER_PAGE']) ? intval($arParams['DELIVERIES_PER_PAGE']) : 9;
$arParams['PAY_SYSTEMS_PER_PAGE'] = isset($arParams['PAY_SYSTEMS_PER_PAGE']) ? intval($arParams['PAY_SYSTEMS_PER_PAGE']) : 8;
$arParams['PICKUPS_PER_PAGE'] = isset($arParams['PICKUPS_PER_PAGE']) ? intval($arParams['PICKUPS_PER_PAGE']) : 5;
$arParams['SHOW_PICKUP_MAP'] = $arParams['SHOW_PICKUP_MAP'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_MAP_IN_PROPS'] = $arParams['SHOW_MAP_IN_PROPS'] === 'Y' ? 'Y' : 'N';
$arParams['USE_YM_GOALS'] = $arParams['USE_YM_GOALS'] === 'Y' ? 'Y' : 'N';
$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

$arParams['SHOW_ZIP_INPUT'] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["SHOW_ZIP_INPUT"]["VALUE"]["ACTIVE"];

$useDefaultMessages = !isset($arParams['USE_CUSTOM_MAIN_MESSAGES']) || $arParams['USE_CUSTOM_MAIN_MESSAGES'] != 'Y';

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_BLOCK_NAME']))
{
	$arParams['MESS_AUTH_BLOCK_NAME'] = GetMessage('AUTH_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REG_BLOCK_NAME']))
{
	$arParams['MESS_REG_BLOCK_NAME'] = GetMessage('REG_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_BASKET_BLOCK_NAME']))
{
	$arParams['MESS_BASKET_BLOCK_NAME'] = GetMessage('BASKET_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REGION_BLOCK_NAME']))
{
	$arParams['MESS_REGION_BLOCK_NAME'] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_SECTION_TITLE_REGION"];
}

if ($useDefaultMessages || !isset($arParams['MESS_PAYMENT_BLOCK_NAME']))
{
	$arParams['MESS_PAYMENT_BLOCK_NAME'] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_SECTION_TITLE_PAYSYSTEM"];
}

if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_BLOCK_NAME']))
{
	$arParams['MESS_DELIVERY_BLOCK_NAME'] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_SECTION_TITLE_DELIVERY"];
}

if ($useDefaultMessages || !isset($arParams['MESS_BUYER_BLOCK_NAME']))
{
	$arParams['MESS_BUYER_BLOCK_NAME'] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_SECTION_TITLE_PROPS"];
}

if ($useDefaultMessages || !isset($arParams['MESS_BACK']))
{
	$arParams['MESS_BACK'] = GetMessage('BACK_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_FURTHER']))
{
	$arParams['MESS_FURTHER'] = GetMessage('FURTHER_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_EDIT']))
{
	$arParams['MESS_EDIT'] = GetMessage('EDIT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ORDER']))
{
	$arParams['MESS_ORDER'] = $arParams['~MESS_ORDER'] = GetMessage('ORDER_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PRICE']))
{
	$arParams['MESS_PRICE'] = GetMessage('PRICE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PERIOD']))
{
	$arParams['MESS_PERIOD'] = GetMessage('PERIOD_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_NAV_BACK']))
{
	$arParams['MESS_NAV_BACK'] = GetMessage('NAV_BACK_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_NAV_FORWARD']))
{
	$arParams['MESS_NAV_FORWARD'] = GetMessage('NAV_FORWARD_DEFAULT');
}

$useDefaultMessages = !isset($arParams['USE_CUSTOM_ADDITIONAL_MESSAGES']) || $arParams['USE_CUSTOM_ADDITIONAL_MESSAGES'] != 'Y';

if ($useDefaultMessages || !isset($arParams['MESS_PRICE_FREE']))
{
	$arParams['MESS_PRICE_FREE'] = GetMessage('PRICE_FREE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ECONOMY']))
{
	$arParams['MESS_ECONOMY'] = GetMessage('ECONOMY_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REGISTRATION_REFERENCE']))
{
	$arParams['MESS_REGISTRATION_REFERENCE'] = GetMessage('REGISTRATION_REFERENCE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_1']))
{
	$arParams['MESS_AUTH_REFERENCE_1'] = GetMessage('AUTH_REFERENCE_1_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_2']))
{
	$arParams['MESS_AUTH_REFERENCE_2'] = GetMessage('AUTH_REFERENCE_2_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_3']))
{
	$arParams['MESS_AUTH_REFERENCE_3'] = GetMessage('AUTH_REFERENCE_3_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ADDITIONAL_PROPS']))
{
	$arParams['MESS_ADDITIONAL_PROPS'] = GetMessage('ADDITIONAL_PROPS_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_USE_COUPON']))
{
	$arParams['MESS_USE_COUPON'] = GetMessage('USE_COUPON_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_COUPON']))
{
	$arParams['MESS_COUPON'] = GetMessage('COUPON_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PERSON_TYPE']))
{
	$arParams['MESS_PERSON_TYPE'] = GetMessage('PERSON_TYPE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SELECT_PROFILE']))
{
	$arParams['MESS_SELECT_PROFILE'] = GetMessage('SELECT_PROFILE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REGION_REFERENCE']))
{
	$arParams['MESS_REGION_REFERENCE'] = GetMessage('REGION_REFERENCE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PICKUP_LIST']))
{
	$arParams['MESS_PICKUP_LIST'] = GetMessage('PICKUP_LIST_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_NEAREST_PICKUP_LIST']))
{
	$arParams['MESS_NEAREST_PICKUP_LIST'] = GetMessage('NEAREST_PICKUP_LIST_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SELECT_PICKUP']))
{
	$arParams['MESS_SELECT_PICKUP'] = GetMessage('SELECT_PICKUP_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_INNER_PS_BALANCE']))
{
	$arParams['MESS_INNER_PS_BALANCE'] = GetMessage('INNER_PS_BALANCE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ORDER_DESC']))
{
	$arParams['MESS_ORDER_DESC'] = GetMessage('ORDER_DESC_DEFAULT');
}

$useDefaultMessages = !isset($arParams['USE_CUSTOM_ERROR_MESSAGES']) || $arParams['USE_CUSTOM_ERROR_MESSAGES'] != 'Y';

if ($useDefaultMessages || !isset($arParams['MESS_PRELOAD_ORDER_TITLE']))
{
	$arParams['MESS_PRELOAD_ORDER_TITLE'] = GetMessage('PRELOAD_ORDER_TITLE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SUCCESS_PRELOAD_TEXT']))
{
	$arParams['MESS_SUCCESS_PRELOAD_TEXT'] = GetMessage('SUCCESS_PRELOAD_TEXT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_FAIL_PRELOAD_TEXT']))
{
	$arParams['MESS_FAIL_PRELOAD_TEXT'] = GetMessage('FAIL_PRELOAD_TEXT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_CALC_ERROR_TITLE']))
{
	$arParams['MESS_DELIVERY_CALC_ERROR_TITLE'] = GetMessage('DELIVERY_CALC_ERROR_TITLE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_CALC_ERROR_TEXT']))
{
	$arParams['MESS_DELIVERY_CALC_ERROR_TEXT'] = GetMessage('DELIVERY_CALC_ERROR_TEXT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR']))
{
	$arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'] = GetMessage('PAY_SYSTEM_PAYABLE_ERROR_DEFAULT');
}

$scheme = $request->isHttps() ? 'https' : 'http';

switch (LANGUAGE_ID)
{
	case 'ru':
		$locale = 'ru-RU'; break;
	case 'ua':
		$locale = 'ru-UA'; break;
	case 'tk':
		$locale = 'tr-TR'; break;
	default:
		$locale = 'en-US'; break;
}

//$this->addExternalCss('/bitrix/css/main/bootstrap.css');
//$APPLICATION->SetAdditionalCSS('/bitrix/css/main/themes/'.$arParams['TEMPLATE_THEME'].'/style.css', true);
$APPLICATION->SetAdditionalCSS($templateFolder.'/css/concept.css', true);
$this->addExternalJs($templateFolder.'/order_ajax.js');
\Bitrix\Sale\PropertyValueCollection::initJs();
$this->addExternalJs($templateFolder.'/script.js');
?>
	<NOSCRIPT>
		<div style="color:red"><?=GetMessage('SOA_NO_JS')?></div>
	</NOSCRIPT>



<?

if (strlen($request->get('ORDER_ID')) > 0)
{
	include(Main\Application::getDocumentRoot().$templateFolder.'/confirm.php');
}
/*elseif ($arParams['DISABLE_BASKET_REDIRECT'] === 'Y' && $arResult['SHOW_EMPTY_BASKET'])
{
	include(Main\Application::getDocumentRoot().$templateFolder.'/empty.php');
}*/
else
{
	
	if($arResult["ORDER_PRICE"]<$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MIN_SUM']['VALUE'] && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_PAGES"]["VALUE"]=="two")
		LocalRedirect(str_replace("/order", "", $APPLICATION->GetCurUri(false)));

	$showProducts = false;

	if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_POSITION"]["VALUE"] != "HIDE" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_PAGES"]["VALUE"]=="two")
		$showProducts = true;
	

	Main\UI\Extension::load('phone_auth');

	$hideDelivery = empty($arResult['DELIVERY']);
	?>

	<form action="<?=POST_FORM_ACTION_URI?>" method="POST" name="ORDER_FORM" id="bx-soa-order-form" enctype="multipart/form-data">


		
		<?
		echo bitrix_sessid_post();

		if (strlen($arResult['PREPAY_ADIT_FIELDS']) > 0)
		{
			echo $arResult['PREPAY_ADIT_FIELDS'];
		}
		?>
		<input type="hidden" name="<?=$arParams['ACTION_VARIABLE']?>" value="saveOrderAjax">
		<input type="hidden" name="location_type" value="code">
		<input type="hidden" name="BUYER_STORE" id="BUYER_STORE" value="<?=$arResult['BUYER_STORE']?>">
		<div id="bx-soa-order" class="row" style="opacity: 0">
			<!--	MAIN BLOCK	-->
			<div class="bx-soa <?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_PAGES"]["VALUE"]=="one")?"col-12":"col-lg-8 col-12"?>">
				<div id="bx-soa-main-notifications">
					<div class="alert alert-danger" style="display:none"></div>
					<div data-type="informer" style="display:none"></div>
				</div>
				<!--	AUTH BLOCK	-->
				<div id="bx-soa-auth" class="bx-soa-section bx-soa-auth" style="display:none">
					<div class="bx-soa-section-title-container">
						<h2 class="bx-soa-section-title col-sm-9">
							<span class="bx-soa-section-title-count"></span><?=$arParams['MESS_AUTH_BLOCK_NAME']?>
						</h2>
					</div>
					<div class="bx-soa-section-content"></div>
				</div>

				<!--	DUPLICATE MOBILE ORDER SAVE BLOCK	-->
				<div id="bx-soa-total-mobile"></div>

				<? if ($arParams['BASKET_POSITION'] === 'before'): ?>
					<!--	BASKET ITEMS BLOCK	-->
					<div id="bx-soa-basket" data-visited="false" class="bx-soa-section bx-active <?=($showProducts)?"":"d-none";?>">

						<div class="bx-soa-section-title-container row no-margin">
							<div class="col-auto wr-icon">
								<div class="icon main-color basket"></div>
							</div>
							<div class="col bx-soa-section-title main1 wr-col-right">
								<?=$arParams['MESS_BASKET_BLOCK_NAME']?>
							</div>

						</div>

						<div class="row no-margin row-xs-margin bx-soa-section-content">
							<div class="col-auto wr-ghost-icon d-none d-md-block">
								<div class="ghost-icon"></div>
							</div>
							<div class="col wr-col-right">
								<div class="bx-soa-section-content-node"></div>
							</div>
						</div>
						

					</div>
				<? endif ?>

				<!--	REGION BLOCK	-->
				<div id="bx-soa-region" data-visited="false" class="bx-soa-section bx-active">

					<div class="bx-soa-section-title-container row no-margin">
						<div class="col-auto wr-icon">
							<div class="icon main-color region"></div>
						</div>
						<div class="col bx-soa-section-title main1 wr-col-right">
							<?=$arParams['MESS_REGION_BLOCK_NAME']?>
						</div>

					</div>

					<div class="row no-margin row-xs-margin bx-soa-section-content">
						<div class="col-auto wr-ghost-icon d-none d-md-block">
							<div class="ghost-icon"></div>
						</div>
						<div class="col wr-col-right">
							<div class="bx-soa-section-content-node"></div>
						</div>
					</div>

					
				</div>





				<? if ($arParams['DELIVERY_TO_PAYSYSTEM'] === 'p2d'): ?>
					<!--	PAY SYSTEMS BLOCK	-->
					<div id="bx-soa-paysystem" data-visited="false" class="bx-soa-section bx-active">

						<div class="bx-soa-section-title-container row no-margin">
							<div class="col-auto wr-icon">
								<div class="icon main-color paysystem"></div>
							</div>
							<div class="col bx-soa-section-title main1 wr-col-right">
								<?=$arParams['MESS_PAYMENT_BLOCK_NAME']?>
							</div>

						</div>

						<div class="row no-margin row-xs-margin bx-soa-section-content">
							<div class="col-auto wr-ghost-icon d-none d-md-block">
								<div class="ghost-icon"></div>
							</div>
							<div class="col wr-col-right">
								<div class="bx-soa-section-content-node"></div>
							</div>
						</div>


					</div>
					<!--	DELIVERY BLOCK	-->
					<div id="bx-soa-delivery" data-visited="false" class="bx-soa-section bx-active" <?=($hideDelivery ? 'style="display:none"' : '')?>>

						<div class="bx-soa-section-title-container row no-margin">
							<div class="col-auto wr-icon">
								<div class="icon main-color delivery"></div>
							</div>
							<div class="col bx-soa-section-title main1 wr-col-right">
								<?=$arParams['MESS_DELIVERY_BLOCK_NAME']?>
							</div>

						</div>

						<div class="row no-margin row-xs-margin bx-soa-section-content">
							<div class="col-auto wr-ghost-icon d-none d-md-block">
								<div class="ghost-icon"></div>
							</div>
							<div class="col wr-col-right">
								<div class="bx-soa-section-content-node"></div>
							</div>
						</div>
					</div>
					<!--	PICKUP BLOCK	-->
					<div id="bx-soa-pickup" data-visited="false" class="bx-soa-section bx-active" style="display:none">

						<div class="bx-soa-section-title-container row no-margin">
							<div class="col-auto wr-icon">
								<div class="icon main-color pickup"></div>
							</div>
							<div class="col bx-soa-section-title main1 wr-col-right">
							</div>

						</div>

						<div class="row no-margin row-xs-margin bx-soa-section-content">
							<div class="col-auto wr-ghost-icon d-none d-md-block">
								<div class="ghost-icon"></div>
							</div>
							<div class="col wr-col-right">
								<div class="bx-soa-section-content-node"></div>
							</div>
						</div>
					</div>
				<? else: ?>
					<!--	DELIVERY BLOCK	-->
					<div id="bx-soa-delivery" data-visited="false" class="bx-soa-section bx-active" <?=($hideDelivery ? 'style="display:none"' : '')?>>

						<div class="bx-soa-section-title-container row no-margin">
							<div class="col-auto wr-icon">
								<div class="icon main-color delivery"></div>
							</div>
							<div class="col bx-soa-section-title main1 wr-col-right">
								<?=$arParams['MESS_DELIVERY_BLOCK_NAME']?>
							</div>

						</div>

						<div class="row no-margin row-xs-margin bx-soa-section-content">
							<div class="col-auto wr-ghost-icon d-none d-md-block">
								<div class="ghost-icon"></div>
							</div>
							<div class="col wr-col-right">
								<div class="bx-soa-section-content-node"></div>
							</div>
						</div>

					</div>
					<!--	PICKUP BLOCK	-->
					<div id="bx-soa-pickup" data-visited="false" class="bx-soa-section bx-active" style="display:none">

						<div class="bx-soa-section-title-container row no-margin">
							<div class="col-auto wr-icon">
								<div class="icon main-color pickup"></div>
							</div>
							<div class="col bx-soa-section-title main1 wr-col-right">
							</div>

						</div>

						<div class="row no-margin row-xs-margin bx-soa-section-content">
							<div class="col-auto wr-ghost-icon d-none d-md-block">
								<div class="ghost-icon"></div>
							</div>
							<div class="col wr-col-right">
								<div class="bx-soa-section-content-node"></div>
							</div>
						</div>

					</div>
					<!--	PAY SYSTEMS BLOCK	-->
					<div id="bx-soa-paysystem" data-visited="false" class="bx-soa-section bx-active">

						<div class="bx-soa-section-title-container row no-margin">
							<div class="col-auto wr-icon">
								<div class="icon main-color paysystem"></div>
							</div>
							<div class="col bx-soa-section-title main1 wr-col-right">
								<?=$arParams['MESS_PAYMENT_BLOCK_NAME']?>
							</div>

						</div>

						<div class="row no-margin row-xs-margin bx-soa-section-content">
							<div class="col-auto wr-ghost-icon d-none d-md-block">
								<div class="ghost-icon"></div>
							</div>
							<div class="col wr-col-right">
								<div class="bx-soa-section-content-node"></div>
							</div>
						</div>
					</div>
				<? endif ?>
				<!--	BUYER PROPS BLOCK	-->
				<div id="bx-soa-properties" data-visited="false" class="bx-soa-section <?=($arParams['BASKET_POSITION'] === 'before' || !$showProducts)? "bx-soa-section-last":"";?> bx-active">

					<div class="bx-soa-section-title-container row no-margin">
						<div class="col-auto wr-icon">
							<div class="icon main-color properties"></div>
						</div>
						<div class="col bx-soa-section-title main1 wr-col-right">
							<?=$arParams['MESS_BUYER_BLOCK_NAME']?>
						</div>

					</div>

					<div class="row no-margin row-xs-margin bx-soa-section-content">
						<div class="col-auto wr-ghost-icon d-none d-md-block">
							<div class="ghost-icon"></div>
						</div>
						<div class="col wr-col-right">
							<div class="bx-soa-section-content-node"></div>
						</div>
					</div>

					
				</div>

				<? if ($arParams['BASKET_POSITION'] === 'after'): ?>
					<!--	BASKET ITEMS BLOCK	-->
					<div id="bx-soa-basket" data-visited="false" class="bx-soa-section bx-soa-section-last bx-active <?=($showProducts)?"":"d-none";?>">

						<div class="bx-soa-section-title-container row no-margin">
							<div class="col-auto wr-icon">
								<div class="icon main-color basket"></div>
							</div>
							<div class="col bx-soa-section-title main1 wr-col-right">
								<?=$arParams['MESS_BASKET_BLOCK_NAME']?>
							</div>

						</div>

						<div class="row no-margin row-xs-margin bx-soa-section-content">
							<div class="col-auto wr-ghost-icon d-none d-md-block">
								<div class="ghost-icon"></div>
							</div>
							<div class="col wr-col-right">
								<div class="bx-soa-section-content-node"></div>
							</div>
						</div>
						

					</div>
				<? endif ?>

				<!--	ORDER SAVE BLOCK	-->
				<div id="bx-soa-orderSave" class="d-none bx-soa-section">

					<div class="row no-margin row-xs-margin bx-soa-section-content">
						<div class="col-auto wr-ghost-icon d-none d-md-block">
							<div class="ghost-icon"></div>
						</div>

						<div class="col wr-col-right">
							<div class="checkbox">
								<?
								if ($arParams['USER_CONSENT'] === 'Y')
								{
									$APPLICATION->IncludeComponent(
										'bitrix:main.userconsent.request',
										'',
										array(
											'ID' => $arParams['USER_CONSENT_ID'],
											'IS_CHECKED' => $arParams['USER_CONSENT_IS_CHECKED'],
											'IS_LOADED' => $arParams['USER_CONSENT_IS_LOADED'],
											'AUTO_SAVE' => 'N',
											'SUBMIT_EVENT_NAME' => 'bx-soa-order-save',
											'REPLACE' => array(
												'button_caption' => isset($arParams['~MESS_ORDER']) ? $arParams['~MESS_ORDER'] : $arParams['MESS_ORDER'],
												'fields' => $arResult['USER_CONSENT_PROPERTY_DATA']
											)
										)
									);
								}

								else
								{?>

									<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["AGREEMENT_FOR_FORMS_HTML"]?>

								<?}
								?>
							</div>
							
						</div>
						
					</div>

					<div class="wr-order-btn d-none d-sm-block">

						<div class="wr-order-btn-a">
							
							<a href="javascript:void(0)" class="main-color button-def elips big" id = "btnOrderSave" data-save-button="true">
								<?
									if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ORDER_NAME"]["~VALUE"])>0)
				                		echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ORDER_NAME"]["~VALUE"];
				                	else
				                		echo $PHOENIX_TEMPLATE_ARRAY["MESS"]["CART_ORDER"];
								?>
							</a>

						</div>

					</div>
					
				</div>

				<div style="display: none;">
					<div id='bx-soa-basket-hidden' class="bx-soa-section"></div>
					<div id='bx-soa-region-hidden' class="bx-soa-section"></div>
					<div id='bx-soa-paysystem-hidden' class="bx-soa-section"></div>
					<div id='bx-soa-delivery-hidden' class="bx-soa-section"></div>
					<div id='bx-soa-pickup-hidden' class="bx-soa-section"></div>
					<div id="bx-soa-properties-hidden" class="bx-soa-section"></div>
					<div id="bx-soa-auth-hidden" class="bx-soa-section">
						<div class="bx-soa-section-content reg"></div>
					</div>
				</div>
			</div>



			<!--	SIDEBAR BLOCK	-->
			<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_PAGES"]["VALUE"]=="one"):?>
			
				<?$this->SetViewTarget('order-side');?>
			<?else:?>

				<div class="col-lg-4 col-12 parent-fixedSrollBlock">

			<?endif;?>



				<div class="wrapperWidthFixedSrollBlock">

					<div class="selector-fixedSrollBlock">
						<div class="selector-fixedSrollBlock-real-height">

							

							<div id="bx-soa-total" class="bx-soa-sidebar">

								<div class="info-table active" data-target="form-fast-order">

									<div class="group-info d-none"></div>

									<?
								        $showCoupon = false;

								        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["COUPON"]["VALUE"]["ACTIVE"] == "Y")
								        	$showCoupon = true;
								    ?>

								    <?if($showCoupon):?>

								    	<?$emptyCouponList = empty($arResult['JS_DATA']['COUPON_LIST']);?>

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
								                    		onchange="enterOrderCoupon();"
															type="text"
															class="focus-anim on-save"
															value=""

								                    >

								                    <a class="main-color in-input" type="input" onclick="enterOrderCoupon();"></a>
								                    
								                </div>

								                <div class="wrapper-coupons clearfix coupons_block">

									            </div>

								            </div>

								        </div>

								    <?endif;?>

								    <div class="wr-price d-none">

									    <div class="wr-pre-desc">
									    	<span class="pre-desc"></span>
									    </div>

									    <div class="total-price bold"></div>
								    	
								    </div>

									<div class="buttons basket-buttons d-none">

										<?if($showBuyBtnOnly):?>

											<div class="wrapper-a-btn">

									        	<a class="first-b main-color button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> shine big callFastOrder callDialog">
									                <?= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_BASKET"]["~VALUE"];?>
									            </a>

									        </div>

										<?else:?>

											<div class="wrapper-a-btn">

									        	<a class="first-b main-color button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> shine big first-click btn-order-save" id="btnOrderSaveFly">
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

									                <a class="sec-b callFastOrder callDialog">

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


									<div class="alert-message-min-sum d-none">
										<div class="text-top bold"><?= $PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_ALERT_MIN_SUM_TEXT_TOP"].CurrencyFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MIN_SUM']['VALUE'], $arResult["ORDER_DATA"]["CURRENCY"])?></div>

										<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_FORM_MINPICE_ALERT"]["VALUE"])):?>
											<div class="text-bottom"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_FORM_MINPICE_ALERT"]["~VALUE"]?></div>
										<?endif;?>
									</div>


									<div class="bottom-dots"></div>

								</div>
								
								
							</div>
						</div>
					</div>
				</div>


				
			<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_PAGES"]["VALUE"]=="one"):?>
				<?$this->EndViewTarget();?>
			<?else:?>

				</div>

			<?endif;?>
		</div>
	</form>

	<div id="bx-soa-saved-files" style="display:none"></div>
	<div id="bx-soa-soc-auth-services" style="display:none">
		<?
		$arServices = false;
		$arResult['ALLOW_SOCSERV_AUTHORIZATION'] = Main\Config\Option::get('main', 'allow_socserv_authorization', 'Y') != 'N' ? 'Y' : 'N';
		$arResult['FOR_INTRANET'] = false;

		if (Main\ModuleManager::isModuleInstalled('intranet') || Main\ModuleManager::isModuleInstalled('rest'))
			$arResult['FOR_INTRANET'] = true;

		if (Main\Loader::includeModule('socialservices') && $arResult['ALLOW_SOCSERV_AUTHORIZATION'] === 'Y')
		{
			$oAuthManager = new CSocServAuthManager();
			$arServices = $oAuthManager->GetActiveAuthServices(array(
				'BACKURL' => $this->arParams['~CURRENT_PAGE'],
				'FOR_INTRANET' => $arResult['FOR_INTRANET'],
			));

			if (!empty($arServices))
			{
				$APPLICATION->IncludeComponent(
					'bitrix:socserv.auth.form',
					'flat',
					array(
						'AUTH_SERVICES' => $arServices,
						'AUTH_URL' => $arParams['~CURRENT_PAGE'],
						'POST' => $arResult['POST'],
					),
					$component,
					array('HIDE_ICONS' => 'Y')
				);
			}
		}
		?>
	</div>


	<div style="display: none">
		<?
		// we need to have all styles for sale.location.selector.steps, but RestartBuffer() cuts off document head with styles in it
		$APPLICATION->IncludeComponent(
			'bitrix:sale.location.selector.steps',
			'.default',
			array(),
			false
		);
		$APPLICATION->IncludeComponent(
			'bitrix:sale.location.selector.search',
			'.default',
			array(),
			false
		);
		?>
	</div>
	<?
	$signer = new Main\Security\Sign\Signer;
	$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.order.ajax');
	$messages = Loc::loadLanguageFile(__FILE__);


	$messages = array_merge(array(
		"ORDER_PICKUP_BTN_DETAIL_HIDE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_PICKUP_BTN_DETAIL_HIDE"],
		"ORDER_PICKUP_BTN_DETAIL" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_PICKUP_BTN_DETAIL"],
		"ORDER_SIDE_SUM_PRODUCTS_WEIGTH" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_SIDE_SUM_PRODUCTS_WEIGTH"],
		"ORDER_SIDE_SUM_PRODUCTS" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_SIDE_SUM_PRODUCTS"],
		"ORDER_SIDE_DISCOUNT_SUM_PRODUCTS" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_SIDE_DISCOUNT_SUM_PRODUCTS"],
		"ORDER_SIDE_SUM_DELIVERY" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_SIDE_SUM_DELIVERY"],

		"DELIVERY_SHOW_MORE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["DELIVERY_SHOW_MORE"],
		"BASKET_TOTAL_WITH_DISCOUNT" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TOTAL_WITH_DISCOUNT"],
		"ORDER_TOTAL_TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["ORDER_TOTAL_TITLE"],
		"DELIVERY_LOCATION_TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["DELIVERY_LOCATION_TITLE"],
		"INNER_PAY_SYSTEM_TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["INNER_PAY_SYSTEM_TITLE"],
		"BASKET_DISCOUNT_COMMENT" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DISCOUNT_COMMENT"]), $messages);


	?>

	<script>

		var globalProductsInfo = <?=CUtil::PhpToJSObject($arResult["ITEMS_INFO"])?>;

		BX.message(<?=CUtil::PhpToJSObject($messages)?>);
		


			BX.Sale.OrderAjaxComponent.init({
				result: <?=CUtil::PhpToJSObject($arResult['JS_DATA'])?>,
				locations: <?=CUtil::PhpToJSObject($arResult['LOCATIONS'])?>,
				params: <?=CUtil::PhpToJSObject($arParams)?>,
				signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
				siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
				ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
				templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
				propertyValidation: true,
				showWarnings: true,
				pickUpMap: {
					defaultMapPosition: {
						lat: 55.76,
						lon: 37.64,
						zoom: 14
					},
					secureGeoLocation: false,
					geoLocationMaxTime: 5000,
					minToShowNearestBlock: 3,
					nearestPickUpsToShow: 3
				},
				propertyMap: {
					defaultMapPosition: {
						lat: 55.76,
						lon: 37.64,
						zoom: 14
					}
				},
				orderBlockId: 'bx-soa-order',
				authBlockId: 'bx-soa-auth',
				basketBlockId: 'bx-soa-basket',
				regionBlockId: 'bx-soa-region',
				paySystemBlockId: 'bx-soa-paysystem',
				deliveryBlockId: 'bx-soa-delivery',
				pickUpBlockId: 'bx-soa-pickup',
				propsBlockId: 'bx-soa-properties',
				totalBlockId: 'bx-soa-total',
				minSumId: 'min_sum'
			});

			<?
			// spike: for children of cities we place this prompt
			$city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();

			?>

			BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
				'source' => $component->getPath().'/get.php',
				'cityTypeId' => intval($city['ID']),
				'messages' => array(
					'otherLocation' => '--- '.GetMessage('SOA_OTHER_LOCATION'),
					'moreInfoLocation' => '--- '.GetMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
					'notFoundPrompt' => '<div class="-bx-popup-special-prompt">'.GetMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.GetMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
							'#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
							'#ANCHOR_END#' => '</a>'
						)).'</div>'
				)
			))?>);

		
	</script>
	<?
	
	if ($arParams['SHOW_PICKUP_MAP'] === 'Y' || $arParams['SHOW_MAP_IN_PROPS'] === 'Y')
	{
		if ($arParams['PICKUP_MAP_TYPE'] === 'yandex')
		{
			$this->addExternalJs($templateFolder.'/scripts/yandex_maps.js');

			$apiKey = htmlspecialcharsbx(Main\Config\Option::get('fileman', 'yandex_map_api_key', ''));
			?>
			<script src="<?=$scheme?>://api-maps.yandex.ru/2.1.50/?apikey=<?=$apiKey?>&load=package.full&lang=<?=$locale?>"></script>
			<script>
				(function bx_ymaps_waiter(){
					if (typeof ymaps !== 'undefined' && BX.Sale && BX.Sale.OrderAjaxComponent)
						ymaps.ready(BX.proxy(BX.Sale.OrderAjaxComponent.initMaps, BX.Sale.OrderAjaxComponent));
					else
						setTimeout(bx_ymaps_waiter, 100);
				})();
			</script>
			<?
		}

		if ($arParams['PICKUP_MAP_TYPE'] === 'google')
		{
			$this->addExternalJs($templateFolder.'/scripts/google_maps.js');
			$apiKey = htmlspecialcharsbx(Main\Config\Option::get('fileman', 'google_map_api_key', ''));
			?>
			<script async defer
				src="<?=$scheme?>://maps.googleapis.com/maps/api/js?key=<?=$apiKey?>&callback=bx_gmaps_waiter">
			</script>
			<script>
				function bx_gmaps_waiter()
				{
					if (BX.Sale && BX.Sale.OrderAjaxComponent)
						BX.Sale.OrderAjaxComponent.initMaps();
					else
						setTimeout(bx_gmaps_waiter, 100);
				}
			</script>
			<?
		}
	}

	if ($arParams['USE_YM_GOALS'] === 'Y')
	{
		?>
		<script>
			(function bx_counter_waiter(i){
				i = i || 0;
				if (i > 50)
					return;

				if (typeof window['yaCounter<?=$arParams['YM_GOALS_COUNTER']?>'] !== 'undefined')
					BX.Sale.OrderAjaxComponent.reachGoal('initialization');
				else
					setTimeout(function(){bx_counter_waiter(++i)}, 100);
			})();
		</script>
		<?
	}
}?>
