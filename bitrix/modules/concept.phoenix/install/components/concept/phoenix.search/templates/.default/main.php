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

/*
if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] != "Y") 
    $b404 = true;

CPhoenix::set404($b404, $arParams);*/


global $result_search_count;
$result_search_count = 0;

$arSearchCodes = Array();


/**/

$arSearchCodes["CATALOG"] = array(
    "CODE" => "concept_phoenix_catalog",
    "NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_CATALOG"],
    "TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_CATALOG_TIT"],
    "ICON_PATH" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["CATALOG_IC"]["SETTINGS"]["SRC"],
);

$arSearchCodes["BLOG"] = array(
    "CODE" => "concept_phoenix_blog",
    "NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_BLOG"],
    "TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_BLOG_TIT"],
    "ICON_PATH" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["BLOG_IC"]["SETTINGS"]["SRC"],
);

$arSearchCodes["NEWS"] = array(
    "CODE" => "concept_phoenix_news",
    "NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_NEWS"],
    "TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_NEWS_TIT"],
    "ICON_PATH" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["NEWS_IC"]["SETTINGS"]["SRC"],
);

$arSearchCodes["ACTIONS"] = array(
    "CODE" => "concept_phoenix_action",
    "NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_ACTIONS"],
    "TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_ACTIONS_TIT"],
    "ICON_PATH" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["ACTIONS_IC"]["SETTINGS"]["SRC"],
);



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

if(!empty($arSearchCodes))
{
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
}
if(!empty($arSearchIn))
{
    foreach($arSearchIn as $key => $value) 
        $arSearchID[$key] = $arSearchCodes[$key]["ID"];
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
                	array(
                		"CONTAINER_ID" => "search-page-input-container",
                		"INPUT_ID" => "search-page-input",
                		"COMPONENT_TEMPLATE" => ".default",
                		"START_PAGE" => "none",
                        "SHOW_RESULTS" => ""/*$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['FASTSEARCH_ACTIVE']['VALUE']['ACTIVE']*/,
                		"COMPOSITE_FRAME_MODE" => "N"
                	),
                	false
                );?>
            </div>  
        </div>
    </div>

</div>


<div class="search-body <?=($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"])? "parent-tool-settings":"";?>">

    <div class="container">
        <div class="row">

            <div class="col-12 desc-found">

                <?$APPLICATION->ShowViewContent('result_search_count');?>

            </div>

            <div class="clearfix"></div>

            <div class="sections-main row no-margin">

                <?
                
                    if(strlen($arResult["QUERY"]) > 0)
                    {
                        if(!empty($arSearchID))
                        {
                            foreach($arSearchID as $key => $ID) 
                            {
                                $APPLICATION->IncludeComponent(
                                    "concept:phoenix.search.result",
                                    "sections",
                                    Array(
                                        "QUERY" => $arResult["QUERY"],
                                        "MODULE_ID" => $module_id,
                                        "PARAM2" => $ID,
                                        "CODE" => $key,
                                        "SECTION_NAME" => trim($arSearchCodes[$key]["NAME"]),
                                        "SECTION_ICON" => trim($arSearchCodes[$key]["ICON_PATH"]),

                                    )
                                );
                            }
                        }

                        $this->SetViewTarget('result_search_count');

                        if($result_search_count > 0)
                        {

                            echo $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_QUERY_VALUE_TITLE"]
                                ." &laquo;"
                                .$arResult["QUERY"]
                                ."&raquo; "
                                .CPhoenix::getTermination($result_search_count, array($PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_FOUND_0"], $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_FOUND_1"],$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_FOUND_0"], $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_FOUND_0"]))

                            ." ".$result_search_count

                            ." ".CPhoenix::getTermination($result_search_count, array($PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_RESULT_2"], $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_RESULT_0"],$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_RESULT_1"], $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_RESULT_2"]));
                        }
                        else
                            echo $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_QUERY_VALUE_TITLE"]
                                ." &laquo;"
                                .$arResult["QUERY"]
                                ."&raquo; "
                                .$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_RESULT_NOT_FOUND"];

                        $this->EndViewTarget();


                    }
                    else
                    {
                        echo "<div class='col-12'>".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_MAIN_START"]."</div>";
                    }
                ?>
             
             
            </div>
            
            <?
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
                                    "TITLE" => $arSearchCodes[$key]["TITLE"]
                                )
                            );
                        }
                    }
                }
            ?>

        </div>
    </div>
    
</div>
