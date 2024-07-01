<x-app-layout>
    <div class="card">
        <div class="cardheader p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="ps-3">{{$termoStr}} - {{$nome}} de número {{$numero_contrato}}</div>
        </div>
        <div class="p-5">
            <div class="d-flex justify-content-end">
                <popup-button data="{{$id_termo}}" id="contrato" title="Novo Empenho" component="form-empenho" action="/contrato/" size="xl" type="primary">
                    <i class="fa fa-plus"></i>
                    Novo Empenho
                </popup-button>
            </div>
            <controle-financeiro-empenho-grid data="{{$id_termo}}"></controle-financeiro-empenho-grid>
        </div>
    </div>
</x-app-layout>
