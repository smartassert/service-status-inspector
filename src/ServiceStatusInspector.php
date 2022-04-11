<?php

declare(strict_types=1);

namespace SmartAssert\ServiceStatusInspector;

class ServiceStatusInspector implements ServiceStatusInspectorInterface
{
    /**
     * @var ComponentInspectorInterface[]
     */
    private array $componentInspectors = [];

    /**
     * @var array<string, bool>
     */
    private array $componentAvailabilities = [];

    /**
     * @var array<string, callable>
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

    public function setComponentInspectors(iterable $inspectors): ServiceStatusInspectorInterface
    {
        foreach ($inspectors as $inspector) {
            if ($inspector instanceof ComponentInspectorInterface) {
                $this->componentInspectors[$inspector->getIdentifier()] = $inspector;
            }
        }

        return $this;
    }

    public function setExceptionHandlers(iterable $handlers): ServiceStatusInspectorInterface
    {
        foreach ($handlers as $name => $handler) {
            if (is_callable($handler)) {
                $this->exceptionHandlers[(string) $name] = $handler;
            }
        }

        return $this;
    }

    /**
     * @return array<string, bool>
     */
    private function findAvailabilities(): array
    {
        $availabilities = [];

        foreach ($this->componentInspectors as $name => $componentInspector) {
            try {
                $isAvailable = $componentInspector->isAvailable();
            } catch (\Throwable $exception) {
                $isAvailable = false;

                foreach ($this->exceptionHandlers as $exceptionHandler) {
                    ($exceptionHandler)($exception);
                }
            }

            $availabilities[$name] = $isAvailable;
        }

        return $availabilities;
    }
}
