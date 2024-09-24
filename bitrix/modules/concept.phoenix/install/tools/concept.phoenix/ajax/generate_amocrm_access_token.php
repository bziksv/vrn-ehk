<?
$subdomain = $_POST['amo_subdomain'];
$link = 'https://' . $subdomain . '/oauth2/access_token';

$data = [
	'client_id' => $_POST['amo_client_id'],
	'client_secret' => $_POST['amo_secret_key'],
	'grant_type' => 'authorization_code',
	'code' => $_POST['amo_code'],
	'redirect_uri' => $_POST['amo_redirect_uri']
];
$curl = curl_init();
curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
curl_setopt($curl,CURLOPT_URL, $link);
curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
curl_setopt($curl,CURLOPT_HEADER, false);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
$out = curl_exec($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

$code = (int)$code;

$Response=json_decode($out,true);

/*if(trim(SITE_CHARSET) == "windows-1251")
{
    $Response["title"] = iconv('windows-1251', 'utf-8', $Response["title"]);
}*/
echo json_encode($Response);
/*$response = json_decode($out, true);
if ($code >= 200 && $code <= 204)
{

}
echo json_encode($response);
*/
// echo $out;