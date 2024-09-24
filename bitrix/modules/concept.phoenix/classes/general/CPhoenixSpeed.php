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
class CPhoenixSpeed{

    public static function ChangePhoenixContent(&$content)
    {    
        global $PHOENIX_TEMPLATE_ARRAY;


        if( (!(defined("ADMIN_SECTION") && ADMIN_SECTION === true) || (!preg_match("/^\/bitrix\//", $_SERVER['REQUEST_URI']))) && $PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['COMPRESS_HTML']['VALUE']['ACTIVE'] == "Y")
        {

            if(preg_match("/^concept_phoenix/", SITE_TEMPLATE_ID))
                $content = preg_replace('~>\s*\n\s*<~', '><', $content);
        }  
    }
    
    
    public static function CompressPhoenixCssJS(&$content)
    {
        if(!(defined("ADMIN_SECTION") && ADMIN_SECTION === true) || (!preg_match("/^\/bitrix\//", $_SERVER['REQUEST_URI'])))
        {

            if(preg_match("/^concept_phoenix/", SITE_TEMPLATE_ID))
            {
                global $PhoenixCssFullList, $PhoenixJSFullList;
                /*$PhoenixCssFullList[] = SITE_TEMPLATE_PATH.'/template_styles.css';
                $PhoenixCssFullList[] = SITE_TEMPLATE_PATH.'/styles.css';*/
                
                if(!empty($PhoenixCssFullList))
                    $FullList = $PhoenixCssFullList;

                if(!empty($PhoenixJSFullList))
                    $FullList = $PhoenixJSFullList;
                

                if(!empty($PhoenixJSFullList) && !empty($PhoenixCssFullList))
                    $FullList = array_merge($PhoenixCssFullList, $PhoenixJSFullList);
                
             
                if(!empty($FullList))
                {
                    foreach($FullList as $file) 
                    {
                        $pathinfo = pathinfo($file);
                        
            
                        $fileMin = $pathinfo['dirname'].'/'.$pathinfo['filename'].'.min.'.$pathinfo['extension'];
                        
                        //unlink($_SERVER["DOCUMENT_ROOT"].$fileMin);
                            
                        $filePath = $_SERVER['DOCUMENT_ROOT'].$file;
                        $fileMinPath = $_SERVER['DOCUMENT_ROOT'].$fileMin;
                        
                        if(substr_count($pathinfo['filename'], ".min") > 0)
                            continue;
                        
                         if (file_exists($filePath) && (!file_exists($fileMinPath) || filemtime($fileMinPath) < filemtime($filePath))) 
                         {
                            $fileContent = file_get_contents($filePath);
                            $fileContent = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $fileContent);
                            $fileContent = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $fileContent);
                            $fileContent = str_replace(array(' 0px', ': ', '; ', ' { ', ' } ', '{ ', ' {', ' }', '} ', ', ', ';}'), array(' 0', ':', ';', '{', '}', '{', '{', '}', '}', ',', '}'), $fileContent);
                            
                            if ($handle = fopen($fileMinPath, 'w')) 
                            {
                                fwrite($handle, $fileContent);
                                fclose($handle); 
                            }
                         }
                        
                    }
                }
            }
        }
    }


    public static function deleteKernelCss(&$content)
    {

        
        global $USER, $APPLICATION, $PHOENIX_TEMPLATE_ARRAY;


        $isAjax = false;

        foreach($_REQUEST as $key => $value) 
        {
            if(substr_count(ToLower($key), "ajax") > 0)
                $isAjax = true;
        }

        if(strpos($content,"</head") <= 0 || strpos($content,"<body") <= 0)
            $isAjax = true;

    
        if($USER->IsAuthorized() || (defined("ADMIN_SECTION") && ADMIN_SECTION === true) || (preg_match("/^\/bitrix\//", $_SERVER['REQUEST_URI'])) || $isAjax || !preg_match("/^concept_phoenix/", SITE_TEMPLATE_ID)) return;
        //if($APPLICATION->GetProperty("save_kernel") == "Y") return;

        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['DELETE_BX_TECH_FILES']['VALUE']['ACTIVE'] == "Y")
        {
            preg_match('/<link.+?href="(.*?kernel_main\/kernel_main.*?\.css)\?\d+"[^>]+>/', $content, $arMatches); 
            
            if(!empty($arMatches[1]))
            {
                $m = explode("<", $arMatches[0]);

                if(!empty($m))
                {
                    foreach($m as $key => $value) 
                    {
                        if(substr_count($value, $arMatches[1]) > 0)
                            $content = str_replace("<".$value, "", $content);
                    }
                }
            }

            preg_match('/<link.+?href="(.*?core\/css\/core.*?\.css)\?\d+"[^>]+>/', $content, $arMatches); 
            
            if(!empty($arMatches[1]))
            {
                $m = explode("<", $arMatches[0]);

                if(!empty($m))
                {
                    foreach($m as $key => $value) 
                    {
                        if(substr_count($value, $arMatches[1]) > 0)
                            $content = str_replace("<".$value, "", $content);
                    }
                }
            }

        }
        
    }

    public static function includeCssInline(&$content)
    {
        
        global $USER, $APPLICATION, $PHOENIX_TEMPLATE_ARRAY;


        $sIncludeCss = "";
        $sIncludeCssClear = "";

        $master_key = "";

        $arMatches = Array();
        $files = Array();
        $pos = array();

        $isAjax = false;

        foreach($_REQUEST as $key => $value) 
        {
            if(substr_count(ToLower($key), "ajax") > 0)
                $isAjax = true;
        }

        
        if(strpos($content,"</head") <= 0 || strpos($content,"<body") <= 0)
            $isAjax = true;

    
        if($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]['TRANSFER_CSS_TO_PAGE']['VALUE']['ACTIVE'] !== "Y" || (defined("ADMIN_SECTION") && ADMIN_SECTION === true) || (preg_match("/^\/bitrix\//", $_SERVER['REQUEST_URI'])) || $isAjax || ($PHOENIX_TEMPLATE_ARRAY["ITEMS"]["OTHER"]["ITEMS"]["SITE_BUILD_ON"]['VALUE']["ACTIVE"] == "Y" && !$PHOENIX_TEMPLATE_ARRAY["IS_ADMIN"]) || !preg_match("/^concept_phoenix/", SITE_TEMPLATE_ID)) return;
        


        preg_match('/<link.+?href="(\/bitrix\/cache\/css\/'.SITE_ID.'\/'.SITE_TEMPLATE_ID.'\/template_[^"]+)"[^>]+>/', $content, $arMatches);    

        if(!empty($arMatches[1]))
        {
            $master_key .= $arMatches[1];
            $files[] = $arMatches[1];

            $m = explode("<", $arMatches[0]);

            if(!empty($m))
            {
                foreach($m as $key => $value) 
                {
                    if(substr_count($value, $arMatches[1]) > 0)
                        $pos[] = "<".$value;
                }
            }
        }

        
        preg_match('/<link.+?href="(\/bitrix\/cache\/css\/'.SITE_ID.'\/'.SITE_TEMPLATE_ID.'\/page_[^"]+)"[^>]+>/', $content, $arMatches);
        
        if(!empty($arMatches[1]))
        {
            $master_key .= $arMatches[1];
            $files[] = $arMatches[1];

            $m = explode("<", $arMatches[0]);

            if(!empty($m))
            {
                foreach($m as $key => $value) 
                {
                    if(substr_count($value, $arMatches[1]) > 0)
                        $pos[] = "<".$value;
                }  
            }      
        }

        preg_match('/<link.+?href="(\/bitrix\/cache\/css\/'.SITE_ID.'\/'.SITE_TEMPLATE_ID.'\/default_[^"]+)"[^>]+>/', $content, $arMatches);

        if(!empty($arMatches[1]))
        {
            $master_key .= $arMatches[1];
            $files[] = $arMatches[1];

            $m = explode("<", $arMatches[0]);

            if(!empty($m))
            {
                foreach($m as $key => $value) 
                {
                    if(substr_count($value, $arMatches[1]) > 0)
                        $pos[] = "<".$value;
                }
            }
        }


        $obCache = new CPHPCache;
        $life_time = 24*3600;

        if($USER->isAdmin())
            $life_time = 0;

        $master_key = md5($master_key);



        if($obCache->InitCache($life_time, $master_key, "/")) 
        {
            $vars = $obCache->GetVars();
            $sIncludeCss = $vars["sIncludeCss"];
            $sIncludeCssClear = $vars["sIncludeCssClear"];
        } 
        else 
        {
            if(!empty($files))
            {
                foreach ($files as $key => $value) 
                {
                    $value = explode("?", $value);
                    $sIncludeCss .= file_get_contents($_SERVER["DOCUMENT_ROOT"].$value[0]);
                }

            }

            $sIncludeCssClear = $sIncludeCss;
        }
        
        if(!empty($pos))
        {
            foreach($pos as $key => $value) 
                $content = str_replace($value, "", $content);
        }
        

        $content = str_replace("</head>", "<style>$sIncludeCssClear</style></head>", $content);


        /* if($obCache->StartDataCache()) 
        {
            $obCache->EndDataCache(array(
             "sIncludeCss" => $sIncludeCss,
             "sIncludeCssClear" => $sIncludeCssClear,
            ));
        } */
        
        
    }


}
?>