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

<?
$frame = $this->createFrame()->begin();
global $PHOENIX_TEMPLATE_ARRAY;
?>

<div class="subscribe-item" id="subscribe-form">
    <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["SUBSCRIPE_DESCRIPTION"]["VALUE"])):?>
        <div class="description"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["SUBSCRIPE_DESCRIPTION"]["~VALUE"]?></div>
    <?endif;?>

    
    <form action="<?=$arResult["FORM_ACTION"];?>" class="form-uni-style subscribe">
		
		<?/*if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["SUBSCRIPE_RUBRICS_SHOW"]["VALUE"]["ACTIVE"] == "Y"):?>

			<ul class="input-checkbox-css">
		
		    	<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			    	<li>
						<label class="input-checkbox-css" for="sf_RUB_ID_<?=$itemValue["ID"]?>">
							<input type="checkbox" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>" />
							<span></span>
							<?=$itemValue["NAME"]?>
						</label>
					</li>
				<?endforeach;?>

			</ul>

		<?endif;*/?>

        <div class="input square">
            <div class="bg"></div>
            <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["SUBSCRIPE"]["OTHER"]["DESC_FOR_INPUT"]?></span>
            <input class="focus-anim " name="sf_EMAIL" type="email">
			

			<input class = "main-color in-input" type="submit" value="" />
            <!-- <button class="main-color in-input" name="form-submit" type="button"></button> -->
            
        </div>
    </form>
</div>
<?
$frame->end();
?>
