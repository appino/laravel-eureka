<?php
declare(strict_types=1);

namespace Appino\LaravelEureka\Factories;

use Eureka\EurekaClient;
use Eureka\Exceptions\InstanceFailureException;
use Appino\LaravelEureka\Exceptions\ServiceUnavailableException;
use Illuminate\Support\Facades\Request;

abstract class EurekaClientFactory
{
    private static EurekaClient $eurekaClient;
    private static array $unavailableServices = [];

    public static function getEurekaClient(): EurekaClient
    {
        if (!isset(self::$eurekaClient)) {
            $homePageUrl = config('laravel-eureka.service_ip') . ':' . config('laravel-eureka.service_port');

            self::$eurekaClient = new EurekaClient([
                'eurekaDefaultUrl' => config('laravel-eureka.eureka_url'),
                'hostName' => config('laravel-eureka.service_ip'),
                'appName' => config('app.name'),
                'ip' => config('laravel-eureka.service_ip'),
                'port' => [ config('laravel-eureka.service_port'), true],
                'homePageUrl' => $homePageUrl,
                'healthCheckUrl' => "$homePageUrl/api/health-check"
            ]);
        }

        return self::$eurekaClient;
    }

    /**
     * This method was created for using it in requests or another short time execution code.
     * You probably don't want to do many requests for one service when it's not available,
     * because it will probably not register.
     *
     * If you for some reasons don't want to base on saved unavailable services, just use
     * @see EurekaClient::fetchInstance()
     * You probably want to use that method when you write a long time execution code.
     *
     * @throws ServiceUnavailableException
     */
    public static function fetchInstance(string $serviceName): object
    {
        if (in_array($serviceName, self::$unavailableServices)) {
            throw new ServiceUnavailableException($serviceName);
        }

        try {
            return self::getEurekaClient()->fetchInstance($serviceName);
        } catch (InstanceFailureException) {
            self::$unavailableServices[] = $serviceName;

            throw new ServiceUnavailableException($serviceName);
        }
    }
}
