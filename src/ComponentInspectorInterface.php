<?php

declare(strict_types=1);

namespace SmartAssert\ServiceStatusInspector;

interface ComponentInspectorInterface
{
    /**
     * @throws \Throwable
     */
    public function isAvailable(): bool;

    public function getIdentifier(): string;
}
