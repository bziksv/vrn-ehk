<?php
namespace Wbs24\Ozonexport;

class PropertiesBasedWarehouse extends Warehouse
{
    use Properties;

    protected $wrappers;
    protected $properties;

    function __construct($param = [])
    {
        parent::__construct($param);

        $objects = $this->param['objects'] ?? [];
        $this->wrappers = new Wrappers($objects);

        $this->properties = false;
    }

    public function checkNeedProperties()
    {
        return true;
    }

    public function getXml($product)
    {
        $storeId = $this->param['storeId'] ?? 1;

        $stocks = $this->getStockFromProperties($product);
        $stocks = $this->verifyAndDropStockIfLess($stocks);

        $xml = '<outlets>';
        foreach ($stocks as $stock) {
            $xml .=
                '<outlet '
                    .'instock="'.$stock['AMOUNT'].'" '
                    .'warehouse_name="'.$stock['STORE_TITLE'].'"'
                .'></outlet>'
            ;
        }
        $xml .= '</outlets>'."\n";

        return $xml;
    }

    protected function getStockFromProperties($product)
    {
        $stocks = [];

        $propertyIdsToStoreTitle = [];
        for ($i = 1; $i <= 5; $i++) {
            $propertyId = $this->param['stocksProp'.$i] ?? false;
            if (!$propertyId) continue;

            $storeTitle = $this->param['stocksPropName'.$i] ?? false;
            if (!$storeTitle) continue;

            $propertyIdsToStoreTitle[$propertyId] = $storeTitle;
        }

        $product = $this->addPropertiesToElement($product, array_keys($propertyIdsToStoreTitle));

        foreach ($propertyIdsToStoreTitle as $propertyId => $storeTitle) {
            $amount = $product['PROPERTY_'.$propertyId.'_VALUE'][0] ?? 0;

            $stocks[] = [
                'AMOUNT' => intval($amount),
                'STORE_TITLE' => $storeTitle,
            ];
        }

        return $stocks;
    }

    protected function verifyAndDropStockIfLess($stocks)
    {
        $minStock = $this->param['minStock'] ?? 0;

        foreach ($stocks as $k => $stock) {
            $stocks[$k]['AMOUNT'] = $stock['AMOUNT'] >= $minStock ? $stock['AMOUNT'] : 0;
        }

        return $stocks;
    }
}
