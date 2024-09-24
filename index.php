<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Интернет магазин элементов художественной ковки и готовых кованых изделий от производителей оптом и в розницу.");
$APPLICATION->SetPageProperty("keywords", "элементы ковки, кованые изделия, ковка, художественная ковка, воронеж");
$APPLICATION->SetPageProperty("title", "Элементы художественной ковки и кованые изделия купить оптом и в розницу");
$APPLICATION->SetTitle("Художественная ковка и кованые изделия из металла");
?><div class="main_banner">
	<?if(CModule::IncludeModule("iblock"))
	{
		$ar_banners=CIBlockElement::GetList(
			array("SORT"=>"ASC"),
			array(
			"IBLOCK_ID"=>4,
			"ACTIVE"=>"Y",
			"<=DATE_ACTIVE_FROM" => array(false, ConvertTimeStamp(false, "FULL")),
			">=DATE_ACTIVE_TO"   => array(false, ConvertTimeStamp(false, "FULL"))
			),
			false,
			array("nTopCount"=>6),
			array("ID","NAME","PREVIEW_PICTURE","PREVIEW_TEXT","PROPERTY_LINK")
		);
		$count=0;
		while($banner=$ar_banners->GetNext())
		{
			$count++;?>
			<div class="banner <?if($count==1){echo "active";}?>">
				<div class="image">
					<!--noindex-->
					<a rel="nofollow" href="<?=$banner["PROPERTY_LINK_VALUE"]?>">
						<img <?=My::NewResize($banner["PREVIEW_PICTURE"],600,376,true);?> alt="<?=$banner["NAME"]?>" />
					</a><!--/noindex-->
				</div>
				<div class="info">
					<span class="title"><?=$banner["NAME"]?></span>
					<span class="text"><?=$banner["PREVIEW_TEXT"]?></span>
					<div class="button_mun">
						<!--noindex--><a rel="nofollow" href="<?=$banner["PROPERTY_LINK_VALUE"]?>" class="button">Подробнее</a><!--/noindex-->
					</div>
				</div>
				<div class="border"></div>
			</div>
		<?}
	}?>
	<div class="border"></div>
	<div class="man">
		<noindex><nofollow>
		<ul class="menu_type">
			<?for($i=1;$i<=$count;$i++)
			{?>
				<li <?if($i==1){?>class="active"<?}?>><!--noindex--><a rel="nofollow" href="#" class="empty"> </a><!--noindex--></li>
			<?}?>
		</ul>
		</nofollow></noindex>
	</div>
