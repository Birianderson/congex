<?php

namespace App\Providers;

use App\Databases\Contracts\CargoContract;
use App\Databases\Contracts\ContratoContract;
use App\Databases\Contracts\EmpresaContract;
use App\Databases\Contracts\PessoaContract;
use App\Databases\Repositories\CargoRepository;
use App\Databases\Repositories\ContratoRepository;
use App\Databases\Repositories\EmpresaRepository;
use App\Databases\Repositories\PessoaRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        app()->bind(EmpresaContract::class, EmpresaRepository::class);
        app()->bind(PessoaContract::class, PessoaRepository::class);
        app()->bind(CargoContract::class, CargoRepository::class);
        app()->bind(ContratoContract::class, ContratoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
