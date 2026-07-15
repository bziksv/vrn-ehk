<div class="info_col">
        <div class="ttitle">Работа по городам России</div>
<? if(CModule::IncludeModule("iblock")):?>
		<div class="info_content">
			<table style="margin-bottom: 30px;" class="contacts_list regs" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
				<td class="work-big">
					 Города
				</td>
				<td class="name">
					 Сотрудник
				</td>
				<td class="e-mail">
					 Контакты
				</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="work-big">
					 Астрахань, Белгород, Волгоград, Екатеринбург, Казань, Краснодар, Курск, Ростов-на-Дону, Махачкала, Нальчик, Северная Осетия, Ставрополь, Уфа, Челябинск, Черкесск, Чечня, Элиста.
				</td>
				<td class="name">
					 Ковалёва Мария Николаевна
				</td>
				<td class="e-mail">
	 <!--<a href="tel:+7-920-434-72-19">+7-920-434-72-19</a><br>-->
	 <!--<a href="tel:+7-473-200-89-81">+7 (473) 200-89-81 доб. 238</a><br>-->
	 <br>
	 <a href="mailto:kovaleva@vrn-ehk.ru">kovaleva@vrn-ehk.ru</a>
				</td>
			</tr>
			<tr>
				<td class="work-big">
					 Республика Беларусь, Республика Казахстан, Крымский полуостров, Великий Новгород, Владимир, Иваново, Калуга, Кострома, Москва, Нижний Новгород, Псков, Санкт-Петербург, Саратов, Тверь, Ярославль.
				</td>
				<td class="name">
					 Индоиту Павел Витальевич
				</td>
				<td class="e-mail">
	 <!--<a href="tel:+7-920-431-27-60">+7-920-431-27-60</a><br>-->
	 <!--<a href="tel:+7-473-200-89-81">+7 (473) 200-89-81 доб. 239</a><br>-->
	 <br>
	 <a href="mailto:pavel@vrn-ehk.ru">pavel@vrn-ehk.ru</a>
				</td>
			</tr>
			<tr>
				<td class="work-big">
					 Брянск, Вологда, Ижевск, Иркутск, Кемерово, Киров, Курган, Липецк, Новосибирск, Омск, Оренбург, Орёл, Пенза, Пермь, Рязань, Самара, Саранск, Смоленск, Тамбов, Тула, Тюмень, Ульяновск, Урюпинск.
				</td>
				<td class="name">
					 Алтухова Светлана Леонидовна
				</td>
				<td class="e-mail">
	 <!--<a href="tel:+7-920-431-27-60">+7-903-656-03-05</a><br>-->
	 <!--<a href="tel:+7-473-200-89-81">+7 (473) 200-89-81 доб. 237</a><br>-->
	 <br>
	 <a href="mailto:altuhova@vrn-ehk.ru">altuhova@vrn-ehk.ru</a>
				</td>
			</tr>
			</tbody>
			</table>

			<div class="ttitle">Наши представительства</div>

			<div class="info_content">
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Филиал 1</a></li>
                        <li><a href="#tabs-2">Филиал 2</a></li>
                        <li><a href="#tabs-3">Филиал 3</a></li>
                    </ul>
                    <div id="tabs-1">
                        <?
                        $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH . "/includes/tabs/branch_1_copy.php", Array(), Array(
                            "MODE"      => "html",
                            "NAME"      => "Редактирование включаемой области раздела",
                            "TEMPLATE"  => ""
                        ));
                        ?>
                    </div>
                    <div id="tabs-2">
                        <?
                        $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH . "/includes/tabs/branch_2.php", Array(), Array(
                            "MODE"      => "html",
                            "NAME"      => "Редактирование включаемой области раздела",
                            "TEMPLATE"  => ""
                        ));
                        ?>
                    </div>
                    <div id="tabs-3">
                        <?
                        $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH . "/includes/tabs/branch_3.php", Array(), Array(
                            "MODE"      => "html",
                            "NAME"      => "Редактирование включаемой области раздела",
                            "TEMPLATE"  => ""
                        ));
                        ?>
                    </div>
                </div>
            </div>
		</div>
<? endif; ?>

</div>

<div class="feedback_col">
    <div class="title">Обратная связь</div>
    <form action="auto_load" class="callback_form" method="POST"></form>
</div>

<div class="clear"></div>