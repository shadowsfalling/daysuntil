<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\CountdownRepositoryInterface;
use App\Repositories\CountdownRepository;
use App\Services\CountdownService;
use App\Services\CategoryService;
use App\Services\CategoryServiceInterface;
use App\Services\CountdownServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CountdownRepositoryInterface::class, CountdownRepository::class);

        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(CountdownServiceInterface::class, CountdownService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
