<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noty - Inicio</title>
    <link rel="icon" type="image/x-icon" href="/../../assets/images/notyIcon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="/../../css/root.css">
    <link rel="stylesheet" href="/../../css/register.css">
</head>

<body>
    <main class="main">
        <div class="register-form">
            <h2>Crear cuenta</h2>
            <form method="POST" action="/register">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Contraseña">
                <button type="submit">Registrarse</button>
            </form>
            <p>¿Ya tienes una cuenta? <a href="/login">Iniciar sesión</a></p>
        </div>
    </main>
</body>

</html>