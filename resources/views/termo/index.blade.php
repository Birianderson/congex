<x-app-layout>
    <div class="card">
        <div class="cardheader p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="ps-3">{{$nome}} {{$numero_contrato}} -> Termos</div>
        </div>
        <div class="p-5">
            <controle-financeiro-termo-grid data="{{$id_contrato}}"></controle-financeiro-termo-grid>
        </div>
    </div>
</x-app-layout>
