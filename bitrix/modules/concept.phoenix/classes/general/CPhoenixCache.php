<?
class CPhoenixCache
{
	public static function _SaveDataCache($obCache, $arRes, $cacheTag, $cachePath, $cacheTime, $cacheID)
	{
		if($cacheTime > 0 && \Bitrix\Main\Config\Option::get("main", "component_cache_on", "Y") != "N")
		{
			$obCache->StartDataCache($cacheTime, $cacheID, $cachePath);

			if(strlen($cacheTag)){
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache($cachePath);
				$CACHE_MANAGER->RegisterTag($cacheTag);
				$CACHE_MANAGER->EndTagCache();
			}

			$obCache->EndDataCache(array("arRes" => $arRes));
		}
	}

	public static function _InitCacheParams($moduleName, $functionName, $arCache)
	{
		CModule::IncludeModule($moduleName);
		$cacheTag = $arCache["TAG"];
		$cachePath = $arCache["PATH"];
		$cacheTime = ($arCache["TIME"] > 0 ? $arCache["TIME"] : 36000000);
		if(!strlen($cacheTag)){
			$cacheTag = "_notag";
		}
		if(!strlen($cachePath)){
			$cachePath = "/CPhoenixCache/".$moduleName."/".$functionName."/".$cacheTag."/";
		}
		return array($cacheTag, $cachePath, $cacheTime);
	}
}