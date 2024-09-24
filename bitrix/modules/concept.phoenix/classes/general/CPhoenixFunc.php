<?
if(!defined('CONCEPT_PHOENIX_MODULE_ID')){
    define('CONCEPT_PHOENIX_MODULE_ID', 'concept.phoenix');
}

class CPhoenixFunc{

    public static function setInitialFilterParams($nameKey = '')
    {
    	if(strlen($nameKey)<=0)
    		return;

        
    	$GLOBALS[$nameKey][] = array(
            "LOGIC" => "OR",
            array("SECTION_GLOBAL_ACTIVE" => "Y"),
            array("SECTION_ID" => 0),
        );

    	$GLOBALS[$nameKey]["SECTION_SCOPE"]="IBLOCK";
    	$GLOBALS[$nameKey]["!PROPERTY_MODE_HIDE_VALUE"]="Y";
       
        $GLOBALS[$nameKey] = array_merge($GLOBALS[$nameKey], CPhoenix::getFilterByRegion());

        $arStoresFilter = CPhoenix::getFilterByStores();

        if(!empty($arStoresFilter))
            $GLOBALS[$nameKey][] = $arStoresFilter;

        return $arFilter;
    }
    public static function setUTM($siteID)
    {
        global $APPLICATION;
        $url = basename($_SERVER['REQUEST_URI']);
        $url_components = parse_url($url); 
        parse_str($url_components['query'], $params);

        if(isset($params['utm_source']) || isset($params['utm_medium']) || isset($params['utm_campaign']) || isset($params['utm_content']) || isset($params['utm_term']))
        {
            $APPLICATION->set_cookie("UTM_SOURCE_".$siteID, "", -1, "/", false ,false,true,"phoenix");

            $APPLICATION->set_cookie("UTM_MEDIUM_".$siteID, "", -1, "/", false ,false,true,"phoenix");

            $APPLICATION->set_cookie("UTM_CAMPAIGN_".$siteID, "", -1, "/", false ,false,true,"phoenix");

            $APPLICATION->set_cookie("UTM_CONTENT_".$siteID, "", -1, "/", false ,false,true,"phoenix");

            $APPLICATION->set_cookie("UTM_TERM_".$siteID, "", -1, "/", false ,false,true,"phoenix");
        }

        if(isset($params['utm_source']))
            $APPLICATION->set_cookie("UTM_SOURCE_".$siteID, $params['utm_source'], time()+60*60*24*14, "/", false ,false,true,"phoenix");

        if(isset($params['utm_medium']))
            $APPLICATION->set_cookie("UTM_MEDIUM_".$siteID, $params['utm_medium'], time()+60*60*24*14, "/", false ,false,true,"phoenix");

        if(isset($params['utm_campaign']))
            $APPLICATION->set_cookie("UTM_CAMPAIGN_".$siteID, $params['utm_campaign'], time()+60*60*24*14, "/", false ,false,true,"phoenix");

        if(isset($params['utm_content']))
            $APPLICATION->set_cookie("UTM_CONTENT_".$siteID, $params['utm_content'], time()+60*60*24*14, "/", false ,false,true,"phoenix");

        if(isset($params['utm_term']))
            $APPLICATION->set_cookie("UTM_TERM_".$siteID, $params['utm_term'], time()+60*60*24*14, "/", false ,false,true,"phoenix");
    }

    public static function getContentFromUrl($url){

        $data = file_get_contents($url);

        if ($data === false){

            if (function_exists('curl_init')){

                $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_TIMEOUT, 5);
                $data = curl_exec($curl);
                curl_close($curl);

            }

        }

        return $data;

    }
}