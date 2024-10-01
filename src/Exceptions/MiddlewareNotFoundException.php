<?php
namespace Zaw\AttributeDrivenCqrs\Exceptions;

class MiddlewareNotFoundException extends \Exception
{
 
    public function __toString(): string
    {
        return "MiddlewareNotFoundException: {$this->message}.\n";
    }

    public function __construct(string $middleware)
    {
        parent::__construct("Middleware class '$middleware' not found.");
    }
}