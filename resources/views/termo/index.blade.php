<x-app-layout>
    <div class="card">
        <div class="cardheader p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="ps-3">{{$nome}} {{$numero_contrato}} -> Termos</div>
        </div>
        <div class="p-5">
            <div class="d-flex justify-content-end">
                <popup-button id="contrato" title="Novo Termo" component="form-termo" action="/termo/" size="xl" type="primary" data="{{$id_contrato}}">
                    <i class="fa fa-plus"></i>
                    Novo Termo
                </popup-button>
            </div>
            <controle-financeiro-termo-grid data="{{$id_contrato}}"></controle-financeiro-termo-grid>
        </div>
    </div>
</x-app-layout>
