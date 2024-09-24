<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


global $APPLICATION;

CModule::IncludeModule("search");


$arDefaultUrlTemplates404 = array(
	"main" => "",
	"page" => "#SECTION#/",
);

$arDefaultVariableAliases404 = array();

$arDefaultVariableAliases = array();




$arVariables = array();

$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

$engine = new CComponentEngine($this);

if(CModule::IncludeModule('iblock'))
{
	$engine->addGreedyPart("#SECTION_CODE_PATH#");
	$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
}

$componentPage = $engine->guessComponentPath(
	$arParams["SEF_FOLDER"],
	$arUrlTemplates,
	$arVariables
);

if($arParams["SEF_FOLDER"] == $APPLICATION->GetCurPage(false))
    $componentPage = "main";
    

$b404 = false;


if(!$componentPage)
{
	$componentPage = "main";
	$b404 = true;
}



CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

$arResult = array(
	"FOLDER" => $arParams["SEF_FOLDER"],
	"URL_TEMPLATES" => $arUrlTemplates,
	"VARIABLES" => $arVariables,
	"ALIASES" => $arVariableAliases,
	"QUERY" => trim($_REQUEST["q"])
);

$this->IncludeComponentTemplate($componentPage);

?>