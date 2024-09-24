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

<?

	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"]) > 0)
	{
		$arFilter = Array(
			"IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
			"ACTIVE_DATE"=>"Y",
			"ACTIVE"=>"Y",
			"PROPERTY_BRAND"=>$arResult["ID"],
			array(
			    "LOGIC" => "OR",
			    array("=PROPERTY_REGION" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["ID"]),
			    array("PROPERTY_REGION" => false)
			)
		);
	}
	else
	{
		$arFilter = Array(
			"IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"],
			"ACTIVE_DATE"=>"Y",
			"ACTIVE"=>"Y",
			"PROPERTY_BRAND"=>$arResult["ID"],
			"PROPERTY_REGION" => false
		);
	}

	$arSelect = Array("ID", "PROPERTY_BRAND");


	$res = CIBlockElement::GetList(Array(), $arFilter, false, array("nTopCount" => "1"), $arSelect);

	$issetBrandCatalog = false;

	if($arBrandCatalog = $res->GetNext())
	    $issetBrandCatalog = true;


	$issetTextBlock = strlen($arResult["~DETAIL_TEXT"])>0;
	$issetVideoGallery = !empty($arResult["PROPERTIES"]["VIDEO_LINK"]["VALUE"]);
	$issetPhotoGallery = !empty($arResult["PROPERTIES"]["GALLERY_PHOTOS"]["VALUE"]);
	$issetCertificate = !empty($arResult["PROPERTIES"]["CERTIFICATE_PHOTOS"]["VALUE"]);

	
	
	/*if(!$issetBrandCatalog)
		$issetTextBlock = false;*/

	$showContent = $issetTextBlock || $issetVideoGallery ||$issetPhotoGallery || $issetCertificate || $issetBrandCatalog;

?>


<?$this->SetViewTarget('brand-headbg');?>
	<?
		if($arResult["PROPERTIES"]["HEADER_PICTURE"]["VALUE"])
		{
			$bg_pic = CFile::ResizeImageGet($arResult["PROPERTIES"]["HEADER_PICTURE"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false);
		}
		elseif(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["BG_PIC"]["VALUE"])>0)
		{
			$bg_pic = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["BG_PIC"]["VALUE"], array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false);
		}
	?>

	<?if(isset($bg_pic)):?> 
		data-src="<?=$bg_pic["src"]?>"
		style="background-image: url(<?=$bg_pic["src"]?>);"

	<?endif;?>

<?$this->EndViewTarget();?>

<?$this->SetViewTarget('brand-sidemenu');?>
	<?if(is_array($arResult["PROPERTIES"]["SIDE_MENU_HTML"]["VALUE"])):?>
        <div class="sidemenuHTML"><?=$arResult["PROPERTIES"]["SIDE_MENU_HTML"]["~VALUE"]["TEXT"]?></div>
    <?endif;?>
<?$this->EndViewTarget();?>

<?$this->SetViewTarget('detail-preview-text');?>
	<?if( strlen($arResult["~PREVIEW_TEXT"]) > 0):?>
	    <div class="subtitle"><?=$arResult["~PREVIEW_TEXT"]?></div>
	<?endif;?>
	<div class="wrapper-btns">
		<?if( $issetTextBlock ):?>
			<div class="wrapper-btn">
				<a href="#about_brand" class="scroll button-def secondary <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?>"><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BRANDS_LINK_TO_DETAIL_INFO"]?></span></a>
			</div>
		<?endif;?>

		<?if($issetBrandCatalog):?>
			<div class="wrapper-btn">
				<a href="#catalog_brand" class="scroll button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?>"><span class="ic-brand"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BRANDS_LINK_TO_CATALOG"]?></span></a>
			</div>
		<?endif;?>

		<?if(!$issetBrandCatalog && !$issetTextBlock):?>

			<div class="wrapper-btn">
				<a href="<?=$arParams["SECTION_URL"]?>" class="scroll button-def main-color <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?>"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BRANDS_LEVEL"]?></a>
			</div>
		
		<?endif;?>
	</div>
<?$this->EndViewTarget();?>



<?$this->SetViewTarget('brand-img');?>
		
	<?if( !empty($arResult["DETAIL_PICTURE"]) && isset($arResult["DETAIL_PICTURE"]["ID"]) && $arResult["DETAIL_PICTURE"]["ID"] > 0 ):?>

		<div class="wr-img">
			<img class="brand-image img-fluid d-block mx-auto" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>">
		</div>

	<?elseif(!empty($arResult["PREVIEW_PICTURE"]) && isset($arResult["PREVIEW_PICTURE"]["ID"]) && $arResult["PREVIEW_PICTURE"]["ID"] > 0 ):?>

		<div class="wr-img">
			<img class="brand-image img-fluid d-block mx-auto" src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>">
		</div>

	<?endif;?>

<?$this->EndViewTarget();?>

<?$this->SetViewTarget('detail-brand-name');?>
	<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["BRANDS_DESCRIPTION_CATALOG"]?><?=$arResult["~NAME"]?>
