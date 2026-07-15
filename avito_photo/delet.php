<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $USER;
if ($USER->IsAdmin()):

	$back = true;

	$IBLOCK_ID = 21;



	$arSelect = Array("ID", "NAME", "PROPERTY_avito");
	$arFilter = Array("IBLOCK_ID"=>$IBLOCK_ID, "!PROPERTY_avito"=>false);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>5), $arSelect);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		
		$back = false;

		if($arFields['PROPERTY_AVITO_VALUE']){


			CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('avito' => Array ("VALUE" => array("del" => "Y")))); 


			 echo $arFields["ID"];
			 echo '<br><br><br>';

			 ?>
			<script>
				$( document ).ready(function() {
				  $(window.location).attr('href', '/avito_photo/delet.php');
				});
			</script>

			<?
		}
	}


	if($back):
		?>
		<script>
			$( document ).ready(function() {
			  $(window.location).attr('href', '/avito_photo/');
			});
		</script>
		<?
	endif;
endif;

?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>