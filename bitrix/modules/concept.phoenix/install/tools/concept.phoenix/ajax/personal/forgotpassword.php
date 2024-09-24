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
	"ERRORS" => ""
);

if($_POST["send"] == "Y")
{
	CModule::IncludeModule("concept.phoenix");
    global $PHOENIX_TEMPLATE_ARRAY;
    CPhoenix::phoenixOptionsValues(SITE_ID, array(
    	"start",
		"lids",
		"other"
	));

    $login = trim($_POST["login"]);

    if(trim(SITE_CHARSET) == "windows-1251")
    	$login = utf8win1251($login);



	$rsUser = CUser::GetByLogin($login);
	if($arUser = $rsUser->Fetch())
	{

	    $arFields = array(
			"EMAIL_FROM" => $PHOENIX_TEMPLATE_ARRAY["SITE_EMAIL"],
			"EMAIL_TO" => $arUser["EMAIL"],
			"SITENAME" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["NAME_SITE"]["VALUE"],
			"LINK_CHANGE_PASS" => $PHOENIX_TEMPLATE_ARRAY["SITE_URL"]."personal/changepasswd/?USER_CHECKWORD=".$arUser["CHECKWORD"]."&USER_LOGIN=".$login,
			"SITE_URL" => $PHOENIX_TEMPLATE_ARRAY["SITE_URL"]
		);


		$eventName = "PHOENIX_USER_PASS_REQUEST_".SITE_ID;

		$event = new CEvent;

		if($event->Send($eventName, SITE_ID, $arFields, "N"))
		{
			$arResult["SUCCESS"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_FORGOT_PAGE_SUCCESS"];

			$arResult["OK"] = "Y";
		}
	}
	else
	{
	    $arResult["ERRORS"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["ERROR_LOGIN_UNDEFINED_1"].$login.$PHOENIX_TEMPLATE_ARRAY["MESS"]["ERROR_LOGIN_UNDEFINED_2"];
	}

}

if(strlen($arResult["ERRORS"]))
{
	if(trim(SITE_CHARSET) == "windows-1251")
		$arResult["ERRORS"] = iconv('windows-1251', 'UTF-8', $arResult["ERRORS"]);
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