<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?global $PHOENIX_TEMPLATE_ARRAY;?>
<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("ajax");?>

<?

$arResult["SECTIONS"] = array();

$on = false;
$off = false;

$arSelect = Array("ID");
$arFilter = Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE_DATE" => "Y", "<DATE_ACTIVE_FROM" => ConvertTimeStamp(time(),"FULL"));
$arFilter2 = Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE_DATE"=>"", "<=DATE_ACTIVE_TO" => ConvertTimeStamp(time(),"FULL"));
$res1 = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$res2 = CIBlockElement::GetList(Array(), $arFilter2, false, false, $arSelect);

while($ob1 = $res1->GetNextElement())
{
    if($on)
        break;

    $on = true;
    

    $arResult["SECTIONS"][] = array("NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["ACTIONS_ON"],
        "SECTION_PAGE_URL" => $PHOENIX_TEMPLATE_ARRAY["MAIN_URLS"]["offers"]."?action=on",
        "ID" => "action_on");
}


while($ob2 = $res2->GetNextElement())
{
    if($off)
        break;

    $off = true;

    
    $arResult["SECTIONS"][] = array("NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["ACTIONS_OFF"],
        "SECTION_PAGE_URL" => $PHOENIX_TEMPLATE_ARRAY["MAIN_URLS"]["offers"]."?action=off",
        "ID" => "action_off");

}

if(strlen($arResult["SECTION"]["SECTION_PAGE_URL"]) > 0)
{
    $arResult["SECTION_BACK"] = $arResult["SECTION"]["SECTION_PAGE_URL"];
}
else
    $arResult["SECTION_BACK"] = $PHOENIX_TEMPLATE_ARRAY["MAIN_URLS"]["offers"];

$arResult["SECTION_MAIN"] = $PHOENIX_TEMPLATE_ARRAY["MAIN_URLS"]["offers"];

?>

<?if($arParams["ELEMENT"] != "Y"):?>
    <ul class="nav row no-gutters">
        <li class="col-12 main">
            <a href="<?=$arResult["SECTION_MAIN"]?>"><span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ALL_ACT"]?></span></a>
        </li> 

        <?if(!empty($arResult["SECTIONS"])):?>

        <?foreach($arResult["SECTIONS"] as $arSection):?>
        
            <li class="col-12" data-id="<?=$arSection["ID"]?>">
                <a href="<?=$arSection["SECTION_PAGE_URL"]?>"><span class="text"><?=$arSection["NAME"]?></span></a>
            </li>
        
        <?endforeach;?>

        <?endif;?>
        
    </ul>

<?else:?>

    <ul class="new-detail row no-gutters">

        <li class="col-12 back"><a href="<?=$arResult["SECTION_BACK"]?>"><span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MOVE_TO_LIST_ACT"]?></span></a></li>

    </ul>

<?endif;?>



<?
$cur_page = $_SERVER["REQUEST_URI"];
$cur_page_no_index = $APPLICATION->GetCurPage(false);
$menu = 0;
?>


<?if(!empty($arResult["SECTIONS"])):?>

    <script type="text/javascript">
    
        <?foreach($arResult["SECTIONS"] as $arSection):?>


        
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