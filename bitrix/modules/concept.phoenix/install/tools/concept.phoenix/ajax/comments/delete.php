<?use Bitrix\CPhoenixComments;
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
header('Content-type: application/json');

$arRes = array(
	'OK'=>'N',
);
global $USER;
$userID = $USER->GetID();

if(CModule::IncludeModule("iblock") && CModule::IncludeModule("concept.phoenix"))
{

	if(intval($_POST["ELEMENT_ID"])>0)
	{

		$result = CPhoenixComments\CPhoenixCommentsTable::getList(array(
		    'filter' => array('=ID' => $_POST["ELEMENT_ID"])
		));

		$arResult = array();

		if($arResult = $result->fetch())
		{
			if(intval($userID)>0 && $userID == $arResult["USER_ID"])
			{
				$result = CPhoenixComments\CPhoenixCommentsTable::delete($_POST["ELEMENT_ID"]);
				$arRes["OK"] = "Y";
			}
			
		}
	}
}

echo json_encode($arRes);