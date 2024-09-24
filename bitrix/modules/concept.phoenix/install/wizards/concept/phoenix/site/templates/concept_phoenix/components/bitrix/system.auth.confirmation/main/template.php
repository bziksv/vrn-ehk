<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?global $PHOENIX_TEMPLATE_ARRAY;?>
<?//here you can place your own messages
	switch($arResult["MESSAGE_CODE"])
	{
	case "E01":
		?><? //When user not found
		break;
	case "E02":
		?><? //User was successfully authorized after confirmation
		break;
	case "E03":
		?><? //User already confirm his registration
		break;
	case "E04":
		?><? //Missed confirmation code
		break;
	case "E05":
		?><? //Confirmation code provided does not match stored one
		break;
	case "E06":
		?><? //Confirmation was successfull
		break;
	case "E07":
		?><? //Some error occured during confirmation
		break;
	}
?>

	
<?if($arResult["MESSAGE_CODE"] == "E01"):?>
	<?echo $arResult["MESSAGE_TEXT"]?>

<?elseif($arResult["MESSAGE_CODE"] == "E06"):?>

	<?
		if(strlen($arResult["USER"]["ID"])>0)
		{
			$USER->Authorize($arResult["USER"]["ID"]);

			if($USER->IsAuthorized())
			{?>
				<div class="text-content">
					<?echo $arResult["MESSAGE_TEXT"]?>
				</div>

				<script>
					$(document).ready(function()
					{
						setTimeout(
							function()
					        {
					            location.href = "<?=SITE_DIR?>personal/";
					        }, 3000);
					});
				</script>
			<?}
		}
	?>

<?else:?>

	<?if($arResult["SHOW_FORM"]):?>
		<div class="col-lg-4 col-md-6 col-12">
			<form class="form" method="post" action="<?echo $arResult["FORM_ACTION"]?>">
				<input type="hidden" name="<?echo $arParams["USER_ID"]?>" value="<?echo $arResult["USER_ID"]?>" />
				
				<div class="row no-margin">
					<div class="col-12 title-form main1">
						<?echo GetMessage("CT_BSAC_TITLE")?>
					</div>

					<div class="col-12">
		                <div class="input <?=(strlen($arResult["USER"]["LOGIN"])>0)?"in-focus":""?>">
		                    <div class="bg"></div>
		                    <span class="desc"><?echo GetMessage("CT_BSAC_LOGIN")?></span>
		                    <input class='focus-anim require' name="<?echo $arParams["LOGIN"]?>" type="text" value="<?echo $arResult["USER"]["LOGIN"]?>" />
		                </div>
		                <div class="input <?=(strlen($arResult["CONFIRM_CODE"])>0)?"in-focus":""?>">
		                    <div class="bg"></div>
		                    <span class="desc"><?echo GetMessage("CT_BSAC_CONFIRM_CODE")?></span>
		                    <input class='focus-anim require' name="<?echo $arParams["CONFIRM_CODE"]?>" type="text" value="<?echo $arResult["CONFIRM_CODE"]?>"/>
		                </div>
		                <div class="errors"></div>
		            </div>

		            <div class="col-12">
		            	<input class="button-def main-color big <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?>" type="submit" value="<?echo GetMessage("CT_BSAC_CONFIRM")?>" />
		            </div>

					
				</div>
				
			</form>
		</div>
		<div class="col-lg-8 col-md-6 col-12 text-content">
			<?echo $arResult["MESSAGE_TEXT"]?>
		</div>
	<?elseif(!$USER->IsAuthorized()):?>
		<?//$APPLICATION->IncludeComponent("bitrix:system.auth.authorize", "", array());?>
	<?endif?>
<?endif;?>