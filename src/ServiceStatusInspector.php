<?php

declare(strict_types=1);

namespace SmartAssert\ServiceStatusInspector;

class ServiceStatusInspector implements ServiceStatusInspectorInterface
{
    /**
     * @var ComponentStatusInterface[]
     */
    private array $componentInspectors = [];

    /**
     * @var array<string, bool>
     */
    private array $componentAvailabilities = [];

    /**
     * @var ExceptionHandlerInterface[]
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
            if ($inspector instanceof ComponentStatusInterface) {
                $this->componentInspectors[$inspector->getIdentifier()] = $inspector;
            }
        }

        return $this;
    }

    public function setExceptionHandlers(iterable $handlers): ServiceStatusInspectorInterface
    {
        foreach ($handlers as $handler) {
            if ($handler instanceof ExceptionHandlerInterface) {
                $this->exceptionHandlers[] = $handler;
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
                    $exceptionHandler->handle($exception);
                }
            }

            $availabilities[$name] = $isAvailable;
        }

        return $availabilities;
    }
}
