<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
?>
<?if($USER->IsAuthorized())
{
	$ar_cur_user=CUser::GetByID($USER->GetID());
	if($cur_user=$ar_cur_user->Fetch())
	{?>
		<div class="personal_page">
			<ul class="pers_pages_menu menu_type">
				<li><a href="/?logout=yes"><span>Выйти</span></a></li>
				<li class="active"><a href="/personal/"><span>Настройки аккаунта</span></a></li>
				<li><a href="/personal/orders/"><span>История заказов</span></a></li>
			</ul>
			<div class="pass_block">
				<form action="#" class="change_pass" method="POST">
					<fieldset>
						<div class="title">Настройки<br/>безопасности<span class="ico"></span></div>
						<div class="line">
							<span class="label">Старый пароль *:</span>
							<span class="value"><input type="password" name="PASSWORD" class="req"></span>
						</div>
						<div class="line">
							<span class="label">Новый пароль *:</span>
							<span class="value"><input type="password" name="NEW_PASSWORD" class="req"></span>
						</div>
						<div class="line">
							<span class="label">Подтверждение пароля *:</span>
							<span class="value"><input type="password" name="CONFIRM_NEW_PASSWORD" class="req"></span>
							<span class="sublabel">Пароль должен содержать<br />минимум 6 символов</span>
						</div>
						<input type="reset" class="cancel" value="Отменить"/>
						<input type="submit" class="send" value="Сохранить"/>
					</fieldset>
				</form>
			</div>
			<div class="blocks">
				<div class="block active">
					<div class="title">
						<a href="#"><span class="ico ico_1"></span>Персональные данные</a>
						<div class="pers_type">
							<div class="item">
								<input type="radio" name="pers_type" checked="checked" id="show_1" value="show_1"/>
								<label for="show_1">Физическое лицо</label>
							</div>
							<div class="item">
								<input type="radio" name="pers_type" id="show_2" value="show_2"/>
								<label for="show_2">Юридическое лицо</label>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="b_content">
						<div class="tabs">
							<div class="tab active" id="tab_show_1">
								<form action="#" method="POST" class="update_user">
									<fieldset>
										<div class="line">
											<span class="label">Фамилия</span>
											<span class="value"><input type="text" value="<?=$cur_user["LAST_NAME"]?>" name="LAST_NAME"></span>
											<div class="clear"></div>
										</div>
										<div class="line">
											<span class="label">Имя *</span>
											<span class="value"><input type="text" value="<?=$cur_user["NAME"]?>" name="NAME" class="req"></span>
											<div class="clear"></div>
										</div>
										<div class="line">
											<span class="label">Отчество</span>
											<span class="value"><input type="text" value="<?=$cur_user["SECOND_NAME"]?>" name="SECOND_NAME"></span>
											<div class="clear"></div>
										</div>
										<div class="line">
											<span class="label">&nbsp;</span>
											<span class="value"><input type="submit" value="Сохранить"></span>
											<div class="clear"></div>
										</div>
									</fieldset>
								</form>
							</div>
							<div class="tab" id="tab_show_2">
								<form action="#" method="POST" class="update_user">
									<fieldset>
										<div class="line">
											<span class="label">Название компании*</span>
											<span class="value"><input type="text" value="<?=htmlspecialchars($cur_user["WORK_COMPANY"])?>" class="req" name="WORK_COMPANY"></span>
											<div class="clear"></div>
										</div>
										<div class="line">
											<span class="label">ИНН *</span>
											<span class="value"><input type="text" value="<?=$cur_user["UF_INN"]?>" name="UF_INN" class="req"></span>
											<div class="clear"></div>
										</div>
										<div class="line">
											<span class="label">КПП *</span>
											<span class="value"><input type="text" value="<?=$cur_user["UF_KPP"]?>" name="UF_KPP" class="req"></span>
											<div class="clear"></div>
										</div>
										<div class="line">
											<span class="label">Юридический адрес</span>
											<span class="value"><input type="text" value="<?=$cur_user["UF_ADDRESS"]?>" name="UF_ADDRESS"></span>
											<div class="clear"></div>
										</div>
										<div class="line">
											<span class="label">Представитель (ФИО)</span>
											<span class="value"><input type="text" value="<?=$cur_user["UF_AGENT"]?>" name="UF_AGENT"></span>
											<div class="clear"></div>
										</div>
										<div class="line">
											<span class="label">&nbsp;</span>
											<span class="value"><input type="submit" value="Сохранить"></span>
											<div class="clear"></div>
										</div>
									</fieldset>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="block">
					<div class="title"><a href="#"><span class="ico ico_2"></span>Контактные данные</a></div>
					<div class="b_content">
						<form action="#" method="POST" class="update_user">
							<fieldset>
								<div class="line">
									<span class="label">E-mail *</span>
									<span class="value"><input type="text" value="<?=$cur_user["EMAIL"]?>" name="EMAIL" class="email_check req"></span>
									<div class="clear"></div>
									<span class="hint">Используется как логин</span>
									<div class="clear"></div>
								</div>
								<div class="line">
									<span class="label">Телефон</span>
									<span class="value"><input type="text" value="<?=$cur_user["PERSONAL_PHONE"]?>" name="PERSONAL_PHONE"></span>
									<div class="clear"></div>
								</div>
								<div class="line">
									<span class="label">&nbsp;</span>
									<span class="value"><input type="submit" value="Сохранить"></span>
									<div class="clear"></div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
				<div class="block">
					<div class="title"><a href="#"><span class="ico ico_3"></span>Данные доставки</a></div>
					<div class="b_content">
						<form action="#" method="POST" class="update_user">
							<fieldset>
								<div class="line">
									<span class="label">Населенный пункт</span>
									<span class="value">
										<?$APPLICATION->IncludeComponent("bitrix:sale.ajax.locations", ".default", array(
											"CITY_OUT_LOCATION" => "Y",
											"ALLOW_EMPTY_CITY" => "Y",
											"COUNTRY_INPUT_NAME" => "COUNTRY",
											"REGION_INPUT_NAME" => "REGION",
											"CITY_INPUT_NAME" => "LOCATION",
											"LOCATION_VALUE" => $cur_user["UF_LOCATION"],
											"COUNTRY" => "",
											"ONCITYCHANGE" => "",
											"NAME" => "q"
											),
											false
										);?>
									</span>
									<div class="clear"></div>
								</div>
								<div class="line">
									<span class="label">Адрес</span>
									<span class="value"><input type="text" value="<?=$cur_user["PERSONAL_STREET"]?>" name="PERSONAL_STREET"></span>
									<div class="clear"></div>
								</div>
								<div class="line">
									<span class="label">&nbsp;</span>
									<span class="value"><input type="submit" value="Сохранить"></span>
									<div class="clear"></div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	<?}
}
else
{?>
	<div class="personal_enter">
		<?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "agr_auth", Array(
			"REGISTER_URL" => "/personal/",	// Страница регистрации
			"FORGOT_PASSWORD_URL" => "/auth/",	// Страница забытого пароля
			"PROFILE_URL" => "/personal/",	// Страница профиля
			"SHOW_ERRORS" => "Y",	// Показывать ошибки
			),
			false
		);?>
		
		<?$APPLICATION->IncludeComponent("bitrix:main.register", "reg_page", Array(
			"SHOW_FIELDS" => array("PERSONAL_PHONE", "CHEK"),	// Поля, которые показывать в форме
			"REQUIRED_FIELDS" => "CHEK",	// Поля, обязательные для заполнения
			"AUTH" => "N",	// Автоматически авторизовать пользователей
			"USE_BACKURL" => "N",	// Отправлять пользователя по обратной ссылке, если она есть
			"SUCCESS_PAGE" => "/personal/?confirm=Y",	// Страница окончания регистрации
			"SET_TITLE" => "N",	// Устанавливать заголовок страницы
			"USER_PROPERTY" => "",	// Показывать доп. свойства
			"USER_PROPERTY_NAME" => "",	// Название блока пользовательских свойств
			),
			false
		);?>
		<div class="why">
			<div class="title">
				Преимущества зарегистрированых пользователей
			</div>
			<p>
				Если вы зарегестрированный клиент нашего интернет-магазина, вы получаете массу приятных возможностей:
			</p>
			<ul>
				<li>С помощью личного кабинета на сайте вы сможете отслеживать статус обработки вашего заказа в реальном времени</li>
				<li>При последующих заказах вам не потребуется вновь вводить свои данные и адрес</li>
				<li>Возможность получать новости, касающиеся нашей компании а так-же элементах ковки, планируемых и проведенных выставок</li>
			</ul>
		</div>
		<div class="clear"></div>
	</div>
<?}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>