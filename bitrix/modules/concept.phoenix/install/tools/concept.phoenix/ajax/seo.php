<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$arResult["OK"] = "N";
$moduleID = "concept.phoenix";
CModule::IncludeModule($moduleID);
global $DB, $PHOENIX_TEMPLATE_ARRAY;


CPhoenix::phoenixOptionsValues($site_id, array(
    "start",
    "region"
));

if(!$PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"])
    die();

$strSql = "SHOW TABLES LIKE 'phoenix_seo'";
$res = $DB->Query($strSql, false, $err_mess.__LINE__);

if($res->SelectedRowsCount() > 0 && $_POST["send"] == "Y")
{

    $update = false;

    if($_POST["phoenix_region_default"] === 'Y' && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['ACTIVE']["VALUE"]["ACTIVE"] == "Y")
        $region_id = 0;

    else
    {
        $region = CPhoenixRegionality::getRegionCurrent();
        $region_id = strval(intval($region["ID"]));
    }
    

    
    
    $url = trim(htmlspecialcharsbx(str_replace('index.php', "", $_POST["seourl"])));

    $WHERE = "url='".$DB->ForSql($url)."' AND site_id='".SITE_ID."' AND region_id=".$DB->ForSql($region_id)."";
    
    if(SITE_CHARSET == "windows-1251")
    {
        if(!empty($_POST))
        {
            foreach($_POST as $key => $value)
            {
                if(is_array($value))
                {
                    foreach($value as $k=>$val)
                        $value[$k] = utf8win1251($val);
                        
                    $_POST[$key] = $value;
                        
                }
                else
                {
                    $_POST[$key] = utf8win1251($value);
                }
            }
        }
    }
    
    
    
    
    
    $strSql = "SELECT * FROM phoenix_seo WHERE ".$WHERE." LIMIT 1";
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);

    if($res->SelectedRowsCount() > 0)
        $update = true;
        
    
    if($update == false)
    {
        $strSql = "INSERT INTO phoenix_seo (url, site_id, region_id, noindex) VALUES ('".$DB->ForSql($url)."','".SITE_ID."', ".$DB->ForSql($region_id).", 0)";
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    }
    
    
    $noindex = trim(htmlspecialcharsbx($_POST["phoenix_seo_noindex"]));
    
    if(strlen($noindex) <= 0)
        $noindex = 0;
    
    $strSql = "UPDATE phoenix_seo SET noindex='".$DB->ForSql($noindex)."' WHERE ".$WHERE;
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    
    
    
    $h1 = trim(htmlspecialcharsEx(strip_tags($_POST["phoenix_seo_h1"])));
    
    $strSql = "UPDATE phoenix_seo SET h1='".$DB->ForSql($h1)."' WHERE ".$WHERE;
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    
    
    $title = trim(htmlspecialcharsEx(strip_tags($_POST["phoenix_seo_title"])));
    
    $strSql = "UPDATE phoenix_seo SET title='".$DB->ForSql($title)."' WHERE ".$WHERE;
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    
    
    
    $description = trim(htmlspecialcharsEx(strip_tags($_POST["phoenix_seo_description"])));
    
    $strSql = "UPDATE phoenix_seo SET description='".$DB->ForSql($description)."' WHERE ".$WHERE;
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    
    
    $keywords = trim(htmlspecialcharsEx(strip_tags($_POST["phoenix_seo_keywords"])));
    
    $strSql = "UPDATE phoenix_seo SET keywords='".$DB->ForSql($keywords)."' WHERE ".$WHERE;
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    
    
    $og_title = trim(htmlspecialcharsEx(strip_tags($_POST["phoenix_seo_og_title"])));
    
    $strSql = "UPDATE phoenix_seo SET og_title='".$DB->ForSql($og_title)."' WHERE ".$WHERE;
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    
    
    $og_description = trim(htmlspecialcharsEx(strip_tags($_POST["phoenix_seo_og_description"])));
    
    $strSql = "UPDATE phoenix_seo SET og_description='".$DB->ForSql($og_description)."' WHERE ".$WHERE;
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    
    
    $og_url = trim(htmlspecialcharsbx($_POST["phoenix_seo_og_url"]));
    
    $strSql = "UPDATE phoenix_seo SET og_url='".$DB->ForSql($og_url)."' WHERE ".$WHERE;
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    
    
    $og_type = trim(htmlspecialcharsbx($_POST["phoenix_seo_og_type"]));
    
    $strSql = "UPDATE phoenix_seo SET og_type='".$DB->ForSql($og_type)."' WHERE ".$WHERE;
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    
    
    if(strlen($_FILES["phoenix_seo_og_image"]["name"]) > 0)
    {
        
        $arIMAGE = $_FILES["phoenix_seo_og_image"];
        $arIMAGE["old_file"] = htmlspecialcharsbx($_POST["imageogimage"]);
        $arIMAGE["MODULE_ID"] = "concept.phoenix";
        $fid = CFile::SaveFile($arIMAGE, "phoenix");
        CFile::Delete($arIMAGE["old_file"]);

        if(SITE_CHARSET == "windows-1251")
            $arIMAGE["name"] = utf8win1251($arIMAGE["name"]);
        

        $strSql = "UPDATE phoenix_seo SET og_image='".$DB->ForSql($fid)."' WHERE ".$WHERE;
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);

    }
    elseif($_POST["phoenix_seo_og_image_del"] == 'Y' && strlen(htmlspecialcharsbx($_FILES["phoenix_seo_og_image"]["name"])) <= 0)
    {
        CFile::Delete(htmlspecialcharsbx($_POST['imageogimage']));
        
        
        $strSql = "UPDATE phoenix_seo SET og_image='' WHERE ".$WHERE;
        $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    }
    
    
    
    
    $meta_tags = $_POST["phoenix_other_meta"];

    if(is_array($meta_tags) && !empty($meta_tags))
    {
        array_shift($meta_tags);
    
        $meta = Array();
    
    
        foreach($meta_tags as $key=>$value)
        {
            if(strlen($value) > 0)
                $meta[] = htmlspecialcharsbx($value);
        }
    }
    
    $meta_tags = base64_encode(serialize($meta));
    
    $strSql = "UPDATE phoenix_seo SET meta_tags='".$DB->ForSql($meta_tags)."' WHERE ".$WHERE;
    $res = $DB->Query($strSql, false, $err_mess.__LINE__);
    
    
    $arResult["OK"] = "Y";
        
    
}


    
$arResult = json_encode($arResult);
echo $arResult;   
?>