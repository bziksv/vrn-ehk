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
<?$bIsMainPage = $APPLICATION->GetCurPage(false) == SITE_DIR;


$terminations = Array();

$terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_1"];
$terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_2"];
$terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_3"];
$terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_4"];

?>

<div class="menu-shadow tone-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?> hidden-sm hidden-xs"></div>

<div class="open-menu tone-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?> hidden-sm hidden-xs blur-container">
    <div class="head-menu-wrap">

        <div class="container">

            <div class="wrapper-head-top">

                <div class="row align-items-center">


                    <div class="col-3">

                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"])):?>

                            <table class="wrapper-item">
                                <tr>
                                    <td class="wrapper-logotype">
                                        <?if(!$bIsMainPage):?>
                                            <a href="<?=SITE_DIR?>">
                                        <?endif;?>

                                           <?=$PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"]?>

                                        <?if(!$bIsMainPage):?>
                                            </a>
                                        <?endif;;?>
                                    </td>
                                </tr>
                            </table>

                        <?endif;?>
                        
                    </div>

                    <div class="col">

                        <?
                            if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['SHOW_IN']['VALUE']['IN_MENU_OPEN'] == "Y" )
                                $APPLICATION->IncludeComponent("concept:phoenix.search.line", "fix-header", 
                                    Array(
                                        "CONTAINER_ID" => "search-open-menu",
                                        "INPUT_ID" => "search-open-menu",
                                        "COMPOSITE_FRAME_MODE" => "N",
                                    ));
                        ?>

                    </div>

                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["VALUE"])>0):?>
                        <div class="col hidden-md text-html">

                            <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]["~VALUE"]?>

                        </div>
                    <?endif;?>

                    <div class="col-xl-3 col-4">
                        
                        <div class="row no-gutters align-items-center justify-content-end wrapper-item counts-board">

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"] ["ACTIVE"] == "Y"):?>

                                <div class="col-4">
                                    <div class="basket-quantity-info-icon cart count-basket-items-parent"><span class="count count-basket">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>"></a></div>
                                </div>

                            <?endif;?>

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y"):?>

                                <div class="col-4">
                                    <div class="basket-quantity-info-icon delay count-delay-parent"><span class="count count-delay">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL_DELAYED"]?>"></a></div>
                                </div>

                            <?endif;?>

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y"):?>

                                <div class="col-4">
                                    <div class="basket-quantity-info-icon compare count-compare-parent"><span class="count count-compare">&nbsp;</span><a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL_COMPARE"]?>"></a></div>
                                </div>

                            <?endif;?>

                        </div>
                    </div>

                    <div class="col-1 wrapper-close">
                        <a class="close-menu main"></a>
                    </div>
                    
                </div>

            </div>
        </div>

        
    </div>
    
   
    <div class="body-menu">

        <?if( !empty($arResult["MENU_CATALOG"]) && !empty($arResult["MENU_CATALOG"]["SUB"])):?>

            <div class="catalog-navigation">

                <div class="container">
                    <div class="row">

                        <div class="col-12">

                            <div class="name-catalog"><a href="<?=$arResult["MENU_CATALOG"]["LINK"]?>"><?=$arResult["MENU_CATALOG"]["NAME"]?></a></div>
                            
                        </div>

                        <div class="clearfix"></div>

                        <?$i = 1;?>


                        <?foreach($arResult["MENU_CATALOG"]["SUB"] as $arItem):?>

                            <div class="col-xl-3 col-md-4 col-12">

                                <table class="item <?if($arItem["SELECTED"]):?>selected<?endif;?>">
                                    <tr>
                                        <td class="picture">

                                            <a href="<?=$arItem["LINK"]?>">

                                                <div class="picture-board">

                                                    <?if(isset($arItem["PICTURE_SRC"])):?>

                                                        <img class="lazyload" data-src="<?=$arItem["PICTURE_SRC"]?>">

                                                    <?else:?>

                                                        <div class="def-img"></div>

                                                    <?endif;?>
                                                    
                                                </div>
                                            </a>
                                            
                                        </td>
                                        <td class="decription">
                                            <div class="name"><a href="<?=$arItem["LINK"]?>"><?=$arItem["NAME"]?></a></div>
                                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSECTIONS_HIDE_COUNT"]["VALUE"]["ACTIVE"] != "Y"):?>

                                                <?if($arItem["ELEMENT_CNT"]!="0"):?>
                                                    <div class="count"><?=$arItem["ELEMENT_CNT"]?> <?=CPhoenix::getTermination($arItem["ELEMENT_CNT"], $terminations)?></div>
                                                <?endif;?>
                                            <?endif;?>
                                        </td>
                                    </tr>
                                </table>

                            </div>

                        <?endforeach;?>

                   

                        <div class="clearfix"></div>
                    </div>
                </div>
                
            </div>

        <?endif;?>

        <div class="main-menu-navigation">

            <div class="container">
                <div class="row">
        
                    <?$k1 = 0;?>
                
                    <?for($i=1; $i<=4; $i++):?> 

                        <?if(!empty($arResult["MENU_COL_".$i])):?>
                        
                            <?$k1++;?>
                            
                            <div class="col-lg-3 col-md-4 col-12">
                                
                                <?foreach($arResult["MENU_COL_".$i] as $arItem):?>

                                	<?
                                		if(empty($arItem))
                                			continue;
                                	?>
                                
                                    <?$colorText = '';?>
                                    <?$icon = '';?>

                                    <?if(strlen($arItem['MENU_COLOR'])>0):?>
                                        <?$colorText = ' style="color: '.$arItem['MENU_COLOR'].';"';?>
                                    <?endif;?>

                                    <?if($arItem['MENU_IC_US'] > 0):?>

                                        <?$iconResize = CFile::ResizeImageGet($arItem['MENU_IC_US'], array('width'=>21, 'height'=>21), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>

                                        <?$icon = '<img class="img-fluid img-icon lazyload" data-src="'.$iconResize['src'].'" alt="'.CPhoenix::prepareText($arItem['NAME']).'" />';?>

                                    <?elseif(strlen($arItem['MENU_ICON'])>0):?>
                                        <?$icon = '<i class="concept-icon '.$arItem['MENU_ICON'].'"></i>';?>
                                    <?endif;?>

                                    <div class="list-menu">
                                        
                                        <a <?if($arItem['NOLINK']):?>

                                            <?=CPhoenix::phoenixMenuAttr($arItem, $arItem['TYPE'])?>

                                        <?else:?>

                                            <?if(strlen($arItem["LINK"]) > 0 && !$arItem["NONE"]):?>href='<?=$arItem['LINK']?>'<?endif;?>


                                            <?if($arItem['BLANK']):?>

                                                target='_blank'

                                            <?endif;?>

                                        <?endif;?>

                                        class="main-item <?if(strlen($arItem["LINK"]) <= 0 && $arItem["NONE"]):?>empty-link<?else:?>hover close-menu-js open<?endif;?>

                                        <?if($arItem["SELECTED"]):?>selected<?endif;?>

                                        <?if($arItem['NOLINK']):?>

                                            <?=CPhoenix::phoenixMenuClass($arItem, $arItem['TYPE'], 'from-modal')?>

                                        <?endif;?>

                                         " <?=$colorText?>><?=$icon?><?=$arItem['NAME']?></a>

                                        <?if(!empty($arItem["SUB"])):?>
                                            <ul class="child">

                                                <?foreach($arItem['SUB'] as $arElements):?>

                                                    <li class="<?if(!empty($arElements["SUB"])):?>parent<?endif;?> <?if($arElements["SELECTED"]):?>selected<?endif;?> 
                                                    <?if(strlen($arElements["ID"])):?>

                                                        section-menu-id-<?=$arElements["ID"]?>

                                                    <?endif;?>">

                                                        <a 

                                                        <?if($arElements['NOLINK']):?>

                                                            <?=CPhoenix::phoenixMenuAttr ($arElements, $arElements['TYPE'])?>

                                                        <?else:?>

                                                            <?if(strlen($arElements["LINK"]) > 0 && !$arElements["NONE"]):?> href='<?=$arElements['LINK']?>'<?endif;?>

                                                            

                                                            <?if($arElements['BLANK']):?>

                                                                target='_blank'

                                                            <?endif;?>

                                                        <?endif;?>


                                                        class="<?if(strlen($arElements["LINK"]) <= 0 && $arElements["NONE"]):?>empty-link<?else:?>hover close-menu-js<?endif;?>

                                                        

                                                        <?if($arElements['NOLINK']):?>

                                                            <?=CPhoenix::phoenixMenuClass($arElements, $arElements['TYPE'], 'from-modal')?>

                                                        <?endif;?>

                                                        "><?=$arElements['NAME']?></a>

                                                        <?if(!empty($arElements["SUB"])):?>

                                                            <ul class="child2">
                                                                <?foreach($arElements["SUB"] as $key_arElements2 => $arElements2):?>
                                                                    <li class="
                                                                        <?if($arElements2["SELECTED"]):?>selected<?endif;?>
                                                                        <?if(strlen($arElements2["ID"])):?>

                                                                            section-menu-id-<?=$arElements2["ID"]?>

                                                                        <?endif;?>

                                                                    ">


                                                                    <a 

                                                                    <?if($arElements2['NOLINK']):?>

                                                                        <?=CPhoenix::phoenixMenuAttr($arElements2, $arElements2['TYPE'])?>

                                                                    <?else:?>

                                                                        <?if(strlen($arElements2["LINK"]) > 0 && !$arElements2["NONE"]):?> href='<?=$arElements2['LINK']?>'<?endif;?>

                                                                        <?if($arElements2['BLANK']):?>

                                                                            target='_blank'

                                                                        <?endif;?>

                                                                    <?endif;?>

                                                                    

                                                                     class="<?if(strlen($arElements2["LINK"]) <= 0 && $arElements2["NONE"]):?>empty-link<?else:?>hover close-menu-js<?endif;?>

                                                                    

                                                                    <?if($arElements2['NOLINK']):?>

                                                                        <?=CPhoenix::phoenixMenuClass($arElements2, $arElements2['TYPE'], 'from-modal')?>


                                                                    <?endif;?>

                                                                     "><?=$arElements2['NAME']?></a>
                                                                    </li>
                                                                <?endforeach;?>
                                                            </ul>

                                                        <?endif;?>

                                                    </li>

                                                <?endforeach;?>

                                            </ul>
                                        <?endif;?>
                                    </div>
                                <?endforeach;?>
                                
                            </div>
                            
                            

                        <?endif;?>
                    
                    
                        <?if($k1 == 2):?>
                            <div class="clearfix visible-sm"></div>
                        <?endif;?>
                    
                    
                    <?endfor;?>

                </div>
            </div>
        </div>
            
        
    </div>
            
        

    <div class="footer-menu-wrap">
        <div class="container">
            <div class="row">

                
                <?$collsCenter = 'col-lg-6 col-md-8 col-12';?>

                <?$frame = $this->createFrame()->begin();?>

                    <?if( empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"]) && empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"]) && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["MENU"] != 'Y'):?>
                        
                        <?$collsCenter = 'col-12';?>

                    <?elseif(empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"])):?>

                        <?$collsCenter = 'col-lg-9 col-12';?>

                    <?elseif(empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"]) && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["MENU"] != 'Y'):?>

                        <?$collsCenter = 'col-lg-9 col-md-8 col-12';?>

                    <?endif;?>



                    <?if( ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y") || !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"])):?>
                        <div class="col-lg-3 col-md-4 col-12 unset-margin-top-child left">

                            <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"])):?>
                                <div class="phone">
                                    <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"] as $keyPhone => $arPnone):?>

                                  
                                        <div><div class="phone-value"><?=$arPnone['name']?></div></div>

                                        <?//if($keyPhone >= 1) break;?>

                                    <?endforeach;?>
                                </div>
                            <?endif;?>

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>

                                <div class="button-wrap">
                                    <a class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform from-modal" 

                                        data-from-open-modal='open-menu' 
                                        data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["OPENMENU_HEADER"]?>" 
                                        data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>">
                                        <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?>
                                            
                                    </a>
                                </div>
                            <?endif;?>
                        </div>

                    <?endif;?>

                <?$frame->end();?>


                <div class="<?=$collsCenter?> center">
                    <div class="copyright-text unset-margin-top-child">

                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_DESC"]["VALUE"])>0):?>
                            <div class="top-text"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_DESC"]["~VALUE"]?></div>
                        <?endif;?>
                        
                        <?/*if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER_INFO"]["VALUE"])>0):?>
                            <div class="top-text reqs"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER_INFO"]["~VALUE"]?></div>
                        <?endif;*/?>
                        
                        
                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPY"]["VALUE"])>0):?>
                            <div class="bottom-text"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPY"]["~VALUE"]?></div>
                        <?endif;?>

                        <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FOOTER'])):?>
                            <div class="political">
                                
                              
                                <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FOOTER'] as $k => $arAgr):?>

                                    <a class="call-modal callagreement from-modal from-openmenu" data-call-modal="agreement<?=$arAgr['ID']?>"><span class="bord-bot"><?=$arAgr['NAME']?></span></a>

                                    
                                <?endforeach;?>
                               
                            </div>
                        <?endif;?>

                        

                    </div>
                </div>
                    
              
                <?$frame = $this->createFrame()->begin();?>
                    <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"]) || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["MENU"] == 'Y'):?>

                        <div class="col-lg-3 col-12 unset-margin-top-child right">


                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["MENU"] == 'Y'):?>
                                <?=CPhoenix::CreateSoc($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"])?>
                            <?endif;?>
                            

                            <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"])):?>
                                <div class="email"><a href="mailto:<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']?>"><span class="bord-bot white"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']?></span></a></div>
                            <?endif;?>
                        </div>

                    <?endif;?>
                <?$frame->end();?>
            </div>
        </div>
        
    </div>
</div>