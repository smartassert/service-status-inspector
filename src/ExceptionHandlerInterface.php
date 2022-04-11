<?php

declare(strict_types=1);

namespace SmartAssert\ServiceStatusInspector;

interface ExceptionHandlerInterface
{
    public function handle(\Throwable $exception): void;
}
