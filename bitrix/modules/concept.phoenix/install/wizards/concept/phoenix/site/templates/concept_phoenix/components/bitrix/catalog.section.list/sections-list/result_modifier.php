<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
if(!empty($arResult["SECTIONS"]))
{
    global $PHOENIX_TEMPLATE_ARRAY;

    $elementCountFilter = array(
        "ACTIVE"=>"Y",
        "ACTIVE_DATE" => "Y",
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "CHECK_PERMISSIONS" => "Y",
        "MIN_PERMISSION" => "R",
        "INCLUDE_SUBSECTIONS" => "Y",
        "!PROPERTY_MODE_HIDE_VALUE"=>"Y"
    );

    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"] == "Y")
        $elementCountFilter['CATALOG_AVAILABLE'] = 'Y';

    $elementCountFilter = array_merge($elementCountFilter, CPhoenix::getFilterByRegion());

    $arStoresFilter = CPhoenix::getFilterByStores();

    if(!empty($arStoresFilter))
        $elementCountFilter[] = $arStoresFilter;

    foreach ($arResult["SECTIONS"] as &$arSection)
    {
        $elementFilter = $elementCountFilter;
        $elementFilter["SECTION_ID"] = $arSection["ID"];
        if ($arSection['RIGHT_MARGIN'] == ($arSection['LEFT_MARGIN'] + 1))
        {
            $elementFilter['INCLUDE_SUBSECTIONS'] = 'N';
        }
        $arSection["~ELEMENT_CNT"] = CIBlockElement::GetList(array(), $elementFilter, array());
        $arSection["ELEMENT_CNT"] = $arSection["~ELEMENT_CNT"];
        
    }
    unset($arSection);
    
    $hide_menu = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["HIDE_EMPTY_CATALOG"]["VALUE"]["ACTIVE"] == "Y") ? true : false;

    $arRes = array();

    foreach($arResult["SECTIONS"] as $key=>$arSection)
    {
        if($arSection["DEPTH_LEVEL"] > 2)
            unset($arResult["SECTIONS"][$key]);
        else
        {
            if($hide_menu && intval($arSection["ELEMENT_CNT"]) <= 0)
            {
                unset($arResult["SECTIONS"][$key]);
                continue;
            }
            $arRes[] = $arSection["ID"];            
        }
        
    }



    if(!empty($arRes))
    {

    	$arCols = Array();

        $rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_ID"]."_SECTION", "FIELD_NAME" => "UF_PHX_CTLG_SIZE"));
        if($arColsRes = $rsData->Fetch())
        {
            $dbRes = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" => $arColsRes["ID"]));

            while($arColsdb = $dbRes->GetNext())
                $arCols["ITEMS"][$arColsdb["ID"]] = $arColsdb; 

            foreach ($arCols["ITEMS"] as $value){
                $arResult["SETTINGS"]["COLS"] .= "<input type=\"hidden\" class=\"colls_".$value["XML_ID"]."\" value=\"".$value["ID"]."\" />";
            }

            $firstItem = reset($arCols["ITEMS"]);

	        $arCols["DEFAULT"] = $firstItem["XML_ID"];
	        unset($firstItem);
        }



        $arUF = array();
        $arSelect = Array("ID", "PICTURE", "UF_PHX_CTLG_PRTXT", "UF_PHX_CTLG_SIZE", "UF_PHX_MAIN_CTLG", "UF_PHX_CTLG_BTN", "UF_PHX_PICT_IN_HEAD", "UF_PHX_MENU_PICT");
        $arFilter = Array('IBLOCK_ID'=>$arParams["IBLOCK_ID"], "ID" => $arRes);
        $db_list = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);

        while($ar_result = $db_list->GetNext())
        {
            $arUF["UF"][$ar_result["ID"]] = $ar_result;
        }

        
        $size2 = array("width" => 356, "height" => 255);
        $size3 = array("width" => 400, "height" => 262);
        
        foreach($arResult["SECTIONS"] as $key=>$arSection)
        {
            if($arSection["ID"] == $arUF["UF"][$arSection["ID"]]["ID"])
            {
                unset($arUF["UF"][$arSection["ID"]]["ID"], $arUF["UF"][$arSection["ID"]]["~ID"]);

                $arSection = array_merge($arSection, $arUF["UF"][$arSection["ID"]]);

                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MAIN_SECTIONS_LIST"]["VALUE"] == "view-2")
                {
                    $img = null;

                    $photo = 0;

                    if($arSection["UF_PHX_MENU_PICT"])
                        $photo = $arSection["UF_PHX_MENU_PICT"];

                    else if($arSection["PICTURE"])
                        $photo = $arSection["PICTURE"];

                    $arSection["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/sect-list-empty.png";
                    
                    if($photo)
                    {
                        $img = CFile::ResizeImageGet($photo, array("width" => 70, "height" => 70), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                        $arSection["PREVIEW_PICTURE_SRC"] = $img["src"];
                    }
                }
                else
                {
                    
                    $colSize = (strlen($arCols["ITEMS"][$arSection["UF_PHX_CTLG_SIZE"]]["XML_ID"])>0)?$arCols["ITEMS"][$arSection["UF_PHX_CTLG_SIZE"]]["XML_ID"]:$arCols["DEFAULT"];


                    $cols = 'col-lg-3 small';
                    $size = array("width" => 430, "height" => 370);

                    if($colSize == 'middle')
                    {
                        $size = array("width" => 750, "height" => 370);
                        $cols = 'middle';
                    }

                    $arSection["COLS_FRAME"] = $cols;

                    $imgXs = null;
                    $imgMd = null;
                    $imgXl = null;

                    if($arSection["PICTURE"] > 0 && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MAIN_SECTIONS_LIST"]["VALUE"] == "view-1")
                    {

                        $imgXs = CFile::ResizeImageGet($arSection["PICTURE"], $size3, BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                                                    
                                                    
                        $imgMd = CFile::ResizeImageGet($arSection["PICTURE"], $size2, BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                        
                        
                        $imgXl = CFile::ResizeImageGet($arSection["PICTURE"], $size, BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);


                        $arSection["PICTURE_XS"] = $imgXs["src"];
                        $arSection["PICTURE_MD"] = $imgMd["src"];
                        $arSection["PICTURE_XL"] = $imgXl["src"];
                    }
                }

                

                
                

                

                if($arSection["DEPTH_LEVEL"] == 1)
                {
                    $main_key = $key;
                    $arResult["SECTIONS"][$main_key] = $arSection;
                }
                
                if($arSection["DEPTH_LEVEL"] == 2)
                {
                    $main_key1 = $key;
                    
                    $arResult["SECTIONS"][$main_key]["SUB"][$main_key1] = $arSection;
                    unset($arResult["SECTIONS"][$main_key1]);
                }

                unset($arUF["UF"][$arSection["ID"]]);
            }
        }
        

        foreach($arResult["SECTIONS"] as $key=>$arSection)
        {
            if($arSection['UF_PHX_MAIN_CTLG']=="0")
                unset($arResult["SECTIONS"][$key]);
                    
                    
            if(!empty($arSection["SUB"]) && is_array($arSection["SUB"]))
            {
                foreach($arSection["SUB"] as $k=>$arSection1)
                {
                    if($arSection['UF_PHX_MAIN_CTLG']=="0")
                        unset($arResult["SECTIONS"][$key]["SUB"][$k]);

                    if($hide_menu && $arSection1["ELEMENT_CNT"] <= 0)
                        unset($arResult["SECTIONS"][$key]["SUB"][$k]);
                }
            }

            if($hide_menu && $arSection["ELEMENT_CNT"] <= 0)
                unset($arResult["SECTIONS"][$key]);
        }

    }

    $arResult["SECTIONS_CNT"] = count($arResult["SECTIONS"]);
}


/*$arSizes = Array();

$rsData = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID"=>"IBLOCK_".$arParams["IBLOCK_ID"]."_SECTION", "LANG" => "ru", "FIELD_NAME" => "UF_PHX_CTLG_SIZE"));

while($arRes = $rsData->Fetch())
{    
    $rsList = CUserFieldEnum::GetList(array("SORT"=>"ASC"), array(
        "USER_FIELD_ID" => $arRes["ID"],
    ));
    
    while($arList = $rsList->GetNext())
    {
        $arSizes[$arList["XML_ID"]] = $arList["ID"];
    }
    
        
}


$arResult["SIZES"] = $arSizes;*/

?>