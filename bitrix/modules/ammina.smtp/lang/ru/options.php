<?

$MESS['ammina.smtp_PAGE_TITLE'] = "Модуль Ammina: Отправка почты через SMTP и DKIM подпись (Битрикс, коробка Битрикс24, Интернет-магазин+CRM)";
$MESS['ammina.smtp_TAB_SETTINGS_TITLE'] = "Настройки";
$MESS['ammina.smtp_TAB_SETTINGS_DESC'] = "Настройки модуля";
$MESS['ammina.smtp_TAB_SUPPORT_TITLE'] = "Техподдержка, развитие модуля";
$MESS['ammina.smtp_TAB_SUPPORT_DESC'] = "Техническая поддержка и пожелания по функционалу модуля ammina.smtp";
$MESS['ammina.smtp_TAB_SUPPORT_CONTENT'] = '
	<h3>Техническая поддержка</h3>
	<p>Техническая поддержка модуля осуществляется по электронной почте <a href="mailto:support@ammina.ru">support@ammina.ru</a></p>
	<h3>Развитие модуля, новый функционал</h3>
	<p>Если вы обнаружили что какого-то функционала модуля не хватает лично для вас - напишите нам.</p>
	<hr/>
	<h3>Наши контакты:</h3>
	<p>Электронная почта: <a href="mailto:support@ammina.ru">support@ammina.ru</a></p>
	<div style="clear:both;"></div>
';
$MESS['ammina.smtp_TAB_RIGHTS_TITLE'] = "Права на доступ";
$MESS['ammina.smtp_TAB_RIGHTS_DESC'] = "Настройка прав на доступ к модулю";
$MESS['ammina.smtp_OPTION_ACTIVATE_MODULE'] = "Активировать модуль";
$MESS['ammina.smtp_OPTION_LOG'] = "Логирование";
$MESS['ammina.smtp_OPTION_DEBUG_LEVEL'] = "Уровень отладки";
$MESS['ammina.smtp_OPTION_DEBUG_LEVEL_0'] = "0: Без отладки";
$MESS['ammina.smtp_OPTION_DEBUG_LEVEL_1'] = "1: Клиентские сообщения";
$MESS['ammina.smtp_OPTION_DEBUG_LEVEL_2'] = "2: Сообщения клиента и сервера";
$MESS['ammina.smtp_OPTION_DEBUG_LEVEL_3'] = "3: Сервер и состояние соединения";
$MESS['ammina.smtp_OPTION_DEBUG_LEVEL_4'] = "4: Низкоуровневый вывод данных";
$MESS['ammina.smtp_OPTION_TIMEOUT'] = "Таймаут SMTP соединения";
$MESS['ammina.smtp_OPTION_NOT_CHANGE_NAME_SENDER'] = "Не менять имя отправителя (если оно указано)";
$MESS['ammina.smtp_OPTION_NOT_HIDE_PASSWORD'] = "Не скрывать пароли почтовых аккаунтов";
$MESS['ammina.smtp_OPTION_SHOW_SUPPORT_FORM'] = "Показывать форму технической поддержки на административных страницах модуля";
$MESS['ammina.smtp_OPTION_SEPARATOR_IMPORT_FROM_MAIL'] = "Настройки агента синхронизации с модулем почты";
$MESS['ammina.smtp_OPTION_IMPORT_FROM_MAIL'] = "Синхронизировать ящики из модуля почты (агентом раз в 10 минут)";
$MESS['ammina.smtp_OPTION_IMPORT_MATH_BY_PORT'] = "При отстутствии соответствия по серверам создавать аккаунт соответствия по портам";
$MESS['ammina.smtp_OPTION_SERVERS_MATH'] = "Соответствие IMAP/POP3 серверов SMTP серверам при синхронизации с модулем почты";
$MESS['ammina.smtp_OPTION_PORT_MATH'] = "Соответствие IMAP/POP3 портов SMTP портам при синхронизации с модулем почты";
$MESS['ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS'] = "Разрешено обновлять поля аккаунта при изменении данных в модуле почта";
$MESS['ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_ACTIVE'] = "Активность";
$MESS['ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_SMTP_HOST'] = "Адрес SMTP сервера";
$MESS['ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_SMTP_PORT'] = "Порт SMTP сервера";
$MESS['ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_SECURE_TYPE'] = "Тип соединения";
$MESS['ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_SENDER_NAME'] = "Имя отправителя";
$MESS['ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_ACCOUNT_LOGIN'] = "Логин почтового аккаунта";
$MESS['ammina.smtp_OPTION_IMPORT_UPDATE_FIELDS_ACCOUNT_PASSWORD'] = "Пароль почтового аккаунта";
$MESS['ammina.smtp_OPTION_IMPORT_MARK_AS_IMPORT'] = "Пометить аккаунты (как импортированные), заведенные вручную, для которых есть ящики в модуле почты";
$MESS['ammina.smtp_OPTION_IMPORT_DELETE_NOT_EXISTS'] = "Удалять ящики, удаленные из модуля почты";

$MESS['ammina.smtp_OPTION_SEPARATOR_LIMITS'] = "Настройки лимитов отправки";
$MESS['ammina.smtp_OPTION_USE_LIMITS'] = "Использовать лимитирование отправки";
$MESS['ammina.smtp_OPTION_LIMITS_DOMAIN_DAY'] = "Лимит отправки писем в день для домена, по умолчанию";
$MESS['ammina.smtp_OPTION_LIMITS_DOMAIN_HOUR'] = "Лимит отправки писем в час для домена, по умолчанию";
$MESS['ammina.smtp_OPTION_LIMITS_DOMAIN_MINUTE'] = "Лимит отправки писем в минуту для домена, по умолчанию";
$MESS['ammina.smtp_OPTION_LIMITS_ACCOUNT_DAY'] = "Лимит отправки писем в день для аккаунта, по умолчанию";
$MESS['ammina.smtp_OPTION_LIMITS_ACCOUNT_HOUR'] = "Лимит отправки писем в час для аккаунта, по умолчанию";
$MESS['ammina.smtp_OPTION_LIMITS_ACCOUNT_MINUTE'] = "Лимит отправки писем в минуту для аккаунта, по умолчанию";

$MESS['ammina.smtp_OPTION_SEPARATOR_STATS'] = "Настройки статистики отправки";
$MESS['ammina.smtp_OPTION_USE_STATS'] = "Сохранять статистику отправки (если используются лимиты отправки, то статистика собирается всегда)";
$MESS['ammina.smtp_OPTION_STATS_DAYS'] = "Время хранения статистики, дней";

$MESS['ammina.smtp_OPTION_SEPARATOR_QUEUE'] = "Настройки сохранения отправленных писем";
$MESS['ammina.smtp_OPTION_USE_QUEUE'] = "Сохранять отправляемые письма в базе данных (если используются лимиты отправки, то сохраняться письма будут всегда)";
$MESS['ammina.smtp_OPTION_QUEUE_DAYS'] = "Время хранения отправленных, дней";
$MESS['ammina.smtp_OPTION_QUEUE_ERROR_DAYS'] = "Время хранения не отправленных (ошибки отправки), дней";
