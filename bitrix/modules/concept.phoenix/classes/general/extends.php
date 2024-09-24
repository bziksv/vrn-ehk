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

class CPhoenixTextarea extends CUserTypeString
{

    public static function CPhoenixTextareaUserType()
    {   

        return array(
            "USER_TYPE_ID" => "CPhoenixTextarea",
            "CLASS_NAME" => "CPhoenixTextarea",
            "DESCRIPTION" => GetMessage("PHOENIX_UF_TEXTAREA"),
            "BASE_TYPE" => "str"
        );
    }

    public function GetEditFormHTML($arUserField, $arHtmlControl)
    {

        if($arUserField["ENTITY_VALUE_ID"]<1 && strlen($arUserField["SETTINGS"]["DEFAULT_VALUE"])>0)
            $arHtmlControl["VALUE"] = htmlspecialcharsbx($arUserField["SETTINGS"]["DEFAULT_VALUE"]);
        if($arUserField["SETTINGS"]["ROWS"] < 8)
            $arUserField["SETTINGS"]["ROWS"] = 8;

        if($arUserField['MULTIPLE'] == 'Y')
            $name = preg_replace("/[\[\]]/i", "_", $arHtmlControl["NAME"]);
        else
            $name = $arHtmlControl["NAME"];
        
        ob_start();
        
        CFileMan::AddHTMLEditorFrame(
            $name,
            $arHtmlControl["VALUE"],
            $name."_TYPE",
            strlen($arHtmlControl["VALUE"])?"html":"text",
            array(
                'height' => $arUserField['SETTINGS']['ROWS']*10,
            )
        );
        
        if($arUserField['MULTIPLE'] == 'Y')
            echo '<input type="hidden" name="'.$arHtmlControl["NAME"].'" >';
        
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html; 
    }

}

class CPhoenixUsersGroupsUserType
{
    function GetUserTypeDescription()
    {
        return array(
            "USER_TYPE_ID"  => "user_groups",
            "CLASS_NAME"    => "CPhoenixUsersGroupsUserType",
            "DESCRIPTION"   => GetMessage("PHOENIX_CLASS_USER_GROUPS_DESCRIPTION"),
            "BASE_TYPE"     => "int",
        );
    }
    
    function OnSearchIndex($arUserField)
    {
        if(!Loader::includeModule('iblock'))
            return;

        if (is_array($arUserField['VALUE']))
        {
            $return = Array();
            $userGroups = CGroup::GetList(($by="C_SORT"), ($order="ASC"), Array('ID' => implode("|", $arUserField['VALUE'])));
            while ($userGroup = $userGroups->Fetch())
            {
                $return[] = $userGroup['NAME'];
            }
            return implode("\r\n", $return);
        }
        else
        {
            $userGroup = CGroup::GetByID(intval($arUserField['VALUE']));
            if ($userGroup = $userGroup->Fetch())
            {
                return $userGroup['NAME'];
            }
            else return '';
        }
    }
    
    function GetFilterHTML($arUserField, $arHtmlControl)
    {
        global $lAdmin;
        $lAdmin->InitFilter(Array($arHtmlControl["NAME"]));
        
        $values = is_array($arHtmlControl["VALUE"]) ? $arHtmlControl["VALUE"] : Array($arHtmlControl["VALUE"]);
        
        if ($arUserField["MULTIPLE"] === 'Y') $multiple = ' multiple size="5"';
        else $multiple = '';

        $html = "<select name='".$arHtmlControl['NAME'].($arUserField["MULTIPLE"] === "Y"?"[]":"")."' ".$multiple."><option value=''>".GetMessage("PHOENIX_CLASS_USER_GROUPS_NO")."</option>";
        
        $userGroups = CGroup::GetList(($by="C_SORT"), ($order="ASC"));
        while ($userGroup = $userGroups->Fetch())
        {
            $html .= "<option ".(in_array($userGroup['ID'], $values)?'selected':'')." value='".$userGroup['ID']."'>[".$userGroup['ID']."] ".$userGroup['NAME']."</option>";
        }
        
        $html .= "</select>";
        
        return  $html;
    }
    
    function GetAdminListViewHTML($arUserField, $arHtmlControl)
    {

        if (intval($arHtmlControl['VALUE']))
        {
            $userGroup = CGroup::GetByID(intval($arHtmlControl['VALUE']));
            if ($userGroup = $userGroup->Fetch())
            {
                return $userGroup['NAME'];
            }
            else return '&nbsp;';
        }
        else return '&nbsp;';
    }
    
