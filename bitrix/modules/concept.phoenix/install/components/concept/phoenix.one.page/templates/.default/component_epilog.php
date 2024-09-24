<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?global $PHOENIX_TEMPLATE_ARRAY, $res;?>

<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("side-menu-back");?>

<?
$res = $arResult;

$host = CPhoenixHost::getHost($_SERVER);

$ref_url = explode("?", $_SERVER['HTTP_REFERER']);
$uri = explode("?", $host.$_SERVER['REQUEST_URI']);
?>

<?if(strlen($ref_url[0]) && $ref_url[0] != $uri[0]):?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("ul.nav").append('<li class="col-12 back"><a href="<?=$_SERVER["HTTP_REFERER"]?>"> <span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MENU_SIDE_BACK"]?></span></a></li>');
        });

    </script>

<?endif;?>

<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("side-menu-back");?>



<?$GLOBALS["h1_main"] = $arResult["H1_MAIN"];?>
<?$temp = $arResult["CACHED_TPL"]?>


<?if(is_array($arResult["SECTION"]["UF_PHX_BANNERS"]) && !empty($arResult["SECTION"]["UF_PHX_BANNERS"])):?>

    <?

$GLOBALS["arrBannersFilter"]["ID"] = $arResult["SECTION"]["UF_PHX_BANNERS"];

    $temp = preg_replace_callback(
        "/#DYN_LEFT_BANNERS#/",
        create_function('$matches', 'ob_start();
                                            
        $GLOBALS["APPLICATION"]->IncludeComponent(
            "bitrix:news.list", 
            "banners-left", 
            array(
                "COMPONENT_TEMPLATE" => "banners-left",
                "IBLOCK_TYPE" => $GLOBALS["PHOENIX_TEMPLATE_ARRAY"]["ITEMS"]["BANNERS"]["IBLOCK_TYPE"],
                "IBLOCK_ID" => $GLOBALS["PHOENIX_TEMPLATE_ARRAY"]["ITEMS"]["BANNERS"]["IBLOCK_ID"],
                "NEWS_COUNT" => "20",
                "SORT_BY1" => "SORT",
                "SORT_ORDER1" => "ASC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FILTER_NAME" => "arrBannersFilter",
                "FIELD_CODE" => array(
                    0 => "DETAIL_PICTURE",
                    1 => "PREVIEW_PICTURE",
                ),
                "PROPERTY_CODE" => array(
                    0 => "",
                    1 => "BANNER_BTN_TYPE",
                    2 => "BANNER_ACTION_ALL_WRAP",
                    3 => "BANNER_USER_BG_COLOR",
                    4 => "BANNER_UPTITLE",
                    5 => "BANNER_BTN_NAME",
                    6 => "BANNER_TITLE",
                    7 => "BANNER_BTN_BLANK",
                    8 => "BANNER_BORDER",
                    9 => "BANNER_DESC",
                    10 => "BANNER_TEXT",
                    11 => "BANNER_LINK",
                    12 => "BANNER_COLOR_TEXT",
                    13 => "",
                ),
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_FILTER" => "Y",
                "CACHE_GROUPS" => "Y",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "SET_TITLE" => "N",
                "SET_BROWSER_TITLE" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_LAST_MODIFIED" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "N",
                "STRICT_SECTION_CHECK" => "N",
                "DISPLAY_DATE" => "N",
                "DISPLAY_NAME" => "N",
                "DISPLAY_PICTURE" => "N",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "COMPOSITE_FRAME_MODE" => "N",
                "PAGER_TEMPLATE" => ".default",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "PAGER_TITLE" => "",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "SET_STATUS_404" => "N",
                "SHOW_404" => "N",
                "MESSAGE_404" => ""
            ),
            $component
        );
        $retrunStr = @ob_get_contents();
        ob_get_clean();
        return $retrunStr;'),
        $temp);
    ?>


<?endif;?>


<?if(!empty($arResult["COMPONENTS"])):?>

    <?foreach($arResult["COMPONENTS"] as $key=>$arItem):?>
        
        <?$path = $arItem["PROPERTIES"]["COMPONENT_PATH"]["VALUE"];?>

        <?
        $temp = preg_replace_callback(
            "/#DYNAMIC".$arItem["ID"]."#/",
            create_function('$matches', 'ob_start();
            $GLOBALS["APPLICATION"]->IncludeFile("'.$path.'", array(),
                array(
                    "MODE"  => "php",
                )
            );
            $retrunStr = @ob_get_contents();
            ob_get_clean();
            return $retrunStr;'),
            $temp);
        ?>

    <?endforeach;?>

<?endif;?>

<?=$temp;?>