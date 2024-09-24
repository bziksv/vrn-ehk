<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("История заказов");
?>
<div class="personal_page orders">
	<ul class="pers_pages_menu menu_type">
		<li><a href="/?logout=yes"><span>Выйти</span></a></li>
		<li><a href="/personal/"><span>Настройки аккаунта</span></a></li>
		<li class="active"><a href="/personal/orders/"><span>История заказов</span></a></li>
	</ul>
	<div class="blocks order_block">
		<div class="block">
			<?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order", 
	"common", 
	array(
		"PROP_1" => array(
		),
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/personal/orders/",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "Y",
		"ORDERS_PER_PAGE" => "10",
		"PATH_TO_PAYMENT" => "/make-order/payment.php",
		"PATH_TO_BASKET" => "/basket/",
		"SET_TITLE" => "Y",
		"SAVE_IN_SESSION" => "Y",
		"NAV_TEMPLATE" => "",
		"CUSTOM_SELECT_PROPS" => array(
			0 => "BASE_UNIT",
			1 => "ARTICLE",
			2 => "",
		),
		"HISTORIC_STATUSES" => array(
			0 => "F",
		),
		"STATUS_COLOR_N" => "green",
		"STATUS_COLOR_F" => "gray",
		"STATUS_COLOR_PSEUDO_CANCELLED" => "red",
		"PROP_2" => array(
		),
		"COMPONENT_TEMPLATE" => "common",
		"DETAIL_HIDE_USER_INFO" => array(
			0 => "0",
		),
		"PATH_TO_CATALOG" => "/catalog/",
		"DISALLOW_CANCEL" => "N",
		"RESTRICT_CHANGE_PAYSYSTEM" => array(
			0 => "0",
		),
		"REFRESH_PRICES" => "N",
		"ORDER_DEFAULT_SORT" => "DATE_INSERT",
		"ALLOW_INNER" => "N",
		"ONLY_INNER_FULL" => "N",
		"STATUS_COLOR_O" => "gray",
		"STATUS_COLOR_B" => "gray",
		"STATUS_COLOR_Q" => "gray",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"STATUS_COLOR_G" => "gray",
		"SEF_URL_TEMPLATES" => array(
			"list" => "",
			"detail" => "#ID#/",
			"cancel" => "#ID#/",
		)
	),
	false
);?>
		</div>
	</div>
	<div class="clear"></div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
