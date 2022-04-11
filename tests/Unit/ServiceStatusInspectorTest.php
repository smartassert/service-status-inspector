<?php

declare(strict_types=1);

namespace SmartAssert\Tests\ServiceStatusInspector\Unit;

use PHPUnit\Framework\TestCase;
use SmartAssert\ServiceStatusInspector\ComponentInspector;
use SmartAssert\ServiceStatusInspector\ServiceStatusInspector;
use SmartAssert\Tests\ServiceStatusInspector\ComponentInspector\ExceptionThrowingInspector;

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
                    return (new ServiceStatusInspector())
                        ->setComponentInspectors([
                            new ComponentInspector('service1', true),
                        ])
                    ;
                })(),
                'expected' => true,
            ],
            'single component, component is unavailable' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentInspectors([
                            new ComponentInspector('service1', false),
                        ])
                        ;
                })(),
                'expected' => false,
            ],
            'single component, component is unavailable by means of throwing an exception' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentInspectors([
                            new ExceptionThrowingInspector('service1', new \Exception()),
                        ])
                    ;
                })(),
                'expected' => false,
            ],
            'multiple component, components are all available' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentInspectors([
                            new ComponentInspector('service1', true),
                            new ComponentInspector('service2', true),
                            new ComponentInspector('service3', true),
                        ])
                    ;
                })(),
                'expected' => true,
            ],
            'multiple component, one component is unavailable' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentInspectors([
                            new ComponentInspector('service1', true),
                            new ComponentInspector('service2', false),
                            new ComponentInspector('service3', true),
                        ])
                        ;
                })(),
                'expected' => false,
            ],
            'multiple component, one component is unavailable by means of throwing an exception' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentInspectors([
                            new ComponentInspector('service1', true),
                            new ExceptionThrowingInspector('service2', new \Exception()),
                            new ComponentInspector('service3', true),
                        ])
                        ;
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
                    return (new ServiceStatusInspector())
                        ->setComponentInspectors([
                            new ComponentInspector('service1', true),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => true,
                ],
            ],
            'single component, component is unavailable' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentInspectors([
                            new ComponentInspector('service1', false),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => false,
                ],
            ],
            'single component, component is unavailable by means of throwing an exception' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentInspectors([
                            new ExceptionThrowingInspector('service1', new \Exception()),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => false,
                ],
            ],
            'multiple component, components are all available' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentInspectors([
                            new ComponentInspector('service1', true),
                            new ComponentInspector('service2', true),
                            new ComponentInspector('service3', true),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => true,
                    'service2' => true,
                    'service3' => true,
                ],
            ],
            'multiple component, one component is unavailable' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentInspectors([
                            new ComponentInspector('service1', true),
                            new ComponentInspector('service2', false),
                            new ComponentInspector('service3', true),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => true,
                    'service2' => false,
                    'service3' => true,
                ],
            ],
            'multiple component, one component is unavailable by means of throwing an exception' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentInspectors([
                            new ComponentInspector('service1', true),
                            new ExceptionThrowingInspector('service2', new \Exception()),
                            new ComponentInspector('service3', true),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => true,
                    'service2' => false,
                    'service3' => true,
                ],
            ],
        ];
    }
}
