<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();


if(!empty($arResult["ITEMS"]))
{

    $arResult["COLS_ISSET"] = isset($arParams["COLS"]) && strlen($arParams["COLS"]);

    foreach($arResult["ITEMS"] as $key => $arItem)
    {
    	$arResult["ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = "";

        if(isset($arItem["DETAIL_PICTURE"]["ID"]) && $arItem["DETAIL_PICTURE"]["ID"])
        {
            $img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"]["ID"], array('width'=>600, 'height'=>600), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

            $arResult["ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = $img["src"];
        }

    	else if(isset($arItem["PREVIEW_PICTURE"]["ID"]) && $arItem["PREVIEW_PICTURE"]["ID"])
    	{
    		$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array('width'=>600, 'height'=>600), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

    		$arResult["ITEMS"][$key]["PREVIEW_PICTURE_SRC"] = $img["src"];
    	}

        $arClass=array(
            "XML_ID"=> $arItem["PROPERTIES"]["BANNER_BTN_TYPE"]["VALUE_XML_ID"],
            "FORM_ID"=> $arItem["PROPERTIES"]["BANNER_BTN_FORM"]["VALUE"],
            "MODAL_ID"=> $arItem["PROPERTIES"]["BANNER_BTN_MODAL"]["VALUE"],
            "QUIZ_ID"=> $arItem["PROPERTIES"]["BANNER_BTN_QUIZ"]["VALUE"],
        );

        $arResult["ITEMS"][$key]["CLASS"] = $arClass;


        $arAttr=array(
            "XML_ID"=> $arItem["PROPERTIES"]["BANNER_BTN_TYPE"]["VALUE_XML_ID"],
            "FORM_ID"=> $arItem["PROPERTIES"]["BANNER_BTN_FORM"]["VALUE"],
            "MODAL_ID"=> $arItem["PROPERTIES"]["BANNER_BTN_MODAL"]["VALUE"],
            "LINK"=> $arItem["PROPERTIES"]["BANNER_LINK"]["VALUE"],
            "BLANK"=> $arItem["PROPERTIES"]["BANNER_BTN_BLANK"]["VALUE_XML_ID"],
            "HEADER"=> $arItem['NAME'],
            "QUIZ_ID"=> $arItem["PROPERTIES"]["BANNER_BTN_QUIZ"]["VALUE"],
            "LAND_ID"=> $arItem["PROPERTIES"]["BANNER_BTN_LAND"]["VALUE"]
        );

        $arResult["ITEMS"][$key]["ATTRS"] = $arAttr;

    }
}