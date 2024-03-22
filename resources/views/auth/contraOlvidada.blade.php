
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
                        <input class="form-control" type="text" name="user" id="user" value="{{$user->correo}}" readonly>
                        <span style="visibility: hidden;">.</span>
                        
                        @if($errors->first('ya_cambio'))
                        {!!$errors->first('ya_cambio','<span style="color: red;margin-bottom:0.5em">:message</span>')!!}
                        </br>
                        </br>
                        <a href="{{ url('/') }}">  Regresar  </a>
                            
                        @else
                        <label id="ltoken" for="user" class="label-form">Código de verificación</label>
                            <small> Revise su correo </small>
                            <input class="form-control" type="text" name="token" id="token">
                            @if($errors->first('codigo'))
                                {!!$errors->first('codigo','<span style="color: red;">:message</span>')!!}
                            @endif

                            <label id="lpassword" for="password" class="label-form">Contraseña</label>
                            <input class="form-control" type="password" name="contrasena" id="contrasena">
                            @if($errors->first('contrasena'))
                                {!!$errors->first('contrasena','<span style="color: red;">:message</span>')!!}
                            @else
                                <span style="visibility: hidden;">.</span>
                            @endif
                            <span style="margin-top:8px;color:red"> <b>La contraseña debe de cumplir los siguientes requisitos:</b>    <br>
                                    -Mínimo 8 caracteres. <br>
                                    -Al menos 1 letra mayúscula. <br>
                                    -Al menos 1 caracter especial. <br>
                                    -Al menos 1 número. <br>
                                    -Sin espacios. <br>  
                            </span>
                            <label for="password_confirm" class="label-form">Confirmar contraseña</label>
                            <input class="form-control" type="password" name="confirmar" id="confirmar">
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
</body>


</html>