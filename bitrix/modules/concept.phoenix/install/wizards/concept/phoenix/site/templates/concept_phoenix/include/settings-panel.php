<?global $PHOENIX_TEMPLATE_ARRAY;?>
<div class="hidden-sm hidden-xs">
	
    <?/*<input type="hidden" name="set-phoenix-panel-section-id" value="<?=$GLOBALS["PHOENIX_CURRENT_SECTION_ID"]?>">
	<input type="hidden" name="set-phoenix-panel-element-id" value="<?=$GLOBALS["PHOENIX_ELEMENT_ID"]?>">
	<input type="hidden" name="set-phoenix-panel-current-page" value="<?=$GLOBALS["PHOENIX_CURRENT_PAGE"]?>">
	<input type="hidden" name="set-phoenix-panel-current-dir" value="<?=$GLOBALS["PHOENIX_CURRENT_DIR"]?>">
	<input type="hidden" name="set-phoenix-panel-current-tmpl" value="<?=$GLOBALS["PHOENIX_CURRENT_TMPL"]?>">*/?>

    <div class="phoenix-main-setting">
        <div class="phoenix-btn mgo-widget-call_pulse">
        </div>
        <span><?=GetMessage("PHOENIX_SETTINGS_PANEL_LIST_BUTTON_TIP");?></span>
    </div>


    <div class="phoenix-sets-list-wrap">
    	<div class="phoenix-sets-list">

    		<div class="phoenix-sets-list-table">  

    			<div class="phoenix-sets-list-cell">  	

	                <?
	                	$pageIblockID = $PHOENIX_TEMPLATE_ARRAY["PAGES"][$GLOBALS["PHOENIX_CURRENT_PAGE"]];

	                	$arPages = array("news", "blog", "actions", "catalog", "brand", "compare", "personal", "basket");
	                    $arPage["news"] = "tab_cont_news_tab";
	                    $arPage["blog"] = "tab_cont_blog_tab";
	                    $arPage["actions"] = "tab_cont_action_tab";
	                    $arPage["catalog"] = "tab_cont_catalog_tab";
	                    $arPage["cart"] = "tab_cont_shop";
	                    $arPage["brand"] = "tab_cont_brand_tab";
	                    $arPage["compare"] = "tab_cont_compare_tab";
	                    $arPage["personal"] = "tab_cont_personal_tab";
	                    $arPage["basket"] = "tab_cont_basket_tab";
	                ?>

	                <?if(in_array($GLOBALS["PHOENIX_CURRENT_PAGE"], $arPages)):?>

	                	<?if($GLOBALS["PHOENIX_CURRENT_DIR"] == "main"):?>

	                        <a class="phoenix-sets-list-item sectedit" href='/bitrix/admin/concept_phoenix_admin_index.php?site_id=<?=SITE_ID?>&tab=<?=$arPage[$GLOBALS["PHOENIX_CURRENT_PAGE"]]?>' target='_blank'>
	                            <span class="set-icon"><?=GetMessage("PHOENIX_SETTINGS_PANEL_MAIN_".$GLOBALS["PHOENIX_CURRENT_PAGE"])?></span>
	                        </a>

	                    <?elseif($GLOBALS["PHOENIX_CURRENT_DIR"] == "section"):?>

	                    	<a class="phoenix-sets-list-item sectedit" href='/bitrix/admin/iblock_section_edit.php?IBLOCK_ID=<?=$pageIblockID?>&type=<?=$PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_TYPE"]?>&ID=<?=$GLOBALS["PHOENIX_CURRENT_SECTION_ID"]?>&lang=ru&find_section_section=0' target='_blank'>
	                            <span class="set-icon"><?=GetMessage("PHOENIX_SETTINGS_PANEL_SECT_".$GLOBALS["PHOENIX_CURRENT_PAGE"])?></span>
	                        </a>


	                    <?elseif($GLOBALS["PHOENIX_CURRENT_DIR"] == "element"):?>


	                    	<a class="phoenix-sets-list-item sectedit" href='/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$pageIblockID?>&type=<?=$PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_TYPE"]?>&ID=<?=$GLOBALS["PHOENIX_ELEMENT_ID"]?>&find_section_section=<?=$GLOBALS["PHOENIX_CURRENT_SECTION_ID"]?>&WF=Y' target='_blank'>
	                            <span class="set-icon"><?=GetMessage("PHOENIX_SETTINGS_PANEL_ELEM_".$GLOBALS["PHOENIX_CURRENT_PAGE"])?></span>
	                        </a>

	                    <?endif;?>

	                <?else:?>

	                	<?if($GLOBALS["PHOENIX_CURRENT_PAGE"] == "cart"):?>

	                		<a class="phoenix-sets-list-item sectedit" href='/bitrix/admin/concept_phoenix_admin_index.php?site_id=<?=SITE_ID?>&tab=<?=$arPage[$GLOBALS["PHOENIX_CURRENT_PAGE"]]?>' target='_blank'>
	                            <span class="set-icon"><?=GetMessage("PHOENIX_SETTINGS_PANEL_MAIN_".$GLOBALS["PHOENIX_CURRENT_PAGE"])?></span>
	                        </a>

	                	<?else:?>

	                		<a class="phoenix-sets-list-item sectedit" href='/bitrix/admin/iblock_section_edit.php?IBLOCK_ID=<?=$PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_ID"]?>&type=<?=$PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_TYPE"]?>&ID=<?=$GLOBALS["PHOENIX_CURRENT_SECTION_ID"]?>&lang=ru&find_section_section=0' target='_blank'>
	                            <span class="set-icon"><?=GetMessage("PHOENIX_SETTINGS_PANEL_LIST_PAGE")?></span>
	                        </a>

	                	<?endif;?>


	                <?endif;?>
	            </div>

	            <?if( $GLOBALS["PHOENIX_CURRENT_PAGE"] != "actions" && $GLOBALS["PHOENIX_CURRENT_PAGE"] != "brand" && in_array($GLOBALS["PHOENIX_CURRENT_PAGE"], $arPages) && (($GLOBALS["PHOENIX_CURRENT_DIR"] == "main" || $GLOBALS["PHOENIX_CURRENT_DIR"] == "section"))):?>

                    <?
                        $sectionID = (intval($GLOBALS["PHOENIX_CURRENT_SECTION_ID"]) <= 0)? 0: $GLOBALS["PHOENIX_CURRENT_SECTION_ID"];

                    ?>


                    <div class="phoenix-sets-list-cell">

                        <a class="phoenix-sets-list-item addsect" href="/bitrix/admin/iblock_section_edit.php?IBLOCK_ID=<?=$pageIblockID?>&type=<?=$PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_TYPE"]?>&ID=0&lang=ru&IBLOCK_SECTION_ID=<?=$sectionID?>&find_section_section=<?=$sectionID?>&from=iblock_list_admin" target='_blank'>
                            <span class="set-icon"><?=($sectionID != 0)?GetMessage("PHOENIX_SETTINGS_LIST_SUBSECTEDIT"):GetMessage("PHOENIX_SETTINGS_LIST_SECTEDIT")?></span>
                        </a>
                    </div>

                <?endif;?>


                <?
                    $showAddBlock = true;
                    if($GLOBALS["PHOENIX_CURRENT_PAGE"] == "cart" || $GLOBALS["PHOENIX_CURRENT_PAGE"] == "compare" || $GLOBALS["PHOENIX_CURRENT_PAGE"] == "personal" || $GLOBALS["PHOENIX_CURRENT_PAGE"] == "basket")
                    {
                        $showAddBlock = false;
                    }

                ?>

                <?if($showAddBlock):?>
                    <div class="phoenix-sets-list-cell">

                        <?if(in_array($GLOBALS["PHOENIX_CURRENT_PAGE"], $arPages)):?>

                        <?
                        $getParams = "";

                        if($GLOBALS["PHOENIX_CURRENT_PAGE"] == "catalog")
                            $getParams .= "&PRODUCT_TYPE=P";
                        ?>

                       
                            <a class="phoenix-sets-list-item addblock" href='/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$pageIblockID?>&type=<?=$PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_TYPE"]?>&ID=0&lang=ru&IBLOCK_SECTION_ID=<?=$GLOBALS["PHOENIX_CURRENT_SECTION_ID"]?>&find_section_section=<?=$GLOBALS["PHOENIX_CURRENT_SECTION_ID"]?>&from=iblock_list_admin<?=$getParams?>' target='_blank'>
                                <span class="set-icon">
                                    <?=GetMessage("PHOENIX_SETTINGS_PANEL_ADD_".$GLOBALS["PHOENIX_CURRENT_PAGE"])?>
                                </span>
                            </a>

                        <?else:?>
                            <a class="phoenix-sets-list-item addblock" href='/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_ID"]?>&type=<?=$PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_TYPE"]?>&ID=0&lang=ru&IBLOCK_SECTION_ID=<?=$GLOBALS["PHOENIX_CURRENT_SECTION_ID"]?>&find_section_section=<?=$GLOBALS["PHOENIX_CURRENT_SECTION_ID"]?>&from=iblock_list_admin' target='_blank'>
                                <span class="set-icon">
                                    <?=GetMessage("PHOENIX_SETTINGS_LIST_ADDBLOCK")?>
                                </span>
                            </a>
                        <?endif;?>
                    </div>
                <?endif;?>

                <div class="phoenix-sets-list-cell">
                    <a class="phoenix-sets-list-item seo phoenix-sets-open" data-open-set='seo'>
                        <span class="seo-name">SEO</span>
                        <span class="set-icon">

                            <?if(in_array($GLOBALS["PHOENIX_CURRENT_PAGE"], $arPages) || $GLOBALS["PHOENIX_CURRENT_PAGE"] == "cart"):?>
                                <?=GetMessage("PHOENIX_SETTINGS_LIST_SEO_PAGE")?>
                            <?else:?>
                                <?=GetMessage("PHOENIX_SETTINGS_LIST_SEO")?>
                            <?endif;?>

                        </span>

                        <span class="status-seo"></span>
                    </a>
                </div>


                <div class="phoenix-sets-list-cell">
                    <div class="phoenix-sets-list-close">
                        
                    </div>
                    <span><?=GetMessage("PHOENIX_SETTINGS_PANEL_CLOSE_LIST_SET")?></span>
                </div>



    		</div>

    	</div>


    	<div class="phoenix-sets-list-left">
           
            <a class="phoenix-sets-list-item phoenix-sets-open edit-sets" data-open-set='edit-sets'>
                <span class="set-icon"><?=GetMessage("PHOENIX_SETTINGS_PANEL_LIST_EDIT_SETS")?></span>
            </a>
            <a class="phoenix-sets-list-item phoenix-sets-open addpage" data-open-set='addpage'>
                <span class="set-icon"><?=GetMessage("PHOENIX_SETTINGS_PANEL_LIST_ADDPAGE")?></span>
            </a>
            <a class="phoenix-sets-list-item phoenix-sets-open forms" data-open-set='forms'>
                <span class="set-icon"><?=GetMessage("PHOENIX_SETTINGS_PANEL_LIST_FORMS")?></span>
            </a>
            <a class="phoenix-sets-list-item phoenix-sets-open modals" data-open-set='modals'>
                <span class="set-icon"><?=GetMessage("PHOENIX_SETTINGS_PANEL_LIST_MODAL")?></span>
            </a>

            <a class="phoenix-sets-list-item phoenix-sets-open iblist" data-open-set='iblist'>
                <span class="set-icon"><?=GetMessage("PHOENIX_SETTINGS_PANEL_IBLIST")?></span>
            </a>

        </div>
    </div>

    <div class="sets-shadow"></div>


	<div class="phoenix-setting edit-sets"></div>
    <div class="phoenix-setting addpage"></div>
    <div class="phoenix-setting newpage"></div>
    <div class="phoenix-setting modals list-style"></div>
    <div class="phoenix-setting forms list-style"></div>
    <div class="phoenix-setting iblist list-style"></div>
    <div class="phoenix-setting newpage"></div>
    <div class="phoenix-setting seo"></div>
    
    <input type="hidden" name="currentSectionId" value="<?=$GLOBALS["PHOENIX_CURRENT_SECTION_ID"]?>">

    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["GLOBALS"]["ITEMS"]["HIDE_ADV"]["VALUE"]["ACTIVE"]=="Y"):?>

        <div class="hide-adv" data-user="<?=$USER->getId()?>"></div>

    <?endif;?>

</div>