<?$this->EndViewTarget();?>






<?if( $issetTextBlock ):?>

	<div class="col-12 order-2">

		<div class="block small-block brand-block brand-description-block" id="about_brand">

			<?/*<div class="main-info">
				<div class="row">

					<div class="col-md-7 col-12">
						<div class="title bold"><?=$arResult["~NAME"]?></div>
						<?if( strlen($arResult["~PREVIEW_TEXT"]) ):?>
							<div class="preview-text italic"><?=$arResult["~PREVIEW_TEXT"]?></div>
						<?endif;?>
					</div>

					<div class="col-md-5 col-12 wrapper-preview-picture">

						<?if( !empty($arResult["PREVIEW_PICTURE"]) && isset($arResult["PREVIEW_PICTURE"]["ID"]) && $arResult["PREVIEW_PICTURE"]["ID"] > 0 ):?>

							<div class="border-preview-picture">

		    					<img class="preview-picture d-block mx-auto" src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>">

		    				</div>

		    			<?endif;?>

						
					</div>
					
				</div>
			</div>*/?>

			<?if( strlen($arResult["~DETAIL_TEXT"]) ):?>

				<div class="detail-text text-content"><?if( !empty($arResult["PREVIEW_PICTURE"]) && isset($arResult["PREVIEW_PICTURE"]["ID"]) && $arResult["PREVIEW_PICTURE"]["ID"] > 0 ):?><img class="preview-picture d-block mx-auto" src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>">
	    			<?endif;?><?=$arResult["~DETAIL_TEXT"]?>
					
				</div>

			<?endif;?>


		</div>

	</div>

<?endif;?>

<?if( $issetVideoGallery ):?>

	<div class="col-12 order-3">
	
		<div class="block small-block brand-block padding-on">

		    <?if(strlen($arResult["PROPERTIES"]["VIDEO_TITLE"]["VALUE"]) > 0):?>
		        <div class="title-block"><?=$arResult["PROPERTIES"]["VIDEO_TITLE"]["~VALUE"]?></div>
		    <?endif;?>


		    <div class="cart-video">

			    <?foreach($arResult["PROPERTIES"]["VIDEO_LINK"]["VALUE"] as $key=>$video):?>
					
					<div class="cart-video-item row">
	    
	                    <div class="<?if(strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["VALUE"][$key]) > 0 || strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["DESCRIPTION"][$key]) > 0):?>col-lg-8 col-12<?else:?>col-12<?endif;?>">
	                    
	                        <div class="videoframe-wrap <?if(strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["VALUE"][$key]) > 0 || strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["DESCRIPTION"][$key]) > 0):?>right-col<?endif;?>">

	                            <?preg_match("/^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/", $video, $out);?>

	                            <iframe width="100%" height="500" src="https://www.youtube.com/embed/<?=$out[1]?>" frameborder="0" gesture="media" allowfullscreen></iframe>
	                        </div>
	                        
	                    </div>
	                    
	                    
	                    <?if(strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["VALUE"][$key]) > 0 || strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["DESCRIPTION"][$key]) > 0):?>
	                    
	                        <div class="col-lg-4 col-12">
	    
	                            <div class="video-text text-content">
	                                
	                                <?if(strlen(trim($arResult["PROPERTIES"]["VIDEO_DESC"]["VALUE"][$key])) > 0):?>
	                                    <h4><?=$arResult["PROPERTIES"]["VIDEO_DESC"]["VALUE"][$key]?></h4>
	                                <?endif;?>
	    
	                                <?if(strlen($arResult["PROPERTIES"]["VIDEO_DESC"]["DESCRIPTION"][$key]) > 0):?>
	                                    <p><?=$arResult["PROPERTIES"]["VIDEO_DESC"]["DESCRIPTION"][$key]?></p>
	                                <?endif;?>
	                                
	                            </div>
	                            
	                        </div>
	                    
	                    <?endif;?>

	                </div>

			    <?endforeach;?>

			</div>

		</div>

	</div>

<?endif;?>

