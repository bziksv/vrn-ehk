<?
$arFilter = [
	"IBLOCK_ID" => 21, // ID Инфо блока
	"SECTION_ID" => 958, // ID Категории
	"ACTIVE" => "Y", 
];

?>

<div class="catalog_section_list">
					<ul class="menu_type">

							<li class="category_home_page">
								<a rel="nofollow" class="image" href="/catalog/filter/?SHOW=SPEC_RAZDEL">
									<span>

							<?
								$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/img_front_1.php", Array(), Array(
							    "MODE"      => "html",
							    "NAME"      => "Картинка",
							    "TEMPLATE"  => "img_front_1.php"
							    )
							);

							?>

									</span>
								</a>

								<span class="name"><a rel="nofollow" href="/catalog/filter/?SHOW=SPEC_RAZDEL"><?=tplvar('name_1',true);?></a></span>
							</li>
<!--	комментировать строки ниже -->
							<li class="category_home_page">

								<a rel="nofollow" class="image" href="/catalog/filter/?SHOW=NEW_RAZDEL">
									<span>
							<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/img_front_2.php", Array(), Array(
							    "MODE"      => "html",
							    "NAME"      => "Картинка",
							    "TEMPLATE"  => "img_front_2.php"
							    )
							);?>
									</span>
								</a>


<span class="name"><a rel="nofollow" href="/catalog/filter/?SHOW=NEW_RAZDEL"><?= tplvar('name_2',true);?></a></span>
							</li>
<!-- до сюда -->
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
								<span class="name"><!--noindex-->
								<a rel="nofollow" href="<?=$section["SECTION_PAGE_URL"];?>">
								<? if($section['UF_NAME_SECTION']):?>
									<?=implode("<br/>", $section['UF_NAME_SECTION']);?>
								<? else: ?>
									<?=$section["NAME"]?>
								<? endif; ?>
								</a>
								<!--/noindex--></span>
							</li>
						<?}?>
					</ul>
					<div class="clear"></div>
				</div>