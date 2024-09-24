<?
use \Bitrix\Main\Config\Option as Option;
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

class CPhoenixSendOrderTable{

    public static function OnOrderNewSendEmailHandler($ID, &$eventName, &$arFields)
    {
      if ($ID > 0 && Loader::IncludeModule('sale') && CModule::IncludeModule('iblock') && Loader::IncludeModule('currency') && Loader::IncludeModule('catalog'))
      {
           

            $arOrder = CSaleOrder::GetByID($ID);

            $rsTemplates = CSite::GetTemplateList($arOrder["LID"]);

            $res = false;

            while($arTemplate = $rsTemplates->Fetch())
            { 
                if(substr_count($arTemplate["TEMPLATE"], "concept_phoenix_".$arOrder["LID"]) > 0)
                    $res = true;
            }

            if(!$res)
                return;

            CModule::IncludeModule("concept.phoenix");


            global $PHOENIX_TEMPLATE_ARRAY;

            
            CPhoenix::phoenixOptionsValues($arOrder["LID"], array(
                "start",
                "lids",
                "politic",
                "catalog",
                "catalog_fields",
                "other",
                "shop",
                "contacts",
                "region"
            ), "N", array("flagRegionSend"=>true));

            $arHIBlockOptions = CPhoenixSku::getHIBlockOptions(true);
           

            $rsBasket = CSaleBasket::GetList(array(), array('ORDER_ID' => $ID));
            $domen = CPhoenixHost::getHost($_SERVER);
            
            

            $order = Sale\Order::load($ID);

           
            $name = $order->getPropertyCollection()->getPayerName();
           
            if(!empty($name))
                $name = $name->getValue();


            $email = $order->getPropertyCollection()->getUserEmail();
           
            if(!empty($email))
                $email = $email->getValue();


            $phone = $order->getPropertyCollection()->getPhone();

            if(!empty($phone))
                $phone = $phone->getValue();



            

            $arFields["EMAIL_TO"] = $email;
            $arFields["EMAIL_FROM"] = $PHOENIX_TEMPLATE_ARRAY["SITE_EMAIL"];
            $arFields["EMAIL_REGION"] = $arFields["EMAIL_ADMIN"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_TO"]['VALUE'];


            $arFields["CPHX_BASKET_TABLE"] = "
                <table style='width: 100%;border: 0;border-collapse: collapse; margin: 0 0 25px;'>
                <tr>
                    <th style='font-family: Arial;font-size: 12px;text-align: left;padding: 5px 10px;border-top: 1px solid #aaa;border-bottom: 1px solid #aaa;'></th>
                    <th style='font-family: Arial;font-size: 12px;text-align: left;padding: 5px 10px;border-top: 1px solid #aaa;border-bottom: 1px solid #aaa;'>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TABLE_PRODUCT_NAME"]."</th>
                    <th style='font-family: Arial;font-size: 12px;text-align: left;padding: 5px 10px;border-top: 1px solid #aaa;border-bottom: 1px solid #aaa;'>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TABLE_PRODUCT_PRICE"]."</th>
                    <th style='font-family: Arial;font-size: 12px;text-align: left;padding: 5px 10px;border-top: 1px solid #aaa;border-bottom: 1px solid #aaa;'>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TABLE_PRODUCT_QUANTITY"]."</th>
                    <th style='font-family: Arial;font-size: 12px;text-align: left;padding: 5px 10px;border-top: 1px solid #aaa;border-bottom: 1px solid #aaa;'>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TABLE_PRODUCT_SUM"]."</th>
                </tr>
            ";


            $list = "";
            $price = 0;
            $price_new = 0;

            $curPrice = 0;
            $curPrice_new = 0;


            $discount = 0;
            $count = 0;

            $keyItem = 0;

            

            while ($ar_res = $rsBasket->GetNext())
            {
                if($ar_res["CAN_BUY"] != "Y") continue;

                if(intval($ar_res["SET_PARENT_ID"])>0)
                    continue;

                $keyItem++;

                $arItem = array();

                $arOptimalPrice = CCatalogProduct::GetOptimalPrice($ar_res["PRODUCT_ID"]);
                $basePrice = $arOptimalPrice["RESULT_PRICE"]["BASE_PRICE"];

                $price += $basePrice*$ar_res["QUANTITY"];
                $price_new += $ar_res["PRICE"]*$ar_res["QUANTITY"];


                $curPrice = 0;
                $curPrice_new = 0;

                $curPrice += $basePrice*$ar_res["QUANTITY"];
                $curPrice_new += $ar_res["PRICE"]*$ar_res["QUANTITY"];

                if($ar_res["MEASURE_CODE"] == 2)
                    $count++;
                else
                    $count += $ar_res["QUANTITY"];

                $nameOffers = "";



               

                $res = CIBlockElement::GetByID($ar_res["PRODUCT_ID"]);

                if($ar = $res->GetNextElement())
                {
                    $arCurProduct = $ar->GetFields();
                    $arCurProduct["PROPERTIES"] = $ar->getProperties();


                    if($arCurProduct["PROPERTIES"]["CML2_LINK"]["VALUE"] > 0)
                    {
                        $res = CIBlockElement::GetByID($arCurProduct["PROPERTIES"]["CML2_LINK"]["VALUE"]);
                        
                        if($ar = $res->GetNextElement())
                        {
                            $arMainProduct = $ar->GetFields();
                            $arMainProduct["PROPERTIES"] = $ar->getProperties();


                            $arItem["NAME"] = strip_tags($arMainProduct["~NAME"]);



                            if( !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"]) )
                            {
                                foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUE_"] as $arOfferField)
                                {

                                    

                                    if( $arCurProduct["PROPERTIES"][$arOfferField]["USER_TYPE"] == "directory" && strlen($arCurProduct["PROPERTIES"][$arOfferField]["VALUE"]) && !empty($arHIBlockOptions['SKU_PROP_LIST']))
                                    {
                                        
                                        foreach ($arHIBlockOptions['SKU_PROP_LIST'] as $arSKUProp)
                                        {
                                            if( $arCurProduct["PROPERTIES"][$arOfferField]['USER_TYPE_SETTINGS']['TABLE_NAME'] == $arSKUProp['TABLE_NAME'])
                                            {

                                                //$nameOffers .= $arCurProduct["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arSKUProp["VALUE_NAME"][$arCurProduct["PROPERTIES"][$arOfferField]["VALUE"]].";&nbsp;";



                                                $nameOffers .= $arCurProduct["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arSKUProp["VALUE_NAME"][$arCurProduct["PROPERTIES"][$arOfferField]["VALUE"]].";<br/>";

                                            }
                                            
                                        }

                                        
                                        
                                    }
                                    else if(strlen($arCurProduct["PROPERTIES"][$arOfferField]["VALUE"]))
                                    {
                                        //$nameOffers .= $arCurProduct["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arCurProduct["PROPERTIES"][$arOfferField]["VALUE"].";&nbsp;";
                                        $nameOffers .= $arCurProduct["PROPERTIES"][$arOfferField]["NAME"].":&nbsp;".$arCurProduct["PROPERTIES"][$arOfferField]["VALUE"].";<br/>";
                                    }
                                }

                                //$arItem["NAME"] .= "&nbsp;(".$nameOffers.")";
                            }



                              $photo = 0;

                              if($arCurProduct["DETAIL_PICTURE"])
                              {
                                  $photo = $arCurProduct["DETAIL_PICTURE"];
                              }

                              else if(!empty($arCurProduct["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                              {
                                  $photo = $arCurProduct["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];
                              }

                              else
                              {
                                    if($arCurProduct["PROPERTIES"]["NO_MERGE_PHOTOS"]["VALUE_XML_ID"] != "Y" )
                                    {
                                          if($arMainProduct["DETAIL_PICTURE"])
                                                $photo = $arMainProduct["DETAIL_PICTURE"];
                                          
                                          else if(!empty($arMainProduct["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                                                $photo = $arMainProduct["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];
                                    }
                              }

                              if($photo)
                              {
                                  $img = CFile::ResizeImageGet($photo, array('width'=>100, 'height'=>80), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                                  $arItem["PREVIEW_PICTURE_SRC"] = $domen.$img["src"];
                              }
                              else
                                  $arItem["PREVIEW_PICTURE_SRC"] = $domen.SITE_TEMPLATE_PATH."/images/ufo.png";

                            $arItem["DETAIL_PAGE"] = $domen.$arCurProduct["DETAIL_PAGE_URL"]."?oID=".$ar_res["PRODUCT_ID"];

                        }
                        
                    }

                    else
                    {

                        $arItem["NAME"] = strip_tags($arCurProduct["~NAME"]);

                        $photo = 0;

                        if($arCurProduct["DETAIL_PICTURE"])
                        {
                            $photo = $arCurProduct["DETAIL_PICTURE"];
                            
                        }

                        else if(!empty($arCurProduct["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
                        {
                            $photo = $arCurProduct["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0];
                        }

                        if($photo)
                        {
                            $img = CFile::ResizeImageGet($photo, array('width'=>100, 'height'=>80), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                            $arItem["PREVIEW_PICTURE_SRC"] = $domen.$img["src"];
                        }
                        else
                            $arItem["PREVIEW_PICTURE_SRC"] = $domen.SITE_TEMPLATE_PATH."/images/ufo.png";

                        $arItem["DETAIL_PAGE"] = $domen.$arCurProduct["DETAIL_PAGE_URL"];

                    }


                    $arItem["ARTICLE"] = strip_tags($arCurProduct["PROPERTIES"]["ARTICLE"]["~VALUE"]);

                    $list .= "<tr>";

                        $list .= "<td style='width: 15%;vertical-align: top;font-family: Arial;font-size: 12px;text-align: left;padding: 10px;border-bottom: 1px solid #aaa;'>
                                <img style='max-width: 85px;' src='".$arItem["PREVIEW_PICTURE_SRC"]."' alt=''/>
                            </td>";


                        $list .= "<td style='vertical-align: top;font-family: Arial;font-size: 12px;text-align: left;padding: 10px;border-bottom: 1px solid #aaa;'>
                                <div><a target='_blank' href='".$arItem["DETAIL_PAGE"]."'>".$arItem['NAME']."</a></div>";

                        if(strlen($arItem["ARTICLE"]))
                            $list .= "<div style='font-style: italic;'>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arItem["ARTICLE"]."</div>";

                        if(strlen($nameOffers))
                            $list .= "<div>".$nameOffers."</div>";

                        $list .= "</td>";


                        $list .= "<td style='vertical-align: top;white-space: nowrap;font-family: Arial;font-size: 12px;text-align: left;padding: 10px;border-bottom: 1px solid #aaa;'>";
                            


                            $list .= CCurrencyLang::CurrencyFormat($ar_res['PRICE'], $arOrder["CURRENCY"], true);


                            if(ceil($ar_res["PRICE"]) != ceil($basePrice))
                            {
                                $list .= "<div style='text-decoration:line-through'>".CCurrencyLang::CurrencyFormat($basePrice, $arOrder["CURRENCY"], true)."</div>";
                            }

                        $list .= "</td>";



                        $list .= "<td style='vertical-align: top;white-space: nowrap;font-family: Arial;font-size: 12px;text-align: left;padding: 10px;border-bottom: 1px solid #aaa;'>
                            ".$ar_res["QUANTITY"]." ".$ar_res["MEASURE_NAME"]."
                            </td>";



                        $list .= "<td style='vertical-align: top;white-space: nowrap;font-family: Arial;font-size: 12px;text-align: left;padding: 10px;border-bottom: 1px solid #aaa;'>
                            ";
                            $list .= CCurrencyLang::CurrencyFormat($curPrice_new, $arOrder["CURRENCY"], true);


                            if(ceil($ar_res["PRICE"]) != ceil($basePrice))
                            {
                                $list .= "<div style='text-decoration:line-through'>".CCurrencyLang::CurrencyFormat($curPrice, $arOrder["CURRENCY"], true)."</div>";
                            }

                        $list .= "</td>";



                    $list .= "</tr>";

                    $crm_list .= "<b>".($keyItem).". </b>".$arItem["NAME"];

                    if(strlen($nameOffers))
                        $crm_list .= " (".$nameOffers.")";

                    $crm_list .= ", "."<b>".GetMessage("PHOENIX_BASKET_CRM_MESS_PRICE")."</b>".CCurrencyLang::CurrencyFormat($ar_res['PRICE'], $arOrder["CURRENCY"], true).", "."<b>".GetMessage("PHOENIX_BASKET_CRM_MESS_QUANTITY")."</b>".$ar_res["QUANTITY"]." ".$ar_res["MEASURE_NAME"].", "."<b>".GetMessage("PHOENIX_BASKET_CRM_MESS_SUM")."</b>".CCurrencyLang::CurrencyFormat($curPrice_new, $arOrder["CURRENCY"], true).";<br/>";
                }


                unset($ar, $res, $arItem);
            }


            $arFields["CPHX_BASKET_TABLE"] .= $list."</table>";

            $arFields["CPHX_ACCOUNT_NUMBER"] = $arOrder["ACCOUNT_NUMBER"];


            //forms values
            $arFields["CPHX_FORM_INFO"] = "";

            


            $propertyCollection = $order->getPropertyCollection();

            $infoForAdmin = "";
            $infoForUser = "";

            $arProp = array();

            foreach ($propertyCollection->getGroups() as $group)
            {

                  $groupProperties = $propertyCollection->getPropertiesByGroupId($group['ID']);

                  if(!is_array($groupProperties))
                        continue;

                  /** @var \Bitrix\Sale\PropertyValue $property */
                  foreach ($propertyCollection->getPropertiesByGroupId($group['ID']) as $property)
                  {
                        if(strlen($property->getViewHtml())>0)
                        {
                            $arProp = $property->getProperty();

                            $valueProp = $property->getViewHtml();
                            
                            if($arProp["TYPE"] == "FILE")
                                $valueProp = $PHOENIX_TEMPLATE_ARRAY["SITE_URL"].$valueProp;

                            if($arProp["UTIL"] == "Y")
                            {
                                $infoForAdmin .= "<b>".htmlspecialcharsbx($property->getName()).":&nbsp;</b>".$valueProp."<br/>";
                            }
                            else
                            {
                                $infoForAdmin .= "<b>".htmlspecialcharsbx($property->getName()).":&nbsp;</b>".$valueProp."<br/>";

                                $infoForUser .= "<b>".htmlspecialcharsbx($property->getName()).":&nbsp;</b>".$valueProp."<br/>";
                            }

                            $arFields["CPHX_FORM_FIELD_".((strlen($arProp["CODE"])>0)?$arProp["CODE"]:$arProp["ID"])] = "<b>".htmlspecialcharsbx($property->getName()).":&nbsp;</b>".$property->getViewHtml();
                            $arFields["CPHX_FORM_FIELD_".((strlen($arProp["CODE"])>0)?$arProp["CODE"]:$arProp["ID"])."_NAME"] = htmlspecialcharsbx($property->getName());
                            $arFields["CPHX_FORM_FIELD_".((strlen($arProp["CODE"])>0)?$arProp["CODE"]:$arProp["ID"])."_VALUE"] = $valueProp;
                            
                        }
                  }
            }

            if(strlen($arOrder["USER_DESCRIPTION"])>0)
            {
                $infoForAdmin .= "<b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]['ORDER_USER_DESCRIPTION'].":&nbsp;</b>".$arOrder["USER_DESCRIPTION"]."<br/>";
                $infoForUser .= "<b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]['ORDER_USER_DESCRIPTION'].":&nbsp;</b>".$arOrder["USER_DESCRIPTION"]."<br/>";

                $arFields["CPHX_FORM_USER_DESCRIPTION"] = $arOrder["USER_DESCRIPTION"];
            }


            if(isset($_COOKIE["phoenix_UTM_SOURCE_".SITE_ID]) || isset($_COOKIE["phoenix_UTM_MEDIUM_".SITE_ID]) || isset($_COOKIE["phoenix_UTM_CAMPAIGN_".SITE_ID]) || isset($_COOKIE["phoenix_UTM_CONTENT_".SITE_ID]) || isset($_COOKIE["phoenix_UTM_TERM_".SITE_ID]))
               $infoForAdmin .= "<br/><br/>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_UTM_TITLE"]."<br/>";


            if(isset($_COOKIE["phoenix_UTM_SOURCE_".SITE_ID]))
                $infoForAdmin .= "utm_source: ".$_COOKIE["phoenix_UTM_SOURCE_".SITE_ID];

            if(isset($_COOKIE["phoenix_UTM_MEDIUM_".SITE_ID]))
                $infoForAdmin .= "<br/>utm_medium: ".$_COOKIE["phoenix_UTM_MEDIUM_".SITE_ID];

            if(isset($_COOKIE["phoenix_UTM_CAMPAIGN_".SITE_ID]))
                $infoForAdmin .= "<br/>utm_campaign: ".$_COOKIE["phoenix_UTM_CAMPAIGN_".SITE_ID];

            if(isset($_COOKIE["phoenix_UTM_CONTENT_".SITE_ID]))
                $infoForAdmin .= "<br/>utm_content: ".$_COOKIE["phoenix_UTM_CONTENT_".SITE_ID];

            if(isset($_COOKIE["phoenix_UTM_TERM_".SITE_ID]))
                $infoForAdmin .= "<br/>utm_term: ".$_COOKIE["phoenix_UTM_TERM_".SITE_ID];


            $arFields["CPHX_FORM_INFO"] = $infoForUser;
            $arFields["CPHX_FORM_INFO_FOR_ADMIN"] = $infoForAdmin;

            $arFields["CPHX_USER_NAME"] = "";
            $arFields["CPHX_USER_PHONE"] = "";
            $arFields["CPHX_USER_EMAIL"] = "";

            if($name)
                  $arFields["CPHX_USER_NAME"] = $name;

            if($phone)
                  $arFields["CPHX_USER_PHONE"] = $phone;

            if($email)
                  $arFields["CPHX_USER_EMAIL"] = $email;


            

            $arFields["CPHX_TOTAL_INFO"] = "";
            $crm_total_info = "";


            //delivery

            $arAllActiveDelivery = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();

            $curDeliveryID = 0;

            foreach ($arAllActiveDelivery as $arDelivery)
            {
                if(strlen($arDelivery["CODE"])>0)
                {
                    if($arDelivery["CODE"] == $arOrder["DELIVERY_ID"])
                    {
                        $curDeliveryID = $arDelivery["ID"];
                    }
                }
                else
                {
                    if($arDelivery["ID"] == $arOrder["DELIVERY_ID"])
                    {
                        $curDeliveryID = $arDelivery["ID"];
                    }
                }
            }


            if($curDeliveryID>0)
            {
                  $deliveryAr = \Bitrix\Sale\Delivery\Services\Manager::getById($curDeliveryID);

                  $shipmentCollection = $order->getShipmentCollection();
                  $stores = \Bitrix\Sale\Delivery\ExtraServices\Manager::getExtraServicesList($curDeliveryID);


                  $extraDelivery = "";
                  foreach ($shipmentCollection as $shipment)
                  {
                      $extra = $shipment->getExtraServices();

                   
                      if(!empty($extra))
                      {
                          foreach($extra as $key => $extraValue){

                              $nameValue = $value = $description= "";

                              if(strlen($extraValue)>0)
                              {
                                  if($stores[$key]["CLASS_NAME"] == "\Bitrix\Sale\Delivery\ExtraServices\Enum")
                                  {
                                      $nameValue = $stores[$key]["NAME"];
                                      $value = $stores[$key]["PARAMS"]["PRICES"][$extraValue]["TITLE"];
                                  }
                                  else if($stores[$key]["CLASS_NAME"] == "\Bitrix\Sale\Delivery\ExtraServices\Quantity")
                                  {
                                      $nameValue = $stores[$key]["NAME"];
                                      $value = $extraValue;
                                  }
                                  else if($stores[$key]["CLASS_NAME"] == "\Bitrix\Sale\Delivery\ExtraServices\Checkbox")
                                  {
                                      $nameValue = $stores[$key]["NAME"];
                                      $value = ($extraValue == "Y")?$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DELIVERY_EXTRA_Y"]:$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DELIVERY_EXTRA_N"];
                                  }

                                  if(strlen($stores[$key]["DESCRIPTION"])>0)
                                      $description = " (".$stores[$key]["DESCRIPTION"].")";

                                  $extraDelivery .= "<div>&mdash;&nbsp;<b>".$nameValue."</b>".$description."&nbsp;&mdash;&nbsp;".$value.";</div>";
                                  
                              }
                              
                          }
                      }
                  }


                  $arFields["CPHX_TOTAL_INFO"] .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DELIVERY_TITLE"]."</b> ".$deliveryAr["NAME"]."</div>";

                  

                  $crm_total_info .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DELIVERY_TITLE"]."</b> ".$deliveryAr["NAME"]."</div>";

                  if(strlen($extraDelivery)>0)
                  {
                        $extraDelivery = "<b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DELIVERY_EXTRA_TITLE"]."</b>".$extraDelivery;
                        $arFields["CPHX_TOTAL_INFO"] .= $extraDelivery;
                        $crm_total_info .= $extraDelivery;
                  }
                
            }


            //pay system
            if ($arPaySys = CSalePaySystem::GetByID($arOrder["PAY_SYSTEM_ID"], $arOrder["PERSON_TYPE_ID"]))
            {
                $arFields["CPHX_TOTAL_INFO"] .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_PAY_SYSTEM_TITLE"]."</b> ".$arPaySys["NAME"]."</div>";

                $crm_total_info .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_PAY_SYSTEM_TITLE"]."</b> ".$arPaySys["NAME"]."</div>";
            }


            $arFields["CPHX_TOTAL_INFO"] .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_SUM"]."</b> ".CCurrencyLang::CurrencyFormat($price_new, $arOrder["CURRENCY"], true)."</div>";


            $crm_total_info .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_SUM"]."</b> ".CCurrencyLang::CurrencyFormat($price_new, $arOrder["CURRENCY"], true)."</div>";


            $discount = $price - $price_new;



            if($discount > 0)
            {
                $arFields["CPHX_TOTAL_INFO"] .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DISCOUNT"]."</b> ".CCurrencyLang::CurrencyFormat($discount, $arOrder["CURRENCY"], true)."</div>";

                $crm_total_info .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DISCOUNT"]."</b> ".CCurrencyLang::CurrencyFormat($discount, $arOrder["CURRENCY"], true)."</div>";
            }

            if($arOrder["PRICE_DELIVERY"])
            {
                $arFields["CPHX_TOTAL_INFO"] .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DELIVERY_COST"]."</b> ".CCurrencyLang::CurrencyFormat($arOrder["PRICE_DELIVERY"], $arOrder["CURRENCY"], true)."</div>";

                $crm_total_info .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DELIVERY_COST"]."</b> ".CCurrencyLang::CurrencyFormat($arOrder["PRICE_DELIVERY"], $arOrder["CURRENCY"], true)."</div>";
            }

            /*if($price != $price_new)
            {*/
                $arFields["CPHX_TOTAL_INFO"] .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TOTAL"]."</b> ".CCurrencyLang::CurrencyFormat($arOrder["PRICE"], $arOrder["CURRENCY"], true)."</div>";

                $crm_total_info .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TOTAL"]."</b> ".CCurrencyLang::CurrencyFormat($arOrder["PRICE"], $arOrder["CURRENCY"], true)."</div>";
            /*}*/


            $message = $arFields["CPHX_FORM_INFO_FOR_ADMIN"]."<br/><br/>"."<b>".GetMessage("PHOENIX_BASKET_CRM_MESS_ORDER_ID")."</b>".$arFields["ORDER_ID"]."<br/><br/>"."<b>".GetMessage("PHOENIX_BASKET_CRM_MESS_ORDER_LIST")."</b><br/>".$crm_list."<br/>".GetMessage("PHOENIX_BASKET_CRM_MESS_DASHED")."<br/>".$crm_total_info."<br/>";

            /*$name = $arFields["ORDER_USER"];
            $phone = "";
            $email = $arFields["EMAIL"];*/

            require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/concept.phoenix/crm.php');
            
            

            $header = GetMessage("PHOENIX_BASKET_CRM_MESS_BASKET_HEADER");

            require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/concept.phoenix/classes/crm/bx24.php');
            require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/concept.phoenix/classes/crm/amocrm_old.php');



      }
    }
}
?>