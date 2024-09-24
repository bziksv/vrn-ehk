<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
?>

<?if(!empty($arResult["ITEMS"])):?>
    <div class="menu-navigation static">

        <div class="menu-navigation-wrap">
            <div class="menu-navigation-inner" id="navigation">

                <div class="row">

                    <ul class="nav">

                        <?foreach($arResult["ITEMS"] as $arItem):?>
                            <li class="col-12">
                                <a href="<?=$arItem["PATH"]?>" class="<?=(isset($arItem["ACTIVE"]) && $arItem["ACTIVE"] === "Y")?"active":""?>">
                                    <span class="text">
                                        <table>
                                            <tr>
                                                <td class="name"><?=$arItem["NAME"]?></td>
                                            </tr>
                                        </table>
                                    </span>
                                </a>
                            </li>
                        <?endforeach;?>

                        <?/*if(!empty($arResult["SECTIONS"])):?>
                        
                            <?foreach($arResult["SECTIONS"] as $arSection):?>
                            
                                <li class="col-12" data-id="<?=$arSection["ID"]?>">
                                    <a href="<?=$arSection["SECTION_PAGE_URL"]?>">
                                        <span class="text">
                                            <table>
                                                <tr>
                                                    <td class="name"><?=strip_tags($arSection["~NAME"])?></td>
                                                    
                                                    <td class="count"><?=$arSection["ELEMENT_CNT"]?></td>
                                                </tr>
                                            </table>
                                        </span>
                                    </a>
                                </li>
                            
                            <?endforeach;?>

                        <?endif;*/?>
                        
                        <?/*<li class="col-12 back">
                            <a href="<?=$arResult["SECTION_BACK"]?>"><span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BACK_TO_LVL_UP"]?></span></a>
                        </li>*/?>
                        
                    </ul>
                </div>
            </div>
        
        </div>

    </div>
<?endif;?>