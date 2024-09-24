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

<?if(!$PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"]) return;?>

<?if($_POST['ajax_seo']=='Y'):?>
    <?$APPLICATION->RestartBuffer();?>


    <div class="inner">

        <div class="phoenix-set-head row no-margin align-items-center">
        
            <div class="col-md-2 col-3 phoenix-set-image"><div></div></div>
            <div class="col-md-8 col-6 phoenix-set-name bold"><?=GetMessage("PHOENIX_SEO_TITLE_NAME")?></div>
            <div class="col-md-2 col-3"></div>
            

            <a class="phoenix-set-close"></a>
            
        </div>

        <form action="/" class="form-sets-js set-form form-seo" enctype="multipart/form-data" method="post" role="form">
        
            <?$url = explode("?", $_SERVER["REQUEST_URI"]);?>
            <?$url = trim(str_replace('index.php', "", $url[0]));?>
        
            <input type="hidden" name="site_id" value="<?=SITE_ID?>" />
            <input type="hidden" name="seourl" value="<?=$url?>" />


            <div class="phoenix-set-top">
                <div class="progress-wrap">
                    <div class="progress-top">
                        <div class="progress-name seo-<?=$arResult["SEO_CLASS"]?>"><?=GetMessage("PHOENIX_SEO_".$arResult["SEO_STATUS"])?></div>
                        <div class="points">(<?=$arResult["SEO_POINTS"]?> <?=GetMessage("PHOENIX_SEO_POINT")?> 100 <?=GetMessage("PHOENIX_SEO_POINTS")?>)</div>
                        <div class="seo-more_info"><span class='show_info'><?=GetMessage("PHOENIX_SEO_SHOW_INFO")?></span><span class='hide_info'><?=GetMessage("PHOENIX_SEO_HIDE_INFO")?></span></div>
                    </div>
                    <div class="progress-bar-phoenix">
                        <div class="progress-status" style='width: <?=$arResult["SEO_POINTS"]?>%;'></div>
                    </div>
                </div>
                <div class="progress-info">
                    <ul>
                        <?if(!empty($arResult["SEO_MESSAGE"])):?>
                            <?foreach($arResult["SEO_MESSAGE"] as $arMess):?>
                                <li class='seo-<?=$arMess["class"]?>'><?=GetMessage($arMess["TEXT"])?></li>
                            <?endforeach;?>
                        <?endif;?>
                    </ul>

                    <div class="spec-comment italic">
                        <?=GetMessage("PHOENIX_SEO_COMMENT")?>
                    </div>
                </div>
            </div>


            <div class="phoenix-set-content">

                <table class="sides">
                    <tr>
                        <td class='set-side-left'>
                        
                            <ul class="set-tabs">
                                <li class='active' data-set='meta'><?=GetMessage("PHOENIX_SEO_META")?></li>
                                <li data-set='og'><?=GetMessage("PHOENIX_SEO_SOC")?></li>
                                <li data-set='other-meta'><?=GetMessage("PHOENIX_SEO_OTHER_META")?></li>
                            </ul>
                            
                            <div class="instruct">
                                <a href="https://goo.gl/uDNEPN" target="_blank"><?=GetMessage("PHOENIX_SEO_INSTRUCT")?></a>
                            </div>
                            
                        </td>
                        
                        <td class='set-side-right'>

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['ACTIVE']["VALUE"]["ACTIVE"] == "Y"):?>
                            <div>
                                <ul class="form-check">                                                
                                    <li>
                                        <label>
                                            <input class='on-save' name="phoenix_region_default" <?if($arResult["REGION_ID"] <= 0):?>checked<?endif;?> type="checkbox" value="Y"><span></span><span><?=GetMessage("PHOENIX_SEO_REGION_DEFAULT")?></span> 
                                        </label>
                                        
                                    </li>
                                    
                                </ul>
                            </div>
                            <?endif;?>

                            <div class="set-content active" data-set='meta'>
                            
                                <div class="input-wrap">
                                    
                                    <ul class="form-check">                                                
                                        <li>
                                            <label>
                                                <input class='on-save' name="phoenix_seo_noindex" <?if($arResult["NOINDEX"] == 1):?>checked<?endif;?> type="checkbox" value="1"><span></span><span><?=GetMessage("PHOENIX_SEO_NOINDEX")?></span> 
                                            </label>
                                            
                                            <span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=GetMessage("PHOENIX_SEO_NOINDEX_HINT")?>"></span>
                                            
                                        </li>
                                        
                                    </ul>
                                
                                </div>
                            
                                <?if(!$GLOBALS["IS_CONSTRUCTOR"]):?>
                                
                                    <div class="input-wrap parent-seo-copy">
                                    
                                        <div class="name bold">
                                        
                                            <?=GetMessage("PHOENIX_SEO_H1")?> 
                                            
                                            <span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=GetMessage("PHOENIX_SEO_H1_HINT")?>"></span>
                                            
                                            <?if(!empty($arResult["SEO_H1_MESSAGE"])):?>
                                            
                                                <span class="answ-seo seo-<?=$arResult["SEO_H1_MESSAGE"][0]["class"]?>" data-toggle="tooltip" data-placement="left" title="" data-original-title="<?=GetMessage($arResult["SEO_H1_MESSAGE"][0]["TEXT"])?>"></span> 
                                                
                                            <?endif;?>
                                            
                                        </div>
                                
                                        <div class="textarea for-copy two_size">
                                        
                                            <?if(strlen($arResult["NEW_H1"]) > 0):?>
                                                <?$h1=$arResult["NEW_H1"]?>
                                            <?else:?>
                                                <?$h1=$arResult["H1"]?>
                                            <?endif;?>
                                        
                                            <div class='disabled_texarea'><span><?=$h1?></span></div>
                                            
                                            <div class="wrap-change"><span type='open' class="change open_more_options on-save" data-show-options="new_seo_h1"><?=GetMessage("PHOENIX_SETTINGS_LIST_CHANGE")?></span></div>
        
                                        </div>
        
                                        <div class="more-option hidden-seo-area" data-show-options="new_seo_h1">
                                        
                                            <div class="name bold"><?=GetMessage("PHOENIX_SEO_NEW_H1")?></div>
                                            
                                            <div class="seo-cancel open_more_options" type='close' data-show-options="new_seo_h1"><?=GetMessage("PHOENIX_SEO_CANCEL")?></div>
        
                                        
                                            <div class="textarea two_size">
                                                <textarea data-fill="<?=(strlen($arResult['NEW_H1'])>0 ? 'Y' : 'N');?>" class='seo-clone' name="phoenix_seo_h1"><?=$arResult["NEW_H1"]?></textarea>
                                            </div>
                                    
                                            
                                        </div>
                                    
                                    </div>
                                
                                <?endif;?>
                                
                                <div class="input-wrap parent-seo-copy">
                                
                                    <div class="name bold">
                                    
                                        <?=GetMessage("PHOENIX_SEO_TITLE")?> 
                                        
                                        <span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=GetMessage("PHOENIX_SEO_TITLE_HINT")?>"></span>
                                        
                                        <?if(!empty($arResult["SEO_TITLE_MESSAGE"])):?>
                                        
                                            <span class="answ-seo seo-<?=$arResult["SEO_TITLE_MESSAGE"][0]["class"]?>" data-toggle="tooltip" data-placement="left" title="" data-original-title="<?=GetMessage($arResult["SEO_TITLE_MESSAGE"][0]["TEXT"])?>"></span>
                                        
                                        <?endif;?>
                                        
                                    </div>
                            
                                    <div class="textarea for-copy two_size">
                                    
                                        <?if(strlen($arResult["NEW_TITLE"]) > 0):?>
                                            <?$title=$arResult["NEW_TITLE"]?>
                                        <?else:?>
                                            <?$title=$arResult["TITLE"]?>
                                        <?endif;?>
                                    
                                        <div class='disabled_texarea'><span><?=$title?></span></div>
                                        
                                        <div class="wrap-change"><span type='open' class="change open_more_options on-save" data-show-options="new_seo_title"><?=GetMessage("PHOENIX_SETTINGS_LIST_CHANGE")?></span></div>
    
                                    </div>
    
                                    <div class="more-option hidden-seo-area" data-show-options="new_seo_title">
                                    
                                        <div class="name bold"><?=GetMessage("PHOENIX_SEO_NEW_TITLE")?></div>
                                        
                                        <div class="seo-cancel open_more_options" type='close' data-show-options="new_seo_title"><?=GetMessage("PHOENIX_SEO_CANCEL")?></div>
    
                                    
                                        <div class="textarea two_size">
                                            <textarea data-fill="<?=(strlen($arResult['NEW_TITLE'])>0 ? 'Y' : 'N');?>" class='seo-clone' name="phoenix_seo_title"><?=$arResult["NEW_TITLE"]?></textarea>
                                        </div>
                                
                                        
                                    </div>
                                
                                </div>
                                
                                
                                <div class="input-wrap parent-seo-copy">
                                
                                    <div class="name bold">
                                    
                                        <?=GetMessage("PHOENIX_SEO_DESCRIPTION")?> 
                                        
                                        <span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=GetMessage("PHOENIX_SEO_DESCRIPTION_HINT")?>"></span>
                                        
                                        <?if(!empty($arResult["SEO_DESCRIPTION_MESSAGE"])):?>
                                        
                                            <span class="answ-seo seo-<?=$arResult["SEO_DESCRIPTION_MESSAGE"][0]["class"]?>" data-toggle="tooltip" data-placement="left" title="" data-original-title="<?=GetMessage($arResult["SEO_DESCRIPTION_MESSAGE"][0]["TEXT"])?>"></span>
                                        
                                        <?endif;?>
                                        
                                    </div>
                            
                                    <div class="textarea for-copy three_size">
                                    
                                        <?if(strlen($arResult["NEW_DESCRIPTION"]) > 0):?>
                                            <?$description=$arResult["NEW_DESCRIPTION"]?>
                                        <?else:?>
                                            <?$description=$arResult["DESCRIPTION"]?>
                                        <?endif;?>
                                    
                                        <div class='disabled_texarea'><span><?=$description?></span></div>
                                        
                                        <div class="wrap-change"><span type='open' class="change open_more_options on-save" data-show-options="new_seo_description"><?=GetMessage("PHOENIX_SETTINGS_LIST_CHANGE")?></span></div>
    
                                    </div>
    
                                    <div class="more-option hidden-seo-area" data-show-options="new_seo_description">
                                    
                                        <div class="name bold"><?=GetMessage("PHOENIX_SEO_NEW_DESCRIPTION")?></div>
                                        
                                        <div class="seo-cancel open_more_options" type='close' data-show-options="new_seo_description"><?=GetMessage("PHOENIX_SEO_CANCEL")?></div>
    
                                    
                                        <div class="textarea three_size">
                                            <textarea data-fill="<?=(strlen($arResult['NEW_DESCRIPTION'])>0 ? 'Y' : 'N');?>" class='seo-clone' name="phoenix_seo_description"><?=$arResult["NEW_DESCRIPTION"]?></textarea>
                                        </div>
                                
                                        
                                    </div>
                                
                                </div>
                                
                                
                                <div class="input-wrap parent-seo-copy">
                                
                                    <div class="name bold">
                                    
                                        <?=GetMessage("PHOENIX_SEO_KEYWORDS")?> 
                                        
                                        <span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=GetMessage("PHOENIX_SEO_KEYWORDS_HINT")?>"></span>
                                        
                                        <?if(!empty($arResult["SEO_KEYWORDS_MESSAGE"])):?>
                                        
                                            <span class="answ-seo seo-<?=$arResult["SEO_KEYWORDS_MESSAGE"][0]["class"]?>" data-toggle="tooltip" data-placement="left" title="" data-original-title="<?=GetMessage($arResult["SEO_KEYWORDS_MESSAGE"][0]["TEXT"])?>"></span>
                                        
                                        <?endif;?>
                                        
                                    </div>
                            
                                    <div class="textarea for-copy three_size">
                                    
                                        <?if(strlen($arResult["NEW_KEYWORDS"]) > 0):?>
                                            <?$keywords=$arResult["NEW_KEYWORDS"]?>
                                        <?else:?>
                                            <?$keywords=$arResult["KEYWORDS"]?>
                                        <?endif;?>
                                    
                                        <div class='disabled_texarea'><span><?=$keywords?></span></div>
                                        
                                        <div class="wrap-change"><span type='open' class="change open_more_options on-save" data-show-options="new_seo_keywords"><?=GetMessage("PHOENIX_SETTINGS_LIST_CHANGE")?></span></div>
    
                                    </div>
    
                                    <div class="more-option hidden-seo-area" data-show-options="new_seo_keywords">
                                    
                                        <div class="name bold"><?=GetMessage("PHOENIX_SEO_NEW_KEYWORDS")?> 
                                        </div>
                                        
                                        <div class="seo-cancel open_more_options" type='close' data-show-options="new_seo_keywords"><?=GetMessage("PHOENIX_SEO_CANCEL")?></div>
    
                                    
                                        <div class="textarea three_size">
                                            <textarea data-fill="<?=(strlen($arResult['NEW_KEYWORDS'])>0 ? 'Y' : 'N');?>" class='seo-clone' name="phoenix_seo_keywords"><?=$arResult["NEW_KEYWORDS"]?></textarea>
                                        </div>
                                
                                        
                                    </div>
                                
                                </div>
                                
                                <?if($GLOBALS["IS_CONSTRUCTOR"]):?>
                                
                                    <div class="spec-comment italic">
                                        <?=GetMessage("PHOENIX_SEO_BASE_COMMENT")?>
                                    </div>
                                    
                                <?endif;?>

                            </div>
                            
                            <div class="set-content" data-set='og'>
                                        
                                            
                                <div class="input-wrap middle clearfix">
                                    
                                    
                                    <div class="clearfix">
                                        
                                        <div class="row">
                                        
                                            <div class="col-md-6 col-12">

                                                
                                            
                                                <div class="input to-right clearfile-parent">

                                                    <label class="file on-save <?if(strlen($arResult["OG_IMAGE_NAME"]) > 0):?>focus-anim<?endif;?>">
                                                    
                                                        <input type="hidden" name="imageogimage" value="<?=$arResult["OG_IMAGE"]?>">

                                                        <input type="hidden" class='phoenix_file_del' name="phoenix_seo_og_image_del" value="">

                                                        <span class="ex-file-desc"><?=GetMessage("PHOENIX_SEO_OG_IMAGE")?></span>
                                                        <span class="ex-file"><?=$arResult["OG_IMAGE_NAME"]?></span>
                                                        <input type="file" accept="image/*" class="hidden flat" id="phoenix_seo_og_image" name="phoenix_seo_og_image"  />
                                                    </label>

                                                    <div class="clearfile on-save <?if(strlen($arResult["OG_IMAGE"]) > 0):?>on<?endif;?>"></div>

                                                </div>
                                                
                                            </div>
                                        
                                        </div>
                                    
                                    </div>
                                    
                                    
                                    

                                    <div class="input <?if(strlen($arResult["OG_TITLE"]) > 0):?>in-focus<?endif;?>">  
                                        <div class="bg"></div>
                                        <span class="desc"><?=GetMessage("PHOENIX_SEO_OG_TITLE")?></span>
                                        <input type="text" class='focus-anim on-save' name="phoenix_seo_og_title" value="<?=$arResult["OG_TITLE"]?>">
                                    
                                    </div>
                                
                                

                                    <div class="textarea <?if(strlen($arResult["OG_DESCRIPTION"]) > 0):?>in-focus<?endif;?>">
                                        <div class="bg"></div>
                                        <span class="desc"><?=GetMessage("PHOENIX_SEO_OG_DESCRIPTION")?></span>
                                        <textarea class="focus-anim on-save" name="phoenix_seo_og_description" value="<?=$arResult["OG_DESCRIPTION"]?>"><?=$arResult["OG_DESCRIPTION"]?></textarea>
                                    </div>
                                
                                
                                
                                
                                
                                    
                                    
                                    <div class="input-wrap clearfix"></div>
                                    
                                    <div class="name bold"><?=GetMessage("PHOENIX_SEO_OG_NOT_NESSESARY")?></div>
                                    
                                    
                                    <div class="input <?if(strlen($arResult["OG_URL"]) > 0):?>in-focus<?endif;?>">  
                                        <div class="bg"></div>  
                                        <span class="desc"><?=GetMessage("PHOENIX_SEO_OG_URL")?></span>
                                        <input type="text" class='focus-anim on-save' name="phoenix_seo_og_url" value="<?=$arResult["OG_URL"]?>">
                                    
                                    </div>
            
                                    
                                    <div class="input <?if(strlen($arResult["OG_TYPE"]) > 0):?>in-focus<?endif;?>">  
                                        <div class="bg"></div>
                                        <span class="desc"><?=GetMessage("PHOENIX_SEO_OG_TYPE")?></span>
                                        <input type="text" class='focus-anim on-save' name="phoenix_seo_og_type" value="<?=$arResult["OG_TYPE"]?>">
                                    
                                    </div>
                                    
                                    
                                    <div class="spec-comment italic">
                                    
                                        <?=GetMessage("PHOENIX_SEO_OG_COMMENT")?><br /><br />
                                        
                                        <?=GetMessage("PHOENIX_SEO_OG_IMAGE_COMMENT")?><br />
                                        <?=GetMessage("PHOENIX_SEO_OG_TITLE_COMMENT")?><br />
                                        <?=GetMessage("PHOENIX_SEO_OG_DESCRIPTION_COMMENT")?><br />
                                        <?=GetMessage("PHOENIX_SEO_OG_URL_COMMENT")?><br />
                                        <?=GetMessage("PHOENIX_SEO_OG_TYPE_COMMENT")?><br />
                                        
                                    </div>
                                
                                
                                </div>
                                
                            </div>
                            
                            
                            <div class="set-content" data-set='other-meta'>
                            
                                <div class="name bold">
                                    <?=GetMessage("PHOENIX_SEO_OTHER_META")?>
                                    <span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=GetMessage("PHOENIX_SEO_META_HINT")?>"></span>
                                </div>
                                
                                <div class="parent-row">

                                    <div class="empty-template">
                                        <div class="input">                                       
                                            <input type="text" class="text seo-name" name="phoenix_other_meta[n<?=count($arResult["META_TAGS"])?>]" value="">
                                        </div>
                                    </div>

                                    <div class="area-for-clone">

                                        <?if(!empty($arResult["META_TAGS"])):?>
                                                
                                            <?foreach($arResult["META_TAGS"] as $k=>$arTag):?>
                                            
                                                <div class="input">                                       
                                                    <input type="text" class="text seo-name on-save" name="phoenix_other_meta[n<?=$k?>]" value="<?=$arTag?>" />
                                                </div>
                                            
                                            <?endforeach;?>

                                        <?endif;?>
                                        
                                    </div>
                                    

                                    <div class="addrow-seo on-save">+ <span><?=GetMessage("PHOENIX_SEO_OTHER_ADD")?></span></div>
                                </div>
                            </div>
                            
                            
                        </td>
                    </tr>
                </table>

            </div>

            <div class="phoenix-set-foot off">
                <table>
                    <tr>
                        <td class='set-left'>
                            <div class="load">
                                <div class="xLoader form-preload set-load"><div class="audio-wave"><span></span><span></span><span></span><span></span><span></span></div></div>
                            </div>
                            <button class="btn-submit-form-seo active" name="form-submit" type="button" value=""><?=GetMessage("PHOENIX_SETTINGS_LIST_SAVE")?></button>
                        </td>
                        <td class='set-right'>
                            <div class='phoenix-set-close'><?=GetMessage("PHOENIX_SETTINGS_LIST_CLOSE")?></div>
                        </td>
                    </tr>
                </table>
                
            </div>
        </form>
    
    </div>


    <?
        if($_POST['ajax_seo']=='Y')
            die();
    ?>
<?endif;?>
