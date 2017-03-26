<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        .logo {
            max-width: 144px;
        }
    </style>
</head>
<body>
    <img src="{{ asset('/images/logo/logo.png') }}" alt="Conciviles Logo" class="logo">
    <h3>Hola {{ $name }}, bienvenido a Conciviles</h3>
    <p>Por favor confirma tu correo electrónico.</p>
    <p>Para ello solo debes dar click en el siguiente enlace:</p>
    <a href="{{ url('register/verify/' . $confirmation_code) }}">Clic aquí para confirmar tu correo electrónico</a>
</body>
</html>