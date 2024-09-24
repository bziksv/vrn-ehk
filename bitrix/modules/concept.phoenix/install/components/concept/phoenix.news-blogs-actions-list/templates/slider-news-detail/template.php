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
        
    <div class="ex-row">
        <div class="section-blog slider-news-detail universal-slider parent-slider-item-js">
                
            <?foreach($arResult["ITEMS"] as $k => $arItem):?>

                <div class="col-12 <?=($k!=0)?'noactive-slide-lazyload':'';?>">
                    <div class="section-blog-item general-hover-shine">

                        <?CPhoenix::admin_setting($arItem, false)?>

                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">

                            <div class="row wr-name no-gutters align-items-center">

                                <div class="name col-12"><?=$arItem["~NAME"]?></div>

                            </div>

                        

                            <?if(strlen($arItem['PREVIEW_PICTURE_SRC'])):?>

                                <div class="picture lazyload" data-src= "<?=$arItem["PREVIEW_PICTURE_SRC"]?>" >
                                    <div class="shine"></div>
                                </div>
                           

                            <?endif;?>

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

                        </a>


                    </div>
                </div>


            <?endforeach;?>

        </div>
    </div>

        <img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">
    </div>

    <?(!isset($arParams["BTNS_ACTIVE"]) || $arParams["BTNS_ACTIVE"] == "") ?"Y":$arParams["BTNS_ACTIVE"];?>

    <?if($arParams["BTNS_ACTIVE"]=="Y"):?>

        <?
            $countItems = count( $arResult["ITEMS"] );

            $class_arrows = "";
            $class_padding_right = "";

            if($countItems>3)
            {
                $class_arrows .= " visible-xxl visible-xl visible-lg";
                $class_padding_right .= " padding-right-xxl padding-right-xl padding-right-lg";
            }

            if($countItems>2)
            {
                $class_arrows .= " visible-md visible-sm";
                $class_padding_right .= " padding-right-md padding-right-sm";
            }
        ?>

        <?if(strlen($class_arrows)>0 || strlen($class_padding_right)>0):?>
            <script>

                $(document).ready(function(){
                    <?if(strlen($class_arrows)>0):?>
                        $("#<?=$arParams["BLOCK_ID"]?>").find(".wr-arrows-slick").addClass('<?=$class_arrows?>').removeClass('d-none');
                    <?endif;?>

                    <?if(strlen($class_padding_right)>0):?>
                        $("#<?=$arParams["BLOCK_ID"]?>").find(".cart-title").addClass('<?=$class_padding_right?>').removeClass('d-none');
                    <?endif;?>
                });
                
            </script>
        <?endif;?>

    <?endif;?>

<?endif;?>