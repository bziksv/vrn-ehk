<?

class CPhoenixRegionality
{

    public static function checkDomainAvailible($domain)
    {
        if(!filter_var($domain, FILTER_VALIDATE_URL))
            return false;

        $curlInit = curl_init($domain);
        curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlInit, CURLOPT_HEADER, true);
        curl_setopt($curlInit, CURLOPT_NOBODY, true);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curlInit);
        curl_close($curlInit);

        if ($response)
            return true;

        return false;
    }

    public static function getRegionById($id, $locationID)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        \Bitrix\Main\Loader::includeModule('sale');

        global $PHOENIX_TEMPLATE_ARRAY;

        static $arRegion;
        $arRegion = false;

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["ACTIVE"]["VALUES"]["ACTIVE"]["VALUE"] == "Y" && strlen($id)>0)
        { 
            $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_LOCATION_LINK_ORDER");
            $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$id);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>1), $arSelect);
            
            if($res->SelectedRowsCount() > 0)
            {
                if($reg = $res->GetNext())
                {
                    $arRegion["REGION_ID"] = $reg["ID"];
                    $arRegion["LOCATION_ID"] = ($locationID)?$locationID:$reg["PROPERTY_LOCATION_LINK_ORDER_VALUE"];
                }
            }            
        }

        return $arRegion;
    }

    public static function getRegionByLocationId($id)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        \Bitrix\Main\Loader::includeModule('sale');

        global $PHOENIX_TEMPLATE_ARRAY;

        static $arRegion;
        $arRegion = array(
            "LOCATION_ID"=>0,
            "REGION_ID"=>0
        );

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["ACTIVE"]["VALUES"]["ACTIVE"]["VALUE"] == "Y" && strlen($id)>0)
        { 
            $res = \Bitrix\Sale\Location\LocationTable::getList(array(
                'filter' => array(
                    '=ID' => $id,
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

            while($item = $res->fetch())
            {

                $arSelect = Array("ID", "IBLOCK_ID", "NAME");
                $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", 
                    array(
                        "LOGIC" => "OR",
                        array('PROPERTY_LOCATION_LINK' => $item["I_ID"]),
                        array('PROPERTY_LOCATION_LINK_ORDER' => $item["I_ID"])
                    )
                );
                $resReg = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nTopCount"=>1), $arSelect);


                $arRegion["LOCATION_ID"] = $id;
                

                if($resReg->SelectedRowsCount() > 0)
                {
                    if($reg = $resReg->GetNext())
                    {
                        $arRegion["REGION_ID"] = $reg["ID"];
                    }
                }
                
            }
        }

        return $arRegion;
    }
    public static function getDefaultRegion()
    {
        global $PHOENIX_TEMPLATE_ARRAY;

        return CPhoenixRegionality::getRegionByLocationId($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["LOCATION_LIST"]["VALUE_HIDDEN"]);
        
    }


    public static function getRegionByIP()
    {
        \Bitrix\Main\Loader::includeModule('iblock');


        global $PHOENIX_TEMPLATE_ARRAY;

        static $arRegion;
        $arRegion = false;

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["ACTIVE"]["VALUES"]["ACTIVE"]["VALUE"] == "Y")
        {

            if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
                return false;

                
            $ip = $_SERVER["REMOTE_ADDR"];

            if(!empty($_SERVER["HTTP_X_REAL_IP"]))
                $ip = $_SERVER["HTTP_X_REAL_IP"];
            
           
            
            $city = false;


            if(class_exists('\Bitrix\Main\Service\GeoIp\Manager'))
            {
                $obBitrixGeoIPResult = \Bitrix\Main\Service\GeoIp\Manager::getDataResult($ip, $languageId);
                if(!isset($_SESSION['GEOIP']['cityName']) || !$_SESSION['GEOIP']['cityName'])
                {
                    if($arGeoData = self::GeoIp_GetGeoData($ip, 'ru')){
                        $_SESSION['GEOIP'] = $arGeoData;
                        $city = isset($_SESSION['GEOIP']['cityName']) && $_SESSION['GEOIP']['cityName'] ? $_SESSION['GEOIP']['cityName'] : '';
                    }
                }
                else{
                    $city = isset($_SESSION['GEOIP']['cityName']) && $_SESSION['GEOIP']['cityName'] ? $_SESSION['GEOIP']['cityName'] : '';
                }
            }

            if(strlen($city)<=0)
            {
                if(\Bitrix\Main\Loader::includeModule('altasib.geobase'))
                {
                    if($arData = CAltasibGeoBase::GetAddres())
                        $city = isset($arData['CITY_NAME']) && $arData['CITY_NAME'] ? $arData['CITY_NAME'] : '';
            
                }
            }


            if(strlen($city)>0)
            {
                
                $res = \Bitrix\Sale\Location\LocationTable::getList(
                    array(
                        'filter' => array(
                            "=NAME.LANGUAGE_ID" => LANGUAGE_ID, 
                            "NAME.NAME" => $city,
                            array(
                                "LOGIC" => "OR",
                                array('=TYPE.CODE' => 'CITY'),
                                array('=TYPE.CODE' => 'VILLAGE')
                            )
                    ),
                        'select' => array('NAME_RU' => 'NAME.NAME', "ID", "PARENT.*", "TYPE")
                    )
                );

                if($arReg = $res->Fetch()) 
                    $arRegion = self::getRegionByLocationId($arReg["ID"]);
            }
        }

        return $arRegion;
    }
    
    public static function getRegionIdByDomain($regionID, $locationID)
    {
        global $PHOENIX_TEMPLATE_ARRAY;

        $arRegion = array();

        $PHOENIX_TEMPLATE_ARRAY["DOMAIN"] = CPhoenixHost::getHost($_SERVER, "N", "Y");

        $arFilter = Array(
            "IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REGION']["IBLOCK_ID"],
            "ACTIVE"=>"Y",
            array('LOGIC'=>'OR',
                array('?PROPERTY_MAIN_DOMAIN'=> $PHOENIX_TEMPLATE_ARRAY["DOMAIN"]."|"."http://".$PHOENIX_TEMPLATE_ARRAY["DOMAIN"].'/'."|"."https://".$PHOENIX_TEMPLATE_ARRAY["DOMAIN"].'/'),
                array('?PROPERTY_DOMAINS'=> $PHOENIX_TEMPLATE_ARRAY["DOMAIN"]."|"."http://".$PHOENIX_TEMPLATE_ARRAY["DOMAIN"].'/'."|"."https://".$PHOENIX_TEMPLATE_ARRAY["DOMAIN"].'/')
            )
        );

        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nTopCount"=>1), array("ID", "PROPERTY_LOCATION_LINK_ORDER"));

        if($PHOENIX_TEMPLATE_ARRAY["DOMAIN"] !== $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["STR_URL"])
        {
            if($res->SelectedRowsCount() > 0)
            {
                while($ob = $res->GetNextElement())
                {
                    $fields = $ob->GetFields();

                    $location = $fields["PROPERTY_LOCATION_LINK_ORDER_VALUE"];

                    if($regionID == $fields["ID"] && $locationID)
                        $location = $locationID;

                    $arRegion = array(
                        "REGION_ID"=>$fields["ID"],
                        "LOCATION_ID"=>$location
                    );
                }
            }
        }

        return $arRegion;

    }

    static function OnSaleComponentOrderPropertiesHandler(&$arFields){

        global $PHOENIX_TEMPLATE_ARRAY;
      
       
        if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"])){
            if($_SERVER['REQUEST_METHOD'] != 'POST'){

                $locationLink = "";

                if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["LOCATION_ID"])>0)
                    $locationLink = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["LOCATION_ID"];

                else if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["LOCATION_LINK_ORDER"]["VALUE"])>0)
                    $locationLink = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["LOCATION_LINK_ORDER"]["VALUE"];
                

                if(strlen($locationLink)>0){

                    $arLocationProp = CSaleOrderProps::GetList(
                        array('SORT' => 'ASC'),
                        array(
                            'PERSON_TYPE_ID' => $arFields['PERSON_TYPE_ID'],
                            'TYPE' => 'LOCATION',
                            'IS_LOCATION' => 'Y',
                            'ACTIVE' => 'Y',
                        ),
                        false,
                        false,
                        array('ID')
                    )->Fetch();
                    if($arLocationProp){
                        $arFields['ORDER_PROP'][$arLocationProp['ID']] = CSaleLocation::getLocationCODEbyID($locationLink);
                    }


                    $arLocationZipProp = CSaleOrderProps::GetList(
                        array('SORT' => 'ASC'),
                        array(
                            'PERSON_TYPE_ID' => $arFields['PERSON_TYPE_ID'],
                            'CODE' => 'ZIP',
                            'ACTIVE' => 'Y',
                        ),
                        false,
                        false,
                        array('ID')
                    )->Fetch();
                    if($arLocationZipProp){
                        $rsLocaction = CSaleLocation::GetLocationZIP($locationLink);
                        $arLocation = $rsLocaction->Fetch();
                        if($arLocation['ZIP']){
                            $arFields['ORDER_PROP'][$arLocationZipProp['ID']] = $arLocation['ZIP'];
                        }
                    }
                }
            }
            else{
                if(isset($arFields['PERSON_TYPE_ID']) && isset($arFields['PERSON_TYPE_OLD'])){
                    if($arFields['PROFILE_CHANGE'] === 'Y' || ($arFields['PERSON_TYPE_ID'] && $arFields['PERSON_TYPE_OLD'] && ($arFields['PERSON_TYPE_ID'] != $arFields['PERSON_TYPE_OLD']))){
                        $arLocationProps = $arZipProps = array();

                        $dbRes = CSaleOrderProps::GetList(
                            array('SORT' => 'ASC'),
                            array(
                                'PERSON_TYPE_ID' => array($arFields['PERSON_TYPE_ID'], $arFields['PERSON_TYPE_OLD']),
                                'TYPE' => 'LOCATION',
                                'IS_LOCATION' => 'Y',
                                'ACTIVE' => 'Y',
                            ),
                            false,
                            false,
                            array(
                                'ID',
                                'PERSON_TYPE_ID'
                            )
                        );
                        while($arLocationProp = $dbRes->Fetch()){
                            $arLocationProps[$arLocationProp['PERSON_TYPE_ID']] = $arLocationProp['ID'];
                        }

                        if($arLocationProps){
                            $arFields['ORDER_PROP'][$arLocationProps[$arFields['PERSON_TYPE_ID']]] = $_POST['order']['ORDER_PROP_'.$arLocationProps[$arFields['PERSON_TYPE_OLD']]];
                        }

                        $dbRes = CSaleOrderProps::GetList(
                            array('SORT' => 'ASC'),
                            array(
                                'PERSON_TYPE_ID' => array($arFields['PERSON_TYPE_ID'], $arFields['PERSON_TYPE_OLD']),
                                'CODE' => 'ZIP',
                                'ACTIVE' => 'Y',
                            ),
                            false,
                            false,
                            array(
                                'ID',
                                'PERSON_TYPE_ID'
                            )
                        );
                        while($arZipProp = $dbRes->Fetch()){
                            $arZipProps[$arZipProp['PERSON_TYPE_ID']] = $arZipProp['ID'];
                        }

                        if($arZipProps){
                            $arFields['ORDER_PROP'][$arZipProps[$arFields['PERSON_TYPE_ID']]] = $_POST['order']['ORDER_PROP_'.$arZipProps[$arFields['PERSON_TYPE_OLD']]];
                        }
                    }
                }
            }
        }
    }

    public static function getRegionList($arItems=array()){

        $arRegions = array();

        if(!empty($arItems))
        {
            global $PHOENIX_TEMPLATE_ARRAY;

            if(!isset($arItems[0]["DOMAIN_SETTINGS"]))
            {
                foreach ($arItems as $key => $fields){
                    $arItems[$key] = CPhoenixRegionality::prepareItemRegion($fields);
                }
            }

            $arRegions = array(
                "FAVORITES"=>array(),
                "OTHER" => array()
            );


            foreach ($arItems as $key => $fields){


                $flagLocation = "";
                $nameLocation = strip_tags(trim($fields["~NAME"]));

                if(isset($fields["NAME_RU"]))
                {
                    $nameLocation = $fields["NAME_VALUE"];

                    if(isset($arRegionsList[$fields["ID"]])){
                        $fields["DOMAIN_SETTINGS"] = $arRegionsList[$fields["ID"]];
                    }
                    else
                        $fields["DOMAIN_SETTINGS"]["HREF"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["URL"];

                    $id = $fields["ID"]."_location";
                }
                else
                {
                    $id = $fields["ID"]."_".$fields["PROPERTIES"]["LOCATION_LINK_ORDER"]["VALUE"];
                }

                


                $searchValues = array(
                    "value"=>implode(';',array($id, $fields["DOMAIN_SETTINGS"]["HREF"], $nameLocation)),
                    "label"=>strip_tags(trim($fields["~NAME"])),
                    "icon"=>$fields["PICTURE_SRC"]
                );

                if($fields["PROPERTIES"]["FAVORITES"]["VALUE"] == "Y")
                {
                    $arRegions["FAVORITES"][$fields["ID"]] = $searchValues;
                }


                $arRegions["OTHER"][$fields["ID"]]["SEARCH_VALUES"] = $searchValues;

            }

        }

        return $arRegions;
    }

    public static function clearDomain($domain)
    {
        if(strlen($domain)<=0)
            return false;

        return CPhoenixHost::convertFromPunycode(str_replace(array("http:","https:", "/"), array("", "", ""), strtolower($domain)));
    }


    public static function prepareItemRegion($arItem=array())
    {

        if(!empty($arItem))
        {

            global $PHOENIX_TEMPLATE_ARRAY;

            $arItem["PICTURE_SRC"] = $arItem["PICTURE_SMALL_SRC"] = "";

            $mainDomain = $mainDomainValue = "";
            $mainDomainprotocol = "http://";
            
            $otherDomains = array();

            if(isset($arItem["NAME_RU"]))
            {
                $arItem["NAME"] = $arItem["~NAME"] = $arItem["NAME_RU"];

                if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["BG"]["SRC"])>0)
                    $arItem["PICTURE_SRC"] = $arItem["PICTURE_SMALL_SRC"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["BG"]["SRC"];

                if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["BG"]["BG_SMALL"])>0)
                    $arItem["PICTURE_SMALL_SRC"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["BG_SMALL"]["SRC"];            

            }
            else
            {
                if(strlen($arItem["PROPERTIES"]["PICTURE"]["VALUE"])>0)
                {
                    $img = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURE"]["VALUE"], array(
                        'width'=>800,
                        'height'=>1000
                    ), BX_RESIZE_IMAGE_EXACT, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                    $arItem["PICTURE_SRC"] = $arItem["PICTURE_SMALL_SRC"] = $img["src"];

                    if(strlen($arItem["PROPERTIES"]["PICTURE_SMALL"]["VALUE"])>0)
                    {
                        $img = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURE_SMALL"]["VALUE"], array(
                            'width'=>240,
                            'height'=>240
                        ), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                        $arItem["PICTURE_SMALL_SRC"] = $img["src"];
                    }
                }



                $mainDomain = self::clearDomain($arItem["PROPERTIES"]["MAIN_DOMAIN"]["VALUE"]);


                $mainDomainValue = trim($arItem["PROPERTIES"]["MAIN_DOMAIN"]["VALUE"]);

                $out = array();

                preg_match("/^https:\/\//", $arItem["PROPERTIES"]["MAIN_DOMAIN"]["VALUE"], $out);

                if(!empty($out))
                    $mainDomainprotocol = $out[0];

                unset($out);

                if(!empty($arItem["PROPERTIES"]["DOMAINS"]["VALUE"]))
                {

                    foreach ($arItem["PROPERTIES"]["DOMAINS"]["VALUE"] as $key => $value)
                    {
                        
                        $protocol = "http://";

                        $out = array();

                        preg_match("/^https:\/\//", $value, $out);

                        if(!empty($out))
                            $protocol = $out[0];

                        $domain = self::clearDomain($value);


                        $otherDomains[$key] = array(
                            "DOMAIN" => $domain,
                            "PROTOCOL"=> $protocol,
                            "VALUE"=>$value,
                            "HREF"=>$protocol.$domain
                        );

                        
                    }
                    unset($out);

                    if(strlen($mainDomainValue)<=0)
                    {
                        $mainDomain = $otherDomains[0]["DOMAIN"];
                        $mainDomainprotocol = $otherDomains[0]["PROTOCOL"];
                        $mainDomainValue = $otherDomains[0]["VALUE"];
                    }

                }

                $arItem["DOMAIN_SETTINGS"] = array(
                    "DOMAIN" => $mainDomain,
                    "PROTOCOL"=>$mainDomainprotocol,
                    "VALUE"=> $mainDomainValue,
                    "OTHER_DOMAINS" => $otherDomains,
                    "HREF"=> $mainDomainprotocol.$mainDomain,
                    "DOMAINS" => array(
                        array(
                            "DOMAIN" => $mainDomain,
                            "HREF" => $mainDomainprotocol.$mainDomain
                        ),
                    )
                );

                if($arItem["DOMAIN_SETTINGS"]["OTHER_DOMAINS"])
                {
                    $arTmp = array();
                    foreach ($arItem["DOMAIN_SETTINGS"]["OTHER_DOMAINS"] as $key => $value) {
                        $arTmp = array(
                            "DOMAIN" => $value["DOMAIN"],
                            "HREF" => $value["HREF"]
                        );
                        $arItem["DOMAIN_SETTINGS"]["DOMAINS"][]=$arTmp;
                    }

                    unset($arTmp);
                }



            }



            if(strlen($mainDomainValue)<=0)
            {
                $arItem["DOMAIN_SETTINGS"] = array(
                    "DOMAIN" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["STR_VALUE"],
                    "PROTOCOL"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["PROTOCOL"],
                    "VALUE"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["VALUE"],
                    "HREF"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["URL"],
                    "DOMAINS" => array()
                ); 
                
            }

            
        }


        return $arItem;

    }

    public static function getRegions(){

        global $PHOENIX_TEMPLATE_ARRAY;

        $arRegions = array();

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REGION']["IBLOCK_ID"])
        {
            $arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REGION']["IBLOCK_ID"], "ACTIVE"=>"Y");

            
            $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false);

            $arItems = array();

            while($ob = $res->GetNextElement())
            {
                $fields = $ob->GetFields();
                $fields["PROPERTIES"] = $ob->GetProperties();
                $arItems[] = CPhoenixRegionality::prepareItemRegion($fields);
            }

            if(empty($arItems))
                return array();

            $arRegions = CPhoenixRegionality::getRegionList($arItems);
            
       
        }



        return $arRegions;
    }

   

   
    public static function getRegionUrl($arRegionID = array(), $pageUrl="", $domen = "")
    {
        global $PHOENIX_TEMPLATE_ARRAY;
        $arUrl = array(
            "URL"=>"",
            "HREF"=>""
        );

        $url = "";

        if(!$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REGION']["IBLOCK_ID"])
            return $arRegion;

        if(isset($arRegionID["REGION_ID"]))
        {
            $arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REGION']["IBLOCK_ID"], "ACTIVE"=>"Y", "ID"=>$arRegionID["REGION_ID"]);
            $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false);
            if($res->SelectedRowsCount() > 0)
            {
                while($ob = $res->GetNextElement())
                {
                    $fields = $ob->GetFields();
                    $fields["PROPERTIES"] = $ob->GetProperties();
                    $arRegion = CPhoenixRegionality::prepareItemRegion($fields);

                    $founded = false;

                    if(!empty($arRegion["DOMAIN_SETTINGS"]["DOMAINS"]))
                    {
                        foreach ($arRegion["DOMAIN_SETTINGS"]["DOMAINS"] as $key => $value) {
                            if($domen == $value["DOMAIN"])
                            {
                                $url = $value["HREF"];
                                $arUrl["URL"] = $value["DOMAIN"];
                                $founded = true;
                                break;
                            }

                        }                        
                    }

                    if(!$founded)
                    {
                        $url = $arRegion["DOMAIN_SETTINGS"]["HREF"];
                        $arUrl["URL"] = $arRegion["DOMAIN_SETTINGS"]["DOMAIN"];
                    }
                }
            }

        }

        if(strlen($arUrl["URL"])<=0)
        {
            $arUrl["URL"] = self::clearDomain($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["URL"]);

            $url = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["URL"];
        }


        $arUrl["HREF"]=$url.$pageUrl;

        return $arUrl;

    }
    public static function getRegionCurrent()
    {
        
        global $PHOENIX_TEMPLATE_ARRAY;

        $arRegion = array();
        

        if(!$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REGION']["IBLOCK_ID"] || ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['ACTIVE']["VALUE"]["ACTIVE"] != "Y" || strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["VALUE"])<=0))
            return $arRegion;


        $regionID = (isset($_COOKIE["phoenix_CurrentRegion_".SITE_ID])) ? $_COOKIE["phoenix_CurrentRegion_".SITE_ID]: NULL;
        $locationID = (isset($_COOKIE["phoenix_CurrentLocation_".SITE_ID])) ? $_COOKIE["phoenix_CurrentLocation_".SITE_ID]: NULL;
        $issetCookieRegion = ($regionID||$locationID)?true:false;




        $domainRegion = CPhoenixRegionality::getRegionIdByDomain($regionID, $locationID);
        $defaultRegion = CPhoenixRegionality::getDefaultRegion();



        $arRegionIDs = array();

        
        if(!empty($domainRegion))
        {
            $arRegionIDs = $domainRegion;
        }
        else if($issetCookieRegion)
        {
            $arRegionIDs = array(
                "REGION_ID"=>$regionID,
                "LOCATION_ID"=>$locationID
            );


        }
        else
        {
            $arRegionIDs = $defaultRegion;
        }



        $arRegion = CPhoenixRegionality::getRegionInfo($arRegionIDs);
        $arRegion["COOKIE_REGION_ID"] = $regionID;
        $arRegion["COOKIE_LOCATION_ID"] = $locationID;

        /*if($issetCookieRegion)
        {
            if($arRegionIDs["REGION_ID"] 
                && strlen($arRegion["DOMAIN_SETTINGS"]["DOMAIN"])>0
                && $PHOENIX_TEMPLATE_ARRAY["DOMAIN"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["STR_URL"]
                && $arRegion["DOMAIN_SETTINGS"]["DOMAIN"] != $PHOENIX_TEMPLATE_ARRAY["DOMAIN"])
            {
                LocalRedirect($arRegion["DOMAIN_SETTINGS"]["HREF"], true);
            }
        }

        if(!empty($domainRegion) && $PHOENIX_TEMPLATE_ARRAY["DOMAIN"] != $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["STR_URL"])
            LocalRedirect($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["URL"], true);*/

        return $arRegion;

    }

    public static function getRegionApply()
    {
        global $PHOENIX_TEMPLATE_ARRAY;
       
        $arRegionID = CPhoenixRegionality::getRegionByIP();

        $arResult = array();

        $showApply = true;


        if(
            ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["COOKIE_LOCATION_ID"] && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["STR_URL"] === $PHOENIX_TEMPLATE_ARRAY["DOMAIN"])
            || 
            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["LOCATION_ID"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["COOKIE_LOCATION_ID"]
        )
            $showApply = false;

        
        if($showApply)
            $arResult = CPhoenixRegionality::getRegionInfo($arRegionID);
        
        
        return $arResult;
        
    }

    public static function getRegionInfo($arItem=array())
    {
        $arResult = array();
        if(!empty($arItem))
        {

            if(intval($arItem["LOCATION_ID"]))
            {
                $arReg = array();

                $resLocation = \Bitrix\Sale\Location\LocationTable::getList(
                    array(
                        'filter' => array(
                            "=NAME.LANGUAGE_ID" => LANGUAGE_ID, 
                            "=ID" => $arItem["LOCATION_ID"]
                    ),
                        'select' => array('NAME_RU' => 'NAME.NAME', "ID")
                    )
                );

                if($arReg = $resLocation->Fetch())
                {
                    $issetRegion = false;

                    if(intval($arItem["REGION_ID"])>0)
                    {
                        $arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REGION']["IBLOCK_ID"], "ACTIVE"=>"Y", "ID"=>$arItem["REGION_ID"]);

                        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false);

                        if($res->SelectedRowsCount() > 0)
                        {
                            while($ob = $res->GetNextElement())
                            {
                                $fields = $ob->GetFields();
                                $fields["PROPERTIES"] = $ob->GetProperties();
                                $fields["CURRENT_ID"] = $fields["ID"]."_".$arItem["LOCATION_ID"];
                                $fields["REGION_ID"] = $fields["ID"];
                                $fields["LOCATION_ID"] = $arItem["LOCATION_ID"];

                                $fields["REGION_NAME"] = $fields["~NAME"];
                                $fields["NAME"] = $fields["~NAME"] = $arReg["NAME_RU"];


                                $arResult = CPhoenixRegionality::prepareItemRegion($fields);
                            }
                            $issetRegion = true;
                        }
                    }

                    if(!$issetRegion)
                    {
                        $arResult["ID"] = $arReg["ID"];
                        $arResult["NAME"] = $arResult["~NAME"] = $arReg["NAME_RU"];
                        $arResult["CURRENT_ID"] = $arReg["ID"]."_location";

                        $arResult["REGION_ID"] = NULL;
                        $arResult["LOCATION_ID"] = $arReg["ID"];
                    }
                }
            }
        }

        return $arResult;
    }


    public static function setRegionOptions($admin = "N", $flagRegionSend = false)
    {
        
        
        if(!(defined("ADMIN_SECTION") && ADMIN_SECTION === true))
        {
            
            static $flagRegion;

            if($flagRegion === NULL || $flagRegionSend)
            {
                global $PHOENIX_TEMPLATE_ARRAY;
                
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGIONS"]=self::getRegions();
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"] = self::getRegionCurrent();
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"] = self::getRegionApply();


                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["DOMAIN_SETTINGS"] && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["DOMAIN_SETTINGS"]["DOMAINS"]))
                {
                    $redirect = true;
                    foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["DOMAIN_SETTINGS"]["DOMAINS"] as $key => $value) {
                        if($PHOENIX_TEMPLATE_ARRAY["DOMAIN"] === $value["DOMAIN"])
                        {
                            $redirect = false;
                            break;
                        }
                    }
                    
                    if($redirect)
                        LocalRedirect($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["DOMAIN_SETTINGS"]["HREF"].$_SERVER["REQUEST_URI"], true);
                }



                $hashCurrent = "";
                $hashApply = "";
                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["SUBMIT"] = false;


                if($admin == "N")
                {
                    if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["PHONES"]["VALUE"]))
                    {
                        $tmpArr = $tmpArrBack = array();

                        foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["PHONES"]["VALUE"] as $key => $value)
                        {
                            $tmpArr[$key]=array(
                                "name"=>$value,
                                "desc"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["PHONES"]["DESCRIPTION"][$key]
                            );

                            $hashCurrent .= $value.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["PHONES"]["DESCRIPTION"][$key];
                        }

                        foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["PHONES"]["~VALUE"] as $key => $value)
                        {
                            $tmpArrBack[$key]=array(
                                "name"=>$value,
                                "desc"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["PHONES"]["~DESCRIPTION"][$key]
                            );
                        }

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"]=$tmpArr;
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"]=$tmpArrBack;

                        

                        unset($tmpArr, $tmpArrBack);
                    }

                    if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["EMAILS"]["VALUE"]))
                    {
                        $tmpArr = $tmpArrBack = array();

                        foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["EMAILS"]["VALUE"] as $key => $value)
                        {
                            $tmpArr[$key]=array(
                                "name"=>$value,
                                "desc"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["EMAILS"]["DESCRIPTION"][$key]
                            );
                            $hashCurrent .= $value.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["EMAILS"]["DESCRIPTION"][$key];
                        }

                        foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["EMAILS"]["~VALUE"] as $key => $value)
                        {
                            $tmpArrBack[$key]=array(
                                "name"=>$value,
                                "desc"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["EMAILS"]["~DESCRIPTION"][$key]
                            );
                        }

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"]=$tmpArr;
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["~VALUE"]=$tmpArrBack;

                        unset($tmpArr, $tmpArrBack);
                    }


                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["REGION_TAG_MAP"]["VALUE"])>0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["DIALOG_MAP"]["VALUE"]=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["REGION_TAG_MAP"]["VALUE"];

                        $hashCurrent .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["REGION_TAG_MAP"]["VALUE"]; 

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["DIALOG_MAP"]["~VALUE"]=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["REGION_TAG_MAP"]["~VALUE"];

                    }

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["ADDRESS"]["VALUE"])>0)
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["ADDRESS"]["VALUE"]=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["ADDRESS"]["VALUE"];

                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["ADDRESS"]["~VALUE"]=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["ADDRESS"]["~VALUE"];

                        $hashCurrent .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["ADDRESS"]["VALUE"];
                    }



                    if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["PRICES_LINK"]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["PRICES_LINK"]["VALUE"]))
                    {
                        unset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"]);

                        unset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_CAN_BUY"]);

                        foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["PRICES_LINK"]["VALUE"] as $key => $value)
                        {
                         
                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"]["VALUES"][$value]["CAN_ACCESS"] === "Y")
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"][$value] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"]["VALUES"][$value]["CODE"];

                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE"]["VALUES"][$value]["CAN_BUY"] === "Y")
                                $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_CAN_BUY"][$value] = $value;

                            $hashCurrent .= $value;
                        };
                    }

                    if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["STORES_LINK"]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["STORES_LINK"]["VALUE"]))
                    {
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["STORES_LINK"]["VALUE"];

                        $tmpSort = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"];
                        sort($tmpSort);
                        $hashCurrent .= implode(";", $tmpSort);
                    }

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_VK"]["VALUE"])>0)
                        $hashCurrent .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["VK"]["VALUE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_VK"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_FACEBOOK"]["VALUE"])>0)
                        $hashCurrent .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["FB"]["VALUE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_FACEBOOK"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_TWITTER"]["VALUE"])>0)
                        $hashCurrent .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["TW"]["VALUE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_TWITTER"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_YOUTUBE"]["VALUE"])>0)
                        $hashCurrent .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["YOUTUBE"]["VALUE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_YOUTUBE"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_INSTAGRAM"]["VALUE"])>0)
                        $hashCurrent .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["INST"]["VALUE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_INSTAGRAM"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_TELEGRAM"]["VALUE"])>0)
                        $hashCurrent .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["TELEGRAM"]["VALUE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_TELEGRAM"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_OK"]["VALUE"])>0)
                        $hashCurrent .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["OK"]["VALUE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_OK"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_TIKTOK"]["VALUE"])>0)
                        $hashCurrent .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["TIKTOK"]["VALUE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["CONTACT_TIKTOK"]["VALUE"];




                    // hashApply
                    if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["PHONES"]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["PHONES"]["VALUE"]))
                    {
                        foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["PHONES"]["VALUE"] as $key => $value)
                        {
                            $hashApply .= $value.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["PHONES"]["DESCRIPTION"][$key]; 
                        }
                    }

                    if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["EMAILS"]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["EMAILS"]["VALUE"]))
                    {
                        foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["EMAILS"]["VALUE"] as $key => $value)
                        {
                            $hashApply .= $value.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["EMAILS"]["DESCRIPTION"][$key]; 
                        }
                    }


                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["REGION_TAG_MAP"]["VALUE"])>0)
                        $hashApply .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["REGION_TAG_MAP"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["ADDRESS"]["VALUE"])>0)
                        $hashApply .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["ADDRESS"]["VALUE"];


                    if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["PRICES_LINK"]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["PRICES_LINK"]["VALUE"]))
                    {
                        foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["PRICES_LINK"]["VALUE"] as $key => $value)
                        {
                            $hashApply .= $value;
                        }
                    }


                    if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["STORES_LINK"]["VALUE"]) && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["STORES_LINK"]["VALUE"]))
                    {

                        $tmpSort = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["STORES_LINK"]["VALUE"];
                        sort($tmpSort);
                        $hashApply .= implode(";", $tmpSort);
                    }


                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_VK"]["VALUE"])>0)
                        $hashApply .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_VK"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_FACEBOOK"]["VALUE"])>0)
                        $hashApply .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_FACEBOOK"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_TWITTER"]["VALUE"])>0)
                        $hashApply .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_TWITTER"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_YOUTUBE"]["VALUE"])>0)
                        $hashApply .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_YOUTUBE"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_INSTAGRAM"]["VALUE"])>0)
                        $hashApply .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_INSTAGRAM"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_TELEGRAM"]["VALUE"])>0)
                        $hashApply .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_TELEGRAM"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_OK"]["VALUE"])>0)
                        $hashApply .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_OK"]["VALUE"];

                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_TIKTOK"]["VALUE"])>0)
                        $hashApply .= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PROPERTIES"]["CONTACT_TIKTOK"]["VALUE"];



                    

                    $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["SUBMIT"] = md5($hashCurrent) !== md5($hashApply);


                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["REGION_ID"])
                    {

                        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["EMAIL_TO"]["VALUE"])>0)
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_TO"]['VALUE'] = htmlspecialcharsBack(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["EMAIL_TO"]["VALUE"]));

                        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["EMAIL_FROM"]["VALUE"])>0)
                            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_FROM"]['VALUE'] = htmlspecialcharsBack(trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"]["EMAIL_FROM"]["VALUE"]));
                    }

                }

                

                $flagRegion = 1;



            }
        }
    }

    public static function GeoIp_GetGeoData($ipAddress, $languageId = 'ru', $arCache = array('TAG' => 'geoDataByIp', 'PATH' => '', 'TIME' => 86400))
    {
        list($cacheTag, $cachePath, $cacheTime) = CPhoenixCache::_InitCacheParams('main', __FUNCTION__, $arCache);
        $obCache = new CPHPCache();
        $cacheID = __FUNCTION__.'_'.$cacheTag.md5($ipAddress.'_'.$languageId);
        if($obCache->InitCache($cacheTime, $cacheID, $cachePath)){
            $res = $obCache->GetVars();
            $arRes = $res['arRes'];
        }
        else{
            $arRes = array();
            $obBitrixGeoIPResult = \Bitrix\Main\Service\GeoIp\Manager::getDataResult($ipAddress, $languageId);
            if($obBitrixGeoIPResult !== \Bitrix\Main\Service\GeoIp\Manager::INFO_NOT_AVAILABLE){
                if($obResult = $obBitrixGeoIPResult->getGeoData()){
                    $arRes = get_object_vars($obResult);
                    CPhoenixCache::_SaveDataCache($obCache, $arRes, $cacheTag, $cachePath, $cacheTime, $cacheID);
                }
            }
        }

        return $arRes;
    }

    static function UpdateRegionSeoFiles($arFields) 
    {
    
        if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("concept.phoenix"))
            return;
    
        global $APPLICATION, $PHOENIX_TEMPLATE_ARRAY;
        $protocol = "https://";
        $robotsDir = "phoenix_regions/robots/";
    
        $arIBlock = CIBlock::GetList(array(), array("ID" => $arFields["IBLOCK_ID"]))->Fetch();
    
        if(substr_count(trim($arIBlock["CODE"]), "concept_phoenix_regions_"))
        {
            $siteID = str_replace("concept_phoenix_regions_", "", trim($arIBlock["CODE"]));
            
            if(!strlen($siteID))
                return;
    
            CPhoenix::phoenixOptionsValues($siteID, array(
                "start",
                "region"
            ));
    
            $regionsActive = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["ACTIVE"]["VALUES"]["ACTIVE"]["VALUE"];
            $mainDomain = self::clearDomain($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["VALUE"]);
    
            if($regionsActive == "Y" && strlen($mainDomain) > 0 && $arFields["IBLOCK_ID"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["IBLOCK_ID"])
            {
                $arSite = CSite::GetList($by, $sort, array("ACTIVE"=>"Y", "ID" =>  $siteID))->Fetch();
                $arSite['DIR'] = str_replace('//', '/', '/'.$arSite['DIR']);
    
                if(!strlen($arSite['DOC_ROOT']))
                    $arSite['DOC_ROOT'] = $_SERVER['DOCUMENT_ROOT'];
                
                $arSite['DOC_ROOT'] = str_replace('//', '/', $arSite['DOC_ROOT'].'/');
                $siteDir = str_replace('//', '/', $arSite['DOC_ROOT'].$arSite['DIR']);
    
    
                $arProperty = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], "sort", "asc", array("CODE" => "MAIN_DOMAIN"))->Fetch();
                $currentDomain = $arProperty["VALUE"];
                
                if($currentDomain)
                {
                    
                    $fileFrom = $siteDir.'main-robots.txt';
                    $fileTo = $siteDir.$robotsDir.'robots_'.$currentDomain.'.txt';

                    if(!file_exists($siteDir.$robotsDir)) 
                        mkdir($siteDir.$robotsDir, 0777, true);
                    
    
                    if(file_exists($fileFrom) && !file_exists($fileTo))
                    {
                        copy($fileFrom, $fileTo);
    
                        $arFile = file($fileTo);
    
                        $protocol = (self::checkDomainAvailible($protocol.$currentDomain)) ? "https://" : "http://";
    
                        foreach($arFile as $key => $str)
                        {
                            if(strpos($str, "Host" ) !== false)
                                $arFile[$key] = "Host: ".$protocol.$currentDomain."\r\n";
    
                            // if(strpos($str, "Sitemap" ) !== false)
                            // 	$arFile[$key] = "Sitemap: ".$protocol.$currentDomain."/".$xml_file."\r\n";
                        }
    
                        $strr = implode("", $arFile);
    
                        file_put_contents($fileTo, $strr);
                    }
                }
    
                
            }
    
        }
    
        
    }

}
