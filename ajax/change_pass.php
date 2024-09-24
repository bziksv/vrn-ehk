<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?if($USER->IsAuthorized())
{
	$ar_cur_user=CUser::GetByID($USER->GetID());
	if($cur_user=$ar_cur_user->GetNext())
	{
		foreach($_POST as $key=>$value)
		{
			$PROP[$key]=strip_tags($value);
		}
		if(strlen($cur_user["PASSWORD"])>32)
      	{
         	$salt=substr($cur_user["PASSWORD"], 0, strlen($cur_user["PASSWORD"])-32);
         	$db_password=substr($cur_user["PASSWORD"],-32);
      	}
      	else
      	{
         	$salt="";
         	$db_password=$cur_user["PASSWORD"];
      	}
      	$user_password=md5($salt.$PROP['PASSWORD']);
      	if($user_password==$db_password)
      	{
			if($PROP["NEW_PASSWORD"]==$PROP["CONFIRM_NEW_PASSWORD"])
			{
				$fields=array(
					"PASSWORD"=>$PROP["NEW_PASSWORD"],
					"CONFIRM_PASSWORD"=>$PROP["CONFIRM_NEW_PASSWORD"]
				);
				$update_user = new CUser;
				$res=$update_user->Update($cur_user["ID"], $fields);
				$strError.=$update_user->LAST_ERROR;
				if($res)
				{
					echo "success";
				}
				else
				{
					echo "error_".$strError;
				}
			}
			else
			{
				echo "error_Введенные пароли не совпадают";
			}
		}
		else
		{
			echo "error_Вы ввели неверный пароль";
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