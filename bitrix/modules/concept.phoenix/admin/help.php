<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);


$moduleID = "concept.phoenix";

if($GLOBALS["APPLICATION"]->GetGroupRight($moduleID) < "R")
    LocalRedirect("/bitrix/");

Main\Loader::includeModule($moduleID);

global $PHOENIX_TEMPLATE_ARRAY;
CPhoenix::setCounterPhoenix();


$APPLICATION->SetTitle(Loc::getMessage("MODULE_PAGE_TITLE"));



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// _____________________________ POST part _____________________________ //
/*if(!empty($_REQUEST["save"]))
{
	LocalRedirect($APPLICATION->GetCurUri("saved=1"));
}*/
// _____________________________ POST part _____________________________ //


$aTabs = array(

	array(
		"DIV" => "maintab1",
		"TAB" => Loc::getMessage("MODULE_INSTRUCTION"),
		"ICON" => "main_user_edit",
		"TITLE" => Loc::GetMessage("MODULE_INSTRUCTION_TITLE")
	),
	array(
		"DIV" => "maintab",
		"TAB" => Loc::getMessage("MODULE_HELP"),
		"ICON" => "main_user_edit",
		"TITLE" => Loc::GetMessage("MODULE_HELP_TITLE")
	),
	

);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<style>
div.quote {
    padding-top: 0.6em;
    padding-bottom: 0.6em;
}
table.quote {
    border: #E8E8E8 solid 1px;
    color: #555;
    background: url(/bitrix/images/concept.phoenix/help/quote-gray.png) no-repeat scroll left top #F7F7F7;
    width: 100%;
}
table.quote>tbody>tr>td {
    padding: 0.95em 1.5em;
}
ul.phx li{
	margin-bottom: 15px;
}

</style>

<?CJSCore::Init(array("jquery"));?>
<script>
	$(document).on('click', '#phoenix_hide_adv', 
	function() 
	{
		$.post(
	        "/bitrix/tools/concept.phoenix/ajax/hide_adv.php",
	        {
	            "phoenix_hide_adv": $(this).prop('checked'),
	            "set": "Y"
	        }
	    ); 
	});
</script>

<form>
<?


echo bitrix_sessid_post();

$tabControl->Begin();

?>

<?$tabControl->BeginNextTab();?>

	<tr style="vertical-align: top;">
		<td width="40%">
			<?=CPhoenixInstructions::printHTML('view_1');?>			
		</td>
	</tr>

<?$tabControl->BeginNextTab();?>
	
	<tr>
		<td colspan = '2'>
			<table class="data-table"><tbody><tr>

				<td>
					<a href="mailto:fenix@concept360.ru" target="_blank"><b>fenix@concept360.ru </b></a><b><?=Loc::getMessage("PHOENIX_TEXT1")?></b><?=Loc::getMessage("PHOENIX_TEXT2")?></b><br><br>

					<div class="quote">

						<table class="quote">
						<tbody><tr><td><?=Loc::getMessage("PHOENIX_TEXT3")?><br><?=Loc::getMessage("PHOENIX_TEXT4")?></td></tr></tbody></table>

					</div>

				</td>
				</tr></tbody>
			</table>

			<table class="data-table"><tbody><tr><td>

					<b><?=Loc::getMessage("PHOENIX_TEXT5")?></b><br>

					<ul>
						<li><?=Loc::getMessage("PHOENIX_TEXT6")?><br></li>
						<li><?=Loc::getMessage("PHOENIX_TEXT7")?><br></li>
						<li><?=Loc::getMessage("PHOENIX_TEXT8")?><br></li>
					</ul>

					<b><?=Loc::getMessage("PHOENIX_TEXT9")?></b>

					<ol>
						<li><?=Loc::getMessage("PHOENIX_TEXT10")?><br></li>
						<li><?=Loc::getMessage("PHOENIX_TEXT11")?><br></li>
						<li><?=Loc::getMessage("PHOENIX_TEXT12")?><br></li></ol>

				</td>

				</tr></tbody></table>

				<div class="quote">
					<table class="quote"><tbody><tr><td><b><?=Loc::getMessage("PHOENIX_TEXT13")?></b><br><br><?=Loc::getMessage("PHOENIX_TEXT14")?><br><br><?=Loc::getMessage("PHOENIX_TEXT15")?></td></tr></tbody>
					</table>
				</div>

				<div class="quote"><table class="quote"><tbody><tr><td><?=Loc::getMessage("PHOENIX_TEXT16")?> <img src="//opt-560835.ssl.1c-bitrix-cdn.ru/bitrix/images/main/smiles/3/bx_smile_wink.png?14416995737007" border="0" data-code=";)" data-definition="UHD" alt=";)" style="width:20px;height:20px;" title="" class="bx-smile">)</td></tr></tbody></table></div>


		</td>
	</tr>

	<tr>
		<td colspan = '2' style="border-top: 1px solid #e5e5e5; padding-bottom: 20px;">
			
		</td>
	</tr>

	<?CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["GLOBALS"]["ITEMS"]["HIDE_ADV"]);?>


<?$tabControl->End();
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>