<?
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\CPhoenixComments;
use Bitrix\Main\Localization\Loc;


require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");





$moduleID = "concept.phoenix";
Loader::includeModule($moduleID);

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


global $APPLICATION, $DB;


$strSql = "SHOW TABLES LIKE 'phoenix_comments_".$siteID."'";
$res = $DB->Query($strSql, false, $err_mess.__LINE__);

if($res->SelectedRowsCount() <= 0)
	LocalRedirect("/bitrix/admin/");



$MODULE_RIGHTS = $APPLICATION->GetGroupRight($moduleID);

if($MODULE_RIGHTS < "R")
    LocalRedirect("/bitrix/");


$aMenu = array();

$arDDMenu = array();

$arDDMenu[] = array(
	"TEXT" => Loc::getMessage("PHOENIX_CHOOSE_SITE"),
	"ACTION" => false
);

foreach($arSites as $arRes)
{
	$arDDMenu[] = array(
		"TEXT" => "[".$arRes["LID"]."] ".$arRes["NAME"],
		"LINK" => "concept_phoenix_admin_comments_list.php?lang=".LANGUAGE_ID."&site_id=".$arRes['LID']
	);
}

$arCurrentSite = $arSites[$siteID];

$aContext = array();
$aContext[] = array(
	"TEXT"	=> "[".$arCurrentSite["LID"]."] ".$arCurrentSite['NAME'],
	"MENU" => $arDDMenu
);



$filter = array();

$currentMenuName = Loc::getMessage("PHOENIX_COMMENTS_LIST_FILTER_ACTIVE_TITLE");

if(isset($_GET["commentsActive"]))
{
	$filter = array_merge( $filter, array('=ACTIVE' => $_GET["commentsActive"]));

	if($_GET["commentsActive"] == "Y")
		$currentMenuName = Loc::getMessage("PHOENIX_COMMENTS_LIST_FILTER_ACTIVE_Y");
	else
		$currentMenuName = Loc::getMessage("PHOENIX_COMMENTS_LIST_FILTER_ACTIVE_N");
}


$arDDMenu = array();

$arDDMenu = array(

	array(
		"TEXT" => Loc::getMessage("PHOENIX_COMMENTS_LIST_FILTER_ACTIVE_DEF"),
		"LINK" => $APPLICATION->GetCurPageParam("", array("commentsActive"))
	),

	array(
		"TEXT" => Loc::getMessage("PHOENIX_COMMENTS_LIST_FILTER_ACTIVE_Y"),
		"LINK" => $APPLICATION->GetCurPageParam("commentsActive=Y", array("commentsActive", "mode"))
		
	),

	array(
		"TEXT" => Loc::getMessage("PHOENIX_COMMENTS_LIST_FILTER_ACTIVE_N"),
		"LINK" => $APPLICATION->GetCurPageParam("commentsActive=N", array("commentsActive", "mode"))
	),
);

$aContext[] = array(
	"TEXT"	=> $currentMenuName,
	"MENU" => $arDDMenu
);



$currentMenuName = Loc::getMessage("PHOENIX_COMMENTS_LIST_FILTER_RESPONSE_TITLE");

if(isset($_GET["commentsResponse"]))
{
	if($_GET["commentsResponse"] == "Y")
	{
		$filter = array_merge($filter, array('!=RESPONSE' => ''));
		$currentMenuName = Loc::getMessage("PHOENIX_COMMENTS_LIST_FILTER_RESPONSE_Y");
	}

	else
	{
		$filter = array_merge($filter, array('=RESPONSE' => ''));
		$currentMenuName = Loc::getMessage("PHOENIX_COMMENTS_LIST_FILTER_RESPONSE_N");
	}
}


$arDDMenu = array();

$arDDMenu = array(

	array(
		"TEXT" => Loc::getMessage("PHOENIX_COMMENTS_LIST_FILTER_RESPONSE_DEF"),
		"LINK" => $APPLICATION->GetCurPageParam("", array("commentsResponse"))
	),

	array(
		"TEXT" => Loc::getMessage("PHOENIX_COMMENTS_LIST_FILTER_RESPONSE_Y"),
		"LINK" => $APPLICATION->GetCurPageParam("commentsResponse=Y", array("commentsResponse", "mode"))
	),

	array(
		"TEXT" => Loc::getMessage("PHOENIX_COMMENTS_LIST_FILTER_RESPONSE_N"),
		"LINK" => $APPLICATION->GetCurPageParam("commentsResponse=N", array("commentsResponse", "mode"))
	),
);

