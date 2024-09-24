<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?global $PHOENIX_TEMPLATE_ARRAY;?>


<? 
global $result_search_count;
$result_search_count = 0;

$arSearchCodes = Array();


/**/

$arSearchCodes["CATALOG"] = array(
    "CODE" => "concept_phoenix_catalog",
    "NAME" => getMessage("PHOENIX_SEARCH_MAIN_CATALOG"),
    "TITLE" => getMessage("PHOENIX_SEARCH_MAIN_CATALOG_TIT"),
    "ICON_PATH" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["CATALOG_IC"]["SETTINGS"]["SRC"],
);

$arSearchCodes["BLOG"] = array(
    "CODE" => "concept_phoenix_blog",
    "NAME" => getMessage("PHOENIX_SEARCH_MAIN_BLOG"),
    "TITLE" => getMessage("PHOENIX_SEARCH_MAIN_BLOG_TIT"),
    "ICON_PATH" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["BLOG_IC"]["SETTINGS"]["SRC"],
);

$arSearchCodes["NEWS"] = array(
    "CODE" => "concept_phoenix_news",
    "NAME" => getMessage("PHOENIX_SEARCH_MAIN_NEWS"),
    "TITLE" => getMessage("PHOENIX_SEARCH_MAIN_NEWS_TIT"),
    "ICON_PATH" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["NEWS_IC"]["SETTINGS"]["SRC"],
);

$arSearchCodes["ACTIONS"] = array(
    "CODE" => "concept_phoenix_action",
    "NAME" => getMessage("PHOENIX_SEARCH_MAIN_ACTIONS"),
    "TITLE" => getMessage("PHOENIX_SEARCH_MAIN_ACTIONS_TIT"),
    "ICON_PATH" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["ACTIONS_IC"]["SETTINGS"]["SRC"],
);

$searchIn = ToUpper(trim($_REQUEST["searchIn"]));
$searchIn = explode(";", $searchIn);

$count = (!empty($searchIn)?count($searchIn):0);

if($count > 0)
{
    $arS = Array();

    foreach($searchIn as $key => $value) 
    {
        if(array_key_exists($value, $arSearchCodes))
            $arS[$value] = $arSearchCodes[$value];
    }

    $arSearchCodes = $arS;
}


$module_id = "iblock";

$arSearchIn = Array();
$arSearchID = Array();


if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_IN"]["VALUE"]))
{
    if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_IN"]["VALUE"]))
    {

        foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_IN"]["VALUE"] as $key=>$value)
        {
            if($value == "Y")
            {
                $arSearchIn[$key] = $key;
            }
        }
    }
}

foreach($arSearchCodes as $key => $value) 
{   

    $res = CIBlock::GetList(
        Array(), 
        Array(
            'CODE'=>$value["CODE"].'_'.SITE_ID
        ), false
    );

    while($ar_res = $res->Fetch())
    {
        $arSearchCodes[$key]["ID"] = $ar_res["ID"];
    }
    
}

if(!empty($arSearchIn))
{

    foreach($arSearchIn as $key => $value) 
        $arSearchID[$key] = $arSearchCodes[$key]["ID"];

}




if(strlen($arResult["QUERY"]) > 0)
{
    if(!empty($arSearchID))
    {
        foreach($arSearchID as $key => $ID) 
        {
            $template = "news";

            if($key == "CATALOG")
                $template = "catalog";

            $APPLICATION->IncludeComponent(
                "concept:phoenix.search.result",
                $template,
                Array(
                    "QUERY" => $arResult["QUERY"],
                    "MODULE_ID" => $module_id,
                    "PARAM2" => $ID,
                    "CODE" => $key,
                    "VIEW" => "short",
                    "TITLE" => $arSearchCodes[$key]["TITLE"],
                    "MODE" => "ajax",
                    "LINE_VIEW_SIZE" => (isset($_REQUEST["LINE_VIEW_SIZE"]) && strlen($_REQUEST["LINE_VIEW_SIZE"])) ? $_REQUEST["LINE_VIEW_SIZE"] : ""
                )
            );
        }
    }

}
?>
