<?use \Bitrix\Main\Config\Option as Option;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Loader,
    Bitrix\Main\ModuleManager,
    Bitrix\Iblock,
    Bitrix\Catalog,
    Bitrix\Currency,
    Bitrix\Currency\CurrencyTable,
    Bitrix\Main,
    Bitrix\Sale,
    Bitrix\Sale\Order,
    Bitrix\Main\Application,
    Bitrix\Sale\DiscountCouponsManager,
    Bitrix\Main\Localization\Loc;

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/classes/general/CPhoenix.php");

class CPhoenixShop 
{
    protected static $handlerDisallow = false;
    
    public static function setPreviewPicture(&$arFields)
    {   
        if(!Loader::includeModule('iblock'))
            return;


        $res = CIBlock::GetByID($arFields["IBLOCK_ID"]);
        
        if($ar_res = $res->GetNext())
            $iBlock["IBLOCK_CODE"] = $ar_res['CODE'];

        if(preg_match("/^concept_phoenix_catalog_/", $iBlock["IBLOCK_CODE"]))
        {
            $ElementID = $arFields["ID"];
            $IBlockID = $arFields["IBLOCK_ID"];

            $newFileArray = array();
            $deletePreviewPicture = false;
            $saveFromList = false; // when previewPicture isset, but location in list
            $morePhotoEmpty = false;
            $detailEmpty = false;
            $loadDetailPicture = false;
            $loadMorePhoto = false;
            $issetPreviewPicture = isset($arFields["PREVIEW_PICTURE"]);


            if($issetPreviewPicture)
            {
                return true;
                /*$newFileArray = $arFields["PREVIEW_PICTURE"];*/
            }
          


            if(isset($arFields["DETAIL_PICTURE"]))
            {
                if(isset($arFields["DETAIL_PICTURE"]["del"]))
                {
                    if($arFields["DETAIL_PICTURE"]["del"] == "Y")
                        $detailEmpty = true;
                }


                $loadDetailPicture = !$detailEmpty && ($arFields["DETAIL_PICTURE"]["error"]=="0" || (!$issetPreviewPicture && isset($arFields["DETAIL_PICTURE"]["old_file"]))); 
            }
            else
                $detailEmpty = true;


            $loadMorePhoto = !$loadDetailPicture;


            if($loadDetailPicture)
            {
                $newFileArray = $arFields["DETAIL_PICTURE"];

                if(!$issetPreviewPicture)
                {
                    if(isset($arFields["DETAIL_PICTURE"]["old_file"]))
                    {
                       $file = CIBlock::makeFileArray($arFields["DETAIL_PICTURE"]["old_file"], false, null, array('allow_file_id' => true));

                        if (is_array($file))
                        {
                            $newFileArray = array();
                            $file['COPY_FILE'] = 'Y';
                            $newFileArray = $file;
                            $newFileArray["MODULE_ID"] = "iblock";
                        } 
                    }
                }
            }

            
            if($loadMorePhoto)
            {

                if($ElementID > 0)
                {
                    $rsLinkProperty = CIBlockElement::GetProperty(
                        $IBlockID,
                        $ElementID,
                        "sort",
                        "asc",
                        array("CODE" => "MORE_PHOTO")
                    );
                }
                else
                    $rsLinkProperty = CIBlockProperty::GetList(Array("sort"=>"asc"), Array("IBLOCK_ID"=>$IBlockID, "CODE"=>"MORE_PHOTO"));
                

                if($arLinkProperty = $rsLinkProperty->GetNext())
                {

                    if(is_array($arFields["PROPERTY_VALUES"][$arLinkProperty["ID"]]))
                        $propValueID = key($arFields["PROPERTY_VALUES"][$arLinkProperty["ID"]]);

                    if($propValueID == "n0")
                    {
                        $newFileArray = $arFields["PROPERTY_VALUES"][$arLinkProperty["ID"]][$propValueID]["VALUE"];
                        $newFileArray["COPY_FILE"] = "Y";
                        $newFileArray["MODULE_ID"] = "iblock";
                        $newFileArray["old_file"] = "";
                    }

                    else
                    {
                        if($propValueID)
                        {
                            if($arFields["PROPERTY_VALUES"][$arLinkProperty["ID"]][$propValueID]["VALUE"]["del"] == "Y")
                                $propValueID = 0; 
                        }

                        if($propValueID)
                        {

                            if($arLinkProperty["PROPERTY_VALUE_ID"] != $propValueID || empty($newFileArray) || $newFileArray["del"] == "Y")
                            {
                                $iterator = CIBlockElement::GetPropertyValues($IBlockID, array('ID' => $ElementID), true, array('ID' => array($arLinkProperty["ID"])));

                                if($arMorePhoto = $iterator->Fetch())
                                {
                                    if(!empty($arMorePhoto["PROPERTY_VALUE_ID"][$arLinkProperty["ID"]]))
                                    {

                                        foreach ($arMorePhoto["PROPERTY_VALUE_ID"][$arLinkProperty["ID"]] as $key => $value)
                                        {
                                            if($value == $propValueID)
                                            {
                                                $keyValueID = $key;
                                                break;
                                            }
                                        }

                                        if($arMorePhoto[$arLinkProperty["ID"]][$keyValueID])
                                        {
                                            $valueID = $arMorePhoto[$arLinkProperty["ID"]][$keyValueID];

                                            $file = CIBlock::makeFileArray($valueID, false, null, array('allow_file_id' => true));

                                            if (is_array($file))
                                            {
                                                $file['COPY_FILE'] = 'Y';
                                                $newFileArray = $file;
                                                $newFileArray["MODULE_ID"] = "iblock";
                                                $newFileArray["old_file"] = $arFields["PREVIEW_PICTURE"]["old_file"];
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        else
                        {
                            $arFilter = Array("ID"=>IntVal($ElementID));
                            $res = CIBlockElement::GetList(Array(), $arFilter, false);

                            while($ob = $res->GetNextElement())
                            {
                                $arProduct = $ob->GetFields();
                                $arProductProps = $ob->GetProperties();
                                $previewPictureID = $arProduct["PREVIEW_PICTURE"];
                                $arProductPropPictureID = $arProductProps["MORE_PHOTO"]["VALUE"][0];
                            }

                            if($arProductPropPictureID)
                            {
                                if(!$previewPictureID && $arProductPropPictureID)
                                {
                                    $file = CIBlock::makeFileArray($arProductPropPictureID, false, null, array('allow_file_id' => true));

                                    if (is_array($file))
                                    {
                                        $newFileArray = $file;
                                        $newFileArray["COPY_FILE"] = "Y";
                                        $newFileArray["MODULE_ID"] = "iblock";
                                    }
                                        
                                }

                                elseif($previewPictureID && $arProductPropPictureID)
                                    $saveFromList = true;
                            }
                            else
                                $morePhotoEmpty = true;

                            

                            // else
                            // {
                            //     $rsLinkProperty = CIBlockProperty::GetList(Array("sort"=>"asc"), Array("IBLOCK_ID"=>$IBlockID, "CODE"=>"NO_MERGE_PHOTOS"));

                            //     if($arLinkProperty = $rsLinkProperty->GetNext())
                            //     {

                            //         if(!is_array($arFields["PROPERTY_VALUES"][$arLinkProperty["ID"]]))
                            //         {
                            //             $rsLinkProperty = CIBlockProperty::GetList(Array("sort"=>"asc"), Array("IBLOCK_ID"=>$IBlockID, "CODE"=>"CML2_LINK"));

                            //             if($arLinkProperty = $rsLinkProperty->GetNext())
                            //             {

                            //                 if(is_array($arFields["PROPERTY_VALUES"][$arLinkProperty["ID"]]) && !empty($arFields["PROPERTY_VALUES"][$arLinkProperty["ID"]]))
                            //                 {
                            //                     $productID = reset(reset($arFields["PROPERTY_VALUES"][$arLinkProperty["ID"]]));
                            //                     $previewPictureID = 0;


                            //                     $arSelect = Array("ID", "PREVIEW_PICTURE");
                            //                     $arFilter = Array("ID"=>IntVal($productID));
                            //                     $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

                            //                     while($ob = $res->GetNextElement())
                            //                     {
                            //                         $arProduct = $ob->GetFields();
                            //                         $previewPictureID = $arProduct["PREVIEW_PICTURE"];
                            //                     }

                            //                     if($previewPictureID)
                            //                     {
                            //                         $rsFile = CFile::GetByID($arProduct["PREVIEW_PICTURE"]);
                            //                         $arFile = $rsFile->Fetch();

                            //                         $timePreviewPictureProduct = $arFile["TIMESTAMP_X"];

                            //                         global $DB;

                            //                         $result = $DB->CompareDates($timePreviewPictureProduct, $timePreviewPicture);

                            //                         if($result == 1 || $arFields["PREVIEW_PICTURE"]["del"] == "Y")
                            //                         {
                            //                             $file = CIBlock::makeFileArray($previewPictureID, false, null, array('allow_file_id' => true));

                            //                             if (is_array($file))
                            //                             {
                            //                                 $file['COPY_FILE'] = 'Y';
                            //                                 $newFileArray = $file;
                            //                                 $newFileArray["MODULE_ID"] = "iblock";
                            //                                 $newFileArray["old_file"] = $arFields["PREVIEW_PICTURE"]["old_file"];
                            //                             }
                            //                         }
                            //                     }
                            //                     else
                            //                     {
                            //                         $deletePreviewPicture = true;
                            //                     }
                            //                 }
                            //             }

                            //         }
                            //         else
                            //         {
                            //             $deletePreviewPicture = true;
                            //         }
                                    
                            //     }
                            //     else
                            //     {
                            //         $deletePreviewPicture = true;
                            //     }
                            // }                   
                        }
                    }
                }

            }

            

            if(!empty($newFileArray) && $morePhotoEmpty && $detailEmpty)
                $newFileArray["del"] = "Y";

            // if($deletePreviewPicture)
            //     $newFileArray["del"] = "Y";


            if(!$saveFromList)
                $arFields["PREVIEW_PICTURE"] = $newFileArray;

        }

       
    }

    public static function GetMinMaxPrice($ID, $IBLOCK_ID)
    {

        if(!Loader::includeModule('currency'))
            return;

        if(!Loader::includeModule('catalog'))
            return;

        if(!Loader::includeModule('iblock'))
            return;

        $ElementID = $ID;
        $IBlockID = $IBLOCK_ID;

        $offersIBlockID = false;
        $offersPropertyID = false;

        $DefaultCurrency = CCurrency::GetBaseCurrency();
        $arCatalog = CCatalog::GetByID($IBlockID);



        if(!substr_count($arCatalog["IBLOCK_TYPE_ID"], "concept_phoenix"))
            return;

        $arIBlock = CIBlock::GetByID($IBlockID);
        $arIBlock = $arIBlock->GetNext();

        if(!substr_count($arIBlock["CODE"], "concept_phoenix_catalog"))
            return;

        
    
        $arProductID = Array();

        if($ElementID > 0)
        {   

            if($arCatalog["OFFERS"] == "Y")
            {
                $rsLinkProperty = CIBlockElement::GetProperty(
                    $IBlockID,
                    $ElementID,
                    "sort",
                    "asc",
                    array("ID" => $arCatalog["SKU_PROPERTY_ID"])
                );

                $arLinkProperty = $rsLinkProperty->GetNext();

                $ElementID = $arLinkProperty["VALUE"];
                $IBlockID = $arLinkProperty["LINK_IBLOCK_ID"];

                $offersIBlockID = $arCatalog["IBLOCK_ID"];
                $offersPropertyID = $arCatalog["SKU_PROPERTY_ID"];

            }
            elseif($arCatalog["OFFERS_IBLOCK_ID"] > 0)
            {

                $offersIBlockID = $arCatalog["OFFERS_IBLOCK_ID"];
                $offersPropertyID = $arCatalog["OFFERS_PROPERTY_ID"];
            }



            $minPrice = 0;
            $maxPrice = 0;

            $price = 0;

            if($offersIBlockID != false)
            {
                $rsOffers = CIBlockElement::GetList(
                    array(),
                    array(
                        "IBLOCK_ID" => $offersIBlockID,
                        "PROPERTY_".$offersPropertyID => $ElementID,
                        "ACTIVE" => "Y"
                    ),
                    false,
                    false,
                    array("ID")
                );

                while($arOffer = $rsOffers->GetNext())
                    $arProductID[] = $arOffer["ID"];
            }



            if(!empty($arProductID))
            {

                $rsPrices = CPrice::GetList(
                    array(),
                    array(
                        "PRODUCT_ID" => $arProductID,
                        //"BASE" => "Y"
                    )
                );
            }
            else
            {
                $rsPrices = CPrice::GetList(
                    array(),
                    array(
                        "PRODUCT_ID" => $ElementID,
                        //"BASE" => "Y"
                    )
                );
            }

            $arPrices = Array();
            $arPricesBase = Array();

            while($arPrice = $rsPrices->GetNext())
            {

                if($DefaultCurrency != $arPrice['CURRENCY'])
                    $arPrice["PRICE"] = CCurrencyRates::ConvertCurrency($arPrice["PRICE"], $arPrice["CURRENCY"], $DefaultCurrency);

                if($arPrice["BASE"] == "Y")
                    $arPricesBase[] = $arPrice["PRICE"];
                else
                    $arPrices[] = $arPrice["PRICE"];
                
            }

            if(!empty($arPrices))
            {
                sort($arPrices);

                $minPrice = reset($arPrices);
                $maxPrice = end($arPrices);
            }

            if(!empty($arPricesBase))
            {
                sort($arPricesBase);

                $minPrice = reset($arPricesBase);
                $maxPrice = end($arPricesBase);
            }

            CIBlockElement::SetPropertyValuesEx(
                $ElementID,
                $IBlockID,
                array(
                    "MINIMUM_PRICE" => $minPrice,
                    "MAXIMUM_PRICE" => $maxPrice,
                )
            );

        }
        
    }


    public static function GetSortPrice1($arFields)
    {
        /*if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog") || !CModule::IncludeModule('currency'))
            return;

        CPhoenixShop::GetMinMaxPrice($arFields["ID"], $arFields["IBLOCK_ID"]);*/
    }

    public static function GetSortPrice2($ID, $arFields)
    {
        /*if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog") || !CModule::IncludeModule('currency'))
            return;

        $res = CIBlockElement::GetByID($arFields["PRODUCT_ID"]);

        if($ar_res = $res->GetNext())
            CPhoenixShop::GetMinMaxPrice($ar_res["ID"], $ar_res["IBLOCK_ID"]);*/
    }

    public static function getOptimalPrice($productID, $quantity, $arUserGroups, $renewal, $arPrices, $siteID, $arDiscountCoupons)
    {
        global $PHOENIX_TEMPLATE_ARRAY;


        $res = false;
        $siteID = ($siteID)?$siteID : SITE_ID;

    
        $rsTemplates = CSite::GetTemplateList($siteID);

        while($arTemplate = $rsTemplates->Fetch())
        { 
            if(substr_count($arTemplate["TEMPLATE"], "concept_phoenix_".$siteID) > 0)
                $res = true;
        }
        
        if(!$res)
            return true;


        if( (defined("ADMIN_SECTION") && ADMIN_SECTION === true))
            return true;

        


        CPhoenix::phoenixOptionsValues($siteID, array(
            "start",
            "catalog",
            "region"
        ));



        if(self::$handlerDisallow)
            return true;
       
        $newPriceList = array();
        $rsPrice = \Bitrix\Catalog\PriceTable::getList(array(
            'filter' => array('PRODUCT_ID'=>$productID)
        ));

        $quantity = floatval($quantity);
        $newPriceList = array();

        while($arPrice=$rsPrice->fetch())
        {
            $addNewPriceListFlag = false;


            if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_CAN_BUY"][$arPrice["CATALOG_GROUP_ID"]]))
            {
                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_CAN_BUY"][$arPrice["CATALOG_GROUP_ID"]] == $arPrice["CATALOG_GROUP_ID"])
                {

                    if($arPrice['QUANTITY_FROM'] || $arPrice['QUANTITY_TO'])
                    {
                       
                        if(!$arPrice['QUANTITY_FROM'])
                            $arPrice['QUANTITY_FROM'] = 0;
                        
                        if(!$arPrice['QUANTITY_TO'])
                        {
                            if($quantity >= floatval($arPrice['QUANTITY_FROM']))
                                $addNewPriceListFlag = true;

                        }
                        else
                        {

                            if($quantity >= floatval($arPrice['QUANTITY_FROM']) && $quantity < floatval($arPrice['QUANTITY_TO']))
                                $addNewPriceListFlag = true;
                                
                        }

                    }
                    else
                    {
                        $addNewPriceListFlag = true;
                    }

                    if($addNewPriceListFlag)
                    {

                        $newPriceList[] = array(
                            "ID"=>$arPrice["ID"],
                            "PRICE"=>$arPrice["PRICE"],
                            "CURRENCY"=>$arPrice["CURRENCY"],
                            "CATALOG_GROUP_ID"=>$arPrice["CATALOG_GROUP_ID"],
                        );
                    }
                    
                }
            }
        };

        

        self::$handlerDisallow = true;

        $result = \CCatalogProduct::GetOptimalPrice(
            $productID,
            $quantity,
            $arUserGroups,
            $renewal,
            $newPriceList,
            $siteID,
            $arDiscountCoupons);


        self::$handlerDisallow = false;



        return $result;
    } 

}
?>