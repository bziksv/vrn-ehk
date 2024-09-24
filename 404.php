<?
define('P404', 'YES');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "ЭХК | Страница не найдена");
$APPLICATION->SetTitle("ЭХК | Страница не найдена");
CHTTP::SetStatus("404 Not Found");
?>
<div class="page_404">
	<div class="inn">
		<div class="text_404">
			<div class="title">404</div>
			<div class="text">
				<p><a href="#">Перейти на главную</a></p>
				<p>
					Это означает, что страницу,
					которую вы искали,<br />удалили,
					или ее никогда не было.
				</p>
				<p>
					Но у нас, по прежмену, один из самых<br />
					полных <a href="/catalog/">каталогов</a> кованых изделий.
				</p>
			</div>
		</div>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>