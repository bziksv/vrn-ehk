<?
use Bitrix\Main\Type\Collection;
use Bitrix\Currency\CurrencyTable;
use Bitrix\Iblock;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>

<?
global $PHOENIX_TEMPLATE_ARRAY;
CPhoenixSku::getHIBlockOptions();


$arResult["VIEW"] = "FLAT";

$arEditAreaId = array();

if(!empty($arResult["ITEMS"]))
{
    foreach ($arResult["ITEMS"] as $key => $arItem){
        $arEditAreaId[$arItem["ID"]] = $this->GetEditAreaId($arItem['ID']).'_catalog_labels';
    }
}

CPhoenix::SetResultModifierCatalogElements($arResult, $arParams, $arEditAreaId);


$arResult["rating"] = array();

if(!empty($arResult["ITEMS"]) && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]["PROPS_IN_LIST_FOR_FLAT"]["VALUE"]["RATE_VOTE"] == "Y")
{
	if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_REVIEW"]["VALUE"]["ACTIVE"] == "Y")
    {
        $arResult["RATING_VIEW"] = "full";
        $arResult["ITEMS_ID"] = array();
        foreach ($arResult["ITEMS"] as $key => $arItem){
           $arResult["ITEMS_ID"][] = $arItem["ID"];
        }
    }
    else
    {
    	$arResult["RATING_VIEW"] = "simple";

	    foreach ($arResult["ITEMS"] as $key => $arItem){
	       $arResult["rating"][$arItem["ID"]] = (strlen($arItem["PROPERTIES"]["rating"]["VALUE"]))?round($arItem["PROPERTIES"]["rating"]["VALUE"]):"0";
	    }
	}
}


$arResult["LABEL"] = $arParams["LABEL"];
$this->__component->arResultCacheKeys = array_merge($this->__component->arResultCacheKeys, array("RATING_VIEW","ITEMS_ID","ITEMS", "LABEL", "COLS", "rating"));