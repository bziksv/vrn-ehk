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

use Bitrix\Main\Loader;
use Bitrix\CPhoenixReviewsInfo;

if(!Loader::includeModule("iblock"))
{
	$this->abortResultCache();
	ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
	return;
}



$arResult = array();
$arResult["ITEMS"] = array();

$arResult["ITEMS"] = CPhoenixReviewsInfo\CPhoenixReviewsInfoTable::getList(array('filter' => array('=PRODUCT_ID' => $arParams["PRODUCT_ID"]),"cache"=>array("ttl"=>3600)))->fetch();



$this->includeComponentTemplate();