<?if(isset($arItem["ELEMENTS"])):?>
<?if(!empty($arItem["ELEMENTS"])):?>

    <?=$arItem["STYLE"]?>

    <?if($show_menu):?>

        <?
            $class1="";
            $class2="col-md-8 col-12";
            $class3="col-md-4 col-12";

            if($arItem["PROPERTIES"]["FAQ_PICTURE"]["VALUE"] > 0)
            {

                $class1="col-md-2 col-12";
                $class2="col-md-6 col-12 with-photo";
            }
        ?>

        <div class="faq-block <?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>parent-animate<?endif;?>">

            <div class="faq-table row align-items-center">

                <?if($arItem["PROPERTIES"]["FAQ_PICTURE"]["VALUE"] > 0):?>
                    <div class="faq-cell <?=$class1?> left">

                        <table>
                            <tr>
                                <td>

                                    <?$img_big = CFile::ResizeImageGet($arItem["PROPERTIES"]["FAQ_PICTURE"]["VALUE"], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>

                                    <img class="mx-auto d-block lazyload" data-src="<?=$img_big["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["FAQ_PICTURE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["FAQ_PICTURE"]["DESCRIPTION"]:"";?>">

                                    <?/*<img alt="" class="img-responsive mx-auto d-block lazy-load" data-src="<?=SITE_TEMPLATE_PATH?>/images/faqq.png">*/?>
                                    
                                </td>
                            </tr>
                        </table>

                        
                    </div>   

                <?endif;?>      


            
                <div class="faq-cell <?=$class2?> center">
                    <div class="wrap-faqtext">

                        <?if(strlen($arItem["PROPERTIES"]["FAQ_NAME"]["VALUE"])):?>
                            <div class="name bold"><?=$arItem["PROPERTIES"]["FAQ_NAME"]["~VALUE"]?></div>
                        <?endif;?>

                        <?if(strlen($arItem["PROPERTIES"]["FAQ_PROF"]["VALUE"])):?>
                            <div class="desc italic"><?=$arItem["PROPERTIES"]["FAQ_PROF"]["~VALUE"]?></div>
                        <?endif;?>

                    </div>
                    
                </div>
             


                <div class="faq-cell <?=$class3?> right">
                    <?if($arItem["BUTTON_CHANGE"]):?>
                        <?CreateButton($arItem, false);?>
                    <?endif;?>
                    
                </div>
            </div>

            <div class="quest-part">
                <div class="faq">

                    <?foreach($arItem["ELEMENTS"] as $k=>$arFaq):?>
                        <div class="faq-element toogle-animate-parent <?if($k == 0 && $arItem["PROPERTIES"]["FAQ_HIDE_FIRST_ITEM"]["VALUE_XML_ID"] != "Y"):?> active <?endif;?> <?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>child-animate opacity-zero<?endif;?>">


                            
                            <?CPhoenix::admin_setting($arFaq, false)?>

                            <div class="question toogle-animate-click">
                                <span><?=$arFaq["~NAME"]?></span>
                            </div>

                            <div class="text text-content italic toogle-animate-content">
                                <?=$arFaq["~PREVIEW_TEXT"]?>
                            </div>
                        </div>
                    <?endforeach;?>

                </div>
            </div>
            
        </div>

    <?else:?>

        <div class="faq-block <?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>parent-animate<?endif;?>">
            <div class="row">

                <?//CPhoenix::admin_setting($arItem, false)?>

                <div class="col-lg-4 col-12">

                    
                    <div class="photo row">

                        <div class="col-lg-12 col-sm-6 col-4">

                            <?if($arItem["PROPERTIES"]["FAQ_PICTURE"]["VALUE"] > 0):?>


                                <?$img_big = CFile::ResizeImageGet($arItem["PROPERTIES"]["FAQ_PICTURE"]["VALUE"], array('width'=>450, 'height'=>450), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                                <img class="mx-auto d-block lazyload" data-src="<?=$img_big["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["FAQ_PICTURE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["FAQ_PICTURE"]["DESCRIPTION"]:"";?>">

                            <?else:?>
                                <img class="img-responsive mx-auto d-block lazyload" data-src="<?=SITE_TEMPLATE_PATH?>/images/faqq.png" alt="" >
                            <?endif;?>

                        </div>

                        <div class="col-lg-12 col-sm-6 col-8">

                            <div class="board-info">

                                <?if(strlen($arItem["PROPERTIES"]["FAQ_NAME"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["FAQ_PROF"]["VALUE"]) > 0):?>
                                    <div class="comm">
                                        <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FAQ_DESC"]?>
                                    </div>
                                <?endif;?>

                                <div class="name">
                                    <?if(strlen($arItem["PROPERTIES"]["FAQ_NAME"]["VALUE"])):?>
                                        <span class="main1"><?=$arItem["PROPERTIES"]["FAQ_NAME"]["~VALUE"]?></span><br>
                                    <?endif;?>
                                    <?if(strlen($arItem["PROPERTIES"]["FAQ_PROF"]["VALUE"])):?>
                                        <span class="prof italic"><?=$arItem["PROPERTIES"]["FAQ_PROF"]["~VALUE"]?></span>
                                    <?endif;?>
                                </div>

                                <div class="hidden-xs">
                                    
                                    <?if($arItem["BUTTON_CHANGE"]):?>
                                        <?CreateButton($arItem, false);?>
                                    <?endif;?>

                                </div>
                                
                            </div>
                            
                        </div>

                        <div class="col-12 visible-xs">
                                    
                            <?if($arItem["BUTTON_CHANGE"]):?>
                                <?CreateButton($arItem, false);?>
                            <?endif;?>

                        </div>
                        
                    </div>
                </div>

                <div class="col-lg-8 col-12">
                    <div class="l_wrap">
                        <div class="faq">

                            <?foreach($arItem["ELEMENTS"] as $k=>$arFaq):?>
                                <div class="faq-element toogle-animate-parent <?if($k == 0 && $arItem["PROPERTIES"]["FAQ_HIDE_FIRST_ITEM"]["VALUE_XML_ID"] != "Y"):?> active <?endif;?> <?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>child-animate opacity-zero<?endif;?>">

                                    
                                    <?CPhoenix::admin_setting($arFaq, false)?>
                                    

                                    <div class="question toogle-animate-click">
                                        <span><?=$arFaq["~NAME"]?></span>
                                    </div>


                                    <div class="text text-content italic toogle-animate-content">
                                        <?=$arFaq["~PREVIEW_TEXT"]?>
                                    </div>
                                </div>
                            <?endforeach;?>



                            <?/*<div class="btn_wrap visible-sm visible-xs">
                                <?if($arItem["BUTTON_CHANGE"]):?>
                                    <?CreateButton($arItem, false);?>
                                <?endif;?>
                            </div>*/?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    <?endif;?>



<?endif;?>
<?endif;?>