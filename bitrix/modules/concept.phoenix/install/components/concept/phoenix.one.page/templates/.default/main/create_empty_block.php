<div class="block light empty-block">
    
    <div class="shadow-tone"></div>
    <div class="top-shadow"></div>


 
    <div class="head def">
        
        <div class="container">
        
            
            <h2 class='main1'><?=GetMessage("PHOENIX_EMPTYBLOCK_TITLE1")?> <?=GetMessage("PHOENIX_EMPTYBLOCK_QU")?><?=$arSection["NAME"]?><?=GetMessage("PHOENIX_EMPTYBLOCK_QU2")?> <?=GetMessage("PHOENIX_EMPTYBLOCK_TITLE2")?></h2>

            <div class="descrip"><?=GetMessage("PHOENIX_EMPTYBLOCK_SUBTITLE")?></div>
                


        </div>
        
    </div>
    
    <div class="content">

        <div class="container">
            <div class="row">
                
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    
                    <div class="start-block">
                        
                        <div class="icon start1"></div>
                        
                        <div class="text"><?=GetMessage("PHOENIX_EMPTYBLOCK_STEP1")?></div>
                        
                        <div class="button">
                            <a href='/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['MENU']["IBLOCK_ID"]?>&type=<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['MENU']["IBLOCK_TYPE"]?>&lang=ru&find_section_section=0' target='_blank' class="button-def main-color elips big"><?=GetMessage("PHOENIX_EMPTYBLOCK_BUTTON1")?></a>
                        </div>


                        <!--div class="desc-copy">
                            <div class="parent_copy">
                                
                                <a data-clipboard-text="<?=$APPLICATION->GetCurPage()?>" class="list-copy"><?=GetMessage("PHOENIX_EMPTYBLOCK_COPY_URL")?></a>
                                
                                <span class="copy-success"><?=GetMessage("PHOENIX_EMPTYBLOCK_COPY_URL_SUCCSESS")?></span>

                            </div>
                        </div-->
                    
                    </div>
                    
                </div>
                
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    
                    <div class="start-block">
                        
                        <div class="icon start2"></div>
                        
                        <div class="text"><?=GetMessage("PHOENIX_EMPTYBLOCK_STEP2")?></div>
                        
                        <div class="button">

                            <a class="button-def main-color elips big" target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_ID"]?>&type=<?=$PHOENIX_TEMPLATE_ARRAY['CONSTR']["IBLOCK_TYPE"]?>&ID=0&lang=ru&IBLOCK_SECTION_ID=<?=$arSection["ID"]?>&find_section_section=<?=$arSection["ID"]?>&from=iblock_list_admin"><?=GetMessage("PHOENIX_EMPTYBLOCK_BUTTON2")?></a>
                        </div>
                    
                    </div>
                    
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                    <div class="start-block">
                        
                        <div class="icon start3"></div>
                        
                        <div class="text"><?=GetMessage("PHOENIX_EMPTYBLOCK_STEP3")?></div>
                        
                        <div class="button">

                            <a class="button-def main-color elips big phoenix-sets-open" data-open-set="seo"><?=GetMessage("PHOENIX_EMPTYBLOCK_BUTTON3")?></a>
                        </div>
                    
                    </div>

                </div>
            
            </div>
        </div>
        
    </div>

</div>