<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule('sale'))
	return;

use	Bitrix\Sale\BusinessValue,
	Bitrix\Sale\OrderStatus,
	Bitrix\Sale\DeliveryStatus,
	Bitrix\Main\Localization\Loc;

\Bitrix\Sale\Delivery\Services\Manager::getHandlersList();
$deliveryItems = array();

include_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/sale/install/index.php';
$saleObject = new sale;
$saleVersion = $saleObject->MODULE_VERSION;
if (version_compare($saleVersion, '15.0.0', '>='))
{
	$BIZVAL_INDIVIDUAL_DOMAIN = BusinessValue::INDIVIDUAL_DOMAIN;
	$BIZVAL_ENTITY_DOMAIN = BusinessValue::ENTITY_DOMAIN;
}
else
{
	$BIZVAL_INDIVIDUAL_DOMAIN = null;
	$BIZVAL_ENTITY_DOMAIN = null;
}


$langID = "ru";
$defCurrency = "RUB";

WizardServices::IncludeServiceLang("settings.php", $lang);

/*person types*/

$personType = $wizard->GetVar("personType");


$arPersonTypeNames = array();
$dbPerson = CSalePersonType::GetList(array(), array("LID" => WIZARD_SITE_ID));

while($arPerson = $dbPerson->Fetch())
	$arPersonTypeNames[$arPerson["ID"]] = $arPerson["NAME"];

$fizExist = in_array(GetMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames);
$urExist = in_array(GetMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames);

$personTypeFiz = (isset($personType["fiz"]) && $personType["fiz"] == "Y" ? "Y" : "N");
$personTypeUr = (isset($personType["ur"]) && $personType["ur"] == "Y" ? "Y" : "N");

if($fizExist)
{
	$arGeneralInfo["personType"]["fiz"] = CSalePersonType::Update(array_search(GetMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames), Array(
			"ACTIVE" => $personTypeFiz,
		)
	);
}
elseif($personTypeFiz == "Y")
{
	 $arGeneralInfo["personType"]["fiz"] = CSalePersonType::Add(Array(
		"LID" => WIZARD_SITE_ID,
		"NAME" => GetMessage("SALE_WIZARD_PERSON_1"),
		"SORT" => "100"
		)
	);
}

if($urExist)
{
	$arGeneralInfo["personType"]["ur"] = CSalePersonType::Update(array_search(GetMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames), Array(
			"ACTIVE" => $personTypeUr,
		)
	);
}
elseif($personTypeUr == "Y")
{
	$arGeneralInfo["personType"]["ur"] = CSalePersonType::Add(Array(
		"LID" => WIZARD_SITE_ID,
		"NAME" => GetMessage("SALE_WIZARD_PERSON_2"),
		"SORT" => "150"
		)
	);
}

