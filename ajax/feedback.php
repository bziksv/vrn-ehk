<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/include/env.php';?>
<?if(CModule::IncludeModule("iblock"))
{
	if (!vrnEhkVerifyRecaptcha(isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : ''))
	{
		echo 'error_recaptcha';
		return false;
	}

	if(!$APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_code"]))
	{
		echo 'error_captcha';
	}
	else
	{
		$el = new CIBlockElement;

		$PROP = array();

		foreach($_POST as $key=>$value)
		{
			$PROP[$key] = strip_tags($value);
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
			echo 'error';
		}
	}
}?>
