<?
    $arViews = Array("FLAT", "LIST", "TABLE");

    $view = $_SESSION["view"];
            
    if(strlen($view) <= 0)
        $view = "FLAT";
    
    if(strlen($_REQUEST["view"]) > 0)
    {
        $view = $_REQUEST["view"];
        $_SESSION["view"] = $view;
    }

?>

<div class="view-list">

    <?if(!empty($arViews)):?>

        <?foreach($arViews as $val):?>


            <a class="view display-view-js display-<?=$val?> <?=($view == $val ? 'active' : '')?>" data-toggle="tooltip" data-view="<?=$val?>" data-placement="top" title = "<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["VIEW_CATALOG_LIST_".$val]?>"></a>

        <?endforeach;?>

    <?endif;?>
</div>