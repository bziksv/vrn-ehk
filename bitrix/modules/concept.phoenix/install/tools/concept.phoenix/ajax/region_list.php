<?
$site_id = trim($_REQUEST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();



require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

CModule::IncludeModule("concept.phoenix");
CModule::IncludeModule("sale");

global $PHOENIX_TEMPLATE_ARRAY;
CPhoenix::phoenixOptionsValues($site_id, array(
	"design",
    "region"
));

CPhoenix::getIblockIDs(array(
    "SITE_ID"=>$site_id, 
    "CODES" => array(
        "concept_phoenix_regions_".$site_id,
        )
    )
);



if(trim(SITE_CHARSET) == "windows-1251")
	$_REQUEST["term"] = utf8win1251($_REQUEST["term"]);

$_REQUEST["term"] = strtolower(trim($_REQUEST["term"]));

$arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['REGION']["IBLOCK_ID"], "ACTIVE"=>"Y", "%NAME"=> $_REQUEST["term"]);


$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, array("nTopCount" => "10"));

$arItems = array();

$arNames = array();

if($res->SelectedRowsCount() > 0)
{
	while($ob = $res->GetNextElement())
	{
		$fields = $ob->GetFields();
		$fields["PROPERTIES"] = $ob->GetProperties();

		$arNames[]=$fields["NAME"];

		if(strlen($fields["PROPERTIES"]["NAME_DESCRIPTION"]["VALUE"])>0)
		{
			$fields["NAME"] = $fields["NAME"].", ".$fields["PROPERTIES"]["NAME_DESCRIPTION"]["VALUE"];
			$fields["~NAME"] = $fields["~NAME"].", ".$fields["PROPERTIES"]["NAME_DESCRIPTION"]["~VALUE"];
		}

		$arItems[]=$fields;
		
	}
}



$arIDS = array();

$res = \Bitrix\Sale\Location\LocationTable::getList(array(
	'limit'=> 10,
    'filter' => array(
    	'=NAME.LANGUAGE_ID' => LANGUAGE_ID,
    	'%NAME_RU'=> $_REQUEST["term"],
    	array(
	        "LOGIC" => "OR",
	        array('=TYPE.CODE' => 'CITY'),
	        array('=TYPE.CODE' => 'VILLAGE')
	    )
    ),
    'select' => array('*', 'NAME_RU' => 'NAME.NAME', 'TYPE_CODE' => 'TYPE.CODE')
));

while($item = $res->fetch())
{
	if(!in_array($item["NAME_RU"], $arNames))
		$arIDS[] = $item["ID"];
}

if(!empty($arIDS))
{

	foreach ($arIDS as $key => $id) {

		$res = \Bitrix\Sale\Location\LocationTable::getList(array(
		    'filter' => array(
		        '=ID' => $id,
		        '=PARENTS.NAME.LANGUAGE_ID' => LANGUAGE_ID,
		        '=PARENTS.TYPE.NAME.LANGUAGE_ID' => LANGUAGE_ID,
		    ),
		    'select' => array(
		        'I_ID' => 'PARENTS.ID',
		        'I_NAME_RU' => 'PARENTS.NAME.NAME',
		        'I_TYPE_CODE' => 'PARENTS.TYPE.CODE',
		        'I_TYPE_NAME_RU' => 'PARENTS.TYPE.NAME.NAME'
		    ),
		    'order' => array(
		        'PARENTS.DEPTH_LEVEL' => 'desc'
		    )
		));

		$arItem = array("ID"=>$id,"NAME_RU"=>"");
		$i = 0;
		
		while($item = $res->fetch())
		{
			$arItem["NAME_RU"] .= (($i==0)?"":", ").$item["I_NAME_RU"];

			if($i==0)
				$arItem["NAME_VALUE"] = $arItem["NAME_RU"];

			$i++;
		}

		$arItems[]=$arItem;
	}
}


$arRegions = CPhoenixRegionality::getRegionList($arItems);

if(!empty($arRegions))
{
	$options = array();

	if(!empty($arRegions["OTHER"]))
	{
		foreach ($arRegions["OTHER"] as $region){

			if(trim(SITE_CHARSET) == "windows-1251")
			{
				$region["SEARCH_VALUES"]["label"] = iconv('windows-1251', 'UTF-8', $region["SEARCH_VALUES"]["label"]);
				$region["SEARCH_VALUES"]["value"] = iconv('windows-1251', 'UTF-8', $region["SEARCH_VALUES"]["value"]);
			}

			$options[] = $region["SEARCH_VALUES"];
		}
	}
}

echo json_encode($options);