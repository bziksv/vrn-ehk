<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $PHOENIX_TEMPLATE_ARRAY;

$leftSideCols = "col-md-5";
$rightSideCols = "col-md-7";

if(!empty($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BANNERS"]["ITEMS"]))
{
	$leftSideCols = "col-xl-7 col-lg-10 col-md-12";
	$rightSideCols = "col-xl-5 col-lg-10 col-md-12";
}
?>


<div class="subscribe-edit">
<?
if(!empty($arResult["MESSAGE"]))
{
	foreach($arResult["MESSAGE"] as $itemID=>$itemValue)
		echo ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"OK"));
}

if(!empty($arResult["ERROR"]))
{
	foreach($arResult["ERROR"] as $itemID=>$itemValue)
		echo ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"ERROR"));
}



//whether to show the forms
if($arResult["ID"] == 0 && empty($_REQUEST["action"]) || CSubscription::IsAuthorized($arResult["ID"]))
{
	//show confirmation form
	if($arResult["ID"]>0 && $arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y")
	{
		include("confirmation.php");
	}
	//show current authorization section
	if($USER->IsAuthorized() && ($arResult["ID"] == 0 || $arResult["SUBSCRIPTION"]["USER_ID"] == 0))
	{
		include("authorization.php");
	}
	//show authorization section for new subscription
	if($arResult["ID"]==0 && !$USER->IsAuthorized())
	{
		if($arResult["ALLOW_ANONYMOUS"]=="N" || ($arResult["ALLOW_ANONYMOUS"]=="Y" && $arResult["SHOW_AUTH_LINKS"]=="Y"))
		{
			include("authorization_new.php");
		}
	}
	//setting section
	include("setting.php");
	//status and unsubscription/activation section
	if($arResult["ID"]>0)
	{
		include("status.php");
	}
	?>
	<?
}
else
{
	//subscription authorization form
	include("authorization_full.php");
}
?>
</div>