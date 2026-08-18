<?php
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/include/env.php';

header('Content-Type: text/plain; charset=UTF-8');

if (!$USER->IsAuthorized()) {
	echo 'error';
	die;
}

$allowed = [
	'LAST_NAME',
	'NAME',
	'SECOND_NAME',
	'EMAIL',
	'PERSONAL_PHONE',
	'WORK_COMPANY',
	'UF_INN',
	'UF_KPP',
	'UF_ADDRESS',
	'UF_AGENT',
];

$userId = (int)$USER->GetID();
$rs = CUser::GetByID($userId);
$cur = $rs ? $rs->Fetch() : false;
if (!$cur) {
	echo 'error';
	die;
}

$fields = [];
foreach ($allowed as $key) {
	if (!array_key_exists($key, $_POST)) {
		continue;
	}
	$fields[$key] = trim(strip_tags((string)$_POST[$key]));
}

$emailChanged = false;
if (isset($fields['EMAIL']) && $fields['EMAIL'] !== '') {
	$oldEmail = trim((string)$cur['EMAIL']);
	$newEmail = $fields['EMAIL'];
	$oldLogin = trim((string)$cur['LOGIN']);
	if ($oldLogin === '' || strcasecmp($oldLogin, $oldEmail) === 0) {
		$fields['LOGIN'] = $newEmail;
	}
	if (strcasecmp($newEmail, $oldEmail) !== 0) {
		$fields['CONFIRM_CODE'] = RandString(8);
		$emailChanged = true;
	}
}

if (isset($fields['PERSONAL_PHONE'])) {
	if (!vrnEhkIsValidRuPhone($fields['PERSONAL_PHONE'])) {
		unset($fields['PERSONAL_PHONE']);
	} else {
		$fields['PERSONAL_PHONE'] = vrnEhkFormatRuPhone($fields['PERSONAL_PHONE']);
	}
}

if (!$fields) {
	echo 'success';
	die;
}

$update = new CUser;
if ($update->Update($userId, $fields)) {
	if ($emailChanged) {
		$check = CUser::GetByID($userId)->Fetch();
		$code = trim((string)($fields['CONFIRM_CODE'] ?? ''));
		if ($code !== '' && trim((string)($check['CONFIRM_CODE'] ?? '')) === '') {
			$update->Update($userId, ['CONFIRM_CODE' => $code]);
		}
		try {
			$session = \Bitrix\Main\Application::getInstance()->getSession();
			$session->remove('VRN_EHK_EMAIL_CONFIRM_ACK');
		} catch (Throwable $e) {
			// ignore
		}
		vrnEhkSendEmailConfirm($userId);
	}
	echo 'success';
} else {
	echo 'error_'.$update->LAST_ERROR;
}
