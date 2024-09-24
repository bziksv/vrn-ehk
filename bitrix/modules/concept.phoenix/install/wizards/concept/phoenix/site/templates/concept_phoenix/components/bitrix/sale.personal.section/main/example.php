<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!$USER->isAuthorized())
    LocalRedirect(SITE_DIR.'auth/');
    
use Bitrix\Main\Localization\Loc;

global $PHOENIX_TEMPLATE_ARRAY;

require("include/_head.php");
?>
<div class="cabinet-wrap orders">
	<div class="container">

		<div class="block-move-to-up">
			<div class="row">

				<div class="col-lg-3 hidden-md hidden-sm hidden-xs">
					<?CPhoenix::ShowCabinetMenu()?>
				</div>
				<div class="<?$APPLICATION->ShowViewContent('class-personal-menu-content');?> col-12 pad_top_container">

					Text here...
				</div>

				<?$APPLICATION->ShowViewContent('banners-personal-content');?>

			</div>
		</div>
	</div>
</div>