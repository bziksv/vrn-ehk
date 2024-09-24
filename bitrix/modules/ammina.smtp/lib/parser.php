<?

namespace Ammina\SMTP;

use PHPMailer\PHPMailer\PHPMailer;

class Parser
{
	protected $strOriginalTo;
	protected $strOriginalSubject;
	protected $strOriginalMessage;
	protected $strOriginalAdditionalHeaders;
	protected $strOriginalAdditionalParameters;
	protected $strCurrentPartHeaderCharset = SITE_CHARSET;
	protected $strCurrentPartHeaderContentType = "";
	protected $strCurrentPartHeaderName = "";
	protected $strCurrentPartHeaderBoundary = "";
	protected $strCurrentSubPartHeaderCharset = SITE_CHARSET;
	protected $strCurrentSubPartHeaderContentType = "";
	protected $strCurrentSubPartHeaderName = "";
	protected $strCurrentSubPartHeaderBoundary = "";
	public $strFieldTo = "";
	public $strFieldToName = "";
	public $strFieldReplyTo = "";
	public $strFieldReplyToName = "";
	public $strFieldCC = "";
	public $strFieldCCName = "";
	public $strFieldBCC = "";
	public $strFieldBCCName = "";

	public $strFieldSubject = "";
	public $arFieldsHeaders = array();
	public $arContents = array();
	public $strHeaderCharset = SITE_CHARSET;
	public $strHeaderContentType = "";
	public $strHeaderBoundary = "";
	public $strFieldFrom = "";
	public $strFieldFromName = "";
	public $strMessage = "";
	public $strAltMessage = "";
	public $bIsHtmlMessage = false;
	public $arAttachment = array();
	public $messageId = false;

	function __construct($to, $subject, $message, $additionalHeaders = '', $additionalParameters = '')
	{
		$this->strOriginalTo = $to;
		$this->strOriginalSubject = $subject;
		$this->strOriginalMessage = $message;
		$this->strOriginalAdditionalHeaders = $additionalHeaders;
		$this->strOriginalAdditionalParameters = $additionalParameters;
	}

	public function doParse()
	{
		list($this->strFieldTo, $this->strFieldToName) = $this->doNormalizeAndParseEmail($this->strOriginalTo);
		$this->strFieldSubject = $this->doNormalizeAndParseString($this->strOriginalSubject);
		$this->arFieldsHeaders = $this->doNormalizeAndParseHeaders($this->strOriginalAdditionalHeaders);
		$this->doParseMessage();
	}

	protected function doNormalizeAndParseString($strValue)
	{
		if (preg_match('/=\?[\\d,\\w,-]*\?[q,Q,b,B]?.*\?=/', $strValue)) {
			$strEncode = SITE_CHARSET;
			if (amsmtp_stripos($strValue, '=?' . $this->strHeaderCharset . '?') !== false) {
				$strEncode = $this->strHeaderCharset;
			} elseif (amsmtp_stripos($strValue, '=?windows-1251?') !== false) {
				$strEncode = "windows-1251";
			} elseif (amsmtp_stripos($strValue, '=?utf-8?') !== false) {
				$strEncode = "utf-8";
			}
			$strValue = iconv_mime_decode($strValue, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, $strEncode);
		}
		return $strValue;
	}

	protected function doNormalizeAndParseEmail($strValue)
	{
		$oMailer = new \PHPMailer();
		$arAddress = $oMailer->parseAddresses($strValue);
		$arEmail = array();
		$arName = array();
		foreach ($arAddress as $k => $v) {
			$arEmail[] = $v['address'];
			$arName[] = $v['name'];
		}
		return array($arEmail, $arName);
	}

