<?	
    if(strlen($arItem["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"]) <= 0)
		$arItem["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"] = "form";


	if(strlen($arItem["PROPERTIES"]["BUTTON_TYPE_2"]["VALUE_XML_ID"]) <= 0)
		$arItem["PROPERTIES"]["BUTTON_TYPE_2"]["VALUE_XML_ID"] = "form";


    $block_name = $arItem['~NAME'];

    if(strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) > 0)
        $block_name .= " (".$arItem["PROPERTIES"]["HEADER"]["~VALUE"].")";

    $block_name = htmlspecialcharsEx(strip_tags(html_entity_decode($block_name)));

    $class_button = "";
    $class_button2 = "";


    $arItem["PROPERTIES"]["BUTTONS_ALIGN"]["VALUE_XML_ID"] = $arItem["PROPERTIES"]["BUTTONS_ALIGN"]["VALUE_XML_ID"] ? $arItem["PROPERTIES"]["BUTTONS_ALIGN"]["VALUE_XML_ID"] : 'txt-align-center';

    $arItem["PROPERTIES"]["BUTTONS_ALIGN_MOB"]["VALUE_XML_ID"] = $arItem["PROPERTIES"]["BUTTONS_ALIGN_MOB"]["VALUE_XML_ID"] ? $arItem["PROPERTIES"]["BUTTONS_ALIGN_MOB"]["VALUE_XML_ID"] : 'txt-align-center-mob';

    if(strlen($arItem["PROPERTIES"]["BUTTON_NAME"]["VALUE"]) > 0 && strlen($arItem["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"]) > 0)
        $class_button = "left-on";
    
    if(strlen($arItem["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"]) > 0 && strlen($arItem["PROPERTIES"]["BUTTON_TYPE_2"]["VALUE_XML_ID"]) > 0)
        $class_button2 = "right-on";
    
?>

