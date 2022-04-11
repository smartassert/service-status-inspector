<?php

declare(strict_types=1);

namespace SmartAssert\Tests\ServiceStatusInspector\ComponentInspector;

use SmartAssert\ServiceStatusInspector\ComponentInspectorInterface;

class ExceptionThrowingInspector implements ComponentInspectorInterface
{
    public function __construct(
        private readonly string $identifier,
        private \Throwable $exception,
    ) {
    }

    public function isAvailable(): bool
    {
        throw $this->exception;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
