<?
$arResult["allSum"] = 0;

		foreach($arResult["GRID"]["ROWS"] as $key => &$arItem)
		{
	

$dbBasketItems = CSaleBasket::GetList(
				array("ID" => "ASC"),
				array(
						"FUSER_ID" => CSaleBasket::GetBasketUserID(),
						"LID" => SITE_ID,
						"ORDER_ID" => "NULL",
						"ID" => $key
					),
				false,
				false,
				$arSelFields
			);
		if ($arRes = $dbBasketItems->GetNext())
		{
			$arItem["PRICE"] = $arRes["PRICE"];
			$arResult["allSum"] += $arItem["QUANTITY"]*$arItem["PRICE"];
		
		}	
			
			
		}

	