<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!$arResult["SECTION"])
{?>
	<div class="selector_block">
		<div class="selector_content">
			<div class="catalog_section_list">
				<ul class="menu_type">
					<?$ar_sections=CIBlockSection::GetList(
						array("SORT"=>"ASC"),
						array("IBLOCK_ID"=>$arResult["ID"],"ACTIVE"=>"Y","DEPTH_LEVEL"=>1),
						false
					);
					while($section=$ar_sections->GetNext())
					{?>
						<li>
							<a class="image" href="<?=$section["SECTION_PAGE_URL"]?>">
								<span>
									<img <?=My::NewResize($section["PICTURE"],180,120,false);?> alt="<?=$section["NAME"]?>" />
								</span>
							</a>
							<span class="name"><a href="<?=$section["SECTION_PAGE_URL"]?>"><?=$section["NAME"]?></a></span>
						</li>
					<?}?>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
	</div>
<?}
else
{?>
	<?foreach($arResult["SECTION"]["PATH"] as $section)
	{
		$APPLICATION->AddChainItem($section["NAME"], $section["SECTION_PAGE_URL"]);
	}?>
	<div class="gallery_list">
		<div class="list">
		<?foreach($arResult["ITEMS"] as $arItem)
		{
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<a rel="lightbox[gallery]" href="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" class="image">
					<span>
						<img <?=My::NewResize($arItem["PREVIEW_PICTURE"]["ID"],165,165,false);?> alt="<?=$arItem["NAME"]?>">
					</span>
				</a>
			</div>
		<?}?>
		<div class="clear"></div>
		</div>
	</div>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<br /><?=$arResult["NAV_STRING"]?>
	<?endif;?>
<?}?>
