<?
use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */
global $PHOENIX_TEMPLATE_ARRAY;
CPhoenixSku::getHIBlockOptions();

$arEditAreaId = array();


if(!empty($arResult["ITEMS"]))
{
	$arResult["VIEW"] = $arParams["VIEW"];
    foreach ($arResult["ITEMS"] as $key => $arItem){
        $arEditAreaId[$arItem["ID"]] = $this->GetEditAreaId($arItem['ID']).'_list';
    }

	CPhoenix::SetResultModifierCatalogElements($arResult, $arParams, $arEditAreaId);
	
}
$cp = $this->__component;
 
if (is_object($cp))
	$cp->SetResultCacheKeys(array('ITEMS_ID', 'ITEMS'));

?>