<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

global $PHOENIX_TEMPLATE_ARRAY;
use Bitrix\Main\Localization\Loc;


$cols = "col-xl-5 col-lg-7 col-md-7";

if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BANNERS"]["ITEMS"]))
	$cols = "col-xl-7 col-md-10";


?>

<div class="bx_profile">

	<?
	ShowError($arResult["strProfileError"]);

	if ($arResult['DATA_SAVED'] == 'Y')
	{
		ShowNote($PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_PRIVATE_PROFILE_DATA_SAVED"]);
		LocalRedirect($_SERVER["REQUEST_URI"]);
	}

	?>

	<div class="row">
		<div class="<?=$cols?> col-12">
			<form method="post" class="form private" name="form1" action="<?=$APPLICATION->GetCurUri()?>" enctype="multipart/form-data" role="form">
				<?=$arResult["BX_SESSION_CHECK"]?>
				<input type="hidden" name="lang" value="<?=LANG?>" />
				<input type="hidden" name="ID" value="<?=$arResult["ID"]?>" />
				<input type="hidden" name="LOGIN" value="<?=$arResult["arUser"]["LOGIN"]?>" />
				<div class="main-profile-block-shown" id="user_div_reg">

					<div class="row no-margin">
						<div class="col-12">
							<div class="main-profile-block-date-info">
							
								<?
									if($arResult["ID"]>0)
									{
										if (strlen($arResult["arUser"]["TIMESTAMP_X"])>0)
										{
											?>
											
												<strong><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_PRIVATE_LAST_UPDATE"]?></strong>&nbsp;
												<strong><?=$arResult["arUser"]["TIMESTAMP_X"]?></strong>
												<br/>
											
											<?
										}

										if (strlen($arResult["arUser"]["LAST_LOGIN"])>0)
										{
											?>
												<strong><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_PRIVATE_LAST_LOGIN"]?></strong>&nbsp;
												<strong><?=$arResult["arUser"]["LAST_LOGIN"]?></strong>
											<?
										}
									}
								?>
							
							</div>

							<div class="input <?=(strlen($arResult["arUser"]["NAME"])) ? "in-focus" : "";?>">
				                <div class="bg"></div>
				                <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_PRIVATE_INPUT_NAME"]?></span>
				                <input id = "main-profile-name" class='focus-anim' name="NAME" type="text" value = "<?=$arResult["arUser"]["NAME"]?>" />
				            </div>

				            <div class="input <?=($arResult["arUser"]["LAST_NAME"]) ? "in-focus" : "";?>">
				                <div class="bg"></div>
				                <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_PRIVATE_INPUT_LAST_NAME"]?></span>
				                <input id = "main-profile-last-name" class='focus-anim' name="LAST_NAME" type="text" value = "<?=$arResult["arUser"]["LAST_NAME"]?>" />
				            </div>

				            <div class="input <?=(strlen($arResult["arUser"]["SECOND_NAME"])) ? "in-focus" : "";?>">
				                <div class="bg"></div>
				                <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_PRIVATE_INPUT_SECOND_NAME"]?></span>
				                <input id = "main-profile-second-name" class='focus-anim' name="SECOND_NAME" type="text" value = "<?=$arResult["arUser"]["SECOND_NAME"]?>" />
				            </div>


				            <div class="input <?=(strlen($arResult["arUser"]["EMAIL"])) ? "in-focus" : "";?>">
				                <div class="bg"></div>
				                <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_PRIVATE_INPUT_EMAIL"]?></span>
				                <input id = "main-profile-email" class='focus-anim' name="EMAIL" type="text" value = "<?=$arResult["arUser"]["EMAIL"]?>" />
				            </div>


				         
			            	<?

				            	$resizePersonalPhoto = array();
				            	if($arResult["arUser"]["PERSONAL_PHOTO"])
				            		$resizePersonalPhoto = CFile::ResizeImageGet($arResult["arUser"]["PERSONAL_PHOTO"], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_PROPORTIONAL, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

			            	?>

		            		<div class='input'>
	                        
				                <label class='file click-file-clear'>
				                    <span class='ex-file-desc'><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PROFILE_PHOTO_DOWNLOAD"]?></span>
				                    <span class='ex-file'></span>
				                    <input name="PERSONAL_PHOTO" class="typefile hidden flat" type="file">

				                </label>

				            </div>
				            		
				            

			            	<?if (strlen($arResult["arUser"]["PERSONAL_PHOTO"])>0):?>
				            	<div class="person-photo">

				            		<div class="row">
				            			
				            			<div class="col-6">
				            				<img src="<?=$resizePersonalPhoto['src']?>" alt=""/>
				            			</div>
				            			
				            			<div class="col-6">
				            				<ul class="input-checkbox-css">

												<li>
												
													<label class="input-checkbox-css">
														
														<input type="checkbox" value="Y" name="PERSONAL_PHOTO_del" id="PERSONAL_PHOTO_del">
														<span class="ic"></span>
														<span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PROFILE_PHOTO_DEL"]?></span>
														
													</label>
												</li>
												
											</ul>
				            			</div>

				            		</div>
				            	
				            	</div>
			            	<?endif;?>

				            
				            

							<?
							if ($arResult['CAN_EDIT_PASSWORD'])
							{
								?>

								<div class="input">
								
									<p class="main-profile-form-password-annotation small">
										<?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?>
									</p>
									
								</div>

								<div class="input">
					                <div class="bg"></div>
					                <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_PRIVATE_NEW_PASSWORD_REQ"]?></span>
					                <input id = "main-profile-password" class='focus-anim' name="NEW_PASSWORD" type="password" value = "" autocomplete="off"/>
					            </div>

					            <div class="input">
					                <div class="bg"></div>
					                <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PERSONAL_PRIVATE_NEW_PASSWORD_CONFIRM"]?></span>
					                <input id = "main-profile-password-confirm" class='focus-anim' name="NEW_PASSWORD_CONFIRM" type="password" value = "" autocomplete="off"/>
					            </div>

								<?
							}
							?>

							<div class="input-btn">
								<div class="row">
									<div class="left-btn col-md-6"><input type="submit" name="save" class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?> main-profile-submit" value="<?=(($arResult["ID"]>0) ? Loc::getMessage("MAIN_SAVE") : Loc::getMessage("MAIN_ADD"))?>"></div>

									<div class="right-btn col-md-6">
										<input type="submit" class="button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]['BTN_VIEW']['VALUE']?>" name="reset" value="<?echo GetMessage("MAIN_RESET")?>">
									</div>
								</div>

								
								
							</div>
						</div>
					</div>
				</div>
			
			</form>
		</div>
	</div>

	<?/*?>
	<div class="col-sm-12 main-profile-social-block">
		<?
		if ($arResult["SOCSERV_ENABLED"])
		{
			$APPLICATION->IncludeComponent("bitrix:socserv.auth.split", "main1", Array(
				"SHOW_PROFILES" => "Y",
					"ALLOW_DELETE" => "Y",
				),
				false
			);
		}
		?>
	</div>
	<?*/?>


</div>