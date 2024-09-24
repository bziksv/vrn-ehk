<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!defined("WIZARD_SITE_ID"))
	return;

if (!defined("WIZARD_SITE_DIR"))
	return;
 


$path = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/ru/"); 

$handle = @opendir($path);
if ($handle)
{
	while ($file = readdir($handle))
	{
		if (in_array($file, array(".", "..")))
			continue; 

		CopyDirFiles(
			$path.$file,
			WIZARD_SITE_PATH."/".$file,
			$rewrite = true, 
			$recursive = true,
			$delete_after_copy = false
		);
	}

}

WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("SITE_DIR" => WIZARD_SITE_DIR));

$arUrlRewrite = array(); 
if (file_exists(WIZARD_SITE_ROOT_PATH."/urlrewrite.php"))
	include(WIZARD_SITE_ROOT_PATH."/urlrewrite.php");

$arNewUrlRewrite = array(
	array(
		'CONDITION' => '#^'.WIZARD_SITE_DIR.'personal/order/#',
	    'RULE' => '',
	    'ID' => 'bitrix:sale.personal.order',
	    'PATH' => WIZARD_SITE_DIR.'personal/order/index.php',
	),
	array(
		'CONDITION' => '#^'.WIZARD_SITE_DIR.'personal/#',
	    'RULE' => '',
	    'ID' => 'bitrix:sale.personal.section',
	    'PATH' => WIZARD_SITE_DIR.'personal/index.php',
	),
	array(
		'CONDITION' => '#^'.WIZARD_SITE_DIR.'catalog/#',
	    'RULE' => '',
	    'ID' => 'bitrix:catalog',
	    'PATH' => WIZARD_SITE_DIR.'catalog/index.php',
	),
	array(
		'CONDITION' => '#^'.WIZARD_SITE_DIR.'search/#',
	    'RULE' => '',
	    'ID' => 'concept:phoenix.search',
	    'PATH' => WIZARD_SITE_DIR.'search/index.php',
	),
	array(
		'CONDITION' => '#^'.WIZARD_SITE_DIR.'basket/#',
	    'RULE' => '',
	    'ID' => 'concept:phoenix.basket',
	    'PATH' => WIZARD_SITE_DIR.'basket/index.php',
	),
	array(
		'CONDITION' => '#^'.WIZARD_SITE_DIR.'brands/#',
	    'RULE' => '',
	    'ID' => 'bitrix:news',
	    'PATH' => WIZARD_SITE_DIR.'brands/index.php',
	),
	array(
		'CONDITION' => '#^'.WIZARD_SITE_DIR.'offers/#',
	    'RULE' => '',
	    'ID' => 'bitrix:news',
	    'PATH' => WIZARD_SITE_DIR.'offers/index.php',
	),
	array(
		'CONDITION' => '#^'.WIZARD_SITE_DIR.'news/#',
	    'RULE' => '',
	    'ID' => 'bitrix:news',
	    'PATH' => WIZARD_SITE_DIR.'news/index.php',
	),
	array(
		'CONDITION' => '#^'.WIZARD_SITE_DIR.'blog/#',
	    'RULE' => '',
	    'ID' => 'bitrix:news',
	    'PATH' => WIZARD_SITE_DIR.'blog/index.php',
	),
	array(
		'CONDITION' => '#^'.WIZARD_SITE_DIR.'#',
	    'RULE' => '',
	    'ID' => 'concept:phoenix.pages',
	    'PATH' => WIZARD_SITE_DIR.'index.php',
	), 

); 

foreach ($arNewUrlRewrite as $arUrl)
{
	if (!in_array($arUrl, $arUrlRewrite))
	{
		CUrlRewriter::Add($arUrl);
	}
}

function ___writeToAreasFile($fn, $text)
{
	if(file_exists($fn) && !is_writable($abs_path) && defined("BX_FILE_PERMISSIONS"))
		@chmod($abs_path, BX_FILE_PERMISSIONS);

	$fd = @fopen($fn, "wb");
	if(!$fd)
		return false;

	if(false === fwrite($fd, $text))
	{
		fclose($fd);
		return false;
	}

	fclose($fd);

	if(defined("BX_FILE_PERMISSIONS"))
		@chmod($fn, BX_FILE_PERMISSIONS);
}

//CheckDirPath(WIZARD_SITE_PATH."include/");


?>