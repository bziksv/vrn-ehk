<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//*************************************
//show confirmation form
//*************************************
?>
<div class="row no-margin top-container">
	<div class="<?=$leftSideCols?> col-12 left-part">
		<div class="ex-row">
			<form class="form subscribe-edit" action="<?=$arResult["FORM_ACTION"]?>" method="get">

				<div class="row no-margin">

					<div class="col-12">

						<div class="title-form main1">
					        <?echo GetMessage("subscr_title_confirm")?>
					    </div>


					    <div class="input">
				            <div class="bg"></div>
				            <span class="desc"><?=getMessage('subscr_conf_code')?></span>
				            <input class='focus-anim require' name="CONFIRM_CODE" type="text" value = "<?echo $arResult["REQUEST"]["CONFIRM_CODE"];?>" />
				        </div>

				        <?echo GetMessage("subscr_conf_date")?><br/>
						<?echo $arResult["SUBSCRIPTION"]["DATE_CONFIRM"];?>


						<div class="input-btn">
							<div class="row">
								<div class="col-lg-6 col-12">

								<input type="submit" class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?>" name="confirm" value="<?echo GetMessage("subscr_conf_button")?>" />

								</div>
							</div>
							
							
						</div>



					</div>
				</div>



			<input type="hidden" name="ID" value="<?echo $arResult["ID"];?>" />
			<?echo bitrix_sessid_post();?>
			</form>
		</div>
	</div>

	<div class="<?=$rightSideCols?> col-12 right-part text">

    	<?echo GetMessage("subscr_conf_note1")?> <a title="<?echo GetMessage("adm_send_code")?>" href="<?echo $arResult["FORM_ACTION"]?>?ID=<?echo $arResult["ID"]?>&amp;action=sendcode&amp;<?echo bitrix_sessid_get()?>"><?echo GetMessage("subscr_conf_note2")?></a>.

    </div>
</div>
