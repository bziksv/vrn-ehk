<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>
<?if($USER->IsAuthorized())
{
	if($APPLICATION->GetCurDir()=="/make-order/")
	{
		LocalRedirect("/make-order/");
	}
	else
	{
		LocalRedirect("/personal/");
	}?>
	<p><?echo GetMessage("MAIN_REGISTER_AUTH")?></p>
<?}
else
{	//echo $_POST['register_submit_button'];
	if (!$_POST['CHEK'] != '' && $_POST['register_submit_button'] == 'Регистрация') {
		$arResult['ERRORS']['POLITIC'] = 'Поле "Политика конфиденциальности" обязательно для заполнения';
	}
	
	$regErrors = [];
	if(count($arResult["ERRORS"]) > 0)
	{	
		foreach ($arResult["ERRORS"] as $key => $error)
			if (intval($key) == 0 && $key !== 0) 
				$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

		$existsError = false;
		foreach ($arResult["ERRORS"] as $error) {
			$text = trim(strip_tags((string)$error));
			if ($text === '') {
				continue;
			}
			if (stripos($text, 'уже существует') !== false) {
				$existsError = true;
				continue;
			}
			$regErrors[] = $text;
		}
		if ($existsError) {
			array_unshift($regErrors, 'Пользователь с таким e-mail уже зарегистрирован. Войдите или укажите другой адрес.');
		}
	}
	?>
	
	<div class="reg">
		<div class="title">Регистрация<span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/auth_2.png" width="35" height="45" alt="Войти"></span></div>
		<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
			<div class="reg-form-error"<?= empty($regErrors) ? ' hidden' : '' ?>><?php
				if (!empty($regErrors)) {
					echo htmlspecialcharsbx(implode("\n", $regErrors));
				}
			?></div>
			<?if($arResult["BACKURL"] <> '')
			{?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?}?>
			<div class="line">
				<span class="label">*E-mail</span>
				<span class="uplabel">Будет ваш Логин</span>
				<span class="value">
					<input type="text" placeholder="username@mail.ru" name="REGISTER[LOGIN]" value="<?=$arResult["VALUES"]["LOGIN"]?>" onkeyup="document.getElementById('login_input').value=this.value;"  onblur="document.getElementById('login_input').value=this.value;" />
					<input type="hidden" name="REGISTER[EMAIL]" value="<?=$arResult["VALUES"]["EMAIL"]?>" id="login_input" />
				</span>
			</div>
			<div class="line">
				<span class="label">*Номер телефона</span>
				<span class="value">
					<input type="text" placeholder="+7-___-___-__-__" name="REGISTER[PERSONAL_PHONE]" class="req phone_check ru_phone_check" value="<?=$arResult["VALUES"]["PERSONAL_PHONE"]?>" />
				</span>
			</div>
			<div class="line">
				<span class="label">Пароль:</span>
				<span class="uplabel">Мин. 6 символов</span>
				<span class="value"><input type="password" name="REGISTER[PASSWORD]" /></span>
			</div>
			<div class="line">
				<span class="label">Подтверждение пароля:</span>
				<span class="value"><input type="password" name="REGISTER[CONFIRM_PASSWORD]" /></span>
			</div>
			<div class="check">
			    <input type="checkbox" checked="checked" name="CHEK" class="req" value="Y">
				<span class="chek">Нажимая на эту кнопку, я даю свое согласие на обработку персональных данных и соглашаюсь с условиями <a target="_blank" href="/upload/politics.pdf">политики обработки персональных данных</a>.</span>
			</div>
			<input type="submit" value="Зарегистрироваться" />
			<input type="hidden" name="register_submit_button" value="<?=GetMessage("AUTH_REGISTER")?>" />
		</form>
		*-поля обязательные для заполнения.
		
	</div>
<?}?>