<input type="hidden" name="linked_state" id="linked_state" value="<?if($bLinked) echo 'Y'; else echo 'N';?>">
<input type="hidden" name="Update" value="Y">
<input type="hidden" name="from" value="<?echo htmlspecialcharsbx($from)?>">
<input type="hidden" name="WF" value="<?echo htmlspecialcharsbx($WF)?>">
<input type="hidden" name="return_url" value="<?echo htmlspecialcharsbx($return_url)?>">
<?if($ID>0 && !$bCopy):?>
	<input type="hidden" name="ID" value="<?echo $ID?>">
<?endif;?>

<?if ($bCopy):?><input type="hidden" name="copyID" value="<? echo $ID; ?>">

<?elseif ($copyID > 0):?>
	<input type="hidden" name="copyID" value="<? echo $copyID; ?>">
<?endif;?>

<?if ($bCatalog):?>
	<?CCatalogAdminTools::showFormParams();?>
<?endif;?>
<input type="hidden" name="IBLOCK_SECTION_ID" value="<?echo intval($IBLOCK_SECTION_ID)?>">
<input type="hidden" name="TMP_ID" value="<?echo intval($TMP_ID)?>">