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
global $INTRANET_TOOLBAR, $PHOENIX_TEMPLATE_ARRAY;

use Bitrix\Main\Context,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Iblock;

CPageOption::SetOptionString("main", "nav_page_in_session", "N");



if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);

$arParams["PARENT_SECTION"] = intval($arParams["PARENT_SECTION"]);
$arParams["SET_LAST_MODIFIED"] = $arParams["SET_LAST_MODIFIED"]==="Y";

$arParams["SORT_BY1"] = "SORT";
$arParams["SORT_ORDER1"]="ASC";

$arParams["SORT_BY2"] = "ID";
$arParams["SORT_ORDER2"]="ASC";



$arParams["CHECK_PERMISSIONS"] = $arParams["CHECK_PERMISSIONS"]!="N";

$arParams["USE_PERMISSIONS"] = $arParams["USE_PERMISSIONS"]=="Y";
if(!is_array($arParams["GROUP_PERMISSIONS"]))
	$arParams["GROUP_PERMISSIONS"] = array(1);

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


if($this->startResultCache($arParams["CACHE_TIME"], array($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"], $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"])))
{
	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	if(is_numeric($arParams["IBLOCK_ID"]))
	{
		$rsIBlock = CIBlock::GetList(array(), array(
			"ACTIVE" => "Y",
			"ID" => $arParams["IBLOCK_ID"],
		));
	}
	else
	{
		$rsIBlock = CIBlock::GetList(array(), array(
			"ACTIVE" => "Y",
			"CODE" => $arParams["IBLOCK_ID"],
			"SITE_ID" => SITE_ID,
		));
	}
	if($arResult = $rsIBlock->GetNext())
	{
		$arResult["USER_HAVE_ACCESS"] = $bUSER_HAVE_ACCESS;
		//SELECT
        
		$arSelect = array(
			"ID",
			"IBLOCK_ID",
			"NAME",
			"TIMESTAMP_X",
			"LIST_PAGE_URL",
			"DETAIL_TEXT",
			"DETAIL_TEXT_TYPE",
			"PREVIEW_TEXT",
			"PREVIEW_TEXT_TYPE",
			"PREVIEW_PICTURE",
			"PROPERTY_*"
		);
		
        
        $arFilter = array (
			"IBLOCK_ID" => $arResult["ID"],
			"IBLOCK_LID" => SITE_ID,
			"ACTIVE" => "Y",
			"ACTIVE_DATE" => "Y",
			"INCLUDE_SUBSECTIONS" => "N",
			"CHECK_PERMISSIONS" => $arParams['CHECK_PERMISSIONS'] ? "Y" : "N",
			"!PROPERTY_ADMIN_ONLY_VIEW_VALUE" => $PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"] ? "" : "Y",

			array(
				"LOGIC" => "OR",
		        array("!PROPERTY_HIDE_BLOCK_VALUE" => "Y"),
		        array("!PROPERTY_HIDE_BLOCK_LG_VALUE" => "Y")
	    	)
		);


		$arFilter = array_merge($arFilter, CPhoenix::getFilterByRegion());


		$arParams["PARENT_SECTION"] = CIBlockFindTools::GetSectionID(
			$arParams["PARENT_SECTION"],
			$arParams["PARENT_SECTION_CODE"],
			array(
				"GLOBAL_ACTIVE" => "Y",
				"IBLOCK_ID" => $arResult["ID"],
			)
		);

		if($arParams["PARENT_SECTION"]>0)
		{
            
			$arFilter["SECTION_ID"] = $arParams["PARENT_SECTION"];

			$arResult["SECTION"]= array();

			$ipropValues = new Iblock\InheritedProperty\SectionValues($arResult["ID"], $arParams["PARENT_SECTION"]);
			$arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();
            
           
            
            $arFil["IBLOCK_ID"] = $arParams["IBLOCK_ID"];
            $arFil["ID"] = $arParams["PARENT_SECTION"];
            
            /*$res = CIBlockSection::GetByID($arParams["PARENT_SECTION"]);
            if($ar_res = $res->GetNext())
                $arResult["SECTION"] = array_merge($arResult["SECTION"], $ar_res);*/
            
            $arSelect1 = Array("UF_*");
            $db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFil, false, $arSelect1);
            
            while($ar_result = $db_list->GetNext())
                $arResult["SECTION"] = array_merge($arResult["SECTION"], $ar_result);

   
		}
		else
		{
			$arResult["SECTION"]= false;
		}

        
		$arSort = array(
			$arParams["SORT_BY1"]=>$arParams["SORT_ORDER1"],
			$arParams["SORT_BY2"]=>$arParams["SORT_ORDER2"],
		);
		
		if(!array_key_exists("ID", $arSort))
			$arSort["ID"] = "DESC";

		$obParser = new CTextParser;
		$arResult["ITEMS"] = array();
		$arResult["ELEMENTS"] = array();
		$rsElement = CIBlockElement::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);
		$rsElement->SetUrlTemplates($arParams["DETAIL_URL"], "", $arParams["IBLOCK_URL"]);
		
        
        while($obElement = $rsElement->GetNextElement())
		{
			$arItem = $obElement->GetFields();

			$arButtons = CIBlock::GetPanelButtons(
				$arItem["IBLOCK_ID"],
				$arItem["ID"],
				0,
				array("SECTION_BUTTONS"=>false, "SESSID"=>false)
			);
			$arItem["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
			$arItem["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

			if($arParams["PREVIEW_TRUNCATE_LEN"] > 0)
				$arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], $arParams["PREVIEW_TRUNCATE_LEN"]);

			if(strlen($arItem["ACTIVE_FROM"])>0)
				$arItem["DISPLAY_ACTIVE_FROM"] = CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arItem["ACTIVE_FROM"], CSite::GetDateFormat()));
			else
				$arItem["DISPLAY_ACTIVE_FROM"] = "";

			$ipropValues = new Iblock\InheritedProperty\ElementValues($arItem["IBLOCK_ID"], $arItem["ID"]);
			$arItem["IPROPERTY_VALUES"] = $ipropValues->getValues();

			Iblock\Component\Tools::getFieldImageData(
				$arItem,
				array('PREVIEW_PICTURE', 'DETAIL_PICTURE'),
				Iblock\Component\Tools::IPROPERTY_ENTITY_ELEMENT,
				'IPROPERTY_VALUES'
			);

			$arItem["FIELDS"] = array();
            

            $arItem["PROPERTIES"] = $obElement->GetProperties();
                
			$arItem["DISPLAY_PROPERTIES"]=array();
            

			if ($arParams["SET_LAST_MODIFIED"])
			{
				$time = DateTime::createFromUserTime($arItem["TIMESTAMP_X"]);
				if (
					!isset($arResult["ITEMS_TIMESTAMP_X"])
					|| $time->getTimestamp() > $arResult["ITEMS_TIMESTAMP_X"]->getTimestamp()
				)
					$arResult["ITEMS_TIMESTAMP_X"] = $time;
			}

			$arResult["ITEMS"][] = $arItem;
			$arResult["ELEMENTS"][] = $arItem["ID"];
		}

		$navComponentParameters = array();

		if ($arParams["PAGER_BASE_LINK_ENABLE"] === "Y")
		{
			$pagerBaseLink = trim($arParams["PAGER_BASE_LINK"]);
			if ($pagerBaseLink === "")
			{
				if (
					$arResult["SECTION"]
					&& $arResult["SECTION"]["PATH"]
					&& $arResult["SECTION"]["PATH"][0]
					&& $arResult["SECTION"]["PATH"][0]["~SECTION_PAGE_URL"]
				)
				{
					$pagerBaseLink = $arResult["SECTION"]["PATH"][0]["~SECTION_PAGE_URL"];
				}
				elseif (
					isset($arItem) && isset($arItem["~LIST_PAGE_URL"])
				)
				{
					$pagerBaseLink = $arItem["~LIST_PAGE_URL"];
				}
			}

			if ($pagerParameters && isset($pagerParameters["BASE_LINK"]))
			{
				$pagerBaseLink = $pagerParameters["BASE_LINK"];
				unset($pagerParameters["BASE_LINK"]);
			}

			$navComponentParameters["BASE_LINK"] = CHTTP::urlAddParams($pagerBaseLink, $pagerParameters, array("encode"=>true));
		}

		$arResult["NAV_STRING"] = $rsElement->GetPageNavStringEx(
			$navComponentObject,
			$arParams["PAGER_TITLE"],
			$arParams["PAGER_TEMPLATE"],
			$arParams["PAGER_SHOW_ALWAYS"],
			$this,
			$navComponentParameters
		);

		$arResult["NAV_CACHED_DATA"] = null;
		$arResult["NAV_RESULT"] = $rsElement;
		$arResult["NAV_PARAM"] = $navComponentParameters;

		$this->setResultCacheKeys(array(
			"ID",
			"IBLOCK_TYPE_ID",
			"LIST_PAGE_URL",
			"NAV_CACHED_DATA",
			"NAME",
			"SECTION",
			"ELEMENTS",
			"IPROPERTY_VALUES",
			"ITEMS_TIMESTAMP_X",
		));
		$this->includeComponentTemplate();
	}
	else
	{
		$this->abortResultCache();
		Iblock\Component\Tools::process404(
			trim($arParams["MESSAGE_404"]) ?: GetMessage("T_NEWS_NEWS_NA")
			,true
			,$arParams["SET_STATUS_404"] === "Y"
			,$arParams["SHOW_404"] === "Y"
			,$arParams["FILE_404"]
		);
	}
}

