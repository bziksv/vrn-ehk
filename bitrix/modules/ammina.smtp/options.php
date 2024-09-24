<?

use Bitrix\Main\Localization\Loc;

if (CAmminaSmtp::getTestPeriodInfo() == \Bitrix\Main\Loader::MODULE_DEMO) {
	CAdminMessage::ShowMessage(array("MESSAGE" => Loc::getMessage("AMMINA_SMTP_SYS_MODULE_IS_DEMO"), "HTML" => true));
} elseif (CAmminaSmtp::getTestPeriodInfo() == \Bitrix\Main\Loader::MODULE_DEMO_EXPIRED) {
	CAdminMessage::ShowMessage(array("MESSAGE" => Loc::getMessage("AMMINA_SMTP_SYS_MODULE_IS_DEMO_EXPIRED"), "HTML" => true));
}

$module_id = "ammina.smtp";
$modulePermissions = CMain::GetGroupRight($module_id);
if ($modulePermissions >= "R") {

	global $MESS;
	IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/options.php");
	IncludeModuleLangFile(__FILE__);

	CModule::IncludeModule($module_id);

	if ($_SERVER['REQUEST_METHOD'] === "GET" && amsmtp_strlen($_REQUEST['RestoreDefaults']) > 0 && $modulePermissions >= "W" && check_bitrix_sessid()) {
		COption::RemoveOption($module_id);
		$z = CGroup::GetList($v1 = "id", $v2 = "asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
		while ($zr = $z->Fetch()) {
			CMain::DelGroupRight($module_id, array($zr["ID"]));
		}
	}
	$arAllowImportUpdateFields = array(
		"ACTIVE" => GetMessage("ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_ACTIVE"),
		"SMTP_HOST" => GetMessage("ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_SMTP_HOST"),
		"SMTP_PORT" => GetMessage("ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_SMTP_PORT"),
		"SECURE_TYPE" => GetMessage("ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_SECURE_TYPE"),
		"SENDER_NAME" => GetMessage("ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_SENDER_NAME"),
		"ACCOUNT_LOGIN" => GetMessage("ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_ACCOUNT_LOGIN"),
		"ACCOUNT_PASSWORD" => GetMessage("ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_ACCOUNT_PASSWORD"),
	);
	$arAllOptions = array(
		array("activate_module", GetMessage("ammina.smtp_OPTION_ACTIVATE_MODULE"), "N", array("checkbox")),
		//array("log", GetMessage("ammina.smtp_OPTION_LOG"), "N", array("checkbox")),
		array("debug_level", GetMessage("ammina.smtp_OPTION_DEBUG_LEVEL"), "0", array("selectbox"), array(
			"0" => GetMessage("ammina.smtp_OPTION_DEBUG_LEVEL_0"),
			"1" => GetMessage("ammina.smtp_OPTION_DEBUG_LEVEL_1"),
			"2" => GetMessage("ammina.smtp_OPTION_DEBUG_LEVEL_2"),
			"3" => GetMessage("ammina.smtp_OPTION_DEBUG_LEVEL_3"),
			"4" => GetMessage("ammina.smtp_OPTION_DEBUG_LEVEL_4"),
		)),
		array("timeout", GetMessage("ammina.smtp_OPTION_TIMEOUT"), "30", array("text", 10)),
		array("not_change_name_sender", GetMessage("ammina.smtp_OPTION_NOT_CHANGE_NAME_SENDER"), "Y", array("checkbox")),
		array("not_hide_password", GetMessage("ammina.smtp_OPTION_NOT_HIDE_PASSWORD"), "N", array("checkbox")),
		//array("show_support_form", Loc::getMessage("ammina.smtp_OPTION_SHOW_SUPPORT_FORM"), "Y", array("checkbox")),

		array("separator", Loc::getMessage("ammina.smtp_OPTION_SEPARATOR_LIMITS")),
		array("use_limits", GetMessage("ammina.smtp_OPTION_USE_LIMITS"), "N", array("checkbox")),
		array("limits_domain_day", GetMessage("ammina.smtp_OPTION_LIMITS_DOMAIN_DAY"), "5000", array("text", 10)),
		array("limits_domain_hour", GetMessage("ammina.smtp_OPTION_LIMITS_DOMAIN_HOUR"), "200", array("text", 10)),
		array("limits_domain_minute", GetMessage("ammina.smtp_OPTION_LIMITS_DOMAIN_MINUTE"), "3", array("text", 10)),
		array("limits_account_day", GetMessage("ammina.smtp_OPTION_LIMITS_ACCOUNT_DAY"), "3000", array("text", 10)),
		array("limits_account_hour", GetMessage("ammina.smtp_OPTION_LIMITS_ACCOUNT_HOUR"), "125", array("text", 10)),
		array("limits_account_minute", GetMessage("ammina.smtp_OPTION_LIMITS_ACCOUNT_MINUTE"), "2", array("text", 10)),

		array("separator", Loc::getMessage("ammina.smtp_OPTION_SEPARATOR_STATS")),
		array("use_stats", GetMessage("ammina.smtp_OPTION_USE_STATS"), "Y", array("checkbox")),
		array("stats_days", GetMessage("ammina.smtp_OPTION_STATS_DAYS"), "365", array("text", 10)),

		array("separator", Loc::getMessage("ammina.smtp_OPTION_SEPARATOR_QUEUE")),
		array("use_queue", GetMessage("ammina.smtp_OPTION_USE_QUEUE"), "Y", array("checkbox")),
		array("queue_days", GetMessage("ammina.smtp_OPTION_QUEUE_DAYS"), "30", array("text", 10)),
		array("queue_error_days", GetMessage("ammina.smtp_OPTION_QUEUE_ERROR_DAYS"), "90", array("text", 10)),

		array("separator", Loc::getMessage("ammina.smtp_OPTION_SEPARATOR_IMPORT_FROM_MAIL")),
		array("import_from_mail", GetMessage("ammina.smtp_OPTION_IMPORT_FROM_MAIL"), "N", array("checkbox")),
		array("import_math_by_port", GetMessage("ammina.smtp_OPTION_IMPORT_MATH_BY_PORT"), "Y", array("checkbox")),
		array("servers_math", Loc::getMessage("ammina.smtp_OPTION_SERVERS_MATH"), implode("\n", CAmminaSmtpConstants::$DEFAULT_SERVERS_MATH), array("textarea", 10, 50)),
		array("port_math", Loc::getMessage("ammina.smtp_OPTION_PORT_MATH"), implode("\n", CAmminaSmtpConstants::$DEFAULT_PORT_MATH), array("textarea", 5, 30)),
		array("import_update_fields", GetMessage("ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS"), implode("|", CAmminaSmtpConstants::$DEFAULT_UPDATE_FIELDS), array("selectboxm", 7), $arAllowImportUpdateFields),
		array("import_mark_as_import", GetMessage("ammina.smtp_OPTION_IMPORT_MARK_AS_IMPORT"), "Y", array("checkbox")),
		array("import_delete_no_exists", GetMessage("ammina.smtp_OPTION_IMPORT_DELETE_NOT_EXISTS"), "Y", array("checkbox")),
	);

	$strWarning = "";
	if ($_SERVER['REQUEST_METHOD'] === "POST" && amsmtp_strlen($_REQUEST['Update']) > 0 && $modulePermissions === "W" && check_bitrix_sessid()) {
		foreach ($arAllOptions as $option) {
			if ($option[0] === "separator") {
				continue;
			}
			$name = $option[0];
			$val = $$name;
			if ($option[3][0] === "checkbox" && $val !== "Y") {
				$val = "N";
			}
			if ($option[3][0] === "selectboxm") {
				if (!is_array($val)) {
					$val = array();
				}
				$val = implode("|", $val);
			}
			COption::SetOptionString($module_id, $name, $val, $option[1]);
		}
	}
	\Ammina\SMTP\Agent\SyncMail::checkAgentExists();
	\Ammina\SMTP\Agent\CheckQueue::checkAgentExists();
	\Ammina\SMTP\Agent\CheckClear::checkAgentExists();

	if (amsmtp_strlen($strWarning) > 0)
		CAdminMessage::ShowMessage($strWarning);

	$aTabs = array();
	$aTabs[] = array(
		'DIV' => 'edit1',
		'TAB' => GetMessage('ammina.smtp_TAB_SETTINGS_TITLE'),
		'TITLE' => GetMessage('ammina.smtp_TAB_SETTINGS_DESC'),
	);
	$aTabs[] = array(
		'DIV' => 'edit2',
		'TAB' => GetMessage('ammina.smtp_TAB_SUPPORT_TITLE'),
		'TITLE' => GetMessage('ammina.smtp_TAB_SUPPORT_DESC'),
	);
	$aTabs[] = array(
		'DIV' => 'editrights',
		'TAB' => GetMessage('ammina.smtp_TAB_RIGHTS_TITLE'),
		'TITLE' => GetMessage('ammina.smtp_TAB_RIGHTS_DESC'),
	);
	$tabControl = new CAdminTabControl('tabControl', $aTabs);

	$tabControl->Begin();
	?>
	<form method="POST" action="<?
	echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&lang=<?= LANGUAGE_ID ?>"><?
		bitrix_sessid_post();
		$tabControl->BeginNextTab();
		foreach ($arAllOptions as $Option) {
			$val = COption::GetOptionString($module_id, $Option[0], $Option[2]);
			$type = $Option[3];
			if ($Option[0] == "separator") {
				?>
				<tr class="heading">
					<td colspan="2"><?= $Option[1] ?></td>
				</tr>
				<?
			} else {
				?>
				<tr>
					<td valign="top" width="50%"><?
						if ($type[0] === "checkbox")
							echo "<label for=\"" . htmlspecialcharsbx($Option[0]) . "\">" . $Option[1] . "</label>";
						else
							echo $Option[1];
						?></td>
					<td valign="middle" width="50%">
						<?
						if ($type[0] === "checkbox") {
							?>
							<input type="checkbox" name="<?
							echo htmlspecialcharsbx($Option[0]) ?>" id="<?
							echo htmlspecialcharsbx($Option[0]) ?>" value="Y"<?
							if ($val === "Y") echo " checked"; ?>>
						<? } elseif ($type[0] === "text") {
							?>
							<input type="text" size="<?
							echo $type[1] ?>" value="<?
							echo htmlspecialcharsbx($val) ?>" name="<?
							echo htmlspecialcharsbx($Option[0]) ?>">
						<? } elseif ($type[0] === "textarea") {
							?>
							<textarea rows="<?
							echo $type[1] ?>" cols="<?
							echo $type[2] ?>" name="<?
							echo htmlspecialcharsbx($Option[0]) ?>"><?
								echo htmlspecialcharsbx($val) ?></textarea>
						<? } elseif ($type[0] === "selectbox") {
							?>
							<select name="<?
							echo htmlspecialcharsbx($Option[0]) ?>" id="<?
							echo htmlspecialcharsbx($Option[0]) ?>">
								<?
								foreach ($Option[4] as $v => $k) {
									?>
									<option value="<?= $v ?>"<?
									if ($val == $v) echo " selected"; ?>><?= $k ?></option><?
								}
								?>
							</select>
						<? } elseif ($type[0] === "selectboxm") {
							$val = explode("|", $val);
							?>
							<select name="<?
							echo htmlspecialcharsbx($Option[0]) ?>[]" id="<?
							echo htmlspecialcharsbx($Option[0]) ?>" size="<?= intval($type[1]) ?>" multiple="multiple">
								<?
								foreach ($Option[4] as $v => $k) {
									?>
									<option value="<?= $v ?>"<?
									if (in_array($v, $val)) {
										echo " selected";
									} ?>><?= $k ?></option><?
								}
								?>
							</select>
						<? } ?>
					</td>
				</tr>
				<?
			}
		}
		$tabControl->BeginNextTab();
		?>
		<tr>
			<td>
				<? echo GetMessage("ammina.smtp_TAB_SUPPORT_CONTENT"); ?>
			</td>
		</tr>
		<?
		$tabControl->BeginNextTab();
		require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php"); ?>
		<?
		$tabControl->Buttons(); ?>
		<script language="JavaScript">
			function RestoreDefaults() {
				if (confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>'))
					window.location = "<?echo $APPLICATION->GetCurPage()?>?RestoreDefaults=Y&lang=<?echo LANG?>&mid=<?echo urlencode($mid) . "&" . bitrix_sessid_get();?>";
			}
		</script>

		<input type="submit" <?
		if ($modulePermissions < "W") echo "disabled" ?> name="Update" value="<?= GetMessage("MAIN_SAVE") ?>">
		<input type="hidden" name="Update" value="Y">
		<input type="reset" name="reset" value="<?= GetMessage("MAIN_RESET") ?>">
		<input type="button" <?
		if ($modulePermissions < "W") echo "disabled" ?> title="<?= GetMessage("MAIN_HINT_RESTORE_DEFAULTS") ?>" onclick="RestoreDefaults();" value="<?= GetMessage("MAIN_RESTORE_DEFAULTS") ?>">
		<?
		$tabControl->End();
		?>
	</form>
	<?
}
