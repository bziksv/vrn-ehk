<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */

global $APPLICATION;
global $USER;
global $DB, $PHOENIX_TEMPLATE_ARRAY;

global $CACHE_MANAGER;

use Bitrix\Main\Context,
    Bitrix\Main\Type\DateTime,
    Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Main\Page\Asset as Asset;



$domen = CPhoenixHost::getHost($_SERVER);
$region_id = strval(intval($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"]));


$arResult = Array();  

$arResult["H1"] = $GLOBALS['APPLICATION']->GetTitle(false);
$arResult["TITLE"] = $GLOBALS['APPLICATION']->GetPageProperty("title");
$arResult["KEYWORDS"] = $GLOBALS['APPLICATION']->GetPageProperty("keywords");
$arResult["DESCRIPTION"] = $GLOBALS['APPLICATION']->GetPageProperty("description");

if(strlen($GLOBALS["H1"]) > 0)
    $arResult["H1"] = $GLOBALS["H1"];
    
if(strlen($GLOBALS["TITLE"]) > 0)
    $arResult["TITLE"] = $GLOBALS["TITLE"];
    
if(strlen($arResult["TITLE"]) <= 0)
    $arResult["TITLE"] = $arResult["H1"];
    
if(strlen($GLOBALS["KEYWORDS"]) > 0)
    $arResult["KEYWORDS"] = $GLOBALS["KEYWORDS"];
    
if(strlen($GLOBALS["DESCRIPTION"]) > 0)
    $arResult["DESCRIPTION"] = $GLOBALS["DESCRIPTION"];


$url = explode("?", $_SERVER["REQUEST_URI"]);
$url = trim(str_replace('index.php', "", $url[0]));
$pageUrl = $domen.$url;


global $DB;

$strSql = "SHOW TABLES LIKE 'phoenix_seo'";
$res = $DB->Query($strSql, false, $err_mess.__LINE__);

if($res->SelectedRowsCount() > 0)
{

    $strSql = "SELECT * FROM phoenix_seo WHERE url='".$url."' AND site_id='".SITE_ID."' AND region_id='".$DB->ForSql($region_id)."' LIMIT 1";
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);

    if($res->SelectedRowsCount() <= 0)
    {
        $strSql = "SELECT * FROM phoenix_seo WHERE url='".$url."' AND site_id='".SITE_ID."' AND region_id=0 LIMIT 1"; 
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    }

    if($res->SelectedRowsCount() <= 0)
    {
        $strSql = "SELECT * FROM phoenix_seo WHERE url='".$url."' AND site_id='".SITE_ID."' AND region_id IS NULL LIMIT 1"; 
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    }
    
    if($res->SelectedRowsCount() > 0)
    {
        $ar_result = $res->GetNext();

        if(strlen(trim($ar_result["region_id"])) <= 0)
        {
            $strSql = "UPDATE phoenix_seo SET region_id='0' WHERE id='".$ar_result["id"]."'";
            $DB->Query($strSql, false, $err_mess.__LINE__);

            $ar_result["region_id"] = 0;
        }  
        
        $arResult["REGION_ID"] = $ar_result["region_id"];


        
        $arResult["NOINDEX"] = $ar_result["noindex"];
        
        $arResult["NEW_H1"] = trim(htmlspecialcharsBack($ar_result["h1"]));
        $arResult["NEW_TITLE"] = trim(htmlspecialcharsBack($ar_result["title"]));
        $arResult["NEW_KEYWORDS"] = trim(htmlspecialcharsBack($ar_result["keywords"]));
        $arResult["NEW_DESCRIPTION"] = trim(htmlspecialcharsBack($ar_result["description"]));
        
        
        $GLOBALS["OG_TITLE"] = $arResult["OG_TITLE"] = trim(htmlspecialcharsBack($ar_result["og_title"]));
        $GLOBALS["OG_DESCRIPTION"] = $arResult["OG_DESCRIPTION"] = trim(htmlspecialcharsBack($ar_result["og_description"]));
        $GLOBALS["OG_IMAGE"] = $arResult["OG_IMAGE"] = $ar_result["og_image"];
        $arResult["OG_URL"] = $ar_result["og_url"];
        $arResult["OG_TYPE"] = $ar_result["og_type"];
        
        if($arResult["OG_IMAGE"] > 0)
        {
            $rsFile = CFile::GetByID($arResult["OG_IMAGE"]);
            $arFile = $rsFile->Fetch();
             
            $GLOBALS["OG_IMAGE_PATH"] = $arResult["OG_IMAGE_PATH"] = $serv.CFile::GetPath($arResult["OG_IMAGE"]);

            $arResult["OG_IMAGE_NAME"] = $arFile["ORIGINAL_NAME"];

            if(SITE_CHARSET == "windows-1251")
                $arResult["OG_IMAGE_NAME"] = utf8win1251($arFile["ORIGINAL_NAME"]);
            
        }
        
        
        $arResult["META_TAGS"] = unserialize(base64_decode($ar_result["meta_tags"]));
        
    }
    
    
    
    if($arResult["NOINDEX"] == 1)
        $GLOBALS['APPLICATION']->AddHeadString('<meta name="robots" content="noindex, nofollow" />');
    
    if(!empty($arResult["META_TAGS"]))
    {
        foreach($arResult["META_TAGS"] as $tag)
            $GLOBALS['APPLICATION']->AddHeadString(htmlspecialcharsBack($tag));
    }
    
    //og tags
    
    if(strlen($arResult["OG_URL"]) > 0)
        $ogUrl = $arResult["OG_URL"];
    else
        $ogUrl = $pageUrl;
    
    $string = '<meta property="og:url" content="'.$ogUrl.'" />';
    $APPLICATION->AddHeadString($string, false, true);


    
    if(strlen($arResult["OG_TYPE"]) > 0)
        $ogType = $arResult["OG_TYPE"];
    else
        $ogType = "website";
   
    $string = '<meta property="og:type" content="'.$ogType.'" />';
    $APPLICATION->AddHeadString($string, false, true);
    
    
    
    if(strlen($arResult["NEW_TITLE"]) > 0)
        $GLOBALS["TITLE"] = $arResult["NEW_TITLE"];
    
    if(strlen($arResult["NEW_KEYWORDS"]) > 0)
        $GLOBALS["KEYWORDS"] = $arResult["NEW_KEYWORDS"];
    
    if(strlen($arResult["NEW_DESCRIPTION"]) > 0)
        $GLOBALS["DESCRIPTION"] = $arResult["NEW_DESCRIPTION"];
    
    if(strlen($arResult["NEW_H1"]) > 0)
        $GLOBALS["H1"] = $arResult["NEW_H1"];  
    
    

}  

    $this->includeComponentTemplate();

?>