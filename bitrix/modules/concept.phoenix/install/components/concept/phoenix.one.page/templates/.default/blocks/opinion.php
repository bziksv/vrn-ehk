<?  if($arItem["PROPERTIES"]["OPINION_TEXT_COLOR"]["VALUE_XML_ID"] == "")
        $arItem["PROPERTIES"]["OPINION_TEXT_COLOR"]["VALUE_XML_ID"] = "dark";

    if($arItem["PROPERTIES"]["OPINION_VIEW"]["VALUE_XML_ID"] == "")
        $arItem["PROPERTIES"]["OPINION_VIEW"]["VALUE_XML_ID"] = "slider";

    if( $arItem["PROPERTIES"]["OPINION_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "" )
        $arItem["PROPERTIES"]["OPINION_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] = "order-first-mob";

    $leftOrder = "";

    if( $arItem["PROPERTIES"]["OPINION_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "order-first-mob" )
        $leftOrder = "last";

    if($arItem["PROPERTIES"]["OPINION_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"] == "")
        $arItem["PROPERTIES"]["OPINION_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"] = "order-first";

    
?>



<?if($arItem["PROPERTIES"]["OPINION_VIEW"]["VALUE_XML_ID"] == "slider"):?>

    <?if(isset($arItem["ELEMENTS"])):?>

    <?if(!empty($arItem["ELEMENTS"])):?>

        <div class="opinion <?=$arItem["PROPERTIES"]["OPINION_TEXT_COLOR"]["VALUE_XML_ID"]?> <?=$arItem["PROPERTIES"]["OPINION_VIEW"]["VALUE_XML_ID"]?>-<?if(!$show_menu):?>big<?else:?>small<?endif;?> <?=$arItem["ELEMENTS"][0]["PROPERTIES"]["OPINION_ROUND_OFF"]["VALUE_XML_ID"]?> <?if(!$show_menu):?>ex-row<?endif;?>">

            <div class="img-for-lazyload-parent">

                <img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arItem["ID"]?>">

                <?$count_opin = count($arItem["ELEMENTS"])?>

                <?if(!$show_menu):?>

                    <div class="slider parent-slider-item-js" data-count = "<?=$count_opin;?>">
                        <div class="slider-nav-wrap <?if($count_opin == 2 || $count_opin == 3):?>open<?endif;?>">

                            <?if($count_opin > 3 || $count_opin == 1):?>
                                <div class="slider-icon-center main-color"><span></span></div>
                            <?endif;?>

                            <div class="slider-nav">
                    
                                <?foreach($arItem["ELEMENTS"] as $k=>$arOpinion):?>

                                    <div class="for-count <?=($k!=0)?'noactive-slide-lazyload':'';?>">
                                        
                                        <?if($count_opin == 2 || $count_opin == 3):?>
                                            <div class="slider-icon main-color"><span></span></div>
                                        <?endif;?>
                                    
                                    
                                        <div class="slider-image row align-items-center justify-content-center no-gutters">
                                            
                                            <?CPhoenix::admin_setting($arOpinion, false)?>
                                        
                                            <?if($arOpinion["PROPERTIES"]["OPINION_IMAGE"]["VALUE"] > 0):?>
                                                <?$img_big = CFile::ResizeImageGet($arOpinion["PROPERTIES"]["OPINION_IMAGE"]["VALUE"], array('width'=>468, 'height'=>468), BX_RESIZE_IMAGE_EXACT , false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                                                <img class="img-fluid mx-auto d-block lazyload" data-src="<?=$img_big["src"]?>"  alt="<?=(strlen($arOpinion["PROPERTIES"]["OPINION_IMAGE"]["DESCRIPTION"]))? $arOpinion["PROPERTIES"]["OPINION_IMAGE"]["DESCRIPTION"]:"";?>"/>
                                            <?else:?>
                                                <img alt="" class="img-fluid mx-auto d-block lazyload" data-src="<?=SITE_TEMPLATE_PATH?>/images/quote.png">
                                            <?endif;?>
                                          
                                        </div>
                                    </div>

                                <?endforeach;?>

                            </div>
                                
                        </div>
                        

                        <div class="slider-for">
                            <?foreach($arItem["ELEMENTS"] as $k=>$arOpinion):?>
                                <div class="<?=($k!=0)?'noactive-slide-lazyload':'';?>">


                                    <?if(!empty($arOpinion["PROPERTIES"]["OPINION_TEXT"]["VALUE"])):?>

                                        <div class="text italic">
                                            <?=$arOpinion["PROPERTIES"]["OPINION_TEXT"]["~VALUE"]["TEXT"]?>
                                        </div>

                                    <?endif;?>

                                    <?if(strlen($arOpinion["PROPERTIES"]["OPINION_NAME"]["VALUE"]) > 0 || strlen($arOpinion["PROPERTIES"]["OPINION_PROF"]["VALUE"]) > 0):?>

                                        <div class="descrip-wrap">
                                            <?if(strlen($arOpinion["PROPERTIES"]["OPINION_NAME"]["VALUE"]) > 0):?>

                                                <div class="name main1">
                                                    <?=$arOpinion["PROPERTIES"]["OPINION_NAME"]["~VALUE"]?>
                                                </div>
                                            <?endif;?>

                                            <?if(strlen($arOpinion["PROPERTIES"]["OPINION_PROF"]["VALUE"]) > 0):?>
                                                <div class="proof">
                                                    <?=$arOpinion["PROPERTIES"]["OPINION_PROF"]["~VALUE"]?>
                                                </div>
                                            <?endif;?>
                                        </div>

                                    <?endif;?>

                                    <?if(strlen($arOpinion["PROPERTIES"]["OPINION_VIDEO"]["VALUE"]) > 0 || strlen($arOpinion["PROPERTIES"]["OPINION_FILE"]["VALUE"]) > 0):?>

                                        <div class="more-info-wrap">

                                            <div class="more-info no-margin-left-right">

                                                <div class="row">
                                                    
                                                
                                                    <?if(strlen($arOpinion["PROPERTIES"]["OPINION_FILE"]["VALUE"]) > 0):?>

                                                        <div class="col-md-auto col-12">

                                                            <div class="link-wrap">

                                                                <?$arFile = CFile::MakeFileArray($arOpinion["PROPERTIES"]["OPINION_FILE"]["VALUE"]);?>
                                    
                                                                <?$is_image = CFile::IsImage($arFile["name"], $arFile["type"]);?>

                                                            
                                                                <a href="<?=CFile::GetPath($arOpinion["PROPERTIES"]["OPINION_FILE"]["VALUE"])?>" <?if(!$is_image):?> target="_blank" <?else:?> data-gallery="s<?=$arOpinion['ID']?>" <?endif;?>class="link-blank">

                                                                    <?if(strlen($arOpinion["PROPERTIES"]["OPINION_FILE_TEXT"]["VALUE"]) > 0):?>

                                                                        <span class="bord-bot"><?=$arOpinion["PROPERTIES"]["OPINION_FILE_TEXT"]["~VALUE"]?></span>

                                                                    <?endif;?>

                                                                </a>

                                                            </div>

                                                        </div>

                                                    <?endif;?>

                                                    


                                                    <?if(strlen($arOpinion["PROPERTIES"]["OPINION_VIDEO"]["VALUE"]) > 0):?>

                                                        <div class="col-md-auto col-12">

                                                            <?$iframe = CPhoenix::createVideo($arOpinion["PROPERTIES"]["OPINION_VIDEO"]["VALUE"]);?> 

                                                            <div class="link-wrap">
         

                                                                <a class="link-video call-modal callvideo" data-call-modal="<?=$iframe["ID"]?>">

                                                                    <?if(strlen($arOpinion["PROPERTIES"]["OPINION_VIDEO_TEXT"]["VALUE"]) > 0):?>

                                                                        <span class="bord-bot"><?=$arOpinion["PROPERTIES"]["OPINION_VIDEO_TEXT"]["~VALUE"]?></span>

                                                                    <?endif;?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                 
                                                    <?endif;?>
                                                </div>

                                            </div>
                                        </div>

                                    <?endif;?>

                                    <?if($arItem["BUTTON_CHANGE"]):?>
                                    	<?CreateButton($arOpinion, $show_menu);?>
                                    <?endif;?>


                                </div>
                            <?endforeach;?>
                        </div>
                
                    </div>

                <?else:?>

                    <div class="slider-mini parent-slider-item-js">

                        <?foreach($arItem["ELEMENTS"] as $k=>$arOpinion):?>

                            <div class="<?=($k!=0)?'noactive-slide-lazyload':'';?>">

                                <div class="opinion-table row">

                                    <div class="opinion-cell z-image image-part col-md-3 col-12">

                                        <div class="wrap-img">     
                                            <?CPhoenix::admin_setting($arOpinion, false)?>                         

                                            <?if($arOpinion["PROPERTIES"]["OPINION_IMAGE"]["VALUE"] > 0):?>
                                                <?$img_big = CFile::ResizeImageGet($arOpinion["PROPERTIES"]["OPINION_IMAGE"]["VALUE"], array('width'=>468, 'height'=>468), BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                                                <img class="lazyload" data-src="<?=$img_big["src"]?>" alt="<?=(strlen($arOpinion["PROPERTIES"]["OPINION_IMAGE"]["DESCRIPTION"]))? $arOpinion["PROPERTIES"]["OPINION_IMAGE"]["DESCRIPTION"]:"";?>"/>
                                            <?else:?>
                                                <img alt="" class="lazyload" data-src="<?=SITE_TEMPLATE_PATH?>/images/quote.png">
                                            <?endif;?>

                                            <div class="slider-icon main-color"><span></span></div>

                                        </div>
        
                                        
                                        
                                    </div>

                                    <div class="opinion-cell text-part col-md-9 col-12">
                                        <?if($arOpinion["TITLE_CHANGE"]):?>
                                            <?CreateHead($arOpinion, $show_menu, true, $main_key)?>
                                        <?endif;?>

                                        <?if(!empty($arOpinion["PROPERTIES"]["OPINION_TEXT"]["VALUE"])):?>
                                            <div class="text italic">
                                                <?=$arOpinion["PROPERTIES"]["OPINION_TEXT"]["~VALUE"]["TEXT"]?>
                                            </div>
                                        <?endif;?>

                                        <?if(strlen($arOpinion["PROPERTIES"]["OPINION_NAME"]["VALUE"]) > 0 || strlen($arOpinion["PROPERTIES"]["OPINION_PROF"]["VALUE"]) > 0):?>

                                            <div class="name-wrap">
                                                <?if(strlen($arOpinion["PROPERTIES"]["OPINION_NAME"]["VALUE"]) > 0):?>
                                                    <div class="name main1">
                                                         <?=$arOpinion["PROPERTIES"]["OPINION_NAME"]["~VALUE"]?>
                                                    </div>

                                                <?endif;?>

                                                <?if(strlen($arOpinion["PROPERTIES"]["OPINION_PROF"]["VALUE"]) > 0):?>
                                                    <div class="prof">
                                                        <?=$arOpinion["PROPERTIES"]["OPINION_PROF"]["~VALUE"]?>
                                                    </div>
                                                <?endif;?>
                                            </div>

                                        <?endif;?>

                                        

                                        <?if(strlen($arOpinion["PROPERTIES"]["OPINION_VIDEO"]["VALUE"]) > 0 || strlen($arOpinion["PROPERTIES"]["OPINION_FILE"]["VALUE"]) > 0):?>

                                            <div class="more-info">

                                                <div class="row">

                                                    <?if(strlen($arOpinion["PROPERTIES"]["OPINION_FILE"]["VALUE"]) > 0):?>

                                                        <div class="col-md-auto col-12">

                                                            <?$arFile = CFile::MakeFileArray($arOpinion["PROPERTIES"]["OPINION_FILE"]["VALUE"]);?>
                                                            <?$is_image = CFile::IsImage($arFile["name"], $arFile["type"]);?>

                                                            <div class="link-wrap">

                                                                <a href="<?=CFile::GetPath($arOpinion["PROPERTIES"]["OPINION_FILE"]["VALUE"])?>" <?if(!$is_image):?> target="_blank" <?else:?> data-gallery="s<?=$arOpinion['ID']?>" <?endif;?>class="link-blank">

                                                                    <?if(strlen($arOpinion["PROPERTIES"]["OPINION_FILE_TEXT"]["VALUE"]) > 0):?>

                                                                        <span class="bord-bot"><?=$arOpinion["PROPERTIES"]["OPINION_FILE_TEXT"]["~VALUE"]?></span>

                                                                    <?endif;?>

                                                                </a>
                                                            </div>
                                                        </div>

                                                    <?endif;?>

                                                    <?if(strlen($arOpinion["PROPERTIES"]["OPINION_VIDEO"]["VALUE"]) > 0):?>

                                                        <div class="col-md-auto col-12">

                                                            <div class="link-wrap">

                                                                <?$iframe = CPhoenix::createVideo($arOpinion["PROPERTIES"]["OPINION_VIDEO"]["VALUE"]);?> 


                                                                <a class="link-video call-modal callvideo" data-call-modal="<?=$iframe["ID"]?>">


                                                                    <?if(strlen($arOpinion["PROPERTIES"]["OPINION_VIDEO_TEXT"]["VALUE"]) > 0):?>

                                                                        <span class="bord-bot"><?=$arOpinion["PROPERTIES"]["OPINION_VIDEO_TEXT"]["~VALUE"]?></span>

                                                                    <?endif;?>
                                                                </a>
                                                            </div>

                                                        </div>
                                                 
                                                    <?endif;?>

                                                </div>
                                            </div>

                                        <?endif;?>

                                        <?if($arItem["BUTTON_CHANGE"]):?>
                                            <?=CreateButton($arOpinion, $show_menu, false)?>
                                        <?endif;?>
                                    </div>

                                </div>
                               
                            </div>

                        <?endforeach;?>
                        
                    </div>

                <?endif;?>

                <img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arItem["ID"]?>">
            </div>
            
        </div>

    <?endif;?>

    <?endif;?>
                    
<?endif;?>


<?if($arItem["PROPERTIES"]["OPINION_VIEW"]["VALUE_XML_ID"] == "block"):?>



    <div class="opinion <?=$arItem["PROPERTIES"]["OPINION_TEXT_COLOR"]["VALUE_XML_ID"]?> full-<?=$arItem["PROPERTIES"]["OPINION_VIEW"]["VALUE_XML_ID"]?>">

        <div class="opinion-table row">

            <div class="opinion-cell text-part col-lg-7 col-md-8 col-12 padding-special-style <?=$leftOrder?>">
                <?if($arItem["TITLE_CHANGE"]):?>
                    <?CreateHead($arItem, $show_menu, true, $main_key)?>
                <?endif;?>

                <?if(!empty($arItem["PROPERTIES"]["OPINION_TEXT"]["VALUE"])):?>
                    <div class="text italic">
                        <?=$arItem["PROPERTIES"]["OPINION_TEXT"]["~VALUE"]["TEXT"]?>
                    </div>
                <?endif;?>


                <?if(strlen($arItem["PROPERTIES"]["OPINION_VIDEO"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["OPINION_FILE"]["VALUE"]) > 0):?>

                    <div class="more-info">

                        <?if(strlen($arItem["PROPERTIES"]["OPINION_FILE"]["VALUE"]) > 0):?>

                            <?$arFile = CFile::MakeFileArray($arItem["PROPERTIES"]["OPINION_FILE"]["VALUE"]);?>
                            <?$is_image = CFile::IsImage($arFile["name"], $arFile["type"]);?>

                            <div class="link-wrap">

                                <a href="<?=CFile::GetPath($arItem["PROPERTIES"]["OPINION_FILE"]["VALUE"])?>" <?if(!$is_image):?> target="_blank" <?else:?> data-gallery="s<?=$arItem['ID']?>" <?endif;?>class="link-blank">

                                    <?if(strlen($arItem["PROPERTIES"]["OPINION_FILE_TEXT"]["VALUE"]) > 0):?>

                                        <span class="bord-bot"><?=$arItem["PROPERTIES"]["OPINION_FILE_TEXT"]["~VALUE"]?></span>

                                    <?endif;?>

                                </a>
                            </div>

                        <?endif;?>

                        <?if(strlen($arItem["PROPERTIES"]["OPINION_VIDEO"]["VALUE"]) > 0):?>

                            <div class="link-wrap">

                                <?$iframe = CPhoenix::createVideo($arItem["PROPERTIES"]["OPINION_VIDEO"]["VALUE"]);?> 


                                <a class="link-video call-modal callvideo" data-call-modal="<?=$iframe["ID"]?>">


                                    <?if(strlen($arItem["PROPERTIES"]["OPINION_VIDEO_TEXT"]["VALUE"]) > 0):?>

                                        <span class="bord-bot"><?=$arItem["PROPERTIES"]["OPINION_VIDEO_TEXT"]["~VALUE"]?></span>

                                    <?endif;?>
                                </a>
                            </div>
                     
                        <?endif;?>
                    </div>

                <?endif;?>

                <?if($arItem["BUTTON_CHANGE"]):?>
                    <?=CreateButton($arItem, $show_menu, false)?>
                <?endif;?>
            </div>

            
            <div class="opinion-cell z-image image-part col-lg-5 col-md-4 col-12 <?=$arItem["PROPERTIES"]["OPINION_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"]?> <?=$arItem["PROPERTIES"]["OPINION_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"]?>">

                <div class="wr-inner">                         

                    <?if($arItem["PROPERTIES"]["OPINION_IMAGE"]["VALUE"] > 0):?>
                        <?$img_big = CFile::ResizeImageGet($arItem["PROPERTIES"]["OPINION_IMAGE"]["VALUE"], array('width'=>500, 'height'=>500), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>
                        <img class="mx-auto d-block lazyload" data-src="<?=$img_big["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["OPINION_IMAGE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["OPINION_IMAGE"]["DESCRIPTION"]:"";?>"/>
                    <?else:?>
                        <img alt="" class="mx-auto d-block lazyload" data-src="<?=SITE_TEMPLATE_PATH?>/images/quote.png">
                    <?endif;?>

                    <?if(strlen($arItem["PROPERTIES"]["OPINION_NAME"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["OPINION_PROF"]["VALUE"]) > 0):?>

                        <div class="name-wrap">
                            <?if(strlen($arItem["PROPERTIES"]["OPINION_NAME"]["VALUE"]) > 0):?>
                                <div class="name main1">
                                     <?=$arItem["PROPERTIES"]["OPINION_NAME"]["~VALUE"]?>
                                </div>

                            <?endif;?>

                            <?if(strlen($arItem["PROPERTIES"]["OPINION_PROF"]["VALUE"]) > 0):?>
                                <div class="prof">
                                    <?=$arItem["PROPERTIES"]["OPINION_PROF"]["~VALUE"]?>
                                </div>
                            <?endif;?>
                        </div>

                    <?endif;?>

                </div> 
                
            </div>
            
         

        </div>
    </div>

<?endif;?>