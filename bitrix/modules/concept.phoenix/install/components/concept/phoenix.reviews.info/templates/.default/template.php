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
    
<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y" && !empty($arResult["ITEMS"]) && $arResult["ITEMS"]["REVIEWS_COUNT"]):?>

    <script>
        
        if($(".circle-progress-bar").length>0)
            setRatingProgress($(".circle-progress-bar").attr("data-item-id"), <?=$arResult["RATING"]?>);

    </script>


    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["RATING_HIDE_BLOCK_TITLE"]["VALUE"]["ACTIVE"] != "Y"):?>

        <div class="cart-title <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RATING_BLOCK_TITLE']["VALUE"])<=0):?>empty-title<?endif;?> ">
                        
     
            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RATING_BLOCK_TITLE']["VALUE"])>0):?><div class="title"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RATING_BLOCK_TITLE']["~VALUE"]?></div><?endif;?>
         
            
            <div class="line"></div>
            
        </div>

    <?endif;?>                

    <div class="rating-block">
        
        <div class="row">
            
            <div class="col-md-4 col-12">

                <div class="item">

                    <div class="row">
                        <div class="col-auto">

                            <div class="left-part">

                                <svg class="circle-progress-bar" data-item-id = "vote-block-<?=$arParams["PRODUCT_ID"]?>">
                                    <circle class="ghost"></circle>
                                    <circle class="rating-progress-bar"></circle>
                                </svg>

                                <div class="wr-rating">
                                    <span class="rating-value"data-item-id = "vote-block-<?=$arParams["PRODUCT_ID"]?>"></span>
                                </div>
                                
                            </div>
                                
                        </div>
                        <div class="col-7 text">
                            <div class="name"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["PANEL_NAME"]?></div>

                            <?if(isset($arResult["RATING_DESC"])):?>
                                <div class="desc italic"><?=$arResult["RATING_DESC"]?></div>
                            <?endif;?>

                        </div>
                    </div>
                    
                </div>
                
            </div>

            <div class="col-md-4 col-12">

                <div class="item">

                    <div class="row">
                        <div class="col-auto">
                            <div class="left-part rating-percent-border <?=$arResult["RECCOMEND"]["STATUS"]?>">
                                <div class="row h-100 align-items-center">
                                    <div class="col-12">
                                        <div class="rating-percent">
                                            <span class="rating-percent-value"><?=$arResult["RECCOMEND"]["COUNT_PERCENT"]?></span>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-7 text">
                            <div class="name"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["RATING"]["RECOM_NAME"]?></div>

                            <?if(isset($arResult["RECCOMEND"]["DESC"])):?>
                                <div class="desc italic"><?=$arResult["RECCOMEND"]["DESC"]?>
                                    <i class="simple-hint fa fa-question-circle hidden-sm hidden-xs main-colot-text" data-toggle="tooltip-ajax" data-placement="bottom" title="" data-original-title='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["RECOMMEND_HINT"]["VALUE"]?>'></i>
                                </div>
                            <?endif;?>
                        </div>
                    </div>
                    
                </div>
                
            </div>

            <div class="col-md-4 col-12">
                <div class="item bg-board vote-panel">
                    <div class="text center">
                        <div class="name"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["VOTE_PANEL_NAME"]?></div>
                        <?=CPhoenix::GetRatingContainerVoteHTML(array("ID"=>$arParams['PRODUCT_ID'], "CLASS"=>"full-rating hover open-fly-block fly-block-review", "ATTR" => "data-fly-block-id='review' data-product-id='".$arParams["PRODUCT_ID"]."'"));?>
                    </div>
                </div>
                
            </div>

        </div>

    </div>

<?endif;?>


<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["REVIEW_HIDE_BLOCK_TITLE"]["VALUE"]["ACTIVE"] != "Y"):?>

    <div class="cart-title <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['REVIEW_BLOCK_TITLE']["VALUE"])<=0):?>empty-title<?endif;?> ">
                    
 
        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['REVIEW_BLOCK_TITLE']["VALUE"])>0):?><div class="title"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['REVIEW_BLOCK_TITLE']["~VALUE"]?></div><?endif;?>
     
        
        <div class="line"></div>
        
    </div>

<?endif;?>


<div class="review-block">

    <div class="row">

        <div class="col-md-4 col-12 wr-group-list order-md-2 order-1">

            <?if(!empty($arResult["ITEMS"]) && $arResult["ITEMS"]["REVIEWS_COUNT"]):?>

                <div class="group-list-simple d-none d-md-block">

                    <?foreach ($arResult["FILTER"] as $key => $arItem):?>

                        <div class="group-item <?=$arItem["STATUS"]?> row no-gutters btn-filter" data-filter = "<?=$key?>">
                            <div class="col"><span class="name"><?=$arItem["NAME"]?></span></div>
                            <div class="col-auto">
                                <div class="count"><?=$arItem["COUNT"]?></div>
                            </div>
                        </div>

                    <?endforeach;?>

                </div>

            <?endif;?>

            <a class="button-def main-color round-sq d-block review-btn open-fly-block fly-block-review" data-fly-block-id="review" data-product-id="<?=$arParams["PRODUCT_ID"]?>"><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["BTN_USER_REVIEW"]?></span></a>
            
        </div>


<?/*close tags in component concept:phoenix.reviews.list */?>