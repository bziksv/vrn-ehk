<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
set_time_limit(0);

global $DB;

$strSql = "SHOW TABLES LIKE 'phoenix_seo'";
$res = $DB->Query($strSql, false, $err_mess.__LINE__);

if($res->SelectedRowsCount() <= 0)
{
    $strSql = 
    "
        CREATE TABLE
            `phoenix_seo` (
                `id` INT(115) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `site_id` VARCHAR(10) NULL DEFAULT NULL,
                `url` VARCHAR(500) NULL DEFAULT NULL,
                `region_id` INT(11) NULL DEFAULT NULL,
                `noindex` INT(11) NULL DEFAULT NULL,
                `title` VARCHAR(500) NULL DEFAULT NULL,
                `keywords` VARCHAR(500) NULL DEFAULT NULL,
                `description` VARCHAR(500) NULL DEFAULT NULL,
                `h1` VARCHAR(500) NULL DEFAULT NULL,
                `og_title` VARCHAR(500) NULL DEFAULT NULL,
                `og_description` VARCHAR(500) NULL DEFAULT NULL,
                `og_image` VARCHAR(500) NULL DEFAULT NULL,
                `og_url` VARCHAR(500) NULL DEFAULT NULL,
                `og_type` VARCHAR(500) NULL DEFAULT NULL,
                `meta_tags` TEXT NULL DEFAULT NULL
            )            
    ";
    
    $DB->Query($strSql, false, $err_mess.__LINE__);

}    



/*$url = WIZARD_SITE_DIR;

$strSql = "INSERT INTO kraken_seo (url, site_id, noindex) VALUES ('".$url."','".WIZARD_SITE_ID."', 1)";
$DB->Query($strSql, false, $err_mess.__LINE__);

foreach($codes as $code)
{
    $url = WIZARD_SITE_DIR.$code."/";
    
    $strSql = "INSERT INTO kraken_seo (url, site_id, noindex) VALUES ('".$url."','".WIZARD_SITE_ID."', 1)";
    $DB->Query($strSql, false, $err_mess.__LINE__);
}


include("lang/ru/lang_seo.php");

//main_page
$arIMAGE = Array();

$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/seo_images/main.jpg");
$arIMAGE["MODULE_ID"] = "concept.phoenix";

$fid = CFile::SaveFile($arIMAGE, "phoenix");
$fid = intval($fid);


$url = WIZARD_SITE_DIR;

$strSql = "UPDATE kraken_seo SET title='".$MESS["KRAKEN_SEO_MAIN_TITLE"]."', keywords='".$MESS["KRAKEN_SEO_MAIN_KEYWORDS"]."', description='".$MESS["KRAKEN_SEO_MAIN_DESCRIPTION"]."', og_title='".$MESS["KRAKEN_SEO_MAIN_OG_TITLE"]."', og_description='".$MESS["KRAKEN_SEO_MAIN_OG_DESCRIPTION"]."', og_image='".$fid."' WHERE url = '".$url."' AND site_id='".WIZARD_SITE_ID."'";
$DB->Query($strSql, false, $err_mess.__LINE__);*/


?>