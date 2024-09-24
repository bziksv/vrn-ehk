<?php
namespace Wbs24\Ozonexport;

trait Properties
{
    protected function isAllPropertiesReady($element, $propertyIds)
    {
        $ready = true;

        foreach ($propertyIds as $id) {
            if (!isset($element['PROPERTY_'.$id.'_VALUE'])) $ready = false;
        }

        return $ready;
    }

    protected function mergeProperties($element, $parent)
    {
        foreach ($parent as $key => $value) {
            if (substr($key, 0, 9) == 'PROPERTY_') {
                $element[$key] = $value;
            }
        }

        return $element;
    }

    protected function addPropertiesToElement($element, $propertyIds = [])
    {
        $elementId = $element['ID'] ?? false;
        $iblockId = $element['IBLOCK_ID'] ?? false;
        if (!$elementId || !$iblockId) return $element;

        $result = $this->wrappers->CIBlockElement->GetPropertyValues(
            $iblockId,
            ["ID" => $elementId],
            false,
            ["ID" => $propertyIds]
        );
        if ($properties = $result->Fetch()) {
            foreach ($properties as $id => $values) {
                if (!is_numeric($id)) continue;
                $element['PROPERTY_'.$id.'_VALUE'] = is_array($values) ? $values : [$values];
            }
        }

        return $element;
    }
}
