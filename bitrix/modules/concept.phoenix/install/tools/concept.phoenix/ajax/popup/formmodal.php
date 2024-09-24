<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
    
if(!CModule::IncludeModule('iblock'))
    return false;


$formID = 0;
/*$element_id = 0;
$element_type = "";*/

if(isset($_POST["arParams"]) && !empty($_POST["arParams"]))
{
    if(isset( $_POST["arParams"]["element_id"]) )
        $formID = $_POST["arParams"]["element_id"];

    /*if(isset( $_POST["arParams"]["element_item_id"]) )
        $element_id = $_POST["arParams"]["element_item_id"];

    if(isset( $_POST["arParams"]["element_item_type"]) )
        $element_type = $_POST["arParams"]["element_item_type"];*/

    $APPLICATION->IncludeComponent(
        "concept:phoenix.form",
        "",
        Array(
            "COMPOSITE_FRAME_MODE" => "N",
            "CURRENT_FORM" => $formID,
            "CURRENT_SITE" => $site_id,
            "IBLOCK_CODE" => "concept_phoenix_forms_".$site_id,
            "MESSAGE_404" => "",
            "SET_STATUS_404" => "N",
            "SHOW_404" => "N",
            /*"ELEMENT_ID" => $element_id,
            "ELEMENT_TYPE" => $element_type,*/
            "PARAMS" => $_POST["arParams"]
        )
    );
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");