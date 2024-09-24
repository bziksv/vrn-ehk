<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $PHOENIX_TEMPLATE_ARRAY;

if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] != "Y" )
{
	global $APPLICATION;

	if (!defined("ERROR_404"))
       define("ERROR_404", "Y");

    \CHTTP::setStatus("404 Not Found");
       
    if ($APPLICATION->RestartWorkarea()) {
       require(\Bitrix\Main\Application::getDocumentRoot()."/404.php");
       die();
    }
}

use Bitrix\Main\Type\Collection;

//$arMeasure = CPhoenix::getArMeasure();


$arResult['ALL_FIELDS'] = array();
$existShow = !empty($arResult['SHOW_FIELDS']);
$existDelete = !empty($arResult['DELETED_FIELDS']);


if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult['SHOW_FIELDS'] as $propCode)
		{
			$arResult['SHOW_FIELDS'][$propCode] = array(
				'CODE' => $propCode,
				'IS_DELETED' => 'N',
				'ACTION_LINK' => str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode);
		$arResult['ALL_FIELDS'] = $arResult['SHOW_FIELDS'];
		if($arResult['ALL_FIELDS']["PREVIEW_PICTURE"] || $arResult['ALL_FIELDS']["DETAIL_PICTURE"])
			unset($arResult['ALL_FIELDS']["PREVIEW_PICTURE"],$arResult['ALL_FIELDS']["DETAIL_PICTURE"]);
	}
	if ($existDelete)
	{
		foreach ($arResult['DELETED_FIELDS'] as $propCode)
		{
			$arResult['ALL_FIELDS'][$propCode] = array(
				'CODE' => $propCode,
				'IS_DELETED' => 'Y',
				'ACTION_LINK' => str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode, $arResult['DELETED_FIELDS']);
	}
	Collection::sortByColumn($arResult['ALL_FIELDS'], array('SORT' => SORT_ASC));
}

$arResult['ALL_PROPERTIES'] = array();
$existShow = !empty($arResult['SHOW_PROPERTIES']);
$existDelete = !empty($arResult['DELETED_PROPERTIES']);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult['SHOW_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult['SHOW_PROPERTIES'][$propCode]['IS_DELETED'] = 'N';
			$arResult['SHOW_PROPERTIES'][$propCode]['ACTION_LINK'] = str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_PROPERTY_TEMPLATE']);
		}
		$arResult['ALL_PROPERTIES'] = $arResult['SHOW_PROPERTIES'];
	}
	unset($arProp, $propCode);
	if ($existDelete)
	{
		foreach ($arResult['DELETED_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult['DELETED_PROPERTIES'][$propCode]['IS_DELETED'] = 'Y';
			$arResult['DELETED_PROPERTIES'][$propCode]['ACTION_LINK'] = str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_PROPERTY_TEMPLATE']);
			$arResult['ALL_PROPERTIES'][$propCode] = $arResult['DELETED_PROPERTIES'][$propCode];
		}
		unset($arProp, $propCode, $arResult['DELETED_PROPERTIES']);
	}
	Collection::sortByColumn($arResult["ALL_PROPERTIES"], array('SORT' => SORT_ASC, 'ID' => SORT_ASC));
}

$arResult["ALL_OFFER_FIELDS"] = array();
$existShow = !empty($arResult["SHOW_OFFER_FIELDS"]);
$existDelete = !empty($arResult["DELETED_OFFER_FIELDS"]);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult["SHOW_OFFER_FIELDS"] as $propCode)
		{
			if($propCode=="PREVIEW_PICTURE" || $propCode=="DETAIL_PICTURE"){
				unset($arResult["SHOW_OFFER_FIELDS"][$propCode]);
			}else{
				$arResult["SHOW_OFFER_FIELDS"][$propCode] = array(
					"CODE" => $propCode,
					"IS_DELETED" => "N",
					"ACTION_LINK" => str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_OF_FIELD_TEMPLATE']),
					'SORT' => $arResult['FIELDS_SORT'][$propCode]
				);
			}
		}
		unset($propCode);
		$arResult['ALL_OFFER_FIELDS'] = $arResult['SHOW_OFFER_FIELDS'];
	}
	if ($existDelete)
	{
		foreach ($arResult['DELETED_OFFER_FIELDS'] as $propCode)
		{
			$arResult['ALL_OFFER_FIELDS'][$propCode] = array(
				"CODE" => $propCode,
				"IS_DELETED" => "Y",
				"ACTION_LINK" => str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_OF_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode, $arResult['DELETED_OFFER_FIELDS']);
	}
	Collection::sortByColumn($arResult['ALL_OFFER_FIELDS'], array('SORT' => SORT_ASC));
}

