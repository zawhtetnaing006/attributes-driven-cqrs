<?php

use PHPUnit\Framework\TestCase;
use Zaw\AttributeDrivenCqrs\Attributes\BeforeHandle;
use Zaw\AttributeDrivenCqrs\Attributes\HandleCommandWith;
use Zaw\AttributeDrivenCqrs\CommandBus;
use Zaw\AttributeDrivenCqrs\Exceptions\MiddlewareNotFoundException;
use Zaw\AttributeDrivenCqrs\Exceptions\MiddlewareRegistrationClosedException;
use Zaw\AttributeDrivenCqrs\Middlewares\Interfaces\MiddlewareInterface;

class TestMiddleware implements MiddlewareInterface {
    public function process(object $command, $result = null): void {
    }
}

#[HandleCommandWith(ValidHandlerOne::class)]    
class TestTwoCommand {
    public function __construct(public string $username, public string $email) {}
}

#[HandleCommandWith(ValidHandlerOne::class)]
#[BeforeHandle(NonExistedMiddleware::class)]
class TestCommand {
    public function __construct(public string $username, public string $email) {}
}

class CommandMiddlewareTest extends TestCase
{
    public function test_middleware_not_found_exception()
    {
        CommandBus::resetInstance();
        $this->expectException(MiddlewareNotFoundException::class);
        $command = new TestCommand('JaneDoe', 'jane@example.com');
        CommandBus::getInstance()->handle($command);
    }

    public function test_middleware_registration_closed_exception()
    {
        CommandBus::resetInstance();
        $this->expectException(MiddlewareRegistrationClosedException::class);
        $command = new TestTwoCommand('JaneDoe', 'jane@example.com');
        CommandBus::getInstance()->handle($command);
        CommandBus::getInstance()->registerBeforeHandle(TestMiddleware::class);
    }

    public function test_non_existed_middleware_exception()
    {
        CommandBus::resetInstance();
        $this->expectException(MiddlewareNotFoundException::class);
        CommandBus::getInstance()->registerBeforeHandle(NonExistedMiddleware::class);
    }
}
