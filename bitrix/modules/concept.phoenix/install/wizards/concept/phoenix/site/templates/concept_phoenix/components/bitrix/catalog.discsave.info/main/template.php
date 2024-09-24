<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<div class="discsave text-content">
	<h3 style="margin-top: 0;"><? echo GetMessage('BX_CMP_CDI_TPL_MESS_DISCOUNT_SAVE'); ?></h3>
	<?if(empty($arResult)):?>
		<p><?= GetMessage('BX_CMP_CDI_TPL_MESS_NO_DISCOUNT_SAVE');?></p>
	<?else:?>



		<?foreach ($arResult as &$arDiscountSave):?>

			<?
				$value = "";

				if(strlen($arDiscountSave["VALUE"])>0)
				{

					if ('P' == $arDiscountSave['VALUE_TYPE'])
					{
						$value = $arDiscountSave['VALUE']."&nbsp;%" ?><?
					}
					else
					{
						$value = CCurrencyLang::CurrencyFormat($arDiscountSave['VALUE'], $arDiscountSave['CURRENCY'], true);
					}

					if (isset($arDiscountSave['NEXT_LEVEL']) && is_array($arDiscountSave['NEXT_LEVEL']))
					{
						$strNextLevel = '';
						if ('P' == $arDiscountSave['NEXT_LEVEL']['VALUE_TYPE'])
						{
							$strNextLevel = $arDiscountSave['NEXT_LEVEL']['VALUE'].'&nbsp;%';
						}
						else
						{
							$strNextLevel = CCurrencyLang::CurrencyFormat($arDiscountSave['NEXT_LEVEL']['VALUE'], $arDiscountSave['CURRENCY'], true);
						}

						?>
						<? $value .= "&nbsp(".(str_replace(array('#SIZE#', '#SUMM#'), array($strNextLevel, CCurrencyLang::CurrencyFormat(($arDiscountSave['NEXT_LEVEL']['RANGE_FROM'] - $arDiscountSave['RANGE_SUMM']),$arDiscountSave['CURRENCY'], true)), GetMessage('BX_CMP_CDI_TPL_MESS_NEXT_LEVEL'))).")"?><?
					}

					$value = GetMessage('BX_CMP_CDI_TPL_MESS_SIZE')."&nbsp;".$value;
				}
				else
					$value = GetMessage('BX_CMP_CDI_TPL_MESS_USER_NO_DISCOUNT_SAVE');

			?>

			<div class="item"><span class="bold"><?=$arDiscountSave['NAME'];?>:</span>&nbsp;<?= $value?></div>


		<?endforeach;?>

	<?endif;?>


</div>