<x-app-layout>
    <div class="card">
        <div class="cardheader p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="ps-3">{{$nome}} {{$numero_contrato}} -> {{$termoStr}} -> Empenho de N° {{$numero_empenho}} -> Notas Fiscais</div>
        </div>
        <div class="p-5">
            <div class="d-flex justify-content-end">
                <popup-button data="{{$id_pagamento}}" id="nota_fiscal" title="Nova Nota Fiscal" component="form-nota-fiscal" action="/contrato/" size="xl" type="primary">
                    <i class="fa fa-plus"></i>
                    Nova Nota Fiscal
                </popup-button>
            </div>
            <controle-financeiro-nota-fiscal data="{{$id_pagamento}}"></controle-financeiro-nota-fiscal>
        </div>
    </div>
</x-app-layout>
