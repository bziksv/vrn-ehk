<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("area");?>

<?

if(strlen($GLOBALS["TITLE"]) <= 0)
{
    if(strlen($arResult["UF_PHX_".$arParams["TYPE"]."_TITLE"]) > 0)
        $GLOBALS["TITLE"] = $arResult["UF_PHX_".$arParams["TYPE"]."_TITLE"];
    else
        $GLOBALS["TITLE"] = $arResult["IPROPERTY_VALUES"]["SECTION_META_TITLE"];
}

if(strlen($GLOBALS["KEYWORDS"]) <= 0)
{
    if(strlen($arResult["UF_PHX_".$arParams["TYPE"]."_KWORD"]) > 0)
        $GLOBALS["KEYWORDS"] = $arResult["UF_PHX_".$arParams["TYPE"]."_KWORD"];
    else
        $GLOBALS["KEYWORDS"] = $arResult["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"];
}

if(strlen($GLOBALS["DESCRIPTION"]) <= 0)
{
    if(strlen($arResult["UF_PHX_".$arParams["TYPE"]."_DSCR"]) > 0)
        $GLOBALS["DESCRIPTION"] = $arResult["UF_PHX_".$arParams["TYPE"]."_DSCR"];
    else
        $GLOBALS["DESCRIPTION"] = $arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"];
}

if(strlen($GLOBALS["H1"]) <= 0)
{
    if(strlen($arResult["UF_PHX_".$arParams["TYPE"]."_H1"]) > 0)
        $GLOBALS["H1"] = $arResult["UF_PHX_".$arParams["TYPE"]."_H1"];
    else
        $GLOBALS["H1"] = $arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"];
}
?>

<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("area");?>