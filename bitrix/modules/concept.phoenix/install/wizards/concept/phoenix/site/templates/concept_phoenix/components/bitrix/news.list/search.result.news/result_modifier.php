<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!empty($arParams["SEARCH_RESULT"]['ITEMS_ID']))
{
	foreach ($arParams["SEARCH_RESULT"]['ITEMS_ID'] as $key => $value){
	   $arParams["SEARCH_RESULT"]['ITEMS_ID'][$key] = intval($value);
	}
}

$newArResult = array();

if(!empty($arResult["ITEMS"]))
{
	foreach ($arResult["ITEMS"] as $key => $value)
	{
	    $newKey = array_search(intval($value["ID"]), $arParams["SEARCH_RESULT"]['ITEMS_ID']);
	    $newArResult[$newKey] = $value;
	}
}

if(!empty($newArResult))
{
	ksort($newArResult);
	$arResult["ITEMS"] = array_values($newArResult);

	foreach ($arResult["ITEMS"] as $value){
		$arResult["ELENETNS_ID"][] = $value["ID"];
	}
}