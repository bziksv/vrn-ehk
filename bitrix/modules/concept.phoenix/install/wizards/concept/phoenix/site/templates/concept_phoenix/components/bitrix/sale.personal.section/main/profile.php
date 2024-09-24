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
<div class="cabinet-wrap profile">
	<div class="container">

		<div class="block-move-to-up">


			<div class="row">

				<div class="col-lg-3 hidden-md hidden-sm hidden-xs">
					<?CPhoenix::ShowCabinetMenu()?>
				</div>
				<div class="<?$APPLICATION->ShowViewContent('class-personal-menu-content');?> col-12 pad_top_container">
					<?
						$APPLICATION->IncludeComponent(
							"bitrix:sale.personal.profile.list",
							"main",
							array(
								"PATH_TO_DETAIL" => $arResult['PATH_TO_PROFILE_DETAIL'],
								"PATH_TO_DELETE" => $arResult['PATH_TO_PROFILE_DELETE'],
								"PER_PAGE" => $arParams["PROFILES_PER_PAGE"],
								"SET_TITLE" =>$arParams["SET_TITLE"],
							),
							$component
						);
					?>
				</div> 

				<?$APPLICATION->ShowViewContent('banners-personal-content');?>
			</div>
		</div>
	</div>
</div>