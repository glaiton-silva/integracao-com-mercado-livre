<?php
namespace App\Jobs;

use App\Http\Controllers\MercadoLivreController;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Models\Category;

class SyncMercadoLivreCategoriesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(){}

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
        $controller = app(MercadoLivreController::class);
            $categories = $controller->getCategories();
            if (empty($categories)) {
                Log::warning("Nenhuma categoria encontrada.");
                return;
            }
            foreach ($categories as $category) {
                Category::updateOrCreate(
                    ['ml_category_id' => $category['id']],
                    ['name' => $category['name']]
                );
                $controller->fetchAndSaveCategoryAttributes($category['id']);
            }
        } catch (\Exception $e) {
            Log::error("Erro ao processar: " . $e->getMessage());
        }
    }
}