</div>
<div class="selector_block tabs_change">
	<div class="man">
		<div class="item i1 change_tab active"><!--noindex--><a rel="nofollow" href="#"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/man_i1_ico.png" width="100" height="100" alt="" /></span><span class="text">Элементы ковки</span></a><!--/noindex--></div>
		<div class="item i2 change_tab"><!--noindex--><a rel="nofollow" href="#"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/man_i2_ico.png" width="100" height="100" alt="" /></span><span class="text">Готовые изделия</span></a><!--/noindex--></div>
		<div class="item i3 change_tab"><!--noindex--><a rel="nofollow" href="#"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/man_i3_ico.png" width="100" height="100" alt="" /></span><span class="text">Фотогалерея</span></a><!--/noindex--></div>
		<div class="item i4"><!--noindex--><a rel="nofollow" href="/price/"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/man_i4_ico.png" width="100" height="100" alt="" /></span><span class="text">Прайс-лист</span></a><!--/noindex--></div>
	</div>
	<div class="clear"></div>
	<?if(CModule::IncludeModule("iblock"))
	{?>
		<div class="selector_content tabs">
			<div class="tab active">
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
							array("IBLOCK_ID"=>21,"ACTIVE"=>"Y","DEPTH_LEVEL"=>2,"SECTION_ID"=>958),
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
			</div>
			<div class="tab">
				<div class="catalog_section_list">
					<ul class="menu_type">
						<?$ar_sections=CIBlockSection::GetList(
							array("SORT"=>"ASC"),
							array("IBLOCK_ID"=>21,"ACTIVE"=>"Y","DEPTH_LEVEL"=>2,"SECTION_ID"=>943),
							false,
							["*", "UF_NAME_SECTION"]
						);
						while($section=$ar_sections->GetNext())
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
			</div>
			<div class="tab">
			<h1 style="margin:25px;">Примеры готовых изделий из элементов нашего ассортимента</h1>
				<div class="gallery_list gallery_main">
					<?$ar_items=CIBlockSection::GetList(
						array("RAND"=>"ASC"),
						array("IBLOCK_ID"=>3,"ACTIVE"=>"Y"),
						false,
						array("nTopCount"=>5),
						array()
					);
					while($item=$ar_items->GetNext())
					{?>
						<div class="item">
							<!--noindex-->
							<a rel="nofollow" href="<?=$item["SECTION_PAGE_URL"];?>" class="image">
								<span>
									<img <?=My::NewResize($item["PICTURE"],155,155,true);?> alt="<?=$item["NAME"]?>">
								</span>
							</a><!--/noindex-->
							<span class="name"><!--noindex--><a rel="nofollow" href="<?=$item["SECTION_PAGE_URL"];?>"><?=$item["NAME"];?></a><!--/noindex--></span>
							<span class="date"><?=$item["DISPLAY_ACTIVE_FROM"];?></span>
						</div>
					<?}?>
					<div class="clear"></div>
				</div>
				<div class="" style="border-bottom: 5px solid;width: 1280px;margin: 60px -40px;color: #D37B48;"></div>
				<h1 style="margin:25px;">Примеры использования кованых изделий</h1>
				<p style="margin: 25px;">
				В данном разделе размещены фото работ лучших мастеров. Здесь вы можете увидеть, как применяются готовые изделия ручной ковки в интерьере, экстерьере и оформлении ландшафта.
				Обращаем ваше внимание на то, что наша компания не изготавливает и не осуществляет установку готовых кованых изделий.
				Наши консультанты помогут вам подобрать элементы ковки из ассортимента, представленного у нас, для осуществления всех ваших идей.
				</p>
				<div class="gallery_list gallery_main">
					<?$ar_items=CIBlockSection::GetList(
						array("RAND"=>"ASC"),
						array("IBLOCK_ID"=>16,"ACTIVE"=>"Y"),
						false,
						array("nTopCount"=>5),
						array()
					);
					while($item=$ar_items->GetNext())
					{?>
						<div class="item">
							<!--noindex-->
							<a rel="nofollow" href="<?=$item["SECTION_PAGE_URL"];?>" class="image">
								<span>
									<img <?=My::NewResize($item["PICTURE"],155,155,true);?> alt="<?=$item["NAME"]?>">
								</span>
							</a><!--/noindex-->
							<span class="name"><!--noindex--><a rel="nofollow" href="<?=$item["SECTION_PAGE_URL"];?>"><?=$item["NAME"];?></a><!--/noindex--></span>
							<span class="date"><?=$item["DISPLAY_ACTIVE_FROM"];?></span>
						</div>
					<?}?>
					<div class="clear"></div>
				</div>
			</div>
			<div class="tab">
				Ну а тут Прайс-лист
			</div>
		</div>
	<?}?>
	<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/our_benefits.php", Array(), Array(
	    "MODE"      => "html",
	    "NAME"      => "наши преимущества",
	    "TEMPLATE"  => "our_benefits.php"
	    )
	);?>
