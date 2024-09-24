<?
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\CPhoenixReviews;
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
    
    LocalRedirect("/bitrix/admin/concept_phoenix_admin_reviews_list.php?lang=".LANGUAGE_ID."&site_id=".$arS["LID"]);
}


global $APPLICATION, $DB;


$strSql = "SHOW TABLES LIKE 'phoenix_reviews_".$siteID."'";
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
		"LINK" => "concept_phoenix_admin_reviews_list.php?lang=".LANGUAGE_ID."&site_id=".$arRes['LID']
	);
}

$arCurrentSite = $arSites[$siteID];

$aContext = array();
$aContext[] = array(
	"TEXT"	=> "[".$arCurrentSite["LID"]."] ".$arCurrentSite['NAME'],
	"MENU" => $arDDMenu
);



$filter = array();

$currentMenuName = Loc::getMessage("PHOENIX_REVIEW_LIST_FILTER_ACTIVE_TITLE");

if(isset($_GET["reviewActive"]))
{
	$filter = array_merge( $filter, array('=ACTIVE' => $_GET["reviewActive"]));

	if($_GET["reviewActive"] == "Y")
		$currentMenuName = Loc::getMessage("PHOENIX_REVIEW_LIST_FILTER_ACTIVE_Y");
	else
		$currentMenuName = Loc::getMessage("PHOENIX_REVIEW_LIST_FILTER_ACTIVE_N");
}


$arDDMenu = array();

$arDDMenu = array(

	array(
		"TEXT" => Loc::getMessage("PHOENIX_REVIEW_LIST_FILTER_ACTIVE_DEF"),
		"LINK" => $APPLICATION->GetCurPageParam("", array("reviewActive"))
	),

	array(
		"TEXT" => Loc::getMessage("PHOENIX_REVIEW_LIST_FILTER_ACTIVE_Y"),
		"LINK" => $APPLICATION->GetCurPageParam("reviewActive=Y", array("reviewActive", "mode"))
		
	),

	array(
		"TEXT" => Loc::getMessage("PHOENIX_REVIEW_LIST_FILTER_ACTIVE_N"),
		"LINK" => $APPLICATION->GetCurPageParam("reviewActive=N", array("reviewActive", "mode"))
	),
);

$aContext[] = array(
	"TEXT"	=> $currentMenuName,
	"MENU" => $arDDMenu
);



$currentMenuName = Loc::getMessage("PHOENIX_REVIEW_LIST_FILTER_STORE_RESPONSE_TITLE");

if(isset($_GET["reviewResponse"]))
{
	if($_GET["reviewResponse"] == "Y")
	{
		$filter = array_merge($filter, array('!=STORE_RESPONSE' => ''));
		$currentMenuName = Loc::getMessage("PHOENIX_REVIEW_LIST_FILTER_STORE_RESPONSE_Y");
	}

	else
	{
		$filter = array_merge($filter, array('=STORE_RESPONSE' => ''));
		$currentMenuName = Loc::getMessage("PHOENIX_REVIEW_LIST_FILTER_STORE_RESPONSE_N");
	}
}


$arDDMenu = array();

