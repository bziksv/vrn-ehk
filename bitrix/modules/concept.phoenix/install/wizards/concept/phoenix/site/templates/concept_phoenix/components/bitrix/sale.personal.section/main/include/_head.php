<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$header_back = "";

if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["HEAD_BG_PIC"]["VALUE"] > 0)
{
    
    $img = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["HEAD_BG_PIC"]["VALUE"], array('width'=>3000, 'height'=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);  
    $header_back = $img["src"];
}

$userID = 0;

if($userID = CPhoenix::GetUserID())
{
    $rsUser = CUser::GetByID($userID);
    $arUser = $rsUser->Fetch();


    if($arUser["PERSONAL_PHOTO"])
    {
        $photo = CFile::ResizeImageGet($arUser["PERSONAL_PHOTO"], array('width'=>176, 'height'=>176), BX_RESIZE_IMAGE_EXACT, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

        $photo_src = $photo["src"];
    }
    else
        $photo_src = SITE_TEMPLATE_PATH."/images/pers_no_photo.jpg";


    $name = "";

    if(strlen($arUser["NAME"]))
    {
        $name = $arUser["NAME"];

        if(strlen($arUser["LAST_NAME"]))
            $name .= "&nbsp;".$arUser["LAST_NAME"];
    }

    if(!strlen($name))
        $name = $arUser["LOGIN"];
}





if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES_MODE"]["VALUE"] == "custom" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"]):?>
    <?
        $img = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]["VALUE"], array('width'=>800, 'height'=>900), BX_RESIZE_IMAGE_PROPORTIONAL, false);  
        $header_back_xs = $img["src"];
    ?>
    <style>
        @media (max-width: 767.98px){
            div.page-header{
                background-image: url('<?=$header_back_xs?>') !important;
            }
        }
    </style>
<?endif;?>


<div class=
		"
			page-header
			cover
			parent-scroll-down
			<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>
			phoenix-firsttype-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
			padding-bottom-section
		" 

	<?if(strlen($header_back)):?>

        style="background-image: url(<?=$header_back?>);"

    <?endif;?>

>


	<div class="shadow-tone <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>"></div>

    <div class="top-shadow"></div>

    <div class="container z-i-9">

    	<div class="row">
    		<div class="<?=($userID)? "col-xl-9 col-lg-8":""?> col-12 part part-left">
	    			
    			<div class="head margin-bottom">
                    <div class="title main1"><h1><?$APPLICATION->ShowTitle(false);?></h1></div>
    			</div>

                <?if($userID):?>

                    <div class="visible-md visible-sm visible-xs">
                        <a href="<?=SITE_DIR."?logout=yes"?>" class="logout"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_PERSONAL_LOGOUT"]?></a>
                    </div>

                <?endif;?>

    		</div>

            <?if($userID):?>

                <div class="col-xl-3 col-lg-4 hidden-md hidden-sm hidden-xs">
                    <div class="fly-personal-widget">
                    
                        <div class="wr-name"><?=$name?></div>

                        <div class="wr-bttns">

                            <div class="wr-bttn left">
                                <a href="<?=SITE_DIR."personal/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["PRIVATE"]["URL"]?>"><span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_PRIVATE_PAGE_NAME"]?></span></a>
                            </div>

                            <div class="wr-bttn right">
                                <a href="<?=SITE_DIR."?logout=yes"?>"><span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_SPS_PERSONAL_LOGOUT_WIDGET"]?></span></a>
                            </div>

                        </div>              
                        
                        <a href="<?=SITE_DIR."personal/".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]["VALUES"]["PRIVATE"]["URL"]?>"><img src="<?=$photo_src?>" alt=""/></a>
                        
                    </div>
                </div>

            <?endif;?>
    	</div>
    </div>

</div>

<?$bannersIsset = !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BANNERS"]["ITEMS"]);?>



<?$this->SetViewTarget('class-personal-menu-content');?><?=($bannersIsset)? "col-lg-6 col-md-8 with-banners":"col-lg-9";?><?$this->EndViewTarget();?>

<?$this->SetViewTarget('banners-personal-content');?>
    <?if($bannersIsset):?>
        <div class="col-lg-3 col-md-4 col-12">
            <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["FIRE_TITLE"]["VALUE"])):?>
                <div class="fire-title main-color bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["FIRE_TITLE"]["~VALUE"]?></div>
            <?endif;?>

            <?
                $GLOBALS["arrBannersSideFilter"]["ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BANNERS"]["ITEMS"];

                $APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    "banners-left",
                    array(
                        "VIEW"=>"flat",
                        "COMPONENT_TEMPLATE" => "banners-left",
                        "IBLOCK_TYPE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BANNERS']["IBLOCK_ID"],
                        "NEWS_COUNT" => "20",
                        "SORT_BY1" => "SORT",
                        "SORT_ORDER1" => "ASC",
                        "SORT_BY2" => "SORT",
                        "SORT_ORDER2" => "ASC",
                        "FILTER_NAME" => "arrBannersSideFilter",
                        "FIELD_CODE" => array(
                            0 => "DETAIL_PICTURE",
                            1 => "PREVIEW_PICTURE",
                        ),
                        "PROPERTY_CODE" => array(
                            0 => "",
                            1 => "BANNER_BTN_TYPE",
                            2 => "BANNER_ACTION_ALL_WRAP",
                            3 => "BANNER_USER_BG_COLOR",
                            4 => "BANNER_UPTITLE",
                            5 => "BANNER_BTN_NAME",
                            6 => "BANNER_TITLE",
                            7 => "BANNER_BTN_BLANK",
                            8 => "BANNER_BORDER",
                            9 => "BANNER_DESC",
                            10 => "BANNER_TEXT",
                            11 => "BANNER_LINK",
                            12 => "BANNER_COLOR_TEXT",
                            13 => "",
                        ),
                        "CHECK_DATES" => "Y",
                        "DETAIL_URL" => "",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000",
                        "CACHE_FILTER" => "Y",
                        "CACHE_GROUPS" => "Y",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "ACTIVE_DATE_FORMAT" => "d.m.Y",
                        "SET_TITLE" => "N",
                        "SET_BROWSER_TITLE" => "N",
                        "SET_META_KEYWORDS" => "N",
                        "SET_META_DESCRIPTION" => "N",
                        "SET_LAST_MODIFIED" => "N",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "INCLUDE_SUBSECTIONS" => "N",
                        "STRICT_SECTION_CHECK" => "N",
                        "DISPLAY_DATE" => "N",
                        "DISPLAY_NAME" => "N",
                        "DISPLAY_PICTURE" => "N",
                        "DISPLAY_PREVIEW_TEXT" => "N",
                        "COMPOSITE_FRAME_MODE" => "N",
                        "PAGER_TEMPLATE" => ".default",
                        "DISPLAY_TOP_PAGER" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "N",
                        "PAGER_TITLE" => "",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "SET_STATUS_404" => "N",
                        "SHOW_404" => "N",
                        "MESSAGE_404" => "",
                    ),
                    $component
                );
            ?>

        </div>
    <?endif;?>
<?$this->EndViewTarget();?>