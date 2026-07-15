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
	 * @return array{processed: int, ids: int[], remaining: int, errors: string[]}
	 */
	public static function processBatch($limit = 1, $cleanupFirst = true)
	{
		$result = array(
			'processed' => 0,
			'ids' => array(),
			'remaining' => 0,
			'errors' => array(),
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
		$arFilter = array(
			'IBLOCK_ID' => self::IBLOCK_CATALOG,
			'!PREVIEW_PICTURE' => false,
			'PROPERTY_avito' => false,
		);

		$res = CIBlockElement::GetList(array('ID' => 'ASC'), $arFilter, false, array('nPageSize' => $limit), $arSelect);
		while ($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$elementId = (int)$arFields['ID'];

			if (!self::generateForElement($elementId, (int)$arFields['PREVIEW_PICTURE'], $template)) {
				$result['errors'][] = 'Failed to generate avito image for element ' . $elementId;
				continue;
			}

			$result['processed']++;
			$result['ids'][] = $elementId;
		}

		$countRes = CIBlockElement::GetList(
			array(),
			$arFilter,
			array(),
			false,
			array('ID')
		);
		if (is_array($countRes)) {
			$result['remaining'] = (int)array_shift($countRes);
		}

		return $result;
	}

	/**
	 * @param array{white: int, height: int, width: int} $template
	 */
	public static function generateForElement($elementId, $previewPictureId, array $template)
	{
		if (!$previewPictureId) {
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

		$canvasHeight = $template['height'] * 2 + (int)$foto['height'];

		$arFilters = array(array(
			'name' => 'watermark',
			'position' => 'center',
			'size' => 'big',
			'type' => 'image',
			'file' => $_SERVER['DOCUMENT_ROOT'] . $foto['src'],
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

		$newFile = CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . $imageResize['src']);
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
		$arFilter = array(
			'IBLOCK_ID' => self::IBLOCK_CATALOG,
			'!PREVIEW_PICTURE' => false,
			'PROPERTY_avito' => false,
		);

		$countRes = CIBlockElement::GetList(array(), $arFilter, array(), false, array('ID'));
		if (!is_array($countRes)) {
			return 0;
		}

		return (int)array_shift($countRes);
	}
}
