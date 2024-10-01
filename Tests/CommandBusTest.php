<?php

use PHPUnit\Framework\TestCase;
use Zaw\AttributeDrivenCqrs\CommandBus;
use Zaw\AttributeDrivenCqrs\Attributes\HandleCommandWith;
use Zaw\AttributeDrivenCqrs\Handlers\Interfaces\CommandHandlerInterface;
use Zaw\AttributeDrivenCqrs\Middlewares\GlobalMiddleware;
use Zaw\AttributeDrivenCqrs\Middlewares\Interfaces\MiddlewareInterface;

// Valid command and handler
#[HandleCommandWith(CreateUserHandler::class)]
class CreateUserCommand {
    public function __construct(public string $username, public string $email) {}
}

class CreateUserHandler implements CommandHandlerInterface {
    public function handle(object $command): string {
        return json_encode([
            "username" => $command->username,
            "email" => $command->email
        ]);
    }
}

class ValidMiddleware implements MiddlewareInterface {
    public function process(object $command, $result = null)
    {
        echo 'Valid middleware processed';
    }
}

class CommandBusTest extends TestCase
{
    public function test_command_bus_run()
    {
        CommandBus::resetInstance();
        $command = new CreateUserCommand('JohnDoe', 'john@example.com');
        $result = CommandBus::getInstance()->handle($command);

        $this->assertEquals('{"username":"JohnDoe","email":"john@example.com"}', $result);
        
    }

    public function test_register_before_handle_command_global_middleware()
    {
        CommandBus::resetInstance();
        CommandBus::getInstance()->registerBeforeHandle(ValidMiddleware::class);
        $this->assertContains(ValidMiddleware::class, CommandBus::getInstance()->getBeforeHandleMiddlewares());
    }

    public function test_before_handle_global_middleware_run()
    {
        CommandBus::resetInstance();
        GlobalMiddleware::resetInstance();
        $command = new CreateUserCommand('JohnDoe', 'john@example.com');
        $this->expectOutputString('Valid middleware processed');
        CommandBus::getInstance()->registerBeforeHandle(ValidMiddleware::class);
        CommandBus::getInstance()->handle($command);
    }

    public function test_after_handle_global_middleware_run()
    {
        CommandBus::resetInstance();
        GlobalMiddleware::resetInstance();
        $command = new CreateUserCommand('JohnDoe', 'john@example.com');
        $this->expectOutputString('Valid middleware processed');
        CommandBus::getInstance()->registerAfterHandle(ValidMiddleware::class);
        CommandBus::getInstance()->handle($command);
    }
}