$arResult['ALL_OFFER_PROPERTIES'] = array();
$existShow = !empty($arResult["SHOW_OFFER_PROPERTIES"]);
$existDelete = !empty($arResult["DELETED_OFFER_PROPERTIES"]);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult['SHOW_OFFER_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult["SHOW_OFFER_PROPERTIES"][$propCode]["IS_DELETED"] = "N";
			$arResult["SHOW_OFFER_PROPERTIES"][$propCode]["ACTION_LINK"] = str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_OF_PROPERTY_TEMPLATE']);
		}
		unset($arProp, $propCode);
		$arResult['ALL_OFFER_PROPERTIES'] = $arResult['SHOW_OFFER_PROPERTIES'];
	}
	if ($existDelete)
	{
		foreach ($arResult['DELETED_OFFER_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult["DELETED_OFFER_PROPERTIES"][$propCode]["IS_DELETED"] = "Y";
			$arResult["DELETED_OFFER_PROPERTIES"][$propCode]["ACTION_LINK"] = str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_OF_PROPERTY_TEMPLATE']);
			$arResult['ALL_OFFER_PROPERTIES'][$propCode] = $arResult["DELETED_OFFER_PROPERTIES"][$propCode];
		}
		unset($arProp, $propCode, $arResult['DELETED_OFFER_PROPERTIES']);
	}
	Collection::sortByColumn($arResult['ALL_OFFER_PROPERTIES'], array('SORT' => SORT_ASC, 'ID' => SORT_ASC));
}


$arSKUPropList = CPhoenixSku::getHIBlockOptions();



