<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


if(isset($_POST["PRODUCT_ID"]{0}))
{
    global $PHOENIX_TEMPLATE_ARRAY;
    
    CModule::IncludeModule("concept.phoenix");
    CPhoenix::phoenixOptionsValues($site_id, array('start', 'catalog'));

    if(!empty($_POST["STORE"]))
    {
        if(trim(SITE_CHARSET) == "windows-1251")
            $_POST["MEASURE"] = utf8win1251($_POST["MEASURE"]);
        

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.store.amount",
            "main",
            Array(
                "CACHE_TIME" => "36000",
                "CACHE_TYPE" => "N",
                "COMPOSITE_FRAME_MODE" => "N",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "ELEMENT_CODE" => "",
                "ELEMENT_ID" => $_POST["PRODUCT_ID"],
                "FIELDS" => array("TITLE", "ADDRESS", "DESCRIPTION", "PHONE", "EMAIL", "COORDINATES", "SCHEDULE", ""),
                "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
                "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_TYPE"],
                "MAIN_TITLE" => "",
                "MIN_AMOUNT" => "0",
                "OFFER_ID" => $_POST["PRODUCT_ID"],
                "SHOW_EMPTY_STORE" => "",
                "SHOW_GENERAL_STORE_INFORMATION" => "N",
                "STORES" => $_POST["STORE"],
                "STORE_PATH" => "",
                "USER_FIELDS" => array("", ""),
                "USE_MIN_AMOUNT" => "N",
                "MEASURE"=>$_POST["MEASURE"],
                /*"STORE_QUANTITY" => $_POST["STORE_QUANTITY"],*/
                "TAB" => $_POST["TAB"]
            )
        );

    }
}