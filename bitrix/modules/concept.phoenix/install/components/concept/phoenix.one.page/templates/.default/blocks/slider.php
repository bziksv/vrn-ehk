<?if(  is_array( $arItem["ELEMENTS"] ) && !empty( $arItem["ELEMENTS"] )  ):?>

            
    <style>

        <?foreach( $arItem["ELEMENTS"] as $k => $arElement ):?>
            
            <?CPhoenix::setHTMLFontHead(
                ".block-slider-item-".$arElement["ID"]." div.head h1, .block-slider-item-".$arElement["ID"]."div.head h2", 
                $arElement["PROPERTIES"]["TITLE_SIZE"]["VALUE"], 
                $arElement["PROPERTIES"]["TITLE_SIZE"]["DESCRIPTION"], 
                $arElement["PROPERTIES"]["TITLE_SIZE_MOB"]["VALUE"], 
                $arElement["PROPERTIES"]["TITLE_SIZE_MOB"]["DESCRIPTION"])
            ?>

            <?CPhoenix::setHTMLFontHead(
                ".block-slider-item-".$arElement["ID"]." div.head .descrip", 
                $arElement["PROPERTIES"]["SUBTITLE_SIZE"]["VALUE"], 
                $arElement["PROPERTIES"]["SUBTITLE_SIZE"]["DESCRIPTION"], 
                $arElement["PROPERTIES"]["SUBTITLE_SIZE_MOB"]["VALUE"], 
                $arElement["PROPERTIES"]["SUBTITLE_SIZE_MOB"]["DESCRIPTION"])
            ?>

            <?if($k == 0):?>

                @media (min-width: 992px){
                    #block<?=$arItem["ID"]?>{
                        <?if( strlen($arElement["PROPERTIES"]["MARGIN_TOP"]["VALUE"])>0 ):?>
                            margin-top: <?=$arElement["PROPERTIES"]["MARGIN_TOP"]["VALUE"]?>px !important;
                        <?endif;?>

                        <?if( strlen($arElement["PROPERTIES"]["MARGIN_BOTTOM"]["VALUE"])>0 ):?>
                            margin-bottom: <?=$arElement["PROPERTIES"]["MARGIN_BOTTOM"]["VALUE"]?>px !important;
                        <?endif;?>

                        <?if( strlen($arElement["PROPERTIES"]["PADDING_TOP"]["VALUE"])>0 ):?>
                            padding-top: <?=$arElement["PROPERTIES"]["PADDING_TOP"]["VALUE"]?>px !important;
                        <?endif;?>

                        <?if( strlen($arElement["PROPERTIES"]["PADDING_BOTTOM"]["VALUE"])>0 ):?>
                            padding-bottom: <?=$arElement["PROPERTIES"]["PADDING_BOTTOM"]["VALUE"]?>px !important;
                        <?endif;?>
                    }
                }

                @media (max-width: 991px){

                    #block<?=$arItem["ID"]?>{
                        <?if( strlen($arElement["PROPERTIES"]["MARGIN_TOP_MOB"]["VALUE"])>0 ):?>
                            margin-top: <?=$arElement["PROPERTIES"]["MARGIN_TOP_MOB"]["VALUE"]?>px !important;
                        <?endif;?>

                        <?if( strlen($arElement["PROPERTIES"]["MARGIN_BOTTOM_MOB"]["VALUE"])>0 ):?>
                            margin-bottom: <?=$arElement["PROPERTIES"]["MARGIN_BOTTOM_MOB"]["VALUE"]?>px !important;
                        <?endif;?>

                        <?if( strlen($arElement["PROPERTIES"]["PADDING_TOP_MOB"]["VALUE"])>0 ):?>
                            padding-top: <?=$arElement["PROPERTIES"]["PADDING_TOP_MOB"]["VALUE"]?>px !important;
                        <?endif;?>

                        <?if( strlen($arElement["PROPERTIES"]["PADDING_BOTTOM_MOB"]["VALUE"])>0 ):?>
                            padding-bottom: <?=$arElement["PROPERTIES"]["PADDING_BOTTOM_MOB"]["VALUE"]?>px !important;
                        <?endif;?>
                    }
                } 


            <?endif;?>

        <?endforeach;?>

    </style>

    <div class="block-slider">
        <div class="img-for-lazyload-parent">

            <img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arItem["ID"]?>">

            <div class="block-slider-list universal-arrows-style parent-slider-item-js" <?if( $arItem["PROPERTIES"]["SLIDER_SCROLL_SPEED"]["VALUE"] > 0 ):?> data-scroll-speed = "<?= $arItem["PROPERTIES"]["SLIDER_SCROLL_SPEED"]["VALUE"]?>"<?endif;?> data-count="<?=count($arItem["ELEMENTS"])?>">
            	
                <?foreach( $arItem["ELEMENTS"] as $iKey => $arElement ):?>

                    <?
                        

                        if($arElement["PROPERTIES"]["SLIDER_PICTURE_POS_HOR"]["VALUE_XML_ID"] == '' || $arElement["PROPERTIES"]["SLIDER_PICTURE_POS_HOR"]["VALUE_XML_ID"] == 'left') 
                            $arElement["PROPERTIES"]["SLIDER_PICTURE_POS_HOR"]["VALUE_XML_ID"] = 'order-first';

                        if($arElement["PROPERTIES"]["SLIDER_PICTURE_POS_HOR"]["VALUE_XML_ID"] == 'right') 
                            $arElement["PROPERTIES"]["SLIDER_PICTURE_POS_HOR"]["VALUE_XML_ID"] = 'order-last';

                        

                        if( $arElement["PROPERTIES"]["SLIDER_TEXT_CLR"]["VALUE_XML_ID"] == "" )
                            $arElement["PROPERTIES"]["SLIDER_TEXT_CLR"]["VALUE_XML_ID"] = "dark";

                        if( $arElement["PROPERTIES"]["SLIDER_PICTURE_POS_VERT"]["VALUE_XML_ID"] == "" )
                            $arElement["PROPERTIES"]["SLIDER_PICTURE_POS_VERT"]["VALUE_XML_ID"] = "align-self-center";

                        if( $arElement["PROPERTIES"]["SLIDER_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "" )
                            $arElement["PROPERTIES"]["SLIDER_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] = "order-first-mob";


                        
                        $arValidate = Array(
                            $arElement["PROPERTIES"]["HEADER"]["VALUE"],
                            $arElement["PROPERTIES"]["SUBHEADER"]["VALUE"],
                            $arElement["PROPERTIES"]["BUTTON_NAME"]["VALUE"],
                            $arElement["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"]
                        );

                        $resultPerm = CPhoenix::getPermissionPhoenix( $arValidate, "||" );
                        $arValidate = NULL;
                        $resultPermText = !empty( $arElement["PROPERTIES"]["SLIDER_TEXT"]["VALUE"] );

                        $cols_text = "col";
                        $cols_img = "col";

                        unset($img);

                        if( $arElement["PROPERTIES"]["SLIDER_PICTURE"]["VALUE"] > 0 )
                            $img = CFile::ResizeImageGet($arElement["PROPERTIES"]["SLIDER_PICTURE"]["VALUE"], array('width'=>1200, 'height'=>1600), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                        if(  isset( $img ) && ($resultPerm || $resultPermText)  )
                        {
                            $cols_img = "col-lg-6 col-md-4 col-12";
                            $cols_text = "col-lg-6 col-md-8 col-12";
                        }
                    ?>

                    

                    <div class="block-slider-item block-slider-item-<?=$arElement["ID"]?> <?if($arElement["PROPERTIES"]["HIDE_BLOCK_LG"]["VALUE"] == "Y"):?>slide-hidden-lg<?endif;?> <?if($arElement["PROPERTIES"]["HIDE_BLOCK"]["VALUE"] == "Y"):?>slide-hidden-xs<?endif;?> <?if($iKey!=0) echo 'noactive-slide-lazyload';?>">

                        

                    	<div class="block-slider-table-wrap padding-change-block">

                            <div class="block-slider-table row <?if(!isset( $img )):?>no-image<?endif;?>">

                    

                                <?if( isset( $img )  ):?>

                                    <div class="lvl1 part-picture <?=$cols_img?> <?=$arElement["PROPERTIES"]["SLIDER_PICTURE_POS_HOR"]["VALUE_XML_ID"]?> <?=$arElement["PROPERTIES"]["SLIDER_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"]?> <?=$arElement["PROPERTIES"]["SLIDER_PICTURE_POS_VERT"]["VALUE_XML_ID"]?> ">

                                        
                                        <div class="row wrapper-picture">

                                            <div class="col-12">
                                                <img class="pic mx-auto d-block lazyload block-slider-item-js" data-src="<?=$img["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["SLIDER_PICTURE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["SLIDER_PICTURE"]["DESCRIPTION"]:"";?>" />
                                            </div>

                                            
                                        </div>
                                        
                                    </div>

                                <?endif;?>



                                <?if( $resultPerm || $resultPermText ):?>

                                    <div class="lvl1 <?=$cols_text?> part-text text-parent-clr-<?=$arElement["PROPERTIES"]["SLIDER_TEXT_CLR"]["VALUE_XML_ID"]?> align-self-center padding-change-part">
                                        
                                        <?
                                            if( $arItem["TITLE_CHANGE"] )
                                                echo CreateHead( $arElement, $show_menu, true, $main_key );
                                        ?>

                                        <?if(isset($arElement["PROPERTIES"]["SLIDER_TEXT"]["VALUE"]["TEXT"])):?>

                                            <div class="text-style text-content text-content-clr-main">

                                                <?= $arElement["PROPERTIES"]["SLIDER_TEXT"]["~VALUE"]["TEXT"];?>

                                            </div>

                                        <?endif;?>

                                        <?
                                            if( $arItem["BUTTON_CHANGE"] )
                                                echo CreateButton( $arElement, $show_menu, false );
                                        ?>

                                    </div>

                                <?endif;?>
                                

                            </div>

                        </div>

                        <?CPhoenix::admin_setting($arElement, false)?>


                    </div>

                <?endforeach;?>

            </div>

            <img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arItem["ID"]?>">

        </div>

    </div>



<?endif;?>