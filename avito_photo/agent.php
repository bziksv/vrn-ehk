<?php

require_once __DIR__ . '/lib/AvitoPhotoService.php';

class AvitoPhotoAgent
{
	const AGENT_MODULE = 'avito_photo';
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

			AvitoPhotoService::processBatch(self::BATCH_SIZE, true);
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
			array('MODULE_ID' => self::AGENT_MODULE)
		);
		if ($res->Fetch()) {
			return true;
		}

		$res = CAgent::GetList(
			array('ID' => 'DESC'),
			array('NAME' => self::AGENT_CALL)
		);

		return (bool)$res->Fetch();
	}

	/**
	 * @return array{ok: bool, message: string}
	 */
	public static function register()
	{
		if (self::isRegistered()) {
			return array(
				'ok' => false,
				'message' => 'Агент уже зарегистрирован (модуль: ' . self::AGENT_MODULE . ').',
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
			array('MODULE_ID' => self::AGENT_MODULE),
			array('NAME' => self::AGENT_CALL),
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
