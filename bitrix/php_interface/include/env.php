<?php
if (!function_exists('vrnEhkIsLocalEnv'))
{
	function vrnEhkIsLocalEnv()
	{
		$host = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : '';
		return in_array($host, array(
			'localhost:8089',
			'127.0.0.1:8089',
			'localhost',
			'127.0.0.1',
		), true);
	}
}

if (!function_exists('vrnEhkVerifyRecaptcha'))
{
	function vrnEhkVerifyRecaptcha($token)
	{
		if (vrnEhkIsLocalEnv())
		{
			return true;
		}

		if (!$token)
		{
			return false;
		}

		$secret = '6Le8ZZ4aAAAAABGjJMS6tsYCxXZwiWZJ39j9KE1Q';
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret='
			.urlencode($secret)
			.'&response='.urlencode($token)
			.'&remoteip='.urlencode($_SERVER['REMOTE_ADDR']);

		$response = json_decode(file_get_contents($url), true);
		if (empty($response['success']))
		{
			return false;
		}

		if (isset($response['score']) && $response['score'] <= 0.7)
		{
			return false;
		}

		return true;
	}
}

if (!function_exists('vrnEhkNormalizeRuPhone'))
{
	function vrnEhkNormalizeRuPhone($phone)
	{
		$digits = preg_replace('/\D+/', '', (string)$phone);
		if (strlen($digits) === 11 && $digits[0] === '8')
		{
			$digits = '7'.substr($digits, 1);
		}
		if (strlen($digits) === 10)
		{
			$digits = '7'.$digits;
		}

		return $digits;
	}
}

if (!function_exists('vrnEhkIsValidRuPhone'))
{
	function vrnEhkIsValidRuPhone($phone)
	{
		$digits = vrnEhkNormalizeRuPhone($phone);

		return (bool)preg_match('/^7[3-9]\d{9}$/', $digits);
	}
}

if (!function_exists('vrnEhkFormatRuPhone'))
{
	function vrnEhkFormatRuPhone($phone)
	{
		$digits = vrnEhkNormalizeRuPhone($phone);
		if (!preg_match('/^7(\d{3})(\d{3})(\d{2})(\d{2})$/', $digits, $matches))
		{
			return $phone;
		}

		return '+7 ('.$matches[1].') '.$matches[2].'-'.$matches[3].'-'.$matches[4];
	}
}
