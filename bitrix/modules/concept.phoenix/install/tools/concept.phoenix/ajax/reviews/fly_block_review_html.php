<?$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if(CModule::IncludeModule("iblock") && CModule::IncludeModule("concept.phoenix")){

	global $PHOENIX_TEMPLATE_ARRAY;

	CPhoenix::phoenixOptionsValues($site_id, array('rating','politic'));
	?>

	<?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_REVIEW"]["VALUE"]["ACTIVE"] == "Y"):
	    CPhoenix::setMess(array("review"));


	    $headBG = "";

	    if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["FLY_HEAD_BG"]["VALUE"])>0)
	    {
	    	$img = CFile::ResizeImageGet($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["FLY_HEAD_BG"]["VALUE"], array('width'=>800, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, true);
	    	$headBG = $img["src"];
	    	unset($img);
	    }

	

		$userID = CPhoenix::GetUserID();

		$userName = "";
		$userEmail = "";

		if($userID)
		{
			$rsUser = CUser::GetByID($userID);
	        if($arUser = $rsUser->Fetch())
	        {
	       		$userName = $arUser["NAME"];

	       		/*if($arUser["LAST_NAME"])
	       			$userName += $arUser["LAST_NAME"];*/

	       		if(strlen($arUser["EMAIL"])>0)
					$userEmail = $arUser["EMAIL"];
	        }

		}

	    $productName = '';

	    if(isset($_POST["params"]["NAME"]))
	    {
	    	$productName = $_POST["params"]["NAME"];
	    	
	    	if(trim(SITE_CHARSET) == "windows-1251")
	    		$productName = utf8win1251($productName);

	    	$productName = htmlspecialcharsbx($productName);
	    }

	    $productPicSrc = '';
	    if(isset($_POST["params"]["PICTURE_SRC"]))
	    	$productPicSrc = htmlspecialcharsbx($_POST["params"]["PICTURE_SRC"]);

		$productID = "";
		if(isset($_POST["params"]["PRODUCT_ID"]))
	    	$productID = htmlspecialcharsbx($_POST["params"]["PRODUCT_ID"]);
	?>

	    <div class="fly-block bgclr-dark" data-fly-block-id="review">

	        <form class="form-review h-100">

	            <div class="row no-gutters h-100">

	                <div class="col-12 align-self-start">

	                    <div class="head" <?=(strlen($headBG)>0)?"style=\"background-image: url('".$headBG."')\" ":"";?>>
	                        <div class="ghost-shadow"></div>

	                        <div class="row row-title no-margin">

	                            <div class="col-auto image"><div></div></div>
	                            <div class="col title align-self-center"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["MAIN_TITLE"]?></div>
	                            <div class="col-auto"><a class="btn-close close-fly-block" data-fly-block-id="review"></a></div>

	                        </div>

	                        
	                    </div>

	                    <div class="body">

	                    	<div class="wr-panel-process">

		                    	<?if(strlen($productName)>0):?>

			                        <div class="row-section">
			                            <div class="product-list-item-simple row align-items-center">
			                                <div class="col-auto">
			                                    <div class="photo row no-gutters align-items-center">
			                                    	<?if(strlen($productPicSrc)>0):?>
				                                    	<div class="col-12">
				                                        	<img src="<?=$productPicSrc?>" alt="">
				                                        </div>
			                                        <?endif;?>
			                                    </div>
			                                </div>
			                                <div class="col">
			                                    <div class="name bold">
			                                        <?=$productName?>
			                                    </div>
			                                </div>
			                                
			                            </div>
			                        </div>

		                        <?endif;?>

		                        <div class="row row-section">

		                            <?if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["USE_VOTE"]["VALUE"]["ACTIVE"] == "Y"):?>

		                                <div class="col-md-6 col-12 wr-rating">
		                                    <div class="title"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["VOTE_PANEL_NAME"]?><i class="simple-hint fa fa-exclamation-circle hidden-sm hidden-xs main-colot-text" data-toggle="tooltip-ajax" data-placement="bottom" title="" data-original-title='<?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["VOTE_PANEL_REQUIRE"]?>'></i></div>
		                                    <?=CPhoenix::GetRatingContainerVoteHTML(array("ID"=>$productID, "CLASS"=>"full-rating hover"));?>
		                                </div>

		                            <?endif;?>

		                            <div class="col-md-6 col-12">
		                                <div class="title"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_1_TITLE"]?></div>

		                                <label class="btn-radio-simple">
		                                    <input type="radio" name="RECOMMEND" value="Y">
		                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_1_VAL_1"]?>
		                                </label>
		                                <label class="btn-radio-simple">
		                                    <input type="radio" name="RECOMMEND" value="N">
		                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_1_VAL_2"]?>
		                                </label>
		                                
		                            </div>
		                            
		                        </div>


		                        <div class="row row-section">
		                            <div class="col-12">
		                                <div class="title"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_2_TITLE"]?></div>
		                                <label class="btn-radio-simple">
		                                    <input type="radio" name="EXPERIENCE" value="D">
		                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_2_VAL_1"]?>
		                                </label>
		                                <label class="btn-radio-simple">
		                                    <input type="radio" name="EXPERIENCE" value="M">
		                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_2_VAL_2"]?>
		                                </label>
		                                <label class="btn-radio-simple">
		                                    <input type="radio" name="EXPERIENCE" value="Y">
		                                    <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_2_VAL_3"]?>
		                                </label>
		                            </div>

		                        </div>

		                        <div class="row row-section">
		                            <div class="col-12">

		                                <div class="title"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_3_TITLE"]?></div>

		                                <div class="textarea-simple">

		                                    <div class="bg"></div>

		                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_3_VAL_1"]?></span>

		                                    <textarea class="focus-anim text-require" name="TEXT"></textarea>
		                                </div>

		                            </div>

		                            <div class="col-md-6 col-12">
		                                <div class="textarea-simple left-col">

		                                    <div class="bg"></div>

		                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_3_VAL_2"]?></span>

		                                    <textarea class="focus-anim" name="POSITIVE"></textarea>
		                                </div>
		                            </div>

		                            <div class="col-md-6 col-12">
		                                <div class="textarea-simple right-col">

		                                    <div class="bg"></div>

		                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["ROW_3_VAL_3"]?></span>

		                                    <textarea class="focus-anim" name="NEGATIVE"></textarea>
		                                </div>
		                            </div>

		                            <div class="col-12">

		                                <label class="load-file-simple">

		                                    <div class="area-files-name">

		                                        <span><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["LOAD_IMAGES"]?></span>

		                                    </div>

		                                    <input class="hidden load-file-simple" name="IMAGES_ID[]" type="file" multiple="">

		                                
		                                </label>

		                            </div>

		                        </div>


		                        <div class="row row-section">

		                            <div class="col-md-6 col-12">

		                                <div class="input-simple left-col <?=(strlen($userName)>0)?"in-focus":""?>"> 

		                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["USER_NAME"]?></span> 

		                                    <input type="text" class="focus-anim text-require" name="USER_NAME" value="<?=$userName?>">

		                                </div>
		                            </div>

		                            <div class="col-md-6 col-12">

		                                <div class="input-simple right-col"> 

		                                    <span class="desc"><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["USER_ACCOUNT_NUMBER"]?></span> 

		                                    <input type="text" class="focus-anim" name="ACCOUNT_NUMBER">

		                                </div>
		                            </div>
		                        </div>

		                        <?= $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["POLITIC"]["AGREEMENT_FOR_FORMS_HTML_FROM_MODAL"]?>


	                    	</div>

	                    	<div class="wr-panel-success d-none">

	                    		<div class="panel-success-mess"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["FLY_PANEL_SUCCESS_MESS"]["~VALUE"]?></div>
	                    		
	                    	</div>

	                    </div>

	                </div>

	                <div class="footer col-12 align-self-end">

	                    <div class="row no-gutters">
	                        <div class="col-md-5 col-12">
	                            
	                            <div class="preloader-simple">
	                                <div class="xLoader form-preload"><div class="audio-wave"><span></span><span></span><span></span><span></span><span></span></div></div>
	                            </div>

	                            <button class="active btn-submit-review" name="form-submit" type="button" value=""><?=$PHOENIX_TEMPLATE_ARRAY["MESS"]["REVIEW"]["BTN_NAME"]?></button>
	                        </div>
	                        <?if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["FLY_PANEL_DESC"]["VALUE"])>0):?>
	                        	<div class="col-md-7 col-12 desc align-self-center"><?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["FLY_PANEL_DESC"]["~VALUE"]?></div>
	                        <?endif;?>
	                    </div>

	                </div>
	            </div>

	            <input type="hidden" name="PRODUCT_ID" value="<?=$productID?>">
	            <input type="hidden" class="review-moderate-mode" value="<?=$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["RATING"]["ITEMS"]["REVIEW_MODERATOR"]["VALUE"]["ACTIVE"]?>">
	        
	        </form>
	    </div>
	<?endif;?>

<?}?>
