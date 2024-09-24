<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(CModule::IncludeModule("iblock") && CModule::IncludeModule("sale") && CModule::IncludeModule("catalog"))
{
    if($g_recaptcha_response = $_POST['g-recaptcha-response']){
        $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Le8ZZ4aAAAAABGjJMS6tsYCxXZwiWZJ39j9KE1Q&response=".$g_recaptcha_response."&remoteip=".$_SERVER["REMOTE_ADDR"]),true);
        if (($response["success"] && $response["score"] <= 0.7)){
            echo "error";
            return false;
        }
    }else{
        echo "error";
        return false;
    }

	$PROP = array();
	foreach($_POST as $key=>$value)
	{
		$PROP[$key]=strip_tags($value);
	}
	$all_sum=0;
	$user_id=3;
	$items="";
	if($USER->IsAuthorized())
	{
		$user_id=$USER->GetID();
	}
	$dbBasketItems = CSaleBasket::GetList(
        array(
                "NAME" => "ASC",
                "ID" => "ASC"
            ),
        array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"
            ),
        false,
        false,
        array("ID", "PRODUCT_ID", "QUANTITY", "PRICE","NAME","DETAIL_PAGE_URL")
    );
	while ($arItems = $dbBasketItems->GetNext())
	{
		$items.="<a href='".$arItems["DETAIL_PAGE_URL"]."' target='_blank'>".$arItems["NAME"]."</a> --- ".round($arItems["QUANTITY"],0)."шт. --- ".($arItems["QUANTITY"]*$arItems["PRICE"])."руб.<br />";
    	$all_sum+=$arItems["PRICE"]*$arItems["QUANTITY"];
	}
	$arFields = array(
	   "LID" => SITE_ID,
	   "PERSON_TYPE_ID" => 1,
	   "PAYED" => "N",
	   "CANCELED" => "N",
	   "STATUS_ID" => "N",
	   "PRICE" => $all_sum,
	   "CURRENCY" => "RUB",
	   "USER_ID" => $user_id,
	   "PAY_SYSTEM_ID" => 1,
	   "PRICE_DELIVERY" => 0,
	   "DELIVERY_ID" => 1,
	   "DISCOUNT_VALUE" => 0,
	   "TAX_VALUE" => 0.0
	);
	$ORDER_ID = CSaleOrder::Add($arFields);
	$ORDER_ID = IntVal($ORDER_ID);
	if($ORDER_ID)
	{
		CSaleBasket::OrderBasket($ORDER_ID, CSaleBasket::GetBasketUserID(), SITE_ID);
		if ($arOrder = CSaleOrder::GetByID($ORDER_ID))
		{
			$arFields = array(
			   "ORDER_ID" => $ORDER_ID,
			   "ORDER_PROPS_ID" => 1,
			   "NAME" => "Имя",
			   "CODE" => "NAME",
			   "VALUE" => "Быстрый заказ ".$PROP["NAME"]
			);
			CSaleOrderPropsValue::Add($arFields);
			$arFields = array(
			   "ORDER_ID" => $ORDER_ID,
			   "ORDER_PROPS_ID" => 2,
			   "NAME" => "Телефон",
			   "CODE" => "PHONE",
			   "VALUE" => $PROP["PHONE"]
			);
			CSaleOrderPropsValue::Add($arFields);
			$arFields = array(
			   "ORDER_ID" => $ORDER_ID,
			   "ORDER_PROPS_ID" => 3,
			   "NAME" => "E-mail",
			   "CODE" => "EMAIL",
			   "VALUE" => $PROP["EMAIL"]
			);
			CSaleOrderPropsValue::Add($arFields);
			$arEventFields = array(
				"NAME"=>$PROP["NAME"],
				"EMAIL"=>$PROP["EMAIL"],
				"PHONE"=>$PROP["PHONE"],
				"ITEMS"=>$items,
				"SUMM"=>$all_sum,
				"DATE"=>date("d.m.Y H:i:s")
			);
			CEvent::Send("FAST_ORDER", "s1", $arEventFields,"Y");
			CEvent::CheckEvents();?>
			<div class="ok_message">
				<div class="title">
					Ваша заказ №<?=$arOrder["ACCOUNT_NUMBER"]?> принят!
				</div>
				<div class="mess">В ближайшее время с вами свяжется наш менеджер для уточнения деталей заказа. Спасибо, что выбрали наш магазин!</div>
			</div>
		<?}
		else
		{
			echo "error";
		}
	}
	else
	{
		$errors="error";
	}
}?>
