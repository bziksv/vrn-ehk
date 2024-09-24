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
    <div class="section-blog row">
            
        <?foreach($arResult["ITEMS"] as $k => $arItem):?>

            <div class="col-lg-<?=$arParams["COL_LG"]?> col-md-6 col-12">
                <div class="section-blog-item general-hover-shine">

                    <?CPhoenix::admin_setting($arItem, false)?>

                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">

                    	<div class="row wr-name no-gutters align-items-center">

                        	<div class="name col-12"><?=$arItem["~NAME"]?></div>

                        </div>
                           

                    

                        <?if(strlen($arItem['PREVIEW_PICTURE_SRC'])):?>

                            <div class="picture lazyload" 
                           
                                <?/*style="background-image: url('<?=$img["src"]?>');"*/?>
                                data-src= "<?=$arItem["PREVIEW_PICTURE_SRC"]?>"

                            >

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
<?endif;?>