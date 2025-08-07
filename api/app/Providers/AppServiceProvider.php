<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\TaskRepositoryInterface;
use App\Infrastructure\Repositories\EloquentTaskRepository;
use App\Domain\Repositories\KeywordRepositoryInterface;
use App\Infrastructure\Repositories\EloquentKeywordRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
        $this->app->bind(KeywordRepositoryInterface::class, EloquentKeywordRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
