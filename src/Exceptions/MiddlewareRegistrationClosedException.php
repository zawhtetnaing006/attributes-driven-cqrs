<?php
namespace Zaw\AttributeDrivenCqrs\Exceptions;

class MiddlewareRegistrationClosedException extends \Exception
{
    public function __toString(): string
    {
        return "MiddlewareRegistrationClosedException: {$this->message}.";
    }
    public function __construct($middleware)
    {
        parent::__construct("Cannot register global middleware: '$middleware'. Global middleware registration cannot be done after command or query handling has started.");
    }
}