</div>
<?$obCache = new CPHPCache;
$life_time = 24*60*60;
$cache_id = "main_page";
if($obCache->InitCache($life_time, $cache_id, "/"))
{
	$obCache->Output();
}
else
{
	if($obCache->StartDataCache())
	{?>
		<div class="catalog_spec_slider tabs_change">
			<div class="man">
				<noindex><nofollow>
				<ul>
					<li class="active change_tab"><!--noindex--><a rel="nofollow" href="#">Лидеры</a><!--/noindex--></li>
					<li class="change_tab"><!--noindex--><a rel="nofollow" href="#">Новинки</a><!--/noindex--></li>
					<li class="change_tab"><!--noindex--><a rel="nofollow" href="#"><?=tplvar('name_3',true);?></a><!--/noindex--></li>
				</ul>
				</nofollow></noindex>
			</div>
			<div class="tabs">
				<?if(CModule::IncludeModule("iblock"))
				{
					$filters=array(
						0=>array("IBLOCK_ID"=>21,"ACTIVE"=>"Y","PROPERTY_LIDER_VALUE"=>"да"),
						1=>array("IBLOCK_ID"=>21,"ACTIVE"=>"Y","PROPERTY_NEW_VALUE"=>"да"),
						2=>array("IBLOCK_ID"=>21,"ACTIVE"=>"Y","PROPERTY_SPEC_VALUE"=>"да")
					);
					$label="";
					$url="";
					foreach($filters as $key=>$filter)
					{
						switch ($key)
						{
							case 0:
								$label="лидеры";
								$url="/catalog/filter/?SHOW=LIDER";
								break;
							case 1:
								$label="новинки";
								$url="/catalog/filter/?SHOW=NEW";
								break;
							case 2:
								$label="предложения";
								$url="/catalog/filter/?SHOW=SPEC";
								break;
							default:
								$label="предложения";
								$url="/catalog/filter/?SHOW=ALL";
								break;
						}?>
						<div class="tab <?if($key==0){echo "active init";}?>" data-id="car_<?=$key?>">
							<div class="carusel">
								<!--noindex-->
								<div class="arrows" id="arr_car_<?=$key?>"><a rel="nofollow" href="#" class="left"></a><a rel="nofollow" href="#" class="right"></a></div><!--/noindex-->
								<div class="inn">
									<ul class="menu_type" style="width:10000px;">
										<?$ar_items=CIBlockElement::GetList(
											array("RAND"=>"ASC"),
											$filter,
											false,
											false,
											array("ID","NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_PAGE_URL","PROPERTY_CML2_ARTICLE","CATALOG_GROUP_1", "PROPERTY_CML2_BASE_UNIT", "PROPERTY_COUNT_RATE","PROPERTY_NAIMENOVANIE_SAYT01","PROPERTY_NAIMENOVANIE_SAYT02","PROPERTY_NAIMENOVANIE_SAYT03")
										);
										while($item=$ar_items->GetNext())
										{
											if ($item["PROPERTY_COUNT_RATE_VALUE"]){
												$count = $item["PROPERTY_COUNT_RATE_VALUE"];
											}else{
												$count = 1;
											}

											$price=My::GetMinPrice($item["ID"],1);?>
											<li class="item slide" data-id="<?=$item["ID"]?>">
												<!--noindex-->
												<a rel="nofollow" href="<?=$item["DETAIL_PAGE_URL"]?>" class="image">
													<span>
														<img  src="<?=CFile::GetPath($item["PREVIEW_PICTURE"])?>" style="max-height:190px"<?//=My::NewResize($item["PREVIEW_PICTURE"],195,190,false);?> alt="<?=$item["NAME"]?>">
													</span>
												</a>
												<!--/noindex-->
												<? if($item["PROPERTY_CML2_ARTICLE_VALUE"] == '10.10'){ ?>
												<span class="art" style="background:transparent"></span>
												<? }else{ ?>
												<span class="art">Арт. <?=$item["PROPERTY_CML2_ARTICLE_VALUE"]?></span>
												<? } ?>
												<span class="name"><!--noindex-->
												<a rel="nofollow" href="<?=$item["DETAIL_PAGE_URL"]?>">
												<? if(strlen($item["PROPERTY_NAIMENOVANIE_SAYT01_VALUE"]) > 1){ ?>
													<span><?=$item["PROPERTY_NAIMENOVANIE_SAYT01_VALUE"]?></span>
													<?=$item["PROPERTY_NAIMENOVANIE_SAYT02_VALUE"]?><br>
													<?=$item["PROPERTY_NAIMENOVANIE_SAYT03_VALUE"]?>
												<?}else{?>
													<?=$item["NAME"]?>
												<?}?>
												</a>
												<!--/noindex--></span>
												<span class="prev"><?=$item["PREIVEW_TEXT"]?></span>
												<?if($item["CATALOG_QUANTITY"]>0 && $price["PRICE"]>0)
												{?>
													<?php if ($price["FULL"] > $price["PRICE"]){?>
													<div class="price"><div class="old"><b><?=My::Money($price["FULL"]);?></b> руб.</div><?=My::Money($price["PRICE"]);?> <b>руб. / <?=$item['PROPERTY_CML2_BASE_UNIT_VALUE']?>.</b></div>
													<?}else{?>
													<div class="price"><?=My::Money($price["PRICE"]);?> <b>руб. / <?=$item['PROPERTY_CML2_BASE_UNIT_VALUE']?>.</b></div>
													<?}?>
													<!--noindex--><a rel="nofollow" href="#" class="buy add2basket <?if($item["CATALOG_QUANTITY"]==0){echo "null";}?>" data-rate="<?=$count?>" data-id="<?=$item["ID"]?>" data-count="<?=$item["CATALOG_QUANTITY"]?>">В корзину</a><!--/noindex-->
												<?}
												else
												{?>
													<div class="no_count">
														<span>Под заказ</span>
														<!--noindex--><a href="#" rel="nofollow" class="buy open_popup change_item_id" data-id="no_item" data-itemid="<?=$item["ID"]?>">Заказать</a><!--/noindex-->
													</div>
												<?}?>
											</li>
										<?}?>
									</ul>
								</div>
								<!--noindex--><a rel="nofollow" href="<?=$url?>" class="button">Все <?=$label?></a><!--/noindex-->
							</div>
						</div>
					<?}
				}?>
			</div>
		</div>
	<?$obCache->EndDataCache();
	}
}?>



<div class="mp_text_block">
	<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/main_text.php", Array(), Array(
	    "MODE"      => "html",
	    "NAME"      => "текст на главной",
	    "TEMPLATE"  => "main_text.php"
	    )
	);?>

	<!-- <div class="border_block">

	<?/*$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/border_text.php", Array(), Array(
		    "MODE"      => "html",
		    "NAME"      => "текст в рамке",
		    "TEMPLATE"  => "border_text.php"
		    )
		);*/?>
	</div> -->

	<div class="clear"></div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
