<?

use Bitrix\Main\Localization\Loc;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Loader::includeModule('ammina.smtp');
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/ammina.smtp/prolog.php");

Loc::loadMessages(__FILE__);
global $APPLICATION, $USER;
$modulePermissions = CMain::GetGroupRight("ammina.smtp");
if ($modulePermissions < "W") {
	$APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

if (CAmminaSmtp::getTestPeriodInfo() === \Bitrix\Main\Loader::MODULE_DEMO_EXPIRED) {
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
	CAdminMessage::ShowMessage(array("MESSAGE" => Loc::getMessage("AMMINA_SMTP_SYS_MODULE_IS_DEMO_EXPIRED"), "HTML" => true));
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
	die();
}

$sTableID = "tbl_ammina_smtp_stat_domains";
global $by, $order;
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$arOrder = (amsmtp_strtoupper($by) === "ID" ? array($by => $order) : array($by => $order, "ID" => "ASC"));
$lAdmin = new CAdminUiList($sTableID, $oSort);

$timeType = $_REQUEST['type'];
if (!in_array($timeType, array("d", "h", "m"))) {
	$timeType = "d";
}

$arDomainsList = array();
$rDomains = \Ammina\SMTP\DomainsTable::getList(array(
	"order" => array("DOMAIN" => "ASC"),
	"select" => array("ID", "DOMAIN")
));
while ($ar = $rDomains->fetch()) {
	$arDomainsList[$ar['ID']] = '[' . $ar['ID'] . '] ' . $ar['DOMAIN'];
}

$filterFields = array(
	array(
		"id" => "DOMAIN_ID",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_DOMAIN_ID"),
		"filterable" => "",
		"type" => "list",
		"items" => $arDomainsList,
		"default" => true,
		"params" => array("multiple" => "Y"),
	),
	array(
		"id" => "SEND_DATE",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_SEND_DATE"),
		"filterable" => "",
		"type" => "date",
		"default" => true
	),
	array(
		"id" => "CNT_SEND",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_CNT_SEND"),
		"filterable" => "",
		"type" => "number",
		"default" => false
	),
	array(
		"id" => "CNT_ERROR",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_CNT_ERROR"),
		"filterable" => "",
		"type" => "number",
		"default" => false
	),
);

$arFilter = array();
$lAdmin->AddFilter($filterFields, $arFilter);
$runtime = array();
if ($timeType === 'm') {
	$runtime[] = new \Bitrix\Main\ORM\Fields\ExpressionField('IS_MINUTE', "IF(%s IS NOT NULL AND %s IS NOT NULL,'Y','N')", array("MINUTE", "HOUR"));
	$arFilter['IS_MINUTE'] = "Y";
} elseif ($timeType === 'h') {
	$runtime[] = new \Bitrix\Main\ORM\Fields\ExpressionField('IS_HOUR', "IF(%s IS NULL AND %s IS NOT NULL,'Y','N')", array("MINUTE", "HOUR"));
	$arFilter['IS_HOUR'] = "Y";
} else {
	$runtime[] = new \Bitrix\Main\ORM\Fields\ExpressionField('IS_DAY', "IF(%s IS NULL AND %s IS NULL,'Y','N')", array("MINUTE", "HOUR"));
	$arFilter['IS_DAY'] = "Y";
}

$arHeader = array(
	array(
		"id" => "ID",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_ID"),
		"sort" => "ID",
		"default" => true,
	),
	array(
		"id" => "DOMAIN_ID",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_DOMAIN_ID"),
		"sort" => "DOMAIN_ID",
		"default" => true,
	),
	array(
		"id" => "SEND_DATE",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_SEND_DATE"),
		"sort" => "SEND_DATE",
		"default" => true,
	),
	array(
		"id" => "HOUR",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_HOUR"),
		"sort" => "HOUR",
		"default" => true,
	),
	array(
		"id" => "MINUTE",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_MINUTE"),
		"sort" => "MINUTE",
		"default" => true,
	),
	array(
		"id" => "CNT_SEND",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_CNT_SEND"),
		"sort" => "CNT_SEND",
		"default" => true,
	),
	array(
		"id" => "CNT_ERROR",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_CNT_ERROR"),
		"sort" => "CNT_ERROR",
		"default" => true,
	),
);

$lAdmin->AddHeaders($arHeader);
$rsItems = \Ammina\SMTP\StatDomainsTable::getList(array(
	"order" => $arOrder,
	"filter" => $arFilter,
	"runtime" => $runtime,
	"select" => array("*", "DOMAIN_AREA_" => "DOMAIN")
));
$rsItems = new CAdminUiResult($rsItems, $sTableID);
$rsItems->NavStart();

$lAdmin->SetNavigationParams($rsItems);

while ($arData = $rsItems->NavNext(true, "f_")) {
	$row =& $lAdmin->AddRow($arData['ID'], $arData);
	$row->AddViewField("DOMAIN_ID", '[<a href="/bitrix/admin/ammina.smtp.domains.edit.php?ID=' . $arData['DOMAIN_ID'] . '">' . $arData['DOMAIN_ID'] . '</a>] ' . $arData['DOMAIN_AREA_DOMAIN']);
}

$lAdmin->AddFooter(
	array(
		array("title" => Loc::getMessage("MAIN_ADMIN_LIST_SELECTED"), "value" => $rsItems->SelectedRowsCount()),
		array("counter" => true, "title" => Loc::getMessage("MAIN_ADMIN_LIST_CHECKED"), "value" => "0"),
	)
);

$lAdmin->CheckListMode();

$APPLICATION->SetTitle(Loc::getMessage("AMMINA_SMTP_PAGE_TITLE", array("#TYPE#" => Loc::getMessage("AMMINA_SMTP_PAGE_TITLE_" . strtoupper($timeType)))));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$lAdmin->DisplayFilter($filterFields);

if (CAmminaSmtp::getTestPeriodInfo() === \Bitrix\Main\Loader::MODULE_DEMO) {
	CAdminMessage::ShowMessage(array("MESSAGE" => Loc::getMessage("AMMINA_SMTP_SYS_MODULE_IS_DEMO"), "HTML" => true));
} elseif (CAmminaSmtp::getTestPeriodInfo() === \Bitrix\Main\Loader::MODULE_DEMO_EXPIRED) {
	CAdminMessage::ShowMessage(array("MESSAGE" => Loc::getMessage("AMMINA_SMTP_SYS_MODULE_IS_DEMO_EXPIRED"), "HTML" => true));
} elseif (COption::GetOptionString("ammina.smtp", "module_status", '') === 'ended') {
	CAdminMessage::ShowMessage(array("MESSAGE" => Loc::getMessage("AMMINA_SMTP_SYS_MODULE_RENEWAL", [
		'#LINK#' => COption::GetOptionString("ammina.smtp", "module_renewal", ''),
		'#DATE_END#' => COption::GetOptionString("ammina.smtp", "module_active_to", '')
	]), "HTML" => true));
} elseif (COption::GetOptionString("ammina.smtp", "module_status", '') === 'timeout') {
	CAdminMessage::ShowMessage(array("MESSAGE" => Loc::getMessage("AMMINA_SMTP_SYS_MODULE_RENEWAL_END", [
		'#LINK#' => COption::GetOptionString("ammina.smtp", "module_renewal", ''),
		'#DATE_END#' => COption::GetOptionString("ammina.smtp", "module_active_to", '')
	]), "HTML" => true));
}

$lAdmin->DisplayList();
CAmminaSmtp::doShowNoteActivateModule();
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");