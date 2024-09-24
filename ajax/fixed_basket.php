<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<img src="/bitrix/templates/main/images/t_h_basket_ico.png" alt="cart">
<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "top_basket", array(
    "PATH_TO_BASKET" => "/basket/",
    "PATH_TO_PERSONAL" => "/personal/",
    "SHOW_PERSONAL_LINK" => "N",
    "SHOW_NUM_PRODUCTS" => "Y",
    "SHOW_TOTAL_PRICE" => "N",
    "SHOW_PRODUCTS" => "N",
    "POSITION_FIXED" => "N"
),
    false
);?>
