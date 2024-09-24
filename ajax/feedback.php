<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?if(CModule::IncludeModule("iblock"))
{

	if($g_recaptcha_response = $_POST['g-recaptcha-response']){
		$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Le8ZZ4aAAAAABGjJMS6tsYCxXZwiWZJ39j9KE1Q&response=".$g_recaptcha_response."&remoteip=".$_SERVER["REMOTE_ADDR"]),true);
		if (($response["success"] && $response["score"] <= 0.7)){
			echo "error";
			return false;
		}
	}else{
		echo "error";
		return false;
	}

	$el = new CIBlockElement;
	$PROP = array();
	foreach($_POST as $key=>$value)
	{
		$PROP[$key]=strip_tags($value);
	}
	$arLoadProductArray = Array(
		"MODIFIED_BY"    => $USER->GetID(),
		"IBLOCK_SECTION_ID" => false,
		"IBLOCK_ID"      => 13,
		"NAME"=> "Вопрос от ".date("d.m.Y"),
		"PROPERTY_VALUES"=>$PROP,
		"PREVIEW_TEXT"=>$PROP["MESSAGE"],
		"ACTIVE"         => "N",
		"ACTIVE_FROM"=>date("d.m.Y H:i")
	);
	if($ELEMENT_ID = $el->Add($arLoadProductArray))
	{
		echo "<div class='success'><span>Спасибо. Ваше сообщение принято.</span></div>";
		$arEventFields = array(
			"NAME"=>$PROP["NAME"],
			"EMAIL"=>$PROP["EMAIL"],
			"SUBJECT"=>$PROP["SUBJECT"],
			"MESSAGE"=>$PROP["MESSAGE"],
			"DATE"=>date("d.m.Y H:i:s"),
			"URL"=>$_SERVER["HTTP_HOST"].'/bitrix/admin/iblock_element_edit.php?WF=Y&ID='.$ELEMENT_ID.'&type=requests&lang=ru&IBLOCK_ID=13&find_section_section=0'
		);
		CEvent::Send("FEEDBACK", "s1", $arEventFields,"Y");
		CEvent::CheckEvents();
	}
	else
	{
		echo "error";//.$el->LAST_ERROR;
	}
}?>
