<x-app-layout>
    <div class="card">
        <div class="cardheader p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="ps-3">Fiscal</div>
        </div>
        <div class="p-5">
            <div class="d-flex justify-content-end">
                <popup-button id="empresa" title="Nova Empresa" component="form-fiscal" action="/fiscal/" size="lg" type="primary">
                    <i class="fa fa-plus"></i>
                    Novo Fiscal
                </popup-button>
            </div>
            <fiscal-grid></fiscal-grid>
        </div>
    </div>
</x-app-layout>
