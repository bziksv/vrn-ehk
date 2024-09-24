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

$arSearchIn = Array();

if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_IN"]["VALUE"]))
{
    if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_IN"]["VALUE"]))
    {
        foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_IN"]["VALUE"] as $key=>$value)
        {
            if($value == "Y")
                $arSearchIn[$key] = $key;
        }
    }
}



$query = htmlspecialcharsbx(trim($_REQUEST["q"]));


$hint = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_DEFAULT"]["VALUE"];


if($arParams["START_PAGE"] == "catalog" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_CATALOG"]["VALUE"]) > 0)
    $hint = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_CATALOG"]["VALUE"];

if($arParams["START_PAGE"] == "blog" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_BLOG"]["VALUE"]) > 0)
    $hint = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_BLOG"]["VALUE"];

if($arParams["START_PAGE"] == "news" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_NEWS"]["VALUE"]) > 0)
    $hint = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_NEWS"]["VALUE"];

if($arParams["START_PAGE"] == "actions" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_ACTIONS"]["VALUE"]) > 0)
    $hint = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_ACTIONS"]["VALUE"];



$arSearchResult = Array();

$arSearchResult["ALL"] = Array();

$arSearchResult["ALL"]["NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_ALL"];
$arSearchResult["ALL"]["PAGE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"];


$arSearchResult["CATALOG"] = Array();

$arSearchResult["CATALOG"]["NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_IN_CATALOG"];
$arSearchResult["CATALOG"]["PAGE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["CATALOG"]."/";


$arSearchResult["BLOG"] = Array();

$arSearchResult["BLOG"]["NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_IN_BLOG"];
$arSearchResult["BLOG"]["PAGE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["BLOG"]."/";


$arSearchResult["NEWS"] = Array();

$arSearchResult["NEWS"]["NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_IN_NEWS"];
$arSearchResult["NEWS"]["PAGE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["NEWS"]."/";


$arSearchResult["ACTIONS"] = Array();

$arSearchResult["ACTIONS"]["NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_IN_ACTIONS"];
$arSearchResult["ACTIONS"]["PAGE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["ACTIONS"]."/";





$arSearchMass = Array();
$active = 0;

if(count($arSearchIn) > 1)
{
    $arSearchMass["ALL"] = $arSearchResult["ALL"];
    $arSearchMass["ALL"]["ID"] = implode(";", $arSearchIn);
}

if(!empty($arSearchResult))
{
    foreach($arSearchResult as $key=>$value)
    {
        if(in_array($key, $arSearchIn))
        {
            $arSearchMass[$key] = $arSearchResult[$key];

            if($key == ToUpper($arParams["START_PAGE"]))
            {
                $arSearchMass[$key]["ACTIVE"] = "Y";
                $active = 1;
            }
        }
    }
}

if($active == 0)
{
    $k = 0;

    if(!empty($arSearchMass))
    {
        foreach($arSearchMass as $key=>$value)
        {
            if($k == 0) 
                $arSearchMass[$key]["ACTIVE"] = "Y";
            else
                break;

            $k++;
        }
    }
}
?>

<form action="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"]?>" class = "search-form fix-header">

    <!-- <div class="wrapper-border"></div> -->

    <div class="search-panel-fix-header">
        
        <div class="row align-items-center search-input">
       
            <div class="col search-input-box" id="<?=$arParams["CONTAINER_ID"]?>">

                <input type="text" id="<?=$arParams["INPUT_ID"]?>" name ="q" class = "search-style search-js" value="<?=$query?>" placeholder="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_FIX_HEADER_PLACEHOLDER"];?>" autocomplete="off">

                <div class="circleG-area">
                    <div class="circleG circleG_1"></div>
                    <div class="circleG circleG_2"></div>
                    <div class="circleG circleG_3"></div>
                </div>

                <ul class="search-list d-none">

                    <?if($arSearchMass):?>
                                        
                        <?foreach($arSearchMass as $key=>$arS):?>
                            
                            <li>
                                <span class="search-get <?if($arS["ACTIVE"] == "Y"):?>active<?endif;?>" data-url="<?=$arS["PAGE"]?>"><?=$arS["NAME"]?></span>
                            </li>

                        <?endforeach;?>

                    <?endif;?>
                    
                </ul>

            </div>

            <div class="col-auto search-button">
                <button class = "search-btn-style" type = "button">
                </button>
            </div>

            <div class="open-search-top"></div>

        </div>
    </div>
        
    
    <div class="clearfix"></div>


</form>

<?if($arParams["SHOW_RESULTS"] == "Y"):?>

    <script>
        BX.ready(function(){
            new AjaxQuickSearch({
                'CONTAINER_ID': '<?=$arParams["CONTAINER_ID"]?>',
                'INPUT_ID': '<?=$arParams["INPUT_ID"]?>',
                'SHOW_RESULTS': '<?=$arParams["SHOW_RESULTS"]?>',
                'LINE_VIEW_SIZE': '<?=$arParams["SEARCH_CONTAINER_WIDTH"]?>'
            });
        });
    </script>

<?endif;?>
