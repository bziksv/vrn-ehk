<?

namespace Ammina\SMTP\Agent;

use Ammina\SMTP\AccountsTable;
use Ammina\SMTP\Mail;
use Ammina\SMTP\QueueTable;
use Bitrix\Main\ORM\Fields\ExpressionField;

class CheckQueue
{
	public static function doExecute()
	{
		self::checkQueue();
		/*self::_makeServersMath();
		self::_makePortMath();
		self::_makeFieldsAllow();
		self::_makeCurrentAccounts();
		self::_checkFromMailModule();*/
		return '\Ammina\SMTP\Agent\CheckQueue::doExecute();';
	}

	public static function checkAgentExists()
	{
		$arAgent = \CAgent::GetList(
			array(),
			array(
				"NAME" => '\Ammina\SMTP\Agent\CheckQueue::doExecute();',
				"MODULE_ID" => "ammina.smtp",
			)
		)->Fetch();
		if ((\COption::GetOptionString("ammina.smtp", "use_queue", "Y") === "Y" || \COption::GetOptionString("ammina.smtp", "use_limits", "N") === "Y")) {
			$arFields = array(
				"NAME" => '\Ammina\SMTP\Agent\CheckQueue::doExecute();',
				"MODULE_ID" => "ammina.smtp",
				"ACTIVE" => "Y",
				"SORT" => 100,
				"IS_PERIOD" => "N",
				"AGENT_INTERVAL" => 60,
				"USER_ID" => false,
				"NEXT_EXEC" => ConvertTimeStamp(false, "FULL"),
			);
			if ($arAgent) {
				\CAgent::Update($arAgent['ID'], $arFields);
			} else {
				\CAgent::Add($arFields);
			}
		} else {
			if ($arAgent) {
				\CAgent::Delete($arAgent['ID']);
			}
		}
	}

	protected static function checkQueue()
	{
		$limitMinute = \COption::GetOptionInt("ammina.smtp", "limits_account_minute", 2);
		if ($limitMinute <= 0) {
			$limitMinute = 2;
		}
		$rAccounts = AccountsTable::getList(array(
			"filter" => array(
				"ACTIVE" => "Y"
			)
		));
		while ($arAccount = $rAccounts->fetch()) {
			$arMail = QueueTable::getList(array(
				"filter" => array(
					"ACCOUNT_ID" => $arAccount['ID'],
					"STATUS" => "N"
				),
				'select' => array('CNT'),
				'runtime' => array(
					new ExpressionField('CNT', 'COUNT(*)')
				)
			))->fetch();
			if ($arMail && $arMail['CNT'] > 0) {
				while ($limitMinute > 0 || $arMail['CNT'] > 0) {
					Mail::checkQueueForAccount($arAccount['ID']);
					$limitMinute--;
					$arMail['CNT']--;
				}
			}
		}
	}

}
