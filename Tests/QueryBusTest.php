<?php
use Zaw\AttributeDrivenCqrs\Attributes\HandleQueryWith;
use Zaw\AttributeDrivenCqrs\Handlers\Interfaces\QueryHandlerInterface;
use Zaw\AttributeDrivenCqrs\QueryBus;
use PHPUnit\Framework\TestCase;

#[HandleQueryWith(GetProductHandler::class)]
class GetProductQuery{
    private $productId;
    public function __construct($productId) {
        $this->productId = $productId;
    }
    public function getProductId() {
        return $this->productId;
    }
}

class GetProductHandler implements QueryHandlerInterface{
    public function handle(object $query) {
        return json_encode(['productId' => $query->getProductId()]);
    }
}
class QueryBusTest extends TestCase
{

    public function test_query_run()
    {
        $query = new GetProductQuery(123);
        $result = QueryBus::getInstance()->handle($query);
        $this->assertEquals('{"productId":123}', $result);
    }
}