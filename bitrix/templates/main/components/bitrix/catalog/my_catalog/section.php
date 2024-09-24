<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>
<?$GLOBALS["HIDE_DESK"]=true;?>

<?
$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"",
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_FIELDS" => ["UF_NAME_SECTION"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
		"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
		"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
		"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
		"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : '')
	),
	$component
);?>

<?if ($arParams['USE_FILTER'] == 'Y')
{
	$arFilter = array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ACTIVE" => "Y",
		"GLOBAL_ACTIVE" => "Y",
	);
	if (0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
	{
		$arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
	}
	elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"])
	{
		$arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
	}

	$obCache = new CPHPCache();
	if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
	{
		$arCurSection = $obCache->GetVars();
	}
	elseif ($obCache->StartDataCache())
	{
		$arCurSection = array();
		if (\Bitrix\Main\Loader::includeModule("iblock"))
		{
			$dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));

			if(defined("BX_COMP_MANAGED_CACHE"))
			{
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache("/iblock/catalog");

				if ($arCurSection = $dbRes->Fetch())
				{
					$CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);
				}
				$CACHE_MANAGER->EndTagCache();
			}
			else
			{
				if(!$arCurSection = $dbRes->Fetch())
					$arCurSection = array();
			}
		}
		$obCache->EndDataCache($arCurSection);
	}
	if (!isset($arCurSection))
	{
		$arCurSection = array();
	}?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.smart.filter",
		".default",
		Array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"SECTION_ID" => $arCurSection['ID'],
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SAVE_IN_SESSION" => "N",
			"XML_EXPORT" => "Y",
			"SECTION_TITLE" => "NAME",
			"SECTION_DESCRIPTION" => "DESCRIPTION",
			'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
			"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"]
		),
		$component,
		array('HIDE_ICONS' => 'Y')
	);
}?>
<?$items_count=CIBlockElement::GetList(
	array(),
	array("IBLOCK_ID"=>$arParams["IBLOCK_ID"],"ACTIVE"=>"Y","SECTION_ID"=>$arCurSection['ID']),
	array(),
	false,
	array()
);
if($items_count>0 || isset($_GET["SHOW"]) && !empty($_GET["SHOW"]))
{
	
	?>
	<div class="catalog_section_list">
		<div class="sort_block">
			<div class="item" <? if(!$_GET['SHOWALL_1']): ?>style="float:right;"<?endif;?>>
				<span class="label">Сортировать по:</span>
				<span class="value">
					<form action="<?=$APPLICATION->GetCurDir()?>" method="GET" name="sort_form">
						<fieldset>
						<? if($_GET["SHOW"] == 'NEW'){?>
						<input type="hidden" name="SHOW" value="NEW">
						<?}?>
						<? if($_GET["SHOW"] == 'SPEC_RAZDEL'){?>
						<input type="hidden" name="SHOW" value="SPEC_RAZDEL">
						<?}?>
						<? if($_GET["SHOW"] == 'NEW_RAZDEL'){?>
						<input type="hidden" name="SHOW" value="NEW_RAZDEL">
						<?}?>
							<select name="sort" onchange="sort_form.submit();">
								<option value="">Выберите один из вариантов</option>
								<option value="CATALOG_PRICE_1-ASC" <?if($_GET["sort"]=="CATALOG_PRICE_1-ASC"){echo "selected";}?>>По цене, сначала дешевые</option>
								<option value="CATALOG_PRICE_1-DESC" <?if($_GET["sort"]=="CATALOG_PRICE_1-DESC"){echo "selected";}?>>По цене, сначала дорогие</option>
							</select>
						</fieldset>
					</form>
				</span>
				<span class="clear"></span>
			</div>
			
			<? if(!$_GET['SHOWALL_1']): 
			$show_all = array_merge($_GET, ['SHOWALL_1' => 1]);
			?>
			<div class="item">
				<noindex><a class="show-all-top" href="?<?=http_build_query($show_all)?>">Показать все<span> на одной странице</span></a></noindex>
			</div>
			<? endif; ?>
			<?/*?>
			<div class="item view_type">
				<span class="label">Вид каталога:</span>
				<span class="value">
					<noindex>
					<nofollow>
					<ul class="man">
						<li class="mozaic <?if(!isset($_SESSION["LIST_VIEW"]) || empty($_SESSION["LIST_VIEW"]) || $_SESSION["LIST_VIEW"]!="Y"){echo "act";}?>"><a class="empty" href="<?=$APPLICATION->GetCurPageParam("LIST=N", array("LIST"));?>">&nbsp;</a></li>
						<li class="list <?if(isset($_SESSION["LIST_VIEW"]) && !empty($_SESSION["LIST_VIEW"]) && $_SESSION["LIST_VIEW"]=="Y"){echo "act";}?>"><a class="empty" href="<?=$APPLICATION->GetCurPageParam("LIST=Y", array("LIST"));?>">&nbsp;</a></li>
					</ul>
					</nofollow>
					</noindex>
				</span>
				<span class="clear"></span>
			</div>
			<?*/?>
			<div class="clear"></div>
		</div>
		<?$intSectionID = 0;?>
		<?$template="";
		if(isset($_SESSION["LIST_VIEW"]) && !empty($_SESSION["LIST_VIEW"]) && $_SESSION["LIST_VIEW"]=="Y")
		{
			$template="list";
		}?>
		<?
		$intSectionID = $APPLICATION->IncludeComponent(
			"bitrix:catalog.section",
			$template,
			array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
				"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
				"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
				"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
				"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
				"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
				"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
				"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
				"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
				"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
				"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
				"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
				"FILTER_NAME" => $arParams["FILTER_NAME"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_FILTER" => $arParams["CACHE_FILTER"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SET_TITLE" => $arParams["SET_TITLE"],
				"SET_STATUS_404" => $arParams["SET_STATUS_404"],
				"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
				"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
				"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
				"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
				"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
				"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
				"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

				"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
				"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
				"PAGER_TITLE" => $arParams["PAGER_TITLE"],
				"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
				"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
				"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
				"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
				"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

				"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
				"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
				"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
				"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
				"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
				"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
				"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
				"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
				"BY_LINK"=>$GLOBALS["BY_LINK"],
				"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
				"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
				"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
				'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
				'CURRENCY_ID' => $arParams['CURRENCY_ID'],
				'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

				'LABEL_PROP' => $arParams['LABEL_PROP'],
				'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
				'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

				'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
				'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
				'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
				'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
				'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
				'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
				'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
				'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
				'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
				'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],

				'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
				"ADD_SECTIONS_CHAIN" => "N"
			),
			$component
		);?>
	</div>
<?}
$ar_cur_section=CIBlockSection::GetList(
	array("SORT"=>"ASC"),
	array("IBLOCK_ID"=>$arParams["IBLOCK_ID"],"=CODE"=>$arResult["VARIABLES"]["SECTION_CODE"]),
	false
);
if($cur_section=$ar_cur_section->GetNext())
{
	/*$ar_nav = CIBlockSection::GetNavChain($arParams["IBLOCK_ID"], $cur_section["ID"]);
	while($nav=$ar_nav->Fetch())
	{
		$APPLICATION->AddChainItem($nav["NAME"], $nav["SECTION_PAGE_URL"]);
	}*/?>
	<div class="under_text">
		<h1><?//$cur_section["NAME"]?></h1>
		<?if($cur_section["DESCRIPTION"] and empty($_GET['PAGEN_1']))
		{
			echo $cur_section["DESCRIPTION"];
		}?>
	</div>
<?}?>
