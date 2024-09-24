<?
// основная вкладка настроек
$MESS["BX_CATALOG_EXPORT_IBLOCK"] = "Выберите инфоблок для выгрузки:";
$MESS["BX_CATALOG_EXPORT_YANDEX_SITE"] = "Выберите сайт для выгрузки:";
$MESS["BX_CATALOG_EXPORT_YANDEX_COMPANY_NAME"] = "Название компании:";
$MESS["BX_CATALOG_EXPORT_YANDEX_ERR_EMPTY_SITE"] = "Не указан ID сайта для выгрузки";
$MESS["BX_CATALOG_EXPORT_YANDEX_ERR_BAD_SITE"] = "Сайт с указанным ID не найден либо деактивирован";
$MESS["BX_CATALOG_EXPORT_YANDEX_OPTION_CONVERT_TO_UTF"] = "Создать выгрузку в кодировке utf-8:";
$MESS["BX_CATALOG_EXPORT_YANDEX_OPTION_CONVERT_TO_CP1251"] = "Создать выгрузку в кодировке windows-1251:";

$MESS["BX_CATALOG_EXPORT_SET_ID"] = "Артикул в OZON (для товара):";
$MESS["BX_CATALOG_EXPORT_SET_ID_NOTE"] = "Введите код свойства инфоблока товаров. Также можно указать код XML_ID, в этом случае будет использован внешний код. По умолчанию в аритикул подставляется ID товара";
$MESS["BX_CATALOG_EXPORT_SET_OFFER_ID"] = "Артикул в OZON (для торгового предложения):";
$MESS["BX_CATALOG_EXPORT_SET_OFFER_ID_NOTE"] = "Введите код свойства инфоблока торговых предложений. Также можно указать код XML_ID, в этом случае будет использован внешний код. По умолчанию в аритикул подставляется ID торгового предложения";
$MESS["BX_CATALOG_EXPORT_MIN_STOCK"] = "Минимальное разрешенное кол-во на остатке:";
$MESS["BX_CATALOG_EXPORT_MIN_STOCK_NOTE"] = "Если необходимо обнулить на OZON остатки у всех товаров, укажите в поле \"Минимальное разрешенное кол-во на остатке\" большую цифру, например 99999. Таким образом в фиде у всех товаров будет на остатке 0-ль";

$MESS["WBS24.OZONEXPORT.MANUAL_CALL_NOTE"] = "<b>Внимание!</b> В ДЕМО режиме модуля установлено ограничение на выгрузку до 1000 товаров. Для демонстрации корректной работы модуля, при запуске формирования фида ВРУЧНУЮ выгружается не более 1000 товаров. Количество зависит от установленных вами фильтров. Для выгрузки всех товаров настраивайте формирование ФИДа на АГЕНТах или CRON.";
$MESS["WBS24.OZONEXPORT.OPEN_LINK"] = "Открыть ссылку";
$MESS["WBS24.OZONEXPORT.COPY_LINK"] = "Копировать ссылку";
$MESS["WBS24.OZONEXPORT.COPY_LINK_SUCCESS"] = "Скопировано";

