<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

global $APPLICATION;
global $PHOENIX_TEMPLATE_ARRAY;
global $result_search_count;

CModule::IncludeModule("search");

if(trim(SITE_CHARSET) == "windows-1251" && isset($_REQUEST["ajax_mode"]) && $_REQUEST["ajax_mode"] == "Y")
    $arParams["QUERY"] = utf8win1251($arParams["QUERY"]);

$isCatalog = false;

$arSearchIblockID = Array();

$arSearchIblockID[] = $arParams["PARAM2"];

if($arParams["CODE"] == "CATALOG")
{
    $isCatalog = true;

    $arSearchIblockID[] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"];

    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["QUEST_IN"]["VALUE"]["IN_BRANDS"] == "Y" && $arParams["PARAM2"])
        $arSearchIblockID[] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BRAND']["IBLOCK_ID"];


}

$arResult = array();
$obSearch = new CSearch;
$obSearch->Search(
    Array(
        "QUERY" => $arParams["QUERY"],
        "MODULE_ID" => $arParams["MODULE_ID"],
        "PARAM2" => $arSearchIblockID,
        "CHECK_DATES" => "Y",
        "SITE_ID" => SITE_ID
    ),
    array("CUSTOM_RANK"=>"DESC", "RANK"=>"DESC", "TITLE_RANK" => "DESC")
);

if($obSearch->selectedRowsCount() <= 0) 
{
    $obSearch->Search(
        Array(
            "QUERY" => $arParams["QUERY"],
            "MODULE_ID" => $arParams["MODULE_ID"],
            "PARAM2" => $arSearchIblockID,
            "CHECK_DATES" => "Y",
            "SITE_ID" => SITE_ID
        ),
        array("CUSTOM_RANK"=>"DESC", "RANK"=>"DESC", "TITLE_RANK" => "DESC"), 
        array('STEMMING' => false)
    );
}

$tmpResult = array();

while($arRes = $obSearch->GetNext() )
{
    if( strpos($arRes["ITEM_ID"], 'S') !== false && $isCatalog)
    {
        $tmp = str_replace( 'S', '', $arRes["ITEM_ID"] );
        $tmpResult[$arRes["PARAM2"]]["SECTIONS_ID"][$tmp] = $tmp;
    }
    else
        $tmpResult[$arRes["PARAM2"]]["ITEMS_ID"][$arRes["ITEM_ID"]] = $arRes["ITEM_ID"];        

}


if(!empty($tmpResult))
{
    foreach ($tmpResult as $iblockID => $arIblock)
    {
        if($isCatalog && $iblockID == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"])
        {
            $arFilter = Array(
                "ID" => $arIblock["ITEMS_ID"], 
                "ACTIVE" => "Y"
            );

            $arSelect = Array("ID", "CATALOG_AVAILABLE", "PROPERTY_CML2_LINK");
           

            $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);

            while($ar_result = $res->GetNext())
            {
                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"] == "L" && $ar_result["CATALOG_SUBSCRIBE"] == "Y")
                    $tmpResult[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]]["ITEMS_ID"][] = $ar_result["PROPERTY_CML2_LINK_VALUE"];

                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"] == "Y" && $ar_result["CATALOG_AVAILABLE"] == "Y")
                    $tmpResult[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]]["ITEMS_ID"][] = $ar_result["PROPERTY_CML2_LINK_VALUE"];
               
                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"] == "N")
                    $tmpResult[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]]["ITEMS_ID"][] = $ar_result["PROPERTY_CML2_LINK_VALUE"];
            }

            

            if(!empty($tmpResult[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]]["ITEMS_ID"]))
                $tmpResult[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]]["ITEMS_ID"] = array_unique($tmpResult[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]]["ITEMS_ID"]);
        }


        if(!empty($arIblock["ITEMS_ID"]))
            $tmpResult[$iblockID]["ITEMS_ID"] = array_unique($arIblock["ITEMS_ID"]);

        if(!empty($arIblock["SECTIONS_ID"]))
            $tmpResult[$iblockID]["SECTIONS_ID"] = array_unique($arIblock["SECTIONS_ID"]);
    }



    foreach ($tmpResult as $iblockID => $arIblock)
    {
        if($isCatalog && !empty($arIblock["SECTIONS_ID"]))
        {
            $newArSections = array();

            $arSelect = Array("ID");
            $arFilter = Array('CNT_ACTIVE'=>'Y','IBLOCK_ID'=>$iblockID, "ID" => $arIblock["SECTIONS_ID"]);
            
            $db_list = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);

            while ($ar_result = $db_list->GetNext())
            {
                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["HIDE_EMPTY_CATALOG"]["VALUE"]["ACTIVE"]=="Y")
                {
                    $arSelect = Array("ID");
                    $arFilter = Array(
                        "ACTIVE" => "Y",
                        "SECTION_ID" => $ar_result["ID"],
                        "!PROPERTY_MODE_HIDE_VALUE"=>"Y",
                        "INCLUDE_SUBSECTIONS"=>'Y',
                        // "SECTION_ACTIVE" => "Y",
                        // "SECTION_SCOPE" => "IBLOCK"
                    );

                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"] == "Y")
                        $arFilter['CATALOG_AVAILABLE'] = "Y";

                    $arFilter = array_merge($arFilter, CPhoenix::getFilterByRegion());

                    $arStoresFilter = CPhoenix::getFilterByStores();

                    if(!empty($arStoresFilter))
                        $arFilter[] = $arStoresFilter;

                    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false,  array("nTopCount" => "1"), $arSelect);

                    if($res->SelectedRowsCount() <= 0)
                        continue;
                } 

                $newArSections[] = $ar_result["ID"];
            }


            foreach ($arIblock["SECTIONS_ID"] as $key => $value) {
                if(!in_array($value, $newArSections))
                    unset($tmpResult[$iblockID]["SECTIONS_ID"][$key]);
            }

        }




        if(!empty($arIblock["ITEMS_ID"]))
        {

            $arSelect = Array("ID", "PROPERTY_MODE_ARCHIVE");

            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ACTIONS']["IBLOCK_ID"] == $iblockID)
                $arFilter = Array("ID"=>$arIblock["ITEMS_ID"]);

            else
            {
                $arFilter = Array("SECTION_SCOPE"=>"IBLOCK","SECTION_GLOBAL_ACTIVE"=>"Y", "ID"=>$arIblock["ITEMS_ID"], "ACTIVE"=>"Y");

                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"] == $iblockID)
                {
                    $arFilter["!PROPERTY_MODE_HIDE_VALUE"] = "Y";
                    $arFilter["!PROPERTY_MODE_ARCHIVE_VALUE"] = "Y";
                }


            }


            $arFilter = array_merge($arFilter, CPhoenix::getFilterByRegion());

            $arStoresFilter = CPhoenix::getFilterByStores();

            if(!empty($arStoresFilter))
                $arFilter[] = $arStoresFilter;
           

            $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);

            $newArElements = array();

            while($ob = $res->GetNext())
            { 
                $newArElements[] = $ob["ID"];
            }

            if(empty($newArElements))
                unset($tmpResult[$iblockID]["ITEMS_ID"]);


            if(!empty($arIblock["ITEMS_ID"]) && !empty($newArElements))
            {

                foreach ($arIblock["ITEMS_ID"] as $key => $value) {
                    if(!in_array($value, $newArElements))
                        unset($tmpResult[$iblockID]["ITEMS_ID"][$key]);
                }

            }

        }
    }

}

