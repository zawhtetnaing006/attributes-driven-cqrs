<?php
namespace Zaw\AttributeDrivenCqrs\Exceptions;

class NoHandlersFoundException extends \Exception
{
    public function __toString(): string
    {
        return "NoHandlersFoundException: {$this->message}\nEach command must have exactly one handler.\n";
    }
    public function __construct(string $commandClass)
    {
        $message = "No handlers found for the command: $commandClass";
        parent::__construct($message);
    }
}