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
<?
$firstItem = array_shift($arResult["ITEMS"]);
array_unshift($arResult["ITEMS"], $firstItem);
?>
    <div class="img-for-lazyload-parent finish-bottom">
        <img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">



        <div class="slider slider-news<?if($arParams["SHOW_MENU"]):?> slider-news-small<?else:?> slider-news-big<?endif;?> universal-head-arrows universal-mobile-arrows <?if($arParams["HEAD_EMPTY"]):?>head-empty<?endif;?> universal-slider section-blog parent-slider-item-js">
                
            <?foreach($arResult["ITEMS"] as $k => $arItem):?>

                <div class="col-12 element <?=($k!=0)?'noactive-slide-lazyload':'';?>">
                    <?CPhoenix::admin_setting($arItem, false)?>

                    <div class="point"></div>

                    

                    <div class="element-inner hover_shine">

                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" >

                            <?if(strlen($arItem['PREVIEW_PICTURE_SRC'])):?>

                                <div class="wrap-img lazyload" data-src="<?=$arItem['PREVIEW_PICTURE_SRC']?>"><div class="shine"></div></div>

                            <?endif;?>

                            <div class="name bold">
                                <?=$arItem["~NAME"]?>
                            </div>
                        </a>

                        <div class="wr-text">

                            <?if(strlen($arItem["~PREVIEW_TEXT"]) > 0):?>
                                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" >
                                    <div class="text">
                                        <?=$arItem["~PREVIEW_TEXT"]?>
                                    </div>
                                </a>
                            <?endif;?>


                        </div>
                        

                        <?//if($arResult["SHOW_DATA"]):?>

                            <div class="date">
                                <?
                                    switch($arItem["TYPE_ELEMENT"]){

                                        case ("action"):?>

                                            <div class="date-action">
                                                <span class="<?=$arItem["CURRENT_TIME_ACTION"]["CLASS"]?>"><?=$arItem["CURRENT_TIME_ACTION"]["TITLE"]?></span>
                                            </div>

                                        <?break;

                                        case ("blog"):
                                        case ("news"):
                                        default:?>
                                            <?=$arItem["ACTIVE_FROM_FORMATED"]?>
                                        <?break;
                                    }
                                ?>
                            </div>

                        <?//endif;?>


                        <?if(strlen($arItem["DETAIL_TITLE"])):?>

                            <div class="btn-detail-wrap">
                                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                                    <span class='bord-bot'><?=$arItem["DETAIL_TITLE"]?></span>
                                </a>
                            </div>

                        <?endif;?>

                    </div>

                </div>


            <?endforeach;?>

        </div>
        
        <img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">
    </div>

    <?

        $countItems = count( $arResult["ITEMS"] );

        $class_arrows = "";

        if($arParams["SHOW_MENU"])
        {

            if($countItems>3)
                $class_arrows .= " visible-xxl visible-xl visible-lg visible-md";
        }

        else
        {
            if($countItems>4)
                $class_arrows .= " visible-xxl visible-xl visible-lg";
            

            if($countItems>3)
                $class_arrows .= " visible-md";
        }

    ?>

    <?if(strlen($class_arrows)>0):?>
        <script>

            $(document).ready(function(){
                $("#<?=$arParams["BLOCK_ID"]?>").find(".wr-arrows-slick").addClass('<?=$class_arrows?>').removeClass('d-none');
            });
            
        </script>
    <?endif;?>
<?endif;?>
