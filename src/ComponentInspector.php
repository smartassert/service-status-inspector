<?php

declare(strict_types=1);

namespace SmartAssert\ServiceStatusInspector;

class ComponentInspector implements ComponentInspectorInterface
{
    public function __construct(
        private readonly string $identifier,
        private readonly bool $isAvailable,
    ) {
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
