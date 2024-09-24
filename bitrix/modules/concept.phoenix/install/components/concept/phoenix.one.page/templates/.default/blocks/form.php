<?

if($arItem["PROPERTIES"]["FORM_TEXT_TITLE_COLOR"]["VALUE_XML_ID"] == "")
    $arItem["PROPERTIES"]["FORM_TEXT_TITLE_COLOR"]["VALUE_XML_ID"] = "dark";


if(strlen($arItem["PROPERTIES"]["FORM_BACKGROUND"]["VALUE"])>0)
    $bg_form = CFile::ResizeImageGet($arItem["PROPERTIES"]["FORM_BACKGROUND"]["VALUE"], array('width'=>700, 'height'=>700), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);


if($arItem["PROPERTIES"]["FORM_IMAGE"]["VALUE"] > 0)      
    $img = CFile::ResizeImageGet($arItem["PROPERTIES"]["FORM_IMAGE"]["VALUE"], array('width'=>900, 'height'=>1500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);


$timer_on = false;

if($arItem["PROPERTIES"]["FORM_TIMER_ON"]["~VALUE"] == 'Y')
    $timer_on = true;


if($arItem["PROPERTIES"]["FORM_ADMIN"]["VALUE_XML_ID"] == "")
    $arItem["PROPERTIES"]["FORM_ADMIN"]["VALUE_XML_ID"] = "light";


if($arItem["PROPERTIES"]["FORM_TEXT_COLOR"]["VALUE_XML_ID"] == "")
    $arItem["PROPERTIES"]["FORM_TEXT_COLOR"]["VALUE_XML_ID"] = "dark";


if($arItem["PROPERTIES"]["FORM_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"] == "")
    $arItem["PROPERTIES"]["FORM_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"] = "left";


$position_hor = $arItem["PROPERTIES"]["FORM_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"];

if($position_hor == "left")
    $position_hor = "order-first";

if($position_hor == "right")
    $position_hor = "order-last";


if( $arItem["PROPERTIES"]["FORM_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "" )
    $arItem["PROPERTIES"]["FORM_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] = "order-first-mob";


if($arItem["PROPERTIES"]["FORM_IMAGE_POSITION"]["VALUE_XML_ID"] == "")
    $arItem["PROPERTIES"]["FORM_IMAGE_POSITION"]["VALUE_XML_ID"] = "middle";

$position_vert = $arItem["PROPERTIES"]["FORM_IMAGE_POSITION"]["VALUE_XML_ID"]; 

if($position_vert == "top")
    $position_vert = "align-self-start";

if($position_vert == "middle")
    $position_vert = "align-self-center";

if($position_vert == "bottom")
    $position_vert = "align-self-end";


$text_ini = false;
if(strlen($arItem["PROPERTIES"]["FORM_IMAGE"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["FORM_TEXT_TITLE"]["~VALUE"]) > 0 || !empty($arItem["PROPERTIES"]["FORM_TEXT_UNDER_TITLE"]["~VALUE"]))
    $text_ini = true;

if($show_menu)
{
	$colForm = "col-xl-5 col-md-6";
	$colText = "col-md-6";
}
else
{
	$colForm = "col-md-auto";
	$colText = "col-md-auto";
}

$ymWizard = "ym-record-keys";
?>



<div class="form-block">
    
    <div class="form-table row no-gutters justify-content-center">

        <?if($text_ini):?>
            
            <div class="<?=$colText?> col-12 form-cell image-part z-image <?=$position_hor?> <?=$position_vert?> <?=$arItem["PROPERTIES"]["FORM_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"]?>">

                <div class="width-control-responsive">

                    <?if(strlen($arItem["PROPERTIES"]["FORM_TEXT_TITLE"]["~VALUE"]) > 0 || (!empty($arItem["PROPERTIES"]["FORM_TEXT_UNDER_TITLE"]["~VALUE"])) ):?>


                        <div class="text-wrap <?=$position_hor?> <?=$arItem["PROPERTIES"]["FORM_TEXT_TITLE_COLOR"]["VALUE_XML_ID"]?>">
                            <?if($arItem["PROPERTIES"]["FORM_UPLINE"]["VALUE"] == 'Y'):?><div class="line main-color"></div><?endif;?>

                            <?if(strlen($arItem["PROPERTIES"]["FORM_TEXT_TITLE"]["~VALUE"]) > 0):?><div class="form-text-title bold"><?=$arItem["PROPERTIES"]["FORM_TEXT_TITLE"]["~VALUE"]?></div><?endif;?>

                            <?if(!empty($arItem["PROPERTIES"]["FORM_TEXT_UNDER_TITLE"]["~VALUE"])):?><div class="form-text-under-title italic"><?=$arItem["PROPERTIES"]["FORM_TEXT_UNDER_TITLE"]["~VALUE"]["TEXT"]?></div><?endif;?>

                        </div>

                    <?endif;?>
                    
                    <?if($arItem["PROPERTIES"]["FORM_IMAGE"]["VALUE"] > 0):?>
                        <img class="lazyload form-img" data-src="<?=$img["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["FORM_IMAGE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["FORM_IMAGE"]["DESCRIPTION"]:"";?>"/>
                    <?endif;?>
                </div>
            
            </div>
         
            
        <?endif;?>

        <div class="<?=$colForm?> col-12 form-cell text-part z-text <?=($arItem["PROPERTIES"]["FORM_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"]=="left")? "side-right":"";?> <?=($arItem["PROPERTIES"]["FORM_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "order-last-mob" )? "first":"";?> <?=(!$text_ini)? "one-col":"";?>">

            <form id = "form-<?=$arItem['ID']?>" action="/" class="form-<?=$arItem['ID']?> form send<?if($timer_on):?> timer_form<?endif;?> <?=$arItem["PROPERTIES"]["FORM_TEXT_COLOR"]["VALUE_XML_ID"]?> lazyload" enctype="multipart/form-data" method="post" role="form" data-src="<?=$bg_form["src"]?>" style="background-color: <?=$arItem["PROPERTIES"]["FORM_BGC"]["VALUE"]?>;">

                <?/*<input name="element" type="hidden" value="<?=$arItem["ID"]?>">*/?>
                <input name="form_name" type="hidden" value="<?=htmlspecialcharsEx($arItem["NAME"])?>">
                <input name="header" type="hidden" value="<?=$block_name?>">
                
                <input type="hidden" name="form_admin" value="<?=$arItem["PROPERTIES"]["FORM_ADMIN"]["VALUE_XML_ID"]?>" />

                


                <table class="wrap-act">
                    <tr>
                        <td>

                            <div class="questions active">


                                <?if(strlen($arItem["PROPERTIES"]["FORM_TITLE"]["VALUE"]) > 0):?>

                                    <div class="title main1 col-12">
                                        <?=$arItem["PROPERTIES"]["FORM_TITLE"]["~VALUE"]?>
                                    </div>

                                <?endif;?>

                                <?if(strlen($arItem["PROPERTIES"]["FORM_SUBTITLE"]["VALUE"]) > 0):?>

                                    <div class="subtitle col-12">
                                        <?=$arItem["PROPERTIES"]["FORM_SUBTITLE"]["~VALUE"]?>
                                    </div>

                                <?endif;?>

                                <?if($timer_on):?>
                                    <div class="col-12">
                                        <input type="hidden" class="timerVal" value="<?=$arItem["PROPERTIES"]["FORM_TIMER_SHOW"]["VALUE"]?>">
                                        <input type="hidden" class="forCookieTime" value="<?=$arItem["PROPERTIES"]["FORM_TIMER_HIDE"]["VALUE"]?>">
                                        <input type="hidden" class="idSect" value="<?=$arSection["ID"]?>">
                                       

                                        <div class="phoenixtimer">

                                            <div class="numbers bold">
                                                <div class="timer-part timer_left">
                                                    <span class='t-top'>{hnn}</span>
                                                    <span class='t-bot'>{hl}</span>
                                                </div>
                                                <div class="sep">:</div>
                                                <div class="timer-part timer_center">
                                                    <span class='t-top'>{mnn}</span>
                                                    <span class='t-bot'>{ml}</span>
                                                </div>
                                                <div class="sep">:</div>
                                                <div class="timer-part timer_right">
                                                    <span class='t-top'>{snn}</span>
                                                    <span class='t-bot'>{sl}</span>
                                                </div>
                                            </div>
                                    

                                        </div>

                                    </div>

                                <?endif;?>

                                <div class="main-inuts">
                                    <div class="col-12">
                                        <div class="input">
                                            <div class="bg"></div>
                                            <span class="desc">Name</span>
                                            <input class='focus-anim input-name' name="name" type="text">
                                            
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input">
                                            <div class="bg"></div>
                                            <span class="desc">Email</span>
                                            <input class='focus-anim input-name' name="email" type="email">
                                            
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input">
                                            <div class="bg"></div>
                                            <span class="desc">Phone</span>
                                            <input class='focus-anim input-name' name="phone" type="tel">
                                            
                                        </div>
                                    </div>
                                </div>
                                    
                                    
                                <?if($arItem["PROPERTIES"]["FORM_ADMIN"]["VALUE_XML_ID"] == "light" || $arItem["PROPERTIES"]["FORM_ADMIN"]["VALUE_XML_ID"] == ""):?>
                            

                                    <?if($arItem["PROPERTIES"]["FORM_RADIOCHECK"]["VALUE_XML_ID"] == "radio" || $arItem["PROPERTIES"]["FORM_RADIOCHECK"]["VALUE_XML_ID"] == "check"):?>

                                        <div class="col-12">

                                            <?if(strlen($arItem["PROPERTIES"]["FORM_LIST_TITLE"]["VALUE"]) > 0):?>

                                                <div class="name-tit bold">
                                                    <?=$arItem["PROPERTIES"]["FORM_LIST_TITLE"]["~VALUE"]?>
                                                </div>

                                            <?endif;?>

                                             <?if($arItem["PROPERTIES"]["FORM_RADIOCHECK"]["VALUE_XML_ID"] == "radio" && is_array($arItem["PROPERTIES"]["FORM_LIST"]["VALUE"]) && !empty($arItem["PROPERTIES"]["FORM_LIST"]["VALUE"])):?>

                                                    <ul class="input-radio-css">

                                                        <?foreach($arItem["PROPERTIES"]["FORM_LIST"]["~VALUE"] as $k=>$arElement):?>

                                                            <li>

                                                                <label class="input-radio-css">

                                                                    <input <?if($k == 0):?>checked <?endif;?> name='radiobutton<?=$arItem["ID"]?>' type="radio" value="<?=$arElement?>"><span></span><?=$arElement?>

                                                                </label>
                                                            </li>

                                                        <?endforeach;?>

                                                    </ul>

                                             <?elseif ($arItem["PROPERTIES"]["FORM_RADIOCHECK"]["VALUE_XML_ID"] == "check" && is_array($arItem["PROPERTIES"]["FORM_LIST"]["VALUE"]) && !empty($arItem["PROPERTIES"]["FORM_LIST"]["VALUE"])):?>

                                                 <ul class="input-checkbox-css">

                                                    <?foreach($arItem["PROPERTIES"]["FORM_LIST"]["~VALUE"] as $k => $arElement):?>

                                                        <li>
                                                            <label class="input-checkbox-css">
                                                                <input type="checkbox" name="checkbox<?=$arItem["ID"]?>[]" value="<?=$arElement?>">
                                                                <span></span>                                                                          
                                                                <span class="text"><?=$arElement?></span>
                                                            </label>
                                                        </li>

                                                    <?endforeach;?>
                 
                                                </ul>

                                            <?endif;?>

                                            

                                        </div>

                                    <?endif;?>

                                    <?if(is_array($arItem["PROPERTIES"]["FORM_INPUTS"]["VALUE_XML_ID"]) && !empty($arItem["PROPERTIES"]["FORM_INPUTS"]["VALUE_XML_ID"])):?>

                                     

                                        <?foreach($arItem["PROPERTIES"]["FORM_INPUTS"]["VALUE_XML_ID"] as $k=>$arInput):?>

                                            <?if($arInput == "name"):?>
                                                <div class="col-12">
                                                    <div class="input">
                                                        <div class="bg"></div>
                                                        <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_NAME"]?></span>
                                                        <input class='focus-anim <?if(in_array("name", $arItem["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?> input-name' name="inp-name" type="text">
                                                        
                                                    </div>
                                                </div>
                                            <?endif;?>

                                            <?if($arInput == "phone"):?>
                                                <div class="col-12">
                                                    <div class="input">

                                                        <div class="bg"></div>
                                                        <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_PHONE"]?></span>

                                                        <input class="phone focus-anim <?if(in_array("phone", $arItem["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?> input-phone" name="inp-phone" type="text">
                                                    </div>
                                                </div>
                                            <?endif;?>

                                            <?if($arInput == "email"):?>
                                                <div class="col-12">
                                                    <div class="input">
                                                        <div class="bg"></div>
                                                        <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_EMAIL"]?></span>
                                                        <input class="focus-anim email <?if(in_array("email", $arItem["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?> input-email" name="inp-email" type="email">
                                                        
                                                    </div>
                                                </div>
                                            <?endif;?>


                                            <?if($arInput == "count"):?>

                                                <div class="col-12">
                                                    <div class="input count <?if(in_array("count", $arItem["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?>">

                                                        <div class="bg"></div>
                                                        <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_COUNT"]?></span>
                                                        <input class='focus-anim <?if(in_array("count", $arItem["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?> input-count' name="count" type="text"> <span class="plus"></span> <span class="minus"></span>
                                                    </div>
                                                </div>

                                            <?endif;?>


                                            <?if($arInput == "date"):?>
                                                <div class="col-12">
                                                    <div class="input date-wrap <?if(in_array("date", $arItem["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?>">

                                                        <div class="bg"></div>
                                                        <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_DATE"]?></span>

                                                        <input class="date focus-anim <?if(in_array("date", $arItem["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?> input-date" name="date" type="text">
                                                    </div>
                                                </div>
                                            <?endif;?>

                                            <?if($arInput == "address"):?>
                                                <div class="col-12">
                                                    <div class="input input-textarea input-textarea-address">

                                                        <div class="bg"></div>
                                                        <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_ADDRESS"]?></span>
                                                        <textarea class='focus-anim <?if(in_array("address", $arItem["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?>' name="address"></textarea>
                                                    </div>
                                                </div>
                                            <?endif;?>

                                            <?if($arInput == "textarea"):?>
                                                <div class="col-12">
                                                    <div class="input input-textarea input-textarea-text">
                                                        <div class="bg"></div>
                                                        <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_TEXTAREA"]?></span>
                                                        <textarea class='focus-anim <?if(in_array("textarea", $arItem["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?> <?=$ymWizard?>' name="text"></textarea>
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

                                                        <input class="hidden <?if(in_array("file", $arItem["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?>require<?endif;?>" name="userfile[]" type="file" multiple="">

                                                        <?if(in_array("file", $arItem["PROPERTIES"]["FORM_INPUTS_REQ"]["VALUE_XML_ID"])):?><span class="star-req"></span><?endif;?>

                                                        </label>
                                                    </div>
                                                </div>

                                            <?endif;?>

                                        <?endforeach;?>

                                    <?endif;?>
                
                                <?elseif($arItem["PROPERTIES"]["FORM_ADMIN"]["VALUE_XML_ID"] == "professional"):?>

                                    <?if(!empty($arItem["PROPERTIES"]["FORM_PROP_INPUTS"]["VALUE"]) && is_array($arItem["PROPERTIES"]["FORM_PROP_INPUTS"]["VALUE"])):?>
                                
                                        <?foreach($arItem["PROPERTIES"]["FORM_PROP_INPUTS"]["VALUE"] as $key=>$arValue):?>
                                            
                                            <?if(strlen($arValue) > 0):?>
                                                
                                                <?$type = $arItem["PROPERTIES"]["FORM_PROP_INPUTS"]["DESCRIPTION"][$key];?>
                                                
                                                <?$type = explode(";", ToLower($type));?>

                                                <?if(!empty($type) && is_array($type)):?>
                                                
                                                    <?foreach($type as $k=>$val):?>
                                                        <?$type[$k] = trim($val);?>
                                                    <?endforeach;?>

                                                <?endif;?>
                                                
                                                
                                                <?if($type[0] == "text"):?>
                                                    
                                                    <div class="col-12">
                                                        <div class="input">
                                                            <div class="bg"></div>
                                                            <span class="desc"><?=$arValue?></span>
                                                            <input class='focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arItem["ID"]?>_<?=$key?>' name="input_<?=$arItem["ID"]?>_<?=$key?>" type="text" />
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                <?endif;?>
                                                
                                                
                                                <?if($type[0] == "textarea"):?>
                                                    
                                                    <div class="col-12">
                                                        <div class="input input-textarea input_<?=$arItem["ID"]?>_<?=$key?>">
                                                            <div class="bg"></div>
                                                            <span class="desc"><?=$arValue?></span>
                                                            <textarea class='focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?>' name="input_<?=$arItem["ID"]?>_<?=$key?>"></textarea>
                                                        </div>
                                                    </div>

                                                <?endif;?>

                                                <?if($type[0] == "name"):?>
                                                
                                                    <div class="col-12">
                                                        <div class="input">
                                                            <div class="bg"></div>
                                                            <span class="desc"><?=$arValue?></span>
                                                            <input class='focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arItem["ID"]?>_<?=$key?>' name="input_<?=$arItem["ID"]?>_<?=$key?>" type="text" />
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                <?endif;?>
                                                
                                                <?if($type[0] == "email"):?>
                                                
                                                    <div class="col-12">
                                                        <div class="input">
                                                            <div class="bg"></div>
                                                            <span class="desc"><?=$arValue?></span>
                                                            <input class="focus-anim email <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arItem["ID"]?>_<?=$key?>" name="input_<?=$arItem["ID"]?>_<?=$key?>" type="email">
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                <?endif;?>
                                                
                                                <?if($type[0] == "phone"):?>
                                                       
                                                    <div class="col-12">
                                                        <div class="input">
                                                            <div class="bg"></div>
                                                            <span class="desc"><?=$arValue?></span>
                                                            <input class="phone focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arItem["ID"]?>_<?=$key?>" name="input_<?=$arItem["ID"]?>_<?=$key?>" type="text">
                                                        </div>
                                                    </div>
                                    
                                                <?endif;?>
                                                
                                                <?if($type[0] == "count"):?>
                                                                                                             
                                                    <div class="col-12">
                                                        <div class="input count <?if($type[1] == "y"):?>require<?endif;?>">
                                                            <div class="bg"></div>
                                                            <span class="desc"><?=$arValue?></span>
                                                            <input class="focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arItem["ID"]?>_<?=$key?>" name="input_<?=$arItem["ID"]?>_<?=$key?>" type="text"> <span class="plus"></span> <span class="minus"></span>
                                                        </div>
                                                    </div>
                                    
                                                <?endif;?>
                                                
                                                <?if($type[0] == "date"):?>
                                                
                                                    <div class="col-12">
                                                        <div class="input date-wrap <?if($type[1] == "y"):?>require<?endif;?>">
                                                            <div class="bg"></div>
                                                            <span class="desc"><?=$arValue?></span>
                                                            <input class="date focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arItem["ID"]?>_<?=$key?>"  name="input_<?=$arItem["ID"]?>_<?=$key?>" type="text">
                                                        </div>
                                                    </div>
                                    
                                                <?endif;?>
                                                
                                                <?if($type[0] == "password"):?>
                                                
                                                    <div class="col-12">
                                                        <div class="input">
                                                            <div class="bg"></div>
                                                            <span class="desc"><?=$arValue?></span>
                                                            <input class="focus-anim <?if($type[1] == "y"):?>require<?endif;?> <?=$ymWizard?> input_<?=$arItem["ID"]?>_<?=$key?>" name="input_<?=$arItem["ID"]?>_<?=$key?>" type="password">
                                                            
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

                                                            <input class="hidden <?if($type[1] == "y"):?>require<?endif;?>"  name="input_<?=$arItem["ID"]?>_<?=$key?>[]" type="file" multiple="">

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

                                                            <?if(!empty($list) && is_array($list)):?>

                                                                <?foreach($list as $arElement):?>

                                                                    <li>

                                                                        <label class="input-radio-css">

                                                                            <input <?if($c == 0):?>checked <?endif;?> name='input_<?=$arItem["ID"]?>_<?=$key?>' type="radio" value="<?=$arElement?>"><span></span><?=$arElement?>

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
                                                            <div class="name-tit bold"><?=$tit1?><?if($type[1] == "y"):?><span class="starrequired">&nbsp;*</span><?endif;?></div>
                                                        <?endif;?>

                                                        <ul class="input-checkbox-css">

                                                            <?if(!empty($list) && is_array($list)):?>
                                                        
                                                                <?foreach($list as $arElement):?>

                                                                    <li>

                                                                        <label class="input-checkbox-css">

                                                                            <input class="<?if($type[1] == "y"):?>check-require<?endif;?>" name='input_<?=$arItem["ID"]?>_<?=$key?>[]' type="checkbox" value="<?=$arElement?>"><span></span><span class="text"><?=$arElement?></span>

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

                                                                <div class="select-list input_<?=$arItem["ID"]?>_<?=$key?>">

                                                                    <?if(!empty($list) && is_array($list)):?>
                                                                    <?foreach($list as $arElement):?>
                                                                        <label>
                                                                            <span class="name">
                                                                                
                                                                                <input class="opinion" type="radio" name='input_<?=$arItem["ID"]?>_<?=$key?>' value="<?=$arElement?>">
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
                                                
                                            
                                                
                                        <?endforeach;;?>

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

                                            if(strlen($arItem["PROPERTIES"]["FORM_BUTTON_BG_COLOR"]["VALUE"]))
                                            {

                                                $b_options = array(
                                                    "MAIN_COLOR" => "btn-bgcolor-custom",
                                                    "STYLE" => "background-color: ".$arItem["PROPERTIES"]["FORM_BUTTON_BG_COLOR"]["VALUE"].";"
                                                );

                                            }
                                        ?>

                                        <button class="button-def <?=$btn_view?> <?=$b_options["MAIN_COLOR"]?> big active btn-submit" 

                                            <?if(strlen($b_options["STYLE"])):?>
                                                style = "<?=$b_options["STYLE"]?>"
                                            <?endif;?>

                                            name="form-submit"
                                            type="button"

                                            <?if(strlen($arItem["PROPERTIES"]["FORM_TO_LINK"]["VALUE"]) > 0):?>
                                                data-link='<?=$arItem["PROPERTIES"]["FORM_TO_LINK"]["VALUE"]?>'<?endif;?>

                                            <?if(strlen($arItem["PROPERTIES"]["FORM_TO_LINK_NEWTAB"]["VALUE"]) > 0):?>
                                                data-link-new='<?=$arItem["PROPERTIES"]["FORM_TO_LINK_NEWTAB"]["VALUE"]?>'<?endif;?>
                                        >

                                            <?if(strlen($arItem['PROPERTIES']['FORM_BUTTON']['VALUE']) > 0):?>
                                                <?=$arItem['PROPERTIES']['FORM_BUTTON']['~VALUE']?>
                                            <?else:?>
                                                <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_SUBMIT"]?>
                                            <?endif;?>
                                                
                                        </button>
                                    </div>

                                    <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["AGREEMENT_FOR_FORMS_HTML"]?>
                                </div>
                                
                            </div>

                            <div class="thank col-12">
                                <?if(!empty($arItem['PROPERTIES']['FORM_THANKS']['VALUE'])):?>
                                    <?=$arItem['PROPERTIES']['FORM_THANKS']['~VALUE']["TEXT"]?>
                                <?else:?>
                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TEMPL_THANK"]?>
                                <?endif;?>
                            </div>

                            <?if($timer_on):?>

                                <div class="timeout_text col-12">
                                    <?if(!empty($arItem['PROPERTIES']['FORM_TIMER_TEXT']['VALUE'])):?>
                                        <?=$arItem['PROPERTIES']['FORM_TIMER_TEXT']['~VALUE']["TEXT"]?>
                                    <?else:?>
                                        <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FORM_TIMEOUT"]?>
                                    <?endif;?>
                                </div>

                            <?endif;?>
                            
                        </td>
                    </tr>
                </table>
                
            </form>

        </div>

    </div>
    
</div>