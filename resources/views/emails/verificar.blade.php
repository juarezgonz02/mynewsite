<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="color:black; font-size: 1.2em">
        <p>
            <p> Estimado/a {{ $details['user']->nombres }} {{ $details['user']->apellidos }}</p>
                Usted ha creado una cuenta nueva en el sistema de proyectos para horas sociales CSS, ingrese este código para verificar su cuenta y establecer su contraseña.
                <br>
                    <p style="color:red; font-size: 2em" > {{$details['user'] -> utoken -> token}}</p>
            </p><br>
            <p> Puede verificar su cuenta a traves de este enlace: 
                <b style="font-size: 1.1em"><a href="{{ url('verificar_usuario/'.$details['user']->correo) }}"> Click aquí </a></b>
            </p>
        <p> 
            Atentamente, Centro de Servicio Social.
        </p><br><br>
    </div>
    
    <p style="color:gray">
        Este es un correo autogenerado por el sistema. No responder <br>
        Centro de servicio social, UCA | css@uca.edu.sv <br>
        Tel. 2210-6600 ext.427 | 2210-6680
    </p>
</body>
</html>
