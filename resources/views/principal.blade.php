<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Busca y aplica a los proyectos disponibles para tu carrera">
    <meta name="title" content="Proyectos del Centro de Servicio Social | CSS UCA">
    <meta name="author" content="">
    <meta name="keyword" content="proyecto, proyectos CSS, CSS">
    <title>Centro de Servicio Social | UCA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <!-- Icons -->
    <link href="css/plantilla.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="{{url('/').'/img/icons/logo-uca.ico'}}">
    <link href="https://fonts.googleapis.com/css2?family=Abel&display=swap" rel="stylesheet">
    <script>
        Window.API_HOST = "{{env('API_HOST')}}"
    </script>
</head>

<body class="app sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">

    <header class="app-header navbar container-fluid">
            <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" data-toggle="collapse" data-target="#sidebarMenu" type="button">
            <span class="navbar-toggler-icon"></span>
            </button>

            
            
            <ul class="nav navbar-nav ml-auto" id=logout>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="d-md-down-none"> {{Auth::user()->correo}} </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-center" style="color: white; background-color: #003C71;">
                            <strong>Cuenta</strong>
                        </div>
                        <a class="dropdown-item" href="{{route('logout')}}" 
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-lock"></i> Cerrar sesión</a>

                        <form id="logout-form" action="{{route('logout')}}" method="POST" style="display:none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </header>

        <div class="app-body">

        @if(Auth::check())
            @if(Auth::user()->idRol == 1)
                @include('plantilla.adminSidebar')
            @else
                @include('plantilla.sidebar')
            @endif
        @else

        @endif

            <!-- Contenido Principal -->
            @yield('dashboard')
            <!-- /Fin del contenido principal -->
        </div>

    </div> <!--CLOSING VUE -->

    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
    <script>
        /*
            Implementation for auto fade sidebar when view on mobile 
            ** USING BOOTSTRAP 4 
        */
        document.querySelector(".app-body").addEventListener("click", ()=>{
            //console.log("click")
                document.querySelector("body").classList.remove("sidebar-mobile-show");
       })
    </script>
</body>
</html>