$arDDMenu = array(

	array(
		"TEXT" => Loc::getMessage("PHOENIX_REVIEW_LIST_FILTER_STORE_RESPONSE_DEF"),
		"LINK" => $APPLICATION->GetCurPageParam("", array("reviewResponse"))
	),

	array(
		"TEXT" => Loc::getMessage("PHOENIX_REVIEW_LIST_FILTER_STORE_RESPONSE_Y"),
		"LINK" => $APPLICATION->GetCurPageParam("reviewResponse=Y", array("reviewResponse", "mode"))
	),

	array(
		"TEXT" => Loc::getMessage("PHOENIX_REVIEW_LIST_FILTER_STORE_RESPONSE_N"),
		"LINK" => $APPLICATION->GetCurPageParam("reviewResponse=N", array("reviewResponse", "mode"))
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





$adminListTableID = 't_reviews';
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

		$selectFields = array('ID', 'ACTIVE', 'PRODUCT_ID');

		$arItemsOldActive = CPhoenixReviews\CPhoenixReviewsTable::getList(array('select' => $selectFields,'filter' => array('=ID' => $arIds)))->fetchAll();
		$arItemsOldActiveValue = array();
		
		

		$productsID = array();
		foreach ($arItemsOldActive as $ID => $arFields)
		{
			/*$arItemsOldActiveValue[$arFields["ID"]] = $arFields["ACTIVE"];*/
			$productsID[$arFields["PRODUCT_ID"]] = $arFields["PRODUCT_ID"];
		}


		unset($arItemsOldActive);

		foreach ($FIELDS as $ID => $arFields)
		{

			$result = CPhoenixReviews\CPhoenixReviewsTable::update($ID, $arFields);
			
			if (!$result->isSuccess())
			{
				$mesEr = $result->getErrorMessages();

				foreach ($mesEr as $value)
				{
					if(strlen($value)>0)
						$adminList->AddUpdateError($value.GetMessage("PHOENIX_REVIEW_SAVE_ERR", array("#ID#" => $ID)), $ID);
				}
			}
		}

		foreach ($productsID as $productID)
		{
			CPhoenix::setReviewsInfo($productID);
		}


		
		/*
		$selectFields = array('ID', 'ACTIVE', 'PRODUCT_ID', 'VOTE', 'RECOMMEND');

		$arItems = CPhoenixReviews\CPhoenixReviewsTable::getList(array('select' => $selectFields,'filter' => array('=ID' => $arIds)))->fetchAll();
		
		if(!empty($arItems))
		{
			foreach ($arItems as $key => $arItem){

				if($arItemsOldActiveValue[$arItem["ID"]] != $arItem["ACTIVE"])
					CPhoenix::updateReviewsInfo(array("ACTION"=>($arItem["ACTIVE"]=="Y")?"add":"delete", "SITE_ID"=>$siteID, "PRODUCT_ID"=>$arItem["PRODUCT_ID"], "RECOMMEND" => $arItem["RECOMMEND"], "VOTE" => $arItem["VOTE"], "ACTIVE"=>"Y"));

			}
		}*/

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
	

	$arItems = CPhoenixReviews\CPhoenixReviewsTable::getList(array('select'=>array('PRODUCT_ID'),'filter' => array('=ID' => $arID)))->fetchAll();

	


	if(!empty($arItems))
	{
		$productsID = array();

		foreach ($arItems as $key => $arItem){
			$productsID[$arItem["PRODUCT_ID"]] = $arItem["PRODUCT_ID"];

			if($arItem["IMAGES_ID"] !== NULL)
			{
				$arItemsImageID = unserialize($arItem["IMAGES_ID"]);

				if(!empty($arItemsImageID))
    			{
    				foreach ($arItemsImageID as $arItemImageID){
    					CFile::Delete($arItemImageID);
    				}
    			}
			}
		}
	}

	foreach($arID as $k => $ID){
		$result = CPhoenixReviews\CPhoenixReviewsTable::delete($ID);
	}

	
	foreach($productsID as $ID){
		CPhoenix::setReviewsInfo($ID);
	}
}



$headerList = array();

$headerList['ID'] = array(
	'id' => 'ID',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_ID_TITLE'),
	'sort' => 'ID',
	'default' => true
);
$headerList['ACTIVE'] = array(
	'id' => 'ACTIVE',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_ACTIVE_TITLE'),
	'sort' => 'ACTIVE',
	'default' => true
);
$headerList['DATE'] = array(
	'id' => 'DATE',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_DATE_TITLE'),
	'sort' => 'DATE',
	'default' => true
);
$headerList['PRODUCT_ID'] = array(
	'id' => 'PRODUCT_ID',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_PRODUCT_ID_TITLE'),
	'sort' => 'PRODUCT_ID',
	'default' => true
);

$headerList['ACCOUNT_NUMBER'] = array(
	'id' => 'ACCOUNT_NUMBER',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_ACCOUNT_NUMBER_TITLE'),
	'sort' => 'ACCOUNT_NUMBER',
);

$headerList['USER_ID'] = array(
	'id' => 'USER_ID',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_USER_ID_TITLE'),
);

$headerList['USER_NAME'] = array(
	'id' => 'USER_NAME',
	'sort' => 'USER_NAME',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_USER_NAME_TITLE')
);

$headerList['USER_EMAIL'] = array(
	'id' => 'USER_EMAIL',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_USER_EMAIL_TITLE'),
	'sort' => 'USER_EMAIL',
);

$headerList['VOTE'] = array(
	'id' => 'VOTE',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_VOTE_TITLE')
);

$headerList['RECOMMEND'] = array(
	'id' => 'RECOMMEND',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_RECOMMEND_TITLE'),
	'sort' => 'RECOMMEND',
);

$headerList['EXPERIENCE'] = array(
	'id' => 'EXPERIENCE',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_EXPERIENCE_TITLE'),
	'sort' => 'EXPERIENCE'
);

$headerList['LIKE_COUNT'] = array(
	'id' => 'LIKE_COUNT',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_LIKE_COUNT_TITLE'),
	'sort' => 'LIKE_COUNT',
);

$headerList['IMAGES_ID'] = array(
	'id' => 'IMAGES_ID',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_IMAGES_ID_TITLE')
);

$headerList['TEXT'] = array(
	'id' => 'TEXT',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_TEXT_TITLE'),
	'sort' => 'TEXT',
	'default' => true
);

$headerList['POSITIVE'] = array(
	'id' => 'POSITIVE',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_POSITIVE_TITLE'),
	'sort' => 'POSITIVE',
);
$headerList['NEGATIVE'] = array(
	'id' => 'NEGATIVE',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_NEGATIVE_TITLE'),
	'sort' => 'NEGATIVE',
);

$headerList['STORE_RESPONSE'] = array(
	'id' => 'STORE_RESPONSE',
	'content' => GetMessage('PHOENIX_REVIEWS_LIST_STORE_RESPONSE_TITLE'),
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
	$countQuery = new Main\Entity\Query(CPhoenixReviews\CPhoenixReviewsTable::getEntity());
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

$reviewsIterator = new CAdminResult(CPhoenixReviews\CPhoenixReviewsTable::getList($getListParams), $adminListTableID);


if ($usePageNavigation)
{
	$reviewsIterator->NavStart($getListParams['limit'], $navyParams['SHOW_ALL'], $navyParams['PAGEN']);
	$reviewsIterator->NavRecordCount = $totalCount;
	$reviewsIterator->NavPageCount = $totalPages;
	$reviewsIterator->NavPageNomer = $navyParams['PAGEN'];
}
else
{
	$reviewsIterator->NavStart();
}
$adminList->NavText($reviewsIterator->GetNavPrint(Loc::GetMessage('PHOENIX_NAV_TITLE')));


$arResult = array();

while($ob = $reviewsIterator->Fetch())
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

    $ob['EXPERIENCE_VALUE'] = Loc::GetMessage('PHOENIX_REVIEWS_LIST_EXPERIENCE_'.$ob['EXPERIENCE']);

	$arResult["ITEMS"][] = $ob;

	$arResult["PRODUCTS_ID"][$ob["PRODUCT_ID"]] = $ob["PRODUCT_ID"];
}
unset($ob);


Main\Loader::includeModule("iblock");

$arSelect = Array("ID", "NAME", "IBLOCK_ID", "IBLOCK_SECTION_ID", "IBLOCK_TYPE_ID");
$arFilter = Array("ID" => $arResult["PRODUCTS_ID"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNextElement())
{ 
    $arFields = $ob->GetFields();

    $arFields["NAME"] = strip_tags($arFields["~NAME"]);

    $arResult["PRODUCTS"][$arFields["ID"]] = $arFields;
}
unset($ob);


if(!empty($arResult["ITEMS"]))
{

	foreach ($arResult["ITEMS"] as $key => $arItem) {
		$viewEdit = "/bitrix/admin/concept_phoenix_admin_reviews_edit.php?ID=".$arItem['ID']."&site_id=".$siteID;

		$urlEdit = "/bitrix/admin/concept_phoenix_admin_reviews_edit.php?ID=".$arItem['ID']."&site_id=".$siteID;

		$row = &$adminList->AddRow($arItem['ID'], $arItem, $urlEdit, Loc::GetMessage('PHOENIX_EDIT_TITLE'));

		$row->AddViewField("ID", '<a href="'.$viewEdit.'" title="'.Loc::GetMessage('PHOENIX_VIEW_TITLE').'">'.htmlspecialcharsBack($arItem['ID']).'</a>');


		$productURL = "/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".$arResult["PRODUCTS"][$arItem["PRODUCT_ID"]]["IBLOCK_ID"]."&type=".$arResult["PRODUCTS"][$arItem["PRODUCT_ID"]]["IBLOCK_TYPE_ID"]."&ID=".$arResult["PRODUCTS"][$arItem["PRODUCT_ID"]]["ID"];

		if(strlen($arResult["PRODUCTS"][$arItem["PRODUCT_ID"]]["IBLOCK_SECTION_ID"])>0)
			$productURL .= "&find_section_section=".$arResult["PRODUCTS"][$arItem["PRODUCT_ID"]]["IBLOCK_SECTION_ID"];
		else
			$productURL .= "&find_section_section=0";

		$productHTML = "<a href='".$productURL."' target='_blank'>".$arResult["PRODUCTS"][$arItem["PRODUCT_ID"]]["NAME"]."</a>";


		$row->AddViewField("PRODUCT_ID", $productHTML);

		if ($selectFieldsMap['EXPERIENCE'])
			$row->AddViewField("EXPERIENCE", $arItem["EXPERIENCE_VALUE"]);

		if ($selectFieldsMap['IMAGES_ID'])
			$row->AddViewField("IMAGES_ID", $arItem["IMAGES_SRC"]);


		if ($selectFieldsMap['ACTIVE'])
			$row->AddCheckField("ACTIVE");


		if ($selectFieldsMap['STORE_RESPONSE'])
			$row->AddInputField("STORE_RESPONSE", array("size" => "30"));


		if ($selectFieldsMap['TEXT'])
			$row->AddInputField("TEXT", array("size" => "30"));

		if ($selectFieldsMap['POSITIVE'])
			$row->AddInputField("POSITIVE", array("size" => "30"));

		if ($selectFieldsMap['NEGATIVE'])
			$row->AddInputField("NEGATIVE", array("size" => "30"));


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
		array("title" => GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value" => $reviewsIterator->SelectedRowsCount()),
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