<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */
global $PHOENIX_TEMPLATE_ARRAY;

use Bitrix\Main\Loader;

if(!Loader::includeModule("iblock"))
{
	$this->abortResultCache();
	ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
	return;
}

$arResult = array();

$arResult["ITEMS"][] = array(
	"PATH" => SITE_DIR."personal/",
	"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_PERSONAL"]
);


if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["ORDERS"] === 'Y')
{
	$arResult["ITEMS"][] = array(
		"PATH" => SITE_DIR."personal/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["ORDERS"]["URL"],
		"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_ORDER_PAGE_NAME"]
	);
	$arResult["ITEMS"][] = array(
		"PATH" => SITE_DIR."personal/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["ORDERS"]["URL"]."?filter_history=Y",
		"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_ORDER_PAGE_HISTORY"],
	);

}

if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["ACCOUNT"] === 'Y' && CBXFeatures::IsFeatureEnabled('SaleAccounts'))
{
	$arResult["ITEMS"][] = array(
		"PATH" => SITE_DIR."personal/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["ACCOUNT"]["URL"],
		"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_ACCOUNT_PAGE_NAME"]
	);
}

if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["PRIVATE"] === 'Y')
{
	$arResult["ITEMS"][] = array(
		"PATH" => SITE_DIR."personal/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["PRIVATE"]["URL"],
		"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_PRIVATE_PAGE_NAME"]
	);
}

if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["PROFILE"] === 'Y')
{
	$arResult["ITEMS"][] = array(
		"PATH" => SITE_DIR."personal/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["PROFILE"]["URL"],
		"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_PROFILE_PAGE_NAME"]
	);
}

if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["BASKET"] === 'Y')
{
	$arResult["ITEMS"][] = array(
		"PATH" => $PHOENIX_TEMPLATE_ARRAY["BASKET_URL"],
		"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_BASKET_PAGE_NAME"],
	);
}

if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["SUBSCRIBE"] === 'Y')
{
	$arResult["ITEMS"][] = array(
		"PATH" => SITE_DIR."personal/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["SUBSCRIBE"]["URL"],
		"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_SUBSCRIBE_PAGE_NAME"]
	);
}

if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CUSTOM_PAGES"]["ITEMS"]))
{
	foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CUSTOM_PAGES"]["ITEMS"] as $value)
    {
		$arResult["ITEMS"][] = array(
			"PATH" => SITE_DIR."personal/".$value['URL'],
			"NAME" => $value['NAME']
		);
	}
}


$arResult["ITEMS"][] = array(
	"PATH" => SITE_DIR."?logout=yes",
	"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_PERSONAL_LOGOUT"]
);

$cur_page = $_SERVER["REQUEST_URI"];
$cur_page_no_index = $APPLICATION->GetCurPage(false);

if(!empty($arResult["ITEMS"]))
{

	foreach ($arResult["ITEMS"] as $key => $arItem)
	{
		$selected = CMenu::IsItemSelected($arItem["PATH"], $cur_page, $cur_page_no_index);

		if($selected)
			$arResult["ITEMS"][$key]["ACTIVE"] = "Y";
	}

}
$this->includeComponentTemplate();