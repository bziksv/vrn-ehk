<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("more-photo");

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>5);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){ 
 $arFields = $ob->GetFields();  
 $arProps = $ob->GetProperties();
 if($arProps["MORE_PHOTO"]["VALUE"]){ 
	 ?>
	 <a href="https://vrn-ehk.ru/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=5&type=catalog&ID=<?=$arFields["ID"]?>&lang=ru"><?=$arFields["NAME"];?></a><br>
	 <?
 }

}

?>

 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>