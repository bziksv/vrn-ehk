<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$go = true;
$arResult["OK"] = "N";



CModule::IncludeModule("concept.phoenix");

if(strlen($_POST["name"])>0 || strlen($_POST["phone"])>0 || strlen($_POST["email"])>0 || $_POST["send"] != "Y")
    $go = false;


global $PHOENIX_TEMPLATE_ARRAY;

CPhoenix::phoenixOptionsValues($site_id, array(
    	"start",
		"lids",
		"other"
	));

if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA"]["VALUE"]["ACTIVE"]=="Y")
{
	
    if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SECRET_KEY"]["VALUE"])>0 && strlen($_POST['captchaToken'])>0)
    {
    	
          $Response = CPhoenixFunc::getContentFromUrl("https://www.google.com/recaptcha/api/siteverify?secret=".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SECRET_KEY"]["VALUE"]."&response={$_POST['captchaToken']}");
          $Return = json_decode($Response);

          if(!($Return->success == true && $Return->score >= floatval($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SCORE"]["VALUE"])))
                $go = false;
    }
    else
        $go = false;

}



if($go)
{


    $email = trim($_POST["bx-email"]);
    $name = trim($_POST["bx-name"]);
    $password = trim($_POST["bx-password"]);


    if(trim(SITE_CHARSET) == "windows-1251")
    {
    	$name = utf8win1251($name);
    	$email = utf8win1251($email);
    	$password = utf8win1251($password);
    }
        
    $NEW_LOGIN = $email;
    
    $dbUserLogin = CUser::GetByLogin($NEW_LOGIN);


    
    if($dbUserLogin->Fetch())
    {
        $arResult["ERROR"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["REG_ERROR_EMAIL"];	
    }
    else
    {
    	$arResult["HREF"] = SITE_DIR."personal/register_success/";
        $user = new CUser;


        $def_group = \Bitrix\Main\Config\Option::get("main", "new_user_registration_def_group", "");
                        
        if ($def_group != "")
            $groupID = explode(",", $def_group);
        
        else
        	$groupID = array();


        $email_confirmation = \Bitrix\Main\Config\Option::get("main", "new_user_registration_email_confirmation", "");
        $email_required = \Bitrix\Main\Config\Option::get("main", "new_user_email_required", "");

        $bConfirmReq = ($email_confirmation == "Y" && $email_required == "Y");
        $randCode = ($bConfirmReq)?randString(8):"";

        if($bConfirmReq)
        	$arResult["HREF"] .= "?REGISTER=Y";


        $arValues = array(
        	"GROUP_ID" => $groupID,
			"LOGIN" => $NEW_LOGIN,
			"NAME" => $name,
			"LAST_NAME" => "",
			"PASSWORD" => $password,
			"CONFIRM_PASSWORD" => $password,
			"EMAIL" => $email,
			"ACTIVE" => ($bConfirmReq)?"N":"Y",
			"CONFIRM_CODE" => $randCode,
			"LID" => SITE_ID,
        );

        
        $arAuthResult = $user->Add($arValues);

        $arValues["USER_ID"] = $arAuthResult;

        $arFields = $arValues;

		unset($arFields["PASSWORD"]);
		unset($arFields["CONFIRM_PASSWORD"]);
        
        if (IntVal($arAuthResult) <= 0)
		{
			$arResult["ERROR"] = ((strlen($user->LAST_ERROR) > 0) ? $user->LAST_ERROR : "" );
		}
		else
		{

			if($bConfirmReq)
			{
				if(strlen($arValues["EMAIL"])>0)
				{
					$event = new CEvent;
					$event->Send("NEW_USER_CONFIRM", SITE_ID, $arFields);
					$arResult["OK"] = "Y";
				}
			}
			else
			{
            	$USER->Authorize($arAuthResult);
            	if ($USER->IsAuthorized())
					$arResult["OK"] = "Y";
				
				else
				{
					$arResult["OK"] = "N";
					$er = GetMessage("STOF_ERROR_REG_CONFIRM");

		            if(trim(SITE_CHARSET) == "windows-1251")
		                $er = iconv('windows-1251', 'UTF-8', $er);
		            
					$arResult["ERROR"] = $er;
				}
			}
				
            
		}
        
        
    } 			


	if(isset($arResult["ERROR"]))
	{
		if(strlen($arResult["ERROR"]))
		{
			if(trim(SITE_CHARSET) == "windows-1251")
				$arResult["ERROR"] = iconv('windows-1251', 'UTF-8', $arResult["ERROR"]);
		}

	}
}



$arResult = json_encode($arResult);
echo $arResult;
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>