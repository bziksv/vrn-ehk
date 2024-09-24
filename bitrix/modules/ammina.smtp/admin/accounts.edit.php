<?

use Bitrix\Main\Localization\Loc,
	\Bitrix\Ammina\SMTP\Helpers\Admin\Blocks;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Loader::includeModule('ammina.smtp');
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/ammina.smtp/prolog.php");
global $USER, $APPLICATION;
Loc::loadMessages(__FILE__);
$ID = isset($_REQUEST["ID"]) ? intval($_REQUEST["ID"]) : 0;

$isSavingOperation = (
	$_SERVER["REQUEST_METHOD"] === "POST"
	&& (
		isset($_POST["apply"])
		|| isset($_POST["save"])
	)
	&& check_bitrix_sessid()
);

$arUserGroups = $USER->GetUserGroupArray();
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

$needFieldsRestore = $_SERVER["REQUEST_METHOD"] === "POST" && !$isSavingOperation;
$isNewItem = ($ID <= 0);
$arCurrentItem = false;
if ($ID > 0) {
	$arCurrentItem = \Ammina\SMTP\AccountsTable::getById($ID)->fetch();
	if (!$arCurrentItem) {
		$isNewItem = false;
		$ID = false;
	}
}

$result = new \Bitrix\Main\Entity\Result();

$customTabber = new CAdminTabEngine("OnAdminAmminaSmtpAccountsEdit");
$customDraggableBlocks = new CAdminDraggableBlockEngine('OnAdminAmminaSmtpAccountsEditDraggable');


if ($isSavingOperation) {

	$errorMessage = '';
	if (!$customTabber->Check()) {
		if ($ex = $APPLICATION->GetException())
			$errorMessage .= $ex->GetString();
		else
			$errorMessage .= "Custom tabber check unknown error!";

		$result->addError(new \Bitrix\Main\Entity\EntityError($errorMessage));
	}

	if (!$customDraggableBlocks->check()) {
		if ($ex = $APPLICATION->GetException())
			$errorMessage .= $ex->GetString();
		else
			$errorMessage .= "Custom draggable block check unknown error!";

		$result->addError(new \Bitrix\Main\Entity\EntityError($errorMessage));
	}
	$_POST['FIELDS']['ACCOUNT_PASSWORD'] = trim($_POST['FIELDS']['ACCOUNT_PASSWORD']);
	$_POST['FIELDS']['ACCOUNT_LOGIN'] = trim($_POST['FIELDS']['ACCOUNT_LOGIN']);
	if (amsmtp_strlen($_POST['FIELDS']['ACCOUNT_PASSWORD']) <= 0 || $_POST['FIELDS']['ACCOUNT_PASSWORD'] === "*****") {
		unset($_POST['FIELDS']['ACCOUNT_PASSWORD']);
	}
	if ($isNewItem) {
		$oTableResult = \Ammina\SMTP\AccountsTable::add($_POST['FIELDS']);
		$ID = $oTableResult->getId();
	} else {
		$oTableResult = \Ammina\SMTP\AccountsTable::update($ID, $_POST['FIELDS']);
	}
	if (!$oTableResult->isSuccess()) {
		$result->addErrors($oTableResult->getErrors());
	}
	if ($result->isSuccess()) {
		if (isset($_POST["save"])) {
			LocalRedirect("/bitrix/admin/ammina.smtp.accounts.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_", false));
		} else {
			LocalRedirect("/bitrix/admin/ammina.smtp.accounts.edit.php?lang=" . LANGUAGE_ID . "&ID=" . $ID . GetFilterParams("filter_", false));
		}
	}
}

if ($ID > 0) {
	$APPLICATION->SetTitle(Loc::getMessage("AMMINA_SMTP_PAGE_TITLE_EDIT"));
} else {
	$APPLICATION->SetTitle(Loc::getMessage("AMMINA_SMTP_PAGE_TITLE_ADD"));
}

