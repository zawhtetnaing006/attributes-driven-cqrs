<?php
namespace Zaw\AttributeDrivenCqrs\Attributes;

use Zaw\AttributeDrivenCqrs\Exceptions\HandlerNotFoundException;
use Zaw\AttributeDrivenCqrs\Exceptions\InvalidCommandHandlerException;
use Zaw\AttributeDrivenCqrs\Handlers\Interfaces\CommandHandlerInterface;
use Zaw\AttributeDrivenCqrs\Validators\CommandHandlerValidator;

#[\Attribute]
class HandleCommandWith
{
    public function __construct(
        public readonly string $handler,
    ) {
        CommandHandlerValidator::validate($handler);
    }
}
