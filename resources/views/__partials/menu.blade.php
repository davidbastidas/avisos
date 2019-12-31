<style>
    nav ul li a:hover:not(#UserDropdown):not(.notme) {
        background-color: #d69d0f !important;
    }

    .menu-eca {
        background-color: #003a63;

    }

    ul li span {
        color: white !important;
    }

    ul li p {
        color: white !important;
    }

    ul li i {
        color: white !important;
    }

    a[aria-expanded="true"]:not(#UserDropdown){
        background-color: #d69d0f !important;
    }
</style>
<nav class="sidebar sidebar-offcanvas menu-eca" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <div class="nav-link">
                <div class="user-wrapper">
                    <div class="profile-image">
                        <img src="{{asset('public/staradmin/assets/images/faces/face3.jpg')}}" alt="profile image"></div>
                    <div class="text-wrapper">
                        <p class="profile-name">{{\Illuminate\Support\Facades\Auth::user()->name}}  </p>
                        <div>
                            <small class="designation text-muted">Admin</small>
                            <span class="status-indicator online"></span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        @foreach($menus as $menu)
            @if(Auth::user()->autorizarPerfil([$menu->ruta]))
                <li class="nav-item">
                    <a class="nav-link" @if($menu->hijos != "[]") data-toggle="collapse" @endif
                    @if($menu->hijos != "[]") href="#dashboard-dropdown{{$menu->id}}"
                       @else
                       href="{{route($menu->ruta_name)}}"
                       @endif
                       aria-expanded="false"
                       aria-controls="dashboard-dropdown">
                        <i class="menu-icon {{$menu->icon}}"></i>
                        <span class="menu-title">{{$menu->nombre}}</span>
                        @if($menu->hijos != "[]")
                            <i class="menu-arrow"></i>
                        @endif
                    </a>
                    <div class="collapse" id="dashboard-dropdown{{$menu->id}}">
                        <ul class="nav flex-column sub-menu">
                            @foreach($menu->hijos as $hijo)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route($hijo->ruta_name)}}"
                                       style="text-align: left !important; margin-left: -30px !important; width: 100% !important;"
                                       @if(Request::is($menu->ruta)) class="active" @endif>
                                        <i class="menu-icon {{$hijo->icon}}"></i>
                                        {{$hijo->nombre}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endif
        @endforeach
    </ul>
</nav>
