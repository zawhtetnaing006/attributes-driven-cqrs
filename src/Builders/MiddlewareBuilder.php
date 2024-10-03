<?php
namespace Zaw\AttributeDrivenCqrs\Builders;
class MiddlewareBuilder
{
    private static array $middlewareInstances = [];
    final public static function getMiddleWareInstance($middlewareClass)
    {
        if (!isset(self::$middlewareInstances[$middlewareClass])) {
            self::$middlewareInstances[$middlewareClass] = DIContainerBuilder::getContainer()->get($middlewareClass);
        }

        return self::$middlewareInstances[$middlewareClass];
    }
}