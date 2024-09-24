<?if(!empty($arItem["PROPERTIES"]["SWITCHER_TABNAME"]["~VALUE"])):?>


    </div> <!-- close from switcher container  -->


    <div class="switcher">

        <?if($arItem["PROPERTIES"]["SWITCHER_VIEW"]["VALUE_XML_ID"] == "tabs-left" || $arItem["PROPERTIES"]["SWITCHER_VIEW"]["VALUE_XML_ID"] == ""):?>

            <div class="<?if(!$show_menu):?>container<?endif;?>">

                <div class="row">
                    
                    <div class="col-md-4 hidden-md hidden-sm hidden-xs">
                        <ul class="switcher-tab left">

                        <?foreach($arItem["PROPERTIES"]["SWITCHER_TABNAME"]["~VALUE"] as $k => $arTabs):?>  
                            

                            <li<?if($k == 0):?> class="active"<?endif;?>>
                                <span><?=$arTabs;?></span>
                            </li>

                            <?endforeach;?>
                            
                        </ul>
                    </div>

                    <div class="col-lg-8 col-12">
                        <div class="switcher-content-wrap left">

                            <?foreach($arItem["PROPERTIES"]["SWITCHER_TABNAME"]["~VALUE"] as $k => $arTabs):?>

                                <div class="switcher-wrap <?if($k == 0):?>active<?endif;?>">

                                    <div class="switcher-title visible-md visible-sm visible-xs <?if($k == 0):?>active<?endif;?>"><?=$arTabs?><div class="main-color"></div></div>   

                                    <div class="switcher-content text-content <?if($k == 0):?>active<?endif;?>">
                                        
                                        <?=str_replace(array("#MAP_START#","#VIDEO_START#"), array('<img class="lazyload img-for-lazyload map-start" data-src="'.SITE_TEMPLATE_PATH.'/images/one_px.png">','<img class="lazyload img-for-lazyload video-start" data-src="'.SITE_TEMPLATE_PATH.'/images/one_px.png">'), $arItem["PROPERTIES"]["SWITCHER_HTML"]["~VALUE"][$k]["TEXT"])?>
                                    </div>

                                </div>
                            <?endforeach;?>

                        </div>
                    </div>

                </div>

                

             
            </div>
            

        <?elseif($arItem["PROPERTIES"]["SWITCHER_VIEW"]["VALUE_XML_ID"] == "tabs-up"):?>

      

            <div class="<?if(!$show_menu):?>container<?endif;?> hidden-md hidden-sm hidden-xs">
                <div class="<?if(!$show_menu):?>ex-row<?endif;?>">

                    <ul class="switcher-tab top">

                        <?foreach($arItem["PROPERTIES"]["SWITCHER_TABNAME"]["~VALUE"] as $k => $arTabs):?>  
                    

                        <li class="<?=$class?> <?if($k == 0):?>active <?=$class2?><?endif;?>">
                            <span><?=$arTabs;?></span>
                        </li>

                        <?endforeach;?>
                        
                    </ul> 
                </div>

           
            </div> <!-- ^container -->
      

           
            <div class="<?if(!$show_menu):?> container<?endif;?>">
            

                <div class="switcher-content-wrap">
                    
                
                    <?foreach($arItem["PROPERTIES"]["SWITCHER_TABNAME"]["~VALUE"] as $k => $arTabs):?>

                        <div class="switcher-wrap <?if($k == 0):?>active<?endif;?>">

                            <div class="switcher-title visible-md visible-sm visible-xs visible-xxs <?if($k == 0):?>active<?endif;?>"><?=$arTabs?><div class="main-color"></div></div>   

                            <div class="switcher-content text-content <?if($k == 0):?>active<?endif;?>">
                                <?=str_replace(array("#MAP_START#","#VIDEO_START#"), array('<img class="lazyload img-for-lazyload map-start" data-src="'.SITE_TEMPLATE_PATH.'/images/one_px.png">','<img class="lazyload img-for-lazyload video-start" data-src="'.SITE_TEMPLATE_PATH.'/images/one_px.png">'), $arItem["PROPERTIES"]["SWITCHER_HTML"]["~VALUE"][$k]["TEXT"])?>
                            </div>

                        </div>
                    <?endforeach;?>

                </div>

      
            </div><!-- ^container -->
       

        <?endif;?>

    </div>

    <div class="<?if(!$show_menu):?>container<?endif;?>"><!-- open from switcher container  -->
        
 

<?endif;?>