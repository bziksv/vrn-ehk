<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $PHOENIX_TEMPLATE_ARRAY;
$footer_style = "";

if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COLOR_BG"]['VALUE'])>0)
{
    $arColor = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COLOR_BG"]['VALUE'];
    $percent = 1;

    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COLOR_BG"]['VALUE'] == "transparent")
        $footer_style .= 'background-color: transparent; ';

 
    else if(preg_match('/^\#/', $arColor))
    {
        $arColor = CPhoenix::convertHex2rgb($arColor);
        $arColor = implode(',',$arColor);

        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_OPACITY_BG"]['VALUE'])>0)
        $percent = (100 - $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_OPACITY_BG"]['VALUE'])/100;

    
        $footer_style .= ' background-color: rgba('.$arColor.', '.$percent.')";';
    }

    else
        $footer_style .= 'background-color: '.$arColor.'; ';
}
?>


<footer class="
txt-color-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["TEXT_COLOR"]["VALUE"]?> 
tone-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?> 
<?if(!$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_BG"]["VALUE"] && strlen($footer_style)<=0):?>default_bg<?endif;?>
lazyload" 
<?if(strlen($footer_style)>0):?> style="<?=$footer_style?>" <?endif;?>

    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_BG"]["VALUE"]):?>
        <?$footer_bg = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_BG"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
        data-src="<?=$footer_bg['src']?>"
    <?endif;?>
>

    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["HIDE_BG_TONE"]["VALUE"]["ACTIVE"] !=='Y'):?><div class="shadow-tone"></div><?endif;?>

    <div class="container">

        <div class="container-top">
            <div class="row">
                
                <div class="col-lg-3 col-sm-6 col-12 column-1">

                    <?=$PHOENIX_TEMPLATE_ARRAY["LOGOTYPE_HTML"]?>

                    <?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("footer-contacts");?>

                        <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["VALUE"])):?>
                            
                            <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"] as $keyPhone => $arPnone):?>
                                <div class="contact-item"><div class="phone"><div class="phone-value"><?=$arPnone['name']?></div></div></div>
                                <?//if($keyPhone >= 1) break;?>
                            <?endforeach;?>
                            
                        <?endif;?>

                        <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"])):?>
                            <div class="contact-item"><div class="email"><a href="mailto:<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']?>"><span class="bord-bot white"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']?></span></a></div>
                            </div>
                        <?endif;?>
                    <?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("footer-contacts");?>

                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
                        <div class="button-wrap">
                            <a class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" 
                            data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FOOTER_DATA_HEADER"]?>"
                            data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
                        </div>
                    <?endif;?>
                    
                </div>

                <div class="col-lg-3 col-sm-6 col-12 column-2">
                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_DESC"]["VALUE"])>0):?>
                        <div class="footer-description-item"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_DESC"]["~VALUE"]?></div>
                    <?endif;?>

                    <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FOOTER'])):?>
                        <div class="political">

                            <?$boderB = ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]=="dark")?"white":""?>
            
                            <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT_FOOTER'] as $arAgr):?>

                                <div class="agreement-item">
                                    <a class="call-modal callagreement" data-call-modal="agreement<?=$arAgr['ID']?>"><span class="bord-bot <?=$boderB?>"><?=$arAgr['NAME']?></span></a>
                                </div>  
                                
                            <?endforeach;?>
                           
                        </div>
                    <?endif;?>

                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_ON"]["VALUE"]["ACTIVE"] == "Y"):?>

                        <div class="copytright-item d-none d-md-block">

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_TYPE"]["VALUE"] == "user"):?>

                                <a<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_URL"]["VALUE"])>0):?> href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_URL"]["VALUE"]?>" target="_blank"<?endif;?> class="copyright">

                                <table>
                                    <tr>
                                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_DESC"]["VALUE"])>0):?>

                                            <td>

                                                <span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_DESC"]["~VALUE"]?></span>

                                            </td>

                                        <?endif;?>

                                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_PIC"]["VALUE"])>0):?>

                                             <td>

                                                <?$logotypeResize = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_PIC"]["VALUE"], array('width'=>100, 'height'=>40), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                            
                                                <img class="img-fluid lazyload" data-src="<?=$logotypeResize['src']?>" alt="logotype" />

                                            </td>

                                        <?endif;?>

                                        
                                    </tr>
                                </table>

                                </a>

                            <?else:?>

                                <a href="http://marketplace.1c-bitrix.ru/solutions/concept.phoenix/" target="_blank" class="copyright">

                                    <table>
                                        <tr>
                                            <td>

                                                <img class="lazyload" data-src="<?=SITE_TEMPLATE_PATH?>/images/copyright_phoenix.png" alt="copyright">

                                            <td>
                                        </tr>
                                    </table>

                                </a>
                            <?endif;?>

                        </div>

                    <?endif;?>


                </div>

                <div class="col-lg-3 col-sm-6 col-12 column-3 parent-tool-settings hidden-xs">
                    
                    <?
                        $APPLICATION->IncludeComponent(
                            "concept:phoenix.menu",
                            "footer",
                            Array(
                                "COMPONENT_TEMPLATE" => "footer",
                                "COMPOSITE_FRAME_MODE" => "N",
                                "CACHE_TIME" => "36000000",
                                "CACHE_TYPE" => "A",
                                "CACHE_USE_GROUPS" => "Y"
                            )
                        );
                    ?>

                    <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"] && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MODE_FAST_EDIT"]['VALUE']["ACTIVE"] == 'Y'):?>

                        <div class="tool-settings">

                            <a 

                                href='/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER_MENU"]["IBLOCK_ID"]?>&type=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER_MENU"]["IBLOCK_TYPE"]?>&lang=ru&find_section_section=0' 
                                class="tool-settings <?if($center):?>in-center<?endif;?>"
                                data-toggle="tooltip"
                                target="_blank"
                                data-placement="right"
                                title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["EDIT"]?> &quot;<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FOOTER_MENU"]?>&quot;"
                            >
                                    
                            </a>

                        </div>

                    <?endif;?>
                </div>

                <div class="col-lg-3 col-sm-6 col-12 column-4">

                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1"]["VALUE"]) || strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2"]["VALUE"])):?>

                        <div class="banner-items">

                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1"]["VALUE"])):?>
                            
                                <div class="banner-item">
                                    <?$banner1 = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1"]["VALUE"], array('width'=>350, 'height'=>130), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>

                                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1_URL"]["VALUE"])):?>
                                        <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1_URL"]["VALUE"]?>" target="_blank">
                                    <?endif;?>

                                        <img class="img-fluid lazyload" data-src="<?=$banner1["src"]?>" alt="" />

                                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1_URL"]["VALUE"])):?>
                                        </a>
                                    <?endif;?>
                                </div>

                            <?endif;?>

                            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2"]["VALUE"])):?>
                            
                                <div class="banner-item">
                                    <?$banner2 = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2"]["VALUE"], array('width'=>350, 'height'=>130), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>

                                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2_URL"]["VALUE"])):?>
                                        <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2_URL"]["VALUE"]?>" target="_blank">
                                    <?endif;?>

                                        <img class="img-fluid lazyload" data-src="<?=$banner2["src"]?>" alt="" />

                                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2_URL"]["VALUE"])):?>
                                        </a>
                                    <?endif;?>
                                </div>

                            <?endif;?>

                        </div>

                    <?endif;?>

                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["FOOTER"] == 'Y')
                        echo CPhoenix::CreateSoc($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]);
                    ?>

                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["SUBSCRIPE"]["VALUE"]["ACTIVE"] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUE"]["SUBSCRIBE"] == "Y"):?>


                        <?$APPLICATION->IncludeComponent(
                            "bitrix:subscribe.form",
                            "main",
                            Array(
                                "CACHE_TIME" => "3600",
                                "CACHE_TYPE" => "A",
                                "COMPOSITE_FRAME_MODE" => "N",
                                "COMPOSITE_FRAME_TYPE" => "AUTO",
                                "PAGE" => SITE_DIR."personal/subscribe/",
                                "SHOW_HIDDEN" => "N",
                                "USE_PERSONALIZATION" => "Y"
                            )
                        );?>

                       

                    <?endif;?>
                </div>

            </div>
        </div>

        <div class="container-bottom row align-items-center">

            <div class="text-item col-lg-6 col-12">
                <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_INFO"]["~VALUE"]?>
            </div>


            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_PIC"]["VALUE"])):?>

                <div class="icon-items col-lg-6 col-12">

                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_URL"]["VALUE"])):?>
                        <a href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_URL"]["VALUE"]?>">
                    <?endif;?>

                    <img class="lazyload" data-src="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_PIC"]["SETTINGS"]["SRC"]?>" alt="" title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_HINT"]["VALUE"]?>"/>

                    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_URL"]["VALUE"])):?>
                        </a>
                    <?endif;?>
                    
                </div>

            <?endif;?>
                  

            
        </div>
    </div>
    
</footer>