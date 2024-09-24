<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;
	
	
	
$arComponentParameters = array(

   "PARAMETERS" => array(
   
      "ELEMENT_ID" => array(
	     "PARENT" => "BASE",
         "NAME" => GetMessage("ELEMENT_ID"),
         "TYPE" => "STRING",
         "REFRESH" => "Y",
         "COLS" => "15"
      ),
      "NEXT_NAME" => array(
	     "PARENT" => "BASE",
         "NAME" => GetMessage("NEXT_NAME"),
         "TYPE" => "STRING",
         "REFRESH" => "Y",
         "COLS" => "15"
      ),
      "LAST_NAME" => array(
	     "PARENT" => "BASE",
         "NAME" => GetMessage("LAST_NAME"),
         "TYPE" => "STRING",
         "REFRESH" => "Y",
         "COLS" => "15"
      ),
      "CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
      "CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BND_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
	              ),
      )
);

?>