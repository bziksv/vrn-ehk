<?php

define('NO_KEEP_STATISTIC', true);
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);
define('NOT_CHECK_PERMISSIONS', true);
define('DisableEventsCheck', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

header('Content-Type: application/json; charset=UTF-8');

if (!\Bitrix\Main\Loader::includeModule('prime.phoneauth')) {
	http_response_code(500);
	echo \Bitrix\Main\Web\Json::encode(['ok' => false, 'message' => 'module']);
	die;
}

$bag = \Prime\PhoneAuth\NovofonWebhook::parseParams();
$check = \Prime\PhoneAuth\NovofonWebhook::verifyRequest($bag);
if (!$check['ok']) {
	http_response_code((int)($check['status'] ?? 403));
	echo \Bitrix\Main\Web\Json::encode($check);
	die;
}

$result = \Prime\PhoneAuth\NovofonWebhook::handle($bag);
echo \Bitrix\Main\Web\Json::encode($result);
