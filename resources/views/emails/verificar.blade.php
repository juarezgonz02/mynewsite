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
        <p> Estimado/a {{ $user->nombres }} {{ $user->apellidos }}</p>
        <p style="font-size: 1.3em">
            Usted ha creado una cuenta nueva en el sistema de proyectos para horas sociales CSS, ingrese este código para verificar su cuenta y establecer su contraseña.
            <br>
                <p style="color:red; font-size: 2em" > {{$user -> utoken -> token}}</p>
            <br>
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