<?if( $issetPhotoGallery ):?>

	<div class="col-12 order-4">

		<?
			$arWaterMark = Array();

			if($arResult["PROPERTIES"]["GALLERY_WATERMARK"]["VALUE"] > 0)
			{

			    $arWaterMark = Array(
			        array(
			            "name" => "watermark",
			            "position" => "center",
			            "type" => "image",
			            "size" => "real",
			            "file" => $_SERVER["DOCUMENT_ROOT"].CFile::GetPath($arResult["PROPERTIES"]["GALLERY_WATERMARK"]["VALUE"]),
			            "fill" => "exact",
			        )
			    );
			}
		?>
		<div class="block small-block brand-block padding-on">

		    <?if(strlen($arResult["PROPERTIES"]["GALLERY_TITLE"]["VALUE"]) > 0):?>
		        <div class="title-block"><?=$arResult["PROPERTIES"]["GALLERY_TITLE"]["~VALUE"]?></div>
		    <?endif;?>
		   	

		    <div class="gallery-block gallery <?=($arResult["PROPERTIES"]["GALLERY_BORDER"]["VALUE_XML_ID"] == "Y")? "border-img-on": ""?>">

		    	<div class="row">

			    	<?foreach($arResult["PROPERTIES"]["GALLERY_PHOTOS"]["VALUE"] as $k=>$photo):?>

			    		<?$file = CFile::ResizeImageGet($photo, array('width'=>325, 'height'=>325), BX_RESIZE_IMAGE_EXACT, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
			           <?$file_big = CFile::ResizeImageGet($photo, array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arWaterMark, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>

			    		<div class="col-sm-3 col-4">
			    			
			    			<a href="<?=$file_big["src"]?>" data-gallery="brand-gallery-<?=$arResult["ID"]?>" class="cursor-loop" title="<?=$arResult["PROPERTIES"]["GALLERY_PHOTOS"]["~DESCRIPTION"][$k]?>">

			    				<div class="gallery-img middle-size lazyload" data-src="<?=$file["src"]?>">

				                	<div class="corner-line"></div>
					               
					                
					        	</div>                             
					        </a>
					        
			    		</div>


			    	<?endforeach;?>

		    	</div>

		   	</div>
			
		</div>

	</div>
<?endif;?>

<?if( $issetCertificate ):?>
	<div class="col-12 order-5">
		<?
			$arWaterMark = Array();

			if($arResult["PROPERTIES"]["CERTIFICATE_WATERMARK"]["VALUE"] > 0)
			{

			    $arWaterMark = Array(
			        array(
			            "name" => "watermark",
			            "position" => "center",
			            "type" => "image",
			            "size" => "real",
			            "file" => $_SERVER["DOCUMENT_ROOT"].CFile::GetPath($arResult["PROPERTIES"]["CERTIFICATE_WATERMARK"]["VALUE"]),
			            "fill" => "exact",
			        )
			    );
			}
		?>
		<div class="block small-block brand-block padding-on">

		    <?if(strlen($arResult["PROPERTIES"]["CERTIFICATE_TITLE"]["VALUE"]) > 0):?>
		        <div class="title-block"><?=$arResult["PROPERTIES"]["CERTIFICATE_TITLE"]["~VALUE"]?></div>
		    <?endif;?>

		    <div class="gallery-block nogallery

		    <?=($arResult["PROPERTIES"]["CERTIFICATE_BORDER"]["VALUE_XML_ID"] == "Y")? "border-img-on": ""?>

		    ">
		    	<div class="row">

		    	<?foreach($arResult["PROPERTIES"]["CERTIFICATE_PHOTOS"]["VALUE"] as $k=>$photo):?>

		    		<?

		    		$file = CFile::ResizeImageGet($photo, array('width'=>325, 'height'=>325), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, Array(), false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>
		           <?$file_big = CFile::ResizeImageGet($photo, array('width'=>1600, 'height'=>1200), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arWaterMark, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>



		            <div class="col-sm-3 col-6">

		            	<a href="<?=$file_big["src"]?>" data-gallery="brand-sertificate-<?=$arResult["ID"]?>" class="d-block cursor-loop" title="<?=$arResult["PROPERTIES"]["CERTIFICATE_PHOTOS"]["DESCRIPTION"][$k]?>">

					    	<div class="gallery-img middle-size">

				                <table>
				                    <tr>
				                        <td>
				                            <div class="gallery-img-wrap">
								                <div class="corner-line"></div>
				                                
				                                <img class="lazyload" data-src="<?=$file["src"]?>" alt="<?=(strlen($arResult["PROPERTIES"]["CERTIFICATE_PHOTOS"]["DESCRIPTION"][$k]))? $arResult["PROPERTIES"]["CERTIFICATE_PHOTOS"]["DESCRIPTION"][$k]:""?>"/>
				                            </div>
				                        </td>
				                    </tr>
				                </table>
				                
				                <?if(strlen($arResult["PROPERTIES"]["CERTIFICATE_PHOTOS"]["DESCRIPTION"][$k]) > 0 ):?>
				                    <div class="text-img">
				                        <?=$arResult["PROPERTIES"]["CERTIFICATE_PHOTOS"]["DESCRIPTION"][$k]?>
				                    </div>
				                <?endif;?>
				            </div>
				        </a>

			        </div>

		    	<?endforeach;?>

		    	</div>
		    </div>


	    </div>

	</div>
<?endif;?>

<?if($showContent):?>
	<script>
		$(document).ready(function()
		{
			$(".catalog-list-wrap").removeClass('d-none');
			<?if($issetBrandCatalog):?>
				$(".brand-goods-isset-js").removeClass('brand-goods-isset-js');
				$(".brand-goods-isset-xs-js").removeClass('brand-goods-isset-xs-js');
			<?endif;?>
		});
	</script>
<?endif;?>