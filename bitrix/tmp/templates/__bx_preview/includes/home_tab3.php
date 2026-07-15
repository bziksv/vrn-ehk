<?

$arFilter = [
	"IBLOCK_ID" => 21, // ID Инфо блока
	"SECTION_ID" => 1168, // ID Категории
	"INCLUDE_SUBSECTIONS" => "Y",
	"ACTIVE" => "Y",
	[
		"LOGIC" => "OR", 
		["PROPERTY_PROIZVODITEL_VALUE" => "PolyTerra"], 
		["PROPERTY_PROIZVODITEL_VALUE" => "HILST"]
	]
];

$res = CIBlockElement::GetList(["SORT" => "ASC"], $arFilter, false, false, ["*", "CATALOG_GROUP_1", "CATALOG_QUANTITY"]);
?>

<div class="catalog_section_list">
		
	<div class="items">
			<? while($ob = $res->GetNextElement()): 
					$arFields = $ob->GetFields();
					$arProperties = $ob->GetProperties();
					
					$price = My::GetMinPrice($arFields["ID"], 1);
			?>
	
			<div class="item">

				<? if($arProperties["NEW_STICKER"]["VALUE"] == "Y"): ?>
					<div class="stickers">
						<div class="news">Новинка</div>
					</div>
				<? endif; ?>
				
				<?if ($arProperties["STICKER"]["VALUE"] == "Y") {?>
					<?if ($price["FULL"] > $price["PRICE"]) {?>
						<div class="sale <?=($arProperties["LAST_PRODUCT"]["VALUE"] == "Y") ? "last" : null;?>"> - <?=round((($price["FULL"] - $price["PRICE"]) / $price["FULL"]) * 100);?>%</div>
					<?}?>
				<?}?>
				
				<?if($arProperties["LAST_PRODUCT"]["VALUE"] == "Y"):?>
					<div class="last-prod">
						<img src="<?=SITE_TEMPLATE_PATH ?>/images/last_prod.png">
					</div>
				<?endif;?>

				<a href="<?=$arFields["DETAIL_PAGE_URL"]?>" class="image">
					<span><img src="<?=CFile::GetPath($arFields["PREVIEW_PICTURE"])?>" alt="<?=$arFields["NAME"]?>"></span>
				</a>
			
				<? if ($arProperties["CML2_ARTICLE"]["VALUE"]):?>
					<span class="art">Арт. <?=$arProperties["CML2_ARTICLE"]["VALUE"]?></span>
				<? else: ?>
					<span class="art" style="background:none"></span>
				<? endif; ?>
				
				<span class="name">
					<a href="<?=$arFields["DETAIL_PAGE_URL"]?>">
					<? if(strlen($arProperties["NAIMENOVANIE_SAYT01"]["VALUE"]) > 1) { ?>
						<span><?=$arProperties["NAIMENOVANIE_SAYT01"]["VALUE"]?></span>
						<?=$arProperties["NAIMENOVANIE_SAYT02"]["VALUE"]?><br>
						<?=$arProperties["NAIMENOVANIE_SAYT03"]["VALUE"]?>
					<?}else{?>
						<?=$arFields["NAME"]?>
					<?}?>
					</a>
				</span>
			
				<span class="preview"><?=$arFields["NAME"]?></span>
				
				<? if ($arFields["CATALOG_QUANTITY"] > 0 && $price["PRICE"] > 0) { ?>
					<? if ($price["FULL"] > $price["PRICE"]) { ?>
					
					<? if ($arProperties["STICKER"]["VALUE"] == "Y" or $arProperties["LAST_PRODUCT"]["VALUE"] == "Y"): ?>
						<div class="price with_old"><div class="old"><b><?=My::Money($price["FULL"])?></b> руб.
						<? if ($arProperties['CML2_BASE_UNIT']['VALUE']) {?> / <?=$arProperties['CML2_BASE_UNIT']['VALUE']?>.<?}?>
						</div>
						<b>
						<?=My::Money($price["PRICE"]);?></b> руб.<?if ($arProperties['CML2_BASE_UNIT']['VALUE']) {?> / <?=$arProperties['CML2_BASE_UNIT']['VALUE']?>.<?}?>
						</div>
					<?else:?>
						<div class="price"><div class="old"><b></b></div><b><?=My::Money($price["FULL"]);?></b> руб.<?if ($arProperties['CML2_BASE_UNIT']['VALUE']){?> / <?=$arProperties['CML2_BASE_UNIT']['VALUE']?>.<?}?></div>
					<?endif;?>


					<?
					}else{?>
					<div class="price"><div class="old"><b></b></div><b><?=My::Money($price["FULL"]);?></b> руб.<?if ($arProperties['CML2_BASE_UNIT']['VALUE']){?> / <?=$arProperties['CML2_BASE_UNIT']['VALUE']?>.<?}?></div>
					<?}?>
					<a href="#" class="buy add2basket <?if($arFields["CATALOG_QUANTITY"] == 0) {echo "null";}?>" data-id="<?=$arFields["ID"]?>" data-rate="1" data-count="<?=$arFields["CATALOG_QUANTITY"]?>">В корзину</a>
				<?}
				else
				{?>
					<div class="no_count">
						<span>Под заказ</span>
						<a href="#" class="buy open_popup change_item_id" data-id="no_item" data-itemid="<?=$arFields["ID"]?>">Заказать</a>
					</div>
				<?}?>
			
			</div>
			
			<? endwhile; ?>
	</div>
						
	<div class="clear"></div>

</div>