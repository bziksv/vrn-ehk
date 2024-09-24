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
use Bitrix\CPhoenixComments;



$arResult = array();


global $PHOENIX_TEMPLATE_ARRAY;
CPhoenix::phoenixOptionsValues(SITE_ID, array('start','comments', 'other'));
CPhoenix::setMess(array('comments'));


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


$filter = array('=ELEMENT_ID' => $arParams["ELEMENT_ID"], 'ACTIVE'=>"Y");



$arResult["ITEMS"] = CPhoenixComments\CPhoenixCommentsTable::getList(array('filter' => $filter,'order'=>array('DATE' => 'DESC'), 'limit' => $limitCount, 'offset' => $offset, "cache"=>array("ttl"=>3600)))->fetchAll();



if(!empty($arResult["ITEMS"]))
{
    foreach ($arResult["ITEMS"] as $key => $value) {
        $arResult["ITEMS"][$key] = CPhoenixDB::CPhoenixMerge($value);
    }
}


$offset = $limit2 * ($page-1) + $limit1;


$arResult["NEXT_ITEMS"] = CPhoenixComments\CPhoenixCommentsTable::getList(array('select'=>array('ID'),'filter' =>$filter,'order'=>array('DATE' => 'DESC'), 'limit' => $limit2, 'offset' => $offset, "cache"=>array("ttl"=>3600)))->fetchAll();




$arResult["COUNT"] = count($arResult["NEXT_ITEMS"]);
        


$arResult["BTN_NAME"] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["BTN_MORE"].$arResult["COUNT"]."&nbsp;".CPhoenix::getTermination(
	$arResult["COUNT"], 
	array(
		$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["BTN_MORE_COMMENTS_CNT_1"],
		$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["BTN_MORE_COMMENTS_CNT_2"],
		$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["BTN_MORE_COMMENTS_CNT_3"],
		$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["BTN_MORE_COMMENTS_CNT_4"]
	)
);



$this->includeComponentTemplate();