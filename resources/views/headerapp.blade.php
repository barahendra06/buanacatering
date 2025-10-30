@yield('header')
{{-- <header id="home"> --}}
    <div id="navbar">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="container px-lg-1 text-center">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img class="logo-header pt-1" src="{{ asset('img/logo-header.png') }}" style="width: 130px; position: relative; z-index: 999;">
                </a>
            </div>
        </nav>
    </div>

    
{{-- </header> --}}