<?php
    $ruta = App\Http\Controllers\UserController::ruta(1);
?>
<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v3.4.0
* @link https://coreui.io
* Copyright (c) 2020 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">

<head>
  <base href="./">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta name="description" content="Centro de Servicio Social | UCA">
  <meta name="author" content="">
  <meta name="keyword" content="Sistema de inscripción de proyectos CSS UCA">
  <link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/register.css" rel="stylesheet">
  <script src="js/plantilla.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <title> Registrate </title>
  <link rel="shortcut icon" type="image/jpg" href=" {{url('/').'/img/logo-uca.png'}}">

  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <!-- <link href="css/plantilla.css" rel="stylesheet"> -->
</head>
<script>
  $(document).ready(function() {
    function getCarreras() {
      $.ajax({
        type: "GET",
        url: './carreraxfacultad',
        data: {
          idFact: idFact
        },
        success: function(res) {
          $('#carrera').empty();
          $.each(res, function(key) {
            $('#carrera').append($("<option></option>").val(res[key]['idCarrera']).text(res[key]['nombre']));
          })

        },
        error: function() {
          //console.log("No se ha seleccionado facultad");
        }
      })
    }

    var idFact = $('#facultad').val()
    getCarreras()

    $('#facultad').change(function() {
      idFact = $('#facultad').val()
      getCarreras()
    })

    $('#gform').submit(function() {
      $('#loading').show();
      $('#registrarbtn').prop('disabled', true);
    });
  });
  
</script>
<body>
  <div class="wrapper">
    <div class="container">
      <div class="card-group">
        <div class="titulo">
          <h1 id="titulo">Completa tu información</h1>
        </div>
        <div class="card p-4">
          <form id="gform" class="form-horizontal" method="POST" action="{{ route('google.register_google') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <span> Una vez haya completado este formulario, podrá acceder directamente con su cuenta de Google, luego, podrá cambiar su información en la pestaña perfil</b> </span>  
            </div>
            <div class="card-body">
              
              <input id="userId" type="text" hidden value="{{$userId}}" class="form-control" name="userId">
              <div class="form-group">
                <label for="carnet" class="label-form">Carnet</label>
                <input id="carnet" type="text" readOnly value="{{$correo}}" class="form-control" name="correo">
                <span style="visibility: hidden;">.</span>
              </div>
              <div class="form-group">
                <label for="nombres" class="label-form">Nombres</label>
                <input id="nombres" type="text" readOnly value="{{$nombres}}" class="form-control" name="nombres" >
                <span style="visibility: hidden;">.</span>
              </div>
              <div class="form-group">
                <label for="apellidos" class="label-form">Apellidos</label>
                <input id="apellidos" type="text" readOnly value="{{$apellidos}}" class="form-control" name="apellidos">
                <span style="visibility: hidden;">.</span>
              </div>
              <div class="form-group mb-3">
                <label for="genero" class="label-form">Genero</label>
                <select class="form-control" id="genero" name="genero">
                  <option value="F">Femenino</option>
                  <option value="M">Masculino</option>
                </select>
              </div>
              <label for="facultad" class="label-form">Facultad</label>
              <div class="form-group mb-3">
                <select class="form-control" id="facultad" name="facultad">
                  @foreach($fact as $value)
                  <option value="{{$value['idFacultad']}}">{{$value['nombre']}}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group ">
                <label for="carrera" class="label-form">Carrera</label>
                <select class="form-control" id="carrera" name="carrera"></select>
                @if($errors->first('email_existente'))
                {!!$errors->first('email_existente','<span style="color: red">:message</span>')!!}
                @else
                <span style="visibility: hidden;">.</span>
                @endif
              </div>

              <div class="form-group ">
                <label for="carrera" class="label-form">Año de carrera</label>
                <select class="form-control" id="perfil" name="perfil">
                  <option value=1> Primer Año </option>
                  <option value=2> Segundo Año </option>
                  <option value=3> Tercer Año </option>
                  <option value=4> Cuarto Año </option>
                  <option value=5> Quinto Año </option>
                  <option value=6> Egresado </option>
                </select>
                 <span style="visibility: hidden;">.</span>

              </div>
              <!--
              <div class="form-group">
                    <label for="contrasena" class="label-form">Contraseña</label>
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
              </div>

              <div class="form-group">
                    <label for="confirmar" class="label-form">Confirmar contraseña</label>
                    <input class="form-control" required type="password" name="confirm_contra" id="confirmar">
                    @if($errors->first('confirm_contra'))
                        {!!$errors->first('confirm_contra','<span style="color: red;margin-bottom:0.5em">:message</span>')!!}
                    @else
                        <span style="visibility: hidden;">.</span>
                    @endif
              </div>

              <div class="form-group mb-2{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                <div class="col-md-12">
                  @if ($errors->has('g-recaptcha-response'))
                  <span style="color: red">
                        Demuestra que no eres un robot!
                  </span>
                  @endif
                </div>
              </div>
            -->
              <div style="display:none;" id="loading">
                <div style=" display: flex;  flex-direction:column; justify-content: center; align-items: center;">
                  <img src="<?php echo $ruta; ?>/img/snake.gif" alt="Loading" /><span  > Cargando....</span>
                </div>
              </div>
            </div>
             {!! app('captcha')->displaySubmit('gform', 'Registrame', ['class' => 'btn btn-primary', 'id'=>'registrarbtn', 'style' => 'background-image: linear-gradient( 109.6deg,  rgba(39,142,255,1) 11.2%, rgba(98,113,255,0.78) 100.2% );']) !!}
            <!--button type="submit" class="btn btn-primary" style="background-image: linear-gradient( 109.6deg,  rgba(39,142,255,1) 11.2%, rgba(98,113,255,0.78) 100.2% );" id="registrarbtn">Registrame</button>
            -->
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="{{url('/js/helpers.js')}}"></script>

</body>

</html>