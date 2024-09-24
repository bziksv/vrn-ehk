<?//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
//error_reporting(-1);

$root=$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/amocrm/";

require $root.'prepare.php';
require $root.'auth.php';
require $root.'account_current.php';
require $root.'fields_info.php';
require $root.'lead_add.php';
require $root.'contacts_list.php';
require $root.'contact_update.php';
require $root.'contact_add.php';
?>

<?//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>