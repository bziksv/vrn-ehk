<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $PHOENIX_TEMPLATE_ARRAY;

$issetDBReviews = !empty($arResult["ITEMS"]) && $arResult["ITEMS"]["REVIEWS_COUNT"];

global $PHOENIX_TEMPLATE_ARRAY;
CPhoenix::phoenixOptionsValues(SITE_ID, array('rating'));
CPhoenix::setMess(array("review", "rating", "other"));

if($issetDBReviews)
{
	foreach ($arResult["ITEMS"] as $key => $value) {
    	$arResult["ITEMS"][$key] = intval($value);
    }
}

$arResult["FILTER"] = array();

$arResult["FILTER"] = array(
	"REVIEWS_COUNT" => array(
		"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["FILTER_MENU_ALL"]
	),
	"RECOMMEND_COUNT_Y" => array(
		"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["FILTER_MENU_1"]
	)
	
);

if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['USE_VOTE']['VALUE']['ACTIVE'] == "Y")
{
	$arResult["FILTER"] = array_merge($arResult["FILTER"], array(

		"VOTE_COUNT_5" => array(
			"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["FILTER_MENU_2"]
		),
		"VOTE_COUNT_4" => array(
			"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["FILTER_MENU_3"]
		),
		"VOTE_COUNT_3" => array(
			"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["FILTER_MENU_4"]
		),
		"VOTE_COUNT_1_2" => array(
			"NAME" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["FILTER_MENU_5"]
		)


	));
}


foreach ($arResult["FILTER"] as $key => $arItem){

	$arResult["FILTER"][$key]["COUNT"] = 0;

	if($arResult["ITEMS"][$key]>0)
	{
		$arResult["FILTER"][$key]["COUNT"] = $arResult["ITEMS"][$key];
		$arResult["FILTER"][$key]["STATUS"] = "active";
	}
		
	else
		$arResult["FILTER"][$key]["STATUS"] = "disabled";

}



$arResult["FILTER"]["REVIEWS_COUNT"]["STATUS"] = "active selected";



if($issetDBReviews && $arResult["ITEMS"]["VOTE_SUM"] && $arResult["ITEMS"]["VOTE_COUNT"])
	$arResult["RATING"] = round($arResult["ITEMS"]["VOTE_SUM"] / $arResult["ITEMS"]["VOTE_COUNT"], 1);
else
	$arResult["RATING"] = 0;



if($issetDBReviews && $arResult["ITEMS"]["REVIEWS_COUNT"]>0)
{
	$arResult["RATING_DESC"] = $arResult["ITEMS"]["REVIEWS_COUNT"]."&nbsp;".CPhoenix::getTermination(
		$arResult["ITEMS"]["REVIEWS_COUNT"], 
		array(
			$PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["PANEL_DESC_CNT_1"],
			$PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["PANEL_DESC_CNT_2"],
			$PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["PANEL_DESC_CNT_3"],
			$PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["PANEL_DESC_CNT_4"]
		)
	);

	$arResult["RATING_DESC"] = str_replace("#COUNT#", $arResult["RATING_DESC"], $PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["PANEL_DESC"]);
}





$arResult["RECCOMEND"] = array();


if($issetDBReviews && $arResult["ITEMS"]["RECOMMEND_COUNT_Y"])
	$arResult["RECCOMEND"]["COUNT_PERCENT"] = intval(round($arResult["ITEMS"]["RECOMMEND_COUNT_Y"] / ($arResult["ITEMS"]["RECOMMEND_COUNT_Y"] + $arResult["ITEMS"]["RECOMMEND_COUNT_N"]), 2) * 100);
else
	$arResult["RECCOMEND"]["COUNT_PERCENT"] = 0;



if($arResult["RECCOMEND"]["COUNT_PERCENT"]<=0)
	$arResult["RECCOMEND"]["COUNT_PERCENT"] = 0;


if($arResult["RECCOMEND"]["COUNT_PERCENT"] >= 1 && $arResult["RECCOMEND"]["COUNT_PERCENT"] <= 20)
	$arResult["RECCOMEND"]["STATUS"] = "rating-1";

if($arResult["RECCOMEND"]["COUNT_PERCENT"] >= 21 && $arResult["RECCOMEND"]["COUNT_PERCENT"] <= 40)
	$arResult["RECCOMEND"]["STATUS"] = "rating-2";

if($arResult["RECCOMEND"]["COUNT_PERCENT"] >= 41 && $arResult["RECCOMEND"]["COUNT_PERCENT"] <= 60)
	$arResult["RECCOMEND"]["STATUS"] = "rating-3";

if($arResult["RECCOMEND"]["COUNT_PERCENT"] >= 61 && $arResult["RECCOMEND"]["COUNT_PERCENT"] <= 80)
	$arResult["RECCOMEND"]["STATUS"] = "rating-4";

if($arResult["RECCOMEND"]["COUNT_PERCENT"] >= 81 && $arResult["RECCOMEND"]["COUNT_PERCENT"] <= 100)
	$arResult["RECCOMEND"]["STATUS"] = "rating-5";



if(intval($arResult["ITEMS"]["RECOMMEND_COUNT_Y"]) > 0)
{
	$arResult["RECCOMEND"]["DESC"] = $arResult["RECCOMEND"]["COUNT_PERCENT"]."%&nbsp;".$PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["PANEL_DESC_PERSON_1"];

	$arResult["RECCOMEND"]["DESC"] = str_replace("#COUNT#", $arResult["RECCOMEND"]["DESC"], $PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["RECOM_DESC"]);
}