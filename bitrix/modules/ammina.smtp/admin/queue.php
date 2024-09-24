<?

use Bitrix\Main\Localization\Loc;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Loader::includeModule('ammina.smtp');
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/ammina.smtp/prolog.php");

Loc::loadMessages(__FILE__);
global $APPLICATION, $USER, $DB;

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

$sTableID = "tbl_ammina_smtp_queue";

global $by, $order;
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$arOrder = (amsmtp_strtoupper($by) === "ID" ? array($by => $order) : array($by => $order, "ID" => "ASC"));
$lAdmin = new CAdminUiList($sTableID, $oSort);

$arAccountsList = array();
$rAccounts = \Ammina\SMTP\AccountsTable::getList(array(
	"order" => array("EMAIL" => "ASC"),
	"select" => array("ID", "EMAIL")
));
while ($ar = $rAccounts->fetch()) {
	$arAccountsList[$ar['ID']] = '[' . $ar['ID'] . '] ' . $ar['EMAIL'];
}

$filterFields = array(
	array(
		"id" => "ID",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_ID"),
		"type" => "number",
		"filterable" => "",
	),
	array(
		"id" => "ACCOUNT_ID",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_ACCOUNT_ID"),
		"filterable" => "",
		"type" => "list",
		"items" => $arAccountsList,
		"default" => true,
		"params" => array("multiple" => "Y"),
	),
	array(
		"id" => "STATUS",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_STATUS"),
		"filterable" => "",
		"type" => "list",
		"items" => array(
			"N" => Loc::getMessage("AMMINA_SMTP_FIELD_STATUS_N"),
			"E" => Loc::getMessage("AMMINA_SMTP_FIELD_STATUS_E"),
			"S" => Loc::getMessage("AMMINA_SMTP_FIELD_STATUS_S"),
		),
		"default" => true,
		"params" => array("multiple" => "Y"),
	),
	array(
		"id" => "DATE_INSERT",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_DATE_INSERT"),
		"filterable" => "",
		"type" => "date",
		"default" => false,
	),
	array(
		"id" => "DATE_SEND",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_DATE_SEND"),
		"filterable" => "",
		"type" => "date",
		"default" => false,
	),
	array(
		"id" => "FIELD_TO",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_FIELD_TO"),
		"filterable" => "",
		"type" => "string",
		"default" => false,
	),
	array(
		"id" => "FIELD_SUBJECT",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_FIELD_SUBJECT"),
		"filterable" => "",
		"type" => "string",
		"default" => false,
	),
);

$arFilter = array();
$lAdmin->AddFilter($filterFields, $arFilter);
global $DB;
if (($arID = $lAdmin->GroupAction()) && $modulePermissions >= "W") {
	if ($_REQUEST['action_target'] === 'selected') {
		$arID = array();
		$dbResultList = \Ammina\SMTP\QueueTable::getList(array(
			"order" => $arOrder,
			"filter" => $arFilter,
			"select" => array("ID")));
		while ($arResult = $dbResultList->Fetch()) {
			$arID[] = $arResult['ID'];
		}
	}

	foreach ($arID as $ID) {
		if (strlen($ID) <= 0) {
			continue;
		}

		switch ($_REQUEST['action']) {
			case "delete":
				@set_time_limit(0);
				$rQueue = \Ammina\SMTP\QueueTable::getList(array(
					"filter" => array("ID" => $ID),
					"select" => array("ID"),
				));
				$arQueueOld = $rQueue->fetch();
				$DB->StartTransaction();
				$rOperation = \Ammina\SMTP\QueueTable::delete($ID);
				if (!$rOperation->isSuccess()) {
					$DB->Rollback();
					if ($ex = $APPLICATION->GetException()) {
						$lAdmin->AddGroupError($ex->GetString(), $ID);
					} else {
						$lAdmin->AddGroupError(Loc::getMessage("AMMINA_SMTP_DELETE_ERROR"), $ID);
					}
				}
				$DB->Commit();
				break;
			case "resend":
				@set_time_limit(0);
				$rQueue = \Ammina\SMTP\QueueTable::getList(array(
					"filter" => array("ID" => $ID),
					"select" => array("ID", "STATUS"),
				));
				$arQueueOld = $rQueue->fetch();
				if ($arQueueOld['STATUS'] === "E") {
					$arFields = array("STATUS" => "N");
					$oResult = \Ammina\SMTP\QueueTable::update($ID, $arFields);
					if (!$oResult->isSuccess()) {
						$lAdmin->AddGroupError(GetMessage("AMMINA_SMTP_RESEND_ERROR") . implode(", ", $oResult->getErrorMessages()), $ID);
					}
				}
				break;
		}
	}
}

