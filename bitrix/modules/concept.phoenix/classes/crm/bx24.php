<?
if(
   $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_ON"]['VALUE']['ACTIVE'] == 'Y' 
   && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_URL"]['VALUE']) > 0 
   && 
   (strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_LOG"]['VALUE']) > 0 
   && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_PAS"]['VALUE']) > 0)
   ||
   (strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_ID"]['VALUE']) > 0 
   && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_WEB_HOOK"]['VALUE']) > 0)

)
{
   

   $crmUrl = "https://".trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_URL"]['VALUE'])."/";
   $login = trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_LOG"]['VALUE']);
   $password = trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_PAS"]['VALUE']);
   
   
   $title = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TITLE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["NAME_SITE"]["VALUE"];

   $mess = "";
   
   $mess .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT2"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["NAME_SITE"]["VALUE"]." (".$domen.")<br/>";

    if($header)
        $mess .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT3"].$header."<br/>";

    if(isset($form_name) && strlen($form_name))
        $mess .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT6"].$form_name."<br/><br/>";

    if($url)
        $mess .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT4"].$url."<br/><br/>";
   
   $mess .= $message;

   $namebx = $name;
   $phonebx = $phone;
   
   if(trim(SITE_CHARSET) == "windows-1251")
   {
       $title = iconv('windows-1251', 'utf-8', $title);
       $namebx = iconv('windows-1251', 'utf-8', $namebx);
       $phonebx = iconv('windows-1251', 'utf-8', $phonebx);
       $mess = iconv('windows-1251', 'utf-8', $mess);
   }
   
   
   $arParams = array(
       'LOGIN' => $login, 
       'PASSWORD' => $password, 
       'TITLE' => $title
   );
    
   if(strlen($namebx) > 0)
       $arParams['NAME'] = $namebx;

   if(strlen($phonebx) > 0)
   {
       $arParams['PHONE_WORK'] = $phonebx;

       $arParams["PHONE"][] = Array(
           "VALUE" => $phonebx, 
           "VALUE_TYPE" => "WORK"
       );
   }
       
   if(strlen($email) > 0)
   {
       $arParams['EMAIL_WORK'] = $email;

       $arParams["EMAIL"][] = Array(
           "VALUE" => $email, 
           "VALUE_TYPE" => "WORK"
       );
   }
       
   if(strlen($mess) > 0)
       $arParams['COMMENTS'] = $mess;

   if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_ASSIGNED_BY_ID"]['VALUE']) > 0)
       $arParams['ASSIGNED_BY_ID'] = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_ASSIGNED_BY_ID"]['VALUE'];


   if(isset($_COOKIE["phoenix_UTM_SOURCE_".$siteID]))
       $arParams['UTM_SOURCE'] = $_COOKIE["phoenix_UTM_SOURCE_".$siteID];

   if(isset($_COOKIE["phoenix_UTM_MEDIUM_".$siteID]))
       $arParams['UTM_MEDIUM'] =$_COOKIE["phoenix_UTM_MEDIUM_".$siteID];

   if(isset($_COOKIE["phoenix_UTM_CAMPAIGN_".$siteID]))
       $arParams['UTM_CAMPAIGN'] = $_COOKIE["phoenix_UTM_CAMPAIGN_".$siteID];

   if(isset($_COOKIE["phoenix_UTM_CONTENT_".$siteID]))
       $arParams['UTM_CONTENT'] = $_COOKIE["phoenix_UTM_CONTENT_".$siteID];

   if(isset($_COOKIE["phoenix_UTM_TERM_".$siteID]))
       $arParams['UTM_TERM'] = $_COOKIE["phoenix_UTM_TERM_".$siteID];
   


   $arParams['SOURCE_ID'] = "WEB";
       
   
   if(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_ID"]['VALUE']) > 0 
   && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_WEB_HOOK"]['VALUE']) > 0)
   {
       $queryData = http_build_query(array(
           'fields' => $arParams,
           'params' => array("REGISTER_SONET_EVENT" => "Y"),
       ));

       $rest = 'crm.lead.add.json';
       $queryUrl = $crmUrl.'rest/'.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_ID"]['VALUE'].'/'.$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_WEB_HOOK"]['VALUE'].'/'.$rest;
       $curl = curl_init();
       curl_setopt_array($curl, array(
           CURLOPT_SSL_VERIFYPEER => 0,
           CURLOPT_POST => 1,
           CURLOPT_HEADER => 0,
           CURLOPT_RETURNTRANSFER => 1,
           CURLOPT_URL => $queryUrl,
           CURLOPT_POSTFIELDS => $queryData,
       ));
       $result = curl_exec($curl);
       curl_close($curl);

   }
   elseif(strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_LOG"]['VALUE']) > 0 
   && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["BX_PAS"]['VALUE']) > 0)
   {
       $obHttp = new CHTTP();
       $result = $obHttp->Post($crmUrl.'crm/configs/import/lead.php', $arParams);
   }
   
   

}