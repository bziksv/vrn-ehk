<?
use Bitrix\CPhoenixComments;
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
	LocalRedirect("/bitrix/admin/concept_phoenix_admin_comments_list.php");


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
    
    LocalRedirect("/bitrix/admin/concept_phoenix_admin_comments_list.php?lang=".LANGUAGE_ID."&site_id=".$arS["LID"]);
}


global $APPLICATION;
$MODULE_RIGHTS = $APPLICATION->GetGroupRight($moduleID);

if($MODULE_RIGHTS < "R")
    LocalRedirect("/bitrix/");

$aTabs = array(
	array(
		"DIV" => "maintab",
		"TAB" => Loc::getMessage("PHOENIX_COMMENTS_EDIT_MAINTAB"),
		"ICON" => "main_user_edit"
	),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

if($_REQUEST["action"] == "delete")
	$APPLICATION->SetTitle(GetMessage("PHOENIX_COMMENTS_EDIT_DEL_TITLE"));
else if(strlen($ID) > 0)
	$APPLICATION->SetTitle(GetMessage("PHOENIX_COMMENTS_EDIT_EDIT_TITLE"));


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");


$result = CPhoenixComments\CPhoenixCommentsTable::getList(array(
    'filter' => array('=ID' => $ID)
));

$arResult = array();

if($arResult = $result->fetch())
{

	$arSelect = Array("ID", "NAME", "IBLOCK_ID", "IBLOCK_SECTION_ID", "IBLOCK_TYPE_ID");
	$arFilter = Array("ID" => $arResult["ELEMENT_ID"]);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

	while($ob = $res->GetNextElement())
	{ 
	    $arFields = $ob->GetFields();

	    $arResult["ELEMENT_ID_HTML"] = "<a href='/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".$arFields["IBLOCK_ID"]."&type=".$arFields["IBLOCK_TYPE_ID"]."&ID=".$arFields["ID"]."' target='_blank'>".strip_tags($arFields["~NAME"])."</a>";
	}


	foreach ($arResult as $key => $value)
	{
		

		if($value === NULL)
			$arResult[$key."_FORMATED"] = '-';
		else
			$arResult[$key."_FORMATED"] = $value;
	}


}
else if($_REQUEST["deleted"] == 1)
{
	echo CAdminMessage::ShowNote(Loc::getMessage("PHOENIX_COMMENTS_EDIT_DEL_COMPLTED_MESS"));
}

else 
	LocalRedirect("/bitrix/admin/concept_phoenix_admin_comments_list.php?lang=".LANGUAGE_ID."&site_id=".$arS["LID"]);



if($_REQUEST["action"] == "delete" && !isset($_REQUEST["deleted"]) && $MODULE_RIGHTS == "W")
{
    
  
	$ID = $_REQUEST['ID'];
	$result = CPhoenixComments\CPhoenixCommentsTable::delete($ID);
	
	LocalRedirect($APPLICATION->GetCurUri("deleted=1"));
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && $MODULE_RIGHTS=="W" && !empty($_REQUEST['Update']) && !isset($_REQUEST["saved"]))
{

	$errors = false;

	$arFields = array(
		'TEXT' => htmlspecialcharsbx($_REQUEST["TEXT"]),
		'RESPONSE' => htmlspecialcharsbx($_REQUEST["RESPONSE"]),
		'ACTIVE' => htmlspecialcharsbx($_REQUEST["ACTIVE"])
	);



	$result = CPhoenixComments\CPhoenixCommentsTable::update($ID, $arFields);
		
		
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

    	if (!isset($_REQUEST['apply']))
			LocalRedirect("/bitrix/admin/concept_phoenix_admin_comments_list.php?lang=".LANGUAGE_ID."&site_id=".$arS["LID"]);

		LocalRedirect($APPLICATION->GetCurUri("ID=".$ID."&".$tabControl->ActiveTabParam()."&saved=1&site_id=".$siteID));
    }

}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");


