<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/include/env.php";
?>
<?$update_user = new CUser;
if($USER->IsAuthorized())
{
	$ar_cur_user=CUser::GetByID($USER->GetID());
	if($cur_user=$ar_cur_user->GetNext())
	{
		$fields=array();
		$emailChanged = false;
		foreach($_POST as $key=>$value)
		{
			$fields[$key]=strip_tags($value);
		}
		unset($fields["CONFIRM_CODE"]);
		if(isset($fields["EMAIL"]) && !empty($fields["EMAIL"]))
		{
			$oldLogin = trim((string)$cur_user["LOGIN"]);
			$oldEmail = trim(htmlspecialchars_decode((string)$cur_user["EMAIL"], ENT_QUOTES));
			$newEmail = trim((string)$fields["EMAIL"]);
			if ($oldLogin === '' || strcasecmp(htmlspecialchars_decode($oldLogin, ENT_QUOTES), $oldEmail) === 0)
			{
				$fields["LOGIN"] = $fields["EMAIL"];
			}
			else
			{
				unset($fields["LOGIN"]);
			}
			if (strcasecmp($newEmail, $oldEmail) !== 0)
			{
				$fields["CONFIRM_CODE"] = RandString(8);
				$emailChanged = true;
			}
		}
		$location=false;
		if(isset($fields["LOCATION"]) && !empty($fields["LOCATION"]))
		{
			$location=$fields["LOCATION"];
		}
		elseif(isset($fields["REGIONLOCATION"]) && !empty($fields["REGIONLOCATION"]))
		{
			$location=$fields["REGIONLOCATION"];
		}
		elseif(isset($fields["COUNTRYLOCATION"]) && !empty($fields["COUNTRYLOCATION"]))
		{
			$location=$fields["COUNTRYLOCATION"];
		}
		if($location)
		{
			$fields["UF_LOCATION"]=$location;
		}
		$res=$update_user->Update($cur_user["ID"], $fields);
		$strError.=$update_user->LAST_ERROR;
		if($res)
		{
			if ($emailChanged)
			{
				vrnEhkSendEmailConfirm((int)$cur_user["ID"]);
			}
			echo "success";
		}
		else
		{
			echo "error_".$strError;
		}
	}
	else
	{
		echo "error";
	}
}
else
{
	echo "error";
}?>