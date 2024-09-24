<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>

<?global $PHOENIX_TEMPLATE_ARRAY;?>
<?


$block_name = htmlspecialcharsEx($arResult['~NAME']);




if( strlen($arResult["PRODUCT"]["PREVIEW_TEXT_POSITION"]) )
    $previewTextPos = $arResult["PRODUCT"]["PREVIEW_TEXT_POSITION"];

else if( isset($arResult["UF_PREVIEW_TEXT_POS"]["XML_ID"]) )
    $previewTextPos = $arResult["UF_PREVIEW_TEXT_POS"]["XML_ID"];

else
    $previewTextPos = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['PREVIEW_TEXT_POSITION']['VALUE'];

if( strlen($previewTextPos)<=0 )
    $previewTextPos = "under_pic";

?>

<?$desc = 0;?>

<?if(strlen($arResult["DETAIL_TEXT"])):?>
    <?$desc = 1;?>
<?endif;?>


<?$this->SetViewTarget('catalog-left-menu');?>

    
    <div class="row">
        <ul class='nav'>
            <?if( !empty($arResult["SIDE_MENU"]) ):?>
            
                <?foreach($arResult["SIDE_MENU"] as $key=>$value):?>
                
                    <?if(strlen($value["NAME"]) > 0):?>
                        <li class="col-12">
                            <a href="#<?=$key?>" class='scroll nav-link <?if($key == "main"):?>review<?endif;?>'><span class="text"><?=$value["NAME"]?></span></a>
                        </li>
                    <?endif;?>
                    
                <?endforeach;?>
            <?endif;?>
            
            <li class="col-12 back">
                <a href="<?=$arResult["SECTION"]["SECTION_PAGE_URL"]?>"><span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CARD_LEVEL_UP"]?></span></a>
            </li>
            
        </ul>
    </div>
                            
<?$this->EndViewTarget();?>

<?$this->SetViewTarget('empl-banner');?>
    
    <?if(!empty($arParams["EMPL_BANNER"])):?>

        <?$APPLICATION->IncludeComponent(
            "concept:phoenix.news-list",
            "empl",
            Array(
                "COMPOSITE_FRAME_MODE" => "N",
                "ELEMENTS_ID" => $arParams["EMPL_BANNER"],
                "VIEW" => "flat-banner",
                "COLS" => "col-12",
                "SORT_BY1" => "SORT",
                "SORT_ORDER1" => "ASC",
            )
        );?>

    <?endif;?>


<?$this->EndViewTarget();?>

<?
    
    $colsHeadLeftPart = "col-md-10 col-12";
    $colsHeadRightPart = "col-md-2 col-12";

    $colsHeadRightPartShare = "col-12";

    if(isset($arResult["BRAND"]))
    {
        $colsHeadLeftPart = "col-lg-8 col-12";
        $colsHeadRightPart = "col-lg-4 col-12";
        $colsHeadRightPartShare = "col-6";
    }
?>


<?$this->SetViewTarget('catalog-detail-head-left-part-cols');?><?=$colsHeadLeftPart?><?$this->EndViewTarget();?>

<?$this->SetViewTarget('catalog-detail-set-product');?>
    <?if($arResult["SHOW_SET_PRODUCT"]):?><a href="#set_product" class="scroll complect-label"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_PRODUCT_BTN_NAME"]?></a><?endif;?>
<?$this->EndViewTarget();?>

<?$this->SetViewTarget('catalog-detail-head-right-part');?>

    <div class="<?=$colsHeadRightPart?> hidden-md hidden-sm hidden-xs">
        <div class="row">

            <?if( isset($arResult["BRAND"]) ):?>

                <div class="col-6">

                    <div class="wrapper-brand" itemprop="brand" itemscope itemtype="http://schema.org/Brand">

                        <div class="brand-picture">
                            <div class="wrapper-img row no-gutters align-items-center">
                                <?if(strlen($arResult["BRAND"]["PREVIEW_PICTURE_SRC"])>0):?>
                                    <img class="img-fluid lazyload" data-src="<?=$arResult["BRAND"]["PREVIEW_PICTURE_SRC"]?>">
                                <?else:?>
                                    <div class="name-brand"><?=strip_tags($arResult["BRAND"]["~NAME"])?></div>
                                <?endif;?>
                            </div>
                            <div class="shadow-tone gray"></div>
                            <a itemprop="url" class="z-absolute-wrapper hidden-xxl hidden-xl" href="<?=$arResult["BRAND"]["DETAIL_PAGE_URL"]?>"></a>
                        </div>

                        <div class="detail-info hidden-lg hidden-md hidden-sm hidden-xs">
                            <div class="header row no-gutters align-items-center">

                                <div class="col-7 row no-gutters align-items-center">
                                    <?if(strlen($arResult["BRAND"]["PREVIEW_PICTURE_SRC"])>0):?>
                                        <img class="img-fluid" src="<?=$arResult["BRAND"]["PREVIEW_PICTURE_SRC"]?>">
                                        <link itemprop="logo" href="<?=$arResult["BRAND"]["PREVIEW_PICTURE_SRC"]?>">
                                    <?else:?>
                                        <div class="name-brand"><?=strip_tags($arResult["BRAND"]["~NAME"])?></div>
                                    <?endif;?>
                                </div>

                                <div class="col-5 right">
                                    <a href="<?=$arResult["BRAND"]["DETAIL_PAGE_URL"]?>#catalog_brand"><span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DETAIL_BRAND_CATALOG_LINK"];?></span></a>
                                </div>

                            </div>

                            <?if( strlen($arResult["BRAND"]["~PREVIEW_TEXT"]) ):?>

                                <div itemprop="description" class="body">
                                    <?=$arResult["BRAND"]["~PREVIEW_TEXT"]?>
                                </div>

                            <?endif;?>

                            
                            <div class="footer">
                                <a class="ic-info" href="<?=$arResult["BRAND"]["DETAIL_PAGE_URL"]?>#about_brand"><span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DETAIL_BRAND_DETAIL_LINK"];?></span></a>
                            </div>
                            
                        </div>
                    </div>
                   
                </div>

            <?endif;?>

            <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARE_DETAIL_CATALOG_ON']['VALUE']['ACTIVE'] == "Y" ):?>
                <div class="<?=$colsHeadRightPartShare?>">

                    <div class="row no-gutters justify-content-end wrapper-icon-round">
                        <!-- <div class="col-auto icon-round print"></div> -->
                        <div class="col-auto icon-round shares open-table-shares">

                            <div class='table-shares'>
                                <?=CPhoenix::shares(
                                    array(
                                        "VK" => array(
                                            "URL" => $arResult["SHARE_URL"],
                                            "TITLE" => $arResult["SHARE_TITLE"],
                                            "IMG" => $arResult["SHARE_IMG"],
                                            "DESCRIPTION" => $arResult["SHARE_DESCRIPTION"],
                                        ),
                                        "FB" => array(
                                            "URL" => $arResult["SHARE_URL"],
                                            "IMG" => $arResult["SHARE_IMG"],
                                            "TITLE" => $arResult["SHARE_TITLE"],
                                            "DESCRIPTION" => $arResult["SHARE_DESCRIPTION"],
                                        ),
                                        "TW" => array(
                                            "URL" => $arResult["SHARE_URL"],
                                            "TITLE" => $arResult["SHARE_TITLE"],
                                        ),
                                        "OK" => array(
                                            "URL" => $arResult["SHARE_URL"],
                                            "TITLE" => $arResult["SHARE_TITLE"],
                                        ),
                                        "MAILRU" => array(
                                            "URL" => $arResult["SHARE_URL"],
                                            "TITLE" => $arResult["SHARE_TITLE"],
                                        ),
                                        "WTSAPP" => array(
                                            "URL" => $arResult["SHARE_URL"],
                                            "TITLE" => $arResult["SHARE_TITLE"],
                                        ),
                                        "TELEGRAM" => array(
                                            "URL" => $arResult["SHARE_URL"],
                                            "TITLE" => $arResult["SHARE_TITLE"],
                                        ),
                                        "SKYPE" => array(
                                            "URL" => $arResult["SHARE_URL"],
                                        ),
                                        "GPLUS" => array(
                                            "URL" => $arResult["SHARE_URL"],
                                        ),
                                        "REDDIT" => array(
                                            "URL" => $arResult["SHARE_URL"],
                                        ),
                                    ),
                                    "view-2"
                                )?>

                                <?if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARES_COMMENT_FOR_DETAIL_CATALOG']['VALUE']) ):?>
                                    <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARES_COMMENT_FOR_DETAIL_CATALOG']['~VALUE']?></div>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
               
                </div>
            <?endif;?>

        </div>

    </div>

<?$this->EndViewTarget();?>

<?
    $itemIds = $arResult["PRODUCT"]["VISUAL"];
?>