\CJSCore::Init();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
/*
Blocks\OrderBasket::getCatalogMeasures();
*/
// context menu
$aMenu = array();
$aMenu[] = array(
	"ICON" => "btn_list",
	"TEXT" => Loc::getMessage("AMMINA_SMTP_TO_LIST"),
	"TITLE" => Loc::getMessage("AMMINA_SMTP_TO_LIST_TITLE"),
	"LINK" => "/bitrix/admin/ammina.smtp.accounts.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_"),
);


$context = new CAdminContextMenu($aMenu);
$context->Show();

//errors
$errorMessage = "";

if (!$result->isSuccess())
	foreach ($result->getErrors() as $error) {
		$errorMessage .= $error->getMessage() . "<br>\n";
	}

if (!empty($errorMessage)) {
	$admMessage = new CAdminMessage($errorMessage);
	echo $admMessage->Show();
}

//prepare blocks order
$defaultBlocksPage = array(
	"account",
	"connect",
	"account_limit",
);

$formId = "ammina_smtp_accounts_edit";

$aTabs = array(
	array("DIV" => "tab_ammina", "TAB" => Loc::getMessage("AMMINA_SMTP_TAB_ACCOUNT"), "SHOW_WRAP" => "N", "IS_DRAGGABLE" => "Y"),
);
$moduleId = 'ammina.smtp';
?>
<form method="POST" action="<?= $APPLICATION->GetCurPage() . "?lang=" . LANGUAGE_ID . GetFilterParams("filter_", false) ?>" name="<?= $formId ?>_form" id="<?= $formId ?>_form" enctype="multipart/form-data">
	<input type="hidden" name="ID" value="<?= $arCurrentItem['ID'] ?>"/>
	<?
	$tabControl = new CAdminTabControlDrag($formId, $aTabs, $moduleId, false, true);
	$tabControl->AddTabs($customTabber);
	$tabControl->Begin();

	$tabControl->BeginNextTab();
	$customFastNavItems = array();
	$customBlocksPage = array();
	$fastNavItems = array();

	foreach ($customDraggableBlocks->getBlocksBrief() as $blockId => $blockParams) {
		$defaultBlocksPage[] = $blockId;
		$customFastNavItems[$blockId] = $blockParams['TITLE'];
		$customBlocksPage[] = $blockId;
	}

	$blocksPage = $tabControl->getCurrentTabBlocksOrder($defaultBlocksPage);
	$customNewBlockIds = array_diff($customBlocksPage, $blocksPage);
	$blocksPage = array_merge($blocksPage, $customNewBlockIds);

	foreach ($blocksPage as $item) {
		if (isset($customFastNavItems[$item])) {
			$fastNavItems[$item] = $customFastNavItems[$item];
		} else {
			$fastNavItems[$item] = Loc::getMessage("AMMINA_SMTP_BLOCK_TITLE_" . amsmtp_strtoupper($item));
		}
	}

	?>
	<tr>
		<td>
			<?= bitrix_sessid_post() ?>
			<div style="position: relative; vertical-align: top">
				<? $tabControl->DraggableBlocksStart(); ?>
				<?
				foreach ($blocksPage as $blockCode) {
					echo '<a id="' . $blockCode . '" class="adm-ammina-smtp-fastnav-anchor"></a>';
					$tabControl->DraggableBlockBegin($fastNavItems[$blockCode], $blockCode);
					switch ($blockCode) {
						case "account":
							echo Blocks\Account::getEdit($arCurrentItem);
							break;
						case "connect":
							echo Blocks\Connect::getEdit($arCurrentItem);
							break;
						case "account_limit":
							echo Blocks\AccountLimit::getEdit($arCurrentItem);
							break;
						default:
							echo $customDraggableBlocks->getBlockContent($blockCode, $tabControl->selectedTab);
							break;
					}
					$tabControl->DraggableBlockEnd();
				}
				?>
			</div>
		</td>
	</tr>
<?

$tabControl->EndTab();

$tabControl->Buttons(
	array(
		"back_url" => "/bitrix/admin/ammina.smtp.accounts.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_"))
);

$tabControl->End();
CAmminaSmtp::doShowNoteActivateModule();
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");