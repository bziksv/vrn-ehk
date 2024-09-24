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

$hint = "";
$placeholder = "";


if($arParams["TYPE"] == "constructor")
{
    
    $hint = $arParams["OPTIONS"]["HINT"];

    if(isset($arParams["OPTIONS"]["SEARCH_IN"]))
    {
        if(!empty($arParams["OPTIONS"]["SEARCH_IN"]))
        {
            foreach($arParams["OPTIONS"]["SEARCH_IN"] as $value)
            {
                $key = ToUpper($value);
                $arSearchIn[$key] = $key;
            }
        }
    }
   


    if(strlen($arParams["OPTIONS"]["PLACEHOLDER"]))
        $placeholder = $arParams["OPTIONS"]["PLACEHOLDER"];

}

else
{
    $query = htmlspecialcharsbx(trim($_REQUEST["q"]));

    $hint = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_DEFAULT"]["VALUE"];

    if($arParams["START_PAGE"] == "catalog")
    {
        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_CATALOG"]["VALUE"]))
            $hint = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_CATALOG"]["VALUE"];

        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["PLACEHOLDER_CATALOG"]["VALUE"]))
            $placeholder =$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["PLACEHOLDER_CATALOG"]["VALUE"];
    }

    if($arParams["START_PAGE"] == "blog")
    {
        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_BLOG"]["VALUE"]))
            $hint = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_BLOG"]["VALUE"];

        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["PLACEHOLDER_BLOG"]["VALUE"]))
            $placeholder =$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["PLACEHOLDER_BLOG"]["VALUE"];
    }

    if($arParams["START_PAGE"] == "news")
    {
        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_NEWS"]["VALUE"]))
            $hint = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_NEWS"]["VALUE"];

        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["PLACEHOLDER_NEWS"]["VALUE"]))
            $placeholder =$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["PLACEHOLDER_NEWS"]["VALUE"];
    }

    if($arParams["START_PAGE"] == "actions")
    {
        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_ACTIONS"]["VALUE"]))
            $hint = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_ACTIONS"]["VALUE"];

        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["PLACEHOLDER_ACTIONS"]["VALUE"]))
            $placeholder =$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["PLACEHOLDER_ACTIONS"]["VALUE"];
    }


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
    
}



$arSearchResult = Array();

$arSearchResult["ALL"] = Array();

$arSearchResult["ALL"]["NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_ALL"];
$arSearchResult["ALL"]["PAGE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"];
$arSearchResult["ALL"]["ID"] = "all";


$arSearchResult["CATALOG"] = Array();

$arSearchResult["CATALOG"]["NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_IN_CATALOG"];
$arSearchResult["CATALOG"]["PAGE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["CATALOG"]."/";
$arSearchResult["CATALOG"]["ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["CATALOG"];


$arSearchResult["BLOG"] = Array();

$arSearchResult["BLOG"]["NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_IN_BLOG"];
$arSearchResult["BLOG"]["PAGE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["BLOG"]."/";
$arSearchResult["BLOG"]["ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["BLOG"];


$arSearchResult["NEWS"] = Array();

$arSearchResult["NEWS"]["NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_IN_NEWS"];
$arSearchResult["NEWS"]["PAGE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["NEWS"]."/";
$arSearchResult["NEWS"]["ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["NEWS"];


$arSearchResult["ACTIONS"] = Array();

$arSearchResult["ACTIONS"]["NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_IN_ACTIONS"];
$arSearchResult["ACTIONS"]["PAGE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["ACTIONS"]."/";
$arSearchResult["ACTIONS"]["ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["URLS"]["ACTIONS"];





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

   

<form action="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]["VALUE"]?><?=(strlen($arParams["START_PAGE"]) && $arParams["START_PAGE"] != "none") ? $arParams["START_PAGE"]."/" : ""?>" class="search-form default-search-form outside-div" id="<?=$arParams["CONTAINER_ID"]?>">

   
    
    <div class="row align-items-center search-panel search-panel-js top-panel <?if(strlen($hint) > 0):?>hint<?endif;?>">

        
        <div class="col-8">
            <div class="search-input-box" id="<?=$arParams["CONTAINER_ID"]?>">
    
                <input id="<?=$arParams["INPUT_ID"]?>" name="q" class="search-style search-js" type="text" value="<?=$query?>" placeholder = "<?=$placeholder?>" autocomplete="off">
                <div class="search-icon search-icon-js hidden-sm hidden-xs"></div>

                <div class="circleG-area">
                    <div class="circleG circleG_1"></div>
                    <div class="circleG circleG_2"></div>
                    <div class="circleG circleG_3"></div>
                </div>

            </div>
        </div>

        <div class="col-4 wrapper-right">

            <div class="row no-gutters align-items-center search-btns-box <?if(/*strlen($hint) <= 0 || */strlen($query) > 0):?>active before-active<?endif;?>">

               

                <div class="col-md-7 show-search-list-parent hidden-sm hidden-xs">
                    
                    <?if(!empty($arSearchMass)):?>

                        <div class="search-list-wrap"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_IN"];?> 

                            <?foreach($arSearchMass as $key=>$arS):?>
                                <?if($arS["ACTIVE"] == "Y"):?>
                                    <span class="search-cur show-search-list"><?=$arS["NAME"]?></span>
                                <?endif;?>
                            <?endforeach;?>
                        
                            <ul class="search-list">
                                
                                <?foreach($arSearchMass as $key=>$arS):?>


                                    
                                    <li>
                                        <span class="search-get <?if($arS["ACTIVE"] == "Y"):?>active<?endif;?>" data-id="<?=$arS["ID"]?>" data-url="<?=$arS["PAGE"]?>"><?=$arS["NAME"]?></span>
                                    </li>

                                <?endforeach;?>
                                
                            </ul>
                                
                        </div>

                    <?endif;?>

                </div>

                <div class="col-md-5 col-12 buttons">

                    <button class = "search-btn-style main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> hidden-xs" type = "submit"><div class="icon-enter"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_BUTTON_NAME"]?></div></button>

                    <button class = "button-def search-btn-style mob main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> d-block d-sm-none" type = "submit"></button>
                    

                </div>
                    

               
                
            </div>

            <?if(strlen($hint) > 0 && strlen($query) <= 0):?>
                <div class="hint-area hidden-sm hidden-xs paste-in-input-parent"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_HINT"]?><span class="paste-in-input" id="<?=$arParams["CONTAINER_ID"]?>_hint"><?=$hint?></span></div>
            <?endif;?>
            
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
                'SHOW_RESULTS': '<?=$arParams["SHOW_RESULTS"]?>'
            });
        });
    </script>

<?endif;?>