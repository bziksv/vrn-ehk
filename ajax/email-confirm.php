<?php

define('NO_KEEP_STATISTIC', true);
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);
define('NOT_CHECK_PERMISSIONS', true);
define('DisableEventsCheck', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/env.php';

header('Content-Type: application/json; charset=UTF-8');

function vrnEhkEmailConfirmJson(array $data, int $code = 200): void
{
	http_response_code($code);
	echo \Bitrix\Main\Web\Json::encode($data);
	die;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
	vrnEhkEmailConfirmJson(['ok' => false, 'error' => 'method'], 405);
}

global $USER;
if (!is_object($USER) || !$USER->IsAuthorized() || !check_bitrix_sessid()) {
	vrnEhkEmailConfirmJson(['ok' => false, 'error' => 'Нужно войти в аккаунт'], 403);
}

$rs = CUser::GetByID((int)$USER->GetID());
$user = $rs ? $rs->Fetch() : null;
if (!$user) {
	vrnEhkEmailConfirmJson(['ok' => false, 'error' => 'Пользователь не найден'], 404);
}

if (trim((string)($user['CONFIRM_CODE'] ?? '')) === '') {
	vrnEhkEmailConfirmJson(['ok' => true, 'already' => true, 'message' => 'Почта уже подтверждена']);
}

try {
	$session = \Bitrix\Main\Application::getInstance()->getSession();
	$last = (int)$session->get('VRN_EHK_EMAIL_CONFIRM_SENT_AT');
	if ($last > 0 && (time() - $last) < 60) {
		vrnEhkEmailConfirmJson(['ok' => false, 'error' => 'Письмо уже отправлено. Подождите минуту.']);
	}
} catch (\Throwable $e) {
	// ignore
}

if (!vrnEhkSendEmailConfirm((int)$user['ID'])) {
	vrnEhkEmailConfirmJson(['ok' => false, 'error' => 'Не удалось отправить письмо'], 500);
}

try {
	\Bitrix\Main\Application::getInstance()->getSession()->set('VRN_EHK_EMAIL_CONFIRM_SENT_AT', time());
} catch (\Throwable $e) {
	// ignore
}

vrnEhkEmailConfirmJson(['ok' => true, 'message' => 'Письмо отправлено. Перейдите по ссылке из письма.']);
