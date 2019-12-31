<style>
    ol li a, ol li, .breadcrumb-item::before {
        color: white !important;
    }
</style>
<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center" style="background-color: #003a63">
        <a class="navbar-brand brand-logo">
            <img src="{{asset('public/images/logo.png')}}" style="width: 170px; height: 50px;">
        </a>
        <a class="navbar-brand brand-logo-mini">
            <img src="{{asset('public/images/logo_mini.png')}}" alt="logo"/>
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center" style="background-color: #008080">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item d-none d-lg-block">
                <a class="nav-link notme" href="{{route('home')}}">
                    <i class="mdi mdi-home" style="font-size: 25px !important;"></i>
                </a>
            </li>
            <li class="nav-item dropdown d-none d-xl-inline-block">
                <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown"
                   aria-expanded="false">
                    <span class="profile-text">Bienvenido, {{\Illuminate\Support\Facades\Auth::user()->name}}</span>
                    <img class="img-xs rounded-circle" src="{{asset('public/staradmin/assets/images/faces/face8.jpg')}}"
                         alt="Profile image"> </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <br>
                    <form action="{{route('logout')}}" method="POST" style="padding: 0">
                        {{csrf_field()}}
                        <button type="submit" class="dropdown-item">
                            Salir
                        </button>
                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>
