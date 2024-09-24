<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $PHOENIX_TEMPLATE_ARRAY, $DB;

$issetDBReviews = !empty($arResult["ITEMS"]);



	
if($issetDBReviews)
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
			$arResult["ITEMS"][$key]["FIRST_LETTER"] = mb_substr($arItem["USER_NAME"], 0, 1);
		

		$arItem["DATE_STR"] = $arItem["DATE"]->toString();

		
		if(strlen($arItem["DATE_STR"])>0)
			$arResult["ITEMS"][$key]["DATE_FORMAT"] = CIBlockFormatProperties::DateFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]["VALUE"], MakeTimeStamp($arItem["DATE_STR"], $format));


		$arResult["ITEMS"][$key]["RECOMMEND_HTML"] = '';

		if($arItem["RECOMMEND"] == "Y")
			$arResult["ITEMS"][$key]["RECOMMEND_HTML"] = '<div class="board-rec rec">'.$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["RECOMMEND"].'</div>';
		
		else if($arItem["RECOMMEND"] == "N")
			$arResult["ITEMS"][$key]["RECOMMEND_HTML"] = '<div class="board-rec no-rec">'.$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["NORECOMMEND"].'</div>';


		if(intval($arItem["LIKE_COUNT"])<=0)
			$arResult["ITEMS"][$key]["LIKE_COUNT"] = $arResult["ITEMS"][$key]["LIKE_COUNT_FORMATED"] = 0;

		else
			$arResult["ITEMS"][$key]["LIKE_COUNT_FORMATED"] = "+".$arItem["LIKE_COUNT"];



		if(intval($arItem["VOTE"])<=0)
			$arResult["ITEMS"][$key]["VOTE"] = 0;

		else if(intval($arItem["VOTE"])>=5)
			$arResult["ITEMS"][$key]["VOTE"] = 5;



		$arResult["ITEMS"][$key]["EXP_DESC"] = "";

		if($arItem["EXPERIENCE"] == "D")
			$arResult["ITEMS"][$key]["EXP_DESC"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_2_VAL_1"];

		else if($arItem["EXPERIENCE"] == "M")
			$arResult["ITEMS"][$key]["EXP_DESC"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_2_VAL_2"];

		else if($arItem["EXPERIENCE"] == "Y")
			$arResult["ITEMS"][$key]["EXP_DESC"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_2_VAL_3"];



		if($arItem["IMAGES_ID"] !== null)
	    {
	    	
	    	$arImagesID = unserialize($arItem["IMAGES_ID"]);
	    
	    	if(!empty($arImagesID))
	    	{
	    		$arResult["ITEMS"][$key]["IMAGES_SRC"] = "";
	    		
	    		foreach ($arImagesID as $keyImg => $value)
	    		{
	    			$arImg = $arImgBig = array();
	    			$arImg = CFile::ResizeImageGet($value, array('width'=>64, 'height'=>64), BX_RESIZE_IMAGE_EXACT, false, Array(), false, 85);
	    			$arImgBig = CFile::ResizeImageGet($value, array('width'=>1200, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, 85);
	    			$arResult["ITEMS"][$key]["IMAGES_SRC"] .= "<a data-gallery=\"review-gallery-".$arItem["ID"]."-".$arItem['USER_ID']."\" href=\"".$arImgBig['src']."\" target=\"_blank\"><img src=\"".$arImg['src']."\"></a>";
	    		}


	    	}
	    	unset($arImagesID);
	    }

	   

		$arResult["ITEMS"][$key]["REVIEW_TEXT_ISSET"] = "";

		if(strlen($arItem["TEXT"])>0 || strlen($arItem["POSITIVE"])>0 || strlen($arItem["NEGATIVE"])>0 || strlen($arResult["ITEMS"][$key]["IMAGES_SRC"])>0)
			$arResult["ITEMS"][$key]["REVIEW_TEXT_ISSET"] = "Y";

		$arResult["ITEMS"][$key]["REVIEW_ADVS_ISSET"] = "";

	}

}