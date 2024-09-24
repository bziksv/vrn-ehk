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
global $USER, $PHOENIX_TEMPLATE_ARRAY;

if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CABINET"]["VALUE"]["ACTIVE"] != "Y")
    LocalRedirect(SITE_DIR);

// if(!$USER->isAuthorized()){
//     LocalRedirect(SITE_DIR.'auth/');
// }
// else
// {

    $arPages = [
        "index" => SITE_DIR."index.php",
        "orders" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["ORDERS"]["URL"],
        "account" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["ACCOUNT"]["URL"],
        "subscribe" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["SUBSCRIBE"]["URL"],
        "profile" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["PROFILE"]["URL"],
        "profile_detail" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["PROFILE"]["URL"]."#ID#/",
        "private" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["PRIVATE"]["URL"],
        "order_detail" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["ORDERS"]["URL"]."#ID#/",
        "order_cancel" => "cancel/#ID#/",
        "register" => "register/",
        "forgotpasswd" => "forgotpasswd/",
        "changepasswd" => "changepasswd/",
        "register_success" => "register_success/",
    ];

    if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CUSTOM_PAGES"]["ITEMS"]))
    {
        foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CUSTOM_PAGES"]["ITEMS"] as $value)
        {
            $arPages[$value['URL']] = $value['URL'].'/';
        }
    }

         
    $APPLICATION->IncludeComponent(
        "bitrix:sale.personal.section", 
        "main", 
        array(
            "ACCOUNT_PAYMENT_SELL_CURRENCY" => CSaleLang::GetLangCurrency(SITE_ID),
            "ACCOUNT_PAYMENT_PERSON_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["ACCOUNT_PERSON_TYPE"]["CUR_VALUE"],
            "SHOW_BASKET_PAGE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["BASKET"],
            "SHOW_ACCOUNT_PAGE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["ACCOUNT"],
            "SHOW_ACCOUNT_COMPONENT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["ACCOUNT"],
            "SHOW_ACCOUNT_PAY_COMPONENT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["ACCOUNT"],
            "SHOW_ORDER_PAGE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["ORDERS"],
            "SHOW_PROFILE_PAGE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["PROFILE"],
            "SHOW_SUBSCRIBE_PAGE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["SUBSCRIBE"],
            "SHOW_PRIVATE_PAGE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["PRIVATE"],
            "SHOW_CONTACT_PAGE" => "",
            "PATH_TO_BASKET" => $PHOENIX_TEMPLATE_ARRAY["BASKET_URL"],
            "SEF_URL_TEMPLATES" => $arPages,
            "ACCOUNT_PAYMENT_ELIMINATED_PAY_SYSTEMS" => array(
                0 => "0",
            ),
            "ACCOUNT_PAYMENT_SELL_SHOW_FIXED_VALUES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SHOW_FIX_PRICE"]["VALUE"]["ACTIVE"],
            "ACCOUNT_PAYMENT_SELL_TOTAL" => explode(";", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['FIX_PRICE_VALUES']["VALUE"]),
            "ACCOUNT_PAYMENT_SELL_USER_INPUT" => "Y",
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CHECK_RIGHTS_PRIVATE" => "N",
            "COMPATIBLE_LOCATION_MODE_PROFILE" => "N",
            "CUSTOM_PAGES" => "",
            "CUSTOM_SELECT_PROPS" => array(
            ),
            "NAV_TEMPLATE" => "",
            "ORDER_HISTORIC_STATUSES" => array(
                0 => "F",
            ),
            "PATH_TO_CATALOG" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CATALOG_URL"]["VALUE"],
            "PATH_TO_CONTACT" => "",
            "PATH_TO_PAYMENT" => $PHOENIX_TEMPLATE_ARRAY["BASKET_URL"]."payment/",
            "PER_PAGE" => "20",
            "PROP_1" => array(
            ),
            "PROP_2" => array(
            ),
            "SAVE_IN_SESSION" => "Y",
            "SEF_FOLDER" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SEF_FOLDER"]["VALUE"],
            "SEF_MODE" => "Y",
            "SEND_INFO_PRIVATE" => "N",
            "SET_TITLE" => "Y",
            "ALLOW_INNER" => "N",
            "ONLY_INNER_FULL" => "N",
            "USER_PROPERTY_PRIVATE" => "",
            "USE_AJAX_LOCATIONS_PROFILE" => "N",
            "COMPONENT_TEMPLATE" => "main",
            
            "ORDER_HIDE_USER_INFO" => array(
                0 => "0",
            ),
            "ORDER_RESTRICT_CHANGE_PAYSYSTEM" => array(
                0 => "0",
            ),
            "ORDER_DEFAULT_SORT" => "STATUS",
            "ORDER_REFRESH_PRICES" => "N",
            "ORDER_DISALLOW_CANCEL" => "N",
            "ORDERS_PER_PAGE" => "20",
            "PROFILES_PER_PAGE" => "20",
            "MAIN_CHAIN_NAME" => "",
            "COMPOSITE_FRAME_MODE" => "N",
        ),
        $component
    );
//}
?>