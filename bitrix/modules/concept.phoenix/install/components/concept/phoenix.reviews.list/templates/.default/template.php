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

global $PHOENIX_TEMPLATE_ARRAY, $APPLICATION, $USER;
    $isAdmin = $USER->isAdmin() || $phoenix_rights > "R";
    $phoenix_rights = $APPLICATION->GetGroupRight("concept.phoenix");

    if($isAdmin)
        CPhoenix::setMess(array("settings"));
?>

<?if(empty($arResult["ITEMS"]) && $arParams["MODE"]=="full"):?>

    <div class="empty-mess">
        <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["EMPTY_REVIEWS"]?>
    </div>

<?elseif(!empty($arResult["ITEMS"])):?>

    <?foreach($arResult["ITEMS"] as $key => $arItem):?>

        <div class="review-item <?=($key == 0 && $arResult["NEXT_PAGE"] == 2)?"first":""?>  <?=($isAdmin)?"parent-tool-settings":""?>" data-review-id="<?=$arItem["ID"]?>">


            <div class="row row-top">

                <div class="col-auto">
                    <div class="wr-photo bold" <?=(isset($arItem["PHOTO_SRC"]))?"style=\"background-image:url('".$arItem["PHOTO_SRC"]."')\"":"";?>>

                        <?=(isset($arItem["FIRST_LETTER"]))? $arItem["FIRST_LETTER"]:"";?>
                    </div>
                </div>

                <div class="col wr-text">
                    <div class="row row-user-panel">

                        <div class="col-lg-8 col-md-7 col-12 wr-name">

                            <div class="name"><span class="bold <?if(strlen($arItem["RECOMMEND_HTML"])>0):?>rec<?endif;?>"><?=$arItem["USER_NAME"]?></span>

                                <?if(strlen($arItem["RECOMMEND_HTML"])>0):?>
                                <span class="d-none d-md-inline-block">
                                   <?=$arItem["RECOMMEND_HTML"]?>
                                </span>
                                <?endif;?>
                            </div>
                            <?if(isset($arItem["DATE_FORMAT"])):?>
                                <div class="date"><?=$arItem["DATE_FORMAT"]?></div>
                            <?endif;?>
                            
                        </div>

                        <div class="col-lg-4 col-md-5 col-12">
                            <div class="row align-items-center">

                                <div class="col wr-like-count">
                                    <div class="row no-gutters align-items-center justify-content-md-end">
                                        <div class="col-12 d-sm-none">
                                            <?if(strlen($arItem["RECOMMEND_HTML"])>0):?>
                                               <?=$arItem["RECOMMEND_HTML"]?>
                                            <?endif;?>
                                        </div>
                                        <div class="col-auto">
                                            <div class="review-like"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["AGREE"]?></div>
                                        </div>

                                        <div class="col-auto">
                                            <div class="review-like-count <?=($arItem["LIKE_COUNT"] != 0)? "plus":"";?>" data-count="<?=$arItem["LIKE_COUNT"]?>"><?=$arItem["LIKE_COUNT_FORMATED"]?></div>
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>



            <div class="row row-bottom">

                <div class="col-auto d-none d-md-block">
                    <div class="wr-photo-ghsot">
                    </div>
                </div>

                <div class="col wr-text">

                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['USE_VOTE']['VALUE']['ACTIVE'] == "Y" || strlen($arItem["EXP_DESC"])>0):?>

                        <div class="row row-user-rating">

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['USE_VOTE']['VALUE']['ACTIVE'] == "Y"):?>
                                <div class="col-auto"><?CPhoenix::GetRatingContainerVoteHTML(array("ID"=>$arItem["ID"], "CLASS"=>"review-user", "RATING_MODE" => "Y", "RATING"=>$arItem["VOTE"]))?></div>
                            <?endif;?>

                            <?if(strlen($arItem["EXP_DESC"])>0):?>

                                <div class="col desc-to-rating align-self-center"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["EXP"]?>:&nbsp;<?=$arItem["EXP_DESC"]?></div>

                            <?endif;?>
                        </div>

                    <?endif;?>

                    <?if($arItem["REVIEW_TEXT_ISSET"] == "Y"):?>

                        <div class="row row-text text">

                            <?if(strlen($arItem["TEXT"])>0 || strlen($arItem["IMAGES_SRC"])>0):?>
                                <div class="col-12 row-comment">
                                    <?=(strlen($arItem["TEXT"])>0)?$arItem["~TEXT"]:"";?>
                                    <?if(strlen($arItem["IMAGES_SRC"])>0):?>
                                        <div class="row-gallery"><?=$arItem["IMAGES_SRC"]?></div>
                                    <?endif;?>
                                </div>
                            <?endif;?>

                            

                            <?//if($arItem["REVIEW_ADVS_ISSET"] == "Y"):?>

                                <?if(strlen($arItem["POSITIVE"])>0):?>

                                    <div class="col-md-6 col-12 wr-column">
                                        <div class="title bold"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ADVS"]?></div>
                                        <?=$arItem["~POSITIVE"]?>
                                    </div>

                                <?endif;?>

                                <?if(strlen($arItem["NEGATIVE"])>0):?>

                                    <div class="col-md-6 col-12">
                                        <div class="title bold"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["DISADVS"]?></div>
                                        <?=$arItem["~NEGATIVE"]?>
                                    </div>

                                <?endif;?>

                            <?//endif;?>
                        </div>

                        <?if(strlen($arItem["STORE_RESPONSE"])>0):?>

                            <div class="answer text">

                                <div class="title bold"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ANSWER_SALE"]?></div>
                                <?=$arItem["~STORE_RESPONSE"]?>
                                
                            </div>

                        <?endif;?>

                    <?endif;?>
                    
                </div>

            </div>

            <?
                if($isAdmin):?>

                    <div class="tool-settings"><a href="/bitrix/admin/concept_phoenix_admin_reviews_edit.php?ID=<?=$arItem["ID"]?>&site_id=<?=SITE_ID?>" class="tool-settings " data-toggle="tooltip-ajax" target="_blank" data-placement="right" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SETTINGS"]["EDIT"]?>"></a></div>

                <?endif;
            ?>
            
        </div>

    <?endforeach;?>

    <?if(!empty($arResult["NEXT_ITEMS"])):?>

        <a class="button-def secondary d-block getReviews" data-page="<?=$arResult["NEXT_PAGE"]?>" data-filter="<?=($arParams["FILTER"])?$arParams["FILTER"]:"";?>"><?=$arResult["BTN_NAME"]?></a>

    <?endif;?>


<?endif;?>

