<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//***********************************
//setting section
//***********************************
?>
<?use Bitrix\Main\Localization\Loc;?>

	<div class="row no-margin top-container">
		<div class="<?=$leftSideCols?> col-12 left-part">
			<div class="ex-row">
				<form class = "form subscribe-edit" action="<?=$arResult["FORM_ACTION"]?>" method="post">

					<?echo bitrix_sessid_post();?>

					<div class="row no-margin">

						<div class="col-12">

							<div class="title-form main1 clearfix">
						        <?echo GetMessage("subscr_title_settings")?>
						    </div>

						    <div class="input <?=(strlen($arResult["SUBSCRIPTION"]["EMAIL"]) || strlen($arResult["REQUEST"]["EMAIL"])) ? "in-focus" : "";?>">
					            <div class="bg"></div>
					            <span class="desc"><?=Loc::getMessage('subscr_email')?></span>
					            <input class='focus-anim require' name="EMAIL" type="text" value = "<?=$arResult["SUBSCRIPTION"]["EMAIL"]!=""?$arResult["SUBSCRIPTION"]["EMAIL"]:$arResult["REQUEST"]["EMAIL"];?>" />
					        </div>

					        <?if(!empty($arResult["RUBRICS"])):?>

					        	<div class="simple-title bold"><?echo GetMessage("subscr_rub")?><span class="starrequired">*</span></div>
					      
					        	<ul class="input-checkbox-css">

					        		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>

					        			<li>
									        <label class="input-checkbox-css">
									            <input type="checkbox" name="RUB_ID[]" value="<?=$itemValue["ID"]?>" <?if($itemValue["CHECKED"]) echo " checked"?>>
									            <span></span>                                                                          
									            <span class="text"><?=$itemValue["NAME"]?></span>
									        </label>
									    </li>
									<?endforeach;?>

					        	</ul>

				        	<?endif;?>

				        	<div class="simple-title bold"><?echo GetMessage("subscr_fmt")?></div>

				        	<ul class="input-radio-css">
				        		<li>
				                    <label class="input-radio-css">
				                        <input name='FORMAT' type="radio" value="text"<?if($arResult["SUBSCRIPTION"]["FORMAT"] == "text") echo " checked"?>><span></span><?echo GetMessage("subscr_text")?>
				                    </label>
				                </li>

				                <li>
				                    <label class="input-radio-css">
				                        <input name='FORMAT' type="radio" value="html"<?if($arResult["SUBSCRIPTION"]["FORMAT"] == "html") echo " checked"?>><span></span>HTML
				                    </label>
				                </li>
				        	</ul>
							
							<div class="input-btn">
								<div class="row">
									<div class="col-md-6 col-12 left-btn">
										<input class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?>" type="submit" name="Save" value="<?echo ($arResult["ID"] > 0? GetMessage("subscr_upd"):GetMessage("subscr_add"))?>" />
									</div>
									<div class="col-md-6 col-12 right-btn">
										<input class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?>" type="reset" value="<?echo GetMessage("subscr_reset")?>" name="reset" />
									</div>
								</div>
								
								
							</div>

						</div>

					</div>


					<input type="hidden" name="PostAction" value="<?echo ($arResult["ID"]>0? "Update":"Add")?>" />
					<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />

					<?if($_REQUEST["register"] == "YES"):?>
						<input type="hidden" name="register" value="YES" />
					<?endif;?>
					<?if($_REQUEST["authorize"]=="YES"):?>
						<input type="hidden" name="authorize" value="YES" />
					<?endif;?>

				</form>
			</div>
        </div>

        <div class="<?=$rightSideCols?> col-12 right-part text">

        	<p><?echo GetMessage("subscr_settings_note1")?></p>
			<p><?echo GetMessage("subscr_settings_note2")?></p>

        </div>

    </div>