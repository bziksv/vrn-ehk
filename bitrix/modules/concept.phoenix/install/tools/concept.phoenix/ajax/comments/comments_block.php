<?$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if(CModule::IncludeModule("iblock") && CModule::IncludeModule("concept.phoenix")):?>

    <?if($_POST["mode"]=="full"):?>

        <?$APPLICATION->IncludeComponent(
            "concept:phoenix.comments.list",
            "",
            Array(
                "COMPOSITE_FRAME_MODE" => "N",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "ELEMENT_ID" => $_POST["id"],
                "MODE"=>$_POST["mode"],
                "LIMIT_1" => $_POST["limit_1"],
                "LIMIT_2" => $_POST["limit_2"]
            )
        );?>
    
    <?elseif($_POST["mode"]=="list"):?>
        <?$APPLICATION->IncludeComponent(
            "concept:phoenix.comments.list",
            "",
            Array(
                "COMPOSITE_FRAME_MODE" => "N",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "ELEMENT_ID" => $_POST["id"],
                "PAGE" => $_POST["page"],
                "LIMIT_1" => $_POST["limit_1"],
                "LIMIT_2" => $_POST["limit_2"],
                "MODE"=>$_POST["mode"],
            )
        );?>
    <?endif;?>

<?endif;?>   