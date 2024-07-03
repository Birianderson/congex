<?php

namespace App\Http\Controllers\Dashboard;
use App\Databases\Models\Contrato;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(){}

    public function index(Request $request): View
    {
        $contratos = Contrato::query()->orderBy('valor')->get();
        $valoresContratos = [];
        $nomesContratos = [];
        $statusCount = [
            'V' => 0,
            'NV' => 0,
            'V1' => 0,
            'V2' => 0,
            'V3' => 0,
            'V4' => 0,
        ];

        $labelMapping = [
            'V' => 'Vigente',
            'NV' => 'Não Vigente',
            'V1' => 'Vigente 1 termo',
            'V2' => 'Vigente 2 termo',
            'V3' => 'Vigente 3 termo',
            'V4' => 'Vigente 4 termo',
        ];
        $valortotal = 0;
        $dividendo = 0;

        foreach ($contratos as $contrato) {
            $dividendo++;
            $status = $contrato->situacao;
            $valortotal = $valortotal + $contrato->valor;
            $valoresContratos[] = $contrato->valor;
            $nomesContratos[] = $contrato->empresa;
            $statusContratos[] = $contrato->situacao;
            if (array_key_exists($status, $statusCount)) {
                $statusCount[$status]++;
            }
        }

        return view('dashboard.index');
    }


}

