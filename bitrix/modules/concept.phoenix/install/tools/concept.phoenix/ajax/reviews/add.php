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

	if(strlen($_POST["PRODUCT_ID"])>0 && intval($_POST["PRODUCT_ID"])>0)
	{
		global $PHOENIX_TEMPLATE_ARRAY;

		CPhoenix::setMess(array("review"));
		$userID = CPhoenix::GetUserID();

		CPhoenix::phoenixOptionsValues($site_id, array('rating'));


		$arFieldsMap = array(
			"PRODUCT_ID",
			"USER_NAME",
			"USER_EMAIL",
			"EXPERIENCE",
			"RECOMMEND",
			"VOTE",
			"TEXT",
			"POSITIVE",
			"NEGATIVE",
			"ACCOUNT_NUMBER"
		);


		$arParams = array();

		foreach ($arFieldsMap as $value)
		{
			if(strlen($_POST[$value])<=0)
				continue;
			
			$tmp = $_POST[$value];

			if(trim(SITE_CHARSET) == "windows-1251")
				$tmp = utf8win1251($tmp);


			if($value == "TEXT" || $value == "POSITIVE" || $value == "NEGATIVE")
				$tmp = nl2br($tmp);
			

			$arParams[$value] = htmlspecialcharsbx($tmp);
		}

		if(isset($_FILES["IMAGES_ID"]))
		{
			if(!empty($_FILES["IMAGES_ID"]))
			{
				$arImagesID = array();

				foreach ($_FILES["IMAGES_ID"]["error"] as $key => $value)
				{
					if($value == 0)
					{
						if($_FILES["IMAGES_ID"]["size"][$key] <= 2000000)
						{
							if(trim(SITE_CHARSET) == "windows-1251")
								$_FILES["IMAGES_ID"]["name"][$key] = utf8win1251($_FILES["IMAGES_ID"]["name"][$key]);

							if(CFile::IsImage($_FILES["IMAGES_ID"]["name"][$key]))
							{
								$arImage = array();

								$arImage = array(
									"name" => $_FILES["IMAGES_ID"]["name"][$key],
									"size" => $_FILES["IMAGES_ID"]["size"][$key],
									"tmp_name" => $_FILES["IMAGES_ID"]["tmp_name"][$key],
									"type" => $_FILES["IMAGES_ID"]["type"][$key],
									"MODULE_ID" => "concept.phoenix"
								);
								
								$arImagesID[] = CFile::SaveFile($arImage, "phoenix");
							}
						}
					}	
				}
				$arParams["IMAGES_ID"] = serialize($arImagesID);
			}

		}


		$arParams["USER_ID"] = $userID;
		

		$arParams["ACTIVE"] = "N";

		if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["REVIEW_MODERATOR"]["VALUE"]["ACTIVE"] != "Y")
			$arParams["ACTIVE"] = "Y";

		if(strlen($_POST["USER_NAME"])<=0)
		{
			$arParams["USER_NAME"] = ($userID)?$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["USER_NAME_DEF"]."&nbsp;".$userID:$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["USER_NAME_GUEST"];
		}

		/*CPhoenix::updateReviewsInfo(array("ACTION"=>"add","SITE_ID"=>$site_id, "PRODUCT_ID"=>$arParams["PRODUCT_ID"],"RECOMMEND" => $arParams["RECOMMEND"], "VOTE" => $arParams["VOTE"], "ACTIVE" => $arParams["ACTIVE"]));
		*/
		$result = CPhoenixReviews\CPhoenixReviewsTable::add($arParams);

		if ($result->isSuccess())
		{
			$reviewId = $result->getId();
			CPhoenix::setReviewsInfo($arParams["PRODUCT_ID"]);
			CPhoenix::sendNewReviewProduct(array("SITE_ID"=> $site_id, "PRODUCT_ID"=>$arParams["PRODUCT_ID"], "REVIEW_ID"=>$reviewId));
		    $arRes["OK"] = "Y";
		}

		

	}
}

echo json_encode($arRes);