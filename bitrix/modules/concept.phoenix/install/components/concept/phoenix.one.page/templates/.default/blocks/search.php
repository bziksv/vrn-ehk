
<?if(strlen($arItem["PROPERTIES"]["SEARCH_MARGIN_FROM_TEXT"]["VALUE"]) || strlen($arItem["PROPERTIES"]["SEARCH_MARGIN_FROM_TEXT_MOB"]["VALUE"])):?>

	<style>

	
	<?
    	if(strlen($arItem["PROPERTIES"]["SEARCH_MARGIN_FROM_TEXT"]["VALUE"]))
    	{
    		echo 
    		"
	    		.constructor-search-".$arItem["ID"]

	    		."
	    		{
	    			margin-top: ".$arItem["PROPERTIES"]["SEARCH_MARGIN_FROM_TEXT"]["VALUE"]."px !important;
				}
    		";
    	}

    	if(strlen($arItem["PROPERTIES"]["SEARCH_MARGIN_FROM_TEXT_MOB"]["VALUE"]))
    	{

    		echo 
    		"
	    		@media (max-width: 991px){

	    			.constructor-search-".$arItem["ID"]
	    			."
	    			{
	    				margin-top: ".$arItem["PROPERTIES"]["SEARCH_MARGIN_FROM_TEXT_MOB"]["VALUE"]."px !important;
	    			}
	    		}
	    	";
    	}
	?>
	</style>

<?endif;?>


<?if(isset($arItem["ADMIN_CHANGE"])):?>
	<?
		$params = array(
			'ID' => $arItem["ID"],
			'IBLOCK_ID' => $arItem["IBLOCK_ID"],
			'IBLOCK_TYPE_ID' => $arItem["IBLOCK_TYPE_ID"],
			'IBLOCK_SECTION_ID' => $arItem["IBLOCK_SECTION_ID"],
			'NAME' => $arItem["NAME"],
			'CLASS' => 'in-center',
			'ATTRS' => 'style="top: -200px;"'
		);
	?>

<?CPhoenix::admin_setting_cust($params);?>
<?endif;?>

<div class="container-form <?=$arItem["PROPERTIES"]["SEARCH_CONTAINER_WIDTH"]["VALUE_XML_ID"]?> constructor-search constructor-search-<?=$arItem["ID"]?> <?=$arItem["PROPERTIES"]["SEARCH_BORDER_BOX_SHADOW"]["VALUE_XML_ID"]?>">

<?
	$APPLICATION->IncludeComponent("concept:phoenix.search.line", ".default", 
		Array(
			"CONTAINER_ID" => "search-page-input-container-construct-".$arItem["ID"],
			"INPUT_ID" => "search-page-input-construct-".$arItem["ID"],
			"COMPONENT_TEMPLATE" => ".default",
			"TYPE" =>"constructor",
			"OPTIONS" => array("PLACEHOLDER" => $arItem["PROPERTIES"]["SEARCH_PLACEHOLDER"]["VALUE"],
			"SEARCH_IN" => $arItem["PROPERTIES"]["SEARCH_IN"]["VALUE_XML_ID"],
			"HINT" => $arItem["PROPERTIES"]["SEARCH_HINT"]["VALUE"]),
			"COMPOSITE_FRAME_MODE" => "N",
			"SHOW_RESULTS" => $arItem["PROPERTIES"]["SEARCH_FASTSEARCH"]["VALUE_XML_ID"],
			"SEARCH_CONTAINER_WIDTH" => $arItem["PROPERTIES"]["SEARCH_CONTAINER_WIDTH"]["VALUE_XML_ID"]
		)
	);

?>
</div>
