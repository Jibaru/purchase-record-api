<?php

namespace Core\Vouchers\Application\Parser\Values\Traits;

use ReflectionClass;

trait IsArrayable
{
    public function toArray(): array
    {
        $objClass = new ReflectionClass($this);

        $classProperties = $objClass->getProperties();

        $arrayForJSON = [];

        foreach ($classProperties as $property) {
            $arrayForJSON[$property->getName()] = $property->getValue($this);
        }

        return $arrayForJSON;
    }
}