    function GetEditFormHTML($arUserField, $arHtmlControl)
    {
        $return = "<select name='".$arHtmlControl['NAME']."' ".($arUserField['EDIT_IN_LIST']==='N'?"disabled='disabled'":"")."><option value=''>".GetMessage("PHOENIX_CLASS_USER_GROUPS_NO")."</option>";
        
        $userGroups = CGroup::GetList(($by="C_SORT"), ($order="ASC"));
        while ($userGroup = $userGroups->Fetch())
        {
            $return .= "<option ".($userGroup['ID'] == $arHtmlControl["VALUE"]?'selected':'')." value='".$userGroup['ID']."'>[".$userGroup['ID']."] ".$userGroup['NAME']."</option>";
        }
        
        $return .= "</select>";
        
        return $return;
    }
    
    function GetDBColumnType($arUserField)
    {
        global $DB;
        switch(strtolower($DB->type))
        {
            case 'mysql': return 'int(18)'; break;
            case 'oracle': return 'number(18)'; break;
            case 'mssql': return "int"; break;
        }
    }
}


class CPhoenixUsersGroupsIblockProperty
{
    function GetUserTypeDescription()
    {
        return Array(
            "PROPERTY_TYPE"         => "S",
            "USER_TYPE"             => "GroupsIblockProperty",
            "DESCRIPTION"           => GetMessage("PHOENIX_CLASS_USER_GROUPS_DESCRIPTION"),
            "GetSettingsHTML"       => Array("CPhoenixUsersGroupsIblockProperty", "GetSettingsHTML"),
            "GetPropertyFieldHtml"  => Array("CPhoenixUsersGroupsIblockProperty", "GetPropertyFieldHtml"),
            "GetAdminListViewHTML"  => Array("CPhoenixUsersGroupsIblockProperty", "GetAdminListViewHTML"),
            "GetAdminFilterHTML"    => Array("CPhoenixUsersGroupsIblockProperty", "GetAdminFilterHTML"),
            "GetPublicViewHTML"     => Array("CPhoenixUsersGroupsIblockProperty", "GetPublicViewHTML"),
        );
    }
    
    function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
    {
        $arPropertyFields = Array("HIDE" => array("ROW_COUNT", "COL_COUNT", "DEFAULT_VALUE"));

        return '';
    }
    
    function GetPublicViewHTML($arProperty, $value, $strHTMLControlName)
    {
        if (intval($value['VALUE']))
        {
            $userGroup = CGroup::GetByID(intval($value['VALUE']));
            if ($userGroup = $userGroup->Fetch())
            {
                return $userGroup['NAME'];
            }
            else return '&nbsp;';
        }
        else return '&nbsp;';
    }
    
    function GetAdminFilterHTML($arProperty, $strHTMLControlName)
    {
        $lAdmin = new CAdminList($strHTMLControlName["TABLE_ID"]);
        $lAdmin->InitFilter(Array($strHTMLControlName["VALUE"]));
        $filterValue = $GLOBALS[$strHTMLControlName["VALUE"]];

        if (isset($filterValue) && is_array($filterValue)) $values = $filterValue;
        else $values = Array();
        
        if ($arProperty["MULTIPLE"] === 'Y') $multiple = ' multiple size="5"';
        else $multiple = '';

        $html = "<select name='".$strHTMLControlName['VALUE']."' ".$multiple."><option value=''>".GetMessage("PHOENIX_CLASS_USER_GROUPS_NO")."</option>";
        
        $userGroups = CGroup::GetList(($by="C_SORT"), ($order="ASC"));
        while ($userGroup = $userGroups->Fetch())
        {
            $html .= "<option ".($userGroup['ID'] == $filterValue["VALUE"]?'selected':'')." value='".$userGroup['ID']."'>[".$userGroup['ID']."] ".$userGroup['NAME']."</option>";
        }
        
        $html .= "</select>";
        
        return  $html;
    }
    
    function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName)
    {
        if (intval($value['VALUE']))
        {
            $userGroup = CGroup::GetByID(intval($value['VALUE']));
            if ($userGroup = $userGroup->Fetch())
            {
                return $userGroup['NAME'];
            }
            else return '&nbsp;';
        }
        else return '&nbsp;';
    }
    
    function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $return = "<select name='".$strHTMLControlName['VALUE']."'><option value=''>".GetMessage("PHOENIX_CLASS_USER_GROUPS_NO")."</option>";
        
        $userGroups = CGroup::GetList(($by="C_SORT"), ($order="ASC"));
        while ($userGroup = $userGroups->Fetch())
        {
            $return .= "<option ".($userGroup['ID'] == $value["VALUE"]?'selected':'')." value='".$userGroup['ID']."'>[".$userGroup['ID']."] ".$userGroup['NAME']."</option>";
        }
        
        $return .= "</select>";
        
        return $return;
    }
}
?>