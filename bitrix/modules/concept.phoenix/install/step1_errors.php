<?if(!check_bitrix_sessid()) return;?>

<?if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("sale") || !CModule::IncludeModule("currency")|| !CModule::IncludeModule("search")):?>

	<div class="adm-info-message-wrap adm-info-message-red">
		<div class="adm-info-message">
			<div class="adm-info-message-title"><?=GetMessage("WIZ_ERROR_ATTENTION")?></div>
			
			<?if(!CModule::IncludeModule("iblock")):?>
				<?=GetMessage("WIZ_ERROR_IBLOCK")?><br>
			<?endif;?>

			<?if(!CModule::IncludeModule("catalog")):?>
				<?=GetMessage("WIZ_ERROR_CATALOG")?><br>
			<?endif;?>

			<?if(!CModule::IncludeModule("sale")):?>
				<?=GetMessage("WIZ_ERROR_SALE")?><br>
			<?endif;?>

			<?if(!CModule::IncludeModule("currency")):?>
				<?=GetMessage("WIZ_ERROR_CURRENCY")?><br>
			<?endif;?>

			<?if(!CModule::IncludeModule("search")):?>
				<?=GetMessage("WIZ_ERROR_SEARCH")?><br>
			<?endif;?>

			<div class="adm-info-message-icon"></div>
		</div>
	</div>

	<div>
		<h4><?=GetMessage("WIZ_ERROR_INSTALL_NOTE")?></h4>
	</div>

<?endif;?>

