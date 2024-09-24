<?

use Bitrix\Main\Localization\Loc;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Loader::includeModule('ammina.smtp');
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/ammina.smtp/prolog.php");
global $APPLICATION, $USER;

Loc::loadMessages(__FILE__);

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

$sTableID = "tbl_ammina_smtp_accounts";

global $by, $order;
$oSort = new CAdminSorting($sTableID, "EMAIL", "asc");
$arOrder = (amsmtp_strtoupper($by) === "ID" ? array($by => $order) : array($by => $order, "ID" => "ASC"));
$lAdmin = new CAdminUiList($sTableID, $oSort);

$queryObject = CLang::getList($b = "sort", $o = "asc", array("VISIBLE" => "Y"));
$listLang = array();
while ($lang = $queryObject->getNext()) {
	$listLang[$lang["LID"]] = $lang["NAME"];
}

$filterFields = array(
	array(
		"id" => "EMAIL",
		"name" => Loc::getMessage("AMMINA_SMTP_FILTER_EMAIL"),
		"filterable" => "?",
		"quickSearch" => "?",
		"default" => true,
	),
);
global $DB;
$arFilter = array();
$lAdmin->AddFilter($filterFields, $arFilter);
if ($lAdmin->EditAction()) {
	foreach ($_REQUEST['FIELDS'] as $ID => $postFields) {
		$DB->StartTransaction();
		$ID = IntVal($ID);

		if (!$lAdmin->IsUpdated($ID)) {
			continue;
		}

		$allowedFields = array(
			"IS_DEFAULT_DOMAIN",
			"ACTIVE",
			"IS_DEFAULT",
			"IS_IMPORT"
		);
		$arFields = array();
		foreach ($allowedFields as $fieldId) {
			if (array_key_exists($fieldId, $postFields)) {
				$arFields[$fieldId] = $postFields[$fieldId];
			}
		}

		$oUpdate = \Ammina\SMTP\AccountsTable::update($ID, $arFields);
		if (!$oUpdate->isSuccess()) {
			$lAdmin->AddUpdateError(GetMessage("AMMINA_SMTP_UPDATE_ERROR", array("#ID#" => $ID, "#ERROR_TEXT#" => implode(", ", $oUpdate->getErrorMessages()))), $ID);
			$DB->Rollback();
		}
		$DB->Commit();
	}
}
if (($arID = $lAdmin->GroupAction()) && $modulePermissions >= "W") {
	if ($_REQUEST['action_target'] === 'selected') {
		$arID = array();
		$dbResultList = \Ammina\SMTP\AccountsTable::getList(array(
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
				$rAccount = \Ammina\SMTP\AccountsTable::getList(array(
					"filter" => array("ID" => $ID),
					"select" => array("ID"),
				));
				$arAccountOld = $rAccount->Fetch();
				$DB->StartTransaction();
				$rOperation = \Ammina\SMTP\AccountsTable::delete($ID);
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
			case "activate":
			case "deactivate":
				$arFields = array("ACTIVE" => ($_REQUEST['action'] === "activate" ? "Y" : "N"));
				$oResult = \Ammina\SMTP\AccountsTable::update($ID, $arFields);
				if (!$oResult->isSuccess()) {
					$lAdmin->AddGroupError(GetMessage("AMMINA_SMTP_UPDATE_ERROR") . implode(", ", $oResult->getErrorMessages()), $ID);
				}
				break;
		}
	}
}

$arHeader = array(
	array(
		"id" => "DOMAIN",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_DOMAIN"),
		"sort" => "DOMAIN_AREA_DOMAIN",
		"default" => true,
	),
	array(
		"id" => "IS_DEFAULT_DOMAIN",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_IS_DEFAULT_DOMAIN"),
		"sort" => "IS_DEFAULT_DOMAIN",
		"default" => true,
	),
	array(
		"id" => "ACTIVE",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_ACTIVE"),
		"sort" => "ACTIVE",
		"default" => true,
	),
	array(
		"id" => "IS_DEFAULT",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_IS_DEFAULT"),
		"sort" => "IS_DEFAULT",
		"default" => true,
	),
	array(
		"id" => "SMTP_HOST",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_SMTP_HOST"),
		"sort" => "SMTP_HOST",
		"default" => true,
	),
	array(
		"id" => "SMTP_PORT",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_SMTP_PORT"),
		"sort" => "SMTP_PORT",
		"default" => true,
	),
	array(
		"id" => "SECURE_TYPE",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_SECURE_TYPE"),
		"sort" => "SECURE_TYPE",
		"default" => true,
	),
	array(
		"id" => "EMAIL",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_EMAIL"),
		"sort" => "EMAIL",
		"default" => true,
	),
	array(
		"id" => "SENDER_NAME",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_SENDER_NAME"),
		"sort" => "SENDER_NAME",
		"default" => true,
	),
	array(
		"id" => "ACCOUNT_LOGIN",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_ACCOUNT_LOGIN"),
		"sort" => "ACCOUNT_LOGIN",
		"default" => true,
	),
	array(
		"id" => "IS_IMPORT",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_IS_IMPORT"),
		"sort" => "IS_IMPORT",
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
	array(
		"id" => "LIMIT_DOMAN_IGNORE",
		"content" => Loc::getMessage("AMMINA_SMTP_FIELD_LIMIT_DOMAN_IGNORE"),
		"sort" => "LIMIT_DOMAN_IGNORE",
		"default" => true,
	),
);

$lAdmin->AddHeaders($arHeader);

$rsItems = \Ammina\SMTP\AccountsTable::getList(array(
	"order" => $arOrder,
	"filter" => $arFilter,
	"select" => array("*", "DOMAIN_AREA_" => "DOMAIN")));
$rsItems = new CAdminUiResult($rsItems, $sTableID);
$rsItems->NavStart();

$lAdmin->SetNavigationParams($rsItems);

while ($dbrs = $rsItems->Fetch()) {
	$row =& $lAdmin->AddRow($dbrs['ID'], $dbrs, 'ammina.smtp.accounts.edit.php?ID=' . $dbrs['ID'] . '&lang=' . LANGUAGE_ID, Loc::getMessage("AMMINA_SMTP_RECORD_EDIT"));
	$row->AddViewField("DOMAIN", '[<a href="/bitrix/admin/ammina.smtp.domains.edit.php?ID=' . $dbrs['DOMAIN_ID'] . '">' . $dbrs['DOMAIN_ID'] . '</a>] ' . $dbrs['DOMAIN_AREA_DOMAIN']);
	$row->AddCheckField("IS_DEFAULT_DOMAIN");
	$row->AddCheckField("ACTIVE");
	$row->AddCheckField("IS_DEFAULT");
	$row->AddCheckField("IS_IMPORT");
	$row->AddCheckField("LIMIT_DOMAN_IGNORE");
	$arActions = array();

	if ($modulePermissions >= "W") {
		$arActions[] = array(
			"ICON" => "edit",
			"TEXT" => Loc::getMessage("MAIN_ADMIN_MENU_EDIT"),
			"DEFAULT" => true,
			"ACTION" => $lAdmin->ActionRedirect("ammina.smtp.accounts.edit.php?ID=" . $dbrs['ID'] . "&lang=" . LANGUAGE_ID),
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
			"LINK" => "ammina.smtp.accounts.edit.php?lang=" . LANGUAGE_ID,
			"TITLE" => Loc::getMessage("AMMINA_SMTP_TO_ADD_DOMAIN_TITLE"),
		),
	);

	$lAdmin->AddAdminContextMenu($aContext);

	$lAdmin->AddGroupActionTable(array(
		"edit" => true,
		"delete" => true,
		"activate" => GetMessage("MAIN_ADMIN_LIST_ACTIVATE"),
		"deactivate" => GetMessage("MAIN_ADMIN_LIST_DEACTIVATE"),
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