<?php

namespace Appino\LaravelEureka\Console\Commands;

use Appino\LaravelEureka\Console\Commands\Abstracts\AbstractCommand;
use Appino\LaravelEureka\Factories\EurekaClientFactory;
use Eureka\EurekaClient;
use Eureka\Exceptions\DeRegisterFailureException;

class EurekaHealthCheckCommand extends AbstractCommand
{
    protected const WAITING_TIME = 1;

    protected $signature = 'eureka:run-heartbeat';

    private EurekaClient $client;

    public function __construct()
    {
        parent::__construct();

        $this->client = EurekaClientFactory::getEurekaClient();
    }

    public function __invoke(): void
    {
        while (true) {
            try {
                $this->client->start();
            } catch (\Exception) {
                echo 'Eureka is not available. Waiting ' . self::WAITING_TIME . ' second.' . PHP_EOL;

                sleep(self::WAITING_TIME);
            }
        }
    }

    public function shutdown(): void
    {
        if (!$this->client->isRegistered()) {
            die;
        }

        try {
            $this->client->deRegister();
        } catch (DeRegisterFailureException $exception) {
            $this->error('Service was unable to deregister. Exception message: ' . $exception->getMessage());
        }

        die;
    }
}