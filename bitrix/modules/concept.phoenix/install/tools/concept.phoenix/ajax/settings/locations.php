<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');   
header('Content-type: application/json');

$locations = array();


if(\Bitrix\Main\Loader::includeModule('sale') && strlen($_REQUEST["term"])>0)
{
	

	if(trim(SITE_CHARSET) == "windows-1251")
		$_REQUEST["term"] = utf8win1251($_REQUEST["term"]);

	$_REQUEST["term"] = strtolower(trim($_REQUEST["term"]));


	$arIDS = array();

	$res = \Bitrix\Sale\Location\LocationTable::getList(array(
		'limit'=> 10,
	    'filter' => array(
	    	'=NAME.LANGUAGE_ID' => LANGUAGE_ID,
	    	'%NAME_RU'=> $_REQUEST["term"],
	    	'=TYPE.CODE'=> 'CITY'
	    ),
	    'select' => array('*', 'NAME_RU' => 'NAME.NAME', 'TYPE_CODE' => 'TYPE.CODE')
	));

	while($item = $res->fetch())
		$arIDS[] = $item["ID"];


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

			$arItem = array("ID"=>$id,"NAME"=>"");
			$i = 0;
			
			while($item = $res->fetch())
			{
				$arItem["NAME"] .= (($i==0)?"":", ").$item["I_NAME_RU"];
				$i++;
			}

			if(trim(SITE_CHARSET) == "windows-1251")
	    		$arItem["NAME"] = iconv('windows-1251', 'UTF-8', $arItem["NAME"]);

			$locations[] = array("value"=>$arItem["ID"],"label"=>$arItem["NAME"]);
		}
	}

}

echo json_encode($locations);