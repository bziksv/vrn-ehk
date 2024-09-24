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
global $btn_view;
global $USER;
global $PHOENIX_TEMPLATE_ARRAY;
global $PHOENIX_MENU;

global $folder;
$folder = $templateFolder;

global $h1;
$h1 = 0;

$btn_view = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE'];
?>


<?
function CreateEmptyBlock($arSection)
{
    global $PHOENIX_TEMPLATE_ARRAY;
    global $folder;
    global $APPLICATION;

    include($_SERVER["DOCUMENT_ROOT"].$folder."/main/create_empty_block.php");
}

function CreateFirstSlider($arSlider)
{

        global $USER;
        global $btn_view;
        global $PHOENIX_TEMPLATE_ARRAY;
        global $PHOENIX_MENU;
        global $folder;
        global $APPLICATION;

        if($PHOENIX_MENU == 0)
            $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"] = 'hidden';

        include($_SERVER["DOCUMENT_ROOT"].$folder."/blocks/".$arSlider["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].".php");

}

function CreateHead($arItem, $show_menu, $min = false, $main_key)
{
    global $h1;
    global $folder;
    global $APPLICATION;
    global $PHOENIX_TEMPLATE_ARRAY;

    if(strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"]) > 0)
        include($_SERVER["DOCUMENT_ROOT"].$folder."/main/create_head.php");     
}



function CreateButton($arItem, $show_menu, $center = true, $view = "view-first", $btn = "def"){


    global $btn_view;
    global $PHOENIX_TEMPLATE_ARRAY;
    global $folder;
    global $APPLICATION;

    if( strlen($arItem["PROPERTIES"]["BUTTON_NAME"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["BUTTON_NAME_2"]["VALUE"]) > 0 )
        include($_SERVER["DOCUMENT_ROOT"].$folder."/main/create_button.php");   
}
?>


<?function CreateElement($arItem, $arSection, $show_menu, $key)
{

    if(strlen($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"])<=0)
    {
        
        return false;
    }

    $main_key = $key;

    global $PHOENIX_TEMPLATE_ARRAY;
    global $btn_view;
    global $PHOENIX_MENU;
    global $folder;
    global $APPLICATION;
    

    $block_name = $arItem['NAME'];

    if(strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) > 0)
        $block_name .= " (".strip_tags($arItem["PROPERTIES"]["HEADER"]["~VALUE"]).")";

    $bg_on = '';
    $style = "";
    $style2 = "";

    $class ="";
    $attr = "";
    

    if($PHOENIX_MENU == 0)
        $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"] = 'hidden';

    if(strlen($arItem["PREVIEW_PICTURE"]["SRC"]))
    {
        if($key == 0)
            $style .= "background-image: url('".$arItem["PREVIEW_PICTURE"]["SRC"]."');";
        
        
        else
        {
            $attr .= "data-src='".$arItem["PREVIEW_PICTURE"]["SRC"]."'";
            $class .= "lazyload ";
        }

        $bg_on = 'bg-on';
    }

    if(strlen($arItem["PROPERTIES"]["BACKGROUND_COLOR"]["VALUE"]) > 0)
    {
        $style .= "background-color: ".$arItem["PROPERTIES"]["BACKGROUND_COLOR"]["VALUE"].";";
        $bg_on = 'bg-on';
    }
    if(strlen($arItem["PROPERTIES"]["SHADOW"]["VALUE_XML_ID"])>0)
        $bg_on = 'bg-on';


    ?>
    
    <?if($arItem["PROPERTIES"]["COMPONENT_DESIGN"]["VALUE_XML_ID"] != "Y" && $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "component"):?>
  
            <?include($_SERVER["DOCUMENT_ROOT"].$folder."/blocks/".$arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].".php");?>
    
    <?else:?>

    	<?

        $class_block = 'block item-block '.$class.' '.$arItem["PROPERTIES"]["SHADOW"]["VALUE_XML_ID"].' '.$arItem["PROPERTIES"]["COVER"]["VALUE"];

        if(strlen($arItem["PROPERTIES"]["CLASS_BLOCK"]["VALUE"])>0)
            $class_block .= ' '.$arItem["PROPERTIES"]["CLASS_BLOCK"]["VALUE"];

        if($key == 0)
            $class_block .= ' first-bigblock phoenix-firsttype-'.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"];

        if($show_menu)
            $class_block .= ' small-block '.$bg_on;
        else
            $class_block .= ' full-block '.$bg_on;

        if($arItem["Z_INDEX"])
            $class_block .= ' z-index';


        if(!$arItem["PADDING_CHANGE"])
            $class_block .= ' padding-on';

        if($arItem["PROPERTIES"]["PARALLAX"]["VALUE_XML_ID"] == "100")
            $class_block .= ' parallax-attachment';

        if($arItem["CHANGER_BLOCK"] === 'Y')
            $class_block .= ' d-none';

        
        $show_block = true;

        if(!$arItem["HIDDEN_BLOCK_SLIDER"])
        {
            if($arItem["PROPERTIES"]["HIDE_BLOCK_LG"]["VALUE"] == "Y")
                $class_block .= ' hidden-xxl hidden-xl hidden-lg hidden-md';

            if($arItem["PROPERTIES"]["HIDE_BLOCK"]["VALUE"] == "Y")
                $class_block .= ' hidden-sm hidden-xs';
        }

        $class_block .= ' block-'.$arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"];

        if(
            $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"] > 0
            ||
            $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"] > 0
            ||
            $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"] > 0
            ||
            strlen($arItem["PROPERTIES"]["VIDEO_BACKGROUND"]["VALUE"]) > 0
        )
        {
            $class_block .= " parent-video-bg"; 

            if($arItem["PROPERTIES"]["VIDEO_BACKGROUND_HIDE_PICTURE"]["VALUE"] === 'Y')
                $class_block .= " hide-bg-img-lg"; 
            
        }

        if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y" && ($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "text"))
            $class_block .= " overflow-h";

        if(isset($arItem["ADDITIONAL"]))
            $class_block .= " block-retranslator";

        ?>

        
        <div id="block<?=$arItem["ID"]?>" 
            class="<?=$class_block?>" style="<?=$style?>" <?=$attr?> <?if($arItem["PROPERTIES"]["PARALLAX"]["VALUE_XML_ID"] == "30"):?>data-enllax-ratio=".25"<?endif;?>>

            <?if(!empty($arItem["ELEMENTS"])):?>
                <?foreach ($arItem["ELEMENTS"] as $keyElement => $arElement):?>
                    <?if(!isset($arElement["ID"])) break;?>
                    <?if($arElement["ID"] == $arItem["ID"]) continue;?>
                    <?if($arElement["PROPERTIES"]["SHOW_IN_MENU"]["VALUE"] == "Y"):?>
                        <div id="block<?=$arElement["ID"]?>"></div>
                    <?endif;?>
                <?endforeach;?>
            <?endif;?>
         
            <?if(!$arItem["CUSTOM_MARGIN_PADDING"]):?>
                <style>

                    <?if(
                        strlen($arItem["PROPERTIES"]["MARGIN_TOP"]["VALUE"])
                        || strlen($arItem["PROPERTIES"]["MARGIN_BOTTOM"]["VALUE"])
                        || strlen($arItem["PROPERTIES"]["PADDING_TOP"]["VALUE"])
                        || strlen($arItem["PROPERTIES"]["PADDING_BOTTOM"]["VALUE"])):?>

                        @media (min-width: 768px){
                            #block<?=$arItem["ID"]?>{
                                <?if(strlen($arItem["PROPERTIES"]["MARGIN_TOP"]["VALUE"])>0):?>
                                    margin-top: <?=$arItem["PROPERTIES"]["MARGIN_TOP"]["VALUE"]?>px !important;
                                <?endif;?>

                                <?if(strlen($arItem["PROPERTIES"]["MARGIN_BOTTOM"]["VALUE"])>0):?>
                                    margin-bottom: <?=$arItem["PROPERTIES"]["MARGIN_BOTTOM"]["VALUE"]?>px !important;
                                <?endif;?>

                                <?if(!$arItem["PADDING_CHANGE"]):?>

                                    <?if(strlen($arItem["PROPERTIES"]["PADDING_TOP"]["VALUE"])>0):?>
                                        padding-top: <?=$arItem["PROPERTIES"]["PADDING_TOP"]["VALUE"]?>px !important;
                                    <?endif;?>

                                    <?if(strlen($arItem["PROPERTIES"]["PADDING_BOTTOM"]["VALUE"])>0):?>
                                        padding-bottom: <?=$arItem["PROPERTIES"]["PADDING_BOTTOM"]["VALUE"]?>px !important;
                                    <?endif;?>

                                <?endif;?>
                            }

                            <?if($arItem["PADDING_CHANGE"]):?>
                                #block<?=$arItem["ID"]?> .padding-change{

                                    <?if(strlen($arItem["PROPERTIES"]["PADDING_TOP"]["VALUE"])>0):?>
                                        padding-top: <?=$arItem["PROPERTIES"]["PADDING_TOP"]["VALUE"]?>px !important;
                                    <?endif;?>

                                    <?if(strlen($arItem["PROPERTIES"]["PADDING_BOTTOM"]["VALUE"])>0):?>
                                        padding-bottom: <?=$arItem["PROPERTIES"]["PADDING_BOTTOM"]["VALUE"]?>px !important;
                                    <?endif;?>

                                }
                            <?endif;?>
                        }
                    <?endif;?>

                    <?if(
                        strlen($arItem["PROPERTIES"]["MARGIN_TOP_MOB"]["VALUE"])
                        || strlen($arItem["PROPERTIES"]["MARGIN_BOTTOM_MOB"]["VALUE"])
                        || strlen($arItem["PROPERTIES"]["PADDING_TOP_MOB"]["VALUE"])
                        || strlen($arItem["PROPERTIES"]["PADDING_BOTTOM_MOB"]["VALUE"])):?>


                        @media (max-width: 767px){

                            #block<?=$arItem["ID"]?>{
                                <?if(strlen($arItem["PROPERTIES"]["MARGIN_TOP_MOB"]["VALUE"])>0):?>margin-top: <?=$arItem["PROPERTIES"]["MARGIN_TOP_MOB"]["VALUE"]?>px !important;<?endif;?>
                                <?if(strlen($arItem["PROPERTIES"]["MARGIN_BOTTOM_MOB"]["VALUE"])>0):?>margin-bottom: <?=$arItem["PROPERTIES"]["MARGIN_BOTTOM_MOB"]["VALUE"]?>px !important;<?endif;?>
                                
                                <?if(strlen($arItem["PROPERTIES"]["PADDING_TOP_MOB"]["VALUE"])>0):?>padding-top: <?=$arItem["PROPERTIES"]["PADDING_TOP_MOB"]["VALUE"]?>px !important; <?endif;?>
                                <?if(strlen($arItem["PROPERTIES"]["PADDING_BOTTOM_MOB"]["VALUE"])>0):?>padding-bottom: <?=$arItem["PROPERTIES"]["PADDING_BOTTOM_MOB"]["VALUE"]?>px !important;;<?endif;?>
                                
                            }

                        }

                    <?endif;?>
                    
                </style>
            <?endif;?>


            


            <?if(
                $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"] > 0
                ||
                $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"] > 0
                ||
                $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"] > 0
                ||
                strlen($arItem["PROPERTIES"]["VIDEO_BACKGROUND"]["VALUE"]) > 0
            ):?>

                <?

                    if(
                        $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"] > 0
                        ||
                        $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"] > 0
                        ||
                        $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"] > 0
                    )
                    {
                        $iframeType = "file";

                        if($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"])
                            $srcMP4 = CFile::GetPath($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"]);

                        if($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"])
                            $srcWEBM = CFile::GetPath($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"]);

                        if($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"])
                            $srcOGG = CFile::GetPath($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"]);
                    }

                    
                    elseif(strlen($arItem["PROPERTIES"]["VIDEO_BACKGROUND"]["VALUE"]) > 0)
                    {
                        $iframeType = "iframe";

                        $srcYB = CPhoenix::createVideo($arItem['PROPERTIES']['VIDEO_BACKGROUND']['~VALUE']);

                    }
                ?>

                <div 
                    class="videoBG hidden-sm hidden-xs"
                    data-type = "<?=$iframeType?>"

                    <?if(isset($srcYB["SRC"]{0})):?>
                        data-srcYB = "<?=$srcYB["SRC"]?>"
                    <?endif;?>

                    <?if(strlen($srcMP4)):?>
                        data-srcMP4 = "<?=$srcMP4?>"
                    <?endif;?>

                    <?if(strlen($srcWEBM)):?>
                        data-srcWEBM = "<?=$srcWEBM?>"
                    <?endif;?>

                    <?if(strlen($srcOGG)):?>
                        data-srcOGG = "<?=$srcOGG?>"
                    <?endif;?>
                >
                </div>

                <img class="lazyload img-for-lazyload videoBG-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png">

            <?endif;?>
        
            <div class="shadow-tone"></div>
    
            
            <?if(!$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["HEADER_BG"]):?>
                <?if($key == 0):?>
                    <div class="top-shadow"></div>
                <?endif;?>
            <?endif;?>
    
            
            <?if(is_array($arItem["PROPERTIES"]["SLIDES"]["VALUE_XML_ID"]) && !empty($arItem["PROPERTIES"]["SLIDES"]["VALUE_XML_ID"])):?>
                
                <?foreach($arItem["PROPERTIES"]["SLIDES"]["VALUE_XML_ID"] as $arSlID):?>
                    <div class="corner <?=$arSlID?> hidden-md hidden-sm hidden-xs"></div>
                <?endforeach;?>
                    
            <?endif;?>
    
            
            
            <?if(!$arItem["TITLE_CHANGE"]):?>
                <?CreateHead($arItem, $show_menu, false, $main_key);?>
            <?endif;?>
            
            <div class="content <?if(($arItem["TITLE_CHANGE"]) || !(strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) > 0 || strlen($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"]) > 0)):?>no-margin<?endif;?>">
    
                <div class="<?if(!$show_menu):?>container<?endif;?>"><!-- open main container -->
                    



                    <?include($_SERVER["DOCUMENT_ROOT"].$folder."/blocks/".$arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].".php");?>
              
                   
    
                    <?if(!$arItem["BUTTON_CHANGE"] && !isset($arItem["HIDE_BUTTON"])):?>
                        <?CreateButton($arItem, $show_menu);?>
                    <?endif;?>

                   
    
                </div><!-- close main container -->
                
            
                
            </div>
    
            <?if(!empty($arItem["PROPERTIES"]["LINES"]["VALUE_XML_ID"]) && is_array($arItem["PROPERTIES"]["LINES"]["VALUE_XML_ID"])):?>
    
                <?foreach($arItem["PROPERTIES"]["LINES"]["VALUE_XML_ID"] as $line):?>
                    
                    <div class="line-ds <?=$line?>">
    
                        <div class="<?if(!$show_menu):?>container<?endif;?>">
                            <div class="ln"></div>
                        </div>
                    </div>
                
                
                <?endforeach;?>
    
            <?endif;?>

            <?if(!isset($arItem["ADMIN_CHANGE"]))
                CPhoenix::admin_setting($arItem, true);?>
            
            <?
                if(isset($arItem["ADDITIONAL"]))
                {
                    $arParams=array(
                        "IBLOCK_ID" => $arItem['IBLOCK_ID'],
                        "IBLOCK_SECTION_ID" => $arItem["ADDITIONAL"]['IBLOCK_SECTION_ID'],
                        "ID" => $arItem["ADDITIONAL"]['ID'],
                        "NAME" => $arItem["ADDITIONAL"]['NAME'],
                        "IBLOCK_TYPE_ID" => $arItem['IBLOCK_TYPE_ID'],
                        "CLASS" => "to-left"
                    );

                    CPhoenix::admin_setting_cust($arParams);
                }

            ?>

            
        </div>

    <?endif;?>

    
<?}?>

<?ob_start();?>

    <?if(!empty($arResult["ITEMS"])):?>

        <?  
            $elements = count($arResult["ITEMS"]);
            $counter = 0;
            $inner_menu = false;
            $show_menu = false;
            if( !empty($arResult["MENU"]) || $arResult["SECTION"]["PHX_BANNERS_ISSET"] || $arResult["SECTION"]["UF_SIDEMENU_HTML"])
                $inner_menu = true;
        ?>

        
        
        <?foreach($arResult["ITEMS"] as $key=>$arItem):?>

            <?global $USER;?>
            <?global $btn_view?>


            <?if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "first_block"):?>
    
                <?CreateFirstSlider($arItem);?>

            <?else:?>

                <?if($counter == 1 && $inner_menu):?>

                    <?$show_menu = true;?>

                    <div class="container content-container constructor-content">
                        <!-- open content-container -->

                        <?
                       
                            $class1 = 'order-first';
                            $class2 = 'order-last';

                            if(strlen($arResult["SECTION"]["UF_PHX_INMENU_POS"])>0){
                                if($arResult["SECTION"]["UF_PHX_INMENU_POS_RES"]['XML_ID'] == 'right')
                                {
                                    $class1 = 'order-last';
                                    $class2 = 'order-first';
                                }
                            }

                        ?>

                        <div class="row">
                            <!-- open row menu -->
                        
                            <div class="col-lg-3 hidden-md hidden-sm hidden-xs <?=$class1?> parent-fixedSrollBlock">

                                <div class="sidemenu-container">

                                    <div class="wrapperWidthFixedSrollBlock">

                                        <div class="selector-fixedSrollBlock menu-navigation" id='navigation'>

                                            <div class="selector-fixedSrollBlock-real-height menu-navigation-inner">

                                                <?if(!empty($arResult["MENU"])):?>

                                                    <div class="row">

                                                        <ul class='nav'>
                                                            <?foreach($arResult["MENU"] as $menu):?>
                                                                

                                                            <li class='col-12 <?if(strlen($menu['ICON'])>0 || strlen($menu['PICTURE'])>0):?> on-ic<?endif;?> <?if($menu["HIDE_LG"] == "Y") echo 'hidden-xxl hidden-xl '; if($menu["HIDE"] == "Y") echo 'hidden-lg hidden-md hidden-sm hidden-xs';?>'>

                                                                <a href="#block<?=$menu['ID']?>" class='nav-link scroll'>

                                                                <?if(strlen($menu['PICTURE'])>0):?>
                                                                    <?$img = CFile::ResizeImageGet($menu["PICTURE"], array('width'=>20, 'height'=>20), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>
                                                                    <img class="lazyload" data-src="<?=$img["src"]?>" alt='<?=$menu['NAME']?>' />
                                                                <?elseif(strlen($menu['ICON'])>0):?>
                                                                    <i class="<?=$menu['ICON']?>" style='color: <?=$menu['ICON_COLOR']?>;'></i>
                                                                <?endif;?>

                                                                <span class="text"><?=$menu['NAME']?></span>

                                                            </a></li>

                                                            <?endforeach;?>
                                                    
                                                        </ul>

                                                    </div>

                                                <?endif;?>

                                                <?if($arResult["SECTION"]["UF_SIDEMENU_HTML"]):?>
                                                    <div class="sidemenuHTML"><?=$arResult["SECTION"]["~UF_SIDEMENU_HTML"]?></div>
                                                <?endif;?>

                                                <?if(is_array($arResult["SECTION"]["UF_PHX_BANNERS"]) && !empty($arResult["SECTION"]["UF_PHX_BANNERS"])):?>
                                                    #DYN_LEFT_BANNERS#
                                                <?endif;?>

                                                    

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-9 col-12 content-inner <?=$class2?>">
                                <!-- open content-inner -->

                        

                <?endif;?>

            
                <?CreateElement($arItem, $arResult["SECTION"], $show_menu, $key);?>

                <?if((($counter+1) == $elements) && $inner_menu):?>

                            </div>
                            <!-- close content-inner -->

                        </div>
                        <!-- close row menu -->
                        
                    </div><!-- close content-container -->

                    

                <?endif;?>
            
            <?endif;?>

            <?$counter++;?>
            
        
        <?endforeach;?>


        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]["VALUE"]["LAND"] === 'Y' && !$arResult["SECTION"]["UF_HIDE_PRODUCTS_VIEWED"]):?>
            <div class="catalog-stories-ajax" data-count="4"></div>
        <?endif;?>


    <?else:?>

        <?CreateEmptyBlock($arResult["SECTION"]);?>

    <?endif;?> 
    



<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>