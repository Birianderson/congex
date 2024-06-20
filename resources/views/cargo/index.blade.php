<x-app-layout>
    <div class="card">
        <div class="cardheader p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="ps-3">Cargo</div>
        </div>
        <div class="p-5">
            <div class="d-flex justify-content-end">
                <popup-button id="cargo" title="Novo Cargo" component="form-cargo" action="/cargo/" size="lg" type="primary">
                    <i class="fa fa-plus"></i>
                    Novo Cargo
                </popup-button>
            </div>
            <cargo-grid></cargo-grid>
        </div>
    </div>
</x-app-layout>
