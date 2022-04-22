<?php

declare(strict_types=1);

namespace SmartAssert\ServiceStatusInspector;

interface ComponentStatusInterface
{
    /**
     * @throws \Throwable
     */
    public function isAvailable(): bool;

    public function getIdentifier(): string;
}
