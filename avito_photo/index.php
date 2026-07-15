<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авито картинки");
require_once __DIR__ . '/lib/AvitoPhotoService.php';

global $USER;
if ($USER->IsAdmin()):
	$pending = AvitoPhotoService::countPending();
?>
<p>Ожидают генерации: <?= (int)$pending ?></p>
<p><a href="/avito_photo/updata.php">Обновить картинки (вручную в браузере)</a></p>
<p><a href="/avito_photo/register_agent.php">Агент / cron</a></p>
<br>
<a href="/avito_photo/delet.php">Удалить картинки</a>
<?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
