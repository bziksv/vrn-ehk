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
<?
    global $PHOENIX_TEMPLATE_ARRAY;

    $block_name = $arResult['~NAME'];

    if(strlen($arResult["PROPERTIES"]["HEADER"]["VALUE"]) > 0)
        $block_name .= " (".$arResult["PROPERTIES"]["HEADER"]["~VALUE"].")";

    $block_name = htmlspecialcharsEx(strip_tags(html_entity_decode($block_name)));
?>

<?$this->SetViewTarget('news-detail-head-right-part');?>

    <?if($arResult["PROPERTIES"]["PIC_IN_HEAD"]["VALUE"]):?>

        <?$pic = CFile::ResizeImageGet($arResult["PROPERTIES"]["PIC_IN_HEAD"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>

        <img class = "lazyload d-block mx-auto" data-src="<?=$pic["src"]?>" alt="">

    <?else:?>

        <div class="wrap-scroll-down hidden-xs hidden-xxs">
            <div class="down-scrollBig scroll-down">
                <i class="fa fa-chevron-down"></i>
            </div>
        </div>
        
    <?endif;?>
                            
<?$this->EndViewTarget();?>

<div class="block small padding-on">


    <div class="new-detail">
        <div class="top-info">

            <div class="row"> 

                <div class="col-md-6 col-12">

                    <?$frame = $this->createFrame()->begin();?>

                        <div class="date">
                            

                                <?if($arResult["IBLOCK_CODE"] == "concept_phoenix_action_".SITE_ID):?>
                                                                                    
                                    <?if(getmicrotime() > MakeTimeStamp($arResult["ACTIVE_TO"]) && strlen($arResult["ACTIVE_TO"])>0):?>

                                        <span class="off"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ACTIONS_ACT_OFF"]?></span>

                                    <?else:?>

                                        <?if(strlen($arResult["ACTIVE_TO"])>0):?>

                                            <span class="to"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ACTIONS_ACT_ON_TO"]?><?echo ToLower(CIBlockFormatProperties::DateFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]["VALUE"], MakeTimeStamp($arResult["ACTIVE_TO"], CSite::GetDateFormat())));?></span>

                                        <?else:?>

                                            <span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ACTIONS_ACT_ON"]?></span>

                                        <?endif;?>

                                    <?endif;?>

                                <?else:?>

                                    <?if(strlen($arResult["ACTIVE_FROM"])>0):?>
                                        <?echo CIBlockFormatProperties::DateFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]["VALUE"], MakeTimeStamp($arResult["ACTIVE_FROM"], CSite::GetDateFormat()));?>
                                    <?endif;?>

                                <?endif;?>

                            

                        </div>

                        <div class="count_views"><i class="concept-icon concept-eye-1"></i> <?=$arResult["SHOW_COUNTER"]?></div> 
                    <?$frame->end();?>

                </div>

                <div class="col-md-6 col-12">
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
                        ),
                        "view-1"
                    )?>
                </div>

            </div>
        </div>

        <div class="new-detail-content text-content">
          

            <?if(strlen($arResult["~DETAIL_TEXT"])>0):?>

                <?=$arResult["~DETAIL_TEXT"]?>

            <?endif;?>
            
        </div>

        <?if(strlen($arResult["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"]) <= 0):?>
                <?$arResult["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"] = "form";?>
        <?endif;?>
            
        <?if(strlen($arResult["PROPERTIES"]["BUTTON_NAME"]["VALUE"]) > 0 && strlen($arResult["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"]) > 0):?>

            <div class="main-button-wrap">

                <?
                    if($arResult["PROPERTIES"]["BUTTON_FORM"]["VALUE"] > 0)
                        $form_id = $arResult["PROPERTIES"]["BUTTON_FORM"]["VALUE"];

                    if($arResult["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"] == "fast_order")
                    {
                        $form_id = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']['VALUE_CATALOG'];

                        if($arResult["PROPERTIES"]["BUTTON_FORM"]["VALUE"] > 0)
                            $form_id = $arResult["PROPERTIES"]["BUTTON_FORM"]["VALUE"];
                    }

                    $arClass = array();
                    $arClass=array(
                        "XML_ID"=> $arResult["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"],
                        "FORM_ID"=> $form_id,
                        "MODAL_ID"=> $arResult["PROPERTIES"]["BUTTON_MODAL"]["VALUE"],
                        "QUIZ_ID"=> $arResult["PROPERTIES"]["BUTTON_QUIZ"]["VALUE"],
                    );
                    
                    $arAttr=array();
                    $arAttr=array(
                        "XML_ID"=> $arResult["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"],
                        "FORM_ID"=> $form_id,
                        "MODAL_ID"=> $arResult["PROPERTIES"]["BUTTON_MODAL"]["VALUE"],
                        "LINK"=> $arResult["PROPERTIES"]["BUTTON_LINK"]["VALUE"],
                        "BLANK"=> $arResult["PROPERTIES"]["BUTTON_BLANK"]["VALUE_XML_ID"],
                        "HEADER"=> $block_name,
                        "QUIZ_ID"=> $arResult["PROPERTIES"]["BUTTON_QUIZ"]["VALUE"],
                        "LAND_ID"=> $arResult["PROPERTIES"]["BUTTON_LAND"]["VALUE"]
                    );
                ?>

                <a
                <?
                    if(strlen($arResult["PROPERTIES"]["BUTTON_ONCLICK"]["VALUE"])>0) 
                    {
                        $str_onclick = str_replace("'", "\"", $arResult["PROPERTIES"]["BUTTON_ONCLICK"]["VALUE"]);

                        echo "onclick='".$str_onclick."'";
                    }
                ;?> class="big button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE'];?> <?=CPhoenix::buttonEditClass($arClass)?> <?if($arResult["PROPERTIES"]["BUTTON_VIEW"]["VALUE_XML_ID"] == "empty"):?> secondary <?elseif($arResult["PROPERTIES"]["BUTTON_VIEW"]["VALUE_XML_ID"] == "shine"):?> shine main-color <?else:?> main-color <?endif;?>" <?=CPhoenix::buttonEditAttr($arAttr)?>><?=$arResult["PROPERTIES"]["BUTTON_NAME"]["~VALUE"]?></a>
                        
            </div>

        <?endif;?>


        <?if(isset($arResult["FILES"])):?>

            <?if(strlen($arResult["PROPERTIES"]["TITLE_BLOCK_FILES"]["VALUE"])>0):?>   
                <div class="text-content">      
                    <h2><?=$arResult["PROPERTIES"]["TITLE_BLOCK_FILES"]["~VALUE"]?></h2>
                </div>   
            <?endif;?>
            
            <div class="files-list">
                <div class="row">

                    <?foreach ($arResult["FILES"] as $arFile):?>
                        <div class="col-md-3 col-6">
                        
                            <div class="item">

                                <a target="_blank" href="<?=$arFile['PATH']?>">
                                    <div class="row">

                                        <div class="col-auto align-self-start wr-icon">
                                            <div class="icon"></div>
                                        </div>

                                        <?if(strlen($arFile['DESC'])>0):?>

                                            <div class="col-9 align-self-center wr-desc">
                                                <div class="desc">
                                                    <?=$arFile['DESC']?>
                                                </div>

                                                <?if(strlen($arFile['SUB_DESC'])>0):?>

                                                    <div class="subdesc">
                                                        <?=$arFile['SUB_DESC']?>
                                                    </div>

                                                <?endif;?>
                                                
                                            </div>
                                        <?endif;?>
                                    </div>

                                </a>
                                
                            </div>

                        </div>

                    <?endforeach;?>


                </div>
            </div>

        <?endif;?>

    </div>

    <?if(isset($arResult["GALLERY"])):?>

        <?if(strlen($arResult["PROPERTIES"]["NEWS_GALLERY_TITLE"]["~VALUE"])>0):?>   
            <div class="text-content">      
                <h2><?=$arResult["PROPERTIES"]["NEWS_GALLERY_TITLE"]["~VALUE"]?></h2>
            </div>   
        <?endif;?>

        
        <div class="gallery-block<?if($arResult["PROPERTIES"]["NEWS_GALLERY_BORDER"]["VALUE"] == "Y"):?> border-img-on<?endif;?> gallery">

            <div class="row">
                
                <?foreach($arResult["GALLERY"] as $k => $arImage):?>

                    <div class="col-md-3 col-4 middle">
                        <a data-gallery="gallery-news-<?=$arResult["ID"]?>" href="<?=$arImage["SRC_LG"]?>" title="<?=$arImage["DESC"]?>" class="cursor-loop">

                            <div class="gallery-img middle-size lazyload" data-src="<?=$arImage["SRC_XS"]?>">
                                <div class="corner-line"></div>
                            </div>

                        </a>
                    </div>

                <?endforeach;?>

            </div>          
                            
        </div>
        
    <?endif;?>



    <?if(!empty($arResult["NEWS_ID"])):?>

        <?if(strlen($arResult["PROPERTIES"]["NEWS_TITLE_NBA"]["~VALUE"])>0):?>
            <div class="text-content">
                <h2><?=$arResult["PROPERTIES"]["NEWS_TITLE_NBA"]["~VALUE"]?></h2>
            </div>
        <?endif;?>        

        <div class="news flat">

            <?
                $APPLICATION->IncludeComponent(
                    "concept:phoenix.news-blogs-actions-list",
                    "flat",
                    Array(
                        "COMPOSITE_FRAME_MODE" => "N",
                        "DISPLAY_DATE" => "N",
                        "DISPLAY_NAME" => "N",
                        "DISPLAY_PICTURE" => "N",
                        "DISPLAY_PREVIEW_TEXT" => "N",
                        "ELEMENTS_ID" => $arResult["NEWS_ID"],
                        "SORT_BY1" => "ACTIVE_FROM",
                        "SORT_ORDER1" => "DESC",
                        "COL_LG" => "4"
                    )
                );
            ?>
        </div>
    <?endif;?>