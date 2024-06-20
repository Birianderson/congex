<?php

namespace App\Providers;

use App\Databases\Contracts\CargoContract;
use App\Databases\Contracts\EmpresaContract;
use App\Databases\Contracts\FiscalContract;
use App\Databases\Repositories\CargoRepository;
use App\Databases\Repositories\EmpresaRepository;
use App\Databases\Repositories\FiscalRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        app()->bind(EmpresaContract::class, EmpresaRepository::class);
        app()->bind(FiscalContract::class, FiscalRepository::class);
        app()->bind(CargoContract::class, CargoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
