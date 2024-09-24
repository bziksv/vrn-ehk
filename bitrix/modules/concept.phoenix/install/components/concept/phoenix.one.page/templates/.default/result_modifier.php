<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult["ITEMS"])):?>
<?
    global $PHOENIX_TEMPLATE_ARRAY;
    global $USER;


    $arReplacement = Array();
    $arReplacementBlocks = Array();

    foreach($arResult["ITEMS"] as $key => $arItem)
    {
        
        if(!empty($arItem["PROPERTIES"]["BLOCK_INSTEAD"]["VALUE"]))
        {
            foreach($arItem["PROPERTIES"]["BLOCK_INSTEAD"]["VALUE"] as $id)
                $arReplacement[$id] = $arItem["ID"];        
                
            $arReplacementBlocks[$arItem["ID"]] = $arItem;
            unset($arResult["ITEMS"][$key]);
        }
    }

    if(!empty($arReplacement))
    {
        foreach($arResult["ITEMS"] as $key=>$arItem)
        {
            if(array_key_exists($arItem["ID"], $arReplacement))
                $arResult["ITEMS"][$key] = $arReplacementBlocks[$arReplacement[$arItem["ID"]]];  
        }
    } 
    
    $arRetranslatorId = array();
    $arRetranslatorParams = array();

    foreach($arResult["ITEMS"] as $key=>$arItem)
    {
        if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "retranslator")
        {
            if($arItem["PROPERTIES"]["RETRANSLATOR"]["VALUE"])
            {
                $arRetranslatorId[] = $arItem["PROPERTIES"]["RETRANSLATOR"]["VALUE"];
                $arRetranslatorParams[$arItem["PROPERTIES"]["RETRANSLATOR"]["VALUE"]] = array(
                    "ID" => $arItem["ID"],
                    "NAME" => $arItem["NAME"],
                    "IBLOCK_SECTION_ID" => (isset($arItem["IBLOCK_SECTION_ID"]))? $arItem["IBLOCK_SECTION_ID"]:0,
                );
            }
            else
                unset($arResult["ITEMS"][$key]);
        }
    }



    if(!empty($arRetranslatorId))
    {
        $arRetranslator = array();
        $res = CIBlockElement::GetList(array(), Array("ID"=> $arRetranslatorId, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y"));

        while($ob = $res->GetNextElement())
        {
            $arElement = Array();
            $arElement = $ob->GetFields();  
            $arElement["PROPERTIES"] = $ob->GetProperties();
            $arElement["ADDITIONAL"] = array(
                "ID" => $arRetranslatorParams[$arElement["ID"]]["ID"],
                "NAME" => $arRetranslatorParams[$arElement["ID"]]["NAME"],
                "IBLOCK_SECTION_ID" => $arRetranslatorParams[$arElement["ID"]]["IBLOCK_SECTION_ID"]
            );

            if($arElement["PREVIEW_PICTURE"]>0)
            {
                $arElement["PREVIEW_PICTURE"] = array(
                    "ID" => $arElement["PREVIEW_PICTURE"],
                    "SRC" => CFile::GetPath($arElement["PREVIEW_PICTURE"])
                );
            }


            $arRetranslator[$arElement["ID"]] = $arElement;
           
        }
    }

    foreach($arResult["ITEMS"] as $key=>$arItem)
    {
        if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "retranslator")
        {
            if(isset($arRetranslator[$arItem["PROPERTIES"]["RETRANSLATOR"]["VALUE"]]))
                $arResult["ITEMS"][$key] = $arRetranslator[$arItem["PROPERTIES"]["RETRANSLATOR"]["VALUE"]];
            else
                unset($arResult["ITEMS"][$key]);
        }
    }

    
    $arChangersBlocks = [];

    foreach($arResult["ITEMS"] as $key=>$arItem)
    {
        if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "changer_blocks")
        {
            if(!empty($arItem["PROPERTIES"]["CHANGER_BLOCKS_ID"]["VALUE"]))
            {
                $arChangersBlock = [];
            	foreach ($arItem["PROPERTIES"]["CHANGER_BLOCKS_ID"]["VALUE"] as $k => $val)
            		$arChangersBlock = array_merge(explode(',', $val), $arChangersBlock);

            	$arChangersBlocks = array_merge($arChangersBlock, $arChangersBlocks);
            
            }
        }
    }

    if(!empty($arChangersBlocks))
    {
    	foreach($arResult["ITEMS"] as $key=>$arItem)
    	{
    		$arResult["ITEMS"][$key]["CHANGER_BLOCK"] = (in_array($arItem["ID"], $arChangersBlocks))?"Y":'N';
    	}
    }


    $arResult["H1_MAIN"] = 0;
    foreach($arResult["ITEMS"] as $key=>$arItem)
    {
        if(strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) > 0 && $arItem["PROPERTIES"]["THIS_H1"]["VALUE"] == "Y")
        {
            $arResult["H1_MAIN"] = 1;
            $arResult["ITEMS"][$key]["H1_MAIN"] = 1;

            break;
        }
    }

?>

<?
$main_key = -1;
$main_key_first = -1;

$valID = "";


