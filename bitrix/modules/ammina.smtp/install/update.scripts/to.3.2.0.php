<?
/**
 * ОБРАТИТЕ ВНИМАНИЕ!<br>
 * Обновление вносит изменения в структуру таблиц базы данных. Обязательно перед обновлением модуля создайте резервную копию сайта (либо дамп таблиц модуля am_smtp_* и архив файлов модуля /bitrix/modules/ammina.smtp/)<br><br>
 * 1. Совместимость с PHP8.1<br>
 * 2. В очереди отправки писем добавлена возможность просмотра получаетеля и темы письма
 */
global $DB;
if ($updater->CanUpdateDatabase()) {
	if ($updater->TableExists("am_smtp_queue")) {
		$arFields = $DB->GetTableFields("am_smtp_queue");
		if (!isset($arFields['FIELD_TO'])) {
			$updater->Query(
				array(
					"MySQL" => "ALTER TABLE `am_smtp_queue` ADD `FIELD_TO` VARCHAR(255) NULL DEFAULT NULL AFTER `DATE_SEND`;",
				)
			);
		}
		if (!isset($arFields['FIELD_SUBJECT'])) {
			$updater->Query(
				array(
					"MySQL" => "ALTER TABLE `am_smtp_queue` ADD `FIELD_SUBJECT` VARCHAR(511) NULL DEFAULT NULL AFTER `FIELD_TO`;",
				)
			);
		}
	}
}
?>