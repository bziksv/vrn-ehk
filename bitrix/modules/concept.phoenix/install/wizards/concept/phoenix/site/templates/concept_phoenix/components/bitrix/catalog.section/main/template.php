<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main;

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

global $PHOENIX_TEMPLATE_ARRAY;


$page = $APPLICATION->GetCurPage();
$filter = $clear = array();
preg_match("/\/filter\//", $page, $filter);
preg_match("/\/clear\//", $page, $clear);

$showSeoText = empty($filter) || !empty($clear);
?>


<?$this->SetViewTarget('catalog-top-desc');?>

    <?if(strlen($arResult["~UF_PHX_CTLG_TOP_T"]) > 0 && strlen($GLOBALS["~UF_PHX_CTLG_TOP_T"]) <= 0 && $arResult["NAV_RESULT"]->NavPageNomer == 1 && $showSeoText):?>

        <div class="top-description text-content">
            <?=$arResult["~UF_PHX_CTLG_TOP_T"]?>
        </div>

    <?endif;?>

    <?if(strlen($GLOBALS["~UF_PHX_CTLG_TOP_T"]) > 0 && $arResult["NAV_RESULT"]->NavPageNomer == 1):?>

        <div class="top-description text-content">
            <?=$GLOBALS["~UF_PHX_CTLG_TOP_T"]?>
        </div>

    <?endif;?>

<?$this->EndViewTarget();?> 


<?$this->SetViewTarget('catalog-bottom-desc');?>

    <?$GLOBALS["BOTTOM_TEXT"] = $GLOBALS["~DESCRIPTION"];?>

    
    <?if($arResult["UF_PHX_CTLG_TXT_P_ENUM"]["XML_ID"] == "short" && strlen($arResult["~DESCRIPTION"]) && strlen($GLOBALS["BOTTOM_TEXT"]) <= 0 && $arResult["NAV_RESULT"]->NavPageNomer == 1 && $showSeoText):?>

        <div class="bottom-description text-content"><?=$arResult["~DESCRIPTION"]?></div>

    <?endif;?>

    <?if($arResult["UF_PHX_CTLG_TXT_P_ENUM"]["XML_ID"] == "short" && strlen($GLOBALS["BOTTOM_TEXT"]) > 0 && $arResult["NAV_RESULT"]->NavPageNomer == 1):?>

        <div class="bottom-description text-content"><?=$GLOBALS["BOTTOM_TEXT"]?></div>

    <?endif;?>

    <?if($arResult["UF_PHX_CTLG_TXT_P_ENUM"]["XML_ID"] == "long" && strlen($arResult["~DESCRIPTION"]) && strlen($GLOBALS["BOTTOM_TEXT"]) <= 0 && $arResult["NAV_RESULT"]->NavPageNomer == 1 && $showSeoText):?>
        <div class="bottom-description-full text-content">
            
            <div class="container">
              
                <?=$arResult["~DESCRIPTION"]?>
              
            </div>
        
        </div>
    <?endif;?>

    <?if($arResult["UF_PHX_CTLG_TXT_P_ENUM"]["XML_ID"] == "long" && strlen($GLOBALS["BOTTOM_TEXT"]) > 0 && $arResult["NAV_RESULT"]->NavPageNomer == 1):?>
        <div class="bottom-description-full text-content">
            
            <div class="container">
              
                <?=$GLOBALS["BOTTOM_TEXT"]?>
              
            </div>
        
        </div>
    <?endif;?>


<?$this->EndViewTarget();?> 

<?$countItems = 0;?>

