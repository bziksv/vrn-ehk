<?

global $DB;

$DB->Query(
	"DELETE FROM b_event_type WHERE EVENT_NAME in (
	'AMMINA_SMTP_TEST'
	)"
);

$DB->Query(
	"DELETE FROM b_event_message WHERE EVENT_NAME in (
	'AMMINA_SMTP_TEST'
	)"
);