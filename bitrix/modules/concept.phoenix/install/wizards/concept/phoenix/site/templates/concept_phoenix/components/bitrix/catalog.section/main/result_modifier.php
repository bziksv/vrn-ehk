<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Type\Collection;
use Bitrix\Currency\CurrencyTable;
use Bitrix\Iblock;
?>

<?global $PHOENIX_TEMPLATE_ARRAY;

CPhoenixSku::getHIBlockOptions();

if(intval($arResult["ID"]))
{
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
}

$arResult["VIEW"] = $arParams["VIEW"];

if(!strlen($arParams["VIEW"]))
    $arResult["VIEW"] = "FLAT";


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






$this->SetViewTarget('available-show');
    echo (CPhoenix::IssetOffers($arResult)) ? "d-none" : "";
$this->EndViewTarget();


if(isset($arParams["SEARCH_ELEMENTS_ID"]))
{
    if(!empty($arParams["SEARCH_ELEMENTS_ID"]))
    {

        foreach ($arParams["SEARCH_ELEMENTS_ID"] as $key => $value) {
           $arParams["SEARCH_ELEMENTS_ID"][$key] = intval($value);
        }


        $newArResult = array();

        if(!empty($arResult["ITEMS"]))
        {
            foreach ($arResult["ITEMS"] as $key => $value)
            {
                $newKey = array_search(intval($value["ID"]), $arParams["SEARCH_ELEMENTS_ID"]);

                $newArResult[$newKey] = $value;
            }
        }
        
        ksort($newArResult);

        $arResult["ITEMS"] = array_values($newArResult);

    }
}