if(!empty($tmpResult))
{
    foreach ($tmpResult as $iblockID => $arIblock)
    {
        $count_items = 0;
        
        if(!empty($tmpResult[$iblockID]["ITEMS_ID"]))
            $count_items = count($tmpResult[$iblockID]["ITEMS_ID"]);


        if($isCatalog)
        {
            if($iblockID == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"])
            {
                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["QUEST_IN"]["VALUE"]["IN_CATEGORY"] == "Y" && isset($arIblock["SECTIONS_ID"]) && !empty($arIblock["SECTIONS_ID"]))
                {

                    $count_sections = count($arIblock["SECTIONS_ID"]);

                    if(is_array($arIblock["SECTIONS_ID"]))
                        $arResult["SECTIONS_ID"] = array_values($arIblock["SECTIONS_ID"]);

                    $arResult["COUNT_SECTIONS"] = $count_sections;
                    $arResult["COUNT_TOTAL"] += $count_sections;
                    
                }
                

                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["QUEST_IN"]["VALUE"]["IN_PRODUCTS"] == "Y" && isset($tmpResult[$iblockID]["ITEMS_ID"]) && !empty($tmpResult[$iblockID]["ITEMS_ID"]))
                {
                    $arResult["ITEMS_ID"] = array_values($tmpResult[$iblockID]["ITEMS_ID"]);
                    $arResult["COUNT_ELEMENTS"] = $count_items;
                    $arResult["COUNT_TOTAL"] += $count_items;
                }
                
            }

            if($iblockID == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BRAND']["IBLOCK_ID"] && isset($tmpResult[$iblockID]["ITEMS_ID"]) && !empty($tmpResult[$iblockID]["ITEMS_ID"]))
            {
                $arResult["COUNT_BRANDS"] = $count_items;
                $arResult["COUNT_TOTAL"] += $count_items;

                if(is_array($tmpResult[$iblockID]["ITEMS_ID"]))
                    $arResult["ITEMS_BRANDS_ID"] = array_values($tmpResult[$iblockID]["ITEMS_ID"]);
            }

        }

        else
        {
            $arResult["COUNT_TOTAL"] = $count_items;
            $arResult["COUNT_ELEMENTS"] = $count_items;

            if(is_array($tmpResult[$iblockID]["ITEMS_ID"]))
                $arResult["ITEMS_ID"] = array_values($tmpResult[$iblockID]["ITEMS_ID"]);
        }
    }

    $result_search_count += $arResult["COUNT_TOTAL"];
}


$this->includeComponentTemplate();