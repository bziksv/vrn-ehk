<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


$arSorts = array("ASC"=>GetMessage("T_IBLOCK_DESC_ASC"), "DESC"=>GetMessage("T_IBLOCK_DESC_DESC"));
$arSortFields = array(
	"ID"=>GetMessage("T_IBLOCK_DESC_FID"),
	"ACTIVE_FROM"=>GetMessage("T_IBLOCK_DESC_FACT"),
	"SORT"=>GetMessage("T_IBLOCK_DESC_FSORT"),
);

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"ELEMENTS_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PHOENIX_T_NEWS_LIST_ELEMENTS_ID_NAME"),
		),
		"COUNT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PHOENIX_T_NEWS_LIST_COUNT_NAME"),
		),
	),
);