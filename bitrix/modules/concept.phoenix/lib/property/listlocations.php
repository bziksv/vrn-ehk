<?
namespace Concept\Phoenix\Property;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class ListLocations{
	static function OnIBlockPropertyBuildList(){
		return array(
			'PROPERTY_TYPE' => 'S',
			'USER_TYPE' => 'SConceptPhoenixListLocations',
			'DESCRIPTION' => Loc::getMessage('LOCATIONS_LINK_PROP_PHOENIX_TITLE'),
			'GetPropertyFieldHtml' => array(__CLASS__, 'GetPropertyFieldHtml'),
			'GetSettingsHTML' => array(__CLASS__, 'GetSettingsHTML'),
		);
	}

	static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName){
		static $cache = array();
		$html = '';


		if(Loader::includeModule('sale'))
		{
			

			$val = ($value["VALUE"] ? $value["VALUE"] : $arProperty["DEFAULT_VALUE"]);
			$regionName = "";

			if(strlen($val)>0)
			{
				$res = \Bitrix\Sale\Location\LocationTable::getList(array(
	                'filter' => array(
	                    '=ID' => $val,
	                    '=PARENTS.NAME.LANGUAGE_ID' => LANGUAGE_ID,
	                    '=PARENTS.TYPE.NAME.LANGUAGE_ID' => LANGUAGE_ID,
	                ),
	                'select' => array(
	                    'I_ID' => 'PARENTS.ID',
	                    'I_NAME_RU' => 'PARENTS.NAME.NAME',
	                    'I_TYPE_CODE' => 'PARENTS.TYPE.CODE',
	                    'I_TYPE_NAME_RU' => 'PARENTS.TYPE.NAME.NAME'
	                ),
	                'order' => array(
	                    'PARENTS.DEPTH_LEVEL' => 'desc'
	                )
	            ));

	            $i = 0;
	            $regionName = "";

	            while($item = $res->fetch())
    			{
    				$regionName .= (($i==0)?"":", ").$item["I_NAME_RU"];
					$i++;
    			}
			}

			$source = "/bitrix/tools/concept.phoenix/ajax/settings/locations.php";

			$class="location-".str_replace(array("PROP","VALUE","[","]"), array(""), $strHTMLControlName["VALUE"]);

			$html = "<div style=\"display:inline-block; position: relative\">            
                        <input type=\"text\" class=\"admin-inputdatalist-css ".$class."\" valign=\"middle\" size=\"50\" value=\"".$regionName."\">
                        <div class=\"admin-inputdatalist-preload\"></div>                        
                    	<input type=\"hidden\" name=\"".$strHTMLControlName["VALUE"]."\" value=\"".$val."\">

                        <div class=\"admin-inputdatalist ".$class."_area\"></div>
                    </div>

                    <script>

                        $( \".".$class."\" ).autocomplete({
                            minLength: 2,
                            source: \"".$source."\",
                            appendTo: \".".$class."_area\",
                            autoFocus: true,
                            select: function( event, ui ) {
                                $(this).parent().find(\"input\").val(ui.item.value);
                                $(this).val(ui.item.label);
                                return false;
                            },
                            change: function( event, ui ) {
                                if(ui.item===null)
                                {
                                	$(this).parent().find(\"input\").val(\"\");
                                	$(this).val(\"\");
                                }
                            },

                        });
                    </script>";
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
