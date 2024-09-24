<?

if($arItem["PROPERTIES"]["ADVANTAGES_VIEW_SIZE"]["VALUE_XML_ID"] == '') 
    $arItem["PROPERTIES"]["ADVANTAGES_VIEW_SIZE"]["VALUE_XML_ID"] = 'big';

$view_size = $arItem["PROPERTIES"]["ADVANTAGES_VIEW_SIZE"]["VALUE_XML_ID"];

if($view_size == "big")
    $view_size = "big-advantages";
if($view_size == "small")
    $view_size = "small-advantages";

if($arItem["PROPERTIES"]["ADVANTAGES_TYPE_PICTURE"]["VALUE_XML_ID"] == '') 
    $arItem["PROPERTIES"]["ADVANTAGES_TYPE_PICTURE"]["VALUE_XML_ID"] = 'images';

if($arItem["PROPERTIES"]["ADVANTAGES_VIEW"]["VALUE_XML_ID"] == '') 
    $arItem["PROPERTIES"]["ADVANTAGES_VIEW"]["VALUE_XML_ID"] = 'flat';

if($arItem["PROPERTIES"]["ADVANTAGES_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"] == '') 
    $arItem["PROPERTIES"]["ADVANTAGES_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"] = 'left';

$position_hor = $arItem["PROPERTIES"]["ADVANTAGES_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"];

if($position_hor == "left")
    $position_hor = "order-first";

if($position_hor == "right")
    $position_hor = "order-last";


if($arItem["PROPERTIES"]["ADVANTAGES_IMAGE_POSITION"]["VALUE_XML_ID"] == '') 
    $arItem["PROPERTIES"]["ADVANTAGES_IMAGE_POSITION"]["VALUE_XML_ID"] = 'middle';


$position_vert = $arItem["PROPERTIES"]["ADVANTAGES_IMAGE_POSITION"]["VALUE_XML_ID"]; 

if($position_vert == "top")
    $position_vert = "align-self-start";

if($position_vert == "middle")
    $position_vert = "align-self-center";

if($position_vert == "bottom")
    $position_vert = "align-self-end";



