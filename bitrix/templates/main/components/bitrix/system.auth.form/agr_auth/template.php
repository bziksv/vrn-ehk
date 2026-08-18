<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($arResult["FORM_TYPE"] == "login")
{
	$phoneAuthOn = false;
	if (\Bitrix\Main\Loader::includeModule('prime.phoneauth')) {
		$phoneAuthOn = \Prime\PhoneAuth\Config::isEnabled();
	}
?>

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
		<? if ($phoneAuthOn): ?>
		<div class="prime-phoneauth-tabs" role="tablist">
			<button type="button" class="is-active" data-tab="password" role="tab">По логину</button>
			<button type="button" data-tab="phone" role="tab">По телефону</button>
		</div>
		<? endif; ?>
		<div class="prime-phoneauth-panel is-active" data-panel="password">
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
		</form>
		</div>
		<? if ($phoneAuthOn): ?>
		<div class="prime-phoneauth-panel" data-panel="phone">
			<div class="prime-phoneauth-error" style="display:none"></div>
			<form class="prime-phoneauth-phone-form" action="#" method="post">
				<div class="line">
					<span class="label">Телефон:</span>
					<span class="value"><input type="text" name="PHONE" class="ru_phone_check" placeholder="+7-___-___-__-__" autocomplete="tel" inputmode="tel"></span>
				</div>
				<input type="submit" value="Продолжить">
			</form>
			<div class="prime-phoneauth-wait" style="display:none">
				<p data-role="message"></p>
				<p>Звоните с номера <strong data-role="from-phone"></strong></p>
				<p>Звоните на телефон: <a class="prime-phoneauth-number" data-role="call-number"></a></p>
				<ol class="prime-phoneauth-steps">
					<li>Наберите номер с того телефона, который указали</li>
					<li>Звонок сбросится сам — страница войдёт в аккаунт</li>
				</ol>
				<button type="button" class="prime-phoneauth-test" data-role="test">Я позвонил (тест)</button>
				<button type="button" class="prime-phoneauth-back" data-role="back">Другой способ входа</button>
			</div>
		</div>
		<? endif; ?>
	</div>
<?}?>
