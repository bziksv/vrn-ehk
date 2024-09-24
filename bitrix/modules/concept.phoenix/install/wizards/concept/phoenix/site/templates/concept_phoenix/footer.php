<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;

?>

    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG"]["SRC"])>0 || $KRAKEN_TEMPLATE_ARRAY["ITEMS"]["BODY_BG_CLR"]["VALUE"] !="transparent"):?>
        <style>
            body{
                <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG"]["SRC"])>0):?>background-image: url(<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG"]["SRC"]?>);<?endif;?>
                background-attachment: <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG_POS"]["VALUE"]?>;
                background-repeat: <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG_REPEAT"]["VALUE"]?>;
                background-position: top center;
                background-color: <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG_CLR"]["VALUE"]?>;
            }
        </style>
    <?endif;?>


    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_ON"]["VALUE"]["ACTIVE"] == "Y"):?>

        <?
            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_MAIN"]["VALUE"])>0 && file_exists($_SERVER["DOCUMENT_ROOT"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_MAIN"]["VALUE"]))
                include($_SERVER["DOCUMENT_ROOT"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_MAIN"]["VALUE"]);
            

            else
                include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/blocks/footer_main.php");
            
        ?>

    <?endif;?>

    
    

    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["WIDGET_FAST_CALL_ON"]["VALUE"]["ACTIVE"]):?>
        <div id="callphone-mob" class="callphone-wrap">
            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["WIDGET_FAST_CALL_DESC"]["VALUE"])>0):?>
                <span class="callphone-desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["WIDGET_FAST_CALL_DESC"]["VALUE"]?></span>
            <?endif;?>
            <a class='callphone' href='tel:<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["WIDGET_FAST_CALL_NUM"]["VALUE"]?>'></a>
        </div>
    <?endif;?>

</div>


<?

if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"] == "Y")
{
    $APPLICATION->IncludeFile(
        SITE_TEMPLATE_PATH."/include/cart.php",
        array(),
        array("MODE"=>"text")
    );
}
?>

<?$APPLICATION->IncludeComponent(
    "bitrix:system.auth.form", 
    "auth", 
    array(
        "COMPONENT_TEMPLATE" => "auth",
        "PROFILE_URL" => "/personal/",
        "SHOW_ERRORS" => "N",
        "COMPOSITE_FRAME_MODE" => "N"
    ),
    false
);?>

<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("set-area");?>
   
    <?
        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['ACTIVE']["VALUE"]["ACTIVE"] == "Y")
        {
            $APPLICATION->IncludeFile(
                SITE_TEMPLATE_PATH."/include/region.php",
                array(),
                array("MODE"=>"text")
            );
        }
    ?>

    <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"]):?>

        <?\Bitrix\Main\Data\StaticHtmlCache::getInstance()->markNonCacheable();?>

        <?$APPLICATION->IncludeFile(
            SITE_TEMPLATE_PATH."/include/alert_errors.php",
            array(),
            array("MODE"=>"text")
        );?>

        <?$APPLICATION->IncludeFile(
            SITE_TEMPLATE_PATH."/include/settings-panel.php",
            array(),
            array("MODE"=>"text")
        );?>
        
    <?endif;?>

<?CPhoenixFunc::setUTM(SITE_ID);?>

<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("set-area");?>


<a href="#body" class="up scroll"></a>



<div class="popup-slider" id="sliderPopup" data-current-image=''>
    
    <div class="gallery-container wrapper-picture">
        <div class="wrapper-big-picture"></div>
        <div class="controls-pictures"></div>
    </div>

    <a class="close-popup-slider-style" onclick = "destroyPopupGallery();"></a>
    <div class="popup-slider-nav">
        <div class="nav-item action_prev"></div>
        <div class="nav-item action_next"></div>
    </div>

</div>


