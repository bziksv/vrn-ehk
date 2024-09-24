<?
if(!defined('CONCEPT_PHOENIX_MODULE_ID')){
    define('CONCEPT_PHOENIX_MODULE_ID', 'concept.phoenix');
}


IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/classes/general/CPhoenix.php");
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option as Option;
use \Bitrix\Main\Page\Asset as Asset;
use Bitrix\Main\Loader,
    Bitrix\Main\ModuleManager,
    Bitrix\Iblock,
    Bitrix\Catalog,
    Bitrix\Currency,
    Bitrix\Currency\CurrencyTable,
    Bitrix\Main,
    Bitrix\Sale,
    Bitrix\Sale\Order,
    Bitrix\Main\Application,
    Bitrix\Sale\DiscountCouponsManager;


class CPhoenixTemplate{

    public static function hiddenInputsFormHTML()
    {?>

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
        
    <?}

    public static function afterEpilogClearComposite()
    {
        global $PHOENIX_TEMPLATE_ARRAY;
        if($PHOENIX_TEMPLATE_ARRAY["BINDEX_BOT"])
        {
            $staticHtmlCache = \Bitrix\Main\Data\StaticHtmlCache::getInstance();
            $staticHtmlCache->deleteAll();
        }
    }
    public static function start($arParams = array())
    {

        global $PHOENIX_TEMPLATE_ARRAY, $APPLICATION;

        if(!SITE_ID)
            die();

        CPhoenix::phoenixOptionsValues(SITE_ID, array(
            "start",
            "design",
            "contacts",
            "menu",
            "footer",
            "catalog",
            "shop",
            "blog",
            "news",
            "actions",
            "lids",
            "services",
            "politic",
            "customs",
            "other",
            "search",
            "catalog_fields",
            "compare",
            "brands",
            "personal",
            "rating",
            "region",
            "comments"
        ));

        CPhoenixTemplate::checkReconstruction();
        CPhoenixTemplate::setGlobalsParams();
        CPhoenixTemplate::setStylesAndScripts();
    }

    public static function setGlobalsParams()
    {
        $GLOBALS["SHOW_PHOENIX_SEO"] = "Y";
        $GLOBALS["IS_CONSTRUCTOR"] = CPhoenix::IsConstructor("concept_phoenix_site_".SITE_ID);
    }

