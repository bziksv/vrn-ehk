<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
	use \Bitrix\Main\Localization\Loc as Loc;
	use \Bitrix\Main\Page\Asset as Asset; 

	$moduleID = "concept.phoenix";
	CModule::IncludeModule($moduleID);

	global $PHOENIX_TEMPLATE_ARRAY, $USER;

	function contactHTML($regionsIsset = false)
	{
		CPhoenixTemplate::contactHTML($regionsIsset);
	}

	$bIsMainPage = $APPLICATION->GetCurPage(false) == SITE_DIR;
?>

<?Loc::loadMessages(__FILE__);?>

<!DOCTYPE HTML>
<html lang="<?=LANGUAGE_ID?>" prefix="og: //ogp.me/ns#">
<head>
	
	<?CPhoenixTemplate::start();?>

	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=2.4" />
	
    <title><?$APPLICATION->ShowTitle()?></title>

    <?$APPLICATION->ShowHead();?>

	<?if(Bitrix\Main\Loader::includeModule('currency')):?>
	    <script skip-moving="true">
	        BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($PHOENIX_TEMPLATE_ARRAY["CURRENCIES"], false, true, true)?>);
	    </script>
	<?endif;?>


	<?
		if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["META"]["VALUE"]))
		{
			foreach($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["META"]["~VALUE"] as $arMeta){
				echo $arMeta["name"]."\n" ;
			}
		}
		if(!$PHOENIX_TEMPLATE_ARRAY["BINDEX_BOT"])
		{

			if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SERVICE"]["VALUE"]["ACTIVE"] != "Y" && strlen($PHOENIX_TEMPLATE_ARRAY["HEAD_JS"])>0)
				echo $PHOENIX_TEMPLATE_ARRAY["HEAD_JS"];


			if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INHEAD"]['VALUE']))
				echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INHEAD"]['~VALUE'];
		}
	?>
	<?	
		if(!$PHOENIX_TEMPLATE_ARRAY["BINDEX_BOT"])
			$APPLICATION->ShowViewContent("service_head");
	?>
</head>

<?
	$APPLICATION->ShowPanel();

	$offset = 20;

	if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_FIXED"]["VALUE"]["ACTIVE"] == 'fixed')
		$offset = 150;

	if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FONT_COLOR"]['VALUE'] == '')
		$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FONT_COLOR"]['VALUE'] == 'light';

	$regionsIsset = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['ACTIVE']["VALUE"]["ACTIVE"] == "Y" && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]['SHOW_IN_HEAD']["VALUE"]["ACTIVE"] == "Y" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["VALUE"])>0 && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["LOCATION_LIST"]["VALUE"])>0;

?>



