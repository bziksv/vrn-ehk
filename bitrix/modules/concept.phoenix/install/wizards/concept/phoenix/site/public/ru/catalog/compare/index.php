<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сравнение товаров");

$APPLICATION->IncludeComponent(
	"concept:phoenix.compare.page",
	"",
	Array(
		"COMPOSITE_FRAME_MODE" => "N"
	)
);


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
