<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/*if(!$USER->isAuthorized())
    LocalRedirect(SITE_DIR."auth/");*/

use Bitrix\Main\Localization\Loc;

global $PHOENIX_TEMPLATE_ARRAY;

$APPLICATION->SetTitle($PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_REGISTER_SUCCESS_PAGE_TITLE"]);
require("include/_head.php");

?>

<div class="cabinet-wrap reg-success-page">
	<div class="container">

		<div class="block-move-to-up">

			<div class="pad_top_container">
			
				<div class="text-content">

					<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_REGISTER_TEXT_SUCCESS"]?>

				</div>

			</div>

		</div>

	</div>
</div>

<?if(!isset($_GET["REGISTER"])):?>
	<script>
		$(document).ready(function()
		{
			setTimeout(
				function()
		        {
		            location.href = "<?=SITE_DIR?>personal/";
		        }, 3000);
		});
	</script>
<?endif;?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>