$aContext[] = array(
	"TEXT"	=> $currentMenuName,
	"MENU" => $arDDMenu
);






$by = 'DATE';
$order = 'DESC';

if(isset($_GET["by"]))
	$by = $_GET["by"];

if(isset($_GET["order"]))
	$order = strtoupper($_GET["order"]);





$adminListTableID = 't_comments';
$adminList = new CAdminList($adminListTableID);
$adminSort = new CAdminSorting($adminListTableID, $by, $order);
$adminList = new CAdminList($adminListTableID, $adminSort);





if ($adminList->EditAction() && $MODULE_RIGHTS == "W")
{
	
	if (isset($FIELDS) && is_array($FIELDS))
	{
		$arIds = array();

		foreach ($FIELDS as $ID => $arFields)
		{
			$arIds[] = $ID;
		}

		$selectFields = array('ID', 'ACTIVE');

		$arItemsOldActive = CPhoenixComments\CPhoenixCommentsTable::getList(array('select' => $selectFields,'filter' => array('=ID' => $arIds)))->fetchAll();
		$arItemsOldActiveValue = array();
		

		foreach ($arItemsOldActive as $ID => $arFields)
		{
			$arItemsOldActiveValue[$arFields["ID"]] = $arFields["ACTIVE"];
		}

		unset($arItemsOldActive);

		foreach ($FIELDS as $ID => $arFields)
		{

			$result = CPhoenixComments\CPhoenixCommentsTable::update($ID, $arFields);
			
			if (!$result->isSuccess())
			{
				$mesEr = $result->getErrorMessages();

				foreach ($mesEr as $value)
				{
					if(strlen($value)>0)
						$adminList->AddUpdateError($value.GetMessage("PHOENIX_COMMENTS_SAVE_ERR", array("#ID#" => $ID)), $ID);
				}
			}
		}

	}


}


if ($MODULE_RIGHTS == "W" && $arID = $adminList->GroupAction())
{

	foreach($arID as $key => $ID)
	{
		$ID = (string)$ID;
		
		if ($ID == '')
			unset($arID[$key]);
	}

	$arItems = CPhoenixComments\CPhoenixCommentsTable::getList(array('filter' => array('=ID' => $arID)))->fetchAll();



	foreach($arID as $k => $ID){
		$result = CPhoenixComments\CPhoenixCommentsTable::delete($ID);
	}
}



$headerList = array();

$headerList['ID'] = array(
	'id' => 'ID',
	'content' => GetMessage('PHOENIX_COMMENTS_LIST_ID_TITLE'),
	'sort' => 'ID',
	'default' => true
);
$headerList['ACTIVE'] = array(
	'id' => 'ACTIVE',
	'content' => GetMessage('PHOENIX_COMMENTS_LIST_ACTIVE_TITLE'),
	'sort' => 'ACTIVE',
	'default' => true
);
$headerList['DATE'] = array(
	'id' => 'DATE',
	'content' => GetMessage('PHOENIX_COMMENTS_LIST_DATE_TITLE'),
	'sort' => 'DATE',
	'default' => true
);
$headerList['ELEMENT_ID'] = array(
	'id' => 'ELEMENT_ID',
	'content' => GetMessage('PHOENIX_COMMENTS_LIST_ELEMENT_ID_TITLE'),
	'sort' => 'ELEMENT_ID',
	'default' => true
);

$headerList['USER_ID'] = array(
	'id' => 'USER_ID',
	'content' => GetMessage('PHOENIX_COMMENTS_LIST_USER_ID_TITLE'),
);

$headerList['USER_NAME'] = array(
	'id' => 'USER_NAME',
	'sort' => 'USER_NAME',
	'content' => GetMessage('PHOENIX_COMMENTS_LIST_USER_NAME_TITLE')
);

$headerList['USER_EMAIL'] = array(
	'id' => 'USER_EMAIL',
	'content' => GetMessage('PHOENIX_COMMENTS_LIST_USER_EMAIL_TITLE'),
	'sort' => 'USER_EMAIL',
);


$headerList['TEXT'] = array(
	'id' => 'TEXT',
	'content' => GetMessage('PHOENIX_COMMENTS_LIST_TEXT_TITLE'),
	'sort' => 'TEXT',
	'default' => true
);


