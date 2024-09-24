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
global $PHOENIX_TEMPLATE_ARRAY, $DB;


use Bitrix\Main\Loader;


if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

if(!is_array($arParams["ELEMENTS_ID"]) || empty($arParams["ELEMENTS_ID"]))
	return false;


$bUSER_HAVE_ACCESS = !$arParams["USE_PERMISSIONS"];
if($arParams["USE_PERMISSIONS"] && isset($GLOBALS["USER"]) && is_object($GLOBALS["USER"]))
{
	$arUserGroupArray = $USER->GetUserGroupArray();
	foreach($arParams["GROUP_PERMISSIONS"] as $PERM)
	{
		if(in_array($PERM, $arUserGroupArray))
		{
			$bUSER_HAVE_ACCESS = true;
			break;
		}
	}
}

$arSort = array(
	$arParams["SORT_BY1"]=>$arParams["SORT_ORDER1"],
	"ID" => "DESC",
);



if($this->startResultCache($arParams["CACHE_TIME"], array($arParams["ELEMENTS_ID"], $bUSER_HAVE_ACCESS)))
{
	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	$arIcon = array();

    $arIcon["blog"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_BLOG_ICON"]['VALUE'];

    $arIcon["video"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_VIDEO_ICON"]['VALUE'];
    $arIcon["interview"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_INTERVIEW_ICON"]['VALUE'];
    $arIcon["opinion"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_OPINION_ICON"]['VALUE'];
    $arIcon["case"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_CASE_ICON"]['VALUE'];


    $arrSect = array(

        "icon-default-blog" => array(
            "NAME"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_BLOG_NAME"]["VALUE"],
            "SRC" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_BLOG_ICON"]["SRC"]
        ),
        "icon-default-video" => array(
            "NAME"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_VIDEO_NAME"]["VALUE"],
            "SRC" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_VIDEO_ICON"]["SRC"]
        ),
        "icon-default-interview" => array(
            "NAME"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_INTERVIEW_NAME"]["VALUE"],
            "SRC" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_INTERVIEW_ICON"]["SRC"]
        ),
        "icon-default-opinion" => array(
            "NAME"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_OPINION_NAME"]["VALUE"],
            "SRC" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_OPINION_ICON"]["SRC"]
        ),
        "icon-default-case" => array(
            "NAME"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_CASE_NAME"]["VALUE"],
            "SRC" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_CASE_ICON"]["SRC"]
        ),
        "icon-default-sens" => array(
            "NAME"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_SENS_NAME"]["VALUE"],
            "SRC" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_SENS_ICON"]["SRC"]
        ),

    );
    

	$arResult = array();
	$arSectionsId = array();
	$arIblocks = array();
	$nTopCount = false;
	



	$typeContent = array();
	$typeContentDefID = 0;

    $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BLOG']["IBLOCK_ID"], "CODE"=>"TYPE_CONTENT"));
	while($enum_fields = $property_enums->GetNext())
	{
		$typeContent[$enum_fields["ID"]] = $enum_fields;

		if(!$typeContentDefID)
			$typeContentDefID = $enum_fields["ID"];
	}


	

	if(strlen($arParams["ELEMENTS_COUNT"])>0)
		$nTopCount = array("nTopCount" => $arParams["ELEMENTS_COUNT"]);
	
	$arResult["SHOW_DATA"] = false;

	$arSelect = array("ID", "NAME", "PREVIEW_TEXT", "IBLOCK_CODE", "ACTIVE", "PREVIEW_PICTURE", "ACTIVE_TO", "ACTIVE_FROM", "IBLOCK_SECTION_ID", "DETAIL_PAGE_URL", "PROPERTY_TYPE_CONTENT");

	$filterOr1 = array(
                "LOGIC" => "OR",
                array("SECTION_SCOPE"=>"IBLOCK", "SECTION_ACTIVE" => "Y", "SECTION_GLOBAL_ACTIVE" => "Y"),
                array("SECTION_ID" => false, "INCLUDE_SUBSECTIONS"=>"N") 
            );


    $filterOr2 = array(
                "LOGIC" => "OR",
                array("ACTIVE_DATE"=>"Y"),
                array("<=DATE_ACTIVE_FROM" => date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")), mktime())) 
            );

	$arFilter = Array("ID"=> $arParams["ELEMENTS_ID"], "ACTIVE"=>"Y", $filterOr1, $filterOr2);
	
    $res = CIBlockElement::GetList($arSort, $arFilter, false, $nTopCount, $arSelect);

    while($ob = $res->GetNextElement())
    {
        $arItem = $ob->GetFields();

        $arItem["TYPE_ELEMENT"] = "";

        if(preg_match("/^concept_phoenix_action_/", $arItem["IBLOCK_CODE"]))
        	$arItem["TYPE_ELEMENT"] = "action";

        if(preg_match("/^concept_phoenix_blog_/", $arItem["IBLOCK_CODE"]))
        	$arItem["TYPE_ELEMENT"] = "blog";

        if(preg_match("/^concept_phoenix_news_/", $arItem["IBLOCK_CODE"]))
        	$arItem["TYPE_ELEMENT"] = "news";

        if($arItem["TYPE_ELEMENT"] != "action" && $arItem["ACTIVE"] != "Y")
        	continue;

        

        if(strlen($arItem["ACTIVE_TO"]) && !$arResult["SHOW_DATA"])
        	$arResult["SHOW_DATA"] = true;


        if($arItem["TYPE_ELEMENT"] == "blog" || $arItem["TYPE_ELEMENT"] == "news")
        {
	        if($arItem["IBLOCK_SECTION_ID"])
	        {
        		$arIblocks[$arItem["IBLOCK_ID"]][$arItem["IBLOCK_SECTION_ID"]] = $arItem["IBLOCK_SECTION_ID"];
	        }

	        if(strlen($arItem["ACTIVE_FROM"]) && $arParams["HIDE_DATE"] != "Y")
	        {
	        	$arItem["ACTIVE_FROM_FORMATED"] = CIBlockFormatProperties::DateFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]["VALUE"], MakeTimeStamp($arItem["ACTIVE_FROM"], CSite::GetDateFormat()));
	        }
        }

        if($arItem["TYPE_ELEMENT"] == "blog")
        {
        	if($arItem["PROPERTY_TYPE_CONTENT_ENUM_ID"] == "")
	            $arItem["PROPERTY_TYPE_CONTENT_ENUM_ID"] = $typeContentDefID;

	        $type_content = "icon-default-".$typeContent[$arItem["PROPERTY_TYPE_CONTENT_ENUM_ID"]]["XML_ID"];
	        
	        $arItem["CATEGORY_ICON_NAME"] = $arrSect[$type_content]["NAME"];
	        $arItem["CATEGORY_ICON_SRC"] = $arrSect[$type_content]["SRC"];
	        $arItem["CATEGORY_ICON_CLASS"] = $type_content;

	        $arItem["DETAIL_TITLE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["MORE"]["~VALUE"];
        }

        if($arItem["TYPE_ELEMENT"] == "action")
        {
        	$arItem["CATEGORY_NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["NEWS_LIST_ACTIONS_DEF_NAME"];
			$arItem["CATEGORY_URL"] = SITE_DIR."action/";

			$arItem["CATEGORY_ICON_NAME"] =  $PHOENIX_TEMPLATE_ARRAY["MESS"]["NEWS_LIST_ACTIONS_CATEGORY_ICON_NAME"];
	        $arItem["CATEGORY_ICON_SRC"] = "";
	        $arItem["CATEGORY_ICON_CLASS"] = "icon-default-sens";

			$arItem["DETAIL_TITLE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["MORE"]["~VALUE"];
			

			if(getmicrotime() > MakeTimeStamp($arItem["ACTIVE_TO"]) && strlen($arItem["ACTIVE_TO"]) > 0)
				$arItem["CURRENT_TIME_ACTION"] = array("CLASS"=> "off", "TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["ACTIONS_ACT_OFF"]);

			else
			{
				if(strlen($arItem["ACTIVE_TO"]))
				{

					$arItem["CURRENT_TIME_ACTION"] = array(
						"CLASS"=> "to", 
						"TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["ACTIONS_ACT_ON_TO"].CIBlockFormatProperties::DateFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]["VALUE"], MakeTimeStamp($arItem["ACTIVE_TO"], CSite::GetDateFormat())));
				}
				else
				{
					$arItem["CURRENT_TIME_ACTION"] = array("CLASS"=> "", "TITLE" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["ACTIONS_ACT_ON"]);
				}
			}
        }

        if($arItem["TYPE_ELEMENT"] == "news")
        {
        	$arItem["CATEGORY_ICON_NAME"] = "news";
	        $arItem["CATEGORY_ICON_SRC"] = "";
	        $arItem["CATEGORY_ICON_CLASS"] = "icon-default-case";

        	$arItem["DETAIL_TITLE"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["MORE"]["~VALUE"];
        }


        $img = array();
        $arItem['PREVIEW_PICTURE_SRC'] = "";

        if($arItem['PREVIEW_PICTURE'])
        {
        	$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>600, 'height'=>400), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

        	$arItem['PREVIEW_PICTURE_SRC'] = $img['src']; 
        }


        $arResult["ITEMS"][] = $arItem;
    }

    
    if(!empty($arIblocks))
    {    
	    foreach ($arIblocks as $id => $sectionsId)
	    {
	    	$SectionList = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>$id, "ID" => $sectionsId), false, array("ID","NAME","SECTION_PAGE_URL", "UF_CATEGORY_LIST"));

		    while ($SectionListRes = $SectionList->GetNext())
		    {
		        $arResult["SECTIONS"][$SectionListRes["ID"]]=$SectionListRes;
		    }
	    }
    }

    if(!empty($arResult["ITEMS"]))
    {
	    foreach ($arResult["ITEMS"] as $key => $arItem)
	    {
	    	if(!isset($arResult["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]))
	    		continue;
	    	
			if(strlen($arResult["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]["NAME"]))
			{
				$arItem["CATEGORY_NAME"] = $arResult["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]["NAME"];
				$arItem["CATEGORY_URL"] = $arResult["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]["SECTION_PAGE_URL"];
			}
			else
			{
				$name = "";
				$url = "";

				if($arItem["TYPE_ELEMENT"] == "news")
				{
					$name = $PHOENIX_TEMPLATE_ARRAY["MESS"]["NEWS_LIST_NEWS_DEF_NAME"];
					$url = SITE_DIR."news/";
				}
				if($arItem["TYPE_ELEMENT"] == "blog")
				{
					$name = $PHOENIX_TEMPLATE_ARRAY["MESS"]["NEWS_LIST_BLOG_DEF_NAME"];
					$url = SITE_DIR."blog/";
				}

				$arItem["CATEGORY_NAME"] = $name;
				$arItem["CATEGORY_URL"] = $url;
			}

	    	$arResult["ITEMS"][$key] = $arItem;
	    }
    }


    if(isset($arParams["SEARCH_SORT"]))
    {
    	if($arParams["SEARCH_SORT"] == "Y")
    	{
    		$newArResult = array();

			if(!empty($arResult["ITEMS"]))
			{
				foreach ($arResult["ITEMS"] as $key => $value)
				{
				    $newKey = array_search(intval($value["ID"]), $arParams["ELEMENTS_ID"]);
				    $newArResult[$newKey] = $value;
				}
			}
			if(!empty($newArResult))
			{
				ksort($newArResult);
				$arResult["ITEMS"] = array_values($newArResult);
			}
    	}

    }

	$this->includeComponentTemplate();
}