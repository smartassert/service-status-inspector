<?php

declare(strict_types=1);

namespace SmartAssert\ServiceStatusInspector;

interface ServiceStatusInspectorInterface
{
    public function isAvailable(): bool;

    /**
     * Get an array of <service name>:bool|string
     * e.g.
     * ['service1' => true, 'service2' => false]
     * .
     *
     * @return array<string, bool|string>
     */
    public function get(): array;

    /**
     * @param iterable<ComponentStatusInspectorInterface> $inspectors
     */
    public function setComponentStatusInspectors(iterable $inspectors): ServiceStatusInspectorInterface;

    /**
     * @param iterable<string, callable> $handlers
     */
    public function setExceptionHandlers(iterable $handlers): ServiceStatusInspectorInterface;
}
