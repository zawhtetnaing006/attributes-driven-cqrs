<?php
use PHPUnit\Framework\TestCase;
use Zaw\AttributeDrivenCqrs\Attributes\HandleQueryWith;
use Zaw\AttributeDrivenCqrs\Exceptions\HandlerNotFoundException;
use Zaw\AttributeDrivenCqrs\Exceptions\InvalidQueryHandlerException;
use Zaw\AttributeDrivenCqrs\Exceptions\NoQueryHandlersFoundException;
use Zaw\AttributeDrivenCqrs\QueryBus;

#[HandleQueryWith(InvalidQueryHandler::class)]
class GetOrderQuery
{
    public function __construct(private int $orderId)
    {
    }

    public function getProductId()
    {
        return $this->orderId;
    }
}

#[HandleQueryWith(NonexistedQueryHandler::class)]
class GetCartQuery
{
    public function __construct(private int $cartId)
    {
    }
}

class GetUserQuery
{
    public function __construct(private int $userId)
    {
    }
}

class InvalidQueryHandler
{
    public function process(object $query)
    {

    }
}

class QueryHandlerTest extends TestCase
{
    public function test_invalid_query_handler()
    {
        $query = new GetOrderQuery(123);
        $this->expectException(InvalidQueryHandlerException::class);
        QueryBus::getInstance()->handle($query);
    }

    public function test_nonexisted_query_handler()
    {
        $query = new GetCartQuery(123);
        $this->expectException(HandlerNotFoundException::class);
        QueryBus::getInstance()->handle($query);
    }

    public function test_no_query_handlers_found_exception()
    {
        $query = new GetUserQuery(123);
        $this->expectException(NoQueryHandlersFoundException::class);
        QueryBus::getInstance()->handle($query);
    }
}