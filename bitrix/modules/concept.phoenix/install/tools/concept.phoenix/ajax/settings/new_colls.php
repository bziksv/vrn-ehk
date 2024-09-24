<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
	die();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');


    


CModule::IncludeModule('iblock');
CModule::IncludeModule('main');

if($_POST["send"] == "Y")
{
	global $PHOENIX_TEMPLATE_ARRAY;
	$moduleID = "concept.phoenix";
    CModule::IncludeModule("concept.phoenix");
    CPhoenix::phoenixOptionsValues($site_id, array(
        "start"
    ));
    
    if(!$PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"])
        die();

	$id = trim($_POST["element_id"]);
	$code = trim($_POST["code"]);
	$view = trim($_POST["view"]);
	$type = trim($_POST["type"]);


	if($type='element')
	{
		$PROPERTY_CODE = $code;
		$PROPERTY_VALUE = $view;

		CIBlockElement::SetPropertyValuesEx($id, false, array($PROPERTY_CODE => $PROPERTY_VALUE));

	}


	if($type='section')
	{
	    $bs = new CIBlockSection;
	    
	    $arFields = Array(
	    	"UF_PHX_CTLG_SIZE" => $view,
	    );
	    
	    $bs->Update($id, $arFields); 
	}


	BXClearCache(true);
}
?>




