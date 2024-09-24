<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?
use \Bitrix\Main;
global $PHOENIX_TEMPLATE_ARRAY;
?>

<?
$documentRoot = Main\Application::getDocumentRoot();
$templatePath = strtolower($arParams['VIEW'].".php");


$file = new Main\IO\File($documentRoot.$templateFolder.'/'.$templatePath);
if ($file->isExists())
{
    include($file->getPath());
}
?>