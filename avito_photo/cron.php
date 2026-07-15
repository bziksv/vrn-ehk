<?php

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('BX_CRONTAB', true);
define('BX_NO_ACCELERATOR_RESET', true);

$_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/..');
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

if (!CModule::IncludeModule('iblock')) {
	fwrite(STDERR, "iblock module is not available\n");
	exit(1);
}

require_once __DIR__ . '/lib/AvitoPhotoService.php';

$limit = 10;
if (PHP_SAPI === 'cli' && isset($argv[1]) && is_numeric($argv[1])) {
	$limit = (int)$argv[1];
} elseif (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
	$limit = (int)$_GET['limit'];
}

$cleanup = true;
if (PHP_SAPI === 'cli' && isset($argv[2]) && $argv[2] === 'skip-cleanup') {
	$cleanup = false;
} elseif (isset($_GET['cleanup']) && $_GET['cleanup'] === 'N') {
	$cleanup = false;
}

$result = AvitoPhotoService::processBatch($limit, $cleanup);

$message = sprintf(
	"[%s] processed=%d remaining=%d ids=%s errors=%s\n",
	date('Y-m-d H:i:s'),
	$result['processed'],
	$result['remaining'],
	implode(',', $result['ids']),
	$result['errors'] ? implode('; ', $result['errors']) : '-'
);

echo $message;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';

exit(empty($result['errors']) ? 0 : 2);
