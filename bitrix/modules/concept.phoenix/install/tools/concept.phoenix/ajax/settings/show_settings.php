<?$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$moduleID = "concept.phoenix";
CModule::IncludeModule($moduleID);

if(!CPhoenix::isAdmin())
    die();

if($_POST["panel"])
{
	$panel = htmlspecialchars(trim($_POST["panel"]));

    if($_POST["currentSectionId"])
        $currentSectionId = htmlspecialchars(trim($_POST["currentSectionId"]));
}

$APPLICATION->IncludeComponent(
    "concept:phoenix.pages.list",
    "",
    Array(
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "COMPOSITE_FRAME_MODE" => "N",
        "SITE_ID" => $site_id,
        "PANEL" => $panel,
        "CURRENT_SECTION_ID" => $currentSectionId
    )
);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");