<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

/**
 * Дополняет поиск товарами по артикулу (CML2_ARTICLE).
 * Стандартный search.title ищет только по TITLE в b_search_content_title.
 */
function vrnEhkAppendArticleSearchResults(array &$arResult, array $arParams, string $query): void
{
	$query = trim($query);
	if ($query === '' || mb_strlen($query) < 2 || !CModule::IncludeModule('iblock')) {
		return;
	}

	$catalogIblockId = 21;
	$topCount = max(1, (int)($arParams['TOP_COUNT'] ?? 5));
	$existingIds = [];

	foreach ($arResult['CATEGORIES'] as $category) {
		if (empty($category['ITEMS']) || !is_array($category['ITEMS'])) {
			continue;
		}
		foreach ($category['ITEMS'] as $item) {
			if (!empty($item['ITEM_ID']) && ctype_digit((string)$item['ITEM_ID'])) {
				$existingIds[(int)$item['ITEM_ID']] = true;
			}
		}
	}

	$articleItems = [];
	$rsElements = CIBlockElement::GetList(
		['SHOW_COUNTER' => 'DESC', 'NAME' => 'ASC'],
		[
			'IBLOCK_ID' => $catalogIblockId,
			'ACTIVE' => 'Y',
			'ACTIVE_DATE' => 'Y',
			[
				'LOGIC' => 'OR',
				['%PROPERTY_CML2_ARTICLE' => $query],
				['%NAME' => $query],
			],
		],
		false,
		['nTopCount' => $topCount],
		['ID', 'IBLOCK_ID', 'NAME', 'DETAIL_PAGE_URL', 'PROPERTY_CML2_ARTICLE']
	);

	while ($element = $rsElements->GetNext()) {
		$id = (int)$element['ID'];
		if (isset($existingIds[$id])) {
			continue;
		}

		$article = (string)($element['PROPERTY_CML2_ARTICLE_VALUE'] ?? '');
		$name = $element['NAME'];
		if ($article !== '' && $article !== '10.10') {
			$name .= ' — Арт. '.$article;
		}

		$articleItems[] = [
			'NAME' => $name,
			'URL' => $element['DETAIL_PAGE_URL'],
			'MODULE_ID' => 'iblock',
			'PARAM1' => 'iblock',
			'PARAM2' => (string)$catalogIblockId,
			'ITEM_ID' => (string)$id,
		];
		$existingIds[$id] = true;

		if (count($articleItems) >= $topCount) {
			break;
		}
	}

	if (empty($articleItems)) {
		return;
	}

	$categoryKey = null;
	foreach ($arResult['CATEGORIES'] as $key => $category) {
		if (($category['TITLE'] ?? '') === 'Каталог' || (string)$key === '0') {
			$categoryKey = $key;
			break;
		}
	}

	if ($categoryKey === null) {
		$categoryKey = 0;
		$arResult['CATEGORIES'][$categoryKey] = [
			'TITLE' => 'Каталог',
			'ITEMS' => [],
		];
	}

	if (empty($arResult['CATEGORIES'][$categoryKey]['ITEMS']) || !is_array($arResult['CATEGORIES'][$categoryKey]['ITEMS'])) {
		$arResult['CATEGORIES'][$categoryKey]['ITEMS'] = [];
	}

	$arResult['CATEGORIES'][$categoryKey]['ITEMS'] = array_slice(
		array_merge($articleItems, $arResult['CATEGORIES'][$categoryKey]['ITEMS']),
		0,
		$topCount
	);
}

/**
 * Fallback для search.page: товары каталога по названию или артикулу.
 */
