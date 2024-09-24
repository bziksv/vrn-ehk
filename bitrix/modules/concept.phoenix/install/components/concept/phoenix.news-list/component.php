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


if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

if(!$arParams["ELEMENTS_ID"])
	return false;



$arSort = array(
	$arParams["SORT_BY1"]=>$arParams["SORT_ORDER1"],
	"ID" => "ASC",
);


if($this->startResultCache($arParams["CACHE_TIME"]))
{
	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	$arResult = array();
	$nTopCount = false;

	if($arParams["ELEMENTS_COUNT"])
		$nTopCount = array("nTopCount" => $arParams["ELEMENTS_COUNT"]);
	
	$arResult["SHOW_DATA"] = false;

	$arFilter = Array("ID"=> $arParams["ELEMENTS_ID"], "ACTIVE" => "Y");
    $res = CIBlockElement::GetList($arSort, $arFilter, false, $nTopCount);

    $arResult["ALL_ID"] = array();
    while($ob = $res->GetNextElement())
    {
        $arItem = $ob->GetFields();  
        $arItem["PROPERTIES"] = $ob->GetProperties();
        $arResult["ITEMS"][] = $arItem;

        if(is_array($arItem["PROPERTIES"]["TEXT"]["VALUE"]))
        	$arResult["ALL_ID"][]= $arItem["ID"];
    }

    $arResult["ALL_ID"] = implode(',',$arResult["ALL_ID"]);

	$this->includeComponentTemplate();
}