<div class="main-button-wrap <?/*if(!$show_menu && $center):?>center<?endif;*/?> <?=$class_button?> <?=$class_button2?> <?=$arItem["PROPERTIES"]["BUTTONS_ALIGN"]["VALUE_XML_ID"]?>">

    <?if(strlen($class_button) > 0):?>

        <?
            if($arItem["PROPERTIES"]["BUTTON_FORM"]["VALUE"] > 0)
                $form_id = $arItem["PROPERTIES"]["BUTTON_FORM"]["VALUE"];

            $product_id = "";

            if($arItem["PROPERTIES"]["BUTTON_OFFER_ID"]["VALUE"])
                $product_id = $arItem["PROPERTIES"]["BUTTON_OFFER_ID"]["VALUE"];

            else if($arItem["PROPERTIES"]["BUTTON_CATALOG_ID"]["VALUE"])
                $product_id = $arItem["PROPERTIES"]["BUTTON_CATALOG_ID"]["VALUE"];


            $arClass = array();
            $arClass=array(
                "XML_ID"=> $arItem["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"],
                "FORM_ID"=> $form_id,
                "MODAL_ID"=> $arItem["PROPERTIES"]["BUTTON_MODAL"]["VALUE"],
                "QUIZ_ID"=> $arItem["PROPERTIES"]["BUTTON_QUIZ"]["VALUE"],
                "FAST_ORDER_PRODUCT_ID" => $product_id,
                "ADD2CART_PRODUCT_ID"=> $product_id
            );
            
            $arAttr=array();
            $arAttr=array(
                "XML_ID"=> $arItem["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"],
                "FORM_ID"=> $form_id,
                "MODAL_ID"=> $arItem["PROPERTIES"]["BUTTON_MODAL"]["VALUE"],
                "LINK"=> $arItem["PROPERTIES"]["BUTTON_LINK"]["VALUE"],
                "BLANK"=> $arItem["PROPERTIES"]["BUTTON_BLANK"]["VALUE_XML_ID"],
                "HEADER"=> $block_name,
                "QUIZ_ID"=> $arItem["PROPERTIES"]["BUTTON_QUIZ"]["VALUE"],
                "LAND_ID"=> $arItem["PROPERTIES"]["BUTTON_LAND"]["VALUE"],
                "FAST_ORDER_PRODUCT_ID" => $product_id,
                "ADD2CART_PRODUCT_ID" => $product_id
            );


            $b_left_options = array(
                "MAIN_COLOR" => "main-color",
                "STYLE" => ""
            );

            if(strlen($arItem["PROPERTIES"]["BUTTON_BG_COLOR"]["VALUE"]) && $arItem["PROPERTIES"]["BUTTON_VIEW"]["VALUE_XML_ID"] != "empty")
            {

                $b_left_options = array(
                    "MAIN_COLOR" => "btn-bgcolor-custom",
                    "STYLE" => "background-color: ".$arItem["PROPERTIES"]["BUTTON_BG_COLOR"]["VALUE"].";"
                );

            }

            $class_left_btn = "";

            if( $view == "view-first")
            {
               $class_left_btn .= "big button-def";

                if( $arItem["PROPERTIES"]["BUTTON_VIEW"]["VALUE_XML_ID"] == "empty" )
                    $class_left_btn .= " secondary";

                else if( $arItem["PROPERTIES"]["BUTTON_VIEW"]["VALUE_XML_ID"] == "shine" )
                    $class_left_btn .= " shine ".$b_left_options["MAIN_COLOR"];

                else
                    $class_left_btn .= " ".$b_left_options["MAIN_COLOR"];
            }

            if($view == "view-second")
                $class_left_btn .= "button-second";
        ?>

        
        <div class="wrapper-btn">

            <a 
                <?if($arItem["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"] != "add_to_cart"):?>
                    title="<?=strip_tags($arItem["PROPERTIES"]["BUTTON_NAME"]["~VALUE"])?>"
                <?endif;?>
                <?
                    if(strlen($arItem["PROPERTIES"]["BUTTON_ONCLICK"]["VALUE"])>0) 
                    {
                        $str_onclick = str_replace("'", "\"", $arItem["PROPERTIES"]["BUTTON_ONCLICK"]["VALUE"]);

                        echo "onclick='".$str_onclick."'";

                        $str_onclick = "";
                    }
                ?>

                class="left <?=$class_left_btn?> <?=$btn_view?> <?=CPhoenix::buttonEditClass($arClass)?>"

                <?if(strlen($b_left_options["STYLE"])):?>
                    style = "<?=$b_left_options["STYLE"]?>"
                <?endif;?>

                <?=CPhoenix::buttonEditAttr($arAttr)?>
            >


                <?if($btn == "bord"):?><span class="bord-bot"><?endif;?>

                    <?=$arItem["PROPERTIES"]["BUTTON_NAME"]["~VALUE"]?>

                <?if($btn == "bord"):?></span><?endif;?>

            </a>


            <?if($arItem["PROPERTIES"]["BUTTON_TYPE"]["VALUE_XML_ID"] == "add_to_cart" && $product_id):?>
                

                    <a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL"]?>" 
                        class="button-def <?=$btn_view?> 

                        <?if($view == "view-first"):?>
                            big
                        <?endif;?>

                        common-btn-basket-style-added btn-added"

                        >
                        <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];?>
                    </a>

                
            <?endif;?>

        </div>

    <?endif;?>

    <?if(strlen($class_button2) > 0):?>

        <?

            $form_id = "";

            if($arItem["PROPERTIES"]["BUTTON_FORM_2"]["VALUE"] > 0)
                $form_id = $arItem["PROPERTIES"]["BUTTON_FORM_2"]["VALUE"];


           $product_id = "";

            if($arItem["PROPERTIES"]["BUTTON_2_OFFER_ID"]["VALUE"])
                $product_id = $arItem["PROPERTIES"]["BUTTON_2_OFFER_ID"]["VALUE"];

            else if($arItem["PROPERTIES"]["BUTTON_2_CATALOG_ID"]["VALUE"])
                $product_id = $arItem["PROPERTIES"]["BUTTON_2_CATALOG_ID"]["VALUE"];


            $arClass = array();
            $arClass=array(
                "XML_ID"=> $arItem["PROPERTIES"]["BUTTON_TYPE_2"]["VALUE_XML_ID"],
                "FORM_ID"=> $form_id,
                "MODAL_ID"=> $arItem["PROPERTIES"]["BUTTON_MODAL_2"]["VALUE"],
                "QUIZ_ID"=> $arItem["PROPERTIES"]["BUTTON_QUIZ_2"]["VALUE"],
                "FAST_ORDER_PRODUCT_ID" => $product_id,
                "ADD2CART_PRODUCT_ID" => $product_id
            );
            
            $arAttr=array();
            $arAttr=array(
                "XML_ID"=> $arItem["PROPERTIES"]["BUTTON_TYPE_2"]["VALUE_XML_ID"],
                "FORM_ID"=> $form_id,
                "MODAL_ID"=> $arItem["PROPERTIES"]["BUTTON_MODAL_2"]["VALUE"],
                "LINK"=> $arItem["PROPERTIES"]["BUTTON_LINK_2"]["VALUE"],
                "BLANK"=> $arItem["PROPERTIES"]["BUTTON_BLANK_2"]["VALUE_XML_ID"],
                "HEADER"=> $block_name,
                "QUIZ_ID"=> $arItem["PROPERTIES"]["BUTTON_QUIZ_2"]["VALUE"],
                "LAND_ID"=> $arItem["PROPERTIES"]["BUTTON_LAND_2"]["VALUE"],
                "FAST_ORDER_PRODUCT_ID" => $product_id,
                "ADD2CART_PRODUCT_ID" => $product_id
            );


            $b_right_options = array(
                "MAIN_COLOR" => "main-color",
                "STYLE" => ""
            );

            if(strlen($arItem["PROPERTIES"]["BUTTON_2_BG_COLOR"]["VALUE"]) && $arItem["PROPERTIES"]["BUTTON_VIEW_2"]["VALUE_XML_ID"] != "empty")
            {

                $b_right_options = array(
                    "MAIN_COLOR" => "btn-bgcolor-custom",
                    "STYLE" => "background-color: ".$arItem["PROPERTIES"]["BUTTON_2_BG_COLOR"]["VALUE"].";"
                );

            }

            $class_right_btn = "";

            if( $view == "view-first")
            {
               $class_right_btn .= "big button-def";

                if( $arItem["PROPERTIES"]["BUTTON_VIEW_2"]["VALUE_XML_ID"] == "empty" )
                    $class_right_btn .= " secondary";

                else if( $arItem["PROPERTIES"]["BUTTON_VIEW_2"]["VALUE_XML_ID"] == "shine" )
                    $class_right_btn .= " shine ".$b_right_options["MAIN_COLOR"];

                else 
                    $class_right_btn .= " ".$b_right_options["MAIN_COLOR"];
            }

            if($view == "view-second")
                $class_right_btn .= "button-second";

            
        ?>

        <div class="wrapper-btn">

            <a 

                <?if($arItem["PROPERTIES"]["BUTTON_TYPE_2"]["VALUE_XML_ID"] != "add_to_cart"):?>
                    title="<?=$arItem["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"]?>"
                <?endif;?>
                <?
                    if(strlen($arItem["PROPERTIES"]["BUTTON_2_ONCLICK"]["VALUE"])>0) 
                    {
                        $str_onclick = str_replace("'", "\"", $arItem["PROPERTIES"]["BUTTON_2_ONCLICK"]["VALUE"]);

                        echo "onclick='".$str_onclick."'";

                        $str_onclick = "";
                    }
                ?>

                class="right <?=$class_right_btn?> <?=$btn_view?> <?=CPhoenix::buttonEditClass($arClass)?>"

                <?if(strlen($b_right_options["STYLE"])):?>
                    style = "<?=$b_right_options["STYLE"]?>"
                <?endif;?>

                <?=CPhoenix::buttonEditAttr($arAttr)?>>

           

                <?if($btn == "bord"):?><span class="bord-bot"><?endif;?>

                    <?=$arItem["PROPERTIES"]["BUTTON_NAME_2"]["~VALUE"]?>

                <?if($btn == "bord"):?></span><?endif;?>

              
                    
            </a>

            <?if($arItem["PROPERTIES"]["BUTTON_TYPE_2"]["VALUE_XML_ID"] == "add_to_cart" && $product_id):?>

                <a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL"]?>" 
                    class="button-def <?=$btn_view?> 
                    <?if($view == "view-first"):?>
                        big
                    <?endif;?>
                    common-btn-basket-style-added btn-added
                    ">
                    <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];?>
                </a>

            <?endif;?>

        </div>

    <?endif;?>
    
</div>