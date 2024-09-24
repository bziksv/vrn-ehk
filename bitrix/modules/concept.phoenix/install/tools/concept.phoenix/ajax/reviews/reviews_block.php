<?$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if(CModule::IncludeModule("iblock") && CModule::IncludeModule("concept.phoenix")):?>

    
    
    <?if($_POST["mode"]=="full"):?>
    
        <?$APPLICATION->IncludeComponent(
            "concept:phoenix.reviews.info",
            "",
            Array(
                "COMPOSITE_FRAME_MODE" => "N",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "PRODUCT_ID" => $_POST["id"]
            )
        );?>

        <?/*open tags in component concept:phoenix.reviews.info */?>

            <div class="col-md-8 col-12 wr-review-list order-md-1">

                <?$APPLICATION->IncludeComponent(
                    "concept:phoenix.reviews.list",
                    "",
                    Array(
                        "COMPOSITE_FRAME_MODE" => "N",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "PRODUCT_ID" => $_POST["id"],
                        "MODE"=>$_POST["mode"],
                        "LIMIT_1" => $_POST["limit_1"],
                        "LIMIT_2" => $_POST["limit_2"]
                    )
                );?>

                </div>
            </div>
        </div>

    <?elseif($_POST["mode"]=="list"):?>

   
        <?$APPLICATION->IncludeComponent(
            "concept:phoenix.reviews.list",
            "",
            Array(
                "COMPOSITE_FRAME_MODE" => "N",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "PRODUCT_ID" => $_POST["id"],
                "PAGE" => $_POST["page"],
                "FILTER" => $_POST["filter"],
                "LIMIT_1" => $_POST["limit_1"],
                "LIMIT_2" => $_POST["limit_2"]
            )
        );?>

    <?endif;?>

<?endif;?>   