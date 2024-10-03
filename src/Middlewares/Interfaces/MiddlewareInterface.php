<?php
namespace Zaw\AttributeDrivenCqrs\Middlewares\Interfaces;

interface MiddlewareInterface {
    public function process($command, $result);
}