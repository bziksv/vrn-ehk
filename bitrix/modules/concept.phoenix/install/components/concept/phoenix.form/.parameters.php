<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

if(!CModule::IncludeModule("iblock"))
	return;

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		
		"CURRENT_SITE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("KRAKEN_FORM_CUR"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"CURRENT_FORM" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("KRAKEN_FORM_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		
	),
);


CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);

?>