<body class=" <?=(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false)?'bindex':'';?> font-maincolor-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FONT_COLOR"]['VALUE']?> <?=($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA"]["VALUE"]["ACTIVE"]=="Y" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SITE_KEY"]["VALUE"])>0)? "captcha":"";?>" id="body" data-spy="scroll" data-target="#navigation" data-offset="<?=$offset?>">


	<?//require_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/styles_and_scripts.php");
	//CPhoenixTemplate::setStylesAndScripts();
	?>
	
	<input type="hidden" class="serverName" name="serverName" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["STR_URL"]?>">
	<input type="hidden" class="curPageUrl" name="curPageUrl" value="<?=$APPLICATION->GetCurPage(false)?>">
	<input type="hidden" class="tmpl_path" name="tmpl_path" value="<?=SITE_TEMPLATE_PATH?>">
	<input type="hidden" class="tmpl" name="tmpl" value="<?=SITE_TEMPLATE_ID?>">
	<input type="hidden" class="site_id" name="site_id" value="<?=SITE_ID?>">
	<input type="hidden" class="domen-url-for-cookie" value="<?=CPhoenixHost::getHostTranslit()?>">
	<input type="hidden" class="urlpage" name="urlpage" value="">
	<input type="hidden" id="showBasketAfterFirstAdd" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ADD_ON"]["VALUE"]["ACTIVE"]?>">
	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA"]["VALUE"]["ACTIVE"]=="Y" && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SITE_KEY"]["VALUE"])>0):?>
		<input class="captcha-site-key" type="hidden" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SITE_KEY"]["VALUE"]?>">

	<?endif;?>
	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MIN_SUM']['VALUE']):?>
	    <input type="hidden" id="min_sum" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['CART_MIN_SUM']['VALUE']?>" />
	<?endif;?>
	<?for($i=1;$i<=10;$i++):?>
	    <input type="hidden" id="custom-input-<?=$i?>" name="custom-input-<?=$i?>" value="">
	<?endfor;?>

	<?for($i=1;$i<=3;$i++):?>
	    <input type="hidden" id="custom-dynamic-input-<?=$i?>" name="custom-dynamic-input-<?=$i?>" value="">
	<?endfor;?>


	<input type="hidden" id="shop-mode" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]["VALUE"]["ACTIVE"]?>">
	<input type="hidden" id="catalogIblockID" value ="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"]?>">
	<input type="hidden" id="LAND_iblockID" value ="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CONSTR']["IBLOCK_ID"]?>">
	<input type="hidden" id="LAND_iblockTypeID" value ="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CONSTR']["IBLOCK_TYPE"]?>">


	<input type="hidden" class="mask-phone" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["STD_MASK"][$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["MAIN_MASK"]["VALUE"]]?>">


	<?
		if(!$PHOENIX_TEMPLATE_ARRAY["BINDEX_BOT"])
		{

			if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SERVICE"]["VALUE"]["ACTIVE"] != "Y" && strlen($PHOENIX_TEMPLATE_ARRAY["BODY_JS"])>0)
				echo $PHOENIX_TEMPLATE_ARRAY["BODY_JS"];

			if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INBODY"]['VALUE']))
				echo $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INBODY"]['~VALUE'];

		}

	
		global $PHOENIX_MENU;


		if($PHOENIX_MENU>0)
			$APPLICATION->IncludeComponent(
				"concept:phoenix.menu", 
				"open_menu", 
				array(
					"COMPONENT_TEMPLATE" => "open_menu",
					"COMPOSITE_FRAME_MODE" => "N",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "A",
					"CACHE_USE_GROUPS" => "Y"
				),
				false
			);
	?>

	<?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['ACTIVE']['VALUE']['ACTIVE'] == "Y" ):?>
	    
	    <div class="search-top search-top-js">
	        
	        <div class="container">
	            <div class="close-search-top"></div>


	            <?$APPLICATION->IncludeComponent("concept:phoenix.search.line", "top", 
	            	Array(
	            		"CONTAINER_ID" => "search-page-input-container-top",
	            		"INPUT_ID" => "search-page-input-top",
	            		"SHOW_RESULTS" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['SEARCH']["ITEMS"]['FASTSEARCH_ACTIVE']['VALUE']['ACTIVE'],
	            		"COMPOSITE_FRAME_MODE" => "N"
	            	)
	            );?>
	            
	        </div>

	    </div>

	<?endif;?>


	<div id="phoenix-container" class="wrapper tone-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>">


		<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["SHARE_ON"]["VALUE"]["ACTIVE"] == 'Y'):?>
	    	
	    
	        <div class="public_shares d-none d-sm-block">
	        
	            <a class='vkontakte' onclick=""><i class="concept-vkontakte"></i><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHARE_TITLE"]?></span></a>
	            
	            <a class='facebook' onclick=""><i class="concept-facebook-1"></i><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHARE_TITLE"]?></span></a>
	            
	            <a class='twitter' onclick=""><i class="concept-twitter-bird-1"></i><span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["SHARE_TITLE"]?></span></a>
	            
	        </div>
	        
	    <?endif;?>


		<?
			if($PHOENIX_MENU>0)
				$APPLICATION->IncludeComponent(
					"concept:phoenix.menu",
					"mobile_menu",
					Array(
						"COMPONENT_TEMPLATE" => "mobile_menu",
						"COMPOSITE_FRAME_MODE" => "N",
						"CACHE_TIME" => "36000000",
						"CACHE_TYPE" => "A",
						"CACHE_USE_GROUPS" => "Y"
					)
				);

			$PHOENIX_TEMPLATE_ARRAY["HEADER_BG"] = false;

			$style_header = "";

			if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_COLOR"]['VALUE'])>0)
			{
				$arColor = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_COLOR"]['VALUE'];
				$percent = 1;


				if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_COLOR"]['VALUE'] == "transparent")
					$style_header .= 'background-color: transparent; ';

				
				else if(preg_match('/^\#/', $arColor))
		        {
		            $arColor = CPhoenix::convertHex2rgb($arColor);
		            $arColor = implode(',',$arColor);

		            if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_OPACITY"]['VALUE'])>0)
		            $percent = (100 - $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_OPACITY"]['VALUE'])/100;

		        
					$style_header .= 'background-color: rgba('.$arColor.', '.$percent.'); ';
		        }

		        else
		        	$header_style .= 'background-color: '.$arColor.'; ';
			}

			if(strlen($style_header)>0)
			{
				$style_header = "style = '".$style_header."'";
				$PHOENIX_TEMPLATE_ARRAY["HEADER_BG"] = true;
			}

			if($PHOENIX_MENU==0)
				$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"] = "hidden";

		?>

		<header 

			class=
			"
				tone-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?> 
				menu-type-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]["VALUE"]?>
				menu-view-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_VIEW"]["VALUE"]?>
				<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_FIXED"]["VALUE"]["ACTIVE"]?>
				<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_COVER"]["VALUE"]["ACTIVE"]?>
				color_header-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["COLOR_HEADER"]["VALUE"]?>
				
			"
			<?if(strlen($style_header)>0) echo $style_header;?>

			<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG"]["VALUE"])>0):?>
				<?$headBgResize = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG"]["VALUE"], array('width'=>2000, 'height'=>2000), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
				data-src-lg="<?=$headBgResize['src']?>"
			<?endif;?>
		>

			<?
				if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_MAIN"]["VALUE"])>0 && file_exists($_SERVER["DOCUMENT_ROOT"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_MAIN"]["VALUE"]))
					include($_SERVER["DOCUMENT_ROOT"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_MAIN"]["VALUE"]);

				else
					include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/blocks/header_main.php");
			?>

		    <?if( $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_FIXED"]["VALUE"]["ACTIVE"] == "fixed" ):?>
			    <?
			    	if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_SCROLL"]["VALUE"])>0 && file_exists($_SERVER["DOCUMENT_ROOT"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_SCROLL"]["VALUE"]))
						include($_SERVER["DOCUMENT_ROOT"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_SCROLL"]["VALUE"]);

					else
			    		include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/blocks/header_scroll.php");
			    ?>
			<?endif;?>

			<?

				if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_MOBILE"]["VALUE"])>0 && file_exists($_SERVER["DOCUMENT_ROOT"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_MOBILE"]["VALUE"]))
					include($_SERVER["DOCUMENT_ROOT"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_MOBILE"]["VALUE"]);

				else
					include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/blocks/header_mobile.php");
			?>
		    
		</header>