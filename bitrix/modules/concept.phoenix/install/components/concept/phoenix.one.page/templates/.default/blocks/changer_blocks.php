<?if(!empty($arItem["PROPERTIES"]["CHANGER_BLOCKS_LABEL"]["VALUE"])):?>
	<?
		$styles = '';
		$arItem["PROPERTIES"]["CHANGER_BLOCKS_STYLE"]["VALUE_XML_ID"] = $arItem["PROPERTIES"]["CHANGER_BLOCKS_STYLE"]["VALUE_XML_ID"] ? $arItem["PROPERTIES"]["CHANGER_BLOCKS_STYLE"]["VALUE_XML_ID"] : "view_1";
	?>


	<div class="row changer-blocks <?=($arItem["PROPERTIES"]["CHANGER_BLOCKS_ALIGN"]["VALUE_XML_ID"])?$arItem["PROPERTIES"]["CHANGER_BLOCKS_ALIGN"]["VALUE_XML_ID"]:'justify-content-center';?> <?=($arItem["PROPERTIES"]["CHANGER_BLOCKS_STYLE"]["VALUE_XML_ID"])?$arItem["PROPERTIES"]["CHANGER_BLOCKS_STYLE"]["VALUE_XML_ID"]:'view_1';?>">
		<div class="line"></div>
		
		<?foreach($arItem["PROPERTIES"]["CHANGER_BLOCKS_LABEL"]["~VALUE"] as $k => $chLabel):?>
			<?
				$arName = explode(';', $chLabel);

				$arDesc = array();
				if($arItem["PROPERTIES"]["CHANGER_BLOCKS_LABEL"]["~DESCRIPTION"][$k])
					$arDesc = explode(';', $arItem["PROPERTIES"]["CHANGER_BLOCKS_LABEL"]["~DESCRIPTION"][$k]);
			?>
			
			<div class="col-auto item item-<?=$k?>">
				<div class="changer-link changer-link-js <?=($k === 0)?'active':'';?> <?=($arItem["PROPERTIES"]["CHANGER_BLOCKS_STYLE"]["VALUE_XML_ID"] === 'view_1' && !isset($arName[1]))?'main-color-border-active':'';?>" data-id = "<?=$arItem["PROPERTIES"]["CHANGER_BLOCKS_ID"]["VALUE"][$k]?>">
					<div class="wr-text">
						<span class="name"><?=$arName[0]?></span>
						<?if(!empty($arDesc)):?>
							<span class="desc" <?=isset($arDesc[1])?'style="background-color:'.$arDesc[1].';"':'';?>><?=$arDesc[0]?></span>
						<?endif;?>
					</div>
				</div>
			</div>

			<?
				if(isset($arName[1]))
				{
					if($arItem["PROPERTIES"]["CHANGER_BLOCKS_STYLE"]["VALUE_XML_ID"] === 'view_2')
					{
						$styles .= '#block'.$arItem["ID"].' .changer-blocks .item-'.$k.' .changer-link.active .wr-text{';
						$styles .= 'border-color:'.$arName[1];
						$styles .= '}';
					}
					else
					{
						$styles .= '#block'.$arItem["ID"].' .changer-blocks .item-'.$k.' .changer-link.active{';
						$styles .= 'border-color:'.$arName[1];
						$styles .= '}';
					}
					
				}
			?>
		<?endforeach;;?>

	</div>

	<?if(strlen($styles)>0):?>
		<style>
			<?=$styles;?>
		</style>
	<?endif;?>
<?endif;?>