<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);?>

<?
global $PHOENIX_TEMPLATE_ARRAY;

	$arPlacemarks = array();
	$gpsN = '';
	$gpsS = '';
?>

<?if(!empty($arResult["STORES"])):?>
	<div class="bx_storege" id="catalog_store_amount_div">

		<div class="header cart-title row">
			<div class="col-auto title"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_STORE_AMOUNT_TITLE_SECTION"]?></div>
			<div class="col wr-line"><div class="line"></div></div>
			<div class="col-auto align-self-center wr-btn-tab">
				<a class="btn-tab list <?=($arParams["TAB"]=="list")?"active":""?>" data-content="list">
					<span><i class="concept-icon concept-list-bullet"></i><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_STORE_AMOUNT_TITLE_BTN_LIST"]?></span>
				</a>
			</div>
			<?/*?>
			<div class="col-auto align-self-center wr-btn-tab d-none">
				<a class="btn-tab flat <?=($arParams["TAB"]=="flat")?"active":""?>" data-content="flat">
					<span><i class="concept-icon concept-location-5"></i><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_STORE_AMOUNT_TITLE_BTN_FLAT"]?></span>
				</a>
			</div>
			*/?>
			
		</div>

		<div class="body">
			
			<?/*
			<div class="tab-content <?=($arParams["TAB"]=="flat")?"":"d-none"?>" data-content="flat">

				<div class="row no-gutters">

					<div class="col-md-4 col-12 wr-items">

						<div class="back2list d-none"><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_STORE_AMOUNT_NAME_BACK2LIST"]?></span></div>

						<?$i=0;?>

						<?foreach($arResult["STORES"] as $pid => $arProperty):?>

							<div class="list-item flat tab-item-list <?=($i==0)?"first":""?>" data-content="<?=$arProperty["ID"]?>">

								<div class="name"><?=$arProperty["TITLE"]?></div>
								<div class="available-simple <?=$arProperty["QUANTITY"]["QUANTITY_STYLE"]?>"><?=$arProperty["QUANTITY"]["DESCRIPTION_FLAT"]?></div>

							</div>

							<div class="detail-item flat tab-item-detail d-none" data-content="<?=$arProperty["ID"]?>">

								<div class="header">

									<div class="name bold"><?=$arProperty["TITLE"]?></div>
									<div class="available-simple <?=$arProperty["QUANTITY"]["QUANTITY_STYLE"]?>"><?=$arProperty["QUANTITY"]["DESCRIPTION_FLAT"]?></div>
								</div>

								<div class="wr-fields">

									<?if(isset($arProperty["SCHEDULE"]{0})):?>
										<div class="field">

											<div class="field-name"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_STORE_AMOUNT_TITLE_SCHEDULE"]?></div>
											<div class="field-value"><?=$arProperty["SCHEDULE"]?></div>
											
										</div>
									<?endif;?>

									<?if(isset($arProperty["PHONE"]{0})):?>
										<div class="field">

											<div class="field-name"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_STORE_AMOUNT_TITLE_PHONE"]?></div>
											<div class="field-value"><?=$arProperty["PHONE"]?></div>
											
										</div>
									<?endif;?>

									<?if(isset($arProperty["EMAIL_FORMATED"]{0})):?>
										<div class="field">

											<div class="field-name"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_STORE_AMOUNT_TITLE_EMAIL"]?></div>
											<div class="field-value"><?=$arProperty["EMAIL_FORMATED"]?></div>
											
										</div>
									<?endif;?>

								</div>

								<?if($arProperty["DESCRIPTION"] != ''):?>
									<div class="description text-content">
										<?echo $arProperty["DESCRIPTION"]?>
									</div>
								<?endif;?>

							</div>

							<?$i++;?>

							<?
								if($arProperty["COORDINATES"]["GPS_S"]!=0 && $arProperty["COORDINATES"]["GPS_N"]!=0)
								{
									$gpsN=substr(doubleval($arProperty["COORDINATES"]["GPS_N"]),0,15);
									$gpsS=substr(doubleval($arProperty["COORDINATES"]["GPS_S"]),0,15);
									$arPlacemarks[]=array("LON"=>$gpsS,"LAT"=>$gpsN,"TEXT"=>$arProperty["TITLE"]);
								}
							?>

						<?endforeach;?>
						
					</div>

					<div class="col wr-map-item">


						<div class="map-item">

							<?
								$APPLICATION->IncludeComponent("bitrix:map.yandex.view", ".default", array(
										"INIT_MAP_TYPE" => "MAP",
										"MAP_DATA" => serialize(array("yandex_lat"=>$gpsN,"yandex_lon"=>$gpsS,"yandex_scale"=>1,"PLACEMARKS" => $arPlacemarks)),
										"MAP_WIDTH" => "100%",
										"MAP_HEIGHT" => "100%",
										"CONTROLS" => array(
											0 => "ZOOM",
										),
										"OPTIONS" => array(
											0 => "ENABLE_SCROLL_ZOOM",
											1 => "ENABLE_DBLCLICK_ZOOM",
											2 => "ENABLE_DRAGGING",
										),
										"MAP_ID" => "MAP_".$arParams["ELEMENT_ID"].randString(8)
									),
									$component,
									array("HIDE_ICONS" => "Y")
								);
							?>
						</div>
						
					</div>

				</div>

			</div>
			*/?>


			<div class="tab-content <?=($arParams["TAB"] == "list")?"":"d-none"?>" data-content="list">

				<?foreach($arResult["STORES"] as $pid => $arProperty):?>

					<div class="list-item <?=$arProperty["QUANTITY"]["QUANTITY_STYLE"]?> list">
						<div class="header row">
							<div class="name col-auto bold"><?=$arProperty["TITLE"]?></div>
							<div class="dotted col"></div>
							<div class="quantity col-auto bold"><?=$arProperty["QUANTITY"]["DESCRIPTION"]?></div>
						</div>


						<?if($arProperty["DESCRIPTION"] != ''):?>
							<div class="field description"><?echo $arProperty["DESCRIPTION"]?></div>
						<?endif;?>

						<?if(strlen($arProperty["SCHEDULE"])>0):?>
							<div class="field"><?echo $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_STORE_AMOUNT_TITLE_SCHEDULE"].":&nbsp;".$arProperty["SCHEDULE"]?></div>
						<?endif;?>

						<?if(strlen($arProperty["PHONE"])>0):?>
							<div class="field"><?echo $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_STORE_AMOUNT_TITLE_PHONE"].":&nbsp;".$arProperty["PHONE"]?></div>
						<?endif;?>
						
						<?if(strlen($arProperty["EMAIL_FORMATED"])>0):?>
							<div class="field"><?echo $PHOENIX_TEMPLATE_ARRAY["MESS"]["CATALOG_STORE_AMOUNT_TITLE_EMAIL"].":&nbsp;".$arProperty["EMAIL_FORMATED"]?></div>
						<?endif;?>
							

					</div>


				<?endforeach;?>

			</div>

		</div>
		
	</div>
<?endif;?>