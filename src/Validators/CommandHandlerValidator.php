<?php
namespace Zaw\AttributeDrivenCqrs\Validators;

use Zaw\AttributeDrivenCqrs\Exceptions\HandlerNotFoundException;
use Zaw\AttributeDrivenCqrs\Exceptions\InvalidCommandHandlerException;

class CommandHandlerValidator
{
    public static function validate($handler): void
    {
        if (!class_exists($handler)) {
            throw new HandlerNotFoundException($handler);
        }

        if (!method_exists($handler, 'handle')) {
            throw new InvalidCommandHandlerException($handler);
        }
    }
}