<?if($arItem["PROPERTIES"]["TARIFF_VIEW"]["VALUE_XML_ID"] == "" || $arItem["PROPERTIES"]["TARIFF_VIEW"]["VALUE_XML_ID"] == "flat"):?>
    
    <?
        if($arItem["PROPERTIES"]["TARIFF_PICTURE_POSITION"]["VALUE_XML_ID"] == "")
            $arItem["PROPERTIES"]["TARIFF_PICTURE_POSITION"]["VALUE_XML_ID"] == "left";

        $position_hor = $arItem["PROPERTIES"]["TARIFF_PICTURE_POSITION"]["VALUE_XML_ID"];

        if($position_hor == "left")
            $position_hor = "order-first";

        if($position_hor == "right")
            $position_hor = "order-last";


        if( $arItem["PROPERTIES"]["TARIFF_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "" )
            $arItem["PROPERTIES"]["TARIFF_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] = "order-first-mob";
    ?>

    <?if($show_menu):?>

        <?
            $issetDetail = (!empty($arItem["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"]));

            if($issetDetail)
                $attrsBtnDetail = "data-blocktitle=\"".$block_name."\" data-typename=\"tariff\" data-elementid=\"".$arItem["ID"]."\"";
        ?>


        <div class="tarif-2 <?=$arItem["PROPERTIES"]["TARIFF_TEXT_COLOR"]["VALUE_XML_ID"]?>">

            <div class="row ">

                <div class="<?if($arItem["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"] > 0):?>col-md-8 col-12<?else:?>col-12<?endif;?>">

                    <div class="left-wrap-inner">


                        <?if(strlen($arItem["PROPERTIES"]["TARIFF_NAME"]["VALUE"]) > 0):?>
                            <div class="title main1">
                                <?=$arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]?> <?if($arItem["PROPERTIES"]["TARIFF_HIT"]["VALUE"] == "Y"):?><span class="hit"></span><?endif;?>
                            </div>
                        <?endif;?>

                        <?if(strlen($arItem["PROPERTIES"]["TARIFF_PREVIEW_TEXT"]["VALUE"]) > 0):?>
                            <div class="subtitle italic">
                                <?=$arItem["PROPERTIES"]["TARIFF_PREVIEW_TEXT"]["~VALUE"]?>
                            </div>
                        <?endif;?>

                        <?if(strlen($arItem["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0):?>

                            <div class="price-wrap row">
                            
                                <?if(strlen($arItem["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0):?>
                                    <div class="old-price"><?=$arItem["PROPERTIES"]["TARIFF_OLD_PRICE"]["~VALUE"]?></div>
                                <?endif;?>

                                <?if(strlen($arItem["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0):?>
                                    <div class="price main1"><?=$arItem["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"]?></div>
                                <?endif;?>
                                
                            </div>
                                
                        <?endif;?>


                        <?if(empty($arItem["PROPERTIES"]["TARIFF_PRICES"]["VALUE"]) && ((strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0) || !empty($arItem["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_PRICES"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"]))):?>

                            <div class="buttons-wrap">
                            
                                <?if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0):?>

                                    <div class="button-child">

                                        <?
                                            $arClass = array();
                                            $arClass=array(
                                                "XML_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
                                                "FORM_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
                                                "MODAL_ID"=> $arItem["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
                                                "QUIZ_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"]
                                            );
                                            
                                            $arAttr=array();
                                            $arAttr=array(
                                                "XML_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
                                                "FORM_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
                                                "MODAL_ID"=> $arItem["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
                                                "LINK"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_LINK"]["VALUE"],
                                                "BLANK"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_BLANK"]["VALUE_XML_ID"],
                                                "HEADER"=> $block_name,
                                                "QUIZ_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"],
                                                "LAND_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_LAND"]["VALUE"]
                                            );
                                        ?>

                                        <a 
                                            <?
                                                if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_ONCLICK"]["VALUE"])>0) 
                                                {
                                                    $str_onclick = str_replace("'", "\"", $arItem["PROPERTIES"]["TARIFF_BUTTON_ONCLICK"]["VALUE"]);

                                                    echo "onclick='".$str_onclick."'";

                                                    $str_onclick = "";
                                                }

                                                $b_options = array(
                                                    "MAIN_COLOR" => "main-color",
                                                    "STYLE" => ""
                                                );

                                                if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"]))
                                                {

                                                    $b_options = array(
                                                        "MAIN_COLOR" => "btn-bgcolor-custom",
                                                        "STYLE" => "background-color: ".$arItem["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"].";"
                                                    );

                                                }
                                            ?>

                                            class="
                                                button-def
                                                <?=$btn_view?>
                                                <?=$b_options["MAIN_COLOR"]?>
                                                element-item
                                                <?=CPhoenix::buttonEditClass ($arClass)?> medium"

                                            <?if(strlen($b_options["STYLE"])):?>
                                                style = "<?=$b_options["STYLE"]?>"
                                            <?endif;?>

                                            <?=CPhoenix::buttonEditAttr($arAttr)?> 

                                            title='<?=CPhoenix::prepareText($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"])?>' data-element-item-id="<?=$arItem["ID"]?>" data-element-item-type = "TRF" 

                                            data-element-item-name = "
                                                <?if(strlen($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"])):?>
                                                    <?=str_replace( "\"", "'", strip_tags($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>
                                                <?else:?>
                                                    <?=str_replace( "\"", "'", strip_tags($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>&nbsp;(<?=str_replace( "\"", "'", strip_tags($arItem["~NAME"]))?>)
                                                <?endif;?>
                                            "
    
                                            data-element-item-price = "<?=strip_tags($arItem["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"])?>"
                                        >

                                           

                                            <?/*if($arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

                                                <?
                                                
                                                    $btn_name2 = GetMessage("LAND_CART_BTN_ADDED_NAME");

                                                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]) > 0)
                                                        $btn_name2 = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];
                                                ?>

                                                <span class="first">
                                                   <?=$arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>
                                                </span>

                                                <span class="second">
                                                    <?=$btn_name2?>
                                                </span> 

                                            <?else:*/?>

                                                <?=$arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>

                                            <?//endif;?>

                                        </a>
                                    </div>

                                <?endif;?>

                                <?if($issetDetail):?>

                                    <div class="button-child">
                                        <a class="link-def info-icon-link open-dialog-window" <?=$attrsBtnDetail?>><span class='bord-bot'><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["TARIFF_DETAIL_BTN_NAME"]?></span></a>
                                    </div>
                                
                                <?endif;?>
                                
                            </div>
                        
                        <?endif;?>  

                        

                    </div>                           
                        
                </div>

                <?if($arItem["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"] > 0):?>

                    <div class="tarif-img-wrap col-md-4 col-12 <?=$position_hor?> <?=$arItem["PROPERTIES"]["TARIFF_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"]?>">
                            
                        <?$img = CFile::ResizeImageGet($arItem["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"], array('width'=>400, 'height'=>300), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]); ?>

                        
                        <?if($issetDetail):?>
                            <a class="open-dialog-window" <?=$attrsBtnDetail?>></a>
                        <?endif;?>
                        
                        <img class="mx-auto d-block lazyload" data-src="<?=$img["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["TARIFF_PICTURE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["TARIFF_PICTURE"]["DESCRIPTION"]:"";?>"/>

                        <?if(strlen($arItem["PROPERTIES"]["TARIFF_PICTURE"]["~DESCRIPTION"])>0):?>

                            <div class="name-wrap">
                                <div class="image-descrip italic">
                                    <?=$arItem["PROPERTIES"]["TARIFF_PICTURE"]["~DESCRIPTION"]?>
                                </div>
                            </div>
                        <?endif;?>

                    </div>

                <?endif;?>

                
                <?if(!empty($arItem["PROPERTIES"]["TARIFF_PRICES"]["VALUE"])):?>

                    <div class="col-12 order-last">

                        <div class="list-wrap">
                        
                            <?if(strlen($arItem["PROPERTIES"]["TARIFF_PRICES_TITLE"]["VALUE"]) > 0):?>
                                <div class="name-list main1"><?=$arItem["PROPERTIES"]["TARIFF_PRICES_TITLE"]["~VALUE"]?></div>
                            <?endif;?>


                            <ul class="list-char">

                                <?if(!empty($arItem["PROPERTIES"]["TARIFF_PRICES"]["VALUE"]) && is_array($arItem["PROPERTIES"]["TARIFF_PRICES"]["VALUE"])):?>
                                
                                    <?foreach($arItem["PROPERTIES"]["TARIFF_PRICES"]["~VALUE"] as $k=>$val):?>
                                        <li class="clearfix">
                                        
                                            <table class="mobile-break">
                                                <tr>
                                                    <td class="left">
                                                        <div><?=$val?></div>
                                                    </td>
                                                    
                                                    <td class="dotted">
                                                        <div></div>
                                                    </td>
                                                    
                                                    <td class="right">
                                                        <div class="main1"><?=$arItem["PROPERTIES"]["TARIFF_PRICES"]["~DESCRIPTION"][$k]?></div>
                                                    </td>
                                                </tr>
                                            </table>
                                        
                                        </li>
                                    <?endforeach;?>

                                <?endif;?>

                            </ul>
                        </div>

                        <div class="clearfix"></div>

                        <?if((strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0) || !empty($arItem["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_PRICES"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"])):?>

                            <div class="buttons-wrap">
                            
                                <?if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0):?>

                                    <?
                                       

                                        $arClass = array();
                                        $arClass=array(
                                            "XML_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
                                            "FORM_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
                                            "MODAL_ID"=> $arItem["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
                                            "QUIZ_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"]
                                        );
                                        
                                        $arAttr=array();
                                        $arAttr=array(
                                            "XML_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
                                            "FORM_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
                                            "MODAL_ID"=> $arItem["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
                                            "LINK"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_LINK"]["VALUE"],
                                            "BLANK"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_BLANK"]["VALUE_XML_ID"],
                                            "HEADER"=> $block_name,
                                            "QUIZ_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"],
                                            "LAND_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_LAND"]["VALUE"]
                                        );
                                    ?>

                                    <div class="button-child">

                                        <a 
                                        <?
                                            if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_ONCLICK"]["VALUE"])>0) 
                                            {
                                                $str_onclick = str_replace("'", "\"", $arItem["PROPERTIES"]["TARIFF_BUTTON_ONCLICK"]["VALUE"]);

                                                echo "onclick='".$str_onclick."'";

                                                $str_onclick = "";
                                            }

                                            $b_options = array(
                                                "MAIN_COLOR" => "main-color",
                                                "STYLE" => ""
                                            );

                                            if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"]))
                                            {

                                                $b_options = array(
                                                    "MAIN_COLOR" => "btn-bgcolor-custom",
                                                    "STYLE" => "background-color: ".$arItem["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"].";"
                                                );

                                            }
                                        ?>

                                        class="button-def <?=$btn_view?> <?=$b_options["MAIN_COLOR"]?> element-item <?=CPhoenix::buttonEditClass($arClass)?><?if(!$show_menu):?><?if($count <= 3):?> big<?else:?> medium<?endif;?><?else:?> medium<?endif;?>" data-element-item-id="<?=$arItem["ID"]?>" data-element-item-type = "TRF" 
                                        data-element-item-name = "
                                            <?if(strlen($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"])):?>
                                                <?=str_replace( "\"", "'", strip_tags($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>
                                            <?else:?>
                                                <?=str_replace( "\"", "'", strip_tags($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>&nbsp;(<?=str_replace( "\"", "'", strip_tags($arItem["~NAME"]))?>)
                                            <?endif;?>
                                        "

                                        data-element-item-price = "<?=strip_tags($arItem["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"])?>"

                                        <?if(strlen($b_options["STYLE"])):?>
                                            style = "<?=$b_options["STYLE"]?>"
                                        <?endif;?>


                                        <?=CPhoenix::buttonEditAttr($arAttr)?> title='<?=CPhoenix::prepareText($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"])?>'>

                                            <?/*if($arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

                                                <?
                                                
                                                    $btn_name2 = GetMessage("LAND_CART_BTN_ADDED_NAME");

                                                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]) > 0)
                                                        $btn_name2 = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];
                                                ?>

                                                <span class="first">
                                                   <?=$arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>
                                                </span>

                                                <span class="second">
                                                    <?=$btn_name2?>
                                                </span> 

                                            <?else:*/?>

                                                <?=$arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>

                                            <?//endif;?>
                                                
                                        </a>
                                    </div>

                                   

                                <?endif;?>


                                <?if($issetDetail):?>

                                    <div class="button-child">
                                        <a class="link-def open-dialog-window info-icon-link" <?=$attrsBtnDetail?>><span class='bord-bot'><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["TARIFF_DETAIL_BTN_NAME"]?></span></a>
                                    </div>
                                
                                <?endif;?>
                                
                            </div>
                        
                        <?endif;?> 

                    </div>
                    
                <?endif;?>
                

            </div>


            
        </div>

    <?else:?>

        <?

            $class = "";
            $count = count($arItem["ELEMENTS"]);
            if($count <= 3)
                $class = "col-lg-4 col-md-6 col-12";
                
            
            else
                $class = "col-lg-3 col-md-6 col-12 four-elements";

            $round_height = "";

            if($arItem["PROPERTIES"]["TARIFF_ROUND_HEIGHT"]["VALUE"] == "Y")
                $round_height = "round-height";
        ?>

        <div class="tarif row no-gutters justify-content-center <?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>parent-animate<?endif;?> <?=$round_height?>">
            
            
            <?if(is_array($arItem["ELEMENTS"]) && !empty($arItem["ELEMENTS"])):?>
            
                <?foreach($arItem["ELEMENTS"] as $k=>$arTariff):?>

                    <?
                        $issetDetail = (!empty($arTariff["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]) || !empty($arTariff["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]));

                        if($issetDetail)
                            $attrsBtnDetail = "data-blocktitle=\"".$block_name."\" data-typename=\"tariff\" data-elementid=\"".$arTariff["ID"]."\"";
                    ?>

                    <div class="tarif-item <?=$class?>">

                        <?CPhoenix::admin_setting($arTariff, false)?>
                            
                        <div class="tarif-element <?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>child-animate opacity-zero<?endif;?>" >

                            <?if($arTariff["PROPERTIES"]["TARIFF_HIT"]["VALUE"] == "Y"):?><div class="star"></div><?endif;?>

                            <div class="row no-gutters tarif-element-inner">

                                <div class="col-12 trff-top-part">
                                
                                    <?if(strlen($arTariff["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]) > 0):?>
                                        <div class="name main1">
                                            <?=$arTariff["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]?>
                                        </div>
                                    <?endif;?>

                                    <?if($arTariff["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"] > 0):?>

                                        <div class="wr-img">
                                            
                                            <?$img = CFile::ResizeImageGet($arTariff["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"], array('width'=>400, 'height'=>300), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]); ?>

                                            
                                            <?if($issetDetail):?>
                                                <a class="open-dialog-window" <?=$attrsBtnDetail?>>
                                            <?endif;?>
                                            
                                            <img class="image lazyload" data-src="<?=$img["src"]?>" alt="<?=(strlen($arTariff["PROPERTIES"]["TARIFF_PICTURE"]["DESCRIPTION"]))? $arTariff["PROPERTIES"]["TARIFF_PICTURE"]["DESCRIPTION"]:"";?>"/>
                                                
                                             <?if((!empty($arTariff["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"])) || (!empty($arTariff["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]) && is_array($arTariff["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]))):?>
                                                </a>
                                            <?endif;?>
                                        </div>
                                    
                                    <?endif;?>
                                    
                                    <?if(strlen($arTariff["PROPERTIES"]["TARIFF_PREVIEW_TEXT"]["VALUE"]) > 0):?>
                                        <div class="tarif-descript italic">
                                            <?=$arTariff["PROPERTIES"]["TARIFF_PREVIEW_TEXT"]["~VALUE"]?>
                                        </div>
                                    <?endif;?>

                            
                                
                                    <?if(!empty($arTariff["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) || !empty($arTariff["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"])):?>
                                    
                                        <ul>
                                            
                                            <?if(is_array($arTariff["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) && !empty($arTariff["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"])):?>
                                                
                                                <?foreach($arTariff["PROPERTIES"]["TARIFF_INCLUDE"]["~VALUE"] as $val):?>
                                                    <li class="point-green"><?=$val?></li>
                                                <?endforeach;?>
                                                
                                            <?endif;?>
                                            
                                            <?if(is_array($arTariff["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"]) && !empty($arTariff["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"])):?>
                                                
                                                <?foreach($arTariff["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["~VALUE"] as $val):?>
                                                    <li><?=$val?></li>
                                                <?endforeach;?>
                                                
                                            <?endif;?>
                                            
                                        </ul>
                                    
                                    <?endif;?>
                                </div>

                                <div class="col-12 trff-bot-part align-self-end">
                                    
                                    <?if((!empty($arTariff["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) || !empty($arTariff["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"])) && (strlen($arTariff["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0 || strlen($arTariff["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0)):?>
                                        <div class="line-grey"></div>
                                    <?endif;?>
                                    
                                    <?if(strlen($arTariff["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0 || strlen($arTariff["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0):?>
                                        
                                        <div class="price-wrap row">
                                            
                                            <?if(strlen($arTariff["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0):?>
                                                <div class="old-price main2"><?=$arTariff["PROPERTIES"]["TARIFF_OLD_PRICE"]["~VALUE"]?></div>
                                            <?endif;?>

                                            <?if(strlen($arTariff["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0):?>
                                                <div class="price main1"><?=$arTariff["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"]?></div>
                                            <?endif;?>
                                            
                                        </div>
                                    
                                    <?endif;?>

                                    
                                    <?if(strlen($arTariff["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"]) <= 0):?>
                                        <?$arTariff["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"] = "form";?>
                                    <?endif;?>
                                    
                                    <?if((strlen($arTariff["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0) || !empty($arTariff["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]) || !empty($arTariff["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]) || !empty($arTariff["PROPERTIES"]["TARIFF_PRICES"]["VALUE"])):?>
                                    
                                        <div class="bot-wrap">
                                            
                                            <?if(strlen($arTariff["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0):?>

                                                <?
                                                    $arClass = array();
                                                    $arClass=array(
                                                        "XML_ID"=> $arTariff["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
                                                        "FORM_ID"=> $arTariff["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
                                                        "MODAL_ID"=> $arTariff["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
                                                        "QUIZ_ID"=> $arTariff["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"]
                                                    );

                                                    
                                                    $arAttr=array();
                                                    $arAttr=array(
                                                        "XML_ID"=> $arTariff["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
                                                        "FORM_ID"=> $arTariff["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
                                                        "MODAL_ID"=> $arTariff["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
                                                        "LINK"=> $arTariff["PROPERTIES"]["TARIFF_BUTTON_LINK"]["VALUE"],
                                                        "BLANK"=> $arTariff["PROPERTIES"]["TARIFF_BUTTON_BLANK"]["VALUE_XML_ID"],
                                                        "HEADER"=> $block_name,
                                                        "QUIZ_ID"=> $arTariff["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"],
                                                        "LAND_ID"=> $arTariff["PROPERTIES"]["TARIFF_BUTTON_LAND"]["VALUE"]
                                                    );
                                                ?>

                                            
                                                <div class="button-wrap">

                                                    <a 

                                                    <?
                                                        if(strlen($arTariff["PROPERTIES"]["TARIFF_BUTTON_ONCLICK"]["VALUE"])>0) 
                                                        {
                                                            $str_onclick = str_replace("'", "\"", $arTariff["PROPERTIES"]["TARIFF_BUTTON_ONCLICK"]["VALUE"]);

                                                            echo "onclick='".$str_onclick."'";

                                                            $str_onclick = "";
                                                        }

                                                        $b_options = array(
                                                            "MAIN_COLOR" => "main-color",
                                                            "STYLE" => ""
                                                        );

                                                        if(strlen($arTariff["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"]))
                                                        {

                                                            $b_options = array(
                                                                "MAIN_COLOR" => "btn-bgcolor-custom",
                                                                "STYLE" => "background-color: ".$arTariff["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"].";"
                                                            );

                                                        }
                                                    ?>


                                                    class = "button-def 
                                                            <?=$btn_view?>
                                                            <?=$b_options["MAIN_COLOR"]?>
                                                            element-item
                                                            <?=CPhoenix::buttonEditClass ($arClass)?>
                                                            <?= ($count <= 3) ? 'big' : 'medium'?>" 

                                                            data-element-item-id="<?=$arTariff["ID"]?>" 
                                                            data-element-item-type = "TRF" 
                                                            data-element-item-name = "
                                                                <?if(strlen($arTariff["PROPERTIES"]["TARIFF_NAME"]["~VALUE"])):?>
                                                                    <?=str_replace( "\"", "'", strip_tags($arTariff["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>
                                                                <?else:?>
                                                                    <?=str_replace( "\"", "'", strip_tags($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>&nbsp;(<?=str_replace( "\"", "'", strip_tags($arItem["~NAME"]))?>)
                                                                <?endif;?>
                                                            "

                                                            data-element-item-price = "<?=strip_tags($arTariff["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"])?>"

                                                            <?if(strlen($b_options["STYLE"])):?>
                                                                style = "<?=$b_options["STYLE"]?>"
                                                            <?endif;?>

                                                            <?=CPhoenix::buttonEditAttr($arAttr)?>>

                                                        <?/*if($arTariff["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

                                                            <?
                                                            
                                                                $btn_name2 = GetMessage("LAND_CART_BTN_ADDED_NAME");

                                                                if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]) > 0)
                                                                    $btn_name2 = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];
                                                            ?>

                                                            <span class="first">
                                                               <?=$arTariff["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>
                                                            </span>

                                                            <span class="second">
                                                                <?=$btn_name2?>
                                                            </span> 

                                                        <?else:*/?>

                                                            <?=$arTariff["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>

                                                        <?//endif;?>
                                                            
                                                    </a>
                                                </div>

                                          
                                            
                                            <?endif;?>

                                            <?if(!empty($arTariff["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]) 
                                                || !empty($arTariff["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]) 
                                                || !empty($arTariff["PROPERTIES"]["TARIFF_PRICES"]["VALUE"])):?>

                                                <div class="link-wrap">
                                                    <a class="open-dialog-window info-icon-link" <?=$attrsBtnDetail?>><span class='bord-bot'><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["TARIFF_DETAIL_BTN_NAME"]?></span></a>
                                                </div>
                                                
                                            <?endif;?>
                                        </div>
                                    
                                    <?endif;?>
                                </div>

                            </div>
                            
                        
                        </div>
                            
                     
                    </div>
                    
                <?endforeach;?>
            
            <?endif;?>
            
        </div>

      
    <?endif;?>
                    
<?endif;?>


<?if($arItem["PROPERTIES"]["TARIFF_VIEW"]["VALUE_XML_ID"] == "full"):?>

    <?
        if($arItem["PROPERTIES"]["TARIFF_PICTURE_POSITION"]["VALUE_XML_ID"] == "")
            $arItem["PROPERTIES"]["TARIFF_PICTURE_POSITION"]["VALUE_XML_ID"] == "left";

        $position_hor = $arItem["PROPERTIES"]["TARIFF_PICTURE_POSITION"]["VALUE_XML_ID"];

        if($position_hor == "left")
            $position_hor = "order-first";

        if($position_hor == "right")
            $position_hor = "order-last";


        if( $arItem["PROPERTIES"]["TARIFF_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "" )
            $arItem["PROPERTIES"]["TARIFF_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] = "order-first-mob";
    ?>

    <?if($show_menu):?>

        <?
            $issetDetail = (!empty($arItem["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"]));

            if($issetDetail)
                $attrsBtnDetail = "data-blocktitle=\"".$block_name."\" data-typename=\"tariff\" data-elementid=\"".$arItem["ID"]."\"";
        ?>

        <div class="tarif-2 <?=$arItem["PROPERTIES"]["TARIFF_TEXT_COLOR"]["VALUE_XML_ID"]?>">

            <div class="row">


                <div class="left-wrap-inner <?if($arItem["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"] > 0):?>col-md-8 col-12<?else:?>col-12<?endif;?>">

                   
                    <?if(strlen($arItem["PROPERTIES"]["TARIFF_NAME"]["VALUE"]) > 0):?>
                        <div class="title main1">
                            <?=$arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]?> <?if($arItem["PROPERTIES"]["TARIFF_HIT"]["VALUE"] == "Y"):?><span class="hit"></span><?endif;?>
                        </div>
                    <?endif;?>

                    <?if(strlen($arItem["PROPERTIES"]["TARIFF_PREVIEW_TEXT"]["VALUE"]) > 0):?>
                        <div class="subtitle italic">
                            <?=$arItem["PROPERTIES"]["TARIFF_PREVIEW_TEXT"]["~VALUE"]?>
                        </div>
                    <?endif;?>

                    <?if(!empty($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"])):?>
                        
                        <div class="list-wrap">
                            <div class="row">
                            
                                <?if(is_array($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) && !empty($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"])):?>
                                
                                    <div class="col-lg-6 col-12">
  
                                        <ul class="adv-plus-minus">
                                            
                                            <?foreach($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["~VALUE"] as $val):?>
                                                <li class="point-green"><?=$val?></li>
                                            <?endforeach;?>
                                        
                                        </ul>

                                    </div>
                                
                                <?endif;?>
                                
                                <?if(is_array($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"]) && !empty($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"])):?>
                                 
                                    <div class="col-lg-6 col-12">
                                    
                                        
                                        <ul class="adv-plus-minus">
                                            
                                            <?foreach($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["~VALUE"] as $val):?>
                                                <li><?=$val?></li>
                                            <?endforeach;?>

                                        </ul>
                                        
                                    </div>
                                
                                <?endif;?>
                                
                            </div>
                        </div>
                    
                    <?endif;?>

                    <?if(strlen($arItem["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0):?>
                            
                    
                        

                        <div class="price-wrap row">
                        
                            <?if(strlen($arItem["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0):?>
                                <div class="old-price"><?=$arItem["PROPERTIES"]["TARIFF_OLD_PRICE"]["~VALUE"]?></div>
                            <?endif;?>

                            <?if(strlen($arItem["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0):?>
                                <div class="price main1"><?=$arItem["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"]?></div>
                            <?endif;?>
                            
                        </div>

                        
                                
                            
                    <?endif;?>


                    <?if(empty($arItem["PROPERTIES"]["TARIFF_PRICES"]["VALUE"])):?>

                        <?if((strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0) || !empty($arItem["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_PRICES"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"])):?>

                            <div class="buttons-wrap">
                            
                                <?if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0):?>

                                    <?
                                        $arClass = array();
                                        $arClass=array(
                                            "XML_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
                                            "FORM_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
                                            "MODAL_ID"=> $arItem["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
                                            "QUIZ_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"]
                                        );
                                        
                                        $arAttr=array();
                                        $arAttr=array(
                                            "XML_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
                                            "FORM_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
                                            "MODAL_ID"=> $arItem["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
                                            "LINK"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_LINK"]["VALUE"],
                                            "BLANK"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_BLANK"]["VALUE_XML_ID"],
                                            "HEADER"=> $block_name,
                                            "QUIZ_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"],
                                            "LAND_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_LAND"]["VALUE"]
                                        );
                                    ?>

                                    

                                    <div class="button-child">

                                        <a 

                                        <?
                                            if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_ONCLICK"]["VALUE"])>0) 
                                            {
                                                $str_onclick = str_replace("'", "\"", $arItem["PROPERTIES"]["TARIFF_BUTTON_ONCLICK"]["VALUE"]);

                                                echo "onclick='".$str_onclick."'";

                                                $str_onclick = "";
                                            }

                                            $b_options = array(
                                                "MAIN_COLOR" => "main-color",
                                                "STYLE" => ""
                                            );

                                            if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"]))
                                            {

                                                $b_options = array(
                                                    "MAIN_COLOR" => "btn-bgcolor-custom",
                                                    "STYLE" => "background-color: ".$arItem["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"].";"
                                                );
                                            }
                                        ?>


                                        class="button-def <?=$btn_view?> <?=$b_options["MAIN_COLOR"]?> element-item <?=CPhoenix::buttonEditClass ($arClass)?><?if(!$show_menu):?> big<?else:?> medium<?endif;?>" data-element-item-id="<?=$arItem["ID"]?>" data-element-item-type = "TRF"
                                        data-element-item-name = "
                                            <?if(strlen($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"])):?>
                                                <?=str_replace( "\"", "'", strip_tags($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>
                                            <?else:?>
                                                <?=str_replace( "\"", "'", strip_tags($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>&nbsp;(<?=str_replace( "\"", "'", strip_tags($arItem["~NAME"]))?>)
                                            <?endif;?>
                                        "

                                        data-element-item-price = "<?=strip_tags($arItem["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"])?>"

                                        <?if(strlen($b_options["STYLE"])):?>
                                            style = "<?=$b_options["STYLE"]?>"
                                        <?endif;?>

                                         <?=CPhoenix::buttonEditAttr($arAttr)?> title='<?=CPhoenix::prepareText($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"])?>'>

                                            <?/*if($arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

                                                <?
                                                
                                                    $btn_name2 = GetMessage("LAND_CART_BTN_ADDED_NAME");

                                                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]) > 0)
                                                        $btn_name2 = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];
                                                ?>

                                                <span class="first">
                                                   <?=$arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>
                                                </span>

                                                <span class="second">
                                                    <?=$btn_name2?>
                                                </span> 

                                            <?else:*/?>

                                                <?=$arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>

                                            <?//endif;?>

                                            
                                        </a>
                                    </div>

                                 

                                <?endif;?>


                                <?if($issetDetail):?>

                                    <div class="button-child">
                                        <a class="link-def open-dialog-window info-icon-link" <?=$attrsBtnDetail?>><span class='bord-bot'><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["TARIFF_DETAIL_BTN_NAME"]?></span></a>
                                    </div>
                                
                                <?endif;?>
                                
                            </div>
                        
                        <?endif;?>  

                    <?endif;?>
                                          
                        
                </div>

                <?if($arItem["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"] > 0):?>

                    <div class="tarif-img-wrap col-md-4 col-12 <?=$position_hor?> <?=$arItem["PROPERTIES"]["TARIFF_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"]?>">

                            
                        <?$img = CFile::ResizeImageGet($arItem["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"], array('width'=>300, 'height'=>300), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]); ?>

                        
                        <?if($issetDetail):?>
                            <a class="open-dialog-window" <?=$attrsBtnDetail?>></a>
                        <?endif;?>
                        
                        <img class="mx-auto d-block lazyload" data-src="<?=$img["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["TARIFF_PICTURE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["TARIFF_PICTURE"]["DESCRIPTION"]:"";?>"/>

                        <?if(strlen($arItem["PROPERTIES"]["TARIFF_PICTURE"]["~DESCRIPTION"])>0):?>

                            <div class="name-wrap">
                                <div class="image-descrip italic">
                                    <?=$arItem["PROPERTIES"]["TARIFF_PICTURE"]["~DESCRIPTION"]?>
                                </div>
                            </div>
                            
                        <?endif;?>

                    </div>

                <?endif;?>

                
                <?if(!empty($arItem["PROPERTIES"]["TARIFF_PRICES"]["VALUE"])):?>

                    <div class="col-12 order-last">

                        <div class="list-wrap">
                        
                            <?if(strlen($arItem["PROPERTIES"]["TARIFF_PRICES_TITLE"]["VALUE"]) > 0):?>
                                <div class="name-list main1"><?=$arItem["PROPERTIES"]["TARIFF_PRICES_TITLE"]["~VALUE"]?></div>
                            <?endif;?>


                            <ul class="list-char">
                                
                                <?foreach($arItem["PROPERTIES"]["TARIFF_PRICES"]["~VALUE"] as $k=>$val):?>
                                    <li class="clearfix">
                                    
                                        <table class="mobile-break">
                                            <tr>
                                                <td class="left">
                                                    <div><?=$val?></div>
                                                </td>
                                                
                                                <td class="dotted">
                                                    <div></div>
                                                </td>
                                                
                                                <td class="right">
                                                    <div class="main1"><?=$arItem["PROPERTIES"]["TARIFF_PRICES"]["~DESCRIPTION"][$k]?></div>
                                                </td>
                                            </tr>
                                        </table>
                                    
                                    </li>
                                <?endforeach;?>

                            </ul>
                        </div>

                 

                        <?if((strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0) 
                            || !empty($arItem["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]) 
                            || !empty($arItem["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]) 
                            || !empty($arItem["PROPERTIES"]["TARIFF_PRICES"]["VALUE"]) 
                            || !empty($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"])
                            || !empty($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"])):?>

                            <div class="buttons-wrap">
                            
                                <?if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0):?>

                                <?
                                    $form_id = "";
                                    if($arItem["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"] > 0)
                                        $form_id = $arItem["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"];


                                    $arClass = array();
                                    $arClass=array(
                                        "XML_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
                                        "FORM_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
                                        "MODAL_ID"=> $arItem["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
                                        "QUIZ_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"]
                                    );
                                    
                                    $arAttr=array();
                                    $arAttr=array(
                                        "XML_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
                                        "FORM_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
                                        "MODAL_ID"=> $arItem["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
                                        "LINK"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_LINK"]["VALUE"],
                                        "BLANK"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_BLANK"]["VALUE_XML_ID"],
                                        "HEADER"=> $block_name,
                                        "QUIZ_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"],
                                        "LAND_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_LAND"]["VALUE"]
                                    );
                                ?>

                                    

                                    <div class="button-child">

                                        <a 

                                        <?
                                            if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_ONCLICK"]["VALUE"])>0) 
                                            {
                                                $str_onclick = str_replace("'", "\"", $arItem["PROPERTIES"]["TARIFF_BUTTON_ONCLICK"]["VALUE"]);

                                                echo "onclick='".$str_onclick."'";

                                                $str_onclick = "";
                                            }


                                            if($show_menu)
                                            {
                                                $arOptions = array(
                                                    "BTN_SIZE" => "medium"
                                                );
                                            }
                                            else
                                            {
                                                $arOptions = array(
                                                    "BTN_SIZE" => "big"
                                                );
                                            }

                                            $b_options = array(
                                                "MAIN_COLOR" => "main-color",
                                                "STYLE" => ""
                                            );

                                            if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"]))
                                            {

                                                $b_options = array(
                                                    "MAIN_COLOR" => "btn-bgcolor-custom",
                                                    "STYLE" => "background-color: ".$arItem["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"].";"
                                                );

                                            }
                                        ?>


                                        class = "button-def 
                                                <?=$b_options["MAIN_COLOR"]?> 
                                                <?=$btn_view?>
                                                <?=CPhoenix::buttonEditClass ($arClass)?> 
                                                <?=$arOptions["BTN_SIZE"]?>" 

                                                data-element-item-id="<?=$arItem["ID"]?>" 
                                                data-element-item-type = "TRF"
                                                data-element-item-name = "
                                                    <?if(strlen($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"])):?>
                                                        <?=str_replace( "\"", "'", strip_tags($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>
                                                    <?else:?>
                                                        <?=str_replace( "\"", "'", strip_tags($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>&nbsp;(<?=str_replace( "\"", "'", strip_tags($arItem["~NAME"]))?>)
                                                    <?endif;?>
                                                "

                                                data-element-item-price = "<?=strip_tags($arItem["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"])?>"
                                                <?if(strlen($b_options["STYLE"])):?>
                                                    style = "<?=$b_options["STYLE"]?>"
                                                <?endif;?>

                                                <?=CPhoenix::buttonEditAttr($arAttr)?>
                                                title='<?=CPhoenix::prepareText($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"])?>'>

                                            <?/*if($arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

                                                <?
                                                    $btn_name2 = GetMessage("LAND_CART_BTN_ADDED_NAME");

                                                    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]) > 0)
                                                        $btn_name2 = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];
                                                ?>

                                                <span class="first">
                                                   <?=$arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>
                                                </span>

                                                <span class="second">
                                                    <?=$btn_name2?>
                                                </span> 

                                            <?else:*/?>
                                                <?=$arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?></a>
                                            <?//endif;?>
                                    </div>

                                 

                                <?endif;?>


                                <?if($issetDetail):?>

                                    <div class="button-child">
                                        <a class="link-def open-dialog-window info-icon-link" <?=$attrsBtnDetail?>><span class='bord-bot'><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["TARIFF_DETAIL_BTN_NAME"]?></span></a>
                                    </div>
                                
                                <?endif;?>
                                
                            </div>
                        
                        <?endif;?> 

                    </div>
                    
                <?endif;?>
                    

            </div>

            


            
        </div>

    <?else:?>

        <?
            $issetDetail = (!empty($arItem["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]));

            if($issetDetail)
                $attrsBtnDetail = "data-blocktitle=\"".$block_name."\" data-typename=\"tariff\" data-elementid=\"".$arItem["ID"]."\"";
        ?>
    
        <div class="tarif-2 <?=$arItem["PROPERTIES"]["TARIFF_TEXT_COLOR"]["VALUE_XML_ID"]?>">

            <?
                if($arItem["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"] > 0)
                    $img = CFile::ResizeImageGet($arItem["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"], array('width'=>500, 'height'=>500), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
            ?>
        
       
            
            <div class="tarif-table">
                <div class="row">

                    <div class="tarif-cell text-part <?if($arItem["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"] > 0):?>col-lg-7 col-md-12 col-12<?else:?>col-12<?endif;?>">

                        <div class="left-wrap-inner">
                    
                            <?if(strlen($arItem["PROPERTIES"]["TARIFF_NAME"]["VALUE"]) > 0):?>
                                <div class="title main1">
                                    <?=$arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]?> <?if($arItem["PROPERTIES"]["TARIFF_HIT"]["VALUE"] == "Y"):?><span class="hit"></span><?endif;?>
                                </div>
                            <?endif;?>

                            <?if(strlen($arItem["PROPERTIES"]["TARIFF_PREVIEW_TEXT"]["VALUE"]) > 0):?>
                                <div class="subtitle italic">
                                    <?=$arItem["PROPERTIES"]["TARIFF_PREVIEW_TEXT"]["~VALUE"]?>
                                </div>
                            <?endif;?>
                            
                            
                            <div class="tarif-body">

                                <?if($arItem["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"] > 0):?>

                                    <noindex>

                                        <div class="image-hidden visible-md visible-sm visible-xs">

                                            <?if($issetDetail):?>
                                                
                                                <?$all_id = "";?>

                                                <?if(!empty($arItem["ID_ALL"])):?>
                                                    <?$all_id = implode("," , $arItem["ID_ALL"]);?>
                                                <?endif;?>

                                                <a class="open-dialog-window" <?=$attrsBtnDetail?>>
                                            <?endif;?>
                                        
                                            <img class="d-block mx-auto lazyload" data-src="<?=$img["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["TARIFF_PICTURE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["TARIFF_PICTURE"]["DESCRIPTION"]:"";?>"/>
                                            
                                            <?if((!empty($arItem["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"])) || (!empty($arItem["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]) && is_array($arItem["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]))):?>
                                                </a>
                                            <?endif;?>

                                            <?if(strlen($arItem["PROPERTIES"]["TARIFF_PICTURE"]["~DESCRIPTION"])>0):?>

                                                <div class="name-wrap">
                                                    <div class="image-descrip italic">
                                                        <?=$arItem["PROPERTIES"]["TARIFF_PICTURE"]["~DESCRIPTION"]?>
                                                    </div>
                                                </div>
                                            <?endif;?>

                                        </div>

                                    </noindex>

                                <?endif;?>
                            
                                <?if(strlen($arItem["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0):?>
                                
                                    <div class="list-wrap">

                                        <div class="price-wrap row">
                                        
                                            <?if(strlen($arItem["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0):?>
                                                <div class="old-price main2"><?=$arItem["PROPERTIES"]["TARIFF_OLD_PRICE"]["~VALUE"]?></div>
                                            <?endif;?>

                                            <?if(strlen($arItem["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0):?>
                                                <div class="price main1"><?=$arItem["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"]?></div>
                                            <?endif;?>
                                            
                                        </div>
                                        
                                    </div>
                                
                                <?endif;?>
                            
                                
                                <?if(!empty($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"])):?>
                            
                                    <div class="list-wrap">
                                        <div class="row">
                                        
                                            <?if(is_array($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) && !empty($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"])):?>
                                            
                                                <div class="col-lg-6 col-12">
              
                                                    <ul class="adv-plus-minus">
                                                        
                                                        <?foreach($arItem["PROPERTIES"]["TARIFF_INCLUDE"]["~VALUE"] as $val):?>
                                                            <li class="point-green"><?=$val?></li>
                                                        <?endforeach;?>
                                                    
                                                    </ul>

                                                </div>
                                            
                                            <?endif;?>
                                            
                                            <?if(is_array($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"]) && !empty($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"])):?>
                                             
                                                <div class="col-lg-6 col-12">
                                                
                                                    
                                                    <ul class="adv-plus-minus">
                                                        
                                                        <?foreach($arItem["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["~VALUE"] as $val):?>
                                                            <li><?=$val?></li>
                                                        <?endforeach;?>

                                                    </ul>
                                                    
                                                </div>
                                            
                                            <?endif;?>
                                            
                                        </div>
                                    </div>
                                
                                <?endif;?>
                                
                                <?if(!empty($arItem["PROPERTIES"]["TARIFF_PRICES"]["VALUE"])):?>

                                    <div class="list-wrap">
                                    
                                        <?if(strlen($arItem["PROPERTIES"]["TARIFF_PRICES_TITLE"]["VALUE"]) > 0):?>
                                            <div class="name main1"><?=$arItem["PROPERTIES"]["TARIFF_PRICES_TITLE"]["~VALUE"]?></div>
                                        <?endif;?>


                                        <ul class="list-char">
                                            
                                            <?foreach($arItem["PROPERTIES"]["TARIFF_PRICES"]["~VALUE"] as $k=>$val):?>
                                                <li class="clearfix">
                                                
                                                    <table class="mobile-break">
                                                        <tr>
                                                            <td class="left">
                                                                <div class="left"><?=$val?></div>
                                                            </td>
                                                            
                                                            <td class="dotted">
                                                                <div class="dotted"></div>
                                                            </td>
                                                            
                                                            <td class="right">
                                                                <div class="main1 right"><?=$arItem["PROPERTIES"]["TARIFF_PRICES"]["~DESCRIPTION"][$k]?></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                
                                                </li>
                                            <?endforeach;?>

                                        </ul>
                                    </div>
                                
                                <?endif;?>
                                
                                <?if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"]) <= 0):?>
                                    <?$arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"] = "form";?>
                                <?endif;?>
                                

                               
                                <?if((strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0) || !empty($arItem["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]) || !empty($arItem["PROPERTIES"]["TARIFF_PRICES"]["VALUE"])):?>

                                    <div class="buttons-wrap no-margin-left-right">
                                    
                                        <?if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0):?>

                                            <?
                                                $arClass = array();
                                                $arClass=array(
                                                    "XML_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
                                                    "FORM_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
                                                    "MODAL_ID"=> $arItem["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
                                                    "QUIZ_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"]
                                                );
                                                
                                                $arAttr=array();
                                                $arAttr=array(
                                                    "XML_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
                                                    "FORM_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
                                                    "MODAL_ID"=> $arItem["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
                                                    "LINK"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_LINK"]["VALUE"],
                                                    "BLANK"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_BLANK"]["VALUE_XML_ID"],
                                                    "HEADER"=> $block_name,
                                                    "QUIZ_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"],
                                                    "LAND_ID"=> $arItem["PROPERTIES"]["TARIFF_BUTTON_LAND"]["VALUE"]
                                                );
                                            ?>

                                            

                                                <div class="button-child">

                                                    <a 

                                                    <?
                                                        if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_ONCLICK"]["VALUE"])>0) 
                                                        {
                                                            $str_onclick = str_replace("'", "\"", $arItem["PROPERTIES"]["TARIFF_BUTTON_ONCLICK"]["VALUE"]);

                                                            echo "onclick='".$str_onclick."'";

                                                            $str_onclick = "";
                                                        }

                                                        $b_options = array(
                                                            "MAIN_COLOR" => "main-color",
                                                            "STYLE" => ""
                                                        );

                                                        if(strlen($arItem["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"]))
                                                        {

                                                            $b_options = array(
                                                                "MAIN_COLOR" => "btn-bgcolor-custom",
                                                                "STYLE" => "background-color: ".$arItem["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"].";"
                                                            );

                                                        }
                                                    ?>


                                                    class="button-def <?=$btn_view?> <?=$b_options["MAIN_COLOR"]?> element-item <?=CPhoenix::buttonEditClass ($arClass)?> <?if(!$show_menu):?> big<?else:?> medium<?endif;?>" data-element-item-id="<?=$arItem["ID"]?>" data-element-item-type = "TRF"

                                                    data-element-item-name = "
                                                        <?if(strlen($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"])):?>
                                                            <?=str_replace( "\"", "'", strip_tags($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>
                                                        <?else:?>
                                                            <?=str_replace( "\"", "'", strip_tags($arItem["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>&nbsp;(<?=str_replace( "\"", "'", strip_tags($arItem["~NAME"]))?>)
                                                        <?endif;?>
                                                    "

                                                    data-element-item-price = "<?=strip_tags($arItem["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"])?>"

                                                    <?if(strlen($b_options["STYLE"])):?>
                                                        style = "<?=$b_options["STYLE"]?>"
                                                    <?endif;?>

                                                    <?=CPhoenix::buttonEditAttr ($arAttr)?>>

                                                        <?/*if($arItem["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

                                                            <?
                                                            
                                                                $btn_name2 = GetMessage("LAND_CART_BTN_ADDED_NAME");

                                                                if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]) > 0)
                                                                    $btn_name2 = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];
                                                            ?>

                                                            <span class="first">
                                                               <?=$arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>
                                                            </span>

                                                            <span class="second">
                                                                <?=$btn_name2?>
                                                            </span> 

                                                        <?else:*/?>

                                                            <?=$arItem["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>

                                                        <?/*endif;*/?>
                                                            

                                                    </a>
                                                </div>

                                        

                                        <?endif;?>


                                        <?if($issetDetail):?>

                                            <div class="button-child">
                                                <a class="button-def <?=$btn_view?> secondary big open-dialog-window" <?=$attrsBtnDetail?>>

                                                    <span class="info-icon-link">
                                                        <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["TARIFF_DETAIL_BTN_NAME"]?>
                                                    </span>
                                                </a>
                                            </div>
                                        
                                        <?endif;?>
                                        
                                    </div>
                                
                                <?endif;?>
                                
                            </div>

                        </div>
                    </div>

                    <?if($arItem["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"] > 0):?>
                
                        <div class="tarif-cell tarif-img-wrap image-part col-md-5 col-12 hidden-md hidden-sm hidden-xs <?=$position_hor?> <?=$arItem["PROPERTIES"]["TARIFF_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"]?>">
                        
                            <img class="img-fluid mx-auto d-block lazyload" data-src="<?=$img["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["TARIFF_PICTURE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["TARIFF_PICTURE"]["DESCRIPTION"]:"";?>"/>

                            <div class="name-wrap">
                                <div class="image-descrip italic">
                                    <?=$arItem["PROPERTIES"]["TARIFF_PICTURE"]["~DESCRIPTION"]?>
                                </div>
                            </div>
                            
                        </div>
                    
                    <?endif;?>

                </div>
                
            </div>
          
                
            
        </div>

    <?endif;?>

<?endif;?>