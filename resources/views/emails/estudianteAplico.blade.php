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
        <p> Estimado/a {{ $details['user']->encargado }}.</p>
        <div>
            Un estudiante ha aplicado al proyecto de horas sociales a su cargo <br>
                <b>Proyecto: {{ $details['user']->nombre }} </b> <br><br>

                <p>
                Información del estudiante: 
                </p>
                
                <p style="padding-left: 1em">
                Nombre: {{ $details['user']->nombres }}.<br>
                Apellidos: {{ $details['user']->apellidos }}.<br>
                Correo: {{ $details['user']->correo }}<br>
                Carrera: {{ $details['user']->n_carrera }}.<br><br>
                </p>

        </div><br>
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