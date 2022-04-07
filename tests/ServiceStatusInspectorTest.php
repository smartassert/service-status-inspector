<?php

declare(strict_types=1);

namespace SmartAssert\Tests\ServiceStatusInspector;

use PHPUnit\Framework\TestCase;
use SmartAssert\ServiceStatusInspector\ServiceStatusInspector;

class ServiceStatusInspectorTest extends TestCase
{
    /**
     * @dataProvider isAvailableDataProvider
     */
    public function testIsAvailable(ServiceStatusInspector $inspector, bool $expected): void
    {
        self::assertSame($expected, $inspector->isAvailable());
    }

    /**
     * @return array<mixed>
     */
    public function isAvailableDataProvider(): array
    {
        return [
            'no components' => [
                'inspector' => new ServiceStatusInspector(),
                'expected' => true,
            ],
            'single component, component is available' => [
                'inspector' => (function () {
                    $inspector = new ServiceStatusInspector();
                    $inspector->setComponentInspector('service1', $this->createPassingInspector());

                    return $inspector;
                })(),
                'expected' => true,
            ],
            'single component, component is unavailable by means of throwing an exception' => [
                'inspector' => (function () {
                    $inspector = new ServiceStatusInspector();
                    $inspector->setComponentInspector('service1', $this->createFailingInspector(new \Exception()));

                    return $inspector;
                })(),
                'expected' => false,
            ],
            'multiple component, components are all available' => [
                'inspector' => (function () {
                    $inspector = new ServiceStatusInspector();
                    $inspector->setComponentInspector('service1', $this->createPassingInspector());
                    $inspector->setComponentInspector('service2', $this->createPassingInspector());
                    $inspector->setComponentInspector('service3', $this->createPassingInspector());

                    return $inspector;
                })(),
                'expected' => true,
            ],
            'multiple component, one component is unavailable by means of throwing an exception' => [
                'inspector' => (function () {
                    $inspector = new ServiceStatusInspector();
                    $inspector->setComponentInspector('service1', $this->createPassingInspector());
                    $inspector->setComponentInspector('service2', $this->createFailingInspector(new \Exception()));
                    $inspector->setComponentInspector('service3', $this->createPassingInspector());

                    return $inspector;
                })(),
                'expected' => false,
            ],
        ];
    }

    /**
     * @dataProvider getDataProvider
     *
     * @param array<string, bool> $expected
     */
    public function testGet(ServiceStatusInspector $inspector, array $expected): void
    {
        self::assertSame($expected, $inspector->get());
    }

    /**
     * @return array<mixed>
     */
    public function getDataProvider(): array
    {
        return [
            'no components' => [
                'inspector' => new ServiceStatusInspector(),
                'expected' => [],
            ],
            'single component, component is available' => [
                'inspector' => (function () {
                    $inspector = new ServiceStatusInspector();
                    $inspector->setComponentInspector('service1', $this->createPassingInspector());

                    return $inspector;
                })(),
                'expected' => [
                    'service1' => true,
                ],
            ],
            'single component, component is unavailable by means of throwing an exception' => [
                'inspector' => (function () {
                    $inspector = new ServiceStatusInspector();
                    $inspector->setComponentInspector('service1', $this->createFailingInspector(new \Exception()));

                    return $inspector;
                })(),
                'expected' => [
                    'service1' => false,
                ],
            ],
            'multiple component, components are all available' => [
                'inspector' => (function () {
                    $inspector = new ServiceStatusInspector();
                    $inspector->setComponentInspector('service1', $this->createPassingInspector());
                    $inspector->setComponentInspector('service2', $this->createPassingInspector());
                    $inspector->setComponentInspector('service3', $this->createPassingInspector());

                    return $inspector;
                })(),
                'expected' => [
                    'service1' => true,
                    'service2' => true,
                    'service3' => true,
                ],
            ],
            'multiple component, one component is unavailable by means of throwing an exception' => [
                'inspector' => (function () {
                    $inspector = new ServiceStatusInspector();
                    $inspector->setComponentInspector('service1', $this->createPassingInspector());
                    $inspector->setComponentInspector('service2', $this->createFailingInspector(new \Exception()));
                    $inspector->setComponentInspector('service3', $this->createPassingInspector());

                    return $inspector;
                })(),
                'expected' => [
                    'service1' => true,
                    'service2' => false,
                    'service3' => true,
                ],
            ],
        ];
    }

    private function createPassingInspector(): callable
    {
        return function () {
        };
    }

    private function createFailingInspector(\Throwable $exception): callable
    {
        return function () use ($exception) {
            throw $exception;
        };
    }
}
