<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/lightbox.css" />
		<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/alertify.core.css" />
		<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/alertify.default.css" />
		<link href="<?=SITE_TEMPLATE_PATH?>/css/selectbox.css" rel="stylesheet" />

        <script src="https://www.google.com/recaptcha/api.js?render=6Le8ZZ4aAAAAAMZUhsxou8BmT27TR4NaAh3HivxN"></script>

		<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery-2.1.1.min.js"></script>
		<script src="<?=SITE_TEMPLATE_PATH?>/js/alertify.min.js" type="text/javascript"></script>
		<script src="<?=SITE_TEMPLATE_PATH?>/js/jcarousellite_1.0.1.pack.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/lightbox.min.js"></script>
		<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.selectbox.min.js"></script>
		<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/scripts.js"></script>
		<script src="/bitrix/templates/main/js/inputmask/jquery.inputmask.js" type="text/javascript"></script>
		<script src="/bitrix/templates/main/js/inputmask/jquery.inputmask.extensions.js" type="text/javascript"></script>

		<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/scroll/image.css" />
		<script src="/bitrix/templates/main/js/jquery.scrollUp.js" type="text/javascript"></script>
		<link href="/favicon.png" rel="icon" type="image/x-icon">
		<link href="/favicon.png" rel="shortcut icon" type="image/x-icon">
		<meta name='yandex-verification' content='4b10aa5c2673e46d'/>
		<meta name="google-site-verification" content="xwvu2CL-Vd_DwvET5CupFoR2J6SOpxyo9_jamweuKfA" />
		<meta name="cmsmagazine" content="4bba8fa61d908c6dea29bdc36d5f8a50" />
		<meta name="google-site-verification" content="zS36U7W4h7czLnhhaM3SwykhTnoSg_OhTBYkRx7YUyM" />


		<title><?$APPLICATION->ShowTitle();?></title>
		<? $APPLICATION->ShowMeta("keywords")   // Вывод мета тега keywords ?>
		<? $APPLICATION->ShowMeta("description") // Вывод мета тега description ?>


