<div class="bl-txt <?/*=($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE"]["VALUE"])? "two-cols" : "one-col";*/?>">

    <div class="row">

    	<?$pictureSmXs = ($arItem["PROPERTIES"]["TEXT_BLOCK_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "hidden-md hidden-sm hidden-xs")?"col-md-12":"col-md-8";?>

        <div class="wr-txt padding-change <?if($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE"]["VALUE"]):?>col-lg-7 <?=$pictureSmXs?> col-12<?else:?>col-12<?endif;?> order-2">

            <?if($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE"]["VALUE"] > 0):?>
                <div class="<?=($arItem["PIC_POS_HOR"] == "order-md-1")? "in-padding-left": "in-padding-right";?>">
            <?endif;?>

                <?if($arItem["TITLE_CHANGE"]):?>
                    <?CreateHead($arItem, $show_menu, true, $main_key)?>
                <?endif;?>
                
                
                <?if($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE"]["VALUE"] <= 0 && $arItem["GALLERY_COUNT"]):?>

                    <div class="wr-tabs">

                        <div class="items">

                            <?$i = 0;?>

                            <?foreach($arItem["GALLERY"] as $arPhoto):?>

                                <div class="item">

                                    <?if($arItem["GALLERY_COUNT"] > 1):?>

                                        <div class="title-mobile d-lg-none <?=($i == 0)?"active":"";?>">
                                            <?=(strlen($arPhoto["DESC"]) > 0)?$arPhoto["DESC"] : $i+1;?>
                                            <div class="main-color"></div>
                                        </div>

                                    <?endif;?>
                                    

                                    <img class="mx-auto lazyload <?if($i == 0):?>active<?endif;?>" data-src="<?=$arPhoto["SRC"]?>" alt="<?=(strlen($arPhoto["DESC"]))? $arPhoto["DESC"]:"";?>"/>
                                    
                                </div>

                                <?$i++;?>

                            <?endforeach;?>

                        </div>


                        <?if($arItem["GALLERY_COUNT"] > 1 || strlen($arItem["GALLERY"][0]["DESC"])>0):?>

                            <ul class="tabs d-none d-lg-table">

                                <?$i = 0;?>
                                <?foreach($arItem["GALLERY"] as $arPhoto):?>
                                    <li class="main-color-active <?if($i == 0):?>active<?endif;?>">
                                        <?=strlen($arPhoto["DESC"])? $arPhoto["DESC"]:$i+1;?>
                                    </li>

                                    <?$i++;?>
                                <?endforeach;?>
                            </ul>
                        <?endif;?>
                    </div>                
                <?endif;?>
                
                <?if( isset($arItem["TEXT"]) ):?>

                    <div class="
                        text-content
                        <?=$arItem["PROPERTIES"]["TEXT_BLOCK_COLOR"]["VALUE_XML_ID"]?>
                        <?=$arItem["PROPERTIES"]["TEXT_BLOCK_TEXT_ALIGN"]["VALUE_XML_ID"]?>
                        <?=$arItem["PROPERTIES"]["TEXT_BLOCK_TEXT_ALIGN_MOB"]["VALUE_XML_ID"]?>">
                        <?=$arItem["TEXT"]?>
                    </div>

                <?endif;?>
                
                <?if($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE"]["VALUE"] > 0 && $arItem["GALLERY_COUNT"]):?>

                    <div class="gallery <?=($arItem["PROPERTIES"]["TEXT_BLOCK_BORDER"]["VALUE"])? "border-img-on":"";?>">
                        <div class="row">
                        
                            <?foreach($arItem["GALLERY"] as $arPhoto):?>
                                
                                <div class="col-3">
                                
                                    <div class="img-wrap">
                                        <a data-gallery="a<?=$arItem["ID"]?>" class="cursor-loop" title="<?=(strlen($arPhoto["DESC"]))? CPhoenix::prepareText($arPhoto["DESC"]):"";?>" href="<?=$arPhoto["SRC_LG"]?>">
                                            <img class="img-fluid lazyload" data-src="<?=$arPhoto["SRC_XS"]?>" alt="<?=(strlen($arPhoto["DESC"]))? $arPhoto["DESC"]:"";?>">
                                        </a>
                                    </div>
                                </div>
                            <?endforeach;?>

                        </div>
                    </div>
                
                <?endif;?>

                <?if($arItem["BUTTON_CHANGE"]):?>
                    <?=CreateButton($arItem, $show_menu, false)?>
                <?endif;?>


            <?if($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE"]["VALUE"] > 0):?>
                </div>
            <?endif;?>

        </div>
        
        
        <?if($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE"]["VALUE"]):?>

            <div class="wr-img col-lg-5 col-md-4 col-12 <?=$arItem["PIC_POS_VERT"]?> <?=$arItem["PIC_POS_HOR"]?> <?=$arItem["PROPERTIES"]["TEXT_BLOCK_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"]?>">

                <img class="mx-auto d-block <?=$arItem["ANIMATE"]?> lazyload" data-src="<?=$arItem["TEXT_BLOCK_PICTURE"]["SRC"]?>" <?=$arItem["ANIMATE_SET"]?> alt="<?=$arItem["TEXT_BLOCK_PICTURE"]["DESC"]?>"/>
            
            </div>

        <?endif;?>
    </div>
</div>