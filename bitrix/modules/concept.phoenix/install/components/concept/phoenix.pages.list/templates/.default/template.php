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

<?global $PHOENIX_TEMPLATE_ARRAY;?>

<?if(isset($arParams['PANEL'])):?>

    <?if($arParams['PANEL'] == "edit-sets"):?>
        
        <div class="inner">

            <div class="phoenix-set-head row no-margin align-items-center">
            
                <div class="col-3 phoenix-set-image"><div></div></div>
                <div class="col-6 phoenix-set-name bold"><?=GetMessage("PHOENIX_SETTINGS_LIST_EDIT_SETS")?></div>
                <div class="col-3"></div>
                <a class="phoenix-set-close"></a>
                
            </div>

            <form action="/" class="form-sets-js set-form form-setting" enctype="multipart/form-data" method="post" role="form">
                
                <input type="hidden" name="site_id" value="<?=SITE_ID?>" />
                <input type="hidden" name="section_id" value="<?=$GLOBALS["CURRENT_SECTION_ID"]?>" />
                <input type="hidden" name="server_url" value="<?=$arResult["SERVER_URL"]?>" />

                <div class="phoenix-set-content">
                    <table class="sides">
                        <tr>
                            <td class='set-side-left'>
                                <ul class="set-tabs">
                                    <li class='active' data-set='instruct'><?=GetMessage("PHOENIX_SETTINGS_LIST_EDIT_SETS_INSTRUCT")?></li>


                                    <li class= "" data-set='disain'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["NAME"]?></li>

                                    <li data-set='contacts'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["NAME"]?></li>

                                    <li data-set='menu'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["NAME"]?></li>

                                    <li data-set='footer'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["NAME"]?></li>

                                    <li data-set='catalog'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["NAME"]?></li>

                                    <li data-set='catalog_fields'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["NAME"]?></li>

                                    <li data-set='rating'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["NAME"]?></li>

                                    <li data-set='comments'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["NAME"]?></li>

                                    <li data-set='brands'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["NAME"]?></li>

                                    <li data-set='cart'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["NAME"]?></li>

                                    <li data-set='order'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["NAME_ORDER"]?></li>

                                    <li data-set='fast_order'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["NAME_FAST_ORDER"]?></li>

                                    <li data-set='compare'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["NAME"]?></li>

                                    <li data-set='blog'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["NAME"]?></li>

                                    <li data-set='news'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["NAME"]?></li>

                                    <li data-set='action'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["NAME"]?></li>

                                    <li data-set='search'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["NAME"]?></li>

                                    <li data-set='personal'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["NAME"]?></li>

                                    <li data-set='lids'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["NAME"]?></li>

                                    <li data-set='services'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["NAME"]?></li>

                                    <li data-set='base_goals_tab'><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["BASE_GOALS"]["TAB"]?></li>

                                    <li data-set='politic'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["NAME"]?></li>

                                    <li data-set='store'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["NAME"]?></li>

                                    <li data-set='region'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["NAME"]?></li>
                                    
                                    <li data-set='customs'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["NAME"]?></li>
                                    <li data-set='other'><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["NAME"]?></li>
                                    
                                </ul>
                                <div class="other-li">
                                    <a href="/bitrix/admin/concept_phoenix_admin_index.php?site_id=<?=SITE_ID?>" target="_blank">
                                    <?=GetMessage("PHOENIX_SETTINGS_LIST_EDIT_SETS_EDIT_IN_ADMIN")?>
                                    </a>
                                </div>

                                
                            </td>
                            <td class='set-side-right'>

                                <div class="set-content" data-set='rating'>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['USE_VOTE']);?>
                                  
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['USE_REVIEW']);?>
                                    </div>

                                    <div class="more-options-wrap">

                                        <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['USE_REVIEW']['VALUE']['ACTIVE'] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['USE_REVIEW']['VALUES']["ACTIVE"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['USE_REVIEW']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>


                                            <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["RATING"]["SECTION_NAME_VIEW_FULL"]?></div>

                                            <div class="input-wrap">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['REVIEW_MODERATOR']);?>

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['SEND_NEW_REVIEW']);?>
                                            </div>

                                            <div class="input-wrap">

                                                <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['EMAIL_TO']['DESCRIPTION']?>
                                                <span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['EMAIL_TO']['HINT']?>"></span></div>

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['EMAIL_TO']);?>


                                            
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RATING_SIDEMENU_SHOW']);?>
                                            </div>

                                            <div class="input-wrap">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RATING_SIDEMENU_NAME']);?>

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RATING_BLOCK_TITLE']);?>
                                            </div>

                                            <div class="input-wrap">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RATING_HIDE_BLOCK_TITLE']);?>
                                            </div>


                                            <div class="input-wrap">

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['REVIEW_BLOCK_TITLE']);?>
                                            </div>

                                            <div class="input-wrap">
                                      
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['REVIEW_HIDE_BLOCK_TITLE']);?>
                                            </div>

                                            <div class="input-wrap">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RECOMMEND_HINT']);?>
                                            </div>

                                            <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["RATING"]["SECTION_NAME_VIEW_FULL_FLY_PANEL"]?></div>

                                            <div class="input-wrap">
                                                <div class="row">

                                                    <div class="col-6">
                                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['FLY_HEAD_BG'], array("CLASS_DIV_INPUT"=> "to-right"));?>
                                                    </div>
                                                </div>

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['FLY_PANEL_DESC']);?>

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['FLY_PANEL_SUCCESS_MESS']);?>
                                            </div>




                                        </div>

                                    </div>

                                </div>

                                <div class="set-content" data-set='comments'>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['PAGES']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['USERS_ACCESS']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['MODERATOR']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['ADMIN_EMAIL_NOTIFY']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['EMAIL_TO']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['FORM_EMAIL_ACTIVE']);?>
                                    </div>
                                 

                                </div>

                                <div class="set-content" data-set='order'>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PAY_FROM_ACCOUNT'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ONLY_FULL_PAY_FROM_ACCOUNT'])?>

                                    </div>
                                    

                                    <div class="input-wrap">

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['DELIVERY_TO_PAYSYSTEM']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['DELIVERY_TO_PAYSYSTEM'])?>

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['TEMPLATE_LOCATION']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['TEMPLATE_LOCATION'])?>


                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['SHOW_ZIP_INPUT'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ALLOW_NEW_PROFILE'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ALLOW_USER_PROFILES'])?>


                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['BASKET_POSITION']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['BASKET_POSITION'])?>
                                    </div>


                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_ORDER_NAME']);?>

                                        <?//CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_PRE_ORDER_NAME']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_COMMENT']);?>
                                    </div>

                                    <div class="input-wrap middle-sm">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_COMPLITED_MESS"]);?>
                                    </div>

                                    <div class="input-wrap middle-sm">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["TEMPLATE_ORDER_PAGE"]);?>
                                    </div>

                                </div>


                                <div class="set-content" data-set='fast_order'>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['FAST_ORDER_IN_BASKET_ON']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['FAST_ORDER_IN_BASKET_ONLY']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['FAST_ORDER_IN_PRODUCT_ON']);?>


                                    </div>

                                    <div class="input-wrap row">
                                        <div class="col-6">
                                            <div class="input to-right in-focus">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE']);?>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input to-left in-focus">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['DELIVERY']);?>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="input to-right in-focus">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PAY_SYSTEM']);?>
                                            </div>
                                        </div>

                                        <div class="col-12">

                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CART_FAST_ORDER_INPUTS"]?></div>

                                            <?foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'] as $value):?>

                                                <?CPhoenix::getOptionHtmlInPublic($value);?>

                                            <?endforeach;?>

                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CART_FAST_ORDER_INPUTS_REQ"]?></div>


                                            <?foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'] as $value):?>

                                                <?CPhoenix::getOptionHtmlInPublic($value);?>

                                            <?endforeach;?>

                                        </div>

                                    </div>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_FAST_ORDER_NAME_IN_BASKET']);?>
                                        
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_FAST_ORDER_NAME_IN_CATALOG_DETAIL']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['FAST_ORDER_FORM_SUBTITLE']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['FAST_ORDER_COMPLITED_MESS']);?>

                                        
                                        
                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SHOP"]["TITLE_CART_NOTIFIC"]?></div>


                                    <div class="input-wrap middle-sm">
                                        

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MESS_THEME_ADMIN']);?>
                                   
                                
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MESS_ADMIN']);?>

                                    </div>

                                    <div class="input-wrap middle-sm">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MESS_THEME_USER']);?>
                                

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MESS_USER']);?>

                                        
                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CATALOG"]["TITLE_MODE_PREORDER"]?></div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['AUTO_MODE_PREORDER']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['MODE_PREORDER_FORM']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['MODE_PREORDER_BTN_NAME']);?>
                                    </div>
                                    
                                </div>



                                <div class="set-content" data-set='personal'>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['CABINET'])?>
                                         
                                    </div>

                                    <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['CABINET']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['CABINET']['VALUES']["ACTIVE"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['CABINET']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>



                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['SECTIONS'])?>
                                        </div>

                                        <div class="input-wrap">

                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['CUSTOM_PAGES']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['CUSTOM_PAGES']['HINT']?>'></span></div>

                                            <?CPhoenix::getOptionHtmlInPublic( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['CUSTOM_PAGES'] );?>

                                          
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['FORM_AUTH_SUBTITLE'])?>
                                        </div>

                                        <div class="input-wrap">
                                            <div class="row">
                                                <div class="col-6">
                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['FORM_PIC'])?>
                                                </div>
                                                <div class="col-6">
                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['HEAD_BG_PIC'])?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['COMMENT_ORDERS'])?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['COMMENT_ORDERS_HISTORY'])?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['COMMENT_ORDERS_ACCOUNT'])?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['COMMENT_PRIVATE'])?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['COMMENT_PROFILE'])?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['COMMENT_BASKET'])?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['COMMENT_SUBSCRIBE'])?>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['FIRE_TITLE'])?>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['SHOW_DISCSAVE'])?>
                                        </div>

                                        <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["PERSONAL"]["TITLE_FIX_PRICE"]?></div>

                                        
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="input to-right in-focus">
                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['ACCOUNT_PERSON_TYPE'])?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['SHOW_FIX_PRICE'])?>
                                        </div>
                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]['FIX_PRICE_VALUES'])?>
                                        </div>




                                    </div>

                                </div>

                                <div class="set-content" data-set='brands'>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]['CUSTOM_URL']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]['NEWS_COUNT']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]['DEFAULT_LIST_VIEW']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]['OFFERS_QUANTITY']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]['BG_PIC']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]['DESC']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]['SEO_TEXT']);?>


                               
                                    </div>

                                </div>

                                <div class="set-content" data-set='catalog'>

                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CUSTOM_URL']);?>

                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['NEWS_COUNT']);?>
                                    
                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFERS_QUANTITY']);?>

                                    
                                    <div class="input-wrap">

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DEFAULT_LIST_VIEW']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DEFAULT_LIST_VIEW']);?>

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['HIDE_NOT_AVAILABLE']['DESCRIPTION']?></div>
                                        
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['HIDE_NOT_AVAILABLE']);?>


                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['HIDE_NOT_AVAILABLE_OFFERS']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['HIDE_NOT_AVAILABLE_OFFERS']);?>

                                    </div>
                              

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['HIDE_EMPTY_CATALOG']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['HIDE_PERCENT']);?>
                                 
                                    </div>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['SUBMENU_MAX_QUANTITY_SECTION']);?>
                                 
                                    </div>


                                    <div class="input-wrap">

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']);?>

                                    </div>

                                    <div class="input-wrap">

                                        <div class="input in-focus">

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE_SORT']);?>

                                        </div>

                                    </div>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']);?>

                                    </div>

                                    <div class="more-option 
                                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUES']["ACTIVE"]["VALUE"]):?> on<?endif;?>
                                        " 
                                        data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>

                                        <div class="input-wrap">

                                            <div class="input in-focus">

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']);?>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_AVAILABLE_PRICE_COUNT']);?>

                                    </div>


                                    <div class="input-wrap">

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']);?>
                                    </div>

                                    <div class="input-wrap">

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['MEASURE']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['MEASURE']);?>
                                    </div>

                                    <div class="input-wrap">

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORIES']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORIES']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TAB_SECTIONS_SHOW']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_FILTER']);?>
                                    </div>

                                    <div class="input-wrap">
                                        
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SORT_LEFT_SIDE']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SORT_LEFT_SIDE']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_SHOW']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_HIDE_PRICE']);?>

                                        <?//CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_SCROLL']);?>
                            
                                        <?//CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_IN_STOCK']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_DEACTIVATED_GOODS']);?>

                                        
                             
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DELAY_ON']);?>
                                  
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['ZOOM_ON']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['ORDER_BTN_NAME']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['LINK_2_DETAIL_PAGE_NAME']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['LINK_2_DETAIL_PAGE_NAME_OFFER']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <div class="row">
                                            <div class="col-6"><?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['HEAD_BG_PIC']);?></div>
                                        </div>
                                        
                                    </div>

                                    <?/*<div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['MAIN_SECTIONS_LIST']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['MAIN_SECTIONS_LIST']);?>
                                    </div>*/?>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['MAIN_SECTIONS_LIST']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SUBSECTIONS_HIDE_COUNT']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SUBTITLE']);?>
                               
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TOP_TEXT']);?>
                                
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['BOT_TEXT']);?>
                               
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['WATERMARK']);?>
                                    </div>

                                    <?/*<div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['VIEW_XS']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['VIEW_XS']);?>
                                    </div>*/?>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_ON']);?>
                                    </div>
                                    

                                    <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_ON']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_ON']['VALUES']["ACTIVE"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_ON']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>

                                        <div class="input-wrap parent-more-option">
                                    
                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_VIEW']['DESCRIPTION']?></div>
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_VIEW']);?>
                                        </div>

                                        <div class="more-options-wrap">

                                            <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_VIEW']['VALUE'] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_VIEW']['VALUES']["visible"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_VIEW']['VALUES']['invisible']['SHOW_OPTIONS']?>'>

                                                <div class="input-wrap middle">

                                                    <div class="row">

                                                        <div class="col-6">
                                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_FEW']['DESCRIPTION']?></div>
                                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_FEW']);?>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_MANY']['DESCRIPTION']?></div>
                                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_MANY']);?>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['PREVIEW_TEXT_POSITION']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['PREVIEW_TEXT_POSITION']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_BTN_SCROLL2CHARS']);?>
                                    </div>

                                    <div class="input-wrap ">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CHARS_SHOW_COUNT']['DESCRIPTION']?></div>
                                        <div class="row">
                                            <div class="col-3">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CHARS_SHOW_COUNT']);?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-wrap ">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILES_SHOW_COUNT']['DESCRIPTION']?></div>
                                        <div class="row">
                                            <div class="col-3">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILES_SHOW_COUNT']);?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DESC_FOR_ACTUAL_PRICE']);?>
                                 
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TIT_FOR_QUANTITY']);?>
                               

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['COMMENT_FOR_DETAIL_CATALOG']);?>
                                    </div>

                                    <div class="input-wrap row">

                                        <div class="col-6">
                                            <div class="input to-right in-focus">

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['BETTER_PRICE']);?>

                                            </div>
                                            
                                        </div>

                                        
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_RUB']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SECTION_SORT_LIST']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SECTION_SORT_LIST']);?>
                                    </div>

                                    

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CATALOG"]["TITLE_SUBSCRIBE_PRODUCT"]?></div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SUBSCRIBE_BTN_NAME']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SUBSCRIBED_BTN_NAME']);?>
                                    </div>



                                </div>

                                <div class="set-content" data-set='catalog_fields'>
                                    
                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CATALOG_ITEM_FIELDS"]["SECTION_NAME_LIST"]?></div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_SKU_FIELDS']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_SKU_FIELDS']);?>
                                    </div>


                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_CHARS_FIELDS']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_CHARS_FIELDS']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['SKU_HIDE_IN_LIST']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['PROPS_IN_LIST_FOR_FLAT']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['PROPS_IN_LIST_FOR_FLAT']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['PROPS_IN_LIST_FOR_LIST']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['PROPS_IN_LIST_FOR_LIST']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['PROPS_IN_LIST_FOR_TABLE']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['PROPS_IN_LIST_FOR_TABLE']);?>
                                    </div>


                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CATALOG_ITEM_FIELDS"]["SECTION_NAME_DETAIL"]?></div>

                                    <div class="input-wrap"><?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_PREDICTION']);?></div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_SKU_FIELDS']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_SKU_FIELDS']);?>
                                    </div>


                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_CHARS_FIELDS']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_CHARS_FIELDS']);?>
                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CATALOG"]["TITLE_DETAIL_PAGE_SORT"]?></div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_ADV']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_CHAR']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_GALLERY']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_VIDEO']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_REVIEW']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_FAQ']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_INFO_1']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_INFO_2']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_INFO_3']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_EMPL']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_GOODS']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_CATEGORY']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_ACCESSORIES']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_RATING']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_COMPLECT']);?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_SET']);?>
                                    </div>

                                    


                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CATALOG_ITEM_FIELDS"]["SECTION_NAME_COMMON"]?></div>


                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_SORT_SKU_CHARS']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_SORT_PROPS_CHARS']);?>
                                        
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_SORT_PROP_CHARS']);?>
                                    </div>





                                </div>

                                <div class="set-content" data-set='compare'>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['ACTIVE']);?>
                                    </div>


                                    <div class="more-option<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['COMPARE']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['COMPARE']['VALUES']["ACTIVE"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['ACTIVE']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['DESC']);?>
                                        </div>

                                        <div class="input-wrap row">
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['BG_PIC']);?>
                                            </div>
                                        </div>

                                        <?if(Bitrix\Iblock\Model\PropertyFeature::isEnabledFeatures()):?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["WARNINGS"]["PROPS"]);?>

                                        <?else:?>

                                            <div class="input-wrap">
                                                <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']['DESCRIPTION']?></div>
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['SKU']);?>
                                            </div>
                                            <div class="input-wrap">
                                                <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']['DESCRIPTION']?></div>
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]['PROPS']);?>
                                            </div>
                                        <?endif;?>

                                    </div>
                                </div>

                                <div class="set-content" data-set='blog'>

                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CUSTOM_URL']);?>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['MORE']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['MORE']['HINT']?>"></span></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['MORE']);?>
                                    </div>



                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['NEWS_COUNT']);?>
                                 

                                    <div class="input-wrap row">

                                        <div class="col-6">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BG_PIC']);?>
                                        </div>

                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['DESC']);?>
                                   
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['TOP_TEXT']);?>
                          
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BOT_TEXT']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['LABELS_MAX_COUNT']);?>
                                    </div>
                                    <div class="input-wrap row">
                                        <div class="col-6">
                                            <div class="input">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BOT_TEXT_POS']);?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BANNERS']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['BANNERS']);?>
                                    </div>

                                    <div class="input-wrap">

                                        <div class="row">

                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CATEGORY_BLOG_ICON'], array("CLASS_DIV_INPUT"=> "to-right") );?>
                                            </div>
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CATEGORY_BLOG_NAME'], array("CLASS_DIV_INPUT"=> "to-left"));?>
                                            </div>


                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CATEGORY_VIDEO_ICON'], array("CLASS_DIV_INPUT"=> "to-right"));?>
                                            </div>
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CATEGORY_VIDEO_NAME'], array("CLASS_DIV_INPUT"=> "to-left"));?>
                                            </div>


                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CATEGORY_INTERVIEW_ICON'], array("CLASS_DIV_INPUT"=> "to-right") );?>
                                            </div>
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CATEGORY_INTERVIEW_NAME'], array("CLASS_DIV_INPUT"=> "to-left"));?>
                                            </div>
                                        

                                   
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CATEGORY_OPINION_ICON'], array("CLASS_DIV_INPUT"=> "to-right") );?>
                                            </div>
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CATEGORY_OPINION_NAME'], array("CLASS_DIV_INPUT"=> "to-left"));?>
                                            </div>

                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CATEGORY_CASE_ICON'], array("CLASS_DIV_INPUT"=> "to-right") );?>
                                            </div>
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CATEGORY_CASE_NAME'], array("CLASS_DIV_INPUT"=> "to-left"));?>
                                            </div>

                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CATEGORY_SENS_ICON'], array("CLASS_DIV_INPUT"=> "to-right") );?>
                                            </div>
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['CATEGORY_SENS_NAME'], array("CLASS_DIV_INPUT"=> "to-left"));?>
                                            </div>

                                        </div>
                                    </div>
                                    
                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]['SIDE_MENU_HTML']);?>
                                    </div>
                                    
                                </div>

                                <div class="set-content" data-set='news'>

                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['CUSTOM_URL']);?>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['MORE']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['MORE']['HINT']?>"></span></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['MORE']);?>
                                    </div>

                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['NEWS_COUNT']);?>

                                    <div class="input-wrap row">

                                        <div class="col-6">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BG_PIC']);?>
                                        </div>

                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['DESC']);?>
                                  
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['TOP_TEXT']);?>
                                 
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BOT_TEXT']);?>
                                    </div>
                                    <div class="input-wrap row">
                                        <div class="col-6">
                                            <div class="input">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BOT_TEXT_POS']);?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BANNERS']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['BANNERS']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]['SIDE_MENU_HTML']);?>
                                    </div>

                                </div>

                                <div class="set-content" data-set='action'>

                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['CUSTOM_URL']);?>
                                    
                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['MORE']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['MORE']['HINT']?>"></span></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['MORE']);?>
                                    </div>

                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['NEWS_COUNT']);?>

                                    <div class="input-wrap row">

                                        <div class="col-6">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BG_PIC']);?>
                                        </div>

                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['DESC']);?>
                                  
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['TOP_TEXT']);?>
                                  
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BOT_TEXT']);?>
                                    </div>
                                    <div class="input-wrap row">
                                        <div class="col-6">
                                            <div class="input">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BOT_TEXT_POS']);?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BANNERS']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['BANNERS']);?>
                                    </div>
                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]['SIDE_MENU_HTML']);?>
                                    </div>

                                </div>



                                <div class="set-content" data-set='disain'>
                                    
                                    
                                    <div class="input-wrap middle">
                            
                                        <div class="name bold"><?=GetMessage("PHOENIX_SETTINGS_LIST_PICTURES")?></div>
                                        <div class="row">
                                            <div class="col-6">
                                               <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_LOGO'], array("CLASS_DIV_INPUT"=> "to-right"));?>
                                            </div>

                                            <div class="col-6">
                                                
                                                <div class="input to-left clearfile-parent">
                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FAVICON'], array("CLASS_DIV_INPUT"=> "to-left"));?>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                               <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['LOGO_LIGHT'], array("CLASS_DIV_INPUT"=> "to-right"));?>
                                            </div>
                                        </div>

                                        <div class="name bold"><?=GetMessage("PHOENIX_SETTINGS_LIST_PICTURES")?></div>
                                        <div class="row">
                                            <div class="col-6">
                                                
                                            
                                               <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['LOGO_MOB'], array("CLASS_DIV_INPUT"=> "to-right"));?>
                                          
                                                
                                            </div>

                                            <div class="col-6">
                                                
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['LOGO_MOB_LIGHT'], array("CLASS_DIV_INPUT"=> "to-left"));?>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_EXTRASIZE"])?>
                                        
                                    </div>

                                    <div class="input-wrap">
                                        <div class="row">
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"], array("CLASS_DIV_INPUT"=> "to-right"))?>
                                            </div>
                                        </div>
                                        
                                    </div>
                

                                    <div class="input-wrap">
                                        <div class="name bold"><?=GetMessage("PHOENIX_SETTINGS_LIST_FONTS")?></div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="input to-right in-focus">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FONTS"]["TITLE"])?>

                                                </div>
                                                
                                            </div>

                                            <div class="col-6">
                                                <div class="input to-left in-focus">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FONTS"]["TEXT"])?>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['MAIN_COLOR_STD']['DESCRIPTION']?></div>
                                        <div class="phoenix-color-row clearfix">

                                            <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['MAIN_COLOR_STD']['VALUES'])):?>
                                                <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['MAIN_COLOR_STD']['VALUES'] as $k => $arColor):?>
                                                    <div class="phoenix-color-col">
                                                        <label>
                                                            <input class='on-save' <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['MAIN_COLOR_STD']['VALUE'] == $arColor && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['MAIN_COLOR']['VALUE'])<=0):?>checked="checked"<?endif;?> name="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['MAIN_COLOR_STD']['NAME']?>" type="radio" value="<?=$arColor?>">
                                                            <span><span style="background-color:<?=$arColor?>;"></span></span>
                                                        </label>
                                                    </div>
                                                <?endforeach;?>
                                            <?endif;?>

                                        </div>
                                    </div>


                                    <div class="input-wrap">
                                        <div class="row">

                                            <div class="col-6">

                                                <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['MAIN_COLOR']['DESCRIPTION']?></div>
                                             
                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['MAIN_COLOR'], array( "CLASS_DIV_INPUT" => "to-right"))?>
                                          
                                            </div>
                                        </div>

                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONT_COLOR']['DESCRIPTION']?></div>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['FONT_COLOR'])?>
                                    </div>

                                    
                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_TONE']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_TONE'])?>

                                    </div>



                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['COLOR_HEADER']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['COLOR_HEADER'])?>
                                 
                                    </div>

                                    <div class="input-wrap none">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['DESCRIPTION']?></div>

                                        <ul class='input-radio-css row'>

                                            <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUES'])):?>

                                                <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUES'] as $arBtn):?>
                                                
                                                    <li class='col-md-4'>
                                                        <label class="input-radio-css with-button">
                                                            <input class='on-save' <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE'] == $arBtn["VALUE"]):?> checked="checked"<?endif;?> name="<?=$arBtn["NAME"]?>" type="radio" value="<?=$arBtn["VALUE"]?>">          
                                                            <span></span>
                                                            <span class="button-def <?=$arBtn["VALUE"]?> main-color"><?=GetMessage("PHOENIX_SETTINGS_BTN_VIEW_NAME")?></span>
                                                        </label>
                                                    </li>

                                                <?endforeach;?>

                                            <?endif;?>

                                       </ul>
                                 
                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["DESIGN"]["TITLE_HEAD_EDIT"]?></div>


                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_VIEW'])?>

                                    </div>

                                    <div class="input-wrap middle">
                            
                                        
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG_COLOR']["DESCRIPTION"]?></div>

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG_COLOR'], array( "CLASS_DIV_INPUT" => "to-right"))?>
                                                    

                                            </div>

                                            <div class="col-6">
                                                
                                                <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG_OPACITY']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG_OPACITY']['HINT']?>"></span></div>

                                                <div class="input">   

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG_OPACITY'], array("CLASS_DIV_INPUT"=> "to-left"))?>

                                                </div>
                                            </div>

                                           
                                        </div>
                                    </div>
                                    <div class="input-wrap">
                                        <div class="row clearfix">
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG'], array("CLASS_DIV_INPUT"=> "to-right"));?>
                                           
                                            </div>

                                            <div class="col-6">

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG_COVER'])?>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_FIXED'])?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_HTML'])?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_MAIN"])?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_SCROLL"])?>
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_MOBILE"])?>
                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["DESIGN"]["TITLE_HEAD_BG_XS_FOR_PAGES"]?></div>

                                    <div class="input-wrap parent-more-option">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG_XS_FOR_PAGES_MODE'])?>
                                    </div>
                                    <div class="more-options-wrap">
                                        <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG_XS_FOR_PAGES_MODE']['VALUE'] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG_XS_FOR_PAGES_MODE']['VALUES']['CUSTOM']['VALUE']):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG_XS_FOR_PAGES_MODE']['SHOW_OPTIONS']?>'>

                                            <div class="input-wrap">

                                                <div class="row">
                                                    <div class="col-8">
                                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG_XS_FOR_PAGES']);?>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["DESIGN"]["TITLE_BODY_BG"]?></div>

                                    <div class="input-wrap">
                                        <div class="row">
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['HEAD_BG_XS_FOR_PAGES_MODE'])?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BODY_BG_CLR'])?>
                                            </div>
                                        </div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BODY_BG_POS'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BODY_BG_REPEAT'])?>
                                    </div>
                 

                                </div>

                                <div class="set-content" data-set='base_goals_tab'>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_GOALS"]?></div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['METRIKA_GOAL'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_CATEGORY'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_ACTION'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_EVENT'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_CATEGORY'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_ACTION'])?>
                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_GOALS_ADD2BASKET"]?></div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['METRIKA_GOAL_ADD2BASKET'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_CATEGORY_ADD2BASKET'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_ACTION_ADD2BASKET'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_EVENT_ADD2BASKET'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_CATEGORY_ADD2BASKET'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_ACTION_ADD2BASKET'])?>
                                    </div>

                                    

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_GOALS_ORDER"]?></div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['METRIKA_GOAL_ORDER'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_CATEGORY_ORDER'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_ACTION_ORDER'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_EVENT_ORDER'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_CATEGORY_ORDER'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_ACTION_ORDER'])?>
                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_GOALS_FAST_ORDER"]?></div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['METRIKA_GOAL_FAST_ORDER'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_CATEGORY_FAST_ORDER'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE_ACTION_FAST_ORDER'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_EVENT_FAST_ORDER'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_CATEGORY_FAST_ORDER'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_ACTION_FAST_ORDER'])?>
                                    </div>


                                </div>

                                

                                <div class="set-content" data-set='lids'>
                                    

                                    <div class="input-wrap">
                                        <div class="name bold"><?=GetMessage("PHOENIX_SETTINGS_LIST_ADMIN_EMAIL")?> <span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=GetMessage("PHOENIX_SETTINGS_LIST_ADMIN_EMAIL_HINT")?>"></span></div>
                                        <div class="row">
                                            <div class="col-6">

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['EMAIL_FROM'], array("CLASS_DIV_INPUT"=> "to-right"));?>


                                            </div>
                                            <div class="col-6">

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['EMAIL_TO'], array("CLASS_DIV_INPUT"=> "to-left"));?>
                                              
                                            </div>
                                        </div>


                                    </div>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['SAVE_IN_IB'])?>
                                      
                                    </div>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['BX_ON'])?>
                                        
                                    </div>

                                    <div class="more-option<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['BX_ON']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['BX_ON']['VALUES']["ACTIVE"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['BX_ON']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>

                                        <div class="input-wrap">

                                            <div class="row">
                                                <div class="col-12">
                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['BX_URL']);?>                                                   
                                                </div>

                                                <div class="col-8">
                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['BX_ASSIGNED_BY_ID'], array("CLASS_DIV_INPUT"=> "to-right"));?>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="input-wrap">

                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["LIDS"]["TITLE_BX_WEB_HOOK"]?></div>

                                            <div class="row">

                                                <div class="col-6">
                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['BX_ID'], array("CLASS_DIV_INPUT"=> "to-right"));?>
                                                </div>
                                                <div class="col-6">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['BX_WEB_HOOK'], array("CLASS_DIV_INPUT"=> "to-left"));?>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-wrap">

                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["LIDS"]["TITLE_BX_STANDART"]?></div>

                                            <div class="row">

                                                <div class="col-6">
                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['BX_LOG'], array("CLASS_DIV_INPUT"=> "to-right"));?>
                                                </div>
                                                <div class="col-6">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['BX_PAS'], array("CLASS_DIV_INPUT"=> "to-left"));?>

                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['SEND_TO_AMO'])?>
                                    </div>

                                    <div class="more-option<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['SEND_TO_AMO']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['SEND_TO_AMO']['VALUES']["ACTIVE"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['SEND_TO_AMO']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>

                                        <div class="input-wrap">

                                            <div class="row">
                                                <div class="col-12">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['AMO_URL']);?> 

                                                </div>

                                                <!-- <div class="col-12">

                                                    <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['AMO_TOKEN']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['AMO_TOKEN']['HINT']?>"></span></div>

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['AMO_TOKEN']);?> 

                                                </div> -->
                                                <div class="col-6">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['AMO_LOG'], array("CLASS_DIV_INPUT" => "to-right"));?> 
                                                 
                                                </div>
                                                <div class="col-6">
                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]['AMO_HASH'], array("CLASS_DIV_INPUT" => "to-left"));?> 

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                            
                                    
                                </div>

                                <div class="set-content" data-set='footer'>
                                    

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_ON'])?>

                                        
                                    </div>

                                    

                                    <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_ON']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_ON']['VALUES']["ACTIVE"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_ON']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_MAIN'])?>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['TEXT_COLOR'])?>
                                        </div>

                                        <div class="input-wrap">

                                            <div class="row">
                                                <div class="col-6">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_BG'], array("CLASS_DIV_INPUT"=> "to-right"))?>
                                                     
                                            
                                                    
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="input-wrap middle">
                                            
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COLOR_BG']['DESCRIPTION']?></div>

                                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COLOR_BG'], array( "CLASS_DIV_INPUT" => "to-right"))?>

                                                </div>

                                                <div class="col-6">
                                                    <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_OPACITY_BG']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_OPACITY_BG']['HINT']?>"></span></div>

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_OPACITY_BG'], array("CLASS_DIV_INPUT" => "to-left"));?> 
                                                  
                                                </div>

                                               
                                            </div>
                                        </div>
                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['HIDE_BG_TONE']);?>
                                        </div>
                                        <div class="input-wrap">

                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_DESC']['DESCRIPTION']?></div>
                                            <div class="row">
                                                <div class="col-12">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_DESC']);?>
                                                 
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_INFO']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_INFO']['HINT']?>'></span></div>
                                            <div class="row">
                                                <div class="col-12">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_INFO']);?>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>

                                        <div class="input-wrap">

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_ON'])?>
                                        </div>

                                        <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_ON']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_ON']['VALUES']["ACTIVE"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_ON']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>

                                            <div class="input-wrap parent-more-option">

                                                <ul class='input-radio-css'>

                                                    <li>
                                                        <label class="input-radio-css">
                                                            <input class="open_more_options on-save" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUES']['DEF']['SHOW_OPTIONS']?>' 
                                                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUE'] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUES']["DEF"]["VALUE"]):?>checked="checked"<?endif;?> name="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUES']["DEF"]["NAME"]?>" type="radio" value="default">          
                                                            <span></span>
                                                            <span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUES']["DEF"]["DESCRIPTION"]?></span>
                                                        </label>
                                                    </li>

                                                    <li>
                                                        <label class="input-radio-css">
                                                            <input class="open_more_options on-save" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUES']['USER']['SHOW_OPTIONS']?>' <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUE'] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUES']["USER"]["VALUE"]):?>checked="checked"<?endif;?> name="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUES']["USER"]["NAME"]?>" type="radio" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUES']["USER"]["VALUE"]?>">          
                                                            <span></span>
                                                            <span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUES']["USER"]["DESCRIPTION"]?></span>
                                                        </label>
                                                    </li>

                                               </ul>
                                         
                                            </div>


                                            <div class="more-options-wrap">
                                                <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUE'] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUES']["USER"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_TYPE']['VALUES']['USER']['SHOW_OPTIONS']?>'>

                                                   <div class="input-wrap">

                                                        <div class="row clearfix">
                                                            <div class="col-6">

                                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_USER_DESC'], array("CLASS_DIV_INPUT" => "to-right"));?> 
                                                            </div>

                                                            <div class="col-6">

                                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_USER_PIC'], array("CLASS_DIV_INPUT"=> "to-left"))?>
                                                            </div>
                                                            
                                                            <div class="col-12">
                                                                <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_USER_URL']['DESCRIPTION']?></div>

                                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['FOOTER_COPYRIGHT_USER_URL']);?> 

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="input-wrap">

                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['BANNER_1']['DESCRIPTION']?></div>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['BANNER_1'])?>
                                        </div>

                                        <div class="input-wrap">

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['BANNER_1_URL'])?>
                                        </div>

                                        <div class="input-wrap">

                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['BANNER_2']['DESCRIPTION']?></div>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['BANNER_2'])?>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['BANNER_2_URL'])?>
                                        </div>

                                        <div class="input-wrap">

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['SUBSCRIPE'])?>
                                        </div>

                                        <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['SUBSCRIPE']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['SUBSCRIPE']['VALUES']["ACTIVE"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['SUBSCRIPE']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>

                                            <div class="input-wrap">

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['SUBSCRIPE_DESCRIPTION'])?>
                                            </div>

                                            <?/*<div class="input-wrap">

                                                CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['SUBSCRIPE_RUBRICS_SHOW'])
                                            </div>*/?>

                                        </div>

                                        <div class="input-wrap">

                                          
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['PAYMENT_PIC'])?>
                                      
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['PAYMENT_URL'])?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]['PAYMENT_HINT'])?>
                                                                                  
                                        </div>
                                    </div>
                                </div>

                                <div class="set-content" data-set='services'>
                                    

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_ANALITICS"]?></div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['LAZY_SERVICE'])?>
                                    </div>

                                    <div class="input-wrap">

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['LAZY_SERVICE_TIME']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['LAZY_SERVICE_TIME']['HINT']?>"></span></div>

                                        <div class="row">
                                            <div class="col-3">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['LAZY_SERVICE_TIME'])?>
                                            </div>
                                        </div>

                                        
                                    </div>


                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['METRIKA'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GOOGLE'])?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_HEAD'])?>


                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['GTM_BODY'])?>


                                    </div>


                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_OTHER_SERVICES"]?></div>


                                    <div class="input-wrap">


                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['INHEAD'])?>


                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['INBODY'])?>


                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['INCLOSEBODY'])?>


                                        <div class="spec-comment italic no-line"><?=GetMessage("PHOENIX_SETTINGS_LIST_SERVICES_DESC")?></div>
                                        
                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_SCRIPTS"]?></div>

                                    <div class="input-wrap none">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['LAZY_SCRIPTS_TIME']['DESCRIPTION']?></div>

                                        <div class="row">
                                            <div class="col-3">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['LAZY_SCRIPTS_TIME'])?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]['LAZY_SCRIPTS'])?>
                                    </div>
                                    
                                </div>

                                <div class="set-content" data-set='customs'>

                                    <div class="input-wrap no-margin-top-bot">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['STYLES']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['STYLES']['HINT']?>"></span></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['STYLES'])?>
                            
                              

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['SCRIPTS']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['SCRIPTS']['HINT']?>"></span></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]['SCRIPTS'])?>
                                        
                                    </div>
                                </div>

                                <div class="set-content" data-set='contacts'>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW'])?>
                                    </div>

                                    <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']['VALUES']["ACTIVE"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_SHOW']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>

                                        <div class="input-wrap">

                                            <div class="name bold"><?=GetMessage("PHOENIX_SETTINGS_CHOOSE_FORMS_TITLE")?></div>

                                            <div class="row">

                                                <div class="col-6">
                                                    <div class="input to-right in-focus">
                                                        <span class="desk"><?=GetMessage("PHOENIX_SETTINGS_CHOOSE_FORMS_CALLBACK")?></span>

                                                        <select name="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["ITEMS"]["CALLBACK"]['NAME']?>" class='on-save'>
                                                            <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]['NAME'])):?>

                                                                <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]['NAME'] as $k => $arForm):?>
                                                                    <option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["ITEMS"]["CALLBACK"]['VALUE'] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]['VALUES'][$k]):?> selected <?endif;?> value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]['VALUES'][$k]?>"><?=$arForm?></option>
                                                                <?endforeach;?>

                                                            <?endif;?>
                                                        </select>
                                                    </div>
                                                    
                                                </div>

                                            </div>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['CALLBACK_NAME'])?>

                                        </div>
                                    </div>

                                   

                                    <div class="input-wrap">

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['HEAD_CONTACTS']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['HEAD_CONTACTS']['HINT']?>'></span></div>

                                        <?CPhoenix::getOptionHtmlInPublic( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['HEAD_CONTACTS'] );?>

                                      
                                    </div>

                                    <div class="input-wrap">

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['HEAD_EMAILS']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['HEAD_EMAILS'] );?>
                               

                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['ADDRESS'] );?>
                                    </div>
                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP']['HINT']?>'></span></div>
                                        
                                        <?CPhoenix::getOptionHtmlInPublic( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['DIALOG_MAP'] );?>
                                    </div>

                                    <div class="input-wrap">

                                        <div class="name bold"><?=GetMessage("PHOENIX_SETTINGS_SOCIALS_TITLE")?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['VK'], array("AFTER_INPUT_HTML"=> "<div class='wrap-i'><i class='concept-vkontakte'></i></div>") );?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['FB'], array("AFTER_INPUT_HTML"=> "<div class='wrap-i'><i class='concept-facebook-1'></i></div>") );?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['TW'], array("AFTER_INPUT_HTML"=> "<div class='wrap-i'><i class='concept-twitter-bird-1'></i></div>") );?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['YOUTUBE'], array("AFTER_INPUT_HTML"=> "<div class='wrap-i'><i class='concept-youtube-play'></i></div>") );?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['INST'], array("AFTER_INPUT_HTML"=> "<div class='wrap-i'><i class='concept-instagram-4'></i></div>") );?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['TELEGRAM'], array("AFTER_INPUT_HTML"=> "<div class='wrap-i'><i class='concept-paper-plane'></i></div>") );?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['OK'], array("AFTER_INPUT_HTML"=> "<div class='wrap-i'><i class='concept-odnoklassniki'></i></div>") );?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['TIKTOK'], array("AFTER_INPUT_HTML"=> "<div class='wrap-i'><i class='concept-tiktok'></i></div>") );?>

                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SOC_GROUP_ICON'])?>
                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['GROUP_POS']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['GROUP_POS'])?>

                                        
                                    </div>

                                    <div class="input-wrap">

                                        <div class="name bold"><?=GetMessage("PHOENIX_SHARE_TITLE")?></div>

                                        <ul class="input-checkbox-css">                                                
                                            <li>
                                                <label class="input-checkbox-css">
                                                    <input class='on-save' name="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARE_ON']['VALUES']["ACTIVE"]["NAME"]?>"<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARE_ON']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARE_ON']['VALUES']["ACTIVE"]["VALUE"]):?> checked<?endif;?> type="checkbox" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARE_ON']['VALUES']["ACTIVE"]["VALUE"]?>"><span></span><span><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARE_ON']['DESCRIPTION']?></span> 
                                                </label>
                                               
                                            </li>
                                            <li>
                                                <label class="input-checkbox-css">
                                                    <input class= 'open_more_options on-save' data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_ON']['VALUES']["ACTIVE"]["SHOW_OPTIONS"]?>' name="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_ON']['VALUES']["ACTIVE"]["NAME"]?>" <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_ON']['VALUE']["ACTIVE"] == "Y"):?> checked<?endif;?> type="checkbox" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_ON']['VALUES']["ACTIVE"]["VALUE"]?>"><span></span><span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_ON']['DESCRIPTION']?></span>
                                                </label>
                                            </li>
                                        </ul>

                                    </div>

                                    <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_ON']['VALUE']["ACTIVE"] == "Y"):?>on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_ON']['VALUES']["ACTIVE"]["SHOW_OPTIONS"]?>'>

                                        <div class="input-wrap">

                                            <div class="row clearfix">
                                                <div class="col-6">
                                                    <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_NUM']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_NUM']['HINT']?>"></span></div>


                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_NUM'], array("CLASS_DIV_INPUT" => "to-left") );?>


                                                </div>

                                                <div class="col-6">
                                                    <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_DESC']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_DESC']['HINT']?>"></span></div>

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['WIDGET_FAST_CALL_DESC'], array("CLASS_DIV_INPUT" => "to-right") );?>

                                                 
                                                </div>
                                            </div>

                                        </div>

                                    </div>


                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARE_DETAIL_CATALOG_ON'])?>

                                        

                                    </div>

                                    <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARE_DETAIL_CATALOG_ON']['VALUE']["ACTIVE"] == "Y"):?>on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARE_DETAIL_CATALOG_ON']['VALUES']["ACTIVE"]["SHOW_OPTIONS"]?>'>

                                        <div class="input-wrap">

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARES_COMMENT_FOR_DETAIL_CATALOG'])?>

                                        </div>

                                    </div>


                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CONTACTS"]["TITLE_PHOENIX_MASK"]?></div>


                                    <div class="input-wrap">
                                        <div class="row">

                                            <div class="col-8">
                                                <div class="input to-right in-focus">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['MAIN_MASK'])?>

                                                </div>
                                            </div>

                                        </div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['USER_MASK'])?>

                                    </div>


                                </div>

                                <div class="set-content" data-set='store'>


                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["LIST"]);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]['SHOW_RESIDUAL']);?>
                                    </div>


                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["STORE"]["TITLE_DETAIL_PAGE"]?></div>


                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_STORE_BLOCK']);?>
                                    </div>
                                    <div class="input-wrap">

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_BLOCK_VIEW']["DESCRIPTION"]?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_BLOCK_VIEW']);?>
                                    </div>
                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_EMPTY_STORE']);?>
                                    </div>
                                    

                                </div>

                                <div class="set-content" data-set='region'>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["REGION"]["TITLE_ALERT_INSTRUCTION"]?></div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['ACTIVE'] );?>
                                    </div>

                                    <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['ACTIVE']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['ACTIVE']['VALUES']["ACTIVE"]["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['ACTIVE']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['USE_404']);?>
                                        </div>

                                        <?/*<div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['USE_IP']);?>
                                        </div>*/?>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['DOMEN_DEFAULT']);?>
                                        </div>

                                        <div class="input-wrap">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['LOCATION_LIST']["DESCRIPTION"]?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['LOCATION_LIST']['HINT']?>"></span></div>
                                                    <div class="input to-right in-focus">

                                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['LOCATION_LIST'])?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['SHOW_IN_HEAD']);?>
                                        </div>

                                        <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["REGION"]["TITLE_POPUP_WINDOW"]?></div>

                                        <div class="input-wrap">
                                            <div class="row">
                                                <div class="col-6"><?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['BG']);?></div>
                                            </div>
                                     
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['COMMENT_FOR_FORM']);?>
                                        </div>


                                        <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["REGION"]["TITLE_APPLY_POPUP_WINDOW"]?></div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['SHOW_APPLY']);?>

                                        </div>

                                        <div class="input-wrap">
                                            <div class="row">
                                                <div class="col-6"><?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['BG_SMALL']);?></div>
                                            </div>
                                    
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['COMMENT_FOR_APPLY']);?>
                                        </div>

                                    </div>

                                </div>

                                <div class="set-content" data-set='politic'>

                                    <div class="input-wrap">

                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['POLITIC_DESC']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['POLITIC_DESC'] );?>


                                    </div>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['POLITIC_CHECKED'])?>
                                      
                                    </div>

                                    <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT']['ITEMS'])):?>

                                        <div class="input-wrap">
                                            <div class="name bold"><?=GetMessage("PHOENIX_AGREEMENT_NAME")?></div>
                                            <ul class="political">

                                                <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT']['ITEMS'])):?>

                                                    <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENT']['ITEMS'] as $k => $arAgr):?>

                                                        <li>
                                                            <div> 
                                                                <?=$arAgr['NAME']?> <a href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["AGREEMENT"]['IBLOCK_ID']?>&type=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["AGREEMENT"]['IBLOCK_TYPE']?>&ID=<?=$arAgr['ID']?>&lang=ru&find_section_section=0&WF=Y" target='_blank'></a>
                                                            </div>
                                                        </li>

                                                    <?endforeach;?>

                                                <?endif;?>
                                                
                                            </ul>
                                        </div>
                                    <?endif;?>

                                    <div class="button-wrap">
                                        <a href='/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['AGREEMENT']['IBLOCK_ID']?>&type=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['AGREEMENT']['IBLOCK_TYPE']?>&ID=0&lang=ru&IBLOCK_SECTION_ID=0&find_section_section=0&from=iblock_list_admin' target="_blank" class="plus"><?=GetMessage("PHOENIX_SETTINGS_CHOOSE_AGREEMENT_NEW")?></a>
                                    </div>

                                </div>

                                <div class="set-content" data-set='other'>

                          

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="input">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['DATE_FORMAT']);?>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['NAME_SITE']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['AFTER_EPILOG_CLEAR_CACHE']);?>
                                    </div>

                                    <?/*<div class="input-wrap">

                                        <div class="name bold"><?=GetMessage("PHOENIX_SETTINGS_CHOOSE_FORMS_TITLE")?></div>

                                        <div class="row">

                                            <div class="col-6">
                                                <div class="input to-right in-focus">
                                                    <span class="desk"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["ITEMS"]['CATALOG']["DESCRIPTION"]?></span>

                                                    <select name="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["ITEMS"]['CATALOG']["NAME"]?>" class='on-save'>

                                                        <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]['NAME'])):?>

                                                            <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]['NAME'] as $k => $arForm):?>
                                                                <option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FORMS']["ITEMS"]['CATALOG']["VALUE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]['VALUES'][$k]):?> selected <?endif;?> value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]['VALUES'][$k]?>"><?=$arForm?></option>
                                                            <?endforeach;?>

                                                        <?endif;?>
                                                    </select>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>*/?>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['ADD_SITE_TITLE']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['MODE_FAST_EDIT']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['SCROLLBAR']);?>

                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["OTHER"]["TITLE_SITE_BUILD"]?></div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['SITE_BUILD_ON']);?>
                                    </div>

                                    <div class="more-option 
                                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['SITE_BUILD_ON']['VALUE']["ACTIVE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['SITE_BUILD_ON']['VALUES']['ACTIVE']['VALUE']):?>
                                            on
                                        <?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['SITE_BUILD_ON']['VALUES']['ACTIVE']['SHOW_OPTIONS']?>'>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['SITE_BUILD_TEXT']);?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['SITE_BUILD_LINK']);?>
                                        </div>

                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["OTHER"]["TITLE_BG_404"]?></div>

                                    
                                    <div class="input-wrap">
                                        <div class="row">
                                            <div class="col-6">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['BG_404']);?>
                                            </div>
                                        </div>
                                        
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['MES_404']);?>
                                    </div>


                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["OTHER"]["TITLE_OPTIMIZIER_OPTIONS"]?></div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['COMPRESS_HTML']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['DELETE_BX_TECH_FILES']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['TRANSFER_CSS_TO_PAGE']);?>
                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["OTHER"]["TITLE_GOOGLE_RECAPTCHA"]?></div>

                                    <div class="input-wrap middle">
                                        
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['CAPTCHA']);?>
                                    </div>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['CAPTCHA_SITE_KEY']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['CAPTCHA_SECRET_KEY']);?>
                                    </div>
                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['CAPTCHA_SCORE']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['CAPTCHA_SCORE']['HINT']?>"></span></div>
                                        <div class="row">
                                            <div class="col-3">
                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['CAPTCHA_SCORE']);?>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="set-content" data-set='menu'>


                                    <div class="input-wrap parent-more-option">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_TYPE']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_TYPE']);?>
                               
                                    </div>



                                    <div class="more-options-wrap">


                                        <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_TYPE']['VALUE'] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_TYPE']['VALUES']['on_board']['VALUE'] || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_TYPE']['VALUE'] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_TYPE']['VALUES']['on_line']['VALUE']):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_TYPE']['SHOW_OPTIONS']?>'>

                                            <div class="input-wrap">

                                                <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_VIEW']['DESCRIPTION']?></div>

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_VIEW']);?>
                                                

                                            </div>

                                            <div class="input-wrap middle">
                            
                                                <div class="row">
                                                    
                                                    <div class="col-6">
                                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_BG_COLOR']['DESCRIPTION']?></div>

                                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_BG_COLOR'], array( "CLASS_DIV_INPUT" => "to-right") )?>
                                                            
                                                    </div>

                                                    <div class="col-6">
                                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_BG_OPACITY']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_BG_OPACITY']['HINT']?>"></span></div>


                                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_BG_OPACITY'], array( "CLASS_DIV_INPUT" => "to-left"));?>


                                                    </div>
                                                </div>
                                            </div>

                                            <div class="input-wrap">
                                                <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_TEXT_COLOR']['DESCRIPTION']?></div>

                                                <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MENU_TEXT_COLOR']);?>
                                         
                                            </div>




                                            
                                        </div>
                                    </div>

                                    <div class="input-wrap">
                                        <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MOBILE_MENU_TONE']['DESCRIPTION']?></div>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['MOBILE_MENU_TONE']);?>
                                 
                                    </div>

                                    <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["MENU"]["TITLE_VIEW_MENU"]?></div>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['CATALOG_COUNT_SHOW']);?>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['OTHER_COUNT_SHOW']);?>
                                 
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['OTHER_COUNT_SHOW']);?>
                                    </div>

                                    <div class="input-wrap">
                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]['LINK_SUBSECTIONS_BTN_NAME']);?>
                                    </div>

                                    
                                </div>

                                <div class="set-content active" data-set='instruct'>

                                    <?if(CModule::IncludeModule('security') && !empty($arResult["PROACTIVE_FILTER_MASK"])):?>

                                        <?if (CSecurityFilter::IsActive()):?>
                                            <div class="input-wrap border-alert">

                                                <div class="other-li">

                                                    <?=GetMessage("PHOENIX_SETTINGS_ALERT_SECURITY")?>
                                                    <?
                                                        foreach ($arResult["PROACTIVE_FILTER_MASK"] as $key => $value)
                                                        {
                                                            echo $value.'<br/>';
                                                        }
                                                    ?>

                                                </div>
                                            </div>

                                        <?endif;?>

                                    <?endif;?>

                                    <div class="input-wrap">
                                        <?=CPhoenixInstructions::printHTML('view_2');?>
                                    </div>
                                    
                                </div>

                                <div class="set-content" data-set='cart'>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_ON']);?>
                       

                                    </div>

                                    <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_ON']['VALUE']["ACTIVE"] == 'Y'):?>on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_ON']['VALUES']["ACTIVE"]["SHOW_OPTIONS"]?>'>

                                        <div class="input-wrap">

                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ORDER_PAGES']['DESCRIPTION']?></div>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ORDER_PAGES'])?>

                                        </div>
                                        

                                        <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SHOP"]["TITLE_CARTMINI_CART"]?></div>

                                        <div class="input-wrap">
                                            
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['BASKET_URL']);?>
                                        </div>

                                        <div class="input-wrap">
                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MINICART_MODE']['DESCRIPTION']?></div>
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MINICART_MODCART_MINICART_LINK_PAGEE']);?>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MINICART_LINK_PAGE']);?>
                                      
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MINICART_DESC_EMPTY']);?>
                                       
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MINICART_DESC_NOEMPTY']);?>
                                        </div>
                                        
                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_IN_MENU_ON']);?>
                                        </div>


                                        
                                        <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SHOP"]["TITLE_CART_BASE"]?></div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MIN_SUM']);?>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ORDER_FORM_MINPICE_ALERT']);?>
                                        </div>
                                  
                                       

                                        <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SHOP"]["TITLE_CART_DESIGN"]?></div>

                                        <div class="input-wrap none">
                                            <div class="row">
                                                <div class="col-6">
                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_HEAD_BG'], array("CLASS_DIV_INPUT"=> "to-right"));?>

                                                </div>
                                            </div>
                                      

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_HEAD_TIT']);?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_ADD_NAME']);?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_ADDED_NAME']);?>
                      
                                        </div>

                                        <div class="input-wrap big">

                                            <a href="/bitrix/admin/concept_phoenix_admin_index.php?lang=ru&site_id=<?=SITE_ID?>" target="_blank"><span class="bord"><?=GetMessage("PHOENIX_CART_MESSAGE_COMMENT_LINK")?></span></a>

                                            <div class="spec-comment no-line italic">
                                                <?=GetMessage("PHOENIX_CART_MESSAGE_COMMENT")?>
                                            </div>

                                        </div>

                                        <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SHOP"]["TITLE_CART_OTHER"]?></div>

                                        <div class="input-wrap middle-sm">

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['BASKET_FILTER']);?>

                                        </div>

                                        <div class="input-wrap middle-sm">

                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']['DESCRIPTION']?><span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']['HINT']?>"></span></div>
                                            <?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']['VALUES'])):?>
                                                <ul class='edit-style input-radio-css'>
                                                    <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']['VALUES'] as $arItem):?>

                                                        <li>
                                                   
                                                            <label class="input-radio-css">
                                                                <input class='on-save' <?if($arItem["VALUE"] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]['AGREEMENTS']['VALUE']):?> checked="checked"<?endif;?> name="<?=$arItem["NAME"]?>" type="radio" value="<?=$arItem["VALUE"]?>">          
                                                                <span></span>
                                                                <span class="text"><?=$arItem["DESCRIPTION"]?></span>
                                                            </label>

                                                            <a href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$arResult["AGREEMENT_BLOCK"]["ID"]?>&type=concept_phoenix&ID=<?=$arItem['ID']?>&lang=ru&find_section_section=0&WF=Y" target='_blank' data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=GetMessage("PHOENIX_SET_MAIN_EDIT")?>"></a>
                                                        </li>

                                                    <?endforeach;?>
                                                </ul>

                                            <?endif;?>

                                        </div>

                                        <div class="input-wrap middle-sm">

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_LINK_CONDITIONS']);?>

                                       
                                        </div>

                                        <div class="input-wrap big">

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_BTN_NAME_CONDITIONS']);?>

                                        </div>

                                        <div class="input-wrap">
                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ADVS']['DESCRIPTION']?><a target = "_blank" href="/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?=$PHOENIX_TEMPLATE_ARRAY['ADVS']["IBLOCK_ID"]?>&type=<?=$arParams["IBLOCK_TYPE"]?>&lang=ru&find_section_section=0" class="edit-icon" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=GetMessage("PHOENIX_CART_ADVS_HINT")?>"></a></div>
                                            <ul class="input-checkbox-css">

                                                <?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ADVS']['VALUES'] as $key => $arItem):?>

                                                    <li>
                                                        <label class="input-checkbox-css">
                                                            <input class='on-save' name="phoenix_cart_advs<?=$arItem["VALUE"]?>" <?if(in_array($arItem["VALUE"], $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['ADVS']['VALUE'])):?> checked<?endif;?> type="checkbox" value="<?=$arItem["VALUE"]?>"><span></span><span><?=$arItem["DESCRIPTION"]?></span> 
                                                        </label>
                                                       
                                                    </li>

                                                <?endforeach;?>                                             
                                                
                                            </ul>
                                        </div>

                                        <div class="input-wrap big">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_ADD_ON'])?>
                                        </div>

                                        <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ADMIN"]['SHOP']["TITLE_CART_PAGE_CART"]?></div>
                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['TITLE_CART_PAGE_CART'])?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_PAGE_TITLE'])?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_PAGE_HEADBG'])?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_PAGE_EMPTY_MESS'])?>

                                            <?/*CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_PAGE_ORDER_COMPLITED_MESS'])*/?>
                                        </div>

                                        <div class="input-wrap">

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['TEMPLATE_BASKET'])?>

                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['COUPON'])?>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['GIFTS_ON'])?>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['SHOW_DELIVERY_PARENT_NAMES'])?>
                                        </div>

                                        
                                    </div>

                                </div>

                                <div class="set-content" data-set='search'>

                                    <div class="input-wrap">

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['ACTIVE']);?>

                                    </div>

                                    <div class="more-option <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['ACTIVE']['VALUES']['ACTIVE']["VALUE"]):?> on<?endif;?>" data-show-options='<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['ACTIVE']['VALUES']['ACTIVE']["SHOW_OPTIONS"]?>'>

                                        <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['SEARCH_PAGE']);?>

                                        <div class="input-wrap">
                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['SEARCH_IN']['DESCRIPTION']?></div>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['SEARCH_IN']);?>

                                        </div>

                                        <div class="input-wrap">
                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['SHOW_IN']['DESCRIPTION']?></div>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['SHOW_IN']);?>
                                        </div>

                                        <div class="input-wrap">
                                            <div class="name bold"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['QUEST_IN']['DESCRIPTION']?></div>
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['QUEST_IN']);?>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['SKIP_CATALOG_PREVIEW_TEXT']);?>
                                        </div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['NOT_FOUND']);?>
                                        </div>



                                        <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY['ADMIN']['SEARCH']["SECT_HINT"]?></div>

                                        <div class="input-wrap">

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['HINT_DEFAULT']);?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['HINT_CATALOG']);?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['HINT_NEWS']);?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['HINT_BLOG']);?>

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['HINT_ACTIONS']);?>


                                        </div>

                                        <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ADMIN']['SEARCH']["SECTION_NAME_DESIGN"]?></div>

                                        <div class="input-wrap middle">
                        
                                            <div class="row">

                                                <div class="col-6">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['HEAD_BG'], array("CLASS_DIV_INPUT"=> "to-right"));?>

                                                </div>

                                                <div class="col-6">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['CATALOG_IC'], array("CLASS_DIV_INPUT"=> "to-left"));?>
                                                    
                                                </div>

                                       

                                                <div class="col-6">

                                                    
                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['BLOG_IC'], array("CLASS_DIV_INPUT"=> "to-right"));?>

                                                </div>

                                                <div class="col-6">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['NEWS_IC'], array("CLASS_DIV_INPUT"=> "to-left"));?>

                                                </div>

                                                <div class="col-6">

                                                    <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['ACTIONS_IC'], array("CLASS_DIV_INPUT"=> "to-right"));?>
                                                    
                                                </div>
                                            </div>
                                        </div>


                                        <div class="section-title"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ADMIN']['SEARCH']["SECTION_NAME_PLACEHOLDER"]?></div>

                                        <div class="input-wrap">
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['PLACEHOLDER_CATALOG']);?>
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['PLACEHOLDER_BLOG']);?>
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['PLACEHOLDER_NEWS']);?>
                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['PLACEHOLDER_ACTIONS']);?>
                                        </div>

                                        <div class="input-wrap">

                                            <?CPhoenix::getOptionHtmlInPublic($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]['FASTSEARCH_ACTIVE']);?>

                                        </div>

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
                                <button class="active btn-submit-main-set" name="form-submit" type="button" value=""><?=GetMessage("PHOENIX_SETTINGS_LIST_SAVE")?></button>
                               </td>
                            <td class='set-right'>
                                <div class='phoenix-set-close'><?=GetMessage("PHOENIX_SETTINGS_LIST_CLOSE")?></div>
                            </td>
                        </tr>
                    </table>
                    
                </div>
            </form>
        
        </div>

        <script>initOptionsByJs();</script>

    <?endif;?>


    <?if($arParams['PANEL'] == "addpage"):?>
        
        <div class="inner">

            <div class="phoenix-set-head row no-margin align-items-center">
         
                <div class="col-3 phoenix-set-image"><div></div></div>
                <div class="col-6 phoenix-set-name bold"><?=GetMessage("PHOENIX_SETTINGS_LIST_ADDPAGE")?></div>
                <div class="col-3"></div>
               

                <a class="phoenix-set-close"></a>
                
            </div>

            <form action="/" class="form-sets-js form-page-list" enctype="multipart/form-data" method="post" role="form">

                <input type="hidden" name="site_id" value="<?=SITE_ID?>" />
                

                <?if(!empty($arResult["SECTIONS"])):?>
                    <?foreach($arResult["SECTIONS"] as $arSection):?>
                        <input type="hidden" name="page_active<?=$arSection['ID']?>" value="<?=$arSection['ACTIVE']?>">
                    <?endforeach;?>
                <?endif;?>



                <div class="phoenix-set-content">
                    <ul class="list">

                        <?if(!empty($arResult["SECTIONS"])):?>
                    
                            <?foreach($arResult["SECTIONS"] as $key=>$arSection):?>
                                
                                <li>
                                
                                    <?if($arSection["ID"] == $arParams["CURRENT_SECTION_ID"]):?>
                                        <div class="active" data-toggle="tooltip" data-placement="right" title="<?=GetMessage("PHOENIX_SETTINGS_LIST_CURRENT_PAGE")?>"></div>
                                    <?endif;?>
                                    
                                    <?if($key == 0):?>
                                        <?$url = SITE_DIR;?>
                                    <?else:?>
                                        <?$url = $arSection["SECTION_PAGE_URL"];?>
                                    <?endif;?>
                              
                                    <?$url2 = $arResult["SERVER_URL"].$url;?>
                                

                                    <span class="list-name">
                                        
                                        <?if($arSection["ID"] != $arParams["CURRENT_SECTION_ID"]):?>
                                            <a href="<?=$url2?>"><span class="bord-bot"><?=$arSection["NAME"]?></span></a>
                                        <?else:?>
                                            <?=$arSection["NAME"]?>
                                        <?endif;?>
                                       
                                    </span>


                                    
                                    <span class="icons-wrap parent_copy">
                                        
                                        <a data-clipboard-text="<?=$url2?>" class="icon list-copy" data-toggle="tooltip" data-placement="top" title="<?=GetMessage("PHOENIX_SETTINGS_LIST_COPY")?>"></a>
                                        
                                        <span class="al copy-success"><?=GetMessage("PHOENIX_SETTINGS_LIST_ALL_SETTINGS_ALERT")?></span>
                                    </span>

                                    <div class="more_set">
                                        <table class='more_set'>
                                            <tr>
                                                <td class='left_set'>
                                                    <span><?=GetMessage("PHOENIX_SETTINGS_LIST_SORT")?></span>
                                                    <input type="text" name = 'sort_<?=$arSection["ID"]?>' class="sort" value="<?=$arSection["SORT"]?>">
                                                </td>
                                                <td class='right_set'>
                                                    <div class="ignite<?if(strlen($arSection['ACTIVE']=='Y')):?> on<?endif;?>">
                                                        <span class="off"><?=GetMessage("PHOENIX_SETTINGS_LIST_PAGE_OFF")?></span>
                                                        <span class="on"><?=GetMessage("PHOENIX_SETTINGS_LIST_PAGE_ON")?></span>
                                                        <span class="toggle-indicator" data-page-id = '<?=$arSection["ID"]?>'>
                                                            <span class="toggle-icon"></span>
                                                            <span class="toggle-icon-overlay"></span>
                                                        </span> 
                                               
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    
                                    <div class="clearfix"></div>
                                    
                                   
                                    <?if($arSection["ID"] == $arParams["CURRENT_SECTION_ID"]):?>
                                    
                                        <div class="domen">
                                            <div class="arrow"></div>
                                            <?=$url2?>
                                        </div>

                                    <?endif;?>
                                    
                                 
                
                                </li>
                            
                            <?endforeach;?>

                        <?endif;?>
                        
                    </ul>

                    <div class="button-wrap">
                        <a class="plus new_page phoenix-sets-open" data-open-set='newpage'><?=GetMessage("PHOENIX_SETTINGS_LIST_ADD_BUTTON")?></a>
                        <a class="edit open_edit"><?=GetMessage("PHOENIX_SETTINGS_LIST_EDIT_SETS_EDIT_LIST")?></a>
                    </div>

                    <div class="more_edit">
                        <div class="load">
                            <div class="xLoader form-preload"><div class="audio-wave"><span></span><span></span><span></span><span></span><span></span></div></div>
                        </div>
                        <button class="active btn-green btn-submit-page-list" name="form-submit" type="button" value=""><?=GetMessage("PHOENIX_SETTINGS_LIST_SAVE")?></button>

                        <a class="edit close_edit"><?=GetMessage("PHOENIX_SETTINGS_LIST_CANCEL")?></a>
                    </div>
                </div>
               
            </form>
        
        </div>
      
    <?endif;?>


    <?if($arParams['PANEL'] == "newpage"):?>
        
        <div class="inner">

            <div class="phoenix-set-head row no-margin align-items-center">

                <div class="col-8 phoenix-set-name bold"><?=GetMessage("PHOENIX_SETTINGS_LIST_NEWPAGE")?></div>
                <div class="col-1"></div>
         

                <a class="phoenix-set-close"></a>
                
            </div>

            <form action="/" class="form-sets-js set-form form-add-page" enctype="multipart/form-data" method="post" role="form">

                <input type="hidden" name="site_id" value="<?=SITE_ID?>" />
                <input type="hidden" name="server_url" value="<?=$arResult["SERVER_URL"]?>" />
                <input type="hidden" name="iblock_id" id="iblock_id" value="<?=$arParams["IBLOCK_ID"]?>" />

                <div class="phoenix-set-content">

                    <div class="input-wrap">
                        <div class="name"><?=GetMessage("PHOENIX_SETTINGS_NEW_PAGE_NAME")?></div>

                        <div class="input">
                            <input type="text" class="name require" name="newpage_name" value="">
                        </div>

                    </div>

                    <div class="input-wrap">
                        <div class="name"><?=GetMessage("PHOENIX_SETTINGS_NEW_PAGE_ID")?> <span class="answer" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?=GetMessage("PHOENIX_SETTINGS_NEW_PAGE_ID_HINT")?>"></span></div>

                        <div class="input">
                            <input type="text" class="name require" name="newpage_id" value="">
                        </div>

                    </div>

                    <div class="input-wrap">
                        <div class="load">
                            <div class="xLoader form-preload"><div class="audio-wave"><span></span><span></span><span></span><span></span><span></span></div></div>
                        </div>
                        <button class="active btn-green btn-submit-add-page" name="form-submit" type="button" value=""><?=GetMessage("PHOENIX_SETTINGS_NEW_PAGE_SAVE")?></button>
                    </div>

                </div>
               
            </form>
        
        </div>
    
    <?endif;?>


    <?if($arParams['PANEL'] == "modals"):?>
        
        <div class="inner blur-container">

            <div class="phoenix-set-head row no-margin align-items-center">
                <div class="col-3 phoenix-set-image"><div></div></div>
                <div class="col-6 phoenix-set-name bold"><?=GetMessage("PHOENIX_SETTINGS_LIST_MODAL")?></div>
                <div class="col-3"></div>
                

                <a class="phoenix-set-close"></a>
                
            </div>

            <div class="phoenix-set-content">


                <?if(!empty($arResult['MODALS_IN_SECTION'])):?>
                    <?foreach($arResult['MODALS_IN_SECTION'] as $arSectModal):?>

                        <div class="list-wrap">

                            <div class="list-title"><?=$arSectModal['NAME']?></div>

                            <ul class='list'>
                                <?if(!empty($arSectModal['ELEMENTS'])):?>
                                    <?foreach($arSectModal['ELEMENTS'] as $arModal):?>
                                        <li>
                                            <span class="list-name"><a class='call-modal callmodal from-modal' data-call-modal="modal<?=$arModal['ID']?>"><span class="bord-bot"><?=$arModal['NAME']?></span></a></span><a data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=GetMessage("PHOENIX_SETTINGS_DEF_EDIT")?>" class='edit-list' href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$arModal['IBLOCK_ID']?>&type=<?=$arParams["IBLOCK_TYPE"]?>&ID=0&lang=ru&IBLOCK_SECTION_ID=<?=$arModal['IBLOCK_SECTION_ID']?>&find_section_section=<?=$arModal['IBLOCK_SECTION_ID']?>&from=iblock_list_admin" target="_blank"></a>
                                            
                                        </li>
                                    <?endforeach;?>
                                <?endif;?>
                              
                            </ul>

                        </div>

                    <?endforeach;?>
                <?endif;?>

                <?if(!empty($arResult['MODALS_ELEMENTS_NO_SECTION'])):?>
                    <div class="list-wrap">
                        <ul class='list'>
                            <?foreach($arResult['MODALS_ELEMENTS_NO_SECTION'] as $arModal):?>

                                <li>
                                    <span class="list-name"><a class='call-modal callmodal from-modal' data-call-modal="modal<?=$arModal['ID']?>"><span class="bord-bot"><?=$arModal['NAME']?></span></a></span><a data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=GetMessage("PHOENIX_SETTINGS_DEF_EDIT")?>" class='edit-list' href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$arModal['IBLOCK_ID']?>&type=<?=$arParams["IBLOCK_TYPE"]?>&ID=<?=$arModal['ID']?>&lang=ru&find_section_section=0&WF=Y" target="_blank"></a>


                                </li>

                            <?endforeach;?>
                        </ul>
                    </div>
                <?endif;?>


                <div class="button-wrap">
                    <a class="plus" href='/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$arResult['MODALS_ELEMENTS_IBLOCK_ID']?>&type=<?=$arParams["IBLOCK_TYPE"]?>&ID=0&lang=ru&IBLOCK_SECTION_ID=0&find_section_section=0&from=iblock_list_admin' target="_blank"><?=GetMessage("PHOENIX_ADD_MODAL")?></a>
              
                </div>

            </div>

            
        
        </div>
        
    <?endif;?>


    <?if($arParams['PANEL'] == "forms"):?>
        
        <div class="inner blur-container">

            <div class="phoenix-set-head row no-margin align-items-center">

                <div class="col-3 phoenix-set-image"><div></div></div>
                <div class="col-6 phoenix-set-name bold"><?=GetMessage("PHOENIX_SETTINGS_LIST_FORMS")?></div>
                <div class="col-3"></div>
             

                <a class="phoenix-set-close"></a>
                
            </div>

            <div class="phoenix-set-content">

                <?if(!empty($arResult['FORMS_IN_SECTION'])):?>
                    <?foreach($arResult['FORMS_IN_SECTION'] as $arSectForm):?>

                        <?if(empty($arSectForm['ELEMENTS'])) continue;?>

                        <div class="list-wrap">

                            <div class="list-title"><?=$arSectForm['NAME']?></div>

                            <ul class='list'>
                                <?if(!empty($arSectForm['ELEMENTS'])):?>
                                    <?foreach($arSectForm['ELEMENTS'] as $arForm):?>
                                        <li>
                                            <span class="list-name"><a class='call-modal callform from-modal' data-call-modal="form<?=$arForm['ID']?>"><span class="bord-bot"><?=$arForm['NAME']?></span></a></span><a data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=GetMessage("PHOENIX_SETTINGS_DEF_EDIT")?>" class='edit-list' href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$arForm['IBLOCK_ID']?>&type=<?=$arParams["IBLOCK_TYPE"]?>&ID=<?=$arForm['ID']?>&lang=ru&find_section_section=<?=$arForm['IBLOCK_SECTION_ID']?>&WF=Y" target="_blank"></a>
                                        </li>
                                    <?endforeach;?>
                                <?endif;?>
                              
                            </ul>

                        </div>

                    <?endforeach;?>
                <?endif;?>

                <?if(!empty($arResult['FORMS_ELEMENTS_NO_SECTION'])):?>
                    <div class="list-wrap">
                        <ul class='list'>
                            <?foreach($arResult['FORMS_ELEMENTS_NO_SECTION'] as $arForm):?>

                                <li>
                                    <span class="list-name"><a data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=GetMessage("PHOENIX_SETTINGS_DEF_EDIT")?>" class='call-modal callform from-modal' data-call-modal="form<?=$arForm['ID']?>"><span class="bord-bot"><?=$arForm['NAME']?></span></a></span><a class='edit-list' href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$arForm['IBLOCK_ID']?>&type=<?=$arParams["IBLOCK_TYPE"]?>&ID=<?=$arForm['ID']?>&lang=ru&find_section_section=0&WF=Y" target="_blank"></a>
                                </li>
                                
                            <?endforeach;?>
                        </ul>
                    </div>
                <?endif;?>

                <div class="button-wrap">
                    <a class="plus" href='/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$arResult['FORMS_ELEMENTS_IBLOCK_ID']?>&type=<?=$arParams["IBLOCK_TYPE"]?>&ID=0&lang=ru&IBLOCK_SECTION_ID=0&find_section_section=0&from=iblock_list_admin' target="_blank"><?=GetMessage("PHOENIX_ADD_FORM")?></a>
                    <a class="edit instr" href='https://goo.gl/ffvH6d' target="_blank"><?=GetMessage("PHOENIX_FORM_HINT")?></a>
                </div>

            </div>

        </div>

    <?endif;?>


    <?if($arParams['PANEL'] == "iblist"):?>
        
        <div class="inner">

            <div class="phoenix-set-head row no-margin align-items-center">
                <div class="col-3 phoenix-set-image"><div></div></div>
                <div class="col-6 phoenix-set-name bold"><?=GetMessage("PHOENIX_SETTINGS_IBLIST_TITLE")?></div>
                <div class="col-3"></div>
                

                <a class="phoenix-set-close"></a>
                
            </div>

            <div class="phoenix-set-content">

                <ul class='list'>

                    <?if(!empty($arResult['IBLIST'])):?>
                        <?foreach($arResult['IBLIST'] as $ibItem):?>

                            <li>

                                <span class="list-name"><a target="_blank" href="/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?=$ibItem["ID"]?>&type=<?=$ibItem["IBLOCK_TYPE_ID"]?>&lang=ru&find_section_section=0"><span class="bord-bot"><?=$ibItem["NAME"]?></span></a></span>
                                
                            </li>

                        <?endforeach;?>
                    <?endif;?>

                </ul>


            </div>

        </div>
        
    <?endif;?>


    

<?endif;?>