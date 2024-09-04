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
        <p> Centro de servicio social Programación de reunión. <b>{{ $details['nombre_proyecto'] }}</b></p>
        <p>
            Se le notifica que se ha programado una reunion de discusión del proyecto <b>{{ $details['nombre_proyecto'] }}</b>, con los siguientes detalles:
            </p><br>
        <p>
            <b>Motivo: </b> {{$details['descripcion']}} </p>
        <p> 
            <b>Lugar:</b> {{ $details['lugar'] }}
        </p>
        <p> 
            <b>Fecha:</b> {{ $details['fecha'] }}
        </p>
        <p> 
            <b>Hora:</b> {{ $details['hour'] }}
        </p>
        <p> 
            <b>Encargado:</b> {{ $details['encargado']}}        </p>
        <p>
            Atentamente, Centro de Servicio Social.
        </p><br><br>
    </div>
    <p style="color:gray">
        Centro de servicio social, UCA | css@uca.edu.sv <br>
        Tel. 2210-6600 ext.427 | 2210-6680
    </p>
</body>
</html>