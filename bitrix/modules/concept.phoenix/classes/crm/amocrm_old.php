<?
if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["SEND_TO_AMO"]['VALUE']['ACTIVE'] == 'Y' && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_URL"]['VALUE']) > 0 && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_LOG"]['VALUE']) > 0 && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_HASH"]['VALUE']) > 0)
{
    
    $crmUrl = "https://".trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_URL"]['VALUE'])."/"; 
    $login = trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_LOG"]['VALUE']);
    $hash = trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_HASH"]['VALUE']);
    
    $title = $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TITLE"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["NAME_SITE"]["VALUE"];
    
    $mess = "";
    
    $mess .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT2"].$PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["NAME_SITE"]["VALUE"]." (".$domen.")\r\n";

    if($header)
        $mess .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT3"].$header."\r\n";

    if(isset($form_name) && strlen($form_name))
        $mess .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT6"].$form_name."\r\n\r\n";

    if($url)
      $mess .= $PHOENIX_TEMPLATE_ARRAY["MESS"]["SEND_TEXT4"].$url."\r\n\r\n";
    
    $mess .= $message;

    $nameamo = $name;
    $phoneamo = $phone;
    $emailamo = $email;
    
    if(trim(SITE_CHARSET) == "windows-1251")
    {
        $title = iconv('windows-1251', 'utf-8', $title);
        $nameamo = iconv('windows-1251', 'utf-8', $nameamo);
        $phoneamo = iconv('windows-1251', 'utf-8', $phoneamo);
        $emailamo = iconv('windows-1251', 'utf-8', $emailamo);
        $mess = iconv('windows-1251', 'utf-8', $mess);
    }
    
    $mess = str_replace(Array("<b>","</b>","<br>","<br/>","<div>","</div>"), Array("", "", "\r\n", "\r\n", "", "\r\n"), $mess);

    $mess = html_entity_decode(strip_tags($mess));
    
    require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/concept.phoenix/amocrm/add.php');
    
}
?>