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

class CPhoenixMainHandlers{ 

    public static function OnBeforeUserAddHandler(&$arFields)
    {
        $siteID = SITE_ID;
        $res = false;

        $rsTemplates = CSite::GetTemplateList($siteID);

        while($arTemplate = $rsTemplates->Fetch())
        {
            if(substr_count($arTemplate["TEMPLATE"], "concept_phoenix_".$siteID) > 0)
                $res = true;
        }

        if($res)
        {
            $rsUser = CUser::GetByLogin($arFields["EMAIL"]);
            if($rsUser->SelectedRowsCount() <= 0)
            {
                $arFields["LOGIN"] = $arFields["EMAIL"];
            }
        }
    }

    public static function OnAfterUserAddHandler(&$arFields)
    {
        
        if(strlen($arFields["EMAIL"])>0 && strlen($arFields["RESULT"])>0)
        {

            $siteID = SITE_ID;
            $res = false;


            
            $rsTemplates = CSite::GetTemplateList($siteID);

            while($arTemplate = $rsTemplates->Fetch())
            { 
                if(substr_count($arTemplate["TEMPLATE"], "concept_phoenix_".$siteID) > 0)
                    $res = true;
            }

            if($res)
            {

                if(strlen($arFields["EMAIL"]) > 0)
                {
                    $rsUser = CUser::GetByLogin($arFields["EMAIL"]);

                    if($rsUser->SelectedRowsCount() <= 0)
                        $arFields["LOGIN"]=$arFields["EMAIL"];
                    

                    global $PHOENIX_TEMPLATE_ARRAY;

                    CPhoenix::phoenixOptionsValues($siteID, array(
                        "start",
                        "lids",
                        "other"
                    ));



                
                    $arFields["EMAIL_FROM"]=$PHOENIX_TEMPLATE_ARRAY["SITE_EMAIL"];
                    $arFields["EMAIL_TO"]=$arFields["EMAIL"];
                    $arFields["SITENAME"]=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["NAME_SITE"]["VALUE"];
                    $arFields["SITE_URL"]=$PHOENIX_TEMPLATE_ARRAY["SITE_URL"];

                    $tmppassword = $arFields["PASSWORD"];
                    $arFields["PASSWORD"]=$arFields["CONFIRM_PASSWORD"];
                    

                    $event = new CEvent;
                    $event->Send("PHOENIX_SEND_REG_SUCCESS_".$siteID, $siteID, $arFields);
                    $arFields["PASSWORD"] = $tmppassword;
                }
            }
        }
        
    }
    
