<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!$USER->isAuthorized())
    LocalRedirect(SITE_DIR.'auth/');

use Bitrix\Main\Localization\Loc;

global $PHOENIX_TEMPLATE_ARRAY;

if ($arParams['SHOW_ORDER_PAGE'] !== 'Y')
{
	LocalRedirect($arParams['SEF_FOLDER']);
}
elseif ($arParams['ORDER_DISALLOW_CANCEL'] === 'Y')
{
	LocalRedirect($arResult['PATH_TO_ORDERS']);
}
if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0)
{
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_ORDERS"), $arResult['PATH_TO_ORDERS']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_ORDER_DETAIL", array("#ID#" => $arResult["VARIABLES"]["ID"])));

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

				<?
					$APPLICATION->IncludeComponent(
						"bitrix:sale.personal.order.cancel",
						"",
						array(
							"PATH_TO_LIST" => $arResult["PATH_TO_ORDERS"],
							"PATH_TO_DETAIL" => $arResult["PATH_TO_ORDER_DETAIL"],
							"SET_TITLE" =>$arParams["SET_TITLE"],
							"ID" => $arResult["VARIABLES"]["ID"],
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
