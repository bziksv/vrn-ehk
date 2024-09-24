<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?global $PHOENIX_TEMPLATE_ARRAY;?>


<?$isAjax = ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["ajax_action"]) && $_POST["ajax_action"] == "Y");?>


<div class="bx_compare" id="bx_catalog_compare_block">
	<?if ($isAjax){
		$APPLICATION->RestartBuffer();
	}?>

	<?$arUnvisible=array("NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE");?>
	<div class="top-container">
		<div class="row">
			<div class="col-lg-3 col-md-4 col-12">

				<div class="comment"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMPARE_COMMENT"]?> <?=$arResult["ITEMS_COUNT"]?> <?=CPhoenix::getTermination($arResult["ITEMS_COUNT"], $arResult["ITEMS_TERM"])?></div>
				<div class="bx_sort_container">

					<ul class="tabs-head">
						<li <?=(!$arResult["DIFFERENT"] ? 'class="current"' : '');?>>
							<span class="button-def sortbutton <? echo (!$arResult["DIFFERENT"] ? ' current main-color' : 'secondary'); ?>" data-href="?DIFFERENT=N" rel="nofollow"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMPARE_ALL_CHARACTERISTICS"]?></span>
						</li>
						<li <?=($arResult["DIFFERENT"] ? 'class="current"' : '');?>>
							<span class="button-def sortbutton diff <? echo ($arResult["DIFFERENT"] ? ' current main-color' : 'secondary'); ?>" data-href="?DIFFERENT=Y" rel="nofollow"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMPARE_ONLY_DIFFERENT"]?></span>
						</li>
					</ul>
					<div class="wrap_remove_button hidden-sm hidden-xs">
				    
						<?
				        $arStr=$arCompareIDs=array();
				        
						if($arResult["ITEMS"]){
							foreach($arResult["ITEMS"] as $arItem){
								$arCompareIDs[]=$arItem["ID"];
							}
						}
				        
						$arStr=implode("&ID[]=", $arCompareIDs);
				        ?>
				        
						<div class="remove_all_compare" onclick="CatalogCompareObj.MakeAjaxAction('/catalog/compare/?action=DELETE_FROM_COMPARE_RESULT&ID[]=<?=$arStr?>', 'Y');"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMPARE_CLEAR_ALL"]?></div>
					</div>
				</div>
			</div>

			<div class="d-md-none col-4"></div>

			<div class="col-lg-9 col-md-8 col-8">

				<div class="table_compare wrap_sliders tabs-body">


					<?if (!empty($arResult["SHOW_FIELDS"])){?>

						<div class="frame top">
							<div class="wraps">
								<table class="compare_view">
									<tr>
										<?foreach($arResult["ITEMS"] as &$arElement){?>
				                        
											<td>
												<div class="item_block <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"]):?>parent-tool-settings<?endif;?>">
													<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arElement['~DELETE_URL'])?>', 'Y');" class="remove" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMPARE_REMOVE_PRODUCT"]?>"></span>
													<div class="image_wrapper_block">

														<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="d-block" target="_blank">
															<img class="d-block mx-auto" src="<?=$arElement["PREVIEW_PICTURE_SRC"]?>" alt=""/>
														</a>


													</div>

													<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="title" title="<?=htmlspecialcharsbx($arElement["~NAME"])?>" target="_blank"><?=$arElement["NAME"]?></a>

													<?/*if(strlen($arElement["NAME_OFFERS"])):?>

														<div class="name_offers">

															<?=$arElement["NAME_OFFERS"]?>
															
														</div>

													<?endif;*/?>
													<div class="cost prices ">
														<?
															$frame = $this->createFrame()->begin('');
															$frame->setBrowserStorage(true);
														?>

														<?if (isset($arElement['MIN_PRICE']) && is_array($arElement['MIN_PRICE'])){?>
				                                            
				                                            
				                                            <div class="price">

				                                                <span class="bold"><?=$arElement["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span>
				                                                <?if(isset($arElement["MEASURE_HTML"])):?>
				                                                	<span class="unit"><?=$arElement["MEASURE_HTML"]?></span>
				                                                <?endif;?>

				                                                <?if($arElement["MIN_PRICE"]["DISCOUNT_DIFF"] > 0):?>
				                                                    <span class="old"><?=$arElement["MIN_PRICE"]["PRINT_VALUE"]?></span>
				                                                <?endif;?>
				                                            
				                                            </div>
				                                            
				                                            
														<?}?>
														<?$frame->end();?>
													</div>

													<?

														$arrFields = array(
															"NAME" => $arElement["NAME"],
															"IBLOCK_TYPE_ID" => $arElement["IBLOCK_TYPE_ID"]

														);

														if($arElement["ID"] == $arElement["~ID"])
														{
															$arrFields["IBLOCK_ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"];
															$arrFields["ID"] = $arElement["~ID"];
															$arrFields["IBLOCK_SECTION_ID"] = $arElement["IBLOCK_SECTION_ID"];
														}
														
														else
														{
															$arrFields["IBLOCK_ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['OFFERS']["IBLOCK_ID"];
															$arrFields["ID"] = $arElement["ID"];
															$arrFields["IBLOCK_SECTION_ID"] = 0;
														}
														
														CPhoenix::admin_setting_cust($arrFields)?>

												</div>
											</td>
										<?}?>
									</tr>
								</table>
							</div>
						</div>


						<div class="wrapp_scrollbar">
							<div class="wr_scrollbar">
								<div class="scrollbar">
									<div class="handle">
										<div class="mousearea"></div>
									</div>
								</div>
							</div>
							<ul class="slider_navigation">
			                    <li class="flex-nav-prev backward"></li>
			                    <li class="flex-nav-next forward"></li>
				            </ul>
						</div>
					<?}?>
					
				</div>

			</div>
		</div>
	</div>
	
	<div class="row">

		<div class="col-lg-3 col-4 left-bottom-side">
			<div class="prop_title_table"></div>
			<?//$APPLICATION->ShowViewContent('prop_title_table');?>
		</div>

		<div class="col-lg-9 col-8 right-bottom-side">

			<div class="frame props">
				
				<div class="wraps">
					<table class="data_table_props compare_view">
						<?$arPropertyNames = array();?>
						<?
						if (!empty($arResult["SHOW_PROPERTIES"])){
							foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty){
								$showRow = true;
								if ($arResult['DIFFERENT']){
									$arCompare = array();
									foreach($arResult["ITEMS"] as &$arElement){
										$arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
										if (is_array($arPropertyValue)){
											sort($arPropertyValue);
											$arPropertyValue = implode(" / ", $arPropertyValue);
										}
										$arCompare[] = $arPropertyValue;
									}
									unset($arElement);
									$showRow = (count(array_unique($arCompare)) > 1);
								}
								if ($showRow){?>
									<?$arPropertyNames[] = $arProperty["NAME"];?>
									<tr>
										<td class="first-td bold">
										<?=$arProperty["NAME"]?>
										<?if($arResult["ALL_PROPERTIES"][$code]){?>
											<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arResult["ALL_PROPERTIES"][$code]["ACTION_LINK"])?>')" class="remove" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMPARE_REMOVE_PRODUCT"]?>"><i></i></span>
										<?}?>
										</td>
										<?foreach($arResult["ITEMS"] as &$arElement){?>
											<td>
												<?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
											</td>
										<?}
										unset($arElement);
										?>
									</tr>
								<?}
							}
						}
						if (!empty($arResult["SHOW_OFFER_PROPERTIES"])){
							foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty){
								$showRow = true;
								if ($arResult['DIFFERENT']){
									$arCompare = array();
									foreach($arResult["ITEMS"] as &$arElement){
										$arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
										if(is_array($arPropertyValue)){
											sort($arPropertyValue);
											$arPropertyValue = implode(" / ", $arPropertyValue);
										}
										$arCompare[] = $arPropertyValue;
									}
									unset($arElement);
									$showRow = (count(array_unique($arCompare)) > 1);
								}
								if ($showRow){?>
									<?$arPropertyNames[] = $arProperty["NAME"];?>
									<tr>
										<td class="first-td bold">
											<?=$arProperty["NAME"]?>
											<?if($arResult["ALL_OFFER_PROPERTIES"][$code]){?>
												<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arResult["ALL_OFFER_PROPERTIES"][$code]["ACTION_LINK"])?>')" class="remove" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMPARE_REMOVE_PRODUCT"]?>"><i></i></span>
											<?}?>
										</td>
										<?foreach($arResult["ITEMS"] as &$arElement){?>
											<td>
												<?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
											</td>
										<?}
										unset($arElement);
										?>
									</tr>
								<?}
							}
						}?>
					</table>
				</div>
			</div>

		</div>

	</div>

	<div class="wrap_remove_button visible-sm visible-xs">
				    
		<?
        $arStr=$arCompareIDs=array();
        
		if($arResult["ITEMS"]){
			foreach($arResult["ITEMS"] as $arItem){
				$arCompareIDs[]=$arItem["ID"];
			}
		}
        
		$arStr=implode("&ID[]=", $arCompareIDs);
        ?>
        
		<div class="remove_all_compare" onclick="CatalogCompareObj.MakeAjaxAction('/catalog/compare/?action=DELETE_FROM_COMPARE_RESULT&ID[]=<?=$arStr?>', 'Y');"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["COMPARE_CLEAR_ALL"]?></div>
	</div>
	

	<?/*ob_start()?>

		<?if(!empty($arPropertyNames)):?>

			<table class="prop_title_table data_table_props compare_view active clone">
				<tbody>
					<?foreach ($arPropertyNames as $nameProperty){?>
						<tr>
							<td class="bold"><?=$nameProperty?></td>
						</tr>
					<?}?>
				</tbody>
			</table>

		<?endif;?>

	<?$prop_title_table = ob_get_clean();?>

	<?$APPLICATION->AddViewContent('prop_title_table', $prop_title_table);*/?>
		

	<script type="text/javascript">
		$(document).ready(function(){
			$(window).on('resize', function(){
				createTableCompare($('.data_table_props:not(.clone)'), $('.prop_title_table'), $('.data_table_props.clone'));
				initSly();
			});
			$(window).resize();
		})
	</script>


<?if ($isAjax){
	die();
}?>



</div>
<script type="text/javascript">
	var CatalogCompareObj = new BX.Iblock.Catalog.CompareClass("bx_catalog_compare_block");
</script>