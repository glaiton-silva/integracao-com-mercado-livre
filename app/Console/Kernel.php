<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     */
    protected $commands = [
        // Se você criar comandos personalizados, registre-os aqui
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Exemplo: Agendar o Job SyncMercadoLivreDataJob para rodar diariamente
        $schedule->job(new \App\Jobs\SyncMercadoLivreDataJob())->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Carregar comandos personalizados da pasta 'Commands'
        $this->load(__DIR__.'/Commands');

        // Incluir comandos que podem ser registrados em routes/console.php
        require base_path('routes/console.php');
    }
}
