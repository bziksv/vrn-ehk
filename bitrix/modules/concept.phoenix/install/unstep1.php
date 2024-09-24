<?IncludeModuleLangFile(__FILE__);?>

<form action="<?=$APPLICATION->GetCurPage(); ?>">
	<?=bitrix_sessid_post()?>

	<input type="hidden" name="lang" value="<?=LANG;?>">
	<input type="hidden" name="id" value="concept.phoenix">
	<input type="hidden" name="uninstall" value="Y">
	<input type="hidden" name="step" value="2">

	<p><b><?=GetMessage('MOD_UNINST_DELETE');?></b></p>

	<input type="submit" name="inst" value="<?=GetMessage('MOD_UNINST_DEL'); ?>">

</form>