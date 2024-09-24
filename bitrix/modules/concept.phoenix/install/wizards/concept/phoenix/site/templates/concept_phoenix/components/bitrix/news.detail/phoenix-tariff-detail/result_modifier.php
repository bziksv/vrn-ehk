<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
	$arFilter = Array("ID"=> $arResult["ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");

    $res = CIBlockElement::GetList(Array("sort" => "asc"), $arFilter);

    while($ob = $res->GetNextElement())
    {
        $arResult["PROPERTIES"] = $ob->GetProperties();
    }
    
    $res = CIBlockSection::GetByID($arResult["~IBLOCK_SECTION_ID"]);
	if($ar_res = $res->GetNext())
	{
	  $arResult["SECTION_MAIN"]["IBLOCK_ID"] = $ar_res["IBLOCK_ID"];
	  $arResult["SECTION_MAIN"]["ID"] = $ar_res["ID"];
	}


	

	$rsSections = CIBlockSection::GetList($arSort, array("IBLOCK_ID" => $arResult["SECTION_MAIN"]["IBLOCK_ID"], "ID"=>$arResult["SECTION_MAIN"]["ID"]),false, array("ID","IBLOCK_ID","IBLOCK_SECTION_ID","NAME","DESCRIPTION","UF_*"));
    while ($arSection = $rsSections->Fetch())
    {
         $arResult["SECTION_MAIN"]["ITEMS"] = $arSection;
    }
    
    if(strlen($arResult["SECTION_MAIN"]["ITEMS"]["UF_CHAM_BUTTONS_TYPE"]) > 0)
    {
        $arResult["SECTION_MAIN"]["ITEMS"]["UF_CHAM_BUTTONS_TYPE_ENUM"] = CUserFieldEnum::GetList(array(), array(
        "ID" => $arResult["SECTION_MAIN"]["ITEMS"]["UF_CHAM_BUTTONS_TYPE"],
        ))->GetNext();
    }
    else
        $arResult["SECTION_MAIN"]["ITEMS"]["UF_CHAM_BUTTONS_TYPE_ENUM"]["XML_ID"] = "elips";
?>