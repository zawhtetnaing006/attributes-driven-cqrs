<?php
namespace Zaw\AttributeDrivenCqrs\Exceptions;
use Zaw\AttributeDrivenCqrs\Middlewares\Interfaces\MiddlewareInterface;

class InvalidMiddlewareException extends \Exception
{
    public function __toString(): string
    {
        return "InvalidMiddlewareException: {$this->message}.\n";
    }
    public function __construct(string $class)
    {
        $message = "$class must implement ".MiddlewareInterface::class;
        parent::__construct($message);
    }
}