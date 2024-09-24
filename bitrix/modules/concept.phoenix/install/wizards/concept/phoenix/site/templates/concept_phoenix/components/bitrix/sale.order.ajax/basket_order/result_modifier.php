<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

global $PHOENIX_TEMPLATE_ARRAY;
$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

$arrItems = array();


$arResult["ITEMS_INFO"] = array();

if(!empty($arResult['JS_DATA']['GRID']['ROWS']))
{
	foreach ($arResult['JS_DATA']['GRID']['ROWS'] as $arItem) {
		$arrItems[] = $arItem["data"]["PRODUCT_ID"];
	}

	$arFilter = Array("ID" => $arrItems, "IBLOCK_ID"=> array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"]));
	$arSelect = array("IBLOCK_ID", "PROPERTY_CML2_LINK", "ID");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

	$arProduct = array();
	$arOffers = array();
	$arOffer2Propduct = array();

	while($ob = $res->GetNext())
	{
		
		if(strlen($ob["PROPERTY_CML2_LINK_VALUE"]))
		{
			$arProduct[] = $ob["PROPERTY_CML2_LINK_VALUE"];
			$arOffers[] = $ob["ID"];
			$arOffer2Propduct[$ob["ID"]] = $ob["PROPERTY_CML2_LINK_VALUE"];
		}
		else
			$arProduct[] = $ob["ID"];

	}

	if( !empty($arProduct) )
	{
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

		CPhoenixSku::getHIBlockOptions();

	}


	foreach ($arResult['JS_DATA']['GRID']['ROWS'] as $arItem)
	{
		$ID = $arItem["data"]["PRODUCT_ID"];

		
		$isOffer = isset($arOffer2Propduct[$ID]);

		$name = "";
		$skuProps = "";
		$detail_url = "";
		$article = "";

		if($isOffer)
		{
			$productIdOfOrder = $arOffer2Propduct[$ID];

			$photo = 0;

			if($arOffersFields[$ID]["PREVIEW_PICTURE"])
	            $photo = $arOffersFields[$ID]["PREVIEW_PICTURE"];

			else if($arOffersFields[$ID]["DETAIL_PICTURE"])
	            $photo = $arOffersFields[$ID]["DETAIL_PICTURE"];
	        

	        else if(!empty($arOffersFields[$ID]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
	            $photo = $arOffersFields[$ID]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];

	        else if($arOffersFields[$ID]["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE"] != "Y")
	        {

	        	if($arProductFields[$arOffersFields[$ID]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PREVIEW_PICTURE"])
	                $photo = $arProductFields[$arOffersFields[$ID]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PREVIEW_PICTURE"];

	        	else if($arProductFields[$arOffersFields[$ID]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"])
	                $photo = $arProductFields[$arOffersFields[$ID]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"];
	            

	            else if(!empty($arProductFields[$arOffersFields[$ID]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
	                $photo = $arProductFields[$arOffersFields[$ID]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];
	        }


			if($photo)
			{
				$img = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

				$arResult["ITEMS_INFO"][$ID]["PREVIEW_PICTURE_SRC"] = $img["src"];

				
			}
			else
				$arResult["ITEMS_INFO"][$ID]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.png";


			$name = strip_tags($arProductFields[$productIdOfOrder]["~NAME"]);
			$detail_url = $arOffersFields[$ID]["DETAIL_PAGE_URL"]."?oID=".$ID;
			$article = strip_tags($arOffersFields[$ID]["PROPERTIES"]["ARTICLE"]["~VALUE"]);

			

			if( !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"]) )
            {

            	foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"] as $arOfferField)
                {

                	if( $arOffersFields[$ID]["PROPERTIES"][$arOfferField]["USER_TYPE"] == "directory" && strlen($arOffersFields[$ID]["PROPERTIES"][$arOfferField]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]) )
                    {

                    	foreach ($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"] as $arSKUProp)
                        {

	                    	if( $arOffersFields[$ID]["PROPERTIES"][$arOfferField]['USER_TYPE_SETTINGS']['TABLE_NAME'] == $arSKUProp['TABLE_NAME'])
	                        {
	                        	if(strlen($arSKUProp["VALUE_NAME"][$arOffersFields[$ID]["PROPERTIES"][$arOfferField]["VALUE"]]))
	                        		$skuProps .= $arOffersFields[$ID]["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arSKUProp["VALUE_NAME"][$arOffersFields[$ID]["PROPERTIES"][$arOfferField]["VALUE"]]."<br/>";

	                        }
	                    }

                    }

                    else if(strlen($arOffersFields[$ID]["PROPERTIES"][$arOfferField]["VALUE"]))
                    {
                        $skuProps .= $arOffersFields[$ID]["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arOffersFields[$ID]["PROPERTIES"][$arOfferField]["VALUE"]."<br/>";
                    }
                }
            }

		}
		else
		{

			$photo = 0;


			if($arProductFields[$ID]["PREVIEW_PICTURE"])
                $photo = $arProductFields[$ID]["PREVIEW_PICTURE"];

            else if($arProductFields[$ID]["DETAIL_PICTURE"])
                $photo = $arProductFields[$ID]["DETAIL_PICTURE"];
            

            else if(!empty($arProductFields[$ID]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                $photo = $arProductFields[$ID]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];


			if($photo)
			{
				$img = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

				$arResult["ITEMS_INFO"][$ID]["PREVIEW_PICTURE_SRC"] = $img["src"];
			}

			else
				$arResult["ITEMS_INFO"][$ID]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.png";

			$detail_url = $arProductFields[$ID]["DETAIL_PAGE_URL"];
			$article = strip_tags($arProductFields[$ID]["PROPERTIES"]["ARTICLE"]["~VALUE"]);

			$name = strip_tags($arProductFields[$ID]["~NAME"]);

		}


		$arResult["ITEMS_INFO"][$ID]["NAME"] .= $name;
		$arResult["ITEMS_INFO"][$ID]["DETAIL_URL"] .= $detail_url;


		$arResult["ITEMS_INFO"][$ID]["HTML"] .= "<a href=\"".$detail_url."\" class=\"d-block bold product-name\">".$name."</a>";



		if(strlen($article)>0)
			$arResult["ITEMS_INFO"][$ID]["HTML"] .= "<div class=\"article italic\">".$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$article."</div>";


		if(strlen($skuProps)>0)
			$arResult["ITEMS_INFO"][$ID]["HTML"] .= "<div class=\"name_offers\">".$skuProps."<br></div>";
		
	}
}



