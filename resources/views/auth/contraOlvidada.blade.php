
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/changePassword.css') }}">
    <link href="{{ asset('js/plantilla.css') }}" >
    <link href="{{ asset('js/jquery.min.css') }}" >
    <link href="{{ asset('js/bootstrap.min.css') }}" >
    <link rel="icon" href="{{url('/').'/img/icons/logo-uca.ico'}}">

    <!--<script src="js/plantilla.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>-->
    
    <title>Contraseña Olvidada</title>
</head>



<body>
    <div class="container" id="main-container">
        <div class="card-body" id="cuerpoc">
        
        @if(session()->has('message'))
            <span style="color: green;">{{session()->get('message')}}</span>
            </br>
            <a href="{{ url('/') }}"> Regresar </a>
        @else
            <form id="form-horizontal" action="{{ url('/cambiar_contra_olvidada') }}" method="post">
                {{ csrf_field() }}
                    <div id="heading">
                        <h1>Cambiar contraseña</h1>
                    </div>
                    <div class="form-group row" id="form-group">
                        <label id="luser" for="user" class="label-form">Usuario</label>
                        <input class="form-control" type="text" name="user"  id="user" value="{{$user->correo}}" readonly>
                        <span style="visibility: hidden;">.</span>
                        
                        @if($errors->first('ya_cambio'))
                        {!!$errors->first('ya_cambio','<span style="color: red;margin-bottom:0.5em">:message</span>')!!}
                        </br>
                        </br>
                        <a href="{{ url('/') }}">  Regresar  </a>
                            
                        @else
                        <label id="ltoken" for="user" class="label-form">Código de verificación</label>
                            <input class="form-control" type="text" readonly value="{{$token}}" name="token" id="token" required>
                            @if($errors->first('token'))
                                {!!$errors->first('token','<span style="color: red;">:message</span>')!!}
                            @else
                                <span style="visibility: hidden;">.</span>
                            @endif

                            <div id="helper" style="padding: 0px">
                                <template>
                                    <password-validator></password-validator>
                                </template>
                            </div>

                            @if($errors->first('contrasena'))
                                {!!$errors->first('contrasena','<span style="color: red;">:message</span>')!!}
                            @else
                                <span style="visibility: hidden;">.</span>
                            @endif
                            
                            <label for="password_confirm"  class="label-form">Confirmar contraseña</label>
                            <input class="form-control" required type="password" name="confirmar" id="confirmar">
                            @if($errors->first('confirmar'))
                                {!!$errors->first('confirmar','<span style="color: red;margin-bottom:0.5em">:message</span>')!!}
                            @else
                                <span style="visibility: hidden;">.</span>
                            @endif
                            </div>
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" id="submit" class="btn btn-primary">Establecer contraseña</button>
                            </div>
                        @endif
                </form>
            </div>
        @endif
    </div>
    <script src="{{url('/js/helpers.js')}}"></script>
</body>


</html>