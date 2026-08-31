<?php

class AvitoPhotoService
{
	const IBLOCK_TEMPLATE = 23;
	const IBLOCK_CATALOG = 21;
	const FALLBACK_WHITE_IMAGE = '/avito_photo/white-_1_.jpg';
	const PREVIEW_MAX_WIDTH = 1500;
	const PREVIEW_MAX_HEIGHT = 1000;

	/**
	 * @return array{white: int, height: int, width: int}|null
	 */
	public static function loadWhiteTemplate()
	{
		$arSelect = array('ID', 'PREVIEW_PICTURE', 'PROPERTY_height', 'PROPERTY_width');
		$arFilter = array(
			'IBLOCK_ID' => self::IBLOCK_TEMPLATE,
			'!PREVIEW_PICTURE' => false,
			'!PROPERTY_height' => false,
			'!PROPERTY_width' => false,
		);

		$res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1), $arSelect);
		if (!$ob = $res->GetNextElement()) {
			return null;
		}

		$arFields = $ob->GetFields();
		$whiteId = (int)$arFields['ID'];
		$white = (int)$arFields['PREVIEW_PICTURE'];
		$templateHeight = (int)$arFields['PROPERTY_HEIGHT_VALUE'];
		$templateWidth = (int)$arFields['PROPERTY_WIDTH_VALUE'];

		$whitePath = CFile::GetPath($white);
		if (!$whitePath || !file_exists($_SERVER['DOCUMENT_ROOT'] . $whitePath)) {
			$fallbackPath = $_SERVER['DOCUMENT_ROOT'] . self::FALLBACK_WHITE_IMAGE;
			if (!file_exists($fallbackPath)) {
				return null;
			}

			$el = new CIBlockElement();
			$el->Update($whiteId, array(
				'PREVIEW_PICTURE' => CFile::MakeFileArray($fallbackPath),
			));

			$res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1), $arSelect);
			if (!$ob = $res->GetNextElement()) {
				return null;
			}

			$arFields = $ob->GetFields();
			$white = (int)$arFields['PREVIEW_PICTURE'];
			$templateHeight = (int)$arFields['PROPERTY_HEIGHT_VALUE'];
			$templateWidth = (int)$arFields['PROPERTY_WIDTH_VALUE'];
		}

		if (!$white || !$templateHeight || !$templateWidth) {
			return null;
		}

		return array(
			'white' => $white,
			'height' => $templateHeight,
			'width' => $templateWidth,
		);
	}

	public static function cleanupBrokenAvitoImages()
	{
		$removed = 0;
		$arSelect = array('ID', 'PROPERTY_avito');
		$arFilter = array(
			'IBLOCK_ID' => self::IBLOCK_CATALOG,
			'!PREVIEW_PICTURE' => false,
			'!PROPERTY_avito' => false,
		);

		$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
		while ($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$avitoPath = false;

			if ($arFields['PROPERTY_AVITO_VALUE']) {
				$avitoPath = CFile::GetPath($arFields['PROPERTY_AVITO_VALUE']);
			}

			if (!$avitoPath || !file_exists($_SERVER['DOCUMENT_ROOT'] . $avitoPath)) {
				CIBlockElement::SetPropertyValuesEx(
					$arFields['ID'],
					self::IBLOCK_CATALOG,
					array('avito' => array('VALUE' => array('del' => 'Y')))
				);
				$removed++;
			}
		}

		return $removed;
	}

	/**
	 * Очередь: активные товары каталога с превью и без свойства avito.
	 */
	public static function pendingFilter()
	{
		return array(
			'IBLOCK_ID' => self::IBLOCK_CATALOG,
			'ACTIVE' => 'Y',
			'!PREVIEW_PICTURE' => false,
			'PROPERTY_avito' => false,
		);
	}

	/**
	 * @return array{processed: int, ids: int[], remaining: int, errors: string[], skipped: int}
	 */
	public static function processBatch($limit = 1, $cleanupFirst = false)
	{
		$result = array(
			'processed' => 0,
			'ids' => array(),
			'remaining' => 0,
			'errors' => array(),
			'skipped' => 0,
		);

		$limit = max(1, (int)$limit);
		$template = self::loadWhiteTemplate();
		if (!$template) {
			$result['errors'][] = 'White template is not configured in iblock ' . self::IBLOCK_TEMPLATE;
			return $result;
		}

		if ($cleanupFirst) {
			self::cleanupBrokenAvitoImages();
		}

		$arSelect = array('ID', 'PREVIEW_PICTURE');
		$arFilter = self::pendingFilter();
		// Берём запас: часть превью битые (файл есть в БД, нет на диске).
		$fetchLimit = max($limit * 20, $limit);

		$res = CIBlockElement::GetList(
			array('ID' => 'ASC'),
			$arFilter,
			false,
			array('nPageSize' => $fetchLimit),
			$arSelect
		);

		while ($ob = $res->GetNextElement()) {
			if ($result['processed'] >= $limit) {
				break;
			}

			$arFields = $ob->GetFields();
			$elementId = (int)$arFields['ID'];
			$previewId = (int)$arFields['PREVIEW_PICTURE'];

			if (!self::previewFileExists($previewId)) {
				$result['skipped']++;
				continue;
			}

			if (!self::generateForElement($elementId, $previewId, $template)) {
				$result['errors'][] = 'Failed to generate avito image for element ' . $elementId;
				$result['skipped']++;
				continue;
			}

			$result['processed']++;
			$result['ids'][] = $elementId;
		}

		$result['remaining'] = self::countPending();

		return $result;
	}

	public static function previewFileExists($previewPictureId)
	{
		$previewPictureId = (int)$previewPictureId;
		if ($previewPictureId <= 0) {
			return false;
		}

		$path = CFile::GetPath($previewPictureId);
		if (!$path) {
			return false;
		}

		return file_exists($_SERVER['DOCUMENT_ROOT'] . $path);
	}

	/**
	 * @param array{white: int, height: int, width: int} $template
	 */
	public static function generateForElement($elementId, $previewPictureId, array $template)
	{
		if (!$previewPictureId || !self::previewFileExists($previewPictureId)) {
			return false;
		}

		$foto = CFile::ResizeImageGet(
			$previewPictureId,
			array('width' => self::PREVIEW_MAX_WIDTH, 'height' => self::PREVIEW_MAX_HEIGHT),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			true
		);

		if (empty($foto['src']) || empty($foto['height']) || empty($foto['width'])) {
			return false;
		}

		$fotoPath = $_SERVER['DOCUMENT_ROOT'] . $foto['src'];
		if (!file_exists($fotoPath)) {
			return false;
		}

		$canvasHeight = $template['height'] * 2 + (int)$foto['height'];

		$arFilters = array(array(
			'name' => 'watermark',
			'position' => 'center',
			'size' => 'big',
			'type' => 'image',
			'file' => $fotoPath,
			'alpha_level' => '100',
		));

		$imageResize = CFile::ResizeImageGet(
			$template['white'],
			array('width' => $canvasHeight, 'height' => $canvasHeight),
			BX_RESIZE_IMAGE_EXACT,
			true,
			$arFilters,
			false,
			100
		);

		if (empty($imageResize['src'])) {
			return false;
		}

		$resizedPath = $_SERVER['DOCUMENT_ROOT'] . $imageResize['src'];
		if (!file_exists($resizedPath)) {
			return false;
		}

		$newFile = CFile::MakeFileArray($resizedPath);
		if (!$newFile) {
			return false;
		}

		CIBlockElement::SetPropertyValues(
			$elementId,
			self::IBLOCK_CATALOG,
			array('VALUE' => $newFile),
			'avito'
		);

		return true;
	}

	public static function countPending()
	{
		$countRes = CIBlockElement::GetList(
			array(),
			self::pendingFilter(),
			array(),
			false,
			array('ID')
		);

		if (is_numeric($countRes)) {
			return (int)$countRes;
		}
		if (is_array($countRes)) {
			return (int)array_shift($countRes);
		}
		if (is_object($countRes) && method_exists($countRes, 'SelectedRowsCount')) {
			return (int)$countRes->SelectedRowsCount();
		}

		return 0;
	}
}
