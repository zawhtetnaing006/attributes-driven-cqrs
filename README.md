# Attribute Driven CQRS
![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)
![Package List Version](https://img.shields.io/badge/version-v1.0.0-blue)

A simple PHP package that makes it easy to implement the CQRS (Command Query Responsibility Segregation) pattern using attributes. With this package, you can streamline command and query handling without a lot of boilerplate, making your code cleaner and more manageable.

## Requirement

- PHP 8.1+

## Installation

```bash
composer require zaw/attribute-driven-cqrs
```

## Examples

### 1. Commands

- A Command describes a single action. It won't execute it. 
- Commands are pure PHP classes whose purpose is to only hold the values needed to execute an operation. 
- Commands can be handled by a command handler. Each command must have a handler. 
- The `HandleCommandWith` attribute can be used to link a command to its handler. The handler class will receive the command as a parameter in its `handle` method.
Note: A command should not return a value. But the package provide a feature to access return  values from handlers to `Middlewares` to handle edge cases.

Example: 

```php
use Zaw\AttributeDrivenCqrs\CommandBus;
use Zaw\AttributeDrivenCqrs\Attributes\HandleCommandWith;

#[HandleCommandWith(CreateUserHandler::class)]
class CreateUserCommand 
{
    public function __construct(private string $username, private string $email) {}

    public function getUserName(): string 
    {
        return $this->username;
    }

    public function getEmail(): string 
    {
        return $this->email;
    }
}

class CreateUserHandler 
{
    public function handle(object $command): void 
    {
        echo "User '{".$command->getUserName()."}' created with email '{".$command->email."}'";
    }
}

$command = new CreateUserCommand('JohnDoe', 'john@example.com');
CommandBus::getInstance()->handle($command);
```

### 2. Queries

- A query describe a data retrival. But it doens't perform it. 
- Each query must have a handler. The `HandleQueryWith` attribute can be used to link a query to its handler. 
- The handler class will receive the query as a parameter in its `handle` method.

```php
use Zaw\AttributeDrivenCqrs\QueryBus;
use Zaw\AttributeDrivenCqrs\Attributes\HandleQueryWith;

#[HandleQueryWith(GetUserHandler::class)]
class GetUserQuery {
    public function __construct(private int $userId) {}
    public function getUserId() 
    {
        return $this->userId;    
    }
}

class GetUserHandler {
    public function handle(object $query) 
    {
        return $userService->getUser($query->getUserId());
    }
}
$query = new GetUserQuery(1);
$result = QueryBus::getInstance()->handle($query);
```

### 3. Handlers

Handlers are responsible for executing the logic associated with a command or query. 

- Handlers must implement the `handle` method, which accepts the command or query as a parameter. This is where the actual logic related to the action (in the case of commands) or data retrieval (in the case of queries) is performed.

- **Dependency Injection Support**: Handlers can now take advantage of dependency injection. This allows you to inject services, repositories, or other dependencies directly into the handler. The package uses [PHP-DI](https://php-di.org/) to resolve handler dependencies automatically.

Example:

```php
use Psr\Log\LoggerInterface;

class CreateUserHandler {
    private LoggerInterface $logger;

    // Services or other dependencies can be injected via the constructor
    public function __construct(LoggerInterface $logger) 
    {
        $this->logger = $logger;
    }

    public function handle(CreateUserCommand $command): void 
    {
        $this->logger->info("Creating user '{$command->getUserName()}'");
        echo "User '{$command->getUserName()}' created with email '{$command->getEmail()}'";
    }
}
```

```php
class GetProductHandler {
    private ProductService $productService;

    // Dependency injection of the ProductService
    public function __construct(ProductService $productService) 
    {
        $this->productService = $productService;
    }

    public function handle(GetProductQuery $query): Product 
    {
        return $this->productService->getProduct($query->getProductId());
    }
}
```

### 4. Middlewares

- Middlewares allow you to execute custom logic before/after a command or query handler is executed.
- Middlewares must implement the `MiddlewareInterface`, which enforces the implementation of the `process` method. The `process` method receives the command or query as the first parameter and the return value from the `handle` method of the CommandHandler/QueryHandler as the second parameter (if applicable).
- **Dependency Injection Support**: Middlewares can now take advantage of dependency injection.

Middlewares can be applied in two ways:

#### Local Middleware
Local middleware applies to specific commands or queries. You can attach middleware directly using the `BeforeHandle` and `AfterHandle` attributes. Local middleware can be used for purposes such as logging, or dispatching events after a command has been successfully handled.

- **BeforeHandle**: Runs before the handler is executed.
- **AfterHandle**: Runs after the handler has finished.

Example of adding local middleware:

```php
use Zaw\AttributeDrivenCqrs\Attributes\BeforeHandle;
use Zaw\AttributeDrivenCqrs\Attributes\AfterHandle;
use Zaw\AttributeDrivenCqrs\Middlewares\Interfaces\MiddlewareInterface;

#[BeforeHandle(LoggingMiddleware::class)]
#[HandleCommandWith(CreateUserHandler::class)]
class CreateUserCommand {
    public function __construct(private string $username, private string $email) {}
}

class LoggingMiddleware implements MiddlewareInterface 
{
    public function process($command, $result)
    {
        echo "Logging: Command " . get_class($command) . " is being processed.";
    }
}
```

#### Global Middleware
Global middleware applies to all commands or queries within a specific bus (CommandBus or QueryBus). These middlewares will automatically run before or after any command or query is handled. Global middleware can be used for purposes such as logging, setting default db connection for each bus, adding authentication for each bus. 

To register global middlewares, you can use the `registerBeforeHandle` and `registerAfterHandle` methods:

```php
CommandBus::getInstance()->registerBeforeHandle(LoggingMiddleware::class);
CommandBus::getInstance()->registerAfterHandle(ValidationMiddleware::class);
```

## Exceptions

The package includes several custom exceptions to help you catch common errors:

- `NoHandlersFoundException`: No handler is found for the command/query.
- `MultipleHandlersFoundException`: Multiple handlers are found for the same command/query.
- `HandlerNotFoundException`: The specified handler class doesn’t exist.
- `InvalidCommandHandlerException`: The handler class doesn’t implement handle method.
- `MiddlewareNotFoundException`: A middleware class couldn’t be found.
- `MiddlewareRegistrationClosedException`: You tried to add global middleware after handling started.

## Performance Considerations

This package uses PHP reflection to find handlers and middleware. But don’t worry — reflection data is cached, so even with a lot of commands and queries, performance remains smooth(unless we're running thousands of commands and queries within a single request-respones cycle).

## License

This package is licensed under the [MIT License](LICENSE).

## Contributing

We welcome contributions! If you'd like to help improve this package, please open an issue or submit a pull request or contact me directly at [zawhtetnaing006@gmail.com](mailto:zawhtetnaing006@gmail.com). Your feedback is invaluable in making this package better for everyone!