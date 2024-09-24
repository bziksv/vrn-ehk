<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
 

<?if($arResult["LAST_ID"] > 0 || $arResult["NEXT_ID"] > 0):?>
	<div class="arrows-popup">
		<div class="pos-r">
			<?if($arResult["LAST_ID"] > 0):?>
				<div class="prev-popup call-modal <?=$arParams["CALL"]?>" data-all-id = "<?=$arParams['ALL_ID']?>" data-call-modal="<?=$arResult['LAST_ID']?>"></div>
			<?endif;?>


			<?if($arResult["NEXT_ID"] > 0):?>
			    <div class="next-popup call-modal <?=$arParams["CALL"]?>" data-all-id = "<?=$arParams['ALL_ID']?>" data-call-modal="<?=$arResult["NEXT_ID"]?>"></div>
			<?endif;?>
		</div>
	</div>
<?endif;?>

    
