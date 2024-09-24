<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
global $PHOENIX_TEMPLATE_ARRAY;
?>


<?if(!empty($arResult)):?>
    <?
        $terminations = Array();

        $terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_1"];
        $terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_2"];
        $terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_3"];
        $terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_4"];



        
        $styleBg = '';


        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_COLOR"]['VALUE']) >= 4)
        {

            $arColor = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_COLOR"]['VALUE'];
            $percent = 1;

            if($arColor != '#')
            {

                if(preg_match('/^\#/', $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_COLOR"]['VALUE']))
                {
                    $arColor = CPhoenix::convertHex2rgb($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_COLOR"]['VALUE']);
                    $arColor = implode(',',$arColor);
                }

                if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_OPACITY"]['VALUE'])>0)
                    $percent = (100 - $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_OPACITY"]['VALUE'])/100;
                

                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]['VALUE'] == "on_board")
                    $styleBg= 'style="background-color: rgba('.$arColor.', '.$percent.');"';

                if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]['VALUE'] == "on_line")
                    $styleBg= 'style="border-bottom: 2px solid rgba('.$arColor.', '.$percent.');"';
            }
            
        }
    ?>


    <div 

        class=
        "
            wrap-main-menu 
            active 
            <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TEXT_COLOR"]['VALUE']?> 
            <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["DROPDOWN_MENU_WIDTH"]['VALUE']?>

        "

        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_VIEW"]['VALUE'] == "full"):?> 

            <?=$styleBg?>

        <?endif;?>

    >
        <div class="container <?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["DROPDOWN_MENU_WIDTH"]['VALUE'] == "full")?"pos-static":""?>">

            <div class="main-menu-inner parent-tool-settings"

                <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_VIEW"]['VALUE'] == "content"):?> 
                    <?=$styleBg?>
                <?endif;?>
            >


                <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"] && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MODE_FAST_EDIT"]['VALUE']["ACTIVE"] == 'Y'):?>

                    <div class="tool-settings">

                        <a 
                            href='/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["IBLOCK_ID"]?>&type=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["IBLOCK_TYPE"]?>&lang=ru&find_section_section=0' 
                            class="tool-settings <?if($center):?>in-center<?endif;?>"
                            data-toggle="tooltip"
                            target="_blank"
                            data-placement="right"
                            title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["EDIT"]?> &quot;<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["NAME"]?>&quot;"
                        >
                                
                        </a>

                    </div>

                <?endif;?>



                <table class="main-menu-board">
                    <tr>

                        <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['SHOW_IN']['VALUE']['IN_MENU'] == "Y" ):?>

                            <td class="wrapper-search">
                                <div class="mini-search-style open-search-top"></div>
                            </td>
                        <?endif;?>


                        <td class="wrapper-menu">
                            
                            <nav class="main-menu">

                                <?foreach($arResult as $arItem):?>

                                    <?

                                        $colorText = '';
                                        $icon = '';

                                        if(strlen($arItem['MENU_COLOR'])>0)
                                            $colorText = ' style="color: '.$arItem['MENU_COLOR'].';"';
                                        

                                        if($arItem['MENU_IC_US']>0)
                                        {
                                            $iconResize = CFile::ResizeImageGet($arItem['MENU_IC_US'], array('width'=>15, 'height'=>15), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                            $icon = '<img class="img-fluid img-icon lazyload" data-src="'.$iconResize['src'].'" alt="'.CPhoenix::prepareText($arItem['NAME']).'" />';
                                        }

                                        elseif(strlen($arItem['MENU_ICON'])>0)
                                            $icon = '<i class="concept-icon '.$arItem['MENU_ICON'].'"></i>';
                                        
                                    ?>


                                    <li class=

                                        "
                                            lvl1 
                                            <?=$arItem["VIEW"]?>
                                            <?/*if($arItem["SELECTED"]):?>selected<?endif;*/?>
                                           
                                            <?if(strlen($arItem["ID"])):?>section-menu-id-<?=$arItem["ID"]?><?endif;?>
                                            <?if(!empty($arItem["SUB"])):?>parent<?endif;?>
                                        ">


                                        <a 

                                            <?if($arItem['NOLINK']):?>

                                                <?=CPhoenix::phoenixMenuAttr($arItem, $arItem['TYPE'])?>

                                            <?else:?>

                                                <?if(strlen($arItem["LINK"]) > 0  && !$arItem["NONE"]):?> 

                                                    href='<?=$arItem['LINK']?>'


                                                    <?if($arItem['BLANK']):?>

                                                        target='_blank'

                                                    <?endif;?>

                                                <?endif;?>

                                            <?endif;?>

                                            class=
                                            "

                                                <?if(strlen($arItem["LINK"]) <= 0 && $arItem["NONE"]):?>
                                                    empty-link
                                                <?endif;?>
                                            
                                                <?if($arItem['NOLINK']):?>
                                                    <?=CPhoenix::phoenixMenuClass($arItem, $arItem['TYPE'])?>
                                                <?endif;?>

                                                <?if($arItem["ACCENTED"]):?>bold<?endif;?>

                                            " 

                                            <?=$colorText?> 

                                        >

                                            <span class="wrap-name">
                                                <span class="

                                                    <?/*if($arItem["ACCENTED"]):?>
                                                        bold
                                                    <?endif;*/?>

                                                ">
                                                    <?=$icon?><?=$arItem['NAME']?>
                                                    <div class="bord"></div>
                                                </span>
                                            </span>

                                        </a>


                                        <?if(!empty($arItem["SUB"])):?>

                                            <?if($arItem["VIEW"] == "view_1"):?>

                                                <ul class="child">

                                                    <li class="wrap-shadow"></li>

                                                    <?if(!empty($arItem["SUB"])):?>

                                                        <?foreach($arItem["SUB"] as $arMenuChild):?>

                                                            <li class="

                                                                <?/*if($arMenuChild["SELECTED"]):?>selected<?endif;*/?>

                                                                <?if(strlen($arMenuChild["ID"])):?>

                                                                    section-menu-id-<?=$arMenuChild["ID"]?>

                                                                <?endif;?>

                                                                <?if(!empty($arMenuChild['SUB'])):?>parent2<?endif;?>

                                                            ">

                                                                <a 

                                                                    <?if($arMenuChild['NOLINK']):?>

                                                                        <?=CPhoenix::phoenixMenuAttr($arMenuChild, $arMenuChild['TYPE'])?>

                                                                    <?else:?>

                                                                        <?if(strlen($arMenuChild["LINK"]) > 0 && !$arMenuChild["NONE"]):?> 

                                                                            href='<?=$arMenuChild['LINK']?>'

                                                                            <?if($arMenuChild['BLANK']):?>

                                                                                target='_blank'

                                                                            <?endif;?>

                                                                        <?endif;?>

                                                                    <?endif;?>


                                                                    class=
                                                                    "

                                                                        <?if(strlen($arMenuChild["LINK"]) <= 0  && $arMenuChild["NONE"]):?>empty-link<?endif;?>

                                                                        

                                                                        <?if($arMenuChild['NOLINK']):?>

                                                                            <?=CPhoenix::phoenixMenuClass($arMenuChild, $arMenuChild['TYPE'])?>

                                                                        <?endif;?>

                                                                    "
                                                                >
                                                                    <?=$arMenuChild['NAME']?><div></div> <span class="act"></span>

                                                                </a> 

                                                               

                                                                <?if( !empty($arMenuChild['SUB']) ):?>
                                                                
                                                                    <ul class="child2">

                                                                        <li class="wrap-shadow"></li>

                                                                        <?foreach($arMenuChild['SUB'] as $keyChild2 => $arMenuChild2):?>

                                                                            <li class="

                                                                                <?/*if($arMenuChild2["SELECTED"]):?>selected<?endif;*/?>

                                                                                <?if(strlen($arMenuChild2["ID"])):?>

                                                                                    section-menu-id-<?=$arMenuChild2["ID"]?>

                                                                                <?endif;?>
                                                                            ">

                                                                                <a 
                                                                                    

                                                                                    <?if($arMenuChild2['NOLINK']):?>

                                                                                        <?=CPhoenix::phoenixMenuAttr ($arMenuChild2, $arMenuChild2['TYPE'])?>

                                                                                    <?else:?>

                                                                                        <?if(strlen($arMenuChild2["LINK"]) > 0 && !$arMenuChild2["NONE"]):?> 
                                                                                        
                                                                                            href='<?=$arMenuChild2['LINK']?>'

                                                                                            <?if($arMenuChild2['BLANK']):?>

                                                                                                target='_blank'

                                                                                            <?endif;?>

                                                                                        <?endif;?>

                                                                                    <?endif;?>

                                                                                    class=
                                                                                    "
                                                                                        <?if(strlen($arMenuChild2["LINK"]) <= 0 && $arMenuChild2["NONE"]):?>empty-link<?endif;?>

                                                                                        

                                                                                        <?if($arMenuChild2['NOLINK']):?>

                                                                                            <?=CPhoenix::phoenixMenuClass($arMenuChild2, $arMenuChild2['TYPE'])?>

                                                                                        <?endif;?>
                                                                                    "
                                                                                >

                                                                                    <?=$arMenuChild2['NAME']?>
                                                                                    <div></div>
                                                                                    <span class="act"></span>

                                                                                </a>
                                                                            </li>


                                                                        <?endforeach;?>
                                                             
                                                                    </ul>

                                                                <?endif;?>

                                                            </li>

                                                        <?endforeach;?>

                                                    <?endif;?>

                                                </ul>



                                            <?elseif($arItem["VIEW"] == "view_2"):?>

                                                <?if(!empty($arItem["SUB"])):?>

                                                    <div class="dropdown-menu-view-2 dropdown-menu-view-2-<?=$arItem['MENU_LVLS_VALUE']?>-js">

                                                        <div class="container">

                                                            <div class="inner">

                                                                <?if($arItem["TOP_TEXT"]):?>
                                                                    <div class="text-content top-text <?=$arItem["EXTRA_MARGIN"]?>"><?=$arItem["TOP_TEXT"]?></div>
                                                                <?endif;?>

                                                                <div class="row">

                                                                    <?if($arItem['MENU_LVLS_VALUE'] === 'lvls_4'):?>
                                                                        
                                                                        <div class="col-lg-3 col-md-5 col-12 sub-menu-lvl-2">

                                                                            <div class="inner-sub-menu-lvl-2">

                                                                                <?$i=0;?>

                                                                                <?foreach($arItem["SUB"] as $arMenuChild):?>

                                                                                    <a 

                                                                                        <?if($arMenuChild['NOLINK']):?>
                                                                                            <?=CPhoenix::phoenixMenuAttr($arMenuChild, $arMenuChild['TYPE'])?>
                                                                                        <?else:?>
                                                                                            <?if(strlen($arMenuChild["LINK"]) > 0  && !$arMenuChild["NONE"]):?> 

                                                                                                href='<?=$arMenuChild['LINK']?>'


                                                                                                <?if($arMenuChild['BLANK']):?>

                                                                                                    target='_blank'

                                                                                                <?endif;?>

                                                                                            <?endif;?>
                                                                                        <?endif;?>

                                                                                        data-sub-menu-lvl-2="<?=$arMenuChild['ID']?>" 


                                                                                        class="
                                                                                                row 
                                                                                                align-items-center
                                                                                                no-gutters
                                                                                                sub-menu-lvl-2-item
                                                                                                sub-menu-lvl-2-item-js
                                                                                                main-color-border-active
                                                                                                main-color-border-hover

                                                                                                <?if(strlen($arMenuChild["ID"])):?>
                                                                                                    section-menu-id-<?=$arMenuChild["ID"]?>
                                                                                                <?endif;?>

                                                                                                <?if(strlen($arMenuChild["LINK"]) <= 0 && $arMenuChild["NONE"]):?>
                                                                                                    empty-link
                                                                                                <?endif;?>

                                                                                                <?if($arMenuChild['NOLINK']):?>
                                                                                                    <?=CPhoenix::phoenixMenuClass($arMenuChild, $arMenuChild['TYPE'])?>
                                                                                                <?endif;?>

                                                                                                <?if( !empty($arMenuChild['SUB']) ):?>isset-sub<?endif;?>"

                                                                                        >

                                                                                     

                                                                                        <div class="col-auto">
                                                                                            <div class="wr-main-sub-img row no-gutters align-items-center">
                                                                                                <div class="col-12">
                                                                                            
                                                                                                    <?if(isset($arMenuChild["PICTURE_SRC"])):?>
                                                                                                            
                                                                                                        <img data-src="<?=$arMenuChild["PICTURE_SRC"]?>" class="lazyload img-fluid" alt="<?=CPhoenix::prepareText($arMenuChild['NAME'])?>">
                                                                                                        
                                                                                                    <?else:?>
                                                                                                    
                                                                                                        <span></span>
                                                                                                        
                                                                                                    <?endif;?>
                                                                                                </div>

                                                                                            </div>

                                                                                        </div>

                                                                                  

                                                                                        <div class="col">
                                                                                            
                                                                                            <div class="main-sub-name main-sub-name-js bold <?if(isset($arMenuChild["PICTURE_SRC"])):?>isset-pic<?endif;?>" title="<?=$arMenuChild["NAME"]?>">
                                                                                                <?=$arMenuChild['NAME']?>
                                                                                                    
                                                                                            </div>

                                                                                        </div>
                                                                                    </a>

                                                                                    <?$i++;?>

                                                                                <?endforeach;?>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-lg-9 col-md-7 col-12 sub-menu-lvl-3">

                                                                            <?$emptyMenu = array();?>
                                                                          

                                                                            <?foreach($arItem["SUB"] as $arMenuChild):?>

                                                                                <div class="row sub-menu-wr-lvl-3-item sub-menu-wr-lvl-3-item-js <?if(strlen($arMenuChild["ID"])):?>section-menu-id-<?=$arMenuChild["ID"]?><?endif;?>" data-sub-menu-lvl-2="<?=$arMenuChild['ID']?>">

                                                                                    <?
                                                                                        $colsItemsWrapper = "col-12";
                                                                                        $colsItems = "col-lg-4 col-md-6";
                                                                                        $colsItem = "col-lg-4 col-md-6";

                                                                                        $colsBannersWrapper = "d-none";

                                                                                        if(!empty($arMenuChild['BANNERS']) || !empty($arItem['BANNERS']))
                                                                                        {
                                                                                            $colsItemsWrapper = "col-lg-8 col-6";
                                                                                            $colsItems = "col-lg-6 col-12";
                                                                                            $colsBannersWrapper = "col-lg-4 col-6";
                                                                                        }
                                                                                    ?>


                                                                                    <div class="<?=$colsItemsWrapper?>">

                                                                                        <div class="row">

                                                                                            <?if($arMenuChild["TOP_TEXT"]):?>
                                                                                                <div class="text-content top-text col-12 <?=$arMenuChild["UF_EXTRA_MARGIN"]?>"><?=$arMenuChild["TOP_TEXT"]?></div>
                                                                                            <?endif;?>

                                                                                            <?if( !empty($arMenuChild['SUB']) ):?>


                                                                                            <?foreach($arMenuChild['SUB'] as $arMenuChild2):?>

                                                                                                <div class="<?=$colsItems?> col-12">
                               
                                                                                                    <table class="item">
                                                                                                        <tr>
                                                                                                            <td class="left">
                                                                                                            
                                                                                                                <?if(isset($arMenuChild2["PICTURE_SRC"])):?>
                                                                                                                    
                                                                                                                    <img data-src="<?=$arMenuChild2["PICTURE_SRC"]?>" class="lazyload img-fluid" alt="<?=CPhoenix::prepareText($arMenuChild2['NAME'])?>">
                                                                                                                    
                                                                                                                <?else:?>
                                                                                                                
                                                                                                                    <span></span>
                                                                                                                    
                                                                                                                <?endif;?>
                                                                                     
                                                                                                            </td>
                                                                                                            
                                                                                                            <td class="right <?if( !empty($arMenuChild2['SUB']) ):?>sub<?endif;?>">

                                                                                                                <a 

                                                                                                                    <?if($arMenuChild2['NOLINK']):?>

                                                                                                                        <?=CPhoenix::phoenixMenuAttr($arMenuChild2, $arMenuChild2['TYPE'])?>

                                                                                                                    <?else:?>

                                                                                                                        <?if(strlen($arMenuChild2["LINK"]) > 0  && !$arMenuChild2["NONE"]):?> 

                                                                                                                            href='<?=$arMenuChild2['LINK']?>'


                                                                                                                            <?if($arMenuChild2['BLANK']):?>

                                                                                                                                target='_blank'

                                                                                                                            <?endif;?>

                                                                                                                        <?endif;?>

                                                                                                                    <?endif;?>

                                                                                                                    class="name 

                                                                                                                    <?if(strlen($arMenuChild2["LINK"]) <= 0 && $arMenuChild2["NONE"]):?>
                                                                                                                        empty-link
                                                                                                                    <?endif;?>

                                                                                                                    <?if($arMenuChild2['NOLINK']):?>
                                                                                                                        <?=CPhoenix::phoenixMenuClass($arMenuChild2, $arMenuChild2['TYPE'])?>
                                                                                                                    <?endif;?>

                                                                                                                    <?if(strlen($arMenuChild2["ID"])):?>

                                                                                                                        section-menu-id-<?=$arMenuChild2["ID"]?>

                                                                                                                    <?endif;?>

                                                                                                                    <?/*if($arMenuChild2["SELECTED"]):?>selected<?endif;*/?>

                                                                                                                " 

                                                                                                                title="<?=$arMenuChild2["NAME"]?>">
                                                                                                                    <?=$arMenuChild2['NAME']?>
                                                                                                                        
                                                                                                                </a>

                                                                                                                <?if($arItem["TYPE"] == 'catalog' && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSECTIONS_HIDE_COUNT"]["VALUE"]["ACTIVE"] != "Y"):?>

                                                                                                                    <?if($arMenuChild2["ELEMENT_CNT"]!="0"):?>
                                                                                                                    
                                                                                                                        <div class="count-sect-elem"><?=$arMenuChild2['ELEMENT_CNT']?> <?=CPhoenix::getTermination($arMenuChild2["ELEMENT_CNT"], $terminations)?></div>
                                                                                                                    <?endif;?>

                                                                                                                <?endif;?>

                                                                                                                <?if( !empty($arMenuChild2['SUB']) ):?>

                                                                                                                    <ul class="lvl2 <?=(strlen($arMenuChild["LINK"])>0)?'':'show-hidden-parent'?>">

                                                                                                                        <?
                                                                                                                            $j = 1;

                                                                                                                            $breakCount = intval($arItem["MAX_QUANTITY_SECTION_SHOW"]);
                                                                                                                        ?>

                                                                                                                        <?foreach($arMenuChild2['SUB'] as $arMenuChild3):?>

                                                                                                                            

                                                                                                                            <li class=
                                                                                                                            "
                                                                                                                                <?=($j > $breakCount)?'show-hidden-child hidden':'';?>
                                                                                                                                
                                                                                                                                <?/*if($arMenuChild3['SELECTED']):?>selected<?endif;*/?>

                                                                                                                                <?if(strlen($arMenuChild3["ID"])):?>

                                                                                                                                    section-menu-id-<?=$arMenuChild3["ID"]?>

                                                                                                                                <?endif;?>


                                                                                                                            ">

                                                                                                                                <a 

                                                                                                                                    <?if($arMenuChild3['NOLINK']):?>

                                                                                                                                        <?=CPhoenix::phoenixMenuAttr($arMenuChild3, $arMenuChild3['TYPE'])?>

                                                                                                                                    <?else:?>

                                                                                                                                        <?if(strlen($arMenuChild3["LINK"]) > 0  && !$arMenuChild3["NONE"]):?> 

                                                                                                                                            href='<?=$arMenuChild3['LINK']?>'


                                                                                                                                            <?if($arMenuChild3['BLANK']):?>

                                                                                                                                                target='_blank'

                                                                                                                                            <?endif;?>

                                                                                                                                        <?endif;?>

                                                                                                                                    <?endif;?>


                                                                                                                                    title = "<?=$arMenuChild3['NAME']?>" 


                                                                                                                                    class=
                                                                                                                                        "

                                                                                                                                            <?if(strlen($arMenuChild3["LINK"]) <= 0 && $arMenuChild3["NONE"]):?>
                                                                                                                                                empty-link
                                                                                                                                            <?endif;?>

                                                                                                                                            <?if($arMenuChild3['NOLINK']):?>
                                                                                                                                                <?=CPhoenix::phoenixMenuClass($arMenuChild3, $arMenuChild3['TYPE'])?>
                                                                                                                                            <?endif;?>

                                                                                                                                        "
                                                                                                                                >

                                                                                                                                    <?=$arMenuChild3['NAME']?>

                                                                                                                                </a>
                                                                                                                            </li>

                                                                                                                            <?$j++;?>

                                                                                                                        <?endforeach;?>

                                                                                                                        <?if(count($arMenuChild2['SUB']) > $breakCount && $breakCount != 0):?>
                                                                                                                            <li class="last show-hidden-wrap">
                                                                                                                                <a <?if(strlen($arMenuChild2["LINK"])>0 && $arMenuChild["TYPE"] === 'catalog'):?>href="<?=$arMenuChild2["LINK"]?>"<?else:?>class='show-hidden'<?endif;?>>
                                                                                                                                    <span><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["LINK_SUBSECTIONS_BTN_NAME"]["~VALUE"];?></span>
                                                                                                                                </a>
                                                                                                                            </li>

                                                                                                                        <?endif;?>
                                                                                                                    </ul>

                                                                                                                <?endif;?>


                                                                                                            </td>
                                                                                                            
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                    
                                                                                                </div>

                                                                                            <?endforeach;?>

                                                                                            <?endif;?>


                                                                                            <?if($arMenuChild["BOTTOM_TEXT"]):?>
                                                                                                <div class="text-content bottom-text col-12"><?=$arMenuChild["BOTTOM_TEXT"]?></div>
                                                                                            <?endif;?>


                                                                                        </div>

                                                                                    </div>

                                                                                    <?if(!empty($arMenuChild['BANNERS']) || !empty($arItem['BANNERS'])):?>
                                                                                        <? $GLOBALS["arrBannersMenuFilter"]["ID"] = (!empty($arMenuChild['BANNERS']))?$arMenuChild['BANNERS']: $arItem['BANNERS'] ?>

                                                                                        <div class="<?=$colsBannersWrapper?>">

                                                                                            <? $APPLICATION->IncludeComponent(
                                                                                                "bitrix:news.list",
                                                                                                "banners-left",
                                                                                                array(
                                                                                                    "VIEW"=>"flat",
                                                                                                    "COMPONENT_TEMPLATE" => "banners-left",
                                                                                                    "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_TYPE"],
                                                                                                    "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_ID"],
                                                                                                    "NEWS_COUNT" => "20",
                                                                                                    "SORT_BY1" => "SORT",
                                                                                                    "SORT_ORDER1" => "ASC",
                                                                                                    "SORT_BY2" => "SORT",
                                                                                                    "SORT_ORDER2" => "ASC",
                                                                                                    "FILTER_NAME" => "arrBannersMenuFilter",
                                                                                                    "FIELD_CODE" => array(
                                                                                                        0 => "DETAIL_PICTURE",
                                                                                                        1 => "PREVIEW_PICTURE",
                                                                                                    ),
                                                                                                    "PROPERTY_CODE" => array(
                                                                                                        0 => "",
                                                                                                        1 => "BANNER_BTN_TYPE",
                                                                                                        2 => "BANNER_ACTION_ALL_WRAP",
                                                                                                        3 => "BANNER_USER_BG_COLOR",
                                                                                                        4 => "BANNER_UPTITLE",
                                                                                                        5 => "BANNER_BTN_NAME",
                                                                                                        6 => "BANNER_TITLE",
                                                                                                        7 => "BANNER_BTN_BLANK",
                                                                                                        8 => "BANNER_BORDER",
                                                                                                        9 => "BANNER_DESC",
                                                                                                        10 => "BANNER_TEXT",
                                                                                                        11 => "BANNER_LINK",
                                                                                                        12 => "BANNER_COLOR_TEXT",
                                                                                                        13 => "",
                                                                                                    ),
                                                                                                    "CHECK_DATES" => "Y",
                                                                                                    "DETAIL_URL" => "",
                                                                                                    "AJAX_MODE" => "N",
                                                                                                    "AJAX_OPTION_JUMP" => "N",
                                                                                                    "AJAX_OPTION_STYLE" => "Y",
                                                                                                    "AJAX_OPTION_HISTORY" => "N",
                                                                                                    "AJAX_OPTION_ADDITIONAL" => "",
                                                                                                    "CACHE_TYPE" => "A",
                                                                                                    "CACHE_TIME" => "36000000",
                                                                                                    "CACHE_FILTER" => "Y",
                                                                                                    "CACHE_GROUPS" => "Y",
                                                                                                    "PREVIEW_TRUNCATE_LEN" => "",
                                                                                                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                                                                                    "SET_TITLE" => "N",
                                                                                                    "SET_BROWSER_TITLE" => "N",
                                                                                                    "SET_META_KEYWORDS" => "N",
                                                                                                    "SET_META_DESCRIPTION" => "N",
                                                                                                    "SET_LAST_MODIFIED" => "N",
                                                                                                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                                                                                    "ADD_SECTIONS_CHAIN" => "N",
                                                                                                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                                                                                    "PARENT_SECTION" => "",
                                                                                                    "PARENT_SECTION_CODE" => "",
                                                                                                    "INCLUDE_SUBSECTIONS" => "N",
                                                                                                    "STRICT_SECTION_CHECK" => "N",
                                                                                                    "DISPLAY_DATE" => "N",
                                                                                                    "DISPLAY_NAME" => "N",
                                                                                                    "DISPLAY_PICTURE" => "N",
                                                                                                    "DISPLAY_PREVIEW_TEXT" => "N",
                                                                                                    "COMPOSITE_FRAME_MODE" => "N",
                                                                                                    "PAGER_TEMPLATE" => ".default",
                                                                                                    "DISPLAY_TOP_PAGER" => "N",
                                                                                                    "DISPLAY_BOTTOM_PAGER" => "N",
                                                                                                    "PAGER_TITLE" => "",
                                                                                                    "PAGER_SHOW_ALWAYS" => "N",
                                                                                                    "PAGER_DESC_NUMBERING" => "N",
                                                                                                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                                                                                    "PAGER_SHOW_ALL" => "N",
                                                                                                    "PAGER_BASE_LINK_ENABLE" => "N",
                                                                                                    "SET_STATUS_404" => "N",
                                                                                                    "SHOW_404" => "N",
                                                                                                    "MESSAGE_404" => "",
                                                                                                ),
                                                                                                $component
                                                                                            ); ?>
                                                                                            
                                                                                        </div>

                                                                                    <?endif;?>


                                                                                </div>


                                                                            <?endforeach;?>



                                                                        </div>

                                                                    <?else:?>

                                                                        <?
                                                                            $colsMenuItem = "col-xl-3 col-lg-4 col-md-6";
                                                                            $bannersIsset = (!empty($arMenuChild['BANNERS']) || !empty($arItem['BANNERS']));
                                                                        ?>

                                                                        <?if($bannersIsset):?>
                                                                            <?
                                                                                $colsMenuItem = "col-lg-4 col-md-6";
                                                                            ?>
                                                                           
                                                                            <div class="col-9">
                                                                                <div class="row">
                                                                        <?endif;?>

                                                                                <?foreach($arItem["SUB"] as $arMenuChild):?>

                                                                                    <div class="<?=$colsMenuItem?> col-12">
                                   
                                                                                        <table class="item">
                                                                                            <tr>
                                                                                                <td class="left">
                                                                                                
                                                                                                    <?if(isset($arMenuChild["PICTURE_SRC"])):?>
                                                                                                        
                                                                                                        <img data-src="<?=$arMenuChild["PICTURE_SRC"]?>" class="lazyload img-fluid" alt="<?=CPhoenix::prepareText($arMenuChild['NAME'])?>">
                                                                                                        
                                                                                                    <?else:?>
                                                                                                    
                                                                                                        <span></span>
                                                                                                        
                                                                                                    <?endif;?>
                                                                         
                                                                                                </td>
                                                                                                
                                                                                                <td class="right <?if( !empty($arMenuChild['SUB']) ):?>sub<?endif;?>">

                                                                                                    <a 

                                                                                                        <?if($arMenuChild['NOLINK']):?>

                                                                                                            <?=CPhoenix::phoenixMenuAttr($arMenuChild, $arMenuChild['TYPE'])?>

                                                                                                        <?else:?>

                                                                                                            <?if(strlen($arMenuChild["LINK"]) > 0  && !$arMenuChild["NONE"]):?> 

                                                                                                                href='<?=$arMenuChild['LINK']?>'


                                                                                                                <?if($arMenuChild['BLANK']):?>

                                                                                                                    target='_blank'

                                                                                                                <?endif;?>

                                                                                                            <?endif;?>

                                                                                                        <?endif;?>

                                                                                                        <?if(strlen($arMenuChild["LINK"])>0):?>href="<?=$arMenuChild["LINK"]?>"<?endif;?>
                                                                                                        title="<?=$arMenuChild["NAME"]?>"

                                                                                                        class="name 

                                                                                                        <?if(strlen($arMenuChild["LINK"]) <= 0 && $arMenuChild["NONE"]):?>
                                                                                                            empty-link
                                                                                                        <?endif;?>

                                                                                                        <?if($arMenuChild['NOLINK']):?>
                                                                                                            <?=CPhoenix::phoenixMenuClass($arMenuChild, $arMenuChild['TYPE'])?>
                                                                                                        <?endif;?>

                                                                                                        <?if(strlen($arMenuChild["ID"])):?>

                                                                                                            section-menu-id-<?=$arMenuChild["ID"]?>

                                                                                                        <?endif;?>

                                                                                                        <?/*if($arMenuChild["SELECTED"]):?>selected<?endif;*/?>

                                                                                                    "

                                                                                                    >
                                                                                                        <?=$arMenuChild['NAME']?>
                                                                                                            
                                                                                                    </a>

                                                                                                    <?if($arItem["TYPE"] == 'catalog' && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSECTIONS_HIDE_COUNT"]["VALUE"]["ACTIVE"] != "Y"):?>

                                                                                                        <?if($arMenuChild["ELEMENT_CNT"]!="0"):?>
                                                                                                        
                                                                                                            <div class="count-sect-elem"><?=$arMenuChild['ELEMENT_CNT']?> <?=CPhoenix::getTermination($arMenuChild["ELEMENT_CNT"], $terminations)?></div>
                                                                                                        <?endif;?>

                                                                                                    <?endif;?>

                                                                                                    <?if( !empty($arMenuChild['SUB']) ):?>

                                                                                                        <ul class="lvl2 <?=(strlen($arMenuChild["LINK"])>0)?'':'show-hidden-parent';?>">

                                                                                                            <?
                                                                                                                $j = 1;

                                                                                                                $breakCount = intval($arItem["MAX_QUANTITY_SECTION_SHOW"]);
                                                                                                            ?>

                                                                                                            <?foreach($arMenuChild['SUB'] as $arMenuChild2):?>

                                                                                                                <li class=
                                                                                                                "
                                                                                                                    <?=($j > $breakCount)?'show-hidden-child hidden':'';?>
                                                                                                                    <?/*if($arMenuChild2['SELECTED']):?>selected<?endif;*/?>

                                                                                                                    <?if(strlen($arMenuChild2["ID"])):?>

                                                                                                                        section-menu-id-<?=$arMenuChild2["ID"]?>

                                                                                                                    <?endif;?>


                                                                                                                ">

                                                                                                                    <a 

                                                                                                                        <?if($arMenuChild2['NOLINK']):?>

                                                                                                                            <?=CPhoenix::phoenixMenuAttr($arMenuChild2, $arMenuChild2['TYPE'])?>

                                                                                                                        <?else:?>

                                                                                                                            <?if(strlen($arMenuChild2["LINK"]) > 0  && !$arMenuChild2["NONE"]):?> 

                                                                                                                                href='<?=$arMenuChild2['LINK']?>'


                                                                                                                                <?if($arMenuChild2['BLANK']):?>

                                                                                                                                    target='_blank'

                                                                                                                                <?endif;?>

                                                                                                                            <?endif;?>

                                                                                                                        <?endif;?>
                                                                                                                        title = "<?=$arMenuChild2['NAME']?>" <?if(strlen($arMenuChild2['LINK'])>0):?>href='<?=$arMenuChild2['LINK']?>'<?endif;?>


                                                                                                                        class="
                                                                                                                            <?if(strlen($arMenuChild2["LINK"]) <= 0 && $arMenuChild2["NONE"]):?>
                                                                                                                                empty-link
                                                                                                                            <?endif;?>

                                                                                                                            <?if($arMenuChild2['NOLINK']):?>
                                                                                                                                <?=CPhoenix::phoenixMenuClass($arMenuChild2, $arMenuChild2['TYPE'])?>
                                                                                                                            <?endif;?>
                                                                                                                        "

                                                                                                                    >

                                                                                                                        <?=$arMenuChild2['NAME']?>

                                                                                                                    </a>
                                                                                                                </li>

                                                                                                                <?$j++;?>

                                                                                                            <?endforeach;?>

                                                                                                            <?if(count($arMenuChild['SUB']) > $breakCount && $breakCount != 0):?>
                                                                                                                <li class="last show-hidden-wrap">
                                                                                                                    <a <?if(strlen($arMenuChild["LINK"])>0):?>href="<?=$arMenuChild["LINK"]?>"<?else:?>class='show-hidden'<?endif;?>>
                                                                                                                        <span><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["LINK_SUBSECTIONS_BTN_NAME"]["~VALUE"];?></span>
                                                                                                                    </a>
                                                                                                                </li>

                                                                                                            <?endif;?>
                                                                                                        </ul>

                                                                                                    <?endif;?>


                                                                                                </td>
                                                                                                
                                                                                            </tr>
                                                                                        </table>
                                                                                        
                                                                                    </div>

                                                                                <?endforeach;?>

                                                                        <?if($bannersIsset):?>
                                                                                </div>
                                                                            </div>
                                                                           

                                                                                <? $GLOBALS["arrBannersMenuFilter"]["ID"] = (!empty($arMenuChild['BANNERS']))?$arMenuChild['BANNERS']: $arItem['BANNERS'] ?>

                                                                            <div class="col">

                                                                                <? $APPLICATION->IncludeComponent(
                                                                                    "bitrix:news.list",
                                                                                    "banners-left",
                                                                                    array(
                                                                                        "VIEW"=>"flat",
                                                                                        "COMPONENT_TEMPLATE" => "banners-left",
                                                                                        "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_TYPE"],
                                                                                        "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_ID"],
                                                                                        "NEWS_COUNT" => "20",
                                                                                        "SORT_BY1" => "SORT",
                                                                                        "SORT_ORDER1" => "ASC",
                                                                                        "SORT_BY2" => "SORT",
                                                                                        "SORT_ORDER2" => "ASC",
                                                                                        "FILTER_NAME" => "arrBannersMenuFilter",
                                                                                        "FIELD_CODE" => array(
                                                                                            0 => "DETAIL_PICTURE",
                                                                                            1 => "PREVIEW_PICTURE",
                                                                                        ),
                                                                                        "PROPERTY_CODE" => array(
                                                                                            0 => "",
                                                                                            1 => "BANNER_BTN_TYPE",
                                                                                            2 => "BANNER_ACTION_ALL_WRAP",
                                                                                            3 => "BANNER_USER_BG_COLOR",
                                                                                            4 => "BANNER_UPTITLE",
                                                                                            5 => "BANNER_BTN_NAME",
                                                                                            6 => "BANNER_TITLE",
                                                                                            7 => "BANNER_BTN_BLANK",
                                                                                            8 => "BANNER_BORDER",
                                                                                            9 => "BANNER_DESC",
                                                                                            10 => "BANNER_TEXT",
                                                                                            11 => "BANNER_LINK",
                                                                                            12 => "BANNER_COLOR_TEXT",
                                                                                            13 => "",
                                                                                        ),
                                                                                        "CHECK_DATES" => "Y",
                                                                                        "DETAIL_URL" => "",
                                                                                        "AJAX_MODE" => "N",
                                                                                        "AJAX_OPTION_JUMP" => "N",
                                                                                        "AJAX_OPTION_STYLE" => "Y",
                                                                                        "AJAX_OPTION_HISTORY" => "N",
                                                                                        "AJAX_OPTION_ADDITIONAL" => "",
                                                                                        "CACHE_TYPE" => "A",
                                                                                        "CACHE_TIME" => "36000000",
                                                                                        "CACHE_FILTER" => "Y",
                                                                                        "CACHE_GROUPS" => "Y",
                                                                                        "PREVIEW_TRUNCATE_LEN" => "",
                                                                                        "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                                                                        "SET_TITLE" => "N",
                                                                                        "SET_BROWSER_TITLE" => "N",
                                                                                        "SET_META_KEYWORDS" => "N",
                                                                                        "SET_META_DESCRIPTION" => "N",
                                                                                        "SET_LAST_MODIFIED" => "N",
                                                                                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                                                                        "ADD_SECTIONS_CHAIN" => "N",
                                                                                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                                                                        "PARENT_SECTION" => "",
                                                                                        "PARENT_SECTION_CODE" => "",
                                                                                        "INCLUDE_SUBSECTIONS" => "N",
                                                                                        "STRICT_SECTION_CHECK" => "N",
                                                                                        "DISPLAY_DATE" => "N",
                                                                                        "DISPLAY_NAME" => "N",
                                                                                        "DISPLAY_PICTURE" => "N",
                                                                                        "DISPLAY_PREVIEW_TEXT" => "N",
                                                                                        "COMPOSITE_FRAME_MODE" => "N",
                                                                                        "PAGER_TEMPLATE" => ".default",
                                                                                        "DISPLAY_TOP_PAGER" => "N",
                                                                                        "DISPLAY_BOTTOM_PAGER" => "N",
                                                                                        "PAGER_TITLE" => "",
                                                                                        "PAGER_SHOW_ALWAYS" => "N",
                                                                                        "PAGER_DESC_NUMBERING" => "N",
                                                                                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                                                                        "PAGER_SHOW_ALL" => "N",
                                                                                        "PAGER_BASE_LINK_ENABLE" => "N",
                                                                                        "SET_STATUS_404" => "N",
                                                                                        "SHOW_404" => "N",
                                                                                        "MESSAGE_404" => "",
                                                                                    ),
                                                                                    $component
                                                                                ); ?>

                                                                            </div>

                                                                        <?endif;?>

                                                                    <?endif;?>

                                                                </div>

                                                                <?if($arItem["BOTTOM_TEXT"]):?>
                                                                    <div class="text-content bottom-text"><?=$arItem["BOTTOM_TEXT"]?></div>
                                                                <?endif;?>
                                                            </div>
                                                        </div>

                                                        

                                                        <div class="blur-shadow-top"></div>
                                                        <div class="blur-shadow-bottom"></div>


                                                    </div>

                                                <?endif;?>

                                            <?endif;?>

                                        <?endif;?>
                                     
                                    </li>
                                    

                                <?endforeach;?>


                            </nav>

                        </td>

                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["MAIN_MENU"] == 'Y' && $PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"]):?>


                            <td class="wrapper-social">

                                <a class="show-soc-groups d-block"

                                    <?=(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SOC_GROUP_ICON']["SRC"]))?"style='background-image: url(\"".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SOC_GROUP_ICON']["SRC"]."\");'":"";?>

                                ></a>
                                <div class="soc-groups-in-menu d-none">
                                    <div class="desc">  
                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SOC_GROUPS_DESCRIPTION_IN_MENU"]?>
                                    </div>  
                                    <?CPhoenix::CreateSoc($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"])?>

                                    <div class="close-soc-groups"></div>
                                </div>
                                
                                

                                <?/*if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_IN_MENU_ON']['VALUE']["ACTIVE"] == "Y"):?>

                                    <div class="mini-cart-style mini-cart-js active">
                                        <div class="area_for_widget-in-menu hidden-xs">
                                            <?
                                                $APPLICATION->IncludeComponent(
                                                    "concept:phoenix.mini_cart",
                                                    "widget-in-menu",
                                                    Array(
                                                        "CURRENT_SITE" => SITE_ID,
                                                        "MESSAGE_404" => "",
                                                        "SET_STATUS_404" => "N",
                                                        "SHOW_404" => "N",
                                                        "MODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_MODE"]["VALUE"],
                                                        "DESC_EMPTY" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_DESC_EMPTY"]["VALUE"],
                                                        "DESC_NOEMPTY" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_DESC_NOEMPTY"]["VALUE"],
                                                        "LINK" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_LINK_PAGE"]["VALUE"]
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>

                                <?endif;*/?>

                            </td>

                        <?endif;?>
                    </tr>
                </table>

            </div>

            

        </div>
    </div>

<?endif;?>