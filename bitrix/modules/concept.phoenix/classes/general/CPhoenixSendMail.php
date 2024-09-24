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
    Bitrix\Sale\DiscountCouponsManager,
    Bitrix\CPhoenixComments;



class CPhoenixSendMail{

    public static function checkValid($siteID, $arParams = array())
    {
        global $PHOENIX_TEMPLATE_ARRAY;
        $arRes = array(
            'CAPTCHA_SCORE'=> 0
        );

        $go = $arParams["GO"];

        CPhoenix::phoenixOptionsValues($siteID, array("other"));

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA"]["VALUE"]["ACTIVE"]=="Y")
        {

            if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SECRET_KEY"]["VALUE"])>0 && strlen($_POST['captchaToken'])>0)
            {
                  $Response = CPhoenixFunc::getContentFromUrl("https://www.google.com/recaptcha/api/siteverify?secret=".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SECRET_KEY"]["VALUE"]."&response={$_POST['captchaToken']}");
                  $Return = json_decode($Response);
                  $arRes["CAPTCHA_SCORE"] = $Return->score;
                  if(!($Return->success == true && $Return->score >= floatval($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SCORE"]["VALUE"])))
                        $go = false;
            }
            else
                  $go = false;

        }

        $arRes['GO'] = $go;

        return $arRes;

    }
    
    public static function Send($siteID)
    {
      global $PHOENIX_TEMPLATE_ARRAY;
      global $APPLICATION;

      $arRes = Array();
      $arRes["OK"] = "N";
      $arRes["SCRIPTS"] = '';

      $go = true;

      if(strlen($_POST["name"])>0 || strlen($_POST["phone"])>0 || strlen($_POST["email"])>0 || $_POST["send"] != "Y")
            $go = false;

        $resValid = CPhoenixSendMail::checkValid($siteID, array("GO"=>$go));
        $go = $resValid["GO"];
        $arRes["CAPTCHA_SCORE"] = $resValid["CAPTCHA_SCORE"];
      

      if($go)
      {

            if(!Loader::includeModule('iblock'))
                return;

            CModule::IncludeModule('concept.phoenix');
            CPhoenix::phoenixOptionsValues($siteID, array(

                "start",
                "lids",
                "politic",
                "catalog",
                "catalog_fields",
                "shop",
                "contacts",
                "other",
                "services",
                "region",

            ));


            CPhoenixSku::getHIBlockOptions();
            
            $domen = CPhoenixHost::getHost($_SERVER);

            $rsSites = CSite::GetByID($siteID);
            $arSite = $rsSites->Fetch();


            $email_to = $email_from = "";

            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_FROM"]['VALUE']) > 0)
                $email_from = htmlspecialcharsBack(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_FROM"]['VALUE']));
            

            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_TO"]['VALUE']) > 0)
                $email_to = htmlspecialcharsBack(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_TO"]['VALUE']));



            $arMailtoAdmin = array();

            if(strlen($email_to)>0)
                $arMailtoAdmin = explode(",", $email_to);

            unset($email_to);

        
            $header = trim($_POST["header"]);
            $url = trim($_POST["url"]);

            $url = urldecode ($url);


            if(trim(SITE_CHARSET) == "windows-1251")
            {
                $header = utf8win1251(trim($_POST["header"]));
                $url = utf8win1251($url);
            }

            $customParam = array();

            $mesParams = "";

            for ($i=1; $i <= 10 ; $i++)
            { 
                if(strlen($_POST["custom-input-".$i]))
                {

                    if(trim(SITE_CHARSET) == "windows-1251")
                        $_POST["custom-input-".$i] = utf8win1251(trim($_POST["custom-input-".$i]));

                    $mesParams .= "<div>".$_POST["custom-input-".$i]."</div>";
                    
                }
            }
            for ($i=1; $i <= 3 ; $i++)
            { 
                if(strlen($_POST["custom-dynamic-input-".$i]))
                {

                    if(trim(SITE_CHARSET) == "windows-1251")
                        $_POST["custom-dynamic-input-".$i] = utf8win1251(trim($_POST["custom-dynamic-input-".$i]));

                    $mesParams .= "<div>".$_POST["custom-dynamic-input-".$i]."</div>";
                    
                }
            }


            if($_POST["typeForm"] == "fast_order")
            {

                global $USER;

                if (Loader::IncludeModule('sale') && Loader::IncludeModule('iblock'))
                {
                    $arRes["FROM_BASKET"] = ($_POST["fromBasket"]=="Y") ? true : false;

                    $startFastOrder = true;

                    $showBuyBtn = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_BASKET_ON"]["VALUE"]["ACTIVE"] == "Y") ? true : false;
                    if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"])&& !in_array('Y', $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"]))
                        $showBuyBtn = false;

                    if(
                        (is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"]) && !in_array('Y', $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]["CUR_VALUE"]]["VALUE"]))
                        && ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_PRODUCT_ON"]["VALUE"]["ACTIVE"] !== 'Y' || !$showBuyBtn)

                    )
                        $startFastOrder = false;



                    if($startFastOrder)
                    {
                        $startCreateFastOrder = false;

                        function getPropertyByCode($propertyCollection, $code)  
                        {
                            if(!empty($propertyCollection))
                            {
                                foreach ($propertyCollection as $property)
                                {
                                    $tmpcode = $property->getField('CODE');

                                    if(strlen($tmpcode)<=0)
                                    {
                                        $propertyInfo = $property->getProperty();
                                        $tmpcode = $propertyInfo["ID"];
                                    }

                                    if($tmpcode == $code)
                                        return $property;
                                }
                                unset($tmpcode);
                            }
                        }


                        $name = "";
                        $phone = "";
                        $email = "";
                        $form_inputs = "";
                        $str_props = '';
                        $flag_props_req = true;


                        
                        if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUE']))
                        {
                            if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUE']))
                            {
                                foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUE'] as $key => $value)
                                {

                                    if($value == "Y")
                                    {
                                        if($key === "COMMENT")
                                            continue;



                                        if( isset( $_POST[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["CODE"]] ) )
                                        {
                                            $tmpProperty = "";

                                            $prop = trim($_POST[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["CODE"]]);

                                            if(trim(SITE_CHARSET) == "windows-1251")
                                                $prop = utf8win1251($prop);
                                            else
                                            {
                                                $prop = CPhoenixSendMail::wp_encode_emoji($prop);
                                            }


                                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["IS_PAYER"] == "Y")
                                                $name = $prop;                                
                                            
                                            else if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["IS_PHONE"] == "Y")
                                                $phone = $prop;
                                            
                                            else if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["IS_EMAIL"] == "Y")
                                                $email = $prop;
                                            

                                            
                                            $form_inputs .= "<b>".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["NAME"].": </b>".$prop."<br>";


                                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUE'][$key] === 'Y')
                                            {
                                                if(strlen(trim($prop))<=0)
                                                    $flag_props_req = false;
                                            }

                                            $str_props .= trim($prop);
                                        }

                                    }
                                    
                                }
                            }

                        }


                        if(strlen($str_props)>0 && $flag_props_req)
                            $startCreateFastOrder = true;
                        
                      
                        if($startCreateFastOrder)
                        {

                            \Bitrix\Sale\Notify::setNotifyDisable(true); 

                            $currencyCode = CCurrency::GetBaseCurrency();

                            DiscountCouponsManager::init();

                            $createNewUser = false;
                            $showConfirm = false;
                            $actionAuth = true;

                            if($USER->isAuthorized())
                            {
                                $registeredUserID = $USER->GetID();
                                /*$rsUser = CUser::GetByID($registeredUserID);
                                $arUser = $rsUser->Fetch();*/
                            }

                            else
                            {

                                if(strlen($email)>0)
                                {
                                    $rsUser = CUser::GetByLogin($email);
                                    if($rsUser->SelectedRowsCount() <= 0)
                                    {
                                        $createNewUser = true;
                                    }
                                    else
                                    {
                                        $showConfirm = true;
                                        $actionAuth = false;

                                        if($arUser = $rsUser->Fetch())
                                            $registeredUserID = $arUser["ID"];
                                    }
                                }
                                else
                                {
                                    $createNewUser = true;
                                    $actionAuth = false;
                                    $showConfirm = true;
                                }

                                if($createNewUser)
                                {

                                    /*$emailUniq = (COption::GetOptionString("main", "new_user_email_uniq_check"));*/

                                    $uind = 0;
                                    do
                                    {
                                        $uind++;
                                        
                                        if ($uind == 10)
                                        {
                                            $newLogin = "buyer".time().GetRandomCode(2);
                                            $newLoginTmp = $newLogin;
                                        }
                                        elseif ($uind > 10)
                                        {
                                            $newLogin = "buyer".time().GetRandomCode(2);
                                            $newLoginTmp = $newLogin;
                                            break;
                                        }
                                        else
                                        {
                                            $newLoginTmp = "buyer".time().GetRandomCode(2);
                                        }
                                        
                                        $dbUserLogin = CUser::GetByLogin($newLoginTmp);
                                    }
                                    while ($arUserLogin = $dbUserLogin->Fetch());


                                
                                    $newLogin = (strlen($email)>0)?$email:$newLoginTmp;
                                    $newEmail = $email;

                                    $email_required = \Bitrix\Main\Config\Option::get("main", "new_user_email_required", "");

                                    if($email_required === 'Y' && strlen($newEmail)<=0)
                                    	$newEmail = $newLogin."@mail.ru";

                                    
                                    
                                    
                                    $def_group = Option::get("main", "new_user_registration_def_group", "");
                                    
                                    if ($def_group != "")
                                    {
                                        $groupID = explode(",", $def_group);
                                        $arPolicy = $USER->GetGroupPolicy($groupID);
                                    }
                                    else
                                    {
                                        $arPolicy = $USER->GetGroupPolicy(array());
                                    }

                                    $password_min_length = intval($arPolicy["PASSWORD_LENGTH"]);
                                    
                                    if ($password_min_length <= 0)
                                        $password_min_length = 6;
                                        
                                    $password_chars = array(
                                        "abcdefghijklnmopqrstuvwxyz",
                                        "ABCDEFGHIJKLNMOPQRSTUVWXYZ",
                                        "0123456789",
                                    );
                                    
                                    if ($arPolicy["PASSWORD_PUNCTUATION"] === "Y")
                                        $password_chars[] = ",.<>/?;:'\"[]{}\|`~!@#\$%^&*()-_+=";
                                        
                                    $newPassword = $newPasswordConfirm = randString($password_min_length+2, $password_chars);

                               

                                    
                                    $userProps = array(
                                        'NEW_EMAIL' => $newEmail,
                                        'NEW_LOGIN' => $newLogin,
                                        'NEW_NAME' => (strlen($name)>0)?$name:"",
                                        'NEW_LAST_NAME' => "",
                                        'NEW_PASSWORD' => $newPassword,
                                        'NEW_PASSWORD_CONFIRM' => $newPasswordConfirm,
                                        'GROUP_ID' => $groupID,
                                        'NEW_PHONE'=> (strlen($phone)>0)?$phone:"",
                                    );


                                    
                                    
                                    $userId = false;
                                    $userData = $userProps;

                                    $user = new CUser;
                                    
                                    $arAuthResult = $user->Add(array(
                                        "LOGIN" => $userData['NEW_LOGIN'],
                                        "NAME" => $userData['NEW_NAME'],
                                        "LAST_NAME" => $userData['NEW_LAST_NAME'],
                                        "PASSWORD" => $userData['NEW_PASSWORD'],
                                        "CONFIRM_PASSWORD" => $userData['NEW_PASSWORD_CONFIRM'],
                                        "PERSONAL_PHONE"=> $userData['NEW_PHONE'],
                                        "EMAIL" => $userData['NEW_EMAIL'],
                                        "GROUP_ID" => $userData['GROUP_ID'],
                                        "ACTIVE" => "Y",
                                        "LID" => $siteID
                                    ));
                                    
                                    $registeredUserID = intval($arAuthResult);

                                    if($actionAuth)
                                        $USER->Authorize($registeredUserID);
                                    
                                }

                            }


                            if($arRes["FROM_BASKET"])
                            {
                                $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $siteID);


                                $order = Order::create($siteID, $registeredUserID);
                                $order->setPersonTypeId($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]);

                                
                            }

                            else
                            {

                                $item = $_POST["productID"];
                                $quantity = $_POST["productQuantity"];

                                if($quantity <= 0)
                                    $quantity = 1;

                                $basket = Sale\Basket::create($siteID); 


                                $item_res = CIBlockElement::GetByID($item);
                                $ar_item_res = $item_res->GetNext();

                                $item = $basket->createItem('catalog', $item); 

                                $item->setFields(array( 
                                    'QUANTITY' => $quantity, 
                                    'CURRENCY' => $currencyCode, 
                                    'LID' => $siteID, 
                                    'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider', 
                                    "PRODUCT_XML_ID" => $ar_item_res["EXTERNAL_ID"],
                                    "CATALOG_XML_ID" => $ar_item_res["IBLOCK_EXTERNAL_ID"]
                                ));
                                
                                $basket->save();

                                $order = Order::create($siteID, $registeredUserID);
                                $order->setPersonTypeId($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]);
                                
                            }

                            $order->setBasket($basket);

                            /*Shipment*/

                            /*$shipmentCollection = $order->getShipmentCollection();
                            $shipment = $shipmentCollection->createItem();
                            $shipment->setFields(array(
                                'DELIVERY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['DELIVERY']["VALUE"],
                                'DELIVERY_NAME' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['DELIVERY']["DESCRIPTIONS"][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['DELIVERY']["VALUE"]],
                                'CURRENCY' => $order->getCurrency()
                            ));

                            $shipmentItemCollection = $shipment->getShipmentItemCollection();

                            foreach ($order->getBasket() as $item)
                            {
                                $shipmentItem = $shipmentItemCollection->createItem($item);
                                $shipmentItem->setQuantity($item->getQuantity());
                            }*/


                            /*Payment*/
                            /*$paymentCollection = $order->getPaymentCollection();
                            $extPayment = $paymentCollection->createItem();
                            $extPayment->setFields(array(
                                'PAY_SYSTEM_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PAY_SYSTEM']["VALUE"],
                                'PAY_SYSTEM_NAME' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PAY_SYSTEM']["DESCRIPTIONS"][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PAY_SYSTEM']["VALUE"]],
                                'SUM' => $order->getPrice()
                            ));*/
                            
                            $order->doFinalAction(true);

                            

                            $propertyCollection = $order->getPropertyCollection();


                            if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUE']))
                            {
                                if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUE']))
                                {
                                    foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUE'] as $key => $value)
                                    {

                                        if($value == "Y")
                                        {

                                            if($key === "COMMENT")
                                                continue;

                                            if( isset( $_POST[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["CODE"]] ) )
                                            {
                                                $tmpProperty = "";

                                                $prop = trim($_POST[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["CODE"]]);

                                                if(trim(SITE_CHARSET) == "windows-1251")
                                                    $prop = utf8win1251($prop);

                                                $tmpProperty = getPropertyByCode($propertyCollection, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["CODE"]);


                                                if($tmpProperty != NULL)
                                                    $tmpProperty->setValue($prop);
                                             
                                            }

                                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["TYPE"] === 'FILE'
                                                && isset( $_FILES[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["CODE"]] )

                                            )
                                            {
                                                $file = $_FILES[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["CODE"]];
                                                

                                                if(trim(SITE_CHARSET) == "windows-1251")
                                                    $file['name'] = utf8win1251($file['name']);


                                                $tmpProperty = getPropertyByCode($propertyCollection, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["CODE"]);

                                                $fid = CFile::SaveFile($file, "phoenix");


                                                

                                                if($tmpProperty != NULL)
                                                {
                                                    $tmpProperty->setValue($fid);
                                                    $form_inputs .= "<b>".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES'][$key]["PROPS"]["NAME"].": </b><a href='".CFile::GetPath($fid)."'>".$file['name']."</a><br>";

                                                }
                                            }
                                        }
                                        
                                    }
                                }

                            }

                            $comment = "";

                            if(isset($_POST["USER_DESCRIPTION"]))
                            {
                                $comment = trim($_POST["USER_DESCRIPTION"]);

                                if(trim(SITE_CHARSET) == "windows-1251")
                                {
                                    $comment = utf8win1251($comment);
                                }
                                else
                                {
                                    $comment = CPhoenixSendMail::wp_encode_emoji($comment);
                                }

                                $form_inputs .= "<b>".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']["CUR_VALUE"]]['VALUES']["COMMENT"]["DESCRIPTION"].": </b>".$comment."<br>";
                            }


                            /*$order->setField('CURRENCY', $currencyCode);*/
                            $order->setField('USER_DESCRIPTION', $comment);
                            $order->save();
                            $orderId = $order->GetId();


                            if($showConfirm)
                                $_SESSION['SALE_ORDER_ID'] = Array($orderId);
                            


                            $arOrder = CSaleOrder::GetByID($orderId);    
                            $arRes["ID"] = $arOrder["ACCOUNT_NUMBER"];

                            if($orderId)
                                $arRes["OK"] = "Y";
                            
                            $sum = 0;        
                
                            $order_list = "";
                            
                            $arShoppingCart = Array();

                            $dbBasketItems = CSaleBasket::GetList(
                                array(
                                        "NAME" => "ASC",
                                        "ID" => "ASC"
                                    ),
                                array(
                                        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                                        "LID" => $siteID,
                                        "ORDER_ID" => $orderId
                                    ),
                                false,
                                false,
                                array("ID", "NAME", "CALLBACK_FUNC", "MODULE", "PROPERTIES", 
                                      "PRODUCT_ID", "QUANTITY", "DELAY", 
                                      "CAN_BUY", "PRICE", "WEIGHT", "BASE_PRICE", "MEASURE_CODE", "MEASURE_NAME")
                            );


                            $BASKET_TABLE = "
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

                            while ($ar_res = $dbBasketItems->Fetch())
                            {
                                if($ar_res["CAN_BUY"] != "Y") continue;

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
                                                    if( $arCurProduct["PROPERTIES"][$arOfferField]["USER_TYPE"] == "directory" && strlen($arCurProduct["PROPERTIES"][$arOfferField]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"]))
                                                    {
                                                        
                                                        foreach ($PHOENIX_TEMPLATE_ARRAY["SKU_PROP_LIST"] as $arSKUProp)
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

                                                $arItem["PREVIEW_PICTURE_SRC"] = $img["src"];
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

                                                $arItem["PREVIEW_PICTURE_SRC"] = $img["src"];
                                            }
                                            else
                                                $arItem["PREVIEW_PICTURE_SRC"] = $domen.SITE_TEMPLATE_PATH."/images/ufo.png";



                                        $arItem["DETAIL_PAGE"] = $domen.$arCurProduct["DETAIL_PAGE_URL"];

                                    }


                                    $arItem["ARTICLE"] = strip_tags($arCurProduct["PROPERTIES"]["ARTICLE"]["~VALUE"]);


                                    $list .= "<tr>";

                                        $list .= "<td style='width: 15%;vertical-align: top;font-family: Arial;font-size: 12px;text-align: left;padding: 10px;border-bottom: 1px solid #aaa;'>
                                                <img src='".$domen.$arItem["PREVIEW_PICTURE_SRC"]."'>
                                            </td>";


                                        $list .= "<td style='vertical-align: top;font-family: Arial;font-size: 12px;text-align: left;padding: 10px;border-bottom: 1px solid #aaa;'>
                                                <div><a target='_blank' href='".$arItem["DETAIL_PAGE"]."'>".$arItem['NAME']."</a></div>";

                                        if(strlen($arItem["ARTICLE"]))
                                            $list .= "<div style='font-style: italic;'>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arItem["ARTICLE"]."</div>";

                                        if(strlen($nameOffers))
                                            $list .= "<div>".$nameOffers."</div>";

                                        $list .= "</td>";


                                        $list .= "<td style='vertical-align: top;white-space: nowrap;font-family: Arial;font-size: 12px;text-align: left;padding: 10px;border-bottom: 1px solid #aaa;'>";
                                            


                                            $list .= CCurrencyLang::CurrencyFormat($ar_res['PRICE'], $currencyCode, true);


                                            if(ceil($ar_res["PRICE"]) != ceil($basePrice))
                                            {
                                                $list .= "<div style='text-decoration:line-through'>".CCurrencyLang::CurrencyFormat($basePrice, $currencyCode, true)."</div>";
                                            }

                                        $list .= "</td>";



                                        $list .= "<td style='vertical-align: top;white-space: nowrap;font-family: Arial;font-size: 12px;text-align: left;padding: 10px;border-bottom: 1px solid #aaa;'>
                                            ".$ar_res["QUANTITY"]." ".$ar_res["MEASURE_NAME"]."
                                            </td>";



                                        $list .= "<td style='vertical-align: top;white-space: nowrap;font-family: Arial;font-size: 12px;text-align: left;padding: 10px;border-bottom: 1px solid #aaa;'>
                                            ";
                                            $list .= CCurrencyLang::CurrencyFormat($curPrice_new, $currencyCode, true);


                                            if(ceil($ar_res["PRICE"]) != ceil($basePrice))
                                            {
                                                $list .= "<div style='text-decoration:line-through'>".CCurrencyLang::CurrencyFormat($curPrice, $currencyCode, true)."</div>";
                                            }

                                        $list .= "</td>";



                                    $list .= "</tr>";


                                    $crm_list .= "<b>".($keyItem).". </b>".$arItem["NAME"];

                                    if(strlen($nameOffers))
                                        $crm_list .= " (".$nameOffers.")";

                                    $crm_list .= ", "."<b>".GetMessage("PHOENIX_BASKET_CRM_MESS_PRICE")."</b>".CCurrencyLang::CurrencyFormat($basePrice, $currencyCode, true).", "."<b>".GetMessage("PHOENIX_BASKET_CRM_MESS_QUANTITY")."</b>".$ar_res["QUANTITY"]." ".$ar_res["MEASURE_NAME"].", "."<b>".GetMessage("PHOENIX_BASKET_CRM_MESS_SUM")."</b>".CCurrencyLang::CurrencyFormat($curPrice_new, $currencyCode, true).";<br/>";

                                }
                                unset($ar, $res, $arItem);
                            }


                            $BASKET_TABLE .= $list."</table>";


                            $discount = $price - $price_new;
                            

                            $TOTAL_INFO .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_SUM"]."</b> ".CCurrencyLang::CurrencyFormat($price, $currencyCode, true)."</div>";

                            $crm_total_info .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_SUM"]."</b> ".CCurrencyLang::CurrencyFormat($price, $currencyCode, true)."</div>";

                            if($discount>0)
                            {
                                $TOTAL_INFO .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DISCOUNT"]."</b> ".CCurrencyLang::CurrencyFormat($discount, $currencyCode, true)."</div>";

                                $crm_total_info .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_DISCOUNT"]."</b> ".CCurrencyLang::CurrencyFormat($discount, $currencyCode, true)."</div>";
                            }
                            
                            if($price != $price_new)
                            {
                                $TOTAL_INFO .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TOTAL"]."</b> ".CCurrencyLang::CurrencyFormat($price_new, $currencyCode, true)."</div>";

                                $crm_total_info .= "<div><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["BASKET_TOTAL"]."</b> ".CCurrencyLang::CurrencyFormat($price_new, $currencyCode, true)."</div>";
                            }


                            $rsSites = CSite::GetByID($siteID);
                            $arSite = $rsSites->Fetch();
                            
                            $search = array(
                                "#SITE_NAME#",
                                "#ORDER_ID#",
                                "#NAME_USER#",
                                "#PHONE_USER#",
                                "#EMAIL_USER#",
                                "#BASKET_TABLE#",
                                "#TOTAL_INFO#",
                                "#DISCOUNT#",
                                "#TOTAL_SUM#",
                                "#URL#",
                                "#URL_ORDER#",
                                "#FORM_INFO#"
                            );

                            $replace   = array(
                                $arSite["NAME"],
                                $arOrder["ACCOUNT_NUMBER"],
                                $name,
                                $phone,
                                $email,
                                $BASKET_TABLE,
                                $TOTAL_INFO,
                                CCurrencyLang::CurrencyFormat($discount, $currencyCode, true),
                                CCurrencyLang::CurrencyFormat($price_new, $currencyCode, true),
                                $domen.$arSite["DIR"],
                                $domen."/bitrix/admin/sale_order_view.php?ID=".$arOrder["ACCOUNT_NUMBER"],
                                $form_inputs
                            );


                            $themeForAdmin = str_replace($search, $replace, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MESS_THEME_ADMIN"]["~VALUE"]);
                            $messageForAdmin = str_replace($search, $replace, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MESS_ADMIN"]["~VALUE"]);

                            $themeForUser = str_replace($search, $replace, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MESS_THEME_USER"]["~VALUE"]);
                            $messageForUser = str_replace($search, $replace, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MESS_USER"]["~VALUE"]);


                            $textThank = str_replace($search, $replace, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_COMPLITED_MESS"]["~VALUE"]);


                            if(trim(SITE_CHARSET) == "windows-1251")
                            {
                                $textThank = iconv('windows-1251', 'UTF-8', $textThank);
                            }

                            $arRes["TEXT_THANK"] = $textThank;



                            $message = $form_inputs."<br/><br/>"."<b>".GetMessage("PHOENIX_BASKET_CRM_MESS_ORDER_ID")."</b>".$arOrder["ACCOUNT_NUMBER"]."<br/><br/>"."<b>".GetMessage("PHOENIX_BASKET_CRM_MESS_ORDER_LIST")."</b><br/>".$crm_list."<br/>".GetMessage("PHOENIX_BASKET_CRM_MESS_DASHED")."<br/>".$crm_total_info."<br/>";

                            
                            

                            if(strlen($mesParams))
                            {
                                $message .= "<br/><br/>".$mesParams;
                                $messageForAdmin .= "<br/><br/>".$mesParams;
                            }

                            if(isset($_COOKIE["phoenix_UTM_SOURCE_".$siteID]) || isset($_COOKIE["phoenix_UTM_MEDIUM_".$siteID]) || isset($_COOKIE["phoenix_UTM_CAMPAIGN_".$siteID]) || isset($_COOKIE["phoenix_UTM_CONTENT_".$siteID]) || isset($_COOKIE["phoenix_UTM_TERM_".$siteID]))
                               $messageForAdmin .= "<br/><br/>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_UTM_TITLE"]."<br/>";


                            if(isset($_COOKIE["phoenix_UTM_SOURCE_".$siteID]))
                                $messageForAdmin .= "utm_source: ".$_COOKIE["phoenix_UTM_SOURCE_".$siteID]."<br/>";

                            if(isset($_COOKIE["phoenix_UTM_MEDIUM_".$siteID]))
                                $messageForAdmin .= "utm_medium: ".$_COOKIE["phoenix_UTM_MEDIUM_".$siteID]."<br/>";

                            if(isset($_COOKIE["phoenix_UTM_CAMPAIGN_".$siteID]))
                                $messageForAdmin .= "utm_campaign: ".$_COOKIE["phoenix_UTM_CAMPAIGN_".$siteID]."<br/>";

                            if(isset($_COOKIE["phoenix_UTM_CONTENT_".$siteID]))
                                $messageForAdmin .= "utm_content: ".$_COOKIE["phoenix_UTM_CONTENT_".$siteID]."<br/>";

                            if(isset($_COOKIE["phoenix_UTM_TERM_".$siteID]))
                                $messageForAdmin .= "utm_term: ".$_COOKIE["phoenix_UTM_TERM_".$siteID]."<br/>";


                            $replyTo = $email_from;

                            $arMailtoUser = array();
                            
                            if(strlen($email))
                            {
                                $arMailtoUser = explode(",", $email);
                                $replyTo = $arMailtoUser[0];
                            }
                            

                            if( !empty($arMailtoUser) && (strlen($themeForUser) || strlen($messageForUser)) )
                            {
                                $arEventFieldsUser = array(
                                    "EMAIL_FROM" => $email_from,
                                    "EMAIL" => $email_from,
                                    "THEME" => $themeForUser,
                                    "MESSAGE" => $messageForUser
                                );

                                

                                foreach ($arMailtoUser as $email_to_val)
                                {
                                   $arEventFieldsUser["EMAIL_TO"] = $email_to_val;
                              
                                    CEvent::Send("PHOENIX_ONECLICK_SEND_".$siteID, $siteID, $arEventFieldsUser, "Y", "");
                                }

                            }

                            if(  !empty($arMailtoAdmin) && ( strlen($themeForAdmin) || strlen($messageForAdmin) )  )
                            {

                                $arEventFieldsAdmin = array(
                                    "EMAIL_FROM" => $email_from,
                                    "EMAIL" => $replyTo,
                                    "THEME" => $themeForAdmin,
                                    "MESSAGE" => $messageForAdmin
                                );

                                foreach ($arMailtoAdmin as $email_to_val)
                                {
                                   $arEventFieldsAdmin["EMAIL_TO"] = $email_to_val;
                                
                                    CEvent::Send("PHOENIX_ONECLICK_SEND_".$siteID, $siteID, $arEventFieldsAdmin, "Y", "");
                                   
                                }
                            }

                            $basket_url = CPhoenix::getBasketUrl(SITE_DIR, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]);


                            $arRes["CUR_PAGE"] = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_PAGES"]["VALUE"]=="one")? $domen.$basket_url."?ORDER_ID=".$arOrder["ACCOUNT_NUMBER"] : $domen.$basket_url."order/?ORDER_ID=".$arOrder["ACCOUNT_NUMBER"];
                            

                            if($orderId && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["ITEMS"]["STORE_QUANTITY_ON"]["VALUE"]["ACTIVE"] == "Y")
                            {
                                global $CACHE_MANAGER;

                                $CACHE_MANAGER->ClearByTag("iblock_id_".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"]);
                                $CACHE_MANAGER->ClearByTag("iblock_id_".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]);

                            }
                        }
                    }
                }




                if($arRes["OK"] == "Y")
                {

                    CPhoenix::getAnalitycCodes($siteID);


                    if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_METRIKA"]) && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA_GOAL_FAST_ORDER"]['VALUE']) > 0)
                    {
                        foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_METRIKA"] as $idVal)
                        {
                            $arRes["SCRIPTS"] .= 'yaCounter'.htmlspecialcharsbx(trim($idVal)).'.reachGoal("'.htmlspecialcharsbx(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA_GOAL_FAST_ORDER"]['VALUE'])).'"); ';
                        }
                    }

                    if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_METRIKA_YM"]) && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA_GOAL_FAST_ORDER"]['VALUE']) > 0)
                    {

                        foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_METRIKA_YM"] as $idVal)
                        {
                            $arRes["SCRIPTS"] .= 'ym('.htmlspecialcharsbx(trim($idVal)).', "reachGoal", "'.htmlspecialcharsbx(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA_GOAL_FAST_ORDER"]['VALUE'])).'");';
                        }
                    }
                    
                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_GOOGLE"]) > 0 && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_CATEGORY_FAST_ORDER"]['VALUE']) > 0 && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_ACTION_FAST_ORDER"]['VALUE']) > 0)
                    {
                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_FLAG_GA'])
                            $arRes["SCRIPTS"] .= 'ga("send", "event", "'.htmlspecialcharsbx(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_CATEGORY_FAST_ORDER"]['VALUE'])).'", "'.htmlspecialcharsbx(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_ACTION_FAST_ORDER"]['VALUE'])).'"); ';

                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_FLAG_GTAG'])
                            $arRes["SCRIPTS"] .= 'gtag("event","'.htmlspecialcharsbx(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_ACTION_FAST_ORDER"]['VALUE'])).'",{"event_category":"'.htmlspecialcharsbx(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_CATEGORY_FAST_ORDER"]['VALUE'])).'"}); ';
                    }

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_GTM"]) > 0 && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_EVENT_FAST_ORDER"]['VALUE']) > 0)
                    {
                        $arRes["SCRIPTS"] .= 'dataLayer.push({"event": "'.htmlspecialcharsbx(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_EVENT_FAST_ORDER"]['VALUE'])).'", "eventCategory": "'.htmlspecialcharsbx(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_CATEGORY_FAST_ORDER"]['VALUE'])).'", "eventAction": "'.htmlspecialcharsbx(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_ACTION_FAST_ORDER"]['VALUE'])).'"});';
                    }

                }
                
            }

            else
            {
                $element_id = intval(trim($_POST["element"]));



                if($element_id > 0)
                {

                    $arFilt = Array("ID"=> $element_id, "ACTIVE"=>"Y");
                    $r = CIBlockElement::GetList($arSort, $arFilt);

                    while($ob = $r->GetNextElement())
                    {
                        $arIBlockElement = $ob->GetFields();  
                        $arIBlockElement["PROPERTIES"] = $ob->GetProperties();
                    }


                    $email_to_form = array();

                    if(strlen($arIBlockElement["PROPERTIES"]["EMAIL_TO"]["VALUE"])>0)
                    {
                        $email_to_form = explode(",", $arIBlockElement["PROPERTIES"]["EMAIL_TO"]["VALUE"]);

                        if($arIBlockElement["PROPERTIES"]["DUBLICATE"]["VALUE"] == "Y")
                        {
                            if(!empty($email_to_form))
                                $arMailtoAdmin = array_merge($arMailtoAdmin, $email_to_form);
                        }
                        else
                            $arMailtoAdmin = $email_to_form;
                    }



                    $form_admin = $arIBlockElement["PROPERTIES"]["FORM_ADMIN"]["VALUE_XML_ID"];
                
                    if(strlen($form_admin)<=0)
                        $form_admin = "light";


                    /*trff*/

                        if( isset($_POST["element_item_type"]) )
                        {

                            $element_item_type = trim($_POST["element_item_type"]);

                            if( isset($_POST["element_item_id"]) )
                                $element_item_id = trim($_POST["element_item_id"]);

                            if( isset($_POST["element_item_name"]) )
                            {
                                $element_item_name = trim($_POST["element_item_name"]);

                                if(trim(SITE_CHARSET) == "windows-1251")
                                    $element_item_name = utf8win1251($element_item_name);
                            }

                            $comment = "";

                            if($element_item_type == "TRF")
                            {
                                $comment = 
                                $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_NAME_".$element_item_type]
                                .$element_item_name
                                ."<br/>";

                                if( isset($_POST["element_item_price"]) )
                                {
                                    $cur_price = trim($_POST["element_item_price"]);

                                    if(trim(SITE_CHARSET) == "windows-1251")
                                        $cur_price = utf8win1251($cur_price);

                                    if(strlen($cur_price)<=0)
                                        $cur_price = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_PRICE_NULL"];

                                    $comment .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_PRICE_NAME"].$cur_price;
                                }
                                
                            }

                            else
                            {
                                $header = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_NAME_".$element_item_type."_TITLE"];
                                $comment = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_NAME_".$element_item_type]."<a href='".$url."' target='_blank'>".$element_item_name."</a>";
                            }

                            $comment .= "<br/><br/>";
                        }


                    /*^catalog - trff*/

                    $message = '';
                    $arFiles = array();

                    $resTestReq = true;

                    if($form_admin == 'light')
                    {
                        $name = trim($_POST["inp-name"]);
                        $phone = trim($_POST["inp-phone"]);
                        $email = trim($_POST["inp-email"]);
                        $date = trim($_POST["date"]);
                        $count = trim($_POST["count"]);
                        $checkbox = $_POST["checkbox".$element_id];
                        
                        $text = trim($_POST["text"]);
                        $address = trim($_POST["address"]);
                        $radiobutton = trim($_POST["radiobutton".$element_id]);
                        
                        
                        $check_value = '';
                        

                        if(trim(SITE_CHARSET) == "windows-1251")
                        {
                            $name = utf8win1251(trim($_POST["inp-name"]));
                            $text = utf8win1251(trim($_POST["text"]));
                            $address = utf8win1251(trim($_POST["address"]));
                            $radiobutton = utf8win1251(trim($_POST["radiobutton".$element_id]));


                            if(!empty($checkbox))
                            {
                                foreach($checkbox as $k => $value)
                                {
                                    $checkbox[$k] = utf8win1251(trim($value));
                                }
                            }
                                            
                        }
                        else
                        {
                            $name = CPhoenixSendMail::wp_encode_emoji($name);
                            $text = CPhoenixSendMail::wp_encode_emoji($text);
                            $address = CPhoenixSendMail::wp_encode_emoji($address);
                        }


                        if(in_array("name", $arIBlockElement["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"]) && strlen($name)<=0)
                              $resTestReq = false;

                        if(in_array("phone", $arIBlockElement["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"]) && strlen($phone)<=0)
                              $resTestReq = false;

                        if(in_array("email", $arIBlockElement["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"]) && strlen($email)<=0)
                              $resTestReq = false;

                        if(in_array("date", $arIBlockElement["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"]) && strlen($date)<=0)
                              $resTestReq = false;

                        if(in_array("count", $arIBlockElement["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"]) && strlen($count)<=0)
                              $resTestReq = false;

                        if(in_array("address", $arIBlockElement["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"]) && strlen($address)<=0)
                              $resTestReq = false;

                        if(in_array("textarea", $arIBlockElement["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"]) && strlen($text)<=0)
                              $resTestReq = false;

                        if(in_array("file", $arIBlockElement["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"]) && empty($_FILES["userfile"]["name"]))
                              $resTestReq = false;



                        if(!empty($_FILES["userfile"]["name"]))
                          {
                              foreach ($_FILES["userfile"]["name"] as $key => $nameFile)
                              {

                                  if($_FILES["userfile"]["error"][$key] == 0)
                                  {

                                    if(trim(SITE_CHARSET) == "windows-1251")
                                        $filename = utf8win1251($nameFile);
                                    else
                                        $filename = $nameFile;

                                    $arParams = array("safe_chars"=>".", "max_len" => 1000);
                                    $filename = Cutil::translit($filename,"ru",$arParams);
                                    
                                    $filename = basename($filename);
                            
                                    $newname = $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/phoenix_tmp_file/'.$filename;
                                    if (!file_exists($newname)) 
                                    {
                                        move_uploaded_file($_FILES["userfile"]["tmp_name"][$key], $newname);
                                    }

                                    $arFiles[] = $newname;
                                      
                                  }
                              }
                        }



                        if(strlen($radiobutton) > 0)
                            $check_value = $radiobutton;
                        

                        if(is_array($checkbox) && !empty($checkbox))
                            $check_value = implode(', ' , $checkbox);
                        


                        if(strlen($comment) > 0)
                            $message .= $comment;



                        if(strlen($count) > 0)
                            $message .= "<b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_COUNT"]."</b>".$count."<br>";

                        if(strlen($name) > 0)
                            $message .= "<b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_NAME"]."</b>".$name."<br>";
                        

                        if(strlen($phone) > 0)
                            $message .= "<b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_PHONE"]."</b>".$phone."<br>";               

                        if(strlen($email) > 0)
                            $message .= "<b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_EMAIL"]."</b>".$email."<br>";           

                        if(strlen($date) > 0)
                            $message .= "<b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_DATE"]."</b>".$date."<br>";
                        

                        if(strlen($address) > 0)
                            $message .= "<b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_ADDRESS"]."</b>".$address."<br>";
                        

                        if(strlen($check_value) > 0)
                            $message .= "<b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_CHECK_VALUE"]."</b>".$check_value."<br>";

                        if(strlen($text) > 0)
                            $message .= "<br><b>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXAREA"]."</b>".$text."<br>";
                        
                    }

                    elseif($form_admin == 'professional')
                    {
                        $email = "";
                        $phone = "";
                        $name = "";
                        
                        $countName = 0;
                        $countPhone = 0;
                        $countEmail = 0;

                        if(strlen($comment) > 0)
                            $message .= $comment;


                        if(!empty($arIBlockElement["PROPERTIES"]["FORM_PROP_INPUTS"]["VALUE"]))
                        {

                            foreach($arIBlockElement["PROPERTIES"]["FORM_PROP_INPUTS"]["VALUE"] as $k => $arVal)
                            {

                                $type = $arIBlockElement["PROPERTIES"]["FORM_PROP_INPUTS"]["DESCRIPTION"][$k];
                                                                                        
                                $type = explode(";", ToLower($type));

                                if(!empty($type))
                                {
                                    foreach($type as $k1=>$val)
                                        $type[$k1] = trim($val);
                                }



                                if($type[0] == "radio" || $type[0] == "checkbox" || $type[0] == "select")
                                {

                                    if($type[1] == "y" && !isset($_POST["input_".$element_id."_$k"]) )
                                        $resTestReq = false;

                                    $list = explode(";", htmlspecialcharsBack($arVal));                                                    
                                    $first = $list[0];

                                    if(substr_count($first, "<") > 0 && substr_count($first, ">") > 0)
                                    {
                                        $tit = str_replace(array("<", ">"), array("", ""), $first);
                                        unset($list[0]);
                                        
                                        if(!empty($_POST["input_".$element_id."_$k"]) && is_array($_POST["input_".$element_id."_$k"]) || strlen(trim($_POST["input_".$element_id."_$k"])) > 0)
                                            $message .= '<b>'.$tit.': </b> ';
                                    }

                                    



                                    if(!empty($_POST["input_".$element_id."_$k"]) && is_array($_POST["input_".$element_id."_$k"]))
                                    {
                                        
                                        $check_array = $_POST["input_".$element_id."_$k"];
                                        
                                        if(trim(SITE_CHARSET) == "windows-1251")
                                        {
                                            foreach($check_array as $c=>$check)
                                                $check_array[$c] = utf8win1251(trim($check));
                                        }
                                        
                                        $message .= implode(", ", $check_array).'<br>';

                                    }

                                    else
                                    {

                                        if(strlen(trim($_POST["input_".$element_id."_$k"]))>0)
                                        {
                                            $check = trim($_POST["input_".$element_id."_$k"]);
                                            
                                            if(trim(SITE_CHARSET) == "windows-1251")
                                                $check = utf8win1251($check);

                                            $message .= $check.'<br>';
                                        }
                                        
                                    }

                                }

                                else if($type[0] != "file")
                                {

                                    if($type[1] == "y" && !isset($_POST["input_".$element_id."_$k"]) )
                                        $resTestReq = false;

                                    if(strlen(trim($_POST["input_".$element_id."_$k"]))>0)
                                    {
                                        $desc = trim($_POST["input_".$element_id."_$k"]);
                
                                        if(trim(SITE_CHARSET) == "windows-1251")
                                            $desc = utf8win1251(trim($_POST["input_".$element_id."_$k"]));
                                        else
                                        {
                                            $desc = CPhoenixSendMail::wp_encode_emoji(trim($_POST["input_".$element_id."_$k"]));
                                        }
                
                                        $message .= '<b>'.$arVal.': </b>'.$desc.'<br>';
                                        
                                        if($type[0] == "name")
                                        {
                                            if($countName <= 0)
                                                $name = $desc;
                 
                                            $countName++;
                                        }
                                        
                                        if($type[0] == "phone")
                                        {
                                            if($countPhone <= 0)
                                                $phone = $desc;
                 
                                            $countPhone++;
                                        }
                                        
                                        if($type[0] == "email")
                                        {
                                            if($countEmail <= 0)
                                                $email = $desc;
                 
                                            $countEmail++;
                                        }
                                    }

                                }

                                

                              if($type[0] == "file")
                              {

                                    if($type[1] == "y" && empty($_FILES["input_".$element_id."_$k"]["name"]) )
                                         $resTestReq = false;

                                      if(!empty($_FILES["input_".$element_id."_$k"]["name"]))
                                      {
                                          $arFile = array();

                                          foreach ($_FILES["input_".$element_id."_$k"]["name"] as $key => $nameFile)
                                          {
                                              if($_FILES["input_".$element_id."_$k"]["error"][$key] == 0)
                                              {

                                                  if(trim(SITE_CHARSET) == "windows-1251")
                                                      $filename = utf8win1251($nameFile);
                                                  else
                                                      $filename = $nameFile;

                                                  $arParams = array("safe_chars"=>".", "max_len" => 1000);
                                                  $filename = Cutil::translit($filename,"ru",$arParams);
                                                  
                                                  $filename = basename($filename);
                                          
                                                  $newname = $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/phoenix_tmp_file/'.$filename;
                                                  if (!file_exists($newname)) 
                                                  {
                                                      move_uploaded_file($_FILES["input_".$element_id."_$k"]["tmp_name"][$key], $newname);
                                                  }

                                                  $arFile[] = $newname;
                                              }
                                          }

                                          $arFiles = array_merge($arFiles, $arFile);
                                      }
                              }
                            }
                        }



                    }

                    if(!$resTestReq)
                    {
                        $arRes = json_encode($arRes);
                        echo $arRes;

                        return false;
                    }


                    $form_name = strip_tags($arIBlockElement["~NAME"]);

                    if(strlen($mesParams))
                        $message .= "<br/><br/>".$mesParams;


                    if(isset($_COOKIE["phoenix_UTM_SOURCE_".$siteID]) || isset($_COOKIE["phoenix_UTM_MEDIUM_".$siteID]) || isset($_COOKIE["phoenix_UTM_CAMPAIGN_".$siteID]) || isset($_COOKIE["phoenix_UTM_CONTENT_".$siteID]) || isset($_COOKIE["phoenix_UTM_TERM_".$siteID]))
                       $message .= "<br/><br/>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_UTM_TITLE"]."<br/>";


                    if(isset($_COOKIE["phoenix_UTM_SOURCE_".$siteID]))
                        $message .= "utm_source: ".$_COOKIE["phoenix_UTM_SOURCE_".$siteID]."<br/>";

                    if(isset($_COOKIE["phoenix_UTM_MEDIUM_".$siteID]))
                        $message .= "utm_medium: ".$_COOKIE["phoenix_UTM_MEDIUM_".$siteID]."<br/>";

                    if(isset($_COOKIE["phoenix_UTM_CAMPAIGN_".$siteID]))
                        $message .= "utm_campaign: ".$_COOKIE["phoenix_UTM_CAMPAIGN_".$siteID]."<br/>";

                    if(isset($_COOKIE["phoenix_UTM_CONTENT_".$siteID]))
                        $message .= "utm_content: ".$_COOKIE["phoenix_UTM_CONTENT_".$siteID]."<br/>";

                    if(isset($_COOKIE["phoenix_UTM_TERM_".$siteID]))
                        $message .= "utm_term: ".$_COOKIE["phoenix_UTM_TERM_".$siteID]."<br/>";


                    $arEventFields = array(
                        "MESSAGE" => $message,
                        "HEADER" => $header,
                        "FORM_NAME" => $form_name,
                        "EMAIL_FROM" => $email_from,
                        "EMAIL"         => (strlen($email)>0)?$email:$email_from,
                        "NAME"           => $name,
                        "PHONE"          => $phone,
                        "CHECK_VALUE"    => $check_value,
                        "COUNT"          => $count,
                        "DATE"          => $date,
                        "TEXT"          => $text,
                        "ADDRESS"          => $address,
                        "MORE_INFO" =>  $comment,
                        "URL" => $url,
                        "PAGE_NAME" => $arSite['NAME']
                    );


                    $toAdminFile = false;

                    if(!empty($arFiles))
                        $toAdminFile = true;


                    if(!empty($arMailtoAdmin))
                    {
                        foreach ($arMailtoAdmin as $email_to_val)
                        {
                            $arEventFields["EMAIL_TO"] = $email_to_val;
                            
                            if($toAdminFile)
                            {
                                if(CEvent::Send("PHOENIX_USER_INFO_".SITE_ID, SITE_ID, $arEventFields, "Y", "", $arFiles))
                                    $arRes["OK"] = "Y";
                            }

                            else
                            {
                                if(CEvent::Send("PHOENIX_USER_INFO_".SITE_ID, SITE_ID, $arEventFields, "Y", ""))
                                    $arRes["OK"] = "Y";
                            }
                        }
                    }
                    else
                        $arRes["OK"] = "Y";

                    $arFilesIb = Array();
                    if(!empty($arFiles))
                    {
                        foreach ($arFiles as $value) {
                            $arFilesIb[] = array("VALUE" => CFile::MakeFileArray($value));
                        }
                    }

                    if(!empty($arIBlockElement["PROPERTIES"]['FORM_TEXT']["~VALUE"]["TEXT"]) || !empty($arIBlockElement['PROPERTIES']['FORM_FILES']['VALUE']) || strlen($arIBlockElement["PROPERTIES"]['FORM_THEME']["VALUE"]) > 0)
                    {

                        $arMailtoUser = array();
                        $arMailtoUser = explode(",", $email);


                        $arEventFields2 = array(
                            "EMAIL_FROM" => $email_from,
                            "EMAIL" => $email_from,
                            "MESSAGE_FOR_USER" => $arIBlockElement["PROPERTIES"]['FORM_TEXT']["~VALUE"]["TEXT"],
                            "THEME" => $arIBlockElement["PROPERTIES"]['FORM_THEME']["~VALUE"]
                        );

                        $files = $arIBlockElement['PROPERTIES']['FORM_FILES']['VALUE'];

                        foreach ($arMailtoUser as $email_to_val)
                        {
                            $arEventFields2["EMAIL_TO"] = $email_to_val;

                            if(!empty($files))
                            {
                                if(CEvent::Send("PHOENIX_FOR_USER_".SITE_ID, SITE_ID, $arEventFields2, "Y", "", $files))
                                    $arRes["OK"] = "Y";
                            }
                            else
                            {
                                if(CEvent::Send("PHOENIX_FOR_USER_".SITE_ID, SITE_ID, $arEventFields2))
                                    $arRes["OK"] = "Y";
                            }

                        }
                        
                    }


                    $yaGoal = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA_GOAL"]['VALUE'];

                    $gogCat = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_CATEGORY"]['VALUE'];
                    $gogAct = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_ACTION"]['VALUE'];

                    $gtmEvn = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_EVENT"]['VALUE'];
                    $gtmCat = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_CATEGORY"]['VALUE'];
                    $gtmAct = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_ACTION"]['VALUE'];

                    
                    $yaGoalForm = $arIBlockElement['PROPERTIES']['YANDEX_GOAL']['VALUE'];
                    $gogCatForm = $arIBlockElement['PROPERTIES']['GOOGLE_GOAL_CATEGORY']['VALUE'];
                    $gogActForm = $arIBlockElement['PROPERTIES']['GOOGLE_GOAL_ACTION']['VALUE'];
                    $gtmEvnForm = $arIBlockElement['PROPERTIES']['GTM_EVENT']['VALUE'];
                    $gtmCatForm = $arIBlockElement['PROPERTIES']['GTM_GOAL_CATEGORY']['VALUE'];
                    $gtmActForm = $arIBlockElement['PROPERTIES']['GTM_GOAL_ACTION']['VALUE'];



                    CPhoenix::getAnalitycCodes($siteID);


                    if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_METRIKA"]))
                    {

                        foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_METRIKA"] as $idVal)
                        {
                            if(strlen($yaGoal)>0)
                                $arRes["SCRIPTS"] .= 'yaCounter'.htmlspecialcharsbx(trim($idVal)).'.reachGoal("'.htmlspecialcharsbx(trim($yaGoal)).'"); ';

                            if(strlen($yaGoalForm)>0)
                                $arRes["SCRIPTS"] .= 'yaCounter'.htmlspecialcharsbx(trim($idVal)).'.reachGoal("'.htmlspecialcharsbx(trim($yaGoalForm)).'"); ';
                        }
                    }

                    if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_METRIKA_YM"]))
                    {

                        foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_METRIKA_YM"] as $idVal)
                        {
                            if(strlen($yaGoal)>0)
                                $arRes["SCRIPTS"] .= 'ym('.htmlspecialcharsbx(trim($idVal)).', "reachGoal", "'.htmlspecialcharsbx(trim($yaGoal)).'");';
                            
                            if(strlen($yaGoalForm)>0)
                                $arRes["SCRIPTS"] .= 'ym('.htmlspecialcharsbx(trim($idVal)).', "reachGoal", "'.htmlspecialcharsbx(trim($yaGoalForm)).'");';

                        }
                    }
                    
                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_GOOGLE"]) > 0)
                    {

                        if(strlen($gogCat)>0 && strlen($gogAct)>0)
                        {
                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_FLAG_GA'])
                                $arRes["SCRIPTS"] .= 'ga("send", "event", "'.htmlspecialcharsbx(trim($gogCat)).'", "'.htmlspecialcharsbx(trim($gogAct)).'"); ';

                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_FLAG_GTAG'])
                                $arRes["SCRIPTS"] .= 'gtag("event","'.htmlspecialcharsbx(trim($gogAct)).'",{"event_category":"'.htmlspecialcharsbx(trim($gogCat)).'"}); ';
                        }

                        if(strlen($gogCatForm)>0 && strlen($gogActForm)>0)
                        {
                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_FLAG_GA'])
                                $arRes["SCRIPTS"] .= 'ga("send", "event", "'.htmlspecialcharsbx(trim($gogCatForm)).'", "'.htmlspecialcharsbx(trim($gogActForm)).'"); ';

                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_FLAG_GTAG'])
                                $arRes["SCRIPTS"] .= 'gtag("event","'.htmlspecialcharsbx(trim($gogActForm)).'",{"event_category":"'.htmlspecialcharsbx(trim($gogCatForm)).'"}); ';
                        }
                    }

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["ID_GTM"]) > 0)
                    {

                        if( strlen($gtmEvn)>0)
                            $arRes["SCRIPTS"] .= 'dataLayer.push({"event": "'.htmlspecialcharsbx(trim($gtmEvn)).'", "eventCategory": "'.htmlspecialcharsbx(trim($gtmCat)).'", "eventAction": "'.htmlspecialcharsbx(trim($gtmAct)).'"});';

                        if( strlen($gtmEvnForm)>0)
                            $arRes["SCRIPTS"] .= 'dataLayer.push({"event": "'.htmlspecialcharsbx(trim($gtmEvnForm)).'", "eventCategory": "'.htmlspecialcharsbx(trim($gtmCatForm)).'", "eventAction": "'.htmlspecialcharsbx(trim($gtmActForm)).'"});';
                    }

                    if(strlen($arIBlockElement['PROPERTIES']['FORM_JS_AFTER_SEND']['VALUE']) > 0)
                        $arRes["SCRIPTS"] .= $arIBlockElement['PROPERTIES']['FORM_JS_AFTER_SEND']['VALUE'];


                    //infoblock
                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["SAVE_IN_IB"]['VALUE']["ACTIVE"] == 'Y')
                    {
                        
                        $request_iblock_code = "concept_phoenix_requests_".SITE_ID;
                        
                        $res = CIBlock::GetList(Array(), Array("CODE"=>$request_iblock_code), false);
                        
                        while($ar_res = $res->GetNext())
                            $request_iblock_id = $ar_res["ID"];
                        
                        
                        $el = new CIBlockElement;

                        

                        
                        $request_message = str_replace(Array("<b>","</b>","<br>", "<br/>"), Array("", "", "\r\n", "\r\n"), $message);



                        
                        $request_text = "";
                        
                        //$request_text .= GetMessage("MESSAGE_TEXT1")."\r\n";
                        $request_text .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT2"].$arSite['NAME']."\r\n";
                        $request_text .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT3"].$header."\r\n";
                        $request_text .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT6"].$form_name."\r\n\r\n";
                        $request_text .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT4"].$url."\r\n\r\n";
                        $request_text .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT5"]."\r\n\r\n";
                        $request_text .= $request_message;


                        $arLoadProductArray = Array(         
                          "IBLOCK_ID"      => $request_iblock_id,
                          "NAME"           => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_INFOBLOCK_TITLE"].date("d.m.Y H:i:s"),
                          "ACTIVE"         => "Y",            
                          "PREVIEW_TEXT"   => $request_text
                        );
                        
                        $id_el = $el->Add($arLoadProductArray);

                        if(!empty($arFilesIb))
                            CIBlockElement::SetPropertyValueCode($id_el, "REQ_FILES", $arFilesIb);
                        

                    }

                    if (file_exists($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/phoenix_tmp_file/'))
                        foreach (glob($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/phoenix_tmp_file/*') as $file)
                            unlink($file);
                  
                }

            }

            require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/concept.phoenix/crm.php');

            $arRes = json_encode($arRes);
            echo $arRes;

            
            require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/concept.phoenix/classes/crm/bx24.php');
            
            require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/concept.phoenix/classes/crm/amocrm_old.php');

      }
      else
      {
            $arRes = json_encode($arRes);
            echo $arRes;
      }


    }

    public static function sendNewComments($arParams = array()){

        global $PHOENIX_TEMPLATE_ARRAY;


        CPhoenix::phoenixOptionsValues($arParams["SITE_ID"], array('comments', 'lids', 'other','region'));


        $go = true;

        if(strlen($_POST["name"])>0 || strlen($_POST["phone"])>0 || strlen($_POST["email"])>0 || $_POST["send"] != "Y")
            $go = false;

        $resValid = CPhoenixSendMail::checkValid($arParams["SITE_ID"], array("GO"=>$go));
        $go = $resValid["GO"];
        $arRes["CAPTCHA_SCORE"] = $resValid["CAPTCHA_SCORE"];
 
        if($go)
        {
        
            $email_to = "";
            
            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]["EMAIL_TO"]["VALUE"])>0)
                $email_to = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]["EMAIL_TO"]["VALUE"];

            else if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_TO"]["VALUE"])>0)
                $email_to = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_TO"]["VALUE"];


            $email_from = "";

            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_FROM"]["VALUE"])>0)
                $email_from = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_FROM"]["VALUE"];

            if(strlen($email_to)>0)
                $arMailtoAdmin = explode(",", $email_to);
            

            if( !empty($arMailtoAdmin) )
            {

                $elementName = "";
                $elementUrl = "";

                if($arParams["ELEMENT_ID"] && intval($arParams["IBLOCK_ID"])>0)
                {

                    $arFilter = Array("IBLOCK_ID"=> $arParams["IBLOCK_ID"], "ID" => $arParams["ELEMENT_ID"]);
                    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, array("ID", "NAME", "DETAIL_PAGE_URL"));

                    if($arElement = $res->GetNext())
                    {
                        
                        $tmpSubstr = substr($arElement["IBLOCK_CODE"], -3);
                        $typeElement = str_replace(array("concept_phoenix_",$tmpSubstr), "", $arElement["IBLOCK_CODE"]);
                        
                        $rsSites = CSite::GetByID($arParams["SITE_ID"]);
                        $arSite = $rsSites->Fetch();

                        $domen = CPhoenixHost::getHost($_SERVER);

                        $elementUrl = $arElement["DETAIL_PAGE_URL"];
                        $elementName = strip_tags($arElement["~NAME"]);

                       
                        /*if(isset($arParams["FROM_JS"]) && $arParams["FROM_JS"] == "Y")
                        {
                            if(trim(SITE_CHARSET) == "windows-1251")
                            {
                                $domen = utf8win1251($domen);
                                $elementName = utf8win1251($elementName);
                            }
                        }*/


                        $elementEditUrl = $domen."/bitrix/admin/concept_phoenix_admin_comments_edit.php?ID=".$arParams["COMMENT_ID"]."&site_id=".$arParams["SITE_ID"];


                        $result = CPhoenixComments\CPhoenixCommentsTable::getList(array(
                            'filter' => array('=ID' => $arParams["COMMENT_ID"])
                        ));

                        $commentContent = "";

                        if($arResult = $result->fetch())
                        {
                            CPhoenix::setMess(array("comments"));

                            if(strlen($arResult["DATE"])>0)
                            {
                                $arResult["DATE_STR"] = $arResult["DATE"]->toString();
                                $format = "DD.MM.YYYY HH:MI:SS";
                                $arResult["DATE_FORMAT"] = CIBlockFormatProperties::DateFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]["VALUE"], MakeTimeStamp($arItem["DATE_STR"], $format));

                                $commentContent .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["DATE_TITLE"].$arResult["DATE_FORMAT"]."<br/>";
                            }

                            if(strlen($arResult["USER_NAME"])>0)
                            {
                                $commentContent .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["USER_NAME_TITLE"].$arResult["USER_NAME"]."<br/>";
                            }
                            if(strlen($arResult["USER_EMAIL"])>0)
                            {
                                $commentContent .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["USER_EMAIL_TITLE"].$arResult["USER_EMAIL"]."<br/>";
                            }
                            if(strlen($arResult["TEXT"])>0)
                            {
                                $commentContent .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["TEXT_TITLE"].$arResult["TEXT"]."<br/>";
                            }

                            if(strlen($arResult["TEXT"])>0)
                            {
                                $commentContent .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["DETAIL_PAGE_URL_TITLE"]."<a href=\"".$elementUrl."\">".$elementName."</a>"."<br/>";
                            }

                        }



                    }
                    
                }


                $arEventFieldsAdmin = array(
                    "EMAIL_FROM" => $email_from,
                    "COMMENT_URL" => $elementEditUrl,
                    "ELEMENT_NAME" => $elementName,
                    "ELEMENT_URL" => $elementUrl,
                    "EMAIL" => $email_from,
                    "SITE_URL" => $domen.$arSite["DIR"],
                    "COMMENT_CONTENT"=>$commentContent,
                    "TYPE_ELEMENT"=>$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["TYPE_ELEMENT_".$typeElement]
                );


                foreach ($arMailtoAdmin as $email_to_val)
                {
                   $arEventFieldsAdmin["EMAIL_TO"] = $email_to_val;
                
                    CEvent::Send("PHOENIX_NEW_COMMENTS_".$arParams["SITE_ID"], $arParams["SITE_ID"], $arEventFieldsAdmin, "Y", "");
                   
                }
            }

        }
    

    }

    public static function OnSalePaymentEntitySaved(\Bitrix\Main\Event $obEvent)
    {

        $obPayment = $obEvent->getParameter("ENTITY");
        $arPayment = $obPayment->getFields()->getValues();

        if($arPayment['PAID'] === 'Y')
        {

            if (Loader::IncludeModule('concept.phoenix') && Loader::IncludeModule('sale') && Loader::IncludeModule('iblock') && Loader::IncludeModule('currency') && Loader::IncludeModule('catalog'))
            {
                $arOrder = CSaleOrder::GetByID($arPayment['ORDER_ID']);

                $rsTemplates = CSite::GetTemplateList($arOrder["LID"]);

                $res = false;

                while($arTemplate = $rsTemplates->Fetch())
                { 
                    if(substr_count($arTemplate["TEMPLATE"], "concept_phoenix_".$arOrder["LID"]) > 0)
                        $res = true;
                }

                if($res)
                {

                    $order = Sale\Order::load($arPayment['ORDER_ID']);
                    $email = $order->getPropertyCollection()->getUserEmail();
                    
                    if(!empty($email))
                    {

                        $email = $email->getValue();
                        $arMailto = Array();
                        $arMailto = explode(",", $email);

                        if( !empty($arMailto) )
                        {


                            $rsBasket = CSaleBasket::GetList(array(), array('ORDER_ID' => $arPayment['ORDER_ID']));

                            $arProductsId = array();

                            while ($ar_res = $rsBasket->GetNext())
                            {
                                $arProductsId[] = $ar_res['PRODUCT_ID'];
                            }

                            if(!empty($arProductsId))
                            {


                                global $PHOENIX_TEMPLATE_ARRAY;

                                CPhoenix::phoenixOptionsValues($arOrder["LID"], array(
                                    "lids",
                                    "region"
                                ));



                                $arProductsIdOffersMain = array();
                                $arFilter = Array("ID" => $arProductsId);
                                $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false);

                                $arProducts = array();

                                while($ob = $res->GetNextElement())
                                {
                                    $arCurProduct = $ob->GetFields();
                                    $arCurProduct["PROPERTIES"] = $ob->getProperties();


                                    if($arCurProduct["PROPERTIES"]["CML2_LINK"]["VALUE"] > 0)
                                        $arProductsIdOffersMain[]=$arCurProduct["PROPERTIES"]["CML2_LINK"]["VALUE"];
                                    else
                                    {
                                        $arProducts[]=$arCurProduct;
                                    }
                                }


                                if(!empty($arProductsIdOffersMain))
                                {
                                    $arFilter = Array("ID" => $arProductsIdOffersMain);
                                    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false);

                                    while($ob = $res->GetNextElement())
                                    {
                                        $arCurProduct = $ob->GetFields();
                                        $arCurProduct["PROPERTIES"] = $ob->getProperties();

                                        $arProducts[]=$arCurProduct;
                                    }
                                }

                                if(!empty($arProducts))
                                {
                                    foreach ($arProducts as $key => $arCurProduct)
                                    {
                                        $message = "";
                                        $arFiles = array();

                                        if((is_array($arCurProduct["PROPERTIES"]["SEND_AFTER_PAY_TEXT"]["VALUE"]) || !empty($arCurProduct["PROPERTIES"]["SEND_AFTER_PAY_FILES"]["VALUE"])))
                                        {

                                            $message .= "<div><b>".strip_tags(html_entity_decode($arCurProduct["~NAME"]))."</b></div><br/>";

                                            if( is_array($arCurProduct["PROPERTIES"]["SEND_AFTER_PAY_TEXT"]["VALUE"]) )
                                            {
                                                $message .= "<div>".$arCurProduct["PROPERTIES"]["SEND_AFTER_PAY_TEXT"]["~VALUE"]["TEXT"]."</div><br/>";
                                            }

                                            if( !empty($arCurProduct["PROPERTIES"]["SEND_AFTER_PAY_FILES"]["VALUE"]) )
                                                $arFiles = $arCurProduct["PROPERTIES"]["SEND_AFTER_PAY_FILES"]["VALUE"];
                                            

                                            $theme = GetMessage("KRAKEN_SEND_PAYED_THEME");

                                            if(strlen($arCurProduct["PROPERTIES"]["SEND_AFTER_PAY_THEME"]["VALUE"]))
                                                $theme = $arCurProduct["PROPERTIES"]["SEND_AFTER_PAY_THEME"]["VALUE"];


                                            $arEventFields = array(
                                                "EMAIL_FROM" => $PHOENIX_TEMPLATE_ARRAY['ITEMS']['LIDS']['ITEMS']['MAIL_FROM']['VALUE'],
                                                "MESSAGE" => $message,
                                                "THEME" => $theme
                                            );


                                            foreach ($arMailto as $key => $value)
                                            {

                                                $arEventFields["EMAIL_TO"] = $value;

                                                if(!empty($arCurProduct["PROPERTIES"]["SEND_AFTER_PAY_FILES"]["VALUE"]))
                                                {
                                                    CEvent::Send("PHOENIX_INFO_ORDER_ITEMS_FOR_USER_AFTER_PAYED_".$arOrder["LID"], $arOrder["LID"], $arEventFields, "Y", "", $arFiles);
                                                }
                                                else
                                                {
                                                    CEvent::Send("PHOENIX_INFO_ORDER_ITEMS_FOR_USER_AFTER_PAYED_".$arOrder["LID"], $arOrder["LID"], $arEventFields, "Y", "");
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

    function _wp_emoji_list( $type = 'entities' ) 
    {
        $entities = array( '&#x1f468;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;', '&#x1f469;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;', '&#x1f469;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;', '&#x1f3f4;&#xe0067;&#xe0062;&#xe0065;&#xe006e;&#xe0067;&#xe007f;', '&#x1f3f4;&#xe0067;&#xe0062;&#xe0073;&#xe0063;&#xe0074;&#xe007f;', '&#x1f3f4;&#xe0067;&#xe0062;&#xe0077;&#xe006c;&#xe0073;&#xe007f;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f468;&#x200d;&#x1f468;&#x200d;&#x1f466;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f468;&#x200d;&#x1f467;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f468;&#x200d;&#x1f467;&#x200d;&#x1f467;', '&#x1f468;&#x200d;&#x1f469;&#x200d;&#x1f466;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f469;&#x200d;&#x1f467;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f469;&#x200d;&#x1f467;&#x200d;&#x1f467;', '&#x1f469;&#x200d;&#x1f469;&#x200d;&#x1f466;&#x200d;&#x1f466;', '&#x1f469;&#x200d;&#x1f469;&#x200d;&#x1f467;&#x200d;&#x1f466;', '&#x1f469;&#x200d;&#x1f469;&#x200d;&#x1f467;&#x200d;&#x1f467;', '&#x1f468;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;', '&#x1f469;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;', '&#x1f469;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;', '&#x1f468;&#x200d;&#x1f466;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f467;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f467;&#x200d;&#x1f467;', '&#x1f468;&#x200d;&#x1f468;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f468;&#x200d;&#x1f467;', '&#x1f468;&#x200d;&#x1f469;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f469;&#x200d;&#x1f467;', '&#x1f469;&#x200d;&#x1f466;&#x200d;&#x1f466;', '&#x1f469;&#x200d;&#x1f467;&#x200d;&#x1f466;', '&#x1f469;&#x200d;&#x1f467;&#x200d;&#x1f467;', '&#x1f469;&#x200d;&#x1f469;&#x200d;&#x1f466;', '&#x1f469;&#x200d;&#x1f469;&#x200d;&#x1f467;', '&#x1f9d1;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;', '&#x1f3c3;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c3;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c3;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c3;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c3;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c3;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c3;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c3;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c3;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c3;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c4;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c4;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c4;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c4;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c4;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c4;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c4;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c4;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c4;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c4;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f3ca;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f3ca;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f3ca;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f3ca;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f3ca;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f3ca;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f3ca;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f3ca;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f3ca;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f3ca;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cb;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cb;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cb;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cb;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cb;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cb;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cb;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cb;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cb;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cb;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cc;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cc;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cc;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cc;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cc;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cc;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cc;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cc;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cc;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cc;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f468;&#x1f3fb;&#x200d;&#x2695;&#xfe0f;', '&#x1f468;&#x1f3fb;&#x200d;&#x2696;&#xfe0f;', '&#x1f468;&#x1f3fb;&#x200d;&#x2708;&#xfe0f;', '&#x1f468;&#x1f3fc;&#x200d;&#x2695;&#xfe0f;', '&#x1f468;&#x1f3fc;&#x200d;&#x2696;&#xfe0f;', '&#x1f468;&#x1f3fc;&#x200d;&#x2708;&#xfe0f;', '&#x1f468;&#x1f3fd;&#x200d;&#x2695;&#xfe0f;', '&#x1f468;&#x1f3fd;&#x200d;&#x2696;&#xfe0f;', '&#x1f468;&#x1f3fd;&#x200d;&#x2708;&#xfe0f;', '&#x1f468;&#x1f3fe;&#x200d;&#x2695;&#xfe0f;', '&#x1f468;&#x1f3fe;&#x200d;&#x2696;&#xfe0f;', '&#x1f468;&#x1f3fe;&#x200d;&#x2708;&#xfe0f;', '&#x1f468;&#x1f3ff;&#x200d;&#x2695;&#xfe0f;', '&#x1f468;&#x1f3ff;&#x200d;&#x2696;&#xfe0f;', '&#x1f468;&#x1f3ff;&#x200d;&#x2708;&#xfe0f;', '&#x1f469;&#x1f3fb;&#x200d;&#x2695;&#xfe0f;', '&#x1f469;&#x1f3fb;&#x200d;&#x2696;&#xfe0f;', '&#x1f469;&#x1f3fb;&#x200d;&#x2708;&#xfe0f;', '&#x1f469;&#x1f3fc;&#x200d;&#x2695;&#xfe0f;', '&#x1f469;&#x1f3fc;&#x200d;&#x2696;&#xfe0f;', '&#x1f469;&#x1f3fc;&#x200d;&#x2708;&#xfe0f;', '&#x1f469;&#x1f3fd;&#x200d;&#x2695;&#xfe0f;', '&#x1f469;&#x1f3fd;&#x200d;&#x2696;&#xfe0f;', '&#x1f469;&#x1f3fd;&#x200d;&#x2708;&#xfe0f;', '&#x1f469;&#x1f3fe;&#x200d;&#x2695;&#xfe0f;', '&#x1f469;&#x1f3fe;&#x200d;&#x2696;&#xfe0f;', '&#x1f469;&#x1f3fe;&#x200d;&#x2708;&#xfe0f;', '&#x1f469;&#x1f3ff;&#x200d;&#x2695;&#xfe0f;', '&#x1f469;&#x1f3ff;&#x200d;&#x2696;&#xfe0f;', '&#x1f469;&#x1f3ff;&#x200d;&#x2708;&#xfe0f;', '&#x1f46e;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f46e;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f46e;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f46e;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f46e;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f46e;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f46e;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f46e;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f46e;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f46e;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f470;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f470;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f470;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f470;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f470;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f470;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f470;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f470;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f470;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f470;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f471;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f471;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f471;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f471;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f471;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f471;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f471;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f471;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f471;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f471;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f473;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f473;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f473;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f473;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f473;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f473;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f473;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f473;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f473;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f473;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f477;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f477;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f477;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f477;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f477;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f477;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f477;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f477;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f477;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f477;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f481;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f481;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f481;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f481;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f481;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f481;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f481;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f481;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f481;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f481;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f482;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f482;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f482;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f482;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f482;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f482;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f482;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f482;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f482;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f482;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f486;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f486;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f486;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f486;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f486;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f486;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f486;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f486;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f486;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f486;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f487;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f487;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f487;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f487;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f487;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f487;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f487;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f487;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f487;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f487;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f574;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f574;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f574;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f574;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f574;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f574;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f574;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f574;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f574;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f574;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f575;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f575;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f575;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f575;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f575;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f575;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f575;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f575;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f575;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f575;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f645;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f645;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f645;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f645;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f645;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f645;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f645;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f645;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f645;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f645;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f646;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f646;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f646;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f646;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f646;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f646;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f646;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f646;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f646;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f646;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f647;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f647;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f647;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f647;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f647;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f647;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f647;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f647;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f647;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f647;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f64b;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f64b;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f64b;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f64b;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f64b;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f64b;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f64b;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f64b;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f64b;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f64b;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f64d;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f64d;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f64d;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f64d;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f64d;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f64d;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f64d;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f64d;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f64d;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f64d;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f64e;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f64e;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f64e;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f64e;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f64e;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f64e;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f64e;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f64e;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f64e;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f64e;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f6a3;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f6a3;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f6a3;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f6a3;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f6a3;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f6a3;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f6a3;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f6a3;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f6a3;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f6a3;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b4;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b4;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b4;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b4;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b4;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b4;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b4;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b4;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b4;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b4;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b5;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b5;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b5;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b5;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b5;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b5;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b5;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b5;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b5;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b5;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b6;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b6;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b6;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b6;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b6;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f926;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f926;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f926;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f926;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f926;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f926;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f926;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f926;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f926;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f926;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f935;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f935;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f935;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f935;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f935;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f935;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f935;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f935;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f935;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f935;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f937;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f937;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f937;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f937;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f937;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f937;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f937;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f937;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f937;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f937;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f938;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f938;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f938;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f938;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f938;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f938;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f938;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f938;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f938;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f938;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f939;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f939;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f939;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f939;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f939;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f939;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f939;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f939;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f939;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f939;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f93d;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f93d;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f93d;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f93d;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f93d;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f93d;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f93d;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f93d;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f93d;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f93d;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f93e;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f93e;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f93e;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f93e;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f93e;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f93e;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f93e;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f93e;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f93e;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f93e;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b8;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b8;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b8;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b8;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b8;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b8;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b8;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b8;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b8;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b8;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b9;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b9;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b9;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b9;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b9;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b9;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b9;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b9;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b9;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b9;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cd;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cd;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cd;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cd;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cd;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cd;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cd;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cd;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cd;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cd;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9ce;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9ce;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9ce;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9ce;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9ce;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cf;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cf;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cf;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cf;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cf;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cf;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cf;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cf;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cf;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cf;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2695;&#xfe0f;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2696;&#xfe0f;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2708;&#xfe0f;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2695;&#xfe0f;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2696;&#xfe0f;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2708;&#xfe0f;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2695;&#xfe0f;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2696;&#xfe0f;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2708;&#xfe0f;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2695;&#xfe0f;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2696;&#xfe0f;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2708;&#xfe0f;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2695;&#xfe0f;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2696;&#xfe0f;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2708;&#xfe0f;', '&#x1f9d6;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d6;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d6;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d6;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d6;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d6;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d6;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d6;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d6;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d6;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d7;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d7;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d7;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d7;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d7;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d7;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d7;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d7;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d7;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d7;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d8;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d8;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d8;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d8;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d8;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d8;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d8;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d8;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d8;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d8;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d9;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d9;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d9;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d9;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d9;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d9;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d9;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d9;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d9;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d9;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9da;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9da;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9da;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9da;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9da;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9da;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9da;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9da;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9da;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9da;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9db;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9db;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9db;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9db;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9db;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9db;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9db;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9db;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9db;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9db;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dc;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dc;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dc;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dc;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dc;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dc;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dc;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dc;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dc;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dc;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dd;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dd;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dd;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dd;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dd;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dd;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dd;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dd;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dd;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dd;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cb;&#xfe0f;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cb;&#xfe0f;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cc;&#xfe0f;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cc;&#xfe0f;&#x200d;&#x2642;&#xfe0f;', '&#x1f3f3;&#xfe0f;&#x200d;&#x26a7;&#xfe0f;', '&#x1f574;&#xfe0f;&#x200d;&#x2640;&#xfe0f;', '&#x1f574;&#xfe0f;&#x200d;&#x2642;&#xfe0f;', '&#x1f575;&#xfe0f;&#x200d;&#x2640;&#xfe0f;', '&#x1f575;&#xfe0f;&#x200d;&#x2642;&#xfe0f;', '&#x26f9;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x26f9;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x26f9;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x26f9;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x26f9;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x26f9;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x26f9;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x26f9;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x26f9;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x26f9;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x26f9;&#xfe0f;&#x200d;&#x2640;&#xfe0f;', '&#x26f9;&#xfe0f;&#x200d;&#x2642;&#xfe0f;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f33e;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f373;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f37c;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f384;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f393;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f3a4;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f3a8;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f3eb;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f3ed;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f4bb;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f4bc;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f527;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f52c;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f680;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f692;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9af;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9b0;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9b1;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9b2;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9b3;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9bc;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9bd;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f33e;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f373;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f37c;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f384;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f393;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f3a4;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f3a8;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f3eb;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f3ed;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f4bb;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f4bc;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f527;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f52c;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f680;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f692;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9af;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9b0;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9b1;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9b2;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9b3;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9bc;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9bd;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f33e;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f373;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f37c;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f384;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f393;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f3a4;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f3a8;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f3eb;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f3ed;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f4bb;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f4bc;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f527;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f52c;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f680;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f692;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9af;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9b0;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9b1;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9b2;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9b3;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9bc;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9bd;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f33e;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f373;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f37c;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f384;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f393;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f3a4;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f3a8;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f3eb;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f3ed;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f4bb;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f4bc;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f527;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f52c;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f680;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f692;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9af;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9b0;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9b1;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9b2;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9b3;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9bc;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9bd;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f33e;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f373;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f37c;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f384;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f393;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f3a4;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f3a8;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f3eb;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f3ed;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f4bb;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f4bc;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f527;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f52c;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f680;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f692;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9af;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9b0;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9b1;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9b2;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9b3;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9bc;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9bd;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f33e;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f373;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f37c;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f384;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f393;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f3a4;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f3a8;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f3eb;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f3ed;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f4bb;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f4bc;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f527;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f52c;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f680;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f692;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9af;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9b0;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9b1;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9b2;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9b3;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9bc;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9bd;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f33e;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f373;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f37c;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f384;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f393;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f3a4;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f3a8;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f3eb;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f3ed;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f4bb;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f4bc;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f527;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f52c;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f680;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f692;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9af;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9b0;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9b1;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9b2;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9b3;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9bc;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9bd;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f33e;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f373;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f37c;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f384;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f393;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f3a4;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f3a8;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f3eb;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f3ed;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f4bb;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f4bc;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f527;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f52c;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f680;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f692;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9af;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9b0;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9b1;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9b2;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9b3;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9bc;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9bd;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f33e;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f373;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f37c;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f384;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f393;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f3a4;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f3a8;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f3eb;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f3ed;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f4bb;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f4bc;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f527;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f52c;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f680;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f692;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9af;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9b0;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9b1;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9b2;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9b3;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9bc;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9bd;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f33e;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f373;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f37c;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f384;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f393;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f3a4;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f3a8;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f3eb;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f3ed;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f4bb;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f4bc;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f527;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f52c;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f680;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f692;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9af;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9b0;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9b1;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9b2;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9b3;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9bc;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9bd;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f33e;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f373;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f37c;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f384;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f393;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f3a4;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f3a8;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f3eb;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f3ed;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f4bb;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f4bc;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f527;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f52c;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f680;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f692;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9af;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9b0;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9b1;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9b2;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9b3;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9bc;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9bd;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f33e;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f373;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f37c;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f384;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f393;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f3a4;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f3a8;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f3eb;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f3ed;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f4bb;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f4bc;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f527;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f52c;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f680;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f692;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9af;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9b0;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9b1;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9b2;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9b3;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9bc;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9bd;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f33e;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f373;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f37c;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f384;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f393;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f3a4;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f3a8;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f3eb;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f3ed;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f4bb;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f4bc;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f527;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f52c;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f680;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f692;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9af;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9b0;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9b1;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9b2;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9b3;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9bc;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9bd;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f33e;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f373;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f37c;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f384;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f393;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f3a4;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f3a8;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f3eb;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f3ed;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f4bb;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f4bc;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f527;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f52c;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f680;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f692;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9af;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9b0;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9b1;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9b2;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9b3;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9bc;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9bd;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f33e;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f373;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f37c;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f384;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f393;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f3a4;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f3a8;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f3eb;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f3ed;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f4bb;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f4bc;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f527;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f52c;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f680;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f692;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9af;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9b0;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9b1;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9b2;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9b3;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9bc;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9bd;', '&#x1f3f3;&#xfe0f;&#x200d;&#x1f308;', '&#x1f3c3;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c3;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c4;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c4;&#x200d;&#x2642;&#xfe0f;', '&#x1f3ca;&#x200d;&#x2640;&#xfe0f;', '&#x1f3ca;&#x200d;&#x2642;&#xfe0f;', '&#x1f3f4;&#x200d;&#x2620;&#xfe0f;', '&#x1f43b;&#x200d;&#x2744;&#xfe0f;', '&#x1f468;&#x200d;&#x2695;&#xfe0f;', '&#x1f468;&#x200d;&#x2696;&#xfe0f;', '&#x1f468;&#x200d;&#x2708;&#xfe0f;', '&#x1f469;&#x200d;&#x2695;&#xfe0f;', '&#x1f469;&#x200d;&#x2696;&#xfe0f;', '&#x1f469;&#x200d;&#x2708;&#xfe0f;', '&#x1f46e;&#x200d;&#x2640;&#xfe0f;', '&#x1f46e;&#x200d;&#x2642;&#xfe0f;', '&#x1f46f;&#x200d;&#x2640;&#xfe0f;', '&#x1f46f;&#x200d;&#x2642;&#xfe0f;', '&#x1f470;&#x200d;&#x2640;&#xfe0f;', '&#x1f470;&#x200d;&#x2642;&#xfe0f;', '&#x1f471;&#x200d;&#x2640;&#xfe0f;', '&#x1f471;&#x200d;&#x2642;&#xfe0f;', '&#x1f473;&#x200d;&#x2640;&#xfe0f;', '&#x1f473;&#x200d;&#x2642;&#xfe0f;', '&#x1f477;&#x200d;&#x2640;&#xfe0f;', '&#x1f477;&#x200d;&#x2642;&#xfe0f;', '&#x1f481;&#x200d;&#x2640;&#xfe0f;', '&#x1f481;&#x200d;&#x2642;&#xfe0f;', '&#x1f482;&#x200d;&#x2640;&#xfe0f;', '&#x1f482;&#x200d;&#x2642;&#xfe0f;', '&#x1f486;&#x200d;&#x2640;&#xfe0f;', '&#x1f486;&#x200d;&#x2642;&#xfe0f;', '&#x1f487;&#x200d;&#x2640;&#xfe0f;', '&#x1f487;&#x200d;&#x2642;&#xfe0f;', '&#x1f645;&#x200d;&#x2640;&#xfe0f;', '&#x1f645;&#x200d;&#x2642;&#xfe0f;', '&#x1f646;&#x200d;&#x2640;&#xfe0f;', '&#x1f646;&#x200d;&#x2642;&#xfe0f;', '&#x1f647;&#x200d;&#x2640;&#xfe0f;', '&#x1f647;&#x200d;&#x2642;&#xfe0f;', '&#x1f64b;&#x200d;&#x2640;&#xfe0f;', '&#x1f64b;&#x200d;&#x2642;&#xfe0f;', '&#x1f64d;&#x200d;&#x2640;&#xfe0f;', '&#x1f64d;&#x200d;&#x2642;&#xfe0f;', '&#x1f64e;&#x200d;&#x2640;&#xfe0f;', '&#x1f64e;&#x200d;&#x2642;&#xfe0f;', '&#x1f6a3;&#x200d;&#x2640;&#xfe0f;', '&#x1f6a3;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b4;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b4;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b5;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b5;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b6;&#x200d;&#x2642;&#xfe0f;', '&#x1f926;&#x200d;&#x2640;&#xfe0f;', '&#x1f926;&#x200d;&#x2642;&#xfe0f;', '&#x1f935;&#x200d;&#x2640;&#xfe0f;', '&#x1f935;&#x200d;&#x2642;&#xfe0f;', '&#x1f937;&#x200d;&#x2640;&#xfe0f;', '&#x1f937;&#x200d;&#x2642;&#xfe0f;', '&#x1f938;&#x200d;&#x2640;&#xfe0f;', '&#x1f938;&#x200d;&#x2642;&#xfe0f;', '&#x1f939;&#x200d;&#x2640;&#xfe0f;', '&#x1f939;&#x200d;&#x2642;&#xfe0f;', '&#x1f93c;&#x200d;&#x2640;&#xfe0f;', '&#x1f93c;&#x200d;&#x2642;&#xfe0f;', '&#x1f93d;&#x200d;&#x2640;&#xfe0f;', '&#x1f93d;&#x200d;&#x2642;&#xfe0f;', '&#x1f93e;&#x200d;&#x2640;&#xfe0f;', '&#x1f93e;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b8;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b8;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b9;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b9;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x200d;&#x2640;&#xfe0f;', '&#x1f9ce;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cf;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cf;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d1;&#x200d;&#x2695;&#xfe0f;', '&#x1f9d1;&#x200d;&#x2696;&#xfe0f;', '&#x1f9d1;&#x200d;&#x2708;&#xfe0f;', '&#x1f9d6;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d6;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d7;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d7;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d8;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d8;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d9;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d9;&#x200d;&#x2642;&#xfe0f;', '&#x1f9da;&#x200d;&#x2640;&#xfe0f;', '&#x1f9da;&#x200d;&#x2642;&#xfe0f;', '&#x1f9db;&#x200d;&#x2640;&#xfe0f;', '&#x1f9db;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9de;&#x200d;&#x2640;&#xfe0f;', '&#x1f9de;&#x200d;&#x2642;&#xfe0f;', '&#x1f9df;&#x200d;&#x2640;&#xfe0f;', '&#x1f9df;&#x200d;&#x2642;&#xfe0f;', '&#x1f415;&#x200d;&#x1f9ba;', '&#x1f441;&#x200d;&#x1f5e8;', '&#x1f468;&#x200d;&#x1f33e;', '&#x1f468;&#x200d;&#x1f373;', '&#x1f468;&#x200d;&#x1f37c;', '&#x1f468;&#x200d;&#x1f384;', '&#x1f468;&#x200d;&#x1f393;', '&#x1f468;&#x200d;&#x1f3a4;', '&#x1f468;&#x200d;&#x1f3a8;', '&#x1f468;&#x200d;&#x1f3eb;', '&#x1f468;&#x200d;&#x1f3ed;', '&#x1f468;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f467;', '&#x1f468;&#x200d;&#x1f4bb;', '&#x1f468;&#x200d;&#x1f4bc;', '&#x1f468;&#x200d;&#x1f527;', '&#x1f468;&#x200d;&#x1f52c;', '&#x1f468;&#x200d;&#x1f680;', '&#x1f468;&#x200d;&#x1f692;', '&#x1f468;&#x200d;&#x1f9af;', '&#x1f468;&#x200d;&#x1f9b0;', '&#x1f468;&#x200d;&#x1f9b1;', '&#x1f468;&#x200d;&#x1f9b2;', '&#x1f468;&#x200d;&#x1f9b3;', '&#x1f468;&#x200d;&#x1f9bc;', '&#x1f468;&#x200d;&#x1f9bd;', '&#x1f469;&#x200d;&#x1f33e;', '&#x1f469;&#x200d;&#x1f373;', '&#x1f469;&#x200d;&#x1f37c;', '&#x1f469;&#x200d;&#x1f384;', '&#x1f469;&#x200d;&#x1f393;', '&#x1f469;&#x200d;&#x1f3a4;', '&#x1f469;&#x200d;&#x1f3a8;', '&#x1f469;&#x200d;&#x1f3eb;', '&#x1f469;&#x200d;&#x1f3ed;', '&#x1f469;&#x200d;&#x1f466;', '&#x1f469;&#x200d;&#x1f467;', '&#x1f469;&#x200d;&#x1f4bb;', '&#x1f469;&#x200d;&#x1f4bc;', '&#x1f469;&#x200d;&#x1f527;', '&#x1f469;&#x200d;&#x1f52c;', '&#x1f469;&#x200d;&#x1f680;', '&#x1f469;&#x200d;&#x1f692;', '&#x1f469;&#x200d;&#x1f9af;', '&#x1f469;&#x200d;&#x1f9b0;', '&#x1f469;&#x200d;&#x1f9b1;', '&#x1f469;&#x200d;&#x1f9b2;', '&#x1f469;&#x200d;&#x1f9b3;', '&#x1f469;&#x200d;&#x1f9bc;', '&#x1f469;&#x200d;&#x1f9bd;', '&#x1f9d1;&#x200d;&#x1f33e;', '&#x1f9d1;&#x200d;&#x1f373;', '&#x1f9d1;&#x200d;&#x1f37c;', '&#x1f9d1;&#x200d;&#x1f384;', '&#x1f9d1;&#x200d;&#x1f393;', '&#x1f9d1;&#x200d;&#x1f3a4;', '&#x1f9d1;&#x200d;&#x1f3a8;', '&#x1f9d1;&#x200d;&#x1f3eb;', '&#x1f9d1;&#x200d;&#x1f3ed;', '&#x1f9d1;&#x200d;&#x1f4bb;', '&#x1f9d1;&#x200d;&#x1f4bc;', '&#x1f9d1;&#x200d;&#x1f527;', '&#x1f9d1;&#x200d;&#x1f52c;', '&#x1f9d1;&#x200d;&#x1f680;', '&#x1f9d1;&#x200d;&#x1f692;', '&#x1f9d1;&#x200d;&#x1f9af;', '&#x1f9d1;&#x200d;&#x1f9b0;', '&#x1f9d1;&#x200d;&#x1f9b1;', '&#x1f9d1;&#x200d;&#x1f9b2;', '&#x1f9d1;&#x200d;&#x1f9b3;', '&#x1f9d1;&#x200d;&#x1f9bc;', '&#x1f9d1;&#x200d;&#x1f9bd;', '&#x1f408;&#x200d;&#x2b1b;', '&#x1f1e6;&#x1f1e8;', '&#x1f1e6;&#x1f1e9;', '&#x1f1e6;&#x1f1ea;', '&#x1f1e6;&#x1f1eb;', '&#x1f1e6;&#x1f1ec;', '&#x1f1e6;&#x1f1ee;', '&#x1f1e6;&#x1f1f1;', '&#x1f1e6;&#x1f1f2;', '&#x1f1e6;&#x1f1f4;', '&#x1f1e6;&#x1f1f6;', '&#x1f1e6;&#x1f1f7;', '&#x1f1e6;&#x1f1f8;', '&#x1f1e6;&#x1f1f9;', '&#x1f1e6;&#x1f1fa;', '&#x1f1e6;&#x1f1fc;', '&#x1f1e6;&#x1f1fd;', '&#x1f1e6;&#x1f1ff;', '&#x1f1e7;&#x1f1e6;', '&#x1f1e7;&#x1f1e7;', '&#x1f1e7;&#x1f1e9;', '&#x1f1e7;&#x1f1ea;', '&#x1f1e7;&#x1f1eb;', '&#x1f1e7;&#x1f1ec;', '&#x1f1e7;&#x1f1ed;', '&#x1f1e7;&#x1f1ee;', '&#x1f1e7;&#x1f1ef;', '&#x1f1e7;&#x1f1f1;', '&#x1f1e7;&#x1f1f2;', '&#x1f1e7;&#x1f1f3;', '&#x1f1e7;&#x1f1f4;', '&#x1f1e7;&#x1f1f6;', '&#x1f1e7;&#x1f1f7;', '&#x1f1e7;&#x1f1f8;', '&#x1f1e7;&#x1f1f9;', '&#x1f1e7;&#x1f1fb;', '&#x1f1e7;&#x1f1fc;', '&#x1f1e7;&#x1f1fe;', '&#x1f1e7;&#x1f1ff;', '&#x1f1e8;&#x1f1e6;', '&#x1f1e8;&#x1f1e8;', '&#x1f1e8;&#x1f1e9;', '&#x1f1e8;&#x1f1eb;', '&#x1f1e8;&#x1f1ec;', '&#x1f1e8;&#x1f1ed;', '&#x1f1e8;&#x1f1ee;', '&#x1f1e8;&#x1f1f0;', '&#x1f1e8;&#x1f1f1;', '&#x1f1e8;&#x1f1f2;', '&#x1f1e8;&#x1f1f3;', '&#x1f1e8;&#x1f1f4;', '&#x1f1e8;&#x1f1f5;', '&#x1f1e8;&#x1f1f7;', '&#x1f1e8;&#x1f1fa;', '&#x1f1e8;&#x1f1fb;', '&#x1f1e8;&#x1f1fc;', '&#x1f1e8;&#x1f1fd;', '&#x1f1e8;&#x1f1fe;', '&#x1f1e8;&#x1f1ff;', '&#x1f1e9;&#x1f1ea;', '&#x1f1e9;&#x1f1ec;', '&#x1f1e9;&#x1f1ef;', '&#x1f1e9;&#x1f1f0;', '&#x1f1e9;&#x1f1f2;', '&#x1f1e9;&#x1f1f4;', '&#x1f1e9;&#x1f1ff;', '&#x1f1ea;&#x1f1e6;', '&#x1f1ea;&#x1f1e8;', '&#x1f1ea;&#x1f1ea;', '&#x1f1ea;&#x1f1ec;', '&#x1f1ea;&#x1f1ed;', '&#x1f1ea;&#x1f1f7;', '&#x1f1ea;&#x1f1f8;', '&#x1f1ea;&#x1f1f9;', '&#x1f1ea;&#x1f1fa;', '&#x1f1eb;&#x1f1ee;', '&#x1f1eb;&#x1f1ef;', '&#x1f1eb;&#x1f1f0;', '&#x1f1eb;&#x1f1f2;', '&#x1f1eb;&#x1f1f4;', '&#x1f1eb;&#x1f1f7;', '&#x1f1ec;&#x1f1e6;', '&#x1f1ec;&#x1f1e7;', '&#x1f1ec;&#x1f1e9;', '&#x1f1ec;&#x1f1ea;', '&#x1f1ec;&#x1f1eb;', '&#x1f1ec;&#x1f1ec;', '&#x1f1ec;&#x1f1ed;', '&#x1f1ec;&#x1f1ee;', '&#x1f1ec;&#x1f1f1;', '&#x1f1ec;&#x1f1f2;', '&#x1f1ec;&#x1f1f3;', '&#x1f1ec;&#x1f1f5;', '&#x1f1ec;&#x1f1f6;', '&#x1f1ec;&#x1f1f7;', '&#x1f1ec;&#x1f1f8;', '&#x1f1ec;&#x1f1f9;', '&#x1f1ec;&#x1f1fa;', '&#x1f1ec;&#x1f1fc;', '&#x1f1ec;&#x1f1fe;', '&#x1f1ed;&#x1f1f0;', '&#x1f1ed;&#x1f1f2;', '&#x1f1ed;&#x1f1f3;', '&#x1f1ed;&#x1f1f7;', '&#x1f1ed;&#x1f1f9;', '&#x1f1ed;&#x1f1fa;', '&#x1f1ee;&#x1f1e8;', '&#x1f1ee;&#x1f1e9;', '&#x1f1ee;&#x1f1ea;', '&#x1f1ee;&#x1f1f1;', '&#x1f1ee;&#x1f1f2;', '&#x1f1ee;&#x1f1f3;', '&#x1f1ee;&#x1f1f4;', '&#x1f1ee;&#x1f1f6;', '&#x1f1ee;&#x1f1f7;', '&#x1f1ee;&#x1f1f8;', '&#x1f1ee;&#x1f1f9;', '&#x1f1ef;&#x1f1ea;', '&#x1f1ef;&#x1f1f2;', '&#x1f1ef;&#x1f1f4;', '&#x1f1ef;&#x1f1f5;', '&#x1f1f0;&#x1f1ea;', '&#x1f1f0;&#x1f1ec;', '&#x1f1f0;&#x1f1ed;', '&#x1f1f0;&#x1f1ee;', '&#x1f1f0;&#x1f1f2;', '&#x1f1f0;&#x1f1f3;', '&#x1f1f0;&#x1f1f5;', '&#x1f1f0;&#x1f1f7;', '&#x1f1f0;&#x1f1fc;', '&#x1f1f0;&#x1f1fe;', '&#x1f1f0;&#x1f1ff;', '&#x1f1f1;&#x1f1e6;', '&#x1f1f1;&#x1f1e7;', '&#x1f1f1;&#x1f1e8;', '&#x1f1f1;&#x1f1ee;', '&#x1f1f1;&#x1f1f0;', '&#x1f1f1;&#x1f1f7;', '&#x1f1f1;&#x1f1f8;', '&#x1f1f1;&#x1f1f9;', '&#x1f1f1;&#x1f1fa;', '&#x1f1f1;&#x1f1fb;', '&#x1f1f1;&#x1f1fe;', '&#x1f1f2;&#x1f1e6;', '&#x1f1f2;&#x1f1e8;', '&#x1f1f2;&#x1f1e9;', '&#x1f1f2;&#x1f1ea;', '&#x1f1f2;&#x1f1eb;', '&#x1f1f2;&#x1f1ec;', '&#x1f1f2;&#x1f1ed;', '&#x1f1f2;&#x1f1f0;', '&#x1f1f2;&#x1f1f1;', '&#x1f1f2;&#x1f1f2;', '&#x1f1f2;&#x1f1f3;', '&#x1f1f2;&#x1f1f4;', '&#x1f1f2;&#x1f1f5;', '&#x1f1f2;&#x1f1f6;', '&#x1f1f2;&#x1f1f7;', '&#x1f1f2;&#x1f1f8;', '&#x1f1f2;&#x1f1f9;', '&#x1f1f2;&#x1f1fa;', '&#x1f1f2;&#x1f1fb;', '&#x1f1f2;&#x1f1fc;', '&#x1f1f2;&#x1f1fd;', '&#x1f1f2;&#x1f1fe;', '&#x1f1f2;&#x1f1ff;', '&#x1f1f3;&#x1f1e6;', '&#x1f1f3;&#x1f1e8;', '&#x1f1f3;&#x1f1ea;', '&#x1f1f3;&#x1f1eb;', '&#x1f1f3;&#x1f1ec;', '&#x1f1f3;&#x1f1ee;', '&#x1f1f3;&#x1f1f1;', '&#x1f1f3;&#x1f1f4;', '&#x1f1f3;&#x1f1f5;', '&#x1f1f3;&#x1f1f7;', '&#x1f1f3;&#x1f1fa;', '&#x1f1f3;&#x1f1ff;', '&#x1f1f4;&#x1f1f2;', '&#x1f1f5;&#x1f1e6;', '&#x1f1f5;&#x1f1ea;', '&#x1f1f5;&#x1f1eb;', '&#x1f1f5;&#x1f1ec;', '&#x1f1f5;&#x1f1ed;', '&#x1f1f5;&#x1f1f0;', '&#x1f1f5;&#x1f1f1;', '&#x1f1f5;&#x1f1f2;', '&#x1f1f5;&#x1f1f3;', '&#x1f1f5;&#x1f1f7;', '&#x1f1f5;&#x1f1f8;', '&#x1f1f5;&#x1f1f9;', '&#x1f1f5;&#x1f1fc;', '&#x1f1f5;&#x1f1fe;', '&#x1f1f6;&#x1f1e6;', '&#x1f1f7;&#x1f1ea;', '&#x1f1f7;&#x1f1f4;', '&#x1f1f7;&#x1f1f8;', '&#x1f1f7;&#x1f1fa;', '&#x1f1f7;&#x1f1fc;', '&#x1f1f8;&#x1f1e6;', '&#x1f1f8;&#x1f1e7;', '&#x1f1f8;&#x1f1e8;', '&#x1f1f8;&#x1f1e9;', '&#x1f1f8;&#x1f1ea;', '&#x1f1f8;&#x1f1ec;', '&#x1f1f8;&#x1f1ed;', '&#x1f1f8;&#x1f1ee;', '&#x1f1f8;&#x1f1ef;', '&#x1f1f8;&#x1f1f0;', '&#x1f1f8;&#x1f1f1;', '&#x1f1f8;&#x1f1f2;', '&#x1f1f8;&#x1f1f3;', '&#x1f1f8;&#x1f1f4;', '&#x1f1f8;&#x1f1f7;', '&#x1f1f8;&#x1f1f8;', '&#x1f1f8;&#x1f1f9;', '&#x1f1f8;&#x1f1fb;', '&#x1f1f8;&#x1f1fd;', '&#x1f1f8;&#x1f1fe;', '&#x1f1f8;&#x1f1ff;', '&#x1f1f9;&#x1f1e6;', '&#x1f1f9;&#x1f1e8;', '&#x1f1f9;&#x1f1e9;', '&#x1f1f9;&#x1f1eb;', '&#x1f1f9;&#x1f1ec;', '&#x1f1f9;&#x1f1ed;', '&#x1f1f9;&#x1f1ef;', '&#x1f1f9;&#x1f1f0;', '&#x1f1f9;&#x1f1f1;', '&#x1f1f9;&#x1f1f2;', '&#x1f1f9;&#x1f1f3;', '&#x1f1f9;&#x1f1f4;', '&#x1f1f9;&#x1f1f7;', '&#x1f1f9;&#x1f1f9;', '&#x1f1f9;&#x1f1fb;', '&#x1f1f9;&#x1f1fc;', '&#x1f1f9;&#x1f1ff;', '&#x1f1fa;&#x1f1e6;', '&#x1f1fa;&#x1f1ec;', '&#x1f1fa;&#x1f1f2;', '&#x1f1fa;&#x1f1f3;', '&#x1f1fa;&#x1f1f8;', '&#x1f1fa;&#x1f1fe;', '&#x1f1fa;&#x1f1ff;', '&#x1f1fb;&#x1f1e6;', '&#x1f1fb;&#x1f1e8;', '&#x1f1fb;&#x1f1ea;', '&#x1f1fb;&#x1f1ec;', '&#x1f1fb;&#x1f1ee;', '&#x1f1fb;&#x1f1f3;', '&#x1f1fb;&#x1f1fa;', '&#x1f1fc;&#x1f1eb;', '&#x1f1fc;&#x1f1f8;', '&#x1f1fd;&#x1f1f0;', '&#x1f1fe;&#x1f1ea;', '&#x1f1fe;&#x1f1f9;', '&#x1f1ff;&#x1f1e6;', '&#x1f1ff;&#x1f1f2;', '&#x1f1ff;&#x1f1fc;', '&#x1f385;&#x1f3fb;', '&#x1f385;&#x1f3fc;', '&#x1f385;&#x1f3fd;', '&#x1f385;&#x1f3fe;', '&#x1f385;&#x1f3ff;', '&#x1f3c2;&#x1f3fb;', '&#x1f3c2;&#x1f3fc;', '&#x1f3c2;&#x1f3fd;', '&#x1f3c2;&#x1f3fe;', '&#x1f3c2;&#x1f3ff;', '&#x1f3c3;&#x1f3fb;', '&#x1f3c3;&#x1f3fc;', '&#x1f3c3;&#x1f3fd;', '&#x1f3c3;&#x1f3fe;', '&#x1f3c3;&#x1f3ff;', '&#x1f3c4;&#x1f3fb;', '&#x1f3c4;&#x1f3fc;', '&#x1f3c4;&#x1f3fd;', '&#x1f3c4;&#x1f3fe;', '&#x1f3c4;&#x1f3ff;', '&#x1f3c7;&#x1f3fb;', '&#x1f3c7;&#x1f3fc;', '&#x1f3c7;&#x1f3fd;', '&#x1f3c7;&#x1f3fe;', '&#x1f3c7;&#x1f3ff;', '&#x1f3ca;&#x1f3fb;', '&#x1f3ca;&#x1f3fc;', '&#x1f3ca;&#x1f3fd;', '&#x1f3ca;&#x1f3fe;', '&#x1f3ca;&#x1f3ff;', '&#x1f3cb;&#x1f3fb;', '&#x1f3cb;&#x1f3fc;', '&#x1f3cb;&#x1f3fd;', '&#x1f3cb;&#x1f3fe;', '&#x1f3cb;&#x1f3ff;', '&#x1f3cc;&#x1f3fb;', '&#x1f3cc;&#x1f3fc;', '&#x1f3cc;&#x1f3fd;', '&#x1f3cc;&#x1f3fe;', '&#x1f3cc;&#x1f3ff;', '&#x1f442;&#x1f3fb;', '&#x1f442;&#x1f3fc;', '&#x1f442;&#x1f3fd;', '&#x1f442;&#x1f3fe;', '&#x1f442;&#x1f3ff;', '&#x1f443;&#x1f3fb;', '&#x1f443;&#x1f3fc;', '&#x1f443;&#x1f3fd;', '&#x1f443;&#x1f3fe;', '&#x1f443;&#x1f3ff;', '&#x1f446;&#x1f3fb;', '&#x1f446;&#x1f3fc;', '&#x1f446;&#x1f3fd;', '&#x1f446;&#x1f3fe;', '&#x1f446;&#x1f3ff;', '&#x1f447;&#x1f3fb;', '&#x1f447;&#x1f3fc;', '&#x1f447;&#x1f3fd;', '&#x1f447;&#x1f3fe;', '&#x1f447;&#x1f3ff;', '&#x1f448;&#x1f3fb;', '&#x1f448;&#x1f3fc;', '&#x1f448;&#x1f3fd;', '&#x1f448;&#x1f3fe;', '&#x1f448;&#x1f3ff;', '&#x1f449;&#x1f3fb;', '&#x1f449;&#x1f3fc;', '&#x1f449;&#x1f3fd;', '&#x1f449;&#x1f3fe;', '&#x1f449;&#x1f3ff;', '&#x1f44a;&#x1f3fb;', '&#x1f44a;&#x1f3fc;', '&#x1f44a;&#x1f3fd;', '&#x1f44a;&#x1f3fe;', '&#x1f44a;&#x1f3ff;', '&#x1f44b;&#x1f3fb;', '&#x1f44b;&#x1f3fc;', '&#x1f44b;&#x1f3fd;', '&#x1f44b;&#x1f3fe;', '&#x1f44b;&#x1f3ff;', '&#x1f44c;&#x1f3fb;', '&#x1f44c;&#x1f3fc;', '&#x1f44c;&#x1f3fd;', '&#x1f44c;&#x1f3fe;', '&#x1f44c;&#x1f3ff;', '&#x1f44d;&#x1f3fb;', '&#x1f44d;&#x1f3fc;', '&#x1f44d;&#x1f3fd;', '&#x1f44d;&#x1f3fe;', '&#x1f44d;&#x1f3ff;', '&#x1f44e;&#x1f3fb;', '&#x1f44e;&#x1f3fc;', '&#x1f44e;&#x1f3fd;', '&#x1f44e;&#x1f3fe;', '&#x1f44e;&#x1f3ff;', '&#x1f44f;&#x1f3fb;', '&#x1f44f;&#x1f3fc;', '&#x1f44f;&#x1f3fd;', '&#x1f44f;&#x1f3fe;', '&#x1f44f;&#x1f3ff;', '&#x1f450;&#x1f3fb;', '&#x1f450;&#x1f3fc;', '&#x1f450;&#x1f3fd;', '&#x1f450;&#x1f3fe;', '&#x1f450;&#x1f3ff;', '&#x1f466;&#x1f3fb;', '&#x1f466;&#x1f3fc;', '&#x1f466;&#x1f3fd;', '&#x1f466;&#x1f3fe;', '&#x1f466;&#x1f3ff;', '&#x1f467;&#x1f3fb;', '&#x1f467;&#x1f3fc;', '&#x1f467;&#x1f3fd;', '&#x1f467;&#x1f3fe;', '&#x1f467;&#x1f3ff;', '&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3ff;', '&#x1f46b;&#x1f3fb;', '&#x1f46b;&#x1f3fc;', '&#x1f46b;&#x1f3fd;', '&#x1f46b;&#x1f3fe;', '&#x1f46b;&#x1f3ff;', '&#x1f46c;&#x1f3fb;', '&#x1f46c;&#x1f3fc;', '&#x1f46c;&#x1f3fd;', '&#x1f46c;&#x1f3fe;', '&#x1f46c;&#x1f3ff;', '&#x1f46d;&#x1f3fb;', '&#x1f46d;&#x1f3fc;', '&#x1f46d;&#x1f3fd;', '&#x1f46d;&#x1f3fe;', '&#x1f46d;&#x1f3ff;', '&#x1f46e;&#x1f3fb;', '&#x1f46e;&#x1f3fc;', '&#x1f46e;&#x1f3fd;', '&#x1f46e;&#x1f3fe;', '&#x1f46e;&#x1f3ff;', '&#x1f470;&#x1f3fb;', '&#x1f470;&#x1f3fc;', '&#x1f470;&#x1f3fd;', '&#x1f470;&#x1f3fe;', '&#x1f470;&#x1f3ff;', '&#x1f471;&#x1f3fb;', '&#x1f471;&#x1f3fc;', '&#x1f471;&#x1f3fd;', '&#x1f471;&#x1f3fe;', '&#x1f471;&#x1f3ff;', '&#x1f472;&#x1f3fb;', '&#x1f472;&#x1f3fc;', '&#x1f472;&#x1f3fd;', '&#x1f472;&#x1f3fe;', '&#x1f472;&#x1f3ff;', '&#x1f473;&#x1f3fb;', '&#x1f473;&#x1f3fc;', '&#x1f473;&#x1f3fd;', '&#x1f473;&#x1f3fe;', '&#x1f473;&#x1f3ff;', '&#x1f474;&#x1f3fb;', '&#x1f474;&#x1f3fc;', '&#x1f474;&#x1f3fd;', '&#x1f474;&#x1f3fe;', '&#x1f474;&#x1f3ff;', '&#x1f475;&#x1f3fb;', '&#x1f475;&#x1f3fc;', '&#x1f475;&#x1f3fd;', '&#x1f475;&#x1f3fe;', '&#x1f475;&#x1f3ff;', '&#x1f476;&#x1f3fb;', '&#x1f476;&#x1f3fc;', '&#x1f476;&#x1f3fd;', '&#x1f476;&#x1f3fe;', '&#x1f476;&#x1f3ff;', '&#x1f477;&#x1f3fb;', '&#x1f477;&#x1f3fc;', '&#x1f477;&#x1f3fd;', '&#x1f477;&#x1f3fe;', '&#x1f477;&#x1f3ff;', '&#x1f478;&#x1f3fb;', '&#x1f478;&#x1f3fc;', '&#x1f478;&#x1f3fd;', '&#x1f478;&#x1f3fe;', '&#x1f478;&#x1f3ff;', '&#x1f47c;&#x1f3fb;', '&#x1f47c;&#x1f3fc;', '&#x1f47c;&#x1f3fd;', '&#x1f47c;&#x1f3fe;', '&#x1f47c;&#x1f3ff;', '&#x1f481;&#x1f3fb;', '&#x1f481;&#x1f3fc;', '&#x1f481;&#x1f3fd;', '&#x1f481;&#x1f3fe;', '&#x1f481;&#x1f3ff;', '&#x1f482;&#x1f3fb;', '&#x1f482;&#x1f3fc;', '&#x1f482;&#x1f3fd;', '&#x1f482;&#x1f3fe;', '&#x1f482;&#x1f3ff;', '&#x1f483;&#x1f3fb;', '&#x1f483;&#x1f3fc;', '&#x1f483;&#x1f3fd;', '&#x1f483;&#x1f3fe;', '&#x1f483;&#x1f3ff;', '&#x1f485;&#x1f3fb;', '&#x1f485;&#x1f3fc;', '&#x1f485;&#x1f3fd;', '&#x1f485;&#x1f3fe;', '&#x1f485;&#x1f3ff;', '&#x1f486;&#x1f3fb;', '&#x1f486;&#x1f3fc;', '&#x1f486;&#x1f3fd;', '&#x1f486;&#x1f3fe;', '&#x1f486;&#x1f3ff;', '&#x1f487;&#x1f3fb;', '&#x1f487;&#x1f3fc;', '&#x1f487;&#x1f3fd;', '&#x1f487;&#x1f3fe;', '&#x1f487;&#x1f3ff;', '&#x1f4aa;&#x1f3fb;', '&#x1f4aa;&#x1f3fc;', '&#x1f4aa;&#x1f3fd;', '&#x1f4aa;&#x1f3fe;', '&#x1f4aa;&#x1f3ff;', '&#x1f574;&#x1f3fb;', '&#x1f574;&#x1f3fc;', '&#x1f574;&#x1f3fd;', '&#x1f574;&#x1f3fe;', '&#x1f574;&#x1f3ff;', '&#x1f575;&#x1f3fb;', '&#x1f575;&#x1f3fc;', '&#x1f575;&#x1f3fd;', '&#x1f575;&#x1f3fe;', '&#x1f575;&#x1f3ff;', '&#x1f57a;&#x1f3fb;', '&#x1f57a;&#x1f3fc;', '&#x1f57a;&#x1f3fd;', '&#x1f57a;&#x1f3fe;', '&#x1f57a;&#x1f3ff;', '&#x1f590;&#x1f3fb;', '&#x1f590;&#x1f3fc;', '&#x1f590;&#x1f3fd;', '&#x1f590;&#x1f3fe;', '&#x1f590;&#x1f3ff;', '&#x1f595;&#x1f3fb;', '&#x1f595;&#x1f3fc;', '&#x1f595;&#x1f3fd;', '&#x1f595;&#x1f3fe;', '&#x1f595;&#x1f3ff;', '&#x1f596;&#x1f3fb;', '&#x1f596;&#x1f3fc;', '&#x1f596;&#x1f3fd;', '&#x1f596;&#x1f3fe;', '&#x1f596;&#x1f3ff;', '&#x1f645;&#x1f3fb;', '&#x1f645;&#x1f3fc;', '&#x1f645;&#x1f3fd;', '&#x1f645;&#x1f3fe;', '&#x1f645;&#x1f3ff;', '&#x1f646;&#x1f3fb;', '&#x1f646;&#x1f3fc;', '&#x1f646;&#x1f3fd;', '&#x1f646;&#x1f3fe;', '&#x1f646;&#x1f3ff;', '&#x1f647;&#x1f3fb;', '&#x1f647;&#x1f3fc;', '&#x1f647;&#x1f3fd;', '&#x1f647;&#x1f3fe;', '&#x1f647;&#x1f3ff;', '&#x1f64b;&#x1f3fb;', '&#x1f64b;&#x1f3fc;', '&#x1f64b;&#x1f3fd;', '&#x1f64b;&#x1f3fe;', '&#x1f64b;&#x1f3ff;', '&#x1f64c;&#x1f3fb;', '&#x1f64c;&#x1f3fc;', '&#x1f64c;&#x1f3fd;', '&#x1f64c;&#x1f3fe;', '&#x1f64c;&#x1f3ff;', '&#x1f64d;&#x1f3fb;', '&#x1f64d;&#x1f3fc;', '&#x1f64d;&#x1f3fd;', '&#x1f64d;&#x1f3fe;', '&#x1f64d;&#x1f3ff;', '&#x1f64e;&#x1f3fb;', '&#x1f64e;&#x1f3fc;', '&#x1f64e;&#x1f3fd;', '&#x1f64e;&#x1f3fe;', '&#x1f64e;&#x1f3ff;', '&#x1f64f;&#x1f3fb;', '&#x1f64f;&#x1f3fc;', '&#x1f64f;&#x1f3fd;', '&#x1f64f;&#x1f3fe;', '&#x1f64f;&#x1f3ff;', '&#x1f6a3;&#x1f3fb;', '&#x1f6a3;&#x1f3fc;', '&#x1f6a3;&#x1f3fd;', '&#x1f6a3;&#x1f3fe;', '&#x1f6a3;&#x1f3ff;', '&#x1f6b4;&#x1f3fb;', '&#x1f6b4;&#x1f3fc;', '&#x1f6b4;&#x1f3fd;', '&#x1f6b4;&#x1f3fe;', '&#x1f6b4;&#x1f3ff;', '&#x1f6b5;&#x1f3fb;', '&#x1f6b5;&#x1f3fc;', '&#x1f6b5;&#x1f3fd;', '&#x1f6b5;&#x1f3fe;', '&#x1f6b5;&#x1f3ff;', '&#x1f6b6;&#x1f3fb;', '&#x1f6b6;&#x1f3fc;', '&#x1f6b6;&#x1f3fd;', '&#x1f6b6;&#x1f3fe;', '&#x1f6b6;&#x1f3ff;', '&#x1f6c0;&#x1f3fb;', '&#x1f6c0;&#x1f3fc;', '&#x1f6c0;&#x1f3fd;', '&#x1f6c0;&#x1f3fe;', '&#x1f6c0;&#x1f3ff;', '&#x1f6cc;&#x1f3fb;', '&#x1f6cc;&#x1f3fc;', '&#x1f6cc;&#x1f3fd;', '&#x1f6cc;&#x1f3fe;', '&#x1f6cc;&#x1f3ff;', '&#x1f90c;&#x1f3fb;', '&#x1f90c;&#x1f3fc;', '&#x1f90c;&#x1f3fd;', '&#x1f90c;&#x1f3fe;', '&#x1f90c;&#x1f3ff;', '&#x1f90f;&#x1f3fb;', '&#x1f90f;&#x1f3fc;', '&#x1f90f;&#x1f3fd;', '&#x1f90f;&#x1f3fe;', '&#x1f90f;&#x1f3ff;', '&#x1f918;&#x1f3fb;', '&#x1f918;&#x1f3fc;', '&#x1f918;&#x1f3fd;', '&#x1f918;&#x1f3fe;', '&#x1f918;&#x1f3ff;', '&#x1f919;&#x1f3fb;', '&#x1f919;&#x1f3fc;', '&#x1f919;&#x1f3fd;', '&#x1f919;&#x1f3fe;', '&#x1f919;&#x1f3ff;', '&#x1f91a;&#x1f3fb;', '&#x1f91a;&#x1f3fc;', '&#x1f91a;&#x1f3fd;', '&#x1f91a;&#x1f3fe;', '&#x1f91a;&#x1f3ff;', '&#x1f91b;&#x1f3fb;', '&#x1f91b;&#x1f3fc;', '&#x1f91b;&#x1f3fd;', '&#x1f91b;&#x1f3fe;', '&#x1f91b;&#x1f3ff;', '&#x1f91c;&#x1f3fb;', '&#x1f91c;&#x1f3fc;', '&#x1f91c;&#x1f3fd;', '&#x1f91c;&#x1f3fe;', '&#x1f91c;&#x1f3ff;', '&#x1f91e;&#x1f3fb;', '&#x1f91e;&#x1f3fc;', '&#x1f91e;&#x1f3fd;', '&#x1f91e;&#x1f3fe;', '&#x1f91e;&#x1f3ff;', '&#x1f91f;&#x1f3fb;', '&#x1f91f;&#x1f3fc;', '&#x1f91f;&#x1f3fd;', '&#x1f91f;&#x1f3fe;', '&#x1f91f;&#x1f3ff;', '&#x1f926;&#x1f3fb;', '&#x1f926;&#x1f3fc;', '&#x1f926;&#x1f3fd;', '&#x1f926;&#x1f3fe;', '&#x1f926;&#x1f3ff;', '&#x1f930;&#x1f3fb;', '&#x1f930;&#x1f3fc;', '&#x1f930;&#x1f3fd;', '&#x1f930;&#x1f3fe;', '&#x1f930;&#x1f3ff;', '&#x1f931;&#x1f3fb;', '&#x1f931;&#x1f3fc;', '&#x1f931;&#x1f3fd;', '&#x1f931;&#x1f3fe;', '&#x1f931;&#x1f3ff;', '&#x1f932;&#x1f3fb;', '&#x1f932;&#x1f3fc;', '&#x1f932;&#x1f3fd;', '&#x1f932;&#x1f3fe;', '&#x1f932;&#x1f3ff;', '&#x1f933;&#x1f3fb;', '&#x1f933;&#x1f3fc;', '&#x1f933;&#x1f3fd;', '&#x1f933;&#x1f3fe;', '&#x1f933;&#x1f3ff;', '&#x1f934;&#x1f3fb;', '&#x1f934;&#x1f3fc;', '&#x1f934;&#x1f3fd;', '&#x1f934;&#x1f3fe;', '&#x1f934;&#x1f3ff;', '&#x1f935;&#x1f3fb;', '&#x1f935;&#x1f3fc;', '&#x1f935;&#x1f3fd;', '&#x1f935;&#x1f3fe;', '&#x1f935;&#x1f3ff;', '&#x1f936;&#x1f3fb;', '&#x1f936;&#x1f3fc;', '&#x1f936;&#x1f3fd;', '&#x1f936;&#x1f3fe;', '&#x1f936;&#x1f3ff;', '&#x1f937;&#x1f3fb;', '&#x1f937;&#x1f3fc;', '&#x1f937;&#x1f3fd;', '&#x1f937;&#x1f3fe;', '&#x1f937;&#x1f3ff;', '&#x1f938;&#x1f3fb;', '&#x1f938;&#x1f3fc;', '&#x1f938;&#x1f3fd;', '&#x1f938;&#x1f3fe;', '&#x1f938;&#x1f3ff;', '&#x1f939;&#x1f3fb;', '&#x1f939;&#x1f3fc;', '&#x1f939;&#x1f3fd;', '&#x1f939;&#x1f3fe;', '&#x1f939;&#x1f3ff;', '&#x1f93d;&#x1f3fb;', '&#x1f93d;&#x1f3fc;', '&#x1f93d;&#x1f3fd;', '&#x1f93d;&#x1f3fe;', '&#x1f93d;&#x1f3ff;', '&#x1f93e;&#x1f3fb;', '&#x1f93e;&#x1f3fc;', '&#x1f93e;&#x1f3fd;', '&#x1f93e;&#x1f3fe;', '&#x1f93e;&#x1f3ff;', '&#x1f977;&#x1f3fb;', '&#x1f977;&#x1f3fc;', '&#x1f977;&#x1f3fd;', '&#x1f977;&#x1f3fe;', '&#x1f977;&#x1f3ff;', '&#x1f9b5;&#x1f3fb;', '&#x1f9b5;&#x1f3fc;', '&#x1f9b5;&#x1f3fd;', '&#x1f9b5;&#x1f3fe;', '&#x1f9b5;&#x1f3ff;', '&#x1f9b6;&#x1f3fb;', '&#x1f9b6;&#x1f3fc;', '&#x1f9b6;&#x1f3fd;', '&#x1f9b6;&#x1f3fe;', '&#x1f9b6;&#x1f3ff;', '&#x1f9b8;&#x1f3fb;', '&#x1f9b8;&#x1f3fc;', '&#x1f9b8;&#x1f3fd;', '&#x1f9b8;&#x1f3fe;', '&#x1f9b8;&#x1f3ff;', '&#x1f9b9;&#x1f3fb;', '&#x1f9b9;&#x1f3fc;', '&#x1f9b9;&#x1f3fd;', '&#x1f9b9;&#x1f3fe;', '&#x1f9b9;&#x1f3ff;', '&#x1f9bb;&#x1f3fb;', '&#x1f9bb;&#x1f3fc;', '&#x1f9bb;&#x1f3fd;', '&#x1f9bb;&#x1f3fe;', '&#x1f9bb;&#x1f3ff;', '&#x1f9cd;&#x1f3fb;', '&#x1f9cd;&#x1f3fc;', '&#x1f9cd;&#x1f3fd;', '&#x1f9cd;&#x1f3fe;', '&#x1f9cd;&#x1f3ff;', '&#x1f9ce;&#x1f3fb;', '&#x1f9ce;&#x1f3fc;', '&#x1f9ce;&#x1f3fd;', '&#x1f9ce;&#x1f3fe;', '&#x1f9ce;&#x1f3ff;', '&#x1f9cf;&#x1f3fb;', '&#x1f9cf;&#x1f3fc;', '&#x1f9cf;&#x1f3fd;', '&#x1f9cf;&#x1f3fe;', '&#x1f9cf;&#x1f3ff;', '&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3ff;', '&#x1f9d2;&#x1f3fb;', '&#x1f9d2;&#x1f3fc;', '&#x1f9d2;&#x1f3fd;', '&#x1f9d2;&#x1f3fe;', '&#x1f9d2;&#x1f3ff;', '&#x1f9d3;&#x1f3fb;', '&#x1f9d3;&#x1f3fc;', '&#x1f9d3;&#x1f3fd;', '&#x1f9d3;&#x1f3fe;', '&#x1f9d3;&#x1f3ff;', '&#x1f9d4;&#x1f3fb;', '&#x1f9d4;&#x1f3fc;', '&#x1f9d4;&#x1f3fd;', '&#x1f9d4;&#x1f3fe;', '&#x1f9d4;&#x1f3ff;', '&#x1f9d5;&#x1f3fb;', '&#x1f9d5;&#x1f3fc;', '&#x1f9d5;&#x1f3fd;', '&#x1f9d5;&#x1f3fe;', '&#x1f9d5;&#x1f3ff;', '&#x1f9d6;&#x1f3fb;', '&#x1f9d6;&#x1f3fc;', '&#x1f9d6;&#x1f3fd;', '&#x1f9d6;&#x1f3fe;', '&#x1f9d6;&#x1f3ff;', '&#x1f9d7;&#x1f3fb;', '&#x1f9d7;&#x1f3fc;', '&#x1f9d7;&#x1f3fd;', '&#x1f9d7;&#x1f3fe;', '&#x1f9d7;&#x1f3ff;', '&#x1f9d8;&#x1f3fb;', '&#x1f9d8;&#x1f3fc;', '&#x1f9d8;&#x1f3fd;', '&#x1f9d8;&#x1f3fe;', '&#x1f9d8;&#x1f3ff;', '&#x1f9d9;&#x1f3fb;', '&#x1f9d9;&#x1f3fc;', '&#x1f9d9;&#x1f3fd;', '&#x1f9d9;&#x1f3fe;', '&#x1f9d9;&#x1f3ff;', '&#x1f9da;&#x1f3fb;', '&#x1f9da;&#x1f3fc;', '&#x1f9da;&#x1f3fd;', '&#x1f9da;&#x1f3fe;', '&#x1f9da;&#x1f3ff;', '&#x1f9db;&#x1f3fb;', '&#x1f9db;&#x1f3fc;', '&#x1f9db;&#x1f3fd;', '&#x1f9db;&#x1f3fe;', '&#x1f9db;&#x1f3ff;', '&#x1f9dc;&#x1f3fb;', '&#x1f9dc;&#x1f3fc;', '&#x1f9dc;&#x1f3fd;', '&#x1f9dc;&#x1f3fe;', '&#x1f9dc;&#x1f3ff;', '&#x1f9dd;&#x1f3fb;', '&#x1f9dd;&#x1f3fc;', '&#x1f9dd;&#x1f3fd;', '&#x1f9dd;&#x1f3fe;', '&#x1f9dd;&#x1f3ff;', '&#x261d;&#x1f3fb;', '&#x261d;&#x1f3fc;', '&#x261d;&#x1f3fd;', '&#x261d;&#x1f3fe;', '&#x261d;&#x1f3ff;', '&#x26f7;&#x1f3fb;', '&#x26f7;&#x1f3fc;', '&#x26f7;&#x1f3fd;', '&#x26f7;&#x1f3fe;', '&#x26f7;&#x1f3ff;', '&#x26f9;&#x1f3fb;', '&#x26f9;&#x1f3fc;', '&#x26f9;&#x1f3fd;', '&#x26f9;&#x1f3fe;', '&#x26f9;&#x1f3ff;', '&#x270a;&#x1f3fb;', '&#x270a;&#x1f3fc;', '&#x270a;&#x1f3fd;', '&#x270a;&#x1f3fe;', '&#x270a;&#x1f3ff;', '&#x270b;&#x1f3fb;', '&#x270b;&#x1f3fc;', '&#x270b;&#x1f3fd;', '&#x270b;&#x1f3fe;', '&#x270b;&#x1f3ff;', '&#x270c;&#x1f3fb;', '&#x270c;&#x1f3fc;', '&#x270c;&#x1f3fd;', '&#x270c;&#x1f3fe;', '&#x270c;&#x1f3ff;', '&#x270d;&#x1f3fb;', '&#x270d;&#x1f3fc;', '&#x270d;&#x1f3fd;', '&#x270d;&#x1f3fe;', '&#x270d;&#x1f3ff;', '&#x23;&#x20e3;', '&#x2a;&#x20e3;', '&#x30;&#x20e3;', '&#x31;&#x20e3;', '&#x32;&#x20e3;', '&#x33;&#x20e3;', '&#x34;&#x20e3;', '&#x35;&#x20e3;', '&#x36;&#x20e3;', '&#x37;&#x20e3;', '&#x38;&#x20e3;', '&#x39;&#x20e3;', '&#x1f004;', '&#x1f0cf;', '&#x1f170;', '&#x1f171;', '&#x1f17e;', '&#x1f17f;', '&#x1f18e;', '&#x1f191;', '&#x1f192;', '&#x1f193;', '&#x1f194;', '&#x1f195;', '&#x1f196;', '&#x1f197;', '&#x1f198;', '&#x1f199;', '&#x1f19a;', '&#x1f1e6;', '&#x1f1e7;', '&#x1f1e8;', '&#x1f1e9;', '&#x1f1ea;', '&#x1f1eb;', '&#x1f1ec;', '&#x1f1ed;', '&#x1f1ee;', '&#x1f1ef;', '&#x1f1f0;', '&#x1f1f1;', '&#x1f1f2;', '&#x1f1f3;', '&#x1f1f4;', '&#x1f1f5;', '&#x1f1f6;', '&#x1f1f7;', '&#x1f1f8;', '&#x1f1f9;', '&#x1f1fa;', '&#x1f1fb;', '&#x1f1fc;', '&#x1f1fd;', '&#x1f1fe;', '&#x1f1ff;', '&#x1f201;', '&#x1f202;', '&#x1f21a;', '&#x1f22f;', '&#x1f232;', '&#x1f233;', '&#x1f234;', '&#x1f235;', '&#x1f236;', '&#x1f237;', '&#x1f238;', '&#x1f239;', '&#x1f23a;', '&#x1f250;', '&#x1f251;', '&#x1f300;', '&#x1f301;', '&#x1f302;', '&#x1f303;', '&#x1f304;', '&#x1f305;', '&#x1f306;', '&#x1f307;', '&#x1f308;', '&#x1f309;', '&#x1f30a;', '&#x1f30b;', '&#x1f30c;', '&#x1f30d;', '&#x1f30e;', '&#x1f30f;', '&#x1f310;', '&#x1f311;', '&#x1f312;', '&#x1f313;', '&#x1f314;', '&#x1f315;', '&#x1f316;', '&#x1f317;', '&#x1f318;', '&#x1f319;', '&#x1f31a;', '&#x1f31b;', '&#x1f31c;', '&#x1f31d;', '&#x1f31e;', '&#x1f31f;', '&#x1f320;', '&#x1f321;', '&#x1f324;', '&#x1f325;', '&#x1f326;', '&#x1f327;', '&#x1f328;', '&#x1f329;', '&#x1f32a;', '&#x1f32b;', '&#x1f32c;', '&#x1f32d;', '&#x1f32e;', '&#x1f32f;', '&#x1f330;', '&#x1f331;', '&#x1f332;', '&#x1f333;', '&#x1f334;', '&#x1f335;', '&#x1f336;', '&#x1f337;', '&#x1f338;', '&#x1f339;', '&#x1f33a;', '&#x1f33b;', '&#x1f33c;', '&#x1f33d;', '&#x1f33e;', '&#x1f33f;', '&#x1f340;', '&#x1f341;', '&#x1f342;', '&#x1f343;', '&#x1f344;', '&#x1f345;', '&#x1f346;', '&#x1f347;', '&#x1f348;', '&#x1f349;', '&#x1f34a;', '&#x1f34b;', '&#x1f34c;', '&#x1f34d;', '&#x1f34e;', '&#x1f34f;', '&#x1f350;', '&#x1f351;', '&#x1f352;', '&#x1f353;', '&#x1f354;', '&#x1f355;', '&#x1f356;', '&#x1f357;', '&#x1f358;', '&#x1f359;', '&#x1f35a;', '&#x1f35b;', '&#x1f35c;', '&#x1f35d;', '&#x1f35e;', '&#x1f35f;', '&#x1f360;', '&#x1f361;', '&#x1f362;', '&#x1f363;', '&#x1f364;', '&#x1f365;', '&#x1f366;', '&#x1f367;', '&#x1f368;', '&#x1f369;', '&#x1f36a;', '&#x1f36b;', '&#x1f36c;', '&#x1f36d;', '&#x1f36e;', '&#x1f36f;', '&#x1f370;', '&#x1f371;', '&#x1f372;', '&#x1f373;', '&#x1f374;', '&#x1f375;', '&#x1f376;', '&#x1f377;', '&#x1f378;', '&#x1f379;', '&#x1f37a;', '&#x1f37b;', '&#x1f37c;', '&#x1f37d;', '&#x1f37e;', '&#x1f37f;', '&#x1f380;', '&#x1f381;', '&#x1f382;', '&#x1f383;', '&#x1f384;', '&#x1f385;', '&#x1f386;', '&#x1f387;', '&#x1f388;', '&#x1f389;', '&#x1f38a;', '&#x1f38b;', '&#x1f38c;', '&#x1f38d;', '&#x1f38e;', '&#x1f38f;', '&#x1f390;', '&#x1f391;', '&#x1f392;', '&#x1f393;', '&#x1f396;', '&#x1f397;', '&#x1f399;', '&#x1f39a;', '&#x1f39b;', '&#x1f39e;', '&#x1f39f;', '&#x1f3a0;', '&#x1f3a1;', '&#x1f3a2;', '&#x1f3a3;', '&#x1f3a4;', '&#x1f3a5;', '&#x1f3a6;', '&#x1f3a7;', '&#x1f3a8;', '&#x1f3a9;', '&#x1f3aa;', '&#x1f3ab;', '&#x1f3ac;', '&#x1f3ad;', '&#x1f3ae;', '&#x1f3af;', '&#x1f3b0;', '&#x1f3b1;', '&#x1f3b2;', '&#x1f3b3;', '&#x1f3b4;', '&#x1f3b5;', '&#x1f3b6;', '&#x1f3b7;', '&#x1f3b8;', '&#x1f3b9;', '&#x1f3ba;', '&#x1f3bb;', '&#x1f3bc;', '&#x1f3bd;', '&#x1f3be;', '&#x1f3bf;', '&#x1f3c0;', '&#x1f3c1;', '&#x1f3c2;', '&#x1f3c3;', '&#x1f3c4;', '&#x1f3c5;', '&#x1f3c6;', '&#x1f3c7;', '&#x1f3c8;', '&#x1f3c9;', '&#x1f3ca;', '&#x1f3cb;', '&#x1f3cc;', '&#x1f3cd;', '&#x1f3ce;', '&#x1f3cf;', '&#x1f3d0;', '&#x1f3d1;', '&#x1f3d2;', '&#x1f3d3;', '&#x1f3d4;', '&#x1f3d5;', '&#x1f3d6;', '&#x1f3d7;', '&#x1f3d8;', '&#x1f3d9;', '&#x1f3da;', '&#x1f3db;', '&#x1f3dc;', '&#x1f3dd;', '&#x1f3de;', '&#x1f3df;', '&#x1f3e0;', '&#x1f3e1;', '&#x1f3e2;', '&#x1f3e3;', '&#x1f3e4;', '&#x1f3e5;', '&#x1f3e6;', '&#x1f3e7;', '&#x1f3e8;', '&#x1f3e9;', '&#x1f3ea;', '&#x1f3eb;', '&#x1f3ec;', '&#x1f3ed;', '&#x1f3ee;', '&#x1f3ef;', '&#x1f3f0;', '&#x1f3f3;', '&#x1f3f4;', '&#x1f3f5;', '&#x1f3f7;', '&#x1f3f8;', '&#x1f3f9;', '&#x1f3fa;', '&#x1f3fb;', '&#x1f3fc;', '&#x1f3fd;', '&#x1f3fe;', '&#x1f3ff;', '&#x1f400;', '&#x1f401;', '&#x1f402;', '&#x1f403;', '&#x1f404;', '&#x1f405;', '&#x1f406;', '&#x1f407;', '&#x1f408;', '&#x1f409;', '&#x1f40a;', '&#x1f40b;', '&#x1f40c;', '&#x1f40d;', '&#x1f40e;', '&#x1f40f;', '&#x1f410;', '&#x1f411;', '&#x1f412;', '&#x1f413;', '&#x1f414;', '&#x1f415;', '&#x1f416;', '&#x1f417;', '&#x1f418;', '&#x1f419;', '&#x1f41a;', '&#x1f41b;', '&#x1f41c;', '&#x1f41d;', '&#x1f41e;', '&#x1f41f;', '&#x1f420;', '&#x1f421;', '&#x1f422;', '&#x1f423;', '&#x1f424;', '&#x1f425;', '&#x1f426;', '&#x1f427;', '&#x1f428;', '&#x1f429;', '&#x1f42a;', '&#x1f42b;', '&#x1f42c;', '&#x1f42d;', '&#x1f42e;', '&#x1f42f;', '&#x1f430;', '&#x1f431;', '&#x1f432;', '&#x1f433;', '&#x1f434;', '&#x1f435;', '&#x1f436;', '&#x1f437;', '&#x1f438;', '&#x1f439;', '&#x1f43a;', '&#x1f43b;', '&#x1f43c;', '&#x1f43d;', '&#x1f43e;', '&#x1f43f;', '&#x1f440;', '&#x1f441;', '&#x1f442;', '&#x1f443;', '&#x1f444;', '&#x1f445;', '&#x1f446;', '&#x1f447;', '&#x1f448;', '&#x1f449;', '&#x1f44a;', '&#x1f44b;', '&#x1f44c;', '&#x1f44d;', '&#x1f44e;', '&#x1f44f;', '&#x1f450;', '&#x1f451;', '&#x1f452;', '&#x1f453;', '&#x1f454;', '&#x1f455;', '&#x1f456;', '&#x1f457;', '&#x1f458;', '&#x1f459;', '&#x1f45a;', '&#x1f45b;', '&#x1f45c;', '&#x1f45d;', '&#x1f45e;', '&#x1f45f;', '&#x1f460;', '&#x1f461;', '&#x1f462;', '&#x1f463;', '&#x1f464;', '&#x1f465;', '&#x1f466;', '&#x1f467;', '&#x1f468;', '&#x1f469;', '&#x1f46a;', '&#x1f46b;', '&#x1f46c;', '&#x1f46d;', '&#x1f46e;', '&#x1f46f;', '&#x1f470;', '&#x1f471;', '&#x1f472;', '&#x1f473;', '&#x1f474;', '&#x1f475;', '&#x1f476;', '&#x1f477;', '&#x1f478;', '&#x1f479;', '&#x1f47a;', '&#x1f47b;', '&#x1f47c;', '&#x1f47d;', '&#x1f47e;', '&#x1f47f;', '&#x1f480;', '&#x1f481;', '&#x1f482;', '&#x1f483;', '&#x1f484;', '&#x1f485;', '&#x1f486;', '&#x1f487;', '&#x1f488;', '&#x1f489;', '&#x1f48a;', '&#x1f48b;', '&#x1f48c;', '&#x1f48d;', '&#x1f48e;', '&#x1f48f;', '&#x1f490;', '&#x1f491;', '&#x1f492;', '&#x1f493;', '&#x1f494;', '&#x1f495;', '&#x1f496;', '&#x1f497;', '&#x1f498;', '&#x1f499;', '&#x1f49a;', '&#x1f49b;', '&#x1f49c;', '&#x1f49d;', '&#x1f49e;', '&#x1f49f;', '&#x1f4a0;', '&#x1f4a1;', '&#x1f4a2;', '&#x1f4a3;', '&#x1f4a4;', '&#x1f4a5;', '&#x1f4a6;', '&#x1f4a7;', '&#x1f4a8;', '&#x1f4a9;', '&#x1f4aa;', '&#x1f4ab;', '&#x1f4ac;', '&#x1f4ad;', '&#x1f4ae;', '&#x1f4af;', '&#x1f4b0;', '&#x1f4b1;', '&#x1f4b2;', '&#x1f4b3;', '&#x1f4b4;', '&#x1f4b5;', '&#x1f4b6;', '&#x1f4b7;', '&#x1f4b8;', '&#x1f4b9;', '&#x1f4ba;', '&#x1f4bb;', '&#x1f4bc;', '&#x1f4bd;', '&#x1f4be;', '&#x1f4bf;', '&#x1f4c0;', '&#x1f4c1;', '&#x1f4c2;', '&#x1f4c3;', '&#x1f4c4;', '&#x1f4c5;', '&#x1f4c6;', '&#x1f4c7;', '&#x1f4c8;', '&#x1f4c9;', '&#x1f4ca;', '&#x1f4cb;', '&#x1f4cc;', '&#x1f4cd;', '&#x1f4ce;', '&#x1f4cf;', '&#x1f4d0;', '&#x1f4d1;', '&#x1f4d2;', '&#x1f4d3;', '&#x1f4d4;', '&#x1f4d5;', '&#x1f4d6;', '&#x1f4d7;', '&#x1f4d8;', '&#x1f4d9;', '&#x1f4da;', '&#x1f4db;', '&#x1f4dc;', '&#x1f4dd;', '&#x1f4de;', '&#x1f4df;', '&#x1f4e0;', '&#x1f4e1;', '&#x1f4e2;', '&#x1f4e3;', '&#x1f4e4;', '&#x1f4e5;', '&#x1f4e6;', '&#x1f4e7;', '&#x1f4e8;', '&#x1f4e9;', '&#x1f4ea;', '&#x1f4eb;', '&#x1f4ec;', '&#x1f4ed;', '&#x1f4ee;', '&#x1f4ef;', '&#x1f4f0;', '&#x1f4f1;', '&#x1f4f2;', '&#x1f4f3;', '&#x1f4f4;', '&#x1f4f5;', '&#x1f4f6;', '&#x1f4f7;', '&#x1f4f8;', '&#x1f4f9;', '&#x1f4fa;', '&#x1f4fb;', '&#x1f4fc;', '&#x1f4fd;', '&#x1f4ff;', '&#x1f500;', '&#x1f501;', '&#x1f502;', '&#x1f503;', '&#x1f504;', '&#x1f505;', '&#x1f506;', '&#x1f507;', '&#x1f508;', '&#x1f509;', '&#x1f50a;', '&#x1f50b;', '&#x1f50c;', '&#x1f50d;', '&#x1f50e;', '&#x1f50f;', '&#x1f510;', '&#x1f511;', '&#x1f512;', '&#x1f513;', '&#x1f514;', '&#x1f515;', '&#x1f516;', '&#x1f517;', '&#x1f518;', '&#x1f519;', '&#x1f51a;', '&#x1f51b;', '&#x1f51c;', '&#x1f51d;', '&#x1f51e;', '&#x1f51f;', '&#x1f520;', '&#x1f521;', '&#x1f522;', '&#x1f523;', '&#x1f524;', '&#x1f525;', '&#x1f526;', '&#x1f527;', '&#x1f528;', '&#x1f529;', '&#x1f52a;', '&#x1f52b;', '&#x1f52c;', '&#x1f52d;', '&#x1f52e;', '&#x1f52f;', '&#x1f530;', '&#x1f531;', '&#x1f532;', '&#x1f533;', '&#x1f534;', '&#x1f535;', '&#x1f536;', '&#x1f537;', '&#x1f538;', '&#x1f539;', '&#x1f53a;', '&#x1f53b;', '&#x1f53c;', '&#x1f53d;', '&#x1f549;', '&#x1f54a;', '&#x1f54b;', '&#x1f54c;', '&#x1f54d;', '&#x1f54e;', '&#x1f550;', '&#x1f551;', '&#x1f552;', '&#x1f553;', '&#x1f554;', '&#x1f555;', '&#x1f556;', '&#x1f557;', '&#x1f558;', '&#x1f559;', '&#x1f55a;', '&#x1f55b;', '&#x1f55c;', '&#x1f55d;', '&#x1f55e;', '&#x1f55f;', '&#x1f560;', '&#x1f561;', '&#x1f562;', '&#x1f563;', '&#x1f564;', '&#x1f565;', '&#x1f566;', '&#x1f567;', '&#x1f56f;', '&#x1f570;', '&#x1f573;', '&#x1f574;', '&#x1f575;', '&#x1f576;', '&#x1f577;', '&#x1f578;', '&#x1f579;', '&#x1f57a;', '&#x1f587;', '&#x1f58a;', '&#x1f58b;', '&#x1f58c;', '&#x1f58d;', '&#x1f590;', '&#x1f595;', '&#x1f596;', '&#x1f5a4;', '&#x1f5a5;', '&#x1f5a8;', '&#x1f5b1;', '&#x1f5b2;', '&#x1f5bc;', '&#x1f5c2;', '&#x1f5c3;', '&#x1f5c4;', '&#x1f5d1;', '&#x1f5d2;', '&#x1f5d3;', '&#x1f5dc;', '&#x1f5dd;', '&#x1f5de;', '&#x1f5e1;', '&#x1f5e3;', '&#x1f5e8;', '&#x1f5ef;', '&#x1f5f3;', '&#x1f5fa;', '&#x1f5fb;', '&#x1f5fc;', '&#x1f5fd;', '&#x1f5fe;', '&#x1f5ff;', '&#x1f600;', '&#x1f601;', '&#x1f602;', '&#x1f603;', '&#x1f604;', '&#x1f605;', '&#x1f606;', '&#x1f607;', '&#x1f608;', '&#x1f609;', '&#x1f60a;', '&#x1f60b;', '&#x1f60c;', '&#x1f60d;', '&#x1f60e;', '&#x1f60f;', '&#x1f610;', '&#x1f611;', '&#x1f612;', '&#x1f613;', '&#x1f614;', '&#x1f615;', '&#x1f616;', '&#x1f617;', '&#x1f618;', '&#x1f619;', '&#x1f61a;', '&#x1f61b;', '&#x1f61c;', '&#x1f61d;', '&#x1f61e;', '&#x1f61f;', '&#x1f620;', '&#x1f621;', '&#x1f622;', '&#x1f623;', '&#x1f624;', '&#x1f625;', '&#x1f626;', '&#x1f627;', '&#x1f628;', '&#x1f629;', '&#x1f62a;', '&#x1f62b;', '&#x1f62c;', '&#x1f62d;', '&#x1f62e;', '&#x1f62f;', '&#x1f630;', '&#x1f631;', '&#x1f632;', '&#x1f633;', '&#x1f634;', '&#x1f635;', '&#x1f636;', '&#x1f637;', '&#x1f638;', '&#x1f639;', '&#x1f63a;', '&#x1f63b;', '&#x1f63c;', '&#x1f63d;', '&#x1f63e;', '&#x1f63f;', '&#x1f640;', '&#x1f641;', '&#x1f642;', '&#x1f643;', '&#x1f644;', '&#x1f645;', '&#x1f646;', '&#x1f647;', '&#x1f648;', '&#x1f649;', '&#x1f64a;', '&#x1f64b;', '&#x1f64c;', '&#x1f64d;', '&#x1f64e;', '&#x1f64f;', '&#x1f680;', '&#x1f681;', '&#x1f682;', '&#x1f683;', '&#x1f684;', '&#x1f685;', '&#x1f686;', '&#x1f687;', '&#x1f688;', '&#x1f689;', '&#x1f68a;', '&#x1f68b;', '&#x1f68c;', '&#x1f68d;', '&#x1f68e;', '&#x1f68f;', '&#x1f690;', '&#x1f691;', '&#x1f692;', '&#x1f693;', '&#x1f694;', '&#x1f695;', '&#x1f696;', '&#x1f697;', '&#x1f698;', '&#x1f699;', '&#x1f69a;', '&#x1f69b;', '&#x1f69c;', '&#x1f69d;', '&#x1f69e;', '&#x1f69f;', '&#x1f6a0;', '&#x1f6a1;', '&#x1f6a2;', '&#x1f6a3;', '&#x1f6a4;', '&#x1f6a5;', '&#x1f6a6;', '&#x1f6a7;', '&#x1f6a8;', '&#x1f6a9;', '&#x1f6aa;', '&#x1f6ab;', '&#x1f6ac;', '&#x1f6ad;', '&#x1f6ae;', '&#x1f6af;', '&#x1f6b0;', '&#x1f6b1;', '&#x1f6b2;', '&#x1f6b3;', '&#x1f6b4;', '&#x1f6b5;', '&#x1f6b6;', '&#x1f6b7;', '&#x1f6b8;', '&#x1f6b9;', '&#x1f6ba;', '&#x1f6bb;', '&#x1f6bc;', '&#x1f6bd;', '&#x1f6be;', '&#x1f6bf;', '&#x1f6c0;', '&#x1f6c1;', '&#x1f6c2;', '&#x1f6c3;', '&#x1f6c4;', '&#x1f6c5;', '&#x1f6cb;', '&#x1f6cc;', '&#x1f6cd;', '&#x1f6ce;', '&#x1f6cf;', '&#x1f6d0;', '&#x1f6d1;', '&#x1f6d2;', '&#x1f6d5;', '&#x1f6d6;', '&#x1f6d7;', '&#x1f6e0;', '&#x1f6e1;', '&#x1f6e2;', '&#x1f6e3;', '&#x1f6e4;', '&#x1f6e5;', '&#x1f6e9;', '&#x1f6eb;', '&#x1f6ec;', '&#x1f6f0;', '&#x1f6f3;', '&#x1f6f4;', '&#x1f6f5;', '&#x1f6f6;', '&#x1f6f7;', '&#x1f6f8;', '&#x1f6f9;', '&#x1f6fa;', '&#x1f6fb;', '&#x1f6fc;', '&#x1f7e0;', '&#x1f7e1;', '&#x1f7e2;', '&#x1f7e3;', '&#x1f7e4;', '&#x1f7e5;', '&#x1f7e6;', '&#x1f7e7;', '&#x1f7e8;', '&#x1f7e9;', '&#x1f7ea;', '&#x1f7eb;', '&#x1f90c;', '&#x1f90d;', '&#x1f90e;', '&#x1f90f;', '&#x1f910;', '&#x1f911;', '&#x1f912;', '&#x1f913;', '&#x1f914;', '&#x1f915;', '&#x1f916;', '&#x1f917;', '&#x1f918;', '&#x1f919;', '&#x1f91a;', '&#x1f91b;', '&#x1f91c;', '&#x1f91d;', '&#x1f91e;', '&#x1f91f;', '&#x1f920;', '&#x1f921;', '&#x1f922;', '&#x1f923;', '&#x1f924;', '&#x1f925;', '&#x1f926;', '&#x1f927;', '&#x1f928;', '&#x1f929;', '&#x1f92a;', '&#x1f92b;', '&#x1f92c;', '&#x1f92d;', '&#x1f92e;', '&#x1f92f;', '&#x1f930;', '&#x1f931;', '&#x1f932;', '&#x1f933;', '&#x1f934;', '&#x1f935;', '&#x1f936;', '&#x1f937;', '&#x1f938;', '&#x1f939;', '&#x1f93a;', '&#x1f93c;', '&#x1f93d;', '&#x1f93e;', '&#x1f93f;', '&#x1f940;', '&#x1f941;', '&#x1f942;', '&#x1f943;', '&#x1f944;', '&#x1f945;', '&#x1f947;', '&#x1f948;', '&#x1f949;', '&#x1f94a;', '&#x1f94b;', '&#x1f94c;', '&#x1f94d;', '&#x1f94e;', '&#x1f94f;', '&#x1f950;', '&#x1f951;', '&#x1f952;', '&#x1f953;', '&#x1f954;', '&#x1f955;', '&#x1f956;', '&#x1f957;', '&#x1f958;', '&#x1f959;', '&#x1f95a;', '&#x1f95b;', '&#x1f95c;', '&#x1f95d;', '&#x1f95e;', '&#x1f95f;', '&#x1f960;', '&#x1f961;', '&#x1f962;', '&#x1f963;', '&#x1f964;', '&#x1f965;', '&#x1f966;', '&#x1f967;', '&#x1f968;', '&#x1f969;', '&#x1f96a;', '&#x1f96b;', '&#x1f96c;', '&#x1f96d;', '&#x1f96e;', '&#x1f96f;', '&#x1f970;', '&#x1f971;', '&#x1f972;', '&#x1f973;', '&#x1f974;', '&#x1f975;', '&#x1f976;', '&#x1f977;', '&#x1f978;', '&#x1f97a;', '&#x1f97b;', '&#x1f97c;', '&#x1f97d;', '&#x1f97e;', '&#x1f97f;', '&#x1f980;', '&#x1f981;', '&#x1f982;', '&#x1f983;', '&#x1f984;', '&#x1f985;', '&#x1f986;', '&#x1f987;', '&#x1f988;', '&#x1f989;', '&#x1f98a;', '&#x1f98b;', '&#x1f98c;', '&#x1f98d;', '&#x1f98e;', '&#x1f98f;', '&#x1f990;', '&#x1f991;', '&#x1f992;', '&#x1f993;', '&#x1f994;', '&#x1f995;', '&#x1f996;', '&#x1f997;', '&#x1f998;', '&#x1f999;', '&#x1f99a;', '&#x1f99b;', '&#x1f99c;', '&#x1f99d;', '&#x1f99e;', '&#x1f99f;', '&#x1f9a0;', '&#x1f9a1;', '&#x1f9a2;', '&#x1f9a3;', '&#x1f9a4;', '&#x1f9a5;', '&#x1f9a6;', '&#x1f9a7;', '&#x1f9a8;', '&#x1f9a9;', '&#x1f9aa;', '&#x1f9ab;', '&#x1f9ac;', '&#x1f9ad;', '&#x1f9ae;', '&#x1f9af;', '&#x1f9b0;', '&#x1f9b1;', '&#x1f9b2;', '&#x1f9b3;', '&#x1f9b4;', '&#x1f9b5;', '&#x1f9b6;', '&#x1f9b7;', '&#x1f9b8;', '&#x1f9b9;', '&#x1f9ba;', '&#x1f9bb;', '&#x1f9bc;', '&#x1f9bd;', '&#x1f9be;', '&#x1f9bf;', '&#x1f9c0;', '&#x1f9c1;', '&#x1f9c2;', '&#x1f9c3;', '&#x1f9c4;', '&#x1f9c5;', '&#x1f9c6;', '&#x1f9c7;', '&#x1f9c8;', '&#x1f9c9;', '&#x1f9ca;', '&#x1f9cb;', '&#x1f9cd;', '&#x1f9ce;', '&#x1f9cf;', '&#x1f9d0;', '&#x1f9d1;', '&#x1f9d2;', '&#x1f9d3;', '&#x1f9d4;', '&#x1f9d5;', '&#x1f9d6;', '&#x1f9d7;', '&#x1f9d8;', '&#x1f9d9;', '&#x1f9da;', '&#x1f9db;', '&#x1f9dc;', '&#x1f9dd;', '&#x1f9de;', '&#x1f9df;', '&#x1f9e0;', '&#x1f9e1;', '&#x1f9e2;', '&#x1f9e3;', '&#x1f9e4;', '&#x1f9e5;', '&#x1f9e6;', '&#x1f9e7;', '&#x1f9e8;', '&#x1f9e9;', '&#x1f9ea;', '&#x1f9eb;', '&#x1f9ec;', '&#x1f9ed;', '&#x1f9ee;', '&#x1f9ef;', '&#x1f9f0;', '&#x1f9f1;', '&#x1f9f2;', '&#x1f9f3;', '&#x1f9f4;', '&#x1f9f5;', '&#x1f9f6;', '&#x1f9f7;', '&#x1f9f8;', '&#x1f9f9;', '&#x1f9fa;', '&#x1f9fb;', '&#x1f9fc;', '&#x1f9fd;', '&#x1f9fe;', '&#x1f9ff;', '&#x1fa70;', '&#x1fa71;', '&#x1fa72;', '&#x1fa73;', '&#x1fa74;', '&#x1fa78;', '&#x1fa79;', '&#x1fa7a;', '&#x1fa80;', '&#x1fa81;', '&#x1fa82;', '&#x1fa83;', '&#x1fa84;', '&#x1fa85;', '&#x1fa86;', '&#x1fa90;', '&#x1fa91;', '&#x1fa92;', '&#x1fa93;', '&#x1fa94;', '&#x1fa95;', '&#x1fa96;', '&#x1fa97;', '&#x1fa98;', '&#x1fa99;', '&#x1fa9a;', '&#x1fa9b;', '&#x1fa9c;', '&#x1fa9d;', '&#x1fa9e;', '&#x1fa9f;', '&#x1faa0;', '&#x1faa1;', '&#x1faa2;', '&#x1faa3;', '&#x1faa4;', '&#x1faa5;', '&#x1faa6;', '&#x1faa7;', '&#x1faa8;', '&#x1fab0;', '&#x1fab1;', '&#x1fab2;', '&#x1fab3;', '&#x1fab4;', '&#x1fab5;', '&#x1fab6;', '&#x1fac0;', '&#x1fac1;', '&#x1fac2;', '&#x1fad0;', '&#x1fad1;', '&#x1fad2;', '&#x1fad3;', '&#x1fad4;', '&#x1fad5;', '&#x1fad6;', '&#x203c;', '&#x2049;', '&#x2122;', '&#x2139;', '&#x2194;', '&#x2195;', '&#x2196;', '&#x2197;', '&#x2198;', '&#x2199;', '&#x21a9;', '&#x21aa;', '&#x231a;', '&#x231b;', '&#x2328;', '&#x23cf;', '&#x23e9;', '&#x23ea;', '&#x23eb;', '&#x23ec;', '&#x23ed;', '&#x23ee;', '&#x23ef;', '&#x23f0;', '&#x23f1;', '&#x23f2;', '&#x23f3;', '&#x23f8;', '&#x23f9;', '&#x23fa;', '&#x24c2;', '&#x25aa;', '&#x25ab;', '&#x25b6;', '&#x25c0;', '&#x25fb;', '&#x25fc;', '&#x25fd;', '&#x25fe;', '&#x2600;', '&#x2601;', '&#x2602;', '&#x2603;', '&#x2604;', '&#x260e;', '&#x2611;', '&#x2614;', '&#x2615;', '&#x2618;', '&#x261d;', '&#x2620;', '&#x2622;', '&#x2623;', '&#x2626;', '&#x262a;', '&#x262e;', '&#x262f;', '&#x2638;', '&#x2639;', '&#x263a;', '&#x2640;', '&#x2642;', '&#x2648;', '&#x2649;', '&#x264a;', '&#x264b;', '&#x264c;', '&#x264d;', '&#x264e;', '&#x264f;', '&#x2650;', '&#x2651;', '&#x2652;', '&#x2653;', '&#x265f;', '&#x2660;', '&#x2663;', '&#x2665;', '&#x2666;', '&#x2668;', '&#x267b;', '&#x267e;', '&#x267f;', '&#x2692;', '&#x2693;', '&#x2694;', '&#x2695;', '&#x2696;', '&#x2697;', '&#x2699;', '&#x269b;', '&#x269c;', '&#x26a0;', '&#x26a1;', '&#x26a7;', '&#x26aa;', '&#x26ab;', '&#x26b0;', '&#x26b1;', '&#x26bd;', '&#x26be;', '&#x26c4;', '&#x26c5;', '&#x26c8;', '&#x26ce;', '&#x26cf;', '&#x26d1;', '&#x26d3;', '&#x26d4;', '&#x26e9;', '&#x26ea;', '&#x26f0;', '&#x26f1;', '&#x26f2;', '&#x26f3;', '&#x26f4;', '&#x26f5;', '&#x26f7;', '&#x26f8;', '&#x26f9;', '&#x26fa;', '&#x26fd;', '&#x2702;', '&#x2705;', '&#x2708;', '&#x2709;', '&#x270a;', '&#x270b;', '&#x270c;', '&#x270d;', '&#x270f;', '&#x2712;', '&#x2714;', '&#x2716;', '&#x271d;', '&#x2721;', '&#x2728;', '&#x2733;', '&#x2734;', '&#x2744;', '&#x2747;', '&#x274c;', '&#x274e;', '&#x2753;', '&#x2754;', '&#x2755;', '&#x2757;', '&#x2763;', '&#x2764;', '&#x2795;', '&#x2796;', '&#x2797;', '&#x27a1;', '&#x27b0;', '&#x27bf;', '&#x2934;', '&#x2935;', '&#x2b05;', '&#x2b06;', '&#x2b07;', '&#x2b1b;', '&#x2b1c;', '&#x2b50;', '&#x2b55;', '&#x3030;', '&#x303d;', '&#x3297;', '&#x3299;', '&#xe50a;' );
        $partials = array( '&#x1f004;', '&#x1f0cf;', '&#x1f170;', '&#x1f171;', '&#x1f17e;', '&#x1f17f;', '&#x1f18e;', '&#x1f191;', '&#x1f192;', '&#x1f193;', '&#x1f194;', '&#x1f195;', '&#x1f196;', '&#x1f197;', '&#x1f198;', '&#x1f199;', '&#x1f19a;', '&#x1f1e6;', '&#x1f1e8;', '&#x1f1e9;', '&#x1f1ea;', '&#x1f1eb;', '&#x1f1ec;', '&#x1f1ee;', '&#x1f1f1;', '&#x1f1f2;', '&#x1f1f4;', '&#x1f1f6;', '&#x1f1f7;', '&#x1f1f8;', '&#x1f1f9;', '&#x1f1fa;', '&#x1f1fc;', '&#x1f1fd;', '&#x1f1ff;', '&#x1f1e7;', '&#x1f1ed;', '&#x1f1ef;', '&#x1f1f3;', '&#x1f1fb;', '&#x1f1fe;', '&#x1f1f0;', '&#x1f1f5;', '&#x1f201;', '&#x1f202;', '&#x1f21a;', '&#x1f22f;', '&#x1f232;', '&#x1f233;', '&#x1f234;', '&#x1f235;', '&#x1f236;', '&#x1f237;', '&#x1f238;', '&#x1f239;', '&#x1f23a;', '&#x1f250;', '&#x1f251;', '&#x1f300;', '&#x1f301;', '&#x1f302;', '&#x1f303;', '&#x1f304;', '&#x1f305;', '&#x1f306;', '&#x1f307;', '&#x1f308;', '&#x1f309;', '&#x1f30a;', '&#x1f30b;', '&#x1f30c;', '&#x1f30d;', '&#x1f30e;', '&#x1f30f;', '&#x1f310;', '&#x1f311;', '&#x1f312;', '&#x1f313;', '&#x1f314;', '&#x1f315;', '&#x1f316;', '&#x1f317;', '&#x1f318;', '&#x1f319;', '&#x1f31a;', '&#x1f31b;', '&#x1f31c;', '&#x1f31d;', '&#x1f31e;', '&#x1f31f;', '&#x1f320;', '&#x1f321;', '&#x1f324;', '&#x1f325;', '&#x1f326;', '&#x1f327;', '&#x1f328;', '&#x1f329;', '&#x1f32a;', '&#x1f32b;', '&#x1f32c;', '&#x1f32d;', '&#x1f32e;', '&#x1f32f;', '&#x1f330;', '&#x1f331;', '&#x1f332;', '&#x1f333;', '&#x1f334;', '&#x1f335;', '&#x1f336;', '&#x1f337;', '&#x1f338;', '&#x1f339;', '&#x1f33a;', '&#x1f33b;', '&#x1f33c;', '&#x1f33d;', '&#x1f33e;', '&#x1f33f;', '&#x1f340;', '&#x1f341;', '&#x1f342;', '&#x1f343;', '&#x1f344;', '&#x1f345;', '&#x1f346;', '&#x1f347;', '&#x1f348;', '&#x1f349;', '&#x1f34a;', '&#x1f34b;', '&#x1f34c;', '&#x1f34d;', '&#x1f34e;', '&#x1f34f;', '&#x1f350;', '&#x1f351;', '&#x1f352;', '&#x1f353;', '&#x1f354;', '&#x1f355;', '&#x1f356;', '&#x1f357;', '&#x1f358;', '&#x1f359;', '&#x1f35a;', '&#x1f35b;', '&#x1f35c;', '&#x1f35d;', '&#x1f35e;', '&#x1f35f;', '&#x1f360;', '&#x1f361;', '&#x1f362;', '&#x1f363;', '&#x1f364;', '&#x1f365;', '&#x1f366;', '&#x1f367;', '&#x1f368;', '&#x1f369;', '&#x1f36a;', '&#x1f36b;', '&#x1f36c;', '&#x1f36d;', '&#x1f36e;', '&#x1f36f;', '&#x1f370;', '&#x1f371;', '&#x1f372;', '&#x1f373;', '&#x1f374;', '&#x1f375;', '&#x1f376;', '&#x1f377;', '&#x1f378;', '&#x1f379;', '&#x1f37a;', '&#x1f37b;', '&#x1f37c;', '&#x1f37d;', '&#x1f37e;', '&#x1f37f;', '&#x1f380;', '&#x1f381;', '&#x1f382;', '&#x1f383;', '&#x1f384;', '&#x1f385;', '&#x1f3fb;', '&#x1f3fc;', '&#x1f3fd;', '&#x1f3fe;', '&#x1f3ff;', '&#x1f386;', '&#x1f387;', '&#x1f388;', '&#x1f389;', '&#x1f38a;', '&#x1f38b;', '&#x1f38c;', '&#x1f38d;', '&#x1f38e;', '&#x1f38f;', '&#x1f390;', '&#x1f391;', '&#x1f392;', '&#x1f393;', '&#x1f396;', '&#x1f397;', '&#x1f399;', '&#x1f39a;', '&#x1f39b;', '&#x1f39e;', '&#x1f39f;', '&#x1f3a0;', '&#x1f3a1;', '&#x1f3a2;', '&#x1f3a3;', '&#x1f3a4;', '&#x1f3a5;', '&#x1f3a6;', '&#x1f3a7;', '&#x1f3a8;', '&#x1f3a9;', '&#x1f3aa;', '&#x1f3ab;', '&#x1f3ac;', '&#x1f3ad;', '&#x1f3ae;', '&#x1f3af;', '&#x1f3b0;', '&#x1f3b1;', '&#x1f3b2;', '&#x1f3b3;', '&#x1f3b4;', '&#x1f3b5;', '&#x1f3b6;', '&#x1f3b7;', '&#x1f3b8;', '&#x1f3b9;', '&#x1f3ba;', '&#x1f3bb;', '&#x1f3bc;', '&#x1f3bd;', '&#x1f3be;', '&#x1f3bf;', '&#x1f3c0;', '&#x1f3c1;', '&#x1f3c2;', '&#x1f3c3;', '&#x200d;', '&#x2640;', '&#xfe0f;', '&#x2642;', '&#x1f3c4;', '&#x1f3c5;', '&#x1f3c6;', '&#x1f3c7;', '&#x1f3c8;', '&#x1f3c9;', '&#x1f3ca;', '&#x1f3cb;', '&#x1f3cc;', '&#x1f3cd;', '&#x1f3ce;', '&#x1f3cf;', '&#x1f3d0;', '&#x1f3d1;', '&#x1f3d2;', '&#x1f3d3;', '&#x1f3d4;', '&#x1f3d5;', '&#x1f3d6;', '&#x1f3d7;', '&#x1f3d8;', '&#x1f3d9;', '&#x1f3da;', '&#x1f3db;', '&#x1f3dc;', '&#x1f3dd;', '&#x1f3de;', '&#x1f3df;', '&#x1f3e0;', '&#x1f3e1;', '&#x1f3e2;', '&#x1f3e3;', '&#x1f3e4;', '&#x1f3e5;', '&#x1f3e6;', '&#x1f3e7;', '&#x1f3e8;', '&#x1f3e9;', '&#x1f3ea;', '&#x1f3eb;', '&#x1f3ec;', '&#x1f3ed;', '&#x1f3ee;', '&#x1f3ef;', '&#x1f3f0;', '&#x1f3f3;', '&#x26a7;', '&#x1f3f4;', '&#x2620;', '&#xe0067;', '&#xe0062;', '&#xe0065;', '&#xe006e;', '&#xe007f;', '&#xe0073;', '&#xe0063;', '&#xe0074;', '&#xe0077;', '&#xe006c;', '&#x1f3f5;', '&#x1f3f7;', '&#x1f3f8;', '&#x1f3f9;', '&#x1f3fa;', '&#x1f400;', '&#x1f401;', '&#x1f402;', '&#x1f403;', '&#x1f404;', '&#x1f405;', '&#x1f406;', '&#x1f407;', '&#x1f408;', '&#x2b1b;', '&#x1f409;', '&#x1f40a;', '&#x1f40b;', '&#x1f40c;', '&#x1f40d;', '&#x1f40e;', '&#x1f40f;', '&#x1f410;', '&#x1f411;', '&#x1f412;', '&#x1f413;', '&#x1f414;', '&#x1f415;', '&#x1f9ba;', '&#x1f416;', '&#x1f417;', '&#x1f418;', '&#x1f419;', '&#x1f41a;', '&#x1f41b;', '&#x1f41c;', '&#x1f41d;', '&#x1f41e;', '&#x1f41f;', '&#x1f420;', '&#x1f421;', '&#x1f422;', '&#x1f423;', '&#x1f424;', '&#x1f425;', '&#x1f426;', '&#x1f427;', '&#x1f428;', '&#x1f429;', '&#x1f42a;', '&#x1f42b;', '&#x1f42c;', '&#x1f42d;', '&#x1f42e;', '&#x1f42f;', '&#x1f430;', '&#x1f431;', '&#x1f432;', '&#x1f433;', '&#x1f434;', '&#x1f435;', '&#x1f436;', '&#x1f437;', '&#x1f438;', '&#x1f439;', '&#x1f43a;', '&#x1f43b;', '&#x2744;', '&#x1f43c;', '&#x1f43d;', '&#x1f43e;', '&#x1f43f;', '&#x1f440;', '&#x1f441;', '&#x1f5e8;', '&#x1f442;', '&#x1f443;', '&#x1f444;', '&#x1f445;', '&#x1f446;', '&#x1f447;', '&#x1f448;', '&#x1f449;', '&#x1f44a;', '&#x1f44b;', '&#x1f44c;', '&#x1f44d;', '&#x1f44e;', '&#x1f44f;', '&#x1f450;', '&#x1f451;', '&#x1f452;', '&#x1f453;', '&#x1f454;', '&#x1f455;', '&#x1f456;', '&#x1f457;', '&#x1f458;', '&#x1f459;', '&#x1f45a;', '&#x1f45b;', '&#x1f45c;', '&#x1f45d;', '&#x1f45e;', '&#x1f45f;', '&#x1f460;', '&#x1f461;', '&#x1f462;', '&#x1f463;', '&#x1f464;', '&#x1f465;', '&#x1f466;', '&#x1f467;', '&#x1f468;', '&#x1f4bb;', '&#x1f4bc;', '&#x1f527;', '&#x1f52c;', '&#x1f680;', '&#x1f692;', '&#x1f91d;', '&#x1f9af;', '&#x1f9b0;', '&#x1f9b1;', '&#x1f9b2;', '&#x1f9b3;', '&#x1f9bc;', '&#x1f9bd;', '&#x2695;', '&#x2696;', '&#x2708;', '&#x1f469;', '&#x2764;', '&#x1f48b;', '&#x1f46a;', '&#x1f46b;', '&#x1f46c;', '&#x1f46d;', '&#x1f46e;', '&#x1f46f;', '&#x1f470;', '&#x1f471;', '&#x1f472;', '&#x1f473;', '&#x1f474;', '&#x1f475;', '&#x1f476;', '&#x1f477;', '&#x1f478;', '&#x1f479;', '&#x1f47a;', '&#x1f47b;', '&#x1f47c;', '&#x1f47d;', '&#x1f47e;', '&#x1f47f;', '&#x1f480;', '&#x1f481;', '&#x1f482;', '&#x1f483;', '&#x1f484;', '&#x1f485;', '&#x1f486;', '&#x1f487;', '&#x1f488;', '&#x1f489;', '&#x1f48a;', '&#x1f48c;', '&#x1f48d;', '&#x1f48e;', '&#x1f48f;', '&#x1f490;', '&#x1f491;', '&#x1f492;', '&#x1f493;', '&#x1f494;', '&#x1f495;', '&#x1f496;', '&#x1f497;', '&#x1f498;', '&#x1f499;', '&#x1f49a;', '&#x1f49b;', '&#x1f49c;', '&#x1f49d;', '&#x1f49e;', '&#x1f49f;', '&#x1f4a0;', '&#x1f4a1;', '&#x1f4a2;', '&#x1f4a3;', '&#x1f4a4;', '&#x1f4a5;', '&#x1f4a6;', '&#x1f4a7;', '&#x1f4a8;', '&#x1f4a9;', '&#x1f4aa;', '&#x1f4ab;', '&#x1f4ac;', '&#x1f4ad;', '&#x1f4ae;', '&#x1f4af;', '&#x1f4b0;', '&#x1f4b1;', '&#x1f4b2;', '&#x1f4b3;', '&#x1f4b4;', '&#x1f4b5;', '&#x1f4b6;', '&#x1f4b7;', '&#x1f4b8;', '&#x1f4b9;', '&#x1f4ba;', '&#x1f4bd;', '&#x1f4be;', '&#x1f4bf;', '&#x1f4c0;', '&#x1f4c1;', '&#x1f4c2;', '&#x1f4c3;', '&#x1f4c4;', '&#x1f4c5;', '&#x1f4c6;', '&#x1f4c7;', '&#x1f4c8;', '&#x1f4c9;', '&#x1f4ca;', '&#x1f4cb;', '&#x1f4cc;', '&#x1f4cd;', '&#x1f4ce;', '&#x1f4cf;', '&#x1f4d0;', '&#x1f4d1;', '&#x1f4d2;', '&#x1f4d3;', '&#x1f4d4;', '&#x1f4d5;', '&#x1f4d6;', '&#x1f4d7;', '&#x1f4d8;', '&#x1f4d9;', '&#x1f4da;', '&#x1f4db;', '&#x1f4dc;', '&#x1f4dd;', '&#x1f4de;', '&#x1f4df;', '&#x1f4e0;', '&#x1f4e1;', '&#x1f4e2;', '&#x1f4e3;', '&#x1f4e4;', '&#x1f4e5;', '&#x1f4e6;', '&#x1f4e7;', '&#x1f4e8;', '&#x1f4e9;', '&#x1f4ea;', '&#x1f4eb;', '&#x1f4ec;', '&#x1f4ed;', '&#x1f4ee;', '&#x1f4ef;', '&#x1f4f0;', '&#x1f4f1;', '&#x1f4f2;', '&#x1f4f3;', '&#x1f4f4;', '&#x1f4f5;', '&#x1f4f6;', '&#x1f4f7;', '&#x1f4f8;', '&#x1f4f9;', '&#x1f4fa;', '&#x1f4fb;', '&#x1f4fc;', '&#x1f4fd;', '&#x1f4ff;', '&#x1f500;', '&#x1f501;', '&#x1f502;', '&#x1f503;', '&#x1f504;', '&#x1f505;', '&#x1f506;', '&#x1f507;', '&#x1f508;', '&#x1f509;', '&#x1f50a;', '&#x1f50b;', '&#x1f50c;', '&#x1f50d;', '&#x1f50e;', '&#x1f50f;', '&#x1f510;', '&#x1f511;', '&#x1f512;', '&#x1f513;', '&#x1f514;', '&#x1f515;', '&#x1f516;', '&#x1f517;', '&#x1f518;', '&#x1f519;', '&#x1f51a;', '&#x1f51b;', '&#x1f51c;', '&#x1f51d;', '&#x1f51e;', '&#x1f51f;', '&#x1f520;', '&#x1f521;', '&#x1f522;', '&#x1f523;', '&#x1f524;', '&#x1f525;', '&#x1f526;', '&#x1f528;', '&#x1f529;', '&#x1f52a;', '&#x1f52b;', '&#x1f52d;', '&#x1f52e;', '&#x1f52f;', '&#x1f530;', '&#x1f531;', '&#x1f532;', '&#x1f533;', '&#x1f534;', '&#x1f535;', '&#x1f536;', '&#x1f537;', '&#x1f538;', '&#x1f539;', '&#x1f53a;', '&#x1f53b;', '&#x1f53c;', '&#x1f53d;', '&#x1f549;', '&#x1f54a;', '&#x1f54b;', '&#x1f54c;', '&#x1f54d;', '&#x1f54e;', '&#x1f550;', '&#x1f551;', '&#x1f552;', '&#x1f553;', '&#x1f554;', '&#x1f555;', '&#x1f556;', '&#x1f557;', '&#x1f558;', '&#x1f559;', '&#x1f55a;', '&#x1f55b;', '&#x1f55c;', '&#x1f55d;', '&#x1f55e;', '&#x1f55f;', '&#x1f560;', '&#x1f561;', '&#x1f562;', '&#x1f563;', '&#x1f564;', '&#x1f565;', '&#x1f566;', '&#x1f567;', '&#x1f56f;', '&#x1f570;', '&#x1f573;', '&#x1f574;', '&#x1f575;', '&#x1f576;', '&#x1f577;', '&#x1f578;', '&#x1f579;', '&#x1f57a;', '&#x1f587;', '&#x1f58a;', '&#x1f58b;', '&#x1f58c;', '&#x1f58d;', '&#x1f590;', '&#x1f595;', '&#x1f596;', '&#x1f5a4;', '&#x1f5a5;', '&#x1f5a8;', '&#x1f5b1;', '&#x1f5b2;', '&#x1f5bc;', '&#x1f5c2;', '&#x1f5c3;', '&#x1f5c4;', '&#x1f5d1;', '&#x1f5d2;', '&#x1f5d3;', '&#x1f5dc;', '&#x1f5dd;', '&#x1f5de;', '&#x1f5e1;', '&#x1f5e3;', '&#x1f5ef;', '&#x1f5f3;', '&#x1f5fa;', '&#x1f5fb;', '&#x1f5fc;', '&#x1f5fd;', '&#x1f5fe;', '&#x1f5ff;', '&#x1f600;', '&#x1f601;', '&#x1f602;', '&#x1f603;', '&#x1f604;', '&#x1f605;', '&#x1f606;', '&#x1f607;', '&#x1f608;', '&#x1f609;', '&#x1f60a;', '&#x1f60b;', '&#x1f60c;', '&#x1f60d;', '&#x1f60e;', '&#x1f60f;', '&#x1f610;', '&#x1f611;', '&#x1f612;', '&#x1f613;', '&#x1f614;', '&#x1f615;', '&#x1f616;', '&#x1f617;', '&#x1f618;', '&#x1f619;', '&#x1f61a;', '&#x1f61b;', '&#x1f61c;', '&#x1f61d;', '&#x1f61e;', '&#x1f61f;', '&#x1f620;', '&#x1f621;', '&#x1f622;', '&#x1f623;', '&#x1f624;', '&#x1f625;', '&#x1f626;', '&#x1f627;', '&#x1f628;', '&#x1f629;', '&#x1f62a;', '&#x1f62b;', '&#x1f62c;', '&#x1f62d;', '&#x1f62e;', '&#x1f62f;', '&#x1f630;', '&#x1f631;', '&#x1f632;', '&#x1f633;', '&#x1f634;', '&#x1f635;', '&#x1f636;', '&#x1f637;', '&#x1f638;', '&#x1f639;', '&#x1f63a;', '&#x1f63b;', '&#x1f63c;', '&#x1f63d;', '&#x1f63e;', '&#x1f63f;', '&#x1f640;', '&#x1f641;', '&#x1f642;', '&#x1f643;', '&#x1f644;', '&#x1f645;', '&#x1f646;', '&#x1f647;', '&#x1f648;', '&#x1f649;', '&#x1f64a;', '&#x1f64b;', '&#x1f64c;', '&#x1f64d;', '&#x1f64e;', '&#x1f64f;', '&#x1f681;', '&#x1f682;', '&#x1f683;', '&#x1f684;', '&#x1f685;', '&#x1f686;', '&#x1f687;', '&#x1f688;', '&#x1f689;', '&#x1f68a;', '&#x1f68b;', '&#x1f68c;', '&#x1f68d;', '&#x1f68e;', '&#x1f68f;', '&#x1f690;', '&#x1f691;', '&#x1f693;', '&#x1f694;', '&#x1f695;', '&#x1f696;', '&#x1f697;', '&#x1f698;', '&#x1f699;', '&#x1f69a;', '&#x1f69b;', '&#x1f69c;', '&#x1f69d;', '&#x1f69e;', '&#x1f69f;', '&#x1f6a0;', '&#x1f6a1;', '&#x1f6a2;', '&#x1f6a3;', '&#x1f6a4;', '&#x1f6a5;', '&#x1f6a6;', '&#x1f6a7;', '&#x1f6a8;', '&#x1f6a9;', '&#x1f6aa;', '&#x1f6ab;', '&#x1f6ac;', '&#x1f6ad;', '&#x1f6ae;', '&#x1f6af;', '&#x1f6b0;', '&#x1f6b1;', '&#x1f6b2;', '&#x1f6b3;', '&#x1f6b4;', '&#x1f6b5;', '&#x1f6b6;', '&#x1f6b7;', '&#x1f6b8;', '&#x1f6b9;', '&#x1f6ba;', '&#x1f6bb;', '&#x1f6bc;', '&#x1f6bd;', '&#x1f6be;', '&#x1f6bf;', '&#x1f6c0;', '&#x1f6c1;', '&#x1f6c2;', '&#x1f6c3;', '&#x1f6c4;', '&#x1f6c5;', '&#x1f6cb;', '&#x1f6cc;', '&#x1f6cd;', '&#x1f6ce;', '&#x1f6cf;', '&#x1f6d0;', '&#x1f6d1;', '&#x1f6d2;', '&#x1f6d5;', '&#x1f6d6;', '&#x1f6d7;', '&#x1f6e0;', '&#x1f6e1;', '&#x1f6e2;', '&#x1f6e3;', '&#x1f6e4;', '&#x1f6e5;', '&#x1f6e9;', '&#x1f6eb;', '&#x1f6ec;', '&#x1f6f0;', '&#x1f6f3;', '&#x1f6f4;', '&#x1f6f5;', '&#x1f6f6;', '&#x1f6f7;', '&#x1f6f8;', '&#x1f6f9;', '&#x1f6fa;', '&#x1f6fb;', '&#x1f6fc;', '&#x1f7e0;', '&#x1f7e1;', '&#x1f7e2;', '&#x1f7e3;', '&#x1f7e4;', '&#x1f7e5;', '&#x1f7e6;', '&#x1f7e7;', '&#x1f7e8;', '&#x1f7e9;', '&#x1f7ea;', '&#x1f7eb;', '&#x1f90c;', '&#x1f90d;', '&#x1f90e;', '&#x1f90f;', '&#x1f910;', '&#x1f911;', '&#x1f912;', '&#x1f913;', '&#x1f914;', '&#x1f915;', '&#x1f916;', '&#x1f917;', '&#x1f918;', '&#x1f919;', '&#x1f91a;', '&#x1f91b;', '&#x1f91c;', '&#x1f91e;', '&#x1f91f;', '&#x1f920;', '&#x1f921;', '&#x1f922;', '&#x1f923;', '&#x1f924;', '&#x1f925;', '&#x1f926;', '&#x1f927;', '&#x1f928;', '&#x1f929;', '&#x1f92a;', '&#x1f92b;', '&#x1f92c;', '&#x1f92d;', '&#x1f92e;', '&#x1f92f;', '&#x1f930;', '&#x1f931;', '&#x1f932;', '&#x1f933;', '&#x1f934;', '&#x1f935;', '&#x1f936;', '&#x1f937;', '&#x1f938;', '&#x1f939;', '&#x1f93a;', '&#x1f93c;', '&#x1f93d;', '&#x1f93e;', '&#x1f93f;', '&#x1f940;', '&#x1f941;', '&#x1f942;', '&#x1f943;', '&#x1f944;', '&#x1f945;', '&#x1f947;', '&#x1f948;', '&#x1f949;', '&#x1f94a;', '&#x1f94b;', '&#x1f94c;', '&#x1f94d;', '&#x1f94e;', '&#x1f94f;', '&#x1f950;', '&#x1f951;', '&#x1f952;', '&#x1f953;', '&#x1f954;', '&#x1f955;', '&#x1f956;', '&#x1f957;', '&#x1f958;', '&#x1f959;', '&#x1f95a;', '&#x1f95b;', '&#x1f95c;', '&#x1f95d;', '&#x1f95e;', '&#x1f95f;', '&#x1f960;', '&#x1f961;', '&#x1f962;', '&#x1f963;', '&#x1f964;', '&#x1f965;', '&#x1f966;', '&#x1f967;', '&#x1f968;', '&#x1f969;', '&#x1f96a;', '&#x1f96b;', '&#x1f96c;', '&#x1f96d;', '&#x1f96e;', '&#x1f96f;', '&#x1f970;', '&#x1f971;', '&#x1f972;', '&#x1f973;', '&#x1f974;', '&#x1f975;', '&#x1f976;', '&#x1f977;', '&#x1f978;', '&#x1f97a;', '&#x1f97b;', '&#x1f97c;', '&#x1f97d;', '&#x1f97e;', '&#x1f97f;', '&#x1f980;', '&#x1f981;', '&#x1f982;', '&#x1f983;', '&#x1f984;', '&#x1f985;', '&#x1f986;', '&#x1f987;', '&#x1f988;', '&#x1f989;', '&#x1f98a;', '&#x1f98b;', '&#x1f98c;', '&#x1f98d;', '&#x1f98e;', '&#x1f98f;', '&#x1f990;', '&#x1f991;', '&#x1f992;', '&#x1f993;', '&#x1f994;', '&#x1f995;', '&#x1f996;', '&#x1f997;', '&#x1f998;', '&#x1f999;', '&#x1f99a;', '&#x1f99b;', '&#x1f99c;', '&#x1f99d;', '&#x1f99e;', '&#x1f99f;', '&#x1f9a0;', '&#x1f9a1;', '&#x1f9a2;', '&#x1f9a3;', '&#x1f9a4;', '&#x1f9a5;', '&#x1f9a6;', '&#x1f9a7;', '&#x1f9a8;', '&#x1f9a9;', '&#x1f9aa;', '&#x1f9ab;', '&#x1f9ac;', '&#x1f9ad;', '&#x1f9ae;', '&#x1f9b4;', '&#x1f9b5;', '&#x1f9b6;', '&#x1f9b7;', '&#x1f9b8;', '&#x1f9b9;', '&#x1f9bb;', '&#x1f9be;', '&#x1f9bf;', '&#x1f9c0;', '&#x1f9c1;', '&#x1f9c2;', '&#x1f9c3;', '&#x1f9c4;', '&#x1f9c5;', '&#x1f9c6;', '&#x1f9c7;', '&#x1f9c8;', '&#x1f9c9;', '&#x1f9ca;', '&#x1f9cb;', '&#x1f9cd;', '&#x1f9ce;', '&#x1f9cf;', '&#x1f9d0;', '&#x1f9d1;', '&#x1f9d2;', '&#x1f9d3;', '&#x1f9d4;', '&#x1f9d5;', '&#x1f9d6;', '&#x1f9d7;', '&#x1f9d8;', '&#x1f9d9;', '&#x1f9da;', '&#x1f9db;', '&#x1f9dc;', '&#x1f9dd;', '&#x1f9de;', '&#x1f9df;', '&#x1f9e0;', '&#x1f9e1;', '&#x1f9e2;', '&#x1f9e3;', '&#x1f9e4;', '&#x1f9e5;', '&#x1f9e6;', '&#x1f9e7;', '&#x1f9e8;', '&#x1f9e9;', '&#x1f9ea;', '&#x1f9eb;', '&#x1f9ec;', '&#x1f9ed;', '&#x1f9ee;', '&#x1f9ef;', '&#x1f9f0;', '&#x1f9f1;', '&#x1f9f2;', '&#x1f9f3;', '&#x1f9f4;', '&#x1f9f5;', '&#x1f9f6;', '&#x1f9f7;', '&#x1f9f8;', '&#x1f9f9;', '&#x1f9fa;', '&#x1f9fb;', '&#x1f9fc;', '&#x1f9fd;', '&#x1f9fe;', '&#x1f9ff;', '&#x1fa70;', '&#x1fa71;', '&#x1fa72;', '&#x1fa73;', '&#x1fa74;', '&#x1fa78;', '&#x1fa79;', '&#x1fa7a;', '&#x1fa80;', '&#x1fa81;', '&#x1fa82;', '&#x1fa83;', '&#x1fa84;', '&#x1fa85;', '&#x1fa86;', '&#x1fa90;', '&#x1fa91;', '&#x1fa92;', '&#x1fa93;', '&#x1fa94;', '&#x1fa95;', '&#x1fa96;', '&#x1fa97;', '&#x1fa98;', '&#x1fa99;', '&#x1fa9a;', '&#x1fa9b;', '&#x1fa9c;', '&#x1fa9d;', '&#x1fa9e;', '&#x1fa9f;', '&#x1faa0;', '&#x1faa1;', '&#x1faa2;', '&#x1faa3;', '&#x1faa4;', '&#x1faa5;', '&#x1faa6;', '&#x1faa7;', '&#x1faa8;', '&#x1fab0;', '&#x1fab1;', '&#x1fab2;', '&#x1fab3;', '&#x1fab4;', '&#x1fab5;', '&#x1fab6;', '&#x1fac0;', '&#x1fac1;', '&#x1fac2;', '&#x1fad0;', '&#x1fad1;', '&#x1fad2;', '&#x1fad3;', '&#x1fad4;', '&#x1fad5;', '&#x1fad6;', '&#x203c;', '&#x2049;', '&#x2122;', '&#x2139;', '&#x2194;', '&#x2195;', '&#x2196;', '&#x2197;', '&#x2198;', '&#x2199;', '&#x21a9;', '&#x21aa;', '&#x20e3;', '&#x231a;', '&#x231b;', '&#x2328;', '&#x23cf;', '&#x23e9;', '&#x23ea;', '&#x23eb;', '&#x23ec;', '&#x23ed;', '&#x23ee;', '&#x23ef;', '&#x23f0;', '&#x23f1;', '&#x23f2;', '&#x23f3;', '&#x23f8;', '&#x23f9;', '&#x23fa;', '&#x24c2;', '&#x25aa;', '&#x25ab;', '&#x25b6;', '&#x25c0;', '&#x25fb;', '&#x25fc;', '&#x25fd;', '&#x25fe;', '&#x2600;', '&#x2601;', '&#x2602;', '&#x2603;', '&#x2604;', '&#x260e;', '&#x2611;', '&#x2614;', '&#x2615;', '&#x2618;', '&#x261d;', '&#x2622;', '&#x2623;', '&#x2626;', '&#x262a;', '&#x262e;', '&#x262f;', '&#x2638;', '&#x2639;', '&#x263a;', '&#x2648;', '&#x2649;', '&#x264a;', '&#x264b;', '&#x264c;', '&#x264d;', '&#x264e;', '&#x264f;', '&#x2650;', '&#x2651;', '&#x2652;', '&#x2653;', '&#x265f;', '&#x2660;', '&#x2663;', '&#x2665;', '&#x2666;', '&#x2668;', '&#x267b;', '&#x267e;', '&#x267f;', '&#x2692;', '&#x2693;', '&#x2694;', '&#x2697;', '&#x2699;', '&#x269b;', '&#x269c;', '&#x26a0;', '&#x26a1;', '&#x26aa;', '&#x26ab;', '&#x26b0;', '&#x26b1;', '&#x26bd;', '&#x26be;', '&#x26c4;', '&#x26c5;', '&#x26c8;', '&#x26ce;', '&#x26cf;', '&#x26d1;', '&#x26d3;', '&#x26d4;', '&#x26e9;', '&#x26ea;', '&#x26f0;', '&#x26f1;', '&#x26f2;', '&#x26f3;', '&#x26f4;', '&#x26f5;', '&#x26f7;', '&#x26f8;', '&#x26f9;', '&#x26fa;', '&#x26fd;', '&#x2702;', '&#x2705;', '&#x2709;', '&#x270a;', '&#x270b;', '&#x270c;', '&#x270d;', '&#x270f;', '&#x2712;', '&#x2714;', '&#x2716;', '&#x271d;', '&#x2721;', '&#x2728;', '&#x2733;', '&#x2734;', '&#x2747;', '&#x274c;', '&#x274e;', '&#x2753;', '&#x2754;', '&#x2755;', '&#x2757;', '&#x2763;', '&#x2795;', '&#x2796;', '&#x2797;', '&#x27a1;', '&#x27b0;', '&#x27bf;', '&#x2934;', '&#x2935;', '&#x2b05;', '&#x2b06;', '&#x2b07;', '&#x2b1c;', '&#x2b50;', '&#x2b55;', '&#x3030;', '&#x303d;', '&#x3297;', '&#x3299;', '&#xe50a;' );
        

        if ( 'entities' === $type ) {
            return $entities;
        }

        return $partials;
    }
    public static function wp_encode_emoji( $content ) {
        $emoji = CPhoenixSendMail::_wp_emoji_list( 'partials' );

        foreach ( $emoji as $emojum ) {
            $emoji_char = html_entity_decode( $emojum );
            if ( false !== strpos( $content, $emoji_char ) ) {
                $content = preg_replace( "/$emoji_char/", "", $content );
            }
        }

        return $content;
    }
}