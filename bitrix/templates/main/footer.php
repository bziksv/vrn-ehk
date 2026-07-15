				<?if(!$is_main){?>
					<?if (!$is_detail && $APPLICATION->GetCurPage() != "/basket/" && !$is_detail_gallery 
						&& strpos($APPLICATION->GetCurPage(),"/personal/")===false 
						&& $APPLICATION->GetCurPage() != "/kontakty/"
						&& $APPLICATION->GetCurPage() != "/kontakty2/"
						&& $APPLICATION->GetCurPage() != "/make-order/")
					{?>
					</div><!-- end: left_col -->
					<div class="right_col">
						<div class="selector_block">
							<div class="man">
								<?if( $pages[1] == "catalog")
								{
									$show_all=true;
									$section_id=false;
									$parent_sections=array();
									if(isset($pages[2]) && !empty($pages[2]))
								   	{
								   		$ar_cur_section=CIBlockSection::GetList(
								   			array("SORT"=>"ASC"),
								   			array("IBLOCK_ID"=>21,"ACTIVE"=>"Y","=CODE"=>$pages[2]),
								   			false
								   		);
								   		if($cur_section=$ar_cur_section->GetNext())
								   		{
								   			$section_id=$cur_section["ID"];
								   			if($cur_section["DEPTH_LEVEL"]!=1)
								   			{
								   				$ar_nav=CIBlockSection::GetNavChain(21, $cur_section["ID"]);
								   				while($nav=$ar_nav->GetNext())
								   				{
								   					$parent_sections[]=$nav["ID"];
								   				}
								   			}
								   			else
							   				{
								   				//$parent_sections[]=$section_id;
							   				}
								   		}
									}
								}
								?>
								
									<? if($section_id == "943" && empty($parent_sections)): ?>
									<div class="item i1">
										<a href="/catalog/elementy-kovki/">
											<span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/man_i1_ico.png" width="100" height="100" alt="" /></span>
											<span class="text">Элементы ковки</span>
										</a>
									</div>
									<? elseif($section_id == "958" && empty($parent_sections)):?>
										<div class="item i2">
											<a href="/catalog/gotovye-izdeliya/">
												<span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/man_i2_ico.png" width="100" height="100" alt="" /></span>
												<span class="text">Готовые изделия</span>
											</a>
										</div>
									<? elseif($pages[1] == "gallery" || $pages[1] =="price"): ?>

									<div class="item i1">
										<a href="/catalog/elementy-kovki/">
											<span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/man_i1_ico.png" width="100" height="100" alt="" /></span>
											<span class="text">Элементы ковки</span>
										</a>
									</div>
									
									<div class="item i2">
										<a href="/catalog/gotovye-izdeliya/">
											<span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/man_i2_ico.png" width="100" height="100" alt="" /></span>
											<span class="text">Готовые изделия</span>
										</a>
									</div>	
									<? endif; ?>
								
								<?
								if($parent_sections && in_array(958, $parent_sections))
								{?>				
									<div class="item i1">
										<a href="/catalog/elementy-kovki/">
											<span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/man_i1_ico.png" width="100" height="100" alt="" /></span>
											<span class="text">Элементы ковки</span>
										</a>
									</div>
									
									<div class="item_content">
										<ul>
											<?if(CModule::IncludeModule("iblock"))
											{
												$arFilter = Array(
											        "IBLOCK_ID"=>21,
											        "ACTIVE"=>"Y",
											        "DEPTH_LEVEL"=>2,
											        "SECTION_ID"=>958
										        );
											   	$group_count=CIBlockSection::GetCount($arFilter);
											   	$sep=ceil($group_count/2);
											   	$ar_sections=CIBlockSection::GetList(
											   		array("SORT"=>"ASC","NAME"=>"ASC"),
											   		$arFilter,
											   		false
											   	);
											   	$count=0;
											   	while($section=$ar_sections->GetNext())
											   	{
											   		$count++;?>
													<li <?if($pages[2] && $pages[2]==$section["CODE"]){?>class="act"<?}?>><a href="<?=$section["SECTION_PAGE_URL"]?>"><?=$section["NAME"]?></a></li>
											   		<?if($count%$sep==0)
											   		{?>
														</ul>
														<ul>
											   		<?}
											   	}
											}?>
										</ul>
										<div class="clear"></div>
									</div>
									<?if($section_id)
									{?>
										<?$APPLICATION->IncludeComponent("bitrix:catalog.smart.filter", "right_filter", array(
											"IBLOCK_TYPE" => "catalog",
											"IBLOCK_ID" => "21",
											"SECTION_ID" => $section_id,
											"FILTER_NAME" => "arrFilter",
											"HIDE_NOT_AVAILABLE" => "N",
											"CACHE_TYPE" => "A",
											"CACHE_TIME" => "36000000",
											"CACHE_GROUPS" => "Y",
											"SAVE_IN_SESSION" => "N",
											"INSTANT_RELOAD" => "N",
											"PRICE_CODE" => array(
												0 => "Реализация Ковка",
											),
											"XML_EXPORT" => "N",
											"SECTION_TITLE" => "-",
											"SECTION_DESCRIPTION" => "-",
											"TEMPLATE_THEME" => "wood"
											),
											false
										);?>
									<?}
								}?>	
								<?if($parent_sections && in_array(943, $parent_sections))
								{?>
									<div class="item i2">
										<a href="/catalog/gotovye-izdeliya/">
											<span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/man_i2_ico.png" width="100" height="100" alt="" /></span>
											<span class="text">Готовые изделия</span>
										</a>
									</div>
									
									<div class="item_content">
										<ul>
											<?if(CModule::IncludeModule("iblock"))
											{
												$arFilter = Array(
											        "IBLOCK_ID"=>21,
											        "ACTIVE"=>"Y",
											        "DEPTH_LEVEL"=>2,
											        "SECTION_ID"=>943
										        );
											   	$group_count=CIBlockSection::GetCount($arFilter);
											   	$sep=ceil($group_count/2);
											   	$ar_sections=CIBlockSection::GetList(
											   		array("SORT"=>"ASC","NAME"=>"ASC"),
											   		$arFilter,
											   		false
											   	);
											   	$count=0;
											   	while($section=$ar_sections->GetNext())
											   	{
											   		$count++;?>
													<li <?if($pages[2] && $pages[2]==$section["CODE"]){?>class="act"<?}?>><a href="<?=$section["SECTION_PAGE_URL"]?>"><?=$section["NAME"]?></a></li>
											   		<?if($count%$sep==0)
											   		{?>
														</ul>
														<ul>
											   		<?}
											   	}
											}?>
										</ul>
										<div class="clear"></div>
									</div>
									<?if($section_id)
									{?>
										<?$APPLICATION->IncludeComponent("bitrix:catalog.smart.filter", "right_filter", array(
											"IBLOCK_TYPE" => "catalog",
											"IBLOCK_ID" => "21",
											"SECTION_ID" => $section_id,
											"FILTER_NAME" => "arrFilter",
											"HIDE_NOT_AVAILABLE" => "N",
											"CACHE_TYPE" => "A",
											"CACHE_TIME" => "36000000",
											"CACHE_GROUPS" => "Y",
											"SAVE_IN_SESSION" => "N",
											"INSTANT_RELOAD" => "N",
											"PRICE_CODE" => array(
												0 => "Реализация Ковка",
											),
											"XML_EXPORT" => "N",
											"SECTION_TITLE" => "-",
											"SECTION_DESCRIPTION" => "-",
											"TEMPLATE_THEME" => "wood"
											),
											false
										);?>
									<?}
								}?>
								
								<?
								if(CModule::IncludeModule("iblock")){
									$arFilter = Array(
										"IBLOCK_ID"=>3,
										"ACTIVE"=>"Y",
										"DEPTH_LEVEL"=>1
									);
									$group_count=CIBlockSection::GetCount($arFilter);
									$section_code=false;
									$section_id=0;
									if(strpos($APPLICATION->GetCurDir(),"/gallery/")!==false)
									{
										$url_arr=explode("/", $APPLICATION->GetCurDir());
										$section_code=$url_arr[2];
									}
								}	
								?>

								<?if( $pages[1] != "gallery" || $section_code)
								{?>
								<div class="item i3">
									<a href="/gallery/"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/man_i3_ico.png" width="100" height="100" alt="" /></span><span class="text">Фотогалерея</span></a>
								</div>
									<div class="item_content">
										<ul>
											<?if(CModule::IncludeModule("iblock"))
											{
											
											   	$sep=ceil($group_count/2);
											   	$ar_sections=CIBlockSection::GetList(
											   		array("SORT"=>"ASC","NAME"=>"ASC"),
											   		$arFilter,
											   		false
											   	);
											   	$count=0;
											   	while($section=$ar_sections->GetNext())
											   	{
											   		$count++;?>
													<li <?if($section_code && $section_code==$section["CODE"]){?>class="act"<?}?>><a href="<?=$section["SECTION_PAGE_URL"]?>"><?=$section["NAME"]?></a></li>
											   		<?if($count%$sep==0)
											   		{?>
														</ul>
														<ul>
											   		<?}
											   	}
											}?>
										</ul>
										<div class="clear"></div>
									</div>
								<?}?>
								<? if($pages[1]!="price"):?>
								<div class="item i4"><a href="/price/"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/man_i4_ico.png" width="100" height="100" alt="" /></span><span class="text">Прайс-лист</span></a></div>
								<? endif; ?>
							</div>
							<div class="clear"></div>
						</div>
						<?
						if( $pages[1] != "news" && $pages[1]!="price"){?>
							<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"main_news", 
	array(
		"IBLOCK_TYPE" => "info",
		"IBLOCK_ID" => "2",
		"NEWS_COUNT" => "2",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ACTIVE_FROM",
		"SORT_ORDER2" => "DESC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>
						<?}elseif($pages[1]!="price"){?>
						<div class="special_items">
							<span class="title">
								Специальные предложения
							</span>
							<div class="spec_carusel">
								<div class="inn">
									<div class="inn">
										<ul class="items menu_type" style="width:2000px;">
											<?if(CModule::IncludeModule("iblock"))
											{
												$ar_items=CIBlockElement::GetList(
													array("RAND"=>"ASC"),
													array("IBLOCK_ID"=>10,"ACTIVE"=>"Y","PROPERTY_SHOW_RIGHT_VALUE"=>"да"),
													false,
													array("nTopCount"=>4),
													array("ID","NAME","DETAIL_PAGE_URL","PREVIEW_PICTURE","PREVIEW_TEXT")
												);
												$count=0;
												while($item=$ar_items->GetNext())
												{
													$count++;?>
													<li>
														<a href="<?=$item["DETAIL_PAGE_URL"]?>" class="image">
															<span>
																<img <?=My::NewResize($item["PREVIEW_PICTURE"],230,170,false);?> alt="<?=$item["NAME"]?>" />
															</span>
														</a>
														<span class="name">
															<a href="<?=$item["DETAIL_PAGE_URL"]?>"><?=$item["NAME"]?></a>
														</span>
														<span class="preview"><?=$item["PREVIEW_TEXT"]?></span>
													</li>
												<?}
											}?>
										</ul>
										<div class="clear"></div>
									</div>
								</div>
								<noindex><nofollow>
								<div class="man">
									<div class="inn">
										<ul class="menu_type">
											<?for($i=1;$i<=$count;$i++)
											{?>
												<li <?if($i==1){?>class="active"<?}?>><a href="#" id="change_<?=$i?>" class="empty">&nbsp;</a></li>
											<?}?>
										</ul>
									</div>
								</div>
								</nofollow></noindex>
								<a href="/specials/" class="all">Все спецпредложения</a>
							</div>
						</div>
						<?}?>
					</div>
					<div class="clear"></div>
				</div><!-- end: page_content-->
					<?}?>
				<?}?>
				<div class="popup add_count">
					<form action="#" class="count_form" method="GET">
						<fieldset>
							<input type="hidden" name="ID" value="0" class="item_id" />
							<div class="title"></div>
							<div class="count_block">
								<span class="label">Кол-во:</span>
								<span class="value">
									<input type="text" value="1" name="quantity" class="number_check" />
									<a class="plus" href="#">+</a>
									<a class="minus" href="#">-</a>
								</span>
								<div class="indicator" title="Много">
									<span style="width:50%;"></span>
								</div>
								<a href="#" class="close empty">&nbsp;</a>
								<div class="clear"></div>
							</div>
							<a href="№" class="add submit">Добавить</a>
						</fieldset>
					</form>
				</div>
			</div><!-- end: content-->
			<div class="footer">	
				<div class="name_address_col">
					<div class="name">
						<div class="t_f_logo"></div>
						<div class="t_f_name">
							<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/bottom_slogan.php", Array(), Array(
							    "MODE"      => "html", 
							    "NAME"      => "слоган снизу", 
							    "TEMPLATE"  => "bottom_slogan.php" 
							    )
							);?>
						</div>
						<div class="clear"></div>
					</div>
					<div class="address">
						<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/bottom_address.php", Array(), Array(
						    "MODE"      => "html", 
						    "NAME"      => "адрес снизу", 
						    "TEMPLATE"  => "bottom_address.php" 
						    )
						);?>
						<span class="phone">
							<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/bottom_phone.php", Array(), Array(
							    "MODE"      => "html", 
							    "NAME"      => "телефон снизу", 
							    "TEMPLATE"  => "bottom_phone.php" 
							    )
							);?>
						</span> 
					</div>
				</div>
				<div class="mail_col">
					По всем вопросам пишите нам на электронную почту:<br />
					<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/bottom_email.php", Array(), Array(
					    "MODE"      => "html", 
					    "NAME"      => "E-mail снизу", 
					    "TEMPLATE"  => "bottom_email.php" 
					    )
					);?>
				</div>
				<div class="col_menu about">
					<?$APPLICATION->IncludeComponent("bitrix:menu", "simple", Array(
						"ROOT_MENU_TYPE" => "bottom_1",	// Тип меню для первого уровня
						"MENU_CACHE_TYPE" => "A",	// Тип кеширования
						"MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
						"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
						"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
						"MAX_LEVEL" => "1",	// Уровень вложенности меню
						"CHILD_MENU_TYPE" => "",	// Тип меню для остальных уровней
						"USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
						"DELAY" => "N",	// Откладывать выполнение шаблона меню
						"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
						),
						false
					);?>
				</div>
				<div class="col_menu catalog">
					<?$APPLICATION->IncludeComponent("bitrix:menu", "simple", Array(
						"ROOT_MENU_TYPE" => "bottom_2",	// Тип меню для первого уровня
						"MENU_CACHE_TYPE" => "A",	// Тип кеширования
						"MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
						"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
						"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
						"MAX_LEVEL" => "1",	// Уровень вложенности меню
						"CHILD_MENU_TYPE" => "",	// Тип меню для остальных уровней
						"USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
						"DELAY" => "N",	// Откладывать выполнение шаблона меню
						"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
						),
						false
					);?>
				</div>
				<div class="col_menu personal">
					<?$APPLICATION->IncludeComponent("bitrix:menu", "simple", Array(
						"ROOT_MENU_TYPE" => "bottom_3",	// Тип меню для первого уровня
						"MENU_CACHE_TYPE" => "A",	// Тип кеширования
						"MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
						"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
						"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
						"MAX_LEVEL" => "1",	// Уровень вложенности меню
						"CHILD_MENU_TYPE" => "",	// Тип меню для остальных уровней
						"USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
						"DELAY" => "N",	// Откладывать выполнение шаблона меню
						"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
						),
						false
					);?>
				</div>
				<div class="clear"></div>
				<div class="copy_block">
					<div class="copy">
						<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/bottom_copy.php", Array(), Array(
						    "MODE"      => "html", 
						    "NAME"      => "копирайты снизу", 
						    "TEMPLATE"  => "bottom_copy.php" 
						    )
						);?>
					</div>
					<div class="social">
						<!--<img src="<?=SITE_TEMPLATE_PATH?>/images/social_block.jpg" width="210" height="32" alt="" />-->
					</div>	
				<div style="display: flex; flex-direction: column;">
					<div style="margin-bottom: 5px;">Мы в социальных сетях:</div>
