<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();


if (COption::GetOptionString("catalog", "1C_GROUP_PERMISSIONS") == "")
	COption::SetOptionString("catalog", "1C_GROUP_PERMISSIONS", "1", GetMessage('SALE_1C_GROUP_PERMISSIONS'));



$langID = "ru";
$bRus = true;

$defCurrency = "RUB";

WizardServices::IncludeServiceLang("price.php", $lang);

if (CModule::IncludeModule("catalog"))
{
	$dbVat = CCatalogVat::GetListEx(
		array(),
		array('RATE' => 0),
		false,
		false,
		array('ID', 'RATE')
	);

	if(!($dbVat->Fetch()))
	{
		$arF = array("ACTIVE" => "Y", "SORT" => "100", "NAME" => GetMessage("WIZ_VAT_1"), "RATE" => 0);
		CCatalogVat::Add($arF);
	}

	$dbVat = CCatalogVat::GetListEx(
		array(),
		array('RATE' => GetMessage("WIZ_VAT_2_VALUE")),
		false,
		false,
		array('ID', 'RATE')
	);

	if(!($dbVat->Fetch()))
	{
		$arF = array("ACTIVE" => "Y", "SORT" => "200", "NAME" => GetMessage("WIZ_VAT_2"), "RATE" => GetMessage("WIZ_VAT_2_VALUE"));
		CCatalogVat::Add($arF);
	}


	$dbResultList = CCatalogGroup::GetList(array(), array("CODE" => "BASE"));


	if($dbResultList->SelectedRowsCount() <= 0)
	{
		$arFields = Array();
		$arFields["USER_LANG"][$langID] = GetMessage("WIZ_PRICE_NAME");
		$arFields["NAME"] = "BASE";
		$arFields["BASE"] = "Y";
		$wizGroupId = Array(1,2);
		$arFields["USER_GROUP"] = $wizGroupId;
		$arFields["USER_GROUP_BUY"] = $wizGroupId;

		$ID = CCatalogGroup::Add($arFields);

		$dbResultList = CCatalogGroup::GetList(array(), array("CODE" => "BASE"));
	}

	if($arRes = $dbResultList->Fetch())
	{
		$arFields = Array();
		$arFields["USER_LANG"][$langID] = GetMessage("WIZ_PRICE_NAME");
		$arFields["BASE"] = "Y";

		if($wizard->GetVar("installPriceBASE") == "Y")
		{
			$db_res = CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID"=>'1', "BUY"=>"Y"));
			
			if($ar_res = $db_res->Fetch())
				$wizGroupId[] = $ar_res['GROUP_ID'];
			
			$wizGroupId[] = 2;
			$arFields["USER_GROUP"] = $wizGroupId;
			$arFields["USER_GROUP_BUY"] = $wizGroupId;
		}

		CCatalogGroup::Update($arRes["ID"], $arFields);
	}

}

?>