<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="row align-items-center">

    <div class="col-lg-1 col-md-2 col-4 left-body">
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="d-block" id="<?=$itemIds["DETAIL_URL_IMG"]?>">

            <img class="img-fluid d-block mx-auto lazyload" id="<?=$itemIds["PICT"]?>" data-src="<?=$arItem["FIRST_ITEM"]["PHOTO"]?>" alt="<?=$arItem["FIRST_ITEM"]["ALT"]?>"/>

        </a>
        <div class="d-none"><span id="<?=$itemIds['DISCOUNT_PERCENT']?>" class="sale"><?=-$arItem["FIRST_ITEM"]['PRICE']["PERCENT"]?>%</span></div>
    </div>
    
    <div class="col-lg-5 col-md-6 col-8 center-left-body">
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="name-element">
            <?=strip_tags($arItem["NAME_HTML"])?>
        </a>
        
        <?if(strlen($arItem["FIRST_ITEM"]["SHORT_DESCRIPTION"])):?>
            <div class="short-description">
                <?=$arItem["FIRST_ITEM"]["SHORT_DESCRIPTION_HTML"]?>
            </div>
        <?endif;?>

        

        <div class="wrapper-delay-compare-available row no-gutters align-items-center">


            <?if( $arItem['CONFIG']['SHOW_DELAY'] == "Y" 
              || $arItem['CONFIG']['SHOW_COMPARE'] == "Y" ):?>

                <div class="wrapper-delay-compare-icons <?=($arItem['HAVEOFFERS'])?"d-none":"";?>">

                    <?if($arItem['CONFIG']['SHOW_DELAY'] == "Y"):?>
                        <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DELAY_TITLE"]?>" class="icon delay add2delay" id = "<?=$itemIds["DELAY"]?>" data-item="<?=$arItem["ID"]?>"></div>
                    <?endif;?>

                    <?if($arItem['CONFIG']['SHOW_COMPARE'] == "Y"):?>
                        <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_COMPARE_TITLE"]?>" class="icon compare add2compare" id = "<?=$itemIds["COMPARE"]?>" data-item="<?=$arItem["ID"]?>"></div>
                    <?endif;?>
                </div>
            <?endif;?>

        
        </div>
        
        
    </div>

    <div class="col-xl-3 col-lg-2 col-md-4 col-12 center-right-body">

        <div class="block-price 
            <?=($arItem["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItem["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'd-none' : ''?>">

            <div class="name-type-price d-none">
                <?=$arItem['FIRST_ITEM']['PRICE']['NAME']?>
            </div>

            <div class="board-price row no-gutters product-price-js">

                <?if($arItem["HAVEOFFERS"]):?>

                    <div class="actual-price">

                        <span class="price-value" id="<?=$itemIds['PRICE']?>"><?=$arItem['OFFER_DIFF']['VALUE']['HTML']?>

                    </div>

                <?else:?>
                    <div class="actual-price">

                        <span class="price-value" id="<?=$itemIds['PRICE']?>"><?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?></span><span class="unit <?=(strlen($arItem["FIRST_ITEM"]['MEASURE'])>0 ? '' : 'd-none')?>" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$arItem["FIRST_ITEM"]['MEASURE_HTML']?></span>

                    </div>

                    
                    <div class="old-price align-self-end <?=($arItem["FIRST_ITEM"]["SHOW_DISCOUNT"] ? '' : 'd-none')?>" id="<?=$itemIds['OLD_PRICE']?>">
                        <?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE']?>
                    </div>
                <?endif;?>

            </div>

        </div>

    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 col-12 offset-lg-0 offset-md-3 right-body">
        

        <div class="wrapper-inner-bot row no-gutters hidden-js <?=($arItem['HAVEOFFERS'])?"hidden":""?>" id="<?=$itemIds['WR_ADD2BASKET']?>">
            

            <div class="quantity-container col-8 quantity-block"
            data-item="<?=$arItem['ID']?>" 
            >

                <div class="inner-quantity-container row no-gutters align-items-center">

                    <table>
                        <tr>
                            <td class="btn-quantity"><span class="product-item-amount-field-btn-minus no-select" id="<?=$itemIds['QUANTITY_DOWN']?>">&minus;</span></td>
                            <td><input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number" name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>" value="<?=$arItem["FIRST_ITEM"]["PRICE"]["MIN_QUANTITY"]?>"></td>
                            <td class="btn-quantity"><span class="product-item-amount-field-btn-plus no-select" id="<?=$itemIds['QUANTITY_UP']?>">&plus;</span></td>
                        </tr>
                    </table>
                </div>

            </div>

            <div class="btn-container align-items-center col-4" id="<?=$itemIds['BASKET_ACTIONS']?>">
                
                <a
                    id = "<?=$itemIds['ADD2BASKET']?>"
                    href="javascript:void(0);"
                    data-item = "<?=$arItem["ID"]?>"
                    class="main-color add2basket" title="<?=CPhoenix::prepareText($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"])?>"></a>

                <a
                    id = "<?=$itemIds['MOVE2BASKET']?>"
                    href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"
                    data-item = "<?=$arItem["ID"]?>"
                    class="move2basket"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?></a>

            </div>

        </div>


        <div class="wrapper-inner-bot row no-gutters <?=($arItem['HAVEOFFERS'])?"":"d-none"?>" id="<?=$itemIds['BTN2DETAIL_PAGE_PRODUCT']?>">

            <div class="btn-container align-items-center col-12">
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="main-color bold">

                    <?if($arItem['HAVEOFFERS']):?>

                        <span class="d-none d-lg-block"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER"]["VALUE"]?></span>

                        <span class="d-lg-none"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER_MOB"]["VALUE"]?></span>

                    <?else:?>

                        <span class="d-block"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME"]["VALUE"]?></span>

                    <?endif;?>
                 
                </a>
            </div>

        </div>
        
    </div>

    <?CPhoenix::admin_setting($arItem, false)?>
</div> 
