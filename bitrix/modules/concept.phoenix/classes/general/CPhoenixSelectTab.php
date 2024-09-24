<?use \Bitrix\Main\Config\Option as Option;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Loader,
    Bitrix\Main\ModuleManager,
    Bitrix\Iblock,
    Bitrix\Catalog,
    Bitrix\Currency,
    Bitrix\Currency\CurrencyTable,
    Bitrix\Main,
    Bitrix\Sale,
    Bitrix\Sale\Order,
    Bitrix\Main\Application,
    Bitrix\Sale\DiscountCouponsManager,
    Bitrix\Main\Localization\Loc;
class CPhoenixSelectTab{ 
    
   public static function PhoenixSelectTab() 
   {
        if($_SERVER["SCRIPT_NAME"] == "/bitrix/admin/iblock_element_edit.php" || $_SERVER["PHP_SELF"] == "/bitrix/admin/iblock_element_edit.php")
        {

            if(!Loader::includeModule('iblock'))
                return;

            $arSite_id = Array();
            $iBlock = Array();

            $res = CIBlock::GetByID($_REQUEST["IBLOCK_ID"]);
            if($ar_res = $res->GetNext())
                $iBlock["IBLOCK_CODE"] = $ar_res['CODE'];


            $resProp = CIBlock::GetProperties($_REQUEST["IBLOCK_ID"], Array(), Array("CODE"=>"TYPE"));
            if($res_prop = $resProp->Fetch())
                $iBlock["PROP_ID"] = $res_prop["ID"];
            
                

            $rsSites = CSite::GetList($by="sort", $order="desc");
            $isIt = false;

            while ($arSite = $rsSites->Fetch())
            {

                if("concept_phoenix_site_".$arSite["LID"] == $iBlock["IBLOCK_CODE"] && !$isIt)
                    $isIt = true;
                
                if($isIt)
                    break;
            }

            if($isIt)
            {
                echo "<input type='hidden' name='prop_select' value='".$iBlock["PROP_ID"]."'>";

                CJSCore::Init(array('jquery'));
                echo "<script src= '/bitrix/js/concept.phoenix/tabs.js'></script>";
            
            }
           
        }

    
   }
}
?>