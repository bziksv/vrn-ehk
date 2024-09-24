<?$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

use Bitrix\CPhoenixReviews;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
header('Content-type: application/json');

$arRes = array();
$arRes["OK"] = "N";


if(CModule::IncludeModule("iblock") && CModule::IncludeModule("concept.phoenix"))
{
	    
	if(strlen($_POST["REVIEW_ID"])>0 && intval($_POST["REVIEW_ID"])>0)
	{

		$arFieldsMap = array(
			"REVIEW_ID",
			"LIKE_COUNT"
		);

		$arParams = array();

		foreach ($arFieldsMap as $key => $value)
		{
			if(strlen($_POST[$value])<=0)
				continue;
			
			$tmp = $_POST[$value];

			if(trim(SITE_CHARSET) == "windows-1251")
				$tmp = utf8win1251($tmp);

			$arParams[$value] = htmlspecialcharsbx($tmp);
		}

		$review = CPhoenixReviews\CPhoenixReviewsTable::getList(array('select' => array('LIKE_COUNT'),'filter' => array('=ID' => $arParams["REVIEW_ID"]),"cache"=>array("ttl"=>3600)))->fetch();


		

		$total = intval($arParams["LIKE_COUNT"]) + intval($review["LIKE_COUNT"]);


		$result = CPhoenixReviews\CPhoenixReviewsTable::update($arParams["REVIEW_ID"], array("LIKE_COUNT"=>$total));
		
		
		if ($result->isSuccess())
			$arRes["OK"] = "Y";

		$arRes["LIKE_COUNT"] = $total;
	}
}

echo json_encode($arRes);