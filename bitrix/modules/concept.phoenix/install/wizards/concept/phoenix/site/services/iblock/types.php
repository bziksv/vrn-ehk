<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

set_time_limit(0);

if(!CModule::IncludeModule("iblock"))
	return;
	
$arTypes = Array(
	Array(
		"ID" => "concept_phoenix",
		"SECTIONS" => "Y",
		"IN_RSS" => "N",
		"SORT" => 600,
		"LANG" => Array(),
	)
    
);



$arLanguages = Array();
$rsLanguage = CLanguage::GetList($by, $order, array());
while($arLanguage = $rsLanguage->Fetch())
	$arLanguages[] = $arLanguage["LID"];


$iblockType = new CIBlockType;

foreach($arTypes as $arType)
{
	$dbType = CIBlockType::GetList(Array(),Array("=ID" => $arType["ID"]."_".WIZARD_SITE_ID));
    
	if($dbType->Fetch())
		continue;
        
    
    $code = $arType["ID"];
    $arType["ID"] = $arType["ID"]."_".WIZARD_SITE_ID;

	foreach($arLanguages as $languageID)
	{
		WizardServices::IncludeServiceLang("type.php", $languageID);

		$arType["LANG"][$languageID]["NAME"] = GetMessage($code."_TYPE_NAME")." (".WIZARD_SITE_ID.")";
		$arType["LANG"][$languageID]["ELEMENT_NAME"] = '';

		if ($arType["SECTIONS"] == "Y")
			$arType["LANG"][$languageID]["SECTION_NAME"] = '';
	}
	
	

	$ID = $iblockType->Add($arType);

}

COption::SetOptionString('iblock','combined_list_mode','Y');
?>