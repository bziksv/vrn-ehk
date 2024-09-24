<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');


$elementID =  htmlspecialcharsbx(trim($_POST["elementID"]));

if($elementID && CModule::IncludeModule('iblock'))
{

	$typeName = htmlspecialcharsbx(trim($_POST["typeName"]));
	$iblockID = trim($_POST["iblockID"]);
	$iblockTypeID = trim($_POST["iblockTypeID"]);


	if(SITE_CHARSET == "windows-1251")
	    $blockTitle = utf8win1251(trim($_POST["blockTitle"]));                
	else
		$blockTitle = trim($_POST["blockTitle"]);

	$arTemplate = Array();

	$arTemplate["tariff"] = Array();
	$arTemplate["tariff"]["TEMPLATE"] = "phoenix-tariff-detail";

	$template = "phoenix-".htmlspecialcharsbx($typeName."-detail");


	?>

	<div class="shadow-modal"></div>

	<div class="phoenix-modal <?=$template?> popup-block-<?=$elementID?>">

		<div class="phoenix-modal-dialog">
			
			<div class="dialog-content">
	            <a class="close-modal"></a>

	            

	            <div class="content-in">
	  			
					<?$APPLICATION->IncludeComponent(
						"bitrix:news.detail",
						$template,
						Array(
							"ELEMENT_ID" => $elementID,
							"COMPONENT_TEMPLATE" => $template,
							"IBLOCK_ID" => $iblockID,
							"IBLOCK_TYPE" => $iblockTypeID,
							"BLOCK_TITLE" => $blockTitle,
							"ACTIVE_DATE_FORMAT" => "d.m.Y",
							"ADD_ELEMENT_CHAIN" => "N",
							"ADD_SECTIONS_CHAIN" => "N",
							"AJAX_MODE" => "N",
							"AJAX_OPTION_ADDITIONAL" => "",
							"AJAX_OPTION_HISTORY" => "N",
							"AJAX_OPTION_JUMP" => "N",
							"AJAX_OPTION_STYLE" => "Y",
							"BROWSER_TITLE" => "-",
							"CACHE_GROUPS" => "Y",
							"CACHE_TIME" => "36000000",
							"CACHE_TYPE" => "A",
							"CHECK_DATES" => "Y",
							"COMPOSITE_FRAME_MODE" => "N",
							"COMPOSITE_FRAME_TYPE" => "AUTO",
							"DETAIL_URL" => "",
							"DISPLAY_BOTTOM_PAGER" => "N",
							"DISPLAY_DATE" => "N",
							"DISPLAY_NAME" => "Y",
							"DISPLAY_PICTURE" => "Y",
							"DISPLAY_PREVIEW_TEXT" => "Y",
							"DISPLAY_TOP_PAGER" => "N",
							"ELEMENT_CODE" => "",
							"FIELD_CODE" => array(0=>"",1=>"",),
							"IBLOCK_URL" => "",
							"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
							"MESSAGE_404" => "",
							"META_DESCRIPTION" => "-",
							"META_KEYWORDS" => "-",
							"PAGER_BASE_LINK_ENABLE" => "N",
							"PAGER_SHOW_ALL" => "N",
							"PAGER_TEMPLATE" => ".default",
							"PAGER_TITLE" => "Страница",
							"PROPERTY_CODE" => array(),
							"SET_BROWSER_TITLE" => "N",
							"SET_CANONICAL_URL" => "N",
							"SET_LAST_MODIFIED" => "N",
							"SET_META_DESCRIPTION" => "N",
							"SET_META_KEYWORDS" => "N",
							"SET_STATUS_404" => "N",
							"SET_TITLE" => "N",
							"SHOW_404" => "N",
							"STRICT_SECTION_CHECK" => "N",
							"USE_PERMISSIONS" => "N",
							"USE_SHARE" => "N",
						)
					);?>

				</div>
			</div>
	    </div>
	</div>

<?}?>