<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $PHOENIX_TEMPLATE_ARRAY;

CPhoenix::setMess(array("region"));
?>

<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['SHOW_APPLY']["VALUE"]["ACTIVE"] == "Y" && !empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"])):?>

	<div class="popup-block active bot animated region-apply d-none" data-animate-out="fadeOutDown" data-popup = "region-apply">


		<div class="bg-shadow-bottom"></div>
		
	    <div class="container popup-block-inner">


	        <a class="hide-popup-block hide-popup-block-js set-first-region-values" onclick="setCookieRegion('<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["CURRENT_ID"]?>');"></a>


	        <?

	        	$bgCustom = "";

	        	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PICTURE_SRC"])>0)
	        		$bgCustom = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PICTURE_SRC"];

	        	else if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['BG']["SRC"])>0)
	        		$bgCustom = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['BG']["SRC"];

	        	

	        	$bgCustomSmall = $bgCustom;

	        	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PICTURE_SMALL_SRC"])>0)
	        		$bgCustomSmall = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["PICTURE_SMALL_SRC"];

	        	else if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['BG_SMALL']["SRC"])>0)
	        		$bgCustomSmall = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['BG_SMALL']["SRC"];

	        ?>

	        <div class="popup-xs-flat row no-gutters align-items-center">

	        	<div class="img background-default d-sm-none" <?if(strlen($bgCustom)):?>style="background-image: url(<?=$bgCustom?>);"<?endif;?>></div>

	        	<div class="img background-default d-none d-md-block" <?if(strlen($bgCustomSmall)):?>style="background-image: url(<?=$bgCustomSmall?>);"<?endif;?>></div>

	        	<div class="col-auto d-none d-md-flex"><div class="img-ghost"></div></div>

	        	<div class="shadow-tone dark d-md-none"></div>

	        	<div class="col">
	        		<div class="text-side row no-margin">
			        	<div class="col-lg-7 col-12 left-col align-self-center">
		        			<div class="title main1"><span class="description"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REGION"]["CURRENT"]?><span class="d-none d-md-inline-block">:&nbsp;</span></span><span class="value value-region"><?=strip_tags($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["~NAME"])?></span></div>
		        			
		        			<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['COMMENT_FOR_APPLY']["VALUE"])>0):?>
		        				<div class="subtitle d-none d-md-block"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['COMMENT_FOR_APPLY']["~VALUE"]?></div>
		        			<?endif;?>

			        	</div>
			        	<div class="col-lg-5 col-12 align-self-center">
			        		<div class="row">
			        			<div class="col-md-6 col-12 btn-left">
				        			<a class="main-color button-def round-sq d-block hide-popup-block-js" onclick="setCookieRegion('<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["APPLY_REGION"]["CURRENT_ID"]?>', '<?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["SUBMIT"])?'Y':''?>');"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REGION"]["OK"]?></a>
				        		</div>
				        		<div class="col-md-6 col-12 btn-right">
				        			<a class="secondary button-def round-sq d-block show-popup-block" data-popup="region-form" data-hide-container=".region-apply"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REGION"]["CHANGE"]?></a>
				        		</div>
			        		</div>
			        	</div>
			        	<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['COMMENT_FOR_APPLY']["VALUE"])>0):?>
				        	<div class="col-12 d-md-none align-self-end">
				        		<div class="subtitle"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['COMMENT_FOR_APPLY']["~VALUE"]?></div>
				        	</div>
			        	<?endif;?>
		        	</div>
	        	</div>
	        </div>

	    </div>
	</div>

<?endif;?>



<script>

  $( function() {

    $( ".autocompleteRegion" ).autocomplete({
    	minLength: 2,
		source: "/bitrix/tools/concept.phoenix/ajax/region_list.php",
		appendTo: ".autocompleteRegionArea",
		autoFocus: true,
		select: function( event, ui ) {
			autocompleteRegionAreaSelected($(this), ui);
	        return false;
		},
		change: function( event, ui ) {
			autocompleteRegionAreaChange($(this), ui.item);
		},
		response: function( event, ui ) {
			if(!ui.content)
				$(".region-popup .error-input").removeClass('d-none');
			else
			{
				$(".region-popup .error-input").addClass('d-none');
			}
		},
    });

    ShowApplyRegion();


  } );
</script>


<?$imagesRegions = "";?>

