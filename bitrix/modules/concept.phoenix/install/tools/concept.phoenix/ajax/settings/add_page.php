<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();
    
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');?>


<?
if($_POST["send"] == "Y")
{
    global $PHOENIX_TEMPLATE_ARRAY;
    $arResult["OK"] = "N";

    CModule::IncludeModule("iblock");

    $moduleID = "concept.phoenix";
    CModule::IncludeModule($moduleID);
    CPhoenix::phoenixOptionsValues($site_id, array(
        "start"
    ));

    if(!$PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"])
        die();

    $iblock_id = $_POST["iblock_id"];
    $name = trim($_POST["newpage_name"]);
    $code = trim($_POST["newpage_id"]);

    if(trim(SITE_CHARSET) == "windows-1251")
    {
        $name = utf8win1251($name);
        $code = utf8win1251($code);
    }

    $bs = new CIBlockSection;

    $arFields = Array(
        "ACTIVE" => "Y",
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID" => $iblock_id,
        "NAME" => $name,
        "CODE" => $code
    );

    if($ID = $bs->Add($arFields))
    {
        $arResult["OK"] = "Y";

        $arSection = GetIBlockSection($ID);
        $arResult["HREF"] = $arSection["SECTION_PAGE_URL"];

    }

}


$arResult = json_encode($arResult);
echo $arResult;?>