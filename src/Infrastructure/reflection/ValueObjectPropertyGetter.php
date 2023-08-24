<?php

namespace App\Infrastructure\reflection;

use ReflectionClass;
use ReflectionException;

class ValueObjectPropertyGetter
{

    //Temporary solution - Issue reported: https://github.com/doctrine/orm/issues/10898
    public static function getValueUsingReflection($object, $propertyName) {
        $reflectionClass = new ReflectionClass(get_class($object));
        try {
            $reflectionProperty = $reflectionClass->getProperty($propertyName);
            $reflectionProperty->setAccessible(true);
            return $reflectionProperty->getValue($object);
        } catch (ReflectionException $e) {
            //TODO refactor
            return null;
        }
    }
}