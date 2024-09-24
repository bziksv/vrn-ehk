<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел");

$APPLICATION->IncludeComponent("concept:phoenix.personal", "",
    array("COMPOSITE_FRAME_MODE" => "N")
);

?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>