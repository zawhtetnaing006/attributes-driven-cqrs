<?php
namespace Zaw\AttributeDrivenCqrs;

use Zaw\AttributeDrivenCqrs\Exceptions\MiddlewareRegistrationClosedException;
use Zaw\AttributeDrivenCqrs\Handlers\Handler;
use Zaw\AttributeDrivenCqrs\Middlewares\AttributeMiddleware;
use Zaw\AttributeDrivenCqrs\Middlewares\GlobalMiddleware;

class CommandBus{
    private static $instance = null;
    private $allowMiddlewareRegistration = true;

    private function __construct(){}
    private function __clone() {}

    public static function getInstance(): CommandBus
    {
        if(is_null(self::$instance)){
            self::$instance = new CommandBus();
        }
        return self::$instance;
    }

    public static function resetInstance(): void
    {
        self::$instance = null;
    }

    final public function handle(object $command): mixed {
        $this->allowMiddlewareRegistration = false;

        GlobalMiddleware::getInstance()->runBeforeHandleCommandMiddlewares($command);
        AttributeMiddleware::runBeforeHandleMiddlewares($command);

        $result = Handler::handleCommand($command);

        AttributeMiddleware::runAfterHandleMiddlewares($command, $result);
        GlobalMiddleware::getInstance()->runAfterHandleCommandMiddlewares($command, $result);
        return $result;
    }

    public function registerBeforeHandle(string $middlewareClass): void
    {
        if(!$this->allowMiddlewareRegistration){
            throw new MiddlewareRegistrationClosedException($middlewareClass);
        }
        GlobalMiddleware::getInstance()->registerBeforeHandleCommandMiddleware($middlewareClass);
    }

    public function registerAfterHandle(string $middlewareClass): void
    {
        if(!$this->allowMiddlewareRegistration){
            throw new MiddlewareRegistrationClosedException($middlewareClass);
        }
        GlobalMiddleware::getInstance()->registerAfterHandleCommandMiddleware($middlewareClass);
    }

    public function getBeforeHandleMiddlewares(): array
    {
        return GlobalMiddleware::getInstance()->getBeforeHandleCommandMiddlewares();
    }

    public function getAfterHandleMiddlewares(): array
    {
        return GlobalMiddleware::getInstance()->getAfterHandleCommandMiddlewares();
    }
}