<?

namespace Ammina\SMTP;

use Bitrix\Main\Type\DateTime;

class Mail
{
	static $actualLog = '';

	public static function custom_mail($to, $subject, $message, $additionalHeaders = '', $additionalParameters = '')
	{
		global $APPLICATION, $oMail, $oParser;
		$oParser = new Parser($to, $subject, $message, $additionalHeaders, $additionalParameters);
		$oParser->doParse();
		$arCurrentAccountDomain = \Ammina\SMTP\AccountsTable::getOptimalAccountDomainByFrom($oParser->strFieldFrom);

		if ($arCurrentAccountDomain) {
			if ($arCurrentAccountDomain['IS_ACCOUNT']) {
				if (self::useQueue()) {
					$queueId = self::pushMessageToQueue($arCurrentAccountDomain['ACCOUNT']['ID'], $to, $subject, $message, $additionalHeaders, $additionalParameters);
					if ($queueId > 0) {
						return self::checkQueueForAccount($arCurrentAccountDomain['ACCOUNT']['ID']);
					}
					self::log("[queue] ERROR PUSH TO QUEUE (To: " . $to . ", Subject: " . $subject . ")");
					return false;
				}

				return self::sendMailMessage($arCurrentAccountDomain['ACCOUNT']['ID'], null, $to, $subject, $message, $additionalHeaders, $additionalParameters);
			}
			return self::sendMailMessageByDomain($arCurrentAccountDomain['DOMAIN']['ID'], $to, $subject, $message, $additionalHeaders, $additionalParameters);
		}

		if ($additionalParameters != "") {
			return @mail($to, $subject, $message, $additionalHeaders, $additionalParameters);
		}

		return @mail($to, $subject, $message, $additionalHeaders);
	}

	public static function logToDb($strMessage)
	{
		self::$actualLog .= date("d.m.Y H:i:s") . ": " . trim($strMessage) . "\n";
	}

