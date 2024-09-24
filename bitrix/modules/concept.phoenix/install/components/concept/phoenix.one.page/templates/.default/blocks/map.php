<?if( $arItem["PROPERTIES"]["MAP_SIZE"]["VALUE_XML_ID"] != "in-container"):?>
        
    </div><!-- close from map container -->

<?endif;?>


<?
if($arItem["PROPERTIES"]["MAP_SUBSTRATE_POS"]["VALUE_XML_ID"] == "")
    $arItem["PROPERTIES"]["MAP_SUBSTRATE_POS"]["VALUE_XML_ID"] = "info-on-map";
?>



<?if( $arItem["PROPERTIES"]["MAP_VIEW"]["VALUE_XML_ID"] == "full" ):?>

    <?
        $pic = NULL;

        if($arItem["PROPERTIES"]["MAP_PICTURE"]["VALUE"])
            $pic = CFile::ResizeImageGet($arItem["PROPERTIES"]["MAP_PICTURE"]["VALUE"], array('width'=>500, 'height'=>700), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

        $left_col = "col-lg-3 col-12";
        $left_active = false;

        $center_col = "col-lg-6 col-12 small";
        $center_active = false;

        $rigth_col = "col-lg-3 col-12";
        $rigth_active = false;

        $width = 500;
        $height = 400;


        if(strlen($arItem["PROPERTIES"]["MAP_PICTURE"]["VALUE"]))
            $left_active = true;
        

        if(strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) || strlen($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"]) || strlen($arItem["PROPERTIES"]["BUTTON_NAME"]["VALUE"]) || strlen($arItem["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"]) || !empty($arItem["PROPERTIES"]["MAP_GALLERY"]["VALUE"]))
           $center_active = true;
        

        if(!empty($arItem["ADVS"]))
            $rigth_active = true;
        

        if(!$left_active && $center_active && !$rigth_active)
        {
            $center_col = "col-12 big";
            $width = 350;
            $height = 220;
        }
        
        if( ($left_active && $center_active && !$rigth_active) || (!$left_active && $center_active && $rigth_active) )
        {
            $center_col = "col-lg-9 col-12 middle";
            $width = 300;
            $height = 200;
        }
       
    ?>

    <?if($left_active || $center_active || $rigth_active):?>

        <div class="map-head-full <?if( $arItem["PROPERTIES"]["MAP_SIZE"]["VALUE_XML_ID"] != "in-container" && !$show_menu ):?>container<?endif;?>">

            <div class="row">

                

                <?if($left_active):?>

                    <div class="<?=$left_col?> pad-top">
                        <img class="d-block mx-auto lazyload map-title-img" data-src="<?=$pic["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["MAP_PICTURE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["MAP_PICTURE"]["DESCRIPTION"]:"";?>"/>
                    </div>

                <?endif;?>

                <?if($center_active):?>

                    <div class="<?=$center_col?> pad-top">
                        <?
                            if( $arItem["TITLE_CHANGE"] && (strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) || strlen($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"])) )
                                echo CreateHead( $arItem, $show_menu, true, $main_key );

                         
                            if( $arItem["BUTTON_CHANGE"] && (strlen($arItem["PROPERTIES"]["BUTTON_NAME"]["VALUE"]) || strlen($arItem["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"])) )
                                echo CreateButton( $arItem, $show_menu, false, "empty", "bord");
                        ?>

                        <?if( !empty($arItem["PROPERTIES"]["MAP_GALLERY"]["VALUE"]) ):?>

                            <div class="map-gallery">
                                <div class="row">
        
                                    <?foreach($arItem["PROPERTIES"]["MAP_GALLERY"]["VALUE"] as $k=>$arElement):?>

                                        <div class="col-lg-2 col-sm-3 col-4">

                                            <?
                                                $pic = NULL;
                                                $pic = CFile::ResizeImageGet($arElement, array('width'=>$width, 'height'=>$height), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                                                $pic_big = NULL;
                                                $pic_big = CFile::ResizeImageGet($arElement, array('width'=>1500, 'height'=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                                            ?>

                                            <a href="<?=$pic_big["src"]?>" title="<?=CPhoenix::prepareText($arItem["PROPERTIES"]["MAP_GALLERY"]["DESCRIPTION"][$k])?>" data-gallery="map-gal<?=$arItem['ID']?>" class="cursor-loop">
                                                <div class="map-gallery-element lazyload" data-src="<?=$pic["src"]?>"></div>
                                            </a>
                                        </div>

                                    <?endforeach;?>

                                </div>

                            </div>

                        <?endif;?>
                    </div>

                <?endif;?>

                <?if($rigth_active):?>

                    <div class="<?=$rigth_col?> line-left">
                        <div class="wrap-adv-elements pad-top h-100">
                            <table class = "adv-elements">

                                <?if(!empty($arItem["ADVS"])):?>
                                    <?foreach($arItem["ADVS"] as $k=>$arAdv):?>
                                        <tr>
                                        
                                            <td class="img">

                                                <?if(strlen($arAdv["PREVIEW_PICTURE_SRC"])):?>
                                            
                                                    <img data-src="<?=$arAdv["PREVIEW_PICTURE_SRC"]?>" class="lazyload" alt="<?=(strlen($arAdv["PREVIEW_PICTURE_DESCRIPTION"]))? $arAdv["PREVIEW_PICTURE_DESCRIPTION"]:"";?>"/>

                                                <?elseif(strlen($arAdv["ICON"]) && $arAdv["PREVIEW_PICTURE"] <= 0):?>
                         
                                                    <div class="icon">
                                                        <i class="<?=$arAdv["ICON"]?>" <?if(strlen($arAdv["ICON"]) > 0):?>style="color: <?=$arAdv["COLOR"]?>;"<?endif;?>></i>
                                                    </div>
                                                    
                                                <?else:?>
                                                    <div class="icon default"></div>
                                                <?endif;?>
                                                
                                            </td>
                                            
                                            <td class='text'><?=$arAdv["SIGN"]?></td>
                                            
                                        </tr>

                                    <?endforeach;?>

                                <?endif;?>  
                            </table>
                        </div>
                    </div>

                <?endif;?>

            </div>
        </div>

    <?endif;?>

<?endif;?>


<div class="map-block <?if(!strlen($arItem["PROPERTIES"]["MAP"]["VALUE"]) > 0):?>no-map <?else:?><?=$arItem["PROPERTIES"]["MAP_SIZE"]["VALUE_XML_ID"]?> <?=$arItem["PROPERTIES"]["MAP_SUBSTRATE_POS"]["VALUE_XML_ID"]?><?endif;?>">

    <?if(strlen($arItem["PROPERTIES"]["MAP"]["VALUE"]) > 0):?>
        <img class="lazyload img-for-lazyload map-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png">
    <?endif;?>

    <?if(
        $arItem["PROPERTIES"]["MAP_SUBSTRATE_POS"]["VALUE_XML_ID"] == "info-on-map"
        && strlen($arItem["PROPERTIES"]["MAP"]["VALUE"])>0
        && (strlen($arItem["PROPERTIES"]["MAP_NAME"]["VALUE"])>0
        || strlen($arItem["PROPERTIES"]["MAP_ADDRESS"]["~VALUE"]) > 0
        || !empty($arItem["PROPERTIES"]["MAP_PHONE"]["VALUE"])
        || !empty($arItem["PROPERTIES"]["MAP_MAIL"]["VALUE"]))):?>

        <div class="wr-desc-table">
            
            <div class="<?if( $arItem["PROPERTIES"]["MAP_SIZE"]["VALUE_XML_ID"] != "in-container" && !$show_menu ):?>container<?endif;?>">

                <div class="<?if( $arItem["PROPERTIES"]["MAP_SIZE"]["VALUE_XML_ID"] != "in-container" && !$show_menu ):?>row<?endif;?>">
                
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="desc-table">

                            <?if(strlen($arItem["PROPERTIES"]["MAP_NAME"]["VALUE"]) > 0):?>

                                <div class="name">
                                    <?=$arItem["PROPERTIES"]["MAP_NAME"]["~VALUE"]?>
                                </div>

                            <?endif;?>


                            <?if(strlen($arItem["PROPERTIES"]["MAP_ADDRESS"]["VALUE"]) > 0):?>

                                <div class="item">
                                   <!--  <div class="text-cell icon icon-point"></div> -->
                                   <?=$arItem["PROPERTIES"]["MAP_ADDRESS"]["~VALUE"]?>
                                </div>

                            <?endif;?>

                            <?if(!empty($arItem["PROPERTIES"]["MAP_PHONE"]["VALUE"])):?>

                                <div class="item phone bold">
                                    <!-- <div class="text-cell icon icon-phone">
                                    </div> -->
                                    
                                    <?=implode("<br/>",$arItem["PROPERTIES"]["MAP_PHONE"]["~VALUE"])?>
                                </div>

                            <?endif;?>

                            <?if(!empty($arItem["PROPERTIES"]["MAP_MAIL"]["VALUE"])):?>

                                <div class="item">
                                    <!-- <div class="text-cell icon icon-mail">
                                    </div> -->
                                    <?foreach($arItem["PROPERTIES"]["MAP_MAIL"]["~VALUE"] as $k => $arMail):?>   

                                        <?if($k != 0):?>
                                            <br>
                                        <?endif;?>

                                         <a href="mailto:<?=$arMail?>"><span class="bord-bot"><?=$arMail?></span></a>

                                    <?endforeach;?>
                                </div>

                            <?endif;?>

                            <?if(!empty($arItem["PROPERTIES"]["MAP_DESC"]["~VALUE"])):?>

                                <div class="item">
                                    <?=$arItem["PROPERTIES"]["MAP_DESC"]["~VALUE"]["TEXT"]?>
                                </div>

                            <?endif;?>

                        </div>
                    </div>

                </div>
                
            </div>
      

        </div>

    <?else:?>

        <?if(strlen($arItem["PROPERTIES"]["MAP_NAME"]["VALUE"])
                || strlen($arItem["PROPERTIES"]["MAP_ADDRESS"]["VALUE"]) 
                || (strlen($arItem["PROPERTIES"]["MAP_NAME"]["VALUE"]) && $show_menu)
                || !empty($arItem["PROPERTIES"]["MAP_PHONE"]["VALUE"]) 
                || !empty($arItem["PROPERTIES"]["MAP_MAIL"]["VALUE"])
                || !empty($arItem["PROPERTIES"]["MAP_DESC"]["~VALUE"])
            ):?>

            <div class="wr-desc-table">
                
                <div class="<?if( $arItem["PROPERTIES"]["MAP_SIZE"]["VALUE_XML_ID"] != "in-container" && !$show_menu ):?>container<?endif;?>">

                    <div class="desc-table">
                

                        <?
                            $class1='col-lg-3 col-md-6 col-12';
                            $class2='col-lg-3 col-md-6 col-12';
                            $class3='col-lg-3 col-md-6 col-12';
                            $class4='col-lg-3 col-md-6 col-12';

                            if($show_menu)
                            {
                                $class1='hidden';
                                $class2='col-lg-4 col-md-6 col-12';
                                $class3='col-lg-4 col-md-6 col-12';
                                $class4='col-lg-4 col-md-6 col-12';
                            }
                        ?>

                        <div class="row no-margin">

                            <?if(strlen($arItem["PROPERTIES"]["MAP_NAME"]["VALUE"]) > 0):?>

                                <div class="<?=$class1?> item name">

                                    <div class="row no-gutters align-items-center h-100">
                                        <div class="col">

                                            <?=$arItem["PROPERTIES"]["MAP_NAME"]["~VALUE"]?>

                                        </div>
                                        
                                    </div>
                                </div>

                            <?endif;?>

                            <?if(strlen($arItem["PROPERTIES"]["MAP_ADDRESS"]["VALUE"]) > 0 || (strlen($arItem["PROPERTIES"]["MAP_NAME"]["VALUE"]) > 0 && $show_menu)):?>


                                <div class="<?=$class2?> item">

                                    <div class="row no-gutters align-items-center h-100">
                                        <div class="col">

                                            <?if(strlen($arItem["PROPERTIES"]["MAP_NAME"]["VALUE"]) > 0 && $show_menu):?>
                                                <div class="name">
                                                    <?=$arItem["PROPERTIES"]["MAP_NAME"]["~VALUE"]?>
                                                </div>

                                            <?endif;?>
                                            <?=$arItem["PROPERTIES"]["MAP_ADDRESS"]["~VALUE"]?>
                                        </div>
                                    </div>
                                    
                                </div>

                            <?endif;?>


                            <?if(!empty($arItem["PROPERTIES"]["MAP_PHONE"]["VALUE"]) || !empty($arItem["PROPERTIES"]["MAP_MAIL"]["VALUE"])):?>

                                <div class="<?=$class3?> item">

                                    <div class="row no-gutters align-items-center h-100">
                                        <div class="col">

                                            <?if(!empty($arItem["PROPERTIES"]["MAP_PHONE"]["VALUE"])):?>
                                                <div class="phone bold">
                                                
                                                    <?=implode("<br/>",$arItem["PROPERTIES"]["MAP_PHONE"]["~VALUE"])?>

                                                </div>
                                            <?endif;?>

                                            <?if(!empty($arItem["PROPERTIES"]["MAP_MAIL"]["VALUE"])):?>
                                                <div class="e-mail">
                                                    <?foreach($arItem["PROPERTIES"]["MAP_MAIL"]["~VALUE"] as $k => $arMail):?>   

                                                        <?if($k != 0):?>
                                                            <br>
                                                        <?endif;?>
                                                        <a href="mailto:<?=$arMail?>"><span class="bord-bot"><?=$arMail?></span></a>

                                                    <?endforeach;?>
                                                </div>

                                            <?endif;?>
                                        </div>
                                    </div>

                                </div>


                            <?endif;?>


                            <?if(!empty($arItem["PROPERTIES"]["MAP_DESC"]["~VALUE"])):?>
                                <div class="<?=$class4?> item">
                                    <div class="row no-gutters align-items-center h-100">
                                        <div class="col">
                                            <?=$arItem["PROPERTIES"]["MAP_DESC"]["~VALUE"]["TEXT"]?>
                                        </div>
                                    </div>
                                </div>
                            <?endif;?>

                        </div>

              
                    </div>
                </div>
           
                   
            </div>

        <?endif;?>

    <?endif;?>

    
    <?if(strlen($arItem["PROPERTIES"]["MAP"]["VALUE"]) > 0):?>

        <div class="<?if( $arItem["PROPERTIES"]["MAP_SIZE"]["VALUE_XML_ID"] !="in-container" && !$show_menu):?>container<?endif;?> d-md-none">

            <div class="main-button-wrap center">
        
                <a class="map-show button-def secondary "><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["TYPE_MAP_SHOW_BTN_NAME"]?></a>    
                  
            </div>


        </div>

        <div class="map-height">

            <?if (preg_match("<script>", $arItem["PROPERTIES"]["MAP"]["VALUE"])):?>
               
               <?$map = str_replace("<script ", "<script data-skip-moving='true' ", $arItem["PROPERTIES"]["MAP"]["~VALUE"]);?>
               <?$map = str_replace("scroll=true", "scroll=false", $map);?>

               <div class="iframe-map-area" data-src="<?=htmlspecialcharsbx($map)?>"></div>
               
           <?elseif(preg_match("<iframe>", $arItem["PROPERTIES"]["MAP"]["VALUE"])):?>

                <div class="iframe-map-area" data-src="<?=htmlspecialcharsbx($arItem["PROPERTIES"]["MAP"]["~VALUE"])?>"></div>
                <div class="overlay" onclick="style.pointerEvents='none'"></div>
                                      
           <?endif;?>

       </div>

    <?endif;?>     
    
</div>



<?if( $arItem["PROPERTIES"]["MAP_SIZE"]["VALUE_XML_ID"] != "in-container"):?>

    <div class="<?if(!$show_menu):?>container<?endif;?>">
        <!-- open from map container -->

<?endif;?>