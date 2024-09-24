<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */


	$APPLICATION->SetTitle(getMessage("SOA_ORDER_COMPLETE"));



?>

<? if (!empty($arResult["ORDER"])): ?>

	<?
		

		$search = array(
            "#ORDER_ID#",
            "#ORDER_DATE#"
        );


        $replace   = array(
            $arResult["ORDER"]["ACCOUNT_NUMBER"],
            '<span class="txt-transform">'.CIBlockFormatProperties::DateFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]["VALUE"], MakeTimeStamp($arResult["ORDER"]["DATE_INSERT"]->toUserTime(), CSite::GetDateFormat())).$arResult["ORDER"]["DATE_INSERT"]->toUserTime()->format(' H:i')."</span>"
        );



        $html = str_replace($search, $replace, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_COMPLITED_MESS"]["~VALUE"]);

    ?>



	<div class="col-12 sale_order_full_table">
	
		<?=$html?>

		<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CABINET"]["VALUE"]["ACTIVE"] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["ORDERS"] == "Y"):?>

			<?/*if (!empty($arResult['ORDER']["PAYMENT_ID"])): ?>
				<?=getMessage("SOA_PAYMENT_SUC", array(
					"#PAYMENT_ID#" => $arResult['PAYMENT'][$arResult['ORDER']["PAYMENT_ID"]]['ACCOUNT_NUMBER']
				))?>
			<? endif */?>
			<? if ($arParams['NO_PERSONAL'] !== 'Y'): ?>
				<br /><br />
				<?=getMessage('SOA_ORDER_SUC1', ['#LINK#' => $arParams['PATH_TO_PERSONAL']])?>
			<? endif; ?>

		<? endif; ?>

		<?unset($_SESSION['SALE_ORDER_ID']);?>

		<div class="payment-block row">

			<?
			if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y')
			{
				if (!empty($arResult["PAYMENT"]))
				{
					foreach ($arResult["PAYMENT"] as $payment)
					{
						if ($payment["PAID"] != 'Y')
						{
							if (!empty($arResult['PAY_SYSTEM_LIST'])
								&& array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
							)
							{
								$arPaySystem = $arResult['PAY_SYSTEM_LIST_BY_PAYMENT_ID'][$payment["ID"]];

								
								if (empty($arPaySystem["ERROR"]))
								{?>

									<div class="col-12">
										<h3 class="pay_name"><?=Loc::getMessage("SOA_PAY")?></h3>
									</div>


									<div class="paysystem-label col-lg-2 col-md-4 col-sm-8 col-12">

											<div class="item">

												<div class="wr-img row align-items-center">
													<div class="col-12">
														<img class="d-block mx-auto img-fluid" src="<?=$arPaySystem["LOGOTIP"]["SRC"]?>" alt="<?=$arPaySystem["NAME"]?>">
													</div>
												</div>

												<div class="name"><?=$arPaySystem["NAME"]?></div>
												
											</div>
											
									</div>

									<div class="col-12 pay-content">

										<? if (strlen($arPaySystem["ACTION_FILE"]) > 0 && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y"): ?>
												
												<?
												$orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
												$paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
												?>
												<script>
													window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
												</script>
												<?=getMessage("SOA_PAY_LINK", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&PAYMENT_ID=".$paymentAccountNumber))?>

												<? if (CSalePdf::isPdfAvailable() && $arPaySystem['IS_AFFORD_PDF']): ?>
												<br/>
													<?=getMessage("SOA_PAY_PDF", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&pdf=1&DOWNLOAD=Y"))?>
												<? endif ?>

										<? else: ?>
											<?=$arPaySystem["BUFFERED_OUTPUT"]?>
										<? endif ?>

									</div>
								<?}
								else
								{
									?>
									<span style="color:red;"><?=getMessage("SOA_ORDER_PS_ERROR")?></span>
									<?
								}
							}
							else
							{
								?>
								<span style="color:red;"><?=getMessage("SOA_ORDER_PS_ERROR")?></span>
								<?
							}
						}
					}
				}
			}
			else
			{
				?>
				<br /><strong><?=$arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR']?></strong>
				<?
			}
		?>
		</div>
	</div>

<? else: ?>

	<b><?=getMessage("SOA_ERROR_ORDER")?></b>
	

	<div class="sale_order_full_table col-12">
	
		<?=getMessage("SOA_ERROR_ORDER_LOST", ["#ORDER_ID#" => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"])])?>
		<?=getMessage("SOA_ERROR_ORDER_LOST1")?>
		
	</div>

<? endif ?>