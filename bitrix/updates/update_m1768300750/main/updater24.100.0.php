<?php
/**
 * @var CUpdater $updater
 * @var CDatabase $DB
 */

if ($updater->CanUpdateDatabase())
{
	if ($DB->type == 'MYSQL')
	{
		if ($DB->GetIndexName('b_cache_tag', ['SITE_ID', 'CACHE_SALT', 'RELATIVE_PATH', 'TAG'], true) !== 'ix_init_tag')
		{
			$DB->Query('ALTER TABLE b_cache_tag ADD INDEX ix_init_tag (SITE_ID, CACHE_SALT, RELATIVE_PATH, TAG)');
		}

		if ($DB->GetIndexName('b_cache_tag', ['RELATIVE_PATH'], true) !== 'ix_relative_path')
		{
			$DB->Query('ALTER TABLE b_cache_tag ADD INDEX ix_relative_path (RELATIVE_PATH)');
		}

		if ($DB->GetIndexName('b_cache_tag', ['TAG', 'RELATIVE_PATH'], true) !== 'ix_tag_relative_path')
		{
			$DB->Query('ALTER TABLE b_cache_tag ADD INDEX ix_tag_relative_path (TAG, RELATIVE_PATH)');
		}

		if ($DB->GetIndexName('b_cache_tag', ['SITE_ID', 'CACHE_SALT', 'RELATIVE_PATH'], true) === 'ix_b_cache_tag_0')
		{
			$DB->Query('ALTER TABLE b_cache_tag DROP INDEX ix_b_cache_tag_0');
		}

		if ($DB->GetIndexName('b_cache_tag', ['TAG'], true) === 'ix_b_cache_tag_1')
		{
			$DB->Query('ALTER TABLE b_cache_tag DROP INDEX ix_b_cache_tag_1');
		}
	}

	if ($DB->type == 'PGSQL')
	{
		if ($DB->GetIndexName('b_cache_tag', ['SITE_ID', 'CACHE_SALT', 'RELATIVE_PATH', 'TAG'], true) !== 'ix_b_cache_tag_site_id_cache_salt_relative_path_tag')
		{
			$DB->Query('CREATE INDEX ix_b_cache_tag_site_id_cache_salt_relative_path_tag ON b_cache_tag (SITE_ID, CACHE_SALT, RELATIVE_PATH, TAG)');
		}

		if ($DB->GetIndexName('b_cache_tag', ['RELATIVE_PATH'], true) !== 'ix_b_cache_tag_relative_path')
		{
			$DB->Query('CREATE INDEX ix_b_cache_tag_relative_path ON b_cache_tag (RELATIVE_PATH)');
		}

		if ($DB->GetIndexName('b_cache_tag', ['TAG', 'RELATIVE_PATH'], true) !== 'ix_b_cache_tag_tag_relative_path')
		{
			$DB->Query('CREATE INDEX ix_b_cache_tag_tag_relative_path ON b_cache_tag (TAG, RELATIVE_PATH)');
		}

		if ($DB->GetIndexName('b_cache_tag', ['SITE_ID', 'CACHE_SALT', 'RELATIVE_PATH'], true) === 'ix_b_cache_tag_site_id_cache_salt_relative_path')
		{
			$DB->Query('DROP INDEX ix_b_cache_tag_site_id_cache_salt_relative_path');
		}

		if ($DB->GetIndexName('b_cache_tag', ['TAG'], true) === 'ix_b_cache_tag_tag')
		{
			$DB->Query('DROP INDEX ix_b_cache_tag_tag');
		}
	}
}

if ($updater->canUpdateKernel())
{
	$filesToDelete = [
		'modules/main/install/components/bitrix/main.post.list/templates/.default/messages.php',
		'components/bitrix/main.post.list/templates/.default/messages.php',
		'modules/main/install/components/bitrix/main.userconsent.view/templates/.default/lang/ru',
		'components/bitrix/main.userconsent.view/templates/.default/lang/ru',
		'modules/main/lib/cli/ormannotatecommand.php',
		'modules/main/classes/mysql/agent.php',
		'modules/main/classes/mysql/favorites.php',
		'modules/main/classes/mysql/rating_rules.php',
		'modules/main/classes/mysql/ratings.php',
		'modules/main/classes/mysql/ratings_components.php',
		'modules/main/classes/mysql/short_uri.php',
		'modules/main/classes/mysql/user_counter.php',
		'modules/main/interface/favorite_menu.php',
		'modules/main/install/images/icons/calendar.gif',
		'images/icons/calendar.gif',
		'modules/main/install/tools/calendar.php',
		'tools/calendar.php',
		'modules/main/lang/de/tools/calendar.php',
		'modules/main/lang/en/tools/calendar.php',
		'modules/main/lang/ru/tools/calendar.php',
		'modules/main/tools/calendar.php',
	];
	foreach ($filesToDelete as $fileName)
	{
		CUpdateSystem::deleteDirFilesEx($_SERVER['DOCUMENT_ROOT'] . $updater->kernelPath . '/' . $fileName);
	}
}

if ($updater->canUpdateKernel())
{
	$updater->CopyFiles('install/components', 'components');
	$updater->CopyFiles('install/gadgets', 'gadgets');
	$updater->CopyFiles('install/js', 'js');
	$updater->CopyFiles('install/panel', 'panel');
}
