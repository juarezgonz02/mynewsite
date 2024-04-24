<?php
    $ruta = App\Http\Controllers\UserController::ruta(1);
?>
<head>
<meta charset="utf-8">
    <link href="css/errorPage.css" rel="stylesheet">
    <script>
        
    </script>
    <meta name="description" content="Centro de Servicio Social | UCA">
    <meta name="author" content="">
    <meta name="keyword" content="Sistema de inscripción de proyectos CSS UCA">
    <title> Oops, 404! </title>
    <link rel="shortcut icon" type="image/jpg" href=" {{url('/').'/img/logo-uca.png'}}">

</head>-
<main>
  <div class="container">
      <div style="text-align: center;">
        <span>
            <img src="<?php echo $ruta; ?>/img/UCA.6b065e25.png" alt="uca" style="width: 150px; height: 200px;">
            <p id="css">Centro de Servicio Social</p>
        </span>
    </div>
    <h1 id="title">Oops!</h1>
    <h2 id="subtitle">parece que algo salió mal. </h2>
    <p id="texto">La página que está buscando no existe o no está disponible temporalmente.</p>
    <button type="button" id="errorbutton">
        <a href="{{url('/')}}" id="errortext"> regresar</a>    
    </button>
    
    <script src="js/plantilla.js"></script>
</div>
</main>