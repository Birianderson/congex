<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="CongexZ">
    <meta name="author" content="Equipe WEB - TCE/MT">
    <meta name="keywords" content="cms,tcemt,noticias,vídeos,legislação">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Assets  -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="wrapper" id="app">
    <x-partials.notification></x-partials.notification>
    <x-partials.menu></x-partials.menu>
    <popup></popup>
    <div class="main">
        <main class="content bg-gray-100">
            <div class="container-fluid p-0">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <div class="float-end d-flex align-content-end">
                            @yield('acoes')
                        </div>

                        <div>
                            <h1 class="h2 fw-bolder mb-3">
                                @yield('titulo')
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        {{$slot}}
                    </div>
                </div>

            </div>
        </main>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row text-muted">
                    <div class="col-6 text-start">
                        <p class="mb-0">
                            <strong>{{config('app.name')}}</strong> - <a class="text-muted" href="https://www.tce.mt.gov.br" target="_blank"><strong>Tribunal de Contas de Mato Grosso</strong></a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
@yield('after_script')
</body>
</html>
