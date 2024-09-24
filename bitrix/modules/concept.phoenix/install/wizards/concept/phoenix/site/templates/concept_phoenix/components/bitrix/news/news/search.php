<?
/*$arSearchCodes = Array();

$arSearchCodes["CATALOG"] = "concept_phoenix_catalog";
$arSearchCodes["BLOG"] = "concept_phoenix_blog";
$arSearchCodes["NEWS"] = "concept_phoenix_news";
$arSearchCodes["ACTIONS"] = "concept_phoenix_action";


$code = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "CODE");
$code = str_replace("_".SITE_ID, "", $code);


$key = array_search($code, $arSearchCodes);*/
?>

<?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['SHOW_IN']['VALUE'][$currentMainPageForSearch] == "Y" ):?>

	<div class="search-block col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
		<?$APPLICATION->IncludeComponent("concept:phoenix.search.line", "", 

			Array(
				"START_PAGE"=>ToLower($currentMainPageForSearch),
				"CONTAINER_ID" => "search-page-input-container-news",
        		"INPUT_ID" => "search-page-input-news",
        		"COMPOSITE_FRAME_MODE" => "N",
        		"SHOW_RESULTS" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['FASTSEARCH_ACTIVE']['VALUE']['ACTIVE']
			)

		);?>
	</div>

<?endif;?>