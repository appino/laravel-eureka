<?php

namespace Appino\LaravelEureka\Console\Commands;

use Appino\LaravelEureka\Factories\EurekaClientFactory;
use Eureka\EurekaClient;
use Illuminate\Console\Command;

class HeartBeatCommand extends Command
{


    protected const WAITING_TIME = 30;

    protected $signature = 'eureka:heartbeat';

    private EurekaClient $client;

    public function __construct()
    {
        parent::__construct();

        $this->client = EurekaClientFactory::getEurekaClient();
    }

    public function handle(): void
    {
        while (true) {
            try {
                if ($this->client->isRegistered()) {
                    $this->client->heartbeat();
                }
            } catch (\Exception) {
                echo 'Eureka is not available. Waiting ' . self::WAITING_TIME . ' second.' . PHP_EOL;

                sleep(self::WAITING_TIME);
            }
        }
    }

}