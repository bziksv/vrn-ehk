<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Type\Collection;
use Bitrix\Currency\CurrencyTable;
use Bitrix\Iblock;
?>

<?
global $PHOENIX_TEMPLATE_ARRAY;

$arResult["VIEW"] = $arParams["VIEW"];

if(!strlen($arParams["VIEW"]))
    $arResult["VIEW"] = "FLAT SLIDER";

$arResult["TEMPLATE"] = "SLIDER";



$arEditAreaId = array();

if(!empty($arResult["ITEMS"]))
{
    foreach ($arResult["ITEMS"] as $key => $arItem){
        $arEditAreaId[$arItem["ID"]] = $this->GetEditAreaId($arItem['ID']).'_list';
    }
}


CPhoenix::SetResultModifierCatalogElements($arResult, $arParams, $arEditAreaId);

$cp = $this->__component;
if (is_object($cp))
{
    $cp->SetResultCacheKeys(array('ITEMS_ID', 'ITEMS'));
}