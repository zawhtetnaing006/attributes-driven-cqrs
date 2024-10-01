<?php
namespace Zaw\AttributeDrivenCqrs\Validators;

use Zaw\AttributeDrivenCqrs\Exceptions\HandlerNotFoundException;
use Zaw\AttributeDrivenCqrs\Exceptions\InvalidQueryHandlerException;

class QueryHandlerValidator
{
    public static function validate($handler): void
    {
        if (!class_exists($handler)) {
            throw new HandlerNotFoundException($handler);
        }

        if (!method_exists($handler, 'handle')) {
            throw new InvalidQueryHandlerException($handler);
        }
    }
}