<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($USER->isAuthorized())
    LocalRedirect(SITE_DIR);

use Bitrix\Main\Localization\Loc;

global $PHOENIX_TEMPLATE_ARRAY;

/*
if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0)
{
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}
*/

$theme = Bitrix\Main\Config\Option::get("main", "wizard_eshop_bootstrap_theme_id", "blue", SITE_ID);


$APPLICATION->SetTitle($PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_FORGOT_PAGE_TITLE"]);
require("include/_head.php");

?>
<div class="cabinet-wrap reg-page">
	<div class="container">

		<div class="block-move-to-up">
			<div class="pad_top_container">
				<div class="row">
					
					<?$APPLICATION->IncludeComponent("bitrix:system.auth.forgotpasswd", "forgetpassword", Array(
						"SHOW_ERRORS" => "Y",
						"AUTH_AUTH_URL" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["AUTH_URL"]["VALUE"],
						"AUTH_REGISTER_URL" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["REGISTER_URL"]["VALUE"],
						"COMPOSITE_FRAME_MODE" => "N",
						),
						false
					);?>
		        </div>
	        </div>
		</div>

	</div>
</div>
