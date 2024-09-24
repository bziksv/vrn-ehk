<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

use Bitrix\Main;

use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader;

global $APPLICATION, $PHOENIX_TEMPLATE_ARRAY, $siteID;
$bGenerate = false;
$hasRegions = false;

$currentPage = "/bitrix/admin/concept_phoenix_admin_generate_robots.php";
$template = 'include_area.php';
$robotsFolder = "phoenix_regions/robots/";

Loc::loadMessages(__FILE__);

$moduleID = "concept.phoenix";
Loader::includeModule($moduleID);

Loader::includeModule("iblock");

CJSCore::Init(array("jquery"));

$modileRights = $APPLICATION->GetGroupRight($moduleID);

if($modileRights < "R")
    LocalRedirect("/bitrix/");

$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/regions.css");
$GLOBALS['APPLICATION']->SetTitle(Loc::getMessage("ROBOTS_PHOENIX_PAGE_TITLE"));

$siteID = isset($_REQUEST['site_id']) ?  htmlspecialcharsbx($_REQUEST['site_id']) : '';

$arSites = array();
$rsSites = CSite::GetList($by="sort", $order="asc");
while($arSite = $rsSites->Fetch()) 
{
    $res = false;
    
    $rsTemplates = CSite::GetTemplateList($arSite["LID"]);

    while($arTemplate = $rsTemplates->Fetch())
    { 
        if(substr_count($arTemplate["TEMPLATE"], "concept_phoenix_".$arSite["LID"]) > 0)
            $res = true;
    }
    
    if($res == true)
        $arSites[$arSite["LID"]] = $arSite;
    
}

if(empty($arSites))
	LocalRedirect("/bitrix/admin/");


if(strlen($siteID) <= 0 || !array_key_exists($siteID, $arSites))
{
    reset($arSites);
	$currentSite = current($arSites);
    LocalRedirect($currentPage."?lang=".LANGUAGE_ID."&site_id=".$currentSite["LID"]);
}

CPhoenix::phoenixOptionsValues($siteID, array(
	"start",
	"region"
));

$regionsActive = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["ACTIVE"]["VALUES"]["ACTIVE"]["VALUE"];
$mainDomain = CPhoenixRegionality::clearDomain($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]["VALUE"]);

$arDDMenu = array();

$arDDMenu[] = array(
	"TEXT" => Loc::getMessage("ROBOTS_PHOENIX_CHOOSE_SITE"),
	"ACTION" => false
);

if(!empty($arSites) && is_array($arSites))
{
	foreach($arSites as $arRes)
	{
		$arDDMenu[] = array(
			"TEXT" => "[".$arRes["LID"]."] ".$arRes["NAME"],
			"LINK" => $currentPage."?lang=".LANGUAGE_ID."&site_id=".$arRes['LID']
		);
	}
}



$arCurrentSite = $arSites[$siteID];

// Copy seo files to the root of the site
if(!file_exists($arCurrentSite["ABS_DOC_ROOT"].$arCurrentSite["DIR"]."robots.php"))
	CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/install/supporting_files/seo/", $arCurrentSite["ABS_DOC_ROOT"].$arCurrentSite["DIR"], true, true, false);

$aContext = array();
$aContext[] = array(
	"TEXT"	=> "[".$arCurrentSite["LID"]."] ".$arCurrentSite['NAME'],
	"MENU" => $arDDMenu
);

$context = new CAdminContextMenu($aContext);
$context->Show();

if($regionsActive == "Y" && strlen($mainDomain) > 0)
	$bGenerate = true;

if($bGenerate === true)
{
	$arRegions = array();

	$arSelect = Array("ID", "NAME", "PROPERTY_MAIN_DOMAIN");
	$arFilter = Array("IBLOCK_ID" => IntVal($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["IBLOCK_ID"]), "IBLOCK_CODE" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["IBLOCK_CODE"], "ACTIVE"=>"", "!PROPERTY_MAIN_DOMAIN" => false);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	while($ob = $res->GetNext())
	{
		$region = array();
		
		$region["ID"] = $ob["ID"];
		$region["NAME"] = $ob["NAME"];
		$region["DOMAIN"] = $ob["PROPERTY_MAIN_DOMAIN_VALUE"];

		$arRegions[] = $region;
	}
}

