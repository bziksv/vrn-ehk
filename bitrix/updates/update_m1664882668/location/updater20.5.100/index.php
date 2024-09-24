<?php
if(IsModuleInstalled('location'))
{
	$updater->CopyFiles("install/js", "js");
}

$useGoogleApi = \Bitrix\Main\Config\Option::get('location', 'use_google_api', 'Y') === 'Y';
$isGoogleApiKeyEmpty = \Bitrix\Main\Config\Option::get('location', 'google_map_api_key', '') === '';
$isGoogleApiKeyBakendEmpty = \Bitrix\Main\Config\Option::get('location', 'google_map_api_key_backend', '') === '';

if(($isGoogleApiKeyEmpty || $isGoogleApiKeyBakendEmpty) && $useGoogleApi && IsModuleInstalled('location'))
{
	$messages = array();
	$firstLang = '';
	$defaultLang = '';
	$iterator = \Bitrix\Main\Localization\LanguageTable::getList(array(
		'select' => array('ID', 'DEF'),
		'filter' => array('=ACTIVE' => 'Y')
	));

	while ($row = $iterator->fetch())
	{
		if ($row['DEF'] === 'Y')
		{
			$defaultLang = $row['ID'];
		}
		if ($firstLang == '')
		{
			$firstLang = $row['ID'];;
		}

		$languageId = $row['ID'];

		\Bitrix\Main\Localization\Loc::loadLanguageFile(
			__FILE__,
			$languageId
		);

		$messages[$languageId] = \Bitrix\Main\Localization\Loc::getMessage(
			'LOCATION_UPDATER_API_KEY_EMPTY',
			[],
			$languageId
		);
	}

	if ($defaultLang == '')
	{
		$defaultLang = $firstLang;
	}

	if (!empty($messages[$defaultLang]))
	{
		CAdminNotify::Add(array(
			'MESSAGE' => $messages[$defaultLang],
			'TAG' => 'LOCATION_EMPTY_GOOGLE_API_KEY',
			'MODULE_ID' => 'location',
			'ENABLE_CLOSE' => 'Y',
			'PUBLIC_SECTION' => 'N',
			'LANG' => $messages,
			'NOTIFY_TYPE' => CAdminNotify::TYPE_NORMAL
		));
	}
	unset($defaultLang, $firstLang, $messages);
}