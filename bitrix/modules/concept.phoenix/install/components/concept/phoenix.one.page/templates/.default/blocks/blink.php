<?if(is_array($arItem["ELEMENTS"]) && !empty($arItem["ELEMENTS"])):?>

    <div class="banners-menu big-parent-colls <?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>parent-animate<?endif;?>">
        <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"] && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MODE_FAST_EDIT"]['VALUE']["ACTIVE"] == 'Y'):?>

            <div class='change-colls-info'><?=GetMessage('PHOENIX_BLINK_INFO_SAVE')?></div>

        <?endif;?>


        <div class="row align-items-center frame-wrap">

            <?foreach($arItem["ELEMENTS"] as $k=>$arLink):?>

                <?
                    $b_options = array(
                        "MAIN_COLOR" => "",
                        "STYLE" => ""
                    );

                    if(strlen($arLink["PROPERTIES"]["BLINK_BUTTON_BG_COLOR"]["VALUE"]))
                    {

                        $b_options = array(
                            "MAIN_COLOR" => "btn-bgcolor-custom",
                            "STYLE" => "background-color: ".$arLink["PROPERTIES"]["BLINK_BUTTON_BG_COLOR"]["VALUE"].";"
                        );

                    }
                ?>

                <?
                    $size = array("width" => 430, "height" => 370);
                    $cols = 'col-lg-3 col-sm-6 col-12 small';

                    if($arLink["PROPERTIES"]['BLINK_COLS']['VALUE_XML_ID'] == 'middle')
                    {
                        $size = array("width" => 750, "height" => 370);
                        $cols = 'col-sm-6 col-12 middle';
                    }
                            
                    if($show_menu)
                    {
                        $cols = 'col-lg-4 col-sm-6 col-12 small';

                        if($arLink["PROPERTIES"]['BLINK_COLS']['VALUE_XML_ID'] == 'middle')
                            $cols = 'col-lg-8 col-sm-6 col-12 middle';
                    }

                        
                    $size2 = array("width" => 356, "height" => 255);
                    $size3 = array("width" => 400, "height" => 262);


                    if($arLink["PROPERTIES"]['BLINK_TEXT_POS']['VALUE_XML_ID'] == "")
                        $arLink["PROPERTIES"]['BLINK_TEXT_POS']['VALUE_XML_ID'] = "text-align-left";

                    if($arLink["PROPERTIES"]['BLINK_BTN_TYPE']['VALUE_XML_ID'] == "")
                        $arLink["PROPERTIES"]['BLINK_BTN_TYPE']['VALUE_XML_ID'] = "form";


                    $anim = "hover-on";

                    if($arLink["PROPERTIES"]['BLINK_ANIM_OFF']['VALUE_XML_ID'] == "Y")
                        $anim = "hover-off";


                    $style_for_class_frame = 'style = "';
                    $style_for_class_frame_flag = "N";

                    if( strlen($arLink["PROPERTIES"]['BLINK_BG_CLR']['VALUE']) >0 )
                    {
                        if($style_for_class_frame_flag != "Y")
                            $style_for_class_frame_flag = "Y";

                        $style_for_class_frame .= 'background-color:'.$arLink["PROPERTIES"]['BLINK_BG_CLR']['VALUE'].';';
                    }

                    $style_for_class_frame .='"';

                    if($arLink["PROPERTIES"]['BLINK_TEXT_COLOR']['VALUE_XML_ID'] == "")
                        $arLink["PROPERTIES"]['BLINK_TEXT_COLOR']['VALUE_XML_ID'] = "light";

                    

                ?>


                <div class="<?=$cols?> parent-change-cools<?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?> child-animate opacity-zero<?endif;?>">
                    <input type="hidden" class='colls_code' value="BLINK_COLS">
                    <input type="hidden" class='colls_middle' value="<?=$arSection['ENUM_COLLS_BLINK'][0]?>">
                    <input type="hidden" class='colls_small' value="<?=$arSection['ENUM_COLLS_BLINK'][1]?>">
                    
                    <div class="frame <?=$arLink["PROPERTIES"]['BLINK_TEXT_COLOR']['VALUE_XML_ID']?> <?=$anim?>" <?if( $style_for_class_frame_flag == "Y" ) echo $style_for_class_frame;?> >


                        <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"] && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MODE_FAST_EDIT"]['VALUE']["ACTIVE"] == 'Y'):?>

                            <span class='change-colls' data-type='element' data-element-id='<?=$arLink['ID']?>'></span>

                        <?endif;?>

                        <?if(strlen($arLink["PROPERTIES"]['BLINK_BTN_TYPE']['VALUE_XML_ID'])>0)

                            {
                                $form_id = "";
                                if($arLink["PROPERTIES"]["BLINK_BUTTON_FORM"]["VALUE"] > 0)
                                    $form_id = $arLink["PROPERTIES"]["BLINK_BUTTON_FORM"]["VALUE"];

                                $product_id = "";

                                if($arLink["PROPERTIES"]["BLINK_OFFER_ID"]["VALUE"])
                                    $product_id = $arLink["PROPERTIES"]["BLINK_OFFER_ID"]["VALUE"];

                                else if($arLink["PROPERTIES"]["BLINK_CATALOG_ID"]["VALUE"])
                                    $product_id = $arLink["PROPERTIES"]["BLINK_CATALOG_ID"]["VALUE"];


                                $arClass = array();
                                $arClass=array(
                                    "XML_ID"=> $arLink["PROPERTIES"]["BLINK_BTN_TYPE"]["VALUE_XML_ID"],
                                    "FORM_ID"=> $form_id,
                                    "MODAL_ID"=> $arLink["PROPERTIES"]["BLINK_BUTTON_MODAL"]["VALUE"],
                                    "QUIZ_ID"=> $arLink["PROPERTIES"]["BLINK_BUTTON_QUIZ"]["VALUE"],
                                    "FAST_ORDER_PRODUCT_ID" => $product_id,
                                    "ADD2CART_PRODUCT_ID" => $product_id
                                );

                                $arAttr=array();
                                $arAttr=array(
                                    "XML_ID"=> $arLink["PROPERTIES"]["BLINK_BTN_TYPE"]["VALUE_XML_ID"],
                                    "FORM_ID"=> $form_id,
                                    "MODAL_ID"=> $arLink["PROPERTIES"]["BLINK_BUTTON_MODAL"]["VALUE"],
                                    "LINK"=> $arLink["PROPERTIES"]["BLINK_BUTTON_LINK"]["VALUE"],
                                    "BLANK"=> $arLink["PROPERTIES"]["BLINK_BUTTON_BLANK"]["VALUE_XML_ID"],
                                    "HEADER"=> $block_name,
                                    "QUIZ_ID"=> $arLink["PROPERTIES"]["BLINK_BUTTON_QUIZ"]["VALUE"],
                                    "LAND_ID"=> $arLink["PROPERTIES"]["BLINK_BTN_LAND"]["VALUE"],
                                    "FAST_ORDER_PRODUCT_ID" => $product_id,
                                    "ADD2CART_PRODUCT_ID" => $product_id
                                );
                            }
                        ?>

                        <?if($arLink["PROPERTIES"]['BLINK_LINK_BLOCK']['VALUE'] == 'Y' && strlen($arLink["PROPERTIES"]['BLINK_BTN_TYPE']['VALUE_XML_ID'])>0):?>

                            <a 
                            <?
                                if(strlen($arLink["PROPERTIES"]["BLINK_ONCLICK"]["VALUE"])>0) 
                                {
                                    $str_onclick = str_replace("'", "\"", $arLink["PROPERTIES"]["BLINK_ONCLICK"]["VALUE"]);

                                    echo "onclick='".$str_onclick."'";

                                    $str_onclick = "";
                                }
                            ?>

                            class="wrap-link <?=CPhoenix::buttonEditClass($arClass)?>" <?=CPhoenix::buttonEditAttr($arAttr)?>></a>


                            <?if($arLink["PROPERTIES"]["BLINK_BTN_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

                                <a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL"]?>" 
                                    class="wrap-link common-btn-basket-style-added">
                                </a>

                            <?endif;?>


                        <?endif;?>


                        <?if( $arLink["PROPERTIES"]['BLINK_BACKGROUND']['VALUE'] > 0 ):?>


                            <?
                                $imgXs = CFile::ResizeImageGet($arLink["PROPERTIES"]['BLINK_BACKGROUND']['VALUE'], $size3, BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                            
                            
                                $imgMd = CFile::ResizeImageGet($arLink["PROPERTIES"]['BLINK_BACKGROUND']['VALUE'], $size2, BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                            
                          
                                $imgXl = CFile::ResizeImageGet($arLink["PROPERTIES"]['BLINK_BACKGROUND']['VALUE'], $size, BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                            ?>

                            <img class="img lazyload visible-sm visible-xs" data-src="<?=$imgXs["src"]?>" alt="<?=(strlen($arLink["PROPERTIES"]["BLINK_BACKGROUND"]["DESCRIPTION"]))? $arLink["PROPERTIES"]["BLINK_BACKGROUND"]["DESCRIPTION"]:"";?>"/>
                            <img class="img lazyload visible-md" data-src="<?=$imgMd["src"]?>" alt="<?=(strlen($arLink["PROPERTIES"]["BLINK_BACKGROUND"]["DESCRIPTION"]))? $arLink["PROPERTIES"]["BLINK_BACKGROUND"]["DESCRIPTION"]:"";?>"/>
                            <img class="img lazyload visible-xxl visible-xl visible-lg" data-src="<?=$imgXl["src"]?>" alt="<?=(strlen($arLink["PROPERTIES"]["BLINK_BACKGROUND"]["DESCRIPTION"]))? $arLink["PROPERTIES"]["BLINK_BACKGROUND"]["DESCRIPTION"]:"";?>"/>

                        <?endif;?>

                                
                        <div class="small-shadow"></div>
                        <div class="frameshadow"></div>
                        
                        <div class="text <?=$arLink["PROPERTIES"]['BLINK_TEXT_POS']['VALUE_XML_ID']?>">
                        
                            <div class="cont <?=($arLink["PROPERTIES"]['BLINK_TEXT_VIEW']['VALUE'] === 'Y')?'cont-hide-subtext':''?>">
                                <div class="name bold"><?=$arLink["PROPERTIES"]['BLINK_TITLE']['~VALUE']?></div>

                                <?if(!empty($arLink["PROPERTIES"]['BLINK_TEXT']['~VALUE'])):?>
                                    <div class="comment"><?=$arLink["PROPERTIES"]['BLINK_TEXT']['~VALUE']["TEXT"]?></div>
                                <?endif;?>
                            </div>

                            <?if( strlen($arLink["PROPERTIES"]['BLINK_NAME']['VALUE'])>0 ):?>

                                <div class="button">

                                    <a 
                                        title ="<?=CPhoenix::prepareText($arLink["PROPERTIES"]['BLINK_NAME']['VALUE'])?>"


                                        <?
                                            if(strlen($arLink["PROPERTIES"]["BLINK_ONCLICK"]["VALUE"])>0) 
                                            {
                                                $str_onclick = str_replace("'", "\"", $arLink["PROPERTIES"]["BLINK_ONCLICK"]["VALUE"]);

                                                echo "onclick='".$str_onclick."'";

                                                $str_onclick = "";
                                            }
                                        ?> 

                                        class="

                                        button-def <?=$b_options["MAIN_COLOR"]?> <?=$btn_view?> <?=CPhoenix::buttonEditClass($arClass)?>

                                        <?if($arLink["PROPERTIES"]["BLINK_BUTTON_VIEW"]["VALUE_XML_ID"] == "" || $arLink["PROPERTIES"]["BLINK_BUTTON_VIEW"]["VALUE_XML_ID"] == "solid"):?> main-color <?elseif($arLink["PROPERTIES"]["BLINK_BUTTON_VIEW"]["VALUE_XML_ID"] == "shine"):?> shine main-color <?else:?> secondary <?endif;?>

                                        " 
                                        <?if(strlen($b_options["STYLE"])):?>
                                            style = "<?=$b_options["STYLE"]?>"
                                        <?endif;?>

                                        <?=CPhoenix::buttonEditAttr($arAttr)?>
                                    >
    
                                        <?=$arLink["PROPERTIES"]['BLINK_NAME']['~VALUE']?>
                                         

                                    </a>

                                    <?if($arLink["PROPERTIES"]["BLINK_BTN_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

                                        <a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL"]?>" 
                                            class="button-def <?=$btn_view?> 

                                            common-btn-basket-style-added btn-added"

                                            >
                                            <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];?>
                                        </a>

                                    <?endif;?>
                                </div>
                            <?endif;?>

                        </div>
                        <?CPhoenix::admin_setting($arLink, false)?>
                        
                    </div>
                </div>

            <?endforeach;?>
        </div>


    </div>

<?endif;?>

<?if( $arItem["PROPERTIES"]["BLINK_VIEW"]["VALUE_XML_ID"] == "banner" ):?>

    <?if( $arItem["PROPERTIES"]["BLINK_WIDTH_FULL"]["VALUE"] == "Y" && !$show_menu):?>
            
        </div> <!-- close from banner container  -->

    <?endif;?>

    <?
        $b_options = array(
            "MAIN_COLOR" => "",
            "STYLE" => ""
        );

        if(strlen($arItem["PROPERTIES"]["BLINK_BUTTON_BG_COLOR"]["VALUE"]))
        {

            $b_options = array(
                "MAIN_COLOR" => "btn-bgcolor-custom",
                "STYLE" => "background-color: ".$arItem["PROPERTIES"]["BLINK_BUTTON_BG_COLOR"]["VALUE"].";"
            );

        }
    ?>

    <div class="banner">
        
        <?
            $bg = '';

            if(strlen($arItem["PROPERTIES"]['BLINK_BACKGROUND']['VALUE'])>0)
                $bg = CFile::ResizeImageGet($arItem["PROPERTIES"]['BLINK_BACKGROUND']['VALUE'], array('width'=>1600, 'height'=>900), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
        ?>

        <div class="element lazyload" data-src="<?=$bg["src"]?>">

            
            <div <?if( $arItem["PROPERTIES"]["BLINK_WIDTH_FULL"]["VALUE"] == "Y" && !$show_menu):?> class="container"<?endif;?>>

                <div <?if( $arItem["PROPERTIES"]["BLINK_WIDTH_FULL"]["VALUE"] == "Y" && !$show_menu):?> class="ex-row"<?endif;?>>

                    <?

                        $text_ini = false;
                        $img_ini = false;
                        $btn_ini = false;

                        if(strlen($arItem["PROPERTIES"]['BLINK_TITLE']['VALUE'])>0 || !empty($arItem["PROPERTIES"]['BLINK_TEXT']['VALUE'])>0)
                            $text_ini = true;

                        if(strlen($arItem["PROPERTIES"]['BLINK_BTN_TYPE']['VALUE_XML_ID'])>0 && strlen($arItem["PROPERTIES"]['BLINK_NAME']['VALUE'])>0)
                            $btn_ini = true;

                        if(strlen($arItem["PROPERTIES"]['BLINK_PICTURE']['VALUE'])>0)
                            $img_ini = true;


                        if($show_menu)
                        {

                            $col_btn = 'd-none';
                            $col_img = 'd-none';
                            $col_text = 'd-none';

                            if($text_ini && $btn_ini && $img_ini )
                            {
                                $col_text = 'col-xl-7 col-lg-6 col-md-9 col-12';
                                $col_btn = 'col-xl-2 col-md-3 col-12';
                                $col_img = 'col-lg-3 col-md-3 col-12';
                            }
                            if($text_ini && !$btn_ini && $img_ini )
                            {
                                $col_text = 'col-md-9 col-12';
                                $col_img = 'col-md-3 col-12';
                            }
                            if($text_ini && $btn_ini && !$img_ini )
                            {
                                $col_text = 'col-lg-9 col-md-8 col-12';
                                $col_btn = 'col-lg-3 col-md-4 col-12';
                            }
                            if(!$text_ini && $btn_ini && $img_ini )
                            {
                                $col_btn = 'col-md-6 col-12';
                                $col_img = 'col-md-6 col-12';
                            }

                            if($text_ini && !$btn_ini && !$img_ini )
                                $col_text = 'col-12';

                            if(!$text_ini && !$btn_ini && $img_ini )
                                $col_img = 'col-12';
                            
                            if(!$text_ini && $btn_ini && !$img_ini )
                                $col_btn = 'col-12';

                        }
                        else
                        {
                            
                            $col_btn = 'd-none';
                            $col_img = 'd-none';
                            $col_text = 'd-none';

                            if($text_ini && $btn_ini && $img_ini )
                            {
                                $col_text = 'col-xl-7 col-lg-6 col-md-9 col-12';
                                $col_btn = 'col-xl-3 col-md-3 col-12';
                                $col_img = 'col-xl-2 col-md-3 col-12';
                            }
                            if($text_ini && !$btn_ini && $img_ini )
                            {
                                $col_text = 'col-md-9 col-12';
                                $col_img = 'col-md-3 col-12';
                            }
                            if($text_ini && $btn_ini && !$img_ini )
                            {
                                $col_text = 'col-lg-9 col-md-8 col-12';
                                $col_btn = 'col-lg-3 col-md-4 col-12';
                            }
                            if(!$text_ini && $btn_ini && $img_ini )
                            {
                                $col_btn = 'col-md-6 col-12';
                                $col_img = 'col-md-6 col-12';
                            }

                            if($text_ini && !$btn_ini && !$img_ini )
                                $col_text = 'col-12';

                            if(!$text_ini && !$btn_ini && $img_ini )
                                $col_img = 'col-12';
                            
                            if(!$text_ini && $btn_ini && !$img_ini )
                                $col_btn = 'col-12';
                            
                        }
                        


                        if(!strlen($arItem["PROPERTIES"]['BLINK_POSITION']['VALUE_XML_ID']))
                            $arItem["PROPERTIES"]['BLINK_POSITION']['VALUE_XML_ID'] = "view1";


                        $arrPoistionBlocks = Array();
                        $arrPoistionBlocks["view1"] = array(
                            "ORDER_TEXT" => "order-lg-1",
                            "ORDER_BTN" => "order-lg-2",
                            "ORDER_IMG" => "order-lg-3",
                        );
                        $arrPoistionBlocks["view2"] = array(
                            "ORDER_TEXT" => "order-lg-2",
                            "ORDER_BTN" => "order-lg-1",
                            "ORDER_IMG" => "order-lg-3",
                        );
                        $arrPoistionBlocks["view3"] = array(
                            "ORDER_TEXT" => "order-lg-3",
                            "ORDER_BTN" => "order-lg-2",
                            "ORDER_IMG" => "order-lg-1",
                        );
                        $arrPoistionBlocks["view4"] = array(
                            "ORDER_TEXT" => "order-lg-2",
                            "ORDER_BTN" => "order-lg-3",
                            "ORDER_IMG" => "order-lg-1",
                        );



                        if(strlen($arItem["PROPERTIES"]['BLINK_BTN_TYPE']['VALUE_XML_ID'])>0)
                        {
                            $form_id = "";
                            if($arItem["PROPERTIES"]["BLINK_BUTTON_FORM"]["VALUE"] > 0)
                                $form_id = $arItem["PROPERTIES"]["BLINK_BUTTON_FORM"]["VALUE"];

                            $product_id = "";

                            if($arItem["PROPERTIES"]["BLINK_OFFER_ID"]["VALUE"])
                                $product_id = $arItem["PROPERTIES"]["BLINK_OFFER_ID"]["VALUE"];

                            else if($arItem["PROPERTIES"]["BLINK_CATALOG_ID"]["VALUE"])
                                $product_id = $arItem["PROPERTIES"]["BLINK_CATALOG_ID"]["VALUE"];

                            $arClass = array();
                            $arClass=array(
                                "XML_ID"=> $arItem["PROPERTIES"]["BLINK_BTN_TYPE"]["VALUE_XML_ID"],
                                "FORM_ID"=> $form_id,
                                "MODAL_ID"=> $arItem["PROPERTIES"]["BLINK_BUTTON_MODAL"]["VALUE"],
                                "QUIZ_ID"=> $arItem["PROPERTIES"]["BLINK_BUTTON_QUIZ"]["VALUE"],
                                "FAST_ORDER_PRODUCT_ID" => $product_id,
                                "ADD2CART_PRODUCT_ID" => $product_id
                            );

                            $arAttr=array();
                            $arAttr=array(
                                "XML_ID"=> $arItem["PROPERTIES"]["BLINK_BTN_TYPE"]["VALUE_XML_ID"],
                                "FORM_ID"=> $form_id,
                                "MODAL_ID"=> $arItem["PROPERTIES"]["BLINK_BUTTON_MODAL"]["VALUE"],
                                "LINK"=> $arItem["PROPERTIES"]["BLINK_BUTTON_LINK"]["VALUE"],
                                "BLANK"=> $arItem["PROPERTIES"]["BLINK_BUTTON_BLANK"]["VALUE_XML_ID"],
                                "HEADER"=> $block_name,
                                "QUIZ_ID"=> $arItem["PROPERTIES"]["BLINK_BUTTON_QUIZ"]["VALUE"],
                                "LAND_ID"=> $arItem["PROPERTIES"]["BLINK_BTN_LAND"]["VALUE"],
                                "FAST_ORDER_PRODUCT_ID" => $product_id,
                                "ADD2CART_PRODUCT_ID" => $product_id
                            );
                        }

                        $class_anim_text = "";
                        $class_anim_btn = "";
                        $class_anim_img = "";
                        if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y")
                        {
                            $class_anim_text = "wow zoomIn";
                            $class_anim_btn = "wow bounceIn";
                            $class_anim_img = "wow fadeIn";
                        }
                    ?>
                  

                    <div class="part-wrap <?=$arItem["PROPERTIES"]['BLINK_TEXT_COLOR']['VALUE_XML_ID']?> row align-items-center no-margin">

                        <?if($text_ini):?>

                            <div class="part text <?=$arrPoistionBlocks[$arItem["PROPERTIES"]['BLINK_POSITION']['VALUE_XML_ID']]["ORDER_TEXT"]?> <?=$col_text?> <?=$class_anim_text?> order-md-1 order-2" data-wow-delay="0.5s">

                                <div class="text bold"><?=$arItem["PROPERTIES"]['BLINK_TITLE']['~VALUE']?></div>
                                <div class="desc"><?=$arItem["PROPERTIES"]['BLINK_TEXT']['~VALUE']["TEXT"]?></div>

                            </div>

                        <?endif;?>

                        <?if($btn_ini):?>

                            <div class="part button <?=$arrPoistionBlocks[$arItem["PROPERTIES"]['BLINK_POSITION']['VALUE_XML_ID']]["ORDER_BTN"]?> <?=$col_btn?> <?=$class_anim_btn?> order-3" data-wow-delay="1s">

                                <a 
                                    title ="<?=CPhoenix::prepareText($arItem["PROPERTIES"]['BLINK_NAME']['VALUE'])?>"


                                    <?
                                        if(strlen($arItem["PROPERTIES"]["BLINK_ONCLICK"]["VALUE"])>0) 
                                        {
                                            $str_onclick = str_replace("'", "\"", $arItem["PROPERTIES"]["BLINK_ONCLICK"]["VALUE"]);

                                            echo "onclick='".$str_onclick."'";

                                            $str_onclick = "";
                                        }
                                    ?> 

                                    class="
                                    button-def <?=$b_options["MAIN_COLOR"]?> <?=$btn_view?> <?=CPhoenix::buttonEditClass($arClass)?>

                                    <?if($arItem["PROPERTIES"]["BLINK_BUTTON_VIEW"]["VALUE_XML_ID"] == "" || $arItem["PROPERTIES"]["BLINK_BUTTON_VIEW"]["VALUE_XML_ID"] == "solid"):?> main-color <?elseif($arItem["PROPERTIES"]["BLINK_BUTTON_VIEW"]["VALUE_XML_ID"] == "shine"):?> shine main-color <?else:?> secondary <?endif;?>

                                    " 

                                    <?if(strlen($b_options["STYLE"])):?>
                                        style = "<?=$b_options["STYLE"]?>"
                                    <?endif;?>

                                    <?=CPhoenix::buttonEditAttr($arAttr)?>>

                                    <?=$arItem["PROPERTIES"]['BLINK_NAME']['~VALUE']?>

                                </a>

                                <?if($arItem["PROPERTIES"]["BLINK_BTN_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

                                    <a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL"]?>" 
                                        class="button-def <?=$btn_view?> 

                                        common-btn-basket-style-added btn-added"

                                        >
                                        <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];?>
                                    </a>

                                <?endif;?>
                            </div>

                        <?endif;?>

                        <?if($img_ini):?>

                            <div class="part image <?=$arrPoistionBlocks[$arItem["PROPERTIES"]['BLINK_POSITION']['VALUE_XML_ID']]["ORDER_IMG"]?> <?=$col_img?> align-self-end <?=$class_anim_img?> order-md-2 order-1">
                                <?$img = CFile::ResizeImageGet($arItem["PROPERTIES"]['BLINK_PICTURE']['VALUE'], array('width'=>300, 'height'=>1000), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                                <img data-src="<?=$img['src']?>" class="lazyload" alt="<?=(strlen($arItem["PROPERTIES"]["BLINK_PICTURE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["BLINK_PICTURE"]["DESCRIPTION"]:"";?>">
                            
                            </div>

                        <?endif;?>

                    </div>

                    

                    <?if($arItem["PROPERTIES"]['BLINK_LINK_BLOCK']['VALUE'] == 'Y' && strlen($arItem["PROPERTIES"]['BLINK_BTN_TYPE']['VALUE_XML_ID'])>0):?>

                        <a 

                            <?
                                if(strlen($arItem["PROPERTIES"]["BLINK_ONCLICK"]["VALUE"])>0) 
                                {
                                    $str_onclick = str_replace("'", "\"", $arItem["PROPERTIES"]["BLINK_ONCLICK"]["VALUE"]);

                                    echo "onclick='".$str_onclick."'";

                                    $str_onclick = "";
                                }
                            ?>
                        
                            class="wrap-link <?=CPhoenix::buttonEditClass($arClass)?>" <?=CPhoenix::buttonEditAttr($arAttr)?>>
                            

                        </a>

                        <?if($arItem["PROPERTIES"]["BLINK_BTN_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

                            <a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL"]?>" 
                                class="wrap-link common-btn-basket-style-added">
                            </a>

                        <?endif;?>

                    <?endif;?>

                </div>

            </div>


        </div>
    
     
      
    </div>

    <?if( $arItem["PROPERTIES"]["BLINK_WIDTH_FULL"]["VALUE"] == "Y" && !$show_menu ):?>

        <div class="container"><!-- open from banner container  -->
            

    <?endif;?>


<?endif;?>