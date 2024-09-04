<?php

// En app/Console/Commands/RetryFailedJobs.php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class RetryFailedJobs extends Command
{
    protected $signature = 'queue:retry-failed';
    protected $description = 'Reintentar todos los trabajos fallidos';

    public function handle()
    {
        echo "Reintentando todos los trabajos fallidos...\n";
        $this->call('queue:retry', ['all'] 	);
    }
}