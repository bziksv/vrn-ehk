<?php

define('NO_KEEP_STATISTIC', true);
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);
define('NOT_CHECK_PERMISSIONS', true);
define('DisableEventsCheck', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

header('Content-Type: application/json; charset=UTF-8');

function vrnEhkEmailExistsJson(array $data): void
{
	echo \Bitrix\Main\Web\Json::encode($data);
	die;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
	vrnEhkEmailExistsJson(['ok' => false, 'exists' => false]);
}

$email = trim((string)($_POST['email'] ?? ''));
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
	vrnEhkEmailExistsJson(['ok' => true, 'exists' => false]);
}

try {
	$connection = \Bitrix\Main\Application::getConnection();
	$escaped = $connection->getSqlHelper()->forSql($email);
	$row = $connection->query(
		"SELECT ID FROM b_user
		WHERE (EXTERNAL_AUTH_ID IS NULL OR EXTERNAL_AUTH_ID='')
			AND (LOWER(LOGIN)=LOWER('{$escaped}') OR LOWER(EMAIL)=LOWER('{$escaped}'))
		LIMIT 1"
	)->fetch();
	vrnEhkEmailExistsJson(['ok' => true, 'exists' => !empty($row)]);
} catch (\Throwable $e) {
	vrnEhkEmailExistsJson(['ok' => false, 'exists' => false]);
}
