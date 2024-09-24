<?
/**
 * ОБРАТИТЕ ВНИМАНИЕ!<br>
 * Обновление вносит изменения в структуру таблиц базы данных. Обязательно перед обновлением модуля создайте резервную копию сайта (либо дамп таблиц модуля am_smtp_* и архив файлов модуля /bitrix/modules/ammina.smtp/)<br><br>
 * Добавлена возможность синхронизации почтовых аккаунтов из модуля Почта. Для активации функционала настройте и включите агента синхронизации в настройках модуля
 */
if (IsModuleInstalled('ammina.smtp')) {
}
global $DB;
if ($updater->CanUpdateDatabase()) {
	if ($updater->TableExists("am_smtp_accounts")) {
		$arFields = $DB->GetTableFields("am_smtp_accounts");
		if (!isset($arFields['IS_IMPORT'])) {
			$updater->Query(
				array(
					"MySQL" => "ALTER TABLE `am_smtp_accounts` ADD `IS_IMPORT` CHAR(1) NOT NULL DEFAULT 'N' AFTER `ACCOUNT_PASSWORD`;",
				)
			);
		}
	}

}

?>