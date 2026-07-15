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


$this->setFrameMode(true);
$strMainID = $this->GetEditAreaId($arResult['ID']);

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);
$price=My::GetMinPrice($arResult["ID"],1);
$special_text = CIBlockElement::GetList(
	Array("SORT"=>"ASC"),
	Array("IBLOCK_ID"=>"9"),
	false,
	false,
	Array("ID", "PREVIEW_TEXT")
);
$special_text = $special_text->GetNext();
$special_text = $special_text["PREVIEW_TEXT"];

?>
<div class="catalog_detail" id="<?=$arItemIDs['ID'];?>">
	<div class="image_col">
		<div class="big_images">
            <? if($arResult["PROPERTIES"]["NEW_STICKER"]["VALUE"] == "Y"):?>
            <div class="stickers">
                <div class="news">Новинка</div>
            </div>
            <? endif; ?>
			<?if($arResult["PROPERTIES"]["LAST_PRODUCT"]["VALUE"] == "Y"):?>
				<div class="last-prod">
					<img src="<?=SITE_TEMPLATE_PATH ?>/images/last_prod.png">
				</div>
			<?endif;?>
			<?$pic_count=0;
			$detail_pic=false;
			if(is_array($arResult["DETAIL_PICTURE"]))
			{
				$pic_count++;
				$detail_pic=true;
				$big_picture=My::Resize($arResult["DETAIL_PICTURE"]["ID"],800,800,false);?>
				<div class="big_image active">
					<a href="<?=$big_picture["SRC"]?>" rel="lightbox[gallery]" class="cur_image">
						<span>
							<img <?=My::NewResize($arResult["DETAIL_PICTURE"]["ID"],420,420,false);?> alt="<?=$arResult["NAME"]?>" />
						</span>
					</a>
				</div>
			<?}
			foreach($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $key=>$image_id)
			{
				$pic_count++;
				$big_picture=My::Resize($image_id,800,800,false);?>
				<div class="big_image <?if($key==0 && !$detail_pic){echo "active";}?>">
					<a href="<?=$big_picture["SRC"]?>" rel="lightbox[gallery]" class="cur_image">
						<span>
							<img <?=My::NewResize($image_id,420,420,false);?> alt="<?=$arResult["NAME"]?>" />
						</span>
					</a>
				</div>
			<?}
			if($pic_count==0)
			{?>
				<div class="cur_image">
					<span>
						<img <?=My::NewResize($arResult["DETAIL_PICTURE"]["ID"],420,420,false);?> alt="<?=$arResult["NAME"]?>" />
					</span>
				</div>
			<?}?>
		</div>
		<div class="preview_images">
			<?if(($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"] && sizeof($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"]) > 4 && !$detail_pic) || ($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"] && $detail_pic && sizeof($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"])>3))
			{?>
				<div class="man"><a class="empty left" href="#">&nbsp;</a><a class="empty right" href="#">&nbsp;</a></div>
			<?}?>
			<div class="inn">
				<ul style="width:1000px" class="menu_type">
					<?if(is_array($arResult["DETAIL_PICTURE"]))
					{?>
						<li class="act">
							<a href="#">
								<span>
									<img <?=My::NewResize($arResult["DETAIL_PICTURE"]["ID"],75,75,false);?> alt="<?=$arResult["NAME"]?>">
								</span>
							</a>
						</li>
					<?}
					foreach($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $key=>$image_id)
					{?>
						<li class="<?if($key==0 && !$detail_pic){echo "act";}?>">
							<a href="#">
								<span>
									<img <?=My::NewResize($image_id,75,75,false);?> alt="<?=$arResult["NAME"]?> <?=$key+1?>">
								</span>
							</a>
						</li>
					<?}?>
				</ul>
			</div>
		</div>
		<div class="social">
			<script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" data-yashareTheme="counter"></div>
		</div>
	</div>
	<div class="info_col">
		<div class="buy_block <?if ($arResult["PROPERTIES"]["SPEC_PRICE"]["VALUE"] == "Y") { echo 'special';}?>">

				<?if($arResult["PROPERTIES"]["CML2_ARTICLE"]["VALUE"] && $arResult["PROPERTIES"]["CML2_ARTICLE"]["VALUE"] != "10.10")
				{?>
				<span class="art">
					Арт. <?=$arResult["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?>
				</span>
				<?}?>






				<?if($arResult["CATALOG_QUANTITY"] > 0 && $price["PRICE"]>0)
				{
					if ($price["FULL"]>$price["PRICE"]) {?>
					<div class="price with_old">
						<b><?=My::Money($price["FULL"])?></b>

						<span class="module">
							руб.
							<span>
								/
							</span>
						<?=$arResult["PROPERTIES"]["CML2_BASE_UNIT"]["VALUE"]?>.
						</span>

						<div class="with_sale">
						<?=My::Money($price["PRICE"]);?>
						<b>руб.</b><b><span class="module"><span> / </span><?=$arResult["PROPERTIES"]["CML2_BASE_UNIT"]["VALUE"]?>.</span></b>
						</div>
					</div>
					<?}else{
					?>
					<div class="price"><b><?=My::Money($price["PRICE"]);?></b> руб.<span class="module"><span> / </span><?=$arResult["PROPERTIES"]["CML2_BASE_UNIT"]["VALUE"]?>.</span></div>
					<?}?>


					<?
						$arPrice = CCatalogProduct::GetOptimalPrice($arResult["ID"], 1, $USER->GetUserGroupArray(), "N");
						$discount = CCatalogDiscount::GetList(Array("SORT"=>"ASC"), Array("ID" => $arPrice["DISCOUNT"]["ID"]), false, false, Array("NAME", "NOTES"));
						$discount = $discount->GetNext();
						if ($discount) { ?>

							<?if ( $price["FULL"] > $price["PRICE"] ) { ?>
								<div class="special_title sale_b">
									<a href="#">
										<span class="text">При заказе через сайт скидка <?=round((($price["FULL"] - $price["PRICE"]) / $price["FULL"]) * 100);?>%</span>
										<span class="inn">
											<?=$discount["~NOTES"]?>
										</span>
									</a>
								</div>
							<?}else{?>
								<div class="special_title sale_b">
									<a href="#">
										<span class="text"><?=$discount["NAME"]?></span>
										<span class="inn">
											<?=$discount["~NOTES"]?>
										</span>
									</a>
								</div>
							<?}?>
						<?
					   }else {?>
						<? if(empty($arResult["PROPERTIES"]["TABU"]["VALUE"]) && empty($arResult["PROPERTIES"]["rasprodazha_postojannaja_ckidka"]["VALUE"]) && empty($arResult["PROPERTIES"]["LAST_PRODUCT"]["VALUE"])): ?>
							<div class="special_title sale_b">
								<a href="#">
									<span class="text">Скидка на товар от суммы покупки</span>
									<span class="inn">
										<ul style="text-align: left;">
											<li>от &nbsp; 5 000 руб. — &nbsp; 3%;</li>
											<li>от 10 000 руб. — &nbsp; 5%;</li>
											<li>от 30 000 руб. — &nbsp; 7%;</li>
											<li>от 50 000 руб. — 10%;</li>
										</ul>
										<p style="font-size: 12px;">от 100 000 руб. скидка рассматривается индивидуально</p>
									</span>
								</a>
							</div>
						<? endif; ?>
						<?}
					/*
						$arDiscounts = CCatalogDiscount::GetDiscountByProduct(
					        $arResult["ID"],
					        $USER->GetUserGroupArray(),
					        "N",
					        false,
					        false
					    );
					    var_dump($arDiscounts);
					    foreach($arDiscounts as $preDiscount)
					    {
					    	$discount[] = CCatalogDiscount::GetList(Array("SORT"=>"ASC"), Array("ID" => $preDiscount["ID"]), false, false, Array("NAME", "NOTES"));
					    }
					    foreach ($discount as $key => $cur_discount) {
					    	$cur_discount = $cur_discount->GetNext();
					    	?>
					    	<div class="special_title sale_b">
								<a href="#">
									<span class="text"><?=$cur_discount["NAME"]?></span>
									<span class="inn">
										<?=$cur_discount["NOTES"]?>
									</span>
								</a>
							</div>
					    	<?
					    }
				    */
					?>

					<?if ($arResult["PROPERTIES"]["SPEC_PRICE"]["VALUE"] == "Y") {?>

							<div class="special_title">
								<a href="#">
									<span class="text">Специальная<br />цена</span>
									<span class="inn">
										<?=$special_text?>
									</span>
								</a>
							</div>

					<?}?>

						<?if($arResult["CATALOG_QUANTITY"] && $price["PRICE"]>0)
			{
				$width=100;
				$label="Много (порядка ".$arResult["CATALOG_QUANTITY"]." ".$arResult["PROPERTIES"]["CML2_BASE_UNIT"]["~VALUE"].".)";
				if($arResult["CATALOG_QUANTITY"]<=9)
				{
					$width=$arResult["CATALOG_QUANTITY"]*10;
				}
				if($arResult["CATALOG_QUANTITY"]==0)
				{
					$label="Нет в наличии";
				}
				elseif($arResult["CATALOG_QUANTITY"]>0 && $arResult["CATALOG_QUANTITY"]<=2)
				{
					$label="Мало (порядка ".$arResult["CATALOG_QUANTITY"]." ".$arResult["PROPERTIES"]["CML2_BASE_UNIT"]["~VALUE"].".)";
				}
				elseif($arResult["CATALOG_QUANTITY"]>2 && $arResult["CATALOG_QUANTITY"]<=5)
				{
					$label="Заканчиваются (порядка ".$arResult["CATALOG_QUANTITY"]." ".$arResult["PROPERTIES"]["CML2_BASE_UNIT"]["~VALUE"].".)";
				}
				elseif($arResult["CATALOG_QUANTITY"]>5 && $arResult["CATALOG_QUANTITY"]<=9)
				{
					$label="В достаточном количестве (порядка ".$arResult["CATALOG_QUANTITY"]." ".$arResult["PROPERTIES"]["CML2_BASE_UNIT"]["~VALUE"].".)";
				}?>
				<?
				if ($arResult["PROPERTIES"]["COUNT_RATE"]["VALUE"]){
					$count = $arResult["PROPERTIES"]["COUNT_RATE"]["VALUE"];
				}else{
					$count = 1;
				}
				?>
				<div class="count_block">
					<span class="label">Кол-во:</span>
					<span class="value"><input type="text" data-rate="<?=$count?>" value="<?=$count?>"/><a href="#" class="plus">+</a><a href="#" class="minus">-</a></span>

					<div class="clear"></div>
				</div>

				<div class="quantity">
					<? if ($arResult["PRODUCT"]["WEIGHT"]): ?>
						<span class="label">Вес: <?=number_format($arResult["PRODUCT"]["WEIGHT"], 0, '.', ' ')?> гр.</span><br/>
					<? endif; ?>
					<span class="label">В наличии: <?=$arResult["CATALOG_QUANTITY"]?> <?=$arResult['CATALOG_MEASURE_NAME']?></span>
				</div>

			<?}?>

					<!--noindex-->
					<a rel="nofollow" href="#" data-id="<?=$arResult["ID"]?>" class="buy_but add2basket <?if(!$arResult["CATALOG_QUANTITY"]){echo "null";}?>">В корзину</a>
					<!--/noindex-->
				<?}
				else
				{?>
					<!--noindex-->
					<div class="no_count_label">
						Под заказ
					</div>
					<a href="#" class="buy_but open_popup change_item_id" data-id="no_item" data-itemid="<?=$arResult["ID"]?>">Заказать</a>
					<!--/noindex-->
				<?}?>

			<?
				foreach($arResult['DESCRIPTION_8'] as $k => $v){
					if($v == 'Вес'){
						if($arResult['PROPERTY_8'][$k] !=0){
						print '<div style="margin: 15px 0;font-size: 17px;" class="price with_old"> Вес элемента: '.$arResult['PROPERTY_8'][$k].' кг.</div>';
						}
					}
				}
				?>
				<div style="margin: 15px 0;font-size: 12px;text-align:center" class="price with_old">*Технические характеристики элемента могут незначительно отличаться от заявленных.</div>
			<div class="params">
				<?foreach($arResult["DISPLAY_PROPERTIES"] as $prop_ar)
				{
					if($prop_ar["VALUE"])
					{?>
						<div class="line">
							<span class="l_label"><?=$prop_ar["NAME"]?>:</span>
							<span class="l_value"><?=$prop_ar["VALUE"]?></span>
							<span class="clear"></span>
						</div>
					<?}
				}?>
			</div>
		</div>
		<div class="sections_block">
			<?$parent_sections=array();
			$ar_nav=CIBlockSection::GetNavChain($arResult["IBLOCK_ID"], $arResult["IBLOCK_SECTION_ID"]);
			while($nav=$ar_nav->GetNext())
			{
				$parent_sections[]=$nav["ID"];
			}
			$ar_first_section=CIBlockSection::GetByID($parent_sections[0]);
			if($first_section=$ar_first_section->GetNext())
			{?>
				<span class="title"><?=$first_section["NAME"]?></span>
				<ul>
					<?$arFilter = Array(
				        "IBLOCK_ID"=>$arResult["IBLOCK_ID"],
				        "ACTIVE"=>"Y",
				        "DEPTH_LEVEL"=>2,
				        "SECTION_ID"=>$first_section["ID"]
			        );
				   	$group_count=CIBlockSection::GetCount($arFilter);
				   	$sep=ceil($group_count/2);
				   	$ar_sections=CIBlockSection::GetList(
				   		array("SORT"=>"ASC","NAME"=>"ASC"),
				   		$arFilter,
				   		false
				   	);
				   	$count=0;
				   	while($section=$ar_sections->GetNext())
				   	{
				   		$count++;?>
						<li <?if(in_array($section["ID"], $parent_sections)){?>class="act"<?}?>><a href="<?=$section["SECTION_PAGE_URL"]?>"><?=$section["NAME"]?></a></li>
				   		<?if($count%$sep==0)
				   		{?>
							</ul>
							<ul>
				   		<?}
				   	}?>
				</ul>
				<div class="clear"></div>
			<?}?>
		</div>
		<div class="clear"></div>
		<?if($arResult["PROPERTIES"]["USER"]["VALUE"] || $arResult["DETAIL_TEXT"])
		{?>
			<div class="text_info tabs_change">
				<div class="tabs">
					<ul class="menu_type">
						<?if($arResult["DETAIL_TEXT"])
						{?>
							<li class="change_tab active"><a href="#">Описание</a></li>
						<?}
						if($arResult["PROPERTIES"]["USER"]["VALUE"])
						{?>
							<li class="change_tab <?if(!$arResult["DETAIL_TEXT"]){echo "active";}?>"><a href="#">Используется в следующих элементах</a></li>
						<?}?>
					</ul>
					<div class="clear"></div>
					<div class="tab_content">
						<?if($arResult["DETAIL_TEXT"])
						{?>
							<div class="tab active">
								<?=$arResult["DETAIL_TEXT"]?>
							</div>
						<?}
						if($arResult["PROPERTIES"]["USER"]["VALUE"])
						{?>
							<div class="tab <?if(!$arResult["DETAIL_TEXT"]){echo "active";}?>">
								<div class="catalog_section_list in_news">
									<div class="items">
										<?$ar_items=CIBlockElement::GetList(
											array("SORT"=>"ASC"),
											array("IBLOCK_ID"=>$arResult["IBLOCK_ID"],"ACTIVE"=>"Y","ID"=>$arResult["PROPERTIES"]["USER"]["VALUE"]),
											false,
											false,
											array("ID","NAME","DETAIL_PAGE_URL","PREVIEW_PICTURE","PREVIEW_TEXT","CATALOG_GROUP_1","PROPERTY_CML2_ARTICLE", "PROPERTY_COUNT_RATE")
										);
										while($item=$ar_items->GetNext())
										{
											$price=My::GetMinPrice($item["ID"],1);?>
											<div class="item">
												<a href="<?=$item["DETAIL_PAGE_URL"]?>" class="image">
													<span>
														<img <?=My::NewResize($item["PREVIEW_PICTURE"]["ID"],219,210,false);?> alt="<?=$item["NAME"]?>" />
													</span>
												</a>

													<?if($item["PROPERTIES"]["CML2_ARTICLE"]["VALUE"] && $item["PROPERTIES"]["CML2_ARTICLE"]["VALUE"] != "10.10")
													{?>
													<span class="art">
														Арт. <?=$item["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?>
													</span>
													<?}?>

												<span class="name">
													<a href="<?=$item["DETAIL_PAGE_URL"]?>">
														<?=$item["NAME"]?>
													</a>
												</span>
												<span class="preview"><?=$item["PREVIEW_TEXT"]?></span>
												<?if($item["CATALOG_QUANTITY"]>0 && $price["PRICE"]>0)
												{?>
													<span class="price"><b><?=My::Money($price["PRICE"]);?></b> руб.</span>
													<a href="#" class="buy add2basket <?if($item["CATALOG_QUANTITY"]==0){echo "null";}?>" data-id="<?=$item["ID"]?>" data-count="<?=$item["CATALOG_QUANTITY"]?>">В корзину</a>
												<?}
												else
												{?>
													<div class="no_count">
														<span>Под заказ</span>
														<a href="#" class="buy open_popup change_item_id" data-rate="" data-id="no_item" data-itemid="<?=$item["ID"]?>">Заказать</a>
													</div>
												<?}?>
											</div>
										<?}?>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						<?}?>
					</div>
				</div>
			</div>
		<?}?>
	</div>
	<div class="clear"></div>
</div>
<div class="look_like">
	<div class="title">Похожие товары </div>
	<div class="carusel">
		<noindex><nofollow><div class="arrows"><a href="#" class="left"></a><a href="#" class="right"></a></div></nofollow></noindex>
		<div class="inn">
			<ul class="menu_type" style="width:10000px;">
				<?$filter=array("IBLOCK_ID"=>$arResult["IBLOCK_ID"],"ACTIVE"=>"Y");
				if($arResult["PROPERTIES"]["SAME"]["VALUE"])
				{
					$filter["ID"]=$arResult["PROPERTIES"]["SAME"]["VALUE"];
					$req=false;
				}
				else
				{
					$filter["SECTION_ID"]=$arResult["IBLOCK_SECTION_ID"];
					$filter["!ID"]=$arResult["ID"];
					$req=array("nTopCount"=>10);
				}
				$ar_same_items=CIBlockElement::GetList(
					array("SORT"=>"ASC"),
					$filter,
					false,
					$req,
					array("ID","NAME","DETAIL_PAGE_URL","PREVIEW_PICTURE","PREVIEW_TEXT","PROPERTY_CML2_ARTICLE", "PROPERTY_CML2_BASE_UNIT", "CATALOG_GROUP_1", "PROPERTY_COUNT_RATE","PROPERTY_NAIMENOVANIE_SAYT01","PROPERTY_NAIMENOVANIE_SAYT02","PROPERTY_NAIMENOVANIE_SAYT03")
				);
				while($same_item=$ar_same_items->GetNext())
				{

					if ($same_item["PROPERTY_COUNT_RATE_VALUE"]){
						$count = $same_item["PROPERTY_COUNT_RATE_VALUE"];
					}else{
						$count = 1;
					}
					$price=My::GetMinPrice($same_item["ID"],1);?>
					<li class="item">
						<a href="<?=$same_item["DETAIL_PAGE_URL"]?>" class="image">
							<span>
								<img <?=My::NewResize($same_item["PREVIEW_PICTURE"],190,190,false);?> alt="<?=$same_item["NAME"]?>">
							</span>
						</a>
						<?if ($same_item["PROPERTY_CML2_ARTICLE_VALUE"] && $same_item["PROPERTY_CML2_ARTICLE_VALUE"] != "10.10"){?>
							<span class="art">Арт. <?=$same_item["PROPERTY_CML2_ARTICLE_VALUE"]?></span>
						<?}?>
						<span class="name"><a href="<?=$same_item["DETAIL_PAGE_URL"]?>">
						<? if(strlen($same_item["PROPERTY_NAIMENOVANIE_SAYT01_VALUE"]) > 1){ ?>
						<span><?=$same_item["PROPERTY_NAIMENOVANIE_SAYT01_VALUE"]?></span>
						<?=$same_item["PROPERTY_NAIMENOVANIE_SAYT02_VALUE"]?><br>
						<?=$same_item["PROPERTY_NAIMENOVANIE_SAYT03_VALUE"]?>
					<?}else{?>
						<?=$same_item["NAME"]?>
					<?}?>
						</a></span>
						<span class="prev"><?=$same_item["PREVIEW_TEXT"]?></span>
						<?if($same_item["CATALOG_QUANTITY"]>0 && $price["PRICE"]>0)
						{?>
							<?if ($price["FULL"] > $price["PRICE"]) {?>
								<?/*?><div class="price"><div class="old"><b><?=My::Money($price["FULL"]);?></b> руб.</div><?=My::Money($price["PRICE"]);?> <b>руб. / <?=$same_item["PROPERTY_CML2_BASE_UNIT_VALUE"]?>.</b></div><?*/?>
								<div class="price"><?=My::Money($price["FULL"]);?> <b>руб. / <?=$same_item["PROPERTY_CML2_BASE_UNIT_VALUE"]?>.</b></div>
							<?}else{?>
								<div class="price"><?=My::Money($price["PRICE"]);?> <b>руб. / <?=$same_item["PROPERTY_CML2_BASE_UNIT_VALUE"]?>.</b></div>
							<?}?>
							<a href="#" class="buy add2basket <?if($same_item["CATALOG_QUANTITY"]==0){echo "null";}?>" data-rate="<?=$count?>" data-id="<?=$same_item["ID"]?>" data-count="<?=$same_item["CATALOG_QUANTITY"]?>">В корзину</a>
						<?}
						else
						{?>
							<div class="no_count">
								<span>Под заказ</span>
								<a href="#" class="buy open_popup change_item_id" data-id="no_item" data-itemid="<?=$same_item["ID"]?>">Заказать</a>
							</div>
						<?}?>
					</li>
				<?}?>
			</ul>
			<div class="clear"></div>
		</div>
	</div>
</div>
