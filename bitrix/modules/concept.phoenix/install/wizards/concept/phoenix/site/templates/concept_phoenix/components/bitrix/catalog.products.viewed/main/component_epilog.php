<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */

global $APPLICATION;
/*
if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = \Bitrix\Main\Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);

	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?
	}
}*/
?>
<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("area-".$arResult['ID']);?>
<?

    $arProductsAmountByStore=array();

    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["SHOW_RESIDUAL"]["VALUE"]["ACTIVE"] === "Y" && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"]))
    {
        $arProductsAmountByStore = CPhoenix::getProductsAmountByStore($arResult['ITEMS']);
    }

    if(!empty($arProductsAmountByStore))
    {
        foreach ($arResult['ITEMS'] as $keyItem => $arItem)
        {
            if($arItem['CONFIG']["SHOW_OFFERS"] && $arItem['HAVEOFFERS'])
            {
                foreach ($arItem['OFFERS'] as $keyOffer => $arOffer)
                    CPhoenix::parseProductByAmount($arOffer, $arProductsAmountByStore[$arOffer["ID"]]);
                
            }
            else
            {
                CPhoenix::parseProductByAmount($arItem, $arProductsAmountByStore[$arItem["ID"]]);
            }
        }
        
    }

?>

<?if(!empty($arResult['ITEMS'])):?>

<?foreach ($arResult['ITEMS'] as $keyItem => $arItem):?>
    <?
        unset($arItem["FIRST_ITEM"], $arItem["SKU_PROPS"]);
    ?>
    <script>
      var <?=$arItem['VISUAL']['ID']?> = new JCCatalogItem(<?=CUtil::PhpToJSObject($arItem, false, true)?>);
    </script>

<?endforeach;?>
<?endif;?>

<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("area-".$arResult['ID']);?>