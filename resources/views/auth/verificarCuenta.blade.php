<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/verifyAccount.css') }}">
    <link href="{{ asset('js/plantilla.css') }}" >
    <link href="{{ asset('js/jquery.min.css') }}" >
    <link href="{{ asset('js/bootstrap.min.css') }}" >
    <link rel="icon" href="{{url('/').'/img/icons/logo-uca.ico'}}">

    <!--<link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="./olvidec.css" rel="stylesheet">
    <script src="js/plantilla.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>-->
    <title>Verificar Cuenta</title>
</head>

<body>
    <div class="container" id="main-container">
        <div class="card-body" id="cuerpoc">
            <form id="form-horizontal" action="{{ url('/verificar_usuario/'.$user->correo) }}" method="post">
            {{ csrf_field() }}
                <div class="heading">
                    <h1>Verifique su cuenta</h1>
                </div>
                <div class="form-group row" id="form-group">

                    <label for="user" class="label-form l">Usuario</label>
                    <input class="form-control" type="text" name="user" id="user" value="{{$user->correo}}" readonly>
                    <label for="token" class="label-form l">Código enviado a su correo</label>
                    <input class="form-control" required type="text" value="{{old('token')}}"  name="token" id="token" >
                    @if($errors->first('token'))
                        {!!$errors->first('token','<span style="color: red;">:message</span>')!!}
                    @else
                        <span style="visibility: hidden;">.</span>
                    @endif


                    <label class="label-form" style="padding-left: 1em;" for="contrasena">Contraseña</label>
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

                    <label for="confirmar" class="label-form">Confirmar contraseña</label>
                    <input class="form-control" required type="password" name="confirmar" id="confirmar">
                    @if($errors->first('confirmar'))
                        {!!$errors->first('confirmar','<span style="color: red;margin-bottom:0.5em">:message</span>')!!}
                    @else
                        <span style="visibility: hidden;">.</span>
                    @endif
                </div>
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" id="submit" class="btn btn-primary">Verificar cuenta</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{url('/js/helpers.js')}}"></script>
</body>
</html>