if(isset($arResult["ID"]))
{


	if ($arResult["IPROPERTY_VALUES"] && $arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "")
		$APPLICATION->SetTitle($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arTitleOptions);
	elseif(isset($arResult["NAME"]))
		$APPLICATION->SetTitle($arResult["NAME"], $arTitleOptions);
	

	if ($arResult["IPROPERTY_VALUES"])
	{
		if ($arResult["IPROPERTY_VALUES"]["SECTION_META_TITLE"] != "")
			$APPLICATION->SetPageProperty("title", $arResult["IPROPERTY_VALUES"]["SECTION_META_TITLE"], $arTitleOptions);

		if ($arResult["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"] != "")
			$APPLICATION->SetPageProperty("keywords", $arResult["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"], $arTitleOptions);

		if ($arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"] != "")
			$APPLICATION->SetPageProperty("description", $arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"], $arTitleOptions);
	}


	if ($arParams["SET_LAST_MODIFIED"] && $arResult["ITEMS_TIMESTAMP_X"])
		Context::getCurrent()->getResponse()->setLastModified($arResult["ITEMS_TIMESTAMP_X"]);
	
	$bIsMainPage = $APPLICATION->GetCurDir(false) == SITE_DIR;

	if(!$bIsMainPage)
		$APPLICATION->AddChainItem($arResult["SECTION"]["NAME"], $arResult["SECTION"]["SECTION_PAGE_URL"]);

	
    return $arResult["SECTION"]["ID"];
}
?>