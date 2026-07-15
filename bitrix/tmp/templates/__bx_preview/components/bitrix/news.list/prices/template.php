<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="price_page">
	<div class="inn">
		<div class="price_list">
			<?foreach($arResult["ITEMS"] as $arItem)
			{?>
				<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
				<div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<span class="name"><?=$arItem["NAME"];?></span>
					<?$ar_file=CFile::GetByID($arItem["PROPERTIES"]["FILE"]["VALUE"]);
					if($file=$ar_file->Fetch())
					{?>
						<span class="format"><?=strtoupper(substr(strrchr($file["FILE_NAME"], "."),1));?> (<?=My::GetSize($file["FILE_SIZE"]);?>)</span>
						<span class="link"><a href="<?=CFile::GetPath($file["ID"]);?>" target="_blank">Скачать</a></span>
					<?}?>
				</div>
			<?}?>
		</div>
	</div>
</div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
