
<?
global $PHOENIX_TEMPLATE_ARRAY;
$basket_url = CPhoenix::getBasketUrl(SITE_DIR, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]);

$curPage = $APPLICATION->GetCurPage(false);
?>

<?if($curPage != $basket_url && $curPage != $basket_url."order/"):?>

    <div class="no-click-block"></div>

    <div class="wrapper-cart basket-style fly-basket">

        <input class="link_empty_cart" type="hidden" value="">

        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"])):?>
        
            <div class="area_for_widget hidden-sm hidden-xs">
            
                <div class="open-cart cart-empty cart-show <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_MODE"]["VALUE"]?>">
                    <div class="before_pulse"></div>
                    <div class="after_pulse"></div>

                    <span class="count"></span>

                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_DESC_EMPTY"]["VALUE"]):?><span class="desc-empty"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_DESC_EMPTY"]["~VALUE"]?></span><?endif;?>
                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_DESC_NOEMPTY"]["VALUE"]):?><span class="desc-no-empty"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_DESC_NOEMPTY"]["VALUE"]?></span><?endif;?>

                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_LINK_PAGE"]["VALUE"]):?>
                        <a class="cart_link scroll" href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_LINK_PAGE"]["VALUE"]?>"></a>
                    <?endif;?>

                </div>
                
            </div>

        <?endif;?>


        <?
            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_HEAD_BG']['VALUE'] > 0)
                $bg = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_HEAD_BG']['VALUE'], array('width'=>1600, 'height'=>500), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 85);
        ?>

        
        <div class="cart-outer cart-parent col-xl-10 col-12">

         
            <div class="cart-inner">

                <div class="head cart-head-height"<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_HEAD_BG']['VALUE'] > 0):?> style="background-image: url(<?=$bg["src"]?>);"<?endif;?>>
                    <div class="incart-shadow"></div>

                    <div class="wrapper-title row align-items-center">

                        <div class="col-lg-2 col-3 hidden-sm hidden-xs cart-image"><div></div></div>
                        <div class="col-lg-8 col-md-6 col-9 title main1"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_HEAD_TIT']['~VALUE']?></div>
                        <div class="col-lg-2 col-3"></div>

                    </div>
                          

                    <a class="cancel-cart cart-close"></a>


                </div>

                <div class="body">

                    <div class="main-table col-12">
                        <div class="row main-table-inner body-fly-basket body-fly-basket-ajax">
                            
                            <?/*
                                $APPLICATION->IncludeComponent("bitrix:sale.basket.basket",
                                    "fly-basket",
                                    Array(
                                    "ACTION_VARIABLE" => "basketAction",    
                                        "ADDITIONAL_PICT_PROP_15" => "-",   
                                        "ADDITIONAL_PICT_PROP_8" => "-",    
                                        "AUTO_CALCULATION" => "Y",  
                                        "BASKET_IMAGES_SCALING" => "adaptive",  
                                        "COLUMNS_LIST_EXT" => array(    
                                            0 => "PREVIEW_PICTURE",
                                            1 => "DISCOUNT",
                                            2 => "DELETE",
                                            3 => "DELAY",
                                            4 => "TYPE",
                                            5 => "SUM",
                                        ),
                                        "COLUMNS_LIST_MOBILE" => array(
                                            0 => "PREVIEW_PICTURE",
                                            1 => "DISCOUNT",
                                            2 => "DELETE",
                                            3 => "DELAY",
                                            4 => "TYPE",
                                            5 => "SUM",
                                        ),
                                        "COMPATIBLE_MODE" => "Y",
                                        "CORRECT_RATIO" => "Y",
                                        "DEFERRED_REFRESH" => "N",
                                        "DISCOUNT_PERCENT_POSITION" => "bottom-right",
                                        "DISPLAY_MODE" => "compact",
                                        "EMPTY_BASKET_HINT_PATH" => "/",
                                        "GIFTS_BLOCK_TITLE" => "",
                                        "GIFTS_CONVERT_CURRENCY" => "N",
                                        "GIFTS_HIDE_BLOCK_TITLE" => "N",
                                        "GIFTS_HIDE_NOT_AVAILABLE" => "N",
                                        "GIFTS_MESS_BTN_BUY" => "Выбрать",
                                        "GIFTS_MESS_BTN_DETAIL" => "Подробнее",
                                        "GIFTS_PAGE_ELEMENT_COUNT" => "4",
                                        "GIFTS_PLACE" => "BOTTOM",
                                        "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
                                        "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
                                        "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
                                        "GIFTS_SHOW_OLD_PRICE" => "N",
                                        "GIFTS_TEXT_LABEL_GIFT" => "Подарок",
                                        "HIDE_COUPON" => "N",
                                        "LABEL_PROP" => "",
                                        "OFFERS_PROPS" => "",
                                        "PATH_TO_ORDER" => SITE_DIR."order/",
                                        "PRICE_DISPLAY_MODE" => "Y",
                                        "PRICE_VAT_SHOW_VALUE" => "Y",
                                        "PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
                                        "QUANTITY_FLOAT" => "N",
                                        "SET_TITLE" => "N",
                                        "SHOW_DISCOUNT_PERCENT" => "Y",
                                        "SHOW_FILTER" => "Y",
                                        "SHOW_RESTORE" => "Y",
                                        "TEMPLATE_THEME" => "blue",
                                        "TOTAL_BLOCK_DISPLAY" => array(
                                            0 => "top",
                                        ),
                                        "USE_DYNAMIC_SCROLL" => "Y",
                                        "USE_ENHANCED_ECOMMERCE" => "N",
                                        "USE_GIFTS" => "N",
                                        "USE_PREPAYMENT" => "N",
                                        "USE_PRICE_ANIMATION" => "Y",
                                        "COMPOSITE_FRAME_MODE" => "N",
                                    ),
                                    false
                                );
                            */?>

                        </div>
                        
                    </div>
                </div>

            </div>

        
             
        </div>
    </div>

<?endif;?> 