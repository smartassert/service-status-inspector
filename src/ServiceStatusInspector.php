<?php

declare(strict_types=1);

namespace SmartAssert\ServiceStatusInspector;

class ServiceStatusInspector implements ServiceStatusInspectorInterface
{
    /**
     * @var array<int|string, callable>
     */
    private array $componentInspectors = [];

    /**
     * @var array<string, bool>
     */
    private array $componentAvailabilities = [];

    /**
     * @var callable[]
     */
    private array $exceptionHandlers = [];

    public function isAvailable(): bool
    {
        $availabilities = $this->get();

        foreach ($availabilities as $availability) {
            if (false === $availability) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array<string, bool>
     */
    public function get(): array
    {
        if ([] === $this->componentAvailabilities) {
            $this->componentAvailabilities = $this->findAvailabilities();
        }

        return $this->componentAvailabilities;
    }

    public function setComponentInspector(string $name, callable $inspector): void
    {
        $this->componentInspectors[$name] = $inspector;
    }

    public function addExceptionHandler(callable $handler): void
    {
        $this->exceptionHandlers[] = $handler;
    }

    /**
     * @return array<string, bool>
     */
    private function findAvailabilities(): array
    {
        $availabilities = [];

        foreach ($this->componentInspectors as $name => $componentInspector) {
            $isAvailable = true;

            try {
                ($componentInspector)();
            } catch (\Throwable $exception) {
                $isAvailable = false;

                foreach ($this->exceptionHandlers as $exceptionHandler) {
                    ($exceptionHandler)($exception);
                }
            }

            $availabilities[(string) $name] = $isAvailable;
        }

        return $availabilities;
    }
}
