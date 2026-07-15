<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="news-detail">
	<div class="item">
		<a href="<?=$arResult["LIST_PAGE_URL"]?>" class="back_link">Назад к списку</a>
	<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<span class="news-date-time"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
	<?endif;?>
	<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<h1 class="name"><?=$arResult["NAME"]?></h1>
	<?endif;?>
	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<div class="preview"><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></div>
	<?endif;?>
	<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
		<img class="detail_picture" border="0" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["NAME"]?>"  title="<?=$arResult["NAME"]?>"/>
	<?endif?>
	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
	<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
	<div style="clear:both"></div>
	<br />
	<?foreach($arResult["FIELDS"] as $code=>$value):
		if ('PREVIEW_PICTURE' == $code || 'DETAIL_PICTURE' == $code)
		{
			?><?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?
			if (!empty($value) && is_array($value))
			{
				?><img border="0" src="<?=$value["SRC"]?>" width="<?=$value["WIDTH"]?>" height="<?=$value["HEIGHT"]?>"><?
			}
		}
		else
		{
			?>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?><?
		}
		?><br />
	<?endforeach;
	foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):
		if($arProperty["CODE"]!="ITEMS")
		{?>

			<?=$arProperty["NAME"]?>:&nbsp;
			<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
				<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
			<?else:?>
				<?=$arProperty["DISPLAY_VALUE"];?>
			<?endif?>
			<br />
		<?}
	endforeach;
	?>
	<div class="news-detail-share">
	<noindex>
		<div class="title">Поделиться ссылкой:</div>
		<div class="value"><script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" data-yashareTheme="counter"></div></div>
		<div class="clear"></div>
	</noindex>
	</div>
	</div>
</div>
<?if($arResult["PROPERTIES"]["ITEMS"]["VALUE"])
{?>
	<div class="catalog_section_list in_news">
		<div class="items">
			<?$ar_items=CIBlockElement::GetList(
				array("SORT"=>"ASC"),
				array("IBLOCK_ID"=>5,"ACTIVE"=>"Y","ID"=>$arResult["PROPERTIES"]["ITEMS"]["VALUE"]),
				false,
				false,
				array("ID","NAME","DETAIL_PAGE_URL","PREVIEW_PICTURE","PREVIEW_TEXT","CATALOG_GROUP_1","PROPERTY_CML2_ARTICLE")
			);
			while($item=$ar_items->GetNext())
			{
				//var_dump($item['PROPERTY_CML2_ARTICLE_VALUE']);
				$price=My::GetMinPrice($item["ID"],1);
				if ($item["PREVIEW_PICTURE"]){
					$resImage = CFile::ResizeImageGet($item['PREVIEW_PICTURE'], array('width'=>219, 'height'=>210), BX_RESIZE_IMAGE_PROPORTIONAL, true); 
				}
				?>
				<div class="item">
					<a href="<?=$item["DETAIL_PAGE_URL"]?>" class="image">
						<span>
							<?if ($resImage){?>
							<img src="<?=$resImage['src']?>" width="<?=$resImage['width']?>" height="<?=$resImage['height']?>" alt="<?=$item["NAME"]?>">
							<?
							}?>
						</span>
					</a>
					<?if($item['PROPERTY_CML2_ARTICLE_VALUE'])
						{?>
					<span class="art">
						Арт. <?=$item['PROPERTY_CML2_ARTICLE_VALUE']?>					
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
							<a href="#" class="buy open_popup change_item_id" data-id="no_item" data-itemid="<?=$item["ID"]?>">Заказать</a>
						</div>
					<?}?>
				</div>
			<?}?>
			<div class="clear"></div>
		</div>
	</div>
<?}?>
<div class="other_news">
	<div class="title">Другие <?=$arResult["IBLOCK_ID"]==2?"новости":"предложения";?></div>
	<div class="items">
		<?$ar_news=CIBlockElement::GetList(
			array("SORT"=>"ASC","active_from"=>"DESC"),
			array("IBLOCK_ID"=>$arResult["IBLOCK_ID"],"ACTIVE"=>"Y","!ID"=>$arResult["ID"]),
			false,
			array("nTopCount"=>3),
			array("ID","NAME","ACTIVE_FROM","DETAIL_PAGE_URL","PREVIEW_TEXT")
		);
		while($new=$ar_news->GetNext())
		{?>
			<div class="item">
				<span class="date"><?=$new["ACTIVE_FROM"]?></span>
				<span class="name"><a href="<?=$new["DETAIL_PAGE_URL"]?>"><?=$new["NAME"]?></a></span>
				<span class="preview"><?=$new["PREVIEW_TEXT"]?></span>
			</div>
		<?}?>
		<div class="clear"></div>
	</div>
</div>