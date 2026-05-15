<?php

namespace App\Providers;

use App\interfaces\AppsheetRepositoryInterface;
use App\Repositories\AppsheetRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        
        $this->app->bind(AppsheetRepositoryInterface::class, AppsheetRepository::class);
       
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
