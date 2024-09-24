<?
if(!defined('CONCEPT_PHOENIX_MODULE_ID')){
    define('CONCEPT_PHOENIX_MODULE_ID', 'concept.phoenix');
}


IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/classes/general/CPhoenix.php");


class CPhoenixInstructions{

    public static function get()
    {
        return array(
            array(
                "NAME"=>GetMessage("PHOENIX_SETTINGS_INSTRUCT_LAND_NAME"),
                "URL" =>GetMessage("PHOENIX_SETTINGS_INSTRUCT_LAND_URL")
            ),
            array(
                "NAME"=>GetMessage("PHOENIX_SETTINGS_INSTRUCT_CATALOG_NAME"),
                "URL" =>GetMessage("PHOENIX_SETTINGS_INSTRUCT_CATALOG_URL")
            ),
            array(
                "NAME"=>GetMessage("PHOENIX_SETTINGS_INSTRUCT_FORM_NAME"),
                "URL" =>GetMessage("PHOENIX_SETTINGS_INSTRUCT_FORM_URL")
            ),
            array(
                "NAME"=>GetMessage("PHOENIX_SETTINGS_INSTRUCT_USER_PHP_NAME"),
                "URL" =>GetMessage("PHOENIX_SETTINGS_INSTRUCT_USER_PHP_URL")
            ),
            array(
                "NAME"=>GetMessage("PHOENIX_SETTINGS_INSTRUCT_ICONS_NAME"),
                "URL" =>GetMessage("PHOENIX_SETTINGS_INSTRUCT_ICONS_URL")
            ),
            array(
                "NAME"=>GetMessage("PHOENIX_SETTINGS_INSTRUCT_CUSTOM_LANG_NAME"),
                "URL" =>GetMessage("PHOENIX_SETTINGS_INSTRUCT_CUSTOM_LANG_URL")
            ),
            array(
                "NAME"=>GetMessage("PHOENIX_SETTINGS_INSTRUCT_SPEED_NAME"),
                "URL" =>GetMessage("PHOENIX_SETTINGS_INSTRUCT_SPEED_URL")
            ),
            array(
                "NAME"=>GetMessage("PHOENIX_SETTINGS_INSTRUCT_FAQ_NAME"),
                "URL" =>GetMessage("PHOENIX_SETTINGS_INSTRUCT_FAQ_URL")
            ),
            array(
                "NAME"=>GetMessage("PHOENIX_SETTINGS_INSTRUCT_REGIONS_NAME"),
                "URL" =>GetMessage("PHOENIX_SETTINGS_INSTRUCT_REGIONS_URL")
            ),
            array(
                "NAME"=>GetMessage("PHOENIX_SETTINGS_INSTRUCT_HEADER_CUSTOM_NAME"),
                "URL" =>GetMessage("PHOENIX_SETTINGS_INSTRUCT_HEADER_CUSTOM_URL")
            ),
        );
    }

    public static function printHTML($view = "view_1")
    {
        $html = "";

        
        $arInstructions = CPhoenixInstructions::get();


        if($view === "view_2")
        {


            if(!empty($arInstructions))
            {
                foreach ($arInstructions as $key => $value)
                {
                    $html .= "<div class=\"instruct\">";
                    $html .= "<a target=\"_blank\" href=\"".$value["URL"]."\">".$value["NAME"]."</a>";
                    $html .= "</div>";
                }

            }
            
        }
        else
        {
            $html = "<ul class=\"phx\">";

            if(!empty($arInstructions))
            {
                foreach ($arInstructions as $key => $value)
                {
                    $html .= "<li>";
                    $html .= "<a target=\"_blank\" href=\"".$value["URL"]."\">".$value["NAME"]."</a>";
                    $html .= "</li>";
                }

            }
            
            $html .= "</ul>";
        }

        

        return $html;
	}
}