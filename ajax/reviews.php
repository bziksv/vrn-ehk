<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
if(CModule::IncludeModule("iblock")){

	 if(!$APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_code"]))
	{
		// Неправильное значение
		echo "Переданы неправильные данные.";
	} else {
		// Правильное значение
			$el = new CIBlockElement;

	$name = trim(strip_tags($_POST['name']));
	$sity = trim(strip_tags($_POST['sity']));
	$text = trim(strip_tags($_POST['text']));
	$chek = trim(strip_tags($_POST['CHEK']));

	$PROP = array();
	$PROP[47] = array(
	array('VALUE'=>$sity,'DESCRIPTION' => ''),
	array('VALUE'=>'Добавлен:','DESCRIPTION' => date('d.m.Y'))
	);



	$arLoadProductArray = Array(
	  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
	  "IBLOCK_ID"      => 8,
	  "ACTIVE_FROM"      => date('d.m.Y'),
	  "PROPERTY_VALUES"=> $PROP,
	  "NAME"           => $name,
	  "ACTIVE"         => "N",
	  "PREVIEW_TEXT"   => $text
	  );

	if ($chek != '') {
		$PRODUCT_ID = $el->Add($arLoadProductArray);
	}
	if( $PRODUCT_ID ){

			$arEventFields= array(
					"NAME" => $name,
					"SITY" => $sity,
					"TEXT" => $text
						);


			CEvent::Send("send_reviews", SITE_ID, $arEventFields, "N", 78);

			echo "Спасибо ваш отзыв отправлен, после проверки администратором отзыв будет размещен на сайте.";
		}else{
			echo "Возникли проблемы попробуйте еще раз.";
		}
		
	}

}
