<?php
namespace Wbs24\Ozonexport;

class OzonexportTest extends BitrixTestCase
{
	public function testGetElementId()
	{
		// входные параметры
		$element = [
			'ID' => 25851,
			'EXTERNAL_ID' => 45265,
			'PROPERTIES' => [
				'9' => [
                    'VALUE' => '01800180',
					'~VALUE' => '01800180',
				],
			],
		];
		$secondParamVariantsArray = [
			'ID',
			'XML_ID',
			9,
		];

		// результат для проверки
		$expectedResultArray = [
			25851,
			45265,
			'01800180',
		];

		// заглушка

		// обход условий
		$ozon = new \Wbs24\Ozonexport;
		foreach ($expectedResultArray as $k => $expectedResult) {
			// вычисление результата
			$result = $ozon->getElementId($element, $secondParamVariantsArray[$k]);

			// проверка
			$this->assertEquals($expectedResult, $result);
		}
	}

	public function testCleanKeysFromQuotes()
	{
		// входные параметры
		$array = [
			'"extendPrice"' => 'Y',
			'"plusPercent"' => 10,
			'"plusAdditionalSum"' => 99,
			'"oldPriceMinusPercent"' => 5,
		];

		// результат для проверки
		$expectedResult = [
			'extendPrice' => 'Y',
			'plusPercent' => 10,
			'plusAdditionalSum' => 99,
			'oldPriceMinusPercent' => 5,
		];

		// заглушка

		// вычисление результата
		$ozon = new \Wbs24\Ozonexport;
		$result = $ozon->cleanKeysFromQuotes($array);

		// проверка
		$this->assertEquals($expectedResult, $result);
	}

    public function testGetFilesByExample()
    {
        // входные параметры
        $filesList = [
            '.',
            '..',
            '/full/path/ozon_67688.php_import_20220419_2005.php',
            '/full/path/ozon_67688.php_import_20220420_1707.php',
            '/full/path/ozon_67688.php_import_20220420_1815.php',
            '/full/path/ozon_67688.php_import_20220420_1830.php',
            '/full/path/testexport.php_import_20220420_1535.php',
            '/full/path/testexport.php_import_20220420_1930.php',
        ];
        $example = '/full/path/ozon_67688.php_import_20220420_1815.php';

        // результат для проверки
        $expectedResult = [
            '/full/path/ozon_67688.php_import_20220419_2005.php',
            '/full/path/ozon_67688.php_import_20220420_1707.php',
            '/full/path/ozon_67688.php_import_20220420_1815.php',
            '/full/path/ozon_67688.php_import_20220420_1830.php',
        ];

        // заглушка

        // вычисление результата
        $method = $this->getMethod('Wbs24\\Ozonexport', 'getFilesByExample');
        $object = new \Wbs24\Ozonexport();
        $result = $method->invokeArgs($object, [
            $filesList,
            $example,
        ]);

        // проверка
        $this->assertEquals($expectedResult, $result);
    }

    public function testGetExportTime()
    {
        // входные параметры
        $files = [
            '/full/path/ozon_67688.php_import_20220420_1815.php',
            '/full/path/incorrect_file_name.php'
        ];

        // результат для проверки
        $expectedResults = [
            202204201815,
            null,
        ];

        // заглушка

        foreach ($files as $k => $file) {
            // вычисление результата
            $method = $this->getMethod('Wbs24\\Ozonexport', 'getExportTime');
            $object = new \Wbs24\Ozonexport();
            $result = $method->invokeArgs($object, [
                $file,
            ]);

            // проверка
            $this->assertEquals($expectedResults[$k], $result);
        }
    }

    public function testGetFilesBeforeTime()
    {
        // входные параметры
        $filesList = [
            '/full/path/bad_name.php',
            '/full/path/ozon_67688.php_import_20220419_2005.php',
            '/full/path/ozon_67688.php_import_20220420_1707.php',
            '/full/path/ozon_67688.php_import_20220420_1815.php',
            '/full/path/ozon_67688.php_import_20220420_1830.php',
        ];
        $beforeTimestamp = 202204201815;

        // результат для проверки
        $expectedResult = [
            '/full/path/ozon_67688.php_import_20220419_2005.php',
            '/full/path/ozon_67688.php_import_20220420_1707.php',
        ];

        // заглушка

        // вычисление результата
        $method = $this->getMethod('Wbs24\\Ozonexport', 'getFilesBeforeTime');
        $object = new \Wbs24\Ozonexport();
        $result = $method->invokeArgs($object, [
            $filesList,
            $beforeTimestamp,
        ]);

        // проверка
        $this->assertEquals($expectedResult, $result);
    }

