<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


global $APPLICATION;
global $PHOENIX_TEMPLATE_ARRAY;


	
$basket_url = CPhoenix::getBasketUrl(SITE_DIR, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]);



$GLOBALS["PHOENIX_CURRENT_PAGE"] = "basket";
$GLOBALS["PHOENIX_CURRENT_DIR"] = "main";


$componentPage = "";

if($basket_url == $APPLICATION->GetCurPage(false))
    $componentPage = "basket_page";




$b404 = false;




if($basket_url."order/" == $APPLICATION->GetCurPage(false))
{

	if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"] != "Y")
		LocalRedirect(SITE_DIR);

	if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_PAGES"]["VALUE"]=="one")
		LocalRedirect(str_replace("/order", "", $APPLICATION->GetCurUri(false)));
	else
		$componentPage = "order_page";
}





if(!$componentPage)
	$b404 = true;


if($b404 && CModule::IncludeModule('iblock'))
{

	/*$folder404 = SITE_DIR.$basket_url."/";
	if ($folder404 != "/")
		$folder404 = "/".trim($folder404, "/ \t\n\r\0\x0B")."/";

	if (substr($folder404, -1) == "/")
		$folder404 .= "index.php";

	if ($folder404 != $APPLICATION->GetCurPage(true))
	{*/
		\Bitrix\Iblock\Component\Tools::process404(
			""
			,(true)
			,(true)
			,(true)
			,""
		);
	/*}*/
}



if($componentPage == "basket_page" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["TEMPLATE_BASKET"]["VALUE"])>0)
	require_once($_SERVER["DOCUMENT_ROOT"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["TEMPLATE_BASKET"]["VALUE"]);

else if($componentPage == "order_page" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["TEMPLATE_ORDER_PAGE"]["VALUE"])>0)
	require_once($_SERVER["DOCUMENT_ROOT"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["TEMPLATE_ORDER_PAGE"]["VALUE"]);

else
	$this->IncludeComponentTemplate($componentPage);
