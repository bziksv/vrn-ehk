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
$GLOBALS["PHOENIX_CURRENT_PAGE"] = "personal";
$GLOBALS["PHOENIX_CURRENT_DIR"] = "main";



$this->includeComponentTemplate();
