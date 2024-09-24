
<?if($arItem["PROPERTIES"]["GALLERY_VIEW"]["VALUE_XML_ID"] == "")
    $arItem["PROPERTIES"]["GALLERY_VIEW"]["VALUE_XML_ID"] = "gallery";
?>
<?if(is_array($arItem["PROPERTIES"]["GALLERY"]["VALUE"]) && !empty($arItem["PROPERTIES"]["GALLERY"]["VALUE"])):?>



    <?if($arItem["PROPERTIES"]["GALLERY_VIEW"]["VALUE_XML_ID"] == "slider"):?>

        <?

            $arWaterMark = Array();

            if($arItem["PROPERTIES"]["GALLERY_WATERMARK"]["VALUE"] > 0){

                $arWaterMark = Array(
                    array(
                        "name" => "watermark",
                        "position" => "center",
                        "type" => "image",
                        "size" => "big",
                        "file" => $_SERVER["DOCUMENT_ROOT"].CFile::GetPath($arItem["PROPERTIES"]["GALLERY_WATERMARK"]["VALUE"]), 
                        "fill" => "exact",
                    )
                );
            }
         
        ?>
    

    <?
        $count_slide = 1;

        if($arItem["PROPERTIES"]["GALLERY_PICS_IN_SLIDE"]["VALUE"] > 0)
        {
            $count_slide = $arItem["PROPERTIES"]["GALLERY_PICS_IN_SLIDE"]["VALUE"];

            if($count_slide < 1)
                $count_slide = 1;

            if($count_slide > 6)
                $count_slide = 6;
        }
            
    ?>
        <div class="img-for-lazyload-parent">
            <img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arItem["ID"]?>">

            <div class="slider-gallery clearfix <?if($count_slide > 1):?>over-one<?endif;?> slider-gallery-<?=$count_slide?> parent-slider-item-js" data-slide-visible = "<?=$count_slide?>">

                <?foreach($arItem["PROPERTIES"]["GALLERY"]["VALUE"] as $k=>$photo):?>

                    <div class="slide-style <?if($k!=0) echo 'noactive-slide-lazyload';?>">
                        <div class="wrap-slide">
                            <table>
                                <tr>
                                    <td>
                                        
                                        <?
                                            $img = CFile::ResizeImageGet($photo, array('width'=>1200, 'height'=>700), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, $arWaterMark, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                                            $img_big = CFile::ResizeImageGet($photo, array('width'=>2000, 'height'=>2000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, $arWaterMark, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                                        ?>

                                        <a href="<?=$img_big["src"]?>" title="<?=CPhoenix::prepareText($arItem["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$k])?>" data-gallery="gal<?=$arItem['ID']?>" class="cursor-loop">
                                            <div class="slide-element lazyload" style="background-image: url('<?=$img["src"]?>');"></div>
                                        </a>   

                                        
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <?if($arItem["GALLERY_COUNT_DESC"] && $count_slide == 1):?>
                            <div class="desc"><?=$arItem["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$k]?></div>
                        <?endif;?>
                        
                    </div>

                <?endforeach;?>

            </div>
            
            <img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arItem["ID"]?>">
        </div>

    <?else:?>

        <div class="
            gallery-block 
            <?if($arItem["PROPERTIES"]["GALLERY_BORDER"]["VALUE"] == "Y"):?>
                border-img-on
            <?endif;?>
            <?=$arItem["PROPERTIES"]["GALLERY_DESK_COLOR"]["VALUE_XML_ID"]?>
            <?=$arItem["PROPERTIES"]["GALLERY_VIEW"]["VALUE_XML_ID"]?>
            "
        >
            

            <? 
                $arSize = Array();

                if($arItem["PROPERTIES"]["GALLERY_VIEW"]["VALUE_XML_ID"] == "nogallery")
                {
                    $arSize[3] = array('width'=>435, 'height'=>435);
                    $arSize[4] = array('width'=>325, 'height'=>325);
                    $arSize[6] = array('width'=>215, 'height'=>215);
                }
                else
                {
                    $arSize[3] = array('width'=>435, 'height'=>435);
                    $arSize[4] = array('width'=>325, 'height'=>325);
                    $arSize[6] = array('width'=>215, 'height'=>215);
                }

                

                $arStyle = Array();

                $arStyle[3] = 'big-size';
                $arStyle[4] = 'middle-size';
                $arStyle[6] = 'small-size';

                $class = "";    
                $str = 1;
                $rows = 0;


                $arWaterMark = Array();

                if($arItem["PROPERTIES"]["GALLERY_WATERMARK"]["VALUE"] > 0){

                    $arWaterMark = Array(
                        array(
                            "name" => "watermark",
                            "position" => "center",
                            "type" => "image",
                            "size" => "real",
                            "file" => $_SERVER["DOCUMENT_ROOT"].CFile::GetPath($arItem["PROPERTIES"]["GALLERY_WATERMARK"]["VALUE"]), 
                            "fill" => "exact",
                        )
                    );
                }                 
            ?>

            <div class="row">

                <?foreach($arItem["PROPERTIES"]["GALLERY"]["VALUE"] as $k=>$photo):?>

                    <?if($photo <= 0) continue;?>

                    <?$rows++;?>  

                    <?$class = 12 / $arItem["GALLERY_COUNT"][$str];?>
                       
                    <div class="col-md-<?=$class?> <?if($arItem["PROPERTIES"]["GALLERY_VIEW"]["VALUE_XML_ID"] == "nogallery"):?> col-sm-4 col-6 <?else:?> col-4<?endif;?>">
                        
                        <?$img_big = CFile::ResizeImageGet($photo, array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arWaterMark);?>

                        <?if($arItem["PROPERTIES"]["GALLERY_VIEW"]["VALUE_XML_ID"] == "nogallery"):?>
                            <?$img = CFile::ResizeImageGet($photo, $arSize[$arItem["GALLERY_COUNT"][$str]], BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                        <?else:?>
                            <?$img = CFile::ResizeImageGet($photo, $arSize[$arItem["GALLERY_COUNT"][$str]], BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                        <?endif;?>

                        

                        <a href="<?=$img_big["src"]?>" data-gallery="gal<?=$arItem['ID']?>" class="d-block cursor-loop" title="<?=CPhoenix::prepareText($arItem["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$k])?>">

                            <?if($arItem["PROPERTIES"]["GALLERY_VIEW"]["VALUE_XML_ID"] == "gallery"):?>

                                <div class="gallery-img <?=$arStyle[$arItem["GALLERY_COUNT"][$str]]?> lazyload" <?if($arItem["PROPERTIES"]["GALLERY_VIEW"]["VALUE_XML_ID"] == "gallery"):?>data-src="<?=$img["src"]?>"<?endif;?>>
                                    <div class="corner-line"></div>
                                </div>

                            <?elseif($arItem["PROPERTIES"]["GALLERY_VIEW"]["VALUE_XML_ID"] == "nogallery"):?>

                                <div class="gallery-img <?=$arStyle[$arItem["GALLERY_COUNT"][$str]]?>">

                                    <table>
                                        <tr>
                                            <td>
                                                <div class="gallery-img-wrap">
                                                    <div class="corner-line"></div>
                                                    <img class="mx-auto d-block lazyload" data-src="<?=$img["src"]?>" alt="<?=strip_tags($arItem["PROPERTIES"]["GALLERY"]["~DESCRIPTION"][$k])?>"/>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <?if(strlen($arItem["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$k]) > 0 ):?>
                                        <div class="text-img">
                                            <?=$arItem["PROPERTIES"]["GALLERY"]["~DESCRIPTION"][$k]?>
                                        </div>
                                    <?endif;?>
                                </div>

                            <?endif;?>
                        </a>


                    </div>

                    <?if($rows >= $arItem["GALLERY_COUNT"][$str]):?>
                        
                        <?$rows = 0;?>
                        <?$str++;?>

                        <?if($str>4) $str=4;?>

                   <?endif;?>
             
                <?endforeach;?>

            </div>

                    
        </div>
    <?endif;?>
    


<?endif;?>