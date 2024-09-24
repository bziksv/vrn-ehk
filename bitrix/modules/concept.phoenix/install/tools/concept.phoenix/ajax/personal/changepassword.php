<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>


<?
$arResult = array(
	"OK" => "N",
	"SUCCESS" => "",
	"ERRORS" => array()
);

if($_POST["send"] == "Y" && isset($_POST["login"]) && isset($_POST["checkword"]))
{

	CModule::IncludeModule("concept.phoenix");
    global $PHOENIX_TEMPLATE_ARRAY;
    CPhoenix::phoenixOptionsValues(SITE_ID, array(
    	"start",
		"lids",
		"other"
	));


	$login = trim($_POST["login"]);
	$checkword = trim($_POST["checkword"]);
	$password = trim($_POST["password"]);
	$password_confirm = trim($_POST["password_confirm"]);

    if(trim(SITE_CHARSET) == "windows-1251")
    {
    	$login = utf8win1251($login);
    	$password = utf8win1251($password);
    	$password_confirm = utf8win1251($password_confirm);
    }

	$rsUser = CUser::GetByLogin($login);
	if($arUser = $rsUser->Fetch())
	{
		$securityPolicy = \CUser::GetGroupPolicy($arUser["ID"]);
		if($arUser["CHECKWORD"] == $checkword)
		{

			if($password != $password_confirm)
			{
				$arResult["ERRORS"][] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CHANGEPASSWORD_ER_DIFFERENT"];
			}
			else
			{
				$user = new CUser;
				$errors = $user->CheckPasswordAgainstPolicy($password, $securityPolicy);

				if(!empty($errors))
				{
					$arResult["ERRORS"] = array_merge($arResult["ERRORS"], $errors);
				}
				else
				{
					global $USER;

					$fields = array(
				        "PASSWORD"=> $password,
				        "CONFIRM_PASSWORD"=> $password_confirm
				    );
				      
				    if($USER->Update($arUser["ID"], $fields))
				    {

						$arFields = array(
							"EMAIL_FROM" => $PHOENIX_TEMPLATE_ARRAY["SITE_EMAIL"],
							"EMAIL_TO" => $arUser["EMAIL"],
							"SITENAME" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["NAME_SITE"]["VALUE"],
							"SITE_URL" => $PHOENIX_TEMPLATE_ARRAY["SITE_URL"],
							"PASSWORD" => $password,
							"LOGIN" => $login,
						);


						$eventName = "PHOENIX_CHANGE_PASSWORD_SUCCESS_".SITE_ID;

						$event = new CEvent;

						if($event->Send($eventName, SITE_ID, $arFields, "N"))
						{
							$arResult["SUCCESS"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CHANGEPASSWORD_SUCCESS"];

							$arResult["OK"] = "Y";
						}
					}
					else
					{
						$arResult["ERRORS"][] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CHANGEPASSWORD_ER_FAIL"];
					}
				}
			}
		}
		else
		{
			$arResult["ERRORS"][] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CHANGEPASSWORD_ERROR_CHECKWORD"].$login;
		}
	}
	else
	{
	    $arResult["ERRORS"][] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["ERROR_LOGIN_UNDEFINED_1"].$login.$PHOENIX_TEMPLATE_ARRAY["MESS"]["ERROR_LOGIN_UNDEFINED_2"];
	}
}

if(!empty($arResult["ERRORS"]) && trim(SITE_CHARSET) == "windows-1251")
{
	foreach ($arResult["ERRORS"] as $key => $value)
	{
		$arResult["ERRORS"][$key] = iconv('windows-1251', 'UTF-8', $value);
	}
}

if(strlen($arResult["SUCCESS"]))
{
	if(trim(SITE_CHARSET) == "windows-1251")
		$arResult["SUCCESS"] = iconv('windows-1251', 'UTF-8', $arResult["SUCCESS"]);
}



$arResult = json_encode($arResult);
echo $arResult;
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>