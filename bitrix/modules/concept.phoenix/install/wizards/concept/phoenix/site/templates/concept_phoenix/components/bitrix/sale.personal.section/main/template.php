<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!$USER->isAuthorized())
    LocalRedirect(SITE_DIR.'auth/');

use Bitrix\Main\Localization\Loc;


global $PHOENIX_TEMPLATE_ARRAY;

/*
if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0)
{
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}
$theme = Bitrix\Main\Config\Option::get("main", "wizard_eshop_bootstrap_theme_id", "blue", SITE_ID);
*/



$availablePages = array();

if ($arParams['SHOW_ORDER_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ORDERS'],
		"name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_ORDER_PAGE_NAME"],
		"icon" => '<i class="concept-icon concept-clock"></i>',
		"text" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_ORDERS"]["~VALUE"],
		"bttn_name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_ORDERS_BTN_NAME"]
	);

	$delimeter = ($arParams['SEF_MODE'] === 'Y') ? "?" : "&";
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ORDERS'].$delimeter."filter_history=Y",
		"name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_ORDER_PAGE_HISTORY"],
		"icon" => '<i class="concept-icon concept-th-list-2"></i>',
		"text" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_ORDERS_HISTORY"]["~VALUE"],
		"bttn_name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_ORDERS_HISTORY_BTN_NAME"]
	);
}

if ($arParams['SHOW_ACCOUNT_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"id"=>"account",
		"path" => $arResult['PATH_TO_ACCOUNT'],
		"name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_ACCOUNT_PAGE_NAME"],
		"icon" => '<i class="concept-icon concept-battery"></i>',
		"text" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_ORDERS_ACCOUNT"]["~VALUE"],
		"bttn_name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_ORDERS_ACCOUNT_BTN_NAME"]
	);
}

if ($arParams['SHOW_PRIVATE_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_PRIVATE'],
		"name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_PRIVATE_PAGE_NAME"],
		"icon" => '<i class="concept-icon concept-user-circle-o"></i>',
		"text" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_PRIVATE"]["~VALUE"],
		"bttn_name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_PRIVATE_BTN_NAME"]
	);
}


if ($arParams['SHOW_PROFILE_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_PROFILE'],
		"name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_PROFILE_PAGE_NAME"],
		"icon" => '<i class="concept-icon concept-vcard"></i>',
		"text" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_PROFILE"]["~VALUE"],
		"bttn_name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_PROFILE_BTN_NAME"]
	);
}

if ($arParams['SHOW_BASKET_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arParams['PATH_TO_BASKET'],
		"name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_BASKET_PAGE_NAME"],
		"icon" => '<i class="concept-icon concept-cart"></i>',
		"text" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_BASKET"]["~VALUE"],
		"bttn_name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_BASKET_BTN_NAME"]
	);
}

if ($arParams['SHOW_SUBSCRIBE_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_SUBSCRIBE'],
		"name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_SUBSCRIBE_PAGE_NAME"],
		"icon" => '<i class="concept-icon concept-mail"></i>',
		"text" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_SUBSCRIBE"]["~VALUE"],
		"bttn_name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_SUBSCRIBE_BTN_NAME"]
	);
}



if (!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CUSTOM_PAGES"]["ITEMS"]))
{
	foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CUSTOM_PAGES"]["ITEMS"] as $page)
	{

		$availablePages[] = array(
			"path" => $page['URL'],
			"name" => $page['NAME'],
			"icon" => (strlen($page['ICON'])) ? '<i class="concept '.htmlspecialcharsbx($page['ICON']).'"></i>' : "",
			"text" => $page['TEXT'],
			"bttn_name" => $PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_BASKET_BTN_NAME_DEFAULT"]
		);
	}
}



require("include/_head.php");
?>


<div class="cabinet-wrap">
	<div class="container">

		<div class="block-move-to-up">
			<?
				if (empty($availablePages))
					ShowError(Loc::getMessage("SPS_ERROR_NOT_CHOSEN_ELEMENT"));
				
				else
				{
					?>
					<div class="row">

						<div class="col-lg-3 hidden-md hidden-sm hidden-xs">
							<?CPhoenix::ShowCabinetMenu()?>
						</div>


						<div class="<?$APPLICATION->ShowViewContent('class-personal-menu-content');?> col-12 pad_top_container">

							<div class="personal-menu-content">

								<?foreach ($availablePages as $key => $blockElement){?>

									<?if($key != 0):?>
										<div class="break-line"></div>
									<?endif;?>

									<div class="row item">

										<div class="col-auto wr-pic">
											
											<div class="pic main-color">
												<?=$blockElement['icon']?>
											</div>

										</div>

										<div class="col-md-10 col-9 wr-text">
											
											<a href="<?=htmlspecialcharsbx($blockElement['path'])?>" class="name bold"><?=$blockElement['name']?></a>

											<?if($blockElement["id"]==="account" && $arParams['SHOW_ACCOUNT_COMPONENT'] !== 'N'):?>

												<?$APPLICATION->IncludeComponent(
													"bitrix:sale.personal.account",
													"small",
													Array(
														"SET_TITLE" => "N"
													),
													$component
												);?>
											<?endif;?>

											<div class="text"><?=$blockElement['text']?></div>
											<div class="wr-bttn">
												<a class="button-second text-clr-blue" href="<?=htmlspecialcharsbx($blockElement['path'])?>"><?=htmlspecialcharsbx($blockElement['bttn_name'])?></a>
											</div>

										</div>

									</div>

								<?}?>

							</div>
							
						</div>

						<?$APPLICATION->ShowViewContent('banners-personal-content');?>

					</div>
					<?
				}
			?>
		</div>
	</div>
</div>