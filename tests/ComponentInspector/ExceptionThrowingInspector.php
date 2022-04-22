<?php

declare(strict_types=1);

namespace SmartAssert\Tests\ServiceStatusInspector\ComponentInspector;

use SmartAssert\ServiceStatusInspector\ComponentStatusInspector;

class ExceptionThrowingInspector extends ComponentStatusInspector
{
    public function __construct(
        private readonly string $identifier,
        private \Throwable $exception,
    ) {
        parent::__construct($this->identifier, false);
    }

    public function isAvailable(): bool
    {
        throw $this->exception;
    }
}
