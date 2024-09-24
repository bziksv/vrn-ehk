<? $disableReferers = false;
if (!isset($_GET["referer1"]) || $_GET["referer1"] == "") $_GET["referer1"] = "yandext";
$strReferer1 = htmlspecialchars($_GET["referer1"]);
if (!isset($_GET["referer2"]) || $_GET["referer2"] == "") $_GET["referer2"] = "";
$strReferer2 = htmlspecialchars($_GET["referer2"]);
header("Content-Type: text/xml; charset=windows-1251");
echo "<"."?xml version=\"1.0\" encoding=\"windows-1251\"?".">"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="2023-11-10 16:15">
<shop>
<name>ЭХК | Художественая ковкаvrn</name>
<company>vrn-ehk.ru</company>
<url>http://vrn-ehk.ru</url>
<platform>1C-Bitrix</platform>
<currencies>
<currency id="RUB" rate="1" />
<currency id="USD" rate="32.3" />
<currency id="EUR" rate="43.8" />
<currency id="UAH" rate="3.941" />
</currencies>
<categories>
<category id="943">Готовые изделия</category>
<category id="944" parentId="943">Мебель кованая</category>
<category id="953" parentId="943">Аксессуары кованые</category>
<category id="955" parentId="943">Ящики почтовые</category>
<category id="956" parentId="943">Флюгеры</category>
<category id="954" parentId="943">Сувениры</category>
<category id="957" parentId="943">Цепи</category>
<category id="958">Элементы ковки</category>
<category id="959" parentId="958">Балясины</category>
<category id="1009" parentId="958">Столбы опорные</category>
<category id="960" parentId="958">Виноград</category>
<category id="961" parentId="958">Лоза виноградная</category>
<category id="996" parentId="958">Перила кованые</category>
<category id="1002" parentId="958">Полоса декоративная</category>
<category id="1003" parentId="958">Полоса хомутная</category>
<category id="1004" parentId="958">Профили</category>
<category id="1039" parentId="958">Животные в ковке</category>
<category id="1010" parentId="958">Трубы прокат</category>
<category id="1012" parentId="1010">Труба витая с одним витком</category>
<category id="1011" parentId="1010">Труба витая с двумя витками</category>
<category id="1014" parentId="1010">Труба профильная, прокатанная по ребрам</category>
<category id="1013" parentId="1010">Труба декоративная</category>
<category id="967" parentId="958">Завитки и кольца</category>
<category id="1036" parentId="967">Завитки и кольца из трубы 10*10 мм.  и  15*15 мм.</category>
<category id="971" parentId="967">Завитки и кольцо из квадрата 10*10 мм.</category>
<category id="968" parentId="967">Завитки и кольцо из квадрата 12*12 мм.</category>
<category id="969" parentId="967">Завитки и кольца из полосы 12 мм.</category>
<category id="972" parentId="967">Завитки и кольцо из полосы 14 мм.</category>
<category id="973" parentId="967">Завитки из трубы 10*10 мм.</category>
<category id="970" parentId="967">Завитки и кольца из трубы 15*15 мм.</category>
<category id="974" parentId="967">Завитки из трубы 20*20 мм.</category>
<category id="975" parentId="967">Завитки из трубы 30*30 мм.</category>
<category id="1038" parentId="967">Кольца из трубы ?12 мм., ?14 мм. и ?20 мм.</category>
<category id="997" parentId="958">Пики, наконечники и навершия</category>
<category id="1035" parentId="997">Заглушки пластиковые</category>
<category id="998" parentId="997">Навершия металлические</category>
<category id="999" parentId="997">Наконечники</category>
<category id="1000" parentId="997">Пики</category>
<category id="1019" parentId="958">Шары и полусферы</category>
<category id="1020" parentId="1019">Полусферы</category>
<category id="1022" parentId="1019">Шары пустотелые</category>
<category id="1023" parentId="1019">Шары горячештампованные</category>
<category id="1021" parentId="1019">Шары кованые</category>
<category id="978" parentId="958">Корзинки</category>
<category id="992" parentId="958">Накладки, заклёпки</category>
<category id="993" parentId="992">Накладки штампованные</category>
<category id="1031" parentId="992">Накладки литые и чугунные</category>
<category id="1065" parentId="992">Заклёпки</category>
<category id="1037" parentId="992">Колпачки декоративные</category>
<category id="962" parentId="958">Вставки</category>
<category id="965" parentId="962">Вставки штампованные</category>
<category id="963" parentId="962">Вставки литые</category>
<category id="1001" parentId="958">Подпятники, переходы</category>
<category id="1034" parentId="1001">Подпятники штампованные</category>
<category id="1033" parentId="1001">Подпятники литые</category>
<category id="1032" parentId="1001">Основания к поручню, переходы на трубу</category>
<category id="1015" parentId="958">Цветы металлические</category>
<category id="1018" parentId="1015">Цветы штампованные</category>
<category id="1017" parentId="1015">Цветы литые</category>
<category id="1016" parentId="1015">Цветы кованые</category>
<category id="988" parentId="958">Листья</category>
<category id="991" parentId="988">Листья штампованные</category>
<category id="990" parentId="988">Листья литые</category>
<category id="989" parentId="988">Листья кованые</category>
<category id="1005" parentId="958">Розетки и панели</category>
<category id="1007" parentId="1005">Розетки</category>
<category id="1006" parentId="1005">Панели</category>
<category id="977" parentId="958">Ключницы и крючки</category>
<category id="1008" parentId="958">Ручки для ворот и дверей</category>
<category id="1047" parentId="958">Фурнитура для дверей и мебели</category>
<category id="950" parentId="1047">Мебельные и дверные накладки</category>
<category id="945" parentId="1047">Петли</category>
<category id="1048" parentId="1047">Шпингалеты, засовы и другое</category>
<category id="948" parentId="1047">Цифры</category>
<category id="1024" parentId="958">Элементы для мебели, кронштейны</category>
<category id="976" parentId="958">Каталоги</category>
<category id="979" parentId="958">Краски кузнечные</category>
<category id="1049" parentId="979">Hard Max уретановая</category>
<category id="1046" parentId="979">Dali «Рогнеда»</category>
<category id="987" parentId="979">Certa</category>
<category id="985" parentId="979">Mister Hammer матовая</category>
<category id="986" parentId="979">Mister Hammer молотковый эффект</category>
<category id="983" parentId="979">WS-Plast</category>
<category id="995" parentId="958">Патина</category>
</categories>
<offers>
</offers>
</shop>
</yml_catalog>
