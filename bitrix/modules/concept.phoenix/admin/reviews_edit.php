<?
use Bitrix\CPhoenixReviews;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");



$moduleID = "concept.phoenix";
Loader::includeModule($moduleID);
Loader::includeModule('iblock');


$MODULE_RIGHTS = $APPLICATION->GetGroupRight($moduleID);

if($MODULE_RIGHTS < "R")
    LocalRedirect("/bitrix/");


$ID = "";

if (isset($_REQUEST['ID']))
	$ID = trim((string)$_REQUEST['ID']);
else
	LocalRedirect("/bitrix/admin/concept_phoenix_admin_reviews_list.php");


$siteID = isset($_REQUEST['site_id']) ? $_REQUEST['site_id'] : '';

$arSites = array();
$rsSites = CSite::GetList($by="sort", $order="asc");
while($arSite = $rsSites->Fetch()) 
{
    $res = false;
    
    $rsTemplates = CSite::GetTemplateList($arSite["LID"]);
    while($arTemplate = $rsTemplates->Fetch())
    {
        if(substr_count($arTemplate["TEMPLATE"], "concept_phoenix_".$arSite["LID"]) > 0)
            $res = true;
    }
    
    if($res == true)
        $arSites[$arSite["LID"]] = $arSite;
    
}

if(strlen($siteID) <= 0)
{
    reset($arSites);
    $arS = current($arSites);
    
    LocalRedirect("/bitrix/admin/concept_phoenix_admin_reviews_list.php?lang=".LANGUAGE_ID."&site_id=".$arS["LID"]);
}


global $APPLICATION;
$MODULE_RIGHTS = $APPLICATION->GetGroupRight($moduleID);

if($MODULE_RIGHTS < "R")
    LocalRedirect("/bitrix/");