<div class="cart-info-block scroll-next-parent" id='main'>

    <div class="small-info-product row align-items-center justify-content-between d-lg-none">

        <?if( isset($arResult["BRAND"]) ):?>
            <div class="col-4 wr-brand order-2">
                <img class="img-fluid lazyload brand" data-src="<?=$arResult["BRAND"]["PREVIEW_PICTURE_SRC_XS"]?>">
                
            </div>
        <?endif;?>

        <div class="board-shadow-tone gray"></div>
        
        <a href="<?=$arResult["BRAND"]["DETAIL_PAGE_URL"]?>" class="brand-link"></a>

        <div class="col-8 board-links order-1">
            <div class="row">
                <div class="col-auto wr-about-product">
                    <a class="name show-side-menu"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DETAIL_ABOUT_PRODUCT"]?></a>
                </div>
                <div class="col-auto wr-price d-md-none">
                    <a href="#actual_price" class="name scroll"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DETAIL_SCROLL2PRICE"]?></a>
                </div>
            </div>
        </div>

    </div>

    <div id="<?=$itemIds['ID']?>" class="catalog-item">


        <div class="row">
            <div class="col-xl-7 col-sm-6 col-12 info-left-side wrapper-delay-compare-icons-parent">

                <div class="wrapper-picture row" id="<?=$itemIds['BIG_SLIDER']?>">

                    <link itemprop="image" href="<?=$arResult["FIRST_ITEM"]["MORE_PHOTOS"][0]["BIG"]["SRC"]?>">

                    <?
                        $colLeft = "d-none";
                        $colRight = "col-12";

                        
                        if( $arResult["FIRST_ITEM"]['MORE_PHOTOS_COUNT'] > 1 || strlen($arResult["PRODUCT"]['VIDEO_POPUP']))
                        {
                            $colLeft = "col-2";
                            $colRight = "col-10";
                        }
                    ?>

                    <div class="wrapper-controls <?=$colLeft?> <?if(strlen($arResult["PRODUCT"]['VIDEO_POPUP'])):?>video<?endif;?>">
                        <div class="controls-pictures">

                            <?if ( !empty($arResult["FIRST_ITEM"]['MORE_PHOTOS']) ):?>

                                <?
                                    $morePhotos = false;
                                    $quantityPhoto = 0;
                                ?>

                                <?foreach ($arResult["FIRST_ITEM"]['MORE_PHOTOS'] as $key => $photo):?>

                                    <?
                                        if( ($key+1) > 3 ){
                                            $morePhotos= true;
                                            break;
                                        }
                                        $quantityPhoto++; 
                                    ?>

                                    <div class="small-picture <?=($key == 0 ? 'active' : '')?>" data-value="<?=$photo['ID']?>">
                                        <img class="lazyload" data-src="<?=$photo['SMALL']['SRC']?>" alt="<?=strip_tags($photo['DESC'])?>">
                                    </div>


                                <?endforeach;?>

                                <?if($morePhotos):?>

                                    <div class="more">

                                        <a class="open-popup-gallery"
                                            data-popup-gallery="<?=$arResult["FIRST_ITEM"]["ID"]?>_<?=$obName?>"
                                        >
                                        <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MORE"]?> <?echo (intval($arResult["FIRST_ITEM"]['MORE_PHOTOS_COUNT']) - $quantityPhoto);?></a>
                                    </div>

                                <?endif;?>


                            <?endif;?>

                        </div>

                        <?if(strlen($arResult["PRODUCT"]['VIDEO_POPUP'])):?>
                            <?$iframe = CPhoenix::createVideo($arResult["PRODUCT"]['VIDEO_POPUP']);?>

                            <a class="call-modal callvideo" data-call-modal="<?=$iframe["ID"]?>">
                                <div class="video-play"></div>
                                <div class="video-play-desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["VIDEO"]?></div>
                            </a>
                        <?endif;?>

                    </div>


                    <div class="outer-big-picture <?=$colRight?>">
                        <div class="wrapper-big-picture">

                            <?if ( !empty($arResult["FIRST_ITEM"]['MORE_PHOTOS']) ):?>
                                <?foreach ($arResult["FIRST_ITEM"]['MORE_PHOTOS'] as $key => $photo):?>

                                    <div class="big-picture <?=($key == 0 ? 'active' : '')?>"  data-id="<?=$photo['ID']?>">

                                        <a 
                                            class="cursor-loop open-popup-gallery d-block <?=$arResult['ZOOM_ON']?>"
                                            data-popup-gallery="<?=$arResult["FIRST_ITEM"]["ID"]?>_<?=$obName?>"

                                        >
                                            <img <?/*src="<?=$photo['MIDDLE']['SRC']?>"*/?>
                                                class="d-block mx-auto img-fluid open-popup-gallery-item lazyload"
                                                data-src = "<?=$photo['BIG']['SRC']?>"
                                                data-popup-gallery="<?=$arResult["FIRST_ITEM"]["ID"]?>_<?=$obName?>"
                                                data-small-src = "<?=$photo['SMALL']['SRC']?>"
                                                data-big-src = "<?=$photo['BIG']['SRC']?>"
                                                data-desc = "<?=$photo['DESC']?>"
                                                alt="<?=$photo['DESC']?>"
                                                >
                                        </a>

                                    </div>

                                <?endforeach;?>
                            <?else:?>

                                <div class="big-picture active">
                                    <img data-src="<?=$arResult["PICTURE_DEF"]?>" class="d-block mx-auto img-fluid lazyload" alt="">
                                    <link itemprop="image" href="<?=$arResult["PICTURE_DEF"]?>">  
                                </div>

                            <?endif;?>

                        </div>

                        <?if(!empty($arResult["PRODUCT"]["LABELS"]["XML_ID"])):?>
                                                    
                            <div class="wrapper-board-label">
                                
                                <?foreach($arResult["PRODUCT"]["LABELS"]["XML_ID"] as $k=>$xml_id):?>
                                    <div class="mini-board <?=$xml_id?>" title="<?=$arResult["PRODUCT"]["LABELS"]["VALUE"][$k]?>"><?=$arResult["PRODUCT"]["LABELS"]["VALUE"][$k]?></div><br/>
                                <?endforeach;?>

                            </div>
                        
                        <?endif;?>

                    </div>

                </div>

                <?

                    $showBtnScroll2Chars = false;

                    if(
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_BTN_SCROLL2CHARS']["VALUE"]["ACTIVE"] == "Y" 
                        && (!empty($arResult['PRODUCT']["CHARS_SORT"]) || !empty($arResult["PRODUCT"]["FILES"]))
                        && $arResult["PRODUCT"]["SCROLL_TO_CHARS_OFF"] != "Y"

                    )
                        $showBtnScroll2Chars = true;

                ?>

                <?if($previewTextPos == "under_pic"):?>

                    <div id="<?=$itemIds["PREVIEW_TEXT"]?>">

                        <div class="wrapper-description under-pic-pos row no-margin">

                            <div class="col-12 detail-description" <?if($desc == 0):?>itemprop="description"<?endif;?>><?=$arResult["FIRST_ITEM"]["PREVIEW_TEXT_HTML"]?></div>

                            <?if($showBtnScroll2Chars || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>

                                <div class="col-12">

                                    <div class="row align-items-center">


                                        <?if($showBtnScroll2Chars):?>
                                            <div class="col-sm-6 col-12">
                                                <a href = "#chars" class="chars-link scroll info-style">
                                                    <span class="bord-bot">

                                                    <?=$arResult["PRODUCT"]["SKROLL_TO_CHARS_TITLE"]?>
                                                        
                                                    </span>
                                                </a>
                                            </div>
                                        <?endif;?>

                                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>

                                            <div class="col-sm-6 col-12">

                                                <?if($arResult["RATING_VIEW"] == "simple"):?>
                                                    
                                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arResult['ID'], "CLASS"=>"simple-rating hover"));?>



                                                <?elseif($arResult["RATING_VIEW"] == "full"):?>

                                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arResult['ID'], "VIEW"=>"rating-reviewsCount", "HREF" => "#rating-block"));?>

                                                <?endif;?>
                                            </div>
                                        <?endif;?>

                                    </div>

                                </div>

                            <?endif;?>


                        </div>

                    </div>

                <?endif;?>

                <?if( $arResult['PRODUCT']['CONFIG']['SHOW_DELAY'] == "Y" 
                      || $arResult['PRODUCT']['CONFIG']['SHOW_COMPARE'] == "Y" ):?>

                    <div class="wrapper-delay-compare-icons">

                        <?if($arResult['PRODUCT']['CONFIG']['SHOW_DELAY'] == "Y"):?>
                            <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DELAY_TITLE"]?>" class="icon delay add2delay" id = "<?=$itemIds["DELAY"]?>" data-item="<?=$arResult["ID"]?>"></div>
                        <?endif;?>


                        <?if($arResult['PRODUCT']['CONFIG']['SHOW_COMPARE'] == "Y"):?>
                            <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_COMPARE_TITLE"]?>" class="icon compare add2compare" id = "<?=$itemIds["COMPARE"]?>" data-item="<?=$arResult["ID"]?>"></div>
                        <?endif;?>
                    
                    </div>

                <?endif;?>

            </div>


            <div class="col-xl-5 col-sm-6 col-12 info-right-side <?=($arResult['PRODUCT']['CONFIG']['SHOW_PRICE_SIDE'])?'':'d-none';?>">  

                <div class="info-right-side-inner">
                    
                    <div class="wrapper-article-available row no-gutters justify-content-between" id="<?=$itemIds['ARTICLE_AVAILABLE']?>">
                            

                            <div class="detail-article italic <?if(strlen($arResult["FIRST_ITEM"]["ARTICLE"])<=0):?>d-none<?endif;?>"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arResult["FIRST_ITEM"]["ARTICLE"]?></div>

                            <div style="display: none;" itemprop="additionalProperty" itemscope="" itemtype="http://schema.org/PropertyValue">
                                <meta itemprop="name" content="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"]?>">
                                <meta itemprop="value" content="<?=addslashes($arResult["FIRST_ITEM"]["ARTICLE"])?>">
                            </div>
                            
                        
                        <div class="product-available-js hidden-js"><?=$arResult["FIRST_ITEM"]["QUANTITY"]["HTML"]?></div>
                    </div>



                    <?if($previewTextPos == "right"):?>

                        <div class="wrapper-description right-pos" id="<?=$itemIds["PREVIEW_TEXT"]?>">

                            <div class="detail-description" <?if($desc == 0):?>itemprop="description"<?endif;?>><?=$arResult["FIRST_ITEM"]["PREVIEW_TEXT_HTML"]?></div>

                        
    
                            <?if( $showBtnScroll2Chars ):?>
                                <a href = "#chars" class="chars-link scroll info-style">
                                    <span class="bord-bot">

                                        <?=$arResult["PRODUCT"]["SKROLL_TO_CHARS_TITLE"]?>
                                        
                                    </span>
                                </a>
                            <?endif;?>


                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>

                                <div class="board-rating-reviews row no-gutters align-items-center">
                                    <?if($arResult["RATING_VIEW"] == "simple"):?>
            
                                        <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arResult['ID'], "CLASS"=>"simple-rating hover"));?>

                                    <?elseif($arResult["RATING_VIEW"] == "full"):?>

                                        <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arResult['ID'], "VIEW"=>"rating-reviewsCount", "HREF" => "#rating-block"));?>

                                    <?endif;?>
                                </div>

                            <?endif;?>

                        </div>


                    <?endif;?>


                    <div class="wrapper-price-sku-props" id="actual_price">

                        <div class="wrapper-price block-price <?=($arResult["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arResult["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'd-none' : ''?>">

                            <?if( strlen($arResult['PRODUCT']["DESC_FOR_ACTUAL_PRICE"]) ):?>
                                <div class="desc-title"><?=$arResult['PRODUCT']["DESC_FOR_ACTUAL_PRICE"]?></div>
                            <?endif;?>

                           
                            <div class="name-type-price d-none">
                                <?=$arResult['FIRST_ITEM']['PRICE']['NAME']?>
                            </div>

                            <div class="board-price row no-gutters">

                                <div class="actual-price">
                                    <span class="price-value bold" id="<?=$itemIds['PRICE']?>"><?=$arResult["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?></span><span class="unit <?=(strlen($arResult["FIRST_ITEM"]['MEASURE_HTML'])>0 ? '' : 'd-none')?>" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$arResult["FIRST_ITEM"]['MEASURE_HTML']?></span>
                                </div>

                                <div class="old-price align-self-end product-old-price-value-js <?=($arResult["FIRST_ITEM"]["SHOW_DISCOUNT"] ? '' : 'd-none')?>" id="<?=$itemIds['OLD_PRICE']?>"><?=($arResult["FIRST_ITEM"]["SHOW_DISCOUNT"] ? $arResult["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE'] : '')?></div>

                            </div>


                            <div class="wrapper-discount-cheaper row no-gutters align-items-center">


                                <div id="<?=$itemIds['DISCOUNT_PRICE']?>"  class="wrapper-discount <?=($arResult["FIRST_ITEM"]["SHOW_DISCOUNT"] ? '' : 'd-none')?>">

                                    <span class="item-style desc-discount"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ECONOMY"]?></span><span class="item-style actual-econom bold product-price-discount-js"><?=$arResult["FIRST_ITEM"]['PRICE']['PRINT_DISCOUNT']?></span>
                                    
                                    <span id="<?=$itemIds['DISCOUNT_PERCENT']?>" class="item-style actual-discount product-percent-discount-js <?=( ($arResult["PRODUCT"]['CONFIG']["SHOW_DISCOUNT_PERCENT"] === 'Y' && $arResult["FIRST_ITEM"]['PRICE']['PERCENT']>0) ? '' : 'd-none')?>">
                                        <?=-$arResult["FIRST_ITEM"]['PRICE']['PERCENT']?>%
                                        
                                    </span>
                                </div>
                                
                            </div>
                            

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['BETTER_PRICE']["VALUE"] != "N"):?>
                               <span class="cheaper">
                                    <a id="<?=$itemIds['CALL_FORM_BETTER_PRICE']?>">
                                        <span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FOUND_CHEAPER"]?></span>
                                    </a>
                                </span>
                            <?endif;?>

                            <?//if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y'):?>
                            
                                <div class="wrapper-matrix-block" id= "<?=$itemIds["PRICE_MATRIX"]?>"><?=$arResult["FIRST_ITEM"]["PRICE_MATRIX_RESULT"]["HTML"]?></div>

                            <?//endif;?>


                        </div>


                        <?if( $arResult['PRODUCT']['CONFIG']["SHOW_OFFERS"] && $arResult['PRODUCT']['HAVEOFFERS'] ):?>

                            <div id="<?=$itemIds['TREE']?>" class="wrapper-skudiv">


                                <?if(!empty($arResult['PRODUCT']['SKU_PROPS'])):?>


                                    <?foreach ($arResult['PRODUCT']['SKU_PROPS'] as $skuProperty):?>
                                        
                                        <?

                                            $propertyId = $skuProperty['ID'];
                                            $descTitle = "";

                                            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic')
                                                $descTitle = "desc-title-with-prop-name";
                                        ?>


                                        <div class="wrapper-sku-props <?if( strlen($descTitle) ):?>with-desc<?endif;?> clearfix" >

                                            <div class="wrapper-title row no-gutters">
                                                <div class="desc-title"><?=htmlspecialcharsEx($skuProperty['NAME'])?><span class="prop-name"></span> 

                                                    <?if(strlen($skuProperty["HINT"])):?>
                                                        <i class="hint-sku fa fa-question-circle hidden-sm hidden-xs" data-toggle="sku-tooltip" data-placement="bottom" title="" data-original-title='<?=str_replace("'", "\"", $skuProperty["HINT"])?>'></i>
                                                    <?endif;?>
                                                </div>
                                                
                                            </div>

                                            <?if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic' || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic_with_info'):?>

                                                <ul class="sku-props clearfix">

                                                    <?if(!empty($skuProperty['VALUES'])):?>


                                                        <?foreach ($skuProperty['VALUES'] as $value):?>

                                                            <?
                                                                $styleTab = "";
                                                                $styleHoverBoard = "";

                                                                if(isset($value["PICT"]) || isset($value["PICT_SEC"]) )
                                                                {
                                                                    if(isset($value["PICT_SEC"]))
                                                                    {
                                                                        $styleHoverBoard .= "background-image: url('".$value['PICT_SEC']['BIG']."'); ";

                                                                        if(isset($value["PICT"]))
                                                                            $styleTab .= "background-image: url('".$value['PICT']['SMALL']."'); ";
                                                                        else
                                                                            $styleTab .= "background-image: url('".$value['PICT_SEC']['SMALL']."'); ";

                                                                    }

                                                                    else if(isset($value["PICT"]))
                                                                    {
                                                                        $styleTab .= "background-image: url('".$value['PICT']['SMALL']."'); ";
                                                                        $styleHoverBoard .= "background-image: url('".$value['PICT']['BIG']."'); ";
                                                                    }
                                                                }

                                                                if($value["COLOR"])
                                                                {
                                                                    $styleTab .= "background-color:".$value["COLOR"]."; ";
                                                                    $styleHoverBoard .= "background-color:".$value["COLOR"]."; ";
                                                                }
                                                            ?>


                                                                <li title='<?=CPhoenix::prepareText($value['NAME'])?>' class="detail-color"

                                                                        data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
                                                                        data-onevalue="<?=$value['ID']?>"
                                                                        

                                                                    >

                                                                    <div class="color" style="<?=$styleTab?>"></div>


                                                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic_with_info'):?>

                                                                        <div class="wrapper-hover-board">
                                                                            <div class="img" style="<?=$styleHoverBoard?>"></div>
                                                                            <div class="desc"><?=$value['NAME']?></div>
                                                                            <div class="arrow"></div>
                                                                        </div>

                                                                    <?endif;?>

                                                                    <span class="active-flag"></span>

                                                                </li>

                                                        <?endforeach;?>

                                                    <?endif;?>
                                                </ul>

                                            <?elseif($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'select'):?>

                                                <div class="wrapper-select-input">

                                                    <ul class="sku-props select-input">

                                                        <li class="area-for-current-value"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SKU_SELECT_TITLE"]?></li>

                                                        <?if(!empty($skuProperty['VALUES'])):?>

                                                            <?foreach ($skuProperty['VALUES'] as $value):?>
                                                                <li title='<?=CPhoenix::prepareText($value['NAME'])?>'

                                                                        data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
                                                                        data-onevalue="<?=$value['ID']?>"

                                                                    ><?=$value['NAME']?></li>
                                                            <?endforeach;?>

                                                        <?endif;?>
                                                       
                                                    </ul>

                                                    <div class="ar-down"></div>

                                                </div>


                                            <?else:?>

                                                <ul class="sku-props">

                                                    <?if(!empty($skuProperty['VALUES'])):?>

                                                        <?foreach ($skuProperty['VALUES'] as &$value):?>
                                                            <li title='<?=CPhoenix::prepareText($value['NAME'])?>' class="detail-text"

                                                                data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
                                                                data-onevalue="<?=$value['ID']?>"

                                                            ><?=$value['NAME']?></li>
                                                        <?endforeach;?>

                                                    <?endif;?>
                                                </ul>


                                            <?endif;?>
                                  
                                            
                                        </div>

                                    <?endforeach;?> 

                                <?endif;?>

                            </div>

                        <?endif;?>

                        <?if( isset($arResult["MODAL_WINDOWS"]) && !empty($arResult["MODAL_WINDOWS"]) || isset($arResult["FORMS"]) && !empty($arResult["FORMS"]) || isset($arResult["QUIZS"]) && !empty($arResult["QUIZS"]) ):?>

                            <div class="wrapper-modals-btn row no-gutters">

                                <?if( isset($arResult["MODAL_WINDOWS"]) && !empty($arResult["MODAL_WINDOWS"])):?>

                                    <?foreach ($arResult["MODAL_WINDOWS"] as $arModalWindow):?>

                                        <div class="modal-btn"><a class="call-modal callmodal" data-call-modal="modal<?=$arModalWindow["ID"]?>"><span class="bord-bot"><?=$arModalWindow["NAME"]?></span></a></div>

                                    <?endforeach;?>

                                <?endif;?>

                                <?if( isset($arResult["FORMS"]) && !empty($arResult["FORMS"])):?>

                                    <?foreach ($arResult["FORMS"] as $arForm):?>

                                        <div class="modal-btn"><a class="call-modal callform" data-call-modal="form<?=$arForm["ID"]?>"><span class="bord-bot"><?=$arForm["NAME"]?></span></a></div>

                                    <?endforeach;?>

                                <?endif;?>

                                <?if( isset($arResult["QUIZS"]) && !empty($arResult["QUIZS"])):?>

                                    <?foreach ($arResult["QUIZS"] as $arQuiz):?>

                                        <div class="modal-btn"><a class="call-wqec" data-wqec-section-id="<?=$arQuiz["ID"]?>"><span class="bord-bot"><?=$arQuiz["NAME"]?></span></a></div>

                                    <?endforeach;?>

                                <?endif;?>
                            </div>
                            
                        <?endif;?>


                        <div class="wrapper-quantity quantity-block hidden-js" data-item="<?=$arResult["FIRST_ITEM"]['ID']?>">

                            <div class="wrapper-title">

                                <?if($arResult["TITLE_FOR_QUANTITY"]):?>
                                    <div class="desc-title">

                                        <?=$arResult["TITLE_FOR_QUANTITY"]?>

                                        <?if( strlen($arResult["TITLE_FOR_QUANTITY_HINT"]) ):?>
                                            <i class="ic-hint fa fa-question-circle hidden-sm hidden-xs" data-toggle="tooltip" data-placement="right" title="<?=CPhoenix::prepareText($arResult["TITLE_FOR_QUANTITY_HINT"])?>"></i>
                                        <?endif;?>

                                    </div>
                                <?endif;?>

                                
                            </div>

                         
                            <div class="wrapper-quantity-total row no-gutters align-items-center">

                                <div class="col-6">

                                    <div class="quantity-container row no-gutters align-items-center justify-content-between">
                                        <span class="product-item-amount-field-btn-minus" id="<?=$itemIds['QUANTITY_DOWN']?>">&minus;</span>
                                        <input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number"
                                            value="<?=$arResult["FIRST_ITEM"]["PRICE"]["MIN_QUANTITY"]?>">
                                        <span class="product-item-amount-field-btn-plus" id="<?=$itemIds['QUANTITY_UP']?>">&plus;</span>
                                    </div>
                                </div>

                                <div class="col-6">

                                    <div class="total-container d-none" id="<?=$itemIds['PRICE_TOTAL']?>">
                                        <span class="desc-total"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRICE_TOTAL_PREFIX"]?></span><span class="total-value bold" title=''></span>
                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="wrapper-btns row no-gutters justify-content-between hidden-js" id="<?=$itemIds["BASKET_ACTIONS"]?>">

                            <div class="col-6 left-btn wr-btn-basket d-none">
                                <a 
                                id = "<?=$itemIds['ADD2BASKET']?>"
                                href="javascript:void(0);" 
                                title = "<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]?>" 
                                data-item="<?=$arResult['ID']?>"
                                class="main-color bold add-to-cart-style add2basket" ><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]?></a>

                                <a 
                                id = "<?=$itemIds['MOVE2BASKET']?>"
                                href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>" 
                                title = "<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?>"
                                data-item = "<?=$arResult["ID"]?>"

                                style = "display: none;"

                                class="bold added-to-cart-style move2basket"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?></a>
                            </div>

                            <div class="col-6 right-btn wr-btn-fast-order d-none">
                                <a 
                                    id="<?=$itemIds['FAST_ORDER']?>"
                                    title = "<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_CATALOG_DETAIL"]["VALUE"]?>" 
                                    href="javascript:void(0);"
                                    class="fast-order second-btn-style"
                                >
                                <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_CATALOG_DETAIL"]["~VALUE"]?>
                                    
                                </a>
                            </div>

                        </div>



                        <div class="hidden-js <?=($arResult["FIRST_ITEM"]["SHOWPREORDERBTN"])?"":"d-none"?>">

                            <a id="<?=$itemIds['PREORDER']?>"
                                title = "<?=CPhoenix::prepareText($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_BTN_NAME"]["VALUE"])?>"
                                class="btn-transpatent detail-btn-preorder"
                            ><span class="icon-load"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_BTN_NAME"]["~VALUE"]?></span></a>
                        </div>

                        <?if($arResult["SHOW_SET_PRODUCT"]):?>
                            <a href="#set_product" class="btn-transpatent ic-info scroll"><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_PRODUCT_BTN_NAME_2"]?></span></a>
                        <?endif;?>

                        <div id="<?=$itemIds['SUBSCRIBE']?>">
                            <?
                                $APPLICATION->IncludeComponent(
                                    'bitrix:catalog.product.subscribe',
                                    'main',
                                    array(
                                        'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
                                        'PRODUCT_ID' => $arResult['ID'],
                                        'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                                        'BUTTON_CLASS' => 'btn-transpatent product-item-detail-buy-button',
                                        'DEFAULT_DISPLAY' => !$arResult["FIRST_ITEM"]['CAN_BUY'],
                                        'MESS_BTN_SUBSCRIBE' => "",
                                    ),
                                    $component,
                                    array('HIDE_ICONS' => 'Y')
                                );
                            ?>
                        </div>

                        <?CPhoenix::createBtnHtml($arResult["BTN"])?>


                        <?if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['COMMENT_FOR_DETAIL_CATALOG']['VALUE']) ):?>

                            <div class="comment-detail-catalog"><span><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['COMMENT_FOR_DETAIL_CATALOG']['~VALUE']?></span></div>

                        <?endif;?>

                        <div class="d-md-none catalog-detail-back">
                            <a href="<?=$arResult["SECTION"]["SECTION_PAGE_URL"]?>" class="back-url-js">
                                <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CARD_LEVEL_UP"]?>
                            </a>
                        </div>

                    </div>


                </div>


            </div>


        </div>

    </div>



