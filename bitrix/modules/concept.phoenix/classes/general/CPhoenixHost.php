<?use \Bitrix\Main\Config\Option as Option;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Loader,
    Bitrix\Main\ModuleManager,
    Bitrix\Iblock,
    Bitrix\Catalog,
    Bitrix\Currency,
    Bitrix\Currency\CurrencyTable,
    Bitrix\Main,
    Bitrix\Sale,
    Bitrix\Sale\Order,
    Bitrix\Main\Application,
    Bitrix\Sale\DiscountCouponsManager,
    Bitrix\Main\Localization\Loc;

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/concept.phoenix/classes/general/CPhoenix.php");
class CPhoenixHost
{
    public static function convertFromPunycode($host, $cut_www = "Y") 
    {
        require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/concept.phoenix/classes/general/convert_class.php');
        $reshost = "";

        if(strlen($host)>0)
        {
            $reshost = $host;

            if(stripos($host, 'xn--') !== false) 
            {
                $idn = new Phoenix_idna_convert(array('idn_version'=>2008));
                $punycode = $idn->decode($host);
                
                if(trim(SITE_CHARSET) == "windows-1251")
                    $punycode = utf8win1251($punycode);
                
                $reshost = $punycode;
            }

            $reshost = explode(":", $reshost);
            $reshost = $reshost[0];

            if($cut_www == "Y")
            {
                $sub = substr($reshost, 0, 4);

                if($sub == "www.")
                    $reshost = substr_replace($reshost, "", 0, 4);
            }
        }
        else
            $reshost = "errHost";


        return $reshost;
    }

    public static function getHostTranslit()
    {
        $res = Cutil::translit(CPhoenixHost::getHost($_SERVER, "N", "N"),"ru", array("replace_space"=>"_","replace_other"=>"_"));

        return $res;
    }

    public static function getHost($server, $with_protocol = "Y", $cut_www = "Y")
    {
        if( !empty($server) )
        {
            $protocol = "";

            if($with_protocol == "Y")
            {
                $protocol = 'http://';

                if ((isset($server['REQUEST_SCHEME']) && $server['REQUEST_SCHEME'] == 'https') || (isset($server['HTTPS']) && ToLower($server['HTTPS']) == 'on'))
                    $protocol = 'https://';
            }

            $host = $server["HTTP_HOST"];

            if(strlen($server["HTTP_HOST"])<=0)
                $host = $server["SERVER_NAME"];

            $host = explode(":", $host);
            $host = $host[0];

            $host = self::convertFromPunycode($host, $cut_www);
               
            $host = $protocol.$host;

        }
        else
        {
            $host = "$_SERVER is empty";
        }
        
        return $host;
    }

} 
?>