<?if(!empty($arItem["PROPERTIES"]["EMPL"]["VALUE"])):?>

    <?if($arItem["PROPERTIES"]["EMPL_VIEW"]["VALUE_XML_ID"] == "full"):?>

        <div class="empl-parent">
            <?$APPLICATION->IncludeComponent(
                "concept:phoenix.news-list",
                "empl",
                Array(
                    "COMPOSITE_FRAME_MODE" => "N",
                    "COUNT" => "",
                    "ELEMENTS_ID" => $arItem["PROPERTIES"]["EMPL"]["VALUE"],
                    "VIEW" => "full",
                    "H_POSITION_IMAGE_BLOCK" => $arItem["PROPERTIES"]["EMPL_PICTURE_POSITION"]["VALUE_XML_ID"],
                    "SIDEMENU" => $show_menu,
                    "BLOCK_TITLE" => $block_name,
                    "SORT_BY1" => "SORT",
                    "SORT_ORDER1" =>"ASC",
                )
            );?>
        </div>

    <?else:?>
        
        <?$APPLICATION->IncludeComponent(
            "concept:phoenix.news-list",
            "empl",
            Array(
                "COMPOSITE_FRAME_MODE" => "N",
                "COUNT" => "",
                "ELEMENTS_ID" => $arItem["PROPERTIES"]["EMPL"]["VALUE"],
                "VIEW" => "flat",
                "ANIMATE" => $arItem["PROPERTIES"]["ANIMATE"]["VALUE"],
                "SIDEMENU" => $show_menu,
                "BLOCK_TITLE" => $block_name,
                "SORT_BY1" => "SORT",
                "SORT_ORDER1" => "ASC",
                "PICTURE_ROUND" => ($arItem["PROPERTIES"]["EMPL_PICTURES_ROUND"]["VALUE"]=="Y")? "pic-round":""
            )
        );?>
    <?endif;?>
<?endif;?>