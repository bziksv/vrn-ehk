<?php
namespace Wbs24\Ozonexport;

use PHPUnit\Framework\TestCase;

class BitrixTestCase extends TestCase {
    protected $backupGlobals = false;

    protected function getMethod($className, $methodName)
    {
        $class = new \ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    protected function getProperty($className, $propertyName)
    {
        $class = new \ReflectionClass($className);
        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }
}
