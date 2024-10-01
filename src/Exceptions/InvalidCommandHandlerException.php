<?php
namespace Zaw\AttributeDrivenCqrs\Exceptions;
use Zaw\AttributeDrivenCqrs\Handlers\Interfaces\CommandHandlerInterface;

class InvalidCommandHandlerException extends \Exception
{
    public function __toString(): string
    {
        return "InvalidCommandHandlerException: {$this->message}.\n";
    }
    public function __construct(string $commandClass)
    {
        $message = "$commandClass must have handle method.";
        parent::__construct($message);
    }
}