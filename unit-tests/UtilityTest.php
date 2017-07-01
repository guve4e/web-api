<?php

/**
 *
 */
trait UtilityTest
{
    /**
     * Call protected/private properties of a class.
     *
     * @param object &$object Instantiated object
     * @param $propertyName
     * @return mixed propertie return.
     * @internal param string $propertiedName
     */
    public function invokeProperty(&$object, $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        return $property;
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     *
     * @param $object
     * @param $nameOfVar
     * @return mixed
     */
    public function getProperty($object, $nameOfVar)
    {
        $var = $this->invokeProperty($object,$nameOfVar);
        $value = $var->getValue($object);
        return $value;
    }
}
