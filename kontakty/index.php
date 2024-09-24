<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Контактные данные");
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
