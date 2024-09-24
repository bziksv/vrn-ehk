<?
namespace Concept\Phoenix\Property;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class ListPrices{
	static function OnIBlockPropertyBuildList(){
		return array(
			'PROPERTY_TYPE' => 'S',
			'USER_TYPE' => 'SConceptPhoenixListPrices',
			'DESCRIPTION' => Loc::getMessage('PRICES_LINK_PROP_PHOENIX_TITLE'),
			'GetPropertyFieldHtml' => array(__CLASS__, 'GetPropertyFieldHtml'),
			'GetPropertyFieldHtmlMulty' => array(__CLASS__, 'GetPropertyFieldHtmlMulty'),
			'GetSettingsHTML' => array(__CLASS__, 'GetSettingsHTML'),
		);
	}

	static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName){
		static $cache = array();
		$html = '';
		if(Loader::includeModule('catalog'))
		{
			$cache["PRICE"] = array();
			$rsPrice = \CCatalogGroup::GetList( array("SORT" => "ASC"), array() );
			while($arPrice = $rsPrice->GetNext())
			{
				$cache["PRICE"][] = $arPrice;
			}

			$varName = str_replace("VALUE", "DESCRIPTION", $strHTMLControlName["VALUE"]);
			$val = ($value["VALUE"] ? $value["VALUE"] : $arProperty["DEFAULT_VALUE"]);
			$html = '<select name="'.$strHTMLControlName["VALUE"].'" onchange="document.getElementById(\'DESCR_'.$varName.'\').value=this.options[this.selectedIndex].text">
			<option value="empty" '.($val == "empty" ? 'selected' : '').'>-</option>';
			foreach($cache["PRICE"] as $arPrice)
			{
				$html .= '<option value="'.$arPrice["ID"].'"';
				if($val == $arPrice["~ID"])
					$html .= ' selected';
				$html .= '>'.$arPrice["NAME"].'</option>';
			}
			$html .= '</select>';
		}

		return $html;
	}

	static function GetPropertyFieldHtmlMulty($arProperty, $value, $strHTMLControlName){
		static $cache = array();
		$html = '';
		if(Loader::includeModule('catalog'))
		{
			$cache["PRICE"] = array();
			$rsPrice = \CCatalogGroup::GetList( array("SORT" => "ASC"), array() );
			while($arPrice = $rsPrice->GetNext())
			{
				$cache["PRICE"][] = $arPrice;
			}

			

			$varName = str_replace("VALUE", "DESCRIPTION", $strHTMLControlName["VALUE"]);
			$arValues = array();
			if($value && is_array($value))
			{
				foreach($value as $arValue)
				{
					$arValues[] = $arValue["VALUE"];
				}
			}
			/*else
				$arValues[] = $arProperty["DEFAULT_VALUE"];*/

			/*if($arProperty['MULTIPLE'] == 'Y')
			{*/


				$html .= '<input type="hidden" name="'.$strHTMLControlName["VALUE"].'" value="">';

				foreach($cache["PRICE"] as $arPrice)
				{
					$id=randString(8);
					$html .= '<input type="checkbox" class="adm-designed-checkbox" name="'.$strHTMLControlName["VALUE"].'[]" value="'.$arPrice["ID"].'" '.(in_array($arPrice["~ID"], $arValues) ? 'checked' : '').' id="'.$id.'">';
					$html .= '<label class="adm-designed-checkbox-label" for="'.$id.'"></label>';
					$html .= '<label for="'.$id.'">'.((strlen($arPrice["NAME_LANG"])>0)?$arPrice["NAME_LANG"]." (".$arPrice["NAME"].")":$arPrice["NAME"]).'</label><br/>';

					unset($id);
					
				}
			/*}*/

			
		}
		return $html;
	}

	static function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields){

		

		$arPropertyFields = array(
            'HIDE' => array(
            	'DEFAULT_VALUE',
            	'SMART_FILTER',
            	'SEARCHABLE',
            	'COL_COUNT',
            	'ROW_COUNT',
            	'FILTER_HINT',
            ),
            'SET' => array(
            	'SMART_FILTER' => 'N',
            	'SEARCHABLE' => 'N',
            	'ROW_COUNT' => '10',
            ),
        );

		return $html;
	}
}
