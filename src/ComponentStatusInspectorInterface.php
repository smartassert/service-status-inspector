<?php

declare(strict_types=1);

namespace SmartAssert\ServiceStatusInspector;

interface ComponentStatusInspectorInterface
{
    /**
     * @throws \Throwable
     */
    public function getStatus(): bool|string;

    public function getIdentifier(): string;
}
