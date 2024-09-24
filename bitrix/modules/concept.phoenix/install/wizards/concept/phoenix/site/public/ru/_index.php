<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ФЕНИКС — безлимитный конструктор лендинговых интернет-магазинов для платформы 1С-Битрикс ");
?>

<?$APPLICATION->IncludeComponent(
	"concept:phoenix.pages", 
	".default", 
	array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "N",
		"FILE_404" => "",
		"IBLOCK_ID" => "#IBLOCK_LANDS#",
		"IBLOCK_TYPE" => "#IBLOCK_TYPE#",
		"MESSAGE_404" => "",
		"SEF_FOLDER" => "#SITE_DIR#",
		"SEF_MODE" => "Y",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"CACHE_GROUPS" => "Y",
		"SEF_URL_TEMPLATES" => array(
			"main" => "",
			"page" => "#SECTION_CODE_PATH#/",
		)
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>