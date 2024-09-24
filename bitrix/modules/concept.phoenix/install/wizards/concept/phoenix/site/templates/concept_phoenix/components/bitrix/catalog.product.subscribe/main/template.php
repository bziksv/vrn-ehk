<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use Bitrix\Main\Localization\Loc;

CJSCore::init(array('popup', 'ajax'));

$this->setFrameMode(true);
global $PHOENIX_TEMPLATE_ARRAY;

CPhoenix::phoenixOptionsValues(SITE_ID, 
array(
    "design"
));

$strMainId = $this->getEditAreaId($arResult['PRODUCT_ID']);
$jsObject = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainId);
$paramsForJs = array(
	'buttonId' => $arResult['BUTTON_ID'],
	'jsObject' => $jsObject,
	'alreadySubscribed' => $arResult['ALREADY_SUBSCRIBED'],
	'listIdAlreadySubscribed' => (!empty($_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID']) ?
		$_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID'] : []),
	'productId' => $arResult['PRODUCT_ID'],
	'buttonClass' => htmlspecialcharsbx($arResult['BUTTON_CLASS']),
	'urlListSubscriptions' => '/',
	'landingId' => SITE_ID,
	'buttonMainColorClass'=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']
);



$showSubscribe = true;

/* Compatibility with the sale subscribe option */
$saleNotifyOption = Bitrix\Main\Config\Option::get('sale', 'subscribe_prod');
if(strlen($saleNotifyOption) > 0)
	$saleNotifyOption = unserialize($saleNotifyOption);
$saleNotifyOption = is_array($saleNotifyOption) ? $saleNotifyOption : array();


foreach($saleNotifyOption as $siteId => $data)
{
	if($siteId == SITE_ID && $data['use'] != 'Y')
		$showSubscribe = false;
}
$templateData = $paramsForJs;
$templateData['showSubscribe'] = $showSubscribe;

?>
	<a id="<?=htmlspecialcharsbx($arResult['BUTTON_ID'])?>"
			class="<?=htmlspecialcharsbx($arResult['BUTTON_CLASS'])?>"
			data-item="<?=htmlspecialcharsbx($arResult['PRODUCT_ID'])?>"
			style="<?=($arResult['DEFAULT_DISPLAY']?'':'display: none;')?>">
		<span>
			<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSCRIBE_BTN_NAME"]["~VALUE"];?>
		</span>
	</a>
	<input type="hidden" id="<?=htmlspecialcharsbx($arResult['BUTTON_ID'])?>_hidden">

	<script type="text/javascript">
		BX.message({
			CPST_SUBSCRIBE_POPUP_TITLE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SUBSCRIBE_PRODUCT_POPUP_TITLE"];?>',
			CPST_SUBSCRIBE_BUTTON_NAME: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSCRIBE_BTN_NAME"]["~VALUE"];?>',
			CPST_SUBSCRIBE_BUTTON_CLOSE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SUBSCRIBE_PRODUCT_BUTTON_CLOSE"];?>',
			CPST_SUBSCRIBE_MANY_CONTACT_NOTIFY: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SUBSCRIBE_PRODUCT_MANY_CONTACT_NOTIFY"];?>',
			CPST_SUBSCRIBE_LABLE_CONTACT_INPUT: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SUBSCRIBE_PRODUCT_LABLE_CONTACT_INPUT"];?>',
			CPST_SUBSCRIBE_VALIDATE_UNKNOW_ERROR: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SUBSCRIBE_PRODUCT_VALIDATE_UNKNOW_ERROR"];?>',
			CPST_SUBSCRIBE_VALIDATE_ERROR_EMPTY_FIELD: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SUBSCRIBE_PRODUCT__VALIDATE_ERROR_EMPTY_FIELD"];?>',
			CPST_SUBSCRIBE_VALIDATE_ERROR: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SUBSCRIBE_PRODUCT_VALIDATE_ERROR"];?>',
			CPST_SUBSCRIBE_CAPTCHA_TITLE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SUBSCRIBE_PRODUCT_CAPTCHA_TITLE"];?>',
			CPST_STATUS_SUCCESS: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SUBSCRIBE_PRODUCT_STATUS_SUCCESS"];?>',
			CPST_STATUS_ERROR: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SUBSCRIBE_PRODUCT_STATUS_ERROR"];?>',
			CPST_ENTER_WORD_PICTURE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SUBSCRIBE_PRODUCT_ENTER_WORD_PICTURE"];?>',
			CPST_TITLE_ALREADY_SUBSCRIBED: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSCRIBED_BTN_NAME"]["~VALUE"];?>',
			CPST_POPUP_SUBSCRIBED_TITLE: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSCRIBED_BTN_NAME"]["~VALUE"];?>',
			CPST_POPUP_SUBSCRIBED_TEXT: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SUBSCRIBE_PRODUCT_POPUP_SUBSCRIBED_TEXT"];?>'
		});

		var <?=$jsObject?> = new JCCatalogProductSubscribe(<?=CUtil::phpToJSObject($paramsForJs, false, true)?>);
	</script>
