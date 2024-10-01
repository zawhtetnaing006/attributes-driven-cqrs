<?php
namespace Zaw\AttributeDrivenCqrs\Exceptions;

class MultipleHandlersFoundException extends \Exception
{
    public function __toString(): string
    {
        return "MultipleHandlersFoundException: {$this->message}\nTo enforce CQRS, we need to have exactly one handler for each command.\n";
    }
    public function __construct(string $commandClass)
    {
        $message = "Multiple handlers found for the command: $commandClass";
        parent::__construct($message);
    }
}