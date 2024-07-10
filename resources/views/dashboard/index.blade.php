<x-app-layout>
    <dashboard
        data-pizza="{{ json_encode($dataPizza) }}"
        valores-contratos="{{ json_encode($valoresContratos) }}"
        nomes-contratos="{{ $nomesContratos }}"
        status-contratos="{{ json_encode($statusContratos) }}"
        total-contratos="{{ json_encode($totalContratos) }}"
        porcentagens="{{ json_encode($porcentagens) }}"
        valor-total="{{json_encode($valortotal)}}"
        risco-contratos-bar-chart-risco="{{json_encode($riscoContratos)}}"
        atencao="{{json_encode($atencao)}}"
        pessoas_id="{{json_encode($pessoas_id)}}"
        cargos_id="{{json_encode($cargos_id)}}"
    ></dashboard>
</x-app-layout>
