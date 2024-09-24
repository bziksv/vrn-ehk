<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск");
?>

<?$APPLICATION->IncludeComponent(
	"concept:phoenix.search", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "#SITE_DIR#search/",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"MESSAGE_404" => "",
		"FILE_404" => "",
		"SEF_URL_TEMPLATES" => array(
			"main" => "",
			"page" => "#SECTION#/",
		)
	),
	false
);?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>