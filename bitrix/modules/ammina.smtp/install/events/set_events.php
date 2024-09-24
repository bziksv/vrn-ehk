<?

global $DB;

use Bitrix\Main\Localization\Loc;

$messages = Loc::loadLanguageFile(__FILE__);

if (!empty($messages)) {
	$listEventType = "'AMMINA_SMTP_TEST'";
	$rs = $DB->query(
		'SELECT count(*) CNT FROM b_event_type WHERE EVENT_NAME IN (' . $listEventType . ')',
		false,
		'File: ' . __FILE__ . '<br>Line: ' . __LINE__
	);
	$ar = $rs->fetch();
	if ($ar['CNT'] <= 0) {
		$template = '#TEXT#';
 
		$eventType = new CEventType;
		$eventMessage = new CEventMessage;
		$listEventName = array('AMMINA_SMTP_TEST');

		$languageIterator = Bitrix\Main\Localization\LanguageTable::getList(
			array(
				'select' => array('ID'),
				'filter' => array('=ACTIVE' => 'Y')
			)
		);
		while ($lang = $languageIterator->fetch()) {
			$sites = array();
			$siteIterator = Bitrix\Main\SiteTable::getList(
				array(
					'select' => array('LID'),
					'filter' => array('LANGUAGE_ID' => $lang['ID'])
				)
			);
			while ($site = $siteIterator->fetch()) {
				$sites[] = $site['LID'];
			}

			foreach ($listEventName as $eventName) {
				$message = str_replace(
					array(
						'#TEXT#'
					),
					array(
						$messages[$eventName . '_TEXT']
					),
					$template
				);

				$eventType->add(
					array(
						'LID' => $lang['ID'],
						'EVENT_NAME' => $eventName,
						'NAME' => $messages[$eventName . '_NAME'],
						'DESCRIPTION' => $messages[$eventName . '_DESC'],
					)
				);
				if (!empty($sites)) {
					$eventMessage->add(
						array(
							'ACTIVE' => 'Y',
							'EVENT_NAME' => $eventName,
							'LID' => $sites,
							'EMAIL_FROM' => '#FROM_EMAIL#',
							'EMAIL_TO' => '#EMAIL#',
							'BCC' => '',
							'SUBJECT' => $messages[$eventName . '_SUBJECT'],
							'MESSAGE' => $message,
							'BODY_TYPE' => 'html',
						)
					);
				}
			}
		}
	}
}