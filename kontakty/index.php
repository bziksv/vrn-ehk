<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Контакты «Элементы художественной ковки» email: info@vrn-ehk.ru. График работы: Пн-Пт 9:00-18:00. Закажите консультацию по кованым изделиям!");
$APPLICATION->SetPageProperty("title", "Контакты ЭХК | Тел: 8 (800) 707-94-91");
$APPLICATION->SetTitle("Контактные данные");
?>
<div class="contacts_page">
    <?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/includes/contact_page.php", Array(), Array(
        "NAME"      => "странцу контактов",
        "TEMPLATE"  => "contact_page.php",
        "SHOW_BORDER" => false
        )
    );?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
