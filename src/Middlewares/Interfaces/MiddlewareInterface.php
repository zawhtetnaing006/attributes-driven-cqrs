<?php
namespace Zaw\AttributeDrivenCqrs\Middlewares\Interfaces;

interface MiddlewareInterface {
    public function process(object $command, $result = null);
}