</div>



<?if($arResult['PRODUCT']['CONFIG']["SHOW_OFFERS"]):?> 
                    
    <div itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer" style="display:none;">

        <?$prices = array();?>
        
        <?foreach($arResult['PRODUCT']['OFFERS'] as $key=>$arOffer):?>
           

            <?$currency = $arOffer["PRICE"]["CURRENCY"];?>
        
            <?if($arOffer["PRICE"]["PRICE"] > 0):?>
                <?$prices[] = $arOffer["PRICE"]["PRICE"];?>
            <?endif;?>


            <div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer" style="display:none;">

                <a itemprop="url" href="<?=$arOffer["URL"]?>"></a>
                <meta itemprop="price" content="<?=$arOffer["PRICE"]["PRICE"]?>">
                <meta itemprop="priceCurrency" content="<?=$arOffer["PRICE"]["CURRENCY"]?>">

                <?if(strlen($arOffer["ITEMPROP_AVAILABLE"])>0):?>
                    <link itemprop="availability" href="http://schema.org/<?=$arOffer["ITEMPROP_AVAILABLE"]?>">
                <?endif;?>

            </div>

        <?endforeach;?>

        <?if(empty($prices)):?>
            <?$prices = Array("0");?>
        <?endif;?>

        <meta itemprop="offerCount" content="<?=count($arResult["OFFERS"])?>">
        <meta itemprop="lowPrice" content="<?=min($prices)?>">
        <meta itemprop="highPrice" content="<?=max($prices)?>">
        <meta itemprop="priceCurrency" content="<?=$currency?>">

    </div>