$menu_count = 0;
$menu_first_sort = 0;
foreach($arResult["ITEMS"] as $key=>$arItem)
{
    //menu
    if($arItem["PROPERTIES"]["SHOW_IN_MENU"]["VALUE"] == "Y")
    {
        $arMenu = Array();

        $arMenu["SORT"] = $arItem["PROPERTIES"]["MENU_SORT"]["VALUE"];

        if(strlen($arItem["PROPERTIES"]["MENU_TITLE"]["VALUE"]) > 0)
            $arMenu["NAME"] = $arItem["PROPERTIES"]["MENU_TITLE"]["~VALUE"];

        elseif(strlen($arItem["PROPERTIES"]["HEADER"]["VALUE"]) > 0)
            $arMenu["NAME"] = $arItem["PROPERTIES"]["HEADER"]["~VALUE"];
        
        else
            $arMenu["NAME"] = $arItem["NAME"];


        $arMenu["ID"] = $arItem["ID"];
        $arMenu["HIDE"] = $arItem["PROPERTIES"]["HIDE_BLOCK"]["VALUE"];
        $arMenu["HIDE_LG"] = $arItem["PROPERTIES"]["HIDE_BLOCK_LG"]["VALUE"];
        $arMenu["ICON"] = $arItem["PROPERTIES"]["MENU_ICON"]["VALUE"];
        $arMenu["ICON_COLOR"] = $arItem["PROPERTIES"]["MENU_ICON"]["DESCRIPTION"];
        $arMenu["PICTURE"] = $arItem["PROPERTIES"]["MENU_PIC"]["VALUE"];

        if(strlen($arItem["PROPERTIES"]["MENU_SORT"]["VALUE"]) > 0)
        {
            $arResult["MENU_FIRST_SORT"][$menu_first_sort] = $arMenu;
            $menu_first_sort++;
        }

        else
            $arResult["MENU_SEC_SORT"][] = $arMenu;
        
        $menu_count++;
        
    }
    
    if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "component")
        $arResult["COMPONENTS"][] = $arItem;


}

$arResult["MENU_COUNT"] = $menu_count;

if(!empty($arResult["MENU_FIRST_SORT"]))
{
    asort($arResult["MENU_FIRST_SORT"]);

    if(!empty($arResult["MENU_SEC_SORT"]))
        $arResult["MENU"] = array_merge($arResult["MENU_FIRST_SORT"], $arResult["MENU_SEC_SORT"]);
    
    else
        $arResult["MENU"] = $arResult["MENU_FIRST_SORT"];

}

else
    $arResult["MENU"] = $arResult["MENU_SEC_SORT"];


$arResult["SECTION"]["PHX_BANNERS_ISSET"] = false;

if(is_array($arResult["SECTION"]["UF_PHX_BANNERS"]) && !empty($arResult["SECTION"]["UF_PHX_BANNERS"]))
{
    $arFilter = Array("ID"=> $arResult["SECTION"]["UF_PHX_BANNERS"], 'ACTIVE'=> 'Y');

    $res = CIBlockElement::GetList(array(), $arFilter, false, array("nTopCount"=>1), array("ID"));

    if($res->SelectedRowsCount() > 0)
        $arResult["SECTION"]["PHX_BANNERS_ISSET"] = true;
}


// posMenu
if(strlen($arResult["SECTION"]["UF_PHX_INMENU_POS"])>0){

    $inner_menu_pos = CUserFieldEnum::GetList(array(), array(
        "ID" => $arResult["SECTION"]["UF_PHX_INMENU_POS"],
    ));
    $arResult["SECTION"]["UF_PHX_INMENU_POS_RES"] = $inner_menu_pos->GetNext();

    unset($inner_menu_pos);
}



$catalogLabels = array();


$first_block_start_desktop = false;
$first_block_start_mobile = false;

$first_block_count_desktop = 0;
$first_block_count_mobile = 0;

