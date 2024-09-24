<?
if(!defined('CONCEPT_PHOENIX_MODULE_ID')){
    define('CONCEPT_PHOENIX_MODULE_ID', 'concept.phoenix');
}


IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/classes/general/CPhoenix.php");
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option as Option;
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
    Bitrix\Sale\DiscountCouponsManager;



class CPhoenix{

    public static function setReviewsInfo($PRODUCT_ID)
    {
        global $DB;
            $getListParams = array(
                'select' => array('VOTE', 'RECOMMEND'),
                'filter' => array("=PRODUCT_ID"=>$PRODUCT_ID, "ACTIVE" => "Y"),
                'order' => array()
            );

        $arResult = Bitrix\CPhoenixReviews\CPhoenixReviewsTable::getList($getListParams)->fetchAll();


        $arFields = array();

        $arFields["REVIEWS_COUNT"] = count($arResult);
        $arFields["VOTE_COUNT"] = 0;
        $arFields["VOTE_SUM"] = 0;
        $arFields["VOTE_COUNT_1_2"] = 0;
        $arFields["VOTE_COUNT_3"] = 0;
        $arFields["VOTE_COUNT_4"] = 0;
        $arFields["VOTE_COUNT_5"] = 0;
        $arFields["RECOMMEND_COUNT_Y"] = 0;
        $arFields["RECOMMEND_COUNT_N"] = 0;
        $arFields["PRODUCT_ID"] = $PRODUCT_ID;
        


        foreach($arResult as $value)
        {
            $value["VOTE"] = intval($value["VOTE"]);
            

            if($value["VOTE"] > 0)
            {
                $arFields["VOTE_SUM"] += $value["VOTE"];
                $arFields["VOTE_COUNT"]++;

                if($value["VOTE"] === 1 || $value["VOTE"] === 2)
                    $arFields["VOTE_COUNT_1_2"]++;

                else if($value["VOTE"] === 3)
                    $arFields["VOTE_COUNT_3"]++;

                else if($value["VOTE"] === 4)
                    $arFields["VOTE_COUNT_4"]++;

                else if($value["VOTE"] === 5)
                    $arFields["VOTE_COUNT_5"]++;
            }

            if($value["RECOMMEND"] === "Y")
                $arFields["RECOMMEND_COUNT_Y"]++;
            
            else if($value["RECOMMEND"] === "N")
                $arFields["RECOMMEND_COUNT_N"]++;

        }



        $arResult = Bitrix\CPhoenixReviewsInfo\CPhoenixReviewsInfoTable::getList(
            array(
                'select'=>array("ID"),
                'filter' => array('=PRODUCT_ID' => $PRODUCT_ID),"cache"=>array("ttl"=>3600)
            ))->fetch();


        if(intval($arResult["ID"]) > 0)
        {

            $result = Bitrix\CPhoenixReviewsInfo\CPhoenixReviewsInfoTable::update($arResult["ID"], $arFields);

        }
        else
        {
            $result = Bitrix\CPhoenixReviewsInfo\CPhoenixReviewsInfoTable::add($arFields);
        }

        

        if ($result->isSuccess())
            return true;
    }

    public static function getUserFields($userID)
    {

        if(!$userID)
            return;

        $userFilter = array(
            'ID' => $userID,
            'ACTIVE' => 'Y'
        );

        $userParams = array(
            'SELECT' => array(),
            'NAV_PARAMS' => array(
                'nTopCount' => 1
            ),
            'FIELDS' => array(
                'ID',
                'NAME',
                'EMAIL'
            ),
        );

        $userBy = "id";
        $userOrder = "desc";

        $rsUser = CUser::GetList(
            $userBy,
            $userOrder,
            $userFilter,
            $userParams
        );

        return $arUser = $rsUser->Fetch();
    }
    public static function repairArUserFields($arUser = array())
    {
        if(!empty($arUser))
        {
            $newArUser = array();
            $newArUserEmpty = array();
            foreach ($arUser as $key => $value)
            {   
                if(!empty($value["VALUE"]))
                    $newArUser[$value["ID"]] = $value;

                else
                    $newArUserEmpty[$value["ID"]] = $value;
            }
            

            if(!empty($newArUser) && !empty($newArUserEmpty))
            {
                $newArUser = array_merge($newArUser, $newArUserEmpty);
                $arUser = $newArUser;
            }
        }
        return $arUser;
    }
    public static function isAdmin()
    {
        global $USER, $APPLICATION;
        return ($USER->isAdmin() || $APPLICATION->GetGroupRight('concept.phoenix') > "R");
    }

    public static function saveOptions($site_id, $arPost = array(), $admPanel = 'N'){

        if(strlen($site_id)<=0 || !CPhoenix::isAdmin())
            die();

        global $PHOENIX_TEMPLATE_ARRAY;


        CPhoenix::phoenixOptionsValues($site_id, array(
            "start",
            "design",
            "contacts",
            "menu",
            "footer",
            "catalog",
            "shop",
            "blog",
            "news",
            "actions",
            "lids",
            "services",
            "politic",
            "customs",
            "other",
            "search",
            "catalog_fields",
            "compare",
            "brands",
            "personal",
            "rating",
            "region"
        ));


       
        $arCheckbox = array();

        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]))
        {
            foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"] as $arItems)
            {
                if(!empty($arItems["ITEMS"]))
                {
                    foreach($arItems["ITEMS"] as $arItem)
                    {
                        if(isset($arItem["TYPE"]))
                        {
                            if($arItem["TYPE"] == "checkbox")
                            {
                                if(isset($arItem["VALUES"]))
                                {
                                    if(!empty($arItem["VALUES"]))
                                    {
                                        foreach ($arItem["VALUES"] as $keyField => $arField) 
                                        {
                                            if(!empty($arItem["VALUE"][$keyField]))
                                                $arCheckbox[$arField["NAME"]] = $arItem["VALUE"][$keyField];
                                        }
                                    }
                                }
                                
                            }
                        }

                        if(isset($arItem["ITEMS"]))
                        {
                            if(!empty($arItem["ITEMS"]))
                            {
                                foreach ($arItem["ITEMS"] as $arFieldItems) 
                                {
                                    if(isset($arFieldItems["TYPE"]))
                                    {
                                        if($arFieldItems["TYPE"] == "checkbox")
                                        {
                                            if(isset($arFieldItems["VALUES"]))
                                            {
                                                if(!empty($arFieldItems["VALUES"]) && is_array($arFieldItems["VALUES"]))
                                                {
                                                    foreach ($arFieldItems["VALUES"] as $keyFieldItem => $arFieldItem) 
                                                    {
                                                        if(!empty($arFieldItems["VALUE"][$keyFieldItem]))
                                                            $arCheckbox[$arFieldItem["NAME"]] = $arFieldItems["VALUE"][$keyFieldItem];
                                                    }
                                                }
                                            }
                                        }
                                        
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }
        unset($arCheckbox['phoenix_hide_adv']);

        
        if(!empty($arPost))
        {
            foreach($arPost as $key => $val)
            {
                if(strpos($key, "phoenix_") !== false && strpos($key, "phoenix_") === 0)
                {
                    if(SITE_CHARSET == "windows-1251" && $admPanel == 'N')
                    {
                        $key = utf8win1251($key);
                        $val = utf8win1251($val);
                    }

                    if(isset($arCheckbox[$key]))
                    {
                        if($arCheckbox[$key] != $val)
                            Option::set("concept.phoenix", $key, htmlspecialcharsEx($val), $site_id);
                        
                        unset($arCheckbox[$key]);
                    }
                    else{
                        Option::set("concept.phoenix", $key, htmlspecialcharsEx($val), $site_id);
                    }
                    
                }

                if(strpos($key, "img_phoenix_") !== false && strpos($key, "img_phoenix_") === 0 && $val=="Y")
                    CPhoenix::deleteImage(Array("NAME"=> str_replace("_del","",$key), "VALUE"=>$arPost["old_".str_replace("_del","",$key)], "SITE_ID"=>$site_id));
            }
        }

        
  

        if(!empty($_FILES))
        {

            foreach($_FILES as $key => $val)
            {
                if(strpos($key, "img_phoenix_") !== false && strpos($key, "img_phoenix_") === 0)
                    CPhoenix::saveImage(array("NAME"=>$key, "VALUE"=>$val, "SITE_ID"=>$site_id,"FROM_ADMIN"=>$admPanel));
                
            }
        }

        if(!empty($arCheckbox))
        {
            foreach ($arCheckbox as $key => $value) {
                Option::delete("concept.phoenix", array(
                    "name" => $key,
                    "site_id" => $site_id
                    )
                );
            }
        }



        //set lists

        CPhoenix::saveSetsMultiRow($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]['ITEMS']["META"]['NAME'], $site_id, $admPanel, "Y");
        CPhoenix::saveSetsMultiRow($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]['ITEMS']["HEAD_CONTACTS"]['NAME'], $site_id, $admPanel, "Y");
        CPhoenix::saveSetsMultiRow($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]['ITEMS']["HEAD_EMAILS"]['NAME'], $site_id, $admPanel, "Y");

        CPhoenix::saveSetsMultiRow($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]['ITEMS']["CUSTOM_PAGES"]['NAME'], $site_id, $admPanel, "Y");


        
        if(isset($arPost["phoenix_name_site"]) && $admPanel == 'N')
        {
            $site_name = $arPost["phoenix_name_site"];

            if(SITE_CHARSET == "windows-1251")
                $site_name = utf8win1251($site_name);

            $arFields = Array(
              "NAME" => $site_name,
            );

            $obSite = new CSite;
            $obSite->Update($site_id, $arFields);
            if (strlen($obSite->LAST_ERROR)>0) $strError .= $obSite->LAST_ERROR;
        }

            BXClearCache(true, $site_id);
    

    }

    public static function getFilterByRegion()
    {
        global $PHOENIX_TEMPLATE_ARRAY;

        $arRegionFilter = array();

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['ACTIVE']["VALUE"]["ACTIVE"] == "Y")
        {
            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"]) > 0)
            {
                $arRegionFilter[] = array(
                    "LOGIC" => "OR",
                    array("=PROPERTY_REGION" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"]),
                    array("PROPERTY_REGION" => false)
                );
            }
            else
            {
                $arRegionFilter["PROPERTY_REGION"] = false;
            }
        }

        

        return $arRegionFilter;

    }
    public static function getFilterByStores()
    {
        global $PHOENIX_TEMPLATE_ARRAY;

        $arStoresFilter = array();

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["CONFIG"]["default_quantity_trace"] === "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["CONFIG"]["default_can_buy_zero"] === "N" && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"]) && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["SHOW_RESIDUAL"]["VALUE"]["ACTIVE"]==="Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"] === "Y")
        {

            if(count($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"]) > 1)
            {
                $arStoresFilter = array('LOGIC' => 'OR');

                foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"] as $key => $storeID)
                    $arStoresFilter[] = array(">CATALOG_STORE_AMOUNT_".$storeID => 0);
            }
            else
            {
                foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"] as $key => $storeID)
                    $arStoresFilter = array(">CATALOG_STORE_AMOUNT_".$storeID => 0);
            }
        }


        return $arStoresFilter;
    }

    public static function getProductsAmountByStore($arItems=array()){

        global $PHOENIX_TEMPLATE_ARRAY;
        $arAmount=array();

        if(!empty($arItems))
        {

            $arIDs = array();

            foreach ($arItems as $key => $arItem)
            {
                $arIDs[$arItem['ID']]=$arItem['ID'];
                if(!empty($arItem['OFFERS']))
                {
                    foreach ($arItem['OFFERS'] as $keyOffer => $arOffer)
                    {
                        $arIDs[$arOffer['ID']]=$arOffer['ID'];
                    }
                }
            }

            if(!empty($arIDs))
            {
                $arStoreProductResult = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"];

                
                $rsStoreProduct = CCatalogStore::GetList(
                   array('PRODUCT_ID'=>'ASC','ID' => 'ASC'),
                   array('ID'=>$arStoreProductResult, 'ACTIVE' => 'Y','PRODUCT_ID'=>$arIDs),
                   false,
                   false,
                   array('ID','TITLE','ACTIVE','PRODUCT_AMOUNT','ELEMENT_ID')
                );

                while($arStoreProduct=$rsStoreProduct->fetch())
                {
                    $arAmount[$arStoreProduct['ELEMENT_ID']] += floatval($arStoreProduct['PRODUCT_AMOUNT']);
                }
            }
        }
        return $arAmount;
    }

    public static function parseProduct($arItem=array(), $arParams=array()){

        global $PHOENIX_TEMPLATE_ARRAY;



        $type = 'list';

        if($arParams['DETAIL_PAGE'] === 'Y' || $arParams['BLOCK_SLIDER'] === 'Y')
            $type = 'element';


        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MEASURE"]["VALUE"][$arItem['ITEM_MEASURE']['ID']] == "Y")
        {
            $measureHTML = "&nbsp;/&nbsp;".$arItem['ITEM_MEASURE']['TITLE'];
            $measure = $arItem['ITEM_MEASURE']['TITLE'];
        }


        $currency = ($arItem["ORIGINAL_PARAMETERS"]["CURRENCY_ID"])?$arItem["ORIGINAL_PARAMETERS"]["CURRENCY_ID"]:Currency\CurrencyManager::getBaseCurrency();

        if($arItem['PRODUCT']['QUANTITY_TRACE'] === 'N')
            $arItem['PRODUCT']['SUBSCRIBE'] = 'N';
        



        $arResult = array(
            'ID'=>$arItem['ID'],
            'IBLOCK_ID'=>$arItem['IBLOCK_ID'],
            'IBLOCK_TYPE_ID'=>$arItem['IBLOCK_TYPE_ID'],
            'IBLOCK_SECTION_ID'=>$arItem['IBLOCK_SECTION_ID'],
            'ACTIVE'=>$arItem['ACTIVE'],
            'OBJ_ID'=>$arItem["OBJ_ID"],
            'NAME'=>$arItem['NAME'],
            'NAME_HTML'=>$arItem['~NAME'],
            'DETAIL_PAGE_URL'=>$arItem['DETAIL_PAGE_URL'],
            'CHECK_QUANTITY' =>$arItem['CHECK_QUANTITY'],
            'MAX_QUANTITY'=>$arItem['PRODUCT']["QUANTITY"],
            'MIN_QUANTITY'=>'',
            'STEP_QUANTITY'=>$arItem['ITEM_MEASURE_RATIOS'][$arItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
            'QUANTITY_FLOAT' => is_float($arItem['ITEM_MEASURE_RATIOS'][$arItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
            'QUANTITY'=>array(
                'TEXT'=>'',
                'QUANTITY_STYLE'=>'',
                'QUANTITY'=>'',
                'QUANTITY_VALUE'=>'',
                'QUANTITY_WITH_TEXT'=>'',
                'HTML'=>''
            ),
            'MEASURE_HTML'=>$measureHTML,
            'MEASURE'=>$measure,
            'CAN_BUY'=>$arItem['CAN_BUY'],
            'CAN_BUY_ZERO'=>$arItem['CATALOG_CAN_BUY_ZERO'],
            'SUBSCRIBE'=>$arItem['PRODUCT']['SUBSCRIBE'],
            'ITEM_PRICE_MODE' => $arItem['ITEM_PRICE_MODE'],
            'ITEM_PRICES' => $arItem['ITEM_PRICES'],
            'ITEM_PRICE_SELECTED' => ($arItem['ITEM_PRICE_SELECTED'])?$arItem['ITEM_PRICE_SELECTED']:0,
            'ITEM_QUANTITY_RANGES' => $arItem['ITEM_QUANTITY_RANGES'],
            'ITEM_QUANTITY_RANGE_SELECTED' => $arItem['ITEM_QUANTITY_RANGE_SELECTED'],
            'ITEM_MEASURE_RATIOS' => $arItem['ITEM_MEASURE_RATIOS'],
            'ITEM_MEASURE_RATIO_SELECTED' => $arItem['ITEM_MEASURE_RATIO_SELECTED'],
            'PRICE'=>Array(
                "UNROUND_BASE_PRICE" => NULL,
                "UNROUND_PRICE" => NULL,
                "BASE_PRICE" => NULL,
                "PRICE" => "-1",
                "CURRENCY" => $currency,
                "MIN_QUANTITY" => "1"
            ),
            'PREVIEW_TEXT'=>$arItem['PREVIEW_TEXT'],
            'PREVIEW_TEXT_HTML'=>$arItem['~PREVIEW_TEXT'],
            
            'GLOBAL_SHOWPREORDERBTN'=>($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["AUTO_MODE_PREORDER"]["VALUE"]["ACTIVE"]=="Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_FORM"]["VALUE"] != "N")? true:false,
            'CATEGORY' => $arItem['CATEGORY_PATH'],

            'SEO_NAME'=>!empty($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
                : $arItem['~NAME'],
            'TITLE'=>!empty($arItem['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
                : $arItem['NAME'],
            'ALT'=>!empty($arItem['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
                : $arItem['NAME'],
            'DEF_PHOTO'=>'/bitrix/images/concept.phoenix/ufo.png',
            'PHOTO'=>'/bitrix/images/concept.phoenix/ufo.png',
            'ITEMPROP_AVAILABLE'=>'',
            'VISUAL'=>array(),
            'STORE'=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"],
            'PRODUCT_TYPE'=>intval($arItem['CATALOG_TYPE']),
            'MORE_PHOTOS'=> array(),
            'MORE_PHOTOS_COUNT'=>1,
            'HAVEOFFERS'=>!empty($arItem['OFFERS']),
            'SHOW_DISCOUNT'=>'',
            'SHOW_BTN_BASKET'=> '',
            'SHOW_BUY_BTN'=> '',
            'SHOW_BTNS'=>'Y',
            'PRICE_MATRIX_RESULT'=>array(),


            'ARTICLE'=>$arItem['PROPERTIES']['ARTICLE']['VALUE'],
            'ARTICLE_HTML'=>$arItem['PROPERTIES']['ARTICLE']['~VALUE'],
            'SKROLL_TO_CHARS_TITLE'=>strlen($arItem["PROPERTIES"]["SKROLL_TO_CHARS_TITLE"]["VALUE"])?$arItem["PROPERTIES"]["SKROLL_TO_CHARS_TITLE"]["VALUE"]:$PHOENIX_TEMPLATE_ARRAY["MESS"]["SKROLL_TO_CHARACTERISTICS"],
            'SCROLL_TO_CHARS_OFF'=>$arItem["PROPERTIES"]["SCROLL_TO_CHARS_OFF"]["VALUE_XML_ID"],
            'FILES'=>$arItem["PROPERTIES"]["FILES"]["VALUE"],
            'FILES_DESC'=>$arItem["PROPERTIES"]["FILES_DESC"]["VALUE"],
            'PREVIEW_TEXT_POSITION'=>$arItem["PROPERTIES"]["PREVIEW_TEXT_POSITION"]["VALUE_XML_ID"],
            'VIDEO_POPUP'=>$arItem["PROPERTIES"]['VIDEO_POPUP']["VALUE"],
            'SHORT_DESCRIPTION'=>is_array($arItem['PROPERTIES']['SHORT_DESCRIPTION']['VALUE'])?$arItem['PROPERTIES']['SHORT_DESCRIPTION']['VALUE']['TEXT']:'',
            'SHORT_DESCRIPTION_HTML'=>is_array($arItem['PROPERTIES']['SHORT_DESCRIPTION']['VALUE'])?$arItem['PROPERTIES']['SHORT_DESCRIPTION']['~VALUE']['TEXT']:'',
            'PREORDER_ONLY'=>$arItem["PROPERTIES"]["PREORDER_ONLY"]["VALUE"],
            'MODE_DISALLOW_ORDER'=>$arItem["PROPERTIES"]["MODE_DISALLOW_ORDER"]["VALUE"],
            'MODE_ARCHIVE'=>$arItem["PROPERTIES"]["MODE_ARCHIVE"]["VALUE"],
            'MODE_HIDE'=>$arItem["PROPERTIES"]["MODE_HIDE"]["VALUE"],
            'LABELS'=>array(
                'XML_ID'=>$arItem["PROPERTIES"]["LABELS"]["VALUE_XML_ID"],
                'VALUE'=>$arItem["PROPERTIES"]["LABELS"]["VALUE"]
            ),
            'DESC_FOR_ACTUAL_PRICE'=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DESC_FOR_ACTUAL_PRICE"]["~VALUE"],

            'SHOWPREORDERBTN'=>'',
            'CONFIG'=>array(
                'SHOW_PRICE_SIDE'=>true,
                'USE_PRICE_COUNT'=>$arParams['USE_PRICE_COUNT'],
                'SHOW_BUY_BTN_OPTION'=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_PRODUCT_ON"]["VALUE"]["ACTIVE"],
                'SHOW_BTN_BASKET_OPTION'=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"],
                'ZOOM'=>$arItem['ZOOM_ON'],
                'SHOW_QUANTITY'=>'Y',
                'SHOW_OLD_PRICE'=>"Y",
                'SHOW_DISCOUNT_PERCENT'=>"Y",
                'SHOW_PRICE'=>'Y',
                'USE_DELAY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"],
                'USE_COMPARE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"],
                'STORE_QUANTITY_ON'=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_ON"]["VALUE"]["ACTIVE"],
                'VIEW_STORE_QUANTITY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_VIEW"]["VALUE"],
                'OFFER_GROUP'=>false,
                'OFFER_GROUP_VALUES'=>array(),
                'COMPARE_URL' => $PHOENIX_TEMPLATE_ARRAY["MAIN_URLS"]['catalog'].'compare/',
                'PRECISION' => 6,
                'SHOW_DELAY'=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"],
                'SHOW_COMPARE'=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"],
                'SHOW_OFFERS'=> false,
                'SHOW_ABSENT'=>'Y',
                'USE_OID'=>'N',
                'BASKET_URL'=>$PHOENIX_TEMPLATE_ARRAY["BASKET_URL"]
            ),

            'FORM_BETTER_PRICE_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['BETTER_PRICE']['VALUE'],
            'FORM_PREORDER_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_FORM"]['VALUE'],
            
            
            'OFFER_ID_SELECTED'=>0,
            'TREE_PROPS'=>array(),
            'OFFERS'=>array(),
            'SKU_PROPS'=>array(),
            'VIEW'=>'',
            'PROPS_CHARS'=>array(),
            'PROP_CHARS'=>array(),
            'CHARS'=>array(
                'ITEMS'=>array(),
                'COUNT'=>0
            ),
            'CHARS_SORT'=>array(),
            'OFFER_DIFF'=>array(),
            'ISOFFERS'=>false,
            'SKU_HIDE_IN_LIST'=>''

        );

        if(isset($arParams["VIEW"]))
            $arResult['VIEW'] = $arParams["VIEW"];
    
        $arResult['TITLE']=CPhoenix::prepareText($arResult['TITLE']);
        $arResult['ALT']=CPhoenix::prepareText($arResult['ALT']);





        if( strlen($arItem["PROPERTIES"]["TITLE_FOR_ACTUAL_PRICE"]["VALUE"]) )
            $arResult["DESC_FOR_ACTUAL_PRICE"] = $arItem["PROPERTIES"]["TITLE_FOR_ACTUAL_PRICE"]["~VALUE"];

        
        


        if($PHOENIX_TEMPLATE_ARRAY['ITEMS']['CATALOG']['ITEMS']['HIDE_PERCENT']['VALUE']['ACTIVE'] === 'Y')
            $arResult['CONFIG']['SHOW_DISCOUNT_PERCENT'] = "";


        if(!empty($arItem["PRICE_MATRIX"]))
        {
            $arResult["PRICE_MATRIX_RESULT"] = CPhoenix::formatPriceMatrix($arItem["PRICE_MATRIX"]);



            $arResult["PRICE_MATRIX_RESULT"]["HTML"] = trim(CPhoenix::showPriceMatrix($arResult["PRICE_MATRIX_RESULT"], $arResult['MEASURE'], $type));

            
            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_AVAILABLE_PRICE_COUNT']['VALUE']['ACTIVE'] === 'Y')
            {

                foreach ($arItem['ITEM_PRICES'] as $key => $arPrice)
                {
                    $arResult['ITEM_PRICES'][$key]['NAME'] = (strlen($arItem["PRICE_MATRIX"]['COLS'][$arPrice['PRICE_TYPE_ID']]['NAME_LANG'])>0)?$arItem["PRICE_MATRIX"]['COLS'][$arPrice['PRICE_TYPE_ID']]['NAME_LANG']:$arItem["PRICE_MATRIX"]['COLS'][$arPrice['PRICE_TYPE_ID']]['NAME'];
                }
            }
        }

        if(isset($arResult['ITEM_PRICES']))
        {
            if(isset($arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]))
                $arResult['PRICE'] = $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']];
            else
                $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']] = $arResult['PRICE'];
        }
        else if(!empty($arItem["MIN_PRICE"]))
        {
            $arResult['PRICE']= $arItem["MIN_PRICE"];
        }
        


        
        $arResult['MIN_QUANTITY'] = $arResult['PRICE']['MIN_QUANTITY'];
        $arResult['SHOW_DISCOUNT'] = floatval($arResult['PRICE']['PERCENT'])>0;

        
        $arResult['SHOW_BTN_BASKET'] = $arResult['CONFIG']['SHOW_BTN_BASKET_OPTION'];

      
        if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"])&& !in_array('Y', $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"]))
        {
            $arResult['CONFIG']['SHOW_BUY_BTN_OPTION'] = ''; 
        }



        $arResult['SHOW_BUY_BTN'] = $arResult['CONFIG']['SHOW_BUY_BTN_OPTION'];
        

        $amount = $arItem['PRODUCT']['QUANTITY'];

        if($arResult["VIEW"] == "GIFTS")
        {
            if($arItem['CATALOG_QUANTITY'])
                $amount = $arItem['CATALOG_QUANTITY'];

            if( $arItem['CATALOG_MEASURE_RATIO'])
            {
                $arResult['MIN_QUANTITY'] = $arResult['STEP_QUANTITY'] = $arItem['CATALOG_MEASURE_RATIO'];
            }
        }


        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["SHOW_RESIDUAL"]["VALUE"]["ACTIVE"] === "Y" && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"]))
            $amount = isset($arParams['PRODUCTS_AMOUNT'][$arResult['ID']])?$arParams['PRODUCTS_AMOUNT'][$arResult['ID']]:0;


        

        CPhoenix::parseProductByAmount($arResult, $amount);
        

        if($arResult['MODE_DISALLOW_ORDER'] === 'Y' || $arResult['SHOWPREORDERBTN'] || $arResult['MODE_ARCHIVE'])
        {
            $arResult['SHOW_BTN_BASKET'] = '';
            $arResult['SHOW_BUY_BTN'] = '';
        }


        if($arResult['SHOW_BTN_BASKET'] === '' && $arResult['SHOW_BUY_BTN'] === '')
            $arResult['SHOW_BTNS'] = 'N';

        
        



        if(!empty($arItem["PROPERTIES"]["CHARS"]["VALUE"]))
        {
            $arResult['PROP_CHARS'] = CPhoenix::getCharacteristicsCatalogItem($arItem["PROPERTIES"]["CHARS"]);
        }

        
        



                

        if($type === 'element')
        {

            if($arParams['DETAIL_PAGE'] === 'Y')
                $arResult['CONFIG']['USE_OID'] = 'Y';

            if (
                CBXFeatures::IsFeatureEnabled('CatCompleteSet')
                && (
                    $arResult['PRODUCT_TYPE'] == CCatalogProduct::TYPE_PRODUCT
                    || $arResult['PRODUCT_TYPE'] == CCatalogProduct::TYPE_SET
                )
            )
            {
                $rsSets = CCatalogProductSet::getList(
                    array(),
                    array(
                        '@OWNER_ID' => $arResult['ID'],
                        '=SET_ID' => 0,
                        '=TYPE' => CCatalogProductSet::TYPE_GROUP
                    ),
                    false,
                    false,
                    array('ID', 'OWNER_ID')
                );
                if ($arSet = $rsSets->Fetch())
                {
                    $arResult['CONFIG']['OFFER_GROUP'] = true;
                }
            }



            $arResult['VISUAL'] = array(
                'ID' => $arItem["OBJ_ID"],
                'NAME'=>$arItem["OBJ_ID"].'_name',
                'TREE' => $arItem["OBJ_ID"].'_skudiv',
                'OLD_PRICE' => $arItem["OBJ_ID"].'_old_price',
                'PRICE' => $arItem["OBJ_ID"].'_price',
                'QUANTITY' => $arItem["OBJ_ID"].'_quantity',
                'QUANTITY_MEASURE' => $arItem["OBJ_ID"].'_quant_measure',
                'DISCOUNT_PRICE' => $arItem["OBJ_ID"].'_price_discount',
                'PRICE_TOTAL' => $arItem["OBJ_ID"].'_price_total',
                'QUANTITY_DOWN' => $arItem["OBJ_ID"].'_quant_down',
                'QUANTITY_UP' => $arItem["OBJ_ID"].'_quant_up',
                'BIG_SLIDER' => $arItem["OBJ_ID"].'_big_slider',
                'BIG_IMG_CONT' => $arItem["OBJ_ID"].'_bigimg_cont',
                'SLIDER_CONT' => $arItem["OBJ_ID"].'_slider_cont',
                'SLIDER_CONT_OF' => $arItem["OBJ_ID"].'_slider_cont_',
                'DISCOUNT_PERCENT' => $arItem["OBJ_ID"].'_dsc_pict',
                'ARTICLE_AVAILABLE' => $arItem["OBJ_ID"].'_article_available',
                'PREVIEW_TEXT' => $arItem["OBJ_ID"].'_preview_text',
                'COMPARE_LINK' => $arItem["OBJ_ID"].'_compare_link',
                'COMPARE' => $arItem["OBJ_ID"].'_compare',
                'PREORDER' => $arItem["OBJ_ID"].'_pre_order',
                'SKU_CHARS' => $arItem["OBJ_ID"].'_sku_chars',
                'PRICE_MATRIX' => $arItem["OBJ_ID"].'_price_matrix',
                'BASKET_ACTIONS' => $arItem["OBJ_ID"].'_basket_actions',
                'ADD2BASKET' => $arItem["OBJ_ID"].'_add2basket',
                'MOVE2BASKET' => $arItem["OBJ_ID"].'_move2basket',
                'FAST_ORDER' => $arItem["OBJ_ID"].'_fast_order',
                'DELAY' => $arItem["OBJ_ID"].'_delay',
                'OFFER_GROUP' => $arItem["OBJ_ID"].'_set_group_',
                'SUBSCRIBE_LINK' => $arItem["OBJ_ID"].'_subscribe_link',
                'SUBSCRIBE'=>$arItem["OBJ_ID"].'_subscribe',
                'CALL_FORM_BETTER_PRICE'=>$arItem["OBJ_ID"].'_call_form_better_price'
            );


            $newArPhotos = array();
            $newArDesc = array();

            if($arItem['DETAIL_PICTURE'])
            {
                $newArPhotos[0] = $arItem['DETAIL_PICTURE']['ID'];
                $newArDesc[0] = CPhoenix::prepareText($arResult['DETAIL_PICTURE']['~DESCRIPTION']);
            }

            if(!empty($arItem['PROPERTIES']['MORE_PHOTO']['VALUE']))
            {
                $newArPhotos = array_merge($newArPhotos, $arItem['PROPERTIES']['MORE_PHOTO']['VALUE']);
                $newArDesc = array_merge($newArDesc, $arItem['PROPERTIES']['MORE_PHOTO']['~DESCRIPTION']);
            }

            if(empty($newArPhotos) && intval($arItem['PREVIEW_PICTURE']['ID']))
            {
                $newArPhotos[0] = $arItem['PREVIEW_PICTURE']['ID'];
                $newArDesc[0] = CPhoenix::prepareText($arResult['PREVIEW_PICTURE']['~DESCRIPTION']);
            
            }


          
            $arResult['MORE_PHOTOS'] = CPhoenixSku::getPhotos($newArPhotos, $PHOENIX_TEMPLATE_ARRAY['ITEMS']['DESIGN']['ITEMS']['PICTURES_QUALITY']['VALUE'], $arParams['WATERMARK'], $newArDesc, $arResult['ALT']);

            if(!empty($arResult['MORE_PHOTOS']))
                $arResult['MORE_PHOTOS_COUNT'] = count($arResult['MORE_PHOTOS']);



            $arResult['PHOTO']=$arResult['MORE_PHOTOS'][0]['MIDDLE']['SRC'];



            if(!empty($PHOENIX_TEMPLATE_ARRAY["SHOW_CHARS_ITEMS_IN_DETAIL"]))
            {
                $arResult["PROPS_CHARS"] = CPhoenix::getPropsCharsCatalogItem($arItem["PROPERTIES"], $PHOENIX_TEMPLATE_ARRAY["SHOW_CHARS_ITEMS_IN_DETAIL"]);
            }

        }
        else
        {
            $arResult['SKU_HIDE_IN_LIST'] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]["SKU_HIDE_IN_LIST"]["VALUE"]["ACTIVE"];
            
            $arResult['VISUAL'] = array(
                'ID' => $arItem["OBJ_ID"],
                'DETAIL_URL_IMG'=>$arItem["OBJ_ID"].'_detail_url_img',
                'NAME'=>$arItem["OBJ_ID"].'_list_name',
                'PICT'=>$arItem["OBJ_ID"].'_list_pict',
                'DELAY' => $arItem["OBJ_ID"].'_list_delay',
                'COMPARE' => $arItem["OBJ_ID"].'_list_compare',
                'PRICE' => $arItem["OBJ_ID"].'_list_price',
                'QUANTITY_MEASURE' => $arItem["OBJ_ID"].'_list_quant_measure',
                'PRICE_MATRIX' => $arItem["OBJ_ID"].'_list_price_matrix',
                'ARTICLE_AVAILABLE' => $arItem["OBJ_ID"].'_list_article_available',
                'TREE' => $arItem["OBJ_ID"].'_skudiv',
                'SHORT_DESCRIPTION'=>$arItem["OBJ_ID"].'_list_short_description',
                'OLD_PRICE' => $arItem["OBJ_ID"].'_list_old_price',
                'PREVIEW_TEXT'=>$arItem["OBJ_ID"].'_list_preview_text',
                'QUANTITY' => $arItem["OBJ_ID"].'_list_quantity',
                'QUANTITY_DOWN' => $arItem["OBJ_ID"].'_list_quant_down',
                'QUANTITY_UP' => $arItem["OBJ_ID"].'_list_quant_up',
                'DISCOUNT_PERCENT' => $arItem["OBJ_ID"].'_list_dsc_pict',
                'BASKET_ACTIONS' => $arItem["OBJ_ID"].'_list_basket_actions',
                'ADD2BASKET' => $arItem["OBJ_ID"].'_list_add2basket',
                'MOVE2BASKET' => $arItem["OBJ_ID"].'_list_move2basket',
                'WR_ADD2BASKET'=> $arItem["OBJ_ID"].'_list_wr2basket',
                'BTN2DETAIL_PAGE_PRODUCT'=>$arItem["OBJ_ID"].'_list_btn2detail_page_product',
                'SKU_CHARS' => $arItem["OBJ_ID"].'_list_sku_chars',
            );


            // photo

                $sizePicture = array(
                    'width'=>290,
                    'height'=>240
                );

                if($arResult["VIEW"] == "LIST")
                {
                    $sizePicture = array(
                        'width'=>350,
                        'height'=>270
                    );
                }

                elseif($arResult["VIEW"] == "TABLE")
                {
                    $sizePicture = array(
                        'width'=>138,
                        'height'=>140
                    );
                }
                elseif($arResult["VIEW"] == "GIFTS")
                {
                    $sizePicture = array(
                        'width'=>90,
                        'height'=>75
                    );
                }
                
                $photo = 0;
                $alt = '';

                if(intval($arItem['PREVIEW_PICTURE']['ID']))
                {
                    $photo = $arItem['PREVIEW_PICTURE']['ID'];
                    if($arItem['PREVIEW_PICTURE']['DESCRIPTION'])
                        $alt = $arItem['PREVIEW_PICTURE']['DESCRIPTION'];
                }

                else if(!empty($arItem['PROPERTIES']['MORE_PHOTO']['VALUE']))
                {
                    $photo = $arItem['PROPERTIES']['MORE_PHOTO']['VALUE'][0];
                    if($arItem['PROPERTIES']['MORE_PHOTO']['DESCRIPTION'][0])
                        $alt = $arItem['PROPERTIES']['MORE_PHOTO']['DESCRIPTION'][0];
                }

                if($photo)
                {
                    if($arItem['PROPERTIES']['RESIZE_IMAGES']['VALUE_XML_ID'] === 'crop')
                    {
                        $arImg = CFile::ResizeImageGet($photo, $sizePicture, BX_RESIZE_IMAGE_EXACT, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY['ITEMS']['DESIGN']['ITEMS']['PICTURES_QUALITY']['VALUE']);
                    }
                    else
                    {
                        $arImg = CFile::ResizeImageGet($photo, $sizePicture, BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY['ITEMS']['DESIGN']['ITEMS']['PICTURES_QUALITY']['VALUE']);
                    }
                    

                    $arResult['PHOTO'] = $arImg['src'];

                    if($alt)
                        $arResult['ALT'] = CPhoenix::prepareText($alt);
                }

            // chars
                if(!empty($PHOENIX_TEMPLATE_ARRAY["SHOW_CHARS_ITEMS_IN_LIST"]))
                {
                    $arResult["PROP_CHARS"] = CPhoenix::getPropsCharsCatalogItem($arItem["PROPERTIES"], $PHOENIX_TEMPLATE_ARRAY["SHOW_CHARS_ITEMS_IN_LIST"]);
                }
        }

        $arResult["CHARS"]["ITEMS"] = array_merge($arResult["PROPS_CHARS"], $arResult["PROP_CHARS"]);

        $arResult["CHARS"]["COUNT"] = (!empty($arResult["CHARS"]["ITEMS"]))?count($arResult["CHARS"]["ITEMS"]):0;


        if($arResult['PRODUCT_TYPE'] === 3)
        {

            if($PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] == "Y")
            {
                $arSKUfields = array();



                if(!empty($arItem["OFFERS"]) && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_']))
                {

                    foreach ($arItem["OFFERS"] as $keyOffer => $arOffer)
                    {


                        if($arSKUfields[$valueProp])
                            continue;


                        foreach ($PHOENIX_TEMPLATE_ARRAY['ITEMS']['CATALOG']['ITEMS']['OFFER_FIELDS']['VALUE_'] as $keyProp => $valueProp)
                        {
                            if($arOffer['PROPERTIES'][$valueProp]['VALUE'])
                                $arSKUfields[$valueProp]=$valueProp;
                        }
                    }
                }


                $arSKU = CPhoenix::GetSkuProperties(
                    array(
                        "IBLOCK_ID" => $arItem['IBLOCK_ID'],
                        "OFFERS" => $arItem['OFFERS'],
                        "SKU_FIELDS" => $arSKUfields,
                        "SITE_ID" => SITE_ID,
                    )
                );

                
                $arResult['SKU_PROPS'] = $arSKU["SKU_PROP_LIST"];
            }


            if($PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] === "Y" && $arResult["HAVEOFFERS"] && !empty($arResult['SKU_PROPS']))
                $arResult['CONFIG']['SHOW_OFFERS'] = true;


            if($PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] === "N" || !$arResult["HAVEOFFERS"] || empty($arResult['SKU_PROPS']))
            {
                
                $arResult['CONFIG']['SHOW_PRICE_SIDE'] = false;

                $arResult['CONFIG']['SHOW_DELAY'] = '';
                $arResult['CONFIG']['SHOW_COMPARE'] = '';
            }


        
            

            if($arResult['CONFIG']["SHOW_OFFERS"])
            {
                if(!empty($arItem["OFFERS"]))
                {

                    if(!empty($arSKU["SKU_MATRIX"]))
                    {
                        foreach ($arSKU["SKU_MATRIX"] as $keyMatrix => $valueMatrix)
                        {

                            if (!isset($arItem['OFFERS'][$keyMatrix]['TREE']))
                            {
                                $arItem['OFFERS'][$keyMatrix]['TREE'] = array();
                                $arItem['OFFERS'][$keyMatrix]['TREE_INFO'] = array();
                            }

                            if(!empty($valueMatrix))
                            {

                                foreach ($valueMatrix as $keyValueMatrix => $valueValueMatrix)
                                {
                                    $arItem['OFFERS'][$keyMatrix]['TREE']['PROP_'.$valueValueMatrix["PROP_ID"]] = $valueValueMatrix['VALUE'];
                                    $arItem['OFFERS'][$keyMatrix]['TREE_INFO'][$valueValueMatrix["PROP_ID"]] = $valueValueMatrix;
                                    $arItem['OFFERS'][$keyMatrix]['SKU_SORT_'.$keyValueMatrix] = $arSKU["SKU_MATRIX"][$keyMatrix][$keyValueMatrix]['SORT'];
                                }

                            }
                        }  
                    }


                    $arIDS = array($arResult['ID']);
                    $intSelected = -1;

                    foreach ($arItem["OFFERS"] as $keyOffer => $arOffer)
                    {


                        $arIDS[] = $arOffer['ID'];

                        if ($arResult['OFFER_ID_SELECTED'] > 0)
                            $foundOffer = ($arResult['OFFER_ID_SELECTED'] == $arOffer['ID']);
                        else
                            $foundOffer = $arOffer['CAN_BUY'];

                        if ($foundOffer && $intSelected == -1)
                            $intSelected = $keyOffer;

                        if ($arOffer['ID']==$_GET['oID'] && !empty($arOffer['TREE']) && is_array($arOffer['TREE']))
                            $intSelected = $keyOffer;
                        
                        unset($foundOffer);


                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MEASURE"]["VALUE"][$arOffer['ITEM_MEASURE']['ID']] == "Y")
                        {
                            $measureHTML = "&nbsp;/&nbsp;".$arOffer['ITEM_MEASURE']['TITLE'];
                            $measure = $arOffer['ITEM_MEASURE']['TITLE'];
                        }

                        $arOffer['DETAIL_PAGE_URL'] = $arResult["DETAIL_PAGE_URL"]."?oID=".$arOffer["ID"];
                        $currency = ($arOffer['ORIGINAL_PARAMETERS']['CURRENCY_ID'])?$arOffer['ORIGINAL_PARAMETERS']['CURRENCY_ID']:Currency\CurrencyManager::getBaseCurrency();

                        if($arOffer['PRODUCT']['QUANTITY_TRACE'] === 'N')
                            $arOffer['PRODUCT']['SUBSCRIBE'] = 'N';

                        $arNewOffer = array(
                            'ID'=>$arOffer['ID'],
                            'NAME'=>$arOffer['NAME'],
                            'NAME_HTML'=>$arOffer['~NAME'],
                            'ARTICLE'=>$arOffer['PROPERTIES']['ARTICLE']['VALUE'],
                            'ARTICLE_HTML'=>$arOffer['PROPERTIES']['ARTICLE']['~VALUE'],
                            'DETAIL_PAGE_URL'=>$arOffer['DETAIL_PAGE_URL'],
                            'CHECK_QUANTITY' =>$arOffer['CHECK_QUANTITY'],
                            'MAX_QUANTITY'=>$arOffer['PRODUCT']["QUANTITY"],
                            'MIN_QUANTITY'=>'',
                            'STEP_QUANTITY'=>$arOffer['ITEM_MEASURE_RATIOS'][$arOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
                            'QUANTITY_FLOAT' => is_float($arOffer['ITEM_MEASURE_RATIOS'][$arOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
                            'QUANTITY'=>array(
                                'TEXT'=>'',
                                'QUANTITY_STYLE'=>'',
                                'QUANTITY'=>'',
                                'QUANTITY_VALUE'=>'',
                                'QUANTITY_WITH_TEXT'=>'',
                                'HTML'=>''
                            ),
                            'MEASURE_HTML'=>$measureHTML,
                            'MEASURE'=>$measure,
                            'CAN_BUY'=>$arOffer['CAN_BUY'],
                            'CAN_BUY_ZERO'=>$arOffer['CATALOG_CAN_BUY_ZERO'],
                            'SUBSCRIBE'=>$arOffer['PRODUCT']['SUBSCRIBE'],
                            'ITEM_PRICE_MODE' => $arOffer['ITEM_PRICE_MODE'],
                            'ITEM_PRICES' => ($arOffer['ITEM_PRICES'])?$arOffer['ITEM_PRICES']:array(),
                            'ITEM_PRICE_SELECTED' => $arOffer['ITEM_PRICE_SELECTED'],
                            'ITEM_QUANTITY_RANGES' => $arOffer['ITEM_QUANTITY_RANGES'],
                            'ITEM_QUANTITY_RANGE_SELECTED' => $arOffer['ITEM_QUANTITY_RANGE_SELECTED'],
                            'ITEM_MEASURE_RATIOS' => $arOffer['ITEM_MEASURE_RATIOS'],
                            'ITEM_MEASURE_RATIO_SELECTED' => $arOffer['ITEM_MEASURE_RATIO_SELECTED'],
                            'PREORDER_ONLY'=>($arOffer['PROPERTIES']['PREORDER_ONLY']['VALUE'])?$arOffer['PROPERTIES']['PREORDER_ONLY']['VALUE']:$arResult['PREORDER_ONLY'],
                            'MODE_DISALLOW_ORDER'=>$arOffer['PROPERTIES']['MODE_DISALLOW_ORDER']['VALUE'],
                            'MODE_ARCHIVE'=>($arResult['MODE_ARCHIVE'] === 'Y')?$arResult['MODE_ARCHIVE']:$arOffer['PROPERTIES']['MODE_ARCHIVE']['VALUE'],
                            'PRICE'=>Array(
                                'NAME'=> NULL,
                                'UNROUND_BASE_PRICE' => NULL,
                                'UNROUND_PRICE' => NULL,
                                'BASE_PRICE' => NULL,
                                'PRICE' => '-1',
                                'CURRENCY' => $currency,
                                'MIN_QUANTITY' => '1'
                            ),
                            'PREVIEW_TEXT'=>(strlen($arOffer["PREVIEW_TEXT"])>0)?$arOffer["PREVIEW_TEXT"]:$arResult["PREVIEW_TEXT"],
                            'PREVIEW_TEXT_HTML'=>(strlen($arOffer["~PREVIEW_TEXT"])>0)?$arOffer["~PREVIEW_TEXT"]:$arResult["PREVIEW_TEXT_HTML"],
                            'SHORT_DESCRIPTION'=>is_array($arOffer['PROPERTIES']['SHORT_DESCRIPTION']['VALUE'])?$arOffer['PROPERTIES']['SHORT_DESCRIPTION']['VALUE']['TEXT']:'',
                            'SHORT_DESCRIPTION_HTML'=>is_array($arOffer['PROPERTIES']['SHORT_DESCRIPTION']['VALUE'])?$arOffer['PROPERTIES']['SHORT_DESCRIPTION']['~VALUE']['TEXT']:'',
                            'ITEMPROP_AVAILABLE'=>'',
                            'MORE_PHOTOS'=> array(),
                            'MORE_PHOTOS_COUNT'=>1,
                            'PHOTO'=>'/bitrix/images/concept.phoenix/ufo.png',
                            'SHOW_DISCOUNT'=>'',
                            'SHOW_BTN_BASKET'=> '',
                            'SHOW_BUY_BTN'=> '',
                            'SHOW_BTNS'=>'Y',
                            'PRICE_MATRIX_RESULT'=>array(),
                            'TREE' => $arOffer['TREE'],
                            'TREE_INFO' => $arOffer['TREE_INFO'],
                            'SKU_LIST_CHARS'=>array(),
                            'ISOFFERS'=>true,
                            'SKU_HIDE_IN_LIST'=>$arResult['SKU_HIDE_IN_LIST']

                        );

                        

                        $skuProps = array();
            

                        if(!empty($PHOENIX_TEMPLATE_ARRAY['SHOW_SKU_ITEMS_IN_DETAIL']))
                        {
                            foreach ($PHOENIX_TEMPLATE_ARRAY['SHOW_SKU_ITEMS_IN_DETAIL'] as $keySkuList => $valueSkuList)
                            {

                                if($arOffer["PROPERTIES"][$valueSkuList]["VALUE"])
                                    $skuProps[] = $arOffer["PROPERTIES"][$valueSkuList]["ID"];
                                
                                
                            }
                        }

                        if(!empty($skuProps))
                            $arNewOffer["SKU_LIST_CHARS"] = CPhoenix::getPropsCharsCatalogItem($arOffer["PROPERTIES"], $skuProps);

                        

                        if($type==='element')
                        {

                            $newArPhotos = array();
                            $newArDesc = array();
                            

                            if($arOffer["DETAIL_PICTURE"])
                            {
                                $newArPhotos[0] = $arOffer["DETAIL_PICTURE"]["ID"];
                                $newArDesc[0] = $arOffer["DETAIL_PICTURE"]["DESCRIPTION"];
                            }

                            if(!empty($arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                            {
                                
				                $newArPhotos = array_merge($newArPhotos, $arOffer['PROPERTIES']['MORE_PHOTO']['VALUE']);
				                $newArDesc = array_merge($newArDesc, $arOffer['PROPERTIES']['MORE_PHOTO']['~DESCRIPTION']);
				            
                            }

                            if(empty($newArPhotos) && intval($arItem['PREVIEW_PICTURE']['ID']) && $arOffer['PROPERTIES']['NO_MERGE_PHOTOS']['VALUE'] === 'Y')
                            {
                                $newArPhotos[0] = $arItem['PREVIEW_PICTURE']['ID'];
                                $newArDesc[0] = CPhoenix::prepareText($arResult['PREVIEW_PICTURE']['~DESCRIPTION']);
                            }


                            $arNewOffer['MORE_PHOTOS'] = CPhoenixSku::getPhotos($newArPhotos, $PHOENIX_TEMPLATE_ARRAY['ITEMS']['DESIGN']['ITEMS']['PICTURES_QUALITY']['VALUE'], $arParams['WATERMARK'], $newArDesc, $arResult['ALT']);


                            if($arOffer['PROPERTIES']['NO_MERGE_PHOTOS']['VALUE'] !== 'Y')
                            {
                                if(!isset($arResult['MORE_PHOTOS'][0]['DEFAULT']))
                                {

                                    if(isset($arNewOffer['MORE_PHOTOS'][0]['DEFAULT']))
                                        $arNewOffer['MORE_PHOTOS'] = array();

                                    $arNewOffer['MORE_PHOTOS'] = array_merge($arNewOffer['MORE_PHOTOS'], $arResult['MORE_PHOTOS']);
                                }
                            }

                            
                            $arNewOffer['PHOTO']=$arNewOffer['MORE_PHOTOS'][0]['MIDDLE']['SRC'];

                            if(!empty($arNewOffer['MORE_PHOTOS']))
                                $arNewOffer['MORE_PHOTOS_COUNT'] = count($arNewOffer['MORE_PHOTOS']);

                            



                        }
                        else
                        {
                            // photo
                                $photo = 0;
                                $alt = '';

                                if(intval($arOffer["PREVIEW_PICTURE"]["ID"]))
                                {
                                    $photo = $arOffer['PREVIEW_PICTURE']['ID'];

                                    if($arOffer['PREVIEW_PICTURE']['DESCRIPTION'])
                                        $alt = $arOffer['PREVIEW_PICTURE']['DESCRIPTION'];

                                }

                                else if(!empty($arOffer['PROPERTIES']['MORE_PHOTO']['VALUE']))
                                {
                                    $photo = $arOffer['PROPERTIES']['MORE_PHOTO']['VALUE'][0];
                                    if($arOffer['PROPERTIES']['MORE_PHOTO']['DESCRIPTION'][0])
                                        $alt = $arOffer['PROPERTIES']['MORE_PHOTO']['DESCRIPTION'][0];
                                }

                                if($photo)
                                {
                                    if($arItem['PROPERTIES']['RESIZE_IMAGES']['VALUE_XML_ID'] === 'crop')
                                        $arImg = CFile::ResizeImageGet($photo, $sizePicture, BX_RESIZE_IMAGE_EXACT, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY['ITEMS']['DESIGN']['ITEMS']['PICTURES_QUALITY']['VALUE']);
                                    else
                                        $arImg = CFile::ResizeImageGet($photo, $sizePicture, BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY['ITEMS']['DESIGN']['ITEMS']['PICTURES_QUALITY']['VALUE']);

                                    $arNewOffer['PHOTO'] = $arImg['src'];

                                    if($alt)
                                        $arNewOffer['ALT'] = CPhoenix::prepareText($alt);
                                }
                                else
                                {
                                    if($arOffer['PROPERTIES']['NO_MERGE_PHOTOS']['VALUE'] !== 'Y')
                                    {
                                        $arNewOffer['PHOTO'] = $arResult['PHOTO'];
                                        $arNewOffer['ALT'] = $arResult['ALT'];
                                    }
                                }
                        }

                        


                        $arPriceTypeID = array();
                        if($arOffer['PRICES'])
                        {
                            foreach($arOffer['PRICES'] as $priceKey => $arOfferPrice)
                            {
                                if($arOfferPrice['CAN_BUY'] == 'Y')
                                    $arPriceTypeID[] = $arOfferPrice['PRICE_ID'];

                                if($arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']])
                                    $arOffer['PRICES'][$priceKey]['GROUP_NAME'] = $arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']];
                            }
                        }

                

                        if(function_exists('CatalogGetPriceTableEx') && (isset($arOffer['PRICE_MATRIX'])) && !$arOffer['PRICE_MATRIX'] && $arPriceTypeID)
                        {

                            $arOffer["PRICE_MATRIX"] = CatalogGetPriceTableEx($arOffer["ID"], 0, $arPriceTypeID, 'Y', $arConvertParams);

                            if(!empty($arOffer["PRICE_MATRIX"]))
                            {
                                $arNewOffer["PRICE_MATRIX_RESULT"] = CPhoenix::formatPriceMatrix($arOffer["PRICE_MATRIX"]);

                                $arNewOffer["PRICE_MATRIX_RESULT"]["HTML"] = trim(CPhoenix::showPriceMatrix($arNewOffer["PRICE_MATRIX_RESULT"], $arNewOffer['MEASURE'], $type));

                                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_AVAILABLE_PRICE_COUNT']['VALUE']['ACTIVE'] === 'Y')

                                {
                                    foreach ($arOffer['ITEM_PRICES'] as $key => $arPrice)
                                    {
                                        $arNewOffer['ITEM_PRICES'][$key]['NAME'] = (strlen($arOffer["PRICE_MATRIX"]['COLS'][$arPrice['PRICE_TYPE_ID']]['NAME_LANG'])>0)?$arOffer["PRICE_MATRIX"]['COLS'][$arPrice['PRICE_TYPE_ID']]['NAME_LANG']:$arOffer["PRICE_MATRIX"]['COLS'][$arPrice['PRICE_TYPE_ID']]['NAME'];
                                    }
                                }
                            }


                            if(count($arOffer['PRICE_MATRIX']['ROWS']) <= 1)
                                $arOffer['PRICE_MATRIX'] = '';
                        }
                        

                        if(isset($arOffer['ITEM_PRICES']))
                        {
                            if(isset($arNewOffer['ITEM_PRICES'][$arNewOffer['ITEM_PRICE_SELECTED']]))
                                $arNewOffer['PRICE'] = $arNewOffer['ITEM_PRICES'][$arNewOffer['ITEM_PRICE_SELECTED']];
                            else
                            {
                                $arNewOffer['ITEM_PRICES'][$arNewOffer['ITEM_PRICE_SELECTED']] = $arNewOffer['PRICE'];
                            }
                        }
                        else if(!empty($arOffer['MIN_PRICE']))
                        {
                            $arNewOffer['PRICE'] = $arOffer['MIN_PRICE'];
                        }



                        $arNewOffer['MIN_QUANTITY'] = $arNewOffer['PRICE']['MIN_QUANTITY'];
                        $arNewOffer['SHOW_DISCOUNT'] = floatval($arNewOffer['PRICE']['PERCENT'])>0;


                        
                        $amount = $arOffer['PRODUCT']['QUANTITY'];

                        if($arResult["VIEW"] == "GIFTS")
                        {
                            if($arOffer['CATALOG_QUANTITY'])
                                $amount = $arOffer['CATALOG_QUANTITY'];

                            if( $arOffer['CATALOG_MEASURE_RATIO'])
                            {
                                $arNewOffer['MIN_QUANTITY'] = $arNewOffer['STEP_QUANTITY'] = $arOffer['CATALOG_MEASURE_RATIO'];
                            }
                        }


                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["SHOW_RESIDUAL"]["VALUE"]["ACTIVE"] === "Y" && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"]))
                            $amount = isset($arParams['PRODUCTS_AMOUNT'][$arNewOffer['ID']])?$arParams['PRODUCTS_AMOUNT'][$arNewOffer['ID']]:0;
                        



                        CPhoenix::parseProductByAmount($arNewOffer, $amount);

                        $arNewOffer['SHOW_BTN_BASKET'] = $arResult['CONFIG']['SHOW_BTN_BASKET_OPTION'];
                        $arNewOffer['SHOW_BUY_BTN'] = $arResult['CONFIG']['SHOW_BUY_BTN_OPTION'];



                        if($arNewOffer['MODE_DISALLOW_ORDER'] === 'Y' || $arNewOffer['SHOWPREORDERBTN'] || $arNewOffer['MODE_ARCHIVE'] || $arNewOffer['SKU_HIDE_IN_LIST'] === 'Y')
                        {
                            $arNewOffer['SHOW_BTN_BASKET'] = '';
                            $arNewOffer['SHOW_BUY_BTN'] = '';
                        }

                        if(     $arResult['PREORDER_ONLY'] === 'Y'
                            ||  $arResult['MODE_DISALLOW_ORDER'] === 'Y' 
                            ||  $arResult['MODE_ARCHIVE'] === 'Y'
                        )
                        {
                            $arNewOffer['SHOW_BTN_BASKET'] = '';
                            $arNewOffer['SHOW_BUY_BTN'] = '';
                        }

                        if(($arNewOffer['SHOW_BTN_BASKET'] === '' && $arNewOffer['SHOW_BUY_BTN'] === '') || $arParams["VIEW"] === "FLAT SLIDER")
                            $arNewOffer['SHOW_BTNS'] = 'N';

                          

                        $arResult["OFFERS"][$keyOffer] = $arNewOffer;

                    }

                    if (-1 == $intSelected)
                        $intSelected = 0;

                    $arResult["OFFER_ID_SELECTED"] = $intSelected;

                   
                    $offerSet = array();

                    if (!empty($arIDS) && CBXFeatures::IsFeatureEnabled('CatCompleteSet'))
                    {
                        $offerSet = array_fill_keys($arIDS, false);
                        $rsSets = CCatalogProductSet::getList(
                            array(),
                            array(
                                '@OWNER_ID' => $arIDS,
                                '=SET_ID' => 0,
                                '=TYPE' => CCatalogProductSet::TYPE_GROUP
                            ),
                            false,
                            false,
                            array('ID', 'OWNER_ID')
                        );
                        while ($arSet = $rsSets->Fetch())
                        {
                            $arSet['OWNER_ID'] = (int)$arSet['OWNER_ID'];
                            $offerSet[$arSet['OWNER_ID']] = true;
                            $arResult['CONFIG']['OFFER_GROUP'] = true;
                        }
                        if ($offerSet[$arResult['ID']])
                        {
                            foreach ($offerSet as &$setOfferValue)
                            {
                                if ($setOfferValue === false)
                                {
                                    $setOfferValue = true;
                                }
                            }
                            unset($setOfferValue);
                            unset($offerSet[$arResult['ID']]);
                        }
                        if ($arResult['CONFIG']['OFFER_GROUP'])
                        {
                            $offerSet = array_filter($offerSet);
                            $arResult['CONFIG']['OFFER_GROUP_VALUES'] = array_keys($offerSet);
                        }
                    }

    

                    $arResult['OFFER_DIFF'] = CPhoenix::getMinPriceFromOffersExt(
                        $arResult["OFFERS"]
                    );



                    $arResult['OFFER_DIFF']['VALUE']['HTML'] = ($arResult['OFFER_DIFF']['DIFF']?$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_PREFIX_FROM"]:"").$arResult['OFFER_DIFF']['VALUE']['PRINT_PRICE'];



                    $skuProps = array();
                    foreach ($arResult['SKU_PROPS'] as $skuProperty)
                    {
                        
                        $skuProps[] = array(
                            'ID' => $skuProperty['ID'],
                            'NAME' => $skuProperty['NAME'],
                            'VALUES' => $skuProperty['VALUES'],
                        );
                    }


                    $arResult['TREE_PROPS']=$skuProps;
                }

            }
            else
            {
                $arResult["OFFERS"][] = $arResult;
            }


        }


       


        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['PROPS_IN_LIST_FOR_'.$arResult['VIEW']]['VALUE']['CHARS'] === 'Y' || $type === 'element')
        {

            $arSortChars = array();
       
            if(!empty($arResult['PROPS_CHARS']))
            {
                $arSortChars["props_chars"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_SORT_PROPS_CHARS']["VALUE"];
            }
         
            if(!empty($arResult['PROP_CHARS']))
                $arSortChars["prop_chars"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_SORT_PROP_CHARS']["VALUE"];
         

            if($arResult['CONFIG']["SHOW_OFFERS"] && $arResult['HAVEOFFERS'])
                $arSortChars["sku_chars"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_SORT_SKU_CHARS']["VALUE"];

            if(!empty($arSortChars))
            {
                uasort($arSortChars, "CPhoenix::cmp");
                $arResult["CHARS_SORT"] = $arSortChars;
            }

        }


        return $arResult;
    }

    public static function cmp($a, $b){
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    public static function parseProductByAmount(&$arItem, $productAmount = 0)
    {

        global $PHOENIX_TEMPLATE_ARRAY;

        
        $preBtn = false;

       

        if($arItem['PREORDER_ONLY']==='Y' && $PHOENIX_TEMPLATE_ARRAY['ITEMS']['CATALOG']['ITEMS']['MODE_PREORDER_FORM']['VALUE'] != 'N')
            $preBtn = true;

        else
            $preBtn = $arItem['GLOBAL_SHOWPREORDERBTN'] && !$arItem['CAN_BUY'];


        if($arItem['MODE_ARCHIVE']==='Y')
        {
            $quantityStyle = 'simple';
            $quantityText = $PHOENIX_TEMPLATE_ARRAY['MESS']['CATALOG_MODE_ARCHIVE'];
            $preBtn = false;
        }
        else if($preBtn)
        {
            $quantityStyle = 'simple';
            $quantityText = $PHOENIX_TEMPLATE_ARRAY['MESS']['CATALOG_PREORDER_ONLY'];
        }


        if($PHOENIX_TEMPLATE_ARRAY['ITEMS']['CATALOG']['ITEMS']['STORE_QUANTITY_ON']['VALUE']['ACTIVE'] == 'Y')
        {

            $arItem['MAX_QUANTITY'] = $productAmount;


            $arItem['QUANTITY'] = CPhoenixSku::getDescQuantity($arItem['MAX_QUANTITY'], $arItem['MEASURE'], !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"]), array('TEXT'=>$quantityText, 'QUANTITY_STYLE'=>$quantityStyle));


            if($arItem['QUANTITY']['QUANTITY_VALUE']>0)
            {
                $arItem["ITEMPROP_AVAILABLE"] = "InStock";
                $arItem['CAN_BUY'] = true;
            }

            else
            {
                if($arItem['CAN_BUY_ZERO']==='N')
                    $arItem['CAN_BUY'] = false;


                $arItem["ITEMPROP_AVAILABLE"] = "OutOfStock";
            }
            if($arItem['PRICE']['PRICE']==='-1' || ($arItem['CAN_BUY_ZERO']==='N' && floatval($arItem['MAX_QUANTITY'])<floatval($arItem['MIN_QUANTITY'])))
                $arItem['CAN_BUY'] = false;


        
            if($arItem['PREORDER_ONLY']!='Y' && $arItem['MODE_ARCHIVE']!=='Y' && $arItem['GLOBAL_SHOWPREORDERBTN'])
            {


                $preBtn = !$arItem['CAN_BUY'];

                if($preBtn)
                {
                    $quantityStyle = 'simple';
                    $quantityText = $PHOENIX_TEMPLATE_ARRAY['MESS']['CATALOG_PREORDER_ONLY'];
                }

                $arItem['QUANTITY'] = CPhoenixSku::getDescQuantity($arItem['MAX_QUANTITY'], $arItem['MEASURE'], !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"]), array('TEXT'=>$quantityText, 'QUANTITY_STYLE'=>$quantityStyle));

            }

        }
        else
        {

            if($arItem['MODE_ARCHIVE']==='Y')
            {
                $quantityStyle = 'simple';
                $quantityText = $PHOENIX_TEMPLATE_ARRAY['MESS']['CATALOG_MODE_ARCHIVE'];
                $preBtn = false;

            }
            else if($preBtn)
            {
                $quantityStyle = 'simple';
                $quantityText = $PHOENIX_TEMPLATE_ARRAY['MESS']['CATALOG_PREORDER_ONLY'];
            }

            if($arItem['MODE_ARCHIVE']==='Y' || $preBtn)
            {
                $arItem['QUANTITY'] = CPhoenixSku::getDescQuantity($arItem['MAX_QUANTITY'], $arItem['MEASURE'], !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"]), array('TEXT'=>$quantityText, 'QUANTITY_STYLE'=>$quantityStyle));
            }
        }

        if($preBtn)
            $arItem['ITEMPROP_AVAILABLE'] = 'PreOrder';



        $arItem['SHOWPREORDERBTN'] = $preBtn;


    }

    public static function prepareObjJs($arItem =array(), $prefix=''){

        $res = array();

        if(!empty($arItem))
        {
            foreach ($arItem as $key => $value)
            {
                $res[$key]=$value.$prefix;
            }
        }

        return $res;
    }

    public static function prepareText($text, $quotes = true){

        global $PHOENIX_TEMPLATE_ARRAY;

        $arSearch = array(
            "\n",
            "\r", 
            "#phoenix.location#", 
            "#phoenix.region#"
        );

        $arReplacement = array(
            " ", 
            " ", 
            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["NAME"], 
            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["REGION_NAME"]
        );

        if($quotes === true)
        {
            $arSearch[] = "'";
            $arSearch[] = '"';

            $arReplacement[] = "";
            $arReplacement[] = "";
        }

        return str_replace($arSearch, $arReplacement, trim(strip_tags(htmlspecialcharsBack($text))));
    }

    public static function admin_setting_custom($arParams=array()){

        global $PHOENIX_TEMPLATE_ARRAY;
    ?>
        <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"] && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MODE_FAST_EDIT"]['VALUE']["ACTIVE"] == 'Y'):?>

              <div class="tool-settings <?=$arParams["CLASS"]?>">
                  <a href='/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$arParams['IBLOCK_ID']?>&type=<?=$arParams['IBLOCK_TYPE_ID']?>&ID=<?=$arParams['ID']?>&find_section_section=<?=$arParams['IBLOCK_SECTION_ID']?>&WF=Y' class="tool-settings" data-toggle="tooltip" target="_blank" data-placement="right" title="<?=Loc::GetMessage("KRAKEN_PAGE_GENERATOR_EDIT")?> &quot;<?=TruncateText($arParams["NAME"], 35)?>&quot;"></a>

              </div>


        <?endif;?>

    <?}

    public static function isLowLicense(){
        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/update_client.php");
        $stableVersionsOnly = COption::GetOptionString("main", "stable_versions_only", "Y");
        $errorMessage = "";

        if ($arUpdateList = CUpdateClient::GetUpdatesList($errorMessage, LANG, $stableVersionsOnly))
        {
            if(in_array(strtolower($arUpdateList["CLIENT"][0]["@"]["LICENSE"]), array(
                GetMessage("CPHX_LICENSE_1"),
                GetMessage("CPHX_LICENSE_2"),
                GetMessage("CPHX_LICENSE_3"))))
                return true;
        }

        return false;
    }

    public static function createVideo($url){

        $videoSrc = $out = array();

        if(substr_count($url, "youtube.com") > 0)
        {
              preg_match("/^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/", $url, $out);
        }

        else if(substr_count($url, "youtu.be") > 0)
        {
              preg_match("/^https:\/\/youtu\.be\/(((\w|-){11}))(?:\S+)?$/", $url, $out);
        }

        if(isset($out[1])&&strlen($out[1])>0)
        {
              $videoSrc = array(
                    "SRC" => "https://www.youtube.com/embed/".$out[1]."?rel=0&amp;mute=1&amp;controls=0&amp;loop=1&amp;showinfo=0&amp;autoplay=1&amp;playlist=".$out[1],
                    "ID" => $out[1],
                    "HTML" =>'<iframe allowfullscreen="" frameborder="0" height="100%" src="https://www.youtube.com/embed/'.$out[1].'?rel=0&amp;controls=1&amp;showinfo=0" width="100%"></iframe>'
              );
        }

        return $videoSrc;
    }

    public static function setMainMess(){


        global $PHOENIX_TEMPLATE_ARRAY;

        
        if(defined('SITE_TEMPLATE_PATH'))
        {

            if(file_exists($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/custom_lang/".LANGUAGE_ID."_lang.php"))
            {
                __IncludeLang($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/custom_lang/".LANGUAGE_ID."_lang.php");
            }
        }

        $PHOENIX_TEMPLATE_ARRAY["MESS"] = Array(
            "COMMENTS_ALERT_AUTH" => getMessage("PHOENIX_MESS_COMMENTS_ALERT_AUTH"),
            "VIEWED_PRODUCTS_TITLE_BLOCK" => getMessage("PHOENIX_MESS_VIEWED_PRODUCTS_TITLE_BLOCK"),
            "SALE_ORDER_ALERT" => getMessage("PHOENIX_MESS_SALE_ORDER_ALERT"),
            "CATALOG_DELAY_TITLE" => getMessage("PHOENIX_MESS_CATALOG_DELAY_TITLE"),
            "CATALOG_COMPARE_TITLE" => getMessage("PHOENIX_MESS_CATALOG_COMPARE_TITLE"),
            "CATALOG_STORE_AMOUNT_NAME_BACK2LIST" => getMessage("PHOENIX_MESS_CATALOG_STORE_AMOUNT_NAME_BACK2LIST"),
            "CATALOG_STORE_AMOUNT_TITLE_SECTION" => getMessage("PHOENIX_MESS_CATALOG_STORE_AMOUNT_TITLE_SECTION"),
            "CATALOG_STORE_AMOUNT_TITLE_BTN_LIST" => getMessage("PHOENIX_MESS_CATALOG_STORE_AMOUNT_TITLE_BTN_LIST"),
            "CATALOG_STORE_AMOUNT_TITLE_BTN_FLAT" => getMessage("PHOENIX_MESS_CATALOG_STORE_AMOUNT_TITLE_BTN_FLAT"),
            "CATALOG_STORE_AMOUNT_TITLE_PHONE" => getMessage("PHOENIX_MESS_CATALOG_STORE_AMOUNT_TITLE_PHONE"),
            "CATALOG_STORE_AMOUNT_TITLE_EMAIL" => getMessage("PHOENIX_MESS_CATALOG_STORE_AMOUNT_TITLE_EMAIL"),
            "CATALOG_STORE_AMOUNT_TITLE_SCHEDULE" => getMessage("PHOENIX_MESS_CATALOG_STORE_AMOUNT_TITLE_SCHEDULE"),
            "CATALOG_MODE_ARCHIVE" => getMessage("PHOENIX_MESS_CATALOG_MODE_ARCHIVE"),
            "CATALOG_PREORDER_ONLY" => getMessage("PHOENIX_MESS_CATALOG_PREORDER_ONLY"),
            "SUBSCRIBE_PRODUCT_POPUP_TITLE" => getMessage("PHOENIX_MESS_SUBSCRIBE_PRODUCT_POPUP_TITLE"),
            "SUBSCRIBE_PRODUCT_BUTTON_CLOSE" => getMessage("PHOENIX_MESS_SUBSCRIBE_PRODUCT_BUTTON_CLOSE"),
            "SUBSCRIBE_PRODUCT_MANY_CONTACT_NOTIFY" => getMessage("PHOENIX_MESS_SUBSCRIBE_PRODUCT_MANY_CONTACT_NOTIFY"),
            "SUBSCRIBE_PRODUCT_LABLE_CONTACT_INPUT" => getMessage("PHOENIX_MESS_SUBSCRIBE_PRODUCT_LABLE_CONTACT_INPUT"),
            "SUBSCRIBE_PRODUCT_VALIDATE_UNKNOW_ERROR" => getMessage("PHOENIX_MESS_SUBSCRIBE_PRODUCT_VALIDATE_UNKNOW_ERROR"),
            "SUBSCRIBE_PRODUCT__VALIDATE_ERROR_EMPTY_FIELD" => getMessage("PHOENIX_MESS_SUBSCRIBE_PRODUCT_VALIDATE_ERROR_EMPTY_FIELD"),
            "SUBSCRIBE_PRODUCT_VALIDATE_ERROR" => getMessage("PHOENIX_MESS_SUBSCRIBE_PRODUCT_VALIDATE_ERROR"),
            "SUBSCRIBE_PRODUCT_CAPTCHA_TITLE" => getMessage("PHOENIX_MESS_SUBSCRIBE_PRODUCT_CAPTCHA_TITLE"),
            "SUBSCRIBE_PRODUCT_STATUS_SUCCESS" => getMessage("PHOENIX_MESS_SUBSCRIBE_PRODUCT_STATUS_SUCCESS"),
            "SUBSCRIBE_PRODUCT_STATUS_ERROR" => getMessage("PHOENIX_MESS_SUBSCRIBE_PRODUCT_STATUS_ERROR"),
            "SUBSCRIBE_PRODUCT_ENTER_WORD_PICTURE" => getMessage("PHOENIX_MESS_SUBSCRIBE_PRODUCT_ENTER_WORD_PICTURE"),
            "SUBSCRIBE_PRODUCT_POPUP_SUBSCRIBED_TEXT" => getMessage("PHOENIX_MESS_SUBSCRIBE_PRODUCT_POPUP_SUBSCRIBED_TEXT"),
            "SMART_FILTER_MORE_BTN" => getMessage("PHOENIX_MESS_SMART_FILTER_MORE_BTN"),
            "PREDICTION_DESCRIPTION" => getMessage("PHOENIX_MESS_PREDICTION_DESCRIPTION"),
            "SET_CONSTRUCTOR_OTHER_SECTION" => getMessage("PHOENIX_MESS_SET_CONSTRUCTOR_OTHER_SECTION"),
            "SET_CONSTRUCTOR_ALERT" => getMessage("PHOENIX_MESS_SET_CONSTRUCTOR_ALERT"),
            "SET_CONSTRUCTOR_CUSTOM_COMPLECT" => getMessage("PHOENIX_MESS_SET_CONSTRUCTOR_CUSTOM_COMPLECT"),
            "SET_CONSTRUCTOR_COMPLITED" => getMessage("PHOENIX_MESS_SET_CONSTRUCTOR_COMPLITED"),
            "SET_CONSTRUCTOR_COUNT_PRODUCTS" => getMessage("PHOENIX_MESS_SET_CONSTRUCTOR_COUNT_PRODUCTS"),
            "SET_CONSTRUCTOR_ADD2BASKET_BTN" => getMessage("PHOENIX_MESS_SET_CONSTRUCTOR_ADD2BASKET_BTN"),
            "SET_CONSTRUCTOR_HIDE" => getMessage("PHOENIX_MESS_SET_CONSTRUCTOR_HIDE"),
            "SET_CONSTRUCTOR_ADD2COMPLECT" => getMessage("PHOENIX_MESS_SET_CONSTRUCTOR_ADD2COMPLECT"),
            "SET_CONSTRUCTOR_CNT_1" => getMessage("PHOENIX_MESS_SET_CONSTRUCTOR_CNT_1"),
            "SET_CONSTRUCTOR_CNT_2" => getMessage("PHOENIX_MESS_SET_CONSTRUCTOR_CNT_2"),
            "SET_CONSTRUCTOR_CNT_3" => getMessage("PHOENIX_MESS_SET_CONSTRUCTOR_CNT_3"),
            "SET_CONSTRUCTOR_CNT_4" => getMessage("PHOENIX_MESS_SET_CONSTRUCTOR_CNT_4"),
            "SETTING_PRODUCT_MAIN_TITLE" => getMessage("PHOENIX_MESS_SETTING_PRODUCT_MAIN_TITLE"),
            "SET_PRODUCT_BTN_NAME" => getMessage("PHOENIX_MESS_SET_PRODUCT_BTN_NAME"),
            "SET_PRODUCT_BTN_NAME_2" => getMessage("PHOENIX_MESS_SET_PRODUCT_BTN_NAME_2"),
            "REG_ERROR_EMAIL" => getMessage("PHOENIX_MESS_REG_ERROR_EMAIL"),
            "RETURN_BACK" => getMessage("PHOENIX_MESS_RETURN_BACK"),
            "ORDER_STEP_TO_ORDER" => getMessage("PHOENIX_ORDER_STEP_TO_ORDER"),
            "ORDER_PICKUP_BTN_DETAIL_HIDE" => getMessage("PHOENIX_ORDER_PICKUP_BTN_DETAIL_HIDE"),
            "ORDER_PICKUP_BTN_DETAIL" => getMessage("PHOENIX_ORDER_PICKUP_BTN_DETAIL"),
            "ORDER_SIDE_SUM_PRODUCTS_WEIGTH" => getMessage("PHOENIX_ORDER_SIDE_SUM_PRODUCTS_WEIGTH"),
            "ORDER_SIDE_SUM_PRODUCTS" => getMessage("PHOENIX_ORDER_SIDE_SUM_PRODUCTS"),
            "ORDER_SIDE_DISCOUNT_SUM_PRODUCTS" => getMessage("PHOENIX_ORDER_SIDE_DISCOUNT_SUM_PRODUCTS"),
            "ORDER_SIDE_SUM_DELIVERY" => getMessage("PHOENIX_ORDER_SIDE_SUM_DELIVERY"),
            "ORDER_USER_DESCRIPTION" => getMessage("PHOENIX_MESS_ORDER_USER_DESCRIPTION"),
            "ORDER_SECTION_TITLE_PROPS" => getMessage("PHOENIX_MESS_ORDER_SECTION_TITLE_PROPS"),
            "ORDER_SECTION_TITLE_DELIVERY" => getMessage("PHOENIX_MESS_ORDER_SECTION_TITLE_DELIVERY"),
            "ORDER_SECTION_TITLE_PAYSYSTEM" => getMessage("PHOENIX_MESS_ORDER_SECTION_TITLE_PAYSYSTEM"),
            "ORDER_SECTION_TITLE_REGION" => getMessage("PHOENIX_MESS_ORDER_SECTION_TITLE_REGION"),
            "DELIVERY_SHOW_MORE" => getMessage("PHOENIX_MESS_DELIVERY_SHOW_MORE"),
            "DELIVERY_LOCATION_TITLE" => getMessage("PHOENIX_MESS_DELIVERY_LOCATION_TITLE"),
            "INNER_PAY_SYSTEM_TITLE" => getMessage("PHOENIX_MESS_INNER_PAY_SYSTEM_TITLE"),
            "RATING_NAME" => getMessage("PHOENIX_MESS_RATING_NAME"),
            "FOUND_CHEAPER" => getMessage("PHOENIX_MESS_FOUND_CHEAPER"),
            "ARTICLE" => getMessage("PHOENIX_ARTICLE"),
            "ARTICLE_SHORT" => getMessage("PHOENIX_ARTICLE_SHORT"),
            "SHOW_ALL" => getMessage("PHOENIX_MESS_SHOW_ALL"),
            "SHOW_ALL_GOODS" => getMessage("PHOENIX_MESS_SHOW_ALL_GOODS"),
            "SHOW_ALL_GOODS_SEARCH" => getMessage("PHOENIX_MESS_SHOW_ALL_GOODS_SEARCH"),
            "SHOW_ALL_CATEGORIES" => getMessage("PHOENIX_MESS_SHOW_ALL_CATEGORIES"),
            "SHOW_ALL_BRANDS" => getMessage("PHOENIX_MESS_SHOW_ALL_BRANDS"),
            "SHOW_ALL_NEWS" => getMessage("PHOENIX_MESS_SHOW_ALL_NEWS"),
            "SHOW_ALL_ACTIONS" => getMessage("PHOENIX_MESS_SHOW_ALL_ACTIONS"),
            "SHOW_ALL_BLOG" => getMessage("PHOENIX_MESS_SHOW_ALL_BLOG"),
            "SBB_BASKET_FILTER" => getMessage("PHOENIX_MESS_SBB_BASKET_FILTER"),
            "MORE" => getMessage("PHOENIX_MESS_MORE"),
            "MORE_EMPLOYEE" => getMessage("PHOENIX_MESS_MORE_EMPLOYEE"),
            "OTHER_CATEGORY" => getMessage("PHOENIX_MESS_OTHER_CATEGORY"),
            "FILTER_AVAILABLE" => getMessage("PHOENIX_MESS_FILTER_AVAILABLE"),
            "SORT_CATALOG_SORT" => getMessage("PHOENIX_MESS_SORT_CATALOG_SORT"),
            "SORT_CATALOG_PRICE" => getMessage("PHOENIX_MESS_SORT_CATALOG_PRICE"),
            "SORT_CATALOG_NAME" => getMessage("PHOENIX_MESS_SORT_CATALOG_NAME"),
            "VIEW_CATALOG_LIST_FLAT" => getMessage("PHOENIX_MESS_VIEW_CATALOG_LIST_FLAT"),
            "VIEW_CATALOG_LIST_LIST" => getMessage("PHOENIX_MESS_VIEW_CATALOG_LIST_LIST"),
            "VIEW_CATALOG_LIST_TABLE" => getMessage("PHOENIX_MESS_VIEW_CATALOG_LIST_TABLE"),
            "CATALOG_HIDE_COLUMN" => getMessage("PHOENIX_MESS_CATALOG_HIDE_COLUMN"),
            "CATALOG_SHOW_COLUMN" => getMessage("PHOENIX_MESS_CATALOG_SHOW_COLUMN"),
            "CATALOG_PREFIX_FROM" => getMessage("PHOENIX_MESS_CATALOG_PREFIX_FROM"),
            "CATALOG_TO_DETAIL" => getMessage("PHOENIX_MESS_CATALOG_TO_DETAIL"),
            "MOB_MENU_SHOW_CONTACTS" => getMessage("PHOENIX_MESS_MOB_MENU_SHOW_CONTACTS"),
            "SHOW_CHARACTERISTICS" => getMessage("PHOENIX_MESS_SHOW_CHARACTERISTICS"),
            "SKROLL_TO_CHARACTERISTICS" => getMessage("PHOENIX_MESS_SKROLL_TO_CHARACTERISTICS"),
            "VIDEO" => getMessage("PHOENIX_MESS_VIDEO"),
            "ECONOMY" => getMessage("PHOENIX_MESS_ECONOMY"),
            "CATALOG_CARD_LEVEL_UP" => getMessage("PHOENIX_MESS_CATALOG_CARD_LEVEL_UP"),
            "BRANDS_LEVEL" => getMessage("PHOENIX_MESS_BRANDS_LEVEL"),
            "CATALOG_CARD_KNOW_MORE" => getMessage("PHOENIX_MESS_CATALOG_CARD_KNOW_MORE"),
            "CATALOG_CARD_SHOW_ALL" => getMessage("PHOENIX_MESS_CATALOG_CARD_SHOW_ALL"),
            "CATEGORY_NEWS" => getMessage("PHOENIX_MESS_CATEGORY_NEWS"),
            "CATEGORY_BLOG" => getMessage("PHOENIX_MESS_CATEGORY_BLOG"),
            "CATEGORY_ACTIONS" => getMessage("PHOENIX_MESS_CATEGORY_ACTIONS"),
            "CATALOG_DETAIL_BRAND_CATALOG_LINK" => getMessage("PHOENIX_MESS_CATALOG_DETAIL_BRAND_CATALOG_LINK"),
            "CATALOG_DETAIL_BRAND_DETAIL_LINK" => getMessage("PHOENIX_MESS_CATALOG_DETAIL_BRAND_DETAIL_LINK"),
            "SEARCH_RESULT_NOT_FOUND" => getMessage("PHOENIX_MESS_SEARCH_RESULT_NOT_FOUND"),
            "SEARCH_COMPACT_FOUND_0" => getMessage("PHOENIX_MESS_SEARCH_COMPACT_FOUND_0"),
            "SEARCH_COMPACT_FOUND_1" => getMessage("PHOENIX_MESS_SEARCH_COMPACT_FOUND_1"),
            "SEARCH_COMPACT_FOUND_AFTER" => getMessage("PHOENIX_MESS_SEARCH_COMPACT_FOUND_AFTER"),
            "SEARCH_COMPACT_RESULT_0" => getMessage("PHOENIX_MESS_SEARCH_COMPACT_RESULT_0"),
            "SEARCH_COMPACT_RESULT_1" => getMessage("PHOENIX_MESS_SEARCH_COMPACT_RESULT_1"),
            "SEARCH_COMPACT_RESULT_2" => getMessage("PHOENIX_MESS_SEARCH_COMPACT_RESULT_2"),
            "CATALOG_CNT_1" => getMessage("PHOENIX_MESS_CATALOG_CNT_1"),
            "CATALOG_CNT_2" => getMessage("PHOENIX_MESS_CATALOG_CNT_2"),
            "CATALOG_CNT_3" => getMessage("PHOENIX_MESS_CATALOG_CNT_3"),
            "CATALOG_CNT_4" => getMessage("PHOENIX_MESS_CATALOG_CNT_4"),
            "LABEL_new" => getMessage("PHOENIX_MESS_LABEL_new"),
            "LABEL_act" => getMessage("PHOENIX_MESS_LABEL_act"),
            "LABEL_pop" => getMessage("PHOENIX_MESS_LABEL_pop"),
            "LABEL_rec" => getMessage("PHOENIX_MESS_LABEL_rec"),
            "SEARCH_MAIN_FOUND_0" => getMessage("PHOENIX_MESS_SEARCH_MAIN_FOUND_0"),
            "SEARCH_MAIN_FOUND_1" => getMessage("PHOENIX_MESS_SEARCH_MAIN_FOUND_1"),
            "SEARCH_MAIN_FOUND_AFTER" => getMessage("PHOENIX_MESS_SEARCH_MAIN_FOUND_AFTER"),
            "SEARCH_MAIN_RESULT_0" => getMessage("PHOENIX_MESS_SEARCH_MAIN_RESULT_0"),
            "SEARCH_MAIN_RESULT_1" => getMessage("PHOENIX_MESS_SEARCH_MAIN_RESULT_1"),
            "SEARCH_MAIN_RESULT_2" => getMessage("PHOENIX_MESS_SEARCH_MAIN_RESULT_2"),
            "SEARCH_MAIN_CATALOG" => getMessage("PHOENIX_MESS_SEARCH_MAIN_CATALOG"),
            "SEARCH_MAIN_NEWS" => getMessage("PHOENIX_MESS_SEARCH_MAIN_NEWS"),
            "SEARCH_MAIN_BLOG" => getMessage("PHOENIX_MESS_SEARCH_MAIN_BLOG"),
            "SEARCH_MAIN_ACTIONS" => getMessage("PHOENIX_MESS_SEARCH_MAIN_ACTIONS"),
            "SEARCH_MAIN_CATALOG_TIT" => getMessage("PHOENIX_MESS_SEARCH_MAIN_CATALOG_TIT"),
            "SEARCH_MAIN_NEWS_TIT" => getMessage("PHOENIX_MESS_SEARCH_MAIN_NEWS_TIT"),
            "SEARCH_MAIN_BLOG_TIT" => getMessage("PHOENIX_MESS_SEARCH_MAIN_BLOG_TIT"),
            "SEARCH_MAIN_ACTIONS_TIT" => getMessage("PHOENIX_MESS_SEARCH_MAIN_ACTIONS_TIT"),
            "SEARCH_MAIN_START" => getMessage("PHOENIX_MESS_SEARCH_MAIN_START"),
            "SEARCH_HINT" => getMessage("PHOENIX_MESS_SEARCH_HINT"),
            "SEARCH_IN" => getMessage("PHOENIX_MESS_SEARCH_IN"),
            "SEARCH_ALL" => getMessage("PHOENIX_MESS_SEARCH_ALL"),
            "SEARCH_IN_CATALOG" => getMessage("PHOENIX_MESS_SEARCH_IN_CATALOG"),
            "SEARCH_IN_BLOG" => getMessage("PHOENIX_MESS_SEARCH_IN_BLOG"),
            "SEARCH_IN_NEWS" => getMessage("PHOENIX_MESS_SEARCH_IN_NEWS"),
            "SEARCH_IN_ACTIONS" => getMessage("PHOENIX_MESS_SEARCH_IN_ACTIONS"),
            "SEARCH_BUTTON_NAME" => getMessage("PHOENIX_MESS_SEARCH_BUTTON_NAME"),
            "SEARCH_FIX_HEADER_PLACEHOLDER" => getMessage("PHOENIX_MESS_SEARCH_FIX_HEADER_PLACEHOLDER"),
            "SEARCH_PRODUCTS" => getMessage("PHOENIX_MESS_SEARCH_PRODUCTS"),
            "SEARCH_SECTIONS" => getMessage("PHOENIX_MESS_SEARCH_SECTIONS"),
            "SEARCH_BRANDS" => getMessage("PHOENIX_MESS_SEARCH_BRANDS"),
            "ACTIONS_ACT_ON" => getMessage("PHOENIX_MESS_ACTIONS_ACT_ON"),
            "ACTIONS_ACT_OFF" => getMessage("PHOENIX_MESS_ACTIONS_ACT_OFF"),
            "ACTIONS_ACT_ON_TO" => getMessage("PHOENIX_MESS_ACTIONS_ACT_ON_TO"),
            "MOVE_TO_CATALOG" => getMessage("PHOENIX_MESS_MOVE_TO_CATALOG"),
            "TARIFF_DETAIL_BTN_NAME" => getMessage("PHOENIX_MESS_TARIFF_DETAIL_BTN_NAME"),
            "ALERT_SAVE_IMAGE" => getMessage("PHOENIX_MESS_ALERT_SAVE_IMAGE"),
            "EDIT" => getMessage("PHOENIX_MESS_EDIT"),
            "BACK_TO_LVL_UP" => getMessage("PHOENIX_MESS_BACK_TO_LVL_UP"),
            "SECTION_CATALOG_EMPTY" => getMessage("PHOENIX_MESS_SECTION_CATALOG_EMPTY"),
            "PRICE_TOTAL_PREFIX" => getMessage("PHOENIX_MESS_PRICE_TOTAL_PREFIX"),
            "BACK_TO_LIST" => getMessage("PHOENIX_MESS_BACK_TO_LIST"),
            "ACTIONS_ON" => getMessage("PHOENIX_MESS_ACTIONS_ON"),
            "ACTIONS_OFF" => getMessage("PHOENIX_MESS_ACTIONS_OFF"),
            "ALL_NW" => getMessage("PHOENIX_MESS_ALL_NW"),
            "ALL_BLG" => getMessage("PHOENIX_MESS_ALL_BLG"),
            "ALL_ACT" => getMessage("PHOENIX_MESS_ALL_ACT"),
            "MOVE_TO_PREV" => getMessage("PHOENIX_MESS_MOVE_TO_PREV"),
            "MOVE_TO_LIST_NW" => getMessage("PHOENIX_MESS_MOVE_TO_LIST_NW"),
            "MOVE_TO_LIST_ACT" => getMessage("PHOENIX_MESS_MOVE_TO_LIST_ACT"),
            "MOVE_TO_LIST_BLG" => getMessage("PHOENIX_MESS_MOVE_TO_LIST_BLG"),
            "SECTION_CATALOG_BTN_ADD" => getMessage("PHOENIX_MESS_SECTION_CATALOG_BTN_ADD"),
            "CART_CLEAR" => getMessage("PHOENIX_MESS_CART_CLEAR"),
            "CART_CONTINUE" => getMessage("PHOENIX_MESS_CART_CONTINUE"),
            "CART_DELIVERY" => getMessage("PHOENIX_MESS_CART_DELIVERY"),
            "CART_ORDER" => getMessage("PHOENIX_MESS_CART_ORDER"),
            "CART_CLICK" => getMessage("PHOENIX_MESS_CART_CLICK"),
            "CART_FAST_ORDER" => getMessage("PHOENIX_MESS_CART_FAST_ORDER"),
            "CART_ORDER_HEADER" => getMessage("PHOENIX_MESS_CART_ORDER_HEADER"),
            "FOOTER_MENU" => getMessage("PHOENIX_MESS_FOOTER_MENU"),
            "SEND_NAME" => getMessage("PHOENIX_MESS_SEND_NAME"),
            "SEND_PHONE" => getMessage("PHOENIX_MESS_SEND_PHONE"),
            "SEND_EMAIL" => getMessage("PHOENIX_MESS_SEND_EMAIL"),
            "SEND_DATE" => getMessage("PHOENIX_MESS_SEND_DATE"),
            "SEND_COUNT" => getMessage("PHOENIX_MESS_SEND_COUNT"),
            "SEND_TEXAREA" => getMessage("PHOENIX_MESS_SEND_TEXAREA"),
            "SEND_ADDRESS" => getMessage("PHOENIX_MESS_SEND_ADDRESS"),
            "SEND_CHECK_VALUE" => getMessage("PHOENIX_MESS_SEND_CHECK_VALUE"),
            "SEND_TITLE_FORM" => getMessage("PHOENIX_MESS_SEND_TITLE_FORM"),
            "SEND_TITLE" => getMessage("PHOENIX_MESS_SEND_TITLE"),
            "SEND_INFOBLOCK_TITLE" => getMessage("PHOENIX_MESS_SEND_INFOBLOCK_TITLE"),
            "SEND_TEXT1" => getMessage("PHOENIX_MESS_SEND_TEXT1"),
            "SEND_TEXT2" => getMessage("PHOENIX_MESS_SEND_TEXT2"),
            "SEND_TEXT3" => getMessage("PHOENIX_MESS_SEND_TEXT3"),
            "SEND_TEXT4" => getMessage("PHOENIX_MESS_SEND_TEXT4"),
            "SEND_TEXT5" => getMessage("PHOENIX_MESS_SEND_TEXT5"),
            "SEND_TEXT6" => getMessage("PHOENIX_MESS_SEND_TEXT6"),
            "SEND_NAME_TRF" => getMessage("PHOENIX_MESS_SEND_NAME_TRF"),
            "SEND_NAME_CTL" => getMessage("PHOENIX_MESS_SEND_NAME_CTL"),
            "SEND_NAME_BETTER_PRICE" => getMessage("PHOENIX_MESS_SEND_NAME_BETTER_PRICE"),
            "SEND_UTM_TITLE" => getMessage("PHOENIX_MESS_SEND_UTM_TITLE"),
            "SEND_NAME_BETTER_PRICE_TITLE" => getMessage("PHOENIX_MESS_SEND_NAME_BETTER_PRICE_TITLE"),
            "SEND_NAME_PREORDER" => getMessage("PHOENIX_MESS_SEND_NAME_PREORDER"),
            "SEND_NAME_PREORDER_TITLE" => getMessage("PHOENIX_MESS_SEND_NAME_PREORDER_TITLE"),
            "SEND_REQUEST" => getMessage("PHOENIX_MESS_SEND_REQUEST"),
            "SEND_FROM" => getMessage("PHOENIX_MESS_SEND_FROM"),
            "SEND_PRICE_NAME" => getMessage("PHOENIX_MESS_SEND_PRICE_NAME"),
            "SEND_PRICE_NULL" => getMessage("PHOENIX_MESS_SEND_PRICE_NULL"),
            "BASKET_DISCOUNT" => getMessage("PHOENIX_MESS_BASKET_DISCOUNT"),
            "BASKET_DISCOUNT_COMMENT" => getMessage("PHOENIX_MESS_BASKET_DISCOUNT_COMMENT"),
            "BASKET_TOTAL_WITH_DISCOUNT" => getMessage("PHOENIX_MESS_BASKET_TOTAL_WITH_DISCOUNT"),
            "BASKET_ALERT_MIN_SUM_TEXT_TOP" => getMessage("PHOENIX_MESS_BASKET_ALERT_MIN_SUM_TEXT_TOP"),
            "BASKET_SALE_ITEMS" => getMessage("PHOENIX_MESS_BASKET_SALE_ITEMS"),
            "BASKET_SALE_ITEMS_DELAY" => getMessage("PHOENIX_MESS_BASKET_SALE_ITEMS_DELAY"),
            "BASKET_COUPON_APPLY" => getMessage("PHOENIX_MESS_BASKET_COUPON_APPLY"),
            "BASKET_ADD2ORDER" => getMessage("PHOENIX_MESS_BASKET_ADD2ORDER"),
            "FAST_ORDER_FORM_TITLE" => getMessage("PHOENIX_MESS_FAST_ORDER_FORM_TITLE"),
            "FAST_ORDER_FORM_BUTTON" => getMessage("PHOENIX_MESS_FAST_ORDER_FORM_BUTTON"),
            "BASKET_TABLE_PRODUCT_NAME" => getMessage("PHOENIX_MESS_BASKET_TABLE_PRODUCT_NAME"),
            "BASKET_TABLE_PRODUCT_QUANTITY" => getMessage("PHOENIX_MESS_BASKET_TABLE_PRODUCT_QUANTITY"),
            "BASKET_TABLE_PRODUCT_SUM" => getMessage("PHOENIX_MESS_BASKET_TABLE_PRODUCT_SUM"),
            "BASKET_TABLE_PRODUCT_PRICE" => getMessage("PHOENIX_MESS_BASKET_TABLE_PRODUCT_PRICE"),
            "BASKET_TABLE_PRODUCT_SUM" => getMessage("PHOENIX_MESS_BASKET_TABLE_PRODUCT_SUM"),
            "BASKET_TOTAL" => getMessage("PHOENIX_MESS_BASKET_TOTAL"),
            "BASKET_TOTAL_TITLE" => getMessage("PHOENIX_MESS_BASKET_TOTAL_TITLE"),
            "ORDER_TOTAL_TITLE" => getMessage("PHOENIX_MESS_ORDER_TOTAL_TITLE"),
            "BASKET_PAYSYSTEM" => getMessage("PHOENIX_MESS_BASKET_PAYSYSTEM"),
            "BASKET_DELIVERY" => getMessage("PHOENIX_MESS_BASKET_DELIVERY"),
            "BASKET_PAYSYSTEM_PRICE" => getMessage("PHOENIX_MESS_BASKET_PAYSYSTEM_PRICE"),
            "BASKET_DELIVERY_PRICE" => getMessage("PHOENIX_MESS_BASKET_DELIVERY_PRICE"),
            "BASKET_SUM" => getMessage("PHOENIX_MESS_BASKET_SUM"),
            "PERSONAL" => getMessage("PHOENIX_MESS_PERSONAL"),
            "PERSONAL_LOGIN" => getMessage("PHOENIX_MESS_PERSONAL_LOGIN"),
            "BASKET_ITEM_EMPTY" => getMessage("PHOENIX_MESS_BASKET_ITEM_EMPTY"),
            "BASKET_PAY_SYSTEM_TITLE" => getMessage("PHOENIX_MESS_BASKET_PAY_SYSTEM_TITLE"),
            "BASKET_DELIVERY_TITLE" => getMessage("PHOENIX_MESS_BASKET_DELIVERY_TITLE"),
            "BASKET_DELIVERY_EXTRA_TITLE"=> getMessage("PHOENIX_MESS_BASKET_DELIVERY_EXTRA_TITLE"),
            "BASKET_DELIVERY_EXTRA_Y"=> getMessage("PHOENIX_MESS_BASKET_DELIVERY_EXTRA_Y"),
            "BASKET_DELIVERY_EXTRA_N"=> getMessage("PHOENIX_MESS_BASKET_DELIVERY_EXTRA_N"),
            "BASKET_DELIVERY_COST" => getMessage("PHOENIX_MESS_BASKET_DELIVERY_COST"),
            "CATALOG_FILTER_TAB" => getMessage("PHOENIX_MESS_CATALOG_FILTER_TAB"),
            "OPENMENU_HEADER" => getMessage("PHOENIX_MESS_OPENMENU_HEADER"),
            "SHARE_TITLE" => getMessage("PHOENIX_MESS_SHARE_TITLE"),
            "HEADER_DATA_HEADER" => getMessage("PHOENIX_MESS_HEADER_DATA_HEADER"),
            "FOOTER_DATA_HEADER" => getMessage("PHOENIX_MESS_FOOTER_DATA_HEADER"),
            "FAQ_DESC" => getMessage("PHOENIX_MESS_FAQ_DESC"),
            "FORM_THANK_TEXT" => getMessage("PHOENIX_FORM_THANK_TEXT"),
            "FORM_TIMEOUT" => getMessage("PHOENIX_FORM_TIMEOUT"),
            "FORM_TEMPL_NAME" => getMessage("PHOENIX_MESS_FORM_TEMPL_NAME"),
            "FORM_TEMPL_COUNT" => getMessage("PHOENIX_MESS_FORM_TEMPL_COUNT"),
            "FORM_TEMPL_PHONE" => getMessage("PHOENIX_MESS_FORM_TEMPL_PHONE"),
            "FORM_TEMPL_ADDRESS" => getMessage("PHOENIX_MESS_FORM_TEMPL_ADDRESS"),
            "FORM_TEMPL_DATE" => getMessage("PHOENIX_MESS_FORM_TEMPL_DATE"),
            "FORM_TEMPL_TEXTAREA" => getMessage("PHOENIX_MESS_FORM_TEMPL_TEXTAREA"),
            "FORM_TEMPL_EMAIL" => getMessage("PHOENIX_MESS_FORM_TEMPL_EMAIL"),
            "FORM_TEMPL_FILE" => getMessage("PHOENIX_MESS_FORM_TEMPL_FILE"),
            "FORM_TEMPL_SELECT" => getMessage("PHOENIX_MESS_FORM_TEMPL_SELECT"),
            "FORM_TEMPL_SUBMIT" => getMessage("PHOENIX_MESS_FORM_TEMPL_SUBMIT"),
            "FORM_TEMPL_THANK" => getMessage("PHOENIX_MESS_FORM_TEMPL_THANK"),
            "FORM_TEMPL_AGREEMENT" => getMessage("PHOENIX_MESS_FORM_TEMPL_AGREEMENT"),
            "MENU_TEMPL_ALL_SECTIONS" => getMessage("PHOENIX_MESS_MENU_TEMPL_ALL_SECTIONS"),
            "MENU_TEMPL_MOB_BACK" => getMessage("PHOENIX_MESS_MENU_TEMPL_MOB_BACK"),
            "MENU_TEMPL_MOB_CALLBACK" => getMessage("PHOENIX_MESS_MENU_TEMPL_MOB_CALLBACK"),
            "MENU_TEMPL_MOB_HEADER" => getMessage("PHOENIX_MESS_MENU_TEMPL_MOB_HEADER"),
            "PRODUCT_ALERT_HEAD" => getMessage("PHOENIX_MESS_PRODUCT_ALERT_HEAD"),
            "PRODUCT_ALERT_SKU" => getMessage("PHOENIX_MESS_PRODUCT_ALERT_SKU"),
            "PRODUCT_ALERT_SKU_TEXT_1" => getMessage("PHOENIX_MESS_PRODUCT_ALERT_SKU_TEXT_1"),
            "PRODUCT_ALERT_SKU_TEXT_2" => getMessage("PHOENIX_MESS_PRODUCT_ALERT_SKU_TEXT_2"),
            "MAIN_ALERT_HEAD" => getMessage("PHOENIX_MESS_MAIN_ALERT_HEAD"),
            "MAIN_ALERT_TITLE" => getMessage("PHOENIX_MESS_MAIN_ALERT_TITLE"),
            "ERRORS_OFFERS" => getMessage("PHOENIX_MESS_ERRORS_OFFERS"),
            "MAIN_ALERT_CALLBACK_TEXT_1" => getMessage("PHOENIX_MESS_MAIN_ALERT_CALLBACK_TEXT_1"),
            "MAIN_ALERT_CALLBACK_TEXT_2" => getMessage("PHOENIX_MESS_MAIN_ALERT_CALLBACK_TEXT_2"),
            "MAIN_ALERT_CALLBACK_TEXT_2_COMMENT" => getMessage("PHOENIX_MESS_MAIN_ALERT_CALLBACK_TEXT_2_COMMENT"),
            "PRODUCT_ALERT_SKU_TEXT_1_COMMENT" => getMessage("PHOENIX_MESS_PRODUCT_ALERT_SKU_TEXT_1_COMMENT"),
            "PRODUCT_ALERT_SKU_TEXT_2_COMMENT" => getMessage("PHOENIX_MESS_PRODUCT_ALERT_SKU_TEXT_2_COMMENT"),
            "SKU_SELECT_TITLE" => getMessage("PHOENIX_MESS_SKU_SELECT_TITLE"),
            "NEWS_LIST_BLOG_DEF_NAME" => getMessage("PHOENIX_MESS_NEWS_LIST_BLOG_DEF_NAME"),
            "NEWS_LIST_NEWS_DEF_NAME" => getMessage("PHOENIX_MESS_NEWS_LIST_NEWS_DEF_NAME"),
            "NEWS_LIST_ACTIONS_DEF_NAME" => getMessage("PHOENIX_MESS_NEWS_LIST_ACTIONS_DEF_NAME"),
            "NEWS_LIST_ACTIONS_CATEGORY_ICON_NAME" => getMessage("PHOENIX_MESS_NEWS_LIST_ACTIONS_CATEGORY_ICON_NAME"),
            "SEARCH_RESULT_GOODS" => getMessage("PHOENIX_MESS_SEARCH_RESULT_GOODS"),
            "SEARCH_RESULT_SECTIONS" => getMessage("PHOENIX_MESS_SEARCH_RESULT_SECTIONS"),
            "SEARCH_RESULT_ACTIONS" => getMessage("PHOENIX_MESS_SEARCH_RESULT_ACTIONS"),
            "SEARCH_RESULT_BLOG" => getMessage("PHOENIX_MESS_SEARCH_RESULT_BLOG"),
            "SEARCH_RESULT_NEWS" => getMessage("PHOENIX_MESS_SEARCH_RESULT_NEWS"),
            "CONTACTS_DIALOG_MAP_BTN_NAME" => getMessage("PHOENIX_MESS_CONTACTS_DIALOG_MAP_BTN_NAME"),
            "CART_FAST_ORDER_INPUTS_REQ" => getMessage("PHOENIX_CART_FAST_ORDER_INPUTS_REQ"),
            "CART_FAST_ORDER_INPUTS" => getMessage("PHOENIX_CART_FAST_ORDER_INPUTS"),
            "SEARCH_QUERY_VALUE_TITLE" => getMessage("PHOENIX_SEARCH_QUERY_VALUE_TITLE"),
            "PERSONAL_SPS_ACCOUNT_PAGE_NAME" => getMessage("PHOENIX_PERSONAL_SPS_ACCOUNT_PAGE_NAME"),
            "PERSONAL_SPS_PRIVATE_PAGE_NAME" => getMessage("PHOENIX_PERSONAL_SPS_PRIVATE_PAGE_NAME"),
            "PERSONAL_SPS_PROFILE_PAGE_NAME" => getMessage("PHOENIX_PERSONAL_SPS_PROFILE_PAGE_NAME"),
            "PERSONAL_SPS_ORDER_PAGE_NAME" => getMessage("PHOENIX_PERSONAL_SPS_ORDER_PAGE_NAME"),
            "PERSONAL_SPS_ORDER_PAGE_HISTORY" => getMessage("PHOENIX_PERSONAL_SPS_ORDER_PAGE_HISTORY"),
            "PERSONAL_SPS_SUBSCRIBE_PAGE_NAME" => getMessage("PHOENIX_PERSONAL_SPS_SUBSCRIBE_PAGE_NAME"),
            "PERSONAL_SPS_BASKET_PAGE_NAME" => getMessage("PHOENIX_PERSONAL_SPS_BASKET_PAGE_NAME"),
            "PERSONAL_SPS_CONTACT_PAGE_NAME" => getMessage("PHOENIX_PERSONAL_SPS_CONTACT_PAGE_NAME"),
            "PERSONAL_SPS_ERROR_NOT_CHOSEN_ELEMENT" => getMessage("PHOENIX_PERSONAL_SPS_ERROR_NOT_CHOSEN_ELEMENT"),
            "PERSONAL_SPS_CHAIN_MAIN" => getMessage("PHOENIX_PERSONAL_SPS_CHAIN_MAIN"),
            "PERSONAL_SPS_TITLE_MAIN" => getMessage("PHOENIX_PERSONAL_SPS_TITLE_MAIN"),
            "PERSONAL_SPS_PERSONAL" => getMessage("PHOENIX_PERSONAL_SPS_PERSONAL"),
            "PERSONAL_SPS_PERSONAL_LOGOUT" => getMessage("PHOENIX_PERSONAL_SPS_PERSONAL_LOGOUT"),
            "PERSONAL_SPS_PERSONAL_LOGOUT_WIDGET" => getMessage("PHOENIX_PERSONAL_SPS_PERSONAL_LOGOUT_WIDGET"),
            "PERSONAL_LOGIN_TITLE" => getMessage("PHOENIX_PERSONAL_LOGIN_TITLE"),
            "PERSONAL_LOGIN_INPUT" => getMessage("PHOENIX_PERSONAL_LOGIN_INPUT"),
            "PERSONAL_PASSWORD_INPUT" => getMessage("PHOENIX_PERSONAL_PASSWORD_INPUT"),
            "PERSONAL_BTN_ENTER" => getMessage("PHOENIX_PERSONAL_BTN_ENTER"),
            "PERSONAL_ERROR_EMPTY" => getMessage("PHOENIX_PERSONAL_ERROR_EMPTY"),
            "PERSONAL_ERROR_PASSWORD" => getMessage("PHOENIX_PERSONAL_ERROR_PASSWORD"),
            "PERSONAL_REGISTER_PAGE_TITLE" => getMessage("PHOENIX_PERSONAL_REGISTER_PAGE_TITLE"),
            "PERSONAL_REGISTER_FORM_TITLE" => getMessage("PHOENIX_PERSONAL_REGISTER_FORM_TITLE"),
            "PERSONAL_REGISTER_BTN_NAME" => getMessage("PHOENIX_PERSONAL_REGISTER_BTN_NAME"),
            "PERSONAL_REGISTER_INPUT_NAME" => getMessage("PHOENIX_PERSONAL_REGISTER_INPUT_NAME"),
            "PERSONAL_REGISTER_INPUT_EMAIL" => getMessage("PHOENIX_PERSONAL_REGISTER_INPUT_EMAIL"),
            "PERSONAL_REGISTER_INPUT_PASSWORD" => getMessage("PHOENIX_PERSONAL_REGISTER_INPUT_PASSWORD"),
            "PERSONAL_REGISTER_TEXT_SUCCESS" => getMessage("PHOENIX_PERSONAL_REGISTER_TEXT_SUCCESS"),
            "PERSONAL_REGISTER_SUCCESS_PAGE_TITLE" => getMessage("PHOENIX_PERSONAL_REGISTER_SUCCESS_PAGE_TITLE"),
            "PERSONAL_REGISTER_INDEX_PAGE_COMMENT" => getMessage("PHOENIX_PERSONAL_REGISTER_INDEX_PAGE_COMMENT"),
            "PERSONAL_FORGOT_PAGE_TITLE" => getMessage("PHOENIX_PERSONAL_FORGOT_PAGE_TITLE"),
            "PERSONAL_FORGOT_PAGE_SUCCESS" => getMessage("PHOENIX_PERSONAL_FORGOT_PAGE_SUCCESS"),
            "PERSONAL_FORGOT_PAGE_COMMENT" => getMessage("PHOENIX_PERSONAL_FORGOT_PAGE_COMMENT"),
            "PERSONAL_FORGOT_PAGE_BTN_NAME" => getMessage("PHOENIX_PERSONAL_FORGOT_PAGE_BTN_NAME"),
            "PERSONAL_CHANGEPASSWORD_PAGE_TITLE" => getMessage("PHOENIX_PERSONAL_CHANGEPASSWORD_PAGE_TITLE"),
            "PERSONAL_CHANGEPASSWORD_PAGE_NEW_PASSWORD" => getMessage("PHOENIX_PERSONAL_CHANGEPASSWORD_PAGE_NEW_PASSWORD"),
            "PERSONAL_CHANGEPASSWORD_PAGE_NEW_PASSWORD_CONFIRM" => getMessage("PHOENIX_PERSONAL_CHANGEPASSWORD_PAGE_NEW_PASSWORD_CONFIRM"),
            "PERSONAL_CHANGEPASSWORD_PAGE_CHANGE" => getMessage("PHOENIX_PERSONAL_CHANGEPASSWORD_PAGE_CHANGE"),
            "PERSONAL_CHANGEPASSWORD_PAGE_NEW_PASSWORD_REQ" => getMessage("PHOENIX_PERSONAL_CHANGEPASSWORD_PAGE_NEW_PASSWORD_REQ"),
            "PERSONAL_CHANGEPASSWORD_PAGE_SECURE_NOTE" => getMessage("PHOENIX_PERSONAL_CHANGEPASSWORD_PAGE_SECURE_NOTE"),
            "PERSONAL_CHANGEPASSWORD_PAGE_NONSECURE_NOTE" => getMessage("PHOENIX_PERSONAL_CHANGEPASSWORD_PAGE_NONSECURE_NOTE"),
            "PERSONAL_PRIVATE_INPUT_NAME" => getMessage("PHOENIX_PERSONAL_PRIVATE_INPUT_NAME"),
            "PERSONAL_PRIVATE_INPUT_LAST_NAME" => getMessage("PHOENIX_PERSONAL_PRIVATE_INPUT_LAST_NAME"),
            "PERSONAL_PRIVATE_INPUT_SECOND_NAME" => getMessage("PHOENIX_PERSONAL_PRIVATE_INPUT_SECOND_NAME"),
            "PERSONAL_PRIVATE_MAIN_RESET" => getMessage("PHOENIX_PERSONAL_PRIVATE_MAIN_RESET"),
            "PERSONAL_PRIVATE_NEW_PASSWORD_CONFIRM" => getMessage("PHOENIX_PERSONAL_PRIVATE_NEW_PASSWORD_CONFIRM"),
            "PERSONAL_PRIVATE_SAVE" => getMessage("PHOENIX_PERSONAL_PRIVATE_SAVE"),
            "PERSONAL_PRIVATE_RESET" => getMessage("PHOENIX_PERSONAL_PRIVATE_RESET"),
            "PERSONAL_PRIVATE_LAST_LOGIN" => getMessage("PHOENIX_PERSONAL_PRIVATE_LAST_LOGIN"),
            "PERSONAL_PRIVATE_NEW_PASSWORD_REQ" => getMessage("PHOENIX_PERSONAL_PRIVATE_NEW_PASSWORD_REQ"),
            "PERSONAL_PRIVATE_INPUT_EMAIL" => getMessage("PHOENIX_PERSONAL_PRIVATE_INPUT_EMAIL"),
            "PERSONAL_PRIVATE_PROFILE_DATA_SAVED" => getMessage("PHOENIX_PERSONAL_PRIVATE_PROFILE_DATA_SAVED"),
            "PERSONAL_PRIVATE_LAST_UPDATE" => getMessage("PHOENIX_PERSONAL_PRIVATE_LAST_UPDATE"),
            "SMARTFILTER_BTN_SHOW" => getMessage("PHOENIX_SMARTFILTER_BTN_SHOW"),
            "SMARTFILTER_BTN_CLEAR" => getMessage("PHOENIX_SMARTFILTER_BTN_CLEAR"),
            "PERSONAL_AUTH_FORM_SOC" => getMessage("PHOENIX_PERSONAL_AUTH_FORM_SOC"),
            "PERSONAL_SPS_ORDERS_BTN_NAME" => getMessage("PHOENIX_PERSONAL_SPS_ORDERS_BTN_NAME"),
            "PERSONAL_SPS_ORDERS_HISTORY_BTN_NAME" => getMessage("PHOENIX_PERSONAL_SPS_ORDERS_HISTORY_BTN_NAME"),
            "PERSONAL_SPS_ORDERS_ACCOUNT_BTN_NAME" => getMessage("PHOENIX_PERSONAL_SPS_ORDERS_ACCOUNT_BTN_NAME"),
            "PERSONAL_SPS_PRIVATE_BTN_NAME" => getMessage("PHOENIX_PERSONAL_SPS_PRIVATE_BTN_NAME"),
            "PERSONAL_SPS_PROFILE_BTN_NAME" => getMessage("PHOENIX_PERSONAL_SPS_PROFILE_BTN_NAME"),
            "PERSONAL_SPS_BASKET_BTN_NAME" => getMessage("PHOENIX_PERSONAL_SPS_BASKET_BTN_NAME"),
            "PERSONAL_SPS_BASKET_BTN_NAME_DEFAULT" => getMessage("PHOENIX_PERSONAL_SPS_BASKET_BTN_NAME_DEFAULT"),
            "PERSONAL_SPS_SUBSCRIBE_BTN_NAME" => getMessage("PHOENIX_PERSONAL_SPS_SUBSCRIBE_BTN_NAME"),
            "COMPARE_ALL_CHARACTERISTICS" => getMessage("PHOENIX_MESS_COMPARE_ALL_CHARACTERISTICS"),
            "COMPARE_ONLY_DIFFERENT" => getMessage("PHOENIX_MESS_COMPARE_ONLY_DIFFERENT"),
            "COMPARE_CLEAR_ALL" => getMessage("PHOENIX_MESS_COMPARE_CLEAR_ALL"),
            "COMPARE_REMOVE_PRODUCT" => getMessage("PHOENIX_MESS_COMPARE_REMOVE_PRODUCT"),
            "COMPARE_COMMENT" => getMessage("PHOENIX_MESS_COMPARE_COMMENT"),
            "COMPARE_CNT_1" => getMessage("PHOENIX_MESS_COMPARE_CNT_1"),
            "COMPARE_CNT_2" => getMessage("PHOENIX_MESS_COMPARE_CNT_2"),
            "COMPARE_CNT_3" => getMessage("PHOENIX_MESS_COMPARE_CNT_3"),
            "COMPARE_CNT_4" => getMessage("PHOENIX_MESS_COMPARE_CNT_4"),
            "PROFILE_PHOTO_DEL" => getMessage("PHOENIX_MESS_PROFILE_PHOTO_DEL"),
            "PROFILE_PHOTO_DOWNLOAD" => getMessage("PHOENIX_MESS_PROFILE_PHOTO_DOWNLOAD"),
            "BIG_SLIDER_CATALOG_NAME_TITLE" => getMessage("PHOENIX_MESS_BIG_SLIDER_CATALOG_NAME_TITLE"),
            "TYPE_MAP_SHOW_BTN_NAME" => getMessage("PHOENIX_MESS_TYPE_MAP_SHOW_BTN_NAME"),
            "MENU_SIDE_BACK" => getMessage("PHOENIX_MESS_MENU_SIDE_BACK"),
            "RATE_VOTE_SUCCESS_ALERT" => getMessage("PHOENIX_MESS_RATE_VOTE_SUCCESS_ALERT"),
            "BRANDS_LINK_TO_DETAIL_INFO" => getMessage("PHOENIX_MESS_BRANDS_LINK_TO_DETAIL_INFO"),
            "BRANDS_LINK_TO_CATALOG" => getMessage("PHOENIX_MESS_BRANDS_LINK_TO_CATALOG"),
            "BRANDS_DESCRIPTION_CATALOG" => getMessage("PHOENIX_MESS_BRANDS_DESCRIPTION_CATALOG"),
            "BREADCRUMB_TOMAIN_TITLE" => getMessage("PHOENIX_MESS_BREADCRUMB_TOMAIN_TITLE"),
            "PRODUCT_OFFERS_COUNT_TITLE_1" => getMessage("PHOENIX_MESS_PRODUCT_OFFERS_COUNT_TITLE_1"),
            "PRODUCT_OFFERS_COUNT_TITLE_2" => getMessage("PHOENIX_MESS_PRODUCT_OFFERS_COUNT_TITLE_2"),
            "PRODUCT_OFFERS_COUNT_TITLE_3" => getMessage("PHOENIX_MESS_PRODUCT_OFFERS_COUNT_TITLE_3"),
            "PRODUCT_OFFERS_COUNT_TITLE_4" => getMessage("PHOENIX_MESS_PRODUCT_OFFERS_COUNT_TITLE_4"),
            "REG_ERROR_LOGIN" => getMessage("PHOENIX_MESS_REG_ERROR_LOGIN"),
            "ERROR_LOGIN_UNDEFINED_1" => getMessage("PHOENIX_MESS_FORGOTPASSWORD_ERROR_LOGIN_UNDEFINED_1"),
            "ERROR_LOGIN_UNDEFINED_2" => getMessage("PHOENIX_MESS_FORGOTPASSWORD_ERROR_LOGIN_UNDEFINED_2"),
            "PERSONAL_FORGOT_PAGE_INPUT_LOGIN" => getMessage("PHOENIX_PERSONAL_FORGOT_PAGE_INPUT_LOGIN"),
            "CHANGEPASSWORD_ERROR_CHECKWORD" => getMessage("PHOENIX_MESS_CHANGEPASSWORD_ERROR_CHECKWORD"),
            "CHANGEPASSWORD_SUCCESS" => getMessage("PHOENIX_MESS_CHANGEPASSWORD_SUCCESS"),
            "CHANGEPASSWORD_ER_FAIL" => getMessage("PHOENIX_MESS_CHANGEPASSWORD_ER_FAIL"),
            "CHANGEPASSWORD_ER_DIFFERENT" => getMessage("PHOENIX_MESS_CHANGEPASSWORD_ER_DIFFERENT"),
            "CHANGEPASSWORD_SUCCESS_BTN_TO_AUTH" => getMessage("PHOENIX_MESS_CHANGEPASSWORD_SUCCESS_BTN_TO_AUTH"),
            "CATALOG_DETAIL_ABOUT_PRODUCT" => getMessage("PHOENIX_MESS_CATALOG_DETAIL_ABOUT_PRODUCT"),
            "CATALOG_DETAIL_SCROLL2PRICE" => getMessage("PHOENIX_MESS_CATALOG_DETAIL_SCROLL2PRICE"),
            "SOC_GROUPS_DESCRIPTION_IN_MENU" => getMessage("PHOENIX_MESS_SOC_GROUPS_DESCRIPTION_IN_MENU"),
            "BASKET_TAB_TITLE_PRODUCTS" => getMessage("PHOENIX_MESS_BASKET_TAB_TITLE_PRODUCTS"),
            "BASKET_TAB_TITLE_DELAYED" => getMessage("PHOENIX_MESS_BASKET_TAB_TITLE_DELAYED"),
            "NEWS_SHOW_SIFE_MENU_NAME_BTN" => getMessage("PHOENIX_MESS_NEWS_SHOW_SIFE_MENU_NAME_BTN"),
            "LOGO_DOMAIN_TITLE" => getMessage("PHOENIX_MESS_LOGO_DOMAIN_TITLE"),
            "SHOP_COUPON_BTN_SHOW" => getMessage("PHOENIX_MESS_SHOP_COUPON_BTN_SHOW"),
            "CATALOG_SEND_AFTER_PAY_THEME" => getMessage("PHOENIX_MESS_CATALOG_SEND_AFTER_PAY_THEME"),
            "BASKET_GIFTS_TITLE" => getMessage("PHOENIX_MESS_BASKET_GIFTS_TITLE"),
            "BASKET_GIFTS_BTN_NAME" => getMessage("PHOENIX_MESS_BASKET_GIFTS_BTN_NAME"),
            
        );


        $email_confirmation = \Bitrix\Main\Config\Option::get("main", "new_user_registration_email_confirmation", "");
        $email_required = \Bitrix\Main\Config\Option::get("main", "new_user_email_required", "");

        $bConfirmReq = ($email_confirmation == "Y" && $email_required == "Y");

        if($bConfirmReq)
            $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_REGISTER_TEXT_SUCCESS"] = Loc::GetMessage("PHOENIX_PERSONAL_REGISTER_TEXT_SUCCESS_REQ");

       


        $PHOENIX_TEMPLATE_ARRAY["ADMIN"] = Array(
            "DESIGN" => array(
                "TITLE_HEAD_EDIT" => Loc::GetMessage("PHOENIX_HEAD_EDIT"),
                "TITLE_HEAD_BG_XS_FOR_PAGES" => Loc::GetMessage("PHOENIX_TITLE_HEAD_BG_XS_FOR_PAGES"),
                "TITLE_BODY_BG" => Loc::GetMessage("PHOENIX_TITLE_BODY_BG")
            ),

            "MENU" => array(
                "TITLE_VIEW_MENU" => Loc::GetMessage("PHOENIX_ADMIN_TITLE_VIEW_MENU"),
            ),
            
            "CONTACTS" => array(
                "TITLE_SOC_EDIT" => Loc::GetMessage("PHOENIX_SOC_EDIT"),
                "TITLE_WIDGETS" => Loc::GetMessage("PHOENIX_WIDGETS_TITLE"),
                "TITLE_PHOENIX_MASK" => Loc::GetMessage("PHOENIX_MASK_TITLE"),
            ),
            
            "SHOP" => array(
                "TITLE_CARTMINI_CART" => Loc::GetMessage("PHOENIX_CARTMINI_CART_TITLE"),
                "TITLE_CART_DESIGN" => Loc::GetMessage("PHOENIX_CART_DESIGN_TITLE"),
                "TITLE_CART_NOTIFIC" => Loc::GetMessage("PHOENIX_CART_NOTIFIC_TITLE"),
                "TITLE_CART_OTHER" => Loc::GetMessage("PHOENIX_CART_OTHER_TITLE"),
                "TITLE_CART_PAGE_CART" => Loc::GetMessage("PHOENIX_PAGE_CART"),
                "TITLE_CART_ORDER_PAGE" => Loc::GetMessage("PHOENIX_ADMIN_TITLE_ORDER_PAGE"),
                "TITLE_CART_BASE" => Loc::GetMessage("PHOENIX_CART_BASE_TITLE"),
                "TITLE_CART_FOR_ADMIN" => Loc::GetMessage("PHOENIX_TITLE_CART_FOR_ADMIN"),
                "TITLE_CART_FOR_USER" => Loc::GetMessage("PHOENIX_TITLE_CART_FOR_USER"),
                "TITLE_CART_FAST_ORDER" => Loc::GetMessage("PHOENIX_TITLE_CART_FAST_ORDER"),
            ),
            "LIDS" => array(
                "TITLE_SAVE_IB" => Loc::GetMessage("PHOENIX_SAVE_IB_TITLE"),
                "TITLE_BX24" => Loc::GetMessage("PHOENIX_BX24_TITLE"),
                "TITLE_BX_STANDART" => Loc::GetMessage("PHOENIX_ADMIN_TITLE_BX_STANDART"),
                "TITLE_BX_WEB_HOOK" => Loc::GetMessage("PHOENIX_ADMIN_TITLE_BX_WEB_HOOK"),
                "TITLE_AMO" => Loc::GetMessage("PHOENIX_AMO_TITLE"),
                "TITLE_MAIL" => Loc::GetMessage("PHOENIX_MAIL_TITLE"),
                
            ),
            "SERVICES" => array(
                "TITLE_OTHER_SERVICES" => Loc::GetMessage("PHOENIX_OTHER_SERVICES_TITLE"),
                "TITLE_SCRIPTS" => Loc::GetMessage("PHOENIX_SERVICES_SCRIPTS_TITLE"),
                "TITLE_ANALITICS" => Loc::GetMessage("PHOENIX_ANALITICS_TITLE"),
                "TITLE_GOALS" => Loc::GetMessage("PHOENIX_GOALS"),
                "TITLE_GOALS_ORDER" => Loc::GetMessage("PHOENIX_GOALS_ORDER"),
                "TITLE_GOALS_FAST_ORDER" => Loc::GetMessage("PHOENIX_GOALS_FAST_ORDER"),
                "TITLE_GOALS_ADD2BASKET" => Loc::GetMessage("PHOENIX_GOALS_ADD2BASKET"),
                
            ),
            "BASE_GOALS" => array(
                "TAB" => Loc::GetMessage("PHOENIX_BASE_GOALS_TAB"),
                
            ),
            "OTHER" => array(
                "TITLE_SITE_BUILD" => Loc::GetMessage("PHOENIX_SITE_BUILD_TITLE"),
                "TITLE_BG_404" => Loc::GetMessage("PHOENIX_BG_404_TITLE"),
                "TITLE_OPTIMIZIER_OPTIONS" => Loc::GetMessage("PHOENIX_OPTIMIZIER_OPTIONS_TITLE"),
                "TITLE_GOOGLE_RECAPTCHA" => Loc::GetMessage("PHOENIX_TITLE_GOOGLE_RECAPTCHA"),
                
            ),
            "SEARCH" => Array(
                "TAB_NAME" => Loc::GetMessage("PHOENIX_SEARCH_TAB"),
                "SECTION_NAME_DESIGN" => Loc::GetMessage("PHOENIX_SEARCH_SECTION_NAME_DESIGN"),
                "SECT_HINT" => Loc::GetMessage("PHOENIX_SEARCH_SECTION_NAME_HINT"),
                "SECTION_NAME_PLACEHOLDER" => Loc::GetMessage("PHOENIX_SECTION_NAME_PLACEHOLDER"),
            ),
            "CATALOG_ITEM_FIELDS" => Array(
                "SECTION_NAME_LIST" => Loc::GetMessage("PHOENIX_CATALOG_ITEM_FIELDS_SECTION_NAME_LIST"),
                "SECTION_NAME_DETAIL" => Loc::GetMessage("PHOENIX_CATALOG_ITEM_FIELDS_SECTION_NAME_DETAIL"),
                "SECTION_NAME_COMMON" => Loc::GetMessage("PHOENIX_CATALOG_ITEM_FIELDS_SECTION_NAME_COMMON"),
            ),
            "CATALOG" => Array(
                "TITLE_MODE_PREORDER" => Loc::GetMessage("PHOENIX_ADMIN_CATALOG_TITLE_MODE_PREORDER"),
                "TITLE_SUBSCRIBE_PRODUCT" => Loc::GetMessage("PHOENIX_ADMIN_CATALOG_TITLE_SUBSCRIBE_PRODUCT"),
                "TITLE_DETAIL_PAGE_SORT" => Loc::GetMessage("PHOENIX_ADMIN_CATALOG_TITLE_DETAIL_PAGE_SORT"),
            ),
            "STORE" => Array(
                "TITLE_DETAIL_PAGE" => Loc::GetMessage("PHOENIX_ADMIN_STORE_TITLE_DETAIL_PAGE")
            ),
            "RATING" => Array(
                "SECTION_NAME_VIEW_FULL" => Loc::GetMessage("PHOENIX_SECTION_NAME_VIEW_FULL"),
                "SECTION_NAME_VIEW_FULL_FLY_PANEL" => Loc::GetMessage("PHOENIX_SECTION_NAME_VIEW_FULL_FLY_PANEL"),
            ),
            "PERSONAL" => array(
                "TITLE_FIX_PRICE" => Loc::GetMessage("PHOENIX_ADMIN_PERSONAL_FIX_PRICE")
                
            ),
            "REGION" => array(
                "TITLE_ALERT_INSTRUCTION" => Loc::GetMessage("PHOENIX_ADMIN_REGION_ALERT_INSTRUCTION"),
                "TITLE_POPUP_WINDOW" => Loc::GetMessage("PHOENIX_ADMIN_REGION_POPUP_WINDOW"),
                "TITLE_APPLY_POPUP_WINDOW" => Loc::GetMessage("PHOENIX_ADMIN_REGION_APPLY_POPUP_WINDOW")
                
            ),

        );

    }

    public static function setDefaultOptions(){
        global $PHOENIX_TEMPLATE_ARRAY;



        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]))
        {
            foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"] as $i =>$arItems)
            {
                if(!empty($arItems["ITEMS"]))
                {

                    foreach($arItems["ITEMS"] as $j => $arItem)
                    {
                        if(isset($arItem["VALUE"]))
                        {
                            if(gettype($arItem["VALUE"]) == "string" || !$arItem["VALUE"])
                            {
                                if(!$arItem["VALUE"])
                                    $arItem["VALUE"] = strval($arItem["VALUE"]);
                                
                                if( !strlen($arItem["VALUE"]) && strlen($arItem["DEFAULT"]) )
                                {
                                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"][$i]["ITEMS"][$j]["VALUE"] = $arItem["DEFAULT"];


                                    if($arItem["TYPE"] == "text")
                                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"][$i]["ITEMS"][$j]["~VALUE"] = htmlspecialcharsBack($arItem["DEFAULT"]);
                                }
                            }

                        }
                    }
                }

            }

        }




    }

    public static function getArMeasure(){
        $res = $IDs = array();

        global $PHOENIX_TEMPLATE_ARRAY;

        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MEASURE"]["VALUE"]))
        {

            if(  CModule::IncludeModule("catalog") )
            {

                foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MEASURE"]["VALUE"] as $key => $value){
                    if($value == "Y")
                        $IDs[] = $key;
                }
            
                $res_measure = CCatalogMeasure::getList(array(), array("ID"=>$IDs), false, false, array("ID", "SYMBOL"));
                while($measure = $res_measure->Fetch()){
                    $res[$measure['ID']] = $measure;
                }        
             }
        }



        return $res;
    }

    public static function sendNewReviewProduct($arParams = array()){

        global $PHOENIX_TEMPLATE_ARRAY;

        CPhoenix::phoenixOptionsValues($arParams["SITE_ID"], array('rating', 'lids', 'region'));

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['SEND_NEW_REVIEW']['VALUE']['ACTIVE']=="Y")
        {
            $email_to = "";
            
            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["EMAIL_TO"]["VALUE"])>0)
                $email_to = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["EMAIL_TO"]["VALUE"];

            else if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_TO"]["VALUE"])>0)
                $email_to = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_TO"]["VALUE"];


            $email_from = "";

            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_FROM"]["VALUE"])>0)
                $email_from = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_FROM"]["VALUE"];


            if(strlen($email_to)>0)
                $arMailtoAdmin = explode(",", $email_to);
            

            if( !empty($arMailtoAdmin) )
            {

                $productName = "";
                $productUrl = "";

                if($arParams["PRODUCT_ID"])
                {
                    
                    CPhoenix::getIblockIDs(array(
                        "SITE_ID"=>$arParams["SITE_ID"], 
                        "CODES" => array(
                            "concept_phoenix_catalog_".$arParams["SITE_ID"]
                            )
                        )
                    );


                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]>0)
                    {

                        $arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ID" => $arParams["PRODUCT_ID"]);
                        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, array("ID", "NAME"));

                        if($arProduct = $res->GetNext())
                        {
                            $rsSites = CSite::GetByID($arParams["SITE_ID"]);
                            $arSite = $rsSites->Fetch();

                            $domen = CPhoenixHost::getHost($_SERVER);

                            $productName = strip_tags($arProduct["~NAME"]);

                            if(isset($arParams["FROM_JS"]) && $arParams["FROM_JS"] == "Y")
                            {
                                if(trim(SITE_CHARSET) == "windows-1251")
                                {
                                    $domen = utf8win1251($domen);
                                    $productName = utf8win1251($productName);
                                }
                            }


                            $productUrl = $domen."/bitrix/admin/concept_phoenix_admin_reviews_edit.php?ID=".$arParams["REVIEW_ID"]."&site_id=".$arParams["SITE_ID"];
                        }
                    }
                    
                }


                $arEventFieldsAdmin = array(
                    "EMAIL_FROM" => $email_from,
                    "REVIEW_URL" => $productUrl,
                    "PRODUCT_NAME" => $productName,
                    "EMAIL" => $email_from,
                    "SITE_URL" => $domen.$arSite["DIR"]
                );


                foreach ($arMailtoAdmin as $email_to_val)
                {
                   $arEventFieldsAdmin["EMAIL_TO"] = $email_to_val;
                
                    if( CEvent::Send("PHOENIX_NEW_REVIEW_PRODUCT_".$arParams["SITE_ID"], $arParams["SITE_ID"], $arEventFieldsAdmin, "Y", "") )
                        $arRes["OK"] = "Y";
                   
                }
            }
        }

    }

    public static function getReviewsInfo($arParams = array()){
        define("SITE_ID", $arParams["SITE_ID"]);

        if(!isset($arParams["select"]))
            $arParams["select"] = array();

        $arResultTMP = Bitrix\CPhoenixReviewsInfo\CPhoenixReviewsInfoTable::getList(array('select'=>$arParams["select"],'filter' => array('=PRODUCT_ID' => $arParams["PRODUCT_ID"]),"cache"=>array("ttl"=>3600)))->fetchAll();

        $arResult = array();

        if(!empty($arResultTMP))
        {
            foreach ($arResultTMP as $key => $arItem){
                foreach ($arItem as $keyItem => $item) {
                    $arResult[$arItem["PRODUCT_ID"]][$keyItem] = intval($item);
                }
            }
            unset($arResultTMP);
        }

        return $arResult;

    }
    public static function updateReviewsInfo($arParams = array()){

        if(!empty($arParams))
        {
            if($arParams["ACTIVE"] == "Y")
            {

                define("SITE_ID", $arParams["SITE_ID"]);

                $count = 0;

                if($arParams["ACTION"]=="add")
                    $count = 1;
                
                else if($arParams["ACTION"]=="delete")
                    $count = -1;


                $arResult = Bitrix\CPhoenixReviewsInfo\CPhoenixReviewsInfoTable::getList(array('filter' => array('=PRODUCT_ID' => $arParams["PRODUCT_ID"]),"cache"=>array("ttl"=>3600)))->fetch();

                if(!empty($arResult))
                {
                    foreach ($arResult as $key => $value) {
                        $arResult[$key] = intval($value);
                    }
                }
                else
                {
                    Bitrix\CPhoenixReviewsInfo\CPhoenixReviewsInfoTable::add(array("PRODUCT_ID"=>$arParams["PRODUCT_ID"]));
                    $arResult = Bitrix\CPhoenixReviewsInfo\CPhoenixReviewsInfoTable::getList(array('filter' => array('=PRODUCT_ID' => $arParams["PRODUCT_ID"]),"cache"=>array("ttl"=>3600)))->fetch();
                }


                $arFields = array();
                $userFields = array();

                if($arParams["RECOMMEND"] !== NULL)
                {
                    $userFields["RECOMMEND"] = $arParams["RECOMMEND"];

                    if($userFields["RECOMMEND"] == "Y")
                        $arFields["RECOMMEND_COUNT_Y"] = $arResult["RECOMMEND_COUNT_Y"] + $count;

                    else if($userFields["RECOMMEND"] == "N")
                        $arFields["RECOMMEND_COUNT_N"] = $arResult["RECOMMEND_COUNT_N"] + $count;
                }

                if($arParams["VOTE"] !== NULL)
                {
                    $userFields["VOTE"] = intval($arParams["VOTE"]);

                    if($userFields["VOTE"]==1 || $userFields["VOTE"]==2)
                        $arFields["VOTE_COUNT_1_2"] = $arResult["VOTE_COUNT_1_2"] + $count;

                    else if($userFields["VOTE"]==3)
                        $arFields["VOTE_COUNT_3"] = $arResult["VOTE_COUNT_3"] + $count;

                    else if($userFields["VOTE"]==4)
                        $arFields["VOTE_COUNT_4"] = $arResult["VOTE_COUNT_4"] + $count;

                    else if($userFields["VOTE"]==5)
                        $arFields["VOTE_COUNT_5"] = $arResult["VOTE_COUNT_5"] + $count;


                    $arFields["VOTE_COUNT"] = $arResult["VOTE_COUNT"] + $count;
                        

                   
                    if($count == 1)
                        $arFields["VOTE_SUM"] = $arResult["VOTE_SUM"] + $userFields["VOTE"];
                    else if($count == -1)
                        $arFields["VOTE_SUM"] = $arResult["VOTE_SUM"] - $userFields["VOTE"];



                    if($arFields["VOTE_COUNT"] <=0)
                    {
                        $arFields["VOTE_COUNT"] = 0;
                        $arFields["VOTE_SUM"] = 0;
                    }
                    
                }

                $arFields["REVIEWS_COUNT"] = $arResult["REVIEWS_COUNT"] + $count;

                if($arFields["REVIEWS_COUNT"] <= 0)
                {
                  $arFields["REVIEWS_COUNT"] = 0;
                  $arFields["VOTE_COUNT"] = 0;
                  $arFields["VOTE_SUM"] = 0;
                  $arFields["VOTE_COUNT_1_2"] = 0;
                  $arFields["VOTE_COUNT_3"] = 0;
                  $arFields["VOTE_COUNT_4"] = 0;
                  $arFields["VOTE_COUNT_5"] = 0;
                  $arFields["RECOMMEND_COUNT_Y"] = 0;
                  $arFields["RECOMMEND_COUNT_N"] = 0;
                }


                $result = Bitrix\CPhoenixReviewsInfo\CPhoenixReviewsInfoTable::update($arResult["ID"], $arFields);
                
                if ($result->isSuccess())
                    return true;

            }

        }

        return false;
    }



    public static function setMess($arParams = array()){
        global $PHOENIX_TEMPLATE_ARRAY;


        if(!isset($PHOENIX_TEMPLATE_ARRAY["MESS"]) && empty($PHOENIX_TEMPLATE_ARRAY["MESS"]))
        {
            $PHOENIX_TEMPLATE_ARRAY["MESS"] = array();
            if(defined('SITE_TEMPLATE_PATH'))
            {
                if(file_exists($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/custom_lang/".LANGUAGE_ID."_lang.php"))
                {
                    __IncludeLang($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/custom_lang/".LANGUAGE_ID."_lang.php");
                }
            }
        }


        if(!empty($arParams))
        {
            foreach ($arParams as $key => $value){

                if($value == "region" && !isset($PHOENIX_TEMPLATE_ARRAY["MESS"]["REGION"]))
                {
                    $arMess = array(
                        "ERROR_INPUT" => getMessage("PHOENIX_MESS_REGION_ERROR_INPUT"),
                        "CURRENT" => getMessage("PHOENIX_MESS_REGION_CURRENT"),
                        "OK" => getMessage("PHOENIX_MESS_REGION_OK"),
                        "CHANGE" => getMessage("PHOENIX_MESS_REGION_CHANGE"),
                        "CHOOSE_TITLE" => getMessage("PHOENIX_MESS_REGION_CHOOSE_TITLE"),
                        "INPUT_CITY_TITLE" => getMessage("PHOENIX_MESS_REGION_INPUT_CITY_TITLE"),
                        "INPUT_CITY_TITLE_2" => getMessage("PHOENIX_MESS_REGION_INPUT_CITY_TITLE_2"),
                        "BTN_SUBMIT" => getMessage("PHOENIX_MESS_REGION_BTN_SUBMIT"),
                    );

                    $PHOENIX_TEMPLATE_ARRAY["MESS"]["REGION"] = $arMess;

                    unset($arMess);
                }

                else if($value == "rating" && !isset($PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]))
                {

                    $arMess = array(
                        "PANEL_NAME" => getMessage("PHOENIX_MESS_RATING_PANEL_NAME"),
                        "PANEL_DESC" => getMessage("PHOENIX_MESS_RATING_PANEL_DESC"),
                        "PANEL_DESC_CNT_1" => getMessage("PHOENIX_MESS_RATING_PANEL_DESC_CNT_1"),
                        "PANEL_DESC_CNT_2" => getMessage("PHOENIX_MESS_RATING_PANEL_DESC_CNT_2"),
                        "PANEL_DESC_CNT_3" => getMessage("PHOENIX_MESS_RATING_PANEL_DESC_CNT_3"),
                        "PANEL_DESC_CNT_4" => getMessage("PHOENIX_MESS_RATING_PANEL_DESC_CNT_4"),
                        "PANEL_DESC_PERSON_1" => getMessage("PHOENIX_MESS_RATING_PANEL_DESC_PERSON_1"),
                        "PANEL_DESC_PERSON_2" => getMessage("PHOENIX_MESS_RATING_PANEL_DESC_PERSON_2"),
                        "PANEL_DESC_PERSON_3" => getMessage("PHOENIX_MESS_RATING_PANEL_DESC_PERSON_3"),
                        "PANEL_DESC_PERSON_4" => getMessage("PHOENIX_MESS_RATING_PANEL_DESC_PERSON_4"),
                        "RECOM_NAME" => getMessage("PHOENIX_MESS_RATING_RECOM_NAME"),
                        "RECOM_DESC" => getMessage("PHOENIX_MESS_RATING_RECOM_DESC"),
                        "PANEL_DESC_PERSON_1_4" => getMessage("PHOENIX_MESS_REVIEWS_PANEL_DESC_PERSON_1_4"),
                        "CONTAINER_DESC_CNT_1" => getMessage("PHOENIX_MESS_RATING_CONTAINER_DESC_CNT_1"),
                        "CONTAINER_DESC_CNT_2" => getMessage("PHOENIX_MESS_RATING_CONTAINER_DESC_CNT_2"),
                        "CONTAINER_DESC_CNT_3" => getMessage("PHOENIX_MESS_RATING_CONTAINER_DESC_CNT_3"),
                        "CONTAINER_DESC_CNT_4" => getMessage("PHOENIX_MESS_RATING_CONTAINER_DESC_CNT_4"),
                    );

                    $PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"] = $arMess;

                    unset($arMess);
                }

                else if($value == "review" && !isset($PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]))
                {
                    $arMess = array(
                        "MAIN_TITLE" => getMessage("PHOENIX_MESS_REVIEW_MAIN_TITLE"),
                        "VOTE_PANEL_NAME" => getMessage("PHOENIX_MESS_REVIEW_VOTE_PANEL_NAME"),
                        "VOTE_PANEL_REQUIRE" => getMessage("PHOENIX_MESS_REVIEW_VOTE_PANEL_REQUIRE"),
                        "ROW_1_TITLE" => getMessage("PHOENIX_MESS_REVIEW_ROW_1_TITLE"),
                        "ROW_1_VAL_1" => getMessage("PHOENIX_MESS_REVIEW_ROW_1_VAL_1"),
                        "ROW_1_VAL_2" => getMessage("PHOENIX_MESS_REVIEW_ROW_1_VAL_2"),
                        "ROW_2_TITLE" => getMessage("PHOENIX_MESS_REVIEW_ROW_2_TITLE"),
                        "ROW_2_VAL_1" => getMessage("PHOENIX_MESS_REVIEW_ROW_2_VAL_1"),
                        "ROW_2_VAL_2" => getMessage("PHOENIX_MESS_REVIEW_ROW_2_VAL_2"),
                        "ROW_2_VAL_3" => getMessage("PHOENIX_MESS_REVIEW_ROW_2_VAL_3"),
                        "ROW_3_TITLE" => getMessage("PHOENIX_MESS_REVIEW_ROW_3_TITLE"),
                        "ROW_3_VAL_1" => getMessage("PHOENIX_MESS_REVIEW_ROW_3_VAL_1"),
                        "ROW_3_VAL_2" => getMessage("PHOENIX_MESS_REVIEW_ROW_3_VAL_2"),
                        "ROW_3_VAL_3" => getMessage("PHOENIX_MESS_REVIEW_ROW_3_VAL_3"),
                        "ADVS" => getMessage("PHOENIX_MESS_REVIEW_ADVS"),
                        "DISADVS" => getMessage("PHOENIX_MESS_REVIEW_DISADVS"),
                        "LOAD_IMAGES" => getMessage("PHOENIX_MESS_REVIEW_LOAD_IMAGES"),
                        "USER_NAME" => getMessage("PHOENIX_MESS_REVIEW_USER_NAME"),
                        "USER_EMAIL" => getMessage("PHOENIX_MESS_REVIEW_USER_EMAIL"),
                        "USER_ACCOUNT_NUMBER" => getMessage("PHOENIX_MESS_REVIEW_USER_ACCOUNT_NUMBER"),
                        "BTN_NAME" => getMessage("PHOENIX_MESS_REVIEW_BTN_NAME"),
                        "DESC" => getMessage("PHOENIX_MESS_REVIEW_DESC"),
                        "FILTER_MENU_ALL" => getMessage("PHOENIX_MESS_REVIEW_FILTER_MENU_ALL"),
                        "FILTER_MENU_1" => getMessage("PHOENIX_MESS_REVIEW_FILTER_MENU_1"),
                        "FILTER_MENU_2" => getMessage("PHOENIX_MESS_REVIEW_FILTER_MENU_2"),
                        "FILTER_MENU_3" => getMessage("PHOENIX_MESS_REVIEW_FILTER_MENU_3"),
                        "FILTER_MENU_4" => getMessage("PHOENIX_MESS_REVIEW_FILTER_MENU_4"),
                        "FILTER_MENU_5" => getMessage("PHOENIX_MESS_REVIEW_FILTER_MENU_5"),
                        "RECOMMEND" => getMessage("PHOENIX_MESS_REVIEW_RECOMMEND"),
                        "NORECOMMEND" => getMessage("PHOENIX_MESS_REVIEW_NORECOMMEND"),
                        "AGREE" => getMessage("PHOENIX_MESS_REVIEW_AGREE"),
                        "EXP" => getMessage("PHOENIX_MESS_REVIEW_EXP"),
                        "ANSWER_SALE" => getMessage("PHOENIX_MESS_REVIEW_ANSWER_SALE"),
                        "BTN_USER_REVIEW" => getMessage("PHOENIX_MESS_REVIEW_BTN_USER_REVIEW"),
                        "EMPTY_REVIEWS" => getMessage("PHOENIX_MESS_REVIEW_EMPTY_REVIEWS"),
                        "USER_NAME_GUEST" => getMessage("PHOENIX_MESS_REVIEW_USER_NAME_GUEST"),
                        "USER_NAME_DEF" => getMessage("PHOENIX_MESS_REVIEW_USER_NAME_DEF"),
                        "BTN_MORE_REVIEWS" => getMessage("PHOENIX_MESS_REVIEW_BTN_MORE_REVIEWS"),
                        "BTN_MORE_REVIEWS_CNT_1" => getMessage("PHOENIX_MESS_REVIEW_BTN_MORE_REVIEWS_CNT_1"),
                        "BTN_MORE_REVIEWS_CNT_2" => getMessage("PHOENIX_MESS_REVIEW_BTN_MORE_REVIEWS_CNT_2"),
                        "BTN_MORE_REVIEWS_CNT_3" => getMessage("PHOENIX_MESS_REVIEW_BTN_MORE_REVIEWS_CNT_3"),
                        "BTN_MORE_REVIEWS_CNT_4" => getMessage("PHOENIX_MESS_REVIEW_BTN_MORE_REVIEWS_CNT_4")
                    );

                    $PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"] = $arMess;

                    unset($arMess);
                }

                else if($value == "settings" && !isset($PHOENIX_TEMPLATE_ARRAY["MESS"]["SETTINGS"]))
                {
                    $arMess = array(
                        "EDIT" => getMessage("PHOENIX_MESS_EDIT")
                    );

                    $PHOENIX_TEMPLATE_ARRAY["MESS"]["SETTINGS"] = $arMess;

                    unset($arMess);
                }
                else if($value == "comments" && !isset($PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]))
                {
                    $arMess = array(
                        "EMPTY_REVIEWS" => getMessage("PHOENIX_MESS_COMMENTS_EMPTY_REVIEWS"),
                        "TITLE_BLOCK" => getMessage("PHOENIX_MESS_COMMENTS_TITLE_BLOCK"),
                        "BTN_MORE" => getMessage("PHOENIX_MESS_COMMENTS_BTN_MORE"),
                        "BTN_MORE_COMMENTS_CNT_1" => getMessage("PHOENIX_MESS_COMMENTS_BTN_MORE_COMMENTS_CNT_1"),
                        "BTN_MORE_COMMENTS_CNT_2" => getMessage("PHOENIX_MESS_COMMENTS_BTN_MORE_COMMENTS_CNT_2"),
                        "BTN_MORE_COMMENTS_CNT_3" => getMessage("PHOENIX_MESS_COMMENTS_BTN_MORE_COMMENTS_CNT_3"),
                        "BTN_MORE_COMMENTS_CNT_4" => getMessage("PHOENIX_MESS_COMMENTS_BTN_MORE_COMMENTS_CNT_4"),
                        "TEXTAREA_DESCRIPTION" => getMessage("PHOENIX_MESS_COMMENTS_TEXTAREA_DESCRIPTION"),
                        "SEND_BTN_NAME" => getMessage("PHOENIX_MESS_COMMENTS_SEND_BTN_NAME"),
                        "USER_NAME_DEF" => getMessage("PHOENIX_MESS_COMMENTS_USER_NAME_DEF"),
                        "USER_NAME_GUEST" => getMessage("PHOENIX_MESS_COMMENTS_USER_NAME_GUEST"),
                        "SUCCESS_MESSAGE" => getMessage("PHOENIX_MESS_COMMENTS_SUCCESS_MESSAGE"),
                        "SUCCESS_MESSAGE_MODERATOR" => getMessage("PHOENIX_MESS_COMMENTS_SUCCESS_MESSAGE_MODERATOR"),
                        "MESSAGE_AUTH" => getMessage("PHOENIX_MESS_COMMENTS_MESSAGE_AUTH"),
                        "RESPONSE_TEXT" => getMessage("PHOENIX_MESS_COMMENTS_RESPONSE_TEXT"),
                        "USER_NAME_TITLE" => getMessage("PHOENIX_MESS_COMMENTS_USER_NAME_TITLE"),
                        "USER_EMAIL_TITLE" => getMessage("PHOENIX_MESS_COMMENTS_USER_EMAIL_TITLE"),
                        "ELEMENT_TITLE" => getMessage("PHOENIX_MESS_COMMENTS_ELEMENT_TITLE"),
                        "TEXT_TITLE" => getMessage("PHOENIX_MESS_COMMENTS_TEXT_TITLE"),
                        "DATE_TITLE" => getMessage("PHOENIX_MESS_COMMENTS_DATE_TITLE"),
                        "DETAIL_PAGE_URL_TITLE" => getMessage("PHOENIX_MESS_COMMENTS_DETAIL_PAGE_URL_TITLE"),
                        "DELETE_BTN_NAME" => getMessage("PHOENIX_MESS_COMMENTS_DELETE_BTN_NAME"),
                        "FORM_NAME" => getMessage("PHOENIX_MESS_COMMENTS_FORM_NAME"),
                        "FORM_EMAIL" => getMessage("PHOENIX_MESS_COMMENTS_FORM_EMAIL"),
                        "TYPE_ELEMENT_news" => getMessage("PHOENIX_MESS_COMMENTS_TYPE_ELEMENT_news"),
                        "TYPE_ELEMENT_blog" => getMessage("PHOENIX_MESS_COMMENTS_TYPE_ELEMENT_blog"),
                        "TYPE_ELEMENT_action" => getMessage("PHOENIX_MESS_COMMENTS_TYPE_ELEMENT_action"),
                    );

                    $PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"] = $arMess;

                    unset($arMess);
                }
            }

        }
        
    }
    public static function getBtnAttrs($arParams = array()){
        $attr = '';

        if($arParams["ACTION"] == "form" && strlen($arParams["FORM_ID"])>0)
            $attr = "data-header='".str_replace("'","\"",strip_tags(htmlspecialcharsBack($arParams["HEADER"])))."' data-call-modal='form".$arParams["FORM_ID"]."'";

        elseif($arParams["ACTION"] == "modal" && strlen($arParams["POPUP_ID"])>0)
            $attr = "data-call-modal='modal".$arParams["POPUP_ID"]."'";

        elseif($arParams["ACTION"] == "block" && strlen($arParams["URL"])>0)
            $attr = "href = '#block".$arParams["URL"]."'";

        elseif($arParams["ACTION"] == "blank" && strlen($arParams["URL"])>0)
        {
            $attr = "href = '".$arParams["URL"]."'";

            if($arParams["TARGET_BLANK"] == "Y")
                $attr .= " target='_blank'";
        }


        elseif($arParams["ACTION"] == "quiz" && strlen($arParams["QUIZ_ID"])>0)
            $attr = "data-wqec-section-id='".$arParams["QUIZ_ID"]."'";

        elseif($arParams["ACTION"] == "land" && strlen($arParams["LAND_ID"])>0)
        {
            $code = 'concept_phoenix_site_'.$arParams["SITE_ID"];
            $arFilter = Array('IBLOCK_CODE' => $code, 'ID' => $arParams["LAND_ID"], "ACTIVE"=>"Y");
            $dbResSect = CIBlockSection::GetList(Array("sort"=>"asc"), $arFilter, false, array("ID", "SECTION_PAGE_URL"));
            
            if($sectRes = $dbResSect->GetNext())
                $land_link = $sectRes['SECTION_PAGE_URL'];

            $attr = "href = '".$land_link."'";

            if($arParams["TARGET_BLANK"] == "Y")
                $attr .= " target='_blank'";
        }

        elseif($arParams["ACTION"] == "fast_order" && strlen($arParams["PRODUCT_ID"])>0)
        {
            $productItem = self::getCatalogItemProps($arParams["PRODUCT_ID"]);

            if($productItem["AVAILABLE"]=="Y")
                $attr = "data-param-product=\"".CUtil::PhpToJSObject($productItem, false, true)."\"";
            
            else
                $attr = "style='display: none;'";
        }

        elseif($arParams["ACTION"] == "add_to_cart" && strlen($arParams["PRODUCT_ID"])>0)
        {
            $productItem = self::getCatalogItemProps($arParams["PRODUCT_ID"]);

            if($productItem["AVAILABLE"]=="Y")
                $attr = "data-param-product=\"".CUtil::PhpToJSObject($productItem, false, true)."\" data-item = \"".$productItem["PRODUCT_ID"]."\"";    
            else
                $attr = "data-style='d-none'";
        }
        
        return $attr;
    }

    public static function getBtnClass ($arParams = array())
    {
        $classes = '';

        if($arParams["ACTION"] == "form" && strlen($arParams["FORM_ID"])>0)
            $classes = 'call-modal callform';

        elseif($arParams["ACTION"] == "modal" && strlen($arParams["POPUP_ID"])>0)
            $classes = 'call-modal callmodal';

        elseif($arParams["ACTION"] == "block")
            $classes = 'scroll';

        elseif($arParams["ACTION"] == "quiz" && strlen($arParams["QUIZ_ID"])>0)
            $classes = 'call-wqec';

        elseif($arParams["ACTION"] == "fast_order" && strlen($arParams["PRODUCT_ID"])>0)
            $classes = 'callFastOrder';

        elseif($arParams["ACTION"] == "add_to_cart" && strlen($arParams["PRODUCT_ID"])>0)
            $classes = 'common-btn-basket-style btn-add2basket';
 

        return $classes;
    }

    public static function createBtnHtml($arParams = array()){

        global $PHOENIX_TEMPLATE_ARRAY;



        $html = "";

        $arParams["ACTION"] = ($arParams["ACTION"]=="")?"form":$arParams["ACTION"];
        $arParams["SITE_ID"] = $PHOENIX_TEMPLATE_ARRAY["SITE_ID"];

        $attrs = CPhoenix::getBtnAttrs($arParams);
        $class = CPhoenix::getBtnClass($arParams);


        if(strlen($arParams["BTN_NAME"]))
        {

            $html .= "<a ";

                $html .= (strlen($arParams["ONCLICK"]))?"onclick='".str_replace("'", "\"", $arParams["ONCLICK"])."'":"";

                $html .= "class='";
                    $html .= "btn-generate button-def ";

                    if( $arParams["VIEW"] == "empty" )
                        $html .= " secondary ";

                    else if( $arParams["VIEW"] == "shine" )
                        $html .= " shine main-color ";

                    else
                        $html .= " main-color ";


                    $html .= $class;
                $html .= "' ";

                $html .= $attrs;

                if($arParams["BG_COLOR"] && $arParams["VIEW"] != "empty" )
                    $html .= " style='background-color: ".$arParams["BG_COLOR"].";' ";

            $html .= ">";

                $html .= $arParams["~BTN_NAME"];

            $html .= "</a>";


            if($arParams["ACTION"] == "add_to_cart")
            {
                $html .= "<a ";
                    $html .= "href='".$PHOENIX_TEMPLATE_ARRAY["BASKET_URL"]."'";

                    $html .= "class='";

                        $html .= "btn-generate button-def common-btn-basket-style-added btn-added";

                    $html .= "'";


                $html .= ">";

                    $html .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];

                $html .= "</a>";
            }
        }

        echo $html;

    }

    public static function getIblockIDs($arParams = array())
    {


        CModule::IncludeModule("iblock");
        global $PHOENIX_TEMPLATE_ARRAY;


        if(!empty($arParams))
        {
            if(!empty($arParams["CODES"]))
            {
                foreach ($arParams["CODES"] as $key => $value) {
                    if(isset($PHOENIX_TEMPLATE_ARRAY["IBLOCKS"][$value]))
                        unset($arParams["CODES"][$key]);
                }
            }


            if(!empty($arParams["CODES"]))
            {
                $siteId = $arParams["SITE_ID"];
                $res = CIBlock::GetList(
                    Array(), 
                    Array(
                        'TYPE'=> "concept_phoenix_".$siteId, 
                        'SITE_ID'=> $siteId,
                        'CODE'=> $arParams["CODES"]
                    ), false
                );

                while($ar_res = $res->Fetch())
                {
                    if(strpos($ar_res["CODE"], "concept_phoenix_agreements_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_agreements_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['AGREEMENT']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['AGREEMENT']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['AGREEMENT']["IBLOCK_CODE"] = $ar_res['CODE'];

                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_agreements_".$siteId] = "Y";
                    }


                    else if(strpos($ar_res["CODE"], "concept_phoenix_modals_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_modals_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['POPUP']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['POPUP']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['POPUP']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_modals_".$siteId] = "Y";
                    }

                    else if(strpos($ar_res["CODE"], "concept_phoenix_forms_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_forms_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_forms_".$siteId] = "Y";
                    }

                    else if(strpos($ar_res["CODE"], "concept_phoenix_faq_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_faq_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FAQ']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FAQ']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FAQ']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_faq_".$siteId] = "Y";
                    }

                    else if(strpos($ar_res["CODE"], "concept_phoenix_requests_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_requests_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REQUESTS']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REQUESTS']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REQUESTS']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_requests_".$siteId] = "Y";
                    }

                    else if(strpos($ar_res["CODE"], "concept_phoenix_advantages_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_advantages_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ADVS']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ADVS']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ADVS']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_advantages_".$siteId] = "Y";
                    }

                    else if(strpos($ar_res["CODE"], "concept_phoenix_site_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_site_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CONSTR']["IBLOCK_ID"] = $PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CONSTR']["IBLOCK_TYPE"] = $PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CONSTR']["IBLOCK_CODE"] = $PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_site_".$siteId] = "Y";
                    }

                    else if(strpos($ar_res["CODE"], "concept_phoenix_catalog_offers_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_catalog_offers_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_catalog_offers_".$siteId] = "Y";
                    }

                    else if(strpos($ar_res["CODE"], "concept_phoenix_catalog_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_catalog_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_catalog_".$siteId] = "Y";
                    }
                    
                    else if(strpos($ar_res["CODE"], "concept_phoenix_footer_menu_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_footer_menu_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FOOTER_MENU']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FOOTER_MENU']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FOOTER_MENU']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_footer_menu_".$siteId] = "Y";
                    }
                    else if(strpos($ar_res["CODE"], "concept_phoenix_menu_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_menu_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['MENU']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['MENU']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['MENU']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_menu_".$siteId] = "Y";
                    }

                    else if(strpos($ar_res["CODE"], "concept_phoenix_brand_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_brand_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BRAND']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BRAND']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BRAND']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_brand_".$siteId] = "Y";
                    }

                    else if(strpos($ar_res["CODE"], "concept_phoenix_banners_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_banners_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_banners_".$siteId] = "Y";
                    }

                    else if(strpos($ar_res["CODE"], "concept_phoenix_blog_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_blog_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BLOG']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BLOG']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BLOG']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_blog_".$siteId] = "Y";
                    }

                    else if(strpos($ar_res["CODE"], "concept_phoenix_news_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_news_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['NEWS']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['NEWS']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['NEWS']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_news_".$siteId] = "Y";
                    }

                    else if(strpos($ar_res["CODE"], "concept_phoenix_action_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_action_".$siteId) === 0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ACTIONS']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ACTIONS']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ACTIONS']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_action_".$siteId] = "Y";
                    }
                    else if(strpos($ar_res["CODE"], "concept_phoenix_regions_".$siteId) !== false && strpos($ar_res["CODE"], "concept_phoenix_regions_".$siteId) === 0)
                    {

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REGION']["IBLOCK_ID"] = intval($ar_res['ID']);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REGION']["IBLOCK_TYPE"] = $ar_res['IBLOCK_TYPE_ID'];
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REGION']["IBLOCK_CODE"] = $ar_res['CODE'];
                        $PHOENIX_TEMPLATE_ARRAY["IBLOCKS"]["concept_phoenix_regions_".$siteId] = "Y";

                    }
                     
                }

               
            }
            
        }


    }

    public static function GetRatingContainerVoteHTML($arParams)
    {

        $ratingMode = isset($arParams["RATING_MODE"]) && $arParams["RATING_MODE"] == "Y";

        $arParams["CLASS"] = (strlen($arParams["CLASS"])>0)?$arParams["CLASS"]:"";

        if($ratingMode && isset($arParams["RATING"]))
        {

            $arParams["RATING"] = intval(round($arParams["RATING"]));

            if($arParams["RATING"] <= 0)
                $arParams["RATING"] = 0;

            else if($arParams["RATING"] > 5)
                $arParams["RATING"] = 5;
        }

        ?>
        <div class="stars_container clearfix <?=$arParams["CLASS"]?>" <?if(isset($arParams["ID"])):?>data-item-id="vote-block-<?=$arParams["ID"]?>" data-rating = "" <?endif;?> <?=(isset($arParams["ATTR"]))? $arParams["ATTR"]:"";?>>
            <?for ($i=1; $i <= 5; $i++){?>
                <div class="star <?=($ratingMode && $i <= $arParams["RATING"])? "active":"";?>" data-point="<?=$i?>"></div>
            <?}?>

        </div>
        
    <?}

    public static function GetRatingVoteHTML($arParams)
    {   
        global $PHOENIX_TEMPLATE_ARRAY;
        ?>

        

            <?if($arParams["VIEW"] == "rating-reviewsCount"):?>

                <div class="row no-gutters rating-reviewsCount">
                    <div class="col-auto">

            <?endif;?>


            <div class="rating-container row no-gutters align-items-center">
                <div class="rating-description"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING_NAME"]?>:&nbsp;</div>
                
                <?self::GetRatingContainerVoteHTML(array("ID"=>$arParams["ID"], "CLASS"=>$arParams["CLASS"]))?>
                  
            </div>


        <?if($arParams["VIEW"] == "rating-reviewsCount"):?>
                </div>

                <div class="col">
                    <?if(isset($arParams["HREF"])):?><a href="<?=$arParams["HREF"]?>" class="scroll"><?endif;?>
                        <div class="reviews-count" data-item-id="<?=$arParams['ID']?>"></div>
                    <?if(isset($arParams["HREF"])):?></a><?endif;?>
                </div>
            </div>
        <?endif;?>

        


        <?
    }

    public static function GetOffersName($arOffersID)
    {
        global $PHOENIX_TEMPLATE_ARRAY;

        if(!Loader::includeModule('iblock'))
            return;

        CPhoenixSku::getHIBlockOptions();

        $arResult = array();

        if($PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] == "Y" && !empty($arOffersID))
        {
            $props = array();
            

            $arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"], "ID" => $arOffersID);
            $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false);

            while($ob = $res->GetNextElement())
            {
                $fields = $ob->GetFields();
                $props = $ob->GetProperties();

                if( !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"]) )
                {
                    $nameOffers = "";

                    foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"] as $arOfferField)
                    {

                        if( $props[$arOfferField]["USER_TYPE"] == "directory" && strlen($props[$arOfferField]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]))
                        {
                            foreach ($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"] as $arSKUProp)
                            {
                                if( $props[$arOfferField]['USER_TYPE_SETTINGS']['TABLE_NAME'] == $arSKUProp['TABLE_NAME'] && strlen($arSKUProp["VALUE_NAME"][$props[$arOfferField]["VALUE"]]))
                                {
                                    $nameOffers .= "<div>".$props[$arOfferField]["NAME"].":&nbsp;".$arSKUProp["VALUE_NAME"][$props[$arOfferField]["VALUE"]]."</div>";
                                }
                            }
                        }
                        else if(strlen($props[$arOfferField]["VALUE"]))
                        {
                            $nameOffers .= "<div>".$props[$arOfferField]["NAME"].":&nbsp;".$props[$arOfferField]["VALUE"]."</div>";
                        }

                        $arResult[$fields["ID"]] = $nameOffers;
                    }
                }
            }

        }

        return $arResult;
    }

    public static function GetCurrentMainPageCodeForSearch($iblockID)
    {
        global $PHOENIX_TEMPLATE_ARRAY;

        $res = "";

       
        if($iblockID == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"])
            $res = "CATALOG";

        if($iblockID == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BLOG']["IBLOCK_ID"])
            $res = "BLOG";

        if($iblockID == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['NEWS']["IBLOCK_ID"])
            $res = "NEWS";

        if($iblockID == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ACTIONS']["IBLOCK_ID"])
            $res = "ACTIONS";
        

        return $res;
    }

    
    public static function ShowCabinetMenu()
    {
        global $APPLICATION, $PHOENIX_TEMPLATE_ARRAY;

        $APPLICATION->IncludeComponent("concept:phoenix.personal.menu", "",
            array()
        );

    }
    
    public static function IssetOffers($arResult)
    {
        $isset = false;

        if(!empty($arResult["ITEMS"]))
        {
            foreach ($arResult["ITEMS"] as $arItem){

                if(isset($arItem["OFFERS"]) && !empty($arItem["OFFERS"]))
                {
                    $isset = true;
                    break;
                }
            }
        }
        return $isset;
    }

    public static function SetElementProperties(&$arResult)
    {
        if(!Loader::includeModule('iblock'))
            return;
        
        if($arResult["IBLOCK_ID"] && $arResult["ID"] && empty($arResult["PROPERTIES"]))
        {
            $arResult["PROPERTIES"] = array();

            $arFilter = Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "ID" => $arResult["ID"]);
            $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false);

            while($ob = $res->GetNextElement())
                $arResult["PROPERTIES"] = $ob->GetProperties();
        }
        
        return true;
    }

    public static function SetElementOffersProperties($iblockID, &$arResult)
    {
        if(!Loader::includeModule('iblock'))
            return;

        if(!empty($arResult['OFFERS']))
        {
            $getProperties = false;
            $i = 0;
            foreach ($arResult["OFFERS"] as $arOffers) {
                if($i==2)
                    break;

                if(empty($arOffers["PROPERTIES"]))
                    $getProperties = true;

                $i++;
            }

            if($getProperties)
            {

                $arrItemsOffers = array();
                $arPropertiesOffers = array();
        
                foreach ($arResult['OFFERS'] as $arOffers)
                    $arrItemsOffers[] = $arOffers["ID"];

            
                $arFilter = Array("IBLOCK_ID"=>$iblockID, "ID" => $arrItemsOffers);
                $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false);

                while($ob = $res->GetNextElement())
                {
                    $fields = $ob->GetFields();
                    $arPropertiesOffers[$fields["ID"]] = $ob->GetProperties();
                }

                foreach ($arResult['OFFERS'] as $offerKey => $value)
                    $arResult['OFFERS'][$offerKey]["PROPERTIES"] = $arPropertiesOffers[$value["ID"]];
            }

        }

        return true;
    }


    public static function SetResultModifierCatalogElements(&$arResult, &$arParams, $arEditAreaId = array())
    {

        if( !empty($arResult["ITEMS"]) )
        {

            if(!Loader::includeModule('iblock'))
                return;

            global $PHOENIX_TEMPLATE_ARRAY;

            // set Properties

                $arrItems = array();
                $arrItemsOffers = array();
                $getProperties = false;
                $getPropertiesOffers = false;

                foreach ($arResult["ITEMS"] as $arItem)
                {
                      if(!empty($arItem["OFFERS"]))
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

                    foreach ($arResult["ITEMS"] as $keyItem => $arItem)
                    {

                        if($getProperties)
                              $arResult["ITEMS"][$keyItem]["PROPERTIES"] = $arProperties[$arItem["ID"]];


                        if( !empty($arItem['OFFERS']) && $PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] == "Y" && $getPropertiesOffers)
                        {

                            foreach ($arItem['OFFERS'] as $offerKey => $value)
                            {

                                $arResult["ITEMS"][$keyItem]['OFFERS'][$offerKey]["PROPERTIES"] = $arPropertiesOffers[$value["ID"]];
                            }
                        }
                    }
                }

                
                foreach ($arResult["ITEMS"] as $keyItem => $arItem)
                {
                    if($arItem["PROPERTIES"]["MODE_HIDE"]["VALUE"]=="Y")
                        unset($arResult["ITEMS"][$keyItem]);
                }



            // prepare Items
                if(!empty($arResult["ITEMS"]))
                {
                    $arResult["ITEMS"] = array_values($arResult["ITEMS"]);

                    $arProductsAmountByStore = array();

                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["SHOW_RESIDUAL"]["VALUE"]["ACTIVE"] === "Y" && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"]))
                        $arProductsAmountByStore = CPhoenix::getProductsAmountByStore($arResult['ITEMS']);

                    
                    $arNewItem = array();

                    foreach ($arResult["ITEMS"] as $keyItem => $arItem)
                    {
                        $arItem['OBJ_ID'] = $arEditAreaId[$arItem['ID']];
                        $arNewItem[$keyItem] = CPhoenix::parseProduct($arItem, array(
                                "PRODUCTS_AMOUNT"=>$arProductsAmountByStore,
                                "VIEW"=>$arResult['VIEW'],
                                "USE_PRICE_COUNT"=>$arParams["USE_PRICE_COUNT"]
                            )
                        );
                    }



                              


                    $arResult["ITEMS"] = $arNewItem;


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



        }


        
        return true;
    }

    public static function GetSkuProperties($arParams)
    {
        if(!Loader::includeModule('catalog') || !Loader::includeModule('iblock'))
            return;


        $arResult =
        $arSKUPropList = 
        $arSKU = 
        $arSKUPropIDs = 
        $skuUserPropList = 
        $arSKUPropKeys = 
        $arTypeString = array();
        if($arParams["IBLOCK_ID"])
        {
            $arInfoSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);


            if(isset($arParams["SKU_FIELDS"]) && !empty($arParams["SKU_FIELDS"]))
            {
                $arSKUPropList = CIBlockPriceTools::getTreeProperties(
                    $arInfoSKU,
                    $arParams["SKU_FIELDS"],
                    array(
                        'NAME' => '-'
                    )
                );

                $arSKUPropIDs = array_keys($arSKUPropList);

                $skuUserPropList = CPhoenixSku::getUserPropsForTree(
                    $arParams["SKU_FIELDS"], 
                    $arSKUPropIDs, 
                    $arParams["SITE_ID"]
                );




                if(!empty($skuUserPropList))
                    $arSKUPropList = $arSKUPropList+$skuUserPropList;




                $arSKUPropIDs = array_keys($arSKUPropList);
                $arSKUPropKeys = array_fill_keys($arSKUPropIDs, false);

            }

        }


        if(!empty($arSKUPropList) && isset($arParams["OFFERS"]) && !empty($arParams["OFFERS"]))
        {
            $boolSKUDisplayProps = false;

            $arResultSKUPropIDs = 
            $arFilterProp =
            $arNeedValues = array();

            if(!empty($arSKUPropIDs))
            {
                foreach ($arParams["OFFERS"] as $keyOffer => $valueOffer)
                {
                    foreach ($arSKUPropIDs as $keyPropId => $valuePropId)
                    {
                        if (isset($valueOffer['PROPERTIES'][$valuePropId]))
                        {
                            $arResultSKUPropIDs[$valuePropId] = true;

                            $valueId = (
                                $arSKUPropList[$valuePropId]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST
                                ? $valueOffer['PROPERTIES'][$valuePropId]['VALUE_ENUM_ID']
                                : $valueOffer['PROPERTIES'][$valuePropId]['VALUE']
                            );

                            if($valueId)
                            {
                                $arFilterProp[$valuePropId] = true;

                                if($arSKUPropList[$valuePropId]['LIST_TYPE'] == "L")
                                {
                                    $valueId = md5($valueOffer['PROPERTIES'][$valuePropId]["VALUE"]);

                                    $arTypeString[$valuePropId]["NAME"] = $valueOffer['PROPERTIES'][$valuePropId]["NAME"];
                                    $arTypeString[$valuePropId]["ID"] = $valueOffer['PROPERTIES'][$valuePropId]["ID"];
                                    $arTypeString[$valuePropId]["PROPERTY_TYPE"] = $valueOffer['PROPERTIES'][$valuePropId]["PROPERTY_TYPE"];

                                    if( empty($arTypeString[$valuePropId]["VALUES"]) )
                                    {
                                        $arTypeString[$valuePropId]["VALUES"][0] = array(
                                            "ID" => 0,
                                            "NAME" => "-",
                                            "SORT" => 9999,
                                            "NA" => 1
                                        );
                                    }

                                    
                                    $arTypeString[$valuePropId]["VALUES"][$valueId] = array(
                                        "ID" => $valueId,
                                        "NAME" => $valueOffer['PROPERTIES'][$valuePropId]["VALUE"],
                                        "SORT" => $valueOffer['PROPERTIES'][$valuePropId]["SORT"]
                                    );
                                    
                                }
                                else
                                {
                                    if (!isset($arNeedValues[$arSKUPropList[$valuePropId]['ID']]))
                                        $arNeedValues[$arSKUPropList[$valuePropId]['ID']] = array();

                                    $arNeedValues[$arSKUPropList[$valuePropId]['ID']][$valueId] = $valueId;
                                }


                            }
                            

                            unset($valueId);

                        }
                    }
                }

                $arSKUPropListNew = array();

                foreach ($arSKUPropIDs as $keyPropId => $valuePropId) {
                    if($arFilterProp[$valuePropId])
                        $arSKUPropListNew[$valuePropId] = $arSKUPropList[$valuePropId];
                }

                if(!empty($arSKUPropListNew))
                {
                    $arSKUPropList = $arSKUPropListNew;
                
                    CIBlockPriceTools::getTreePropertyValues($arSKUPropList, $arNeedValues);

                    $arSKUPropList = CPhoenixSku::getPropHIBlockOptions($arSKUPropList);
                    $arSKUPropList = array_merge($arSKUPropList, $arTypeString);


                    $arDouble = 
                    $arMatrix = 
                    $arMatrixFields = array();

                    foreach ($arParams["OFFERS"] as $keyOffer => $valueOffer)
                    {
                        $valueOffer['ID'] = intval($valueOffer['ID']);

                        if (isset($arDouble[$valueOffer['ID']]))
                            continue;

                        if(!empty($arSKUPropIDs))
                        {

                            foreach ($arSKUPropIDs as $keyPropId => $valuePropId)
                            {
                                $arCell = array(
                                    'VALUE' => 0,
                                    'SORT' => PHP_INT_MAX,
                                    'NA' => true,
                                    'PROP_ID' => null
                                );


                                if (isset($valueOffer['PROPERTIES'][$valuePropId]))
                                {
                                    $arCell['PROP_ID'] = $valueOffer['PROPERTIES'][$valuePropId]["ID"];

                                    if($valueOffer['PROPERTIES'][$valuePropId]['VALUE'])
                                    {
                                        $arMatrixFields[$valuePropId] = true;
                                        $arCell['NA'] = false;


                                        if ('directory' == $valueOffer['PROPERTIES'][$valuePropId]['USER_TYPE'])
                                        {
                                            $intValue = $arSKUPropList[$valuePropId]['XML_MAP'][$valueOffer['PROPERTIES'][$valuePropId]['VALUE']];
                                            $arCell['VALUE'] = $intValue;
                                        }
                                        elseif ('L' == $valueOffer['PROPERTIES'][$valuePropId]['PROPERTY_TYPE'])
                                            $arCell['VALUE'] = (int)$valueOffer['PROPERTIES'][$valuePropId]['VALUE_ENUM_ID'];
                                        
                                        elseif ('E' == $valueOffer['PROPERTIES'][$valuePropId]['PROPERTY_TYPE'])
                                            $arCell['VALUE'] = (int)$valueOffer['PROPERTIES'][$valuePropId]['VALUE'];
                                        

                                        elseif ('S' == $valueOffer['PROPERTIES'][$valuePropId]['PROPERTY_TYPE'] || 'N' == $valueOffer['PROPERTIES'][$valuePropId]['PROPERTY_TYPE'])
                                            $arCell['VALUE'] = md5($valueOffer['PROPERTIES'][$valuePropId]['VALUE']);
                                        

                                        $arCell['SORT'] = $arSKUPropList[$valuePropId]['VALUES'][$arCell['VALUE']]['SORT'];
                                        $arCell['NAME'] = $arSKUPropList[$valuePropId]['VALUES'][$arCell['VALUE']]['NAME'];
                                        $arCell['SKU_NAME'] = $valueOffer['PROPERTIES'][$valuePropId]["NAME"];
                                    }

                                }

                                $arRow[$valuePropId] = $arCell;
                            }
                            $arMatrix[$keyOffer] = $arRow;
                        }

                        $arDouble[$valueOffer['ID']] = true;
                    }


                    if(!empty($arSKUPropIDs))
                    {

                        foreach ($arSKUPropIDs as $keyPropId => $valuePropId)
                        {
                            $boolExist = $arMatrixFields[$valuePropId];

                            if(!empty($arMatrix))
                            {

                                foreach ($arMatrix as $keyMatrix => $valueMatrix)
                                {
                                    if (!$boolExist)
                                        unset($arMatrix[$keyMatrix][$valuePropId]);
                                }
                            }
                        }
                    }

                    if(!empty($arMatrix))
                    {
                        foreach ($arMatrix as $keyMatrix => $valueMatrix)
                        {
                            if(empty($arMatrix[$keyMatrix]))
                                unset($arMatrix[$keyMatrix]);
                        }
                    }
                }
            }

        }

        $arResult["SKU_PROP_LIST"] = $arSKUPropList;
        $arResult["SKU_MATRIX"] = $arMatrix;

        if(!empty($arResult["SKU_PROP_LIST"]))
        {
            foreach ($arResult["SKU_PROP_LIST"] as $key => $arProp) 
            {
                $res = CIBlockProperty::GetByID($arProp["ID"]);
                if($ar_res = $res->GetNext())
                {
                    $arResult["SKU_PROP_LIST"][$key]["HINT"] = $ar_res["HINT"];
                    $arResult["SKU_PROP_LIST"][$key]["~HINT"] = $ar_res["~HINT"];
                }
            }
        }

        return $arResult;
    }



    public static function GetUserID(){
        static $userID;

        if($userID === NULL)
        {
            global $USER;
            $userID = $USER->GetID();
            $userID = ($userID > 0 ? $userID : 0);
        }
        return $userID;
    }

    public static function ShowCabinetLink()
    {
        global $PHOENIX_TEMPLATE_ARRAY;
        static $hauth_call;
        $iCalledID = ++$hauth_call;

        $html = "";

        if($PHOENIX_TEMPLATE_ARRAY["USER_ID"])
        {
            $rsUser = CUser::GetByID($PHOENIX_TEMPLATE_ARRAY["USER_ID"]);
            $arUser = $rsUser->Fetch();

            $name = "";

            if(strlen($arUser["NAME"]))
            {
                $name = $arUser["NAME"];

                if(strlen($arUser["LAST_NAME"]))
                    $name .= "&nbsp;".$arUser["LAST_NAME"];
            }
            
            $photo = 0;


            if(!strlen($name))
                $name = $arUser["LOGIN"];

            if($arUser["PERSONAL_PHOTO"])
            {
                $photo = CFile::ResizeImageGet($arUser["PERSONAL_PHOTO"], array('width'=>64, 'height'=>64), BX_RESIZE_IMAGE_EXACT, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
            }
        }

        if($PHOENIX_TEMPLATE_ARRAY["USER_ID"])
            $a_params = "class='wr-link' href='".SITE_DIR."personal/'";
        else
            $a_params = "class='wr-link show-phx-modal-dialog' data-target='auth-modal-dialog'";

       

        $html = '<!-- noindex -->';
              
            $html .= "<div class=\"wr-cabinet\">";
                  $html .= "<table class='cabinet'";

                    if(!$PHOENIX_TEMPLATE_ARRAY["USER_ID"])
                        $html .= " title='".$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_LOGIN"]."'";

                    $html .= ">";
                    $html .= "<tr>";
                        $html .= "<td class='picture'>";

                            if(isset($photo["src"]))
                            {
                                $html .= "<img class='visible-xl visible-xxl' src='".$photo["src"]."' alt=''>";
                                $html .= "<div class='def-picture hidden-xl hidden-xxl'></div>";
                            }
                            else
                                $html .= "<div class='def-picture'></div>";

                        $html .= "</td>";


                        $html .= "<td class='name hidden-lg hidden-md hidden-sm hidden-xs'>";
                            $html .= "<div class='width-limit'>";
                                $html .= "<span>";

                                    if($PHOENIX_TEMPLATE_ARRAY["USER_ID"])
                                        $html .= $name;
                                    else
                                        $html .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL"];

                                $html .= "</span>";
                            $html .= "</div>";
                        $html .= "</td>";

                    $html .= "</tr>";
                  $html .= "</table>";
                  $html .= "<a ".$a_params."></a>";
            $html .= "</div>";
        $html .= '<!-- /noindex -->';


        //Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('header-auth-block'.$iCalledID);
            echo $html;
        //Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('header-auth-block'.$iCalledID);

    }

    public static function isStringFloat($value)
    {
        if(strpos($value, ".") !== false)
            return true;
        
        else
            return false;
    }

    public static function getNumberFromStringWithType($value)
    {
        if(self::isStringFloat($value))
            return (float)$value;
        else
            return (int)$value;
    }

    public static function getBasketUrl($siteDIR, $urlBasket)
    {
        $basket_url = "";

        if(strlen($urlBasket))
        {
            $basket_url = $urlBasket;
        }
        else
        {
            $basket_url = str_replace('/', '', $urlBasket);
            $basket_url = $siteDIR.$basket_url.'/';
        }
        

        return $basket_url;
    }

    public static function getElementsBasket($siteID)
    {

        $arBasketItems = array();

        if(Loader::includeModule("sale"))
        {
            $rsBasket = CSaleBasket::GetList( 
                array( "NAME" => "ASC", "ID" => "ASC"),
                array("FUSER_ID" => CSaleBasket::GetBasketUserID(),  "LID" => $siteID, "ORDER_ID" => NULL ),
                    false, false, array("ID", "PRODUCT_ID", "DELAY", "SUBSCRIBE", "CAN_BUY", "QUANTITY", "SET_PARENT_ID") );

            while( $arBasket = $rsBasket->GetNext() )
            {
                if(strlen($arBasket["SET_PARENT_ID"])<=0)
                    $arBasketItems[] = $arBasket;
            }
        }

        return $arBasketItems;
    }

    public static function getElementsBasketHTML($iblockID, $siteID)
    {

        $arItems = array();
        
        if($iblockID)
        {
            $arBasketItems = CPhoenix::getElementsBasket($siteID);

            global $compare_items;
            

            if(!is_array($compare_items))
            {
                $iblockID = $_POST['iblockID'];
                $compare_items = array();

                if($iblockID && isset($_SESSION["CATALOG_COMPARE_LIST"][$iblockID]["ITEMS"]))
                    $compare_items = array_keys($_SESSION["CATALOG_COMPARE_LIST"][$iblockID]["ITEMS"]);
            }

            if($arBasketItems)
            {
                $basket_items = $delay_items = array();

                foreach($arBasketItems as $arBasketItem)
                {

                    if($arBasketItem["DELAY"]=="N")
                        $basket_items[] = $arBasketItem["PRODUCT_ID"];

                    elseif($arBasketItem["DELAY"]=="Y")
                        $delay_items[] = $arBasketItem["PRODUCT_ID"];
                }
            }

            if(!empty($basket_items))
                $arItems["BASKET"] = array_combine($basket_items, $basket_items);
            
            if(!empty($delay_items))
                $arItems["DELAY"] = array_combine($delay_items, $delay_items);
            
            if(!empty($compare_items))
                $arItems["COMPARE"] = array_combine($compare_items, $compare_items);

            $arItems["BASKET_COUNT"] = (isset($arItems["BASKET"]))?count($arItems["BASKET"]):0;
            $arItems["DELAY_COUNT"] = (isset($arItems["DELAY"]))?count($arItems["DELAY"]):0;
            $arItems["COMPARE_COUNT"] = (isset($arItems["COMPARE"]))?count($arItems["COMPARE"]):0;

            unset($iblockID, $delay_items, $basket_items, $compare_items);
        }

        return $arItems;
    }



    public static function setHTMLFontHead($style_name = "", $font_size = "", $line_height = "", $font_size_mob = "", $line_height_mob = "")
    {
        $font_style="";
        $font_style_mob="";

        if(strlen($font_size))
        {
            $font_style = "@media (min-width: 768px){";

                if(strlen($font_size))
                {
                    $font_style .= $style_name;
                    $font_style .= 
                        "{
                            font-size:".$font_size."px !important;
                        ";

                            $line_height_tit = intval($font_size) + 5;

                            if(strlen($line_height))
                                $line_height_tit = $line_height;

                            $font_style .= "line-height:".$line_height_tit."px !important";
                        
                    $font_style .= "}";
                }

            $font_style .= "}";
        }
        
        if(strlen($font_size_mob))
        {
            $font_style_mob = "@media (max-width: 767px){";

                if(strlen($font_size_mob))
                {
                    $font_style_mob .= $style_name;
                    $font_style_mob .= 
                        "{
                            font-size:".$font_size_mob."px !important;
                        ";

                            $line_height_tit = intval($font_size_mob) + 5;

                            if(strlen($line_height_mob))
                                $line_height_tit = $line_height_mob;

                            $font_style_mob .= "line-height:".$line_height_tit."px !important";
                        
                    $font_style_mob .= "}";
                }

            $font_style_mob .= "}";
        }
        

        echo $font_style.$font_style_mob;
    }

    public static function shares( $arShares, $view = "view-1" )
    {
        $html = "";

        if( !empty($arShares) )
        {
            $list = "";

            if($view == "view-1")
            {
                foreach ($arShares as $keyShare => $valueShare)
                {
                    $valueShare["TITLE"] = str_replace("'", "`", $valueShare["TITLE"]);

                    if($keyShare == "VK")
                    {
                        $list .= "<a onclick='Share.vkontakte(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\", \"".$valueShare["IMG"]."\", \"".$valueShare["DESCRIPTION"]."\")' class='soc_vk'><i class='concept-vkontakte'></i></a>";
                    }

                    if($keyShare == "FB")
                    {
                        $list .= "<a onclick='Share.facebook(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\", \"".$valueShare["IMG"]."\", \"".$valueShare["DESCRIPTION"]."\")' class='soc_fb'><i class='concept-facebook-1'></i></a>";
                    }

                    if($keyShare == "TW")
                    {
                        $list .= "<a onclick='Share.twitter(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\")' class='soc_tw'><i class='concept-twitter-bird-1'></i></a>";
                    }

                    if($keyShare == "OK")
                    {
                        $list .= "<a onclick='Share.ok(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\")' class='soc_ok'><i class='concept-odnoklassniki-1'></i></a>";
                    }

                    if($keyShare == "MAILRU")
                    {
                        $list .= "<a onclick='Share.mailRu(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\")' class='soc_mailRu'><i class='concept-at'></i></a>";
                    }

                    if($keyShare == "WTSAPP")
                    {
                        $list .= "<a onclick='Share.wtsApp(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\")' class='soc_wtsApp'><i class='concept-whatsapp'></i></a>";
                    }

                    if($keyShare == "TELEGRAM")
                    {
                        $list .= "<a onclick='Share.telegram(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\")' class='soc_telegram'><i class='concept-paper-plane'></i></a>";
                    }

                    if($keyShare == "SKYPE")
                    {
                        $list .= "<a onclick='Share.skype(\"".$valueShare["URL"]."\")' class='soc_skype'><i class='concept-skype-outline'></i></a>";
                    }

                    /*if($keyShare == "GPLUS")
                    {
                        $list .= "<a onclick='Share.gPlus(\"".$valueShare["URL"]."\")' class='soc_gPlus'><i class='concept-gplus-2'></i></a>";
                    }*/

                    if($keyShare == "REDDIT")
                    {
                        $list .= "<a onclick='Share.reddit(\"".$valueShare["URL"]."\")' class='soc_reddit'><i class='concept-reddit-1'></i></a>";
                    }


                }

                $html = "<div class='shares'>".$list."</div>";

            }

            if($view == "view-2")
            {
                foreach ($arShares as $keyShare => $valueShare)
                {
                    if($keyShare == "VK")
                    {
                        $list .= "<li><a onclick='Share.vkontakte(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\", \"".$valueShare["IMG"]."\", \"".$valueShare["DESCRIPTION"]."\")' class='soc_vk'><i class='concept-vkontakte'></i></a></li>";
                    }

                    if($keyShare == "FB")
                    {
                        $list .= "<li><a onclick='Share.facebook(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\", \"".$valueShare["IMG"]."\", \"".$valueShare["DESCRIPTION"]."\")' class='soc_fb'><i class='concept-facebook-1'></i></a></li>";
                    }
                    if($keyShare == "TW")
                    {
                        $list .= "<li><a onclick='Share.twitter(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\")' class='soc_tw'><i class='concept-twitter-bird-1'></i></a></li>";
                    }

                    if($keyShare == "OK")
                    {
                        $list .= "<li><a onclick='Share.ok(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\")' class='soc_ok'><i class='concept-odnoklassniki-1'></i></a></li>";
                    }

                    if($keyShare == "MAILRU")
                    {
                        $list .= "<li><a onclick='Share.mailRu(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\")' class='soc_mailRu'><i class='concept-at'></i></a></li>";
                    }

                    if($keyShare == "WTSAPP")
                    {
                        $list .= "<li><a onclick='Share.wtsApp(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\")' class='soc_wtsApp'><i class='concept-whatsapp'></i></a></li>";
                    }

                    if($keyShare == "TELEGRAM")
                    {
                        $list .= "<li><a onclick='Share.telegram(\"".$valueShare["URL"]."\", \"".$valueShare["TITLE"]."\")' class='soc_telegram'><i class='concept-paper-plane'></i></a></li>";
                    }

                    if($keyShare == "SKYPE")
                    {
                        $list .= "<li><a onclick='Share.skype(\"".$valueShare["URL"]."\")' class='soc_skype'><i class='concept-skype-outline'></i></a></li>";
                    }
                    /*if($keyShare == "GPLUS")
                    {
                        $list .= "<li><a onclick='Share.gPlus(\"".$valueShare["URL"]."\")' class='soc_gPlus'><i class='concept-gplus-2'></i></a></li>";
                    }*/

                    if($keyShare == "REDDIT")
                    {
                        $list .= "<li><a onclick='Share.reddit(\"".$valueShare["URL"]."\")' class='soc_reddit'><i class='concept-reddit-1'></i></a></li>";
                    }
                }

                $html = "<ul class='shares'>".$list."</ul>";

            }
        }

        return $html;
    }


    public static function getAnalitycCodes($siteId)
    {
        global $PHOENIX_TEMPLATE_ARRAY;

        CPhoenix::phoenixOptionsValues($siteId, array("services"));

        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['ID_METRIKA_YM'] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['ID_METRIKA'] = array();
        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['ID_GOOGLE'] = "";
        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['ID_GTM'] = "";

        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['METRIKA']["VALUE"])>0)
        {
            $input_line = "";
            $pattern = "";
            $search = "";
            $replace = "";

            $input_line = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['METRIKA']["VALUE"]; 
            $pattern = "/id:[0-9]*,/"; 

            if(preg_match_all($pattern, $input_line, $output_array))
            {
                $search = array("id:",",");
                $replace   = array("", "");
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['ID_METRIKA'] = str_replace($search, $replace, $output_array[0]);
            }

            $patternFlagYM = "/ym\([0-9]*,/";


            if(preg_match_all($patternFlagYM, $input_line, $out))
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['ID_METRIKA_YM'] = str_replace(array("ym(",","), array("",""), $out[0]);
        }

        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE']["VALUE"])>0)
        {
            $patternFlagGa = "/ga\(/";
            $patternFlagGtag = "/gtag\(/"; 

            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_FLAG_GA'] = preg_match($patternFlagGa, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE']["VALUE"]);
            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_FLAG_GTAG'] = preg_match($patternFlagGtag, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE']["VALUE"]);


            $input_line = "";
            $pattern = "";
            $search = "";
            $replace = "";


            $input_line = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE']["VALUE"]; 
            $pattern = "/'UA-.*'/";

            preg_match($pattern, $input_line, $output_array);


            $search = array("'");
            $replace   = array("");
            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['ID_GOOGLE'] = str_replace($search, $replace, $output_array[0]);
        }

        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_HEAD']["VALUE"])>0)
        {
            $input_line = "";
            $pattern = "";
            $search = "";
            $replace = "";


            $input_line = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_HEAD']["VALUE"]; 
            $pattern = "/'GTM-.*'/";

            preg_match($pattern, $input_line, $output_array);

            $search = array("'");
            $replace   = array("");
            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['ID_GTM'] = str_replace($search, $replace, $output_array[0]);
        }

    }

    public static function getGoalsScriptsHTML($siteId, $arParams = array())
    {

        $res = "";

        if(!empty($arParams))
        {

            global $PHOENIX_TEMPLATE_ARRAY;

            CPhoenix::getAnalitycCodes($siteId);

            if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_METRIKA"]) && strlen($arParams["YAGOAL"]) > 0)
            {
                foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_METRIKA"] as $idVal)
                {
                    $res .= 'yaCounter'.htmlspecialcharsbx(trim($idVal)).'.reachGoal("'.htmlspecialcharsbx(trim($arParams["YAGOAL"])).'"); ';
                }
            }

            if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_METRIKA_YM"]) && strlen($arParams["YAGOAL"]) > 0)
            {

                foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_METRIKA_YM"] as $idVal)
                {
                    $res .= 'ym('.htmlspecialcharsbx(trim($idVal)).', "reachGoal", "'.htmlspecialcharsbx(trim($arParams["YAGOAL"])).'");';
                }
            }

            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_GOOGLE"]) > 0 && strlen($arParams["GA_CAT"]) > 0 && strlen($arParams["GA_ACT"]) > 0)
            {
                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_FLAG_GA'])
                    $res .= 'ga("send", "event", "'.htmlspecialcharsbx(trim($arParams["GA_CAT"])).'", "'.htmlspecialcharsbx(trim($arParams["GA_ACT"])).'"); ';

                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_FLAG_GTAG'])
                    $res .= 'gtag("event","'.htmlspecialcharsbx(trim($arParams["GA_ACT"])).'",{"event_category":"'.htmlspecialcharsbx(trim($arParams["GA_CAT"])).'"}); ';
            }

            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_GTM"]) > 0 && strlen($arParams["GTM_EVT"]) > 0)
            {
                $res .= 'dataLayer.push({"event": "'.htmlspecialcharsbx(trim($arParams["GTM_EVT"])).'", "eventCategory": "'.htmlspecialcharsbx(trim($arParams["GTM_CAT"])).'", "eventAction": "'.htmlspecialcharsbx(trim($arParams["GTM_ACT"])).'"});';
            }

            if(strlen($res)>0)
                $res = "<script>".$res."</script>";

        }

        return $res;
    }

    public static function phoenixOptionsValues($siteId, $arParams = array(), $admin = "N", $arParamsOther = array())
    {

        $initOptions = false;

        if(strlen($siteId)>0)
        {
            $rsSites = CSite::GetByID($siteId);

            if($arSite = $rsSites->Fetch())
            {

                $rsTemplates = CSite::GetTemplateList($arSite["LID"]);

                while($arTemplate = $rsTemplates->Fetch())
                { 
                    if(substr_count($arTemplate["TEMPLATE"], "concept_phoenix_".$arSite["LID"]) > 0)
                        $res = true;
                }
                
                if($res == true)
                    $initOptions = true;

            }
        }

        
        if(!$initOptions)
        {
            echo json_encode(array('OK'=>'N'));
            die();
        }

        global $PHOENIX_TEMPLATE_ARRAY, $APPLICATION, $USER;
        $moduleID = "concept.phoenix";
        $PHOENIX_TEMPLATE_ARRAY["SITE_ID"] = $siteId;

       


        $colLG = 80;
        $colMD = 40;
        $colSM = 15;


        $colVisFull = "100%";

        $colVisLG = 300;
        $colVisMD = 200;
        $colVisSM = 150;

        $rowLG = 20;
        $rowMD = 10;
        $rowSM = 5;
        $rowONE = 1;

        CPhoenix::setMainMess();

        if(!empty($arParams))
        {
            $PHOENIX_TEMPLATE_ARRAY["SERVER_NAME"] = str_replace(array("http://","https://"), array("", ""), $arSite["SERVER_NAME"]);

            $PHOENIX_TEMPLATE_ARRAY["ERRORS"] = array();

            foreach ($arParams as $keyParams => $valueParams) {

                if($valueParams == "start")
                {

                    self::getIblockIDs(array(
                        "SITE_ID"=>$siteId, 
                        "CODES" => array(
                            "concept_phoenix_site_".$siteId, 
                            "concept_phoenix_catalog_".$siteId,
                            "concept_phoenix_catalog_offers_".$siteId,
                            "concept_phoenix_footer_menu_".$siteId,
                            "concept_phoenix_menu_".$siteId,
                            "concept_phoenix_brand_".$siteId,
                            "concept_phoenix_banners_".$siteId,
                            "concept_phoenix_blog_".$siteId,
                            "concept_phoenix_news_".$siteId,
                            "concept_phoenix_action_".$siteId,
                            "concept_phoenix_advantages_".$siteId,
                            "concept_phoenix_faq_".$siteId,
                            "concept_phoenix_requests_".$siteId,
                            "concept_phoenix_modals_".$siteId,
                            "concept_phoenix_forms_".$siteId,
                            "concept_phoenix_agreements_".$siteId,
                            "concept_phoenix_regions_".$siteId,
                            )
                        )
                    );



                    global $PHOENIX_MENU;
                    CPhoenix::setCounterPhoenix();

                    $PHOENIX_MENU = 0;

                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['MENU']["IBLOCK_ID"]>0)
                    {

                        $arFilter = Array('IBLOCK_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['MENU']["IBLOCK_ID"], "ACTIVE"=>"Y");
                        $resSect = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, array("ID"), array("nTopCount" => "1"));

                        while($sectRes = $resSect->GetNext())
                        {
                            if($PHOENIX_MENU > 0)
                                break;

                            $PHOENIX_MENU++;
                        }
                    }


                    $PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"] = array();

                    $PHOENIX_TEMPLATE_ARRAY["GROUP_POLICY_2"] = CUser::GetGroupPolicy([2]);

                    $PHOENIX_TEMPLATE_ARRAY["SITE_URL"] = CPhoenixHost::getHost($_SERVER).$arSite["DIR"];
                
                    $userID = $USER->GetID();
                    $userID = ($userID > 0 ? $userID : 0);

                    $PHOENIX_TEMPLATE_ARRAY["USER_ID"] = $userID;
                    $PHOENIX_TEMPLATE_ARRAY["USER_GROUPS"] = CUser::GetUserGroup($PHOENIX_TEMPLATE_ARRAY["USER_ID"]);

                    unset($userID);

                    global $HTTP_USER_AGENT; 
                    $PHOENIX_TEMPLATE_ARRAY["OS"] = false;
                    
                    if (strstr($HTTP_USER_AGENT,"Mac")) 
                        $PHOENIX_TEMPLATE_ARRAY["OS"]=true; 



                    $PHOENIX_TEMPLATE_ARRAY["PAGES"] = array(
                        "catalog" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
                        "brand" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BRAND']["IBLOCK_ID"],
                        "blog" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BLOG']["IBLOCK_ID"],
                        "news" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['NEWS']["IBLOCK_ID"],
                        "actions" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ACTIONS']["IBLOCK_ID"],
                    );

                    
                    
                    $phoenix_rights = $APPLICATION->GetGroupRight($moduleID);

                    $PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"] = $USER->isAdmin() || $phoenix_rights > "R";




                    $PHOENIX_TEMPLATE_ARRAY["SITE_CONTENT_JS"] = "";
                    $PHOENIX_TEMPLATE_ARRAY["SITE_CONTENT_CSS"] = "";



                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["NAME"][0] = GetMessage("PHOENIX_EMPTY_VAL");
                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["VALUES"][0] = "N"; 


                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["IBLOCK_ID"]>0)
                    {

                        $arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
                        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, array("ID","NAME","IBLOCK_ID","IBLOCK_TYPE_ID"));


                        while($ob = $res->GetNextElement()){ 
                            $arFields = $ob->GetFields();  

                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]['NAME'][] = $arFields['NAME'];
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]['VALUES'][] = $arFields['ID'];

                            if(!isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["IBLOCK_ID"]))
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["IBLOCK_ID"] = $arFields['IBLOCK_ID'];

                            if(!isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["IBLOCK_TYPE_ID"]))
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["IBLOCK_TYPE_ID"] = $arFields['IBLOCK_TYPE_ID'];
                        }
                    }


                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK'] = array(
                        "NAME" => $name_val = "phoenix_id_callback",
                        "DESCRIPTIONS" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["NAME"],
                        "VALUES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["VALUES"],
                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_OTHER_FORM_CALLBACK_TITLE"),
                        "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                        "DEFAULT" =>  "N",
                        "TYPE" => "select"
                    );


                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['BETTER_PRICE'] = array(
                        "NAME" => $name_val = "phoenix_form_better_price",
                        "DESCRIPTIONS" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["NAME"],
                        "VALUES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["VALUES"],
                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FORM_BETTER_PRICE_TITLE"),
                        "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                        "DEFAULT" =>  "N",
                        "TYPE" => "select"
                    );


                }

                elseif($valueParams == "comments" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["NAME"]))
                { 
                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"] = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_COMMENTS_NAME"),

                        "ITEMS" => array(

                            "FORM_EMAIL_ACTIVE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_COMMENTS_FORM_EMAIL_ACTIVE"),
                                "NAME" => $name_val = "phoenix_comments_form_email_active",
                                "VALUES" =>  array(

                                    "ACTIVE"=>array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_COMMENTS_FORM_EMAIL_ACTIVE"),
                                        "VALUE" => "Y"
                                    ),

                                ),
                                "VALUE" => array(
                                    "ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId),
                                ),
                                "TYPE" => "checkbox",
                            ),

                            "EMAIL_TO" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_COMMENTS_EMAIL_TO"),
                                "HINT" => Loc::GetMessage("PHOENIX_SET_COMMENTS_EMAIL_TO_HINT"),
                                "NAME" => $name_val = "phoenix_comments_email_to",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text"
                            ),

                            "ADMIN_EMAIL_NOTIFY" => array(
                                "DESCRIPTION"=>GetMessage("PHOENIX_SET_COMMENTS_ADMIN_EMAIL_NOTIFY"),
                                "NAME" => $name_val = "phoenix_comments_admin_email_notify",
                                "VALUES"=> array(
                                    "never" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_SET_COMMENTS_ADMIN_EMAIL_NOTIFY_NEVER"),
                                        "VALUE" => "never",
                                    ),
                                    "ever" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_SET_COMMENTS_ADMIN_EMAIL_NOTIFY_EVER"),
                                        "VALUE" => "ever",
                                    ),
                                    /*"moderation" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_SET_COMMENTS_ADMIN_EMAIL_NOTIFY_MODETATION"),
                                        "VALUE" => "moderation",
                                    ),*/
                                ),
                                "VALUE"=> Option::get("concept.phoenix", $name_val, false, $siteId),
                                "TYPE" => "radio",
                                "DEFAULT"=> "never"

                            ),


                            "USERS_ACCESS" => array(
                                "DESCRIPTION"=>GetMessage("PHOENIX_SET_COMMENTS_USERS_ACCESS"),
                                "NAME" => $name_val = "phoenix_comments_users_access",
                                "VALUES"=> array(
                                    "all" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_SET_COMMENTS_USERS_ACCESS_ALL"),
                                        "VALUE" => "all",
                                    ),
                                    "auth" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_SET_COMMENTS_USERS_ACCESS_AUTH"),
                                        "VALUE" => "auth",
                                    ),
                                ),
                                "VALUE"=> Option::get("concept.phoenix", $name_val, false, $siteId),
                                "TYPE" => "radio",
                                "DEFAULT"=> "all"

                            ),


                            "PAGES" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_COMMENTS_PAGES"),
                                "NAME" => $name_val = "phoenix_comments_pages",
                                "VALUES" =>  array(

                                    "NEWS"=>array(
                                        "NAME" => $name_val."_news",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_COMMENTS_PAGES_NEWS"),
                                        "VALUE" => "Y"
                                    ),
                                    "BLOG"=>array(
                                        "NAME" => $name_val."_blog",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_COMMENTS_PAGES_BLOG"),
                                        "VALUE" => "Y"
                                    ),
                                    "ACTIONS"=>array(
                                        "NAME" => $name_val."_actions",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_COMMENTS_PAGES_ACTIONS"),
                                        "VALUE" => "Y"
                                    ),

                                ),
                                "VALUE" => array(
                                    "NEWS"=>Option::get($moduleID, $name_val."_news", false, $siteId),
                                    "BLOG"=>Option::get($moduleID, $name_val."_blog", false, $siteId),
                                    "ACTIONS"=>Option::get($moduleID, $name_val."_actions", false, $siteId),
                                ),
                                "TYPE" => "checkbox",
                            ),

                            "MODERATOR" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_COMMENTS_MODERATOR"),
                                "NAME" => $name_val = "phoenix_comments_moderator",
                                "VALUES" =>  array(

                                    "ACTIVE"=>array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_COMMENTS_MODERATOR"),
                                        "VALUE" => "Y"
                                    ),

                                ),
                                "VALUE" => array(
                                    "ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId),
                                ),
                                "TYPE" => "checkbox",
                            ),
                        ),
                    );
                }

                elseif($valueParams == "design" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["NAME"]))
                {   

                    $phonesBack = array();
                    $phones = Option::get($moduleID, "phoenix_phones", false, $siteId);
                    if(strlen($phones))
                        $phones = unserialize(base64_decode($phones));
                    else
                        $phones = array();

                   


                    if(!empty($phones))
                    {
                        foreach($phones as $k => $val)
                        {
                            $phones[$k]['name']=str_replace(array("'"), array("&#39;"), $val['name']);
                            $phones[$k]['desc']=$val['desc'];
                        }

                        foreach($phones as $k => $val)
                        {
                            $phonesBack[$k]['name']=htmlspecialcharsBack($val['name']);
                            $phonesBack[$k]['desc']=htmlspecialcharsBack($val['desc']);
                        }
                    }

                    $emails_addr = Option::get($moduleID, "phoenix_emails_addr", false, $siteId);
                    if(strlen($emails_addr))
                        $emails_addr = unserialize(base64_decode($emails_addr));
                    else
                        $emails_addr = array();


                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"] = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_DISIGN_NAME"),

                        "ITEMS" => array(

                            "HEADER_MAIN" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_HEADER_MAIN"),
                                "HINT"=>Loc::GetMessage("PHOENIX_SET_HEADER_MAIN_HINT"),
                                "NAME" => $name_val = "phoenix_header_main",
                                "VALUE" => $opt = trim(Option::get("concept.phoenix", $name_val, false, $siteId)),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "HEADER_SCROLL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_HEADER_SCROLL"),
                                "HINT"=>Loc::GetMessage("PHOENIX_SET_HEADER_SCROLL_HINT"),
                                "NAME" => $name_val = "phoenix_header_scroll",
                                "VALUE" => $opt = trim(Option::get("concept.phoenix", $name_val, false, $siteId)),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "HEADER_MOBILE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_HEADER_MOBILE"),
                                "HINT"=>Loc::GetMessage("PHOENIX_SET_HEADER_MOBILE_HINT"),
                                "NAME" => $name_val = "phoenix_header_mobile",
                                "VALUE" => $opt = trim(Option::get("concept.phoenix", $name_val, false, $siteId)),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "BODY_BG_CLR" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_BODY_BG_CLR"),
                                "NAME" => $name_val = "phoenix_body_bg_clr",
                                "VALUE" => $opt = Option::get("concept.phoenix", $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "DEFAULT" => "transparent",
                                "HINT" => Loc::GetMessage("PHOENIX_SET_BODY_BG_CLR_HINT"),
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "BODY_BG_POS" => array(
                                "DESCRIPTION"=>GetMessage("PHOENIX_SET_BODY_BG_POS"),
                                "NAME" => $name_val = "phoenix_body_bg_pos",
                                "VALUES"=> array(
                                    "scroll" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_SET_BODY_BG_POS_SCROLL"),
                                        "VALUE" => "scroll",
                                    ),
                                    "fixed" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_SET_BODY_BG_POS_FIXED"),
                                        "VALUE" => "fixed",
                                    ),
                                ),
                                "VALUE"=> Option::get("concept.phoenix", $name_val, false, $siteId),
                                "TYPE" => "radio",
                                "DEFAULT"=> "scroll"

                            ),

                            "BODY_BG_REPEAT" => array(
                                "DESCRIPTION"=>GetMessage("PHOENIX_SET_BODY_BG_REPEAT"),
                                "NAME" => $name_val = "phoenix_body_bg_repeat",
                                "VALUES"=> array(
                                    "no-repeat" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_SET_BODY_BG_REPEAT_NO"),
                                        "VALUE" => "no-repeat",
                                    ),
                                    "repeat" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_SET_BODY_BG_REPEAT_XY"),
                                        "VALUE" => "repeat",
                                    ),
                                    "repeat-y" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_SET_BODY_BG_REPEAT_Y"),
                                        "VALUE" => "repeat-y",
                                    ),
                                    "repeat-x" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_SET_BODY_BG_REPEAT_X"),
                                        "VALUE" => "repeat-x",
                                    ),
                                ),
                                "VALUE"=> Option::get("concept.phoenix", $name_val, false, $siteId),
                                "TYPE" => "radio",
                                "DEFAULT"=> "no-repeat"

                            ),

                            "BODY_BG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_BODY_BG"),
                                "NAME" => $name_val = "img_phoenix_body_bg",
                                "VALUE" => $opt = Option::get("concept.phoenix", $name_val, false, $siteId),
                                "SRC" => "",
                                "TYPE" => "image"
                            ),

                            "HEAD_LOGO" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_LOGO"),
                                "NAME" => $name_val = "img_phoenix_logotype",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "LOGO_MOB" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_LOGO_MOB"),
                                "NAME" => $name_val = "img_phoenix_logo_mob",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "LOGO_MOB_LIGHT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_LOGO_MOB_LIGHT"),
                                "NAME" => $name_val = "img_phoenix_logo_mob_light",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "FAVICON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FAVICON"),
                                "NAME" => $name_val = "img_phoenix_favicon",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "SRC" => "",
                                "TYPE" => "image"

                            ),

                            "LOGO_LIGHT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_LOGO_LIGHT"),
                                "NAME" => $name_val = "img_phoenix_logo_light",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "LOGO_EXTRASIZE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_LOGO_EXTRASIZE"),
                                "NAME" => $name_val = "phoenix_head_logo_extrasize",
                                "VALUES" =>  array(

                                    "ACTIVE"=>array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_LOGO_EXTRASIZE"),
                                        "VALUE" => "cover"
                                    )
                                ),
                                "VALUE" => array(
                                    "ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)
                                ),
                                "TYPE" => "checkbox",
                            ),

                            "PICTURES_QUALITY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PICTURES_QUALITY"),
                                "NAME" => $name_val = "phoenix_pic_quality",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_PICTURES_QUALITY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "FONTS" => array(

                                "HEAD_TITLE" => Loc::GetMessage("PHOENIX_FONTS_MAIN_TITLE"),

                                "TITLE" => array(

                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_FONT_TITLE"),

                                    "NAME" => $name_val = "phoenix_font_title",

                                    "DESCRIPTIONS" => array(
                                        Loc::GetMessage("PHOENIX_FONT_TITLE_1"),
                                        Loc::GetMessage("PHOENIX_FONT_TITLE_2"),
                                        Loc::GetMessage("PHOENIX_FONT_TITLE_3"),
                                        Loc::GetMessage("PHOENIX_FONT_TITLE_4"),
                                        loc::GetMessage("PHOENIX_FONT_TITLE_5"),
                                        Loc::GetMessage("PHOENIX_FONT_TITLE_6"),
                                        Loc::GetMessage("PHOENIX_FONT_TITLE_7"),
                                        Loc::GetMessage("PHOENIX_FONT_TITLE_8"),
                                        Loc::GetMessage("PHOENIX_FONT_TITLE_9"),
                                    ),

                                    "VALUES" => array(
                                        "lato",
                                        "arial",
                                        "exo2",
                                        "roboto",
                                        "elmessiri",
                                        "helvetica",
                                        "ptserif",
                                        "yanonekaffeesatz",
                                        "segoeUI",
                                    ),

                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "TYPE" => "select"
                                    
                                ),

                                "TEXT" => array(

                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_FONT_TEXT"),

                                    "NAME" => $name_val = "phoenix_font_text",                              

                                    "DESCRIPTIONS" => array(
                                        Loc::GetMessage("PHOENIX_FONT_TEXT_1"),
                                        Loc::GetMessage("PHOENIX_FONT_TEXT_2"),
                                        Loc::GetMessage("PHOENIX_FONT_TEXT_3"),
                                        Loc::GetMessage("PHOENIX_FONT_TEXT_4"),
                                        Loc::GetMessage("PHOENIX_FONT_TEXT_5"),
                                        Loc::GetMessage("PHOENIX_FONT_TEXT_6"),
                                        Loc::GetMessage("PHOENIX_FONT_TEXT_7"),
                                        Loc::GetMessage("PHOENIX_FONT_TEXT_8"),
                                        Loc::GetMessage("PHOENIX_FONT_TEXT_9"),
                                        Loc::GetMessage("PHOENIX_FONT_TEXT_10"),

                                    ),

                                    "VALUES" => array(
                                        "lato",
                                        "arial",
                                        "roboto",
                                        "arimo",
                                        "elmessiri",
                                        "exo2",
                                        "firasans",
                                        "helvetica",
                                        "opensans",
                                        "segoeUI",
                                    ),

                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "TYPE" => "select"

                                ),

                                

                            ),

                            "MAIN_COLOR_STD" => array(

                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MAIN_COLOR_STD"),

                                "NAME" => $name_val = "phoenix_color_std",

                                "DESCRIPTIONS" => array(
                                    Loc::GetMessage("PHOENIX_COLOR_1"),
                                    Loc::GetMessage("PHOENIX_COLOR_2"),
                                    Loc::GetMessage("PHOENIX_COLOR_3"),
                                    Loc::GetMessage("PHOENIX_COLOR_4"),
                                    loc::GetMessage("PHOENIX_COLOR_5"),
                                    Loc::GetMessage("PHOENIX_COLOR_6"),
                                    Loc::GetMessage("PHOENIX_COLOR_7"),
                                    Loc::GetMessage("PHOENIX_COLOR_8"),
                                    Loc::GetMessage("PHOENIX_COLOR_9"),
                                    Loc::GetMessage("PHOENIX_COLOR_10"),
                                    Loc::GetMessage("PHOENIX_COLOR_11"),
                                    Loc::GetMessage("PHOENIX_COLOR_12"),
                                    Loc::GetMessage("PHOENIX_COLOR_13"),
                                    Loc::GetMessage("PHOENIX_COLOR_14"),
                                ),

                                "VALUES" => array(
                                    "#2285c4",
                                    "#e59a05",
                                    "#e5420b",
                                    "#66b132",
                                    "#358a69",
                                    "#da0b76",
                                    "#ff00ae",
                                    "#193cec",
                                    "#936200",
                                    "#8d0909",
                                    "#3d860b",
                                    "#08d585",
                                    "#494949",
                                    "#b71cea",
                                ),

                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "#2285c4",
                                "TYPE" => "select"

                            ),

                            "MAIN_COLOR" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MAIN_COLOR"),
                                "NAME" => $name_val = "phoenix_main_color",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_MAIN_COLOR_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "color_picker"
                                )
                            ),

                            "FONT_COLOR" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FONT_COLOR"),
                                "NAME" => $name_val = "phoenix_font_color",
                                "VALUES" => array(

                                    "DARK" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FONT_COLOR_DARK"),
                                        "VALUE" => "dark"
                                    ),

                                    "LIGHT" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FONT_COLOR_LIGHT"),
                                        "VALUE" => "light"

                                    ),

                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "light",
                                "TYPE" => "radio",
                            ),

                            "HEAD_TONE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_TONE"),
                                "NAME" => $name_val = "phoenix_head_tone",
                                "VALUES" => array(

                                    "DARK" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_TONE_DARK"),
                                        "VALUE" => "dark"
                                    ),

                                    "LIGHT" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_TONE_LIGHT"),
                                        "VALUE" => "light"
                                    )

                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "dark",
                                "TYPE" => "radio",

                            ),

                            "COLOR_HEADER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_COLOR_HEADER_NAME"),
                                "NAME" => $name_val = "phoenix_color_header",
                                "VALUES" =>  array(
                                    "DEF" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_COLOR_HEADER_DEF"),
                                        "VALUE" => "def"
                                    ),
                                    "DARK" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_COLOR_HEADER_DARK"),
                                        "VALUE" => "dark"
                                    ),
                                    "LIGHT" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_COLOR_HEADER_LIGHT"),
                                        "VALUE" => "light"
                                    )
                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "def",
                                "TYPE" => "radio",
                            ),

                            "BTN_VIEW" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BTN_VIEW"),
                                "NAME" => $name_val = "phoenix_btn_view",
                                "VALUES" => array(

                                    "NORMAL" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_BTN_VIEW_NORMAL"),
                                        "VALUE" => "normal"
                                    ),

                                    "ROUNF-SQ" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FONT_ROUND_SQ"),
                                        "VALUE" => "round-sq"
                                    ),

                                    "ELIPS" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_BTN_VIEW_ELIPS"),
                                        "VALUE" => "elips"
                                    ),

                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "elips",
                                "TYPE" => "radio",
                            ),

                            "HEAD_BG_COLOR" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_BG_COLOR"),
                                "NAME" => $name_val = "phoenix_head_bg_color",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_HEAD_BG_COLOR_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "color_picker"
                                )
                            ),

                            "HEAD_BG_OPACITY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_BG_OPACITY"),
                                "NAME" => $name_val = "phoenix_head_bg_opacity",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "HINT" => Loc::GetMessage("PHOENIX_HEAD_BG_OPACITY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text"
                            ),

                            "HEAD_BG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_BG"),
                                "NAME" => $name_val = "img_phoenix_head_bg",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "HEAD_BG_COVER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_BG_COVER"),
                                "NAME" => $name_val = "phoenix_head_bg_cover",
                                "VALUES" =>  array(

                                    "ACTIVE"=>array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_BG_COVER"),
                                        "VALUE" => "cover"
                                    )
                                ),
                                "VALUE" => array(
                                    "ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)
                                ),
                                "TYPE" => "checkbox",
                            ),

                            "HEAD_FIXED" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_FIXED"),
                                "NAME" => $name_val = "phoenix_head_fixed",
                                "VALUES" =>  array(

                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_FIXED"),
                                        "VALUE" => "fixed"
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_HEAD_FIXED_HINT"),
                                "TYPE" => "checkbox",
                            ),

                            "HEAD_VIEW" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_VIEW"),
                                "NAME" => $name_val = "phoenix_head_view",
                                "VALUES" =>  array(

                                    "ONE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_VIEW_ONE"),
                                        "VALUE" => "one"
                                    ),
                                    "TWO" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_VIEW_TWO"),
                                        "VALUE" => "two"
                                    )
                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "one",
                                "TYPE" => "radio",
                            ),

                            

                            "HEAD_BG_XS_FOR_PAGES_MODE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_BG_XS_FOR_PAGES_MODE"),
                                "NAME" => $name_val = "phoenix_headBgXs4pagesMode",
                                "VALUES" =>  array(

                                    "DEFAULT" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_BG_XS_FOR_PAGES_MODE_DEF"),
                                        "VALUE" => "default",
                                        "SHOW_OPTIONS" => " ",
                                    ),
                                    "CUSTOM" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_BG_XS_FOR_PAGES_MODE_CUSTOM"),
                                        "VALUE" => "custom",
                                        "SHOW_OPTIONS" => "headBgXsCustom",
                                    )
                                ),
                                "SHOW_OPTIONS" => "headBgXsCustom",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "default",
                                "TYPE" => "radio",
                            ),

                            "HEAD_BG_XS_FOR_PAGES" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_BG_XS_FOR_PAGES"),
                                "NAME" => $name_val = "img_phoenix_headbgxs4pages",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "HEAD_HTML" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_HTML"),
                                "NAME" => $name_val = "phoenix_head_html",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_HEAD_HTML_HINT"),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),
                        ),
                        
                    );


                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG"]["VALUE"])>0)
                    {
                        $img = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG"]["VALUE"], array('width'=>900, 'height'=>1000), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG"]["SRC"] = $img["src"];

                        unset($img);
                    }

                    $logoID = 0;
                    $logoIDmob = 0;

                    $PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"] = "";

                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_LOGO"]["VALUE"]>0 && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"] == "dark")
                    {
                        $logoID = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_LOGO"]["VALUE"];

                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_MOB"]["VALUE"]>0)
                            $logoIDmob = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_MOB"]["VALUE"];
                    }

                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"] == "light")
                    {
                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_LIGHT"]["VALUE"]>0)
                            $logoID = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_LIGHT"]["VALUE"];
                        
                        else if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_LOGO"]["VALUE"]>0)
                            $logoID = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_LOGO"]["VALUE"];

                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_MOB_LIGHT"]["VALUE"] > 0)
                            $logoIDmob = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_MOB_LIGHT"]["VALUE"];

                        else if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_MOB"]["VALUE"] > 0)
                            $logoIDmob = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_MOB"]["VALUE"];
                    }


                    if($logoID)
                    {
                        $sizeLogo = array('width'=>300, 'height'=>100);
                        
                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_EXTRASIZE"]["VALUE"]["ACTIVE"] === 'Y')
                            $sizeLogo = array('width'=>600, 'height'=>200);

                        $logotypeResize = CFile::ResizeImageGet($logoID, $sizeLogo, BX_RESIZE_IMAGE_PROPORTIONAL, true);

                        $classVisibility = "";

                        if($logoIDmob)
                            $classVisibility = "hidden-md hidden-sm hidden-xs";

                        $PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"] = "<img class='logotype lazyload ".$classVisibility."' data-src='".$logotypeResize["src"]."' />";

                        $PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_SRC"]=$logotypeResize["src"];


                    }

                    if($logoIDmob)
                    {
                        $logotypeResize = CFile::ResizeImageGet($logoIDmob, array('width'=>500, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, true);

                        $PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"] .= "<img class='logotype lazyload visible-md visible-sm visible-xs' data-src='".$logotypeResize["src"]."' />";
                    }

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FAVICON']['VALUE']))
                    {
                        $file = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FAVICON']['VALUE'], array('width'=>40, 'height'=>40), BX_RESIZE_IMAGE_PROPORTIONAL, false);

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FAVICON"]["SRC"] = $file["src"];

                        unset($file);
                    
                    }

                }


                elseif($valueParams == "contacts" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"] = array(
                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_CONTACTS_NAME"),

                        "ITEMS" => array(

                            "SOC_GROUP_ICON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SOC_GROUP_ICON"),
                                "NAME" => $name_val = "img_phoenix_socgroup_icon",
                                "HINT" => Loc::GetMessage("PHOENIX_SOC_GROUP_ICON_HINT"),
                                "SRC" => "",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                /*"SETTINGS" => CFile::GetFileArray($opt),*/
                                "TYPE" => "image"
                            ),

                            "CALLBACK_SHOW" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CALLBACK_SHOW"),
                                "NAME" => $name_val = "phoenix_callback_show",
                                "VALUES" => array(

                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_CALLBACK_SHOW"),
                                        "VALUE" => "Y",
                                        "SHOW_OPTIONS" => "callback_show"
                                    )

                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "CALLBACK_NAME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CALLBACK_NAME"),
                                "NAME" => $name_val = "phoenix_callback_name",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_CALLBACK_NAME_HINT"),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CALLBACK_NAME_DEF"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "HEAD_CONTACTS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_CONTACTS"),
                                "NAME" => "phoenix_phones",
                                "VALUE" => $phones,
                                "~VALUE" => $phonesBack,
                                "HINT" => Loc::GetMessage("PHOENIX_HEAD_CONTACTS_HINT"),
                                "DESC_ON" => "Y",
                                "TYPE" => "text",
                                "MULTY" => "Y",
                                "FIELD_NAME" => Loc::GetMessage("PHOENIX_SETTINGS_CONTACTS"),
                                "BTN_NAME" => Loc::GetMessage("PHOENIX_SETTINGS_CONTACTS_ADD"),
                                "DESC_NAME" => Loc::GetMessage("PHOENIX_SETTINGS_CONTACTS_DESC"),
                            ),

                            "HEAD_EMAILS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_EMAILS"),
                                "NAME" => "phoenix_emails_addr",
                                "VALUE" => $emails_addr,
                                "DESC_ON" => "Y",
                                "TYPE" => "text",
                                "MULTY" => "Y",
                                "FIELD_NAME" => Loc::GetMessage("PHOENIX_SETTINGS_EMAILS"),
                                "BTN_NAME" => Loc::GetMessage("PHOENIX_SETTINGS_EMAILS_ADD"),
                                "DESC_NAME" => Loc::GetMessage("PHOENIX_SETTINGS_EMAILS_DESC"),
                            ),

                            "VK" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_VK"),
                                "NAME" => $name_val = "phoenix_soc_vk",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "FB" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FB"),
                                "NAME" => $name_val = "phoenix_soc_fb",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "TW" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_TW"),
                                "NAME" => $name_val = "phoenix_soc_tw",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "YOUTUBE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_YOUTUBE"),
                                "NAME" => $name_val = "phoenix_soc_youtube",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "INST" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_INST"),
                                "NAME" => $name_val = "phoenix_soc_inst",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "TELEGRAM" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_TELEGRAM"),
                                "NAME" => $name_val = "phoenix_soc_telegram",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "OK" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_OK"),
                                "NAME" => $name_val = "phoenix_soc_ok",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "TIKTOK" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_TIKTOK"),
                                "NAME" => $name_val = "phoenix_soc_tiktok",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "GROUP_POS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GROUP_POS"),

                                "VALUES" => array(

                                    "CONTACT" => array(
                                        "NAME" => "phoenix_soc_pos_contacts",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_GROUP_POS_CONTACTS"),
                                        "VALUE" => "Y"
                                    ),

                                    "MENU" => array(
                                        "NAME" => "phoenix_soc_pos_menu",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_GROUP_POS_OPEN_MENU"),
                                        "VALUE" => "Y"
                                    ),

                                    "MAIN_MENU" => array(
                                        "NAME" => "phoenix_soc_pos_main_menu",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_GROUP_POS_MAIN_MENU"),
                                        "VALUE" => "Y"
                                    ),

                                    "FOOTER" => array(
                                        "NAME" => "phoenix_soc_pos_footer",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_GROUP_POS_FOOTER"),
                                        "VALUE" => "Y"
                                    )
                                ),

                                "VALUE" => array(

                                    "CONTACT" => Option::get($moduleID, "phoenix_soc_pos_contacts", false, $siteId),
                                    "MAIN_MENU" => Option::get($moduleID, "phoenix_soc_pos_main_menu", false, $siteId),
                                    "MENU" => Option::get($moduleID, "phoenix_soc_pos_menu", false, $siteId),
                                    "FOOTER" => Option::get($moduleID, "phoenix_soc_pos_footer", false, $siteId)
                                    
                                ),
                                "TYPE" => "checkbox",
                            ),

                            "SHARE_ON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHARE_ON"),
                                "NAME" => $name_val = "phoenix_share_on",
                                "VALUES" => array(

                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHARE_ON"),
                                        "VALUE" => "Y"
                                    )

                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "SHARE_DETAIL_CATALOG_ON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHARE_DETAIL_CATALOG_ON"),
                                "VALUES" => array(

                                    "ACTIVE"=>array(
                                        "NAME" =>  $name_val = "phoenix_detail_catalog_shares_on",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHARE_DETAIL_CATALOG_ON"),
                                        "VALUE" => "Y",
                                        "SHOW_OPTIONS" => "share_detail_catalog"
                                    ),
                                ),

                                "VALUE" => array(

                                    "ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId),
                                ),

                                "TYPE" => "checkbox",
                            ),

                            "SHARES_COMMENT_FOR_DETAIL_CATALOG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHARES_COMMENT_FOR_DETAIL_CATALOG"),
                                "NAME" => $name_val = "phoenix_share_for_detail_catalog",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "WIDGET_FAST_CALL_ON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_WIDGET_FAST_CALL_ON"),
                                "NAME" => $name_val = "phoenix_widget_fast_call_on",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_WIDGET_FAST_CALL_ON"),
                                        "VALUE" => "Y",
                                        "SHOW_OPTIONS" => "call_phone_on"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "WIDGET_FAST_CALL_NUM" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_WIDGET_FAST_CALL_NUM"),
                                "NAME" => $name_val = "phoenix_widget_fast_call_num",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" =>  htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_WIDGET_FAST_CALL_NUM_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text"
                            ),

                            "WIDGET_FAST_CALL_DESC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_WIDGET_FAST_CALL_DESC"),
                                "NAME" => $name_val = "phoenix_widget_fast_call_desc",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" =>  htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_WIDGET_FAST_CALL_DESC_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text"
                            ),

                            "MAIN_MASK" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MASK"),
                                "NAME" => $name_val = "phoenix_mask",
                                "DESCRIPTIONS" => array(
                                    Loc::GetMessage("PHOENIX_MASK1"),
                                    Loc::GetMessage("PHOENIX_MASK2"),
                                    Loc::GetMessage("PHOENIX_MASK3"),
                                    Loc::GetMessage("PHOENIX_MASK4"),
                                    Loc::GetMessage("PHOENIX_MASK5")
                                ),

                                "VALUES" => array(
                                    "rus",
                                    "ukr",
                                    "blr",
                                    "kz",
                                    "user"
                                ),

                                "VALUE" => Option::get("concept.phoenix",  $name_val, false, $siteId),
                                "TYPE" => "select",

                                "ATTR" => "style='width: 250px;'"

                            ),

                            "USER_MASK" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_USER_MASK"),
                                "NAME" => $name_val = "phoenix_user_mask",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_USER_MASK_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            'STD_MASK' => array(
                                'rus'=>'+7 (999) 999-99-99',
                                'ukr'=>'+380 (99) 999-99-99',
                                'blr'=>'+375 (99) 999-99-99',
                                'kz'=>'+7 (999) 999-99-99',
                                'user'=>Option::get("concept.kraken", "phoenix_user_mask", false, $siteId)
                            ),



                            "ADDRESS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ADDRESS"),
                                "NAME" => $name_val = "phoenix_contact_address",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "DIALOG_MAP" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_DIALOG_MAP"),
                                "HINT" => Loc::GetMessage("PHOENIX_DIALOG_MAP_HINT"),
                                "NAME" => $name_val = "phoenix_contact_dialog_map",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text"
                            ),
                        ),
                    );


                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SOC_GROUP_ICON']["VALUE"])){
                        $arResizeImgTmp = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SOC_GROUP_ICON']["VALUE"], array('width'=>120, 'height'=>120), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SOC_GROUP_ICON']["SRC"] = $arResizeImgTmp['src'];
                    }


                    $PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"] = false;

                    $term = array(
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["VK"]['VALUE'],
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["FB"]['VALUE'],
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["TW"]['VALUE'],
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["YOUTUBE"]['VALUE'],
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["INST"]['VALUE'],
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["TELEGRAM"]['VALUE'],
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["OK"]['VALUE']
                    );

                    if(!empty($term))
                    {
                        foreach ($term as $key => $value) {
                            if(strlen($value) && !$PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"])
                            {
                                $PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"] = true;
                                break;
                            }
                        }
                    }


                }


                elseif($valueParams == "menu" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["NAME"]))
                {   
                    $tmpArr = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_MENU_NAME"),

                        "ITEMS" => array(

                            "LINK_SUBSECTIONS_BTN_NAME"=>array(

                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_MENU_LINK_SUBSECTIONS_BTN_NAME"),
                                "NAME" => $name_val = "phoenix_set_menu_link_subsections_btn_name",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_SET_MENU_LINK_SUBSECTIONS_BTN_NAME_DEFAULT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "DROPDOWN_MENU_WIDTH"=>array(
                                "DESCRIPTION"=>GetMessage("PHOENIX_DROPDOWN_MENU_WIDTH"),
                                "NAME" => $name_val = "phoenix_dropdown_menu_width",
                                "VALUES"=> array(
                                    "CONTENT" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_DROPDOWN_MENU_WIDTH_CONTENT"),
                                        "VALUE" => "content",
                                    ),
                                    "FULL" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => GetMessage("PHOENIX_DROPDOWN_MENU_WIDTH_FULL"),
                                        "VALUE" => "full",
                                    ),
                                ),
                                "VALUE"=> Option::get($moduleID, $name_val, false, $siteId),
                                "TYPE" => "radio",
                                "DEFAULT"=> "full"

                            ),

                            "MENU_FIXED_VIEW"=>array(
                                "DESCRIPTION"=>GetMessage("PHOENIX_ADMIN_MENU_FIXED_VIEW"),
                                "VALUES"=> array(
                                    "OPEN" => array(
                                        "NAME" => "phoenix_menu_fixed_view",
                                        "DESCRIPTION" => GetMessage("PHOENIX_ADMIN_MENU_FIXED_VIEW_OPEN"),
                                        "VALUE" => "view-open",
                                    ),
                                    "HIDE" => array(
                                        "NAME" => "phoenix_menu_fixed_view",
                                        "DESCRIPTION" => GetMessage("PHOENIX_ADMIN_MENU_FIXED_VIEW_HIDE"),
                                        "VALUE" => "view-hide",
                                    )
                                ),
                                "DEFAULT"=> "view-hide",
                                "VALUE"=> Option::get($moduleID, "phoenix_menu_fixed_view", false, $siteId),
                                "TYPE" => "radio",

                            ),


                            "CATALOG_COUNT_SHOW"=>array(

                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_MENU_CATALOG_COUNT_SHOW"),
                                "HINT" => Loc::GetMessage("PHOENIX_SET_MENU_CATALOG_COUNT_SHOW_HINT"),
                                "NAME" => $name_val = "phoenix_set_menu_catalog_count_show",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_SET_MENU_CATALOG_COUNT_SHOW_DEFAULT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "OTHER_COUNT_SHOW"=>array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_MENU_OTHER_COUNT_SHOW"),
                                "NAME" => $name_val = "phoenix_set_menu_other_count_show",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_SET_MENU_OTHER_COUNT_SHOW_DEFAULT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "MENU_TYPE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_TYPE"),
                                "NAME" => $name_val = "phoenix_menu_type",
                                "VALUES" => array(

                                    "hidden" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_TYPE1"),
                                        "VALUE" => "hidden",
                                        "SHOW_OPTIONS" => " ",
                                        
                                    ),

                                    "on_board" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_TYPE2"),
                                        "VALUE" => "on_board",
                                        "SHOW_OPTIONS" => "menu_type",
                                    ),
                                    "on_line" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_TYPE3"),
                                        "VALUE" => "on_line",
                                        "SHOW_OPTIONS" => "menu_type",

                                    ),

                                ),
                                "SHOW_OPTIONS" => "menu_type",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "on_line",
                                "TYPE" => "radio"

                            ),

                            "MENU_VIEW" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_VIEW"),
                                "NAME" => $name_val = "phoenix_menu_view",
                                "VALUES" => array(

                                    "full" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_VIEW1"),
                                        "VALUE" => "full"
                                    ),

                                    "content" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_VIEW2"),
                                        "VALUE" => "content"

                                    ),

                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "content",
                                "TYPE" => "radio",

                            ),

                            "VIEW_SECOND_LVL_FOR_CATALOG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_VIEW_SECOND_LVL_FOR_CATALOG"),
                                "NAME" => $name_val = "phoenix_menu_view_second_lvl_for_catalog",
                                "VALUES" => array(

                                    "default" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_VIEW_SECOND_LVL_FOR_CATALOG_DEF"),
                                        "VALUE" => "default"
                                    ),

                                    "flat" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_VIEW_SECOND_LVL_FOR_CATALOG_FLAT"),
                                        "VALUE" => "flat"

                                    ),

                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "default",
                                "TYPE" => "radio",

                            ),

                            "MOBILE_MENU_TONE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MOBILE_MENU_TONE"),
                                "NAME" => $name_val = "phoenix_mobile_menu_tone",
                                "VALUES" => array(

                                    "DARK" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_MOBILE_MENU_TONE_DARK"),
                                        "VALUE" => "dark"
                                    ),

                                    "LIGHT" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_MOBILE_MENU_TONE_LIGHT"),
                                        "VALUE" => "light"

                                    ),

                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "dark",
                                "TYPE" => "radio",
                            ),

                            "MENU_BG_COLOR" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_BG_COLOR"),
                                "NAME" => $name_val = "phoenix_menu_bg_color",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_MENU_BG_COLOR_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "color_picker"
                                ),
                            ),

                            "MENU_BG_OPACITY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_BG_OPACITY"),
                                "NAME" => $name_val = "phoenix_menu_bg_opacity",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_MENU_BG_OPACITY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text"
                            ),

                            "MENU_TEXT_COLOR" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_TEXT_COLOR"),
                                "NAME" => $name_val = "phoenix_menu_text_color",
                                "VALUES" => array(

                                    "DEF" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_TEXT_COLOR_NONE"),
                                        "VALUE" => "def"
                                    ),

                                    "DARK" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_TEXT_COLOR_DARK"),
                                        "VALUE" => "dark"
                                    ),

                                    "LIGHT" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_MENU_TEXT_COLOR_LIGHT"),
                                        "VALUE" => "light"

                                    ),

                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "def",
                                "TYPE" => "radio",
                            ),

                            "HIDE_EMPTY_CATALOG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_HIDE_EMPTY_CATALOG"),
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => "phoenix_hide_empty_catalog",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_HIDE_EMPTY_CATALOG"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_hide_empty_catalog", false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "SUBMENU_MAX_QUANTITY_SECTION" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SUBMENU_MAX_QUANTITY_SECTION"),
                                "NAME" => $name_val = "phoenix_submenu_max_quantity_section",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "DEFAULT" => "3",
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),


                        ),
                    );

                    if(empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]))
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"] = array();

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"] = array_merge($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"], $tmpArr);

                }

                elseif($valueParams == "footer" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"] = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_FOOTER_NAME"),

                        "ITEMS" => array(

                            "FOOTER_MAIN" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_FOOTER_MAIN"),
                                "HINT"=>Loc::GetMessage("PHOENIX_SET_FOOTER_MAIN_HINT"),
                                "NAME" => $name_val = "phoenix_footer_main",
                                "VALUE" => $opt = trim(Option::get("concept.phoenix", $name_val, false, $siteId)),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "TEXT_COLOR" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_TEXT_COLOR"),
                                "NAME" => $name_val = "phoenix_footer_text_color",
                                "VALUES" =>  array(
                                    "DEF" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_TEXT_COLOR_DEF"),
                                        "VALUE" => "default",
                                    ),

                                    "DARK" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_TEXT_COLOR_DARK"),
                                        "VALUE" => "dark",
                                    ),

                                    "LIGHT" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_TEXT_COLOR_LIGHT"),
                                        "VALUE" => "light",
                                    )
                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "default",
                                "TYPE" => "radio",
                            ),

                            "HIDE_BG_TONE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_HIDE_BG_TONE"),
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => "phoenix_footer_hide_bg_tone",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_HIDE_BG_TONE"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_footer_hide_bg_tone", false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_FOOTER_ON_HINT"),
                                "TYPE" => "checkbox",
                            ),

                            "FOOTER_ON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_ON"),
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => "phoenix_footer_on",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_ON"),
                                        "VALUE" => "Y",
                                        "HINT" => Loc::GetMessage("PHOENIX_FOOTER_ON_HINT"),
                                        "SHOW_OPTIONS" => "footer_options"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_footer_on", false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_FOOTER_ON_HINT"),
                                "TYPE" => "checkbox",
                            ),

                            "FOOTER_DESC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_DESC"),
                                "NAME" => $name_val = "phoenix_footer_desc",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowMD,
                                "SIZE" => $colMD,
                                "TYPE" => "text"
                            ),

                            "FOOTER_INFO" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_INFO"),
                                "NAME" => $name_val = "phoenix_footer_info",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_FOOTER_INFO_HINT"),
                                "ROWS" => $rowMD,
                                "SIZE" => $colMD,
                                "TYPE" => "text"
                            ),

                            "FOOTER_BG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_BG"),
                                "NAME" => $name_val = "img_phoenix_footer_bg",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "FOOTER_COLOR_BG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_COLOR_BG"),
                                "NAME" => $name_val = "phoenix_footer_color_bg",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_FOOTER_COLOR_BG_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "color_picker"
                                )
                            ),

                            "FOOTER_OPACITY_BG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_OPACITY_BG"),
                                "NAME" => $name_val = "phoenix_footer_opacity_bg",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_FOOTER_OPACITY_BG_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text"
                            ),

                            "FOOTER_COPYRIGHT_ON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_ON"),
                                "NAME" => $name_val = "phoenix_footer_copyright_on",
                                "VALUES" =>  array(
                                    "ACTIVE"=>array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_ON"),
                                        "VALUE" => "Y",
                                        "SHOW_OPTIONS" => "footer_author_show"
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_ON_HINT"),
                                "TYPE" => "checkbox",
                            ),

                            "FOOTER_COPYRIGHT_TYPE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_TYPE"),
                                "NAME" => $name_val = "phoenix_footer_copyright_type",
                                "VALUES" =>  array(
                                    "DEF" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_TYPE_DEF"),
                                        "VALUE" => "default",
                                        "SHOW_OPTIONS" => " "
                                    ),

                                    "USER" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_TYPE_USER"),
                                        "VALUE" => "user",
                                        "SHOW_OPTIONS" => "footer_author_type_user"
                                    )
                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "default",
                                "HINT" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_TYPE_HINT"),
                                "TYPE" => "radio",
                            ),

                            "FOOTER_COPYRIGHT_USER_DESC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_USER_DESC"),
                                "NAME" => $name_val = "phoenix_footer_copyright_user_desc",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_USER_DESC_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "FOOTER_COPYRIGHT_USER_PIC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_USER_PIC"),
                                "NAME" => $name_val = "img_phoenix_footer_copyright_user_pic",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_USER_PIC_HINT"),
                                "TYPE" => "image"
                            ),

                            "FOOTER_COPYRIGHT_USER_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_USER_URL"),
                                "NAME" => $name_val = "phoenix_footer_copyright_user_url",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "HINT" => Loc::GetMessage("PHOENIX_FOOTER_COPYRIGHT_USER_URL_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text"
                            ),

                            "BANNER_1" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_BANNER_1"),
                                "NAME" => $name_val = "img_phoenix_footer_banner_1",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "BANNER_1_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_BANNER_1_URL"),
                                "NAME" => $name_val = "phoenix_footer_banner_1_url",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "BANNER_2" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_BANNER_2"),
                                "NAME" => $name_val = "img_phoenix_footer_banner_2",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "BANNER_2_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_BANNER_2_URL"),
                                "NAME" => $name_val = "phoenix_footer_banner_2_url",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "SUBSCRIPE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_SUBSCRIPE"),
                                "NAME" => $name_val = "phoenix_footer_subscripe",
                                "VALUES" =>  array(
                                    "ACTIVE"=>array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_SUBSCRIPE"),
                                        "VALUE" => "Y",
                                        "SHOW_OPTIONS" => "footer_subscripe"
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                                "OTHER" => Array(
                                    "DESC_FOR_INPUT" => Loc::GetMessage("PHOENIX_FOOTER_SUBSCRIPE_DESC_FOR_INPUT"),
                                )
                            ),

                            "SUBSCRIPE_DESCRIPTION" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_SUBSCRIPE_DESCRIPTION"),
                                "NAME" => $name_val = "phoenix_footer_subscripe_description",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "SUBSCRIPE_RUBRICS_SHOW" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_SUBSCRIPE_RUBRICS_SHOW"),
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => "phoenix_rubrics_show",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_SUBSCRIPE_RUBRICS_SHOW"),
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_rubrics_show", false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "PAYMENT_PIC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_PAYMENT_PIC"),
                                "NAME" => $name_val = "img_phoenix_footer_payment_pic",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "PAYMENT_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_PAYMENT_URL"),
                                "NAME" => $name_val = "phoenix_footer_payment_url",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "PAYMENT_HINT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FOOTER_PAYMENT_HINT"),
                                "NAME" => $name_val = "phoenix_footer_payment_hint",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                        ),

                    );
                }


                elseif($valueParams == "catalog" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["NAME"]))
                {   
                    if(CModule::IncludeModule("catalog"))
                    {

                        if(empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]))
                        {
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"] = array();
                        }


                        self::getIblockIDs(array(
                            "SITE_ID"=>$siteId, 
                            "CODES" => array(
                                "concept_phoenix_catalog_".$siteId,
                                "concept_phoenix_catalog_offers_".$siteId
                                )
                            )
                        );

                        $tmpArr = array(

                            "NAME" => Loc::GetMessage("PHOENIX_ADMIN_CATALOG_NAME"),

                            "CONFIG" => array(
                                "default_quantity_trace" => COption::GetOptionString("catalog", "default_quantity_trace"),
                                "default_can_buy_zero"=>COption::GetOptionString("catalog", "default_can_buy_zero")
                            ),

                            "ITEMS" => array(

                                "SIDE_MENU_HTML" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_SIDE_MENU_HTML"),
                                    "NAME" => $name_val = "phoenix_catalog_side_menu_html",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $rowMD,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                        "SIZE_HEIGHT" => "middle",
                                        "TYPE" => "textarea"
                                    )
                                ),

                                "DETAIL_PAGE_SORT_ADV" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_ADV"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_adv",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "10",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_CHAR" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_CHAR"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_char",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "20",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_GALLERY" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_GALLERY"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_gallery",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "30",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_VIDEO" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_VIDEO"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_video",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "40",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_REVIEW" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_REVIEW"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_review",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "70",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_FAQ" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_FAQ"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_faq",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "80",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_INFO_1" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_INFO_1"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_info_1",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "90",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_INFO_2" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_INFO_2"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_info_2",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "91",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_INFO_3" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_INFO_3"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_info_3",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "92",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_EMPL" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_EMPL"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_empl",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "100",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_GOODS" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_GOODS"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_goods",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "110",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_CATEGORY" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_CATEGORY"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_category",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "120",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_ACCESSORIES" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_ACCESSORIES"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_accessories",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "130",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_RATING" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_RATING"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_rating",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "140",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_COMPLECT" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_COMPLECT"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_complect",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "5",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DETAIL_PAGE_SORT_SET" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DETAIL_PAGE_SORT_SET"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_page_sort_set",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "6",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),



                                "CHARS_SHOW_COUNT" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_CHARS_SHOW_COUNT"),
                                    "NAME" => $name_val = "phoenix_catalog_chars_show_count",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "5",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                ),

                                "FILES_SHOW_COUNT" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_FILES_SHOW_COUNT"),
                                    "NAME" => $name_val = "phoenix_catalog_files_show_count",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "3",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                ),

                                "CUSTOM_URL" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_CUSTOM_URL"),
                                    "NAME" => $name_val = "phoenix_catalog_custom_url",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colMD,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "DEFAULT_LIST_VIEW" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DEFAULT_LIST_VIEW"),
                                    "NAME" => $name_val = "phoenix_catalog_default_list_view",
                                    "VALUES" =>  array(
                                        "FLAT" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DEFAULT_LIST_VIEW_FLAT"),
                                            "VALUE" => "FLAT"
                                        ),
                                        "LIST" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DEFAULT_LIST_VIEW_LIST"),
                                            "VALUE" => "LIST"
                                        ),
                                        "TABLE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_DEFAULT_LIST_VIEW_TABLE"),
                                            "VALUE" => "TABLE"
                                        ),
                                        
                                    ),
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "FLAT",
                                    "TYPE" => "radio",
                                ),

                                "OFFERS_QUANTITY" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_OFFERS_QUANTITY"),
                                    "HINT" => Loc::GetMessage("PHOENIX_SET_CATALOG_OFFERS_QUANTITY_HINT"),
                                    "NAME" => $name_val = "phoenix_catalog_offers_quantity",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                    "DEFAULT" => "0",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "SHOW_DEACTIVATED_GOODS" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_SHOW_DEACTIVATED_GOODS"),
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => "phoenix_catalog_show_deactivated_goods",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_SHOW_DEACTIVATED_GOODS"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_catalog_show_deactivated_goods", false, $siteId)),
                                    "TYPE" => "checkbox"
                                ),

                                "TAB_SECTIONS_SHOW" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_TAB_SECTIONS_SHOW"),
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => "phoenix_catalog_tabsections_show",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_TAB_SECTIONS_SHOW"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_catalog_tabsections_show", false, $siteId)),
                                    "TYPE" => "checkbox"
                                ),


                                "HIDE_NOT_AVAILABLE" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_HIDE_NOT_AVAILABLE"),
                                    "NAME" => $name_val = "phoenix_catalog_hidenotavailable",
                                    "VALUES" =>  array(
                                        "Y" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_HIDE_NOT_AVAILABLE_Y"),
                                            "VALUE" => "Y"
                                        ),
                                        "L" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_HIDE_NOT_AVAILABLE_L"),
                                            "VALUE" => "L"
                                        ),
                                        "N" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_HIDE_NOT_AVAILABLE_N"),
                                            "VALUE" => "N"
                                        ),
                                        
                                    ),
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "L",
                                    "TYPE" => "radio",
                                ),

                                "HIDE_NOT_AVAILABLE_OFFERS" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_HIDE_NOT_AVAILABLE_OFFERS"),
                                    "NAME" => $name_val = "phoenix_catalog_hidenotavailableoffers",
                                    "VALUES" =>  array(
                                        "Y" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_HIDE_NOT_AVAILABLE_OFFERS_Y"),
                                            "VALUE" => "Y"
                                        ),
                                        "L" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_HIDE_NOT_AVAILABLE_OFFERS_L"),
                                            "VALUE" => "L"
                                        ),
                                        "N" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_HIDE_NOT_AVAILABLE_OFFERS_N"),
                                            "VALUE" => "N"
                                        ),
                                        
                                    ),
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "N",
                                    "TYPE" => "radio",
                                ),


                                "FILTER_HIDE_PRICE" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_FILTER_HIDE_PRICE"),
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => "phoenix_catalog_filter_hide_price",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_FILTER_HIDE_PRICE"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_catalog_filter_hide_price", false, $siteId)),
                                    "TYPE" => "checkbox"
                                ),

                                "HIDE_PERCENT" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_HIDE_PERCENT"),
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => "phoenix_catalog_hide_percent",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_HIDE_PERCENT"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_catalog_hide_percent", false, $siteId)),
                                    "TYPE" => "checkbox"
                                ),

                                "NEWS_COUNT" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_COUNT_ELEMENTS"),
                                    "NAME" => $name_val = "phoenix_catalog_count_elements",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                    "DEFAULT" => "24",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "STORES_ID" => array(),

                                "SHOW_STORE_BLOCK" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SHOW_STORE_BLOCK"),
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => "phoenix_catalog_show_store_block",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SHOW_STORE_BLOCK"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_catalog_show_store_block", false, $siteId)),
                                    "TYPE" => "checkbox"
                                ),


                                "STORE_BLOCK_VIEW" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_STORE_BLOCK_VIEW"),
                                    "NAME" => $name_val = "phoenix_catalog_store_block_view",
                                    "VALUES" =>  array(
                                        "list" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_STORE_BLOCK_VIEW_list"),
                                            "VALUE" => "list"
                                        ),
                                        /*"flat" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_STORE_BLOCK_VIEW_flat"),
                                            "VALUE" => "flat"
                                        ),*/
                                        "popup" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_STORE_BLOCK_VIEW_popup"),
                                            "VALUE" => "popup"
                                        )
                                        
                                    ),
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "list",
                                    "TYPE" => "radio",
                                ),

                                "SHOW_EMPTY_STORE" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SHOW_EMPTY_STORE"),
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => "phoenix_catalog_show_empty_store",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SHOW_EMPTY_STORE"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_catalog_show_empty_store", false, $siteId)),
                                    "TYPE" => "checkbox"
                                ),

                                "SECTION_SORT_LIST" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SECTION_SORT_LIST"),
                                    "NAME" => $name_val = "phoenix_catalog_section_sort_list",
                                    "VALUES" =>  array(
                                        "SORT_asc" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SECTION_SORT_LIST_SORT_asc"),
                                            "VALUE" => "SORT_asc"
                                        ),
                                        "SORT_desc" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SECTION_SORT_LIST_SORT_desc"),
                                            "VALUE" => "SORT_desc"
                                        ),
                                        "PRICE_asc" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SECTION_SORT_LIST_PRICE_asc"),
                                            "VALUE" => "PRICE_asc"
                                        ),
                                        "PRICE_desc" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SECTION_SORT_LIST_PRICE_desc"),
                                            "VALUE" => "PRICE_desc"
                                        ),
                                        "NAME_asc" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SECTION_SORT_LIST_NAME_asc"),
                                            "VALUE" => "NAME_asc"
                                        ),
                                        "NAME_desc" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SECTION_SORT_LIST_NAME_desc"),
                                            "VALUE" => "NAME_asc"
                                        ),
                                    ),
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "SORT_asc",
                                    "TYPE" => "radio",
                                ),

                                "SUBSCRIBE_BTN_NAME" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_SUBSCRIBE_SUBSCRIBE_BTN_NAME"),
                                    "NAME" => $name_val = "phoenix_subscribe_btn_name",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "DEFAULT" =>Loc::GetMessage("PHOENIX_SET_SUBSCRIBE_SUBSCRIBE_BTN_NAME_DEF"),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                          "VIEW" => "decorated",
                                    )
                                ),

                                "SUBSCRIBED_BTN_NAME" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_SUBSCRIBE_SUBSCRIBED_BTN_NAME"),
                                    "NAME" => $name_val = "phoenix_subscribed_btn_name",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "DEFAULT" =>Loc::GetMessage("PHOENIX_SET_SUBSCRIBE_SUBSCRIBED_BTN_NAME_DEF"),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                          "VIEW" => "decorated",
                                    )
                                ),

                                "MODE_PREORDER_FORM" => array(

                                    "NAME" => $name_val = "phoenix_catalog_mode_preorder_form",
                                    "DESCRIPTIONS" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["NAME"],
                                    "VALUES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["VALUES"],
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_MODE_PREORDER_FORM"),
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" =>  "N",
                                    "TYPE" => "select"

                                ),

                                "MODE_PREORDER_BTN_NAME" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_MODE_PREORDER_BTN_NAME"),
                                    "NAME" => $name_val = "phoenix_catalog_mode_preorder_btn_name",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "DEFAULT" =>Loc::GetMessage("PHOENIX_SET_MODE_PREORDER_BTN_NAM_DEF"),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                          "VIEW" => "decorated",
                                    )
                                ),

                                "AUTO_MODE_PREORDER" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_AUTO_MODE_PREORDER"),
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => "phoenix_catalog_auto_mode_preorder",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_AUTO_MODE_PREORDER"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_catalog_auto_mode_preorder", false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "SHOW_PREDICTION" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_SHOW_PREDICTION"),
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => "phoenix_show_prediction",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_SHOW_PREDICTION"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_show_prediction", false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),


                                "DETAIL_SORT_SKU_CHARS" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_DETAIL_SORT_SKU_CHARS"),
                                    "NAME" => $name_val = "phoenix_catalog_detail_sort_sku_chars",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" =>"5",
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                          "VIEW" => "decorated",
                                    )
                                ),

                                  "DETAIL_SORT_PROPS_CHARS" => array(
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_DETAIL_SORT_PROPS_CHARS"),
                                        "NAME" => $name_val = "phoenix_catalog_detail_sort_props_chars",
                                        "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                        "DEFAULT" =>"10",
                                        "ROWS" => $rowONE,
                                        "SIZE" => $colSM,
                                        "TYPE" => "text",
                                        "IN_PUBLIC" => array(
                                              "VIEW" => "decorated",
                                        )
                                  ),

                                  "DETAIL_SORT_PROP_CHARS" => array(
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_DETAIL_SORT_PROP_CHARS"),
                                        "NAME" => $name_val = "phoenix_catalog_detail_sort_prop_chars",
                                        "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                        "DEFAULT" =>"15",
                                        "ROWS" => $rowONE,
                                        "SIZE" => $colSM,
                                        "TYPE" => "text",
                                        "IN_PUBLIC" => array(
                                              "VIEW" => "decorated",
                                        )
                                  ),

                                  "USE_BTN_SCROLL2CHARS" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_USE_BTN_SCROLL2CHARS"),
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => "phoenix_use_btn_scroll2chars",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_USE_BTN_SCROLL2CHARS"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_use_btn_scroll2chars", false, $siteId)),
                                    "TYPE" => "checkbox",
                                  ),

                                "PIC_IN_HEAD" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_PIC_IN_HEAD"),
                                    "NAME" => $name_val = "img_phoenix_ctlg_pic_in_head",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "SETTINGS" => CFile::GetFileArray($opt),
                                    "TYPE" => "image"
                                ),


                                "FILTER_IN_STOCK" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_FILTER_IN_STOCK"),
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => "phoenix_filter_in_stock",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_FILTER_IN_STOCK"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE" => ""/*Option::get($moduleID, "phoenix_filter_in_stock", false, $siteId))*/),
                                    "TYPE" => "checkbox",
                                ),


                                /*"ORDER_BTN_NAME" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_ORDER_BTN_NAME"),
                                    "NAME" => $name_val = "phoenix_catalog_btn_name",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "HINT" => Loc::GetMessage("PHOENIX_ORDER_BTN_NAME_HINT"),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),*/

                                "LINK_2_DETAIL_PAGE_NAME" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_LINK_2_DETAIL_PAGE_NAME"),
                                    "NAME" => $name_val = "phoenix_catalog_link_2_detail_page_name",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => Loc::GetMessage("PHOENIX_LINK_2_DETAIL_PAGE_NAME_DEF"),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "LINK_2_DETAIL_PAGE_NAME_OFFER" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_LINK_2_DETAIL_PAGE_NAME_OFFER"),
                                    "NAME" => $name_val = "phoenix_catalog_link_2_detail_page_name_offer",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => Loc::GetMessage("PHOENIX_LINK_2_DETAIL_PAGE_NAME_OFFER_DEF"),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "LINK_2_DETAIL_PAGE_NAME_OFFER_MOB" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_LINK_2_DETAIL_PAGE_NAME_OFFER_MOB"),
                                    "NAME" => $name_val = "phoenix_catalog_link_2_detail_page_name_offer_mob",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => Loc::GetMessage("PHOENIX_LINK_2_DETAIL_PAGE_NAME_OFFER_MOB_DEF"),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "HEAD_BG_PIC" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_HEAD_BG_PIC"),
                                    "NAME" => $name_val = "img_phoenix_catalog_head_bg_pic",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "SETTINGS" => CFile::GetFileArray($opt),
                                    "TYPE" => "image"
                                ),

                                "SUBTITLE" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SUBTITLE"),
                                    "NAME" => $name_val = "phoenix_catalog_subtitle",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "HINT" => Loc::GetMessage("PHOENIX_SUBTITLE_HINT"),
                                    "ROWS" => $rowSM,
                                    "SIZE" => $colLG,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "TOP_TEXT" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_TOP_TEXT"),
                                    "NAME" => $name_val = "phoenix_catalog_top_text",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "HINT" => Loc::GetMessage("PHOENIX_CATALOG_TOP_TEXT_HINT"),
                                    "ROWS" => $colVisFull,
                                    "SIZE" => $colVisLG,
                                    "TYPE" => "text",
                                    "VIEW" => "visual",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                        "SIZE_HEIGHT" => "middle",
                                        "TYPE" => "textarea"
                                    )
                                ),

                                "BOT_TEXT" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_BOT_TEXT"),
                                    "NAME" => $name_val = "phoenix_catalog_bot_text",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "HINT" => Loc::GetMessage("PHOENIX_CATALOG_BOT_TEXT_HINT"),
                                    "ROWS" => $colVisFull,
                                    "SIZE" => $colVisLG,
                                    "TYPE" => "text",
                                    "VIEW" => "visual",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                        "SIZE_HEIGHT" => "middle",
                                        "TYPE" => "textarea"
                                    )
                                ),

                                "WATERMARK" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_WATERMARK"),
                                    "NAME" => $name_val = "img_phoenix_catalog_watermark",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "SETTINGS" => CFile::GetFileArray($opt),
                                    "TYPE" => "image"
                                ),

                                "VIEW_XS" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_VIEW_XS"),
                                    "NAME" => $name_val = "phoenix_catalog_view_xs",
                                    "VALUES" =>  array(
                                        "12" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_VIEW_XS_ONE"),
                                            "VALUE" => "12"
                                        ),
                                        "6" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_VIEW_XS_TWO"),
                                            "VALUE" => "6"
                                        )
                                    ),
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "6",
                                    "TYPE" => "radio",
                                ),

                                "LABELS_MAX_COUNT" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_LABELS_MAX_COUNT"),
                                    "NAME" => $name_val = "phoenix_catalog_labels_max_count",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "DEFAULT" => "8",
                                    "HINT" => Loc::GetMessage("PHOENIX_LABELS_MAX_COUNT_HINT"),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                ),

                                "STORE_QUANTITY_ON" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_STORE_QUANTITY_ON"),
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => "phoenix_store_quantity_on",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_STORE_QUANTITY_ON"),
                                            "VALUE" => "Y",
                                            "SHOW_OPTIONS" => "quantity_sets"
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_store_quantity_on", false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "STORE_QUANTITY_MANY" => array(
                                    "DESCRIPTION" => getMessage("PHOENIX_STORE_QUANTITY_MANY"),
                                    "DESCRIPTION_2" => getMessage("PHOENIX_STORE_QUANTITY_MANY_2"),
                                    "DESCRIPTION_NOEMPTY" => getMessage("PHOENIX_STORE_QUANTITY_MANY_NOEMPTY"),
                                    "DESCRIPTION_EMPTY" => getMessage("PHOENIX_STORE_QUANTITY_MANY_EMPTY"),
                                    "NAME" => $name_val = "phoenix_store_quantity_many",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "HINT" => Loc::GetMessage("PHOENIX_STORE_QUANTITY_MANY_HINT"),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                ),

                                "STORE_QUANTITY_FEW" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_STORE_QUANTITY_FEW"),
                                    "DESCRIPTION_2" => Loc::GetMessage("PHOENIX_STORE_QUANTITY_FEW_2"),
                                    "NAME" => $name_val = "phoenix_store_quantity_few",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "HINT" => Loc::GetMessage("PHOENIX_STORE_QUANTITY_FEW_HINT"),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colSM,
                                    "TYPE" => "text",
                                ),

                                "STORE_QUANTITY_VIEW" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_STORE_QUANTITY_VIEW"),
                                    "NAME" => $name_val = "phoenix_store_quantity_view",
                                    "VALUES" =>  array(
                                        "visible" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_STORE_QUANTITY_VIEW_VISIBLE"),
                                            "VALUE" => "visible",
                                            "SHOW_OPTIONS" => " "
                                        ),
                                        "invisible" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_STORE_QUANTITY_VIEW_INVISIBLE"),
                                            "VALUE" => "invisible",
                                            "SHOW_OPTIONS" => " quantity_invisible"
                                        )
                                    ),
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "invisible",
                                    "TYPE" => "radio"
                                ),

                                "PREVIEW_TEXT_POSITION" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_PREVIEW_TEXT_POSITION"),
                                    "NAME" => $name_val = "phoenix_catalog_preview_text_position",
                                    "VALUES" =>  array(
                                        "right" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_PREVIEW_TEXT_POSITION_RIGHT"),
                                            "VALUE" => "right"
                                        ),
                                        "under_pic" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_PREVIEW_TEXT_POSITION_UNDER_PIC"),
                                            "VALUE" => "under_pic"
                                        )
                                    ),
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "under_pic",
                                    "TYPE" => "radio",
                                ),

                                "DESC_FOR_ACTUAL_PRICE" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_DESC_FOR_ACTUAL_PRICE"),
                                    "NAME" => $name_val = "phoenix_desc_for_actual_price",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colLG,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "TIT_FOR_QUANTITY" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_TIT_FOR_QUANTITY"),
                                    "NAME" => $name_val = "phoenix_tit_for_quantity",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colLG,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "COMMENT_FOR_QUANTITY" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_COMMENT_FOR_QUANTITY"),
                                    "NAME" => $name_val = "phoenix_commment_for_quantity",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $rowMD,
                                    "SIZE" => $colLG,
                                    "TYPE" => "text"
                                ),

                                "COMMENT_FOR_DETAIL_CATALOG" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_COMMENT_FOR_DETAIL_CATALOG"),
                                    "NAME" => $name_val = "phoenix_for_detail_catalog",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $rowSM,
                                    "SIZE" => $colLG,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                        "TYPE" => "textarea",
                                        "SIZE_HEIGHT" => "middle",
                                    )
                                ),

                                "SHARES" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_DETAIL_CATALOG_SHARES"),
                                    "VALUES" => array(

                                        "VK"=>array(
                                            "NAME" => "phoenix_share_vk",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_DETAIL_CATALOG_SHARE_VK"),
                                            "VALUE" => "Y"
                                        ),
                                        "FB"=>array(
                                            "NAME" => "phoenix_share_fb",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_DETAIL_CATALOG_SHARE_FB"),
                                            "VALUE" => "Y"
                                        ),
                                        "TW"=>array(
                                            "NAME" => "phoenix_share_tw",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_DETAIL_CATALOG_SHARE_TW"),
                                            "VALUE" => "Y"
                                        ),

                                    ),

                                    "VALUE" => array(

                                        "VK"=>Option::get($moduleID, "phoenix_share_vk", false, $siteId),
                                        "FB"=>Option::get($moduleID, "phoenix_share_fb", false, $siteId),
                                        "TW"=>Option::get($moduleID, "phoenix_share_tw", false, $siteId),

                                    ),

                                    "TYPE" => "checkbox",
                                ),

                                "DELAY_ON" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_DELAY_ON"),
                                    "NAME" => $name_val = "phoenix_delay_on",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_DELAY_ON"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "ZOOM_ON" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_ZOOM_ON"),
                                    "HINT" => Loc::GetMessage("PHOENIX_ZOOM_ON_HINT"),
                                    "NAME" => $name_val = "phoenix_zoom_on",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_ZOOM_ON"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "CONVERT_CURRENCY" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CONVERT_CURRENCY"),
                                    "NAME" => $name_val = "phoenix_convert_currecy",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CONVERT_CURRENCY"),
                                            "VALUE" => "Y",
                                            "SHOW_OPTIONS" => "currency_id"
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "USE_PRICE_COUNT" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_USE_PRICE_COUNT"),
                                    "HINT" => Loc::GetMessage("PHOENIX_USE_PRICE_COUNT_HINT"),
                                    "NAME" => $name_val = "phoenix_use_price_count",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_USE_PRICE_COUNT"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "SHOW_AVAILABLE_PRICE_COUNT" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOW_AVAILABLE_PRICE_COUNT"),
                                    "HINT" => Loc::GetMessage("PHOENIX_SHOW_AVAILABLE_PRICE_COUNT_HINT"),
                                    "NAME" => $name_val = "phoenix_show_available_price_count",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOW_AVAILABLE_PRICE_COUNT"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),
                                
                                "SORT_LEFT_SIDE" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SORT_LEFT_SIDE"),
                                    "NAME" => $name_val = "phoenix_catalog_sort_left_side",
                                    "VALUES" =>  array(
                                        "filter" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SORT_LEFT_SIDE_FILTER"),
                                            "VALUE" => "filter"
                                        ),
                                        "sections" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SORT_LEFT_SIDE_SECTIONS"),
                                            "VALUE" => "sections"
                                        )
                                    ),
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "filter",
                                    "TYPE" => "radio",
                                ),

                                "STORIES" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_STORIES"),

                                    "VALUES" => array(
                                        "CATALOG" => array(
                                            "NAME" => "phoenix_catalog_stories_catalog",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_STORIES_CATALOG"),
                                            "VALUE" => "Y"
                                        ),
                                        "BASKET" => array(
                                            "NAME" => "phoenix_catalog_stories_basket",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_STORIES_BASKET"),
                                            "VALUE" => "Y"
                                        ),
                                        "LAND" => array(
                                            "NAME" => "phoenix_catalog_stories_land",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_STORIES_LAND"),
                                            "VALUE" => "Y"
                                        ),

                                        "BRANDS" => array(
                                            "NAME" => "phoenix_catalog_stories_brands",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_STORIES_BRANDS"),
                                            "VALUE" => "Y"
                                        ),

                                        "NEWS" => array(
                                            "NAME" => "phoenix_catalog_stories_news",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_STORIES_NEWS"),
                                            "VALUE" => "Y"
                                        ),

                                        "BLOG" => array(
                                            "NAME" => "phoenix_catalog_stories_blog",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_STORIES_BLOG"),
                                            "VALUE" => "Y"
                                        ),

                                        "ACTIONS" => array(
                                            "NAME" => "phoenix_catalog_stories_actions",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_STORIES_ACTIONS"),
                                            "VALUE" => "Y"
                                        ),
                                        

                                    ),

                                    "VALUE" => array(
                                        "CATALOG" => Option::get($moduleID, "phoenix_catalog_stories_catalog", false, $siteId),
                                        "BASKET" => Option::get($moduleID, "phoenix_catalog_stories_basket", false, $siteId),
                                        "LAND" => Option::get($moduleID, "phoenix_catalog_stories_land", false, $siteId),
                                        "BRANDS" => Option::get($moduleID, "phoenix_catalog_stories_brands", false, $siteId),
                                        "NEWS" => Option::get($moduleID, "phoenix_catalog_stories_news", false, $siteId),
                                        "BLOG" => Option::get($moduleID, "phoenix_catalog_stories_blog", false, $siteId),
                                        "ACTIONS" => Option::get($moduleID, "phoenix_catalog_stories_actions", false, $siteId),
                                        
                                        
                                    ),
                                    "TYPE" => "checkbox",
                                ),

                                "USE_FILTER" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_USE_FILTER"),
                                    "NAME" => $name_val = "phoenix_catalog_use_filter",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_USE_FILTER"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "FILTER_SHOW" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_FILTER_SHOW"),
                                    "NAME" => $name_val = "phoenix_catalog_filter_show",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_FILTER_SHOW"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "FILTER_SCROLL" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_FILTER_SCROLL"),
                                    "NAME" => $name_val = "phoenix_catalog_filter_scroll",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_FILTER_SCROLL"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "MAIN_SECTIONS_LIST" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_MAIN_SECTIONS_LIST"),
                                    "NAME" => $name_val = "phoenix_catalog_main_section_list",
                                    "VALUES" =>  array(
                                        "D2P" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_MAIN_SECTIONS_LIST_1"),
                                            "VALUE" => "view-1"
                                        ),
                                        "P2D" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_MAIN_SECTIONS_LIST_2"),
                                            "VALUE" => "view-2"
                                        )
                                    ),
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "DEFAULT" => "view-1",
                                    "TYPE" => "radio",
                                ),


                                "SUBSECTIONS_HIDE_COUNT" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SUBSECTIONS_HIDE_COUNT"),
                                    "NAME" => $name_val = "phoenix_catalog_subsections_hide_count",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_SUBSECTIONS_HIDE_COUNT"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "USE_RUB" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_USE_RUB"),
                                    "NAME" => $name_val = "phoenix_catalog_use_rub",
                                    "HINT" => Loc::GetMessage("PHOENIX_CATALOG_USE_RUB_HINT"),
                                    "VALUES" =>  array(

                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_USE_RUB"),
                                            "VALUE" => "Y"
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                            ),


                        );

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"] = array_merge($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"], $tmpArr);

                        unset($tmpArr);


                        


                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["CURRENCY_ID"] = array(
                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CURRENCY_ID"),
                            "NAME" => $name_val = "phoenix_currency_id",
                            "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                            "DEFAULT" => Currency\CurrencyManager::getBaseCurrency(),
                            "TYPE" => "select",
                        );



                        $currencyList = Currency\CurrencyManager::getCurrencyList();

                        if(!empty($currencyList))
                        {

                            foreach ($currencyList as $keyCurency => $valueCurency)
                            {
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["CURRENCY_ID"]["DESCRIPTIONS"][] = $valueCurency;
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["CURRENCY_ID"]["VALUES"][] = $keyCurency;
                            }
                            unset($valueCurency, $keyCurency);

                        }

                        $currencyIterator = CurrencyTable::getList(array(
                            'select' => array('CURRENCY')
                        ));

                        while ($currency = $currencyIterator->fetch())
                        {
                            $currencyFormat = CCurrencyLang::GetFormatDescription($currency['CURRENCY']);
                            $PHOENIX_TEMPLATE_ARRAY['CURRENCIES'][] = array(
                                'CURRENCY' => $currency['CURRENCY'],
                                'FORMAT' => array(
                                    'FORMAT_STRING' => $currencyFormat['FORMAT_STRING'],
                                    'DEC_POINT' => $currencyFormat['DEC_POINT'],
                                    'THOUSANDS_SEP' => $currencyFormat['THOUSANDS_SEP'],
                                    'DECIMALS' => $currencyFormat['DECIMALS'],
                                    'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
                                    'HIDE_ZERO' => $currencyFormat['HIDE_ZERO']
                                )
                            );
                        }



                        $arMesure = array();
                        $res_measure = CCatalogMeasure::getList();

                        while($measure = $res_measure->Fetch()) {
                            $arMesureOptions[$measure["ID"]] = array(
                                "NAME" => "phoenix_measure_".$measure["ID"],
                                "DESCRIPTION" => $measure["MEASURE_TITLE"],
                                "VALUE" => "Y"
                            );
                            $arMesureValues[$measure["ID"]] = Option::get($moduleID, "phoenix_measure_".$measure["ID"], false, $siteId);
                        }
                        
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MEASURE"] = array(
                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_MEASURE_TITLE"),
                            "VALUES" => $arMesureOptions,
                            "VALUE" => $arMesureValues,
                            "TYPE" => "checkbox"
                        );

                        unset($arMesureOptions, $arMesureValues);

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['DESCRIPTION'] = Loc::GetMessage("PHOENIX_CATALOG_OFFER_FIELDS");
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['TYPE'] = "checkbox";
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['SHOW_DESC_ALONE'] = "Y";

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_SKU_FIELDS']['DESCRIPTION'] = Loc::GetMessage("PHOENIX_CATALOG_LIST_SKU_FIELDS");
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_SKU_FIELDS']['TYPE'] = "checkbox";
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_SKU_FIELDS']['SHOW_DESC_ALONE'] = "Y";

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_SKU_FIELDS']['DESCRIPTION'] = Loc::GetMessage("PHOENIX_CATALOG_DETAIL_SKU_FIELDS");
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_SKU_FIELDS']['TYPE'] = "checkbox";
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_SKU_FIELDS']['SHOW_DESC_ALONE'] = "Y";



                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']['DESCRIPTION'] = Loc::GetMessage("PHOENIX_COMPARE_SKU");
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']['TYPE'] = "checkbox";
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']['SHOW_DESC_ALONE'] = "Y";


                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"]>0)
                        {

                            $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "id"=>"asc"), Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"]));


                            $arrSkip = array(
                                    "MORE_PHOTO",
                                    "CML2_LINK",
                                    "ARTICLE",
                                    "PREVIEW_TEXT",
                                    "NO_MERGE_PHOTOS",
                                    "SHORT_DESCRIPTION",
                                    "MODE_DISALLOW_ORDER",
                                    "PREORDER_ONLY",
                                    "MODE_ARCHIVE",
                                    "MODE_HIDE"
                            );
                            
                            while($prop_fields = $properties->GetNext())
                            {

                                if(in_array($prop_fields["CODE"], $arrSkip))
                                    continue;

                                $arItRes = array();

                                $arItRes["NAME"] = "phoenix_offer_field_".$prop_fields["ID"];
                                $arItRes["DESCRIPTION"] = $prop_fields["NAME"];

                                $code = (strlen($prop_fields["CODE"])>0)?$prop_fields["CODE"]:$prop_fields["ID"];
                            

                                $arItRes["VALUE"] = $prop_fields["ID"];
                                $arItRes["VALUE_2"] = Option::get($moduleID, $arItRes["NAME"]."_value_2", false, $siteId);

                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUES'][$prop_fields["ID"]] = $arItRes;
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE'][$prop_fields["ID"]] = Option::get($moduleID, $arItRes["NAME"], false, $siteId);
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['CODE'][$prop_fields["ID"]] = $code;

                    
                                $arItRes["NAME"] = "phoenix_catalog_list_sku_fields_".$prop_fields["ID"];

                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_SKU_FIELDS']['VALUES'][$prop_fields["ID"]] = $arItRes;
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_SKU_FIELDS']['VALUE'][$prop_fields["ID"]] = Option::get($moduleID, $arItRes["NAME"], false, $siteId);
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_SKU_FIELDS']['CODE'][$prop_fields["ID"]] = $code;


                                $arItRes["NAME"] = "phoenix_catalog_detail_sku_fields_".$prop_fields["ID"];

                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_SKU_FIELDS']['VALUES'][$prop_fields["ID"]] = $arItRes;
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_SKU_FIELDS']['VALUE'][$prop_fields["ID"]] = Option::get($moduleID, $arItRes["NAME"], false, $siteId);
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_SKU_FIELDS']['CODE'][$prop_fields["ID"]] = $code;



                                $arItRes["NAME"] = "phoenix_compare_sku_".$prop_fields["ID"];
                                $arItRes["CODE"] = $code;

                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']['VALUES'][$prop_fields["ID"]] = $arItRes;
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']['VALUE'][$prop_fields["ID"]] = Option::get($moduleID, $arItRes["NAME"], false, $siteId);
                                
                            }
                        }

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']['CODE_VALUE'] = array();

                        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']['VALUE']))
                        {

                            foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']['VALUE'] as $value)
                            {
                                if($value)
                                {
                                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']['CODE_VALUE'][] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']['VALUES'][$value]['CODE'];
                                }
                            }
                        }



                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['BIND'] = array(
                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SKU_DESC"),
                            "DESCRIPTIONS" => array(
                                Loc::GetMessage("PHOENIX_SKU_TEXT"),
                                Loc::GetMessage("PHOENIX_SKU_PIC"),
                                Loc::GetMessage("PHOENIX_SKU_PIC_WITH_INFO"),
                                Loc::GetMessage("PHOENIX_SKU_SELECT"),
                            ),

                            "VALUES" => array(
                                "text",
                                "pic",
                                "pic_with_info",
                                "select",
                            ),

                        );

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["WARNINGS"]["PROPS"]["DESCRIPTION"] = Loc::GetMessage("PHOENIX_COMPARE_WARNINGS_PROPS");
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["WARNINGS"]["PROPS"]["TYPE"] = "warning";
                        


                        $PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] = "N";

                        $tmpArr = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE'];

                        if(is_array($tmpArr))
                            $tmpArr = array_diff($tmpArr, array(''));

                        if( !empty($tmpArr) )
                        {
                            foreach ($tmpArr as $key => $value) {
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'][$key] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['CODE'][$value];
                            }
                            unset($tmpArr);
                            $PHOENIX_TEMPLATE_ARRAY["GLOBAL_SKU"] = "Y";

                        }
                        

                        $PHOENIX_TEMPLATE_ARRAY["SHOW_SKU_ITEMS_IN_LIST"] = array();

                        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_SKU_FIELDS']["VALUE"]))
                        {
                            $PHOENIX_TEMPLATE_ARRAY["SHOW_SKU_ITEMS_IN_LIST"] = array_diff($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_SKU_FIELDS']["VALUE"], array(''));

                            if(!empty($PHOENIX_TEMPLATE_ARRAY["SHOW_SKU_ITEMS_IN_LIST"]))
                            {
                                foreach ($PHOENIX_TEMPLATE_ARRAY["SHOW_SKU_ITEMS_IN_LIST"] as $key => $value)
                                {
                                    $PHOENIX_TEMPLATE_ARRAY["SHOW_SKU_ITEMS_IN_LIST"][$key] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_SKU_FIELDS']["CODE"][$key];
                                }
                            }
                        }



                        $PHOENIX_TEMPLATE_ARRAY["SHOW_SKU_ITEMS_IN_DETAIL"] = array();

                        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_SKU_FIELDS']["VALUE"]))
                        {
                            $PHOENIX_TEMPLATE_ARRAY["SHOW_SKU_ITEMS_IN_DETAIL"] = array_diff($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_SKU_FIELDS']["VALUE"], array(''));

                            if(!empty($PHOENIX_TEMPLATE_ARRAY["SHOW_SKU_ITEMS_IN_DETAIL"]))
                            {

                                foreach ($PHOENIX_TEMPLATE_ARRAY["SHOW_SKU_ITEMS_IN_DETAIL"] as $key => $value){
                                    $PHOENIX_TEMPLATE_ARRAY["SHOW_SKU_ITEMS_IN_DETAIL"][$key] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_SKU_FIELDS']["CODE"][$key];
                                }
                            }
                        }

                        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_']))
                        {
                            foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'] as $key => $value){
                                unset($PHOENIX_TEMPLATE_ARRAY["SHOW_SKU_ITEMS_IN_LIST"][$key]);
                                unset($PHOENIX_TEMPLATE_ARRAY["SHOW_SKU_ITEMS_IN_DETAIL"][$key]);
                            }
                        }

                        $arTypePriceOptionsCheckbox = $arTypePriceValuesCheckbox = $arTypePriceOptionsRadio = array();

                        $dbPriceType = CCatalogGroup::GetList();
                        while ($arPriceType = $dbPriceType->Fetch())
                        {

                            $arTypePriceOptionsCheckbox[$arPriceType["ID"]] = array(
                                "NAME" => "phoenix_type_price_".$arPriceType["ID"],
                                "DESCRIPTION" => (strlen($arPriceType["NAME_LANG"])) ? $arPriceType["NAME_LANG"]." (".$arPriceType["NAME"].")" : $arPriceType["NAME"],
                                "VALUE" => "Y",
                                "CODE" => $arPriceType["NAME"],
                                "CAN_ACCESS" => $arPriceType["CAN_ACCESS"],
                                "CAN_BUY" => $arPriceType["CAN_BUY"],
                                "BASE" => $arPriceType["BASE"]
                            );

                            $arTypePriceValuesCheckbox[$arPriceType["ID"]] = Option::get($moduleID, "phoenix_type_price_".$arPriceType["ID"], false, $siteId);

                            $arTypePriceOptionsRadioDesc[] = (strlen($arPriceType["NAME_LANG"])) ? $arPriceType["NAME_LANG"]." (".$arPriceType["NAME"].")" : $arPriceType["NAME"];

                            $arTypePriceOptionsRadioValues[] = $arPriceType["ID"];

                        }

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"] = array(
                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_TYPE_PRICE"),
                            "VALUES" => $arTypePriceOptionsCheckbox,
                            "VALUE" => $arTypePriceValuesCheckbox,
                            "VALUE_" => array(),
                            "VALUES_ID" => array(),
                            "VALUE_CAN_BUY" => array(),
                            "TYPE" => "checkbox",
                                "SHOW_DESC_ALONE"=>"Y",
                        );

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE_SORT"] = array(
                            "NAME" => "phoenix_type_price_sort",
                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_TYPE_PRICE_SORT"),
                            "DESCRIPTIONS" => $arTypePriceOptionsRadioDesc,
                            "VALUES" => $arTypePriceOptionsRadioValues,
                            "VALUE" => Option::get($moduleID, "phoenix_type_price_sort", false, $siteId),
                            "TYPE" => "select",
                        );

                        unset($arTypePriceOptionsCheckbox, $arTypePriceValuesCheckbox, $arTypePriceOptionsRadioDesc, $arTypePriceOptionsRadioValues);
                        

                        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"]["VALUE"]))
                        {

                            $priceSort = 0;

                            foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"]["VALUE"] as $key => $value)
                            {
                                if($value == "Y")
                                {
                                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"]["VALUES"][$key]["CAN_ACCESS"] === "Y")
                                    {
                                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"]["VALUE_"][$key] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"]["VALUES"][$key]["CODE"];
                                        
                                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"]["VALUES_ID"][$key] = $key;
                                    }

                                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"]["VALUES"][$key]["CAN_BUY"] === "Y")
                                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"]["VALUE_CAN_BUY"][$key] = $key;

                                    if($priceSort === 0)
                                        $priceSort = $key;

                                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"]["VALUES"][$key]["BASE"] === "Y")
                                        $priceSort = $key;
                                    
                                }
                                
                            }


                            if(!$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE_SORT"]["VALUE"] || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE_SORT"]["VALUE"] === "N")
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE_SORT"]["VALUE"] = $priceSort;
                        }

                        //CHARS_FIELDS

                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_CHARS_FIELDS']['DESCRIPTION'] = Loc::GetMessage("PHOENIX_CATALOG_LIST_CHARS_FIELDS");
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_CHARS_FIELDS']['TYPE'] = "checkbox";

                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_CHARS_FIELDS']['DESCRIPTION'] = Loc::GetMessage("PHOENIX_CATALOG_DETAIL_CHARS_FIELDS");
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_CHARS_FIELDS']['TYPE'] = "checkbox";

                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']['DESCRIPTION'] = Loc::GetMessage("PHOENIX_COMPARE_PROPS");
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']['TYPE'] = "checkbox";


                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]>0)
                            {

                                $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "id"=>"asc"), Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]));


                                $arrSkip = array(
                                    "ARTICLE","VIDEO_POPUP","BANNERS_LEFT","CHOOSE_LANDING","MODAL_WINDOWS","FORMS","TIT_FOR_QUANTITY","TITLE_FOR_ACTUAL_PRICE","PARENT_ADV","COMMENT_FOR_QUANTITY","RESIZE_IMAGES","CART_BTN_NAME","CART_BTN_NAME_ADDED","COMMENT_HIDE","DONT_SHOW_FORM","PREVIEW_TEXT_POSITION","CART_ADD_ON","BANNERS_LEFT_TYPE","PREVIEW_TEXT","SEND_TEXT","SEND_THEME","QUIZS","ORDER_FORM","CHARS","ITEM_TEMPLATE","LABELS","BRAND","BUTTON_NAME","MENUTITLE_BLOCK1","SHOWMENU_BLOCK1","TITLE_BLOCK2","MENUTITLE_BLOCK2","SHOWMENU_BLOCK2","POSITION_BLOCK2","HIDELINE_BLOCK2","TITLE_BLOCK3","MENUTITLE_BLOCK3","SHOWMENU_BLOCK3","ADVANTAGES","HIDELINE_BLOCK3","POSITION_BLOCK3","POSITION_BLOCK4","TITLE_BLOCK4","MENUTITLE_BLOCK4","SHOWMENU_BLOCK4","HIDELINE_BLOCK4","VIDEO","TITLE_BLOCK5","MENUTITLE_BLOCK5","VIDEO_DESC","SHOWMENU_BLOCK5","POSITION_BLOCK5","HIDELINE_BLOCK5","TITLE_BLOCK6","POSITION_BLOCK6","MENUTITLE_BLOCK6","SHOWMENU_BLOCK6","SIMILAR","HIDELINE_BLOCK6","SPECIALS","BLOGS","NEWS","TITLE_BLOCK7","MENUTITLE_BLOCK7","SHOWMENU_BLOCK7","POSITION_BLOCK7","HIDELINE_BLOCK7","STUFF","POSITION_BLOCK8","FAQ_ELEMENTS","FAQ_PROF","TITLE_BLOCK8","FAQ_NAME","FAQ_BUTTON_NAME","MENUTITLE_BLOCK8","SHOWMENU_BLOCK8","HIDELINE_BLOCK8","FAQ_FORM","POSITION_BLOCK9","TITLE_BLOCK9","MENUTITLE_BLOCK9","FILES_DESC","SHOWMENU_BLOCK9","HIDELINE_BLOCK9","TITLE_BLOCK10","GALLERY_COLS","MENUTITLE_BLOCK10","SHOWMENU_BLOCK10","HIDELINE_BLOCK10","POSITION_BLOCK10","vote_count","rating","vote_sum", "HEADER_PICTURE", "MORE_PHOTO", "FILES", "SEND_FILES", "FAQ_PICTURE", "GALLERY", "MINIMUM_PRICE", "MAXIMUM_PRICE", "SHORT_DESCRIPTION", "PREVIEW_TEXT", "SIMILAR_CATEGORY", "SHOWMENU_BLOCK11", "MENUTITLE_BLOCK11", "HIDELINE_BLOCK11", "TITLE_BLOCK11", "POSITION_BLOCK11", "SHOWBLOCK5", "SIMILAR_SOURCE", "SIMILAR_CATEGORY_LIST", "SIMILAR_MAX_QUANTITY", "POSITION_BLOCK13", "SIMILAR_CATEGORY_INHERIT", "SHOWMENU_BLOCK13", "MENUTITLE_BLOCK13", "TITLE_BLOCK13", "ACCESSORY", "HIDELINE_BLOCK13", "SHOWBLOCK13", "ACCESSORY_SOURCE", "ACCESSORY_CATEGORY_LIST", "ACCESSORY_MAX_QUANTITY", "POSITION_BLOCK12", "TITLE_BLOCK12", "SHOWMENU_BLOCK12", "MENUTITLE_BLOCK12", "HIDELINE_BLOCK12", "DETAIL_TEXT_BLOCK12", "SKROLL_TO_CHARS_TITLE", "SKROLL_TO_CHARS_ON", "GALLERY_ANIMATE", "SIMILAR_CATEGORY_INHERIT", "POSITION_BLOCK14", "BTN_ACTION", "BTN_FORM", "BTN_URL", "BTN_POPUP", "BTN_QUIZ_ID", "BTN_TARGET_BLANK", "BTN_LAND", "BTN_PRODUCT_ID", "BTN_ONCLICK", "BTN_NAME", "BTN_OFFER_ID", "BTN_BG_COLOR", "BTN_VIEW","SCROLL_TO_CHARS_OFF", "SHOWMENU_BLOCK_SET_PRODUCT", "MENUTITLE_BLOCK_SET_PRODUCT", "HIDELINE_BLOCK_SET_PRODUCT", "TITLE_BLOCK_SET_PRODUCT", "POSITION_BLOCK_SET_PRODUCT", "SHOW_BLOCK_SET_PRODUCT", "SHOWMENU_BLOCK_SET_PRODUCT_CONSTRUCTOR", "MENUTITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR", "HIDELINE_BLOCK_SET_PRODUCT_CONSTRUCTOR", "TITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR", "POSITION_BLOCK_SET_PRODUCT_CONSTRUCTOR", "SHOW_BLOCK_SET_PRODUCT_CONSTRUCTOR", "TITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR_OTHER", "EMPL_BANNER","EMPL_BANNER_TYPE", "MODE_DISALLOW_ORDER","PREORDER_ONLY", "MODE_ARCHIVE","MODE_HIDE", "FAQ_HIDE_FIRST_ITEM", "POSITION_BLOCK15", "TITLE_BLOCK15", "MENUTITLE_BLOCK15", "HIDELINE_BLOCK15", "DETAIL_TEXT_BLOCK15", "SHOWMENU_BLOCK15", "REGION", "SEND_AFTER_PAY_THEME", "SEND_AFTER_PAY_FILES", "SEND_AFTER_PAY_TEXT", "AFTER_PAY_TEXT", "SORT_SELF","SIDE_MENU_HTML"
                                );
                                
                                

                                while($prop_fields = $properties->GetNext())
                                {

                                    if(in_array($prop_fields["CODE"], $arrSkip))
                                        continue;


                                    $arItRes = array();
                                    $arItRes["NAME"] = "phoenix_catalog_item_detail_chars_fields_".$prop_fields["ID"];
                                    $arItRes["DESCRIPTION"] = $prop_fields["NAME"];
                                    $arItRes["VALUE"] = $prop_fields["ID"];


                                    if(strlen($prop_fields["CODE"])>0)
                                        $arItRes["CODE"] = $prop_fields["CODE"];

                                    else
                                        $arItRes["CODE"] = $prop_fields["ID"];

                                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_CHARS_FIELDS']['VALUES'][$prop_fields["ID"]] = $arItRes;
                                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_CHARS_FIELDS']['VALUE'][$prop_fields["ID"]] = Option::get($moduleID, $arItRes["NAME"], false, $siteId);

                                    $arItRes["NAME"] = "phoenix_catalog_item_list_chars_fields_".$prop_fields["ID"];

                                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_CHARS_FIELDS']['VALUES'][$prop_fields["ID"]] = $arItRes;
                                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_CHARS_FIELDS']['VALUE'][$prop_fields["ID"]] = Option::get($moduleID, $arItRes["NAME"], false, $siteId);


                                    $arItRes["NAME"] = "phoenix_compare_props_".$prop_fields["ID"];

                                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']['VALUES'][$prop_fields["ID"]] = $arItRes;
                                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']['VALUE'][$prop_fields["ID"]] = Option::get($moduleID, $arItRes["NAME"], false, $siteId);
                                    
                                }

                            }


                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']['CODE_VALUE'] = array();

                            if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']['VALUE']))
                            {

                                foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']['VALUE'] as $value)
                                {
                                    if($value)
                                    {
                                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']['CODE_VALUE'][] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']['VALUES'][$value]['CODE'];
                                        //$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']['CODE_VALUE'][] = $value;
                                    }
                                }
                            }


                            $PHOENIX_TEMPLATE_ARRAY["SHOW_CHARS_ITEMS_IN_DETAIL"] = array();
                            $PHOENIX_TEMPLATE_ARRAY["SHOW_CHARS_ITEMS_IN_LIST"] = array();

                            
                            if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_CHARS_FIELDS']["VALUE"]))
                            {
                                foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_CHARS_FIELDS']["VALUE"] as $value)
                                {
                                    if($value)
                                    {
                                        $PHOENIX_TEMPLATE_ARRAY["SHOW_CHARS_ITEMS_IN_DETAIL"][] = $value;
                                    }
                                }
                            }

                            if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_CHARS_FIELDS']["VALUE"]))
                            {
                                foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_CHARS_FIELDS']["VALUE"] as $value)
                                {
                                    if($value)
                                    {
                                        $PHOENIX_TEMPLATE_ARRAY["SHOW_CHARS_ITEMS_IN_LIST"][] = $value;
                                    }
                                }
                            }
                        //


                        $PHOENIX_TEMPLATE_ARRAY["TERMINATIONS_OFFERS"] = array(
                            $PHOENIX_TEMPLATE_ARRAY["MESS"]["PRODUCT_OFFERS_COUNT_TITLE_1"],
                            $PHOENIX_TEMPLATE_ARRAY["MESS"]["PRODUCT_OFFERS_COUNT_TITLE_2"],
                            $PHOENIX_TEMPLATE_ARRAY["MESS"]["PRODUCT_OFFERS_COUNT_TITLE_3"],
                            $PHOENIX_TEMPLATE_ARRAY["MESS"]["PRODUCT_OFFERS_COUNT_TITLE_4"]

                        );


                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"] = array(
                            "NAME" => Loc::GetMessage("PHOENIX_SET_STORE_NAME"),
                            "ITEMS" => array(
                                "LIST" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_STORE_LIST_DESCRIPTION"),
                                    "VALUES" =>  array(),
                                    "VALUE" => array(),
                                    "PUBLIC_VALUE" => array(),
                                    "TYPE" => "checkbox",
                                ),
                                "SHOW_RESIDUAL" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_STORE_SHOW_RESIDUAL"),
                                    "HINT" => Loc::GetMessage("PHOENIX_SET_STORE_SHOW_RESIDUAL_HINT"),
                                    "NAME" => $name_val = "phoenix_store_show_residual",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_STORE_SHOW_RESIDUAL"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                            )
                        );


                        $dbResult = CCatalogStore::GetList(
                           array('SORT'=>'ASC','ID' => 'ASC'),
                           array('ACTIVE' => 'Y'),
                           false,
                           false,
                           array("ID","TITLE","ACTIVE")
                        );
                        while($arStoreProduct=$dbResult->fetch())
                        {
                            $nameInput = "phoenix_store_list_".$arStoreProduct["ID"];

                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["LIST"]["VALUES"][$arStoreProduct["ID"]] = array(
                                "NAME"=>$nameInput,
                                "DESCRIPTION" => $arStoreProduct["TITLE"],
                                "VALUE"=>"Y"

                            );
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["LIST"]["VALUE"][$arStoreProduct["ID"]] = Option::get($moduleID, $nameInput, false, $siteId);
                        }

                        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["LIST"]["VALUE"]))
                        {
                            foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["LIST"]["VALUE"] as $key => $value)
                            {
                                if($value == "Y")
                                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["LIST"]["PUBLIC_VALUE"][$key] = $key;

                            }
                        }

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["LIST"]["PUBLIC_VALUE"];



                    }
                }


                elseif($valueParams == "shop" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"] = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_SHOP_NAME"),

                        "NAME_ORDER" => Loc::GetMessage("PHOENIX_ADMIN_SHOP_NAME_ORDER"),

                        "NAME_FAST_ORDER" => Loc::GetMessage("PHOENIX_ADMIN_SHOP_NAME_FAST_ORDER"),

                        "ITEMS" => array(

                            "SHOW_DELIVERY_PARENT_NAMES" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_SHOW_DELIVERY_PARENT_NAMES"),
                                "NAME" => $name_val = "phoenix_show_delivery_parent_names",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_SHOW_DELIVERY_PARENT_NAMES"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "GIFTS_ON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_GIFTS_ON"),
                                "NAME" => $name_val = "phoenix_gifts_on",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_GIFTS_ON"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "CART_ON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_ON"),
                                "NAME" => $name_val = "phoenix_cart_on",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_ON"),
                                        "VALUE" => "Y",
                                        "SHOW_OPTIONS" => "cart_on"
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),


                            "PAY_FROM_ACCOUNT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PAY_FROM_ACCOUNT"),
                                "NAME" => $name_val = "phoenix_pay_from_account",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_PAY_FROM_ACCOUNT"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "ONLY_FULL_PAY_FROM_ACCOUNT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ONLY_FULL_PAY_FROM_ACCOUNT"),
                                "NAME" => $name_val = "phoenix_only_pay_from_account",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_ONLY_FULL_PAY_FROM_ACCOUNT"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "DELIVERY_TO_PAYSYSTEM" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_DELIVERY_TO_PAYSYSTEM"),
                                "NAME" => $name_val = "phoenix_delivery_to_paysystem",
                                "VALUES" =>  array(
                                    "D2P" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_DELIVERY_TO_PAYSYSTEM_D2P"),
                                        "VALUE" => "d2p"
                                    ),
                                    "P2D" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_DELIVERY_TO_PAYSYSTEM_P2D"),
                                        "VALUE" => "p2d"
                                    )
                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "d2p",
                                "TYPE" => "radio",
                            ),


                            "TEMPLATE_LOCATION" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_TEMPLATE_LOCATION"),
                                "NAME" => $name_val = "phoenix_template_location",
                                "VALUES" =>  array(
                                    "POPUP" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_TEMPLATE_LOCATION_POPUP"),
                                        "VALUE" => "popup"
                                    ),
                                    "DEF" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_TEMPLATE_LOCATION_DEF"),
                                        "VALUE" => ".default"
                                    )
                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "popup",
                                "TYPE" => "radio",
                            ),


                            "BASKET_POSITION" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BASKET_POSITION"),
                                "NAME" => $name_val = "phoenix_basket_position",
                                "VALUES" =>  array(
                                    "HIDE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_BASKET_POSITION_HIDE"),
                                        "VALUE" => "HIDE"
                                    ),
                                    "BEFORE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_BASKET_POSITION_BEFORE"),
                                        "VALUE" => "before"
                                    ),
                                    "AFTER" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_BASKET_POSITION_AFTER"),
                                        "VALUE" => "after"
                                    )
                                    
                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "after",
                                "TYPE" => "radio",
                            ),



                            "CART_MINICART_MODE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_MINICART_MODE"),
                                "NAME" => $name_val = "phoenix_cart_minicart_mode",
                                "VALUES" =>  array(
                                    "SEMI_SHOW" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_MINICART_MODE_SEMI_SHOW"),
                                        "VALUE" => "semi_show"
                                    ),
                                    "SHOW" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_MINICART_MODE_SHOW"),
                                        "VALUE" => "show"
                                    )
                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "show",
                                "TYPE" => "radio",
                                "IN_PUBLIC" => array(
                                    "CLASS_UL" => "edit-style"
                                ),
                            ),

                            "CART_MINICART_LINK_PAGE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_MINICART_LINK_PAGE"),
                                "NAME" => $name_val = "phoenix_cart_minicart_link_page",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "HINT" => Loc::GetMessage("PHOENIX_CART_MINICART_PAGE_LINK_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_MINICART_DESC_EMPTY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_MINICART_DESC_EMPTY"),
                                "NAME" => $name_val = "phoenix_cart_minicart_desc_empty",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" =>  htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_MINICART_DESC_NOEMPTY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_MINICART_DESC_NOEMPTY"),
                                "NAME" => $name_val = "phoenix_cart_minicart_desc_noempty",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" =>  htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_HEAD_BG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_HEAD_BG"),
                                "NAME" => $name_val = "img_phoenix_cart_head_bg",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "CART_HEAD_TIT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_HEAD_TIT"),
                                "NAME" => $name_val = "phoenix_cart_head_tit",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_BTN_ADD_NAME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_BTN_ADD_NAME"),
                                "NAME" => $name_val = "phoenix_cart_btn_add_name",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_ADD_TO_CART"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_BTN_ADDED_NAME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_BTN_ADDED_NAME"),
                                "NAME" => $name_val = "phoenix_cart_btn_added_name",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_ADDED_TO_CART"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_BTN_ORDER_NAME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_BTN_ORDER_NAME"),
                                "NAME" => $name_val = "phoenix_cart_btn_order_name",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CART_BTN_ORDER_NAME_DEF"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            /*"CART_BTN_PRE_ORDER_NAME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_BTN_PRE_ORDER_NAME"),
                                "NAME" => $name_val = "phoenix_cart_btn_pre_order_name",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CART_BTN_PRE_ORDER_NAME_DEFAULT"),
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),*/

                            "CART_COMMENT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_COMMENT"),
                                "NAME" => $name_val = "phoenix_cart_comment",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_MESS_THEME_ADMIN" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_MESS_THEME_ADMIN"),
                                "NAME" => $name_val = "phoenix_cart_mess_theme_admin",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CART_MESS_THEME_ADMIN_DEF"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_MESS_ADMIN" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_MESS_ADMIN"),
                                "NAME" => $name_val = "phoenix_cart_mess_admin",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CART_MESS_ADMIN_DEF"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                    "TYPE" => "textarea",
                                    "SIZE_HEIGHT" => "middle",
                                )
                            ),

                            "CART_MESS_THEME_USER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_MESS_THEME_USER"),
                                "NAME" => $name_val = "phoenix_cart_mess_theme_user",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CART_MESS_THEME_USER_DEF"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_MESS_USER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_MESS_USER"),
                                "NAME" => $name_val = "phoenix_cart_mess_user",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" =>  htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CART_MESS_USER_DEF"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                    "TYPE" => "textarea",
                                    "SIZE_HEIGHT" => "middle",
                                )
                            ),

                            "CART_LINK_CONDITIONS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_LINK_CONDITIONS"),
                                "NAME" => $name_val = "phoenix_cart_link_conditions",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "HINT" => Loc::GetMessage("PHOENIX_CART_LINK_CONDITIONS_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_BTN_NAME_CONDITIONS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_BTN_NAME_CONDITIONS"),
                                "NAME" => $name_val = "phoenix_cart_btn_name_conditions",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" =>  htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_ADD_ON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_ADD_ON"),
                                "NAME" => $name_val = "phoenix_cart_add_on",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_ADD_ON"),
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "CART_PAGE_TITLE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_PAGE_TITLE"),
                                "NAME" => $name_val = "phoenix_cart_page_title",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_PAGE_HEADBG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CARTPAGE_HEADBG"),
                                "NAME" => $name_val = "img_phoenix_cart_page_bg",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "CART_PAGE_EMPTY_MESS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_PAGE_EMPTY_MESS"),
                                "NAME" => $name_val = "phoenix_cart_page_empty_mess",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CART_PAGE_EMPTY_MESS_DEF"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea"
                                )
                            ),

                            /*"CART_PAGE_ORDER_COMPLITED_MESS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CARTPAGE_ORDER_COMPLITED_MESS"),
                                "NAME" => $name_val = "phoenix_cart_page_order_complited_mess",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CARTPAGE_ORDER_COMPLITED_MESS_DEF"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea"
                                )
                            ),*/

                            "FAST_ORDER_COMPLITED_MESS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FAST_ORDER_COMPLITED_MESS"),
                                "NAME" => $name_val = "phoenix_fast_order_complited_mess",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_FAST_ORDER_COMPLITED_MESS_DEF"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea"
                                )
                            ),

                            "ORDER_COMPLITED_MESS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ORDER_COMPLITED_MESS"),
                                "NAME" => $name_val = "phoenix_order_complited_mess",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_ORDER_COMPLITED_MESS_DEF"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea"
                                )
                            ),

                            "BASKET_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BASKET_URL"),
                                "NAME" => $name_val = "phoenix_basket_url",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => "/basket/",
                                "ROWS" => $small_size_row_vis_text,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "CART_MIN_SUM" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_MIN_SUM"),
                                "NAME" => $name_val = "phoenix_cart_min_sum",
                                "DEFAULT" => 0,
                                "VALUE" => self::getNumberFromStringWithType(Option::get($moduleID, $name_val, false, $siteId)),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),


                            "FAST_ORDER_IN_BASKET_ON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FAST_ORDER_IN_BASKET_ON"),
                                "NAME" => $name_val = "phoenix_cart_fast_order_in_basket_on",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FAST_ORDER_IN_BASKET_ON"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "FAST_ORDER_IN_BASKET_ONLY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FAST_ORDER_IN_BASKET_ONLY"),
                                "HINT" => Loc::GetMessage("PHOENIX_FAST_ORDER_IN_BASKET_ONLY_HINT"),
                                "NAME" => $name_val = "phoenix_cart_fast_order_in_basket_only",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FAST_ORDER_IN_BASKET_ONLY"),
                                        "HINT" => Loc::GetMessage("PHOENIX_FAST_ORDER_IN_BASKET_ONLY_HINT"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "FAST_ORDER_IN_PRODUCT_ON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FAST_ORDER_IN_PRODUCT_ON"),
                                "NAME" => $name_val = "phoenix_cart_fast_order_in_order_on",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FAST_ORDER_IN_PRODUCT_ON"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "CART_BTN_FAST_ORDER_NAME_IN_BASKET" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_BTN_FAST_ORDER_NAME_IN_BASKET"),
                                "NAME" => $name_val = "phoenix_cart_btn_fast_order_name_in_basket",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "DEFAULT" => Loc::GetMessage("PHOENIX_FAST_ORDER"),
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CART_BTN_FAST_ORDER_NAME_IN_CATALOG_DETAIL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_BTN_FAST_ORDER_NAME_IN_CATALOG_DETAIL"),
                                "NAME" => $name_val = "phoenix_cart_btn_fast_order_name_in_ctl_detail",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "DEFAULT" => Loc::GetMessage("PHOENIX_FAST_ORDER"),
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "ORDER_FORM_MINPICE_ALERT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ORDER_FORM_MINPICE_ALERT"),
                                "NAME" => $name_val = "phoenix_shop_form_minprice_alert",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "FAST_ORDER_FORM_SUBTITLE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FAST_ORDER_FORM_SUBTITLE"),
                                "NAME" => $name_val = "phoenix_shop_fast_order_form_subtitle",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "ORDER_PAGES" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_ORDER_PAGES"),
                                "NAME" => $name_val = "phoenix_shop_order_pages",
                                "VALUES" => array(

                                    "DARK" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_ORDER_PAGES_ONE"),
                                        "VALUE" => "one"
                                    ),

                                    "LIGHT" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_ORDER_PAGES_TWO"),
                                        "VALUE" => "two"

                                    ),

                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "two",
                                "TYPE" => "radio",
                            ),

                            "TEMPLATE_BASKET" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_ORDER_TEMPLATE_BASKET"),
                                "NAME" => $name_val = "phoenix_shop_order_template_basket",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "TEMPLATE_ORDER_PAGE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_ORDER_TEMPLATE_ORDER_PAGE"),
                                "NAME" => $name_val = "phoenix_shop_order_template_order_page",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),
                            
                            "COUPON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_COUPON"),
                                "NAME" => $name_val = "phoenix_shop_coupon",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_COUPON"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "BASKET_FILTER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_BASKET_FILTER"),
                                "NAME" => $name_val = "phoenix_shop_basket_filter",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_BASKET_FILTER"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "SHOW_ZIP_INPUT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_SHOW_ZIP_INPUT"),
                                "NAME" => $name_val = "phoenix_show_zip_input",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_SHOW_ZIP_INPUT"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),
                            "ALLOW_NEW_PROFILE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_ALLOW_NEW_PROFILE"),
                                "NAME" => $name_val = "phoenix_shop_allow_new_profile",
                                "VALUES" =>  array(

                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_ALLOW_NEW_PROFILE"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                              ),
                            "ALLOW_USER_PROFILES" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_ALLOW_USER_PROFILES"),
                                "NAME" => $name_val = "phoenix_shop_allow_user_profiles",
                                "VALUES" =>  array(

                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SHOP_ALLOW_USER_PROFILES"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                              ),

                        ),


                    );


                    
                    


                    
                    if(\Bitrix\Main\Loader::includeModule("iblock") && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ADVS']["IBLOCK_ID"]>0)
                    {

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ADVS']['DESCRIPTION'] = Loc::GetMessage("PHOENIX_CART_ADVS_TITLE");
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ADVS']['HINT'] = Loc::GetMessage("PHOENIX_CART_ADVS_TITLE_HINT");
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ADVS']['TYPE'] = "checkbox";

                        $arFilter = array();
                        $arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ADVS']["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
                        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, array("ID","NAME"));


                        while($ob = $res->GetNextElement()){ 
                            $arFields = $ob->GetFields();
                            

                            $arItRes = array();
                            $arItRes["NAME"] = "phoenix_cart_advs".$arFields['ID'];
                            $arItRes["DESCRIPTION"] = $arFields['NAME'];
                            $arItRes["VALUE"] = $arFields['ID'];


                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ADVS']['VALUES'][$arFields['ID']] = $arItRes;
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ADVS']['VALUE'][$arFields['ID']] = Option::get($moduleID, $arItRes["NAME"], false, $siteId);
                        }

                    }



                    if(\Bitrix\Main\Loader::includeModule("sale"))
                    {


                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE'] = array(
                            "NAME" => $name_val = "phoenix_personal_type",
                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_PERSON_TYPE_TITLE"),
                            "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                            "DEFAULT" =>  "N",
                            "TYPE" => "select",
                            "ATTR" => "class = 'show-option' style='width: 250px;'",
                            "CLASS_SELECT" => "btn-block-show"
                        );

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"] = str_replace("person_type_","", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["VALUE"]);


                        $db_ptype = CSalePersonType::GetList(Array("SORT" => "ASC"), Array("LID"=>$siteId, "ACTIVE"=>"Y"));

                        
                        while ($ptype = $db_ptype->Fetch())
                        {
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["DESCRIPTIONS"][$ptype['ID']] = $ptype['NAME'];
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["VALUES"][$ptype['ID']] = "person_type_".$ptype['ID'];

                            if(!strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["VALUE"]))
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["VALUE"] = "person_type_".$ptype['ID'];


                            
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$ptype["ID"]]["DESCRIPTION"] = Loc::GetMessage("PHOENIX_CART_FAST_ORDER_INPUTS");
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$ptype["ID"]]['TYPE'] = "checkbox";

                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$ptype["ID"]]['IN_PUBLIC'] = array(
                                "CLASS_UL"=>"d-none block-show person_type_".$ptype['ID']
                            );

                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$ptype["ID"]]['TR_CLASS'] = "content-options";
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$ptype["ID"]]['TR_ATTRS'] = "data-option-name = 'phoenix_personal_type' data-content-options = 'person_type_".$ptype['ID']."'";
                            


                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$ptype["ID"]]["DESCRIPTION"] = Loc::GetMessage("PHOENIX_CART_FAST_ORDER_INPUTS_REQ");
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$ptype["ID"]]['TYPE'] = "checkbox";

                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$ptype["ID"]]['IN_PUBLIC'] = array(
                                "CLASS_UL"=>"d-none block-show person_type_".$ptype['ID']
                            );

                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$ptype["ID"]]['TR_CLASS'] = "content-options";
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$ptype["ID"]]['TR_ATTRS'] = "data-option-name = 'phoenix_personal_type' data-content-options = 'person_type_".$ptype['ID']."'";


                        }

                        $arSelectProps = array('ID', 'CODE', 'NAME', 'PERSON_TYPE_ID', 'TYPE', 'IS_PHONE', 'IS_EMAIL', 'IS_PAYER', 'REQUIED', 'DEFAULT_VALUE', 'DESCRIPTION');

                        $dbRes = CSaleOrderProps::GetList(array('SORT' => 'ASC'), array('ACTIVE' => 'Y'), false, false, $arSelectProps);

                        while($arItem = $dbRes->Fetch())
                        {
                            $arItRes = array();

                            if($arItem['TYPE'] === 'LOCATION')
                                continue;

                            $arItRes["NAME"] = "phoenix_personal_props_".$arItem['ID'];
                            $arItRes["DESCRIPTION"] = $arItem['NAME'];
                            $arItRes["DEFAULT_VALUE"] = $arItem['DEFAULT_VALUE'];
                            $arItRes["VALUE"] = "Y";

                            if(strlen($arItem["CODE"])<=0)
                                $arItem["CODE"]=$arItem["ID"];


                            if($arItem['TYPE'] === 'SELECT')
                            {
                                $arItRes["VARIANTS"] = array();
                                $dbVariants = CSaleOrderPropsVariant::GetList(
                                        array("SORT" => "ASC"),
                                        array("ORDER_PROPS_ID" => $arItem["ID"]),
                                        false,
                                        false,
                                        array("*")
                                    );
                                while ($arVariants = $dbVariants->GetNext())
                                {
                                    $arItRes["VARIANTS"][] = $arVariants;
                                }

                            }

                            $arItRes["PROPS"] = $arItem;
                            $arItRes["INPUT_CLASS"] = "checkbox_inp";


                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$arItem["PERSON_TYPE_ID"]]['VALUES'][$arItem['ID']] = $arItRes;
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$arItem["PERSON_TYPE_ID"]]['VALUE'][$arItem['ID']] = Option::get($moduleID, $arItRes["NAME"], false, $siteId);



                            $arItRes["NAME"] = "phoenix_personal_props_r_".$arItem['ID'];
                            $arItRes["INPUT_CLASS"] = "checkbox_req";

                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$arItem["PERSON_TYPE_ID"]]['VALUES'][$arItem['ID']] = $arItRes;
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$arItem["PERSON_TYPE_ID"]]['VALUE'][$arItem['ID']] = Option::get($moduleID, $arItRes["NAME"], false, $siteId);
                            
                        }

                       

                        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS']))
                        {
                            foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'] as $key => $arItem){


                                if(empty($arItem["VALUES"]))
                                {
                                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$key]["VALUE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$key]["VALUES"] = array();
                                }

                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$key]["VALUES"] = array_merge(
                                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$key]["VALUES"], 
                                        array(

                                            "COMMENT" => array(
                                                "NAME" => "phoenix_personal_props_com_".$key,
                                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_FAST_ORDER_INPUTS_COMMENT"),
                                                "VALUE" => "Y",
                                                "INPUT_CLASS" => "checkbox_inp",
                                                "PROPS" => array(
                                                    "TYPE" => "TEXTAREA",
                                                    "CODE" => "USER_DESCRIPTION"
                                                )
                                            )

                                        )
                                );

                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$key]["VALUE"] = array_merge(
                                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$key]["VALUE"], 
                                        array(
                                            "COMMENT" => Option::get($moduleID, "phoenix_personal_props_com_".$key, false, $siteId)
                                        )
                                );

                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$key]["VALUES"] = array_merge(
                                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$key]["VALUES"], 
                                        array(

                                            "COMMENT" => array(
                                                "NAME" => "phoenix_personal_props_r_com_".$key,
                                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_FAST_ORDER_INPUTS_COMMENT"),
                                                "VALUE" => "Y",
                                                "INPUT_CLASS" => "checkbox_req"
                                            )

                                        )
                                );

                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$key]["VALUE"] = array_merge(
                                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$key]["VALUE"], 
                                        array(
                                            "COMMENT" => Option::get($moduleID, "phoenix_personal_props_r_com_".$key, false, $siteId)
                                        )
                                );
                            }
                        }

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['DELIVERY'] = array(
                            "NAME" => $name_val = "phoenix_delivery",
                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_DELIVERY_TITLE"),
                            "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                            "DEFAULT" =>  "N",
                            "TYPE" => "select",
                            "ATTR" => "class = 'show-option' style='width: 250px;'"
                        );
                        

                        $arDelivery = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();

                        $arWithParentId = array();

                        if(!empty($arDelivery))
                        {
                            foreach ($arDelivery as $key => $arItem){
                                if(intval($arItem["PARENT_ID"])>0)
                                    $arWithParentId[$arItem["PARENT_ID"]] = $arItem["PARENT_ID"];
                            }
                        }

                        if(!empty($arWithParentId))
                        {
                            foreach ($arWithParentId as $key => $arItem){
                                unset($arDelivery[$key]);
                            }
                        }

                        if(!empty($arDelivery))
                        {
                            foreach ($arDelivery as $key => $arItem){
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['DELIVERY']["DESCRIPTIONS"][$arItem['ID']] = $arItem['NAME'];
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['DELIVERY']["VALUES"][$arItem['ID']] = $arItem['ID'];

                                if(!strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['DELIVERY']["VALUE"]))
                                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['DELIVERY']["VALUE"] = $arItem['ID'];
                            }
                        }



                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PAY_SYSTEM'] = array(
                            "NAME" => $name_val = "phoenix_pay_system",
                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_PAY_SYSTEM_TITLE"),
                            "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                            "DEFAULT" =>  "N",
                            "TYPE" => "select",
                            "ATTR" => "class = 'show-option' style='width: 250px;'"
                        );


                        $dbRes = CSalePaySystem::GetList(array('SORT' => 'ASC'), array('ACTIVE' => 'Y'), false, false, array());
                        while($arItem = $dbRes->Fetch())
                        {
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PAY_SYSTEM']["DESCRIPTIONS"][$arItem['ID']] = $arItem['NAME'];
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PAY_SYSTEM']["VALUES"][$arItem['ID']] = $arItem['ID'];

                            if(!strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PAY_SYSTEM']["VALUE"]))
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PAY_SYSTEM']["VALUE"] = $arItem['ID'];
                        }


                        unset($db_ptype, $ptype, $dbRes);
                    }

                }


                elseif($valueParams == "blog" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["NAME"]))
                {   
                    $tmpArr = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_BLOG_NAME"),

                        "ITEMS" => array(

                            "SIDE_MENU_HTML" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_BLOG_SIDE_MENU_HTML"),
                                "NAME" => $name_val = "phoenix_blog_side_menu_html",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowMD,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea"
                                )
                            ),

                            "CUSTOM_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_BLOG_CUSTOM_URL"),
                                "NAME" => $name_val = "phoenix_blog_custom_url",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "NEWS_COUNT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_BLOG_COUNT_ELEMENTS"),
                                "NAME" => $name_val = "phoenix_blog_count_elements",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "DEFAULT" => "24",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "PIC_IN_HEAD" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLOG_PIC_IN_HEAD"),
                                "NAME" => $name_val = "img_phoenix_blog_pic_in_head",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),


                            "MORE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLG_MORE"),
                                "NAME" => $name_val = "phoenix_blg_more",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_BLG_MORE_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text"
                            ),

                            "BG_PIC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLOG_BG_PIC"),
                                "NAME" => $name_val = "img_phoenix_blog_bg_pic",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "DESC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLOG_DESC"),
                                "NAME" => $name_val = "phoenix_blog_desc",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_BLOG_DESC_HINT"),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "TOP_TEXT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLOG_TOP_TEXT"),
                                "NAME" => $name_val = "phoenix_blog_top_text",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_BLOG_TOP_TEXT_HINT"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "BOT_TEXT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLOG_BOT_TEXT"),
                                "NAME" => $name_val = "phoenix_blog_bot_text",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_BLOG_BOT_TEXT_HINT"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "BOT_TEXT_POS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLOG_BOT_TEXT_POS"),
                                "NAME" => $name_val = "phoenix_blg_bot_text_pos",
                                "DESCRIPTIONS" => array(
                                    Loc::GetMessage("PHOENIX_BLOG_BOT_TEXT_POS_SHORT"),
                                    Loc::GetMessage("PHOENIX_BLOG_BOT_TEXT_POS_FULL"),

                                ),

                                "VALUES" => array(
                                    "phoenix_short",
                                    "phoenix_long",
                                ),

                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "TYPE" => "select"
                            ),

                            "CATEGORY_BLOG_ICON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLOG_CATEGORY_BLOG_ICON"),
                                "NAME" => $name_val = "img_phoenix_blog_category_blog_icon",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SRC" => "",
                                "TYPE" => "image"
                            ),

                            "CATEGORY_VIDEO_ICON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLOG_CATEGORY_VIDEO_ICON"),
                                "NAME" => $name_val = "img_phoenix_blog_category_video_icon",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SRC" => "",
                                "TYPE" => "image"
                            ),

                            "CATEGORY_INTERVIEW_ICON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLOG_CATEGORY_INTERVIEW_ICON"),
                                "NAME" => $name_val = "img_phoenix_blog_category_interview_icon",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SRC" => "",
                                "TYPE" => "image"
                            ),

                            "CATEGORY_OPINION_ICON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLOG_CATEGORY_OPINION_ICON"),
                                "NAME" => $name_val = "img_phoenix_blog_category_opinion_icon",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SRC" => "",
                                "TYPE" => "image"
                            ),

                            "CATEGORY_CASE_ICON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLOG_CATEGORY_CASE_ICON"),
                                "NAME" => $name_val = "img_phoenix_blog_category_case_icon",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SRC" => "",
                                "TYPE" => "image"
                            ),

                            "CATEGORY_SENS_ICON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BLOG_CATEGORY_SENS_ICON"),
                                "NAME" => $name_val = "img_phoenix_blog_category_sens_icon",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SRC" => "",
                                "TYPE" => "image"
                            ),

                            "CATEGORY_BLOG_NAME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATEGORY_BLOG_NAME"),
                                "NAME" => $name_val = "phoenix_blog_category_blog_name",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CATEGORY_BLOG_NAME_DEF"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "CATEGORY_VIDEO_NAME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATEGORY_VIDEO_NAME"),
                                "NAME" => $name_val = "phoenix_blog_category_video_name",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CATEGORY_VIDEO_NAME_DEF"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "CATEGORY_INTERVIEW_NAME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATEGORY_INTERVIEW_NAME"),
                                "NAME" => $name_val = "phoenix_blog_category_interview_name",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CATEGORY_INTERVIEW_NAME_DEF"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),
                            
                            "CATEGORY_OPINION_NAME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATEGORY_OPINION_NAME"),
                                "NAME" => $name_val = "phoenix_blog_category_opinion_name",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CATEGORY_OPINION_NAME_DEF"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),
                            "CATEGORY_CASE_NAME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATEGORY_CASE_NAME"),
                                "NAME" => $name_val = "phoenix_blog_category_case_name",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CATEGORY_CASE_NAME_DEF"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),
                            "CATEGORY_SENS_NAME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATEGORY_SENS_NAME"),
                                "NAME" => $name_val = "phoenix_blog_category_sens_name",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_CATEGORY_SENS_NAME_DEF"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),


                        ),

                    );

                    if(empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]))
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"] = array();

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"] = array_merge($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"], $tmpArr);

                    unset($tmpArr);


                    $arResizeImgTmp = array();
                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_BLOG_ICON"]["VALUE"])){
                        $arResizeImgTmp = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_BLOG_ICON"]["VALUE"], array('width'=>60, 'height'=>60), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_BLOG_ICON"]["SRC"] = $arResizeImgTmp['src'];
                    }
                        
                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_VIDEO_ICON"]["VALUE"])){
                        $arResizeImgTmp = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_VIDEO_ICON"]["VALUE"], array('width'=>60, 'height'=>60), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_VIDEO_ICON"]["SRC"] = $arResizeImgTmp['src'];
                    }


                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_INTERVIEW_ICON"]["VALUE"])){
                        $arResizeImgTmp = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_INTERVIEW_ICON"]["VALUE"], array('width'=>60, 'height'=>60), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_INTERVIEW_ICON"]["SRC"] = $arResizeImgTmp['src'];
                    }


                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_OPINION_ICON"]["VALUE"])){
                        $arResizeImgTmp = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_OPINION_ICON"]["VALUE"], array('width'=>60, 'height'=>60), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_OPINION_ICON"]["SRC"] = $arResizeImgTmp['src'];
                    }


                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_CASE_ICON"]["VALUE"])){
                        $arResizeImgTmp = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_CASE_ICON"]["VALUE"], array('width'=>60, 'height'=>60), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_CASE_ICON"]["SRC"] = $arResizeImgTmp['src'];
                    }


                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_SENS_ICON"]["VALUE"])){
                        $arResizeImgTmp = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_SENS_ICON"]["VALUE"], array('width'=>60, 'height'=>60), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_SENS_ICON"]["SRC"] = $arResizeImgTmp['src'];
                    }



                    
                }

                elseif($valueParams == "news" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["NAME"]))
                {   
                    $tmpArr = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_NEWS_NAME"),

                        "ITEMS" => array(

                            "SIDE_MENU_HTML" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_NEWS_SIDE_MENU_HTML"),
                                "NAME" => $name_val = "phoenix_news_side_menu_html",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowMD,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea"
                                )
                            ),

                            "CUSTOM_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_NEWS_CUSTOM_URL"),
                                "NAME" => $name_val = "phoenix_news_custom_url",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),


                            "NEWS_COUNT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_NEWS_COUNT_ELEMENTS"),
                                "NAME" => $name_val = "phoenix_news_count_elements",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "DEFAULT" => "24",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "PIC_IN_HEAD" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_NEWS_PIC_IN_HEAD"),
                                "NAME" => $name_val = "img_phoenix_news_pic_in_head",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "MORE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_NEWS_MORE"),
                                "NAME" => $name_val = "phoenix_news_more",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_NEWS_MORE_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text"
                            ),

                            "BG_PIC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_NEWS_BG_PIC"),
                                "NAME" => $name_val = "img_phoenix_news_bg_pic",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "DESC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_NEWS_DESC"),
                                "NAME" => $name_val = "phoenix_news_desc",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_NEWS_DESC_HINT"),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "TOP_TEXT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_NEWS_TOP_TEXT"),
                                "NAME" => $name_val = "phoenix_news_top_text",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_NEWS_TOP_TEXT_HINT"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "BOT_TEXT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_NEWS_BOT_TEXT"),
                                "NAME" => $name_val = "phoenix_news_bot_text",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_NEWS_BOT_TEXT_HINT"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "BOT_TEXT_POS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_NEWS_BOT_TEXT_POS"),
                                "NAME" => $name_val = "phoenix_news_bot_text_pos",


                                "DESCRIPTIONS" => array(
                                    Loc::GetMessage("PHOENIX_NEWS_BOT_TEXT_POS_SHORT"),
                                    Loc::GetMessage("PHOENIX_NEWS_BOT_TEXT_POS_FULL"),
                                ),

                                "VALUES" => array(
                                    "phoenix_short",
                                    "phoenix_long",
                                ),

                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "TYPE" => "select"
                            ),

                        ),

                    );

                    if(empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]))
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"] = array();

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"] = array_merge($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"], $tmpArr);

                    unset($tmpArr);
                }


                elseif($valueParams == "actions" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["NAME"]))
                {   

                    $tmpArr = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_ACTION_NAME"),

                        "ITEMS" => array(
                            "SIDE_MENU_HTML" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_OFFERS_SIDE_MENU_HTML"),
                                "NAME" => $name_val = "phoenix_offers_side_menu_html",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowMD,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea"
                                )
                            ),
                            "CUSTOM_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_OFFERS_CUSTOM_URL"),
                                "NAME" => $name_val = "phoenix_offers_custom_url",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "NEWS_COUNT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_OFFERS_COUNT_ELEMENTS"),
                                "NAME" => $name_val = "phoenix_offers_count_elements",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "DEFAULT" => "24",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "PIC_IN_HEAD" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ACT_PIC_IN_HEAD"),
                                "NAME" => $name_val = "img_phoenix_act_pic_in_head",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "MORE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ACT_MORE"),
                                "NAME" => $name_val = "phoenix_act_more",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_ACT_MORE_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text"
                            ),

                            "BG_PIC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ACTION_BG_PIC"),
                                "NAME" => $name_val = "img_phoenix_action_bg_pic",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "DESC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ACTION_DESC"),
                                "NAME" => $name_val = "phoenix_action_desc",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_ACTION_DESC_HINT"),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "TOP_TEXT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ACTION_TOP_TEXT"),
                                "NAME" => $name_val = "phoenix_action_top_text",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_ACTION_TOP_TEXT_HINT"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "BOT_TEXT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ACTION_BOT_TEXT"),
                                "NAME" => $name_val = "phoenix_action_bot_text",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_ACTION_BOT_TEXT_HINT"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),
                            
                            "BOT_TEXT_POS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ACTION_BOT_TEXT_POS"),
                                "NAME" => $name_val = "phoenix_act_bot_text_pos",

                                "DESCRIPTIONS" => array(
                                    Loc::GetMessage("PHOENIX_ACTION_BOT_TEXT_POS_SHORT"),
                                    Loc::GetMessage("PHOENIX_ACTION_BOT_TEXT_POS_FULL"),
                                ),

                                "VALUES" => array(
                                    "phoenix_short",
                                    "phoenix_long",
                                ),
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "TYPE" => "select"
                            ),

                        ),


                    );

                    if(empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]))
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"] = array();

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"] = array_merge($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"], $tmpArr);

                    unset($tmpArr);
                }

                elseif($valueParams == "lids" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"] = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_LIDS_NAME"),

                        "ITEMS" => array(

                            "EMAIL_TO" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MAIL_TO"),
                                "NAME" => $name_val = "phoenix_mail_to",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "HINT" => Loc::GetMessage("PHOENIX_MAIL_TO_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "EMAIL_FROM" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MAIL_FROM"),
                                "NAME" => $name_val = "phoenix_mail_from",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "HINT" => Loc::GetMessage("PHOENIX_MAIL_FROM_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "SAVE_IN_IB" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SAVE_IN_IB"),
                                "NAME" => $name_val = "phoenix_save_in_ib",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SAVE_IN_IB"),
                                        "VALUE" => "Y",
                                        "HINT" => Loc::GetMessage("PHOENIX_SAVE_IN_IB_HINT")
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_SAVE_IN_IB_HINT"),
                                "TYPE" => "checkbox",
                            ),

                            "BX_ON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BX_ON"),
                                "NAME" => $name_val = "phoenix_bx_on",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_BX_ON"),
                                        "VALUE" => "Y",
                                        "HINT" => Loc::GetMessage("PHOENIX_BX_ON_HINT"),
                                        "SHOW_OPTIONS" => "bx_options"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_BX_ON_HINT"),
                                "TYPE" => "checkbox",
                            ),

                            "BX_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BX_URL"),
                                "NAME" => $name_val = "phoenix_url",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_BX_URL_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "BX_LOG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BX_LOG"),
                                "NAME" => $name_val = "phoenix_login",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_BX_LOG_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "BX_PAS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BX_PAS"),
                                "NAME" => $name_val = "phoenix_password",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "BX_ASSIGNED_BY_ID" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BX_ASSIGNED_BY_ID"),
                                "NAME" => $name_val = "phoenix_bx_assigned_by_id",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_BX_ASSIGNED_BY_ID_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "BX_ID" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_BX_ID"),
                                "NAME" => $name_val = "phoenix_bx_id",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "BX_WEB_HOOK" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_BX_WEB_HOOK"),
                                "NAME" => $name_val = "phoenix_bx_web_hook",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "SEND_TO_AMO" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEND_TO_AMO"),
                                "NAME" => $name_val = "phoenix_send_to_amo",
                                "VALUES" =>  array(
                                    "ACTIVE" =>  array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEND_TO_AMO"),
                                        "VALUE" => "Y",
                                        "HINT" => Loc::GetMessage("PHOENIX_SEND_TO_AMO_HINT"),
                                        "SHOW_OPTIONS" => "amo_options"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_SEND_TO_AMO_HINT"),
                                "TYPE" => "checkbox",
                            ),


                            "AMO_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_AMO_URL"),
                                "NAME" => $name_val = "phoenix_amo_url",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_AMO_URL_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "AMO_LOG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_AMO_LOG"),
                                "NAME" => $name_val = "phoenix_amo_login",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_AMO_LOG_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "AMO_HASH" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_AMO_HASH"),
                                "NAME" => $name_val = "phoenix_amo_hash",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            /*"AMO_TOKEN" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_AMO_TOKEN"),
                                "HINT" => Loc::GetMessage("PHOENIX_SET_AMO_TOKEN_HINT"),
                                "NAME" => $name_val = "phoenix_amo_token",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "ROWS" => $rowMD,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                )
                            ),*/

                        ),


                    );


                    

                    
                }



                elseif($valueParams == "services" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"] = array(
                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_SERVICES_NAME"),

                        "ITEMS" => array(

                            "META" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_META"),
                                "NAME" => "phoenix_meta",
                                "VALUE" => $meta,
                                "~VALUE" => $metaBack,
                                "HINT" => Loc::GetMessage("PHOENIX_META_HINT"),
                                "DESC_ON" => "N",
                                "TYPE" => "text",
                                "MULTY" => "Y"
                            ),

                            "METRIKA" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_METRIKA"),
                                "NAME" => $name_val = "phoenix_metrika",
                                "VALUE" => $opt = trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_METRIKA_HINT"),
                                "ROWS" => $rowMD,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GOOGLE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GOOGLE"),
                                "NAME" => $name_val = "phoenix_google",
                                "VALUE" => $opt =  trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_GOOGLE_HINT"),
                                "ROWS" => $rowMD,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_HEAD" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_HEAD"),
                                "NAME" => $name_val = "phoenix_gtm_head",
                                "VALUE" => $opt = trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_HEAD_HINT"),
                                "ROWS" => $rowMD,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_BODY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_BODY"),
                                "NAME" => $name_val = "phoenix_gtm_body",
                                "VALUE" => $opt = trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_BODY_HINT"),
                                "ROWS" => $rowMD,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "METRIKA_GOAL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_METRIKA_GOAL"),
                                "NAME" => $name_val = "phoenix_metrika_goal",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_METRIKA_GOAL_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GOOGLE_ACTION" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GOOGLE_ACTION"),
                                "NAME" => $name_val = "phoenix_google_action",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GOOGLE_ACTION_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GOOGLE_CATEGORY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GOOGLE_CATEGORY"),
                                "NAME" => $name_val = "phoenix_google_category",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GOOGLE_CATEGORY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_EVENT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_EVENT"),
                                "NAME" => $name_val = "phoenix_gtm_event",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_EVENT_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_ACTION" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_ACTION"),
                                "NAME" => $name_val = "phoenix_gtm_action",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_CATEGORY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_CATEGORY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_CATEGORY"),
                                "NAME" => $name_val = "phoenix_gtm_category",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_CATEGORY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "INHEAD" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_INHEAD"),
                                "NAME" => $name_val = "phoenix_in_head",
                                "VALUE" => $opt = trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowLG,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "INBODY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_INBODY"),
                                "NAME" => $name_val = "phoenix_in_body",
                                "VALUE" => $opt = trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowLG,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "INCLOSEBODY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_INCLOSEBODY"),
                                "NAME" => $name_val = "phoenix_in_close_body",
                                "VALUE" => $opt = trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowLG,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "LAZY_SERVICE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_LAZY_SERVICE"),
                                "NAME" => $name_val = "phoenix_lazy_service",
                                "HINT" => Loc::GetMessage("PHOENIX_LAZY_SERVICE_HINT"),
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_LAZY_SERVICE"),
                                        "HINT" => Loc::GetMessage("PHOENIX_LAZY_SERVICE_HINT"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "LAZY_SERVICE_TIME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_LAZY_SERVICE_TIME"),
                                "HINT" => Loc::GetMessage("PHOENIX_LAZY_SERVICE_TIME_HINT"),
                                "NAME" => $name_val = "phoenix_lazy_service_time",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "1",
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text"
                            ),


                            "LAZY_SCRIPTS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_LAZY_SCRIPTS"),
                                "NAME" => $name_val = "phoenix_lazy_scripts",
                                "VALUE" => $opt = trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowLG,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "LAZY_SCRIPTS_TIME" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_LAZY_SCRIPTS_TIME"),
                                "NAME" => $name_val = "phoenix_lazy_scripts_time",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "1",
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text"
                            ),


                            "METRIKA_GOAL_FAST_ORDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_METRIKA_GOAL"),
                                "NAME" => $name_val = "phoenix_metrika_goal_fast_order",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_METRIKA_GOAL_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GOOGLE_ACTION_FAST_ORDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GOOGLE_ACTION"),
                                "NAME" => $name_val = "phoenix_google_action_fast_order",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GOOGLE_ACTION_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GOOGLE_CATEGORY_FAST_ORDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GOOGLE_CATEGORY"),
                                "NAME" => $name_val = "phoenix_google_category_fast_order",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GOOGLE_CATEGORY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_EVENT_FAST_ORDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_EVENT"),
                                "NAME" => $name_val = "phoenix_gtm_event_fast_order",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_EVENT_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_ACTION_FAST_ORDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_ACTION"),
                                "NAME" => $name_val = "phoenix_gtm_action_fast_order",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_CATEGORY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_CATEGORY_FAST_ORDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_CATEGORY"),
                                "NAME" => $name_val = "phoenix_gtm_category_fast_order",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_CATEGORY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),




                            "METRIKA_GOAL_ORDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_METRIKA_GOAL"),
                                "NAME" => $name_val = "phoenix_metrika_goal_order",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_METRIKA_GOAL_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GOOGLE_ACTION_ORDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GOOGLE_ACTION"),
                                "NAME" => $name_val = "phoenix_google_action_order",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GOOGLE_ACTION_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GOOGLE_CATEGORY_ORDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GOOGLE_CATEGORY"),
                                "NAME" => $name_val = "phoenix_google_category_order",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GOOGLE_CATEGORY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_EVENT_ORDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_EVENT"),
                                "NAME" => $name_val = "phoenix_gtm_event_order",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_EVENT_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_ACTION_ORDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_ACTION"),
                                "NAME" => $name_val = "phoenix_gtm_action_order",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_CATEGORY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_CATEGORY_ORDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_CATEGORY"),
                                "NAME" => $name_val = "phoenix_gtm_category_order",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_CATEGORY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),


                            "METRIKA_GOAL_ADD2BASKET" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_METRIKA_GOAL"),
                                "NAME" => $name_val = "phoenix_metrika_goal_add2basket",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_METRIKA_GOAL_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GOOGLE_ACTION_ADD2BASKET" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GOOGLE_ACTION"),
                                "NAME" => $name_val = "phoenix_google_action_add2basket",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GOOGLE_ACTION_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GOOGLE_CATEGORY_ADD2BASKET" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GOOGLE_CATEGORY"),
                                "NAME" => $name_val = "phoenix_google_category_add2basket",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GOOGLE_CATEGORY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_EVENT_ADD2BASKET" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_EVENT"),
                                "NAME" => $name_val = "phoenix_gtm_event_add2basket",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_EVENT_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_ACTION_ADD2BASKET" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_ACTION"),
                                "NAME" => $name_val = "phoenix_gtm_action_add2basket",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_CATEGORY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "GTM_CATEGORY_ADD2BASKET" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_GTM_CATEGORY"),
                                "NAME" => $name_val = "phoenix_gtm_category_add2basket",
                                "VALUE" => trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_GTM_CATEGORY_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),


                        ),
                    );


                    $PHOENIX_TEMPLATE_ARRAY["HEAD_JS"] = "";

                    $PHOENIX_TEMPLATE_ARRAY["HEAD_JS"] .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA"]['~VALUE'];
                    $PHOENIX_TEMPLATE_ARRAY["HEAD_JS"] .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE"]['~VALUE'];
                    $PHOENIX_TEMPLATE_ARRAY["HEAD_JS"] .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_HEAD"]['~VALUE'];




                    $PHOENIX_TEMPLATE_ARRAY["HEAD_JS"] = str_replace(array("<script"), array("<script data-skip-moving='true'"),$PHOENIX_TEMPLATE_ARRAY["HEAD_JS"]);


                    

                    $PHOENIX_TEMPLATE_ARRAY["BODY_JS"] = "";
                    
                    $PHOENIX_TEMPLATE_ARRAY["BODY_JS"] .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_BODY"]['~VALUE'];


                    $PHOENIX_TEMPLATE_ARRAY["BODY_JS"] = str_replace(array("<script"), array("<script data-skip-moving='true'"),$PHOENIX_TEMPLATE_ARRAY["BODY_JS"]);



                    $PHOENIX_TEMPLATE_ARRAY["LAZY_SCRIPTS"] = "";

                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SERVICE"]["VALUE"]["ACTIVE"] == "Y")
                    {
                          if(strlen($PHOENIX_TEMPLATE_ARRAY["HEAD_JS"])>0)
                                $PHOENIX_TEMPLATE_ARRAY["LAZY_SCRIPTS"] .= $PHOENIX_TEMPLATE_ARRAY["HEAD_JS"];

                          if(strlen($PHOENIX_TEMPLATE_ARRAY["BODY_JS"])>0)
                                $PHOENIX_TEMPLATE_ARRAY["LAZY_SCRIPTS"] .= $PHOENIX_TEMPLATE_ARRAY["BODY_JS"];
                    }
                }


                elseif($valueParams == "politic" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"] = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_POLITIC_NAME"),

                        "ITEMS" => array(

                            "POLITIC_DESC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_POLITIC_DESC"),
                                "NAME" => $name_val = "phoenix_politic_desc",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_POLITIC_DESC_HINT"),
                                "ROWS" => $rowSM,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea"
                                )
                            ),

                            "POLITIC_CHECKED" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_POLITIC_CHECKED"),
                                "NAME" => $name_val = "phoenix_politic_checked",
                                "VALUES" =>  array(

                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_POLITIC_CHECKED"),
                                        "VALUE" => "Y"
                                    )

                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_POLITIC_CHECKED_HINT"),
                                "TYPE" => "checkbox",
                            ),


                        ),


                    );


                    self::getIblockIDs(array(
                        "SITE_ID"=>$siteId, 
                        "CODES" => array(
                                "concept_phoenix_agreements_".$siteId,
                            )
                        )
                    );


                    if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['AGREEMENT']["IBLOCK_ID"]))
                    {

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']['DESCRIPTION'] = Loc::GetMessage("PHOENIX_CART_AGREEMENTS_TITLE");
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']['HINT'] = Loc::GetMessage("PHOENIX_CART_AGREEMENTS_TITLE_HINT");

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']['TYPE'] = "radio";


                        $arSelect = array("ID", "NAME", "PROPERTY_CASE_TEXT", "PROPERTY_SHOW_FORM", "PROPERTY_SHOW_FOOTER");
                        $arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['AGREEMENT']["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
                        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

                        while($ob = $res->GetNextElement()){

                            $arItem = $ob->GetFields();

                            if($arItem["PROPERTY_SHOW_FORM_VALUE"] == 'Y')
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM'][] = $arItem;

                            if($arItem["PROPERTY_SHOW_FOOTER_VALUE"] == 'Y')
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FOOTER'][] = $arItem;


                            $arItRes = array();
                            $arItRes["NAME"] = "phoenix_cart_conditions";
                            $arItRes["DESCRIPTION"] = $arItem['NAME'];
                            $arItRes["VALUE"] = $arItem['ID'];

                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']['VALUES'][] = $arItRes;
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT']['ITEMS'][] = $arItem;
                        }


                        $arEmpty[0] = array("NAME" => "phoenix_cart_conditions", "DESCRIPTION" => GetMessage("PHOENIX_EMPTY_VAL"), "VALUE" => "N");
                    
                        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["AGREEMENTS"]["NAME"]))
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["AGREEMENTS"]["NAME"] = array_merge($arEmpty, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["AGREEMENTS"]["NAME"]);


                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']["VALUE"] = trim(Option::get($moduleID, "phoenix_cart_conditions", false, $siteId));

                   

                        if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM']))
                              $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM_COUNT'] = count($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM']);




                        $resHtml = "";

                        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM']))
                        {
                            
                            $resHtml .= '<div class="wrap-agree">';
                                $resHtml .= '<label class="input-checkbox-css">';
                                    $resHtml .= '<input type="checkbox" class="agreecheck" name="checkboxAgree" value="agree"';

                                        $resHtml .= ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["POLITIC_CHECKED"]['VALUE']["ACTIVE"] == 'Y')? 'checked' : '';

                                    $resHtml .= '>';

                                    $resHtml .= '<span></span>';
                                $resHtml .= '</label>';


                                $resHtml .= '<div class="wrap-desc"> ';

                                    $resHtml .= '<span class="text">';

                                        $resHtml .= (strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["POLITIC_DESC"]['VALUE'])>0) ? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["POLITIC_DESC"]['~VALUE']: GetMessage('PHOENIX_MODAL_FORM_AGREEMENT');

                                    $resHtml .= '</span>';

                                    foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM'] as $k => $arAgr)
                                    {

                                        $resHtml .= '<a class="call-modal callagreement" data-call-modal="agreement'.$arAgr['ID'].'">';

                                            $resHtml .= (strlen($arAgr['PROPERTY_CASE_TEXT_VALUE'])>0)?$arAgr['~PROPERTY_CASE_TEXT_VALUE']:$arAgr['~NAME'];

                                        $resHtml .= '</a>';

                                        $resHtml .= ($k+1 != $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FORM_COUNT'])?', ':'';

                                    }


                                $resHtml .= '</div>';
                                
                            $resHtml .= '</div>';
                        }

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["AGREEMENT_FOR_FORMS_HTML"] = $resHtml;

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["AGREEMENT_FOR_FORMS_HTML_FROM_MODAL"] = str_replace("callagreement", "callagreement from-modal", $resHtml);

                        
                        unset($resHtml);

                    }



                }


                elseif($valueParams == "customs" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"] = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_CUSTOMS_NAME"),

                        "ITEMS" => array(

                            "STYLES" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_STYLES"),
                                "NAME" => $name_val = "phoenix_styles",
                                "VALUE" => $opt = trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_STYLES_HINT"),
                                "ROWS" => $rowMD,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea"
                                )
                            ),

                            "SCRIPTS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SCRIPTS"),
                                "NAME" => $name_val = "phoenix_scripts",
                                "VALUE" => $opt = trim(Option::get($moduleID, $name_val, false, $siteId)),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_SCRIPTS_HINT"),
                                "ROWS" => $rowMD,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea"
                                )
                            ),

                        ),

                    );
                }


                elseif($valueParams == "other" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["NAME"]))
                {   

                    $site_name =  Option::get($moduleID, "phoenix_name_site", false, $siteId);

                    if(strlen($site_name)<=0)
                        $site_name = $arSite['NAME'];

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"] = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_OTHER_NAME"),

                        "ITEMS" => array(



                            "AFTER_EPILOG_CLEAR_CACHE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_AFTER_EPILOG_CLEAR_CACHE"),
                                "NAME" => $name_val = "phoenix_after_epilog_clear_cache",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_AFTER_EPILOG_CLEAR_CACHE"),
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "NAME_SITE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_NAME_SITE"),
                                "NAME" => "phoenix_name_site",
                                "VALUE" => $site_name,
                                "HINT" => Loc::GetMessage("PHOENIX_NAME_SITE_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "ADD_SITE_TITLE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ADD_SITE_TITLE"),
                                "NAME" => $name_val = "phoenix_add_site_title",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_ADD_SITE_TITLE"),
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "MODE_FAST_EDIT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_ADMIN_EDIT_ON"),
                                "NAME" => $name_val = "phoenix_admin_edit_on",
                                "VALUES" =>  array(

                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_ADMIN_EDIT_ON"),
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_ADMIN_EDIT_ON_HINT"),
                                "TYPE" => "checkbox",
                            ),

                            "SCROLLBAR" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SCROLLBAR"),
                                "NAME" => $name_val = "phoenix_show_scrollbar",
                                "VALUES" =>  array(

                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SCROLLBAR"),
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "SITE_BUILD_ON" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SITE_BUILD_ON"),
                                "NAME" => $name_val = "phoenix_build_on",
                                "VALUES" => array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val, 
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SITE_BUILD_ON"), 
                                        "VALUE" => "Y",
                                        "SHOW_OPTIONS" => "site_build_on"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_SITE_BUILD_ON_HINT"),
                                "TYPE" => "checkbox",
                            ),

                            "SITE_BUILD_TEXT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SITE_BUILD_TEXT"),
                                "NAME" => $name_val = "phoenix_build_text",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_SITE_BUILD_TEXT_HINT"),
                                "DEFAULT" => Loc::GetMessage("PHOENIX_SITE_BUILD_TEXT_DEFAULT"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "SITE_BUILD_LINK" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SITE_BUILD_LINK"),
                                "NAME" => $name_val = "phoenix_build_link",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "HINT" => Loc::GetMessage("PHOENIX_SITE_BUILD_LINK_HINT"),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "BG_404" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BG_404"),
                                "NAME" => $name_val = "img_phoenix_bg_404",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "MES_404" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_MES_404"),
                                "NAME" => $name_val = "phoenix_mes404",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_MES_404_HINT"),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "DATE_FORMAT" => array(

                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_DATE_FORMAT"),

                                "NAME" => $name_val = "phoenix_date_format",

                                "DESCRIPTIONS" => array(
                                    Loc::GetMessage("PHOENIX_DATE_FORMAT_1"),
                                    Loc::GetMessage("PHOENIX_DATE_FORMAT_2"),
                                    Loc::GetMessage("PHOENIX_DATE_FORMAT_3"),
                                    Loc::GetMessage("PHOENIX_DATE_FORMAT_4"),
                                    loc::GetMessage("PHOENIX_DATE_FORMAT_5"),
                                    Loc::GetMessage("PHOENIX_DATE_FORMAT_6"),
                                    Loc::GetMessage("PHOENIX_DATE_FORMAT_7"),
                                    Loc::GetMessage("PHOENIX_DATE_FORMAT_8")
                                ),

                                "VALUES" => array(
                                    "j F Y",
                                    "j F Y H:i",
                                    "d.m.Y",
                                    "d.m.Y H:i",
                                    "f j, Y",
                                    "f j, Y G:i",
                                    "m.d.Y",
                                    "m.d.Y G:i"
                                ),

                                "DEFAULT" => "j F Y",

                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "TYPE" => "select"
                                
                            ),

                            "COMPRESS_HTML" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_COMPRESS_HTML"),
                                "NAME" => $name_val = "phoenix_compress_html",
                                "VALUES" => array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val, 
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_COMPRESS_HTML"), 
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_COMPRESS_HTML_HINT"),
                                "TYPE" => "checkbox",
                            ),

                            "DELETE_BX_TECH_FILES" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_DELETE_BX_TECH_FILES"),
                                "NAME" => $name_val = "phoenix_delete_bx_tech_files",
                                "VALUES" => array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val, 
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_DELETE_BX_TECH_FILES"), 
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_DELETE_BX_TECH_FILES_HINT"),
                                "TYPE" => "checkbox",
                            ),

                            "TRANSFER_CSS_TO_PAGE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_TRANSFER_CSS_TO_PAGE"),
                                "NAME" => $name_val = "phoenix_transfer_css_to_page",
                                "VALUES" => array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val, 
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_TRANSFER_CSS_TO_PAGE"), 
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "HINT" => Loc::GetMessage("PHOENIX_TRANSFER_CSS_TO_PAGE_HINT"),
                                "TYPE" => "checkbox",
                            ),


                            "CAPTCHA" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CAPTCHA"),
                                "NAME" => $name_val = "phoenix_captcha",
                                "VALUES" => array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val, 
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_CAPTCHA"), 
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "CAPTCHA_SITE_KEY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CAPTCHA_SITE_KEY"),
                                "NAME" => $name_val = "phoenix_captcha_site_key",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "CAPTCHA_SECRET_KEY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_CAPTCHA_SECRET_KEY"),
                                "NAME" => $name_val = "phoenix_captcha_secret_key",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "CAPTCHA_SCORE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CAPTCHA_SCORE"),
                                "HINT" => Loc::GetMessage("PHOENIX_SET_CAPTCHA_SCORE_HINT"),
                                "NAME" => $name_val = "phoenix_captcha_score",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT" => "0.9",
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                            ),

                        ),

                    );

                    $bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false);
                        $PHOENIX_TEMPLATE_ARRAY["BINDEX_BOT"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['AFTER_EPILOG_CLEAR_CACHE']['VALUE']['ACTIVE'] === 'Y' && $bIndexBot;
                }


                elseif($valueParams == "search" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"] = array(
                        "NAME" => Loc::GetMessage("PHOENIX_SEARCH_NAME"),

                        "ITEMS" => array(

                            "SKIP_CATALOG_PREVIEW_TEXT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_SKIP_CATALOG_PREVIEW_TEXT"),
                                "HINT"=>Loc::GetMessage("PHOENIX_SEARCH_SKIP_CATALOG_PREVIEW_TEXT_HINT"),
                                "NAME" => $name_val = "phoenix_search_skip_catalog_preview_text",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_SKIP_CATALOG_PREVIEW_TEXT"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "ACTIVE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_ACTIVE"),
                                "NAME" => $name_val = "phoenix_search_active",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_ACTIVE"),
                                        "VALUE" => "Y",
                                        "SHOW_OPTIONS" => "search_options"
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "HEAD_BG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_HEAD_BG"),
                                "NAME" => $name_val = "img_phoenix_search_head_bg",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "SEARCH_IN" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_IN"),
                                "VALUES" =>  array(

                                    "CATALOG" => array(
                                        "NAME" => "phoenix_search_in_catalog",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_IN_CATALOG"),
                                        "VALUE" => "Y"
                                    ),

                                    "BLOG" => array(
                                        "NAME" => "phoenix_search_in_blog",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_IN_BLOG"),
                                        "VALUE" => "Y"
                                    ),

                                    "NEWS" => array(
                                        "NAME" => "phoenix_search_in_news",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_IN_NEWS"),
                                        "VALUE" => "Y"
                                    ),

                                    "ACTIONS" => array(
                                        "NAME" => "phoenix_search_in_actions",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_IN_ACTIONS"),
                                        "VALUE" => "Y"
                                    ),
                                ),
                                "VALUE" => array(

                                    "CATALOG" => Option::get($moduleID, "phoenix_search_in_catalog", false, $siteId),
                                    "BLOG" => Option::get($moduleID, "phoenix_search_in_blog", false, $siteId),
                                    "NEWS" => Option::get($moduleID, "phoenix_search_in_news", false, $siteId),
                                    "ACTIONS" => Option::get($moduleID, "phoenix_search_in_actions", false, $siteId)

                                ),
                                "TYPE" => "checkbox",
                            ),

                            "CATALOG_IC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_CATALOG_IC"),
                                "NAME" => $name_val = "img_phoenix_search_catalog_ic",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "BLOG_IC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_BLOG_IC"),
                                "NAME" => $name_val = "img_phoenix_search_blog_ic",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "NEWS_IC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_NEWS_IC"),
                                "NAME" => $name_val = "img_phoenix_search_news_ic",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "ACTIONS_IC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_ACTIONS_IC"),
                                "NAME" => $name_val = "img_phoenix_search_actions_ic",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "SHOW_IN" => array(

                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_SHOW_IN"),
                                "VALUES" =>  array(

                                    "CATALOG" => array(
                                        "NAME" => "phoenix_search_show_in_catalog",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_SHOW_IN_CATALOG"),
                                        "VALUE" => "Y"
                                    ),

                                    "BLOG" => array(
                                        "NAME" => "phoenix_search_show_in_blog",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_SHOW_IN_BLOG"),
                                        "VALUE" => "Y"
                                    ),

                                    "NEWS" => array(
                                        "NAME" => "phoenix_search_show_in_news",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_SHOW_IN_NEWS"),
                                        "VALUE" => "Y"
                                    ),

                                    "ACTIONS" => array(
                                        "NAME" => "phoenix_search_show_in_actions",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_SHOW_IN_ACTIONS"),
                                        "VALUE" => "Y"
                                    ),
                                    "IN_MENU" => array(
                                        "NAME" => 'phoenix_search_show_in_menu',
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_SHOW_IN_MENU"),
                                        "VALUE" => 'Y'
                                    ),
                                    "IN_MENU_OPEN" => array(
                                        "NAME" => 'phoenix_search_show_in_menu_open',
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_SHOW_IN_MENU_OPEN"),
                                        "VALUE" => 'Y'
                                    ),
                                ),

                                "VALUE" => array(

                                    "CATALOG" => Option::get($moduleID, "phoenix_search_show_in_catalog", false, $siteId),
                                    "BLOG" => Option::get($moduleID, "phoenix_search_show_in_blog", false, $siteId),
                                    "NEWS" => Option::get($moduleID, "phoenix_search_show_in_news", false, $siteId),
                                    "ACTIONS" => Option::get($moduleID, "phoenix_search_show_in_actions", false, $siteId),
                                    "IN_MENU" => Option::get($moduleID, "phoenix_search_show_in_menu", false, $siteId),
                                    "IN_MENU_OPEN" => Option::get($moduleID, "phoenix_search_show_in_menu_open", false, $siteId),

                                ),
                                "TYPE" => "checkbox",
                            ),

                            "QUEST_IN" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_QUEST_IN"),
                                "VALUES" =>  array(

                                    "IN_CATEGORY" => array(
                                        "NAME" => "phoenix_search_quest_in_categories",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_QUEST_IN_CATEGORIES"),
                                        "VALUE" => "Y"
                                    ),

                                    "IN_PRODUCTS" => array(
                                        "NAME" => "phoenix_search_quest_in_products",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_QUEST_IN_PRODUCTS"),
                                        "VALUE" => "Y"
                                    ),

                                    "IN_BRANDS" => array(
                                        "NAME" => "phoenix_search_quest_in_brands",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_QUEST_IN_BRANDS"),
                                        "VALUE" => "Y"
                                    ),
                                    
                                ),

                                "VALUE" => array(

                                    "IN_CATEGORY" => Option::get($moduleID, "phoenix_search_quest_in_categories", false, $siteId),
                                    "IN_PRODUCTS" => Option::get($moduleID, "phoenix_search_quest_in_products", false, $siteId),
                                    "IN_BRANDS" => Option::get($moduleID, "phoenix_search_quest_in_brands", false, $siteId),

                                ),
                                "TYPE" => "checkbox",
                            ),

                            "HINT_DEFAULT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_HINT_DEFAULT"),
                                "NAME" => $name_val = "phoenix_search_hint_default",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "HINT_CATALOG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_HINT_CATALOG"),
                                "NAME" => $name_val = "phoenix_search_hint_catalog",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "HINT_BLOG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_HINT_BLOG"),
                                "NAME" => $name_val = "phoenix_search_hint_blog",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "HINT_NEWS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_HINT_NEWS"),
                                "NAME" => $name_val = "phoenix_search_hint_news",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "HINT_ACTIONS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_HINT_ACTIONS"),
                                "NAME" => $name_val = "phoenix_search_hint_acrtions",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "SEARCH_PAGE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_PAGE"),
                                "NAME" => $name_val = "phoenix_search_page",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "DEFAULT"=>SITE_DIR.'search/',
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "PLACEHOLDER_CATALOG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_PLACEHOLDER_CATALOG"),
                                "NAME" => $name_val = "phoenix_search_placeholder_catalog",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "PLACEHOLDER_BLOG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_PLACEHOLDER_BLOG"),
                                "NAME" => $name_val = "phoenix_search_placeholder_blog",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "PLACEHOLDER_NEWS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_PLACEHOLDER_NEWS"),
                                "NAME" => $name_val = "phoenix_search_placeholder_news",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "PLACEHOLDER_ACTIONS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_PLACEHOLDER_ACTIONS"),
                                "NAME" => $name_val = "phoenix_search_placeholder_actions",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "URLS" => array(
                                "CATALOG" => "catalog",
                                "NEWS" => "news",
                                "ACTIONS" => "actions",
                                "BLOG" => "blog"
                            ),

                            "NOT_FOUND" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SEARCH_NOT_FOUND"),
                                "NAME" => $name_val = "phoenix_search_not_found",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "FASTSEARCH_ACTIVE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_FASTSEARCH_ACTIVE"),
                                "NAME" => $name_val = "phoenix_fastsearch_active",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_FASTSEARCH_ACTIVE"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),


                        ),
                    );


                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["QUEST_IN"]["VALUE"]["IN_CATEGORY"] != "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["QUEST_IN"]["VALUE"]["IN_PRODUCTS"] != "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["QUEST_IN"]["VALUE"]["IN_BRANDS"] != "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_IN"]["VALUE"]["CATALOG"] == "Y")
                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_IN"]["VALUE"]["CATALOG"] = "";


                    
                }


                elseif($valueParams == "catalog_fields" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["NAME"] = Loc::GetMessage("PHOENIX_ADMIN_CATALOG_ITEM_FIELDS_NAME");

                    $tmpArr = array(

                        "SKU_HIDE_IN_LIST" => array(

                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_ITEM_FIELDS_SKU_HIDE_IN_LIST"),
                            "NAME" => $name_val = "phoenix_catalog_item_fields_sku_show_in_list",
                            "VALUES" =>  array(
                                "ACTIVE" => array(
                                    "NAME" => $name_val,
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_ITEM_FIELDS_SKU_HIDE_IN_LIST"),
                                    "VALUE" => "Y",
                                )
                            ),
                            "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                            "TYPE" => "checkbox",
                        ),

                        "PROPS_IN_LIST_FOR_FLAT" => array(

                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_FLAT"),

                            "VALUES" =>  array(
                                "PREVIEW_TEXT" => array(
                                    "NAME" => "phoenix_catalogIF_l_flat_preview_text",
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_FLAT_PREVIEW_TEXT"),
                                    "VALUE" => "Y",
                                ),
                                "DESCRIPTION" => array(
                                    "NAME" => "phoenix_catalogIF_l_flat_description",
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_FLAT_DESCRIPTION"),
                                    "VALUE" => "Y",
                                ),
                                "SKU_PROPS" => array(
                                    "NAME" => "phoenix_catalogIF_l_flat_sku_props",
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_FLAT_SKU_PROPS"),
                                    "VALUE" => "Y",
                                ),
                                "CHARS" => array(
                                    "NAME" => "phoenix_catalogIF_l_flat_chars",
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_FLAT_CHARS"),
                                    "VALUE" => "Y",
                                ),
                                "ITEM_PROPS" => array(
                                    "NAME" => "phoenix_catalogIF_l_flat_item_props",
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_FLAT_ITEM_PROPS"),
                                    "VALUE" => "Y",
                                ),
                                "RATE_VOTE" => array(
                                    "NAME" => "phoenix_catalogIF_l_flat_rate_vote",
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_FLAT_RATE_VOTE"),
                                    "VALUE" => "Y",
                                ),
                            ),

                            "VALUE" => array(

                                "PREVIEW_TEXT" => Option::get($moduleID, "phoenix_catalogIF_l_flat_preview_text", false, $siteId),
                                "DESCRIPTION" => Option::get($moduleID, "phoenix_catalogIF_l_flat_description", false, $siteId),
                                "SKU_PROPS" => Option::get($moduleID, "phoenix_catalogIF_l_flat_sku_props", false, $siteId),
                                "CHARS" => Option::get($moduleID, "phoenix_catalogIF_l_flat_chars", false, $siteId),
                                "ITEM_PROPS" => Option::get($moduleID, "phoenix_catalogIF_l_flat_item_props", false, $siteId),
                                "RATE_VOTE" => Option::get($moduleID, "phoenix_catalogIF_l_flat_rate_vote", false, $siteId),
                            ),
                            "TYPE" => "checkbox",
                        ),

                        "PROPS_IN_LIST_FOR_LIST" => array(

                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_LIST"),
                            "VALUES" =>  array(
                                "PREVIEW_TEXT" => array(
                                    "NAME" => "phoenix_catalogIF_l_list_preview_text",
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_LIST_PREVIEW_TEXT"),
                                    "VALUE" => "Y",
                                ),
                                "DESCRIPTION" => array(
                                    "NAME" => "phoenix_catalogIF_l_list_description",
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_LIST_DESCRIPTION"),
                                    "VALUE" => "Y",
                                ),
                                "SKU_PROPS" => array(
                                    "NAME" => "phoenix_catalogIF_l_list_sku_props",
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_LIST_SKU_PROPS"),
                                    "VALUE" => "Y",
                                ),
                                "CHARS" => array(
                                    "NAME" => "phoenix_catalogIF_l_list_chars",
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_LIST_CHARS"),
                                    "VALUE" => "Y",
                                ),
                                "ITEM_PROPS" => array(
                                    "NAME" => "phoenix_catalogIF_l_list_item_props",
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_LIST_ITEM_PROPS"),
                                    "VALUE" => "Y",
                                ),
                                "RATE_VOTE" => array(
                                    "NAME" => "phoenix_catalogIF_l_list_rate_vote",
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_CATALOG_I_F_L_LIST_RATE_VOTE"),
                                    "VALUE" => "Y",
                                ),
                            ),
                            "VALUE" => array(

                                "PREVIEW_TEXT" => Option::get($moduleID, "phoenix_catalogIF_l_list_preview_text", false, $siteId),
                                "DESCRIPTION" => Option::get($moduleID, "phoenix_catalogIF_l_list_description", false, $siteId),
                                "SKU_PROPS" => Option::get($moduleID, "phoenix_catalogIF_l_list_sku_props", false, $siteId),
                                "CHARS" => Option::get($moduleID, "phoenix_catalogIF_l_list_chars", false, $siteId),
                                "ITEM_PROPS" => Option::get($moduleID, "phoenix_catalogIF_l_list_item_props", false, $siteId),
                                "RATE_VOTE" => Option::get($moduleID, "phoenix_catalogIF_l_list_rate_vote", false, $siteId),
                            ),
                            "TYPE" => "checkbox",
                        ),
                    );


                    if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]))
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"] = array_merge($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"],$tmpArr);
                    }



                }


                elseif($valueParams == "compare" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["NAME"] = Loc::GetMessage("PHOENIX_ADMIN_COMPARE_ITEM_FIELDS_NAME");

                    $tmpArr = array(
                        "ACTIVE" => array(
                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_COMPARE_ACTIVE"),
                            "NAME" => $name_val = "phoenix_compare_active",
                            "VALUES" =>  array(
                                "ACTIVE" => array(
                                    "NAME" => $name_val,
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_COMPARE_ACTIVE"),
                                    "VALUE" => "Y",
                                    "SHOW_OPTIONS" => "compare_options"
                                )
                            ),
                            "VALUE" => array("ACTIVE" => Option::get($moduleID, $name_val, false, $siteId)),
                            "TYPE" => "checkbox",
                        ),
                        "BG_PIC" => array(
                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_COMPARE_BG_PIC"),
                            "NAME" => $name_val = "img_phoenix_compare_bg_pic",
                            "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                            "SETTINGS" => CFile::GetFileArray($opt),
                            "TYPE" => "image"
                        ),

                        "DESC" => array(
                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_COMPARE_DESC"),
                            "NAME" => $name_val = "phoenix_compare_desc",
                            "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                            "~VALUE" => htmlspecialcharsBack($opt),
                            "ROWS" => $rowSM,
                            "SIZE" => $colLG,
                            "TYPE" => "text",
                            "IN_PUBLIC" => array(
                                "SIZE_HEIGHT" => "middle",
                                "TYPE" => "textarea",
                                "VIEW" => "decorated",
                            )
                        ),

                    );

                    if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]))
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"] = array_merge($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"],$tmpArr);
                    
                }


                elseif($valueParams == "brands" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"] = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_BRAND_NAME"),

                        "ITEMS" => array(
                            "CUSTOM_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_BRANDS_CUSTOM_URL"),
                                "NAME" => $name_val = "phoenix_brands_custom_url",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "NEWS_COUNT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_BRANDS_COUNT_ELEMENTS"),
                                "NAME" => $name_val = "phoenix_brands_count_elements",
                                "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                "ROWS" => $rowONE,
                                "SIZE" => $colSM,
                                "TYPE" => "text",
                                "DEFAULT" => "24",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                )
                            ),

                            "BG_PIC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BRANDS_BG_PIC"),
                                "NAME" => $name_val = "img_phoenix_brand_bg_pic",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "DESC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BRANDS_DESC"),
                                "NAME" => $name_val = "phoenix_brand_desc",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "HINT" => Loc::GetMessage("PHOENIX_BRANDS_DESC"),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),
                            "SEO_TEXT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_BRANDS_SEO_TEXT"),
                                "NAME" => $name_val = "phoenix_brands_seo_text",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $colVisFull,
                                "SIZE" => $colVisLG,
                                "TYPE" => "text",
                                "VIEW" => "visual",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea"
                                )
                            ),
                        ),

                    );
                }


                elseif($valueParams == "personal" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"] = array(
                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_PERSONAL_NAME"),

                        "ITEMS" => array(

                                "SHOW_DISCSAVE" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_SHOW_DISCSAVE"),
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => "phoenix_catalog_show_discsave",
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_CATALOG_SHOW_DISCSAVE"),
                                            "VALUE" => "Y",
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE" => Option::get($moduleID, "phoenix_catalog_show_discsave", false, $siteId)),
                                    "TYPE" => "checkbox"
                                ),


                              "FIX_PRICE_VALUES" => array(
                                      "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_FIX_PRICE_VALUES"),
                                      "NAME" => $name_val = "phoenix_personal_fix_price_values",
                                      "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                      "DEFAULT"=> "100;200;500;1000;5000;",
                                      "ROWS" => $rowONE,
                                      "SIZE" => $colMD,
                                      "TYPE" => "text",
                                      "IN_PUBLIC" => array(
                                          "VIEW" => "decorated"
                                      )
                                  ),

                              "SHOW_FIX_PRICE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_SHOW_FIX_PRICE"),
                                "NAME" => $name_val = "phoenix_personal_show_fix_price",
                                "VALUES" =>  array(

                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_SHOW_FIX_PRICE"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                              ),

                              "CABINET" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_CABINET"),
                                "NAME" => $name_val = "phoenix_personal_cabinet",
                                "VALUES" =>  array(

                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_CABINET"),
                                        "VALUE" => "Y",
                                        "SHOW_OPTIONS" => "personal_on"
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                              ),

                            "SECTIONS" => array(

                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_SECTIONS"),
                                "VALUES" =>  array(
                                    "ORDERS" => array(
                                        "NAME" => "phoenix_personal_section_orders",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_SECTION_ORDERS"),
                                        "URL" => "orders/",
                                        "VALUE" => "Y",
                                    ),
                                    "ACCOUNT" => array(
                                        "NAME" => "phoenix_personal_section_account",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_SECTION_ACCOUNT"),
                                        "URL" => "account/",
                                        "VALUE" => "Y",
                                    ),
                                    "PRIVATE" => array(
                                        "NAME" => "phoenix_personal_section_private",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_SECTION_PRIVATE"),
                                        "URL" => "private/",
                                        "VALUE" => "Y",
                                    ),
                                    "PROFILE" => array(
                                        "NAME" => "phoenix_personal_section_profile",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_SECTION_PROFILE"),
                                        "URL" => "profiles/",
                                        "VALUE" => "Y",
                                    ),
                                    "BASKET" => array(
                                        "NAME" => "phoenix_personal_section_basket",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_SECTION_BASKET"),
                                        "URL" => "",
                                        "VALUE" => "Y"
                                    ),
                                    "SUBSCRIBE" => array(
                                        "NAME" => "phoenix_personal_section_subscribe",
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_SECTION_SUBSCRIBE"),
                                        "URL" => "subscribe/",
                                        "VALUE" => "Y",
                                    ),
                                    
                                ),
                                "VALUE" => array(
                                    "ORDERS" => Option::get($moduleID, "phoenix_personal_section_orders", false, $siteId),
                                    "ACCOUNT" => Option::get($moduleID, "phoenix_personal_section_account", false, $siteId),
                                    "PRIVATE" => Option::get($moduleID, "phoenix_personal_section_private", false, $siteId),
                                    "PROFILE" => Option::get($moduleID, "phoenix_personal_section_profile", false, $siteId),
                                    "BASKET" => Option::get($moduleID, "phoenix_personal_section_basket", false, $siteId),
                                    "SUBSCRIBE" => Option::get($moduleID, "phoenix_personal_section_subscribe", false, $siteId),
                                    
                                ),
                                "TYPE" => "checkbox",
                            ),


                            "AUTH_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_AUTH_URL"),
                                "VALUE" => SITE_DIR."auth/",
                            ),

                            "REGISTER_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_REGISTER_URL"),
                                "VALUE" => SITE_DIR."personal/register/",
                            ),
                            "FORGOT_PASSWORD_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_FORGOT_PASSWORD_URL"),
                                "VALUE" => SITE_DIR."personal/forgotpasswd/",
                            ),

                            "SEF_FOLDER" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_SEF_FOLDER"),
                                "VALUE" => SITE_DIR."personal/",
                            ),

                            "CATALOG_URL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_SEF_FOLDER"),
                                "VALUE" => SITE_DIR."catalog/",
                            ),

                            "FORM_AUTH_SUBTITLE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_FORM_AUTH_SUBTITLE"),
                                "NAME" => $name_val = "phoenix_personal_form_subtitle",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "FORM_PIC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_FORM_PIC"),
                                "NAME" => $name_val = "img_phoenix_personal_form_pic",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "HEAD_BG_PIC" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_HEAD_BG_PIC"),
                                "NAME" => $name_val = "img_phoenix_personal_head_pic",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SETTINGS" => CFile::GetFileArray($opt),
                                "TYPE" => "image"
                            ),

                            "COMMENT_ORDERS" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_COMMENT_ORDERS"),
                                "NAME" => $name_val = "phoenix_personal_comment_orders",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "COMMENT_ORDERS_HISTORY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_COMMENT_ORDERS_HISTORY"),
                                "NAME" => $name_val = "phoenix_personal_comment_orders_history",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "COMMENT_ORDERS_ACCOUNT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_COMMENT_ORDERS_ACCOUNT"),
                                "NAME" => $name_val = "phoenix_personal_comment_orders_account",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "COMMENT_PRIVATE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_COMMENT_PRIVATE"),
                                "NAME" => $name_val = "phoenix_personal_comment_private",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "COMMENT_PROFILE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_PROFILE"),
                                "NAME" => $name_val = "phoenix_personal_comment_profile",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "COMMENT_BASKET" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_COMMENT_BASKET"),
                                "NAME" => $name_val = "phoenix_personal_comment_basket",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "COMMENT_SUBSCRIBE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_COMMENT_SUBSCRIBE"),
                                "NAME" => $name_val = "phoenix_personal_comment_subsribe",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),

                            "FIRE_TITLE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_FIRE_TITLE"),
                                "NAME" => $name_val = "phoenix_personal_fire_title",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated"
                                )
                            ),

                            "CUSTOM_PAGES" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_CUSTOM_PAGES"),
                                "NAME" => "phoenix_personal_custom_pages",
                                "VALUE" => array(),
                                "HINT" => Loc::GetMessage("PHOENIX_PERSONAL_CUSTOM_PAGES_HINT"),
                                "DESC_ON" => "N",
                                "TYPE" => "text",
                                "MULTY" => "Y",
                                "BTN_NAME" => Loc::GetMessage("PHOENIX_SETTINGS_CUSTOM_PAGES_ADD"),
                                "FIELD_NAME" => Loc::GetMessage("PHOENIX_PERSONAL_CUSTOM_PAGES_FIELD_NAME"),
                                "ITEMS"=>array(),
                                "JSON_ARRAY"=>''
                            ),

                            
                        )
                    );

                    $customPages = Option::get($moduleID, "phoenix_personal_custom_pages", false, $siteId);

                    if(strlen($customPages))
                        $customPages = unserialize(base64_decode($customPages));
                    else
                        $customPages = array();


                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CUSTOM_PAGES"]["VALUE"] = $customPages;

                    if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CUSTOM_PAGES"]["VALUE"]))
                    {
                        foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CUSTOM_PAGES"]["VALUE"] as $value)
                        {
                            $tmpRes = explode(";", CPhoenix::prepareText(htmlspecialcharsBack($value['name'])));

                        
                            if(!empty($tmpRes))
                            {
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CUSTOM_PAGES"]["ITEMS"][]=array(
                                    'URL'=>(isset($tmpRes[0]))?trim($tmpRes[0]):'',
                                    'NAME'=>(isset($tmpRes[1]))?trim($tmpRes[1]):'',
                                    'ICON'=>(isset($tmpRes[2]))?trim($tmpRes[2]):'',
                                    'TEXT'=>(isset($tmpRes[3]))?trim($tmpRes[3]):''
                                );
                            }
                        }
                    }


                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE'] = array(
                        "NAME" => $name_val = "phoenix_personal_type",
                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_CART_PERSON_TYPE_TITLE"),
                        "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                        "DEFAULT" =>  "N",
                        "TYPE" => "select",
                        "ATTR" => "class = 'show-option' style='width: 250px;'",
                        "CLASS_SELECT" => "btn-block-show"
                    );


                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['ACCOUNT_PERSON_TYPE'] = array(
                        "NAME" => $name_val = "phoenix_personal_account_person_type",
                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_PERSONAL_ACCOUNT_PERSON_TYPE"),
                        "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                        "DEFAULT" =>  "N",
                        "TYPE" => "select"

                    );


                    $db_ptype = CSalePersonType::GetList(Array("SORT" => "ASC"), Array("LID"=>$siteId, "ACTIVE"=>"Y"));

                    $arPersonTypes = array();

                    while ($ptype = $db_ptype->Fetch())
                    {

                        $arPersonTypes[] = "person_type_".$ptype['ID'];

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["DESCRIPTIONS"][$ptype['ID']] 
                        = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['ACCOUNT_PERSON_TYPE']["DESCRIPTIONS"][$ptype['ID']]
                        = $ptype['NAME'];

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["VALUES"][$ptype['ID']]
                        = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['ACCOUNT_PERSON_TYPE']["VALUES"][$ptype['ID']]
                        = "person_type_".$ptype['ID'];

                        if(!strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["VALUE"]))
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["VALUE"] = "person_type_".$ptype['ID'];

                        if(!strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['ACCOUNT_PERSON_TYPE']["VALUE"]))
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['ACCOUNT_PERSON_TYPE']["VALUE"] = "person_type_".$ptype['ID'];


                        
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$ptype["ID"]]["DESCRIPTION"] = Loc::GetMessage("PHOENIX_CART_FAST_ORDER_INPUTS");
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$ptype["ID"]]['TYPE'] = "checkbox";

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$ptype["ID"]]['IN_PUBLIC'] = array(
                            "CLASS_UL"=>"d-none block-show person_type_".$ptype['ID']
                        );

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$ptype["ID"]]['TR_CLASS'] = "content-options";
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$ptype["ID"]]['TR_ATTRS'] = "data-option-name = 'phoenix_personal_type' data-content-options = 'person_type_".$ptype['ID']."'";
                        


                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$ptype["ID"]]["DESCRIPTION"] = Loc::GetMessage("PHOENIX_CART_FAST_ORDER_INPUTS_REQ");
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$ptype["ID"]]['TYPE'] = "checkbox";

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$ptype["ID"]]['IN_PUBLIC'] = array(
                            "CLASS_UL"=>"d-none block-show person_type_".$ptype['ID']
                        );

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$ptype["ID"]]['TR_CLASS'] = "content-options";
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'][$ptype["ID"]]['TR_ATTRS'] = "data-option-name = 'phoenix_personal_type' data-content-options = 'person_type_".$ptype['ID']."'";


                    }


                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["VALUE"])<=0)
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["VALUE"] = $arPersonTypes[0];

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"] = str_replace("person_type_","", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["VALUE"]);

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["PERSONAL"]["SHOP"]["ITEMS"]['ACCOUNT_PERSON_TYPE']["VALUE"])<=0)
                        $PHOENIX_TEMPLATE_ARRAY["PERSONAL"]["SHOP"]["ITEMS"]['ACCOUNT_PERSON_TYPE']["VALUE"] = $arPersonTypes[0];

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['ACCOUNT_PERSON_TYPE']["CUR_VALUE"] = str_replace("person_type_","", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['ACCOUNT_PERSON_TYPE']["VALUE"]);

                }

                elseif($valueParams == "rating" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["NAME"]))
                {   

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"] = array(

                            "NAME" => Loc::GetMessage("PHOENIX_ADMIN_RATING_NAME"),

                            "ITEMS" => array(

                                "USE_VOTE" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_RATING_USE_VOTE"),
                                    "NAME" => $name_val = "phoenix_ctl_use_vote",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_RATING_USE_VOTE"),
                                            "VALUE" => "Y"
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "USE_REVIEW" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_RATING_USE_REVIEW"),
                                    "NAME" => $name_val = "phoenix_use_review",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_RATING_USE_REVIEW"),
                                            "VALUE" => "Y",
                                            "SHOW_OPTIONS" => "sets-reviews"
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),


                                "RATING_SIDEMENU_SHOW" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_RATING_SIDEMENU_SHOW"),
                                    "NAME" => $name_val = "phoenix_rating_sidemenu_show",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_RATING_SIDEMENU_SHOW"),
                                            "VALUE" => "Y"
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "RATING_SIDEMENU_NAME" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_RATING_SIDEMENU_NAME"),
                                    "NAME" => $name_val = "phoenix_rating_sidemenu_name",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colLG,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "RATING_BLOCK_TITLE" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_RATING_BLOCK_TITLE"),
                                    "NAME" => $name_val = "phoenix_rating_block_title",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colLG,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "RATING_HIDE_BLOCK_TITLE" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_RATING_HIDE_BLOCK_TITLE"),
                                    "NAME" => $name_val = "phoenix_rating_hide_block_title",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_RATING_HIDE_BLOCK_TITLE"),
                                            "VALUE" => "Y"
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),


                                "REVIEW_BLOCK_TITLE" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_REVIEW_BLOCK_TITLE"),
                                    "NAME" => $name_val = "phoenix_review_block_title",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colLG,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "REVIEW_HIDE_BLOCK_TITLE" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_REVIEW_HIDE_BLOCK_TITLE"),
                                    "NAME" => $name_val = "phoenix_review_hide_block_title",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_REVIEW_HIDE_BLOCK_TITLE"),
                                            "VALUE" => "Y"
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),


                                "REVIEW_MODERATOR" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_REVIEW_MODERATOR"),
                                    "NAME" => $name_val = "phoenix_review_moderator",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_REVIEW_MODERATOR"),
                                            "VALUE" => "Y"
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),

                                "FLY_HEAD_BG" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_REVIEW_FLY_HEAD_BG"),
                                    "NAME" => $name_val = "img_phoenix_review_fly_head_bg",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "SETTINGS" => CFile::GetFileArray($opt),
                                    "TYPE" => "image"
                                ),

                                "FLY_PANEL_SUCCESS_MESS"=> array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_REVIEW_FLY_PANEL_SUCCESS_MESS"),
                                    "NAME" => $name_val = "phoenix_review_fly_pnl_success",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $colVisFull,
                                    "SIZE" => $colVisLG,
                                    "TYPE" => "text",
                                    "VIEW" => "visual",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                        "SIZE_HEIGHT" => "middle",
                                        "TYPE" => "textarea"
                                    )
                                ),

                                "FLY_PANEL_DESC" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_REVIEW_FLY_PANEL_DESC"),
                                    "NAME" => $name_val = "phoenix_review_fly_pnl_desc",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colLG,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "RECOMMEND_HINT" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_REVIEW_RECOMMEND_HINT"),
                                    "NAME" => $name_val = "phoenix_rating_recommend_hint",
                                    "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                    "~VALUE" => htmlspecialcharsBack($opt),
                                    "DEFAULT" => Loc::GetMessage("PHOENIX_REVIEW_RECOMMEND_HINT_DEF"),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colLG,
                                    "TYPE" => "text",
                                    "IN_PUBLIC" => array(
                                        "VIEW" => "decorated",
                                    )
                                ),

                                "EMAIL_TO" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_REVIEW_EMAIL_TO"),
                                    "HINT" => Loc::GetMessage("PHOENIX_REVIEW_EMAIL_TO_HINT"),
                                    "NAME" => $name_val = "phoenix_review_email_to",
                                    "VALUE" => Option::get($moduleID, $name_val, false, $siteId),
                                    "ROWS" => $rowONE,
                                    "SIZE" => $colLG,
                                    "TYPE" => "text"
                                ),

                                "SEND_NEW_REVIEW" => array(
                                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_REVIEW_SEND_NEW_REVIEW"),
                                    "NAME" => $name_val = "phoenix_review_send_new_review",
                                    "VALUES" =>  array(
                                        "ACTIVE" => array(
                                            "NAME" => $name_val,
                                            "DESCRIPTION" => Loc::GetMessage("PHOENIX_REVIEW_SEND_NEW_REVIEW"),
                                            "VALUE" => "Y"
                                        )
                                    ),
                                    "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                    "TYPE" => "checkbox",
                                ),
                            ),
                        );

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["FLY_PANEL_SUCCESS_MESS"]["VALUE"])<=0)
                    {
                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["REVIEW_MODERATOR"]["VALUE"]["ACTIVE"] === "Y")
                        {
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["FLY_PANEL_SUCCESS_MESS"]["VALUE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["FLY_PANEL_SUCCESS_MESS"]["~VALUE"] = Loc::GetMessage("PHOENIX_REVIEW_FLY_PANEL_SUCCESS_MESS_DEF");


                        }
                        else
                        {
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["FLY_PANEL_SUCCESS_MESS"]["VALUE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["FLY_PANEL_SUCCESS_MESS"]["~VALUE"] = Loc::GetMessage("PHOENIX_REVIEW_FLY_PANEL_SUCCESS_MESS_DEF_2");
                        }
                    }



                    
                }

                elseif($valueParams == "region" && !isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["NAME"]))
                {   



                    $tmpArr = array(

                        "NAME" => Loc::GetMessage("PHOENIX_ADMIN_REGION_NAME"),

                        "ITEMS" => array(
                            "ACTIVE" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_ACTIVE"),
                                "NAME" => $name_val = "phoenix_region_active",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_ACTIVE"),
                                        "VALUE" => "Y",
                                        "SHOW_OPTIONS" => "region_options"
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),
                            "USE_404" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_REGION_USE_404"),
                                "NAME" => $name_val = "phoenix_region_use_404",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_REGION_USE_404"),
                                        "VALUE" => "Y",
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),

                            "DOMEN_DEFAULT" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_SET_DOMEN_DEFAULT"),
                                "HINT" => Loc::GetMessage("PHOENIX_REGION_SET_DOMEN_DEFAULT_HINT"),
                                "NAME" => $name_val = "phoenix_region_domen_default",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowONE,
                                "SIZE" => $colMD,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "VIEW" => "decorated",
                                ),
                                "PROTOCOL"=>"http://",
                                "URL"=>""
                            ),

                            "USE_IP" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_USE_IP"),
                                "NAME" => $name_val = "phoenix_region_use_ip",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_USE_IP"),
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),
                            "SHOW_IN_HEAD" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_SHOW_IN_HEAD"),
                                "NAME" => $name_val = "phoenix_region_show_in_head",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_SHOW_IN_HEAD"),
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),
                            "SHOW_APPLY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_SHOW_APPLY"),
                                "HINT" => Loc::GetMessage("PHOENIX_REGION_SHOW_APPLY_HINT"),
                                "NAME" => $name_val = "phoenix_region_show_apply",
                                "VALUES" =>  array(
                                    "ACTIVE" => array(
                                        "NAME" => $name_val,
                                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_SHOW_APPLY"),
                                        "VALUE" => "Y"
                                    )
                                ),
                                "VALUE" => array("ACTIVE"=>Option::get($moduleID, $name_val, false, $siteId)),
                                "TYPE" => "checkbox",
                            ),
                            "COMMENT_FOR_APPLY" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_COMMENT_FOR_APPLY"),
                                "HINT" => Loc::GetMessage("PHOENIX_REGION_COMMENT_FOR_APPLY_HINT"),
                                "NAME" => $name_val = "phoenix_region_comment_for_apply",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),
                            "COMMENT_FOR_FORM" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_COMMENT_FOR_FORM"),
                                "HINT" => Loc::GetMessage("PHOENIX_REGION_COMMENT_FOR_FORM_HINT"),
                                "NAME" => $name_val = "phoenix_region_comment_for_form",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "~VALUE" => htmlspecialcharsBack($opt),
                                "ROWS" => $rowSM,
                                "SIZE" => $colLG,
                                "TYPE" => "text",
                                "IN_PUBLIC" => array(
                                    "SIZE_HEIGHT" => "middle",
                                    "TYPE" => "textarea",
                                    "VIEW" => "decorated",
                                )
                            ),
                            "BG" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_BG"),
                                "NAME" => $name_val = "img_phoenix_region_bg",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SRC" => "",
                                "TYPE" => "image"
                            ),
                            "BG_SMALL" => array(
                                "DESCRIPTION" => Loc::GetMessage("PHOENIX_REGION_BG_SMALL"),
                                "NAME" => $name_val = "img_phoenix_region_bg_small",
                                "VALUE" => $opt = Option::get($moduleID, $name_val, false, $siteId),
                                "SRC" => "",
                                "TYPE" => "image"
                            ),
                        )
                    );

                    if(empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]))
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"] = array();


                    $tmpArr["ITEMS"]["LOCATION_LIST"] = array(
                        "DESCRIPTION" => Loc::GetMessage("PHOENIX_SET_LOCATION_LIST"),
                        "CLASS_SELECTOR" =>"location_list",
                        "SOURCE"=>"/bitrix/tools/concept.phoenix/ajax/settings/locations.php",
                        "HINT" => Loc::GetMessage("PHOENIX_SET_LOCATION_LIST_HINT"),
                        "NAME" => $name_val = "phoenix_location_list",
                        "VALUE_HIDDEN" => Option::get($moduleID, $name_val, false, $siteId),
                        "VALUE" => "",
                        "TYPE" => "inputdatalist",
                        "ROWS" => $rowONE,
                        "SIZE" => $colLG
                    );

                    

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"] = array_merge($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"], $tmpArr);

                    unset($tmpArr);

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['BG']['VALUE']))
                    {
                        $file = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['BG']['VALUE'], array('width'=>800, 'height'=>900), BX_RESIZE_IMAGE_PROPORTIONAL, false);

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["BG"]["SRC"] = $file["src"];

                        unset($file);
                    }

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['BG_SMALL']['VALUE']))
                    {
                        $file = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['BG_SMALL']['VALUE'], array('width'=>120, 'height'=>170), BX_RESIZE_IMAGE_PROPORTIONAL, false);

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["BG_SMALL"]["SRC"] = $file["src"];

                        unset($file);
                    }

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["VALUE"])>0)
                    {

                        preg_match("/^https:\/\//", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["VALUE"], $out);

                        if(!empty($out))
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["PROTOCOL"] = $out[0];

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["STR_URL"]=str_replace(array("http://","https://"), array("",""), $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["VALUE"]);

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["STR_URL"]=str_replace("/", "", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["STR_URL"]);
                        

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["URL"]=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["PROTOCOL"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["STR_URL"];

                        
                    }


                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["LOCATION_LIST"]["VALUE_HIDDEN"])>0 && \Bitrix\Main\Loader::includeModule('sale'))
                    {

                        $res = \Bitrix\Sale\Location\LocationTable::getList(array(
                            'filter' => array(
                                '=ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["LOCATION_LIST"]["VALUE_HIDDEN"],
                                '=PARENTS.NAME.LANGUAGE_ID' => LANGUAGE_ID,
                                '=PARENTS.TYPE.NAME.LANGUAGE_ID' => LANGUAGE_ID,
                            ),
                            'select' => array(
                                'I_ID' => 'PARENTS.ID',
                                'I_NAME_RU' => 'PARENTS.NAME.NAME',
                                'I_TYPE_CODE' => 'PARENTS.TYPE.CODE',
                                'I_TYPE_NAME_RU' => 'PARENTS.TYPE.NAME.NAME'
                            ),
                            'order' => array(
                                'PARENTS.DEPTH_LEVEL' => 'desc'
                            )
                        ));

                        $i = 0;
                        $regionName = "";

                        while($item = $res->fetch())
                        {
                            $regionName .= (($i==0)?"":", ").$item["I_NAME_RU"];
                            $i++;
                        }

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["LOCATION_LIST"]["VALUE"] = $regionName;
                    }


                }
            }



            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_ID"]>0)
            {

                $arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
                $res = CIBlockElement::GetList(Array(), $arFilter, false);

                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BANNERS']["DESCRIPTION"] = Loc::GetMessage("PHOENIX_BANNER_TITLE");
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BANNERS']["DESCRIPTION"] = Loc::GetMessage("PHOENIX_BANNER_TITLE");
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BANNERS']["DESCRIPTION"] = Loc::GetMessage("PHOENIX_BANNER_TITLE");
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BANNERS']["TYPE"] = "checkbox";
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BANNERS']["TYPE"] = "checkbox";
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BANNERS']["TYPE"] = "checkbox";


                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BANNERS"]["ITEMS"] = array();


                while($ob = $res->GetNextElement()){
                    $arFields = $ob->GetFields();
                    $arProductProps["PROPERTIES"] = $ob->GetProperties();

                   
                    if($arProductProps["PROPERTIES"]["PERSONAL_SHOW_BANNERS"]["VALUE_XML_ID"] == "show_all")
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BANNERS"]["ITEMS"][$arFields["ID"]] = $arFields["ID"];
                    


                    if($arProductProps["PROPERTIES"]["PERSONAL_SHOW_BANNERS"]["VALUE_XML_ID"] == "show_users")
                    {

                        if(!empty($arProductProps["PROPERTIES"]["PERSONAL_BANNERS_FOR_USERS"]["VALUE"]))
                        {

                            foreach ($arProductProps["PROPERTIES"]["PERSONAL_BANNERS_FOR_USERS"]["VALUE"] as$userID)
                            {
                                if($PHOENIX_TEMPLATE_ARRAY["USER_ID"] == $userID)
                                {
                                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BANNERS"]["ITEMS"][$arFields["ID"]] = $arFields["ID"];
                                    break;
                                }
                            }

                        }

                        if(!empty($arProductProps["PROPERTIES"]["PERSONAL_BANNERS_FOR_USER_GROUPS"]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["USER_GROUPS"]) && is_array($PHOENIX_TEMPLATE_ARRAY["USER_GROUPS"]))
                        {

                            $break = false;

                            foreach ($arProductProps["PROPERTIES"]["PERSONAL_BANNERS_FOR_USER_GROUPS"]["VALUE"] as $userGroupID)
                            {
                                if($break)
                                    break;

                                foreach ($PHOENIX_TEMPLATE_ARRAY["USER_GROUPS"] as $curUserGroupID)
                                {
                                    if($curUserGroupID == $userGroupID)
                                    {
                                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BANNERS"]["ITEMS"][$arFields["ID"]] = $arFields["ID"];
                                        $break = true;
                                        break;
                                    }
                                }
                            }

                            unset($break);
                        }
                    }
                   
                    

                    $NW["NAME"] = "phoenix_banner_nw".$arFields['ID'];
                    $ACT["NAME"] = "phoenix_banner_act".$arFields['ID'];
                    $BLG["NAME"] = "phoenix_banner_blg".$arFields['ID'];

                    $NW["DESCRIPTION"] = $ACT["DESCRIPTION"] = $BLG["DESCRIPTION"] = $arFields['NAME'];

                    $NW["VALUE"] = $ACT["VALUE"] = $BLG["VALUE"] = $arFields['ID'];

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BANNERS']['VALUES'][] = $NW;
                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BANNERS']['VALUE'][] = Option::get($moduleID, $NW["NAME"], false, $siteId);



                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BANNERS']['VALUES'][] = $BLG;
                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BANNERS']['VALUE'][] = Option::get($moduleID, $BLG["NAME"], false, $siteId);



                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BANNERS']['VALUES'][] = $ACT;
                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BANNERS']['VALUE'][] = Option::get($moduleID, $ACT["NAME"], false, $siteId);
                }

            }



            if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BANNERS']['VALUE']) && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BANNERS']['VALUE']))
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BANNERS']['VALUE'] = array_diff($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BANNERS']['VALUE'], array(''));

            if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BANNERS']['VALUE']) && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BANNERS']['VALUE']))
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BANNERS']['VALUE'] = array_diff($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BANNERS']['VALUE'], array(''));

            if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BANNERS']['VALUE']) && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BANNERS']['VALUE']))
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BANNERS']['VALUE'] = array_diff($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BANNERS']['VALUE'], array(''));
        }

        $PHOENIX_TEMPLATE_ARRAY["DOMAIN"]=CPhoenixHost::getHost($_SERVER, "N", "Y");


        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["OFFERS_QUANTITY"]["VALUE_RESULT"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["OFFERS_QUANTITY"]["VALUE"] ;

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]["SKU_HIDE_IN_LIST"]["VALUE"]["ACTIVE"] === "Y")
            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["OFFERS_QUANTITY"]["VALUE_RESULT"] = "5";



        $PHOENIX_TEMPLATE_ARRAY["MAIN_URLS"] = array(
            "catalog" => (strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["CUSTOM_URL"]["VALUE"])>0)? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["CUSTOM_URL"]["VALUE"]: SITE_DIR."catalog/",
            "brand" => (strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["CUSTOM_URL"]["VALUE"])>0)? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["CUSTOM_URL"]["VALUE"]: SITE_DIR."brands/",
            "brands" => (strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["CUSTOM_URL"]["VALUE"])>0)? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["CUSTOM_URL"]["VALUE"]: SITE_DIR."brands/",
            "blog" => (strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CUSTOM_URL"]["VALUE"])>0)? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CUSTOM_URL"]["VALUE"]: SITE_DIR."blog/",
            "news" => (strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["CUSTOM_URL"]["VALUE"])>0)? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["CUSTOM_URL"]["VALUE"]: SITE_DIR."news/",
            "offers" => (strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["CUSTOM_URL"]["VALUE"])>0)? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["CUSTOM_URL"]["VALUE"]: SITE_DIR."offers/",
            "action" => (strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["CUSTOM_URL"]["VALUE"])>0)? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["CUSTOM_URL"]["VALUE"]: SITE_DIR."offers/",
            "search" => (strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["CUSTOM_URL"]["VALUE"])>0)? $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["CUSTOM_URL"]["VALUE"]: SITE_DIR."search/",
        );

        $PHOENIX_TEMPLATE_ARRAY["BASKET_URL"] = CPhoenix::getBasketUrl(SITE_DIR, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]);


        $PHOENIX_TEMPLATE_ARRAY["BASKET_URL_DELAYED"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]."?url-tab=delayed";

        $PHOENIX_TEMPLATE_ARRAY["BASKET_URL_COMPARE"] = $PHOENIX_TEMPLATE_ARRAY["MAIN_URLS"]['catalog'].'compare/';


        CPhoenix::setDefaultOptions();


        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['ACTIVE']["VALUE"]["ACTIVE"] == "Y" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["VALUE"])>0 && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["LOCATION_LIST"]["VALUE"])>0)
        {
            CPhoenixRegionality::setRegionOptions($admin, $arParamsOther["flagRegionSend"]);

        }

        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_FROM"]['VALUE']) > 0)
            $PHOENIX_TEMPLATE_ARRAY["SITE_EMAIL"] = htmlspecialcharsBack(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_FROM"]['VALUE']));
        else
            $PHOENIX_TEMPLATE_ARRAY["SITE_EMAIL"] = "";


        
    }

    

    

    public static function setCounterPhoenix()
    {
        global $PHOENIX_TEMPLATE_ARRAY;
        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["GLOBALS"]["ITEMS"]["HIDE_ADV"] = array(
            "DESCRIPTION" => Loc::GetMessage("PHOENIX_HIDE_ADV"),
            "NAME" => $name_val = "phoenix_hide_adv",
            "VALUES" =>  array(

                "ACTIVE"=>array(
                    "NAME" => $name_val,
                    "DESCRIPTION" => Loc::GetMessage("PHOENIX_HIDE_ADV"),
                    "VALUE" => "Y"
                )
            ),
            "VALUE" => array(
                "ACTIVE"=>Option::get("concept.phoenix", $name_val, false, "")
            ),
            "TYPE" => "checkbox",
        );

    }


    public static function getMinPriceFromOffersExt(&$offers, $replaceMinPrice = true){
        $replaceMinPrice = ($replaceMinPrice === true);
        $result = false;
        $minPrice = 0;
        $diff = 0;
        if(!Loader::includeModule('iblock') || !Loader::includeModule('currency'))
            return;


        if (!empty($offers))
        {
            $doubles = array();
            foreach ($offers as $oneOffer)
            {

                if($oneOffer["PRICE"]["PRICE"] === '-1')
                    continue;
                $oneOffer['ID'] = (int)$oneOffer['ID'];
                if (isset($doubles[$oneOffer['ID']]))
                    continue;
                /*if (!$oneOffer['CAN_BUY'])
                    continue;*/

                //CIBlockPriceTools::setRatioMinPrice($oneOffer, $replaceMinPrice);


                $oneOffer['MIN_PRICE']['CATALOG_MEASURE_RATIO'] = $oneOffer['CATALOG_MEASURE_RATIO'];
                $oneOffer['MIN_PRICE']['CATALOG_MEASURE'] = $oneOffer['CATALOG_MEASURE'];
                $oneOffer['MIN_PRICE']['CATALOG_MEASURE_NAME'] = $oneOffer['CATALOG_MEASURE_NAME'];
                $oneOffer['MIN_PRICE']['~CATALOG_MEASURE_NAME'] = $oneOffer['~CATALOG_MEASURE_NAME'];

                if (empty($result["VALUE"]))
                {

                    $minPrice = $oneOffer['PRICE']['PRICE'];
                    $result["VALUE"] = $oneOffer['PRICE'];
                    $result["OFFER_ID"] = $oneOffer["ID"];

                }
                else
                {

                    $comparePrice = $oneOffer['PRICE']['PRICE'];

                    if($minPrice != $comparePrice && $diff == 0)
                    {
                        $diff++;
                    }
                    

                    if ($minPrice > $comparePrice)
                    {
                        $minPrice = $comparePrice;
                        $result["VALUE"] = $oneOffer['PRICE'];
                        $result["OFFER_ID"] = $oneOffer["ID"];
                    }

                    
                }




                $doubles[$oneOffer['ID']] = true;
            }
            
            $result["DIFF"] = $diff;

        }

        return $result;
    }

    public static function formatPriceMatrix($arItem = array()){

        if(!Loader::includeModule('currency'))
            return;
        

        if(isset($arItem))
        {

            $result = false;
            $minPrice = 0;
            $arItem['EXTRA_PRICE'] = false;

            if(!empty($arItem['MATRIX']))
            {
                foreach($arItem['MATRIX'] as $key => $arPriceGroup)
                {
                    if(!empty($arPriceGroup))
                    {
                        if(count($arPriceGroup)>1)
                            $arItem['EXTRA_PRICE'] = true;

                        
                        $i = 0;
                        foreach($arPriceGroup as $key2 => $arPrice)
                        {
                            

                            if($arPrice['PRICE'])
                            {
                                if($arItem['CAN_BUY'] && in_array($key, $arItem['CAN_BUY']))
                                {

                                    if($i === 0)
                                    {

                                        if (empty($result))
                                        {
                                            $minPrice = ($arPrice['DISCOUNT_PRICE'] != $arPrice['PRICE'] ? $arPrice['DISCOUNT_PRICE'] : $arPrice['PRICE']);
                                            $result = $minPrice;
                                            $arItem['MIN_PRICE']['TYPE_PRICE'] = $key;
                                            
                                        }
                                        else
                                        {
                                            $comparePrice = ($arPrice['DISCOUNT_PRICE'] != $arPrice['PRICE'] ? $arPrice['DISCOUNT_PRICE'] : $arPrice['PRICE']);
                                            if ($minPrice > $comparePrice)
                                            {
                                                $minPrice = $comparePrice;
                                                $result = $minPrice;
                                                $arItem['MIN_PRICE']['TYPE_PRICE'] = $key;
                                            }

                                        }

                                        $arItem['MIN_PRICE']['VALUE'] = $result;
                                        $arItem['MIN_PRICE']['DISCOUNT_VALUE'] = $result;
                                        $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] = CCurrencyLang::CurrencyFormat($result, $arPrice['CURRENCY'], true);
                                        $arItem['MIN_PRICE']['CURRENCY'] = $arPrice['CURRENCY'];
                                        $arItem['MIN_PRICE']['CAN_BUY'] = 'Y';
                                    }

                                    

                                }
                                
                                $arItem['MATRIX'][$key][$key2]['PRINT_PRICE'] =  CCurrencyLang::CurrencyFormat($arPrice['PRICE'], $arPrice['CURRENCY'], true);


                            }

                            if($arPrice['DISCOUNT_PRICE'])
                                $arItem['MATRIX'][$key][$key2]['PRINT_DISCOUNT_PRICE'] =  CCurrencyLang::CurrencyFormat($arPrice['DISCOUNT_PRICE'], $arPrice['CURRENCY'], true);

                            $i++;

                        }

                    }
                }
            }

        }
        return $arItem;
    }

    public static function showPriceMatrix($arItem = array(), $strMeasure = '', $type = 'list'){
        $html = '';
        global $PHOENIX_TEMPLATE_ARRAY;

        if(isset($arItem))
        {
            $countRows = (!empty($arItem['ROWS'])) ? count($arItem['ROWS']) : 0 ;
            $countPriceGroup = (!empty($arItem['COLS'])) ? count($arItem['COLS']) : 0 ;

            if(strlen($strMeasure)>0)
            {
                $strMeasureActualPrice = "&nbsp;/".$strMeasure;
                $strMeasure = "&nbsp;".$strMeasure;
            }


            ob_start();?>

            <?if($countRows > 1 && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y'):?>

                <div class="wrapper-matrix-block">

                    <div class="matrix-block">
                        <?
                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_AVAILABLE_PRICE_COUNT']['VALUE']['ACTIVE'] !== 'Y')
                            {
                                foreach($arItem['COLS'] as $arPriceGroup)
                                {
                                    if($arPriceGroup['ID'] != $arItem['MIN_PRICE']['TYPE_PRICE'])
                                        unset($arItem['COLS'][$arPriceGroup['ID']]);
                                }
                                $countPriceGroup = (!empty($arItem['COLS'])) ? count($arItem['COLS']) : 0 ;
                            }
                        ?>

                        <?if($countPriceGroup):?>

                            <?foreach($arItem['COLS'] as $arPriceGroup):?>

                                <div class="matrix-row">

                                    <?if($countPriceGroup > 1):?>
                                        <div class="name-matrix"><?=(strlen($arPriceGroup["NAME_LANG"])) ? $arPriceGroup["NAME_LANG"] : $arPriceGroup["NAME"]?></div>
                                    <?endif;?>

                                    <?if(isset($arItem['MATRIX'][$arPriceGroup['ID']])):?>

                                        <?if(!empty($arItem['MATRIX'][$arPriceGroup['ID']])):?>

                                            <?foreach($arItem['MATRIX'][$arPriceGroup['ID']] as $key => $arPrice):?>

                                                <table class="item-matrix">
                                                    <tr>

                                                        <?if($countRows>1):?>
                                                            <td class="quantity-matrix">
                                                                <?

                                                                    $quantity_from = $arItem['ROWS'][$key]['QUANTITY_FROM'];
                                                                    $quantity_to = $arItem['ROWS'][$key]['QUANTITY_TO'];
                                                                    $text = ($quantity_to ? ($quantity_from ? $quantity_from.'-'.$quantity_to : '<'.$quantity_to ) : '>'.$quantity_from );
                                                                ?>

                                                                <?=$text.$strMeasure?>

                                                            </td>
                                                        <?endif;?>

                                                        <?
                                                            $actualPrice = ($arPrice["PRICE"] > $arPrice["DISCOUNT_PRICE"])?$arPrice["PRINT_DISCOUNT_PRICE"]:$arPrice["PRINT_PRICE"];

                                                        ?>

                                                        <td class="price-matrix">

                                                            <div class="price" data-currency="<?=$arPrice["CURRENCY"];?>" data-value="<?=$arPrice["DISCOUNT_PRICE"];?>">
                                                                <?if(strlen($arPrice["DISCOUNT_PRICE"])):?>
                                                                    <span class="values_wrapper bold"><?=$actualPrice?></span><?//=$strMeasureActualPrice?>
                                                                <?endif;?>
                                                            </div>
                                                            <?/*if($arParams["SHOW_OLD_PRICE"]=="Y"):?>
                                                                <div class="price discount" data-currency="<?=$arPrice["CURRENCY"];?>" data-value="<?=$arPrice["PRICE"];?>">
                                                                    <span class="values_wrapper"><?=$arPrice["PRINT_PRICE"];?></span>
                                                                </div>
                                                            <?endif;*/?>
                                                         
                                                        </td>
                                                    </tr>
                                                </table>

                                            <?endforeach;?>

                                        <?endif;?>
                                    <?endif;?>

                                </div>

                            <?endforeach;?>
                        <?endif;?>
                        
                    </div>

                </div>

            
            <?elseif($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_AVAILABLE_PRICE_COUNT']['VALUE']['ACTIVE'] === 'Y'):?>

                <?
                    if(!$arItem['EXTRA_PRICE'])
                        unset($arItem['COLS'][$arItem['MIN_PRICE']['TYPE_PRICE']], $arItem['MATRIX'][$arItem['MIN_PRICE']['TYPE_PRICE']]);

                    $arNewMatrix = array();

                    if(!empty($arItem['MATRIX']))
                    {
                        foreach ($arItem['MATRIX'] as $keyPriceGroup => $arPriceGroup)
                        {
                            foreach($arPriceGroup as $keyPrice => $arPrice)
                            {
                                $arNewMatrix[$keyPriceGroup][$keyPrice] = $arPrice;
                                break;
                            }
                        }
                    }

                    $arItem['MATRIX'] = $arNewMatrix;

                ?>


                <?foreach($arItem['COLS'] as $arPriceGroup):?>

                    <?if(isset($arItem['MATRIX'][$arPriceGroup['ID']])):?>

                        <?if(!empty($arItem['MATRIX'][$arPriceGroup['ID']])):?>

                            <?foreach($arItem['MATRIX'][$arPriceGroup['ID']] as $key => $arPrice):?>

                                <?


                                    $percent = 0;

                                    if($PHOENIX_TEMPLATE_ARRAY['ITEMS']['CATALOG']['ITEMS']['HIDE_PERCENT']['VALUE']['ACTIVE'] !== 'Y')
                                    {
                                        if($arPrice['PRICE'] > $arPrice['DISCOUNT_PRICE'])
                                            $percent = 100 - intval($arPrice['DISCOUNT_PRICE']/$arPrice['PRICE'] * 100);
                                    }

                                    if($arPrice['PRICE'] > $arPrice['DISCOUNT_PRICE'])
                                    {
                                        $diff = CCurrencyLang::CurrencyFormat($arPrice['PRICE']-$arPrice['DISCOUNT_PRICE'], $arPrice['CURRENCY'], true);
                                    }
                                    
                                ?>

                                <?$actualPrice = ($arPrice["PRICE"] > $arPrice["DISCOUNT_PRICE"])?$arPrice["PRINT_DISCOUNT_PRICE"]:$arPrice["PRINT_PRICE"];?>

                                <div class="wr-board-price">

                                     <div class="name-type-price"><?=(strlen($arPriceGroup["NAME_LANG"])) ? $arPriceGroup["NAME_LANG"] : $arPriceGroup["NAME"]?></div>

                                    <div class="board-price row no-gutters">
                                        <div class="actual-price">
                                            <span class="price-value <?=($type === 'element')?'bold':''?>"><?=$actualPrice?></span><?if(strlen($strMeasureActualPrice)):?><span class="unit"><?=$strMeasureActualPrice?></span><?endif;?>
                                        </div>

                                        <?if($arPrice["PRICE"] > $arPrice["DISCOUNT_PRICE"]):?>
                                            <div class="old-price align-self-end product-old-price-value-js"><?=$arPrice["PRINT_PRICE"]?></div>
                                        <?endif;?>
                                    </div>

                                    <?if($percent && $type === 'element'):?>

                                        <div class="wrapper-discount-cheaper row no-gutters align-items-center">

                                            <div class="wrapper-discount">

                                                <span class="item-style desc-discount"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ECONOMY"]?></span><span class="item-style actual-econom bold product-price-discount-js"><?=$diff?></span>
                                                
                                                <span class="item-style actual-discount product-percent-discount-js">
                                                    <?=-$percent?>%
                                                </span>
                                            </div>
                                            
                                        </div>

                                    <?endif;?>

                                </div>

                            <?endforeach;?>

                        <?endif;?>

                    <?endif;?>

                <?endforeach;?>


            <?endif;?>

         
            
            <?
            $html = ob_get_contents();
            ob_end_clean();



            //foreach(GetModuleEvents(CONCEPT_PHOENIX_MODULE_ID, 'OnConceptShowPriceMatrix', true) as $arEvent) // event for manipulation price matrix
                //ExecuteModuleEventEx($arEvent, array($arItem, $arParams, $strMeasure, $arAddToBasketData, &$html));
        }
        return $html;
    }


    public static function deleteTabContentUser($arOurTabs, $arr, $arUser, $arPropFields, $arrSkipFields)
    {

        if(!empty($arOurTabs))
        {
            foreach ($arOurTabs as $arTab)
            {
                if(isset($arr[$arTab["DIV"]]))
                {
                    if(!empty($arr[$arTab["DIV"]]))
                    {                    
                        foreach ($arr[$arTab["DIV"]] as $elements) 
                        {
                            if($elements["TYPE"] == "PROPERTY")
                            {
                                if(strpos($elements["CODE"], "PROPERTY_") !== false && strpos($elements["CODE"], "PROPERTY_") === 0)
                                {
                                    $code = str_replace("PROPERTY_", "", $elements["CODE"]);

                                    if(array_key_exists($arPropFields[$code], $arUser))
                                    {
                                        
                                        unset($arUser[$arPropFields[$code]]); 
                                    }
                                    
                                    elseif(array_key_exists($code, $arUser))
                                        unset($arUser[$code]); 
                                }
                            }
                        }
                    }
                }
            }
        }



        if( !empty($arUser) )
        {
            foreach ($arUser as $key => $items){

                if(in_array("PROPERTY_".$items["CODE"], $arrSkipFields))
                    unset($arUser[$key]);
            }
        }
        
        return $arUser;
    }

    public static function showTabContentUser($arUser)
    {
        global  $tabControl;

        if(!empty($arUser))
        {
            
            $tabControl->BeginNextFormTab();
        
            foreach ($arUser as $items)
                self::showHtmlField("PROPERTY_".$items["CODE"]);
        }

    }

    public static function showTabContent($arOurTabs, $arr)
    {

        global $tabControl;

        if(!empty($arOurTabs))
        {

            foreach ($arOurTabs as $arTab)
            {
                $tabControl->BeginNextFormTab();

                if(isset($arr[$arTab["DIV"]]))
                {
                    if(!empty($arr[$arTab["DIV"]]))
                    {
                        foreach ($arr[$arTab["DIV"]] as $elements) 
                        {
                            
                            if($elements["TYPE"] == "PROPERTY")
                                self::showHtmlField($elements["CODE"], $elements["NAME"], $elements["HIDDEN"]);
                            

                            if($elements["TYPE"] == "STRING")
                                $tabControl->AddSection($elements["ID"], $elements["NAME"]);
                            
                        }
                    }

                }
            }
        }
    }

    public static function showHtmlField($code, $title = "", $hidden = "")
    {
        

        global 

            $IBLOCK_ID,
            $arIBlock, 
            $str_ACTIVE, 
            $str_ACTIVE_FROM,
            $str_ACTIVE_TO,
            $str_SORT,
            $str_XML_ID,
            $str_NAME,
            $str_CODE,
            $str_PREVIEW_TEXT,
            $str_PREVIEW_TEXT_TYPE,
            $str_DETAIL_TEXT,
            $str_DETAIL_TEXT_TYPE,
            $str_IPROPERTY_TEMPLATES,
            $str_TAGS,
            $str_DETAIL_PICTURE,
            $str_PREVIEW_PICTURE,
            $str_IBLOCK_ELEMENT_SECTION,
            $str_IBLOCK_SECTION_ID,
            $bCatalog,
            $TMP_ID,
            $APPLICATION,
            $bCopy,
            $historyId,
            $bVarsFromForm,
            $bPropertyAjax,
            $PROP,
            $tabControl,
            $arTranslit,
            $arPropFields,
            $ID,
            $PREV_ID,
            $bWorkflow,
            $prev_arElement,
            $arElement,
            $bFileman;



        if($code == "ACTIVE")
        {
            $title_val = GetMessage("IBLOCK_FIELD_ACTIVE");

            if(strlen($title))
                $title_val = $title;


            $tabControl->AddCheckBoxField("ACTIVE", $title_val.":", false, array("Y","N"), $str_ACTIVE=="Y");
        }

        else if($code == "TAGS")
        {
            $tabControl->BeginCustomField("TAGS", GetMessage("IBLOCK_FIELD_TAGS").":", $arIBlock["FIELDS"]["TAGS"]["IS_REQUIRED"] === "Y");
            ?>
                <tr id="tr_TAGS">
                    <td>

                        <?
                        if(strlen($title))
                            echo $tabControl->GetCustomLabelHTML($code, $title);

                        else
                            echo $tabControl->GetCustomLabelHTML();
                    ?>

                        <?echo "<br>".GetMessage("IBLOCK_ELEMENT_EDIT_TAGS_TIP")?>

                    </td>
                    <td>
                        <?if(Bitrix\Main\Loader::includeModule('search')):
                            $arLID = array();
                            $rsSites = CIBlock::GetSite($IBLOCK_ID);
                            while($arSite = $rsSites->Fetch())
                                $arLID[] = $arSite["LID"];
                            echo InputTags("TAGS", htmlspecialcharsback($str_TAGS), $arLID, 'size="55"');
                        else:?>
                            <input type="text" size="20" name="TAGS" maxlength="255" value="<?echo $str_TAGS?>">
                        <?endif?>
                    </td>
                </tr>
            <?
            $tabControl->EndCustomField("TAGS",
                '<input type="hidden" name="TAGS" value="'.$str_TAGS.'">'
            );


        }

        else if($code == "SECTIONS")
        {
          
            if($arIBlock["FIELDS"]["IBLOCK_SECTION"]["DEFAULT_VALUE"]["KEEP_IBLOCK_SECTION_ID"] === "Y")
            {
                $arDropdown = array();
                if ($str_IBLOCK_ELEMENT_SECTION)
                {
                    $sectionList = CIBlockSection::GetList(
                        array("left_margin"=>"asc"),
                        array("=ID"=>$str_IBLOCK_ELEMENT_SECTION),
                        false,
                        array("ID", "NAME")
                    );
                    while ($section = $sectionList->Fetch())
                        $arDropdown[$section["ID"]] = htmlspecialcharsEx($section["NAME"]);
                }
                $tabControl->BeginCustomField("IBLOCK_ELEMENT_SECTION_ID", GetMessage("IBEL_E_MAIN_IBLOCK_SECTION_ID").":", false);
                ?>
                    <tr id="tr_IBLOCK_ELEMENT_SECTION_ID" style="<?=($hidden == "Y")?"display: none":"";?>;">
                        <td class="adm-detail-valign-top"><?echo $tabControl->GetCustomLabelHTML()?></td>
                        <td>
                            <div id="RESULT_IBLOCK_ELEMENT_SECTION_ID">
                            <select name="IBLOCK_ELEMENT_SECTION_ID" id="IBLOCK_ELEMENT_SECTION_ID" onchange="InheritedPropertiesTemplates.updateInheritedPropertiesValues(false, true)">

                            <?if(!empty($arDropdown)):?>
                                <?foreach($arDropdown as $key => $val):?>
                                    <option value="<?echo $key?>" <?if ($str_IBLOCK_SECTION_ID == $key) echo 'selected'?>><?echo $val?></option>
                                <?endforeach?>
                            <?endif;?>
                            </select>
                            </div>
                            <script type="text/javascript">
                                window.ipropTemplates[window.ipropTemplates.length] = {
                                    "ID": "IBLOCK_ELEMENT_SECTION_ID",
                                    "INPUT_ID": "IBLOCK_ELEMENT_SECTION_ID",
                                    "RESULT_ID": "RESULT_IBLOCK_ELEMENT_SECTION_ID",
                                    "TEMPLATE": ""
                                };
                                window.ipropTemplates[window.ipropTemplates.length] = {
                                    "ID": "CODE",
                                    "INPUT_ID": "CODE",
                                    "RESULT_ID": "",
                                    "TEMPLATE": ""
                                };
                                <?
                                if (COption::GetOptionString('iblock', 'show_xml_id') == 'Y')
                                {
                                ?>
                                window.ipropTemplates[window.ipropTemplates.length] = {
                                    "ID": "XML_ID",
                                    "INPUT_ID": "XML_ID",
                                    "RESULT_ID": "",
                                    "TEMPLATE": ""
                                };
                                <?
                                }
                                ?>
                            </script>
                        </td>
                    </tr>
                <?
                $tabControl->EndCustomField("IBLOCK_ELEMENT_SECTION_ID",
                    '<input type="hidden" name="IBLOCK_ELEMENT_SECTION_ID" id="IBLOCK_ELEMENT_SECTION_ID" value="'.$str_IBLOCK_SECTION_ID.'">'
                );
            }
           

            $tabControl->BeginCustomField("SECTIONS", GetMessage("IBLOCK_SECTION"), $arIBlock["FIELDS"]["IBLOCK_SECTION"]["IS_REQUIRED"] === "Y");?>

            
            <tr id="tr_SECTIONS" style="<?=($hidden == "Y")?"display: none":"";?>;">
                <?if($arIBlock["SECTION_CHOOSER"] != "D" && $arIBlock["SECTION_CHOOSER"] != "P"):?>

                    <?$l = CIBlockSection::GetTreeList(Array("IBLOCK_ID"=>$IBLOCK_ID), array("ID", "NAME", "DEPTH_LEVEL"));?>
                    <td width="40%" class="adm-detail-valign-top">

                        <?
                            if(strlen($title))
                                echo $tabControl->GetCustomLabelHTML($code, $title);

                            else
                                echo $tabControl->GetCustomLabelHTML();
                        ?>
                        
                    </td>
                    <td width="60%">
                    <select name="IBLOCK_SECTION[]" size="14" multiple onchange="onSectionChanged()">
                        <option value="0"<?if(is_array($str_IBLOCK_ELEMENT_SECTION) && in_array(0, $str_IBLOCK_ELEMENT_SECTION))echo " selected"?>><?echo GetMessage("IBLOCK_UPPER_LEVEL")?></option>
                    <?
                        while($ar_l = $l->GetNext()):
                            ?><option value="<?echo $ar_l["ID"]?>"<?if(is_array($str_IBLOCK_ELEMENT_SECTION) && in_array($ar_l["ID"], $str_IBLOCK_ELEMENT_SECTION))echo " selected"?>><?echo str_repeat(" . ", $ar_l["DEPTH_LEVEL"])?><?echo $ar_l["NAME"]?></option><?
                        endwhile;
                    ?>
                    </select>
                    </td>

                <?elseif($arIBlock["SECTION_CHOOSER"] == "D"):?>
                    <td width="40%" class="adm-detail-valign-top">

                        <?
                            if(strlen($title))
                                echo $tabControl->GetCustomLabelHTML($code, $title);

                            else
                                echo $tabControl->GetCustomLabelHTML();
                        ?>

                    </td>
                    <td width="60%">
                        <table class="internal" id="sections">
                        <?
                        if(is_array($str_IBLOCK_ELEMENT_SECTION))
                        {
                            $i = 0;

                            if(!empty($str_IBLOCK_ELEMENT_SECTION))
                            {
                                foreach($str_IBLOCK_ELEMENT_SECTION as $section_id)
                                {
                                    $rsChain = CIBlockSection::GetNavChain($IBLOCK_ID, $section_id);
                                    $strPath = "";
                                    while($arChain = $rsChain->GetNext())
                                        $strPath .= $arChain["NAME"]."&nbsp;/&nbsp;";
                                    if(strlen($strPath) > 0)
                                    {
                                        ?><tr>
                                            <td nowrap><?echo $strPath?></td>
                                            <td>
                                            <input type="button" value="<?echo GetMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">
                                            <input type="hidden" name="IBLOCK_SECTION[]" value="<?echo intval($section_id)?>">
                                            </td>
                                        </tr><?
                                    }
                                    $i++;
                                }
                            }
                        }
                        ?>
                        <tr>
                            <td>
                            <script type="text/javascript">
                            function deleteRow(button)
                            {
                                var my_row = button.parentNode.parentNode;
                                var table = document.getElementById('sections');
                                if(table)
                                {
                                    for(var i=0; i<table.rows.length; i++)
                                    {
                                        if(table.rows[i] == my_row)
                                        {
                                            table.deleteRow(i);
                                            onSectionChanged();
                                        }
                                    }
                                }
                            }
                            function addPathRow()
                            {
                                var table = document.getElementById('sections');
                                if(table)
                                {
                                    var section_id = 0;
                                    var html = '';
                                    var lev = 0;
                                    var oSelect;
                                    while(oSelect = document.getElementById('select_IBLOCK_SECTION_'+lev))
                                    {
                                        if(oSelect.value < 1)
                                            break;
                                        html += oSelect.options[oSelect.selectedIndex].text+'&nbsp;/&nbsp;';
                                        section_id = oSelect.value;
                                        lev++;
                                    }
                                    if(section_id > 0)
                                    {
                                        var cnt = table.rows.length;
                                        var oRow = table.insertRow(cnt-1);

                                        var i=0;
                                        var oCell = oRow.insertCell(i++);
                                        oCell.innerHTML = html;

                                        var oCell = oRow.insertCell(i++);
                                        oCell.innerHTML =
                                            '<input type="button" value="<?echo GetMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">'+
                                            '<input type="hidden" name="IBLOCK_SECTION[]" value="'+section_id+'">';
                                        onSectionChanged();
                                    }
                                }
                            }
                            function find_path(item, value)
                            {
                                if(item.id==value)
                                {
                                    var a = Array(1);
                                    a[0] = item.id;
                                    return a;
                                }
                                else
                                {
                                    for(var s in item.children)
                                    {
                                        if(ar = find_path(item.children[s], value))
                                        {
                                            var a = Array(1);
                                            a[0] = item.id;
                                            return a.concat(ar);
                                        }
                                    }
                                    return null;
                                }
                            }
                            function find_children(level, value, item)
                            {
                                if(level==-1 && item.id==value)
                                    return item;
                                else
                                {
                                    for(var s in item.children)
                                    {
                                        if(ch = find_children(level-1,value,item.children[s]))
                                            return ch;
                                    }
                                    return null;
                                }
                            }
                            function change_selection(name_prefix, prop_id,value,level,id)
                            {
                                var lev = level+1;
                                var oSelect;

                                while(oSelect = document.getElementById(name_prefix+lev))
                                {
                                    jsSelectUtils.deleteAllOptions(oSelect);
                                    jsSelectUtils.addNewOption(oSelect, '0', '(<?echo GetMessage("MAIN_NO")?>)');
                                    lev++;
                                }

                                oSelect = document.getElementById(name_prefix+(level+1))
                                if(oSelect && (value!=0||level==-1))
                                {
                                    var item = find_children(level,value,window['sectionListsFor'+prop_id]);
                                    for(var s in item.children)
                                    {
                                        var obj = item.children[s];
                                        jsSelectUtils.addNewOption(oSelect, obj.id, obj.name, true);
                                    }
                                }
                                if(document.getElementById(id))
                                    document.getElementById(id).value = value;
                            }
                            function init_selection(name_prefix, prop_id, value, id)
                            {
                                var a = find_path(window['sectionListsFor'+prop_id], value);
                                change_selection(name_prefix, prop_id, 0, -1, id);
                                for(var i=1;i<a.length;i++)
                                {
                                    if(oSelect = document.getElementById(name_prefix+(i-1)))
                                    {
                                        for(var j=0;j<oSelect.length;j++)
                                        {
                                            if(oSelect[j].value==a[i])
                                            {
                                                oSelect[j].selected=true;
                                                break;
                                            }
                                        }
                                    }
                                    change_selection(name_prefix, prop_id, a[i], i-1, id);
                                }
                            }
                            var sectionListsFor0 = {id:0,name:'',children:Array()};

                            <?
                            $rsItems = CIBlockSection::GetTreeList(Array("IBLOCK_ID"=>$IBLOCK_ID), array("ID", "NAME", "DEPTH_LEVEL"));
                            $depth = 0;
                            $max_depth = 0;
                            $arChain = array();
                            while($arItem = $rsItems->GetNext())
                            {
                                if($max_depth < $arItem["DEPTH_LEVEL"])
                                {
                                    $max_depth = $arItem["DEPTH_LEVEL"];
                                }
                                if($depth < $arItem["DEPTH_LEVEL"])
                                {
                                    $arChain[]=$arItem["ID"];

                                }
                                while($depth > $arItem["DEPTH_LEVEL"])
                                {
                                    array_pop($arChain);
                                    $depth--;
                                }
                                $arChain[count($arChain)-1] = $arItem["ID"];
                                echo "sectionListsFor0";

                                if(!empty($arChain))
                                {
                                    foreach($arChain as $i)
                                        echo ".children['".intval($i)."']";
                                }

                                echo " = { id : ".$arItem["ID"].", name : '".CUtil::JSEscape($arItem["NAME"])."', children : Array() };\n";
                                $depth = $arItem["DEPTH_LEVEL"];
                            }
                            ?>
                            </script>
                            <?
                            for($i = 0; $i < $max_depth; $i++)
                                echo '<select id="select_IBLOCK_SECTION_'.$i.'" onchange="change_selection(\'select_IBLOCK_SECTION_\',  0, this.value, '.$i.', \'IBLOCK_SECTION[n'.$key.']\')"><option value="0">('.GetMessage("MAIN_NO").')</option></select>&nbsp;';
                            ?>
                            <script type="text/javascript">
                                init_selection('select_IBLOCK_SECTION_', 0, '', 0);
                            </script>
                            </td>
                            <td><input type="button" value="<?echo GetMessage("IBLOCK_ELEMENT_EDIT_PROP_ADD")?>" onClick="addPathRow()"></td>
                        </tr>
                        </table>
                    </td>

                <?else:?>
                    <td width="40%" class="adm-detail-valign-top">
                        <?
                            if(strlen($title))
                                echo $tabControl->GetCustomLabelHTML($code, $title);

                            else
                                echo $tabControl->GetCustomLabelHTML();
                        ?>
                    </td>
                    <td width="60%">
                        <table id="sections" class="internal">
                        <?
                        if(is_array($str_IBLOCK_ELEMENT_SECTION))
                        {
                            $i = 0;

                            if(!empty($str_IBLOCK_ELEMENT_SECTION))
                            {
                                foreach($str_IBLOCK_ELEMENT_SECTION as $section_id)
                                {
                                    $rsChain = CIBlockSection::GetNavChain($IBLOCK_ID, $section_id);
                                    $strPath = "";
                                    while($arChain = $rsChain->GetNext())
                                        $strPath .= $arChain["NAME"]."&nbsp;/&nbsp;";
                                    if(strlen($strPath) > 0)
                                    {
                                        ?><tr>
                                            <td><?echo $strPath?></td>
                                            <td>
                                            <input type="button" value="<?echo GetMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">
                                            <input type="hidden" name="IBLOCK_SECTION[]" value="<?echo intval($section_id)?>">
                                            </td>
                                        </tr><?
                                    }
                                    $i++;
                                }
                            }
                        }
                        ?>
                        </table>
                            <script type="text/javascript">
                            function deleteRow(button)
                            {
                                var my_row = button.parentNode.parentNode;
                                var table = document.getElementById('sections');
                                if(table)
                                {
                                    for(var i=0; i<table.rows.length; i++)
                                    {
                                        if(table.rows[i] == my_row)
                                        {
                                            table.deleteRow(i);
                                            onSectionChanged();
                                        }
                                    }
                                }
                            }
                            function InS<?echo md5("input_IBLOCK_SECTION")?>(section_id, html)
                            {
                                var table = document.getElementById('sections');
                                if(table)
                                {
                                    if(section_id > 0 && html)
                                    {
                                        var cnt = table.rows.length;
                                        var oRow = table.insertRow(cnt-1);

                                        var i=0;
                                        var oCell = oRow.insertCell(i++);
                                        oCell.innerHTML = html;

                                        var oCell = oRow.insertCell(i++);
                                        oCell.innerHTML =
                                            '<input type="button" value="<?echo GetMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">'+
                                            '<input type="hidden" name="IBLOCK_SECTION[]" value="'+section_id+'">';
                                        onSectionChanged();
                                    }
                                }
                            }
                            </script>
                            <input name="input_IBLOCK_SECTION" id="input_IBLOCK_SECTION" type="hidden">
                            <input type="button" value="<?echo GetMessage("IBLOCK_ELEMENT_EDIT_PROP_ADD")?>..." onClick="jsUtils.OpenWindow('/bitrix/admin/iblock_section_search.php?lang=<?echo LANGUAGE_ID?>&amp;IBLOCK_ID=<?echo $IBLOCK_ID?>&amp;n=input_IBLOCK_SECTION&amp;m=y&amp;iblockfix=y&amp;tableId=iblocksection-<?=$IBLOCK_ID; ?>', 900, 700);">
                    </td>
                <?endif;?>
            </tr>

            <input type="hidden" name="IBLOCK_SECTION[]" value="">



            <script type="text/javascript">
            function onSectionChanged()
            {

                
                <?
                $additionalParams = '';
                if ($bCatalog)
                {
                    $catalogParams = array('TMP_ID' => $TMP_ID);
                    CCatalogAdminTools::addTabParams($catalogParams);
                    if (!empty($catalogParams))
                    {
                        foreach ($catalogParams as $name => $value)
                        {
                            if ($additionalParams != '')
                                $additionalParams .= '&';
                            $additionalParams .= urlencode($name) . "=" . urlencode($value);
                        }
                        unset($name, $value);
                    }
                    unset($catalogParams);
                }
                ?>


                var form = BX('<?echo CUtil::JSEscape($tabControl->GetFormName())?>'),
                    url = '<?echo CUtil::JSEscape($APPLICATION->GetCurPageParam($additionalParams))?>',
                    selectedTab = BX(s='<?echo CUtil::JSEscape("form_element_".$IBLOCK_ID."_active_tab")?>'),
                    groupField;
    
                if (selectedTab && selectedTab.value)
                {
                    url += '&<?echo CUtil::JSEscape("form_element_".$IBLOCK_ID."_active_tab")?>=' + selectedTab.value;
                }
                <?if($arIBlock["SECTION_PROPERTY"] === "Y" || defined("CATALOG_PRODUCT")):?>
                groupField = new JCIBlockGroupField(form, 'tr_IBLOCK_ELEMENT_PROPERTY', url);
                groupField.reload();
                <?endif;
                if($arIBlock["FIELDS"]["IBLOCK_SECTION"]["DEFAULT_VALUE"]["KEEP_IBLOCK_SECTION_ID"] === "Y"):?>
                InheritedPropertiesTemplates.updateInheritedPropertiesValues(false, true);
                <?endif?>

            }
            </script>

            <? 
            $hidden = "";
            if(is_array($str_IBLOCK_ELEMENT_SECTION))
            {
                if(!empty($str_IBLOCK_ELEMENT_SECTION))
                {
                    foreach($str_IBLOCK_ELEMENT_SECTION as $section_id)
                        $hidden .= '<input type="hidden" name="IBLOCK_SECTION[]" value="'.intval($section_id).'">';  
                }
            }
               
            $tabControl->EndCustomField("SECTIONS", $hidden);
        }


        else if($code == "IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE")
        {
            $tabControl->BeginCustomField("IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE", GetMessage("IBEL_E_SEO_ELEMENT_TITLE"));
            ?>
            <tr class="adm-detail-valign-top" style='<?=($hidden == "Y")?"display: none;":"";?>'>
                <td width="40%">
                    <?
                        if(strlen($title))
                            echo $tabControl->GetCustomLabelHTML($code, $title);

                        else
                            echo $tabControl->GetCustomLabelHTML();
                    ?>
                        
                </td>
                <td width="60%"><?echo IBlockInheritedPropertyInput($IBLOCK_ID, "ELEMENT_PAGE_TITLE", $str_IPROPERTY_TEMPLATES, "E", GetMessage("IBEL_E_SEO_OVERWRITE"))?></td>


                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_PREVIEW_PICTURE_FILE_ALT][TEMPLATE]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]["TEMPLATE"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_PREVIEW_PICTURE_FILE_ALT][INHERITED]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]["INHERITED"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_PREVIEW_PICTURE_FILE_TITLE][TEMPLATE]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]["TEMPLATE"]?>">


                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_PREVIEW_PICTURE_FILE_TITLE][INHERITED]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]["INHERITED"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_PREVIEW_PICTURE_FILE_NAME][TEMPLATE]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_PREVIEW_PICTURE_FILE_NAME"]["TEMPLATE"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_PREVIEW_PICTURE_FILE_NAME][INHERITED]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_PREVIEW_PICTURE_FILE_NAME"]["INHERITED"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_PREVIEW_PICTURE_FILE_NAME][LOWER]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_PREVIEW_PICTURE_FILE_NAME"]["LOWER"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_PREVIEW_PICTURE_FILE_NAME][TRANSLIT]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_PREVIEW_PICTURE_FILE_NAME"]["TRANSLIT"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_PREVIEW_PICTURE_FILE_NAME][SPACE]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_PREVIEW_PICTURE_FILE_NAME"]["SPACE"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_DETAIL_PICTURE_FILE_ALT][TEMPLATE]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_DETAIL_PICTURE_FILE_ALT"]["TEMPLATE"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_DETAIL_PICTURE_FILE_ALT][INHERITED]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_DETAIL_PICTURE_FILE_ALT"]["INHERITED"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_DETAIL_PICTURE_FILE_TITLE][TEMPLATE]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]["TEMPLATE"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_DETAIL_PICTURE_FILE_TITLE][INHERITED]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]["INHERITED"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_DETAIL_PICTURE_FILE_NAME][TEMPLATE]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_DETAIL_PICTURE_FILE_NAME"]["TEMPLATE"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_DETAIL_PICTURE_FILE_NAME][INHERITED]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_DETAIL_PICTURE_FILE_NAME"]["INHERITED"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_DETAIL_PICTURE_FILE_NAME][LOWER]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_DETAIL_PICTURE_FILE_NAME"]["LOWER"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_DETAIL_PICTURE_FILE_NAME][TRANSLIT]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_DETAIL_PICTURE_FILE_NAME"]["TRANSLIT"]?>">

                <input type="hidden" name="IPROPERTY_TEMPLATES[ELEMENT_DETAIL_PICTURE_FILE_NAME][SPACE]" value="<?=$str_IPROPERTY_TEMPLATES["ELEMENT_DETAIL_PICTURE_FILE_NAME"]["SPACE"]?>">


                <input type="hidden" name="TAGS" value="<?=$str_TAGS?>">
            </tr>
            <?
            $tabControl->EndCustomField("IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE",
                IBlockInheritedPropertyHidden($IBLOCK_ID, "ELEMENT_PAGE_TITLE", $str_IPROPERTY_TEMPLATES, "E", GetMessage("IBEL_E_SEO_OVERWRITE"))
            );
        




        }

        else if($code == "IPROPERTY_TEMPLATES_ELEMENT_META_TITLE")
        {
            $tabControl->BeginCustomField("IPROPERTY_TEMPLATES_ELEMENT_META_TITLE", GetMessage("IBEL_E_SEO_META_TITLE"));
            ?>
            <tr class="adm-detail-valign-top" style='<?=($hidden == "Y")?"display: none;":"";?>'>
                <td width="40%">

                    <?
                        if(strlen($title))
                            echo $tabControl->GetCustomLabelHTML($code, $title);

                        else
                            echo $tabControl->GetCustomLabelHTML();
                    ?>
                        
                </td>
                <td width="60%"><?echo IBlockInheritedPropertyInput($IBLOCK_ID, "ELEMENT_META_TITLE", $str_IPROPERTY_TEMPLATES, "E", GetMessage("IBEL_E_SEO_OVERWRITE"))?></td>
            </tr>
            <?
            $tabControl->EndCustomField("IPROPERTY_TEMPLATES_ELEMENT_META_TITLE",
                IBlockInheritedPropertyHidden($IBLOCK_ID, "ELEMENT_META_TITLE", $str_IPROPERTY_TEMPLATES, "E", GetMessage("IBEL_E_SEO_OVERWRITE"))
            );
        }

        else if($code == "IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION")
        {
            $tabControl->BeginCustomField("IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION", GetMessage("IBEL_E_SEO_META_DESCRIPTION"));
            ?>
            <tr class="adm-detail-valign-top" style='<?=($hidden == "Y")?"display: none;":"";?>'>
                <td width="40%">

                    <?
                        if(strlen($title))
                            echo $tabControl->GetCustomLabelHTML($code, $title);

                        else
                            echo $tabControl->GetCustomLabelHTML();
                    ?>
                        
                </td>
                <td width="60%"><?echo IBlockInheritedPropertyInput($IBLOCK_ID, "ELEMENT_META_DESCRIPTION", $str_IPROPERTY_TEMPLATES, "E", GetMessage("IBEL_E_SEO_OVERWRITE"))?></td>
            </tr>
            <?
            $tabControl->EndCustomField("IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION",
                IBlockInheritedPropertyHidden($IBLOCK_ID, "ELEMENT_META_DESCRIPTION", $str_IPROPERTY_TEMPLATES, "E", GetMessage("IBEL_E_SEO_OVERWRITE"))
            );
        }


        else if($code == "IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS")
        {
            $tabControl->BeginCustomField("IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS", GetMessage("IBEL_E_SEO_META_KEYWORDS"));
            ?>
            <tr class="adm-detail-valign-top" style='<?=($hidden == "Y")?"display: none;":"";?>'>
                <td width="40%">

                    <?

                        if(strlen($title))
                            echo $tabControl->GetCustomLabelHTML($code, $title);

                        else
                            echo $tabControl->GetCustomLabelHTML();
                    ?>
                        
                </td>
                <td width="60%"><?echo IBlockInheritedPropertyInput($IBLOCK_ID, "ELEMENT_META_KEYWORDS", $str_IPROPERTY_TEMPLATES, "E", GetMessage("IBEL_E_SEO_OVERWRITE"))?></td>
            </tr>
            <?
            $tabControl->EndCustomField("IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS",
                IBlockInheritedPropertyHidden($IBLOCK_ID, "ELEMENT_META_KEYWORDS", $str_IPROPERTY_TEMPLATES, "E", GetMessage("IBEL_E_SEO_OVERWRITE"))
            );
        }

        else if($code == "DETAIL_TEXT")
        {
            $tabControl->BeginCustomField("DETAIL_TEXT", GetMessage("IBLOCK_FIELD_DETAIL_TEXT"), $arIBlock["FIELDS"]["DETAIL_TEXT"]["IS_REQUIRED"] === "Y");
            ?>
                <tr class="heading" id="tr_DETAIL_TEXT_LABEL" style='<?=($hidden == "Y")?"display: none;":"";?>'>
                    <td colspan="2">
                        
                        <?
                            if(strlen($title))
                                echo $tabControl->GetCustomLabelHTML($code, $title);

                            else
                                echo $tabControl->GetCustomLabelHTML();

                        ?>
                            
                    </td>
                </tr>
                <?if($ID && $PREV_ID && $bWorkflow):?>
                <tr id="tr_DETAIL_TEXT_DIFF">
                    <td colspan="2">
                        <div style="width:95%;background-color:white;border:1px solid black;padding:5px">
                            <?echo getDiff($prev_arElement["DETAIL_TEXT"], $arElement["DETAIL_TEXT"])?>
                        </div>
                    </td>
                </tr>
                <?elseif(COption::GetOptionString("iblock", "use_htmledit", "Y")=="Y" && $bFileman):?>
                <tr id="tr_DETAIL_TEXT_EDITOR">
                    <td colspan="2" align="center">
                        <?CFileMan::AddHTMLEditorFrame(
                            "DETAIL_TEXT",
                            $str_DETAIL_TEXT,
                            "DETAIL_TEXT_TYPE",
                            $str_DETAIL_TEXT_TYPE,
                            array(
                                'height' => 450,
                                'width' => '100%'
                            ),
                            "N",
                            0,
                            "",
                            "",
                            $arIBlock["LID"],
                            true,
                            false,
                            array(
                                'toolbarConfig' => CFileMan::GetEditorToolbarConfig("iblock_".(defined('BX_PUBLIC_MODE') && BX_PUBLIC_MODE == 1 ? 'public' : 'admin')),
                                'saveEditorKey' => $IBLOCK_ID,
                                'hideTypeSelector' => $arIBlock["FIELDS"]["DETAIL_TEXT_TYPE_ALLOW_CHANGE"]["DEFAULT_VALUE"] === "N",
                            )
                        );
                    ?></td>
                </tr>
                <?else:?>
                <tr id="tr_DETAIL_TEXT_TYPE">
                    <td><?echo GetMessage("IBLOCK_DESC_TYPE")?></td>
                    <td>
                        <?if($arIBlock["FIELDS"]["DETAIL_TEXT_TYPE_ALLOW_CHANGE"]["DEFAULT_VALUE"] === "N"):?>
                            <input type="hidden" name="DETAIL_TEXT_TYPE" value="<?echo $str_DETAIL_TEXT_TYPE?>"><?echo $str_DETAIL_TEXT_TYPE!="html"? GetMessage("IBLOCK_DESC_TYPE_TEXT"): GetMessage("IBLOCK_DESC_TYPE_HTML")?>
                        <?else:?>
                            <input type="radio" name="DETAIL_TEXT_TYPE" id="DETAIL_TEXT_TYPE_text" value="text"<?if($str_DETAIL_TEXT_TYPE!="html")echo " checked"?>> <label for="DETAIL_TEXT_TYPE_text"><?echo GetMessage("IBLOCK_DESC_TYPE_TEXT")?></label> / <input type="radio" name="DETAIL_TEXT_TYPE" id="DETAIL_TEXT_TYPE_html" value="html"<?if($str_DETAIL_TEXT_TYPE=="html")echo " checked"?>> <label for="DETAIL_TEXT_TYPE_html"><?echo GetMessage("IBLOCK_DESC_TYPE_HTML")?></label>
                        <?endif?>
                    </td>
                </tr>
                <tr id="tr_DETAIL_TEXT">
                    <td colspan="2" align="center">
                        <textarea cols="60" rows="20" name="DETAIL_TEXT" style="width:100%"><?echo $str_DETAIL_TEXT?></textarea>
                    </td>
                </tr>
                <?endif?>
            <?
            $tabControl->EndCustomField("DETAIL_TEXT",
                '<input type="hidden" name="DETAIL_TEXT" value="'.$str_DETAIL_TEXT.'">'.
                '<input type="hidden" name="DETAIL_TEXT_TYPE" value="'.$str_DETAIL_TEXT_TYPE.'">'
            );
        }

        else if($code == "PREVIEW_TEXT")
        {
            $tabControl->BeginCustomField("PREVIEW_TEXT", GetMessage("IBLOCK_FIELD_PREVIEW_TEXT"), $arIBlock["FIELDS"]["PREVIEW_TEXT"]["IS_REQUIRED"] === "Y");
            ?>
                <tr class="heading" id="tr_PREVIEW_TEXT_LABEL" style='<?=($hidden == "Y")?"display: none;":"";?>'>
                    <td colspan="2">

                        <?
                            if(strlen($title))
                                echo $tabControl->GetCustomLabelHTML($code, $title);

                            else
                                echo $tabControl->GetCustomLabelHTML();
                        ?>
                            

                        </td>
                </tr>
                <?if($ID && $PREV_ID && $bWorkflow):?>
                <tr id="tr_PREVIEW_TEXT_DIFF">
                    <td colspan="2">
                        <div style="width:95%;background-color:white;border:1px solid black;padding:5px">
                            <?echo getDiff($prev_arElement["PREVIEW_TEXT"], $arElement["PREVIEW_TEXT"])?>
                        </div>
                    </td>
                </tr>
                <?elseif(COption::GetOptionString("iblock", "use_htmledit", "Y")=="Y" && $bFileman):?>
                <tr id="tr_PREVIEW_TEXT_EDITOR">
                    <td colspan="2" align="center">
                        <?CFileMan::AddHTMLEditorFrame(
                        "PREVIEW_TEXT",
                        $str_PREVIEW_TEXT,
                        "PREVIEW_TEXT_TYPE",
                        $str_PREVIEW_TEXT_TYPE,
                        array(
                            'height' => 450,
                            'width' => '100%'
                        ),
                        "N",
                        0,
                        "",
                        "",
                        $arIBlock["LID"],
                        true,
                        false,
                        array(
                            'toolbarConfig' => CFileMan::GetEditorToolbarConfig("iblock_".(defined('BX_PUBLIC_MODE') && BX_PUBLIC_MODE == 1 ? 'public' : 'admin')),
                            'saveEditorKey' => $IBLOCK_ID,
                            'hideTypeSelector' => $arIBlock["FIELDS"]["PREVIEW_TEXT_TYPE_ALLOW_CHANGE"]["DEFAULT_VALUE"] === "N",
                        )
                        );?>
                    </td>
                </tr>
                <?else:?>
                <tr id="tr_PREVIEW_TEXT_TYPE">
                    <td><?echo GetMessage("IBLOCK_DESC_TYPE")?></td>
                    <td>
                        <?if($arIBlock["FIELDS"]["PREVIEW_TEXT_TYPE_ALLOW_CHANGE"]["DEFAULT_VALUE"] === "N"):?>
                            <input type="hidden" name="PREVIEW_TEXT_TYPE" value="<?echo $str_PREVIEW_TEXT_TYPE?>"><?echo $str_PREVIEW_TEXT_TYPE!="html"? GetMessage("IBLOCK_DESC_TYPE_TEXT"): GetMessage("IBLOCK_DESC_TYPE_HTML")?>
                        <?else:?>
                            <input type="radio" name="PREVIEW_TEXT_TYPE" id="PREVIEW_TEXT_TYPE_text" value="text"<?if($str_PREVIEW_TEXT_TYPE!="html")echo " checked"?>> <label for="PREVIEW_TEXT_TYPE_text"><?echo GetMessage("IBLOCK_DESC_TYPE_TEXT")?></label> / <input type="radio" name="PREVIEW_TEXT_TYPE" id="PREVIEW_TEXT_TYPE_html" value="html"<?if($str_PREVIEW_TEXT_TYPE=="html")echo " checked"?>> <label for="PREVIEW_TEXT_TYPE_html"><?echo GetMessage("IBLOCK_DESC_TYPE_HTML")?></label>
                        <?endif?>
                    </td>
                </tr>
                <tr id="tr_PREVIEW_TEXT">
                    <td colspan="2" align="center">
                        <textarea cols="60" rows="10" name="PREVIEW_TEXT" style="width:100%"><?echo $str_PREVIEW_TEXT?></textarea>
                    </td>
                </tr>
                <?endif;
            $tabControl->EndCustomField("PREVIEW_TEXT",
                '<input type="hidden" name="PREVIEW_TEXT" value="'.$str_PREVIEW_TEXT.'">'.
                '<input type="hidden" name="PREVIEW_TEXT_TYPE" value="'.$str_PREVIEW_TEXT_TYPE.'">'
            );
        }

        else if($code == "ACTIVE_FROM")
        {
            $tabControl->BeginCustomField("ACTIVE_FROM", GetMessage("IBLOCK_FIELD_ACTIVE_PERIOD_FROM"), $arIBlock["FIELDS"]["ACTIVE_FROM"]["IS_REQUIRED"] === "Y");
            ?>
                <tr id="tr_ACTIVE_FROM" style='<?=($hidden == "Y")?"display: none;":"";?>'>
                    <td>
                        <?

                            if(strlen($title))
                                echo $tabControl->GetCustomLabelHTML($code, $title);

                            else
                                echo $tabControl->GetCustomLabelHTML();

                        ?>:
                    </td>
                    <td><?echo CAdminCalendar::CalendarDate("ACTIVE_FROM", $str_ACTIVE_FROM, 19, true)?></td>
                </tr>
            <?
            $tabControl->EndCustomField("ACTIVE_FROM", '<input type="hidden" id="ACTIVE_FROM" name="ACTIVE_FROM" value="'.$str_ACTIVE_FROM.'">');
        }

        else if($code == "ACTIVE_TO")
        {
            $tabControl->BeginCustomField("ACTIVE_TO", GetMessage("IBLOCK_FIELD_ACTIVE_PERIOD_TO"), $arIBlock["FIELDS"]["ACTIVE_TO"]["IS_REQUIRED"] === "Y");
            ?>
                <tr id="tr_ACTIVE_TO" style='<?=($hidden == "Y")?"display: none;":"";?>'>
                    <td>
                        <?
                            if(strlen($title))
                                echo $tabControl->GetCustomLabelHTML($code, $title);

                            else
                                echo $tabControl->GetCustomLabelHTML();
                        ?>:
                    </td>
                    <td><?echo CAdminCalendar::CalendarDate("ACTIVE_TO", $str_ACTIVE_TO, 19, true)?></td>
                </tr>

            <?
            $tabControl->EndCustomField("ACTIVE_TO", '<input type="hidden" id="ACTIVE_TO" name="ACTIVE_TO" value="'.$str_ACTIVE_TO.'">');
        }

        else if($code == "SORT")
        {

            $title_val = GetMessage("IBLOCK_FIELD_SORT");

            if(strlen($title) > 0)
                $title_val = $title;

                

            $tabControl->AddEditField("SORT", $title_val.":", $arIBlock["FIELDS"]["SORT"]["IS_REQUIRED"] === "Y", array("size" => 7, "maxlength" => 10), $str_SORT);
        }


        else if($code == "XML_ID")
        {

            $title_val = GetMessage("IBLOCK_FIELD_XML_ID");

            if(strlen($title) > 0)
                $title_val = $title;

                

            $tabControl->AddEditField("XML_ID", $title_val. ":", $arIBlock["FIELDS"]["XML_ID"]["IS_REQUIRED"] === "Y", array("size" => 20, "maxlength" => 255, "id" => "XML_ID"), $str_XML_ID);
        }
        
        else if($code == "NAME")
        {
            if($arTranslit["TRANSLITERATION"] == "Y")
            {
                $tabControl->BeginCustomField("NAME", GetMessage("IBLOCK_FIELD_NAME").":", true);
                ?>
                    <tr id="tr_NAME" style='<?=($hidden == "Y")?"display: none;":"";?>'>
                        <td>
                            
                            <?
                                if(strlen($title) > 0)
                                    echo $tabControl->GetCustomLabelHTML($code, $title);

                                else
                                    echo $tabControl->GetCustomLabelHTML();
                            ?>
                                
                        </td>
                        <td style="white-space: nowrap;">
                            <input type="text" size="50" name="NAME" id="NAME" maxlength="255" value="<?echo $str_NAME?>"><img id="name_link" title="<?echo GetMessage("IBEL_E_LINK_TIP")?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?if($bLinked) echo 'link.gif'; else echo 'unlink.gif';?>" onclick="set_linked()" />
                        </td>
                    </tr>
                <?
                $tabControl->EndCustomField("NAME",
                    '<input type="hidden" name="NAME" id="NAME" value="'.$str_NAME.'">'
                );

                $tabControl->BeginCustomField("CODE", GetMessage("IBLOCK_FIELD_CODE").":", $arIBlock["FIELDS"]["CODE"]["IS_REQUIRED"] === "Y");
                ?>
                    <tr id="tr_CODE">
                        <td>
                            
                            <?
                                if(strlen($title))
                                    echo $tabControl->GetCustomLabelHTML($code, $title);

                                else
                                    echo $tabControl->GetCustomLabelHTML();
                            ?>
                                
                        </td>
                        <td style="white-space: nowrap;">
                            <input type="text" size="50" name="CODE" id="CODE" maxlength="255" value="<?echo $str_CODE?>"><img id="code_link" title="<?echo GetMessage("IBEL_E_LINK_TIP")?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?if($bLinked) echo 'link.gif'; else echo 'unlink.gif';?>" onclick="set_linked()" />
                        </td>
                    </tr>
                <?
                $tabControl->EndCustomField("CODE",
                    '<input type="hidden" name="CODE" id="CODE" value="'.$str_CODE.'">'
                );
            }

            else
            {

                $title_val = GetMessage("IBLOCK_FIELD_NAME");

                if(strlen($title) > 0)
                    $title_val = $title;
                    
            
                $tabControl->AddEditField("NAME", $title_val.":", true, array("size" => 50, "maxlength" => 255), $str_NAME);
            }
        }

        else if($code == "CODE")
        {
            if($arTranslit["TRANSLITERATION"] != "Y")
            {

                $title_val = GetMessage("IBLOCK_FIELD_CODE");
                
                if(strlen($title) > 0)
                    $title_val = $title;
                    

                $tabControl->AddEditField("CODE", $title_val.":", $arIBlock["FIELDS"]["CODE"]["IS_REQUIRED"] === "Y", array("size" => 20, "maxlength" => 255), $str_CODE);
            }
        }

        else if($code == "PREVIEW_PICTURE")
        {
            $tabControl->BeginCustomField("PREVIEW_PICTURE", GetMessage("IBLOCK_FIELD_PREVIEW_PICTURE"), $arIBlock["FIELDS"]["PREVIEW_PICTURE"]["IS_REQUIRED"] === "Y");
            if($bVarsFromForm && !array_key_exists("PREVIEW_PICTURE", $_REQUEST) && $arElement)
                $str_PREVIEW_PICTURE = intval($arElement["PREVIEW_PICTURE"]);
            ?>
                <tr id="tr_PREVIEW_PICTURE" class="adm-detail-file-row" style="<?=($hidden == "Y")?"display: none":"";?>;">
                    <td width="40%" class="adm-detail-valign-top">

                        <?
                            if(strlen($title))
                                echo $tabControl->GetCustomLabelHTML($code, $title);

                            else
                                echo $tabControl->GetCustomLabelHTML();
                        ?>

                    :</td>
                    <td width="60%">
                        <?if($historyId > 0):?>
                            <?echo CFileInput::Show("PREVIEW_PICTURE", $str_PREVIEW_PICTURE, array(
                                "IMAGE" => "Y",
                                "PATH" => "Y",
                                "FILE_SIZE" => "Y",
                                "DIMENSIONS" => "Y",
                                "IMAGE_POPUP" => "Y",
                                "MAX_SIZE" => array(
                                    "W" => COption::GetOptionString("iblock", "detail_image_size"),
                                    "H" => COption::GetOptionString("iblock", "detail_image_size"),
                                ),
                            ));
                            ?>
                        <?else:?>
                            <?
                            if (class_exists('\Bitrix\Main\UI\FileInput', true))
                            {
                                echo \Bitrix\Main\UI\FileInput::createInstance(array(
                                        "name" => "PREVIEW_PICTURE",
                                        "description" => true,
                                        "upload" => true,
                                        "allowUpload" => "I",
                                        "medialib" => false,
                                        "fileDialog" => true,
                                        "cloud" => true,
                                        "delete" => true,
                                        "maxCount" => 1
                                    ))->show(($bVarsFromForm ? $_REQUEST["PREVIEW_PICTURE"] : ($ID > 0 && !$bCopy ? $str_PREVIEW_PICTURE: 0)), $bVarsFromForm);
                            }
                            else
                            {
                                echo CFileInput::Show("PREVIEW_PICTURE", ($ID > 0 && !$bCopy? $str_PREVIEW_PICTURE: 0),
                                    array(
                                        "IMAGE" => "Y",
                                        "PATH" => "Y",
                                        "FILE_SIZE" => "Y",
                                        "DIMENSIONS" => "Y",
                                        "IMAGE_POPUP" => "Y",
                                        "MAX_SIZE" => array(
                                            "W" => COption::GetOptionString("iblock", "detail_image_size"),
                                            "H" => COption::GetOptionString("iblock", "detail_image_size"),
                                        ),
                                    ), array(
                                        'upload' => true,
                                        'medialib' => false,
                                        'file_dialog' => true,
                                        'cloud' => true,
                                        'del' => true,
                                        'description' => true,
                                    )
                                );
                            }
                            ?>
                        <?endif?>
                    </td>
                </tr>
            <?
            $tabControl->EndCustomField("PREVIEW_PICTURE", "");
        }

        else if($code == "DETAIL_PICTURE")
        {
            $tabControl->BeginCustomField("DETAIL_PICTURE", GetMessage("IBLOCK_FIELD_DETAIL_PICTURE"), $arIBlock["FIELDS"]["DETAIL_PICTURE"]["IS_REQUIRED"] === "Y");
            if($bVarsFromForm && !array_key_exists("DETAIL_PICTURE", $_REQUEST) && $arElement)
                $str_DETAIL_PICTURE = intval($arElement["DETAIL_PICTURE"]);
            ?>
                <tr id="tr_DETAIL_PICTURE" class="adm-detail-file-row" style='<?=($hidden == "Y")?"display: none;":"";?>'>
                    <td width="40%" class="adm-detail-valign-top">

                        <?
                            if(strlen($title))
                                echo $tabControl->GetCustomLabelHTML($code, $title);

                            else
                                echo $tabControl->GetCustomLabelHTML();
                        ?>

                    :</td>
                    <td width="60%">
                        <?if($historyId > 0):?>
                            <?echo CFileInput::Show("DETAIL_PICTURE", $str_DETAIL_PICTURE, array(
                                "IMAGE" => "Y",
                                "PATH" => "Y",
                                "FILE_SIZE" => "Y",
                                "DIMENSIONS" => "Y",
                                "IMAGE_POPUP" => "Y",
                                "MAX_SIZE" => array(
                                    "W" => COption::GetOptionString("iblock", "detail_image_size"),
                                    "H" => COption::GetOptionString("iblock", "detail_image_size"),
                                ),
                            ));
                            ?>
                        <?else:?>
                            <?if (class_exists('\Bitrix\Main\UI\FileInput', true))
                            {
                                echo \Bitrix\Main\UI\FileInput::createInstance(array(
                                        "name" => "DETAIL_PICTURE",
                                        "description" => true,
                                        "upload" => true,
                                        "allowUpload" => "I",
                                        "medialib" => false,
                                        "fileDialog" => true,
                                        "cloud" => true,
                                        "delete" => true,
                                        "maxCount" => 1
                                    ))->show($bVarsFromForm ? $_REQUEST["DETAIL_PICTURE"] : ($ID > 0 && !$bCopy? $str_DETAIL_PICTURE: 0), $bVarsFromForm);
                            }
                            else
                            {
                                echo CFileInput::Show("DETAIL_PICTURE", ($ID > 0 && !$bCopy? $str_DETAIL_PICTURE: 0),
                                    array(
                                        "IMAGE" => "Y",
                                        "PATH" => "Y",
                                        "FILE_SIZE" => "Y",
                                        "DIMENSIONS" => "Y",
                                        "IMAGE_POPUP" => "Y",
                                        "MAX_SIZE" => array(
                                            "W" => COption::GetOptionString("iblock", "detail_image_size"),
                                            "H" => COption::GetOptionString("iblock", "detail_image_size"),
                                        ),
                                    ), array(
                                        'upload' => true,
                                        'medialib' => false,
                                        'file_dialog' => true,
                                        'cloud' => true,
                                        'del' => true,
                                        'description' => true,
                                    )
                                );
                            }
                            ?>
                        <?endif?>
                    </td>
                </tr>
            <?
            $tabControl->EndCustomField("DETAIL_PICTURE", "");
        }

        
        else
        {
            // if ($arIBlock["SECTION_PROPERTY"] === "Y" || defined("CATALOG_PRODUCT"))
            // {
            //     $arPropLinks = array("IBLOCK_ELEMENT_PROP_VALUE");
            //     if(is_array($str_IBLOCK_ELEMENT_SECTION) && !empty($str_IBLOCK_ELEMENT_SECTION))
            //     {
            //         foreach($str_IBLOCK_ELEMENT_SECTION as $section_id)
            //         {
            //             foreach(CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, $section_id) as $PID => $arLink)
            //                 $arPropLinks[$PID] = "PROPERTY_".$PID;
            //         }
                    
            //     }
            //     else
            //     {
            //         foreach(CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, 0) as $PID => $arLink)
            //             $arPropLinks[$PID] = "PROPERTY_".$PID;

            //     }
            //     // $tabControl->AddFieldGroup("IBLOCK_ELEMENT_PROPERTY", GetMessage("IBLOCK_ELEMENT_PROP_VALUE"), $arPropLinks, $bPropertyAjax);
            // }


            if(strpos($code, "PROPERTY_") !== false && strpos($code, "PROPERTY_") === 0)
                $code = str_replace("PROPERTY_", "", $code);




            $prop_values = $PROP[$arPropFields[$code]]["VALUE"];
            $prop_fields = $PROP[$arPropFields[$code]];

            if(empty($PROP[$arPropFields[$code]]))
            {
                $prop_values = $PROP[$code]["VALUE"];
                $prop_fields = $PROP[$code];
            }


            $tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
            ?>
            <tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row" <?endif?> style='<?=($hidden == "Y")?"display: none;":"";?>'>
                <td class="adm-detail-valign-top" width="40%"><?if($prop_fields["HINT"]!=""):
                    ?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script><?
                endif;?>
                    <?
                        if(strlen($title))
                            echo $tabControl->GetCustomLabelHTML("PROPERTY_".$prop_fields["ID"], $title);

                        else
                            echo $tabControl->GetCustomLabelHTML();?>:</td>
                <td width="60%"><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
            </tr>
            <?
                $hidden = "";
                if(!is_array($prop_fields["~VALUE"]))
                    $values = Array();
                else
                    $values = $prop_fields["~VALUE"];
                $start = 1;

                if(!empty($values))
                {

                    foreach($values as $key=>$val)
                    {
                        if($bCopy)
                        {
                            $key = "n".$start;
                            $start++;
                        }

                        if(is_array($val) && array_key_exists("VALUE",$val))
                        {
                            $hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
                            $hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
                        }
                        else
                        {
                            $hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
                            $hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
                        }
                    }
                }
            $tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);
            
        }

    }

    public static function set404($b404, $arParams)
    {

        if($b404)
        {
            \Bitrix\Iblock\Component\Tools::process404(
                ""
                ,($arParams["SET_STATUS_404"] === "Y")
                ,($arParams["SET_STATUS_404"] === "Y")
                ,($arParams["SHOW_404"] === "Y")
                ,$arParams["FILE_404"]
            );
            
        }

    }


    public static function patternUrl($url)
    {
        $search = array("http://", "https://", "/", "?");
        $replace   = array("", "", "\/", "\?");
        $search2 = array(".php", ".html");
        $replace2   = array("", "");

        $pattern = str_replace($search, $replace, $url);
        $tmpUrl = str_replace($search2, $replace2, $url);

        if(preg_match("/\./i", $tmpUrl))
        {
            $arUrlExp = explode("\/", $pattern);
            
            if(count($arUrlExp) == 1 || (count($arUrlExp) == 2 && empty($arUrlExp[1])))
            {
                if(!preg_match("/^".$url."$/i", $_SERVER['SERVER_NAME']."/"))
                    $pattern = "\/";
            }
            else
            {
                $arUrlExp[0] = "";
                $pattern = implode("\/", $arUrlExp);
            }
        }

        return $pattern;
            
    }

    public static function convertRgb2hex($rgb) {

       $hex = "#";
       $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
       $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
       $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

       return $hex;
    }

    public static function convertHex2rgb($hex) 
    {
       $hex = str_replace("#", "", $hex);

       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       $rgb = array($r, $g, $b);
       return $rgb;
    }

    public static function ClearCacheIBCatalog($patnTemple)
    {
        global $CACHE_MANAGER;

        if(!Loader::includeModule('iblock'))
            return;
        
        $iblock_id = 0;
        $arRes = array();

        $path = $patnTemple.'/ctlg_cache.txt';

        $arFilter = Array("IBLOCK_CODE"=>"concept_phoenix_catalog_".SITE_ID, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y");
        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, array("ID", "IBLOCK_ID"));



        while($ob = $res->GetNextElement())
        { 
            $arElement = $ob->GetFields();

            $arRes[] = $arElement["ID"];

            if($iblock_id == 0)
                $iblock_id = $arElement["IBLOCK_ID"];
        }

        $arRes = implode($arRes, ",");
        $GLOBALS["PHOENIX_STR_FOR_CATALOG_CACHE"] = $arRes = md5($arRes);
        $tmpText = $arRes;

        $valueHash = "";

        if (file_exists($path))
            $valueHash = file_get_contents($path);
        else
            touch($path);

        if($arRes != $valueHash)
        {
            $f = fopen($path, 'w');
            fwrite($f, $tmpText);
            fclose($f);

            $CACHE_MANAGER->ClearByTag("iblock_id_".$iblock_id);
        }
        
    }

    

    public static function IsConstructor($iblock_code)
    {
        global $APPLICATION;
        
        $arDefaultUrlTemplates404 = array(
          "main" => "",
          "page" => "#SECTION_CODE#/",
        );

        $arVariables = array();
        
        $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, SITE_DIR);
        $engine = new CComponentEngine();
        
        $componentPage = $engine->guessComponentPath(
          SITE_DIR,
          $arUrlTemplates,
          $arVariables
        );
        
        $is_construct = false;
            
        if($APPLICATION->GetCurPage() === SITE_DIR)
          $is_construct = true;
            
        if($componentPage == "page")
        {      
            if(!Loader::includeModule('iblock'))
                return;
            
            $arFilter = Array('ACTIVE'=>"Y", "IBLOCK_CODE" => $iblock_code);
            
          if(isset($arVariables["SECTION_CODE"]))
                $arFilter["CODE"] = $arVariables["SECTION_CODE"];
                
            if(isset($arVariables["SECTION_ID"]))
                $arFilter["ID"] = $arVariables["SECTION_ID"];
            
            $db_list = CIBlockSection::GetList(Array(), $arFilter, false);
            
            if($db_list->SelectedRowsCount() > 0)
                $is_construct = true;
        }
        
        return $is_construct;
    }

    public static function getTermination($num, $array)
    {
        $number = substr($num, -2);
         
        if($number > 10 and $number < 15)
        {
            $term = $array[3];
        }
        else
        { 
                         
            $number = substr($number, -1);
             
            if($number == 0) {$term = $array[0];}
            if($number == 1 ) {$term = $array[1];}
            if($number > 1 ) {$term = $array[2];}
            if($number > 4 ) {$term = $array[3];}
        }
         
        return  $term;
    }

    public static function saveSetsMultiRow($name, $siteId, $from_admin = 'N', $desc = "N")
    {

        $arRes = array();
        
        $allCount = 0;


        if(isset($_REQUEST['cphnx_list_'.$name]))
        {
            if(!empty($_REQUEST['cphnx_list_'.$name]))
            {

                foreach($_REQUEST['cphnx_list_'.$name] as $key => $arVal)
                {
                    if(strlen($arVal) == 0)
                        continue;

                    
                    $arVal = htmlspecialcharsEx($arVal);
                    $arDesc = htmlspecialcharsEx($_REQUEST['cphnx_list_desc_'.$name][$key]);

                    $arRes[$allCount]['name'] = $arVal;
                    if($desc == "Y")
                        $arRes[$allCount]['desc'] = $arDesc;

                    $allCount++;

                    
                }

                if(trim(SITE_CHARSET) == "windows-1251" && $from_admin != 'Y')
                    $arRes = utf8win1251($arRes);


                $arRes = base64_encode(serialize($arRes));

                Option::set("concept.phoenix", $name, $arRes, $siteId);

            }
        }

    }

    public static function deleteImage($arParams)
    {
        CFile::Delete($arParams["VALUE"]);
        Option::set("concept.phoenix", $arParams["NAME"], '', $arParams["SITE_ID"]);
    }

    public static function saveImage($arParams)
    {
        if(strlen($arParams["VALUE"]['name'])>0)
        {   
            if(isset($arParams["FROM_ADMIN"]))
            {
                if(trim(SITE_CHARSET) == "windows-1251" && $arParams["FROM_ADMIN"] != 'Y')
                    $arParams["VALUE"]['name'] = utf8win1251($arParams["VALUE"]['name']);
            }

            $fid = CFile::SaveFile($arParams["VALUE"], "phoenix");
            $fid = intval($fid);
            Option::set("concept.phoenix", $arParams["NAME"], $fid, $arParams["SITE_ID"]);
        }

    }


    public static function CreateSoc($arSoc){

        $res = "<div class='soc-group'>";

            if(strlen($arSoc["VK"]['VALUE'])>0)
                $res .= "<a rel='noindex, nofollow' target='_blank' href='".$arSoc["VK"]['VALUE']."' class='soc_ic soc_vk'><i class='concept-vkontakte'></i></a>";

            if(strlen($arSoc["FB"]['VALUE'])>0)
                $res .= "<a rel='noindex, nofollow' target='_blank' href='".$arSoc["FB"]['VALUE']."' class='soc_ic soc_fb'><i class='concept-facebook-1'></i></a>";

            if(strlen($arSoc["TW"]['VALUE'])>0)
                $res .= "<a rel='noindex, nofollow' target='_blank' href='".$arSoc["TW"]['VALUE']."' class='soc_ic soc_tw'><i class='concept-twitter-bird-1'></i></a>";

            if(strlen($arSoc["YOUTUBE"]['VALUE'])>0)
                $res .= "<a rel='noindex, nofollow' target='_blank' href='".$arSoc["YOUTUBE"]['VALUE']."' class='soc_ic soc_yu'><i class='concept-youtube-play'></i></a>";

            if(strlen($arSoc["INST"]['VALUE'])>0)
                $res .= "<a rel='noindex, nofollow' target='_blank' href='".$arSoc["INST"]['VALUE']."' class='soc_ic soc_ins'><i class='concept-instagram-4'></i></a>";

            if(strlen($arSoc["TELEGRAM"]['VALUE'])>0)
                $res .= "<a rel='noindex, nofollow' target='_blank' href='".$arSoc["TELEGRAM"]['VALUE']."' class='soc_ic soc_telegram'><i class='concept-paper-plane'></i></a>";

            if(strlen($arSoc["OK"]['VALUE'])>0)
                $res .= "<a rel='noindex, nofollow' target='_blank' href='".$arSoc["OK"]['VALUE']."' class='soc_ic soc_ok'><i class='concept-odnoklassniki'></i></a>";

            if(strlen($arSoc["TIKTOK"]['VALUE'])>0)
                $res .= "<a rel='noindex, nofollow' target='_blank' href='".$arSoc["TIKTOK"]['VALUE']."' class='soc_ic soc_tiktok'><i class='concept-soc_tiktok'></i></a>";


        $res .= "<div class='clearfix'></div></div>";

        echo $res;

    }

    public static function phoenixMenuClass ($element, $type, $from='')
    {

        $class = '';


        if($type == 'form' && strlen($element['FORM'])>0)
            $class = 'call-modal callform '.$from;

        if($type == 'modal' && strlen($element['MODAL'])>0)
            $class = 'call-modal callmodal '.$from;

        if($type == 'quiz' && strlen($element['QUIZ'])>0)
            $class = 'call-wqec '.$from;
        

        return $class;

    }

    public static function phoenixMenuAttr ($element, $type)
    {

        $attr = '';


        if($type == 'form' && strlen($element['FORM'])>0)
            $attr = 'data-call-modal="form'.$element['FORM'].'"';

        if($type == 'modal' && strlen($element['MODAL'])>0)
            $attr = 'data-call-modal="modal'.$element['MODAL'].'"';

        if($type == 'quiz' && strlen($element['QUIZ'])>0)
            $attr = 'data-wqec-section-id="'.$element['QUIZ'].'"';             

        return $attr;

    }
    public static function buttonEditClass ($arClass = array())
    {
        $classes = '';

        if($arClass["XML_ID"] == "form" && strlen($arClass["FORM_ID"])>0)
            $classes = 'call-modal callform';

        elseif($arClass["XML_ID"] == "modal" && strlen($arClass["MODAL_ID"])>0)
            $classes = 'call-modal callmodal';

        elseif($arClass["XML_ID"] == "block")
            $classes = 'scroll';

        elseif($arClass["XML_ID"] == "quiz" && strlen($arClass["QUIZ_ID"])>0)
            $classes = 'call-wqec';

        elseif($arClass["XML_ID"] == "fast_order" && strlen($arClass["FAST_ORDER_PRODUCT_ID"])>0)
            $classes = 'callFastOrder';

        elseif($arClass["XML_ID"] == "add_to_cart" && strlen($arClass["ADD2CART_PRODUCT_ID"])>0)
            $classes = 'common-btn-basket-style btn-add2basket';
 

        return $classes;
    }


    public static function buttonEditAttr ($arAttr = array())
    {
        $attr = '';

        if($arAttr["XML_ID"] == "form" && strlen($arAttr["FORM_ID"])>0)
            $attr = "data-header='".str_replace("'","\"",strip_tags(htmlspecialcharsBack($arAttr["HEADER"])))."' data-call-modal='form".$arAttr["FORM_ID"]."'";

        elseif($arAttr["XML_ID"] == "modal" && strlen($arAttr["MODAL_ID"])>0)
            $attr = "data-call-modal='modal".$arAttr["MODAL_ID"]."'";

        elseif($arAttr["XML_ID"] == "block" && strlen($arAttr["LINK"])>0)
            $attr = "href = '#block".$arAttr["LINK"]."'";

        elseif($arAttr["XML_ID"] == "blank" && strlen($arAttr["LINK"])>0)
            $attr = "href = '".$arAttr["LINK"]."' ".$arAttr["BLANK"];


        elseif($arAttr["XML_ID"] == "quiz" && strlen($arAttr["QUIZ_ID"])>0)
            $attr = "data-wqec-section-id='".$arAttr["QUIZ_ID"]."'";

        elseif($arAttr["XML_ID"] == "land" && strlen($arAttr["LAND_ID"])>0)
        {
            global $PHOENIX_TEMPLATE_ARRAY;

            $arFilter = Array('IBLOCK_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CONSTR']["IBLOCK_ID"], 'ID' => $arAttr["LAND_ID"], "ACTIVE"=>"Y");
            $dbResSect = CIBlockSection::GetList(Array("sort"=>"asc"), $arFilter, false, array('SECTION_PAGE_URL', 'UF_PHX_MAIN_PAGE'));
            
            if($sectRes = $dbResSect->GetNext())
            {
                $land_link = $sectRes['SECTION_PAGE_URL'];

                if($sectRes['UF_PHX_MAIN_PAGE'])
                    $land_link = SITE_DIR;
            }

            $attr = "href = '".$land_link."' ".$arAttr["BLANK"];
        }

        elseif($arAttr["XML_ID"] == "fast_order" && strlen($arAttr["FAST_ORDER_PRODUCT_ID"])>0)
        {
            $productItem = self::getCatalogItemProps($arAttr["FAST_ORDER_PRODUCT_ID"]);

            if($productItem["AVAILABLE"]=="Y")
                $attr = "data-param-product=\"".CUtil::PhpToJSObject($productItem, false, true)."\"";
            
            else
                $attr = "style='display: none;'";
        }

        elseif($arAttr["XML_ID"] == "add_to_cart" && strlen($arAttr["ADD2CART_PRODUCT_ID"])>0)
        {
            $productItem = self::getCatalogItemProps($arAttr["ADD2CART_PRODUCT_ID"]);

            if($productItem["AVAILABLE"]=="Y")
                $attr = "data-param-product=\"".CUtil::PhpToJSObject($productItem, false, true)."\" data-item = \"".$productItem["PRODUCT_ID"]."\"";    
            else
                $attr = "data-style='d-none'";
        }
        
        return $attr;
    }

    public static function getCatalogItemProps ($id)
    {
        global $PHOENIX_TEMPLATE_ARRAY;

        if(!Loader::includeModule('catalog'))
            return;

        $productItem = array();

        if($id)
        {   

            $db_res = CCatalogProduct::GetList(
                array("ID" => "ASC"),
                array("ID" => $id),
                false,
                array(),
                array("TYPE")
            );
            $ar_res = $db_res->Fetch();

            $productType = $ar_res["TYPE"];
            


            if( $productType == 3 || $productType == 4)
            {

                if( $productType == 4 )
                {

                    $offerID = $id;

                    $arProduct = CCatalogSKU::getProductList(
                        $id, 
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"]
                    );

                    $id = $arProduct[$offerID]["ID"];
                }

                $ar_res = array();

                $db_res = CCatalogProduct::GetList(
                    array("ID" => "ASC"),
                    array("ID" => $id),
                    false,
                    array(),
                    array("ELEMENT_NAME")
                );
                $ar_res = $db_res->Fetch();



                $productItem["NAME"] = strip_tags($ar_res["ELEMENT_NAME"]);
                
                $offers = CCatalogSKU::getOffersList(
                    $id,
                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
                    $skuFilter = array(),
                    $fields = array(), 
                    $propertyFilter = array("CODE"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['CODE'])
                );


                if( $productType == 3 )
                {
                    $offer = array_shift($offers[$id]);
                    $id = $offer["ID"];
                }

                if( $productType == 4 )
                {
                    $offer = $offers[$id][$offerID];
                    $id = $offerID;
                }


                unset($offers);

                if(!empty($offer["PROPERTIES"]))
                {

                    CPhoenixSku::getHIBlockOptions();
                    foreach ($offer["PROPERTIES"] as $key => $arProp)
                    {
                        $valName = "";

                        if($arProp["USER_TYPE"] == "directory" && !empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]))
                        {
                            foreach ($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"] as $arSKUProp)
                            {
                                if($arProp["USER_TYPE_SETTINGS"]["TABLE_NAME"] == $arSKUProp["TABLE_NAME"])
                                {
                                    $valName = $arSKUProp["VALUE_NAME"][$arProp["VALUE"]];
                                }
                            }
                        }
                        else
                        {
                            $valName = $arProp["VALUE"];
                        }

                        if(strlen($valName))
                        {
                            $skuName = $arProp["NAME"];

                            $productItem["element_item_offers"][$key]["SKU_NAME"] = $arProp["NAME"];
                            $productItem["element_item_offers"][$key]["NAME"] = $valName;
                        }

                    }

                }
                
            }



            $ar_res = array();
            
            $db_res = CCatalogProduct::GetList(
                array("ID" => "ASC"),
                array("ID" => $id),
                false,
                array(),
                array("ID", "AVAILABLE", "QUANTITY", "ELEMENT_NAME")
            );
            $ar_res = $db_res->Fetch();

            if( !isset($productItem["NAME"]) )
            {
                $productItem["NAME"] = strip_tags($ar_res["ELEMENT_NAME"]);
            }


            $productItem["PRODUCT_ID"] = $id;

            $ar_res["QUANTITY"] = self::getNumberFromStringWithType($ar_res["QUANTITY"]);

            $rsRatios = CCatalogMeasureRatio::getList(array(), array('PRODUCT_ID' => $id), false, false, array('PRODUCT_ID', 'RATIO'));

            $arRatio = $rsRatios->Fetch();
          
            $arRatio['PRODUCT_ID'] = (int) $arRatio['PRODUCT_ID'];
            $arRatio["RATIO"] = self::getNumberFromStringWithType($arRatio["RATIO"]);


            if($ar_res["QUANTITY"] < $arRatio["RATIO"])
                $productItem["QUANTITY"] = $arRatio["RATIO"];
            else
                $productItem["QUANTITY"] = $arRatio["RATIO"];
               
            $arPrice = CCatalogProduct::GetOptimalPrice($arRatio['PRODUCT_ID'], $productItem["QUANTITY"]);

            $productItem["PRICE"] = $arPrice["RESULT_PRICE"]["DISCOUNT_PRICE"];
            $productItem["AVAILABLE"] = $ar_res["AVAILABLE"];  
            $productItem["PRICE_ID"] = $arPrice["PRICE"]["ID"];
        }

        return $productItem;
    }


    public static function admin_setting($arItem, $center = false, $pos = 'right')
    {
        global $PHOENIX_TEMPLATE_ARRAY;


        if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"] && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MODE_FAST_EDIT"]['VALUE']["ACTIVE"] == 'Y')
        {
            $res = "<div class='tool-settings'>";

                $res .= "<a href='/bitrix/admin/iblock_element_edit.php?";
                    $res .= "IBLOCK_ID=".$arItem['IBLOCK_ID'];
                    $res .= "&type=".$arItem['IBLOCK_TYPE_ID'];
                    $res .= "&ID=".$arItem['ID'];
                    $res .= "&find_section_section=".$arItem['IBLOCK_SECTION_ID'];
                $res .= "'";
                $res .= "class='tool-settings ";

                    if($center)
                        $res .= "in-center";

                    $res .= "' ";

                $res .= "data-toggle='tooltip' target='_blank' data-placement='".$pos."' title='".Loc::GetMessage("PHOENIX_PAGE_GENERATOR_EDIT")." &quot;".TruncateText($arItem["NAME"], 35)."&quot;'></a>";

            $res .= "</div>";

            echo $res;
        }

    }

    public static function admin_setting_cust($arParams = array()){
        global $PHOENIX_TEMPLATE_ARRAY;

        if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"] && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MODE_FAST_EDIT"]['VALUE']["ACTIVE"] == 'Y')
        {
            $res = "<div class='tool-settings ".( (isset($arParams['CLASS']))?$arParams['CLASS']:"" )."'>";

                $res .= "<a href='/bitrix/admin/iblock_element_edit.php?";
                    $res .= "IBLOCK_ID=".$arParams['IBLOCK_ID'];
                    $res .= "&type=".$arParams['IBLOCK_TYPE_ID'];
                    $res .= "&ID=".$arParams['ID'];

                    if(strlen($arParams['IBLOCK_SECTION_ID']))
                        $res .= "&find_section_section=".$arParams['IBLOCK_SECTION_ID'];

                $res .= "'";

                $res .= "class='tool-settings ";

                    if(isset($arParams["CLASS"]))
                        $res .= $arParams["CLASS"];

                $res .= "' ";

                    if(isset($arParams["ATTRS"]))
                        $res .= $arParams["ATTRS"];

                $res .= "data-toggle='tooltip' target='_blank' data-placement='right' title='".Loc::GetMessage("PHOENIX_PAGE_GENERATOR_EDIT")." &quot;".TruncateText($arParams["NAME"], 35)."&quot;'></a>";

            $res .= "</div>";

            echo $res;
        }

    }

    public static function getPermissionPhoenix($arRes = array(), $type = "||")
    {
        if(!empty($arRes))
        {
            
            if($type == "&&")
            {

                foreach ($arRes as $value)
                {
                    if( strlen($value)<=0 )
                        return false;
                }

                return true;
            }

            if($type == "||")
            {
                foreach ($arRes as $value)
                {
                    if( strlen($value)>0 )
                        return true;
                }

                return false;
            }
        }

        return false;
        
    }

    public static function getTitleHtml($title)
    {
        echo "<tr class='heading'>
                    <td colspan='2'>"
                    .$title
                    ."</td>
                </tr>";
    }

    public static function getOptionHtml($arField = array())
    {

        global $PHOENIX_TEMPLATE_ARRAY;

        $tr_class = "";
        $tr_attrs = "";

        if(strlen($arField["TR_ATTRS"]) && isset($arField["TR_ATTRS"]))
            $tr_attrs = $arField["TR_ATTRS"];

        if($arField["REQ"] == "Y")
            $tr_class .= " adm-detail-required-field ";

        if(strlen($arField["TR_CLASS"]) && isset($arField["TR_CLASS"]))
            $tr_class .= $arField["TR_CLASS"];

        if(strlen($tr_class))
            $tr_class = "class='".$tr_class."'";


        if(strlen($arField["HINT"])>0)
        {
            ob_start();
                ShowJSHint($arField["HINT"]);
                $hint = ob_get_contents();
            ob_end_clean();
        }

        if($arField["TYPE"] == "text")
        {
            $require_style = "";

            if($arField["REQ"] == "Y")
                $require_style = " class='adm-detail-required-field'";

        
            



            $input = "";

            if($arField["VIEW"] == "visual")
            {

                ob_start();

                    CFileMan::AddHTMLEditorFrame(
                        $arField["NAME"],
                        $arField["VALUE"],
                        $arField["NAME"],
                        "html",
                        array(
                            'height' => $arField["SIZE"],
                            'width' => $arField["ROWS"]
                        ),   
                        "N",
                        0,
                        "",
                        "",
                        "",
                        true,
                        false,
                        array(
                        ));

                    $input = ob_get_contents();

                ob_end_clean();
                

                $res = "
                    <tr class='heading'>
                        <td colspan='2' valign='top'>"
                            .$arField["DESCRIPTION"]
                        ."</td>
                    </tr>

                    <tr>
                        <td colspan='2' valign='top'>
                        ".$input."
                        </td>

                    </tr>
                    ";
            }

            else
            {

               
                if($arField["DISABLED"] == "Y")
                {
                    $input = $arField["VALUE"];
                }
                else
                {
                    /*$input = CUserTypeString::GetEditFormHTML(array("SETTINGS"=> array('SIZE' => $arField["SIZE"], 'ROWS' => $arField["ROWS"],), 'EDIT_IN_LIST' => 'Y'), array('NAME' => $arField["NAME"], "VALUE" => $arField["VALUE"]));*/

                    $input = "";


                    if( intval($arField["ROWS"]) > 1)
                        $input = "<textarea cols=\"".$arField["SIZE"]."\" rows=\"".$arField["ROWS"]."\" name=\"".$arField["NAME"]."\">".$arField["VALUE"]."</textarea>";
                    else
                        $input = "<input type=\"text\" valign=\"middle\" size=\"".$arField["SIZE"]."\" name=\"".$arField["NAME"]."\" value=\"".$arField["VALUE"]."\">";
                }
                

                $res = "
                    <tr ".$tr_class." ".$tr_attrs.">

                        <td width='40%' valign='top'>".$hint."   
                            <label a for='".$arField["NAME"]."'>".$arField["DESCRIPTION"]."</label>:
                        </td>
                        <td width='60%' valign='top'>
                        "
                        .$input

                        ."</td>


                    </tr>
                    ";
                
            }

            echo $res;
        }

        if($arField["TYPE"] == "checkbox" || $arField["TYPE"] == "radio")
        {
            
            $input = "";

            $count = count($arField["VALUES"]);

            if(isset($arField["BIND"]) && !empty($arField["BIND"]))
            {
                $arr = array(
                    "REFERENCE" => $arField["BIND"]["DESCRIPTIONS"],
                    "REFERENCE_ID" => $arField["BIND"]["VALUES"],
                );
            }

            if(!empty($arField["VALUES"]))
            {

                  foreach($arField["VALUES"] as $k => $val)
                  {

                    $desc = "<span style='margin: 0 10px 0 3px;'>".$val["DESCRIPTION"]."</span>";
                    if($arField["TYPE"] == "checkbox")
                    {
                        $cmp = $arField["VALUE"][$k];

                        if($count == 1 && !isset($arField["SHOW_DESC_ALONE"]) && $arField["SHOW_DESC_ALONE"] != "Y")
                            $desc = "";

                    }
                    
                    else
                        $cmp = $arField["VALUE"];

                    $select = "";

                    if(isset($arField["BIND"]) && !empty($arField["BIND"]))
                        $select = " ".SelectBoxFromArray($val["NAME"]."_value_2", $arr, $val["VALUE_2"], "");


                    $inputFields = "";

                    if(isset($val["INPUT_CLASS"]) && strlen($val["INPUT_CLASS"]))
                        $inputFields .= " class='".$val["INPUT_CLASS"]."' ";

                    if(isset($val["INPUT_ATTRS"]) && strlen($val["INPUT_ATTRS"]))
                        $inputFields .= $val["INPUT_ATTRS"];
                    
                    $input .= InputType($arField["TYPE"], $val["NAME"], $val["VALUE"], $cmp, false, $desc, $inputFields).$select."<br>";

                }
            }
        

            $res = "
            <tr ".$tr_class." ".$tr_attrs.">
                <td width='40%' valign='top'>".$hint.$arField["DESCRIPTION"].":
                </td>
                <td width='60%' valign='middle'>
                ".$input."
                
                </td>
            </tr>
            ";
            

            echo $res;
        }

        if($arField["TYPE"] == "image")
        {

            $res = "
            <tr ".$tr_class." ".$tr_attrs.">
                <td width='30%' valign='top'>".$hint.$arField["DESCRIPTION"]."
                </td>
                <td width='70%' valign='middle'>
                    <input type='hidden' name='old_".$arField["NAME"]."' value='".$arField["VALUE"]."'>
                ".CFileInput::Show($arField["NAME"], $arField["VALUE"],
                        array(
                            'IMAGE' => $arField["VALUE"],
                            'PATH' => 'Y',
                            'FILE_SIZE' => 'Y',
                            'DIMENSIONS' => 'Y',
                            'IMAGE_POPUP' => 'Y',
                            'MAX_SIZE' => array(
                            'W' => 200,
                            'H' => 200,
                            ),
                        ),
                        array(
                            'upload' => true,
                            'del' => true,
                        )
                    )."
                
                </td>
            </tr>
            ";

            echo $res;
        }

        if($arField["TYPE"] == "select")
        {
            array_unshift($arField["DESCRIPTIONS"], Loc::GetMessage("PHOENIX_SET_SELECT_EMPTY"));
            array_unshift($arField["VALUES"], "N");

            $arr = array(
                "REFERENCE" => $arField["DESCRIPTIONS"],
                "REFERENCE_ID" => $arField["VALUES"],
            );


            $style = "";
            if($arField["ATTR"])
                $style = $arField["ATTR"];

            $res = "
            <tr ".$tr_class." ".$tr_attrs.">
                <td width='40%' valign='middle'>".$hint.$arField["DESCRIPTION"].":
                </td>
                <td width='60%' valign='middle'>
                ".SelectBoxFromArray($arField["NAME"], $arr, $arField["VALUE"], "", $style)."
                
                </td>
            </tr>
            ";

            echo $res;
        }

        if($arField["TYPE"] == "inputdatalist")
        {

            $res = "
            <tr ".$tr_class." ".$tr_attrs.">
                <td width='40%' valign='middle'>".$hint.$arField["DESCRIPTION"].":
                </td>
                <td width='60%' valign='middle'>
                    <div style=\"display:inline-block; position: relative\">            
                        <input type=\"text\" class=\"admin-inputdatalist-css ".$arField["CLASS_SELECTOR"]."\" valign=\"middle\" size=\"".$arField["SIZE"]."\" value=\"".$arField["VALUE"]."\">
                        <div class=\"admin-inputdatalist-preload\"></div>
                        <input type=\"hidden\" name=\"".$arField["NAME"]."\" value=\"".$arField["VALUE_HIDDEN"]."\">
                        <div class=\"admin-inputdatalist ".$arField["CLASS_SELECTOR"]."_area\"></div>
                    </div>

                    <script>

                        $( \".".$arField["CLASS_SELECTOR"]."\" ).autocomplete({
                            minLength: 2,
                            source: \"".$arField["SOURCE"]."\",
                            appendTo: \".".$arField["CLASS_SELECTOR"]."_area\",
                            autoFocus: true,
                            select: function( event, ui ) {
                                $(this).parent().find(\"input\").val(ui.item.value);
                                $(this).val(ui.item.label);
                                return false;
                            },
                            change: function( event, ui ) {
                                if(ui.item===null)
                                {
                                    $(this).parent().find(\"input\").val(\"\");
                                    $(this).val(\"\");
                                }
                            },

                        });
                    </script>
                </td>
            </tr>
            ";

            echo $res;
        }

        if($arField["TYPE"] == "warning")
        {
            $res = "
            <tr ".$tr_class." ".$tr_attrs.">
                <td colspan=\"2\" width='100%' valign='middle'>
                <div style=\"margin: 16px 0;
                        font-size: 13px;
                        line-height: 18px;
                        padding: 15px 30px 15px 18px;
                        background: #ffee9b;
                        \">"
                .$arField["DESCRIPTION"]
                ."</div></td>
            </tr>
            ";

            echo $res;
        }

    }

    public static function getOptionHtmlInPublic($arField = array(), $arAttrs = array())
    {
        global $PHOENIX_TEMPLATE_ARRAY;

        if( !isset($arField["IN_PUBLIC"]) )
            $arField["IN_PUBLIC"] = array();

        else
        {
            if(!isset($arField["IN_PUBLIC"]["VIEW"]))
                $arField["IN_PUBLIC"]["VIEW"] = "";

            if(!isset($arField["IN_PUBLIC"]["CLASS_UL"]))
                $arField["IN_PUBLIC"]["CLASS_UL"] = "";

            if(!isset($arField["IN_PUBLIC"]["SIZE_HEIGHT"]))
                $arField["IN_PUBLIC"]["SIZE_HEIGHT"] = "";
        }


        if($arField["TYPE"] == "text")
        {
            $res = "";

            if($arField["MULTY"] == "Y")
            {
                $list = "";
                $row_left = "";
                $row_right = "";
                $cols = "col-12";

                $field_name = "";
                $field_focus_anim = "";
                $desc_name = "";
                $desc_focus_anim = "";


                if($arField["FIELD_NAME"] !== NULL)
                {
                    $field_name = "<span class='desc'>".$arField["FIELD_NAME"]."</span>";
                    $field_focus_anim = "focus-anim";
                }
                if($arField["DESC_NAME"] !== NULL)
                {
                    $desc_name = "<span class='desc'>".$arField["DESC_NAME"]."</span>";
                    $desc_focus_anim = "focus-anim";
                }



                if($arField["DESC_ON"] == "Y")
                {
                    $cols = "col-6";

                    $row_left = "to-right";
                    $row_right = "to-left";


                    $desc = "

                    <div class='".$cols."'>
                        <div class='input ".$arAttrs["CLASS_DIV_INPUT"]." ".$row_right."'> 
                            <div class='bg'></div>
                            ".$desc_name."                                    
                            <input type='text' class='".$desc_focus_anim." text for-copy-2' name='cphnx_list_desc_".$arField['NAME']."[n".count($arField['VALUE'])."]' value=''>
                        </div>
                    </div>

                    ";
                }
                

                if(!empty($arField['VALUE']))
                {
                    foreach($arField['VALUE'] as $k => $arValue)
                    {
                        $inFocusField = "";
                        if(strlen($arValue['name'])>0)
                            $inFocusField = "in-focus";


                        $inFocusDesc = "";
                        if(strlen($arValue['desc'])>0)
                            $inFocusDesc = "in-focus";
                        

                        if($arField["DESC_ON"] == "Y")
                        {
                            $desc_in_list = "

                            <div class='".$cols."'>
                                <div class='input ".$row_right." ".$inFocusDesc."'> 
                                    <div class='bg'></div>
                                    ".$desc_name."                                      
                                    <input type='text' class='".$desc_focus_anim." text on-save' name='cphnx_list_desc_".$arField['NAME']."[n".$k."]' value='".$arValue['desc']."'>
                                </div>
                            </div>
                
                            ";
                        }


                        $list .= "

                        <div class='row row-for-copy'>

                            <div class='".$cols."'>
                                <div class='input ".$arAttrs["CLASS_DIV_INPUT"]." ".$inFocusField." ".$row_left."'>
                                    <div class='bg'></div>
                                    ".$field_name."
                                    <input type='text' class='text on-save ".$field_focus_anim."' name='cphnx_list_".$arField['NAME']."[n".$k."]' value='".$arValue['name']."'>
                                </div>
                            </div>

                            ".$desc_in_list."

                        </div>

                        ";
                    }
                }

                $res = "

                <div class='parent-row'>

                    <div class='empty-template'>
                        <div class='row row-for-copy'>
                            <div class='".$cols."'>
                                <div class='input ".$arAttrs["CLASS_DIV_INPUT"]." ".$row_left."'>
                                    <div class='bg'></div>
                                    ".$field_name."
                                    <input type='text' class='text for-copy-1 ".$field_focus_anim."' name='cphnx_list_".$arField['NAME']."[n".count($arField['VALUE'])."]' value=''>
                                </div>
                            </div>
                            ".$desc."
                        </div>
                        
                    </div>

                    <div class='rows-copy'>

                    ".$list."

                    </div>

                    <div class='addrow on-save'>+ <span>".$arField["BTN_NAME"]."</span></div>

                </div>
                ";


                echo $res;

            }

            else
            {
                
                $inFocus = "";
                $after_input = "";

                if($arAttrs["AFTER_INPUT_HTML"] !== NULL)
                    $after_input = $arAttrs["AFTER_INPUT_HTML"];
                

                if($arField["IN_PUBLIC"]["VIEW"] == "decorated")
                {


                    if(strlen($arField['VALUE'])>0)
                        $inFocus = "in-focus";

                    if($arField["IN_PUBLIC"]["TYPE"] == "textarea")
                    {
                        $res = "

                        <div class='textarea focus-anim ".$inFocus." ".$arField["IN_PUBLIC"]["SIZE_HEIGHT"]." ".$arAttrs["CLASS_DIV_INPUT"]." on-save'>

                            <div class='bg'></div>

                            <span class='desc'>".$arField['DESCRIPTION']."</span>

                            <textarea class='focus-anim' name='".$arField['NAME']."' value='".$arField['VALUE']."'>".$arField['VALUE']."</textarea>
                        </div>

                        ";


                    }
                    else
                    {
                        $res = "

                        <div class='input ".$arAttrs["CLASS_DIV_INPUT"]." ".$inFocus."'> 

                        <span class='desc'>".$arField['DESCRIPTION']."</span> 

                        <input type='text' class='focus-anim on-save' name='".$arField['NAME']."' value='".$arField['VALUE']."'>

                        ".$after_input."

                        </div>";

                        
                    }

                    echo $res;

                    
                }

                else if($arField["IN_PUBLIC"]["VIEW"] == "color_picker")
                {
                    $color = " ";
                    $saveOn = "";
                    if(strlen($arField['VALUE'])>0)
                    {
                        $color = $arField['VALUE'];
                        $saveOn = "on";
                    }


                    $res = "

                    <div class='input ".$arAttrs["CLASS_DIV_INPUT"]." parent-clearcolor'>
                        <div class='bg'></div>

                        <input id='picker_".$arField['NAME']."' class='picker_color on-save' name='".$arField['NAME']."' type='text' value='".$color."'>
                            <span class='call_picker on-save'></span>

                            <div class='picker-wrap'>
                                <a class='picker-close'></a>
                                <div id='panel_".$arField['NAME']."' class='picker_panel'></div>
                            </div>
                            <div class='clearcolor on-save ".$saveOn."'></div>
                    </div>

                    ";

                    echo $res;
                }

                else
                {
                    

                    if($arField["IN_PUBLIC"]["TYPE"] == "textarea")
                    {
                        $res = "
                        <div class='textarea ".$arField["IN_PUBLIC"]["SIZE_HEIGHT"]." ".$arAttrs["CLASS_DIV_INPUT"]."'>    
                            <textarea type='text' class='text on-save' name='".$arField['NAME']."'>".$arField['VALUE']."</textarea>
                            ".$after_input."
                        </div>
                        ";
                    }

                    else
                    {
                        $res = "

                            <div class='input ".$arAttrs["CLASS_DIV_INPUT"]." ".$inFocus."'> 

                                <input type='text' class='text on-save' name='".$arField['NAME']."' value='".$arField['VALUE']."''>
                                ".$after_input."

                            </div>

                        ";
                    }

                    echo $res;
                    
                }
            }
            
        }

        if($arField["TYPE"] == "checkbox" || $arField["TYPE"] == "radio")
        {
            if( !empty($arField['VALUES']) )
            {
                if(count($arField['VALUES']) == 1)
                    $class_alone = "alone";

                $classType = "form-check";

                if($arField['TYPE'] == "radio")
                    $classType = "form-radio";

                

                $list = "";


                foreach($arField['VALUES'] as $key => $opt)
                {

                    $checked = "";


                    if($arField['TYPE'] == "radio")
                    {
                        if($arField['VALUE'] == $opt["VALUE"])
                            $checked = "checked";
                    }
                    else
                    {
                        if($arField['VALUE'][$key] == $opt["VALUE"])
                            $checked = "checked";
                    }

                    $hint = "";

                    if( strlen($opt["HINT"]) )
                    {
                        $hint = "<span class='answer' data-toggle='tooltip' data-placement='top' title='' data-original-title='".$opt['HINT']."'></span>";
                    }

                    $showOption = "";
                    $classShowOption = "";

                    if(strlen($opt['SHOW_OPTIONS']))
                    {
                        $showOption = "data-show-options='".$opt['SHOW_OPTIONS']."'";
                        $classShowOption = "open_more_options";
                    }

                    $select ="";

                    if(isset($arField["BIND"]))
                    {
                        if(!empty($arField["BIND"]))
                        {
                            $option = "";

                            foreach($arField["BIND"]["VALUES"] as $keySelectOption => $valueOption)
                            {

                                $selected = "";
                                if( $valueOption == $opt["VALUE_2"])
                                    $selected = "selected";
                                

                                $option .= "<option value='".$valueOption."' ".$selected.">".$arField["BIND"]["DESCRIPTIONS"][$keySelectOption]."</option>";

                            }

                            $select ="<select style='margin-left: 10px;' name='".$opt["NAME"]."_value_2' class='on-save'>".$option."</select>";
                        }
                    }

                    $list .= 
                    "
                        <li>
                            <label>
                            <input class='".$arAttrs["CLASS_INPUT"]." ".$classShowOption." on-save' name='".$opt["NAME"]."' type='".$arField['TYPE']."' value='".$opt["VALUE"]."' ".$checked." ".$showOption." ".$arAttrs["ATTR_INPUT"]."><span></span><span class='text'>".$opt["DESCRIPTION"]."</span></label>
                            ".$select.$hint."
                        </li>

                    ";
                }


                $res = "<ul class='".$classType." ".$class_alone;

                if($arField["IN_PUBLIC"]["CLASS_UL"])
                    $res .= $arField["IN_PUBLIC"]["CLASS_UL"];

                $res .= "'>";

                    $res .= $list;
                    

                $res .= "</ul>";

                echo $res;
            }
        }

        if($arField["TYPE"] == "image")
        {
            $inFocus = "";
            $saveOn = "";

            if(strlen($arField["SETTINGS"]["ORIGINAL_NAME"]) > 0)
            {
                $inFocus = "focus-anim";
                $saveOn = "on";
            }

            $res = "
            <div class='input clearfile-parent ".$arAttrs["CLASS_DIV_INPUT"]."'>
                <input type='hidden' name='old_".$arField['NAME']."' value='".$arField['VALUE']."'>
                <input type='hidden' class='phoenix_file_del' name='".$arField['NAME']."_del' value=''>
                        
                <label class='file on-save ".$inFocus." click-file-clear'>
                    <span class='ex-file-desc'>".$arField['DESCRIPTION']."</span>
                    <span class='ex-file'>".$arField["SETTINGS"]["ORIGINAL_NAME"]."</span>
                    <input type='file' accept='image/*' class='hidden flat' id='".$arField['NAME']."' name='".$arField['NAME']."'/>

                </label>

                <div class='clearfile on-save ".$saveOn."'></div>

            </div>

            ";

            echo $res;
        }

        if($arField["TYPE"] == "select")
        {

            array_unshift($arField["DESCRIPTIONS"], Loc::GetMessage("PHOENIX_SET_SELECT_EMPTY"));
            array_unshift($arField["VALUES"], "N");

            $list ="";

            if(!empty($arField['DESCRIPTIONS'])){
                foreach($arField['DESCRIPTIONS'] as $k => $arFont)
                {
                    $selected = "";
                    if($arField['VALUE'] == $arField['VALUES'][$k]) 
                        $selected = "selected";

                    $list .= "<option value='".$arField['VALUES'][$k]."' ".$selected.">".$arFont."</option>";
                }
            }

            $res = "<span class='desk'>".$arField['DESCRIPTION']."</span>

            <select name='".$arField["NAME"]."' class='on-save ".$arField["CLASS_SELECT"]."'>".$list."</select>";

            echo $res;
        }


        if($arField["TYPE"] == "inputdatalist")
        {

            $res = "
                <input type=\"text\" class=\"on-save admin-inputdatalist-css ".$arField["CLASS_SELECTOR"]."\" valign=\"middle\" size=\"".$arField["SIZE"]."\" value=\"".$arField["VALUE"]."\">
                <div class=\"circleG-area\">
                    <div class=\"circleG circleG_1\"></div>
                    <div class=\"circleG circleG_2\"></div>
                    <div class=\"circleG circleG_3\"></div>
                </div>
                <input type=\"hidden\" name=\"".$arField["NAME"]."\" value=\"".$arField["VALUE_HIDDEN"]."\">
               
                <div class=\"admin-inputdatalist ".$arField["CLASS_SELECTOR"]."_area\"></div>

                <script>

                    $( \".".$arField["CLASS_SELECTOR"]."\" ).autocomplete({
                        minLength: 2,
                        source: \"".$arField["SOURCE"]."\",
                        appendTo: \".".$arField["CLASS_SELECTOR"]."_area\",
                        autoFocus: true,
                        select: function( event, ui ) {
                            $(this).parent().find(\"input\").val(ui.item.value);
                            $(this).val(ui.item.label);
                            return false;
                        },
                        change: function( event, ui ) {
                            if(ui.item===null)
                            {
                                $(this).parent().find(\"input\").val(\"\");
                                $(this).val(\"\");
                            }
                        },

                    });
                </script>
            ";

            echo $res;
        }



        if($arField["TYPE"] == "warning")
        {
            $res = "
                <div class='setting-warning-txt'>".$arField["DESCRIPTION"]."</div>
            ";

            echo $res;
        }

    }

    public static function getPropsCharsCatalogItem($arProps, $arFields, $showFields = "Y")
    {
        global $PHOENIX_TEMPLATE_ARRAY;

        $arPropsNew = array();
        $characteristics = array();

        if(!empty($arProps))
        {
            foreach ($arProps as $key => $value) {
                $arPropsNew[$value["ID"]] = $key;
            }
        }


        if( !empty($arFields) && $showFields == "Y")
        {

            $arPropG = $arPropE = array();


            foreach ($arFields as $value)
            {
                 $item = $arProps[$arPropsNew[$value]];

                

                  if($item["PROPERTY_TYPE"] == "E")
                  {
                        if(is_array($item["VALUE"]))
                        {
                              foreach ($item["VALUE"] as $keyValue => $itemValue){
                                    $arPropE[] = $itemValue;
                              }
                        }
                        else
                              $arPropE[] = $item["VALUE"];
                  }

                  else if($item["PROPERTY_TYPE"] == "G")
                  {
                        if(is_array($item["VALUE"]))
                        {
                              foreach ($item["VALUE"] as $keyValue => $itemValue){
                                    $arPropG[] = $itemValue;
                              }
                        }
                        else
                              $arPropG[] = $item["VALUE"];
                  }
            }


            if(!empty($arPropE))
            {
                $arFilter = Array("ID"=> $arPropE, "ACTIVE"=>"Y");
                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, array("ID", "NAME", "DETAIL_PAGE_URL"));

                $arPropEres = array();

                while($ob = $res->GetNext()){ 
                    $arPropEres[$ob["ID"]] = "<a href=\"".$ob["DETAIL_PAGE_URL"]."\" target=\"_blank\">".strip_tags($ob["~NAME"])."</a>";
                }
            }

            if(!empty($arPropG))
            {
                $arFilter = Array('ID' => $arPropG, "ACTIVE"=>"Y");
                $resSect = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, array("ID", "NAME", "SECTION_PAGE_URL"));

                $arPropGres = array();
                while($ob = $resSect->GetNext())
                {
                    $arPropGres[$ob["ID"]] = "<a href=\"".$ob["SECTION_PAGE_URL"]."\" target=\"_blank\">".strip_tags($ob["~NAME"])."</a>";
                }

            }


            foreach ($arFields as $value)
            {
                $item = $arProps[$arPropsNew[$value]];


                if($item["PROPERTY_TYPE"]=="S" && $item["USER_TYPE"]=="directory")
                {

                    if(is_array($item["VALUE"]))
                    {

                        foreach ($item["~VALUE"] as $keyValue => $itemValue){
                            $item["~VALUE"][$keyValue] = $PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST_XML"][$item["USER_TYPE_SETTINGS"]["TABLE_NAME"]]["VALUES"][$itemValue]["NAME"];
                        }
                    }
                    else
                    {
                        if(strlen($item["~VALUE"])>0)
                            $item["~VALUE"] = $PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST_XML"][$item["USER_TYPE_SETTINGS"]["TABLE_NAME"]]["VALUES"][$item["~VALUE"]]["NAME"];
                        else
                            $item["~VALUE"] = $PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST_XML"][$item["USER_TYPE_SETTINGS"]["TABLE_NAME"]]["VALUES"][$item["VALUE"]]["NAME"];
                    }

                   
                }
                else if($item["PROPERTY_TYPE"] == "E")
                {
                    if(is_array($item["VALUE"]))
                    {
                        foreach ($item["~VALUE"] as $keyValue => $itemValue){

                            if(array_key_exists($itemValue, $arPropEres))
                                $item["~VALUE"][$keyValue] = $arPropEres[$itemValue];
                            else
                                unset($item["~VALUE"][$keyValue]);
                        }

                    }
                    else
                    {
                        if(array_key_exists($item["VALUE"], $arPropEres))
                            $item["~VALUE"] = $arPropEres[$item["~VALUE"]];
                        else
                            unset($item["~VALUE"]);
                    }

                }

                else if($item["PROPERTY_TYPE"] == "G")
                {
                    if(is_array($item["VALUE"]))
                    {
                        foreach ($item["~VALUE"] as $keyValue => $itemValue){

                            if(array_key_exists($itemValue, $arPropGres))
                                $item["~VALUE"][$keyValue] = $arPropGres[$itemValue];
                            else
                                unset($item["~VALUE"][$keyValue]);
                        }

                    }
                    else
                    {

                        if(array_key_exists($item["VALUE"], $arPropGres))
                            $item["~VALUE"] = $arPropGres[$item["~VALUE"]];
                        else
                            unset($item["~VALUE"]);
                    }

                }

                else if($item["PROPERTY_TYPE"] == "F")
                {

                    if(is_array($item["VALUE"]))
                    {
                        foreach ($item["~VALUE"] as $keyValue => $itemValue){
                            $arFile = CFile::GetByID($itemValue);
                            $resFile = $arFile->Fetch();

                            $item["~VALUE"][$keyValue] = "<a href=\"".CFile::GetPath($itemValue)."\" target=\"_blank\">".$resFile["FILE_NAME"]."</a>";
                        }

                    }
                    else
                    {
                        $arFile = CFile::GetByID($item["VALUE"]);
                        $resFile = $arFile->Fetch();

                        $item["~VALUE"] = "<a href=\"".CFile::GetPath($item["VALUE"])."\" target=\"_blank\">".$resFile["FILE_NAME"]."</a>";
                    }
                    
                }
                
                $resValue = (is_array($item["VALUE"]))?implode(", ", $item["~VALUE"]) : $item["~VALUE"];

                if(strlen($resValue)>0)
                    $characteristics[] = array("NAME"=>$item["NAME"], "VALUE"=>$resValue, "HINT"=>$item["HINT"]);

            }
        }

        unset($arPropsNew, $arFields, $arProps);

        return $characteristics;
    }

    public static function getCharacteristicsCatalogItem($arProps, $showProps = "Y")
    {
        
        $characteristics = array();

        if( (!empty($arProps["VALUE"])) && $showProps == "Y")
        {

            foreach ($arProps["~VALUE"] as $key => $value)
            {
                $res = array();
                $res["NAME"] = $value;
                $res["VALUE"] = "";


                if( strlen($arProps["~DESCRIPTION"][$key]) )
                    $res["VALUE"] = $arProps["~DESCRIPTION"][$key];


                $characteristics[] = $res;
            }
        }

        unset($arProps);

        return $characteristics;
    }

    public static function fixedNumber($number)
    {
        if(strpos($number, ".") !== false)
            $number = (float)$number;
        else
            $number = (int)$number;

        return $number;
    }

    public static function OnBeforeUserAddHandler(&$arFields)
    {
    }
}


class CPhoenixDB{

    public static function CPhoenixMerge($arr)
    {
        $arRes = array();

        if(!empty($arr))
        {

            foreach ($arr as $key => $value) {
                $arRes[$key] = $value;
                $arRes["~".$key] = htmlspecialcharsBack($value);
            }
        }
        return $arRes;
    }
}