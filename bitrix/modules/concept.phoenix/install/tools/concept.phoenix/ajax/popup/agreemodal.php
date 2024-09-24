<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();


require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
use \Bitrix\Main\Localization\Loc as Loc;
Loc::loadMessages(__FILE__);
CModule::IncludeModule('iblock');
    
    $element_id = $_POST["arParams"]["element_id"];
	$arItem = array();

	$arFilter = Array("IBLOCK_CODE" => 'concept_phoenix_agreements_'.$site_id, "ID" => $element_id, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, array("ID", "DETAIL_TEXT"));

if($ob = $res->GetNext()){

?>
	
<div class="shadow-modal"></div>

<div class="phoenix-modal window-modal blur-container">

	<div class="phoenix-modal-dialog">
		
		<div class="dialog-content">
            <a class="close-modal"></a>

            <div class="content-in">
				<?=$ob['~DETAIL_TEXT']?>
            </div>

        </div>

	</div>
</div>
<?}?>


