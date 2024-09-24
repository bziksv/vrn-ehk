<?if(!empty($arItem["PROPERTIES"]["NUMBERS_TEXTS"]["VALUE"])):?>
<div class="info-num <?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>parent-animate<?endif;?>">
    <div class="row <?if(!$show_menu):?>justify-content-center<?endif;?>">

        <?$class = "col-md-6 col-12";?>


        <?$count = count($arItem["PROPERTIES"]["NUMBERS_TEXTS"]["VALUE"]);?>

        <?if(!$show_menu):?>
        
            <?if($count <= 3):?>
                <?$class = "col-md-4 col-12";?>      
            <?else:?>
                <?$class = "col-lg-3 col-md-6 col-12 four-elements";?>
            <?endif;?>

        <?endif;?>
       
                
        <?foreach($arItem["PROPERTIES"]["NUMBERS_TEXTS"]["~VALUE"] as $k => $arValue):?>

        	<div class="<?=$class?>">
                
                <div class="info-num-element <?=$arItem["PROPERTIES"]["NUMBERS_TEXTS_COLOR"]["VALUE_XML_ID"]?> <?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>child-animate opacity-zero<?endif;?>">

                    <?if($arItem["STRING_NUM"] > 0):?>

                        <div title = '<?=CPhoenix::prepareText($arValue)?>' class="title main1" <?if(strlen($arItem["PROPERTIES"]["NUMBERS_FONT_SIZE"]["VALUE"]) > 0):?> style="font-size: <?=$arItem["PROPERTIES"]["NUMBERS_FONT_SIZE"]["VALUE"]?>px; line-height: <?=$arItem["PROPERTIES"]["NUMBERS_FONT_SIZE"]["VALUE"] + 3?>px; min-height: <?=$arItem["PROPERTIES"]["NUMBERS_FONT_SIZE"]["VALUE"] + 3?>px ;"<?endif;?>>
                            
                            <?=$arValue?>
                        </div>

                     <?endif;?>

                    <div class="text" title="<?=CPhoenix::prepareText($arItem["PROPERTIES"]["NUMBERS_TEXTS"]["DESCRIPTION"][$k])?>">
                        <?=$arItem["PROPERTIES"]["NUMBERS_TEXTS"]["~DESCRIPTION"][$k]?>
                    </div>

                </div>

            </div>

        <?endforeach;?>
                

    </div>
</div>
<?endif;?>