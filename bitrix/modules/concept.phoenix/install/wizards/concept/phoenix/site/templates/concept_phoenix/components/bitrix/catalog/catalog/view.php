<?
    $arViews = Array("FLAT", "LIST", "TABLE");

    $view = $_SESSION["view"];
            
    if(strlen($view) <= 0)
        $view = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DEFAULT_LIST_VIEW"]["VALUE"];
    
    
    if(strlen($_REQUEST["view"]) > 0)
    {
        $view = $_REQUEST["view"];
        $_SESSION["view"] = $view;
    }

?>

<div class="view-list">

    <?foreach($arViews as $val):?>

        <?
            /*$current_url = explode("?", $_SERVER["REQUEST_URI"]);
            $current_url = $current_url[0];

            $add = DeleteParam(array("view"));

            if(strlen($add) > 0)
                $add .= '&view='.$val;
            else
                $add .= 'view='.$val;

            $current_url = $current_url."?".$add;
            $url = str_replace('+', '%2B', $current_url);*/

        ?>


        <a <?/*href="<?=$url?>#actionbox"*/?> class="view display-view-js display-<?=$val?> <?=($view == $val ? 'active' : '')?>" data-toggle="tooltip" data-view="<?=$val?>" data-placement="top" title = "<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["VIEW_CATALOG_LIST_".$val]?>"></a>

    <?endforeach;?>
</div>