<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?if(CModule::IncludeModule("iblock") && CModule::IncludeModule("sale") && CModule::IncludeModule("catalog") && isset($_GET["id"]) && !empty($_GET["id"]))
{
	if($_GET["id"]=="tryes")
	{
		$dbBasketItems = CSaleBasket::GetList(
				array(
						"DATE_INSERT"=>"DESC"
					),
				array(
						"FUSER_ID" => CSaleBasket::GetBasketUserID(),
						"LID" => SITE_ID,
						"ORDER_ID" => "NULL"
					),
				false,
				false,
				array("ID", "DETAIL_PAGE_URL", "MODULE", 
					  "PRODUCT_ID", "QUANTITY", "DELAY", 
					  "CAN_BUY", "PRICE", "DATE_INSERT","NAME","DETAIL_PAGE_URL")
			);
		while ($arItems = $dbBasketItems->Fetch())
		{
			$arFields['PROPS'][] = array(
				"NAME"=>"Для примерки",
				"CODE"=>"FITTING",
				"VALUE"=>0,
				"SORT"=>100
			);
			$result=CSaleBasket::Update($arItems["ID"], $arFields);
			if($result)
			{
				echo "success";
			}
			else
			{
				echo "error";
			}
		}
	}
	else
	{
		if(CSaleBasket::Delete($_GET["id"]))
		{
			echo "success";
		}
		else
		{
			echo "error";
		}
	}
}
else
{
	echo "error";
}?>