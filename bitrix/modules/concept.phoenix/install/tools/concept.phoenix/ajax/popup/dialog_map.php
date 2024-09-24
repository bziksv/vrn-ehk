<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();
    
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if(CModule::IncludeModule("concept.phoenix"))
{
	global $PHOENIX_TEMPLATE_ARRAY;
	CPhoenix::phoenixOptionsValues(SITE_ID, array(
		"start",
		"contacts",
		"region"
	));
	?>
	<div class="shadow-modal"></div>

	<div class="phoenix-modal window-modal map blur-container">

		<div class="phoenix-modal-dialog">
			
			<div class="dialog-content">
	            <a class="close-modal"></a>

	            <div class="content-in text-content">


	               	<?if (preg_match("<script>", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["DIALOG_MAP"]["VALUE"])):?>
	               
		               <?$map = str_replace("<script ", "<script data-skip-moving='true' ", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["DIALOG_MAP"]["~VALUE"]);?>
		               <?=str_replace("scroll=true", "scroll=false", $map);?>
		             		               
		           <?else:?>
		                   
		               <?if(preg_match("<iframe>", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["DIALOG_MAP"]["VALUE"])):?>
		                   <div class="overlay" onclick="style.pointerEvents='none'"></div>
		               <?endif;?>
		               
		               <?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CONTACTS"]["ITEMS"]["DIALOG_MAP"]["~VALUE"];?>
		                                      
		           <?endif;?>
					
	            </div>

	        </div>

		</div>
	</div>
<?}?>