<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
</head>

<body>

    <main class="main">

    <div class="login-form">
        <h2>Crear cuenta</h2>
    </div>

    </main>

    <h2>Iniciar sesión</h2>
    <form method="POST" action="/login">
        <input type="text" name="email" placeholder="Correo">
        <input type="password" name="password" placeholder="Contraseña">
        <button type="submit">Entrar</button>
    </form>

</body>

</html>