<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
// После успешного подтверждения по ссылке из письма — сразу логиним,
// чтобы не заставлять пользователя вводить те же данные ещё раз.
if (
	!$USER->IsAuthorized()
	&& isset($arResult["MESSAGE_CODE"])
	&& $arResult["MESSAGE_CODE"] === "E06"
	&& !empty($arResult["USER"]["ID"])
)
{
	$USER->Authorize(intval($arResult["USER"]["ID"]));
	LocalRedirect("/personal/?reg_confirmed=Y");
}
?>

<?//here you can place your own messages
	switch($arResult["MESSAGE_CODE"])
	{
	case "E01":
		?><? //When user not found
		break;
	case "E02":
		?><? //User was successfully authorized after confirmation
		break;
	case "E03":
		?><? //User already confirm his registration
		break;
	case "E04":
		?><? //Missed confirmation code
		break;
	case "E05":
		?><? //Confirmation code provided does not match stored one
		break;
	case "E06":
		?><? //Confirmation was successfull
		break;
	case "E07":
		?><? //Some error occured during confirmation
		break;
	}
?>
<?if($arResult["SHOW_FORM"]):?>
<div class="personal_enter" style="margin:auto;padding:auto;border:none;">

	<div class="auth">
		
		<p style="color:red"><?echo $arResult["MESSAGE_TEXT"]?></p>
		
		<form method="post" action="<?echo $arResult["FORM_ACTION"]?>">
	
			<div class="line">
				<span class="label"><?echo GetMessage("CT_BSAC_LOGIN")?>:</span>
				<span class="value"><input type="text" name="<?echo $arParams["LOGIN"]?>" maxlength="50" value="<?echo $arResult["LOGIN"]?>" size="17" /></span>
			</div>		
			
			<div class="line">
				<span class="label"><?echo GetMessage("CT_BSAC_CONFIRM_CODE")?>:</span>
				<span class="value"><input type="text" name="<?echo $arParams["CONFIRM_CODE"]?>" maxlength="50" value="<?echo $arResult["CONFIRM_CODE"]?>" size="17" /></span>
			</div>		
			
			<div class="line">
				<input type="submit" value="<?echo GetMessage("CT_BSAC_CONFIRM")?>" />
			</div>		
		
			<input type="hidden" name="<?echo $arParams["USER_ID"]?>" value="<?echo $arResult["USER_ID"]?>" />
		</form>
		
	</div>

</div>

<div class="clear"></div>

<?elseif($USER->IsAuthorized()):?>
	<p>Регистрация подтверждена. Добро пожаловать!</p>
	<p><a href="/personal/">Перейти в личный кабинет</a></p>
<?elseif($arResult["MESSAGE_CODE"] === "E03"):?>
	<p>Регистрация уже была подтверждена ранее. <a href="/personal/">Войти</a></p>
<?else:?>
	<p><?echo $arResult["MESSAGE_TEXT"]?></p>
	<p><a href="/personal/">Войти</a></p>
<?endif?>
