<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

CJSCore::Init(array("popup"));
//$this->addExternalCss("/bitrix/css/main/bootstrap.css");

if (!empty($arResult["errorMessage"]))
{
	if (!is_array($arResult["errorMessage"]))
	{
		ShowError($arResult["errorMessage"]);
	}
	else
	{
		if(!empty($arResult["errorMessage"]))
		{
			foreach ($arResult["errorMessage"] as $errorMessage)
			{
				ShowError($errorMessage);
			}
		}
	}
}
else
{
	if ($arParams['REFRESHED_COMPONENT_MODE'] === 'Y')
	{
		$wrapperId = str_shuffle(substr($arResult['SIGNED_PARAMS'],0,10));
		?>
		<div class="bx-sap" id="bx-sap<?=$wrapperId?>">
			
			<?
			if ($arParams['SELL_VALUES_FROM_VAR'] != 'Y')
			{
				if ($arParams['SELL_SHOW_FIXED_VALUES'] === 'Y')
				{
					?>
			
					<div class="sale-acountpay-block">
						<h3 class="sale-acountpay-title"><?= Loc::getMessage("SAP_FIXED_PAYMENT") ?></h3>
						<div class="sale-acountpay-fixedpay-container">
							<div class="sale-acountpay-fixedpay-list">
								<?

								if(!empty($arParams["SELL_TOTAL"]))
								{
									foreach ($arParams["SELL_TOTAL"] as $valueChanging)
									{
										?>
										<div class="sale-acountpay-fixedpay-item">
											<?=CUtil::JSEscape(htmlspecialcharsbx($valueChanging))?>
										</div>
										<?
									}
								}
								?>
							</div>
						</div>
					</div>
					
					<?
				}
				?>
				
				<div class="sale-acountpay-block form-horizontal">
					<h3 class="sale-acountpay-title"><?=Loc::getMessage("SAP_SUM")?></h3>
					<div class="row align-items-center no-gutters">
							
						<div class='col-md-2 col-6'>
							<input type='text'	placeholder='0.00' 
							class='form-control sale-acountpay-input' value='0.00' name="<?=CUtil::JSEscape(htmlspecialcharsbx($arParams["VAR"]))?>
							<?=($arParams['SELL_USER_INPUT'] === 'N' ? "disabled" :"")?>
							">
						</div>

						<div class='col'>&nbsp;&nbsp;<?=str_replace("~~","&#", str_replace(array("&#", "#"), array("~~", ""), $arResult['FORMATED_CURRENCY']));?></div>
					</div>
				</div>
				
			<?
			}
			else
			{
				if ($arParams['SELL_SHOW_RESULT_SUM'] === 'Y')
				{
					?>
					
					<div class="sale-acountpay-block form-horizontal">
						<h3 class="sale-acountpay-title"><?=Loc::getMessage("SAP_SUM")?></h3>
						<h2><?=SaleFormatCurrency($arResult["SELL_VAR_PRICE_VALUE"], $arParams['SELL_CURRENCY'])?></h2>
					</div>
					<?
				}
				?>
				
				<input type="hidden" name="<?=CUtil::JSEscape(htmlspecialcharsbx($arParams["VAR"]))?>"
						class="form-control input-lg sale-acountpay-input"
						value="<?=CUtil::JSEscape(htmlspecialcharsbx($arResult["SELL_VAR_PRICE_VALUE"]))?>">
				
				<?
			}
			?>
			
			<div class="sale-acountpay-block">
				<h3 class="sale-acountpay-title"><?=Loc::getMessage("SAP_TYPE_PAYMENT_TITLE")?></h3>
				<div>
					<div class="sale-acountpay-pp row">
						
						<?

							if(!empty($arResult['PAYSYSTEMS_LIST']))
							{

								foreach ($arResult['PAYSYSTEMS_LIST'] as $key => $paySystem)
								{
									?>
									<div class="col-lg-2 col-md-4 col-6">
										<div class="sale-acountpay-pp-company <?= ($key == 0) ? 'bx-selected' :""?>">
											<div class="sale-acountpay-pp-company-graf-container">
												<input type="checkbox"
														class="sale-acountpay-pp-company-checkbox"
														name="PAY_SYSTEM_ID"
														value="<?=$paySystem['ID']?>"
														<?= ($key == 0) ? "checked='checked'" :""?>
												>
												<?
												if (isset($paySystem['LOGOTIP']))
												{
													?>
													<div class="sale-acountpay-pp-company-image"
														style="
															background-image: url(<?=$paySystem['LOGOTIP']?>);
															background-image: -webkit-image-set(url(<?=$paySystem['LOGOTIP']?>) 1x, url(<?=$paySystem['LOGOTIP']?>) 2x);">
													</div>
													<?
												}
												?>
											</div>
											<div class="sale-acountpay-pp-company-smalltitle">
												<?=CUtil::JSEscape(htmlspecialcharsbx($paySystem['NAME']))?>
											</div>
										</div>
									</div>
									<?
								}	
							}
						?>
					
					</div>
				</div>
			</div>

			<a href="" class="button-def little main-color btn sale-account-pay-button"><?=Loc::getMessage("SAP_BUTTON")?></a>
				
			
		</div>
		<?
		$javascriptParams = array(
			"alertMessages" => array("wrongInput" => Loc::getMessage('SAP_ERROR_INPUT')),
			"url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
			"templateFolder" => CUtil::JSEscape($templateFolder),
			"templateName" => $this->__component->GetTemplateName(),
			"signedParams" => $arResult['SIGNED_PARAMS'],
			"wrapperId" => $wrapperId
		);
		$javascriptParams = CUtil::PhpToJSObject($javascriptParams);
		?>
		<script>
			var sc = new BX.saleAccountPay(<?=$javascriptParams?>);
		</script>
	<?
	}
	else
	{
		
		?>
		<h3><?=Loc::getMessage("SAP_BUY_MONEY")?></h3>
		<form method="post" name="buyMoney" action="">
			<?

			if(!empty($arResult["AMOUNT_TO_SHOW"]))
			{
				foreach($arResult["AMOUNT_TO_SHOW"] as $value)
				{
					?>
					<input type="radio" name="<?=CUtil::JSEscape(htmlspecialcharsbx($arParams["VAR"]))?>"
						value="<?=$value["ID"]?>" id="<?=CUtil::JSEscape(htmlspecialcharsbx($arParams["VAR"])).$value["ID"]?>">
					<label for="<?=CUtil::JSEscape(htmlspecialcharsbx($arParams["VAR"])).$value["ID"]?>"><?=$value["NAME"]?></label>
					<br />
					<?
				}
			}
			?>
			<input type="submit" name="button" value="<?=GetMessage("SAP_BUTTON")?>">
		</form>
		<?
	}
}