	protected function doNormalizeAndParseHeaders($strHeaders)
	{
		$strHeaders = str_replace(["\r\n", "\r"], "\n", $strHeaders);
		$arHeaders = explode("\n", $strHeaders);
		foreach ($arHeaders as $strLine) {
			$arLine = explode(": ", $strLine);
			if (trim(amsmtp_strtolower($arLine[0])) === "content-type") {
				unset($arLine[0]);
				$strLine = implode(": ", $arLine);
				$arLine = explode("; ", $strLine);
				$this->strHeaderContentType = $arLine[0];
				foreach ($arLine as $strPart) {
					if (amsmtp_strpos(amsmtp_strtolower($strPart), "charset=") === 0) {
						$this->strHeaderCharset = amsmtp_substr($strPart, 8);
					} elseif (amsmtp_strpos(amsmtp_strtolower($strPart), "boundary=\"") === 0) {
						$this->strHeaderBoundary = amsmtp_substr($strPart, 10, amsmtp_strlen($strPart) - 11);
					}
				}
				break;
			}
		}
		if (!preg_match('/=\?[\\d,\\w,-]*\?[q,Q,b,B]?.*\?=/', $strHeaders)) {
			$arData = explode("\n", $strHeaders);
			$arNormalizeLines = array();
			$strCurrentField = "";
			foreach ($arData as $val) {
				if (amsmtp_strpos($val, ": ") === false) {
					$strCurrentField .= $val;
				} else {
					if (amsmtp_strlen($strCurrentField) > 0) {
						$arNormalizeLines[] = $strCurrentField;
					}
					$strCurrentField = $val;
				}
			}
			if (amsmtp_strlen($strCurrentField) > 0) {
				$arNormalizeLines[] = $strCurrentField;
			}
			foreach ($arNormalizeLines as $k => $v) {
				$arVal = explode(": ", $v);
				$strName = $arVal[0];
				unset($arVal[0]);
				$strValue = implode(": ", $arVal);
				$arNormalizeLines[$k] = iconv_mime_encode($strName, $strValue);
			}
			$strHeaders = implode("\n", $arNormalizeLines);
		}
		$arHeadersOriginal = iconv_mime_decode_headers($strHeaders, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, $this->strHeaderCharset);
		$arHeaders = array();
		$strFrom = "";
		$this->messageId = false;
		foreach ($arHeadersOriginal as $k => $v) {
			$arHeaders[amsmtp_strtolower($k)] = array(
				"NAME" => $k,
				"VALUE" => $v,
			);
			if (amsmtp_strtolower($k) === "from") {
				$strFrom = $v;
			} elseif (amsmtp_strtolower($k) === "message-id") {
				$this->messageId = $v;
				unset($arHeaders[amsmtp_strtolower($k)]);
			} elseif (amsmtp_strtolower($k) === "reply-to") {
				list($this->strFieldReplyTo, $this->strFieldReplyToName) = $this->doNormalizeAndParseEmail($v);
				unset($arHeaders[amsmtp_strtolower($k)]);
			} elseif (amsmtp_strtolower($k) === "cc") {
				list($this->strFieldCC, $this->strFieldCCName) = $this->doNormalizeAndParseEmail($v);
				unset($arHeaders[amsmtp_strtolower($k)]);
			} elseif (amsmtp_strtolower($k) === "bcc") {
				list($this->strFieldBCC, $this->strFieldBCCName) = $this->doNormalizeAndParseEmail($v);
				unset($arHeaders[amsmtp_strtolower($k)]);
			}
		}
		if (amsmtp_strlen($strFrom) <= 0) {
			$arSite = \CSite::GetArrayByID(SITE_ID);
			if ($arSite) {
				$strFrom = $arSite['EMAIL'];
			} else {
				$strFrom = \COption::GetOptionString("main", "email_from", $strFrom);
			}
		}
		$this->strFieldFrom = $strFrom;
		if (amsmtp_strpos($strFrom, '<') !== false) {
			$this->strFieldFromName = trim(amsmtp_substr($strFrom, 0, amsmtp_strpos($strFrom, '<')));
			$strFrom = amsmtp_substr($strFrom, amsmtp_strpos($strFrom, '<') + 1);
			$this->strFieldFrom = amsmtp_substr($strFrom, 0, amsmtp_strpos($strFrom, ">"));
		}
		return $arHeaders;
	}

