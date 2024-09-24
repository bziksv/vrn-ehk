<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?global $PHOENIX_TEMPLATE_ARRAY;?>



<?if($arResult["LANDING"]["ID"] > 0):?>

    <?
    $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "GLOBAL_ACTIVE"=>"Y", "ACTIVE"=>"Y", 'ID' => $arResult["LANDING"]["ID"]);
    $db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, array("ID", "UF_PHX_LINK_OBJ", "UF_PHX_MAIN_PAGE"));

    $ar_result = $db_list->GetNext();


    if($ar_result["UF_PHX_MAIN_PAGE"])
         LocalRedirect(SITE_DIR, true, "301 Moved permanently");
    ?>

    <?
    if($ar_result["UF_PHX_LINK_OBJ"])
    {
        $url = "";

        $arCodes = Array();

        $arCodes["catalog"] = Array();
        $arCodes["catalog"]["ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["IBLOCK_ID"];
        $arCodes["catalog"]["PROP"] = "UF_PHX_CTLG_T_ID";

        
        $arCodes["blog"] = Array();
        $arCodes["blog"]["ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["BLOG"]["IBLOCK_ID"];
        $arCodes["blog"]["PROP"] = "UF_PHX_BLG_T_ID";

        $arCodes["news"] = Array();
        $arCodes["news"]["ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["NEWS"]["IBLOCK_ID"];
        $arCodes["news"]["PROP"] = "UF_PHX_NW_T_ID";

        $arCodes["actions"] = Array();
        $arCodes["actions"]["ID"] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["ACTIONS"]["IBLOCK_ID"];


        foreach($arCodes as $key => $block) 
        {
            if(strlen($url) <= 0 && strlen($block["PROP"]) > 0)
            {
                $arSelect = Array("ID", "NAME", "SECTION_PAGE_URL");
                $arFilter = Array('IBLOCK_ID'=>$block["ID"], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE'=>'Y', $block["PROP"]=>$ar_result["ID"]);
                $db_list = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);

                if($result = $db_list->GetNext())
                {
                    $url = $result["SECTION_PAGE_URL"];
                    break;
                }
            }

            if(strlen($url) <= 0)
            {
                $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL");
                $arFilter = Array("IBLOCK_ID"=>$block["ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", 'GLOBAL_ACTIVE'=>'Y', "PROPERTY_CHOOSE_LANDING" => $ar_result["ID"]);
                $db_list = CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>1), $arSelect);

                if($result = $db_list->GetNext())
                {
                    $url = $result["DETAIL_PAGE_URL"];
                    break;
                }

            }
        }

        if(strlen($url) > 0)
            LocalRedirect($url, true, "301 Moved permanently");
    }
    ?>



    <?$GLOBALS["PHOENIX_CURRENT_SECTION_ID"] = $APPLICATION->IncludeComponent(
    	"concept:phoenix.one.page", 
    	"", 
    	array(
    		"CACHE_TIME" => $arParams["CACHE_TIME"],
    		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
    		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
    		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
    		"PARENT_SECTION" => $ar_result["ID"],
    		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
    		"MESSAGE_404" => $arParams["MESSAGE_404"],
    		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
    		"SHOW_404" => $arParams["SHOW_404"],
    		"FILE_404" => $arParams["FILE_404"],
    		"COMPONENT_TEMPLATE" => "",
            "COMPOSITE_FRAME_MODE" => "N",
    	),
    	$component
    );?>
    
    
<!-- dlya nastroek saita -->

    <?$GLOBALS["PHOENIX_IBLOCK_ID"] = $arParams["IBLOCK_ID"];?>
    <?$GLOBALS["PHOENIX_IBLOCK_TYPE"] = $arParams["IBLOCK_TYPE"];?>

<?endif;?>