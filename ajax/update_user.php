<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$update_user = new CUser;
if($USER->IsAuthorized())
{
	$ar_cur_user=CUser::GetByID($USER->GetID());
	if($cur_user=$ar_cur_user->GetNext())
	{
		$fields=array();
		foreach($_POST as $key=>$value)
		{
			$fields[$key]=strip_tags($value);
		}
		if(isset($fields["EMAIL"]) && !empty($fields["EMAIL"]))
		{
			$fields["LOGIN"]=$fields["EMAIL"];
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