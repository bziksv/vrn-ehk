<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$ar_nav = CIBlockSection::GetNavChain($arParams["IBLOCK_ID"], $arResult["IBLOCK_SECTION_ID"]);
while($nav=$ar_nav->Fetch())
{
	$APPLICATION->AddChainItem($nav["NAME"], $nav["SECTION_PAGE_URL"]);
}?>
<div class="gallery_detail">
	<a href="/gallery/" class="back">Назад к списку работ<span></span></a>
	<div class="image_col">
		<div class="big_images">
			<?foreach($arResult["PROPERTIES"]["PHOTOS"]["VALUE"] as $key=>$image_id)
			{
				$big_picture=My::Resize($image_id,800,800,true);?>
				<div class="big_image <?if($key==0){echo "active";}?>">
					<a href="<?=$big_picture["SRC"]?>" rel="lightbox[gallery]" class="cur_image">
						<span>
							<img <?=My::NewResize($image_id,470,470,true);?> alt="<?=$arResult["NAME"]?>" />
						</span>
					</a>
				</div>
			<?}?>
		</div>
		<div class="preview_images">
			<?if(sizeof($arResult["PROPERTIES"]["PHOTOS"]["VALUE"])>4)
			{?>
				<div class="man"><a class="empty left" href="#">&nbsp;</a><a class="empty right" href="#">&nbsp;</a></div>
			<?}?>
			<div class="inn">
				<ul style="width:1000px" class="menu_type">
					<?foreach($arResult["PROPERTIES"]["PHOTOS"]["VALUE"] as $key=>$image_id)
					{?>
						<li class="<?if($key==0){echo "act";}?>">
							<a href="#">
								<span>
									<img <?=My::NewResize($image_id,80,80,true);?> alt="<?=$arResult["NAME"]?> <?=$key+1?>">
								</span>
							</a>
						</li>
					<?}?>
				</ul>
			</div>
		</div>
	</div>
	<div class="info_col">
		<h1 class="name"><b><?=$arResult["NAME"]?></b>,<br /><?=$arResult["PREVIEW_TEXT"]?></h1>
		<span class="title">Описание:</span>
		<div class="hidden_text">
			<span>
				<?=$arResult["DETAIL_TEXT"]?>
			</span>
		</div>
		<div class="buttons_block">
			<a class="more show_hidden_text" href="#">Подробнее</a>
			<?if(isset($arResult["PROPERTIES"]["REVIEW"]["VALUE"]) && !empty($arResult["PROPERTIES"]["REVIEW"]["VALUE"]))
			{
				$ar_cur_review=CIBlockElement::GetByID($arResult["PROPERTIES"]["REVIEW"]["VALUE"]);
				if($cur_review=$ar_cur_review->GetNext())
				{?>
					<a class="review" href="<?=$cur_review["DETAIL_PAGE_URL"]?>">Отзыв клиента</a>
				<?}
			}
			else
			{?>
				<a class="review" href="/reviews/">Отзывы клиентов</a>
			<?}?>
		</div>
		<span class="title">Поделиться ссылкой:</span>
		<div class="social">
			<script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" data-yashareTheme="counter"></div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?if($arResult["PROPERTIES"]["ITEMS"]["VALUE"])
{?>
	<div class="look_like">
		<div class="title">Элементы, используемые в проекте</div>
		<div class="carusel">
			<noindex><nofollow><div class="arrows"><a href="#" class="left"></a><a href="#" class="right"></a></div></nofollow></noindex>
			<div class="inn">
				<ul class="menu_type" style="width:10000px;">
					<?$ar_same_items=CIBlockElement::GetList(
						array("SORT"=>"ASC"),
						array("IBLOCK_ID"=>5,"ACTIVE"=>"Y","ID"=>$arResult["PROPERTIES"]["ITEMS"]["VALUE"]),
						false,
						false,
						array("ID","NAME","DETAIL_PAGE_URL","PREVIEW_PICTURE","PREVIEW_TEXT","PROPERTY_CML2_ARTICLE","CATALOG_GROUP_1")
					);
					while($same_item=$ar_same_items->GetNext())
					{
						$price=My::GetMinPrice($same_item["ID"],1);?>
						<li class="item">
							<a href="<?=$same_item["DETAIL_PAGE_URL"]?>" class="image">
								<span>
									<img <?=My::NewResize($same_item["PREVIEW_PICTURE"],219,210,false);?> alt="<?=$same_item["NAME"]?>">
								</span>
							</a>
							<span class="art">Арт. <?=$same_item["PROPERTY_CML2_ARTICLE_VALUE"]?></span>
							<span class="name"><a href="<?=$same_item["DETAIL_PAGE_URL"]?>"><?=$same_item["NAME"]?></a></span>
							<span class="prev"><?=$same_item["PREVIEW_TEXT"]?></span>
							<?if($same_item["CATALOG_QUANTITY"]>0 && $price["PRICE"]>0)
							{?>
								<span class="price"><?=My::Money($price["PRICE"]);?> <b>руб.</b></span>
								<a href="#" class="buy add2basket <?if($same_item["CATALOG_QUANTITY"]==0){echo "null";}?>" data-id="<?=$same_item["ID"]?>" data-count="<?=$same_item["CATALOG_QUANTITY"]?>">В корзину</a>
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
<?}?>
<div class="gallery_list_on_detail">
	<div class="title">Похожие проекты</div>
	<div class="items">
		<?$filter=array("IBLOCK_ID"=>$arResult["IBLOCK_ID"],"ACTIVE"=>"Y");
		if($arResult["PROPERTIES"]["SAME"]["VALUE"])
		{
			$filter["ID"]=$arResult["PROPERTIES"]["SAME"]["VALUE"];
		}
		else
		{
			$filter["!ID"]=$arResult["ID"];
		}
		$ar_same_items=CIBlockElement::GetList(
			array("RAND"=>"ASC"),
			$filter,
			false,
			array("nTopCount"=>5),
			array("ID","NAME","DETAIL_PAGE_URL","ACTIVE_FROM","PREVIEW_PICTURE","PREVIEW_TEXT")
		);
		while($same_item=$ar_same_items->GetNext())
		{
			$price=My::GetMinPrice($same_item["ID"],1);?>
			<div class="item">
				<a href="<?=$same_item["DETAIL_PAGE_URL"]?>" class="image"><span><img <?=My::NewResize($same_item["PREVIEW_PICTURE"],210,210,false);?> alt="<?=$same_item["NAME"]?>"></span></a>
				<span class="name"><a href="<?=$same_item["DETAIL_PAGE_URL"]?>"><?=$same_item["NAME"]?>, <?=$same_item["PREVIEW_TEXT"]?></a></span>
				<span class="date"><?=$same_item["ACTIVE_FROM"]?></span>
			</div>
		<?}?>
		<div class="clear"></div>
	</div>
</div>