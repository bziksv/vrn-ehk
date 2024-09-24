<?if( (!empty($arSlider["ELEMENTS_LG"]) && is_array($arSlider["ELEMENTS_LG"])) || (!empty($arSlider["ELEMENTS_XS"]) && is_array($arSlider["ELEMENTS_XS"])) ):?>
<?
    global $h1_used;
    $h1_used = false;
?>
<?function initSliderPhoenixOnePageGenerator($__arSlider, $__device)
{?>

<?
    global $PHOENIX_TEMPLATE_ARRAY, $h1_used;

	if($__device == "lg")
	{
		$deviceClass = "hidden-sm hidden-xs slider-lg";
		$deviceProp = "START_DESKTOP";
		$forArName = "ELEMENTS_LG";
		$mediaCss = "min-width: 768px";
		$postfixCss = "";
	}
	else
	{
		$deviceClass = "visible-sm visible-xs slider-xs";
		$deviceProp = "START_MOBILE";
		$forArName = "ELEMENTS_XS";
		$mediaCss = "max-width: 767px";
		$postfixCss = "_MOB";
	}

    
?>

<?if(!empty($__arSlider[$forArName]) && is_array($__arSlider[$forArName])):?>
	
	<div class="wrap-first-slider <?=$deviceClass?>"
	    data-full-height = "<?=( ($__arSlider[$deviceProp]["PROPERTIES"]["FB_HEIGHT_WINDOW"]["VALUE"] == "Y") ? "Y" : "")?>"
	    data-autoslide = "<?=( ($__arSlider[$deviceProp]["PROPERTIES"]["FB_SLIDER_TIME"]["VALUE"] > 0) ? "Y" : "")?>"
        data-autoslide-time = "<?=intval($__arSlider[$deviceProp]["PROPERTIES"]["FB_SLIDER_TIME"]["VALUE"])*1000?>"
    >
		<?
			foreach($__arSlider[$forArName] as $k => $arItem)
			{
				if(strlen($arItem["PROPERTIES"]["MARGIN_TOP".$postfixCss]["VALUE"])
					|| strlen($arItem["PROPERTIES"]["MARGIN_BOTTOM".$postfixCss]["VALUE"])
	    			|| strlen($arItem["PROPERTIES"]["PADDING_TOP".$postfixCss]["VALUE"])
	    			|| strlen($arItem["PROPERTIES"]["PADDING_BOTTOM".$postfixCss]["VALUE"])
	    			|| strlen($arItem["PROPERTIES"]["TITLE_SIZE".$postfixCss]["VALUE"])
	    			|| strlen($arItem["PROPERTIES"]["SUBTITLE_SIZE".$postfixCss]["VALUE"]))
				{
	    			$html_style .= "@media (".$mediaCss."){";


	    				if(strlen($arItem["PROPERTIES"]["MARGIN_TOP".$postfixCss]["VALUE"])
		    				|| strlen($arItem["PROPERTIES"]["MARGIN_BOTTOM".$postfixCss]["VALUE"])
			    			|| strlen($arItem["PROPERTIES"]["PADDING_TOP".$postfixCss]["VALUE"])
			    			|| strlen($arItem["PROPERTIES"]["PADDING_BOTTOM".$postfixCss]["VALUE"]))
	    				{

			    			$html_style .= ".first_slider_".$arItem["ID"]."{";

			    				if(strlen($arItem["PROPERTIES"]["MARGIN_TOP".$postfixCss]["VALUE"]))
			    					$html_style .= "margin-top:".$arItem["PROPERTIES"]["MARGIN_TOP".$postfixCss]["VALUE"]."px !important;";

			    				if(strlen($arItem["PROPERTIES"]["MARGIN_BOTTOM".$postfixCss]["VALUE"]))
			    					$html_style .= "margin-bottom:".$arItem["PROPERTIES"]["MARGIN_BOTTOM".$postfixCss]["VALUE"]."px !important;";

			    				if(strlen($arItem["PROPERTIES"]["PADDING_TOP".$postfixCss]["VALUE"]))
			    					$html_style .= "padding-top:".$arItem["PROPERTIES"]["PADDING_TOP".$postfixCss]["VALUE"]."px !important;";

			    				if(strlen($arItem["PROPERTIES"]["PADDING_BOTTOM".$postfixCss]["VALUE"]))
			    					$html_style .= "padding-bottom:".$arItem["PROPERTIES"]["PADDING_BOTTOM".$postfixCss]["VALUE"]."px !important;";

			    			$html_style .= "}";

		    			}


		    			if(strlen($arItem["PROPERTIES"]["TITLE_SIZE".$postfixCss]["VALUE"]))
		    			{

		    				$html_style .= ".first_slider_".$arItem["ID"]." div.head div.title, .first_slider_".$arItem["ID"]." div.head h1, .first_slider_".$arItem["ID"]." div.head h2";

		    				$html_style .= "{";


		    					$html_style .= "font-size:".$arItem["PROPERTIES"]["TITLE_SIZE".$postfixCss]["VALUE"]."px !important;";

		    					if(strlen($arItem["PROPERTIES"]["TITLE_SIZE".$postfixCss]["DESCRIPTION"]))
		    						$html_style .= "line-height:".$arItem["PROPERTIES"]["TITLE_SIZE".$postfixCss]["DESCRIPTION"]."px !important;";
		    					else
		    						$html_style .= "line-height:".(intval($arItem["PROPERTIES"]["TITLE_SIZE".$postfixCss]["VALUE"]) + 5)."px !important;";
			    				
		    				$html_style .= "}";

		    			}

		    			if(strlen($arItem["PROPERTIES"]["SUBTITLE_SIZE".$postfixCss]["VALUE"]))
		    			{

		    				$html_style .= ".first_slider_".$arItem["ID"]." div.head .subtitle";

		    				$html_style .= "{";

		    					$html_style .= "font-size:".$arItem["PROPERTIES"]["SUBTITLE_SIZE".$postfixCss]["VALUE"]."px !important;";

		    					if(strlen($arItem["PROPERTIES"]["SUBTITLE_SIZE".$postfixCss]["DESCRIPTION"]))
		    						$html_style .= "line-height:".$arItem["PROPERTIES"]["SUBTITLE_SIZE".$postfixCss]["DESCRIPTION"]."px !important;";
		    					else
		    						$html_style .= "line-height:".(intval($arItem["PROPERTIES"]["SUBTITLE_SIZE".$postfixCss]["VALUE"]) + 5)."px !important;";
			    				
		    				$html_style .= "}";

		    			}


	    			$html_style .= "}";

				}

			}

			if(strlen($html_style))
				echo "<style>".$html_style."</style>";

			$html_style = "";
		?>

	    <?if(!$PHOENIX_TEMPLATE_ARRAY["HEADER_BG"]):?><div class="top-shadow"></div><?endif;?>

	    

	    <div class="first-slider <?=$deviceClass?> item-block parent-slider-item-js" id="block<?=$__arSlider["ID"]?>">

	        <?$countSlider = count($__arSlider[$forArName]);?>

	    
            <?foreach($__arSlider[$forArName] as $k => $arItem):?>

                <?
                    $block_name = $arItem['~NAME'];

                    if(strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) > 0)
                        $block_name .= " (".$arItem["PROPERTIES"]["HEADER"]["~VALUE"].")";

                    $block_name = htmlspecialcharsEx(strip_tags(html_entity_decode($block_name)));

                    if($arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "")
                   		$arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] = "easy";
                ?>

              
                <?$style_bg = '';?>


                <?if(strlen($arItem["PREVIEW_PICTURE"]["SRC"])>0):?>


                <?elseif($arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "easy" && $arItem["PROPERTIES"]["FB_CLICK_DOWN"]["VALUE"] == "Y"):?>
                    <?$style_bg .= "background-size: cover;";?>
                <?endif;?>

                
                
                <?
                    $animate = '';
    		        $animate_set = '';

		        	if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y")
		        	{
		        		$animate = 'wow fadeIn';
		            	$animate_set = 'data-wow-offset="250" data-wow-duration="0.5s" data-wow-delay="0.2s"';
		        	}


                    if( $arItem["PROPERTIES"]["FB_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "" )
                        $arItem["PROPERTIES"]["FB_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] = "order-1";

                    if($arItem["PROPERTIES"]["FB_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"] == "")
                        $arItem["PROPERTIES"]["FB_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"] = "order-md-1";


                    if($arItem["PROPERTIES"]["FB_IMAGE_POSITION"]["VALUE_XML_ID"] == "")
                        $arItem["PROPERTIES"]["FB_IMAGE_POSITION"]["VALUE_XML_ID"] = "middle";

                    $position_vert = $arItem["PROPERTIES"]["FB_IMAGE_POSITION"]["VALUE_XML_ID"]; 

                    if($position_vert == "top")
                        $position_vert = "align-self-start";

                    if($position_vert == "middle")
                        $position_vert = "align-self-center";

                    if($position_vert == "bottom")
                        $position_vert = "align-self-end";


                    $class_cols='col-12';
                    $class_padding_left='';

               

                    if($arItem["TWO_COLS"] == "Y")
                    {
                        $class_cols='col-lg-7 col-md-8 col-12 two-cols';

                        if($arItem["PROPERTIES"]["FB_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"] == "order-md-1")
                        {
                            $class_cols = 'col-lg-7 col-md-8 col-12 two-cols right';
                            $class_padding_left='wrap-padding-left';
                        }
                    }

                    elseif($arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "easy" && $arItem["PROPERTIES"]["FB_CLICK_DOWN"]["VALUE"] == "Y")
                    {
                        $class_cols='col-md-9 col-12';
                    }

                    if($arItem["PROPERTIES"]["FB_TEXT_COLOR"]["VALUE_XML_ID"] == "")
                    	$arItem["PROPERTIES"]["FB_TEXT_COLOR"]["VALUE_XML_ID"] = "dark";

                    $class_block = '';

                    if(
                        $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"] > 0
                        ||
                        $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"] > 0
                        ||
                        $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"] > 0
                        ||
                        strlen($arItem["PROPERTIES"]["VIDEO_BACKGROUND"]["VALUE"]) > 0
                    )
                    {
                        $class_block .= " parent-video-bg"; 

                        if($arItem["PROPERTIES"]["VIDEO_BACKGROUND_HIDE_PICTURE"]["VALUE"] === 'Y')
                            $class_block .= " hide-bg-img-lg"; 
                    }
		        ?>
            
                <div id="first_slider_<?=$arItem["ID"]?>" 
                	class="
                		<?=$class_block?>
                		first_slider_<?=$arItem["ID"]?>
                        first_slider_<?=$postfixCss?>_<?=$arItem["ID"]?>
                        first-block

                         <?=(strlen($arItem["PROPERTIES"]["CLASS_BLOCK"]["VALUE"])>0)?$arItem["PROPERTIES"]["CLASS_BLOCK"]["VALUE"].$postfixCss:"";?>
                		
                		<?if($k!=0) echo 'noactive-slide-lazyload';?>
                		view-<?=$arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"]?>
                		phoenix-firsttype-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
                		<?=$arItem["PROPERTIES"]["SHADOW"]["VALUE_XML_ID"]?> <?=$arItem["PROPERTIES"]["COVER"]["VALUE_XML_ID"]?>
                        <?=($arItem["TWO_COLS"] == "Y")? "two-cols":"one-col";?>
                        "

                style="<?=$style_bg?> <?if(strlen($arItem["PROPERTIES"]["BACKGROUND_COLOR"]["VALUE"]) > 0):?>background-color: <?=$arItem["PROPERTIES"]["BACKGROUND_COLOR"]["VALUE"]?>;<?endif;?> <?if(strlen($arItem["PREVIEW_PICTURE"]["SRC"])>0):?>background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>');<?endif;?>" 


                >

                    <?if(is_array($arItem["PROPERTIES"]["SLIDES"]["VALUE_XML_ID"]) && !empty($arItem["PROPERTIES"]["SLIDES"]["VALUE_XML_ID"])):?>
        
                        <?foreach($arItem["PROPERTIES"]["SLIDES"]["VALUE_XML_ID"] as $arSlID):?>
                            <?if($arSlID == 'top tb' || $arSlID == 'top bt') continue;?>
                            <div class="corner <?=$arSlID?> hidden-xs hidden-sm"></div>
                        <?endforeach;?>
                            
                    <?endif;?>

                    <?CPhoenix::admin_setting($arItem, true)?>

                    <?if(
                        $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"] > 0
                        ||
                        $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"] > 0
                        ||
                        $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"] > 0
                        ||
                        strlen($arItem["PROPERTIES"]["VIDEO_BACKGROUND"]["VALUE"]) > 0
                    ):?>

                        <?

                            if(
                                $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"] > 0
                                ||
                                $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"] > 0
                                ||
                                $arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"] > 0
                            )
                            {
                                $iframeType = "file";

                                if($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"])
                                    $srcMP4 = CFile::GetPath($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_MP4"]["VALUE"]);

                                if($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"])
                                    $srcWEBM = CFile::GetPath($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_WEBM"]["VALUE"]);

                                if($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"])
                                    $srcOGG = CFile::GetPath($arItem["PROPERTIES"]["VIDEO_BACKGROUND_FILE_OGG"]["VALUE"]);
                            }

                            
                            elseif(strlen($arItem["PROPERTIES"]["VIDEO_BACKGROUND"]["VALUE"]) > 0)
                            {
                                $iframeType = "iframe";

                                $srcYB = CPhoenix::createVideo($arItem['PROPERTIES']['VIDEO_BACKGROUND']['~VALUE']);

                            }
                        ?>

                        <div 
                            class="videoBG hidden-sm hidden-xs"
                            data-type = "<?=$iframeType?>"

                            <?if(strlen($srcYB["SRC"])>0):?>
                                data-srcYB = "<?=$srcYB["SRC"]?>"
                            <?endif;?>

                            <?if(strlen($srcMP4)):?>
                                data-srcMP4 = "<?=$srcMP4?>"
                            <?endif;?>

                            <?if(strlen($srcWEBM)):?>
                                data-srcWEBM = "<?=$srcWEBM?>"
                            <?endif;?>

                            <?if(strlen($srcOGG)):?>
                                data-srcOGG = "<?=$srcOGG?>"
                            <?endif;?>
                        >
                        </div>

                        <img class="lazyload img-for-lazyload videoBG-start-fb" data-src="<?=SITE_TEMPLATE_PATH?>/images/one_px.png">

                    <?endif;?>
                    
                    <div class="shadow-tone"></div>
                
                    <div class="container">

                        <div class="first-block-container <?=$arItem["PROPERTIES"]["FB_TEXT_COLOR"]["VALUE_XML_ID"]?> row">
        
                            <div class="first-block-cell text-part <?=$class_cols?><?if($arItem["PROPERTIES"]["FB_CLICK_DOWN"]["VALUE"] == "Y" && $arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] != "easy"):?> scrollnext<?endif;?> order-2">
                            
                                <div class="<?=$class_padding_left?>">

                                    <div class="
                                    	head
                                    	<?if($arItem["TWO_COLS"] == "Y" || ($arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "easy" && $arItem["PROPERTIES"]["FB_CLICK_DOWN"]["VALUE"] == "Y")):?>
                                    		min left
                                    	<?endif;?>

                                		<?=$arItem["PROPERTIES"]["TITLE_SHADOW"]["VALUE_XML_ID"]?>
                                		<?=$animate;?>
                                		<?=$arItem["PROPERTIES"]["SUBTITLE_SHADOW"]["VALUE_XML_ID"]?>
                                		<?=$arItem["PROPERTIES"]["MAIN_TITLE_POS"]["VALUE_XML_ID"]?>
                                        <?=$arItem["PROPERTIES"]["MAIN_TITLE_POS_MOB"]["VALUE_XML_ID"]?>"
                                	>

                                        <?if(strlen($arItem["PROPERTIES"]["SUPHEADER"]["VALUE"]) > 0):?>
                                            <div class="supheader txt-color-<?=$arItem["PROPERTIES"]["HEADER_COLOR"]["VALUE_XML_ID"]?>"><?=$arItem["PROPERTIES"]["SUPHEADER"]["~VALUE"]?></div>
                                        <?endif;?>
                                        
                                        <?if(strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) > 0):?>
                                        
                                            <?$tit = Array();?>
                                            <?$title = Array();?>
                                        
                                            <?
                                                if(substr_count($arItem["PROPERTIES"]["HEADER"]["VALUE"], "{") > 0 && substr_count($arItem["PROPERTIES"]["HEADER"]["VALUE"], "}") > 0)
                                                {
                                                    $tit = explode("{", $arItem["PROPERTIES"]["HEADER"]["VALUE"]);
                                                    $title[] = $tit[0];
                                                    $tit = $tit[1];
                                                    
                                                    
                                                    $tit = explode("}", $tit);
                                                    $title[] = $tit[1];
                                                    $tit = $tit[0];
                                                    
                                                    
                                                    $tit = explode("|", $tit);
                                                    
                                                }
                                            ?>
                                        
                                        
                                            <div class="title main1 <?=$arItem["PROPERTIES"]["HEADER_COLOR"]["VALUE_XML_ID"]?>" <?if(strlen($arItem["PROPERTIES"]["TITLE_COLOR"]["VALUE"])>0):?> style="color: <?=$arItem["PROPERTIES"]["TITLE_COLOR"]["VALUE"]?>;"<?endif;?>>



                                                <?$h1_close = 0;?>
                                                
                                                <?if($arItem["PROPERTIES"]["THIS_H1"]["VALUE"] == "Y" && $h1 == 0 && isset($arItem["H1_MAIN"]) && !$h1_used):?>
                                                    <h1>
                                                    
                                                    <?
                                                        $h1 = 1;
                                                        $h1_used = 1;
                                                        $h1_close = 1;
                                                    ?>
                                                <?endif;?>
                                            
                                                <?if(!empty($tit)):?>
                                                    <?=htmlspecialcharsBack($title[0])?><span class="typed"></span><?=htmlspecialcharsBack($title[1])?>
                                                <?else:?>
                                                    <?=$arItem["PROPERTIES"]["HEADER"]["~VALUE"]?>
                                                <?endif;?>
                                                
                                                <?if($arItem["PROPERTIES"]["THIS_H1"]["VALUE"] == "Y" && $h1_close == 1):?>
                                                    </h1>
                                                <?endif;?>
                                            
                                                                                                
                                            </div>
                                            
                                            <?if(!empty($tit)):?>

                                            <script>
                                                $(document).ready(
                                                        function(){
                                                        $("div.first_slider_<?=$postfixCss?>_<?=$arItem["ID"]?> div.title span.typed").typed({
                                                            strings: ["<?=implode('","', $tit)?>"],
                                                            typeSpeed: 50,
                                                            startDelay: 3000,
                                                            backDelay: 2000,
                                                        });
                                                    });

                                            </script>
                                            
                                            <?endif;?>
                                        
                                        <?endif;?>
                                        
                                        <?if(strlen($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"]) > 0):?>
                                        
                                            
                                            <?$tit = Array();?>
                                            <?$title = Array();?>
                                        
                                            <?
                                            if(substr_count($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"], "{") > 0 && substr_count($arItem["PROPERTIES"]["SUBHEADER"]["VALUE"], "}") > 0)
                                            {
                                                $tit = explode("{", $arItem["PROPERTIES"]["SUBHEADER"]["VALUE"]);
                                                $title[] = $tit[0];
                                                $tit = $tit[1];
                                                
                                                
                                                $tit = explode("}", $tit);
                                                $title[] = $tit[1];
                                                $tit = $tit[0];
                                                
                                                
                                                $tit = explode("|", $tit);
                                                
                                            }
                                            ?>
                                        
                                            <div class="subtitle <?=$arItem["PROPERTIES"]["HEADER_COLOR"]["VALUE_XML_ID"]?>" <?if(strlen($arItem["PROPERTIES"]["SUBTITLE_COLOR"]["VALUE"])>0):?> style="color: <?=$arItem["PROPERTIES"]["SUBTITLE_COLOR"]["VALUE"]?>;"<?endif;?>>
                                            
                                                <?if(!empty($tit)):?>
                                                    <?=htmlspecialcharsBack($title[0])?><span class="typed"></span><?=htmlspecialcharsBack($title[1])?>
                                                <?else:?>
                                                    <?=$arItem["PROPERTIES"]["SUBHEADER"]["~VALUE"]?>
                                                <?endif;?>
                                            
                                            </div>
                                            
                                            <?if(!empty($tit)):?>

                                                <script>

                                                   
                                                    $(document).ready(
                                                            function(){
                                                            $("div.first_slider_<?=$postfixCss?>_<?=$arItem["ID"]?> div.subtitle span.typed").typed({
                                                                strings: ["<?=implode('","', $tit)?>"],
                                                                typeSpeed: 50,
                                                                startDelay: 3000,
                                                                backDelay: 2000,
                                                            });
                                                        });

                                                </script>

                                                <?$arResult["PPC"] = "123123DSADSAASD";?>
                                            
                                            <?endif;?>
                                            
                                        <?endif;?>

                                    </div>
                                        
                                    <?if( ($arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "icons" || $arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "mixed") && strlen($arItem["ICONS_MAX"])):?>

                                    	<?
                                    		$classIcons = ($arItem["TWO_COLS"] == "Y") ? "list" : "flat";

                                    		if($arItem["TWO_COLS"] == "Y")
                                    			$classIconsElement = "col-sm-6 col-12";
                                    		else
                                    			$classIconsElement = ($arItem["ICONS_MAX"] <= 3) ? "col-sm-4 col-12" : "col-lg-3 col-sm-4 col-12";

                                    	?>


                                    	<div class="
                                    			icons-block
                                    			<?=$classIcons?>
                                    			<?=$arItem["PROPERTIES"]["FB_TEXT_COLOR"]["VALUE_XML_ID"]?>
	                                    		row
	                                    		<?echo ($arItem["TWO_COLS"] == "Y")?"":"justify-content-center";?>
	                                    		<?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>parent-animate<?endif;?>"
	                                    >


	                                    	<?for($i = 0; $i < $arItem["ICONS_MAX"]; $i++):?>
	                                    		<?/*if($i > 3) continue;*/?>

	                                    		<div class="<?=$classIconsElement?>">

		                                    		<div class="
		                                    				element
		                                    				row
		                                    				<?if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y"):?>
		                                    					child-animate opacity-zero
		                                    				<?endif;?>"
		                                    		>

		                                    			<?if($arItem["ICONS_COUNT"] > 0):?>

			                                    			<div class="
			                                    				col-sm-12 
			                                    				<?if($arItem["PROPERTIES"]["FB_ICONS"]["VALUE"][$i] > 0):?>
			                                    					col-3
			                                    				<?else:?>
			                                    					hidden-xs
			                                    				<?endif;?>">
			                                    				<div class="img-area row">
			                                    				
		                                    						<?if($arItem["PROPERTIES"]["FB_ICONS"]["VALUE"][$i] > 0):?>
				                                    					<?$img = CFile::ResizeImageGet($arItem["PROPERTIES"]["FB_ICONS"]["VALUE"][$i], array('width'=>200, 'height'=>70), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);?>

				                                    						<div class="col-12 align-self-center">

				                                    							<img class="img-fluid" src="<?=$img["src"]?>" alt="<?=(strlen($arItem["PROPERTIES"]["FB_ICONS"]["DESCRIPTION"][$i]))? $arItem["PROPERTIES"]["FB_ICONS"]["DESCRIPTION"][$i]:"";?>"/>

				                                    						</div>

				                                    					

				                                    					<?$img = "";?>

			                                    					<?endif;?>

		                                    					</div>

		                                    				</div>
		                                    			<?endif;?>

		                                    			<?if($arItem["ICONS_DESC_COUNT"] > 0):?>

		                                    				<div class="
                                                                    align-self-center
		                                    						text-area
		                                    						<?if($arItem["PROPERTIES"]["FB_ICONS"]["VALUE"][$i] > 0):?>
			                                    						col-sm-12 col-9
				                                    				<?else:?>
				                                    					col-12
				                                    				<?endif;?>
		                                    						">
		                                    					<?=$arItem["PROPERTIES"]["FB_ICONS_DESC"]["~VALUE"][$i]?>
		                                    				</div>

		                                    			<?endif;?>

		                                    		</div>

	                                    		</div>

	                                    	<?endfor;?>

                                    	</div>

                                        
                                    <?endif;?>

                                    <?if($arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "buttons" 
                                    	|| $arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "mixed"):?>

                                        <div class="
                                        	buttons-block
                                        	row
                                        	<?echo ($arItem["TWO_COLS"] == "Y")? "with-image" : "without-image justify-content-center" ;?>
 

	                                        <?/*if(strlen($arItem["PROPERTIES"]["FB_LB_NAME"]["VALUE"])>0):?> left-button-on<?endif;?><?if(strlen($arItem["PROPERTIES"]["FB_RB_NAME"]["VALUE"])>0):?> right-button-on<?endif;?><?if(strlen($arItem["PROPERTIES"]["FB_VIDEO_LINK"]["VALUE"])>0):?> video-button-on<?endif;?> <?=$arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"]*/?>"
	                                    >
                                            
                                            <?/*if($arItem["TWO_COLS"] != "Y"):?>

                                                <?if($arItem["BUTTONS_COUNT"] == 2):?>
                                                    <div class="col-xl-2 col-md-0 col-12"></div>
                                                <?endif;?>

                                            <?endif;*/?>
                                        
                                            <?if($arItem["PROPERTIES"]["FB_LB_TYPE"]["VALUE_XML_ID"] == ""):?>
                                                <?$arItem["PROPERTIES"]["FB_LB_TYPE"]["VALUE_XML_ID"] = "form";?>
                                            <?endif;?>
                                        
                                            <?if(strlen($arItem["PROPERTIES"]["FB_LB_NAME"]["VALUE"])):?>
                                            
                                                <div class="
                                                	<?if($arItem["TWO_COLS"] == "Y"):?>
                                                		col-sm-6 col-12
                                                	<?else:?>
                                                		col-md-4 col-sm-6 col-12
	                                                <?endif;?>"
                                               	>
                                                    <div class="button left">

                                                        <?
                                                            $form_id = "";
                                                            if($arItem["PROPERTIES"]["FB_LB_FORM"]["VALUE"] > 0)
                                                                $form_id = $arItem["PROPERTIES"]["FB_LB_FORM"]["VALUE"];

                                                            $product_id = "";

															if($arItem["PROPERTIES"]["FB_LB_OFFER_ID"]["VALUE"])
																$product_id = $arItem["PROPERTIES"]["FB_LB_OFFER_ID"]["VALUE"];

															else if($arItem["PROPERTIES"]["FB_LB_CATALOG_ID"]["VALUE"])
																$product_id = $arItem["PROPERTIES"]["FB_LB_CATALOG_ID"]["VALUE"];


                                                            $arClass = array();
                                                            $arClass=array(
                                                                "XML_ID"=> $arItem["PROPERTIES"]["FB_LB_TYPE"]["VALUE_XML_ID"],
                                                                "FORM_ID"=> $form_id,
                                                                "MODAL_ID"=> $arItem["PROPERTIES"]["FB_LB_MODAL"]["VALUE"],
                                                                "QUIZ_ID"=> $arItem["PROPERTIES"]["FB_LB_BUTTON_QUIZ"]["VALUE"],
                                                                "FAST_ORDER_PRODUCT_ID" => $product_id,
                                                                "ADD2CART_PRODUCT_ID" => $product_id
                                                            );

                                                            $arAttr=array();
                                                            $arAttr=array(
                                                                "XML_ID"=> $arItem["PROPERTIES"]["FB_LB_TYPE"]["VALUE_XML_ID"],
                                                                "FORM_ID"=> $form_id,
                                                                "MODAL_ID"=> $arItem["PROPERTIES"]["FB_LB_MODAL"]["VALUE"],
                                                                "LINK"=> $arItem["PROPERTIES"]["FB_LB_LINK"]["VALUE"],
                                                                "BLANK"=> $arItem["PROPERTIES"]["FB_LB_BUTTON_BLANK"]["VALUE_XML_ID"],
                                                                "HEADER"=> $block_name,
                                                                "QUIZ_ID"=> $arItem["PROPERTIES"]["FB_LB_BUTTON_QUIZ"]["VALUE"],
                                                                "LAND_ID"=> $arItem["PROPERTIES"]["FB_LB_LAND"]["VALUE"],
                                                                "FAST_ORDER_PRODUCT_ID" => $product_id,
                                                                "ADD2CART_PRODUCT_ID" => $product_id
                                                            );

                                                        ?>

                                                        <a 
                                                            <?
                                                                if(strlen($arItem["PROPERTIES"]["FB_LB_ONCLICK"]["VALUE"])>0) 
                                                                {
                                                                    $str_onclick = str_replace("'", "\"", $arItem["PROPERTIES"]["FB_LB_ONCLICK"]["VALUE"]);

                                                                    echo "onclick='".$str_onclick."'";

                                                                    $str_onclick = "";
                                                                }

                                                                $b_left_options = array(
                                                                    "MAIN_COLOR" => "main-color",
                                                                    "STYLE" => ""
                                                                );

                                                                if(strlen($arItem["PROPERTIES"]["FB_LB_BG_COLOR"]["VALUE"]) && $arItem["PROPERTIES"]["FB_LB_VIEW"]["VALUE_XML_ID"] != "empty")
                                                                {

                                                                    $b_left_options = array(
                                                                        "MAIN_COLOR" => "btn-bgcolor-custom",
                                                                        "STYLE" => "background-color: ".$arItem["PROPERTIES"]["FB_LB_BG_COLOR"]["VALUE"].";"
                                                                    );

                                                                }
                                                            ?> 

                                                            title="<?=CPhoenix::prepareText($arItem["PROPERTIES"]["FB_LB_NAME"]["VALUE"])?>" class="button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> 

                                                            	<?if($arItem["PROPERTIES"]["FB_LB_VIEW"]["VALUE_XML_ID"] == "" 
                                                            	|| $arItem["PROPERTIES"]["FB_LB_VIEW"]["VALUE_XML_ID"] == "solid"):?> 
                                                            		<?=$b_left_options["MAIN_COLOR"]?>
                                                            	<?elseif($arItem["PROPERTIES"]["FB_LB_VIEW"]["VALUE_XML_ID"] == "shine"):?>
                                                            		shine <?=$b_left_options["MAIN_COLOR"]?> 
                                                            	<?else:?>
                                                            		secondary
                                                            	<?endif;?> <?=CPhoenix::buttonEditClass($arClass)?>"

                                                            	<?if(strlen($b_left_options["STYLE"])):?>
                                                                    style = "<?=$b_left_options["STYLE"]?>"
                                                                <?endif;?>


                                                            	<?=CPhoenix::buttonEditAttr($arAttr);?>

                                                        	>

                                                            
                                                            <?=$arItem["PROPERTIES"]["FB_LB_NAME"]["~VALUE"]?>
                                                           
                                                        </a>

                                                        <?if($arItem["PROPERTIES"]["FB_LB_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

                                                        	<a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL"]?>" class="button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> common-btn-basket-style-added btn-added">
                                                        		<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];?>
                                                        	</a>

                                                        <?endif;?>
                                                    </div>

                                                </div>
                                            
                                            <?endif;?>
                                            
                                            <?if(strlen($arItem["PROPERTIES"]["FB_VIDEO_LINK"]["VALUE"]) > 0):?>

                                                <div class="
                                                	<?if($arItem["TWO_COLS"] == "Y"):?>
                                                		col-sm-6 col-12
                                                	<?else:?>
                                                		col-md-4 col-sm-6 col-12
                                                	<?endif;?>"
                                                >


                                                    <?$iframe = CPhoenix::createVideo($arItem['PROPERTIES']['FB_VIDEO_LINK']['~VALUE']);?>    
                                                        
                                                        <a class="link-video call-modal callvideo" data-call-modal="<?=$iframe["ID"]?>">
                                                        
                                                            <div class="video-cont">

                                                                <div class="video color-<?=$arItem["PROPERTIES"]["FB_TEXT_COLOR"]["VALUE_XML_ID"]?>">
                                                                
                                                                    <div class="play-button"></div>
                                                                
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="video-name"><?=$arItem["PROPERTIES"]["FB_VIDEO_NAME"]["~VALUE"]?></div>
                                                                                <div class="video-comm"><?=$arItem["PROPERTIES"]["FB_VIDEO_COMMENT"]["~VALUE"]?></div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                    
                                                                </div> 
                                                            </div>
                                                        
                                                        </a>
                                                        
                                                   
                                                </div>
                                            
                                            <?endif;?>
                                        
                                        
                                            <?
                                                if($arItem["PROPERTIES"]["FB_RB_TYPE"]["VALUE_XML_ID"] == "")
                                                    $arItem["PROPERTIES"]["FB_RB_TYPE"]["VALUE_XML_ID"] = "form";               
                                            ?>
                                        
                                            <?if(strlen($arItem["PROPERTIES"]["FB_RB_NAME"]["VALUE"])):?>
                                            
                                                <?if($arItem["BUTTONS_COUNT"] == 3 && $arItem["TWO_COLS"] == "Y"):?>
                                                    <span class="clearfix"></span>
                                                <?endif;?>
                                            
                                                <div class="
                                                	<?if($arItem["TWO_COLS"] == "Y"):?>
                                                		col-sm-6 col-12
                                                	<?else:?>
                                                		col-md-4 col-sm-6 col-12
                                            		<?endif;?>"
                                            	>
                                                   

                                                    <div class="button right">

                                                        <?
                                                            $form_id = "";

                                                            if($arItem["PROPERTIES"]["FB_RB_FORM"]["VALUE"] > 0)
                                                                $form_id = $arItem["PROPERTIES"]["FB_RB_FORM"]["VALUE"];

                                                            $product_id = "";

															if($arItem["PROPERTIES"]["FB_RB_OFFER_ID"]["VALUE"])
																$product_id = $arItem["PROPERTIES"]["FB_RB_OFFER_ID"]["VALUE"];

															else if($arItem["PROPERTIES"]["FB_RB_CATALOG_ID"]["VALUE"])
																$product_id = $arItem["PROPERTIES"]["FB_RB_CATALOG_ID"]["VALUE"];


                                                            $arClass = array();
                                                            $arClass=array(
                                                                "XML_ID"=> $arItem["PROPERTIES"]["FB_RB_TYPE"]["VALUE_XML_ID"],
                                                                "FORM_ID"=> $form_id,
                                                                "MODAL_ID"=> $arItem["PROPERTIES"]["FB_RB_MODAL"]["VALUE"],
                                                                "QUIZ_ID"=> $arItem["PROPERTIES"]["FB_RB_BUTTON_QUIZ"]["VALUE"],
                                                                "FAST_ORDER_PRODUCT_ID" => $product_id,
                                                                "ADD2CART_PRODUCT_ID" => $product_id
                                                            );

                                                            $arAttr=array();
                                                            $arAttr=array(
                                                                "XML_ID"=> $arItem["PROPERTIES"]["FB_RB_TYPE"]["VALUE_XML_ID"],
                                                                "FORM_ID"=> $form_id,
                                                                "MODAL_ID"=> $arItem["PROPERTIES"]["FB_RB_MODAL"]["VALUE"],
                                                                "LINK"=> $arItem["PROPERTIES"]["FB_RB_LINK"]["VALUE"],
                                                                "BLANK"=> $arItem["PROPERTIES"]["FB_RB_BUTTON_BLANK"]["VALUE_XML_ID"],
                                                                "HEADER"=> $block_name,
                                                                "QUIZ_ID"=> $arItem["PROPERTIES"]["FB_RB_BUTTON_QUIZ"]["VALUE"],
                                                                "LAND_ID"=> $arItem["PROPERTIES"]["FB_RB_LAND"]["VALUE"],
                                                                "FAST_ORDER_PRODUCT_ID"=> $product_id,
                                                                "ADD2CART_PRODUCT_ID" => $product_id
                                                            );
                                                        ?>


                                                        <a 

                                                            <?
                                                                if(strlen($arItem["PROPERTIES"]["FB_RB_ONCLICK"]["VALUE"])>0) 
                                                                {
                                                                    $str_onclick = str_replace("'", "\"", $arItem["PROPERTIES"]["FB_RB_ONCLICK"]["VALUE"]);

                                                                    echo "onclick='".$str_onclick."'";

                                                                    $str_onclick = "";
                                                                }

                                                                $b_right_options = array(
                                                                    "MAIN_COLOR" => "main-color",
                                                                    "STYLE" => ""
                                                                );

                                                                if(strlen($arItem["PROPERTIES"]["FB_RB_BG_COLOR"]["VALUE"]) && $arItem["PROPERTIES"]["FB_RB_VIEW"]["VALUE_XML_ID"] != "empty")
                                                                {

                                                                    $b_right_options = array(
                                                                        "MAIN_COLOR" => "btn-bgcolor-custom",
                                                                        "STYLE" => "background-color: ".$arItem["PROPERTIES"]["FB_RB_BG_COLOR"]["VALUE"].";"
                                                                    );

                                                                }
                                                            ?> 


                                                            title ="<?=CPhoenix::prepareText($arItem["PROPERTIES"]["FB_RB_NAME"]["VALUE"])?>" 
                                                            class="button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> 

                                                            <?if($arItem["PROPERTIES"]["FB_RB_VIEW"]["VALUE_XML_ID"] == "" || $arItem["PROPERTIES"]["FB_RB_VIEW"]["VALUE_XML_ID"] == "solid"):?> 
                                                            	<?=$b_right_options["MAIN_COLOR"]?>  
                                                            <?elseif($arItem["PROPERTIES"]["FB_RB_VIEW"]["VALUE_XML_ID"] == "shine"):?>
                                                            	shine <?=$b_right_options["MAIN_COLOR"]?> 
                                                            <?else:?>
                                                            	secondary
                                                            <?endif;?> <?=CPhoenix::buttonEditClass ($arClass)?>"

                                                            <?if(strlen($b_right_options["STYLE"])):?>
                                                                style = "<?=$b_right_options["STYLE"]?>"
                                                            <?endif;?>

                                                            <?=CPhoenix::buttonEditAttr ($arAttr)?>>


                                                            <?=$arItem["PROPERTIES"]["FB_RB_NAME"]["~VALUE"]?>
                                                                
                                                        </a>


                                                        <?if($arItem["PROPERTIES"]["FB_RB_TYPE"]["VALUE_XML_ID"] == "add_to_cart"):?>

															<a href="<?=$PHOENIX_TEMPLATE_ARRAY["BASKET_URL"]?>" class="button-def <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]['VALUE']?> common-btn-basket-style-added btn-added">
																<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]["~VALUE"];?>
															</a>

														<?endif;?>

                                                    </div>
                                                    
                                                </div>
                                            
                                            <?endif;?>
                                            
                                        </div>
                                    
                                    <?endif;?>
                                
                                </div>
                                
                            </div>
                            
                            <?if($arItem["TWO_COLS"] == "Y"):?>
                            
                                <div class="first-block-cell image-part col-lg-5 col-md-4 col-12 <?=$arItem["PROPERTIES"]["FB_IMAGE_BLOCK_POSITION"]["VALUE_XML_ID"]?>

                                <?=$arItem["PROPERTIES"]["FB_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"]?>

                                <?=$position_vert?>">

                                    <?$file = CFile::ResizeImageGet($arItem["PROPERTIES"]["FB_ADD_PICTURE"]["VALUE"], array('width'=>800, 'height'=>800), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 60);?>
                                    <img src="<?=$file["src"]?>" class="img-fluid mx-auto d-block" alt="<?=(strlen($arItem["PROPERTIES"]["FB_ADD_PICTURE"]["DESCRIPTION"]))? $arItem["PROPERTIES"]["FB_ADD_PICTURE"]["DESCRIPTION"]:"";?>"/>

                                    
                                </div>

                            <?elseif($arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "easy" && $arItem["PROPERTIES"]["FB_CLICK_DOWN"]["VALUE"] == "Y"):?>

                                <div class="first-block-cell col-md-3 col-12 hidden-xs order-3">

                                   <div class="wrap-scroll-down hidden-xs">
                                        <div class="down-scrollBig scroll-down hidden-xs">
                                            <i class="fa fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    
                                </div>

                            
                            <?endif;?>
                            
                        </div>

                    </div>

                    <?if($arItem["PROPERTIES"]["FB_CLICK_DOWN"]["VALUE"] == "Y" && $arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] != "easy"):?>
                        <div class="wrap-scroll-down hidden-xs bottom-position">
                            <div class="down-scroll scroll-down">
                                <i class="fa fa-chevron-down"></i>
                            </div>
                        </div>
                    <?endif;?>


                </div>

            <?endforeach;?>
	    </div>

	</div>

<?endif;?>

<?}?>


<div class="parent-scroll-down">

    


	<?
		initSliderPhoenixOnePageGenerator($arSlider, "lg");
		initSliderPhoenixOnePageGenerator($arSlider, "xs");
	?>

</div>


<?endif;?>