if(!empty($arResult["ITEMS"]))
{
	
	$arOffersID = array();

	$arIDs = array(); 

	foreach ($arResult["ITEMS"] as $key => $arItem)
	{
		if($arItem["ID"] != $arItem["~ID"])
		{
			$arOffersID[] = $arItem["ID"];
			$arProductID[] = $arItem["~ID"];
		}
		else
			$arProductID[] = $arItem["ID"];

		$arIDs[] = $arItem["ID"];
	}

	if(!empty($arIDs))
		$arMeasure = \Bitrix\Catalog\ProductTable::getCurrentRatioWithMeasure($arIDs);
	

	if(!empty($arProductID))
	{

		$arProducts = array();

		$arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ID" => $arProductID);
        $res = CIBlockElement::GetList(Array(), $arFilter, false);


        while($ob = $res->GetNextElement())
        {
            $fields = $ob->GetFields();
            $fields["PROPERTIES"] = $ob->GetProperties();
            $arProducts[$fields["ID"]] = $fields;
        }
	}

	if(!empty($arOffersID))
	{	
		$arOffers = array();

		$arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"], "ID" => $arOffersID);
        $res = CIBlockElement::GetList(Array(), $arFilter, false);


        while($ob = $res->GetNextElement())
        {
            $fields = $ob->GetFields();
            $fields["PROPERTIES"] = $ob->GetProperties();
            $arOffers[$fields["ID"]] = $fields;
        }
	}

	foreach ($arResult["ITEMS"] as $key => $arItem)
	{
		$pictureID = 0;


		/*if(isset($arMeasure[$arItem["CATALOG_MEASURE"]]["SYMBOL"]))
			$arResult["ITEMS"][$key]["MEASURE_HTML"] = "&nbsp;/&nbsp;".$arMeasure[$arItem["CATALOG_MEASURE"]]["SYMBOL"];*/


		if(isset($arMeasure[$arItem['ID']]))
		{
			if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MEASURE"]["VALUE"][$arMeasure[$arItem['ID']]['MEASURE']['ID']] == "Y")
				$arResult["ITEMS"][$key]["MEASURE_HTML"] = "&nbsp;/&nbsp;".$arMeasure[$arItem['ID']]['MEASURE']['~SYMBOL'];
		}


		
		$pictureID = 0;
		
		if($arItem["ID"] != $arItem["~ID"])
		{
			$arResult["ITEMS"][$key]["NAME"] = strip_tags($arItem["~NAME"]);

			$arResult["ITEMS"][$key]["DETAIL_PAGE_URL"] = $arOffers[$arItem["ID"]]["DETAIL_PAGE_URL"]."?oID=".$arOffers[$arItem["ID"]]["~ID"];

			
			if($arOffers[$arItem["ID"]]["PREVIEW_PICTURE"])
				$pictureID = $arOffers[$arItem["ID"]]["PREVIEW_PICTURE"];

			else if($arOffers[$arItem["ID"]]["DETAIL_PICTURE"])
				$pictureID = $arOffers[$arItem["ID"]]["DETAIL_PICTURE"];

			else if(!empty($arOffers[$arItem["ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
				$pictureID = $arOffers[$arItem["ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];

			else if($arOffers[$arItem["PRODUCT_ID"]]["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE"] != "Y")
			{

				if($arProducts[$arOffers[$arItem["ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PREVIEW_PICTURE"])
	                $pictureID = $arProducts[$arOffers[$arItem["ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PREVIEW_PICTURE"];

				else if($arProducts[$arOffers[$arItem["ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"])
	                $pictureID = $arProducts[$arOffers[$arItem["ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"];
	            

	            else if(!empty($arProducts[$arOffers[$arItem["ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
	                $pictureID = $arProducts[$arOffers[$arItem["ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];
			}

			

			/*$arResult["ITEMS"][$key]["NAME_OFFERS"] = "";

			if( !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"]) )
	        {

	            foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"] as $arOfferField)
	            {
	                if( $arItem["OFFER_PROPERTIES"][$arOfferField]["USER_TYPE"] == "directory" && strlen($arItem["OFFER_PROPERTIES"][$arOfferField]["VALUE"]) && !empty($arSKUPropList))
	                {
	                    
	                    foreach ($arSKUPropList as $arSKUProp)
	                    {
	                        if( $arItem["OFFER_PROPERTIES"][$arOfferField]['USER_TYPE_SETTINGS']['TABLE_NAME'] == $arSKUProp['TABLE_NAME'])
	                        {

	                            $arResult["ITEMS"][$key]["NAME_OFFERS"] .= $arItem["OFFER_PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arSKUProp["VALUE_NAME"][$arItem["OFFER_PROPERTIES"][$arOfferField]["VALUE"]].";<br/>";

	                        }
	                        
	                    }

	                    
	                    
	                }
	                else if(strlen($arItem["OFFER_PROPERTIES"][$arOfferField]["VALUE"]))
	                {
	                    $arResult["ITEMS"][$key]["NAME_OFFERS"] .= $arItem["OFFER_PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arItem["OFFER_PROPERTIES"][$arOfferField]["VALUE"].";<br/>";
	                }
	            }
	        }*/
		}
		else
		{

			$arResult["ITEMS"][$key]["NAME"] = strip_tags($arProducts[$arItem["ID"]]["~NAME"]);
			
			$arResult["ITEMS"][$key]["DETAIL_PAGE_URL"] = $arProducts[$arItem["ID"]]["DETAIL_PAGE_URL"];

			if($arProducts[$arItem["ID"]]["PREVIEW_PICTURE"])
				$pictureID = $arProducts[$arItem["ID"]]["PREVIEW_PICTURE"];

			else if($arProducts[$arItem["ID"]]["DETAIL_PICTURE"])
				$pictureID = $arProducts[$arItem["ID"]]["DETAIL_PICTURE"];

			else if(!empty($arProducts[$arItem["ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
				$pictureID = $arProducts[$arItem["ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];


		}

		if($pictureID)
		{
			$img = CFile::ResizeImageGet($pictureID, array('width'=>320, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

			$arResult["ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = $img["src"];
		}
		else
			$arResult["ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.png";


	}




	$arResult["ITEMS_COUNT"] = count($arResult["ITEMS"]);

	$arResult["ITEMS_TERM"] = array(
		$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_1"],
		$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_2"],
		$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_3"],
		$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_4"],
	);
}


?>