<?php
namespace Zaw\AttributeDrivenCqrs\Exceptions;
use Zaw\AttributeDrivenCqrs\Handlers\Interfaces\QueryHandlerInterface;

class InvalidQueryHandlerException extends \Exception
{
    public function __toString(): string
    {
        return "InvalidQueryHandlerException: {$this->message}.\n";
    }
    public function __construct(string $class)
    {
        $message = "$class must have handle method.";
        parent::__construct($message);
    }
}