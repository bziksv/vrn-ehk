<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Text\Converter;
use \Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$moduleID = "concept.phoenix";

Main\Loader::includeModule($moduleID);


global $APPLICATION;
if($GLOBALS["APPLICATION"]->GetGroupRight($moduleID) < "R")
    LocalRedirect("/bitrix/");

$APPLICATION->SetTitle(Loc::getMessage("MODULE_PHOENIX_PAGE_TITLE"));


global $PHOENIX_TEMPLATE_ARRAY, $siteId;

$siteId = isset($_REQUEST['site_id']) ?  htmlspecialcharsbx($_REQUEST['site_id']) : '';


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

if(strlen($siteId) <= 0 || !array_key_exists($siteId, $arSites))
{
    reset($arSites);
	$arS = current($arSites);
    LocalRedirect("/bitrix/admin/concept_phoenix_admin_index.php?lang=".LANGUAGE_ID."&site_id=".$arS["LID"]);
}



CPhoenix::phoenixOptionsValues($siteId, array(
	"start",
	"design",
	"contacts",
	"menu",
	"footer",
	"catalog",
	"shop",
	"blog",
	"news",
	"actions",
	"lids",
	"services",
	"politic",
	"customs",
	"other",
	"search",
	"catalog_fields",
	"compare",
	"brands",
	"personal",
	"rating",
	"region",
	"comments"
));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");


$aMenu = array();

$arDDMenu = array();

$arDDMenu[] = array(
	"TEXT" => Loc::getMessage("MODULE_PHOENIX_CHOOSE_SITE"),
	"ACTION" => false
);
if(!empty($arSites))
{
	foreach($arSites as $arRes)
	{
		$arDDMenu[] = array(
			"TEXT" => "[".$arRes["LID"]."] ".$arRes["NAME"],
			"LINK" => "concept_phoenix_admin_index.php?lang=".LANGUAGE_ID."&site_id=".$arRes['LID']
		);
	}
}
$arCurrentSite = $arSites[$siteId];
$aContext = array();
$aContext[] = array(
	"TEXT"	=> "[".$arCurrentSite["LID"]."] ".$arCurrentSite['NAME'],
	"MENU" => $arDDMenu
);

$context = new CAdminContextMenu($aContext);
$context->Show();




function addListMultyRow($arField = array())
{?>

	<?global $siteId;?>

	<?

		if(empty($options))
			$options = array();
		
		
		$count_options = array(array(''),array(''),array(''));
		
		if(!isset($arField["VALUE"]) || empty($arField["VALUE"]))
			$arField["VALUE"] = Array();

		$arValues = array_merge($arField["VALUE"], $count_options);

	?>

	<td class="adm-detail-valign-top adm-detail-content-cell-l" width="30%">
		<?if(strlen($arField["HINT"])>0) ShowJSHint($arField["HINT"]);?>
		<?=$arField["DESCRIPTION"]?>:
	</td>

	<td width="70%" class="adm-detail-content-cell-r">

		<table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%" id="<?=$arField["NAME"]?>">
			<tbody>

				<?if(!empty($arValues)):?>

					<?foreach($arValues as $key => $val):?>
						<tr>
							<td>
								<input name="cphnx_list_<?=$arField["NAME"]?>[n<?=$key?>]" value="<?=$val['name']?>" size="<?if($arField["DESC_ON"] == "Y"):?>30<?else:?>70<?endif;?>" type="text"> 

								<?if($arField["DESC_ON"] == "Y"):?>
									<span style="margin-left: 10px;" title="<?=Loc::GetMessage("PHOENIX_HEAD_DESC")?>"><?=Loc::GetMessage("PHOENIX_HEAD_DESC")?>: <input name="cphnx_list_desc_<?=$arField["NAME"]?>[n<?=$key?>]" value="<?=$val['desc']?>" size="18" type="text"></span>
								<?endif;?>
							</td>
						</tr>
					<?endforeach;?>

				<?endif;?>

				<tr>
					<td>
						<input type="button" value="<?=Loc::GetMessage("MODULE_PHOENIX_ADD")?>" onclick="addNewRow('<?=$arField["NAME"]?>')">
					</td>
				</tr>

				<script type="text/javascript">BX.addCustomEvent('onAutoSaveRestore', function(ob, data) {for (var i in data){if (i.substring(0,9)=='phoenix_<?=$name?>['){addNewRow('phoenix_<?=$name?>')}}})</script>
			</tbody>

		</table>

	</td>


<?}



