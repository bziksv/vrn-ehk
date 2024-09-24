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
global $PHOENIX_TEMPLATE_ARRAY;
?>


<?if(!empty($arResult["SECTIONS"])):?>

    <?if($arParams["VIEW"] == "in_head" ):?>

        <div class="section-items row <?=$arParams["ALIGN"]?> 

            <?= ($arParams["ANIMATE_MODE"]) ? 'parent-animate' : ''?>" 

            <?= ($arParams["ANIMATE_MODE"]) ? 'data-delay = "1.4"' : ''?>>
            
            <?
                $countItems = count($arResult["SECTIONS"]);
                $curCountItems = 0;


                $maxItems = 5;
                $cols = "col-xl-five col-md-3 col-sm-2 col-4";
                $controlCount = 2;

                if(!$arParams["PICT_IN_HEAD_ISSET"] && $countItems > 5)
                {
                    $maxItems = 6;
                    $cols = "col-xl-2 col-md-3 col-sm-2 col-4";
                    $controlCount = 3;
                }
            ?>  


            <?foreach($arResult["SECTIONS"] as $key => $arSection):?>
            
                <div class="<?=$cols?> <?= ($arParams["ANIMATE_MODE"]) ? 'child-animate opacity-zero' : ''?>">

                    <a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="d-block link-item">
                        <div class="picture-board">

                            <?
                                $photo = 0;

                                if($arSection["UF_PHX_MENU_PICT"])
                                    $photo = $arSection["UF_PHX_MENU_PICT"];
                                else if($arSection["PICTURE"])
                                    $photo = $arSection["PICTURE"];

                                $arSection["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/sect-list-empty.png";

                    

                                if($photo)
                                {
                                    $img = CFile::ResizeImageGet($photo, array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_PROPORTIONAL, false);

                                    $arSection["PREVIEW_PICTURE_SRC"] = $img['src'];
                                }

                            ?>

                            <img src="<?=$arSection["PREVIEW_PICTURE_SRC"]?>" class="img-fluid mx-auto d-block" alt="<?=CPhoenix::prepareText($arSection["NAME"])?>">

                            <?/*if( $arSection["ELEMENT_CNT"] > 0 ):?>
                                <div title="<?=$arSection["ELEMENT_CNT"]?>" class="quantity main-color no-empty"><?=$arSection["ELEMENT_CNT"]?></div>  
                            <?endif;*/?>
                        </div>
                        <div class="desk"><?=$arSection["~NAME"]?></div>
                    </a>
                    
                </div>

                <?
                    if( ($key > $controlCount && $countItems > $maxItems) 
                        || $key > ($maxItems - 1)  )
                    {
                        $curCountItems = $key + 1;
                        break;
                    }
                ?>

            
            <?endforeach;?>

        

            <?if( $countItems > $maxItems ):?>
            
                <div class="<?=$cols?>">
                    <a class="d-block btn-open-hidden-board" data-open = "board-section-items">
                        <div class="picture-board more">
                            <span class="more"></span>
                        </div>
                        <div class="desk"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MORE"]?> <?=$countItems - $curCountItems?></div>
                    </a>
                </div>

                <div class="board-section-items content-hidden-board" data-content = "board-section-items">
                    <div class="board-section-items-inner">
                        <div class="row">

                            <?foreach($arResult["SECTIONS"] as $key => $arSection):?>

                                <div class="<?=$cols?>">

                                    <a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="d-block link-item">
                                        <div class="picture-board">

                                            <?
                                                $photo = 0;

                                                if($arSection["UF_PHX_MENU_PICT"])
                                                    $photo = $arSection["UF_PHX_MENU_PICT"];
                                                else if($arSection["PICTURE"])
                                                    $photo = $arSection["PICTURE"];

                                                $arSection["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH."/images/sect-list-empty.png";

                                            

                                                if($photo)
                                                {
                                                    $img = CFile::ResizeImageGet($photo, array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_PROPORTIONAL, false);

                                                    $arSection["PREVIEW_PICTURE_SRC"] = $img['src'];
                                                }

                                            ?>

                                            <img src="<?=$arSection["PREVIEW_PICTURE_SRC"]?>" class="img-fluid mx-auto d-block" alt="<?=CPhoenix::prepareText($arSection["NAME"])?>">
                                        </div>
                                        <div class="desk"><?=$arSection["~NAME"]?></div>
                                    </a>
                                    
                                </div>

                            <?endforeach;?>

                        </div>

                        <a class="close-board-section-items btn-close-hidden-board" data-close = "board-section-items"></a>
                    </div>
                    
                </div>
                  

            <?endif;?>
          
        </div>

    <?endif;?>

    
    <?if($arParams["VIEW"] == "in_content"):?>

        <?
            $terminations = Array();

            $terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_1"];
            $terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_2"];
            $terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_3"];
            $terminations[] = $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_CNT_4"];

            $colsLeft = "col-lg-3 col-12";
            $colsRight = "col-lg-9 col-12";
            $colsItems = "col-lg-4 col-md-6 col-12";

            if( !strlen($arParams["SUBTITLE"]) )
            {
                $colsRight = "col-12";
                $colsItems = "col-lg-3 col-md-6 col-12";
            }
            

            if( empty($arResult["SECTIONS"]) )
                $colsLeft = "col-12";
            


        ?>
    
        <div class="subsection-subtitle-wrapper">
            <div class="row">

                <?if(strlen($arParams["SUBTITLE"])):?>

                    <div class="<?=$colsLeft?>">
                        <div class="page-subtitle"><?=htmlspecialcharsBack($arParams["SUBTITLE"])?></div>
                    </div>

                <?endif;?>

                <?if( !empty($arResult["SECTIONS"]) ):?>

                    <div class="<?=$colsRight?>">

                        <div class="subsection-list">
                            <div class="row">
                                    
                                <?foreach($arResult["SECTIONS"] as $arSection):?>
                                
                                    <div class="<?=$colsItems?>">
                        
                                        <table class="item">
                                            <tr>
                                                <td class="left">
                                                
                                                    <?
                                                        $photo = 0;
                                                        if($arSection["UF_PHX_MENU_PICT"])
                                                            $photo = $arSection["UF_PHX_MENU_PICT"];
                                                        else if($arSection["PICTURE"])
                                                            $photo = $arSection["PICTURE"];
                                                    ?>
                                                
                                                    <?if($photo):?>
                                                    
                                                        <?$img = CFile::ResizeImageGet($photo, array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>
                                                                
                                                        <img data-src="<?=$img["src"]?>" class="img-fluid mx-auto d-block lazyload" alt="<?=CPhoenix::prepareText($arSection["NAME"])?>">
                                                        
                                                    <?else:?>
                                                    
                                                        <span></span>
                                                        
                                                    <?endif;?>
                         
                                                </td>
                                                
                                                <td class="right">
                                                    <a class="name" href="<?=$arSection["SECTION_PAGE_URL"]?>" title="<?=$arSection["NAME"]?>">
                                                        <span class="bord-bot"><?=$arSection["~NAME"]?></span>
                                                            
                                                        </a>

                                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSECTIONS_HIDE_COUNT"]["VALUE"]["ACTIVE"] != "Y" && $arSection["ELEMENT_CNT"]!="0"):?>
                                                        <div class="count-sect-elem"><?=$arSection["ELEMENT_CNT"]?> <?=CPhoenix::getTermination($arSection["ELEMENT_CNT"], $terminations)?></div>
                                                    <?endif;?>
                                                </td>
                                                
                                            </tr>
                                        </table>
                                        
                                    </div>

                                <?endforeach;?>
                            
                        
                            </div>
                        </div>

                    </div>

                <?endif;?>

            </div>
        </div>
    <?endif;?>



<?endif;?>


<? 
unset($maxItems, $cols, $controlCount, $curCountItems, $countItems);
