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
<?
global $PHOENIX_TEMPLATE_ARRAY;

$b404 = false;

/*if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] != "Y") 
    $b404 = true;*/

$section = strtoupper ( trim($arResult["VARIABLES"]["SECTION"]) );



$arSearchCodes = Array();
$arSearchCodes["CATALOG"] = array(
    "CODE" => "concept_phoenix_catalog",
    "TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_CATALOG_TIT"]
);

$arSearchCodes["BLOG"] = array(
    "CODE" => "concept_phoenix_blog",
    "TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_BLOG_TIT"]
);

$arSearchCodes["NEWS"] = array(
    "CODE" => "concept_phoenix_news",
    "TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_NEWS_TIT"]
);

$arSearchCodes["ACTIONS"] = array(
    "CODE" => "concept_phoenix_action",
    "TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_ACTIONS_TIT"]
);


$arSearchIn = Array();

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




if(!in_array($section, $arSearchIn))
	$b404 = true;


CPhoenix::set404($b404, $arParams);



$res = CIBlock::GetList(
    Array(), 
    Array(
        'CODE'=>$arSearchCodes[$section]["CODE"].'_'.SITE_ID
    ), false
);

while($ar_res = $res->Fetch())
{
    $arSearchCodes[$section]["ID"] = $ar_res["ID"];
}

?>

<div class="search-header phoenix-firsttype-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?> <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>" <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HEAD_BG"]["VALUE"] > 0):?>style="background-image: url('<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HEAD_BG"]["SETTINGS"]["SRC"]?>');"<?endif;?>>

    <div class="shadow-tone"></div>
    <div class="top-shadow"></div>

    <div class="container">
        <div class="row no-margin">
            <div class="col-12">
                <?$APPLICATION->IncludeComponent(
                    "concept:phoenix.search.line", 
                    ".default",
                    Array(
                        "START_PAGE" => $arResult["VARIABLES"]["SECTION"],
                        "CONTAINER_ID" => "search-page-input-container", "INPUT_ID" => "search-page-input",
                        "COMPOSITE_FRAME_MODE" => "N",
                        "SHOW_RESULTS" => ""/*$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['FASTSEARCH_ACTIVE']['VALUE']['ACTIVE']*/
                    )
                    
                );?>
            </div>
        </div>
    </div>

</div>

<div class="search-body <?=($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"])? "parent-tool-settings":"";?>">

    <div class="container">
        <div class="row">
            
            <?

                $module_id = "iblock";

                if(strlen($arResult["QUERY"]) > 0)
                {

                    $template = "news";

                    if($section == "CATALOG")
                        $template = "catalog";
                    
                    $APPLICATION->IncludeComponent(
                        "concept:phoenix.search.result",
                        $template,
                        Array(
                            "QUERY" => $arResult["QUERY"],
                            "MODULE_ID" => $module_id,
                            "PARAM2" => $arSearchCodes[$section]["ID"],
                            "CODE" => $section,
                            "VIEW" => "full",
                            "TITLE" => $arSearchCodes[$section]["TITLE"]
                        )
                    );
                }
                else
                {
                    echo "<div class='col-12'>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_START"]."</div>";
                }

            ?>

        </div>
    </div>
    
</div>