    public static function PhoenixOnEpilogHandler() 
    {
        
        global $PHOENIX_TEMPLATE_ARRAY;
        
        if(empty($PHOENIX_TEMPLATE_ARRAY))
            return;
        
        if(strlen($title) <= 0)
            $title = $GLOBALS['APPLICATION']->GetPageProperty("title");
        
        if(strlen($title) <= 0)
            $title = $GLOBALS['APPLICATION']->GetDirProperty("title");
        
        if(strlen($GLOBALS['TITLE']) > 0)
            $title = $GLOBALS['TITLE']; 
        
        if(strlen($title) <= 0)
            $title = $GLOBALS['APPLICATION']->GetTitle();

        $title = CPhoenix::prepareText(html_entity_decode($title));

        $rsSites = CSite::GetByID($siteId);
        $arSite = $rsSites->Fetch();

        
        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["ADD_SITE_TITLE"]["VALUE"]["ACTIVE"] == "Y" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["NAME_SITE"]["VALUE"])>0)
            $title = $title." &mdash; ".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["NAME_SITE"]["VALUE"];

        
        $GLOBALS['APPLICATION']->SetPageProperty('title', $title);

    
    
        if(!empty($_GET) && is_array($_GET))
        {
            foreach($_GET as $key=>$value)
            {
                if(substr_count($key, "PAGEN_") > 0 && $value > 1)
                {
                    $GLOBALS['APPLICATION']->SetPageProperty('title', $title.Loc::GetMessage("PHOENIX_META_PAGE").intval($value).'');
                }
            }
        }

        
        

        if(strlen($GLOBALS['KEYWORDS']) > 0)    
            $GLOBALS['APPLICATION']->SetPageProperty('keywords', CPhoenix::prepareText($GLOBALS['KEYWORDS']));
        
        if(strlen($GLOBALS['DESCRIPTION']) > 0)
        {
            if(!empty($_GET) && is_array($_GET))
            {
                foreach($_GET as $key=>$value)
                {
                    if(substr_count($key, "PAGEN_") > 0 && $value > 1)
                    {
                        $GLOBALS['APPLICATION']->SetPageProperty('description', CPhoenix::prepareText($GLOBALS['DESCRIPTION']).Loc::GetMessage("PHOENIX_META_PAGE").intval($value).'');
                    }
                }
            }
            else 
            {
                $GLOBALS['APPLICATION']->SetPageProperty('description', CPhoenix::prepareText($GLOBALS['DESCRIPTION']));
            }
        }
          
        
        if(strlen($GLOBALS["OG_TITLE"]) <= 0)
            $GLOBALS["OG_TITLE"] = $title;
        
        if(strlen($GLOBALS["OG_TITLE"]) > 0)
        {
            $string = '<meta property="og:title" content="'.CPhoenix::prepareText($GLOBALS["OG_TITLE"]).'" />';
            $GLOBALS['APPLICATION']->AddHeadString($string, false, true);
        }
        
        if(strlen($GLOBALS["OG_DESCRIPTION"]) <= 0)
            $GLOBALS["OG_DESCRIPTION"] = $GLOBALS['DESCRIPTION'];

        if(strlen($GLOBALS["OG_DESCRIPTION"]) > 0)
        {
            $string = '<meta property="og:description" content="'.CPhoenix::prepareText($GLOBALS["OG_DESCRIPTION"]).'" />';
            $GLOBALS['APPLICATION']->AddHeadString($string, false, true);
        }

        $domen = CPhoenixHost::getHost($_SERVER);
        if(strlen($GLOBALS["OG_IMAGE"]) <= 0)
            $ogImageSrc = $GLOBALS["OG_IMAGE_DEF"];
        
        else
            $ogImageSrc = $GLOBALS["OG_IMAGE_PATH"];
       


        if(strlen($ogImageSrc)>0)
        {
            $string = '
            <link rel="image_src" href="'.$domen.$ogImageSrc.'">
            <meta property="og:image" content="'.$domen.$ogImageSrc.'" />';
            $GLOBALS['APPLICATION']->AddHeadString($string, false, true);
        }

        
        if(strlen($GLOBALS['H1']) > 0)
        {
            $h1 = CPhoenix::prepareText(html_entity_decode($GLOBALS['H1']), false);
            $GLOBALS['APPLICATION']->SetTitle($h1, false);
        }
    }

    static function OnEndBufferContentHandler(&$content)
    {

        if($_POST['ajax_seo']!='Y' && !defined('ADMIN_SECTION') && !defined('WIZARD_SITE_ID') && (!preg_match("/^\/bitrix\//", $_SERVER['REQUEST_URI'])))
        {
            global $PHOENIX_TEMPLATE_ARRAY;
            
            if(empty($PHOENIX_TEMPLATE_ARRAY))
                return;

            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["REGION_ID"])
            {
                $arTagSeoMarks = array();
                foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PROPERTIES"] as $key => $value)
                {
                    if(strpos($key, 'REGION_TAG_MAP') !== false)
                        continue;

                    if(strpos($key, 'REGION_TAG_') !== false){
                        $content = str_replace('#'.$key.'#', $value["~VALUE"], $content);
                    }
                }
            }
            else
            {
            	$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REGION']["IBLOCK_ID"]));

				while ($prop_fields = $properties->GetNext())
				{
					if(strpos($prop_fields["CODE"], 'REGION_TAG_') !== false){
						$content = str_replace('#'.$prop_fields["CODE"].'#', '', $content);
					}
				}
            }
        }
       
        
    }
}
?>