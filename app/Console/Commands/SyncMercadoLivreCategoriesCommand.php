<?php

namespace App\Console\Commands;

use App\Jobs\SyncMercadoLivreCategoriesJob;
use Illuminate\Console\Command;

class SyncMercadoLivreCategoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-mercado-livre-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza as categorias do Mercado Livre com o banco de dados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando a sincronização das categorias e atributos do Mercado Livre...');

        // Despacha o job para a fila
        SyncMercadoLivreCategoriesJob::dispatch();

        $this->info('Job para sincronização enviado com sucesso!');
    }
}
