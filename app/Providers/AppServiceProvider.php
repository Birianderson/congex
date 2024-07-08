<?php

namespace App\Providers;

use App\Databases\Contracts\CargoContract;
use App\Databases\Contracts\ContratoContract;
use App\Databases\Contracts\EmpresaContract;
use App\Databases\Contracts\NotaFiscalContract;
use App\Databases\Contracts\EmpenhoContract;
use App\Databases\Contracts\PessoaContract;
use App\Databases\Contracts\RiscoContratoContract;
use App\Databases\Contracts\TermoContract;
use App\Databases\Contracts\TipoArquivoContract;
use App\Databases\Repositories\CargoRepository;
use App\Databases\Repositories\ContratoRepository;
use App\Databases\Repositories\EmpresaRepository;
use App\Databases\Repositories\NotaFiscalRepository;
use App\Databases\Repositories\EmpenhoRepository;
use App\Databases\Repositories\PessoaRepository;
use App\Databases\Repositories\RiscoContratoRepository;
use App\Databases\Repositories\TermoRepository;
use App\Databases\Repositories\TipoArquivoRepository;
use App\Http\Requests\RiscoContratoRequest;
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
        app()->bind(TermoContract::class, TermoRepository::class);
        app()->bind(EmpenhoContract::class, EmpenhoRepository::class);
        app()->bind(NotaFiscalContract::class, NotaFiscalRepository::class);
        app()->bind(RiscoContratoContract::class, RiscoContratoRepository::class);
        app()->bind(TipoArquivoContract::class, TipoArquivoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
