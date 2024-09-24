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
global $PHOENIX_TEMPLATE_ARRAY;
?>

<?if(!empty($arResult["SECTIONS"])):?>

<?

    $countItems = count($arResult["SECTIONS"]);
    $classItem = ($countItems<=3)? "col-4":"";
?>

    
    <div class="ex-row">

        <div class="img-for-lazyload-parent finish-bottom">

            <img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arResult["SECTIONS"][0]["ID"]?>">


            <div class="section-items-slider <?=($countItems>3)? "parent-slider-item-js":"no-slider"?>">

                <?if($countItems<=3):?><div class="row justify-content-center no-margin"><?endif;?>

                <?foreach($arResult["SECTIONS"] as $key => $arSection):?>
                
                    <div class="<?if($countItems>3):?><?=($key != 0) ? 'noactive-slide-lazyload' : ''?><?endif;?> <?=$classItem?>">
                        <div class="item">
                            <a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="d-block">
                                    
                                <img src="<?=$arSection["PICTURE_SRC"]?>" class="img-fluid mx-auto d-block" alt="<?=CPhoenix::prepareText($arSection["NAME"])?>">

                                <div class="desc row align-items-center">
                                    <div class="col-12">
                                        <?=$arSection["~NAME"]?>
                                    </div> 
                                </div>
                            </a>
                        </div>
                    </div>

                <?endforeach;?>

                <?if($countItems<=3):?></div><?endif;?>
              
            </div>


            <?if($countItems>3):?>
            <div class="slider-swipe-icon">
            </div>
            <?endif;?>

            <img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arResult["SECTIONS"][0]["ID"]?>">

        </div>

    </div>

<?endif;?>


<? 
unset($maxItems, $cols, $controlCount, $curCountItems, $countItems);
