<?$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
header('Content-type: application/json');

$arRes = array();
$arRes["OK"] = "N";


if(CModule::IncludeModule("iblock") && CModule::IncludeModule("concept.phoenix"))
{
    global $PHOENIX_TEMPLATE_ARRAY;

    CPhoenix::getIblockIDs(array("SITE_ID"=>$site_id, "CODES"=> array("concept_phoenix_catalog_".$site_id)));



    $params = array();

    $params['ID'] = intval(htmlspecialchars(trim($_POST["id"])));
    $params['VOTE'] = intval(htmlspecialchars(trim($_POST["vote"])));


    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), array("ID" => $params['ID']), false, false, array("PROPERTY_rating", "PROPERTY_vote_count", "PROPERTY_vote_sum"));

    if($ob = $res->GetNext())
    {
        $params["VOTE_COUNT"] = intval($ob["PROPERTY_VOTE_COUNT_VALUE"]) + 1;
        $params["VOTE_SUM"] = intval($ob["PROPERTY_VOTE_SUM_VALUE"]) + $params['VOTE'];
        $params["RATING"] = round($params["VOTE_SUM"] / $params["VOTE_COUNT"]);

        if($params["RATING"]<=0)
            $params["RATING"] = 0;

        if($params["RATING"]>=5)
            $params["RATING"] = 5;
    }
    CIBlockElement::SetPropertyValues($params["ID"], $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], $params["VOTE_COUNT"], "vote_count");
    CIBlockElement::SetPropertyValues($params["ID"], $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], $params["VOTE_SUM"], "vote_sum");
    CIBlockElement::SetPropertyValues($params["ID"], $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], $params["RATING"], "rating");
    $arRes["PRODUCT"] = array("ID"=>$params['ID'], "RATING"=>$params["RATING"],"VOTE"=>$params['VOTE']);
    
    $arRes["OK"] = "Y";
    unset($params, $res);
}

echo json_encode($arRes);