if( $arItem["PROPERTIES"]["ADVANTAGES_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "" )
    $arItem["PROPERTIES"]["ADVANTAGES_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] = "order-first-mob";


?>



<?if($arItem["PROPERTIES"]["ADVANTAGES_VIEW"]["VALUE_XML_ID"] == "flat"):?>


    <?$count = $arItem["PIC_MAX"];?>

    <div class=
        "
            row
            no-gutters
            advantages
            <?=$arItem["PROPERTIES"]["ADVANTAGES_VIEW"]["VALUE_XML_ID"]?>
            <?=$arItem["PROPERTIES"]["ADVANTAGES_TYPE_PICTURE"]["VALUE_XML_ID"]?>
            <?=$arItem["PROPERTIES"]["ADVANTAGES_TEXT_COLOR"]["VALUE_XML_ID"]?>
            <?=$view_size?> 
            <?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>parent-animate<?endif;?>
            <?if(strlen($arItem["PROPERTIES"]["ADVANTAGES_IMAGE"]["VALUE"]) > 0):?> image-on<?endif;?> 
        "
    >

        <?if(!$show_menu):?>
            
            <div class="advantages-table">
                <div class="row">

                    <div class="advantages-cell text-part padding-change z-text <?if($arItem["PROPERTIES"]["ADVANTAGES_IMAGE"]["VALUE"] > 0):?>col-lg-7 col-md-12 col-12 <?else:?>col-12<?endif;?>">
                    
                        <div class="<?if($arItem["PROPERTIES"]["ADVANTAGES_IMAGE"]["VALUE"] > 0):?><?if($position_hor == "order-first"):?> wrap-padding-left<?else:?> wrap-padding-right<?endif;?><?endif;?>">

                            <?if($arItem["TITLE_CHANGE"]):?>
                                <?CreateHead($arItem, $show_menu, true, $main_key)?>
                                <div class="clearfix"></div>
                            <?endif;?>

                            <div class="part-wrap row <?if($arItem["PROPERTIES"]["ADVANTAGES_IMAGE"]["VALUE"] <= 0):?>justify-content-center<?endif;?>">

                                <?if(strlen($count)>0):?>

                                    <?$class = "col-lg-4 col-sm-6 col-12";?>  
                                    

                                        <?if($view_size == "small-advantages"):?>

                                            <?$class = "col-lg-3 col-sm-6 col-12";?>  

                                            <?if(strlen($arItem["PROPERTIES"]["ADVANTAGES_IMAGE"]["VALUE"]) > 0):?>
                                                <?$class = "col-sm-6 col-12";?>
                                            <?endif;?>

                                        <?else:?>

                                            <?if(strlen($arItem["PROPERTIES"]["ADVANTAGES_IMAGE"]["VALUE"]) > 0):?>
                                                <?$class = "col-sm-6 col-12";?>
                                            <?else:?>

                                                <?if($count % 4 == 0):?>
                                                    <?$class = "col-lg-3 col-sm-6 col-12 four-cols";?>
                                                <?endif;?>

                                            <?endif;?>
                                        <?endif;?>

                                        <?for($i = 0; $i < $count; $i++):?>

                                            <div class="<?=$class?>">

                                                <div class="element <?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>child-animate opacity-zero<?endif;?>">
                                                    

                                                    <?if($arItem["PIC_COUNT"] > 0 || $arItem["IC_COUNT"]):?>
                                                
                                                        <div class="image-table">
                                                            <div class="image-cell">

                                                                <?if($arItem["PROPERTIES"]["ADVANTAGES_TYPE_PICTURE"]["VALUE_XML_ID"] == "images"):?>


                                                            
                                                                    <?if($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["VALUE"][$i] > 0):?>


                                                                        <?if($arItem["TITLE_CHANGE"]):?>

                                                                            <?$file = CFile::ResizeImageGet($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["VALUE"][$i], array('width'=>720, 'height'=>256), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>

                                                                        <?else:?>

                                                                            <?$file = CFile::ResizeImageGet($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["VALUE"][$i], array('width'=>720, 'height'=>470), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>

                                                                        <?endif;?>


                                                                        <img class="img-fluid lazyload" data-src="<?=$file["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["DESCRIPTION"][$i]))? strip_tags($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["~DESCRIPTION"][$i]):"";?>"/>


                                                                    <?endif;?>

                                                                <?elseif($arItem["PROPERTIES"]["ADVANTAGES_TYPE_PICTURE"]["VALUE_XML_ID"] == "icons"):?>

                                                                    <i class="style-ic <?=$arItem["PROPERTIES"]["ADVANTAGES_ICONS"]["VALUE"][$i]?>" style="color: <?=$arItem["PROPERTIES"]["ADVANTAGES_ICONS"]["~DESCRIPTION"][$i]?>"></i>

                                                                <?endif;?>
                                                                
                                                            </div>
                                                        </div>
                                                    
                                                    <?endif;?>


                                                    
                                                    <?if($arItem["PIC_DESC_COUNT"] > 0 || $arItem["PIC_NAME_COUNT"] > 0):?>

                                                        <div class="text-wrap <?if($arItem["PIC_COUNT"] > 0 || $arItem["IC_COUNT"]):?>icons-on<?endif;?>">

                                                            <?if(strlen($arItem["PROPERTIES"]["ADVANTAGES_IMAGE"]["VALUE"]) > 0 || $view_size == "small-advantages"):?>


                                                                <?if(strlen($arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["~DESCRIPTION"][$i]) > 0):?>

                                                                    <div class="name main1">
                                                                        <?=$arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["~DESCRIPTION"][$i]?>
                                                                    </div>

                                                                <?endif;?>


                                                            <?else:?>


                                                                <?if($arItem["PIC_NAME_COUNT"] > 0):?>

                                                                    <div class="name main1">
                                                                        <?=$arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["~DESCRIPTION"][$i]?>
                                                                    </div>

                                                                <?endif;?>


                                                            <?endif;?>



                                                            <?if($arItem["PIC_DESC_COUNT"] > 0):?>
                                                            
                                                                <div class="text">
                                                                    <?=$arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["~VALUE"][$i]?>
                                                                </div>

                                                            <?endif;?>

                                                        </div>
                                                    
                                                    <?endif;?>

                                                </div>

                                            </div>
                                        
                                        <?endfor;?>

                                    

                                <?endif;?>

                            </div>


                            <?if($arItem["BUTTON_CHANGE"]):?>
                                <?=CreateButton($arItem, $show_menu, false)?>
                            <?endif;?>
                        </div>

                        
                    </div>

                    <?if(strlen($arItem["PROPERTIES"]["ADVANTAGES_IMAGE"]["VALUE"]) > 0):?>
                    
                        <div class="advantages-cell image-part z-image col-lg-5 col-12 <?=$position_vert?> <?=$position_hor?> <?=$arItem["PROPERTIES"]["ADVANTAGES_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"]?>">
                        
                            <?$file = CFile::ResizeImageGet($arItem["PROPERTIES"]["ADVANTAGES_IMAGE"]["VALUE"], array('width'=>800, 'height'=>800), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                        
                            <img class="img-fluid mx-auto d-block lazyload" data-src="<?=$file["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["ADVANTAGES_IMAGE"]["DESCRIPTION"][$i]))? strip_tags($arItem["PROPERTIES"]["ADVANTAGES_IMAGE"]["~DESCRIPTION"][$i]):"";?>"/>
                        
                        </div>
                        
                    <?endif;?>

                </div>

            </div>


        <?else:?>

            <?

                $class = 'col-12';
                $class2 = 'col-md-3 col-4';
                $class3 = 'col-md-9 col-8';

                if($view_size == "small-advantages")
                    $class = 'col-lg-6 col-12';
                    
                
            ?>


                <?for($i = 0; $i < $count; $i++):?>

                    <?if($i !=0):?>
                        <div class="col-12 <? if($view_size == "small-advantages"):?>visible-sm visible-xs<?endif;?>">
                            <div class="adv-line"></div>
                        </div>
                    <?endif;?>

                    <div class="<?=$class?>">

                        <div class="adv-table <?=$view_size?> row">

                            <?if(strlen($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["VALUE"][$i])>0 || strlen($arItem["PROPERTIES"]["ADVANTAGES_ICONS"]["VALUE"][$i])>0):?>

                                <div class="adv-cell left <?=$class2?>">


                                    <?if($arItem["PROPERTIES"]["ADVANTAGES_TYPE_PICTURE"]["VALUE_XML_ID"] == "icons"):?>

                                         <i class="style-ic <?=$arItem["PROPERTIES"]["ADVANTAGES_ICONS"]["VALUE"][$i]?>" style="color: <?=$arItem["PROPERTIES"]["ADVANTAGES_ICONS"]["~DESCRIPTION"][$i]?>"></i>
                                    
                                    <?else:?>
                                        <?$img = CFile::ResizeImageGet($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["VALUE"][$i], array('width'=>200, 'height'=>800), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                                        <img data-src="<?=$img['src']?>" class="img-fluid mx-auto d-block lazyload" alt="<?=(strlen($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["DESCRIPTION"][$i]))? strip_tags($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["~DESCRIPTION"][$i]):"";?>">

                                    <?endif;?>
                                </div>

                            <?endif;?>

                            <div class="adv-cell right <?=$class3?>">
                                <?if(strlen($arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["~DESCRIPTION"][$i])>0):?>
                                    <div class="title bold"><?=$arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["~DESCRIPTION"][$i]?></div>
                                <?endif;?>

                                <?if(strlen($arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["~VALUE"][$i])>0):?>
                                    <div class="desc"><?=$arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["~VALUE"][$i]?></div>
                                <?endif;?>
                                
                            </div>

                        </div>

                    </div>

                <?endfor;?>

        

  
        <?endif;?>

        

    </div><!-- ^advantages -->




<?elseif($arItem["PROPERTIES"]["ADVANTAGES_VIEW"]["VALUE_XML_ID"] == "slider"):?>

    <div>
        <img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arItem["ID"]?>">


        <div class="slider-advantages <?=$view_size?>-slide <?=$arItem["PROPERTIES"]["ADVANTAGES_TEXT_COLOR"]["VALUE_XML_ID"]?> parent-slider-item-js">

            <?for($i = 0; $i < $arItem["PIC_MAX"]; $i++):?>

                <div class="col-12 <?=($i != 0)?'noactive-slide-lazyload':'';?>">
                    <div class="div-table">
                           

                        <?if(strlen($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["VALUE"][$i])>0 || strlen($arItem["PROPERTIES"]["ADVANTAGES_ICONS"]["VALUE"][$i])>0):?>
                            <div class="div-cell left">
                                <table>
                                    <tr>
                                        <td>
                                            <?if($arItem["PROPERTIES"]["ADVANTAGES_TYPE_PICTURE"]["VALUE_XML_ID"] == "icons"):?>

                                                 <i class="style-ic <?=$arItem["PROPERTIES"]["ADVANTAGES_ICONS"]["VALUE"][$i]?>" style="color: <?=$arItem["PROPERTIES"]["ADVANTAGES_ICONS"]["~DESCRIPTION"][$i]?>"></i>
                                            
                                            <?else:?>

                                                <?$file = CFile::ResizeImageGet($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["VALUE"][$i], array('width'=>1200, 'height'=>960), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                                                <img data-src="<?=$file['src']?>" class="img-fluid mx-auto d-block lazyload" alt="<?=(strlen($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["DESCRIPTION"][$i]))? strip_tags($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["~DESCRIPTION"][$i]):"";?>">

                                            <?endif;?>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?endif;?>

                        <div class="div-cell right">

                            <?if(strlen($arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["~DESCRIPTION"][$i])>0):?>
                                <div class="title bold"><?=$arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["~DESCRIPTION"][$i]?></div>
                            <?endif;?>

                            <?if(strlen($arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["~VALUE"][$i])>0):?>
                                <div class="desc"><?=$arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["~VALUE"][$i]?></div>
                            <?endif;?>

                        </div>

                    </div>

                </div>

            <?endfor;?>


        </div>

        <img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arItem["ID"]?>">
    </div>
<?endif;?>