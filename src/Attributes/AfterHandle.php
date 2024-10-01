<?php
namespace Zaw\AttributeDrivenCqrs\Attributes;

use Zaw\AttributeDrivenCqrs\Exceptions\InvalidMiddlewareException;
use Zaw\AttributeDrivenCqrs\Exceptions\MiddlewareNotFoundException;
use Zaw\AttributeDrivenCqrs\Middlewares\Middleware;
use Zaw\AttributeDrivenCqrs\Validators\MiddlewareValidator;

#[\Attribute]
class AfterHandle
{
    public function __construct(
        public readonly string $middleware
    ) {
        MiddlewareValidator::validate($middleware);
    }
}