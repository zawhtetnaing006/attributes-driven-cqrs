<?php
namespace Zaw\AttributeDrivenCqrs\Traits;
use Zaw\AttributeDrivenCqrs\Exceptions\MiddlewareRegistrationClosedException;
use Zaw\AttributeDrivenCqrs\Validators\MiddlewareValidator;

trait MiddlewareTrait {
    final protected static function runGlobalBeforeHandleMiddlewares(object $message, $result = null): void
    {
        foreach (self::$beforeHandleMiddlewares as $middlewareClass) {
            $middleware = self::getMiddleWareInstance($middlewareClass);
            $middleware->process($message, $result);
        }
    }

    final protected static function runGlobalAfterHandleMiddlewares(object $message, $result = null): void
    {
        foreach (self::$afterHandleMiddlewares as $middlewareClass) {
            $middleware = self::getMiddleWareInstance($middlewareClass);
            $middleware->process($message, $result);
        }
    }


    final protected static function validateGlobalMiddlewares(): void
    {
        foreach (self::$beforeHandleMiddlewares as $middlewareClass) {
            MiddlewareValidator::validate($middlewareClass);
        }
        foreach (self::$afterHandleMiddlewares as $middlewareClass) {
            MiddlewareValidator::validate($middlewareClass);
        }
    }

    final public static function registerBeforeHandle(string $middlewareClass): void
    {
        if(!self::$allowMiddlewareRegister) {
            throw new MiddlewareRegistrationClosedException($middlewareClass);
        }
        self::$beforeHandleMiddlewares[] = $middlewareClass;
    }

    final public static function registerAfterHandle(string $middlewareClass): void
    {
        self::$afterHandleMiddlewares[] = $middlewareClass;
    }
}