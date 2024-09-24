<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>

<?


$arSelect = Array("ID", "UF_*");
$arFilter = Array('IBLOCK_ID'=>$arParams["IBLOCK_ID"], "ID" => $arParams["PARENT_SECTION"]);
$db_list = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
$ar_result = $db_list->GetNext();


if(strlen($ar_result["UF_PHX_".$arParams["TYPE"]."_TXT_P"]) > 0)
{
    $ar_result["UF_PHX_".$arParams["TYPE"]."_TXT_P_ENUM"] = CUserFieldEnum::GetList(array(), array(
        "ID" => $ar_result["UF_PHX_".$arParams["TYPE"]."_TXT_P"],
    ))->GetNext();


}

if(strlen($ar_result["UF_PHX_".$arParams["TYPE"]."_TXT_P_ENUM"]["XML_ID"]) <= 0)
    $ar_result["UF_PHX_".$arParams["TYPE"]."_TXT_P_ENUM"]["XML_ID"] = "short";

$arResult = array_merge($arResult, $ar_result);


if($arParams["HIDE_SECTIONS"] != "Y")
{
    foreach($arResult["ITEMS"] as $arItem)
    {
        if(strlen($arItem["IBLOCK_SECTION_ID"])>0 || $arItem["IBLOCK_CODE"] != $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ACTIONS']["IBLOCK_CODE"])
            $arResult["PARENT_ON"] = "Y";
    }
}

$arIblokIds = array();
$code = array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BLOG']["IBLOCK_CODE"], $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['NEWS']["IBLOCK_CODE"], $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ACTIONS']["IBLOCK_CODE"]);

$SectList = CIBlockSection::GetList(array(), array("IBLOCK_CODE"=>$code, "ACTIVE"=>"Y"), false, array("IBLOCK_ID"));
while ($SectListGet = $SectList->GetNext())
{
    $arIblokIds[] = $SectListGet["IBLOCK_ID"];
   
}

$SectList = NULL;

foreach($arIblokIds as $value)
{
    $SectList = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>$value, "ACTIVE"=>"Y"), false, array("ID","NAME","SECTION_PAGE_URL", "UF_CATEGORY_LIST"));
    while ($SectListGet = $SectList->GetNext())
    {
        
        if(strlen($SectListGet["UF_CATEGORY_LIST"]))
        {
            $SectListGet["UF_CATEGORY_LIST_ENUM"] = CUserFieldEnum::GetList(array(), array(
                "ID" => $SectListGet["UF_CATEGORY_LIST"],
            ))->GetNext();
        }
        else
            $SectListGet["UF_CATEGORY_LIST_ENUM"]["XML_ID"] = "type-1";


        $arResult["BNA"][$SectListGet["ID"]]=$SectListGet;
    }
}




$cp = $this->__component;
 
if (is_object($cp))
{
    $cp->arResult['UF_PHX_'.$arParams["TYPE"].'_TITLE'] = $ar_result["UF_PHX_".$arParams["TYPE"]."_TITLE"];
    $cp->arResult['UF_PHX_'.$arParams["TYPE"].'_KWORD'] = $ar_result["UF_PHX_".$arParams["TYPE"]."_KWORD"];
    $cp->arResult['UF_PHX_'.$arParams["TYPE"].'_DSCR'] = $ar_result["UF_PHX_".$arParams["TYPE"]."_DSCR"];
    $cp->arResult['UF_PHX_'.$arParams["TYPE"].'_H1'] = $ar_result["UF_PHX_".$arParams["TYPE"]."_H1"];
    
    $cp->SetResultCacheKeys(array('UF_PHX_'.$arParams["TYPE"].'_TITLE', 'UF_PHX_'.$arParams["TYPE"].'_KWORD', 'UF_PHX_'.$arParams["TYPE"].'_DSCR', 'UF_PHX_'.$arParams["TYPE"].'_H1'));
    
    $arResult['UF_PHX_'.$arParams["TYPE"].'_TITLE'] = $cp->arResult['UF_PHX_'.$arParams["TYPE"].'_TITLE'];
    $arResult['UF_PHX_'.$arParams["TYPE"].'_KWORD'] = $cp->arResult['UF_PHX_'.$arParams["TYPE"].'_KWORD'];
    $arResult['UF_PHX_'.$arParams["TYPE"].'_DSCR'] = $cp->arResult['UF_PHX_'.$arParams["TYPE"].'_DSCR'];
    $arResult['UF_PHX_'.$arParams["TYPE"].'_H1'] = $cp->arResult['UF_PHX_'.$arParams["TYPE"].'_H1'];
}
