<?
if (preg_match('#^/filecleaner_backup/#', $_SERVER['REQUEST_URI'])) {
    header("HTTP/1.0 403 Forbidden");
    die('Access denied');
}

include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/404.php'))
	include_once($_SERVER['DOCUMENT_ROOT'].'/404.php');
?>