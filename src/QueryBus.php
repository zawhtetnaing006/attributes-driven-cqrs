<?php
namespace Zaw\AttributeDrivenCqrs;
use Zaw\AttributeDrivenCqrs\Exceptions\MiddlewareRegistrationClosedException;
use Zaw\AttributeDrivenCqrs\Handlers\Handler;
use Zaw\AttributeDrivenCqrs\Middlewares\AttributeMiddleware;
use Zaw\AttributeDrivenCqrs\Middlewares\GlobalMiddleware;

class QueryBus{
    private static $instance = null;
    private $allowMiddlewareRegistration = true;

    private $context = [];
    private function __construct(){}
    private function __clone() {}

    public static function getInstance(): QueryBus
    {
        if(is_null(self::$instance)){
            self::$instance = new QueryBus();
        }
        return self::$instance;
    }

    public static function resetInstance(): void
    {
        self::$instance = null;
    }

    final public function handle(object $query): mixed {
        $this->allowMiddlewareRegistration = false;

        GlobalMiddleware::getInstance()->runBeforeHandleQueryMiddlewares($query);
        AttributeMiddleware::runBeforeHandleMiddlewares($query);

        $result = Handler::handlequery($query);

        AttributeMiddleware::runAfterHandleMiddlewares($query, $result);
        GlobalMiddleware::getInstance()->runAfterHandleQueryMiddlewares($query, $result);
        return $result;
    }

    final public function registerBeforeHandle(string $middlewareClass): void
    {
        if(!$this->allowMiddlewareRegistration) {
            throw new MiddlewareRegistrationClosedException($middlewareClass);
        }
        GlobalMiddleware::getInstance()->registerBeforeHandleQueryMiddleware($middlewareClass);
    }

    final public function registerAfterHandle(string $middlewareClass): void
    {
        if(!$this->allowMiddlewareRegistration) {
            throw new MiddlewareRegistrationClosedException($middlewareClass);
        }
        GlobalMiddleware::getInstance()->registerAfterHandleQueryMiddleware($middlewareClass);
    }

    
    public function setContext(string $key, mixed $value): void
    {
        $this->context[$key] = $value;
    }

    public function getContext(string $key): mixed
    {
        return $this->context[$key] ?? null;
    }
}