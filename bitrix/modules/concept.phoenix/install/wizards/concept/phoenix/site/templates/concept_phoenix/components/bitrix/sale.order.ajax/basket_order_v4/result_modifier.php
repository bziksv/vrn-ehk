<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);


$arItemsIdCur = array();

if( !empty($arResult['JS_DATA']["GRID"]["ROWS"]) )
{
	foreach ($arResult['JS_DATA']["GRID"]["ROWS"] as $item)
	{
		$arItemsIdCur[] = $item["data"]["PRODUCT_ID"];
	}


	
	$arFilter = Array("ID"=> $arItemsIdCur);
    $res = CIBlockElement::GetList(Array(), $arFilter);

    while($ar_res = $res->GetNextElement())
    {
    	$arItem = array();
    	$imgTmp = "";

    	$arItem = $ar_res->GetFields();
    	$arItem["PROPERTIES"] = $ar_res->getProperties();


    	// if(!empty($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
    	// {

    	// 	$imgTmp = CFile::ResizeImageGet($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0], array('width'=>80, 'height'=>80), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

    	// 	$curGood[$arItem["ID"]]["SRC"] = $imgTmp['src'];
    	// }
    	
    	if(strlen($arItem["PROPERTIES"]["CML2_LINK"]["VALUE"]))
    	{

    		$arFilter1 = Array("ID"=> $arItem["PROPERTIES"]["CML2_LINK"]["VALUE"]);

		    $res1 = CIBlockElement::GetList(Array(), $arFilter1);

		    while($ar_res1 = $res1->GetNextElement())
		    {
		    	$arItem1 = array();
		    	$imgTmp = "";

		    	$arItem1 = $ar_res1->GetFields();
		    	$arItem1["PROPERTIES"] = $ar_res1->getProperties();


		    	// if( !strlen($curGood[$arItem["ID"]]["SRC"]) && !empty($arItem1["PROPERTIES"]["MORE_PHOTO"]["VALUE"]) && $arItem1["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE"] != "Y")
	    		// {
	    		// 	$imgTmp = CFile::ResizeImageGet($arItem1["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0], array('width'=>80, 'height'=>80), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

	    		// 	$curGood[$arItem["ID"]]["SRC"] = $imgTmp['src'];
	    		
	    		// }

	    		$curGood[$arItem["ID"]]["NAME"] = strip_tags($arItem1["~NAME"]);
	    		$curGood[$arItem["ID"]]["DETAIL_PAGE_URL"] = $arItem1["DETAIL_PAGE_URL"]."?oID=".$arItem["ID"];
		    }
    		

    	}
    	else
    	{
    		$curGood[$arItem["ID"]]["NAME"] = strip_tags($arItem["~NAME"]);
    		$curGood[$arItem["ID"]]["DETAIL_PAGE_URL"] = $arItem["DETAIL_PAGE_URL"];
    	}
    }


    foreach ($arResult['JS_DATA']["GRID"]["ROWS"] as $key => $item)
	{
		$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["NAME"] = $curGood[$item["data"]["PRODUCT_ID"]]["NAME"];
		$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["DETAIL_PAGE_URL"] = $curGood[$item["data"]["PRODUCT_ID"]]["DETAIL_PAGE_URL"];
		// $arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["PREVIEW_PICTURE"] = true;
		// $arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["DETAIL_PICTURE_SRC"] = $arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["PREVIEW_PICTURE_SRC"] = $curGood[$item["data"]["PRODUCT_ID"]]["SRC"];
	}
}