	protected function doParseMessage()
	{
		if (amsmtp_strpos($this->strHeaderContentType, "multipart/") === 0) {
			if ($this->strHeaderContentType === "multipart/alternative") {
				$arContent = explode("--" . $this->strHeaderBoundary, $this->strOriginalMessage);
				foreach ($arContent as $k => $v) {
					if ($k > 0 && $k < (count($arContent) - 1)) {
						$this->strCurrentPartHeaderContentType = "";
						$this->strCurrentPartHeaderName = "";
						$this->strCurrentPartHeaderCharset = SITE_CHARSET;
						$this->strCurrentPartHeaderBoundary = "";
						list($arPartHeader, $strPartContent) = $this->doParseMessagePart(trim($v));
						if ($this->strCurrentPartHeaderContentType === "text/plain") {
							$this->strAltMessage = $strPartContent;
						} elseif ($this->strCurrentPartHeaderContentType === "text/html") {
							$this->strMessage = $strPartContent;
							$this->bIsHtmlMessage = true;
						} elseif ($this->strCurrentPartHeaderContentType === "multipart/related") {
							$arChildContent = explode("--" . $this->strCurrentPartHeaderBoundary, $v);
							foreach ($arChildContent as $k1 => $v1) {
								if ($k1 > 0 && $k1 < (count($arChildContent) - 1)) {
									$this->strCurrentSubPartHeaderContentType = "";
									$this->strCurrentSubPartHeaderName = "";
									$this->strCurrentSubPartHeaderCharset = SITE_CHARSET;
									$this->strCurrentSubPartHeaderBoundary = "";
									list($arSubPartHeader, $strSubPartContent) = $this->doParseMessageSubPart(trim($v1));
									if ($this->strCurrentSubPartHeaderContentType === "text/html") {
										$this->strMessage = $strSubPartContent;
										$this->bIsHtmlMessage = true;
									} else {
										if (isset($arSubPartHeader['content-disposition']) && (amsmtp_strpos($arSubPartHeader['content-disposition']['VALUE'], 'attachment;') !== false)) {
											if ($arSubPartHeader['content-transfer-encoding']['VALUE'] === "base64") {
												$strSubPartContent = base64_decode($strSubPartContent);
											}
											$this->arAttachment[] = array(
												"headers" => $arSubPartHeader,
												"content-type" => $this->strCurrentSubPartHeaderContentType,
												"name" => $this->strCurrentSubPartHeaderName,
												"charset" => $this->strCurrentSubPartHeaderCharset,
												"content" => $strSubPartContent,
											);
										}
									}
								}
							}
						}
					}
				}
			} elseif ($this->strHeaderContentType === "multipart/mixed") {
				$arContent = explode("--" . $this->strHeaderBoundary, $this->strOriginalMessage);
				foreach ($arContent as $k => $v) {
					if ($k > 0 && $k < (count($arContent) - 1)) {
						$this->strCurrentPartHeaderContentType = "";
						$this->strCurrentPartHeaderName = "";
						$this->strCurrentPartHeaderCharset = SITE_CHARSET;
						$this->strCurrentPartHeaderBoundary = "";
						list($arPartHeader, $strPartContent) = $this->doParseMessagePart(trim($v));
						if ($this->strCurrentPartHeaderContentType === "multipart/alternative") {
							$arChildContent = explode("--" . $this->strCurrentPartHeaderBoundary, $strPartContent);
							foreach ($arChildContent as $k1 => $v1) {
								if ($k1 > 0 && $k1 < (count($arChildContent) - 1)) {
									$this->strCurrentPartHeaderContentType = "";
									$this->strCurrentPartHeaderName = "";
									$this->strCurrentPartHeaderCharset = SITE_CHARSET;
									$this->strCurrentPartHeaderBoundary = "";
									list($arChildPartHeader, $strChildPartContent) = $this->doParseMessagePart(trim($v1));
									if ($this->strCurrentPartHeaderContentType === "text/plain") {
										$this->strAltMessage = $strChildPartContent;
									} elseif ($this->strCurrentPartHeaderContentType === "text/html") {
										$this->strMessage = $strChildPartContent;
										$this->bIsHtmlMessage = true;
									}
								}
							}
						} elseif (amsmtp_strlen($this->strCurrentPartHeaderName) > 0) {
							if ($arPartHeader['content-transfer-encoding']['VALUE'] === "base64") {
								$strPartContent = base64_decode($strPartContent);
							}
							$this->arAttachment[] = array(
								"headers" => $arPartHeader,
								"content-type" => $this->strCurrentPartHeaderContentType,
								"name" => $this->strCurrentPartHeaderName,
								"charset" => $this->strCurrentPartHeaderCharset,
								"content" => $strPartContent,
							);
						} elseif ($this->strCurrentPartHeaderContentType === "text/plain") {
							$this->strMessage = $strPartContent;
						} elseif ($this->strCurrentPartHeaderContentType === "text/html") {
							$this->strMessage = $strPartContent;
							$this->bIsHtmlMessage = true;
						}
					}
				}
			} elseif ($this->strHeaderContentType === "multipart/related") {
				$arContent = explode("--" . $this->strHeaderBoundary, $this->strOriginalMessage);
				foreach ($arContent as $k => $v) {
					if ($k > 0 && $k < (count($arContent) - 1)) {
						$this->strCurrentPartHeaderContentType = "";
						$this->strCurrentPartHeaderName = "";
						$this->strCurrentPartHeaderCharset = SITE_CHARSET;
						$this->strCurrentPartHeaderBoundary = "";
						list($arPartHeader, $strPartContent) = $this->doParseMessagePart(trim($v));
						if (amsmtp_strlen($this->strCurrentPartHeaderName) > 0) {
							if ($arPartHeader['content-transfer-encoding']['VALUE'] === "base64") {
								$strPartContent = base64_decode($strPartContent);
							}
							$this->arAttachment[] = array(
								"headers" => $arPartHeader,
								"content-type" => $this->strCurrentPartHeaderContentType,
								"name" => $this->strCurrentPartHeaderName,
								"charset" => $this->strCurrentPartHeaderCharset,
								"content" => $strPartContent,
							);
						} elseif ($this->strCurrentPartHeaderContentType === "text/plain") {
							$this->strMessage = $strPartContent;
						} elseif ($this->strCurrentPartHeaderContentType === "text/html") {
							$this->strMessage = $strPartContent;
							$this->bIsHtmlMessage = true;
						}
					}
				}
			}
		} else {
			if ($this->strHeaderContentType === "text/html") {
				$this->bIsHtmlMessage = true;
			}
			if (preg_match('/=\?[\\d,\\w,-]*\?[q,Q,b,B]?.*\?=/', $this->strOriginalMessage)) {
				$this->strMessage = iconv_mime_decode($this->strOriginalMessage, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, $this->strHeaderCharset);
			} else {
				$this->strMessage = $this->strOriginalMessage;
			}
		}
	}

