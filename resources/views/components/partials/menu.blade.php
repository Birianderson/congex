<div class="d-flex flex-column flex-shrink-0 text-white bg-gray-100" style="width: 280px;">
    <a href="/" class="text-start ms-5" style="height: 60px; width: 100%;">
        <img src="{{ asset('assets/image/logo.png') }}" alt="Logo" style="max-height: 100%; max-width: 100%; object-fit: contain;">
    </a>
    <ul class="nav flex-column mb-auto mt-3">
        <li class="mt-2">
            <a href="{{route('dashboard.index')}}" class="nav-link menu-text @if(str_contains(request()->route()->getName(), 'dashboard')) element-with-gradient text-white @endif">
                <i class="fa fa-line-chart me-2"></i>
                Dashboard
            </a>
        </li>
        <li class="mt-2">
            <a href="#" class="nav-link menu-text">
                <i class="fa fa-archive me-2"></i>
                Licitações
            </a>
        </li>
        <li class="mt-2">
            <a href="{{route('contrato.index')}}" class="nav-link menu-text @if((str_contains(request()->route()->getName(), 'contrato')) && !str_contains(request()->route()->getName(), 'financeiro')) element-with-gradient text-white @endif">
                <i class="fa fa-file-text me-2"></i>
                Contratos
            </a>
        </li>
        <li class="mt-2">
            <a href="{{route('contrato.controle_financeiro')}}" class="nav-link menu-text @if(str_contains(request()->route()->getName(), 'controle_financeiro')) element-with-gradient text-white @endif">
                <i class="fa fa-dollar me-2"></i>
                Controle Financeiro
            </a>
        </li>
        <li class="mt-2">
            <a href="{{route('risco.index')}}" class="nav-link menu-text @if(str_contains(request()->route()->getName(), 'risco')) element-with-gradient text-white @endif">
                <i class="fa fa-warning me-2"></i>
                Controle de Risco
            </a>
        </li>
        <li class="mt-2">
            <a href="{{route('empresa.index')}}" class="nav-link menu-text @if(str_contains(request()->route()->getName(), 'empresa')) element-with-gradient text-white @endif">
                <i class="fa fa-building me-2"></i>
                Empresas
            </a>
        </li>
        <li class="mt-2">
            <a href="#submenu1" data-bs-toggle="collapse" class="nav-link menu-text @if(str_contains(request()->route()->getName(), 'pessoa')) show text-primary @endif" @if(str_contains(request()->route()->getName(), 'fiscal')) aria-expanded="true" @endif>
                <i class="fa fa-cog me-2"></i>
                Configuração
                <i class="fa fa-chevron-down ms-auto"></i>
            </a>
            <ul id="submenu1" class="collapse list-unstyled ps-3  @if(str_contains(request()->route()->getName(), 'pessoa')) show @endif @if(str_contains(request()->route()->getName(), 'cargo')) show @endif" >
                <li>
                    <a href="#" class="nav-link menu-text">
                        <i class="fa fa-th-list me-2"></i>
                        Fases da Licitação
                    </a>
                </li>
                <li>
                    <a href="{{route('tipo-arquivo.index')}}" class="nav-link menu-text @if(str_contains(request()->route()->getName(), 'tipo-arquivo')) element-with-gradient text-white @endif">
                        <i class="fa fa-paperclip me-2"></i>
                        Arquivos
                    </a>
                </li>
                <li>
                    <a href="{{route('pessoa.index')}}" class="nav-link menu-text @if(str_contains(request()->route()->getName(), 'pessoa')) element-with-gradient text-white @endif">
                        <i class="fa fa-people-group me-2"></i>
                        Pessoas
                    </a>
                </li>
                <li>
                    <a href="{{route('cargo.index')}}" class="nav-link menu-text @if(str_contains(request()->route()->getName(), 'cargo')) element-with-gradient text-white @endif">
                        <i class="fa fa-briefcase me-2"></i>
                        Cargos
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link menu-text">
                        <i class="fa fa-book me-2"></i>
                        Termo Aditivo
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    <hr>
</div>
