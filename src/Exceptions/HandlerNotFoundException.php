<?php
namespace Zaw\AttributeDrivenCqrs\Exceptions;

class HandlerNotFoundException extends \Exception
{
    public function __toString(): string
    {
        return "HandlerNotFoundException: {$this->message}.\n";
    }
    public function __construct(string $middleware)
    {
        parent::__construct("Handler class '$middleware' not found.");
    }
}
