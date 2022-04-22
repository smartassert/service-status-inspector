<?php

declare(strict_types=1);

namespace SmartAssert\Tests\ServiceStatusInspector\Unit;

use PHPUnit\Framework\TestCase;
use SmartAssert\ServiceStatusInspector\ComponentStatusInspector;
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
            'single component, component status is boolean true' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', true),
                        ])
                    ;
                })(),
                'expected' => true,
            ],
            'single component, component status is boolean false' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', false),
                        ])
                        ;
                })(),
                'expected' => false,
            ],
            'single component, component status is string' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', '0.123'),
                        ])
                        ;
                })(),
                'expected' => true,
            ],
            'single component, component is unavailable by means of throwing an exception' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ExceptionThrowingInspector('service1', new \Exception()),
                        ])
                    ;
                })(),
                'expected' => false,
            ],
            'multiple components, all have string statuses' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', '0.123'),
                            new ComponentStatusInspector('service2', '34v634'),
                            new ComponentStatusInspector('service3', 'foo'),
                        ])
                    ;
                })(),
                'expected' => true,
            ],
            'multiple components, all have boolean true statuses' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', true),
                            new ComponentStatusInspector('service2', true),
                            new ComponentStatusInspector('service3', true),
                        ])
                    ;
                })(),
                'expected' => true,
            ],
            'multiple components, one has string status, one boolean true and one boolean false' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', 'string'),
                            new ComponentStatusInspector('service2', false),
                            new ComponentStatusInspector('service3', true),
                        ])
                        ;
                })(),
                'expected' => false,
            ],
            'multiple components, one component is unavailable by means of throwing an exception' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', true),
                            new ExceptionThrowingInspector('service2', new \Exception()),
                            new ComponentStatusInspector('service3', true),
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
     * @param array<string, bool|string> $expected
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
            'single component, component status is boolean true' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', true),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => true,
                ],
            ],
            'single component, component status is boolean false' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', false),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => false,
                ],
            ],
            'single component, component status is string' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', '0.123'),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => '0.123',
                ],
            ],
            'single component, component is unavailable by means of throwing an exception' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ExceptionThrowingInspector('service1', new \Exception()),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => false,
                ],
            ],
            'multiple components, all have string statuses' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', '0.123'),
                            new ComponentStatusInspector('service2', '34v634'),
                            new ComponentStatusInspector('service3', 'foo'),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => '0.123',
                    'service2' => '34v634',
                    'service3' => 'foo',
                ],
            ],
            'multiple components, all have boolean true statuses' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', true),
                            new ComponentStatusInspector('service2', true),
                            new ComponentStatusInspector('service3', true),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => true,
                    'service2' => true,
                    'service3' => true,
                ],
            ],
            'multiple components, one has string status, one boolean true and one boolean false' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', 'string'),
                            new ComponentStatusInspector('service2', false),
                            new ComponentStatusInspector('service3', true),
                        ])
                        ;
                })(),
                'expected' => [
                    'service1' => 'string',
                    'service2' => false,
                    'service3' => true,
                ],
            ],
            'multiple components, one component is unavailable by means of throwing an exception' => [
                'inspector' => (function () {
                    return (new ServiceStatusInspector())
                        ->setComponentStatusInspectors([
                            new ComponentStatusInspector('service1', true),
                            new ExceptionThrowingInspector('service2', new \Exception()),
                            new ComponentStatusInspector('service3', true),
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
