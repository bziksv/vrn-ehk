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
?>

<?if(!empty($arResult["SECTIONS"])):?>
<?

global $admin_active;
global $show_setting;
global $PHOENIX_TEMPLATE_ARRAY;

$admin_active = $USER->isAdmin();
$show_setting = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MODE_FAST_EDIT"]['VALUE']["ACTIVE"];

$terminations = Array();

$terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_1"];
$terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_2"];
$terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_3"];
$terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_4"];
?>
<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MAIN_SECTIONS_LIST"]["VALUE"] == "view-2"):?>
    <div class="container">
        <div class="row catalog-list-items">

            <?$i = 1;?>

            <?foreach($arResult["SECTIONS"] as $arSection):?>

                <div class="catalog-list-item view-2 col-lg-4 col-md-6 col-12">

                    <div class="wr-row middle row no-gutters">

                        <div class="col-auto">
                            <div class="wr-img">
                                <img src="<?=$arSection["PREVIEW_PICTURE_SRC"]?>" alt="<?=CPhoenix::prepareText($arSection["NAME"])?>">
                            </div>
                        </div>
                        <div class="col align-self-center">
                            <a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="name bold">
                                <?=$arSection["NAME"]?>
                            </a>
                            <div class="quantity">
                                <?$frame = $this->createFrame()->begin();?>

                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSECTIONS_HIDE_COUNT"]["VALUE"]["ACTIVE"] != "Y" && $arSection["ELEMENT_CNT"]!="0"):?>
                                        <div class="comment"><?=$arSection["ELEMENT_CNT"]?> <?=CPhoenix::getTermination($arSection["ELEMENT_CNT"], $terminations)?></div>
                                    <?endif;?>
                                <?$frame->end();?>
                            </div>
                        </div>

                    </div>

                    <?if(!empty($arSection["SUB"])):?>

                        <div class="wr-row row sub-items">

                            <?foreach($arSection["SUB"] as $arSub):?>
                                <div class="col-auto">
                                    <a class="sub-item" href="<?=$arSub["SECTION_PAGE_URL"]?>"><?=$arSub["~NAME"]?><?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSECTIONS_HIDE_COUNT"]["VALUE"]["ACTIVE"] != "Y"):?>&nbsp;<?$frame = $this->createFrame()->begin();?><span class="sub-quantity"><?=$arSub["ELEMENT_CNT"]?></span><?$frame->end();?><?endif;?></a>
                                </div>
                            <?endforeach;?>

                        </div>
                    <?endif;?>

                    <?if(strlen($arSection["~UF_PHX_CTLG_PRTXT"])>0):?>
                        <div class="text">
                            <?=$arSection["~UF_PHX_CTLG_PRTXT"]?>
                        </div>
                    <?endif;?>
                    
                </div>
                

                <?if($arResult["SECTIONS_CNT"] == $i) break;?>

                <?if($i%2 == 0):?>
                    <span class="col-12 d-none d-md-block d-lg-none"><div class="break-line"></div></span>
                <?endif;?>

                <?if($i%3 == 0):?>
                    <span class="col-12 d-none d-lg-block"><div class="break-line"></div></span>
                <?endif;?>
                
               
                <span class="col-12 d-md-none"><div class="break-line"></div></span>


                <?$i++;?>
            <?endforeach;?>

        </div>
    </div>

