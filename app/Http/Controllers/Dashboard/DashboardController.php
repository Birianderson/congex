<?php

namespace App\Http\Controllers\Dashboard;

use App\Databases\Models\Contrato;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {

        $contratos = Contrato::query()->with(['empresa', 'responsabilidades', 'termos', 'risco'])
            ->selectRaw("
                CONTRATO.*,
                CASE
                    WHEN contrato.situacao = 'NV' THEN (SELECT ta.data_inicio FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V0' THEN (SELECT ta.data_inicio FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V1' THEN (SELECT ta.data_inicio FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V2' THEN (SELECT ta.data_inicio FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V3' THEN (SELECT ta.data_inicio FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V4' THEN (SELECT ta.data_inicio FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                END AS data_inicio ,
                CASE
                    WHEN contrato.situacao = 'NV' THEN (SELECT ta.data_fim FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V0' THEN (SELECT ta.data_fim FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V1' THEN (SELECT ta.data_fim FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '1')
                    WHEN contrato.situacao = 'V2' THEN (SELECT ta.data_fim FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '2')
                    WHEN contrato.situacao = 'V3' THEN (SELECT ta.data_fim FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '3')
                    WHEN contrato.situacao = 'V4' THEN (SELECT ta.data_fim FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '4')
                END AS data_fim_real ,
                CASE
                    WHEN contrato.situacao = 'NV' THEN (SELECT ta.VALOR FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V0' THEN (SELECT ta.VALOR FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '0')
                    WHEN contrato.situacao = 'V1' THEN (SELECT ta.VALOR FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '1')
                    WHEN contrato.situacao = 'V2' THEN (SELECT ta.VALOR FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '2')
                    WHEN contrato.situacao = 'V3' THEN (SELECT ta.VALOR FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '3')
                    WHEN contrato.situacao = 'V4' THEN (SELECT ta.VALOR FROM TERMO ta WHERE ta.CONTRATO_ID = contrato.id AND ta.numero = '4')
                END AS valor_real
            ")->orderBy('valor_real')->get();
        $valoresContratos = [];
        $nomesContratos = [];
        $riscoContratos = [];
        $riscoPossibilidadesContratos = [];
        $riscoSituacaoContratos = [];
        $statusCount = [
            'V0' => 0,
            'V1' => 0,
            'V2' => 0,
            'V3' => 0,
            'V4' => 0,
            'V5' => 0,
            'NV' => 0,
        ];

        $labelMapping = [
            'V0' => 'Contrato Inicial',
            'V1' => 'Vigente 1 termo',
            'V2' => 'Vigente 2 termo',
            'V3' => 'Vigente 3 termo',
            'V4' => 'Vigente 4 termo',
            'V5' => 'Vigente 5 termo',
            'NV' => 'Não Vigente',
        ];
        $valortotal = 0;
        $dividendo = 0;
        $atencao = [];
        foreach ($contratos as $contrato) {
            if ($contrato->situacao != 'NV') {
                $valortotal = $valortotal + $contrato->valor_real;
            }
            $dividendo++;
            $status = $contrato->situacao;
            $valoresContratos[] = $contrato->valor_real;
            $riscoContratos[] = $contrato->risco->pontuacao ?? 0;
            $riscoPossibilidadesContratos[] = $contrato->risco->possibilidades ?? '';
            $riscoSituacaoContratos[] = $contrato->risco->situacao ?? '';
            $nomesContratos[] = $contrato->empresa->nome . ' - N° ' . $contrato->numero;
            $tempCargos = [];
            $tempPessoas = [];
            foreach ($contrato->responsabilidades as $responsabilidade) {
                $tempCargos[] = $responsabilidade->cargo_id;
                $tempPessoas[] = $responsabilidade->pessoa_id;
            }
            $cargos_id[] = implode(',', $tempCargos);
            $pessoas_id[] = implode(',', $tempPessoas);
            $statusContratos[] = $contrato->situacao;
            if (array_key_exists($status, $statusCount)) {
                $statusCount[$status]++;
            }
            $dataFimReal = Carbon::parse($contrato->data_fim_real);
            $hoje = Carbon::now();
            $quatroMesesFuturo = $hoje->copy()->addMonths(4);
            $atencao[] = ($dataFimReal->greaterThan($hoje) && $dataFimReal->lessThan($quatroMesesFuturo)) ? 1 : 0;
        }
        // Calcula a soma dos valores no array
        $total = array_sum($statusCount);

        // Calcula a porcentagem para cada valor
        $porcentagens = [];
        if ($total > 0) {
            foreach ($statusCount as $value) {
                $porcentagem = ($value / $total) * 100;
                $porcentagemFormatada = number_format($porcentagem, 1);
                $porcentagens[] = $porcentagemFormatada;
            }
        } else {
            $statusContratos[] = array_fill(0, count($statusCount), '0.0');
            $porcentagens = array_fill(0, count($statusCount), '0.0');
        }


        $series = array_values($statusCount);

        $labels = array_map(function ($label) use ($labelMapping) {
            return $labelMapping[$label];
        }, array_keys($statusCount));

        $dataPizza = [
            'series' => $series,
            'labels' => $labels,
        ];
        $totalContratos = count($valoresContratos);
        $valoresContratos = json_encode($valoresContratos);
        $nomesContratos = json_encode($nomesContratos);
        $riscoContratos = json_encode($riscoContratos);
        $riscoPossibilidadesContratos = json_encode($riscoPossibilidadesContratos);
        $riscoSituacaoContratos = json_encode($riscoSituacaoContratos);

        return view('dashboard.index', compact('dataPizza', 'valoresContratos', 'nomesContratos', 'statusContratos', 'totalContratos', 'porcentagens', 'valortotal', 'riscoContratos','riscoPossibilidadesContratos','riscoSituacaoContratos', 'atencao', 'cargos_id', 'pessoas_id'));
    }
}