<?if( !empty($arResult["ITEMS"]) ):?>

    <?

        $countItems = count($arResult["ITEMS"]);

        if($hide_items = isset($arParams["MAX_VISIBLE_ITEMS"]))
            $max_visible_items = intval($arParams["MAX_VISIBLE_ITEMS"]);
    ?>

    <div class="catalog-block">

        <?
            $classItem = "";

            if( $arResult['VIEW'] == "FLAT" )
            {
                $colsitem = "4";
                
                if($arParams["COLS"] == "4")
                    $colsitem = "3";

                $colsitemLg = "4";

                if(isset($arParams["COLS_LG"]))
                {
                    if($arParams["COLS_LG"] == "2")
                        $colsitemLg = "6";

                    if($arParams["COLS_LG"] == "3")
                        $colsitemLg = "4";

                    if($arParams["COLS_LG"] == "4")
                        $colsitemLg = "3";
                }
            }
        ?>

        <div class="catalog-list <?=$arResult["VIEW"]?> <?=($hide_items)? "show-hidden-parent":"";?> <?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_AVAILABLE_PRICE_COUNT']['VALUE']['ACTIVE'] === 'Y')?'size-lg':''?>">
            <div class="row">

                <script>
                    BX.message({
                        PRICE_TOTAL_PREFIX: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRICE_TOTAL_PREFIX"]?>',
                        RELATIVE_QUANTITY_MANY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_2"]?>',
                        RELATIVE_QUANTITY_FEW: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["DESCRIPTION_2"]?>',
                        RELATIVE_QUANTITY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_NOEMPTY"]?>',
                        RELATIVE_QUANTITY_EMPTY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_EMPTY"]?>',
                        ARTICLE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"]?>',
                    });
                      
                </script>
	

                <?$i = 0;?>
                <?foreach ($arResult['ITEMS'] as $keyItem => $arItem):?>

                    <?
                        $skuProps = array();
                        
                        if( $arResult['VIEW'] == "FLAT" )
                            $classItem = "col-xl-".$colsitem." col-lg-".$colsitemLg." col-md-4 col-6 border-r";

                        $itemIds = $arItem["VISUAL"];
                    ?>



                    <?if($arResult['VIEW'] == "LIST" || $arResult['VIEW'] == "TABLE"):?>
                        <div class="col-12">
                    <?endif;?>

                        <div class="<?=$classItem?> item catalog-item <?=($countItems == ($keyItem+1)) ? 'last-item' : ''?>

                            <?if($hide_items && ($i+1) > $max_visible_items):?>
                                show-hidden-child
                                hidden
                            <?endif;?>

                        " id="<?=$itemIds['ID']?>">

                            <?
                            	$documentRoot = Main\Application::getDocumentRoot();
        						$templatePath = strtolower($arResult['VIEW']).'/template.php';

        						
        						$file = new Main\IO\File($documentRoot.$templateFolder.'/'.$templatePath);
        						if ($file->isExists())
        						{
        							include($file->getPath());
        						}

                            ?>
                        </div>

                    <?if($arResult['VIEW'] == "LIST" || $arResult['VIEW'] == "TABLE"):?>
                        </div>
                    <?endif;?>


                    <?if($arResult['VIEW'] == "FLAT"):?>

                        <?if($countItems != ($keyItem+1)):?>

                            <?if($arParams["COLS"] == "4"):?>

                                <?if( ($keyItem+1) % 4 == 0  ):?>
                                    <span class="col-12 break-line visible-xxl visible-xl visible-lg">
                                        <div class="<?if($hide_items && ($i+1) > $max_visible_items):?>show-hidden-child hidden<?endif;?>"></div>
                                    </span>
                                <?endif;?>

                                <?if( ($keyItem+1) % 3 == 0  ):?>
                                    <span class="col-12 break-line visible-md">
                                        <div></div>
                                    </span>
                                <?endif;?>

                            <?endif;?>

                            <?if($arParams["COLS"] == "3"):?>

                                <?if( ($keyItem+1) % 3 == 0  ):?>
                                    <span class="col-12 break-line visible-xxl visible-xl visible-lg visible-md">
                                        <div class="<?if($hide_items && ($i+1) > $max_visible_items):?>show-hidden-child hidden<?endif;?>"></div>
                                    </span>
                                <?endif;?>

                            <?endif;?>

                            <?if( ($keyItem+1) % 2 == 0 ):?>
                                <span class="col-12 break-line visible-sm visible-xs">
                                    <div class="<?if($hide_items && ($i+1) > $max_visible_items):?>show-hidden-child hidden<?endif;?>"></div>
                                </span>
                            <?endif;?>

                        <?endif;?>

                    <?endif;?>

                    <?$i++;?>

                <?endforeach;?>


                <?if($hide_items && $countItems > $max_visible_items):?>

                    <div class="col-12">

                        <div class="catalog-button-wrap center" >

                            <a class="button-def secondary big show-hidden <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?>">
                                <?if(strlen($arParams["MAX_VISIBLE_ITEMS_BTN_NAME"])):?>
                                    <?=$arParams["MAX_VISIBLE_ITEMS_BTN_NAME"]?>
                                <?else:?>
                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHOW_ALL"]?>
                                <?endif;?>
                            </a>
                            
                        </div>
                    </div>
               
                <?endif;?>

            </div>
        </div>

    </div>
    
    <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
        <div class="clearfix"></div>
        <?=$arResult["NAV_STRING"]?>
    <?endif;?>

<?else:?>

    <?if($arParams["FROM"] == "section"):?>


        <?

            $showAlert = true;

            if($arParams["INCLUDE_SUBSECTIONS"] == "N")
            {
                $showAlert = false;

                $arSelect = Array("ID");
                $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"],"SECTION_ID"=>$arParams["SECTION_ID"], "ACTIVE"=>"Y", "SECTION_ACTIVE" => "Y", "SECTION_SCOPE" => "iblock", "INCLUDE_SUBSECTIONS" => "Y");

                $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, array("nTopCount" => 1), $arSelect);

                if(intval($res->SelectedRowsCount())<=0)
                    $showAlert = true;

            }

        ?>

        <?if($showAlert):?>


            <div class="attention"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SECTION_CATALOG_EMPTY"]?></div>


            <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"]):?>
                <a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$arResult["IBLOCK_ID"]?>&type=<?=$arResult["IBLOCK_TYPE_ID"]?>&ID=0&lang=ru&IBLOCK_SECTION_ID=<?=$arResult["ID"]?>&find_section_section=<?=$arResult["ID"]?>&from=iblock_list_admin&PRODUCT_TYPE=P" class="button-def main-color big"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SECTION_CATALOG_BTN_ADD"]?></a>
            <?endif;?>

        
        <?endif;?>

    <?endif;?>
<?endif;?>