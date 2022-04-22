<?php

declare(strict_types=1);

namespace SmartAssert\ServiceStatusInspector;

class ComponentStatusInspector implements ComponentStatusInspectorInterface
{
    public function __construct(
        private readonly string $identifier,
        private readonly bool|string $status,
    ) {
    }

    public function getStatus(): bool|string
    {
        return $this->status;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
