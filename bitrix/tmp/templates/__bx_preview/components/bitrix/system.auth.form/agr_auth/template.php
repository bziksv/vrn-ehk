<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($arResult["FORM_TYPE"] == "login")
{?>

	<?if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
		ShowMessage($arResult['ERROR_MESSAGE']);?>
	<div class="auth">
		<div class="title">
		<? if($arParams['REGISTER_POPUP']):?>
			Для добавления товара авторизуйтесь или зарегистрируйтесь 
		<? else: ?>
			Авторизация<span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/auth_1.png" width="35" height="45" alt="Войти"></span>
		<? endif; ?>
		</div>
		<form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
			<?if($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?endif?>
			<?foreach ($arResult["POST"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>
			<input type="hidden" name="AUTH_FORM" value="Y" />
			<input type="hidden" name="TYPE" value="AUTH" />
			<div class="line">
				<span class="label">Логин (e-mail):</span>
				<span class="value"><input type="text" name="USER_LOGIN" placeholder="username@mail.ru" value="<?=$arResult["USER_LOGIN"]?>" /></span>
			</div>
			<div class="line">
				<span class="label">Пароль:</span>
				<span class="value"><input type="password" name="USER_PASSWORD" /></span>
				<? if($arParams['REGISTER_POPUP']):?>
                <span class="sublabel"><a href="<?=$arParams['REGISTER_POPUP']?>">Зарегистрироваться</a></span>
                /
                <? endif; ?>
				<span class="sublabel"><a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>">Забыли пароль?</a></span>
			</div>
			<input type="submit" value="Войти">
		</div>
	</form>
<?}?>