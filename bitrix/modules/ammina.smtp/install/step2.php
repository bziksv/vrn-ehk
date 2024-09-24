<?
if (!check_bitrix_sessid()) {
	return;
}

global $errors, $APPLICATION;

if (amsmtp_strlen($errors) <= 0) {
	CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));
} elseif (is_array($errors)) {
	for ($i = 0; $i < count($errors); $i++)
		$alErrors .= $errors[$i] . "<br>";
	CAdminMessage::ShowMessage(array("TYPE" => "ERROR", "MESSAGE" => GetMessage("MOD_INST_ERR"), "DETAILS" => $alErrors, "HTML" => true));
}
if ($ex = $APPLICATION->GetException()) {
	CAdminMessage::ShowMessage(array("TYPE" => "ERROR", "MESSAGE" => GetMessage("MOD_INST_ERR"), "HTML" => true, "DETAILS" => $ex->GetString()));
}
?>
<form action="<? echo $APPLICATION->GetCurPage() ?>">
	<input type="hidden" name="lang" value="<? echo LANG ?>">
	<input type="submit" name="" value="<? echo GetMessage("MOD_BACK") ?>">
	<form>