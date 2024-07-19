<?php
    $ruta = App\Http\Controllers\UserController::ruta(2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="color:black">
        <p> Estimado/a estudiante.</p>
        <p>
            Lamentamos informarle ha sido removido del proyecto de horas sociales: <b>{{ $details['data']->proyecto }}</b>
            <br>
            Agradecemos su interés mostrado y le invitamos a formar parte de otros proyectos disponibles.

            Puede revisar los proyectos disponibles para usted en el siguiente enlace: <br>
            <a href="<?php echo $ruta; ?>"><?php echo $ruta; ?></a>
        </p><br>
        <p>
            Atentamente, Centro de Servicio Social.
        </p><br><br>
    </div>
    
    <p style="color:gray">
        Este es un correo autogenerado por el sistema. <br>
        Centro de servicio social, UCA | css@uca.edu.sv <br>
        Tel. 2210-6600 ext.427 | 2210-6680
    </p>
</body>
</html>