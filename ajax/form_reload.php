<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$ar_cur_user=CUser::GetByID($USER->GetID());
if($cur_user=$ar_cur_user->Fetch())
{
	$phone=$cur_user["PERSONAL_PHONE"];
}?>
<fieldset>
	<?if(isset($_GET["form"]) && !empty($_GET["form"]))
	{
		switch($_GET["form"])
		{
			case "callback_form":?>
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
				<div class="check">
				    <input type="checkbox" checked="checked" name="CHEK" class="req" value="Y">
				        <span class="chek">Нажимая на эту кнопку, я даю свое согласие на <a href="/upload/compliance.pdf" target="_blank">обработку персональных данных</a> и соглашаюсь с условиями <a href="/upload/politics.pdf" target="_blank">политики конфиденциальности</a>.</span>
				</div>
				<input type="submit" value="Отправить">
				<?break;
			case "fast_order_form":?>
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
				<div class="check">
				    <input type="checkbox" checked="checked" name="CHEK" class="req" value="Y">
				        <span class="chek">Нажимая на эту кнопку, я даю свое согласие на <a href="/upload/compliance.pdf" target="_blank">обработку персональных данных</a> и соглашаюсь с условиями <a href="/upload/politics.pdf" target="_blank">политики конфиденциальности</a>.</span>
				</div>
				<input type="submit" value="Отправить">
				<?break;
			case "no_item_form":?>
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
				<div class="check">
				    <input type="checkbox" checked="checked" name="CHEK" class="req" value="Y">
				        <span class="chek">Нажимая на эту кнопку, я даю свое согласие на <a href="/upload/compliance.pdf" target="_blank">обработку персональных данных</a> и соглашаюсь с условиями <a href="/upload/politics.pdf" target="_blank">политики конфиденциальности</a>.</span>
				</div>
				<input type="submit" value="Отправить">
				<?break;
			default:echo "";
		}
	}?>
</fieldset>