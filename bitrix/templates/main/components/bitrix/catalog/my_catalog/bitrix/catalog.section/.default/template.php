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
if (!empty($arResult['ITEMS']))

{?>
	<div class="items">
		<?foreach ($arResult['ITEMS'] as $key => $arItem)
		{
			$prop = CIBlockElement::GetProperty($arItem['IBLOCK_ID'], $arItem["ID"], array("sort" => "asc"), Array("CODE"=>"CML2_ARTICLE")); // по известным только Битрикс причинам, при выводе Артикула в списке, Битрикс обрезал 0 в начале и конце, поэтому добавлена таккая конструкция
			$prop = $prop -> Fetch();

			if ($arItem["PROPERTIES"]["COUNT_RATE"]["VALUE"]){
				$count = $arItem["PROPERTIES"]["COUNT_RATE"]["VALUE"];
			}else{
				$count = 1;
			}
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
			$strMainID = $this->GetEditAreaId($arItem['ID']);
			$price=My::GetMinPrice($arItem["ID"],1);?>
			<div class="item" id="<?=$strMainID;?>">

			<? if($arItem["PROPERTIES"]["NEW_STICKER"]["VALUE"] == "Y"): ?>
                <div class="stickers">
                    <div class="news">Новинка</div>
                </div>
            <? endif; ?>

			<?if ($arItem["PROPERTIES"]["STICKER"]["VALUE"] == "Y") {?>
				<?if ($price["FULL"] > $price["PRICE"]) {?>
					<div class="sale <?=($arItem["PROPERTIES"]["LAST_PRODUCT"]["VALUE"] == "Y") ? "last" : null;?>">- <?=round((($price["FULL"] - $price["PRICE"]) / $price["FULL"]) * 100);?>%</div>
				<?}?>
			<?}?>

			<?if($arItem["PROPERTIES"]["LAST_PRODUCT"]["VALUE"] == "Y"):?>
				<div class="last-prod">
					<img src="<?=SITE_TEMPLATE_PATH ?>/images/last_prod.png">
				</div>
			<?endif;?>


				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="image">
					<span>
						<img <?=My::NewResize($arItem["PREVIEW_PICTURE"]["ID"],219,210,false);?> alt="<?=$arItem["NAME"]?>" />
					</span>
				</a>
				<?
					//var_dump($arItem["PROPERTIES"]);
				?>
					<?if($prop["VALUE"] && $prop["VALUE"] != "10.10")
					{?>
					<span class="art">
						Арт. <?=$prop["VALUE"]?>
					</span>
					<?}else{?>
					<span class="art" style="background:none"></span>
					<?}?>

				<?
					$arrayP = array('NAIMENOVANIE_SAYT01','NAIMENOVANIE_SAYT02','NAIMENOVANIE_SAYT03');
					foreach($arrayP as $p){
					$prop = CIBlockElement::GetProperty($arItem['IBLOCK_ID'], $arItem["ID"], array("sort" => "asc"), Array("CODE"=>$p)); // по известным только Битрикс причинам, при выводе Артикула в списке, Битрикс обрезал 0 в начале и конце, поэтому добавлена таккая конструкция
					$prop = $prop -> Fetch();
					$arrayP[$prop['CODE']] =  $prop['VALUE'];
					}
				?>



				<span class="name">
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
					<? if(strlen($arrayP["NAIMENOVANIE_SAYT01"]) > 1){ ?>
						<span><?=$arrayP["NAIMENOVANIE_SAYT01"]?></span>
						<?=$arrayP["NAIMENOVANIE_SAYT02"]?><br>
						<?=$arrayP["NAIMENOVANIE_SAYT03"]?>
					<?}else{?>
						<?=$arItem["NAME"]?>
					<?}?>
					</a>
				</span>
				<span class="preview"><?=$arItem["PREVIEW_TEXT"]?></span>
				<?if($arItem["CATALOG_QUANTITY"]>0 && $price["PRICE"]>0)
				{?>
					<?if ($price["FULL"] > $price["PRICE"]) {?>
					<?/*?><div class="price with_old"><div class="old"><b><?=My::Money($price["FULL"])?></b> руб.</div><b><?=My::Money($price["PRICE"]);?></b> руб.<?if ($arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']){?> / <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']?>.<?}?></div><?*/?>


							<?if($arItem["PROPERTIES"]["STICKER"]["VALUE"] == "Y" or $arItem["PROPERTIES"]["LAST_PRODUCT"]["VALUE"] == "Y"):?>
								<div class="price with_old"><div class="old"><b><?=My::Money($price["FULL"])?></b> руб.<?if ($arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']){?> / <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']?>.<?}?></div><b><?=My::Money($price["PRICE"]);?></b> руб.<?if ($arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']){?> / <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']?>.<?}?></div>
							<?else:?>
								<div class="price"><div class="old"><b></b></div><b><?=My::Money($price["FULL"]);?></b> руб.<?if ($arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']){?> / <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']?>.<?}?></div>
							<?endif;?>


					<?
					}else{?>
					<div class="price"><div class="old"><b></b></div><b><?=My::Money($price["FULL"]);?></b> руб.<?if ($arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']){?> / <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']?>.<?}?></div>
					<?}?>
					<a href="#" class="buy add2basket <?if($arItem["CATALOG_QUANTITY"]==0){echo "null";}?>" data-id="<?=$arItem["ID"]?>" data-rate="<?=$count?>" data-count="<?=$arItem["CATALOG_QUANTITY"]?>">В корзину</a>
				<?}
				else
				{?>
					<div class="no_count">
						<span>Под заказ</span>
						<a href="#" class="buy open_popup change_item_id" data-id="no_item" data-itemid="<?=$arItem["ID"]?>">Заказать</a>
					</div>
				<?}?>
			</div>
		<?}?>
		<div class="clear"></div>
	</div>
	<?if ($arParams["DISPLAY_BOTTOM_PAGER"])
	{?>
		<?=$arResult["NAV_STRING"]?>
	<?}

}?>
