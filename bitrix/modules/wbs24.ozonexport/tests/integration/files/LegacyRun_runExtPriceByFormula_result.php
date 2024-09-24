<? $disableReferers = false;
if (!isset($_GET["referer1"]) || strlen($_GET["referer1"])<=0) $_GET["referer1"] = "yandext";
$strReferer1 = htmlspecialchars($_GET["referer1"]);
if (!isset($_GET["referer2"]) || strlen($_GET["referer2"]) <= 0) $_GET["referer2"] = "";
$strReferer2 = htmlspecialchars($_GET["referer2"]);
header("Content-Type: text/xml; charset=UTF-8");
echo "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="2021-11-09 23:35">
<shop>
<name>Сантехстрой Групп</name>
<company>Сантехстрой Групп</company>
<url>http://santehstroy</url>
<platform>1C-Bitrix</platform>
<offers>
<offer id="test1">
<price>14850</price>
<oldprice>15593</oldprice>
<min_price>13500</min_price>
<premium_price>13500</premium_price>
<outlets><outlet instock="15"></outlet></outlets>
</offer>
<offer id="212750">
<price>990</price>
<oldprice>1040</oldprice>
<min_price>900</min_price>
<premium_price>900</premium_price>
<outlets><outlet instock="20"></outlet></outlets>
</offer>
<offer id="212754">
<price>550</price>
<oldprice>578</oldprice>
<min_price>500</min_price>
<premium_price>500</premium_price>
<outlets><outlet instock="17"></outlet></outlets>
</offer>
</offers>
</shop>
</yml_catalog>