// _____________________________ POST part _____________________________ //
if(!empty($_REQUEST["save"]))
{

	CPhoenix::saveOptions($siteId, $_POST, 'Y');
	LocalRedirect($APPLICATION->GetCurUri("saved=1&tab=". htmlspecialcharsbx($_REQUEST["tab"])));

}
// _____________________________ POST part _____________________________ //


$aTabs = array(
	array(
		"DIV" => "maintab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["NAME"],
		"ICON" => "main_user_edit",
	),

	array(
		"DIV" => "contactstab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "mainmenu",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "footertab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["NAME"],
		"ICON" => "main_user_edit",
	),
	

	array(
		"DIV" => "catalog_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "catalog_item_fields_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "rating_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "comments_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "brand_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "basket_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "order_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["NAME_ORDER"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "fast_order_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["NAME_FAST_ORDER"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "compare_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "blog_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "news_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["NAME"],
		"ICON" => "main_user_edit",
	),
	
	array(
		"DIV" => "action_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "search_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["NAME"],
		"ICON" => "main_user_edit"
	),
	array(
		"DIV" => "personal_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["NAME"],
		"ICON" => "main_user_edit"
	),
	
	array(
		"DIV" => "lidtab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "otherservicestab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "base_goals_tab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ADMIN"]["BASE_GOALS"]["TAB"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "agreementtab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "store",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["NAME"],
		"ICON" => "main_user_edit",
	),
	array(
		"DIV" => "region",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["NAME"],
		"ICON" => "main_user_edit",
	),
	
	array(
		"DIV" => "customestab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["NAME"],
		"ICON" => "main_user_edit",
	),

	array(
		"DIV" => "othertab",
		"TAB" => $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["NAME"],
		"ICON" => "main_user_edit",
	),
	
	
	


);

$tabControl = new CAdminTabControl("tabControl", $aTabs);




require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");



if($_REQUEST["saved"] == 1)
{
	echo CAdminMessage::ShowNote(Loc::getMessage("MODULE_PHOENIX_NOTE_MSG1"));
}
?>


<script type="text/javascript">
	function addNewRow(tableID, row_to_clone)
	{
		var tbl = document.getElementById(tableID);
		var cnt = tbl.rows.length;
		if(row_to_clone == null)
			row_to_clone = -2;
		var sHTML = tbl.rows[cnt+row_to_clone].cells[0].innerHTML;
		var oRow = tbl.insertRow(cnt+row_to_clone+1);
		var oCell = oRow.insertCell(0);

		var s, e, n, p;
		p = 0;
		while(true)
		{
			s = sHTML.indexOf('[n',p);
			if(s<0)break;
			e = sHTML.indexOf(']',s);
			if(e<0)break;
			n = parseInt(sHTML.substr(s+2,e-s));
			sHTML = sHTML.substr(0, s)+'[n'+(++n)+']'+sHTML.substr(e+1);
			p=s+1;
		}
		p = 0;
		while(true)
		{
			s = sHTML.indexOf('__n',p);
			if(s<0)break;
			e = sHTML.indexOf('_',s+2);
			if(e<0)break;
			n = parseInt(sHTML.substr(s+3,e-s));
			sHTML = sHTML.substr(0, s)+'__n'+(++n)+'_'+sHTML.substr(e+1);
			p=e+1;
		}
		p = 0;
		while(true)
		{
			s = sHTML.indexOf('__N',p);
			if(s<0)break;
			e = sHTML.indexOf('__',s+2);
			if(e<0)break;
			n = parseInt(sHTML.substr(s+3,e-s));
			sHTML = sHTML.substr(0, s)+'__N'+(++n)+'__'+sHTML.substr(e+2);
			p=e+2;
		}
		p = 0;
		while(true)
		{
			s = sHTML.indexOf('xxn',p);
			if(s<0)break;
			e = sHTML.indexOf('xx',s+2);
			if(e<0)break;
			n = parseInt(sHTML.substr(s+3,e-s));
			sHTML = sHTML.substr(0, s)+'xxn'+(++n)+'xx'+sHTML.substr(e+2);
			p=e+2;
		}
		p = 0;
		while(true)
		{
			s = sHTML.indexOf('%5Bn',p);
			if(s<0)break;
			e = sHTML.indexOf('%5D',s+3);
			if(e<0)break;
			n = parseInt(sHTML.substr(s+4,e-s));
			sHTML = sHTML.substr(0, s)+'%5Bn'+(++n)+'%5D'+sHTML.substr(e+3);
			p=e+3;
		}

		var htmlObject = {'html': sHTML};
		BX.onCustomEvent(window, 'onAddNewRowBeforeInner', [htmlObject]);
		sHTML = htmlObject.html;

		oCell.innerHTML = sHTML;

		var patt = new RegExp ("<"+"script"+">[^\000]*?<"+"\/"+"script"+">", "ig");
		var code = sHTML.match(patt);
		if(code)
		{
			for(var i = 0; i < code.length; i++)
			{
				if(code[i] != '')
				{
					s = code[i].substring(8, code[i].length-9);
					jsUtils.EvalGlobal(s);
				}
			}
		}

		if (BX && BX.adminPanel)
		{
			BX.adminPanel.modifyFormElements(oRow);
			BX.onCustomEvent('onAdminTabsChange');
		}

		setTimeout(function() {
			var r = BX.findChildren(oCell, {tag: /^(input|select|textarea)$/i});
			if (r && r.length > 0)
			{
				for (var i=0,l=r.length;i<l;i++)
				{
					if (r[i].form && r[i].form.BXAUTOSAVE)
						r[i].form.BXAUTOSAVE.RegisterInput(r[i]);
					else
						break;
				}
			}
		}, 10);
	}
</script>

<?CJSCore::Init(array("jquery"));?>

<script type="text/javascript">


	jQuery(document).ready(function($) {

		<?if(strlen(htmlspecialcharsbx($_REQUEST["tab"]))>0):?>

			var value = "<?=htmlspecialcharsbx($_REQUEST["tab"])?>".replace("tab_cont_", "");

			$("#tabControl_tabs").find("span").removeClass('adm-detail-tab-active');
			$("#tabControl_tabs").find("span#<?=htmlspecialcharsbx($_REQUEST["tab"])?>").addClass('adm-detail-tab-active');
			$("div.adm-detail-content-wrap").find("div.adm-detail-content").hide();
			$("div.adm-detail-content-wrap").find("div#"+value).show();

		<?endif;?>

		showOptions("Y");
	});

	$(document).on('change', ".show-option", function()
	{

		showOptions("", $(this));
	});

	

	function showOptions(firstLoad, this_)
	{
		firstLoad = firstLoad || "";
		this_ = this_ || "";


		if(firstLoad == "Y")
		{
			$(".content-options").css({"display": "none"});

			$(".show-option").each(
				function(index)
				{
					$(".content-options[data-content-options='"+$(this)[0].value+"']").css({"display": ""});
				}
			);
		}

		else
		{
			$(".content-options[data-option-name='"+this_[0].name+"']").css({"display": "none"});
			$(".content-options[data-content-options='"+this_[0].value+"']").css({"display": ""});
		}
	}



	function checkReq(this_, reg, new_str)
	{
		if(this_.context.checked)
		{
			var str = this_.context.name;
			str = str.replace(reg, new_str);

			$("input[name='"+str+"']").prop('checked', true);
		}
	}

	function testReq(this_, reg, new_str)
	{
		if(!this_.context.checked)
		{
			var str = this_.context.name;
			str = str.replace(reg, new_str);

			if($("input[name='"+str+"']")[0].checked)
				this_.prop('checked', true);
		}
	}

	$(document).on('change', ".checkbox_req", function()
	{
		checkReq($(this), "r_", "");
	});

	$(document).on('change', ".checkbox_inp", function()
	{
		testReq($(this), "phoenix_personal_props_", "phoenix_personal_props_r_");
	});
	
</script>

<?\Bitrix\Main\Page\Asset::getInstance()->addJs("/bitrix/js/concept.phoenix/jquery-ui.min.js");?>


<script>
	$(document).on('click', '.adm-detail-tab', 
	function() 
	{
		$("input[name='tab']").val($(this).attr("id"));
	});
</script>

<style>
	.admin-inputdatalist-preload{
		width: 14px;
	    height: 14px;
	    position: absolute;
	    right: 7px;
	    top: 6px;
	    background-repeat: no-repeat;
	    background-image: url(/bitrix/panel/main/images/waiter-white.gif)!important;
	    background-size: contain;
	    display: none;
	}
	input.admin-inputdatalist-css.ui-autocomplete-loading + .admin-inputdatalist-preload{
	    display: block;
	}
	input.admin-inputdatalist-css{
        background: url(/bitrix/images/concept.phoenix/search_gr.svg) 6px center no-repeat !important;
	    background-size: 14px !important;
	    padding-left: 25px !important;
	    background-color: #fff !important;
	}
	.admin-inputdatalist{
	    position: absolute;
	    left: 0;
	    right: 0;
	    top: 45px;
	}
	.admin-inputdatalist .ui-autocomplete {
	    background: #fff;
	    max-height: 240px;
	    overflow: auto;
	    list-style: none;
	    padding: 0;
	    margin: 0;
	    z-index: 9;
        box-shadow: 0px 15px 16px 0px rgba(50, 50, 50, 0.30);
	}
	.admin-inputdatalist .ui-menu-item {
	    cursor: pointer;
        font-size: 13px;
    	padding: 10px 10px 9px;
	    margin: 0;
	}
	.admin-inputdatalist .ui-autocomplete .ui-menu-item:hover {
	    background-color: #eee;
	}
</style>



<form id="phoenix" method="POST" action="<?=$APPLICATION->GetCurPage()?>?lang=<?=LANGUAGE_ID?>&site_id=<?=$siteId?>" ENCTYPE="multipart/form-data" name="landphoenix">

	<input name="tab" type="hidden" value="<?=htmlspecialcharsbx($_REQUEST["tab"])?>">
<?





echo bitrix_sessid_post();

$tabControl->Begin();


// disain
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FAVICON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_DESCRIPTOR"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_DESCRIPTOR_BACKDROP"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_LOGO"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_LIGHT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_MOB"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_MOB_LIGHT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["LOGO_EXTRASIZE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]);


CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FONTS"]["HEAD_TITLE"]);


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FONTS"]["TITLE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FONTS"]["TEXT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["MAIN_COLOR_STD"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["MAIN_COLOR"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["FONT_COLOR"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_TONE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["COLOR_HEADER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BTN_VIEW"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["DESIGN"]["TITLE_HEAD_EDIT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_VIEW"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_COLOR"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_OPACITY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG"]);


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_COVER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_FIXED"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_HTML"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_MAIN"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_SCROLL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEADER_MOBILE"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["DESIGN"]["TITLE_HEAD_BG_XS_FOR_PAGES"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES_MODE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["HEAD_BG_XS_FOR_PAGES"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["DESIGN"]["TITLE_BODY_BG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG_CLR"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG_POS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["BODY_BG_REPEAT"]);


// contacts
$tabControl->BeginNextTab();


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_SHOW"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["CALLBACK_NAME"]);


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['CALLBACK']);

?>


<tr><?addListMultyRow($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_CONTACTS"]);?></tr>

<tr><?addListMultyRow($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["HEAD_EMAILS"]);?></tr>


<?

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["ADDRESS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["DIALOG_MAP"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CONTACTS"]["TITLE_SOC_EDIT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["VK"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["FB"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["TW"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["YOUTUBE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["INST"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["TELEGRAM"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["OK"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["TIKTOK"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["GROUP_POS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SOC_GROUP_ICON']);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CONTACTS"]["TITLE_WIDGETS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["SHARE_ON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["WIDGET_FAST_CALL_ON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["WIDGET_FAST_CALL_NUM"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["WIDGET_FAST_CALL_DESC"]);


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARE_DETAIL_CATALOG_ON']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]['SHARES_COMMENT_FOR_DETAIL_CATALOG']);


CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CONTACTS"]["TITLE_PHOENIX_MASK"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["MAIN_MASK"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["USER_MASK"]);



	
//menu
$tabControl->BeginNextTab();


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TYPE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_VIEW"]);

//CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["VIEW_SECOND_LVL_FOR_CATALOG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_COLOR"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_BG_OPACITY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_TEXT_COLOR"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MOBILE_MENU_TONE"]);


CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["MENU"]["TITLE_VIEW_MENU"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["CATALOG_COUNT_SHOW"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["OTHER_COUNT_SHOW"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["LINK_SUBSECTIONS_BTN_NAME"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["DROPDOWN_MENU_WIDTH"]);

//CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["MENU_FIXED_VIEW"]);


// footer
$tabControl->BeginNextTab();


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_ON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_MAIN"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["TEXT_COLOR"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_DESC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_INFO"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_BG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COLOR_BG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_OPACITY_BG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["HIDE_BG_TONE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_ON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_TYPE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_DESC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_PIC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["FOOTER_COPYRIGHT_USER_URL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_1_URL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["BANNER_2_URL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["SUBSCRIPE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["SUBSCRIPE_DESCRIPTION"]);

//CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["SUBSCRIPE_RUBRICS_SHOW"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_PIC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_URL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FOOTER"]["ITEMS"]["PAYMENT_HINT"]);


// catalog
$tabControl->BeginNextTab();



// CPhoenix::getOptionHtml($arFields);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["CUSTOM_URL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["NEWS_COUNT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["DEFAULT_LIST_VIEW"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["OFFERS_QUANTITY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE_OFFERS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["HIDE_EMPTY_CATALOG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_PERCENT"]);

//CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["MENU"]["ITEMS"]["SUBMENU_MAX_QUANTITY_SECTION"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TYPE_PRICE_SORT']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CONVERT_CURRENCY']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CURRENCY_ID']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_PRICE_COUNT']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_AVAILABLE_PRICE_COUNT']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['OFFER_FIELDS']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MEASURE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORIES"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TAB_SECTIONS_SHOW']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_FILTER']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SORT_LEFT_SIDE']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_SHOW']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["FILTER_HIDE_PRICE"]);

//CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_SCROLL']);

//CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILTER_IN_STOCK']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SHOW_DEACTIVATED_GOODS"]);


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DELAY_ON']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['ZOOM_ON']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["ORDER_BTN_NAME"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LINK_2_DETAIL_PAGE_NAME_OFFER_MOB"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["PIC_IN_HEAD"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HEAD_BG_PIC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MAIN_SECTIONS_LIST"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSECTIONS_HIDE_COUNT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBTITLE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TOP_TEXT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["BOT_TEXT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["LABELS_MAX_COUNT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["WATERMARK"]);

//CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["VIEW_XS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_ON']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_VIEW']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_MANY']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['STORE_QUANTITY_FEW']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['PREVIEW_TEXT_POSITION']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['USE_BTN_SCROLL2CHARS']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['CHARS_SHOW_COUNT']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['FILES_SHOW_COUNT']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DESC_FOR_ACTUAL_PRICE']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['TIT_FOR_QUANTITY']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['COMM_FOR_QUANTITY']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['COMMENT_FOR_DETAIL_CATALOG']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["FORMS"]["ITEMS"]['BETTER_PRICE']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["USE_RUB"]);

/*CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHARES']);*/


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SECTION_SORT_LIST"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CATALOG"]["TITLE_SUBSCRIBE_PRODUCT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSCRIBE_BTN_NAME"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SUBSCRIBED_BTN_NAME"]);



// SKU
$tabControl->BeginNextTab();


CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CATALOG_ITEM_FIELDS"]["SECTION_NAME_LIST"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_SKU_FIELDS']);



CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['LIST_CHARS_FIELDS']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['SKU_HIDE_IN_LIST']);



CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['PROPS_IN_LIST_FOR_FLAT']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['PROPS_IN_LIST_FOR_LIST']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['PROPS_IN_LIST_FOR_TABLE']);



CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CATALOG_ITEM_FIELDS"]["SECTION_NAME_DETAIL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['SHOW_PREDICTION']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_SKU_FIELDS']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG_ITEM_FIELDS"]["ITEMS"]['DETAIL_CHARS_FIELDS']);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CATALOG"]["TITLE_DETAIL_PAGE_SORT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_ADV']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_CHAR']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_GALLERY']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_VIDEO']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_REVIEW']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_FAQ']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_INFO_1']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_INFO_2']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_INFO_3']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_EMPL']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_GOODS']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_CATEGORY']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_ACCESSORIES']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_RATING']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_COMPLECT']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_PAGE_SORT_SET']);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CATALOG_ITEM_FIELDS"]["SECTION_NAME_COMMON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_SORT_SKU_CHARS']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_SORT_PROPS_CHARS']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]['DETAIL_SORT_PROP_CHARS']);

// rating
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['USE_VOTE']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['USE_REVIEW']);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["RATING"]["SECTION_NAME_VIEW_FULL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['REVIEW_MODERATOR']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['SEND_NEW_REVIEW']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["EMAIL_TO"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RATING_SIDEMENU_SHOW']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RATING_SIDEMENU_NAME']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RATING_BLOCK_TITLE']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RATING_HIDE_BLOCK_TITLE']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['REVIEW_BLOCK_TITLE']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['REVIEW_HIDE_BLOCK_TITLE']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['RECOMMEND_HINT']);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["RATING"]["SECTION_NAME_VIEW_FULL_FLY_PANEL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['FLY_HEAD_BG']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['FLY_PANEL_DESC']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]['FLY_PANEL_SUCCESS_MESS']);


// comments
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['PAGES']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['USERS_ACCESS']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['MODERATOR']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['ADMIN_EMAIL_NOTIFY']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['EMAIL_TO']);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMMENTS"]["ITEMS"]['FORM_EMAIL_ACTIVE']);

// brands
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["CUSTOM_URL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["NEWS_COUNT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["BG_PIC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["DESC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BRANDS"]["ITEMS"]["SEO_TEXT"]);


// main shop
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_PAGES"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["SHOW_PRODUCTS_IN_ORDER"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SHOP"]["TITLE_CARTMINI_CART"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_URL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_MODE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_LINK_PAGE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_DESC_EMPTY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MINICART_DESC_NOEMPTY"]);


CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SHOP"]["TITLE_CART_BASE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MIN_SUM"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_FORM_MINPICE_ALERT"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SHOP"]["TITLE_CART_DESIGN"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_HEAD_BG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_HEAD_TIT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADD_NAME"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ADDED_NAME"]);


CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SHOP"]["TITLE_CART_OTHER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_FILTER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["AGREEMENTS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_LINK_CONDITIONS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_NAME_CONDITIONS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ADVS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_ADD_ON"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SHOP"]["TITLE_CART_PAGE_CART"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_PAGE_TITLE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_PAGE_HEADBG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_PAGE_EMPTY_MESS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["TEMPLATE_BASKET"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["COUPON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["GIFTS_ON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["SHOW_DELIVERY_PARENT_NAMES"]);

// order
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PAY_FROM_ACCOUNT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ONLY_FULL_PAY_FROM_ACCOUNT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["DELIVERY_TO_PAYSYSTEM"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["TEMPLATE_LOCATION"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["SHOW_ZIP_INPUT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ALLOW_NEW_PROFILE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ALLOW_USER_PROFILES"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["BASKET_POSITION"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_ORDER_NAME"]);

/*CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_PRE_ORDER_NAME"]);*/

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_COMMENT"]);

/*CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SHOP"]["TITLE_CART_NOTIFIC"]);*/

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["ORDER_COMPLITED_MESS"]);

/*CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_PAGE_ORDER_COMPLITED_MESS"]);
*/

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["TEMPLATE_ORDER_PAGE"]);


// fast order
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_BASKET_ON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_BASKET_ONLY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_IN_PRODUCT_ON"]);


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PERSON_TYPE"]);

/*CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["DELIVERY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["PAY_SYSTEM"]);*/


if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS']))
{
	if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS']))
	{
		foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS']['ITEMS'] as $value)
		{
			CPhoenix::getOptionHtml($value);
		}
	}
}

if(isset($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS']))
{
	if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS']))
	{
		foreach ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]['PERSON_TYPE_PROPS_REQ']['ITEMS'] as $value) {
			CPhoenix::getOptionHtml($value);
		}
	}
}

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_BASKET"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_BTN_FAST_ORDER_NAME_IN_CATALOG_DETAIL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_FORM_SUBTITLE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["FAST_ORDER_COMPLITED_MESS"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SHOP"]["TITLE_CART_NOTIFIC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MESS_THEME_ADMIN"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MESS_ADMIN"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MESS_THEME_USER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SHOP"]["ITEMS"]["CART_MESS_USER"]);


CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["CATALOG"]["TITLE_MODE_PREORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["AUTO_MODE_PREORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_FORM"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["MODE_PREORDER_BTN_NAME"]);


// compare
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["ACTIVE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["DESC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["BG_PIC"]);



if(Bitrix\Iblock\Model\PropertyFeature::isEnabledFeatures())
	CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["WARNINGS"]["PROPS"]);
else
{
	CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["SKU"]);

	CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["COMPARE"]["ITEMS"]["PROPS"]);
}
	



// blog
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CUSTOM_URL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["MORE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["NEWS_COUNT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["PIC_IN_HEAD"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["BG_PIC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["DESC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["TOP_TEXT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["BOT_TEXT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["BOT_TEXT_POS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["BANNERS"]);
	

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_BLOG_ICON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_BLOG_NAME"]);


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_VIDEO_ICON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_VIDEO_NAME"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_INTERVIEW_ICON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_INTERVIEW_NAME"]);


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_OPINION_ICON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_OPINION_NAME"]);


CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_CASE_ICON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_CASE_NAME"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_SENS_ICON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["CATEGORY_SENS_NAME"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["ITEMS"]["SIDE_MENU_HTML"]);

// news
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["CUSTOM_URL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["MORE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["NEWS_COUNT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["PIC_IN_HEAD"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["BG_PIC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["DESC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["TOP_TEXT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["BOT_TEXT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["BOT_TEXT_POS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["BANNERS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["ITEMS"]["SIDE_MENU_HTML"]);


// actions
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["CUSTOM_URL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["MORE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["NEWS_COUNT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["PIC_IN_HEAD"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["BG_PIC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["DESC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["TOP_TEXT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["BOT_TEXT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["BOT_TEXT_POS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["BANNERS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["ITEMS"]["SIDE_MENU_HTML"]);


// search
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["ACTIVE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_PAGE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SEARCH_IN"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SHOW_IN"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["QUEST_IN"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["SKIP_CATALOG_PREVIEW_TEXT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["NOT_FOUND"]);



CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]['SEARCH']["SECT_HINT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_DEFAULT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_CATALOG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_BLOG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_NEWS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HINT_ACTIONS"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY['ADMIN']['SEARCH']["SECTION_NAME_DESIGN"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["HEAD_BG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["CATALOG_IC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["BLOG_IC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["NEWS_IC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["ACTIONS_IC"]);


CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY['ADMIN']['SEARCH']["SECTION_NAME_PLACEHOLDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["PLACEHOLDER_CATALOG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["PLACEHOLDER_BLOG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["PLACEHOLDER_NEWS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["PLACEHOLDER_ACTIONS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SEARCH"]["ITEMS"]["FASTSEARCH_ACTIVE"]);


// personal
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CABINET"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SECTIONS"]);

?>
<tr><?addListMultyRow($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["CUSTOM_PAGES"]);?></tr>
<?

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["FORM_AUTH_SUBTITLE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["FORM_PIC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["HEAD_BG_PIC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_ORDERS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_ORDERS_HISTORY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_ORDERS_ACCOUNT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_PRIVATE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_PROFILE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_BASKET"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["COMMENT_SUBSCRIBE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["FIRE_TITLE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SHOW_DISCSAVE"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["PERSONAL"]["TITLE_FIX_PRICE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["ACCOUNT_PERSON_TYPE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["SHOW_FIX_PRICE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["PERSONAL"]["ITEMS"]["FIX_PRICE_VALUES"]);



// lids
$tabControl->BeginNextTab();

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["LIDS"]["TITLE_MAIL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_TO"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["EMAIL_FROM"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["LIDS"]["TITLE_SAVE_IB"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["SAVE_IN_IB"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["LIDS"]["TITLE_BX24"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_ON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_URL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_ASSIGNED_BY_ID"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["LIDS"]["TITLE_BX_WEB_HOOK"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_ID"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_WEB_HOOK"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["LIDS"]["TITLE_BX_STANDART"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_LOG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_PAS"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["LIDS"]["TITLE_AMO"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["SEND_TO_AMO"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_URL"]);

/*CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_TOKEN"]);*/

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_LOG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_HASH"]);




// analitycs
$tabControl->BeginNextTab();



CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_ANALITICS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SERVICE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SERVICE_TIME"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_HEAD"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_BODY"]);


CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_OTHER_SERVICES"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INHEAD"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INBODY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["INCLOSEBODY"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_SCRIPTS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SCRIPTS_TIME"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["LAZY_SCRIPTS"]);


// analitycs
$tabControl->BeginNextTab();

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_GOALS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA_GOAL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_CATEGORY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_ACTION"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_EVENT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_CATEGORY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_ACTION"]);

// 

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_GOALS_ADD2BASKET"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA_GOAL_ADD2BASKET"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_CATEGORY_ADD2BASKET"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_ACTION_ADD2BASKET"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_EVENT_ADD2BASKET"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_CATEGORY_ADD2BASKET"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_ACTION_ADD2BASKET"]);

// 
CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_GOALS_ORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA_GOAL_ORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_CATEGORY_ORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_ACTION_ORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_EVENT_ORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_CATEGORY_ORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_ACTION_ORDER"]);
// 

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["SERVICES"]["TITLE_GOALS_FAST_ORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA_GOAL_FAST_ORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_CATEGORY_FAST_ORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_ACTION_FAST_ORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_EVENT_FAST_ORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_CATEGORY_FAST_ORDER"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_ACTION_FAST_ORDER"]);
// 

// politic
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["POLITIC_DESC"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["ITEMS"]["POLITIC_CHECKED"]);

// store
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["LIST"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["STORE"]["ITEMS"]["SHOW_RESIDUAL"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["STORE"]["TITLE_DETAIL_PAGE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SHOW_STORE_BLOCK"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["STORE_BLOCK_VIEW"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SHOW_EMPTY_STORE"]);



// region

$tabControl->BeginNextTab();

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["REGION"]["TITLE_ALERT_INSTRUCTION"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["ACTIVE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["USE_404"]);

//CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["USE_IP"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["DOMEN_DEFAULT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["LOCATION_LIST"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["SHOW_IN_HEAD"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["REGION"]["TITLE_POPUP_WINDOW"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["BG"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["COMMENT_FOR_FORM"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["REGION"]["TITLE_APPLY_POPUP_WINDOW"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["SHOW_APPLY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["BG_SMALL"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["REGION"]["ITEMS"]["COMMENT_FOR_APPLY"]);





// customs
$tabControl->BeginNextTab();

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]["STYLES"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CUSTOMS"]["ITEMS"]["SCRIPTS"]);





// other
$tabControl->BeginNextTab();



CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DATE_FORMAT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["NAME_SITE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["AFTER_EPILOG_CLEAR_CACHE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["ADD_SITE_TITLE"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MODE_FAST_EDIT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SCROLLBAR"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["OTHER"]["TITLE_SITE_BUILD"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SITE_BUILD_ON"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SITE_BUILD_TEXT"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SITE_BUILD_LINK"]);


CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["OTHER"]["TITLE_BG_404"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["BG_404"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["MES_404"]);


CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["OTHER"]["TITLE_OPTIMIZIER_OPTIONS"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["COMPRESS_HTML"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["DELETE_BX_TECH_FILES"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["TRANSFER_CSS_TO_PAGE"]);

CPhoenix::getTitleHtml($PHOENIX_TEMPLATE_ARRAY["ADMIN"]["OTHER"]["TITLE_GOOGLE_RECAPTCHA"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SITE_KEY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SECRET_KEY"]);

CPhoenix::getOptionHtml($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["CAPTCHA_SCORE"]);
?>


<?
$tabControl->Buttons();?>

<?if($GLOBALS["APPLICATION"]->GetGroupRight($moduleID) > "R"):?>
    <input type="submit" name="save" value="<?=Loc::GetMessage("MODULE_PHOENIX_BUTTON_SAVE_VALUE")?>" title="<?=Loc::GetMessage("MODULE_PHOENIX_BUTTON_SAVE_TITLE")?>" />
<?endif;?>


<?


$tabControl->End();
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>