function vrnEhkSearchPageByArticle(string $query, int $limit = 20): array
{
	$query = trim($query);
	if ($query === '' || mb_strlen($query) < 2 || !CModule::IncludeModule('iblock')) {
		return [];
	}

	$items = [];
	$rsElements = CIBlockElement::GetList(
		['SHOW_COUNTER' => 'DESC', 'NAME' => 'ASC'],
		[
			'IBLOCK_ID' => 21,
			'ACTIVE' => 'Y',
			'ACTIVE_DATE' => 'Y',
			[
				'LOGIC' => 'OR',
				['%PROPERTY_CML2_ARTICLE' => $query],
				['%NAME' => $query],
			],
		],
		false,
		['nTopCount' => $limit],
		['ID', 'IBLOCK_ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'PROPERTY_CML2_ARTICLE']
	);

	while ($element = $rsElements->GetNext()) {
		$article = (string)($element['PROPERTY_CML2_ARTICLE_VALUE'] ?? '');
		$title = $element['NAME'];
		$body = ($article !== '' && $article !== '10.10') ? 'Арт. '.$article : '';

		$items[] = [
			'ITEM_ID' => $element['ID'],
			'TITLE_FORMATED' => $title,
			'BODY_FORMATED' => $body,
			'URL' => $element['DETAIL_PAGE_URL'],
			'PICTURE' => $element['PREVIEW_PICTURE'] ? CFile::GetPath($element['PREVIEW_PICTURE']) : '',
		];
	}

	return $items;
}

/**
 * Нормализация строки для сравнения артикула/названия.
 */
function vrnEhkNormalizeSearchString(string $value): string
{
	return mb_strtolower(trim($value));
}

/**
 * Точное совпадение: артикул или название товара (100%).
 */
function vrnEhkFindSingleExactMatch(string $query): ?array
{
	$query = trim($query);
	if ($query === '' || !CModule::IncludeModule('iblock')) {
		return null;
	}

	$queryNorm = vrnEhkNormalizeSearchString($query);
	$catalogIblockId = 21;
	$matches = [];

	$rsElements = CIBlockElement::GetList(
		['ID' => 'ASC'],
		[
			'IBLOCK_ID' => $catalogIblockId,
			'ACTIVE' => 'Y',
			'ACTIVE_DATE' => 'Y',
			'PROPERTY_CML2_ARTICLE' => $query,
		],
		false,
		false,
		['ID', 'NAME', 'DETAIL_PAGE_URL', 'PROPERTY_CML2_ARTICLE']
	);

	while ($element = $rsElements->GetNext()) {
		$article = (string)($element['PROPERTY_CML2_ARTICLE_VALUE'] ?? '');
		if ($article === '' || $article === '10.10') {
			continue;
		}
		if (vrnEhkNormalizeSearchString($article) === $queryNorm) {
			$matches[] = $element;
		}
	}

	if (count($matches) === 1) {
		return [
			'ID' => (int)$matches[0]['ID'],
			'URL' => $matches[0]['DETAIL_PAGE_URL'],
		];
	}
	if (count($matches) > 1) {
		return null;
	}

	$rsElements = CIBlockElement::GetList(
		['ID' => 'ASC'],
		[
			'IBLOCK_ID' => $catalogIblockId,
			'ACTIVE' => 'Y',
			'ACTIVE_DATE' => 'Y',
			'=NAME' => $query,
		],
		false,
		false,
		['ID', 'NAME', 'DETAIL_PAGE_URL']
	);

	while ($element = $rsElements->GetNext()) {
		if (vrnEhkNormalizeSearchString($element['NAME']) === $queryNorm) {
			$matches[] = $element;
		}
	}

	if (count($matches) === 1) {
		return [
			'ID' => (int)$matches[0]['ID'],
			'URL' => $matches[0]['DETAIL_PAGE_URL'],
		];
	}

	return null;
}

/**
 * Проверяет, что результат поиска — 100% совпадение с запросом.
 */
function vrnEhkIsExactSearchResult(string $query, array $item): bool
{
	$queryNorm = vrnEhkNormalizeSearchString($query);
	if ($queryNorm === '') {
		return false;
	}

	$title = strip_tags((string)($item['TITLE_FORMATED'] ?? $item['TITLE'] ?? ''));
	if (vrnEhkNormalizeSearchString($title) === $queryNorm) {
		return true;
	}

	if (empty($item['ITEM_ID']) || !CModule::IncludeModule('iblock')) {
		return false;
	}

	$prop = CIBlockElement::GetProperty(21, (int)$item['ITEM_ID'], [], ['CODE' => 'CML2_ARTICLE']);
	if ($row = $prop->Fetch()) {
		$article = (string)($row['VALUE'] ?? '');
		if ($article !== '' && $article !== '10.10' && vrnEhkNormalizeSearchString($article) === $queryNorm) {
			return true;
		}
	}

	return false;
}
