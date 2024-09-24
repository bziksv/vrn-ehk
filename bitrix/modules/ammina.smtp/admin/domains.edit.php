<?

use Bitrix\Main\Localization\Loc,
	\Bitrix\Ammina\SMTP\Helpers\Admin\Blocks;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Loader::includeModule('ammina.smtp');
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/ammina.smtp/prolog.php");
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
	$arCurrentItem = \Ammina\SMTP\DomainsTable::getById($ID)->fetch();
	if (!$arCurrentItem) {
		$isNewItem = false;
		$ID = false;
	}
}

$result = new \Bitrix\Main\Entity\Result();

$customTabber = new CAdminTabEngine("OnAdminAmminaSmtpDomainsEdit");
$customDraggableBlocks = new CAdminDraggableBlockEngine('OnAdminAmminaSmtpDomainsEditDraggable');


if ($isSavingOperation) {

	$errorMessage = '';
	if (!$customTabber->Check()) {
		if ($ex = $APPLICATION->GetException()) {
			$errorMessage .= $ex->GetString();
		} else {
			$errorMessage .= "Custom tabber check unknown error!";
		}

		$result->addError(new \Bitrix\Main\Entity\EntityError($errorMessage));
	}

	if (!$customDraggableBlocks->check()) {
		if ($ex = $APPLICATION->GetException()) {
			$errorMessage .= $ex->GetString();
		} else {
			$errorMessage .= "Custom draggable block check unknown error!";
		}

		$result->addError(new \Bitrix\Main\Entity\EntityError($errorMessage));
	}

	if ($_POST['FIELDS']['DKIM_REGENERATE'] === "Y") {
		CheckDirPath($_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/smtp/keys/");
		if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/.htaccess")) {
			file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/.htaccess", 'Deny from All');
		}
		$strPrivateKeyPath = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/smtp/keys/" . $_POST['FIELDS']['DOMAIN'] . ".private.pem";
		$strPublicKeyPath = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/smtp/keys/" . $_POST['FIELDS']['DOMAIN'] . ".public.pem";
		$pk = openssl_pkey_new(
			[
				'digest_alg' => 'sha256',
				'private_key_bits' => 2048,
				'private_key_type' => OPENSSL_KEYTYPE_RSA,
			]
		);
		//Save private key
		openssl_pkey_export_to_file($pk, $strPrivateKeyPath);
		//Save public key
		$pubKey = openssl_pkey_get_details($pk);
		$strPublicKey = $pubKey['key'];
		file_put_contents($strPublicKeyPath, $strPublicKey);
		$strPrivateKey = file_get_contents($strPrivateKeyPath);
		$_POST['FIELDS']['DKIM_PRIVATE'] = $strPrivateKey;
		$_POST['FIELDS']['DKIM_PUBLIC'] = $strPublicKey;
	} elseif (amsmtp_strlen($_POST['FIELDS']['DKIM_PRIVATE']) > 0) {
		$_POST['FIELDS']['DKIM_PRIVATE'] = str_replace("\n\r", "\n", $_POST['FIELDS']['DKIM_PRIVATE']);
		$_POST['FIELDS']['DKIM_PRIVATE'] = str_replace("\r\n", "\n", $_POST['FIELDS']['DKIM_PRIVATE']);
		$_POST['FIELDS']['DKIM_PUBLIC'] = str_replace("\n\r", "\n", $_POST['FIELDS']['DKIM_PUBLIC']);
		$_POST['FIELDS']['DKIM_PUBLIC'] = str_replace("\r\n", "\n", $_POST['FIELDS']['DKIM_PUBLIC']);
		CheckDirPath($_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/smtp/keys/");
		if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/.htaccess")) {
			file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/.htaccess", 'Deny from All');
		}
		$strPrivateKeyPath = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/smtp/keys/" . $_POST['FIELDS']['DOMAIN'] . ".private.pem";
		$strPublicKeyPath = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/smtp/keys/" . $_POST['FIELDS']['DOMAIN'] . ".public.pem";
		file_put_contents($strPrivateKeyPath, $_POST['FIELDS']['DKIM_PRIVATE']);
		file_put_contents($strPublicKeyPath, $_POST['FIELDS']['DKIM_PUBLIC']);
	}
	unset($_POST['FIELDS']['DKIM_REGENERATE']);
	if ($isNewItem) {
		$oTableResult = \Ammina\SMTP\DomainsTable::add($_POST['FIELDS']);
		$ID = $oTableResult->getId();
	} else {
		$oTableResult = \Ammina\SMTP\DomainsTable::update($ID, $_POST['FIELDS']);
	}
	if (!$oTableResult->isSuccess()) {
		$result->addErrors($oTableResult->getErrors());
	}
	if ($result->isSuccess()) {
		if (isset($_POST["save"])) {
			LocalRedirect("/bitrix/admin/ammina.smtp.domains.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_", false));
		} else {
			LocalRedirect("/bitrix/admin/ammina.smtp.domains.edit.php?lang=" . LANGUAGE_ID . "&ID=" . $ID . GetFilterParams("filter_", false));
		}
	}
}

if ($ID > 0) {
	$APPLICATION->SetTitle(Loc::getMessage("AMMINA_SMTP_PAGE_TITLE_EDIT"));
} else {
	$APPLICATION->SetTitle(Loc::getMessage("AMMINA_SMTP_PAGE_TITLE_ADD"));
}

CJSCore::Init();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

// context menu
$aMenu = array();
$aMenu[] = array(
	"ICON" => "btn_list",
	"TEXT" => Loc::getMessage("AMMINA_SMTP_TO_LIST"),
	"TITLE" => Loc::getMessage("AMMINA_SMTP_TO_LIST_TITLE"),
	"LINK" => "/bitrix/admin/ammina.smtp.domains.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_"),
);


$context = new CAdminContextMenu($aMenu);
$context->Show();

//errors
$errorMessage = "";

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
	"domain",
	"dkim",
	"domain_limit",
);

$formId = "ammina_smtp_domains_edit";

$aTabs = array(
	array("DIV" => "tab_ammina", "TAB" => Loc::getMessage("AMMINA_SMTP_TAB_DOMAIN"), "SHOW_WRAP" => "N", "IS_DRAGGABLE" => "Y"),
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
						case "domain":
							echo Blocks\Domain::getEdit($arCurrentItem);
							break;
						case "dkim":
							echo Blocks\Dkim::getEdit($arCurrentItem);
							break;
						case "domain_limit":
							echo Blocks\DomainLimit::getEdit($arCurrentItem);
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
		"back_url" => "/bitrix/admin/ammina.smtp.domains.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_"))
);

$tabControl->End();

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