$headerList['RESPONSE'] = array(
	'id' => 'RESPONSE',
	'content' => GetMessage('PHOENIX_COMMENTS_LIST_RESPONSE_TITLE'),
	'default' => true
);


$adminList->AddHeaders($headerList);

$selectFields = array_fill_keys($adminList->GetVisibleHeaderColumns(), true);
$selectFields['ID'] = true;
$selectFieldsMap = array_fill_keys(array_keys($headerList), false);
$selectFieldsMap = array_merge($selectFieldsMap, $selectFields);

$usePageNavigation = true;
$navyParams = array();
if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'excel')
{
	$usePageNavigation = false;
}
else
{
	$navyParams = CDBResult::GetNavParams(CAdminResult::GetNavSize($adminListTableID));
	if ($navyParams['SHOW_ALL'])
	{
		$usePageNavigation = false;
	}
	else
	{
		$navyParams['PAGEN'] = (int)$navyParams['PAGEN'];
		$navyParams['SIZEN'] = (int)$navyParams['SIZEN'];
	}
}


$selectFields = array_keys($selectFields);


$getListParams = array(
	'select' => $selectFields,
	'filter' => $filter,
	'order' => array($by => $order)
);

if ($usePageNavigation)
{
	$countQuery = new Main\Entity\Query(CPhoenixComments\CPhoenixCommentsTable::getEntity());
	$countQuery->addSelect(new Main\Entity\ExpressionField('CNT', 'COUNT(1)'));
	$countQuery->setFilter($getListParams['filter']);
	$totalCount = $countQuery->setLimit(null)->setOffset(null)->exec()->fetch();
	unset($countQuery);
	$totalCount = (int)$totalCount['CNT'];

	$navyParams['SIZEN'] = 10;
	if ($totalCount > 0)
	{
		$totalPages = ceil($totalCount/$navyParams['SIZEN']);
		if ($navyParams['PAGEN'] > $totalPages)
			$navyParams['PAGEN'] = $totalPages;
		$getListParams['limit'] = $navyParams['SIZEN'];
		$getListParams['offset'] = $navyParams['SIZEN']*($navyParams['PAGEN']-1);
	}
	else
	{
		$navyParams['PAGEN'] = 1;
		$getListParams['limit'] = $navyParams['SIZEN'];
		$getListParams['offset'] = 0;
	}
}

$commentsIterator = new CAdminResult(CPhoenixComments\CPhoenixCommentsTable::getList($getListParams), $adminListTableID);


if ($usePageNavigation)
{
	$commentsIterator->NavStart($getListParams['limit'], $navyParams['SHOW_ALL'], $navyParams['PAGEN']);
	$commentsIterator->NavRecordCount = $totalCount;
	$commentsIterator->NavPageCount = $totalPages;
	$commentsIterator->NavPageNomer = $navyParams['PAGEN'];
}
else
{
	$commentsIterator->NavStart();
}
$adminList->NavText($commentsIterator->GetNavPrint(Loc::GetMessage('PHOENIX_NAV_TITLE')));


$arResult = array();