<?else:?>
    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" style="display:none;">

        <a itemprop="url" href="<?=$arResult["DETAIL_PAGE_URL"]?>"></a>

        <?if(strlen($arResult["PRODUCT"]["ITEMPROP_AVAILABLE"])>0):?>
            <link itemprop="availability" href="http://schema.org/<?=$arResult["PRODUCT"]["ITEMPROP_AVAILABLE"]?>">
        <?endif;?>

        <span itemprop="price" content="<?=$arResult["FIRST_ITEM"]['PRICE']['PRICE']?>"></span>
        <span itemprop="priceCurrency"content="<?=$arResult["FIRST_ITEM"]['PRICE']['CURRENCY']?>"></span>

    </div>

<?endif;?>


<?if ($arResult['CATALOG'] && \Bitrix\Main\ModuleManager::isModuleInstalled('sale') && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SHOW_PREDICTION"]["VALUE"]["ACTIVE"] == "Y"):?>

    <div class="col-12">
        <div class="prediction-text d-none row align-items-center">
            <div class="col-auto"><div class="board-description"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PREDICTION_DESCRIPTION"]?></div></div>
            <div class="col area-text"></div>
        </div>
    </div>
    <?
        $APPLICATION->IncludeComponent(
            'bitrix:sale.prediction.product.detail',
            'main',
            array(
                'BUTTON_ID' => $arResult["SHOW_BUY_BTN_OPTION"] ? $itemIds['BUY_LINK'] : $itemIds['ADD_BASKET_LINK'],
                'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
                'POTENTIAL_PRODUCT_TO_BUY' => array(
                    'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
                    'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
                    'PRODUCT_PROVIDER_CLASS' => isset($arResult['~PRODUCT_PROVIDER_CLASS']) ? $arResult['~PRODUCT_PROVIDER_CLASS'] : '\Bitrix\Catalog\Product\CatalogProvider',
                    'QUANTITY' => isset($arResult['MAX_QUANTITY']) ? $arResult['MAX_QUANTITY'] : null,
                    'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

                    'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID']) ? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'] : null,
                    'SECTION' => array(
                        'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
                        'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
                        'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
                        'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
                    ),
                )
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );
    ?>

<?endif;?>




