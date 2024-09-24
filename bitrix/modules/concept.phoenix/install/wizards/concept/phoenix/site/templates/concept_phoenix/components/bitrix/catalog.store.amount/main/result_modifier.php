<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if(!empty($arResult["STORES"]))
{
	global $PHOENIX_TEMPLATE_ARRAY;

	
	foreach($arResult["STORES"] as $pid => $arProperty)
	{

		if(intval($arProperty["REAL_AMOUNT"])<=0 && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["SHOW_EMPTY_STORE"]["VALUE"]["ACTIVE"]!=="Y")
		{

			unset($arResult["STORES"][$pid]);
			continue;
		}
		$arResult["STORES"][$pid]["MEASURE"] = $arParams["MEASURE"];
		$arResult["STORES"][$pid]["QUANTITY"] = CPhoenixSku::getDescQuantity($arProperty["REAL_AMOUNT"], $arParams["MEASURE"]);

		
		$arResult["STORES"][$pid]["QUANTITY"]["DESCRIPTION"] = (strlen($arResult["STORES"][$pid]["QUANTITY"]["QUANTITY_FORMATED"])>0?$arResult["STORES"][$pid]["QUANTITY"]["QUANTITY_FORMATED"]:$arResult["STORES"][$pid]["QUANTITY"]["TEXT"]);

		
		$arResult["STORES"][$pid]["QUANTITY"]["DESCRIPTION_FLAT"] = (strlen($arResult["STORES"][$pid]["QUANTITY"]["QUANTITY_WITH_TEXT"])>0?$arResult["STORES"][$pid]["QUANTITY"]["QUANTITY_WITH_TEXT"]:$arResult["STORES"][$pid]["QUANTITY"]["TEXT"]);


		if(strlen($arProperty["EMAIL"])>0)
			$arResult["STORES"][$pid]["EMAIL_FORMATED"] = "<a href='mailto:".$arProperty["EMAIL"]."'>".$arProperty["EMAIL"]."</a>";
	}


	if(!empty($arResult["STORES"]))
	{
		$dbResult = CCatalogStore::GetList(
	       array('SORT'=>'ASC','ID' => 'ASC'),
	       array('ACTIVE' => 'Y'),
	       false,
	       false,
	       array("ID","SORT")
	    );

	    $arResultStores = array();

	    while($arStoreProduct=$dbResult->fetch())
	    {
	    	$arResultStores[$arStoreProduct['ID']] = $arStoreProduct['SORT'];
	    }


	    $arStoresNew = array();

		foreach($arResult["STORES"] as $pid => $arProperty)
		{
			$arProperty['SORT']=$arResultStores[$arProperty['ID']];
			$arStoresNew[$arProperty['ID']] = $arProperty;
		}

		$arStoresResult = array();

		if(!empty($arResultStores))
		{
			foreach($arResultStores as $pid => $arProperty)
			{
				if(isset($arStoresNew[$pid]))
					$arStoresResult[] = $arStoresNew[$pid];
			}

			unset($arResult["STORES"]);
			$arResult["STORES"] = $arStoresResult;
		}

	}
	
}

