<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */


use Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock;


  
if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule('concept.phoenix'))
	return false;

global $PHOENIX_TEMPLATE_ARRAY;

    CPhoenix::phoenixOptionsValues($arParams["CURRENT_SITE"], 
        array(
            "start",
            "design",
            "politic"
        ));
    
    $arResult = array();

    $arFilter = Array('IBLOCK_CODE' => $arParams["IBLOCK_CODE"], "ID" => $arParams["CURRENT_FORM"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false);

    while($ob = $res->GetNextElement())
    {
        $arResult = $ob->GetFields();  
        $arResult["PROPERTIES"] = $ob->GetProperties();
    }


    $arResult["ELEMENT_ITEM"] = array();

    if(isset($arParams["PARAMS"]["element_item_id"]))
        $arResult["ELEMENT_ITEM"]["ID"] = $arParams["PARAMS"]["element_item_id"];
    
    if(isset($arParams["PARAMS"]["element_item_type"]))
        $arResult["ELEMENT_ITEM"]["TYPE"] = $arParams["PARAMS"]["element_item_type"];

    
    
    if(isset($arParams["PARAMS"]["element_item_name"]))
    {
        $arResult["ELEMENT_ITEM"]["NAME"] = trim($arParams["PARAMS"]["element_item_name"]);

        if(SITE_CHARSET == "windows-1251")
            $arResult["ELEMENT_ITEM"]["NAME"] = utf8win1251($arResult["ELEMENT_ITEM"]["NAME"]);


        
        $arResult["ELEMENT_ITEM"]["NAME"] = strip_tags(htmlspecialcharsBack($arResult["ELEMENT_ITEM"]["NAME"]));

        $html_name_container = "<div class='main-name bold'>";
        $html_name_container .= $arResult["ELEMENT_ITEM"]["NAME"];
        $html_name_container .= "</div> ";


        if(isset($arParams["PARAMS"]["ARTICLE"]))
            $arResult["ELEMENT_ITEM"]["ARTICLE"] = $arParams["PARAMS"]["ARTICLE"];

        if(strlen($arResult["ELEMENT_ITEM"]["ARTICLE"]))
        {
            $html_name_container .= "<div class='article italic'>";
            $html_name_container .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arResult["ELEMENT_ITEM"]["ARTICLE"];
            $html_name_container .= "</div> "; 
        }

        

        if(isset($arParams["PARAMS"]["element_item_offers"]))
        {
            $html_offers_container = "";

            if( !empty($arParams["PARAMS"]["element_item_offers"]) )
            {

                if(SITE_CHARSET == "windows-1251")
                {

                    foreach ($arParams["PARAMS"]["element_item_offers"] as $k => $arItem)
                    {
                        $arParams["PARAMS"]["element_item_offers"][$k]["NAME"] = utf8win1251($arItem["NAME"]);
                        $arParams["PARAMS"]["element_item_offers"][$k]["SKU_NAME"] = utf8win1251($arItem["SKU_NAME"]);
                    }
                }

                $i = 0;

                foreach ($arParams["PARAMS"]["element_item_offers"] as $k => $arItem)
                {

                    if($arItem["NA"] == "true")
                        continue;

                    $arParams["PARAMS"]["element_item_offers"][$k]["NAME"] = strip_tags($arItem["NAME"]);

                    
                    if($i != 0)
                        $html_offers_container .= "<br/>";

                    $html_offers_container .= "<span class='first_name'>";
                    $html_offers_container .= $arItem["SKU_NAME"].":&nbsp;";
                    $html_offers_container .= "</span>";
                    $html_offers_container .= "<span class='second_name italic'>";
                    $html_offers_container .= $arItem["NAME"];
                    $html_offers_container .= "</span> ";

                    

                    $i++;
                }
            }

            $arResult["ELEMENT_ITEM"]["OFFERS"] = $arParams["PARAMS"]["element_item_offers"];

            if(strlen($html_offers_container))
                $html_name_container .= $html_offers_container;
        }

        $arResult["ELEMENT_ITEM"]["HTML_NAME"] = $html_name_container;

        $arResult["ELEMENT_ITEM"]["HTML_NAME_VALUE"] = strip_tags(str_replace("'", "&apos;", $html_name_container));

        
        if(isset($arParams["PARAMS"]["PHOTO"]))
            $arResult["ELEMENT_ITEM"]["PHOTO"] = $arParams["PARAMS"]["PHOTO"];

    }

    if(isset($arParams["PARAMS"]["element_item_price"]))
    {
        $arResult["ELEMENT_ITEM"]["PRICE"] = trim($arParams["PARAMS"]["element_item_price"]);

        if(SITE_CHARSET == "windows-1251")
            $arResult["ELEMENT_ITEM"]["PRICE"] = utf8win1251($arParams["PARAMS"]["element_item_price"]);

        $arResult["ELEMENT_ITEM"]["PRICE"] = strip_tags(str_replace("'", "&apos;", $arResult["ELEMENT_ITEM"]["PRICE"]));
    }

    if(isset($arParams["PARAMS"]["element_item_input_name"]))
    {
        $arResult["ELEMENT_ITEM"]["TYPE"] = $arParams["PARAMS"]["element_item_input_name"];

        if(SITE_CHARSET == "windows-1251")
            $arResult["ELEMENT_ITEM"]["INPUT_NAME"] = utf8win1251($arParams["PARAMS"]["element_item_input_name"]);
    }
    
    

$this->includeComponentTemplate();
?>
