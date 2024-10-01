<?php
namespace Zaw\AttributeDrivenCqrs\Exceptions;

class NoQueryHandlersFoundException extends \Exception
{
    public function __toString(): string
    {
        return "NoHandlersFoundException: {$this->message}\nEach query must have exactly one handler.\n";
    }
    public function __construct(string $class)
    {
        $message = "No handlers found for the query: $class";
        parent::__construct($message);
    }
}