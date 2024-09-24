<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
header('Content-type: application/json');

$arRes["OK"] = "N";

if(Loader::includeModule("sale"))
{

    $inBasket = "N";

    $fUserID = CSaleBasket::GetBasketUserID(True);
    $fUserID = IntVal($fUserID);



    $error = '';

    

    if($_POST['product_id'])
    {
        $rsBasket = CSaleBasket::GetList( 
            array( "NAME" => "ASC", "ID" => "ASC"),
            array("FUSER_ID" => $fUserID,  "LID" => $_POST['site_id'], "ORDER_ID" => NULL, "PRODUCT_ID" => $_POST['product_id']),
                false, false, array("ID", "PRODUCT_ID", "DELAY", "SUBSCRIBE", "CAN_BUY", "QUANTITY") );

        if($arBasket = $rsBasket->GetNext())
            $inBasket = "Y";
        
        
    }
    else
        $error = 'error product_id';


    $arFields = array();

    if($inBasket == "N")
    {
        $name = trim($_POST['name']);
        $detail_page_url = trim($_POST['name']);

        if(SITE_CHARSET == "windows-1251")
        {
            $name = utf8win1251($name);
            $detail_page_url = utf8win1251($detail_page_url);
        }

        $arFields = array(
            "PRODUCT_ID" => $_POST['product_id'],
            "PRODUCT_PRICE_ID" => $_POST['price_id'],
            "PRICE" => $_POST['price'],
            "CURRENCY" => $_POST['currency'],
            "QUANTITY" => $_POST['quantity'],
            "LID" => $_POST['site_id'],
            "DELAY" => "Y",
            "CAN_BUY" => $_POST['can_buy'],
            "NAME" => $name,
            "MODULE" => "sale",
            "DETAIL_PAGE_URL" => $detail_page_url,
            "FUSER_ID" => $fUserID
        );

        if(!$idBasket = CSaleBasket::Add($arFields))
            $error = 'error Add Item Basket';

        $arRes["ACTION"] = "active";
    }

    if($inBasket == "Y")
    {

        if($arBasket["DELAY"] == "Y")
        {
            if (!CSaleBasket::Delete($arBasket["ID"]))
                $error = 'error Delete Item Basket';

            $arRes["ACTION"] = "delete";
        }
        else
        {
            $arFields = array(
                "DELAY" => "Y",
                "QUANTITY" => $_POST['quantity'],
            );

            if(!CSaleBasket::Update($arBasket["ID"], $arFields))
                $error = 'error Update Item Basket';

            $arRes["ACTION"] = "active";
           
                
        }
    }

    if(strlen($error))
        $arRes["OK"] = "wishlist: ".$error;
    else
        $arRes["OK"] = "Y";


    unset($arFields, $inBasket, $fUserID);
}

echo json_encode($arRes);
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");