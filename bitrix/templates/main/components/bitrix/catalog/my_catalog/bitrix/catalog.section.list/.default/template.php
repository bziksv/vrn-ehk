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
$this->setFrameMode(true);?>
<?
// текущая страница: /ru/support/index.php?id=3&s=5
global $APPLICATION;
$dir = $APPLICATION->GetCurDir();
// в $dir будет значение "/ru/support/"
?>
<? //echo "<pre>"; print_r($arResult); echo "</pre>";
//404
if (empty($arResult["SECTIONS"]) AND $arResult["SECTION"]["ID"] <= 0 AND $dir <> "/catalog/filter/"){
		//echo 1;
		 global $APPLICATION;
        //$APPLICATION->RestartBuffer();
       // include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
       include $_SERVER['DOCUMENT_ROOT'].'/404.php';
       //include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
	
}


?>
<div class="selector_block">
	<div class="selector_content">
		<div class="catalog_section_list">

		<div class="items">


		<?
				foreach ($arResult['SECTIONS'] as &$arSection){

					if($arSection['IBLOCK_SECTION_ID'] == 944){

						if(CModule::IncludeModule("iblock"))
						{

							  $arFilter = Array('IBLOCK_ID'=>$arParams['IBLOCK_ID'], 'GLOBAL_ACTIVE'=>'Y', 'SECTION_ID'=>$arSection['ID']);
							  $db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, true);
							  while($ar_result = $db_list->GetNext())
							  {
								  $arResult['SECTIONS'][] = $ar_result;
							  }

						}
					}
				}



				$arrCount = array();


				foreach ($arResult['SECTIONS'] as &$arSection){

					if(
					$arSection['IBLOCK_SECTION_ID'] == 944 OR
					$arSection['IBLOCK_SECTION_ID'] == 946
					){

						if(CModule::IncludeModule("iblock"))
						{



						   // выберем 10 элементов из папки $ID информационного блока $BID
						   $items = GetIBlockElementList($arParams['IBLOCK_ID'], $arSection['ID'], Array("sort"=>"asc"),false,$fillter);
						   while($arItem = $items->GetNext())
						   {



			$prop = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arItem["ID"], array("sort" => "asc"), Array("CODE"=>"CML2_ARTICLE")); // по известным только Битрикс причинам, при выводе Артикула в списке, Битрикс обрезал 0 в начале и конце, поэтому добавлена таккая конструкция
			$prop = $prop -> Fetch();


			if ($arItem["PROPERTIES"]["COUNT_RATE"]["VALUE"]){
				$count = $arItem["PROPERTIES"]["COUNT_RATE"]["VALUE"];
			}else{
				$count = 1;
			}
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
			$strMainID = $this->GetEditAreaId($arItem['ID']);
			$price=My::GetMinPrice($arItem["ID"],1);
			$QUANTITY = CCatalogProduct::GetByID($arItem["ID"]);

			?>

			<div class="item" id="<?=$strMainID;?>">



			<?
			$stiker = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arItem["ID"], array("sort" => "asc"), Array("CODE"=>"STICKER"));
			$stiker = $stiker -> Fetch();

			$last_prod = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arItem["ID"], array("sort" => "asc"), Array("CODE"=>"LAST_PRODUCT"));
			$last_prod = $last_prod -> Fetch();

			if ($stiker['VALUE_ENUM'] == "Y") {?>
				<?if ($price["FULL"] > $price["PRICE"]) {?>
					<div class="sale <?=($last_prod["VALUE_ENUM"] == "Y") ? "last" : null;?>">- <?=round((($price["FULL"] - $price["PRICE"]) / $price["FULL"]) * 100);?>%</div>
				<?}?>
			<?}?>

			<?if($last_prod["VALUE_ENUM"] == "Y"):?>
				<div class="last-prod">
					<img src="<?=SITE_TEMPLATE_PATH ?>/images/last_prod.png">
				</div>
			<?endif;?>

				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="image">
					<span>
						<img <?=My::NewResize($arItem["PREVIEW_PICTURE"],219,210,false);?> alt="<?=$arItem["NAME"]?>" />
					</span>
				</a>
				<?
					//var_dump($QUANTITY['QUANTITY']);
				?>

				<?if($prop["VALUE"] != "10.10")
					{?>
					<span class="art">
						Арт. <?=$prop["VALUE"]?>
					</span>
					<?}else{?>
					<span class="" style="padding: 0 10px;height: 17px;overflow: hidden;display: inline-block;margin: 0 0 10px 0;"></span>
					<?}?>

				<span class="name">
				<?
					$arrayP = array('NAIMENOVANIE_SAYT01','NAIMENOVANIE_SAYT02','NAIMENOVANIE_SAYT03');
					foreach($arrayP as $p){
					$prop = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arItem["ID"], array("sort" => "asc"), Array("CODE"=>$p)); // по известным только Битрикс причинам, при выводе Артикула в списке, Битрикс обрезал 0 в начале и конце, поэтому добавлена таккая конструкция
					$prop = $prop -> Fetch();
					$arrayP[$prop['CODE']] =  $prop['VALUE'];
					}
				?>

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
				<?if($QUANTITY['QUANTITY']>0 and $price["PRICE"]>0)
				{?>
					<?if ($price["FULL"] > $price["PRICE"]) {?>

							<?if($stiker['VALUE_ENUM'] == "Y" or $last_prod["VALUE_ENUM"] == "Y"):?>
								<div class="price with_old"><div class="old"><b><?=My::Money($price["FULL"])?></b> руб.<?if ($arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']){?> / <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']?>.<?}?></div><b><?=My::Money($price["PRICE"]);?></b> руб.<?if ($arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']){?> / <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']?>.<?}?></div>
							<?else:?>
								<div class="price"><div class="old"><b></b></div><b><?=My::Money($price["FULL"]);?></b> руб.<?if ($arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']){?> / <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']?>.<?}?></div>
							<?endif;?>

					<?
					}else{?>
					<div class="price"><div class="old"><b></b></div><b><?=My::Money($price["FULL"]);?></b> руб.<?if ($arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']){?> / <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE']?>.<?}?></div>
					<?}?>
					<a href="#" class="buy add2basket <?if($QUANTITY['QUANTITY']==0){echo "null";}?>" data-id="<?=$arItem["ID"]?>" data-rate="<?=$count?>" data-count="<?=$QUANTITY['QUANTITY']?>">В корзину</a>
				<?}
				else
				{?>
					<div class="no_count">
						<span>Под заказ</span>
						<a href="#" class="buy open_popup change_item_id" data-id="no_item" data-itemid="<?=$arItem["ID"]?>">Заказать</a>
					</div>
				<?}?>
			</div>


			<?

						   }

						}

					}

				}
		?>
		<div class="clear"></div>
		</div>



			<ul class="menu_type">
				<?foreach ($arResult['SECTIONS'] as &$arSection)
				{

					if(
					$arSection['IBLOCK_SECTION_ID'] != 944 and
					$arSection['IBLOCK_SECTION_ID'] != 946
					){

					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
					?>
					<li id="<?=$this->GetEditAreaId($arSection['ID']);?>" class="category_home_page">
						<a class="image" href="<?=$arSection["SECTION_PAGE_URL"]?>">
							<span>
								<img src="<?=$arSection["PICTURE"]["SRC"]?>" alt="<?=$arSection["NAME"]?>" />
							</span>
						</a>
						<span class="name">
							<a href="<?=$arSection["SECTION_PAGE_URL"]?>">
								<?
								if($arSection['UF_NAME_SECTION'])
									print implode("<br/>", $arSection['UF_NAME_SECTION']);
								else
									print str_replace('~', '<br/>', $arSection["NAME"])
								?>
							</a>
						</span>
					</li>
				<?}
				}
				?>
			</ul>
			<div class="clear"></div>
		</div>
		<?if(!$GLOBALS["HIDE_DESK"])
		{?>
			<div class="under_text">
				<h1><?=$arResult['SECTION']['NAME']?></h1>
				<?=$arResult['SECTION']['DESCRIPTION']?>

				<?$APPLICATION->SetPageProperty('title', $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_META_TITLE"]);?>
				<?$APPLICATION->SetPageProperty('description', $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]);?>
				<?$APPLICATION->SetPageProperty('keywords', $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"]);?>
			</div>
		<?}?>
	</div>
</div>
