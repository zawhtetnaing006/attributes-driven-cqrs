<?php
namespace Zaw\AttributeDrivenCqrs\Attributes;

use Zaw\AttributeDrivenCqrs\Validators\QueryHandlerValidator;

#[\Attribute]
class HandleQueryWith
{
    public function __construct(
        public readonly string $handler,
    ) {
        QueryHandlerValidator::validate($handler);
    }
}
