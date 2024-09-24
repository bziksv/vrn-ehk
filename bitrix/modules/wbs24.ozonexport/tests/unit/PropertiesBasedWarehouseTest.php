<?php
namespace Wbs24\Ozonexport;

use Bitrix\Main\Loader;

class PropertiesBasedWarehouseTest extends BitrixTestCase
{
    public function testGetXml()
    {
        // входные параметры
        $stockPropId = 100;
        $stock = 10;
        $storeTitle = 'store';
        $product = [
            'ID' => 50,
            'IBLOCK_ID' => 1,
        ];

        // результат для проверки
        $expectedResult =
            '<outlets>'
            .'<outlet '
                .'instock="'.$stock.'" '
                .'warehouse_name="'.$storeTitle.'"'
            .'></outlet>'
            .'</outlets>'."\n"
        ;

        // заглушки
        Loader::includeModule('iblock');

        $CIBlockResultStub = $this->createMock(\CIBlockResult::class);
        $fetchResults = [
            [
                'IBLOCK_ELEMENT_ID' => $product['ID'],
                $stockPropId => $stock,
            ],
            false,
        ];
        $CIBlockResultStub->method('Fetch')
            ->will($this->onConsecutiveCalls(...$fetchResults));

        $CIBlockElementStub = $this->createMock(CIBlockElement::class);
        $CIBlockElementStub->method('GetPropertyValues')
            ->willReturn($CIBlockResultStub);

        // вычисление результата
        $warehouse = new PropertiesBasedWarehouse([
            'stocksProp1' => $stockPropId,
            'stocksPropName1' => $storeTitle,
            'objects' => [
                'CIBlockElement' => $CIBlockElementStub,
            ],
        ]);
        $result = $warehouse->getXml($product);

        // проверка
        $this->assertEquals($expectedResult, $result);
    }
}
