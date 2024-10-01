<?php
namespace Zaw\AttributeDrivenCqrs\Attributes;

use Zaw\AttributeDrivenCqrs\Validators\MiddlewareValidator;

#[\Attribute]
class BeforeHandle
{
    public function __construct(
        public readonly string $middleware
    ) {
        MiddlewareValidator::validate($middleware);
    }
}