<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");

$cpt = new CCaptcha();
$captchaPass = COption::GetOptionString("main", "captcha_password", "");

if (strlen($captchaPass) <= 0)
{
	$captchaPass = randString(10);
	COption::SetOptionString("main", "captcha_password", $captchaPass);
}

$cpt->SetCodeCrypt($captchaPass);
?>

<?
$ar_cur_user=CUser::GetByID($USER->GetID());

if($cur_user=$ar_cur_user->Fetch())
{
	$phone = $cur_user["PERSONAL_PHONE"];
}?>

<fieldset>
	<?if(isset($_GET["form"]) && !empty($_GET["form"]))
	{
		switch($_GET["form"])
		{
			case "callback_form":?>
				<input name="captcha_code" value="<?=htmlspecialchars($cpt->GetCodeCrypt());?>" type="hidden">
			
				<span class="line">
					<span class="label">Представьтесь: *</span>
					<span class="value"><input type="text" placeholder="Пример: Иван Иванович" name="NAME" class="req" value="<?=$USER->GetFullName()?>"/></span>
				</span>
				<span class="line">
					<span class="label">Ваш e-mail*:</span>
					<span class="value"><input type="text" placeholder="Пример: mail@mail.ru" name="EMAIL" class="req email_check" value="<?=$USER->GetEmail()?>"/></span>
				</span>
				<span class="line">
					<span class="label">Тема сообщения:</span>
					<span class="value"><input type="text" placeholder="Пример: Сотрудничество" name="SUBJECT"/></span>
				</span>
				<span class="line">
					<span class="label">Ваше сообщение:</span>
					<span class="value"><textarea name="MESSAGE" class="req"></textarea></span>
				</span>
				<span class="line">
					<span class="label">CAPTCHA: *</span>
					<span class="value">
						<img src="/bitrix/tools/captcha.php?captcha_code=<?=htmlspecialchars($cpt->GetCodeCrypt());?>">
						<input id="captcha_word" name="captcha_word" class="req" type="text">
					</span>
				</span>
				<div class="check">
				    <input type="checkbox" checked="checked" name="CHEK" class="req" value="Y">
				        <span class="chek">Нажимая на эту кнопку, я даю свое согласие на обработку персональных данных и соглашаюсь с условиями <a target="_blank" href="/upload/politics.pdf">политики обработки персональных данных</a>.</span>
				</div>
				<input type="submit" value="Отправить">
				<?break;
			case "fast_order_form":?>
				<input name="captcha_code" value="<?=htmlspecialchars($cpt->GetCodeCrypt());?>" type="hidden">
				
				<div class="param long_dong">
					<span class="label">Представьтесь: *</span>
					<input type="text" class="req" name="NAME" value="<?=$USER->GetFullName()?>" />
				</div>
				<div class="param first_child">
					<span class="label">E-mail: *</span>
					<input type="text" class="email_check req" name="EMAIL" value="<?=$USER->GetEmail()?>" />
				</div>
				<div class="param">
					<span class="label">Номер телефона: *</span>
					<input type="text" class="phone_check req" name="PHONE" value="<?=$phone?>" />
				</div>
				<span class="line">
					<span class="label">CAPTCHA: *</span>
					<span class="value">
						<img src="/bitrix/tools/captcha.php?captcha_code=<?=htmlspecialchars($cpt->GetCodeCrypt());?>">
						<input id="captcha_word" name="captcha_word" class="req" type="text">
					</span>
				</span>
				<div class="check">
				    <input type="checkbox" checked="checked" name="CHEK" class="req" value="Y">
				        <span class="chek">Нажимая на эту кнопку, я даю свое согласие на обработку персональных данных и соглашаюсь с условиями <a target="_blank" href="/upload/politics.pdf">политики обработки персональных данных</a>.</span>
				</div>
				<input type="submit" value="Отправить">
				<?break;
			case "no_item_form":?>
				<input name="captcha_code" value="<?=htmlspecialchars($cpt->GetCodeCrypt());?>" type="hidden">
				
				<div class="param long_dong">
					<span class="label">Представьтесь: *</span>
					<input type="text" class="req" name="NAME" value="<?=$USER->GetFullName()?>" />
				</div>
				<div class="param first_child">
					<span class="label">E-mail: *</span>
					<input type="text" class="email_check req" name="EMAIL"  value="<?=$USER->GetEmail()?>">
				</div>
				<div class="param">
					<span class="label">Номер телефона: *</span>
					<input type="text" class="phone_check req" name="PHONE" value="<?=$phone?>" />
				</div>
				<input type="hidden" name="ITEM" value="0" class="change_id" />
				<span class="line">
					<span class="label">CAPTCHA: *</span>
					<span class="value">
						<img src="/bitrix/tools/captcha.php?captcha_code=<?=htmlspecialchars($cpt->GetCodeCrypt());?>">
						<input id="captcha_word" name="captcha_word" class="req" type="text">
					</span>
				</span>
				<div class="check">
				    <input type="checkbox" checked="checked" name="CHEK" class="req" value="Y">
					<span class="chek">Нажимая на эту кнопку, я даю свое согласие на обработку персональных данных и соглашаюсь с условиями <a target="_blank" href="/upload/politics.pdf">политики обработки персональных данных</a>.</span>
				</div>
				<input type="submit" value="Отправить">
				<?break;
			default:echo "";
		}
	}?>
</fieldset>