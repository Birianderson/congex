<x-app-layout>
    <div class="card">
        <div class="cardheader p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="ps-3">Tipos de Arquivo</div>
        </div>
        <div class="p-5">
            <div class="d-flex justify-content-end">
                <popup-button id="tipo-arquivo" title="Novo Arquivo" component="form-tipo-arquivo" action="/tipo-arquivo/" size="lg" type="primary">
                    <i class="fa fa-plus"></i>
                    Nova Arquivo
                </popup-button>
            </div>
            <tipo-arquivo-grid></tipo-arquivo-grid>
        </div>
    </div>
</x-app-layout>
