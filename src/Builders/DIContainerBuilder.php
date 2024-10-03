<?php
namespace Zaw\AttributeDrivenCqrs\Builders;
use DI\Container;
use DI\ContainerBuilder;
class DIContainerBuilder
{
    private static ?Container $container = null;
    public static function getContainer(): Container
    {
        if (self::$container === null) {
            $containerBuilder = new ContainerBuilder();
            self::$container = $containerBuilder->build();
        }

        return self::$container;
    }
}