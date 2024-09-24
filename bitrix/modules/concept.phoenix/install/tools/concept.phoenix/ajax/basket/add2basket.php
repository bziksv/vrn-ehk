<?
$site_id = trim($_POST["site_id"]);

if(strlen($site_id) > 0)
    define("SITE_ID", htmlspecialchars($site_id));
else
    die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader,
    Bitrix\Sale,
    Bitrix\Sale\Order;
header('Content-type: application/json');

$arRes["OK"] = "N";
$arRes["SCRIPTS"] = "";

if(Loader::includeModule("sale") && CModule::IncludeModule("catalog"))
{
	$inBasket = "N";
	$fUserID = CSaleBasket::GetBasketUserID(True);
    $fUserID = IntVal($fUserID);
    $error = '';

  

    if($_POST["productItem"]['PRODUCT_ID'])
    {
        $rsBasket = CSaleBasket::GetList( 
            array( "NAME" => "ASC", "ID" => "ASC"),
            array("FUSER_ID" => $fUserID,  "LID" => $_POST['site_id'], "ORDER_ID" => NULL, "PRODUCT_ID" => $_POST["productItem"]['PRODUCT_ID']),
                false, false, array("ID", "PRODUCT_ID", "DELAY", "SUBSCRIBE", "CAN_BUY", "QUANTITY") );

        if($arBasket = $rsBasket->GetNext())
            $inBasket = "Y";
    }
    else
        $error = 'error product_id';



    if ($inBasket == "Y")
    {
        CSaleBasket::Delete($arBasket["ID"]);
    }

    
   

    $basket = Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(),$site_id);

    $item = $basket->createItem('catalog', $_POST["productItem"]['PRODUCT_ID']);
    
    $item->setFields(array( 
        'QUANTITY' => $_POST["productItem"]['QUANTITY'],
        'LID' => $site_id, 
        'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider'
    ));

    $basket->save();



    if(strlen($error))
        $arRes["OK"] = "add2basket: ".$error;
    else
        $arRes["OK"] = "Y";




    if($arRes["OK"] == "Y")
    {
        if(Loader::includeModule("concept.phoenix"))
        {
            global $PHOENIX_TEMPLATE_ARRAY;

            CPhoenix::phoenixOptionsValues($_POST['site_id'], array("services"));


            $arRes["SCRIPTS"] = CPhoenix::getGoalsScriptsHTML($_POST['site_id'],

                array(
                    "YAGOAL"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["METRIKA_GOAL_ADD2BASKET"]['VALUE'],
                    "GA_CAT"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_CATEGORY_ADD2BASKET"]['VALUE'],
                    "GA_ACT"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GOOGLE_ACTION_ADD2BASKET"]['VALUE'],
                    "GTM_EVT"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_EVENT_ADD2BASKET"]['VALUE'],
                    "GTM_CAT"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_CATEGORY_ADD2BASKET"]['VALUE'],
                    "GTM_ACT"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["SERVICES"]["ITEMS"]["GTM_ACTION_ADD2BASKET"]['VALUE'],
                )

            );
        }
    }
}


echo json_encode($arRes);
