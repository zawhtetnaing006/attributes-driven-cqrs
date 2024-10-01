<?php
namespace Zaw\AttributeDrivenCqrs\Builders;


class ReflectionBuilder
{
    private static array $reflectionCache = [];
    final public static function getReflectionInstance(object $class): \ReflectionClass
    {
        $className = get_class($class);

        if (!isset(self::$reflectionCache[$className])) {
            self::$reflectionCache[$className] = new \ReflectionClass($class);
        }

        return self::$reflectionCache[$className];
    }
}