// вкладка "Обнуление остатков"
$MESS["CAT_ADM_MISC_EXP_TAB_OFFERS_LOG"] = "Обнуление остатков";
$MESS["CAT_ADM_MISC_EXP_TAB_OFFERS_LOG_TITLE"] = "Настройка обнуления остатков";
$MESS["BX_CATALOG_EXPORT_OFFERS_LOG_ON"] = "Обнулять остатки товаров, которые теперь отсутствуют в фиде:";
$MESS["BX_CATALOG_EXPORT_OFFERS_LOG_LIFETIME"] = "Срок в днях, сколько отсутствующие товары будут выгружатся с нулевыми остатками:";
$MESS["BX_CATALOG_EXPORT_OFFERS_LOG_CLEAN"] = "Очистка кеша выгруженных товаров:";
$MESS["BX_CATALOG_EXPORT_OFFERS_LOG_CLEAN_BUTTON"] = "Очистить";
$MESS["BX_CATALOG_EXPORT_OFFERS_LOG_CLEANED_MESSAGE"] = "Кеш успешно очищен";
$MESS["BX_CATALOG_EXPORT_OFFERS_LOG_NOTE"] =
    "<p>По умолчанию, в ФИД (файл для ОЗОНА) выгружаются только «Активные» товары. Однако бывают ситуации при которых выгруженный в ОЗОН товар стал деактивированным. Например: На сайте Доступное количество товара (остаток) 5 штук. Далее товар был деактивирован (причины могут быть разные: продали на сайте, обнулили и сразу деактивировали по прайсам поставщика и т.д.). Т.к. по умолчанию в ФИД выгружаются только активные товары, то он не попадает в выгрузку, а значит на ОЗОНе не обнуляется.</p>"
    ."<p>Данная вкладка используется для таких случаев. Вам достаточно установить галочку и указать количество дней (рекомендуем от 1-3). Это необходимо для того, чтобы отслеживать деактивированные товары и обнулять их на ОЗОН.<br>"
    ."Например (включаем режим обнуления остатков): На сайте Доступное количество товара (остаток) 5 штук. При очередной выгрузке модуль запоминает выгруженные товары. Далее товар был деактивирован. При следующей выгрузке ФИДа в него попадет деактивированный товар с остатком 0. Результат: на ОЗОН его остаток обнулится.</p>"
    ."<p>ВАЖНО! Если вы проводите эксперименты с выгрузкой и меняете разделы для выгрузки и другие параметры, то после завершения тестирования воспользуйтесь функцией «Очистка кеша выгруженных товаров», чтобы удалить всю историю ваших экспериментов.</p>"
;

// вкладка "Ценообразование"
$MESS["BX_CATALOG_EXPORT_COMMONPRICE_SUBTITLE"] = "Общие настройки цены";
$MESS["BX_CATALOG_EXPORT_IGNORE_SALE"] = "Игнорировать скидки (выгружать полную цену):";
$MESS["BX_CATALOG_EXPORT_IGNORE_SALE_NOTE"] = "Если стоит галочка, то при использовании Ценообразования используется цена без скидки. То есть игнорируются скидки из раздела \"Маркетинг\"";

$MESS["WBS24_OZONEXPORT_FORMULA_SUBTITLE"] = "Ценообразование по формуле <sup style='color: red;'>NEW</sup>";
$MESS["WBS24_OZONEXPORT_FORMULA_ON"] = "Использовать ценообразование по формуле:";
$MESS["WBS24_OZONEXPORT_FORMULA_PRICE"] = "<b>Цена со скидкой (price):</b>";
$MESS["WBS24_OZONEXPORT_FORMULA_OLDPRICE"] = "<b>Цена без скидки (oldprice)</b>";
$MESS["WBS24_OZONEXPORT_FORMULA_OLDPRICE_BEFORE_10K"] = "для товаров до 10 000 руб.:";
$MESS["WBS24_OZONEXPORT_FORMULA_OLDPRICE_BEFORE_10K_NOTE"] = "Требование ОЗОН: \"Если текущая цена от 400 до 10 000 рублей включительно, разница между текущей ценой и ценой до скидки должна быть больше 5%\"";
$MESS["WBS24_OZONEXPORT_FORMULA_OLDPRICE_AFTER_10K"] = "для товаров свыше 10 000 руб.:";
$MESS["WBS24_OZONEXPORT_FORMULA_OLDPRICE_AFTER_10K_NOTE"] = "Требование ОЗОН: \"Если текущая цена выше 10 000 рублей, разница между текущей ценой и ценой до скидки должна быть больше 500 рублей\"";
$MESS["WBS24_OZONEXPORT_FORMULA_MIN_PRICE"] = "<b>Рассчет минимальной цены, для опции ОЗОН \"Автоприменение акций\" (min_price):</b>";
$MESS["WBS24_OZONEXPORT_FORMULA_PREMIUM_PRICE"] = "<b>Цена для покупателей с подпиской <br>Ozon Premium (premium_price):</b>";
$MESS["WBS24_OZONEXPORT_FORMULA_MARK_PRICE"] = "Цена";
$MESS["WBS24_OZONEXPORT_FORMULA_MARK_PRICE_DISCOUNT"] = "Цена со скидкой";
$MESS["WBS24_OZONEXPORT_FORMULA_NOTE"] =
    "<p style='text-align: left;'>
    В формулах можно использовать:<br>
    1) метки цен, которые доступны для выбора под каждой формулой;<br>
    2) арифметические действия * / + - и скобки для указания приоритета ( );<br>
    3) числа, включая дробные (разделителем десятичной дроби является точка).
    </p>"