if($_REQUEST["saved"] == 1)
	echo CAdminMessage::ShowNote(Loc::getMessage("PHOENIX_COMMENTS_EDIT_SAVE_SUC"));



$aContext = array(
	array(
		"ICON" => "btn_list",
		"TEXT" => GetMessage("MAIN_ADMIN_MENU_LIST"),
		"LINK" => "concept_phoenix_admin_comments_list.php?lang=".LANGUAGE_ID."&site_id=".$siteID,
		"TITLE" => GetMessage("MAIN_ADMIN_MENU_LIST")
	),
);

if (strlen($ID) > 0 && $_REQUEST["action"] != "delete" && $MODULE_RIGHTS == "W")
{
	$aContext[] = array(
		"ICON" => "btn_delete",
		"TEXT" => GetMessage("MAIN_ADMIN_MENU_DELETE"),
		"ONCLICK" => "javascript:if(confirm('".GetMessageJS("PHOENIX_COMMENTS_EDIT_DEL_PROMT_MESS")."'))window.location='concept_phoenix_admin_comments_edit.php?site_id=".$siteID."&action=delete&ID=".CUtil::JSEscape($ID)."';",
	);
}

$context = new CAdminContextMenu($aContext);
$context->Show();
?>

<form id="phoenix_comments" method="POST" action="<?=$APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" name="phoenix_comments" style="display:<?=($_REQUEST["deleted"] == 1)?"none":""?>">
	<input type="hidden" name="ID" value="<?=htmlspecialcharsbx($ID); ?>">
	<input type="hidden" name="Update" value="Y">
	<input type="hidden" name="site_id" value="<?=$siteID?>">

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
				"DESCRIPTION" => Loc::getMessage("PHOENIX_COMMENTS_EDIT_ID_TITLE"),
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
						"DESCRIPTION" => Loc::getMessage("PHOENIX_COMMENTS_EDIT_ACTIVE_TITLE"),
						"VALUE" => "Y"

					)
				),
				"VALUE" => array("ACTIVE" => $arResult["ACTIVE"]),
				"DESCRIPTION" => Loc::getMessage("PHOENIX_COMMENTS_EDIT_ACTIVE_TITLE"),
				"TYPE" => "checkbox",
			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "DATE",
				"VALUE" => $arResult["DATE"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_COMMENTS_EDIT_DATE_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"
			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "ELEMENT_ID",
				"VALUE" => $arResult["ELEMENT_ID_HTML"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_COMMENTS_EDIT_ELEMENT_ID_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"
			)
		);


		CPhoenix::getOptionHtml(
			array(
				"NAME" => "USER_NAME",
				"VALUE" => $arResult["USER_NAME_FORMATED"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_COMMENTS_EDIT_USER_NAME_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"

			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "USER_EMAIL",
				"VALUE" => $arResult["USER_EMAIL_FORMATED"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_COMMENTS_EDIT_USER_EMAIL_TITLE"),
				"TYPE" => "text",
				"DISABLED" => "Y"

			)
		);

		CPhoenix::getOptionHtml(
			array(
				"NAME" => "TEXT",
				"VALUE" => $arResult["TEXT"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_COMMENTS_EDIT_TEXT_TITLE"),
				"TYPE" => "text",
				"SIZE" => "150",
				"ROWS" => "15",
				"VIEW"=>"visual"
			)
		);


		CPhoenix::getOptionHtml(
			array(
				"NAME" => "RESPONSE",
				"VALUE" => $arResult["RESPONSE"],
				"DESCRIPTION" => Loc::getMessage("PHOENIX_COMMENTS_EDIT_RESPONSE_TITLE"),
				"TYPE" => "text",
				"SIZE" => "150",
				"ROWS" => "15",
				"VIEW"=>"visual"
			)
		);


	$tabControl->EndTab();

	$tabControl->Buttons(array("disabled" => $MODULE_RIGHTS < "W", "back_url" =>"/bitrix/admin/concept_phoenix_admin_comments_list.php?lang=".LANGUAGE_ID."&site_id=".$siteID));


	$tabControl->End();

?>
</form>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>