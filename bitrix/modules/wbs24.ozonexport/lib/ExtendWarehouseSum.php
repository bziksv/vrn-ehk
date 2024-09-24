<?php
namespace Wbs24\Ozonexport;

class ExtendWarehouseSum extends ExtendWarehouse
{
    public function getXml($product)
    {
        $productId = $product['ID'] ?? 0;
        $stocks = $this->getStockInWarehouses($productId);
        $stocks = $this->filterAndRenameWarehouses($stocks);
        $stocks = $this->verifyAndDropStockIfLess($stocks);

        $stock = [
            'AMOUNT' => $this->sumStocks($stocks),
            'STORE_TITLE' => $this->param['warehouseSumName'] ?: '',
        ];

        $xml = '<outlets>';
        $xml .=
            '<outlet '
                .'instock="'.$stock['AMOUNT'].'" '
                .'warehouse_name="'.$stock['STORE_TITLE'].'"'
            .'></outlet>'
        ;
        $xml .= '</outlets>'."\n";

        return $xml;
    }

    protected function sumStocks($stocks)
    {
        $sumStock = 0;

        foreach ($stocks as $stock) {
            $sumStock += $stock['AMOUNT'];
        }

        return $sumStock;
    }
}
