<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
if(CModule::IncludeModule("iblock")){

    if($g_recaptcha_response = $_POST['g-recaptcha-response']){
        $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Le8ZZ4aAAAAABGjJMS6tsYCxXZwiWZJ39j9KE1Q&response=".$g_recaptcha_response."&remoteip=".$_SERVER["REMOTE_ADDR"]),true);
        if (($response["success"] && $response["score"] <= 0.7)){
            echo "Возникли проблемы попробуйте еще раз.";
            return false;
        }
    }else{
        echo "Возникли проблемы попробуйте еще раз.";
        return false;
    }

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