	protected function doParseMessagePart($strContent)
	{
		$strPartHeader = "";
		$strPartContent = "";
		$strContent = str_replace(["\r\n", "\r"], "\n", $strContent);
		$pos = amsmtp_strpos($strContent, "\n\n");
		$strPartHeader = amsmtp_substr($strContent, 0, $pos);
		$strPartContent = amsmtp_substr($strContent, $pos + 2);
		$arPartHeader = $this->doNormalizeAndParsePartHeaders($strPartHeader);
		if (preg_match('/=\?[\\d,\\w,-]*\?[q,Q,b,B]?.*\?=/', $strPartContent)) {
			$strPartContent = iconv_mime_decode($strPartContent, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, $this->strCurrentPartHeaderCharset);
		}
		return array($arPartHeader, $strPartContent);
	}

	protected function doNormalizeAndParsePartHeaders($strHeaders)
	{
		$strHeaders = str_replace(["\r\n", "\r"], "\n", $strHeaders);
		$arHeaders = explode("\n", $strHeaders);
		foreach ($arHeaders as $strLine) {
			$arLine = explode(": ", $strLine);
			if (trim(amsmtp_strtolower($arLine[0])) === "content-type") {
				unset($arLine[0]);
				$strLine = implode(": ", $arLine);
				$arLine = explode("; ", $strLine);
				$this->strCurrentPartHeaderContentType = $arLine[0];
				foreach ($arLine as $strPart) {
					if (amsmtp_strpos(amsmtp_strtolower($strPart), "charset=") === 0) {
						$this->strCurrentPartHeaderCharset = amsmtp_substr($strPart, 8);
					} elseif (amsmtp_strpos(amsmtp_strtolower($strPart), "boundary=\"") === 0) {
						$this->strCurrentPartHeaderBoundary = amsmtp_substr($strPart, 10, amsmtp_strlen($strPart) - 11);
					} elseif (amsmtp_strpos(amsmtp_strtolower($strPart), "name=\"") === 0) {
						$this->strCurrentPartHeaderName = amsmtp_substr($strPart, 6, amsmtp_strlen($strPart) - 7);
						if (preg_match('/=\?[\\d,\\w,-]*\?[q,Q,b,B]?.*\?=/', $this->strCurrentPartHeaderName)) {
							$this->strCurrentPartHeaderName = iconv_mime_decode($this->strCurrentPartHeaderName, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, $this->strCurrentPartHeaderCharset);
						}
					}
				}
				break;
			}
		}
		if (!preg_match('/=\?[\\d,\\w,-]*\?[q,Q,b,B]?.*\?=/', $strHeaders)) {
			$arData = explode("\n", $strHeaders);
			$arNormalizeLines = array();
			$strCurrentField = "";
			foreach ($arData as $val) {
				if (amsmtp_strpos($val, ": ") === false) {
					$strCurrentField .= $val;
				} else {
					if (amsmtp_strlen($strCurrentField) > 0) {
						$arNormalizeLines[] = $strCurrentField;
					}
					$strCurrentField = $val;
				}
			}
			if (amsmtp_strlen($strCurrentField) > 0) {
				$arNormalizeLines[] = $strCurrentField;
			}
			foreach ($arNormalizeLines as $k => $v) {
				$arVal = explode(": ", $v);
				$strName = $arVal[0];
				unset($arVal[0]);
				$strValue = implode(": ", $arVal);
				$arNormalizeLines[$k] = iconv_mime_encode($strName, $strValue);
			}
			$strHeaders = implode("\n", $arNormalizeLines);
		}
		$arHeadersOriginal = iconv_mime_decode_headers($strHeaders, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, $this->strCurrentPartHeaderCharset);
		$arHeaders = array();
		foreach ($arHeadersOriginal as $k => $v) {
			$arHeaders[amsmtp_strtolower($k)] = array(
				"NAME" => $k,
				"VALUE" => $v,
			);
		}
		return $arHeaders;
	}

