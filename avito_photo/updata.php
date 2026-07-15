<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require_once __DIR__ . '/lib/AvitoPhotoService.php';

global $USER;
if ($USER->IsAdmin()):
	$cleanupFirst = ($_REQUEST['avito'] ?? '') !== 'y';
	$result = AvitoPhotoService::processBatch(1, $cleanupFirst);

	if ($result['processed'] > 0) {
		echo (int)$result['ids'][0];
		echo '<br><br><br>';
	}

	if (!empty($result['errors'])) {
		echo htmlspecialcharsbx(implode('; ', $result['errors']));
		echo '<br><br>';
	}

	if ($result['remaining'] > 0 && empty($result['errors'])) {
		?>
		<script>
			$(document).ready(function() {
				$(window.location).attr('href', '/avito_photo/updata.php?avito=y');
			});
		</script>
		<?
	} else {
		?>
		<script>
			$(document).ready(function() {
				$(window.location).attr('href', '/avito_photo/');
			});
		</script>
		<?
	}
endif;
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
