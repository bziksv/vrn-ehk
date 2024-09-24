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
    <div class="img-for-lazyload-parent">

        <img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">


        <?if($arParams["SHOW_MENU"]):?>
            <div class="ex-row mob">
        <?endif;?>

            <div class="slider slider-news<?if($arParams["SHOW_MENU"]):?> slider-news-small<?else:?> slider-news-big<?endif;?> universal-head-arrows universal-mobile-arrows <?if($arParams["HEAD_EMPTY"]):?>head-empty<?endif;?> universal-slider section-blog parent-slider-item-js">
                    
                <?foreach($arResult["ITEMS"] as $k => $arItem):?>

                    <div class="col-12 <?=($k!=0)?'noactive-slide-lazyload':'';?>">
                        <div class="section-blog-item general-hover-shine">

                            <?CPhoenix::admin_setting($arItem, false)?>

                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">

                                <div class="row wr-name no-gutters align-items-center">

                                    <div class="name col-12"><?=$arItem["~NAME"]?></div>

                                </div>

                            

                                <?if(strlen($arItem['PREVIEW_PICTURE_SRC'])):?>

                                    <div class="picture lazyload" data-src= "<?=$arItem["PREVIEW_PICTURE_SRC"]?>">
                                        <div class="shine"></div>
                                    </div>
                               

                                <?endif;?>

                            </a>

                            <div class="desc">

                            	<?if(isset($arItem["CATEGORY_ICON_NAME"])):?>

        	                        <div 
        	                            title ="<?=$arItem["CATEGORY_ICON_NAME"]?>"
        	                            class="section-blog-icon 
        	                                lazyload 
        	                                <?=$arItem["CATEGORY_ICON_CLASS"]?>" 

        	                            <?if(strlen($arItem["CATEGORY_ICON_SRC"])):?> data-src="<?=$arItem["CATEGORY_ICON_SRC"]?>"<?endif;?>
        	                            >
        	                            
        	                        </div>

                                <?endif;?>

                                <a href='<?=$arItem["CATEGORY_URL"]?>' class="wrap-link-sect"><?=$arItem["CATEGORY_NAME"]?></a>



                                <?/*if(strlen($arItem["ACTIVE_FROM"]) > 0):?>
                                    <?echo CIBlockFormatProperties::DateFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]["VALUE"], MakeTimeStamp($arItem["ACTIVE_FROM"], CSite::GetDateFormat()));?>
                                <?endif;*/?>

                            </div>

                            


                        </div>
                    </div>


                <?endforeach;?>

            </div>

        <?if($arParams["SHOW_MENU"]):?>
            </div>
        <?endif;?>

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