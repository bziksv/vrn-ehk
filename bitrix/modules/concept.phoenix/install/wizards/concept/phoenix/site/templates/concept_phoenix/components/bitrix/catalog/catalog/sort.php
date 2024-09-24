<?	
$arAvailableSort = array();

$arSorts = Array("SORT", "PRICE", "NAME");

if(in_array("SORT", $arSorts)){
	$arAvailableSort["SORT"] = array("SORT", "desc");
}
/*if(in_array("PRICE", $arSorts)){ 
    $arAvailableSort["PRICE"] = array("PROPERTY_MAXIMUM_PRICE", "desc"); 
}*/
if(in_array("PRICE", $arSorts)){ 
    $arAvailableSort["PRICE"] = array("CATALOG_PRICE_SCALE_".$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["TYPE_PRICE_SORT"]["VALUE"], "desc"); 
}
if(in_array("NAME", $arSorts)){
    $arAvailableSort["NAME"] = array("NAME", "desc");
}
if(in_array("QUANTITY", $arSorts)){
	$arAvailableSort["CATALOG_AVAILABLE"] = array("QUANTITY", "desc");
}



$arSortVal = explode("_", $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SECTION_SORT_LIST"]["VALUE"]);

$sort2 = $arSortVal[0];
$sort_order2 = $arSortVal[1];



if($_REQUEST["sort"]){
	$sort2 = ToUpper($_REQUEST["sort"]); 
	$_SESSION["sort"] = ToUpper($_REQUEST["sort"]);
}
elseif($_SESSION["sort"]){
	$sort2 = ToUpper($_SESSION["sort"]);
}

if($_REQUEST["order"]){
    
	$sort_order2 = $_REQUEST["order"];
	$_SESSION["order"] = $_REQUEST["order"];
}
elseif($_SESSION["order"]){
	$sort_order2 = $_SESSION["order"];
}

?>

<div class="element-sort">
    
    <?foreach($arAvailableSort as $key => $val):?>
        
		<?$newSort = $sort_order2 == 'desc' ? 'asc' : 'desc';
        
        /*$current_url = explode("?", $_SERVER["REQUEST_URI"]);
        $current_url = $current_url[0];
        
        $add = DeleteParam(array("sort","order"));
        
        if(strlen($add) > 0)
            $add .= '&sort='.$key.'&order='.$newSort;
        else
            $add .= 'sort='.$key.'&order='.$newSort;
        
		$current_url = $current_url."?".$add;
		$url = str_replace('+', '%2B', $current_url);*/?>
	
	<div class="wrap-sort">
		<a <?/*href="<?=$url;?>#actionbox"*/?> class="sort_btn sort_btn_js <?=($sort2 == $key ? 'active' : '')?> <?=htmlspecialcharsbx($sort_order2)?> <?=$key?>" rel="nofollow" data-sort="<?=$key?>" data-order="<?=$newSort?>">
            
            <?=$PHOENIX_TEMPLATE_ARRAY["MESS"]['SORT_CATALOG_'.$key]?>
           
		</a>
	</div>
        
 	<?endforeach;?>

    
</div>

    
    
    
<?
$tmpsort = $sort2;

if($sort2 == "PRICE"){
	$sort2 = $arAvailableSort["PRICE"][0];
}

if($sort2 == "CATALOG_AVAILABLE"){
	$sort2 = "CATALOG_QUANTITY";
}


   /* 
if($tmpsort == "PRICE" && $sort_order2 == "asc")
{
    $sort2 = "PROPERTY_MINIMUM_PRICE";
    $sort_order2 = "asc";  
}
elseif($tmpsort == "PRICE" && $sort_order2 == "desc")
{
    $sort2 = "PROPERTY_MINIMUM_PRICE";
    $sort_order2 = "desc";  
}*/

$sort1 = "PROPERTY_MODE_ARCHIVE";
$sort_order1 = "asc";


?>