
</div> <!-- close from news container  -->


<?if($arItem["PROPERTIES"]["NEWS_VIEW"]["VALUE_XML_ID"] == ""):?>
    <?$arItem["PROPERTIES"]["NEWS_VIEW"]["VALUE_XML_ID"] == "chrono";?>
<?endif;?>


<?if(strlen($arItem["PROPERTIES"]["NEWS_PICTURE"]["VALUE"]) > 0):?>
    <div class="<?if(!$show_menu):?>container<?endif;?>">
        
        <div class="news-image">
            <?$img_big = CFile::ResizeImageGet($arItem["PROPERTIES"]["NEWS_PICTURE"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
            <img class="center-block lazyload" data-src="<?=$img_big["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["NEWS_PICTURE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["NEWS_PICTURE"]["DESCRIPTION"]:"";?>">
            
        </div>

    </div>

 
<?endif;?>

<?if(is_array($arItem["ELEMENTS"]) && !empty($arItem["ELEMENTS"])):?>

    <div class="news <?if($arItem["SHOW_SUBNAME"] == 0):?> no-date<?endif;?> <?=$arItem["PROPERTIES"]["NEWS_VIEW"]["VALUE_XML_ID"]?> <?/*if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>parent-animate<?endif;*/?> universal-parent-slider">

   
        <?if($arItem["PROPERTIES"]["NEWS_VIEW"]["VALUE_XML_ID"] == "chrono" || $arItem["PROPERTIES"]["NEWS_VIEW"]["VALUE_XML_ID"] == "flat-2"):?>
            <?

                $head_empty = false;

                if( strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"])<=0 && strlen($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"])<=0 && strlen($arItem["PROPERTIES"]["BUTTON_NAME"]["VALUE"])<=0 && strlen($arItem["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"])<=0 && strlen($countItems)<=0 )
                    $head_empty = true;

            ?>

            <?//if( !$head_empty):?>
          
                <div class="<?if(!$show_menu):?>container<?endif;?>">

                    <div class="head-view-second row align-items-center">


                        <?if( $arItem["TITLE_CHANGE"] && (strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) || strlen($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"])) ):?>
                            <div class="col left">
                                <?= CreateHead( $arItem, $show_menu, true, $main_key );?>
                            </div>
                        <?endif;?>

                        <?//if( strlen($arItem["PROPERTIES"]["BUTTON_NAME"]["VALUE"]) || strlen($arItem["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"]) ):?>

                            <div class="<?=($head_empty)?"col":"col-auto"?> right d-none d-md-block">

                                <div class="row align-items-center justify-content-end">

                                    <?//if(strlen($class_arrows)):?>
                                        <div class="col-auto wr-arrows-slick d-none">
                                            <div class="universal-arrows-mini">
                                                <div class="arrow-next"></div>
                                                <div class="arrow-prev"></div>
                                            </div>
                                        </div>
                                    <?//endif;?>

                                    <?if(strlen($arItem["PROPERTIES"]["BUTTON_NAME"]["VALUE"]) || strlen($arItem["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"])):?>

                                        <div class="col-auto">
                                            <?if($arItem["BUTTON_CHANGE"])
                                                echo CreateButton( $arItem, $show_menu, false, "view-second" );?>
                                        </div>

                                    <?endif;?>
                                </div>
                            </div>

                        <?//endif;?>
                          
                    </div>
                </div>

            <?//endif;?>

            <?if($arItem["PROPERTIES"]["NEWS_VIEW"]["VALUE_XML_ID"] == "chrono"):?>

                <div class="bg_line_cont">
                    <div class="bg_line"></div>
                </div>

            <?endif;?>


        <?endif;?>

    
      

        <?if($arItem["PROPERTIES"]["NEWS_VIEW"]["VALUE_XML_ID"] == "chrono"):?>

        	<div class="<?if(!$show_menu):?>container<?endif;?>">
                <div class="ex-row">

                    <?
                        $APPLICATION->IncludeComponent(
                            "concept:phoenix.news-blogs-actions-list",
                            "chrono",
                            Array(
                                "BLOCK_ID" =>  "block".$arItem["ID"],
                                "COMPOSITE_FRAME_MODE" => "N",
                                "DISPLAY_DATE" => "N",
                                "DISPLAY_NAME" => "N",
                                "DISPLAY_PICTURE" => "N",
                                "DISPLAY_PREVIEW_TEXT" => "N",
                                "ELEMENTS_ID" => $arItem["ELEMENTS"],
                                "SORT_BY1" => "ACTIVE_FROM",
                                "SORT_ORDER1" => $arItem["ELEMENTS_SORT"],
                                "SHOW_MENU" => $show_menu,
                                "HEAD_EMPTY" => $head_empty,
                                "HIDE_DATE" => $arItem["PROPERTIES"]["NEWS_HIDE_DATE"]["VALUE_XML_ID"],
                                "ELEMENTS_COUNT" => (isset($arItem["ELEMENTS_CNT"]))?$arItem["ELEMENTS_CNT"]:""
                            )
                        );

                    ?>

    

    	            <div class="d-block d-sm-none col-12">

    	                <?if( $arItem["BUTTON_CHANGE"] )
    	                    echo CreateButton( $arItem, $show_menu, false );?>

    	            </div>
                   

                </div>

		    </div>
            

        <?endif;?>

    
        <?
            if($arItem["PROPERTIES"]["NEWS_VIEW"]["VALUE_XML_ID"] == "flat")
            {?>

                <div class="<?if(!$show_menu):?>container<?endif;?>">

                    <div class="<?if($show_menu):?>ex-row<?endif;?>">

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
                                "ELEMENTS_ID" => $arItem["ELEMENTS"],
                                "SORT_BY1" => "ACTIVE_FROM",
                                "SORT_ORDER1" => $arItem["ELEMENTS_SORT"],
                                "ANIMATE" => $arItem["PROPERTIES"]["ANIMATE"]["VALUE"],
                                "COL_LG" => ($show_menu) ? "4": "3",
                                "HIDE_DATE" => $arItem["PROPERTIES"]["NEWS_HIDE_DATE"]["VALUE_XML_ID"],
                                "ELEMENTS_COUNT" => (isset($arItem["ELEMENTS_CNT"]))?$arItem["ELEMENTS_CNT"]:""
                            )
                        );?>
                    </div>
                </div>
            <?}
        ?>


        <?if ($arItem["PROPERTIES"]["NEWS_VIEW"]["VALUE_XML_ID"] == "flat-2"):?>

            <div class="<?if(!$show_menu):?>container<?endif;?>">
                <div class="<?if(!$show_menu):?>ex-row<?endif;?>">

                    <?
                        $APPLICATION->IncludeComponent(
                            "concept:phoenix.news-blogs-actions-list",
                            "slider",
                            Array(
                                "BLOCK_ID" => "block".$arItem["ID"],
                                "COMPOSITE_FRAME_MODE" => "N",
                                "DISPLAY_DATE" => "N",
                                "DISPLAY_NAME" => "N",
                                "DISPLAY_PICTURE" => "N",
                                "DISPLAY_PREVIEW_TEXT" => "N",
                                "ELEMENTS_ID" => $arItem["ELEMENTS"],
                                "SORT_BY1" => "ACTIVE_FROM",
                                "SORT_ORDER1" => $arItem["ELEMENTS_SORT"],
                                "SHOW_MENU" => $show_menu,
                                "HEAD_EMPTY" => $head_empty,
                                "ELEMENTS_COUNT" => (isset($arItem["ELEMENTS_CNT"]))?$arItem["ELEMENTS_CNT"]:""
                            )
                        );
                    ?>

                    <div class="d-block d-sm-none col-12">

                        <?if( $arItem["BUTTON_CHANGE"] )
                            echo CreateButton( $arItem, $show_menu, false );?>

                    </div>
                </div>
            </div>

        <?endif;?>

    </div>
    
<?endif;?>


<div class="<?if(!$show_menu):?>container<?endif;?>"><!-- open from news container  -->