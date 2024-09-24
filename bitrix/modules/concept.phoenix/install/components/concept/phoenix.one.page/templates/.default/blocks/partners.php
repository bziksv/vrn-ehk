<?if(!empty($arItem["PROPERTIES"]["PARTNERS"]["VALUE"])):?>

    <?
        if( $arItem["PROPERTIES"]["PARTNERS_VIEW"]["VALUE_XML_ID"] == "" )
            $arItem["PROPERTIES"]["PARTNERS_VIEW"]["VALUE_XML_ID"] = "partners-flat";


        $countItems = count($arItem["PROPERTIES"]["PARTNERS"]["VALUE"]);

        $class = "";
        $offsetClass = "";

        if(!$show_menu)
        {
            if($countItems <= 6)
            {

                if($countItems <= 4)
                    $class="col-lg-3 col-md-4 col-6 big";
                
                else
                    $class="col-lg-2 col-md-4 col-6";
                

            }

            else
                $class="col-lg-2 col-md-4 col-6";                       
            
        }
        else
            $class="col-xl-2 col-lg-3 col-md-4 col-6";
        
        

    ?>

    <div class="partners <?=$arItem["PROPERTIES"]["PARTNERS_VIEW"]["VALUE_XML_ID"]?> universal-parent-slider <?=$arItem["PROPERTIES"]["PARTNERS_SUBSTRATE"]["VALUE_XML_ID"]?>">

        <?if( $arItem["PROPERTIES"]["PARTNERS_VIEW"]["VALUE_XML_ID"] == "partners-flat" ):?>

            <div class="row justify-content-center">

                <?foreach($arItem["PROPERTIES"]["PARTNERS"]["VALUE"] as $k => $arPartner):?>

                    <?/*if($countItems <= 4):*/?>

                        <?$img = CFile::ResizeImageGet($arPartner, array('width'=>400, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>

                    <?/*else:?>
                        <?$img = CFile::ResizeImageGet($arPartner, array('width'=>400, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                    <?endif;*/?>

                    <div class="<?=$class?> <?=$arItem["PROPERTIES"]["PARTNERS_GRAYSCALE"]["VALUE_XML_ID"]?> ">
                        <div class="item">

                            <?if(  !empty( $arItem["PROPERTIES"]["PARTNERS_LINKS"]["VALUE"] ) && strlen($arItem["PROPERTIES"]["PARTNERS_LINKS"]["VALUE"][$k])>0 ):?>
                                <?
                                    $show_link = true;

                                    if($arItem["PROPERTIES"]["PARTNERS_LINKS"]["~VALUE"][$k] == '"-"' || $arItem["PROPERTIES"]["PARTNERS_LINKS"]["VALUE"][$k] == '-')
                                        $show_link = false;
                                ?>

                                <?if($show_link):?>

                                    <a target = "_blank" href="<?=$arItem["PROPERTIES"]["PARTNERS_LINKS"]["VALUE"][$k]?>" class="general-link-wrap"></a>

                                <?endif;?>

                            <?endif;?>

                            <div class="wr-img row no-gutters align-items-center">
                                <div class="col-12">

                                    <img class="d-block mx-auto lazyload" data-src="<?=$img["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["PARTNERS"]["DESCRIPTION"][$k]))? strip_tags($arItem["PROPERTIES"]["PARTNERS"]["DESCRIPTION"][$k]):"";?>">
                                    
                                </div>
                            </div>


                            <?if(strlen($arItem["PROPERTIES"]["PARTNERS"]["DESCRIPTION"][$k]) > 0):?>

                                <div class="partners-part-bot hidden-xs hidden-sm">
                                    <?=$arItem["PROPERTIES"]["PARTNERS"]["~DESCRIPTION"][$k]?>
                                </div>

                            <?endif;?>

                        </div>

                    </div>

                <?endforeach;?>

            </div>

            <div class="col-12">

                <?if( $arItem["BUTTON_CHANGE"] )
                    echo CreateButton( $arItem, $show_menu, false );?>

            </div>

        <?elseif( $arItem["PROPERTIES"]["PARTNERS_VIEW"]["VALUE_XML_ID"] == "partners-slider" ):?>

            <?

                $class_arrows = "";

                if($countItems>6)
                    $class_arrows .= " d-none d-lg-block";
                

                if($countItems>4)
                    $class_arrows .= " d-none d-sm-block d-md-none";
                

                if($countItems>1)
                    $class_arrows .= " d-block d-sm-none";


                $head_empty = false;

                if( strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"])<=0 && strlen($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"])<=0 && strlen($arItem["PROPERTIES"]["BUTTON_NAME"]["VALUE"])<=0 && strlen($arItem["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"])<=0 && strlen($class_arrows)<=0)
                    $head_empty = true;

            ?>

            <?if( !$head_empty):?>
      
                <div class="head-view-second row align-items-center">

                    <?if( $arItem["TITLE_CHANGE"] && (strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) || strlen($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"])) ):?>
                        <div class="col left">
                            <?= CreateHead( $arItem, $show_menu, true, $main_key );?>
                        </div>
                    <?endif;?>

                    <?if( (strlen($arItem["PROPERTIES"]["BUTTON_NAME"]["VALUE"]) || strlen($arItem["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"]) || strlen($class_arrows)) ):?>

                        <div class="col-auto right d-none d-md-block">

                            <div class="row align-items-center justify-content-end">

                                <?if(strlen($class_arrows)):?>
                                    <div class="col-auto <?=$class_arrows?>">
                                        <div class="universal-arrows-mini">
                                            <div class="arrow-next"></div>
                                            <div class="arrow-prev"></div>
                                        </div>
                                    </div>
                                <?endif;?>

                                <?if(strlen($arItem["PROPERTIES"]["BUTTON_NAME"]["VALUE"]) || strlen($arItem["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"])):?>

                                    <div class="col-auto">
                                        <?if($arItem["BUTTON_CHANGE"])
                                            echo CreateButton( $arItem, $show_menu, false, "view-second" );?>
                                    </div>

                                <?endif;?>
                            </div>
                        </div>

                    <?endif;?>
                  
                </div>

            <?endif;?>

            <div class="ex-row img-for-lazyload-parent">

                <img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arItem["ID"]?>">

                <div class="partners-slider-list universal-head-arrows universal-mobile-arrows <?if($head_empty):?>head-empty<?endif;?> universal-slider parent-slider-item-js">

                    <?foreach($arItem["PROPERTIES"]["PARTNERS"]["VALUE"] as $k => $arPartner):?>

                        <div class="col-12 <?=($k!=0)?'noactive-slide-lazyload':'';?>">

                        <?if($countItems <= 4):?>

                            <?$img = CFile::ResizeImageGet($arPartner, array('width'=>300, 'height'=>90), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>

                        <?else:?>
                            <?$img = CFile::ResizeImageGet($arPartner, array('width'=>300, 'height'=>70), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                        <?endif;?>

                            <div class="item <?=$arItem["PROPERTIES"]["PARTNERS_GRAYSCALE"]["VALUE_XML_ID"]?> ">



                                <?if(  !empty( $arItem["PROPERTIES"]["PARTNERS_LINKS"]["VALUE"] ) && strlen($arItem["PROPERTIES"]["PARTNERS_LINKS"]["VALUE"][$k])>0 ):?>


                                    <?

                                        $show_link = true;

                                        if($arItem["PROPERTIES"]["PARTNERS_LINKS"]["~VALUE"][$k] == '"-"' || $arItem["PROPERTIES"]["PARTNERS_LINKS"]["VALUE"][$k] == '-')
                                            $show_link = false;

                                        ?>

                                        <?if($show_link):?>

                                            <a target = "_blank" href="<?=$arItem["PROPERTIES"]["PARTNERS_LINKS"]["VALUE"][$k]?>" class="general-link-wrap"></a>

                                        <?endif;?>

                                <?endif;?>

                                <div class="wr-img row no-gutters align-items-center">
                                    <div class="col-12">
                                        <img class="d-block mx-auto lazyload" data-src="<?=$img["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["PARTNERS"]["DESCRIPTION"][$k]))? strip_tags($arItem["PROPERTIES"]["PARTNERS"]["DESCRIPTION"][$k]):"";?>">
                                    </div>
                                </div>


                                <?if(strlen($arItem["PROPERTIES"]["PARTNERS"]["DESCRIPTION"][$k]) > 0):?>

                                    <div class="partners-part-bot">
                                        <?=$arItem["PROPERTIES"]["PARTNERS"]["~DESCRIPTION"][$k]?>
                                    </div>

                                <?endif;?>

                            </div>
                            
                        </div>

                      

                    <?endforeach;?>


                </div>

                <img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$arItem["ID"]?>">

            </div>

            <div class="visible-sm visible-xs">

                <?if( $arItem["BUTTON_CHANGE"] )
                    echo CreateButton( $arItem, $show_menu, false );?>

            </div>

        <?endif;?>

    </div>

<?endif;?>