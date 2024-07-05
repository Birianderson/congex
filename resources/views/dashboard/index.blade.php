<x-app-layout>
    <dashboard
        data-pizza="{{ json_encode($dataPizza) }}"
        valores-contratos="{{ json_encode($valoresContratos) }}"
        nomes-contratos="{{ $nomesContratos }}"
        status-contratos="{{ json_encode($statusContratos) }}"
        total-contratos="{{ json_encode($totalContratos) }}"
        porcentagens="{{ json_encode($porcentagens) }}"
        valor-total="{{json_encode($valortotal)}}"
    ></dashboard>
</x-app-layout>
