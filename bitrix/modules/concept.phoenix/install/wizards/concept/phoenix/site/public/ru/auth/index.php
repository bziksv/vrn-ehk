<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
?>

<?$APPLICATION->IncludeComponent(
    "bitrix:system.auth.form", 
    "auth2", 
    array(
        "COMPONENT_TEMPLATE" => "auth",
        "PROFILE_URL" => "/personal/",
        "SHOW_ERRORS" => "N",
        "COMPOSITE_FRAME_MODE" => "N"
    ),
    false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>