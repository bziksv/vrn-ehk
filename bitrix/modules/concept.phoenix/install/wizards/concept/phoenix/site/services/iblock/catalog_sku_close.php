<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();


set_time_limit(0);

if(!CModule::IncludeModule("iblock"))
	return;


$iblockCode = "concept_phoenix_catalog_offers";
$iblockCode1 = $iblockCode."_".WIZARD_SITE_ID;
$iblockType = "concept_phoenix"."_".WIZARD_SITE_ID;


$rsIBlock = CIBlock::GetList(array(), array("CODE" => $iblockCode, "TYPE" => $iblockType));
$iblockID = false; 

if($arIBlock = $rsIBlock->Fetch())
	$iblockID = $arIBlock["ID"]; 

if($iblockID != false)
{
	//IBlock fields
	$iblock = new CIBlock;
    
	$arFields = Array(
		"ACTIVE" => "Y",
		"FIELDS" => array ( 'IBLOCK_SECTION' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'ACTIVE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'Y', ), 'ACTIVE_FROM' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '=today', ), 'ACTIVE_TO' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'SORT' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'NAME' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => '', ), 'PREVIEW_PICTURE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => array ( 'FROM_DETAIL' => 'N', 'SCALE' => 'N', 'WIDTH' => '', 'HEIGHT' => '', 'IGNORE_ERRORS' => 'N', ), ), 'PREVIEW_TEXT_TYPE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'html', ), 'PREVIEW_TEXT' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'DETAIL_PICTURE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => array ( 'SCALE' => 'N', 'WIDTH' => '', 'HEIGHT' => '', 'IGNORE_ERRORS' => 'N', ), ), 'DETAIL_TEXT_TYPE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'text', ), 'DETAIL_TEXT' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'XML_ID' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'CODE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'TAGS' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'SECTION_CODE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ) ),  
		"CODE" => $iblockCode1, 
		"XML_ID" => $iblockCode1,
		"NAME" => $iblock->GetArrayByID($iblockID, "NAME"),
		"EDIT_FILE_BEFORE" => "/bitrix/modules/concept.phoenix/iblock_forms_edit/catalog_offers_before_save.php",
		"EDIT_FILE_AFTER" => "/bitrix/modules/concept.phoenix/iblock_forms_edit/catalog_offers.php"
	);
	
	$iblock->Update($iblockID, $arFields);



	$rsIBlock = CIBlock::GetList(array(), array("CODE" => "concept_phoenix_catalog_".WIZARD_SITE_ID, "TYPE" => $iblockType));

	if($arIBlock = $rsIBlock->Fetch())
	{

		$arProperty = array();
		$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblockID));
		while($arProp = $dbProperty->Fetch())
			$arProperty[$arProp["CODE"]] = $arProp["ID"];

		CIBlockProperty::Delete($arProperty["CML2_LINK"]);

		$ID_SKU = CCatalog::LinkSKUIBlock($arIBlock["ID"], $iblockID);
		$rsCatalogs = CCatalog::GetList(array(), array('IBLOCK_ID' => $iblockID), false, false, array('IBLOCK_ID'));
		if($arCatalog = $rsCatalogs->Fetch()){
			CCatalog::Update($iblockID, array('PRODUCT_IBLOCK_ID' => $arIBlock["ID"], 'SKU_PROPERTY_ID' => $ID_SKU));
		}
		else{
			CCatalog::Add(array('IBLOCK_ID' => $iblockID, 'PRODUCT_IBLOCK_ID' => $arIBlock["ID"], 'SKU_PROPERTY_ID' => $ID_SKU));
		}


	}

}


?>