<? $APPLICATION->ShowCSS(); // Подключение основных файлов стилей template_styles.css и styles.css ?>
<? $APPLICATION->ShowHeadStrings() // Отображает специальные стили, JavaScript ?>
<? $APPLICATION->ShowHeadScripts() // Вывода служебных скриптов ?>

        <link href="<?=SITE_TEMPLATE_PATH?>/css/mobile.css" rel="stylesheet" media="screen and (max-width: 768px)">
        <link href="<?=SITE_TEMPLATE_PATH?>/css/small-mobile.css" rel="stylesheet" media="screen and (max-width: 485px)">
		<!--[if lt IE 8]><link rel="stylesheet" href="/bitrix/templates/main/css/ie.css" type="text/css" /><![endif]-->

		<!-- Отключение обработки номеров Skype -->
		<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" /><meta content="telephone=no" name="format-detection" /><!-- end: Отключение обработки номеров Skype -->
		<!--<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,100,700,900&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>-->
		<!-- Yandex.Metrika counter -->
		<script type="text/javascript">
		(function (d, w, c) {
		    (w[c] = w[c] || []).push(function() {
		        try {
		            w.yaCounter29264840 = new Ya.Metrika({id:29264840,
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
		<noscript><div><img src="//mc.yandex.ru/watch/29264840" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->
		<meta name='yandex-verification' content='40bdc57da02f6529' />
		<meta name="google-site-verification" content="xwvu2CL-Vd_DwvET5CupFoR2J6SOpxyo9_jamweuKfA" />
	</head>
	<?
		$is_main = $APPLICATION->GetCurDir() == "/";
		$pages = $APPLICATION->GetCurDir();
		$pages = explode('/', $pages);
		$is_detail=false;
		$is_detail_gallery=false;
		if(strpos($APPLICATION->GetCurDir(),"/catalog/")!==false && sizeof($pages)==5)
	   	{
			$is_detail=true;
		}
		//if(strpos($APPLICATION->GetCurDir(),"/gallery/")!==false && sizeof($pages)==4)
	   	if(strpos($APPLICATION->GetCurDir(),"/gallery/")!==false && sizeof($pages)==5)
	   	{
			$is_detail_gallery=true;
		}
	?>
	<body>
		<?$APPLICATION->ShowPanel();?>
		<script>

		$(function(){

			$.scrollUp({
				scrollText: ''
			});

		});
		</script>



		<div class="main_container">
			<div class="header">
				<div class="logo">
					<a href="/" title="Главная страница | ЭХК - кованные изделия, все для ковки">
						<img src="<?=SITE_TEMPLATE_PATH?>/images/t_h_logo.jpg" width="150" height="93" alt="ЭХК - кованные изделия, все для ковки" />
					</a>
				</div>
				<div class="menu_line">
					<?$APPLICATION->IncludeComponent("bitrix:menu", "top_menu_multi", Array(
                            "ROOT_MENU_TYPE" => "top",	// Тип меню для первого уровня
                            "MENU_CACHE_TYPE" => "A",	// Тип кеширования
                            "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
                            "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
                            "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
                            "MAX_LEVEL" => "2",	// Уровень вложенности меню
                            "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
                            "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
                            "DELAY" => "N",	// Откладывать выполнение шаблона меню
                            "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
                            ),
                            false
                        );?>
					<div class="personal_block">
						<noindex><nofollow><a href="/personal/"><?if($USER->IsAuthorized()){echo $USER->GetLogin();}else{echo "Личный кабинет";}?></a></nofollow></noindex>
					</div>
					<div class="basket small_basket">
						<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "top_basket", array(
	"PATH_TO_BASKET" => "/basket/",
	"PATH_TO_PERSONAL" => "/personal/",
	"SHOW_PERSONAL_LINK" => "N",
	"SHOW_NUM_PRODUCTS" => "Y",
	"SHOW_TOTAL_PRICE" => "N",
	"SHOW_PRODUCTS" => "N",
	"POSITION_FIXED" => "N"
	),
	false
);?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="info_line">
					<div class="search_block">
						<?$APPLICATION->IncludeComponent(
	"bitrix:search.title",
	"top_search",
	array(
		"NUM_CATEGORIES" => "3",
		"TOP_COUNT" => "5",
		"ORDER" => "date",
		"USE_LANGUAGE_GUESS" => "N",
		"CHECK_DATES" => "N",
		"SHOW_OTHERS" => "N",
		"PAGE" => "#SITE_DIR#search/",
		"CATEGORY_0_TITLE" => "Каталог",
		"CATEGORY_0" => array(
			0 => "iblock_catalog",
		),
		"CATEGORY_0_iblock_catalog" => array(
			0 => "all",
		),
		"CATEGORY_1_TITLE" => "Информация",
		"CATEGORY_1" => array(
			0 => "iblock_info",
		),
		"CATEGORY_1_iblock_info" => array(
			0 => "10",
			1 => "2",
			2 => "3",
			3 => "8",
		),
		"CATEGORY_2_TITLE" => "Сайт",
		"CATEGORY_2" => array(
			0 => "main",
		),
		"CATEGORY_2_main" => array(
		),
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-search-input",
		"CONTAINER_ID" => "title-search"
	),
	false
);?>
					</div>
					<!-- Старый блок со слоганом
					<div class="slogan">
						<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/slogan.php", Array(), Array(
						    "MODE"      => "html",
						    "NAME"      => "слоган",
						    "TEMPLATE"  => "slogan.php"
						    )
						);?>
					</div>
					-->
					<div class="address">
						<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/address.php", Array(), Array(
						    "MODE"      => "html",
						    "NAME"      => "адрес",
						    "TEMPLATE"  => "address.php"
						    )
						);?>
					</div>
					<div class="phone">
						<span class="number">
							<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/top_phone.php", Array(), Array(
							    "MODE"      => "html",
							    "NAME"      => "телефон сверху",
							    "TEMPLATE"  => "top_phone.php"
							    )
							);?>
						</span>
						<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/top_email.php", Array(), Array(
						    "MODE"      => "html",
						    "NAME"      => "E-mail сверху",
						    "TEMPLATE"  => "top_email.php"
						    )
						);?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
            <!-- end: header-->

            <div class="fixed-header">
                <div class="fixed-menu">
                    <div class="logo">
                        <a href="/" title="Главная страница | ЭХК - кованные изделия, все для ковки" class="logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/t_h_logo.jpg" alt="ЭХК - кованные изделия, все для ковки" />
                        </a>
                    </div>

                   <div class="menu">
                       <?$APPLICATION->IncludeComponent("bitrix:menu", "top_menu_multi", Array(
                           "ROOT_MENU_TYPE" => "top",	// Тип меню для первого уровня
                           "MENU_CACHE_TYPE" => "A",	// Тип кеширования
                           "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
                           "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
                           "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
                           "MAX_LEVEL" => "2",	// Уровень вложенности меню
                           "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
                           "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
                           "DELAY" => "N",	// Откладывать выполнение шаблона меню
                           "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
                       ),
                           false
                       );?>
                   </div>

                    <div class="phone">
                        <?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/top_phone.php", Array(), Array(
                                "MODE"      => "html",
                                "NAME"      => "телефон сверху",
                                "TEMPLATE"  => "top_phone.php"
                            )
                        );?>
                    </div>

                    <div class="search">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:search.title",
                            "top_search",
                            array(
                                "NUM_CATEGORIES" => "3",
                                "TOP_COUNT" => "5",
                                "ORDER" => "date",
                                "USE_LANGUAGE_GUESS" => "N",
                                "CHECK_DATES" => "N",
                                "SHOW_OTHERS" => "N",
                                "PAGE" => "#SITE_DIR#search/",
                                "CATEGORY_0_TITLE" => "Каталог",
                                "CATEGORY_0" => array(
                                    0 => "iblock_catalog",
                                ),
                                "CATEGORY_0_iblock_catalog" => array(
                                    0 => "all",
                                ),
                                "CATEGORY_1_TITLE" => "Информация",
                                "CATEGORY_1" => array(
                                    0 => "iblock_info",
                                ),
                                "CATEGORY_1_iblock_info" => array(
                                    0 => "10",
                                    1 => "2",
                                    2 => "3",
                                    3 => "8",
                                ),
                                "CATEGORY_2_TITLE" => "Сайт",
                                "CATEGORY_2" => array(
                                    0 => "main",
                                ),
                                "CATEGORY_2_main" => array(
                                ),
                                "SHOW_INPUT" => "Y",
                                "INPUT_ID" => "title-search-input",
                                "CONTAINER_ID" => "title-search"
                            ),
                            false
                        );?>
                    </div>

                    <div class="cart fixed_basket">
                        <img src="/bitrix/templates/main/images/t_h_basket_ico.png" alt="cart">
                        <?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "top_basket", array(
                            "PATH_TO_BASKET" => "/basket/",
                            "PATH_TO_PERSONAL" => "/personal/",
                            "SHOW_PERSONAL_LINK" => "N",
                            "SHOW_NUM_PRODUCTS" => "Y",
                            "SHOW_TOTAL_PRICE" => "N",
                            "SHOW_PRODUCTS" => "N",
                            "POSITION_FIXED" => "N"
                        ),
                            false
                        );?>
                    </div>
                </div>
            </div>

            <div class="mobile-header">
                <div class="menu">
                    <div class="col">
                        <a href="/" title="Главная страница | ЭХК - кованные изделия, все для ковки" class="logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/t_h_logo.jpg" alt="ЭХК - кованные изделия, все для ковки" />
                        </a>
                    </div>
                    <div class="col">
                        <div class="phone">
                            <?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/top_phone_mobile.php", Array(), Array(
                                    "MODE"      => "html",
                                    "NAME"      => "телефон сверху",
                                    "TEMPLATE"  => "top_phone.php"
                                )
                            );?>
                        </div>
                    </div>
                    <div class="col">
                        <div class="cart mobile_basket">
                            <?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "mobile", array(
                                "PATH_TO_BASKET" => "/basket/",
                                "PATH_TO_PERSONAL" => "/personal/",
                                "SHOW_PERSONAL_LINK" => "N",
                                "SHOW_NUM_PRODUCTS" => "Y",
                                "SHOW_TOTAL_PRICE" => "N",
                                "SHOW_PRODUCTS" => "N",
                                "POSITION_FIXED" => "N"
                            ),
                                false
                            );?>
                        </div>
                    </div>
                    <div class="col">
                        <a href="#" class="menu-btn" id="mobile-menu-btn">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/hamburger.png" alt="menu">
                        </a>
                    </div>
                </div>

                <div class="drop-down-menu">

                    <div class="address">
                        <?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/address.php", Array(), Array(
                                "MODE"      => "html",
                                "NAME"      => "адрес",
                                "TEMPLATE"  => "address.php"
                            )
                        );?>
                    </div>
                    <div class="maps">
                        <a href="/kontakty/">Карта проезда</a>
                    </div>
                    <div class="time-work">
                        <p>Часы работы:<br/> 8:00 - 17:00 (пн-пт), 8:30 - 16:30 (сб), воскресенье - выходной</p>
                    </div>
                    <div class="personal">
                        <nofollow>
                            <? if($USER->IsAuthorized()): ?>
                            <p>Личный кабинет</p>
                            <? endif; ?>
                            <a href="/personal/"><?if($USER->IsAuthorized()){echo $USER->GetLogin();}else{echo "Личный кабинет";}?></a>
                        </nofollow>
                    </div>

                    <?$APPLICATION->IncludeComponent("bitrix:menu", "mobile", Array(
                        "ROOT_MENU_TYPE" => "top",	// Тип меню для первого уровня
                        "MENU_CACHE_TYPE" => "A",	// Тип кеширования
                        "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
                        "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
                        "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
                        "MAX_LEVEL" => "2",	// Уровень вложенности меню
                        "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
                        "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
                        "DELAY" => "N",	// Откладывать выполнение шаблона меню
                        "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
                    ),
                        false
                    );?>
                </div>
            </div>

			<div class="content <? if(count($pages) == 4 && in_array('catalog', $pages)):?>section<?endif;?>" <?if(!$is_main){ echo 'id="inner"'; }?>>
				<?if(!$is_main){?>
				<div class="title_block">
					<div class="breadcrumbs">
					<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "common", array(
	"START_FROM" => "0",
	"PATH" => "",
	"SITE_ID" => "s1"
	),
	false
);?>
					</div>
					<?if($pages[1]=="catalog" && sizeof($pages)==4 && $pages[2]!="filter")
					{
						if(CModule::IncludeModule("iblock"))
						{
							$ar_cur_section=CIBlockSection::GetList(
					   			array("SORT"=>"ASC"),
					   			array("IBLOCK_ID"=>21,"ACTIVE"=>"Y","=CODE"=>$pages[2]),
					   			false
					   		);
					   		if($cur_section=$ar_cur_section->GetNext())
					   		{?>
								<div class="page_title"><?=$cur_section["NAME"]?></div>
					   		<?}
						}?>
					<?}
					elseif(sizeof($pages)==4 && ($pages[1]=="news" || $pages[1]=="specials"))
					{?>
						<div class="page_title"><?=$APPLICATION->ShowTitle(false)?></div>
					<?}
					else
					{?>
						<h1><?=$APPLICATION->ShowTitle(false)?></h1>
					<?}?>
				</div>
				<?if(!$is_detail && $APPLICATION->GetCurPage() != "/basket/" && !$is_detail_gallery
					&& strpos($APPLICATION->GetCurPage(),"/personal/")===false
					&& $APPLICATION->GetCurPage() != "/kontakty/"
					&& $APPLICATION->GetCurPage() != "/make-order/")
				{?>
				<div class="page_content">
						<div class="left_col">
						<?}?>
				<?}?>