foreach($arResult["ITEMS"] as $key=>$arItem)
{
    
    if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "first_block")
    {
        if($main_key_first < 0)
            $main_key_first = $key;
        
        
        if($arItem["PROPERTIES"]["FB_ADD_PICTURE"]["VALUE"] > 0)
            $arItem["TWO_COLS"] = "Y";
        
        if($arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "" || $arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "buttons" || $arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "mixed")
        {
            $k = 0;
            
            if(strlen($arItem["PROPERTIES"]["FB_LB_NAME"]["VALUE"]) > 0)
                $k++;
                
            if(strlen($arItem["PROPERTIES"]["FB_VIDEO_LINK"]["VALUE"]) > 0)
                $k++;
                
            if(strlen($arItem["PROPERTIES"]["FB_RB_NAME"]["VALUE"]) > 0)
                $k++;
                
                
            $arItem["BUTTONS_COUNT"] = $k;
                
        }
        
        if($arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "icons" || $arItem["PROPERTIES"]["FB_VIEW"]["VALUE_XML_ID"] == "mixed")
        {
            $arItem["ICONS_COUNT"] = 0;
            $arItem["ICONS_DESC_COUNT"] = 0;

            if(!empty($arItem["PROPERTIES"]["FB_ICONS"]["VALUE"]))
                $arItem["ICONS_COUNT"] = count($arItem["PROPERTIES"]["FB_ICONS"]["VALUE"]);

            if(!empty($arItem["PROPERTIES"]["FB_ICONS_DESC"]["VALUE"]))
                $arItem["ICONS_DESC_COUNT"] = count($arItem["PROPERTIES"]["FB_ICONS_DESC"]["VALUE"]);

            $arItem["ICONS_MAX"] = max($arItem["ICONS_COUNT"], $arItem["ICONS_DESC_COUNT"]);
        }

        if($arItem["PROPERTIES"]["MAIN_TITLE_POS"]["VALUE_XML_ID"] == "")
            $arItem["PROPERTIES"]["MAIN_TITLE_POS"]["VALUE_XML_ID"] = "def";

        if($arItem["PROPERTIES"]["MAIN_TITLE_POS_MOB"]["VALUE_XML_ID"] == "")
            $arItem["PROPERTIES"]["MAIN_TITLE_POS_MOB"]["VALUE_XML_ID"] = "def-mob";


        if($arItem["PROPERTIES"]["HIDE_BLOCK_LG"]["VALUE"] != "Y")
        {

            if(!$first_block_start_desktop)
            {
                $arResult["ITEMS"][$main_key_first]["START_DESKTOP"] = $arItem;
                $first_block_start_desktop = true;
            }

            $first_block_count_desktop++;

            $arResult["ITEMS"][$main_key_first]["DESKTOP_COUNT"] = $first_block_count_desktop;

            $arResult["ITEMS"][$main_key_first]["ELEMENTS_LG"][] = $arItem;
            
        }

        if($arItem["PROPERTIES"]["HIDE_BLOCK"]["VALUE"] != "Y")
        {

            if(!$first_block_start_mobile){
                $arResult["ITEMS"][$main_key_first]["START_MOBILE"] = $arItem;
                $first_block_start_mobile = true;
            }

            $first_block_count_mobile++;

            $arResult["ITEMS"][$main_key_first]["MOBILE_COUNT"] = $first_block_count_mobile;

            $arResult["ITEMS"][$main_key_first]["ELEMENTS_XS"][] = $arItem;
        }
        
        
        /*$arResult["ITEMS"][$main_key_first]["ELEMENTS"][] = $arItem;*/
        
        if($main_key_first != $key)
            unset($arResult["ITEMS"][$key]);
        
    }

    //tariffs
    if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "tariff" && ($arItem["PROPERTIES"]["TARIFF_VIEW"]["VALUE_XML_ID"] == "" || $arItem["PROPERTIES"]["TARIFF_VIEW"]["VALUE_XML_ID"] == "flat"))
    {
        
        $type = $arItem["PROPERTIES"]["TARIFF_VIEW"]["VALUE_XML_ID"];
        
        if(strlen($type) <= 0)
            $type = "flat";
        
        if($valID != $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].$type)
            $main_key = -1;
    }
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "service")
    {
        
        $type = $arItem["PROPERTIES"]["SERVICE_VIEW"]["VALUE_XML_ID"];
        
        if(strlen($type) <= 0)
            $type = "flat";
        
        if($valID != $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].$type)
            $main_key = -1;
            
    }

    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "blink" && ($arItem["PROPERTIES"]["BLINK_VIEW"]["VALUE_XML_ID"] == "" || $arItem["PROPERTIES"]["BLINK_VIEW"]["VALUE_XML_ID"] == "link"))
    {

        $type = $arItem["PROPERTIES"]["BLINK_VIEW"]["VALUE_XML_ID"];
        
        if(strlen($type) <= 0)
            $type = "link";
        
        if($valID != $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].$type)
            $main_key = -1;

    }

    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "slider")
    {

        $type = "block-slider";
        
        if($valID != $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].$type)
            $main_key = -1;
    }

    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "opinion")
    {
        
        $type = $arItem["PROPERTIES"]["OPINION_VIEW"]["VALUE_XML_ID"];
        
        if(strlen($type) <= 0)
            $type = "slider";
        
        if($valID != $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].$type)
            $main_key = -1;
            
    }

    else
    {
        if($valID != $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"])
            $main_key = -1;
    }


    
    
    
    
    //text
    if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "text")
    {
        if($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE"]["VALUE"] > 0)
        {
            $arResult["ITEMS"][$key]["PADDING_CHANGE"] = true;
            $arResult["ITEMS"][$key]["TITLE_CHANGE"] = true;
            $arResult["ITEMS"][$key]["BUTTON_CHANGE"] = true;
        }

        $arResult["ITEMS"][$key]["PIC_POS_HOR"] = ($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE_BLOCK_POSITION"]["VALUE_XML_ID"] == "")? "left" : $arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE_BLOCK_POSITION"]["VALUE_XML_ID"];

        if($arResult["ITEMS"][$key]["PIC_POS_HOR"] == "left")
            $arResult["ITEMS"][$key]["PIC_POS_HOR"] = "order-md-1";

        if($arResult["ITEMS"][$key]["PIC_POS_HOR"] == "right")
            $arResult["ITEMS"][$key]["PIC_POS_HOR"] = "order-md-3";



        $arResult["ITEMS"][$key]["PIC_POS_VERT"] = ($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE_POSITION"]["VALUE_XML_ID"] == "")?"middle":$arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE_POSITION"]["VALUE_XML_ID"]; 

        if($arResult["ITEMS"][$key]["PIC_POS_VERT"] == "top")
            $arResult["ITEMS"][$key]["PIC_POS_VERT"] = "align-self-start";

        if($arResult["ITEMS"][$key]["PIC_POS_VERT"] == "middle")
            $arResult["ITEMS"][$key]["PIC_POS_VERT"] = "align-self-center";

        if($arResult["ITEMS"][$key]["PIC_POS_VERT"] == "bottom")
            $arResult["ITEMS"][$key]["PIC_POS_VERT"] = "align-self-end";


        if( $arItem["PROPERTIES"]["TEXT_BLOCK_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "" 
            || $arItem["PROPERTIES"]["TEXT_BLOCK_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "order-first-mob")
            $arResult["ITEMS"][$key]["PROPERTIES"]["TEXT_BLOCK_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] = "order-1";

        if($arItem["PROPERTIES"]["TEXT_BLOCK_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] == "order-last-mob")
            $arResult["ITEMS"][$key]["PROPERTIES"]["TEXT_BLOCK_IMAGE_POSITION_MOBILE"]["VALUE_XML_ID"] = "order-3";


        if($arItem["PROPERTIES"]["TEXT_BLOCK_TEXT_ALIGN"]["VALUE_XML_ID"] == "")
            $arResult["ITEMS"][$key]["PROPERTIES"]["TEXT_BLOCK_TEXT_ALIGN"]["VALUE_XML_ID"] = "def";

        if($arItem["PROPERTIES"]["TEXT_BLOCK_TEXT_ALIGN_MOB"]["VALUE_XML_ID"] == "")
            $arResult["ITEMS"][$key]["PROPERTIES"]["TEXT_BLOCK_TEXT_ALIGN_MOB"]["VALUE_XML_ID"] = $arResult["ITEMS"][$key]["PROPERTIES"]["TEXT_BLOCK_TEXT_ALIGN"]["VALUE_XML_ID"].'-mob';



        $arResult["ITEMS"][$key]["ANIMATE"] = '';
        $arResult["ITEMS"][$key]["ANIMATE_SET"] = '';

        if($arItem["PROPERTIES"]["ANIMATE"]["VALUE"] == "Y")
        {
            
            if($arResult["ITEMS"][$key]["PIC_POS_VERT"] == "align-self-end")
                $arResult["ITEMS"][$key]["ANIMATE"] = 'wow slideInUp';
            else
                $arResult["ITEMS"][$key]["ANIMATE"] = 'wow zoomIn';


            $arResult["ITEMS"][$key]["ANIMATE_SET"] = 'data-wow-offset="250" data-wow-duration="1s" data-wow-delay="0.5s"';
        }

        $arResult["ITEMS"][$key]["GALLERY_COUNT"] = 0;

        if(!empty($arItem["PROPERTIES"]["TEXT_BLOCK_GALLERY"]["VALUE"]))
            $arResult["ITEMS"][$key]["GALLERY_COUNT"] = count($arItem["PROPERTIES"]["TEXT_BLOCK_GALLERY"]["VALUE"]);


        if($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE"]["VALUE"] > 0)
        {
            $file = CFile::ResizeImageGet($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE"]["VALUE"], array('width'=>800, 'height'=>1000), BX_RESIZE_IMAGE_PROPORTIONAL, false);
            
            $arResult["ITEMS"][$key]["TEXT_BLOCK_PICTURE"]["SRC"] = $file["src"];
            $arResult["ITEMS"][$key]["TEXT_BLOCK_PICTURE"]["DESC"] = $arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE"]["DESCRIPTION"];

        }

        if(!empty($arItem["PROPERTIES"]["TEXT_BLOCK_GALLERY"]["VALUE"]))
        {

            if($arItem["PROPERTIES"]["TEXT_BLOCK_PICTURE"]["VALUE"] > 0)
            {

                foreach($arItem["PROPERTIES"]["TEXT_BLOCK_GALLERY"]["VALUE"] as $kPhoto=>$photoID){

                    $file = CFile::ResizeImageGet($photoID, array("width" =>200, "height" =>140), BX_RESIZE_IMAGE_EXACT, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                    $arResult["ITEMS"][$key]["GALLERY"][$kPhoto]["SRC_XS"] = $file["src"];


                    $file = CFile::ResizeImageGet($photoID, array("width" =>1500, "height" =>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                    $arResult["ITEMS"][$key]["GALLERY"][$kPhoto]["SRC_LG"] = $file["src"];


                    $arResult["ITEMS"][$key]["GALLERY"][$kPhoto]["DESC"] = $arItem["PROPERTIES"]["TEXT_BLOCK_GALLERY"]["DESCRIPTION"][$kPhoto];
                }
            }

            else
            {
                foreach($arItem["PROPERTIES"]["TEXT_BLOCK_GALLERY"]["VALUE"] as $kPhoto=>$photoID){

                    $file = CFile::ResizeImageGet($photoID, array("width" => 1200, "height" =>700), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);
                    $arResult["ITEMS"][$key]["GALLERY"][$kPhoto]["SRC"] = $file["src"];


                    $arResult["ITEMS"][$key]["GALLERY"][$kPhoto]["DESC"] = $arItem["PROPERTIES"]["TEXT_BLOCK_GALLERY"]["DESCRIPTION"][$kPhoto];
                }
            }
            
        }

        if(!empty($arItem["PROPERTIES"]["TEXT_BLOCK_TEXT"]["VALUE"]))
        {
            $arResult["ITEMS"][$key]["TEXT"] = str_replace(array("#MAP_START#","#VIDEO_START#"), array('<img class="lazyload img-for-lazyload map-start" data-src="'.SITE_TEMPLATE_PATH.'/images/one_px.png">','<img class="lazyload img-for-lazyload video-start" data-src="'.SITE_TEMPLATE_PATH.'/images/one_px.png">'), $arItem["PROPERTIES"]["TEXT_BLOCK_TEXT"]["~VALUE"]["TEXT"]);
        }
    }
   
   
    
    //tariffs flat
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "tariff" && ($arItem["PROPERTIES"]["TARIFF_VIEW"]["VALUE_XML_ID"] == "" || $arItem["PROPERTIES"]["TARIFF_VIEW"]["VALUE_XML_ID"] == "flat") && $menu_count <= 0)
    {

        if($main_key < 0)
            $main_key = $key;
    
    
        $arResult["ITEMS"][$main_key]["ELEMENTS"][] = $arItem;
        
        if($main_key != $key)
            unset($arResult["ITEMS"][$key]);

    }

    //blink links

    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "blink" && ($arItem["PROPERTIES"]["BLINK_VIEW"]["VALUE_XML_ID"] == "" || $arItem["PROPERTIES"]["BLINK_VIEW"]["VALUE_XML_ID"] == "link"))
    {


        if($main_key < 0)
            $main_key = $key;


        $arResult["ITEMS"][$main_key]["ELEMENTS"][] = $arItem;
        
        if($main_key != $key)
            unset($arResult["ITEMS"][$key]);

    }

    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "slider")
    {
        if($main_key < 0)
            $main_key = $key;


        $arResult["ITEMS"][$main_key]["ELEMENTS"][] = $arItem;
        
        if($main_key != $key)
            unset($arResult["ITEMS"][$key]);
    }


    //opinion
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "opinion")
    {
        if($arItem["PROPERTIES"]["OPINION_VIEW"]["VALUE_XML_ID"] != "slider")
        {
            $arResult["ITEMS"][$key]["TITLE_CHANGE"] = true;
            
        }

        if($arItem["PROPERTIES"]["OPINION_VIEW"]["VALUE_XML_ID"] == "slider")
        {   
            if($main_key < 0)
                $main_key = $key;
        
        
            $arResult["ITEMS"][$main_key]["ELEMENTS"][] = $arItem;
            
            if($main_key != $key)
                unset($arResult["ITEMS"][$key]);
        }
        $arResult["ITEMS"][$key]["BUTTON_CHANGE"] = true;

    }

    //news
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "news")
    {

        $arResult["ITEMS"][$key]["Z_INDEX"] = true;

        $arNews = array();


        $arResult["ITEMS"][$key]["ELEMENTS_SORT"] ="DESC";

        if($arItem["PROPERTIES"]["NEWS_CHRONO"]["VALUE_XML_ID"] == "asc")
            $arResult["ITEMS"][$key]["ELEMENTS_SORT"] = "ASC";


        if($arItem["PROPERTIES"]["NEWS_MODE"]["VALUE_XML_ID"] == "auto")
        {

            if(is_array($arItem["PROPERTIES"]["NEWS_RESOURCE"]["VALUE_XML_ID"]) && !empty($arItem["PROPERTIES"]["NEWS_RESOURCE"]["VALUE_XML_ID"]))
            {
                $iblocksID = array();

                if(in_array("news", $arItem["PROPERTIES"]["NEWS_RESOURCE"]["VALUE_XML_ID"]))
                    $iblocksID = array_merge( array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['NEWS']["IBLOCK_ID"]), $iblocksID);

                if(in_array("blog", $arItem["PROPERTIES"]["NEWS_RESOURCE"]["VALUE_XML_ID"]))
                    $iblocksID = array_merge( array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['BLOG']["IBLOCK_ID"]), $iblocksID);

                if(in_array("offers", $arItem["PROPERTIES"]["NEWS_RESOURCE"]["VALUE_XML_ID"]))
                    $iblocksID = array_merge( array($PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ACTIONS']["IBLOCK_ID"]), $iblocksID);


                if(!empty($iblocksID))
                {
                    $arSort = array(
                        "ACTIVE_FROM" => $arResult["ITEMS"][$key]["ELEMENTS_SORT"],
                        "ID" => "DESC",
                    );


                    $arFilter = Array("IBLOCK_ID"=> $iblocksID);


                    $res = CIBlockElement::GetList($arSort, $arFilter, false, array("nTopCount"=>($arItem["PROPERTIES"]["NEWS_QUANTITY"]["VALUE"] != "") ? $arItem["PROPERTIES"]["NEWS_QUANTITY"]["VALUE"] : 12), array("ID"));

                    while($ob = $res->GetNextElement())
                    {
                        $arElement = Array();
                        $arElement = $ob->GetFields();

                        $arNews[] = $arElement["ID"];
                    }
                }

            }


            
        }
        else
        {
            if(!empty($arItem["PROPERTIES"]["NEWS_ELEMENTS_NEWS"]["VALUE"]))
                $arNews = array_merge($arItem["PROPERTIES"]["NEWS_ELEMENTS_NEWS"]["VALUE"], $arNews);

            if(!empty($arItem["PROPERTIES"]["NEWS_ELEMENTS_BLOG"]["VALUE"]))
                $arNews = array_merge($arItem["PROPERTIES"]["NEWS_ELEMENTS_BLOG"]["VALUE"], $arNews);

            if(!empty($arItem["PROPERTIES"]["NEWS_ELEMENTS_ACTION"]["VALUE"]))
                $arNews = array_merge($arItem["PROPERTIES"]["NEWS_ELEMENTS_ACTION"]["VALUE"], $arNews);
        }


        $arResult["ITEMS"][$key]["ELEMENTS"] = $arNews;

        unset($arNews);
        

    }

    //catalog
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "catalog")
    {
        $arResult["ITEMS"][$key]["Z_INDEX"] = true;
        /*$arResult["ITEMS"][$key]["BUTTON_CHANGE"] = true;*/
    }


    //catalog labels
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "catalog_labels")
    {
        $arResult["ITEMS"][$key]["Z_INDEX"] = true;
        if( !empty($arItem["PROPERTIES"]["CATALOG_LABELS_SECTIONS"]["VALUE"]) )
        {
            if(intval($arItem["PROPERTIES"]["CATALOG_LABELS_ITEMS_MAX_COUNT"]["VALUE"])== 0)
                $arResult["ITEMS"][$key]["PROPERTIES"]["CATALOG_LABELS_ITEMS_MAX_COUNT"]["VALUE"] = 7;


            if(empty($catalogLabels))
            {
                $property_enums = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "CODE"=>"LABELS"));


                while($enum_fields = $property_enums->GetNext())
                {
                    $nameLab = getMessage("PHOENIX_MESS_LABEL_".$enum_fields["XML_ID"]);

                    $catalogLabels[$enum_fields["XML_ID"]]["ID"] = $enum_fields["ID"];
                    $catalogLabels[$enum_fields["XML_ID"]]["NAME"] = ($nameLab)? $nameLab:$enum_fields["VALUE"];
                    $catalogLabels[$enum_fields["XML_ID"]]["ICON"] = $enum_fields["XML_ID"];
                    $catalogLabels[$enum_fields["XML_ID"]]["TAB_ID"] = $arItem["ID"]."_".$enum_fields["ID"];
                }

            }


            $arResult["ITEMS"][$key]["LABELS"] = $catalogLabels;

            if(!empty($arResult["ITEMS"][$key]["LABELS"]))
            {

                foreach ($arResult["ITEMS"][$key]["LABELS"] as $labelKey => $value){
                    
                    $arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['CATALOG']["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y",  "SECTION_ID"=>$arItem["PROPERTIES"]["CATALOG_LABELS_SECTIONS"]["VALUE"], "INCLUDE_SUBSECTIONS" => "Y", "PROPERTY_LABELS" => $value["ID"], "SECTION_ACTIVE"=>"Y", "SECTION_GLOBAL_ACTIVE" => "Y", "SECTION_SCOPE" => "IBLOCK");

                    if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["CATALOG"]["ITEMS"]["HIDE_NOT_AVAILABLE"]["VALUE"] === "Y")
                        $arFilter['CATALOG_AVAILABLE'] = "Y";

                    $arFilter = array_merge($arFilter, CPhoenix::getFilterByRegion());

                    $arStoresFilter = CPhoenix::getFilterByStores();

                    if(!empty($arStoresFilter))
                        $arFilter[] = $arStoresFilter;


                    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, array("nTopCount"=>1), array("ID"));

                    if($res->SelectedRowsCount() <=0)
                        unset($arResult["ITEMS"][$key]["LABELS"][$labelKey]);

                }

                if(!empty($arResult["ITEMS"][$key]["LABELS"]))
                    $arResult["ITEMS"][$key]["LABELS"] = array_values($arResult["ITEMS"][$key]["LABELS"]);
            }

            $arResult["ITEMS"][$key]["BUTTON_CHANGE"] = true;
        }
    }
    
    //faq
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "faq")
    {

        if(!empty($arItem["PROPERTIES"]["FAQ_ELEMENTS"]["VALUE"]))
        {
            $arSelect = array("ID","NAME", "PREVIEW_TEXT", "IBLOCK_ID", "IBLOCK_TYPE_ID", "IBLOCK_SECTION_ID");
            $arFilter = Array("IBLOCK_ID"=>$PHOENIX_TEMPLATE_ARRAY["ITEMS"]['FAQ']["IBLOCK_ID"], "SECTION_ID" => $arItem["PROPERTIES"]["FAQ_ELEMENTS"]["VALUE"], "ACTIVE"=>"Y", "INCLUDE_SUBSECTIONS" => "N");

            $res = CIBlockElement::GetList(Array("sort" => "asc"), $arFilter, false, false, $arSelect);

            while($ob = $res->GetNextElement())
            {
                $arElement = Array();
                $arElement = $ob->GetFields();
                $arResult["ITEMS"][$key]["ELEMENTS"][] = $arElement;
            }


            $html = "";

            

            if(isset($arItem["PROPERTIES"]['FAQ_QUESTS_TXT_CLR']["VALUE"]{3}))
            {
                $html = "<style>";

                    $html .= "#block".$arItem["ID"]." div.faq-block div.faq div.faq-element:not(.active) div.question span{";

                        $html .= "color: ".$arItem["PROPERTIES"]['FAQ_QUESTS_TXT_CLR']["VALUE"].";";
                        $html .= "border-bottom-color: ".$arItem["PROPERTIES"]['FAQ_QUESTS_TXT_CLR']["VALUE"].";";

                    $html .= "}";

                $html .= "</style>";
            }

            $arResult["ITEMS"][$key]["STYLE"] = $html;

        }
        $arResult["ITEMS"][$key]["BUTTON_CHANGE"] = true;

    }

    if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "tariff" && ($arItem["PROPERTIES"]["TARIFF_VIEW"]["VALUE_XML_ID"] == "" || $arItem["PROPERTIES"]["TARIFF_VIEW"]["VALUE_XML_ID"] == "flat"))
    {
        
        $type = $arItem["PROPERTIES"]["TARIFF_VIEW"]["VALUE_XML_ID"];
        
        if(strlen($type) <= 0)
            $type = "flat";
        
        $valID = $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].$type;
    }

    /*elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "service")
    {
        
        $type = $arItem["PROPERTIES"]["SERVICE_VIEW"]["VALUE_XML_ID"];
        
        if(strlen($type) <= 0)
            $type = "flat";
        
        $valID = $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].$type;
    }*/

    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "blink" && ($arItem["PROPERTIES"]["BLINK_VIEW"]["VALUE_XML_ID"] == "" || $arItem["PROPERTIES"]["BLINK_VIEW"]["VALUE_XML_ID"] == "link"))
    {
        $type = $arItem["PROPERTIES"]["BLINK_VIEW"]["VALUE_XML_ID"];
        
        if(strlen($type) <= 0)
            $type = "link";

        $valID = $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].$type;
            
    }

    elseif( $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "slider" )
    {
        $type = "block-slider";
        $valID = $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].$type;    
    }

    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "opinion")
    {
        
        $type = $arItem["PROPERTIES"]["OPINION_VIEW"]["VALUE_XML_ID"];
        
        if(strlen($type) <= 0)
            $type = "slider";
        
        $valID = $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"].$type;
    }

    else
    {
        $valID = $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"];
    }

    
}


