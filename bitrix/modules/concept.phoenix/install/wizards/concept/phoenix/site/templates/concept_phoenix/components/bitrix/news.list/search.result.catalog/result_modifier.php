<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $PHOENIX_TEMPLATE_ARRAY;


if( !empty($arParams["SEARCH_RESULT"]['SECTIONS_ID']) )
{
	$SectList = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ID"=> $arParams["SEARCH_RESULT"]['SECTIONS_ID'], "ACTIVE"=>"Y"), false, array("ID", "NAME", "SECTION_PAGE_URL", "PICTURE", "UF_PHX_MENU_PICT"));
	while ($SectListGet = $SectList->GetNext())
	{
        $SectListGet["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH.'/images/ufo.jpg';
        $src = "";


        $photo = 0;

        if($SectListGet["UF_PHX_MENU_PICT"])
            $photo = $SectListGet["UF_PHX_MENU_PICT"];
        else if($SectListGet["PICTURE"])
            $photo = $SectListGet["PICTURE"];

        $SectListGet["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/ufo.png";

 
        if($photo)
        {
            $img = CFile::ResizeImageGet($photo, array('width'=>120, 'height'=>120), BX_RESIZE_IMAGE_EXACT, false);
            $SectListGet["PREVIEW_PICTURE_SRC"] = $img["src"];
        }

        
	    $arResult["SECTIONS"][]=$SectListGet;
	}

    if(!empty($arParams["SEARCH_RESULT"]['SECTIONS_ID']))
    {
        foreach ($arParams["SEARCH_RESULT"]['SECTIONS_ID'] as $key => $value) {
           $arParams["SEARCH_RESULT"]['SECTIONS_ID'][$key] = intval($value);
        }
    }
    

    $newArResult = array();

    if(!empty($arResult["SECTIONS"]))
    {

        foreach ($arResult["SECTIONS"] as $key => $value)
        {
            $newKey = array_search(intval($value["ID"]), $arParams["SEARCH_RESULT"]['SECTIONS_ID']);

            $newArResult[$newKey] = $value;
        }

    }
    if(!empty($newArResult))
    {
        ksort($newArResult);
        $arResult["SECTIONS"] = array_values($newArResult);

    }

}

if( !empty($arParams["SEARCH_RESULT"]['ITEMS_BRANDS_ID']) )
{
    $arFilter = Array("ID"=> $arParams["SEARCH_RESULT"]['ITEMS_BRANDS_ID']);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, array("ID", "IBLOCK_ID", "PREVIEW_PICTURE", "NAME", "DETAIL_PAGE_URL"));

    while($ob = $res->GetNextElement())
    { 
        $arFields = $ob->GetFields();

        if($arFields["PREVIEW_PICTURE"])
        {
        	$img = CFile::ResizeImageGet($arFields["PREVIEW_PICTURE"], array('width'=>350, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, false);

        	$arFields["PREVIEW_PICTURE_SRC"] = $img["src"];
        }
        
        $arResult["BRANDS"][] = $arFields;
    }

    if(!empty($arParams["SEARCH_RESULT"]['ITEMS_BRANDS_ID']))
    {

        foreach ($arParams["SEARCH_RESULT"]['ITEMS_BRANDS_ID'] as $key => $value) {
           $arParams["SEARCH_RESULT"]['ITEMS_BRANDS_ID'][$key] = intval($value);
        }
    
    }

    $newArResult = array();

    if(!empty($arResult["BRANDS"]))
    {

        foreach ($arResult["BRANDS"] as $key => $value)
        {
            $newKey = array_search(intval($value["ID"]), $arParams["SEARCH_RESULT"]['ITEMS_BRANDS_ID']);

            $newArResult[$newKey] = $value;
        }

    }

    if(!empty($newArResult))
    {
        ksort($newArResult);
        $arResult["BRANDS"] = array_values($newArResult);
    }
}

if( !empty($arParams["SEARCH_RESULT"]['ITEMS_ID']) )
{


    $arResult["SEARCH_ITEMS_ID"] = array();

    if($arParams["VIEW"] == "short")
    {
        foreach ($arParams["SEARCH_RESULT"]['ITEMS_ID'] as $key => $value)
        {
            if($key >= 4)
                break;

            $arResult["SEARCH_ITEMS_ID"][] = $value;
        }
    }
    else
        $arResult["SEARCH_ITEMS_ID"] = $arParams["SEARCH_RESULT"]['ITEMS_ID'];
}