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
$ymWizard = "ym-record-keys";
?>

<?if($arResult["PROPERTIES"]["FORM_TEXT_TITLE_COLOR"]["VALUE_XML_ID"] == ""):?>
    <?$arResult["PROPERTIES"]["FORM_TEXT_TITLE_COLOR"]["VALUE_XML_ID"] = "dark";?>
<?endif;?>

<?if(strlen($arResult["DETAIL_PICTURE"]) > 0):?>
    <?$img = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array('width'=>1000, 'height'=>1000), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>
<?endif;?>

    
<div class="shadow-modal"></div>

<div class="phoenix-modal form-modal blur-container">

    <div class="phoenix-modal-dialog">
        
        <div class="dialog-content">
            <a class="close-modal"></a>

            <div class="form-modal-table">

                <?if(strlen($arResult["PROPERTIES"]['TITLE_COMMENT']['VALUE']) > 0 
                    || strlen($arResult["PREVIEW_TEXT"]) > 0 
                    || strlen($arResult["DETAIL_PICTURE"]) > 0 
                ):?>

                    <div class="form-modal-cell part-more hidden-xs <?if($arResult["PROPERTIES"]['COVER']['VALUE'] == "Y"):?>cover <?endif;?><?=$arResult["PROPERTIES"]["FORM_POSITION_IMAGE"]["VALUE_XML_ID"]?> <?=$arResult["PROPERTIES"]["FORM_TEXT_TITLE_COLOR"]["VALUE_XML_ID"]?>"<?if(strlen($arResult["DETAIL_PICTURE"]) > 0):?> style="background-image: url('<?=$img["src"]?>');"<?endif;?>>

                        <?if(strlen($arResult["PROPERTIES"]['TITLE_COMMENT']['VALUE']) > 0):?>

                            <div class="comment main1">
                                <?=$arResult["PROPERTIES"]['TITLE_COMMENT']['~VALUE']?>
                            </div>

                        <?endif;?>

                        <?/*if(isset($arResult["ELEMENT_ITEM"]["PHOTO"])):?>

                            <img src="<?=$arResult["ELEMENT_ITEM"]["PHOTO"]?>" alt="" class="img-offer">

                        <?endif;*/?>

                        <?if(strlen($arResult["PREVIEW_TEXT"]) > 0):?>

                            <div class="text-content">
                                <?=$arResult["~PREVIEW_TEXT"]?>
                            </div>

                        <?endif;?>

                    </div>

                <?endif;?>


                <div class="form-modal-cell part-form <?=($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"])?"parent-tool-settings":"";?>" <?if(strlen($arResult["PREVIEW_PICTURE"]) > 0):?><?$img_form = CFile::ResizeImageGet($arResult["PREVIEW_PICTURE"], array('width'=>1000, 'height'=>1000), BX_RESIZE_IMAGE_PROPORTIONAL, false);?><?endif;?> style="background-image: url('<?=$img_form["src"]?>'); background-color: <?=$arResult["PROPERTIES"]['FORM_BGC']['VALUE']?>;">

                    <?if($arResult["PROPERTIES"]["FORM_TEXT_COLOR"]["VALUE_XML_ID"] == ""):?>
                        <?$arResult["PROPERTIES"]["FORM_TEXT_COLOR"]["VALUE_XML_ID"] = "dark";?>
                    <?endif;?>

                    <form id = "form-<?=$arResult['ID']?>" action="/" class="form-<?=$arResult['ID']?> form send <?=$arResult["PROPERTIES"]["FORM_TEXT_COLOR"]["VALUE_XML_ID"]?> <?if($arParams["CART_FORM"] == "Y") echo "form-cart";?>" method="post" role="form">

                        <?/*<input name="element" type="hidden" value="<?=$arResult['ID']?>" />*/?>
                        <input name="header" type="hidden" value="<?if(strlen($arResult["PROPERTIES"]["HEADER"]["~VALUE"]) > 0):?><?=htmlspecialcharsEx($arResult["PROPERTIES"]["HEADER"]["VALUE"])?><?endif;?>" />
                        <input type="hidden" name="form_admin" value="<?=$arResult["PROPERTIES"]["FORM_ADMIN"]["VALUE_XML_ID"]?>" />

                        <?if(isset($arResult["ELEMENT_ITEM"]["ID"])):?>
                            <input name="element_item_id" type="hidden" value="<?=$arResult["ELEMENT_ITEM"]["ID"]?>" />
                        <?endif;?>

                        <?if(isset($arResult["ELEMENT_ITEM"]["TYPE"])):?>
                            <input name="element_item_type" type="hidden" value="<?=$arResult["ELEMENT_ITEM"]["TYPE"]?>" />
                        <?endif;?>

                        <?if(isset($arResult["ELEMENT_ITEM"]["NAME"])):?>
                            <input name="element_item_name" type="hidden" value='<?=$arResult["ELEMENT_ITEM"]["HTML_NAME_VALUE"]?>' />
                        <?endif;?>

                        <?if(isset($arResult["ELEMENT_ITEM"]["PRICE"])):?>
                            <input name="element_item_price" type="hidden" value="<?=$arResult["ELEMENT_ITEM"]["PRICE"]?>" />
                        <?endif;?>
                        
                        <table class="wrap-act">
                            <tr>
                                <td>
                                    <div class="col-12 questions active">
                                        <div class="row">
                                            <?if(strlen($arResult['PROPERTIES']['FORM_TITLE']['VALUE']) > 0):?>

                                                <div class="col-12 title-form main1 clearfix">
                                                    <?=$arResult['PROPERTIES']['FORM_TITLE']['~VALUE']?>
                                                </div>

                                            <?endif;?>

                                            <?if(strlen($arResult['PROPERTIES']['FORM_SUBTITLE']['VALUE']) > 0):?>

                                                <div class="col-12 subtitle-form clearfix">
                                                    <?=$arResult['PROPERTIES']['FORM_SUBTITLE']['~VALUE']?>
                                                </div>

                                            <?endif;?>

                                            <?if(isset($arResult["ELEMENT_ITEM"]["NAME"])):?>
                                            
                                                <div class="col-12 add_text <?if(strlen($arResult['PROPERTIES']['FORM_SUBTITLE']['VALUE']) <= 0):?>more_margin<?endif;?> <?=($arResult["ELEMENT_ITEM"]["TYPE"]=="BETTER_PRICE" || $arResult["ELEMENT_ITEM"]["TYPE"]=="PREORDER")? "offer":"";?>">

                                                    <?=$arResult["ELEMENT_ITEM"]["HTML_NAME"]?>

                                                    <?/*<div class="main-name bold"><?=$arResult["ELEMENT_ITEM"]["NAME"]?></div><?if(isset($arResult["ELEMENT_ITEM"]["OFFERS"]) && !empty($arResult["ELEMENT_ITEM"]["OFFERS"])):?>(<?foreach ($arResult["ELEMENT_ITEM"]["OFFERS"] as $arItem):?><span class="first_name"><?=$arItem["SKU_NAME"]?>:&nbsp;</span><span class="second_name italic"><?=$arItem["NAME"]?></span>;&nbsp;<?endforeach;?>)<?endif;?>*/?>

                                                </div>

                                            <?endif;?>

                                            <?CPhoenixTemplate::hiddenInputsFormHTML();?>

                                            
                                            
                                            <?if($arResult["PROPERTIES"]["FORM_ADMIN"]["VALUE_XML_ID"] == "light" || $arResult["PROPERTIES"]["FORM_ADMIN"]["VALUE_XML_ID"] == ""):?>


                                                <?if($arResult["PROPERTIES"]["FORM_RADIOCHECK"]["VALUE_XML_ID"] == "radio" || $arResult["PROPERTIES"]["FORM_RADIOCHECK"]["VALUE_XML_ID"] == "check"):?>

                                                    <div class="col-12">

                                                        <?if(strlen($arResult["PROPERTIES"]["FORM_LIST_TITLE"]["VALUE"]) > 0):?>

                                                            <div class="name-tit bold">
                                                                <?=$arResult["PROPERTIES"]["FORM_LIST_TITLE"]["~VALUE"]?>
                                                            </div>

                                                        <?endif;?>

                                                         <?if($arResult["PROPERTIES"]["FORM_RADIOCHECK"]["VALUE_XML_ID"] == "radio" && is_array($arResult["PROPERTIES"]["FORM_LIST"]["VALUE"]) && !empty($arResult["PROPERTIES"]["FORM_LIST"]["VALUE"])):?>

                                                                <ul class="input-radio-css">

                                                                    <?foreach($arResult["PROPERTIES"]["FORM_LIST"]["~VALUE"] as $k=>$arElement):?>

                                                                        <li>

                                                                            <label class="input-radio-css">

                                                                                <input <?if($k == 0):?>checked <?endif;?> name='radiobutton<?=$arResult["ID"]?>' type="radio" value="<?=htmlspecialcharsEx($arElement)?>"><span></span><?=$arElement?>

                                                                            </label>
                                                                        </li>

                                                                    <?endforeach;?>

                                                                </ul>

                                                         <?elseif ($arResult["PROPERTIES"]["FORM_RADIOCHECK"]["VALUE_XML_ID"] == "check" && is_array($arResult["PROPERTIES"]["FORM_LIST"]["VALUE"]) && !empty($arResult["PROPERTIES"]["FORM_LIST"]["VALUE"])):?>

                                                             <ul class="input-checkbox-css">

                                                                <?foreach($arResult["PROPERTIES"]["FORM_LIST"]["~VALUE"] as $k => $arElement):?>

                                                                    <li>
                                                                        <label class="input-checkbox-css">
                                                                            <input type="checkbox" name="checkbox<?=$arResult["ID"]?>[]" value="<?=htmlspecialcharsEx($arElement)?>">
                                                                            <span></span>                                                                          
                                                                            <span class="text"><?=$arElement?></span>
                                                                        </label>
                                                                    </li>

                                                                <?endforeach;?>
                             
                                                            </ul>

                                                        <?endif;?>

                                                        

                                                    </div>

                                                <?endif;?>

                                                <?if(is_array($arResult["PROPERTIES"]["FORM_INPUTS"]["VALUE_XML_ID"]) && !empty($arResult["PROPERTIES"]["FORM_INPUTS"]["VALUE_XML_ID"])):?>

                                                    <?foreach($arResult["PROPERTIES"]["FORM_INPUTS"]["VALUE_XML_ID"] as $k=>$arInput):?>

                                                        <?if($arInput == "name"):?>
                                                            <div class="col-12">
                                                                <div class="input">
                                                                    <div class="bg"></div>
                                                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_NAME"]?></span>
                                                                    <input class='focus-anim <?if(in_array("name", $arResult["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?> input-name' name="inp-name" type="text">
                                                                    
                                                                </div>
                                                            </div>
                                                        <?endif;?>

                                                        <?if($arInput == "phone"):?>
                                                            <div class="col-12">
                                                                <div class="input">
                                                                    <div class="bg"></div>
                                                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_PHONE"]?></span>

                                                                    <input name="inp-phone" class="phone focus-anim <?if(in_array("phone", $arResult["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?> input-phone" type="text" autocomplete="inp-phone">
                                                                </div>
                                                            </div>
                                                        <?endif;?>

                                                        <?if($arInput == "email"):?>
                                                            <div class="col-12">
                                                                <div class="input">
                                                                    <div class="bg"></div>

                                                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_EMAIL"]?></span>
                                                                    <input class="focus-anim email <?if(in_array("email", $arResult["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?> input-email" name="inp-email" type="email">
                                                                    
                                                                </div>
                                                            </div>
                                                        <?endif;?>


                                                        <?if($arInput == "count"):?>

                                                            <div class="col-12">
                                                                <div class="input count <?if(in_array("count", $arResult["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?>">

                                                                    <div class="bg"></div>
                                                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_COUNT"]?></span>
                                                                    <input name="count" type="text" class="focus-anim <?if(in_array("count", $arResult["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?> input-count"> <span class="plus"></span> <span class="minus"></span>
                                                                </div>
                                                            </div>

                                                        <?endif;?>


                                                        <?if($arInput == "date"):?>
                                                            <div class="col-12">
                                                                <div class="input date-wrap <?if(in_array("date", $arResult["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?>">

                                                                    <div class="bg"></div>
                                                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_DATE"]?></span>
                                                                    <input class="date focus-anim <?if(in_array("date", $arResult["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?> input-date"  name="date" type="text">
                                                                </div>
                                                            </div>
                                                        <?endif;?>

                                                        <?if($arInput == "address"):?>
                                                            <div class="col-12">
                                                                <div class="input input-textarea">
                                                                    <div class="bg"></div>
                                                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_ADDRESS"]?></span>
                                                                    <textarea class='focus-anim <?if(in_array("address", $arResult["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?> input-textarea-address'  name="address"></textarea>
                                                                </div>
                                                            </div>
                                                        <?endif;?>

                                                        <?if($arInput == "textarea"):?>
                                                            <div class="col-12">
                                                                <div class="input input-textarea">
                                                                    <div class="bg"></div>
                                                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_TEXTAREA"]?></span>

                                                                    <textarea class='focus-anim <?if(in_array("textarea", $arResult["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?> input-textarea-text'  name="text"></textarea>
                                                                </div>
                                                            </div>
                                                        <?endif;?>

                                                        

                                                        <?if($arInput == "file"):?>

                                                            <div class="col-12">
                                                                <div class="load-file">
                                                                    <label class="area-file">

                                                                        <div class="area-files-name">

                                                                            <span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_FILE"]?></span>

                                                                        </div>

                                                                    <input class="hidden <?if(in_array("file", $arResult["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?>" name="userfile[]" type="file" multiple="">

                                                                    <?if(in_array("file", $arResult["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?><span class="star-req"></span><?endif;?>

                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>

                                                        <?endif;?>

                                                    <?endforeach;?>

                                                <?endif;?>
                                                
                                            
                                            <?elseif($arResult["PROPERTIES"]["FORM_ADMIN"]["VALUE_XML_ID"] == "professional"):?>

                                                <?if(!empty($arResult["PROPERTIES"]["FORM_PROP_INPUTS"]["VALUE"])):?>
                                                
                                                    <?foreach($arResult["PROPERTIES"]["FORM_PROP_INPUTS"]["VALUE"] as $key=>$arValue):?>
                                                                            
                                                        <?if(strlen($arValue) > 0):?>
                                                            
                                                            <?$type = $arResult["PROPERTIES"]["FORM_PROP_INPUTS"]["DESCRIPTION"][$key];?>
                                                            
                                                            <?$type = explode(";", ToLower($type));?>

                                                            <?if(!empty($type)):?>
                                                                <?foreach($type as $k=>$val):?>
                                                                    <?$type[$k] = trim($val);?>
                                                                <?endforeach;?>
                                                            <?endif;?>
                                                            
                                                            
                                                            <?if($type[0] == "text"):?>
                                                                
                                                                <div class="col-12">
                                                                    <div class="input">
                                                                        <div class="bg"></div>
                                                                        <span class="desc"><?=$arValue?></span>
                                                                        <input class='focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arResult["ID"]?>_<?=$key?>' name="input_<?=$arResult["ID"]?>_<?=$key?>" type="text" />
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            <?endif;?>
                                                            
                                                            
                                                            <?if($type[0] == "textarea"):?>
                                                                
                                                                <div class="col-12">
                                                                    <div class="input input-textarea input_<?=$arResult["ID"]?>_<?=$key?>">
                                                                        <div class="bg"></div>
                                                                        <span class="desc"><?=$arValue?></span>
                                                                        <textarea class='focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?>' name="input_<?=$arResult["ID"]?>_<?=$key?>"></textarea>
                                                                    </div>
                                                                </div>

                                                            <?endif;?>

                                                            <?if($type[0] == "name"):?>
                                                            
                                                                <div class="col-12">
                                                                    <div class="input">
                                                                        <div class="bg"></div>
                                                                        <span class="desc"><?=$arValue?></span>
                                                                        <input class='focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arResult["ID"]?>_<?=$key?>' name="input_<?=$arResult["ID"]?>_<?=$key?>" type="text" />
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            <?endif;?>
                                                            
                                                            <?if($type[0] == "email"):?>
                                                            
                                                                <div class="col-12">
                                                                    <div class="input">
                                                                        <div class="bg"></div>
                                                                        <span class="desc"><?=$arValue?></span>
                                                                        <input class="focus-anim email <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arResult["ID"]?>_<?=$key?>" name="input_<?=$arResult["ID"]?>_<?=$key?>" type="email">
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            <?endif;?>
                                                            
                                                            <?if($type[0] == "phone"):?>
                                                                   
                                                                <div class="col-12">
                                                                    <div class="input">
                                                                        <div class="bg"></div>
                                                                        <span class="desc"><?=$arValue?></span>
                                                                        <input class="phone focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arResult["ID"]?>_<?=$key?>" name="input_<?=$arResult["ID"]?>_<?=$key?>" type="text" autocomplete="input_<?=$arResult["ID"]?>_<?=$key?>">
                                                                    </div>
                                                                </div>
                                                
                                                            <?endif;?>
                                                            
                                                            <?if($type[0] == "count"):?>
                                                                                                                         
                                                                <div class="col-12">
                                                                    <div class="input count <?if($type[1] == "y"):?>require<?endif;?>">
                                                                        <div class="bg"></div>
                                                                        <span class="desc"><?=$arValue?></span>
                                                                        <input class="focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arResult["ID"]?>_<?=$key?>" name="input_<?=$arResult["ID"]?>_<?=$key?>" type="text"> <span class="plus"></span> <span class="minus"></span>
                                                                    </div>
                                                                </div>
                                                
                                                            <?endif;?>
                                                            
                                                            <?if($type[0] == "date"):?>
                                                            
                                                                <div class="col-12">
                                                                    <div class="input date-wrap <?if($type[1] == "y"):?>require<?endif;?>">
                                                                        <div class="bg"></div>
                                                                        <span class="desc"><?=$arValue?></span>
                                                                        <input class="date focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arResult["ID"]?>_<?=$key?>" name="input_<?=$arResult["ID"]?>_<?=$key?>" type="text">
                                                                    </div>
                                                                </div>
                                                
                                                            <?endif;?>
                                                            
                                                            <?if($type[0] == "password"):?>
                                                            
                                                                <div class="col-12">
                                                                    <div class="input">
                                                                        <div class="bg"></div>
                                                                        <span class="desc"><?=$arValue?></span>
                                                                        <input class="focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arResult["ID"]?>_<?=$key?>" name="input_<?=$arResult["ID"]?>_<?=$key?>" type="password">
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                            <?endif;?>
                                                            
                                                            
                                                            <?if($type[0] == "file"):?>

                                                                <div class="col-12">
                                                                    <div class="load-file">
                                                                        <label class="area-file">

                                                                            <div class="area-files-name">

                                                                                <span><?=$arValue?></span>

                                                                            </div>

                                                                        <input class="hidden <?if($type[1] == "y"):?>require<?endif;?>"  name="input_<?=$arResult["ID"]?>_<?=$key?>[]" type="file" multiple="">

                                                                        <?if($type[1] == "y"):?><span class="star-req"></span><?endif;?>

                                                                        </label>
                                                                    </div>
                                                                </div>

                                                            <?endif;?>
                                                            
                                                            
                                                            <?if($type[0] == "radio"):?>
                                                                
                                                                <?$list = explode(";", htmlspecialcharsBack($arValue));?>
                                                                
                                                                <?
                                                                $first = $list[0];
                                                                
                                                                if(substr_count($first, "<") > 0 && substr_count($first, ">") > 0)
                                                                {
                                                                    $tit = str_replace(array("<", ">"), array("", ""), $first);
                                                                    unset($list[0]);
                                                                }
                                                                
                                                                ?>
                                                            
                                                                <div class="col-12">
                                                                
                                                                    <?if(strlen($tit) > 0):?>
                                                                        <div class="name-tit bold"><?=$tit?></div>
                                                                    <?endif;?>

                                                                    <ul class="input-radio-css">
                                                                    
                                                                        <?$c = 0;?>

                                                                        <?if(!empty($list)):?>

                                                                            <?foreach($list as $arElement):?>

                                                                                <li>

                                                                                    <label class="input-radio-css">

                                                                                        <input <?if($c == 0):?>checked <?endif;?> name='input_<?=$arResult["ID"]?>_<?=$key?>' type="radio" value="<?=htmlspecialcharsEx($arElement)?>"><span></span><?=$arElement?>

                                                                                    </label>
                                                                                </li>
                                                                                
                                                                                <?$c++;?>

                                                                            <?endforeach;?>

                                                                        <?endif;?>

                                                                    </ul>

                                                                </div>
                                                            
                                                            <?endif;?>
                                                            
                                                            
                                                            <?if($type[0] == "checkbox"):?>
                                                                
                                                                <?$list = explode(";", htmlspecialcharsBack($arValue));?>
                                                                
                                                                <?
                                                                $first = $list[0];
                                                                
                                                                if(substr_count($first, "<") > 0 && substr_count($first, ">") > 0)
                                                                {
                                                                    $tit1 = str_replace(array("<", ">"), array("", ""), $first);
                                                                    unset($list[0]);
                                                                }
                                                                
                                                                ?>
                                                            
                                                                <div class="col-12">
                                                                
                                                                    <?if(strlen($tit1) > 0):?>
                                                                        <div class="name-tit bold"><?=$tit1?></div>
                                                                    <?endif;?>

                                                                    <ul class="input-checkbox-css">

                                                                        <?if(!empty($list)):?>
                                                                    
                                                                            <?foreach($list as $arElement):?>

                                                                                <li>

                                                                                    <label class="input-checkbox-css">

                                                                                        <input class='<?if($type[1] == "y"):?>check-require<?endif;?>' name='input_<?=$arResult["ID"]?>_<?=$key?>[]' type="checkbox" value="<?=htmlspecialcharsEx($arElement)?>"><span></span><span class="text"><?=$arElement?></span>

                                                                                    </label>
                                                                                </li>
                                                                                
                                                                            <?endforeach;?>

                                                                        <?endif;?>

                                                                    </ul>

                                                                </div>
                                                            
                                                            <?endif;?>

                                                            <?if($type[0] == "select"):?>
                                                                
                                                                <?$list = explode(";", htmlspecialcharsBack($arValue));?>
                                                                
                                                                <?
                                                                $first = $list[0];
                                                                
                                                                if(substr_count($first, "<") > 0 && substr_count($first, ">") > 0)
                                                                {
                                                                    $tit2 = str_replace(array("<", ">"), array("", ""), $first);
                                                                    unset($list[0]);
                                                                }
                                                                
                                                                ?>
                                                            
                                                                <div class="col-12">

                                                                    <?if(strlen($tit2) > 0):?>
                                                                        <div class="name-tit bold"><?=$tit2?></div>
                                                                    <?endif;?>

                                                                    <div class="input">
                                                                
                                                                        <div class="form-select <?if($type[1] == "y"):?>select-require<?endif;?>">
                                                                            <div class="ar-down"></div>
                                                                            
                                                                            <div class="select-list-choose first"><span class="list-area"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_SELECT"];?></span></div>

                                                                            <div class="select-list input_<?=$arResult["ID"]?>_<?=$key?>">
                                                                                <?if(!empty($list)):?>
                                                                                    <?foreach($list as $arElement):?>
                                                                                        <label>
                                                                                            <span class="name">
                                                                                                
                                                                                                <input class="opinion" type="radio" name='input_<?=$arResult["ID"]?>_<?=$key?>' value="<?=htmlspecialcharsEx($arElement)?>">
                                                                                                <span class="text"><?=$arElement?></span>
                                                                                                
                                                                                            </span>
                                                                                        </label>
                                                                                    <?endforeach;?>
                                                                                <?endif;?>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                 
                                                                </div>
                                                            
                                                            <?endif;?>

                                                        <?endif;?>
                                                            
                                                        
                                                            
                                                    <?endforeach;?>

                                                <?endif;?>
                                            
                                            
                                            <?endif;?>


                                            <div class="col-12">
                                                <div class="input-btn">
                                                    <div class="load">
                                                        <div class="xLoader form-preload"><div class="audio-wave"><span></span><span></span><span></span><span></span><span></span></div></div>
                                                    </div>

                                                    <?

                                                        $b_options = array(
                                                            "MAIN_COLOR" => "main-color",
                                                            "STYLE" => ""
                                                        );

                                                        if(strlen($arResult["PROPERTIES"]["FORM_BUTTON_BG_COLOR"]["VALUE"]))
                                                        {

                                                            $b_options = array(
                                                                "MAIN_COLOR" => "btn-bgcolor-custom",
                                                                "STYLE" => "background-color: ".$arResult["PROPERTIES"]["FORM_BUTTON_BG_COLOR"]["VALUE"].";"
                                                            );

                                                        }

                                                    ?>

                                                    <button class="button-def <?=$b_options["MAIN_COLOR"]?> big active <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?> btn-submit"

                                                        <?if(strlen($b_options["STYLE"])):?>
                                                            style = "<?=$b_options["STYLE"]?>"
                                                        <?endif;?>

                                                        name="form-submit" type="button" 
                                                        
                                                        <?if(strlen($arResult["PROPERTIES"]["FORM_TO_LINK"]["VALUE"]) > 0):?> 
                                                            data-link='<?=$arResult["PROPERTIES"]["FORM_TO_LINK"]["VALUE"]?>' 
                                                        <?endif;?>

                                                        <?if(strlen($arResult["PROPERTIES"]["FORM_TO_LINK_NEWTAB"]["VALUE"]) > 0):?>
                                                            data-link-new='<?=$arResult["PROPERTIES"]["FORM_TO_LINK_NEWTAB"]["VALUE"]?>'
                                                        <?endif;?>
                                                    >
                                                        
                                                        <?if(strlen($arResult['PROPERTIES']['FORM_BUTTON']['VALUE']) > 0):?><?=$arResult['PROPERTIES']['FORM_BUTTON']['~VALUE']?><?else:?><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_SUBMIT"]?><?endif;?></button>
                                                </div>
                                            </div>
                                        </div>

                                        <?= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["AGREEMENT_FOR_FORMS_HTML_FROM_MODAL"]?>
                                    </div>
                                        
                                    <div class="col-12 thank">
                                        <?if(!empty($arResult['PROPERTIES']['FORM_THANKS']['VALUE'])):?>
                                            <?=$arResult['PROPERTIES']['FORM_THANKS']['~VALUE']['TEXT']?>
                                        <?else:?>
                                            <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_THANK"]?>
                                        <?endif;?>
                                    </div>
                                    
                                    
                                </td>
                            </tr>
                        </table>
                    </form>

                    <?CPhoenix::admin_setting($arResult, false)?>
                </div>

                <?if(strlen($arResult["PROPERTIES"]['TITLE_COMMENT']['VALUE']) > 0 || strlen($arResult["PREVIEW_TEXT"]) > 0 || strlen($arResult["DETAIL_PICTURE"]) > 0 ):?>

                    <div class="form-modal-cell part-more <?if(strlen($arResult["PROPERTIES"]['TITLE_COMMENT']['VALUE']) > 0 || strlen($arResult["PREVIEW_TEXT"]) > 0):?>visible-xs <?else:?>hidden<?endif;?> <?=$arResult["PROPERTIES"]["FORM_TEXT_TITLE_COLOR"]["VALUE_XML_ID"]?>" <?if(strlen($arResult["DETAIL_PICTURE"]) > 0):?> style="background-image: url('<?=$img["src"]?>')"<?endif;?>>

                        <?if(strlen($arResult["PROPERTIES"]['TITLE_COMMENT']['VALUE']) > 0):?>

                            <div class="comment main1">
                                <?=$arResult["PROPERTIES"]['TITLE_COMMENT']['~VALUE']?>
                            </div>

                        <?endif;?>

                        <?if(strlen($arResult["PREVIEW_TEXT"]) > 0):?>

                            <div class="text-content">
                                <?=$arResult["~PREVIEW_TEXT"]?>
                            </div>

                        <?endif;?>
                    </div>

                <?endif;?>
            </div>


        </div>

    </div>
</div>

<?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"]):?>
<script>
    $('[data-toggle="tooltip"]').tooltip({
        html:true
    });

    if($(".phoenix-sets-list-wrap").hasClass('on'))
        $('div.tool-settings').addClass('on');
    
</script>
<?endif;?>