$arResult["SECTION"]['ENUM_COLLS_BLINK'] = array();

foreach($arResult["ITEMS"] as $key=>$arItem)
{

    if($arItem["PROPERTIES"]["MAIN_TITLE_POS"]["VALUE_XML_ID"] == "")
        $arResult["ITEMS"][$key]["PROPERTIES"]["MAIN_TITLE_POS"]["VALUE_XML_ID"] = "def";

    if($arItem["PROPERTIES"]["MAIN_TITLE_POS_MOB"]["VALUE_XML_ID"] == "")
        $arResult["ITEMS"][$key]["PROPERTIES"]["MAIN_TITLE_POS_MOB"]["VALUE_XML_ID"] = "def-mob";
    

    if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "blink"){

        if(($arItem["PROPERTIES"]["BLINK_VIEW"]["VALUE_XML_ID"] == "" || $arItem["PROPERTIES"]["BLINK_VIEW"]["VALUE_XML_ID"] == "link") && empty($arResult["SECTION"]['ENUM_COLLS_BLINK']))
        {
            $property_enums = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$arParams['IBLOCK_ID'], "CODE"=>"BLINK_COLS"));
            $arResult['ENUM_COLLS_BLINK'] = array();
            while($enum_fields = $property_enums->GetNext())
            {
                $arResult["SECTION"]['ENUM_COLLS_BLINK'][] = $enum_fields['ID'];
            }

            unset($property_enums, $enum_fields);
        }
    }

    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "gallery")
    {

        $val=3;
        $arCount = Array();

        for($i=1; $i <=4; $i++)
        {
            if(strlen($arItem["PROPERTIES"]["GALLERY_COUNT_PHOTO_$i"]["VALUE"]) > 0)
                $val=(int)$arItem["PROPERTIES"]["GALLERY_COUNT_PHOTO_$i"]["VALUE"];

            $arCount[$i] = $val;
        }

        $arCountDesc = false;

        if($arItem["PROPERTIES"]["GALLERY_VIEW"]["VALUE_XML_ID"] == "slider")
        {
            if(!empty($arItem["PROPERTIES"]["GALLERY"]["~DESCRIPTION"]))
            {
               foreach($arItem["PROPERTIES"]["GALLERY"]["~DESCRIPTION"] as $desc)
                {
                    if(strlen($desc)>0)
                    {
                        $arCountDesc = true;
                        break;
                    }
                } 
            }

        }

        $arResult["ITEMS"][$key]["GALLERY_COUNT"] = $arCount;
        $arResult["ITEMS"][$key]["GALLERY_COUNT_DESC"] = $arCountDesc;
    }

    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "news")
    {
        $arElements = Array();

        if($arItem["PROPERTIES"]["NEWS_VIEW"]["VALUE_XML_ID"] == "" || $arItem["PROPERTIES"]["NEWS_VIEW"]["VALUE_XML_ID"] =="chrono" || $arItem["PROPERTIES"]["NEWS_VIEW"]["VALUE_XML_ID"] =="flat-2")
        {
            $arResult["ITEMS"][$key]["TITLE_CHANGE"] = true;
            $arResult["ITEMS"][$key]["BUTTON_CHANGE"] = true;
        }

    }

    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "catalog")
    {

        $picture = 0;
        
        if(!empty($arItem["ELEMENTS"]))
        {
            foreach($arItem["ELEMENTS"] as $arCatalog)
            {
                if(isset($arCatalog["PICTURE"]))
                {
                    if(strlen($arCatalog["PICTURE"]) > 0)              
                        $picture++;
                }
                
    
            }
        }
        
        $arResult["ITEMS"][$key]["SHOW_CATALOG_PICTURE"] = $picture;
    }

    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "numbers")
    {
        $string_num = 0;

        if(!empty($arItem["PROPERTIES"]["NUMBERS_TEXTS"]["VALUE"]))
        {
            foreach($arItem["PROPERTIES"]["NUMBERS_TEXTS"]["VALUE"] as $arNum)
            {
                if(strlen(trim($arNum)) > 0)
                    $string_num++;
            }
        }
        

        $arResult["ITEMS"][$key]["STRING_NUM"] = $string_num;

    }

    //advantages
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "advantages")
    {
        $arItem["PIC_COUNT"] = 0;
        $arItem["IC_COUNT"] = 0;
        $arItem["PIC_NAME_COUNT"] = 0;
        $arItem["PIC_DESC_COUNT"] = 0;

        if(strlen($arItem["PROPERTIES"]["ADVANTAGES_IMAGE"]["VALUE"]) > 0 && $menu_count <= 0)
        {
            $arResult["ITEMS"][$key]["PADDING_CHANGE"] = true;
            $arResult["ITEMS"][$key]["TITLE_CHANGE"] = true;
            $arResult["ITEMS"][$key]["BUTTON_CHANGE"] = true;
        }

        if($arItem["PROPERTIES"]["ADVANTAGES_TYPE_PICTURE"]["VALUE_XML_ID"] == "images" || $arItem["PROPERTIES"]["ADVANTAGES_TYPE_PICTURE"]["VALUE_XML_ID"] == "")
        {
            if(!empty($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["VALUE"]))
            {
                foreach($arItem["PROPERTIES"]["ADVANTAGES_PICTURES"]["VALUE"] as $arValue){
                    if(strlen($arValue) > 0){
                        $arItem["PIC_COUNT"]++;
                    }
                }
            }

        }

        else if($arItem["PROPERTIES"]["ADVANTAGES_TYPE_PICTURE"]["VALUE_XML_ID"] == "icons")
        {
            if(!empty($arItem["PROPERTIES"]["ADVANTAGES_ICONS"]["VALUE"]))
            {
                foreach($arItem["PROPERTIES"]["ADVANTAGES_ICONS"]["VALUE"] as $arValue){
                    if(strlen($arValue) > 0){
                        $arItem["IC_COUNT"]++;
                    }
                }
            }
        }

        if(!empty($arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["DESCRIPTION"]))
        {
            foreach($arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["DESCRIPTION"] as $arValue){
                if(strlen($arValue) > 0){
                    $arItem["PIC_NAME_COUNT"]++;
                }
            }
        }

        if(!empty($arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["VALUE"]))
        {
            foreach($arItem["PROPERTIES"]["ADVANTAGES_PICTURES_DESC"]["VALUE"] as $arValue){
                if(strlen($arValue) > 0){
                    $arItem["PIC_DESC_COUNT"]++;
                }
            }
        }


        $arResult["ITEMS"][$key]["PIC_COUNT"] = $arItem["PIC_COUNT"];
        $arResult["ITEMS"][$key]["IC_COUNT"] = $arItem["IC_COUNT"];
        $arResult["ITEMS"][$key]["PIC_NAME_COUNT"] = $arItem["PIC_NAME_COUNT"];
        $arResult["ITEMS"][$key]["PIC_DESC_COUNT"] = $arItem["PIC_DESC_COUNT"];
        $arResult["ITEMS"][$key]["PIC_MAX"] = max($arItem["PIC_COUNT"], $arItem["PIC_DESC_COUNT"], $arItem["PIC_NAME_COUNT"], $arItem["IC_COUNT"]);

    }

    //slider
    elseif( $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "slider" )
    {
        
        $arResult["ITEMS"][$key]["TITLE_CHANGE"] = true;
        $arResult["ITEMS"][$key]["BUTTON_CHANGE"] = true;
        $arResult["ITEMS"][$key]["PADDING_CHANGE"] = true;
        //$arResult["ITEMS"][$key]["HIDDEN_BLOCK_SLIDER"] = true;
        $arResult["ITEMS"][$key]["CUSTOM_MARGIN_PADDING"] = true;
       
    }

    //partners
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "partners" && $arItem["PROPERTIES"]["PARTNERS_VIEW"]["VALUE_XML_ID"] == "partners-slider" )
    {
        $arResult["ITEMS"][$key]["TITLE_CHANGE"] = true;
        $arResult["ITEMS"][$key]["BUTTON_CHANGE"] = true;
    }

    //map
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "map" && $arItem["PROPERTIES"]["MAP_VIEW"]["VALUE_XML_ID"] == "full" )
    {
        $arResult["ITEMS"][$key]["TITLE_CHANGE"] = true;
        $arResult["ITEMS"][$key]["BUTTON_CHANGE"] = true;

        $arSelect = array("ID", "PREVIEW_PICTURE", "PROPERTY_ICON", "PROPERTY_SIGN");

        $arFilter = Array("IBLOCK_ID"=> $PHOENIX_TEMPLATE_ARRAY["ITEMS"]['ADVS']["IBLOCK_ID"], "ID" => $arItem["PROPERTIES"]['MAP_ADVS']['VALUE'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");

        $res = NULL;
        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false);

        $arAdvs = array();

        while($ob = $res->GetNextElement()){ 

            $arFields = $ob->GetFields();
            $arFields["PROPERTIES"] = $ob->GetProperties();

            $arElement["SIGN"] = $arFields["PROPERTIES"]["SIGN"]["~VALUE"];
            $arElement["ICON"] = $arFields["PROPERTIES"]["ICON"]["VALUE"];
            $arElement["COLOR"] = $arFields["PROPERTIES"]["ICON"]["DESCRIPTION"];



            $arElement["PREVIEW_PICTURE_SRC"] = "";
            $arElement["PREVIEW_PICTURE_DESCRIPTION"] = "";

            if($arFields["PREVIEW_PICTURE"])
            {
                $file = CFile::ResizeImageGet($arFields["PREVIEW_PICTURE"], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["DESIGN"]["ITEMS"]["PICTURES_QUALITY"]["VALUE"]);

                $arElement["PREVIEW_PICTURE_SRC"] = $file["src"];

                $preview_pic_db = CFile::GetById($arFields["PREVIEW_PICTURE"]);
                $preview_pic = $preview_pic_db->getNext();

                $arElement["PREVIEW_PICTURE_DESCRIPTION"] = $preview_pic["DESCRIPTION"];
            }

            $arResult["ITEMS"][$key]["ADVS"][] = $arElement;
        }
    }

    //
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "catalog_big_items")
    {
        $arResult["ITEMS"][$key]["TITLE_CHANGE"] = true;
        $arResult["ITEMS"][$key]["HIDE_BUTTON"] = true;
    }
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "form")
    {
        $arResult["ITEMS"][$key]["HIDE_BUTTON"] = true;
    }
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "switcher")
    {
        $arResult["ITEMS"][$key]["HIDE_BUTTON"] = true;
    }
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "blink")
    {
        $arResult["ITEMS"][$key]["HIDE_BUTTON"] = true;
    }
    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "retranslator")
    {
        $arResult["ITEMS"][$key]["HIDE_BUTTON"] = true;
    }
    

    elseif($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "search")
        $arResult["ITEMS"][$key]["ADMIN_CHANGE"] = true;

}



