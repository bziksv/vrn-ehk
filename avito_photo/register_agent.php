<?php

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

global $USER, $APPLICATION;

$APPLICATION->SetTitle('Авито картинки — агент');

if (!$USER->IsAdmin()) {
	require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
	return;
}

require_once __DIR__ . '/agent.php';

$message = '';
$messageType = 'info';
$action = isset($_REQUEST['action']) ? (string)$_REQUEST['action'] : '';

if ($action !== '') {
	if (!check_bitrix_sessid()) {
		$message = 'Сессия устарела. Обновите страницу (F5) и нажмите кнопку ещё раз.';
		$messageType = 'error';
	} elseif ($action === 'register') {
		$result = AvitoPhotoAgent::register();
		$message = $result['message'];
		$messageType = $result['ok'] ? 'ok' : 'error';
	} elseif ($action === 'unregister') {
		$result = AvitoPhotoAgent::unregister();
		$message = $result['message'];
		$messageType = 'ok';
	}
}

$agentExists = AvitoPhotoAgent::isRegistered();

?>
<p><a href="/avito_photo/">← Назад</a></p>

<?php if ($message): ?>
<p style="padding:10px; border:1px solid <?= $messageType === 'error' ? '#c00' : '#090' ?>;">
	<?= htmlspecialcharsbx($message) ?>
</p>
<?php endif; ?>

<p>Статус агента: <strong><?= $agentExists ? 'зарегистрирован' : 'не зарегистрирован' ?></strong></p>
<p>Искать в списке агентов: модуль <strong>avito_photo</strong>, функция содержит <strong>AvitoPhotoAgent</strong>.</p>

<form method="post" action="/avito_photo/register_agent.php">
	<?= bitrix_sessid_post() ?>
	<?php if ($agentExists): ?>
		<button type="submit" name="action" value="unregister">Удалить агента</button>
	<?php else: ?>
		<button type="submit" name="action" value="register">Зарегистрировать агента</button>
	<?php endif; ?>
</form>

<h3>Cron без агента</h3>
<p>Можно запускать напрямую из системного cron:</p>
<pre>*/10 * * * * php <?= htmlspecialcharsbx($_SERVER['DOCUMENT_ROOT']) ?>/avito_photo/cron.php 10 >> /var/log/avito_photo.log 2>&amp;1</pre>
<p>Или через HTTP (если настроен доступ):</p>
<pre>*/10 * * * * curl -s "<?= htmlspecialcharsbx((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']) ?>/avito_photo/cron.php?limit=10" >> /var/log/avito_photo.log 2>&amp;1</pre>
<p>Для работы агента Bitrix на cron также нужен стандартный вызов:</p>
<pre>*/5 * * * * php <?= htmlspecialcharsbx($_SERVER['DOCUMENT_ROOT']) ?>/bitrix/modules/main/tools/cron_events.php</pre>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'; ?>