;

$MESS["BX_CATALOG_EXPORT_EXTPRICE_SUBTITLE"] = "Ценообразование (правила изменения цены)";
$MESS["CAT_ADM_MISC_EXP_TAB_EXTPRICE"] = "Ценообразование";
$MESS["CAT_ADM_MISC_EXP_TAB_EXTPRICE_TITLE"] = "Расширенное управление ценами";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_ON"] = "Использовать ценообразование:";

$MESS["BX_CATALOG_EXPORT_EXTPRICE_PRICE"] = "Цена со скидкой (price)";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_PRICE_NOTE"] = "Формула: Цена со скидкой (price) = Цена * ( 1 + K% ) + S";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_PLUS_PERCENT"] = "K =";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_PLUS_PERCENT_NOTE"] = "K - наценка (%). Например, если значение 30, это значит плюс 30% к цене";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_PLUS_ADDITIONAL_SUM"] = "S =";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_PLUS_ADDITIONAL_SUM_NOTE"] = "S - надбавка. Обычно используется для включения в цену стоимости доставки";

$MESS["BX_CATALOG_EXPORT_EXTPRICE_OLD_PRICE"] = "Цена без скидки (oldprice)";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_OLD_PRICE_NOTE"] = "Если указано 20, то в ОЗОН будет \"Цена без скидки\" и рядом указано \"Скидка 20%\"";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_OLD_PRICE_PLUS_PERCENT"] = "Размер скидки (для товаров до 10 000 руб.):";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_OLD_PRICE_PLUS_PERCENT_NOTE"] = "Требование ОЗОН: \"Если текущая цена от 400 до 10 000 рублей включительно, разница между текущей ценой и ценой до скидки должна быть больше 5%\"";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_OLD_PRICE_MORE10K_PLUS_PERCENT"] = "Размер скидки (для товаров свыше 10 000 руб.):";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_OLD_PRICE_MORE10K_PLUS_PERCENT_NOTE"] = "Требование ОЗОН: \"Если текущая цена выше 10 000 рублей, разница между текущей ценой и ценой до скидки должна быть больше 500 рублей\"";

$MESS["BX_CATALOG_EXPORT_EXTPRICE_PREMIUM_PRICE"] = "Цена для покупателей с подпиской <br>Ozon Premium (premium_price)";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_PREMIUM_PRICE_MINUS_PERCENT"] = "Размер скидки:";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_PREMIUM_PRICE_MINUS_PERCENT_NOTE"] = "Если указано 10, то в ОЗОН будет \"Цена для покупателей\" с подпиской Ozon Premium на 10% меньше \"Цены со скидкой\"";

$MESS["BX_CATALOG_EXPORT_EXTPRICE_MIN_PRICE"] = "Рассчет минимальной цены, для опции ОЗОН \"Автоприменение акций\" (min_price)";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_MIN_PRICE_MINUS_PERCENT"] = "Размер скидки:";
$MESS["BX_CATALOG_EXPORT_EXTPRICE_MIN_PRICE_MINUS_PERCENT_NOTE"] = "Например, если указано 10, то в ОЗОН минимальная цена товара при участии в акциях, будет не меньше чем \"Цена со скидкой\" минус 10%";

// вкладка "Склады"
$MESS["CAT_ADM_MISC_EXP_TAB_WAREHOUSE"] = "Склады";
$MESS["CAT_ADM_MISC_EXP_TAB_WAREHOUSE_TITLE"] = "Настройка складов";

$MESS["BX_CATALOG_EXPORT_WAREHOUSE_LABEL"] = "Остатки из доступного количества";
$MESS["BX_CATALOG_EXPORT_WAREHOUSE_DEFAULT_NAME"] = "Название склада в ОЗОН (по умолчанию):";
$MESS["BX_CATALOG_EXPORT_WAREHOUSE_DEFAULT_NAME_NOTE"] = "Если вам необходимо обновлять данные на определенном складе, то введите его название. Посмотрите/скопируйте название склада в аккаунте ОЗОНа и впишите в это поле.<br><font style='color:red'>Настройка не применима при расширенном выводе складов</font>";

