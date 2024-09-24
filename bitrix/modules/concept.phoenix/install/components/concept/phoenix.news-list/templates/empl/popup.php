<?if(!empty($arResult["ITEMS"])):?>
<?
	$arItem = $arResult["ITEMS"][0];
	unset($arResult["ITEMS"]);
?>

	<div class="shadow-modal"></div>

	<div class="phoenix-modal employee-modal blur-container employee-modal-<?=$arItem["ID"]?> row no-gutters align-items-center">
		<div class="col-12">
			<div class="phoenix-modal-dialog">
				
				<div class="dialog-content container">
		            <a class="close-modal"></a>

		            <div class="content-in">

		            	<div class="row">
		            		<div class="col-xl-8 col-md-7 col-12 left-side">
		            			<div class="name bold"><?=$arItem["~NAME"]?></div>

		            			<?if(strlen($arItem['PROPERTIES']['EMPL_DESC']['VALUE'])>0):?>
		            				<div class="subname"><?=$arItem['PROPERTIES']['EMPL_DESC']['~VALUE']?></div>
		            			<?endif;?>
		            			
		            			<?if(!empty($arItem['PROPERTIES']['LABELS']['VALUE'])):?>
		            				<div class="labels">
		            					<?foreach($arItem['PROPERTIES']['LABELS']['~VALUE'] as $key => $value):?>

		            						<?
			            						$style = '';

		            							if(strlen($arItem['PROPERTIES']['LABELS']['DESCRIPTION'][$key])>0)
		            							{
		            								$arr = explode(';', $arItem['PROPERTIES']['LABELS']['DESCRIPTION'][$key]);

		            								if(is_array($arr) && !empty($arr))
		            								{
		            									if($arr[0])
		            									{
		            										$style = 'background-color:'.$arr[0].';';
		            									}
		            									if($arr[1])
		            									{
		            										$style .= 'color:'.$arr[1].';';
		            									}

		            								}

		            							}

		            							if(strlen($style)>0)
		            								$style = 'style="'.$style.'"';
		            						?>
		            						<div class="label" <?=$style?>>
		            							<?=$value?>
		            						</div>
		            					<?endforeach;?>
		            				</div>
		            			<?endif;?>

		            			<?if(is_array($arItem['PROPERTIES']['QUOTE']['VALUE'])):?>
		            				<div class="quote italic"><?=$arItem['PROPERTIES']['QUOTE']['~VALUE']['TEXT']?></div>
		            			<?endif;?>
		            			<?if(is_array($arItem['PROPERTIES']['TEXT']['VALUE'])):?>
		            				<div class="text-content"><?=$arItem['PROPERTIES']['TEXT']['~VALUE']['TEXT']?></div>
		            			<?endif;?>
		            		</div>
		            		<div class="col-xl-4 col-md-5 col-12 right-side">
		            			<div class="img">
		            				<?$img["src"] = SITE_TEMPLATE_PATH.'/images/empl.jpg';?>

						            <?if(strlen($arItem["PREVIEW_PICTURE"])>0):?>
						                <?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>600, 'height'=>600), BX_RESIZE_IMAGE_PROPORTIONAL, false);?>
						            <?endif;?>
		            				<img src="<?=$img['src']?>" alt="">
		            			</div>

		            			<?if(strlen($arItem['PROPERTIES']['EMPL_PHONE']['VALUE'])>0 ||strlen($arItem['PROPERTIES']['EMPL_EMAIL']['VALUE'])>0 ):?>

		            				<div class="wr-subinfo-contacts">

				            			<?if(strlen($arItem['PROPERTIES']['EMPL_PHONE']['VALUE'])>0):?>
			                                <div class="phone-css bold">

			                                    <span title='<?=$arItem['PROPERTIES']['EMPL_PHONE']['VALUE']?>'>

			                                        <a href="tel:<?=$arItem["PHONE_TRIM"]?>"><?=$arItem['PROPERTIES']['EMPL_PHONE']['~VALUE']?></a>

			                                    </span>

			                                </div><br/>
			                            <?endif;?>

			                            <?if(strlen($arItem['PROPERTIES']['EMPL_EMAIL']['VALUE'])>0):?>
			                                <div class="email-css"><a href="mailto:<?=$arItem['PROPERTIES']['EMPL_EMAIL']['VALUE']?>" title='<?=$arItem['PROPERTIES']['EMPL_EMAIL']['VALUE']?>'><span class="bord-bot"><?=$arItem['PROPERTIES']['EMPL_EMAIL']['~VALUE']?></span></a></div>
			                            <?endif;?>
			                        </div><br/>

	                            <?endif;?>


		            			<?if(strlen($arItem['PROPERTIES']['VIDEO']['VALUE'])>0 || $arItem['PROPERTIES']['FILES']['VALUE']):?>
			            			<div class="wr-subinfo">
			            				<?if(strlen($arItem['PROPERTIES']['VIDEO']['VALUE'])>0):?>
			            					<?$iframe = CPhoenix::createVideo($arItem["PROPERTIES"]["VIDEO"]["VALUE"]);?> 
			            					<a class="video-css call-modal callvideo from-modal" data-call-modal="<?=$iframe["ID"]?>">
			            						<?if(strlen($arItem["PROPERTIES"]["VIDEO_NAME"]["VALUE"]) > 0):?>

	                                                <span class="bord-bot"><?=$arItem["PROPERTIES"]["VIDEO_NAME"]["~VALUE"]?></span>

	                                            <?endif;?>
			            					</a><br/>
		            					<?endif;?>

			            				<?if(is_array($arItem['PROPERTIES']['FILES']['VALUE']) && !empty($arItem['PROPERTIES']['FILES']['VALUE'])):?>

			            					<?foreach($arItem['PROPERTIES']['FILES']['VALUE'] as $key => $value):?>

				            					<?$arFile = CFile::MakeFileArray($value);?>
		                                        <?$is_image = CFile::IsImage($arFile["name"], $arFile["type"]);?>

		                                        <?if($key === 0):?>
						            				<a href="<?=CFile::GetPath($value)?>" <?if(!$is_image):?> target="_blank" <?else:?> data-gallery="empl_<?=$arItem['ID']?>" <?endif;?>class="file-css">

			                                            <?if(strlen($arItem["PROPERTIES"]["FILE_NAME"]["VALUE"]) > 0):?>

			                                                <span class="bord-bot"><?=$arItem["PROPERTIES"]["FILE_NAME"]["~VALUE"]?></span>

			                                            <?endif;?>

			                                        </a>
			                                    <?else:?>

			                                    	<a href="<?=CFile::GetPath($value)?>" <?if(!$is_image):?> target="_blank" <?else:?> data-gallery="empl_<?=$arItem['ID']?>" class="d-none" <?endif;?>></a>

		                                        <?endif;?>
	                                        <?endforeach;?>
			            				<?endif;?>
			            			</div>
		            			<?endif;?>
		            			<?if(strlen($arItem['PROPERTIES']['EMPL_BTN_NAME']['VALUE'])>0 && strlen($arItem['PROPERTIES']['EMPL_FORMS']['VALUE'])>0):?>

	                                <div class="wrap-button">
	                                <a class="button-def main-color from-modal call-modal callform d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?>" data-call-modal="form<?=$arItem['PROPERTIES']['EMPL_FORMS']['VALUE']?>" title='<?=$arItem['PROPERTIES']['EMPL_BTN_NAME']['VALUE']?>'><?=$arItem['PROPERTIES']['EMPL_BTN_NAME']['~VALUE']?></a>
	                                </div>
	                            <?endif;?>
		            		</div>
		            	</div>
		            	
		            </div>

		            <?
			            $APPLICATION->IncludeComponent(
						   "concept:phoenix.next-last-element",
						   "id_arrows",
						   Array(
						   		"CALL"=>"callemployee",
								"CACHE_GROUPS" => "Y",
								"CACHE_TIME" => "3600000",
								"CACHE_TYPE" => "N",
								"COMPONENT_TEMPLATE" => ".default",
								"ELEMENT_ID" => $arItem["ID"],
								"ALL_ID" => $arParams["ALL_ID"],
						   )
			    		);
					?>

		        </div>

			</div>
		</div>
	</div>

<?endif;?>