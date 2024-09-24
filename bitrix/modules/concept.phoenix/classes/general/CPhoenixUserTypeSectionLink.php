<?
use \Bitrix\Main\Config\Option as Option;
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

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/classes/general/CPhoenix.php");

class CPhoenixUserTypeSectionLink{

    private static $Script_included = false;



    public static function PhoenixGetUserTypeDescription()
    {
        global $iblock_id;

        return array(
            "USER_TYPE_ID" => "SECTION_LINK",
            "CLASS_NAME" => "CPhoenixUserTypeSectionLink",
            "DESCRIPTION" => Loc::GetMessage("PHOENIX_USER_TS_DESCRIPTION"),
            "BASE_TYPE" => "string",
            "PROPERTY_TYPE" => "N",
            "USER_TYPE" => "SECTION_LINK",
            "GetPublicViewHTML" => array("CPhoenixUserTypeSectionLink","PhoenixGetPublicViewHTML"),
            "GetPropertyFieldHtml" => array("CPhoenixUserTypeSectionLink","PhoenixGetPropertyFieldHtml"),
        );
    }

    public static function PhoenixGetPublicViewHTML($arProperty, $value, $strHTMLControlName)
    {
        return $value['VALUE'];
    }

    public function PhoenixGetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {

        $sectionsList=array();
        $ibNames=array();

        $rsIB = CIBlock::GetList();
        while ($ib = $rsIB->GetNext()) {
            $ibNames[$ib['ID']] = $ib['NAME'];
        }

        if(!empty($_REQUEST['PARAMS']))
            $code = $_REQUEST['PARAMS']['IBLOCK_ID'];
        else
            $code = $_REQUEST['IBLOCK_ID'];

        

        $rsBindValues = CIBlockSection::GetList(
            array("SORT" => "ASC"),
            array("IBLOCK_ID"=>$code),
            false,
            array(
                "ID",
                "IBLOCK_ID",
                "IBLOCK_NAME",
                "NAME"
            ),
            false
        );
        while ($bind_value = $rsBindValues->GetNext()) {
            $sectionsList[$bind_value['IBLOCK_ID']]['NAME'] = $ibNames[$bind_value['IBLOCK_ID']];
            $sectionsList[$bind_value['IBLOCK_ID']]['SECTIONS'][] = $bind_value;
        }

        $optionsHTML='<option value=""> -=( '.Loc::GetMessage("PHOENIX_USER_TS_OPTION").' )=- </option>';

        if(!empty($sectionsList))
        {
            foreach($sectionsList as $ib){

                $optionsHTML .= '<optgroup label="'.$ib['NAME'].'">';
                
                if(!empty($ib['SECTIONS']))
                {

                    foreach($ib['SECTIONS'] as $s){
                        $optionsHTML .= '<option value="'.$s["ID"].'"'.
                            ( $value["VALUE"]==$s['ID'] ? ' selected' : '' ).
                            '>'.
                            $s['NAME'].' ['.$s['ID'].']'.
                            '</option>';
                    }
                }

                $optionsHTML .= '</optgroup>';

            }

            return  '<select name="'.$strHTMLControlName["VALUE"].'">'.$optionsHTML.'</select>';

        }

        

    }

}
?>