<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $PHOENIX_TEMPLATE_ARRAY;

Bitrix\Main\Type\Collection::sortByColumn($arResult["SET_ITEMS"]["DEFAULT"], array('SET_SORT' => SORT_ASC), '', null, true);

Bitrix\Main\Type\Collection::sortByColumn($arResult["SET_ITEMS"]["OTHER"], array('SET_SORT' => SORT_ASC), '', null, true);

$arrItems=array();
$arrItems[$arResult['PRODUCT_ID']] = $arResult['PRODUCT_ID'];
$arrItems[$arResult['ELEMENT_ID']] = $arResult['ELEMENT_ID'];

foreach (array("DEFAULT", "OTHER") as $type)
{
	foreach ($arResult["SET_ITEMS"][$type] as $key=>$arItem)
	{
		$arrItems[$arItem['ID']] = $arItem['ID'];
	}
}




$arProduct = array();
$arOffers = array();
$arOffer2Product = array();


if(!empty($arrItems))
{
    $arFilter = Array("ID" => $arrItems);
    $arSelect = array("IBLOCK_ID", "PROPERTY_CML2_LINK", "ID");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

    $arProduct = array();
    $arOffers = array();
    $arOffer2Product = array();

    while($ob = $res->GetNext())
    {
        
        if(strlen($ob["PROPERTY_CML2_LINK_VALUE"]))
        {
            $arProduct[] = $ob["PROPERTY_CML2_LINK_VALUE"];
            $arOffers[] = $ob["ID"];
            $arOffer2Product[$ob["ID"]] = $ob["PROPERTY_CML2_LINK_VALUE"];
        }
        else
            $arProduct[] = $ob["ID"];
    }
}

if( !empty($arProduct) )
{
    CPhoenix::getIblockIDs(array("concept_phoenix_catalog_".SITE_ID));

    $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ID" => $arProduct);
    $res = CIBlockElement::GetList(Array(), $arFilter, false);


    $arProductFields = array();

    while($ob = $res->GetNextElement())
    {
        $fields = array();
        $fields = $ob->GetFields();

        $arProductFields[$fields["ID"]] = $fields;
        $arProductFields[$fields["ID"]]["PROPERTIES"] = $ob->GetProperties();
    }
}

if( !empty($arOffers) )
{
    CPhoenix::getIblockIDs(array("concept_phoenix_catalog_offers_".SITE_ID));

    if(empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]))
        CPhoenixSku::getHIBlockOptions();


    $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"], "ID" => $arOffers);
    $res = CIBlockElement::GetList(Array(), $arFilter, false);


    $arOffersFields = array();

    while($ob = $res->GetNextElement())
    {
        $fields = array();
        $fields = $ob->GetFields();

        $arOffersFields[$fields["ID"]] = $fields;
        $arOffersFields[$fields["ID"]]["PROPERTIES"] = $ob->GetProperties();
    }
}



$isOffer = isset($arOffer2Product[$arResult["ELEMENT"]["ID"]]);
$ELEMENT_ID = $arResult["ELEMENT"]["ID"];

