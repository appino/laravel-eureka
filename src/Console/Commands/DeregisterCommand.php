<?php

namespace Appino\LaravelEureka\Console\Commands;

use Appino\LaravelEureka\Factories\EurekaClientFactory;
use Eureka\EurekaClient;
use Illuminate\Console\Command;

class DeregisterCommand extends Command
{


    protected const WAITING_TIME = 1;

    protected $signature = 'eureka:deregister';

    private EurekaClient $client;

    public function __construct()
    {
        parent::__construct();

        $this->client = EurekaClientFactory::getEurekaClient();
    }

    public function handle(): void
    {
        try {
            if ($this->client->isRegistered()) {
                $this->client->deRegister();
            }
        } catch (\Exception) {
            echo 'Eureka is not available. Waiting ' . self::WAITING_TIME . ' second.' . PHP_EOL;

            sleep(self::WAITING_TIME);
        }
    }

}