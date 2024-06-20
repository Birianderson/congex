<x-app-layout>
    <div class="card">
        <div class="cardheader p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="ps-3">Pessoa</div>
        </div>
        <div class="p-5">
            <div class="d-flex justify-content-end">
                <popup-button id="pessoa" title="Nova Empresa" component="form-pessoa" action="/pessoa/" size="lg" type="primary">
                    <i class="fa fa-plus"></i>
                    Nova Pessoa
                </popup-button>
            </div>
            <pessoa-grid></pessoa-grid>
        </div>
    </div>
</x-app-layout>
