<?php

declare(strict_types=1);

namespace SmartAssert\ServiceStatusInspector;

class ServiceStatusInspector implements ServiceStatusInspectorInterface
{
    /**
     * @var ComponentStatusInspectorInterface[]
     */
    private array $componentStatusInspectors = [];

    /**
     * @var array<string, bool|string>
     */
    private array $componentStatuses = [];

    /**
     * @var ExceptionHandlerInterface[]
     */
    private array $exceptionHandlers = [];

    public function isAvailable(): bool
    {
        $statuses = $this->get();

        foreach ($statuses as $status) {
            if (is_bool($status) && false === $status) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array<string, bool|string>
     */
    public function get(): array
    {
        if ([] === $this->componentStatuses) {
            $this->componentStatuses = $this->findStatuses();
        }

        return $this->componentStatuses;
    }

    public function setComponentStatusInspectors(iterable $inspectors): ServiceStatusInspectorInterface
    {
        foreach ($inspectors as $inspector) {
            if ($inspector instanceof ComponentStatusInspectorInterface) {
                $this->componentStatusInspectors[$inspector->getIdentifier()] = $inspector;
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
     * @return array<string, bool|string>
     */
    private function findStatuses(): array
    {
        $statuses = [];

        foreach ($this->componentStatusInspectors as $name => $componentInspector) {
            try {
                $status = $componentInspector->getStatus();
            } catch (\Throwable $exception) {
                $status = false;

                foreach ($this->exceptionHandlers as $exceptionHandler) {
                    $exceptionHandler->handle($exception);
                }
            }

            $statuses[$name] = $status;
        }

        return $statuses;
    }
}
