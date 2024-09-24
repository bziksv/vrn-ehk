<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("PHOENIX_T_IBLOCK_SMART_FILTER"),
	"DESCRIPTION" => GetMessage("PHOENIX_T_IBLOCK_SMART_FILTER_DESC"),
	"ICON" => "/images/iblock_filter.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 70,
	"PATH" => array(
		"ID" => "concept",
        "NAME" => GetMessage("PHOENIX_T_IBLOCK_DESC_COMPANY_GENERATOR"),
		"SORT" => 100,
		"CHILD" => array(
			"ID" => "pages_phx",
			"NAME" => GetMessage("PHOENIX_T_IBLOCK_DESC_PAGE_GENERATOR"),
			"SORT" => 5,
			"CHILD" => array(
				"ID" => "page_cmpx",
			),
		),
	),
);
?>