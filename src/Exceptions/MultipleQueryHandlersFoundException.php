<?php
namespace Zaw\AttributeDrivenCqrs\Exceptions;

class MultipleQueryHandlersFoundException extends \Exception
{
    public function __toString(): string
    {
        return "MultipleHandlersFoundException: {$this->message}\nTo enforce CQRS, we need to have exactly one handler for each query.\n";
    }
    public function __construct(string $class)
    {
        $message = "Multiple handlers found for the query: $class";
        parent::__construct($message);
    }
}