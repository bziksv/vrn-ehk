<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="success_order">
	<?
	if (!empty($arResult["ORDER"]))
	{
		?>
		<table class="sale_order_full_table table_0">
			<tr>
				<td>
					<div class="success_text">
						<span class="sub_title">Ваш заказ №<?=$arResult["ORDER"]["ACCOUNT_NUMBER"];?></span><span class="date">от <?=$arResult["ORDER"]["DATE_INSERT"];?></span><span class="status">успешно создан</span> 
					</div>
					<span class="block_1">
						<?= GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?>
					</span>
				</td>
			</tr>
		</table>
		<?
		if (!empty($arResult["PAY_SYSTEM"]))
		{
			?>

			<table class="sale_order_full_table table_1">
				<tr>
					<td class="ps_logo">
						<div class="pay_name"><?=GetMessage("SOA_TEMPL_PAY")?></div>
						<?=CFile::ShowImage($arResult["PAY_SYSTEM"]["LOGOTIP"], 100, 100, "border=0", "", false);?>
						<div class="paysystem_name"><?= $arResult["PAY_SYSTEM"]["NAME"] ?></div><br>
					</td>
				</tr>
				<?
				if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0)
				{
					?>
					<tr>
						<td>
							<?
							if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y")
							{
								?>
								<script language="JavaScript">
									window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>');
								</script>
								<?= GetMessage("SOA_TEMPL_PAY_LINK", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))))?>
								<?
								if (CSalePdf::isPdfAvailable() && CSalePaySystemsHelper::isPSActionAffordPdf($arResult['PAY_SYSTEM']['ACTION_FILE']))
								{
									?><br />
									<?= GetMessage("SOA_TEMPL_PAY_PDF", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&pdf=1&DOWNLOAD=Y")) ?>
									<?
								}
							}
							else
							{
								if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"])>0)
								{
									include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
								}
							}
							?>
						</td>
					</tr>
					<?
				}
				?>
			</table>
			<?
		}
	}
	else
	{
		?>
		<b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b>

		<table class="sale_order_full_table table_2">
			<tr>
				<td>
					<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
					<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?>
				</td>
			</tr>
		</table>
		<?
	}
	?>
	<div class="clear"></div>
</div>