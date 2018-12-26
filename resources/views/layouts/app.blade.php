<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('__partials.head')
@if(isset(\Illuminate\Support\Facades\Auth::user()->id))
    <body class="sidebar-fixed sidebar-icon-only">
    <div class="container-scroller">
        @include('__partials.nav')
        <div class="container-fluid page-body-wrapper">
            @include('__partials.menu')
            <div class="main-panel">
                <div class="content-wrapper">
                    @include('__partials.scripts')
                    @yield('content')
                </div>
                @include('__partials.footer')
            </div>
        </div>
    </div>
    </body>
@else
    @if (\Request::is('/'))
        <body>
        <div class="container-scroller">
            @yield('content')
        </div>
        </body>
    @endif
    @if (\Request::is('login'))
        <body>
        <div class="container-scroller">
            @yield('content')
        </div>
        </body>
    @endif
@endif
</html>