<?else:?>

    <div class="catalog-main-menu big-parent-colls">

        
        <?if($admin_active && $show_setting == "Y"):?>
            <div class="change-colls-info"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ALERT_SAVE_IMAGE"]?></div>
        <?endif;?>

        <div class="container">
            <div class="frame-wrap">
     
                <div class="row">
                            
                    <?foreach($arResult["SECTIONS"] as $arSection):?>
                    
                        
                        <div class="<?=$arSection["COLS_FRAME"]?> col-sm-6 col-12 parent-change-cools">

                            <div class="frame-outer<?if(strlen($arSection["UF_PHX_CTLG_PRTXT"]) > 0 || !empty($arSection["SUB"])):?> elem-hover<?endif;?>">
                            
                                <div class="frame-inner elem-hover-height-more">
                                
                                    <div class="frame light elem-hover-height">
                                    
                                    
                                        <?if($admin_active && $show_setting == 'Y'):?>
                                           
                                            <input type="hidden" class='colls_code' value="UF_PHX_CTLG_SIZE" />

                                            <?=$arResult["SETTINGS"]["COLS"]?>
                                        
                                            <span class='change-colls' data-type='section' data-element-id='<?=$arSection["ID"]?>'></span>
                                        <?endif;?>
                                    
        
                                        <!-- <a class="wrap-link" href="<?=$arSection["SECTION_PAGE_URL"]?>"></a> -->
                                        

                                        <?if($arSection["PICTURE"] > 0):?>

                                            <img class="img lazyload visible-sm visible-xs" data-src="<?=$arSection["PICTURE_XS"]?>"/>
                                            <img class="img lazyload visible-md" data-src="<?=$arSection["PICTURE_MD"]?>"/>
                                            <img class="img lazyload visible-xxl visible-xl visible-lg" data-src="<?=$arSection["PICTURE_XL"]?>"/>
                                        
                                        <?endif;?>
                                                
                                        <div class="small-shadow"></div>
                                        <div class="frameshadow"></div>
                                        
                                        <div class="text">
                                        
                                            <div class="cont">
                                                <div class="name bold"><?=$arSection["NAME"]?></div>

                                                <?$frame = $this->createFrame()->begin();?>
                                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSECTIONS_HIDE_COUNT"]["VALUE"]["ACTIVE"] != "Y" && $arSection["ELEMENT_CNT"]!="0"):?>
                                                        <div class="comment"><?=$arSection["ELEMENT_CNT"]?> <?=CPhoenix::getTermination($arSection["ELEMENT_CNT"], $terminations)?></div>
                                                    <?endif;?>
                                                <?$frame->end();?>

                                            </div>
        
                                            <div class="button">
                                            
                                                <a class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?>" href="<?=$arSection["SECTION_PAGE_URL"]?>">
                                                    
                                                    <?if(strlen($arSection["UF_PHX_CTLG_BTN"]) > 0):?>
                                                        <?=$arSection["~UF_PHX_CTLG_BTN"]?>
                                                    <?else:?>
                                                        <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MOVE_TO_CATALOG"]?>
                                                    <?endif;?>
                                                    
                                                </a>
                                                
                                            </div>
        
                                            
                                        </div>
                                        
                                        
                                        <?if($admin_active && $show_setting == 'Y'):?>   
                                        
                                            <div class="tool-settings">
                                            
                                                <a href="/bitrix/admin/iblock_section_edit.php?IBLOCK_ID=<?=$arParams["IBLOCK_ID"]?>&type=<?=$arParams["IBLOCK_TYPE_ID"]?>&ID=<?=$arSection["ID"]?>&lang=ru&find_section_section=0" class="tool-settings " data-toggle="tooltip" target="_blank" data-placement="right" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["EDIT"]?> &quot;<?=htmlspecialcharsEx($arSection["NAME"])?>&quot; "></a>
                                            
                                               
                                    
                                            </div>
                                        
                                        <?endif;?>
                                        
               
                                    </div>
                                    
                                    
                                    <?if(strlen($arSection["UF_PHX_CTLG_PRTXT"]) > 0 || !empty($arSection["SUB"])):?>
                                    
                                        <div class="frame-desc-wrap elem-hover-show">
                                            
                                            <?if(!empty($arSection["SUB"])):?>
                                            
                                                <ul class="catalog-link clearfix">
                                                    <?foreach($arSection["SUB"] as $arSub):?>
                                                        <li>
                                                            <a href="<?=$arSub["SECTION_PAGE_URL"]?>" title="<?=$arSub["NAME"]?>"><span class="bord-bot"><?=$arSub["~NAME"]?></span></a>
                                                        </li>
                                                    <?endforeach;?>
                                                </ul>
                                            
                                            <?endif;?>
                                            
                                            <?if(strlen($arSection["UF_PHX_CTLG_PRTXT"]) > 0):?>
                                            
                                                <div class="frame-desc-wrap-inner">
                                                    <?=$arSection["~UF_PHX_CTLG_PRTXT"]?>
                                                </div>
                                            
                                            <?endif;?>
            
                                        </div>
                                    
                                    <?endif;?>
        
                                </div>
        
                            </div>
                        </div>
                        
                        
                    <?endforeach;?>

                </div>

            </div>
        </div>
        
    </div>


<?endif;?>

<?endif;?>