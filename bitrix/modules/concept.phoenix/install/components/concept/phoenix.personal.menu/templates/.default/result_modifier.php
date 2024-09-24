<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION, $PHOENIX_TEMPLATE_ARRAY;
$cur_page = $_SERVER["REQUEST_URI"];
$cur_page_no_index = $APPLICATION->GetCurPage(false);


if($cur_page_no_index != SITE_DIR."personal/")
{
	$mainKey = -1;
	if(!empty($arResult["ITEMS"]))
	{

		foreach ($arResult["ITEMS"] as $key => $arItem)
		{
			if($arItem["PATH"] == SITE_DIR."personal/")
				$mainKey = $key;

			if($mainKey != -1 && $mainKey != $key && $arItem["ACTIVE"] == "Y")
				$arResult["ITEMS"][$mainKey]["ACTIVE"] = "";


			if(isset($_REQUEST["filter_history"]) 
				&& $_REQUEST["filter_history"] == "Y" 
				&& $cur_page_no_index == SITE_DIR."personal/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["ORDERS"]["URL"]
				&& $arItem["PATH"] == SITE_DIR."personal/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["ORDERS"]["URL"])
			{

				$arResult["ITEMS"][$key]["ACTIVE"] = "";
			}
			
		}
	}
}