<div><noindex><a rel="nofollow" target="_blank" href="https://max.ru/u/f9LHodD0cOJXebQ-vEGEzaKEhd_wbMRli-d_Eb96QifeeHYC31JAXl0CV18">
	<img width="35px" src="/upload/medialibrary/c8f/6n02oqr9jbyjfm6a4w8avs21rular8g7/max_logo.png"></a>
	<noindex><a href="https://t.me/POLIMER36" rel="nofollow" target="_blank"><img width="35px" src="/upload/medialibrary/6d3/v3bpi1yugn0q9my0w084z5l4fae6g365/1frjssl3sv9odyyf89clnppvq6jxl3ji.png"></a></noindex>
	<noindex><a href="https://vk.com/polimer36" rel="nofollow" target="_blank"><img width="35px" src="/upload/medialibrary/72f/h3qjlv9cd48bf1j9splcpor6moqnoxb3/0gjxltunca3bxngbbnmkhhsojk19wemp.png"></a></noindex>
</div>
</div>



<div class="payment" style="display: flex; justify-content: flex-end; align-items: center;">
    <img src="<?=SITE_TEMPLATE_PATH?>/images/Uniteller_Visa_MasterCard_234x45.jpg" 
         title="Uniteller_Visa_MasterCard_234x45.jpg" 
         border="0" 
         alt="Uniteller_Visa_MasterCard_234x45.jpg" 
         width="234" 
         height="45" 
         style="margin-right: 10px;">
    <a href="https://prime-ltd.su/?from=vrn-ehk.ru">
        <img src="http://prime-ltd.su/logo/black.svg" 
             title="Продвижение сайтов" 
             alt="Продвижение сайтов" 
             width="170">
    </a>
