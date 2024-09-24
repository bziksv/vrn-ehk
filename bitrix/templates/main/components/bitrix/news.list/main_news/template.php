<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="news_list on_main">
	<div class="title">Новости</div>
	<?foreach($arResult["ITEMS"] as $arItem)
	{			
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
		<div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<span class="date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></span>
			<span class="name"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></span>
			<span class="preview"><?=$arItem["PREVIEW_TEXT"];?></span>
		</div>
	<?}?>
	<span class="more"><a href="/news/">Архив новостей</a></span>
</div>