<div class="modalArea shadow-modal-wind-contact"> 
    <div class="shadow-modal"></div>

    <div class="phoenix-modal window-modal">

        <div class="phoenix-modal-dialog">
            
            <div class="dialog-content">
                <a class="close-modal wind-close"></a>

                <div class="content-in">
                    <div class="list-contacts-modal">

                    <?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("popup-contacts");?>
                        <table>

                            

                                <?if($regionsIsset):?>
                                    <tr class="d-sm-none">
                                        <td>
                                            <?="<span class=\"opacity-hover icon-simple ic-region show-popup-block\" data-popup=\"region-form\">".strip_tags($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["~NAME"])."</span>";?>
                                        </td>
                                    </tr>
                                <?endif;?>

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

                                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE'] != "N" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']["VALUE"]["ACTIVE"] == "Y"):?>
                                            <tr class="no-border-top">
                                                <td>

                                                    <div class="button-wrap">
                                                        <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FOOTER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
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
                                                    <a class="button-def main-color d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]["VALUE"]?> call-modal callform" data-from-open-modal='open-menu' data-header="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FOOTER_DATA_HEADER"]?>" data-call-modal="form<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]["VALUE"]?></a>
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

                        <?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("popup-contacts");?>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<?$APPLICATION->IncludeComponent("concept:phoenix.seo", "", Array());?>

<?if(!$PHOENIX_TEMPLATE_ARRAY["BINDEX_BOT"]):?>
	<script type="text/javascript">
	    initPhoneMask();
	</script>


	<div class="loading"></div>
	<div class="loading-top-right circleG-area"><div class="circleG circleG_1"></div><div class="circleG circleG_2"></div><div class="circleG circleG_3"></div></div>


	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["LAZY_SCRIPTS"])>0):?>

	    <script>

	        $(window).on("load", function()
	        {
	            var timerService = setTimeout(function()
	            {
	                $("body").append('<?=str_replace(array("/","'", "\r\n"), array("\/", '"', ""), $PHOENIX_TEMPLATE_ARRAY["LAZY_SCRIPTS"])?>');
	                clearTimeout(timerService);

	            },  <?=intval($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SERVICE_TIME"]["VALUE"])?> * 1000);

	        });
	    </script>
	<?endif;?>

	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["USE_RUB"]["VALUE"]["ACTIVE"]=="Y"):?>

	    <script>
	        $(window).on("load", function()
	        {
	            $("body").append("<link href=\"https://fonts.googleapis.com/css?family=PT+Sans+Caption&amp;display=swap&amp;subset=latin-ext\" type=\"text/css\" rel=\"stylesheet\">");
	        });
	        
	    </script>

	<?endif;?>

	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SCRIPTS"]["VALUE"])>0):?>

	    <script>

	        $(window).on("load", function()
	        {
	            var timerService2 = setTimeout(function()
	            {

	                $("body").append('<?=str_replace(array("/","'", "\r\n"), array("\/", '"', ""), $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SCRIPTS"]["~VALUE"])?>');
	            
	                clearTimeout(timerService2);
	            },  <?=intval($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SCRIPTS_TIME"]["VALUE"])?> * 1000);

	        });
	    </script>
	<?endif;?>



	<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("css-js-area");?>
	<?=(strlen($PHOENIX_TEMPLATE_ARRAY["SITE_CONTENT_JS"]))? "<script type='text/javascript'>".$PHOENIX_TEMPLATE_ARRAY["SITE_CONTENT_JS"]."</script>":"";?>
	<?=(strlen($PHOENIX_TEMPLATE_ARRAY["SITE_CONTENT_CSS"]))? "<style>".$PHOENIX_TEMPLATE_ARRAY["SITE_CONTENT_CSS"]."</style>":"";?>
	<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("css-js-area");?>

	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>
	    <div id="alert-vote" style="display: none;"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["RATE_VOTE_SUCCESS_ALERT"]?></div>
	<?endif;?>

	

	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['STYLES']['VALUE'])>0)
	{?>
	    <style type="text/css">
	    <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['STYLES']['~VALUE']?>
	    </style>
	    
	<?}?>

	<?
	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['SCRIPTS']['VALUE'])>0)
	{?>
	    <script type='text/javascript'>
	        <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['SCRIPTS']['~VALUE'];?>
	    </script>
	<?}?>

	<?
	    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INCLOSEBODY"]['VALUE'])>0)
	        echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INCLOSEBODY"]['~VALUE'];

	    $APPLICATION->ShowViewContent("service_close_body");
	?>
<?endif;?>
</body>
</html>