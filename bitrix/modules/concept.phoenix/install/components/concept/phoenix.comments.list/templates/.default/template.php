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
    if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"])
        CPhoenix::setMess(array("settings"));
?>

<?if($_POST["mode"]=="full" && empty($arResult["ITEMS"])):?>

    <div class="empty-mess">
        <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["EMPTY_REVIEWS"]?>
    </div>

<?endif;?>

<?if(!empty($arResult["ITEMS"])):?>

    <?foreach($arResult["ITEMS"] as $key => $arItem):?>

        <div class="review-item review-item-comments <?=($key == 0 && $arResult["NEXT_PAGE"] == 2)?"first":""?>  <?=($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"])?"parent-tool-settings":""?>" data-review-id="<?=$arItem["ID"]?>">


            <div class="row row-top">

                <div class="col-auto">
                    <div class="wr-photo bold" <?=(isset($arItem["PHOTO_SRC"]))?"style=\"background-image:url('".$arItem["PHOTO_SRC"]."')\"":"";?>>

                        <?=(isset($arItem["FIRST_LETTER"]))? $arItem["FIRST_LETTER"]:"";?>
                    </div>
                </div>

                <div class="col wr-text">
                    <div class="row row-user-panel">

                        <div class="col-12 wr-name">

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

                    </div>
                </div>
            </div>



            <div class="row row-bottom">

                <div class="col-auto d-none d-md-block">
                    <div class="wr-photo-ghsot">
                    </div>
                </div>

                <div class="col wr-text">

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

                            
                        </div>

                        <?if(strlen($arItem["RESPONSE"])>0):?>

                            <div class="answer text">

                                <div class="title bold"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMMENTS"]["RESPONSE_TEXT"]?></div>
                                <?=$arItem["~RESPONSE"]?>
                                
                            </div>

                        <?endif;?>

                    <?endif;?>
                    
                </div>

            </div>

            <?
                if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"]):?>

                    <div class="tool-settings"><a href="/bitrix/admin/concept_phoenix_admin_comments_edit.php?ID=<?=$arItem["ID"]?>&site_id=<?=SITE_ID?>" class="tool-settings " data-toggle="tooltip-ajax" target="_blank" data-placement="right" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SETTINGS"]["EDIT"]?>"></a></div>

                <?endif;
            ?>
            <?if( intval($PHOENIX_TEMPLATE_ARRAY["USER_ID"]) > 0 && $PHOENIX_TEMPLATE_ARRAY["USER_ID"] == $arItem["USER_ID"]):?>
                <div class="review-item-delete comment-delete-js" data-review-id="<?=$arItem["ID"]?>"></div>
            <?endif;?>
        </div>

    <?endforeach;?>

    <?if(!empty($arResult["NEXT_ITEMS"])):?>

        <a class="button-def secondary d-block get-comments-js" data-page="<?=$arResult["NEXT_PAGE"]?>"><?=$arResult["BTN_NAME"]?></a>

    <?endif;?>

<?endif;?>