    public function testIsManualCall()
    {
        // входные параметры
        $traceArray = [
            '[{"file":"/home/bitrix/www/bitrix/php_interface/include/catalog_export/ozon_run.php","line":3,"function":"require"},{"file":"/home/bitrix/www/bitrix/modules/catalog/admin/export_setup.php","line":310,"args":["/home/bitrix/www/bitrix/php_interface/include/catalog_export/ozon_run.php"],"function":"include"},{"file":"/home/bitrix/www/bitrix/admin/cat_export_setup.php","line":2,"args":["/home/bitrix/www/bitrix/modules/catalog/admin/export_setup.php"],"function":"require_once"}]',
            '[{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\php_interface\\\\include\\\\catalog_export\\\\ozon_run.php","line":3,"function":"require"},{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\catalog\\\\admin\\\\export_setup.php","line":310,"args":["C:\\\\sites\\\\santehstroy\\\\bitrix\\\\php_interface\\\\include\\\\catalog_export\\\\ozon_run.php"],"function":"include"},{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\admin\\\\cat_export_setup.php","line":2,"args":["C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\catalog\\\\admin\\\\export_setup.php"],"function":"require_once"}]',
            '[{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\php_interface\\\\include\\\\catalog_export\\\\ozon_run.php","line":3,"function":"require"},{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\catalog\\\\general\\\\catalog_export.php","line":315,"args":["C:\\\\sites\\\\santehstroy\\\\bitrix\\\\php_interface\\\\include\\\\catalog_export\\\\ozon_run.php"],"function":"include"},{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\main\\\\classes\\\\mysql\\\\agent.php(166) : eval()`d code","line":1,"function":"PreGenerateExport","class":"CAllCatalogExport","type":"::","args":[14]},{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\main\\\\classes\\\\mysql\\\\agent.php","line":166,"function":"eval"},{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\askaron.agents\\\\include.php","line":59,"function":"ExecuteAgents","class":"CAgent","type":"::","args":[""]},{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\askaron.agents\\\\include.php","line":18,"function":"CheckAgents","class":"CAskaronAgents","type":"::","args":[]},{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\main\\\\classes\\\\general\\\\module.php","line":480,"function":"OnPageStartHandler","class":"CAskaronAgents","type":"::","args":[]},{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\main\\\\include.php","line":173,"function":"ExecuteModuleEventEx","args":[{"SORT":"500","TO_MODULE_ID":"askaron.agents","TO_PATH":"","TO_CLASS":"CAskaronAgents","TO_METHOD":"OnPageStartHandler","TO_METHOD_ARG":[],"VERSION":"1","TO_NAME":"CAskaronAgents::OnPageStartHandler (askaron.agents)","FROM_DB":true,"FROM_MODULE_ID":"main","MESSAGE_ID":"OnPageStart"}]},{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\main\\\\include\\\\prolog_before.php","line":14,"args":["C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\main\\\\include.php"],"function":"require_once"},{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\main\\\\tools\\\\cron_events.php","line":11,"args":["C:\\\\sites\\\\santehstroy\\\\bitrix\\\\modules\\\\main\\\\include\\\\prolog_before.php"],"function":"require"}]',
            '[{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\php_interface\\\\include\\\\catalog_export\\\\ozon_run.php","line":3,"function":"require"},{"file":"C:\\\\sites\\\\santehstroy\\\\bitrix\\\\php_interface\\\\include\\\\catalog_export\\\\cron_frame.php","line":93,"args":["C:\\\\sites\\\\santehstroy\\\\bitrix\\\\php_interface\\\\include\\\\catalog_export\\\\ozon_run.php"],"function":"include"}]',
        ];

        // результат для проверки
        $expectedResultArray = [
            true,
            true,
            false,
            false,
        ];

        // заглушка

        // вычисление результата
        $method = $this->getMethod('Wbs24\\Ozonexport', 'isManualCall');
        foreach ($traceArray as $key => $jsonTrace) {
            $object = new \Wbs24\Ozonexport();
            $trace = json_decode($jsonTrace, true);
            $result = $method->invokeArgs($object, [$trace]);

            // проверка
            $this->assertEquals($expectedResultArray[$key], $result);
        }
    }
}
