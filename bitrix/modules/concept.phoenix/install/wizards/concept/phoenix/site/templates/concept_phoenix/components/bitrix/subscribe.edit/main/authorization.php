<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//*************************************
//show current authorization section
//*************************************
?>

<div class="row no-margin top-container">
	<div class="<?=$leftSideCols?> col-12 left-part">
		
		<?echo GetMessage("adm_auth_user")?>
		<?echo htmlspecialcharsbx($USER->GetFormattedName(false));?> [<?echo htmlspecialcharsbx($USER->GetLogin())?>].
		

	</div>

	<div class="<?=$rightSideCols?> col-12 right-part text">

		<?if($arResult["ID"]==0):?>
			<?echo GetMessage("subscr_auth_logout1")?> <a href="<?echo $arResult["FORM_ACTION"]?>?logout=YES&amp;sf_EMAIL=<?echo $arResult["REQUEST"]["EMAIL"]?><?echo $arResult["REQUEST"]["RUBRICS_PARAM"]?>"><?echo GetMessage("adm_auth_logout")?></a><?echo GetMessage("subscr_auth_logout2")?><br />
		<?else:?>
			<?echo GetMessage("subscr_auth_logout3")?> <a href="<?echo $arResult["FORM_ACTION"]?>?logout=YES&amp;sf_EMAIL=<?echo $arResult["REQUEST"]["EMAIL"]?><?echo $arResult["REQUEST"]["RUBRICS_PARAM"]?>"><?echo GetMessage("adm_auth_logout")?></a><?echo GetMessage("subscr_auth_logout4")?><br />
		<?endif;?>
    </div>
</div>
