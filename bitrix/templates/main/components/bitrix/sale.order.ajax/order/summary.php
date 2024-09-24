<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="right_order_basket">
	<div class="title">Ваш заказ:</div>
	<div class="goods">
		<div class="inn">
			<ul>
				<?foreach ($arResult["GRID"]["ROWS"] as $k => $arData)
				{
					$sep="шт.";
					$ar_res = CIBlockElement::GetProperty(5, $arData["data"]["PRODUCT_ID"], "sort", "asc", array("CODE"=>"CML2_BASE_UNIT"));
					if($res=$ar_res->GetNext())
					{
						$sep=$res['VALUE'];
					}?>
					<li>
						<a href="<?=$arData["data"]["DETAIL_PAGE_URL"]?>" class="image">
							<span>
								<img <?=My::NewResize($arData["data"]["PREVIEW_PICTURE"],60,60,false);?> alt="<?=$arData["data"]["NAME"]?>">
							</span>
						</a>
						<span class="info">
							<span class="name"><a href="<?=$arData["data"]["DETAIL_PAGE_URL"]?>"><?=$arData["data"]["NAME"]?></a></span>
							<span class="price"><b><?=My::Money($arData["data"]["PRICE"]);?></b> руб</span>
							<span class="quantity"><b><?=round($arData["data"]["QUANTITY"]);?></b> <?=$sep?>.</span>
							<span class="clear"></span>
						</span>
						<span class="clear"></span>
					</li>
				<?}?>
			</ul>
		</div>
	</div>
	<?$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
	$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
	$bPropsColumn = false;
	$bUseDiscount = false;
	$bPriceType = false;
	$bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column?>
	<div class="bx_ordercart">
		<div class="bx_ordercart_order_pay">
			<div class="bx_ordercart_order_pay_right">
				<table class="bx_ordercart_order_sum">
					<tbody>
						<tr>
							<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_WEIGHT_SUM")?></td>
							<td class="custom_t2" class="price"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
						</tr>
						<tr>
							<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_SUMMARY")?></td>
							<td class="custom_t2" class="price"><?=$arResult["ORDER_PRICE_FORMATED"]?></td>
						</tr>
						<?
						if (doubleval($arResult["DISCOUNT_PRICE"]) > 0)
						{
							?>
							<tr>
								<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?><?if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"])>0):?> (<?echo $arResult["DISCOUNT_PERCENT_FORMATED"];?>)<?endif;?>:</td>
								<td class="custom_t2" class="price"><?echo $arResult["DISCOUNT_PRICE_FORMATED"]?></td>
							</tr>
							<?
						}
						if(!empty($arResult["TAX_LIST"]))
						{
							foreach($arResult["TAX_LIST"] as $val)
							{
								?>
								<tr>
									<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=$val["NAME"]?> <?=$val["VALUE_FORMATED"]?>:</td>
									<td class="custom_t2" class="price"><?=$val["VALUE_MONEY_FORMATED"]?></td>
								</tr>
								<?
							}
						}
						if (doubleval($arResult["DELIVERY_PRICE"]) > 0)
						{
							?>
							<tr>
								<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_DELIVERY")?></td>
								<td class="custom_t2" class="price"><?=$arResult["DELIVERY_PRICE_FORMATED"]?></td>
							</tr>
							<?
						}
						if (strlen($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0)
						{
							?>
							<tr>
								<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_PAYED")?></td>
								<td class="custom_t2" class="price"><?=$arResult["PAYED_FROM_ACCOUNT_FORMATED"]?></td>
							</tr>
							<?
						}

						if ($bUseDiscount):
						?>
							<tr>
								<td class="custom_t1 fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_IT")?></td>
								<td class="custom_t2 fwb" class="price"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></td>
							</tr>
							<tr>
								<td class="custom_t1" colspan="<?=$colspan?>"></td>
								<td class="custom_t2" style="text-decoration:line-through; color:#828282;"><?=$arResult["PRICE_WITHOUT_DISCOUNT"]?></td>
							</tr>
						<?
						else:
						?>
							<tr>
								<td class="custom_t1 fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_IT")?></td>
								<td class="custom_t2 fwb" class="price"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></td>
							</tr>
						<?
						endif;
						?>
					</tbody>
				</table>
				<div style="clear:both;"></div>

			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