<?if($arParams["SHOW_STORE_BLOCK"] && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORES_ID"])):?>

    <?
        $tab_active = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_BLOCK_VIEW"]["VALUE"];
    ?>

    <?$frame = $this->createFrame()->begin(); ?>

    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_BLOCK_VIEW"]["VALUE"] == "popup"):?>
        <?$tab_active = "list";?>

        <div class="popup-block d-none" data-popup = "store-block">
            <div class="popup-shadow-tone hide-popup-block-js"></div>
            <div class="container popup-block-inner">
                <a class="hide-popup-block hide-popup-block-js"></a>

    <?endif;?>

        <input type="hidden" class="tab-store" value="<?=$tab_active?>">

        <div class="position-relative">

            <div class="preloader-item" data-preload = "store_block"></div>

            <div class="ajax_store_area">

                
                <?/*if(!empty($arResult["FIRST_ITEM"]["STORE"])):?>
                
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:catalog.store.amount",
                        "main",
                        Array(
                            "CACHE_TIME" => "36000",
                            "CACHE_TYPE" => "A",
                            "COMPOSITE_FRAME_MODE" => "N",
                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                            "ELEMENT_CODE" => "",
                            "ELEMENT_ID" => $arResult["FIRST_ITEM"]["ID"],
                            "FIELDS" => array("TITLE", "ADDRESS", "DESCRIPTION", "PHONE", "EMAIL", "COORDINATES", "SCHEDULE", ""),
                            "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
                            "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_TYPE"],
                            "MAIN_TITLE" => "",
                            "MIN_AMOUNT" => "0",
                            "OFFER_ID" => $arResult["FIRST_ITEM"]["ID"],
                            "SHOW_EMPTY_STORE" => "",
                            "SHOW_GENERAL_STORE_INFORMATION" => "N",
                            "STORES" => $arResult["FIRST_ITEM"]["STORE"],
                            "STORE_PATH" => "",
                            "USER_FIELDS" => array("", ""),
                            "USE_MIN_AMOUNT" => "N",
                            "MEASURE"=>$arResult["FIRST_ITEM"]["MEASURE"],
                            "STORE_QUANTITY" => $arResult["FIRST_ITEM"]["STORE"],
                            "TAB" => $tab_active
                        )
                    );?>

                <?endif;*/?>

            </div>
            
        </div>

    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_BLOCK_VIEW"]["VALUE"] == "popup"):?>
            </div>
        </div>
    <?endif;?>

    <?$frame->end();?>

<?endif;?>



<?if(!empty($arResult["BLOCK_SORT"])):?>
    <?foreach($arResult["BLOCK_SORT"] as $main_key=>$val):?>


        <?if($main_key == "set_product_constructor" && $arResult["PROPERTIES"]['SHOW_BLOCK_SET_PRODUCT_CONSTRUCTOR']["VALUE"] == "Y" && $arResult['PRODUCT']['CONFIG']['OFFER_GROUP']):?>
        
            <div id="set_product_constructor"></div>



            <?
                $params = array(
                    'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
                    'PRICE_CODE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
                    'BASKET_URL' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"],
                    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                    'CACHE_TIME' => $arParams['CACHE_TIME'],
                    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                    'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
                    'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
                    'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
                    'HIDELINE_BLOCK_SET_PRODUCT_CONSTRUCTOR_OTHER'=> $arResult["PROPERTIES"]['HIDELINE_BLOCK_SET_PRODUCT_CONSTRUCTOR_OTHER']["VALUE"],
                    'TITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR_OTHER'=> $arResult["PROPERTIES"]['TITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR_OTHER']["~VALUE"]
                );
            ?>

            <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"] != "Y"):?>

                <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                    
                    <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["VALUE"]) > 0):?>
                        <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK_SET_PRODUCT_CONSTRUCTOR"]["~VALUE"]?></div>
                    <?endif;?>
                    
                    <div class="line"></div>
                    
                </div>
            
            <?endif;?>

            <?
                if ($arResult['PRODUCT']['CONFIG']["SHOW_OFFERS"])
                {
                    
                    foreach ($arResult['PRODUCT']['CONFIG']['OFFER_GROUP_VALUES'] as $offerId)
                    {?>
                        <span id="<?=$itemIds['OFFER_GROUP'].$offerId?>" style="display: none;">

                            <?
                                $params["DETAIL_URL"] = $arParams['~DETAIL_URL'];
                                $params["OFFERS_CART_PROPERTIES"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'];
                                $params["IBLOCK_ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"];
                                $params["ELEMENT_ID"] = $offerId;
                                $params["DETAIL_URL"] = $arParams['~DETAIL_URL'];

                                $APPLICATION->IncludeComponent('bitrix:catalog.set.constructor', 'main', $params,
                                    $component,
                                    array('HIDE_ICONS' => 'Y')
                                );

                            ?>
                        </span>

                    <?
                    }
                   
                }
                else
                {
                    
                    $params["IBLOCK_ID"]=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"];
                    $params["ELEMENT_ID"]=$arResult['ID'];
                    $APPLICATION->IncludeComponent('bitrix:catalog.set.constructor', 'main',$params,
                        $component,
                        array('HIDE_ICONS' => 'Y')
                    );

                }
            ?>

        <?endif;?>


        <?if($main_key == "set_product" && $arResult["SHOW_SET_PRODUCT"]):?>

            <div class="cart-block " id="set_product">
                
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK_SET_PRODUCT"]["VALUE"] != "Y"):?>
                
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK_SET_PRODUCT"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                        
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK_SET_PRODUCT"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK_SET_PRODUCT"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>
                        
                    </div>
                
                <?endif;?>


                <div class="row">


                    <?foreach ($arResult["SET_ITEMS"] as $key => $arSet):?>

                        <div class="col-md-3 col-6 wr-product-item">
                            <div class="product-item flat">
                                <div class="wr-img row no-gutters align-items-center">
                                    <div class="col"><img class="d-block mx-auto lazyload" data-src="<?=$arSet["PREVIEW_PICTURE_SRC"]?>" alt=""></div>
                                    <div class="plus-label">+</div>
                                </div>
                                <a class="name" href="<?=$arSet["DETAIL_PAGE"]?>" target="_blank"><?=$arSet["NAME"]?></a>
                                <?if(strlen($arSet["NAME_OFFERS"])>0):?>
                                    <div class="sku"><?=$arSet["NAME_OFFERS"]?></div>
                                <?endif;?>
                                
                                <div class="measure-label"><?=$arSet["MEASURE"]?></div>
                                <?

                                    CPhoenix::admin_setting_custom(array(
                                        "CLASS" => "",
                                        "IBLOCK_TYPE_ID"=>$arSet["IBLOCK_TYPE_ID"],
                                        "IBLOCK_ID"=> $arSet["IBLOCK_ID"],
                                        "ID"=>$arSet["ID"],
                                        "IBLOCK_SECTION_ID"=>$arSet["IBLOCK_SECTION_ID"],
                                        "NAME"=>$arSet["NAME"]
                                    ), false);
                                ?>

                                <?
                                    if($arSet["IS_OFFER"]=="Y")
                                    {
                                        CPhoenix::admin_setting_custom(array(
                                            "CLASS" => "second",
                                            "IBLOCK_TYPE_ID"=>$arSet["IBLOCK_TYPE_ID_MAIN"],
                                            "IBLOCK_ID"=> $arSet["IBLOCK_ID_MAIN"],
                                            "ID"=>$arSet["ID_MAIN"],
                                            "IBLOCK_SECTION_ID"=>$arSet["IBLOCK_SECTION_ID_MAIN"],
                                            "NAME"=>$arSet["NAME"]."&nbsp;(".$PHOENIX_TEMPLATE_ARRAY["MESS"]["SETTING_PRODUCT_MAIN_TITLE"].")"
                                        ), false);
                                    }
                                ?>
                            </div>
                        </div>


                    <?endforeach;?>

                </div>
                
            </div>
        
        <?endif;?>   

        
        <?if($main_key == "advantages" && isset($arResult["ADVANTAGES"]) && !empty($arResult["ADVANTAGES"])):?>

            <div class="cart-block " id="advantages">
                
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK2"]["VALUE"] != "Y"):?>
                
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK2"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                        
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK2"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK2"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>
                        
                    </div>
                
                <?endif;?>
                
                <div class="cart-advantage">
                    <div class="row">

                        <?
                            $total_count = count($arResult["ADVANTAGES"]);

                            $class = "col-lg-3 col-sm-4 col-12";
                            
                            if($total_count%3 == 0)
                                $class = "col-sm-4 col-12";
                            
                            
                            if($total_count == 2)
                                $class = "col-sm-6 col-12";
                            
                            
                            if($total_count == 1)
                                $class = "col-12";
                            
                        ?>

                        <?foreach($arResult["ADVANTAGES"] as $key=>$arAdv):?>

                        

                            <div class="<?=$class?>">
                                <table class="size-<?=$arAdv["PROPERTIES"]["SIZE"]["VALUE_XML_ID"]?>">
                                    <tr>
                                    
                                        <td class="img">
                                        
                                            <?if($arAdv["PREVIEW_PICTURE_SRC"]):?>
                                                <img data-src="<?=$arAdv["PREVIEW_PICTURE_SRC"]?>" class="d-block mx-auto img-fluid lazyload"  alt=""/>

                                            <?elseif(strlen($arAdv["PROPERTIES"]["ICON"]["VALUE"]) && $arAdv["PREVIEW_PICTURE"] <= 0):?>
                     
                                                <div class="icon">
                                                    <i class="<?=$arAdv["PROPERTIES"]["ICON"]["VALUE"]?>" <?if(strlen($arAdv["PROPERTIES"]["ICON"]["DESCRIPTION"]) > 0):?>style="color: <?=$arAdv["PROPERTIES"]["ICON"]["DESCRIPTION"]?>;"<?endif;?>></i>
                                                </div>
                                                
                                            <?else:?>
                                                <div class="icon default"></div>
                                            <?endif;?>
                                            
                                        </td>
                                        
                                        <td class='text'><?=$arAdv["PROPERTIES"]["SIGN"]["~VALUE"]?></td>
                                        
                                    </tr>
                                </table>

                                <?CPhoenix::admin_setting($arAdv, false)?>
                            </div>

                        <?endforeach;?>
                        
        
                    </div>
                </div>
                
            </div>
        
        <?endif;?>   
        
        
        
        <?if(  $main_key == "chars" && (!empty($arResult['PRODUCT']["CHARS_SORT"]) || !empty($arResult["PROPERTIES"]["FILES"]["VALUE"]))):?>

         
            <div class="cart-block " id='chars'>
                
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK3"]["VALUE"] != "Y"):?>
                
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK3"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                        
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK3"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK3"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>
                    </div>
                    
                <?endif;?>

                <div class="cart-char <?=$arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"]?>">
                    <div class="row">

                        <?

                            $charsColLeft = "col-12";
                            $charsColRight = "col-12";

                            if($arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] == "col-one")
                            {
                           
                                if( (!empty($arResult['PRODUCT']["CHARS_SORT"]) || ($arResult['PRODUCT']['CONFIG']["SHOW_OFFERS"] && $arResult['PRODUCT']['HAVEOFFERS'])) && !empty($arResult["PROPERTIES"]["FILES"]["VALUE"]))
                                {
                                    $charsColLeft = "col-sm-8 col-12";
                                    $charsColRight = "col-sm-4 col-12";
                                }
                            }


                            $countChars = 0;
                            $countBreak = intval($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CHARS_SHOW_COUNT']['VALUE']);

                        ?>
                    
                        <div class="<?=$charsColLeft?> cart-char-col-left">
    
                            <div class="cart-char-table-wrap show-hidden-parent">
                                

                                <?foreach ($arResult['PRODUCT']["CHARS_SORT"] as $keyChar => $valueChar):?>
                                    <?if($keyChar == "sku_chars"):?>

                                        <div class="sku-chars">

                                            <?if($arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] == "cols-two"):?>
                                                <div class="row">
                                                    <div class="col-sm-6 col-12">
                                            <?endif;?>

                                                <div id = "<?=$itemIds["SKU_CHARS"]?>"></div>

                                            <?if($arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] == "cols-two"):?>
                                                    </div>
                                                </div>
                                            <?endif;?>

                                        </div>


                                    <?elseif($keyChar == "props_chars"):?>

                                        <?if($arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] == "cols-two"):?>
                                            <div class="row">
                                        <?endif;?>

                                        <?if(!empty($arResult['PRODUCT']['PROPS_CHARS'])):?>
                    
                                            <?foreach($arResult['PRODUCT']['PROPS_CHARS'] as $key=>$value):?>


                                                <?if($arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] == "cols-two"):?>
                                                    <div class="col-sm-6 col-12">
                                                <?endif;?>

                                                <table class='cart-char-table mobile-break show-hidden-child <?if($countChars >= $countBreak):?>hidden<?endif;?>' itemprop="additionalProperty" itemscope="" itemtype="http://schema.org/PropertyValue">
                                                    <tbody>
                                                        <tr>
                                                            <td class="left"><span itemprop="name"><?=$value["NAME"]?></span>
                                                                <?if(strlen($value["HINT"])):?>
                                                                    <i class="hint-prop fa fa-question-circle hidden-sm hidden-xs" data-toggle="prop-tooltip" data-placement="bottom" title="" data-original-title='<?=str_replace("'", "\"", $value["HINT"])?>'></i>
                                                                <?endif;?>

                                                            </td>
                                                            
                                                            <td class="dotted">
                                                                <div class="dotted"></div>
                                                            </td>
                                                            
                                                            <td class="right bold" itemprop="value"><?=$value["VALUE"]?></td>
                                                        </tr>
                                                    </tbody>

                                                </table>

                                                <?if($arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] == "cols-two"):?>
                                                    </div>
                                                <?endif;?>


                                                <?$countChars++?>
                                    
                                            <?endforeach;?>

                                        <?endif;?>


                                        <?if($arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] == "cols-two"):?>
                                            </div>
                                        <?endif;?>


                                    <?elseif($keyChar == "prop_chars"):?>

                                        <?if($arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] == "cols-two"):?>
                                            <div class="row">
                                        <?endif;?>


                                        <?if(!empty($arResult['PRODUCT']['PROP_CHARS'])):?>
                    
                                            <?foreach($arResult['PRODUCT']['PROP_CHARS'] as $key=>$value):?>


                                                <?if($arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] == "cols-two"):?>
                                                    <div class="col-sm-6 col-12">
                                                <?endif;?>

                                                <table class='cart-char-table mobile-break show-hidden-child <?if($countChars >= $countBreak):?>hidden<?endif;?>'>
                                                    <tbody>
                                                        <tr>
                                                            <td class="left"><?=$value["NAME"]?>
                                                                

                                                                <?if(strlen($value["HINT"])):?>
                                                                    <i class="hint-prop fa fa-question-circle hidden-sm hidden-xs" data-toggle="prop-tooltip" data-placement="bottom" title="" data-original-title='<?=str_replace("'", "\"", $value["HINT"])?>'></i>
                                                                <?endif;?>

                                                            </td>
                                                            
                                                            <td class="dotted">
                                                                <div class="dotted"></div>
                                                            </td>
                                                            
                                                            <td class="right bold">
                                                                <?=$value["VALUE"]?></td>
                                                        </tr>
                                                    </tbody>

                                                </table>

                                                <?if($arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] == "cols-two"):?>
                                                    </div>
                                                <?endif;?>


                                                <?$countChars++?>
                                    
                                            <?endforeach;?>

                                        <?endif;?>



                                        <?if($arResult["UF_CHARS_VIEW_ENUM"]["XML_ID"] == "cols-two"):?>
                                            </div>
                                        <?endif;?>


                                    <?endif;?>


                                <?endforeach;?>

                            

    
                                <?if($countChars > $countBreak):?>
                                    
                                    <div class="show-hidden-wrap">
                                        <a class="style-scroll-ar-down show-hidden"><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL"]?></span></a>
                                    </div>
                                
                                <?endif;?>
                                
    
                            </div> 
                        </div>
                        
                      
                        
                        <?if(!empty($arResult["PROPERTIES"]["FILES"]["VALUE"])):?>

                            <?
                                $countFiles = 0;
                                $countBreak = intval($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILES_SHOW_COUNT']['VALUE']);
                            ?>
                        
                            <div class="<?=$charsColRight?> cart-char-col-right show-hidden-parent">

                                <div class="files-list">
                            
                                    <?foreach($arResult["PROPERTIES"]["FILES"]["VALUE"] as $k=>$file):?>

                                        <div class="item show-hidden-child <?if($countFiles >= $countBreak):?>hidden<?endif;?>">
                                            
                                            <a target="_blank" href="<?=CFile::GetPath($file)?>">
               
                                                <div class="row">

                                                    <div class="col-auto align-self-start wr-icon">
                                                        <div class="icon"></div>
                                                    </div>

                                                    <?if(strlen($arResult["PROPERTIES"]["FILES_DESC"]["VALUE"][$k])>0):?>

                                                        <div class="col align-self-center wr-desc">
                                                            <div class="desc">
                                                                <?=$arResult["PROPERTIES"]["FILES_DESC"]["VALUE"][$k]?>
                                                            </div>

                                                            <?if(strlen($arResult["PROPERTIES"]["FILES_DESC"]["DESCRIPTION"][$k])>0):?>

                                                                <div class="subdesc">
                                                                    <?=$arResult["PROPERTIES"]["FILES_DESC"]["DESCRIPTION"][$k]?>
                                                                </div>

                                                            <?endif;?>
                                                            
                                                        </div>
                                                    <?endif;?>
                                                </div>
                                            
                                            </a>
                                        </div>

                                        <?$countFiles++;?>
                                    
                                    <?endforeach;?>
                                    
                                </div>

                                <?if($countFiles > $countBreak):?>
                                    
                                    <div class="show-hidden-wrap">
                                        <a class="style-scroll-ar-down show-hidden"><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL"]?></span></a>
                                    </div>
                                
                                <?endif;?>
                                
                            </div>
                        
                        <?endif;?>
                        
                    </div>
                </div>
                
            </div>
        
        <?endif;?>
        
        <?if($main_key == "video" && !empty($arResult["PROPERTIES"]["VIDEO"]["VALUE"])):?>          

            <div class="cart-block clerfix" id='video'>
            
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK4"]["VALUE"] != "Y"):?>
            
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK4"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                    
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK4"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK4"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>
                    
                    </div>
                    
                <?endif;?>
        
                <div class="cart-video">

                    
                    <?foreach($arResult["PROPERTIES"]["VIDEO"]["VALUE"] as $key=>$video):?>

                        <div class="cart-video-item row video-start-parent">

                            <img class="lazyload img-for-lazyload video-start-js" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png">
        
                            <div class="<?if(strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["VALUE"][$key]) > 0 || strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["DESCRIPTION"][$key]) > 0):?>col-lg-8 col-12<?else:?>col-12<?endif;?>">
                            
                                <div class="videoframe-wrap <?if(strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["VALUE"][$key]) > 0 || strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["DESCRIPTION"][$key]) > 0):?>right-col<?endif;?>">

                                    <?$iframe = CPhoenix::createVideo($video);?>

                                    <div class="iframe-video-area" data-src="<?=htmlspecialcharsbx($iframe["HTML"])?>"></div>
        
                                    <?/*<iframe width="100%" height="500" src="https://www.youtube.com/embed/<?=$iframe["ID"]?>" frameborder="0" allow="autoplay" allowfullscreen></iframe>*/?>
                                </div>
                                
                            </div>
                            
                            
                            <?if(strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["VALUE"][$key]) > 0 || strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["DESCRIPTION"][$key]) > 0):?>
                            
                                <div class="col-lg-4 col-12">
            
                                    <div class="video-text text-content">
                                        
                                        <?if(strlen(trim($arResult["PROPERTIES"]["VIDEO_DESC"]["VALUE"][$key])) > 0):?>
                                            <h4><?=$arResult["PROPERTIES"]["VIDEO_DESC"]["VALUE"][$key]?></h4>
                                        <?endif;?>
            
                                        <?if(strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["DESCRIPTION"][$key]) > 0):?>
                                            <p><?=$arResult["PROPERTIES"]["VIDEO_DESC"]["DESCRIPTION"][$key]?></p>
                                        <?endif;?>
                                        
                                    </div>
                                    
                                </div>
                            
                            <?endif;?>
        
                        </div>
                    
                    <?endforeach;?>
        
                </div>
                
            </div>
            
        <?endif;?>
        
        
        <?if($main_key == "similar" && isset($arResult["SIMILAR"]) && !empty($arResult["SIMILAR"]) ):?>
            
            <div class="cart-block universal-parent-slider universal-mobile-arrows" id='similar'>
                
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK5"]["VALUE"] != "Y"):?>
                
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK5"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                    
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK5"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK5"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>

                        
                        <div class="universal-arrows-mini wr-arrows-slick d-none">
                            <div class="arrow-next"></div>
                            <div class="arrow-prev"></div>
                        </div>
                        
                    </div>
                
                <?endif;?>

                <div class="cart-catalog-list-wrap">
                    <?
                        $GLOBALS['arFilterElementSimilar'] = array("ID" => $arResult["SIMILAR"]);

                        CPhoenixFunc::setInitialFilterParams('arFilterElementSimilar');


                        $intSectionID = $APPLICATION->IncludeComponent(
                            "bitrix:catalog.section",
                            "slider",
                            array(
                                "BLOCK_ID"=> "similar",
                                "BTNS_ACTIVE" => ($arResult["PROPERTIES"]["HIDELINE_BLOCK13"]["VALUE"] == "Y")? "N": "Y",
                                "COMPOSITE_FRAME_MODE" => "Y",
                                "FILTER_NAME" => "arFilterElementSimilar",
                                "OBJ_NAME" => "similar_".$arResult["ID"],
                                "PAGE_ELEMENT_COUNT" => $arResult["PROPERTIES"]["SIMILAR_MAX_QUANTITY"]["VALUE"],

                                'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
                                'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
                                "PAGER_TEMPLATE" => "phoenix_round",
                                "VIEW" => "FLAT SLIDER",
                                "COLS" => "3",
                                "COLS_LG" => "2",
                                "ELEMENT_SORT_FIELD" => "RAND",
                                "ELEMENT_SORT_ORDER" => "ASC",
                                "ELEMENT_SORT_FIELD2" => "RAND",
                                "ELEMENT_SORT_ORDER2" => "ASC",
                                'HIDE_NOT_AVAILABLE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"],
                                'HIDE_NOT_AVAILABLE_OFFERS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"],
                                "COMPONENT_TEMPLATE" => "main",
                                "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_TYPE"],
                                "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
                                "PRICE_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
                                "OFFERS_CART_PROPERTIES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                "OFFERS_PROPERTY_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                'OFFER_TREE_PROPS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                "OFFERS_FIELD_CODE" => array("NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE",""),

                                "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                                "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                                
                                "OFFERS_SORT_FIELD" => "sort",
                                "OFFERS_SORT_ORDER" => "id",
                                "OFFERS_SORT_FIELD2" => "asc",
                                "OFFERS_SORT_ORDER2" => "asc",
                                "OFFERS_LIMIT" => "0",

                                "USE_PRICE_COUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == "Y" ? "Y" : "N",
                                "USE_PRODUCT_QUANTITY" => "Y",
                                "SHOW_PRICE_COUNT" => "1",
                                'SHOW_OLD_PRICE' => "Y",
                                'SHOW_MAX_QUANTITY' => "Y",
                                "PRICE_VAT_INCLUDE" => "Y",
                                'SHOW_DISCOUNT_PERCENT' => "Y",

                                "ACTION_VARIABLE" => "action",
                                "PRODUCT_ID_VARIABLE" => "id",
                                "SECTION_ID_VARIABLE" => "SECTION_ID",
                                "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                                "PRODUCT_PROPS_VARIABLE" => "prop",
                                "CACHE_TYPE" => "N",
                                "CACHE_FILTER" => "Y",
                                "CACHE_TIME" => "10",
                                "CACHE_GROUPS" => "Y",
                                "SET_LAST_MODIFIED" => "N",
                                "DISPLAY_TOP_PAGER" => "N",
                                "DISPLAY_BOTTOM_PAGER" =>  "N",
                                "PAGER_SHOW_ALL" => "N",
                                "ADD_PROPERTIES_TO_BASKET" => "Y",
                                "PARTIAL_PRODUCT_PROPERTIES" => "N",
                                "PAGER_SHOW_ALWAYS" => "N",
                                
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "LAZY_LOAD" => "N",
                                "LOAD_ON_SCROLL" => "N",
                                "USE_MAIN_ELEMENT_SECTION" => "N",
                                'PRODUCT_DISPLAY_MODE' => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                'PRODUCT_SUBSCRIPTION' => "N",
                                'ENLARGE_PRODUCT' => "STRICT",
                                'COMPARE_NAME' => "CATALOG_COMPARE_LIST",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",

                                "INCLUDE_SUBSECTIONS" => "Y",
                                "SHOW_ALL_WO_SECTION" => "Y",
                                "SET_TITLE" => "Y",
                                "SET_STATUS_404" => "Y",
                                "SHOW_404" => "Y",
                                'ADD_PICT_PROP' => "-",
                                'OFFER_ADD_PICT_PROP' => "-",
                                "META_KEYWORDS" => "-",
                                "META_DESCRIPTION" => "-",
                                "BROWSER_TITLE" => "-",
                                
                                'USE_ENHANCED_ECOMMERCE' => '',
                                'ADD_TO_BASKET_ACTION' => "",
                                'SHOW_CLOSE_POPUP' => "",
                                'COMPARE_PATH' => "",
                                'DISCOUNT_PERCENT_POSITION' => '',
                                'ENLARGE_PROP' => '',
                                'SHOW_SLIDER' => "",
                                'SLIDER_INTERVAL' => '',
                                'SLIDER_PROGRESS' => '',
                                'PRODUCT_BLOCKS_ORDER' => "",
                                'PRODUCT_ROW_VARIANTS' => "",
                                "SECTION_ID" => "",
                                "SECTION_CODE" => "",
                                "PRODUCT_PROPERTIES" => "",
                                "PAGER_TITLE" => "",
                                "CUSTOM_FILTER" => "",
                                "FILE_404" => "",
                                "LINE_ELEMENT_COUNT" => "",
                                "BASKET_URL" => "",
                                "PROPERTY_CODE" => array(),
                                "PROPERTY_CODE_MOBILE" => array(),
                                'LABEL_PROP' => array(),
                                
                            ),
                            $component
                        );
                      
                    ?>

                </div>
                
            </div>
        
        <?endif;?>

        <?if($main_key == "accessory" && !empty($arResult["ACCESSORY"]) ):?>
            
            <div class="cart-block universal-parent-slider universal-mobile-arrows" id='accessory'>
                
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK13"]["VALUE"] != "Y"):?>

                
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK13"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                    
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK13"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK13"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>

                      
                        <div class="universal-arrows-mini wr-arrows-slick d-none">
                            <div class="arrow-next"></div>
                            <div class="arrow-prev"></div>
                        </div>
                    

                    </div>
                
                <?endif;?>

                <div class="cart-catalog-list-wrap">
                    <?
                        $GLOBALS['arFilterElementAccessory'] = array("ID" => $arResult["ACCESSORY"]);
                        CPhoenixFunc::setInitialFilterParams('arFilterElementAccessory');
                        $intSectionID = $APPLICATION->IncludeComponent(
                            "bitrix:catalog.section",
                            "slider",
                            array(
                                "BLOCK_ID" => "accessory",
                                "BTNS_ACTIVE" => ($arResult["PROPERTIES"]["HIDELINE_BLOCK13"]["VALUE"] == "Y")? "N": "Y",
                                "COMPOSITE_FRAME_MODE" => "Y",
                                "FILTER_NAME" => "arFilterElementAccessory",
                                "OBJ_NAME" => "accessory_".$arResult["ID"],
                                "PAGE_ELEMENT_COUNT" => $arResult["PROPERTIES"]["ACCESSORY_MAX_QUANTITY"]["VALUE"],

                                'CURRENCY_ID' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']['VALUE'],
                                'CONVERT_CURRENCY' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"],
                                "PAGER_TEMPLATE" => "phoenix_round",
                                "VIEW" => "FLAT SLIDER",
                                "COLS" => "3",
                                "COLS_LG" => "2",
                                "ELEMENT_SORT_FIELD" => "RAND",
                                "ELEMENT_SORT_ORDER" => "ASC",
                                "ELEMENT_SORT_FIELD2" => "RAND",
                                "ELEMENT_SORT_ORDER2" => "ASC",
                                'HIDE_NOT_AVAILABLE' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"],
                                'HIDE_NOT_AVAILABLE_OFFERS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]["VALUE"],
                                "COMPONENT_TEMPLATE" => "main",
                                "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_TYPE"],
                                "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
                                "PRICE_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']["VALUE_"],
                                "OFFERS_CART_PROPERTIES" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                "OFFERS_PROPERTY_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                'OFFER_TREE_PROPS' => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['VALUE_'],
                                "OFFERS_FIELD_CODE" => array("NAME","PREVIEW_TEXT","PREVIEW_PICTURE","DETAIL_TEXT","DETAIL_PICTURE",""),

                                "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                                "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                                
                                "OFFERS_SORT_FIELD" => "sort",
                                "OFFERS_SORT_ORDER" => "id",
                                "OFFERS_SORT_FIELD2" => "asc",
                                "OFFERS_SORT_ORDER2" => "asc",
                                "OFFERS_LIMIT" => "0",

                                "USE_PRICE_COUNT" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == "Y" ? "Y" : "N",
                                "USE_PRODUCT_QUANTITY" => "Y",
                                "SHOW_PRICE_COUNT" => "1",
                                'SHOW_OLD_PRICE' => "Y",
                                'SHOW_MAX_QUANTITY' => "Y",
                                "PRICE_VAT_INCLUDE" => "Y",
                                'SHOW_DISCOUNT_PERCENT' => "Y",

                                "ACTION_VARIABLE" => "action",
                                "PRODUCT_ID_VARIABLE" => "id",
                                "SECTION_ID_VARIABLE" => "SECTION_ID",
                                "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                                "PRODUCT_PROPS_VARIABLE" => "prop",
                                "CACHE_TYPE" => "N",
                                "SET_LAST_MODIFIED" => "N",
                                "DISPLAY_TOP_PAGER" => "N",
                                "DISPLAY_BOTTOM_PAGER" =>  "N",
                                "PAGER_SHOW_ALL" => "N",
                                "CACHE_FILTER" => "Y",
                                "ADD_PROPERTIES_TO_BASKET" => "Y",
                                "PARTIAL_PRODUCT_PROPERTIES" => "N",
                                "PAGER_SHOW_ALWAYS" => "N",
                                
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "LAZY_LOAD" => "N",
                                "LOAD_ON_SCROLL" => "N",
                                "USE_MAIN_ELEMENT_SECTION" => "N",
                                'PRODUCT_DISPLAY_MODE' => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                'PRODUCT_SUBSCRIPTION' => "N",
                                'ENLARGE_PRODUCT' => "STRICT",
                                'COMPARE_NAME' => "CATALOG_COMPARE_LIST",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                "CACHE_TIME" => "10",
                                "INCLUDE_SUBSECTIONS" => "Y",
                                "SHOW_ALL_WO_SECTION" => "Y",
                                "CACHE_GROUPS" => "Y",
                                "SET_TITLE" => "Y",
                                "SET_STATUS_404" => "Y",
                                "SHOW_404" => "Y",
                                'ADD_PICT_PROP' => "-",
                                'OFFER_ADD_PICT_PROP' => "-",
                                "META_KEYWORDS" => "-",
                                "META_DESCRIPTION" => "-",
                                "BROWSER_TITLE" => "-",
                                
                                'USE_ENHANCED_ECOMMERCE' => '',
                                'ADD_TO_BASKET_ACTION' => "",
                                'SHOW_CLOSE_POPUP' => "",
                                'COMPARE_PATH' => "",
                                'DISCOUNT_PERCENT_POSITION' => '',
                                'ENLARGE_PROP' => '',
                                'SHOW_SLIDER' => "",
                                'SLIDER_INTERVAL' => '',
                                'SLIDER_PROGRESS' => '',
                                'PRODUCT_BLOCKS_ORDER' => "",
                                'PRODUCT_ROW_VARIANTS' => "",
                                "SECTION_ID" => "",
                                "SECTION_CODE" => "",
                                "PRODUCT_PROPERTIES" => "",
                                "PAGER_TITLE" => "",
                                "CUSTOM_FILTER" => "",
                                "FILE_404" => "",
                                "LINE_ELEMENT_COUNT" => "",
                                "BASKET_URL" => "",
                                "PROPERTY_CODE" => array(),
                                "PROPERTY_CODE_MOBILE" => array(),
                                'LABEL_PROP' => array(),
                                
                            ),
                            $component
                        );
                      
                    ?>

                </div>
                
            </div>
        
        <?endif;?>

        <?if($main_key == "similar_category" && !empty($arResult["SIMILAR_CATEGORY"])):?>
            <div class="cart-block " id='similar_category'>

                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK11"]["VALUE"] != "Y"):?>
                
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK11"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                    
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK11"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK11"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>
                    </div>
                
                <?endif;?>


                <div class="wr-category-items-flat hidden-sm hidden-xs">
                    <div class="row no-margin">

                        <?foreach($arResult["SIMILAR_CATEGORY"] as $keySimilarCategory => $itemSimilarCategory):?>

                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3 col-6">

                                <div class="category-item-flat">

                                    <a href="<?=$itemSimilarCategory["SECTION_PAGE_URL"]?>" class="d-block">

                                        <div class="wr-img mx-auto row align-items-center">
                                            <div class="col-12">
                                                <img class="d-block mx-auto img-fluid" src="<?=$itemSimilarCategory["PICTURE_SRC"]?>" alt="">
                                            </div>
                                        </div>

                                        <div class="name"><?= $itemSimilarCategory["~NAME"]?></div>

                                    </a>

                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>

                <div class="ex-row visible-sm visible-xs">

                    <div class="wr-category-items-slider">


                        <div class="img-for-lazyload-parent finish-bottom">

                            <img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="similar_<?=$arResult["ID"]?>">


                                <div class="section-items-slider parent-slider-item-js">

                                    <?foreach($arResult["SIMILAR_CATEGORY"] as $keySimilarCategory => $itemSimilarCategory):?>
                                    
                                        <div class="<?=($keySimilarCategory != 0) ? 'noactive-slide-lazyload' : ''?>">
                                            <div class="item">
                                                <a href="<?=$itemSimilarCategory["SECTION_PAGE_URL"]?>" class="d-block">
                                                        
                                                    <img src="<?=$itemSimilarCategory["PICTURE_SRC"]?>" class="img-fluid mx-auto d-block" alt="">

                                                    <div class="desc row align-items-center">
                                                        <div class="col-12">
                                                            <?=$itemSimilarCategory["~NAME"]?>
                                                        </div> 
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                    <?endforeach;?>
                                  
                                </div>

                                <?if(intval($arResult["SIMILAR_CATEGORY_CNT"])>3):?>
                                    <div class="slider-swipe-icon dark">
                                    </div>
                                <?endif;?>

                            <img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="similar_<?=$arResult["ID"]?>">
                        </div>

                    </div>

                </div>

            </div>

        <?endif;?>

        <?if($main_key == "stuff" && strlen($arResult["PROPERTIES"]["STUFF"]["VALUE"])):?>

            <div class="cart-block " id='stuff'>
                
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK6"]["VALUE"] != "Y"):?>
                    
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK6"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                    
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK6"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK6"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>
                    </div>
                
                <?endif;?>

                <?
                    $block_name = $arResult['NAME'];

                    if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK6"]["VALUE"]))
                        $block_name .= " (".$arResult["PROPERTIES"]["TITLE_BLOCK6"]["VALUE"].")";
                ?>
                <?$APPLICATION->IncludeComponent(
                    "concept:phoenix.news-list",
                    "empl",
                    Array(
                        "COMPOSITE_FRAME_MODE" => "N",
                        "COUNT" => "",
                        "ELEMENTS_ID" => $arResult["PROPERTIES"]["STUFF"]["VALUE"],
                        "VIEW" => "full",
                        "BLOCK_TITLE" => $block_name,
                        "SIDEMENU" => true,
                        "SORT_BY1" => "SORT",
                        "SORT_ORDER1" =>"ASC"
                    )
                );?>

            </div>
           

        <?endif;?>
        
        
        <?if($main_key == "news_list" && !empty($arResult["PROPERTIES"]["NEWS_LIST"]["VALUE"])):?>
            
            <div class="cart-block universal-parent-slider universal-mobile-arrows" id='news_list'>
                
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK7"]["VALUE"] != "Y"):?>
                    
                
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK7"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                    
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK7"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK7"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>

                       
                        <div class="universal-arrows-mini wr-arrows-slick d-none">
                            <div class="arrow-next"></div>
                            <div class="arrow-prev"></div>
                        </div>
                       
                    </div>
                
                <?endif;?>

                <?

                    $APPLICATION->IncludeComponent(
                        "concept:phoenix.news-blogs-actions-list",
                        "slider-news-detail",
                        Array(
                            "BLOCK_ID"=> "news_list",
                            "BTNS_ACTIVE" => ($arResult["PROPERTIES"]["HIDELINE_BLOCK7"]["VALUE"] == "Y")? "N": "Y",
                            "COMPOSITE_FRAME_MODE" => "N",
                            "DISPLAY_DATE" => "N",
                            "DISPLAY_NAME" => "N",
                            "DISPLAY_PICTURE" => "N",
                            "DISPLAY_PREVIEW_TEXT" => "N",
                            "ELEMENTS_ID" => $arResult["PROPERTIES"]["NEWS_LIST"]["VALUE"],
                            "SORT_BY1" => "rand",
                            "SORT_ORDER1" => "ASK",
                            "COL_LG" => "4"
                        )
                    );
                ?>
                
            </div>  
                
        <?endif;?>
        
        
        <?if($main_key == "faq" && !empty($arResult["PROPERTIES"]["FAQ"]["VALUE"])):?>

            <?
                $class1="";
                $class2="col-xl-8 col-sm-7 col-12";
                $class3="col-xl-4 col-sm-5 col-12";

                if($arResult["PROPERTIES"]["FAQ_PICTURE"]["VALUE"] > 0)
                {

                    $class1="col-sm-2 col-12";
                    $class2="col-xl-6 col-sm-5 col-12 with-photo";
                }
            ?>
            
            <div class="cart-block " id="faq">
                
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK8"]["VALUE"] != "Y"):?>
                
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK8"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                    
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK8"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK8"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>
                        
                    </div>
                
                <?endif;?>

                <div class="faq-block cart-faq-block">

                    <?if(  strlen($arResult["PROPERTIES"]["FAQ_PICTURE"]["VALUE"])>0 || strlen($arResult["PROPERTIES"]["FAQ_NAME"]["VALUE"])>0|| strlen($arResult["PROPERTIES"]["FAQ_POST"]["VALUE"])>0 || strlen($arResult["PROPERTIES"]["FAQ_BUTTON_NAME"]["VALUE"])>0  ):?>
                
                        <div class="faq-table row align-items-center no-margin">

                            <?if(strlen($arResult["PROPERTIES"]["FAQ_PICTURE"]["VALUE"])>0):?>
                                <div class="faq-cell <?=$class1?> left">

                                    <table>
                                        <tr>
                                            <td>
                                                <?$img_big = CFile::ResizeImageGet($arResult["PROPERTIES"]["FAQ_PICTURE"]["VALUE"], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_EXACT, false);?>

                                                <img class="img-fluid mx-auto d-block lazyload" data-src="<?=$img_big["src"]?>" alt=""/>
                                            </td>
                                        </tr>
                                    </table>

                                    
                                </div>   

                            <?endif;?>


                            <?if(strlen($arResult["PROPERTIES"]["FAQ_NAME"]["VALUE"])>0|| strlen($arResult["PROPERTIES"]["FAQ_PROF"]["VALUE"])>0):?>
                        
                                <div class="faq-cell <?=$class2?> center">
                                    <div class="wrap-faqtext">

                                        <?if(strlen($arResult["PROPERTIES"]["FAQ_NAME"]["VALUE"])):?>
                                            <div class="name bold"><?=$arResult["PROPERTIES"]["FAQ_NAME"]["~VALUE"]?></div>
                                        <?endif;?>

                                        <?if(strlen($arResult["PROPERTIES"]["FAQ_PROF"]["VALUE"])):?>
                                            <div class="desc italic"><?=$arResult["PROPERTIES"]["FAQ_PROF"]["~VALUE"]?></div>
                                        <?endif;?>

                                    </div>
                                    
                                </div>

                            <?endif;?>


                            <?if(strlen($arResult["PROPERTIES"]["FAQ_BUTTON_NAME"]["VALUE"])>0):?>
                         


                                <div class="faq-cell <?=$class3?> right">
                                    <div class="main-button-wrap center">

                                        <a class="big button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> call-modal callform main-color" data-header = "<?=$block_name?>" data-call-modal="form<?=$arResult["PROPERTIES"]["FAQ_FORM"]["VALUE"]?>" title="<?=$arResult["PROPERTIES"]["FAQ_BUTTON_NAME"]["VALUE"]?>"><?=$arResult["PROPERTIES"]["FAQ_BUTTON_NAME"]["VALUE"]?></a>

                                    </div>
                                    
                                </div>

                            <?endif;?>
                            
                        </div>

                    <?endif;?>

                    <div class="quest-part">
                        <div class="faq">

                            <?foreach($arResult["PROPERTIES"]["FAQ"]["VALUE"] as $k=>$arFaq):?>
                                <div class="faq-element quest-parent <?if($k == 0 && $arResult["PROPERTIES"]["FAQ_HIDE_FIRST_ITEM"]["VALUE_XML_ID"] != "Y"):?> active <?endif;?> toogle-animate-parent">
                                    
                                    <?CPhoenix::admin_setting($arFaq, false)?>

                                    <div class="question toogle-animate-click">
                                        <span><?=$arFaq["~NAME"]?></span>
                                    </div>

                                    <div class="text text-content italic toogle-animate-content">
                                        <?=$arFaq["~PREVIEW_TEXT"]?>
                                    </div>
                                </div>
                            <?endforeach;?>

                        </div>
                    </div>

              
                    
                </div>
                
            </div>
        
        <?endif;?>
        
        
        <?if($main_key == "text" && strlen($arResult["DETAIL_TEXT"]) > 0):?>
        
            <div class="cart-block " id="text">
                
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK9"]["VALUE"] != "Y"):?>
                
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK9"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                    
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK9"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK9"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>
                    </div>
                
                <?endif;?>
        
                <div class="cart-simple-text text-content" <?if($desc == 1):?>itemprop="description"<?endif;?>><?=$arResult["~DETAIL_TEXT"]?></div>
                
            </div>
        
        
        <?endif;?>

        <?if($main_key == "text2" && is_array($arResult["PROPERTIES"]["DETAIL_TEXT_BLOCK12"]["VALUE"])):?>

        
            <div class="cart-block " id="text2">
                
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK12"]["VALUE"] != "Y"):?>
                
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK12"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                    
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK12"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK12"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>
                    </div>
                
                <?endif;?>

                <?if(isset($arResult["PROPERTIES"]["DETAIL_TEXT_BLOCK12"]["~VALUE"]["TEXT"])):?>
        
                    <div class="cart-simple-text text-content"><?=$arResult["PROPERTIES"]["DETAIL_TEXT_BLOCK12"]["~VALUE"]["TEXT"]?></div>

                <?endif;?>
                
            </div>
        
        
        <?endif;?>


        <?if($main_key == "text3" && is_array($arResult["PROPERTIES"]["DETAIL_TEXT_BLOCK15"]["VALUE"])):?>

        
            <div class="cart-block " id="text3">
                
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK15"]["VALUE"] != "Y"):?>
                
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK15"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                    
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK15"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK15"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>
                    </div>
                
                <?endif;?>

                <?if(isset($arResult["PROPERTIES"]["DETAIL_TEXT_BLOCK15"]["~VALUE"]["TEXT"])):?>
        
                    <div class="cart-simple-text text-content"><?=$arResult["PROPERTIES"]["DETAIL_TEXT_BLOCK15"]["~VALUE"]["TEXT"]?></div>

                <?endif;?>
                
            </div>
        
        
        <?endif;?>
        
        
        <?if($main_key == "gallery" && !empty($arResult["PROPERTIES"]["GALLERY"]["VALUE"])):?>
        
            <div class="cart-block " id="gallery">
                
                <?if($arResult["PROPERTIES"]["HIDELINE_BLOCK10"]["VALUE"] != "Y"):?>
                
                    <div class="cart-title <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK10"]["VALUE"]) <= 0):?>empty-title<?endif;?> ">
                    
                        <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK10"]["VALUE"]) > 0):?>
                            <div class="title"><?=$arResult["PROPERTIES"]["TITLE_BLOCK10"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <div class="line"></div>
                    </div>
                
                <?endif;?>
        
                <div class="cart-simple-gallery">         
                  
                    <?if($arResult["PROPERTIES"]["GALLERY_COLS"]["VALUE_XML_ID"] == ""):?>
                        <?$arResult["PROPERTIES"]["GALLERY_COLS"]["VALUE_XML_ID"] = "four";?>
                    <?endif;?>

                    <?if($arResult["PROPERTIES"]["GALLERY_COLS"]["VALUE_XML_ID"] == "one"):?>
                    
                        <div class="single-photos">
                        
                            <?foreach($arResult["PROPERTIES"]["GALLERY"]["VALUE"] as $k=>$photo):?>
                                
                                <div class="photo-item row">
            
                                    <div class="<?if(strlen($arResult["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$k]) > 0):?>col-lg-8 col-12<?else:?>col-12<?endif;?>">
                                    
                                        <div class="photo-wrap <?if(strlen($arResult["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$k]) > 0):?>right-col<?endif;?>">
                                        
                                            <?$file = CFile::ResizeImageGet($photo, array('width'=>1020, 'height'=>3020), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>
                                            <?$file_big = CFile::ResizeImageGet($photo, array('width'=>2000, 'height'=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>
            
                                            <a href="<?=$file_big["src"]?>" data-gallery="gal-item-add" class="cursor-loop" title="<?=CPhoenix::prepareText($arResult["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$k])?>">
                                                <img class="img-fluid lazyload" data-src="<?=$file["src"]?>" />
                                            </a> 
                                            
                                            
                                        </div>
                                        
                                    </div>
                                    
                                    
                                    <?if(strlen($arResult["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$k]) > 0):?>
                                    
                                        <div class="col-lg-4 col-12">
                    
                                            <div class="photo-text text-content">

                                                <?if(strlen($arResult["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$k]) > 0):?>
                                                    <p><?=$arResult["PROPERTIES"]["GALLERY"]["~DESCRIPTION"][$k]?></p>
                                                <?endif;?>
                                                
                                            </div>
                                            
                                        </div>
                                    
                                    <?endif;?>
                
                                </div>
                                
                            
                            <?endforeach;?>
                        
                        </div>
                    
                    
                    <?else:?>

                    
                        <div class="gallery-block gallery row <?if($arResult["PROPERTIES"]["GALLERY_ANIMATE"]["VALUE"] == "Y"):?>parent-animate<?endif;?>">
                        
                            <?$size_big = array('width'=>2000, 'height'=>1500);?>
                            
                            <?if($arResult["PROPERTIES"]["GALLERY_COLS"]["VALUE_XML_ID"] == "two"):?>
                                
                                <?$class = "col-6";?>
                                <?$size = array('width'=>506, 'height'=>482);?>
                                
                                <?$clear = 2;?>
                                
                            <?endif;?>
                            
                            <?if($arResult["PROPERTIES"]["GALLERY_COLS"]["VALUE_XML_ID"] == "three"):?>
                                
                                <?$class = "col-md-4 col-6";?>
                                <?$size = array('width'=>500, 'height'=>500);?>
                                
                                <?$clear = 3;?>
                                
                            <?endif;?>
                            
                            <?if($arResult["PROPERTIES"]["GALLERY_COLS"]["VALUE_XML_ID"] == "four"):?>
                                
                                <?$class = "col-md-3 col-6";?>
                                <?$size = array('width'=>400, 'height'=>400);?>
                                
                                <?$clear = 4;?>
                                
                            <?endif;?>
                            
                            <?if($arResult["PROPERTIES"]["GALLERY_COLS"]["VALUE_XML_ID"] == "five"):?>
                                
                                <?$class = "col-md-five col-6";?>
                                <?$size = array('width'=>300, 'height'=>300);?>
                                
                                <?$clear = 5;?>
                                
                            <?endif;?>
                            
                            <?if($arResult["PROPERTIES"]["GALLERY_COLS"]["VALUE_XML_ID"] == "six"):?>
                                
                                <?$class = "col-md-2 col-6";?>
                                <?$size = array('width'=>200, 'height'=>200);?>
                                
                                <?$clear = 6;?>
                                
                            <?endif;?>
                            
                            <?foreach($arResult["PROPERTIES"]["GALLERY"]["VALUE"] as $k=>$photo):?>
                        
                                <div class="<?=$class?> <?if($arResult["PROPERTIES"]["GALLERY_ANIMATE"]["VALUE"] == "Y"):?> child-animate opacity-zero<?endif;?>">
                                
                                    <div class="gallery-img">
                                    
                                        <?$file = CFile::ResizeImageGet($photo, $size, BX_RESIZE_IMAGE_EXACT, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                                            <?$file_big = CFile::ResizeImageGet($photo, $size_big, BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
            
                                        <a href="<?=$file_big["src"]?>" data-gallery="gal-item-add" class="cursor-loop" title="<?=CPhoenix::prepareText($arResult["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$k])?>">
                                        
                                            <div class="corner-line"></div>
                                            <img class="d-block mx-auto img-fluid lazyload" data-src="<?=$file["src"]?>" />
                                                                 
                                        </a>
                                    </div>                   
                                </div>
                            <?endforeach;?>
                        </div>
                    <?endif;?>
                </div>
            </div>
        <?endif;?>


        <?if($main_key == "rating" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_REVIEW"]["VALUE"]["ACTIVE"] == "Y"):?>

            <div class="cart-block" id="rating-block">
                <input class = "product-<?=$arResult["ID"]?>-name" type="hidden" value="<?=str_replace("'","\"",strip_tags($arResult["~NAME"]))?>">
                <input class = "product-<?=$arResult["ID"]?>-picture-src-small" type="hidden" value="<?=$arResult["FIRST_ITEM"]["MORE_PHOTOS"][0]["SMALL"]["SRC"]?>">

                <div class="review-block-ajax" data-product-id = "<?=$arResult["ID"]?>"></div>

            </div>

        <?endif;?>


    <?endforeach;?>
<?endif;?>

<?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"] && !empty($arResult['OFFERS']) && $arResult["SKU_EMPTY"]):?>

    <div class="alert-block hidden-sm hidden-xs">
            
        <div class="phoenix-alert-btn mgo-widget-alert_pulse"></div>
        
        <div class="alert-block-content">
            
            <div class="alert-head">
                <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRODUCT_ALERT_HEAD"]?>
                
                <a class="alert-close"></a>
            </div>
            
            <div class="alert-body">
                
                <div class="cont">
                        
                    <div class="big-name"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRODUCT_ALERT_SKU"]?></div>
                    
                    <div class="instr">
                        
                        <div class="instr-element">
                            
                            <div class="text">1. <a class="phoenix-sets-open" data-open-set="edit-sets"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRODUCT_ALERT_SKU_TEXT_1"]?></a></div>

                            <div class="comment"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRODUCT_ALERT_SKU_TEXT_1_COMMENT"]?></div>
                            
                        </div>
                        
                        <div class="instr-element">
                            
                            <div class="text">2. <a class="phoenix-sets-open" data-open-set="edit-sets"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRODUCT_ALERT_SKU_TEXT_2"]?></a></div>

                            <div class="comment"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRODUCT_ALERT_SKU_TEXT_2_COMMENT"]?></div>
                            
                        </div>
                        
                    </div>
                    
                </div>
                
                
            </div>
            
        </div>
        
    </div>

<?endif;?>



<script>
    BX.message({
        ECONOMY_INFO_MESSAGE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ECONOMY"]?>',
        PRICE_TOTAL_PREFIX: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRICE_TOTAL_PREFIX"]?>',
        RELATIVE_QUANTITY_MANY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_2"]?>',
        RELATIVE_QUANTITY_FEW: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["DESCRIPTION_2"]?>',
        RELATIVE_QUANTITY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_NOEMPTY"]?>',
        RELATIVE_QUANTITY_EMPTY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_EMPTY"]?>',
        MORE_PHOTOS: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MORE"]?>',
        SITE_ID: '<?=$component->getSiteId()?>',
        ARTICLE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"]?>',
    });
    // var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
  
</script>
<?
unset($itemIds, $jsParams);