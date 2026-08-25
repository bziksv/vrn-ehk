<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?if(CModule::IncludeModule("iblock"))
{
	$el = new CIBlockElement;
	$PROP = array();
	foreach($_POST as $key=>$value)
	{
		$PROP[$key]=strip_tags($value);
	}
	$item="-";
	$ar_cur_item=CIBlockElement::GetByID($PROP["ITEM"]);
	if($cur_item=$ar_cur_item->GetNext())
	{
		$item="<a href='http://".$_SERVER["HTTP_HOST"].$cur_item["DETAIL_PAGE_URL"]."'>".$cur_item["NAME"]." [".$cur_item["ID"]."]</a>";
	}
	$arLoadProductArray = Array(
		"MODIFIED_BY"    => $USER->GetID(),
		"IBLOCK_SECTION_ID" => false,
		"IBLOCK_ID"      => 14,
		"NAME"=> "Заказ от ".date("d.m.Y"),
		"PROPERTY_VALUES"=>$PROP,
		"ACTIVE"         => "N",
		"ACTIVE_FROM"=>date("d.m.Y H:i")
	);
	if($PRODUCT_ID = $el->Add($arLoadProductArray))
	{
		echo "<div class='success'><span>Спасибо.<br /> Ваш запрос отправлен. Менеджер свяжется с Вами в ближайшее время</span></div>";
		$arEventFields = array(
			"NAME"=>$PROP["NAME"],
			"EMAIL"=>$PROP["EMAIL"],
			"PHONE"=>$PROP["PHONE"],
			"ITEM"=>$item,
			"DATE"=>date("d.m.Y H:i:s"),
			"URL"=>$_SERVER["HTTP_HOST"]."/bitrix/admin/iblock_element_edit.php?WF=Y&ID=".$PRODUCT_ID."&type=requests&lang=ru&IBLOCK_ID=14&find_section_section=0"
		);
		CEvent::Send("ORDER_ITEM", "s1", $arEventFields,"Y");
		CEvent::CheckEvents();
	}
	else
	{
		echo "error";//.$el->LAST_ERROR;
	}
}?>
