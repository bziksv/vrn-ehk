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
<?

$bIsMainPage = $APPLICATION->GetCurPage(false) == SITE_DIR;

$regionsIsset = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['SHOW_IN_HEAD']["VALUE"]["ACTIVE"] == "Y" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["VALUE"])>0;

?>


    <div class="open-menu-mobile tone-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MOBILE_MENU_TONE"]['VALUE']?> hidden-xxl hidden-xl hidden-lg hidden-md">

        <div class="menu-mobile-inner">

            <div class="head-wrap">

                <div class="row">
                    <div class="col-6">
                        <table class="logotype">
                            <tr>
                                <td>
                                    <?=$PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"]?>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <?if(
                        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CABINET"]["VALUE"]["ACTIVE"] == "Y"
                        || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"] == "Y"
                        || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y"
                        || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y"):?>

                        <div class="col-12">

                            <div class="wr-count-products-info">
                                <div class="row align-items-center">

                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"] == "Y"):?>

                                        <div class="col-3">
                                            <div class="wr-item">
                                                <div class="basket-quantity-info-icon cart count-basket-items-parent"><span class="count count-basket">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"></a></div>
                                            </div>
                                        </div>

                                    <?endif;?>

                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y"):?>

                                        <div class="col-3">
                                            <div class="wr-item">
                                                <div class="basket-quantity-info-icon delay count-delay-parent"><span class="count count-delay">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL_DELAYED"]?>"></a></div>
                                            </div>
                                        </div>

                                    <?endif;?>

                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y"):?>

                                        <div class="col-3">
                                            <div class="wr-item">
                                                <div class="basket-quantity-info-icon compare count-compare-parent"><span class="count count-compare">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL_COMPARE"]?>"></a></div>
                                            </div>
                                        </div>

                                    <?endif;?>

                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CABINET"]["VALUE"]["ACTIVE"] == "Y"):?>

                                        <div class="col-3">
                                            <div class="wr-item">
                                                <?=CPhoenix::ShowCabinetLink();?>
                                            </div>
                                        </div>

                                    <?endif;?>
                                    
                                </div>
                                
                            </div>

                        </div>

                    <?endif;?>

                    <?if($regionsIsset):?>

                        
                        <div class="col-12 region-row">
                            <?="<span class=\"opacity-hover icon-simple ic-region show-popup-block current-region-js invisible\" data-popup=\"region-form\">".strip_tags($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["~NAME"])."</span>";?>
                        </div>
                        
                    <?endif;?>
                </div>
                
                
            </div>
        
               
            <div class="menu-content">

                <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y" ):?>

                    <?
                        $APPLICATION->IncludeComponent("concept:phoenix.search.line", 
                            "mobile", 
                                Array(
                                "CONTAINER_ID" => "search-mobile-menu",
                                "INPUT_ID" => "search-mobile-menu",
                                "COMPOSITE_FRAME_MODE" => "N")
                            );
                    ?>

                <?endif;?>

                <?if(!empty($arResult)):?>

                    <ul class="mobile-menu-list main-list show-open" data-menu-list="main">

                        <?foreach($arResult as $arItem):?>
                            <?$colorText = '';?>
                            <?$icon = '';?>

                            <?if(strlen($arItem['MENU_COLOR'])>0):?>
                                    <?$colorText = ' style="color: '.$arItem['MENU_COLOR'].';"';?>
                                <?endif;?>

                            <?if($arItem['MENU_IC_US'] > 0):?>

                                <?$iconResize = CFile::ResizeImageGet($arItem['MENU_IC_US'], array('width'=>19, 'height'=>19), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>

                                <?$icon = '<img class="img-responsive img-icon lazyload" data-src="'.$iconResize['src'].'" alt="icon" />';?>

                            <?elseif(strlen($arItem['MENU_ICON'])>0):?>
                                <?$icon = '<i class="concept-icon '.$arItem['MENU_ICON'].'"></i>';?>
                            <?endif;?>

                            <li class="

                                <?if(strlen($arItem["ID"])):?>

                                    section-menu-id-<?=$arItem["ID"]?>

                                <?endif;?>


                                <?if($arItem["SELECTED_MENU"] == "Y"):?>selected<?endif;?><?if(!empty($arItem["SUB"])):?> parent<?endif;?>">

                                <a 

                                <?if($arItem['NOLINK']):?>

                                    <?=CPhoenix::phoenixMenuAttr ($arItem, $arItem['TYPE'])?>

                                <?else:?>

                                    <?if(strlen($arItem['LINK'])>0 && empty($arItem["SUB"]) && !$arItem["NONE"] ):?> 

                                        href="<?=$arItem['LINK']?>"

                                        class="close-menu-mobile-js"

                                        <?if($arItem['BLANK']):?>

                                            target='_blank'

                                        <?endif;?>

                                    <?endif;?>

                                <?endif;?>


                                 <?=$colorText?><?if(!empty($arItem["SUB"])):?>data-menu-list="<?=$arItem['IBLOCK_ID'].$arItem["ID"]?>"<?endif;?>

                                    class="

                                        <?if(!empty($arItem["SUB"])):?>open-mobile-list<?endif;?> 

                                        <?if($arItem['NOLINK']):?>
        
                                            <?=CPhoenix::phoenixMenuClass($arItem, $arItem['TYPE'])?>
        
                                        <?endif;?>"

                                 >

                                 <?=$icon?><?=$arItem['NAME']?>
                                     

                                 </a>


                                <div class="border-mob-menu"></div>
                            </li> 

                        <?endforeach;?>
                    </ul>

                    <?foreach($arResult as $arItem):?>

                        <?if(empty($arItem["SUB"])) continue;?>

                        <?$colorText = '';?>
                        <?$icon = '';?>

                        <?if($arItem['MENU_IC_US']>0):?>

                            <?$iconResize = CFile::ResizeImageGet($arItem['MENU_IC_US'], array('width'=>19, 'height'=>19), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>

                            <?$icon = '<img class="img-responsive img-icon lazyload" data-src="'.$iconResize['src'].'" alt="icon" />';?>

                        <?elseif(strlen($arItem['MENU_ICON'])>0):?>
                            <?$icon = '<i class="concept-icon '.$arItem['MENU_ICON'].'"></i>';?>
                        <?endif;?>


                        <ul class="mobile-menu-list in-list" data-menu-list="<?=$arItem['IBLOCK_ID'].$arItem['ID']?>">
                            <li class="back"><a class="open-mobile-list" data-menu-list="main">&larr; <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MENU_TEMPL_MOB_BACK"]?></a></li>

                            <li class="menu-title">
                                <a 

                                <?if($arItem['NOLINK']):?>

                                    class = "<?=CPhoenix::phoenixMenuClass($arItem, $arItem['TYPE'])?>"

                                    <?=CPhoenix::phoenixMenuAttr($arItem, $arItem['TYPE'])?>

                                <?else:?>

                                    <?if(strlen($arItem['LINK'])>0):?> 

                                        href="<?=$arItem['LINK']?>"
                                        class="close-menu-mobile-js"

                                        <?if($arItem['BLANK']):?>

                                            target='_blank'

                                        <?endif;?>

                                    <?endif;?>

                                <?endif;?>

                                <?=$colorText?>><?=$icon?><?=$arItem['NAME']?>
                                    

                                </a>
                            </li>

                            <?if(!empty($arItem["SUB"])):?>
                                <?foreach($arItem["SUB"] as $arElements):?>
                                    <li class="<?if(!empty($arElements["SUB"])):?>parent<?endif;?><?if($arElements["SELECTED"]):?> selected<?endif;?>

                                    <?if(strlen($arElements["ID"])):?>

                                        section-menu-id-<?=$arElements["ID"]?>

                                    <?endif;?>

                                    ">
                                        <a 

                                        <?if($arElements['NOLINK']):?>

                                            class = "<?=CPhoenix::phoenixMenuClass($arElements, $arElements['TYPE'])?>"

                                            <?=CPhoenix::phoenixMenuAttr($arElements, $arElements['TYPE'])?>

                                        <?else:?>

                                            <?if(strlen($arElements['LINK'])>0 && empty($arElements["SUB"]) && !$arElements["NONE"] ):?> 

                                                href="<?=$arElements['LINK']?>"
                                                class="close-menu-mobile-js"

                                                <?if($arElements['BLANK']):?>

                                                    target='_blank'

                                                <?endif;?>

                                            <?endif;?> 

                                        <?endif;?> 



                                        <?if(!empty($arElements["SUB"])):?> class="open-mobile-list 

                                            <?if($arMenuChild2['NOLINK']):?>

                                                <?=CPhoenix::phoenixMenuClass ($arMenuChild2, $arMenuChild2['TYPE'])?>

                                            <?endif;?>

                                            " data-menu-list="<?=$arItem['IBLOCK_ID'].$arItem['ID'].$arElements['ID']?>"<?endif;?>><?=$arElements['NAME']?></a>
                                        <div class="border-mob-menu"></div>
                                    </li>

                                <?endforeach;?>
                            <?endif;?>
                        </ul><!-- ^mobile-menu-list -->


                        <?if(!empty($arItem["SUB"])):?>
                            <?foreach($arItem["SUB"] as $arElements):?>

                                <?if(empty($arElements['SUB'])) continue;?>

                                <ul class="mobile-menu-list in-list" data-menu-list="<?=$arItem['IBLOCK_ID'].$arItem['ID'].$arElements['ID']?>">
                                    <li class="back"><a class="open-mobile-list" data-menu-list="<?=$arItem['IBLOCK_ID'].$arItem['ID']?>"><i class='concept-icon concept-left-open-1'></i><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MENU_TEMPL_MOB_BACK"]?></a></li>
                                    <li class="menu-title">
                                        <a 

                                        <?if($arElements['NOLINK']):?>

                                            class = "<?=CPhoenix::phoenixMenuClass($arElements, $arElements['TYPE'])?>"

                                            <?=CPhoenix::phoenixMenuAttr($arElements, $arElements['TYPE'])?>

                                        <?else:?>

                                            <?if(strlen($arElements['LINK'])>0):?> 

                                                href="<?=$arElements['LINK']?>"
                                                class="close-menu-mobile-js"

                                                <?if($arElements['BLANK']):?>

                                                    target='_blank'

                                                <?endif;?>

                                            <?endif;?> 

                                        <?endif;?> 

                                        <?if($arElements['NOLINK']):?>

                                            class='<?=CPhoenix::phoenixMenuClass ($arElements, $arElements['TYPE'])?>'

                                        <?endif;?>
                                        >

                                        <?=$arElements['NAME']?></a>
                                    </li>

                                    <?foreach($arElements['SUB'] as $key_arElements2 => $arElements2):?>

                                        <li class="<?if($arElements2['SELECTED']):?>selected<?endif;?>

                                            <?if(strlen($arElements2["ID"])):?>

                                                section-menu-id-<?=$arElements2["ID"]?>

                                            <?endif;?>
                                            ">
                                            <a

                                            <?if($arElements2['NOLINK']):?>

                                                class = "<?=CPhoenix::phoenixMenuClass($arElements2, $arElements2['TYPE'])?>"

                                                <?=CPhoenix::phoenixMenuAttr ($arElements2, $arElements2['TYPE'])?>

                                            <?else:?>

                                                <?if(strlen($arElements2['LINK'])>0 && !$arElements2["NONE"] ):?> 

                                                    href="<?=$arElements2['LINK']?>"
                                                    class="close-menu-mobile-js"

                                                    <?if($arElements2['BLANK']):?>

                                                        target='_blank'

                                                    <?endif;?>

                                                <?endif;?> 

                                            <?endif;?> 

                                            <?if($arElements2['NOLINK']):?>

                                                class='<?=CPhoenix::phoenixMenuClass($arElements2, $arElements2['TYPE'])?>'

                                            <?endif;?>

                                            ><?=$arElements2['NAME']?></a>
                                            <div class="border-mob-menu"></div>
                                        </li>

                                    <?endforeach;?>

                                </ul><!-- ^mobile-menu-list -->

                            <?endforeach;?>
                        <?endif;?>

                    <?endforeach;?>

                <?endif;?>

            </div><!-- ^menu-content -->

        </div><!-- ^menu-mobile-inner -->


        <div class="foot-wrap">
         
            <?$frame = $this->createFrame()->begin();?>
                <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]['VALUE']) || !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]['VALUE']) ):?>

                    <?
                        $countPhone = 0;

                        if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]['VALUE'])){
                            if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]['VALUE']) && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]['VALUE']))
                                $countPhone = count($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]['VALUE']);
                        }


                        $countEmail = 0;

                        if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]['VALUE'])){
                            if(is_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]['VALUE']) && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]['VALUE']))
                                $countEmail = count($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]['VALUE']);
                        }
                            


                    ?>
                    
                    <div class="contacts">
                        <div class="phone-wrap">
                            <?$is = '';?>

                            <?if($countPhone):?>
                          
                                <div class="phone"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]['~VALUE'][0]['name']?></div>
                                <?$is = 'phone';?>
                                <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]['~VALUE'][0]['desc']?></div>
                            <?else:?>
                                <a href="mailto:<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]['VALUE'][0]['name']?>"><span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]['VALUE'][0]['name']?></span></a>
                                <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]['~VALUE'][0]['desc']?></div>
                                <?$is = 'email';?>
                            <?endif;?>

                            
                        </div>

                        <?if($countPhone>1 || $countEmail>1 || ($countPhone && $countEmail)):?>

                            <div class="wr-open-list-contact">

                                <div class="ic-open-list-contact open-list-contact"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MOB_MENU_SHOW_CONTACTS"]?></div>
                            </div>

                            <div class="list-contacts">

                                <?if($countPhone):?>

                                    <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]['~VALUE'] as $keyPhone => $arPnone):?>

                                        <?if($is == 'phone' && $keyPhone == 0) continue;?>
                                        <div class="contact-wrap">
                                            <div class="phone"><?=$arPnone['name']?></div>
                                            <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]['~VALUE'][$keyPhone]['desc']?></div>
                                        </div>
                                    <?endforeach;?>

                                <?endif;?>


                                <?if($countEmail):?>

                                    <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]['VALUE'] as $keyEmail => $arEmail):?>
                                        <?if($is == 'email' && $keyEmail==0) continue;?>

                                        <div class="contact-wrap">
                       
                                            <div class="email"><a href="mailto:<?=$arEmail['name']?>"><span class="bord-bot"><?=$arEmail['name']?></span></a></div>
                                            <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]['VALUE'][$keyEmail]['desc']?></div>
                                        </div>


                                    <?endforeach;?>

                                <?endif;?>

                                <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['VALUE'])):?>

                                    <div class="contact-wrap map">

                                        <?if (preg_match("<script>", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["DIALOG_MAP"]["VALUE"])):?>
                   
                                           <?$map = str_replace("<script ", "<script data-skip-moving='true' ", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["DIALOG_MAP"]["~VALUE"]);?>
                                           <?$map = str_replace("scroll=true", "scroll=false", $map);?>

                                           <div class="iframe-map-area" data-src="<?=htmlspecialcharsbx($map)?>"></div>
                                           
                                        <?elseif(preg_match("<iframe>", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["DIALOG_MAP"]["VALUE"])):?>

                                            <div class="iframe-map-area" data-src="<?=htmlspecialcharsbx($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["DIALOG_MAP"]["~VALUE"])?>"></div>
                                            <div class="overlay" onclick="style.pointerEvents='none'"></div>
                                                                  
                                        <?endif;?>

                                    </div>

                                <?endif;?>

                            </div>
                            

                        <?endif;?>
                          

                    </div>
                <?endif;?>
            <?$frame->end();?>
                        
            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']["VALUE"] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
                <a class="button-def shine main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> call-modal callform" data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MENU_TEMPL_MOB_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']["VALUE"]?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
              
            <?endif;?>

            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["MAIN_MENU"] == 'Y'):?>
                <?=CPhoenix::CreateSoc($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"])?>
            <?endif;?>
      
           
        </div><!-- ^foot-wrap -->
        
         
    </div><!-- ^menu-mobile -->
    <a class="close-menu mobile hidden-xxl hidden-xl hidden-lg hidden-md"></a>

   