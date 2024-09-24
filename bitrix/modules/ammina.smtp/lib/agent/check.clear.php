<?

namespace Ammina\SMTP\Agent;

use Ammina\SMTP\QueueTable;
use Ammina\SMTP\StatAccountsTable;
use Ammina\SMTP\StatDomainsTable;

class CheckClear
{
	public static function doExecute()
	{
		if (\COption::GetOptionString("ammina.smtp", "use_stats", "Y") === "Y" || \COption::GetOptionString("ammina.smtp", "use_limits", "N") === "Y") {
			self::_checkStatClear();
		}
		if (\COption::GetOptionString("ammina.smtp", "use_queue", "Y") === "Y" || \COption::GetOptionString("ammina.smtp", "use_limits", "N") === "Y") {
			self::_checkQueueClear();
		}
		return '\Ammina\SMTP\Agent\CheckClear::doExecute();';
	}

	public static function checkAgentExists()
	{
		$arAgent = \CAgent::GetList(
			array(),
			array(
				"NAME" => '\Ammina\SMTP\Agent\CheckClear::doExecute();',
				"MODULE_ID" => "ammina.smtp",
			)
		)->Fetch();
		if (\COption::GetOptionString("ammina.smtp", "use_queue", "Y") === "Y" || \COption::GetOptionString("ammina.smtp", "use_limits", "N") === "Y" || \COption::GetOptionString("ammina.smtp", "use_stats", "Y") === "Y") {
			$arFields = array(
				"NAME" => '\Ammina\SMTP\Agent\CheckClear::doExecute();',
				"MODULE_ID" => "ammina.smtp",
				"ACTIVE" => "Y",
				"SORT" => 100,
				"IS_PERIOD" => "N",
				"AGENT_INTERVAL" => 3600,
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

	protected static function _checkStatClear()
	{
		$iStatDays = \COption::GetOptionInt("ammina.smtp", "stats_days", 365);
		if ($iStatDays <= 0) {
			$iStatDays = 365;
		}
		$rQueue = StatAccountsTable::getList(array(
			"filter" => array(
				"<SEND_DATE" => ConvertTimeStamp(time() - 3600 * 24 * $iStatDays, "SHORT")
			),
			"select" => array("ID")
		));
		while ($ar = $rQueue->fetch()) {
			StatAccountsTable::Delete($ar['ID']);
		}

		$rQueue = StatDomainsTable::getList(array(
			"filter" => array(
				"<SEND_DATE" => ConvertTimeStamp(time() - 3600 * 24 * $iStatDays, "SHORT")
			),
			"select" => array("ID")
		));
		while ($ar = $rQueue->fetch()) {
			StatDomainsTable::Delete($ar['ID']);
		}
	}

	protected static function _checkQueueClear()
	{
		$iQueueDays = \COption::GetOptionInt("ammina.smtp", "queue_days", 30);
		$iQueueErrorDays = \COption::GetOptionInt("ammina.smtp", "queue_error_days", 90);
		if ($iQueueDays <= 0) {
			$iQueueDays = 30;
		}
		if ($iQueueErrorDays <= 0) {
			$iQueueErrorDays = 90;
		}
		$rQueue = QueueTable::getList(array(
			"filter" => array(
				"STATUS" => "S",
				"<DATE_INSERT" => ConvertTimeStamp(time() - 3600 * 24 * $iQueueDays, "SHORT")
			),
			"select" => array("ID")
		));
		while ($ar = $rQueue->fetch()) {
			QueueTable::Delete($ar['ID']);
		}
		$rQueue = QueueTable::getList(array(
			"filter" => array(
				"STATUS" => "E",
				"<DATE_INSERT" => ConvertTimeStamp(time() - 3600 * 24 * $iQueueErrorDays, "SHORT")
			),
			"select" => array("ID")
		));
		while ($ar = $rQueue->fetch()) {
			QueueTable::Delete($ar['ID']);
		}
	}

}