if(!empty($arRegions))
	$hasRegions = true;


$arTabs = array(
	array(
		"DIV" => "maintab",
		"TAB" => "",
		"ICON" => "main_user_edit",
	)
);
?>

<?$tabControl = new CAdminTabControl("tabControl", $arTabs);?>

<?/*--Save--*/?>

<?
if($_POST["save"] && $_POST["generateAll"] == "Y" && $modileRights >= "W" && check_bitrix_sessid())
{
	if(!empty($arRegions))
	{
		foreach($arRegions as $arRegion)
		{
			$filePath = $arCurrentSite["ABS_DOC_ROOT"].$arCurrentSite["DIR"].$robotsFolder."robots_".$arRegion["DOMAIN"].".txt";

			if(file_exists($filePath))
				unlink($filePath);

			$el = new CIBlockElement;
			$res = $el->Update($arRegion["ID"], array());
		}
	}

	LocalRedirect($currentPage."?lang=".LANGUAGE_ID."&site_id=".$arCurrentSite["LID"]."&saved=Y");
}

if(!empty($_POST["ID"]) && $modileRights >= "W" && check_bitrix_sessid())
{
	if(!empty($arRegions))
	{
		foreach($arRegions as $arRegion)
		{
			if($arRegion["ID"] == $_POST["ID"])
			{
				$filePath = $arCurrentSite["ABS_DOC_ROOT"].$arCurrentSite["DIR"].$robotsFolder."robots_".$arRegion["DOMAIN"].".txt";

				if(file_exists($filePath))
					unlink($filePath);

				$el = new CIBlockElement;
				$res = $el->Update($arRegion["ID"], array());
			}
			
		}
	}

	LocalRedirect($currentPage."?lang=".LANGUAGE_ID."&site_id=".$arCurrentSite["LID"]."&saved=Y");
}
?>

<?if($_REQUEST["saved"] == "Y"):?>
	<div class="adm-info-message-wrap adm-info-message-green">
		<div class="adm-info-message">
			<div class="adm-info-message-title"><?=Loc::getMessage("ROBOTS_PHOENIX_SAVE_ALL");?></div>
			
			<div class="adm-info-message-icon"></div>
		</div>
	</div>
<?endif;?>

<?/*--Save--*/?>


<?if(!$bGenerate):?>
	<div class="adm-info-message"><?=Loc::getMessage("ROBOTS_PHOENIX_NO_ACTIVATED_REGIONS");?></div>
