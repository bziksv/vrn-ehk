<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
header('Content-type: application/json');

global $APPLICATION, $PHOENIX_TEMPLATE_ARRAY;



CModule::IncludeModule("concept.phoenix");

CPhoenix::getIblockIDs(array(
    "SITE_ID"=>$site_id, 
    "CODES" => array(
        "concept_phoenix_regions_".$site_id,
        )
    )
);

CPhoenix::phoenixOptionsValues($site_id, array(
    "start",
	"design",
    "region"
));

$arRes = array(
    'OK'=>'Y',
    'HREF'=>''
);

$id = trim($_POST["id"]);
$serverName = trim($_POST["serverName"]);
$curPageUrl = trim($_POST["curPageUrl"]);
$submit = trim($_POST["submit"]);

preg_match("/_location/", $id, $out);


$regionID = "";

$arRegionID =array();

if(!empty($out))
{
    $regionID = str_replace("_location", "", $id);
    $arRegionID = CPhoenixRegionality::getRegionByLocationId($regionID);
}

else
{
    $out = explode("_", $id);
    $regionID = $out[0];
    $locationID = (isset($out[1])?$out[1]:0);
    $arRegionID = CPhoenixRegionality::getRegionById($regionID, $locationID);
}



$domen = CPhoenixHost::getHost($_SERVER, "N", "Y");

$arUrl = CPhoenixRegionality::getRegionUrl($arRegionID, $curPageUrl, $domen);

$arRegion = CPhoenixRegionality::getRegionInfo($arRegionID);


$arRes['NAME_LOCATION'] = $arRegion['NAME'];
$arRes['ID_LOCATION'] = $arRegionID["REGION_ID"]."_".$arRegionID["LOCATION_ID"];

if(trim(SITE_CHARSET) == "windows-1251")
    $arRes['NAME_LOCATION'] = iconv('windows-1251', 'UTF-8', $arRegion['NAME']);



if($domen != $arUrl["URL"] || $submit == "Y")
    $arRes["HREF"] = $arUrl["HREF"];

$APPLICATION->set_cookie("CurrentRegion_".$site_id, $arRegionID["REGION_ID"], time()+60*60*24*3, "/", $serverName,false,true,"phoenix");
$APPLICATION->set_cookie("CurrentLocation_".$site_id, $arRegionID["LOCATION_ID"], time()+60*60*24*3, "/", $serverName,false,true,"phoenix");


echo json_encode($arRes);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");