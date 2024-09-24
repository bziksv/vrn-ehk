<?

use Bitrix\Main\Localization\Loc,
	\Bitrix\Ammina\SMTP\Helpers\Admin\Blocks;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Loader::includeModule('ammina.smtp');
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/ammina.smtp/prolog.php");
global $APPLICATION;
Loc::loadMessages(__FILE__);
$ID = isset($_REQUEST["ID"]) ? intval($_REQUEST["ID"]) : 0;
global $USER, $APPLICATION;
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

$arCurrentItem = false;
if ($ID > 0) {
	$arCurrentItem = \Ammina\SMTP\QueueTable::getList(array(
		"filter" => array(
			"ID" => $ID
		),
		"select" => array("*", "ACCOUNT_AREA_" => "ACCOUNT")
	))->fetch();
	if (!$arCurrentItem) {
		$ID = false;
	}
}
if ($ID <= 0) {
	LocalRedirect("/bitrix/admin/ammina.smtp.queue.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_", false));
}
$result = new \Bitrix\Main\Entity\Result();

$APPLICATION->SetTitle(Loc::getMessage("AMMINA_SMTP_PAGE_TITLE_VIEW"));

CJSCore::Init();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

// context menu
$aMenu = array();
$aMenu[] = array(
	"ICON" => "btn_list",
	"TEXT" => Loc::getMessage("AMMINA_SMTP_TO_LIST"),
	"TITLE" => Loc::getMessage("AMMINA_SMTP_TO_LIST_TITLE"),
	"LINK" => "/bitrix/admin/ammina.smtp.queue.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_"),
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

$formId = "ammina_smtp_queue_view";

$aTabs = array(
	array("DIV" => "tab_ammina1", "TAB" => Loc::getMessage("AMMINA_SMTP_TAB_QUEUE_VIEW"), "SHOW_WRAP" => "N", "IS_DRAGGABLE" => "N", "TITLE" => Loc::getMessage("AMMINA_SMTP_TAB_QUEUE_VIEW_TITLE")),
	array("DIV" => "tab_ammina2", "TAB" => Loc::getMessage("AMMINA_SMTP_TAB_QUEUE_MAIL"), "SHOW_WRAP" => "N", "IS_DRAGGABLE" => "N", "TITLE" => Loc::getMessage("AMMINA_SMTP_TAB_QUEUE_MAIL_TITLE")),
	array("DIV" => "tab_ammina3", "TAB" => Loc::getMessage("AMMINA_SMTP_TAB_QUEUE_LOG"), "SHOW_WRAP" => "N", "IS_DRAGGABLE" => "N", "TITLE" => Loc::getMessage("AMMINA_SMTP_TAB_QUEUE_LOG_TITLE")),
);
$tabControl = new CAdminTabControl($formId, $aTabs, true, true);
?>
	<form method="POST" action="<?= $APPLICATION->GetCurPage() . "?lang=" . LANGUAGE_ID . GetFilterParams("filter_", false) ?>" name="<?= $formId ?>_form" id="<?= $formId ?>_form" enctype="multipart/form-data">
		<input type="hidden" name="ID" value="<?= $arCurrentItem['ID'] ?>"/>
		<?= bitrix_sessid_post() ?>
		<?
		$tabControl->Begin();
		$tabControl->BeginNextTab();
		?>
		<tr>
			<td class="adm-detail-content-cell-l fwb" width="40%"><?= Loc::getMessage("AMMINA_SMTP_FIELD_ID") ?>:</td>
			<td class="adm-detail-content-cell-r" width="60%"><?= $arCurrentItem['ID'] ?></td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?= Loc::getMessage("AMMINA_SMTP_FIELD_ACCOUNT_ID") ?>:</td>
			<td class="adm-detail-content-cell-r">[<a href="/bitrix/admin/ammina.smtp.accounts.edit.php?ID=<?= $arCurrentItem['ACCOUNT_ID'] ?>"><?= $arCurrentItem['ACCOUNT_ID'] ?></a>] <?= htmlspecialchars($arCurrentItem['ACCOUNT_AREA_EMAIL']) ?>
			</td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?= Loc::getMessage("AMMINA_SMTP_FIELD_DATE_INSERT") ?>:</td>
			<td class="adm-detail-content-cell-r"><?= htmlspecialchars($arCurrentItem['DATE_INSERT']) ?></td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?= Loc::getMessage("AMMINA_SMTP_FIELD_DATE_SEND") ?>:</td>
			<td class="adm-detail-content-cell-r"><?= htmlspecialchars($arCurrentItem['DATE_SEND']) ?></td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?= Loc::getMessage("AMMINA_SMTP_FIELD_FIELD_TO") ?>:</td>
			<td class="adm-detail-content-cell-r"><?= htmlspecialchars($arCurrentItem['FIELD_TO']) ?></td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?= Loc::getMessage("AMMINA_SMTP_FIELD_FIELD_SUBJECT") ?>:</td>
			<td class="adm-detail-content-cell-r"><?= htmlspecialchars($arCurrentItem['FIELD_SUBJECT']) ?></td>
		</tr>
		<?
		$tabControl->BeginNextTab();
		?>
		<tr class="heading">
			<td colspan="2"><?= Loc::getMessage("AMMINA_SMTP_FIELD_MAIL_DATA") ?></td>
		</tr>
		<?
		foreach ($arCurrentItem['MAIL_DATA'] as $k => $v) {
			?>
			<tr valign="top">
				<td class="adm-detail-content-cell-l"><?= $k ?>:</td>
				<td class="adm-detail-content-cell-r"><?= nl2br(htmlspecialchars($v)) ?></td>
			</tr>
			<?
		}
		$tabControl->BeginNextTab();
		?>
		<tr class="heading">
			<td colspan="2"><?= Loc::getMessage("AMMINA_SMTP_FIELD_LOG_DATA") ?></td>
		</tr>
		<tr>
			<td colspan="2"><?= nl2br(htmlspecialchars($arCurrentItem['LOG_DATA'])) ?></td>
		</tr>
		<?
		$tabControl->EndTab();

		$tabControl->Buttons(
			array(
				"btnSave" => false,
				"btnApply" => false,
				"btnCancel" => false,
				"back_url" => "/bitrix/admin/ammina.smtp.domains.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_"))
		);

		$tabControl->End();
		?>
	</form>

<?
if ($ID > 0) {
	echo BeginNote();
	$dnskey = $arCurrentItem['DKIM_SELECTOR'] . "._domainkey";
	$dnsvalue = 'v=DKIM1; h=sha256; k=rsa; t=s; p=';
	$publickey = preg_replace('/^-+.*?-+$/m', '', $arCurrentItem['DKIM_PUBLIC']);
	$publickey = str_replace(["\r", "\n"], '', $publickey);
	$keyparts = str_split($publickey, 253); //Becomes 255 when quotes are included
	foreach ($keyparts as $keypart) {
		$dnsvalue .= trim($keypart);
	}
	echo loc::getMessage("AMMINA_SMTP_DNS_RECORDS_NOTE", array(
		"#SUBDOMAIN#" => $dnskey,
		"#DNS_RECORD#" => $dnsvalue,
	));
	echo EndNote();
}
CAmminaSmtp::doShowNoteActivateModule();
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");