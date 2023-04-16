<?php

namespace Core\Vouchers\Application\Parser\Values\Helpers;

use ReflectionClass;
use stdClass;

class Filler
{
    /**
     * @param  stdClass $obj
     * @param  string   $className
     * @return stdClass
     */
    public static function withNull(stdClass $obj, string $className): stdClass
    {
        $objClass = new ReflectionClass($className);

        $classProperties = $objClass->getProperties();

        $arrayForJSON = [];

        foreach ($classProperties as $property) {
            if (!property_exists($obj, $property->getName())) {
                $obj->{$property->getName()} = null;
            }
        }

        return $obj;
    }
}
