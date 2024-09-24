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

$defSections = array("CATALOG","BLOG","NEWS","ACTIONS");
global $PHOENIX_TEMPLATE_ARRAY;
?>


<div class="col-lg-3 col-md-6 col-12">

    <div class="section-box-wrap <?if($arResult["COUNT_TOTAL"] > 0):?>found<?endif;?>">

        <?if( $arResult["COUNT_TOTAL"] > 0 ):?><a class="general-link-wrap scroll" href="#to-<?=$arParams["CODE"]?>"></a><?endif;?>

        <table class="section-box">
            <tr>

                <td class="pic">

                    <?
                        $icon_def = $arParams["CODE"];

                        if(!in_array($arParams["CODE"], $defSections))
                            $icon_def = "CATALOG";
                    ?>

                    <div class="pic pic-<?=$icon_def?>" <?if( strlen($arParams["SECTION_ICON"]) ):?> style="background-image: url('<?=$arParams["SECTION_ICON"]?>');" <?endif;?> ></div>
                	
                </td>

                <td class="desc">
                    <div class="name"><?=$arParams["SECTION_NAME"]?><?if(intval($arResult["COUNT_TOTAL"])>0):?><span class="main-color visible-sm visible-xs"><?=$arResult["COUNT_TOTAL"]?></span><?endif;?></div>
                    <div class="info hidden-sm hidden-xs">

                        <?if( $arResult["COUNT_TOTAL"] > 0 )
                        {
                            echo $arResult["COUNT_TOTAL"]." ".CPhoenix::getTermination(  $arResult["COUNT_TOTAL"], array( $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_COMPACT_RESULT_2"], $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_COMPACT_RESULT_0"], $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_COMPACT_RESULT_1"], $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_COMPACT_RESULT_2"] )  );
                        }
                        else
                            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["NOT_FOUND"]["~VALUE"]))
                                echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["NOT_FOUND"]["~VALUE"];
                            else
                                echo $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEARCH_RESULT_NOT_FOUND"];
                        
                        ?>
                            
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
