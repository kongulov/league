<?php

namespace Infrastructure;

use ReflectionClass;

class Container
{
    public function get(string $class): object
    {
        $reflectionClass = new ReflectionClass($class);

        $constructor = $reflectionClass->getConstructor();
        if (!$constructor) {
            return new $class;
        }

        $params = $constructor->getParameters();
        $dependencies = [];

        foreach ($params as $param) {
            $dependencyClass = $param->getType()->getName();
            $dependencies[] = $this->get($dependencyClass);
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
