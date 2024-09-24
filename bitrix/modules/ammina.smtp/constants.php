<?

class CAmminaSmtpConstants
{
	static public $DEFAULT_SERVERS_MATH = array(
		"imap.yandex.ru|pop.yandex.ru=smtp.yandex.ru:465|S",
		"imap.mail.ru|pop.mail.ru=smtp.mail.ru:465|S",
		"imap.rambler.ru|pop.rambler.ru=smtp.rambler.ru:465|S",
		"imap.gmail.com=smtp.gmail.com:465|S"
	);
	static public $DEFAULT_PORT_MATH = array(
		"110|143=25|N",
		"993|995=465|S"
	);
	static public $DEFAULT_UPDATE_FIELDS = array(
		"ACTIVE",
		"SMTP_HOST",
		"SMTP_PORT",
		"SECURE_TYPE",
		"SENDER_NAME",
		"ACCOUNT_LOGIN",
		"ACCOUNT_PASSWORD",
	);
}