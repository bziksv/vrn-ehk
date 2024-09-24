<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Такой страницы не существует");
?>

<?

global $PHOENIX_TEMPLATE_ARRAY;

if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["BG_404"]["VALUE"] > 0)
	$bg = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["BG_404"]["VALUE"], array('width'=>2000, 'height'=>2000), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

?>

<div class="error-404 tone-<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]["VALUE"]?>" <?if(isset($bg)):?> style="background-image: url(<?=$bg["src"]?>);" <?endif;?>>
	<div class="top-shadow"></div>
	<div class="shadow-tone"></div>
	<div class="message404">
		<?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MES_404"]["VALUE"])>0):?>
			<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MES_404"]["~VALUE"]?>
		<?else:?>
		<span>404</span>
		Такой страницы не существует :(
		<?endif;?>
	</div>
</div>


<?//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>