    public static function getMainColorPath()
    {
        global $PHOENIX_TEMPLATE_ARRAY;

        $cur_color = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["MAIN_COLOR"]['VALUE'];

        if(strlen($cur_color) <= 4)
        {
            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["MAIN_COLOR_STD"]['VALUE']) > 0)
                $cur_color = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["MAIN_COLOR_STD"]['VALUE'];
        }


        if(preg_match("/^rgb/", $cur_color))
        {
            $cur_color = str_replace(array("rgba", "rgb","(",")", ";"), array("","","","",""), $cur_color);
            $cur_color = explode(",", $cur_color);
            $cur_color = CPhoenix::convertRgb2hex($cur_color);
        }

        $name_color = str_replace(array("#"), array(""), $cur_color);


        $file_less = $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/css/main_color.less";

        $dir = SITE_TEMPLATE_PATH."/css/generate_colors/site/";

        $dir_abs = $_SERVER["DOCUMENT_ROOT"].$dir;

        $newfile_css = $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/css/generate_colors/site/main_color_".$name_color.".css";

        $flag = false;



        if(!file_exists($newfile_css))
        {
            DeleteDirFilesEx($dir);
            $flag = true;
        }


        if($flag)
        {

            CheckDirPath($dir_abs);

            require ($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lessc.inc.php");

            $less = new lessc;
            $less->setVariables(array(
                "color" => $cur_color
            ));

            $less->compileFile($file_less, $newfile_css);
        }

        return SITE_TEMPLATE_PATH."/css/generate_colors/site/main_color_".$name_color.".css";
    }

    public static function checkReconstruction()
    {
        global $PHOENIX_TEMPLATE_ARRAY, $APPLICATION;

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SITE_BUILD_ON"]['VALUE']["ACTIVE"] == "Y" && !$PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"])
        {
            $bIsMainPage = $APPLICATION->GetCurPage(false) == SITE_DIR;

            if(!$bIsMainPage)
                LocalRedirect(SITE_DIR);


            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SITE_BUILD_LINK"]["VALUE"])>0)
                require_once($_SERVER["DOCUMENT_ROOT"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SITE_BUILD_LINK"]["VALUE"]);
            
            else
                echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SITE_BUILD_TEXT"]["~VALUE"];

            die();
        }
    }


    public static function setStylesAndScripts()
    {
        global $PHOENIX_TEMPLATE_ARRAY, $PhoenixCssFullList, $PhoenixJSFullList;

        $go = true;



        if($PHOENIX_TEMPLATE_ARRAY["BINDEX_BOT"])
            $go = false;
        

        $PhoenixCssList = Array();
        $PhoenixCssTrueList = Array();


        $PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/bootstrap.min.css";
        $PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/header.css";
        $PhoenixCssTrueList[] = SITE_TEMPLATE_PATH."/css/main_responsive.css";
        $PhoenixCssTrueList[] = SITE_TEMPLATE_PATH."/css/header_responsive.css";
        
        if($go)
        {
            CJSCore::Init(array('ajax', 'popup', 'fx', 'currency'));
            \Bitrix\Main\Loader::includeModule('currency');

            
            $PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/font-awesome.css";
            $PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/animate.min.css";
            $PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/xloader.css";
            $PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/blueimp-gallery.min.css";
            $PhoenixCssList[] = SITE_TEMPLATE_PATH."/slick/slick.css";
            $PhoenixCssList[] = SITE_TEMPLATE_PATH."/slick/slick-theme.css";
            $PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/jquery.datetimepicker.min.css";
            $PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/farbtastic.css";
            $PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/concept.css";
            
            $PhoenixCssList[] = SITE_TEMPLATE_PATH."/css/main.css";

            $PhoenixCssTrueList[] = SITE_TEMPLATE_PATH."/css/jquery.countdown.css";
            $PhoenixCssTrueList[] = SITE_TEMPLATE_PATH."/css/responsive.css";
            $PhoenixCssTrueList[] = CPhoenixTemplate::getMainColorPath();


            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jqueryConcept.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/bootstrap.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/bootstrap.bundle.min.js";

            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery.plugin.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery.countdown.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/lang/ru/jquery.countdown-ru.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/device.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/wow.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery.enllax.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery.maskedinputConcept.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery.blueimp-gallery.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/slick/slick.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/lang/ru/jquery.datetimepicker.full.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/typed.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/sly.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/lazyload.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery.zoom.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/jquery-ui.min.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/subscribe.js";

            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/script.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/forms.js";
            $PhoenixJSFullList[] = SITE_TEMPLATE_PATH."/js/custom.js";

            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA"]["VALUE"]["ACTIVE"]=="Y" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SITE_KEY"]["VALUE"])>0)
            {
                Asset::getInstance()->addJs('https://www.google.com/recaptcha/api.js?render='.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SITE_KEY"]["VALUE"]);
            }

            


            CPhoenixTemplate::setFonts();


            $mainStyles = "";
            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SCROLLBAR"]['VALUE']["ACTIVE"]=="Y")
                $mainStyles .= "
                ::-webkit-scrollbar{ 
                    width: 0px; 
                }";

            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_PERCENT"]['VALUE']["ACTIVE"]=="Y")
                $mainStyles .= "
                    div.cart-info-block div.wrapper-discount span.actual-discount,
                    div.catalog-list.FLAT div.item span.sale{
                        display: none;
                    }
                ";

            $mainStyles = (strlen($mainStyles)>0)?"<style>".$mainStyles."</style>":"";

            echo $mainStyles;

        }

        $PhoenixCssFullList = array_merge($PhoenixCssList, $PhoenixCssTrueList);

        if(!empty($PhoenixCssList))
        {
            foreach($PhoenixCssList as $css)
                Asset::getInstance()->addCss($css);
        }
        if(!empty($PhoenixCssTrueList))
        {
            foreach($PhoenixCssTrueList as $css)
                Asset::getInstance()->addCss($css, true);
        }
        if(!empty($PhoenixJSFullList))
        {
            foreach($PhoenixJSFullList as $js)
                Asset::getInstance()->addJs($js);
        }

        if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"])
        {
            Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/css/fonts/fontAdmin.css", true);
            Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/css/settings.css");
            Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/farbtastic.js");
            Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/zero-clipboard.js");
            Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/settings.js");
        }
           
    }

    public static function setFonts(){
        global $PHOENIX_TEMPLATE_ARRAY, $APPLICATION;

        //standart fonts
        $arStandart = array();

        $arStandart[] = "arial";


        //include
        $arInclude = Array();

        $arInclude[] = "lato";
        $arInclude[] = "helvetica";
        $arInclude[] = "segoeUI";


        //Google fonts
        $arGoogle = Array();

        $arGoogle["elmessiri"] = "El+Messiri:400,700";
        $arGoogle["exo2"] = "Exo+2:400,700";
        $arGoogle["ptserif"] = "PT+Serif:400,700";
        $arGoogle["roboto"] = "Roboto:300,400,700";
        $arGoogle["yanonekaffeesatz"] = "Yanone+Kaffeesatz:400,700";
        $arGoogle["firasans"] = "Fira+Sans+Condensed:300,700";
        $arGoogle["arimo"] = "Arimo:400,700";
        $arGoogle["opensans"] = "Open+Sans:400,700";


        if(in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE'], $arInclude))
        {
            $font[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE']] = true;
            $PhoenixCssListOther[] = SITE_TEMPLATE_PATH."/css/fonts/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE'].".css";
        }
        elseif(!in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE'], $arStandart) && !in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE'], $arInclude))
        {
            $font[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE']] = true;
            $PhoenixCssListOther[] = "https://fonts.googleapis.com/css?family=".$arGoogle[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE']]."&amp;subset=cyrillic";
        }

        if(in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE'], $arInclude) && !$font[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE']]){
            $PhoenixCssListOther[] = SITE_TEMPLATE_PATH."/css/fonts/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE'].".css";
        }
        elseif(!in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE'], $arStandart) && !in_array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE'], $arInclude) && !$font[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE']])
        {
            $PhoenixCssListOther[] = "https://fonts.googleapis.com/css?family=".$arGoogle[$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE']]."&amp;subset=latin,cyrillic";
        }


        $PhoenixCssListOther[] = SITE_TEMPLATE_PATH."/css/fonts/title/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TITLE']['VALUE'].".css";
        $PhoenixCssListOther[] = SITE_TEMPLATE_PATH."/css/fonts/text/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONTS']['TEXT']['VALUE'].".css";

        $PhoenixCssListOther[] = SITE_TEMPLATE_PATH."/css/custom.css";

        foreach($PhoenixCssListOther as $css)
            Asset::getInstance()->addCss($css, true);


        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FAVICON']['SRC']))
        {

            if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FAVICON"]["SETTINGS"]))
                $arFile = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FAVICON"]["SETTINGS"];

            $APPLICATION->AddHeadString('<link rel="icon" href="'.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FAVICON']['SRC'].'" type="'.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FAVICON"]["SETTINGS"]["CONTENT_TYPE"].'">', false, true);

            if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FAVICON"]["SETTINGS"]["CONTENT_TYPE"] == "image/x-icon")
                $APPLICATION->AddHeadString('<link rel="shortcut icon" href="'.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FAVICON']['SRC'].'" type="'.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FAVICON"]["SETTINGS"]["CONTENT_TYPE"].'">', false, true);

        }
        else
            $APPLICATION->AddHeadString('<link rel="icon" href="'.SITE_TEMPLATE_PATH.'/favicon.png" type="image/png">');   
    }

    public static function contactHTML($regions = false){
        global $PHOENIX_TEMPLATE_ARRAY;
        ?>


        <div class="wrapper-board-contact parent-show-board-contact-js <?=($regions)?"regions":""?>">
                                                            
        <?  
            $comment = $contact_text = $email_text ="";
            $show_list = false;
            
            
            $contact_text = (isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][0]['name']))?$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][0]['name']:"";

            if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']))
                $email_text = 
                    "
                        <a class='visible-part mail' href='mailto:"
                            .$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']
                            ."'>

                            <span class='bord-bot'>"
                                .$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['name']
                            ."</span>
                        </a>
                    ";

            $comment = (isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][0]['desc']))?$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][0]['desc']:"";



            if( strlen($contact_text) )
            {
                if(strlen($comment)<=0)
                    $comment = $email_text;
                
                $email_text = "";
            }


            if( strlen($comment)<=0 )
                $comment = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][0]['desc'];


            
            if($regions)
                $comment = "<span class=\"opacity-hover icon-simple ic-region show-popup-block value-region-main\" data-popup=\"region-form\">".strip_tags($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["~NAME"])."</span>";

            
        ?>

        <?if( strlen($contact_text) ):?>

            <div>

                <div class="visible-part phone" title="<?=strip_tags($contact_text)?>">
                    
                    <?=$contact_text?>

                    <div class="ic-open-list-contact show-board-contact-js open-list-contact"><span></span></div>
                        
                </div>

            </div>

        <?endif;?>

        <?if( strlen($email_text) ):?>

            <div>

                <div class='visible-part' title="<?=strip_tags($email_text)?>">
                    <?=$email_text?>

                    <div class="ic-open-list-contact show-board-contact-js"><span></span></div>
                </div>

            </div>

        <?endif;?>

        <?if( strlen($comment) ):?>

            <div class='comment'>
                <?=$comment?>
            </div>

        <?endif;?>

        <?if( strlen($email_text) || strlen($contact_text) ):?>

            <div class="list-contacts">

                <table>

                    <?$flagcallback = true;?>

                    <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"] as $key => $val):?>
                    
                        <tr>
                            <td>
                                <div class="phone"><span ><?=$val['name']?></span></div>
                                <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][$key]["desc"]) > 0):?>
                                    <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]["~VALUE"][$key]["desc"]?></div>
                                <?endif;?>
                            </td>
                        </tr>

                        <?if($key == 0):?>

                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]["CALLBACK"]["VALUE"] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
                                <tr class="no-border-top">
                                    <td>

                                        <div class="button-wrap">
                                            <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["HEADER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
                                        </div>
                                    </td>
                                </tr>
                            <?endif;?>

                            <?$flagcallback = false;?>

                        <?endif;?>

                    <?endforeach;?>

                    <?if($flagcallback):?>

                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
                            <tr>
                                <td>

                                    <div class="button-wrap">
                                        <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["HEADER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
                                    </div>
                                </td>
                            </tr>
                        <?endif;?>

                    <?endif;?>


                    <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"] as $key => $val):?>

                        <tr>
                            <td>
                                <div class="email"><a href="mailto:<?=$val['name']?>"><span class="bord-bot"><?=$val['name']?></span></a></div>
                                <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][$key]["desc"]) > 0):?>
                                    <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]["VALUE"][$key]["desc"]?></div>
                                <?endif;?>
                            </td>
                        </tr>

                    <?endforeach;?>

                    <?if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['VALUE']) || strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE']) ):?>
                        <tr>
                            <td>
                                <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE'])):?>

                                    <div class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS']['VALUE']?></div>

                                <?endif;?>


                                <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['VALUE'])):?>

                                    
                                    <a class="btn-map-ic show-dialog-map"><i class="concept-icon concept-location-5"></i> <span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CONTACTS_DIALOG_MAP_BTN_NAME"]?></span></a>
                                    

                                <?endif;?>
                            </td>
                        </tr>
                    <?endif;?>

                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]["VALUE"]["CONTACT"] == 'Y' && $PHOENIX_TEMPLATE_ARRAY["DISJUNCTIO"]["SOC_GROUP"]["VALUE"]):?>
                        <tr>
                            <td>
                                <?CPhoenix::CreateSoc($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"])?>
                            </td>
                        </tr>
                    
                    <?endif;?>
                        
                </table>
            </div>

        <?endif;?>

    </div>

    <?}
}