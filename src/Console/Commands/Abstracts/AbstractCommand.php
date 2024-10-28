<?php

declare(strict_types = 1);

namespace Appino\LaravelEureka\Console\Commands\Abstracts;

use Illuminate\Console\Command;

abstract class AbstractCommand extends Command
{
    public function __construct()
    {
        parent::__construct();

        pcntl_async_signals(true);

        pcntl_signal(SIGINT, [$this, 'shutdown']);
        pcntl_signal(SIGTERM, [$this, 'shutdown']);
    }

    abstract protected function shutdown(): void;
}
