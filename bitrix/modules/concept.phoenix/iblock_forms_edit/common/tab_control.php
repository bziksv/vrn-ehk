
<?
	if($arTranslit["TRANSLITERATION"] == "Y")
	{
		CJSCore::Init(array('translit'));
		?>
	
			<script type="text/javascript">
			var linked=<?if($bLinked) echo 'true'; else echo 'false';?>;
			function set_linked()
			{
				linked=!linked;

				var name_link = document.getElementById('name_link');
				if(name_link)
				{
					if(linked)
						name_link.src='/bitrix/themes/.default/icons/iblock/link.gif';
					else
						name_link.src='/bitrix/themes/.default/icons/iblock/unlink.gif';
				}
				var code_link = document.getElementById('code_link');
				if(code_link)
				{
					if(linked)
						code_link.src='/bitrix/themes/.default/icons/iblock/link.gif';
					else
						code_link.src='/bitrix/themes/.default/icons/iblock/unlink.gif';
				}
				var linked_state = document.getElementById('linked_state');
				if(linked_state)
				{
					if(linked)
						linked_state.value='Y';
					else
						linked_state.value='N';
				}
			}
			var oldValue = '';
			function transliterate()
			{
				if(linked)
				{
					var from = document.getElementById('NAME');
					var to = document.getElementById('CODE');
					if(from && to && oldValue != from.value)
					{
						BX.translit(from.value, {
							'max_len' : <?echo intval($arTranslit['TRANS_LEN'])?>,
							'change_case' : '<?echo $arTranslit['TRANS_CASE']?>',
							'replace_space' : '<?echo $arTranslit['TRANS_SPACE']?>',
							'replace_other' : '<?echo $arTranslit['TRANS_OTHER']?>',
							'delete_repeat_replace' : <?echo $arTranslit['TRANS_EAT'] == 'Y'? 'true': 'false'?>,
							'use_google' : <?echo $arTranslit['USE_GOOGLE'] == 'Y'? 'true': 'false'?>,
							'callback' : function(result){to.value = result; setTimeout('transliterate()', 250); }
						});
						oldValue = from.value;
					}
					else
					{
						setTimeout('transliterate()', 250);
					}
				}
				else
				{
					setTimeout('transliterate()', 250);
				}
			}
			transliterate();
			</script>
		<?
	}
?>


<script type="text/javascript">
var InheritedPropertiesTemplates = new JCInheritedPropertiesTemplates(
	'<?echo $tabControl->GetName()?>_form',
	'/bitrix/admin/iblock_templates.ajax.php?ENTITY_TYPE=E&IBLOCK_ID=<?echo intval($IBLOCK_ID)?>&ENTITY_ID=<?echo intval($ID)?>'
);
BX.ready(function(){
	setTimeout(function(){
		InheritedPropertiesTemplates.updateInheritedPropertiesTemplates(true);
	}, 1000);
});
</script>