<?else:?>

	<?if(!$hasRegions):?>
		<div class="adm-info-message"><?=Loc::getMessage("ROBOTS_PHOENIX_NO_DOMAIN_REGIONS");?></div>
	<?else:?>

		<?if($arCurrentSite["DIR"] !== "/"):?>
			<div class="adm-info-message"><?=Loc::getMessage("ROBOTS_PHOENIX_IN_FOLDER");?></div>
		<?else:?>

			<div class="adm-info-message"><?=Loc::getMessage("ROBOTS_PHOENIX_ROBOTS_INFO");?></div><br><br>

			<?$tabControl->Begin();?>

			<form method="post" class="max_options" enctype="multipart/form-data" action="<?=$currentPage?>?lang=<?=LANGUAGE_ID?>&amp;site_id=<?=$siteID?>">

				<input type="hidden" name="generateAll" value="Y" />

				<?=bitrix_sessid_post();?>

				
				<?$tabControl->BeginNextTab();?>
					<tr>
						<td>
							<?=Loc::getMessage("ROBOTS_PHOENIX_ROBOTS_MAIN")?>							
						</td>
						<td style="width:50%;">
							<?
							$href = "javascript: new BX.CAdminDialog({'content_url':'/bitrix/admin/public_file_edit.php?site=".$siteID."&bxpublic=Y&from=includefile&path=".$arCurrentSite["DIR"]."main-robots.txt&lang=".LANGUAGE_ID."&noeditor=Y&template=".$template."&subdialog=Y','width':'1009','height':'503'}).Show();";
							?><a class="adm-btn" href="<?=$href?>" name="edit" title="<?=Loc::getMessage('ROBOTS_PHOENIX_EDIT_ROBOTS')?>"><?=Loc::getMessage('ROBOTS_PHOENIX_EDIT_ROBOTS')?></a>

							<?$href = (CPhoenixRegionality::checkDomainAvailible("https://".$mainDomain)) ? "https://" : "http://";?>
							<?$href = $href.$mainDomain."/robots.txt"?>
								
							&nbsp; <a href="<?=$href;?>" target="_blank"><?=$href;?></a>
							
						</td>
					</tr>

					<?if(!empty($arRegions)):?>

						<tr class="heading"><td colspan="2"><?=LOC::getMessage("ROBOTS_PHOENIX_MODULE_DOMAINS")?></td></tr>

						<?foreach($arRegions as $arRegion):?>

								<tr>
									<td>
										<?=$arRegion["NAME"]." (".$arRegion["DOMAIN"].")";?>							
									</td>
									<td style="width:50%;">
										<?$href = "javascript: new BX.CAdminDialog({'content_url':'/bitrix/admin/public_file_edit.php?site=".$siteID."&bxpublic=Y&from=includefile&path=".$arCurrentSite["DIR"].$robotsFolder."robots_".$arRegion["DOMAIN"].".txt&lang=".LANGUAGE_ID."&noeditor=Y&template=".$template."&subdialog=Y','width':'1009','height':'503'}).Show();";?>

										<a class="adm-btn" href="<?=$href?>" name="edit" title="<?=Loc::getMessage('ROBOTS_PHOENIX_EDIT_ROBOTS')?>"><?=Loc::getMessage('ROBOTS_PHOENIX_EDIT_ROBOTS')?></a>

										<input type="button" name="generate" data-element_id="<?=$arRegion["ID"];?>" class="submit-btn adm-btn-save" value="<?=Loc::getMessage("ROBOTS_PHOENIX_BUTTON_SAVE_VALUE_SHORT")?>" title="<?=Loc::getMessage("ROBOTS_PHOENIX_BUTTON_SAVE_VALUE_SHORT")?>">

										
										<?$href = (CPhoenixRegionality::checkDomainAvailible("https://".$arRegion["DOMAIN"])) ? "https://" : "http://";?>
										<?$href = $href.$arRegion["DOMAIN"]."/robots.txt"?>
											
										&nbsp; <a href="<?=$href;?>" target="_blank"><?=$href;?></a>
									</td>
								</tr>

						<?endforeach;?>
					<?endif;?>

					<?$tabControl->Buttons();?>

					<?if($modileRights > "R"):?>
						<input type="submit" name="save" class="submit-btn adm-btn-save" value="<?=Loc::GetMessage("ROBOTS_PHOENIX_BUTTON_SAVE_VALUE")?>" title="<?=Loc::GetMessage("ROBOTS_PHOENIX_BUTTON_SAVE_VALUE")?>" />
					<?endif;?>
				

			</form>

			<script type="text/javascript">
				$(document).ready(function(){
					$('input[name=generate]').on('click', function(){
						var _this = $(this);
						_this.attr('disabled', 'disabled');
						$.ajax({
							type: 'POST',
							dataType: 'html',
							data: {'sessid': $('input[name=sessid]').val(), 'ID': _this.data('element_id')},
							success: function(html){
								_this.removeAttr('disabled');
							},
							error: function(data){
								window.console&&console.log(data);
							}
						});
					})
				});			
			</script>

			<?$tabControl->End();?>

		<?endif;?>

		
		
		
	<?endif;?>

<?endif;?>


