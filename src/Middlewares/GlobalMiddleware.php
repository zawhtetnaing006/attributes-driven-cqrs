<?php
namespace Zaw\AttributeDrivenCqrs\Middlewares;
use Zaw\AttributeDrivenCqrs\Builders\MiddlewareBuilder;
use Zaw\AttributeDrivenCqrs\Validators\MiddlewareValidator;

class GlobalMiddleware
{
    private static $instance = null;
    private $beforeHandleCommandMiddlewares = [];
    private $afterHandleCommandMiddlewares = [];
    private $beforeHandleQueryMiddlewares = [];
    private $afterHandleQueryMiddlewares = [];
    private function __construct(){}
    private function __clone() {}

    public static function getInstance(): GlobalMiddleware
    {
        if(is_null(self::$instance)){
            self::$instance = new GlobalMiddleware();
        }
        return self::$instance;
    }

    public static function resetInstance(): void
    {
        self::$instance = null;
    }
    
    public function handleMiddlewares($middlewares, object $commandOrQuery, $result = null): void
    {
        foreach ($middlewares as $middlewareClass) {
            $middleware = MiddlewareBuilder::getMiddleWareInstance($middlewareClass);
            $middleware->process($commandOrQuery, $result);
        }
    }


    public function runBeforeHandleQueryMiddlewares(object $query, $result = null): void
    {
        $this->handleMiddlewares($this->beforeHandleQueryMiddlewares, $query, $result);
    }

    public function runAfterHandleQueryMiddlewares(object $query, $result = null): void 
    {
        $this->handleMiddlewares($this->afterHandleQueryMiddlewares, $query, $result);
    }

    public function runBeforeHandleCommandMiddlewares(object $command, $result = null): void
    {
        $this->handleMiddlewares($this->beforeHandleCommandMiddlewares, $command, $result);
    }

    public function runAfterHandleCommandMiddlewares(object $command, $result = null): void
    {
        $this->handleMiddlewares($this->afterHandleCommandMiddlewares, $command, $result);
    }

    public function registerBeforeHandleCommandMiddleware(string $middlewareClass): void
    {
        MiddlewareValidator::validate($middlewareClass);
        $this->beforeHandleCommandMiddlewares[] = $middlewareClass;
    }

    public function registerAfterHandleCommandMiddleware(string $middlewareClass): void
    {
        MiddlewareValidator::validate($middlewareClass);
        $this->afterHandleCommandMiddlewares[] = $middlewareClass;
    }

    public function registerBeforeHandleQueryMiddleware(string $middlewareClass): void
    {
        MiddlewareValidator::validate($middlewareClass);
        $this->beforeHandleQueryMiddlewares[] = $middlewareClass;
    }

    public function registerAfterHandleQueryMiddleware(string $middlewareClass): void
    {
        MiddlewareValidator::validate($middlewareClass);
        $this->afterHandleQueryMiddlewares[] = $middlewareClass;
    }

    public function getBeforeHandleCommandMiddlewares(): array
    {
        return $this->beforeHandleCommandMiddlewares;
    }

    public function getAfterHandleCommandMiddlewares(): array   
    {
        return $this->afterHandleCommandMiddlewares;
    }

    public function getBeforeHandleQueryMiddlewares(): array
    {
        return $this->beforeHandleQueryMiddlewares;
    }

    public function getAfterHandleQueryMiddlewares(): array
    {
        return $this->afterHandleQueryMiddlewares;
    }
}