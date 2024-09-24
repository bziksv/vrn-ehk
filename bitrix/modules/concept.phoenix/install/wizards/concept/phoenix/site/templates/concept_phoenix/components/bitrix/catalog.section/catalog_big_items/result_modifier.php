<?
use Bitrix\Main\Type\Collection;
use Bitrix\Currency\CurrencyTable;
use Bitrix\Iblock;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>

<?
global $PHOENIX_TEMPLATE_ARRAY;
CPhoenixSku::getHIBlockOptions();

if( !empty($arResult["ITEMS"]) )
{
        
    if(strlen($arParams['CURRENCY_ID'])<=0)
        $arParams['CURRENCY_ID'] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'];

    
    $arrItems = array();
    $arrItemsOffers = array();
    $getProperties = false;
    $getPropertiesOffers = false;


    foreach ($arResult["ITEMS"] as $arItem)
    {

          if(empty($arItem["OFFERS"]))
          {
                if(empty($arItem["OFFERS"][0]["PROPERTIES"]))
                      $getPropertiesOffers = true;
          }

          if(empty($arItem["PROPERTIES"]))
                $getProperties = true;
    }

    
    foreach ($arResult["ITEMS"] as $arItem)
    {
          $arrItems[] = $arItem["ID"];

          if(!empty($arItem['OFFERS']) && $PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] == "Y")
          {
                foreach ($arItem['OFFERS'] as $arOffers)
                {
                   $arrItemsOffers[] = $arOffers["ID"];
                }
          }
    }


    if(!empty($arrItems) && $getProperties)
    {
        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]>0)
        {

            $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ID" => $arrItems);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false);



            $arProperties = array();
            $fields = array();

            while($ob = $res->GetNextElement())
            {
                $fields = $ob->GetFields();
                $arProperties[$fields["ID"]] = $ob->GetProperties();
            }
        }

    }


    if(!empty($arrItemsOffers) && $getPropertiesOffers)
    {

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"]>0)
        {
            $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"], "ID" => $arrItemsOffers);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false);


            $arPropertiesOffers = array();
            $fields = array();

            while($ob = $res->GetNextElement())
            {
                $fields = $ob->GetFields();
                $arPropertiesOffers[$fields["ID"]] = $ob->GetProperties();
            }
        }

    }

    if($getProperties || $getPropertiesOffers)
    {
          foreach ($arResult["ITEMS"] as $itemKey => $arItem)
          {

                if($getProperties)
                      $arResult["ITEMS"][$itemKey]["PROPERTIES"] = $arProperties[$arItem["ID"]];


                if( !empty($arItem['OFFERS']) && $PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] == "Y" && $getPropertiesOffers)
                {

                      foreach ($arItem['OFFERS'] as $offerKey => $value)
                      {
                            $arResult["ITEMS"][$itemKey]['OFFERS'][$offerKey]["PROPERTIES"] = $arPropertiesOffers[$value["ID"]];
                      }
                }
          }
    }


    if(!empty($arResult["ITEMS"]))
    {
        $arNewItemsList = array();

        $arParams["WATERMARK"] = Array();

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["WATERMARK"]["VALUE"] > 0)
        {

            $arParams["WATERMARK"] = Array(
                array(
                    "name" => "watermark",
                    "position" => "center",
                    "type" => "image",
                    "size" => "real",
                    "file" => $_SERVER["DOCUMENT_ROOT"].CFile::GetPath($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["WATERMARK"]["VALUE"]),
                    "fill" => "exact",
                )
            );
        }

        $arProductsAmountByStore = array();

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["SHOW_RESIDUAL"]["VALUE"]["ACTIVE"] === "Y" && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"]))
            $arProductsAmountByStore = CPhoenix::getProductsAmountByStore($arResult['ITEMS']);

    }
}


$arResult["rating"] = array();

