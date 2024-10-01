<?php

use PHPUnit\Framework\TestCase;
use Zaw\AttributeDrivenCqrs\CommandBus;
use Zaw\AttributeDrivenCqrs\Attributes\HandleCommandWith;
use Zaw\AttributeDrivenCqrs\Exceptions\HandlerNotFoundException;
use Zaw\AttributeDrivenCqrs\Exceptions\NoHandlersFoundException;
use Zaw\AttributeDrivenCqrs\Exceptions\MultipleHandlersFoundException;
use Zaw\AttributeDrivenCqrs\Exceptions\InvalidCommandHandlerException;
use Zaw\AttributeDrivenCqrs\Handlers\Interfaces\CommandHandlerInterface;

#[HandleCommandWith(InvalidCommandHandler::class)]
class CreateProductCommand {
    public function __construct(public string $username, public string $email) {}
}

#[HandleCommandWith(NonExistedHandler::class)]
class InvalidCommand {
    public function __construct(public string $username, public string $email) {}
}
class NoHandlerCommand {
    public function __construct(public string $username, public string $email) {}
}

#[HandleCommandWith(ValidHandlerOne::class)]
#[HandleCommandWith(NoHandlerCommand::class)]
class MultipleHandlersCommand {
    public function __construct(public string $username, public string $email) {}
}
class InvalidCommandHandler {
    public function handle(object $command): void {}
}

class ValidHandlerOne implements CommandHandlerInterface{
    public function handle(object $command): mixed {
        return "Success";
    }
}

class ValidHandlerTwo implements CommandHandlerInterface{
    public function handle(object $command): mixed {
        return "Success";
    }
}

class CommandHandlerTest extends TestCase
{
    public function test_invalid_command_handler_exception()
    {
        CommandBus::resetInstance();
        $this->expectException(InvalidCommandHandlerException::class);
        $command = new CreateProductCommand('JaneDoe', 'jane@example.com');
        CommandBus::getInstance()->handle($command);
    }

    // Test for NoHandlersFoundException
    public function test_no_handlers_found_exception()
    {
        CommandBus::resetInstance();
        $this->expectException(NoHandlersFoundException::class);
        // Assuming no handler is registered for this command
        $command = new NoHandlerCommand('JaneDoe', 'jane@example.com');
        CommandBus::getInstance()->handle($command);
    }

    // Test for MultipleHandlersFoundException
    public function test_multiple_handlers_found_exception()
    {
        CommandBus::resetInstance();
        $this->expectException(MultipleHandlersFoundException::class);
        $command = new MultipleHandlersCommand('JaneDoe', 'jane@example.com');
        CommandBus::getInstance()->handle($command);
    }

    public function test_handler_not_found_exception()
    {
        CommandBus::resetInstance();
        $this->expectException(exception: HandlerNotFoundException::class);
        $command = new InvalidCommand('JaneDoe', 'jane@example.com');
        CommandBus::getInstance()->handle($command);
    }
}
