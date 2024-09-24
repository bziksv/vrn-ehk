<?php
namespace Wbs24\Ozonexport;

class Filter
{
    use Properties;

    protected $param;
    protected $wrappers;

    function __construct($param = [])
    {
        $this->setParam($param);

        $objects = $this->param['objects'] ?? [];
        $this->wrappers = new Wrappers($objects);
    }

    public function setParam($param)
    {
        foreach ($param as $name => $value) {
            if ($name == 'conditions') {
                $filter = $this->getFilterFromConditions($value);
                $this->param['filter'] = $filter;
            }
            $this->param[$name] = $value;
        }
    }

    protected function getFilterFromConditions($encodedConditions)
    {
        $conditions = unserialize(base64_decode($encodedConditions));

        $conditionTreeObject = new \CCatalogCondTree();
        $success = $conditionTreeObject->Init(BT_COND_MODE_GENERATE, BT_COND_BUILD_CATALOG);
        $filter = '';
        if ($success) {
            $filter = $conditionTreeObject->Generate($conditions, array("FIELD" => '$element'));
        }

        return $filter;
    }

    public function verifyElementShowing($element, $parent = [])
    {
        $filterOn = $this->param['filterOn'] ?? false;
        if (!$filterOn) return true;

        $filter = $this->param['filter'] ?? false;
        if (!$filter) return true;

        $element['SECTION_ID'] = $element['SECTIONS'];

        $propertyIds = $this->getNeededPropertyIds($filter);
        if ($propertyIds) {
            $element = $this->addPropertiesToElement($element, $propertyIds);
        }

        if ($parent && !$this->isAllPropertiesReady($element, $propertyIds)) {
            $parent = $this->addPropertiesToElement($parent, $propertyIds);
            $element = $this->mergeProperties($element, $parent);
        }

        /* $log = date("Y.m.d H:i:s")."\r\n".print_r([$element, $filter], true)."\r\n\r\n";
        if ($log) {
            $handle = @fopen($_SERVER['DOCUMENT_ROOT']."/upload/elements_log.txt", "a");
            fwrite($handle, $log);
            fclose($handle);
        } */

        $allowShow = eval("return ${filter};") ? true : false;

        return $allowShow;
    }

    protected function getNeededPropertyIds($filter)
    {
        $propertyIds = [];

        preg_match_all('/PROPERTY_(\d+)_VALUE/', $filter, $match);
        if (!empty($match[1])) {
            foreach ($match[1] as $id) {
                $propertyIds[] = intval($id);
            }
        }

        return array_values(array_unique($propertyIds));
    }
}
