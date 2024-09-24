<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (CModule::IncludeModule("iblock")):

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arResult = array();

if ($this->StartResultCache())
{

  $ID = $arParams['ELEMENT_ID'];

  if(strlen($arParams["ALL_ID"]) > 0)
  {
    $filter["ID"] = explode("," , $arParams["ALL_ID"]);;
  }
  else
  {
    $filter["ID"] = Array(0);
  }
  
  $rs = CIBlockElement::GetByID($ID);


  $ar_id = $rs->GetNext();

  $arFilter = Array("IBLOCK_ID" => $ar_id["IBLOCK_ID"], "ACTIVE" => "Y", "INCLUDE_SUBSECTIONS" => "Y");
        
	//global $APPLICATION;
	//$FILTER_VALUE = $APPLICATION->get_cookie("FILTER_VALUE");
        
	//$FILTER_VALUE = unserialize($FILTER_VALUE);

	$arFilter = array_merge($arFilter, $filter);

  $res = CIBlockElement::GetList(Array("sort"=>"ASC","id"=>"ASC"), $arFilter, false, Array("nElementID"=>$ID,"nPageSize"=>"1"), Array("NAME", "DETAIL_PAGE_URL", "ID"));

  while($ar_fields = $res->GetNext())
  {
    $mas[] = array("name"=>$ar_fields["NAME"], "DETAIL_PAGE_URL"=>$ar_fields["DETAIL_PAGE_URL"], "ID"=>$ar_fields["ID"]);
  }
  if(!empty($mas))
  {


      if (count($mas) == 3)
      {
          $arResult = array(
               "NEXT_ID" => $mas['2']['ID'],
               "NEXT_NAME" => $mas['2']['name'],
               "NEXT_SRC" => $mas['2']['DETAIL_PAGE_URL'],
               "LAST_ID" => $mas['0']['ID'],
               "LAST_NAME" => $mas['0']['name'],
               "LAST_SRC" => $mas['0']['DETAIL_PAGE_URL'],
           );
       }
       


    if ((count($mas) == 2) and ($ID == $mas['0']['ID']))
      {
            $arFilter = Array('GLOBAL_ACTIVE'=>'Y', 'IBLOCK_CODE'=> $ar_id["IBLOCK_CODE"]);
            $db_list = CIBlockSection::GetTreeList($arFilter);
            $s = "";
            $l = "0";
            while($ar_result = $db_list->GetNext())
              {
                if ($ar_result["ID"] == $ar_id["IBLOCK_SECTION_ID"])
                  {
                   $l = 1;
                  }
                if ($l == "1")
                  {
                   //$s[] = $ar_result["ID"];
                  }


               
              }

            $s = $ar_id["IBLOCK_SECTION_ID"];

            
            $arFilter = Array("IBLOCK_ID" => $ar_id["IBLOCK_ID"], "ACTIVE" => "Y", "SECTION_ID" => $s, "!ID" => $ID);
            

            

          $arFilter = array_merge($arFilter, $filter);
            
            $res = CIBlockElement::GetList(Array("sort"=>"ASC","id"=>"ASC"), $arFilter, false, false, Array("NAME", "DETAIL_PAGE_URL", "ID"));

            while($arfields = $res->GetNext())
            {
                $arResult = array(
                   "NEXT_ID" => $mas['1']['ID'],
                   "NEXT_NAME" => $mas['1']['name'],
                   "NEXT_SRC" => $mas['1']['DETAIL_PAGE_URL'],
                   "LAST_ID" => $arfields['ID'],
                   "LAST_NAME" => $arfields['NAME'],
                   "LAST_SRC" => $arfields['DETAIL_PAGE_URL'],
                );
            }

            
       }



    if ((count($mas) == 2) and ($ID == $mas['1']['ID']))
      {

            $arFilter = Array('GLOBAL_ACTIVE'=>'Y', 'IBLOCK_CODE'=> $ar_id["IBLOCK_CODE"]);
            $db_list = CIBlockSection::GetTreeList($arFilter);
            $s = "";
            $l = "0";
            while($ar_result = $db_list->GetNext())
              {
                if ($ar_result["ID"] == $ar_id["IBLOCK_SECTION_ID"])
                  {
                   $l = 1;
                  }
                if ($l == "1")
                  {
                   //$s[] = $ar_result["ID"];
                  }
               
              }

            $s = $ar_id["IBLOCK_SECTION_ID"];
            
            
            $arFilter = Array("IBLOCK_ID" => $ar_id["IBLOCK_ID"], "ACTIVE" => "Y", "SECTION_ID" => $s, "!ID" => $ID);
            
        	  //global $APPLICATION;
        	  //$FILTER_VALUE = $APPLICATION->get_cookie("FILTER_VALUE");
                
        	  //$FILTER_VALUE = unserialize($FILTER_VALUE);
                
        	 
                $arFilter = array_merge($arFilter, $filter);

            $res = CIBlockElement::GetList(Array("sort"=>"ASC","id"=>"ASC"),$arFilter, false, Array("nPageSize"=>"1"), Array("NAME", "DETAIL_PAGE_URL", "ID"));

            $arfields = $res->GetNext();

            $arResult = array(
               "NEXT_ID" => $arfields['ID'],
               "NEXT_NAME" => $arfields['NAME'],
               "NEXT_SRC" => $arfields['DETAIL_PAGE_URL'],
               "LAST_ID" => $mas['0']['ID'],
               "LAST_NAME" => $mas['0']['name'],
               "LAST_SRC" => $mas['0']['DETAIL_PAGE_URL'],
            );

       }


      if ($arParams["CACHE_TYPE"] == "N")
            $this->AbortResultCache();


        $this->IncludeComponentTemplate();
    }
}

endif;

?>