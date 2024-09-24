<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?global $PHOENIX_TEMPLATE_ARRAY;?>

<?

$erFlag = false;
if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]["CALLBACK"]["VALUE"] == "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y")
{
    $PHOENIX_TEMPLATE_ARRAY["ERRORS"]["CALLBACK"] = "Y";
    
}

if(
    $PHOENIX_TEMPLATE_ARRAY["ERRORS"]["CALLBACK"] === 'Y'
    ||
    $PHOENIX_TEMPLATE_ARRAY["ERRORS"]["OFFERS"] === 'Y'
    )
    $erFlag = true;



?>

<?if($erFlag):?>
    <div class="alert-block hidden-sm hidden-xs">
        
        <div class="phoenix-alert-btn mgo-widget-alert_pulse"></div>
        
        <div class="alert-block-content">
            
            <div class="alert-head">
                <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MAIN_ALERT_HEAD"]?>
                
                <a class="alert-close"></a>
            </div>
            
            <div class="alert-body">

                
                <?if($PHOENIX_TEMPLATE_ARRAY["ERRORS"]["CALLBACK"] === "Y"):?>
                    
                    <div class="cont">
                        
                        <div class="big-name"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MAIN_ALERT_TITLE"]?></div>
                        
                        <div class="instr">
                            
                            <div class="instr-element">
                                
                                <div class="text">1. <a href="/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["IBLOCK_ID"]?>&type=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["IBLOCK_TYPE_ID"]?>&lang=<?=LANGUAGE_ID?>&find_section_section=0" target="_blank"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MAIN_ALERT_CALLBACK_TEXT_1"]?></a></div>
                                
                            </div>
                            
                            <div class="instr-element">
                                
                                <div class="text">2. <a class="phoenix-sets-open" data-open-set="edit-sets"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MAIN_ALERT_CALLBACK_TEXT_2"]?></a></div>
                                <div class="comment"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MAIN_ALERT_CALLBACK_TEXT_2_COMMENT"]?></div>
                                
                            </div>
                            
                        </div>
                        
                    </div>
                    
                <?endif;?>


                <?if($PHOENIX_TEMPLATE_ARRAY["ERRORS"]["OFFERS"] ==="Y"):?>
                    
                    <div class="cont">
                        
                        <div class="big-name"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ERRORS_OFFERS"]?></div>
                        
                    </div>
                    
                <?endif;?>


                
            </div>
            
        </div>
        
    </div>
<?endif;?>

