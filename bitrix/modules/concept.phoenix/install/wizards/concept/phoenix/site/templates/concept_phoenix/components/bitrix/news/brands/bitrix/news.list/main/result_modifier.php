<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
global $PHOENIX_TEMPLATE_ARRAY;

if(!empty($arResult["ITEMS"]))
{
	foreach($arResult["ITEMS"] as $key => $arItem)
	{

		if(isset($arItem["PREVIEW_PICTURE"]["ID"]) && $arItem["PREVIEW_PICTURE"]["ID"])
		{
			$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array('width'=>216, 'height'=>60), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

			$arResult["ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = $img["src"];
		}
		else
			$arResult["ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH.'/images/ufo.jpg';
	}
}