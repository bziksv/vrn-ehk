<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
/*$arPage["NW"] = SITE_DIR."news/";
$arPage["BLG"] = SITE_DIR."blog/";*/

if(!empty($arResult["SECTIONS"]))
{
    foreach($arResult["SECTIONS"] as $key=>$arSection)
    {

        $arSelect = Array("ID", "UF_*");
        $arFilter = Array('IBLOCK_ID'=>$arParams["IBLOCK_ID"], "ID" => $arSection["ID"]);
        $db_list = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
        $ar_result = $db_list->GetNext();
        
        
        $arSection = array_merge($arSection, $ar_result);
        
        if(!$arSection['UF_PHX_MAIN_'.$arParams["TYPE"]])
            unset($arResult["SECTIONS"][$key]);

    }
}

if(strlen($arResult["SECTION"]["SECTION_PAGE_URL"]) > 0)
{
    $arResult["SECTION_BACK"] = $arResult["SECTION"]["SECTION_PAGE_URL"];
}
else
    $arResult["SECTION_BACK"] = $arParams["FOLDER"];

$arResult["SECTION_MAIN"] = $arParams["FOLDER"];
    
    
global $APPLICATION;

$cp = $this->__component; // объект компонента

if (is_object($cp))
{
    $cp->arResult['MAIN'] = $arResult;
    $cp->SetResultCacheKeys(array('MAIN'));

    $arResult['MAIN'] = $cp->arResult['MAIN'];
}
?>