<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $PHOENIX_TEMPLATE_ARRAY;

	

	$section_id = $arResult["IBLOCK_SECTION_ID"];

	$arResult["SECTIONS_ID"] = array();

	while($section_id != 0)
	{
	    $res = CIBlockSection::GetByID($section_id);
	    if($ar_res = $res->GetNext())
	        $arResult["SECTIONS_ID"][] = $ar_res["ID"];

	    $section_id = $ar_res["IBLOCK_SECTION_ID"];
	}

	

	$arNews = array();

	if(!empty($arResult["PROPERTIES"]["NEWS_ELEMENTS_NEWS"]["VALUE"]))
	    $arNews = array_merge($arNews, $arResult["PROPERTIES"]["NEWS_ELEMENTS_NEWS"]["VALUE"]);

	if(!empty($arResult["PROPERTIES"]["NEWS_ELEMENTS_BLOG"]["VALUE"]))
	    $arNews = array_merge($arNews, $arResult["PROPERTIES"]["NEWS_ELEMENTS_BLOG"]["VALUE"]);

	if(!empty($arResult["PROPERTIES"]["NEWS_ELEMENTS_ACTION"]["VALUE"]))
	    $arNews = array_merge($arNews, $arResult["PROPERTIES"]["NEWS_ELEMENTS_ACTION"]["VALUE"]);

	$arResult["NEWS_ID"] = $arNews;


	$host = CPhoenixHost::getHost($_SERVER);
	$url = $host.$_SERVER["REQUEST_URI"];

	$arResult["SHARE_TITLE"] = strip_tags(htmlspecialcharsBack($arResult["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]));
	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_TITLE"])>0)
		$arResult["SHARE_TITLE"] = strip_tags(htmlspecialcharsBack($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_TITLE"]));


	$arResult["SHARE_DESCRIPTION"] = strip_tags(htmlspecialcharsBack($arResult["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"]));
	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_DESCRIPTION"])>0)
	    $arResult["SHARE_DESCRIPTION"] = strip_tags(htmlspecialcharsBack($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_DESCRIPTION"]));

	$arResult["SHARE_DESCRIPTION"] = str_replace(array("\r\n", "\r", "\n", "<br>", "<br/>"), array(" ", " ", " "," "," "), $arResult["SHARE_DESCRIPTION"]);


	$arResult["SHARE_IMG"] = $host.CFile::GetPath($arResult["PREVIEW_PICTURE"]["ID"]);
	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_IMAGE"])>0)
		$arResult["SHARE_IMG"] = $host.CFile::GetPath($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_IMAGE"]);


	$arResult["SHARE_URL"] = $url;
	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_URL"])>0)
		$arResult["SHARE_URL"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OG_URL"];


	$arResult["CATALOG"]["TITLE"] = $arResult["PROPERTIES"]["NEWS_TITLE_CATALOG"]["~VALUE"];
	$arResult["CATALOG"]["ITEMS"] = $arResult["PROPERTIES"]["CATALOG"]["VALUE"];



	if(!empty($arResult["PROPERTIES"]["NEWS_GALLERY"]["VALUE"]))
	{
		$arResult["GALLERY"] = array();

		$arWaterMark = Array();

        if($arResult["PROPERTIES"]["WATERMARK"]["VALUE"] > 0)
        {

            $arWaterMark = Array(
                array(
                    "name" => "watermark",
                    "position" => "center",
                    "type" => "image",
                    "size" => "big",
                    "file" => $_SERVER["DOCUMENT_ROOT"].CFile::GetPath($arResult["PROPERTIES"]["WATERMARK"]["VALUE"]), 
                    "fill" => "exact",
                )
            );
        }

		foreach($arResult["PROPERTIES"]["NEWS_GALLERY"]["VALUE"] as $k => $arImages)
		{
			$img_lg = CFile::ResizeImageGet($arImages, array('width'=>1600, 'height'=>1600), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, $arWaterMark, false, 85);

			$arResult["GALLERY"][$k]["SRC_LG"] = $img_lg['src'];

			$img_xs = CFile::ResizeImageGet($arImages, array('width'=>300, 'height'=>300), BX_RESIZE_IMAGE_EXACT, false, false, false, 85);


			$arResult["GALLERY"][$k]["SRC_XS"] = $img_xs['src'];


			$arResult["GALLERY"][$k]["DESC"] = (strlen($arResult["PROPERTIES"]["NEWS_GALLERY"]["~DESCRIPTION"][$k])>0)? $arResult["PROPERTIES"]["NEWS_GALLERY"]["~DESCRIPTION"][$k]:"";
		}

		unset($img_big, $img_min);
	}

	if(!empty($arResult["PROPERTIES"]["FILES"]["VALUE"]))
	{
		$arResult["FILES"]=array();

		foreach($arResult["PROPERTIES"]["FILES"]["VALUE"] as $k=>$file)
		{
			$arResult["FILES"][$k]["PATH"] = CFile::GetPath($file);
			$arResult["FILES"][$k]["DESC"] = (strlen($arResult["PROPERTIES"]["FILES_DESC"]["VALUE"][$k])>0)? $arResult["PROPERTIES"]["FILES_DESC"]["~VALUE"][$k]:"";
			$arResult["FILES"][$k]["SUB_DESC"] = (strlen($arResult["PROPERTIES"]["FILES_DESC"]["DESCRIPTION"][$k])>0)? $arResult["PROPERTIES"]["FILES_DESC"]["~DESCRIPTION"][$k]:"";
		}
	}




	
	$cp = $this->__component;
	if (is_object($cp))
	    $cp->arResultCacheKeys = array_merge($this->__component->arResultCacheKeys, array("CATALOG", "SECTIONS_ID"));
?>