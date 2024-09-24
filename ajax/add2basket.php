<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?if(CModule::IncludeModule("iblock") && CModule::IncludeModule("sale") && CModule::IncludeModule("catalog") && isset($_GET["ID"]) && !empty($_GET["ID"]))
{
	global $USER;
	if (!$USER->IsAuthorized())
		return print "authorized";
	
	$good_quantity = false;
	$quantity=1;
	if(isset($_GET["quantity"]) && !empty($_GET["quantity"]))
	{
		$quantity=IntVal($_GET["quantity"]);
	}
	$ar_fix_item=CIBlockElement::GetByID($_GET["ID"]);
	if($fix_item=$ar_fix_item->GetNext())
	{
		$ar_item=CIBlockElement::GetList(
			array(),
			array("IBLOCK_ID"=>$fix_item["IBLOCK_ID"],"ACTIVE"=>"Y","ID"=>$fix_item["ID"]),
			false,
			false,
			array("ID","NAME","DETAIL_PAGE_URL","XML_ID","CATALOG_GROUP_1","PROPERTY_CML2_LINK","PROPERTY_CML2_ARTICLE", "PROPERTY_CML2_BASE_UNIT", "CATALOG_QUANTITY")
		);
		if($item=$ar_item->GetNext())
		{
			
				$b_quantity = CSaleBasket::GetList(
	        		array(
		                "NAME" => "ASC",
	            	),
	       		 	array(
	                	"PRODUCT_ID" => $item["ID"],
	                	"FUSER_ID" => CSaleBasket::GetBasketUserID(),
	                	"ORDER_ID" => "NULL"
	            	),
		        	false,
		        	false,
		        	array("ID", "NAME", "QUANTITY", "COUNT")
	        	);
	        	$b_quantity = $b_quantity -> Fetch();

	        	if ($b_quantity){
	        		if(($b_quantity["QUANTITY"] + $quantity) <= $item["CATALOG_QUANTITY"])
	        		{
	        			$good_quantity = true;
	        		}
	        	}else{
	        		if($quantity <= $item["CATALOG_QUANTITY"])
	        		{
	        			$good_quantity = true;
	        		}
	        	}

        	if($good_quantity)
			{
				if($item["IBLOCK_ID"]==21)
				{
					$discount_price=My::GetMinPrice($item["ID"],1);
					$price=$item["CATALOG_PRICE_1"]-$discount_price["PRICE"];
					$arFields = array(    
						"PRODUCT_ID" => $item["ID"],    
						"PRODUCT_PRICE_ID" => 1,    
						"PRODUCT_PROVIDER_CLASS" => "\Bitrix\Catalog\Product\CatalogProvider",	
						"MODULE" => "catalog",
						"WEIGHT" => $item["CATALOG_WEIGHT"],    
						"PRICE" => $discount_price["PRICE"],    
						"DISCOUNT_PRICE" => $price, 
						"DISCOUNT_NAME" => $discount_price['DATA']['DISCOUNT']['NAME'], 
						"DISCOUNT_VALUE" => $discount_price['DATA']['DISCOUNT']['VALUE'], 
						"PRODUCT_XML_ID" => $item["XML_ID"],    
						"CATALOG_XML_ID" => $item["IBLOCK_EXTERNAL_ID"],       
						"CURRENCY" => "RUB",      
						"QUANTITY" => $quantity,    
						"LID" => "s1",    
						"DELAY" => "N",    
						"CAN_BUY" => "Y", 
						"PROPS"=>array(
							array(
								"NAME"=>"Артикул",
								"CODE"=>"ARTICLE",
								"VALUE"=>$item["PROPERTY_CML2_ARTICLE_VALUE"]?$item["PROPERTY_CML2_ARTICLE_VALUE"]:"-",
								"SORT"=>100
							),
							array(
								"NAME"=>"Единица измерения",
								"CODE"=>"BASE_UNIT",
								"VALUE"=>$item["PROPERTY_CML2_BASE_UNIT_VALUE"]?$item["PROPERTY_CML2_BASE_UNIT_VALUE"]:"-",
								"SORT"=>200
							)
						),   
						"NAME" => htmlspecialcharsBack($item["NAME"]),       
						"DETAIL_PAGE_URL" => $item["DETAIL_PAGE_URL"] 
					);   
					$id=CSaleBasket::Add($arFields);
					if($id)
					{
						echo "success";
					}
					else
					{
						echo "error";
					}
				}
				else
				{
					if(!empty($item["PROPERTY_CML2_LINK_VALUE"]))
					{
						$ar_parent_item=CIBlockElement::GetList(
							array(),
							array("IBLOCK_ID"=>21,"ACTIVE"=>"Y","ID"=>$item["PROPERTY_CML2_LINK_VALUE"]),
							false,
							false,
							array("ID","NAME","DETAIL_PAGE_URL","XML_ID","CATALOG_GROUP_1","PROPERTY_CML2_LINK","PROPERTY_CML2_ARTICLE")
						);
						if($parent_item=$ar_parent_item->GetNext())
						{
							$discount_price=My::GetMinPrice($item["ID"],1);
							$price=$item["CATALOG_PRICE_1"]-$discount_price["PRICE"];
							$arFields = array(    
								"PRODUCT_ID" => $item["ID"],    
								"PRODUCT_PRICE_ID" => 1,    
								"WEIGHT" => $item["CATALOG_WEIGHT"],    
								"PRICE" => $discount_price["PRICE"],    
								"DISCOUNT_PRICE" => $price, 
								"PRODUCT_XML_ID" => $item["XML_ID"],    
								"CATALOG_XML_ID" => $item["IBLOCK_EXTERNAL_ID"],       
								"CURRENCY" => "RUB",      
								"QUANTITY" => $quantity,    
								"LID" => "s1",    
								"DELAY" => "N",    
								"CAN_BUY" => "Y",
								"PROPS"=>array(
									array(
										"NAME"=>"Артикул",
										"CODE"=>"ARTICLE",
										"VALUE"=>$item["PROPERTY_CML2_ARTICLE_VALUE"]?$item["PROPERTY_CML2_ARTICLE_VALUE"]:"-",
										"SORT"=>100
									),
									array(
										"NAME"=>"Единица измерения",
										"CODE"=>"BASE_UNIT",
										"VALUE"=>$item["PROPERTY_CML2_BASE_UNIT_VALUE"]?$item["PROPERTY_CML2_BASE_UNIT_VALUE"]:"-",
										"SORT"=>200
									)
								),   
								"NAME" => htmlspecialcharsBack($parent_item["NAME"]),       
								"DETAIL_PAGE_URL" => $parent_item["DETAIL_PAGE_URL"]
							);   
							$id=CSaleBasket::Add($arFields);
							if($id)
							{
								echo "success";
							}
							else
							{
								echo "error";
							}
						}
						else
						{
							echo "error";
						}
					}
					else
					{
						echo "error";
					}
				}
			}else{
				echo "Запрашиваемое кол-во превышает остаток. На складе: ".$item["CATALOG_QUANTITY"];
			}
		}
		else
		{
			echo "error";
		}
	}
	else
	{
		echo "error";
	}
}
else
{
	echo "error";
}?>