<?php
namespace Zaw\AttributeDrivenCqrs\Validators;

use Zaw\AttributeDrivenCqrs\Exceptions\InvalidMiddlewareException;
use Zaw\AttributeDrivenCqrs\Exceptions\MiddlewareNotFoundException;
use Zaw\AttributeDrivenCqrs\Middlewares\Interfaces\MiddlewareInterface;

class MiddlewareValidator
{
    public static function validate($middleware): void
    {
        if (!class_exists($middleware)) {
            throw new MiddlewareNotFoundException($middleware);
        }

        if (!is_subclass_of($middleware, MiddlewareInterface::class)) {
            throw new InvalidMiddlewareException(
                $middleware
            );
        }
    }
}