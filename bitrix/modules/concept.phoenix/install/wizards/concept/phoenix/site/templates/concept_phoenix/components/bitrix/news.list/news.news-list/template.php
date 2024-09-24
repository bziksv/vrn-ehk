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

<?

global $PHOENIX_TEMPLATE_ARRAY;?>


<?

    $arIcon = array();

    $arIcon["blog"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_BLOG_ICON"]['VALUE'];

    $arIcon["video"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_VIDEO_ICON"]['VALUE'];
    $arIcon["interview"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_INTERVIEW_ICON"]['VALUE'];
    $arIcon["opinion"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_OPINION_ICON"]['VALUE'];
    $arIcon["case"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_CASE_ICON"]['VALUE'];


    $arrSect = array(

        "icon-default-blog" => array(
            "NAME"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_BLOG_NAME"]["VALUE"],
            "SRC" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_BLOG_ICON"]["SRC"]
        ),
        "icon-default-video" => array(
            "NAME"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_VIDEO_NAME"]["VALUE"],
            "SRC" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_VIDEO_ICON"]["SRC"]
        ),
        "icon-default-interview" => array(
            "NAME"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_INTERVIEW_NAME"]["VALUE"],
            "SRC" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_INTERVIEW_ICON"]["SRC"]
        ),
        "icon-default-opinion" => array(
            "NAME"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_OPINION_NAME"]["VALUE"],
            "SRC" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_OPINION_ICON"]["SRC"]
        ),
        "icon-default-case" => array(
            "NAME"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_CASE_NAME"]["VALUE"],
            "SRC" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_CASE_ICON"]["SRC"]
        ),
        "icon-default-sens" => array(
            "NAME"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_SENS_NAME"]["VALUE"],
            "SRC" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_SENS_ICON"]["SRC"]
        ),

    );

?>


<?if( $arParams["TYPE"] == "BLG" ):?>

    <div class="section-blog row">
        
        <?foreach($arResult["ITEMS"] as $k => $arItem):?>


            <div class="col-lg-<?=$arParams["COL_LG"]?> col-md-6 col-12">
                <div class="section-blog-item general-hover-shine">

                    <?CPhoenix::admin_setting($arItem, false)?>

                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">

                        <div class="row wr-name no-gutters align-items-center">

                            <div class="name col-12"><?=$arItem["~NAME"]?></div>

                        </div>
                        
                        <?if(strlen($arItem['PREVIEW_PICTURE']["ID"]) > 0):?>
                                        
                            <?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array('width'=>600, 'height'=>400), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                        <?endif;?>

                        <div class="picture lazyload" 
                       
                            <?/*style="background-image: url('<?=$img["src"]?>');"*/?>
                            data-src= "<?=(strlen($arItem['PREVIEW_PICTURE']["ID"]) > 0)?$img["src"]:'';?>"

                        >

                            <div class="shine"></div>
                        </div>
                   

                        

                        <div class="desc">

                            <?
                                if($arItem["PROPERTIES"]["TYPE_CONTENT"]["VALUE_XML_ID"] == "")
                                    $arItem["PROPERTIES"]["TYPE_CONTENT"]["VALUE_XML_ID"] = "blog";

                                $type_content = "icon-default-".$arItem["PROPERTIES"]["TYPE_CONTENT"]["VALUE_XML_ID"];
                            ?>

                            <div 
                                title ="<?=$arrSect[$type_content]["NAME"]?>"

                                class="section-blog-icon 
                                    lazyload 
                                    <?=$type_content?>" 

                                <?if(strlen($arrSect[$type_content]["SRC"])):?> data-src="<?=$arrSect[$type_content]["SRC"]?>"<?endif;?>
                                >
                                
                            </div>



                            <?if(strlen($arItem["ACTIVE_FROM"]) > 0):?>
                                <div class="date-format"><?echo CIBlockFormatProperties::DateFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]["VALUE"], MakeTimeStamp($arItem["ACTIVE_FROM"], CSite::GetDateFormat()));?></div>
                            <?endif;?>

                        </div>

                    </a>


                </div>

                
            </div>

        <?endforeach;?>

    </div>

<?else:?>
    <div class="news flat">

        <div class="wrap-elements">  
            
            <div class="row">

    			<?foreach($arResult["ITEMS"] as $k => $arItem):?>

    				<div class="col-lg-<?=$arParams["COL_LG"]?> col-md-6 col-12">
                        <div class="wrap-element">

                            <div class="element">

                                <?CPhoenix::admin_setting($arItem, false)?>
                            
                                <?/*<a href='<?=$arItem["DETAIL_PAGE_URL"]?>' class="wrap-link"></a>*/?>
                             
                                <table>
                                    <tr>
                                        <td>

                                            <?$img["src"] = "";?>

                                            <?if($arItem["PREVIEW_PICTURE"]["ID"]>0):?>
                                                <?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array('width'=>600, 'height'=>400), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
                                            <?endif;?>

                                            <a href='<?=$arItem["DETAIL_PAGE_URL"]?>' class='hover_shine img-wrap'>
                                                <div class='bg-img lazyload' 

                                                <?if($arItem["PREVIEW_PICTURE"]["ID"]>0):?> 

                                                    <?/*style='background-image: url(<?=$img["src"]?>);'*/?>

                                                    data-src= "<?=$img["src"]?>"

                                                <?endif;?>>

                                                    <div class="new-dark-shadow"></div>
                                              
                                                </div>
                                                <div class="shine"></div>
                                            </a>
                                     
                                        </td>
                                    </tr>
                                </table>


                                <div class="wrap-text">


                                    <?if($arItem["IBLOCK_CODE"] != $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ACTIONS']["IBLOCK_CODE"] && $arResult["PARENT_ON"] == "Y" && $arParams["HIDE_SECTIONS"] != "Y"):?>
                                        <div class="section" title='<?=$arItem['SECTION_NAME']?>'>

                                            <?

                                                $name = "";
                                                $link_news = "";

                                                if(strlen($arResult['BNA'][$arItem['IBLOCK_SECTION_ID']]['NAME'])>0)
                                                {
                                                    
                                                    $name = $arResult['BNA'][$arItem['IBLOCK_SECTION_ID']]['~NAME'];
                                                    $link_news = $arResult['BNA'][$arItem['IBLOCK_SECTION_ID']]['SECTION_PAGE_URL'];
                                                }
                                                else
                                                {
                                                    
                                                    $name_def = "NEWS";
                                                    $link_news = $PHOENIX_TEMPLATE_ARRAY["MAIN_URLS"]["news"];
                                                    
                                                    if($arItem["IBLOCK_CODE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BLOG']["IBLOCK_CODE"])
                                                    {
                                                        $name_def = "BLOG";
                                                        $link_news = $PHOENIX_TEMPLATE_ARRAY["MAIN_URLS"]["blog"];
                                                    }

                                                    $name = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATEGORY_".$name_def];
                                                }

                                            ?>

                                            <a href='<?=$link_news?>' class="wrap-link-sect"><?=$name?></a>

                                        </div>

                                    
                                    <?endif;?>


                     

                                    <?if($arItem["IBLOCK_CODE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ACTIONS']["IBLOCK_CODE"]):?>

                                        <div class="date-action">

                                            <?$frame = $this->createFrame()->begin("");?>
                                            
                                                <?if(getmicrotime() > MakeTimeStamp($arItem["ACTIVE_TO"]) && strlen($arItem["ACTIVE_TO"])>0):?>

                                                    <span class="off"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ACTIONS_ACT_OFF"];?></span>

                                                <?else:?>

                                                    <?if(strlen($arItem["ACTIVE_TO"])>0):?>

                                                        <span class="to"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ACTIONS_ACT_ON_TO"]?><?echo CIBlockFormatProperties::DateFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]["VALUE"], MakeTimeStamp($arItem["ACTIVE_TO"], CSite::GetDateFormat()));?></span>

                                                    <?else:?>

                                                        <span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ACTIONS_ACT_ON"]?></span>

                                                    <?endif;?>

                                                <?endif;?>

                                            <?$frame->end();?>

                                        </div>

                                    <?endif;?>

                                    <a href='<?=$arItem["DETAIL_PAGE_URL"]?>'>
                                        <div class="new-name bold"><?=$arItem['~NAME']?></div>
                                    </a>


                                    <?if(strlen($arItem["ACTIVE_FROM"]) > 0 && $arItem["IBLOCK_CODE"] != $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ACTIONS']["IBLOCK_CODE"]):?>
                                        <div class="date">

                                            <?echo CIBlockFormatProperties::DateFormat($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]["VALUE"], MakeTimeStamp($arItem["ACTIVE_FROM"], CSite::GetDateFormat()));?>

                                        </div>
                                        
                                    <?endif;?>


                                    <?if(strlen($arItem["~PREVIEW_TEXT"])>0):?>
                                        <a href='<?=$arItem["DETAIL_PAGE_URL"]?>'>
                                            <div class="new-text"><?=$arItem["~PREVIEW_TEXT"]?></div>
                                        </a>
                                    <?endif;?>

                                </div>

                            </div>

                            <div class="new-shadow"></div>

                        </div>


                    </div>



    				
    			<?endforeach;?>

            </div>

		</div> 
        
   
    </div>

<?endif;?>


<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <div class="clearfix"></div>
    <?=$arResult["NAV_STRING"]?>
<?endif;?>