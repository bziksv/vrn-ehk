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

use Bitrix\Main\Loader,
	Bitrix\Main,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	$this->abortResultCache();
	ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
	return;
}

if(!Loader::IncludeModule('concept.phoenix'))
	return false;

global $PHOENIX_TEMPLATE_ARRAY;

CPhoenix::phoenixOptionsValues($arParams["SITE_ID"], array(
	"start",
	"design",
	"contacts",
	"comments",
	"menu",
	"footer",
	"catalog",
	"shop",
	"blog",
	"news",
	"actions",
	"lids",
	"services",
	"politic",
	"customs",
	"other",
	"search",
	"catalog_fields",
	"compare",
	"brands",
	"personal",
	"rating",
	"region"
), "Y");
/*************************************************************************
	Processing of received parameters
*************************************************************************/
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = intval($PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_ID"]);
$arParams["IBLOCK_ID_PAGE"] = $PHOENIX_TEMPLATE_ARRAY["PAGES"][$arParams["CURRENT_PAGE"]];


$arParams["TOP_DEPTH"] = intval($arParams["TOP_DEPTH"]);
if($arParams["TOP_DEPTH"] <= 0)
	$arParams["TOP_DEPTH"] = 2;
    



/*************************************************************************
			Work with cache
*************************************************************************/
//if($this->startResultCache())
//{
	if($arParams['PANEL'] == "addpage")
	{
		$arResult["SECTIONS"]=array();

        $arFilter = Array('IBLOCK_ID' => $PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_ID"]);
        $dbResSect = CIBlockSection::GetList(Array("sort"=>"asc"), $arFilter, false, array("ID", "ACTIVE", "SECTION_PAGE_URL", "NAME", "SORT"));
        
        while($sectRes = $dbResSect->GetNext())
            $arResult["SECTIONS"][] = $sectRes;
	}


	if($arParams['PANEL'] == "forms")
	{
		$arSelect = Array("SORT", "ID", "NAME", "IBLOCK_SECTION_ID", "IBLOCK_ID");
		$arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["IBLOCK_ID"]);
		$res = CIBlockElement::GetList(Array("sort"=>"asc"), $arFilter, false, false, $arSelect);
		while($ob = $res->GetNextElement())
		{

			$arFields = $ob->GetFields();

			if(strlen($arResult["FORMS_ELEMENTS_IBLOCK_ID"])<=0)
				$arResult["FORMS_ELEMENTS_IBLOCK_ID"] = $arFields['IBLOCK_ID'];


			$arResult["FORMS_ELEMENTS"][] = $arFields;

			if(strlen($arFields["IBLOCK_SECTION_ID"])<=0)
				$arResult["FORMS_ELEMENTS_NO_SECTION"][] = $arFieldsModal;

		}

		$arSelect = Array("SORT", "ID", "NAME", "IBLOCK_ID");
		$dbResSect = CIBlockSection::GetList(
		   Array("SORT"=>"ASC"),
		   Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["IBLOCK_ID"]),
		   false,
		   $arSelect
		);


		while($sectRes = $dbResSect->GetNext())
		{
			$arSections[] = $sectRes;
		}


	    if(!empty($arSections))
	    {
	        foreach($arSections as $arSection){  
			
	    		foreach($arResult["FORMS_ELEMENTS"] as $key=>$arItem){
	    			
	    			 if($arItem['IBLOCK_SECTION_ID'] == $arSection['ID']){
	    				$arSection['ELEMENTS'][] =  $arItem;
	    			 }
	    		}
	    		asort($arSection['ELEMENTS']);
	    		
	    		$arElementGroups[] = $arSection;
	    	}
	  
	    	$arResult["FORMS_IN_SECTION"] = $arElementGroups;
	    }
	}


	if($arParams['PANEL'] == "modals")
	{

		$arSelect = Array("SORT", "ID", "NAME", "IBLOCK_SECTION_ID", "IBLOCK_ID");
		$arFilterModal = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['POPUP']["IBLOCK_ID"]);
		$resModal = CIBlockElement::GetList(Array("sort"=>"asc"), $arFilterModal, false, false, $arSelect);
		while($obModal = $resModal->GetNextElement())
		{

			$arFieldsModal = $obModal->GetFields();

			if(strlen($arResult["MODALS_ELEMENTS_IBLOCK_ID"])<=0)
				$arResult["MODALS_ELEMENTS_IBLOCK_ID"] = $arFieldsModal['IBLOCK_ID'];

			
			$arResult["MODALS_ELEMENTS"][] = $arFieldsModal;

			if(strlen($arFieldsModal["IBLOCK_SECTION_ID"]) <= 0)
				$arResult["MODALS_ELEMENTS_NO_SECTION"][] = $arFieldsModal;

		}


		$arSelect = Array("SORT", "ID", "NAME", "IBLOCK_ID");
		$dbResSectModal = CIBlockSection::GetList(
		   Array("SORT"=>"ASC"),
		   Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['POPUP']["IBLOCK_ID"]),
		   false,
		   $arSelect
		);

		while($sectResModal = $dbResSectModal->GetNext())
		{
			$arSectionsModal[] = $sectResModal;
		}


		if(!empty($arSectionsModal) && is_array($arSectionsModal))
		{

			foreach($arSectionsModal as $arSectionModal){  

				if(!empty($arResult["MODALS_ELEMENTS"]) && is_array($arResult["MODALS_ELEMENTS"]))
				{
					foreach($arResult["MODALS_ELEMENTS"] as $key=>$arItem){
					
					 if($arItem['IBLOCK_SECTION_ID'] == $arSectionModal['ID']){
							$arSectionModal['ELEMENTS'][] =  $arItem;
						 }
					}

				}

				if(isset($arSectionModal['ELEMENTS']))
				{

					if(!empty($arSectionModal['ELEMENTS']))
					{
						asort($arSectionModal['ELEMENTS']);
						$arElementGroupsModal[] = $arSectionModal;
					}
				
				}
				
			}

		}

		$arResult["MODALS_IN_SECTION"] = $arElementGroupsModal;


	}


	if($arParams['PANEL'] == "iblist")
	{

		$resIb = CIBlock::GetList(
		   Array("sort" => "asc"), 
		   Array(
		      'TYPE'=> $PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_TYPE"], 
		      'SITE_ID'=> $arParams["SITE_ID"], 
		      'ACTIVE'=>'Y',
		      "!CODE"=> array($PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_CODE"], $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["IBLOCK_CODE"], $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['POPUP']["IBLOCK_CODE"])
		   ), false
		);

		while($ar_res = $resIb->Fetch())
		{
		   $arResult["IBLIST"][] = $ar_res;
		}

	}

	if($arParams['PANEL'] == "edit-sets")
	{

		$arResult["PROACTIVE_FILTER_MASK"] = array(
	        "/bitrix/admin/concept_phoenix_admin_index.php",
	        "/bitrix/tools/concept.phoenix/ajax/*"
	    );


	    $strSql = "SHOW TABLES LIKE 'b_sec_filter_mask'";
	    $res = $DB->Query($strSql, false, $err_mess.__LINE__);

	    if($res->SelectedRowsCount() > 0)
	    {

	        $strSql = "SELECT * FROM b_sec_filter_mask"; 
	        $res = $DB->Query($strSql, false, $err_mess.__LINE__);


	        if($res->SelectedRowsCount() > 0)
	        {
	            while ($ar_result = $res->GetNext())
	            {
	                if(empty($arResult["PROACTIVE_FILTER_MASK"]))
	                    break;

	                if(in_array($ar_result["FILTER_MASK"], $arResult["PROACTIVE_FILTER_MASK"]) && (!$ar_result["SITE_ID"] || $ar_result["SITE_ID"] === SITE_ID))
	                {
	                    $keyArFilter = array_keys($arResult["PROACTIVE_FILTER_MASK"], $ar_result["FILTER_MASK"]);
	                    unset($arResult["PROACTIVE_FILTER_MASK"][$keyArFilter[0]]);
	                }
	            };
	            
	        
	        }
	    }


	}

	

	$this->includeComponentTemplate();
//}

?>