<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket", 
	"big_basket", 
	array(
		"COLUMNS_LIST" => array(
			0 => "PROPERTY_CML2_BASE_UNIT",
			1 => "PROPERTY_COUNT_RATE",
		),
		"PATH_TO_ORDER" => "/make-order/",
		"HIDE_COUPON" => "N",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"USE_PREPAYMENT" => "N",
		"QUANTITY_FLOAT" => "N",
		"SET_TITLE" => "Y",
		"ACTION_VARIABLE" => "action",
		"OFFERS_PROPS" => ""
	),
	false
);?>