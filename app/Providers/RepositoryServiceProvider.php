<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\Contracts\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\AutoTypeRepository::class, \App\Repositories\AutoTypeRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\AutoModelRepository::class, \App\Repositories\AutoModelRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\AutoMotorRepository::class, \App\Repositories\AutoMotorRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\AutoRepository::class, \App\Repositories\AutoRepositoryEloquent::class);
        //:end-bindings:

        //Services
        $this->app->bind(\App\Services\Contracts\UserService::class, \App\Services\UserService::class);
        $this->app->bind(\App\Services\Contracts\AutoTypeService::class, \App\Services\AutoTypeService::class);
        $this->app->bind(\App\Services\Contracts\AutoModelService::class, \App\Services\AutoModelService::class);
        $this->app->bind(\App\Services\Contracts\AutoMotorService::class, \App\Services\AutoMotorService::class);
        $this->app->bind(\App\Services\Contracts\AutoService::class, \App\Services\AutoService::class);
    }
}
