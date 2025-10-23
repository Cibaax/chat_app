<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\ChatRepositoryInterface;
use App\Infrastructure\Eloquent\ChatRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ChatRepositoryInterface::class,
            ChatRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}