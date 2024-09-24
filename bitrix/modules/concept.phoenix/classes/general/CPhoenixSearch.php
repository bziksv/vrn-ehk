<?
class CPhoenixSearch{

    public static function onBeforeIndexHandler($arFields)
    {
        CModule::IncludeModule('iblock');

        $skipPreviewText = "";
        $arrIBlock = Array(); 
        $arrModIBlock = Array();

        $arIBlockCodes = Array();

        $arIBlockCodes[] = "concept_phoenix_catalog";
        $arIBlockCodes[] = "concept_phoenix_catalog_offers";
        $arIBlockCodes[] = "concept_phoenix_brand";
        $arIBlockCodes[] = "concept_phoenix_blog";
        $arIBlockCodes[] = "concept_phoenix_news";
        $arIBlockCodes[] = "concept_phoenix_action";

        $arModIBlockCodes = Array();

        $arModIBlockCodes[] = "concept_phoenix_catalog";
        $arModIBlockCodes[] = "concept_phoenix_catalog_offers";
        $arModIBlockCodes[] = "concept_phoenix_brand";
        //$arIBlockCodes[] = "concept_phoenix_blog";
        //$arIBlockCodes[] = "concept_phoenix_news";
        //$arIBlockCodes[] = "concept_phoenix_action";
        
        $arSites = Array();
    
        if(!is_array($arFields["SITE_ID"]))
            $arSites[] = $arFields["SITE_ID"];
        else
            $arSites = $arFields["SITE_ID"];

        if(!empty($arSites))
        {
            foreach($arSites as $siteID)
            {
                $iblockType = "concept_phoenix_".$siteID;
   
                $db_iblock_type = CIBlockType::GetList(
                    Array(),
                    Array("ID" => $iblockType)
                );
                
                if($db_iblock_type->SelectedRowsCount() > 0)
                {
                    $skipPreviewText = \Bitrix\Main\Config\Option::get("concept.phoenix", "phoenix_search_skip_catalog_preview_text", false, $siteID);
                    $arIBlock = Array();
               
                    $res = CIBlock::GetList(
                        Array(), 
                        Array(
                            'TYPE'=>$iblockType, 
                            'ACTIVE'=>'Y', 
                        ), false
                    );
                    
                    while($ar_res = $res->Fetch())
                    {
                        $code = str_replace("_".$siteID, "", $ar_res["CODE"]);

                        if(in_array($code, $arIBlockCodes))
                            $arrIBlock[] = $ar_res["ID"];

                        if(in_array($code, $arModIBlockCodes))
                            $arrModIBlock[] = $ar_res["ID"];
                    }
                }
            }
        }

        if($arFields["MODULE_ID"] == "iblock" && !empty($arFields["PARAM2"]) && !empty($arrIBlock)) 
        {        

            if(in_array($arFields["PARAM2"], $arrIBlock))
            {
                
                $flag = true;
                $check = substr($arFields["ITEM_ID"], 0, 1);
               
                if($check == "S") 
                {
                    $res = CIBlockSection::GetList(
                        Array('SORT' => 'ASC'), 
                        Array("IBLOCK_ID" => $arFields["PARAM2"], "ACTIVE" => "Y", "ID" => substr($arFields["ITEM_ID"], 1), "GLOBAL_ACTIVE" => "Y"), 
                        false,
                        Array("ID", "DESCRIPTION")
                    );

                    if($arElement = $res->GetNext())
                    {
                        $arDelFields = array("DESCRIPTION");

                        if(!empty($arDelFields))
                        {
                            foreach($arDelFields as $value) 
                            {
                                if(isset($arElement[$value]) && strlen($arElement[$value]) > 0)
                                {
                                   $arFields["BODY"] = str_replace(CSearch::KillTags($arElement[$value]), "", $arFields["BODY"]);
                                }    
                            }
                        }
                        
                    }
                    else
                    {
                        $flag = false;
                    }
                } 
                else 
                {
                    $arIBlockElement = array();

                    $arFilter = Array("ID"=> $arFields["ITEM_ID"], "ACTIVE"=>"Y");
                    $r = CIBlockElement::GetList($arSort, $arFilter, false,false, array("IBLOCK_SECTION_ID", "ID"));

                    $arIBlockElement = $r->GetNext();
                    

                    $ar_new_groups = array();
                    $db_old_groups = CIBlockElement::GetElementGroups($arFields["ITEM_ID"], true, array("ID", "GLOBAL_ACTIVE"));
                    $ar_new_groups = Array($NEW_GROUP_ID);

                    while($ar_group = $db_old_groups->Fetch())
                    {
                        if($ar_group["GLOBAL_ACTIVE"] == "Y")
                            $ar_new_groups[] = $ar_group["ID"];
                    }

                    if(!empty($ar_new_groups))
                        $ar_new_groups = array_diff($ar_new_groups, array(''));

                    if(empty($ar_new_groups) && $arIBlockElement["IBLOCK_SECTION_ID"] > 0)
                    {
                        $flag = false;
                    }
                    else
                    {
                        
                        $arDelFields = array("DETAIL_TEXT"); 

                        if($skipPreviewText == "Y")
                            $arDelFields[] = "PREVIEW_TEXT";

                        if(!empty($arrModIBlock))  
                        {  

                            if(in_array($arFields["PARAM2"], $arrModIBlock) && intval($arFields["ITEM_ID"]) > 0)
                            {
                              $dbElement = CIblockElement::GetByID($arFields["ITEM_ID"]);
                              
                              if($arElement = $dbElement->Fetch())
                              {
                                if(!empty($arDelFields))
                                {
                                    foreach ($arDelFields as $value) 
                                    {
                                        if(isset ($arElement[$value]) && strlen($arElement[$value])> 0)
                                        {
                                           $arFields["BODY"] = str_replace(CSearch::KillTags($arElement[$value]), "", $arFields["BODY"]);
                                        }    
                                    }
                                }          
                              } 

                            } 

                        } 
                        
                    }
                }

                if(!$flag) 
                {
                    $arFields["TITLE"] = "";
                    $arFields["BODY"] = "";
                   
                }
            }
              

        }

        return $arFields;       
    }
}
?>