if(!empty($arResult["ITEMS"]) && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y")
{
    
    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_REVIEW"]["VALUE"]["ACTIVE"] == "Y")
    {
        $arResult["RATING_VIEW"] = "full";
        $arResult["ITEMS_ID"] = array();
        foreach ($arResult["ITEMS"] as $key => $arItem){
           $arResult["ITEMS_ID"][] = $arItem["ID"];
        }
    }
    else
    {
        $arResult["RATING_VIEW"] = "simple";

        foreach ($arResult["ITEMS"] as $key => $arItem){
           $arResult["rating"][$arItem["ID"]] = (strlen($arItem["PROPERTIES"]["rating"]["VALUE"]))?round($arItem["PROPERTIES"]["rating"]["VALUE"]):"0";
        }
    }
    

    
}


if(!empty($arResult["ITEMS"]))
{

    $arNewItem = array();

    foreach ($arResult["ITEMS"] as $keyItem => $arItem)
    {
        $arItem['OBJ_ID'] = $arParams['OBJ_NAME'].'_'.$this->GetEditAreaId($arItem['ID']).'_construct';

        $arNewItem[$keyItem] = CPhoenix::parseProduct($arItem, array(

                'BLOCK_SLIDER'=>'Y',
                'PRODUCTS_AMOUNT'=>$arProductsAmountByStore,
                'VIEW'=>$arResult['VIEW'],
                'USE_PRICE_COUNT'=>$arParams['USE_PRICE_COUNT'],
                'WATERMARK'=>$arParams['WATERMARK']
            )
        );

        $arItem["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"] = ($arItem["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"]=="")?"form":$arItem["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"];

        $arNewItem[$keyItem]["BTN"] = array(
            "BTN_NAME" => $arItem["PROPERTIES"]["BTN_NAME"]["VALUE"],
            "~BTN_NAME" => $arItem["PROPERTIES"]["BTN_NAME"]["~VALUE"],
            "ACTION" => $arItem["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"],
            "FORM_ID" => $arItem["PROPERTIES"]["BTN_FORM"]["VALUE"],
            "POPUP_ID" => $arItem["PROPERTIES"]["BTN_POPUP"]["VALUE"],
            "PRODUCT_ID" => ($arItem["PROPERTIES"]["BTN_OFFER_ID"]["VALUE"])?$arItem["PROPERTIES"]["BTN_OFFER_ID"]["VALUE"]:$arItem["PROPERTIES"]["BTN_PRODUCT_ID"]["VALUE"],
            "VIEW" => $arItem["PROPERTIES"]["BTN_VIEW"]["VALUE_XML_ID"],
            "BG_COLOR" => $arItem["PROPERTIES"]["BTN_BG_COLOR"]["VALUE"],
            "QUIZ_ID" => $arItem["PROPERTIES"]["BTN_QUIZ_ID"]["VALUE"],
            "LAND_ID" => $arItem["PROPERTIES"]["BTN_LAND"]["VALUE"],
            "URL" => $arItem["PROPERTIES"]["BTN_URL"]["VALUE"],
            "TARGET_BLANK" => $arItem["PROPERTIES"]["BTN_TARGET_BLANK"]["VALUE"],
            "HEADER" => strip_tags($arItem["~NAME"]),
            "ONCLICK" => $arItem["PROPERTIES"]["BTN_ONCLICK"]["VALUE"]
        );


        $arNewItem[$keyItem]["MODAL_WINDOWS"] = 
        $arNewItem[$keyItem]["FORMS"] =
        $arNewItem[$keyItem]["QUIZS"] = array();


        if(!empty($arItem["PROPERTIES"]["MODAL_WINDOWS"]["VALUE"]))
        {
            $arElements = Array();

            $arSelect = Array("ID", "NAME");
            $arFilter = Array("ID"=>$arItem["PROPERTIES"]["MODAL_WINDOWS"]["VALUE"], "ACTIVE"=>"Y");

            $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
            while($ob = $res->GetNextElement())
            { 
                $arElem = $ob->GetFields();  
                $arElem["PROPERTIES"] = $ob->GetProperties();
                
                $arElements[] = $arElem;
            }

            $arNewItem[$keyItem]["MODAL_WINDOWS"] = $arElements;
        }


        if(!empty($arItem["PROPERTIES"]["FORMS"]["VALUE"]))
        {
            $arElements = Array();

            $arSelect = Array("ID", "NAME");
            $arFilter = Array("ID"=>$arItem["PROPERTIES"]["FORMS"]["VALUE"], "ACTIVE"=>"Y");

            $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
            while($ob = $res->GetNextElement())
            { 
                $arElem = $ob->GetFields();  
                $arElem["PROPERTIES"] = $ob->GetProperties();
                
                $arElements[] = $arElem;
            }

            $arNewItem[$keyItem]["FORMS"] = $arElements;
        }


        if(IsModuleInstalled("concept.quiz"))
        {

            if(!empty($arItem["PROPERTIES"]["QUIZS"]["VALUE"]))
            {
                $arElements = Array();

                $arSelect = Array("ID", "NAME");
                $arFilter = Array("ID"=>$arItem["PROPERTIES"]["QUIZS"]["VALUE"], "ACTIVE"=>"Y");

                $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
                while($ob = $res->GetNextElement())
                { 
                    $arElem = $ob->GetFields();  
                    $arElem["PROPERTIES"] = $ob->GetProperties();
                    
                    $arElements[] = $arElem;
                }

                $arNewItem[$keyItem]["QUIZS"] = $arElements;
            }
        }

    }

    $arResult["ITEMS"] = $arNewItem;

    $arResult["ITEMS_COUNT"] = (!empty($arResult['ITEMS']))?count($arResult['ITEMS']):0;

    // first item
        foreach ($arResult["ITEMS"] as $keyItem => $arItem)
        {
            if($arItem["HAVEOFFERS"])
            {
                $arResult['ITEMS'][$keyItem]["FIRST_ITEM"] = $arItem['OFFERS'][$arItem["OFFER_ID_SELECTED"]];
            }
            else
            {
                $arResult['ITEMS'][$keyItem]["FIRST_ITEM"] = $arItem;
            }
        }
}

$cp = $this->__component;
 
if (is_object($cp))
{
    $cp->SetResultCacheKeys(array('ITEMS_ID', 'rating', 'ITEMS'));
}