<x-app-layout>
    <dashboard
        data-pizza="{{ json_encode($dataPizza) }}"
        valores-contratos="{{ json_encode($valoresContratos) }}"
        nomes-contratos="{{ $nomesContratos }}"
        status-contratos="{{ json_encode($statusContratos) }}"
        total-contratos="{{ json_encode($totalContratos) }}"
        valortotalformatado="{{ json_encode($valortotalformatado) }}"
        porcentagens="{{ json_encode($porcentagens) }}"
    ></dashboard>
</x-app-layout>
