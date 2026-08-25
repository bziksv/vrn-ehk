<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подтверждение регистрации");

/**
 * Пользователь активируется сразу при регистрации (ACTIVE=Y),
 * но CONFIRM_CODE остаётся до перехода по ссылке из письма.
 * Штатный bitrix:system.auth.confirmation при ACTIVE=Y показывает
 * «уже подтверждено» и НЕ очищает CONFIRM_CODE — из‑за этого
 * табличка «Подтвердите почту» продолжает показываться.
 * Здесь сбрасываем код при верной ссылке.
 */
$userId = (int)($_REQUEST['confirm_user_id'] ?? $_REQUEST['USER_ID'] ?? 0);
$confirmCode = trim((string)($_REQUEST['confirm_code'] ?? $_REQUEST['CONFIRM_CODE'] ?? ''));
if ($userId > 0 && $confirmCode !== '' && CModule::IncludeModule('main'))
{
	$rs = CUser::GetByID($userId);
	if ($user = $rs->Fetch())
	{
		$stored = trim((string)($user['CONFIRM_CODE'] ?? ''));
		if ($stored !== '' && hash_equals($stored, $confirmCode))
		{
			$obUser = new CUser();
			$obUser->Update($userId, ['CONFIRM_CODE' => '']);
			try
			{
				$session = \Bitrix\Main\Application::getInstance()->getSession();
				$session->remove('VRN_EHK_JUST_REGISTERED');
				$session->remove('PRIME_ALERTS_JUST_REGISTERED');
				$session->set('VRN_EHK_EMAIL_CONFIRM_ACK', 'Y');
				$session->remove('prime_alerts_email_confirm_dismissed');
			}
			catch (\Throwable $e)
			{
				// ignore
			}
		}
	}
}
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.confirmation",
	"",
	Array(
		"CONFIRM_CODE" => "confirm_code",
		"LOGIN" => "login",
		"USER_ID" => "confirm_user_id"
	)
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
