<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!$USER->isAuthorized())
    LocalRedirect(SITE_DIR.'auth/');

use Bitrix\Main\Localization\Loc;

global $PHOENIX_TEMPLATE_ARRAY;

/*if ($arParams['SHOW_PROFILE_PAGE'] !== 'Y')
{
	LocalRedirect($arParams['SEF_FOLDER']);
}

if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0)
{
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_PROFILE"));
*/
require("include/_head.php");

?>
<div class="cabinet-wrap profile_detail">
	<div class="container">

		<div class="block-move-to-up">
			<div class="row">

				<div class="col-lg-3 hidden-md hidden-sm hidden-xs">
					<?CPhoenix::ShowCabinetMenu()?>
				</div>

				<div class="<?//$APPLICATION->ShowViewContent('class-personal-menu-content');?> col-lg-9 col-12 pad_top_container">

					<?
						$APPLICATION->IncludeComponent(
							"bitrix:sale.personal.profile.detail",
							"main",
							array(
								"PATH_TO_LIST" => $arResult["PATH_TO_PROFILE"],
								"PATH_TO_DETAIL" => $arResult["PATH_TO_PROFILE_DETAIL"],
								"SET_TITLE" =>$arParams["SET_TITLE"],
								"USE_AJAX_LOCATIONS" => $arParams['USE_AJAX_LOCATIONS_PROFILE'],
								"COMPATIBLE_LOCATION_MODE" => $arParams['COMPATIBLE_LOCATION_MODE_PROFILE'],		
								"ID" => $arResult["VARIABLES"]["ID"],
							),
							$component
						);
					?>
				</div>
				<?//$APPLICATION->ShowViewContent('banners-personal-content');?>
			</div>
		</div>
	</div>
</div>