<?
IncludeModuleLangFile(__FILE__);
global $APPLICATION;
if (CMain::GetGroupRight("ammina.smtp") >= "R") {
	$aMenu = array(
		"parent_menu" => "global_menu_services",
		"section" => "ammina.smtp",
		"sort" => 10000,
		"text" => GetMessage("AMMINA_SMTP_MENU_TEXT"),
		"title" => GetMessage("AMMINA_SMTP_MENU_TITLE"),
		"icon" => "ammina_smtp_menu_icon",
		"page_icon" => "ammina_smtp_page_icon",
		"items_id" => "menu_ammina_smtp",
		"items" => array(
			array(
				"text" => GetMessage("AMMINA_SMTP_MENU_DOMAINS_TEXT"),
				"title" => GetMessage("AMMINA_SMTP_MENU_DOMAINS_TITLE"),
				"url" => "ammina.smtp.domains.php?lang=" . LANGUAGE_ID,
				"more_url" => array("ammina.smtp.domains.edit.php"),
			),
			array(
				"text" => GetMessage("AMMINA_SMTP_MENU_ACCOUNTS_TEXT"),
				"title" => GetMessage("AMMINA_SMTP_MENU_ACCOUNTS_TITLE"),
				"url" => "ammina.smtp.accounts.php?lang=" . LANGUAGE_ID,
				"more_url" => array("ammina.smtp.accounts.edit.php"),
			),
			array(
				"text" => GetMessage("AMMINA_SMTP_MENU_TEST_TEXT"),
				"title" => GetMessage("AMMINA_SMTP_MENU_TEST_TITLE"),
				"url" => "ammina.smtp.test.php?lang=" . LANGUAGE_ID,
				"more_url" => array(),
			),
			array(
				"text" => GetMessage("AMMINA_SMTP_MENU_QUEUE_TEXT"),
				"title" => GetMessage("AMMINA_SMTP_MENU_QUEUE_TITLE"),
				"url" => "ammina.smtp.queue.php?lang=" . LANGUAGE_ID,
				"more_url" => array(
					"ammina.smtp.queue.view.php"
				),
			),
			array(
				"text" => GetMessage("AMMINA_SMTP_MENU_STAT_DOMAINS_TEXT"),
				"title" => GetMessage("AMMINA_SMTP_MENU_STAT_DOMAINS_TITLE"),
				"items_id" => "menu_ammina_smtp_stat_domains",
				"items" => array(
					array(
						"text" => GetMessage("AMMINA_SMTP_MENU_STAT_DOMAINS_DAY_TEXT"),
						"title" => GetMessage("AMMINA_SMTP_MENU_STAT_DOMAINS_DAY_TITLE"),
						"url" => "ammina.smtp.stat.domains.php?lang=" . LANGUAGE_ID . "&type=d",
					),
					array(
						"text" => GetMessage("AMMINA_SMTP_MENU_STAT_DOMAINS_HOUR_TEXT"),
						"title" => GetMessage("AMMINA_SMTP_MENU_STAT_DOMAINS_HOUR_TITLE"),
						"url" => "ammina.smtp.stat.domains.php?lang=" . LANGUAGE_ID . "&type=h",
					),
					array(
						"text" => GetMessage("AMMINA_SMTP_MENU_STAT_DOMAINS_MINUTE_TEXT"),
						"title" => GetMessage("AMMINA_SMTP_MENU_STAT_DOMAINS_MINUTE_TITLE"),
						"url" => "ammina.smtp.stat.domains.php?lang=" . LANGUAGE_ID . "&type=m",
					),
				)
			),
			array(
				"text" => GetMessage("AMMINA_SMTP_MENU_STAT_ACCOUNTS_TEXT"),
				"title" => GetMessage("AMMINA_SMTP_MENU_STAT_ACCOUNTS_TITLE"),
				"items_id" => "menu_ammina_smtp_stat_accounts",
				"items" => array(
					array(
						"text" => GetMessage("AMMINA_SMTP_MENU_STAT_ACCOUNTS_DAY_TEXT"),
						"title" => GetMessage("AMMINA_SMTP_MENU_STAT_ACCOUNTS_DAY_TITLE"),
						"url" => "ammina.smtp.stat.accounts.php?lang=" . LANGUAGE_ID . "&type=d",
					),
					array(
						"text" => GetMessage("AMMINA_SMTP_MENU_STAT_ACCOUNTS_HOUR_TEXT"),
						"title" => GetMessage("AMMINA_SMTP_MENU_STAT_ACCOUNTS_HOUR_TITLE"),
						"url" => "ammina.smtp.stat.accounts.php?lang=" . LANGUAGE_ID . "&type=h",
					),
					array(
						"text" => GetMessage("AMMINA_SMTP_MENU_STAT_ACCOUNTS_MINUTE_TEXT"),
						"title" => GetMessage("AMMINA_SMTP_MENU_STAT_ACCOUNTS_MINUTE_TITLE"),
						"url" => "ammina.smtp.stat.accounts.php?lang=" . LANGUAGE_ID . "&type=m",
					),
				)
			),
		),
	);

	return $aMenu;
}
return false;