$MESS["BX_CATALOG_EXPORT_WAREHOUSE_EXTEND_LABEL"] = "Остатки из складов Битрикс";
$MESS["BX_CATALOG_EXPORT_WAREHOUSE_EXTEND_ON"] = "Использовать расширенный вывод складов:";
$MESS["BX_CATALOG_EXPORT_WAREHOUSE_SUM_ON"] = "Суммировать остатки по складам в один склад ОЗОН:";
$MESS["BX_CATALOG_EXPORT_WAREHOUSE_SUM_NAME"] = "";
$MESS["BX_CATALOG_EXPORT_WAREHOUSE_FILTER_ON"] = "Выборочная выгрузка складов / изменение названий";
$MESS["BX_CATALOG_EXPORT_WAREHOUSE_FILTER_ON_NOTE"] = "Поставьте галочку рядом со складом, с которого необходимо выгружать данные в OZON. При необходимости, впишите название склада используемое на ОЗОНе";
$MESS["BX_CATALOG_EXPORT_WAREHOUSE_ELEMENT_ACTIVE"] = "";
$MESS["BX_CATALOG_EXPORT_WAREHOUSE_ELEMENT_NAME"] = "";

$MESS["WBS24.OZONEXPORT.WAREHOUSE_PROPBASED_SUBTITLE"] = "Остатки из свойств (Простой товар)";
$MESS["WBS24.OZONEXPORT.WAREHOUSE_PROPBASED_ON"] = "Получать остатки из свойств:";
$MESS["WBS24.OZONEXPORT.WAREHOUSE_PROPBASED_PROP"] = "Свойство";
$MESS["WBS24.OZONEXPORT.WAREHOUSE_PROPBASED_STORE_TITLE"] = "Название склада для свойства";
$MESS["WBS24.OZONEXPORT.WAREHOUSE_PROPBASED_WARNING"] = "<span style='color: red;'>Данная настройка работает только для простых товаров. Для товаров с торговыми предложениями получение остатков из свойств на данный момент не доступно</span>";

// вкладка "Ограничения"
$MESS["CAT_ADM_MISC_EXP_TAB_LIMITATIONS"] = "Ограничения по цене";
$MESS["CAT_ADM_MISC_EXP_TAB_LIMITATIONS_TITLE"] = "Настройка ограничений";
$MESS["BX_CATALOG_EXPORT_PRICE_LIMIT_ON"] = "Использовать ограничение по цене";
$MESS["BX_CATALOG_EXPORT_PRICE_LIMIT"] = "Цена товара:";
$MESS["BX_CATALOG_EXPORT_PRICE_LIMIT_MIN"] = "от ";
$MESS["BX_CATALOG_EXPORT_PRICE_LIMIT_MAX"] = " до ";
$MESS["BX_CATALOG_EXPORT_PRICE_LIMIT_NOTE"] = "В выгрузку попадут только товары с указанным диапазоном цены";
$MESS["BX_CATALOG_EXPORT_PRICE_LIMIT_BEFORE_EXTPRICE"] = "Использовать цену до Ценообразования";
$MESS["BX_CATALOG_EXPORT_PRICE_LIMIT_BEFORE_EXTPRICE_NOTE"] = "Если стоит галочка, то используется цена из каталога до применения Ценообразования";

// вкладка "Фильтры"
$MESS["CAT_ADM_MISC_EXP_TAB_FILTER"] = "Фильтры";
$MESS["CAT_ADM_MISC_EXP_TAB_FILTER_TITLE"] = "Настройка фильтров";
$MESS["BX_CATALOG_EXPORT_FILTER_ON_NOTE"] = "<font style='color:red'>Все ограничения, испульзуемые в предыдущих вкладках, имеют наивысший приоритет</font>";
$MESS["BX_CATALOG_EXPORT_FILTER_ON"] = "Использовать фильтры";
?>
