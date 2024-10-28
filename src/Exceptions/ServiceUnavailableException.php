<?php

declare(strict_types=1);

namespace Appino\LaravelEureka\Exceptions;

class ServiceUnavailableException extends \Exception
{
    public function __construct(string $serviceName)
    {
        parent::__construct("Service '{$serviceName}' are unavailable");
    }
}