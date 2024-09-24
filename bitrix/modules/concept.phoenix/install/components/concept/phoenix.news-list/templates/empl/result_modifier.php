<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?
	global $PHOENIX_TEMPLATE_ARRAY;
	
	if(!isset($arParams['VIEW']) || !strlen(trim($arParams['VIEW'])))
		$arParams['VIEW'] = "flat";



	$arParams["BLOCK_TITLE_FORMATED"] = str_replace("'","\"",strip_tags(htmlspecialcharsBack($arParams["~BLOCK_TITLE"])));


	$arSectionsID = array();

	if($arParams['VIEW'] == "full")
	{
		$arSizePhoto = array(
			'width'=>600, 'height'=>600
		);
		$resize = BX_RESIZE_IMAGE_EXACT;
	}
	else
	{
		

		if($arParams["PICTURE_ROUND"] == "pic-round")
		{
			$arSizePhoto = array(
				'width'=>600, 'height'=>600
			);

			$resize = BX_RESIZE_IMAGE_EXACT;
		}
		else
		{
			$arSizePhoto = array(
				'width'=>500, 'height'=>400
			);
				
			$resize = BX_RESIZE_IMAGE_PROPORTIONAL;
		}
	}
	
	if(!empty($arResult["ITEMS"]))
	{
		foreach ($arResult["ITEMS"] as $key => $arItem)
		{
			$arItem["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH.'/images/empl.jpg';



			if(strlen($arItem["PREVIEW_PICTURE"]))
			{
				$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], $arSizePhoto, $resize, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

				$arItem["PREVIEW_PICTURE_SRC"] = $img["src"];

				$preview_pic_db = CFile::GetById($arItem["PREVIEW_PICTURE"]);
                $preview_pic = $preview_pic_db->getNext();

                $arItem["PREVIEW_PICTURE_DESCRIPTION"] = $preview_pic["DESCRIPTION"];
			}

			$arItem["DETAIL_PICTURE_SRC"] = "";

			if( strlen($arItem["DETAIL_PICTURE"]) )
			{
				$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array('width'=>600, 'height'=>400), $resize, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

				$arItem["DETAIL_PICTURE_SRC"] = $img["src"];

				$detail_pic_db = CFile::GetById($arItem["DETAIL_PICTURE"]);
                $detail_pic = $detail_pic_db->getNext();

                $arItem["DETAIL_PICTURE_DESCRIPTION"] = $detail_pic["DESCRIPTION"];
			}

			if($arItem["IBLOCK_SECTION_ID"])
				$arSectionsID[$arItem["IBLOCK_SECTION_ID"]] = $arItem["IBLOCK_SECTION_ID"];

			$arItem["PHONE_TRIM"] = preg_replace('/[^0-9+]/', '', $arItem['PROPERTIES']['EMPL_PHONE']['VALUE']);

			$arResult["ITEMS"][$key] = $arItem;
			
		}
	}

	if($arParams['VIEW'] == "flat")
	{
		$rest1 = 5 % 3;
		$rest2 = 7 % 3;

		$arResult["COLS_CLASS"] = "col-xl-3 col-md-4 col-sm-6 col-12";

		$count = count($arResult["ITEMS"]);

		$arResult["BTN_SIZE"] = "middle";

		if($count % 4 == 0 && !$arParams["SIDEMENU"])
			$arResult["COLS_CLASS"] = "col-lg-3 col-sm-6 col-12 four-cols";

		elseif($count % 3 == $rest1)
			$arResult["BTN_SIZE"] = "big";

		elseif($count % 3 == $rest2)
			$arResult["BTN_SIZE"] = "big";


		$SectListEmpl = CIBlockSection::GetList(array(), array("ID"=>$arSectionsID, "ACTIVE"=>"Y"), false, array("ID", "NAME"));
		while ($SectListGetEmpl = $SectListEmpl->GetNext())
		{
		    $arResult["SECTIONS"][$SectListGetEmpl["ID"]]=$SectListGetEmpl;
		}
	}
?>