<?php
namespace Zaw\AttributeDrivenCqrs\Handlers;
use Zaw\AttributeDrivenCqrs\Attributes\HandleCommandWith;
use Zaw\AttributeDrivenCqrs\Attributes\HandleQueryWith;
use Zaw\AttributeDrivenCqrs\Builders\ReflectionBuilder;
use Zaw\AttributeDrivenCqrs\Exceptions\MultipleHandlersFoundException;
use Zaw\AttributeDrivenCqrs\Exceptions\MultipleQueryHandlersFoundException;
use Zaw\AttributeDrivenCqrs\Exceptions\NoHandlersFoundException;
use Zaw\AttributeDrivenCqrs\Exceptions\NoQueryHandlersFoundException;
use DI\Container;
use DI\ContainerBuilder;

class Handler
{
    private static ?Container $container = null;
    private static function getContainer(): Container
    {
        if (self::$container === null) {
            $containerBuilder = new ContainerBuilder();
            self::$container = $containerBuilder->build();
        }

        return self::$container;
    }

    public static function handleCommand(object $command): mixed
    {
        $reflectionInstance = ReflectionBuilder::getReflectionInstance($command);
        $attributes = $reflectionInstance->getAttributes(HandleCommandWith::class);
        if(count($attributes) === 0) {
            throw new NoHandlersFoundException(get_class($command));
        }

        if(count($attributes) > 1) {
            throw new MultipleHandlersFoundException(get_class($command));
        }
        
        $attribute = reset($attributes);
        $handler = $attribute->newInstance();
        $commandHandler = self::getContainer()->get($handler->handler);

        $result = $commandHandler->handle($command);
        return $result;
    }

    public static function handleQuery(object $query): mixed
    {
        $reflectionInstance = ReflectionBuilder::getReflectionInstance($query);
        $attributes = $reflectionInstance->getAttributes(HandleQueryWith::class);

        if(count($attributes) === 0) {
            throw new NoQueryHandlersFoundException(get_class($query));
        }

        if(count($attributes) > 1) {
            throw new MultipleQueryHandlersFoundException(get_class($query));
        }
        
        $attribute = reset($attributes);
        $handler = $attribute->newInstance();
        $commandHandler = self::getContainer()->get($handler->handler);

        //Run command handler
        $result = $commandHandler->handle($query);
        return $result;
    }
}