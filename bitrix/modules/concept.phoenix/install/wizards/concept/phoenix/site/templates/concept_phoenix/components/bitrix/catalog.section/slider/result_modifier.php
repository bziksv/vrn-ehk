<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Type\Collection;
use Bitrix\Currency\CurrencyTable;
use Bitrix\Iblock;
?>

<?
$arSelect = Array("ID", "UF_*");
$arFilter = Array('IBLOCK_ID'=>$arParams["IBLOCK_ID"], "ID" => $arResult["ID"]);
$db_list = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
$ar_result = $db_list->GetNext();

if(strlen($ar_result["UF_PHX_CTLG_TXT_P"]) > 0)
{
    $ar_result["UF_PHX_CTLG_TXT_P_ENUM"] = CUserFieldEnum::GetList(array(), array(
        "ID" => $ar_result["UF_PHX_CTLG_TXT_P"],
    ))->GetNext();
}

if(strlen($ar_result["UF_PHX_CTLG_TXT_P_ENUM"]["XML_ID"]) <= 0)
    $ar_result["UF_PHX_CTLG_TXT_P_ENUM"]["XML_ID"] = "short";

$arResult = array_merge($arResult, $ar_result);




global $PHOENIX_TEMPLATE_ARRAY;

$arResult["VIEW"] = $arParams["VIEW"];

if(!strlen($arParams["VIEW"]))
    $arResult["VIEW"] = "FLAT SLIDER";

$arResult["TEMPLATE"] = "SLIDER";

$arResult["rating"] = array();

if(!empty($arResult["ITEMS"]) && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]["PROPS_IN_LIST_FOR_".$arResult["VIEW"]]["VALUE"]["RATE_VOTE"] == "Y")
{
    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_REVIEW"]["VALUE"]["ACTIVE"] == "Y")
    {
        $arResult["RATING_VIEW"] = "full";
        $arResult["ITEMS_ID"] = array();
        foreach ($arResult["ITEMS"] as $key => $arItem){
           $arResult["ITEMS_ID"][] = $arItem["ID"];
        }
    }
    else
    {
        $arResult["RATING_VIEW"] = "simple";

        foreach ($arResult["ITEMS"] as $key => $arItem){
           $arResult["rating"][$arItem["ID"]] = (strlen($arItem["PROPERTIES"]["rating"]["VALUE"]))?round($arItem["PROPERTIES"]["rating"]["VALUE"]):"0";
        }
    }
}

$arEditAreaId = array();

if(!empty($arResult["ITEMS"]))
{
    foreach ($arResult["ITEMS"] as $key => $arItem){
        $arEditAreaId[$arItem["ID"]] = $this->GetEditAreaId($arItem['ID']).'_list';
    }
}

CPhoenix::SetResultModifierCatalogElements($arResult, $arParams, $arEditAreaId);

$arResult["ARTICLE_LINE"] = false;

if(!empty($arResult["ITEMS"]))
{

    foreach ($arResult["ITEMS"] as $key => $arItem){

        if($arResult["ARTICLE_LINE"])
            break;

        if(isset($arItem['OFFER_WITHOUT_SKU']))
            continue;
        else
        {
            if(strlen($arItem["ARTICLE"])>0 || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_ON"]["VALUE"]["ACTIVE"] == "Y")
                $arResult["ARTICLE_LINE"] = true;
        }
    }
}

$cp = $this->__component;
 
if (is_object($cp))
{
    $cp->arResult['UF_PHX_CTLG_TITLE'] = $ar_result["UF_PHX_CTLG_TITLE"];
    $cp->arResult['UF_PHX_CTLG_KWORD'] = $ar_result["UF_PHX_CTLG_KWORD"];
    $cp->arResult['UF_PHX_CTLG_DSCR'] = $ar_result["UF_PHX_CTLG_DSCR"];
    $cp->arResult['UF_PHX_CTLG_H1'] = $ar_result["UF_PHX_CTLG_H1"];
    
    $cp->SetResultCacheKeys(array('ITEMS_ID','rating', 'UF_PHX_CTLG_TITLE', 'UF_PHX_CTLG_KWORD', 'UF_PHX_CTLG_DSCR', 'UF_PHX_CTLG_H1', 'ITEMS'));
    
    $arResult['UF_PHX_CTLG_TITLE'] = $cp->arResult['UF_PHX_CTLG_TITLE'];
    $arResult['UF_PHX_CTLG_KWORD'] = $cp->arResult['UF_PHX_CTLG_KWORD'];
    $arResult['UF_PHX_CTLG_DSCR'] = $cp->arResult['UF_PHX_CTLG_DSCR'];
    $arResult['UF_PHX_CTLG_H1'] = $cp->arResult['UF_PHX_CTLG_H1'];
}