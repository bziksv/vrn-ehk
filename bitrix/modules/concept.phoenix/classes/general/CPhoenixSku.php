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

class CPhoenixSku{

    public static function getHIBlockOptions($return = false)
    {
        global $PHOENIX_TEMPLATE_ARRAY;

        if(empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]))
        {
            
            $skuDBList = array();

            if ( CModule::IncludeModule('highloadblock') )
            {
                global $DB;

                $DBList = array();

                $strSql = "SELECT * FROM b_hlblock_entity";
                $res = $DB->Query($strSql, false, $err_mess.__LINE__);

                if($res->SelectedRowsCount() > 0)
                {

                    while($ar_result = $res->GetNext())
                    {
                        $DBList[] = $ar_result;
                    }
                }

                if(!empty($DBList))
                {
                    foreach ($DBList as $DBListValue)
                    {
                        $hlblock = HL\HighloadBlockTable::getById( $DBListValue["ID"] )->fetch();
                        $entity = HL\HighloadBlockTable::compileEntity( $hlblock );
                        $entity_data_class = $entity->getDataClass();


                        $hlDataClassResult = $entity_data_class::getList(array(
                            /*"order" => array("UF_SORT" => "ASC"),*/
                            "filter" => array(),
                        ));

                        while ($hlDataRes = $hlDataClassResult->fetch()) 
                        {
                            $skuDBList[$hlblock["TABLE_NAME"]]["TABLE_NAME"] = $hlblock["TABLE_NAME"];

                            if(isset($hlDataRes["UF_SORT"]))
                                $skuDBList[$hlblock["TABLE_NAME"]]["VALUES"][$hlDataRes["ID"]]["SORT"] = $hlDataRes["UF_SORT"];

                            if(isset($hlDataRes["ID"]))
                                $skuDBList[$hlblock["TABLE_NAME"]]["VALUES"][$hlDataRes["ID"]]["ID"] = $hlDataRes["ID"];

                            if(isset($hlDataRes["UF_NAME"]))
                                $skuDBList[$hlblock["TABLE_NAME"]]["VALUES"][$hlDataRes["ID"]]["NAME"] = $hlDataRes["UF_NAME"];

                            if(isset($hlDataRes["UF_XML_ID"]))
                            {
                                $skuDBList[$hlblock["TABLE_NAME"]]["VALUES"][$hlDataRes["ID"]]["XML_ID"] = $hlDataRes["UF_XML_ID"];
                                $skuDBList[$hlblock["TABLE_NAME"]]["VALUE_NAME"][$hlDataRes["UF_XML_ID"]] = $hlDataRes["UF_NAME"];
                            }


                            if(isset($hlDataRes["UF_COLOR"]))
                                $skuDBList[$hlblock["TABLE_NAME"]]["VALUES"][$hlDataRes["ID"]]["COLOR"] = $hlDataRes["UF_COLOR"];



                            if(isset($hlDataRes["UF_PICT"]) && $hlDataRes["UF_PICT"] > 0)
                            {

                                $resImage = CFile::ResizeImageGet($hlDataRes["UF_PICT"], array('width'=>260, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, 85);

                                $skuDBList[$hlblock["TABLE_NAME"]]["VALUES"][$hlDataRes["ID"]]["PICT"]["BIG"] = $resImage['src'];

                                $resImage = CFile::ResizeImageGet($hlDataRes["UF_PICT"], array('width'=>40, 'height'=>40), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, 85);

                                $skuDBList[$hlblock["TABLE_NAME"]]["VALUES"][$hlDataRes["ID"]]["PICT"]["SMALL"] = $resImage['src'];

                                unset($resImage);
                            }

                            if(isset($hlDataRes["UF_PICT_SEC"]) && $hlDataRes["UF_PICT_SEC"] > 0)
                            {
                                $resImage = CFile::ResizeImageGet($hlDataRes["UF_PICT_SEC"], array('width'=>260, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, 85);

                                $skuDBList[$hlblock["TABLE_NAME"]]["VALUES"][$hlDataRes["ID"]]["PICT_SEC"]["BIG"] = $resImage['src'];

                                $resImage = CFile::ResizeImageGet($hlDataRes["UF_PICT_SEC"], array('width'=>260, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, 85);
                                $skuDBList[$hlblock["TABLE_NAME"]]["VALUES"][$hlDataRes["ID"]]["PICT_SEC"]["SMALL"] = $resImage['src'];

                                unset($resImage);
                            }

                            
                        }

                    }

                    $skuDBListXML = array();


                    if(!empty($skuDBList))
                    {

                        foreach ($skuDBList as $key => $value){

                            $newSort = array();

                            foreach ($value["VALUES"] as $keyItem => $item){

                                if(isset($item["SORT"]))
                                    $sort = $item["SORT"];
                                
                                if(!strlen($sort))
                                    $sort = 500;


                                $newSort[$keyItem] = $sort;
                                unset($sort);
                            }

                            asort($newSort);

                            foreach ($value["VALUES"] as $keyItem => $item){
                                $newSort[$keyItem] = $item;
                            }


                            $skuDBList[$key]["VALUES"] = $newSort;

                        }
                        unset($newSort);


                        foreach ($skuDBList as $key => $value){
                            foreach ($value["VALUES"] as $keyItem => $item){
                                $skuDBListXML[$key]["VALUES"][$item["XML_ID"]] = $item;
                            }
                        }

                    }
                }

            }

            
            $PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"] = $skuDBList;
            $PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST_XML"] = $skuDBListXML;
        }

        if($return)
        {
            return array(
                'SKU_PROP_LIST'=> $skuDBList,
                'SKU_PROP_LIST_XML'=> $skuDBListXML
            );
        }

    }

    public static function getPropHIBlockOptions($arSKUPropList)
    {
        if ( CModule::IncludeModule('highloadblock') )
        {
            global $PHOENIX_TEMPLATE_ARRAY;

            if(!empty($arSKUPropList))
            {
                CPhoenixSku::getHIBlockOptions();
                foreach ($arSKUPropList as $skuKey => $skuValue)
                {

                    if( isset($skuValue['USER_TYPE_SETTINGS']['TABLE_NAME']) && $skuValue["USER_TYPE"] == "directory")
                    {
                        if(!empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]))
                        {

                            foreach ($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"] as $skuDBListValue)
                            {

                                if($skuDBListValue["TABLE_NAME"] == $skuValue['USER_TYPE_SETTINGS']['TABLE_NAME'])
                                {
                                    if(!empty($skuValue["VALUES"]))
                                    {
                                        foreach ($skuValue["VALUES"] as $keyValues => $valueValues)
                                        {
                                            if($skuDBListValue["VALUES"][$keyValues])
                                            {
                                               $arSKUPropList[$skuKey]["VALUES"][$keyValues] = $skuDBListValue["VALUES"][$keyValues]; 
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $arSKUPropList[$skuKey]["VALUES"] = array();
                                        $arSKUPropList[$skuKey]["VALUES"] = $skuDBListValue["VALUES"];
                                        $arSKUPropList[$skuKey]["VALUES"][0] = array(
                                            "ID" => 0,
                                            "NAME" => "-",
                                            "SORT" => 9999,
                                            "NA" => 1
                                        );
                                    }
                                    
                                }

                            }
                        }
                    }
                    
                }

            }
            
        }

        return $arSKUPropList;
    }

    public static function getSKUCodes( $arSkuProps )
    {

        $arSKUPropCodes = array();

        if( !empty($arSkuProps) )
        {
            foreach ($arSkuProps as $skuValue)
            {
                $arSKUPropCodes[] = $skuValue["CODE"];
            }
        }
        

        return $arSKUPropCodes;
    }

    public static function getSkuPropFields( $iBlockCODE, $offerTreeProps, $arOffers, $arSkuProps)
    {
        if(!Loader::includeModule('iblock'))
            return;

        $arSKUPropCodes = self::getSKUCodes($arSkuProps);

        $skuPropList = array();

        if( strlen($iBlockCODE) )
        {

            $properties = CIBlockProperty::GetList(Array("sort"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_CODE"=> $iBlockCODE));

            while($prop_fields = $properties->GetNext())
            {
                if(strlen($prop_fields["CODE"]) && !empty($offerTreeProps) && !empty($arSKUPropCodes))
                {

                    $code = (strlen($prop_fields["CODE"])>0?$prop_fields["CODE"]:$prop_fields["ID"]);
                    if(!in_array($code, $offerTreeProps) || in_array($code, $arSKUPropCodes))
                        continue;


                    if($prop_fields["PROPERTY_TYPE"]=="S" && $prop_fields["MULTIPLE"] == "N")
                    {

                        $skuPropList[$code] = array(
                            "ID" => $prop_fields["ID"],
                            "NAME" => $prop_fields["NAME"],
                            "CODE" => $prop_fields["CODE"],
                            "LIST_TYPE" => $prop_fields["LIST_TYPE"],
                            "SORT" => $prop_fields["SORT"],
                            "PROPERTY_TYPE" => $prop_fields["PROPERTY_TYPE"],
                            "SHOW_MODE" => "TEXT",

                        );
                    }
                }
                
            }
        }

        if( !empty($arOffers) )
        {
            $skuPropList = self::getValuesSkuPropFields( $arOffers, $skuPropList );
        }
        

        return $skuPropList;
    }

    public static function getValuesSkuPropFields( $arOffers, $skuPropList )
    {
        if( !empty($arOffers) )
        {
            foreach ($arOffers as $offer)
            {

                if( !empty($offer["PROPERTIES"]) )
                {
                    foreach ($offer["PROPERTIES"] as $code => $prop)
                    {

                        if( $skuPropList[$code]["CODE"] == $code)
                        {
                            if( !isset($skuPropList[$code]["VALUES"][0]) )
                            {
                                $skuPropList[$code]["VALUES"][0] = array(
                                    "ID" => 0,
                                    "NAME" => "-",
                                    "NA" => 1,
                                    "SORT" => 999
                                );
                            }

                            if( strlen($prop["VALUE"]) )
                            {
                                $mdValue = md5($prop["VALUE"]);
                                $skuPropList[$code]["VALUES"][$mdValue] = array(
                                    "ID" => $mdValue,
                                    "NAME" => $prop["VALUE"]
                                );
                            }
                        }
                    }
                }
                
            }
        }

        return $skuPropList;
    }

    public static function getPhotos( $arPhotos = array(), $quality = 85, $arWaterMark=array(), $arDesc = array(), $default_desc = "", $haveOffers = false)
    {
        $arImages = array();
        

        if( !empty($arPhotos) )
        {

            foreach ($arPhotos as $k => $value)
            {
                $arImage = array();

                $arImage["BIG"] = CFile::ResizeImageGet($value, array('width'=>1200, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arWaterMark, false, $quality);

                $arImage["MIDDLE"] = CFile::ResizeImageGet($value, array('width'=>511, 'height'=>500), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $quality);

                $arImage["SMALL"] = CFile::ResizeImageGet($value, array('width'=>160, 'height'=>120), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $quality);



                $arImage["ID"] = $value;

                $arImage["DESC"] = "";

                if(strlen($arDesc[$k]))
                    $arImage["DESC"] = $arDesc[$k];
                else
                    $arImage["DESC"] = $default_desc;

                $arImage["DESC"] = CPhoenix::prepareText($arImage["DESC"] );

                $arImage["BIG"] = array_change_key_case($arImage["BIG"], CASE_UPPER);
                $arImage["MIDDLE"] = array_change_key_case($arImage["MIDDLE"], CASE_UPPER);
                $arImage["SMALL"] = array_change_key_case($arImage["SMALL"], CASE_UPPER);


                $arImages[] = $arImage;

            }
        }

        else
        {
            $arImages=array(
                  array(
                        "BIG" =>  Array(
                              "SRC" => '/bitrix/images/concept.phoenix/ufo.png',
                        ),
                        "MIDDLE" =>  Array(
                              "SRC" => '/bitrix/images/concept.phoenix/ufo.png',
                        ),
                        "SMALL" =>  Array(
                              "SRC" => '/bitrix/images/concept.phoenix/ufo.png',
                        ),
                        "DEFAULT"=>"Y"
                  )
            );
        }


        return $arImages;
    }

    public static function getPhotosId( $arPhotos, $quality )
    {
        $IDs = array();

        if( !empty($arPhotos) )
        {
            foreach ($arPhotos as $value)
            {
                $IDs[] = $value;
            }
        }

        return $IDs;
    }

    public static function getQuantityForManyFew()
    {

        global $PHOENIX_TEMPLATE_ARRAY;

        $quantity["MANY"] = NULL;
        $quantity["FEW"] = NULL;

        if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["VALUE"]) )
            $quantity["MANY"] = CPhoenix::fixedNumber($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["VALUE"]);

        if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["VALUE"]) )
            $quantity["FEW"] = CPhoenix::fixedNumber($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["VALUE"]);
        

        return $quantity;
    }

    public static function getDescQuantity($maxQuantity, $measure = "", $storeIsset = false, $arParams=array())
    {
        global $PHOENIX_TEMPLATE_ARRAY;


        $desc_quantity = array(
            'TEXT'=>'',
            'QUANTITY_STYLE'=>'without-quantity',
            'QUANTITY'=>'',
            'QUANTITY_VALUE'=>'',
            'QUANTITY_WITH_TEXT'=>'',
            'HTML'=>''
        );

        $maxQuantity = CPhoenix::fixedNumber($maxQuantity);

        if($maxQuantity < 0)
            $maxQuantity = 0;

        $desc_quantity["QUANTITY_VALUE"] = $maxQuantity;

        if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_VIEW"]["VALUE"] == "visible" && $maxQuantity != 0)
        {
            $desc_quantity["TEXT"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_NOEMPTY"];
            $desc_quantity["QUANTITY_STYLE"] = "with-quantity";
            $desc_quantity["QUANTITY"] = $maxQuantity;
            

            if(strlen($measure))
                $desc_quantity["QUANTITY"] .= " ".$measure;

            $desc_quantity["QUANTITY_FORMATED"] = $desc_quantity["QUANTITY"];


            $desc_quantity["QUANTITY_WITH_TEXT"] = $desc_quantity["TEXT"].":&nbsp;<span class='quantity'>".$desc_quantity["QUANTITY_FORMATED"]."</span>";
            
        }

        else
        {
            $quantity = self::getQuantityForManyFew();

            if($maxQuantity == 0)
            {
                $desc_quantity["TEXT"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_EMPTY"];
                $desc_quantity["QUANTITY_STYLE"] = " empty-quantity";


                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_VIEW"]["VALUE"] == "visible")
                {
                    $desc_quantity["QUANTITY_FORMATED"] = $maxQuantity;
            
                    if(strlen($measure))
                        $desc_quantity["QUANTITY_FORMATED"] .= " ".$measure;
                }
                

            }

            elseif($maxQuantity > 0)
            {
                if($maxQuantity >= $quantity["MANY"])
                {
                    $desc_quantity["TEXT"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_2"];
                    $desc_quantity["QUANTITY_STYLE"] .= " many";
                }

                if($maxQuantity <= $quantity["FEW"])
                {
                    $desc_quantity["TEXT"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["DESCRIPTION_2"];
                    $desc_quantity["QUANTITY_STYLE"] .= " few";
                }

                if( $maxQuantity > $quantity["FEW"] && $maxQuantity < $quantity["MANY"] )
                {
                    $desc_quantity["TEXT"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_NOEMPTY"];
                }
            }
        }

        if($maxQuantity > 0 && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_BLOCK_VIEW"]["VALUE"] == "popup" && $storeIsset)
        {
            $desc_quantity["QUANTITY_STYLE"] .= " show-popup-block-store";
        }


       

        if(isset($arParams["TEXT"])||isset($arParams["QUANTITY_STYLE"]))
        {
            if(isset($arParams["TEXT"]))
                $desc_quantity['TEXT'] = $arParams["TEXT"];

            if(isset($arParams["QUANTITY_STYLE"]))
                $desc_quantity['QUANTITY_STYLE'] = $arParams["QUANTITY_STYLE"];
        }
        else
        {
            if(strlen($desc_quantity["QUANTITY"]))
                $quantityHtml = ': <span class="quantity bold">'.$desc_quantity["QUANTITY"].'</span>';
        }
        

        $desc_quantity["HTML"] = '<div class="detail-available '.$desc_quantity["QUANTITY_STYLE"].'"><span class="text">'.$desc_quantity["TEXT"].'</span>'.$quantityHtml.'</div>';


        return $desc_quantity;
    }

    public static function getUserPropsForTree($treeProps, $arSKUPropIDs, $siteID)
    {
        $skuPropList = array();

        if(!Loader::includeModule('iblock'))
            return;

        if(!empty($treeProps))
        {   
           

            $properties = CIBlockProperty::GetList(Array("sort"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_CODE"=>"concept_phoenix_catalog_offers_".$siteID));
        
            while($prop_fields = $properties->GetNext())
            {

                $code = (strlen($prop_fields["CODE"])>0?$prop_fields["CODE"]:$prop_fields["ID"]);

              

                if(!in_array($code, $treeProps) || in_array($code, $arSKUPropIDs))
                    continue;


                if(($prop_fields["PROPERTY_TYPE"]=="S" || $prop_fields["PROPERTY_TYPE"]=="N") && $prop_fields["MULTIPLE"] == "N")
                {
                    $skuPropList[$code] = array(
                        "ID" => $prop_fields["ID"],
                        "NAME" => $prop_fields["NAME"],
                        "CODE" => $prop_fields["CODE"],
                        "LIST_TYPE" => $prop_fields["LIST_TYPE"],
                        "SORT" => $prop_fields["SORT"],
                        "PROPERTY_TYPE" => $prop_fields["PROPERTY_TYPE"],
                        "SHOW_MODE" => "TEXT",
                        "VALUES" => array()

                    );
                }
                    
         
                
            }

        }


        return $skuPropList;
    }

}
?>