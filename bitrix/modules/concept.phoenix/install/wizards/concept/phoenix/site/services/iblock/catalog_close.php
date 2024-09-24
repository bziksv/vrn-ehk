<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

set_time_limit(0);

if(!CModule::IncludeModule("iblock"))
	return;


$iblockCode = "concept_phoenix_catalog";
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
		"FIELDS" => array ( 
			"PREVIEW_PICTURE" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => array(
						"FROM_DETAIL" => "N",
						"DELETE_WITH_DETAIL" => "N",
						"UPDATE_WITH_DETAIL" => "N",
					),
				), 
			'IBLOCK_SECTION' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => Array('KEEP_IBLOCK_SECTION_ID' => "Y"), ), 'ACTIVE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'Y', ), 'ACTIVE_FROM' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '=today', ), 'ACTIVE_TO' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'SORT' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'NAME' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => '', ), 
			 'PREVIEW_TEXT_TYPE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'html', ), 
			 'PREVIEW_TEXT' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 
			 'DETAIL_PICTURE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => array ( 'SCALE' => 'N', 'WIDTH' => '', 'HEIGHT' => '', 'IGNORE_ERRORS' => 'N', ), ), 'DETAIL_TEXT_TYPE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'text', ), 
			 'DETAIL_TEXT' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 
			 'XML_ID' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 
			 'CODE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => array ( 'UNIQUE' => 'Y', 'TRANSLITERATION' => 'Y', 'TRANS_LEN' => 100, 'TRANS_CASE' => 'L', 'TRANS_SPACE' => '-', 'TRANS_OTHER' => '-', 'TRANS_EAT' => 'Y', 'USE_GOOGLE' => 'N', ), ), 'TAGS' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'SECTION_CODE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => array ( 'UNIQUE' => 'Y', 'TRANSLITERATION' => 'Y', 'TRANS_LEN' => 100, 'TRANS_CASE' => 'L', 'TRANS_SPACE' => '-', 'TRANS_OTHER' => '-', 'TRANS_EAT' => 'Y', 'USE_GOOGLE' => 'N', ), ) ), 
		"CODE" => $iblockCode1, 
		"XML_ID" => $iblockCode1,
		"NAME" => $iblock->GetArrayByID($iblockID, "NAME"),
		"EDIT_FILE_BEFORE" => "/bitrix/modules/concept.phoenix/iblock_forms_edit/catalog_before_save.php",
		"EDIT_FILE_AFTER" => "/bitrix/modules/concept.phoenix/iblock_forms_edit/catalog.php"
	);
	
	$iblock->Update($iblockID, $arFields);
}


?>