	public static function log($strMessage)
	{
		CheckDirPath($_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/smtp/log/");
		if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/.htaccess")) {
			file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/.htaccess", 'Deny from All');
		}
		$f = fopen($_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/smtp/log/" . date("Y-m-d") . ".log", "a+b");
		if ($f) {
			$bLock = true;
			$cnt = 100;
			while (!flock($f, LOCK_EX | LOCK_NB)) {
				$cnt--;
				if ($cnt <= 0) {
					$bLock = false;
					break;
				}
				usleep(10000);
			}
			if ($bLock) {
				$strMessage = date("d.m.Y H:i:s") . ": " . trim($strMessage) . "\n";
				fwrite($f, $strMessage);
				flock($f, LOCK_UN);
			}
			fclose($f);
		}
	}

	public static function useQueue()
	{
		return (\COption::GetOptionString("ammina.smtp", "use_queue", "Y") === "Y" || \COption::GetOptionString("ammina.smtp", "use_limits", "N") === "Y");
	}

	public static function useStat()
	{
		return (\COption::GetOptionString("ammina.smtp", "use_stats", "Y") === "Y" || \COption::GetOptionString("ammina.smtp", "use_limits", "N") === "Y");
	}

	public static function useLimits()
	{
		return \COption::GetOptionString("ammina.smtp", "use_limits", "N") === "Y";
	}

	public static function pushMessageToQueue($accountId, $to, $subject, $message, $additionalHeaders, $additionalParameters)
	{
		$arFields = array(
			"ACCOUNT_ID" => $accountId,
			"STATUS" => "N",
			"DATE_INSERT" => new DateTime(),
			"MAIL_DATA" => array(
				"to" => $to,
				"subject" => $subject,
				"message" => $message,
				"additionalHeaders" => $additionalHeaders,
				"additionalParameters" => $additionalParameters
			)
		);
		$result = QueueTable::add($arFields);
		if ($result->isSuccess()) {
			return $result->getId();
		}
		return false;
	}

	public static function checkQueueForAccount($accountId)
	{
		if (self::useQueue() && self::isAllowLimitAccount($accountId)) {
			$arNextMail = QueueTable::getList(array(
				"filter" => array(
					"ACCOUNT_ID" => $accountId,
					"STATUS" => "N"
				),
				"order" => array(
					"ID" => "DESC"
				),
				'limit' => 1
			))->fetch();
			if ($arNextMail) {
				QueueTable::update($arNextMail['ID'], array("STATUS" => "P"));
				self::$actualLog = '';
				$result = self::sendMailMessage($arNextMail['ACCOUNT_ID'], $arNextMail['ID'], $arNextMail['MAIL_DATA']['to'], $arNextMail['MAIL_DATA']['subject'], $arNextMail['MAIL_DATA']['message'], $arNextMail['MAIL_DATA']['additionalHeaders'], $arNextMail['MAIL_DATA']['additionalParameters']);
				if (strlen($arNextMail['LOG_DATA']) > 1024 * 1024 * 1024) {
					$ar = explode("\n\n============================================\n\n", $arNextMail['LOG_DATA']);
					if (count($ar) > 1) {
						$arNextMail['LOG_DATA'] = array_pop($ar);
					} else {
						$arNextMail['LOG_DATA'] = '';
					}
				}
				if ($result) {
					QueueTable::update($arNextMail['ID'], array(
						"STATUS" => "S",
						"DATE_SEND" => new DateTime(),
						"LOG_DATA" => strlen($arNextMail['LOG_DATA']) > 0 ? $arNextMail['LOG_DATA'] . "\n\n============================================\n\n" . self::$actualLog : self::$actualLog
					));
				} else {
					QueueTable::update($arNextMail['ID'], array(
						"STATUS" => "E",
						"LOG_DATA" => strlen($arNextMail['LOG_DATA']) > 0 ? $arNextMail['LOG_DATA'] . "\n\n============================================\n\n" . self::$actualLog : self::$actualLog
					));
					return false;
				}
			}
		}
		return true;
	}

	public static function sendMailMessage($accountId, $queueId, $to, $subject, $message, $additionalHeaders, $additionalParameters)
	{
		global $oMail, $oParser;
		$oParser = new Parser($to, $subject, $message, $additionalHeaders, $additionalParameters);
		$oParser->doParse();
		$queueId=(int)$queueId;
		if ($queueId > 0) {
			QueueTable::update($queueId, [
				'FIELD_TO' => $oParser->strFieldTo[0],
				'FIELD_SUBJECT' => $oParser->strFieldSubject
			]);
		}
		$arCurrentAccountDomain = \Ammina\SMTP\AccountsTable::getAccountDomainInfo($accountId);
		if ($arCurrentAccountDomain) {
			$oAuth = false;
			if (amsmtp_strlen($arCurrentAccountDomain['DOMAIN']['DKIM_PRIVATE']) > 0) {
				$oMail = new \PHPMailer();
			} else {
				$oMail = new \PHPMailer\PHPMailer\PHPMailer();
				if (\CModule::IncludeModule("mail")) {
					$tmpMeta = \Bitrix\Mail\Helper\OAuth::parseMeta($arCurrentAccountDomain['ACCOUNT']['ACCOUNT_PASSWORD']);
					if ($arCurrentAccountDomain['IS_ACCOUNT'] && $tmpMeta) {
						$bxOauthInstance = \Bitrix\Mail\Helper\OAuth::getInstanceByMeta($arCurrentAccountDomain['ACCOUNT']['ACCOUNT_PASSWORD']);
						$tokens = $bxOauthInstance->getStoredToken();
						if (strlen($tokens) > 0) {
							$oAuth = new \PHPMailer\PHPMailer\OAuth(array(
								"userName" => $arCurrentAccountDomain['ACCOUNT']['ACCOUNT_LOGIN'],
								"token" => $tokens
							));
							$oMail->setOAuth($oAuth);
							$oMail->AuthType = "XOAUTH2";
						}
					}
				}
			}
			if (strlen($oParser->messageId) > 0) {
				$oMail->MessageID = $oParser->messageId;
			}
			$oMail->SMTPDebug = intval(\COption::GetOptionString("ammina.smtp", "debug_level", 0));
			$oMail->Debugoutput = function ($str, $level) {
				if (self::useQueue()) {
					Mail::logToDb("[debug level " . $level . "] " . $str);
				} else {
					Mail::log("[debug level " . $level . "] " . $str);
				}
			};
			if ($arCurrentAccountDomain['IS_ACCOUNT']) {
				$oMail->isSMTP();
				$oMail->Host = $arCurrentAccountDomain['ACCOUNT']['SMTP_HOST'];
				$oMail->SMTPAutoTLS = false;
				$oMail->SMTPAuth = true;
				if (!$oAuth) {
					$oMail->Username = $arCurrentAccountDomain['ACCOUNT']['ACCOUNT_LOGIN'];
					$oMail->Password = $arCurrentAccountDomain['ACCOUNT']['ACCOUNT_PASSWORD'];
				}
				$oMail->SMTPSecure = ($arCurrentAccountDomain['ACCOUNT']['SECURE_TYPE'] === "S" ? "ssl" : ($arCurrentAccountDomain['ACCOUNT']['SECURE_TYPE'] === "T" ? "tls" : ""));
				$oMail->Port = $arCurrentAccountDomain['ACCOUNT']['SMTP_PORT'];
				if (\COption::GetOptionString("ammina.smtp", "not_change_name_sender", "Y") === "Y" && strlen($oParser->strFieldFromName) > 0) {
					$oMail->setFrom($arCurrentAccountDomain['ACCOUNT']['EMAIL'], $oParser->strFieldFromName);
				} else {
					$oMail->setFrom($arCurrentAccountDomain['ACCOUNT']['EMAIL'], $arCurrentAccountDomain['ACCOUNT']['SENDER_NAME']);
				}
				$oMail->Timeout = \COption::GetOptionInt("ammina.smtp", "timeout", 30);
			} elseif ($arCurrentAccountDomain['IS_DOMAIN']) {
				$oMail->setFrom($oParser->strFieldFrom, $oParser->strFieldFromName);
				$oMail->isMail();
			}
			$oMail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			$oMail->clearAddresses();
			$oMail->clearReplyTos();
			$oMail->clearCCs();
			$oMail->clearBCCs();

			if (is_array($oParser->strFieldTo)) {
				foreach ($oParser->strFieldTo as $k => $v) {
					$oMail->addAddress($v, $oParser->strFieldToName[$k]);
				}
			} else {
				$oMail->addAddress($oParser->strFieldTo, $oParser->strFieldToName);
			}
			if (is_array($oParser->strFieldReplyTo)) {
				foreach ($oParser->strFieldReplyTo as $k => $v) {
					$oMail->addReplyTo($v, $oParser->strFieldReplyToName[$k]);
				}
			} elseif (strlen($oParser->strFieldReplyTo) > 0) {
				$oMail->addReplyTo($oParser->strFieldReplyTo, $oParser->strFieldReplyToName);
			}
			if (is_array($oParser->strFieldCC)) {
				foreach ($oParser->strFieldCC as $k => $v) {
					$oMail->addCC($v, $oParser->strFieldCCName[$k]);
				}
			} elseif (strlen($oParser->strFieldCC) > 0) {
				$oMail->addCC($oParser->strFieldCC, $oParser->strFieldCCName);
			}
			if (is_array($oParser->strFieldBCC)) {
				foreach ($oParser->strFieldBCC as $k => $v) {
					$oMail->addBCC($v, $oParser->strFieldBCCName[$k]);
				}
			} elseif (strlen($oParser->strFieldBCC) > 0) {
				$oMail->addBCC($oParser->strFieldBCC, $oParser->strFieldBCCName);
			}

			$oMail->Subject = $oParser->strFieldSubject;
			$oMail->CharSet = $oParser->strHeaderCharset;
			$oMail->isHTML($oParser->bIsHtmlMessage);
			if (amsmtp_strlen($arCurrentAccountDomain['DOMAIN']['DKIM_PRIVATE']) > 0) {
				$oMail->DKIM_domain = $arCurrentAccountDomain['DOMAIN']['DOMAIN'];
				$oMail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/smtp/keys/" . $arCurrentAccountDomain['DOMAIN']['DOMAIN'] . ".private.pem";
				$oMail->DKIM_selector = $arCurrentAccountDomain['DOMAIN']['DKIM_SELECTOR'];
				$oMail->DKIM_passphrase = $arCurrentAccountDomain['DOMAIN']['DKIM_PASSPHRASE'];
				$oMail->DKIM_identity = $oMail->From;
				$oMail->DKIM_copyHeaderFields = false;
				$oMail->Encoding = "base64";//\PHPMailer\PHPMailer\PHPMailer::ENCODING_BASE64;
			}
			if (amsmtp_strpos($oParser->strHeaderContentType, "multipart/") === 0) {
				if ($oParser->strHeaderContentType === "multipart/alternative") {
					$oMail->Body = $oParser->strMessage;
					$oMail->AltBody = $oParser->strAltMessage;
					foreach ($oParser->arAttachment as $arData) {
						$oMail->addStringEmbeddedImage($arData['content'], str_replace(["<", ">"], "", $arData['headers']['content-id']['VALUE']), $arData['name'], $arData['headers']['content-transfer-encoding']['VALUE'], $arData['content-type'], "attachment");
					}
				} elseif ($oParser->strHeaderContentType === "multipart/mixed") {
					$oMail->Body = $oParser->strMessage;
					$oMail->AltBody = $oParser->strAltMessage;
					foreach ($oParser->arAttachment as $arData) {
						$oMail->addStringEmbeddedImage($arData['content'], str_replace(["<", ">"], "", $arData['headers']['content-id']['VALUE']), $arData['name'], $arData['headers']['content-transfer-encoding']['VALUE'], $arData['content-type'], "attachment");
					}
				} elseif ($oParser->strHeaderContentType === "multipart/related") {
					$oMail->Body = $oParser->strMessage;
					foreach ($oParser->arAttachment as $arData) {
						$oMail->addStringEmbeddedImage($arData['content'], str_replace(["<", ">"], "", $arData['headers']['content-id']['VALUE']), $arData['name'], $arData['headers']['content-transfer-encoding']['VALUE'], $arData['content-type'], "attachment");
					}
				}
			} else {
				$oMail->Body = $oParser->strMessage;
			}
			foreach ($oParser->arFieldsHeaders as $key => $value) {
				if ($key === "content-transfer-encoding" || $key === "from" || $key === "date" || $key === "mime-version" || $key === "content-type" || $key === "to") {
					continue;
				}

				if ($key === "bcc") {
					$arBcc = explode(",", $value['VALUE']);
					foreach ($arBcc as $strBcc) {
						$oMail->addBCC($strBcc);
					}
				} elseif ($key === "cc") {
					$arCc = explode(",", $value['VALUE']);
					foreach ($arCc as $strCc) {
						$oMail->addCC($strCc);
					}
				} elseif ($key === "reply-to") {
					$oMail->addReplyTo($value['VALUE']);
				} else {
					$oMail->addCustomHeader($value['NAME'], $value['VALUE']);
				}
			}
			$status = $oMail->send();
			/*if (\COption::GetOptionString("ammina.smtp", "log", "N") === "Y") {
				if ($status) {
					$strLogMessage = "Status: " . ($status ? "OK" : "Fail") . ". From: " . $oParser->strFieldFrom . ". Message: " . $oMail->getSentMIMEMessage();
				} else {
					$strLogMessage = "Status: " . ($status ? "OK" : "Fail") . ". From: " . $oParser->strFieldFrom . ". Error: " . $oMail->ErrorInfo;
				}
				self::log($strLogMessage);
			}*/
			self::updateStatForAccount($accountId, $status);
			return $status;
		}
		return false;
	}

	public static function sendMailMessageByDomain($domainId, $to, $subject, $message, $additionalHeaders, $additionalParameters)
	{
		global $APPLICATION, $oMail, $oParser;
		$oParser = new Parser($to, $subject, $message, $additionalHeaders, $additionalParameters);
		$oParser->doParse();
		$arCurrentDomain = \Ammina\SMTP\DomainsTable::getDomainInfo($domainId);
		if ($arCurrentDomain) {
			if (amsmtp_strlen($arCurrentDomain['DKIM_PRIVATE']) > 0) {
				$oMail = new \PHPMailer();
			} else {
				$oMail = new \PHPMailer\PHPMailer\PHPMailer();
			}
			if (strlen($oParser->messageId) > 0) {
				$oMail->MessageID = $oParser->messageId;
			}
			$oMail->SMTPDebug = intval(\COption::GetOptionString("ammina.smtp", "debug_level", 0));
			$oMail->Debugoutput = function ($str, $level) {
				Mail::log("[debug level " . $level . "] " . $str);
			};
			$oMail->setFrom($oParser->strFieldFrom, $oParser->strFieldFromName);
			$oMail->isMail();
			$oMail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			$oMail->clearAddresses();
			if (is_array($oParser->strFieldTo)) {
				foreach ($oParser->strFieldTo as $k => $v) {
					$oMail->addAddress($v, $oParser->strFieldToName[$k]);
				}
			} else {
				$oMail->addAddress($oParser->strFieldTo, $oParser->strFieldToName);
			}
			$oMail->Subject = $oParser->strFieldSubject;
			$oMail->CharSet = $oParser->strHeaderCharset;
			$oMail->isHTML($oParser->bIsHtmlMessage);
			if (amsmtp_strlen($arCurrentDomain['DKIM_PRIVATE']) > 0) {
				$oMail->DKIM_domain = $arCurrentDomain['DOMAIN'];
				$oMail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/ammina/smtp/keys/" . $arCurrentDomain['DOMAIN'] . ".private.pem";
				$oMail->DKIM_selector = $arCurrentDomain['DKIM_SELECTOR'];
				$oMail->DKIM_passphrase = $arCurrentDomain['DKIM_PASSPHRASE'];
				$oMail->DKIM_identity = $oMail->From;
				$oMail->DKIM_copyHeaderFields = false;
				$oMail->Encoding = "base64";//\PHPMailer\PHPMailer\PHPMailer::ENCODING_BASE64;
			}
			if (amsmtp_strpos($oParser->strHeaderContentType, "multipart/") === 0) {
				if ($oParser->strHeaderContentType === "multipart/alternative") {
					$oMail->Body = $oParser->strMessage;
					$oMail->AltBody = $oParser->strAltMessage;
					foreach ($oParser->arAttachment as $arData) {
						$oMail->addStringEmbeddedImage($arData['content'], str_replace(["<", ">"], "", $arData['headers']['content-id']['VALUE']), $arData['name'], $arData['headers']['content-transfer-encoding']['VALUE'], $arData['content-type'], "attachment");
					}
				} elseif ($oParser->strHeaderContentType === "multipart/mixed") {
					$oMail->Body = $oParser->strMessage;
					$oMail->AltBody = $oParser->strAltMessage;
					foreach ($oParser->arAttachment as $arData) {
						$oMail->addStringEmbeddedImage($arData['content'], str_replace(["<", ">"], "", $arData['headers']['content-id']['VALUE']), $arData['name'], $arData['headers']['content-transfer-encoding']['VALUE'], $arData['content-type'], "attachment");
					}
				} elseif ($oParser->strHeaderContentType === "multipart/related") {
					$oMail->Body = $oParser->strMessage;
					foreach ($oParser->arAttachment as $arData) {
						$oMail->addStringEmbeddedImage($arData['content'], str_replace(["<", ">"], "", $arData['headers']['content-id']['VALUE']), $arData['name'], $arData['headers']['content-transfer-encoding']['VALUE'], $arData['content-type'], "attachment");
					}
				}
			} else {
				$oMail->Body = $oParser->strMessage;
			}
			foreach ($oParser->arFieldsHeaders as $key => $value) {
				if ($key === "content-transfer-encoding" || $key === "from" || $key === "date" || $key === "mime-version" || $key === "content-type" || $key === "to") {
					continue;
				}

				if ($key === "bcc") {
					$arBcc = explode(",", $value['VALUE']);
					foreach ($arBcc as $strBcc) {
						$oMail->addBCC($strBcc);
					}
				} elseif ($key === "cc") {
					$arCc = explode(",", $value['VALUE']);
					foreach ($arCc as $strCc) {
						$oMail->addCC($strCc);
					}
				} elseif ($key === "reply-to") {
					$oMail->addReplyTo($value['VALUE']);
				} else {
					$oMail->addCustomHeader($value['NAME'], $value['VALUE']);
				}
			}
			$status = $oMail->send();
			if (\COption::GetOptionString("ammina.smtp", "log", "N") === "Y") {
				if ($status) {
					$strLogMessage = "Status: " . ($status ? "OK" : "Fail") . ". From: " . $oParser->strFieldFrom . ". Message: " . $oMail->getSentMIMEMessage();
				} else {
					$strLogMessage = "Status: " . ($status ? "OK" : "Fail") . ". From: " . $oParser->strFieldFrom . ". Error: " . $oMail->ErrorInfo;
				}
				self::log($strLogMessage);
			}
			self::updateStatForDomain($domainId, $status);
			return $status;
		}
		return false;
	}

	public static function updateStatForAccount($accountId, $state)
	{
		$updateField = $state ? "CNT_SEND" : "CNT_ERROR";
		$sendTime = time();
		$arDateStat = StatAccountsTable::getList(array(
			"filter" => array(
				"ACCOUNT_ID" => $accountId,
				"SEND_DATE" => ConvertTimeStamp($sendTime, "SHORT"),
				"IS_DAY" => "Y"
			),
			"runtime" => array(
				new \Bitrix\Main\ORM\Fields\ExpressionField('IS_DAY', "IF(%s IS NULL AND %s IS NULL,'Y','N')", array("MINUTE", "HOUR"))
			)
		))->fetch();
		if ($arDateStat) {
			StatAccountsTable::update($arDateStat['ID'], array(
				$updateField => $arDateStat[$updateField] + 1
			));
		} else {
			StatAccountsTable::add(array(
				"ACCOUNT_ID" => $accountId,
				"SEND_DATE" => new DateTime(ConvertTimeStamp($sendTime)),
				"HOUR" => null,
				"MINUTE" => null,
				$updateField => 1
			));
		}

		$arDateStat = StatAccountsTable::getList(array(
			"filter" => array(
				"ACCOUNT_ID" => $accountId,
				"SEND_DATE" => ConvertTimeStamp($sendTime, "SHORT"),
				"HOUR" => date("H", $sendTime),
				"IS_HOUR" => "Y"
			),
			"runtime" => array(
				new \Bitrix\Main\ORM\Fields\ExpressionField('IS_HOUR', "IF(%s IS NULL AND %s IS NOT NULL,'Y','N')", array("MINUTE", "HOUR"))
			)
		))->fetch();
		if ($arDateStat) {
			StatAccountsTable::update($arDateStat['ID'], array(
				$updateField => $arDateStat[$updateField] + 1
			));
		} else {
			StatAccountsTable::add(array(
				"ACCOUNT_ID" => $accountId,
				"SEND_DATE" => new DateTime(ConvertTimeStamp($sendTime)),
				"HOUR" => date("H", $sendTime),
				"MINUTE" => null,
				$updateField => 1
			));
		}

		$arDateStat = StatAccountsTable::getList(array(
			"filter" => array(
				"ACCOUNT_ID" => $accountId,
				"SEND_DATE" => ConvertTimeStamp($sendTime, "SHORT"),
				"HOUR" => date("H", $sendTime),
				"MINUTE" => date("i", $sendTime),
				"IS_MINUTE" => "Y"
			),
			"runtime" => array(
				new \Bitrix\Main\ORM\Fields\ExpressionField('IS_MINUTE', "IF(%s IS NOT NULL AND %s IS NOT NULL,'Y','N')", array("MINUTE", "HOUR"))
			)
		))->fetch();
		if ($arDateStat) {
			StatAccountsTable::update($arDateStat['ID'], array(
				$updateField => $arDateStat[$updateField] + 1
			));
		} else {
			StatAccountsTable::add(array(
				"ACCOUNT_ID" => $accountId,
				"SEND_DATE" => new DateTime(ConvertTimeStamp($sendTime)),
				"HOUR" => date("H", $sendTime),
				"MINUTE" => date("i", $sendTime),
				$updateField => 1
			));
		}

		$account = AccountsTable::getAccountInfo($accountId);
		if ($account) {
			self::updateStatForDomain($account['DOMAIN_ID'], $state);
		}
	}

	public static function updateStatForDomain($domainId, $state)
	{
		$updateField = $state ? "CNT_SEND" : "CNT_ERROR";
		$sendTime = time();
		$arDateStat = StatDomainsTable::getList(array(
			"filter" => array(
				"DOMAIN_ID" => $domainId,
				"SEND_DATE" => ConvertTimeStamp($sendTime, "SHORT"),
				"IS_DAY" => "Y"
			),
			"runtime" => array(
				new \Bitrix\Main\ORM\Fields\ExpressionField('IS_DAY', "IF(%s IS NULL AND %s IS NULL,'Y','N')", array("MINUTE", "HOUR"))
			)
		))->fetch();
		if ($arDateStat) {
			StatDomainsTable::update($arDateStat['ID'], array(
				$updateField => $arDateStat[$updateField] + 1
			));
		} else {
			StatDomainsTable::add(array(
				"DOMAIN_ID" => $domainId,
				"SEND_DATE" => new DateTime(ConvertTimeStamp($sendTime)),
				"HOUR" => null,
				"MINUTE" => null,
				$updateField => 1
			));
		}

		$arDateStat = StatDomainsTable::getList(array(
			"filter" => array(
				"DOMAIN_ID" => $domainId,
				"SEND_DATE" => ConvertTimeStamp($sendTime, "SHORT"),
				"HOUR" => date("H", $sendTime),
				"IS_HOUR" => "Y"
			),
			"runtime" => array(
				new \Bitrix\Main\ORM\Fields\ExpressionField('IS_HOUR', "IF(%s IS NULL AND %s IS NOT NULL,'Y','N')", array("MINUTE", "HOUR"))
			)
		))->fetch();
		if ($arDateStat) {
			StatDomainsTable::update($arDateStat['ID'], array(
				$updateField => $arDateStat[$updateField] + 1
			));
		} else {
			StatDomainsTable::add(array(
				"DOMAIN_ID" => $domainId,
				"SEND_DATE" => new DateTime(ConvertTimeStamp($sendTime)),
				"HOUR" => date("H", $sendTime),
				"MINUTE" => null,
				$updateField => 1
			));
		}

		$arDateStat = StatDomainsTable::getList(array(
			"filter" => array(
				"DOMAIN_ID" => $domainId,
				"SEND_DATE" => ConvertTimeStamp($sendTime, "SHORT"),
				"HOUR" => date("H", $sendTime),
				"MINUTE" => date("i", $sendTime),
				"IS_MINUTE" => "Y"
			),
			"runtime" => array(
				new \Bitrix\Main\ORM\Fields\ExpressionField('IS_MINUTE', "IF(%s IS NOT NULL AND %s IS NOT NULL,'Y','N')", array("MINUTE", "HOUR"))
			)
		))->fetch();
		if ($arDateStat) {
			StatDomainsTable::update($arDateStat['ID'], array(
				$updateField => $arDateStat[$updateField] + 1
			));
		} else {
			StatDomainsTable::add(array(
				"DOMAIN_ID" => $domainId,
				"SEND_DATE" => new DateTime(ConvertTimeStamp($sendTime)),
				"HOUR" => date("H", $sendTime),
				"MINUTE" => date("i", $sendTime),
				$updateField => 1
			));
		}
	}

	public static function isAllowLimitAccount($accountId)
	{
		if (!self::useLimits()) {
			return true;
		}
		$arAccount = \Ammina\SMTP\AccountsTable::getAccountInfo($accountId);
		if ($arAccount) {
			$limitDay = \COption::GetOptionInt("ammina.smtp", "limits_account_day", 3000);
			$limitHour = \COption::GetOptionInt("ammina.smtp", "limits_account_hour", 125);
			$limitMinute = \COption::GetOptionInt("ammina.smtp", "limits_account_minute", 2);

			if ($arAccount['LIMIT_DAY'] < 0 || $arAccount['LIMIT_DAY'] > 0) {
				$limitDay = $arAccount['LIMIT_DAY'];
			}
			if ($arAccount['LIMIT_HOUR'] < 0 || $arAccount['LIMIT_HOUR'] > 0) {
				$limitHour = $arAccount['LIMIT_HOUR'];
			}
			if ($arAccount['LIMIT_MINUTE'] < 0 || $arAccount['LIMIT_MINUTE'] > 0) {
				$limitMinute = $arAccount['LIMIT_MINUTE'];
			}
			$currentTime = time();
			$arFind = array();
			if ($limitDay > 0) {
				$arFind[] = array(
					"SEND_DATE" => ConvertTimeStamp($currentTime, "SHORT"),
					"IS_DAY" => "Y"
				);
			}
			if ($limitHour > 0) {
				$arFind[] = array(
					"SEND_DATE" => ConvertTimeStamp($currentTime, "SHORT"),
					"HOUR" => date("H", $currentTime),
					"IS_HOUR" => "Y"
				);
			}
			if ($limitDay > 0) {
				$arFind[] = array(
					"SEND_DATE" => ConvertTimeStamp($currentTime, "SHORT"),
					"HOUR" => date("H", $currentTime),
					"MINUTE" => date("i", $currentTime),
					"IS_MINUTE" => "Y"
				);
			}
			if (count($arFind) <= 0) {
				return self::isAllowLimitDomain($arAccount['DOMAIN_ID']);
			}
			$arFind['LOGIC'] = "OR";
			$rStat = StatAccountsTable::getList(array(
				"filter" => array(
					"ACCOUNT_ID" => $accountId,
					$arFind
				),
				"runtime" => array(
					new \Bitrix\Main\ORM\Fields\ExpressionField('IS_DAY', "IF(%s IS NULL AND %s IS NULL,'Y','N')", array("MINUTE", "HOUR")),
					new \Bitrix\Main\ORM\Fields\ExpressionField('IS_HOUR', "IF(%s IS NULL AND %s IS NOT NULL,'Y','N')", array("MINUTE", "HOUR")),
					new \Bitrix\Main\ORM\Fields\ExpressionField('IS_MINUTE', "IF(%s IS NOT NULL AND %s IS NOT NULL,'Y','N')", array("MINUTE", "HOUR"))
				)
			));
			$bAllowLimit = true;
			while ($arStat = $rStat->fetch()) {
				if ($arStat['HOUR'] !== null && $arStat['MINUTE'] !== null) {
					if ($limitMinute > 0 && $arStat['CNT_SEND'] >= $limitMinute) {
						$bAllowLimit = false;
					}
				} elseif ($arStat['HOUR'] !== null) {
					if ($limitHour > 0 && $arStat['CNT_SEND'] >= $limitHour) {
						$bAllowLimit = false;
					}
				} else {
					if ($limitDay > 0 && $arStat['CNT_SEND'] >= $limitDay) {
						$bAllowLimit = false;
					}
				}
			}
			if ($bAllowLimit && $arAccount['LIMIT_DOMAN_IGNORE'] !== "Y") {
				return self::isAllowLimitDomain($arAccount['DOMAIN_ID']);
			}
			return $bAllowLimit;
		}
		return true;
	}

	public static function isAllowLimitDomain($domainId)
	{
		$arDomain = \Ammina\SMTP\DomainsTable::getDomainInfo($domainId);
		if ($arDomain) {
			$limitDay = \COption::GetOptionInt("ammina.smtp", "limits_domain_day", 5000);
			$limitHour = \COption::GetOptionInt("ammina.smtp", "limits_domain_hour", 200);
			$limitMinute = \COption::GetOptionInt("ammina.smtp", "limits_domain_minute", 3);

			if ($arDomain['LIMIT_DAY'] < 0 || $arDomain['LIMIT_DAY'] > 0) {
				$limitDay = $arDomain['LIMIT_DAY'];
			}
			if ($arDomain['LIMIT_HOUR'] < 0 || $arDomain['LIMIT_HOUR'] > 0) {
				$limitHour = $arDomain['LIMIT_HOUR'];
			}
			if ($arDomain['LIMIT_MINUTE'] < 0 || $arDomain['LIMIT_MINUTE'] > 0) {
				$limitMinute = $arDomain['LIMIT_MINUTE'];
			}
			$currentTime = time();
			$arFind = array();
			if ($limitDay > 0) {
				$arFind[] = array(
					"SEND_DATE" => ConvertTimeStamp($currentTime, "SHORT"),
					"IS_DAY" => "Y"
				);
			}
			if ($limitHour > 0) {
				$arFind[] = array(
					"SEND_DATE" => ConvertTimeStamp($currentTime, "SHORT"),
					"HOUR" => date("H", $currentTime),
					"IS_HOUR" => "Y"
				);
			}
			if ($limitDay > 0) {
				$arFind[] = array(
					"SEND_DATE" => ConvertTimeStamp($currentTime, "SHORT"),
					"HOUR" => date("H", $currentTime),
					"MINUTE" => date("i", $currentTime),
					"IS_MINUTE" => "Y"
				);
			}
			if (count($arFind) <= 0) {
				return true;
			}
			$arFind['LOGIC'] = "OR";
			$rStat = StatDomainsTable::getList(array(
				"filter" => array(
					"DOMAIN_ID" => $domainId,
					$arFind
				),
				"runtime" => array(
					new \Bitrix\Main\ORM\Fields\ExpressionField('IS_DAY', "IF(%s IS NULL AND %s IS NULL,'Y','N')", array("MINUTE", "HOUR")),
					new \Bitrix\Main\ORM\Fields\ExpressionField('IS_HOUR', "IF(%s IS NULL AND %s IS NOT NULL,'Y','N')", array("MINUTE", "HOUR")),
					new \Bitrix\Main\ORM\Fields\ExpressionField('IS_MINUTE', "IF(%s IS NOT NULL AND %s IS NOT NULL,'Y','N')", array("MINUTE", "HOUR"))
				)
			));
			$bAllowLimit = true;
			while ($arStat = $rStat->fetch()) {
				if ($arStat['HOUR'] !== null && $arStat['MINUTE'] !== null) {
					if ($limitMinute > 0 && $arStat['CNT_SEND'] >= $limitMinute) {
						$bAllowLimit = false;
					}
				} elseif ($arStat['HOUR'] !== null) {
					if ($limitHour > 0 && $arStat['CNT_SEND'] >= $limitHour) {
						$bAllowLimit = false;
					}
				} else {
					if ($limitDay > 0 && $arStat['CNT_SEND'] >= $limitDay) {
						$bAllowLimit = false;
					}
				}
			}
			return $bAllowLimit;
		}
		return true;
	}
}
