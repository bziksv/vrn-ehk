<?$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

use Bitrix\CPhoenixComments;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
header('Content-type: application/json');

$arRes = array(
	'OK'=>'N',
	'MESSAGE'=>'',
	'NEW'=>'N'
);


if(CModule::IncludeModule("iblock") && CModule::IncludeModule("concept.phoenix"))
{

	if(intval($_POST["ELEMENT_ID"])>0)
	{
		global $PHOENIX_TEMPLATE_ARRAY;
		$arParams = array(
			'USER_ID'=>'',
			'ACTIVE'=>'N',
			'USER_NAME'=>'',
			'USER_EMAIL'=>'',
			'ELEMENT_ID'=>'',
			'TEXT'=>'',
		);

		CPhoenix::phoenixOptionsValues($site_id, array('comments'));
		CPhoenix::setMess(array("comments"));


		$userID = CPhoenix::GetUserID();


		$arFieldsMap = array(
			"ELEMENT_ID",
			"TEXT",
			"USER_NAME",
			"USER_EMAIL"
		);

		foreach ($arFieldsMap as $value)
		{
			if(strlen($_POST[$value])<=0)
				continue;
			
			$tmp = $_POST[$value];

			if(trim(SITE_CHARSET) == "windows-1251")
				$tmp = utf8win1251($tmp);


			if($value == "TEXT")
				$tmp = nl2br($tmp);

			$arParams[$value] = htmlspecialcharsbx($tmp);
		}

		$arParams["USER_ID"] = $userID;

		$arRes['MESSAGE'] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["SUCCESS_MESSAGE_MODERATOR"];

		if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]["MODERATOR"]["VALUE"]["ACTIVE"] != "Y")
		{
			$arParams["ACTIVE"] = "Y";
			$arRes["NEW"] = "Y";
			$arRes['MESSAGE'] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["SUCCESS_MESSAGE"];
		}


		if(strlen($arParams["USER_NAME"])<=0)
		{
			$arParams["USER_NAME"] = ($userID)?$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["USER_NAME_DEF"]."&nbsp;".$userID:$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["USER_NAME_GUEST"];
		}
		
		

		$result = CPhoenixComments\CPhoenixCommentsTable::add($arParams);

		if ($result->isSuccess())
		{
			$commentId = $result->getId();

			
        	if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['ADMIN_EMAIL_NOTIFY']['VALUE'] === "ever")
        	{
        		CPhoenixSendMail::sendNewComments(array("SITE_ID"=> $site_id, "IBLOCK_ID"=>$_POST["IBLOCK_ID"], "ELEMENT_ID"=>$arParams["ELEMENT_ID"], "COMMENT_ID"=>$commentId));

        		  
        	}
				
			
		    $arRes["OK"] = "Y";
		}

		

	}
}

if(trim(SITE_CHARSET) == "windows-1251")
    $arRes['MESSAGE'] = iconv('windows-1251', 'UTF-8', $arRes['MESSAGE']);

echo json_encode($arRes);