
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
            <span style="color: red;">{{trans('auth.fail_contra_olvidada_token')}}</span>
            </br>
            <a href="{{ url('/') }}"> Regresar </a>
    </div>
    <script src="{{url('/js/helpers.js')}}"></script>
</body>


</html>