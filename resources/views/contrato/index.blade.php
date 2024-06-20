<x-app-layout>
    <div class="card">
        <div class="cardheader p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="ps-3">Contratos</div>
        </div>
        <div class="p-5">
            <div class="d-flex justify-content-end">
                <popup-button id="contrato" title="Novo Contrato" component="form-contrato" action="/contrato/" size="xl" type="primary">
                    <i class="fa fa-plus"></i>
                    Novo Contrato
                </popup-button>
            </div>
            <contrato-grid></contrato-grid>
        </div>
    </div>
</x-app-layout>
