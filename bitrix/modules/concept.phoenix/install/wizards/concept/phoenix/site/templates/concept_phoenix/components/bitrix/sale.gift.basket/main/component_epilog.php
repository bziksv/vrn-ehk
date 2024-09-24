<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
global $APPLICATION;
/*if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateData['TEMPLATE_THEME']);
}
CJSCore::Init(array("popup"));*/
?>

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


<?foreach ($arResult['ITEMS'] as $keyItem => $arItem):?>
    <?
        unset($arItem["FIRST_ITEM"], $arItem["SKU_PROPS"]);
    ?>
    <script>
       var <?=$arItem['VISUAL']['ID']?> = new JCSaleGiftBasket(<? echo CUtil::PhpToJSObject($arItem, false, true); ?>);
    </script>

<?endforeach;?>