<div class="popup-block region-popup d-none" data-popup = "region-form">
	<div class="popup-shadow-tone hide-popup-block-js"></div>
	
    <div class="container popup-block-inner">

        <a class="hide-popup-block hide-popup-block-js set-first-region-values" onclick="setCookieRegion('<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["CURRENT_ID"]?>');"></a>

        <div class="row no-gutters h-100">

        	<div class="col-md-6 left-side background-default d-none d-md-flex" <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PICTURE_SRC"])):?>style="background-image: url(<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PICTURE_SRC"]?>);"<?endif;?>>
        		<div class="shadow-tone-top"></div>

        		<div class="position-relative title"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REGION"]["CURRENT"]?>:&nbsp;<span class="bold value-region"><?=strip_tags($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["~NAME"])?></span></div>

        	</div>

        	<div class="col-md-6 col-12 right-side background-default" <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PICTURE_SRC"])):?>style="background-image: url(<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PICTURE_SRC"]?>);"<?endif;?>>
        		<div class="shadow-tone dark d-md-none"></div>

        		<div class="row h-100">
	        		<div class="col-12 wr-line lg-big">
		        		<form action="/" method="post" role="form">
		        			<input type="hidden" class="region-id" name="region-id" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["CURRENT_ID"]?>">


						    <input type="hidden" class="region-id-default" name="region-default" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["CURRENT_ID"]?>">
						    <input type="hidden" class="region-name-default" name="region-name-default" value="<?=strip_tags($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["~NAME"])?>">
						    <input type="hidden" class="region-image-default" name="region-image-default" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PICTURE_SRC"]?>">
						    



						    <input type="hidden" class="region-name" name="region-name" value="<?=strip_tags($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["~NAME"])?>">
						    <input type="hidden" class="region-image" name="region-image" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["PICTURE_SRC"]?>">
						    <input type="hidden" class="region-id-current" name="region-id-current" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["CURRENT_ID"]?>">
						    
						    
		        			<div class="title d-md-none"><div class="description"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REGION"]["CURRENT"]?></div><div class="value bold value-region"><?=strip_tags($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["~NAME"])?></div></div>

		        			<div class="row">

		        				<?if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGIONS"]["FAVORITES"])):?>

			        				<div class="col-12 order-md-1 order-2">

					        			<div class="section-form big main1 d-none d-md-block"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REGION"]["CHOOSE_TITLE"]?></div>

					        			<div class="row wr-line lg-big wr-flat-xs">

					        				<?foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGIONS"]["FAVORITES"] as $region):?>

					        					<?
					        						$arRegionsValues = explode(";",$region["value"]);
					        						$regionID = $arRegionsValues[0];
					        						$regionHref = $arRegionsValues[1];

					        						if(strlen($region["icon"])>0)
					        							$imagesRegions .= "<img class='d-none' src='".$region["icon"]."' alt=''>";
					        					?>

						        				<div class="col-md-6 col-auto wr-line">
						        					<label class="input-radio-css green-check no-border flat-xs">
								        				<input type="radio" name="region-value" value="<?=$regionID?>" <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CURRENT_REGION"]["CURRENT_ID"]==$regionID):?>checked<?endif;?> data-label="<?=$region["label"]?>" data-icon = "<?=$region["icon"]?>">
								        				<span></span>
								        				<span class="text"><?=$region["label"]?></span>
								        			</label>
						        				</div>
						        				
					        				<?endforeach;?>
					        			</div>

				        			</div>

			        			<?endif;?>

			        			<?//if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGIONS"]["OTHER"])):?>

				        			<div class="col-12 order-md-2 order-1">

					        			<div class="section-form min d-none d-md-block"><?=(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGIONS"]["FAVORITES"]))?$PHOENIX_TEMPLATE_ARRAY["MESS"]["REGION"]["INPUT_CITY_TITLE"]:$PHOENIX_TEMPLATE_ARRAY["MESS"]["REGION"]["INPUT_CITY_TITLE_2"]?></div>

					        			<div class="input-simple mode-select ic-search ic-status inp-small no-desc wr-line big">
					        				<span class="ic-search"></span>
					        				<span class="ic-status"></span>
					        				<input type="text" value="" class="autocompleteRegion b-ra" onkeydown="if(event.keyCode==13){return false;}">
					        				<div class="circleG-area">
					        					<div class="circleG circleG_1"></div>
					        					<div class="circleG circleG_2"></div>
					        					<div class="circleG circleG_3"></div>
					        				</div>
					        				<div class="autocompleteRegionArea"></div>
					        				
					        			</div>

					        			<div class="error-input d-none bold">
				        					<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REGION"]["ERROR_INPUT"]?>
				        				</div>
					        			
					        		</div>
				        		<?//endif;?>

		        			</div>

		        			<div class="wrapper-btn position-relative">
		        				
                               <div class="circleG-area submit-region-load">
		        					<div class="circleG circleG_1"></div>
		        					<div class="circleG circleG_2"></div>
		        					<div class="circleG circleG_3"></div>
		        				</div>
                                
                                <a class="main-color button-def big shine d-block <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> submit-region"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REGION"]["BTN_SUBMIT"]?></a>
		        			</div>

		        			

		        		</form>
	        		</div>

	        		<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["COMMENT_FOR_FORM"]["VALUE"])>0):?>

	        			<div class="col-12 align-self-end">

			        		<div class="dashed-comment">
			        			<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["COMMENT_FOR_FORM"]["~VALUE"]?>
			        		</div>

			        	</div>

	        		<?endif;?>
        		</div>
        		
        	</div>

        </div>

    </div>
</div>

<?if(strlen($imagesRegions)>0):?>
	<script>
		setTimeout(function() {
	        $('div.popup-block').append("<?=$imagesRegions?>");
	    }, 12000);
		
	</script>
<?endif;?>
