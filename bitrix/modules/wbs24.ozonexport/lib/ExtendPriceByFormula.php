<?php
namespace Wbs24\Ozonexport;

class ExtendPriceByFormula extends Price
{
    protected $Formula;
    protected $currentBasePrice = 0;

    public function __construct($param = [], $objects = [])
    {
        parent::__construct($param);

        $this->Formula = $objects['Formula'] ?? new Formula();
    }

    public function getPrice($minPrice, $fullPrice)
    {
        $ignoreSale = $this->param['ignoreSale'] ?? false;
        $basePrice = $ignoreSale ? $fullPrice : $minPrice;
        $formula = $this->param['formulaPrice'] ?: false;

        $price = $this->calcByFormula($formula, [
            'PRICE' => $basePrice,
        ]);
        // сохранение текущей базовой цены для расчета в других формулах для данного товара
        // (необходимо т.к. через параметры базовая цена в другие формулы не передается)
        $this->setCurrentBasePrice($basePrice);

        return round($price);
    }

    protected function setCurrentBasePrice($basePrice)
    {
        $this->currentBasePrice = $basePrice;
    }

    protected function getCurrentBasePrice()
    {
        return $this->currentBasePrice;
    }

    public function getOldPrice($minPrice, $fullPrice)
    {
        $formulaBefore10k = $this->param['formulaOldPrice'] ?? '';
        $formulaAfter10k = $this->param['formulaOldPrice10k'] ?? '';
        $formula = ($minPrice > 10000) ? $formulaAfter10k : $formulaBefore10k;

        $oldPrice = $this->calcByFormula($formula, [
            'PRICE_DISCOUNT' => $minPrice,
            'PRICE' => $this->getCurrentBasePrice(),
        ]);

        if ($minPrice > 10000) {
            if ($minPrice + 500 >= $oldPrice) $oldPrice = 0;
        } else {
            if ($minPrice * 1.05 > $oldPrice) $oldPrice = 0;
        }

        return round($oldPrice);
    }

    public function getPremiumPrice($minPrice, $fullPrice)
    {
        $formula = $this->param['formulaPremiumPrice'] ?? '';

        $premiumPrice = $this->calcByFormula($formula, [
            'PRICE_DISCOUNT' => $minPrice,
            'PRICE' => $this->getCurrentBasePrice(),
        ]);

        if ($premiumPrice >= $minPrice) $premiumPrice = 0;

        return round($premiumPrice);
    }

    public function getMinPrice($minPrice, $fullPrice)
    {
        $formula = $this->param['formulaMinPrice'] ?? '';

        $newMinPrice = $this->calcByFormula($formula, [
            'PRICE_DISCOUNT' => $minPrice,
            'PRICE' => $this->getCurrentBasePrice(),
        ]);

        if ($newMinPrice >= $minPrice) $newMinPrice = 0;

        return round($newMinPrice);
    }

    protected function calcByFormula($formula, $marks)
    {
        if ($formula) {
            $allowedMarks = array_keys($marks);
            $this->Formula->setMarks($allowedMarks);
            $this->Formula->setFormula($formula);
            $price = $this->Formula->calc($marks);
        } else {
            $price = $marks['PRICE'] ?? 0;
        }

        return $price;
    }
}
