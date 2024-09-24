<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

use Bitrix\Main\Localization\Loc;

$GLOBALS['APPLICATION']->SetTitle(Loc::getMessage("PHOENIX_AMOCRM_PAGE_TITLE"));
$currentPage = "/bitrix/admin/concept_phoenix_admin_generate_amocrm.php";
$arTabs = array(
	array(
		"DIV" => "maintab",
		"TAB" => Loc::getMessage("PHOENIX_AMOCRM_PAGE_MAINTAB"),
		"ICON" => "main_user_edit",
	)
);

$tabControl = new CAdminTabControl("tabControl", $arTabs);
CJSCore::Init(array("jquery"));
if($_POST["save"] && check_bitrix_sessid())
{

}


$tabControl->Begin();?>

<form method="post" class="amo_form" enctype="multipart/form-data" action="<?=$currentPage?>?lang=<?=LANGUAGE_ID?>">

	<?=bitrix_sessid_post();?>

	<?$tabControl->BeginNextTab();
	?>

		<tr>
			<td class="left-td"><?=Loc::getMessage("PHOENIX_AMOCRM_PAGE_SUBDOMAIN")?></td>
			<td><input class="amo_subdomain" type="text"></td>
		</tr>
		<tr>
			<td class="left-td"><?=Loc::getMessage("PHOENIX_AMOCRM_PAGE_REDIRECT_URI")?></td>
			<td><input class="amo_redirect_uri" type="text"></td>
		</tr>
		<tr>
			<td class="left-td"><?=Loc::getMessage("PHOENIX_AMOCRM_PAGE_SECRET_KEY")?></td>
			<td><input class="amo_secret_key" type="text"></td>
		</tr>
		<tr>
			<td class="left-td"><?=Loc::getMessage("PHOENIX_AMOCRM_PAGE_CLIENT_ID")?></td>
			<td><input class="amo_client_id" type="text"></td>
		</tr>
		<tr>
			<td class="left-td"><?=Loc::getMessage("PHOENIX_AMOCRM_PAGE_CODE")?></td>
			<td><textarea class="amo_code"></textarea></td>
		</tr>

		<tr >
			<td class="wr-btn" colspan="2">
				<input type="submit" name="save" class="submit-btn adm-btn-save" value="<?=Loc::GetMessage("PHOENIX_AMOCRM_PAGE_BUTTON_SAVE_VALUE")?>" title="<?=Loc::GetMessage("PHOENIX_AMOCRM_PAGE_BUTTON_SAVE_VALUE")?>" />
			</td>
		</tr>

		<tr>
			<td colspan="2" class="wr_amocrm_token">

				<div class="wr_row">
					<div class="amocrm_token_value_title"><?=Loc::GetMessage("PHOENIX_AMOCRM_PAGE_TOKEN_RESULT_TITLE")?></div>
					<div class="amocrm_token_value_subtitle"><?=Loc::GetMessage("PHOENIX_AMOCRM_PAGE_TOKEN_RESULT_SUBTITLE")?></div>
					<div class="amocrm_token_value_ajax"></div>
				</div>

				<div class="wr_row">
					<div class="amocrm_token_value_title"><?=Loc::GetMessage("PHOENIX_AMOCRM_PAGE_REFRESH_TOKEN_RESULT_TITLE")?></div>
					<div class="amocrm_token_value_subtitle"><?=Loc::GetMessage("PHOENIX_AMOCRM_PAGE_REFRESH_TOKEN_RESULT_SUBTITLE")?></div>
					<div class="amocrm_refresh_token_value_ajax"></div>
				</div>
			</div>
		</tr>
		<tr>
			<td colspan="2" class="wr_amocrm_token_errors_ajax"></td>
		</tr>
		

</form>

<style>
	.wr_row{
		margin-bottom: 40px;
	}
	td.wr-btn{
		padding-top: 30px;
	}
	td.left-td{
		width: 25%;
	}
	.amo_form input[type='text']{
		width: 100%;
	}
	.amo_form textarea{
		width: 100%;
	}
	.wr_amocrm_token_errors_ajax,
	.wr_amocrm_token{
		display: none;
	}
	.wr_amocrm_token_errors_ajax.active,
	.wr_amocrm_token.active{
		padding-top: 40px;
		display: table-cell;
	}
	.wr_amocrm_token.active .amocrm_refresh_token_value_ajax,
	.wr_amocrm_token.active .amocrm_token_value_ajax{
		border: 2px solid #eee;
		border-radius: 5px;
		word-break: break-word;
		padding: 20px;
	}
	
	.amocrm_token_value_title{
		margin-bottom: 10px;
		font-size: 20px;
		font-weight: bold;
	}
	.amocrm_token_value_subtitle{
		margin-bottom: 20px;
		font-size: 14px;
		color: #989898;
	}
	.wr_amocrm_token_errors_ajax{
		font-size: 20px;
		font-weight: bold;
	}
</style>


	<script type="text/javascript">
		$(document).ready(function(){
			$('input.submit-btn').on('click', function(e){
				e.preventDefault();
				var target = e.target,
					_this=$(this);
        		form = target.closest('form');

				var amo_subdomain = form.querySelector(".amo_subdomain"),
					amo_client_id = form.querySelector(".amo_client_id"),
					amo_secret_key = form.querySelector(".amo_secret_key"),
					amo_redirect_uri = form.querySelector(".amo_redirect_uri"),
					amo_code = form.querySelector(".amo_code"),
					amocrm_token_value_ajax = form.querySelector(".amocrm_token_value_ajax"),
					amocrm_refresh_token_value_ajax = form.querySelector(".amocrm_refresh_token_value_ajax"),
					wrAmocrm_token_errors_ajax = form.querySelector(".wr_amocrm_token_errors_ajax"),
					wrAmocrm_token = form.querySelector(".wr_amocrm_token");


					wrAmocrm_token.classList.remove('active');
					wrAmocrm_token_errors_ajax.classList.remove('active');

				if(amo_subdomain.value && amo_client_id.value && amo_secret_key.value && amo_code.value && amo_redirect_uri.value)
				{

					$.ajax({
						url: '/bitrix/tools/concept.phoenix/ajax/generate_amocrm_access_token.php',
						type: 'POST',
						dataType: 'json',
						data: {
							'amo_subdomain':amo_subdomain.value,
							'amo_client_id':amo_client_id.value,
							'amo_secret_key':amo_secret_key.value,
							'amo_code':amo_code.value,
							'amo_redirect_uri':amo_redirect_uri.value
						},
						success: function(result){

							if(result)
							{

								if(result.access_token)
								{
									amocrm_token_value_ajax.innerHTML = result.access_token;
									amocrm_refresh_token_value_ajax.innerHTML = result.refresh_token;
									wrAmocrm_token.classList.add('active');
								}
								else if(result.detail)
								{
									wrAmocrm_token_errors_ajax.classList.add('active');
									wrAmocrm_token_errors_ajax.innerHTML = result.detail;
								}

								_this.removeClass('adm-btn-load');
								$('.adm-btn-load-img-green').remove();

							}
						},
						error: function(data){
							
						}
					});
				}
			})
		});			
	</script>

<?$tabControl->End();?>