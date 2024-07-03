<x-app-layout>
    <div class="row">
        <div class="col-lg-2 col-md-4 order-1">
            <termo-card
                numero="0"
                titulo="Contrato Inicial"
                valor_total="100"
                numero_contratos="5"
                porcentagem_contratos="30"
            ></termo-card>
        </div>
        <div class="col-lg-2 col-md-4 order-1">
            <termo-card
                numero="1"
                titulo="1° Termo Aditivo"
                valor_total="100"
                numero_contratos="5"
                porcentagem_contratos="30"
            ></termo-card>
        </div>
        <div class="col-lg-2 col-md-4 order-1">
            <termo-card
                numero="2"
                titulo="2° Termo Aditivo"
                valor_total="100"
                numero_contratos="5"
                porcentagem_contratos="30"
            ></termo-card>
        </div>
        <div class="col-lg-2 col-md-4 order-1">
            <termo-card
                numero="3"
                titulo="3° Termo Aditivo"
                valor_total="100"
                numero_contratos="5"
                porcentagem_contratos="30"
            ></termo-card>
        </div>
        <div class="col-lg-2 col-md-4 order-1">
            <termo-card
                numero="4"
                titulo="4° Termo Aditivo"
                valor_total="100"
                numero_contratos="5"
                porcentagem_contratos="30"
            ></termo-card>
        </div>
        <div class="col-lg-2 col-md-4 order-1">
            <termo-card
                numero="5"
                titulo="5° Termo Aditivo"
                valor_total="100"
                numero_contratos="5"
                porcentagem_contratos="30"
            ></termo-card>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card shadow-termo">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-body">
                            <div class="todos" id="gerencia"></div>
                            <h5 class="mt-3">Gerência de Risco</h5>
                            <span class="fw-semibold d-block mb-1">Valor dos contratos selecionados</span>
                            <h3 id="ValorSelecionado" class=" card-title text-nowrap mb-2">R$ 100.000.000 </h3>
                            <div><small id="textoselecionado" class="text-primary fw-semibold "><i
                                        class="bx bx-down-arrow-alt "></i> -14.82%</small></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <chart-radial></chart-radial>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-6">
                    <div class="card card-body shadow-termo card-height">
                        <h5 class="mt-3">Total de Contratos</h5>
                        <h2>40</h2>
                        <h3 class=" card-title text-nowrap mb-2">R$ 100.000.000 </h3>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card card-body shadow-termo card-height">
                        <h5 class="mt-3">Contratos em Atenção</h5>
                        <h2>10</h2>
                        <h3 class=" card-title text-nowrap mb-2">R$ 10.000.000 </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow-termo align-items-stretch">
                <h5 class="card-header m-0 me-2 pb-3">VALOR MONETÁRIO DOS CONTRATOS</h5>
                <form class="text-start p-3" id="filterForm">
                    <div class="row">
                        <div class="col-5">
                            <label class="form-label">Valor</label>
                            <select class="form-select" id="filterValue" aria-label="cu">
                                <option value="">Todos</option>
                                <option value="menor">Menor de R$1.000.000</option>
                                <option value="entre1e4">Entre R$1.000.000 e R$4.000.000</option>
                                <option value="entre4e9">Entre R$4.000.000 e R$9.000.000</option>
                                <option value="maior9">Acima de R$9.000.000</option>
                            </select>
                        </div>
                        <div class="col-5">
                            <label class="form-label">Situação</label>
                            <select class="form-select" id="filterSituacao" aria-label="cu">
                                <option value="">Todos</option>
                                <option value="V">Vigênte</option>
                                <option value="V1">1° Termo Aditivo</option>
                                <option value="V2">2° Termo Aditivo</option>
                                <option value="V3">3° Termo Aditivo</option>
                                <option value="V4">4° Termo Aditivo</option>
                                <option value="NV">Vencido</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <label class="form-label">Filtrar</label>
                            <button id="" class="btn btn-primary me-3" type="submit"><i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <bar-chart></bar-chart>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow-termo align-items-stretch">
                <h5 class="card-header m-0 me-2 pb-3">RISCO POR CONTRATO</h5>
                <form class="text-start p-3" id="filterForm">
                    <div class="row">
                        <div class="col-5">
                            <label class="form-label">Valor</label>
                            <select class="form-select" id="filterValue" aria-label="cu">
                                <option value="">Todos</option>
                                <option value="menor">Menor de R$1.000.000</option>
                                <option value="entre1e4">Entre R$1.000.000 e R$4.000.000</option>
                                <option value="entre4e9">Entre R$4.000.000 e R$9.000.000</option>
                                <option value="maior9">Acima de R$9.000.000</option>
                            </select>
                        </div>
                        <div class="col-5">
                            <label class="form-label">Situação</label>
                            <select class="form-select" id="filterSituacao" aria-label="cu">
                                <option value="">Todos</option>
                                <option value="V">Vigênte</option>
                                <option value="V1">1° Termo Aditivo</option>
                                <option value="V2">2° Termo Aditivo</option>
                                <option value="V3">3° Termo Aditivo</option>
                                <option value="V4">4° Termo Aditivo</option>
                                <option value="NV">Vencido</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <label class="form-label">Filtrar</label>
                            <button id="" class="btn btn-primary me-3" type="submit"><i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <bar-chart></bar-chart>
            </div>
        </div>
    </div>
</x-app-layout>
