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

$sTableID = "tbl_ammina_smtp_domains";

global $by, $order;
$oSort = new CAdminSorting($sTableID, "DOMAIN", "asc");
$arOrder = (amsmtp_strtoupper($by) === "ID" ? array($by => $order) : array($by => $order, "ID" => "ASC"));
$lAdmin = new CAdminUiList($sTableID, $oSort);

$queryObject = CLang::getList($b = "sort", $o = "asc", array("VISIBLE" => "Y"));
$listLang = array();
while ($lang = $queryObject->getNext())
	$listLang[$lang["LID"]] = $lang["NAME"];

$filterFields = array(
	array(
		"id" => "DOMAIN",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_DOMAIN"),
		"filterable" => "?",
		"quickSearch" => "?",
		"default" => true,
	),
);

$arFilter = array();
$lAdmin->AddFilter($filterFields, $arFilter);

if (($arID = $lAdmin->GroupAction()) && $modulePermissions >= "W") {
	if ($_REQUEST['action_target'] === 'selected') {
		$arID = array();
		$dbResultList = \Ammina\SMTP\DomainsTable::getList(array(
			"order" => $arOrder,
			"filter" => $arFilter,
			"select" => array("ID")));
		while ($arResult = $dbResultList->Fetch()) {
			$arID[] = $arResult['ID'];
		}
	}

	foreach ($arID as $ID) {
		if (amsmtp_strlen($ID) <= 0) {
			continue;
		}

		switch ($_REQUEST['action']) {
			case "delete":
				@set_time_limit(0);
				$rDomain = \Ammina\SMTP\DomainsTable::getList(array(
					"filter" => array("ID" => $ID),
					"select" => array("ID"),
				));
				$arDomainOld = $rDomain->Fetch();
				$DB->StartTransaction();
				$bIsOk = true;
				$rAccounts = \Ammina\SMTP\AccountsTable::getList(array(
					"filter" => array("DOMAIN_ID" => $ID),
					"select" => array("ID"),
				));
				while ($arAccount = $rAccounts->fetch()) {
					$rOperation = \Ammina\SMTP\AccountsTable::delete($arAccount['ID']);
					if (!$rOperation->isSuccess()) {
						$bIsOk = false;
						$DB->Rollback();
						if ($ex = $APPLICATION->GetException()) {
							$lAdmin->AddGroupError($ex->GetString(), $ID);
						} else {
							$lAdmin->AddGroupError(Loc::getMessage("AMMINA_SMTP_DELETE_ERROR"), $ID);
						}
					}
				}
				if ($bIsOk) {
					$rOperation = \Ammina\SMTP\DomainsTable::delete($ID);
					if (!$rOperation->isSuccess()) {
						$DB->Rollback();
						if ($ex = $APPLICATION->GetException()) {
							$lAdmin->AddGroupError($ex->GetString(), $ID);
						} else {
							$lAdmin->AddGroupError(Loc::getMessage("AMMINA_SMTP_DELETE_ERROR"), $ID);
						}
					}
					$DB->Commit();
				}
				break;
		}
	}
}


$arHeader = array(
	array(
		"id" => "DOMAIN",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_DOMAIN"),
		"sort" => "DOMAIN",
		"default" => true,
	),
	array(
		"id" => "DKIM_PRIVATE",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_DKIM_PRIVATE"),
		"default" => false,
	),
	array(
		"id" => "DKIM_PUBLIC",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_DKIM_PUBLIC"),
		"default" => true,
	),
	array(
		"id" => "DKIM_SELECTOR",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_DKIM_SELECTOR"),
		"sort" => "DKIM_SELECTOR",
		"default" => true,
	),
	array(
		"id" => "DKIM_PASSPHRASE",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_DKIM_PASSPHRASE"),
		"sort" => "DKIM_PASSPHRASE",
		"default" => true,
	),
	array(
		"id" => "LIMIT_DAY",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_LIMIT_DAY"),
		"sort" => "LIMIT_DAY",
		"default" => true,
	),
	array(
		"id" => "LIMIT_HOUR",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_LIMIT_HOUR"),
		"sort" => "LIMIT_HOUR",
		"default" => true,
	),
	array(
		"id" => "LIMIT_MINUTE",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_LIMIT_MINUTE"),
		"sort" => "LIMIT_MINUTE",
		"default" => true,
	),
);

$lAdmin->AddHeaders($arHeader);

$rsItems = \Ammina\SMTP\DomainsTable::getList(array(
	"order" => $arOrder,
	"filter" => $arFilter));
$rsItems = new CAdminUiResult($rsItems, $sTableID);
$rsItems->NavStart();

$lAdmin->SetNavigationParams($rsItems);

while ($dbrs = $rsItems->Fetch()) {
	$row =& $lAdmin->AddRow($dbrs['ID'], $dbrs, 'ammina.smtp.domains.edit.php?ID=' . $dbrs['ID'] . '&lang=' . LANGUAGE_ID, Loc::getMessage("AMMINA_SMTP_RECORD_EDIT"));
	$arActions = array();

	if ($modulePermissions >= "W") {
		$arActions[] = array(
			"ICON" => "edit",
			"TEXT" => Loc::getMessage("MAIN_ADMIN_MENU_EDIT"),
			"DEFAULT" => true,
			"ACTION" => $lAdmin->ActionRedirect("ammina.smtp.domains.edit.php?ID=" . $dbrs['ID'] . "&lang=" . LANGUAGE_ID),
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
	$aContext = array(
		array(
			"ICON" => "btn_new",
			"TEXT" => Loc::getMessage("AMMINA_SMTP_TO_ADD_DOMAIN"),
			"LINK" => "ammina.smtp.domains.edit.php?lang=" . LANGUAGE_ID,
			"TITLE" => Loc::getMessage("AMMINA_SMTP_TO_ADD_DOMAIN_TITLE"),
		),
	);

	$lAdmin->AddAdminContextMenu($aContext);

	$lAdmin->AddGroupActionTable(array());
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