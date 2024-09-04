<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="color:black">
        <p> Estimado/a {{ $details['data']->nombres }} {{ $details['data']->apellidos }},</p>
        <p>
            Se le notifica que su solicitud para el siguiente proceso de aplicación al proyecto de horas sociales: <br>
            <span> <b> {{ $details['data']->nombre }} <b> </span> <b style="color:red">ha sido rechazada.</b> <br>
        </p>
        <p>
            Si desea más información por favor ponerse en contácto con {{ $details['data']->encargado }} {{ $details['data']->correo_encargado }}.
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