if($isOffer)
{
	$productIdOfOrder = $arOffer2Product[$ELEMENT_ID];
	$arResult["ELEMENT"]["NAME"] = strip_tags($arProductFields[$productIdOfOrder]["~NAME"]);
    $arResult["ELEMENT"]["IBLOCK_ID"]=$arOffersFields[$ELEMENT_ID]["IBLOCK_ID"];

	$photo = 0;

    if($arOffersFields[$ELEMENT_ID]["PREVIEW_PICTURE"])
        $photo = $arOffersFields[$ELEMENT_ID]["PREVIEW_PICTURE"];

    else if($arOffersFields[$ELEMENT_ID]["DETAIL_PICTURE"])
        $photo = $arOffersFields[$ELEMENT_ID]["DETAIL_PICTURE"];
    

    else if(!empty($arOffersFields[$ELEMENT_ID]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
        $photo = $arOffersFields[$ELEMENT_ID]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];

    else if($arOffersFields[$ELEMENT_ID]["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE"] != "Y")
    {

        if($arProductFields[$arOffersFields[$ELEMENT_ID]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PREVIEW_PICTURE"])
            $photo = $arProductFields[$arOffersFields[$ELEMENT_ID]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PREVIEW_PICTURE"];

        else if($arProductFields[$arOffersFields[$ELEMENT_ID]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"])
            $photo = $arProductFields[$arOffersFields[$ELEMENT_ID]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"];
        

        else if(!empty($arProductFields[$arOffersFields[$ELEMENT_ID]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
            $photo = $arProductFields[$arOffersFields[$ELEMENT_ID]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];
    }



    if($photo)
    {
        $img = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

        $arResult["ELEMENT"]["PREVIEW_PICTURE_SRC"] = $img["src"];

        
    }
    else
        $arResult["ELEMENT"]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.png";


    $arResult["ELEMENT"]["DETAIL_PAGE_URL"] = $arOffersFields[$ELEMENT_ID]["DETAIL_PAGE_URL"]."?oID=".$ELEMENT_ID;



    if( !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"]) )
    {

        foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"] as $arOfferField)
        {
            if( $arOffersFields[$ELEMENT_ID]["PROPERTIES"][$arOfferField]["USER_TYPE"] == "directory" && strlen($arOffersFields[$ELEMENT_ID]["PROPERTIES"][$arOfferField]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]) )
            {

                foreach ($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"] as $arSKUProp)
                {

                    if( $arOffersFields[$ELEMENT_ID]["PROPERTIES"][$arOfferField]['USER_TYPE_SETTINGS']['TABLE_NAME'] == $arSKUProp['TABLE_NAME'])
                    {
                        if(strlen($arSKUProp["VALUE_NAME"][$arOffersFields[$ELEMENT_ID]["PROPERTIES"][$arOfferField]["VALUE"]]))
                        {

                            $arResult["ELEMENT"]["SKU_LIST"] .= $arOffersFields[$ELEMENT_ID]["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arSKUProp["VALUE_NAME"][$arOffersFields[$ELEMENT_ID]["PROPERTIES"][$arOfferField]["VALUE"]]."<br/>";
                        }

                    }
                }
            }
            else if(strlen($arOffersFields[$ELEMENT_ID]["PROPERTIES"][$arOfferField]["VALUE"]))
            {
                $arResult["ELEMENT"]["SKU_LIST"] .= $arOffersFields[$ELEMENT_ID]["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arOffersFields[$ELEMENT_ID]["PROPERTIES"][$arOfferField]["VALUE"]."<br/>";
            }

        }

        
    }
}
else
{
	$arResult["ELEMENT"]["NAME"] = strip_tags($arProductFields[$ELEMENT_ID]["~NAME"]);

    $arResult["ELEMENT"]["IBLOCK_ID"]=$arProductFields[$ELEMENT_ID]["IBLOCK_ID"];
	$photo = 0;


    if($arProductFields[$ELEMENT_ID]["PREVIEW_PICTURE"])
        $photo = $arProductFields[$ELEMENT_ID]["PREVIEW_PICTURE"];

    else if($arProductFields[$ELEMENT_ID]["DETAIL_PICTURE"])
        $photo = $arProductFields[$ELEMENT_ID]["DETAIL_PICTURE"];
    

    else if(!empty($arProductFields[$ELEMENT_ID]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
        $photo = $arProductFields[$ELEMENT_ID]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];


    if($photo)
    {
        $img = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

        $arResult["ELEMENT"]["PREVIEW_PICTURE_SRC"] = $img["src"];
    }

    else
        $arResult["ELEMENT"]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.png";

    $arResult["ELEMENT"]["DETAIL_PAGE_URL"] = $arProductFields[$ELEMENT_ID]["DETAIL_PAGE_URL"];
}


$arDefaultSetIDs = array($arResult["ELEMENT"]["ID"]);

foreach (array("DEFAULT", "OTHER") as $type)
{
	foreach ($arResult["SET_ITEMS"][$type] as $key=>$arItem)
	{
		$isOffer = isset($arOffer2Product[$arItem["ID"]]);

		$name="";
		$imgSrc="";
		$detaiPageUrl="";
		$skuList="";
		$sectionID="";

		if($isOffer)
        {

        	$productIdOfOrder = $arOffer2Product[$arItem["ID"]];
        	$name = strip_tags($arProductFields[$productIdOfOrder]["~NAME"]);
        	$sectionID=$arProductFields[$productIdOfOrder]["IBLOCK_SECTION_ID"];
        	$photo = 0;


            if($arOffersFields[$arItem["ID"]]["PREVIEW_PICTURE"])
                $photo = $arOffersFields[$arItem["ID"]]["PREVIEW_PICTURE"];

            else if($arOffersFields[$arItem["ID"]]["DETAIL_PICTURE"])
                $photo = $arOffersFields[$arItem["ID"]]["DETAIL_PICTURE"];
            

            else if(!empty($arOffersFields[$arItem["ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                $photo = $arOffersFields[$arItem["ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];

            else if($arOffersFields[$arItem["ID"]]["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE"] != "Y")
            {

                if($arProductFields[$arOffersFields[$arItem["ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PREVIEW_PICTURE"])
                    $photo = $arProductFields[$arOffersFields[$arItem["ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PREVIEW_PICTURE"];

                else if($arProductFields[$arOffersFields[$arItem["ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"])
                    $photo = $arProductFields[$arOffersFields[$arItem["ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"];
                

                else if(!empty($arProductFields[$arOffersFields[$arItem["ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                    $photo = $arProductFields[$arOffersFields[$arItem["ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];
            }



            if($photo)
            {
                $img = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                $imgSrc = $img["src"];

                
            }
            else
                $imgSrc = SITE_TEMPLATE_PATH."/images/ufo.png";


            $detaiPageUrl = $arOffersFields[$arItem["ID"]]["DETAIL_PAGE_URL"]."?oID=".$arItem["ID"];



            if( !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"]) )
            {

                foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"] as $arOfferField)
                {
                    if( $arOffersFields[$arItem["ID"]]["PROPERTIES"][$arOfferField]["USER_TYPE"] == "directory" && strlen($arOffersFields[$arItem["ID"]]["PROPERTIES"][$arOfferField]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]) )
                    {

                        foreach ($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"] as $arSKUProp)
                        {

                            if( $arOffersFields[$arItem["ID"]]["PROPERTIES"][$arOfferField]['USER_TYPE_SETTINGS']['TABLE_NAME'] == $arSKUProp['TABLE_NAME'])
                            {
                                if(strlen($arSKUProp["VALUE_NAME"][$arOffersFields[$arItem["ID"]]["PROPERTIES"][$arOfferField]["VALUE"]]))
                                {

                                    $skuList .= $arOffersFields[$arItem["ID"]]["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arSKUProp["VALUE_NAME"][$arOffersFields[$arItem["ID"]]["PROPERTIES"][$arOfferField]["VALUE"]]."<br/>";
                                }

                            }
                        }
                    }
                    else if(strlen($arOffersFields[$arItem["ID"]]["PROPERTIES"][$arOfferField]["VALUE"]))
                    {
                        $skuList .= $arOffersFields[$arItem["ID"]]["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arOffersFields[$arItem["ID"]]["PROPERTIES"][$arOfferField]["VALUE"]."<br/>";
                    }

                }

                
            }

        }
		else
		{

			$name = strip_tags($arProductFields[$arItem["ID"]]["~NAME"]);
			$sectionID=$arProductFields[$arItem["ID"]]["IBLOCK_SECTION_ID"];

			$photo = 0;

            if($arProductFields[$arItem["ID"]]["DETAIL_PICTURE"])
                $photo = $arProductFields[$arItem["ID"]]["DETAIL_PICTURE"];
            

            else if(!empty($arProductFields[$arItem["ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                $photo = $arProductFields[$arItem["ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];


            if($photo)
            {
                $img = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                $imgSrc = $img["src"];
            }

            else
                $imgSrc = SITE_TEMPLATE_PATH."/images/ufo.png";

            $detaiPageUrl = $arProductFields[$arItem["ID"]]["DETAIL_PAGE_URL"];

		}

		$arElement = array(
			"ID"=>$arItem["ID"],
			"NAME"=>$name,
			"IBLOCK_SECTION_ID"=>$sectionID,
			"PREVIEW_PICTURE_SRC" => $imgSrc,
			"DETAIL_PAGE_URL"=>$detaiPageUrl,
			"SKU_LIST"=> $skuList,
			"DETAIL_PICTURE"=>$arItem["DETAIL_PICTURE"],
			"PREVIEW_PICTURE"=> $arItem["PREVIEW_PICTURE"],
			"PRICE_CURRENCY" => $arItem["PRICE_CURRENCY"],
			"PRICE_DISCOUNT_VALUE" => $arItem["PRICE_DISCOUNT_VALUE"],
			"PRICE_PRINT_DISCOUNT_VALUE" => $arItem["PRICE_PRINT_DISCOUNT_VALUE"],
			"PRICE_VALUE" => $arItem["PRICE_VALUE"],
			"PRICE_PRINT_VALUE" => $arItem["PRICE_PRINT_VALUE"],
			"PRICE_DISCOUNT_DIFFERENCE_VALUE" => $arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"],
			"PRICE_DISCOUNT_DIFFERENCE" => $arItem["PRICE_DISCOUNT_DIFFERENCE"],
			"CAN_BUY" => $arItem['CAN_BUY'],
			"SET_QUANTITY" => $arItem['SET_QUANTITY'],
			"MEASURE_RATIO" => $arItem['MEASURE_RATIO'],
			"BASKET_QUANTITY" => $arItem['BASKET_QUANTITY'],
			"MEASURE" => $arItem['MEASURE']
		);
		if ($arItem["PRICE_CONVERT_DISCOUNT_VALUE"])
			$arElement["PRICE_CONVERT_DISCOUNT_VALUE"] = $arItem["PRICE_CONVERT_DISCOUNT_VALUE"];
		if ($arItem["PRICE_CONVERT_VALUE"])
			$arElement["PRICE_CONVERT_VALUE"] = $arItem["PRICE_CONVERT_VALUE"];
		if ($arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"])
			$arElement["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"] = $arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"];


		$arResult["SET_ITEMS"][$type][$key] = $arElement;

		if ($type == "DEFAULT")
			$arDefaultSetIDs[] = $arItem["ID"];

	}
}

$arSectionsID = array();
foreach (array("DEFAULT", "OTHER") as $type)
{
    foreach ($arResult["SET_ITEMS"][$type] as $key=>$arItem)
    {
        if(strlen($arItem["IBLOCK_SECTION_ID"])>0)
            $arSectionsID[$arItem["IBLOCK_SECTION_ID"]]=$arItem["IBLOCK_SECTION_ID"];
        else
            $arResult["SET_ITEMS"][$type][$key]["IBLOCK_SECTION_ID"]="other";
    }
}

$arResult["SET_SECTIONS"] = array();

if(!empty($arSectionsID))
{
    $arFilter = Array('ID' => $arSectionsID, "ACTIVE"=>"Y");
    $dbResSect = CIBlockSection::GetList(Array("sort"=>"asc"), $arFilter, false, array("ID", "NAME"));

    while($sectRes = $dbResSect->GetNext())
    {
        $sectRes["ITEMS"] = array();
        $sectRes["NAME"] = strip_tags($sectRes["~NAME"]);
        $arResult["SET_SECTIONS"][$sectRes["ID"]]=$sectRes;
    }
}

if(!empty($arResult["SET_ITEMS"]["OTHER"]))
{
    foreach ($arResult["SET_ITEMS"]["OTHER"] as $key=>$arItem)
    {
        if(strlen($arItem["IBLOCK_SECTION_ID"])>0)
            $arResult["SET_SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]["ITEMS"][] = $arItem["ID"];
    } 
}

if(!empty($arResult["SET_SECTIONS"]))
{
    foreach ($arResult["SET_SECTIONS"] as $key=>$arItem)
    {
        $arResult["SET_SECTIONS"][$key]["COUNT"]=count($arItem["ITEMS"]);
    }
}



if ('' == $arParams['TEMPLATE_THEME'])
	$arParams['TEMPLATE_THEME'] = 'blue';


$arResult["DEFAULT_SET_IDS"] = $arDefaultSetIDs;

$arResult["SET_ITEMS_COUNT"] = count($arResult["SET_ITEMS"]["DEFAULT"]) + 1;
