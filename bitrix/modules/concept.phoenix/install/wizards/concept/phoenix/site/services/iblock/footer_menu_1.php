<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

set_time_limit(0);

if(WIZARD_INSTALL_DEMO_DATA)
{


	if(!CModule::IncludeModule("iblock"))
		return;


	$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/ru/footer_menu_1.xml";

	$iblockCode = "concept_phoenix_footer_menu";
	$iblockType = "concept_phoenix"."_".WIZARD_SITE_ID;


	$rsIBlock = CIBlock::GetList(array(), array("CODE" => $iblockCode, "TYPE" => $iblockType));
	$iblockID = false; 

	if($arIBlock = $rsIBlock->Fetch())
		$iblockID = $arIBlock["ID"]; 


	if($iblockID != false)
	{
		$permissions = Array(
			"1" => "X",
			"2" => "R"
		);
	    
		$dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "content_editor"));
	    
		if($arGroup = $dbGroup -> Fetch())
		{
			$permissions[$arGroup["ID"]] = 'W';
		};
	    
	    
	    ImportXMLFile(
	        $iblockXMLFile,
	        $iblockType,
	        WIZARD_SITE_ID,
	        "N",
	        "N",
	        true
	    );
	}

}
?>