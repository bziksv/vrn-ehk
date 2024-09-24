<?

use Ammina\SMTP\AccountsTable;
use Bitrix\Main\Localization\Loc,
	\Bitrix\Ammina\SMTP\Helpers\Admin\Blocks;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Loader::includeModule('ammina.smtp');
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/ammina.smtp/prolog.php");
include($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/ammina.smtp/install/events/set_events.php");
global $APPLICATION, $USER;
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
$arCurrentItem = array();
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

$result = new \Bitrix\Main\Entity\Result();

$customTabber = new CAdminTabEngine("OnAdminAmminaSmtpAccountsEdit");
$customDraggableBlocks = new CAdminDraggableBlockEngine('OnAdminAmminaSmtpAccountsEditDraggable');

//errors
$errorMessage = "";

if ($isSavingOperation) {
	$errorMessage = '';
	$arCurrentItem = $_POST['FIELDS'];

	if ($arCurrentItem['ACCOUNT'] > 0) {
		$arAccount = AccountsTable::getList(
			array(
				"filter" => array("ID" => $arCurrentItem['ACCOUNT'])
			)
		)->fetch();
	}
	if (!$arAccount) {
		$arAccount = AccountsTable::getList(
			array(
				"filter" => array("IS_DEFAULT" => 'Y')
			)
		)->fetch();
	}
	if (!$arAccount) {
		$arAccount = AccountsTable::getList(
			array(
				"order" => array("ID" => 'ASC')
			)
		)->fetch();
	}
	if (!$arAccount) {
		$arAccount['EMAIL'] = COption::GetOptionString("main", "email_from", "");
	}
	$arEvent = array(
		"FROM_EMAIL" => $arAccount['EMAIL'],
		"EMAIL" => $arCurrentItem['TO']
	);
	$arLid = array();
	global $AMMINA_SMTP_ACTIVATE;
	$AMMINA_SMTP_ACTIVATE = true;
	$b = 'ID';
	$o = 'DESC';
	$arMessage = CEventMessage::GetList($b, $o, array("TYPE_ID" => "AMMINA_SMTP_TEST"))->Fetch();
	$strResult = CEvent::SendImmediate("AMMINA_SMTP_TEST", $arMessage['LID'], $arEvent);
	if ($strResult === "F") {
		$errorMessage = array("MESSAGE" => Loc::getMessage("AMMINA_SMTP_TEST_STATUS_ERROR"), "TYPE" => "ERROR", "HTML" => true);
	} elseif ($strResult === "Y") {
		$errorMessage = array("MESSAGE" => Loc::getMessage("AMMINA_SMTP_TEST_STATUS_OK"), "TYPE" => "OK", "HTML" => true);
	}
}

$APPLICATION->SetTitle(Loc::getMessage("AMMINA_SMTP_PAGE_TITLE_TEST"));

CJSCore::Init();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
// context menu
$aMenu = array();


$context = new CAdminContextMenu($aMenu);
$context->Show();

if (defined("AMMINA_SMTP_CUSTOM_MAIL_EXISTS") && AMMINA_SMTP_CUSTOM_MAIL_EXISTS === true) {
	if (defined("AMMINA_SMTP_CUSTOM_MAIL_EXISTS_FILE")) {
		$errorMessageCustomMail = Loc::getMessage("AMMINA_SMTP_TEST_CUSTOM_MAIL_EXISTS_FILE", array("#FILE#" => AMMINA_SMTP_CUSTOM_MAIL_EXISTS_FILE));
	} else {
		$errorMessageCustomMail = Loc::getMessage("AMMINA_SMTP_TEST_CUSTOM_MAIL_EXISTS");
	}
	$admMessage = new CAdminMessage($errorMessageCustomMail);
	echo $admMessage->Show();
}

if (!$result->isSuccess()) {
	foreach ($result->getErrors() as $error) {
		$errorMessage .= $error->getMessage() . "<br>\n";
	}
}

if (!empty($errorMessage)) {
	$admMessage = new CAdminMessage($errorMessage);
	echo $admMessage->Show();
}

//prepare blocks order
$defaultBlocksPage = array(
	"test",
);

$formId = "ammina_smtp_test";

$aTabs = array(
	array("DIV" => "tab_ammina", "TAB" => Loc::getMessage("AMMINA_SMTP_TAB_TEST"), "SHOW_WRAP" => "N", "IS_DRAGGABLE" => "Y"),
);
$moduleId='ammina.smtp';
?>
<form method="POST" action="<?= $APPLICATION->GetCurPage() . "?lang=" . LANGUAGE_ID . GetFilterParams("filter_", false) ?>" name="<?= $formId ?>_form" id="<?= $formId ?>_form" enctype="multipart/form-data">
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
						case "test":
							echo Blocks\Test::getEdit($arCurrentItem);
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
		"back_url" => "/bitrix/admin/ammina.smtp.test.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_"),
		"btnApply" => false,
		"btnCancel" => false
	)
);

$tabControl->End();
CAmminaSmtp::doShowNoteActivateModule();
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");