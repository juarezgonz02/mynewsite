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
        <p> Estimado/a {{ $details['user']->nombres }},</p>

        @if(isset($details['token']))
            <p>
                Se solicitó un cambio de contraseña, ingrese al siguiente enlace para restablecerla.
            </p>
            <p>
               Código de restablecimiento: <span style="color: red; font-size: 1.3em"> {!!$details['token']!!} </span>.
            </p>
            <span style="color: blue; font-size: 1em" ><a href="{{ url('/cambiar_contra_olvidada/').'/'.$details['token'] }}"> Restablecer contraseña </a></span>
        @endif

        <p>
            Se le recuerda que este proceso puede realizarse <b style="color:red">una vez por día.</b>
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