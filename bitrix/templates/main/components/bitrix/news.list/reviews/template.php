<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<style>
.callback_form_reviews input{
	    border-radius: 5px;
		padding: 10px;
		width: 300px;
}
.callback_form_reviews textarea{
	    border-radius: 5px;
		padding: 10px;
		width: 300px;
}
</style>
<div id="dialog" title="Заполните форму отзыва" style="display:none">

  <form action="" class="callback_form_reviews" method="POST">
				<p>Ф.И.О</p>
				<p><input type="text" placeholder="Пример: Иван Иванович" name="name" class="req" value=""></p>
				<p>Город:</p>
				<p><input type="text" placeholder="Пример: Воронеж" name="sity" class="req email_check" value=""></p>
				<p>Текст отзыва:</p>
				<p><textarea name="text" class="req"></textarea></p>
				<div class="check">
				    <input type="checkbox" checked="checked" name="CHEK" class="req" value="Y">
				        <span class="chek">Нажимая на эту кнопку, я даю свое согласие на <a href="/upload/compliance.pdf" target="_blank">обработку персональных данных</a> и соглашаюсь с условиями <a href="/upload/politics.pdf" target="_blank">политики конфиденциальности</a>.</span>
				</div>
				<p><input type="submit" value="Отправить"></p>
				
	</form>
</div>
<div class="reviews_btn"><p style="margin: 10px;">Оставить отзыв</p></div>
<div class="message_riviews" style="
	position: absolute;
    margin: auto 300px;
    background: #a64221;
    color: #fff;
    padding: 10px;
    border-radius: 10px;
    display: none;
	width: 250px;
    text-align: center;
    font-size: small;
	"></div>
<div class="reviews_page">
	<?foreach($arResult["ITEMS"] as $arItem)
	{
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
		<div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>" data-id="<?=$arItem["ID"]?>">
			<div class="author_block">
				<!--
				<div class="photo">
					<img <?//My::NewResize($arItem["PREVIEW_PICTURE"]["ID"],48,62,false);?> alt="<?//$arItem["NAME"]?>"> 
				</div>
				-->
				<div class="name" style="margin: -3px 0 0 0px;"><?=$arItem["NAME"]?></div>
				<div class="clear"></div>
				<div class="about">
					<?foreach($arItem["PROPERTIES"]["DESCRIPTION"]["VALUE"] as $key=>$value)
					{
						echo $value." ".$arItem["PROPERTIES"]["DESCRIPTION"]["DESCRIPTION"][$key]."<br />";	
					}?>
				</div>
			</div>
			<div class="text_block">
				<div class="text hidden_text">
					<span>
						<?=$arItem["PREVIEW_TEXT"]?>
					</span>
				</div>
				<span class="more show_review"><a href="#">Читать далее...​</a></span>
			</div>
			<div class="clear"></div>
		</div>
	<?}?>
</div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>

<script>
$(function(){
	$('.reviews_btn').click(function(){
	
		 $( "#dialog" ).dialog({
			 'width':'auto'
		 });
	});	
	
	
	$(".callback_form_reviews").submit(function (e) {
		var ser = $(this).serialize();
      $.ajax({
         url: '/ajax/reviews.php',
         data: ser,
         type: 'post',
         success: function (data) {
			 $( "#dialog" ).dialog( "close" );
			 $('.message_riviews').text(data);
			 $('.message_riviews').fadeIn('slow');
			 setTimeout(function(){$('.message_riviews').fadeOut()}, 3000);
         }
      });
      return false;
   });
	
	
});
</script>