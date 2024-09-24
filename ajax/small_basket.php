<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "top_basket", Array(
	"PATH_TO_BASKET" => "/basket/",	// Страница корзины
	"PATH_TO_PERSONAL" => "/personal/",	// Персональный раздел
	"SHOW_PERSONAL_LINK" => "N",	// Отображать персональный раздел
	"SHOW_NUM_PRODUCTS" => "Y",	// Показывать количество товаров
	"SHOW_TOTAL_PRICE" => "N",	// Показывать общую сумму по товарам
	"SHOW_PRODUCTS" => "N",	// Показывать список товаров
	"POSITION_FIXED" => "N",	// Отображать корзину поверх шаблона
	),
	false
);?>