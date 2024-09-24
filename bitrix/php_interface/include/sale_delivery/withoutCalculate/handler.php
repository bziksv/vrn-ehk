<?
namespace Sale\Handlers\Delivery;
use Bitrix\Sale\Delivery\CalculationResult;
use Bitrix\Sale\Delivery\Services\Base;
class WithoutCalculateHandler extends Base
{
    public static function getClassTitle()
    {
        return 'Доставка без расчета стоимости';
    }

    public static function getClassDescription()
    {
        return 'Доставка, стоимость которой не рассчитывается в общей сумме.';
    }

    protected function calculateConcrete(\Bitrix\Sale\Shipment $shipment)
    {
        $result = new CalculationResult();
        $result->setDeliveryPrice(0);

        return $result;
    }
}
