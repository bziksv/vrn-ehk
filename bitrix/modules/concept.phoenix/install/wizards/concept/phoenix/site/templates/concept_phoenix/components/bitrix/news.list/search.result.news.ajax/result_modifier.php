<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arParams["LINE_VIEW_SIZE"] == "half-width")
    $arResult["COLS_ITEMS"] = "col-sm-6 col-12";

else if($arParams["LINE_VIEW_SIZE"] == "three-quarter-width")
    $arResult["COLS_ITEMS"] = "col-md-4 col-sm-6 col-12";

else
    $arResult["COLS_ITEMS"] = "col-md-3 col-sm-6 col-12";



$arResult["PAGE_ELEMENT_COUNT"] = 5;

if(!empty($arResult["ITEMS"]))
{
	foreach($arResult["ITEMS"] as $key => $arItem)
	{
		$arResult["ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH.'/images/ufo.jpg';

		if($arItem['PREVIEW_PICTURE']["ID"])
		{
			$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array('width'=>120, 'height'=>120), BX_RESIZE_IMAGE_EXACT, false);

			$arResult["ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = $img["src"];
		}
	}
}

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
}