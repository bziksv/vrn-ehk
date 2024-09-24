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


<?if(!empty($arResult)):?>
<div class="menu-items">
    
    <?foreach($arResult as $arItem):?>
                                
        <div class="menu-item">

            <?
                


                $item = "<a ";

                    if($arItem['NOLINK'])
                        $item .= CPhoenix::phoenixMenuAttr($arItem, $arItem['TYPE'])." ";
                    else
                    {
                        if(strlen($arItem["LINK"]) > 0 && !$arItem["NONE"])
                            $item .= "href='".$arItem['LINK']."' ";

                        if($arItem['BLANK'])
                            $item .= "target='_blank' ";
                        
                    }

                    $item .= "class='";

                        if(strlen($arItem["ID"]))
                            $item .= "section-menu-id-".$arItem["ID"]." ";

                        if(strlen($arItem["LINK"]) <= 0 && $arItem["NONE"])
                            $item .= "empty-link ";

                        else
                            $item .= "hover ";

                        if($arItem["SELECTED"])
                            $item .= "selected ";

                        if($arItem['NOLINK'])
                            $item .= CPhoenix::phoenixMenuClass($arItem, $arItem["TYPE"]);

                        if($arItem["ACCENTED"])
                            $item .= "accent";

                    $item .= "' ";


                    $colorText = '';

                    if(strlen($arItem['MENU_COLOR'])>0)
                        $colorText = ' style="color: '.$arItem['MENU_COLOR'].';"';


                $item .= $colorText.">".$arItem['NAME']."</a>";

            ?>

            <?= $item;?>

        </div>
        
    <?endforeach;?>

</div>
<?endif;?>