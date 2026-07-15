<?
$arFilter = [
	"IBLOCK_ID" => 21, // ID Инфо блока
	"SECTION_ID" => 943, // ID Категории
	"ACTIVE" => "Y", 
];
?>

<div class="catalog_section_list">
	<ul class="menu_type">
		<?$ar_sections=CIBlockSection::GetList(
			array("SORT"=>"ASC"),
			$arFilter,
			false,
			["*", "UF_NAME_SECTION"]
		);
		while($section = $ar_sections->GetNext())
		{?>
			<li class="category_home_page">
				<!--noindex-->
				<a rel="nofollow" class="image" href="<?=$section["SECTION_PAGE_URL"]?>">
					<span>
						<img src="<?=CFile::GetPath($section["PICTURE"])?>" alt="<?=$section["NAME"]?>" />
					</span>
				</a>
				<!--/noindex-->
				<span class="name"><!--noindex--><a rel="nofollow" href="<?=$section["SECTION_PAGE_URL"]?>">
				<? if($section['UF_NAME_SECTION']):?>
					<?=implode("<br/>", $section['UF_NAME_SECTION']);?>
				<? else: ?>
					<?=$section["NAME"]?>
				<? endif; ?>
				</a><!--/noindex--></span>
			</li>
		<?}?>
	</ul>
	<div class="clear"></div>
</div>