$arHeader = array(
	array(
		"id" => "ID",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_ID"),
		"sort" => "ID",
		"default" => true,
	),
	array(
		"id" => "ACCOUNT_ID",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_ACCOUNT_ID"),
		"sort" => "ACCOUNT_ID",
		"default" => true,
	),
	array(
		"id" => "STATUS",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_STATUS"),
		"sort" => "STATUS",
		"default" => true,
	),
	array(
		"id" => "DATE_INSERT",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_DATE_INSERT"),
		"sort" => "DATE_INSERT",
		"default" => true,
	),
	array(
		"id" => "DATE_SEND",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_DATE_SEND"),
		"sort" => "DATE_SEND",
		"default" => true,
	),
	array(
		"id" => "FIELD_TO",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_FIELD_TO"),
		"sort" => "FIELD_TO",
		"default" => true,
	),
	array(
		"id" => "FIELD_SUBJECT",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_FIELD_SUBJECT"),
		"sort" => "FIELD_SUBJECT",
		"default" => true,
	),
);

$lAdmin->AddHeaders($arHeader);

$rsItems = \Ammina\SMTP\QueueTable::getList(array(
	"order" => $arOrder,
	"filter" => $arFilter,
	"select" => array("ID", "ACCOUNT_ID", "STATUS", "DATE_INSERT", "DATE_SEND", "FIELD_TO", "FIELD_SUBJECT", "ACCOUNT_AREA_" => "ACCOUNT")
));
$rsItems = new CAdminUiResult($rsItems, $sTableID);
$rsItems->NavStart();

$lAdmin->SetNavigationParams($rsItems);

while ($dbrs = $rsItems->Fetch()) {
	$row =& $lAdmin->AddRow($dbrs['ID'], $dbrs, 'ammina.smtp.queue.view.php?ID=' . $dbrs['ID'] . '&lang=' . LANGUAGE_ID, Loc::getMessage("AMMINA_SMTP_RECORD_VIEW"));
	$row->AddViewField("ACCOUNT_ID", '[<a href="/bitrix/admin/ammina.smtp.accounts.edit.php?ID=' . $dbrs['ACCOUNT_ID'] . '">' . $dbrs['ACCOUNT_ID'] . '</a>] ' . $dbrs['ACCOUNT_AREA_EMAIL']);
	$row->AddViewField('STATUS', Loc::getMessage('AMMINA_SMTP_FIELD_STATUS_' . $dbrs['STATUS']));

	$arActions = array();

	if ($modulePermissions >= "W") {
		if ($dbrs['STATUS'] === "E") {
			$arActions[] = array(
				"ICON" => "resend",
				"TEXT" => GetMessage("AMMINA_SMTP_ACTION_RESEND"),
				"ACTION" => $lAdmin->ActionDoGroup($dbrs['ID'], "resend"),
			);
		}
		$arActions[] = array(
			"ICON" => "view",
			"TEXT" => Loc::getMessage("AMMINA_SMTP_ACTION_VIEW"),
			"DEFAULT" => true,
			"ACTION" => $lAdmin->ActionRedirect("ammina.smtp.queue.view.php?ID=" . $dbrs['ID'] . "&lang=" . LANGUAGE_ID),
		);
		$arActions[] = array(
			"SEPARATOR" => true,
		);
		$arActions[] = array(
			"ICON" => "delete",
			"TEXT" => GetMessage("AMMINA_SMTP_ACTION_DELETE"),
			"ACTION" => "if(confirm('" . GetMessage('AMMINA_SMTP_ACTION_DELETE_CONFIRM') . "')) " . $lAdmin->ActionDoGroup($dbrs['ID'], "delete"),
		);
	}

	if (count($arActions) > 0) {
		$row->AddActions($arActions);
	}
}

$lAdmin->AddFooter(
	array(
		array("title" => Loc::getMessage("MAIN_ADMIN_LIST_SELECTED"), "value" => $rsItems->SelectedRowsCount()),
		array("counter" => true, "title" => Loc::getMessage("MAIN_ADMIN_LIST_CHECKED"), "value" => "0"),
	)
);

if ($modulePermissions >= "W") {
	$aContext = array();

	$lAdmin->AddAdminContextMenu($aContext);

	$lAdmin->AddGroupActionTable(array(
		"delete" => true,
		"resend" => GetMessage("AMMINA_SMTP_ACTION_RESEND"),
	));
}

$lAdmin->CheckListMode();

$APPLICATION->SetTitle(Loc::getMessage("AMMINA_SMTP_PAGE_TITLE"));

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