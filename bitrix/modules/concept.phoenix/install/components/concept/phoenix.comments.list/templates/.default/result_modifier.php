<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $PHOENIX_TEMPLATE_ARRAY, $DB;

$issetDB = !empty($arResult["ITEMS"]);


	
if($issetDB)
{
	$format = "DD.MM.YYYY HH:MI:SS";

	$arUsers = array();

	foreach($arResult["ITEMS"] as $key => $arItem)
	{
		if($arItem["USER_ID"] != 0)
			$arUsers[$arItem["USER_ID"]] = $arItem["USER_ID"];
	}

	
	if(!empty($arUsers))
	{
		$usersPhoto = array();
		$filter = Array("ID" => implode("|",$arUsers));
		$select = Array("FIELDS" => array("ID","PERSONAL_PHOTO"));

		$order = array('sort' => 'asc');
		$sort = 'sort';
		$rsUsers = CUser::GetList($order, $sort, $filter, $select);

		while ($arUser = $rsUsers->Fetch()){

			if($arUser['PERSONAL_PHOTO'])
			{
				$photo = CFile::ResizeImageGet($arUser["PERSONAL_PHOTO"], array('width'=>60, 'height'=>60), BX_RESIZE_IMAGE_EXACT, false, Array(), false, 85);

				$usersPhoto[$arUser['ID']] = $photo['src'];
			}
		}

		unset($rsUsers,$arUser);

		
	}





	foreach($arResult["ITEMS"] as $key => $arItem)
	{
		if(isset($usersPhoto[$arItem['USER_ID']]))
			$arResult["ITEMS"][$key]["PHOTO_SRC"] = $usersPhoto[$arItem['USER_ID']];
		
		else
			$arResult["ITEMS"][$key]["FIRST_LETTER"] = mb_substr(trim($arItem["USER_NAME"]), 0, 1);
		
		

		$arItem["DATE_STR"] = $arItem["DATE"]->toString();

		
		if(strlen($arItem["DATE_STR"])>0)
			$arResult["ITEMS"][$key]["DATE_FORMAT"] = CIBlockFormatProperties::DateFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]["VALUE"], MakeTimeStamp($arItem["DATE_STR"], $format));


		$arResult["ITEMS"][$key]["REVIEW_TEXT_ISSET"] = "";

		if(strlen($arItem["TEXT"])>0 || strlen($arItem["POSITIVE"])>0 || strlen($arItem["NEGATIVE"])>0 || strlen($arResult["ITEMS"][$key]["IMAGES_SRC"])>0)
			$arResult["ITEMS"][$key]["REVIEW_TEXT_ISSET"] = "Y";

		$arResult["ITEMS"][$key]["REVIEW_ADVS_ISSET"] = "";

	}

}