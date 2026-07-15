<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/include/search_by_article.php';

$query = trim((string)($arResult['REQUEST']['~QUERY'] ?? $arResult['REQUEST']['QUERY'] ?? ''));
if ($query !== '') {
	$arResult['SEARCH'] = array_values(array_filter($arResult['SEARCH'], static function ($arItem) {
		$iblockId = (string)($arItem['PARAM2'] ?? '');
		return $iblockId === '' || $iblockId === '21';
	}));
}

if (empty($arResult['SEARCH']) && $query !== '') {
	$articleResults = vrnEhkSearchPageByArticle($query, (int)($arParams['PAGE_RESULT_COUNT'] ?? 20));
	if (!empty($articleResults)) {
		$arResult['SEARCH'] = $articleResults;
	}
}

if (count($arResult['SEARCH']) === 1 && vrnEhkIsExactSearchResult($query, $arResult['SEARCH'][0])) {
	LocalRedirect($arResult['SEARCH'][0]['URL']);
}

$arResult["TAGS_CHAIN"] = array();
if($arResult["REQUEST"]["~TAGS"])
{
	$res = array_unique(explode(",", $arResult["REQUEST"]["~TAGS"]));
	$url = array();
	foreach ($res as $key => $tags)
	{
		$tags = trim($tags);
		if(!empty($tags))
		{
			$url_without = $res;
			unset($url_without[$key]);
			$url[$tags] = $tags;
			$result = array(
				"TAG_NAME" => htmlspecialcharsex($tags),
				"TAG_PATH" => $APPLICATION->GetCurPageParam("tags=".urlencode(implode(",", $url)), array("tags")),
				"TAG_WITHOUT" => $APPLICATION->GetCurPageParam((count($url_without) > 0 ? "tags=".urlencode(implode(",", $url_without)) : ""), array("tags")),
			);
			
			$arResult["TAGS_CHAIN"][] = $result;
		}
	}
}

foreach($arResult["SEARCH"] as &$arItem){
	if (empty($arItem['ITEM_ID']) || !ctype_digit((string)$arItem['ITEM_ID'])) {
		continue;
	}
	$res = CIBlockElement::GetByID($arItem['ITEM_ID'])->GetNext();
	if ($res && !empty($res['PREVIEW_PICTURE'])) {
		$arItem['PICTURE'] = CFile::GetPath($res['PREVIEW_PICTURE']);
	}
}
?>