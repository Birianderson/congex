<x-app-layout>
    <div class="card">
        <div class="cardheader p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="ps-3">Empresas</div>
        </div>
        <div class="p-5">
            <div class="d-flex justify-content-end">
                <popup-button id="empresa" title="Nova Empresa" component="form-empresa" action="/empresa/" size="lg">
                    <i class="fa fa-plus"></i>
                    Nova Empresa
                </popup-button>
            </div>
            <empresa-grid></empresa-grid>
        </div>
    </div>
</x-app-layout>
