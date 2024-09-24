<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?global $PHOENIX_TEMPLATE_ARRAY;?>
<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("ajax");?>

<?if($arParams["ELEMENT"] != "Y"):?>
    <?if(!empty($arResult["MAIN"]["SECTIONS"])):?>
        <ul class="nav row no-gutters">

            <li class="main col-12">
                <a href="<?=$arResult["MAIN"]["SECTION_MAIN"]?>"><span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ALL_".$arParams["TYPE"]]?></span></a>
            </li> 
            
            <?foreach($arResult["MAIN"]["SECTIONS"] as $arSection):?>
            
                <li class="col-12" data-id="<?=$arSection["ID"]?>">
                    <a href="<?=$arSection["SECTION_PAGE_URL"]?>"><span class="text"><?=strip_tags(html_entity_decode($arSection["NAME"]))?></span></a>
                </li>
            
            <?endforeach;?>
            
        </ul>

    <?endif;?>

<?else:?>

    <ul class="new-detail row no-gutters">

        <li class="col-12 back"><a href="<?=$arResult["MAIN"]["SECTION_BACK"]?>"><span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MOVE_TO_LIST_".$arParams["TYPE"]];?></span></a></li>

    </ul>
    
<?endif;?>


<?
$cur_page = $_SERVER["REQUEST_URI"];
$cur_page_no_index = $APPLICATION->GetCurPage(false);
$menu = 0;
?>


<?if(!empty($arResult["MAIN"]["SECTIONS"])):?>

    <script type="text/javascript">
    
        <?foreach($arResult["MAIN"]["SECTIONS"] as $arSection):?>

        
            <?if(CMenu::IsItemSelected($arSection["SECTION_PAGE_URL"], $cur_page, $cur_page_no_index)):?>
                $("#navigation li[data-id='<?=$arSection["ID"]?>'] a").addClass("active");
                <?$menu = 1;?>
            <?endif;?>
            
        <?endforeach;?>

        <?if($menu < 1):?>
    		$("#navigation li.main a").addClass("active");
        <?endif;?>
        
    </script>

<?endif;?>


<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("ajax");?>