$aTabs = array(
	array(
		"DIV" => "maintab",
		"TAB" => Loc::getMessage("PHOENIX_REVIEW_EDIT_MAINTAB"),
		"ICON" => "main_user_edit"
	),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

if($_REQUEST["action"] == "delete")
	$APPLICATION->SetTitle(GetMessage("PHOENIX_REVIEW_EDIT_DEL_TITLE"));
else if(strlen($ID) > 0)
	$APPLICATION->SetTitle(GetMessage("PHOENIX_REVIEW_EDIT_EDIT_TITLE"));






require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");


/*$selectFields = array(
	'ID', 'ACTIVE', 'DATE', 'PRODUCT_ID', 'ACCOUNT_NUMBER', 'USER_ID', 'USER_NAME', 'USER_EMAIL', 'VOTE', 'RECOMMEND', 'EXPERIENCE', 'TEXT', 'POSITIVE','NEGATIVE', 'IMAGES_ID', 'LIKE_COUNT', 'STORE_RESPONSE');*/
$result = CPhoenixReviews\CPhoenixReviewsTable::getList(array(
    /*'select' => $selectFields,*/
    'filter' => array('=ID' => $ID)
));

$arResult = array();

if($arResult = $result->fetch())
{

	$arResult["IMAGES_SRC"] = "-";

    if($arResult["IMAGES_ID"] !== null)
    {
    	$arImagesID = unserialize($arResult["IMAGES_ID"]);

    	if(!empty($arImagesID))
    	{

    		$arResult["IMAGES_SRC"] = "";
    		
    		foreach ($arImagesID as $key => $value)
    		{
    			$arImg = $arImgBig = array();
    			$arImg = CFile::ResizeImageGet($value, array('width'=>64, 'height'=>64), BX_RESIZE_IMAGE_EXACT, false, Array(), false, 85);
    			$arImgBig = CFile::ResizeImageGet($value, array('width'=>1200, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, 85);
    			$arResult["IMAGES_SRC"] .= "<a href=\"".$arImgBig['src']."\" target=\"_blank\"><img style=\"margin: 0 5px 5px 0;\" src=\"".$arImg['src']."\"></a>";
    		}
    	}
    	unset($arImagesID);
    }

	$arSelect = Array("ID", "NAME", "IBLOCK_ID", "IBLOCK_SECTION_ID", "IBLOCK_TYPE_ID");
	$arFilter = Array("ID" => $arResult["PRODUCT_ID"]);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

	while($ob = $res->GetNextElement())
	{ 
	    $arFields = $ob->GetFields();

	    $arResult["PRODUCT_ID_HTML"] = "<a href='/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".$arFields["IBLOCK_ID"]."&type=".$arFields["IBLOCK_TYPE_ID"]."&ID=".$arFields["ID"]."' target='_blank'>".strip_tags($arFields["~NAME"])."</a>";
	}

	if($arResult["ACCOUNT_NUMBER"] !== NULL)
	{
		if (CModule::IncludeModule("sale"))
		{
			$rsOrder = CSaleOrder::GetList(array('ID' => 'DESC'), array('ACCOUNT_NUMBER' => $arResult["ACCOUNT_NUMBER"], 'LID' => $siteID), false, false, array("ID"));

			if($ar_sales = $rsOrder->Fetch())
				$arResult["ACCOUNT_NUMBER"] = "<a href='/bitrix/admin/sale_order_view.php?ID=".$arResult["ACCOUNT_NUMBER"]."' target='_blank'>".$ar_sales["ID"]."</a>";
			
			else
				$arResult["ACCOUNT_NUMBER"] = Loc::getMessage("PHOENIX_REVIEWS_EDIT_ORDER_ID", array("#ORDER_ID#"=> $arResult["ACCOUNT_NUMBER"]));
		}
	}

	

	foreach ($arResult as $key => $value)
	{
		

		if($value === NULL)
			$arResult[$key."_FORMATED"] = '-';
		else
			$arResult[$key."_FORMATED"] = $value;
	}

	$arResult["RECOMMEND_FORMATED"] = Loc::getMessage("PHOENIX_REVIEWS_EDIT_RECOMMEND_".$arResult["RECOMMEND_FORMATED"]);
	$arResult["EXPERIENCE_FORMATED"] = Loc::getMessage("PHOENIX_REVIEWS_EDIT_EXPERIENCE_".$arResult["EXPERIENCE_FORMATED"]);


}
else if($_REQUEST["deleted"] == 1)
{
	echo CAdminMessage::ShowNote(Loc::getMessage("PHOENIX_REVIEW_EDIT_DEL_COMPLTED_MESS"));
}

else 
	LocalRedirect("/bitrix/admin/concept_phoenix_admin_reviews_list.php?lang=".LANGUAGE_ID."&site_id=".$arS["LID"]);



if($_REQUEST["action"] == "delete" && !isset($_REQUEST["deleted"]) && $MODULE_RIGHTS == "W")
{
    if($arResult["IMAGES_ID"] != "-")
    {
    	$arImagesID = unserialize($arResult["IMAGES_ID"]);

    	if(!empty($arImagesID))
    	{
    		foreach ($arImagesID as $key => $value)
    		{
    			CFile::Delete($value);
    		}
    	}
    	unset($arImagesID);
    }
    $ID = $_REQUEST['ID'];

    $result = CPhoenixReviews\CPhoenixReviewsTable::getList(array(
	    'select'=>array('PRODUCT_ID'),
	    'filter' => array('=ID' => $ID)
	))->fetch();
    
    $productID = $result["PRODUCT_ID"];


	$result = CPhoenixReviews\CPhoenixReviewsTable::delete($ID);
	
	if ($result->isSuccess())
		CPhoenix::setReviewsInfo($productID);
	

	LocalRedirect($APPLICATION->GetCurUri("deleted=1"));
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && $MODULE_RIGHTS=="W" && !empty($_REQUEST['Update']) && !isset($_REQUEST["saved"]))
{

	$errors = false;

	$arFields = array(
		'TEXT' => htmlspecialcharsbx($_REQUEST["TEXT"]),
		'POSITIVE' => htmlspecialcharsbx($_REQUEST["POSITIVE"]),
		'NEGATIVE' => htmlspecialcharsbx($_REQUEST["NEGATIVE"]),
		'STORE_RESPONSE' => htmlspecialcharsbx($_REQUEST["STORE_RESPONSE"]),
		'ACTIVE' => htmlspecialcharsbx($_REQUEST["ACTIVE"])
	);



	$result = CPhoenixReviews\CPhoenixReviewsTable::update($ID, $arFields);
		
		
	if (!$result->isSuccess())
	{
		$mesEr = $result->getErrorMessages();
		
		foreach ($mesEr as $value){
			if(strlen($value)>0)
	    		echo CAdminMessage::ShowMessage($value);
	    }
	    $errors = true;
	}


	if(!$errors)
    {
    	$result = CPhoenixReviews\CPhoenixReviewsTable::getList(array(
		    'select'=>array('PRODUCT_ID'),
		    'filter' => array('=ID' => $ID)
		))->fetch();
		
		CPhoenix::setReviewsInfo($result["PRODUCT_ID"]);

    	if (!isset($_REQUEST['apply']))
			LocalRedirect("/bitrix/admin/concept_phoenix_admin_reviews_list.php?lang=".LANGUAGE_ID."&site_id=".$arS["LID"]);

		LocalRedirect($APPLICATION->GetCurUri("ID=".$ID."&".$tabControl->ActiveTabParam()."&saved=1&site_id=".$siteID));
    }

}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");


if($_REQUEST["saved"] == 1)
	echo CAdminMessage::ShowNote(Loc::getMessage("PHOENIX_REVIEW_EDIT_SAVE_SUC"));



$aContext = array(
	array(
		"ICON" => "btn_list",
		"TEXT" => GetMessage("MAIN_ADMIN_MENU_LIST"),
		"LINK" => "concept_phoenix_admin_reviews_list.php?lang=".LANGUAGE_ID."&site_id=".$siteID,
		"TITLE" => GetMessage("MAIN_ADMIN_MENU_LIST")
	),
);

if (strlen($ID) > 0 && $_REQUEST["action"] != "delete" && $MODULE_RIGHTS == "W")
{
	$aContext[] = array(
		"ICON" => "btn_delete",
		"TEXT" => GetMessage("MAIN_ADMIN_MENU_DELETE"),
		"ONCLICK" => "javascript:if(confirm('".GetMessageJS("PHOENIX_REVIEW_EDIT_DEL_PROMT_MESS")."'))window.location='concept_phoenix_admin_reviews_edit.php?site_id=".$siteID."&action=delete&ID=".CUtil::JSEscape($ID)."';",
	);
}

$context = new CAdminContextMenu($aContext);
$context->Show();
?>

<form id="phoenix_review" method="POST" action="<?=$APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" name="phoenix_review" style="display:<?=($_REQUEST["deleted"] == 1)?"none":""?>">
	<input type="hidden" name="ID" value="<?=htmlspecialcharsbx($ID); ?>">
	<input type="hidden" name="Update" value="Y">
	<input type="hidden" name="site_id" value="<?=$siteID?>">
	<input type="hidden" name="images" value="<?=$arResult["IMAGES_ID"]?>">

<?
	echo bitrix_sessid_post();
	$tabControl->Begin();
	$tabControl->BeginNextTab();
		/*CPhoenix::getOptionHtml(
			array(
				"NAME" => "ID",
				"VALUE" => $arResult["ID"],
				"SIZE" => "5",
				"ROWS" => "1",
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_ID_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"

			)
		)*/

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "ACTIVE",
				"VALUES" => array(
					"ACTIVE"=>array(

						"NAME" => "ACTIVE",
						"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_ACTIVE_TITLE"),
						"VALUE" => "Y"

					)
				),
				"VALUE" => array("ACTIVE" => $arResult["ACTIVE"]),
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_ACTIVE_TITLE"),
				"TYPE" => "checkbox",
			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "DATE",
				"VALUE" => $arResult["DATE"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_DATE_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"
			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "PRODUCT_ID",
				"VALUE" => $arResult["PRODUCT_ID_HTML"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_PRODUCT_ID_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"
			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "ACCOUNT_NUMBER",
				"VALUE" => $arResult["ACCOUNT_NUMBER_FORMATED"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_ACCOUNT_NUMBER_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"
			)
		);


		CPhoenix::getOptionHtml(
			array(
				"NAME" => "USER_NAME",
				"VALUE" => $arResult["USER_NAME_FORMATED"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_USER_NAME_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"

			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "USER_EMAIL",
				"VALUE" => $arResult["USER_EMAIL_FORMATED"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_USER_EMAIL_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"

			)
		);


		CPhoenix::getOptionHtml(
			array(
				"NAME" => "VOTE",
				"VALUE" => $arResult["VOTE_FORMATED"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_VOTE_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"
			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "RECOMMEND",
				"VALUE" => $arResult["RECOMMEND_FORMATED"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_RECOMMEND_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"
			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "EXPERIENCE",
				"VALUE" => $arResult["EXPERIENCE_FORMATED"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_EXPERIENCE_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"
			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "LIKE_COUNT",
				"VALUE" => $arResult["LIKE_COUNT"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_LIKE_COUNT_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"
			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "IMAGES_ID",
				"VALUE" => $arResult["IMAGES_SRC"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_IMAGES_ID_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"
			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "TEXT",
				"VALUE" => $arResult["TEXT"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_TEXT_TITLE"),
				"TYPE" => "text",
				"SIZE" => "150",
				"ROWS" => "15",
				"VIEW"=>"visual"
			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "POSITIVE",
				"VALUE" => $arResult["POSITIVE"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_POSITIVE_TITLE"),
				"TYPE" => "text",
				"SIZE" => "150",
				"ROWS" => "15",
				"VIEW"=>"visual"
			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "NEGATIVE",
				"VALUE" => $arResult["NEGATIVE"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_NEGATIVE_TITLE"),
				"TYPE" => "text",
				"SIZE" => "150",
				"ROWS" => "15",
				"VIEW"=>"visual"
			)
		);


		CPhoenix::getOptionHtml(
			array(
				"NAME" => "STORE_RESPONSE",
				"VALUE" => $arResult["STORE_RESPONSE"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_REVIEWS_EDIT_STORE_RESPONSE_TITLE"),
				"TYPE" => "text",
				"SIZE" => "150",
				"ROWS" => "15",
				"VIEW"=>"visual"
			)
		);


	$tabControl->EndTab();

	$tabControl->Buttons(array("disabled" => $MODULE_RIGHTS < "W", "back_url" =>"/bitrix/admin/concept_phoenix_admin_reviews_list.php?lang=".LANGUAGE_ID."&site_id=".$siteID));


	$tabControl->End();

?>
</form>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>