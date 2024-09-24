<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;



$arComponentParameters = array(
	"GROUPS" => array(
		
	),
	"PARAMETERS" => array(

		"SEF_MODE" => Array(
			"main" => array(
				"NAME" => GetMessage("T_IBLOCK_SEF_PAGE_NEWS"),
				"DEFAULT" => "",
				"VARIABLES" => array(),
			),
			"page" => array(
				"NAME" => GetMessage("T_IBLOCK_SEF_PAGE_NEWS_DETAIL"),
				"DEFAULT" => "#SECTION#/",
				"VARIABLES" => array("SECTION_CODE", "SECTION_ID"),
			),
		),
		
	),
);

CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);


if($arCurrentValues["USE_PERMISSIONS"]!="Y")
	unset($arComponentParameters["PARAMETERS"]["GROUP_PERMISSIONS"]);

?>
