<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?
global $PHOENIX_TEMPLATE_ARRAY;

$arResult['ZOOM_ON'] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['ZOOM_ON']['VALUE']['ACTIVE'] == "Y" ? 'zoom' : '';



$arSelect = Array("ID", "UF_CHARS_VIEW","UF_ZOOM_ON");
$arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "GLOBAL_ACTIVE"=>"Y", "ID"=>$arResult["IBLOCK_SECTION_ID"]);
$db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, $arSelect);

if($ar_result = $db_list->GetNext())
{
    if(!$arResult['ZOOM_ON'])
        $arResult['ZOOM_ON'] = $ar_res["UF_ZOOM_ON"] ? 'zoom' : '';

    if($ar_result["UF_CHARS_VIEW"] > 0)
    {
        $arResult["UF_CHARS_VIEW_ENUM"] = CUserFieldEnum::GetList(array(), array(
        "ID" => $ar_result["UF_CHARS_VIEW"],
        ))->GetNext();
    }
    else
        $arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] = "col-one";
}

CPhoenixSku::getHIBlockOptions();

CPhoenix::SetElementProperties($arResult);

if(!empty($arResult["OFFERS"]))
{
    CPhoenix::SetElementOffersProperties($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"], $arResult);
}

$arResult["OBJ_ID"] = $this->GetEditAreaId($arResult['ID']).'_detail';


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
    $arProductsAmountByStore = CPhoenix::getProductsAmountByStore(array($arResult));



$arResult["PRODUCT"] = CPhoenix::parseProduct($arResult, 
    array(
        "DETAIL_PAGE"=>"Y",
        "PRODUCTS_AMOUNT"=>$arProductsAmountByStore,
        'USE_PRICE_COUNT'=>$arParams['USE_PRICE_COUNT'],
        'WATERMARK'=>$arParams["WATERMARK"]
    ));


if($arResult["PRODUCT"]["HAVEOFFERS"])
    $arResult["FIRST_ITEM"] = $arResult["PRODUCT"]["OFFERS"][$arResult["PRODUCT"]["OFFER_ID_SELECTED"]];
else
    $arResult["FIRST_ITEM"] = $arResult["PRODUCT"];


/*------------*/

    $arResult["SECTIONS_ID"] = array();
    $similarCategory = array();
    $advantages = array();


    if(!empty($arResult["PROPERTIES"]["SIMILAR_CATEGORY"]["VALUE"]) && $arResult["PROPERTIES"]["SIMILAR_CATEGORY_INHERIT"]["VALUE_XML_ID"] == "own")
        $similarCategory = $arResult["PROPERTIES"]["SIMILAR_CATEGORY"]["VALUE"];



    if(!empty($arResult["PROPERTIES"]["ADVANTAGES"]["VALUE"]) && $arResult["PROPERTIES"]["PARENT_ADV"]["VALUE_XML_ID"] == "own")
        $advantages = $arResult["PROPERTIES"]["ADVANTAGES"]["VALUE"];



    $parent_section_id = $arResult["IBLOCK_SECTION_ID"];

    if(in_array($parent_section_id, $arParams["NEW_GROUPS"]))
    {
        while($parent_section_id != 0)
        {
            $arSelect = Array("ID", "DETAIL_PICTURE", "IBLOCK_SECTION_ID", "UF_*");
            $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ID"=>$parent_section_id, "ACTIVE"=>"Y", "GLOBAL_ACTIVE"=>"Y");
            $db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, $arSelect);
            
            while($ar_res = $db_list->GetNext())
            {
              
                if($arResult["PROPERTIES"]["SIMILAR_CATEGORY_INHERIT"]["VALUE_XML_ID"] == "parent")
                {
                    if(empty($similarCategory) && !empty($ar_res["UF_SIMILAR_CATEGORY"]))
                        $similarCategory = $ar_res["UF_SIMILAR_CATEGORY"];
                }

                if($arResult["PROPERTIES"]["PARENT_ADV"]["VALUE_XML_ID"] == "parent")
                {
                    if(empty($advantages) && !empty($ar_res["UF_PHX_CTLG_ADV"]))
                        $advantages = $ar_res["UF_PHX_CTLG_ADV"];
                }



                if($ar_res["UF_DESC_FOR_PRICE"] && !isset($arResult["UF_DESC_FOR_PRICE"])){
                    $arResult["UF_DESC_FOR_PRICE"] = $ar_res["~UF_DESC_FOR_PRICE"];
                }

                if($ar_res["UF_TIT_FOR_QUANTITY"] && !isset($arResult["UF_TIT_FOR_QUANTITY"])){
                    $arResult["UF_TIT_FOR_QUANTITY"] = $ar_res["~UF_TIT_FOR_QUANTITY"];
                }

                if($ar_res["UF_COMM_FOR_QUANTITY"] && !isset($arResult["UF_TIT_FOR_QUANTITY"])){
                    $arResult["UF_COMM_FOR_QUANTITY"] = $ar_res["~UF_COMM_FOR_QUANTITY"];
                }

                if(strlen($ar_res["UF_PREVIEW_TEXT_POS"]) && !isset($arResult["UF_PREVIEW_TEXT_POS"])){

                    $arResult["UF_PREVIEW_TEXT_POS"] = CUserFieldEnum::GetList(array(), array(
                        "ID" => $ar_res["UF_PREVIEW_TEXT_POS"],
                    ))->GetNext();
                }

                $arResult["SECTIONS_ID"][] = $ar_res["ID"];
                $parent_section_id = $ar_res["IBLOCK_SECTION_ID"];

            }
            
        } 
    }

    if( strlen($arResult["PROPERTIES"]["TITLE_FOR_ACTUAL_PRICE"]["VALUE"]) )
        $arResult["DESC_FOR_ACTUAL_PRICE"] = $arResult["PROPERTIES"]["TITLE_FOR_ACTUAL_PRICE"]["~VALUE"];

    else if(strlen($arResult["UF_DESC_FOR_PRICE"]))
        $arResult["DESC_FOR_ACTUAL_PRICE"] = $arResult["UF_DESC_FOR_PRICE"];
    
    else
        $arResult["DESC_FOR_ACTUAL_PRICE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DESC_FOR_ACTUAL_PRICE"]["~VALUE"];


    

    if( strlen($arResult["PROPERTIES"]["TIT_FOR_QUANTITY"]["VALUE"]) )
        $arResult["TITLE_FOR_QUANTITY"] = $arResult["PROPERTIES"]["TIT_FOR_QUANTITY"]["~VALUE"];

    else if(strlen($arResult["UF_TIT_FOR_QUANTITY"]))
        $arResult["TITLE_FOR_QUANTITY"] = $arResult["UF_TIT_FOR_QUANTITY"];

    else
        $arResult["TITLE_FOR_QUANTITY"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TIT_FOR_QUANTITY"]["~VALUE"];



    $arResult["TITLE_FOR_QUANTITY_HINT"] = "";

    if( $arResult["PROPERTIES"]["COMMENT_HIDE"]["VALUE_XML_ID"] != "Y" )
    {

        if( strlen($arResult["PROPERTIES"]["COMMENT_FOR_QUANTITY"]["VALUE"]) )
            $arResult["TITLE_FOR_QUANTITY_HINT"] = $arResult["PROPERTIES"]["COMMENT_FOR_QUANTITY"]["VALUE"];

        else if( strlen($arResult["UF_COMM_FOR_QUANTITY"]) )
            $arResult["TITLE_FOR_QUANTITY_HINT"] = $arResult["UF_COMM_FOR_QUANTITY"];

        else
            $arResult["TITLE_FOR_QUANTITY_HINT"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["COMMENT_FOR_QUANTITY"]["VALUE"];
    }
    



    if( strlen($arResult["PROPERTIES"]["BRAND"]["VALUE"]) )
    {
        $arElements = Array();

        $arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT", "DETAIL_PAGE_URL");
        $arFilter = Array("ID"=>$arResult["PROPERTIES"]["BRAND"]["VALUE"], "ACTIVE"=>"Y");

        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
        while($ob = $res->GetNextElement())
        { 
            $arResult["BRAND"] = $ob->GetFields();  
        }

        if( strlen($arResult["BRAND"]["PREVIEW_PICTURE"]) && isset($arResult["BRAND"]["PREVIEW_PICTURE"]) && $arResult["BRAND"]["PREVIEW_PICTURE"] > 0 )
        {
            $arPicture = CFile::ResizeImageGet($arResult["BRAND"]["PREVIEW_PICTURE"], array('width'=>143, 'height'=>32), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

            $arResult["BRAND"]["PREVIEW_PICTURE_SRC"] = $arPicture["src"];

            unset($arPicture);

            $arPicture = CFile::ResizeImageGet($arResult["BRAND"]["PREVIEW_PICTURE"], array('width'=>286, 'height'=>64), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

            $arResult["BRAND"]["PREVIEW_PICTURE_SRC_XS"] = $arPicture["src"];

            unset($arPicture);
        }
        
    }


    if(!empty($arResult["PROPERTIES"]["MODAL_WINDOWS"]["VALUE"]))
    {
        $arElements = Array();

        $arSelect = Array("ID", "NAME");
        $arFilter = Array("ID"=>$arResult["PROPERTIES"]["MODAL_WINDOWS"]["VALUE"], "ACTIVE"=>"Y");

        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
        while($ob = $res->GetNextElement())
        { 
            $arElem = $ob->GetFields();  
            $arElem["PROPERTIES"] = $ob->GetProperties();
            
            $arElements[] = $arElem;
        }

        $arResult["MODAL_WINDOWS"] = $arElements;
    }


    if(!empty($arResult["PROPERTIES"]["FORMS"]["VALUE"]))
    {
        $arElements = Array();

        $arSelect = Array("ID", "NAME");
        $arFilter = Array("ID"=>$arResult["PROPERTIES"]["FORMS"]["VALUE"], "ACTIVE"=>"Y");

        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
        while($ob = $res->GetNextElement())
        { 
            $arElem = $ob->GetFields();  
            $arElem["PROPERTIES"] = $ob->GetProperties();
            
            $arElements[] = $arElem;
        }

        $arResult["FORMS"] = $arElements;
    }


    if(IsModuleInstalled("concept.quiz"))
    {

        if(!empty($arResult["PROPERTIES"]["QUIZS"]["VALUE"]))
        {
            $arElements = Array();
            $arSelect = Array("ID", "NAME");
            $arFilter = Array("ACTIVE"=>"Y", "ID"=>$arResult["PROPERTIES"]["QUIZS"]["VALUE"]);
            $db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, $arSelect);

            while ($arElements = $db_list->GetNext())
            {
                $arResult["QUIZS"][] = $arElements;
            }
        }
    }


    if(!empty($advantages))
    {
       
        $arElements = Array();

        $arSizes = Array(
            "small" => array(
                    "width" => 80,
                    "height" => 80
                ),
            "big" => array(
                    "width" => 200,
                    "height" => 200
                ),
        );

        $arSelect = Array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "IBLOCK_TYPE_ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_*");
        $arFilter = Array("ID"=>$advantages, "ACTIVE"=>"Y");

        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
        while($ob = $res->GetNextElement())
        { 
            $arElem = $ob->GetFields();  
            $arElem["PROPERTIES"] = $ob->GetProperties();

            if(!$arElem["PROPERTIES"]["SIZE"]["VALUE_XML_ID"])
                $arElem["PROPERTIES"]["SIZE"]["VALUE_XML_ID"] = "small";

           

            $arElem["PREVIEW_PICTURE_SRC"] = "";
            $file = array();


            if($arElem["PREVIEW_PICTURE"])
            {
                $file = CFile::ResizeImageGet($arElem["PREVIEW_PICTURE"], 
                    $arSizes[$arElem["PROPERTIES"]["SIZE"]["VALUE_XML_ID"]],
                    BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                $arElem["PREVIEW_PICTURE_SRC"] = $file["src"];
            }
            
            $arElements[] = $arElem;
        }
        
        $arResult["ADVANTAGES"] = $arElements;

    }



    //setProducts

    if($arResult["PROPERTIES"]["SHOW_BLOCK_SET_PRODUCT"]["VALUE"] == "Y")
    {
        if($arResult["CATALOG_TYPE"] == 2)
        {

            $currentSet = false;
            $allSets = CCatalogProductSet::getAllSetsByProduct($arResult['ID'], CCatalogProductSet::TYPE_SET);

            foreach ($allSets as &$oneSet)
            {
                if ($oneSet['ACTIVE'] == 'Y')
                {
                    $currentSet = $oneSet;
                    break;
                }
            }
            unset($oneSet, $allSets);


            Bitrix\Main\Type\Collection::sortByColumn($currentSet['ITEMS'], array('SORT' => SORT_ASC), '', null, true);



            $arSetItemsID = array();
            $productQuantity = array(
                $arResult['ID'] => 1
            );
            foreach ($currentSet['ITEMS'] as $index => $item)
            {
                $id = $item['ITEM_ID'];
                $arSetItemsID[] = $id;
                $productLink[$id] = $index;
                $productQuantity[$id] = $item['QUANTITY'];
                unset($id);
            }
            unset($index, $item);



            $ratioResult = Bitrix\Catalog\ProductTable::getCurrentRatioWithMeasure($arSetItemsID);

            foreach ($ratioResult as $ratioProduct => $ratioData)
            {
                $arResult['SET_ITEMS_RATIO'][$ratioProduct] = $ratioData['RATIO'];
                $ratioResult[$ratioProduct]["QUANTITY"] = $productQuantity[$ratioProduct] *= $ratioData['RATIO'];
            }
            unset($ratioProduct, $ratioData);


            $arrItems = array();


            $select = array(
                'ID',
                'CATALOG_TYPE'
            );
            $filter = array(
                'ID' => $arSetItemsID,
                'IBLOCK_LID' => SITE_ID,
                'ACTIVE_DATE' => 'Y',
                'ACTIVE' => 'Y',
                'CHECK_PERMISSIONS' => 'Y',
                'MIN_PERMISSION' => 'R'
            );

            $itemsIterator = CIBlockElement::GetList(
                array(),
                $filter,
                false,
                false,
                $select
            );
            while ($item = $itemsIterator->GetNext())
            {
                $arrItems[$item['ID']] = $item['ID'];
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
                // CPhoenix::getIblockIDs(array("concept_phoenix_catalog_offers_".SITE_ID));

                // if(empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]))
                //     CPhoenixSku::getHIBlockOptions();

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



            $arResult["SET_ITEMS"] = array();

            foreach ($currentSet['ITEMS'] as $key => $arItem)
            {

                $isOffer = isset($arOffer2Product[$arItem["ITEM_ID"]]);

                $arResult["SET_ITEMS"][$key]["ID"] = $arItem["ITEM_ID"];


                $arResult["SET_ITEMS"][$key]["MEASURE"] = $ratioResult[$arItem["ITEM_ID"]]["QUANTITY"]."&nbsp;".$ratioResult[$arItem["ITEM_ID"]]["MEASURE"]["SYMBOL"];

                if($isOffer)
                {
                    $productIdOfOrder = $arOffer2Product[$arItem["ITEM_ID"]];

                    
                    $arResult["SET_ITEMS"][$key]["NAME"] = strip_tags($arProductFields[$productIdOfOrder]["~NAME"]);


                    $arResult["SET_ITEMS"][$key]["IBLOCK_TYPE_ID"] = $arOffersFields[$arItem["ITEM_ID"]]["IBLOCK_TYPE_ID"];
                    $arResult["SET_ITEMS"][$key]["IBLOCK_SECTION_ID"] = $arOffersFields[$arItem["ITEM_ID"]]["IBLOCK_SECTION_ID"];
                    $arResult["SET_ITEMS"][$key]["IBLOCK_ID"] = $arOffersFields[$arItem["ITEM_ID"]]["IBLOCK_ID"];


                    $arResult["SET_ITEMS"][$key]["IS_OFFER"] = "Y";
                    $arResult["SET_ITEMS"][$key]["ID_MAIN"] = $productIdOfOrder;
                    $arResult["SET_ITEMS"][$key]["IBLOCK_TYPE_ID_MAIN"] = $arProductFields[$productIdOfOrder]["IBLOCK_TYPE_ID"];
                    $arResult["SET_ITEMS"][$key]["IBLOCK_SECTION_ID_MAIN"] = $arProductFields[$productIdOfOrder]["IBLOCK_SECTION_ID"];
                    $arResult["SET_ITEMS"][$key]["IBLOCK_ID_MAIN"] = $arProductFields[$productIdOfOrder]["IBLOCK_ID"];

                    $photo = 0;

                    if($arOffersFields[$arItem["ITEM_ID"]]["DETAIL_PICTURE"])
                        $photo = $arOffersFields[$arItem["ITEM_ID"]]["DETAIL_PICTURE"];
                    

                    else if(!empty($arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                        $photo = $arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];

                    else if($arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE"] != "Y")
                    {

                        if($arProductFields[$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"])
                            $photo = $arProductFields[$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["DETAIL_PICTURE"];
                        

                        else if(!empty($arProductFields[$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                            $photo = $arProductFields[$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["CML2_LINK"]["VALUE"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];
                    }



                    if($photo)
                    {
                        $img = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                        $arResult["SET_ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = $img["src"];

                        
                    }
                    else
                        $arResult["SET_ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.png";


                    $arResult["SET_ITEMS"][$key]["DETAIL_PAGE"] = $arOffersFields[$arItem["ITEM_ID"]]["DETAIL_PAGE_URL"]."?oID=".$arItem["ITEM_ID"];
                    /*$arResult["SET_ITEMS"][$key]["ARTICLE"] = strip_tags($arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"]["ARTICLE"]["~VALUE"]);*/

                    



                    if( !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"]) )
                    {

                        foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"] as $arOfferField)
                        {
                            if( $arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["USER_TYPE"] == "directory" && strlen($arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]) )
                            {

                                foreach ($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"] as $arSKUProp)
                                {

                                    if( $arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]['USER_TYPE_SETTINGS']['TABLE_NAME'] == $arSKUProp['TABLE_NAME'])
                                    {
                                        if(strlen($arSKUProp["VALUE_NAME"][$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]]))
                                        {

                                            $arResult["SET_ITEMS"][$key]["NAME_OFFERS"] .= $arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arSKUProp["VALUE_NAME"][$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]]."<br/>";
                                        }

                                    }
                                }
                            }
                            else if(strlen($arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]))
                            {
                                $arResult["SET_ITEMS"][$key]["NAME_OFFERS"] .= $arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arOffersFields[$arItem["ITEM_ID"]]["PROPERTIES"][$arOfferField]["VALUE"]."<br/>";
                            }

                        }

                        
                    }

                }

                else
                {
                    $arResult["SET_ITEMS"][$key]["NAME"] = strip_tags($arProductFields[$arItem["ITEM_ID"]]["~NAME"]);


                    $arResult["SET_ITEMS"][$key]["IBLOCK_TYPE_ID"] = $arProductFields[$arItem["ITEM_ID"]]["IBLOCK_TYPE_ID"];
                    $arResult["SET_ITEMS"][$key]["IBLOCK_SECTION_ID"] = $arProductFields[$arItem["ITEM_ID"]]["IBLOCK_SECTION_ID"];
                    $arResult["SET_ITEMS"][$key]["IBLOCK_ID"] = $arProductFields[$arItem["ITEM_ID"]]["IBLOCK_ID"];

                    $photo = 0;

                    if($arProductFields[$arItem["ITEM_ID"]]["DETAIL_PICTURE"])
                        $photo = $arProductFields[$arItem["ITEM_ID"]]["DETAIL_PICTURE"];
                    

                    else if(!empty($arProductFields[$arItem["ITEM_ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                        $photo = $arProductFields[$arItem["ITEM_ID"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];


                    if($photo)
                    {
                        $img = CFile::ResizeImageGet($photo, array('width'=>290, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                        $arResult["SET_ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = $img["src"];
                    }

                    else
                        $arResult["SET_ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.png";

                    $arResult["SET_ITEMS"][$key]["DETAIL_PAGE"] = $arProductFields[$arItem["ITEM_ID"]]["DETAIL_PAGE_URL"];
                    /*$arResult["SET_ITEMS"][$key]["ARTICLE"] = strip_tags($arProductFields[$arItem["ITEM_ID"]]["PROPERTIES"]["ARTICLE"]["~VALUE"]);*/

                }
            }
        }

        $arResult["SHOW_SET_PRODUCT"] = isset($arResult["SET_ITEMS"]) && !empty($arResult["SET_ITEMS"]) && $arResult["PROPERTIES"]["SHOW_BLOCK_SET_PRODUCT"]["VALUE"] == "Y";

    }


    // BLOG, NEWS, ACTIONS SECTIONS

    $code = array('concept_phoenix_blog_'.SITE_ID, 'concept_phoenix_news_'.SITE_ID, 'concept_phoenix_action_'.SITE_ID);

    $SectList = CIBlockSection::GetList(array("SORT"=>"ASC"), array("IBLOCK_CODE"=>$code, "ACTIVE"=>"Y"), false, array("ID","NAME","SECTION_PAGE_URL"));
    while ($SectListGet = $SectList->GetNext())
    {
        if(strlen($SectListGet["UF_CATEGORY_LIST"]))
        {
            $SectListGet["UF_CATEGORY_LIST_ENUM"] = CUserFieldEnum::GetList(array(), array(
                "ID" => $SectListGet["UF_CATEGORY_LIST"],
            ))->GetNext();
        }
        else
            $SectListGet["UF_CATEGORY_LIST_ENUM"]["XML_ID"] = "icon-default-opinion";
        
        $arResult["BNA"][$SectListGet["ID"]]=$SectListGet;
    }

    $arNews = array();

    if(!empty($arResult["PROPERTIES"]["NEWS"]["VALUE"]))
        $arNews = array_merge($arNews, $arResult["PROPERTIES"]["NEWS"]["VALUE"]);

    if(!empty($arResult["PROPERTIES"]["BLOGS"]["VALUE"]))
        $arNews = array_merge($arNews, $arResult["PROPERTIES"]["BLOGS"]["VALUE"]);

    if(!empty($arResult["PROPERTIES"]["SPECIALS"]["VALUE"]))
        $arNews = array_merge($arNews, $arResult["PROPERTIES"]["SPECIALS"]["VALUE"]);

    $arResult["PROPERTIES"]["NEWS_LIST"]["VALUE"] = $arNews;

    //faq
    if(!empty($arResult["PROPERTIES"]["FAQ_ELEMENTS"]["VALUE"]))
    {
        $code_faq = "concept_phoenix_faq_".SITE_ID;
        $arFilter = Array("IBLOCK_CODE"=>$code_faq, "SECTION_ID" => $arResult["PROPERTIES"]["FAQ_ELEMENTS"]["VALUE"], "ACTIVE"=>"Y", "INCLUDE_SUBSECTIONS" => "N");
        $res = CIBlockElement::GetList(Array("sort" => "asc"), $arFilter);

        while($ob = $res->GetNextElement())
        {

            $arElement = Array();
            
            $arElement = $ob->GetFields();  
            $arElement["PROPERTIES"] = $ob->GetProperties();

            $arResult["PROPERTIES"]["FAQ"]["VALUE"][] = $arElement;
           
        } 
    }

    //similar
    if($arResult["PROPERTIES"]["SHOWBLOCK5"]["VALUE"] == "Y")
    {

     
        $arResult["SIMILAR"] = array();

        if($arResult["PROPERTIES"]["SIMILAR_SOURCE"]["VALUE_XML_ID"] == "")
            $arResult["PROPERTIES"]["SIMILAR_SOURCE"]["VALUE_XML_ID"] = "random_category_inherit";

        if(trim($arResult["PROPERTIES"]["SIMILAR_MAX_QUANTITY"]["VALUE"]) == "" || intval($arResult["PROPERTIES"]["SIMILAR_MAX_QUANTITY"]["VALUE"]) == 0)
            $arResult["PROPERTIES"]["SIMILAR_MAX_QUANTITY"]["VALUE"] = "4";


        if($arResult["PROPERTIES"]["SIMILAR_SOURCE"]["VALUE_XML_ID"] == "random_category_inherit" && $arResult['IBLOCK_SECTION_ID']>0)
        {
            $arSelect = array("ID");
            $arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "INCLUDE_SUBSECTIONS"=>"Y", "SECTION_GLOBAL_ACTIVE"=>"Y", "SECTION_ID" => $arResult['IBLOCK_SECTION_ID'], "!ID" => $arResult['ID']);
            $res = CIBlockElement::GetList(Array("RAND"=>"ASC"), $arFilter, false, Array("nTopCount"=>$arResult["PROPERTIES"]["SIMILAR_MAX_QUANTITY"]["VALUE"]), $arSelect);

            while($ob = $res->GetNextElement())
            {
                $aritem = array();
                $aritem = $ob->GetFields();
                $arResult["SIMILAR"][] = $aritem["ID"];

            } 

        }

        if($arResult["PROPERTIES"]["SIMILAR_SOURCE"]["VALUE_XML_ID"] == "random_category_list" && !empty($arResult["PROPERTIES"]["SIMILAR_CATEGORY_LIST"]["VALUE"]) )
        {
            $arSelect = array("ID");
            $arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "ACTIVE"=>'Y', "INCLUDE_SUBSECTIONS"=>"Y", "SECTION_GLOBAL_ACTIVE"=>"Y", "SECTION_ID" => $arResult["PROPERTIES"]["SIMILAR_CATEGORY_LIST"]["VALUE"], "!ID" => $arResult['ID']);
            $res = CIBlockElement::GetList(Array("RAND"=>"ASC"), $arFilter, false, Array("nTopCount"=>$arResult["PROPERTIES"]["SIMILAR_MAX_QUANTITY"]["VALUE"]), $arSelect);

            while($ob = $res->GetNextElement())
            {
                $aritem = array();
                $aritem = $ob->GetFields();
                $arResult["SIMILAR"][] = $aritem["ID"];
            }
        }

        if($arResult["PROPERTIES"]["SIMILAR_SOURCE"]["VALUE_XML_ID"] == "elements")
            $arResult["SIMILAR"] = $arResult["PROPERTIES"]["SIMILAR"]["VALUE"];
    }

    //accessory
    if($arResult["PROPERTIES"]["SHOWBLOCK13"]["VALUE"] == "Y")
    {
        $arResult["ACCESSORY"] = array();

        if($arResult["PROPERTIES"]["ACCESSORY_SOURCE"]["VALUE_XML_ID"] == "")
            $arResult["PROPERTIES"]["ACCESSORY_SOURCE"]["VALUE_XML_ID"] = "random_category_inherit";

        if($arResult["PROPERTIES"]["ACCESSORY_MAX_QUANTITY"]["VALUE"] == "")
            $arResult["PROPERTIES"]["ACCESSORY_MAX_QUANTITY"]["VALUE"] = "4";


        if($arResult["PROPERTIES"]["ACCESSORY_SOURCE"]["VALUE_XML_ID"] == "random_category_inherit" && $arResult['IBLOCK_SECTION_ID']>0)
        {
            $arSelect = array("ID");
            $arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "ACTIVE"=>'Y', "INCLUDE_SUBSECTIONS"=>"Y", "SECTION_GLOBAL_ACTIVE"=>"Y", "SECTION_ID" => $arResult['IBLOCK_SECTION_ID'], "!ID" => $arResult['ID']);
            $res = CIBlockElement::GetList(Array("RAND"=>"ASC"), $arFilter, false, Array("nTopCount"=>$arResult["PROPERTIES"]["ACCESSORY_MAX_QUANTITY"]["VALUE"]), $arSelect);

            while($ob = $res->GetNextElement())
            {
                $aritem = array();
                $aritem = $ob->GetFields();
                $arResult["ACCESSORY"][] = $aritem["ID"];
            } 

        }


        if($arResult["PROPERTIES"]["ACCESSORY_SOURCE"]["VALUE_XML_ID"] == "random_category_list" && !empty($arResult["PROPERTIES"]["ACCESSORY_CATEGORY_LIST"]["VALUE"]) )
        {
            $arSelect = array("ID");
            $arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "INCLUDE_SUBSECTIONS"=>"Y", "SECTION_GLOBAL_ACTIVE"=>"Y", "SECTION_ID" => $arResult["PROPERTIES"]["ACCESSORY_CATEGORY_LIST"]["VALUE"], "!ID" => $arResult['ID']);
            $res = CIBlockElement::GetList(Array("RAND"=>"ASC"), $arFilter, false, Array("nTopCount"=>$arResult["PROPERTIES"]["ACCESSORY_MAX_QUANTITY"]["VALUE"]), $arSelect);

            while($ob = $res->GetNextElement())
            {
                $aritem = array();
                $aritem = $ob->GetFields();
                $arResult["ACCESSORY"][] = $aritem["ID"];
            }
        }

        if($arResult["PROPERTIES"]["ACCESSORY_SOURCE"]["VALUE_XML_ID"] == "elements")
            $arResult["ACCESSORY"] = $arResult["PROPERTIES"]["ACCESSORY"]["VALUE"];
    }

    //similar category

    if(!empty($similarCategory))
    {

        $arSelect = Array("ID", "SECTION_PAGE_URL", "NAME", "UF_PHX_MENU_PICT");
        $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ID"=>$similarCategory, "ACTIVE"=>"Y", "GLOBAL_ACTIVE"=>"Y");

        $db_list = CIBlockSection::GetList(Array("RAND"=>"ASC"), $arFilter, false, $arSelect);
        
        $arResult["SIMILAR_CATEGORY"] = array();
        while($ar_res = $db_list->GetNext())
        {
            $img = CFile::ResizeImageGet($ar_res["UF_PHX_MENU_PICT"], array('width'=>140, 'height'=>140), BX_RESIZE_IMAGE_PROPORTIONAL, false);

            $ar_res["PICTURE_SRC"] = $img["src"];
            $arResult["SIMILAR_CATEGORY"][] = $ar_res;
        }

        $arResult["SIMILAR_CATEGORY_CNT"] = count($arResult["SIMILAR_CATEGORY"]);

    }


    unset($similarCategory);

    $arMenu = Array();
    $arTmpMenu = Array();
    $arTmpMenuSort = Array();

    $arSort = Array();
    $sortFromElement = ($arResult["PROPERTIES"]["SORT_SELF"]["VALUE"] === "Y")?true:false;



    $arBlocks = false;

    //main
    if($arResult["PROPERTIES"]["SHOWMENU_BLOCK1"]["VALUE"] == "Y")
    {
        $arMenu["main"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK1"]["~VALUE"];
    }


    //advantages
    if($arResult["PROPERTIES"]["SHOWMENU_BLOCK2"]["VALUE"] == "Y" && isset($arResult["ADVANTAGES"]) && !empty($arResult["ADVANTAGES"]))
    {
        $arTmpMenu["advantages"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK2"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["advantages"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK2"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK2"]["VALUE"] : 10);
        else
            $arTmpMenuSort["advantages"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_ADV']['VALUE'];
        
        $arBlocks = true;
    }

    if($sortFromElement)
        $arSort["advantages"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK2"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK2"]["VALUE"] : 10);
    else
        $arSort["advantages"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_ADV']['VALUE'];


    //set_product
    if($arResult["PROPERTIES"]["SHOW_BLOCK_SET_PRODUCT"]["VALUE"] == "Y" && $arResult["PROPERTIES"]["SHOWMENU_BLOCK_SET_PRODUCT"]["VALUE"] == "Y" && isset($arResult["SET_ITEMS"]) && !empty($arResult["SET_ITEMS"]))
    {
        $arTmpMenu["set_product"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK_SET_PRODUCT"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["set_product"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT"]["VALUE"] : 5);
        else
            $arTmpMenuSort["set_product"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_COMPLECT']['VALUE'];
        
        $arBlocks = true;
    }

    if($sortFromElement)
        $arSort["set_product"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT"]["VALUE"] : 5);
    else
        $arSort["set_product"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_COMPLECT']['VALUE'];



    //set_product constructor
    if($arResult["PROPERTIES"]["SHOW_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"] == "Y" && $arResult["PROPERTIES"]["SHOWMENU_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"] == "Y")
    {
        $arTmpMenu["set_product_constructor"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["set_product_constructor"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"] : 6);
        else
            $arTmpMenuSort["set_product_constructor"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_SET']['VALUE'];
        
        $arBlocks = true;
    }

    if($sortFromElement)
        $arSort["set_product_constructor"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"] : 6);
    else
        $arSort["set_product_constructor"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_SET']['VALUE'];





    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_REVIEW"]["VALUE"]["ACTIVE"] == "Y")
    {
        CPhoenix::setMess(array("review", "rating"));
        
        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["RATING_SIDEMENU_SHOW"]["VALUE"]["ACTIVE"] == "Y")
        {
            

            $arTmpMenu["rating-block"]["NAME"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["RATING_SIDEMENU_NAME"]["VALUE"];


            if($sortFromElement)
                $arSort["rating-block"] = $arTmpMenuSort["rating-block"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK14"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK14"]["VALUE"] : 140);
            else
                $arSort["rating-block"] = $arTmpMenuSort["rating-block"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_RATING']['VALUE'];
            
            $arBlocks = true;
        }


    }

    $arResult["rating"] = array();


    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y")
            
    {
        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_REVIEW"]["VALUE"]["ACTIVE"] == "Y")
        {
            $arResult["RATING_VIEW"] = "full";
        }
        else
        {
            $arResult["RATING_VIEW"] = "simple";
            $arResult["rating"][$arResult["ID"]]["VALUE"] = (strlen($arResult["PROPERTIES"]["rating"]["VALUE"]))?round($arResult["PROPERTIES"]["rating"]["VALUE"]):"0";
            $arResult["rating"][$arResult["ID"]]["COUNT"] = (strlen($arResult["PROPERTIES"]["vote_count"]["VALUE"]))?round($arResult["PROPERTIES"]["vote_count"]["VALUE"]):"0";
        }
    }


    if($sortFromElement)
        $arSort["rating"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK14"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK14"]["VALUE"] : 140);
    else
        $arSort["rating"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_RATING']['VALUE'];



    //chars
    if($arResult["PROPERTIES"]["SHOWMENU_BLOCK3"]["VALUE"] == "Y" && (!empty($arResult['PRODUCT']["CHARS_SORT"]) || !empty($arResult["PROPERTIES"]["FILES"]["VALUE"])))
    {
        $arTmpMenu["chars"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK3"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["chars"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK3"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK3"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK3"]["DEFAULT_VALUE"]);
        else
            $arTmpMenuSort["chars"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_CHAR']['VALUE'];
        
        $arBlocks = true;
    }


    if($sortFromElement)
        $arSort["chars"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK3"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK3"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK3"]["DEFAULT_VALUE"]);
    else
        $arSort["chars"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_CHAR']['VALUE'];

    //video
    if($arResult["PROPERTIES"]["SHOWMENU_BLOCK4"]["VALUE"] == "Y" && !empty($arResult["PROPERTIES"]["VIDEO"]["VALUE"]))
    {
        $arTmpMenu["video"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK4"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["video"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK4"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK4"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK"]["DEFAULT_VALUE"]);
        else
            $arTmpMenuSort["video"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_VIDEO']['VALUE'];
        
        $arBlocks = true;
    }

    if($sortFromElement)
        $arSort["video"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK4"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK4"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK4"]["DEFAULT_VALUE"]);
    else
        $arSort["video"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_VIDEO']['VALUE'];

    //similar
    if( $arResult["PROPERTIES"]["SHOWMENU_BLOCK5"]["VALUE"] == "Y" && isset($arResult["SIMILAR"]) && !empty($arResult["SIMILAR"]) )
    {
        $arTmpMenu["similar"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK5"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["similar"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK5"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK5"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK5"]["DEFAULT_VALUE"]);

        else
            $arTmpMenuSort["similar"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_GOODS']['VALUE'];
        
        $arBlocks = true;
    }

    if($sortFromElement)
        $arSort["similar"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK5"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK5"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK5"]["DEFAULT_VALUE"]);
    else
        $arSort["similar"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_GOODS']['VALUE'];


    //accessory
    if( $arResult["PROPERTIES"]["SHOWMENU_BLOCK13"]["VALUE"] == "Y" && isset($arResult["ACCESSORY"]) && !empty($arResult["ACCESSORY"]) )
    {
        $arTmpMenu["accessory"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK13"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["accessory"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK13"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK13"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK13"]["DEFAULT_VALUE"]);
        else
            $arTmpMenuSort["accessory"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_ACCESSORIES']['VALUE'];

        
        $arBlocks = true;
    }


    if($sortFromElement)
        $arSort["accessory"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK13"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK13"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK13"]["DEFAULT_VALUE"]);

    else
        $arSort["accessory"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_ACCESSORIES']['VALUE'];


    //similar category
    if($arResult["PROPERTIES"]["SHOWMENU_BLOCK11"]["VALUE"] == "Y" && !empty($arResult["SIMILAR_CATEGORY"]))
    {
        $arTmpMenu["similar_category"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK11"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["similar_category"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK11"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK11"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK11"]["DEFAULT_VALUE"]);
        else
            $arTmpMenuSort["similar_category"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_CATEGORY']['VALUE'];
        
        $arBlocks = true;
    }

    if($sortFromElement)
        $arSort["similar_category"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK11"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK11"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK11"]["DEFAULT_VALUE"]);
    else
        $arSort["similar_category"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_CATEGORY']['VALUE'];



    //stuff
    if($arResult["PROPERTIES"]["SHOWMENU_BLOCK6"]["VALUE"] == "Y" && !empty($arResult["PROPERTIES"]["STUFF"]["VALUE"]))
    {
        $arTmpMenu["stuff"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK6"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["stuff"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK6"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK6"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK6"]["DEFAULT_VALUE"]);
        else
            $arTmpMenuSort["stuff"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_EMPL']['VALUE'];
        
        $arBlocks = true;
    }

    if($sortFromElement)
        $arSort["stuff"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK6"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK6"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK6"]["DEFAULT_VALUE"]);
    else
        $arSort["stuff"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_EMPL']['VALUE'];


    //news_list
    if($arResult["PROPERTIES"]["SHOWMENU_BLOCK7"]["VALUE"] == "Y" && !empty($arResult["PROPERTIES"]["NEWS_LIST"]["VALUE"]))
    {
        $arTmpMenu["news_list"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK7"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["news_list"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK7"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK7"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK7"]["DEFAULT_VALUE"]);
        else
            $arTmpMenuSort["news_list"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_REVIEW']['VALUE'];
        
        $arBlocks = true;
    }

    if($sortFromElement)
        $arSort["news_list"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK7"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK7"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK7"]["DEFAULT_VALUE"]);
    else
        $arSort["news_list"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_REVIEW']['VALUE'];




    //faq
    if($arResult["PROPERTIES"]["SHOWMENU_BLOCK8"]["VALUE"] == "Y" && !empty($arResult["PROPERTIES"]["FAQ"]["VALUE"]))
    {
        $arTmpMenu["faq"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK8"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["faq"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK8"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK8"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK8"]["DEFAULT_VALUE"]);

        else
            $arTmpMenuSort["faq"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_FAQ']['VALUE'];
        
        $arBlocks = true;
    }

    if($sortFromElement)
        $arSort["faq"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK8"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK8"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK8"]["DEFAULT_VALUE"]);
    else
        $arSort["faq"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_FAQ']['VALUE'];



    //text
    if($arResult["PROPERTIES"]["SHOWMENU_BLOCK9"]["VALUE"] == "Y" && strlen($arResult["DETAIL_TEXT"]) > 0)
    {
        $arTmpMenu["text"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK9"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["text"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK9"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK9"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK9"]["DEFAULT_VALUE"]);
        else
            $arTmpMenuSort["text"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_INFO_1']['VALUE'];
        
        $arBlocks = true;
    }

    if($sortFromElement)
        $arSort["text"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK9"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK9"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK9"]["DEFAULT_VALUE"]);
    else
        $arSort["text"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_INFO_1']['VALUE'];


    //text 2
    if($arResult["PROPERTIES"]["SHOWMENU_BLOCK12"]["VALUE"] == "Y" && strlen($arResult["DETAIL_TEXT"]) > 0)
    {
        $arTmpMenu["text2"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK12"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["text2"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK12"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK12"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK12"]["DEFAULT_VALUE"]);
        else
            $arTmpMenuSort["text2"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_INFO_2']['VALUE'];
        
        $arBlocks = true;
    }

    if($sortFromElement)
        $arSort["text2"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK12"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK12"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK12"]["DEFAULT_VALUE"]);
    else 
        $arSort["text2"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_INFO_2']['VALUE'];


    //text 3
    if($arResult["PROPERTIES"]["SHOWMENU_BLOCK15"]["VALUE"] == "Y" && strlen($arResult["DETAIL_TEXT"]) > 0)
    {
        $arTmpMenu["text3"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK15"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["text3"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK15"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK15"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK15"]["DEFAULT_VALUE"]);
        else
            $arTmpMenuSort["text3"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_INFO_3']['VALUE'];
        
        $arBlocks = true;
    }


    if($sortFromElement)
        $arSort["text3"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK15"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK15"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK15"]["DEFAULT_VALUE"]);
    else
        $arSort["text3"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_INFO_3']['VALUE'];


    //gallery
    if($arResult["PROPERTIES"]["SHOWMENU_BLOCK10"]["VALUE"] == "Y" && !empty($arResult["PROPERTIES"]["GALLERY"]["VALUE"]))
    {
        $arTmpMenu["gallery"]["NAME"] = $arResult["PROPERTIES"]["MENUTITLE_BLOCK10"]["~VALUE"];

        if($sortFromElement)
            $arTmpMenuSort["gallery"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK10"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK10"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK10"]["DEFAULT_VALUE"]);
        else
            $arTmpMenuSort["gallery"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_GALLERY']['VALUE'];
        
        $arBlocks = true;
    }

    if($sortFromElement)
        $arSort["gallery"] = ((strlen($arResult["PROPERTIES"]["POSITION_BLOCK10"]["VALUE"]) > 0) ? $arResult["PROPERTIES"]["POSITION_BLOCK10"]["VALUE"] : $arResult["PROPERTIES"]["POSITION_BLOCK10"]["DEFAULT_VALUE"]);
    else
        $arSort["gallery"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_GALLERY']['VALUE'];





    function cmp($a, $b) 
    {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    uasort($arTmpMenuSort, "cmp");
    uasort($arSort, "cmp");
    


    if(!empty($arTmpMenuSort))
    {
        foreach($arTmpMenuSort as $key=>$value)
            $arMenu[$key] = $arTmpMenu[$key];   
    } 


    $arResult["SIDE_MENU"] = $arMenu;
    $arResult["BLOCK_SORT"] = $arSort;
    $arResult["BLOCKS"] = $arBlocks;
    



    $host = CPhoenixHost::getHost($_SERVER);
    $url = $host.$_SERVER["REQUEST_URI"];

    $arResult["SHARE_TITLE"] = strip_tags(htmlspecialcharsBack($arResult["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]));
    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_TITLE"])>0)
        $arResult["SHARE_TITLE"] = strip_tags($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_TITLE"]);


    $arResult["SHARE_DESCRIPTION"] = strip_tags(htmlspecialcharsBack($arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"]));
    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_DESCRIPTION"])>0)
        $arResult["SHARE_DESCRIPTION"] = strip_tags(htmlspecialcharsBack($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_DESCRIPTION"]));

    $arResult["SHARE_DESCRIPTION"] = str_replace(array("\r\n", "\r", "\n", "<br>", "<br/>"), array(" ", " ", " "," "," "), $arResult["SHARE_DESCRIPTION"]);

    if(!empty($arResult['PRODUCT']['MORE_PHOTOS']))
        $arResult["SHARE_IMG"] = $host.$arResult['PRODUCT']['MORE_PHOTOS'][0]['MIDDLE']['SRC'];

    if($arResult["PRODUCT"]["HAVEOFFERS"])
        $arResult["SHARE_IMG"] = $host.$arResult['PRODUCT']['OFFERS'][$arResult['PRODUCT']['OFFER_SELECTED']]['MORE_PHOTOS'][0]['MIDDLE']['SRC'];

    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_IMAGE"])>0)
        $arResult["SHARE_IMG"] = $host.CFile::GetPath($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_IMAGE"]);

    $GLOBALS["OG_IMAGE"] = $GLOBALS["OG_IMAGE_PATH"] = $arResult["SHARE_IMG"];
    
    $arResult["SHARE_URL"] = $url;
    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_URL"])>0)
        $arResult["SHARE_URL"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_URL"];



    $arResult["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"] = ($arResult["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"]=="")?"form":$arResult["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"];


    $arResult["BTN"] = array(
        "BTN_NAME" => $arResult["PROPERTIES"]["BTN_NAME"]["VALUE"],
        "~BTN_NAME" => $arResult["PROPERTIES"]["BTN_NAME"]["~VALUE"],
        "ACTION" => $arResult["PROPERTIES"]["BTN_ACTION"]["VALUE_XML_ID"],
        "FORM_ID" => $arResult["PROPERTIES"]["BTN_FORM"]["VALUE"],
        "POPUP_ID" => $arResult["PROPERTIES"]["BTN_POPUP"]["VALUE"],
        "PRODUCT_ID" => ($arResult["PROPERTIES"]["BTN_OFFER_ID"]["VALUE"])?$arResult["PROPERTIES"]["BTN_OFFER_ID"]["VALUE"]:$arResult["PROPERTIES"]["BTN_PRODUCT_ID"]["VALUE"],
        "VIEW" => $arResult["PROPERTIES"]["BTN_VIEW"]["VALUE_XML_ID"],
        "BG_COLOR" => $arResult["PROPERTIES"]["BTN_BG_COLOR"]["VALUE"],
        "QUIZ_ID" => $arResult["PROPERTIES"]["BTN_QUIZ_ID"]["VALUE"],
        "LAND_ID" => $arResult["PROPERTIES"]["BTN_LAND"]["VALUE"],
        "URL" => $arResult["PROPERTIES"]["BTN_URL"]["VALUE"],
        "TARGET_BLANK" => $arResult["PROPERTIES"]["BTN_TARGET_BLANK"]["VALUE"],
        "HEADER" => strip_tags($arResult["~NAME"]),
        "ONCLICK" => $arResult["PROPERTIES"]["BTN_ONCLICK"]["VALUE"]
    );


/*----------*/


$cp = $this->__component;

if (is_object($cp))
{
    $cp->arResult['SECTIONS_ID'] = $arResult["SECTIONS_ID"];
    $cp->SetResultCacheKeys(array('ID','SECTIONS_ID', 'rating', 'PRODUCT'));

    $arResult['SECTIONS_ID'] = $cp->arResult['SECTIONS_ID'];
}








