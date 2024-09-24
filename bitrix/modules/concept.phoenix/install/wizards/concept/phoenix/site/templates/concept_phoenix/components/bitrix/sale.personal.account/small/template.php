<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="text">
	<?
		if(!empty($arResult["ACCOUNT_LIST"]))
		{
			foreach($arResult["ACCOUNT_LIST"] as $accountValue)
			{
				?>
				<div class="sale-personal-account-wallet-list-item">
					<div class="sale-personal-account-wallet-sum"><?=$accountValue['SUM']?></div>
					<div class="sale-personal-account-wallet-currency">
						<div class="sale-personal-account-wallet-currency-item"><?=$accountValue['CURRENCY']?></div>
						<div class="sale-personal-account-wallet-currency-item"><?=$accountValue["CURRENCY_FULL_NAME"]?></div>
					</div>
				</div>
				<?
			}
		}
	?>
</div>