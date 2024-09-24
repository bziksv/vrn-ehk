<?

namespace Ammina\SMTP\Agent;

use Ammina\SMTP\AccountsTable;
use Ammina\SMTP\DomainsTable;
use Bitrix\Mail\MailboxTable;

class SyncMail
{
	protected static $SERVERS_MATH = array();
	protected static $PORT_MATH = array();
	protected static $FIELDS_ALLOW = array();
	protected static $CURRENT_ACCOUNTS_BY_EMAIL = array();
	protected static $CURRENT_ACCOUNTS_IMPORTED = array();
	protected static $CHECKED_ACCOUNTS = array();

	public static function doExecute()
	{
		self::_makeServersMath();
		self::_makePortMath();
		self::_makeFieldsAllow();
		self::_makeCurrentAccounts();
		self::_checkFromMailModule();
		return '\Ammina\SMTP\Agent\SyncMail::doExecute();';
	}

	public static function checkAgentExists()
	{
		$arAgent = \CAgent::GetList(
			array(),
			array(
				"NAME" => '\Ammina\SMTP\Agent\SyncMail::doExecute();',
				"MODULE_ID" => "ammina.smtp",
			)
		)->Fetch();
		if (\COption::GetOptionString("ammina.smtp", "import_from_mail", "N") === "Y") {
			$arFields = array(
				"NAME" => '\Ammina\SMTP\Agent\SyncMail::doExecute();',
				"MODULE_ID" => "ammina.smtp",
				"ACTIVE" => "Y",
				"SORT" => 100,
				"IS_PERIOD" => "N",
				"AGENT_INTERVAL" => 600,
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

	protected static function _makeServersMath()
	{
		$arServersMath = explode("\n", \COption::GetOptionString("ammina.smtp", "servers_math", implode("\n", \CAmminaSmtpConstants::$DEFAULT_SERVERS_MATH)));
		foreach ($arServersMath as $arMath) {
			$arMath = trim($arMath);
			if (amsmtp_strlen($arMath) > 0) {
				$arMath = explode("=", $arMath);
				$arMathData = explode("|", $arMath[1]);
				if (!isset($arMathData[1]) || !in_array($arMathData[1], array("S", "T", "N"))) {
					$arMathData[1] = "N";
				}
				$arMathData[0] = explode(":", $arMathData[0]);
				if (!isset($arMathData[0][1])) {
					$arMathData[0][1] = 25;
				}
				$arMathData = array(
					"SMTP_HOST" => $arMathData[0][0],
					"SMTP_PORT" => $arMathData[0][1],
					"SECURE_TYPE" => $arMathData[1],
				);
				$arPopServers = explode("|", $arMath[0]);
				foreach ($arPopServers as $popServer) {
					$popServer = trim($popServer);
					if (amsmtp_strlen($popServer) > 0) {
						self::$SERVERS_MATH[amsmtp_strtolower($popServer)] = $arMathData;
					}
				}
			}
		}
	}

	protected static function _makePortMath()
	{
		$arPortMath = explode("\n", \COption::GetOptionString("ammina.smtp", "port_math", implode("\n", \CAmminaSmtpConstants::$DEFAULT_PORT_MATH)));
		foreach ($arPortMath as $arMath) {
			$arMath = trim($arMath);
			if (amsmtp_strlen($arMath) > 0) {
				$arMath = explode("=", $arMath);
				$arMathData = explode("|", $arMath[1]);
				if (!isset($arMathData[1]) || !in_array($arMathData[1], array("S", "T", "N"))) {
					$arMathData[1] = "N";
				}
				$arMathData = array(
					"SMTP_PORT" => $arMathData[0],
					"SECURE_TYPE" => $arMathData[1],
				);
				$arPopPorts = explode("|", $arMath[0]);
				foreach ($arPopPorts as $popPorts) {
					$popPorts = trim($popPorts);
					if (amsmtp_strlen($popPorts) > 0) {
						self::$PORT_MATH[amsmtp_strtolower($popPorts)] = $arMathData;
					}
				}
			}
		}
	}

	protected static function _makeFieldsAllow()
	{
		self::$FIELDS_ALLOW = explode("|", \COption::GetOptionString("ammina.smtp", "import_update_fields", implode("|", \CAmminaSmtpConstants::$DEFAULT_UPDATE_FIELDS)));
	}

	protected static function _makeCurrentAccounts()
	{
		$rAccounts = AccountsTable::getList(array());
		while ($arAccount = $rAccounts->fetch()) {
			self::$CURRENT_ACCOUNTS_BY_EMAIL[amsmtp_strtolower($arAccount['EMAIL'])] = $arAccount;
			if ($arAccount['IS_IMPORT'] === "Y") {
				self::$CURRENT_ACCOUNTS_IMPORTED[$arAccount['ID']] = $arAccount['ID'];
			}
		}
	}

	protected static function _checkFromMailModule()
	{
		if (\CModule::IncludeModule("mail")) {
			$rMailBox = MailboxTable::getList();
			while ($arMailBox = $rMailBox->fetch()) {
				$arFields = array(
					"ACTIVE" => $arMailBox['ACTIVE'],
					"SENDER_NAME" => $arMailBox['NAME'],
					"EMAIL" => self::_findFirstEmail(array($arMailBox['EMAIL'], $arMailBox['NAME'], $arMailBox['LOGIN'])),
					"ACCOUNT_LOGIN" => $arMailBox['LOGIN'],
					"ACCOUNT_PASSWORD" => $arMailBox['PASSWORD'],
					"SMTP_HOST" => $arMailBox['SERVER'],
					"IS_IMPORT" => "Y"
				);
				if ($arMailBox['USER_ID'] > 0) {
					$arUser = \CUser::GetByID($arMailBox['USER_ID'])->Fetch();
					if ($arUser) {
						$strName = \CUser::FormatName("#LAST_NAME# #NAME# #SECOND_NAME#", $arUser, false, false);
						if (amsmtp_strlen($strName) > 0) {
							$arFields['SENDER_NAME'] = $strName;
						}
					}
				}
				$arCheckDomain = explode("@", $arFields['EMAIL']);
				if (amsmtp_strlen($arCheckDomain[1]) > 0) {
					$arDomain = DomainsTable::getList(array(
						"filter" => array("DOMAIN" => $arCheckDomain[1])
					))->fetch();
					if ($arDomain) {
						$arFields['DOMAIN_ID'] = $arDomain['ID'];
					} else {
						$oRes = DomainsTable::add(array(
							"DOMAIN" => $arCheckDomain[1]
						));
						if ($oRes->isSuccess()) {
							$arFields['DOMAIN_ID'] = $oRes->getId();
						}
					}
				}
				$bMath = false;
				if (isset(self::$SERVERS_MATH[amsmtp_strtolower($arMailBox['SERVER'])])) {
					$bMath = true;
					$arFields["SMTP_HOST"] = self::$SERVERS_MATH[amsmtp_strtolower($arMailBox['SERVER'])]['SMTP_HOST'];
					$arFields["SMTP_PORT"] = self::$SERVERS_MATH[amsmtp_strtolower($arMailBox['SERVER'])]['SMTP_PORT'];
					$arFields["SECURE_TYPE"] = self::$SERVERS_MATH[amsmtp_strtolower($arMailBox['SERVER'])]['SECURE_TYPE'];
				} elseif (isset(self::$PORT_MATH[$arMailBox['PORT']]) && \COption::GetOptionString("ammina.smtp", "import_math_by_port", "Y") === "Y") {
					$bMath = true;
					$arFields["SMTP_PORT"] = self::$PORT_MATH[$arMailBox['PORT']]['SMTP_PORT'];
					$arFields["SECURE_TYPE"] = self::$PORT_MATH[$arMailBox['PORT']]['SECURE_TYPE'];
				}
				if ($arFields['DOMAIN_ID'] <= 0) {
					$bMath = false;
				}

				if ($bMath) {
					$arCurrentAccount = false;
					if (isset(self::$CURRENT_ACCOUNTS_BY_EMAIL[amsmtp_strtolower($arFields['EMAIL'])])) {
						$arCurrentAccount = self::$CURRENT_ACCOUNTS_BY_EMAIL[amsmtp_strtolower($arFields['EMAIL'])];
					}
					if ($arCurrentAccount) {
						foreach ($arFields as $field => $value) {
							if (!in_array($field, self::$FIELDS_ALLOW)) {
								unset($arFields[$field]);
							}
						}
						if ($arCurrentAccount['IS_IMPORT'] !== "Y" && \COption::GetOptionString("ammina.smtp", "import_mark_as_import", "Y") === "Y") {
							$arFields['IS_IMPORT'] = "Y";
						}
						if (!empty($arFields)) {
							AccountsTable::update($arCurrentAccount['ID'], $arFields);
						}
						self::$CHECKED_ACCOUNTS[] = $arCurrentAccount['ID'];
					} else {
						$oRes = AccountsTable::add($arFields);
					}
				}
			}
			if (\COption::GetOptionString("ammina.smtp", "import_delete_no_exists", "Y") === "Y") {
				foreach (self::$CURRENT_ACCOUNTS_IMPORTED as $id) {
					if (!in_array($id, self::$CHECKED_ACCOUNTS)) {
						AccountsTable::delete($id);
					}
				}
			}
		}
	}

	protected static function _findFirstEmail($arEmails)
	{
		foreach ($arEmails as $email) {
			$email = trim($email);
			if (check_email($email)) {
				return $email;
			}
		}
		return false;
	}
}

