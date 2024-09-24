<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>

<?
global $PHOENIX_TEMPLATE_ARRAY;

if( !empty($arResult["ITEMS"]) )
{
    foreach ($arResult["ITEMS"] as $key => $arItem){
        $arEditAreaId[$arItem["ID"]] = $this->GetEditAreaId($arItem['ID']).'_list';
    }

    CPhoenix::SetResultModifierCatalogElements($arResult, $arParams, $arEditAreaId);

    
    if(isset($arParams["SEARCH_ELEMENTS_ID"]) && !empty($arParams["SEARCH_ELEMENTS_ID"]))
    {

        foreach ($arParams["SEARCH_ELEMENTS_ID"] as $key => $value) {
           $arParams["SEARCH_ELEMENTS_ID"][$key] = intval($value);
        }


        $newArResult = array();

        foreach ($arResult["ITEMS"] as $key => $value)
        {
            $newKey = array_search(intval($value["ID"]), $arParams["SEARCH_ELEMENTS_ID"]);

            $newArResult[$newKey] = $value;
        }
        
        ksort($newArResult);

        $arResult["ITEMS"] = array_values($newArResult);
    }


}