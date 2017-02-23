<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        .logo {
            width: 100px;
            height: 53px;
        }
    </style>
</head>
<body>
    <img src="http://www.redgesam.cl/wp2/wp-content/uploads/2013/06/email-sticker.jpg" class="logo">
    <h2>Hola {{ $name }}, bienvenido a Conciviles</h2>
    <p>Por favor confirma tu correo electrónico.</p>
    <p>Para ello solo debes dar click en el siguiente enlace:</p>
    <a href="{{ url('register/verify/' . $confirmation_code) }}">Click para confirmar tu correo electrónico</a>
</body>
</html>