//Order Prop Group
if ($fizExist)
{
	$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(Array(), Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_FIZ1")), false, false, array("ID"));
	if ($arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext())
		$arGeneralInfo["propGroup"]["user_fiz"] = $arSaleOrderPropsGroup["ID"];

	$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(Array(),Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_FIZ2")), false, false, array("ID"));
	if ($arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext())
		$arGeneralInfo["propGroup"]["adres_fiz"] = $arSaleOrderPropsGroup["ID"];

}
elseif($personType["fiz"] == "Y" )
{
	$arGeneralInfo["propGroup"]["user_fiz"] = CSaleOrderPropsGroup::Add(Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_FIZ1"), "SORT" => 100));
	$arGeneralInfo["propGroup"]["adres_fiz"] = CSaleOrderPropsGroup::Add(Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_FIZ2"), "SORT" => 200));
}

if ($urExist)
{
	$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(Array(), Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_UR1")), false, false, array("ID"));
	if ($arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext())
		$arGeneralInfo["propGroup"]["user_ur"] = $arSaleOrderPropsGroup["ID"];

	$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(Array(),Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_UR2")), false, false, array("ID"));
	if ($arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext())
		$arGeneralInfo["propGroup"]["adres_ur"] = $arSaleOrderPropsGroup["ID"];
}
elseif($personType["ur"] == "Y")
{
	$arGeneralInfo["propGroup"]["user_ur"] = CSaleOrderPropsGroup::Add(Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_UR1"), "SORT" => 300));
	$arGeneralInfo["propGroup"]["adres_ur"] = CSaleOrderPropsGroup::Add(Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_UR2"), "SORT" => 400));
}

if($personType["fiz"] == "Y")
{
	$businessValuePersonDomain[$arGeneralInfo["personType"]["fiz"]] = $BIZVAL_INDIVIDUAL_DOMAIN;

	$businessValueCodes['CLIENT_NAME'] = array('GROUP' => 'CLIENT', 'SORT' =>  100, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN);
	$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_6"),
			"TYPE" => "TEXT",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 100,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_fiz"],
			"SIZE1" => 40,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "Y",
			"IS_PAYER" => "Y",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "FIO",
			"IS_FILTERED" => "Y",
		);

	$businessValueCodes['CLIENT_EMAIL'] = array('GROUP' => 'CLIENT', 'SORT' =>  110, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN);
	$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
			"NAME" => "E-Mail",
			"TYPE" => "TEXT",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 110,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_fiz"],
			"SIZE1" => 40,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "Y",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "EMAIL",
			"IS_FILTERED" => "Y",
		);

	$businessValueCodes['CLIENT_PHONE'] = array('GROUP' => 'CLIENT', 'SORT' =>  120, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN);
	$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_9"),
			"TYPE" => "TEXT",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 120,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_fiz"],
			"SIZE1" => 0,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "PHONE",
			"IS_PHONE" => "Y",
			"IS_FILTERED" => "N",
		);

	/*$businessValueCodes['CLIENT_ZIP'] = array('GROUP' => 'CLIENT', 'SORT' =>  130, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
		"NAME" => GetMessage("SALE_WIZARD_PROP_4"),
		"TYPE" => "TEXT",
		"REQUIED" => "N",
		"DEFAULT_VALUE" => "101000",
		"SORT" => 130,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_fiz"],
		"SIZE1" => 8,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "ZIP",
		"IS_FILTERED" => "N",
		"IS_ZIP" => "Y",
	);*/

	$businessValueCodes['CLIENT_CITY'] = array('GROUP' => 'CLIENT', 'SORT' =>  145, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
		"NAME" => GetMessage("SALE_WIZARD_PROP_21"),
		"TYPE" => "TEXT",
		"REQUIED" => "N",
		"DEFAULT_VALUE" => $shopLocation,
		"SORT" => 145,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_fiz"],
		"SIZE1" => 40,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "CITY",
		"IS_FILTERED" => "Y",
	);

	/*$businessValueCodes['CLIENT_LOCATION'] = array('GROUP' => 'CLIENT', 'SORT' =>  140, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
		"NAME" => GetMessage("SALE_WIZARD_PROP_2"),
		"TYPE" => "LOCATION",
		"REQUIED" => "Y",
		"DEFAULT_VALUE" => $location,
		"SORT" => 140,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "Y",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_fiz"],
		"SIZE1" => 40,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "LOCATION",
		"IS_FILTERED" => "N",
		"INPUT_FIELD_LOCATION" => ""
	);*/

	$businessValueCodes['CLIENT_ADDRESS'] = array('GROUP' => 'CLIENT', 'SORT' =>  150, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN);
	$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_5"),
			"TYPE" => "TEXTAREA",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 150,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_fiz"],
			"SIZE1" => 30,
			"SIZE2" => 3,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "ADDRESS",
			"IS_FILTERED" => "N",
		);
}

if($personType["ur"] == "Y")
{
	$businessValuePersonDomain[$arGeneralInfo["personType"]["ur"]] = $BIZVAL_ENTITY_DOMAIN;

	$businessValueCodes['COMPANY_EMAIL'] = array('GROUP' => 'COMPANY', 'SORT' =>  110, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
		"NAME" => "E-Mail",
		"TYPE" => "TEXT",
		"REQUIED" => "Y",
		"DEFAULT_VALUE" => "",
		"SORT" => 110,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
		"SIZE1" => 40,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "Y",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "EMAIL",
		"IS_FILTERED" => "Y",
	);

	$businessValueCodes['COMPANY_NAME'] = array('GROUP' => 'COMPANY', 'SORT' =>  130, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
		"NAME" => GetMessage("SALE_WIZARD_PROP_40"),
		"TYPE" => "TEXT",
		"REQUIED" => "Y",
		"DEFAULT_VALUE" => "",
		"SORT" => 130,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ur"],
		"SIZE1" => 40,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "Y",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "COMPANY_NAME",
		"IS_FILTERED" => "Y",
	);

	$businessValueCodes['COMPANY_ADDRESS'] = array('GROUP' => 'COMPANY', 'SORT' =>  140, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
		"NAME" => GetMessage("SALE_WIZARD_PROP_47"),
		"TYPE" => "TEXTAREA",
		"REQUIED" => "Y",
		"DEFAULT_VALUE" => "",
		"SORT" => 140,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
		"SIZE1" => 40,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "COMPANY_ADR",
		"IS_FILTERED" => "N",
	);

	$businessValueCodes['COMPANY_EGRPU'] = array('GROUP' => 'COMPANY', 'SORT' =>  150, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
		"NAME" => GetMessage("SALE_WIZARD_PROP_48"),
		"TYPE" => "TEXT",
		"REQUIED" => "Y",
		"DEFAULT_VALUE" => "",
		"SORT" => 150,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
		"SIZE1" => 30,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "EGRPU",
		"IS_FILTERED" => "N",
	);

	$businessValueCodes['COMPANY_INN'] = array('GROUP' => 'COMPANY', 'SORT' =>  160, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
		"NAME" => GetMessage("SALE_WIZARD_PROP_49"),
		"TYPE" => "TEXT",
		"REQUIED" => "N",
		"DEFAULT_VALUE" => "",
		"SORT" => 160,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
		"SIZE1" => 30,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "INN",
		"IS_FILTERED" => "N",
	);

	$businessValueCodes['COMPANY_NDS'] = array('GROUP' => 'COMPANY', 'SORT' =>  170, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
		"NAME" => GetMessage("SALE_WIZARD_PROP_46"),
		"TYPE" => "TEXT",
		"REQUIED" => "N",
		"DEFAULT_VALUE" => "",
		"SORT" => 170,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
		"SIZE1" => 30,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "NDS",
		"IS_FILTERED" => "N",
	);

	/*$businessValueCodes['COMPANY_ZIP'] = array('GROUP' => 'COMPANY', 'SORT' =>  180, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
		"NAME" => GetMessage("SALE_WIZARD_PROP_44"),
		"TYPE" => "TEXT",
		"REQUIED" => "N",
		"DEFAULT_VALUE" => "",
		"SORT" => 180,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
		"SIZE1" => 8,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "ZIP",
		"IS_FILTERED" => "N",
		"IS_ZIP" => "Y",
	);*/

	$businessValueCodes['COMPANY_CITY'] = array('GROUP' => 'COMPANY', 'SORT' =>  190, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
		"NAME" => GetMessage("SALE_WIZARD_PROP_43"),
		"TYPE" => "TEXT",
		"REQUIED" => "Y",
		"DEFAULT_VALUE" => $shopLocation,
		"SORT" => 190,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
		"SIZE1" => 30,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "CITY",
		"IS_FILTERED" => "Y",
	);

	$businessValueCodes['COMPANY_OPERATION_ADDRESS'] = array('GROUP' => 'COMPANY', 'SORT' =>  200, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
		"NAME" => GetMessage("SALE_WIZARD_PROP_42"),
		"TYPE" => "TEXTAREA",
		"REQUIED" => "Y",
		"DEFAULT_VALUE" => "",
		"SORT" => 200,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
		"SIZE1" => 30,
		"SIZE2" => 3,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "ADDRESS",
		"IS_FILTERED" => "N",
	);

	$businessValueCodes['COMPANY_PHONE'] = array('GROUP' => 'COMPANY', 'SORT' =>  210, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
		"NAME" => GetMessage("SALE_WIZARD_PROP_45"),
		"TYPE" => "TEXT",
		"REQUIED" => "Y",
		"DEFAULT_VALUE" => "",
		"SORT" => 210,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
		"SIZE1" => 30,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "PHONE",
		"IS_FILTERED" => "N",
	);
	
}

$propCityId = 0;
reset($businessValueCodes);

foreach($arProps as $prop)
{
	$variants = Array();
	if(!empty($prop["VARIANTS"]))
	{
		$variants = $prop["VARIANTS"];
		unset($prop["VARIANTS"]);
	}

	if ($prop["CODE"] == "LOCATION" && $propCityId > 0)
	{
		$prop["INPUT_FIELD_LOCATION"] = $propCityId;
		$propCityId = 0;
	}

	$dbSaleOrderProps = CSaleOrderProps::GetList(array(), array("PERSON_TYPE_ID" => $prop["PERSON_TYPE_ID"], "CODE" =>  $prop["CODE"]));
	if ($arSaleOrderProps = $dbSaleOrderProps->GetNext())
		$id = $arSaleOrderProps["ID"];
	else
		$id = CSaleOrderProps::Add($prop);

	if ($prop["CODE"] == "CITY")
	{
		$propCityId = $id;
	}
	if(strlen($prop["CODE"]) > 0)
	{
		//$arGeneralInfo["propCode"][$prop["CODE"]] = $prop["CODE"];
		$arGeneralInfo["propCodeID"][$prop["CODE"]] = $id;
		$arGeneralInfo["properies"][$prop["PERSON_TYPE_ID"]][$prop["CODE"]] = $prop;
		$arGeneralInfo["properies"][$prop["PERSON_TYPE_ID"]][$prop["CODE"]]["ID"] = $id;
	}

	if(!empty($variants))
	{
		foreach($variants as $val)
		{
			$val["ORDER_PROPS_ID"] = $id;
			CSaleOrderPropsVariant::Add($val);
		}
	}

	// add business value mapping to property
	$businessValueCodes[key($businessValueCodes)]['MAP'] = array($prop['PERSON_TYPE_ID'] => array('PROPERTY', $id));
	next($businessValueCodes);
}


/*paysystems*/

$paysystem = $wizard->GetVar("paysystem");


$arPaySystems = Array();

if($paysystem["cash"] == "Y")
{
	$arPaySystemsTmp = array(
		'PAYSYSTEM' => array(
			"NAME" => GetMessage("SALE_WIZARD_PS_CASH"),
			"PSA_NAME" => GetMessage("SALE_WIZARD_PS_CASH"),
			"SORT" => 80,
			"ACTIVE" => "Y",
			"DESCRIPTION" => GetMessage("SALE_WIZARD_PS_CASH_DESCR"),
			"ACTION_FILE" => "cash",
			"RESULT_FILE" => "",
			"NEW_WINDOW" => "N",
			"PARAMS" => "",
			"HAVE_PAYMENT" => "Y",
			"HAVE_ACTION" => "N",
			"HAVE_RESULT" => "N",
			"HAVE_PREPAY" => "N",
			"HAVE_RESULT_RECEIVE" => "N",
			"ENTITY_REGISTRY_TYPE" => "ORDER"
		)
	);

	if($personType["fiz"] == "Y")
		$arPaySystemsTmp["PERSON_TYPE"] = array($arGeneralInfo["personType"]["fiz"]);

	$arPaySystems[] = $arPaySystemsTmp;
}


foreach($arPaySystems as $arPaySystem)
{
	$updateFields = array();

	$val = $arPaySystem['PAYSYSTEM']; 

	if (array_key_exists('LOGOTIP', $val) && is_array($val['LOGOTIP']))
	{
		$updateFields['LOGOTIP'] = $val['LOGOTIP'];
		unset($val['LOGOTIP']);
	}

	$dbRes = \Bitrix\Sale\PaySystem\Manager::getList(array('select' => array("ID", "NAME"), 'filter' => array("NAME" => $val["NAME"])));
	$tmpPaySystem = $dbRes->Fetch(); 
	if(empty($tmpPaySystem))
	{	
		$resultAdd = \Bitrix\Sale\Internals\PaySystemActionTable::add($val); 

		if($resultAdd->isSuccess())
		{
			$id = $resultAdd->getId(); 

			if (array_key_exists('BIZVAL', $arPaySystem) && $arPaySystem['BIZVAL'])
			{
				$arGeneralInfo["paySystem"][$arPaySystem["ACTION_FILE"]] = $id;
				foreach ($arPaySystem['BIZVAL'] as $personType => $codes)
				{
					foreach ($codes as $code => $map)
						\Bitrix\Sale\BusinessValue::setMapping($code, 'PAYSYSTEM_'.$id, $personType, array('PROVIDER_KEY' => $map['TYPE'], 'PROVIDER_VALUE' => $map['VALUE']), true);
				}
			}

			if ($arPaySystem['PERSON_TYPE'])
			{
				$params = array(
					'filter' => array(
						"SERVICE_ID" => $id,
						"SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
						"=CLASS_NAME" => '\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType'
					)
				);

				$dbRes = \Bitrix\Sale\Internals\ServiceRestrictionTable::getList($params);
				if (!$dbRes->fetch())
				{
					$fields = array(
						"SERVICE_ID" => $id,
						"SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
						"SORT" => 100,
						"PARAMS" => array(
							'PERSON_TYPE_ID' => $arPaySystem['PERSON_TYPE']
						)
					);
					\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType::save($fields);
				}
			}

			$updateFields['PARAMS'] = serialize(array('BX_PAY_SYSTEM_ID' => $id));
			$updateFields['PAY_SYSTEM_ID'] = $id;

			$image = '/bitrix/modules/sale/install/images/sale_payments/'.$val['ACTION_FILE'].'.png';
			if ((!array_key_exists('LOGOTIP', $updateFields) || !is_array($updateFields['LOGOTIP'])) &&
				\Bitrix\Main\IO\File::isFileExists($_SERVER['DOCUMENT_ROOT'].$image)
			)
			{
				$updateFields['LOGOTIP'] = CFile::MakeFileArray($image);
				$updateFields['LOGOTIP']['MODULE_ID'] = "sale";
			}

			CFile::SaveForDB($updateFields, 'LOGOTIP', 'sale/paysystem/logotip');
			\Bitrix\Sale\Internals\PaySystemActionTable::update($id, $updateFields);
		}
	}
	else
	{
		$flag = false;

		$params = array(
			'filter' => array(
				"SERVICE_ID" => $tmpPaySystem['ID'],
				"SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
				"=CLASS_NAME" => '\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType'
			)
		);

		$dbRes = \Bitrix\Sale\Internals\ServiceRestrictionTable::getList($params);
		$restriction = $dbRes->fetch();

		if ($restriction)
		{
			foreach ($restriction['PARAMS']['PERSON_TYPE_ID'] as $personTypeId)
			{
				if (array_search($personTypeId, $arPaySystem['PERSON_TYPE']) === false)
				{
					$arPaySystem['PERSON_TYPE'][] = $personTypeId;
					$flag = true;
				}
			}

			$restrictionId = $restriction['ID'];
		}

		if ($flag)
		{
			$fields = array(
				"SERVICE_ID" => $restrictionId,
				"SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
				"SORT" => 100,
				"PARAMS" => array(
					'PERSON_TYPE_ID' => $arPaySystem['PERSON_TYPE']
				)
			);

			\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType::save($fields, $restrictionId);
		}
	}
}

/*delivery*/

$delivery = $wizard->GetVar("delivery");

$dbRes = \Bitrix\Sale\Internals\ServiceRestrictionTable::getList(array(
		'filter' => array(
			'=CLASS_NAME' => '\Bitrix\Sale\Delivery\Restrictions\BySite',
			'=SERVICE_TYPE' => \Bitrix\Sale\Delivery\Restrictions\Manager::SERVICE_TYPE_SHIPMENT
		)
	));

	$dlvBySiteExist = false;

	while($rstr = $dbRes->fetch())
	{
		$lids = $rstr["PARAMS"]["SITE_ID"];

		if(is_array($lids))
			$dlvBySiteExist = in_array(WIZARD_SITE_ID, $lids);
		else
			$dlvBySiteExist = (WIZARD_SITE_ID == $lids);

		if($dlvBySiteExist)
			break;
	}

if(!$dlvBySiteExist)
{
	$deliveryItems[] = array(
		"NAME" => GetMessage("SALE_WIZARD_COUR"),
		"DESCRIPTION" => GetMessage("SALE_WIZARD_COUR_DESCR"),
		"CLASS_NAME" => '\Bitrix\Sale\Delivery\Services\Configurable',
		"CURRENCY" => $defCurrency,
		"SORT" => 100,
		"ACTIVE" => $delivery["courier"] == "Y" ? "Y" : "N",
		"LOGOTIP" => "/bitrix/modules/sale/ru/delivery/courier_logo.png",
		"CONFIG" => array(
			"MAIN" => array(
				"PRICE" => ($bRus ? "500" : "30"),
				"CURRENCY" => $defCurrency,
				"PERIOD" => array(
					"FROM" => 0,
					"TO" => 0,
					"TYPE" => "D"
				)
			)
		)
	);

	$deliveryItems[] = array(
		"NAME" => GetMessage("SALE_WIZARD_COUR1"),
		"DESCRIPTION" => GetMessage("SALE_WIZARD_COUR1_DESCR"),
		"CLASS_NAME" => '\Bitrix\Sale\Delivery\Services\Configurable',
		"CURRENCY" => $defCurrency,
		"SORT" => 200,
		"ACTIVE" => $delivery["self"] == "Y" ? "Y" : "N",
		"LOGOTIP" => "/bitrix/modules/sale/ru/delivery/self_logo.png",
		"CONFIG" => array(
			"MAIN" => array(
				"PRICE" => 0,
				"CURRENCY" => $defCurrency,
				"PERIOD" => array(
					"FROM" => 0,
					"TO" => 0,
					"TYPE" => "D"
				)
			)
		)
	);
}

foreach($deliveryItems as $code => $fields)
{
	//If service already exist just set activity
	if($fields['CLASS_NAME'] == '\Bitrix\Sale\Delivery\Services\Automatic' && !empty($existAutoDlv[$code]) && $fields["ACTIVE"] == "Y")
	{
		\Bitrix\Sale\Delivery\Services\Manager::update(
			$existAutoDlv[$code]["ID"],
			array("ACTIVE" => "Y")
		);
	}
	//not exist
	else
	{
		if(!empty($fields["LOGOTIP"]))
		{
			if (file_exists($_SERVER["DOCUMENT_ROOT"].$fields["LOGOTIP"]))
			{
				$fields["LOGOTIP"] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$fields["LOGOTIP"]);
				$fields["LOGOTIP"]["MODULE_ID"] = "sale";
				CFile::SaveForDB($fields, "LOGOTIP", "sale/delivery/logotip");
			}
		}

		if($service = \Bitrix\Sale\Delivery\Services\Manager::createObject($fields))
			$fields = $service->prepareFieldsForSaving($fields);

		$res = \Bitrix\Sale\Delivery\Services\Manager::add($fields);

		if($res->isSuccess())
		{
			if(!$fields["CLASS_NAME"]::isInstalled())
				$fields["CLASS_NAME"]::install();

			if($fields["CLASS_NAME"] == '\Bitrix\Sale\Delivery\Services\Configurable')
			{
				$newId = $res->getId();

				$res = \Bitrix\Sale\Internals\ServiceRestrictionTable::add(array(
					"SERVICE_ID" => $newId,
					"SERVICE_TYPE" => \Bitrix\Sale\Services\Base\RestrictionManager::SERVICE_TYPE_SHIPMENT,
					"CLASS_NAME" => '\Bitrix\Sale\Delivery\Restrictions\BySite',
					"PARAMS" => array(
						"SITE_ID" => array(WIZARD_SITE_ID),
					)
				));

				\Bitrix\Sale\Location\Admin\LocationHelper::resetLocationsForEntity(
					$newId,
					$arLocation4Delivery,
					\Bitrix\Sale\Delivery\Services\Manager::getLocationConnectorEntityName(),
					false // is locations codes?
				);

				$res = \Bitrix\Sale\Internals\ServiceRestrictionTable::add(array(
					"SERVICE_ID" => $newId,
					"SERVICE_TYPE" => \Bitrix\Sale\Services\Base\RestrictionManager::SERVICE_TYPE_SHIPMENT,
					"CLASS_NAME" => '\Bitrix\Sale\Delivery\Restrictions\ByLocation'
				));

				//Link delivery "pickup" to store
				if($fields["NAME"] == GetMessage("SALE_WIZARD_COUR1"))
				{
					\Bitrix\Main\Loader::includeModule('catalog');
					$dbStores = CCatalogStore::GetList(array(), array("ACTIVE" => 'Y'), false, false, array("ID"));

					if($store = $dbStores->Fetch())
					{
						\Bitrix\Sale\Delivery\ExtraServices\Manager::saveStores(
							$newId,
							array($store['ID'])
						);
					}
				}
			}
		}
	}
}

?>