while($ob = $commentsIterator->Fetch())
{

	foreach ($ob as $key => $value)
    {
	    $ob[$key] = htmlspecialcharsBack($value);
    }

    $ob["IMAGES_SRC"] = "";

    if($ob["IMAGES_ID"] !== null)
    {
    	$ob["IMAGES_ID"] = unserialize($ob["IMAGES_ID"]);

    	if(!empty($ob["IMAGES_ID"]))
    	{
    		foreach ($ob["IMAGES_ID"] as $key => $value)
    		{
    			$arImg = $arImgBig = array();
    			$arImg = CFile::ResizeImageGet($value, array('width'=>64, 'height'=>64), BX_RESIZE_IMAGE_EXACT, false, Array(), false, 85);
    			$arImgBig = CFile::ResizeImageGet($value, array('width'=>1200, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, 85);
    			$ob["IMAGES_SRC"] .= "<a href=\"".$arImgBig['src']."\" target=\"_blank\"><img style=\"margin: 0 5px 5px 0;\" src=\"".$arImg['src']."\"></a>";
    		}
    	}
    }

    $ob['EXPERIENCE_VALUE'] = Loc::GetMessage('PHOENIX_COMMENTS_LIST_EXPERIENCE_'.$ob['EXPERIENCE']);

	$arResult["ITEMS"][] = $ob;

	$arResult["ELEMENTS_ID"][$ob["ELEMENT_ID"]] = $ob["ELEMENT_ID"];
}
unset($ob);


Main\Loader::includeModule("iblock");

$arSelect = Array("ID", "NAME", "IBLOCK_ID", "IBLOCK_SECTION_ID", "IBLOCK_TYPE_ID");
$arFilter = Array("ID" => $arResult["ELEMENTS_ID"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNextElement())
{ 
    $arFields = $ob->GetFields();

    $arFields["NAME"] = strip_tags($arFields["~NAME"]);

    $arResult["ELEMENTS"][$arFields["ID"]] = $arFields;
}
unset($ob);


if(!empty($arResult["ITEMS"]))
{

	foreach ($arResult["ITEMS"] as $key => $arItem) {
		$viewEdit = "/bitrix/admin/concept_phoenix_admin_comments_edit.php?ID=".$arItem['ID']."&site_id=".$siteID;

		$urlEdit = "/bitrix/admin/concept_phoenix_admin_comments_edit.php?ID=".$arItem['ID']."&site_id=".$siteID;

		$row = &$adminList->AddRow($arItem['ID'], $arItem, $urlEdit, Loc::GetMessage('PHOENIX_EDIT_TITLE'));

		$row->AddViewField("ID", '<a href="'.$viewEdit.'" title="'.Loc::GetMessage('PHOENIX_VIEW_TITLE').'">'.htmlspecialcharsBack($arItem['ID']).'</a>');


		$elementURL = "/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".$arResult["ELEMENTS"][$arItem["ELEMENT_ID"]]["IBLOCK_ID"]."&type=".$arResult["ELEMENTS"][$arItem["ELEMENT_ID"]]["IBLOCK_TYPE_ID"]."&ID=".$arResult["ELEMENTS"][$arItem["ELEMENT_ID"]]["ID"];

		if(strlen($arResult["ELEMENTS"][$arItem["ELEMENT_ID"]]["IBLOCK_SECTION_ID"])>0)
			$elementURL .= "&find_section_section=".$arResult["ELEMENTS"][$arItem["ELEMENT_ID"]]["IBLOCK_SECTION_ID"];
		else
			$elementURL .= "&find_section_section=0";

		$elementHTML = "<a href='".$elementURL."' target='_blank'>".$arResult["ELEMENTS"][$arItem["ELEMENT_ID"]]["NAME"]."</a>";


		$row->AddViewField("ELEMENT_ID", $elementHTML);


		if ($selectFieldsMap['ACTIVE'])
			$row->AddCheckField("ACTIVE");


		if ($selectFieldsMap['RESPONSE'])
			$row->AddInputField("RESPONSE", array("size" => "30"));


		if ($selectFieldsMap['TEXT'])
			$row->AddInputField("TEXT", array("size" => "30"));


		$arActions = array();
		$arActions[] = array(
			"ICON" => "edit",
			"DEFAULT" => "Y",
			"TEXT" => GetMessage("MAIN_ADMIN_MENU_EDIT"),
			"ACTION" => $adminList->ActionRedirect($urlEdit)
		);

		$arActions[] = array("SEPARATOR" => true);
			$arActions[] = array(
				"ICON" => "delete",
				"TEXT" => GetMessage("MAIN_ADMIN_MENU_DELETE"),
				"ACTION" => "if(confirm('".GetMessage('KRAKEN_SHOP_DELIVERY_CONFIRM_DEL_MESSAGE')."')) ".$adminList->ActionDoGroup($arItem['ID'], "delete")
			);



		$row->AddActions($arActions);
	}
}





$adminList->AddFooter(
	array(
		array("title" => GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value" => $commentsIterator->SelectedRowsCount()),
		array("counter" => true, "title" => GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value" => "0"),
	)
);

if ($MODULE_RIGHTS == "W")
{
	$adminList->AddGroupActionTable(Array(
		"delete"=>GetMessage("MAIN_ADMIN_LIST_DELETE"),
		)
	);
}

$adminList->AddAdminContextMenu($aContext);

$adminList->CheckListMode();

$APPLICATION->SetTitle(Loc::getMessage("PHOENIX_PAGE_TITLE"));

Main\Page\Asset::getInstance()->addJs('/bitrix/js/iblock/iblock_edit.js');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<style>
	.adm-nav-pages-block{
		float: none;
	}
	.adm-nav-pages-total-block,
	.adm-nav-pages-number-block{
		display: none;
	}
</style>
<?

$adminList->DisplayList();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");