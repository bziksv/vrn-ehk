<?
$access_token = $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_TOKEN"]["VALUE"];

if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["SEND_TO_AMO"]['VALUE']['ACTIVE'] == 'Y' && strlen($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_URL"]['VALUE'])>0 && strlen($access_token)>0)
{


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


	$subdomain = trim($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["LIDS"]["ITEMS"]["AMO_URL"]['VALUE']);

	$link = 'https://' . $subdomain . '/api/v4/leads/complex';


	$headers = [
		'Authorization: Bearer ' . $access_token
	];


	$lead = array(
		array(
			"name" => $title,
		    "price" => 0,
		    "created_at"=>time(),
		    "_embedded" => array(
		    	"contacts" => array(
		    		array(
			    		"first_name"=> $nameamo,
			    		"custom_fields_values" => array(
			    			array(
			    				"field_code"=> 'EMAIL',
			    				"values"=>array(
			    					array(
			    						"enum_code"=>"WORK",
			    						"value"=>$emailamo
			    					)
			    				),
			    			),
			    			array(
			    				"field_code"=> 'PHONE',
			    				"values"=>array(
			    					array(
			    						"enum_code"=>"WORK",
			    						"value"=>$phoneamo
			    					)
			    				),
			    			)
			    		)
					)
		    	),
		    )
		),
	);

	$lead =json_encode($lead);


	$curl = curl_init();
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
	curl_setopt($curl,CURLOPT_URL, $link);
	curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl,CURLOPT_HEADER, false);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $lead);
	$out = curl_exec($curl);
	$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	$code = (int)$code;


	$Response=json_decode($out,true);
	

	
    dump($Response);

	if($code >= 200 && $code <= 204)
	{

		if($Response[0]['id'] && strlen($mess)>0)
		{

			$link = 'https://' . $subdomain . '/api/v4/leads/'.$Response[0]['id'].'/notes';


			$lead = array(
				array(
					"note_type" => "common",
					"params"=>array(
						"text"=>$mess
					)
				),
			);

			$lead =json_encode($lead);

			$curl = curl_init();
			curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
			curl_setopt($curl,CURLOPT_URL, $link);
			curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl,CURLOPT_HEADER, false);
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $lead);
			$out = curl_exec($curl);
			$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
			$code = (int)$code;


		}
	}

}