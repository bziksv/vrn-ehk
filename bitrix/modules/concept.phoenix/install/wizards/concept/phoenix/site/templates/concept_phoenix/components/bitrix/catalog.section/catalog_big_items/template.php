<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main;

/** @var array $arParams */
/** @var array $arItem */
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
global $PHOENIX_TEMPLATE_ARRAY;
?>



<?if( !empty($arResult["ITEMS"]) ):?>

    <?
    	$arResult["ITEMS_COUNT"] = intval($arResult["ITEMS_COUNT"]);

    	if($arResult["ITEMS_COUNT"] > 1)
    	{
    		$colHead = "col-xl-8 col-lg-7 col-sm-6";

		    if($arParams["SIDEMENU"])
		        $colHead = "col-xl-7 col-sm-6";
    	}
    	else
    		$colHead = "";

    	/*$this->SetViewTarget($arParams["OBJ_NAME"]);

    		echo "<div class=\"".$colHead." col-12\">";

    	$this->EndViewTarget();*/

        $showBuyBtn = $showBuyBtnOption= ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_PRODUCT_ON"]["VALUE"]["ACTIVE"] === "Y") ? 1 : 0;
		$showBtnBasket = $showBtnBasketOption =($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"] === "Y" ) ? 1 : 0;

        $arResult['ZOOM_ON'] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['ZOOM_ON']['VALUE']['ACTIVE'] == "Y" ? 'zoom' : '';

        $countItems = count($arResult["ITEMS"]);
        $firstItem = array_shift($arResult["ITEMS"]);
        array_unshift($arResult["ITEMS"], $firstItem);


        $colsBlockPictures = "col-xl-8 col-lg-7 col-sm-6";
        $colsBlockInfo = "col-xl-4 col-lg-5 col-sm-6";

        if($arParams["SIDEMENU"])
        {
        	$colsBlockPictures = "col-xl-7 col-sm-6";
        	$colsBlockInfo = "col-xl-5 col-sm-6";
        }

    ?>

    <div class="img-for-lazyload-parent finish-bottom">
    	<div class="correct-cols-head" data-head-cols="<?=$colHead?>" data-head-target="<?=$arParams["OBJ_NAME"]?>"></div>

    	<img class="lazyload img-for-lazyload slider-start" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">
    	

	    <div class="slider_catalog_big_items cart-info-block parent-slider-item-js slider-dots-style tone-<?=$arParams["CLR_TONE"]?> <?=($arParams["SIDEMENU"])? "min":""?>" data-count = "<?=$arResult["ITEMS_COUNT"]?>">
	       
	        <?foreach ($arResult['ITEMS'] as $keyItem => $arItem):?>

	        	<?
	        		$itemIds = $arItem["VISUAL"];

					if( strlen($arItem["PREVIEW_TEXT_POSITION"]) )
					    $previewTextPos = $arItem["PREVIEW_TEXT_POSITION"];

					else
						$previewTextPos = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['PREVIEW_TEXT_POSITION']['VALUE'];

					if( strlen($previewTextPos)<=0 )
					    $previewTextPos = "under_pic";
	        	?>


	            <div class="catalog-slide <?=($keyItem != 0) ? 'noactive-slide-lazyload' : ''?>">
	                <div id="<?=$itemIds['ID']?>" class="catalog-item <?if($PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"]):?>parent-tool-settings<?endif;?>">

	                    <div class="row">

	                        <div class="<?=$colsBlockPictures?> col-12 info-left-side wrapper-delay-compare-icons-parent"> 

	                            <div class="wrapper-picture row" id="<?=$itemIds['BIG_SLIDER']?>">
				                    <link itemprop="image" href="<?=$arItem["FIRST_ITEM"]["MORE_PHOTOS"][0]["BIG"]["SRC"]?>">

				                    <?
				                        $colLeft = "d-none";
				                        $colRight = "col-12";

				                        
				                        if( $arItem["FIRST_ITEM"]['MORE_PHOTOS_COUNT'] > 1 || strlen($arItem['VIDEO_POPUP']))
				                        {
				                            $colLeft = "col-2";
				                            $colRight = "col-10";
				                        }
				                    ?>

				                    <div class="wrapper-controls <?=$colLeft?> <?if(strlen($arItem['VIDEO_POPUP'])):?>video<?endif;?>">
				                        <div class="controls-pictures">

				                            <?if ( !empty($arItem["FIRST_ITEM"]['MORE_PHOTOS']) ):?>

				                                <?
				                                    $morePhotos = false;
				                                    $quantityPhoto = 0;

				                                ?>

				                                <?foreach ($arItem["FIRST_ITEM"]['MORE_PHOTOS'] as $key => $photo):?>

				                                    <?
				                                        if( ($key+1) > 3 ){
				                                            $morePhotos= true;
				                                            break;
				                                        }
				                                        $quantityPhoto++;
				                                    ?>

				                                    <div class="small-picture <?=($key == 0 ? 'active' : '')?>" data-value="<?=$photo['ID']?>">
				                                        <img class="lazyload" data-src="<?=$photo['SMALL']['SRC']?>" alt="<?=strip_tags($photo['DESC'])?>">
				                                    </div>


				                                <?endforeach;?>

				                               

				                                <?if($morePhotos):?>

				                                    <div class="more">

				                                        <a class="open-popup-gallery"
				                                            data-popup-gallery="<?=$arItem["FIRST_ITEM"]["ID"]?>_<?=$obName?>"
				                                        >
				                                        <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MORE"]?> <?echo (intval($arItem["FIRST_ITEM"]['MORE_PHOTOS_COUNT']) - $quantityPhoto);?></a>
				                                    </div>

				                                <?endif;?>


				                            <?endif;?>

				                        </div>

				                        <?if(strlen($arItem['VIDEO_POPUP'])):?>
				                            <?$iframe = CPhoenix::createVideo($arItem['VIDEO_POPUP']);?>

				                            <a class="call-modal callvideo" data-call-modal="<?=$iframe["ID"]?>">
				                                <div class="video-play"></div>
				                                <div class="video-play-desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["VIDEO"]?></div>
				                            </a>
				                        <?endif;?>

				                    </div>


				                    <div class="outer-big-picture <?=$colRight?>">
				                        <div class="wrapper-big-picture">

				                            <?if ( !empty($arItem["FIRST_ITEM"]['MORE_PHOTOS']) ):?>
				                                <?foreach ($arItem["FIRST_ITEM"]['MORE_PHOTOS'] as $key => $photo):?>

				                                    <div class="big-picture <?=($key == 0 ? 'active' : '')?>"  data-id="<?=$photo['ID']?>">

				                                        <a 
				                                            class="cursor-loop open-popup-gallery d-block <?=$arItem['ZOOM_ON']?>"
				                                            data-popup-gallery="<?=$arItem["FIRST_ITEM"]["ID"]?>_<?=$obName?>"

				                                        >
				                                            <img <?/*src="<?=$photo['MIDDLE']['SRC']?>"*/?>
				                                                class="d-block mx-auto img-fluid open-popup-gallery-item lazyload"
				                                                data-src = "<?=$photo['BIG']['SRC']?>"
				                                                data-popup-gallery="<?=$arItem["FIRST_ITEM"]["ID"]?>_<?=$obName?>"
				                                                data-small-src = "<?=$photo['SMALL']['SRC']?>"
				                                                data-big-src = "<?=$photo['BIG']['SRC']?>"
				                                                data-desc = "<?=$photo['DESC']?>"
				                                                alt="<?=$photo['DESC']?>"
				                                                >
				                                        </a>

				                                    </div>

				                                <?endforeach;?>
				                            <?else:?>

				                                <div class="big-picture active">
				                                    <img data-src="<?=$no_photo_src?>" class="d-block mx-auto img-fluid lazyload" alt="">
				                                    <link itemprop="image" href="<?=$no_photo_src?>">  
				                                </div>

				                            <?endif;?>

				                        </div>

				                        <?if(!empty($arItem["LABELS"])):?>
				                                                    
				                            <div class="wrapper-board-label">
				                                
				                                <?foreach($arItem["LABELS"]["XML_ID"] as $k=>$xml_id):?>
				                                    <div class="mini-board <?=$xml_id?>" title="<?=$arItem["LABELS"]["VALUE"][$k]?>"><?=$arItem["LABELS"]["VALUE"][$k]?></div><br/>
				                                <?endforeach;?>

				                            </div>
				                        
				                        <?endif;?>

				                    </div>

				                </div>

	                            <div class="wr-top-part first visivle-sm visible-xs">

									<a href="<?=$arItem['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="product-name bold" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BIG_SLIDER_CATALOG_NAME_TITLE"]?>">
										<?=$arItem["NAME_HTML"]?>
									</a>

									
								</div>


	                            <?if($previewTextPos == "under_pic"):?>

	                            	<div id="<?=$itemIds["PREVIEW_TEXT"]?>">


	                                    <div class="wrapper-description under-pic-pos row no-margin">

	                                    	<div class="col-12 detail-description" itemprop="description"><?=$arItem["FIRST_ITEM"]["PREVIEW_TEXT"]?></div>



	                                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>

	                                            <div class="col-12 board-rating-reviews row no-gutters align-items-center">
	                                                
	                                                <?if($arResult["RATING_VIEW"] == "simple"):?>
                                                    
					                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItem['ID'], "CLASS"=>"simple-rating hover"));?>

					                                <?elseif($arResult["RATING_VIEW"] == "full"):?>

					                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItem['ID'], "VIEW"=>"rating-reviewsCount", "HREF"=>$arItem["DETAIL_PAGE_URL"]."#rating-block"));?>

					                                <?endif;?>
	                                            </div>
	                                        <?endif;?>

	                                        
	                                    </div>


                                    </div>
	                               

	                            <?endif;?>


	                            <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y" 
	                                  || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y" ):?>

	                                <div class="wrapper-delay-compare-icons">

	                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DELAY_ON"]["VALUE"]["ACTIVE"] == "Y"):?>
	                                        <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_DELAY_TITLE"]?>" class="icon delay add2delay" id = "<?=$itemIds["DELAY"]?>" data-item="<?=$arItem["ID"]?>"></div>
	                                    <?endif;?>


	                                    <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]["VALUE"]["ACTIVE"] == "Y"):?>
	                                        <div title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_COMPARE_TITLE"]?>" class="icon compare add2compare" id = "<?=$itemIds["COMPARE"]?>" data-item="<?=$arItem["ID"]?>"></div>
	                                    <?endif;?>
	                                
	                                </div>

	                            <?endif;?>
	                            
	                        </div>


	                        <div class="<?=$colsBlockInfo?> col-12 info-right-side <?=($arItem['CONFIG']['SHOW_PRICE_SIDE'])?'':'d-none';?>" >  

	                            <div class="info-right-side-inner">
	                            	<div class="ghost-bl"></div>

	                            	<div class="wr-top-part second">

	                            		<div class="hidden-sm hidden-xs">
	
			                            	<a href="<?=$arItem['FIRST_ITEM']['DETAIL_PAGE_URL']?>" class="product-name bold" title="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BIG_SLIDER_CATALOG_NAME_TITLE"]?>">
			                            		<?=$arItem["NAME_HTML"]?>
			                            	</a>
			                            </div>

	                                	<div class="wrapper-article-available row no-gutters justify-content-between" id="<?=$itemIds['ARTICLE_AVAILABLE']?>">

				                                <div class="detail-article italic <?if(strlen($arItem["FIRST_ITEM"]["ARTICLE"])<=0):?>d-none<?endif;?>"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"].$arItem["FIRST_ITEM"]["ARTICLE"]?></div>

				                                <div style="display: none;" itemprop="additionalProperty" itemscope="" itemtype="http://schema.org/PropertyValue">
				                                    <meta itemprop="name" content="<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"]?>">
				                                    <meta itemprop="value" content="<?=addslashes($arItem["FIRST_ITEM"]["ARTICLE"])?>">
				                                </div>
					                        
					                        <div class="product-available-js hidden-js"><?=$arItem["FIRST_ITEM"]["QUANTITY"]["HTML"]?></div>
					                    </div>


	                                </div>

	                                <div class="wr-bot-part">


		                                <?if($previewTextPos == "right"):?>

		                                    <div class="wrapper-description right-pos" id="<?=$itemIds["PREVIEW_TEXT"]?>">
					                            <div class="detail-description" itemprop="description"><?=$arItem["FIRST_ITEM"]["PREVIEW_TEXT"]?></div>

						                      

		                                        <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>

		                                            <div class="board-rating-reviews row no-gutters align-items-center">
		                                                <?if($arResult["RATING_VIEW"] == "simple"):?>
                                                    
						                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItem['ID'], "CLASS"=>"simple-rating hover"));?>

						                                <?elseif($arResult["RATING_VIEW"] == "full"):?>

						                                    <?=CPhoenix::GetRatingVoteHTML(array("ID"=>$arItem['ID'], "VIEW"=>"rating-reviewsCount", "HREF"=>$arItem["DETAIL_PAGE_URL"]."#rating-block"));?>

						                                <?endif;?>
		                                            </div>

		                                        <?endif;?>
		                                    </div>

		                                <?endif;?>
		                                


		                                <div class="wrapper-price-sku-props">

		                                	<div class="wrapper-price block-price <?=($arItem["FIRST_ITEM"]['MODE_ARCHIVE']=="Y" || $arItem["FIRST_ITEM"]['PRICE']["PRICE"] == '-1') ? 'd-none' : ''?>">


					                            <?if( strlen($arItem["DESC_FOR_ACTUAL_PRICE"]) ):?>
					                                <div class="desc-title"><?=$arItem["DESC_FOR_ACTUAL_PRICE"]?></div>
					                            <?endif;?>

					                            <div class="name-type-price d-none">
					                                <?=$arResult['FIRST_ITEM']['PRICE']['NAME']?>
					                            </div>

					                            <div class="board-price row no-gutters">
					                                <div class="actual-price">
					                                    <span class="price-value bold" id="<?=$itemIds['PRICE']?>"><?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_PRICE']?></span><span class="unit <?=(strlen($arItem["FIRST_ITEM"]['MEASURE_HTML'])<=0 ? '' : 'd-none')?>" id="<?=$itemIds['QUANTITY_MEASURE']?>"><?=$arItem["FIRST_ITEM"]['MEASURE_HTML']?></span>
					                                </div>

					                                <div class="old-price align-self-end product-old-price-value-js <?=($arItem["FIRST_ITEM"]["SHOW_DISCOUNT"] ? '' : 'd-none')?>" id="<?=$itemIds['OLD_PRICE']?>"><?=($arItem["FIRST_ITEM"]["SHOW_DISCOUNT"] ? $arItem["FIRST_ITEM"]['PRICE']['PRINT_BASE_PRICE'] : '')?></div>

					                            </div>


					                            <div class="wrapper-discount-cheaper row no-gutters align-items-center">


					                                <div id="<?=$itemIds['DISCOUNT_PRICE']?>"  class="wrapper-discount <?=($arItem["FIRST_ITEM"]["SHOW_DISCOUNT"] ? '' : 'd-none')?>">

					                                    <span class="item-style desc-discount"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ECONOMY"]?></span><span class="item-style actual-econom bold product-price-discount-js"><?=$arItem["FIRST_ITEM"]['PRICE']['PRINT_DISCOUNT']?></span>
					                                    
					                                    <span id="<?=$itemIds['DISCOUNT_PERCENT']?>" class="item-style actual-discount product-percent-discount-js <?=( ($arItem['CONFIG']["SHOW_DISCOUNT_PERCENT"] === 'Y' && $arItem["FIRST_ITEM"]['PRICE']['PERCENT']>0) ? '' : 'd-none')?>">
					                                        <?=-$arItem["FIRST_ITEM"]['PRICE']['PERCENT']?>%
					                                        
					                                    </span>
					                                </div>
					                                
					                                <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['BETTER_PRICE']["VALUE"] != "N"):?>
					                                   <span class="cheaper">
					                                        <a id="<?=$itemIds['CALL_FORM_BETTER_PRICE']?>">
					                                            <span class="bord-bot"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["FOUND_CHEAPER"]?></span>
					                                        </a>
					                                    </span>
					                                <?endif;?>

					                                
					                            </div>

					                            <?//if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']['VALUE']["ACTIVE"] == 'Y'):?>
					                            
				                                    <div class="wrapper-matrix-block" id= "<?=$itemIds["PRICE_MATRIX"]?>"><?=$arItem["FIRST_ITEM"]["PRICE_MATRIX_RESULT"]["HTML"]?></div>

					                            <?//endif;?>


					                        </div>


					                        <?if( $arItem['CONFIG']["SHOW_OFFERS"] ):?>

					                            <div id="<?=$itemIds['TREE']?>" class="wrapper-skudiv">


					                            	<?if(!empty($arItem['SKU_PROPS'])):?>


						                                <?foreach ($arItem['SKU_PROPS'] as $skuProperty):?>
						                                    
						                                    <?

						                                        $propertyId = $skuProperty['ID'];
						                                        $descTitle = "";

						                                        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic')
						                                            $descTitle = "desc-title-with-prop-name";
						                                    ?>


						                                    <div class="wrapper-sku-props <?if( strlen($descTitle) ):?>with-desc<?endif;?> clearfix" >

						                                        <div class="wrapper-title row no-gutters">
						                                            <div class="desc-title"><?=htmlspecialcharsEx($skuProperty['NAME'])?><span class="prop-name"></span> 

						                                                <?if(strlen($skuProperty["HINT"])):?>
						                                                    <i class="hint-sku fa fa-question-circle hidden-sm hidden-xs" data-toggle="sku-tooltip" data-placement="bottom" title="" data-original-title='<?=str_replace("'", "\"", $skuProperty["HINT"])?>'></i>
						                                                <?endif;?>
						                                            </div>
						                                            
						                                        </div>

						                                        <?if ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic' || $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic_with_info'):?>

						                                            <ul class="sku-props clearfix">

						                                                <?if(!empty($skuProperty['VALUES'])):?>


						                                                    <?foreach ($skuProperty['VALUES'] as $value):?>

						                                                        <?
						                                                            $styleTab = "";
						                                                            $styleHoverBoard = "";

						                                                            if(isset($value["PICT"]) || isset($value["PICT_SEC"]) )
						                                                            {
						                                                                if(isset($value["PICT_SEC"]))
						                                                                {
						                                                                    $styleHoverBoard .= "background-image: url('".$value['PICT_SEC']['BIG']."'); ";

						                                                                    if(isset($value["PICT"]))
						                                                                        $styleTab .= "background-image: url('".$value['PICT']['SMALL']."'); ";
						                                                                    else
						                                                                        $styleTab .= "background-image: url('".$value['PICT_SEC']['SMALL']."'); ";

						                                                                }

						                                                                else if(isset($value["PICT"]))
						                                                                {
						                                                                    $styleTab .= "background-image: url('".$value['PICT']['SMALL']."'); ";
						                                                                    $styleHoverBoard .= "background-image: url('".$value['PICT']['BIG']."'); ";
						                                                                }
						                                                            }

						                                                            if($value["COLOR"])
						                                                            {
						                                                                $styleTab .= "background-color:".$value["COLOR"]."; ";
						                                                                $styleHoverBoard .= "background-color:".$value["COLOR"]."; ";
						                                                            }
						                                                        ?>


						                                                            <li title='<?=CPhoenix::prepareText($value['NAME'])?>' class="detail-color"

						                                                                    data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
						                                                                    data-onevalue="<?=$value['ID']?>"
						                                                                    

						                                                                >

						                                                                <div class="color" style="<?=$styleTab?>"></div>


						                                                                <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'pic_with_info'):?>

						                                                                    <div class="wrapper-hover-board">
						                                                                        <div class="img" style="<?=$styleHoverBoard?>"></div>
						                                                                        <div class="desc"><?=$value['NAME']?></div>
						                                                                        <div class="arrow"></div>
						                                                                    </div>

						                                                                <?endif;?>

						                                                                <span class="active-flag"></span>

						                                                            </li>

						                                                    <?endforeach;?>

						                                                <?endif;?>
						                                            </ul>

						                                        <?elseif($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']["VALUES"][$skuProperty["ID"]]["VALUE_2"] == 'select'):?>

						                                            <div class="wrapper-select-input">

						                                                <ul class="sku-props select-input">

						                                                    <li class="area-for-current-value"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SKU_SELECT_TITLE"]?></li>

						                                                    <?if(!empty($skuProperty['VALUES'])):?>

						                                                        <?foreach ($skuProperty['VALUES'] as $value):?>
						                                                            <li title='<?=CPhoenix::prepareText($value['NAME'])?>'

						                                                                    data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
						                                                                    data-onevalue="<?=$value['ID']?>"

						                                                                ><?=$value['NAME']?></li>
						                                                        <?endforeach;?>

						                                                    <?endif;?>
						                                                   
						                                                </ul>

						                                                <div class="ar-down"></div>

						                                            </div>


						                                        <?else:?>

						                                            <ul class="sku-props">

						                                                <?if(!empty($skuProperty['VALUES'])):?>

						                                                    <?foreach ($skuProperty['VALUES'] as &$value):?>
						                                                        <li title='<?=CPhoenix::prepareText($value['NAME'])?>' class="detail-text"

						                                                            data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
						                                                            data-onevalue="<?=$value['ID']?>"

						                                                        ><?=$value['NAME']?></li>
						                                                    <?endforeach;?>

						                                                <?endif;?>
						                                            </ul>


						                                        <?endif;?>
						                              
						                                        
						                                    </div>

						                                <?endforeach;?> 

					                                <?endif;?>

					                            </div>

					                        <?endif;?>

		                                    


		                                    <?if( isset($arItem["MODAL_WINDOWS"]) && !empty($arItem["MODAL_WINDOWS"]) || isset($arItem["FORMS"]) && !empty($arItem["FORMS"]) || isset($arItem["QUIZS"]) && !empty($arItem["QUIZS"]) ):?>

		                                        <div class="wrapper-modals-btn row no-gutters">

		                                            <?if( isset($arItem["MODAL_WINDOWS"]) && !empty($arItem["MODAL_WINDOWS"])):?>

		                                                <?foreach ($arItem["MODAL_WINDOWS"] as $arModalWindow):?>

		                                                    <div class="modal-btn"><a class="call-modal callmodal" data-call-modal="modal<?=$arModalWindow["ID"]?>"><span class="bord-bot"><?=$arModalWindow["NAME"]?></span></a></div>

		                                                <?endforeach;?>

		                                            <?endif;?>

		                                            <?if( isset($arItem["FORMS"]) && !empty($arItem["FORMS"])):?>

		                                                <?foreach ($arItem["FORMS"] as $arForm):?>

		                                                    <div class="modal-btn"><a class="call-modal callform" data-call-modal="form<?=$arForm["ID"]?>"><span class="bord-bot"><?=$arForm["NAME"]?></span></a></div>

		                                                <?endforeach;?>

		                                            <?endif;?>

		                                            <?if( isset($arItem["QUIZS"]) && !empty($arItem["QUIZS"])):?>

		                                                <?foreach ($arItem["QUIZS"] as $arQuiz):?>

		                                                    <div class="modal-btn"><a class="call-wqec" data-wqec-section-id="<?=$arQuiz["ID"]?>"><span class="bord-bot"><?=$arQuiz["NAME"]?></span></a></div>

		                                                <?endforeach;?>

		                                            <?endif;?>

		                                        </div>


		                                    <?endif;?>

		                                    <div class="wrapper-quantity quantity-block hidden-js" data-item="<?=$arItem["FIRST_ITEM"]['ID']?>">

					                            <div class="wrapper-title">

					                                <?if($arItem["TITLE_FOR_QUANTITY"]):?>
					                                    <div class="desc-title">

					                                        <?=$arItem["TITLE_FOR_QUANTITY"]?>

					                                        <?if( strlen($arItem["TITLE_FOR_QUANTITY_HINT"]) ):?>
					                                            <i class="ic-hint fa fa-question-circle hidden-sm hidden-xs" data-toggle="tooltip" data-placement="right" title="<?=CPhoenix::prepareText($arItem["TITLE_FOR_QUANTITY_HINT"])?>"></i>
					                                        <?endif;?>

					                                    </div>
					                                <?endif;?>

					                                
					                            </div>

					                         
					                            <div class="wrapper-quantity-total row no-gutters align-items-center">

					                                <div class="col-6">

					                                    <div class="quantity-container row no-gutters align-items-center justify-content-between">
					                                        <span class="product-item-amount-field-btn-minus" id="<?=$itemIds['QUANTITY_DOWN']?>">&minus;</span>
					                                        <input class="product-item-amount-field" id="<?=$itemIds['QUANTITY']?>" type="number"
					                                            value="<?=$arItem["FIRST_ITEM"]["PRICE"]["MIN_QUANTITY"]?>">
					                                        <span class="product-item-amount-field-btn-plus" id="<?=$itemIds['QUANTITY_UP']?>">&plus;</span>
					                                    </div>
					                                </div>

					                                <div class="col-6">

					                                    <div class="total-container d-none" id="<?=$itemIds['PRICE_TOTAL']?>">
					                                        <span class="desc-total"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRICE_TOTAL_PREFIX"]?></span><span class="total-value bold" title=''></span>
					                                    </div>

					                                </div>

					                            </div>

					                        </div>


					                        <div class="wrapper-btns row no-gutters justify-content-between hidden-js" id="<?=$itemIds["BASKET_ACTIONS"]?>">

					                            <div class="col-6 left-btn wr-btn-basket d-none">
					                                <a 
					                                id = "<?=$itemIds['ADD2BASKET']?>"
					                                href="javascript:void(0);" 
					                                title = "<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]?>" 
					                                data-item="<?=$arItem['ID']?>"
					                                class="main-color bold add-to-cart-style add2basket" ><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]["~VALUE"]?></a>

					                                <a 
					                                id = "<?=$itemIds['MOVE2BASKET']?>"
					                                href="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]["VALUE"]?>" 
					                                title = "<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?>"
					                                data-item = "<?=$arItem["ID"]?>"

					                                style = "display: none;"

					                                class="bold added-to-cart-style move2basket"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"]?></a>
					                            </div>

					                            <div class="col-6 right-btn wr-btn-fast-order d-none">
					                                <a 
					                                    id="<?=$itemIds['FAST_ORDER']?>"
					                                    title = "<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_CATALOG_DETAIL"]["VALUE"]?>" 
					                                    href="javascript:void(0);"
					                                    class="fast-order second-btn-style"
					                                >
					                                <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_CATALOG_DETAIL"]["~VALUE"]?>
					                                    
					                                </a>
					                            </div>

					                        </div>



					                        <div class="hidden-js <?=($arItem["FIRST_ITEM"]["SHOWPREORDERBTN"])?"":"d-none"?>">

					                            <a id="<?=$itemIds['PREORDER']?>"
					                                title = "<?=CPhoenix::prepareText($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_BTN_NAME"]["VALUE"])?>"
					                                class="btn-transpatent detail-btn-preorder"
					                            ><span class="icon-load"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_BTN_NAME"]["~VALUE"]?></span></a>
					                        </div>

					                        <?if($arItem["SHOW_SET_PRODUCT"]):?>
					                            <a href="#set_product" class="btn-transpatent ic-info scroll btn-setproduct"><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SET_PRODUCT_BTN_NAME_2"]?></span></a>
					                        <?endif;?>

					                        <div id="<?=$itemIds['SUBSCRIBE']?>">
					                            <?
					                                $APPLICATION->IncludeComponent(
					                                    'bitrix:catalog.product.subscribe',
					                                    'main',
					                                    array(
					                                        'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
					                                        'PRODUCT_ID' => $arItem['ID'],
					                                        'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
					                                        'BUTTON_CLASS' => 'btn-transpatent product-item-detail-buy-button',
					                                        'DEFAULT_DISPLAY' => !$arItem["FIRST_ITEM"]['CAN_BUY'],
					                                        'MESS_BTN_SUBSCRIBE' => "",
					                                    ),
					                                    $component,
					                                    array('HIDE_ICONS' => 'Y')
					                                );
					                            ?>
					                        </div>
		                                    


					                        <?CPhoenix::createBtnHtml($arItem["BTN"])?>


					                        <?if( strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['COMMENT_FOR_DETAIL_CATALOG']['VALUE']) ):?>

					                            <div class="comment-detail-catalog"><span><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['COMMENT_FOR_DETAIL_CATALOG']['~VALUE']?></span></div>

					                        <?endif;?>
		                                        
		                                    

		                                </div>

	                                </div>

	                            </div>

	                           
	                        </div>
	                    </div>

	                    <?CPhoenix::admin_setting($arItem, true)?>
	                </div>

	            </div>

	        <?endforeach;?>

	    </div>

    	<img class="lazyload img-for-lazyload slider-finish" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png" data-id="<?=$firstItem["ID"]?>">


    </div>



    <script>
        BX.message({
            ECONOMY_INFO_MESSAGE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ECONOMY"]?>',
            PRICE_TOTAL_PREFIX: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["PRICE_TOTAL_PREFIX"]?>',
            RELATIVE_QUANTITY_MANY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_2"]?>',
            RELATIVE_QUANTITY_FEW: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_FEW"]["DESCRIPTION_2"]?>',
            RELATIVE_QUANTITY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_NOEMPTY"]?>',
            RELATIVE_QUANTITY_EMPTY: '<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_QUANTITY_MANY"]["DESCRIPTION_EMPTY"]?>',
            MORE_PHOTOS: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["MORE"]?>',
            SITE_ID: '<?=$component->getSiteId()?>',
            ARTICLE: '<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["ARTICLE_SHORT"]?>',
        });
      
    </script>



<?endif;?>