<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("return-back");?>
<?
global $PHOENIX_TEMPLATE_ARRAY;
	$host = CPhoenixHost::getHost($_SERVER);


	$ref_url = explode("?", $_SERVER['HTTP_REFERER']);
	$uri = explode("?", $host.$_SERVER['REQUEST_URI']);

?>

<?if(strlen($ref_url[0])>0 && $ref_url[0] != $uri[0]):?>
	<script type="text/javascript">
        jQuery(document).ready(function($) {
        	if($(".ajax-back-page").length>0)
            	$(".ajax-back-page").html('<div class="return-back"><a href="<?=$_SERVER["HTTP_REFERER"]?>">&#8592;&nbsp;<span class="text"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["RETURN_BACK"]?></span></a></div>');
        });

    </script>
<?endif;?>
<?\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("return-back");?>