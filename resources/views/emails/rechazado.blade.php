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
            Recibimos su solicitud para el siguiente proceso de aplicación al proyecto de horas sociales: <br>
            <span> <b> {{ $details['data']->nombre }} <b> </span> <br>
        Agradecemos mucho su interés en el proyecto, sin embargo, después de evaluar su solicitud, 
        se ha tomado la decisión de optar por otros perfiles para este proyecto. 
        <br>
        Le animamos a considerar aplicar a otros desde la platafoma.
        </p>

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