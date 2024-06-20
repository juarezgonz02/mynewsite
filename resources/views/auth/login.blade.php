<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v3.4.0
* @link https://coreui.io
* Copyright (c) 2020 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="es">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Busca y aplica a los proyectos disponibles para tu carrera  ">
    <meta name="title" content="Proyectos centro de Servicio Social | CSS UCA">
    <meta name="author" content="">
    <meta name="keyword" content="proyecto, proyectos CSS, CSS">
     <meta name="robots" content="index"/>
    <link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
    <title> Centro de Servicio Social </title>
    <link rel="shortcut icon" type="image/jpg" href=" {{ url('/') . '/img/logo-uca.png' }}">
    <script src="https://accounts.google.com/gsi/client" async></script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .message_box {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid blue;
        }
    </style>
</head>

<body>
      <div class="wrapper">
                    <div class="container">
                        <div class="card-group">
                            <div id="form-container" class="d-flex flex-column justify-content-center align-items-center mx-auto">
                                <img alt="CSS LOGO" class="mx-auto d-block login-logotype" src="img/logotype.svg" />

                                @if ($errors->first('verified'))
                                    {!! $errors->first('verified', '<div class="message_box"> <p style="color: blue">:message</p></div>') !!}
                                @else
                                    <span style="visibility: hidden;">.</span>
                                @endif
                                <form method="POST" style="min-width: 70%" id="login_form" action="{{ route('login') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="carnet" class="label-form">Carnet</label>
                                        <input class="form-control hijos" value="{{ old('carnet') }}" id="carnet"
                                            name="carnet" type="text">
                                        @if ($errors->first('carnet'))
                                            {!! $errors->first('carnet', '<span style="color: red">:message</span>') !!}
                                        @else
                                            <span style="visibility: hidden;">.</span>
                                        @endif
                                    </div>

                                    <div class="form-group mb-2">
                                        <div class="d-flex justify-content-between " style="gap: 24px;">
                                            <label for="contraseña" class="label-form">Contraseña</label>
                                            <a class="small" href="{{ url('/contra_olvidada_form') }}">Olvidaste tu
                                                contraseña?</a>
                                        </div>
                                        <input class="form-control hijos" id="contraseña" name="contraseña"
                                            type="password">
                                        @if ($errors->first('contraseña'))
                                            {!! $errors->first('contraseña', '<span style="color: red">:message</span>') !!}
                                        @elseif($errors->first('email_inexistente'))
                                            {!! $errors->first('email_inexistente', '<span style="color: red">:message </span>') !!}
                                            <a href="{{ url('/register_form') }}">Puedes registrar tu cuenta aqui</a>
                                        @elseif($errors->first('no_verified'))
                                            {!! $errors->first('no_verified', '<span style="color: red">:message </span>') !!}
                                        @else
                                            <span style="visibility: hidden;">.</span>
                                        @endif
                                    </div>

                                    <div>
                                        <div class="d-flex justify-content-center">
                                            {!! app('captcha')->displaySubmit('login_form', 'Iniciar Sesion', [
                                                'class' => 'btn btn-login btn-lg btn-primary btn-block font-weight-bold ',
                                                'style' =>
                                                    'background-image: linear-gradient( 109.6deg,  rgba(39,142,255,1) 11.2%, rgba(98,113,255,0.78) 100.2% );',
                                            ]) !!}
                                        </div>


                                        <div style="text-align: center" class="d-flex flex-column mt-3 justify-content-center">
                                            <span> ¿No tienes cuenta? </span> 
                                              <span> <a
                                                    href="{{ url('/register_form') }}"> Registrate aquí </a>
                                              <span>
                                              <p>
                                                o
                                              </p>
                                        </div>

                                    </div>


                                    <div class="d-flex justify-content-center">
                                        <div id="g_id_onload"
                                            data-client_id="{{env('GOOGLE_CLIENT_ID')}}"
                                            data-context="signin" data-ux_mode="redirect"
                                            data-login_uri="{{ url('/oauth2Callback') }}" data-auto_prompt="false">
                                        </div>

                                        <div class="g_id_signin" data-type="standard" data-shape="rectangular"
                                            data-theme="filled_blue" data-text="signin_with" data-size="large"
                                            data-logo_alignment="left" data-width="100" data-height="110">
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center mt-4 flex-column">
                                        Encuentranos en: 
                                        <a href="https://play.google.com/store/apps/details?id=com.cssuca.app&hl=es">
                                            <img class="google-badge-class" alt="Encuentranos en Google Play " src="img/google-play-badge.png"/> 
                                        </a>
                                    </div>

                                    <div
                                        class="d-flex justify-content-center form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                        <div class="col-md-12 text-center">
                                            @if ($errors->has('g-recaptcha-response'))
                                                <span style="color: red">
                                                    {!! $errors->first('g-recaptcha-response', '<span style="color: red">Demuestra que no eres un robot!</span>') !!}
                                                </span>
                                            @else
                                                <span style="visibility: hidden;">.</span>
                                            @endif
                                        </div>
                                    </div>


                                </form>
                            </div>
            </div>
            {{-- <div class="d-none d-md-flex col-md-4 col-lg-4 bg-image image"></div> --}}
        </div>
    </div>
</body>

</html>
