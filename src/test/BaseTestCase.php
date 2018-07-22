<?php
use PHPUnit\Framework\TestCase;
class BaseTestCase extends TestCase {
    public function invoke_private_method($instance, $method_name, array $parameters) {
        $reflection = new ReflectionClass($instance);
        $method = $reflection->getMethod($method_name);
        $method->setAccessible(true);
        return $method->invokeArgs($instance, $parameters);
    }
    public function assertArraySimilar(array $expected, array $actual) {
        $this->assertEmpty(array_diff($expected, $actual));
        $this->assertEmpty(array_diff($actual, $expected));
    }
}
