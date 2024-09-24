<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $PHOENIX_TEMPLATE_ARRAY;

if ($arParams['SHOW_SUBSCRIBE_PAGE'] !== 'Y')
	LocalRedirect($arParams['SEF_FOLDER']);

/*
if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0)
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);


$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_SUBSCRIBE_NEW"));
*/
require("include/_head.php");

$isAuth = $USER->isAuthorized();
?>

<div class="cabinet-wrap subscribe">
	<div class="container">

		<div class="block-move-to-up">

			<div class="row">

				<?if($isAuth):?>
					<div class="col-lg-3 hidden-md hidden-sm hidden-xs">
						<?CPhoenix::ShowCabinetMenu()?>
					</div>
				<?else:?>

				<?endif;?>

				<div class="

					<?if($isAuth):?>
						<?$APPLICATION->ShowViewContent('class-personal-menu-content');?>
					<?endif;?>

					col-12 pad_top_container">

					<?
						$APPLICATION->IncludeComponent(
							"bitrix:subscribe.edit",
							"main",
							Array(
								"AJAX_MODE" => "N",
								"SHOW_HIDDEN" => "N",
								"ALLOW_ANONYMOUS" => "Y",
								"SHOW_AUTH_LINKS" => "Y",
								"CACHE_TYPE" => "A",
								"CACHE_TIME" => "36000000",
								"SET_TITLE" => "N",
								"AJAX_OPTION_SHADOW" => "Y",
								"AJAX_OPTION_JUMP" => "N",
								"AJAX_OPTION_STYLE" => "Y",
								"AJAX_OPTION_HISTORY" => "N"
							),
						false
						);

						// $APPLICATION->IncludeComponent(
						// 	'bitrix:catalog.product.subscribe.list',
						// 	'',
						// 	array(
						// 		'SET_TITLE' => $arParams['SET_TITLE'],
						// 		'DETAIL_URL' => $arParams['SUBSCRIBE_DETAIL_URL']
						// 	),
						// 	$component
						// );
					?>

				</div>

				<?if($isAuth):?>

					<?$APPLICATION->ShowViewContent('banners-personal-content');?>

				<?endif;?>
			</div>
		</div>
	</div>
</div>