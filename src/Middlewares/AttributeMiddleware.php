<?php
namespace Zaw\AttributeDrivenCqrs\Middlewares;
use Zaw\AttributeDrivenCqrs\Attributes\AfterHandle;
use Zaw\AttributeDrivenCqrs\Attributes\BeforeHandle;
use Zaw\AttributeDrivenCqrs\Builders\MiddlewareBuilder;
use Zaw\AttributeDrivenCqrs\Builders\ReflectionBuilder;
class AttributeMiddleware
{
    private static function runAttributeMiddlewares(string $attributeClass, object $commandOrQuery, $result = null): void
    {
        $reflectionClass = ReflectionBuilder::getReflectionInstance($commandOrQuery);
        $middlewareAttributes = $reflectionClass->getAttributes($attributeClass);

        foreach ($middlewareAttributes as $middlewareAttribute) {
            $middlewareInstance = $middlewareAttribute->newInstance();
            $middlewareClass = $middlewareInstance->middleware;

            $middleware = MiddlewareBuilder::getMiddleWareInstance($middlewareClass);
            $middleware->process($commandOrQuery, $result);
        }
    }
    public static function runBeforeHandleMiddlewares(object $commandOrQuery, $result = null): void
    {
        self::runAttributeMiddlewares( BeforeHandle::class, $commandOrQuery, $result);
    }

    public static function runAfterHandleMiddlewares(object $commandOrQuery, $result = null): void
    {
        self::runAttributeMiddlewares( AfterHandle::class, $commandOrQuery, $result);
    }
}