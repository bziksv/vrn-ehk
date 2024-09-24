<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */

use Bitrix\Main\Loader;
use Bitrix\CPhoenixReviews;

if(!Loader::includeModule("iblock"))
{
	$this->abortResultCache();
	ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
	return;
}

$arResult = array();
$arResult["ITEMS"] = array();


$page = intval($arParams["PAGE"]);

if($page<=0)
	$page = 1;

$arResult["NEXT_PAGE"] = $page + 1;



$limit1 = intval($arParams["LIMIT_1"]);

if($limit1<=0)
	$limit1 = 3;



$limit2 = intval($arParams["LIMIT_2"]);

if($limit2<=0)
	$limit2 = 10;


$limitCount = $limit1;

if($page>1)
	$limitCount = $limit2;


if($page == 1)
	$offset = 0;

else if($page > 1)
	$offset = $limit2 * ($page-2) + $limit1;



$filter = array('=PRODUCT_ID' => $arParams["PRODUCT_ID"], 'ACTIVE'=>"Y");


if($arParams["FILTER"])
{
	if($arParams["FILTER"] == "RECOMMEND_COUNT_Y")
		$filter['=RECOMMEND'] = "Y";

	else if($arParams["FILTER"] == "VOTE_COUNT_1_2")
		$filter['><VOTE'] = array(1,2);

	else if($arParams["FILTER"] == "VOTE_COUNT_3")
		$filter['=VOTE'] = 3;

	else if($arParams["FILTER"] == "VOTE_COUNT_4")
		$filter['=VOTE'] = 4;

	else if($arParams["FILTER"] == "VOTE_COUNT_5")
		$filter['=VOTE'] = 5;
}




$arResult["ITEMS"] = CPhoenixReviews\CPhoenixReviewsTable::getList(array('filter' => $filter,'order'=>array('DATE' => 'DESC'), 'limit' => $limitCount, 'offset' => $offset, "cache"=>array("ttl"=>3600)))->fetchAll();


global $PHOENIX_TEMPLATE_ARRAY;
CPhoenix::phoenixOptionsValues(SITE_ID, array('rating', "other"));
CPhoenix::setMess(array("review", "rating", "other"));

if(!empty($arResult["ITEMS"]))
{
    foreach ($arResult["ITEMS"] as $key => $value) {
        $arResult["ITEMS"][$key] = CPhoenixDB::CPhoenixMerge($value);
    }
}





$offset = $limit2 * ($page-1) + $limit1;


$arResult["NEXT_ITEMS"] = CPhoenixReviews\CPhoenixReviewsTable::getList(array('select'=>array('ID'),'filter' =>$filter,'order'=>array('DATE' => 'DESC'), 'limit' => $limit2, 'offset' => $offset, "cache"=>array("ttl"=>3600)))->fetchAll();




$arResult["COUNT"] = count($arResult["NEXT_ITEMS"]);
        


$arResult["BTN_NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["BTN_MORE_REVIEWS"].$arResult["COUNT"]."&nbsp;".CPhoenix::getTermination(
		$arResult["COUNT"], 
		array(
			$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["BTN_MORE_REVIEWS_CNT_1"],
			$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["BTN_MORE_REVIEWS_CNT_2"],
			$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["BTN_MORE_REVIEWS_CNT_3"],
			$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["BTN_MORE_REVIEWS_CNT_4"]
		)
	);


$this->includeComponentTemplate();