<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */

if ($arParams["SET_TITLE"] == "Y")
{
	$APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_COMPLETE"));
}

$orderDate = '';
$orderNumber = '';
$paymentNumber = '';
$orderPrice = '';
$orderCurrency = 'RUB';
$hasUnpaidPayment = false;
$paymentBlockHtml = '';
$paymentError = '';

if (!empty($arResult["ORDER"]))
{
	$orderNumber = htmlspecialcharsbx($arResult["ORDER"]["ACCOUNT_NUMBER"]);
	$orderDate = $arResult["ORDER"]["DATE_INSERT"] instanceof \Bitrix\Main\Type\DateTime
		? $arResult["ORDER"]["DATE_INSERT"]->toUserTime()->format('d.m.Y H:i')
		: (string)$arResult["ORDER"]["DATE_INSERT"];

	if (!empty($arResult['ORDER']["PAYMENT_ID"]) && !empty($arResult['PAYMENT'][$arResult['ORDER']["PAYMENT_ID"]]['ACCOUNT_NUMBER']))
	{
		$paymentNumber = htmlspecialcharsbx($arResult['PAYMENT'][$arResult['ORDER']["PAYMENT_ID"]]['ACCOUNT_NUMBER']);
	}

	if (!empty($arResult["ORDER"]["PRICE"]))
	{
		$orderPrice = SaleFormatCurrency($arResult["ORDER"]["PRICE"], $arResult["ORDER"]["CURRENCY"] ?? 'RUB');
		$orderCurrency = (string)($arResult["ORDER"]["CURRENCY"] ?? 'RUB');
	}

	if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y' && !empty($arResult["PAYMENT"]))
	{
		foreach ($arResult["PAYMENT"] as $payment)
		{
			if ($payment["PAID"] === 'Y')
			{
				continue;
			}

			if (
				empty($arResult['PAY_SYSTEM_LIST'])
				|| !array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
			)
			{
				$paymentError = Loc::getMessage("SOA_ORDER_PS_ERROR");
				break;
			}

			$arPaySystem = $arResult['PAY_SYSTEM_LIST_BY_PAYMENT_ID'][$payment["ID"]];

			if (!empty($arPaySystem["ERROR"]))
			{
				$paymentError = Loc::getMessage("SOA_ORDER_PS_ERROR");
				break;
			}

			$hasUnpaidPayment = true;
			$paymentSum = SaleFormatCurrency($payment["SUM"], $payment["CURRENCY"] ?? $orderCurrency);

			ob_start();
			?>
			<div class="order-confirm-pay-card">
				<div class="order-confirm-pay-card__head">
					<div class="order-confirm-pay-card__title"><?=Loc::getMessage("SOA_PAY")?></div>
					<? if (!empty($arPaySystem["LOGOTIP"])): ?>
						<div class="order-confirm-pay-card__logo">
							<?=CFile::ShowImage($arPaySystem["LOGOTIP"], 120, 40, 'border=0', '', false)?>
						</div>
					<? endif; ?>
					<div class="order-confirm-pay-card__ps-name"><?=htmlspecialcharsbx($arPaySystem["NAME"])?></div>
				</div>

				<div class="order-confirm-pay-card__summary">
					<? if ($paymentNumber !== ''): ?>
						<div class="order-confirm-pay-card__row">
							<span>Номер оплаты</span>
							<strong>№<?=$paymentNumber?></strong>
						</div>
					<? endif; ?>
					<div class="order-confirm-pay-card__row order-confirm-pay-card__row_sum">
						<span>Сумма к оплате</span>
						<strong><?=$paymentSum?></strong>
					</div>
				</div>

				<div class="order-confirm-pay-form">
					<? if ($arPaySystem["ACTION_FILE"] <> '' && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y"): ?>
						<?
						$orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
						$paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
						?>
						<script>
							window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
						</script>
						<p class="order-confirm-pay-form__hint">
							<?=Loc::getMessage("SOA_PAY_LINK", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&PAYMENT_ID=".$paymentAccountNumber))?>
						</p>
					<? else: ?>
						<?=$arPaySystem["BUFFERED_OUTPUT"]?>
					<? endif; ?>
				</div>

				<div class="order-confirm-pay-card__cards">
					<span class="order-confirm-pay-card__card-logo">
						<img src="<?=SITE_TEMPLATE_PATH?>/images/Uniteller_Visa_MasterCard_234x45.jpg" alt="Visa, MasterCard, Uniteller">
					</span>
					<span class="order-confirm-pay-card__card-logo">
						<img src="<?=SITE_TEMPLATE_PATH?>/images/payment-mir.svg?v=3" alt="Мир">
					</span>
					<span class="order-confirm-pay-card__card-logo">
						<img src="<?=SITE_TEMPLATE_PATH?>/images/payment-sbp.svg?v=3" alt="СБП">
					</span>
				</div>
			</div>
			<?
			$paymentBlockHtml = ob_get_clean();
			break;
		}
	}
	elseif ($arResult["ORDER"]["IS_ALLOW_PAY"] !== 'Y')
	{
		$paymentError = (string)$arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'];
	}
}
?>

<? if (!empty($arResult["ORDER"])): ?>

<div class="success_order order-confirm">
	<div class="order-confirm__grid">
		<div class="order-confirm-success sale_order_full_table table_0">
			<div class="success_text">
				<span class="sub_title">Ваш заказ №<?=$orderNumber?></span>
				<span class="date">от <?=$orderDate?></span>
				<span class="status">успешно создан</span>
			</div>

			<? if ($paymentNumber !== ''): ?>
				<div class="order-confirm-success__payment-id">
					Номер вашей оплаты: <strong>№<?=$paymentNumber?></strong>
				</div>
			<? endif; ?>

			<? if ($orderPrice !== ''): ?>
				<div class="order-confirm-success__total">
					Сумма заказа: <strong><?=$orderPrice?></strong>
				</div>
			<? endif; ?>

			<? if ($arParams['NO_PERSONAL'] !== 'Y'): ?>
				<div class="block_1">
					<?=Loc::getMessage('SOA_ORDER_SUC1', ['#LINK#' => $arParams['PATH_TO_PERSONAL']])?>
				</div>
			<? endif; ?>
		</div>

		<? if ($hasUnpaidPayment && $paymentBlockHtml !== ''): ?>
			<?=$paymentBlockHtml?>
		<? elseif ($paymentError !== ''): ?>
			<div class="order-confirm-pay-card order-confirm-pay-card_error">
				<div class="order-confirm-pay-card__title"><?=Loc::getMessage("SOA_PAY")?></div>
				<p class="order-confirm-pay-card__error"><?=$paymentError?></p>
			</div>
		<? endif; ?>
	</div>
</div>

<script>
	ym(29264840,'reachGoal','UspeshnoeOformlenie031024143836', {}, function () {
		console.log('запрос UspeshnoeOformlenie031024143836 в Метрику успешно отправлен');
	});
</script>

<? else: ?>

<div class="success_order order-confirm">
	<b><?=Loc::getMessage("SOA_ERROR_ORDER")?></b>
	<div class="order-confirm-pay-card order-confirm-pay-card_error">
		<p>
			<?=Loc::getMessage("SOA_ERROR_ORDER_LOST", ["#ORDER_ID#" => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"])])?>
			<?=Loc::getMessage("SOA_ERROR_ORDER_LOST1")?>
		</p>
	</div>
</div>

<? endif ?>