</div>

					<!--<div class="made_by"><a title="Создание сайта" href="http://www.agrweb.ru/">Создание сайта</a></div>-->




					<div class="clear"></div>
<hr>
					<noindex><p>Наш сайт использует <a target="_blank" href="/upload/cookies.pdf">cookies</a> для обеспечения работоспособности и сбора статистики. С их помощью мы анализируем пользовательскую активность, улучшаем работу сайта и делаем рекламу более релевантной. Оставаясь на сайте, вы даёте согласие на обработку ваших персональных данных. Вы можете отключить сохранение cookies в настройках браузера в любой момент. На сайте также применяются <a target="_blank" href="/upload/rules-recommendation.pdf">рекомендательные технологии</a>. Подробнее об обработке персональных данных — в нашей <a target="_blank" href="/upload/politics.pdf">Политике конфиденциальности.</a></p></noindex>

				</div>
			</div><!-- end: footer-->
		</div><!-- end: main_container-->
		<div id="no_item" class="popup">
			<a class="close empty" href="#">&nbsp;</a>
			<div class="title">Заказ товара</div>
			<form method="GET" class="no_item_form" action="auto_load"></form>
		</div>
		
		<?
        global $USER;
        if (!$USER->IsAuthorized()):
        ?>
        <div id="authorized" class="popup" <? if(isset($_REQUEST['AUTH_FORM']) && $_REQUEST['AUTH_FORM'] === 'Y' && isset($_REQUEST['TYPE']) && $_REQUEST['TYPE'] === 'AUTH'): ?>style="display: block;"<? endif; ?>>
            <a class="close empty" href="#">&nbsp;</a>
            <div class="personal_enter">
                <?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "agr_auth", Array(
                    "REGISTER_URL" => "/personal/",	// Страница регистрации
                    "REGISTER_POPUP" => "/personal/",	// Страница регистрации
                    "FORGOT_PASSWORD_URL" => "/auth/",	// Страница забытого пароля
                    "PROFILE_URL" => "/personal/",	// Страница профиля
                    "SHOW_ERRORS" => "Y",	// Показывать ошибки
                ),
                    false
                );?>
            </div>
        </div>
        <? endif; ?>

		<!-- Yandex.Metrika counter -->
		<script type="text/javascript">
		(function (d, w, c) {
			(w[c] = w[c] || []).push(function() {
				try {
					w.yaCounter29860334 = new Ya.Metrika({id:29860334,
							webvisor:true,
							clickmap:true,
							trackLinks:true,
							accurateTrackBounce:true});
				} catch(e) { }
			});
		
			var n = d.getElementsByTagName("script")[0],
				s = d.createElement("script"),
				f = function () { n.parentNode.insertBefore(s, n); };
			s.type = "text/javascript";
			s.async = true;
			s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
		
			if (w.opera == "[object Opera]") {
				d.addEventListener("DOMContentLoaded", f, false);
			} else { f(); }
		})(document, window, "yandex_metrika_callbacks");
		</script>
		<noscript><div><img src="//mc.yandex.ru/watch/29860334" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->

<!-- чат от Битриксе по просьбе клиента от 06-10-2022 в телеграм Константин -->
<script>
        (function(w,d,u){
                var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
                var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://cdn-ru.bitrix24.ru/b7243579/crm/site_button/loader_7_35t9fk.js');
</script>


<!-- Piwik от Prime -->
<script type="text/javascript">
  var _paq = _paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//piwik.prime-ltd.su/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '3']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Piwik Code -->


<!-- Roistat Counter Start -->
<script>
(function(w, d, s, h, id) {
    w.roistatProjectId = id; w.roistatHost = h; w.roistatPage = d.location.href; w.roistatReferrer = d.referrer;
    var p = d.location.protocol == "https:" ? "https://" : "http://";
    var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/"+id+"/init?referrer="+encodeURIComponent(d.location.href);
    var js = d.createElement(s); js.charset="UTF-8"; js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);
})(window, document, 'script', 'cloud.roistat.com', '1e2efb94c3182618c54f4523b9c8d0a7');
</script>
<!-- Roistat Counter End -->




</body>
</html>