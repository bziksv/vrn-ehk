<?if($_POST["set"] == "Y" )
{
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
	$val = ($_POST["phoenix_hide_adv"]=="true") ? "Y": "N";
	 \Bitrix\Main\Config\Option::set("concept.phoenix", "phoenix_hide_adv", $val);
}

if($_POST["check"] == "Y" )
{
    

	$arRes["OK"] = "N";
	$path = $_SERVER['DOCUMENT_ROOT']."/bitrix/tools/concept.phoenix/ajax/hide_adv.txt";

	$params = array();
	$user = $_POST["USER_ID"];


	$flag = true;

	if (file_exists($path))
	{

        $text = file_get_contents($path);

        $params = unserialize($text);

        if(is_array($params))
        {
        	if(array_key_exists($user, $params) && time() - $params[$user] < 86400)
        		$flag = false;
        }
	}

    if($flag)
    {
        $params[$user] = time();
        $arRes["OK"] = "Y";
    }


    $text = serialize($params);

	$f = fopen($path, 'w');
    fwrite($f, $text);
    fclose($f);

    echo json_encode($arRes);
    
}