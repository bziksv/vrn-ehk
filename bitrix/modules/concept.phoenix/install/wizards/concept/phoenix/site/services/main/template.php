<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

$wizard_template_id = "concept_phoenix";


$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".$wizard_template_id."_".WIZARD_SITE_ID;

CopyDirFiles(
	$_SERVER["DOCUMENT_ROOT"].WizardServices::GetTemplatesPath(WIZARD_RELATIVE_PATH."/site")."/".$wizard_template_id,
	$bitrixTemplateDir,
	$rewrite = true,
	$recursive = true, 
	$delete_after_copy = false,
	$exclude = "themes"
);

//Attach template to default site
$obSite = CSite::GetList($by = "def", $order = "desc", Array("LID" => WIZARD_SITE_ID));

if ($arSite = $obSite->Fetch())
{
	$arTemplates = Array();
	$found = false;
	$foundEmpty = false;
	$obTemplate = CSite::GetTemplateList($arSite["LID"]);
	while($arTemplate = $obTemplate->Fetch())
	{
		if(!$found && strlen(trim($arTemplate["CONDITION"]))<=0)
		{
			$arTemplate["TEMPLATE"] = $wizard_template_id."_".WIZARD_SITE_ID;
			$found = true;
		}
		if($arTemplate["TEMPLATE"] == "empty")
		{
			$foundEmpty = true;
			continue;
		}
		$arTemplates[]= $arTemplate;
	}

	if (!$found)
		$arTemplates[]= Array("CONDITION" => "", "SORT" => 150, "TEMPLATE" => $wizard_template_id."_".WIZARD_SITE_ID);

	$arFields = Array(
		"TEMPLATE" => $arTemplates,
		"NAME" => $arSite["NAME"],
	);

	$obSite = new CSite();
	$obSite->Update($arSite["LID"], $arFields);
}

COption::SetOptionString("main", "wizard_template_id", $wizard_template_id."_".WIZARD_SITE_ID, false, WIZARD_SITE_ID);
?>
