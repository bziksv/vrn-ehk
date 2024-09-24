<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}
?>
<div class="modern-page-navigation">
<?

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>
	<span class="modern-page-title"><?=GetMessage("pages")?></span>

	<? for ($pages = 1; $pages <= $arResult["NavPageCount"]; $pages++): ?>
	
		<? if($pages == $arResult["NavPageNomer"]):?>
			<span class="modern-page-current"><?=$pages?></span>
		<? else: ?>
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$pages?>"><?=$pages?></a>
		<? endif; ?>
		
	<? endfor; ?>
	
<?
if ($arResult["bShowAll"]):
	if ($arResult["NavShowAll"]):
?>
		<a class="modern-page-pagen" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0"><?=GetMessage("nav_paged")?></a>
<?
	else:
?>
		<noindex><a class="modern-page-all" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1"><?=GetMessage("nav_all")?></a></noindex>
<?
	endif;
endif
?>
</div>