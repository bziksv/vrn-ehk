<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?echo ShowError($arResult["ERROR_MESSAGE"]);
if ($normalCount > 0)
{?>
	<div class="basket_page">
		<div class="line title">
			<div class="number">№</div>
			<div class="photo">Фото</div>
			<div class="name">Наименование товара</div>
			<div class="articul">Артикул</div>
			<div class="price">Цена</div>
			<div class="weight">Вес</div>
			<div class="count">Кол-во</div>
			<div class="total_price">Стоимость</div>
			<div class="delete">Удалить</div>
			<div class="clear"></div>
		</div>
		<?$count=0;
		$VALUES = array();
		foreach($arResult["GRID"]["ROWS"] as $k=>$arItem)
		{
			
			if ($arItem["PROPERTY_COUNT_RATE_VALUE"]){
				$c_count = $arItem["PROPERTY_COUNT_RATE_VALUE"];
			}else{
				$c_count = 1;
			}
			if($arItem["DELAY"]=="N" && $arItem["CAN_BUY"]=="Y")
			{
				$count++;?>
				<div class="line <?if($count==sizeof($arResult["GRID"]["ROWS"])){echo "last";}?>" data-id="<?=$arItem["ID"]?>">
					<div class="number"><span><?=$count;?>.</span></div>
					<div class="photo">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
							<span>
								<img <?=My::NewResize($arItem["PREVIEW_PICTURE"],47,47,false);?> alt="<?=$arItem["NAME"]?>">
							</span>
						</a>
					</div>
					<div class="name"><span><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></span></div>
					<div class="articul">
						<span>
							<?foreach($arItem["PROPS"] as $val)
							{
								if($val["CODE"]=="ARTICLE" && !empty($val["VALUE"]) && $val["VALUE"] != "10.10")
								{?>
									Арт. <?=$val["VALUE"]?>
								<?}
							}?>
						</span>
					</div>
					<div class="price">
						<span>
							<b><?=My::Money($arItem["PRICE"]);?></b> руб.
						</span>
					</div>
					<?
											
							$res = CIBlockElement::GetProperty(5, $arItem['PRODUCT_ID'], "sort", "asc", array("CODE" => "CML2_TRAITS"));
						
					?>
					<div class="weight">
						<span>
							<?
							while ($ob = $res->GetNext())
								{
									if($ob['DESCRIPTION'] == 'Вес'){
										if($ob['VALUE'] != 0 and !empty($ob['VALUE'])){
											print '<b>'.$ob['VALUE'].' </b>кг.';	
										}
										$VALUES[] = $ob['VALUE']*$arItem["QUANTITY"];
									}
								}	
							?>
						</span>
					</div>
					<div class="count_block">
						<span class="value">
							<input type="text" data-count="<?=$arItem["AVAILABLE_QUANTITY"]?>" data-rate="<?=$c_count?>" value="<?=$arItem["QUANTITY"]?>" />
							<a class="plus" href="#">+</a>
							<a class="minus" href="#">-</a>
						</span>
					</div>
					<div class="total_price">
						<span>
							<b><?=My::Money($arItem["QUANTITY"]*$arItem["PRICE"]);?></b> руб.
						</span>
					</div>
					<div class="delete"><a href="#" class="empty delete_item">&nbsp;</a></div>
					<div class="clear"></div>
				</div>
			<?}
		}
		?>
		
		<div class="total_info_line">
		
		<div class="clear_basket"><a href="#" class="clear_all_basket">Очистить корзину</a></div>
		<?
		if(!empty($VALUES) and $VALUES != 0){
			print '<div style="border-bottom:none;width:200px" class="total_basket"><div class="label"> Общий вес: '.array_sum($VALUES).' кг.</div></div>';
		}
		?>
			
			<div class="total_basket">
				<div class="label">Товаров на сумму:</div>
				<div class="value"><b><?=My::Money($arResult["allSum"])?></b> руб.</div>
			</div>
			<div class="buttons_block">
				<a class="button open_popup" data-id="fast_order_popup" href="#">Быстрая покупка</a>
				<a class="button" href="/make-order/">Оформить заказ</a>
				<div class="fast_order popup" id="fast_order_popup">
					<a href="#" class="close empty">&nbsp;</a>
					<div class="title">Быстрый заказ</div>
					<form action="auto_load" class="fast_order_form" method="GET"></form>
				</div>
			</div>
		</div>
	</div>
<?}
else
{?>
	<div id="basket_items_list">
		<table>
			<tbody>
				<tr>
					<td colspan="<?=$numCells?>" style="text-align:center">
						<div class=""><?=GetMessage("SALE_NO_ITEMS");?></div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
<?}?>