foreach($arResult["ITEMS"] as $key=>$arItem)
{
    if($arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"] == "first_block")
    {
        unset($arResult["ITEMS"][$key]);
        array_unshift($arResult["ITEMS"], $arItem);
    }
  
}


?>
<?endif;?>


<?$frame = $this->createFrame()->begin();?>


    <?/*if(strlen($arResult["SECTION"]["UF_PHX_CSS_USER"]) > 0):?>
      
        <?$this->SetViewTarget("service_head");?>

            <?
                
                $headscript = "<style type='text/css'>".$arResult["SECTION"]["~UF_PHX_CSS_USER"]."</style>";

                echo $headscript;

            ?>
     
        <?$this->EndViewTarget(); ?> 
        
    <?endif;*/?>

    <?if(strlen($arResult["SECTION"]["UF_PHX_JS_USER"]) > 0 || strlen($arResult["SECTION"]["UF_PHX_CSS_USER"]) > 0):?>

        <?$this->SetViewTarget("service_close_body");?>
            
            <?

                $closebodyscript = "";

                if(strlen($arResult["SECTION"]["UF_PHX_JS_USER"]) > 0)
                    $closebodyscript = "<script type='text/javascript'>".$arResult["SECTION"]["~UF_PHX_JS_USER"]."</script>";

                if(strlen($arResult["SECTION"]["UF_PHX_CSS_USER"]) > 0)
                    $closebodyscript .= "<style type='text/css'>".$arResult["SECTION"]["~UF_PHX_CSS_USER"]."</style>";

                echo $closebodyscript;
            ?>
     
        <?$this->EndViewTarget(); ?> 
        
    <?endif;?>

<?$frame->end();?>


<?
$this->__component->arResultCacheKeys = array_merge($this->__component->arResultCacheKeys, array("CACHED_TPL", "COMPONENTS", 'SEO', 'IPROPERTY_TEMPLATES', 'H1_MAIN'));
?>
