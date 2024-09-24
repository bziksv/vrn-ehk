<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//***********************************
//status and unsubscription/activation section
//***********************************
?>
<div class="bot-container">
	<form class="status-page" action="<?=$arResult["FORM_ACTION"]?>" method="get">
		<?echo bitrix_sessid_post();?>

		<div class="section-title bold"><?echo GetMessage("subscr_title_status")?></div>

		<div class="row">
			<div class="<?=$leftSideCols?> col-12 left-part">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="data-table">
					<tr valign="top">
						<td nowrap><?echo GetMessage("subscr_conf")?></td>
						<td nowrap class="<?echo ($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y"? "notetext":"errortext")?>"><?echo ($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y"? GetMessage("subscr_yes"):GetMessage("subscr_no"));?></td>
						
					</tr>
					<tr>
						<td nowrap><?echo GetMessage("subscr_act")?></td>
						<td nowrap class="<?echo ($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"? "notetext":"errortext")?>"><?echo ($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"? GetMessage("subscr_yes"):GetMessage("subscr_no"));?></td>
					</tr>
					<tr>
						<td nowrap><?echo GetMessage("adm_id")?></td>
						<td nowrap><?echo $arResult["SUBSCRIPTION"]["ID"];?>&nbsp;</td>
					</tr>
					<tr>
						<td nowrap><?echo GetMessage("subscr_date_add")?></td>
						<td nowrap><?echo $arResult["SUBSCRIPTION"]["DATE_INSERT"];?>&nbsp;</td>
					</tr>
					<tr>
						<td nowrap><?echo GetMessage("subscr_date_upd")?></td>
						<td nowrap><?echo $arResult["SUBSCRIPTION"]["DATE_UPDATE"];?>&nbsp;</td>
					</tr>
					
				</table>
			</div>
			<div class="<?=$rightSideCols?> col-12 right-part text">
				
				<?if($arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y"):?>
					<p><?echo GetMessage("subscr_title_status_note1")?></p>
				<?elseif($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"):?>
					<p><?echo GetMessage("subscr_title_status_note2")?></p>
					<p><?echo GetMessage("subscr_status_note3")?></p>
				<?else:?>
					<p><?echo GetMessage("subscr_status_note4")?></p>
					<p><?echo GetMessage("subscr_status_note5")?></p>
				<?endif;?>
			
			</div>

			<?if($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y"):?>

				<div class="col-12 input-btn">

					<?if($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"):?>
						<input class="button-def main-color active <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?>" type="submit" name="unsubscribe" value="<?echo GetMessage("subscr_unsubscr")?>" />
						<input type="hidden" name="action" value="unsubscribe" />
					<?else:?>
						<input class="button-def main-color active <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?>" type="submit" name="activate" value="<?echo GetMessage("subscr_activate")?>" />
						<input type="hidden" name="action" value="activate" />
					<?endif;?>

				</div>
			
			<?endif;?>
		</div>

		<input class="button-def main-color active <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?>" type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
		
	</form>
</div>