	protected function doParseMessageSubPart($strContent)
	{
		$strPartHeader = "";
		$strPartContent = "";
		$strContent = str_replace(["\r\n", "\r"], "\n", $strContent);
		$pos = amsmtp_strpos($strContent, "\n\n");
		$strPartHeader = amsmtp_substr($strContent, 0, $pos);
		$strPartContent = amsmtp_substr($strContent, $pos + 2);
		$arPartHeader = $this->doNormalizeAndParseSubPartHeaders($strPartHeader);
		if (preg_match('/=\?[\\d,\\w,-]*\?[q,Q,b,B]?.*\?=/', $strPartContent)) {
			$strPartContent = iconv_mime_decode($strPartContent, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, $this->strCurrentSubPartHeaderCharset);
		}
		return array($arPartHeader, $strPartContent);
	}

	protected function doNormalizeAndParseSubPartHeaders($strHeaders)
	{
		$strHeaders = str_replace(["\r\n", "\r"], "\n", $strHeaders);
		$arHeaders = explode("\n", $strHeaders);
		foreach ($arHeaders as $strLine) {
			$arLine = explode(": ", $strLine);
			if (trim(amsmtp_strtolower($arLine[0])) === "content-type") {
				unset($arLine[0]);
				$strLine = implode(": ", $arLine);
				$arLine = explode("; ", $strLine);
				$this->strCurrentSubPartHeaderContentType = $arLine[0];
				foreach ($arLine as $strPart) {
					if (amsmtp_strpos(amsmtp_strtolower($strPart), "charset=") === 0) {
						$this->strCurrentSubPartHeaderCharset = amsmtp_substr($strPart, 8);
					} elseif (amsmtp_strpos(amsmtp_strtolower($strPart), "boundary=\"") === 0) {
						$this->strCurrentSubPartHeaderBoundary = amsmtp_substr($strPart, 10, amsmtp_strlen($strPart) - 11);
					} elseif (amsmtp_strpos(amsmtp_strtolower($strPart), "name=\"") === 0) {
						$this->strCurrentSubPartHeaderName = amsmtp_substr($strPart, 6, amsmtp_strlen($strPart) - 7);
						if (preg_match('/=\?[\\d,\\w,-]*\?[q,Q,b,B]?.*\?=/', $this->strCurrentSubPartHeaderName)) {
							$this->strCurrentSubPartHeaderName = iconv_mime_decode($this->strCurrentSubPartHeaderName, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, $this->strCurrentPartHeaderCharset);
						}
					}
				}
				break;
			}
		}
		if (!preg_match('/=\?[\\d,\\w,-]*\?[q,Q,b,B]?.*\?=/', $strHeaders)) {
			$arData = explode("\n", $strHeaders);
			$arNormalizeLines = array();
			$strCurrentField = "";
			foreach ($arData as $val) {
				if (amsmtp_strpos($val, ": ") === false) {
					$strCurrentField .= $val;
				} else {
					if (amsmtp_strlen($strCurrentField) > 0) {
						$arNormalizeLines[] = $strCurrentField;
					}
					$strCurrentField = $val;
				}
			}
			if (amsmtp_strlen($strCurrentField) > 0) {
				$arNormalizeLines[] = $strCurrentField;
			}
			foreach ($arNormalizeLines as $k => $v) {
				$arVal = explode(": ", $v);
				$strName = $arVal[0];
				unset($arVal[0]);
				$strValue = implode(": ", $arVal);
				$arNormalizeLines[$k] = iconv_mime_encode($strName, $strValue);
			}
			$strHeaders = implode("\n", $arNormalizeLines);
		}
		$arHeadersOriginal = iconv_mime_decode_headers($strHeaders, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, $this->strCurrentSubPartHeaderCharset);
		$arHeaders = array();
		foreach ($arHeadersOriginal as $k => $v) {
			$arHeaders[amsmtp_strtolower($k)] = array(
				"NAME" => $k,
				"VALUE" => $v,
			);
		}
		return $arHeaders;
	}
}