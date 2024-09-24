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
use \Bitrix\Main\Localization\Loc as Loc;
Loc::loadMessages(__FILE__);
global $PHOENIX_TEMPLATE_ARRAY;
CModule::IncludeModule('concept.phoenix');
CPhoenix::phoenixOptionsValues(SITE_ID, array(
	"design"
));
?>

<?
$block_name = $arResult['~NAME'];

if(strlen($arResult["PROPERTIES"]["HEADER"]["VALUE"]) > 0)
    $block_name .= " (".$arResult["PROPERTIES"]["HEADER"]["~VALUE"].")";

$block_name = htmlspecialcharsEx(strip_tags(html_entity_decode($block_name)));
?>

<div class="container">
	<div class="tariff-container blur-container">
					
		<div class="content no-margin-top-bot">

	        <div class="top-wrap">


			    <div class="info-table">

					<?if(strlen($arResult["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"]) > 0):?>

				        <div class="info-cell image-wrap hidden-xs">
				        	<?$img = CFile::ResizeImageGet($arResult["PROPERTIES"]["TARIFF_PICTURE"]["VALUE"], array('width'=>400, 'height'=>400), BX_RESIZE_IMAGE_EXACT, false, false, false, 85);?>
				        	<img class="img-fluid" alt="<?=$arResult["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]?>" src="<?=$img["src"]?>" />
				        </div>
				        
			        <?endif?>


			        <div class="info-cell text-wrap no-margin-top-bot">


			            <?if(strlen($arResult["PROPERTIES"]["TARIFF_NAME"]["VALUE"]) > 0):?>

				            <div class="name main1">
				                <?=$arResult["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]?> <?if($arResult["PROPERTIES"]["TARIFF_HIT"]["VALUE"] =="Y"):?><span class="hit"></span><?endif;?>
				            </div>

						<?endif?>

						<?if(strlen($arResult["PROPERTIES"]["TARIFF_PREVIEW_TEXT"]["VALUE"]) > 0):?>

				            <div class="text">
				                <?=$arResult["PROPERTIES"]["TARIFF_PREVIEW_TEXT"]["~VALUE"]?>
				            </div>

						<?endif?>

						<?if(strlen($arResult["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0):?>

			            	<div class="price-sm main1 visible-sm"><?=$arResult["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"]?> <?if(strlen($arResult["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0):?><span class="old-price main2"><?=$arResult["PROPERTIES"]["TARIFF_OLD_PRICE"]["~VALUE"]?></span><?endif?></div>

			            <?endif?>
			        </div>

					
	                
					
			    </div>

			</div>



			<?

				$left = false;
				$right = false;
					

				if(strlen($arResult["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]["TEXT"]) > 0 || !empty($arResult["PROPERTIES"]["TARIFF_PRICES"]["VALUE"]) || !empty($arResult["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"]))

				{
					$left = true;
				}

				if(!empty($arResult["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) || !empty($arResult["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"]) || strlen($arResult["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0 || strlen($arResult["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0 || strlen($arResult["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0)
				{
					$right = true;

				}
			?>

			<div class="tariff-container-inner<?if($left && $right):?> on-part<?endif;?><?if(!$right):?> no-right<?endif;?><?if(!$left):?> no-left<?endif;?> row">

				<?if($left):?>

					<div class="<?if($right):?>col-md-8 col-12<?else:?>col-12<?endif;?> tariff-container-inner-cell left">

						<div class="part-wrap">
					

							<?if(strlen($arResult["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["VALUE"]["TEXT"]) > 0):?>

						        <div class="text-content no-margin-top-bot">
						            <?=$arResult["PROPERTIES"]["TARIFF_DETAIL_TEXT"]["~VALUE"]["TEXT"]?>
						        </div>

					        <?endif;?>

					        <?if(!empty($arResult["PROPERTIES"]["TARIFF_PRICES"]["VALUE"])):?>
		                
		                        <div class="list-wrap">
		                        
		                            <?if(strlen($arResult["PROPERTIES"]["TARIFF_PRICES_TITLE"]["VALUE"]) > 0):?>
		                                <div class="name-list main1"><?=$arResult["PROPERTIES"]["TARIFF_PRICES_TITLE"]["~VALUE"]?></div>
		                            <?endif;?>


		                            <ul class="list-char">
		                                
		                                <?foreach($arResult["PROPERTIES"]["TARIFF_PRICES"]["~VALUE"] as $k=>$val):?>
		                                    <li class="clearfix">
		                                    
		                                        <table class="mobile-break">
		                                            <tr>
		                                                <td class="left">
		                                                    <div><?=$val?></div>
		                                                </td>
		                                                
		                                                <td class="dotted">
		                                                    <div></div>
		                                                </td>
		                                                
		                                                <td class="right">
		                                                    <div class="main1"><?=$arResult["PROPERTIES"]["TARIFF_PRICES"]["~DESCRIPTION"][$k]?></div>
		                                                </td>
		                                            </tr>
		                                        </table>
		                                    
		                                    </li>
		                                <?endforeach;?>

		                            </ul>
		                        </div>
		                    
		                    <?endif;?>

							<?if(!empty($arResult["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"])):?>

						        <div class="gallery <?if($arResult["PROPERTIES"]["TARIFF_GALLERY_BORDER"]["VALUE"] == "Y"):?>border-on<?endif;?>">
						        	<?if(strlen($arResult["PROPERTIES"]["TARIFF_GALLERY_TITLE"]["VALUE"]) > 0):?>
							            <div class="gallery-name main1">
							                <?=$arResult["PROPERTIES"]["TARIFF_GALLERY_TITLE"]["~VALUE"]?>
							            </div>
						            <?endif;?>


						            <div class="row clearfix">
						            	<?
				                            $arWaterMark = Array();
					    
				                            if($arResult["PROPERTIES"]["TARIFF_WATERMARK"]["VALUE"] > 0)
				                            {
				    
				                                $arWaterMark = Array(
				                                    array(
				                                        "name" => "watermark",
				                                        "position" => "center",
				                                        "type" => "image",
				                                        "size" => "big",
				                                        "file" => $_SERVER["DOCUMENT_ROOT"].CFile::GetPath($arResult["PROPERTIES"]["TARIFF_WATERMARK"]["VALUE"]), 
				                                        "fill" => "exact",
				                                    )
				                                );
				                            }

				                            // $colls_gal = "col-md-3 col-4";
				                            // if(!$right)
				                            	$colls_gal = "col-md-2 col-4";


					                             
				                        ?>
										
										<?foreach($arResult["PROPERTIES"]["TARIFF_GALLERY"]["VALUE"] as $k => $arImages):?>

											

											<?$file_big = CFile::ResizeImageGet($arImages, array('width'=>1600, 'height'=>1600), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, $arWaterMark, false, 90);?>

					                    	<?$file_min = CFile::ResizeImageGet($arImages, array('width'=>300, 'height'=>300), BX_RESIZE_IMAGE_EXACT, false, false, false, 90);?>


							                <div class="<?=$colls_gal?>">
							                	<div class="img-wrap">

							                    	<a data-gallery="catalog<?=$arResult["ID"]?>" href="<?=$file_big["src"]?>" title="<?=$arResult["PROPERTIES"]["TARIFF_GALLERY"]["~DESCRIPTION"][$k]?>" class="cursor-loop">

					                        		<img class="img-fluid" alt="<?=$arResult["NAME"]?>"  src="<?=$file_min["src"]?>" /></a>
							                    </div>
							                </div>

							                <?
							                	if(!$right)
								                {
								                	if(($k+1) % 6 == 0)
								                		echo "<span class='clearfix hidden-xs'></span>";

								                	if(($k+1) % 3 == 0)
								                		echo "<span class='clearfix visible-xs'></span>";

								                }
								                else
								                {
								                	if(($k+1) % 4 == 0)
								                		echo "<span class='clearfix hidden-xs'></span>";

								                	if(($k+1) % 3 == 0)
								                		echo "<span class='clearfix visible-xs'></span>";
								                }
							                ?>


						                <?endforeach;?>

						            </div>
						        </div>

					        <?endif;?>

				        </div>

			        </div>

		        <?endif;?>

		        <?if($right):?>

			        <div class="col-md-4 col-12 tariff-container-inner-cell right">

			        	<div class="part-wrap">
			        	

				        	<?if(!empty($arResult["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"]) || !empty($arResult["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"])):?>
			                                                    
			                    <ul class='adv-plus-minus'>
			                        
			                        <?if(!empty($arResult["PROPERTIES"]["TARIFF_INCLUDE"]["VALUE"])):?>
			                            
			                            <?foreach($arResult["PROPERTIES"]["TARIFF_INCLUDE"]["~VALUE"] as $val):?>
			                                <li class="point-green"><?=$val?></li>
			                            <?endforeach;?>
			                            
			                        <?endif;?>
			                        
			                        <?if(!empty($arResult["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["VALUE"])):?>
			                            
			                            <?foreach($arResult["PROPERTIES"]["TARIFF_NOT_INCLUDE"]["~VALUE"] as $val):?>
			                                <li><?=$val?></li>
			                            <?endforeach;?>
			                            
			                        <?endif;?>
			                        
			                    </ul>
			                
			                <?endif;?>

			                <?if(strlen($arResult["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0 || strlen($arResult["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0):?>


					        	<div class="price-wrap">

					        		<?if(strlen($arResult["PROPERTIES"]["TARIFF_OLD_PRICE"]["VALUE"]) > 0):?>
						            	<div class="old-price main2"><?=$arResult["PROPERTIES"]["TARIFF_OLD_PRICE"]["~VALUE"]?></div>
						            <?endif?>

						        	<?if(strlen($arResult["PROPERTIES"]["TARIFF_PRICE"]["VALUE"]) > 0):?>


						            	<div class="price main1"><?=$arResult["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"]?></div>

						            <?endif?>

						        </div>

					        <?endif?>


				        	<?if(strlen($arResult["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]) > 0):?>

								<?if(strlen($arResult["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"]) <= 0):?>
				                    <?$arResult["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"] = "form";?>
				                <?endif;?>

						
						        <div class="button-wrap">

						        	<?
									    $arClass = array();
									    $arClass=array(
									        "XML_ID"=> $arResult["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
									        "FORM_ID"=> $arResult["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
									        "MODAL_ID"=> $arResult["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
									        "QUIZ_ID"=> $arResult["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"]
									    );
									    
									    $arAttr=array();
									    $arAttr=array(
									        "XML_ID"=> $arResult["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"],
									        "FORM_ID"=> $arResult["PROPERTIES"]["TARIFF_BUTTON_FORM"]["VALUE"],
									        "MODAL_ID"=> $arResult["PROPERTIES"]["TARIFF_MODAL"]["VALUE"],
									        "LINK"=> $arResult["PROPERTIES"]["TARIFF_BUTTON_LINK"]["VALUE"],
									        "BLANK"=> $arResult["PROPERTIES"]["TARIFF_BUTTON_BLANK"]["VALUE_XML_ID"],
									        "HEADER"=> $block_name,
									        "QUIZ_ID"=> $arResult["PROPERTIES"]["TARIFF_BUTTON_QUIZ"]["VALUE"],
									        "LAND_ID"=> $arResult["PROPERTIES"]["TARIFF_BUTTON_LAND"]["VALUE"]
									    );

									    $b_options = array(
	                                        "MAIN_COLOR" => "main-color",
	                                        "STYLE" => ""
	                                    );

	                                    if(strlen($arResult["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"]))
	                                    {

	                                        $b_options = array(
	                                            "MAIN_COLOR" => "btn-bgcolor-custom",
	                                            "STYLE" => "background-color: ".$arResult["PROPERTIES"]["TARIFF_BUTTON_BG_COLOR"]["VALUE"].";"
	                                        );

	                                    }
									?>
						        	

						            <a class="button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE'];?> <?=$b_options["MAIN_COLOR"]?> big from-modal element-item <?=CPhoenix::buttonEditClass ($arClass)?>" data-element-item-id="<?=$arResult["ID"]?>" data-element-item-type = "TRF"
					            	data-element-item-name = "
	                                    <?if(strlen($arResult["PROPERTIES"]["TARIFF_NAME"]["~VALUE"])):?>
	                                        <?=str_replace( "\"", "'", strip_tags($arResult["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>
	                                    <?else:?>
	                                        <?=str_replace( "\"", "'", strip_tags($arResult["PROPERTIES"]["TARIFF_NAME"]["~VALUE"]))?>&nbsp;(<?=str_replace( "\"", "'", strip_tags($arResult["~NAME"]))?>)
	                                    <?endif;?>
	                                "

	                                data-element-item-price = "<?=strip_tags($arResult["PROPERTIES"]["TARIFF_PRICE"]["~VALUE"])?>"

						            	<?if(strlen($b_options["STYLE"])):?>
	                                        style = "<?=$b_options["STYLE"]?>"
	                                    <?endif;?>

						            	<?=CPhoenix::buttonEditAttr($arAttr)?> title='<?=$arResult["PROPERTIES"]["TARIFF_BUTTON_NAME"]["VALUE"]?>'>

						            	<?/*if($arResult["PROPERTIES"]["TARIFF_BUTTON_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

										    <?
										    
										        $btn_name2 = GetMessage("LAND_CART_BTN_ADDED_NAME");

										        if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]) > 0)
										            $btn_name2 = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];
										    ?>

										    <span class="first">
										       <?=$arResult["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>
										    </span>

										    <span class="second">
										        <?=$btn_name2?>
										    </span> 

										<?else:*/?>

						            		<?=$arResult["PROPERTIES"]["TARIFF_BUTTON_NAME"]["~VALUE"]?>

						            	<?/*endif;*/?>
						            		
						            </a>

					
						        </div>
						 

					        <?endif?>
	                        
	                        <?if(!empty($arResult["PROPERTIES"]["TARIFF_COMMENT"]["VALUE"])):?>
	                                
	                            <div class="tariff-comment"><?=$arResult["PROPERTIES"]["TARIFF_COMMENT"]["~VALUE"]["TEXT"]?></div>
	                         
	                        <?endif;?>

				        </div>


			        </div>

		        <?endif;?>

	        </div>




	    </div>

	</div>
</div>