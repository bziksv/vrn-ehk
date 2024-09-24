<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


$arStartPage = Array();

$arStartPage["none"] = GetMessage("NONE");
$arStartPage["catalog"] = GetMessage("CATALOG");
$arStartPage["blog"] = GetMessage("BLOG");
$arStartPage["news"] = GetMessage("NEWS");
$arStartPage["actions"] = GetMessage("ACTIONS");

$arComponentParameters = array(
	"GROUPS" => array(
		
	),
	"PARAMETERS" => array(

		"START_PAGE" => Array(
			"NAME" => GetMessage("START_PAGE"),
			"TYPE" => "LIST",
			"VALUES" => $arStartPage
		),

		"SHOW_RESULTS" => array(
			"NAME" => GetMessage("SEARCH_SHOW_RESULTS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),

		"CONTAINER_ID" => Array(
			"NAME" => GetMessage("SEARCH_CONTAINER_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "search-input-box"
		),
		"INPUT_ID" => Array(
			"NAME" => GetMessage("SEARCH_INPUT_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "search-input"
		)
		
	),
);
?>