<?php

require_once __DIR__ . '/lib/AvitoPhotoService.php';

class AvitoPhotoAgent
{
	/**
	 * Пустой MODULE_ID: модуля avito_photo в Bitrix нет, а при несуществующем
	 * MODULE_ID агент пропускается (CModule::IncludeModule → continue).
	 */
	const AGENT_MODULE = '';
	const AGENT_CALL = 'require_once($_SERVER["DOCUMENT_ROOT"]."/avito_photo/agent.php"); AvitoPhotoAgent::run();';
	const BATCH_SIZE = 10;
	const INTERVAL_SECONDS = 300;

	public static function run()
	{
		try {
			if (!CModule::IncludeModule('iblock')) {
				self::log('iblock module is not available');
				return self::AGENT_CALL;
			}

			// cleanupFirst=false: полный обход картинок вешает агента (RUNNING=Y).
			$result = AvitoPhotoService::processBatch(self::BATCH_SIZE, false);
			self::log(sprintf(
				'processed=%d skipped=%d remaining=%d ids=%s errors=%s',
				$result['processed'],
				isset($result['skipped']) ? $result['skipped'] : 0,
				$result['remaining'],
				implode(',', $result['ids']),
				$result['errors'] ? implode('; ', $result['errors']) : '-'
			));
		} catch (\Throwable $e) {
			self::log($e->getMessage());
		}

		// Bitrix удаляет агента, если вернуть пустую строку — всегда возвращаем имя.
		return self::AGENT_CALL;
	}

	protected static function log($message)
	{
		$line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
		$path = $_SERVER['DOCUMENT_ROOT'] . '/avito_photo/agent.log';
		@file_put_contents($path, $line, FILE_APPEND | LOCK_EX);
	}

	public static function isRegistered()
	{
		$res = CAgent::GetList(
			array('ID' => 'DESC'),
			array('NAME' => self::AGENT_CALL)
		);

		return (bool)$res->Fetch();
	}

	/**
	 * Исправляет уже созданных агентов с несуществующим MODULE_ID=avito_photo.
	 *
	 * @return array{ok: bool, message: string, fixed: int}
	 */
	public static function repair()
	{
		$fixed = 0;
		$seen = array();

		foreach (array(
			array('NAME' => self::AGENT_CALL),
			array('MODULE_ID' => 'avito_photo'),
		) as $filter) {
			$res = CAgent::GetList(array('ID' => 'DESC'), $filter);
			while ($agent = $res->Fetch()) {
				$id = (int)$agent['ID'];
				if (isset($seen[$id])) {
					continue;
				}
				$seen[$id] = true;

				$needsFix = ($agent['MODULE_ID'] !== '')
					|| ($agent['ACTIVE'] !== 'Y')
					|| empty($agent['LAST_EXEC']);

				if (!$needsFix && $agent['MODULE_ID'] === '') {
					continue;
				}

				$ok = CAgent::Update($id, array(
					'MODULE_ID' => '',
					'NAME' => self::AGENT_CALL,
					'ACTIVE' => 'Y',
					'AGENT_INTERVAL' => self::INTERVAL_SECONDS,
					'IS_PERIOD' => 'N',
					'NEXT_EXEC' => ConvertTimeStamp(time() + 60, 'FULL'),
					'DATE_CHECK' => false,
					'RUNNING' => 'N',
					'RETRY_COUNT' => 0,
				));

				if ($ok) {
					$fixed++;
				}
			}
		}

		return array(
			'ok' => true,
			'fixed' => $fixed,
			'message' => $fixed > 0
				? 'Исправлено агентов: ' . $fixed . '. MODULE_ID очищен, следующий запуск через ~1 мин.'
				: 'Подходящих агентов для исправления не найдено.',
		);
	}

	/**
	 * @return array{ok: bool, message: string}
	 */
	public static function register()
	{
		$repair = self::repair();
		if ($repair['fixed'] > 0) {
			return array(
				'ok' => true,
				'message' => $repair['message'],
			);
		}

		if (self::isRegistered()) {
			return array(
				'ok' => false,
				'message' => 'Агент уже зарегистрирован.',
			);
		}

		$agentId = CAgent::AddAgent(
			self::AGENT_CALL,
			self::AGENT_MODULE,
			'N',
			self::INTERVAL_SECONDS,
			'',
			'Y',
			ConvertTimeStamp(time() + 60, 'FULL'),
			30
		);

		if (!$agentId) {
			global $APPLICATION;
			$error = 'Не удалось создать агента.';
			$exception = $APPLICATION->GetException();
			if ($exception) {
				$error .= ' ' . $exception->GetString();
			}

			return array(
				'ok' => false,
				'message' => $error,
			);
		}

		return array(
			'ok' => true,
			'message' => 'Агент зарегистрирован (ID: ' . (int)$agentId . '). Интервал: '
				. self::INTERVAL_SECONDS . ' сек, пачка: ' . self::BATCH_SIZE . ' товаров.',
		);
	}

	/**
	 * @return array{ok: bool, message: string, deleted: int}
	 */
	public static function unregister()
	{
		$deleted = 0;
		$seen = array();

		foreach (array(
			array('NAME' => self::AGENT_CALL),
			array('MODULE_ID' => 'avito_photo'),
		) as $filter) {
			$res = CAgent::GetList(array('ID' => 'DESC'), $filter);
			while ($agent = $res->Fetch()) {
				$id = (int)$agent['ID'];
				if (isset($seen[$id])) {
					continue;
				}
				$seen[$id] = true;
				CAgent::Delete($id);
				$deleted++;
			}
		}

		return array(
			'ok' => true,
			'message' => $deleted > 0
				? 'Удалено агентов: ' . $deleted . '.'
				: 'Агент не найден.',
			'deleted' => $deleted,
		);
	}
}
