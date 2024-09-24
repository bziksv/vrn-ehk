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

	foreach ($arResult["SECTIONS"] as &$arSection)
	{
		$elementFilter = $elementCountFilter;
		$elementFilter["SECTION_ID"] = $arSection["ID"];

		if ($arSection['RIGHT_MARGIN'] == ($arSection['LEFT_MARGIN'] + 1))
			$elementFilter['INCLUDE_SUBSECTIONS'] = 'N';
		
		if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"] == "Y")
			$elementFilter['CATALOG_AVAILABLE'] = 'Y';

		

		$elementFilter = array_merge($elementFilter, CPhoenix::getFilterByRegion());

	    $arStoresFilter = CPhoenix::getFilterByStores();

	    if(!empty($arStoresFilter))
	        $elementFilter[] = $arStoresFilter;
	
		$arSection["~ELEMENT_CNT"] = CIBlockElement::GetList(array(), $elementFilter, array());
		$arSection["ELEMENT_CNT"] = $arSection["~ELEMENT_CNT"];
		
	}
	unset($arSection);

	if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["HIDE_EMPTY_CATALOG"]["VALUE"]["ACTIVE"]=="Y")
	{
		foreach ($arResult["SECTIONS"] as $key => $arSection){

			if($arSection["ELEMENT_CNT"] === "0")
				unset($arResult["SECTIONS"][$key]);
		
		}
	}
	    
	$this->__component->arResultCacheKeys = array_merge($this->__component->arResultCacheKeys, array('SECTIONS'));
}

if(strlen($arResult["SECTION"]["SECTION_PAGE_URL"]) > 0)
    $arResult["SECTION_BACK"] = $arResult["SECTION"]["SECTION_PAGE_URL"];
else
    $arResult["SECTION_BACK"] = SITE_DIR."catalog/";
?>