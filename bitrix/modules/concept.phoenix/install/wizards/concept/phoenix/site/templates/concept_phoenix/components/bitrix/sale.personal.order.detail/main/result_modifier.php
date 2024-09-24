<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $PHOENIX_TEMPLATE_ARRAY;
$arProductsID = array();
$arOffersID = array();
$arMainProductsID = array();

if(!empty($arResult['BASKET']))
{
	foreach ($arResult['BASKET'] as $key => $arItem){
		
		if(isset($arItem["PARENT"])){
			$arMainProductsID[$arItem["PARENT"]["ID"]] = $arItem["PARENT"]["ID"];
			$arResult['BASKET'][$key]["DETAIL_PAGE_URL"] = $arItem["DETAIL_PAGE_URL"]."?oID=".$arItem["PRODUCT_ID"];
			$arOffersID[] = $arItem["PRODUCT_ID"];
		}
		else
		{
			$arMainProductsID[$arItem["ID"]] = $arItem["PRODUCT_ID"];
		}
	}
}



if(!empty($arOffersID))
{
	$arOffersName = CPhoenix::GetOffersName($arOffersID);
}

if(!empty($arMainProductsID))
{
	$newArProduct = array();
	$arItem = array();
	
	$arSelect = array("ID", "NAME", "PROPERTY_AFTER_PAY_TEXT");
	$arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ID" => $arMainProductsID);
    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);

    while($ob = $res->GetNextElement())
    {
        $arItem = $ob->GetFields();
        $arItem["NAME"] = strip_tags($arItem["~NAME"]);
        $newArProduct[$arItem["ID"]] = $arItem;

    }



    foreach($arResult['BASKET'] as $key => $arItem){
	
		if(isset($arItem["PARENT"]))
		{
			$arResult['BASKET'][$key]["NAME"] = $newArProduct[$arItem["PARENT"]["ID"]]["NAME"];
			$arResult['BASKET'][$key]["NAME_OFFERS"] = $arOffersName[$arItem["PRODUCT_ID"]];
			$arResult['BASKET'][$key]["AFTER_PAY_TEXT"] = $newArProduct[$arItem["PARENT"]["ID"]]["~PROPERTY_AFTER_PAY_TEXT_VALUE"];
		}
		else
		{
			$arResult['BASKET'][$key]["AFTER_PAY_TEXT"] = $newArProduct[$arItem["PRODUCT_ID"]]["~PROPERTY_AFTER_PAY_TEXT_VALUE"];;
		}
	}
}