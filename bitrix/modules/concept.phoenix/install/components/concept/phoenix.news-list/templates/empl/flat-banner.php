<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>


<?if(!empty($arResult["ITEMS"])):?>

<div class="empl empl-banner">
	<?foreach ($arResult["ITEMS"] as $key => $arEmpl):?>
		
		<div class="wrap-element <?=$arParams["COLS"]?> parent-tool-settings">

	        <div class="element">
	            

	            <?$img["src"] = SITE_TEMPLATE_PATH.'/images/empl.jpg';?>

	            <?if(strlen($arEmpl["PREVIEW_PICTURE"])>0):?>
	                <?$img = CFile::ResizeImageGet($arEmpl["PREVIEW_PICTURE"], array('width'=>600, 'height'=>600), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>
	            <?endif;?>

	            <div class="wr-empl-face">
	                <div class="empl-face lazyload pic-round" data-src="<?=$img["src"]?>"></div>
	            </div>

	            <div class="wrap-text">


	                <?if(strlen($arSection['EMPL'][$arEmpl['IBLOCK_SECTION_ID']]['NAME'])>0):?>
	                    <div class="section" title='<?=$arSection['EMPL'][$arEmpl['IBLOCK_SECTION_ID']]['NAME']?>'><?=$arSection['EMPL'][$arEmpl['IBLOCK_SECTION_ID']]['~NAME']?></div>
	                <?endif;?>

	                <div class="empl-name bold"><?=$arEmpl['~NAME']?></div>

	                <?if(strlen($arEmpl['PROPERTIES']['EMPL_DESC']['VALUE'])>0):?>
	                    <div class="empl-desc italic"><?=$arEmpl['PROPERTIES']['EMPL_DESC']['~VALUE']?></div>
	                <?endif;?>

	            </div>

	            <?if(strlen($arEmpl['PROPERTIES']['EMPL_PHONE']['VALUE'])>0 || strlen($arEmpl['PROPERTIES']['EMPL_EMAIL']['VALUE'])>0 || strlen($arEmpl['PROPERTIES']['BUTTON_NAME']['VALUE'])>0 || is_array($arEmpl["PROPERTIES"]["TEXT"]["VALUE"])):?>

		            <?if(is_array($arEmpl["PROPERTIES"]["TEXT"]["VALUE"])):?>
	                    <div class="wrap-button">
	                       <a class="button-def main-color <?=$btn_size?> <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> call-modal callemployee " data-call-modal="<?=$arEmpl["ID"]?>"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MORE_EMPLOYEE"]?></a>
	                    </div>
	                    
	                <?else:?>
		                
		                <?if(strlen($arEmpl['PROPERTIES']['EMPL_PHONE']['VALUE'])>0):?>
		                    <div class="empl-phone bold">

		                        <span title='<?=$arEmpl['PROPERTIES']['EMPL_PHONE']['VALUE']?>'>

		                            <?$phone=preg_replace('/[^0-9+]/', '', $arEmpl['PROPERTIES']['EMPL_PHONE']['VALUE']);?>

		                            <a href="tel:<?=$phone?>"><?=$arEmpl['PROPERTIES']['EMPL_PHONE']['~VALUE']?></a>

		                        </span>

		                    </div>
		                <?endif;?>

		                <?if(strlen($arEmpl['PROPERTIES']['EMPL_EMAIL']['VALUE'])>0):?>
		                    <div class="empl-email"><a href="mailto:<?=$arEmpl['PROPERTIES']['EMPL_EMAIL']['VALUE']?>" title='<?=$arEmpl['PROPERTIES']['EMPL_EMAIL']['VALUE']?>'><span class="bord-bot"><?=$arEmpl['PROPERTIES']['EMPL_EMAIL']['~VALUE']?></span></a></div>
		                <?endif;?>


		                <?if(strlen($arEmpl['PROPERTIES']['EMPL_BTN_NAME']['VALUE'])>0 && strlen($arEmpl['PROPERTIES']['EMPL_FORMS']['VALUE'])>0):?>

	                        <div class="wrap-button">
	                        <a class="button-def main-color call-modal callform <?=$btn_size?> <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?>"  data-call-modal="form<?=$arEmpl['PROPERTIES']['EMPL_FORMS']['VALUE']?>" data-header='<?=$arParams["BLOCK_TITLE_FORMATED"]?>' title='<?=$arEmpl['PROPERTIES']['EMPL_BTN_NAME']['VALUE']?>'><?=$arEmpl['PROPERTIES']['EMPL_BTN_NAME']['~VALUE']?></a>
	                        </div>
	                    <?endif;?>

	                <?endif;?>
	             
	            <?endif;?>
	        </div>
			<?CPhoenix::admin_setting($arEmpl, false)?>
	   	</div>

	<?endforeach;?>
	<div class="clearfix"></div>
</div>
<?endif;?>