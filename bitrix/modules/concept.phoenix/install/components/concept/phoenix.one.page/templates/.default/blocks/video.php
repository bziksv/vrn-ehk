<div class="video-block <?=$arItem["PROPERTIES"]["VIDEO_BLOCK_COLOR"]["VALUE_XML_ID"]?>">
    <img class="lazyload img-for-lazyload video-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png">

    <?if(count($arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["VALUE"]) <= 1):?>
    
        <?if(strlen($arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["VALUE"][0]) > 0):?>

            <?if(strlen($arItem["PROPERTIES"]["VIDEO_BLOCK_PICTURES"]["VALUE"][0]) > 0)
                $img = CFile::ResizeImageGet($arItem["PROPERTIES"]["VIDEO_BLOCK_PICTURES"]["VALUE"][0], array('width'=>1240, 'height'=>830), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
            ?>

            <div class="video-content lazyload" data-src="<?=$img["src"]?>">
                <?$iframe = CPhoenix::createVideo($arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["VALUE"][0]);?>

                <?if(strlen($arItem["PROPERTIES"]["VIDEO_BLOCK_PICTURES"]["VALUE"][0])<=0):?>
                    <div class="iframe-video-area" data-src="<?=htmlspecialcharsbx($iframe["HTML"])?>"></div>

                <?else:?>
                    <a class="call-modal callvideo big-play" data-call-modal="<?=$iframe["ID"]?>"></a>
                <?endif;?>

                
                
            </div>

            <?if(strlen($arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["DESCRIPTION"][0])>0):?>
                <div class="desc-one"><?=$arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["~DESCRIPTION"][0]?></div>
            <?endif;?>
        
        <?endif;?>
        
        

    <?else:?>

        <?

            $countVideo = count($arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["VALUE"]);
            $class="";

            if(!$show_menu)
            {
                if($countVideo == 2)
                    $class="col-xl-5 col-md-6 col-12";
            

                else
                    $class="col-md-3 col-12";
            }

            else
                $class="col-md-6 col-12";

            
        ?>



        <?if(is_array($arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["VALUE"]) && !empty($arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["VALUE"])):?>
            
            <div class="video-gallery-wrap <?if($countVideo == 2):?>two-video<?endif;?> row justify-content-center">

                <?if(!empty($arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["VALUE"]) && is_array($arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["VALUE"])):?>
    
                    <?foreach($arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["VALUE"] as $k=>$arVideo):?>

                        <?
                            if(strlen($arItem["PROPERTIES"]["VIDEO_BLOCK_PICTURES"]["VALUE"][$k])>0)
                            {
                                if($show_menu)
                                {
                                    if($countVideo == 2)
                                        $img = CFile::ResizeImageGet($arItem["PROPERTIES"]["VIDEO_BLOCK_PICTURES"]["VALUE"][$k], array('width'=>860, 'height'=>430), BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]); 
                                    
                                    else
                                        $img = CFile::ResizeImageGet($arItem["PROPERTIES"]["VIDEO_BLOCK_PICTURES"]["VALUE"][$k], array('width'=>475, 'height'=>250), BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                                }
                                else
                                {
                                    if($countVideo == 2)
                                        $img = CFile::ResizeImageGet($arItem["PROPERTIES"]["VIDEO_BLOCK_PICTURES"]["VALUE"][$k], array('width'=>860, 'height'=>430), BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]); 
                                    
                                    else
                                        $img = CFile::ResizeImageGet($arItem["PROPERTIES"]["VIDEO_BLOCK_PICTURES"]["VALUE"][$k], array('width'=>475, 'height'=>250), BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                                }
                            }

                            else
                                $img["src"] = SITE_TEMPLATE_PATH."/images/video-pic.jpg";
                            
                        ?>

                        
                        <div class="video-gallery <?=$class?><?if($k == 0):?> <?=$offsetClass?><?endif;?><?if(($k+1) == $needKey):?> <?=$arNeed[$residual]["OFFSET"]?><?endif;?>">
                            <div class="video-gallery-element">


                                <?$iframe = CPhoenix::createVideo($arVideo);?>  
                                <table class="videoimage-wrap lazyload" data-src="<?=$img["src"]?>">
                                    <tr>
                                        <td>
                                            <a class="call-modal callvideo" data-call-modal="<?=$iframe["ID"]?>">
                                              
                                                <div class="play"></div>
                                            </a>

                                        </td>
                                    </tr>
                                </table>

                                <?if(strlen($arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["DESCRIPTION"][$k])>0):?>
                                    <div class="desc"><?=$arItem["PROPERTIES"]["VIDEO_BLOCK_CODE"]["~DESCRIPTION"][$k]?></div>
                                <?endif;?>
                            </div>

                        </div>  

                    <?endforeach;?>

                <?endif;?>

            </div>

        <?endif;?>


    <?endif;?>

    <?if(isset($arItem["PROPERTIES"]["VIDEO_BLOCK_TEXT"]["VALUE"]["TEXT"]) > 0):?>
            
        <div class="text text-content">
            <?=$arItem["PROPERTIES"]["VIDEO_BLOCK_TEXT"]["~VALUE"]["TEXT"]?>
        </div>
    
    <?endif;?>

</div>
