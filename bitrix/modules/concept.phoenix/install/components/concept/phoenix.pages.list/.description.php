<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("PHOENIX_T_IBLOCK_LISTTPAGE"),
	"DESCRIPTION" => GetMessage("PHOENIX_T_IBLOCK_LISTTPAGE_DESC"),
	"ICON" => "/images/sections_top_count.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 20,
	"PATH" => array(
		"ID" => "concept",
        "NAME" => GetMessage("PHOENIX_T_IBLOCK_DESC_COMPANY_GENERATOR"),
		"SORT" => 200,
		"CHILD" => array(
			"ID" => "pages_phx",
			"NAME" => GetMessage("PHOENIX_T_IBLOCK_DESC_PAGE_GENERATOR"),
			"SORT" => 30,
			"CHILD" => array(
				"ID" => "page_cmpx",
			),
		),
	),
);

?>